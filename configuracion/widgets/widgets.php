<?php
 
namespace Widgets;

class Widgets
{
	public static function data()
	{
		return array(
				// Español
				// Los widget se definen en un array de tres niveles
				// 1º Se define el nombre del widget
				'menu_secundario' => array(
					 // 2º Se define la clase del widget
					'clase' => 'menuSecundario',
					 // 3º Se definen los metodos de la clase principal
					 // Los widget soportan solo un metodo
					 // de configuracion y uno de contenidos.
					 // Solo el metodo de contenido soporta la inclusion
					 // de parametros asociados.
					 // Si estos son requeridos se agrega un
					 // elemento mas a la matriz especificandolos. 
					'metodos' => array(
									// Seteo de metodos de configuración.
									'conf' => 'getConfig',
									// Los metodos de contenido
									'cont' => 'getMenu',
									// Parametros del metodo de contenido.
									// Los parametros siempre se agragan desde
									// una matriz.
									// Si el metodo no posee parametros,
									// solo se comenta la linea
									'parametros' => array('item'),
								
									)
								),
			
				'menu_primario' => array(
					'clase' => 'menuPrimario',
					'metodos' => array(
									'conf' => 'getConfig',
									'cont' => 'getMenu',
									'parametros' => array('item'),
									)
								),
				'menu_primario_pie' => array(
					'clase' => 'menuPrimarioPie',
					'metodos' => array(
									'conf' => 'getConfig',
									'cont' => 'getMenu',
									'parametros' => array('item'),
									)
								),
								
				'menu_administrador' => array(
					'clase' => 'menuAdministrador',
					'metodos' => array(
									'conf' => 'getConfig',
									'cont' => 'getMenu',
									'parametros' => array('item'),
									)
								),
				'menu_administracion' => array(
					'clase' => 'menuAdministracion',
					'metodos' => array(
									'conf' => 'getConfig',
									'cont' => 'getMenu',
									'parametros' => array('item'),
									)
								),
																
			);		
	}
}
