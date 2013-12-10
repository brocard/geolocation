<?php
/**
 * Description of Usuario
 *
 * @author Yusniel Brocard <ybrocard@jobomas.com>
 */
class Pais extends Clasifimasdb {

    public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    public function getAll( $limit = 0 ){
        if( $limit > 0 ){
            $limit = 'limit ' . $limit;
        } else {
            $limit = '';
        }
        $qry = "SELECT * FROM PAIS WHERE Estado=1 order by Nombre" . $limit;
        return $this->query( $qry );
    }
    
    public function getByAbreviatura( $abrev ){
        $qry = "SELECT * FROM PAIS WHERE Abreviatura='" . addcslashes( $abrev, "'" ) . "' limit 1" ;
        return $this->qryRow( $qry );
    }
    
}