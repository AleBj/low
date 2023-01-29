<?php

use controllers\administradorController\administradorController;

class formsController extends administradorController
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
		$this->redireccionar('administrador/forms/listado');	
   	}
	
	public function listado($pagina = false)
   	{
		$this->_acl->acceso('encargado_access');
		
		//$this->_view->setJs(array('jquery.btechco.excelexport','jquery.base64','exportar_data'));
		// $this->_view->setCss(array('sweetalert'));		
		// $this->_view->setJs(array('sweetalert.min'));
		
		$pagina = (!validador::filtrarInt($pagina)) ? false : (int) $pagina;
		$paginador = new Paginador();

		$datos = $this->_trabajosGestion->traerAllForms();


		// echo "<pre>";print_r($datos);exit;
		
		$this->_view->datos = $paginador->paginar($datos, $pagina, 10);
		$this->_view->paginacion = $paginador->getView('paginador-bootstrap', 'administrador/forms/listado');
			
		$this->_view->titulo = 'Administrador - Seguimiento';
        $this->_view->renderizar('listado', 'forms');	
   	}
 
	
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
				
				$_prod  = $this->_trabajosGestion->traerBuscadorForms($_val);
				
				// echo "<pre>";print_r($_prod);echo"</pre>";exit;
				
				$_html = '';
				if($_prod){
					// foreach($_prod as $prod){ 
					for ($i=0; $i <count($_prod); $i++) { 

						$_exist_user = admin::traerUserPorId($_prod[$i]['id_user']);
						if($_exist_user){
					
							$_state = ($_prod[$i]['estado'] == 'close') ? 'Expired' : ucfirst($_prod[$i]['estado']);
							// $_cliente = admin::traerClientePorUsers($prod['id_cliente']);
						
		        			$_html .='<div class="forum-item">
		        					<div class="row">
		        						<div class="col-md-10">
											<a href="" class="forum-item-title">
											
												N&deg order: <strong>'.$_prod[$i]['id_compra'].'</strong>
												<br>
						                        <small>Client: <strong>'.admin::convertirCaracteres(admin::traerUserPorId($_prod[$i]['id_user'])->nombre).' '.admin::convertirCaracteres(admin::traerUserPorId($_prod[$i]['id_user'])->apellido).'</strong></small>
						                        <br>
                       							<small>Product: <strong>'.admin::convertirCaracteres($_prod[$i]['titulo']).'</strong></small>
					                        </a>

										</div>
										
										<div class="col-md-2 forum-info">
							                <div class="tooltip-demo pull-right">'; 


							            if($_prod[$i]['estado'] == 'complete'){
					                       $_html .=' <a class="btn btn-info btn-sm" href="javascript:void(0);" style="cursor:default">';
					                    } elseif($_prod[$i]['estado'] == 'incomplete'){
					                       $_html .=' <a class="btn btn-warning btn-sm" href="javascript:void(0);" style="cursor:default">';
					                    }elseif($_prod[$i]['estado'] == 'close'){
					                       $_html .=' <a class="btn btn-default btn-sm" href="javascript:void(0);" style="cursor:default">';
					                    }else{
					                       $_html .=' <a class="btn btn-danger btn-sm" href="javascript:void(0);" style="cursor:default">';
					                    }
					                       $_html .='Status: <strong>'.$_state.'</strong>
					                        </a>';
					                    
					                    if($_prod[$i]['estado'] == 'complete'){
					                        $_html .='&nbsp;&nbsp;<a class="btn btn-success " href="'.$this->_conf['url_enlace'].'administrador/compras/ver/'. $_prod[$i]['id'].'">
					                           Download
					                        </a>';
					                    }									
												
								                        
										$_html .='</div>
						                </div>
						            </div>
						        </div>';

						
						}
		        
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
	
	public function buscadorEstado()
	{
		$this->_acl->acceso('encargado_access');
		
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){	
			
				$_val = $_POST['status'];
				// $_estado = $_POST['estado'];
				
				$_prod  = $this->_trabajosGestion->traerBuscadorFormsEstado($_val);
				
				// echo "<pre>";print_r($_prod);echo"</pre>";exit;
				
				$_html = '';
				if($_prod){
					// foreach($_prod as $prod){ 
					for ($i=0; $i <count($_prod); $i++) { 


						$_exist_user = admin::traerUserPorId($_prod[$i]['id_user']);
						if($_exist_user){
					
							$_docu = admin::traerDocuSignPorId($_prod[$i]['id']);
							// $_cliente = admin::traerClientePorUsers($prod['id_cliente']);

							$_state = ($_prod[$i]['estado'] == 'close') ? 'Expired' : ucfirst($_prod[$i]['estado']);
						
		        			$_html .='<div class="forum-item">
		        					<div class="row">
		        						<div class="col-md-7">
											<a href="" class="forum-item-title">
											
												N&deg order: <strong>'.$_prod[$i]['id_compra'].'</strong>
												<br>
						                        <small>Client: <strong>'.admin::convertirCaracteres(admin::traerUserPorId($_prod[$i]['id_user'])->nombre).' '.admin::convertirCaracteres(admin::traerUserPorId($_prod[$i]['id_user'])->apellido).'</strong></small>
						                        <br>
                       							<small>Product: <strong>'.admin::convertirCaracteres($_prod[$i]['titulo']).'</strong></small>
					                        </a>

										</div>
										
										<div class="col-md-5 forum-info">
							                <div class="tooltip-demo pull-right">'; 


							            if($_prod[$i]['estado'] == 'complete'){
					                       $_html .=' <a class="btn btn-info btn-sm" href="javascript:void(0);" style="cursor:default">';
					                    } elseif($_prod[$i]['estado'] == 'incomplete'){
					                       $_html .=' <a class="btn btn-warning btn-sm" href="javascript:void(0);" style="cursor:default">';
					                    }elseif($_prod[$i]['estado'] == 'close'){
					                       $_html .=' <a class="btn btn-default btn-sm" href="javascript:void(0);" style="cursor:default">';
					                    }else{
					                       $_html .=' <a class="btn btn-danger btn-sm" href="javascript:void(0);" style="cursor:default">';
					                    }
					                    
					                    $_html .='Status: <strong>'.$_state.'</strong>
					                        </a>';
					                    
					                    if($_prod[$i]['estado'] == 'complete'){
					                        /*$_html .='&nbsp;&nbsp;<a class="btn btn-success " href="'.$this->_conf['url_enlace'].'administrador/compras/ver/'. $_prod[$i]['id'].'">
					                           Download
					                        </a>';*/


					                        $_html .=' <a class="btn btn-warning" href="'. $this->_conf['url_enlace'].'administrador/forms/verform/'.$_prod[$i]['id'].'/'.$_prod[$i]['id_compra'].'" target="_blank" alt="Download PDF" title="Download PDF">
					                           <i class="fa fa-download"></i>
					                        </a>
					                        
					                       <a class="btn btn-primary" onclick="generarDocs('.$_prod[$i]['id'].', '.$_prod[$i]['id_compra'].');" href="javascript:void(0);" alt="Generate documents" title="Generate documents">
					                           <i class="fa fa-file-text"></i>
					                        </a>';

					                        if(file_exists($this->_conf['ruta_archivos_descargas'].'forms/'.$_prod[$i]['item'].'/'.$_prod[$i]['id'].'/'.$_prod[$i]['item'].'-'.$_prod[$i]['id_compra'].'.zip')){
						                        $_html .='<a class="btn btn-success _download" href="'.$this->_conf['url_enlace'].'public/descargas/forms/'.$_prod[$i]['item'].'/'.$_prod[$i]['id'].'/'.$_prod[$i]['item'].'-'.$_prod[$i]['id_compra'].'.zip" alt="Download Documents" title="Download Documents">
						                           <i class="fa fa-download"></i>
						                        </a>';
					                        } else{
						                         $_html .='<a class="btn btn-success _download" href="'.$this->_conf['url_enlace'].'public/descargas/forms/'.$_prod[$i]['item'].'/'.$_prod[$i]['id'].'/'.$_prod[$i]['item'].'-'.$_prod[$i]['id_compra'].'.zip" alt="Download Documents" title="Download Documents" style="display:none;">
						                           <i class="fa fa-download"></i>
						                        </a>';
					                        }     

					                       if(isset($_docu['docusign']) && $_docu['docusign'] == 'si'){
						                         $_html .='<a class="btn btn-info btn-sm _docusign" href="javascript:void(0);" style="cursor:default;">
						                            Sent to <strong>DocuSign</strong>
						                        </a>';
					                        } else{
						                         $_html .='<a class="btn btn-info btn-sm _docusign" href="javascript:void(0);" style="display:none;cursor:default;">
						                            Sent to <strong>DocuSign</strong>
						                        </a>';
					                        }                 

					                        $_html .=' <a class="btn btn-white _loading" style="display:none;">
					                            <i class="fa fa-cog fa-spin fa-lg"></i>
					                        </a>';


					                    }									
												
								                        
										$_html .='</div>
						                </div>
						            </div>
						        </div>';

						
						}
		        
					}			
				}else{
					$_html = 'No data loaded!';
				}
				echo $_html;

			}else{
				$this->redireccionar('error/access/404');
			}
			
		}
	}


	public function verform($_id, $_id_compra)
	{	
		$this->_acl->acceso('encargado_access');	

		$this->_view->id_form = $_id;
		$this->_view->user = $this->_trabajosGestion->traerUser($this->_sess->get('id_usuario_front'));
		$this->_view->condados =  $this->_trabajosGestion->traerFormsCondados();
		$this->_view->states =  $this->_trabajosGestion->traerFormsStates();
		$this->_view->order = $this->_trabajosGestion->traerOrder($_id_compra);
		$this->_view->order['forms'] =  $this->_trabajosGestion->traerFormsPorId($_id);
		$this->_view->order['form_info']['id_producto'] =$this->_view->order['forms']['id_producto'];
		$this->_view->order['form_info']['item_producto'] = admin::traerProductoPorId($this->_view->order['forms']['id_producto'])->item;
		$this->_view->order['form_info']['estado'] =$this->_view->order['forms']['estado'];
		$this->_view->order['form_info']['img'] =$this->_view->order['forms']['img'];
		$this->_view->order['form_info']['fecha'] =$this->_view->order['forms']['fecha'];
		unset($this->_view->order['forms']['id'], $this->_view->order['forms']['id_compra'], $this->_view->order['forms']['id_user'], $this->_view->order['forms']['id_producto'], $this->_view->order['forms']['estado'], $this->_view->order['forms']['img'], $this->_view->order['forms']['fecha']);
		
		// echo "<pre>";print_r($this->_view->order);exit;
		 

		if($this->_view->order['forms']['divorce_information']!=''){
			$this->_view->order['forms']['divorce_information'] = unserialize(base64_decode($this->_view->order['forms']['divorce_information'] ));
		}
		if($this->_view->order['forms']['divorce_residency']!=''){
			$this->_view->order['forms']['divorce_residency'] = unserialize(base64_decode($this->_view->order['forms']['divorce_residency'] ));
		}

		if($this->_view->order['forms']['wife_information']!=''){
			$this->_view->order['forms']['wife_information'] = unserialize(base64_decode($this->_view->order['forms']['wife_information'] ));
		}

		if($this->_view->order['forms']['wife_identification']!=''){
			$this->_view->order['forms']['wife_identification'] = unserialize(base64_decode($this->_view->order['forms']['wife_identification'] ));
		}
		
		if($this->_view->order['forms']['wife_employer_information']!=''){
			$this->_view->order['forms']['wife_employer_information'] = unserialize(base64_decode($this->_view->order['forms']['wife_employer_information'] ));
		}

		if($this->_view->order['forms']['wife_monthly_income']!=''){
			$this->_view->order['forms']['wife_monthly_income'] = unserialize(base64_decode($this->_view->order['forms']['wife_monthly_income'] ));
		}

		if($this->_view->order['forms']['wife_monthly_average']!=''){
			$this->_view->order['forms']['wife_monthly_average'] = unserialize(base64_decode($this->_view->order['forms']['wife_monthly_average'] ));
		}


		if($this->_view->order['forms']['wife_child_care_expenses']!=''){
			$this->_view->order['forms']['wife_child_care_expenses'] = unserialize(base64_decode($this->_view->order['forms']['wife_child_care_expenses'] ));
		}

		if($this->_view->order['forms']['wife_other_child_support']!=''){
			$this->_view->order['forms']['wife_other_child_support'] = unserialize(base64_decode($this->_view->order['forms']['wife_other_child_support'] ));
		}



		if($this->_view->order['forms']['husband_personal_information']!=''){
			$this->_view->order['forms']['husband_personal_information'] = unserialize(base64_decode($this->_view->order['forms']['husband_personal_information'] ));
		}

		if($this->_view->order['forms']['husband_identification']!=''){
			$this->_view->order['forms']['husband_identification'] = unserialize(base64_decode($this->_view->order['forms']['husband_identification'] ));
		}
		
		if($this->_view->order['forms']['husband_employer_information']!=''){
			$this->_view->order['forms']['husband_employer_information'] = unserialize(base64_decode($this->_view->order['forms']['husband_employer_information'] ));
		}

		if($this->_view->order['forms']['husband_monthly_income']!=''){
			$this->_view->order['forms']['husband_monthly_income'] = unserialize(base64_decode($this->_view->order['forms']['husband_monthly_income'] ));
		}

		if($this->_view->order['forms']['husband_monthly_average']!=''){
			$this->_view->order['forms']['husband_monthly_average'] = unserialize(base64_decode($this->_view->order['forms']['husband_monthly_average'] ));
		}

		if($this->_view->order['forms']['husband_child_care_expenses']!=''){
			$this->_view->order['forms']['husband_child_care_expenses'] = unserialize(base64_decode($this->_view->order['forms']['husband_child_care_expenses'] ));
		}

		if($this->_view->order['forms']['husband_other_child_support']!=''){
			$this->_view->order['forms']['husband_other_child_support'] = unserialize(base64_decode($this->_view->order['forms']['husband_other_child_support'] ));
		}

		if($this->_view->order['forms']['child_care']!=''){
			$this->_view->order['forms']['child_care'] = unserialize(base64_decode($this->_view->order['forms']['child_care']));
		}


		if($this->_view->order['forms']['property_division']!=''){
			$this->_view->order['forms']['property_division'] = unserialize(base64_decode($this->_view->order['forms']['property_division'] ));
		}else{
			unset($this->_view->order['forms']['property_division']);
		}

		if($this->_view->order['forms']['debt']!=''){
			$this->_view->order['forms']['debt'] = unserialize(base64_decode($this->_view->order['forms']['debt'] ));
		}else{
			unset($this->_view->order['forms']['debt']);
		}

		if($this->_view->order['forms']['spousal_support']!=''){
			$this->_view->order['forms']['spousal_support'] = unserialize(base64_decode($this->_view->order['forms']['spousal_support'] ));
		}else{
			unset($this->_view->order['forms']['spousal_support']);
		}

		if($this->_view->order['forms']['name_change']!=''){
			$this->_view->order['forms']['name_change'] = unserialize(base64_decode($this->_view->order['forms']['name_change'] ));
		}


		if($this->_view->order['forms']['children_from_the_marriage']!=''){
			$this->_view->order['forms']['children_from_the_marriage'] = unserialize(base64_decode($this->_view->order['forms']['children_from_the_marriage'] ));
		}

		if($this->_view->order['forms']['children_residence']!=''){
			$this->_view->order['forms']['children_residence'] = unserialize(base64_decode($this->_view->order['forms']['children_residence']));
		}


		if($this->_view->order['forms']['custody_and_visitation']!=''){
			$this->_view->order['forms']['custody_and_visitation'] = unserialize(base64_decode($this->_view->order['forms']['custody_and_visitation'] ));
		}

		if($this->_view->order['forms']['parenting_schedule_holidays']!=''){
			$this->_view->order['forms']['parenting_schedule_holidays'] = unserialize(base64_decode($this->_view->order['forms']['parenting_schedule_holidays'] ));
		}

		if($this->_view->order['forms']['parenting_schedule_special_occasions']!=''){
			$this->_view->order['forms']['parenting_schedule_special_occasions'] = unserialize(base64_decode($this->_view->order['forms']['parenting_schedule_special_occasions'] ));
		}

		if($this->_view->order['forms']['parenting_transportation']!=''){
			$this->_view->order['forms']['parenting_transportation'] = unserialize(base64_decode($this->_view->order['forms']['parenting_transportation'] ));
		}

		if($this->_view->order['forms']['parenting_major_decisions']!=''){
			$this->_view->order['forms']['parenting_major_decisions'] = unserialize(base64_decode($this->_view->order['forms']['parenting_major_decisions'] ));
		}

		if($this->_view->order['forms']['parenting_income_tax_exemptions']!=''){
			$this->_view->order['forms']['parenting_income_tax_exemptions'] = unserialize(base64_decode($this->_view->order['forms']['parenting_income_tax_exemptions'] ));
		}

		if($this->_view->order['forms']['child_support']!=''){
			$this->_view->order['forms']['child_support'] = unserialize(base64_decode($this->_view->order['forms']['child_support'] ));

			$_data = $this->_trabajosGestion->calculateChildSupport($_id);

			// echo "<pre>";print_r($_data);exit;

			if($_data){


				if($_data['mother_payment'] != $_data['father_payment']){

					if($_data['mother_payment'] != 0){
						$this->_view->calculate['payment_parent'] = 'Mother';
						$this->_view->calculate['payment_receiver'] = 'Father';
						$this->_view->calculate['payment_using'] = number_format($_data['mother_payment'], 2, '.', ',');
					}

					if($_data['father_payment'] != 0){
						$this->_view->calculate['payment_parent'] = 'Father';
						$this->_view->calculate['payment_receiver'] = 'Mother';
						$this->_view->calculate['payment_using'] = number_format($_data['father_payment'], 2, '.', ',');
					}


				}else{
					$this->_view->calculate['payment_parent'] = '';
					$this->_view->calculate['payment_using'] = number_format(0, 2, '.', ',');
				}


				$this->_view->calculate['mother_income'] = number_format($_data['mother_income'], 2, '.', ',');
				$this->_view->calculate['father_income'] = number_format($_data['father_income'], 2, '.', ',');
				$this->_view->calculate['mother_child_support_oblig'] = number_format($_data['mother_child_support_oblig'], 2, '.', ',');
				$this->_view->calculate['father_child_support_oblig'] = number_format($_data['father_child_support_oblig'], 2, '.', ',');
				$this->_view->calculate['mother_child_support_credits'] = number_format($_data['mother_child_support_credits'], 2, '.', ',');
				$this->_view->calculate['father_child_support_credits'] = number_format($_data['father_child_support_credits'], 2, '.', ',');
				$this->_view->calculate['mother_payment'] = number_format($_data['mother_payment'], 2, '.', ',');
				$this->_view->calculate['father_payment'] = number_format($_data['father_payment'], 2, '.', ',');
			}
		}

		// echo "<pre>";print_r($this->_view->order);exit;

		// $this->_view->cargar_img = true;

		/*if($this->_view->order['form_info']['img'] == ''){
            $this->_view->cargar_img = true;
        }else{
            // $this->_publicGestion->enviarEmail($_id, $this->_view->data['email'], $this->_view->data['nombres'], $this->_view->data['apellidos'], $this->_view->data['img']);
            $this->_view->imprimir_pdf = $this->_trabajosGestion->descargarPDF($this->_view->order['form_info']['img']);
        }

		if($_POST){
            if(validador::getPostParam('img_val')){
                $this->_trabajosGestion->agregarImg($_id, $_POST['img_val']);
                $this->redireccionar('administrador/forms/verform/' . $_id.'/'.$_id_compra);
            }
        }*/

		$this->_view->titulo = 'Quickdivorce.com - Form';

		if($this->_view->order['form_info']['item_producto'] == 'msa'){
			$this->_view->renderizar('msa','forms', 'forms');
		}else if($this->_view->order['form_info']['item_producto'] == 'ppa'){
			$this->_view->renderizar('ppa','forms', 'forms');
		}else{
			$this->_view->renderizar('msappa','forms', 'forms');
		}
		

	}

	public function generateDocs()
	{
		$this->_acl->acceso('encargado_access');

		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){	
			
				$_id = $_POST['_id'];
				$_id_compra = $_POST['_id_compra'];


				$this->_view->id_form = $_id;				
				$this->_view->condados =  $this->_trabajosGestion->traerFormsCondados();
				$this->_view->states =  $this->_trabajosGestion->traerFormsStates();
				$this->_view->order = $this->_trabajosGestion->traerOrder($_id_compra);
				$this->_view->order['forms'] =  $this->_trabajosGestion->traerFormsPorId($_id);
				$this->_view->user = $this->_trabajosGestion->traerUser($this->_view->order['forms']['id_user']);
				$this->_view->order['form_info']['id_producto'] =$this->_view->order['forms']['id_producto'];
				$this->_view->order['form_info']['item_producto'] = admin::traerProductoPorId($this->_view->order['forms']['id_producto'])->item;
				$this->_view->order['form_info']['estado'] =$this->_view->order['forms']['estado'];
				$this->_view->order['form_info']['img'] =$this->_view->order['forms']['img'];
				$this->_view->order['form_info']['fecha'] =$this->_view->order['forms']['fecha'];
				unset($this->_view->order['forms']['id'], $this->_view->order['forms']['id_compra'], $this->_view->order['forms']['id_user'], $this->_view->order['forms']['id_producto'], $this->_view->order['forms']['estado'], $this->_view->order['forms']['img'], $this->_view->order['forms']['fecha']);


				if($this->_view->order['forms']['divorce_information']!=''){
					$this->_view->order['forms']['divorce_information'] = unserialize(base64_decode($this->_view->order['forms']['divorce_information'] ));
				}

				if($this->_view->order['forms']['divorce_residency']!=''){
					$this->_view->order['forms']['divorce_residency'] = unserialize(base64_decode($this->_view->order['forms']['divorce_residency'] ));
				}

				if($this->_view->order['forms']['wife_information']!=''){
					$this->_view->order['forms']['wife_information'] = unserialize(base64_decode($this->_view->order['forms']['wife_information'] ));
				}

				if($this->_view->order['forms']['wife_identification']!=''){
					$this->_view->order['forms']['wife_identification'] = unserialize(base64_decode($this->_view->order['forms']['wife_identification'] ));
				}
				
				if($this->_view->order['forms']['wife_employer_information']!=''){
					$this->_view->order['forms']['wife_employer_information'] = unserialize(base64_decode($this->_view->order['forms']['wife_employer_information'] ));
				}

				if($this->_view->order['forms']['wife_monthly_income']!=''){
					$this->_view->order['forms']['wife_monthly_income'] = unserialize(base64_decode($this->_view->order['forms']['wife_monthly_income'] ));
				}


				if($this->_view->order['forms']['wife_monthly_average']!=''){
					$this->_view->order['forms']['wife_monthly_average'] = unserialize(base64_decode($this->_view->order['forms']['wife_monthly_average'] ));
				}


				if($this->_view->order['forms']['wife_child_care_expenses']!=''){
					$this->_view->order['forms']['wife_child_care_expenses'] = unserialize(base64_decode($this->_view->order['forms']['wife_child_care_expenses'] ));
				}

				if($this->_view->order['forms']['wife_other_child_support']!=''){
					$this->_view->order['forms']['wife_other_child_support'] = unserialize(base64_decode($this->_view->order['forms']['wife_other_child_support'] ));
				}


				if($this->_view->order['forms']['husband_personal_information']!=''){
					$this->_view->order['forms']['husband_personal_information'] = unserialize(base64_decode($this->_view->order['forms']['husband_personal_information'] ));
				}

				if($this->_view->order['forms']['husband_identification']!=''){
					$this->_view->order['forms']['husband_identification'] = unserialize(base64_decode($this->_view->order['forms']['husband_identification'] ));
				}
				
				if($this->_view->order['forms']['husband_employer_information']!=''){
					$this->_view->order['forms']['husband_employer_information'] = unserialize(base64_decode($this->_view->order['forms']['husband_employer_information'] ));
				}

				if($this->_view->order['forms']['husband_monthly_income']!=''){
					$this->_view->order['forms']['husband_monthly_income'] = unserialize(base64_decode($this->_view->order['forms']['husband_monthly_income'] ));
				}

				if($this->_view->order['forms']['husband_monthly_average']!=''){
					$this->_view->order['forms']['husband_monthly_average'] = unserialize(base64_decode($this->_view->order['forms']['husband_monthly_average'] ));
				}


				if($this->_view->order['forms']['husband_child_care_expenses']!=''){
					$this->_view->order['forms']['husband_child_care_expenses'] = unserialize(base64_decode($this->_view->order['forms']['husband_child_care_expenses'] ));
				}

				if($this->_view->order['forms']['husband_other_child_support']!=''){
					$this->_view->order['forms']['husband_other_child_support'] = unserialize(base64_decode($this->_view->order['forms']['husband_other_child_support'] ));
				}

				if($this->_view->order['forms']['child_care']!=''){
					$this->_view->order['forms']['child_care'] = unserialize(base64_decode($this->_view->order['forms']['child_care']));
				}

				if($this->_view->order['forms']['property_division']!=''){
					$this->_view->order['forms']['property_division'] = unserialize(base64_decode($this->_view->order['forms']['property_division'] ));
				}

				if($this->_view->order['forms']['debt']!=''){
					$this->_view->order['forms']['debt'] = unserialize(base64_decode($this->_view->order['forms']['debt'] ));
				}

				if($this->_view->order['forms']['spousal_support']!=''){
					$this->_view->order['forms']['spousal_support'] = unserialize(base64_decode($this->_view->order['forms']['spousal_support'] ));
				}

				if($this->_view->order['forms']['name_change']!=''){
					$this->_view->order['forms']['name_change'] = unserialize(base64_decode($this->_view->order['forms']['name_change'] ));
				}


				if($this->_view->order['forms']['children_from_the_marriage']!=''){
					$this->_view->order['forms']['children_from_the_marriage'] = unserialize(base64_decode($this->_view->order['forms']['children_from_the_marriage'] ));
				}

				if($this->_view->order['forms']['children_residence']!=''){
					$this->_view->order['forms']['children_residence'] = unserialize(base64_decode($this->_view->order['forms']['children_residence']));
				}


				if($this->_view->order['forms']['custody_and_visitation']!=''){
					$this->_view->order['forms']['custody_and_visitation'] = unserialize(base64_decode($this->_view->order['forms']['custody_and_visitation'] ));
				}

				if($this->_view->order['forms']['parenting_schedule_holidays']!=''){
					$this->_view->order['forms']['parenting_schedule_holidays'] = unserialize(base64_decode($this->_view->order['forms']['parenting_schedule_holidays'] ));
				}

				if($this->_view->order['forms']['parenting_schedule_special_occasions']!=''){
					$this->_view->order['forms']['parenting_schedule_special_occasions'] = unserialize(base64_decode($this->_view->order['forms']['parenting_schedule_special_occasions'] ));
				}

				if($this->_view->order['forms']['parenting_transportation']!=''){
					$this->_view->order['forms']['parenting_transportation'] = unserialize(base64_decode($this->_view->order['forms']['parenting_transportation'] ));
				}

				if($this->_view->order['forms']['parenting_major_decisions']!=''){
					$this->_view->order['forms']['parenting_major_decisions'] = unserialize(base64_decode($this->_view->order['forms']['parenting_major_decisions'] ));
				}

				if($this->_view->order['forms']['parenting_income_tax_exemptions']!=''){
					$this->_view->order['forms']['parenting_income_tax_exemptions'] = unserialize(base64_decode($this->_view->order['forms']['parenting_income_tax_exemptions'] ));
				}

				if($this->_view->order['forms']['child_support']!=''){
					$this->_view->order['forms']['child_support'] = unserialize(base64_decode($this->_view->order['forms']['child_support'] ));
				}

				// echo "<pre>";print_r($this->_view->order);exit;


				$_county = $this->_view->order['forms']['divorce_information']['FilingCounty'];
				$_circuit = $this->_trabajosGestion->traerFormsCircuit($_county);

				if($this->_view->order['forms']['divorce_information']['FilingSeparation'] == 'Wife'){

					$_petitioner = ucfirst($this->_view->order['forms']['wife_information']['Wife-firstname']).' '.ucfirst($this->_view->order['forms']['wife_information']['Wife-lastname']);
					$_respondent = ucfirst($this->_view->order['forms']['husband_personal_information']['husband-firstname']).' '.ucfirst($this->_view->order['forms']['husband_personal_information']['husband-lastname']);
					$_email_pet = $this->_view->order['forms']['wife_information']['Wife-email'];
					$_email_res = $this->_view->order['forms']['husband_personal_information']['husband-email'];

					$_birth_pet = $this->_view->order['forms']['wife_information']['Wife-birth'];
					$_birth_res = $this->_view->order['forms']['husband_personal_information']['husband-birth'];


					$_age_pet = admin::calcular_edad($this->_view->order['forms']['wife_information']['Wife-birth']);
					$_age_res = admin::calcular_edad($this->_view->order['forms']['husband_personal_information']['husband-birth']);


					if(isset($this->_view->order['forms']['wife_information']['Wife-apt']) && $this->_view->order['forms']['wife_information']['Wife-apt'] != ''){
						$_address_pet = $this->_view->order['forms']['wife_information']['Wife-address'].' - '.$this->_view->order['forms']['wife_information']['Wife-apt'];
					}else{
						$_address_pet = $this->_view->order['forms']['wife_information']['Wife-address'];
					}

					
					$_city_pet = $this->_view->order['forms']['wife_information']['Wife-city'].', '.$this->_view->order['forms']['wife_information']['WifeState'].', '.$this->_view->order['forms']['wife_information']['Wife-zipcode'];	
					

					if(isset($this->_view->order['forms']['husband_personal_information']['husband-apt']) && $this->_view->order['forms']['husband_personal_information']['husband-apt'] != ''){
						$_address_res = $this->_view->order['forms']['husband_personal_information']['husband-address'].' - '.$this->_view->order['forms']['husband_personal_information']['husband-apt'];
					}else{
						$_address_res = $this->_view->order['forms']['husband_personal_information']['husband-address'];
					}

					$_city_res = $this->_view->order['forms']['husband_personal_information']['husband-city'].', '.$this->_view->order['forms']['husband_personal_information']['husbandState'].', '.$this->_view->order['forms']['husband_personal_information']['husband-zipcode'];
					$_county_pet = $this->_view->order['forms']['wife_information']['Wife-County'];
					$_county_res = $this->_view->order['forms']['husband_personal_information']['husband-County'];

					$_tel_pet = $this->_view->order['forms']['wife_information']['Wife-phone'];
					$_tel_res = $this->_view->order['forms']['husband_personal_information']['husband-phone'];

					if($this->_view->order['forms']['wife_employer_information']['wife-employed'] == 'Yes'){
						$_mark1_pet = '';
						$_mark2_pet = 'X';
						$_mark3_pet = '';
						
					}else{
						$_mark1_pet = 'X';
						$_mark2_pet = '';
						$_mark3_pet = '';
					}

					$_employer_pet = $this->_view->order['forms']['wife_employer_information']['wife-EmployerEmployed'];
					$_occupation_pet = $this->_view->order['forms']['wife_employer_information']['wife-OccupationEmployed'];

					if($this->_view->order['forms']['wife_employer_information']['wife-EmployedAddress2'] != ''){
						$_address_work_pet = $this->_view->order['forms']['wife_employer_information']['wife-EmployedAddress'].' '.$this->_view->order['forms']['wife_employer_information']['wife-EmployedAddress2'];
					}else{
						$_address_work_pet = $this->_view->order['forms']['wife_employer_information']['wife-EmployedAddress'];
					}
					


					$_city_work_pet = $this->_view->order['forms']['wife_employer_information']['wife-cityEmployed'].', '.$this->_view->order['forms']['wife_employer_information']['wifeStateEmployed'].', '.$this->_view->order['forms']['wife_employer_information']['wife-zipcodeEmployed'];
					$_tel_work_pet = $this->_view->order['forms']['wife_employer_information']['wife-phoneEmployed'];

					if($this->_view->order['forms']['husband_employer_information']['husband-employed'] == 'Yes'){
						$_mark1_res = '';
						$_mark2_res = 'X';
						$_mark3_res = '';
						
					}else{
						$_mark1_res = 'X';
						$_mark2_res = '';
						$_mark3_res = '';
					}


					// Income and Deductions
					$_wageSalaries_pet = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-wageSalaries'];
					$_InterestDividend_pet = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-InterestDividend'];
					$_BusinessIncome_pet = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-BusinessIncome'];
					$_AlmonyMarriage_pet = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-AlmonyMarriage'];
					$_DisabilityBenefits_pet = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-DisabilityBenefits'];
					$_WorkersCompensation_pet = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-WorkersCompensation'];
					$_UnemployedBenefits_pet = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-UnemployedBenefits'];
					$_PensionRetirement_pet = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-PensionRetirement'];
					$_socialSecurityBenefits_pet = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-socialSecurityBenefits'];
					$_Other_pet = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-Other'];
					$_IncomeTaxes_pet = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-IncomeTaxes'];
					$_FICA_pet = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-FICA'];
					$_Medicare_pet = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-Medicare'];
					$_HealthInsurance_pet = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-HealthInsurance'];
					$_StateIndustrialInsurance_pet = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-StateIndustrialInsurance'];
					$_MandatoryUnionDues_pet = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-MandatoryUnionDues'];
					$_MandatoryPensionPlan_pet = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-MandatoryPensionPlan'];
					$_AlimonyPaidPrior_pet = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-AlimonyPaidPrior'];
					// $_NormalBusinessExpenses_pet = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-NormalBusinessExpenses'];


					$_wageSalaries_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-wageSalaries'];
					$_InterestDividend_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-InterestDividend'];
					$_BusinessIncome_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-BusinessIncome'];
					$_AlmonyMarriage_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-AlmonyMarriage'];
					$_DisabilityBenefits_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-DisabilityBenefits'];
					$_WorkersCompensation_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-WorkersCompensation'];
					$_UnemployedBenefits_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-UnemployedBenefits'];
					$_PensionRetirement_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-PensionRetirement'];
					$_socialSecurityBenefits_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-socialSecurityBenefits'];
					$_Other_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-Other'];
					$_IncomeTaxes_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-IncomeTaxes'];
					$_FICA_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-FICA'];
					$_Medicare_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-Medicare'];
					$_HealthInsurance_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-HealthInsurance'];
					$_StateIndustrialInsurance_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-StateIndustrialInsurance'];
					$_MandatoryUnionDues_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-MandatoryUnionDues'];
					$_MandatoryPensionPlan_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-MandatoryPensionPlan'];
					$_AlimonyPaidPrior_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-AlimonyPaidPrior'];
					// $_NormalBusinessExpenses_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-NormalBusinessExpenses'];


					// Avegare
					$_rentPayments_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-rentPayments'];
					$_propertyTaxes_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-propertyTaxes'];
					$_insuranceResidence_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-insuranceResidence'];
					$_condominiumMaintenance_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-condominiumMaintenance'];
					$_Electricity_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Electricity'];
					$_WaterGarbageSewer_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-WaterGarbageSewer'];
					$_Telephone_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Telephone'];
					$_FuelOilGas_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-FuelOilGas'];
					$_RepairsMaintenance_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-RepairsMaintenance'];
					$_LawnCare_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-LawnCare'];
					$_PoolMaintenance_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-PoolMaintenance'];
					$_PestControl_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-PestControl'];
					$_MiscHousehold_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-MiscHousehold'];
					$_FoodHome_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-FoodHome'];
					$_MealsOutsideHome_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-MealsOutsideHome'];
					$_MCableTV_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-CableTV'];
					$_Alarm_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Alarm'];
					$_ServiceContractsAppliances_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-ServiceContractsAppliances'];
					$_MaidService_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-MaidService'];
					$_OtherHousehold_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-OtherHousehold'];


					$_Gasoline_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Gasoline'];
					$_Repairs_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Repairs'];
					$_AutoTags_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-AutoTags'];
					$_CarInsurance_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-CarInsurance'];
					$_Payments_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Payments'];
					$_RentalReplacements_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-RentalReplacements'];
					$_AltTransportation_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-AltTransportation'];
					$_TollsParking_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-TollsParking'];
					$_CarOther_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-CarOther'];


					$_Nursery_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Nursery'];
					$_SchoolTuition_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-SchoolTuition'];
					$_SchoolSupplies_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-SchoolSupplies'];
					$_AfterSchoolActivities_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-AfterSchoolActivities'];
					$_LunchMoney_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-LunchMoney'];
					$_PrivateLessons_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-PrivateLessons'];
					$_Allowances_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Allowances'];
					$_Clothing_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Clothing'];
					$_Entertainment_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Entertainment'];
					$_HealthInsuranceChildren_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-HealthInsuranceChildren'];
					$_MedicalDental_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-MedicalDental'];
					$_Psychiatric_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Psychiatric'];
					$_Orthodontic_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Orthodontic'];
					$_Vitamins_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Vitamins'];
					$_Beauty_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Beauty'];
					$_NonprescriptionMedication_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-NonprescriptionMedication'];
					$_Cosmetics_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Cosmetics'];
					$_Gifts_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Gifts'];
					$_CampSummer_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-CampSummer'];
					$_Clubs_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Clubs'];
					$_TimeSharing_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-TimeSharing'];

					$_Miscellaneous_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Miscellaneous'];
					$_OtherAnotherRelationship_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-OtherAnotherRelationship'];

					$_HealthInsuranceBis_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-HealthInsuranceBis'];
					$_LifeInsurance_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-LifeInsurance'];
					$_DentalInsurance_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-DentalInsurance'];
					$_OtherInsurance_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-OtherInsurance'];

					$_Laundry_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Laundry'];
					$_ClothingBis_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-ClothingBis'];
					$_MedicalDentalUnreimbursed_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-MedicalDentalUnreimbursed'];
					$_PsychiatricUnreimbursed_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-PsychiatricUnreimbursed'];
					$_NonprescriptionMedicationsCosmetics_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-NonprescriptionMedicationsCosmetics'];
					$_Grooming_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Grooming'];
					$_GiftsBis_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-GiftsBis'];
					$_PetExpenses_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-PetExpenses'];
					$_ClubMembership_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-ClubMembership'];
					$_SportsHobbies_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-SportsHobbies'];
					$_EntertainmentBis_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-EntertainmentBis'];
					$_Periodicals_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Periodicals'];
					$_Vacations_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Vacations'];
					$_ReligiousOrganizations_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-ReligiousOrganizations'];
					$_BankCharges_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-BankCharges'];
					$_EducationExpenses_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-EducationExpenses'];
					$_OtherNotListed_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-OtherNotListed'];


					$_NameCreditor_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-NameCreditor'];
					$_PaymentCreditor_pet = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-PaymentCreditor'];




					$_rentPayments_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-rentPayments'];
					$_propertyTaxes_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-propertyTaxes'];
					$_insuranceResidence_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-insuranceResidence'];
					$_condominiumMaintenance_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-condominiumMaintenance'];
					$_Electricity_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Electricity'];
					$_WaterGarbageSewer_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-WaterGarbageSewer'];
					$_Telephone_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Telephone'];
					$_FuelOilGas_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-FuelOilGas'];
					$_RepairsMaintenance_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-RepairsMaintenance'];
					$_LawnCare_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-LawnCare'];
					$_PoolMaintenance_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-PoolMaintenance'];
					$_PestControl_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-PestControl'];
					$_MiscHousehold_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-MiscHousehold'];
					$_FoodHome_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-FoodHome'];
					$_MealsOutsideHome_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-MealsOutsideHome'];
					$_MCableTV_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-CableTV'];
					$_Alarm_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Alarm'];
					$_ServiceContractsAppliances_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-ServiceContractsAppliances'];
					$_MaidService_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-MaidService'];
					$_OtherHousehold_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-OtherHousehold'];


					$_Gasoline_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Gasoline'];
					$_Repairs_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Repairs'];
					$_AutoTags_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-AutoTags'];
					$_CarInsurance_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-CarInsurance'];
					$_Payments_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Payments'];
					$_RentalReplacements_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-RentalReplacements'];
					$_AltTransportation_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-AltTransportation'];
					$_TollsParking_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-TollsParking'];
					$_CarOther_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-CarOther'];


					$_Nursery_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Nursery'];
					$_SchoolTuition_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-SchoolTuition'];
					$_SchoolSupplies_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-SchoolSupplies'];
					$_AfterSchoolActivities_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-AfterSchoolActivities'];
					$_LunchMoney_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-LunchMoney'];
					$_PrivateLessons_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-PrivateLessons'];
					$_Allowances_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Allowances'];
					$_Clothing_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Clothing'];
					$_Entertainment_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Entertainment'];
					$_HealthInsuranceChildren_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-HealthInsuranceChildren'];
					$_MedicalDental_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-MedicalDental'];
					$_Psychiatric_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Psychiatric'];
					$_Orthodontic_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Orthodontic'];
					$_Vitamins_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Vitamins'];
					$_Beauty_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Beauty'];
					$_NonprescriptionMedication_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-NonprescriptionMedication'];
					$_Cosmetics_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Cosmetics'];
					$_Gifts_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Gifts'];
					$_CampSummer_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-CampSummer'];
					$_Clubs_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Clubs'];
					$_TimeSharing_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-TimeSharing'];

					$_Miscellaneous_res= $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Miscellaneous'];
					$_OtherAnotherRelationship_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-OtherAnotherRelationship'];

					$_HealthInsuranceBis_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-HealthInsuranceBis'];
					$_LifeInsurance_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-LifeInsurance'];
					$_DentalInsurance_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-DentalInsurance'];
					$_OtherInsurance_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-OtherInsurance'];

					$_Laundry_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Laundry'];
					$_ClothingBis_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-ClothingBis'];
					$_MedicalDentalUnreimbursed_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-MedicalDentalUnreimbursed'];
					$_PsychiatricUnreimbursed_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-PsychiatricUnreimbursed'];
					$_NonprescriptionMedicationsCosmetics_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-NonprescriptionMedicationsCosmetics'];
					$_Grooming_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Grooming'];
					$_GiftsBis_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-GiftsBis'];
					$_PetExpenses_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-PetExpenses'];
					$_ClubMembership_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-ClubMembership'];
					$_SportsHobbies_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-SportsHobbies'];
					$_EntertainmentBis_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-EntertainmentBis'];
					$_Periodicals_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Periodicals'];
					$_Vacations_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Vacations'];
					$_ReligiousOrganizations_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-ReligiousOrganizations'];
					$_BankCharges_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-BankCharges'];
					$_EducationExpenses_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-EducationExpenses'];
					$_OtherNotListed_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-OtherNotListed'];
					

					$_NameCreditor_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-NameCreditor'];
					$_PaymentCreditor_res = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-PaymentCreditor'];








					$_employer_res = $this->_view->order['forms']['husband_employer_information']['husband-EmployerEmployed'];
					$_occupation_res = $this->_view->order['forms']['husband_employer_information']['husband-OccupationEmployed'];
					

					if($this->_view->order['forms']['husband_employer_information']['husband-EmployedAddress2'] != ''){
						$_address_work_res = $this->_view->order['forms']['husband_employer_information']['husband-EmployedAddress'].' '.$this->_view->order['forms']['husband_employer_information']['husband-EmployedAddress2'];
					}else{
						$_address_work_res = $this->_view->order['forms']['husband_employer_information']['husband-EmployedAddress'];
					}

					$_city_work_res = $this->_view->order['forms']['husband_employer_information']['husband-cityEmployed'].', '.$this->_view->order['forms']['husband_employer_information']['husbandStateEmployed'].', '.$this->_view->order['forms']['husband_employer_information']['husband-zipcodeEmployed'];
					$_tel_work_res = $this->_view->order['forms']['husband_employer_information']['husband-phoneEmployed'];

					if(!empty($this->_view->order['forms']['spousal_support'])){

						if($this->_view->order['forms']['spousal_support']['Support-spousalSupport'] == 'No'){
							$_alimony_all = 'X';
							$_alimony_pet = '';
							$_alimony_res = '';
							$_alimony_amount = '';
							$_alimony_date = '';
							$_alimony_endate = '';
						}else{

							if($this->_view->order['forms']['spousal_support']['Support-PaySupport'] == 'Wife'){

								$_alimony_all = '';
								$_alimony_pet = 'X';
								$_alimony_res = '';
								$_alimony_amount = $this->_view->order['forms']['spousal_support']['Support-Monthly'];
								$_alimony_date = date('m/d/Y', strtotime($this->_view->order['forms']['spousal_support']['Support-PaymentDate']));
									$_alimony_endate = date("m/d/Y",strtotime($this->_view->order['forms']['spousal_support']['Support-PaymentDate']."+ ".$this->_view->order['forms']['spousal_support']['Support-Length']." month"));

								/*if($this->_view->order['form_info']['item_producto'] =='msappa'){
									$_alimony_date = date('m/d/Y', strtotime($this->_view->order['forms']['spousal_support']['dateMarriage']));
									$_alimony_endate = date("m/d/Y",strtotime($this->_view->order['forms']['spousal_support']['dateMarriage']."+ ".$this->_view->order['forms']['spousal_support']['Support-Length']." month"));
								}elseif($this->_view->order['form_info']['item_producto'] =='msa'){
									$_alimony_date = date('m/d/Y', strtotime($this->_view->order['forms']['spousal_support']['Support-PaymentDate']));
									$_alimony_endate = date("m/d/Y",strtotime($this->_view->order['forms']['spousal_support']['Support-PaymentDate']."+ ".$this->_view->order['forms']['spousal_support']['Support-Length']." month"));
								}*/
								
							}else{
								$_alimony_all = '';
								$_alimony_pet = '';
								$_alimony_res = 'X';
								$_alimony_amount = $this->_view->order['forms']['spousal_support']['Support-Monthly'];
								$_alimony_date = date('m/d/Y', strtotime($this->_view->order['forms']['spousal_support']['Support-PaymentDate']));
								$_alimony_endate = date("m/d/Y",strtotime($this->_view->order['forms']['spousal_support']['Support-PaymentDate']."+ ".$this->_view->order['forms']['spousal_support']['Support-Length']." month"));

								/*if($this->_view->order['form_info']['item_producto'] =='msappa'){
									$_alimony_date = date('m/d/Y', strtotime($this->_view->order['forms']['spousal_support']['dateMarriage']));
									$_alimony_endate = date("m/d/Y",strtotime($this->_view->order['forms']['spousal_support']['dateMarriage']."+ ".$this->_view->order['forms']['spousal_support']['Support-Length']." month"));
								}elseif($this->_view->order['form_info']['item_producto'] =='msa'){
									$_alimony_date = date('m/d/Y', strtotime($this->_view->order['forms']['spousal_support']['Support-PaymentDate']));
									$_alimony_endate = date("m/d/Y",strtotime($this->_view->order['forms']['spousal_support']['Support-PaymentDate']."+ ".$this->_view->order['forms']['spousal_support']['Support-Length']." month"));
								}*/
							}						
						}
					}else{
						$_alimony_all = '';
						$_alimony_pet = '';
						$_alimony_res = '';
						$_alimony_amount = '';
						$_alimony_date = '';
						$_alimony_endate = '';
					}

					/*if(isset($this->_view->order['forms']['child_care']) && !empty($this->_view->order['forms']['child_care'])){

						$templateProcessor->setValue('health_pet', $this->_view->order['forms']['child_care']['ChildCare-MedicalExpenses-percentMother']);
						$templateProcessor->setValue('health_res', $this->_view->order['forms']['child_care']['ChildCare-MedicalExpenses-percentFather']);

						$templateProcessor->setValue('extra_pet', $this->_view->order['forms']['child_care']['ChildCare-Extracurricular-percentMother']);
						$templateProcessor->setValue('extra_res', $this->_view->order['forms']['child_care']['ChildCare-Extracurricular-percentFather']);

					}else{
						$templateProcessor->setValue('health_pet', '');
						$templateProcessor->setValue('health_res', '');
						$templateProcessor->setValue('extra_pet', '');
						$templateProcessor->setValue('extra_res', '');
					}*/


					if(isset($this->_view->order['forms']['child_care']) && !empty($this->_view->order['forms']['child_care'])){
						
						$_health_pet = $this->_view->order['forms']['child_care']['ChildCare-MedicalExpenses-percentMother'];
						$_health_res = $this->_view->order['forms']['child_care']['ChildCare-MedicalExpenses-percentFather'];
						$_extra_pet = $this->_view->order['forms']['child_care']['ChildCare-Extracurricular-percentMother'];
						$_extra_res = $this->_view->order['forms']['child_care']['ChildCare-Extracurricular-percentFather'];
						
					}else{
						$_health_pet = '';
						$_health_res = '';
						$_extra_pet = '';
						$_extra_res = '';
					}

					
				}else{

					$_petitioner = ucfirst($this->_view->order['forms']['husband_personal_information']['husband-firstname']).' '.ucfirst($this->_view->order['forms']['husband_personal_information']['husband-lastname']);
					$_respondent = ucfirst($this->_view->order['forms']['wife_information']['Wife-firstname']).' '.ucfirst($this->_view->order['forms']['wife_information']['Wife-lastname']);
					$_email_pet = $this->_view->order['forms']['husband_personal_information']['husband-email'];
					$_email_res = $this->_view->order['forms']['wife_information']['Wife-email'];

					$_birth_res = $this->_view->order['forms']['wife_information']['Wife-birth'];
					$_birth_pet = $this->_view->order['forms']['husband_personal_information']['husband-birth'];


					$_age_res = admin::calcular_edad($this->_view->order['forms']['wife_information']['Wife-birth']);
					$_age_pet = admin::calcular_edad($this->_view->order['forms']['husband_personal_information']['husband-birth']);

					// $_address_pet = $this->_view->order['forms']['husband_personal_information']['husband-address'].' - '.$this->_view->order['forms']['husband_personal_information']['husband-apt'];

					if(isset($this->_view->order['forms']['husband_personal_information']['husband-apt']) && $this->_view->order['forms']['husband_personal_information']['husband-apt'] != ''){
						$_address_pet = $this->_view->order['forms']['husband_personal_information']['husband-address'].' - '.$this->_view->order['forms']['husband_personal_information']['husband-apt'];
					}else{
						$_address_pet = $this->_view->order['forms']['husband_personal_information']['husband-address'];
					}

					$_city_pet = $this->_view->order['forms']['husband_personal_information']['husband-city'].', '.$this->_view->order['forms']['husband_personal_information']['husbandState'].', '.$this->_view->order['forms']['husband_personal_information']['husband-zipcode'];
					// $_address_res = $this->_view->order['forms']['wife_information']['Wife-address'].' - '.$this->_view->order['forms']['wife_information']['Wife-apt'];

					if(isset($this->_view->order['forms']['wife_information']['Wife-apt']) && $this->_view->order['forms']['wife_information']['Wife-apt'] != ''){
						$_address_res = $this->_view->order['forms']['wife_information']['Wife-address'].' - '.$this->_view->order['forms']['wife_information']['Wife-apt'];
					}else{
						$_address_res = $this->_view->order['forms']['wife_information']['Wife-address'];
					}

					$_city_res = $this->_view->order['forms']['wife_information']['Wife-city'].', '.$this->_view->order['forms']['wife_information']['WifeState'].', '.$this->_view->order['forms']['wife_information']['Wife-zipcode'];	
					$_county_pet = $this->_view->order['forms']['husband_personal_information']['husband-County'];
					$_county_res = $this->_view->order['forms']['wife_information']['Wife-County'];

					$_tel_pet = $this->_view->order['forms']['husband_personal_information']['husband-phone'];
					$_tel_res = $this->_view->order['forms']['wife_information']['Wife-phone'];

					if($this->_view->order['forms']['husband_employer_information']['husband-employed'] == 'Yes'){
						$_mark1_pet = '';
						$_mark2_pet = 'X';
						$_mark3_pet = '';
						
					}else{
						$_mark1_pet = 'X';
						$_mark2_pet = '';
						$_mark3_pet = '';
					}


					



					$_employer_pet = $this->_view->order['forms']['husband_employer_information']['husband-EmployerEmployed'];
					$_occupation_pet = $this->_view->order['forms']['husband_employer_information']['husband-OccupationEmployed'];
					// $_address_work_pet = $this->_view->order['forms']['husband_employer_information']['husband-EmployedAddress'].' '.$this->_view->order['forms']['husband_employer_information']['husband-EmployedAddress2'];

					if($this->_view->order['forms']['husband_employer_information']['husband-EmployedAddress2'] != ''){
						$_address_work_pet = $this->_view->order['forms']['husband_employer_information']['husband-EmployedAddress'].' '.$this->_view->order['forms']['husband_employer_information']['husband-EmployedAddress2'];
					}else{
						$_address_work_pet = $this->_view->order['forms']['husband_employer_information']['husband-EmployedAddress'];
					}
					

					$_city_work_pet = $this->_view->order['forms']['husband_employer_information']['husband-cityEmployed'].', '.$this->_view->order['forms']['husband_employer_information']['husbandStateEmployed'].', '.$this->_view->order['forms']['husband_employer_information']['husband-zipcodeEmployed'];
					$_tel_work_pet = $this->_view->order['forms']['husband_employer_information']['husband-phoneEmployed'];

					if($this->_view->order['forms']['wife_employer_information']['wife-employed'] == 'Yes'){
						$_mark1_res = '';
						$_mark2_res = 'X';
						$_mark3_res = '';
						
					}else{
						$_mark1_res = 'X';
						$_mark2_res = '';
						$_mark3_res = '';
					}



					// Income and Deductions
					$_wageSalaries_res = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-wageSalaries'];
					$_InterestDividend_res = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-InterestDividend'];
					$_BusinessIncome_res = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-BusinessIncome'];
					$_AlmonyMarriage_res = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-AlmonyMarriage'];
					$_DisabilityBenefits_res = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-DisabilityBenefits'];
					$_WorkersCompensation_res = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-WorkersCompensation'];
					$_UnemployedBenefits_res = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-UnemployedBenefits'];
					$_PensionRetirement_res = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-PensionRetirement'];
					$_socialSecurityBenefits_res = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-socialSecurityBenefits'];
					$_Other_res = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-Other'];
					$_IncomeTaxes_res = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-IncomeTaxes'];
					$_FICA_res = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-FICA'];
					$_Medicare_res = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-Medicare'];
					$_HealthInsurance_res = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-HealthInsurance'];
					$_StateIndustrialInsurance_res = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-StateIndustrialInsurance'];
					$_MandatoryUnionDues_res = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-MandatoryUnionDues'];
					$_MandatoryPensionPlan_res = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-MandatoryPensionPlan'];
					$_AlimonyPaidPrior_res = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-AlimonyPaidPrior'];
					// $_NormalBusinessExpenses_res = $this->_view->order['forms']['wife_monthly_income']['WifeFinancial-NormalBusinessExpenses'];


					$_wageSalaries_pet = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-wageSalaries'];
					$_InterestDividend_pet = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-InterestDividend'];
					$_BusinessIncome_pet = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-BusinessIncome'];
					$_AlmonyMarriage_pet = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-AlmonyMarriage'];
					$_DisabilityBenefits_pet = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-DisabilityBenefits'];
					$_WorkersCompensation_pet = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-WorkersCompensation'];
					$_UnemployedBenefits_pet = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-UnemployedBenefits'];
					$_PensionRetirement_pet = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-PensionRetirement'];
					$_socialSecurityBenefits_pet = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-socialSecurityBenefits'];
					$_Other_pet = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-Other'];
					$_IncomeTaxes_pet = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-IncomeTaxes'];
					$_FICA_pet = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-FICA'];
					$_Medicare_pet = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-Medicare'];
					$_HealthInsurance_pet = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-HealthInsurance'];
					$_StateIndustrialInsurance_pet = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-StateIndustrialInsurance'];
					$_MandatoryUnionDues_pet = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-MandatoryUnionDues'];
					$_MandatoryPensionPlan_pet = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-MandatoryPensionPlan'];
					$_AlimonyPaidPrior_pet = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-AlimonyPaidPrior'];
					// $_NormalBusinessExpenses_pet = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-NormalBusinessExpenses'];




					// Avegare
					$_rentPayments_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-rentPayments'];
					$_propertyTaxes_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-propertyTaxes'];
					$_insuranceResidence_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-insuranceResidence'];
					$_condominiumMaintenance_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-condominiumMaintenance'];
					$_Electricity_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Electricity'];
					$_WaterGarbageSewer_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-WaterGarbageSewer'];
					$_Telephone_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Telephone'];
					$_FuelOilGas_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-FuelOilGas'];
					$_RepairsMaintenance_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-RepairsMaintenance'];
					$_LawnCare_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-LawnCare'];
					$_PoolMaintenance_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-PoolMaintenance'];
					$_PestControl_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-PestControl'];
					$_MiscHousehold_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-MiscHousehold'];
					$_FoodHome_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-FoodHome'];
					$_MealsOutsideHome_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-MealsOutsideHome'];
					$_MCableTV_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-CableTV'];
					$_Alarm_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Alarm'];
					$_ServiceContractsAppliances_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-ServiceContractsAppliances'];
					$_MaidService_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-MaidService'];
					$_OtherHousehold_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-OtherHousehold'];


					$_Gasoline_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Gasoline'];
					$_Repairs_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Repairs'];
					$_AutoTags_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-AutoTags'];
					$_CarInsurance_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-CarInsurance'];
					$_Payments_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Payments'];
					$_RentalReplacements_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-RentalReplacements'];
					$_AltTransportation_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-AltTransportation'];
					$_TollsParking_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-TollsParking'];
					$_CarOther_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-CarOther'];


					$_Nursery_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Nursery'];
					$_SchoolTuition_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-SchoolTuition'];
					$_SchoolSupplies_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-SchoolSupplies'];
					$_AfterSchoolActivities_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-AfterSchoolActivities'];
					$_LunchMoney_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-LunchMoney'];
					$_PrivateLessons_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-PrivateLessons'];
					$_Allowances_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Allowances'];
					$_Clothing_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Clothing'];
					$_Entertainment_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Entertainment'];
					$_HealthInsuranceChildren_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-HealthInsuranceChildren'];
					$_MedicalDental_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-MedicalDental'];
					$_Psychiatric_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Psychiatric'];
					$_Orthodontic_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Orthodontic'];
					$_Vitamins_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Vitamins'];
					$_Beauty_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Beauty'];
					$_NonprescriptionMedication_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-NonprescriptionMedication'];
					$_Cosmetics_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Cosmetics'];
					$_Gifts_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Gifts'];
					$_CampSummer_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-CampSummer'];
					$_Clubs_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Clubs'];
					$_TimeSharing_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-TimeSharing'];

					$_Miscellaneous_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Miscellaneous'];
					$_OtherAnotherRelationship_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-OtherAnotherRelationship'];

					$_HealthInsuranceBis_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-HealthInsuranceBis'];
					$_LifeInsurance_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-LifeInsurance'];
					$_DentalInsurance_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-DentalInsurance'];
					$_OtherInsurance_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-OtherInsurance'];

					$_Laundry_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Laundry'];
					$_ClothingBis_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-ClothingBis'];
					$_MedicalDentalUnreimbursed_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-MedicalDentalUnreimbursed'];
					$_PsychiatricUnreimbursed_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-PsychiatricUnreimbursed'];
					$_NonprescriptionMedicationsCosmetics_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-NonprescriptionMedicationsCosmetics'];
					$_Grooming_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Grooming'];
					$_GiftsBis_res= $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-GiftsBis'];
					$_PetExpenses_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-PetExpenses'];
					$_ClubMembership_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-ClubMembership'];
					$_SportsHobbies_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-SportsHobbies'];
					$_EntertainmentBis_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-EntertainmentBis'];
					$_Periodicals_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Periodicals'];
					$_Vacations_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-Vacations'];
					$_ReligiousOrganizations_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-ReligiousOrganizations'];
					$_BankCharges_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-BankCharges'];
					$_EducationExpenses_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-EducationExpenses'];
					$_OtherNotListed_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-OtherNotListed'];


					$_NameCreditor_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-NameCreditor'];
					$_PaymentCreditor_res = $this->_view->order['forms']['wife_monthly_average']['WifeFinancial-PaymentCreditor'];




					$_rentPayments_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-rentPayments'];
					$_propertyTaxes_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-propertyTaxes'];
					$_insuranceResidence_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-insuranceResidence'];
					$_condominiumMaintenance_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-condominiumMaintenance'];
					$_Electricity_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Electricity'];
					$_WaterGarbageSewer_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-WaterGarbageSewer'];
					$_Telephone_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Telephone'];
					$_FuelOilGas_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-FuelOilGas'];
					$_RepairsMaintenance_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-RepairsMaintenance'];
					$_LawnCare_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-LawnCare'];
					$_PoolMaintenance_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-PoolMaintenance'];
					$_PestControl_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-PestControl'];
					$_MiscHousehold_pet= $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-MiscHousehold'];
					$_FoodHome_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-FoodHome'];
					$_MealsOutsideHome_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-MealsOutsideHome'];
					$_MCableTV_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-CableTV'];
					$_Alarm_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Alarm'];
					$_ServiceContractsAppliances_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-ServiceContractsAppliances'];
					$_MaidService_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-MaidService'];
					$_OtherHousehold_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-OtherHousehold'];


					$_Gasoline_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Gasoline'];
					$_Repairs_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Repairs'];
					$_AutoTags_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-AutoTags'];
					$_CarInsurance_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-CarInsurance'];
					$_Payments_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Payments'];
					$_RentalReplacements_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-RentalReplacements'];
					$_AltTransportation_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-AltTransportation'];
					$_TollsParking_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-TollsParking'];
					$_CarOther_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-CarOther'];


					$_Nursery_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Nursery'];
					$_SchoolTuition_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-SchoolTuition'];
					$_SchoolSupplies_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-SchoolSupplies'];
					$_AfterSchoolActivities_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-AfterSchoolActivities'];
					$_LunchMoney_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-LunchMoney'];
					$_PrivateLessons_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-PrivateLessons'];
					$_Allowances_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Allowances'];
					$_Clothing_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Clothing'];
					$_Entertainment_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Entertainment'];
					$_HealthInsuranceChildren_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-HealthInsuranceChildren'];
					$_MedicalDental_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-MedicalDental'];
					$_Psychiatric_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Psychiatric'];
					$_Orthodontic_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Orthodontic'];
					$_Vitamins_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Vitamins'];
					$_Beauty_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Beauty'];
					$_NonprescriptionMedication_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-NonprescriptionMedication'];
					$_Cosmetics_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Cosmetics'];
					$_Gifts_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Gifts'];
					$_CampSummer_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-CampSummer'];
					$_Clubs_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Clubs'];
					$_TimeSharing_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-TimeSharing'];

					$_Miscellaneous_pet= $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Miscellaneous'];
					$_OtherAnotherRelationship_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-OtherAnotherRelationship'];

					$_HealthInsuranceBis_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-HealthInsuranceBis'];
					$_LifeInsurance_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-LifeInsurance'];
					$_DentalInsurance_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-DentalInsurance'];
					$_OtherInsurance_pet= $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-OtherInsurance'];

					$_Laundry_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Laundry'];
					$_ClothingBis_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-ClothingBis'];
					$_MedicalDentalUnreimbursed_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-MedicalDentalUnreimbursed'];
					$_PsychiatricUnreimbursed_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-PsychiatricUnreimbursed'];
					$_NonprescriptionMedicationsCosmetics_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-NonprescriptionMedicationsCosmetics'];
					$_Grooming_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Grooming'];
					$_GiftsBis_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-GiftsBis'];
					$_PetExpenses_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-PetExpenses'];
					$_ClubMembership_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-ClubMembership'];
					$_SportsHobbies_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-SportsHobbies'];
					$_EntertainmentBis_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-EntertainmentBis'];
					$_Periodicals_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Periodicals'];
					$_Vacations_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-Vacations'];
					$_ReligiousOrganizations_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-ReligiousOrganizations'];
					$_BankCharges_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-BankCharges'];
					$_EducationExpenses_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-EducationExpenses'];
					$_OtherNotListed_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-OtherNotListed'];
					

					$_NameCreditor_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-NameCreditor'];
					$_PaymentCreditor_pet = $this->_view->order['forms']['husband_monthly_average']['HusbandFinancial-PaymentCreditor'];






					$_employer_res = $this->_view->order['forms']['wife_employer_information']['wife-EmployerEmployed'];
					$_occupation_res = $this->_view->order['forms']['wife_employer_information']['wife-OccupationEmployed'];
					// $_address_work_res = $this->_view->order['forms']['wife_employer_information']['wife-EmployedAddress'].' '.$this->_view->order['forms']['wife_employer_information']['wife-EmployedAddress2'];

					if($this->_view->order['forms']['wife_employer_information']['wife-EmployedAddress2'] != ''){
						$_address_work_res = $this->_view->order['forms']['wife_employer_information']['wife-EmployedAddress'].' '.$this->_view->order['forms']['wife_employer_information']['wife-EmployedAddress2'];
					}else{
						$_address_work_res = $this->_view->order['forms']['wife_employer_information']['wife-EmployedAddress'];
					}

					$_city_work_res = $this->_view->order['forms']['wife_employer_information']['wife-cityEmployed'].', '.$this->_view->order['forms']['wife_employer_information']['wifeStateEmployed'].', '.$this->_view->order['forms']['wife_employer_information']['wife-zipcodeEmployed'];
					$_tel_work_res = $this->_view->order['forms']['wife_employer_information']['wife-phoneEmployed'];

					if(!empty($this->_view->order['forms']['spousal_support'])){

						if($this->_view->order['forms']['spousal_support']['Support-spousalSupport'] == 'No'){
							$_alimony_all = 'X';
							$_alimony_pet = '';
							$_alimony_res = '';
							$_alimony_amount = '';
							$_alimony_date = '';
							$_alimony_endate = '';
						}else {

							if($this->_view->order['forms']['spousal_support']['Support-PaySupport'] == 'Husband'){

								$_alimony_all = '';
								$_alimony_pet = '';
								$_alimony_res = 'X';
								$_alimony_amount = $this->_view->order['forms']['spousal_support']['Support-Monthly'];
								$_alimony_date = date('m/d/Y', strtotime($this->_view->order['forms']['spousal_support']['Support-PaymentDate']));
								$_alimony_endate = date("m/d/Y",strtotime($this->_view->order['forms']['spousal_support']['Support-PaymentDate']."+ ".$this->_view->order['forms']['spousal_support']['Support-Length']." months"));

								/*if($this->_view->order['form_info']['item_producto'] =='msappa'){
									$_alimony_date = date('m/d/Y', strtotime($this->_view->order['forms']['spousal_support']['dateMarriage']));
									$_alimony_endate = date("m/d/Y",strtotime($this->_view->order['forms']['spousal_support']['dateMarriage']."+ ".$this->_view->order['forms']['spousal_support']['Support-Length']." month"));
								}elseif($this->_view->order['form_info']['item_producto'] =='msa'){
									$_alimony_date = date('m/d/Y', strtotime($this->_view->order['forms']['spousal_support']['Support-PaymentDate']));
									$_alimony_endate = date("m/d/Y",strtotime($this->_view->order['forms']['spousal_support']['Support-PaymentDate']."+ ".$this->_view->order['forms']['spousal_support']['Support-Length']." month"));
								}*/

							}else{

								$_alimony_all = '';
								$_alimony_pet = 'X';
								$_alimony_res = '';
								$_alimony_amount = $this->_view->order['forms']['spousal_support']['Support-Monthly'];
								$_alimony_date = date('m/d/Y', strtotime($this->_view->order['forms']['spousal_support']['Support-PaymentDate']));
								$_alimony_endate = date("m/d/Y",strtotime($this->_view->order['forms']['spousal_support']['Support-PaymentDate']."+ ".$this->_view->order['forms']['spousal_support']['Support-Length']." month"));

								/*if($this->_view->order['form_info']['item_producto'] =='msappa'){
									$_alimony_date = date('m/d/Y', strtotime($this->_view->order['forms']['spousal_support']['dateMarriage']));
									$_alimony_endate = date("m/d/Y",strtotime($this->_view->order['forms']['spousal_support']['dateMarriage']."+ ".$this->_view->order['forms']['spousal_support']['Support-Length']." month"));
								}elseif($this->_view->order['form_info']['item_producto'] =='msa'){
									$_alimony_date = date('m/d/Y', strtotime($this->_view->order['forms']['spousal_support']['Support-PaymentDate']));
									$_alimony_endate = date("m/d/Y",strtotime($this->_view->order['forms']['spousal_support']['Support-PaymentDate']."+ ".$this->_view->order['forms']['spousal_support']['Support-Length']." month"));
								}*/
								
							}						
						}

					}else{
						$_alimony_all = '';
						$_alimony_pet = '';
						$_alimony_res = '';
						$_alimony_amount = '';
						$_alimony_date = '';
						$_alimony_endate = '';
					}


					if(isset($this->_view->order['forms']['child_care']) && !empty($this->_view->order['forms']['child_care'])){

						/*$templateProcessor->setValue('health_pet', $this->_view->order['forms']['child_care']['ChildCare-MedicalExpenses-percentFather']);
						$templateProcessor->setValue('health_res', $this->_view->order['forms']['child_care']['ChildCare-MedicalExpenses-percentMother']);

						$templateProcessor->setValue('extra_pet', $this->_view->order['forms']['child_care']['ChildCare-Extracurricular-percentFather']);
						$templateProcessor->setValue('extra_res', $this->_view->order['forms']['child_care']['ChildCare-Extracurricular-percentMother']);*/

						$_health_pet = $this->_view->order['forms']['child_care']['ChildCare-MedicalExpenses-percentFather'];
						$_health_res = $this->_view->order['forms']['child_care']['ChildCare-MedicalExpenses-percentMother'];
						$_extra_pet = $this->_view->order['forms']['child_care']['ChildCare-Extracurricular-percentFather'];
						$_extra_res = $this->_view->order['forms']['child_care']['ChildCare-Extracurricular-percentMother'];
						
					}else{
						$_health_pet = '';
						$_health_res = '';
						$_extra_pet = '';
						$_extra_res = '';
					}
							
					
				}


				

				$_ruta = $this->_conf['ruta_archivos'].'forms/'.$this->_view->order['form_info']['item_producto'].'/'.$this->_view->id_form;
				if(!file_exists($_ruta)){
					mkdir($_ruta, 0777, true);
				}

				// traer templates
				$path    = $this->_conf['ruta_archivos_templates'].$this->_view->order['form_info']['item_producto'];
				$files = scandir($path);
				$files = array_diff(scandir($path), array('.', '..'));
				sort($files);
				/*foreach($files as $file){
				  echo "<a href='$file'>$file</a>";
				}*/

				// echo "<pre>";print_r($files);exit;

				require_once  RAIZ.'vendor/autoload.php';
				// require_once '../vendor/phpoffice/phpword/bootstrap.php';

				foreach($files as $file){

					// if(!file_exists($_ruta.'/'.$file)){

						$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($path.'/'.$file);
						$templateProcessor->setValue('circuit', $_circuit);
						$templateProcessor->setValue('county', $_county);
						$templateProcessor->setValue('petitioner', $_petitioner);
						$templateProcessor->setValue('respondent', $_respondent);

						$templateProcessor->setValue('name_res', $_respondent);
						$templateProcessor->setValue('address_res', $_address_res);
						$templateProcessor->setValue('city_res', $_city_res);
						
						$templateProcessor->setValue('age_res', $_age_res.' years old');
						$templateProcessor->setValue('age_pet', $_age_pet.' years old');

						$templateProcessor->setValue('day', date('d'));
						$templateProcessor->setValue('month', date('F'));
						$templateProcessor->setValue('year', date('y'));
						$templateProcessor->setValue('year2', date('Y'));

						$templateProcessor->setValue('date', date('m/d/Y'));
						$templateProcessor->setValue('petitioner_email', $_email_pet);
						$templateProcessor->setValue('respondent_email', $_email_res);

						$templateProcessor->setValue('petitioner_county', $_county_pet);
						$templateProcessor->setValue('name_pet', $_petitioner);
						$templateProcessor->setValue('address_pet', $_address_pet);
						$templateProcessor->setValue('city_pet', $_city_pet);

						$templateProcessor->setValue('tel_pet', $_tel_pet);
						$templateProcessor->setValue('tel_res', $_tel_res);

						$templateProcessor->setValue('mark1_pet', $_mark1_pet);
						$templateProcessor->setValue('mark2_pet', $_mark2_pet);
						$templateProcessor->setValue('mark3_pet', $_mark3_pet);
						$templateProcessor->setValue('employer_pet', $_employer_pet);
						$templateProcessor->setValue('occupation_pet', $_occupation_pet);
						$templateProcessor->setValue('address_work_pet', $_address_work_pet);
						$templateProcessor->setValue('city_work_pet', $_city_work_pet);
						$templateProcessor->setValue('tel_work_pet', $_tel_work_pet);

						$templateProcessor->setValue('mark1_res', $_mark1_res);
						$templateProcessor->setValue('mark2_res', $_mark2_res);
						$templateProcessor->setValue('mark3_res', $_mark3_res);
						$templateProcessor->setValue('employer_res', $_employer_res);
						$templateProcessor->setValue('occupation_res', $_occupation_res);
						$templateProcessor->setValue('address_work_res', $_address_work_res);
						$templateProcessor->setValue('city_work_res', $_city_work_res);
						$templateProcessor->setValue('tel_work_res', $_tel_work_res);

						$templateProcessor->setValue('health_pet', $_health_pet);
						$templateProcessor->setValue('health_res', $_health_res);
						$templateProcessor->setValue('extra_pet', $_extra_pet);
						$templateProcessor->setValue('extra_res', $_extra_res);


						if($this->_view->order['forms']['divorce_information']['FilingSeparation'] == 'Wife'){
							$templateProcessor->setValue('father', $_respondent);
						}else{
							$templateProcessor->setValue('father', $_petitioner);
						}

						if(isset($this->_view->order['forms']['divorce_information']['dateMarriage'])){
							$templateProcessor->setValue('married_date', date('m/d/Y', strtotime($this->_view->order['forms']['divorce_information']['dateMarriage'])));
						}else{
							$templateProcessor->setValue('married_date', '');
						}

						
						if(isset($this->_view->order['forms']['child_support']) && !empty($this->_view->order['forms']['child_support'])){

							if($this->_view->order['forms']['child_support']['ChildSupport-Using'] == 'Yes'){

								$templateProcessor->setValue('payment_parent', $this->_view->order['forms']['child_support']['ChildSupport-MonthlyPayment-Parent']);
								$templateProcessor->setValue('payment_receiver', $this->_view->order['forms']['child_support']['ChildSupport-MonthlyPayment-Receiver']);
								$templateProcessor->setValue('payment_using', $this->_view->order['forms']['child_support']['ChildSupport-MonthlyPayment-Using']);

							}

							if($this->_view->order['forms']['child_support']['ChildSupport-Using'] == 'No'){
								

								if(isset($this->_view->order['forms']['child_support']['ChildSupport-MonthlyPayment-Takecare'])){

									$templateProcessor->setValue('payment_parent', $this->_view->order['forms']['child_support']['ChildSupport-MonthlyPayment-Takecare']);

									if($this->_view->order['forms']['child_support']['ChildSupport-MonthlyPayment-Takecare'] == 'Petitioner'){
										$templateProcessor->setValue('payment_receiver', 'Respondant');
									}else{
										$templateProcessor->setValue('payment_receiver', 'Petitioner');
									}

								}else{
									$templateProcessor->setValue('payment_parent', '');
									$templateProcessor->setValue('payment_receiver', '');
								}
								
								
								
								$templateProcessor->setValue('payment_using', $this->_view->order['forms']['child_support']['ChildSupport-MonthlyPayment']);

							}

						}

						if(isset($this->_view->order['forms']['child_care']) && !empty($this->_view->order['forms']['child_care'])){

							$templateProcessor->setValue('health_resp', $this->_view->order['forms']['child_care']['ChildCare-ChildHealthInsurance-Parent']);

						}else{
							$templateProcessor->setValue('health_resp', '');
						}
						

						$templateProcessor->setValue('alimony_all', $_alimony_all);
						$templateProcessor->setValue('alimony_pet', $_alimony_pet);
						$templateProcessor->setValue('alimony_res', $_alimony_res);
						$templateProcessor->setValue('alimony_amount', $_alimony_amount);
						$templateProcessor->setValue('alimony_date', $_alimony_date);
						$templateProcessor->setValue('alimony_endate', $_alimony_endate);



						$templateProcessor->setValue('inc_1', '$'.$_wageSalaries_pet);
						$templateProcessor->setValue('inc_2', '$0');
						$templateProcessor->setValue('inc_3', '$'.$_BusinessIncome_pet);
						$templateProcessor->setValue('inc_4', '$'.$_DisabilityBenefits_pet);
						$templateProcessor->setValue('inc_5', '$'.$_WorkersCompensation_pet);
						$templateProcessor->setValue('inc_6', '$'.$_UnemployedBenefits_pet);
						$templateProcessor->setValue('inc_7', '$'.$_PensionRetirement_pet);
						$templateProcessor->setValue('inc_8', '$'.$_socialSecurityBenefits_pet);
						$templateProcessor->setValue('inc_9', '$'.$_AlmonyMarriage_pet);
						$templateProcessor->setValue('inc_10', '$'.$_InterestDividend_pet);
						$templateProcessor->setValue('inc_11', '$0');
						$templateProcessor->setValue('inc_12', '$0');
						$templateProcessor->setValue('inc_13', '$0');
						$templateProcessor->setValue('inc_14', '$0');
						$templateProcessor->setValue('inc_15', '$'.$_Other_pet);

						$templateProcessor->setValue('ded_1', '$'.$_IncomeTaxes_pet);
						$templateProcessor->setValue('de_2', '$'.$_FICA_pet);
						$templateProcessor->setValue('de_3', '$'.$_Medicare_pet);
						$templateProcessor->setValue('ded_4', '$'.$_MandatoryUnionDues_pet);
						$templateProcessor->setValue('ded_5', '$'.$_MandatoryPensionPlan_pet);
						$templateProcessor->setValue('ded_6', '$'.$_HealthInsurance_pet);
						$templateProcessor->setValue('ded_7', '$0');
						$templateProcessor->setValue('ded_8', '$'.$_AlimonyPaidPrior_pet);

						$_arr_sum_income_pet = array($_wageSalaries_pet, 
											$_BusinessIncome_pet, 
											$_DisabilityBenefits_pet, 
											$_WorkersCompensation_pet, 
											$_UnemployedBenefits_pet, 
											$_PensionRetirement_pet, 
											$_socialSecurityBenefits_pet, 
											$_AlmonyMarriage_pet, 
											$_InterestDividend_pet,
											$_Other_pet);

						$_income_sum_pet = array_sum($_arr_sum_income_pet);
						$templateProcessor->setValue('total_inc', '$'.$_income_sum_pet);


						$_arr_sum_ded_pet = array($_IncomeTaxes_pet, 
											$_FICA_pet, 
											$_Medicare_pet, 
											$_MandatoryUnionDues_pet, 
											$_MandatoryPensionPlan_pet, 
											$_HealthInsurance_pet, 
											$_AlimonyPaidPrior_pet);

						$_ded_sum_pet = array_sum($_arr_sum_ded_pet);
						$templateProcessor->setValue('total_ded', '$'.$_ded_sum_pet);

						$_subtract_pet = $_income_sum_pet - $_ded_sum_pet;
						$templateProcessor->setValue('subtract', '$'.$_subtract_pet);



						$templateProcessor->setValue('inc_1_r', '$'.$_wageSalaries_res);
						$templateProcessor->setValue('inc_2_r', '$0');
						$templateProcessor->setValue('inc_3_r', '$'.$_BusinessIncome_res);
						$templateProcessor->setValue('inc_4_r', '$'.$_DisabilityBenefits_res);
						$templateProcessor->setValue('inc_5_r', '$'.$_WorkersCompensation_res);
						$templateProcessor->setValue('inc_6_r', '$'.$_UnemployedBenefits_res);
						$templateProcessor->setValue('inc_7_r', '$'.$_PensionRetirement_res);
						$templateProcessor->setValue('inc_8_r', '$'.$_socialSecurityBenefits_res);
						$templateProcessor->setValue('inc_9_r', '$'.$_AlmonyMarriage_res);
						$templateProcessor->setValue('inc_10_r', '$'.$_InterestDividend_res);
						$templateProcessor->setValue('inc_11_r', '$0');
						$templateProcessor->setValue('inc_12_r', '$0');
						$templateProcessor->setValue('inc_13_r', '$0');
						$templateProcessor->setValue('inc_14_r', '$0');
						$templateProcessor->setValue('inc_15_r', '$'.$_Other_res);

						$templateProcessor->setValue('ded_1_r', '$'.$_IncomeTaxes_res);
						$templateProcessor->setValue('de2r', '$'.$_FICA_res);
						$templateProcessor->setValue('de3r', '$'.$_Medicare_res);
						$templateProcessor->setValue('ded_4_r', '$'.$_MandatoryUnionDues_res);
						$templateProcessor->setValue('ded_5_r', '$'.$_MandatoryPensionPlan_res);
						$templateProcessor->setValue('ded_6_r', '$'.$_HealthInsurance_res);
						$templateProcessor->setValue('ded_7_r', '$0');
						$templateProcessor->setValue('ded_8_r', '$'.$_AlimonyPaidPrior_res);

						$_arr_sum_income_res = array($_wageSalaries_res, 
											$_BusinessIncome_res, 
											$_DisabilityBenefits_res, 
											$_WorkersCompensation_res, 
											$_UnemployedBenefits_res, 
											$_PensionRetirement_res, 
											$_socialSecurityBenefits_res, 
											$_AlmonyMarriage_res, 
											$_InterestDividend_res,
											$_Other_res);

						$_income_sum_res = array_sum($_arr_sum_income_res);
						$templateProcessor->setValue('total_inc_r', '$'.$_income_sum_res);


						$_arr_sum_ded_res = array($_IncomeTaxes_res, 
											$_FICA_res, 
											$_Medicare_res, 
											$_MandatoryUnionDues_res, 
											$_MandatoryPensionPlan_res, 
											$_HealthInsurance_res, 
											$_AlimonyPaidPrior_res);

						$_ded_sum_res = array_sum($_arr_sum_ded_res);
						$templateProcessor->setValue('total_ded_r', '$'.$_ded_sum_res);

						$_subtract_res = $_income_sum_res - $_ded_sum_res;
						$templateProcessor->setValue('subtract_r', '$'.$_subtract_res);



						// Average
						$templateProcessor->setValue('av_1', '$'.$_rentPayments_pet);
						$templateProcessor->setValue('av_2', '$'.$_propertyTaxes_pet);
						$templateProcessor->setValue('av_3', '$'.$_insuranceResidence_pet);
						$templateProcessor->setValue('av_4', '$'.$_condominiumMaintenance_pet);
						$templateProcessor->setValue('av_5', '$'.$_Electricity_pet);
						$templateProcessor->setValue('av_6', '$'.$_WaterGarbageSewer_pet);
						$templateProcessor->setValue('av_7', '$'.$_Telephone_pet);
						$templateProcessor->setValue('av_8', '$'.$_FuelOilGas_pet);
						$templateProcessor->setValue('av_9', '$'.$_RepairsMaintenance_pet);
						$templateProcessor->setValue('av_10', '$'.$_LawnCare_pet);
						$templateProcessor->setValue('av_11', '$'.$_PoolMaintenance_pet);
						$templateProcessor->setValue('av_12', '$'.$_PestControl_pet);
						$templateProcessor->setValue('av_13', '$'.$_MiscHousehold_pet);
						$templateProcessor->setValue('av_14', '$'.$_FoodHome_pet);
						$templateProcessor->setValue('av_15', '$'.$_MealsOutsideHome_pet);
						$templateProcessor->setValue('av_16', '$'.$_MCableTV_pet);
						$templateProcessor->setValue('av_17', '$'.$_Alarm_pet);
						$templateProcessor->setValue('av_18', '$'.$_ServiceContractsAppliances_pet);
						$templateProcessor->setValue('av_19', '$'.$_MaidService_pet);
						

						$_tot_OtherHousehold_pet = 0;
						if(!empty($_OtherHousehold_pet)){


							$table_OtherHousehold_pet = new \PhpOffice\PhpWord\Element\Table(array(
																					'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::AUTO,  
																					// 'width' => 100 * 30,
																					// 'borderColor' =>'000000',
    																				// 'borderSize' => 2,
																				));

							$fontStyle = array('bold' => true,'name' => 'Calibri', 'size' => 12);
							$fontStyle2 = array('name' => 'Calibri', 'size' => 12);
						    $styleCell = array('align' => 'left');
																			    
						    
						    for ($i=0; $i <count($_OtherHousehold_pet); $i++) {
							
								$table_OtherHousehold_pet->addRow();
								$table_OtherHousehold_pet->addCell(8000)->addText('$'.$_OtherHousehold_pet[$i], $fontStyle2, $styleCell);
								$_tot_OtherHousehold_pet = $_tot_OtherHousehold_pet + $_OtherHousehold_pet[$i];
							}
						   					


							$templateProcessor->setComplexBlock('{av_20}', $table_OtherHousehold_pet);

						}else{
							// $_OtherHousehold_pet = 0;
							$templateProcessor->setValue('av_20', '$0');
						}



						$_arr_household_pet = array($_rentPayments_pet,
													$_propertyTaxes_pet,
													$_insuranceResidence_pet,
													$_condominiumMaintenance_pet,
													$_Electricity_pet,
													$_WaterGarbageSewer_pet,
													$_Telephone_pet,
													$_FuelOilGas_pet,
													$_RepairsMaintenance_pet,
													$_LawnCare_pet,
													$_PoolMaintenance_pet,
													$_PestControl_pet,
													$_MiscHousehold_pet,
													$_FoodHome_pet,
													$_MealsOutsideHome_pet,
													$_MCableTV_pet,
													$_Alarm_pet,
													$_ServiceContractsAppliances_pet,
													$_MaidService_pet,
													$_tot_OtherHousehold_pet);

						$_sum_household_pet = array_sum($_arr_household_pet);
						$templateProcessor->setValue('total_av', '$'.$_sum_household_pet);


						$templateProcessor->setValue('av_21', '$'.$_Gasoline_pet);
						$templateProcessor->setValue('av_22', '$'.$_Repairs_pet);
						$templateProcessor->setValue('av_23', '$'.$_AutoTags_pet);
						$templateProcessor->setValue('av_24', '$'.$_CarInsurance_pet);
						$templateProcessor->setValue('av_25', '$'.$_Payments_pet);
						$templateProcessor->setValue('av_26', '$'.$_RentalReplacements_pet);
						$templateProcessor->setValue('av_27', '$'.$_AltTransportation_pet);
						$templateProcessor->setValue('av_28', '$'.$_TollsParking_pet);
						$templateProcessor->setValue('av_29', '$'.$_CarOther_pet);


						$_arr_automobile_pet = array($_Gasoline_pet,
													$_Repairs_pet,
													$_AutoTags_pet,
													$_CarInsurance_pet,
													$_Payments_pet,
													$_RentalReplacements_pet,
													$_AltTransportation_pet,
													$_TollsParking_pet,
													$_CarOther_pet);

						$_sum_automobile_pet = array_sum($_arr_automobile_pet);
						$templateProcessor->setValue('total_auto', '$'.$_sum_automobile_pet);


						$templateProcessor->setValue('av_30', '$'.$_Nursery_pet);
						$templateProcessor->setValue('av_31', '$'.$_SchoolTuition_pet);
						$templateProcessor->setValue('av_32', '$'.$_SchoolSupplies_pet);
						$templateProcessor->setValue('av_33', '$'.$_AfterSchoolActivities_pet);
						$templateProcessor->setValue('av_34', '$'.$_LunchMoney_pet);
						$templateProcessor->setValue('av_35', '$'.$_PrivateLessons_pet);
						$templateProcessor->setValue('av_36', '$'.$_Allowances_pet);
						$templateProcessor->setValue('av_37', '$'.$_Clothing_pet);
						$templateProcessor->setValue('av_38', '$'.$_Entertainment_pet);
						$templateProcessor->setValue('av_39', '$'.$_HealthInsuranceChildren_pet);
						$templateProcessor->setValue('av_40', '$'.$_MedicalDental_pet);
						$templateProcessor->setValue('av_41', '$'.$_Psychiatric_pet);
						$templateProcessor->setValue('av_42', '$'.$_Orthodontic_pet);
						$templateProcessor->setValue('av_43', '$'.$_Vitamins_pet);
						$templateProcessor->setValue('av_44', '$'.$_Beauty_pet);
						$templateProcessor->setValue('av_45', '$'.$_NonprescriptionMedication_pet);
						$templateProcessor->setValue('av_46', '$'.$_Cosmetics_pet);
						$templateProcessor->setValue('av_47', '$'.$_Gifts_pet);
						$templateProcessor->setValue('av_48', '$'.$_CampSummer_pet);
						$templateProcessor->setValue('av_49', '$'.$_Clubs_pet);
						$templateProcessor->setValue('av_50', '$'.$_TimeSharing_pet);
						$templateProcessor->setValue('av_51', '$'.$_Miscellaneous_pet);


						$_arr_children_pet = array($_Nursery_pet,
													$_SchoolTuition_pet,
													$_SchoolSupplies_pet,
													$_AfterSchoolActivities_pet,
													$_LunchMoney_pet,
													$_PrivateLessons_pet,
													$_Allowances_pet,
													$_Clothing_pet,
													$_Entertainment_pet,
													$_HealthInsuranceChildren_pet,
													$_MedicalDental_pet,
													$_Psychiatric_pet,
													$_Orthodontic_pet,
													$_Vitamins_pet,
													$_Beauty_pet,
													$_NonprescriptionMedication_pet,
													$_Cosmetics_pet,
													$_Gifts_pet,
													$_CampSummer_pet,
													$_Clubs_pet,
													$_TimeSharing_pet,
													$_Miscellaneous_pet);

						$_sum_children_pet = array_sum($_arr_children_pet);
						$templateProcessor->setValue('total_chi', '$'.$_sum_children_pet);

						$_tot_OtherAnotherRelationship_pet = 0;
						if(!empty($_OtherAnotherRelationship_pet)){


							$table_OtherAnotherRelationship_pet = new \PhpOffice\PhpWord\Element\Table(array(
																					'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::AUTO,  
																					// 'width' => 100 * 30,
																					// 'borderColor' =>'000000',
    																				// 'borderSize' => 2,
																				));

							$fontStyle = array('bold' => true,'name' => 'Calibri', 'size' => 12);
							$fontStyle2 = array('name' => 'Calibri', 'size' => 12);
						    $styleCell = array('align' => 'left');
						
						    for ($i=0; $i <count($_OtherAnotherRelationship_pet); $i++) {

							
								$table_OtherAnotherRelationship_pet->addRow();
								$table_OtherAnotherRelationship_pet->addCell(8000)->addText('$'.$_OtherAnotherRelationship_pet[$i], $fontStyle2, $styleCell);	
								$_tot_OtherAnotherRelationship_pet = $_tot_OtherAnotherRelationship_pet + $_OtherAnotherRelationship_pet[$i];							
							}					   					


							$templateProcessor->setComplexBlock('{av_52}', $table_OtherAnotherRelationship_pet);

						}else{
							$templateProcessor->setValue('av_52', '$0');
						}

						$templateProcessor->setValue('total_another', '$'.$_tot_OtherAnotherRelationship_pet);




						$templateProcessor->setValue('av_53', '$'.$_HealthInsuranceBis_pet);
						$templateProcessor->setValue('av_54', '$'.$_LifeInsurance_pet);
						$templateProcessor->setValue('av_55', '$'.$_DentalInsurance_pet);


						$_tot_OtherInsurance_pet = 0;
						if(!empty($_OtherInsurance_pet)){


							$table_OtherInsurance_pet = new \PhpOffice\PhpWord\Element\Table(array(
																					'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::AUTO,  
																					// 'width' => 100 * 30,
																					// 'borderColor' =>'000000',
    																				// 'borderSize' => 2,
																				));

							$fontStyle = array('bold' => true,'name' => 'Calibri', 'size' => 12);
							$fontStyle2 = array('name' => 'Calibri', 'size' => 12);
						    $styleCell = array('align' => 'left');
						
						    for ($i=0; $i <count($_OtherInsurance_pet); $i++) {

							
								$table_OtherInsurance_pet->addRow();
								$table_OtherInsurance_pet->addCell(8000)->addText('$'.$_OtherInsurance_pet[$i], $fontStyle2, $styleCell);		
								$_tot_OtherInsurance_pet = $_tot_OtherInsurance_pet + $_OtherInsurance_pet[$i];						
							}					   					


							$templateProcessor->setComplexBlock('{av_56}', $table_OtherInsurance_pet);

						}else{
							$templateProcessor->setValue('av_56', '$0');
						}

						$_sum_insurance_pet = $_DentalInsurance_pet + $_tot_OtherInsurance_pet;
						$templateProcessor->setValue('total_ins', '$'.$_sum_insurance_pet);




						$templateProcessor->setValue('av_57', '$'.$_Laundry_pet);
						$templateProcessor->setValue('av_58', '$'.$_ClothingBis_pet);
						$templateProcessor->setValue('av_59', '$'.$_MedicalDentalUnreimbursed_pet);
						$templateProcessor->setValue('av_60', '$'.$_PsychiatricUnreimbursed_pet);
						$templateProcessor->setValue('av_61', '$'.$_NonprescriptionMedicationsCosmetics_pet);
						$templateProcessor->setValue('av_62', '$'.$_Grooming_pet);
						$templateProcessor->setValue('av_63', '$'.$_GiftsBis_pet);
						$templateProcessor->setValue('av_64', '$'.$_PetExpenses_pet);
						$templateProcessor->setValue('av_65', '$'.$_ClubMembership_pet);
						$templateProcessor->setValue('av_66', '$'.$_SportsHobbies_pet);
						$templateProcessor->setValue('av_67', '$'.$_EntertainmentBis_pet);
						$templateProcessor->setValue('av_68', '$'.$_Periodicals_pet);
						$templateProcessor->setValue('av_69', '$'.$_Vacations_pet);
						$templateProcessor->setValue('av_70', '$'.$_ReligiousOrganizations_pet);
						$templateProcessor->setValue('av_71', '$'.$_BankCharges_pet);
						$templateProcessor->setValue('av_72', '$'.$_EducationExpenses_pet);


						$_tot_OtherNotListed_pet = 0;
						if(!empty($_OtherNotListed_pet)){


							$table_OtherNotListed_pet = new \PhpOffice\PhpWord\Element\Table(array(
																					'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::AUTO,  
																					// 'width' => 100 * 30,
																					// 'borderColor' =>'000000',
    																				// 'borderSize' => 2,
																				));

							$fontStyle = array('bold' => true,'name' => 'Calibri', 'size' => 12);
							$fontStyle2 = array('name' => 'Calibri', 'size' => 12);
						    $styleCell = array('align' => 'left');
						
						    for ($i=0; $i <count($_OtherNotListed_pet); $i++) {

							
								$table_OtherNotListed_pet->addRow();
								$table_OtherNotListed_pet->addCell(8000)->addText('$'.$_OtherNotListed_pet[$i], $fontStyle2, $styleCell);
								$_tot_OtherNotListed_pet = $_tot_OtherNotListed_pet  + $_OtherNotListed_pet[$i];								
							}					   					


							$templateProcessor->setComplexBlock('{av_73}', $table_OtherNotListed_pet);

						}else{
							$templateProcessor->setValue('av_73', '$0');
						}


						$_arr_other_pet = array($_Laundry_pet,
												$_ClothingBis_pet,
												$_MedicalDentalUnreimbursed_pet,
												$_PsychiatricUnreimbursed_pet,
												$_NonprescriptionMedicationsCosmetics_pet,
												$_Grooming_pet,
												$_GiftsBis_pet,
												$_PetExpenses_pet,
												$_ClubMembership_pet,
												$_SportsHobbies_pet,
												$_EntertainmentBis_pet,
												$_Periodicals_pet,
												$_Vacations_pet,
												$_ReligiousOrganizations_pet,
												$_BankCharges_pet,
												$_EducationExpenses_pet,
												$_tot_OtherNotListed_pet);

						$_sum_other_pet = array_sum($_arr_other_pet);
						$templateProcessor->setValue('total_other', '$'.$_sum_other_pet);




						$_tot_NameCreditor_pet = 0;
						if(!empty($_NameCreditor_pet)){


							$table_NameCreditor_pet = new \PhpOffice\PhpWord\Element\Table(array(
																					'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT,  
																					'width' => 100 * 50,
																					// 'borderColor' =>'000000',
    																				// 'borderSize' => 2,
																				));

							$fontStyle = array('bold' => true,'name' => 'Calibri', 'size' => 12);
							$fontStyle2 = array('name' => 'Calibri', 'size' => 12);
						    $styleCell = array('align' => 'left');

						    $table_NameCreditor_pet->addRow();
							$table_NameCreditor_pet->addCell(8000)->addText('Name of Creditor', $fontStyle2, $styleCell);
							$table_NameCreditor_pet->addCell(8000)->addText('Payment', $fontStyle2, $styleCell);
						
						    for ($i=0; $i <count($_NameCreditor_pet); $i++) {

							
								$table_NameCreditor_pet->addRow();
								$table_NameCreditor_pet->addCell(8000)->addText($_NameCreditor_pet[$i], $fontStyle2, $styleCell);	
								$table_NameCreditor_pet->addCell(8000)->addText('$'.$_PaymentCreditor_pet[$i], $fontStyle2, $styleCell);	
								$_tot_NameCreditor_pet = $_tot_NameCreditor_pet + $_PaymentCreditor_pet[$i];							
							}					   					


							$templateProcessor->setComplexBlock('{av_74}', $table_NameCreditor_pet);

						}else{
							$templateProcessor->setValue('av_74', '$0');
						}

						$templateProcessor->setValue('av_subtotal', '$'.$_tot_NameCreditor_pet);

						$_arr_av_total_pet = array($_sum_household_pet,
												$_sum_automobile_pet,
												$_sum_children_pet,
												$_tot_OtherAnotherRelationship_pet,
												$_sum_insurance_pet,
												$_sum_other_pet);

						$_sum_av_total_pet = array_sum($_arr_av_total_pet);
						$templateProcessor->setValue('av_total', '$'.$_sum_av_total_pet);
						$templateProcessor->setValue('av_income', '$'.$_subtract_pet);
						$templateProcessor->setValue('av_exp', '$'.$_sum_av_total_pet);

						if($_subtract_pet > $_sum_av_total_pet){
							$_av_surplus_pet = $_subtract_pet - $_sum_av_total_pet;
							$_av_def_pet = 0;
						}
						if($_sum_av_total_pet > $_subtract_pet){
							$_av_def_pet = $_sum_av_total_pet - $_subtract_pet;
							$_av_surplus_pet = 0;
						}

						$templateProcessor->setValue('av_surplus', '$'.$_av_surplus_pet);
						$templateProcessor->setValue('av_def', '$'.$_av_def_pet);




















						// Average
						$templateProcessor->setValue('av_1_r', '$'.$_rentPayments_res);
						$templateProcessor->setValue('av_2_r', '$'.$_propertyTaxes_res);
						$templateProcessor->setValue('av_3_r', '$'.$_insuranceResidence_res);
						$templateProcessor->setValue('av_4_r', '$'.$_condominiumMaintenance_res);
						$templateProcessor->setValue('av_5_r', '$'.$_Electricity_res);
						$templateProcessor->setValue('av_6_r', '$'.$_WaterGarbageSewer_res);
						$templateProcessor->setValue('av_7_r', '$'.$_Telephone_res);
						$templateProcessor->setValue('av_8_r', '$'.$_FuelOilGas_res);
						$templateProcessor->setValue('av_9_r', '$'.$_RepairsMaintenance_res);
						$templateProcessor->setValue('av_10_r', '$'.$_LawnCare_res);
						$templateProcessor->setValue('av_11_r', '$'.$_PoolMaintenance_res);
						$templateProcessor->setValue('av_12_r', '$'.$_PestControl_res);
						$templateProcessor->setValue('av_13_r', '$'.$_MiscHousehold_res);
						$templateProcessor->setValue('av_14_r', '$'.$_FoodHome_res);
						$templateProcessor->setValue('av_15_r', '$'.$_MealsOutsideHome_res);
						$templateProcessor->setValue('av_16_r', '$'.$_MCableTV_res);
						$templateProcessor->setValue('av_17_r', '$'.$_Alarm_res);
						$templateProcessor->setValue('av_18_r', '$'.$_ServiceContractsAppliances_res);
						$templateProcessor->setValue('av_19_r', '$'.$_MaidService_res);
						

						$_tot_OtherHousehold_res = 0;
						if(!empty($_OtherHousehold_res)){


							$table_OtherHousehold_res = new \PhpOffice\PhpWord\Element\Table(array(
																					'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::AUTO,  
																					// 'width' => 100 * 30,
																					// 'borderColor' =>'000000',
    																				// 'borderSize' => 2,
																				));

							$fontStyle = array('bold' => true,'name' => 'Calibri', 'size' => 12);
							$fontStyle2 = array('name' => 'Calibri', 'size' => 12);
						    $styleCell = array('align' => 'left');
																			    
						    
						    for ($i=0; $i <count($_OtherHousehold_res); $i++) {
							
								$table_OtherHousehold_res->addRow();
								$table_OtherHousehold_res->addCell(8000)->addText('$'.$_OtherHousehold_res[$i], $fontStyle2, $styleCell);
								$_tot_OtherHousehold_res = $_tot_OtherHousehold_res + $_OtherHousehold_res[$i];
							}
						   					


							$templateProcessor->setComplexBlock('{av_20_r}', $table_OtherHousehold_res);

						}else{
							// $_OtherHousehold_pet = 0;
							$templateProcessor->setValue('av_20_r', '$0');
						}



						$_arr_household_res = array($_rentPayments_res,
													$_propertyTaxes_res,
													$_insuranceResidence_res,
													$_condominiumMaintenance_res,
													$_Electricity_res,
													$_WaterGarbageSewer_res,
													$_Telephone_res,
													$_FuelOilGas_res,
													$_RepairsMaintenance_res,
													$_LawnCare_res,
													$_PoolMaintenance_res,
													$_PestControl_res,
													$_MiscHousehold_res,
													$_FoodHome_res,
													$_MealsOutsideHome_res,
													$_MCableTV_res,
													$_Alarm_res,
													$_ServiceContractsAppliances_res,
													$_MaidService_res,
													$_tot_OtherHousehold_res);

						$_sum_household_res = array_sum($_arr_household_res);
						$templateProcessor->setValue('total_av_r', '$'.$_sum_household_res);


						$templateProcessor->setValue('av_21_r', '$'.$_Gasoline_res);
						$templateProcessor->setValue('av_22_r', '$'.$_Repairs_res);
						$templateProcessor->setValue('av_23_r', '$'.$_AutoTags_res);
						$templateProcessor->setValue('av_24_r', '$'.$_CarInsurance_res);
						$templateProcessor->setValue('av_25_r', '$'.$_Payments_res);
						$templateProcessor->setValue('av_26_r', '$'.$_RentalReplacements_res);
						$templateProcessor->setValue('av_27_r', '$'.$_AltTransportation_res);
						$templateProcessor->setValue('av_28_r', '$'.$_TollsParking_res);
						$templateProcessor->setValue('av_29_r', '$'.$_CarOther_res);


						$_arr_automobile_res = array($_Gasoline_res,
													$_Repairs_res,
													$_AutoTags_res,
													$_CarInsurance_res,
													$_Payments_res,
													$_RentalReplacements_res,
													$_AltTransportation_res,
													$_TollsParking_res,
													$_CarOther_res);

						$_sum_automobile_res = array_sum($_arr_automobile_res);
						$templateProcessor->setValue('total_auto_r', '$'.$_sum_automobile_res);


						$templateProcessor->setValue('av_30_r', '$'.$_Nursery_res);
						$templateProcessor->setValue('av_31_r', '$'.$_SchoolTuition_res);
						$templateProcessor->setValue('av_32_r', '$'.$_SchoolSupplies_res);
						$templateProcessor->setValue('av_33_r', '$'.$_AfterSchoolActivities_res);
						$templateProcessor->setValue('av_34_r', '$'.$_LunchMoney_res);
						$templateProcessor->setValue('av_35_r', '$'.$_PrivateLessons_res);
						$templateProcessor->setValue('av_36_r', '$'.$_Allowances_res);
						$templateProcessor->setValue('av_37_r', '$'.$_Clothing_res);
						$templateProcessor->setValue('av_38_r', '$'.$_Entertainment_res);
						$templateProcessor->setValue('av_39_r', '$'.$_HealthInsuranceChildren_res);
						$templateProcessor->setValue('av_40_r', '$'.$_MedicalDental_res);
						$templateProcessor->setValue('av_41_r', '$'.$_Psychiatric_res);
						$templateProcessor->setValue('av_42_r', '$'.$_Orthodontic_res);
						$templateProcessor->setValue('av_43_r', '$'.$_Vitamins_res);
						$templateProcessor->setValue('av_44_r', '$'.$_Beauty_res);
						$templateProcessor->setValue('av_45_r', '$'.$_NonprescriptionMedication_res);
						$templateProcessor->setValue('av_46_r', '$'.$_Cosmetics_res);
						$templateProcessor->setValue('av_47_r', '$'.$_Gifts_res);
						$templateProcessor->setValue('av_48_r', '$'.$_CampSummer_res);
						$templateProcessor->setValue('av_49_r', '$'.$_Clubs_res);
						$templateProcessor->setValue('av_50_r', '$'.$_TimeSharing_res);
						$templateProcessor->setValue('av_51_r', '$'.$_Miscellaneous_res);


						$_arr_children_res = array($_Nursery_res,
													$_SchoolTuition_res,
													$_SchoolSupplies_res,
													$_AfterSchoolActivities_res,
													$_LunchMoney_res,
													$_PrivateLessons_res,
													$_Allowances_res,
													$_Clothing_res,
													$_Entertainment_res,
													$_HealthInsuranceChildren_res,
													$_MedicalDental_res,
													$_Psychiatric_res,
													$_Orthodontic_res,
													$_Vitamins_res,
													$_Beauty_res,
													$_NonprescriptionMedication_res,
													$_Cosmetics_res,
													$_Gifts_res,
													$_CampSummer_res,
													$_Clubs_res,
													$_TimeSharing_res,
													$_Miscellaneous_res);

						$_sum_children_res = array_sum($_arr_children_res);
						$templateProcessor->setValue('total_chi_r', '$'.$_sum_children_res);

						$_tot_OtherAnotherRelationship_res = 0;
						if(!empty($_OtherAnotherRelationship_res)){


							$table_OtherAnotherRelationship_res = new \PhpOffice\PhpWord\Element\Table(array(
																					'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::AUTO,  
																					// 'width' => 100 * 30,
																					// 'borderColor' =>'000000',
    																				// 'borderSize' => 2,
																				));

							$fontStyle = array('bold' => true,'name' => 'Calibri', 'size' => 12);
							$fontStyle2 = array('name' => 'Calibri', 'size' => 12);
						    $styleCell = array('align' => 'left');
						
						    for ($i=0; $i <count($_OtherAnotherRelationship_res); $i++) {

							
								$table_OtherAnotherRelationship_res->addRow();
								$table_OtherAnotherRelationship_res->addCell(8000)->addText('$'.$_OtherAnotherRelationship_res[$i], $fontStyle2, $styleCell);	
								$_tot_OtherAnotherRelationship_res = $_tot_OtherAnotherRelationship_res + $_OtherAnotherRelationship_res[$i];							
							}					   					


							$templateProcessor->setComplexBlock('{av_52_r}', $table_OtherAnotherRelationship_res);

						}else{
							$templateProcessor->setValue('av_52_r', '$0');
						}

						$templateProcessor->setValue('total_another_r', '$'.$_tot_OtherAnotherRelationship_res);




						$templateProcessor->setValue('av_53_r', '$'.$_HealthInsuranceBis_res);
						$templateProcessor->setValue('av_54_r', '$'.$_LifeInsurance_res);
						$templateProcessor->setValue('av_55_r', '$'.$_DentalInsurance_res);


						$_tot_OtherInsurance_res = 0;
						if(!empty($_OtherInsurance_res)){


							$table_OtherInsurance_res = new \PhpOffice\PhpWord\Element\Table(array(
																					'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::AUTO,  
																					// 'width' => 100 * 30,
																					// 'borderColor' =>'000000',
    																				// 'borderSize' => 2,
																				));

							$fontStyle = array('bold' => true,'name' => 'Calibri', 'size' => 12);
							$fontStyle2 = array('name' => 'Calibri', 'size' => 12);
						    $styleCell = array('align' => 'left');
						
						    for ($i=0; $i <count($_OtherInsurance_res); $i++) {

							
								$table_OtherInsurance_res->addRow();
								$table_OtherInsurance_res->addCell(8000)->addText('$'.$_OtherInsurance_res[$i], $fontStyle2, $styleCell);		
								$_tot_OtherInsurance_res = $_tot_OtherInsurance_res + $_OtherInsurance_res[$i];						
							}					   					


							$templateProcessor->setComplexBlock('{av_56_r}', $table_OtherInsurance_res);

						}else{
							$templateProcessor->setValue('av_56_r', '$0');
						}

						$_sum_insurance_res = $_DentalInsurance_res + $_tot_OtherInsurance_res;
						$templateProcessor->setValue('total_ins_r', '$'.$_sum_insurance_res);




						$templateProcessor->setValue('av_57_r', '$'.$_Laundry_res);
						$templateProcessor->setValue('av_58_r', '$'.$_ClothingBis_res);
						$templateProcessor->setValue('av_59_r', '$'.$_MedicalDentalUnreimbursed_res);
						$templateProcessor->setValue('av_60_r', '$'.$_PsychiatricUnreimbursed_res);
						$templateProcessor->setValue('av_61_r', '$'.$_NonprescriptionMedicationsCosmetics_res);
						$templateProcessor->setValue('av_62_r', '$'.$_Grooming_res);
						$templateProcessor->setValue('av_63_r', '$'.$_GiftsBis_res);
						$templateProcessor->setValue('av_64_r', '$'.$_PetExpenses_res);
						$templateProcessor->setValue('av_65_r', '$'.$_ClubMembership_res);
						$templateProcessor->setValue('av_66_r', '$'.$_SportsHobbies_res);
						$templateProcessor->setValue('av_67_r', '$'.$_EntertainmentBis_res);
						$templateProcessor->setValue('av_68_r', '$'.$_Periodicals_res);
						$templateProcessor->setValue('av_69_r', '$'.$_Vacations_res);
						$templateProcessor->setValue('av_70_r', '$'.$_ReligiousOrganizations_res);
						$templateProcessor->setValue('av_71_r', '$'.$_BankCharges_res);
						$templateProcessor->setValue('av_72_r', '$'.$_EducationExpenses_res);


						$_tot_OtherNotListed_res = 0;
						if(!empty($_OtherNotListed_res)){


							$table_OtherNotListed_res = new \PhpOffice\PhpWord\Element\Table(array(
																					'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::AUTO,  
																					// 'width' => 100 * 30,
																					// 'borderColor' =>'000000',
    																				// 'borderSize' => 2,
																				));

							$fontStyle = array('bold' => true,'name' => 'Calibri', 'size' => 12);
							$fontStyle2 = array('name' => 'Calibri', 'size' => 12);
						    $styleCell = array('align' => 'left');
						
						    for ($i=0; $i <count($_OtherNotListed_res); $i++) {

							
								$table_OtherNotListed_res->addRow();
								$table_OtherNotListed_res->addCell(8000)->addText('$'.$_OtherNotListed_res[$i], $fontStyle2, $styleCell);
								$_tot_OtherNotListed_res = $_tot_OtherNotListed_res  + $_OtherNotListed_res[$i];								
							}					   					


							$templateProcessor->setComplexBlock('{av_73_r}', $table_OtherNotListed_res);

						}else{
							$templateProcessor->setValue('av_73_r', '$0');
						}


						$_arr_other_res = array($_Laundry_res,
												$_ClothingBis_res,
												$_MedicalDentalUnreimbursed_res,
												$_PsychiatricUnreimbursed_res,
												$_NonprescriptionMedicationsCosmetics_res,
												$_Grooming_res,
												$_GiftsBis_res,
												$_PetExpenses_res,
												$_ClubMembership_res,
												$_SportsHobbies_res,
												$_EntertainmentBis_res,
												$_Periodicals_res,
												$_Vacations_res,
												$_ReligiousOrganizations_res,
												$_BankCharges_res,
												$_EducationExpenses_res,
												$_tot_OtherNotListed_res);

						$_sum_other_res = array_sum($_arr_other_res);
						$templateProcessor->setValue('total_other_r', '$'.$_sum_other_res);




						$_tot_NameCreditor_res = 0;
						if(!empty($_NameCreditor_res)){


							$table_NameCreditor_res = new \PhpOffice\PhpWord\Element\Table(array(
																					'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT,  
																					'width' => 100 * 50,
																					// 'borderColor' =>'000000',
    																				// 'borderSize' => 2,
																				));

							$fontStyle = array('bold' => true,'name' => 'Calibri', 'size' => 12);
							$fontStyle2 = array('name' => 'Calibri', 'size' => 12);
						    $styleCell = array('align' => 'left');

						    $table_NameCreditor_res->addRow();
							$table_NameCreditor_res->addCell(8000)->addText('Name of Creditor', $fontStyle2, $styleCell);
							$table_NameCreditor_res->addCell(8000)->addText('Payment', $fontStyle2, $styleCell);
						
						    for ($i=0; $i <count($_NameCreditor_res); $i++) {

							
								$table_NameCreditor_res->addRow();
								$table_NameCreditor_res->addCell(8000)->addText($_NameCreditor_res[$i], $fontStyle2, $styleCell);	
								$table_NameCreditor_res->addCell(8000)->addText('$'.$_PaymentCreditor_res[$i], $fontStyle2, $styleCell);	
								$_tot_NameCreditor_res = $_tot_NameCreditor_res + $_PaymentCreditor_res[$i];							
							}					   					


							$templateProcessor->setComplexBlock('{av_74_r}', $table_NameCreditor_res);

						}else{
							$templateProcessor->setValue('av_74_r', '$0');
						}

						$templateProcessor->setValue('av_subtotal_r', '$'.$_tot_NameCreditor_res);

						$_arr_av_total_res = array($_sum_household_res,
												$_sum_automobile_res,
												$_sum_children_res,
												$_tot_OtherAnotherRelationship_res,
												$_sum_insurance_res,
												$_sum_other_res);

						$_sum_av_total_res = array_sum($_arr_av_total_res);
						$templateProcessor->setValue('av_total_r', '$'.$_sum_av_total_res);
						$templateProcessor->setValue('av_income_r', '$'.$_subtract_res);
						$templateProcessor->setValue('av_exp_r', '$'.$_sum_av_total_res);

						// exit($_subtract_res.' - '.$_sum_av_total_res);

						if($_subtract_res > $_sum_av_total_res){
							$_av_surplus_res = $_subtract_res - $_sum_av_total_res;
							$_av_def_res = 0;
						}elseif($_sum_av_total_res > $_subtract_res){
							$_av_def_res = $_sum_av_total_res - $_subtract_res;
							$_av_surplus_res = 0;
						}else{
							$_av_def_res = 0;
							$_av_surplus_res = 0;
						}

						$templateProcessor->setValue('av_surplus_r', '$'.$_av_surplus_res);
						$templateProcessor->setValue('av_def_r', '$'.$_av_def_res);
						



						/*

						$_rentPayments_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-rentPayments'];
						$_propertyTaxes_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-propertyTaxes'];
						$_insuranceResidence_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-insuranceResidence'];
						$_condominiumMaintenance_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-condominiumMaintenance'];
						$_Electricity_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-Electricity'];
						$_WaterGarbageSewer_res = $this->e->order['forms']['husband_monthly_income']['HusbandFinancial-WaterGarbageSewer'];
						$_Telephone_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-Telephone'];
						$_FuelOilGas_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-FuelOilGas'];
						$_RepairsMaintenance_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-RepairsMaintenance'];
						$_LawnCare_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-LawnCare'];
						$_PoolMaintenance_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-PoolMaintenance'];
						$_PestControl_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-PestControl'];
						$_MiscHousehold_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-MiscHousehold'];
						$_FoodHome_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-FoodHome'];
						$_MealsOutsideHome_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-MealsOutsideHome'];
						$_MCableTV_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-CableTV'];
						$_Alarm_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-Alarm'];
						$_ServiceContractsAppliances_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-ServiceContractsAppliances'];
						$_MaidService_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-MaidService'];
						$_OtherHousehold_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-OtherHousehold'];


						$_Gasoline_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-Gasoline'];
						$_Repairs_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-Repairs'];
						$_AutoTags_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-AutoTags'];
						$_CarInsurance_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-CarInsurance'];
						$_Payments_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-Payments'];
						$_RentalReplacements_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-RentalReplacements'];
						$_AltTransportation_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-AltTransportation'];
						$_TollsParking_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-TollsParking'];
						$_CarOther_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-CarOther'];


						$_Nursery_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-Nursery'];
						$_SchoolTuition_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-SchoolTuition'];
						$_SchoolSupplies_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-SchoolSupplies'];
						$_AfterSchoolActivities_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-AfterSchoolActivities'];
						$_LunchMoney_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-LunchMoney'];
						$_PrivateLessons_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-PrivateLessons'];
						$_Allowances_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-Allowances'];
						$_Clothing_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-Clothing'];
						$_Entertainment_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-Entertainment'];
						$_HealthInsuranceChildren_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-HealthInsuranceChildren'];
						$_MedicalDental_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-MedicalDental'];
						$_Psychiatric_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-Psychiatric'];
						$_Orthodontic_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-Orthodontic'];
						$_Vitamins_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-Vitamins'];
						$_Beauty_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-Beauty'];
						$_NonprescriptionMedication_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-NonprescriptionMedication'];
						$_Cosmetics_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-Cosmetics'];
						$_Gifts_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-Gifts'];
						$_CampSummer_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-CampSummer'];
						$_Clubs_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-Clubs'];
						$_TimeSharing_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-TimeSharing'];

						$_Miscellaneous_res= $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-Miscellaneous'];
						$_OtherAnotherRelationship_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-OtherAnotherRelationship'];

						$_HealthInsuranceBis_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-HealthInsuranceBis'];
						$_LifeInsurance_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-LifeInsurance'];
						$_DentalInsurance_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-DentalInsurance'];
						$_OtherInsurance_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-OtherInsurance'];

						$_Laundry_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-Laundry'];
						$_MedicalDentalUnreimbursed_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-MedicalDentalUnreimbursed'];
						$_PsychiatricUnreimbursed_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-PsychiatricUnreimbursed'];
						$_NonprescriptionMedicationsCosmetics_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-NonprescriptionMedicationsCosmetics'];
						$_Grooming_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-Grooming'];
						$_GiftsBis_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-GiftsBis'];
						$_PetExpenses_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-PetExpenses'];
						$_ClubMembership_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-ClubMembership'];
						$_SportsHobbies_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-SportsHobbies'];
						$_EntertainmentBis_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-EntertainmentBis'];
						$_Periodicals_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-Periodicals'];
						$_Vacations_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-Vacations'];
						$_ReligiousOrganizations_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-ReligiousOrganizations'];
						$_BankCharges_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-BankCharges'];
						$_EducationExpenses_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-EducationExpenses'];
						$_OtherNotListed_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-OtherNotListed'];
						

						$_NameCreditor_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-NameCreditor'];
						$_PaymentCreditor_res = $this->_view->order['forms']['husband_monthly_income']['HusbandFinancial-PaymentCreditor'];*/







						if($this->_view->order['forms']['divorce_residency']['other-case'] == 'No'){
							$templateProcessor->setValue('rel_cases_no', 'X');
							$templateProcessor->setValue('rel_cases_yes', '__');
							$templateProcessor->setValue('table_othercase', '');

						}else if($this->_view->order['forms']['divorce_residency']['other-case'] == 'Yes'){
							$templateProcessor->setValue('rel_cases_no', '__');
							$templateProcessor->setValue('rel_cases_yes', 'X');

							// $templateProcessor->setValue('rel_cases_name', $this->_view->order['forms']['divorce_residency']['othercase-casename']);
							// $templateProcessor->setValue('rel_cases_num', $this->_view->order['forms']['divorce_residency']['othercase-casenumber']);


							if(!empty($this->_view->order['forms']['divorce_residency']['othercase-casename'])){


								$table_othercase = new \PhpOffice\PhpWord\Element\Table(array(
																'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::AUTO, 
																// 'width' => 100 * 55,
																'borderColor' =>'000000',
	    														'borderSize' => 2,
															));
							   

							    $fontStyle = array('name' => 'Times New Roman', 'size' => 12);
							    $styleCell = array('align' => 'center');

							    $table_othercase->addRow();
								$table_othercase->addCell(8000)->addText('Case Name', $fontStyle, $styleCell);
								$table_othercase->addCell(8000)->addText('Case Number', $fontStyle, $styleCell);

								for ($i=0; $i <count($this->_view->order['forms']['divorce_residency']['othercase-casename']); $i++) {

									
									$table_othercase->addRow();
									$table_othercase->addCell(8000)->addText($this->_view->order['forms']['divorce_residency']['othercase-casename'][$i], $fontStyle, $styleCell);
									$table_othercase->addCell(8000)->addText($this->_view->order['forms']['divorce_residency']['othercase-casenumber'][$i], $fontStyle, $styleCell);
									
								}


								$templateProcessor->setComplexBlock('{table_othercase}', $table_othercase);
								

							}


						}else{
							$templateProcessor->setValue('rel_cases_no', '__');
							$templateProcessor->setValue('rel_cases_yes', '__');
							$templateProcessor->setValue('table_othercase', '');
						}

						


						if(!empty($this->_view->order['forms']['children_from_the_marriage'])){


							$table = new \PhpOffice\PhpWord\Element\Table(array(
															'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::AUTO, 
															// 'width' => 100 * 55,
															'borderColor' =>'000000',
    														'borderSize' => 2,
														));
						    /*foreach ($details as $detail) {
						        $table->addRow();
						        $table->addCell(700)->addText($detail->column);
						        $table->addCell(500)->addText(1);
						    }*/

						    $fontStyle = array('name' => 'Times New Roman', 'size' => 12);
						    $styleCell = array('align' => 'center');

						    $table->addRow();
							$table->addCell(8000)->addText('Name of Child (initials Only)', $fontStyle, $styleCell);
							$table->addCell(8000)->addText('Date of Birth', $fontStyle, $styleCell);
							$table->addCell(8000)->addText('Gender', $fontStyle, $styleCell);

							for ($i=0; $i <count($this->_view->order['forms']['children_from_the_marriage']['child-firstname']); $i++) {

								
								$_firstname = substr($this->_view->order['forms']['children_from_the_marriage']['child-firstname'][$i], 0, 1); 
								$_middlename = substr($this->_view->order['forms']['children_from_the_marriage']['child-middlename'][$i], 0, 1);
								$_lastname = substr($this->_view->order['forms']['children_from_the_marriage']['child-lastname'][$i], 0, 1); 
								
								$table->addRow();
								$table->addCell(8000)->addText(strtoupper($_firstname.$_middlename.$_lastname), $fontStyle, $styleCell);
								$table->addCell(8000)->addText(date('m/d/Y', strtotime($this->_view->order['forms']['children_from_the_marriage']['child-birth'][$i])), $fontStyle, $styleCell);
								$table->addCell(8000)->addText($this->_view->order['forms']['children_from_the_marriage']['child-gender'][$i], $fontStyle, $styleCell);




								// $templateProcessor->setValue('name_child'.($i+1), strtoupper($_firstname.$_middlename.$_lastname));
								// $templateProcessor->setValue('birth_child'.($i+1), $this->_view->order['forms']['children_from_the_marriage']['child-birth'][$i]);
								// $templateProcessor->setValue('gender_child'.($i+1), $this->_view->order['forms']['children_from_the_marriage']['child-gender'][$i]);
								
							}


							$templateProcessor->setComplexBlock('{table}', $table);




							$table_paternity = new \PhpOffice\PhpWord\Element\Table(array(
															'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::AUTO, 
															// 'width' => 100 * 55,
															 'borderColor' =>'000000',
    														'borderSize' => 2,
														));
						   

						     $fontStyle = array('name' => 'Times New Roman', 'size' => 12);
						    $styleCell = array('align' => 'center');

						    $table_paternity->addRow();
							$table_paternity->addCell(8000)->addText('Name of Child (initials Only)', $fontStyle, $styleCell);
							$table_paternity->addCell(8000)->addText('Gender', $fontStyle, $styleCell);
							$table_paternity->addCell(8000)->addText('Date of Birth', $fontStyle, $styleCell);
							

							for ($i=0; $i <count($this->_view->order['forms']['children_from_the_marriage']['child-firstname']); $i++) {

								$_num = $i+1;
								
								$_firstname = substr($this->_view->order['forms']['children_from_the_marriage']['child-firstname'][$i], 0, 1); 
								$_middlename = substr($this->_view->order['forms']['children_from_the_marriage']['child-middlename'][$i], 0, 1);
								$_lastname = substr($this->_view->order['forms']['children_from_the_marriage']['child-lastname'][$i], 0, 1); 
								
								$table_paternity->addRow();
								$table_paternity->addCell(1000)->addText(strtoupper($_firstname.$_middlename.$_lastname), $fontStyle, $styleCell);
								$table_paternity->addCell(1000)->addText($this->_view->order['forms']['children_from_the_marriage']['child-gender'][$i], $fontStyle, $styleCell);
								$table_paternity->addCell(1000)->addText(date('m/d/Y', strtotime($this->_view->order['forms']['children_from_the_marriage']['child-birth'][$i])), $fontStyle, $styleCell);
								
							
							}


							$templateProcessor->setComplexBlock('{table_paternity}', $table_paternity);


							// Children residence

							if(!empty($this->_view->order['forms']['children_residence'])){


								$templateProcessor->setValue('tot_child', count($this->_view->order['forms']['children_from_the_marriage']['child-firstname']));

								$table_children = new \PhpOffice\PhpWord\Element\Table(array(
																'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::AUTO, 
																// 'width' => 100 * 55,
																 'borderColor' =>'000000',
	    														'borderSize' => 2,
															));
							   

							     $fontStyle = array('name' => 'Times New Roman', 'size' => 12);
							    $styleCell = array('align' => 'center');

							    $table_children->addRow();
								$table_children->addCell(8000)->addText('Child’s full legal name', $fontStyle, $styleCell);
								$table_children->addCell(8000)->addText('Gender', $fontStyle, $styleCell);
								$table_children->addCell(8000)->addText('Place of Birth', $fontStyle, $styleCell);
								$table_children->addCell(8000)->addText('Year of Birth', $fontStyle, $styleCell);
								

								for ($i=0; $i <count($this->_view->order['forms']['children_from_the_marriage']['child-firstname']); $i++) {

									$_num = $i+1;
									
									$_firstname = $this->_view->order['forms']['children_from_the_marriage']['child-firstname'][$i].' '; 
									$_middlename = $this->_view->order['forms']['children_from_the_marriage']['child-middlename'][$i].' ';
									$_lastname = $this->_view->order['forms']['children_from_the_marriage']['child-lastname'][$i].' '; 
									
									$table_children->addRow();
									$table_children->addCell(1000)->addText(ucwords($_firstname.$_middlename.$_lastname), $fontStyle, $styleCell);
									$table_children->addCell(1000)->addText($this->_view->order['forms']['children_from_the_marriage']['child-gender'][$i], $fontStyle, $styleCell);
									$table_children->addCell(1000)->addText($this->_view->order['forms']['children_from_the_marriage']['child-birthPlace'][$i], $fontStyle, $styleCell);
									$table_children->addCell(1000)->addText(date('Y', strtotime($this->_view->order['forms']['children_from_the_marriage']['child-birth'][$i])), $fontStyle, $styleCell);
									
								
								}


								$templateProcessor->setComplexBlock('{table_children}', $table_children);





								$table_residence = new \PhpOffice\PhpWord\Element\Table(array(
																'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::AUTO, 
																// 'width' => 100 * 55,
																 'borderColor' =>'000000',
	    														'borderSize' => 2,
															));
							   

							     $fontStyle = array('name' => 'Times New Roman', 'size' => 12);
							    $styleCell = array('align' => 'center');

							    $table_residence->addRow();
								$table_residence->addCell(2000)->addText('Minor Child', $fontStyle, $styleCell);
								$table_residence->addCell(2500)->addText('Time of Residence (through present)', $fontStyle, $styleCell);
								$table_residence->addCell(2500)->addText('Address', $fontStyle, $styleCell);
								$table_residence->addCell(1000)->addText('Persons residing', $fontStyle, $styleCell);
								$table_residence->addCell(1000)->addText('Relationship', $fontStyle, $styleCell);
								

								for ($w=0; $w <count($this->_view->order['forms']['children_from_the_marriage']['child-firstname']); $w++) {

									for ($i=0; $i<count($this->_view->order['forms']['children_residence']['childrenresidence-address'][$this->_view->order['forms']['children_from_the_marriage']['child-firstname'][$w]]); $i++){

										// $_num = $w+1;
										
										$_firstname = $this->_view->order['forms']['children_from_the_marriage']['child-firstname'][$w].' '; 
										$_middlename = $this->_view->order['forms']['children_from_the_marriage']['child-middlename'][$w].' ';
										$_lastname = $this->_view->order['forms']['children_from_the_marriage']['child-lastname'][$w].' '; 
										
										$table_residence->addRow();
										$table_residence->addCell(2000)->addText(ucwords($_firstname.$_middlename.$_lastname), $fontStyle, $styleCell);
										$table_residence->addCell(2500)->addText($this->_view->order['forms']['children_residence']['childrenresidence-time'][$this->_view->order['forms']['children_from_the_marriage']['child-firstname'][$w]][$i], $fontStyle, $styleCell);
										$table_residence->addCell(2500)->addText($this->_view->order['forms']['children_residence']['childrenresidence-address'][$this->_view->order['forms']['children_from_the_marriage']['child-firstname'][$w]][$i], $fontStyle, $styleCell);
										$table_residence->addCell(1000)->addText($this->_view->order['forms']['children_residence']['childrenresidence-people'][$this->_view->order['forms']['children_from_the_marriage']['child-firstname'][$w]][$i], $fontStyle, $styleCell);
										$table_residence->addCell(1000)->addText($this->_view->order['forms']['children_residence']['childrenresidence-relationship'][$this->_view->order['forms']['children_from_the_marriage']['child-firstname'][$w]][$i], $fontStyle, $styleCell);



									}
								
								}


								$templateProcessor->setComplexBlock('{table_residence}', $table_residence);


							}else{

								$templateProcessor->setValue('tot_child', '');

								$table_children = new \PhpOffice\PhpWord\Element\Table(array(
																'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::AUTO, 
																// 'width' => 100 * 55,
																 'borderColor' =>'000000',
	    														'borderSize' => 2,
															));
							   

							    $fontStyle = array('name' => 'Times New Roman', 'size' => 12);
							    $styleCell = array('align' => 'center');

							    $table_children->addRow();
								$table_children->addCell(8000)->addText('Child’s full legal name', $fontStyle, $styleCell);
								$table_children->addCell(8000)->addText('Gender', $fontStyle, $styleCell);
								$table_children->addCell(8000)->addText('Place of Birth', $fontStyle, $styleCell);
								$table_children->addCell(8000)->addText('Year of Birth', $fontStyle, $styleCell);

								$templateProcessor->setComplexBlock('{table_children}', $table_children);

								$table_residence = new \PhpOffice\PhpWord\Element\Table(array(
																'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::AUTO, 
																// 'width' => 100 * 55,
																 'borderColor' =>'000000',
	    														'borderSize' => 2,
															));
							   

							    $fontStyle = array('name' => 'Times New Roman', 'size' => 12);
							    $styleCell = array('align' => 'center');

							    $table_residence->addRow();
								$table_residence->addCell(2000)->addText('Minor Child', $fontStyle, $styleCell);
								$table_residence->addCell(2500)->addText('Time of Residence (through present)', $fontStyle, $styleCell);
								$table_residence->addCell(2500)->addText('Address', $fontStyle, $styleCell);
								$table_residence->addCell(1000)->addText('Persons residing', $fontStyle, $styleCell);
								$table_residence->addCell(1000)->addText('Relationship', $fontStyle, $styleCell);

								$templateProcessor->setComplexBlock('{table_residence}', $table_residence);
							}




						}




						if(!empty($this->_view->order['forms']['parenting_major_decisions'])){


							$table_major_decisions = new \PhpOffice\PhpWord\Element\Table(array(
																					'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::AUTO,  
																					// 'width' => 100 * 30,
																					// 'borderColor' =>'000000',
    																				// 'borderSize' => 2,
																				));
																			    
						    $table_major_decisions->addRow();
						    $table_major_decisions->addCell(3000)->addText('Education Decisions: ');
						    $table_major_decisions->addCell(5000)->addText($this->_view->order['forms']['parenting_major_decisions']['custody-MajorDecisions-Education']);
						    $table_major_decisions->addRow();
						    $table_major_decisions->addCell(3000)->addText('Non-emergency health care: ');
						    $table_major_decisions->addCell(5000)->addText($this->_view->order['forms']['parenting_major_decisions']['custody-MajorDecisions-HealthCare']);
						    $table_major_decisions->addRow();
						    $table_major_decisions->addCell(3000)->addText('Religious Upbringing: ');
						    $table_major_decisions->addCell(5000)->addText($this->_view->order['forms']['parenting_major_decisions']['custody-MajorDecisions-Religius']);

						    if($this->_view->order['forms']['parenting_major_decisions']['custody-MajorDecisions-add1-Text'] !=''){
						    	$table_major_decisions->addRow();
							    $table_major_decisions->addCell(3000)->addText($this->_view->order['forms']['parenting_major_decisions']['custody-MajorDecisions-add1-Text'].': ');
							    $table_major_decisions->addCell(5000)->addText($this->_view->order['forms']['parenting_major_decisions']['custody-MajorDecisions-add1']);
						    }

						    if($this->_view->order['forms']['parenting_major_decisions']['custody-MajorDecisions-add2-Text'] !=''){
						    	$table_major_decisions->addRow();
							    $table_major_decisions->addCell(3000)->addText($this->_view->order['forms']['parenting_major_decisions']['custody-MajorDecisions-add2-Text'].': ');
							    $table_major_decisions->addCell(5000)->addText($this->_view->order['forms']['parenting_major_decisions']['custody-MajorDecisions-add2']);
						    }						


							$templateProcessor->setComplexBlock('{table_major_decisions}', $table_major_decisions);

						}


						if(!empty($this->_view->order['forms']['custody_and_visitation'])){

							if($this->_view->order['forms']['custody_and_visitation']['custody-children'] == 'Shared Responsibility, Mother residence determines school district' || $this->_view->order['forms']['custody_and_visitation']['custody-children'] == 'Shared Responsibility, Father residence determines school district'){
								$templateProcessor->setValue('cv_share', 'X');
								$templateProcessor->setValue('cv_sole', '');
								$templateProcessor->setValue('cv_sole_name', '');
							}
							
							/*if($this->_view->order['forms']['custody_and_visitation']['custody-children'] == 'Shared Responsibility, Father residence determines school district'){
								$templateProcessor->setValue('cv_fa', 'X');
							}*/

							if($this->_view->order['forms']['custody_and_visitation']['custody-children'] == 'Mothers has Sole Parental Responsibility'){
								$templateProcessor->setValue('cv_sole', 'X');
								$_cv_sole_name = $this->_view->order['forms']['wife_information']['Wife-firstname'].' '.$this->_view->order['forms']['wife_information']['Wife-lastname'];
								$templateProcessor->setValue('cv_sole_name', $_cv_sole_name);
								$templateProcessor->setValue('cv_share', '');
							}

							if($this->_view->order['forms']['custody_and_visitation']['custody-children'] == 'Fathers has Sole Parental Responsibility'){
								$templateProcessor->setValue('cv_sole', 'X');
								$_cv_sole_name = $this->_view->order['forms']['husband_personal_information']['husband-firstname'].' '.$this->_view->order['forms']['husband_personal_information']['husband-lastname'];
								$templateProcessor->setValue('cv_sole_name', $_cv_sole_name);
								$templateProcessor->setValue('cv_share', '');
							}

							if($this->_view->order['forms']['custody_and_visitation']['custody-WeekendTime'] == 'Other'){
								$templateProcessor->setValue('cv_weekend', $this->_view->order['forms']['custody_and_visitation']['custody-WeekendTime-other']);
							}else{
								$templateProcessor->setValue('cv_weekend', $this->_view->order['forms']['custody_and_visitation']['custody-WeekendTime']);
							}

							$templateProcessor->setValue('cv_weekend_time', 'Vistation begins: '.$this->_view->order['forms']['custody_and_visitation']['custody-VistationBegins'].' - Vistation ends: '.$this->_view->order['forms']['custody_and_visitation']['custody-VistationEnds']);


							if($this->_view->order['forms']['custody_and_visitation']['custody-WeekendParentingTime'] == 'Other'){
								$templateProcessor->setValue('cv_weekday', $this->_view->order['forms']['custody_and_visitation']['custody-WeekendParentingTime-other']);
							}else{
								$templateProcessor->setValue('cv_weekday', $this->_view->order['forms']['custody_and_visitation']['custody-WeekendParentingTime']);
							}

							$templateProcessor->setValue('cv_weekday_time', 'Vistation begins: '.$this->_view->order['forms']['custody_and_visitation']['custody-VistationParentingBegins'].' - Vistation ends: '.$this->_view->order['forms']['custody_and_visitation']['custody-VistationParentingEnds']);






							if($this->_view->order['forms']['custody_and_visitation']['custody-WinterBreak']!=''){


								if($this->_view->order['forms']['custody_and_visitation']['custody-WinterBreak']=='Other'){
									$templateProcessor->setValue('cv_winter', $this->_view->order['forms']['custody_and_visitation']['custody-WinterBreak-other']);
								}else{
									$templateProcessor->setValue('cv_winter', $this->_view->order['forms']['custody_and_visitation']['custody-WinterBreak']);
								}

								// $templateProcessor->setValue('cv_winter', $this->_view->order['forms']['custody_and_visitation']['custody-WinterBreak']);
							}

							if($this->_view->order['forms']['custody_and_visitation']['custody-WinterBreak-from']!=''){
								$templateProcessor->setValue('cv_winter', 'From: '.$this->_view->order['forms']['custody_and_visitation']['custody-WinterBreak-from'].' To: '.$this->_view->order['forms']['custody_and_visitation']['custody-WinterBreak-To']);
							}

							if($this->_view->order['forms']['custody_and_visitation']['custody-WinterBreak-other']!=''){
								$templateProcessor->setValue('cv_winter', $this->_view->order['forms']['custody_and_visitation']['custody-WinterBreak-other']);
							}



							if($this->_view->order['forms']['custody_and_visitation']['custody-SpringBreak']!=''){


								if($this->_view->order['forms']['custody_and_visitation']['custody-SpringBreak']=='Other'){
									$templateProcessor->setValue('cv_spring', $this->_view->order['forms']['custody_and_visitation']['custody-SpringBreak-other']);
								}else{
									$templateProcessor->setValue('cv_spring', $this->_view->order['forms']['custody_and_visitation']['custody-SpringBreak']);
								}
								
							}

							/*if($this->_view->order['forms']['custody_and_visitation']['custody-SpringBreak']!=''){
								$templateProcessor->setValue('cv_spring', $this->_view->order['forms']['custody_and_visitation']['custody-SpringBreak']);
							}*/

							if($this->_view->order['forms']['custody_and_visitation']['custody-SpringBreak-from']!=''){
								$templateProcessor->setValue('cv_spring', 'From: '.$this->_view->order['forms']['custody_and_visitation']['custody-SpringBreak-from'].' To: '.$this->_view->order['forms']['custody_and_visitation']['custody-SpringBreak-To']);
							}

							if($this->_view->order['forms']['custody_and_visitation']['custody-SpringBreak-other']!=''){
								$templateProcessor->setValue('cv_spring', $this->_view->order['forms']['custody_and_visitation']['custody-SpringBreak-other']);
							}




							if($this->_view->order['forms']['custody_and_visitation']['custody-SummerBreak']!=''){

								if($this->_view->order['forms']['custody_and_visitation']['custody-SummerBreak']=='Divide'){
									$templateProcessor->setValue('cv_summer', 'Divide the summer equally as follows: '.$this->_view->order['forms']['custody_and_visitation']['custody-SummerBreak-Divide']);
								}else{

									if($this->_view->order['forms']['custody_and_visitation']['custody-SummerBreak']=='Other'){
										$templateProcessor->setValue('cv_summer', $this->_view->order['forms']['custody_and_visitation']['custody-SummerBreak-other']);
									}else{
										$templateProcessor->setValue('cv_summer', $this->_view->order['forms']['custody_and_visitation']['custody-SummerBreak']);
									}

																	

								}
							}
							

							if($this->_view->order['forms']['custody_and_visitation']['custody-SummerBreak-from']!=''){
								$templateProcessor->setValue('cv_summer', 'From: '.$this->_view->order['forms']['custody_and_visitation']['custody-SummerBreak-from'].' To: '.$this->_view->order['forms']['custody_and_visitation']['custody-SummerBreak-To']);
							}

							if($this->_view->order['forms']['custody_and_visitation']['custody-SummerBreak-other']!=''){
								$templateProcessor->setValue('cv_summer', $this->_view->order['forms']['custody_and_visitation']['custody-SummerBreak-other']);
							}


							


							if($this->_view->order['forms']['custody_and_visitation']['custody-ForeignTravel']=='out'){
								$templateProcessor->setValue('cv_travel_in','X');
								$templateProcessor->setValue('cv_travel_out','');
							}else{
								$templateProcessor->setValue('cv_travel_in','');
								$templateProcessor->setValue('cv_travel_out','X');
							}

						}


						/*if(!empty($this->_view->order['forms']['parenting_schedule_holidays'])){


						}*/



						if(!empty($this->_view->order['forms']['parenting_schedule_holidays']) && !empty($this->_view->order['forms']['parenting_schedule_special_occasions'])){


							$table_holidays = new \PhpOffice\PhpWord\Element\Table(array(
																					'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::AUTO,  
																					// 'width' => 100 * 30,
																					'borderColor' =>'000000',
																					'borderSize' => 2,
																					'name' => 'Calibri',
																				));


							$fontStyle = array('bold' => true,'name' => 'Calibri', 'size' => 12);
							$fontStyle2 = array('name' => 'Calibri', 'size' => 12);
						    $styleCell = array('align' => 'center');


							$table_holidays->addRow();
						    $table_holidays->addCell(3000)->addText('EVENT', $fontStyle, $styleCell);
						    $table_holidays->addCell(3000)->addText('PARENT', $fontStyle, $styleCell);
						    $table_holidays->addCell(3000)->addText('EVERY YEAR', $fontStyle, $styleCell);
						    $table_holidays->addCell(3000)->addText('EVEN YEARS', $fontStyle, $styleCell);
						    $table_holidays->addCell(3000)->addText('ODD YEARS', $fontStyle, $styleCell);
						    $table_holidays->addCell(3000)->addText('TIMEFRAME', $fontStyle, $styleCell);
																			    
						    $table_holidays->addRow();
						    $table_holidays->addCell(3000)->addText('Mother\'s Birthday', $fontStyle2);
						    $table_holidays->addCell(3000)->addText($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-MotherBirth'], $fontStyle2, $styleCell);
						    if($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-MotherBirth-Year']=='Every'){
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    }

						    if($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-MotherBirth-Year']=='Even'){
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    }

						    if($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-MotherBirth-Year']=='Odd'){
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    }


						    $table_holidays->addRow();
						    $table_holidays->addCell(3000)->addText('Father\'s Birthday', $fontStyle2);
						    $table_holidays->addCell(3000)->addText($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-FatherBirth'], $fontStyle2, $styleCell);
						    if($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-FatherBirth-Year']=='Every'){
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    }

						    if($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-FatherBirth-Year']=='Even'){
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    }

						    if($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-FatherBirth-Year']=='Odd'){
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    }


						    $table_holidays->addRow();
						    $table_holidays->addCell(3000)->addText('Mother\'s Day', $fontStyle2);
						    $table_holidays->addCell(3000)->addText($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-MotherDay'], $fontStyle2, $styleCell);
						    if($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-MotherDay-Year']=='Every'){
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    }

						    if($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-MotherDay-Year']=='Even'){
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    }

						    if($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-MotherDay-Year']=='Odd'){
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    }


						    $table_holidays->addRow();
						    $table_holidays->addCell(3000)->addText('Father\'s Day', $fontStyle2);
						    $table_holidays->addCell(3000)->addText($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-FatherDay'], $fontStyle2, $styleCell);
						    if($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-FatherDay-Year']=='Every'){
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    }

						    if($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-FatherDay-Year']=='Even'){
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    }

						    if($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-FatherDay-Year']=='Odd'){
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    }

						    if(!empty($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-child'])){

						    	for ($i=0; $i <count($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-child']); $i++) { 
						    		$table_holidays->addRow();
						    		$table_holidays->addCell(3000)->addText('Child '.($i+1).' Birthday', $fontStyle2);
						    		$table_holidays->addCell(3000)->addText($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-child'][$i], $fontStyle2, $styleCell);
						    		if($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-child-Year'][$i]=='Every'){
								    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
								    	$table_holidays->addCell(5000)->addText('');
								    	$table_holidays->addCell(5000)->addText('');
								    	$table_holidays->addCell(5000)->addText('');
								    }

								    if($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-child-Year'][$i]=='Even'){
								    	$table_holidays->addCell(5000)->addText('');
								    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
								    	$table_holidays->addCell(5000)->addText('');
								    	$table_holidays->addCell(5000)->addText('');
								    }

								    if($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-child-Year'][$i]=='Odd'){
								    	$table_holidays->addCell(5000)->addText('');
								    	$table_holidays->addCell(5000)->addText('');
								    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
								    	$table_holidays->addCell(5000)->addText('');
								    }
						    	}
						    }


						    if($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-Other1']!=''){
						    	$table_holidays->addRow();
							    $table_holidays->addCell(3000)->addText($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-Other1'], $fontStyle2);
							    $table_holidays->addCell(3000)->addText($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-Other1-parent'], $fontStyle2, $styleCell);

							    if($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-Other1-Year']=='Every'){
							    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
							    	$table_holidays->addCell(5000)->addText('');
							    	$table_holidays->addCell(5000)->addText('');
							    	$table_holidays->addCell(5000)->addText('');
							    }

							    if($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-Other1-Year']=='Even'){
							    	$table_holidays->addCell(5000)->addText('');
							    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
							    	$table_holidays->addCell(5000)->addText('');
							    	$table_holidays->addCell(5000)->addText('');
							    }

							    if($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-Other1-Year']=='Odd'){
							    	$table_holidays->addCell(5000)->addText('');
							    	$table_holidays->addCell(5000)->addText('');
							    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
							    	$table_holidays->addCell(5000)->addText('');
							    }
						    }


						    if($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-Other2']!=''){
						    	$table_holidays->addRow();
							    $table_holidays->addCell(3000)->addText($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-Other2'], $fontStyle2);
							    $table_holidays->addCell(3000)->addText($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-Other2-parent'], $fontStyle2, $styleCell);

							    if($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-Other2-Year']=='Every'){
							    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
							    	$table_holidays->addCell(5000)->addText('');
							    	$table_holidays->addCell(5000)->addText('');
							    	$table_holidays->addCell(5000)->addText('');
							    }

							    if($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-Other2-Year']=='Even'){
							    	$table_holidays->addCell(5000)->addText('');
							    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
							    	$table_holidays->addCell(5000)->addText('');
							    	$table_holidays->addCell(5000)->addText('');
							    }

							    if($this->_view->order['forms']['parenting_schedule_special_occasions']['custody-SpecialOccasions-Other2-Year']=='Odd'){
							    	$table_holidays->addCell(5000)->addText('');
							    	$table_holidays->addCell(5000)->addText('');
							    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
							    	$table_holidays->addCell(5000)->addText('');
							    }
						    }

						    



						    $table_holidays->addRow();
						    $table_holidays->addCell(3000)->addText('New Years Day', $fontStyle2);
						    $table_holidays->addCell(3000)->addText($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-NewYear'], $fontStyle2, $styleCell);
						    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-NewYear-Year']=='Every'){
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    }

						    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-NewYear-Year']=='Even'){
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    }

						    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-NewYear-Year']=='Odd'){
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    }


						    $table_holidays->addRow();
						    $table_holidays->addCell(3000)->addText('Martin Luther King Day', $fontStyle2);
						    $table_holidays->addCell(3000)->addText($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-MartinLutherKing'], $fontStyle2, $styleCell);
						    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-MartinLutherKing-Year']=='Every'){
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    }

						    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-MartinLutherKing-Year']=='Even'){
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    }

						    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-MartinLutherKing-Year']=='Odd'){
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    }



						    $table_holidays->addRow();
						    $table_holidays->addCell(3000)->addText('Presidents\' Day', $fontStyle2);
						    $table_holidays->addCell(3000)->addText($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-Presidents'], $fontStyle2, $styleCell);
						    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-Presidents-Year']=='Every'){
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    }

						    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-Presidents-Year']=='Even'){
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    }

						    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-Presidents-Year']=='Odd'){
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    }



						    $table_holidays->addRow();
						    $table_holidays->addCell(3000)->addText('Memorial Day', $fontStyle2);
						    $table_holidays->addCell(3000)->addText($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-Memorial'], $fontStyle2, $styleCell);
						    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-Memorial-Year']=='Every'){
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    }

						    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-Memorial-Year']=='Even'){
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    }

						    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-Memorial-Year']=='Odd'){
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    }


						    $table_holidays->addRow();
						    $table_holidays->addCell(3000)->addText('July 4th', $fontStyle2);
						    $table_holidays->addCell(3000)->addText($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-July4'], $fontStyle2, $styleCell);
						    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-July4-Year']=='Every'){
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    }

						    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-July4-Year']=='Even'){
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    }

						    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-July4-Year']=='Odd'){
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    }



						    $table_holidays->addRow();
						    $table_holidays->addCell(3000)->addText('Labor Day', $fontStyle2);
						    $table_holidays->addCell(3000)->addText($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-labor'], $fontStyle2, $styleCell);
						    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-labor-Year']=='Every'){
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    }

						    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-labor-Year']=='Even'){
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    }

						    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-labor-Year']=='Odd'){
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    }


						    $table_holidays->addRow();
						    $table_holidays->addCell(3000)->addText('Veterans\' Day', $fontStyle2);
						    $table_holidays->addCell(3000)->addText($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-Veterans'], $fontStyle2, $styleCell);
						    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-Veterans-Year']=='Every'){
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    }

						    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-Veterans-Year']=='Even'){
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    }

						    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-Veterans-Year']=='Odd'){
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    }


						    $table_holidays->addRow();
						    $table_holidays->addCell(3000)->addText('Thanksgiving Day', $fontStyle2);
						    $table_holidays->addCell(3000)->addText($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-Thanksgiving'], $fontStyle2, $styleCell);
						    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-Thanksgiving-Year']=='Every'){
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    }

						    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-Thanksgiving-Year']=='Even'){
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    }

						    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-Thanksgiving-Year']=='Odd'){
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    }

						     $table_holidays->addRow();
						    $table_holidays->addCell(3000)->addText('Christmas Eve', $fontStyle2);
						    $table_holidays->addCell(3000)->addText($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-ChristmasEve'], $fontStyle2, $styleCell);
						    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-ChristmasEve-Year']=='Every'){
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    }

						    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-ChristmasEve-Year']=='Even'){
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    }

						    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-ChristmasEve-Year']=='Odd'){
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    }



						     $table_holidays->addRow();
						    $table_holidays->addCell(3000)->addText('Christmas Day', $fontStyle2);
						    $table_holidays->addCell(3000)->addText($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-ChristmasDay'], $fontStyle2, $styleCell);
						    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-ChristmasDay-Year']=='Every'){
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    }

						    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-ChristmasDay-Year']=='Even'){
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    }

						    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-ChristmasDay-Year']=='Odd'){
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('');
						    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
						    	$table_holidays->addCell(5000)->addText('');
						    }


						    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-Additional1']!=''){
						    	$table_holidays->addRow();
							    $table_holidays->addCell(3000)->addText($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-Additional1'], $fontStyle2);
							    $table_holidays->addCell(3000)->addText($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-Additional1-parent'], $fontStyle2, $styleCell);

							    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-Additional1-Year']=='Every'){
							    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
							    	$table_holidays->addCell(5000)->addText('');
							    	$table_holidays->addCell(5000)->addText('');
							    	$table_holidays->addCell(5000)->addText('');
							    }

							    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-Additional1-Year']=='Even'){
							    	$table_holidays->addCell(5000)->addText('');
							    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
							    	$table_holidays->addCell(5000)->addText('');
							    	$table_holidays->addCell(5000)->addText('');
							    }

							    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-Additional1-Year']=='Odd'){
							    	$table_holidays->addCell(5000)->addText('');
							    	$table_holidays->addCell(5000)->addText('');
							    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
							    	$table_holidays->addCell(5000)->addText('');
							    }
						    }


						     if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-Additional2']!=''){
						    	$table_holidays->addRow();
							    $table_holidays->addCell(3000)->addText($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-Additional2'], $fontStyle2);
							    $table_holidays->addCell(3000)->addText($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-Additional2-parent'], $fontStyle2, $styleCell);

							    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-Additional2-Year']=='Every'){
							    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
							    	$table_holidays->addCell(5000)->addText('');
							    	$table_holidays->addCell(5000)->addText('');
							    	$table_holidays->addCell(5000)->addText('');
							    }

							    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-Additional2-Year']=='Even'){
							    	$table_holidays->addCell(5000)->addText('');
							    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
							    	$table_holidays->addCell(5000)->addText('');
							    	$table_holidays->addCell(5000)->addText('');
							    }

							    if($this->_view->order['forms']['parenting_schedule_holidays']['custody-holidays-Additional2-Year']=='Odd'){
							    	$table_holidays->addCell(5000)->addText('');
							    	$table_holidays->addCell(5000)->addText('');
							    	$table_holidays->addCell(5000)->addText('X', $fontStyle, $styleCell);
							    	$table_holidays->addCell(5000)->addText('');
							    }
						    }
						    					


							$templateProcessor->setComplexBlock('{table_holidays}', $table_holidays);

						}


						if(!empty($this->_view->order['forms']['parenting_transportation']) && !empty($this->_view->order['forms']['parenting_transportation'])){

							if($this->_view->order['forms']['parenting_transportation']['transportation'] == 'Parent shall provide all transportation'){

								$templateProcessor->setValue('trans1', 'X');
								$templateProcessor->setValue('trans2', '');
								$templateProcessor->setValue('trans3', '');
								$templateProcessor->setValue('transP', $this->_view->order['forms']['parenting_transportation']['transportation-Parent']);

							}

							if($this->_view->order['forms']['parenting_transportation']['transportation'] == 'The parent beginning their time-sharing shall provide transportation'){

								$templateProcessor->setValue('trans1', '');
								$templateProcessor->setValue('trans2', 'X');
								$templateProcessor->setValue('trans3', '');
								$templateProcessor->setValue('transP', '');

							}

							if($this->_view->order['forms']['parenting_transportation']['transportation'] == 'The parent ending their time-sharing shall provide transportation'){

								$templateProcessor->setValue('trans1', '');
								$templateProcessor->setValue('trans2', '');
								$templateProcessor->setValue('trans3', 'X');
								$templateProcessor->setValue('transP', '');

							}
							


						}



						$_total_property = 0;

						if(!empty($this->_view->order['forms']['property_division'])){


							


							$table_property = new \PhpOffice\PhpWord\Element\Table(array(
																					'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT,  
																					'width' => 100 * 50,
																					'borderColor' =>'000000',
																					'borderSize' => 2,
																					'name' => 'Calibri',
																				));


							$fontStyle = array('bold' => false, 'name' => 'Calibri', 'size' => 12);
							$fontStyle2 = array('name' => 'Calibri', 'size' => 12);
						    $styleCell = array('align' => 'center');
						    $styleCell2 = array('align' => 'left');

						    $cellRowSpan = array('vMerge' => 'restart');
							$cellRowContinue = array('vMerge' => 'continue');
							$cellColSpan = array('gridSpan' => 2, 'bgColor' => 'eaeaea');


						    $table_property->addRow();
						    $table_property->addCell(null,$cellColSpan)->addText('PROPERTY', $fontStyle, $styleCell);
						    // $table_property->addCell(3000)->addText(null, $cellRowContinue);

							$table_property->addRow();
						    $table_property->addCell(3000)->addText('Do you have cash on hand to divide?', $fontStyle, $styleCell2);
						    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-cash'], $fontStyle, $styleCell);

						    if($this->_view->order['forms']['property_division']['Property-cash'] == 'Yes'){

							    $table_property->addRow();
							    $table_property->addCell(3000)->addText('How much cash on hand will the Husband keep?', $fontStyle, $styleCell2);
							    $table_property->addCell(3000)->addText('$'.$this->_view->order['forms']['property_division']['Property-cash-husband'], $fontStyle, $styleCell);

							    $table_property->addRow();
							    $table_property->addCell(3000)->addText('How much cash on hand will the Wife keep?', $fontStyle, $styleCell2);
							    $table_property->addCell(3000)->addText('$'.$this->_view->order['forms']['property_division']['Property-cash-wife'], $fontStyle, $styleCell);

							    $_total_property = $_total_property + $this->_view->order['forms']['property_division']['Property-cash-husband'];
							    $_total_property = $_total_property + $this->_view->order['forms']['property_division']['Property-cash-wife'];
							    
							}

						    $table_property->addRow();
						    $table_property->addCell(null,$cellColSpan);


						    $table_property->addRow();
						    $table_property->addCell(3000)->addText('Real Estate: Do you have any houses or land?', $fontStyle, $styleCell2);
						    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-realEstate'], $fontStyle, $styleCell);

						    if($this->_view->order['forms']['property_division']['Property-realEstate'] == 'Yes'){

							    $table_property->addRow();
							    $table_property->addCell(3000)->addText('Address and description of property as it appears on deed', $fontStyle, $styleCell2);
							    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-realEstateDescription'], $fontStyle, $styleCell);


							    $table_property->addRow();
							    $table_property->addCell(3000)->addText('Market Value', $fontStyle, $styleCell2);
							    $table_property->addCell(3000)->addText('$'.$this->_view->order['forms']['property_division']['Property-realEstateValue'], $fontStyle, $styleCell);

							    $table_property->addRow();
							    $table_property->addCell(3000)->addText('Will this property be kept by one spouse or sold with the proceeds being split?', $fontStyle, $styleCell2);
							    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-realEstateKeep'], $fontStyle, $styleCell);

							    $table_property->addRow();
							    $table_property->addCell(3000)->addText('Do you have any MARITAL houses or land to distribute between you?', $fontStyle, $styleCell2);
							    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-realEstateMarital'], $fontStyle, $styleCell);

							    if(isset($this->_view->order['forms']['property_division']['Property-realEstateMarital']) && $this->_view->order['forms']['property_division']['Property-realEstateMarital'] == 'Yes'){

							    	$table_property->addRow();
								    $table_property->addCell(3000)->addText('Will this property be kept by one spouse or sold with the proceeds being split?', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-realEstateMaritalKeep'], $fontStyle, $styleCell);

								    if($this->_view->order['forms']['property_division']['Property-realEstateMaritalKeep'] == 'Kept'){

								    	$table_property->addRow();
									    $table_property->addCell(3000)->addText('Who will keep it?', $fontStyle, $styleCell2);
									    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-realEstateMaritalKeepit'], $fontStyle, $styleCell);

								    }

							    }


							    $_total_property = $_total_property + $this->_view->order['forms']['property_division']['Property-realEstateValue'];


							}


						    $table_property->addRow();
						    $table_property->addCell(null,$cellColSpan);


						    $table_property->addRow();
						    $table_property->addCell(3000)->addText('Vehicles: Do you have any motor vehicles that need to be separated? ', $fontStyle, $styleCell2);
						    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-Vehicles'], $fontStyle, $styleCell);

						    if($this->_view->order['forms']['property_division']['Property-Vehicles'] == 'Yes'){

							    for ($i=0; $i <count($this->_view->order['forms']['property_division']['Property-VehicleDescription']); $i++) {

							    	$table_property->addRow();
								    $table_property->addCell(3000)->addText('Vehicle Description', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-VehicleDescription'][$i], $fontStyle, $styleCell);


								    $table_property->addRow();
								    $table_property->addCell(3000)->addText('Market Value', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText('$'.$this->_view->order['forms']['property_division']['Property-VehicleValue'][$i], $fontStyle, $styleCell);

								    $table_property->addRow();
								    $table_property->addCell(3000)->addText('Whose name(s) are currently on the title?', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-VehicleTitular'][$i], $fontStyle, $styleCell);

								    $table_property->addRow();
								    $table_property->addCell(3000)->addText('Is there money owed on the vehicle?', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-VehicleOwed'][$i], $fontStyle, $styleCell);


								    $table_property->addRow();
								    $table_property->addCell(3000)->addText('Total Liability', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-VehicleLiability'][$i], $fontStyle, $styleCell);

								    $table_property->addRow();
								    $table_property->addCell(3000)->addText('Who will keep this vehicle and the liability, if any?', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-VehicleKeep'][$i], $fontStyle, $styleCell);


								    $_total_property = $_total_property + $this->_view->order['forms']['property_division']['Property-VehicleValue'][$i];

							    }
							}

							$table_property->addRow();
						    $table_property->addCell(null,$cellColSpan);

						    
						    $table_property->addRow();
						    $table_property->addCell(3000)->addText('Retirement Benefits: Do you have retirement plans to separate?', $fontStyle, $styleCell2);
						    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-Retirement'], $fontStyle, $styleCell);

						    if($this->_view->order['forms']['property_division']['Property-Retirement'] == 'Yes'){

							    for ($i=0; $i <count($this->_view->order['forms']['property_division']['Property-PlanName']); $i++) {

							    	$table_property->addRow();
								    $table_property->addCell(3000)->addText('Description including Plan Name', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-PlanName'][$i], $fontStyle, $styleCell);

								    $table_property->addRow();
								    $table_property->addCell(3000)->addText('Plan Administrator', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-PlanAdministrator'][$i], $fontStyle, $styleCell);


								    $table_property->addRow();
								    $table_property->addCell(3000)->addText('Market Value', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText('$'.$this->_view->order['forms']['property_division']['Property-PlanValue'][$i], $fontStyle, $styleCell);

								    

								    $table_property->addRow();
								    $table_property->addCell(3000)->addText('Who will keep this account?', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-PlanKeep'][$i], $fontStyle, $styleCell);


								    $table_property->addRow();
								    $table_property->addCell(3000)->addText('Will the other spouse receive any portion of this retirement account?', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-PlanPortion'][$i], $fontStyle, $styleCell);

								    if(isset($this->_view->order['forms']['property_division']['Property-PlanPortion'][$i]) && $this->_view->order['forms']['property_division']['Property-PlanPortion'][$i] == 'Yes'){
								    	$table_property->addRow();
									    $table_property->addCell(3000)->addText('How much must be transferred', $fontStyle, $styleCell2);
									    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-PlanTransferred'][$i], $fontStyle, $styleCell);
								    }	

								    $_total_property = $_total_property + $this->_view->order['forms']['property_division']['Property-PlanValue'][$i];							    


							    }
							}


							$table_property->addRow();
						    $table_property->addCell(null,$cellColSpan);


						    $table_property->addRow();
						    $table_property->addCell(3000)->addText('Bank Accounts: Do you have bank accounts to divide?', $fontStyle, $styleCell2);
						    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-BankAccount'], $fontStyle, $styleCell);

						    if($this->_view->order['forms']['property_division']['Property-BankAccount'] == 'Yes'){

							    for ($i=0; $i <count($this->_view->order['forms']['property_division']['Property-BankAccountDescription']); $i++) {

							    	$table_property->addRow();
								    $table_property->addCell(3000)->addText('Description including bank name and account type', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-BankAccountDescription'][$i], $fontStyle, $styleCell);


								    $table_property->addRow();
								    $table_property->addCell(3000)->addText('Market Value', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText('$'.$this->_view->order['forms']['property_division']['Property-BankAccountValue'][$i], $fontStyle, $styleCell);

								    

								    $table_property->addRow();
								    $table_property->addCell(3000)->addText('Who will keep this account?', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-BankAccountKeep'][$i], $fontStyle, $styleCell);


								    $table_property->addRow();
								    $table_property->addCell(3000)->addText('Will the other spouse receive any portion of this account?', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-BankAccountPortion'][$i], $fontStyle, $styleCell);

								    if(isset($this->_view->order['forms']['property_division']['Property-BankAccountPortion'][$i]) && $this->_view->order['forms']['property_division']['Property-BankAccountPortion'][$i] == 'Yes'){
								    	$table_property->addRow();
									    $table_property->addCell(3000)->addText('How much must be transferred', $fontStyle, $styleCell2);
									    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-BankAccountTransferred'][$i], $fontStyle, $styleCell);
								    }	

								    $_total_property = $_total_property + $this->_view->order['forms']['property_division']['Property-BankAccountValue'][$i];	


							    }
							}

							$table_property->addRow();
						    $table_property->addCell(null,$cellColSpan);


						    $table_property->addRow();
						    $table_property->addCell(3000)->addText('Investment Accounts: Do you have investment accounts or stocks/bonds/mutual funds to divide?', $fontStyle, $styleCell2);
						    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-InvestmentAccounts'], $fontStyle, $styleCell);

						    if($this->_view->order['forms']['property_division']['Property-InvestmentAccounts'] == 'Yes'){

							    for ($i=0; $i <count($this->_view->order['forms']['property_division']['Property-InvestmentAccountsDescription']); $i++) {

							    	$table_property->addRow();
								    $table_property->addCell(3000)->addText('Description including account/investment name', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-InvestmentAccountsDescription'][$i], $fontStyle, $styleCell);


								    $table_property->addRow();
								    $table_property->addCell(3000)->addText('Market Value', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText('$'.$this->_view->order['forms']['property_division']['Property-InvestmentAccountsValue'][$i], $fontStyle, $styleCell);
								    

								    $table_property->addRow();
								    $table_property->addCell(3000)->addText('Who will keep this account/investment?', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-InvestmentAccountsKeep'][$i], $fontStyle, $styleCell);


								    $table_property->addRow();
								    $table_property->addCell(3000)->addText('Will the other spouse receive any portion of this account?', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-InvestmentAccountsPortion'][$i], $fontStyle, $styleCell);

								    if(isset($this->_view->order['forms']['property_division']['Property-InvestmentAccountsPortion'][$i]) && $this->_view->order['forms']['property_division']['Property-InvestmentAccountsPortion'][$i] == 'Yes'){
								    	$table_property->addRow();
									    $table_property->addCell(3000)->addText('How much must be transferred', $fontStyle, $styleCell2);
									    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-InvestmentAccountsTransferred'][$i], $fontStyle, $styleCell);
								    }

								    $_total_property = $_total_property + $this->_view->order['forms']['property_division']['Property-InvestmentAccountsValue'][$i];	


							    }
							}


							$table_property->addRow();
						    $table_property->addCell(null,$cellColSpan);


							$table_property->addRow();
						    $table_property->addCell(3000)->addText('Life Insurance: Do you have life insurance that needs to be divided?', $fontStyle, $styleCell2);
						    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-LifeInsurance'], $fontStyle, $styleCell);

						    if($this->_view->order['forms']['property_division']['Property-LifeInsurance'] == 'Yes'){

							    for ($i=0; $i <count($this->_view->order['forms']['property_division']['Property-LifeInsuranceDescription']); $i++) {

							    	$table_property->addRow();
								    $table_property->addCell(3000)->addText('Description (include insurance company and last 4 digits of policy number)', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-LifeInsuranceDescription'][$i], $fontStyle, $styleCell);


								    $table_property->addRow();
								    $table_property->addCell(3000)->addText('Cash Surrender Value', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText('$'.$this->_view->order['forms']['property_division']['Property-LifeInsuranceValue'][$i], $fontStyle, $styleCell);
								    

								    $table_property->addRow();
								    $table_property->addCell(3000)->addText('Who will keep this property?', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-LifeInsuranceKeep'][$i], $fontStyle, $styleCell);

								    $_total_property = $_total_property + $this->_view->order['forms']['property_division']['Property-LifeInsuranceValue'][$i];


							    }
							}


						    $table_property->addRow();
						    $table_property->addCell(null,$cellColSpan);



							$table_property->addRow();
						    $table_property->addCell(3000)->addText('Boats: Do you have any boats to divide?', $fontStyle, $styleCell2);
						    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-Boats'], $fontStyle, $styleCell);

						    if($this->_view->order['forms']['property_division']['Property-Boats'] == 'Yes'){

							    for ($i=0; $i <count($this->_view->order['forms']['property_division']['Property-BoatsDescription']); $i++) {

							    	$table_property->addRow();
								    $table_property->addCell(3000)->addText('Description of boat', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-BoatsDescription'][$i], $fontStyle, $styleCell);


								    $table_property->addRow();
								    $table_property->addCell(3000)->addText('Market Value', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText('$'.$this->_view->order['forms']['property_division']['Property-BoatsValue'][$i], $fontStyle, $styleCell);
								    

								    $table_property->addRow();
								    $table_property->addCell(3000)->addText('Who will keep this boat?', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-BoatsKeep'][$i], $fontStyle, $styleCell);


								    $_total_property = $_total_property + $this->_view->order['forms']['property_division']['Property-BoatsValue'][$i];


							    }
							}



							$table_property->addRow();
						    $table_property->addCell(null,$cellColSpan);


							$table_property->addRow();
						    $table_property->addCell(3000)->addText('Other Vehicles: Do you have any other vehicles to divide?', $fontStyle, $styleCell2);
						    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-OtherVehicles'], $fontStyle, $styleCell);

						    if($this->_view->order['forms']['property_division']['Property-OtherVehicles'] == 'Yes'){

							    for ($i=0; $i <count($this->_view->order['forms']['property_division']['Property-OtherVehiclesDescription']); $i++) {

							    	$table_property->addRow();
								    $table_property->addCell(3000)->addText('Description of vehicle', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-OtherVehiclesDescription'][$i], $fontStyle, $styleCell);


								    $table_property->addRow();
								    $table_property->addCell(3000)->addText('Market Value', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText('$'.$this->_view->order['forms']['property_division']['Property-OtherVehiclesValue'][$i], $fontStyle, $styleCell);
								    

								    $table_property->addRow();
								    $table_property->addCell(3000)->addText('Who will keep this vehicle?', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-OtherVehiclesKeep'][$i], $fontStyle, $styleCell);


								    $_total_property = $_total_property + $this->_view->order['forms']['property_division']['Property-OtherVehiclesValue'][$i];


							    }
							}


							$table_property->addRow();
						    $table_property->addCell(null,$cellColSpan);


							$table_property->addRow();
						    $table_property->addCell(3000)->addText('Owed Money: Do you have any money owed to you to divide?', $fontStyle, $styleCell2);
						    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-OwedMoney'], $fontStyle, $styleCell);

						    if($this->_view->order['forms']['property_division']['Property-OwedMoney'] == 'Yes'){

							    for ($i=0; $i <count($this->_view->order['forms']['property_division']['Property-OwedMoneyDescription']); $i++) {

							    	$table_property->addRow();
								    $table_property->addCell(3000)->addText('Description', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-OwedMoneyDescription'][$i], $fontStyle, $styleCell);


								    $table_property->addRow();
								    $table_property->addCell(3000)->addText('Market Value', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText('$'.$this->_view->order['forms']['property_division']['Property-OwedMoneyValue'][$i], $fontStyle, $styleCell);
								    

								    $table_property->addRow();
								    $table_property->addCell(3000)->addText('Who will keep this property?', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-OwedMoneyKeep'][$i], $fontStyle, $styleCell);


								    $_total_property = $_total_property + $this->_view->order['forms']['property_division']['Property-OwedMoneyValue'][$i];


							    }
							}


							$table_property->addRow();
						    $table_property->addCell(null,$cellColSpan);


							$table_property->addRow();
						    $table_property->addCell(3000)->addText('Furniture: Do you have any furniture to divide?', $fontStyle, $styleCell2);
						    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-Furniture'], $fontStyle, $styleCell);

						    if($this->_view->order['forms']['property_division']['Property-Furniture'] == 'Yes'){

							    for ($i=0; $i <count($this->_view->order['forms']['property_division']['Property-FurnitureDescription']); $i++) {

							    	$table_property->addRow();
								    $table_property->addCell(3000)->addText('Description', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-FurnitureDescription'][$i], $fontStyle, $styleCell);


								    $table_property->addRow();
								    $table_property->addCell(3000)->addText('Market Value', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText('$'.$this->_view->order['forms']['property_division']['Property-FurnitureValue'][$i], $fontStyle, $styleCell);
								    

								    $table_property->addRow();
								    $table_property->addCell(3000)->addText('Who will keep this property?', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-FurnitureKeep'][$i], $fontStyle, $styleCell);

								     $_total_property = $_total_property + $this->_view->order['forms']['property_division']['Property-FurnitureValue'][$i];


							    }
							}


							$table_property->addRow();
						    $table_property->addCell(null,$cellColSpan);


							$table_property->addRow();
						    $table_property->addCell(3000)->addText('Jewelry and Collectibles: Do you have any jewelry or collectibles to divide?', $fontStyle, $styleCell2);
						    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-Jewelry'], $fontStyle, $styleCell);

						    if($this->_view->order['forms']['property_division']['Property-Jewelry'] == 'Yes'){

							    for ($i=0; $i <count($this->_view->order['forms']['property_division']['Property-JewelryDescription']); $i++) {

							    	$table_property->addRow();
								    $table_property->addCell(3000)->addText('Description', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-JewelryDescription'][$i], $fontStyle, $styleCell);


								    $table_property->addRow();
								    $table_property->addCell(3000)->addText('Market Value', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText('$'.$this->_view->order['forms']['property_division']['Property-JewelryValue'][$i], $fontStyle, $styleCell);
								    

								    $table_property->addRow();
								    $table_property->addCell(3000)->addText('Who will keep this property?', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-JewelryKeep'][$i], $fontStyle, $styleCell);


								    $_total_property = $_total_property + $this->_view->order['forms']['property_division']['Property-JewelryValue'][$i];


							    }
							}


							$table_property->addRow();
						    $table_property->addCell(null,$cellColSpan);



							$table_property->addRow();
						    $table_property->addCell(3000)->addText('Sports and Entertainment Equipment: Do you have any sports or entertainment equipment to divide?', $fontStyle, $styleCell2);
						    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-Sports'], $fontStyle, $styleCell);

						    if($this->_view->order['forms']['property_division']['Property-Sports'] == 'Yes'){

							    for ($i=0; $i <count($this->_view->order['forms']['property_division']['Property-SportsDescription']); $i++) {

							    	$table_property->addRow();
								    $table_property->addCell(3000)->addText('Description', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-SportsDescription'][$i], $fontStyle, $styleCell);


								    $table_property->addRow();
								    $table_property->addCell(3000)->addText('Market Value', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText('$'.$this->_view->order['forms']['property_division']['Property-SportsValue'][$i], $fontStyle, $styleCell);
								    

								    $table_property->addRow();
								    $table_property->addCell(3000)->addText('Who will keep this property?', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-SportsKeep'][$i], $fontStyle, $styleCell);

								    $_total_property = $_total_property + $this->_view->order['forms']['property_division']['Property-SportsValue'][$i];


							    }
							}


							$table_property->addRow();
						    $table_property->addCell(null,$cellColSpan);



							$table_property->addRow();
						    $table_property->addCell(3000)->addText('Business Interests: Do you have any business interests to divide?', $fontStyle, $styleCell2);
						    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-Business'], $fontStyle, $styleCell);

						    if($this->_view->order['forms']['property_division']['Property-Business'] == 'Yes'){

							    for ($i=0; $i <count($this->_view->order['forms']['property_division']['Property-BusinessDescription']); $i++) {

							    	$table_property->addRow();
								    $table_property->addCell(3000)->addText('Description', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-BusinessDescription'][$i], $fontStyle, $styleCell);


								    $table_property->addRow();
								    $table_property->addCell(3000)->addText('Market Value', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText('$'.$this->_view->order['forms']['property_division']['Property-BusinessValue'][$i], $fontStyle, $styleCell);
								    

								    $table_property->addRow();
								    $table_property->addCell(3000)->addText('Who will keep this property?', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-BusinessKeep'][$i], $fontStyle, $styleCell);

								    $_total_property = $_total_property + $this->_view->order['forms']['property_division']['Property-BusinessValue'][$i];


							    }
							}


							$table_property->addRow();
						    $table_property->addCell(null,$cellColSpan);


							$table_property->addRow();
						    $table_property->addCell(3000)->addText('Additional Property: Do you have additional property to divide?', $fontStyle, $styleCell2);
						    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-AdditionalProperty'], $fontStyle, $styleCell);

						    if($this->_view->order['forms']['property_division']['Property-AdditionalProperty'] == 'Yes'){

							    for ($i=0; $i <count($this->_view->order['forms']['property_division']['Property-AdditionalPropertyDescription']); $i++) {

							    	$table_property->addRow();
								    $table_property->addCell(3000)->addText('Description', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-AdditionalPropertyDescription'][$i], $fontStyle, $styleCell);


								    $table_property->addRow();
								    $table_property->addCell(3000)->addText('Market Value', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText('$'.$this->_view->order['forms']['property_division']['Property-AdditionalPropertyValue'][$i], $fontStyle, $styleCell);
								    

								    $table_property->addRow();
								    $table_property->addCell(3000)->addText('Who will keep this property?', $fontStyle, $styleCell2);
								    $table_property->addCell(3000)->addText($this->_view->order['forms']['property_division']['Property-AdditionalPropertyKeep'][$i], $fontStyle, $styleCell);


								    $_total_property = $_total_property + $this->_view->order['forms']['property_division']['Property-AdditionalPropertyValue'][$i];


							    }
							}


							$table_property->addRow();
						    $table_property->addCell(null,$cellColSpan);


							$table_property->addRow();
						    $table_property->addCell(3000)->addText('Total Property', $fontStyle, $styleCell2);
						    $table_property->addCell(3000)->addText('$'.$_total_property, $fontStyle, $styleCell);


						    $templateProcessor->setComplexBlock('{property}', $table_property);


						}

						$_total_debt = 0;

						if(!empty($this->_view->order['forms']['debt'])){


							

							$table_debt = new \PhpOffice\PhpWord\Element\Table(array(
																					'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT,  
																					'width' => 100 * 50,
																					'borderColor' =>'000000',
																					'borderSize' => 2,
																					'name' => 'Calibri',
																				));


							$fontStyle = array('bold' => false, 'name' => 'Calibri', 'size' => 12);
							$fontStyle2 = array('name' => 'Calibri', 'size' => 12);
						    $styleCell = array('align' => 'center');
						    $styleCell2 = array('align' => 'left');

						    $cellRowSpan = array('vMerge' => 'restart');
							$cellRowContinue = array('vMerge' => 'continue');
							$cellColSpan = array('gridSpan' => 2, 'bgColor' => 'eaeaea');


						    $table_debt->addRow();
						    $table_debt->addCell(null,$cellColSpan)->addText('DEBT', $fontStyle, $styleCell);


						    $table_debt->addRow();
						    $table_debt->addCell(3000)->addText('Credit Card Debt: Do you have Credit Card debt you need to divide?', $fontStyle, $styleCell2);
						    $table_debt->addCell(3000)->addText($this->_view->order['forms']['debt']['Property-debt-credit-car'], $fontStyle, $styleCell);

						    if($this->_view->order['forms']['debt']['Property-debt-credit-car'] == 'Yes'){

							    for ($i=0; $i <count($this->_view->order['forms']['debt']['Property-CreditCarDescription']); $i++) {

							    	$table_debt->addRow();
								    $table_debt->addCell(3000)->addText('Description including bank name and account type (do not enter account #)', $fontStyle, $styleCell2);
								    $table_debt->addCell(3000)->addText($this->_view->order['forms']['debt']['Property-CreditCarDescription'][$i], $fontStyle, $styleCell);


								    $table_debt->addRow();
								    $table_debt->addCell(3000)->addText('Creditor', $fontStyle, $styleCell2);
								    $table_debt->addCell(3000)->addText($this->_view->order['forms']['debt']['Property-CreditCarCreditor'][$i], $fontStyle, $styleCell);
								    

								    $table_debt->addRow();
								    $table_debt->addCell(3000)->addText('Current Amount Owed', $fontStyle, $styleCell2);
								    $table_debt->addCell(3000)->addText('$'.$this->_view->order['forms']['debt']['Property-CreditCarAmount'][$i], $fontStyle, $styleCell);

								    $table_debt->addRow();
								    $table_debt->addCell(3000)->addText('Monthly Payment', $fontStyle, $styleCell2);
								    $table_debt->addCell(3000)->addText('$'.$this->_view->order['forms']['debt']['Property-CreditCarMonthly'][$i], $fontStyle, $styleCell);

								    $table_debt->addRow();
								    $table_debt->addCell(3000)->addText('Who will be responsible for this debt?', $fontStyle, $styleCell2);
								    $table_debt->addCell(3000)->addText($this->_view->order['forms']['debt']['Property-CreditCarResponsible'][$i], $fontStyle, $styleCell);

								    $_total_debt = $_total_debt + $this->_view->order['forms']['debt']['Property-CreditCarAmount'][$i];
								    $_total_debt = $_total_debt + $this->_view->order['forms']['debt']['Property-CreditCarMonthly'][$i];

							    }
							}


							$table_debt->addRow();
						    $table_debt->addCell(null,$cellColSpan);



						    $table_debt->addRow();
						    $table_debt->addCell(3000)->addText('Bank Loans: Do you have Bank/Credit Union Loans to divide?', $fontStyle, $styleCell2);
						    $table_debt->addCell(3000)->addText($this->_view->order['forms']['debt']['Property-debt-bankLoans'], $fontStyle, $styleCell);

						    if($this->_view->order['forms']['debt']['Property-debt-bankLoans'] == 'Yes'){

							    for ($i=0; $i <count($this->_view->order['forms']['debt']['Property-BankLoansDescription']); $i++) {

							    	$table_debt->addRow();
								    $table_debt->addCell(3000)->addText('Description including bank name and account type (do not enter account #)', $fontStyle, $styleCell2);
								    $table_debt->addCell(3000)->addText($this->_view->order['forms']['debt']['Property-BankLoansDescription'][$i], $fontStyle, $styleCell);


								    $table_debt->addRow();
								    $table_debt->addCell(3000)->addText('Creditor', $fontStyle, $styleCell2);
								    $table_debt->addCell(3000)->addText($this->_view->order['forms']['debt']['Property-BankLoansCreditor'][$i], $fontStyle, $styleCell);
								    

								    $table_debt->addRow();
								    $table_debt->addCell(3000)->addText('Current Amount Owed', $fontStyle, $styleCell2);
								    $table_debt->addCell(3000)->addText('$'.$this->_view->order['forms']['debt']['Property-BankLoansAmount'][$i], $fontStyle, $styleCell);

								    $table_debt->addRow();
								    $table_debt->addCell(3000)->addText('Monthly Payment', $fontStyle, $styleCell2);
								    $table_debt->addCell(3000)->addText('$'.$this->_view->order['forms']['debt']['Property-BankLoansMonthly'][$i], $fontStyle, $styleCell);

								    $table_debt->addRow();
								    $table_debt->addCell(3000)->addText('Who will be responsible for this debt?', $fontStyle, $styleCell2);
								    $table_debt->addCell(3000)->addText($this->_view->order['forms']['debt']['Property-BankLoansResponsible'][$i], $fontStyle, $styleCell);

								    $_total_debt = $_total_debt + $this->_view->order['forms']['debt']['Property-BankLoansAmount'][$i];
								    $_total_debt = $_total_debt + $this->_view->order['forms']['debt']['Property-BankLoansMonthly'][$i];

							    }
							}


							$table_debt->addRow();
						    $table_debt->addCell(null,$cellColSpan);



						    $table_debt->addRow();
						    $table_debt->addCell(3000)->addText('Judgments: Do you have Court Judgment Payments to divide?', $fontStyle, $styleCell2);
						    $table_debt->addCell(3000)->addText($this->_view->order['forms']['debt']['Property-debt-Judgments'], $fontStyle, $styleCell);

						    if($this->_view->order['forms']['debt']['Property-debt-Judgments'] == 'Yes'){

							    for ($i=0; $i <count($this->_view->order['forms']['debt']['Property-JudgmentsDescription']); $i++) {

							    	$table_debt->addRow();
								    $table_debt->addCell(3000)->addText('Description including bank name and account type (do not enter account #)', $fontStyle, $styleCell2);
								    $table_debt->addCell(3000)->addText($this->_view->order['forms']['debt']['Property-JudgmentsDescription'][$i], $fontStyle, $styleCell);


								    $table_debt->addRow();
								    $table_debt->addCell(3000)->addText('Creditor', $fontStyle, $styleCell2);
								    $table_debt->addCell(3000)->addText($this->_view->order['forms']['debt']['Property-JudgmentsCreditor'][$i], $fontStyle, $styleCell);
								    

								    $table_debt->addRow();
								    $table_debt->addCell(3000)->addText('Current Amount Owed', $fontStyle, $styleCell2);
								    $table_debt->addCell(3000)->addText('$'.$this->_view->order['forms']['debt']['Property-JudgmentsAmount'][$i], $fontStyle, $styleCell);

								    $table_debt->addRow();
								    $table_debt->addCell(3000)->addText('Monthly Payment', $fontStyle, $styleCell2);
								    $table_debt->addCell(3000)->addText('$'.$this->_view->order['forms']['debt']['Property-JudgmentsMonthly'][$i], $fontStyle, $styleCell);

								    $table_debt->addRow();
								    $table_debt->addCell(3000)->addText('Who will be responsible for this debt?', $fontStyle, $styleCell2);
								    $table_debt->addCell(3000)->addText($this->_view->order['forms']['debt']['Property-JudgmentsResponsible'][$i], $fontStyle, $styleCell);

								    $_total_debt = $_total_debt + $this->_view->order['forms']['debt']['Property-JudgmentsAmount'][$i];
								    $_total_debt = $_total_debt + $this->_view->order['forms']['debt']['Property-JudgmentsMonthly'][$i];

							    }
							}


							$table_debt->addRow();
						    $table_debt->addCell(null,$cellColSpan);


						    $table_debt->addRow();
						    $table_debt->addCell(3000)->addText('Other Debts: Do you have other debts to divide between you and your spouse?', $fontStyle, $styleCell2);
						    $table_debt->addCell(3000)->addText($this->_view->order['forms']['debt']['Property-debt-Other'], $fontStyle, $styleCell);

						    if($this->_view->order['forms']['debt']['Property-debt-Other'] == 'Yes'){

							    for ($i=0; $i <count($this->_view->order['forms']['debt']['Property-debtOtherDescription']); $i++) {

							    	$table_debt->addRow();
								    $table_debt->addCell(3000)->addText('Description including bank name and account type (do not enter account #)', $fontStyle, $styleCell2);
								    $table_debt->addCell(3000)->addText($this->_view->order['forms']['debt']['Property-debtOtherDescription'][$i], $fontStyle, $styleCell);


								    $table_debt->addRow();
								    $table_debt->addCell(3000)->addText('Creditor', $fontStyle, $styleCell2);
								    $table_debt->addCell(3000)->addText($this->_view->order['forms']['debt']['Property-debtOtherCreditor'][$i], $fontStyle, $styleCell);
								    

								    $table_debt->addRow();
								    $table_debt->addCell(3000)->addText('Current Amount Owed', $fontStyle, $styleCell2);
								    $table_debt->addCell(3000)->addText('$'.$this->_view->order['forms']['debt']['Property-debtOtherAmount'][$i], $fontStyle, $styleCell);

								    $table_debt->addRow();
								    $table_debt->addCell(3000)->addText('Monthly Payment', $fontStyle, $styleCell2);
								    $table_debt->addCell(3000)->addText('$'.$this->_view->order['forms']['debt']['Property-debtOtherMonthly'][$i], $fontStyle, $styleCell);

								    $table_debt->addRow();
								    $table_debt->addCell(3000)->addText('Who will be responsible for this debt?', $fontStyle, $styleCell2);
								    $table_debt->addCell(3000)->addText($this->_view->order['forms']['debt']['Property-debtOtherResponsible'][$i], $fontStyle, $styleCell);

								    $_total_debt = $_total_debt + $this->_view->order['forms']['debt']['Property-debtOtherAmount'][$i];
								    $_total_debt = $_total_debt + $this->_view->order['forms']['debt']['Property-debtOtherMonthly'][$i];



							    }
							}


							$table_debt->addRow();
						    $table_debt->addCell(null,$cellColSpan);


							$table_debt->addRow();
						    $table_debt->addCell(3000)->addText('Total Debts', $fontStyle, $styleCell2);
						    $table_debt->addCell(3000)->addText('$'.$_total_debt, $fontStyle, $styleCell);

						    $templateProcessor->setComplexBlock('{debt}', $table_debt);

						}


						$templateProcessor->setValue('total_asset', '$'.$_total_property);
						$templateProcessor->setValue('total_liab', '$'.$_total_debt);
						$templateProcessor->setValue('total_net', '$'.($_total_property - $_total_debt));



						$templateProcessor->saveAs($_ruta.'/'.$file);
					// }
				}

				// copiar ultimo archivo pdf
				/*$_path_pdf    = $this->_conf['ruta_archivos_templates'];
				$_files_pdf = scandir($_path_pdf);
				$_files_pdf = array_diff(scandir($_path_pdf), array('.', '..'));
				foreach($_files_pdf as $file){
					if($file == 'TQDCreditCardAuthorizationForm.pdf'){
						copy($_path_pdf.$file, $_ruta.'/'.$file);
					}
				}*/

				if(file_exists($this->_conf['ruta_archivos_templates'].'TQDCreditCardAuthorizationForm.pdf')){
					copy($this->_conf['ruta_archivos_templates'].'TQDCreditCardAuthorizationForm.pdf', $_ruta.'/TQDCreditCardAuthorizationForm.pdf');
				}

				// guardar data docs en BD
				$_formdoc = new contenidos_forms_doc();
				$_formdoc->id_form = $this->_view->id_form;
				$_formdoc->documentos = 'si';
				$_formdoc->fecha = date('Y-m-d');
				$_formdoc->save();


				// Crear excel calculo child support				
				$_gen_excel = $this->generateExcel($this->_view->id_form, $_ruta);

				// echo $_gen_excel;
				


				// Generar zip
				$_destino = $this->_conf['ruta_archivos_descargas'].'forms/'.$this->_view->order['form_info']['item_producto'].'/'.$this->_view->id_form;
				if(!file_exists($_destino)){
					mkdir($_destino, 0777, true);
				}
				$_zip = $this->_trabajosGestion->generarZip($_ruta, $_destino.'/'.$this->_view->order['form_info']['item_producto'].'-'.$_id_compra.'.zip');

				if($_zip){

					$_editoc = contenidos_forms_doc::find(array('conditions' => array('id = ?', $_formdoc->id)));
					$_editoc->zip = 'si';
					$_editoc->save();

					// echo 'ok';			

				}else{

					$_editoc = contenidos_forms_doc::find(array('conditions' => array('id = ?', $_formdoc->id)));
					$_editoc->zip = 'no';
					$_editoc->save();

					// echo 'no';

				}

				// exit;



				//////////////////
				// Enviar DocuSign
				//////////////////

				try {

					// DocuSign account credentials & Integrator Key				
					$rsaPrivateKey = file_get_contents($this->_conf['docusign']['private_key']);
					$impersonateUserId = $this->_conf['docusign']['user_id'];
					$integrator_key = $this->_conf['docusign']['integrator_key'];
					$host = $this->_conf['docusign']['host'];
					$scopes = "signature impersonation";
					

					/////////////////////////////////////////////////////////////////////////
					// OAUTH
					/////////////////////////////////////////////////////////////////////////

					$config = new DocuSign\eSign\Configuration();
					$apiClient = new DocuSign\eSign\Client\ApiClient($config);					
				    $apiClient->getOAuth()->setOAuthBasePath($this->_conf['docusign']['base_path']);
				    $response = $apiClient->requestJWTUserToken($integrator_key, $impersonateUserId, $rsaPrivateKey, $scopes, 60);				


					/////////////////////////////////////////////////////////////////////////
					// Create & Send Envelope (aka Signature Request)
					/////////////////////////////////////////////////////////////////////////

					if(isset($response)){

					    $access_token = $response[0]['access_token'];
					    $info = $apiClient->getUserInfo($access_token);
					    $account_id = $info[0]['accounts'][0]['account_id'];

					    $config->setHost($host);
					    // $config->addDefaultHeader('Authorization', 'Bearer' . $access_token);
					    $apiClient = new DocuSign\eSign\Client\ApiClient($config);

						// set recipient information
						// $recipientName = "Lucho Test";
						// $recipientEmail = "luciano@indigo.com.ar";

						// $recipientName = $this->_view->user['nombre'].' '.$this->_view->user['apellido'];
						// $recipientEmail = $this->_view->user['email'];
						$recipientName = $_petitioner;
						$recipientName2 = $_respondent;
						$recipientEmail = $_email_pet;
						$recipientEmail2 = $_email_res;

						// instantiate a new envelopeApi object
						$envelopeApi = new DocuSign\eSign\Api\EnvelopesApi($apiClient);

						// configure the document we want signed
						// $path    = __DIR__.'/docs';
						$files_ds = scandir($_ruta);
						$files_ds = array_diff(scandir($_ruta), array('.', '..'));
						sort($files_ds);

						// echo "<pre>";print_r($files);exit;
						$_datos_docs = array();
						$_cont = 1;
						foreach($files_ds as $file){

							if($file != 'child_support_calculate.xlsx'){

							    $document = new DocuSign\eSign\Model\Document();
							    $document->setDocumentBase64(base64_encode(file_get_contents($_ruta.'/'.$file)));
							    $document->setName($file);
							    if($file == 'TQDCreditCardAuthorizationForm.pdf'){
							        $document->setFileExtension("pdf");
							    }else{
							        $document->setFileExtension("docx");
							    } 
							    // $document->setFileExtension("docx");
							    $document->setDocumentId($_cont);
							    $_datos_docs[] = $document;
							    $_cont++;

						    }
						}

						// Create a |SignHere| tab somewhere on the document for the recipient to sign
						$signHere = new \DocuSign\eSign\Model\SignHere();
						$signHere->setAnchorString("(pet)");
						$signHere->setAnchorXOffset("60");
						$signHere->setAnchorYOffset("0");
						$signHere->setAnchorUnits("pixels");
						$signHere->setAnchorIgnoreIfNotPresent(false);

						$signHere2 = new \DocuSign\eSign\Model\SignHere();
						$signHere2->setAnchorString("(res)");
						$signHere2->setAnchorXOffset("60");
						$signHere2->setAnchorYOffset("0");
						$signHere2->setAnchorUnits("pixels");
						$signHere2->setAnchorIgnoreIfNotPresent(false);


						$_datos_txt = array();

						for ($i=1; $i<10; $i++) { 
						    $textTab = new DocuSign\eSign\Model\Text();
						    $textTab->setTabId($i);
						    $textTab->setTabLabel("label_".$i);
						    $textTab->setAnchorString("{".$i."}");
						    $textTab->setXPosition("10");
						    $textTab->setYPosition("2");
						    $textTab->setAnchorUnits("pixels");
						    $textTab->setValue("");
						    $_datos_txt[] = $textTab;
						}

						// add the signature tab to the envelope's list of tabs
						$tabs = new DocuSign\eSign\Model\Tabs();
						$tabs->setSignHereTabs(array($signHere));
						$tabs->setTextTabs($_datos_txt);

						$tabs2 = new DocuSign\eSign\Model\Tabs();
						$tabs2->setSignHereTabs(array($signHere2));

						// add a signer to the envelope
						$signer = new \DocuSign\eSign\Model\Signer();
						$signer->setEmail($recipientEmail);
						$signer->setName($recipientName);
						$signer->setRecipientId($this->_view->user['id']);
						$signer->setTabs($tabs);

						$signer2 = new \DocuSign\eSign\Model\Signer();
						$signer2->setEmail($recipientEmail2);
						$signer2->setName($recipientName2);
						$signer2->setRecipientId($this->_view->user['id']*2);
						$signer2->setTabs($tabs2);

						// Add a recipient to sign the document
						$recipients = new DocuSign\eSign\Model\Recipients();
						$recipients->setSigners(array($signer, $signer2));
						$envelop_definition = new DocuSign\eSign\Model\EnvelopeDefinition();
						$envelop_definition->setEmailSubject("TheQuickdivorce.com - Please sign these docs");

						// set envelope status to "sent" to immediately send the signature request
						$envelop_definition->setStatus("sent");
						$envelop_definition->setRecipients($recipients);
						// $envelop_definition->setDocuments(array($document, $document1));
						$envelop_definition->setDocuments($_datos_docs);

						// create and send the envelope! (aka signature request)
						$envelop_summary = $envelopeApi->createEnvelope($account_id, $envelop_definition, null);
						// echo "{$envelop_summary}\n";
						if($envelop_summary['error_details']==''){

							$_edit_ds = contenidos_forms_doc::find(array('conditions' => array('id = ?', $_formdoc->id)));
							$_edit_ds->docusign = 'si';
							$_edit_ds->save();

						    echo "ok";

						}else{
							$_edit_ds = contenidos_forms_doc::find(array('conditions' => array('id = ?', $_formdoc->id)));
							$_edit_ds->docusign = 'no';
							$_edit_ds->save();

							echo 'no';
						}

					}


				} catch (Exception $e) {
					// echo 'no';
					echo 'Exception: ',  $e->getMessage();
				}

				// echo "<pre>";print_r($envelop_summary);exit;			

				// echo "<pre>";print_r($files);exit;

			}
		}


	}

	public function generateExcel($_id, $_ruta)
	{

		$this->_acl->acceso('encargado_access');
		

		$_data = $this->_trabajosGestion->calculateChildSupportExcel($_id);

		if($_data){


			require_once  RAIZ.'libs/PHPExcel.php';

			$objPHPExcel = new PHPExcel();

			// Set document properties
			// echo date('H:i:s') , " Set document properties" , EOL;
			/*$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
										 ->setLastModifiedBy("Maarten Balliauw")
										 ->setTitle("PHPExcel Test Document")
										 ->setSubject("PHPExcel Test Document")
										 ->setDescription("Test document for PHPExcel, generated using PHP classes.")
										 ->setKeywords("office PHPExcel php")
										 ->setCategory("Test result file");*/

			$objPHPExcel->getProperties()->setTitle("Child Support Calculate");


			// Add some data
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A1', 'Children')
			            ->setCellValue('A2', $_data['children'])
			            ->setCellValue('B3', 'Mother')
			            ->setCellValue('C3', 'Father')
			            ->setCellValue('A4', 'Net Monthly Income')
						->setCellValue('B4', $_data['net_monthly_income_mother'])
						->setCellValue('C4', $_data['net_monthly_income_father'])
			            ->setCellValue('A5', 'Overnight Percentage')
			            ->setCellValue('B5', $_data['overnight_percentage_mother'])
						->setCellValue('C5', $_data['overnight_percentage_father'])
			            ->setCellValue('A6', 'Overnights')
			            ->setCellValue('B6', $_data['overnights_mother'])
						->setCellValue('C6', $_data['overnights_father'])
			            ->setCellValue('A7', 'Payment Share To Other')
			            ->setCellValue('B7', $_data['payment_share_to_other_mother'])
						->setCellValue('C7', $_data['payment_share_to_other_father'])
			            ->setCellValue('A8', 'Health Insurance Premiums')
			            ->setCellValue('B8', $_data['health_insurance_premiums_mother'])
						->setCellValue('C8', $_data['health_insurance_premiums_father'])
			            ->setCellValue('A9', 'Presumed Amt Paid')
			            ->setCellValue('B9', $_data['presumed_amt_paid_mother'])
						->setCellValue('C9', $_data['presumed_amt_paid_father']);	


			$objPHPExcel->getActiveSheet()
					    ->getStyle('A1:A2')
					    ->getAlignment()
					    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			$objPHPExcel->getActiveSheet()
					    ->getStyle('B3:B9')
					    ->getAlignment()
					    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			$objPHPExcel->getActiveSheet()
					    ->getStyle('C3:C9')
					    ->getAlignment()
					    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);		

			// $objPHPExcel->getActiveSheet()->getStyle('A4:A9')->getAlignment()->setWrapText(true);

			foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			    foreach ($worksheet->getColumnIterator() as $column) {
			        $worksheet
			            ->getColumnDimension($column->getColumnIndex())
			            ->setAutoSize(true);
			    } 
			}

			$styleArray = array(
				'font' => array(
					'bold' => true
				)
			);


			$objPHPExcel->getActiveSheet()->getStyle('A9')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('B9')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('C9')->applyFromArray($styleArray);
	


			// $objPHPExcel->getActiveSheet()->setCellValue('A8',"Hello\nWorld");
			// $objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(-1);
			// $objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setWrapText(true);


			// $value = "-ValueA\n-Value B\n-Value C";
			// $objPHPExcel->getActiveSheet()->setCellValue('A10', $value);
			// $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
			// $objPHPExcel->getActiveSheet()->getStyle('A10')->getAlignment()->setWrapText(true);
			// $objPHPExcel->getActiveSheet()->getStyle('A10')->setQuotePrefix(true);


			// Rename worksheet
			// $objPHPExcel->getActiveSheet()->setTitle('Simple');

			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);

			// Save Excel 2007 file				
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save($_ruta.'/child_support_calculate.xlsx');


			return true;


		}else{
			return false;
		}

		
	}


	
	public function calcular($_id)
	{
		$this->_acl->acceso('encargado_access');


		// echo "<pre>";print_r($_POST);exit;

		$_id = (int) $_id;
		$_data = $this->_trabajosGestion->calculateChildSupport($_id);

		// echo "<pre>";print_r($_data);exit;

		if($_data){


			if($_data['mother_payment'] != $_data['father_payment']){

				if($_data['mother_payment'] != 0){
					$jsondata['payment_parent'] = 'Mother';
					$jsondata['payment_receiver'] = 'Father';
					$jsondata['payment_using'] = number_format($_data['mother_payment'], 2, '.', ',');
				}

				if($_data['father_payment'] != 0){
					$jsondata['payment_parent'] = 'Father';
					$jsondata['payment_receiver'] = 'Mother';
					$jsondata['payment_using'] = number_format($_data['father_payment'], 2, '.', ',');
				}


			}else{
				$jsondata['payment_parent'] = '';
				$jsondata['payment_using'] = number_format(0, 2, '.', ',');
			}


			$jsondata['mother_income'] = number_format($_data['mother_income'], 2, '.', ',');
			$jsondata['father_income'] = number_format($_data['father_income'], 2, '.', ',');
			$jsondata['mother_child_support_oblig'] = number_format($_data['mother_child_support_oblig'], 2, '.', ',');
			$jsondata['father_child_support_oblig'] = number_format($_data['father_child_support_oblig'], 2, '.', ',');
			$jsondata['mother_child_support_credits'] = number_format($_data['mother_child_support_credits'], 2, '.', ',');
			$jsondata['father_child_support_credits'] = number_format($_data['father_child_support_credits'], 2, '.', ',');				
			$jsondata['mother_payment'] = number_format($_data['mother_payment'], 2, '.', ',');
			$jsondata['father_payment'] = number_format($_data['father_payment'], 2, '.', ',');
		}
		

		return json_encode($jsondata);

		
	}

	public function test()
	{

		$this->_acl->acceso('encargado_access');

		$_data = $this->_trabajosGestion->calculateChildSupportTEST(25);

		if($_data){


			require_once  RAIZ.'libs/PHPExcel.php';

			$objPHPExcel = new PHPExcel();

			// Set document properties
			// echo date('H:i:s') , " Set document properties" , EOL;
			/*$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
										 ->setLastModifiedBy("Maarten Balliauw")
										 ->setTitle("PHPExcel Test Document")
										 ->setSubject("PHPExcel Test Document")
										 ->setDescription("Test document for PHPExcel, generated using PHP classes.")
										 ->setKeywords("office PHPExcel php")
										 ->setCategory("Test result file");*/

			$objPHPExcel->getProperties()->setTitle("Child Support Calculate");


			// Add some data
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A1', 'Children')
			            ->setCellValue('A2', $_data['children'])
			            ->setCellValue('B3', 'Mother')
			            ->setCellValue('C3', 'Father')
			            ->setCellValue('A4', 'Net Monthly Income')
						->setCellValue('B4', $_data['net_monthly_income_mother'])
						->setCellValue('C4', $_data['net_monthly_income_father'])
			            ->setCellValue('A5', 'Overnight Percentage')
			            ->setCellValue('B5', $_data['overnight_percentage_mother'])
						->setCellValue('C5', $_data['overnight_percentage_father'])
			            ->setCellValue('A6', 'Overnights')
			            ->setCellValue('B6', $_data['overnights_mother'])
						->setCellValue('C6', $_data['overnights_father'])
			            ->setCellValue('A7', 'Payment Share To Other')
			            ->setCellValue('B7', $_data['payment_share_to_other_mother'])
						->setCellValue('C7', $_data['payment_share_to_other_father'])
			            ->setCellValue('A8', 'Health Insurance Premiums')
			            ->setCellValue('B8', $_data['health_insurance_premiums_mother'])
						->setCellValue('C8', $_data['health_insurance_premiums_father'])
			            ->setCellValue('A9', 'Presumed Amt Paid')
			            ->setCellValue('B9', $_data['presumed_amt_paid_mother'])
						->setCellValue('C9', $_data['presumed_amt_paid_father']);	


			$objPHPExcel->getActiveSheet()
					    ->getStyle('A1:A2')
					    ->getAlignment()
					    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			$objPHPExcel->getActiveSheet()
					    ->getStyle('B3:B9')
					    ->getAlignment()
					    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			$objPHPExcel->getActiveSheet()
					    ->getStyle('C3:C9')
					    ->getAlignment()
					    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);		

			// $objPHPExcel->getActiveSheet()->getStyle('A4:A9')->getAlignment()->setWrapText(true);

			foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			    foreach ($worksheet->getColumnIterator() as $column) {
			        $worksheet
			            ->getColumnDimension($column->getColumnIndex())
			            ->setAutoSize(true);
			    } 
			}

			$styleArray = array(
				'font' => array(
					'bold' => true
				)
			);


			$objPHPExcel->getActiveSheet()->getStyle('A9')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('B9')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('C9')->applyFromArray($styleArray);
	


			// $objPHPExcel->getActiveSheet()->setCellValue('A8',"Hello\nWorld");
			// $objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(-1);
			// $objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setWrapText(true);


			// $value = "-ValueA\n-Value B\n-Value C";
			// $objPHPExcel->getActiveSheet()->setCellValue('A10', $value);
			// $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
			// $objPHPExcel->getActiveSheet()->getStyle('A10')->getAlignment()->setWrapText(true);
			// $objPHPExcel->getActiveSheet()->getStyle('A10')->setQuotePrefix(true);


			// Rename worksheet
			// $objPHPExcel->getActiveSheet()->setTitle('Simple');

			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);

			// Save Excel 2007 file				
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save($this->_conf['ruta_archivos'].'xlsx/child_support_demo.xlsx');


			echo "<pre>";print_r($_data);exit;


			/*if($_data['mother_payment'] != $_data['father_payment']){

				if($_data['mother_payment'] != 0){
					$jsondata['payment_parent'] = 'Mother';
					$jsondata['payment_receiver'] = 'Father';
					$jsondata['payment_using'] = number_format($_data['mother_payment'], 2, '.', ',');
				}

				if($_data['father_payment'] != 0){
					$jsondata['payment_parent'] = 'Father';
					$jsondata['payment_receiver'] = 'Mother';
					$jsondata['payment_using'] = number_format($_data['father_payment'], 2, '.', ',');
				}


			}else{
				$jsondata['payment_parent'] = '';
				$jsondata['payment_using'] = number_format(0, 2, '.', ',');
			}


			$jsondata['mother_income'] = number_format($_data['mother_income'], 2, '.', ',');
			$jsondata['father_income'] = number_format($_data['father_income'], 2, '.', ',');
			$jsondata['mother_child_support_oblig'] = number_format($_data['mother_child_support_oblig'], 2, '.', ',');
			$jsondata['father_child_support_oblig'] = number_format($_data['father_child_support_oblig'], 2, '.', ',');
			$jsondata['mother_child_support_credits'] = number_format($_data['mother_child_support_credits'], 2, '.', ',');
			$jsondata['father_child_support_credits'] = number_format($_data['father_child_support_credits'], 2, '.', ',');				
			$jsondata['mother_payment'] = number_format($_data['mother_payment'], 2, '.', ',');
			$jsondata['father_payment'] = number_format($_data['father_payment'], 2, '.', ',');*/
		}

		echo "<pre>";print_r($jsondata);exit;

		$this->_view->renderizar('test','forms', 'forms');
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