<?php
namespace Nucleo\Session;

use Nucleo\Registro\Registro;
use \Exception;

class Session
{
	public function __construct()
	{
		session_start();
        self::tokenCsrf();
	}
	
    /*public function init()
    {
        session_start();
    }*/
    
    public function destroy($clave = false)
    {
        if($clave){
            if(is_array($clave)){
                for($i = 0; $i < count($clave); $i++){
                    if(isset($_SESSION[$clave[$i]])){
                        unset($_SESSION[$clave[$i]]);
                    }
                }
            }else{
                if(isset($_SESSION[$clave])){
                    unset($_SESSION[$clave]);
                }
            }
        }else{
            session_destroy();
        }
    }
    
    public function set($clave, $valor)
    {
        if(!empty($clave))
        $_SESSION[$clave] = $valor;
    }
	
	public function setMulti($_claveUno, $_valor)
	{
		if(!empty($_claveUno))
			return $_SESSION[$_claveUno][]=$_valor;
			//return array_push($_SESSION[$_claveUno],$_valor);
	}
    
    public function get($clave)
    {
        if(isset($_SESSION[$clave]))
            return $_SESSION[$clave];
    }
	
	public function setFirst($_clave, $_valor)
	{
		if(!empty($_clave))
			return array_unshift($_SESSION[$_clave],$_valor);
	}
	
	public function setUnique($_clave)
	{
		if(!empty($_clave))
			return array_unique($_SESSION[$_clave]);
	}
	
	public function getArray($clave, $_cla = false)
    {
        if(isset($_SESSION[$clave])){
			if($_cla != false && isset($_SESSION[$clave][$_cla])){
				 return $_SESSION[$clave][$_cla];
			}
		}    
    }
    
    public function acceso($level)
    {
        if(!$this->get('autenticado')){
            header('location:' . Registro::tomarInstancia()->_conf['url_enlace'] . 'error/acceso/' . Registro::tomarInstancia()->_conf['errores']['acceso']['autenticado']);
            exit;
        }

        if($this->get('autenticado') != $this->get('_csrf')){
            header('location:' . Registro::tomarInstancia()->_conf['url_enlace'] . 'error/acceso/' . Registro::tomarInstancia()->_conf['errores']['acceso']['autenticado']);
            exit();
        }
        
        $this->tiempo();
        
        if($this->getLevel($level) > $this->getLevel($this->get('level'))){
            header('location:' . Registro::tomarInstancia()->_conf['url_enlace'] . 'error/acceso/' . Registro::tomarInstancia()->_conf['errores']['acceso']['nivel']);
            exit;
        }
    }
    
    public function accesoView($level)
    {
        if(!$this->get('autenticado')){
            return false;
        }

        if($this->get('autenticado') != $this->get('_csrf')) {
            return false;
        }
        
        if($this->getLevel($level) > $this->getLevel($this->get('level'))){
            return false;
        }
        return true;
    }
    
    public function getLevel($level)
    {
        $role['admin'] = 3;
        $role['especial'] = 2;
        $role['usuario'] = 1;
        
        if(!array_key_exists($level, $role)){
            throw new Exception('Error de acceso');
        }else{
            return $role[$level];
        }
    }
    
    public function accesoEstricto(array $level, $noAdmin = false)
    {
        if(!$this->get('autenticado')){
            header('location:' . Registro::tomarInstancia()->_conf['url_enlace'] . 'error/acceso/' . Registro::tomarInstancia()->_conf['errores']['acceso']['extricto']);
            exit;
        }
        
        if($this->get('autenticado') != $this->get('_csrf')){
            header('location:' . Registro::tomarInstancia()->_conf['url_enlace'] . 'error/acceso/' . Registro::tomarInstancia()->_conf['errores']['acceso']['extricto']);
            exit();
        }

        $this->tiempo();
        
        if($noAdmin == false){
            if($this->get('level') == 'admin'){
                return;
            }
        }
        
        if(count($level)){
            if(in_array($this->get('level'), $level)){
                return;
            }
        }
        header('location:' . Registro::tomarInstancia()->_conf['url_enlace'] . 'error/acceso/' . Registro::tomarInstancia()->_conf['errores']['acceso']['extricto']);
    }
    
    public function accesoViewEstricto(array $level, $noAdmin = false)
    {
        if(!$this->get('autenticado')){
            return false;
        }
        
        if($this->get('autenticado') != $this->get('_csrf')) {
            return false;
        }

        if($noAdmin == false){
            if($this->get('level') == 'admin'){
                return true;
            }
        }
        
        if(count($level)){
            if(in_array($this->get('level'), $level)){
                return true;
            }
        }
        return false;
    }
    
    public function tiempo()
    {
        if(!$this->get('tiempo') || !Registro::tomarInstancia()->_conf['tiempo_sesion']){
            throw new Exception('No se ha definido el tiempo de sesion'); 
        }
        
        if(Registro::tomarInstancia()->_conf['tiempo_sesion'] == 0){
            return;
        }
        
        if(time() - $this->get('tiempo') > (Registro::tomarInstancia()->_conf['tiempo_sesion'] * 60)){
           /* $this->destroy();
            header('location:' . Registro::tomarInstancia()->_conf['url_enlace'] . 'error/acceso/' . Registro::tomarInstancia()->_conf['errores']['acceso']['tiempo']);*/
        }
        else{
     		$this->set('tiempo', time());
        }
    }
	
	public function tiempoActividad($_clave, $_variable)
	{
		//$this->destroy($_variable);
		$inactivo=1800;
		if($this->get($_clave)){
			$actividad = time() - $this->get($_clave);
			if($actividad > $inactivo){
				$this->destroy($_variable);
			}
		}
		
	}

    public function tokenCsrf()
    {
        if(!$this->get('_csrf')) $this->set('_csrf', $this->generateRandomString(40, 50));
    }

    public function generateRandomString($_min, $_max)
    {
        $_min = (int) $_min;
        $_max = (int) $_max;
        $_final = rand($_min, $_max);

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for($i=0;$i<$_final;$i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}