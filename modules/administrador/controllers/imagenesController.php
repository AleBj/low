<?php

use controllers\administradorController\administradorController;

class imagenesController extends administradorController
{
	public $_trabajosGestion;
	
    public function __construct() 
    {
		parent::__construct();
		$this->getLibrary('class.validador');
		$this->getLibrary('class.admin');
		$this->_trabajosGestion = new admin();
		
		$this->getLibrary('class.upload');
		
		$this->_error = 'has-error';
		$this->_filtro = '';
    }
    
   	
	
	public function cargImg($_tipo, $_pos='')
	{
		if (!empty($_FILES)) {
			if($this->_sess->get('img_id')) $this->_sess->destroy('img_id');
			$_info = pathinfo($_FILES['file']['name']);
			$_nombre_archivo =  basename($_FILES['file']['name'], '.' . $_info['extension']);
			$_nombre_interno = admin::cadenaAleatoriaSegura(15);
			//$_formatos_img = Conf\formatos_img::data();
			$size = getimagesize($_FILES['file']['tmp_name']);		
			
			if($_tipo=='cursos' && $_pos == 'principal'){
				$foo = new upload($_FILES['file']);
				$foo->file_new_name_body 		= $_nombre_interno;
				//$foo->image_convert 			= 'jpg';
				$foo->image_resize          	= true;
				$foo->jpeg_quality          	= 100;
				$foo->png_compression       	= 9;
				/*$foo->image_ratio_fill      	= true;
				$foo->image_ratio_y         	= true;*/
				$foo->image_ratio_crop			= true;	
				// $foo->image_x = $this->_conf['formatos_img'][$_tipo.'_grandes']['ancho'];
				$foo->image_y = $this->_conf['formatos_img'][$_tipo.'_'.$_pos.'_grandes']['alto'];	
				$tamañoOrig = admin::CapturarAnchoAlto($_FILES['file']['tmp_name']);
				$foo->image_x =  round(($foo->image_y/$tamañoOrig[0])*$tamañoOrig[1]);				
				//$foo->image_background_color 	= '#00000';			
				//$foo->process($this->_imagenesGestion->gestionDirectoriosOriginales($this->_conf['ruta_img_cargadas'] . 'slider' . DS . 'originales' . DS, date("Y-m-d")));
				$foo->process($this->_conf['ruta_img_cargadas'] . $_tipo . DS . $_pos. DS. 'grandes'. DS);

			}elseif($_tipo=='blog' && $_pos==''){
				$foo = new upload($_FILES['file']);
				$foo->file_new_name_body 		= $_nombre_interno;
				//$foo->image_convert 			= 'jpg';
				$foo->image_resize          	= true;
				$foo->jpeg_quality          	= 100;
				// $foo->png_compression       	= 10;
				/*$foo->image_ratio_fill      	= true;
				$foo->image_ratio_y         	= true;*/
				$foo->image_ratio_crop			= true;	
				$foo->image_x = $this->_conf['formatos_img'][$_tipo.'_grandes']['ancho'];
				$foo->image_y = $this->_conf['formatos_img'][$_tipo.'_grandes']['alto'];			
				//$foo->image_background_color 	= '#00000';			
				//$foo->process($this->_imagenesGestion->gestionDirectoriosOriginales($this->_conf['ruta_img_cargadas'] . 'slider' . DS . 'originales' . DS, date("Y-m-d")));
				$foo->process($this->_conf['ruta_img_cargadas'] . $_tipo . DS . $_pos. DS. 'grandes'. DS);
				
			}elseif($_tipo=='seo' && $_pos==''){
				$foo = new upload($_FILES['file']);
				$foo->file_new_name_body 		= $_nombre_interno;
				//$foo->image_convert 			= 'jpg';
				$foo->image_resize          	= true;
				$foo->jpeg_quality          	= 100;
				// $foo->png_compression       	= 10;
				/*$foo->image_ratio_fill      	= true;
				$foo->image_ratio_y         	= true;*/
				$foo->image_ratio_crop			= true;	
				$foo->image_x = $this->_conf['formatos_img'][$_tipo.'_grandes']['ancho'];
				$foo->image_y = $this->_conf['formatos_img'][$_tipo.'_grandes']['alto'];			
				//$foo->image_background_color 	= '#00000';			
				//$foo->process($this->_imagenesGestion->gestionDirectoriosOriginales($this->_conf['ruta_img_cargadas'] . 'slider' . DS . 'originales' . DS, date("Y-m-d")));
				$foo->process($this->_conf['ruta_img_cargadas'] . $_tipo . DS . $_pos. DS. 'grandes'. DS);
				
			}elseif($_tipo=='press'){
				$foo = new upload($_FILES['file']);
				$foo->file_new_name_body 		= $_nombre_interno;
				//$foo->image_convert 			= 'jpg';
				$foo->image_resize          	= true;
				$foo->jpeg_quality          	= 100;
				$foo->png_compression       	= 9;
				/*$foo->image_ratio_fill      	= true;
				$foo->image_ratio_y         	= true;*/
				$foo->image_ratio_crop			= true;	
				$foo->image_x = $this->_conf['formatos_img'][$_tipo.'_grandes']['ancho'];
				// $foo->image_y = $this->_conf['formatos_img'][$_tipo.'_'.$_pos.'_grandes']['alto'];	
				$tamañoOrig = admin::CapturarAnchoAlto($_FILES['file']['tmp_name']);
				$foo->image_y =  round(($foo->image_x/$tamañoOrig[0])*$tamañoOrig[1]);				
				//$foo->image_background_color 	= '#00000';			
				//$foo->process($this->_imagenesGestion->gestionDirectoriosOriginales($this->_conf['ruta_img_cargadas'] . 'slider' . DS . 'originales' . DS, date("Y-m-d")));
				$foo->process($this->_conf['ruta_img_cargadas'] . $_tipo . DS . 'grandes'. DS);

			}elseif($_tipo=='slider'){
				$foo = new upload($_FILES['file']);
				$foo->file_new_name_body 		= $_nombre_interno;
				//$foo->image_convert 			= 'jpg';
				$foo->image_resize          	= true;
				$foo->jpeg_quality          	= 100;
				$foo->png_compression       	= 9;
				/*$foo->image_ratio_fill      	= true;
				$foo->image_ratio_y         	= true;*/
				$foo->image_ratio_crop			= true;	
				$foo->image_x = $this->_conf['formatos_img'][$_tipo.'_'.$_pos.'_grandes']['ancho'];
				// $foo->image_y = $this->_conf['formatos_img'][$_tipo.'_'.$_pos.'_grandes']['alto'];	
				$tamañoOrig = admin::CapturarAnchoAlto($_FILES['file']['tmp_name']);
				$foo->image_y =  round(($foo->image_x/$tamañoOrig[0])*$tamañoOrig[1]);				
				//$foo->image_background_color 	= '#00000';			
				//$foo->process($this->_imagenesGestion->gestionDirectoriosOriginales($this->_conf['ruta_img_cargadas'] . 'slider' . DS . 'originales' . DS, date("Y-m-d")));
				$foo->process($this->_conf['ruta_img_cargadas'] . $_tipo . DS . $_pos. DS. 'grandes'. DS);

			}else{
				if($_pos==''){
					$foo = new upload($_FILES['file']);
					$foo->file_new_name_body 		= $_nombre_interno;
					//$foo->image_convert 			= 'jpg';
					$foo->image_resize          	= true;
					$foo->jpeg_quality          	= 100;
					// $foo->png_compression       	= 10;
					/*$foo->image_ratio_fill      	= true;
					$foo->image_ratio_y         	= true;*/
					$foo->image_ratio_crop			= true;	
					$foo->image_x = $this->_conf['formatos_img'][$_tipo.'_grandes']['ancho'];
					$foo->image_y = $this->_conf['formatos_img'][$_tipo.'_grandes']['alto'];			
					//$foo->image_background_color 	= '#00000';			
					//$foo->process($this->_imagenesGestion->gestionDirectoriosOriginales($this->_conf['ruta_img_cargadas'] . 'slider' . DS . 'originales' . DS, date("Y-m-d")));
					$foo->process($this->_conf['ruta_img_cargadas'] . $_tipo . DS . 'grandes'. DS);
				}else{
					$foo = new upload($_FILES['file']);
					$foo->file_new_name_body 		= $_nombre_interno;
					//$foo->image_convert 			= 'jpg';
					$foo->image_resize          	= true;
					$foo->jpeg_quality          	= 100;
					// $foo->png_compression       	= 10;
					/*$foo->image_ratio_fill      	= true;
					$foo->image_ratio_y         	= true;*/
					$foo->image_ratio_crop			= true;	
					$foo->image_x = $this->_conf['formatos_img'][$_tipo.'_'.$_pos.'_grandes']['ancho'];
					$foo->image_y = $this->_conf['formatos_img'][$_tipo.'_'.$_pos.'_grandes']['alto'];			
					//$foo->image_background_color 	= '#00000';			
					//$foo->process($this->_imagenesGestion->gestionDirectoriosOriginales($this->_conf['ruta_img_cargadas'] . 'slider' . DS . 'originales' . DS, date("Y-m-d")));
					$foo->process($this->_conf['ruta_img_cargadas'] . $_tipo . DS . $_pos. DS. 'grandes'. DS);
				
				}
			}
			
			
			if($foo->processed){

				if($_tipo=='paginas'){
					$thumb = new upload($foo->file_dst_pathname);
					$thumb->image_resize = true;
					$thumb->image_ratio_crop = 'T';
					// $thumb->image_x = $this->_conf['formatos_img'][$_tipo.'_thumb']['ancho'];
					$thumb->image_y = $this->_conf['formatos_img'][$_tipo.'_'.$_pos.'_thumb']['alto'];
					$thumb->image_x =  round(($thumb->image_y/$foo->image_dst_x)*$foo->image_dst_y);
					// $tamañoOrig = admin::CapturarAnchoAlto($thumb->file_dst_pathname);
					// $thumb->image_x =  round(($thumb->image_y/$tamañoOrig[0])*$tamañoOrig[1]);
					$thumb->process($this->_conf['ruta_img_cargadas'] . $_tipo. DS  . 'thumb'. DS);

				}elseif($_tipo=='press'){

					$thumb = new upload($foo->file_dst_pathname);
					$thumb->image_resize = true;
					$thumb->image_ratio_crop = 'T';
					$thumb->image_x = $this->_conf['formatos_img'][$_tipo.'_thumb']['ancho'];
					// $thumb->image_y = $this->_conf['formatos_img'][$_tipo.'_'.$_pos.'_thumb']['alto'];
					/*$tamañoOrig = admin::CapturarAnchoAlto($foo->file_dst_pathname);
					$thumb->image_y =  round(($thumb->image_x/$tamañoOrig[0])*$tamañoOrig[1]);*/
					$thumb->image_y =  round(($thumb->image_x/$foo->image_dst_x)*$foo->image_dst_y);		
					$thumb->process($this->_conf['ruta_img_cargadas'] . $_tipo . DS . 'thumb'. DS);

				}elseif($_tipo=='slider'){

					$thumb = new upload($foo->file_dst_pathname);
					$thumb->image_resize = true;
					$thumb->image_ratio_crop = 'T';
					$thumb->image_x = $this->_conf['formatos_img'][$_tipo.'_'.$_pos.'_thumb']['ancho'];
					$thumb->image_y =  round(($thumb->image_x/$foo->image_dst_x)*$foo->image_dst_y);
					// $tamañoOrig = admin::CapturarAnchoAlto($thumb->file_dst_pathname);
					// $thumb->image_y =  round(($thumb->image_x/$tamañoOrig[0])*$tamañoOrig[1]);				
					$thumb->process($this->_conf['ruta_img_cargadas'] . $_tipo . DS . $_pos. DS. 'thumb'. DS);

				}else{
				
					if($_pos==''){
						$thumb = new upload($foo->file_dst_pathname);
						$thumb->image_resize = true;
						$thumb->image_ratio_crop = 'T';
						$thumb->image_x = $this->_conf['formatos_img'][$_tipo.'_thumb']['ancho'];
						$thumb->image_y = $this->_conf['formatos_img'][$_tipo.'_thumb']['alto'];
						$thumb->process($this->_conf['ruta_img_cargadas'] . $_tipo. DS . 'thumb'. DS);
					}else{
						$thumb = new upload($foo->file_dst_pathname);
						$thumb->image_resize = true;
						$thumb->image_ratio_crop = 'T';
						$thumb->image_x = $this->_conf['formatos_img'][$_tipo.'_'.$_pos.'_thumb']['ancho'];
						$thumb->image_y = $this->_conf['formatos_img'][$_tipo.'_'.$_pos.'_thumb']['alto'];
						$thumb->process($this->_conf['ruta_img_cargadas'] . $_tipo. DS . $_pos . DS . 'thumb'. DS);
					}
				}
			
								
				$_nombre = (isset($_POST['imagen_principal_nombre']) && $_POST['imagen_principal_nombre'] != '') ? $_POST['imagen_principal_nombre'] : $_FILES['file']['name'];
				// Base Datos
				$_imagen = $this->_trabajosGestion->cargarImgDB($this->_sess->get('carga_actual'), $foo->file_dst_name, $_nombre, $_pos, $_tipo, $this->_conf['ruta_img_cargadas']);
				if($_imagen) $this->_sess->set('img_id', $_imagen);
			}
		}
	}
	
