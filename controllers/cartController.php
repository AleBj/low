<?php
use Nucleo\Controller\Controller;
use \Mailjet\Resources;

class cartController extends Controller
{
	public $homeGestion;
	// public $enviaya;
	//public $_cache;
	
    public function __construct()
	{
        parent::__construct();
		
		$this->getLibrary('class.home');		
		$this->homeGestion = new home();
		
		$this->getLibrary('class.validador');
		
		// $this->getLibrary('PHPMailerAutoload');
		// $this->envioMail = new PHPMailer();

		$this->getLibrary('AntiXSS');
		$this->_xss = new AntiXSS();

		 // echo "<pre>";print_r($_SESSION);echo "</pre>";//exit;
		
    }


    /*public function test()
    {
    	
    	$_compra = $this->homeGestion->traerOrder(1662);
    	$_data_pago = base64_decode($_compra['data_pago']);
		$_compra['data_pago'] =  json_decode(unserialize($_data_pago));
    	$_productos = $this->homeGestion->traerDatosCarrito($_compra['id']);
    	// $_cant_prod = (count($_productos)<=1) ? count($_productos)." ITEM" : count($_productos). " ITEMS";

    	// $_delivery = ($_compra['delivery']!=0.00) ? $_compra['subtotal'] + $_compra['delivery'] : $_compra['subtotal'];

    	if($_compra){					    	

    		
			$_body = '<!DOCTYPE html>
						<html lang="en">
						<head>
						    <meta charset="UTF-8">
						    <meta http-equiv="X-UA-Compatible" content="IE=edge">
						    <meta name="viewport" content="width=device-width, initial-scale=1.0">
						    <title>The Quick Divorce</title>
						</head>					


						<body>
						<h3>Payment information</h3>
						<table border="1" cellspacing="0" cellpadding="2">
	                        <tr align="center" bgcolor="#CCCCCC" style="font-weight:bold">
	                              <td>Payment method</td> 
	                              <td>Card Name</td> 
	                              <td>Card Brand</td>
	                              <td>Card Number</td> 
	                              <td>Receipt</td>
	                          </tr>
	                        <tr>
	                          <td>'.$_compra['data_pago']->source->funding.'</td>
	                          <td>'.$_compra['data_pago']->source->name.'</td>
	                          <td>'.$_compra['data_pago']->source->brand.'</td>
	                          <td>**** **** **** '.$_compra['data_pago']->payment_method_details->card->last4.'</td>
	                          <td><a href="'.$_compra['data_pago']->receipt_url.'" target="_blank">See the receipt</a></td>
	                      	</tr>

	                       
	                    </table>

						<h3>Product information</h3>

						<table border="1" cellspacing="0" cellpadding="2">
	                        <tr align="center" bgcolor="#CCCCCC" style="font-weight:bold">
	                              <td>Product</td> 
	                              <td>Quantity</td> 
	                              <td>Price</td> 
	                          </tr>';
	                        foreach($_productos as $prod){

		                       $_body .= '   <tr align="center">
		                              <td>'.admin::convertirCaracteres(admin::traerProductoPorTipoStatic($prod['id_producto'], $prod['tipo_producto'])->titulo).'</td>
		                               <td>'.$prod['cantidad'].'</td>
		                              <td>$'.number_format($prod['precio'], 2, ',', '.').'</td>
		                              
		                         </tr>';
		                    }

	                     $_body .= '<tr>
	                          <td class="total" align="right" colspan="3">Subtotal: $'.number_format($_compra['subtotal'], 2, '.', ',').'</td>
	                      </tr>

	                       <tr>
	                          <td class="total" align="right" colspan="3"><strong>Total: $'.number_format($_compra['total'], 2, '.', ',').'</strong></td>
	                      </tr>
	                    </table>
						
						</body>
						</html>';
        

        }


        require RAIZ.'vendor/autoload.php';

		$this->envioMail = new PHPMailer(true);

        $this->envioMail->From ='info@thequickdivorce.com';
		$this->envioMail->FromName ='The Quick Divorce';
        $this->envioMail->Subject = 'Thank you for your order!';               
        $this->envioMail->Body = $_body;
        // $this->envioMail->AddAddress($_compra['email']);
        $this->envioMail->IsHTML(true); 
        
        $exito = $this->envioMail->Send();
        
        $intentos=1;
        
        while ((!$exito) && ($intentos < 3)) {
            sleep(5);           
            $exito = $this->envioMail->Send();              
            $intentos=$intentos+1;          
        }
        
        if(!$exito) {           
            echo $this->envioMail->ErrorInfo;
            exit;               
        }else{


        	// Envio mail factura
        	
	    	$_productos = $this->homeGestion->traerDatosCarrito($_compra['id']);
	     	//$_shipp = $this->homeGestion->traerShipping($_compra['id_user']);
			// $_bill = $this->homeGestion->traerBilling($_compra['id_user']);
			// $_cant_prod = (count($_productos)<=1) ? count($_productos)." ITEM" : count($_productos). " ITEMS";

	    	if($_compra){
	    	

				$_body = '<!DOCTYPE html>
							<html lang="en">
							<head>
							    <meta charset="UTF-8">
							    <meta http-equiv="X-UA-Compatible" content="IE=edge">
							    <meta name="viewport" content="width=device-width, initial-scale=1.0">
							    <title>The Quick Divorce</title>
							</head>					


							<body>
							<h3>Payment information</h3>
							<table border="1" cellspacing="0" cellpadding="2">
		                        <tr align="center" bgcolor="#CCCCCC" style="font-weight:bold">
		                              <td>Payment method</td> 
		                              <td>Card Name</td> 
		                              <td>Card Brand</td>
		                              <td>Card Number</td> 
		                              <td>Receipt</td>
		                          </tr>
		                        <tr>
		                          <td>'.$_compra['data_pago']->source->funding.'</td>
		                          <td>'.$_compra['data_pago']->source->name.'</td>
		                          <td>'.$_compra['data_pago']->source->brand.'</td>
		                          <td>**** **** **** '.$_compra['data_pago']->payment_method_details->card->last4.'</td>
		                          <td><a href="'.$_compra['data_pago']->receipt_url.'" target="_blank">See the receipt</a></td>
		                      	</tr>

		                       
		                    </table>

							<h3>Product information</h3>

							<table border="1" cellspacing="0" cellpadding="2">
		                        <tr align="center" bgcolor="#CCCCCC" style="font-weight:bold">
		                              <td>Product</td> 
		                              <td>Quantity</td> 
		                              <td>Price</td> 
		                          </tr>';
		                        foreach($_productos as $prod){

			                       $_body .= '   <tr align="center">
			                              <td>'.admin::convertirCaracteres(admin::traerProductoPorTipoStatic($prod['id_producto'], $prod['tipo_producto'])->titulo).'</td>
			                               <td>'.$prod['cantidad'].'</td>
			                              <td>$'.number_format($prod['precio'], 2, ',', '.').'</td>
			                              
			                         </tr>';
			                    }

		                     $_body .= '<tr>
		                          <td class="total" align="right" colspan="3">Subtotal: $'.number_format($_compra['subtotal'], 2, '.', ',').'</td>
		                      </tr>

		                       <tr>
		                          <td class="total" align="right" colspan="3"><strong>Total: $'.number_format($_compra['total'], 2, '.', ',').'</strong></td>
		                      </tr>
		                    </table>
							
							</body>
							</html>';


				
            

            }


            

            $this->envioMail->From ='info@thequickdivorce.com';
    		$this->envioMail->FromName ='The Quick Divorce';
            $this->envioMail->Subject = 'New order N°'. $_compra['id'];               
            $this->envioMail->Body = $_body;
            // $this->envioMail->AddAddress($_compra['email']);
            $this->envioMail->IsHTML(true); 
            
            $exito = $this->envioMail->Send();
            
            $intentos=1;
            
            while ((!$exito) && ($intentos < 3)) {
                sleep(5);           
                $exito = $this->envioMail->Send();              
                $intentos=$intentos+1;          
            }
            
            if(!$exito) {           
                echo $this->envioMail->ErrorInfo;
                exit;               
            }


        }

    }
*/

    public function index()
	{	
					 

		if($this->_sess->get('_id_compra')){
			$this->_sess->destroy('_id_compra');
		}
		/*if($this->_sess->get('_carro')){
			$this->_sess->destroy('_carro');
		}*/
	
		if($this->_sess->get('autenticado_front')){
			$this->_view->user = home::traerUserPorId($this->_sess->get('id_usuario_front'));
		}

		// $this->_view->sucursal = $this->homeGestion->traerSucursal($_SESSION['_sucursal']);
		// $this->_view->grupo = $this->homeGestion->traerGrupo($this->_view->sucursal['id_grupo']);

		if(!$this->_sess->get('_carro')){
			$this->_view->empty_cart = true;
			// exit;
		}else if($_SESSION['_carro']){

			$this->_view->empty_cart = false;
			// $_cart = '<h2>Carrito de compra</h2>';
			$this->_view->_subtotal = 0;
			$this->_view->_total = 0;
			foreach($_SESSION['_carro'] as $id => $val){
			  
				$this->_view->_total_parcial = $val['cantidad']*$val['precio'];
				$this->_view->_subtotal += $this->_view->_total_parcial;
				$this->_view->_total += $this->_view->_total_parcial;

			}	

			if(isset($_SESSION['_new_product'])){
				$this->_view->_new_product = home::convertirCaracteres(home::traerProductoPorItemStatic($_SESSION['_new_product'])->titulo);
				$this->_sess->destroy('_new_product');
			}
			
				
		}


		

		// echo "<pre>";print_r($this->_view->user);echo "</pre>";exit;		


		// SEO
        $this->_view->seo = $this->homeGestion->traerSeoSeccion('home', 'seccion');
        $this->_view->titulo = $this->_view->seo['titulo'];
        $this->_view->description = trim(strip_tags($this->_view->seo['descripcion']));
         // $this->_view->canonical = $this->_view->seo['canonical'];
        $_ogimage = home::traerDataImagen($this->_view->seo['id_img']);
        if($_ogimage){
        	$_img_url = $this->_conf['base_url'].'public/img/subidas/seo/grandes/'.$_ogimage->path;
        }
       

        if(isset($_img_url)){
        	$this->_view->setMetas(array(
	            '<meta property="og:type" content="website" />
	            <meta property="og:title" content="'.$this->_view->titulo.'" />
	            <meta property="og:description" content="'.$this->_view->description.'" />
	            <meta property="og:url" content="'.home::getCurrentUrl().'" />
	            <meta property="og:site_name" content="A CONSCIOUS SEPARATION POWERED BY THE QUICK DIVORCE PLATFORM" />
	            <meta property="og:image" content="'.$_img_url.'" />'
        	));
			
        }else{
        	$this->_view->setMetas(array(
	            '<meta property="og:type" content="website" />
	            <meta property="og:title" content="'.$this->_view->titulo.'" />
	            <meta property="og:description" content="'.$this->_view->description.'" />
	            <meta property="og:url" content="'.home::getCurrentUrl().'" />
	            <meta property="og:site_name" content="A CONSCIOUS SEPARATION POWERED BY THE QUICK DIVORCE PLATFORM" />'
	        ));
			
        }
		
		// $this->_view->titulo = 'The Quick Divorce';	
		$this->_view->renderizar('cart','cart', 'default');
    }

    public function checkout()
	{	

		if(!$this->_sess->get('autenticado_front')){
			$this->redireccionar('cart/login');
		}else{


			if(!$this->_sess->get('_carro')){
				$this->redireccionar('cart');
				exit;
			}else{			 

				if($this->_sess->get('_id_compra')){
					$this->_sess->destroy('_id_compra');
				}
				/*if($this->_sess->get('_carro')){
					$this->_sess->destroy('_carro');
				}*/
			
				if($this->_sess->get('autenticado_front')){
					$this->_view->user = home::traerUserPorId($this->_sess->get('id_usuario_front'));
				}

				// $this->_view->sucursal = $this->homeGestion->traerSucursal($_SESSION['_sucursal']);
				// $this->_view->grupo = $this->homeGestion->traerGrupo($this->_view->sucursal['id_grupo']);


				if($_SESSION['_carro']){

					// $_cart = '<h2>Carrito de compra</h2>';
					$this->_view->_subtotal = 0;
					$this->_view->_total = 0;
					foreach($_SESSION['_carro'] as $id => $val){
					  
						$this->_view->_total_parcial = $val['cantidad']*$val['precio'];
						$this->_view->_subtotal += $this->_view->_total_parcial;
						$this->_view->_total += $this->_view->_total_parcial;

					}				

					
					
						
				}


			}
		}
		// echo "<pre>";print_r($this->_view->user);echo "</pre>";exit;		


		// SEO
        $this->_view->seo = $this->homeGestion->traerSeoSeccion('home', 'seccion');
        $this->_view->titulo = $this->_view->seo['titulo'];
        $this->_view->description = trim(strip_tags($this->_view->seo['descripcion']));
         // $this->_view->canonical = $this->_view->seo['canonical'];
        $_ogimage = home::traerDataImagen($this->_view->seo['id_img']);
        if($_ogimage){
        	$_img_url = $this->_conf['base_url'].'public/img/subidas/seo/grandes/'.$_ogimage->path;
        }
       

        if(isset($_img_url)){
        	$this->_view->setMetas(array(
	            '<meta property="og:type" content="website" />
	            <meta property="og:title" content="'.$this->_view->titulo.'" />
	            <meta property="og:description" content="'.$this->_view->description.'" />
	            <meta property="og:url" content="'.home::getCurrentUrl().'" />
	            <meta property="og:site_name" content="A CONSCIOUS SEPARATION POWERED BY THE QUICK DIVORCE PLATFORM" />
	            <meta property="og:image" content="'.$_img_url.'" />'
        	));
			
        }else{
        	$this->_view->setMetas(array(
	            '<meta property="og:type" content="website" />
	            <meta property="og:title" content="'.$this->_view->titulo.'" />
	            <meta property="og:description" content="'.$this->_view->description.'" />
	            <meta property="og:url" content="'.home::getCurrentUrl().'" />
	            <meta property="og:site_name" content="A CONSCIOUS SEPARATION POWERED BY THE QUICK DIVORCE PLATFORM" />'
	        ));
			
        }
		
		// $this->_view->titulo = 'The Quick Divorce';	
		$this->_view->renderizar('checkout','cart', 'default');
    }

