<?php

use controllers\administradorController\administradorController;

class clientesController extends administradorController
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
		
		$this->redireccionar('administrador/clientes/lead');	
    }
	
	public function lead($pagina = false)
    {
		$this->_acl->acceso('encargado_access');
		
		//$this->_view->setJs(array('jquery.btechco.excelexport','jquery.base64','exportar_promo'));
		
		$pagina = (!validador::filtrarInt($pagina)) ? false : (int) $pagina;
		$paginador = new Paginador();

		$this->_sess->destroy('carga_actual');
		$this->_sess->destroy('edicion_actual');

		$this->_view->setCss(array('sweetalert'));
        $this->_view->setJs(array('sweetalert.min'));
		
		 $datos = $this->_trabajosGestion->traerUsers('lead');		 
		
		$this->_view->datos = $paginador->paginar($datos, $pagina, 10);
		$this->_view->paginacion = $paginador->getView('paginador-bootstrap', 'administrador/clientes/lead');
		
		 // echo "<pre>";print_r($datos);echo "</pre>";exit;
			
		$this->_view->titulo = 'Administrador - Seguimiento';
        $this->_view->renderizar('lead', 'clientes');	
    }

    public function customer($pagina = false)
    {
		$this->_acl->acceso('encargado_access');
		
		//$this->_view->setJs(array('jquery.btechco.excelexport','jquery.base64','exportar_promo'));
		
		$pagina = (!validador::filtrarInt($pagina)) ? false : (int) $pagina;
		$paginador = new Paginador();

		$this->_sess->destroy('carga_actual');
		$this->_sess->destroy('edicion_actual');

		$this->_view->setCss(array('sweetalert'));
        $this->_view->setJs(array('sweetalert.min'));
		
		 $datos = $this->_trabajosGestion->traerUsers('customer');		 
		
		$this->_view->datos = $paginador->paginar($datos, $pagina, 10);
		$this->_view->paginacion = $paginador->getView('paginador-bootstrap', 'administrador/clientes/customer');
		
		 // echo "<pre>";print_r($datos);echo "</pre>";exit;
			
		$this->_view->titulo = 'Administrador - Seguimiento';
        $this->_view->renderizar('customer', 'clientes');	
    }
	
	
	 public function inactive($pagina = false)
    {
		$this->_acl->acceso('encargado_access');
		
		//$this->_view->setJs(array('jquery.btechco.excelexport','jquery.base64','exportar_promo'));
		
		$pagina = (!validador::filtrarInt($pagina)) ? false : (int) $pagina;
		$paginador = new Paginador();

		$this->_sess->destroy('carga_actual');
		$this->_sess->destroy('edicion_actual');

		$this->_view->setCss(array('sweetalert'));
        $this->_view->setJs(array('sweetalert.min'));
		
		 $datos = $this->_trabajosGestion->traerUsers('inactive');		 
		
		$this->_view->datos = $paginador->paginar($datos, $pagina, 10);
		$this->_view->paginacion = $paginador->getView('paginador-bootstrap', 'administrador/clientes/inactive');
		
		 // echo "<pre>";print_r($datos);echo "</pre>";exit;
			
		$this->_view->titulo = 'Administrador - Seguimiento';
        $this->_view->renderizar('inactive', 'clientes');	
    }
	
	/*
	
	public function vista_previa_catalogos($_id)
    {
		$this->_acl->acceso('encargado_access');
		
		$this->_view->setCss(array('basic'));
		$this->_view->setJs(array('modernizr.2.5.3.min', 'turn'));
		
		
		
		$this->_view->trabajo = $this->_trabajosGestion->traerCatalogo($_id);
		$this->_view->imagenes = $this->_trabajosGestion->traerGaleriaPorIdentificador($this->_view->trabajo->identificador, $this->_view->trabajo->imagenes_orientacion);
		$this->_view->size = getimagesize($this->_conf['base_url']. 'public/img/subidas/catalogos/cat_'.$this->_view->trabajo->identificador.'/'.$this->_view->imagenes[0]->path);
		
		//echo $this->_view->size;
		//echo "<pre>";print_r($this->_view->size);exit;
			
		$this->_view->titulo = 'Administrador - Seguimiento';
        $this->_view->renderizar('vista_previa_catalogos', 'tendencias');	
    }
	*/
	
	
	
	public function editar($_id)
	{
		//$this->_acl->acceso('admin_access');
		$this->_acl->acceso('encargado_access');
		
		validador::validarParametroInt($_id,$this->_conf['base_url']);		
			
		
		// $this->_view->setCssPlugin(array('dropzone.min'));
		// $this->_view->setJs(array('jquery.maskedinput'));

		$this->_view->trabajo = $this->_trabajosGestion->traerUser($_id);
		// $this->_view->direcciones = $this->_trabajosGestion->traerDireccionesEnviosUsers($_id);
		// $this->_view->billing = admin::traerDireccionesBillingUsers($_id);
				
		// $this->_sess->set('edicion_actual', $this->_view->trabajo['identificador']);
		
		 // echo "<pre>";print_r($this->_view->billing);exit;		
		
				
			
		if($_POST){
			
			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
			
				if($_POST['envio01'] == 1){
					
					$this->_view->datos = $_POST;


				
					 // echo "<pre>";print_r($this->_view->datos);exit;

					/*if(!validador::getTexto('titulo')){
						$this->_view->_error ='Debe completar el campo titulo';
						$this->_view->titulo = 'Administrador - Seguimiento';
						$this->_view->renderizar('editar', 'capacitaciones');
						exit;
					} 

					if(!validador::getInt('codigo')){
						$this->_view->_error ='Debe completar el campo codigo';
						$this->_view->titulo = 'Administrador - Seguimiento';
						$this->_view->renderizar('editar', 'tendencias');
						exit;
					} 

					if(!validador::getTexto('descripcion')){
						$this->_view->_error ='Debe completar el campo descripcion';
						$this->_view->titulo = 'Administrador - Seguimiento';
						$this->_view->renderizar('editar', 'tendencias');
						exit;
					}*/


					/*if(!validador::getPostParam('nombre')){
						echo 'Debe introducir un nombre';
						exit;
					}

					if(!validador::getPostParam('apellido')){
						echo 'Debe introducir un apellido';
						exit;
					}

					if(!validador::getPostParam('dni')){
						echo 'Debe introducir un DNI';
						exit;
					}

					if(!validador::getPostParam('telefono')){
						echo 'Debe introducir un telefono';
						exit;
					}


					if(!validador::validarEmail($this->_view->datos['email'])){
						echo 'Debe introducir un email valido';
						exit;
					}*/

					/*if(!validador::validarEmail($this->_view->datos['emailConfirm'])){
						echo 'Debe introducir un email valido';
						exit;
					}*/

					/*if($this->_view->datos['email']!= $this->_view->datos['emailConfirm']){					
						echo 'El email y repetir email no coinciden';
						exit;
					}*/

					/*if($this->_view->datos['password'] != ''){

						if(!validador::getAlphaNum('password')){					
							echo 'Debe introducir su contraseña';
							exit;
						}						
						

						$_caracteres = 8;
						if(!validador::largoCadenaMin(validador::getTexto('password'), $_caracteres)){
		                    echo 'El Password debe tener como mínimo 8 caracteres';
							exit;
		                }

		                if(!preg_match('/[a-z]/', validador::getTexto('password'))){
							echo'El Password debe tener al menos una letra Minuscula.';
							exit;
						}


						if(!preg_match('/[0-9]/', validador::getTexto('password'))){
							echo 'El Password debe tener al menos un Número.';
							exit;
						}

						if(!preg_match('/[A-Z]/', validador::getTexto('password'))){
							echo 'El Password debe tener al menos una letra Mayuscula.';
							exit;
						}

					}*/

					if($this->_view->datos['password'] !=''){

				 		if(!validador::getAlphaNum('password')){					
							/*$this->_view->_error = 'Debe introducir su password';
							$this->_view->renderizar('index','registro');
							exit;*/

							echo 'You must enter your password';
							exit;
						}


						if($this->_view->datos['password']!= $this->_view->datos['repeat_password']){					
							/*$this->_view->_error = 'Debe introducir su password';
							$this->_view->renderizar('index','registro');
							exit;*/

							echo 'Password and repeat password do not match';
							exit;
						}

					}	
						
								
					
					$user = contenidos_user::find($this->_view->trabajo['id']);
					$user->nombre = admin::convertirCaracteres(validador::getTexto('nombre'));
					$user->apellido = admin::convertirCaracteres(validador::getTexto('apellido'));	
					$user->telefono = $this->_view->datos['telefono'];
					$user->email = $this->_view->datos['email'];
					$user->pais = $this->_view->datos['pais'];
					$user->state = $this->_view->datos['estado'];
					$user->ciudad = $this->_view->datos['ciudad'];
					$user->direccion = $this->_view->datos['direccion'];
					$user->cod_postal = $this->_view->datos['cod_postal'];
					$user->order_notes = admin::convertirCaracteres(validador::getTexto('order_notes'));	
					if($this->_view->datos['password'] !=''){
						$user->password = Hash::getHash('md5', $this->_xss->xss_clean($this->_view->datos['password']), $this->_conf['hash_key']);
					}
					$user->estado = $this->_view->datos['estado'];
					if($this->_view->datos['estado'] !='inactive'){
						$user->activacion = 'si';
					}else{
						$user->activacion = 'no';
					}
					// $user->fecha = date('Y-m-d H:i:s');
					$user->save();


					

					

					// $this->_sess->destroy('edicion_actual');					
					$this->redireccionar('administrador/clientes');
					
												
					
				}

			}else{
				$this->redireccionar('error/access/404');
			}
		}
	
		$this->_view->titulo = 'Administrador - Seguimiento';
        $this->_view->renderizar('editar', 'clientes');	
    }
	
	
	
	
	
	public function cargar($_categoria = null)
    {	
		$this->_acl->acceso('encargado_access');
		//$this->_view->_categoria = (int) $_categoria;
	
		if(!$this->_sess->get('carga_actual')){
			$this->_sess->set('carga_actual', rand((int)1135687452,(int)999999999));
		}
		
		$this->_view->setCssPlugin(array('dropzone.min','jquery.tagsinput-revisited'));
		$this->_view->setJs(array('dropzone', 'jquery.tagsinput-revisited'));

		// $this->_view->categorias = $this->_trabajosGestion->traerCategorias();
		// $this->_view->tags = $this->_trabajosGestion->traerTags();

		 // echo "<pre>";print_r($_SESSION);echo "</pre>";//exit;
		//unset($_SESSION['lote_img_gal']);

		// echo "<pre>";print_r($this->_view->tags);echo "</pre>";exit;

		
		//$this->_sess->destroy('img_id');
		
		
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){	
			
				if($_POST['envio01'] == 1){
					
					$this->_view->data = $_POST;					
				
					// echo "<pre>";print_r($_POST);exit;



					//Leer excel
	                $_excel = contenidos_archivos_exce::find(array('conditions' => array('id = ?', 1)));
	                if($_excel){
	                	require_once dirname(__FILE__) . '/../../../libs/PHPExcel/IOFactory.php';				

						$objReader = PHPExcel_IOFactory::createReader('Excel2007');
						//$objReader->setReadDataOnly(true);
						$objPHPExcel = $objReader->load(dirname(__FILE__) ."/../../../public/files/".$_excel->path);
						$sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, false);

	                    $_cont= array();
	                    for ($i=0; $i < count($sheetData); $i++) { 
	                        if(count(array_filter($sheetData[$i])) != 0){
	                            $_cont[] = $sheetData[$i];
	                        }
	                    }

	                    /*$_contenido = serialize($_cont);
	                    $_contenido = base64_encode($_contenido);*/

						

	                    // echo "<pre>";print_r($_cont);echo "</pre>";exit;

	                    

	                    for ($i=1; $i < count($_cont); $i++) { 

	                    	$_dat = contenidos_cliente::find(array('conditions' => array('numero_cliente = ?', $_cont[$i][0])));
	                    	if(!$_dat){
	                    		$cat = new contenidos_cliente();
								$cat->numero_cliente = $_cont[$i][0];
								$cat->razon_social = $_cont[$i][1];
								$cat->ciudad = $_cont[$i][2];
								$cat->provincia = $_cont[$i][3];				
								$cat->fecha = date('Y-m-d');
								$cat->save();
	                    	}

	                    	
	                    }


	                    $this->_sess->destroy('carga_actual');
						$this->redireccionar('administrador/clientes');

						
	                }else{
						$this->redireccionar('error/access/404');				
					}

					
							
								
					
					
				}

			}else{
				$this->redireccionar('error/access/404');
			}	
		}
	
		$this->_view->titulo = 'Administrador - Seguimiento';
        $this->_view->renderizar('cargar', 'clientes');	
    }


    public function exportaDatosUser($_id)
    {

    	$this->_acl->acceso('encargado_access');

		$data = $this->_trabajosGestion->traerUsersExportUser($_id);

		// $_direcciones = $this->_trabajosGestion->traerDireccionesEnviosUsers($data['id']);
		
		// $_facturacion = $this->_trabajosGestion->traerDatosFacturacionUsers($data['id']);
		
		// echo "<pre>";print_r($data);echo "</pre>";exit;

		$_nomb = admin::crearTitulo2(admin::convertirCaracteres($data['nombre'])).'_'.admin::crearTitulo2(admin::convertirCaracteres($data['apellido']));

		$this->_view->filename = $_nomb.'-'.date('Ymd').'.xls';	

		$this->_view->html ='<table border="1" cellpadding="10" cellspacing="0" bordercolor="#666666" style="border-collapse:collapse;">
							<tbody>
							<tr style="font-weight: bold;font-size:20px;background-color:#ddd;">
							<td colspan="12" valign="top" align="center">Client information</td>
							</tr>
							<tr style="font-weight: bold;">		
							<td>First Name</td>
							<td>Last Name</td>
							<td>Phone</td>	
							<td>Email</td>
							<td>Country</td>
							<td>State</td>
							<td>City</td>
							<td>Street Address</td>
							<td>Zip Code</td>
							<td>Order Notes</td>
							<td>Status</td>
							<td>Date</td>	
							</tr>';
			
		$this->_view->html .='<tr>';
		$this->_view->html .='<td valign="top">'.admin::convertirCaracteres3($data['nombre']).'</td>';
		$this->_view->html .='<td valign="top">'.admin::convertirCaracteres3($data['apellido']).'</td>';		
		$this->_view->html .='<td valign="top">'.$data['telefono'].'</td>';
		$this->_view->html .='<td valign="top">'.$data['email'].'</td>';
		$this->_view->html .='<td valign="top">'.$data['pais'].'</td>';
		$this->_view->html .='<td valign="top">'.$data['state'].'</td>';
		$this->_view->html .='<td valign="top">'.$data['ciudad'].'</td>';
		$this->_view->html .='<td valign="top">'.$data['direccion'].'</td>';
		$this->_view->html .='<td valign="top">'.$data['cod_postal'].'</td>';
		$this->_view->html .='<td valign="top">'.$data['order_notes'].'</td>';
		$this->_view->html .='<td valign="top">'.$data['estado'].'</td>';
		$this->_view->html .='<td valign="top">'.$data['fecha'].'</td>';			
    	$this->_view->html .='</tr>';
    	$this->_view->html .='</tbody>';
		$this->_view->html .='</table>';

		header("Pragma: public");
		header("Expires: 0");
		header("Content-type: application/x-msdownload");
		header("Content-Disposition: attachment; filename=".$this->_view->filename);
		header("Pragma: no-cache");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

		echo $this->_view->html;	  


		// $this->_view->titulo = 'Administrador - Seguimiento';
		// $this->_view->renderizar('exportar', 'clientes','vacio');
    }
   
	
	public function exportaDatos($_estado)
    {

    	$this->_acl->acceso('encargado_access');

		$_datos = $this->_trabajosGestion->traerUsersExportAll($_estado);

		// echo "<pre>";print_r($_datos);echo "</pre>";exit;

		$this->_view->filename = 'clients_'.$_estado.'-'.date('Ymd').'.xls';	

		$this->_view->html ='<table border="1" cellpadding="10" cellspacing="0" bordercolor="#666666" style="border-collapse:collapse;">
							<tbody>
							<tr style="font-weight: bold;font-size:20px;background-color:#ddd;">
							<td colspan="12" valign="top" align="center">Client information</td>
							</tr>
							<tr style="font-weight: bold;">		
							<td>First Name</td>
							<td>Last Name</td>
							<td>Phone</td>	
							<td>Email</td>
							<td>Country</td>
							<td>State</td>
							<td>City</td>
							<td>Street Address</td>
							<td>Zip Code</td>
							<td>Order Notes</td>
							<td>Status</td>
							<td>Date</td>
							</tr>';

		foreach ($_datos as $data) {
			$this->_view->html .='<tr>';
			$this->_view->html .='<td valign="top">'.admin::convertirCaracteres3($data['nombre']).'</td>';
			$this->_view->html .='<td valign="top">'.admin::convertirCaracteres3($data['apellido']).'</td>';		
			$this->_view->html .='<td valign="top">'.$data['telefono'].'</td>';
			$this->_view->html .='<td valign="top">'.$data['email'].'</td>';
			$this->_view->html .='<td valign="top">'.$data['pais'].'</td>';
			$this->_view->html .='<td valign="top">'.$data['state'].'</td>';
			$this->_view->html .='<td valign="top">'.$data['ciudad'].'</td>';
			$this->_view->html .='<td valign="top">'.$data['direccion'].'</td>';
			$this->_view->html .='<td valign="top">'.$data['cod_postal'].'</td>';
			$this->_view->html .='<td valign="top">'.$data['order_notes'].'</td>';
			$this->_view->html .='<td valign="top">'.$data['estado'].'</td>';
			$this->_view->html .='<td valign="top">'.$data['fecha'].'</td>';
	    	$this->_view->html .='</tr>';
		}
	
		
		$this->_view->html .='</tbody>
		</table>';

		header("Pragma: public");
		header("Expires: 0");
		header("Content-type: application/x-msdownload");
		header("Content-Disposition: attachment; filename=".$this->_view->filename);
		header("Pragma: no-cache");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

		echo $this->_view->html;


		// $this->_view->titulo = 'Administrador - Seguimiento';
		// $this->_view->renderizar('exportar', 'clientes','vacio');



    }

    public function buscador()
	{
		$this->_acl->acceso('encargado_access');
		
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){	
			
				$_val = $_POST['valor'];
				$_estado = $_POST['estado'];
				
				$this->_view->prod  = $this->_trabajosGestion->traerBuscadorUsers(ucwords(strtolower($_val)), $_estado);
				
				/*echo "<pre>";print_r($this->_view->users);echo"</pre>";*/
				
				$_html = '';
				if($this->_view->prod){

					foreach($this->_view->prod as $prod){ 

						// $_cliente = admin::traerClientePorUsers($prod['id_cliente']);
						
		        		$_html .='<div class="forum-item">
		        					<div class="row">
		        						<div class="col-md-8">
											<a href="" class="forum-item-title">'.$prod['nombre'].' '.$prod['apellido'].'</a>
											<small>Email: <strong>'.$prod['email'].'</strong></small>
						                   	&nbsp;&nbsp;
						                   	<small>Estado: <strong>'.$prod['estado'].'</strong></small>
										</div>
										
										<div class="col-md-4 forum-info">
							                <div class="tooltip-demo pull-right">

							                	<a class="btn btn-success" href="'.$this->_conf['url_enlace'].'administrador/clientes/exportaDatosUser/'.$prod['id'].'">
						                          Export
						                        </a>&nbsp;&nbsp;
						                          							
												<a class="btn btn-warning" href="'.$this->_conf['url_enlace'].'administrador/clientes/editar/'. $prod['id'].'">
													Detail
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
									                        title: "Are you sure you want to delete this content?\nIf you delete the client, all associated orders will be deleted",
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
									                            var url= _root_ + "administrador/clientes/borrar";
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
	
	public function borrar()
	{
		$this->_acl->acceso('encargado_access');
		//$_id = (int) $_id;
		

		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){		
			
				$_id = (int) $_POST['_id'];
		

				$_borrar = $this->_trabajosGestion->borrarUser($_id);
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

	
	
}