	public function cargImgEditar($_tipo, $_pos='')
	{
		if (!empty($_FILES)) {
			if($this->_sess->get('img_id')) $this->_sess->destroy('img_id');
			$_info = pathinfo($_FILES['file']['name']);
			$_nombre_archivo =  basename($_FILES['file']['name'], '.' . $_info['extension']);
			$_nombre_interno = admin::cadenaAleatoriaSegura(15);
			//$_formatos_img = Conf\formatos_img::data();
			$size = getimagesize($_FILES['file']['tmp_name']);
	
			if($_tipo=='blog' && $_pos==''){
				$foo = new upload($_FILES['file']);
				$foo->file_new_name_body 		= $_nombre_interno;
				//$foo->image_convert 			= 'jpg';
				$foo->image_resize          	= true;
				$foo->jpeg_quality          	= 100;
				// $foo->png_compression       	= 10;
				/*$foo->image_ratio_fill      	= true;
				$foo->image_ratio_y         	= true;*/
				$foo->image_ratio_crop			= true;	
				$foo->image_x = $this->_conf['formatos_img'][$_tipo.'_grandes']['ancho'];
				$foo->image_y = $this->_conf['formatos_img'][$_tipo.'_grandes']['alto'];			
				//$foo->image_background_color 	= '#00000';			
				//$foo->process($this->_imagenesGestion->gestionDirectoriosOriginales($this->_conf['ruta_img_cargadas'] . 'slider' . DS . 'originales' . DS, date("Y-m-d")));
				$foo->process($this->_conf['ruta_img_cargadas'] . $_tipo . DS . $_pos. DS. 'grandes'. DS);
				
			}elseif($_tipo=='seo' && $_pos==''){
				$foo = new upload($_FILES['file']);
				$foo->file_new_name_body 		= $_nombre_interno;
				//$foo->image_convert 			= 'jpg';
				$foo->image_resize          	= true;
				$foo->jpeg_quality          	= 100;
				// $foo->png_compression       	= 10;
				/*$foo->image_ratio_fill      	= true;
				$foo->image_ratio_y         	= true;*/
				$foo->image_ratio_crop			= true;	
				$foo->image_x = $this->_conf['formatos_img'][$_tipo.'_grandes']['ancho'];
				$foo->image_y = $this->_conf['formatos_img'][$_tipo.'_grandes']['alto'];			
				//$foo->image_background_color 	= '#00000';			
				//$foo->process($this->_imagenesGestion->gestionDirectoriosOriginales($this->_conf['ruta_img_cargadas'] . 'slider' . DS . 'originales' . DS, date("Y-m-d")));
				$foo->process($this->_conf['ruta_img_cargadas'] . $_tipo . DS . $_pos. DS. 'grandes'. DS);
				
			}elseif($_tipo=='press' && $_pos==''){
				$foo = new upload($_FILES['file']);
				$foo->file_new_name_body 		= $_nombre_interno;
				//$foo->image_convert 			= 'jpg';
				$foo->image_resize          	= true;
				$foo->jpeg_quality          	= 100;
				$foo->png_compression       	= 9;
				/*$foo->image_ratio_fill      	= true;
				$foo->image_ratio_y         	= true;*/
				$foo->image_ratio_crop			= true;	
				$foo->image_x = $this->_conf['formatos_img'][$_tipo.'_grandes']['ancho'];
				// $foo->image_y = $this->_conf['formatos_img'][$_tipo.'_'.$_pos.'_grandes']['alto'];	
				$tamañoOrig = admin::CapturarAnchoAlto($_FILES['file']['tmp_name']);
				$foo->image_y =  round(($foo->image_x/$tamañoOrig[0])*$tamañoOrig[1]);				
				//$foo->image_background_color 	= '#00000';			
				//$foo->process($this->_imagenesGestion->gestionDirectoriosOriginales($this->_conf['ruta_img_cargadas'] . 'slider' . DS . 'originales' . DS, date("Y-m-d")));
				$foo->process($this->_conf['ruta_img_cargadas'] . $_tipo . DS . 'grandes'. DS);

			}elseif($_tipo=='slider'){
				$foo = new upload($_FILES['file']);
				$foo->file_new_name_body 		= $_nombre_interno;
				//$foo->image_convert 			= 'jpg';
				$foo->image_resize          	= true;
				$foo->jpeg_quality          	= 100;
				$foo->png_compression       	= 9;
				/*$foo->image_ratio_fill      	= true;
				$foo->image_ratio_y         	= true;*/
				$foo->image_ratio_crop			= true;	
				$foo->image_x = $this->_conf['formatos_img'][$_tipo.'_'.$_pos.'_grandes']['ancho'];
				// $foo->image_y = $this->_conf['formatos_img'][$_tipo.'_'.$_pos.'_grandes']['alto'];	
				$tamañoOrig = admin::CapturarAnchoAlto($_FILES['file']['tmp_name']);
				$foo->image_y =  round(($foo->image_x/$tamañoOrig[0])*$tamañoOrig[1]);				
				//$foo->image_background_color 	= '#00000';			
				//$foo->process($this->_imagenesGestion->gestionDirectoriosOriginales($this->_conf['ruta_img_cargadas'] . 'slider' . DS . 'originales' . DS, date("Y-m-d")));
				$foo->process($this->_conf['ruta_img_cargadas'] . $_tipo . DS . $_pos. DS. 'grandes'. DS);

			}else{
				$foo = new upload($_FILES['file']);
				$foo->file_new_name_body 		= $_nombre_interno;
				//$foo->image_convert 			= 'jpg';
				$foo->image_resize          	= true;
				$foo->jpeg_quality          	= 100;
				// $foo->png_compression       	= 10;
				/*$foo->image_ratio_fill      	= true;
				$foo->image_ratio_y         	= true;*/
				$foo->image_ratio_crop			= true;	
				$foo->image_x = $this->_conf['formatos_img'][$_tipo.'_'.$_pos.'_grandes']['ancho'];
				$foo->image_y = $this->_conf['formatos_img'][$_tipo.'_'.$_pos.'_grandes']['alto'];			
				//$foo->image_background_color 	= '#00000';			
				//$foo->process($this->_imagenesGestion->gestionDirectoriosOriginales($this->_conf['ruta_img_cargadas'] . 'slider' . DS . 'originales' . DS, date("Y-m-d")));
				$foo->process($this->_conf['ruta_img_cargadas'] . $_tipo . DS . $_pos. DS. 'grandes'. DS);

			
			
			}
			
			if($foo->processed){
				
				if($_tipo=='blog' && $_pos==''){
					$thumb = new upload($foo->file_dst_pathname);
					$thumb->image_resize = true;
					$thumb->image_ratio_crop = 'T';
					$thumb->image_x = $this->_conf['formatos_img'][$_tipo.'_thumb']['ancho'];
					$thumb->image_y = $this->_conf['formatos_img'][$_tipo.'_thumb']['alto'];
					$thumb->process($this->_conf['ruta_img_cargadas'] . $_tipo. DS . 'thumb'. DS);
					
				}elseif($_tipo=='seo' && $_pos==''){
					$thumb = new upload($foo->file_dst_pathname);
					$thumb->image_resize = true;
					$thumb->image_ratio_crop = 'T';
					$thumb->image_x = $this->_conf['formatos_img'][$_tipo.'_thumb']['ancho'];
					$thumb->image_y = $this->_conf['formatos_img'][$_tipo.'_thumb']['alto'];
					$thumb->process($this->_conf['ruta_img_cargadas'] . $_tipo. DS . 'thumb'. DS);
					
				}elseif($_tipo=='press' && $_pos==''){

					$thumb = new upload($foo->file_dst_pathname);
					$thumb->image_resize = true;
					// $thumb->image_ratio_crop = 'T';
					$thumb->image_x = $this->_conf['formatos_img'][$_tipo.'_thumb']['ancho'];
					// $thumb->image_y = $this->_conf['formatos_img'][$_tipo.'_'.$_pos.'_thumb']['alto'];
					/*$tamañoOrig = admin::CapturarAnchoAlto($foo->file_dst_pathname);
					$thumb->image_y =  round(($thumb->image_x/$tamañoOrig[0])*$tamañoOrig[1]);*/
					$thumb->image_y =  round(($thumb->image_x/$foo->image_dst_x)*$foo->image_dst_y);		
					$thumb->process($this->_conf['ruta_img_cargadas'] . $_tipo . DS . 'thumb'. DS);

				}elseif($_tipo=='slider'){

					$thumb = new upload($foo->file_dst_pathname);
					$thumb->image_resize = true;
					$thumb->image_ratio_crop = 'T';
					$thumb->image_x = $this->_conf['formatos_img'][$_tipo.'_'.$_pos.'_thumb']['ancho'];
					// $thumb->image_y = $this->_conf['formatos_img'][$_tipo.'_'.$_pos.'_thumb']['alto'];
					/*$tamañoOrig = admin::CapturarAnchoAlto($foo->file_dst_pathname);
					$thumb->image_y =  round(($thumb->image_x/$tamañoOrig[0])*$tamañoOrig[1]);*/
					$thumb->image_y =  round(($thumb->image_x/$thumb->image_dst_x)*$thumb->image_dst_y);		
					$thumb->process($this->_conf['ruta_img_cargadas'] . $_tipo . DS . $_pos. DS. 'thumb'. DS);

				}else{
					$thumb = new upload($foo->file_dst_pathname);
					$thumb->image_resize = true;
					$thumb->image_ratio_crop = 'T';
					$thumb->image_x = $this->_conf['formatos_img'][$_tipo.'_'.$_pos.'_thumb']['ancho'];
					$thumb->image_y = $this->_conf['formatos_img'][$_tipo.'_'.$_pos.'_thumb']['alto'];
					$thumb->process($this->_conf['ruta_img_cargadas'] . $_tipo. DS . $_pos . DS . 'thumb'. DS);
				}
								
				$_nombre = (isset($_POST['imagen_principal_nombre']) && $_POST['imagen_principal_nombre'] != '') ? $_POST['imagen_principal_nombre'] : $_FILES['file']['name'];
				// Base Datos
				//$_imagen = $this->_trabajosGestion->cargarImgDB($this->_sess->get('carga_actual'), $_nombre_interno . '.jpg', $_nombre, 1);
				$_imagen = $this->_trabajosGestion->cargarImgDB($this->_sess->get('edicion_actual'), $foo->file_dst_name, $_nombre, $_pos, $_tipo, $this->_conf['ruta_img_cargadas']);

				// echo $foo->log;
				
				if($_imagen) $this->_sess->set('img_id', $_imagen);
			}
		}
	}
	
	
	public function cargImgLote($_tipo, $_pos)
	{
		if(!isset($_SESSION['lote_img_gal'])) $_SESSION['lote_img_gal'] = array();
		
		if(!$this->_sess->get('img_lote')){
			$this->_sess->set('img_lote', 'si');
		}

		if (!empty($_FILES)) {

			$_info = pathinfo($_FILES['file']['name']);
			$_nombre_archivo =  basename($_FILES['file']['name'], '.' . $_info['extension']);
			$_nombre_interno = admin::cadenaAleatoriaSegura(15);
			//$_formatos_img = Conf\formatos_img::data();
			$size = getimagesize($_FILES['file']['tmp_name']);
			
			
			$foo = new upload($_FILES['file']);
			$foo->file_new_name_body 		= $_nombre_interno;
			//$foo->image_convert 			= 'jpg';
			$foo->image_resize          	= true;
			$foo->jpeg_quality          	= 100;
			// $foo->png_compression       	= 10;
			/*$foo->image_ratio_fill      	= true;
			$foo->image_ratio_y         	= true;*/
			$foo->image_ratio_crop			= true;	
			$foo->image_x = $this->_conf['formatos_img'][$_tipo.'_'.$_pos.'_grandes']['ancho'];
			$foo->image_y = $this->_conf['formatos_img'][$_tipo.'_'.$_pos.'_grandes']['alto'];			
			//$foo->image_background_color 	= '#00000';			
			//$foo->process($this->_imagenesGestion->gestionDirectoriosOriginales($this->_conf['ruta_img_cargadas'] . 'slider' . DS . 'originales' . DS, date("Y-m-d")));
			$foo->process($this->_conf['ruta_img_cargadas'] . $_tipo . DS . $_pos. DS. 'grandes'. DS);
			if($foo->processed){
				
				
				$thumb = new upload($foo->file_dst_pathname);
				$thumb->image_resize = true;
				$thumb->image_ratio_crop = true;
				$thumb->image_x = $this->_conf['formatos_img'][$_tipo.'_'.$_pos.'_thumb']['ancho'];
				$thumb->image_y = $this->_conf['formatos_img'][$_tipo.'_'.$_pos.'_thumb']['alto'];
				$thumb->process($this->_conf['ruta_img_cargadas'] . $_tipo. DS . $_pos . DS . 'thumb'. DS);
				
				
								
				$_nombre = (isset($_POST['imagen_principal_nombre']) && $_POST['imagen_principal_nombre'] != '') ? $_POST['imagen_principal_nombre'] : $_FILES['file']['name'];
				// Base Datos
				$_imagen = $this->_trabajosGestion->cargarImgDBLote($this->_sess->get('carga_actual'), $foo->file_dst_name, $_nombre, $_pos, $_tipo, $this->_conf['ruta_img_cargadas']);
				//if($_imagen) $this->_sess->set('img_id', $_imagen);
				if($_imagen) $_SESSION['lote_img_gal'][$_imagen] = 'ok';
				
				/*echo "<pre>";
				print_r($_SESSION['lote_img_gal']);*/
				
			}
			
		}
	}
	
	
	public function cargImgLoteEditar($_tipo, $_pos)
	{
		if(!isset($_SESSION['lote_img_gal'])) $_SESSION['lote_img_gal'] = array();
		
		if(!$this->_sess->get('img_lote')){
			$this->_sess->set('img_lote', 'si');
		}

		if (!empty($_FILES)) {

			$_info = pathinfo($_FILES['file']['name']);
			$_nombre_archivo =  basename($_FILES['file']['name'], '.' . $_info['extension']);
			$_nombre_interno = admin::cadenaAleatoriaSegura(15);
			//$_formatos_img = Conf\formatos_img::data();
			$size = getimagesize($_FILES['file']['tmp_name']);
			
			
			$foo = new upload($_FILES['file']);
			$foo->file_new_name_body 		= $_nombre_interno;
			//$foo->image_convert 			= 'jpg';
			$foo->image_resize          	= true;
			$foo->jpeg_quality          	= 100;
			// $foo->png_compression       	= 10;
			/*$foo->image_ratio_fill      	= true;
			$foo->image_ratio_y         	= true;*/
			$foo->image_ratio_crop			= true;	
			$foo->image_x = $this->_conf['formatos_img'][$_tipo.'_'.$_pos.'_grandes']['ancho'];
			$foo->image_y = $this->_conf['formatos_img'][$_tipo.'_'.$_pos.'_grandes']['alto'];				
			//$foo->image_background_color 	= '#00000';			
			//$foo->process($this->_imagenesGestion->gestionDirectoriosOriginales($this->_conf['ruta_img_cargadas'] . 'slider' . DS . 'originales' . DS, date("Y-m-d")));
			$foo->process($this->_conf['ruta_img_cargadas'] . $_tipo . DS . $_pos. DS. 'grandes'. DS);
			if($foo->processed){
				
				
				$thumb = new upload($foo->file_dst_pathname);
				$thumb->image_resize = true;
				$thumb->image_ratio_crop = true;
				$thumb->image_x = $this->_conf['formatos_img'][$_tipo.'_'.$_pos.'_thumb']['ancho'];
				$thumb->image_y = $this->_conf['formatos_img'][$_tipo.'_'.$_pos.'_thumb']['alto'];
				$thumb->process($this->_conf['ruta_img_cargadas'] . $_tipo. DS . $_pos . DS . 'thumb'. DS);;			
					
				/*$thumb = new upload($foo->file_dst_pathname);
				$thumb->image_resize = true;
				$thumb->image_ratio_crop = true;
				$thumb->image_x = $this->_conf['formatos_img'][$_seccion.'_thumb']['ancho'];
				$thumb->image_y = $this->_conf['formatos_img'][$_seccion.'_thumb']['alto'];
				$thumb->process($this->_conf['ruta_img_cargadas'] . $_seccion . DS . 'miniatura'. DS);*/
				
				
								
				$_nombre = (isset($_POST['imagen_principal_nombre']) && $_POST['imagen_principal_nombre'] != '') ? $_POST['imagen_principal_nombre'] : $_FILES['file']['name'];
				// Base Datos
				$_imagen = $this->_trabajosGestion->cargarImgDBLote($this->_sess->get('edicion_actual'), $foo->file_dst_name, $_nombre, $_pos, $_tipo, $this->_conf['ruta_img_cargadas']);
				//if($_imagen) $this->_sess->set('img_id', $_imagen);
				if($_imagen) $_SESSION['lote_img_gal'][$_imagen] = 'ok';
				
				/*echo "<pre>";
				print_r($_SESSION['lote_img_gal']);*/
				
			}
			
		}
	}
	
	
	
	
	public function cargImgLoteGaleriaNoticia()
	{
		if(!isset($_SESSION['lote_img_gal'])) $_SESSION['lote_img_gal'] = array();
		
		if(!$this->_sess->get('img_lote')){
			$this->_sess->set('img_lote', 'si');
		}

		if (!empty($_FILES)) {

			$_info = pathinfo($_FILES['file']['name']);
			$_nombre_archivo =  basename($_FILES['file']['name'], '.' . $_info['extension']);
			$_nombre_interno = ImagenesAdministrador::cadenaAleatoriaSegura(15);
			$_formatos_img = Conf\formatos_img::data();

			$size = getimagesize($_FILES['file']['tmp_name']);

			$foo = new upload($_FILES['file']);
			$foo->file_new_name_body 		= $_nombre_interno;	
			$foo->image_convert 			= 'jpg';
			$foo->image_resize          	= true;
			$foo->image_ratio_fill      	= true;
			$foo->image_ratio_y         	= true;
			$foo->image_x        			= $_formatos_img[$_formatos_img['formatos'][0]]['principal']['ancho'];
			$foo->image_background_color 	= '#00000';
			$foo->process($this->_imagenesGestion->gestionDirectoriosOriginales(Conf\ruta_img_cargadas::data() . 'formatos' . DS . 'originales' . DS, date("Y-m-d")));

			if($foo->processed){

				$_ruta_i = Conf\ruta_img_cargadas::data() . 'formatos' . DS . 'originales' . DS . date("Y") . DS . date("m") . DS . date("d") . DS . $_nombre_interno . '.jpg';

				for($ff=0;$ff<count($_formatos_img['formatos']);$ff++){

	                // principal
	                $principal = new upload($_ruta_i);
	                $principal->image_resize = true;
	                $principal->image_ratio_crop = 'T';
	                $principal->image_y = $_formatos_img[$_formatos_img['formatos'][$ff]]['principal']['alto'];
	                $principal->image_x = $_formatos_img[$_formatos_img['formatos'][$ff]]['principal']['ancho'];
	                $principal->process($this->_imagenesGestion->gestionDirectoriosConFormato(Conf\ruta_img_cargadas::data() . 'formatos' . DS . 'principal' . DS, date("Y/m/d"), $_formatos_img['formatos'][$ff]));

	                // grande
	                $grande = new upload($_ruta_i);
	                $grande->image_resize = true;
	                $grande->image_ratio_crop = 'T';
	                $grande->image_y = $_formatos_img[$_formatos_img['formatos'][$ff]]['grande']['alto'];
	                $grande->image_x = $_formatos_img[$_formatos_img['formatos'][$ff]]['grande']['ancho'];
	                $grande->process($this->_imagenesGestion->gestionDirectoriosConFormato(Conf\ruta_img_cargadas::data() . 'formatos' . DS . 'grandes' . DS, date("Y/m/d"), $_formatos_img['formatos'][$ff]));
	                
	                // Mediana
	                $media = new upload($_ruta_i);
	                $media->image_resize = true;
	                $media->image_ratio_crop = 'T';
	                $media->image_x = $_formatos_img[$_formatos_img['formatos'][$ff]]['mediana']['ancho'];
	                $media->image_y = $_formatos_img[$_formatos_img['formatos'][$ff]]['mediana']['alto'];
	                $media->process($this->_imagenesGestion->gestionDirectoriosConFormato(Conf\ruta_img_cargadas::data() . 'formatos' . DS . 'medianas' . DS, date("Y/m/d"), $_formatos_img['formatos'][$ff]));

	                // chica
	                $chica = new upload($_ruta_i);
	                $chica->image_resize = true;
	                $chica->image_ratio_crop = 'T';
	                $chica->image_x = $_formatos_img[$_formatos_img['formatos'][$ff]]['chica']['ancho'];
	                $chica->image_y = $_formatos_img[$_formatos_img['formatos'][$ff]]['chica']['alto'];
	                $chica->process($this->_imagenesGestion->gestionDirectoriosConFormato(Conf\ruta_img_cargadas::data() . 'formatos' . DS . 'chicas' . DS, date("Y/m/d"), $_formatos_img['formatos'][$ff]));
					
					// miniatura
	                $miniatura = new upload($_ruta_i);
	                $miniatura->image_resize = true;
	                $miniatura->image_ratio_crop = 'T';
	                $miniatura->image_x = $_formatos_img[$_formatos_img['formatos'][$ff]]['miniatura']['ancho'];
	                $miniatura->image_y = $_formatos_img[$_formatos_img['formatos'][$ff]]['miniatura']['alto'];
	                $miniatura->process($this->_imagenesGestion->gestionDirectoriosConFormato(Conf\ruta_img_cargadas::data() . 'formatos' . DS . 'miniaturas' . DS, date("Y/m/d"), $_formatos_img['formatos'][$ff]));

		        }

				$_nombre = ($_POST['nombre_lote'] != '') ? $_POST['nombre_lote'] : $_FILES['file']['name'];
				//$_personas = ($_POST['personas_lote'] != '') ? $_POST['personas_lote'] : '';
				$_id_i = $this->_imagenesGestion->cargarImgDBLote($this->_sess->get('carga_actual'), $_nombre_interno . '.jpg', $_nombre, 1, 'si');

				//agregamos el id al lote
				$_SESSION['lote_img_gal'][$_id_i] = 'ok';
				
				/*echo "<pre>";
				print_r($_SESSION['lote_img_gal']);
				exit;*/
			}
		}
	}
	
	
	public function cargImgLoteGaleriaEditar()
	{
		if(!isset($_SESSION['lote_img_gal'])) $_SESSION['lote_img_gal'] = array();
		
		if(!$this->_sess->get('img_lote')){
			$this->_sess->set('img_lote', 'si');
		}

		if (!empty($_FILES)) {

			$_info = pathinfo($_FILES['file']['name']);
			$_nombre_archivo =  basename($_FILES['file']['name'], '.' . $_info['extension']);
			$_nombre_interno = ImagenesAdministrador::cadenaAleatoriaSegura(15);
			$_formatos_img = Conf\formatos_img::data();

			$size = getimagesize($_FILES['file']['tmp_name']);

			$foo = new upload($_FILES['file']);
			$foo->file_new_name_body 		= $_nombre_interno;	
			$foo->image_convert 			= 'jpg';
			$foo->image_resize          	= true;
			$foo->image_ratio_fill      	= true;
			$foo->image_ratio_y         	= true;
			$foo->image_x        			= $_formatos_img[$_formatos_img['formatos'][0]]['principal']['ancho'];
			$foo->image_background_color 	= '#00000';
			$foo->process($this->_imagenesGestion->gestionDirectoriosOriginales(Conf\ruta_img_cargadas::data() . 'formatos' . DS . 'originales' . DS, date("Y-m-d")));

			if($foo->processed){

				$_ruta_i = Conf\ruta_img_cargadas::data() . 'formatos' . DS . 'originales' . DS . date("Y") . DS . date("m") . DS . date("d") . DS . $_nombre_interno . '.jpg';

				for($ff=0;$ff<count($_formatos_img['formatos']);$ff++){

	                // principal
	                $principal = new upload($_ruta_i);
	                $principal->image_resize = true;
	                $principal->image_ratio_crop = 'T';
	                $principal->image_y = $_formatos_img[$_formatos_img['formatos'][$ff]]['principal']['alto'];
	                $principal->image_x = $_formatos_img[$_formatos_img['formatos'][$ff]]['principal']['ancho'];
	                $principal->process($this->_imagenesGestion->gestionDirectoriosConFormato(Conf\ruta_img_cargadas::data() . 'formatos' . DS . 'principal' . DS, date("Y/m/d"), $_formatos_img['formatos'][$ff]));

	                // grande
	                $grande = new upload($_ruta_i);
	                $grande->image_resize = true;
	                $grande->image_ratio_crop = 'T';
	                $grande->image_y = $_formatos_img[$_formatos_img['formatos'][$ff]]['grande']['alto'];
	                $grande->image_x = $_formatos_img[$_formatos_img['formatos'][$ff]]['grande']['ancho'];
	                $grande->process($this->_imagenesGestion->gestionDirectoriosConFormato(Conf\ruta_img_cargadas::data() . 'formatos' . DS . 'grandes' . DS, date("Y/m/d"), $_formatos_img['formatos'][$ff]));
	                
	                // Mediana
	                $media = new upload($_ruta_i);
	                $media->image_resize = true;
	                $media->image_ratio_crop = 'T';
	                $media->image_x = $_formatos_img[$_formatos_img['formatos'][$ff]]['mediana']['ancho'];
	                $media->image_y = $_formatos_img[$_formatos_img['formatos'][$ff]]['mediana']['alto'];
	                $media->process($this->_imagenesGestion->gestionDirectoriosConFormato(Conf\ruta_img_cargadas::data() . 'formatos' . DS . 'medianas' . DS, date("Y/m/d"), $_formatos_img['formatos'][$ff]));

	                // chica
	                $chica = new upload($_ruta_i);
	                $chica->image_resize = true;
	                $chica->image_ratio_crop = 'T';
	                $chica->image_x = $_formatos_img[$_formatos_img['formatos'][$ff]]['chica']['ancho'];
	                $chica->image_y = $_formatos_img[$_formatos_img['formatos'][$ff]]['chica']['alto'];
	                $chica->process($this->_imagenesGestion->gestionDirectoriosConFormato(Conf\ruta_img_cargadas::data() . 'formatos' . DS . 'chicas' . DS, date("Y/m/d"), $_formatos_img['formatos'][$ff]));
					
					// miniatura
	                $miniatura = new upload($_ruta_i);
	                $miniatura->image_resize = true;
	                $miniatura->image_ratio_crop = 'T';
	                $miniatura->image_x = $_formatos_img[$_formatos_img['formatos'][$ff]]['miniatura']['ancho'];
	                $miniatura->image_y = $_formatos_img[$_formatos_img['formatos'][$ff]]['miniatura']['alto'];
	                $miniatura->process($this->_imagenesGestion->gestionDirectoriosConFormato(Conf\ruta_img_cargadas::data() . 'formatos' . DS . 'miniaturas' . DS, date("Y/m/d"), $_formatos_img['formatos'][$ff]));

		        }

				$_nombre = ($_POST['nombre_lote'] != '') ? $_POST['nombre_lote'] : $_FILES['file']['name'];
				//$_personas = ($_POST['personas_lote'] != '') ? $_POST['personas_lote'] : '';
				$_id_i = $this->_imagenesGestion->cargarImgDBLote($this->_sess->get('edicion_actual'), $_nombre_interno . '.jpg', $_nombre, 1, 'si');

				//agregamos el id al lote
				$_SESSION['lote_img_gal'][$_id_i] = 'ok';
				
				/*echo "<pre>";
				print_r($_SESSION['lote_img_gal']);
				exit;*/
			}
		}
	}
	
	
	
	
	
	
	
	
	public function cargImgSol($_seccion,$_modulo,$_pos)
	{
		if (!empty($_FILES)) {
			if($this->_sess->get('img_id')) $this->_sess->destroy('img_id');
			$_info = pathinfo($_FILES['file']['name']);
			$_nombre_archivo =  basename($_FILES['file']['name'], '.' . $_info['extension']);
			$_nombre_interno = admin::cadenaAleatoriaSegura(15);
			$size = getimagesize($_FILES['file']['tmp_name']);
			
			if($_modulo==4){
				$foo = new upload($_FILES['file']);
				$foo->file_new_name_body 		= $_nombre_interno;
				//$foo->image_convert 			= 'jpg';
				$foo->process($this->_conf['ruta_img_cargadas'] . $_seccion . DS);
			}else{
				$foo = new upload($_FILES['file']);
				$foo->file_new_name_body 		= $_nombre_interno;
				$foo->image_convert 			= 'jpg';
				$foo->image_resize          	= true;
				/*$foo->image_ratio_fill      	= true;
				$foo->image_ratio_y         	= true;*/
				$foo->image_ratio_crop			= true;	
				$foo->image_x = $this->_conf['formatos_img'][$_seccion.$_pos.'_'.$_modulo]['ancho'];
				$foo->image_y = $this->_conf['formatos_img'][$_seccion.$_pos.'_'.$_modulo]['alto'];			
				//$foo->image_background_color 	= '#00000';			
				$foo->process($this->_conf['ruta_img_cargadas'] . $_seccion . DS);
			}
			if($foo->processed){
				
				$chica = new upload($foo->file_dst_pathname);
				$chica->image_resize = true;
				$chica->image_ratio_crop = 'T';
				$chica->image_x = $this->_conf['formatos_img']['thumb']['ancho'];
				$chica->image_y = $this->_conf['formatos_img']['thumb']['alto'];
				$chica->process($this->_conf['ruta_img_cargadas'] . $_seccion . DS . 'thumb'. DS);
								
				$_nombre = $_FILES['file']['name'];
				// Base Datos
				if($_modulo==4){
					$_imagen = $this->_trabajosGestion->cargarImgDB($this->_sess->get('carga_actual'), $foo->file_dst_name, $_nombre, $_pos, $_seccion, $this->_conf['ruta_img_cargadas'],$_modulo);
				}else{
					$_imagen = $this->_trabajosGestion->cargarImgDB($this->_sess->get('carga_actual'), $_nombre_interno . '.jpg', $_nombre, $_pos, $_seccion, $this->_conf['ruta_img_cargadas'],$_modulo);
				}
				if($_imagen) $this->_sess->set('img_id', $_imagen);
			}
		}
	}
	
	
	public function cargImgSolEditar($_seccion,$_modulo,$_pos)
	{
		if (!empty($_FILES)) {
			if($this->_sess->get('img_id')) $this->_sess->destroy('img_id');
			$_info = pathinfo($_FILES['file']['name']);
			$_nombre_archivo =  basename($_FILES['file']['name'], '.' . $_info['extension']);
			$_nombre_interno = admin::cadenaAleatoriaSegura(15);
			//$_formatos_img = Conf\formatos_img::data();
			$size = getimagesize($_FILES['file']['tmp_name']);
	
			/*if($_modulo==4){
				$foo = new upload($_FILES['file']);
				$foo->file_new_name_body 		= $_nombre_interno;
				$foo->image_convert 			= 'jpg';
				$foo->process($this->_conf['ruta_img_cargadas'] . $_seccion . DS);
			}else{
				$foo = new upload($_FILES['file']);
				$foo->file_new_name_body 		= $_nombre_interno;
				$foo->image_convert 			= 'jpg';
				$foo->image_resize          	= true;				
				$foo->image_ratio_crop			= true;	
				$foo->image_x = $this->_conf['formatos_img'][$_seccion.$_pos.'_'.$_modulo]['ancho'];
				$foo->image_y = $this->_conf['formatos_img'][$_seccion.$_pos.'_'.$_modulo]['alto'];			
				//$foo->image_background_color 	= '#00000';			
				$foo->process($this->_conf['ruta_img_cargadas'] . $_seccion . DS);
			}
			
			if($foo->processed){
				
			
				$chica = new upload($foo->file_dst_pathname);
				$chica->image_resize = true;
				$chica->image_ratio_crop = 'T';
				$chica->image_x = $this->_conf['formatos_img']['thumb']['ancho'];
				$chica->image_y = $this->_conf['formatos_img']['thumb']['alto'];
				$chica->process($this->_conf['ruta_img_cargadas'] . $_seccion . DS . 'thumb'. DS);
								
				$_nombre = $_FILES['file']['name'];
				// Base Datos
				$_imagen = $this->_trabajosGestion->cargarImgDB($this->_sess->get('edicion_actual'), $_nombre_interno . '.jpg', $_nombre, $_pos, $_seccion, $this->_conf['ruta_img_cargadas'], $_modulo);
				
				if($_imagen) $this->_sess->set('img_id', $_imagen);
			}*/
			
			
			
			if($_modulo==4){
				$foo = new upload($_FILES['file']);
				$foo->file_new_name_body 		= $_nombre_interno;
				//$foo->image_convert 			= 'jpg';
				$foo->process($this->_conf['ruta_img_cargadas'] . $_seccion . DS);
			}else{
				$foo = new upload($_FILES['file']);
				$foo->file_new_name_body 		= $_nombre_interno;
				$foo->image_convert 			= 'jpg';
				$foo->image_resize          	= true;
				/*$foo->image_ratio_fill      	= true;
				$foo->image_ratio_y         	= true;*/
				$foo->image_ratio_crop			= true;	
				$foo->image_x = $this->_conf['formatos_img'][$_seccion.$_pos.'_'.$_modulo]['ancho'];
				$foo->image_y = $this->_conf['formatos_img'][$_seccion.$_pos.'_'.$_modulo]['alto'];			
				//$foo->image_background_color 	= '#00000';			
				$foo->process($this->_conf['ruta_img_cargadas'] . $_seccion . DS);
			}
			if($foo->processed){
				
				$chica = new upload($foo->file_dst_pathname);
				$chica->image_resize = true;
				$chica->image_ratio_crop = 'T';
				$chica->image_x = $this->_conf['formatos_img']['thumb']['ancho'];
				$chica->image_y = $this->_conf['formatos_img']['thumb']['alto'];
				$chica->process($this->_conf['ruta_img_cargadas'] . $_seccion . DS . 'thumb'. DS);
								
				$_nombre = $_FILES['file']['name'];
				// Base Datos
				if($_modulo==4){
					$_imagen = $this->_trabajosGestion->cargarImgDB($this->_sess->get('edicion_actual'), $foo->file_dst_name, $_nombre, $_pos, $_seccion, $this->_conf['ruta_img_cargadas'],$_modulo);
				}else{
					$_imagen = $this->_trabajosGestion->cargarImgDB($this->_sess->get('edicion_actual'), $_nombre_interno . '.jpg', $_nombre, $_pos, $_seccion, $this->_conf['ruta_img_cargadas'],$_modulo);
				}
				if($_imagen) $this->_sess->set('img_id', $_imagen);
			}
			
			
			
			
		}
	}
		
