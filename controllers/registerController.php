<?php

use Nucleo\Controller\Controller;

class registerController extends Controller
{
		
    public function __construct()
	{
        parent::__construct();

        $this->getLibrary('class.validador');	

        $this->getLibrary('AntiXSS');
		$this->_xss = new AntiXSS();

		$this->getLibrary('PHPMailerAutoload');
        $this->envioMail = new PHPMailer();		

        if($this->_sess->get('autenticado_front')){
			$this->redireccionar('user');	
		}
		
				
    }
    
   		
	
	/*public function index()
	{	
		$this->redireccionar('usuarios/login');		
    }*/
	

	public function index()
    {

		
    }
    

    /*public function guest()
    {
    	if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
			
			
				if(validador::getInt('enviar') == 1){
					
					
					$this->_view->datos = $_POST;

					 // echo "<pre>";print_r($_POST);exit;					

					

					

					if(!validador::validarEmail($this->_view->datos['email_guest'])){
						// echo 'Debe introducir un email valido';
						echo $this->_conf['text_lang']['header']['guest'][$_SESSION['_lang']]['validacion1'];
						exit;
					}
					

					

	    				

	    	 			//$_mes = ($this->_view->datos['month']<10) ? '0'.$this->_view->datos['month']: $this->_view->datos['month'];
						// $_dia = ($this->_view->datos['day']<10) ? '0'.$this->_view->datos['day']: $this->_view->datos['day'];

						$user = new contenidos_user();
						$user->nombre = $this->_xss->xss_clean('Guest');
						// $user->apellido = $this->_xss->xss_clean(validador::getTexto('lastname'));						
						$user->email = $this->_xss->xss_clean($this->_view->datos['email_guest']);
						// $user->password = Hash::getHash('md5', $this->_xss->xss_clean($this->_view->datos['pass']), $this->_conf['hash_key']);
						// $user->telefono = $this->_xss->xss_clean($this->_view->datos['telefono']);						
						// $user->fecha_nacimiento = $_dia.'-'.$_mes.'-'.$this->_view->datos['year'];
						// $user->identificador = $this->_sess->get('carga_actual');
						$user->fecha = date('Y-m-d H:i:s');
						$user->save();

						// $this->_sess->destroy('carga_actual');
						$this->_sess->set('autenticado_front', true);
						$this->_sess->set('usuario_front', 'invitado');
						$this->_sess->set('id_usuario_front', $user->id);

						// mail admin
			            $_body='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
							<html>
							<head>
								<meta name="viewport" content="width=device-width" />
								<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
								<title>Farmacia París</title>
							</head>
							</body>
							<table cellspacing="0" cellpadding="0" align="center" width="600" style="width: 600px;font-family: \"Arial\",sans-serif;color:#58595b">
							    
							    <tr>
									<td width="20"></td>
									<td>
										<img src="'.$this->_conf['base_url'] .'views/layout/default/img/mailing/logo_Farmacia_Paris.png" alt="Farmacia París" style="display: block;outline: none;width: 110px;border:none">
									</td>
									<td align="right" style="text-align: right;">
										<strong style="font-size: 16px;">Confirmación de cuenta</strong>
										<p style="font-size: 10px;">Suscribete a nuestro newsletter y recibe nuetsras promociones</p>
									</td>
									<td width="20"></td>
								</tr>  
								<tr>
									<td colspan="4" height="30" style="height: 20px;"></td>
								</tr>
								<tr>
									<td colspan="4" height="4" style="height: 4px;background-color: #cfcfce;"></td>
								</tr> 
								<tr>
									<td colspan="4" height="30" style="height: 20px;"></td>
								</tr>
								<tr>
									<td width="20"></td>
									<td colspan="2">
										<p><strong style="font-size: 22px">Hola '.$user->nombre.' '.$user->apellido.',</strong></p>
										<p style="line-height: 1.4">Tu cuenta en Farmacia París E-commerce ha sido creada  satisfactoriamente, da click para acceder</p>
									</td>
									<td width="20"></td>		
								</tr>
								<tr>
									<td width="20"></td>	
									<td colspan="2"><p><a href="'.$this->_conf['base_url'] .'" style="color: #fff;background-color: #d03d44;display: inline-block;padding: 10px 16px;text-decoration: none;">Inicio de sesión</a></p></td>
									<td width="20"></td>	
								</tr>
								<tr><td height="30" colspan="4"></td></tr>
								<tr>
									<td width="20"></td>	
									<td colspan="2"><p><strong style="font-size: 22px">¡Gracias!</strong></p></td>
									<td width="20"></td>	
								</tr>
								<tr><td height="50" colspan="4"></td></tr>

								<tr style="background-color: #cfcfce">
									<td width="20"></td>
									<td colspan="2">
										<table cellspacing="0" cellpadding="0" align="center" width="100%">
											<tr><td colspan="3" height="30"></td></tr>
											<tr>
												<td style="width: 33.3%;font-size: 14px;line-height: 1.4" valign="top">
													<strong>Farmacia Paris</strong> <br>
													<a href="" style="color: #58595b;text-decoration: none;">Servicios</a>
												</td>
												<td style="width: 33.3%;font-size: 14px;line-height: 1.4" valign="top">
													<a href="" style="color: #58595b;text-decoration: none;">Aviso de privacidad</a> <br>
													<a href="" style="color: #58595b;text-decoration: none;">Términos y condiciones</a> <br>
													<a href="" style="color: #58595b;text-decoration: none;">Preguntas Frecuentes</a>
												</td>
												<td style="width: 33.3%;font-size: 14px;line-height: 1.4" valign="top">Síguenos en: <a href=""><img src="'.$this->_conf['base_url'] .'views/layout/default/img/mailing/fb.png" alt="Facebook"></a> <a href=""><img src="'.$this->_conf['base_url'] .'views/layout/default/img/mailing/instagram.png" alt="Instagram"></a></td> 
											</tr>	
											<tr><td colspan="3" height="30"></td></tr>
										</table>
									</td>
									<td width="20"></td>
								</tr>
							</table>
							</html>';
			            
			            $this->envioMail->IsSMTP();
			            $this->envioMail->SMTPAuth = true;
			            $this->envioMail->Host = "secure.emailsrvr.com";
			            $this->envioMail->Username = "tiendaenlinea@farmaciaparis.com"; 
			            $this->envioMail->Password = "SedV&zpYe8fNCk"; 
			            $this->envioMail->Port = 465;  
			            $this->envioMail->From ='tiendaenlinea@farmaciaparis.com';
			            $this->envioMail->FromName ='Farmacia Paris';
			            $this->envioMail->Subject = 'Nuevo Registro';               
			            $this->envioMail->Body = $_body;
			            $this->envioMail->AddAddress($this->_view->datos['email']);            
			            // $this->envioMail->AddAddress('lucianodirisio@gmail.com');
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

	    				
	    			// }
										
					
				}

			}else{
				//$this->redireccionar('error/access/404');
				$this->_view->_error = 'Hubo un error, vuelva a intentarlo mas tarde.';
				// $this->_view->renderizar('index','login');
				// exit;
			}
		}

    }

   

    public function registrarDatos()
    {
    	if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
			
			
				if(validador::getInt('enviar') == 1){
					
					
					$this->_view->datos = $_POST;

					 // echo "<pre>";print_r($_POST);exit;					
					

					if(!validador::getPostParam('firstName')){
						// echo 'Debe introducir un nombre';
						echo $this->_conf['text_lang']['header']['registro'][$_SESSION['_lang']]['validacion1'];
						exit;
					}

					if(!validador::getPostParam('Surname')){
						echo $this->_conf['text_lang']['header']['registro'][$_SESSION['_lang']]['validacion2'];
						// echo 'Debe introducir un apellido';
						exit;
					}


					if(!validador::validarEmail($this->_view->datos['email'])){
						echo $this->_conf['text_lang']['header']['registro'][$_SESSION['_lang']]['validacion3'];
						// echo 'Debe introducir un email valido';
						exit;
					}

					if(!validador::validarEmail($this->_view->datos['emailConfirm'])){
						echo $this->_conf['text_lang']['header']['registro'][$_SESSION['_lang']]['validacion4'];
						// echo 'Debe introducir un email valido';
						exit;
					}

					if($this->_view->datos['email']!= $this->_view->datos['emailConfirm']){					
						echo $this->_conf['text_lang']['header']['registro'][$_SESSION['_lang']]['validacion5'];
						// echo 'El email y repetir email no coinciden';
						exit;
					}

					if(!validador::getAlphaNum('pass')){					
						echo $this->_conf['text_lang']['header']['registro'][$_SESSION['_lang']]['validacion6'];
						// echo 'Debe introducir su contraseña';
						exit;
					}

					
					if(!validador::getAlphaNum('passConfirm')){					
						echo $this->_conf['text_lang']['header']['registro'][$_SESSION['_lang']]['validacion7'];
						// echo 'Debe introducir su contraseña';
						exit;
					}


					$_caracteres = 8;
					if(!validador::largoCadenaMin(validador::getTexto('pass'), $_caracteres)){
	                    // echo 'El Password debe tener como mínimo 8 caracteres';
	                    echo $this->_conf['text_lang']['header']['registro'][$_SESSION['_lang']]['validacion8'];
						exit;
	                }

	                if(!preg_match('/[a-z]/', validador::getTexto('pass'))){
						// echo'El Password debe tener al menos una letra Minuscula.';
						echo $this->_conf['text_lang']['header']['registro'][$_SESSION['_lang']]['validacion9'];
						exit;
					}


					if(!preg_match('/[0-9]/', validador::getTexto('pass'))){
						// echo 'El Password debe tener al menos un Número.';
						echo $this->_conf['text_lang']['header']['registro'][$_SESSION['_lang']]['validacion10'];
						exit;
					}

					if(!preg_match('/[A-Z]/', validador::getTexto('pass'))){
						// echo 'El Password debe tener al menos una letra Mayuscula.';
						echo $this->_conf['text_lang']['header']['registro'][$_SESSION['_lang']]['validacion11'];
						exit;
					}


					if($this->_view->datos['pass']!= $this->_view->datos['passConfirm']){					
						// echo 'Contraseña y repetir contraseña no coinciden';
						echo $this->_conf['text_lang']['header']['registro'][$_SESSION['_lang']]['validacion12'];
						exit;
					}
									
					
					
					

					$roww = contenidos_user::find(array('conditions' => array('nombre = ? AND email = ?', $this->_xss->xss_clean(validador::getTexto('firstName')), $this->_xss->xss_clean(validador::getTexto('email')))));
	    			if($roww){
	    				echo $this->_conf['text_lang']['header']['registro'][$_SESSION['_lang']]['validacion_user'];
	    				// echo 'Ya hay un usuario registrado con este email y nombre';
						exit;

	    			}else{

	    				

						$user = new contenidos_user();
						$user->nombre = $this->_xss->xss_clean(validador::getTexto('firstName'));
						$user->apellido = $this->_xss->xss_clean(validador::getTexto('Surname'));						
						$user->email = $this->_xss->xss_clean($this->_view->datos['email']);
						// $user->password = $this->_xss->xss_clean($this->_view->datos['pass']);
						$user->password = hash('sha256', $this->_xss->xss_clean($this->_view->datos['pass']));
						$user->fecha = date('Y-m-d H:i:s');
						$user->save();

						// $this->_sess->destroy('carga_actual');
						$this->_sess->set('autenticado_front', true);
						$this->_sess->set('usuario_front', $user->nombre);
						$this->_sess->set('id_usuario_front', $user->id);

						// mail admin
			            

			            echo 'ok';
						exit;

	    				
	    			}
										
					
				}

			}else{
				echo $this->_conf['text_lang']['header']['registro'][$_SESSION['_lang']]['autenticacion'];
				// $this->_view->_error = 'Hubo un error, vuelva a intentarlo mas tarde.';
				// $this->_view->renderizar('index','login');
				exit;
			}
		}

    }*/

