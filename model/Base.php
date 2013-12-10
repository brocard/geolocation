<?php
class Base{

    private static $basePath;
    
	public static function autoload($className){
        if( file_exists( self::$basePath . 'model/'.$className.'.php' ) ){
            include( self::$basePath . 'model/'.$className.'.php');
        }
	}
	
    public static function Init(){
        //cargamos time al iniciar ejecucion
        self::$basePath='./';
    }

    public static function getBasePath( ){
        return self::$basePath;
    }

    public static function setBasePath( $basePath ){
        self::$basePath = rtrim($basePath,'/') . '/';
    }

    //devuelve time para poder calcular tiempo de ejecucion
    public static function mtime(){
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

    //devuelve el tiempo de ejecucion transcurrido desde el inicio
    public static function exec_time(){        
        return round((self::mtime())-(self::$start_time),4);
    }

    //devuelve el tiempo de ejecucion transcurrido desde el tiempo q se pase como parametro
    public static function partial_time($time){        
        return round(self::mtime()-$time,4);
    } 

    public static function trace( $msg, $force=false, $extra = array() ) {
        if( !$force ) {
            if( !APP_DEBUG ) {
                return true;
            }
        }
        echo self::$start_time . '   =>   ' . Base::exec_time() . ' : ' . $msg . BR;
        if( !empty( $extra ) ) {
            foreach ($extra as $key => $value) {
                echo $key . ': ' . $value . '; ';
            }
            echo BR;
        }
    }

}

spl_autoload_register(array('Base','autoload'));

Base::Init();