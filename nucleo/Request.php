<?php

namespace Nucleo\Request;

use Nucleo\Registro\Registro;

class Request 
{
	// La marco soporta la aplicacion de modulos.
	// Esto es fundamental en funcion de la idea de colocar
	// todo lo referente a la gestión de cada parte en un
	// mismo modulo independiente del resto.
	private $_modulo;
    private $_controlador;
    private $_metodo;
    private $_argumentos;
    private $_modules;
    
    public function __construct() 
    {
        if(isset($_GET['url'])){
			
            $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            $url = array_filter($url);

			//Modulos de la Aplicaion. Aqui se declaran los
			//modulos que esten activos en la aplicacion.
			$this->_modules = Registro::tomarInstancia()->_conf['modulos'];
			
			if(isset($this->_modules) && count($this->_modules)){
				$this->_modulo = strtolower(array_shift($url));
			}
            
            if(!$this->_modulo){
                $this->_modulo = false;
            }else{
                if(count($this->_modules)){
                    if(!in_array($this->_modulo, $this->_modules)){
                        $this->_controlador = $this->_modulo;
                        $this->_modulo = false;
                    }else{
                        $this->_controlador = strtolower(array_shift($url));
                        
                        if(!$this->_controlador){
                            $this->_controlador = 'index';
                        }
                    }
                }else{
                     $this->_controlador = $this->_modulo;
                     $this->_modulo = false;
                }
            }
            
            $this->_metodo = strtolower(array_shift($url));
            $this->_argumentos = $url;           
        }
        
        if(!$this->_controlador){
            $this->_controlador = Registro::tomarInstancia()->_conf['default_controller'];
        }
        
        if(!$this->_metodo){
            $this->_metodo = 'index';
        }
        
        if(!isset($this->_argumentos)){
            $this->_argumentos = array();
        }
    }
    
    public function getModulo()
    {
        return $this->_modulo;
    }
    
    public function getControlador()
    {
        return $this->_controlador;
    }
    
    public function getMetodo()
    {
        return $this->_metodo;
    }
    
    public function getArgs()
    {
        return $this->_argumentos;
    }
}