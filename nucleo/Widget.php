<?php

 
namespace Nucleo\Widget;

use Nucleo\Registro\Registro;
use \Exception;

abstract class Widget
{
	private $_conf;
	private $_registro;
	private $_sess;

    protected function loadModel($model)
    {		
		if(is_readable(RAIZ . 'widgets' . DS . 'models' . DS . $model . '.php')){
			
			include_once RAIZ . 'widgets' . DS . 'models' . DS . $model . '.php';
            $modelClass = $model . 'ModelWidget';
            
            if(class_exists($modelClass)){
                return new $modelClass;
            }
        }
        throw new Exception('Error modelo de widget');
    }
    
    protected function render($view, $data = array(), $ext = 'phtml')
    {
		// Enviamos a la vista del widget los datos de configuración
		// y la sesion en curso.
		$this->_registro = Registro::tomarInstancia();
		$this->_sess = $this->_registro->_sess;
		$this->_conf = $this->_registro->_conf;
		
		if(is_readable(RAIZ . 'widgets' . DS . 'views' . DS . $view . '.' . $ext)){
			ob_start();
       		extract($data);
          	include RAIZ . 'widgets' . DS . 'views' . DS . $view . '.' . $ext;
          	$content = ob_get_contents();
          	ob_end_clean();
             
        	return $content;
		}
		throw new Exception('Error vista widget');
    }
}