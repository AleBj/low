<?php

 
class Menues
{
	public $_menu_;
	public $_cache_;

	public $_permisos;
	
	public function __construct(){
		$this->_menu_primario = array();
		$this->_cache_ = array();
		$this->_permisos = array();
    }
	
	public function gestinarPermisos($_id_permiso)
	{
		// Traemos los datos de permiso requeridos
		$_data_permisos = widgets_menu_permiso::find($_id_permiso);
		
		//creamos una matriz acorde a nuestras pretensiones.
		$this->_permisos['acceso'] = $_data_permisos->acceso;
					
		if($_data_permisos->rol != 0){
			$this->_permisos['rol'] = $_data_permisos->rol;
		}
		return $this->_permisos;
	}
	
	public function gestionarSubMenu($_url_base, $_menu_padre, $_elemeto_padre)
	{
		// Traemos todos los elementos submenu que coincidan
		// con con el elemento padre invocado en el registro.
		$_data_sub_menu = widgets_menu_submenu::find('all', array('conditions' => array('menu_padre = ?', $_menu_padre)));
		
		if(isset($_sub_menu)){
			unset($_sub_menu);
			$_sub_menu = array();
		}
		
		// Recorremos la matriz y creamos una nueva
		// acorde a nuestras pretensiones. 
		foreach($_data_sub_menu as $_valores){
			if($_elemeto_padre == $_valores->elemento_padre){
				$_sub_menu[] = array(
										'permiso' 	=> $this->gestinarPermisos($_valores->permiso),
										'id'		=> $_valores->enlace_id,
										'titulo'	=> $_valores->titulo,
										'titulo_in'	=> $_valores->titulo_in,
										'enlace'	=> $_url_base . $_valores->enlace,
										'imagen'	=> $_valores->imagen,
										
									);	
			}
		}
		return $_sub_menu;
	}
	
	public function gestionarMenu($_menu, $_url_base, $_item = false)
	{
		$_item = $_item ? $_item : '';
		$_tabla = 'widgets_menu_' . $_menu;
		$_data = $_tabla::all();

		for($i=0;$i<count($_data);$i++){
			
			if($_data[$i]->sub_menu == '0'){
				
				$this->_menu_[$i] = array(
							'permiso' 	=> $this->gestinarPermisos($_data[$i]->permiso),
							'id'		=> $_data[$i]->enlace_id,
							'titulo'	=> $_data[$i]->titulo,
							'titulo_in'	=> $_data[$i]->titulo_in,
							'enlace'	=> $_url_base . $_data[$i]->enlace,
							'imagen'	=> $_data[$i]->imagen,
							'item'		=> $_item,
							//'color'		=> $_data[$i]->color,
						);	
			}else{
				$this->_menu_[$i] = array(
							'permiso' 	=>$this->gestinarPermisos($_data[$i]->permiso),
							'id'		=> $_data[$i]->enlace_id,
							'titulo'	=> $_data[$i]->titulo,
							'titulo_in'	=> $_data[$i]->titulo_in,
							'enlace'	=> $_url_base . $_data[$i]->enlace,
							'imagen'	=> $_data[$i]->imagen,
							'item'		=> $_item,
							//'color'		=> $_data[$i]->color,
							'submenu' 	=> $this->gestionarSubMenu($_url_base, $_data[$i]->sub_menu, $_data[$i]->enlace_id),
						);
			}
		}
		return $this->_menu_;
	}
	