   /* public function login()
    {

    	if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
			
			
				if(validador::getInt('enviar') == 1){
					
					
					$this->_view->datos = $_POST;
					
					if(!validador::validarEmail($this->_view->datos['Email'])){
						// echo 'Debe introducir un email valido';
						echo $this->_conf['text_lang']['header']['login'][$_SESSION['_lang']]['validacion1'];
						exit;
					}
					
					if(!validador::getAlphaNum('pasword')){					
						echo $this->_conf['text_lang']['header']['login'][$_SESSION['_lang']]['validacion2'];
						exit;
					}

					$_caracteres = 8;
					if(!validador::largoCadenaMin(validador::getTexto('pasword'), $_caracteres)){
	                    echo $this->_conf['text_lang']['header']['login'][$_SESSION['_lang']]['validacion3'];
						exit;
	                }

	                if(!preg_match('/[a-z]/', validador::getTexto('pasword'))){
						echo $this->_conf['text_lang']['header']['login'][$_SESSION['_lang']]['validacion4'];
						exit;
					}


					if(!preg_match('/[0-9]/', validador::getTexto('pasword'))){
						echo $this->_conf['text_lang']['header']['login'][$_SESSION['_lang']]['validacion5'];
						exit;
					}
					
					if(!preg_match('/[A-Z]/', validador::getTexto('pasword'))){
						echo $this->_conf['text_lang']['header']['login'][$_SESSION['_lang']]['validacion6'];
						exit;
					}				
					
					
					// 'find' si se busca un solo registro, 'all' si se busca solo 1
					$row = contenidos_user::find(array(
											'conditions' => array(
															'email = ? AND password = ?', 
															$this->_xss->xss_clean($this->_view->datos['Email']),
															hash('sha256', $this->_xss->xss_clean(validador::getPostParam('pasword')))
															)
												)
										);
					
					
					if(!$row){
						echo $this->_conf['text_lang']['header']['login'][$_SESSION['_lang']]['validacion_user'];
						// echo 'Usuario y/o password incorrectos';
						exit;
					}
					
										
					$this->_sess->set('autenticado_front', true);
					$this->_sess->set('usuario_front', $row->nombre);
					$this->_sess->set('id_usuario_front', $row->id);

					echo 'ok';
					exit;
					// $this->redireccionar('home');
				}

			}else{
				echo $this->_conf['text_lang']['header']['login'][$_SESSION['_lang']]['autenticacion'];
				// echo 'Hubo un error, vuelva a intentarlo mas tarde.';
				exit;
			}
		}

    }*/


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


