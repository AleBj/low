<?php

use controllers\administradorController\administradorController;

class comprasController extends administradorController
{
	public $_trabajosGestion;
	
    public function __construct() 
    {
		parent::__construct();
		
		$this->getLibrary('class.validador');
		
		$this->getLibrary('class.admin');
		$this->_trabajosGestion = new admin();
		
		// $this->getLibrary('PHPMailerAutoload');
		// $this->envioMail = new PHPMailer();
		
		$this->getLibrary('class.upload');
		
		$this->_error = 'has-error';
		$this->_filtro = '';
		
    }
    
    public function index()
    {	
		$this->redireccionar('administrador/compras/aprobados');	
    }
	
	public function aprobados($pagina = false)
    {
		$this->_acl->acceso('encargado_access');
		
		//$this->_view->setJs(array('jquery.btechco.excelexport','jquery.base64','exportar_data'));
		// $this->_view->setCss(array('sweetalert'));		
		// $this->_view->setJs(array('sweetalert.min'));
		
		$pagina = (!validador::filtrarInt($pagina)) ? false : (int) $pagina;
		$paginador = new Paginador();

		$this->_view->tit = 'Approved orders';
		$datos = $this->_trabajosGestion->traerPedidos('approved');


		// echo "<pre>";print_r($this->_view->datos);exit;
		
		$this->_view->datos = $paginador->paginar($datos, $pagina, 10);
		$this->_view->paginacion = $paginador->getView('paginador-bootstrap', 'administrador/compras/aprobados');
			
		$this->_view->titulo = 'Administrador - Seguimiento';
        $this->_view->renderizar('aprobados', 'compras');	
    }

    public function rechazados($pagina = false)
    {
		$this->_acl->acceso('encargado_access');
		
		//$this->_view->setJs(array('jquery.btechco.excelexport','jquery.base64','exportar_data'));
		// $this->_view->setCss(array('sweetalert'));		
		// $this->_view->setJs(array('sweetalert.min'));
		
		$pagina = (!validador::filtrarInt($pagina)) ? false : (int) $pagina;
		$paginador = new Paginador();
		
		$this->_view->tit = 'Rejected orders';
		$datos = $this->_trabajosGestion->traerPedidos('rejected');


		// echo "<pre>";print_r($this->_view->datos);exit;
		
		$this->_view->datos = $paginador->paginar($datos, $pagina, 10);
		$this->_view->paginacion = $paginador->getView('paginador-bootstrap', 'administrador/compras/rechazados');
			
		$this->_view->titulo = 'Administrador - Seguimiento';
        $this->_view->renderizar('rechazados', 'compras');	
    }
	
	public function pendientes($pagina = false)
    {
		$this->_acl->acceso('encargado_access');
		
		//$this->_view->setJs(array('jquery.btechco.excelexport','jquery.base64','exportar_data'));
		$this->_view->setCss(array('sweetalert'));		
		$this->_view->setJs(array('sweetalert.min'));
		
		$pagina = (!validador::filtrarInt($pagina)) ? false : (int) $pagina;
		$paginador = new Paginador();

		$this->_view->tit = 'Pending orders';
		$datos = $this->_trabajosGestion->traerPedidos('pending');
		
		$this->_view->datos = $paginador->paginar($datos, $pagina, 10);
		$this->_view->paginacion = $paginador->getView('paginador-bootstrap', 'administrador/compras/pendientes');
			
		$this->_view->titulo = 'Administrador - Seguimiento';
        $this->_view->renderizar('pendientes', 'compras');	
    }

   /* public function devoluciones($pagina = false)
    {
		$this->_acl->acceso('encargado_access');
		
		//$this->_view->setJs(array('jquery.btechco.excelexport','jquery.base64','exportar_data'));
		$this->_view->setCss(array('sweetalert'));		
		$this->_view->setJs(array('sweetalert.min'));
		
		$pagina = (!validador::filtrarInt($pagina)) ? false : (int) $pagina;
		$paginador = new Paginador();

		$this->_view->tit = 'Compras Devoluciones';
		$datos = $this->_trabajosGestion->traerPedidos('devolucion');
		
		$this->_view->datos = $paginador->paginar($datos, $pagina, 10);
		$this->_view->paginacion = $paginador->getView('paginador-bootstrap', 'administrador/compras/devoluciones');
			
		$this->_view->titulo = 'Administrador - Seguimiento';
        $this->_view->renderizar('devoluciones', 'compras');	
    }*/
	
