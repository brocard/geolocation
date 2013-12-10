<?php
/**
 * Description of Modeldb
 *
 * @author Yusniel Brocard <brocard@gmail.com>
 */
class Modeldb extends db {
    
    protected $conexionActiva = 'local';
    private static $_models;
    
    public function __construct() {
        parent::__construct();
    }
    
    public static function model($className=__CLASS__)
	{
		if(isset(self::$_models[$className]))
			return self::$_models[$className];
		else
		{
			$model=self::$_models[$className]=new $className(null);
			return $model;
		}
	}
    
}
