<?php /** @file */

require_once('boot.php');
require_once('include/cli_startup.php');
require_once('include/dir_fns.php');

function friendicadirpull_run($argv, $argc){

	cli_startup();
	$a = get_app();

	if ($argv[1] == 'pull' && $argv[2] == 'all')
      $target = '/pull/all';
   if ($argv[1] == 'pull' && $argv[2] == 'since')
      $target = 'pull/since/' . $argv[3];

	$dirs = get_friendica_dirs();
	$toss = mt_rand(0,count($dirs) -1);
	$url = $dirs[$toss];

	if ($url && $target) {
		// If this is a RedMatrix/Federated Global Directory, the url
		// contains a /submit.  We need to strip this here.
		if (strpos($url,'submit'))
			$url = str_replace('/submit','',$url);

			$url = $url . '/sync/' . $target;

			$json = z_fetch_url($url);
			$x = json_decode($json['body'],true);
			$results = $x['results'];

			foreach ($results as $result) {
				$who = $result;
				dirsync_friendica($who,1);
			}

		}
}

if (array_search(__file__,get_included_files())===0){
  friendicadirpull_run($argv,$argc);
  killme();
}
