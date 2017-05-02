<?php

require_once('include/items.php');
require_once('include/conversation.php');


function home_init(&$a) {

	$ret = array();

	call_hooks('home_init',$ret);

	$splash = ((argc() > 1 && argv(1) === 'splash') ? true : false);

	$channel = $a->get_channel();
	if(local_user() && $channel && $channel['xchan_url'] && ! $splash) {
		$dest = $channel['channel_startpage'];
		if(! $dest)
			$dest = get_pconfig(local_user(),'system','startpage');
		if(! $dest)
			$dest = get_config('system','startpage');
		if(! $dest)
			$dest = z_root() . '/apps';

		goaway($dest);
	}

	if(get_account_id() && ! $splash) {
		goaway(z_root() . '/new_channel');
	}

}


function home_content(&$a, $update = 0, $load = false) {

	require_once('mod/directory.php');
	$o = directory_content($a);
	return $o;
}
