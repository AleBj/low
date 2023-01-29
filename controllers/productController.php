<?php

use Nucleo\Controller\Controller;

class productController extends Controller
{
	public $homeGestion;
	
    public function __construct()
	{
        parent::__construct();
		
		$this->getLibrary('class.home');		
		$this->homeGestion = new home();	

		// $this->getLibrary('PHPMailerAutoload');
		// $this->envioMail = new PHPMailer();

		$this->getLibrary('AntiXSS');
		$this->_xss = new AntiXSS();	
				
		$this->getLibrary('class.validador');		

		/*if($this->_sess->get('_carro')){
				$this->_sess->destroy('_carro');
			}*/
				
    }
    
   	public function index()
   	{

   		if($this->_sess->get('autenticado_front')){
			$this->_view->user = home::traerUserPorId($this->_sess->get('id_usuario_front'));
		}


		// $this->_view->productos = $this->homeGestion->traerProductos('alta');
		$this->_view->productos_variables = $this->homeGestion->traerProductosVariables('alta');
		

   		// echo "<pre>";print_r($this->_view->productos);echo "</pre>";exit;

   		// SEO
        $this->_view->seo = $this->homeGestion->traerSeoSeccion('productos_variables', 'seccion');
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
		$this->_view->renderizar('index', 'getstarted', 'default');
   	}	

	public function detail($_val)
   	{

   		/*if(!$this->_sess->get('autenticado_front')){

   			$this->redireccionar('product');
			
		}else{*/

			$this->_view->user = home::traerUserPorId($this->_sess->get('id_usuario_front'));

			if($_val == 'ppa' || $_val == 'msa' || $_val == 'msappa'){
				$this->_view->productos = $this->homeGestion->traerProductoPorItem($_val);
				$this->_view->seo = $this->homeGestion->traerSeo($this->_view->productos['id'], 'productos_fijos', 'interna');
			}else{
				$this->_view->productos = $this->homeGestion->traerProductoVariablePorItem($_val);
				$this->_view->seo = $this->homeGestion->traerSeo($this->_view->productos['id'], 'productos_variables', 'interna');
			}		


		// }

   		// echo "<pre>";print_r($this->_view->productos);echo "</pre>";exit;

		// SEO        
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
		$this->_view->renderizar('detalle', 'product', 'default');
   	}	




