<?php 
	ini_set('display_errors', true);

	$list = file_get_contents('http://api.geonames.org/countryInfo?username=brocard&type=json&lang=es');

	echo '<pre>';
		print_r(json_decode($list, true));
	echo '</pre>';
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

	// $fp = fopen('results.json', 'w');
	// fwrite($fp, json_encode($response));
	// fclose($fp);

?>