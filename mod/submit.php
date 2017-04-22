<?php
function submit_init(&$a) {
	$url = hex2bin(notags(trim($_GET['url'])));

	if ($url) {
		$where = parse_url($url, PHP_URL_HOST);
		$who = str_replace('/profile/', '', parse_url($url, PHP_URL_PATH));
		$webbie = $who . '@' . $where;
		$import = discover_by_webbie($webbie,1);

		// FIXME - not entirely convinced this works (read, this probably
		//	does not work.  Revisit when everything is basically working.
			if (! $import) {
				$vcard = scrape_vcard($url);
					if ($vcard['webbie'])
						$import = discover_by_webbie($vcard['webbie'],1);
			}
		}

}
