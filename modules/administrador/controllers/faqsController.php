<?php

use controllers\administradorController\administradorController;

class faqsController extends administradorController
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
		
		$this->redireccionar('administrador/faqs/listar');	
    }
	
	public function listar($pagina = false)
  {
		$this->_acl->acceso('encargado_access');
		
		//$this->_view->setJs(array('jquery.btechco.excelexport','jquery.base64','exportar_promo'));
		
		$pagina = (!validador::filtrarInt($pagina)) ? false : (int) $pagina;
		$paginador = new Paginador();

		$this->_sess->destroy('carga_actual');
		$this->_sess->destroy('edicion_actual');

		$this->_view->setCss(array('sweetalert'));
    $this->_view->setJs(array('sweetalert.min'));
		
		$this->_view->datos = $this->_trabajosGestion->traerFaqs();
		
		$this->_view->datos = $paginador->paginar($this->_view->datos, $pagina, 20);
		$this->_view->paginacion = $paginador->getView('paginador-bootstrap', 'administrador/faqs/listar');
		
		 // echo "<pre>";print_r($this->_view->datos);echo "</pre>";exit;
			
		$this->_view->titulo = 'Administrador - Seguimiento';
    	$this->_view->renderizar('index', 'faqs');	
  }
	
	
	
	
	
	
	public function editar($_id)
	{
		//$this->_acl->acceso('admin_access');
		$this->_acl->acceso('encargado_access');
		
		validador::validarParametroInt($_id,$this->_conf['base_url']);		
			
		
		// $this->_view->setCssPlugin(array('dropzone.min'));
		// $this->_view->setJs(array('dropzone'));

		$this->_view->trabajo = $this->_trabajosGestion->traerFaq($_id);		
		// $this->_sess->set('edicion_actual', $this->_view->trabajo['identificador']);


		 // echo "<pre>";print_r($this->_view->trabajo);exit;		
				
			
		if($_POST){
			
			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
			
				if($_POST['envio01'] == 1){
					
					$this->_view->data = $_POST;
					
				
					// echo "<pre>";print_r($this->_view->data);exit;

					

					if(!validador::getTexto('pregunta')){
						$this->_view->_error ='You must complete the question field';
						$this->_view->titulo = 'Backend';
						$this->_view->renderizar('editar', 'faqs');
						exit;
					} 

					if(!validador::getTexto('respuesta')){
						$this->_view->_error ='You must complete the answer field';
						$this->_view->titulo = 'Backend';
						$this->_view->renderizar('editar', 'faqs');
						exit;
					} 
					
				

					$cat = contenidos_faq::find($this->_view->trabajo['id']);
					if($cat){
						$cat->pregunta = $this->_xss->xss_clean($_POST['pregunta']);
						$cat->respuesta = $this->_xss->xss_clean($_POST['respuesta']);
						$cat->save();
								
					}
					
					

					// $this->_sess->destroy('edicion_actual');
					// $this->_sess->destroy('img_id');
					// $this->_sess->destroy('img_lote');
					$this->redireccionar('administrador/faqs');
					
												
					
				}

			}else{
				$this->redireccionar('error/access/404');
			}
		}
	
		$this->_view->titulo = 'Backend';
    	$this->_view->renderizar('editar', 'faqs');	
  }
	
	
	
	
	
	public function cargar($_categoria = null)
  	{	
		
		$this->_acl->acceso('encargado_access');
		
		
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){	
			
				if($_POST['envio01'] == 1){
					
					$this->_view->data = $_POST;					
				
					// echo "<pre>";print_r($_POST);exit;

					if(!validador::getTexto('pregunta')){
						$this->_view->_error ='You must complete the question field';
						$this->_view->titulo = 'Backend';
						$this->_view->renderizar('cargar', 'faqs');
						exit;
					} 

					if(!validador::getTexto('respuesta')){
						$this->_view->_error ='You must complete the answer field';
						$this->_view->titulo = 'Backend';
						$this->_view->renderizar('cargar', 'faqs');
						exit;
					} 
					



					$cat = new contenidos_faq();
					$cat->pregunta = $this->_xss->xss_clean($_POST['pregunta']);
					$cat->respuesta = $this->_xss->xss_clean($_POST['respuesta']);
					$cat->save();	
	
								
					
					// $this->_sess->destroy('carga_actual');
					// $this->_sess->destroy('img_id');
					// $this->_sess->destroy('img_lote');
					$this->redireccionar('administrador/faqs');
				}

			}else{
				$this->redireccionar('error/access/404');
			}	
		}
	
		$this->_view->titulo = 'Administrador - Seguimiento';
        $this->_view->renderizar('cargar', 'faqs');	
    }
	
	
	public function borrar()
	{
		$this->_acl->acceso('encargado_access');
		//$_id = (int) $_id;
		

		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){		
			
				 $_id = (int) $_POST['_id'];		

				$_borrar = $this->_trabajosGestion->borrarFaqs($_id);
				if ($_borrar==false) {
					echo "enuso";
				}else{
					echo 1;
				}
				

			}else{
				$this->redireccionar('error/access/404');
			}
		}
		

	}

	public function buscador()
	{
		$this->_acl->acceso('encargado_access');
		
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){	
			
				// $_val = $_POST['valor'];

				$_val = $_POST['valor'];
                // $_val = base64_encode($_val);
				
				$_datos  = $this->_trabajosGestion->traerBuscadorFaqs($_val);
				
				 // echo "<pre>";print_r($_datos);echo"</pre>";exit;
				
				if($_datos ){

					$_html = '';
					foreach($_datos as $datos){ 

													        							
						$_html .= '<div class="forum-item">
							            <div class="row">
							                <div class="col-md-10">
							                    
							                    <a href="" class="forum-item-title">
							                       '.admin::convertirCaracteres($datos['pregunta']).'
							                    </a>
							                </div>

							                <div class="col-md-2 forum-info">
							                    <div class="tooltip-demo pull-right">						                    

							                        <a class="btn btn-warning" href="'. $this->_conf['url_enlace'].'administrador/faqs/editar/'.$datos['id'].'">
							                           Edit
							                        </a>&nbsp;&nbsp;

							                        <a href="javascript:void(0);" class="btn btn-danger _borrar_'. $datos['id'].'" title="Borrar">
							                            Delete
							                        </a>&nbsp;&nbsp;

							                    </div>
							                </div>
							            </div>
							        </div>';

					        $_html .= '<script>					        			
								        $(document).ready(function () {
								                $("._borrar_'.$datos['id'].'").click(function () {
								                    swal({
								                        title: "Are you sure you want to delete this content?",
								                        text: "The data will be permanently deleted!",
								                        type: "warning",
								                        showCancelButton: true,
								                        confirmButtonColor: "#DD6B55",
								                        confirmButtonText: "Yes, delete it!",
								                        cancelButtonText: "Don\'t delete!",
								                        closeOnConfirm: false,
								                        closeOnCancel: false },
								                    function (isConfirm) {
								                        if (isConfirm) {
								                            var url= _root_ + "administrador/faqs/borrar";
								                            var dataString = "_id='.$datos['id'].'&_csrf='.$this->_sess->get('_csrf').'";
								                            $.ajax({
								                                    type: "POST",
								                                    url: url,
								                                    data: dataString,
								                                    success: function(data){
								                                      if(data==1){
								                                        swal("Deleted!", "The content was successfully deleted", "success");
								                                        setTimeout(function() {
								                                            location.reload();
								                                        }, 200);
								                                      }else{
								                                        swal("Cancelled", "It cannot be deleted because the content is in use", "error");
								                                      }
								                                        
								                                        
								                                    }
								                            });
								                        } else {
								                            swal("Cancelled", "The content is saved", "error");
								                        }
								                    });
								                });
								            });
								        </script>';
		        
					}
					
				
				}else{
					$_html = '<h3>No records</h3>';
				}

							
				
				echo $_html;

			}else{
				$this->redireccionar('error/access/404');
			}
			
		}
	}


	// SEO
	
	public function seo()
    {
    	 $this->_acl->acceso('encargado_access');

    	 $this->_view->setCssPlugin(array('dropzone.min'));
		$this->_view->setJs(array('dropzone'));
		 		 

		$this->_view->seo = $this->_trabajosGestion->traerSeoSeccion('faqs', 'seccion');

		if(isset($this->_view->seo['identificador']) && $this->_view->seo['identificador']!=''){
			$this->_sess->set('edicion_actual', $this->_view->seo['identificador']);
		}else{
			$this->_sess->set('edicion_actual', rand((int)1135687452,(int)999999999));
		}

		// echo "<pre>";print_r($this->_view->seo);exit;
        		
    	$this->_view->titulo = 'Administrador - Cargar SEO';
        $this->_view->renderizar('seo', 'faqs');
    }
	

	public function consolidarSeo()
    {
    	$this->_acl->acceso('encargado_access');


        if($_POST){

            if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
				
				// echo "<pre>";print_r($_POST);exit;
				$_img_id =(isset($_POST['_seo_img'])) ? $_POST['_seo_img'] : 0;
				$_cargar = $this->_trabajosGestion->editarSeo($_POST['_id'],
                                                                'faqs',
                                                                $_POST['_tipo'],
                                                                $_POST['titulo_seo'],
                                                                $_POST['desc_seo'],
	                                                            $_img_id,
	                                                        	$this->_sess->get('edicion_actual'));
				
				
				if($_cargar == true){	
                	
                    echo "Load Successful! Wait...
                    <script>
                        setTimeout(function(){  window.location.href = \"" . $this->_conf['base_url']. "administrador/faqs/seo\"; }, 1000);
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