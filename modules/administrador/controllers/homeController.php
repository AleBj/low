<?php

use controllers\administradorController\administradorController;

class homeController extends administradorController
{
	public $_trabajosGestion;
	public $_xss;
	
    public function __construct() 
    {
		parent::__construct();
		$this->getLibrary('class.validador');
		$this->getLibrary('class.admin');		
		$this->_trabajosGestion = new admin();
		$this->getLibrary('AntiXSS');
		$this->_xss = new AntiXSS();
		
		$this->getLibrary('class.upload');
		
		$this->_error = 'has-error';
		$this->_filtro = '';

		// echo "<pre>";print_r($_SESSION);echo "</pre>";
		
    }
    
    public function index()
    {	
		
		$this->redireccionar('administrador/home/seo');	
    }
	

	// SEO
	
	public function seo()
    {
    	 $this->_acl->acceso('encargado_access');

    	 $this->_view->setCssPlugin(array('dropzone.min'));
		$this->_view->setJs(array('dropzone'));
		 		 

		$this->_view->seo = $this->_trabajosGestion->traerSeoSeccion('home', 'seccion');

		if(isset($this->_view->seo['identificador']) && $this->_view->seo['identificador']!=''){
			$this->_sess->set('edicion_actual', $this->_view->seo['identificador']);
		}else{
			$this->_sess->set('edicion_actual', rand((int)1135687452,(int)999999999));
		}

		// echo "<pre>";print_r($this->_view->seo);exit;
        		
    	$this->_view->titulo = 'Administrador - Cargar SEO';
        $this->_view->renderizar('seo', 'home');
    }
	

	public function consolidarSeo()
    {
    	$this->_acl->acceso('encargado_access');


        if($_POST){

            if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
				
				// echo "<pre>";print_r($_POST);exit;
				$_img_id =(isset($_POST['_seo_img'])) ? $_POST['_seo_img'] : 0;
				$_cargar = $this->_trabajosGestion->editarSeo($_POST['_id'],
                                                                'home',
                                                                $_POST['_tipo'],
                                                                $_POST['titulo_seo'],
                                                                $_POST['desc_seo'],
	                                                            $_img_id,
	                                                        	$this->_sess->get('edicion_actual'));
				
				
				if($_cargar == true){	
                	
                    echo "Load Successful! Wait...
                    <script>
                        setTimeout(function(){  window.location.href = \"" . $this->_conf['base_url']. "administrador/home/seo\"; }, 1000);
                    </script>"; 
                 
					
								
				}else{
					echo "Error";				
				}
				
				$this->_sess->destroy('edicion_actual');
				exit();
				
            } 
        }
		
    }
	
	
	
	
}