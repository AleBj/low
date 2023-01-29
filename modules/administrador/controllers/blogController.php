<?php

use controllers\administradorController\administradorController;

class blogController extends administradorController
{
	public $_trabajosGestion;
	public $_xss;
	public $paginador;
	
    public function __construct() 
    {
		parent::__construct();

		$this->getLibrary('class.validador');

		$this->getLibrary('class.admin');		
		$this->_trabajosGestion = new admin();

		$this->getLibrary('AntiXSS');
		$this->_xss = new AntiXSS();
		
		$this->getLibrary('class.upload');

		$this->getLibrary('class.paginador');		
		
		$this->_error = 'has-error';
		$this->_filtro = '';

		
		
    }
    
    public function index()
    {	
		
		$this->redireccionar('administrador/blog/listar');	
    }
	
	public function listar($pagina = false)
    {
		$this->_acl->acceso('encargado_access');
		
		$pagina = (!validador::filtrarInt($pagina)) ? false : (int) $pagina;
		$paginador = new Paginador();

		// $this->_sess->destroy('carga_actual');
		// $this->_sess->destroy('edicion_actual');

		$this->_view->setCss(array('sweetalert'));
        $this->_view->setJs(array('sweetalert.min'));
		
		 $datos = $this->_trabajosGestion->traerBlogs();		 
		
		$this->_view->datos = $paginador->paginar($datos, $pagina, 10);
		$this->_view->paginacion = $paginador->getView('paginador-bootstrap', 'administrador/blog/listar');
		
		 // echo "<pre>";print_r($datos);echo "</pre>";exit;
			
		$this->_view->titulo = 'Administrador - Seguimiento';
        $this->_view->renderizar('index', 'blog');	
    }
	
	

	
	public function editar($_id)
	{
		//$this->_acl->acceso('admin_access');
		$this->_acl->acceso('encargado_access');
		
		validador::validarParametroInt($_id,$this->_conf['base_url']);		
			
		
		$this->_view->setCssPlugin(array('dropzone.min'));
		$this->_view->setJs(array('dropzone'));

		$this->_view->trabajo = $this->_trabajosGestion->traerBlog($_id);
		$this->_view->seo = $this->_trabajosGestion->traerSeo($_id, 'blog', 'interna');
		// $this->_view->grupos = $this->_trabajosGestion->traerGrupos();

		/*if($this->_view->trabajo['identificador']!=''){
			$this->_sess->set('edicion_actual', rand((int)1135687452,(int)999999999));
		}else{
			$this->_sess->set('edicion_actual', $this->_view->trabajo['identificador']);
		}*/
				
		$this->_sess->set('edicion_actual', $this->_view->trabajo['identificador']);
		
		 // echo "<pre>";print_r($this->_view->billing);exit;		
		
		
		$this->_view->titulo = 'Administrador - Seguimiento';
        $this->_view->renderizar('editar', 'blog');	
    }
	
	
	public function consolidarEditar()
	{
		//$this->_acl->acceso('admin_access');
		$this->_acl->acceso('encargado_access');		
			
			
		if($_POST){
			
			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
			
				if($_POST['envio01'] == 1){
					
					$this->_view->datos = $_POST;


				
					 // echo "<pre>";print_r($this->_view->datos);exit;



					if(!validador::getPostParam('titulo')){
						echo ('You must complete the title field');
						exit;
					}

					if(!validador::getPostParam('texto')){
						echo ('You must complete the text field');
						exit;
					}

					
					

					$suc = contenidos_blo::find($_POST['_id']);
					$suc->titulo = admin::convertirCaracteres($this->_xss->xss_clean($_POST['titulo']));
					$suc->texto = admin::convertirCaracteres($this->_xss->xss_clean($_POST['texto']));						
					$suc->identificador = $this->_sess->get('edicion_actual');
					$suc->item = admin::crearItems($this->_xss->xss_clean($_POST['titulo']));
					$suc->fecha_hora = date('Y-m-d H:i:s');
					$suc->fecha = date('Y-m-d');
					

					if($suc->save()){
						$_img_id =(isset($_POST['_seo_img'])) ? $_POST['_seo_img'] : 0;
						$_seo = $this->_trabajosGestion->editarSeo($_POST['_id'],
	                                                                'blog',
	                                                                $_POST['_tipo'],
	                                                                $_POST['titulo_seo'],
	                                                                $_POST['desc_seo'],
	                                                            	$_img_id,
	                                                            	$this->_sess->get('edicion_actual'));
					}
					

					$this->_sess->destroy('edicion_actual');

                	
                    echo "Load Successful! Wait...
                    <script>
                        setTimeout(function(){  window.location.href = \"" . $this->_conf['base_url']. "administrador/blog\"; }, 1000);
                    </script>";               
					
								
				

					// $this->redireccionar('administrador/blog');
					
												
					
				}

			}else{
				$this->redireccionar('error/access/404');
			}
		}
	
    }
	
	
	
	public function cargar()
    {	
		$this->_acl->acceso('encargado_access');
	
		if(!$this->_sess->get('carga_actual')){
			$this->_sess->set('carga_actual', rand((int)1135687452,(int)999999999));
		}
		
		$this->_view->setCssPlugin(array('dropzone.min', 'icheck'));
		$this->_view->setJs(array('dropzone', 'icheck.min'));

		// $this->_view->grupos = $this->_trabajosGestion->traerGrupos();


		$this->_view->titulo = 'Administrador - Seguimiento';
        $this->_view->renderizar('cargar', 'blog');	
    }


    public function consolidarCargar()
    {	
		$this->_acl->acceso('encargado_access');	
	
		
		if($_POST){
			
			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
			
				if($_POST['envio01'] == 1){
					
					$this->_view->datos = $_POST;


				
					 // echo "<pre>";print_r($this->_view->datos);exit;

				
					if(!validador::getPostParam('titulo')){
						echo ('You must complete the title field');
						exit;
					}

					if(!validador::getPostParam('texto')){
						echo ('You must complete the text field');
						exit;
					}


					
				
								
					
					$suc = new contenidos_blo();
					$suc->titulo = admin::convertirCaracteres($this->_xss->xss_clean($_POST['titulo']));
					$suc->texto = admin::convertirCaracteres($this->_xss->xss_clean($_POST['texto']));						
					$suc->identificador = $this->_sess->get('carga_actual');
					$suc->item = admin::crearItems($this->_xss->xss_clean($_POST['titulo']));
					$suc->fecha_hora = date('Y-m-d H:i:s');
					$suc->fecha = date('Y-m-d');
					// $suc->save();


					if($suc->save()){
						$_img_id =(isset($_POST['_seo_img'])) ? $_POST['_seo_img'] : 0;
						$_seo = $this->_trabajosGestion->cargarSeo($suc->id,
	                                                                'blog',
	                                                                $_POST['_tipo'],
	                                                                $_POST['titulo_seo'],
	                                                                $_POST['desc_seo'],
	                                                            	$_img_id,
	                                                            	$this->_sess->get('carga_actual'));
					}


					$this->_sess->destroy('carga_actual');	

					echo "Load Successful! Wait...
                    <script>
                        setTimeout(function(){  window.location.href = \"" . $this->_conf['base_url']. "administrador/blog\"; }, 1000);
                    </script>";

					// $this->redireccionar('administrador/blog');
					
												
					
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
			
				$_val = $_POST['valor'];
				
				$this->_view->prod  = $this->_trabajosGestion->traerBuscadorBlogs(ucwords(strtolower($_val)));
				
				/*echo "<pre>";print_r($this->_view->users);echo"</pre>";*/
				
				$_html = '';
				if($this->_view->prod){
					
					foreach($this->_view->prod as $prod){ 

						// $_cliente = admin::traerClientePorUsers($prod['id_cliente']);
						
		        		$_html .='<div class="forum-item">
		        					<div class="row">
		        						<div class="col-md-8">
											<a href="" class="forum-item-title">'.admin::convertirCaracteres($prod['titulo']).'</a>										
										</div>
										
										<div class="col-md-4 forum-info">
							                <div class="tooltip-demo pull-right">						                	
						                          							
												<a class="btn btn-warning" href="'.$this->_conf['url_enlace'].'administrador/blog/editar/'. $prod['id'].'">
													Edit
												</a>&nbsp;&nbsp;

												<a href="javascript:void(0);" class="btn btn-danger _borrar_'. $prod['id'].'" title="Borrar">
						                            Delete
						                        </a>&nbsp;&nbsp;					                        
								                        
											</div>
						                </div>
						            </div>
						        </div>';

						$_html .= '<script>					        			
									        $(document).ready(function () {
									                $("._borrar_'.$prod['id'].'").click(function () {
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
									                            var url= _root_ + "administrador/blog/borrar";
									                            var dataString = "_id='.$prod['id'].'&_csrf='.$this->_sess->get('_csrf').'";
									                            $.ajax({
									                                    type: "POST",
									                                    url: url,
									                                    data: dataString,
									                                    success: function(data){
									                                      if(data=="ok"){
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
									                            swal("Cancelado", "El contenido esta guardado", "error");
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
	
	public function borrar()
	{
		$this->_acl->acceso('encargado_access');
		

		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){		
			
				$_id = (int) $_POST['_id'];
		

				$_borrar = $this->_trabajosGestion->borrarBlogs($_id, $this->_conf['ruta_img_cargadas'], 'blog');
				if ($_borrar==false) {
					echo "enuso";
				}else{
					echo "ok";
				}
				

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
		 		 

		$this->_view->seo = $this->_trabajosGestion->traerSeoSeccion('blog', 'seccion');

		if(isset($this->_view->seo['identificador']) && $this->_view->seo['identificador']!=''){
			$this->_sess->set('edicion_actual', $this->_view->seo['identificador']);
		}else{
			$this->_sess->set('edicion_actual', rand((int)1135687452,(int)999999999));
		}

		// echo "<pre>";print_r($this->_view->seo);exit;
        		
    	$this->_view->titulo = 'Administrador - Cargar SEO';
        $this->_view->renderizar('seo', 'blog');
    }
	

	public function consolidarSeo()
    {
    	$this->_acl->acceso('encargado_access');


        if($_POST){

            if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
				
				// echo "<pre>";print_r($_POST);exit;
				$_img_id =(isset($_POST['_seo_img'])) ? $_POST['_seo_img'] : 0;
				$_cargar = $this->_trabajosGestion->editarSeo($_POST['_id'],
                                                                'blog',
                                                                $_POST['_tipo'],
                                                                $_POST['titulo_seo'],
                                                                $_POST['desc_seo'],
	                                                            $_img_id,
	                                                        	$this->_sess->get('edicion_actual'));
				
				
				if($_cargar == true){	
                	
                    echo "Load Successful! Wait...
                    <script>
                        setTimeout(function(){  window.location.href = \"" . $this->_conf['base_url']. "administrador/blog/seo\"; }, 1000);
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