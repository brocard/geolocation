<?php 
	ini_set('display_errors', true);
	header('Content-Type: text/html; charset=utf-8');

	error_reporting(E_ALL ^ E_NOTICE);

	require 'model/Base.php';
	Base::setBasePath( dirname( __FILE__ ) );

	//Memcached
	$mc = new Memcached(); 
	$mc->addServer("localhost", 11211); 

	//$mc->delete("countries_osclass"); 
	//Service Geo Osclass Country
	$countries_os = '';
	$countries_os = $mc->get("countries_osclass"); 	
	if ( empty($countries_os) ) {
		$list = file_get_contents('http://geo.osclass.org/geo.download.php?action=country&term=all');
		$countries_os = json_decode($list);
		$mc->set("countries_osclass", $countries_os, time()+86400); 			
	}

	//File .json
	$countries_json = file_get_contents('countries.json');
	$countries_json = json_decode($countries_json, true);

	$datos_extras = array();
	foreach ($countries_json as $cjson) {
		$datos_extras[$cjson['cca2']] = $cjson;
	}

	// echo '<pre>';
	// 	print_r($datos_extras);
	// echo '</pre>';

	//Service Geo Geonames	
	$countries_gn = '';
	$countries_gn = $mc->get("countries_geonames"); 	
	if ( empty($countries_gn) ) {
		$list_gn = file_get_contents('http://api.geonames.org/countryInfo?username=brocard&type=json&lang=es');
		$countries_gn = json_decode($list_gn);
		$countries_gn = $countries_gn->geonames;
		$mc->set("countries_geonames", $countries_gn, time()+86400);
	}

	// echo '<pre>';
	// 	print_r($countries_gn);
	// echo '</pre>';
	// exit();

	$new = array();
	//Recorrer array paises Geonames
	foreach ($countries_gn as $key => $country) {

		//Recorrer array paises Osclass
		foreach ($countries_os as $k => $cos) {
			//Crear arreglo final
			if ( $country->countryCode == $cos->id ) {
				$new[$cos->id]['abbr'] = $cos->id;				
				$new[$cos->id]['name_en'] = $cos->name;				
				$new[$cos->id]['name_es'] = $country->countryName;				
				//Datos Extras
				$new[$cos->id]['currency'] = $country->currencyCode;				
				$new[$cos->id]['callingCode'] = $datos_extras[$cos->id]['callingCode'];				
				//Continente
				$new[$cos->id]['continent'] = $country->continent;				
				$new[$cos->id]['continente_name'] = $country->continentName;
				$new[$cos->id]['geoname_id'] = $country->geonameId;

				//Service Geo Osclass Country
				$pais = strtolower($cos->id);
				//$mc->delete("reg_osclass_".$pais);
				$region_os = $mc->get("reg_osclass_".$pais); 	
				if ( empty($region_os) ) {
					$url_regions = 'http://geo.osclass.org/geo.download.php?action=region&country_code='.$cos->id.'&term=all';
					$regions = file_get_contents($url_regions);	
					$region_os = json_decode($regions, true);
					$mc->set("reg_osclass_".$pais, $region_os, time()+86400 ); 			
				}
				$new[$cos->id]['province'] = isset($region_os['error']) ? array() : $region_os;

				if ( isset($region_os['error'])) {
					//geonameId
					$prov_all = $mc->get("prov_geo_".$pais); 	
					if ( empty($prov_all) ) {
						$url_provincias = 'http://www.geonames.org/childrenJSON?geonameId='.$country->geonameId.'&lang=es';
						$provincias = file_get_contents($url_provincias);	
						$provincias_gn = json_decode($provincias, true);
						$prov_all = $provincias_gn['geonames'];
						//Guardar en Memcached
						$mc->set("prov_geo_".$pais, $prov_all, time()+86400 ); 			
					}
					$new[$cos->id]['province'] = $prov_all;
				}				
			}
		}
	}

	echo '<pre>';
		print_r($new);
	echo '</pre>';
	exit();

	foreach ($new as $country) {
		//Insert into database
		Country::model()->insertData('country', array(
				'nombre' => utf8_decode($country['name_es']),
				'nombre_en' => utf8_decode($country['name_en']),
				'code' => $country['abbr'], 
				'code_call' => $country['callingCode'], 
				'geoname_id' => $country['geoname_id'], 
			)
		);
		$file_r = 'region_'.strtolower($country['abbr']).'.json';
		//Document root file
		$server_root = $_SERVER['DOCUMENT_ROOT'];
		//Escribir en archivo, sino existe lo crea
		echo '<pre>';
			print_r($server_root.'/countries/cache/'.$file_r);
		echo '</pre>';

		$fp = fopen($server_root.'/countries/cache/'.$file_r, 'w+');
		if ($fp) {
			fwrite($fp, json_encode($country));
			fclose($fp);
		}
	}	

?>