<?php 
	ini_set('display_errors', true);
	header('Content-Type: text/html; charset=utf-8');

	error_reporting(E_ALL ^ E_NOTICE);

	require 'model/Base.php';
	Base::setBasePath( dirname( __FILE__ ) );

	$dir = dirname(__FILE__).'/cache/';

	echo '<pre>';
		print_r($dir);
	echo '</pre>';

	if(!$dh = @opendir($dir)) return;
    while (false !== ($current = readdir($dh))) {
        if($current != '.' && $current != '..') {
        	if ( stripos($current, '.json') !== false ) {
        		$countries_json = file_get_contents($dir.$current);
				$countries_json = json_decode($countries_json, true);

				echo '<pre>';
					print_r($countries_json);
				echo '</pre>';

				// if ( ! empty($countries_json['province']) ) {
				// 	echo '<pre>';
				// 		print_r($countries_json['province']);
				// 	echo '</pre>';
				// } else {
				// 	printf("No existe provincias para %s - %s <br>", $countries_json['abbr'], $countries_json['name_es']);
				// }
        	}
        }
    }
    closedir($dh);

?>