		$this->_view->titulo = 'The Quick Divorce';	
		$this->_view->renderizar('index', 'register', 'default');

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


				if($this->_view->datos['password']!= $this->_view->datos['repeat_password']){					
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
		            $_body='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		                    <html xmlns="http://www.w3.org/1999/xhtml">
		                    <head>
		                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		                    <title>The Quick Divorce</title>
		                    </head>                 
		                    <body>
		                    <p>Haga click <a href="'.$this->_conf['base_url'].'getstarted/activation/'.$_token.'">aqui</a> para activar su cuenta</p>
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
		            $this->envioMail->Subject = 'Active su cuenta';               
		            $this->envioMail->Body = $_body;
		            // $this->envioMail->AddAddress($this->_view->datos['email']);            
		            $this->envioMail->AddAddress('lucianodirisio@gmail.com');
		            $this->envioMail->IsHTML(true); 
		            
		            $exito = $this->envioMail->Send();
		            
		            $intentos=1;
		            
		            while ((!$exito) && ($intentos < 3)) {
		                // sleep(5);           
		                $exito = $this->envioMail->Send();              
		                $intentos=$intentos+1;          
		            }
		            
		            if(!$exito) {           
		                echo "Problemas enviando correo electrónico a ".$this->envioMail->ErrorInfo;
		                exit;               
		            }

					// $this->_sess->destroy('carga_actual');
					// $this->_sess->set('autenticado_front', true);
					// $this->_sess->set('usuario_front', $user->nombre.' '.$user->apellido);
					// $this->_sess->set('id_usuario_front', $user->id);

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

    public function activation($_token)
    {

    	$user = contenidos_user::find(array('conditions' => array('token = ?', $this->_xss->xss_clean($_token))));
		if($user){
			$user->estado = 'lead';
			$user->token = '';
			$user->activacion = 'si';
			$user->fecha = date('Y-m-d H:i:s');					
			$user->save();

			$this->_view->status = 'Su cuenta ya esta activa. <a href="'.$this->_conf['base_url'].'getstarted">comenzar</a>';
			// exit;

		}else{
			$this->_view->status = 'Error al activar la cuenta';
		}


		$this->_view->titulo = 'The Quick Divorce';	
		$this->_view->renderizar('activacion', 'register', 'default');

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

    /*public function recuperar()
    {
        if($this->_sess->get('autenticado_front')){
            $this->redireccionar('home');
        }
        
      
		$this->_view->titulo = 'Recuperar contraseña';
        $this->_view->renderizar('recuperar','registro', 'login');
    }*/
	
	/*public function recuperarPass()
    {

    	if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
			
			
				if(validador::getInt('enviar') == 1){
					
					
					$this->_view->datos = $_POST;
					
					// if(!validador::getAlphaNum('usuario')){
					if(!validador::validarEmail($this->_view->datos['email'])){						
						echo 'Debe introducir un email valido';
						exit;
					}
													
					
					
					// 'find' si se busca un solo registro, 'all' si se busca solo 1
					$row = contenidos_user::find(array('conditions' => array('email = ?', $this->_xss->xss_clean($this->_view->datos['email']))));					
					
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
				                    <title>Farmacia Paris</title>
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
				            $this->envioMail->From ='info@farmaciaparis.com.ar';
				            $this->envioMail->FromName ='Farmacia Paris';
				            $this->envioMail->Subject = 'Recuperar '.utf8_decode('contraseña');               
				            $this->envioMail->Body = $_body;
				            $this->envioMail->AddAddress($this->_view->datos['email']);            
				            // $this->envioMail->AddAddress('lucianodirisio@gmail.com');
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
					
					
				}

			}else{				
				echo 'Hubo un error, vuelva a intentarlo mas tarde.';
				exit;
			}
		}

    }*/

    public function setLang()
	{
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
			
				$_lang = (int) $_POST['valor'];

				$_SESSION['_lang'] = $_lang;

				echo 1;
				exit;
			}
		}
	
	}

    public function signout()
	{
		$this->_sess->destroy('autenticado_front');
        $this->_sess->destroy('id_usuario_front');
        $this->_sess->destroy('usuario_front');
        // $this->_sess->destroy('_tipo_envio');
        // $this->_sess->destroy('_datos_compra');
        // $this->_sess->destroy('_datos_compra_finales');
        $this->redireccionar();
    }
	
	
}
?>