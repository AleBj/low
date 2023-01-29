<?php

use Nucleo\Model\Model;
use \PDO;

class loginModel extends Model
{
    public function __construct() {
        parent::__construct();
    }
    
    public function getUsuario($usuario, $password)
    {
        $datos = $this->_db->query(
                "select * from usuarios " .
                "where usuario = '$usuario' " .
                "and pass = '" . Hash::getHash('sha512', $password, $this->_conf['hash_key']) ."'"
                );
        
        return $datos->fetch();
    }

    

}