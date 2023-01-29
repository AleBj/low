<?php

use controllers\administradorController\administradorController;

class archivosController extends administradorController
{
	public $_trabajosGestion;
	
    public function __construct() 
    {
		parent::__construct();
		$this->getLibrary('class.validador');
		$this->getLibrary('class.admin');
		$this->_trabajosGestion = new admin();
		
		//$this->getLibrary('class.upload');
		
		$this->_error = 'has-error';
		$this->_filtro = '';
    }
    
   	
	
	
 	public function cargarArchivos($_directorio='')
    {
    	$this->_acl->acceso('encargado_access');
		
		if (!empty($_FILES)) {
	    	
	    	$filename = strtolower($_FILES['file']['name']);
			// $whitelist = array('jpg', 'png', 'gif', 'jpeg');
			$whitelist = array('pdf', 'ppt');  
			$backlist = array('php', 'php3', 'php4', 'phtml','exe','bat','js');
			$tmp = explode('.', $filename);
			$file_extension = end($tmp); 
			if(!in_array($file_extension, $whitelist)){
				$this->_sess->set('archivo_error', 'archivo invalido');
			    echo 'archivo invalido';
			    exit();
			}
			if(in_array($file_extension, $backlist)){
				$this->_sess->set('archivo_error', 'archivo invalido');
			    echo 'archivo invalido';
			    exit();
			}

	    	if($this->_sess->get('archivo_id')) $this->_sess->destroy('archivo_id');
			$_info = pathinfo($_FILES['file']['name']);
			$_nombre_archivo =  basename($_FILES['file']['name'], '.' . $_info['extension']);
			$_nombre_interno = admin::cadenaAleatoriaSegura(15);
			$_nombre = explode('.', $_FILES['file']['name']);
			$_nombre = $_nombre[0];
			$_formato = $_info['extension'];
			//$size = getimagesize($_FILES['file']['tmp_name']);
	    	//echo "<pre>";print_r($_FILES);exit;
			$_video =$this->_trabajosGestion->subirArchivo($_formato, $this->_sess->get('carga_actual'), 'file', $_nombre, $this->_conf['ruta_archivos']);
			if($_video) $this->_sess->set('archivo_id', $_video);
    	
    	}
    }
    public function editarArchivo($_directorio='')
    {
    	$this->_acl->acceso('encargado_access');
    	//$_id = (int) $_id;
    	if (!empty($_FILES)) { 

			$filename = strtolower($_FILES['file']['name']);
			// $whitelist = array('jpg', 'png', 'gif', 'jpeg');
			$whitelist = array('pdf', 'ppt');   
			$backlist = array('php', 'php3', 'php4', 'phtml','exe','bat','js');
			$tmp = explode('.', $filename);
			$file_extension = end($tmp); 
			if(!in_array($file_extension, $whitelist)){
				$this->_sess->set('archivo_error', 'archivo invalido');
			    echo 'archivo invalido';
			    exit();
			}
			if(in_array($file_extension, $backlist)){
				$this->_sess->set('archivo_error', 'archivo invalido');
			    echo 'archivo invalido';
			    exit();
			}

			if($this->_sess->get('archivo_id')) $this->_sess->destroy('archivo_id');
			$_info = pathinfo($_FILES['file']['name']);
			$_nombre_archivo =  basename($_FILES['file']['name'], '.' . $_info['extension']);
			$_nombre_interno = admin::cadenaAleatoriaSegura(15);
			$_nombre = explode('.', $_FILES['file']['name']);
			$_nombre = $_nombre[0];
			$_formato = $_info['extension'];
			//$size = getimagesize($_FILES['file']['tmp_name']);
	    	//echo "<pre>";print_r($_FILES);exit;    	
			$_video =$this->_trabajosGestion->subirArchivo($_formato, $this->_sess->get('edicion_actual'), 'file', $_nombre, $this->_conf['ruta_archivos']);
			if($_video) $this->_sess->set('archivo_id', $_video);
    	}
    }

