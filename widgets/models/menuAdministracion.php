<?php

use Nucleo\Registro\Registro;

class menuAdministracionModelWidget
{
	public $_gestion_menu;
	
    public function __construct()
	{
		require_once(Registro::tomarInstancia()->_conf['raiz'] . 'widgets/libs/class.menus.php');
		$this->_gestion_menu = new Menues;
    }
    
    public function getMenu($item = false)
    {
		$this->_gestion_menu->crearCache('administracion', Registro::tomarInstancia()->_conf['base_url'], Registro::tomarInstancia()->_conf['ruta_cache']['widgets']['menus']);
		return $this->_gestion_menu->combertirCacheSimple(
										'administracion', 
										Registro::tomarInstancia()->_conf['base_url'], 
										Registro::tomarInstancia()->_conf['ruta_cache']['widgets']['menus'], 
										$item
									);  	
	}
	
	public function crearMenu()
	{
		$this->_gestion_menu->crearCache('administracion', Registro::tomarInstancia()->_conf['base_url'], Registro::tomarInstancia()->_conf['ruta_cache']['widgets']['menus']);
	}
}