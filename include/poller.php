<?php /** @file */

require_once('boot.php');
require_once('include/cli_startup.php');


function poller_run($argv, $argc){

	cli_startup();

	$a = get_app();

	$maxsysload = intval(get_config('system','maxloadavg'));
	if($maxsysload < 1)
		$maxsysload = 50;
	if(function_exists('sys_getloadavg')) {
		$load = sys_getloadavg();
		if(intval($load[0]) > $maxsysload) {
			logger('system: load ' . $load . ' too high. Poller deferred to next scheduled run.');
			return;
		}
	}

	$interval = intval(get_config('system','poll_interval'));
	if(! $interval) 
		$interval = ((get_config('system','delivery_interval') === false) ? 3 : intval(get_config('system','delivery_interval')));

	// Check for a lockfile.  If it exists, but is over an hour old, it's stale.  Ignore it.
	$lockfile = 'store/[data]/poller';
	if((file_exists($lockfile)) && (filemtime($lockfile) > (time() - 3600)) 
		&& (! get_config('system','override_poll_lockfile'))) {
		logger("poller: Already running");
		return;
	}
	
	// Create a lockfile.  Needs two vars, but $x doesn't need to contain anything.
	file_put_contents($lockfile, $x);

	logger('poller: start');
	
	// Allow somebody to staggger daily activities if they have more than one site on their server,
	// or if it happens at an inconvenient (busy) hour.

	$h1 = intval(get_config('system','cron_hour'));
	$h2 = intval(datetime_convert('UTC','UTC','now','G'));

	$dirmode = get_config('system','directory_mode');

	/**
	 * Cron Daily
	 *
	 * Actions in the following block are executed once per day, not on every poller run
	 *
	 */

	if(($d2 != $d1) && ($h1 == $h2)) {

		call_hooks('cron_daily',datetime_convert());


		$d3 = intval(datetime_convert('UTC','UTC','now','N'));
		if($d3 == 7) {
		
			/**
			 * Cron Weekly
			 * 
			 * Actions in the following block are executed once per day only on Sunday (once per week).
			 *
			 */

			// Once a week, poll a Friendica directory to pickup anything we missed.

         $date = strtotime(datetime_convert('UTC','UTC','now - 7 days'));
         proc_run('php','include/friendicadirpull.php','pull','since',$date);


			call_hooks('cron_weekly',datetime_convert());


			z_check_cert();

			require_once('include/hubloc.php');
			prune_hub_reinstalls();

			require_once('include/Contact.php');
			mark_orphan_hubsxchans();


			// get rid of really old poco records

			q("delete from xlink where xlink_updated < %s - INTERVAL %s and xlink_static = 0 ",
				db_utcnow(), db_quoteinterval('14 DAY')
			);

			$dirmode = intval(get_config('system','directory_mode'));
			if($dirmode === DIRECTORY_MODE_SECONDARY || $dirmode === DIRECTORY_MODE_PRIMARY) {
				logger('regdir: ' . print_r(z_fetch_url(get_directory_primary() . '/regdir?f=&url=' . urlencode(z_root()) . '&realm=' . urlencode(get_directory_realm())),true));
			}

			/**
			 * End Cron Weekly
			 */
		}

		// If this is a directory server, request a sync with an upstream
		// directory at least once a day, up to once every poll interval. 
		// Pull remote changes and push local changes.
		// potential issue: how do we keep from creating an endless update loop? 

		if($dirmode == DIRECTORY_MODE_SECONDARY || $dirmode == DIRECTORY_MODE_PRIMARY) {
			require_once('include/dir_fns.php');
			sync_directories($dirmode);
		}

		require_once('include/hubloc.php');
		remove_obsolete_hublocs();

		/**
		 * End Cron Daily
		 */
	}

	// update any photos which didn't get imported properly
	// This should be rare

	$r = q("select xchan_photo_l, xchan_hash from xchan where xchan_photo_l != '' and xchan_photo_m = '' 
		and xchan_photo_date < %s - INTERVAL %s",
		db_utcnow(), 
		db_quoteinterval('1 DAY')
	);
	if($r) {
		require_once('include/photo/photo_driver.php');
		foreach($r as $rr) {
			$photos = import_profile_photo($rr['xchan_photo_l'],$rr['xchan_hash']);
			$x = q("update xchan set xchan_photo_l = '%s', xchan_photo_m = '%s', xchan_photo_s = '%s', xchan_photo_mimetype = '%s'
				where xchan_hash = '%s'",
				dbesc($photos[0]),
				dbesc($photos[1]),
				dbesc($photos[2]),
				dbesc($photos[3]),
				dbesc($rr['xchan_hash'])
			);
		}
	}

	reload_plugins();

	if($dirmode == DIRECTORY_MODE_SECONDARY || $dirmode == DIRECTORY_MODE_PRIMARY) {
		$r = q("SELECT u.ud_addr, u.ud_id, u.ud_last FROM updates AS u INNER JOIN (SELECT ud_addr, max(ud_id) AS ud_id FROM updates WHERE ( ud_flags & %d ) = 0 AND ud_addr != '' AND ( ud_last = '%s' OR ud_last > %s - INTERVAL %s ) GROUP BY ud_addr) AS s ON s.ud_id = u.ud_id ",
			intval(UPDATE_FLAGS_UPDATED),
			dbesc(NULL_DATE),
			db_utcnow(), db_quoteinterval('7 DAY')
		);
		if($r) {
			foreach($r as $rr) {

				// If they didn't respond when we attempted before, back off to once a day
				// After 7 days we won't bother anymore

				if($rr['ud_last'] != NULL_DATE)
					if($rr['ud_last'] > datetime_convert('UTC','UTC', 'now - 1 day'))
						continue;
				proc_run('php','include/onedirsync.php',$rr['ud_id']);
				if($interval)
					@time_sleep_until(microtime(true) + (float) $interval);
			}
		}
	}

	//All done - clear the lockfile	
	@unlink($lockfile);

	return;
}

if (array_search(__file__,get_included_files())===0){
  poller_run($argv,$argc);
  killme();
}