	public function crearCache($_menu, $_url_base, $_ruta_cache)
	{
		$_tabla = 'widgets_menu_' . $_menu;
		$_data = $_tabla::all();

		for($i=0;$i<count($_data);$i++){
			
			if($_data[$i]->sub_menu == '0'){
				$this->_cache_[$i] = array(
							'permiso' 	=> $this->gestinarPermisos($_data[$i]->permiso),
							'id'		=> $_data[$i]->enlace_id,
							'titulo'	=> $_data[$i]->titulo,
							'titulo_in'	=> $_data[$i]->titulo_in,
							'enlace'	=> $_url_base . $_data[$i]->enlace,
							'imagen'	=> $_data[$i]->imagen,
							//'color'		=> $_data[$i]->color
						);	
			}else{
				$this->_cache_[$i] = array(
							'permiso' 	=> $this->gestinarPermisos($_data[$i]->permiso),
							'id'		=> $_data[$i]->enlace_id,
							'titulo'	=> $_data[$i]->titulo,
							'titulo_in'	=> $_data[$i]->titulo_in,
							'enlace'	=> $_url_base . $_data[$i]->enlace,
							'imagen'	=> $_data[$i]->imagen,
							//'color'		=> $_data[$i]->color,
							'submenu' 	=> $this->gestionarSubMenu($_url_base, $_data[$i]->sub_menu, $_data[$i]->enlace_id)
						);
			}
		}
		
		if(is_readable($_ruta_cache . $_menu . '.txt')){
			
			// Primero, tomamos el archivo
			$_contenido = file_get_contents($_ruta_cache . $_menu . '.txt',FILE_USE_INCLUDE_PATH);
			
			// Lo desencriptamos
			$base_64 = base64_decode($_contenido);
			
			// Y lo deserializamos
			$_data = unserialize($base_64);
			
			// metemos el nuevo contenido.
			$_data = $this->_cache_;
			
			// y volvemos encriptar los datos 
			$serializar = serialize($_data);
			// Y lo encriptamos en md64
			$codificar = base64_encode($serializar);
			
		}else{
			// Serielizamos el archivo
			$serializar = serialize($this->_cache_);
			// Y lo encriptamos en md64
			$codificar = base64_encode($serializar);
			
			// Luego creamos el archivo final y lo escribimos.
			// ATENCION: si el archi existe, se sobrescribe.
			fopen($_ruta_cache . $_menu . '.txt',"w+");
		}
		
		if(is_readable($_ruta_cache . $_menu . '.txt')){
			@file_put_contents($_ruta_cache . $_menu . '.txt', $codificar);
		}else{
			throw new Exception('No se pudo crear Cache de elemento');	
		}
	}
	
	public function combertirCacheSimple($_menu, $_url_base, $_ruta_cache, $_item)
	{
		$_item = $_item ? $_item : '';
		
		if(is_readable($_ruta_cache . $_menu . '.txt')){
			if(filesize($_ruta_cache . $_menu . '.txt') > 0){
				
				$contenido = @file_get_contents($_url_base . $_ruta_cache . $_menu . '.txt', FILE_USE_INCLUDE_PATH);
				$base_64 = base64_decode($contenido);
				$_data = unserialize($base_64);	
				
				if(is_array($_data)){
					for($i=0;$i<count($_data);$i++){
						
						if(@!$_data[$i]['submenu']){
							$_dat[] = array(
										'permiso' 	=> $_data[$i]['permiso'],
										'id'		=> $_data[$i]['id'],
										'titulo'	=> $_data[$i]['titulo'],
										//'titulo_in'	=> $_data[$i]['titulo_in'],
										'enlace'	=> $_data[$i]['enlace'],
										'imagen'	=> $_data[$i]['imagen'],
										//'color'		=> $_data[$i]['color'],
										'item'		=> $_item,
									);
						}else{
							$_dat[] = array(
										'permiso' 	=> $_data[$i]['permiso'],
										'id'		=> $_data[$i]['id'],
										'titulo'	=> $_data[$i]['titulo'],
										//'titulo_in'	=> $_data[$i]['titulo_in'],
										'enlace'	=> $_data[$i]['enlace'],
										'imagen'	=> $_data[$i]['imagen'],
										//'color'		=> $_data[$i]['color'],
										'item'		=> $_item,
										'submenu'	=> $_data[$i]['submenu'],
									);
						}
					}
				}
				
			}else{
				throw new Exception('el archivo ' . $_menu . '.txt' . ' esta vacio. Buscar en base de datos!');		
			}
		}else{
			throw new Exception('el archivo ' . $_menu . '.txt' . ' no existe o esta corrupto. Buscar en base de datos!');		
		}
		
		return (isset($_dat)) ? $_dat : false;
		
		//return $_dat;
	}

}