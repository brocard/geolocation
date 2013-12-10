<?php 
	ini_set('display_errors', true);
	header('Content-Type: text/html; charset=utf-8');

	$list = file_get_contents('http://geo.osclass.org/geo.download.php?action=country&term=all');
	$countries = json_decode($list);

	$new_list = new stdClass();
	foreach ($countries as $country) {
		$new_list = $country;

		$url_regions = 'http://geo.osclass.org/geo.download.php?action=region&country='.$country->name.'&term=all';
		$regions = file_get_contents($url_regions);	
		$new_list->regions = $regions = json_decode($regions);

		$file_r = 'region_'.strtolower($country->id).'.json';
		//
		$server_root = $_SERVER['DOCUMENT_ROOT'];
		//
		$fp = fopen($server_root.'/countries/cache/'.$file_r, 'w+');
		if ($fp) {
			fwrite($fp, json_encode($new_list));
			fclose($fp);
		}
		//sleep(5);
		break;
	}

	echo '<pre>';
		print_r($new_list);
	echo '</pre>';

	// echo '<pre>';
	// 	print_r($countries);
	// echo '</pre>';
	exit();

	//Listado de Paises
	$countries = file_get_contents('countries.json');
	$countries = json_decode($countries, true);

	// echo '<pre>';
	// 	print_r($countries);
	// echo '</pre>';
	// exit();

	$pais=array();
	foreach ($countries as $index => $value) {
		//Abreviatura País	
		$abbr = strtoupper($value['cca2']);
		//Info País	
		$pais[$abbr]['name'] = utf8_decode($value['name']);
		$pais[$abbr]['iso'] = $value['cca2'];
		$pais[$abbr]['currency'] = $value['currency'];
		$pais[$abbr]['callingCode'] = $value['callingCode'];
		$pais[$abbr]['region'] = $value['region'];
		$pais[$abbr]['subregion'] = $value['subregion'];
		$pais[$abbr]['language'] = $value['language'];
		$pais[$abbr]['latlng'] = $value['latlng'];
	}

	echo '<pre>';
		print_r($pais);
	echo '</pre>';

	

?>