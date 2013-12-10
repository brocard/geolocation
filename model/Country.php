<?php
/**
 * Description of Country
 *
 * @author Yusniel Brocard <ybrocard@jobomas.com>
 */
class Country extends Modeldb {

	protected $_table;

    public function __construct() {
        parent::__construct();
        $this->_table = 'country';
    }

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	} 
    
    public function getAll($limit = 0)
    {
        $limit = $limit>0 ? 'limit ' . $limit : '';
        $qry = "SELECT * FROM ".$this->_table." WHERE estado=1" . $limit;
        return $this->query( $qry );
    }

    public function insertData( $table, $params=array() )
    {  
    	// todo sanitise with mysql_escape_string()
		foreach ($params as $key => $v) {
		    $val = is_numeric($v) ? $v : "'" . $v . "'";
		    $set .= sprintf("%s=%s%s", $key, $val, ($v == end($params) ? "" : ", "));
		}
        $sql = sprintf("INSERT INTO %s SET %s", $this->_table, $set);
    	return $this->insert($sql);
    }

    public function setInfoToDb( $info = array() )
    {
        $sql = "INSERT INTO country (id, var ) VALUES ($id, '$var')";
        return $this->insert($sql);
    }

    public function executeInsert($sql)
    {
        return $this->insert($sql);
    }
    
}