    public function traerArchivoFinal($_directorio='')
	{	
		$this->_acl->acceso('encargado_access');

		if($this->_sess->get('archivo_error')){
			echo 'archivo invalido';
			$this->_sess->destroy('archivo_error');
			exit();
		}

		$_data = $this->_trabajosGestion->traerDataArchivo($this->_sess->get('archivo_id'));
		$_vid = $this->_conf['ruta_archivos']. $_data->path;
		// $_vid1 = $this->_conf['ruta_img_cargadas']. "anteriores/archivos/" . $_data->path;
		if(!file_exists($_vid)) exit("Ha ocurrido un error. El video no existe o se ha corrompido.");

		/*if(file_exists($this->_conf['ruta_img_cargadas'] . $_seccion . "/" . $_data_img->path)){
            $_url_img = $this->_conf['base_url'] . "public/img/subidas/".$_seccion . "/thumb/" . $_data_img->path;
        }else if(file_exists($this->_conf['ruta_img_cargadas'] . "anteriores/images/" . $_data_img->path)){
            $_url_img = $this->_conf['base_url'] . "public/img/subidas/anteriores/images/". $_data_img->path; 
        }else{
        	exit("Ha ocurrido un error. La imágen no existe o se ha corrompido.");
        }*/
		
		
	    echo '<h4>'.$_data->path.' <a href="javascript:void(0);" class="btn btn-danger btn-xs _borrar_'.$_data->id.'" title="Borrar">X</a></h4>
	    <script>
          $(document).ready(function () {
                $("._borrar_'.$_data->id.'").click(function () {
                    swal({
                        title: "Estas seguro de borrar este contenido?",
                        text: "Los datos se perderán permanentemente!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Si, que se borre!",
                        cancelButtonText: "No, mejor no!",
                        closeOnConfirm: false,
                        closeOnCancel: false },
                    function (isConfirm) {
                        if (isConfirm) {
                            var url= _root_ + "administrador/archivos/borrar";
                            var dataString = "_id='.$_data->id.'&_csrf='.$this->_sess->get('_csrf').'";
                            $.ajax({
                                    type: "POST",
                                    url: url,
                                    data: dataString,
                                    success: function(data){
                                      if(data=="ok"){
                                        swal("Borrado!", "El contenido se borró con exito.", "success");
                                        setTimeout(function() {
                                            location.reload();
                                        }, 200);
                                      }else{
                                        swal("Cancelado", "No se puede borrar porque el contenido esta en uso", "error");
                                      }
                                        
                                        
                                    }
                            });
                        } else {
                            swal("Cancelado", "El contenido esta guardado", "error");
                        }
                    });
                });
            });
        </script>';		
		
		$this->_sess->destroy('archivo_id');
		
		exit();
	}
	
	
	public function cargarArchivosExcel($_directorio='')
    {
    	$this->_acl->acceso('encargado_access');
		
		if (!empty($_FILES)) {
	    	
	    	$filename = strtolower($_FILES['file']['name']);
			// $whitelist = array('jpg', 'png', 'gif', 'jpeg');
			$whitelist = array('xlsx', 'xls');  
			$backlist = array('php', 'php3', 'php4', 'phtml','exe','bat','js');
			$tmp = explode('.', $filename);
			$file_extension = end($tmp); 
			if(!in_array($file_extension, $whitelist)){
				$this->_sess->set('archivo_error', 'archivo invalido');
			    echo 'archivo invalido';
			    exit();
			}
			if(in_array($file_extension, $backlist)){
				$this->_sess->set('archivo_error', 'archivo invalido');
			    echo 'archivo invalido 2';
			    exit();
			}

	    	if($this->_sess->get('archivo_id')) $this->_sess->destroy('archivo_id');
			$_info = pathinfo($_FILES['file']['name']);
			$_nombre_archivo =  basename($_FILES['file']['name'], '.' . $_info['extension']);
			$_nombre_interno = admin::cadenaAleatoriaSegura(15);
			$_nombre = explode('.', $_FILES['file']['name']);
			$_nombre = $_nombre[0];
			$_formato = $_info['extension'];
			//$size = getimagesize($_FILES['file']['tmp_name']);
	    	//echo "<pre>";print_r($_FILES);exit;
			$_video =$this->_trabajosGestion->subirExcel($_formato, $this->_sess->get('carga_actual'), 'file', $_nombre, $this->_conf['ruta_archivos']);
			if($_video) $this->_sess->set('archivo_id', $_video);
    	
    	}
    }
    public function editarArchivoExcel($_directorio='')
    {
    	$this->_acl->acceso('encargado_access');
    	//$_id = (int) $_id;
    	if (!empty($_FILES)) { 

			$filename = strtolower($_FILES['file']['name']);
			// $whitelist = array('jpg', 'png', 'gif', 'jpeg');
			$whitelist = array('xlsx', 'xls');  
			$backlist = array('php', 'php3', 'php4', 'phtml','exe','bat','js');
			$tmp = explode('.', $filename);
			$file_extension = end($tmp); 
			if(!in_array($file_extension, $whitelist)){
				$this->_sess->set('archivo_error', 'archivo invalido');
			    echo 'archivo invalido';
			    exit();
			}
			if(in_array($file_extension, $backlist)){
				$this->_sess->set('archivo_error', 'archivo invalido');
			    echo 'archivo invalido';
			    exit();
			}

			if($this->_sess->get('archivo_id')) $this->_sess->destroy('archivo_id');
			$_info = pathinfo($_FILES['file']['name']);
			$_nombre_archivo =  basename($_FILES['file']['name'], '.' . $_info['extension']);
			$_nombre_interno = admin::cadenaAleatoriaSegura(15);
			$_nombre = explode('.', $_FILES['file']['name']);
			$_nombre = $_nombre[0];
			$_formato = $_info['extension'];
			//$size = getimagesize($_FILES['file']['tmp_name']);
	    	//echo "<pre>";print_r($_FILES);exit;    	
			$_video =$this->_trabajosGestion->subirExcel($_formato, $this->_sess->get('edicion_actual'), 'file', $_nombre, $this->_conf['ruta_archivos']);
			if($_video) $this->_sess->set('archivo_id', $_video);
    	}
    }

	public function traerArchivoFinalExcel($_directorio='')
	{	
		$this->_acl->acceso('encargado_access');

		if($this->_sess->get('archivo_error')){
			echo 'archivo invalido';
			$this->_sess->destroy('archivo_error');
			exit();
		}

		$_data = $this->_trabajosGestion->traerDataExcel($this->_sess->get('archivo_id'));
		$_vid = $this->_conf['ruta_archivos']. $_data->path;
		if(!file_exists($_vid)) exit("Ha ocurrido un error. El video no existe o se ha corrompido.");
		
		
	    echo '<h4>'.$_data->path.'</h4>';		
		
		$this->_sess->destroy('archivo_id');
		
		exit();
	}
	
	public function borrar()
	{
		$this->_acl->acceso('encargado_access');
		//$_id = (int) $_id;
		

		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){		
			
				$_id = (int) $_POST['_id'];
		

				$_borrar = $this->_trabajosGestion->borrarArchivo($_id, $this->_conf['ruta_archivos']);
				if ($_borrar==false) {
					echo "enuso";
				}else{
					echo "ok";
					$this->_sess->destroy('archivo_id');
				}
				

			}else{
				$this->redireccionar('error/access/404');
			}
		}
		

	}
	
	
		
		
		
		
	
	
	
	
	
	
}