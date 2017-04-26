<?php

function sync_content(&$a) {
// sync/all
	$profiles = array();

  $r = q("select xchan_url from xchan where xchan_network = 'friendica-over-diaspora'");

  foreach($r as $row) $profiles[$row['xchan_url']] = $row['xchan_url'];
  $results = array_values($profiles);
  
  $data = array(
    'now' => datetime_convert('UTC','UTC','now'),
    'count' => count($results),
    'results' => $results
  );
  
json_return_and_die($data);

}
