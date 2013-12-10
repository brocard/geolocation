<?php 
	ini_set('display_errors', true);
	header('Content-Type: text/html; charset=utf-8');

	error_reporting(E_ALL ^ E_NOTICE);

	require 'model/Base.php';
	Base::setBasePath( dirname( __FILE__ ) );

	//Memcached
	$mc = new Memcached(); 
	$mc->addServer("localhost", 11211); 


	$countries = Country::model()->getAll();
	foreach ($countries as $k=>$country) {
		if ( $country['code']=='MX' ) {
			$state = 'http://www.geonames.org/childrenJSON?geonameId='.$country['geoname_id'].'&lang=es';
			$state_all = file_get_contents($state);
			$state_all = json_decode($state_all, true);		
			
			// echo '<pre>';
			// 	print_r($state_all);
			// echo '</pre>';

			foreach ( $state_all['geonames'] as $city ) {
				if ( $city['geonameId']=='3521082') {
					$ciudades = 'http://www.geonames.org/childrenJSON?geonameId='.$city['geonameId'].'&lang=es';
					$city_all = file_get_contents($ciudades);
					$city_all = json_decode($city_all, true);

					// echo '<pre>';
					// 	print_r($city_all['geonames']);
					// echo '</pre>';

					foreach ($city_all['geonames'] as $local) {
						if ( $local['geonameId']=='8583050') {
							$localidades = 'http://www.geonames.org/childrenJSON?geonameId='.$local['geonameId'].'&lang=es';
							$local_all = file_get_contents($localidades);
							$local_all = json_decode($local_all, true);

							echo '<pre>';
								print_r($local_all['geonames']);
							echo '</pre>';
						}
					}
				}
			}
		}
	}

	foreach ($state_all['geonames'] as $key => $stat) {
		echo '<pre>';
			print_r( ($key+1). ' -- ' . $stat['name']);
		echo '</pre>';
	}

	// echo '<pre>';
	// 	print_r($countries);
	// echo '</pre>';
	exit();

	
	$prov_all = $provincias_gn['geonames'];

	
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