	public function cargImgCasos($_seccion,$_pos,$_val)
	{
		if (!empty($_FILES)) {
			if($this->_sess->get('img_id')) $this->_sess->destroy('img_id');
			$_info = pathinfo($_FILES['file']['name']);
			$_nombre_archivo =  basename($_FILES['file']['name'], '.' . $_info['extension']);
			$_nombre_interno = admin::cadenaAleatoriaSegura(15);
			$size = getimagesize($_FILES['file']['tmp_name']);
			
			$foo = new upload($_FILES['file']);
			$foo->file_new_name_body 		= $_nombre_interno;
			$foo->image_convert 			= 'jpg';
			$foo->image_resize          	= true;
			/*$foo->image_ratio_fill      	= true;
			$foo->image_ratio_y         	= true;*/
			$foo->image_ratio_crop			= true;	
			$foo->image_x = $this->_conf['formatos_img'][$_seccion.'_'.$_val]['ancho'];
			$foo->image_y = $this->_conf['formatos_img'][$_seccion.'_'.$_val]['alto'];			
			//$foo->image_background_color 	= '#00000';			
			$foo->process($this->_conf['ruta_img_cargadas'] . $_seccion . DS);
			
			if($foo->processed){
				
				$chica = new upload($foo->file_dst_pathname);
				$chica->image_resize = true;
				$chica->image_ratio_crop = 'T';
				$chica->image_x = $this->_conf['formatos_img']['thumb']['ancho'];
				$chica->image_y = $this->_conf['formatos_img']['thumb']['alto'];
				$chica->process($this->_conf['ruta_img_cargadas'] . $_seccion . DS . 'thumb'. DS);
								
				$_nombre = $_FILES['file']['name'];
				// Base Datos
				$_imagen = $this->_trabajosGestion->cargarImgDB($this->_sess->get('carga_actual'), $_nombre_interno . '.jpg', $_nombre, $_pos, $_seccion, $this->_conf['ruta_img_cargadas']);
				if($_imagen) $this->_sess->set('img_id', $_imagen);
			}
		}
	}
	
	
	public function cargImgCasosEditar($_seccion,$_pos,$_val)
	{
		if (!empty($_FILES)) {
			if($this->_sess->get('img_id')) $this->_sess->destroy('img_id');
			$_info = pathinfo($_FILES['file']['name']);
			$_nombre_archivo =  basename($_FILES['file']['name'], '.' . $_info['extension']);
			$_nombre_interno = admin::cadenaAleatoriaSegura(15);
			//$_formatos_img = Conf\formatos_img::data();
			$size = getimagesize($_FILES['file']['tmp_name']);
	
			/*if($_modulo==4){
				$foo = new upload($_FILES['file']);
				$foo->file_new_name_body 		= $_nombre_interno;
				$foo->image_convert 			= 'jpg';
				$foo->process($this->_conf['ruta_img_cargadas'] . $_seccion . DS);
			}else{
				$foo = new upload($_FILES['file']);
				$foo->file_new_name_body 		= $_nombre_interno;
				$foo->image_convert 			= 'jpg';
				$foo->image_resize          	= true;				
				$foo->image_ratio_crop			= true;	
				$foo->image_x = $this->_conf['formatos_img'][$_seccion.$_pos.'_'.$_modulo]['ancho'];
				$foo->image_y = $this->_conf['formatos_img'][$_seccion.$_pos.'_'.$_modulo]['alto'];			
				//$foo->image_background_color 	= '#00000';			
				$foo->process($this->_conf['ruta_img_cargadas'] . $_seccion . DS);
			}
			
			if($foo->processed){
				
			
				$chica = new upload($foo->file_dst_pathname);
				$chica->image_resize = true;
				$chica->image_ratio_crop = 'T';
				$chica->image_x = $this->_conf['formatos_img']['thumb']['ancho'];
				$chica->image_y = $this->_conf['formatos_img']['thumb']['alto'];
				$chica->process($this->_conf['ruta_img_cargadas'] . $_seccion . DS . 'thumb'. DS);
								
				$_nombre = $_FILES['file']['name'];
				// Base Datos
				$_imagen = $this->_trabajosGestion->cargarImgDB($this->_sess->get('edicion_actual'), $_nombre_interno . '.jpg', $_nombre, $_pos, $_seccion, $this->_conf['ruta_img_cargadas'], $_modulo);
				
				if($_imagen) $this->_sess->set('img_id', $_imagen);
			}*/
			
			$foo = new upload($_FILES['file']);
			$foo->file_new_name_body 		= $_nombre_interno;
			$foo->image_convert 			= 'jpg';
			$foo->image_resize          	= true;
			/*$foo->image_ratio_fill      	= true;
			$foo->image_ratio_y         	= true;*/
			$foo->image_ratio_crop			= true;	
			$foo->image_x = $this->_conf['formatos_img'][$_seccion.'_'.$_val]['ancho'];
			$foo->image_y = $this->_conf['formatos_img'][$_seccion.'_'.$_val]['alto'];		
			//$foo->image_background_color 	= '#00000';			
			$foo->process($this->_conf['ruta_img_cargadas'] . $_seccion . DS);
			if($foo->processed){
				
				$chica = new upload($foo->file_dst_pathname);
				$chica->image_resize = true;
				$chica->image_ratio_crop = 'T';
				$chica->image_x = $this->_conf['formatos_img']['thumb']['ancho'];
				$chica->image_y = $this->_conf['formatos_img']['thumb']['alto'];
				$chica->process($this->_conf['ruta_img_cargadas'] . $_seccion . DS . 'thumb'. DS);
								
				$_nombre = $_FILES['file']['name'];
				// Base Datos
				$_imagen = $this->_trabajosGestion->cargarImgDB($this->_sess->get('edicion_actual'), $_nombre_interno . '.jpg', $_nombre, $_pos, $_seccion, $this->_conf['ruta_img_cargadas']);
				if($_imagen) $this->_sess->set('img_id', $_imagen);
			}
			
			
			
			
		}
	}
	
