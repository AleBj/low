<?php
namespace Nucleo\Ar;

use ActiveRecord;
use Nucleo\Registro\Registro;

class Ar
{
	private function __construct(){}
	
	public static function arrancar()
	{	
		$conexiones = array(			 
			    Registro::tomarInstancia()->_conf['baseDatos']['actual'] => 
							   'mysql://' .  Registro::tomarInstancia()->_conf['baseDatos'][Registro::tomarInstancia()->_conf['baseDatos']['actual']]['db_usuario'] 
								    . ':' .  Registro::tomarInstancia()->_conf['baseDatos'][Registro::tomarInstancia()->_conf['baseDatos']['actual']]['db_pass'] 
									. '@' .  Registro::tomarInstancia()->_conf['baseDatos'][Registro::tomarInstancia()->_conf['baseDatos']['actual']]['db_host'] 
									. '/' .  Registro::tomarInstancia()->_conf['baseDatos'][Registro::tomarInstancia()->_conf['baseDatos']['actual']]['db_nombre'] 
							. ';charset=' .  Registro::tomarInstancia()->_conf['baseDatos'][Registro::tomarInstancia()->_conf['baseDatos']['actual']]['db_char'],
		);
		
		ActiveRecord\Config::initialize(function($cfg) use ($conexiones)
		{	
			$cfg->set_model_directory(RAIZ . Registro::tomarInstancia()->_conf['directorio_modelos']);
			
			$cfg->set_connections($conexiones);
		
		    // por defecto, ahora es de desarrollo
			$cfg->set_default_connection(Registro::tomarInstancia()->_conf['baseDatos']['actual']);
		});
	}
}