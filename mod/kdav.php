<?php 

	/**
	* @brief Dav module for KDE
	* 
	* Allows a KDE user to add shortcuts to Konqeuror
	* which redirect from their own site to another
	* users dav.  The KDE cookie store will perform
	* magic auth during this process, thus providing 
	* the only form of accessing private files from 
	* dav on the desktop
	*
	* Example link to place in Konqeuror's "places": 
	* https://example.com/kdav?f=&url=example2.com/cloud/myfriend
	*/


function kdav_content(&$a) {
	$where = $_REQUEST['url']; 
	if ($where)
		goaway(zid('webdavs://' . $where));
	else
		info('No webdav location specified');
	return; 
}
