<?php

namespace Nucleo\Registro;

class Registro
{
	private static $_instancia;
    private $_data;
	
	private function __construct() {}
	
	public static function tomarInstancia()
	{
		if(!self::$_instancia instanceof self){
            self::$_instancia = new Registro();
        }
        return self::$_instancia;
	}
	
	public function __set($name, $value) {
        $this->_data[$name] = $value;
    }
    
    public function __get($name) {
        if(isset($this->_data[$name])){
            return $this->_data[$name];
        }
        return false;
    }
}
