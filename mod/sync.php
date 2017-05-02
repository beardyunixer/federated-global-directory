<?php

function sync_content(&$a) {
	if (argv(1) == 'pull' && argv(2) == 'all')
		$SQL_EXTRA = '';
	if (argv(1) == 'pull' && argv(2) == 'since') {
		$date = datetime_convert(argv(3));
		$SQL_EXTRA = "and xchan_updated > " . intval($date);
	}

	$profiles = array();

  $r = q("select xchan_url from xchan where xchan_network = 'friendica-over-diaspora' $SQL_EXTRA");

  foreach($r as $row) 
	$profiles[$row['xchan_url']] = $row['xchan_url'];

  $results = array_values($profiles);
  
  $data = array(
    'now' => datetime_convert('UTC','UTC','now'),
    'count' => count($results),
    'results' => $results
  );
  
	json_return_and_die($data);
}

