<?php

namespace Nucleo\Bootstrap;

use Nucleo\Registro\Registro;
use \Exception;

class Bootstrap
{
    public static function run()
    {
        $controller = Registro::tomarInstancia()->_request->getControlador() . 'Controller';
        $args = Registro::tomarInstancia()->_request->getArgs();
        
        if(Registro::tomarInstancia()->_request->getModulo()){
            $rutaModulo = RAIZ . 'controllers' . DS . Registro::tomarInstancia()->_request->getModulo() . 'Controller.php';

          
            if(is_readable($rutaModulo)){
                require_once $rutaModulo;
                $rutaControlador = RAIZ . 'modules'. DS . Registro::tomarInstancia()->_request->getModulo() . DS . 'controllers' . DS . $controller . '.php';
            }else{
                throw new Exception('Error de base de modulo');
            }
        }else{
            $rutaControlador = RAIZ . 'controllers' . DS . $controller . '.php';
        }
		
        if(is_readable($rutaControlador)){
			
            require_once $rutaControlador;
            $controller = new $controller;
            
            if(is_callable(array($controller, Registro::tomarInstancia()->_request->getMetodo()))){
                $metodo = Registro::tomarInstancia()->_request->getMetodo();
            }else{
                $metodo = 'index';
            }
            
            if(isset($args)){
                call_user_func_array(array($controller, $metodo), $args);
            }else{
                call_user_func(array($controller, $metodo));
            }  
        }else{
			throw new Exception('no encontrado todavia');
			
        }
    }
}