   	public function register()
    {
    	if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
						
					
					
				$this->_view->datos = $_POST;

				 // echo "<pre>";print_r($_POST);exit;					
				

				if(!validador::getPostParam('nombre')){
					/*$this->_view->_error = 'Debe introducir un numero de cliente valido';
					$this->_view->renderizar('index','registro');
					exit;*/

					echo 'Debe introducir un nombre';
					exit;
				}

				if(!validador::getPostParam('apellido')){
					/*$this->_view->_error = 'Debe introducir un numero de cliente valido';
					$this->_view->renderizar('index','registro');
					exit;*/

					echo 'Debe introducir un apellido';
					exit;
				}


				// if(!validador::getAlphaNum('usuario')){
				if(!validador::validarEmail($this->_view->datos['email'])){
					/*$this->_view->_error = 'Debe introducir un email valido';
					$this->_view->renderizar('index','registro');
					exit;*/

					echo 'Debe introducir un email valido';
					exit;
				}

				/*if(!validador::numerico($this->_view->datos['telefono'])) {
					echo 'Debe introducir un numero de telefono válido';
					exit;
				}*/
				
				//if(!validador::getSql('pass',$this->_conf['baseDatos'])){
				if(!validador::getAlphaNum('password')){					
					/*$this->_view->_error = 'Debe introducir su password';
					$this->_view->renderizar('index','registro');
					exit;*/

					echo 'Debe introducir su contraseña';
					exit;
				}


				if($this->_view->datos['password']!= $this->_view->datos['repeat_pass']){					
					/*$this->_view->_error = 'Debe introducir su password';
					$this->_view->renderizar('index','registro');
					exit;*/

					echo 'Contraseña y repetir contraseña no coinciden';
					exit;
				}
								
				
				
				

				$roww = contenidos_user::find(array('conditions' => array('email = ?', $this->_xss->xss_clean(validador::getTexto('email')))));
    			if($roww){
    				
    				echo 'Ya hay un usuario registrado con este email';
					exit;

    			}else{

    				/*if(!$this->_sess->get('carga_actual')){
						$this->_sess->set('carga_actual', rand(1135687452,9999999999));
					}

    				$_mes = ($this->_view->datos['month']<10) ? '0'.$this->_view->datos['month']: $this->_view->datos['month'];
					$_dia = ($this->_view->datos['day']<10) ? '0'.$this->_view->datos['day']: $this->_view->datos['day'];*/

					$user = new contenidos_user();
					$user->nombre = $this->_xss->xss_clean(validador::getTexto('nombre'));
					$user->apellido = $this->_xss->xss_clean(validador::getTexto('apellido'));						
					$user->email = $this->_xss->xss_clean($this->_view->datos['email']);
					// $user->password = $this->_xss->xss_clean($this->_view->datos['pass']);
					$user->password = Hash::getHash('md5', $this->_xss->xss_clean($this->_view->datos['password']), $this->_conf['hash_key']);
					// $user->telefono = $this->_xss->xss_clean($this->_view->datos['telefono']);						
					// $user->fecha_nacimiento = $_dia.'/'.$_mes.'/'.$this->_view->datos['year'];
					// $user->identificador = $this->_sess->get('carga_actual');
					$user->fecha = date('Y-m-d H:i:s');
					$user->save();

					// $this->_sess->destroy('carga_actual');
					$this->_sess->set('autenticado_front', true);
					$this->_sess->set('usuario_front', $user->nombre.' '.$user->apellido);
					$this->_sess->set('id_usuario_front', $user->id);

					echo 'ok';
					exit;
    				
    			}
										
					
				

			}else{
				//$this->redireccionar('error/access/404');
				$this->_view->_error = 'Hubo un error, vuelva a intentarlo mas tarde.';
				// $this->_view->renderizar('index','login');
				// exit;
			}
		}

    }

    public function login()
    {

    	if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){	
			
				
					
					
				$this->_view->datos = $_POST;
				
				// if(!validador::getAlphaNum('usuario')){
				if(!validador::validarEmail($this->_view->datos['email_login'])){
					// $this->_view->_error = 'Debe introducir un email valido';
					/*$this->_view->renderizar('index','login');
					exit;*/
					echo 'Debe introducir un email valido';
					exit;
				}
				
				//if(!validador::getSql('pass',$this->_conf['baseDatos'])){
				if(!validador::getAlphaNum('password_login')){					
					/*$this->_view->_error = 'Debe introducir su password';
					$this->_view->renderizar('index','login', 'login'); 
					exit;*/
					echo 'Debe introducir su password';
					exit;
				}
								
				
				
				// 'find' si se busca un solo registro, 'all' si se busca solo 1
				$row = contenidos_user::find(array(
										'conditions' => array(
														'email = ? AND password = ?', 
														// $this->_xss->xss_clean(validador::getAlphaNum('usuario')), 
														$this->_xss->xss_clean($this->_view->datos['email_login']),
														Hash::getHash('md5', $this->_xss->xss_clean(validador::getPostParam('password_login')), $this->_conf['hash_key'])
														// $this->_xss->xss_clean(validador::getPostParam('pass'))
														)
											)
									);
				
				
				if(!$row){
					/*$this->_view->_error = 'Usuario y/o password incorrectos';
					$this->_view->renderizar('index','login', 'login');
					exit;*/
					echo 'Usuario y/o password incorrectos';
					exit;
				}
				
									
				$this->_sess->set('autenticado_front', true);
				$this->_sess->set('usuario_front', $row->nombre.' '.$row->apellido);
				$this->_sess->set('id_usuario_front', $row->id);

				echo 'ok';
				exit;
				// $this->redireccionar('home');
				

			}else{
				//$this->redireccionar('error/access/404');
				// $this->_view->_error = 'Hubo un error, vuelva a intentarlo mas tarde.';
				echo 'Hubo un error, vuelva a intentarlo mas tarde.';
				exit;
			}
		}

    }
  
	public function recoverPass()
    {

    	if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
			
			
				
					
					
				$this->_view->datos = $_POST;
				
				// if(!validador::getAlphaNum('usuario')){
				if(!validador::validarEmail($this->_view->datos['email_recover'])){						
					echo 'Debe introducir un email valido';
					exit;
				}
												
				
				
				// 'find' si se busca un solo registro, 'all' si se busca solo 1
				$row = contenidos_user::find(array('conditions' => array('email = ?', $this->_xss->xss_clean($this->_view->datos['email_recover']))));					
				
				if(!$row){						
					echo 'El usuario no esta registrado';
					exit;
				}else{

					// $_token = md5(uniqid($this->_view->datos['email'], true));
					$_pretoken = uniqid();
					$_token = Hash::getHash('md5', $_pretoken, $this->_conf['hash_key']);
					$reg = contenidos_user::find(array('conditions' => array('password = ?', $_token)));
					if(!$reg){
						$row->password = Hash::getHash('md5', $_token, $this->_conf['hash_key']);
						$row->save();


						// mail admin
			            $_body='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			                    <html xmlns="http://www.w3.org/1999/xhtml">
			                    <head>
			                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			                    <title>The Quick Divorce</title>
			                    </head>                 
			                    <body>
			                    <p>Su nueva contraseña es <strong>'.$_token.'</strong></p>
			                    </body>
			                    </html>';
			            
			            // $this->envioMail->IsSMTP();
			            // $this->envioMail->SMTPAuth = true;
			            // $this->envioMail->Host = "smtphub.cencosud.cl";
			            // $this->envioMail->Username = "_MailCarteleria"; 
			            // $this->envioMail->Password = "a23uj8rs"; 
			            // $this->envioMail->Port = 25;                    
			            $this->envioMail->From ='info@thequickdivorce.com';
			            $this->envioMail->FromName ='The Quick Divorce';
			            $this->envioMail->Subject = 'Recuperar '.utf8_decode('contraseña');               
			            $this->envioMail->Body = $_body;
			            // $this->envioMail->AddAddress($this->_view->datos['email']);            
			            $this->envioMail->AddAddress('lucianodirisio@gmail.com');
			            $this->envioMail->IsHTML(true); 
			            
			            $exito = $this->envioMail->Send();
			            
			            $intentos=1;
			            
			            while ((!$exito) && ($intentos < 3)) {
			                sleep(5);           
			                $exito = $this->envioMail->Send();              
			                $intentos=$intentos+1;          
			            }
			            
			            if(!$exito) {           
			                echo "Problemas enviando correo electrónico a ".$this->envioMail->ErrorInfo;
			                exit;               
			            }

			            echo 'ok';
						exit;

					}else{
						echo 'Hubo un error, vuelva a intentarlo.';
						exit;
					}	


				}
					
					
				

			}else{				
				echo 'Hubo un error, vuelva a intentarlo mas tarde.';
				exit;
			}
		}

    }

	public function questions()
    {

    	if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){	
			
				echo "<pre>";print_r($_POST);exit;	
					
					
				$this->_view->datos = $_POST;


				
				if($_POST['preg1'] == 'yes' && $_POST['preg2'] == 'yes'){

					if($_POST['preg3'] == 'no' && $_POST['preg4'] == 'yes'){
						// PPA
						echo 'ppa';
						exit;
					}
					if($_POST['preg3'] == 'yes' && $_POST['preg4'] == 'yes'){
						// MSA + PPA
						echo 'msappa';
						exit;
					}
					if($_POST['preg3'] == 'yes' && $_POST['preg4'] == 'no'){
						// MSA
						echo 'msa';
						exit;
					}

				}else{
					// No califica
					echo 'no_califica';
					exit;
				}							
				
				
				/*$row = contenidos_user::find(array(
										'conditions' => array(
														'email = ? AND password = ?', 
														// $this->_xss->xss_clean(validador::getAlphaNum('usuario')), 
														$this->_xss->xss_clean($this->_view->datos['email_login']),
														Hash::getHash('md5', $this->_xss->xss_clean(validador::getPostParam('password_login')), $this->_conf['hash_key'])
														// $this->_xss->xss_clean(validador::getPostParam('pass'))
														)
											)
									);
				
				
				if(!$row){
					
					echo 'Usuario y/o password incorrectos';
					exit;
				}
				
									
				$this->_sess->set('autenticado_front', true);
				$this->_sess->set('usuario_front', $row->nombre.' '.$row->apellido);
				$this->_sess->set('id_usuario_front', $row->id);

				echo 'ok';
				exit;*/
				

			}else{
				//$this->redireccionar('error/access/404');
				// $this->_view->_error = 'Hubo un error, vuelva a intentarlo mas tarde.';
				echo 'Hubo un error, vuelva a intentarlo mas tarde.';
				exit;
			}
		}

    }


	
}
?>