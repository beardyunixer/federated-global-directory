<?php
function submit_init(&$a) {
	$url = hex2bin(notags(trim($_GET['url'])));
	$suppresssync = 0;

	// Friendica adds submit to the URL provided in the admin panel.
	// We can abuse this.  If we tell users to add directory/submit
	// as the URL, updates directly from sites will end up at 
	// directory/submit/submit.  We can use this to detect an update
	// from an end user vs an update from a directory until.
	// This is important because I don't yet see how Friendica prevents
	// loops.  I'm not saying they don't, just I haven't necessarily
	// got my head round it yet...

   if (argv(1) === 'submit');
		$suppresssync = 1;

		dlogger('Sync: ' . $sync);

	if ($url) {
		$where = parse_url($url, PHP_URL_HOST);
		$who = str_replace('/profile/', '', parse_url($url, PHP_URL_PATH));
		$webbie = $who . '@' . $where;
		$import = discover_by_webbie($webbie,$suppresssync);
		dlogger('Webbie: ' . $webbie);

		// FIXME - not entirely convinced this works (read, this probably
		//	does not work.  Revisit when everything is basically working.
			if (! $import) {
				$vcard = scrape_vcard($url);
					if ($vcard['webbie'])
						$import = discover_by_webbie($vcard['webbie'],$suppresssync);
			}
		}

}