	public function traerImagenfinal($_accion, $_tipo, $_pos='')
	{	
		//if($_formato == false) exit("NO se pudo procesar la imágen");
		//$_formatos_img = Conf\formatos_img::data();
		//if(!in_array($_formato, $_formatos_img['formatos'])) exit("NO se pudo procesar la imágen");
		$_data_img = $this->_trabajosGestion->traerDataBasicaImagen($this->_sess->get('img_id'));
		// Imagen principal
		//$_img = $this->_conf['ruta_img_cargadas'] . $_seccion . "/" . $_data_img->path;
		if($_pos==''){
			$_img = $this->_conf['ruta_img_cargadas'] . $_tipo."/thumb/" .$_data_img->path;
		}else{
			$_img = $this->_conf['ruta_img_cargadas'] . $_tipo."/".$_pos."/thumb/" .$_data_img->path;
		}
		
		if(!file_exists($_img)) exit("Ha ocurrido un error. La imágen no existe o se ha corrompido.");
		
		/*if($_accion=='cargar'){
			echo "<div id=\"" . $this->_sess->get('img_id') . "\">
				<img src=\"" . $this->_conf['base_url'] . "public/img/subidas/".$_seccion."/" . $_data_img->path . "\" class=\"img-thumbnail\">
			</div>";
		}else{
			echo "<div id=\"" . $this->_sess->get('img_id') . "\">
				<img src=\"" . $this->_conf['base_url'] . "public/img/subidas/".$_seccion."/" . $_data_img->path . "\" class=\"img-thumbnail\">
			</div>";
		}*/
	    if($_pos==''){	

	    	if($_tipo=='seo'){
	    		echo "<div id=\"" . $this->_sess->get('img_id') . "\">
					<img src=\"" . $this->_conf['base_url'] . "public/img/subidas/".$_tipo."/thumb/" . $_data_img->path . "\" class=\"img-thumbnail\">
					<input type=\"hidden\" name=\"_seo_img\" value=\"".$_data_img->id."\">
				</div>";
	    	}else{
	    		echo "<div id=\"" . $this->_sess->get('img_id') . "\">
					<img src=\"" . $this->_conf['base_url'] . "public/img/subidas/".$_tipo."/thumb/" . $_data_img->path . "\" class=\"img-thumbnail\">
				</div>";
	    	}	
			
		}else{
			echo "<div id=\"" . $this->_sess->get('img_id') . "\">
					<img src=\"" . $this->_conf['base_url'] . "public/img/subidas/".$_tipo."/".$_pos."/thumb/" . $_data_img->path . "\" class=\"img-thumbnail\">
				</div>";
		}	
		$this->_sess->destroy('img_id');
		
		exit();
	}
	
	
	public function traerImagenesGalerias($_accion,$_tipo,$_pos)
	{
		if(isset($_SESSION['lote_img_gal']) && !empty($_SESSION['lote_img_gal'])){

			$_ids = array();

			foreach ($_SESSION['lote_img_gal'] as $key => $value){
				$_ids[] = $key;
			}

			$_ids = implode(',', $_ids);
			$_data = $this->_trabajosGestion->traerImagenesPorLote($_ids);
			
			//echo $_ids;
			/*echo "<pre>";
			print_r($_data);*/
			
			unset($_SESSION['lote_img_gal']);

			if(is_array($_data) && !empty($_data)){

				$_imp = '';

				//for($im=0;$im<count($_data);$im++){
				foreach($_data as $data){
					
					$_img = $this->_conf['ruta_img_cargadas'] . $_tipo."/".$_pos."/thumb/" .$data->path;

					//if(file_exists("public/img/subidas/".$_seccion."/" .  $data->path)){
					if(file_exists($_img)){
						
						if($_accion=='cargar'){
							$_imp .= "<div><img src=\"" . $this->_conf['base_url'] . "public/img/subidas/".$_tipo."/".$_pos."/thumb/" . $data->path . "\" class=\"img-thumbnail\"></div>";;
						}else{
							$_imp .= "<div><img src=\"" . $this->_conf['base_url'] . "public/img/subidas/".$_tipo."/".$_pos."/thumb/" . $data->path . "\" class=\"img-thumbnail\">
										<input name=\"eliminar_gal[]\" type=\"checkbox\" value=\"".$data->id."\" /> Eliminar Imagen</div>";
						}

						
					}
				}
				//unset($_SESSION['lote_img_gal']);

				echo $_imp;
				exit;
			}
			exit("<p>No se encontraron datos de las imagenes cargadas 1.</p>");
		}
		exit("<p>No se encontraron datos de las imagenes cargadas 2.</p>");
	}
	
	
	public function traerImagenfinalMod4($_seccion)
	{	
		//if($_formato == false) exit("NO se pudo procesar la imágen");
		//$_formatos_img = Conf\formatos_img::data();
		//if(!in_array($_formato, $_formatos_img['formatos'])) exit("NO se pudo procesar la imágen");
		$this->_view->data_img = $this->_trabajosGestion->traerDataBasicaImagen($this->_sess->get('img_id'));
		// Imagen principal
		$_img = $this->_conf['ruta_img_cargadas'] . $_seccion . "/" . $this->_view->data_img->path;
		if(!file_exists($_img)) exit("Ha ocurrido un error. La imágen no existe o se ha corrompido.");
		
		
	    echo "<div id=\"" . $this->_sess->get('img_id') . "\">
	        <img src=\"" . $this->_conf['base_url'] . "public/img/subidas/".$_seccion."/" . $this->_view->data_img->path . "\" class=\"img-thumbnail\">
	    </div>";		
		
		$this->_sess->destroy('img_id');
		
		exit();
	}
	
		
		
		
		
		
		
	
	public function listar($pagina = false)
    {
		$this->_acl->acceso('encargado_access');
		
		$this->_view->setJs(array('funciones'));
		
		$pagina = (!validador::filtrarInt($pagina)) ? false : (int) $pagina;
		$paginador = new Paginador();
		
		$img = $this->_trabajosGestion->traerDatosImagenes();
		
		$this->_view->imagenes = $paginador->paginar($img, $pagina, 20);
		$this->_view->paginacion = $paginador->getView('paginador-bootstrap', 'administrador/imagenes/listar');
			
		$this->_view->titulo = 'Administrador - Seguimiento';
        $this->_view->renderizar('listar', 'imagenes');	
    }
	
	
	/*public function orden($_id, $_orden)
	{
		
		$this->_acl->acceso('encargado_access');
		
		$_id = (int) $_id;
		$_orden = (int) $_orden;
		
		$this->_trabajosGestion->cambiarOrdenDestacados($_id, $_orden);
		$this->redireccionar('administrador/destacados/listar');
		
	}*/
	
	
	public function editar($_id)
	{
		//$this->_acl->acceso('admin_access');
		$this->_acl->acceso('encargado_access');
		
		validador::validarParametroInt($_id,$this->_conf['base_url']);		
		
		$this->_view->trabajo = $this->_trabajosGestion->traerDatosImagen($_id);		
	
		//$this->_view->estados = $this->_trabajosGestion->traerEstados();		
		
		/*echo "<pre>";
		print_r($this->_view->imgs);
		echo "</pre>";*/
		
		$this->_view->setJs(array('funciones'));
		
									
		
				
			
		if($_POST){
			
			
			if($_POST['envio01'] == 1){
			
				$this->_view->data = $_POST;	
				
				/*echo "<pre>";
				print_r($_FILES);
				echo "</pre>";
				exit;*/
				
								
				//if($_FILES['imgGaleria']['name'][0]!=''){
				if(is_array($_FILES['imagen']) && $_FILES['imagen']['name'][0]!=''){
					
						/*$imagen = getimagesize($_FILES['imagen']['tmp_name']);   
  						$ancho = $imagen[0];          
  						$alto = $imagen[1];	
						
						$val = ($alto>$ancho) ? '1' : 'T';	*/
					
						$upload = new upload($_FILES['imagen'], 'es_Es');
						$upload->allowed = array('image/*');
						$upload->file_new_name_body = uniqid();
						$upload->process($this->_conf['ruta_img_cargadas'] . 'originales' . DS);
						
						if($upload->processed){
							
							$thumb = new Upload($upload->file_dst_pathname);
							$thumb->image_resize = true;
							$thumb->image_ratio_crop = 'T';
							$thumb->image_x = $this->_conf['formatos_img']['thumb']['ancho'];
							$thumb->image_y = $this->_conf['formatos_img']['thumb']['alto'];
							/*$tamañoOrig = admin::CapturarAnchoAlto($upload->file_dst_pathname);
							$thumb->image_y =  round(($thumb->image_x/$tamañoOrig[0])*$tamañoOrig[1]);*/
							$thumb->file_name_body_pre = 'thumb_';
							$thumb->process($this->_conf['ruta_img_cargadas']);
			
							$mediana = new Upload($upload->file_dst_pathname);
							$mediana->image_resize = true;
							$mediana->image_ratio_crop = 'T';
							$mediana->image_x = $this->_conf['formatos_img']['mediana']['ancho'];
							$mediana->image_y = $this->_conf['formatos_img']['mediana']['alto'];
/*							$tamañoOrig = admin::CapturarAnchoAlto($upload->file_dst_pathname);
							$mediana->image_y =  round(($mediana->image_x/$tamañoOrig[0])*$tamañoOrig[1]);
*/							$mediana->file_name_body_pre = 'mediana_';
							$mediana->process($this->_conf['ruta_img_cargadas']);
							
							$grande = new Upload($upload->file_dst_pathname);
							$grande->image_resize = true;
							$grande->image_ratio_crop = true;
							$grande->image_x = $this->_conf['formatos_img']['grande']['ancho'];
							//$grande->image_y = $this->_conf['formatos_img']['grande']['alto'];
							$tamañoOrig = admin::CapturarAnchoAlto($upload->file_dst_pathname);
							$grande->image_y =  round(($grande->image_x/$tamañoOrig[0])*$tamañoOrig[1]);
							$grande->file_name_body_pre = 'grande_';
							$grande->process($this->_conf['ruta_img_cargadas']);
			
							$imagen = contenidos_imagene::find(array('conditions' => array('identificador = ?', $this->_view->trabajo->identificador)));
							if($imagen){
								$imagen->path = $upload->file_dst_name;
								$imagen->save();
							
							}else{
								$imagen = new contenidos_imagene();
								$imagen->identificador = $this->_view->trabajo->identificador;
								$imagen->path = $upload->file_dst_name;
								$imagen->orden = 0;
								$imagen->fecha_alt = date("Y-m-d H:i:s", strtotime('now'));
								$imagen->save();
							}
							
							$zip = new ZipArchive;
							$zip->open($this->_conf['ruta_archivos_descargas'].$upload->file_dst_name.".zip",ZipArchive::CREATE);
							$zip->addFile($this->_conf['ruta_img_cargadas'].$upload->file_dst_name);
							$zip->close();
														
							
						}else{
							throw new Exception('Error al cargar la imagen');
						}
				}
				
				
				
				
							
				
				$_editar = contenidos_datos_imagene::find($this->_view->trabajo->id);
				$_editar->dia = $this->_view->data['dia'];
				$_editar->titulo = validador::getTexto('titulo');
				$_editar->descripcion = validador::getTexto('desc');
				$_editar->identificador = $this->_view->trabajo->identificador;
				$_editar->save();
				
				
				$this->redireccionar('administrador/imagenes/listar');
											
				
			}
		}
	
		$this->_view->titulo = 'Administrador - Seguimiento';
        $this->_view->renderizar('editar', 'imagenes');	
    }
	
	
	
	
	
