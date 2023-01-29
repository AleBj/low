<?php

use controllers\administradorController\administradorController;

class productosController extends administradorController
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
		
    }
    
    public function index()
    {	
		$this->redireccionar('administrador/productos/fijos');	
    }
	
	public function fijos($pagina = false)
    {
		$this->_acl->acceso('encargado_access');
		
		//$this->_view->setJs(array('jquery.btechco.excelexport','jquery.base64','exportar_data'));
		$this->_view->setCss(array('sweetalert'));		
		$this->_view->setJs(array('sweetalert.min'));
		
		/*$pagina = (!validador::filtrarInt($pagina)) ? false : (int) $pagina;
		$paginador = new Paginador();*/
		
		$this->_view->datos = $this->_trabajosGestion->traerProductos('alta');
		
		/*$this->_view->beneficios = $paginador->paginar($beneficios, $pagina, 20);
		$this->_view->paginacion = $paginador->getView('paginador-bootstrap', 'administrador/beneficios/listarAlta');*/


		// echo "<pre>";print_r($this->_view->datos);exit;
			
		$this->_view->titulo = 'Administrador - Seguimiento';
        $this->_view->renderizar('fijos', 'productos');	
    }
	
	public function variables($pagina = false)
    {
		$this->_acl->acceso('encargado_access');
		
		//$this->_view->setJs(array('jquery.btechco.excelexport','jquery.base64','exportar_data'));
		$this->_view->setCss(array('sweetalert'));		
		$this->_view->setJs(array('sweetalert.min'));
		
		/*$pagina = (!validador::filtrarInt($pagina)) ? false : (int) $pagina;
		$paginador = new Paginador();*/
		
		$this->_view->datos = $this->_trabajosGestion->traerProductosVariables('alta');
		
		/*$this->_view->beneficios = $paginador->paginar($beneficios, $pagina, 20);
		$this->_view->paginacion = $paginador->getView('paginador-bootstrap', 'administrador/beneficios/listarAlta');*/
			
		$this->_view->titulo = 'Administrador - Seguimiento';
        $this->_view->renderizar('variables', 'productos');	
    }

    public function cargarPrecio()
	{
		$this->_acl->acceso('encargado_access');
		
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){	
			
				$_val = explode(',', $_POST['valor']);
				// $_estado = $_POST['estado'];
				
				// $_datos  = $this->_trabajosGestion->traerProdBuscador($_val, $_estado);
				
				// echo "<pre>";print_r($_val);echo"</pre>";exit;
				
				if(!empty($_val)){

					$_html = '';
					foreach($_val as $val){ 


													        							
						$_html .= '<div class="col-lg-12 input-group" style="margin-bottom:5px;"> 
                                    <div class="input-group-addon">$</div>
                                    <input class="form-control" id="price_'.$val.'" name="price[]" placeholder="Precio para '.$val.' OZ" type="text"  /> 
                                  </div>';

					        
		        
					}

					$_html .= '<br><a class="btn btn btn-info" id="btPrice"  href="javascript:void(0);">Cargar precios</a>';
					$_html .= '<script>
								$("#btPrice").click(function(){

							        var _vals = [];
							        $(":checkbox:checked").each(function(i){
							          _vals[i] = $(this).val();
							        });

							        $.ajax({
							            type: "POST",
							            url: _root_ + "administrador/productos/cargarPrecio",
							            data: "valor="+_vals+"&_csrf='.$this->_sess->get('_csrf').'",
							            success: function(data){
							                $(".cont_price").html("").append(data).fadeIn("slow");
							            }
							        });
							        
							        return false;

							    });
							    </script>';

					
				
				}else{
					$_html = 'No hay resultados';
				}

							
				
				echo $_html;

			}else{
				$this->redireccionar('error/access/404');
			}
			
		}
	}
	
	
	public function cargar($_categoria = null)
    {	
		$this->_acl->acceso('encargado_access');
		//$this->_view->_categoria = (int) $_categoria;
	
		/*if(!$this->_sess->get('carga_actual')){
			$this->_sess->set('carga_actual', rand(1135687452,9999999999));
		}
		*/
		// $this->_view->setCssPlugin(array('dropzone.min', 'icheck'));
		// $this->_view->setJs(array('dropzone', 'icheck.min'));
		
		// $this->_view->pres = $this->_trabajosGestion->traerProdPresentaciones();
		// $this->_view->subcat = $this->_trabajosGestion->traerSubcategorias();
		// $this->_view->tags = $this->_trabajosGestion->traerTags();
		
		//$this->_sess->destroy('img_id');
		
		
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){	
			
			
				if($_POST['envio01'] == 1){
					
					$this->_view->data = $_POST;
					
				
					// echo "<pre>";print_r($_POST);exit;
					

					$_cargar = new contenidos_producto();
					$_cargar->titulo = $this->_xss->xss_clean($_POST['titulo']);
					$_cargar->descripcion = $this->_xss->xss_clean($_POST['desc']);	
					$_cargar->precio = $this->_xss->xss_clean($_POST['precio']);		
					$_cargar->estado = 'alta';
					$_cargar->fecha = date('Y-m-d');
					$_cargar->save();
								
					
					// $this->_sess->destroy('carga_actual');
					// $this->_sess->destroy('img_id');
					$this->redireccionar('administrador/productos/fijos');
				}	

			}else{
				$this->redireccionar('error/access/404');
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
		$this->_view->setJs(array('dropzone'));
		
		$this->_view->trabajo = $this->_trabajosGestion->traerProducto($_id);
		
		if($this->_view->trabajo['identificador']==''){
			$this->_sess->set('edicion_actual', rand((int)1135687452,(int)999999999));
		}else{
			$this->_sess->set('edicion_actual', $this->_view->trabajo['identificador']);
		}

		// $this->_view->cat = $this->_trabajosGestion->traerCategorias();
		// $this->_view->subcat = $this->_trabajosGestion->traerSubcategorias();
		
		// $this->_view->tags = $this->_trabajosGestion->traerTags();
		// $this->_view->cantidad = $this->_view->trabajo['cantidad_inventario'] - $this->_view->trabajo['politicas_inventario'];
		
		// echo "<pre>";print_r($this->_view->trabajo);echo "</pre>";exit;
								
		
	
		$this->_view->titulo = 'Administrador - Seguimiento';
        $this->_view->renderizar('editar', 'productos');	
    }


    public function consolidarEditar()
	{				
		$this->_acl->acceso('encargado_access');

		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){	
			
			
				if($_POST['envio01'] == 1){
				
					$this->_view->data = $_POST;	


					 // echo "<pre>";print_r($this->_view->data);exit;
					
					
					/*if(isset($this->_view->data['eliminar_gal']) && $this->_view->data['eliminar_gal'][0]!=''){
						foreach($this->_view->data['eliminar_gal'] as $_gal){
							$this->_trabajosGestion->eliminarImgGal($_gal, $this->_conf['ruta_img_cargadas'], 'productos', 'galeria');			
						}
					}*/

					/*if($this->_view->data['tags']!=''){
						$_tags = explode(',', $this->_view->data['tags']);
						foreach ($_tags as $val) {
							$_hay = contenidos_tag::find(array('conditions' => array('nombre = ?', $val)));
							if(!$_hay){
								$tag = new contenidos_tag();
								$tag->nombre = $val;						
								$tag->save();
							}							
						}						
					}*/
					
					
					/*$fecha = date("Y-m-d", strtotime($this->_view->data['fecha_nacimiento']));
					$_fecha_separada = explode('-',$fecha);*/
					
					// $_subcat = (isset($this->_view->data['subcategoria'])) ? $this->_view->data['subcategoria']: '';			
					
					//echo $_dia_festivo ;
					//echo "<pre>";print_r($this->_view->data);exit;
					

					$_cargar = contenidos_producto::find($_POST['_id']);
					$_cargar->titulo = $this->_xss->xss_clean($_POST['titulo']);
					$_cargar->descripcion = $this->_xss->xss_clean($_POST['desc']);	
					$_cargar->precio = $this->_xss->xss_clean($_POST['precio']);
					$_cargar->identificador = $this->_sess->get('edicion_actual');		
					// $_cargar->estado = $this->_xss->xss_clean($_POST['estado']);
					// $_cargar->fecha = date('Y-m-d');
					// $_cargar->save();

					if($_cargar->save()){

						$_img_id =(isset($_POST['_seo_img'])) ? $_POST['_seo_img'] : 0;
						$_seo = $this->_trabajosGestion->editarSeo($_POST['_id'],
	                                                                'productos_fijos',
	                                                                $_POST['_tipo'],
	                                                                $_POST['titulo_seo'],
	                                                                $_POST['desc_seo'],
	                                                            	$_img_id,
	                                                            	$this->_sess->get('edicion_actual'));
					}
					
					$this->_sess->destroy('edicion_actual');
					// $this->_sess->destroy('img_id');
					/*if($this->_view->data['estado']=='alta'){
						$this->redireccionar('administrador/productos/fijos');
					}else{
						$this->redireccionar('administrador/productos/bajas');
					}*/
					
					// $this->redireccionar('administrador/productos/fijos');

					echo "Load Successful! Wait...
                    <script>
                        setTimeout(function(){  window.location.href = \"" . $this->_conf['base_url']. "administrador/productos/fijos\"; }, 1000);
                    </script>";
												
					
				}else{
					$this->redireccionar('error/access/404');
				}
			}
		}
	
    }
	
	/*public function cargarexcel($_categoria = null)
    {	
		$this->_acl->acceso('encargado_access');
		//$this->_view->_categoria = (int) $_categoria;
	
		if(!$this->_sess->get('carga_actual')){
			$this->_sess->set('carga_actual', rand((int)1135687452,(int)999999999));
		}
		
		$this->_view->setCssPlugin(array('dropzone.min'));
		$this->_view->setJs(array('dropzone'));
	
		
		
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

	                    

						

	                      // echo "<pre>";print_r($_cont);echo "</pre>";exit;

	                    

	                    for ($i=1; $i < count($_cont); $i++) { 


	                    	if(!$this->_sess->get('carga_actual')){
								$this->_sess->set('carga_actual', rand((int)1135687452,(int)999999999));
							}

	                    	
								if($_cont[$i][18]!=''){
									$_tags = explode(';', $_cont[$i][18]);
									foreach ($_tags as $val) {
										$_hay = contenidos_tag::find(array('conditions' => array('nombre = ?', $val)));
										if(!$_hay){
											$tag = new contenidos_tag();
											$tag->nombre = $val;						
											$tag->save();
										}							
									}	
									$_guardar_tags ='';
									$_guardar_tags = implode(',', $_tags);					
								}

								$_cargar = new contenidos_producto();
								$_cargar->id_categoria = $_cont[$i][0];
								$_cargar->id_subcategoria = $_cont[$i][1];					
								$_cargar->sku = ($_cont[$i][2]!='') ? $_cont[$i][2] : 0;
								$_cargar->sku_variable = ($_cont[$i][3]!='') ? $_cont[$i][3] : 0;
								$_cargar->nombre = ($_cont[$i][4]!='') ? htmlentities($_cont[$i][4]) : '';
								$_cargar->stock = ($_cont[$i][5]!='') ? $_cont[$i][5] : 1;
								$_cargar->presentacion = ($_cont[$i][6]!='') ? $_cont[$i][6] : '';
								$_cargar->unidad_medida = ($_cont[$i][7]!='') ? $_cont[$i][7] : '';
								$_cargar->dimension = ($_cont[$i][8]!='') ? $_cont[$i][8] : '';
								$_cargar->ubicacion = ($_cont[$i][9]!='') ? $_cont[$i][9] : '';
								$_cargar->cantidad_inventario = ($_cont[$i][10]!='') ? $_cont[$i][10] : 0;
								$_cargar->politicas_inventario = ($_cont[$i][11]!='') ? $_cont[$i][11] : 0;							
								$_cargar->descripcion = ($_cont[$i][12]!='') ? $_cont[$i][12] : '';
								$_cargar->precio = ($_cont[$i][13]!='') ? $_cont[$i][13] : 0;
								$_cargar->oferta = ($_cont[$i][14]!='') ? $_cont[$i][14] : 0;
								$_cargar->impuesto = ($_cont[$i][15]!='') ? $_cont[$i][15] : 0;
								$_cargar->envio = ($_cont[$i][16]!='') ? $_cont[$i][16] : 'no';
								// $_cargar->precio_mayor = $this->_view->data['precio_mayor'];
								$_cargar->tags = ($_cont[$i][18]!='') ? $_guardar_tags : '';
								$_cargar->titulo_seo = ($_cont[$i][19]!='') ? htmlentities($_cont[$i][19]) : '';
								$_cargar->descripcion_seo = ($_cont[$i][20]!='') ? $_cont[$i][20] : '';				
								$_cargar->item = admin::crearItems(htmlentities($_cont[$i][4]));					
								$_cargar->identificador = $this->_sess->get('carga_actual');
								$_cargar->estado = ($_cont[$i][21] == 'si' ) ? 1 : 2;
								$_cargar->fecha = date('Y-m-d');
								$_cargar->save();


								if($_cont[$i][17]!=''){

									$_imgs = explode(';', $_cont[$i][17]);
									$_carpetaOrigen = $this->_conf['ruta_img_excel'];
									$_carpetaDestino = $this->_conf['ruta_img_cargadas'] . 'productos'. DS;

									foreach ($_imgs as $val) {										

										if(file_exists($_carpetaOrigen. $val)){	

											$_temp = explode(".", $val);
                                        	$_extension = end($_temp);
                                        	$_nombre_orig = $_temp[0];	

											
											if($val === reset($_imgs)){


												$foo = new upload($_carpetaOrigen. $val);
												$foo->file_new_name_body 		= $_nombre_orig;
												$foo->image_resize          	= true;
												$foo->jpeg_quality          	= 100;
												$foo->png_compression       	= 10;
												$foo->image_ratio_crop			= true;	
												// $foo->image_x = $this->_conf['formatos_img']['productos_principal_grandes']['ancho'];
												$foo->image_y = $this->_conf['formatos_img']['productos_principal_grandes']['alto'];	
												$tamañoOrig = admin::CapturarAnchoAlto($foo->file_src_pathname);
												$foo->image_x =  round(($foo->image_y/$tamañoOrig[0])*$tamañoOrig[1]);				
												$foo->process($_carpetaDestino . 'principal/grandes'. DS);

												if($foo->processed){

													$thumb = new upload($foo->file_dst_pathname);
													$thumb->image_resize = true;
													$thumb->image_ratio_crop = true;
													// $thumb->image_x = $this->_conf['formatos_img']['productos_principal_thumb']['ancho'];
													$thumb->image_y = $this->_conf['formatos_img']['productos_principal_thumb']['alto'];
													$tamañoOrig = admin::CapturarAnchoAlto($foo->file_dst_pathname);
													$thumb->image_x =  round(($thumb->image_y/$tamañoOrig[0])*$tamañoOrig[1]);
													$thumb->process($_carpetaDestino . 'principal/thumb'. DS);

													if($thumb->processed){
														// $foo->clean();
														// $thumb->clean()														

														$img = new contenidos_imagene();
														$img->nombre = $val;
														$img->identificador = $this->_sess->get('carga_actual');
														$img->path = $val;
														$img->posicion = 'principal';
														$img->tipo = 'productos';
														$img->orden = 0;
														$img->fecha_alt = date('Y-m-d');
														$img->save();

														unlink($_carpetaOrigen. $val);
													}


												}
												

											}else{

												$foo = new upload($_carpetaOrigen. $val);
												$foo->file_new_name_body 		= $_nombre_orig;
												$foo->image_resize          	= true;
												$foo->jpeg_quality          	= 100;
												$foo->png_compression       	= 10;
												$foo->image_ratio_crop			= true;	
												// $foo->image_x = $this->_conf['formatos_img']['productos_galeria_grandes']['ancho'];
												$foo->image_y = $this->_conf['formatos_img']['productos_galeria_grandes']['alto'];	
												$tamañoOrig = admin::CapturarAnchoAlto($foo->file_src_pathname);
												$foo->image_x =  round(($foo->image_y/$tamañoOrig[0])*$tamañoOrig[1]);				
												$foo->process($_carpetaDestino . 'galeria/grandes'. DS);

												if($foo->processed){

													$thumb = new upload($foo->file_dst_pathname);
													$thumb->image_resize = true;
													$thumb->image_ratio_crop = true;
													// $thumb->image_x = $this->_conf['formatos_img']['productos_galeria_thumb']['ancho'];
													$thumb->image_y = $this->_conf['formatos_img']['productos_galeria_thumb']['alto'];
													$tamañoOrig = admin::CapturarAnchoAlto($foo->file_dst_pathname);
													$thumb->image_x =  round(($thumb->image_y/$tamañoOrig[0])*$tamañoOrig[1]);
													$thumb->process($_carpetaDestino . 'galeria/thumb'. DS);

													if($thumb->processed){
														// $foo->clean();
														// $thumb->clean();														

														$img = new contenidos_imagene();
														$img->nombre = $val;
														$img->identificador = $this->_sess->get('carga_actual');
														$img->path = $val;
														$img->posicion = 'galeria';
														$img->tipo = 'productos';
														$img->orden = 0;
														$img->fecha_alt = date('Y-m-d');
														$img->save();

														unlink($_carpetaOrigen. $val);
													}


												}

																					

											}

										}


									}						
								}							

								
	                    	// }


								$this->_sess->destroy('carga_actual');
	                    	
	                    }


	                    
						 $this->redireccionar('administrador/productos');

						
	                }else{
						$this->redireccionar('error/access/404');				
					}
					
				}

			}else{
				$this->redireccionar('error/access/404');
			}	
		}
	
		$this->_view->titulo = 'Administrador - Seguimiento';
        $this->_view->renderizar('cargar_excel', 'productos');	
    }*/


   /* public function exportarexcel()
    {

    	$this->_acl->acceso('encargado_access');

		// $_datos = $this->_view->datos = $this->_trabajosGestion->traerProductos(1);
		$_datos = $this->_view->datos = $this->_trabajosGestion->traerTodosProductos();

		 // echo "<pre>";print_r($_datos);echo "</pre>";exit;

		$this->_view->filename = 'productos_'.date('d-m-Y').'.xls';	

		$this->_view->html ='<table border="1" cellpadding="10" cellspacing="0" bordercolor="#666666" style="border-collapse:collapse;">
		<tbody>
		<tr style="font-weight: bold;">		
		<td>ID Categoria</td>
		<td>ID Subcategoria</td>			
		<td>SKU</td>				
		<td>SKU variable</td>
		<td>Nombre</td>
		<td>Stock</td>	
		<td>Presentación</td>
		<td>Unidad de medida</td>			
		<td>Dimensión</td>				
		<td>Ubicación de producto</td>
		<td>Cantidad de inventario</td>
		<td>Políticas de inventario</td>	
		<td>Descripción</td>
		<td>Precio</td>			
		<td>Oferta</td>				
		<td>Impuesto</td>
		<td>Envío</td>
		<td>Imágenes</td>	
		<td>Tags</td>
		<td>Título SEO</td>
		<td>Descripción SEO</td>
		<td>Publicar</td>
		<td>Fecha</td>
		</tr>';

		foreach ($_datos as $data) {

			

			$_publicar = ($data['estado'] == 1) ? 'si' : 'no';
			

			$this->_view->html .='<tr>';
			$this->_view->html .='<td valign="top">'.$data['id_categoria'].'</td>';
			$this->_view->html .='<td valign="top">'.$data['id_subcategoria'].'</td>';
			$this->_view->html .='<td valign="top">'.$data['sku'].'</td>';
			$this->_view->html .='<td valign="top">'.$data['sku_variable'].'</td>';
			$this->_view->html .='<td valign="top">'.admin::convertirCaracteres($data['nombre']).'</td>';
			$this->_view->html .='<td valign="top">'.$data['stock'].'</td>';
			$this->_view->html .='<td valign="top">'.admin::convertirCaracteres($data['presentacion']).'</td>';
			$this->_view->html .='<td valign="top">'.$data['unidad_medida'].'</td>';
			$this->_view->html .='<td valign="top">'.$data['dimension'].'</td>';
			$this->_view->html .='<td valign="top">'.$data['ubicacion'].'</td>';
			$this->_view->html .='<td valign="top">'.$data['cantidad_inventario'].'</td>';
			$this->_view->html .='<td valign="top">'.$data['politicas_inventario'].'</td>';
			$this->_view->html .='<td valign="top">'.admin::convertirCaracteres($data['descripcion']).'</td>';
			$this->_view->html .='<td valign="top">'.$data['precio'].'</td>';
			$this->_view->html .='<td valign="top">'.$data['oferta'].'</td>';
			$this->_view->html .='<td valign="top">'.$data['impuesto'].'</td>';
			$this->_view->html .='<td valign="top">'.$data['envio'].'</td>';
			$this->_view->html .='<td valign="top">'. $data['imagenes'] .'</td>';
			$this->_view->html .='<td valign="top">'.$data['tags'].'</td>';
			$this->_view->html .='<td valign="top">'.admin::convertirCaracteres($data['titulo_seo']).'</td>';
			$this->_view->html .='<td valign="top">'.admin::convertirCaracteres($data['descripcion_seo']).'</td>';
			$this->_view->html .='<td valign="top">'.$_publicar.'</td>';
			$this->_view->html .='<td valign="top">'.$data['fecha'].'</td>';
	    	$this->_view->html .='</tr>';
		}
	
		
		$this->_view->html .='</tbody>
		</table>';


		$this->_view->titulo = 'Administrador - Seguimiento';
		$this->_view->renderizar('exportar', 'productos','vacio');



    }*/

	public function cargarvariables($_categoria = null)
    {	
		$this->_acl->acceso('encargado_access');
		//$this->_view->_categoria = (int) $_categoria;
	
		if(!$this->_sess->get('carga_actual')){
			$this->_sess->set('carga_actual', rand(1135687452,9999999999));
		}
		
		$this->_view->setCssPlugin(array('dropzone.min'));
		$this->_view->setJs(array('dropzone'));
		
		// $this->_view->pres = $this->_trabajosGestion->traerProdPresentaciones();
		// $this->_view->subcat = $this->_trabajosGestion->traerSubcategorias();
		// $this->_view->tags = $this->_trabajosGestion->traerTags();
		
		//$this->_sess->destroy('img_id');
		
	
		$this->_view->titulo = 'Administrador - Seguimiento';
        $this->_view->renderizar('cargarvariables', 'productos');	
    }


    public function consolidarCargarVariables()
    {	
		$this->_acl->acceso('encargado_access');		
		
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){	
			
			
				if($_POST['envio01'] == 1){
					
					$this->_view->data = $_POST;
					
				
					// echo "<pre>";print_r($_POST);exit;
					

					$_cargar = new contenidos_productos_variable();
					$_cargar->titulo = $this->_xss->xss_clean($_POST['titulo']);
					$_cargar->descripcion = $this->_xss->xss_clean($_POST['desc']);	
					$_cargar->precio = $this->_xss->xss_clean($_POST['precio']);		
					$_cargar->estado = 'alta';
					$_cargar->item = admin::crearItems($this->_xss->xss_clean($_POST['titulo']));
					$_cargar->identificador = $this->_sess->get('carga_actual');
					$_cargar->fecha = date('Y-m-d');
					// $_cargar->save();

					if($_cargar->save()){

						$_img_id =(isset($_POST['_seo_img'])) ? $_POST['_seo_img'] : 0;
						$_seo = $this->_trabajosGestion->cargarSeo($_cargar->id,
	                                                                'productos_variables',
	                                                                $_POST['_tipo'],
	                                                                $_POST['titulo_seo'],
	                                                                $_POST['desc_seo'],
	                                                            	$_img_id,
	                                                            	$this->_sess->get('carga_actual'));
					}
								
					
					$this->_sess->destroy('carga_actual');
					// $this->_sess->destroy('img_id');
					// $this->redireccionar('administrador/productos/variables');

					echo "Load Successful! Wait...
                    <script>
                        setTimeout(function(){  window.location.href = \"" . $this->_conf['base_url']. "administrador/productos/variables\"; }, 1000);
                    </script>";
				}	

			}else{
				$this->redireccionar('error/access/404');
			}
		}
	
    }
	
	public function editarvariables($_id)
	{
		//$this->_acl->acceso('admin_access');
		$this->_acl->acceso('encargado_access');
		
		validador::validarParametroInt($_id,$this->_conf['base_url']);	
		
		$this->_view->setCssPlugin(array('dropzone.min'));
		$this->_view->setJs(array('dropzone'));
		
		$this->_view->trabajo = $this->_trabajosGestion->traerProductoVariable($_id);


		if($this->_view->trabajo['identificador']==''){
			$this->_sess->set('edicion_actual', rand((int)1135687452,(int)999999999));
		}else{
			$this->_sess->set('edicion_actual', $this->_view->trabajo['identificador']);
		}

		// $this->_view->cat = $this->_trabajosGestion->traerCategorias();
		// $this->_view->subcat = $this->_trabajosGestion->traerSubcategorias();
		
		// $this->_view->tags = $this->_trabajosGestion->traerTags();
		// $this->_view->cantidad = $this->_view->trabajo['cantidad_inventario'] - $this->_view->trabajo['politicas_inventario'];
		
		// echo "<pre>";print_r($this->_view->pres);echo "</pre>";exit;
								
			
		$this->_view->titulo = 'Administrador - Seguimiento';
        $this->_view->renderizar('editarvariables', 'productos');	
    }
	
	
	public function consolidarEditarVariables()
	{
		//$this->_acl->acceso('admin_access');
		$this->_acl->acceso('encargado_access');		
		
			
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){	
			
			
				if($_POST['envio01'] == 1){
				
					$this->_view->data = $_POST;	


					 // echo "<pre>";print_r($this->_view->data);exit;
					
					
					/*if(isset($this->_view->data['eliminar_gal']) && $this->_view->data['eliminar_gal'][0]!=''){
						foreach($this->_view->data['eliminar_gal'] as $_gal){
							$this->_trabajosGestion->eliminarImgGal($_gal, $this->_conf['ruta_img_cargadas'], 'productos', 'galeria');			
						}
					}*/

					/*if($this->_view->data['tags']!=''){
						$_tags = explode(',', $this->_view->data['tags']);
						foreach ($_tags as $val) {
							$_hay = contenidos_tag::find(array('conditions' => array('nombre = ?', $val)));
							if(!$_hay){
								$tag = new contenidos_tag();
								$tag->nombre = $val;						
								$tag->save();
							}							
						}						
					}*/
					
					
					/*$fecha = date("Y-m-d", strtotime($this->_view->data['fecha_nacimiento']));
					$_fecha_separada = explode('-',$fecha);*/
					
					// $_subcat = (isset($this->_view->data['subcategoria'])) ? $this->_view->data['subcategoria']: '';			
					
					//echo $_dia_festivo ;
					//echo "<pre>";print_r($this->_view->data);exit;
					

					$_cargar = contenidos_productos_variable::find($_POST['_id']);
					$_cargar->titulo = $this->_xss->xss_clean($_POST['titulo']);
					$_cargar->descripcion = $this->_xss->xss_clean($_POST['desc']);	
					$_cargar->precio = $this->_xss->xss_clean($_POST['precio']);	
					$_cargar->item = admin::crearItems($this->_xss->xss_clean($_POST['titulo']));	
					$_cargar->identificador = $this->_sess->get('edicion_actual');
					// $_cargar->estado = $this->_xss->xss_clean($_POST['estado']);
					// $_cargar->fecha = date('Y-m-d');
					// $_cargar->save();

					if($_cargar->save()){

						$_img_id =(isset($_POST['_seo_img'])) ? $_POST['_seo_img'] : 0;
						$_seo = $this->_trabajosGestion->editarSeo($_POST['_id'],
	                                                                'productos_variables',
	                                                                $_POST['_tipo'],
	                                                                $_POST['titulo_seo'],
	                                                                $_POST['desc_seo'],
	                                                            	$_img_id,
	                                                            	$this->_sess->get('edicion_actual'));
					}
					
					$this->_sess->destroy('edicion_actual');
					// $this->_sess->destroy('img_id');
					/*if($this->_view->data['estado']=='alta'){
						$this->redireccionar('administrador/productos/fijos');
					}else{
						$this->redireccionar('administrador/productos/bajas');
					}*/
					
					// $this->redireccionar('administrador/productos/variables');

					echo "Load Successful! Wait...
                    <script>
                        setTimeout(function(){  window.location.href = \"" . $this->_conf['base_url']. "administrador/productos/variables\"; }, 1000);
                    </script>"; 
												
					
				}else{
					$this->redireccionar('error/access/404');
				}
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
				$_tipo = $_POST['_tipo'];
		

				$_borrar = $this->_trabajosGestion->borrarProducto($_id, $_tipo );
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

	public function buscador()
	{
		$this->_acl->acceso('encargado_access');
		
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){	
			
				$_val = $_POST['valor'];
				$_estado = $_POST['estado'];
				$_tipo = $_POST['tipo'];
				
				$_datos  = $this->_trabajosGestion->traerProdBuscador($_tipo, $_val);
				
				// echo "<pre>";print_r($_datos);echo"</pre>";exit;
				
				if($_datos ){

					$_html = '';
					foreach($_datos as $datos){ 

						// $titulo = unserialize($datos['titulo']);


						if($_tipo == 'fijos'){

							$_html .= '<div class="forum-item grid-item">
							            <div class="row">
							                <div class="col-md-10">
							                    
							                    <a href="" class="forum-item-title">
							                       '.admin::convertirCaracteres($datos['titulo']).'
							                    </a>
							                </div>

							                <div class="col-md-2 forum-info">
							                    <div class="tooltip-demo pull-right">						                    

							                        <a class="btn btn-warning" href="'. $this->_conf['url_enlace'].'administrador/productos/editar/'.$datos['id'].'">
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
								                            var url= _root_ + "administrador/productos/borrar";
								                            var dataString = "_id='.$datos['id'].'&_tipo=fijos&_csrf='.$this->_sess->get('_csrf').'";
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


						}else{

							$_html .= '<div class="forum-item grid-item">
							            <div class="row">
							                <div class="col-md-10">
							                    
							                    <a href="" class="forum-item-title">
							                       '.admin::convertirCaracteres($datos['titulo']).'
							                    </a>
							                </div>

							                <div class="col-md-2 forum-info">
							                    <div class="tooltip-demo pull-right">						                    

							                        <a class="btn btn-warning" href="'. $this->_conf['url_enlace'].'administrador/productos/editarvariables/'.$datos['id'].'">
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
								                            var url= _root_ + "administrador/productos/borrar";
								                            var dataString = "_id='.$datos['id'].'&_tipo=variables&_csrf='.$this->_sess->get('_csrf').'";
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
	
	public function seo_fijos()
    {
    	 $this->_acl->acceso('encargado_access');

    	 $this->_view->setCssPlugin(array('dropzone.min'));
		$this->_view->setJs(array('dropzone'));
		 		 

		$this->_view->seo = $this->_trabajosGestion->traerSeoSeccion('productos_fijos', 'seccion');

		if(isset($this->_view->seo['identificador']) && $this->_view->seo['identificador']!=''){
			$this->_sess->set('edicion_actual', $this->_view->seo['identificador']);
		}else{
			$this->_sess->set('edicion_actual', rand((int)1135687452,(int)999999999));
		}

		// echo "<pre>";print_r($this->_view->seo);exit;
        		
    	$this->_view->titulo = 'Administrador - Cargar SEO';
        $this->_view->renderizar('seo_fijos', 'productos');
    }


    public function seo_variables()
    {
    	 $this->_acl->acceso('encargado_access');

    	 $this->_view->setCssPlugin(array('dropzone.min'));
		$this->_view->setJs(array('dropzone'));
		 		 

		$this->_view->seo = $this->_trabajosGestion->traerSeoSeccion('productos_variables', 'seccion');

		if(isset($this->_view->seo['identificador']) && $this->_view->seo['identificador']!=''){
			$this->_sess->set('edicion_actual', $this->_view->seo['identificador']);
		}else{
			$this->_sess->set('edicion_actual', rand((int)1135687452,(int)999999999));
		}

		// echo "<pre>";print_r($this->_view->seo);exit;
        		
    	$this->_view->titulo = 'Administrador - Cargar SEO';
        $this->_view->renderizar('seo_variables', 'productos');
    }
	

	public function consolidarSeoFijos()
    {
    	$this->_acl->acceso('encargado_access');


        if($_POST){

            if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
				
				// echo "<pre>";print_r($_POST);exit;
				$_img_id =(isset($_POST['_seo_img'])) ? $_POST['_seo_img'] : 0;
				$_cargar = $this->_trabajosGestion->editarSeo($_POST['_id'],
                                                                'productos_fijos',
                                                                $_POST['_tipo'],
                                                                $_POST['titulo_seo'],
                                                                $_POST['desc_seo'],
	                                                            $_img_id,
	                                                        	$this->_sess->get('edicion_actual'));
				
				
				if($_cargar == true){	
                	
                    echo "Load Successful! Wait...
                    <script>
                        setTimeout(function(){  window.location.href = \"" . $this->_conf['base_url']. "administrador/productos/seo_fijos\"; }, 1000);
                    </script>"; 
                 
					
								
				}else{
					echo "Error";				
				}
				
				$this->_sess->destroy('edicion_actual');
				exit();
				
            } 
        }
		
    }
	
	
	public function consolidarSeoVariables()
    {
    	$this->_acl->acceso('encargado_access');


        if($_POST){

            if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
				
				// echo "<pre>";print_r($_POST);exit;
				$_img_id =(isset($_POST['_seo_img'])) ? $_POST['_seo_img'] : 0;
				$_cargar = $this->_trabajosGestion->editarSeo($_POST['_id'],
                                                                'productos_variables',
                                                                $_POST['_tipo'],
                                                                $_POST['titulo_seo'],
                                                                $_POST['desc_seo'],
	                                                            $_img_id,
	                                                        	$this->_sess->get('edicion_actual'));
				
				
				if($_cargar == true){	
                	
                    echo "Load Successful! Wait...
                    <script>
                        setTimeout(function(){  window.location.href = \"" . $this->_conf['base_url']. "administrador/productos/seo_variables\"; }, 1000);
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