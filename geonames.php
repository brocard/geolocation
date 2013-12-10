<?php 
	ini_set('display_errors', true);
	header('Content-Type: text/html; charset=utf-8');

	// $list = file_get_contents('http://api.geonames.org/search?country=mx&username=brocard&style=medium&lang=es&type=json&featureClass=P');
	$list = file_get_contents('http://api.geonames.org/search?username=brocard&country=mx&lang=es&type=json&featureClass=A');
	$list_full = json_decode($list);

	echo '<pre>';
		print_r($list_full);
	echo '</pre>';

?>	 