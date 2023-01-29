<?php

use Nucleo\Registro\Registro;

class menuPrimarioPieModelWidget
{
	public $_gestion_menu;
	
    public function __construct()
	{
		require_once(Registro::tomarInstancia()->_conf['raiz'] . 'widgets/libs/class.menus.php');
		$this->_gestion_menu = new Menues;
    }
    
    public function getMenu($_item = false)
    {
		//$this->_gestion_menu->crearCache('primario_pie', Registro::tomarInstancia()->_conf['base_url'], Registro::tomarInstancia()->_conf['ruta_cache']['widgets']['menus']);
		return $this->_gestion_menu->combertirCacheSimple(
										'primario_pie', 
										Registro::tomarInstancia()->_conf['base_url'], 
										Registro::tomarInstancia()->_conf['ruta_cache']['widgets']['menus'], 
										$_item
									);
    }
	
	public function crearMenu()
	{
		$this->_gestion_menu->crearCache('primario_pie', Registro::tomarInstancia()->_conf['base_url'], Registro::tomarInstancia()->_conf['ruta_cache']['widgets']['menus']);
	}
}