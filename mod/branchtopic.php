<?php

function branchtopic_init(&$a) {

	if(! local_user())
		return;

	$item_id = 0;

	if(argc() > 1)
		$item_id = intval(argv(1));	

	if(! $item_id)
		return;

	$channel = $a->get_channel();

	if(! $channel)
		return;


	$r = q("select * from item where id = %d and uid = %d and owner_xchan = '%s' and id != parent limit 1",
		intval($item_id),
		intval(local_user()),
		dbesc($channel['channel_hash'])
	);

	if(! $r)
		return;

	$p = q("select * from item where id = %d and uid = %d limit 1",
		intval($r[0]['parent']),
		intval(local_user())
	);

	$x = q("update item set parent = id, route = '', item_flags = (item_flags | %d) where id = %d",
		intval(ITEM_THREAD_TOP),
		intval($item_id)
	);

	return;
}
