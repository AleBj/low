<?php

use controllers\administradorController\administradorController;

class videosController extends administradorController
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
    
   	/*public function cargarvideonativonoticia()
    {
        if($_POST){

            if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){

                if($this->_sess->get('id_video_nativo')) $this->_sess->destroy('id_video_nativo');

                $_video_nativo_id = $this->_videosGestion->subirArchivo('mp4', '_archivo_video_nativo', validador::getPostParam('nombre_video_nativo'), $this->_sess->get('carga_actual'));

                $this->_sess->set('id_video_nativo', $_video_nativo_id);
								
                echo 2;
                exit();
            }
        }
    }*/
	
	
 	public function cargarvideonativo()
    {
    	$this->_acl->acceso('encargado_access');
		
		if (!empty($_FILES)) {

			// echo "<pre>";print_r($_FILES);exit;
	    	
	    	$filename = strtolower($_FILES['file']['name']);
			// $whitelist = array('jpg', 'png', 'gif', 'jpeg');
			$whitelist = array('mp4','avi','mpeg');  
			$backlist = array('php', 'php3', 'php4', 'phtml','exe','bat','js');
			$tmp = explode('.', $filename);
			$file_extension = end($tmp); 
			if(!in_array($file_extension, $whitelist)){
				$this->_sess->set('video_error', 'archivo invalido');
			    echo 'archivo invalido';
			    exit();
			}
			if(in_array($file_extension, $backlist)){
				$this->_sess->set('video_error', 'archivo invalido');
			    echo 'archivo invalido';
			    exit();
			}

	    	if($this->_sess->get('video_id')) $this->_sess->destroy('video_id');
			$_info = pathinfo($_FILES['file']['name']);
			$_nombre_archivo =  basename($_FILES['file']['name'], '.' . $_info['extension']);
			$_nombre_interno = admin::cadenaAleatoriaSegura(15);
			$_nombre = explode('.', $_FILES['file']['name']);
			$_nombre = $_nombre[0];
			$_formato = $_info['extension'];
			//$size = getimagesize($_FILES['file']['tmp_name']);
	    	//echo "<pre>";print_r($_FILES);exit;
			$_video =$this->_trabajosGestion->subirVideo($_formato, $this->_sess->get('carga_actual'), 'file', $_nombre, $this->_conf['ruta_videos']);
			if($_video) $this->_sess->set('video_id', $_video);
    	
    	}
    }
    public function editarvideonativo()
    {
    	$this->_acl->acceso('encargado_access');
    	//$_id = (int) $_id;
    	if (!empty($_FILES)) { 

			$filename = strtolower($_FILES['file']['name']);
			// $whitelist = array('jpg', 'png', 'gif', 'jpeg');
			$whitelist = array('mp4','avi','mpeg');  
			$backlist = array('php', 'php3', 'php4', 'phtml','exe','bat','js');
			$tmp = explode('.', $filename);
			$file_extension = end($tmp); 
			if(!in_array($file_extension, $whitelist)){
				$this->_sess->set('video_error', 'archivo invalido');
			    echo 'archivo invalido';
			    exit();
			}
			if(in_array($file_extension, $backlist)){
				$this->_sess->set('video_error', 'archivo invalido');
			    echo 'archivo invalido';
			    exit();
			}

			if($this->_sess->get('video_id')) $this->_sess->destroy('video_id');
			$_info = pathinfo($_FILES['file']['name']);
			$_nombre_archivo =  basename($_FILES['file']['name'], '.' . $_info['extension']);
			$_nombre_interno = admin::cadenaAleatoriaSegura(15);
			$_nombre = explode('.', $_FILES['file']['name']);
			$_nombre = $_nombre[0];
			$_formato = $_info['extension'];
			//$size = getimagesize($_FILES['file']['tmp_name']);
	    	//echo "<pre>";print_r($_FILES);exit;    	
			$_video =$this->_trabajosGestion->subirVideo($_formato, $this->_sess->get('edicion_actual'), 'file', $_nombre, $this->_conf['ruta_videos']);
			if($_video) $this->_sess->set('video_id', $_video);
    	}
    }
	
	
	public function traerVideoFinal($_directorio='')
	{	
		$this->_acl->acceso('encargado_access');

		if($this->_sess->get('video_error')){
			echo 'archivo invalido';
			$this->_sess->destroy('video_error');
			exit();
		}

		$_data = $this->_trabajosGestion->traerDataVideo($this->_sess->get('video_id'));
		$_vid = $this->_conf['ruta_videos'] . $_data->path;
		if(!file_exists($_vid)) exit("Ha ocurrido un error. El video no existe o se ha corrompido.");
		
		
	    echo '<div id="' . $this->_sess->get('video_id') . '">
		        <video width="100%" controls>
					<source src="'.$this->_conf['base_url'] . 'public/videos/'. $_data->path.'" type="video/mp4">
				</video>
		    </div>';		
		
		$this->_sess->destroy('video_id');
		
		exit();
	}
	
	
	
	
		
		
		
		
	
	
	
	
	
	
}