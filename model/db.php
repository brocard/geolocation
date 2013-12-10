<?php
/**
 * Description of db
 *
 * @author Yusniel Brocard <brocard@gmail.com>
 */
class db {
    protected $conexiones = array(
            'local'    => array(
                'host' => "localhost",
                'user' => "root",
                'pass' => "qazxsw123",
                'db'   => 'geolocation'
            )
        );
    
    protected $conexionActiva;
    protected $link;

    public function __construct() {
        $this->connect();
    }
    
    protected function connect()
    {
        mysql_query("SET NAMES utf8");
        if( !array_key_exists( $this->conexionActiva, $this->conexiones) ){
            die( $this->conexionActiva . ' doesn\'t defined' );
        }
        if ( 
            !( 
                $this->link=mysql_connect( 
                    $this->conexiones[ $this->conexionActiva ]['host'] ,
                    $this->conexiones[ $this->conexionActiva ]['user'] ,
                    $this->conexiones[ $this->conexionActiva ]['pass']
                    ) 
                ) 
            ){
            echo 'ERROR DE CONEXION A ' . $this->conexionActiva ;
            echo ' - '.mysql_errno($this->link).' - '.mysql_error($this->link);
            return false;
        }else{
            if ( !mysql_select_db( $this->conexiones[ $this->conexionActiva ]['db'] ,$this->link ) ){
                echo "Error seleccionando la base de datos.";
                return false;
            }
            return true;
        }
    }
    
    public function qryAll( $tabla ){
        $qry="Select * from $tabla WHERE Estado=1";    
        return $this->query( $qry );
    }

    public function qryRow( $qry ){
        $res = $this->query( $qry );
        if( !empty( $res ) && is_array( $res ) ){
            return current( $res );    
        } 
        return array();
    }

    public function insert( $qry ){
        $res=mysql_query( $qry, $this->link );
        if( !$res ){
            echo mysql_error( $this->link );
        }
        return true;
    }

    public function update( $qry ){
        $res=mysql_query( $qry, $this->link );
        if( !$res ){
            echo mysql_error( $this->link );
        }
        return true;
    }

    public function query( $qry ){
        $data = array();
        $res=mysql_query( $qry, $this->link );
        if( !$res ){
//            echo $qry.'<br/>';
            echo mysql_error( $this->link );
        }
        while( $row = mysql_fetch_assoc( $res ) ) {
            $data[] = $row;
        }
        mysql_free_result( $res );
        return $data;
    }

    public function columns( $tabla ){
        $qry = "SHOW FULL COLUMNS FROM $tabla";
        return $this->query( $qry );
    }
}