	public function cargar($_categoria = null)
    {	
		$this->_acl->acceso('encargado_access');
		//$this->_view->_categoria = (int) $_categoria;
	
		/*if(!$this->_sess->get('carga_actual')){
			$this->_sess->set('carga_actual', rand(1135687452,9999999999));
		}*/
		
		//$this->_view->setCssPlugin(array('tinymce.min'));
		$this->_view->setJs(array('funciones'));
		
			
		
		
		if($_POST){
			
			
			if($_POST['envio01'] == 1){
				
				$this->_view->data = $_POST;
				
			
				/*echo "<pre>";
				print_r($_POST);
				print_r($_FILES);
				echo "</pre>";
				exit;*/
				
				
				if($_FILES['imagen']){					
					
					$files = array();
					foreach ($_FILES['imagen'] as $k => $l) {
						foreach ($l as $i => $v) {
							if (!array_key_exists($i, $files))
								$files[$i] = array();
							$files[$i][$k] = $v;
						}
					}
					
					/*echo'<pre>';
					print_r($files);
					echo $this->_view->data['ruta_img'];
					exit;
						*/	
					$cont=0;			
					foreach ($files as $file) {
						
						$this->_sess->set('carga_actual', rand(1135687452,9999999999));
						
						if($file['error']!=4){
							$upload = new Upload($file);
							//$upload->allowed = array('image');
							$upload->file_new_name_body = uniqid();
							if ($upload->uploaded) {
								$upload->Process($this->_conf['ruta_img_cargadas'] . 'originales' . DS);
							
								if($upload->processed){				
									
									$thumb = new Upload($upload->file_dst_pathname);
									$thumb->image_resize = true;
									$thumb->image_ratio_crop = 'T';
									$thumb->image_x = $this->_conf['formatos_img']['thumb']['ancho'];
									$thumb->image_y = $this->_conf['formatos_img']['thumb']['alto'];
									/*$tamañoOrig = admin::CapturarAnchoAlto($upload->file_dst_pathname);
									$thumb->image_y =  round(($thumb->image_x/$tamañoOrig[0])*$tamañoOrig[1]);*/
									$thumb->file_name_body_pre = 'thumb_';
									$thumb->process($this->_conf['ruta_img_cargadas']);
					
									$mediana = new Upload($upload->file_dst_pathname);
									$mediana->image_resize = true;
									$mediana->image_ratio_crop = 'T';
									$mediana->image_x = $this->_conf['formatos_img']['mediana']['ancho'];
									$mediana->image_y = $this->_conf['formatos_img']['mediana']['alto'];
									/*$tamañoOrig = admin::CapturarAnchoAlto($upload->file_dst_pathname);
									$mediana->image_y =  round(($mediana->image_x/$tamañoOrig[0])*$tamañoOrig[1]);*/
									$mediana->file_name_body_pre = 'mediana_';
									$mediana->process($this->_conf['ruta_img_cargadas']);
									
									$grande = new Upload($upload->file_dst_pathname);
									$grande->image_resize = true;
									$grande->image_ratio_crop = true;
									$grande->image_x = $this->_conf['formatos_img']['grande']['ancho'];
									//$grande->image_y = $this->_conf['formatos_img']['grande']['alto'];
									$tamañoOrig = admin::CapturarAnchoAlto($upload->file_dst_pathname);
									$grande->image_y =  round(($grande->image_x/$tamañoOrig[0])*$tamañoOrig[1]);
									$grande->file_name_body_pre = 'grande_';
									$grande->process($this->_conf['ruta_img_cargadas']);
					
									//
									/*$imagen = contenidos_imagene::find(array('conditions' => array('identificador = ?', $this->_sess->get('carga_actual'))));
									if($imagen){
										$imagen->path = $upload->file_dst_name;
										$imagen->bajada = $_POST['bajada'][$i];
										$imagen->save();
									
									}else{*/
									$imagen = new contenidos_imagene();
									$imagen->identificador = $this->_sess->get('carga_actual');
									$imagen->path = $upload->file_dst_name;
									$imagen->orden = 0;
									$imagen->fecha_alt = date("Y-m-d H:i:s", strtotime('now'));
									$imagen->save();
									
									$reel = contenidos_datos_imagene::create(array(					
										'dia'				=> $this->_view->data['dia'][$cont],
										'titulo'			=> $this->_view->data['titulo'][$cont],
										'descripcion'		=> $this->_view->data['desc'][$cont],	
										'identificador'		=> $this->_sess->get('carga_actual'),					
										'nombre_orig_img'	=> '',
										'fecha'				=> date('Y-m-d'),
									));
									
									$this->_sess->destroy('carga_actual');
									
									
								}else{
									throw new Exception('Error al cargar la imagen');
								}
							}else{
								throw new Exception('Error al cargar la imagen');
							}
						}
					$cont++;
					}
					
					
				}
				
			//exit;
				
				
			/*	if(is_array($_FILES['imagen']) && $_FILES['imagen']['name'][0]!=''){
					
					for($i=0;$i< count($_FILES['imagen']['name']);$i++){
					
						$upload = new upload($_FILES['imagen'], 'es_Es');
						$upload->allowed = array('image/*');
						$upload->file_new_name_body = uniqid();
						$upload->process($this->_conf['ruta_img_cargadas'] . 'originales' . DS);
						
						if($upload->processed){
							
							$thumb = new upload($upload->file_dst_pathname);
							$thumb->image_resize = true;
							$thumb->image_ratio_crop = true;
							$thumb->image_x = $this->_conf['formatos_img']['thumb']['ancho'];
							//$thumb->image_y = $this->_conf['formatos_img']['thumb']['alto'];
							$tamañoOrig = admin::CapturarAnchoAlto($upload->file_dst_pathname);
							$thumb->image_y =  round(($thumb->image_x/$tamañoOrig[0])*$tamañoOrig[1]);
							$thumb->file_name_body_pre = 'thumb_';
							$thumb->process($this->_conf['ruta_img_cargadas']);
			
							$mediana = new upload($upload->file_dst_pathname);
							$mediana->image_resize = true;
							$mediana->image_ratio_crop = true;
							$mediana->image_x = $this->_conf['formatos_img']['mediana']['ancho'];
							//$grande->image_y = $this->_conf['formatos_img']['mediana']['alto'];
							$tamañoOrig = admin::CapturarAnchoAlto($upload->file_dst_pathname);
							$mediana->image_y =  round(($mediana->image_x/$tamañoOrig[0])*$tamañoOrig[1]);
							$mediana->file_name_body_pre = 'mediana_';
							$mediana->process($this->_conf['ruta_img_cargadas']);
							
							$grande = new upload($upload->file_dst_pathname);
							$grande->image_resize = true;
							$grande->image_ratio_crop = true;
							$grande->image_x = $this->_conf['formatos_img']['grande']['ancho'];
							//$grande->image_y = $this->_conf['formatos_img']['grande']['alto'];
							$tamañoOrig = admin::CapturarAnchoAlto($upload->file_dst_pathname);
							$grande->image_y =  round(($grande->image_x/$tamañoOrig[0])*$tamañoOrig[1]);
							$grande->file_name_body_pre = 'grande_';
							$grande->process($this->_conf['ruta_img_cargadas']);
			
							$imagen = new contenidos_imagene();
							$imagen->identificador = $this->_sess->get('carga_actual');
							$imagen->path = $upload->file_dst_name;
							$imagen->orden = 0;
							$imagen->fecha_alt = date("Y-m-d H:i:s", strtotime('now'));
							$imagen->save();
							
						}else{
							throw new Exception('Error al cargar la imagen');
						}
					}
				}*/
				
				
			/*for($i=0;$i<count($this->_view->data['dia']);$i++){
				$reel = contenidos_datos_imagene::create(array(					
					'dia'				=> $this->_view->data['dia'][$i],
					'titulo'			=> $this->_view->data['titulo'][$i],
					'descripcion'		=> $this->_view->data['desc'][$i],	
					'identificador'		=> $this->_sess->get('carga_actual'),					
					'nombre_orig_img'	=> '',
					'fecha'				=> date('Y-m-d'),
				));
			}*/
				
				
							
				
				
				$this->redireccionar('administrador/imagenes/listar');
			}	
		}
	
		$this->_view->titulo = 'Administrador - Seguimiento';
        $this->_view->renderizar('cargar', 'imagenes');	
    }
	
