<?php

function sync_content(&$a) {
// sync/all
  //Find all the profiles.
  $r = q("select xchan_url from xchan where xchan_network = 'friendica-over-diaspora'");

  //This removes the keys, so it's a flat array.  Apparently.
  $results = array_values($r);
  
  //Format it nicely.
  return array(
    'now' => datetime_convert('UTC','UTC','now'),
    'count' => count($results),
    'results' => $results
  );
  
}
