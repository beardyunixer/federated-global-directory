<?php 

function kdav_content(&$a) {
	$where = $_REQUEST['url']; 
	if ($where)
		goaway(zid('webdavs://' . $where));
	else
		info('No webdav location specified');
	return; 
}