	public function borrar($_id)
	{
		$this->_acl->acceso('encargado_access');
		$_id = (int) $_id;
		
		//$this->_view->trabajo = $this->_trabajosGestion->traerTrabajo($_id);
		
		$this->_trabajosGestion->borrarImg($_id, $this->_conf['ruta_img_cargadas']);
		$this->redireccionar('administrador/imagenes/listar');
	}
	
	public function buscador()
	{
		$this->_acl->acceso('encargado_access');
		
		if($_POST){
			
			$_val = $_POST['valor'];
			
			$this->_view->imgs  = $this->_trabajosGestion->traerImgBuscador(ucwords(strtolower($_val)));
			
			/*echo "<pre>";
			print_r($this->_view->users);
			echo"</pre>";*/
			
			$_html = '<ul class="list-group">';
			foreach($this->_view->imgs as $imgs){           
        
				$_html .='<li class="list-group-item" onmouseover="this.classList.add(\'item_sobre\');" onmouseout="this.classList.remove(\'item_sobre\');">					
						<img style="width:200px;" class="img-thumbnail" src="'.$this->_conf['base_url'] . 'public/img/subidas/thumb_' . admin::traerImgChica($imgs->identificador)->path.'"/>';
						if($imgs->titulo!=''){
				$_html .='<span class="btn btn-info btn-sm">'.admin::convertirCaracteres($imgs->titulo).'</span>';
						}
				$_html .='&nbsp;
						<span class="btn btn-primary btn-sm">Día '.$imgs->dia.'</span>					
						<span class="flotar_derecha" style="margin-right:15px">				
							<a href="'.$this->_conf['url_enlace'].'administrador/imagenes/editar/'. $imgs->id.'" class="btn btn-success btn-sm">
								editar
							</a>
							<a href="'.$this->_conf['url_enlace'].'administrador/imagenes/borrar/'. $imgs->id.'" class="btn btn-danger btn-sm">
								Borrar
							</a>
						</span>
					</li>';
        
			}			
			$_html .='</ul>';
			
			echo $_html;
			
		}
	}
	
	
	
	
	
	
}