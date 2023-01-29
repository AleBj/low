<?php
use Nucleo\Registro\Registro;

class Pd
{

	private function __construct(){}

	protected static function conexionDB()
	{
		$_conexiones = array();
		$_data = array();
		$_estricto = array('db_host', 'db_usuario', 'db_pass', 'db_nombre', 'db_char');
		$_instancia = Registro::tomarInstancia()->_conf['baseDatos']['actual'];	
		$_bases = Registro::tomarInstancia()->_conf['baseDatos'];
		

		if(!is_array($_bases) || empty($_bases)) throw new Exception("Error: no hay datos de DB cofigurados.");

		foreach($_bases as $clave => $valor){

			if(is_array($valor)){

				if($clave == $_instancia){
					for($e=0;$e<count($_estricto);$e++){
						if(!array_key_exists($_estricto[$e], $valor)) throw new Exception("Error: formato de datos de DB INCOMPATIBLE");
					}

					$db = new PDO("mysql:host=" . $valor['db_host'] . ";" . 
							  "dbname=" . $valor['db_nombre'], 
							  $valor['db_usuario'], 
							  $valor['db_pass'], 
							  	array(
									PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . $valor['db_char']) 
								);			  
					$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
					$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
					$_data[$clave] = $db;
				}

				
			}
		}

		if(isset($_data)){

			$db = null;
			return $_data;
			
		}else throw new Exception("Error: No hay conexiones disponibles");
	}

	public static function instancia()	
	{
		$_instancia =Registro::tomarInstancia()->_conf['baseDatos']['actual'];

		// echo $_instancia;exit;

		// echo"<pre>";print_r($_instancia);echo"</pre>";exit;

		$_conexiones = self::conexionDB();
		if(!array_key_exists($_instancia, $_conexiones)) throw new Exception("Error: la instancia de DB solicitada NO EXISTE");
		return $_conexiones[$_instancia];
	}
}


