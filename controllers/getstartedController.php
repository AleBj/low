<?php

use Nucleo\Controller\Controller;
use \Mailjet\Resources;
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;
// use PHPMailer\PHPMailer\Exception;

class getstartedController extends Controller
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

		
				
    }
    
   	public function index()
   	{

   		if($this->_sess->get('autenticado_front')){
			$this->_view->user = home::traerUserPorId($this->_sess->get('id_usuario_front'));
		}

   		// echo "<pre>";print_r($this->_view->aboutus);echo "</pre>";exit;

   		// SEO
        $this->_view->seo = $this->homeGestion->traerSeoSeccion('productos_fijos', 'seccion');
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

					echo 'You must enter a first name';
					exit;
				}

				if(!validador::getPostParam('apellido')){
					/*$this->_view->_error = 'Debe introducir un numero de cliente valido';
					$this->_view->renderizar('index','registro');
					exit;*/

					echo 'You must enter a last name';
					exit;
				}


				// if(!validador::getAlphaNum('usuario')){
				if(!validador::validarEmail($this->_view->datos['email'])){
					/*$this->_view->_error = 'Debe introducir un email valido';
					$this->_view->renderizar('index','registro');
					exit;*/

					echo 'You must enter a valid email';
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

					echo 'You must enter a password';
					exit;
				}


				if($this->_view->datos['password']!= $this->_view->datos['repeat_password']){					
					/*$this->_view->_error = 'Debe introducir su password';
					$this->_view->renderizar('index','registro');
					exit;*/

					echo 'Password and repeat password do not match';
					exit;
				}
								
				
				
				

				$roww = contenidos_user::find(array('conditions' => array('email = ?', $this->_xss->xss_clean(validador::getTexto('email')))));
    			if($roww){
    				
    				echo 'There is already a registered user with this email';
					exit;

    			}else{

    				/*if(!$this->_sess->get('carga_actual')){
						$this->_sess->set('carga_actual', rand(1135687452,9999999999));
					}

    				$_mes = ($this->_view->datos['month']<10) ? '0'.$this->_view->datos['month']: $this->_view->datos['month'];
					$_dia = ($this->_view->datos['day']<10) ? '0'.$this->_view->datos['day']: $this->_view->datos['day'];*/


					// exit('entro!');


					$_token = home::generateSecureToken(128, 'chars');

					$user = new contenidos_user();
					$user->nombre = $this->_xss->xss_clean(validador::getTexto('nombre'));
					$user->apellido = $this->_xss->xss_clean(validador::getTexto('apellido'));						
					$user->email = $this->_xss->xss_clean($this->_view->datos['email']);
					// $user->password = $this->_xss->xss_clean($this->_view->datos['pass']);
					$user->password = Hash::getHash('md5', $this->_xss->xss_clean($this->_view->datos['password']), $this->_conf['hash_key']);
					// $user->telefono = $this->_xss->xss_clean($this->_view->datos['telefono']);						
					// $user->fecha_nacimiento = $_dia.'/'.$_mes.'/'.$this->_view-
					$user->token = $_token;
					$user->activacion = 'no';
					$user->fecha = date('Y-m-d H:i:s');					
					$user->save();


					// Envio mail con taoken activacion
		            $_body = '<!DOCTYPE html>
							<html lang="en">
							<head>
							    <meta charset="UTF-8">
							    <meta http-equiv="X-UA-Compatible" content="IE=edge">
							    <meta name="viewport" content="width=device-width, initial-scale=1.0">
							    <link rel="preconnect" href="https://fonts.gstatic.com">
							    <title>The Do-It-Yourself, Online Divorce Solution • TheQuickDivorce.com</title>
							</head>

							<body style="margin: auto;width: 50%;padding:0;box-sizing: border-box;min-width: 600px;color: #000;font-family: Arial, Helvetica, sans-serif;">

							   <header class="header" style="height: 100px;background-color: #162536;text-align: center;">
							       <img src="'.$this->_conf['base_url'].'views/layout/default/img/TQD_logo.png" alt="" style="width: 340px;margin-top: 10px;">
							   </header> 

							   <div class="container" style="background-color: #f2f0e2;">

							        <div class="intro-text" style="text-align: center;">
							            <h3 class="text-big" style="margin: 0;padding-top:20px">COMPLETE YOUR REGISTRATION</h3>
							            <p>Click <a href="'.$this->_conf['base_url'].'getstarted/activation/'.$_token.'">here</a> to activate your account</p>
							        </div>
							        
							        <div class="" style="text-align: center; background-color: #162536; padding: 20px 0;"></div>
							   </div>
							</body>
							</html>';

		            // require RAIZ.'vendor/PHPMailer/src/PHPMailer.php';

		            require RAIZ.'vendor/autoload.php';

		            /*$this->envioMail = new PHPMailer(true);		            
		            $this->envioMail->isSMTP();                                            
				    $this->envioMail->Host       = $this->_conf['smtp']['host'];                     
				    $this->envioMail->SMTPAuth   = true;                                   
				    $this->envioMail->Username   = $this->_conf['smtp']['user'];                     
				    $this->envioMail->Password   = $this->_conf['smtp']['pass'];                               
				    $this->envioMail->Port       = $this->_conf['smtp']['port'];                                    
				    $this->envioMail->SMTPSecure = "ssl";
				    $this->envioMail->SMTPOptions = array(
				        'ssl' => array(
				            'verify_peer' => false,
				            'verify_peer_name' => false,
				            'allow_self_signed' => true
				             )
				        );                   
		            $this->envioMail->From ='notifications@thequickdivorce.com';
		            $this->envioMail->FromName ='The Quick Divorce';
		            $this->envioMail->Subject = 'Activate your account';               
		            $this->envioMail->Body = $_body;
		            $this->envioMail->AddAddress($this->_view->datos['email']);            
		            $this->envioMail->IsHTML(true); 
		            
		            $exito = $this->envioMail->Send();
		            
		            $intentos=1;
		            
		            while ((!$exito) && ($intentos < 3)) {
		                // sleep(5);           
		                $exito = $this->envioMail->Send();              
		                $intentos=$intentos+1;          
		            }
		            
		            if(!$exito) {           
		                echo "Problems sending email to ".$this->envioMail->ErrorInfo;
		                exit;               
		            }*/

					// $this->_sess->destroy('carga_actual');
					// $this->_sess->set('autenticado_front', true);
					// $this->_sess->set('usuario_front', $user->nombre.' '.$user->apellido);
					// $this->_sess->set('id_usuario_front', $user->id);



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
					        'Email' => $this->_view->datos['email'],
					        'Name' => home::convertirCaracteres(validador::getTexto('nombre')).' '.home::convertirCaracteres(validador::getTexto('apellido'))
					      ]
					    ],	
					    'Subject' => 'Activate your account',
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
						echo 'ok';
						exit;
					}




					
    				
    			}
										
					
				

			}else{
				//$this->redireccionar('error/access/404');
				$this->_view->_error = 'There was an error, please try again later.';
				// $this->_view->renderizar('index','login');
				// exit;
			}
		}

    }

    public function activation($_token)
    {

    	$user = contenidos_user::find(array('conditions' => array('token = ?', $this->_xss->xss_clean($_token))));
		if($user){
			$user->estado = 'lead';
			$user->token = '';
			$user->activacion = 'si';
			$user->fecha = date('Y-m-d H:i:s');					
			$user->save();

			// $this->_view->status = 'Your account is already active. <a href="'.$this->_conf['base_url'].'cart/checkout">Get started</a>';
			$this->_view->status = 'Thank you for your confirming your account! Click <a href="'.$this->_conf['base_url'].'cart/checkout">here</a> to complete your purchase.';

			// exit;

		}else{
			$this->_view->status = 'Error activating account';
		}

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
		$this->_view->renderizar('activacion', 'getstarted', 'default');

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
					echo 'You must enter a valid email';
					exit;
				}
				
				//if(!validador::getSql('pass',$this->_conf['baseDatos'])){
				if(!validador::getAlphaNum('password_login')){					
					/*$this->_view->_error = 'Debe introducir su password';
					$this->_view->renderizar('index','login', 'login'); 
					exit;*/
					echo 'You must enter your password';
					exit;
				}
								
				
				
				// 'find' si se busca un solo registro, 'all' si se busca solo 1
				$row = contenidos_user::find(array(
										'conditions' => array(
														'email = ? AND activacion = ? AND password = ? ', 
														$this->_xss->xss_clean($this->_view->datos['email_login']), 'si',
														Hash::getHash('md5', $this->_xss->xss_clean(validador::getPostParam('password_login')), $this->_conf['hash_key'])
														)
											)
									);
				
				
				if(!$row){
					/*$this->_view->_error = 'Usuario y/o password incorrectos';
					$this->_view->renderizar('index','login', 'login');
					exit;*/
					echo 'Incorrect username and/or password';
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
				echo 'There was an error, please try again later.';
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
					echo 'You must enter a valid email';
					exit;
				}
												
				
				
				// 'find' si se busca un solo registro, 'all' si se busca solo 1
				$row = contenidos_user::find(array('conditions' => array('email = ?', $this->_xss->xss_clean($this->_view->datos['email_recover']))));					
				
				if(!$row){						
					echo 'The user is not registered';
					exit;
				}else{

					// $_token = md5(uniqid($this->_view->datos['email'], true));
					$_pretoken = uniqid();
					$_token = Hash::getHash('md5', $_pretoken, $this->_conf['hash_key']);
					$reg = contenidos_user::find(array('conditions' => array('password = ?', $_pretoken)));
					if(!$reg){
						$row->password = $_token;
						$row->save();


						// mail admin
			            /*$_body='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			                    <html xmlns="http://www.w3.org/1999/xhtml">
			                    <head>
			                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			                    <title>The Quick Divorce</title>
			                    </head>                 
			                    <body>
			                    <p>Your new password is <strong>'.$_token.'</strong></p>
			                    </body>
			                    </html>';*/


			            $_body = '<!DOCTYPE html>
									<html lang="en">
									<head>
									    <meta charset="UTF-8">
									    <meta http-equiv="X-UA-Compatible" content="IE=edge">
									    <meta name="viewport" content="width=device-width, initial-scale=1.0">
									    <link rel="preconnect" href="https://fonts.gstatic.com">
									    <title>The Do-It-Yourself, Online Divorce Solution • TheQuickDivorce.com</title>
									</head>

									<body style="margin: auto;width: 50%;padding:0;box-sizing: border-box;min-width: 600px;color: #000;font-family: Arial, Helvetica, sans-serif;">

									   <header class="header" style="height: 100px;background-color: #162536;text-align: center;">
									       <img src="'.$this->_conf['base_url'].'views/layout/default/img/TQD_logo.png" alt="" style="width: 340px;margin-top: 10px;">
									   </header> 

									   <div class="container" style="background-color: #f2f0e2;">

									        <div class="intro-text" style="text-align: center;">
									            <h3 class="text-big" style="margin: 0;padding-top:20px">Hello '.home::convertirCaracteres($row->nombre).' '.home::convertirCaracteres($row->apellido).'</h3>
									            <p>Your new password is <strong>'.$_pretoken.'</strong></p>
									        </div>
									        
									        <div class="" style="text-align: center; background-color: #162536; padding: 20px 0;"></div>
									   </div>
									</body>
									</html>';

			            require RAIZ.'vendor/autoload.php';

		            	/*$this->envioMail = new PHPMailer(true);
			            $this->envioMail->isSMTP();                                            
					    $this->envioMail->Host       = $this->_conf['smtp']['host'];                     
					    $this->envioMail->SMTPAuth   = true;                                   
					    $this->envioMail->Username   = $this->_conf['smtp']['user'];                     
					    $this->envioMail->Password   = $this->_conf['smtp']['pass'];                               
					    $this->envioMail->Port       = $this->_conf['smtp']['port'];                                    
					    $this->envioMail->SMTPSecure = "ssl";
					    $this->envioMail->SMTPOptions = array(
					        'ssl' => array(
					            'verify_peer' => false,
					            'verify_peer_name' => false,
					            'allow_self_signed' => true
					             )
					        );                   
			            $this->envioMail->From ='notifications@thequickdivorce.com';
			            $this->envioMail->FromName ='The Quick Divorce';
			            $this->envioMail->Subject = 'Recover Password';               
			            $this->envioMail->Body = $_body;
			            $this->envioMail->AddAddress($this->_view->datos['email_recover']);            
			            $this->envioMail->IsHTML(true); 
			            
			            $exito = $this->envioMail->Send();
			            
			            $intentos=1;
			            
			            while ((!$exito) && ($intentos < 3)) {
			                // sleep(5);           
			                $exito = $this->envioMail->Send();              
			                $intentos=$intentos+1;          
			            }
			            
			            if(!$exito) {           
			                echo "Problems sending email to".$this->envioMail->ErrorInfo;
			                exit;               
			            }*/


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
						        'Email' => $this->_view->datos['email_recover'],
						        'Name' => home::convertirCaracteres($row->nombre).' '.home::convertirCaracteres($row->apellido)
						      ]
						    ],
						    'Subject' => 'Recover Password',
						    'HTMLPart' => $_body,
						  ]
						]
						];
						$response = $mj->post(Resources::$Email, ['body' => $body]);
						$response->success();

						$_resp = $response->getData();

						// echo $_resp['Messages'][0]['Status'];

						if($_resp['Messages'][0]['Status']=='success'){
							echo 'ok';
							exit;
						}else{
							echo "Problems sending email";
			                exit; 
						}




			            // echo 'ok';
						// exit;

					}else{
						echo 'There was an error, please try again.';
						exit;
					}	


				}
					
					
				

			}else{				
				echo 'There was an error, please try again later.';
				exit;
			}
		}

    }

	public function questions()
    {

    	if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){	
			
				// echo "<pre>";print_r($_POST);exit;	
					
					
				$this->_view->datos = $_POST;


				
				if($_POST['preg1'] == 'yes' && $_POST['preg2'] == 'yes'){

					if($_POST['preg3'] == 'no' && $_POST['preg4'] == 'yes'){
						// PPA
						echo 'ppa';
						exit;
					}
					if($_POST['preg3'] == 'no' && $_POST['preg4'] == 'no'){
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
				
				
				
				

			}else{
				//$this->redireccionar('error/access/404');
				// $this->_view->_error = 'Hubo un error, vuelva a intentarlo mas tarde.';
				echo 'There was an error, please try again later.';
				exit;
			}
		}

    }


	
}
?>