	/*public function enprogreso($pagina = false)
    {
		$this->_acl->acceso('encargado_access');
		
		//$this->_view->setJs(array('jquery.btechco.excelexport','jquery.base64','exportar_data'));
		$this->_view->setCss(array('sweetalert'));		
		$this->_view->setJs(array('sweetalert.min'));
		
		// $pagina = (!validador::filtrarInt($pagina)) ? false : (int) $pagina;
		// $paginador = new Paginador();
		$this->_view->tit = 'Compras en progreso';
		$this->_view->datos = $this->_trabajosGestion->traerPedidos('enprogreso');
		
		// $this->_view->beneficios = $paginador->paginar($beneficios, $pagina, 20);
		// $this->_view->paginacion = $paginador->getView('paginador-bootstrap', 'administrador/beneficios/listarAlta');
			
		$this->_view->titulo = 'Administrador - Seguimiento';
        $this->_view->renderizar('index', 'compras');	
    }
	*/
	
	public function ver($_id)
	{
		//$this->_acl->acceso('admin_access');
		$this->_acl->acceso('encargado_access');
		
		validador::validarParametroInt($_id,$this->_conf['base_url']);	
		
		// $this->_view->setCssPlugin(array('dropzone.min'));
		// $this->_view->setJs(array('dropzone'));	
		
		$this->_view->trabajo = $this->_trabajosGestion->traerPedido($_id);	
		$this->_view->user = $this->_trabajosGestion->traerUser($this->_view->trabajo['id_user']);
		$this->_view->productos = $this->_trabajosGestion->traerDatosCarrito($this->_view->trabajo['id']);


		$this->_view->trabajo['data_pago'] =  json_decode(unserialize(base64_decode($this->_view->trabajo['data_pago'])));
		

		 // echo "<pre>";print_r($this->_view->trabajo);exit;
		// echo "<pre>";print_r($this->_view->trabajo['data_pago']['Payload']['Answer']['PAYMENTMETHODNAME']);exit;
				
			
		if($_POST){
			
			
			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){	
			
				$this->_view->data = $_POST;	
				
				// echo "<pre>";print_r($this->_view->data);exit;				
				
				$_editar = contenidos_compra::find($this->_view->trabajo['id']);
				$_editar->estado = $this->_view->data['estado'];
				$_editar->save();
				
				
				/*$this->_sess->destroy('edicion_actual');
				$this->_sess->destroy('img_id');*/
				if($this->_view->data['estado']=='approved'){
					$this->redireccionar('administrador/compras/aprobados');
				}else if($this->_view->data['estado']=='pending'){
					$this->redireccionar('administrador/compras/pendientes');
				}else{
					$this->redireccionar('administrador/compras/rechazados');
				}
				
											
				 
			}
		}
	
