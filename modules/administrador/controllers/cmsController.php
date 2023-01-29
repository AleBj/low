<?php

use controllers\administradorController\administradorController;

class cmsController extends administradorController
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
		
		$this->redireccionar('administrador/cms/disclaimer');	
    }


    public function disclaimer()
	{
		//$this->_acl->acceso('admin_access');
		$this->_acl->acceso('encargado_access');
		
		$this->_view->setCssPlugin(array('dropzone.min'));
		$this->_view->setJs(array('dropzone'));	
			
		
		// $this->_view->setCssPlugin(array('dropzone.min'));
		// $this->_view->setJs(array('dropzone'));

		$this->_view->trabajo = $this->_trabajosGestion->traerDisclaimer();		
		$this->_view->seo = $this->_trabajosGestion->traerSeoSeccion('disclaimer', 'seccion');
		// $this->_sess->set('edicion_actual', $this->_view->trabajo['identificador']);

		if(isset($this->_view->seo['identificador']) && $this->_view->seo['identificador']!=''){
			$this->_sess->set('edicion_actual', $this->_view->seo['identificador']);
		}else{
			$this->_sess->set('edicion_actual', rand((int)1135687452,(int)999999999));
		}

		 // echo "<pre>";print_r($this->_view->trabajo);exit;		
				
			
		if($_POST){
			
			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
			
				if($_POST['envio01'] == 1){
					
					$this->_view->data = $_POST;
					
				
					// echo "<pre>";print_r($this->_view->data);exit;

					

					if(!validador::getTexto('texto')){
						$this->_view->_error ='You must complete the text field';
						$this->_view->titulo = 'Administrador - Seguimiento';
						$this->_view->renderizar('disclaimer', 'cms');
						exit;
					} 

					
					
				

					$cat = contenidos_disclaime::find(1);
					if($cat){
						$cat->texto = $this->_xss->xss_clean($_POST['texto']);
						// $cat->save();	


						if($cat->save()){
							$_img_id =(isset($_POST['_seo_img'])) ? $_POST['_seo_img'] : 0;
							$_seo = $this->_trabajosGestion->editarSeo($_POST['_id'],
		                                                                'disclaimer',
		                                                                $_POST['_tipo'],
		                                                                $_POST['titulo_seo'],
		                                                                $_POST['desc_seo'],
		                                                            	$_img_id,
		                                                            	$this->_sess->get('edicion_actual'));

						}							
					}
					
					

					$this->_sess->destroy('edicion_actual');
					// $this->_sess->destroy('img_id');
					// $this->_sess->destroy('img_lote');
					$this->redireccionar('administrador/cms/disclaimer');
					
												
					
				}

			}else{
				$this->redireccionar('error/access/404');
			}
		}
		
		$this->_view->titulo = 'Administrador - Seguimiento';
	    $this->_view->renderizar('disclaimer', 'cms');	
	}


	public function howitworks()
	{
		//$this->_acl->acceso('admin_access');
		$this->_acl->acceso('encargado_access');

		$this->_view->setCssPlugin(array('dropzone.min'));
		$this->_view->setJs(array('dropzone'));	
		
		$this->_view->trabajo = $this->_trabajosGestion->traerHowitworks();	
		$this->_view->seo = $this->_trabajosGestion->traerSeoSeccion('howitworks', 'seccion');
		// $this->_sess->set('edicion_actual', $this->_view->trabajo['identificador']);

		if(isset($this->_view->seo['identificador']) && $this->_view->seo['identificador']!=''){
			$this->_sess->set('edicion_actual', $this->_view->seo['identificador']);
		}else{
			$this->_sess->set('edicion_actual', rand((int)1135687452,(int)999999999));
		}

			
		// $this->_sess->set('edicion_actual', $this->_view->trabajo['identificador']);


		 // echo "<pre>";print_r($this->_view->trabajo);exit;		
				
			
		if($_POST){
			
			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
			
				if($_POST['envio01'] == 1){
					
					$this->_view->data = $_POST;
					
				
					// echo "<pre>";print_r($this->_view->data);exit;

					

					if(!validador::getTexto('texto')){
						$this->_view->_error ='Debe completar el campo texto';
						$this->_view->titulo = 'Administrador - Seguimiento';
						$this->_view->renderizar('howitworks', 'cms');
						exit;
					} 

					
					
				

					$cat = contenidos_howitwork::find(1);
					if($cat){
						$cat->texto = $this->_xss->xss_clean($_POST['texto']);
						// $cat->save();	


						if($cat->save()){
							$_img_id =(isset($_POST['_seo_img'])) ? $_POST['_seo_img'] : 0;
							$_seo = $this->_trabajosGestion->editarSeo($_POST['_id'],
		                                                                'howitworks',
		                                                                $_POST['_tipo'],
		                                                                $_POST['titulo_seo'],
		                                                                $_POST['desc_seo'],
		                                                            	$_img_id,
		                                                            	$this->_sess->get('edicion_actual'));

						}								
					}
					
					

					$this->_sess->destroy('edicion_actual');
					// $this->_sess->destroy('img_id');
					// $this->_sess->destroy('img_lote');
					$this->redireccionar('administrador/cms/howitworks');
					
												
					
				}

			}else{
				$this->redireccionar('error/access/404');
			}
		}
		
		$this->_view->titulo = 'Administrador - Seguimiento';
	    $this->_view->renderizar('howitworks', 'cms');	
	}


	public function whyus()
	{
		//$this->_acl->acceso('admin_access');
		$this->_acl->acceso('encargado_access');
		
		$this->_view->setCssPlugin(array('dropzone.min'));
		$this->_view->setJs(array('dropzone'));	

		$this->_view->trabajo = $this->_trabajosGestion->traerWhyus();		
		$this->_view->seo = $this->_trabajosGestion->traerSeoSeccion('whyus', 'seccion');
		// $this->_sess->set('edicion_actual', $this->_view->trabajo['identificador']);

		if(isset($this->_view->seo['identificador']) && $this->_view->seo['identificador']!=''){
			$this->_sess->set('edicion_actual', $this->_view->seo['identificador']);
		}else{
			$this->_sess->set('edicion_actual', rand((int)1135687452,(int)999999999));
		}


		 // echo "<pre>";print_r($this->_view->trabajo);exit;		
				
			
		if($_POST){
			
			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
			
				if($_POST['envio01'] == 1){
					
					$this->_view->data = $_POST;
					
				
					// echo "<pre>";print_r($this->_view->data);exit;

					

					if(!validador::getTexto('texto')){
						$this->_view->_error ='Debe completar el campo texto';
						$this->_view->titulo = 'Administrador - Seguimiento';
						$this->_view->renderizar('whyus', 'cms');
						exit;
					} 

					
					
				

					$cat = contenidos_whyu::find(1);
					if($cat){
						$cat->texto = $this->_xss->xss_clean($_POST['texto']);
						// $cat->save();	


						if($cat->save()){

							$_img_id =(isset($_POST['_seo_img'])) ? $_POST['_seo_img'] : 0;
							$_seo = $this->_trabajosGestion->editarSeo($_POST['_id'],
		                                                                'whyus',
		                                                                $_POST['_tipo'],
		                                                                $_POST['titulo_seo'],
		                                                                $_POST['desc_seo'],
		                                                            	$_img_id,
		                                                            	$this->_sess->get('edicion_actual'));

						}								
					}
					
					

					$this->_sess->destroy('edicion_actual');
					// $this->_sess->destroy('img_id');
					// $this->_sess->destroy('img_lote');
					$this->redireccionar('administrador/cms/whyus');
					
												
					
				}

			}else{
				$this->redireccionar('error/access/404');
			}
		}
		
		$this->_view->titulo = 'Administrador - Seguimiento';
	    $this->_view->renderizar('whyus', 'cms');	
	}

	
	public function termsandconditions()
	{
		//$this->_acl->acceso('admin_access');
		$this->_acl->acceso('encargado_access');

		$this->_view->setCssPlugin(array('dropzone.min'));
		$this->_view->setJs(array('dropzone'));	
		
		$this->_view->trabajo = $this->_trabajosGestion->traerTermsandconditions();	
		$this->_view->seo = $this->_trabajosGestion->traerSeoSeccion('termsandconditions', 'seccion');
		// $this->_sess->set('edicion_actual', $this->_view->trabajo['identificador']);

		if(isset($this->_view->seo['identificador']) && $this->_view->seo['identificador']!=''){
			$this->_sess->set('edicion_actual', $this->_view->seo['identificador']);
		}else{
			$this->_sess->set('edicion_actual', rand((int)1135687452,(int)999999999));
		}

			
		// $this->_sess->set('edicion_actual', $this->_view->trabajo['identificador']);


		 // echo "<pre>";print_r($this->_view->trabajo);exit;		
				
			
		if($_POST){
			
			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
			
				if($_POST['envio01'] == 1){
					
					$this->_view->data = $_POST;
					
				
					// echo "<pre>";print_r($this->_view->data);exit;

					

					if(!validador::getTexto('texto')){
						$this->_view->_error ='You must complete the text field';
						$this->_view->titulo = 'Administrador - Seguimiento';
						$this->_view->renderizar('termsandconditions', 'cms');
						exit;
					} 

					
					
				

					$cat = contenidos_termsandcondition::find(1);
					if($cat){
						$cat->texto = $this->_xss->xss_clean($_POST['texto']);
						// $cat->save();	


						if($cat->save()){
							$_img_id =(isset($_POST['_seo_img'])) ? $_POST['_seo_img'] : 0;
							$_seo = $this->_trabajosGestion->editarSeo($_POST['_id'],
		                                                                'termsandconditions',
		                                                                $_POST['_tipo'],
		                                                                $_POST['titulo_seo'],
		                                                                $_POST['desc_seo'],
		                                                            	$_img_id,
		                                                            	$this->_sess->get('edicion_actual'));

						}								
					}
					
					

					$this->_sess->destroy('edicion_actual');
					// $this->_sess->destroy('img_id');
					// $this->_sess->destroy('img_lote');
					$this->redireccionar('administrador/cms/termsandconditions');
					
												
					
				}

			}else{
				$this->redireccionar('error/access/404');
			}
		}
		
		$this->_view->titulo = 'Administrador - Seguimiento';
	    $this->_view->renderizar('termsandconditions', 'cms');	
	}
	
	
}