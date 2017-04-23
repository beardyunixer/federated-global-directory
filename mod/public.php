<?php

require_once('include/conversation.php');

function public_content(&$a, $update = 0, $load = false) {

	if($load)
		$_SESSION['loadtime'] = datetime_convert();


	if(get_config('system','block_public') && (! get_account_id()) && (! remote_channel())) {
			return login();
	}


	if(get_config('system','disable_discover_tab'))
		return;

	if(! $update) {

		$maxheight = get_config('system','home_divmore_height');
		if(! $maxheight)
			$maxheight = 75;

		$o .= '<div id="live-public"></div>' . "\r\n";
		$o .= "<script> var profile_uid = " . ((intval(local_user())) ? local_user() : (-1)) 
			. "; var profile_page = " . $a->pager['page'] 
			. "; divmore_height = " . intval($maxheight) . "; </script>\r\n";

		$a->page['htmlhead'] .= replace_macros(get_markup_template("build_query.tpl"),array(
			'$baseurl' => z_root(),
			'$pgtype'  => 'public',
			'$uid'     => ((local_user()) ? local_user() : '0'),
			'$gid'     => '0',
			'$cid'     => '0',
			'$cmin'    => '0',
			'$cmax'    => '99',
			'$star'    => '0',
			'$liked'   => '0',
			'$conv'    => '0',
			'$spam'    => '0',
			'$fh'      => '1',
			'$nouveau' => '0',
			'$wall'    => '0',
			'$list'    => '0',
			'$page'    => (($a->pager['page'] != 1) ? $a->pager['page'] : 1),
			'$search'  => '',
			'$order'   => 'comment',
			'$file'    => '',
			'$cats'    => '',
			'$tags'    => '',
			'$dend'    => '',
			'$mid'     => '',
			'$verb'     => '',
			'$dbegin'  => ''
		));
	}

	if($update && ! $load) {
		// only setup pagination on initial page view
		$pager_sql = '';
	}
	else {
		$a->set_pager_itemspage(20);
		$pager_sql = sprintf(" LIMIT %d OFFSET %d ", intval($a->pager['itemspage']), intval($a->pager['start']));
	}

	require_once('include/identity.php');
	require_once('include/security.php');

	if(get_config('system','site_firehose')) {
		$uids = " and item.uid in ( " . stream_perms_api_uids(PERMS_PUBLIC) . " ) and item_private = 0  and (item_flags & " . intval(ITEM_WALL) . " ) > 0 ";
	}
	else {
		$sys = get_sys_channel();
		$uids = " and item.uid  = " . intval($sys['channel_id']) . " ";
		$sql_extra = item_permissions_sql($sys['channel_id']);
		$a->data['firehose'] = intval($sys['channel_id']);
	}



	$page_mode = 'list';

	$simple_update = (($update) ? " and item.item_unseen = 1 " : '');

	if($update && $_SESSION['loadtime'])
		$simple_update = " AND (( item_unseen = 1 AND item.changed > '" . datetime_convert('UTC','UTC',$_SESSION['loadtime']) . "' )  OR item.changed > '" . datetime_convert('UTC','UTC',$_SESSION['loadtime']) . "' ) ";
	if($load)
		$simple_update = '';

	//logger('update: ' . $update . ' load: ' . $load);

	if($update) {

		$ordering = "commented";

		if($load) {

			// Fetch a page full of parent items for this page

			$r = q("SELECT distinct item.id AS item_id, $ordering FROM item
				left join abook on item.author_xchan = abook.abook_xchan
				WHERE true $uids AND item.item_restrict = 0
				AND item.parent = item.id
				and ((abook.abook_flags & %d) = 0 or abook.abook_flags is null)
				$sql_extra3 $sql_extra $sql_nets
				ORDER BY $ordering DESC $pager_sql ",
				intval(ABOOK_FLAG_BLOCKED)
			);


		}
		elseif($update) {

			$r = q("SELECT distinct item.id AS item_id, $ordering FROM item
				left join abook on item.author_xchan = abook.abook_xchan
				WHERE true $uids AND item.item_restrict = 0
				AND item.parent = item.id $simple_update
				and ((abook.abook_flags & %d) = 0 or abook.abook_flags is null)
				$sql_extra3 $sql_extra $sql_nets",
				intval(ABOOK_FLAG_BLOCKED)
			);
			$_SESSION['loadtime'] = datetime_convert();
		}
		// Then fetch all the children of the parents that are on this page
		$parents_str = '';
		$update_unseen = '';

		if($r) {

			$parents_str = ids_to_querystr($r,'item_id');

			$items = q("SELECT item.*, item.id AS item_id FROM item
				WHERE true $uids AND item.item_restrict = 0
				AND item.parent IN ( %s )
				$sql_extra ",
				dbesc($parents_str)
			);

			xchan_query($items,true,(-1));
			$items = fetch_post_tags($items,true);
			$items = conv_sort($items,$ordering);
		}
		else {
			$items = array();
		}

	}

	// fake it
	$mode = ('network');

	$o .= conversation($a,$items,$mode,$update,$page_mode);

	if(($items) && (! $update))
		$o .= alt_pager($a,count($items));

	return $o;

}
