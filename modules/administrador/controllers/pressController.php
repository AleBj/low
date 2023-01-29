<?php

use controllers\administradorController\administradorController;

class pressController extends administradorController
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
		
		$this->redireccionar('administrador/press/listar');	
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
		
		 $datos = $this->_trabajosGestion->traerPresses();		 
		
		$this->_view->datos = $paginador->paginar($datos, $pagina, 10);
		$this->_view->paginacion = $paginador->getView('paginador-bootstrap', 'administrador/press/listar');
		
		 // echo "<pre>";print_r($datos);echo "</pre>";exit;
			
		$this->_view->titulo = 'Administrador - Seguimiento';
        $this->_view->renderizar('index', 'press');	
    }
	
	

	
	public function editar($_id)
	{
		//$this->_acl->acceso('admin_access');
		$this->_acl->acceso('encargado_access');
		
		validador::validarParametroInt($_id,$this->_conf['base_url']);		
			
		
		$this->_view->setCssPlugin(array('dropzone.min'));
		$this->_view->setJs(array('dropzone'));

		$this->_view->trabajo = $this->_trabajosGestion->traerPress($_id);
		// $this->_view->grupos = $this->_trabajosGestion->traerGrupos();

		/*if($this->_view->trabajo['identificador']!=''){
			$this->_sess->set('edicion_actual', rand((int)1135687452,(int)999999999));
		}else{
			$this->_sess->set('edicion_actual', $this->_view->trabajo['identificador']);
		}*/
				
		$this->_sess->set('edicion_actual', $this->_view->trabajo['identificador']);
		
		 // echo "<pre>";print_r($this->_view->trabajo);exit;		
		
				
			
		if($_POST){
			
			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
			
				if($_POST['envio01'] == 1){
					
					$this->_view->datos = $_POST;


				
					 // echo "<pre>";print_r($this->_view->datos);exit;



					if(!validador::getPostParam('titulo')){
						$this->_view->_error ='Debe introducir un titulo';
						$this->_view->titulo = 'Administrador - Seguimiento';
						$this->_view->renderizar('editar', 'press');
						exit;
					}

					if(!validador::getPostParam('medio')){
						$this->_view->_error ='Debe introducir un medio';
						$this->_view->titulo = 'Administrador - Seguimiento';
						$this->_view->renderizar('editar', 'press');
						exit;
					}

					
					/*if(!validador::getPostParam('fecha_publicacion')){
						$this->_view->_error ='Debe introducir una fecha de publicacion';
						$this->_view->titulo = 'Administrador - Seguimiento';
						$this->_view->renderizar('editar', 'press');
						exit;
					}*/

					if($_POST['radio_activo']!='' && $_POST['radio_activo']=='_link'){
						$_link = $this->_xss->xss_clean($_POST['link']);
						$this->_trabajosGestion->borrarArchivoPorIdentificador($this->_sess->get('edicion_actual'), $this->_conf['ruta_archivos']);

					}else if($_POST['radio_activo']!='' && $_POST['radio_activo']=='_archivo'){
						$_link = '';
					}
								
					
					$suc = contenidos_pres::find($this->_view->trabajo['id']);
					$suc->titulo = admin::convertirCaracteres($this->_xss->xss_clean($_POST['titulo']));
					$suc->medio = admin::convertirCaracteres($this->_xss->xss_clean($_POST['medio']));			
					$suc->fecha_publicacion = $this->_xss->xss_clean($_POST['fecha_publicacion']);
					$suc->link = $_link;	
					// $suc->identificador = $this->_sess->get('edicion_actual');
					// $suc->fecha_hora = date('Y-m-d H:i:s');
					// $suc->fecha = date('Y-m-d');
					$suc->save();		

					
					

					$this->_sess->destroy('edicion_actual');					
					$this->redireccionar('administrador/press');
					
												
					
				}

			}else{
				$this->redireccionar('error/access/404');
			}
		}
	
		$this->_view->titulo = 'Administrador - Seguimiento';
        $this->_view->renderizar('editar', 'press');	
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

		
		
		if($_POST){
			
			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
			
				if($_POST['envio01'] == 1){
					
					$this->_view->datos = $_POST;


				
					 // echo "<pre>";print_r($this->_view->datos);exit;

				
					if(!validador::getPostParam('titulo')){
						$this->_view->_error ='Debe introducir un titulo';
						$this->_view->titulo = 'Administrador - Seguimiento';
						$this->_view->renderizar('cargar', 'press');
						exit;
					}

					if(!validador::getPostParam('medio')){
						$this->_view->_error ='Debe introducir un medio';
						$this->_view->titulo = 'Administrador - Seguimiento';
						$this->_view->renderizar('cargar', 'press');
						exit;
					}

					
					/*if(!validador::getPostParam('fecha_publicacion')){
						$this->_view->_error ='Debe introducir una fecha de publicacion';
						$this->_view->titulo = 'Administrador - Seguimiento';
						$this->_view->renderizar('cargar', 'press');
						exit;
					}*/
								
					
					$suc = new contenidos_pres();
					$suc->titulo = admin::convertirCaracteres($this->_xss->xss_clean($_POST['titulo']));
					$suc->medio = admin::convertirCaracteres($this->_xss->xss_clean($_POST['medio']));			
					$suc->fecha_publicacion = $this->_xss->xss_clean($_POST['fecha_publicacion']);
					$suc->link = $this->_xss->xss_clean($_POST['link']);	
					$suc->identificador = $this->_sess->get('carga_actual');
					$suc->fecha_hora = date('Y-m-d H:i:s');
					$suc->fecha = date('Y-m-d');
					$suc->save();


					$this->_sess->destroy('carga_actual');					
					$this->redireccionar('administrador/press');
					
												
					
				}

			}else{
				$this->redireccionar('error/access/404');
			}
		}


		$this->_view->titulo = 'Administrador - Seguimiento';
        $this->_view->renderizar('cargar', 'press');	
    }

    public function buscador()
	{
		$this->_acl->acceso('encargado_access');
		
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){	
			
				$_val = $_POST['valor'];
				
				$this->_view->prod  = $this->_trabajosGestion->traerBuscadorPress(ucwords(strtolower($_val)));
				
				/*echo "<pre>";print_r($this->_view->users);echo"</pre>";*/
				
				$_html = '';
				foreach($this->_view->prod as $prod){ 

					// $_cliente = admin::traerClientePorUsers($prod['id_cliente']);
					
	        		$_html .='<div class="forum-item">
	        					<div class="row">
	        						<div class="col-md-8">
										<a href="" class="forum-item-title">'.admin::convertirCaracteres($prod['titulo']).'</a>										
									</div>
									
									<div class="col-md-4 forum-info">
						                <div class="tooltip-demo pull-right">						                	
					                          							
											<a class="btn btn-warning" href="'.$this->_conf['url_enlace'].'administrador/press/editar/'. $prod['id'].'">
												Editar
											</a>&nbsp;&nbsp;

											<a href="javascript:void(0);" class="btn btn-danger _borrar_'. $prod['id'].'" title="Borrar">
					                            Eliminar
					                        </a>&nbsp;&nbsp;					                        
							                        
										</div>
					                </div>
					            </div>
					        </div>';

					$_html .= '<script>					        			
								        $(document).ready(function () {
								                $("._borrar_'.$prod['id'].'").click(function () {
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
								                            var url= _root_ + "administrador/press/borrar";
								                            var dataString = "_id='.$prod['id'].'&_csrf='.$this->_sess->get('_csrf').'";
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
		

				$_borrar = $this->_trabajosGestion->borrarPress($_id, $this->_conf['ruta_img_cargadas'], 'press');
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


   
	/*
	public function exportaDatos()
    {

    	$this->_acl->acceso('encargado_access');

		$_datos = $this->_trabajosGestion->traerUsersExport();

		// echo "<pre>";print_r($_datos);echo "</pre>";exit;

		$this->_view->filename = 'usuarios_'.date('d-m-Y').'.xls';	

		$this->_view->html ='<table border="1" cellpadding="10" cellspacing="0" bordercolor="#666666" style="border-collapse:collapse;">
		<tbody>
		<tr style="font-weight: bold;">		
		<td>Nombre</td>
		<td>Apellido</td>			
		<td>Email</td>				
		<td>Fecha de alta</td>	
		</tr>';

		foreach ($_datos as $data) {
			$this->_view->html .='<tr>';
			$this->_view->html .='<td valign="top">'.admin::convertirCaracteres($data['nombre']).'</td>';
			$this->_view->html .='<td valign="top">'.admin::convertirCaracteres($data['apellido']).'</td>';
			$this->_view->html .='<td valign="top">'.$data['email'].'</td>';
			$this->_view->html .='<td valign="top">'.$data['fecha'].'</td>';
			
			// $this->_view->html .='<td valign="top">'.$data['fecha_nacimiento'].'</td>';
			// $this->_view->html .='<td valign="top">'.$data['email'].'</td>';
			// $this->_view->html .='<td valign="top">'.$data['password'].'</td>';
	    	$this->_view->html .='</tr>';
		}
	
		
		$this->_view->html .='</tbody>
		</table>';


		$this->_view->titulo = 'Administrador - Seguimiento';
		$this->_view->renderizar('exportar', 'users','vacio');



    }

    

	public function exportaDatosUser($_id)
    {

    	$this->_acl->acceso('encargado_access');

		$data = $this->_trabajosGestion->traerUsersExportUser($_id);

		// $_direcciones = $this->_trabajosGestion->traerDireccionesEnviosUsers($data['id']);
		
		// $_facturacion = $this->_trabajosGestion->traerDatosFacturacionUsers($data['id']);
		
		// echo "<pre>";print_r($data);echo "</pre>";exit;

		$_nomb = admin::crearTitulo2(admin::convertirCaracteres($data['nombre'])).'_'.admin::crearTitulo2(admin::convertirCaracteres($data['apellido']));

		$this->_view->filename = $_nomb.'_'.date('d-m-Y').'.xls';	

		$this->_view->html ='<table border="1" cellpadding="10" cellspacing="0" bordercolor="#666666" style="border-collapse:collapse;">
		<tbody>
		<tr style="font-weight: bold;font-size:20px;background-color:#ddd;">
		<td colspan="4" valign="top" align="center">Datos Personales</td>
		</tr>
		<tr style="font-weight: bold;">		
		<td>Nombre</td>
		<td>Apellido</td>			
		<td>Email</td>
		<td>Fecha de alta</td>	
		</tr>';

		// foreach ($_datos as $data) {


			
			$this->_view->html .='<tr>';
			$this->_view->html .='<td valign="top">'.admin::convertirCaracteres($data['nombre']).'</td>';
			$this->_view->html .='<td valign="top">'.admin::convertirCaracteres($data['apellido']).'</td>';
			$this->_view->html .='<td valign="top">'.$data['email'].'</td>';
			$this->_view->html .='<td valign="top">'.$data['fecha'].'</td>';			
	    	$this->_view->html .='</tr>';
	    	$this->_view->html .='</tbody>';
			$this->_view->html .='</table>';

	   


	    	$_direcciones = $this->_trabajosGestion->traerDireccionesEnviosUsers($data['id']);
	    	if($_direcciones){

	    		$this->_view->html .='<table border="1" cellpadding="10" cellspacing="0" bordercolor="#666666" style="border-collapse:collapse;">
				<tbody>
				<tr style="font-weight: bold;font-size:20px;background-color:#ddd;">
				<td colspan="7" valign="top" align="center">Dirección de Envío</td>
				</tr>

				<tr style="font-weight: bold;">		
				<td>Titulo</td>
				<td>Dirección 1</td>
				<td>Dirección 2</td>
				<td>Código Postal</td>
				<td>Ciudad</td>
				<td>Estado</td>
				<td>País</td>	
				</tr>';

	    		
	    		foreach ($_direcciones as $dir) {		    			

					$this->_view->html .='<tr>';
					$this->_view->html .='<td valign="top">'.admin::convertirCaracteres($dir['address_name']).'</td>';
					$this->_view->html .='<td valign="top">'.admin::convertirCaracteres($dir['address_line_1']).'</td>';
					$this->_view->html .='<td valign="top">'.admin::convertirCaracteres($dir['address_line_2']).'</td>';
					$this->_view->html .='<td valign="top">'.$dir['zipcode'].'</td>';
					$this->_view->html .='<td valign="top">'.$dir['city'].'</td>';
					$this->_view->html .='<td valign="top">'.$dir['state'].'</td>';
					$this->_view->html .='<td valign="top">'.$dir['country'].'</td>';
			    	$this->_view->html .='</tr>';
			    	

	    		}

	    		$this->_view->html .='</tbody>';
				$this->_view->html .='</table>';

	    		
	    	}

	    	
		// }
	
		
		$this->_view->html .='</tbody>';
		$this->_view->html .='</table>';


		$this->_view->titulo = 'Administrador - Seguimiento';
		$this->_view->renderizar('exportar', 'users','vacio');



    }
	*/
}