    public function login()
	{

		// SEO
        $this->_view->seo = $this->homeGestion->traerSeoSeccion('home', 'seccion');
        $this->_view->titulo = $this->_view->seo['titulo'];
        $this->_view->description = trim(strip_tags($this->_view->seo['descripcion']));
         // $this->_view->canonical = $this->_view->seo['canonical'];
        $_ogimage = home::traerDataImagen($this->_view->seo['id_img']);
        if($_ogimage){
        	$_img_url = $this->_conf['base_url'].'public/img/subidas/seo/grandes/'.$_ogimage->path;
        }
       

        if(isset($_img_url)){
        	$this->_view->setMetas(array(
	            '<meta property="og:type" content="website" />
	            <meta property="og:title" content="'.$this->_view->titulo.'" />
	            <meta property="og:description" content="'.$this->_view->description.'" />
	            <meta property="og:url" content="'.home::getCurrentUrl().'" />
	            <meta property="og:site_name" content="A CONSCIOUS SEPARATION POWERED BY THE QUICK DIVORCE PLATFORM" />
	            <meta property="og:image" content="'.$_img_url.'" />'
        	));
			
        }else{
        	$this->_view->setMetas(array(
	            '<meta property="og:type" content="website" />
	            <meta property="og:title" content="'.$this->_view->titulo.'" />
	            <meta property="og:description" content="'.$this->_view->description.'" />
	            <meta property="og:url" content="'.home::getCurrentUrl().'" />
	            <meta property="og:site_name" content="A CONSCIOUS SEPARATION POWERED BY THE QUICK DIVORCE PLATFORM" />'
	        ));
			
        }

		// $this->_view->titulo = 'The Quick Divorce';	
		$this->_view->renderizar('login','cart', 'default');
	}


