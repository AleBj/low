<?php

 
namespace Nucleo\Controller;

use Nucleo\Registro\Registro;
use Nucleo\View\View;
//use Modelos\modelos;
use \Exception;

abstract class Controller
{
    protected $_view;
	protected $_sess;
	protected $_conf;
	protected $_acl;

    
    public function __construct() 
    {
        $this->_view = new View();
		$this->_sess = Registro::tomarInstancia()->_sess;
		$this->_conf = Registro::tomarInstancia()->_conf;
		$this->_acl  = Registro::tomarInstancia()->_acl;
		$this->loadModelosAR();
    }
    
    abstract public function index();
    
	protected function loadModelosAR()
	{
		$rutaModeloAr = RAIZ . 'modelos' . DS . 'modelos.php';
		
		if(is_readable($rutaModeloAr)){
			require_once $rutaModeloAr;
		}else{
			throw new Exception('Error de Carga de Modelos AR');
		}
	}

    protected function loadModel($modelo, $modulo = false)
    {
        $modelo = $modelo . 'Model';
        $rutaModelo = RAIZ . 'models' . DS . $modelo . '.php';
        
        if(!$modulo){
            $modulo = Registro::tomarInstancia()->_request->getModulo();
        }
        
        if($modulo){
           if($modulo != 'default'){
               $rutaModelo = RAIZ . 'modules' . DS . $modulo . DS . 'models' . DS . $modelo . '.php';
           } 
        }
        
        if(is_readable($rutaModelo)){
            require_once $rutaModelo;
            $modelo = new $modelo;
            return $modelo;
        }else {
            throw new Exception('Error de modelo');
        }
    }
    
    protected function getLibrary($libreria)
    {
        $rutaLibreria = RAIZ . 'libs' . DS . $libreria . '.php';
        
        if(is_readable($rutaLibreria))
            require_once $rutaLibreria;
        		else throw new Exception('Error de libreria'); 
    }
	
    protected function redireccionar($ruta = false)
    {
        if($ruta){
            header('location:' . Registro::tomarInstancia()->_conf['url_enlace'] . $ruta);
            exit;
        }else{
            header('location:' . Registro::tomarInstancia()->_conf['url_enlace']);
            exit;
        }
    }
	
	protected function recargar()
	{		
       //$this->redireccionar($_SERVER['HTTP_REFERER']);
	   echo $_SERVER['PHP_SELF'];
		
	}
	
}