		$this->_view->titulo = 'Administrador - Seguimiento';
        $this->_view->renderizar('ver', 'compras');	
    }

    public function exportar($_estado)
    {

    	$this->_acl->acceso('encargado_access');

		$_datos = $this->_trabajosGestion->traerComprasExport($_estado);

		for ($i=0; $i < count($_datos) ; $i++) { 
			// $_data_pago = base64_decode($_datos[$i]['data_pago']);
			$_datos[$i]['data_pago'] =  unserialize(base64_decode($_datos[$i]['data_pago']));
		}

		// echo "<pre>";print_r($_datos);echo "</pre>";exit;

		$this->_view->filename = 'compras_'.$_estado.'_'.date('d-m-Y').'.xls';	

		$this->_view->html ='<table border="1" cellpadding="10" cellspacing="0" bordercolor="#666666" style="border-collapse:collapse;">
		<tbody>
		<tr style="font-weight: bold;">		
		<td>'.utf8_decode('N°').' Compra</td>
		<td>Nombre</td>
		<td>Apellido</td>
		<td>DNI</td>
		<td>Telefono</td>
		<td>Email</td>	
		<td>Sucursal</td>
		<td>Plataforma de Pago</td>
		<td>Subtotal</td>
		<td>Descuento</td>		
		<td>Total</td>
		<td>Fecha de compra</td>
		</tr>';

		foreach ($_datos as $data) {

			if($data['plataforma_pago'] == 'transfer'){
				$_plataforma_pago = 'Transferencia Bancaria';
			}else if($data['plataforma_pago'] == 'mp'){
				$_plataforma_pago = 'Mercado Pago';
			}else if($data['plataforma_pago'] == 'tp'){
				$_plataforma_pago = 'Todo Pago';
			}


			$this->_view->html .='<tr>';
			$this->_view->html .='<td valign="top">'.$data['id'].'</td>';
			$this->_view->html .='<td valign="top">'.admin::convertirCaracteres3($data['nombre']).'</td>';
			$this->_view->html .='<td valign="top">'.admin::convertirCaracteres3($data['apellido']).'</td>';
			$this->_view->html .='<td valign="top">'.$data['dni'].'</td>';
			$this->_view->html .='<td valign="top">'.$data['telefono'].'</td>';
			$this->_view->html .='<td valign="top">'.$data['email'].'</td>';
			$this->_view->html .='<td valign="top">'.utf8_decode(admin::traerSucursalId($data['id_sucursal'])->nombre).'</td>';
			$this->_view->html .='<td valign="top">'.$_plataforma_pago.'</td>';
			$this->_view->html .='<td valign="top">$'.number_format($data['subtotal'], 2, '.', ',').'</td>';
			$this->_view->html .='<td valign="top">$'.number_format($data['descuento'], 2, '.', ',').'</td>';
			$this->_view->html .='<td valign="top">$'.number_format($data['total'], 2, '.', ',').'</td>';
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
		// $this->_view->renderizar('exportar', 'compras','vacio');



    }



	 public function buscador()
	{
		$this->_acl->acceso('encargado_access');
		
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){	
			
				$_val = $_POST['buscador'];
				$_estado = $_POST['estado'];
				
				$_prod  = $this->_trabajosGestion->traerBuscadorCompras($_val, $_estado);
				
				// echo "<pre>";print_r($_prod);echo"</pre>";exit;
				
				$_html = '';
				if($_prod){
					// foreach($_prod as $prod){ 
					for ($i=0; $i <count($_prod); $i++) { 
					

						// $_cliente = admin::traerClientePorUsers($prod['id_cliente']);
						
		        		$_html .='<div class="forum-item">
		        					<div class="row">
		        						<div class="col-md-10">
											<a href="" class="forum-item-title">
											
												<small>'.date ("Y-m-d", strtotime($_prod[$i]['fecha'])).'</small>
						                        <br />
						                        Client: <strong>'.admin::convertirCaracteres(admin::traerUserPorId($_prod[$i]['id_user'])->nombre).' '.admin::convertirCaracteres(admin::traerUserPorId($_prod[$i]['id_user'])->apellido).'</strong>
						                        &nbsp;-&nbsp;
						                        N&deg order: <strong>'.$_prod[$i]['id'].'</strong>
					                        </a>

										</div>
										
										<div class="col-md-2 forum-info">
							                <div class="tooltip-demo pull-right">   							
												<a class="btn btn-warning" href="'.$this->_conf['url_enlace'].'administrador/compras/ver/'. $_prod[$i]['id'].'">
													Detail
												</a>&nbsp;&nbsp;

												<!-- <a href="javascript:void(0);" class="btn btn-danger _borrar_'. $_prod[$i]['id'].'" title="Borrar">
						                            Eliminar
						                        </a>&nbsp;&nbsp; -->
								                        
											</div>
						                </div>
						            </div>
						        </div>';

						/*$_html .= '<script>					        			
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
									                            var url= _root_ + "administrador/compras/borrar";
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
									        </script>';*/
						
		        
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
	
	
	//////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////
	
	public function cargar($_categoria = null)
    {	
		$this->_acl->acceso('encargado_access');
		//$this->_view->_categoria = (int) $_categoria;
	
		if(!$this->_sess->get('carga_actual')){
			$this->_sess->set('carga_actual', rand(1135687452,9999999999));
		}
		
		$this->_view->setCssPlugin(array('dropzone.min'));
		$this->_view->setJs(array('dropzone.min'));
		
		$this->_view->cat = $this->_trabajosGestion->traerCategorias();
		
		//$this->_sess->destroy('img_id');
		
		
		if($_POST){
			
			
			if($_POST['envio01'] == 1){
				
				$this->_view->data = $_POST;
				
			
				//echo "<pre>";print_r($_POST);exit;
				
				
				//SETEAR FECHA Y HORA
				/*$_fecha_format = explode('-',$_POST['fecha']);
				$_fecha = admin::formatFechaSql($_POST['fecha']);
				$_hora = $_POST['hora'];
				$_fecha_hora_format = $_fecha.' '.$_hora;
				$time = strtotime($_fecha_hora_format);
				$_fecha_hora_format = date('Y-m-d H:i:s',$time);*/
								
				/*echo $_fecha_hora_format;
				exit;*/
			
				$reel = contenidos_producto::create(array(					
					'id_categoria'		=> $this->_view->data['categoria'],
					'id_promo'			=> 0,
					'stock'				=> $this->_view->data['stock'],
					'titulo'			=> htmlentities($this->_view->data['titulo']),					
					'sku'				=> $this->_view->data['sku'],
					'ancho'				=> $this->_view->data['ancho'],
					'perfil'			=> $this->_view->data['perfil'],
					'rodado'			=> $this->_view->data['rodado'],
					//'diametro'			=> $this->_view->data['diametro'],
					//'serie'				=> $this->_view->data['serie'],
					//'ic'				=> $this->_view->data['ic'],
					//'iv'				=> $this->_view->data['iv'],					
					'descripcion'		=> validador::getTexto('desc'),					
					'beneficios'		=> validador::getTexto('beneficios'),												
					'datos_tecnicos'	=> validador::getTexto('datos_tecnicos'),
					'precio'				=> $this->_view->data['precio'],						
					'identificador'		=> $this->_sess->get('carga_actual'),
					'estado'			=> $this->_view->data['estado'],
					//'orden' 			=> admin::ultimoOrdenBlog()->orden+1,
					'fecha'				=> date('Y-m-d'),
				));
					
				
				
							
				
				$this->_sess->destroy('carga_actual');
				//$this->_sess->destroy('img_id');
				$this->redireccionar('administrador/productos/altas');
			}	
		}
	
		$this->_view->titulo = 'Administrador - Seguimiento';
        $this->_view->renderizar('cargar', 'productos');	
    }
	
	
	
	public function editar($_id)
	{
		//$this->_acl->acceso('admin_access');
		$this->_acl->acceso('encargado_access');
		
		validador::validarParametroInt($_id,$this->_conf['base_url']);	
		
		$this->_view->setCssPlugin(array('dropzone.min'));
		$this->_view->setJs(array('dropzone.min'));	
		
		$this->_view->trabajo = $this->_trabajosGestion->traerProducto($_id);	
		$this->_view->cat = $this->_trabajosGestion->traerCategorias();
		$this->_sess->set('edicion_actual', $this->_view->trabajo->identificador);
		
		/*echo "<pre>";
		print_r($this->_view->imgs);
		echo "</pre>";*/
								
		
				
			
		if($_POST){
			
			
			if($_POST['envio01'] == 1){
			
				$this->_view->data = $_POST;	
				
				
				if(isset($this->_view->data['eliminar_gal']) && $this->_view->data['eliminar_gal'][0]!=''){
					foreach($this->_view->data['eliminar_gal'] as $_gal){
						$this->_trabajosGestion->eliminarImgGal($_gal, $this->_conf['ruta_img_cargadas'], 'productos', 'galeria');			
					}
				}
				
				
				/*$fecha = date("Y-m-d", strtotime($this->_view->data['fecha_nacimiento']));
				$_fecha_separada = explode('-',$fecha);*/
				
							
				
				//echo $_dia_festivo ;
				//echo "<pre>";print_r($this->_view->data);exit;
				
				
				$_editar = contenidos_producto::find($this->_view->trabajo->id);
				$_editar->id_categoria = $this->_view->data['categoria'];
				$_editar->stock = $this->_view->data['stock'];
				//$_editar->titulo = admin::convertirCaracteres5(validador::getTexto('titulo'));
				$_editar->titulo = htmlentities($this->_view->data['titulo']);
				$_editar->sku = $this->_view->data['sku'];
				$_editar->ancho = $this->_view->data['ancho'];
				$_editar->perfil = $this->_view->data['perfil'];
				$_editar->rodado = $this->_view->data['rodado'];
				//$_editar->diametro = $this->_view->data['diametro'];
				//$_editar->serie = $this->_view->data['serie'];
				//$_editar->ic = $this->_view->data['ic'];
				//$_editar->iv = $this->_view->data['iv'];
				$_editar->descripcion = validador::getTexto('desc');
				$_editar->beneficios = validador::getTexto('beneficios');
				$_editar->datos_tecnicos = validador::getTexto('datos_tecnicos');
				$_editar->precio = $this->_view->data['precio'];
				$_editar->estado = $this->_view->data['estado'];
				//$_editar->fecha = $_fecha_hora_format;
				$_editar->save();
				
				/*$this->_sess->destroy('edicion_actual');
				$this->_sess->destroy('img_id');*/
				if($this->_view->data['estado']==1){
					$this->redireccionar('administrador/productos/altas');
				}else{
					$this->redireccionar('administrador/productos/bajas');
				}
				
				
											
				
			}
		}
	
		$this->_view->titulo = 'Administrador - Seguimiento';
        $this->_view->renderizar('editar', 'productos');	
    }
	
	
	
	
	
	
	
	
	
	public function borrar($_id)
	{
		$this->_acl->acceso('encargado_access');
		$_id = (int) $_id;
		
		//$this->_view->trabajo = $this->_trabajosGestion->traerTrabajo($_id);
		
		$this->_trabajosGestion->borrarProducto($_id, $this->_conf['ruta_img_cargadas'], 'productos');
		//$this->redireccionar('administrador/trabajos/altas');
		echo 'ok';
	}
	
	
	
	
}