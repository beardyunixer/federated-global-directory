<?php


function viewsrc_content(&$a) {

	$o = '';

	$sys = get_sys_channel();

	$item_id = ((argc() > 1) ? intval(argv(1)) : 0);
	$json    = ((argc() > 2 && argv(2) === 'json') ? true : false);

	if(! local_user()) {
		notice( t('Permission denied.') . EOL);
	}


	if(! $item_id) {
		$a->error = 404;
		notice( t('Item not found.') . EOL);
	}

	if(local_user() && $item_id) {
		$r = q("select item_flags, body, id from item where item_restrict = 0 and uid in (%d , %d) and id = %d limit 1",
			intval(local_user()),
			intval($sys['channel_id']),
			intval($item_id)
		);

		if($r) {
			if($r[0]['item_flags'] & ITEM_OBSCURED) 
				$r[0]['body'] = crypto_unencapsulate(json_decode($r[0]['body'],true),get_config('system','prvkey')); 
			$o = (($json) ? json_encode($r[0]['body']) : str_replace("\n",'<br />',$r[0]['body']));
		}
	}

	if(is_ajax()) {
		print '<div><i class="icon-pencil"> ' . t('Source of Item') . ' ' . $r[0]['id'] . '</i></div>';
		echo $o;
		killme();
	} 

	return $o;
}