    public function llenarCarro()
	{
		if($_POST){


			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){

				// echo "<pre>";print_r($_POST);exit;

				//$_indice = $_POST['indice'];
				$_id = $_POST['_id'];
				$_precio = $_POST['_precio'];
				$_cantidad = $_POST['_cantidad'];
				$_item = $_POST['_item'];
				// $_sucursal = $_POST['_sucursal'];

				/*foreach($_SESSION['_carro'] as $id => $val){

					if($val['sucursal'] != $_SESSION['_sucursal']){
						unset($_SESSION['_carro'][$id]);
					}
				}*/

				if(isset($_SESSION['_carro'][$_id.'_'.$_item])){	
						
					$_SESSION['_carro'][$_id.'_'.$_item]['cantidad'] = $_SESSION['_carro'][$_id.'_'.$_item]['cantidad'] + $_cantidad;						
					
				}else{		
					
					$_SESSION['_carro'][$_id.'_'.$_item]=array(
						"cantidad" => $_cantidad,
						"precio" => $_precio,
						"item" => $_item,
					);
					
					$_SESSION['_new_product'] =$_item;
				}

				/*foreach($_SESSION['_carro'] as $id => $val){

					if($val['sucursal'] != $_SESSION['_sucursal']){
						unset($_SESSION['_carro'][$_id]);
					}

					if(isset($_SESSION['_codesc'])){
						unset($_SESSION['_codesc']);
					}
				}*/

				/*$_total =0;
				$_cart='';
				
				if($_SESSION['_carro']){

					$_cart = '<div class="row-table">
				                    <div class="prod head">Producto</div>
				                    <div class="sbt head">Subtotal</div>
				                </div>';
					
					foreach($_SESSION['_carro'] as $id => $val){

						// $_id = $id;
						// $idd = explode('_', $id);
						// $id = $idd[0];
					  
						$_subtotal = $val['cantidad']*$val['precio'];
						$_total += $_subtotal;

                        $_cart .= '<div class="row-table">
					                    <div class="prod">
					                        '.home::convertirCaracteres(home::traerCursoPorId($id)->nombre).'
					                    </div>
					                    <div class="sbt">
					                        $'.number_format($val['cantidad']*$val['precio'], 2, ',', '.').'
					                        <a onclick="$().eliminarProdPopUp(\''.$id.'\');" href="javascript:void(0);" class="fa fa-trash"></a>
					                    </div>
					                </div>';

					}


					if(!isset($_SESSION['_codesc'])){

						$_cart .= '<div class="row-table">
					                    <div class="cupon">
					                        <input type="text" id="cod_desc" name="cod_desc" maxlength="8" placeholder="Código de descuento">
					                        <button id="btCodDesc">Aplicar</button>
					                    </div>
					                </div>';

					    $_cart .= "<script>
					    			$('#btCodDesc').click(function(e){

								        e.preventDefault();
								        e.stopPropagation();

								        var _val = $('#cod_desc').val(); 

								        if(_val.length ==8){    

								            $('#_codesc').val(_val);                    
								 
								            $.ajax({
								                type: 'POST',
								                url: _root_+'cart/traerCodigoDescuento',
								                dataType: 'json',
								                data: {'_cod' : _val, '_csrf' : '".$this->_sess->get('_csrf')."'},             
								                beforeSend: function(){

								                },
								                success: function(data){

								                    if (data.total!='vacio') {
								                        $('.table .row-table .cupon').fadeOut(400);
								                        $('.table .row-table.desc .head.sbt').html(data.codesc);             
								                        $('.table .row-table.total .head.sbt').html(data.total);

								                    }else{
								                        alert('El código no existe');
								                    }
								                }
								            });          


								            return false;

								        }else{
								            return false;
								        }

								    });
								</script>";         

					}
					

					if(isset($_SESSION['_codesc'])){

						$_descod = ($_total*$_SESSION['_codesc'])/100;
                    	$_total = $_total - $_descod;

                    	$_cart .= '<div class="row-table desc">
					                    <div class="head">Descuento:</div>
					                    <div class="sbt head">$'.number_format($_descod, 2, ',', '.').'</div>
					                </div>';

					}else{

						$_cart .='<div class="row-table desc">
				                    <div class="head">Descuento:</div>
				                    <div class="sbt head"></div>
				                </div>';
					}
				

					$_cart .= '<div class="row-table total">
				                    <div class="head">Total:</div>
				                    <div class="sbt head">$'.number_format($_total, 2, ',', '.').'</div>
				                </div>';

                   $_cart .= '<div class="row-table buttons">
				                    <a href="#" class="wellBlue">Seguir comprando</a>
				                    <a href="'.$this->_conf['base_url'].'cart" class="wellBlue invert inner-link">Iniciar Compra</a>
				                </div>';
						
				}else{
					
					$_cart = '<div class="row-table">
				                    <div class="prod head">Producto</div>
				                    <div class="sbt head">Subtotal</div>
				                </div>';
					
					$_cart .= '<div class="row-table">
			                    <div class="prod">
			                        <h5>No items</h5>
			                    </div>                    
			                </div>';
				}*/
				
				
				
				// $jsondata['total'] = "$".number_format($_total, 2, '.', '');
				$jsondata['cart'] = 'ok';
				// $jsondata['cant_item'] = count($_SESSION['_carro']);
							
				echo json_encode($jsondata);
				exit;
			}
		}
	
	}
    
   
   

    public function consolidarRegistro()
    {
    	if($_POST){

    		// echo "<pre>";print_r($_POST);exit;	

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
			
			
					
					
					$this->_view->datos = $_POST;

					 // echo "<pre>";print_r($_POST);exit;					
					

					if(!validador::getPostParam('nombre')){
						// echo 'Debe introducir un nombre';
						$jsondata['estado'] = 'Debe introducir un nombre';
						echo json_encode($jsondata);
						exit;
					}

					if(!validador::getPostParam('apellido')){
						// echo 'Debe introducir un apellido';
						$jsondata['estado'] ='Debe introducir un apellido';
						echo json_encode($jsondata);
						exit;
					}

					if(!validador::getPostParam('dni')){
						// echo 'Debe introducir un DNI';
						$jsondata['estado'] ='Debe introducir un DNI';
						echo json_encode($jsondata);
						exit;
					}

					if(!validador::numerico($this->_view->datos['dni'])){					
						// echo 'Debe introducir un numero en DNI';
						$jsondata['estado'] = 'Debe introducir un numero en DNI';
						echo json_encode($jsondata);
						exit;
					}


					/*if(!validador::getPostParam('telefono')){
						echo 'Debe introducir un telefono';
						exit;
					}*/

					if(!validador::numerico($this->_view->datos['telefono'])){					
						// echo 'Debe introducir un numero en telefono';
						$jsondata['estado'] ='Debe introducir un numero en telefono';
						echo json_encode($jsondata);
						exit;
					}


					if(!validador::validarEmail($this->_view->datos['email'])){
						// echo 'Debe introducir un email valido';
						$jsondata['estado'] ='Debe introducir un email valido';
						echo json_encode($jsondata);
						exit;
					}										
					
					
					

					/*$roww = contenidos_user::find(array('conditions' => array('dni = ?', $this->_xss->xss_clean($this->_view->datos['dni']))));
	    			if($roww){

	    	 			//echo 'Ya hay un usuario registrado con ese DNI';
						// exit;

						$_nombre_completo = $roww->nombre.' '.$roww->apellido;
						// $this->_sess->destroy('carga_actual');
						$this->_sess->set('autenticado_front', true);
						$this->_sess->set('usuario_front', $_nombre_completo);
						$this->_sess->set('id_usuario_front', $roww->id);
						$this->_sess->set('_payment', $this->_view->datos['payment']);


						if($this->_sess->get('autenticado_front')){
							$_user = home::traerUserPorId($this->_sess->get('id_usuario_front'));
						}

	    			}else{

	    				

						$user = new contenidos_user();
						$user->nombre = $this->_xss->xss_clean(validador::getTexto('nombre'));
						$user->apellido = $this->_xss->xss_clean(validador::getTexto('apellido'));						
						$user->dni = $this->_xss->xss_clean($this->_view->datos['dni']);
						$user->telefono = $this->_xss->xss_clean($this->_view->datos['telefono']);
						$user->email = $this->_xss->xss_clean($this->_view->datos['email']);
						// $user->password = $this->_xss->xss_clean($this->_view->datos['pass']);
						// $user->password = hash('sha256', $this->_xss->xss_clean($this->_view->datos['pass']));
						$user->fecha = date('Y-m-d H:i:s');
						$user->save();


						$_nombre_completo = $user->nombre.' '.$user->apellido;
						// $this->_sess->destroy('carga_actual');
						$this->_sess->set('autenticado_front', true);
						$this->_sess->set('usuario_front', $_nombre_completo);
						$this->_sess->set('id_usuario_front', $user->id);
						$this->_sess->set('_payment', $this->_view->datos['payment']);


						if($this->_sess->get('autenticado_front')){
							$_user = home::traerUserPorId($this->_sess->get('id_usuario_front'));
						}


					}	*/


					$user = contenidos_user::find(array('conditions' => array('dni = ?', $this->_view->datos['dni'])));	
					if($user){

						$user->nombre = $this->_xss->xss_clean(validador::getTexto('nombre'));
						$user->apellido = $this->_xss->xss_clean(validador::getTexto('apellido'));						
						$user->dni = $this->_xss->xss_clean($this->_view->datos['dni']);
						$user->telefono = $this->_xss->xss_clean($this->_view->datos['telefono']);
						$user->email = $this->_xss->xss_clean($this->_view->datos['email']);
						// $user->password = $this->_xss->xss_clean($this->_view->datos['pass']);
						// $user->password = hash('sha256', $this->_xss->xss_clean($this->_view->datos['pass']));
						// $user->fecha = date('Y-m-d H:i:s');
						$user->save();


					}else{


						$user = new contenidos_user();
						$user->nombre = $this->_xss->xss_clean(validador::getTexto('nombre'));
						$user->apellido = $this->_xss->xss_clean(validador::getTexto('apellido'));						
						$user->dni = $this->_xss->xss_clean($this->_view->datos['dni']);
						$user->telefono = $this->_xss->xss_clean($this->_view->datos['telefono']);
						$user->email = $this->_xss->xss_clean($this->_view->datos['email']);
						// $user->password = $this->_xss->xss_clean($this->_view->datos['pass']);
						// $user->password = hash('sha256', $this->_xss->xss_clean($this->_view->datos['pass']));
						$user->fecha = date('Y-m-d H:i:s');
						$user->save();
					}

					$_nombre_completo = $user->nombre.' '.$user->apellido;
					// $this->_sess->destroy('carga_actual');
					$this->_sess->set('autenticado_front', true);
					$this->_sess->set('usuario_front', $_nombre_completo);
					$this->_sess->set('id_usuario_front', $user->id);
					$this->_sess->set('_payment', $this->_view->datos['payment']);


					if($this->_sess->get('autenticado_front')){
						$_user = home::traerUserPorId($this->_sess->get('id_usuario_front'));
					}


					// guarda data compra

					if($_SESSION['_carro']){

						$_total = 0;
						$_subtotal = 0;
						foreach($_SESSION['_carro'] as $id => $val){
						  
							$_total_parcial = $val['cantidad']*$val['precio'];
							$_subtotal += $_total_parcial;
							$_total += $_total_parcial;

						}				

						if(isset($_SESSION['_codesc'])){
							$_descod = ($_total*$_SESSION['_codesc'])/100;
		                    $_total = $_total - $_descod;
						}
						
							
					}


					// $_data_pago = serialize(json_encode($charge));
					// $_data_pago = base64_encode($_data_pago);



				    $_compra = new contenidos_compra();	
					$_compra->id_user = $this->_sess->get('id_usuario_front');
					$_compra->id_sucursal = $_SESSION['_sucursal'];
					$_compra->subtotal = $_subtotal;
					$_compra->descuento = (isset($_descod)) ? $_descod : 0;
					$_compra->total = $_total;	
					$_compra->plataforma_pago = $_SESSION['_payment'];
					$_compra->estado = 'sin procesar';
					$_compra->fecha = date('Y-m-d H:i:s');	
					$_compra->save();


					$_carrito = contenidos_compras_detall::find('all',array('conditions' => array('id_compra = ?', $_compra->id)));	
					if(!$_carrito){

						foreach($_SESSION['_carro'] as $key => $val){

							
							$_llenar_carro = new contenidos_compras_detall();
							$_llenar_carro->id_compra = $_compra->id;
							$_llenar_carro->id_curso = $key;
							$_llenar_carro->cantidad = $val['cantidad'];
							$_llenar_carro->precio = $val['precio'];
							$_llenar_carro->fecha = date('Y-m-d H:i:s');	
							$_llenar_carro->save();
						
						}
					}

					// $this->_view->_num_pedido = $_compra->id;
					$this->_sess->set('_id_compra', $_compra->id);



					if($_SESSION['_payment'] == 'transfer'){

						// $this->_view->sucursal = $this->homeGestion->traerSucursal($_SESSION['_sucursal']);
						// $this->_view->grupo = $this->homeGestion->traerGrupo($this->_view->sucursal['id_grupo']);

						$jsondata['estado'] = 'transfer';
						// echo 1;
						// exit;


					}elseif($_SESSION['_payment'] == 'mp'){

						$this->_view->sucursal = $this->homeGestion->traerSucursal($_SESSION['_sucursal']);
						$this->_view->grupo = $this->homeGestion->traerGrupo($this->_view->sucursal['id_grupo']);

						$_mp_access_token = $this->_view->grupo['mp_access_token'];


						include_once RAIZ."vendor/autoload.php";

					    MercadoPago\SDK::setAccessToken($_mp_access_token);
					    // MercadoPago\SDK::setAccessToken('TEST-5645556691721325-021209-2d1ef092f2dfbf59bd7c03e974d8e682__LB_LD__-18614912');

					    $preference = new MercadoPago\Preference();

					    $preference->back_urls = array(
					        "success" => $this->_conf['base_url']."cart/response",
					        "failure" => $this->_conf['base_url']."cart/response",
					        "pending" => $this->_conf['base_url']."cart/response"
					    );
					    $preference->auto_return = "approved";

					    $_datos_prod = array();

					    foreach($_SESSION['_carro'] as $key => $val){

					    	$item = new MercadoPago\Item();
						    $item->title = home::convertirCaracteres(home::traerCursoPorId($key)->nombre);
						    $item->quantity = $val['cantidad'];
						    $item->unit_price = $val['precio'];
						    $_datos_prod[] = $item;
						    // $preference->items = array($item);
					    }
					  
					    $preference->items = $_datos_prod;
					    $preference->save();				    
							
				
						$jsondata['prefer_id'] = $preference->id;
						$jsondata['estado'] = 'mp';
						// echo 2;
						// exit;

					}elseif($_SESSION['_payment'] == 'tp'){

						$this->_view->sucursal = $this->homeGestion->traerSucursal($_SESSION['_sucursal']);
						$this->_view->grupo = $this->homeGestion->traerGrupo($this->_view->sucursal['id_grupo']);

						$_tp_api_key = $this->_view->grupo['tp_api_key'];
						$_tp_merchant_id = $this->_view->grupo['tp_merchant_id'];
						// $_tp_api_key = 'TODOPAGO D2D4AAACA41E48979820B57536ABB35F';
						// $_tp_merchant_id = 949386;
						$_security_encod = explode(' ', $_tp_api_key);

						include_once RAIZ."vendor/todopago/php-sdk/vendor/autoload.php";

						//común a todas los métodos
						$mode = "prod";
						// $mode = "test";
						$http_header = array('Authorization'=> $_tp_api_key,
						 					'user_agent' => 'PHPSoapClient');

						//creo instancia de la clase TodoPago
						$connector = new TodoPago\Sdk($http_header, $mode);

						$_currencycode = 032;
						$_merchant = $_tp_merchant_id;
						$_enconding = 'XML';
						$_security = $_security_encod[1];

						//id de la operacion
						// $operationid = rand(0, 99999999);
						$operationid = $_compra->id;

						//opciones para el método sendAuthorizeRequest (datos propios del comercio)
						$optionsSAR_comercio = array(
							'Security'=> $_security,
							'EncodingMethod'=> $_enconding,
							'Merchant'=> $_merchant,
							'URL_OK'=> $this->_conf['base_url']."cart/responsetodopago?operationid=".$operationid,
							'URL_ERROR'=> $this->_conf['base_url']."cart/rejectedtodopago"
						);

						foreach($_SESSION['_carro'] as $key => $val){

							$_CSITPRODUCTCODE[] = home::convertirCaracteres(home::traerCursoPorId($key)->nombre);
							$_CSITPRODUCTDESCRIPTION[] = home::convertirCaracteres(home::traerCursoPorId($key)->nombre);    
							$_CSITPRODUCTNAME[] = home::convertirCaracteres(home::traerCursoPorId($key)->nombre);  
							$_CSITPRODUCTSKU[] = rand(0, 99999999);
							$_CSITTOTALAMOUNT[]= $val['precio'];
							$_CSITQUANTITY[] = $val['cantidad'];
							$_CSITUNITPRICE[] = $val['precio'];
						
						}

						// + opciones para el método sendAuthorizeRequest (datos propios de la operación) 
						$optionsSAR_operacion = array(
							'MERCHANT'=> $_merchant, //dato fijo (número identificador del comercio)
							'OPERATIONID'=> $operationid, //número único que identifica la operación
							'CURRENCYCODE'=> $_currencycode, //por el momento es el único tipo de moneda aceptada
							'AMOUNT'=> $_total,
							'CSBTCITY'=> "Buenos Aires",
							'CSSTCITY'=> "Buenos Aires",							
							'CSBTCOUNTRY'=> "AR",
							'CSSTCOUNTRY'=> "AR",							
							'CSBTEMAIL'=> $user->email,
							'CSSTEMAIL'=> $user->email,							
							'CSBTFIRSTNAME'=> $user->nombre,
							'CSSTFIRSTNAME'=> $user->nombre, 
							'CSBTLASTNAME'=> $user->apellido,
							'CSSTLASTNAME'=> $user->apellido,							
							'CSBTPHONENUMBER'=> $user->telefono,     
							'CSSTPHONENUMBER'=> $user->telefono,
							'CSBTPOSTALCODE'=> "1010",
							'CSSTPOSTALCODE'=> "1010",							
							'CSBTSTATE'=> "B",
							'CSSTSTATE'=> "B",							
							'CSBTSTREET1'=> "Cerrito 740",
							'CSSTSTREET1'=> "Cerrito 740",							
							'CSBTCUSTOMERID'=> $user->id,
							'CSBTIPADDRESS'=> home::getUserIpAddr(),       
							'CSPTCURRENCY'=> "ARS",
							'CSPTGRANDTOTALAMOUNT'=> number_format($_total, 2, '.', ''),
							'CSITPRODUCTCODE'=> implode('#',$_CSITPRODUCTCODE),
							'CSITPRODUCTDESCRIPTION'=> implode('#',$_CSITPRODUCTDESCRIPTION),    
							'CSITPRODUCTNAME'=> implode('#',$_CSITPRODUCTNAME),  
							'CSITPRODUCTSKU'=> implode('#',$_CSITPRODUCTSKU),
							'CSITTOTALAMOUNT'=> implode('#',$_CSITTOTALAMOUNT),
							'CSITQUANTITY'=> implode('#',$_CSITQUANTITY),
							'CSITUNITPRICE'=> implode('#',$_CSITUNITPRICE),
							);


						$rta = $connector->sendAuthorizeRequest($optionsSAR_comercio, $optionsSAR_operacion);

						if($rta['StatusCode'] != -1) {
							// var_dump($rta);
							$jsondata['error'] = $rta['StatusMessage'];
							// echo"sssd<pre>";print_r($rta);exit;
						} else {
							setcookie('RequestKey',$rta["RequestKey"],  time() + (86400 * 30), "/");
							$jsondata['url'] = $rta["URL_Request"];
							$jsondata['error'] = '';
							// header("Location: ".$rta["URL_Request"]);		
						}

						$jsondata['estado'] = 'tp';
						// echo json_encode($jsondata);	    				
	    	 			// exit;
						// echo 3;
						// exit;

					}
						

			            
					echo json_encode($jsondata);	    				
	    			exit;
										
					
				

			}else{
				// echo 'Hubo un error, vuelva a intentarlo mas tarde.';
				$jsondata['estado'] ='Hubo un error, vuelva a intentarlo mas tarde.';
				echo json_encode($jsondata);
				// $this->_view->_error = 'Hubo un error, vuelva a intentarlo mas tarde.';
				// $this->_view->renderizar('index','login');
				exit;
			}
		}

    }

    public function success()
	{	

		if(!$this->_sess->get('autenticado_front')){
			$this->redireccionar('cart');
			exit;
		}else{			 

		
			if($this->_sess->get('autenticado_front')){
				$this->_view->user = home::traerUserPorId($this->_sess->get('id_usuario_front'));
			}


			$this->_sess->destroy('_carro');
	        $this->_sess->destroy('_sucursal');
	        $this->_sess->destroy('_codesc');
	        $this->_sess->destroy('_payment');
	        // $this->_sess->destroy('_id_compra');

			
			/*$this->_view->_compra = $this->homeGestion->traerCompraPorId($this->_sess->get('_id_compra'));

			// echo "<pre>";print_r($_SESSION);echo "</pre>";exit;
			// echo "<pre>";print_r($this->_view->_compra);echo "</pre>";exit;


			if(!empty($this->_view->_compra['detalle_compra'])){

				$this->_view->_total = 0;
				foreach($this->_view->_compra['detalle_compra'] as $id => $val){
				  
					$this->_view->_total_parcial = $val['cantidad']*$val['precio'];
					$this->_view->_subtotal += $this->_view->_total_parcial;
					$this->_view->_total += $this->_view->_total_parcial;

				}				

				if(isset($this->_view->_compra['descuento']) && $this->_view->_compra['descuento'] > 0){
					// $this->_view->_descod = ($this->_view->_total*$_compra['descuento'])/100;
					$this->_view->_descod = $this->_view->_compra['descuento'];
                    $this->_view->_total = $this->_view->_total - $this->_view->_descod;
				}
				
					
			}


			// if($this->_view->_compra['plataforma_pago'] == 'transfer'){

				$this->_view->sucursal = $this->homeGestion->traerSucursal($this->_view->_compra['id_sucursal']);
				$this->_view->grupo = $this->homeGestion->traerGrupo($this->_view->sucursal['id_grupo']);
				$this->_view->_num_pedido = $this->_view->_compra['id'];
			// }	*/		

		  

			


		}

		// echo "<pre>";print_r($this->_view->sucursal);echo "</pre>";		
		
		// $this->_view->titulo = 'Drivers';
		$this->_view->renderizar('success','cart', 'default');
    }

    public function inprocess()
	{	

		if(!$this->_sess->get('autenticado_front')){
			$this->redireccionar();
			exit;
		}else{			 

		
			if($this->_sess->get('autenticado_front')){
				$this->_view->user = home::traerUserPorId($this->_sess->get('id_usuario_front'));
			}


			$this->_sess->destroy('_carro');
	        $this->_sess->destroy('_sucursal');
	        $this->_sess->destroy('_codesc');
	        $this->_sess->destroy('_payment');
	        // $this->_sess->destroy('_id_compra');

			
			$this->_view->_compra = $this->homeGestion->traerCompraPorId($this->_sess->get('_id_compra'));

			// echo "<pre>";print_r($_SESSION);echo "</pre>";exit;
			// echo "<pre>";print_r($this->_view->_compra);echo "</pre>";exit;


			if(!empty($this->_view->_compra['detalle_compra'])){

				$this->_view->_total = 0;
				foreach($this->_view->_compra['detalle_compra'] as $id => $val){
				  
					$this->_view->_total_parcial = $val['cantidad']*$val['precio'];
					$this->_view->_subtotal += $this->_view->_total_parcial;
					$this->_view->_total += $this->_view->_total_parcial;

				}				

				if(isset($this->_view->_compra['descuento']) && $this->_view->_compra['descuento'] > 0){
					// $this->_view->_descod = ($this->_view->_total*$_compra['descuento'])/100;
					$this->_view->_descod = $this->_view->_compra['descuento'];
                    $this->_view->_total = $this->_view->_total - $this->_view->_descod;
				}
				
					
			}


			// if($this->_view->_compra['plataforma_pago'] == 'transfer'){

				$this->_view->sucursal = $this->homeGestion->traerSucursal($this->_view->_compra['id_sucursal']);
				$this->_view->grupo = $this->homeGestion->traerGrupo($this->_view->sucursal['id_grupo']);
				$this->_view->_num_pedido = $this->_view->_compra['id'];
			// }			

		  

			


		}

		// echo "<pre>";print_r($this->_view->sucursal);echo "</pre>";		
		
		// $this->_view->titulo = 'Drivers';
		$this->_view->renderizar('inprocess','cart', 'default');
    }

    public function rejected()
	{	

		if(!$this->_sess->get('autenticado_front')){
			$this->redireccionar();
			exit;
		}else{			 

		
			if($this->_sess->get('autenticado_front')){
				$this->_view->user = home::traerUserPorId($this->_sess->get('id_usuario_front'));
			}


			$this->_sess->destroy('_carro');
	        $this->_sess->destroy('_sucursal');
	        $this->_sess->destroy('_codesc');
	        $this->_sess->destroy('_payment');
	        // $this->_sess->destroy('_id_compra');

			
			// $this->_view->_compra = $this->homeGestion->traerCompraPorId($this->_sess->get('_id_compra'));

			// echo "<pre>";print_r($_SESSION);echo "</pre>";exit;
			// echo "<pre>";print_r($this->_view->_compra);echo "</pre>";exit;		


		}

		// echo "<pre>";print_r($this->_view->sucursal);echo "</pre>";		
		
		// $this->_view->titulo = 'Drivers';
		$this->_view->renderizar('rejected','cart', 'default');
    }

    


	public function modificarCantProd()
	{
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
			
				$_id = $_POST['id'];
				// $_idd = explode('_', $_id);
				// $_id = $_idd.'_'.$_POST['presentacion'];
				$_cant = $_POST['cantidad'];
				$_tipo = $_POST['tipo'];
				
				
				$_SESSION['_carro'][$_id]['cantidad'] = $_cant;
				
				
				$jsondata['id'] = $_id;
				$jsondata['subtotal'] ="$".number_format($_SESSION['_carro'][$_id]['cantidad']*$_SESSION['_carro'][$_id]['precio'], 2, '.', '');
				
				// $_user = home::traerUserPorId($this->_sess->get('id_usuario_front'));
				
				
				/*foreach($_SESSION['_carro'] as $id => $val){
					$_subtotal = $val['cantidad']*$val['precio'];
					$_total += $_subtotal;
				}
				*/
				
				$_total =0;
				$_cart='';
				
				if($_SESSION['_carro']){

					// $_cart = '<h2>Carrito de compra</h2>';
					
					foreach($_SESSION['_carro'] as $id => $val){
					  
							$_subtotal = $val['cantidad']*$val['precio'];
							$_total += $_subtotal;
						 
					}	
					
					
						
				}else{
					// $_cart = '<h2>No hay productos</h2>';
				}


				// $_shipping = (count($_SESSION['_carro'])>1) ? '0.00' : '7.50';
				if(!empty($_SESSION['_carro'])){

					/*if(count($_SESSION['_carro'])<=5){
						$_shipping = '0.00';
					}else{*/

						$_cant_val=0;
						foreach($_SESSION['_carro'] as $id => $val){
					  
							$_cant_val += $val['cantidad'];												
					   
						}

						// $_shipping = ($_SESSION['_carro'][1]['cantidad']>5) ? '0.00' : '7.50';
						// $_shipping = ($_cant_val>5) ? '0.00' : '7.50';
					// }

				}

				// $_SESSION['_shipping'] = $_shipping; 
				// $jsondata['shipping'] = "$".number_format($_shipping, 2, '.', '');
				
		
				if($this->_sess->get('autenticado_front')){

					
						$_totalparcial = $_total;
						$jsondata['totalparcial'] = "$".number_format($_totalparcial, 2, '.', '');
						$jsondata['totalparcialval'] = number_format($_totalparcial, 2, '.', '');
						// $_total = $_total + $_shipping;
						$jsondata['total'] = "$".number_format($_total, 2, '.', '');
						

					

				}else{

					$_totalparcial = $_total;					
					$jsondata['totalparcial'] = "$".number_format($_totalparcial, 2, '.', '');
					// $_total = $_total + $_shipping;
					$jsondata['total'] = "$".number_format($_total, 2, '.', '');
				}
				
				// $jsondata['total'] = "$".number_format($_total, 2, ',', '.');
				// $jsondata['cart'] = $_cart;
							
				echo json_encode($jsondata);
				exit;
			}
		}
	
	}

	public function eliminarProd()
	{
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
			
				$_id = $_POST['id'];
				// $_idd = explode('_', $_id);
				// $_idd = $_idd[0];
				//$_cant = $_POST['cantidad'];
				unset($_SESSION['_carro'][$_id]);
				
				
				$_user = home::traerUserPorId($this->_sess->get('id_usuario_front'));

				
				
				/*$_SESSION['_carro'][$_id]['cantidad'] = $_cant;
				$jsondata['subtotal'] ="$".number_format($_SESSION['_carro'][$_id]['cantidad']*$_SESSION['_carro'][$_id]['precio'], 2, ',', '.');*/
				// $_cart='';
				$_total=0;

				if($_SESSION['_carro']){	

					// $_cart = '<h2>Carrito de compra</h2>'; 
					
					foreach($_SESSION['_carro'] as $id => $val){
					  
							$_subtotal = $val['cantidad']*$val['precio'];
							$_total += $_subtotal;						
					   
					}

						
				}

				// $_shipping = (count($_SESSION['_carro'])>1) ? '0.00' : '7.50';
				if(!empty($_SESSION['_carro'])){

					/*if(count($_SESSION['_carro'])<=5){
						$_shipping = '0.00';
					}else{*/
						
						$_cant_val=0;
						foreach($_SESSION['_carro'] as $id => $val){
					  
							$_cant_val += $val['cantidad'];												
					   
						}

						// $_shipping = ($_SESSION['_carro'][1]['cantidad']>5) ? '0.00' : '7.50';
						// $_shipping = ($_cant_val>5) ? '0.00' : '7.50';
					// }

				}else{
					// $_shipping = '0.00';
				}
				
				// $_SESSION['_shipping'] = $_shipping;
				// $jsondata['shipping'] = "$".number_format($_shipping, 2, '.', '');
				// $_SESSION['_shipping'] = $jsondata['shipping'];
				
				if($this->_sess->get('autenticado_front')){
				
					
					$_totalparcial = $_total;
					$jsondata['totalparcial'] = "$".number_format($_totalparcial, 2, '.', '');
					$jsondata['totalparcialval'] = number_format($_totalparcial, 2, '.', '');
					// $_total = $_total + $_shipping;
					$jsondata['total'] = "$".number_format($_total, 2, '.', '');
					


				}else{
					$_totalparcial = $_total;
					$jsondata['totalparcial'] = "$".number_format($_totalparcial, 2, '.', '');
					// $_total = $_total + $_shipping;
					$jsondata['total'] = "$".number_format($_total, 2, '.', '');
				}
				
				$jsondata['item'] = "#tr_".$_id;
				
				
							
				echo json_encode($jsondata);
				exit;
			}
		}
	
	}

	

	public function llenarCarro2()
	{
		if($_POST){


			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){

				// echo "<pre>";print_r($_POST);exit;

				//$_indice = $_POST['indice'];
				$_id = $_POST['_id'];
				$_precio = $_POST['_precio'];
				$_cantidad = $_POST['_cantidad'];

				if(isset($_SESSION['_carro'][$_id])){	
						
					$_SESSION['_carro'][$_id]['cantidad'] = $_SESSION['_carro'][$_id]['cantidad'] + $_cantidad;						
					
				}else{		
					
					$_SESSION['_carro'][$_id]=array(
						"cantidad" => $_cantidad,
						"precio" => $_precio,
					);
					
					
				}

				$_total =0;
				$_cart='';
				
				if($_SESSION['_carro']){

					$_cart = '<h2>Carrito de compra</h2>';
					
					foreach($_SESSION['_carro'] as $id => $val){
					  
							$_subtotal = $val['cantidad']*$val['precio'];
							$_total += $_subtotal;
						
					
						$_cart .= '<div id="tr_'.$id.'" class="product">';
						
							$_cantidad  = (home::traerProductoPorId($id)->cantidad_inventario) - (home::traerProductoPorId($id)->politicas_inventario);

							$_cart .= '<div class="img" style="background-image: url('.$this->_conf['base_url'] . 'public/img/subidas/productos/principal/thumb/' . home::traerImg(home::traerProductoPorId($id)->identificador,'productos','principal')->path .')"></div>
											<div class="detalle">
												<strong>'.home::traerProductoPorId($id)->nombre.'</strong> 
												<strong>'.home::traerProductoPorId($id)->presentacion.' '.home::traerProductoPorId($id)->unidad_medida.'</strong>
												
												<form action="" id="lpmfp">
													<label>cantidad: </label>
													<div class="qty" style="width:80px">
													<input type="number" class="cantidadheader" id="cantidadheader" name="cantidadheader_'.$id.'" value="'.$val['cantidad'].'" min="1" max="'.$_cantidad .'" readonly>
													<div class="quantity-nav"><div class="quantity-button quantity-up fa fa-caret-up"></div><div class="quantity-button quantity-down fa fa-caret-down"></div></div>
													</div>
												</form>
											</div>
											<div class="delete">
												<strong>$ '.number_format($val['cantidad']*$val['precio'], 2, '.', '').'</strong>
												<a onclick="$().eliminarProdPopUp('.$id.');" href="javascript:void(0);">Eliminar</a>
											</div>';


						
						$_cart .= '</div>';      
					}
					
								
					$_cart .= '<a href="'.$this->_conf['url_enlace'].'carrito" class="btn">Comprar</a>'; 
					$_cart .= '<a href="javascript:void(0);" onclick="$().seguirComprando();" class="btn gris">Seguir agregando al carrito</a>'; 	
					
						
				}else{
					$_cart = '<h2>No hay productos</h2>';
				}
				
				
				
				$jsondata['total'] = "$".number_format($_total, 2, '.', '');
				$jsondata['cart'] = $_cart;
				$jsondata['cant_item'] = count($_SESSION['_carro']);
				// $jsondata['cant_item'] = (count($_SESSION['_carro']) < 10) ? '0'.count($_SESSION['_carro']) : count($_SESSION['_carro']);
							
				echo json_encode($jsondata);
				exit;
			}
		}
	
	}

	public function modificarCantProdHeader()
	{
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
			
				$_id = $_POST['id'];
				// $_idd = explode('_', $_id);
				// $_id = $_idd.'_'.$_POST['presentacion'];
				$_cant = $_POST['cantidad'];
				$_tipo = $_POST['tipo'];
				// $_cod = $_POST['codesc'];
				
				$_SESSION['_carro'][$_id]['cantidad'] = $_cant;
				
				
				$jsondata['id'] = $_id;
				$jsondata['subtotal'] ="$".number_format($_SESSION['_carro'][$_id]['cantidad']*$_SESSION['_carro'][$_id]['precio'], 2, ',', '.');
				
				$_user = home::traerUserPorId($this->_sess->get('id_usuario_front'));
				// $_codigo = home::traerCodigoDescuentoPorId($_user->codigo_descuento);

								
				$_total =0;
				$_cart='';
				



				if($_SESSION['_carro']){

					$_cart = '<div class="user">
                                <div class="name">
                                    <h5>'.$this->_conf['text_lang']['header']['carrito'][$_SESSION['_lang']]['titulo'].'</h5>
                                </div>
                            </div>';
					
					foreach($_SESSION['_carro'] as $id => $val){

						$_id = $id;
						// $idd = explode('_', $id);
						// $id = $idd[0];
					  
						$_subtotal = $val['cantidad']*$val['precio'];
						$_total += $_subtotal;
						// $_pres = $val['presentacion'];
						// $_nombre['name'] = unserialize(home::traerCursoPorId($id)->nombre);
						
					
						$_cart .= '<div id="tr_'.$_id.'" class="list">
                                <div class="item">
                                    <div class="row">
                                        <p class="col">Product</p>
                                        <div class="imageProduct col">
                                            <img src="'.$this->_conf['base_url'] . 'public/img/subidas/cursos/principal/grandes/' . home::traerImg(home::traerCursoPorId($id)->identificador,'cursos','principal')->path.'">
                                        </div>
                                        <div class="name col">
                                            <p>'.home::convertirCaracteres(home::traerCursoPorId($id)->nombre).'</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <p class="col">Qty</p>
                                        <div class="qty col">
                                            <input type="number" class="cantidadheader" id="cantidadheader" name="cantidadheader_'.$_id.'" value="'.$val['cantidad'].'" min="1" max="'.home::traerCursoPorId($id)->cantidad.'" name="qty"/>                                            
                                        </div>
                                        <div class="price col">
                                            <p>Price</p>
                                            <span>$'.number_format($val['cantidad']*$val['precio'], 2, '.', '').'</span>
                                            <div class="delete col">
                                                <a onclick="$().eliminarProdPopUp(\''.$_id.'\');" href="javascript:void(0);" class="btn">
                                                  <img src="'.$this->_conf['base_url'] . 'views/layout/default/img/icons/trash.svg" alt="">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>';

					}
					
				

					$_cart .= '<div class="total">
			                        <h5>Total <span>$'.number_format($_total, 2, '.', '').'</span></h5>
			                    </div>';

                   $_cart .= '<div class="last-link">
		                        <a class="btn btn-verde bordered d-block" href="'.$this->_conf['base_url'].'cart">Go to checkout</a>
		                    </div>';
						
				}else{
					$_cart = '<div class="user">
                                <div class="name">
                                    <h5>'.$this->_conf['text_lang']['header']['carrito'][$_SESSION['_lang']]['titulo'].'</h5>
                                </div>
                            </div>';
					$_cart .= '<h4>No items</h4>';
				}
				
				// $jsondata['total'] = "$".number_format($_total, 2, ',', '.');
				$jsondata['cart'] = $_cart;
							
				echo json_encode($jsondata);
				exit;
			}
		}
	
	}
	
	
	public function eliminarProdHeader()
	{
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
			
				$_id = $_POST['id'];
				$_idd = explode('_', $_id);
				$_idd = $_idd[0];
				unset($_SESSION['_carro'][$_id]);
				
				$_user = home::traerUserPorId($this->_sess->get('id_usuario_front'));
				
				$_cart='';
				$_total=0;

				

				if($_SESSION['_carro']){

					$_cart = '<div class="row-table">
				                    <div class="prod head">Producto</div>
				                    <div class="sbt head">Subtotal</div>
				                </div>';
					
					foreach($_SESSION['_carro'] as $id => $val){

						// $_id = $id;
						// $idd = explode('_', $id);
						// $id = $idd[0];
					  
						$_subtotal = $val['cantidad']*$val['precio'];
						$_total += $_subtotal;

                        $_cart .= '<div class="row-table">
					                    <div class="prod">
					                        '.home::convertirCaracteres(home::traerCursoPorId($id)->nombre).'
					                    </div>
					                    <div class="sbt">
					                        $'.number_format($val['cantidad']*$val['precio'], 2, ',', '.').'
					                        <a onclick="$().eliminarProdPopUp(\''.$id.'\');" href="javascript:void(0);" class="fa fa-trash"></a>
					                    </div>
					                </div>';

					}


					if(!isset($_SESSION['_codesc'])){

						$_cart .= '<div class="row-table">
					                    <div class="cupon">
					                        <input type="text" id="cod_desc" name="cod_desc" maxlength="8" placeholder="Código de descuento">
					                        <button id="btCodDesc">Aplicar</button>
					                    </div>
					                </div>';

					}
					

					if(isset($_SESSION['_codesc'])){

						$_descod = ($_total*$_SESSION['_codesc'])/100;
                    	$_total = $_total - $_descod;

                    	$_cart .= '<div class="row-table desc">
					                    <div class="head">Descuento:</div>
					                    <div class="sbt head">$'.number_format($_descod, 2, ',', '.').'</div>
					                </div>';

					}else{

						$_cart .='<div class="row-table desc">
				                    <div class="head">Descuento:</div>
				                    <div class="sbt head"></div>
				                </div>';
					}
				

					$_cart .= '<div class="row-table total">
				                    <div class="head">Total:</div>
				                    <div class="sbt head">$'.number_format($_total, 2, ',', '.').'</div>
				                </div>';

                   $_cart .= '<div class="row-table buttons">
				                    <a href="#" class="wellBlue">Seguir comprando</a>
				                    <a href="'.$this->_conf['base_url'].'cart" class="wellBlue invert inner-link">Iniciar Compra</a>
				                </div>';
						
				}else{

					unset($_SESSION['_codesc']);

					$_cart = '<div class="row-table">
				                    <div class="prod head">Producto</div>
				                    <div class="sbt head">Subtotal</div>
				                </div>';
					
					$_cart .= '<div class="row-table">
			                    <div class="prod">
			                        <h5>No items</h5>
			                    </div>                    
			                </div>';
				}
				
				
				
				$jsondata['cart'] = $_cart;
				$jsondata['cant_item'] = count($_SESSION['_carro']);
							
				echo json_encode($jsondata);
				exit;
			}
		}
	
	}


	public function traerCodigoDescuento()
	{

		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){

				$_cod = $_POST['_cod'];
				$_codigo = $this->homeGestion->traerCodigoDescuento($_cod);
				

				if($_codigo && $_codigo['descuento']!=0){		

					/*$user = contenidos_user::find($this->_sess->get('id_usuario_front'));
					if($user){
						$user->codigo_descuento = $_codigo['id'];
						$user->save();
					}*/		

					$_SESSION['_codesc'] = $_codigo['descuento'];

					$_total =0;
					$_cart='';
					// $_user = home::traerUserPorId($this->_sess->get('id_usuario_front'));
					
					if($_SESSION['_carro']){

						// $_cart = '<h2>Carrito de compra</h2>';
						
						foreach($_SESSION['_carro'] as $id => $val){
						  
							/*if($val['promocion']!=0){
								$_subtotal = $val['precio'];
								$_total += $_subtotal;
							}else{*/
								$_subtotal = $val['cantidad']*$val['precio'];
								$_total += $_subtotal;
							// }			
							
						}
							
					}



					

					$_codesc = ($_total*$_codigo['descuento'])/100;
					$_total = $_total - $_codesc;
					// $jsondata['codesc'] = "$".number_format($_codesc, 2, ',', '.');
					$jsondata['codesc'] = "$".number_format($_codesc, 2, ',', '.');
					$jsondata['total'] = "$".number_format($_total, 2, ',', '.');

					// $jsondata['codescval'] = number_format($_codesc, 2, ',', '.');
					// $jsondata['descuserval'] = number_format($_desc_user, 2, ',', '.');

					

				}else{
					$jsondata['total'] = 'vacio';
				}
				
				// $jsondata['total'] = "$".number_format($_total, 2, ',', '.');
				// $jsondata['cart'] = $_cart;
							
				echo json_encode($jsondata);
				exit;


			}
		}

	}



	///////////////////////////////////////////////////////////////////////////////////////////

	

	public function procesarPayment()
	{
		
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){


				if(!$this->_sess->get('autenticado_front')){
					echo "no";
					exit;
				}else{	

					 // echo "<pre>";print_r($_POST);exit;
					// echo "<pre>";print_r($_SESSION);echo "</pre>";exit;

					$this->_view->datos = $_POST;

					
					$_total =0;
					$_total_part =0;
					foreach($_SESSION['_carro'] as $id => $val){

						$_id = $id;
						$idd = explode('_', $id);
						$id = $idd[0];
					  
						$_subtotal = $val['cantidad']*$val['precio'];
						$_total_part += $_subtotal;
						// $_nombre['name'] = unserialize(home::traerProductoPorId($id)->nombre);


					}

					$_total = $_total_part;

					require dirname(__FILE__) . '/../libs/stripe/vendor/stripe-php-master/init.php';

					
					\Stripe\Stripe::setApiKey($this->_conf['stripe']['private_key']);

					// convertir a centavos
					$amount = intval($_total * 100);

					$token = $_POST["token"];

					

					

					try {

						$charge = \Stripe\Charge::create([
					      "amount" => $amount, //va en centavos
					      "currency" => "usd",
					      "description" => "Online Payment",
					      "source" => $token,					      
						  // "shipping" => $_shipping_array,						  
						]);

						// echo "<pre>";print_r($charge);exit;


						$user = contenidos_user::find(array('conditions' => array('id = ?', $this->_sess->get('id_usuario_front'))));	
						if($user){

							$user->nombre = $this->_xss->xss_clean(validador::getTexto('nombre'));
							$user->apellido = $this->_xss->xss_clean(validador::getTexto('apellido'));						
							$user->email = $this->_xss->xss_clean($this->_view->datos['email']);
							$user->telefono = $this->_xss->xss_clean($this->_view->datos['telefono']);
							// $user->company_name = $this->_xss->xss_clean($this->_view->datos['company_name']);
							$user->pais = $this->_xss->xss_clean(validador::getTexto('pais'));
							$user->state = $this->_xss->xss_clean(validador::getTexto('estado'));
							$user->ciudad = $this->_xss->xss_clean(validador::getTexto('ciudad'));
							$user->direccion = $this->_xss->xss_clean(validador::getTexto('direccion'));
							$user->cod_postal = $this->_xss->xss_clean($this->_view->datos['cod_postal']);
							$user->order_notes = $this->_xss->xss_clean($this->_view->datos['order_notes']);
							$user->estado = 'customer';
							$user->save();
						}

						

					    $_data_pago = serialize(json_encode($charge));
        				$_data_pago = base64_encode($_data_pago);

					    /*$_compra = new contenidos_compra();	
						$_compra->id_user = $this->_sess->get('id_usuario_front');
						$_compra->subtotal = $_total_part;
						$_compra->delivery = $_SESSION['_shipping'];
						// $_compra->taxes = 0;
						$_compra->total = $_total;	
						$_compra->delivery_method = 'USPS Priority Mail 2-day delivery';
						$_compra->payment_method = $charge->payment_method_details->card->brand;
						$_compra->tipo_direccion = $_SESSION['_direccion']['tipo'];
						$_compra->id_direccion = $_SESSION['_direccion']['id'];
						$_compra->data_pago = $_data_pago;
						$_compra->estado = 'aprobado';
						$_compra->fecha = date('Y-m-d H:i:s');	
						$_compra->save();*/


						$_compra = new contenidos_compra();	
						$_compra->id_user = $this->_sess->get('id_usuario_front');
						$_compra->subtotal = $_total_part;
						// $_compra->delivery = $_SESSION['_shipping'];
						// $_compra->taxes = 0;
						$_compra->total = $_total;	
						// $_compra->delivery_method = 'USPS Priority Mail 2-day delivery';
						$_compra->metodo_pago = $charge->payment_method_details->card->brand;
						// $_compra->tipo_direccion = $_SESSION['_direccion']['tipo'];
						// $_compra->id_direccion = $_SESSION['_direccion']['id'];
						$_compra->data_pago = $_data_pago;
						$_compra->estado = 'pending';
						$_compra->fecha = date('Y-m-d H:i:s');	
						$_compra->save();



						/*$_carrito = contenidos_compras_detall::find('all',array('conditions' => array('id_compra = ?', $_compra->id)));	
						if(!$_carrito){

							foreach($_SESSION['_carro'] as $key => $val){

								$_cart_id = $key;
								$_carro_idd = explode('_', $key);
								$_carro_id = $_carro_idd[0];

									
								$_llenar_carro = new contenidos_compras_detall();
								$_llenar_carro->id_compra = $_compra->id;
								$_llenar_carro->id_producto = $_carro_id;
								$_llenar_carro->cantidad = $val['cantidad'];
								$_llenar_carro->presentacion = $val['presentacion'];
								$_llenar_carro->precio = $val['precio'];
								$_llenar_carro->fecha = date('Y-m-d H:i:s');	
								$_llenar_carro->save();
							
							}
						}*/


						$_carrito = contenidos_compras_detall::find('all',array('conditions' => array('id_compra = ?', $_compra->id)));	
						if(!$_carrito){

							foreach($_SESSION['_carro'] as $key => $val){

								$_cart_id = $key;
								$_carro_idd = explode('_', $key);
								$_carro_id = $_carro_idd[0];						

									
								$_llenar_carro = new contenidos_compras_detall();
								$_llenar_carro->id_compra = $_compra->id;
								$_llenar_carro->id_producto = $_carro_id;
								$_llenar_carro->cantidad = $val['cantidad'];							
								$_llenar_carro->precio = $val['precio'];
								if($val['item'] == 'ppa' || $val['item'] == 'msa' || $val['item']== 'msappa'){
									$_llenar_carro->tipo_producto = 'fijo';
								}else{
									$_llenar_carro->tipo_producto = 'variable';
								}
								
								$_llenar_carro->fecha = date('Y-m-d H:i:s');	
								$_llenar_carro->save();

								if($val['item'] == 'ppa' || $val['item'] == 'msa' || $val['item']== 'msappa'){

									$_fecha_exp = date('Y-m-d', strtotime(date('Y-m-d') . ' +6 months'));	

									$_form = new contenidos_forms_respuesta();	
									$_form->id_user = $this->_sess->get('id_usuario_front');
									$_form->id_compra = $_compra->id;
									$_form->id_producto = $_carro_id;
									$_form->estado = 'not started';
									$_form->fecha_aviso_1 = date('Y-m-d', strtotime($_fecha_exp  . ' -7 day'));
									$_form->fecha_aviso_2 = date('Y-m-d', strtotime($_fecha_exp  . ' -2 day'));
									$_form->fecha_expiracion =  $_fecha_exp;	
									$_form->fecha = date('Y-m-d H:i:s');	
									$_form->save();


									$_aviso = new contenidos_forms_aviso();	
									$_aviso->id_form = $_form->id;
									$_aviso->fecha = date('Y-m-d');	
									$_aviso->save();


								}
							
							}
						}

					    // echo "<pre>"; print_r($charge);exit;
					    // echo $charge->outcome->network_status;
					    // echo $paymentIntent->client_secret;
					    // echo json_encode($charge); exit;   



					    if($charge->outcome->network_status == 'approved_by_network'){
					    	// echo 'La operacion se realizo con exito';
					    	$_SESSION['_numero_compra'] = $_compra->id;
					    	// echo 'ok';

					    	$_status = contenidos_compra::find(array('conditions' => array('id = ?', $_compra->id)));	
					    	if($_status){
					    		$_status->estado = 'approved';
								$_status->save();
					    	}


					    	// Envio mail de compra

					    	$_compra2 = $this->homeGestion->traerOrder2($_compra->id);
					    	$_data_pago = base64_decode($_compra2['data_pago']);
							$_compra2['data_pago'] =  json_decode(unserialize($_data_pago));
					    	$_productos = $this->homeGestion->traerDatosCarrito($_compra2['id']);

					    	// echo "<pre>";print_r($_compra2);exit;

					    	if($_compra2){	


					    		$_body = '<!DOCTYPE html>
									<html lang="en">
									<head>
									    <meta charset="UTF-8">
									    <meta http-equiv="X-UA-Compatible" content="IE=edge">
									    <meta name="viewport" content="width=device-width, initial-scale=1.0">
									    <link rel="preconnect" href="https://fonts.gstatic.com">									    
									    <title>The Quick Divorce</title>
									</head>

									<body style="margin: auto;width: 50%;padding:0;box-sizing: border-box;min-width: 600px;color: #000;font-family: Arial, Helvetica, sans-serif;">

									   <header class="header" style="height: 100px;background-color: #162536;text-align: center;">
									       <img src="'.$this->_conf['base_url'].'views/layout/default/img/TQD_logo.png" alt="" style="width: 340px;margin-top: 10px;">
									   </header> 

									   <div class="container" style="background-color: #f2f0e2;">

									        <div class="intro-text" style="text-align: center;">
									            <h3 class="text-big" style="margin: 0;padding-top:10px">THANK YOU!</h3>
									            <p class="text-small" style="text-align:center;">Click the button below to begin filling out your form.</p>
									            <button onclick="'.$this->_conf['base_url'].'user/myorders" type="button" style="width: 190px;height: 40.5px;padding:4px 5px;background:#ffffff;border-radius: 4px;position: relative; display: flex;justify-content: center;align-items: center;margin:25px auto 0;z-index: 2;box-shadow: 3px 5px 10px rgba(0,0,0,0.2);transition:all 0.36s ease-in-out;border: none;outline: 0;border: 0;background: #c5d7de;cursor: pointer;"><p style="height: 100%; width: 100%;display: flex;border:solid 1px #526074;color:#1d2731; font-family: \'gothambold\', sans-serif; font-weight: bold;    font-size: 13px;text-align: center; text-transform: uppercase;display: flex;justify-content: center;align-items: center;border-color: #ffffff;">GET STARTED</p></button>
									            <h3 class="text-medio">ORDER N&deg; '.$_compra2['id'].'</h3>
									            <p class="text-small">'.home::convertirCaracteres($this->_sess->get('usuario_front')).', thank you! We are processing your order.</p>
									            <p class="text-small">Date: '.date('d M o', strtotime($_compra2['fecha'])).'</p>
									        </div>

									        <div class="item" style="background-color: white;margin: 20px 50px;padding: 15px 30px;">
									            <h3>Payment information</h3>
									            <table width="100%" border="1" cellspacing="0" cellpadding="2">
									                <tr align="center" bgcolor="#CCCCCC" style="font-weight:bold">
									                      <td>Payment method</td> 
									                      <td>Card Name</td> 
									                      <td>Card Brand</td>
									                      <td>Card Number</td> 
									                      <td>Receipt</td>
									                  </tr>
									                <tr>
									                  <td>'.$_compra2['data_pago']->source->funding.'</td>
									                  <td>'.$_compra2['data_pago']->source->name.'</td>
									                  <td>'.$_compra2['data_pago']->source->brand.'</td>
									                  <td>**** **** **** '.$_compra2['data_pago']->payment_method_details->card->last4.'</td>
									                  <td><a href="'.$_compra2['data_pago']->receipt_url.'" target="_blank">See the receipt</a></td>
									                </tr>

									               
									            </table>
									        </div>

									        <div class="item" style="background-color: white;margin: 20px 50px;padding: 15px 30px;">
									            <h3>Product information</h3>

									            <table border="1" cellspacing="0" cellpadding="2">
									                <tr align="center" bgcolor="#CCCCCC" style="font-weight:bold">
									                      <td>Product</td> 
									                      <td>Quantity</td> 
									                      <td>Price</td> 
									                  </tr>';
									                foreach($_productos as $prod){

									                   $_body .= '   <tr align="center">
									                          <td>'.home::convertirCaracteres(home::traerProductoPorTipoStatic($prod['id_producto'], $prod['tipo_producto'])->titulo).'</td>
									                           <td>'.$prod['cantidad'].'</td>
									                          <td>$'.number_format($prod['precio'], 2, ',', '.').'</td>
									                          
									                     </tr>';
									                }

									             $_body .= '<tr>
									                  <td class="total" align="right" colspan="3">Subtotal: $'.number_format($_compra2['subtotal'], 2, '.', ',').'</td>
									              </tr>

									               <tr>
									                  <td class="total" align="right" colspan="3"><strong>Total: $'.number_format($_compra2['total'], 2, '.', ',').'</strong></td>
									              </tr>
									            </table>
									        </div>

									        <div class="" style="text-align: center; background-color: #162536;padding: 20px 0;"></div>
									   </div>
									</body>
									</html>';				    	

					    		
								
				            

				            }


				            require RAIZ.'vendor/autoload.php';
		            		

				            // use \Mailjet\Resources;
							$mj = new \Mailjet\Client($this->_conf['smtp']['api_key'],$this->_conf['smtp']['secret_key'],true,['version' => 'v3.1']);
							$body = [
							'Messages' => [
							  [
							    'From' => [
							      'Email' => "notifications@thequickdivorce.com",
							      'Name' => "The Quick Divorce"
							    ],
							    'To' => [
							      [
							        'Email' => $_compra2['email_user'],
							        'Name' => home::convertirCaracteres($this->_sess->get('usuario_front'))
							      ]
							    ],							    
					            
							    'Subject' => 'Thank you for your order!',
							    'HTMLPart' => $_body,
							  ]
							]
							];
							$response = $mj->post(Resources::$Email, ['body' => $body]);
							$response->success();

							$_resp = $response->getData();

							// echo $_resp['Messages'][0]['Status'];

							if($_resp['Messages'][0]['Status'] !='success'){
								echo "Error";
				                exit; 

							}else{


				            	$_edit = contenidos_compra::find(array('conditions' => array('id = ?', $_compra->id)));	
								if($_edit){

									$_edit->envio_user = 1;
									$_edit->save();		
																				
								}


				            	// Envio mail factura
				            	
						    	// $_productos = $this->homeGestion->traerDatosCarrito($_compra2['id']);
						     	//$_shipp = $this->homeGestion->traerShipping($_compra2['id_user']);
								// $_bill = $this->homeGestion->traerBilling($_compra2['id_user']);
								// $_cant_prod = (count($_productos)<=1) ? count($_productos)." ITEM" : count($_productos). " ITEMS";

						    	if($_compra2){


						    		$_body2 = '<!DOCTYPE html>
										<html lang="en">
										<head>
										    <meta charset="UTF-8">
										    <meta http-equiv="X-UA-Compatible" content="IE=edge">
										    <meta name="viewport" content="width=device-width, initial-scale=1.0">
										    <link rel="preconnect" href="https://fonts.gstatic.com">
										    <title>The Quick Divorce</title>
										</head>

										<body style="margin: auto;width: 50%;padding:0;box-sizing: border-box;min-width: 600px;color: #000;font-family: Arial, Helvetica, sans-serif;">

										   <header class="header" style="height: 100px;background-color: #162536;text-align: center;">
										       <img src="'.$this->_conf['base_url'].'views/layout/default/img/TQD_logo.png" alt="" style="width: 340px;margin-top: 10px;">
										   </header> 

										   <div class="container" style="background-color: #f2f0e2;">

										        <div class="intro-text" style="text-align: center;">
										            <h3 class="text-medio" style="margin:0;padding-top:20px">ORDER N&deg; '.$_compra2['id'].'</h3>
										            <p class="text-small">Client: <strong>'.home::convertirCaracteres($this->_sess->get('usuario_front')).'</strong></p>
										            <p class="text-small">Date: '.date('d M o', strtotime($_compra2['fecha'])).'</p>
										        </div>

										        <div class="item" style="background-color: white;margin: 20px 50px;padding: 15px 30px;">
										            <h3>Payment information</h3>
										            <table width="100%" border="1" cellspacing="0" cellpadding="2">
										                <tr align="center" bgcolor="#CCCCCC" style="font-weight:bold">
										                      <td>Payment method</td> 
										                      <td>Card Name</td> 
										                      <td>Card Brand</td>
										                      <td>Card Number</td> 
										                      <td>Receipt</td>
										                  </tr>
										                <tr>
										                  <td>'.$_compra2['data_pago']->source->funding.'</td>
										                  <td>'.$_compra2['data_pago']->source->name.'</td>
										                  <td>'.$_compra2['data_pago']->source->brand.'</td>
										                  <td>**** **** **** '.$_compra2['data_pago']->payment_method_details->card->last4.'</td>
										                  <td><a href="'.$_compra2['data_pago']->receipt_url.'" target="_blank">See the receipt</a></td>
										                </tr>

										               
										            </table>
										        </div>

										        <div class="item" style="background-color: white;margin: 20px 50px;padding: 15px 30px;">
										            <h3>Product information</h3>

										            <table border="1" cellspacing="0" cellpadding="2">
										                <tr align="center" bgcolor="#CCCCCC" style="font-weight:bold">
										                      <td>Product</td> 
										                      <td>Quantity</td> 
										                      <td>Price</td> 
										                  </tr>';
										                foreach($_productos as $prod){

										                   $_body2 .= '   <tr align="center">
										                          <td>'.home::convertirCaracteres(home::traerProductoPorTipoStatic($prod['id_producto'], $prod['tipo_producto'])->titulo).'</td>
										                           <td>'.$prod['cantidad'].'</td>
										                          <td>$'.number_format($prod['precio'], 2, ',', '.').'</td>
										                          
										                     </tr>';
										                }

										             $_body2 .= '<tr>
										                  <td class="total" align="right" colspan="3">Subtotal: $'.number_format($_compra2['subtotal'], 2, '.', ',').'</td>
										              </tr>

										               <tr>
										                  <td class="total" align="right" colspan="3"><strong>Total: $'.number_format($_compra2['total'], 2, '.', ',').'</strong></td>
										              </tr>
										            </table>
										        </div>

										        <div class="" style="text-align: center; background-color: #162536;padding: 20px 0;"></div>
										   </div>
										</body>
										</html>';
						    	

									


									
					            

					            }
	            

					            

					            // use \Mailjet\Resources;
								$mj = new \Mailjet\Client($this->_conf['smtp']['api_key'],$this->_conf['smtp']['secret_key'],true,['version' => 'v3.1']);
								$body = [
								'Messages' => [
								  [
								    'From' => [
								      'Email' => "notifications@thequickdivorce.com",
								      'Name' => "The Quick Divorce"
								    ],
								    'To' => [
								      [
								        'Email' => "info@thequickdivorce.com",
								      	'Name' => "The Quick Divorce"
								      ]
								    ],
								    'Cc' => [
						                [
						                    'Email' => "aliette@carolanfamilylaw.com",
						                    'Name' => "Aliette"
						                ]
						            ],							    
						            'Bcc' => [						               
						                [
						                    'Email' => "thequickdivorce@gmail.com",
						                    'Name' => "The Quick Divorce Gmail"
						                ]
						            ],
								    'Subject' => 'New order: '. $_compra2['id'],
								    'HTMLPart' => $_body2,
								  ]
								]
								];
								$response = $mj->post(Resources::$Email, ['body' => $body]);
								$response->success();

								$_resp = $response->getData();

								// echo $_resp['Messages'][0]['Status'];

								if($_resp['Messages'][0]['Status'] !='success'){
									echo "Error";
					                exit; 
					                
								}




					            $_edit = contenidos_compra::find(array('conditions' => array('id = ?', $_compra->id)));	
								if($_edit){

									$_edit->envio_admin = 1;
									$_edit->save();									
											
								}


				            }

				            echo 'ok';
							exit;

							
					    }else{

					    	$_status = contenidos_compra::find(array('conditions' => array('id = ?', $_compra->id)));	
					    	if($_status){
					    		$_status->estado = 'rejected';
								$_status->save();
					    	}

					    	echo 'Payment process failed';
							exit;
					    }

					    
					} catch(\Stripe\Exception\CardException $e) {
						// Since it's a decline, \Stripe\Exception\CardException will be caught
						// echo 'Status is:' . $e->getHttpStatus() . '\n';
						// echo 'Type is:' . $e->getError()->type . '<br>';
						// echo 'Code is:' . $e->getError()->code . '\n';
						// param is '' in this case
						// echo 'Param is:' . $e->getError()->param . '\n';
						echo  $e->getError()->message;
					} catch (\Stripe\Exception\RateLimitException $e) {
						// Too many requests made to the API too quickly
						echo  $e->getError()->message;
					} catch (\Stripe\Exception\InvalidRequestException $e) {
						// Invalid parameters were supplied to Stripe's API
						echo  $e->getError()->message;
					} catch (\Stripe\Exception\AuthenticationException $e) {
						// Authentication with Stripe's API failed
						// (maybe you changed API keys recently)
						echo  $e->getError()->message;
					} catch (\Stripe\Exception\ApiConnectionException $e) {
						// Network communication with Stripe failed
						echo  $e->getError()->message;
					} catch (\Stripe\Exception\ApiErrorException $e) {
						// Display a very generic error to the user, and maybe send
						// yourself an email
						echo  $e->getError()->message;
					} catch (Exception $e) {
						// Something else happened, completely unrelated to Stripe
						// echo  $e->getError()->message;
						echo $e->getMessage();
					}							
								
									
					
					// echo 'ok';
					exit;
				}
			}
		}
		
	}


	

	public function continuarCompra()
	{
		
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){


				if(!$this->_sess->get('autenticado_front')){
					// $jsondata['msg'] = 'no';
					// echo json_encode($jsondata);
					echo "no";
					exit;
				}else{	

					 // echo "<pre>";print_r($_POST);exit;

							
					$_crearUser = home::traerUserPorId($this->_sess->get('id_usuario_front'));
					if(!$_crearUser){
						// $jsondata['msg'] = 'no';
						// echo json_encode($jsondata);
						// exit;
						echo "no";
						exit;
					}else{


						// echo "<pre>";print_r($_SESSION);exit;


						/*if($_SESSION['_shipping']!='0.00'){

							$_msg = 'shipping';

						}else{

							$_msg = 'payment';

						}*/
						$_msg = 'shipping';

						// $_SESSION['_datos_compra_finales'] = $_POST;
						// array_shift($_SESSION['_datos_compra_finales']);
						// $jsondata['msg'] = 'si';
						// $jsondata['tipo_envio'] = $_SESSION['_datos_compra_finales']['_tipo_envio'];
						// echo json_encode($jsondata);
						// exit;

						echo $_msg;
						exit;

						
					}
				}
			}
		}
	}

	public function procesarShipping()
	{
		
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){


				if(!$this->_sess->get('autenticado_front')){
					echo "no";
					exit;
				}else{	

					 // echo "<pre>";print_r($_POST);exit;


					if(!validador::getPostParam('addressname')){
						// echo 'Debe introducir un nombre';
						echo $this->_conf['text_lang']['carrito']['shipping'][$_SESSION['_lang']]['validacion1'];
						exit;
					}

					if(!validador::getPostParam('addressline1')){
						// echo 'Debe introducir un nombre';
						echo $this->_conf['text_lang']['carrito']['shipping'][$_SESSION['_lang']]['validacion2'];
						exit;
					}

					/*if(!validador::getPostParam('addressline2')){
						// echo 'Debe introducir un nombre';
						echo $this->_conf['text_lang']['carrito']['shipping'][$_SESSION['_lang']]['validacion3'];
						exit;
					}*/

					if(!validador::getPostParam('zipcode')){
						// echo 'Debe introducir un nombre';
						echo $this->_conf['text_lang']['carrito']['shipping'][$_SESSION['_lang']]['validacion6'];
						exit;
					}

					if(!validador::getPostParam('city')){
						// echo 'Debe introducir un nombre';
						echo $this->_conf['text_lang']['carrito']['shipping'][$_SESSION['_lang']]['validacion4'];
						exit;
					}

					if(!validador::getPostParam('state')){
						// echo 'Debe introducir un nombre';
						echo $this->_conf['text_lang']['carrito']['shipping'][$_SESSION['_lang']]['validacion5'];
						exit;
					}

					if(!validador::getPostParam('country')){
						// echo 'Debe introducir un nombre';
						echo $this->_conf['text_lang']['carrito']['shipping'][$_SESSION['_lang']]['validacion7'];
						exit;
					}

							
					$_envio = contenidos_shippin::find(array('conditions' => array('id_user = ?', $this->_sess->get('id_usuario_front'))));

					if($_envio){
						
						// $_envio->id_user = $this->_sess->get('id_usuario_front');
						$_envio->address_name = $_POST['addressname'];
						$_envio->address_line_1 = $_POST['addressline1'];	
						$_envio->address_line_2 = $_POST['addressline2'];
						$_envio->zipcode = $_POST['zipcode'];
						$_envio->city = $_POST['city'];
						$_envio->state = $_POST['state'];
						$_envio->country = $_POST['country'];
						$_envio->fecha = date('Y-m-d H:i:s');	
						// $_envio->save();
						
						

					}else{

						$_envio = new contenidos_shippin();	
						$_envio->id_user = $this->_sess->get('id_usuario_front');
						$_envio->address_name = $_POST['addressname'];
						$_envio->address_line_1 = $_POST['addressline1'];	
						$_envio->address_line_2 = $_POST['addressline2'];
						$_envio->zipcode = $_POST['zipcode'];
						$_envio->city = $_POST['city'];
						$_envio->state = $_POST['state'];
						$_envio->country = $_POST['country'];
						$_envio->fecha = date('Y-m-d H:i:s');	
						// $_envio->save();

					}		


					if($_envio->save()){

						$_SESSION['_direccion']['tipo'] = $_POST['_tipo'];
						$_SESSION['_direccion']['id'] = $_envio->id;
					}		
									
					
					echo 'ok';
					exit;
				}
			}
		}
		
	}

	public function procesarBilling()
	{
		
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){


				if(!$this->_sess->get('autenticado_front')){
					echo "no";
					exit;
				}else{	

					 // echo "<pre>";print_r($_POST);exit;


					if(!validador::getPostParam('addressnameBilling')){
						// echo 'Debe introducir un nombre';
						echo $this->_conf['text_lang']['carrito']['shipping'][$_SESSION['_lang']]['validacion1'];
						exit;
					}

					if(!validador::getPostParam('addressline1Billing')){
						// echo 'Debe introducir un nombre';
						echo $this->_conf['text_lang']['carrito']['shipping'][$_SESSION['_lang']]['validacion2'];
						exit;
					}

					/*if(!validador::getPostParam('addressline2Billing')){
						// echo 'Debe introducir un nombre';
						echo $this->_conf['text_lang']['carrito']['shipping'][$_SESSION['_lang']]['validacion3'];
						exit;
					}*/

					if(!validador::getPostParam('zipcodeBilling')){
						// echo 'Debe introducir un nombre';
						echo $this->_conf['text_lang']['carrito']['shipping'][$_SESSION['_lang']]['validacion6'];
						exit;
					}

					if(!validador::getPostParam('cityBilling')){
						// echo 'Debe introducir un nombre';
						echo $this->_conf['text_lang']['carrito']['shipping'][$_SESSION['_lang']]['validacion4'];
						exit;
					}

					if(!validador::getPostParam('stateBilling')){
						// echo 'Debe introducir un nombre';
						echo $this->_conf['text_lang']['carrito']['shipping'][$_SESSION['_lang']]['validacion5'];
						exit;
					}

					if(!validador::getPostParam('countryBilling')){
						// echo 'Debe introducir un nombre';
						echo $this->_conf['text_lang']['carrito']['shipping'][$_SESSION['_lang']]['validacion7'];
						exit;
					}

							
					$_envio = contenidos_billin::find(array('conditions' => array('id_user = ?', $this->_sess->get('id_usuario_front'))));

					if($_envio){
						
						// $_envio->id_user = $this->_sess->get('id_usuario_front');
						$_envio->address_name = $_POST['addressnameBilling'];
						$_envio->address_line_1 = $_POST['addressline1Billing'];	
						$_envio->address_line_2 = $_POST['addressline2Billing'];
						$_envio->zipcode = $_POST['zipcodeBilling'];
						$_envio->city = $_POST['cityBilling'];
						$_envio->state = $_POST['stateBilling'];
						$_envio->country = $_POST['countryBilling'];
						$_envio->fecha = date('Y-m-d H:i:s');	
						// $_envio->save();
						
						

					}else{

						$_envio = new contenidos_billin();	
						$_envio->id_user = $this->_sess->get('id_usuario_front');
						$_envio->address_name = $_POST['addressnameBilling'];
						$_envio->address_line_1 = $_POST['addressline1Billing'];	
						$_envio->address_line_2 = $_POST['addressline2Billing'];
						$_envio->zipcode = $_POST['zipcodeBilling'];
						$_envio->city = $_POST['cityBilling'];
						$_envio->state = $_POST['stateBilling'];
						$_envio->country = $_POST['countryBilling'];
						$_envio->fecha = date('Y-m-d H:i:s');	
						// $_envio->save();

					}		


					if($_envio->save()){

						$_SESSION['_direccion']['tipo'] = $_POST['_tipo'];
						$_SESSION['_direccion']['id'] = $_envio->id;
					}		
									
					
					echo 'ok';
					exit;
				}
			}
		}
		
	}

	

	public function changePrice()
	{
		
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){


				/*if(!$this->_sess->get('autenticado_front')){
					exit;
				}else{*/	

					 // echo "<pre>";print_r($_POST);exit;

							
					/*$_crearUser = home::traerUserPorId($this->_sess->get('id_usuario_front'));
					if(!$_crearUser){
						echo "no";
						exit;
					}else{*/

						$_id = (int) $_POST['_id'];
						$_pres = (int) $_POST['_presentacion'];

						$_prod = $this->homeGestion->traerProducto($_id);
						if($_prod){
							$_present = unserialize($_prod['presentation']);
							$_price = unserialize($_prod['price']);
						}

						foreach ($_present as $key => $val) {
							if($val == $_pres){
								$_pres_key = $key;
							}
							
						}

						$jsondata['price'] = '$ '.number_format($_price[$_pres_key], 2, '.', '');
						$jsondata['price_sin_format'] = $_price[$_pres_key];

						echo json_encode($jsondata);
						exit;

						
					// }
				// }
			}
		}
	}


	/////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////
	
	

	

	public function direccionEnvio()
	{
		if(!$this->_sess->get('autenticado_front')){
			$this->redireccionar();
			exit;
		}

		$this->_view->categorias = $this->homeGestion->traerCategorias();
		$this->_view->sucursales = $this->homeGestion->traerSucursales();
		$this->_view->banners = $this->homeGestion->traerBanners(1, 5);
		$this->_view->direcciones = $this->homeGestion->traerDireccionesEnvios($this->_sess->get('id_usuario_front')); 
		$this->_view->estados = $this->homeGestion->traerEstados();
		
		// if($this->_sess->get('autenticado_front')){
		$this->_view->user = home::traerUserPorId($this->_sess->get('id_usuario_front'));
		$_arr_envio = array();
		foreach ($_SESSION['_carro'] as $id => $val) {
			$_us = $this->homeGestion->traerProductosPorId($id);
			$_arr_envio[]=$_us['envio'];
		}

		$_envio1 = array_search('no', $_arr_envio);
		if($_envio1!==false){
			$this->_view->_retiro = 1;
		}else{
			$this->_view->_retiro = 0;
		}

		$_envio2 = array_search('si', $_arr_envio);
		if($_envio2!==false){
			$this->_view->_domicilio = 1;
		}else{
			$this->_view->_domicilio = 0;
		}

		// echo $_envio2;
		// echo "<pre>";print_r($_arr_envio);exit;
		
		$this->_view->titulo = 'Farmacia Paris';
		$this->_view->renderizar('envio','cart', 'default');
	}

	
	public function cargarEnvio()
	{
		
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){


				if(!$this->_sess->get('autenticado_front')){
					echo "no";
					exit;
				}else{	

					 // echo "<pre>";print_r($_POST);exit;

							
					// exit('checkout');	
					if($_POST['_id']!=''){

						$_envio = contenidos_shippin::find($_POST['_id']);	
						if($_envio){
							$_envio->id_user = $this->_sess->get('id_usuario_front');
							$_envio->address_name = $_POST['addressname'];
							$_envio->address_line_1 = $_POST['addressline1'];	
							$_envio->address_line_2 = $_POST['addressline2'];
							$_envio->zipcode = $_POST['zipcode'];
							$_envio->city = $_POST['city'];
							$_envio->state = $_POST['state'];
							$_envio->country = $_POST['country'];
							$_envio->fecha = date('Y-m-d H:i:s');	
							$_envio->save();
						}
						

					}else{

						$_envio = new contenidos_shippin();	
						$_envio->id_user = $this->_sess->get('id_usuario_front');
						$_envio->address_name = $_POST['addressname'];
						$_envio->address_line_1 = $_POST['addressline1'];	
						$_envio->address_line_2 = $_POST['addressline2'];
						$_envio->zipcode = $_POST['zipcode'];
						$_envio->city = $_POST['city'];
						$_envio->state = $_POST['state'];
						$_envio->country = $_POST['country'];
						$_envio->fecha = date('Y-m-d H:i:s');	
						$_envio->save();

					}				
									
					
					echo 'ok';
					exit;
				}
			}
		}
	}

	public function editarEnvio()
	{
		
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){


				if(!$this->_sess->get('autenticado_front')){
					echo "no";
					exit;
				}else{	

					 // echo "<pre>";print_r($_POST);exit;
					$_id =  $_POST['id'];
					$_estados = $this->homeGestion->traerEstados();
					$dir = $this->homeGestion->traerDireccionEnvio($_id); 

					// echo "<pre>";print_r($dir);exit;

					if($dir){
						$_html='';

						$_html .='<form id="form_envio" name="form_envio" action="">
									<input type="hidden" name="_csrf" value="'.$this->_sess->get('_csrf').'">
									<input type="hidden" name="_id" id="_id" value="'.$dir['id'].'">
									<div class="full">
										<label for="name">* Titulo</label>
										<input type="text" name="name" id="name" value="'.home::convertirCaracteres($dir['titulo']).'" required="">
									</div>			
									<div class="mid">
										<label for="calle">* Calle</label>
										<input type="text" name="calle" id="calle" value="'.home::convertirCaracteres($dir['calle']).'" required="">
									</div>
									<div class="four">
										<label for="N_exterior">* Nº Exterior</label>
										<input type="number" name="N_exterior" id="N_exterior" value="'.$dir['numero_ext'].'" required="">
									</div>
									<div class="four">
										<label for="N_interior">Nº Interior</label>
										<input type="number" name="N_interior" id="N_interior" value="'.$dir['numero_int'].'">
									</div>
									<div class="third">
										<label for="cod_postal">* Código Postal</label>
										<input type="number" name="cod_postal" id="cod_postal" value="'.$dir['codigo_postal'].'" required="">
									</div>
									<div class="third">
										<label for="colonia">* Colonia</label>
										<input type="text" name="colonia" id="colonia" value="'.home::convertirCaracteres($dir['colonia']).'" required="">
									</div>
									<div class="third">
										<label for="municipio">* Municipio</label>
										<input type="text" name="municipio" id="municipio" value="'.home::convertirCaracteres($dir['municipio']).'" required="">
									</div>
									<div class="mid">
										<label for="estado">* Estado</label>
										<select name="estado" id="estado" required="">
											<option value="">Seleccione un estado</option>';
											foreach ($_estados as $est){
												if($est['abreviatura'] == $dir['estado']){
													$_html .='<option value="'.$est['abreviatura'].'" selected>'.$est['estado'].'</option>';
												}else{
													$_html .='<option value="'.$est['abreviatura'].'">'.$est['estado'].'</option>';
												}											
											}
							$_html .='</select>										
									</div>
									<div class="mid">
										<label for="telefono">* Pais</label>
										<select name="pais" id="pais" required="">
											<option value="">Seleccione un país</option>';
											if($dir['pais'] == 'Mexico'){
												$_html .='<option value="'.home::convertirCaracteres($dir['pais']).'" selected>'.home::convertirCaracteres($dir['pais']).'</option>';
											}else{
												$_html .='<option value="'.home::convertirCaracteres($dir['pais']).'">'.home::convertirCaracteres($dir['pais']).'</option>';
											}
										
							$_html .='</select>	
										
									</div>
									<button id="btCargarDir">Cargar dirección</button>
									</form>';

						$_html .='<script>
									$("#form_envio").on("submit", function(e){
										e.preventDefault();
										e.stopPropagation();	
										
										$.ajax({
											type: "POST",
											url: _root_+"cart/cargarEnvio",
											data: $("#form_envio").serialize(),
											beforeSend: function(){
												$("#cont_form").slideUp(800); 
											},
											success: function(data){
												$(".address").slideUp(800, function(){
													$(".address").html(data).slideDown(800);
													$("#form_envio input[type=\"text\"],input[type=\"number\"]").val("");
													$("#_id").val("");
												});
												
											}
										});
										
										return false;
									});

									</script>';

					}
									
					
					echo $_html;
					exit;
				}
			}
		}
	}

	public function selecEnvio()
	{
		
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){


				if(!$this->_sess->get('autenticado_front')){
					echo "no";
					exit;
				}else{	

					 // echo "<pre>";print_r($_POST);exit;

					// if(!isset($_SESSION['_datos_compra'])){							
					$_SESSION['_tipo_envio'] = $_POST['selec_envio'];
					// }
					// unset($_SESSION['_datos_compra']['_csrf']);
					// array_shift($_SESSION['_datos_compra']);
					// array_pop($_SESSION['_datos_compra']);

					// echo "<pre>";print_r($_SESSION['_carro']);echo "</pre>";
					// echo "<pre>";print_r($_SESSION['_datos_compra']);exit;

							// 
					// $this->redireccionar('cart/direccionEnvio');				
					
					echo "ok";
					exit;
				}
			}
		}
	}

	
	
	
	public function finalizarCompra()
	{
		
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){


				if(!$this->_sess->get('autenticado_front')){
					$jsondata['msg'] = 'no';
					echo json_encode($jsondata);
					// echo "no";
					exit;
				}else{	

					// echo "<pre>";print_r($_POST);exit;

							
					$_crearUser = home::traerUserPorId($this->_sess->get('id_usuario_front'));
					if(!$_crearUser){
						$jsondata['msg'] = 'no';
						echo json_encode($jsondata);
						// echo "no";
						exit;
					}else{

						if($_crearUser->nombre == ''){
							$jsondata['msg'] = 'faltan_datos_1';
							echo json_encode($jsondata);
							// echo "faltan_datos_1";
							exit;
						}
						if($_crearUser->apellido==''){
							$jsondata['msg'] = 'faltan_datos_1';
							echo json_encode($jsondata);
							// echo "faltan_datos_1";
							exit;
						}
						if($_crearUser->telefono==''){
							$jsondata['msg'] = 'faltan_datos_1';
							echo json_encode($jsondata);
							// echo "faltan_datos_1";
							exit;
						}
						
						if($_crearUser->fecha_nacimiento==''){
							$jsondata['msg'] = 'faltan_datos_1';
							echo json_encode($jsondata);
							// echo "faltan_datos_1";
							exit;
						}
						if($_crearUser->email==''){
							$jsondata['msg'] = 'faltan_datos_2';
							echo json_encode($jsondata);
							// echo "faltan_datos_2";
							exit;
						}
						if($_crearUser->password==''){
							$jsondata['msg'] = 'faltan_datos_2';
							echo json_encode($jsondata);
							// echo "faltan_datos_2";
							exit;
						}
					}	


					// echo "<pre>";print_r($_crearUser);exit;

					$_arr_envio = array();
					foreach ($_SESSION['_carro'] as $id => $val) {
						$_us = $this->homeGestion->traerProductosPorId($id);
						$_arr_envio[]=$_us['envio'];
					}

					// echo "<pre>";print_r($_arr_envio);exit;	

					if($_POST['_tipo_envio']==2){

						$_envio1 = array_search('no', $_arr_envio);
						if($_envio1!==false){
							$_retiro = 1;
						}else{
							$_retiro = 0;
						}

						$_envio2 = array_search('si', $_arr_envio);
						if($_envio2!==false){
							$_domicilio = 1;
						}else{
							$_domicilio = 0;
						}

						if($_retiro == 1 && $_domicilio == 1){
							$jsondata['tipo_envio'] = 2;
							echo json_encode($jsondata);
							exit;
						}else{
							$jsondata['tipo_envio'] = 1;
							echo json_encode($jsondata);
							exit;
						}

					}else{
						$jsondata['tipo_envio'] = 1;
						echo json_encode($jsondata);
						exit;
					}

					// echo "<pre>";print_r($_arr_envio);exit;				


					exit('checkout');	
					
					/*$_pedido = contenidos_pedido::find($this->_sess->get('_pedido'));	
					if(!$_pedido){
						$_crear_pedido = new contenidos_pedido();
						$_crear_pedido->id_user = $this->_sess->get('id_usuario_front');
						$_crear_pedido->monto_final = $_POST['total'];
						$_crear_pedido->estado = 'pendiente';					
						$_crear_pedido->fecha = date('Y-m-d H:i:s');	
						$_crear_pedido->save();
					}
					
					if(!$this->_sess->get('_pedido')){
						$this->_sess->set('_pedido', $_crear_pedido->id);
					}
					
					$_carrito = contenidos_carrito::find('all',array('conditions' => array('id_pedido = ?', $this->_sess->get('_pedido'))));	
					if(!$_carrito){
						foreach($_SESSION['_carro'] as $id => $val){
							
							$_llenar_carro = new contenidos_carrito();
							$_llenar_carro->id_pedido = $_crear_pedido->id;
							$_llenar_carro->id_producto = $id;
							// $_llenar_carro->id_promocion = $val['promocion'];
							$_llenar_carro->cantidad = $val['cantidad'];
							$_llenar_carro->precio = $val['precio'];
							$_llenar_carro->fecha = date('Y-m-d H:i:s');
							$_llenar_carro->save();
						
						}
					}
					
					
					echo "ok";
					exit;*/
				}
			}
		}
	}


	

	public function procesarCorreo()
	{
		
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){


				if(!$this->_sess->get('autenticado_front')){
					$jsondata['msg'] = 'no';
					echo json_encode($jsondata);
					// echo "no";
					exit;
				}else{	

					  // echo "<pre>";print_r($_POST);exit;

							
					$_correo = explode(';', $_POST['_correo']);

					if($_SESSION['_datos_compra_finales']){
						$_SESSION['_datos_compra_finales']['_carrier'] = $_correo[0];
						$_SESSION['_datos_compra_finales']['_carrier_service_code'] = $_correo[1];
						$_SESSION['_datos_compra_finales']['_carrier_total_amount'] = $_correo[2];
					}
					

					$jsondata['msg'] = 'si';
					echo json_encode($jsondata);
					// echo "no";
					exit;

						
					
				}
			}
		}
	}


	

	/*public function checkout()
	{		

		if(!$this->_sess->get('autenticado_front')){
			$this->redireccionar();
			exit;
		}else{			 

					
			$_crearUser = home::traerUserPorId($this->_sess->get('id_usuario_front'));
			if(!$_crearUser){
				$this->redireccionar();
				exit;
			}else{

				$this->_view->categorias = $this->homeGestion->traerCategorias();
				$this->_view->banners = $this->homeGestion->traerBanners(1, 5);
				// $this->_view->prod_rel = $this->homeGestion->traerProductosRel(1);				
				$this->_view->user = home::traerUserPorId($this->_sess->get('id_usuario_front'));

				// echo "<pre>";print_r($_SESSION);exit;

				// echo "<pre>";print_r($_POST);exit;

			


				if($_SESSION['_datos_compra_finales']['_tipo_envio'] == 2){

					$this->_view->precio_envio = $_SESSION['_datos_compra_finales']['_carrier_total_amount'];

					// calcular precio envio

					$_arr_envio = array();
					foreach ($_SESSION['_carro'] as $id => $val) {
						$_us = $this->homeGestion->traerProductosPorId($id);
						if($_us['envio'] =='si'){
							$_arr_envio[]=$_us;
						}
						
					}

					

					// echo "<pre>";print_r($_arr_envio);//exit;	
					
					$_dir = $this->homeGestion->traerDireccionEnvio($_SESSION['_datos_compra_finales']['_id_envio']);


					


					// echo "<pre>";print_r($_dir);exit;	
					

										
					// $this->_view->precio_envio = $_SESSION['_datos_compra_finales']['total_amount'];
					
					


				}else{

					// pasar a plataforma de pago



					$this->_view->precio_envio= 'no';
					
				}					


				// exit('checkout');	
				
				
			}
		}


			
		$this->_view->titulo = 'Farmacia Paris';
		$this->_view->renderizar('checkout','cart', 'default');
	}
*/

	public function facturar()
	{
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){

				if(!$this->_sess->get('autenticado_front')){
					$jsondata['msg'] = 'no';
				}else{	

					// echo "<pre>";print_r($_POST);exit;

					$_fact = $_POST['facturacion'];

					if($_fact == 'si'){

						$_user = contenidos_user::find($this->_sess->get('id_usuario_front'));
						if($_user){
							$_user->factura = 'si';
							$_user->save();							
						}

						$jsondata['msg'] = 'si_fc';


					}else{

						$_user = contenidos_user::find($this->_sess->get('id_usuario_front'));
						if($_user){
							$_user->factura = 'no';
							$_user->save();							
						}

						$jsondata['msg'] = 'no_fc';
						// exit;
					}			
					
					
				}

				echo json_encode($jsondata);
				exit;	
			}
		}
	}





	public function finalizarCompraTransferencia()
	{
		
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){

				if(!$this->_sess->get('_user')){
					echo "no";
					exit;
				}else{				
							
					$_crearUser = contenidos_user::find(array('conditions' => array('identificador = ?', $this->_sess->get('_user'))));	
					if(!$_crearUser){
						echo "no";
						exit;
					}			
					
					$_pedido = contenidos_pedido::find($this->_sess->get('_pedido'));	
					if(!$_pedido){
						$_crear_pedido = new contenidos_pedido();
						$_crear_pedido->id_user = home::traerUserPorIdenficador($this->_sess->get('_user'))->id;
						$_crear_pedido->monto_final = $_POST['total'];
						$_crear_pedido->estado = 'pendiente';					
						$_crear_pedido->fecha = date('Y-m-d');	
						$_crear_pedido->save();
					}
					
					if(!$this->_sess->get('_pedido')){
						$this->_sess->set('_pedido', $_crear_pedido->id);
					}
					
					$_carrito = contenidos_carrito::find('all',array('conditions' => array('id_pedido = ?', $this->_sess->get('_pedido'))));	
					if(!$_carrito){
						foreach($_SESSION['_carro'] as $id => $val){
							
							$_llenar_carro = new contenidos_carrito();
							$_llenar_carro->id_pedido = $_crear_pedido->id;
							$_llenar_carro->id_producto = $id;
							$_llenar_carro->id_promocion = $val['promocion'];
							$_llenar_carro->cantidad = $val['cantidad'];
							$_llenar_carro->precio = $val['precio'];
							$_llenar_carro->fecha = date('Y-m-d');	
							$_llenar_carro->save();
						
						}
					}
					
					
					$this->_sess->set('_monto_tranfer', $_POST['total']);
					$this->_sess->set('_datos_carro', $_SESSION['_carro']);
					$this->_sess->destroy('_pedido');
					unset($_SESSION['_carro']);
					
					echo "ok";
					exit;
				}
			}
		}
	}
	
	

	
	

}
?>