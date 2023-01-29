<?php

use controllers\adminController\adminController;

class loginController extends adminController
{   
	
    public function __construct()
    {
        parent::__construct();
		$this->getLibrary('class.validador');	

		$this->getLibrary('class.usuarios');		
		$this->usuariosGestion = new usuarios();

		$this->getLibrary('class.admin');
		$this->_trabajosGestion = new admin();

		$this->getLibrary('AntiXSS');
		$this->_xss = new AntiXSS();
		
    }
    
    public function index()
    {
        if($this->_sess->get('autenticado')){
            $this->redireccionar('administrador');
        }
        
        $this->_view->titulo = 'Iniciar Sesion';
        // echo "<pre>";print_r($_SESSION);echo "</pre>";
		
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
			
			
				if(validador::getInt('enviar') == 1){
					
					
					$this->_view->datos = $_POST;
					
					if(!validador::getAlphaNum('usuario')){
						$this->_view->_error = 'You must enter your username';
						$this->_view->renderizar('index','login','login');
						exit;
					}
					
					//if(!validador::getSql('pass',$this->_conf['baseDatos'])){
					if(!validador::getAlphaNum('pass')){
						$this->_view->_error = 'You must enter your password';
						$this->_view->renderizar('index','login','login');
						exit;
					}
									
					
					
					// 'find' si se busca un solo registro, 'all' si se busca solo 1
					$row = usuario::find(array(
											'conditions' => array(
															'usuario = ? AND pass = ?', 
															$this->_xss->xss_clean(validador::getAlphaNum('usuario')), 
															Hash::getHash('sha512', validador::getPostParam('pass'), $this->_conf['hash_key'])
															)
												)
										);
					
					
					if(!$row){
						$this->_view->_error = 'Incorrect username and/or password';
						$this->_view->renderizar('index','login','login');
						exit;
					}
					
					if($row->estado != 1){
						$this->_view->_error = 'The user is not enabled';
						$this->_view->renderizar('index','login','login');
						exit;
					}
					
					/*if($row->role!=1){
						if($row->role==2){
							$this->_sess->set('_super_jumbo', 1);
							$this->_sess->set('_super_disco', 2);
							$_prov = usuarios::traerProvGestion($row->id)->provincias;					
							$_prov = explode(',', $_prov);
							$this->_sess->set('_prov_jumbo', $_prov);						
							$this->_sess->set('_prov_disco', $_prov);
						}else{
							$this->_sess->set('_super_jumbo', 1);
							$_prov = usuarios::traerProvGestion($row->id)->provincias;					
							$_prov = explode(',', $_prov);
							$this->_sess->set('_prov_jumbo', $_prov);					

						}
					}else{
						$_prov = '';
					}*/
					
					$this->_sess->set('autenticado', true);
					$this->_sess->set('sesion_en_curso', true);
					$this->_sess->set('level', $row->role);
					$this->_sess->set('usuario', $row->usuario);
					$this->_sess->set('id_usuario', $row->id);
					$this->_sess->set('tiempo', time());
					// $this->_sess->set('_provincias', $_prov);




					//dar de baja contenidos de mas de 60 dias
					/*$_bajas = $this->borrarContBaja();
					if($_bajas){
						$this->redireccionar('administrador');
					}*/

					
					$this->redireccionar('administrador');
				}

			}else{
				//$this->redireccionar('error/access/404');
				$this->_view->_error = 'There was an error, please try again later.';
				// $this->_view->renderizar('index','login');
				// exit;
			}
		}

        $this->_view->renderizar('index','login','login');
    }



  /* public function borrarContBaja()
   {   	

    	//setear asig de alta y bajas segun fecha de hoy
    	$this->setAltaBaja();
    	
    	//traer asig de baja
    	$_bajas = $this->_trabajosGestion->traerAsignacionesBaja();
    	
    	//traer las asig de baja con mas de 60 dias
    	$fecha_hoy = date('Y-m-d');
    	foreach ($_bajas as $val) {
    		$_hasta = $val->vigencia_hasta->format('Y-m-d');
    		$nuevafecha = strtotime('+60 day' , strtotime($_hasta ));
			$nuevafecha = date('Y-m-d' , $nuevafecha);
			// echo $nuevafecha."<br>";

			if($nuevafecha < $fecha_hoy){
				$_data_id[]= $val->id;
				$_data[]= $val;
			}
    		
    	}
    	
    	
    	// echo $ids;
    	// echo "<pre>";print_r($_asig_id);//exit;
    	 // echo "<pre>";print_r($_data_id);exit;
    	// echo "<pre>";print_r($_res);exit;

  		//borrar contenidos no utilizados en otras asignaciones
  		if(isset($_data)){

	  		foreach ($_data as $val) {
	  			//borrar cada contenido
	  			if ($val->ids_catalogos!='') {
	  				$_borrar_cat = $this->_trabajosGestion->borrarVariosCatalogo($val->ids_catalogos, $this->_conf['ruta_img_cargadas'], 'catalogos', $_data_id);
	  			}
	  		 	if ($val->ids_videos!='') {
	  		 		$_borrar_vid = $this->_trabajosGestion->borrarVariosVideos($val->ids_videos, $this->_conf['ruta_videos'], $_data_id);
	  		 	}
	  		 	if ($val->ids_footers!='') {
	  		 		$_borrar_foot = $this->_trabajosGestion->borrarVariosFooters($val->ids_footers, $this->_conf['ruta_img_cargadas'], 'promociones', $_data_id);
	  		 	}
	  		 	if ($val->ids_fondos!='') {
	  		 		$_borrar_fon = $this->_trabajosGestion->borrarVariosFondos($val->ids_fondos, $this->_conf['ruta_img_cargadas'], 'fondos', $_data_id);
	  		 	}
	  		 	if ($val->ids_inactivas!='') {
	  		 		$_borrar_inac = $this->_trabajosGestion->borrarVariosInactivas($val->ids_inactivas, $this->_conf['ruta_videos'], $_data_id);
	  		 	} 		 	
	  		 	
	  		 	//borrar asignacion
	  		 	$borrar = contenidos_asignacione::find($val->id);
	  		 	$borrar->delete();	
	  		 } 
  		}


		return true;

		//echo "<pre>";print_r($_data_id);exit;    	

    }

   

    private function setAltaBaja($_id=false)
	{	
		$_fechahoy = date('Y-m-d H:i:s');
		$asignaciones = $this->_trabajosGestion->traerAsignacionesPdo($_id);		
		if($asignaciones){
			foreach($asignaciones as $asig){
				if($_fechahoy >= date('Y-m-d H:i:s', strtotime($asig['vigencia_desde'])) && $_fechahoy <= date('Y-m-d H:i:s', strtotime($asig['vigencia_hasta']))){
					if($asig['estado']!='alta'){
						$this->_trabajosGestion->updateEstadoAsigPdo($asig['id'],'alta');
					}
					
				}else{
					if($asig['estado']!='baja'){
						$this->_trabajosGestion->updateEstadoAsigPdo($asig['id'],'baja');
					}
					
				}
			}			
		}
    }
    */
	
	public function userData($carpeta)
	{
		foreach(glob($carpeta . "/*") as $archivos_carpeta){
			if (is_dir($archivos_carpeta)){
				userData($archivos_carpeta);
			}else{
				unlink($archivos_carpeta);
			}
		} 
		//rmdir($carpeta);
	}
	
	
    public function cerrar()
    {
        $this->_sess->destroy();
        $this->redireccionar();
    }
}