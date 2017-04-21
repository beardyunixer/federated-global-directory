<?php
function submit_init(&$a) {
	$url = hex2bin(notags(trim($_GET['url'])));
	$where = parse_url($url, PHP_URL_HOST);
	$who = str_replace('/profile/', '', parse_url($url, PHP_URL_PATH));
	$webbie = $who . '@' . $where;
	discover_by_webbie($webbie);
}
