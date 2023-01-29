<?php

namespace BaseDatos;

class BaseDatos
{
	public static function data()
	{
		return array(
				// Base de datos actualmente conectada
				'actual' => 'primaria',
				
				// Bases de datos disponibles
				'primaria' => array(
								'db_host' 		=> 'localhost',
								'db_usuario'	=> 'qd_webmaster',
								'db_pass' 		=> 'gzqqJMhB12NT',
								'db_nombre' 	=> 'quickdivorce2022',
								'db_char' 		=> 'utf8',
							),
				'secundaria' => array(
								'db_host' 		=> 'localhost',
								'db_usuario'	=> 'root',
								'db_pass' 		=> 'lucho77',
								'db_nombre' 	=> 'quickdivorce',
								'db_char' 		=> 'utf8',
							)
																	
				);		
	}
}
