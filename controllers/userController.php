<?php

use Nucleo\Controller\Controller;
use \Mailjet\Resources;

class userController extends Controller
{
	public $homeGestion;
		
    public function __construct()
	{

        parent::__construct();

        $this->getLibrary('class.validador');

        $this->getLibrary('class.home');		
		$this->homeGestion = new home();	

        $this->getLibrary('AntiXSS');
		$this->_xss = new AntiXSS();

				
    }
    
   		
	
	public function index()
	{	
		$this->redireccionar('user/myaccount');		
    }
	

	public function myaccount()
    {
        /*if(!$this->_sess->get('autenticado_front')){
            $this->redireccionar('getstarted');
        }*/

        if($this->_sess->get('autenticado_front')){
			$this->_view->user = $this->homeGestion->traerUser($this->_sess->get('id_usuario_front'));
		}
		// $this->_view->address = $this->homeGestion->traerShipping($this->_sess->get('id_usuario_front'));
		// $this->_view->billing = $this->homeGestion->traerBilling($this->_sess->get('id_usuario_front'));
		

		// echo "<pre>";print_r($this->_view->user);exit;

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
        $this->_view->renderizar('myaccount','user', 'default');
    }

    public function myorders()
    {
        if(!$this->_sess->get('autenticado_front')){
            $this->redireccionar('user/myaccount');
        }


		$this->_view->user = $this->homeGestion->traerUser($this->_sess->get('id_usuario_front'));
		$this->_view->orders = $this->homeGestion->traerOrders($this->_sess->get('id_usuario_front'), 'approved');

		for ($i=0; $i < count($this->_view->orders) ; $i++) { 
			// $_data_pago = base64_decode($this->_view->orders[$i]['data_pago']);
			$this->_view->orders[$i]['detalle'] =  $this->homeGestion->traerDataOrder($this->_view->orders[$i]['id']);
			$this->_view->orders[$i]['forms'] =  $this->homeGestion->traerFormsPorUser($this->_view->orders[$i]['id_user'], $this->_view->orders[$i]['id']);
		}
				
		
		// $this->_view->address = $this->homeGestion->traerShipping($this->_sess->get('id_usuario_front'));
		// $this->_view->billing = $this->homeGestion->traerBilling($this->_sess->get('id_usuario_front'));
		
		 // echo "<pre>";print_r($this->_view->orders);exit;
		// echo "<pre>";print_r($this->_view->orders[3]['data_pago']->source->name);exit;

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

		// $this->_view->titulo = 'My Orders';
        $this->_view->renderizar('myorders','user', 'default');
    }


    public function editarDatos()
    {
    	if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
				
					
					
				$this->_view->datos = $_POST;

				 // echo "<pre>";print_r($_POST);exit;

				if($this->_view->datos['email'] !=''){

					if(!validador::validarEmail($this->_view->datos['email'])){
						/*$this->_view->_error = 'Debe introducir un email valido';
						$this->_view->renderizar('index','registro');
						exit;*/

						echo 'You must enter a valid email';
						exit;
					}

				}



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

				
									

				$user = contenidos_user::find(array('conditions' => array('id = ?', $this->_view->datos['_id'])));
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

					if($this->_view->datos['password'] !=''){
						// $user->password = $this->_xss->xss_clean($this->_view->datos['pass']);
						$user->password = Hash::getHash('md5', $this->_xss->xss_clean($this->_view->datos['password']), $this->_conf['hash_key']);
					}
					// $user->token = $_token;
					// $user->activacion = 'si';
					// $user->fecha = date('Y-m-d H:i:s');					
					$user->save();


					// $this->_sess->destroy('carga_actual');
					// $this->_sess->set('autenticado_front', true);
					$this->_sess->set('usuario_front', $user->nombre.' '.$user->apellido);
					// $this->_sess->set('id_usuario_front', $user->id);

					echo 'ok';
					exit;
    				
    			}else{
    				echo 'User does not exist';
					exit;
    			}
										
					
				

			}else{
				//$this->redireccionar('error/access/404');
				echo 'There was an error, please try again later.';
				// $this->_view->renderizar('index','login');
				exit;
			}
		}

    }



    public function myforms($_id_compra, $_id_form)
    {
        if(!$this->_sess->get('autenticado_front')){
            $this->redireccionar('user/myaccount');
        }

        $this->_view->id_form = $_id_form;
		$this->_view->user = $this->homeGestion->traerUser($this->_sess->get('id_usuario_front'));
		$this->_view->condados =  $this->homeGestion->traerFormsCondados();
		$this->_view->states =  $this->homeGestion->traerFormsStates();
		$this->_view->order = $this->homeGestion->traerOrder($_id_compra);
		$this->_view->order['forms'] =  $this->homeGestion->traerFormsPorId2($_id_form, $this->_sess->get('id_usuario_front'));
		// $this->_view->order['forms'] =  $this->homeGestion->traerFormsPorId($_id_form);
		if($this->_view->order['forms']){

			$this->_view->order['form_info']['id_producto'] =$this->_view->order['forms']['id_producto'];
			$this->_view->order['form_info']['item_producto'] = home::traerProductoPorId($this->_view->order['forms']['id_producto'])->item;
			$this->_view->order['form_info']['estado'] =$this->_view->order['forms']['estado'];
			$this->_view->order['form_info']['img'] =$this->_view->order['forms']['img'];
			$this->_view->order['form_info']['fecha'] =$this->_view->order['forms']['fecha'];
			unset($this->_view->order['forms']['id'], $this->_view->order['forms']['id_compra'], $this->_view->order['forms']['id_user'], $this->_view->order['forms']['id_producto'], $this->_view->order['forms']['estado'], $this->_view->order['forms']['img'], $this->_view->order['forms']['fecha']);	
			

			// echo "<pre>";print_r($_comparar);exit;
			//  echo "<pre>";print_r($this->_view->modules);//exit;
			//  echo "<pre>";print_r($this->_view->order['forms']);exit;

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
				$this->_view->order['forms']['children_residence'] = unserialize(base64_decode($this->_view->order['forms']['children_residence'] ));
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


		}else{
			$this->redireccionar('user/myorders');
		}


		// echo "<pre>";print_r($this->_view->order);exit;

		// $this->_view->titulo = 'My Forms';

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

		if($this->_view->order['form_info']['item_producto'] == 'msa'){
			$this->_view->renderizar('msa2','forms', 'default');
		}else if($this->_view->order['form_info']['item_producto'] == 'ppa'){
			$this->_view->renderizar('ppa2','forms', 'default');
		}else{
			$this->_view->renderizar('msappa2','forms', 'default');
		}
        
    }

    public function myforms2($_id_compra, $_id_form)
    {
        if(!$this->_sess->get('autenticado_front')){
            $this->redireccionar('user/myaccount');
        }

        $this->_view->id_form = $_id_form;
		$this->_view->user = $this->homeGestion->traerUser($this->_sess->get('id_usuario_front'));
		$this->_view->condados =  $this->homeGestion->traerFormsCondados();
		$this->_view->states =  $this->homeGestion->traerFormsStates();
		$this->_view->order = $this->homeGestion->traerOrder($_id_compra);
		$this->_view->order['forms'] =  $this->homeGestion->traerFormsPorId2($_id_form, $this->_sess->get('id_usuario_front'));
		// $this->_view->order['forms'] =  $this->homeGestion->traerFormsPorId($_id_form);
		if($this->_view->order['forms']){

			$this->_view->order['form_info']['id_producto'] =$this->_view->order['forms']['id_producto'];
			$this->_view->order['form_info']['item_producto'] = home::traerProductoPorId($this->_view->order['forms']['id_producto'])->item;
			$this->_view->order['form_info']['estado'] =$this->_view->order['forms']['estado'];
			$this->_view->order['form_info']['img'] =$this->_view->order['forms']['img'];
			$this->_view->order['form_info']['fecha'] =$this->_view->order['forms']['fecha'];
			unset($this->_view->order['forms']['id'], $this->_view->order['forms']['id_compra'], $this->_view->order['forms']['id_user'], $this->_view->order['forms']['id_producto'], $this->_view->order['forms']['estado'], $this->_view->order['forms']['img'], $this->_view->order['forms']['fecha']);	
			

			// echo "<pre>";print_r($_comparar);exit;
			//  echo "<pre>";print_r($this->_view->modules);//exit;
			//  echo "<pre>";print_r($this->_view->order['forms']);exit;

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


			if($this->_view->order['forms']['custody_and_visitation']!=''){
				$this->_view->order['forms']['custody_and_visitation'] = unserialize(base64_decode($this->_view->order['forms']['custody_and_visitation'] ));
			}

			if($this->_view->order['forms']['parenting_schedule_holidays']!=''){
				$this->_view->order['forms']['parenting_schedule_holidays'] = unserialize(base64_decode($this->_view->order['forms']['parenting_schedule_holidays'] ));
			}

			if($this->_view->order['forms']['parenting_schedule_special_occasions']!=''){
				$this->_view->order['forms']['parenting_schedule_special_occasions'] = unserialize(base64_decode($this->_view->order['forms']['parenting_schedule_special_occasions'] ));
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


		}else{
			$this->redireccionar('user/myorders');
		}


		// echo "<pre>";print_r($this->_view->order);exit;

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

		// $this->_view->titulo = 'My Forms';

		if($this->_view->order['form_info']['item_producto'] == 'msa'){
			$this->_view->renderizar('msa2','forms', 'default');
		}else if($this->_view->order['form_info']['item_producto'] == 'ppa'){
			$this->_view->renderizar('ppa2','forms', 'default');
		}else{
			$this->_view->renderizar('msappa2','forms', 'default');
		}
        
    }


    public function consolidarForm()
    {
    	if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
				
				// echo "<pre>";print_r($_POST);exit;	
					
				$this->_view->datos = $_POST;
				$c = $this->_view->datos['_form_part'];

				// $this->_view->user = $this->homeGestion->traerUser($this->_sess->get('id_usuario_front'));

				unset($_POST['_csrf'],$_POST['_id_form'],$_POST['_item'],$_POST['_form_part']);

				$_data = base64_encode(serialize($this->_xss->xss_clean($_POST)));

				  // echo "_information<pre>";print_r($_information);//exit;
				  // echo "_residency<pre>";print_r($_residency);//exit;

				$_datos = contenidos_forms_respuesta::find(array('conditions' => array('id = ?', $this->_view->datos['_id_form'])));	
				if($_datos){
					$_datos->$c = $_data;	
					$_datos->estado = ($_datos->estado =='not started') ? 'incomplete' : $_datos->estado;					
					$_datos->save();
				}

				$_check =  $this->homeGestion->checkFinalForm($_datos->id_producto, $this->_view->datos['_id_form']);
				if(!empty($_check)){
					echo "ok";
				}else{

					// envio aviso mail
					$_body = '<!DOCTYPE html>
								<html lang="en">
								<head>
								    <meta charset="UTF-8">
								    <meta http-equiv="X-UA-Compatible" content="IE=edge">
								    <meta name="viewport" content="width=device-width, initial-scale=1.0">
								    <link rel="preconnect" href="https://fonts.gstatic.com">
								    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
								    <link href="https://fonts.googleapis.com/css2?family=Karla:wght@300&display=swap" rel="stylesheet">
								    <title>The Quick Divorce</title>
								</head>

								<body style="margin: auto;width: 50%;padding:0;box-sizing: border-box;min-width: 600px;font-family: \'Karla\';color: #000;">

								   <header class="header" style="height: 100px;background-color: #162536;">
								       <img src="'.$this->_conf['base_url'].'views/layout/default/img/TQD_logo.png" alt="" style="width: 340px;margin: auto;display: block;padding-top: 10px;">
								   </header> 

								   <div class="container" style="background-color: #f2f0e2; padding: 0 50px;">

								        <div class="intro-text" >
								            <br><br>
								            <h3 class="text-big" style="margin: 0;padding-top:10px; text-align: center; font-size:20px;">A new form has been completed!</h3>
								            <br><br>
								            <p class="text-small">
								                CLIENT NAME: <strong>'.home::convertirCaracteres(strtoupper($this->_sess->get('usuario_front'))).'</strong>
								                <br>
								                PACKAGE: <strong>'.strtoupper($this->_view->datos['_item']).'</strong>
								            </p>            

								            <a href="'.$this->_conf['base_url'].'administrador/forms" style="display: block;width: 190px;padding: 5px;border-radius: 4px;margin: 25px auto 0;box-shadow: 3px 5px 10px rgba(0,0,0,0.2);outline: 0;border: 0;background: #c5d7de;cursor: pointer;">
								            	<p style="height: 100%; width: 100%; display: block; border: solid 1px #fff; color: #1d2731; font-family:\'gothambold\', sans-serif; font-weight: bold; font-size: 13px; text-align: center; text-transform: uppercase; padding: 10px 0; margin: 0;">view details</p>
								            </a>
								          
								        </div>

								        <br><br>
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
		            $this->envioMail->Subject = 'New Completed '.strtoupper($this->_view->datos['_item']).': '.home::convertirCaracteres(strtoupper($this->_sess->get('usuario_front')));               
		            $this->envioMail->Body = $_body;
		            $this->envioMail->AddAddress('info@thequickdivorce.com');
		            // $this->envioMail->AddAddress('notifications@thequickdivorce.com');
		            $this->envioMail->addCC('aliette@carolanfamilylaw.com');
		            $this->envioMail->IsHTML(true); 
		            
		            $exito = $this->envioMail->Send();
		            
		            $intentos=1;
		            
		            while ((!$exito) && ($intentos < 3)) {
		                // sleep(5);           
		                $exito = $this->envioMail->Send();              
		                $intentos=$intentos+1;          
		            }
		            
		            if(!$exito) {           
		                echo $this->envioMail->ErrorInfo;
		                exit;               
		            }*/

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
					    'Subject' => 'New Completed '.strtoupper($this->_view->datos['_item']).': '.home::convertirCaracteres(strtoupper($this->_sess->get('usuario_front'))),
					    'HTMLPart' => $_body,
					  ]
					]
					];
					$response = $mj->post(Resources::$Email, ['body' => $body]);
					$response->success();

					$_resp = $response->getData();

					// echo $_resp['Messages'][0]['Status'];

					if($_resp['Messages'][0]['Status']=='success'){
						echo "final";
						exit;
					}else{
						echo "Error";
		                exit; 
					}


					
				}				
							
				// echo json_encode($jsondata);
			}
		}
	}


	public function consolidarForm2()
    {
    	if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
				
				// echo "<pre>";print_r($_POST);exit;	
					
				$this->_view->datos = $_POST;
				$c = $this->_view->datos['_form_part'];

				$_id_form = $_POST['_id_form'];
				$_item_producto = $_POST['_item'];

				unset($_POST['_csrf'],$_POST['_id_form'],$_POST['_item'],$_POST['_form_part']);

				$_data = base64_encode(serialize($this->_xss->xss_clean($_POST)));

				  // echo "_information<pre>";print_r($_information);//exit;
				  // echo "_residency<pre>";print_r($_residency);//exit;


				$_child = $_POST['child-firstname'];
				$_html='';
				for ($i=0; $i <count($_child); $i++) { 
					if($i==0){
						$_html .='<tr>
	                            <td width="40%"><strong>'.$_child[$i].'\'s Birthday</strong></td>
	                            <td width="20%"><input type="radio" name="custody-SpecialOccasions-child[]" value="Mother"> <label for="">Mother</label></td>
	                            <td width="20%"><input type="radio" name="custody-SpecialOccasions-child[]" value="Father"> <label for="">Father</label></td>
	                            <td width="20%"></td>
	                        </tr>
	                        <tr>
                            <td width="40%">Year</td>
                            <td width="20%"><input type="radio" name="custody-SpecialOccasions-child-Year[]" value="Even" > <label for="">Even</label></td>
                            <td width="20%"><input type="radio" name="custody-SpecialOccasions-child-Year[]" value="Odd"> <label for="">Odd</label></td>
                            <td width="20%"><input type="radio" name="custody-SpecialOccasions-child-Year[]" value="Every"> <label for="">Every</label></td>
                        </tr>';
                    }else{
                      	$_html .='<tr>
	                            <td width="40%"><strong>'.$_child[$i].'\'s Birthday</strong></td>
	                            <td width="20%"><input type="radio" name="custody-SpecialOccasions-child['.$i.']" value="Mother"> <label for="">Mother</label></td>
	                            <td width="20%"><input type="radio" name="custody-SpecialOccasions-child['.$i.']" value="Father"> <label for="">Father</label></td>
	                            <td width="20%"></td>
	                        </tr>
	                        <tr>
                            <td width="40%">Year</td>
                            <td width="20%"><input type="radio" name="custody-SpecialOccasions-child-Year['.$i.']" value="Even" > <label for="">Even</label></td>
                            <td width="20%"><input type="radio" name="custody-SpecialOccasions-child-Year['.$i.']" value="Odd"> <label for="">Odd</label></td>
                            <td width="20%"><input type="radio" name="custody-SpecialOccasions-child-Year['.$i.']" value="Every"> <label for="">Every</label></td>
                        </tr>';
                    }
				}

				$jsondata['html'] =$_html;


				$_forms =  $this->homeGestion->traerFormsPorId2($_id_form, $this->_sess->get('id_usuario_front'));
				// $this->_view->order['forms'] =  $this->homeGestion->traerFormsPorId($_id_form);
				if($_forms){

					/*if($_forms['children_residence']!=''){
						$_forms['children_residence'] = unserialize(base64_decode($_forms['children_residence']));
					}*/
					$_forms['children_residence']='';
				}

				// echo "<pre>";print_r($_forms['children_residence']);exit;	

				$_residence='';
				for ($i=0; $i <count($_child); $i++) { 
					if($i==0){

						$_residence .='<h2>Children\'s Residence</h2>
				                       <form name="children_residence" id="children_residence" autocomplete="off">
				                        <input type="hidden" name="_csrf" value="'.$this->_sess->get('_csrf').'">
				                        <input type="hidden" name="_id_form" value="'.$_id_form.'">
				                        <input type="hidden" name="_item" value="'.$_item_producto.'">
				                        <input type="hidden" name="_form_part" value="children_residence">
				                       <table cellpadding="0" cellspacing="0" width="100%">
				                           <tr>
				                               <td colspan="3">List the residences of the minor children of this partnership</td>
				                           </tr>
				                       </table>';

				                        if(is_array($_POST) && $_POST['child-firstname'][0]!=''){
				                        for ($w=0; $w<count($_POST['child-firstname']); $w++){

				                        // if(isset($_forms['children_residence']) && $_forms['children_residence'] !=''){

				                        $_residence .='<table cellpadding="0" cellspacing="0" width="100%">
				                            <tr>
				                                <td colspan="5"><strong class="title-child">Child '.$_POST['child-firstname'][$w].':</strong></td>
				                            </tr>
				                        </table>

				                        <table cellpadding="0" cellspacing="0" width="100%" id="childrenresidence'.$w.'">

				                           
				                            <tr>
				                                <td width="50%">Where has this child been residing for the last five years?</td>
				                                <td width="10%"></td>
				                                <td width="40%" colspan="3"><input type="text" name="childrenresidence-address['.$_POST['child-firstname'][$w].'][]" value="'.((isset($_forms['children_residence']) && $_forms['children_residence'] !='' && $_forms['children_residence']['childrenresidence-address'][$_POST['child-firstname'][$w]][0]!='') ? $_forms['children_residence']['childrenresidence-address'][$_POST['child-firstname'][$w]][0] : '').'"></td>
				                            </tr>
				                            <tr>
				                                <td width="50%">Total time living in this  address</td>
				                                <td width="10%"></td>
				                                <td width="40%" colspan="3"><input type="number" name="childrenresidence-time['.$_POST['child-firstname'][$w].'][]" value="'.((isset($_forms['children_residence']) && $_forms['children_residence'] !='' && $_forms['children_residence']['childrenresidence-time'][$_POST['child-firstname'][$w]][0]!='') ? ($_forms['children_residence']['childrenresidence-time'][$_POST['child-firstname'][$w]][0]) : '').'"></td>
				                            </tr>
				                            <tr>
				                                <td width="50%">Total people residing in this residence</td>
				                                <td width="10%"></td>
				                                <td width="40%" colspan="3"><input type="number" name="childrenresidence-people['.$_POST['child-firstname'][$w].'][]" value="'.((isset($_forms['children_residence']) && $_forms['children_residence'] !='' && $_forms['children_residence']['childrenresidence-people'][$_POST['child-firstname'][$w]][0]!='') ? ($_forms['children_residence']['childrenresidence-people'][$_POST['child-firstname'][$w]][0]) : '').'"></td>
				                            </tr>
				                          <tr>
				                                <td width="50%">Relationship</td>
				                                <td width="10%"></td>
				                                <td width="15%"><input type="radio" name="childrenresidence-relationship['.$_POST['child-firstname'][$w].'][]" value="Mother" '.((isset($_forms['children_residence']) && isset($_forms['children_residence']['childrenresidence-relationship']) && $_forms['children_residence']['childrenresidence-relationship'][$_POST['child-firstname'][$w]][0]=='Mother') ? 'checked="checked"' : '').'> <label>Mother</label></td>

				                                <td width="14%"><input type="radio" name="childrenresidence-relationship['.$_POST['child-firstname'][$w].'][]" value="Father" '.((isset($_forms['children_residence']) && isset($_forms['children_residence']['childrenresidence-relationship']) && $_forms['children_residence']['childrenresidence-relationship'][$_POST['child-firstname'][$w]][0]=='Father') ? 'checked="checked"' : '').'> <label>Father</label></td>

				                                <td width="13%"><input type="radio" name="childrenresidence-relationship['.$_POST['child-firstname'][$w].'][]" value="Both" '.((isset($_forms['children_residence']) && isset($_forms['children_residence']['childrenresidence-relationship']) && $_forms['children_residence']['childrenresidence-relationship'][$_POST['child-firstname'][$w]][0]=='Both') ? 'checked="checked"' : '').'> <label>Both</label></td>
				                                <td width="20%"><input type="hidden" name="childresidence'.$w.'_cont[]" value=""></td>
				                            </tr>
				                            
				                            <tr><td colspan="5"><hr style="color: #eaeaea;"></td></tr>

				                        </table>

				                        

				                        <div id="duplicate-childrenresidence'.$w.'">';

				                            if(isset($_forms['children_residence']) && $_forms['children_residence'] !='' && $_forms['children_residence']['childrenresidence-address'][$_POST['child-firstname'][$w]][0]!=''){
				                            if(count($_forms['children_residence']['childrenresidence-address'][$_POST['child-firstname'][$w]])>1){
				                            for ($i=1; $i<count($_forms['children_residence']['childrenresidence-address'][$_POST['child-firstname'][$w]]); $i++){


				                            $_residence .='<table cellpadding="0" cellspacing="0" width="100%" id="childrenresidence'.$w.'_'.$i.'">  

				                                <tr>
				                                    <td width="50%">Where has this child been residing for the last five years?</td>
				                                    <td width="10%"></td>
				                                    <td width="40%" colspan="3"><input type="text" name="childrenresidence-address['.$_POST['child-firstname'][$w].']['.$i.']" value="'.((isset($_forms['children_residence']) && $_forms['children_residence'] !='' && $_forms['children_residence']['childrenresidence-address'][$_POST['child-firstname'][$w]][$i]!='') ? $_forms['children_residence']['childrenresidence-address'][$_POST['child-firstname'][$w]][$i] : '').'"></td>
				                                </tr>
				                                <tr>
				                                    <td width="50%">Total time living in this  address</td>
				                                    <td width="10%"></td>
				                                    <td width="40%" colspan="3"><input type="number" name="childrenresidence-time['.$_POST['child-firstname'][$w].']['.$i.']" value="'.((isset($_forms['children_residence']) && $_forms['children_residence'] !='' && $_forms['children_residence']['childrenresidence-time'][$_POST['child-firstname'][$w]][$i]!='') ? $_forms['children_residence']['childrenresidence-time'][$_POST['child-firstname'][$w]][$i] : '').'"></td>
				                                </tr>
				                                <tr>
				                                    <td width="50%">Total people residing in this residence</td>
				                                    <td width="10%"></td>
				                                    <td width="40%" colspan="3"><input type="number" name="childrenresidence-people['.$_POST['child-firstname'][$w].']['.$i.']" value="'.((isset($_forms['children_residence']) && $_forms['children_residence'] !='' && $_forms['children_residence']['childrenresidence-people'][$_POST['child-firstname'][$w]][$i]!='') ? $_forms['children_residence']['childrenresidence-people'][$_POST['child-firstname'][$w]][$i] : '').'"></td>
				                                </tr>
				                                <tr>
				                                    <td width="50%">Relationship</td>
				                                    <td width="10%"></td>
				                                    <td width="15%"><input type="radio" name="childrenresidence-relationship['.$_POST['child-firstname'][$w].']['.$i.']" value="Mother" '.((isset($_forms['children_residence']) && isset($_forms['children_residence']['childrenresidence-relationship']) && $_forms['children_residence']['childrenresidence-relationship'][$_POST['child-firstname'][$w]][$i]=='Mother') ? 'checked="checked"' : '').'> <label>Mother</label></td>

				                                    <td width="14%"><input type="radio" name="childrenresidence-relationship['.$_POST['child-firstname'][$w].']['.$i.']" value="Father" '.((isset($_forms['children_residence']) && isset($_forms['children_residence']['childrenresidence-relationship']) && $_forms['children_residence']['childrenresidence-relationship'][$_POST['child-firstname'][$w]][$i]=='Father') ? 'checked="checked"' : '').'> <label>Father</label></td>
				                                    
				                                    <td width="13%"><input type="radio" name="childrenresidence-relationship['.$_POST['child-firstname'][$w].']['.$i.']" value="Both" '.((isset($_forms['children_residence']) && isset($_forms['children_residence']['childrenresidence-relationship']) && $_forms['children_residence']['childrenresidence-relationship'][$_POST['child-firstname'][$w]][$i]=='Both') ? 'checked="checked"' : '').'> <label>Both</label></td>
				                                    <td width="20%"><input type="hidden" name="childresidence'.$w.'_cont[]" value=""></td>
				                                </tr>
				                               
				                               <tr><td colspan="5"><hr style="color: #eaeaea;"></td></tr>
				                              
				                            </table>';


				                            }
				                        	}
				                    		}                            

				                        $_residence .='</div>

				                        <div class="buttons" style="padding-top: 20px;">
				                            <button id="btRemoveChildrenResidence'.$w.'" type="button">Remove Last Residence</button>
				                            <button id="btAddChildrenResidence'.$w.'" type="button">Add Another Residence</button>
				                        </div>


				                        <table cellpadding="0" cellspacing="0" width="100%">
				                            <tr><td colspan="4"></td></tr>   
				                            <tr><td colspan="4"><hr></td></tr>
				                            <tr><td colspan="4"></td></tr>                           
				                        </table>';


				                       }

				                        



				                        }else{


				                       		$_residence .='<table cellpadding="0" cellspacing="0" width="100%">
					                           <tr>
					                               <td colspan="3">You must complete the children from partnership module to enable this one</td>
					                           </tr>
					                       </table>';

					                        }

				                        if(is_array($_POST) && $_POST['child-firstname'][0]!=''){
				                        
					                        $_residence .='<table cellpadding="0" cellspacing="0" width="100%">
					                            <!-- <tr><td colspan="4"><hr></td></tr>
					                            <tr><td colspan="4"></td></tr> -->

					                           <tr>
					                                <td colspan="4" style="margin: auto;">
					                                    <div class="buttons" style="text-align:center;">
					                                        <button class="continue" id="btChildrenResidence">Continue</button>
					                                    </div>
					                                    <div class="childrenresidence_success"></div>
					                                    <div class="childrenresidence_error"></div>
					                                </td>
					                            </tr>
					                        </table>';

				                        }

				                       $_residence .='</form>';



				                       $_residence_js ='<script>';
				                        $_residence_js .='$(document).ready(function() {';

				                            if(is_array($_POST) && $_POST['child-firstname'][0]!=''){
				                            for ($w=0; $w<count($_POST['child-firstname']); $w++){

				                            $_var_let = ((isset($_forms['children_residence']) && $_forms['children_residence'] !='' && isset($_forms['children_residence']['childrenresidence-address'][$_POST['child-firstname'][$w]])) ? count($_forms['children_residence']['childrenresidence-address'][$_POST['child-firstname'][$w]])+1 : 0);

				                           $_residence_js .='let xbgh_'.$w.' = '.$_var_let.';
				                            $(\'#btAddChildrenResidence'.$w.'\').on(\'click\', function(){
				                                xbgh_'.$w.'++;				                                
				                                let clone = $(\'#childrenresidence0\').clone();        

				                                clone.find(\'tr input[name="childrenresidence-address['.$_POST['child-firstname'][$w].'][]"]\').attr(\'name\', \'childrenresidence-address['.$_POST['child-firstname'][$w].'][\'+xbgh_'.$w.'+\']\');
				                                clone.find(\'tr input[name="childrenresidence-time['.$_POST['child-firstname'][$w].'][]"]\').attr(\'name\', \'childrenresidence-time['.$_POST['child-firstname'][$w].'][\'+xbgh_'.$w.'+\']\');
				                                clone.find(\'tr input[name="childrenresidence-people['.$_POST['child-firstname'][$w].'][]"]\').attr(\'name\', \'childrenresidence-people['.$_POST['child-firstname'][$w].'][\'+xbgh_'.$w.'+\']\');                               

				                                clone.find(\'tr input[type="radio"]\').attr(\'name\', \'childrenresidence-relationship['.$_POST['child-firstname'][$w].'][\'+xbgh_'.$w.'+\']\');

				                                clone.find(\'tr input[type="text"], tr input[type="number"]\').val(\'\');
				                                clone.find(\'tr input[type="radio"]\').prop(\'checked\', false);
				                                $(\'#duplicate-childrenresidence'.$w.'\').append(clone.attr(\'id\',\'childrenresidence'.$w.'_\'+xbgh_'.$w.'));
				                            });
				                            $(\'#btRemoveChildrenResidence'.$w.'\').on(\'click\', function(){
				                                if(xbgh_'.$w.' > 0){
				                                    $(\'#childrenresidence'.$w.'_\'+xbgh_'.$w.').remove()
				                                    xbgh_'.$w.'--;
				                                }
				                            });';

				                            }}

				                            if(is_array($_POST) && $_POST['child-firstname'][0]!=''){

				                            	                               
				                                

				                                $_residence_js .='$("#btChildrenResidence").click(function(){';  

				                                    for ($w=0; $w<count($_POST['child-firstname']); $w++){

				                                    $_residence_js .='var inps_'.$w.' = document.getElementsByName(\'childresidence'.$w.'_cont[]\');

				                                    for (var i = 0; i <inps_'.$w.'.length; i++) {

				                                        if(i==0){
				                                            i=\'\';
				                                        }

				                                        if($(\'input[name="childrenresidence-address['.$_POST['child-firstname'][$w].'][\'+i+\']"]\').val()==\'\'){
				                                            $(\'.childrenresidence_error\').html("You must complete <strong>Child '.$_POST['child-firstname'][$w].': Where has this child been residing for the last five years?</strong> field").fadeIn(\'slow\').css(\'color\', \'#f00\');
				                                            return false;
				                                        }

				                                        if($(\'input[name="childrenresidence-time['.$_POST['child-firstname'][$w].'][\'+i+\']"]\').val()==\'\'){
				                                            $(\'.childrenresidence_error\').html("You must complete <strong>Child '.$_POST['child-firstname'][$w].': Total time living in this address</strong> field").fadeIn(\'slow\').css(\'color\', \'#f00\');
				                                            return false;
				                                        }

				                                        if($(\'input[name="childrenresidence-people['.$_POST['child-firstname'][$w].'][\'+i+\']"]\').val()==\'\'){
				                                            $(\'.childrenresidence_error\').html("You must complete <strong>Child '.$_POST['child-firstname'][$w].': Total people residing in this residence</strong> field").fadeIn(\'slow\').css(\'color\', \'#f00\');
				                                            return false;
				                                        }

				                                                    

				                                        if ($(\'input[name="childrenresidence-relationship['.$_POST['child-firstname'][$w].'][\'+i+\']"]:checked\').length == 0) {
				                                            $(\'.childrenresidence_error\').html("You must complete <strong>Child '.$_POST['child-firstname'][$w].': Relationship</strong> field").fadeIn(\'slow\').css(\'color\', \'#f00\');
				                                            return false;
				                                        } 

				                                        if(i==\'\'){
				                                            i=0;
				                                        }

				                                    }';

				                                     
				                                    }

				                                    $_residence_js .='$(\'.childrenresidence_error\').html(\'\').fadeOut(\'fast\');

				                               
				                                    $.ajax({
				                                        type: \'POST\',
				                                        url: _root_+"user/consolidarForm",
				                                        data: $(\'#children_residence\').serialize(),
				                                        beforeSend: function(){
				                                            
				                                        },     
				                                        success: function(data){

				                                            if(data == \'ok\'){
				                                                $(\'#navform li[data-section="Parenting-Plan"] .subsection div.sb\').removeClass(\'active\');
				                                                $(\'.containerForm section#Parenting-Plan .contForm\').hide();
				                                                $(\'.containerForm section#Parenting-Plan > div:First\').show();
				                                                $(\'#navform li[data-section="Parenting-Plan"] .subsection div[data-section="custody"].sb\').addClass(\'active\');
				                                                $(\'#navform li[data-section="Children"] .subsection div[data-section="childrenresidence"] span\').removeClass(\'incomplete\');
				                                                $(\'#navform li[data-section="Children"] .subsection div[data-section="childrenresidence"] span\').addClass(\'complete\');
				                                                $(\'#navform li[data-section="Children"]\').removeClass(\'active\');
				                                                $(\'#navform li[data-section="Parenting-Plan"]\').addClass(\'active\');
				                                                $(\'#Children\').hide();
				                                                $(\'#Parenting-Plan\').show();                    


				                                                goUp();

				                                            }else if(data == \'final\'){

				                                                $(\'#navform li[data-section="Children"] .subsection div[data-section="childrenresidence"] span\').removeClass(\'incomplete\');
				                                                $(\'#navform li[data-section="Children"] .subsection div[data-section="childrenresidence"] span\').addClass(\'complete\');
				                                                $(\'#navform li[data-section="Children"]\').removeClass(\'active\');
				                                                $(\'#Children\').hide();
				                                                $(\'#complete-form\').show();
				                                                goUp();
				                                            
				                                            }else{
				                                                $(\'.childrenresidence_error\').html(data).fadeIn(\'slow\');
				                                            }
				                                          
				                                        }
				                                    });

				                                    return false;    
				                                });                               
				                                
				                                
				                            });';

				                            }
				                        	$_residence_js .='</script>';

					}
				}

				$jsondata['residence'] = $_residence;
				$jsondata['residence_js'] = $_residence_js;

				$_datos = contenidos_forms_respuesta::find(array('conditions' => array('id = ?', $this->_view->datos['_id_form'])));	
				if($_datos){
					$_datos->$c = $_data;						
					$_datos->save();
				}

				$_check =  $this->homeGestion->checkFinalForm($_datos->id_producto, $this->_view->datos['_id_form']);
				if(!empty($_check)){
					$jsondata['estado'] = "ok";
				}else{
					
					// envio aviso mail
					$_body = '<!DOCTYPE html>
								<html lang="en">
								<head>
								    <meta charset="UTF-8">
								    <meta http-equiv="X-UA-Compatible" content="IE=edge">
								    <meta name="viewport" content="width=device-width, initial-scale=1.0">
								    <link rel="preconnect" href="https://fonts.gstatic.com">
								    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
								    <link href="https://fonts.googleapis.com/css2?family=Karla:wght@300&display=swap" rel="stylesheet">
								    <title>The Quick Divorce</title>
								</head>

								<body style="margin: auto;width: 50%;padding:0;box-sizing: border-box;min-width: 600px;font-family: \'Karla\';color: #000;">

								   <header class="header" style="height: 100px;background-color: #162536;">
								       <img src="'.$this->_conf['base_url'].'views/layout/default/img/TQD_logo.png" alt="" style="width: 340px;margin: auto;display: block;padding-top: 10px;">
								   </header> 

								   <div class="container" style="background-color: #f2f0e2; padding: 0 50px;">

								        <div class="intro-text" >
								            <br><br>
								            <h3 class="text-big" style="margin: 0;padding-top:10px; text-align: center;">A new form has been completed!</h3>
								            <br><br>
								            <p class="text-small">
								                CLIENT NAME: <strong>'.home::convertirCaracteres(strtoupper($this->_sess->get('usuario_front'))).'</strong>
								                <br>
								                PACKAGE: <strong>'.strtoupper($this->_view->datos['_item']).'</strong>
								            </p>            

								            <a href="'.$this->_conf['base_url'].'administrador/forms" style="display: block;width: 190px;padding: 5px;border-radius: 4px;margin: 25px auto 0;box-shadow: 3px 5px 10px rgba(0,0,0,0.2);outline: 0;border: 0;background: #c5d7de;cursor: pointer;">
								            	<p style="height: 100%; width: 100%; display: block; border: solid 1px #fff; color: #1d2731; font-family:\'gothambold\', sans-serif; font-weight: bold; font-size: 13px; text-align: center; text-transform: uppercase; padding: 10px 0; margin: 0;">view details</p>
								            </a>
								          
								        </div>

								        <br><br>
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
		            $this->envioMail->Subject = 'New Completed '.strtoupper($this->_view->datos['_item']).': '.home::convertirCaracteres(strtoupper($this->_sess->get('usuario_front')));               
		            $this->envioMail->Body = $_body;
		            $this->envioMail->AddAddress('info@thequickdivorce.com');
		            // $this->envioMail->AddAddress('notifications@thequickdivorce.com');
		            $this->envioMail->addCC('aliette@carolanfamilylaw.com');
		            $this->envioMail->IsHTML(true); 
		            
		            $exito = $this->envioMail->Send();
		            
		            $intentos=1;
		            
		            while ((!$exito) && ($intentos < 3)) {
		                // sleep(5);           
		                $exito = $this->envioMail->Send();              
		                $intentos=$intentos+1;          
		            }
		            
		            if(!$exito) {           
		                echo $this->envioMail->ErrorInfo;
		                exit;               
		            }*/




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
					    'Subject' => 'New Completed '.strtoupper($this->_view->datos['_item']).': '.home::convertirCaracteres(strtoupper($this->_sess->get('usuario_front'))),
					    'HTMLPart' => $_body,
					  ]
					]
					];
					$response = $mj->post(Resources::$Email, ['body' => $body]);
					$response->success();

					$_resp = $response->getData();

					// echo $_resp['Messages'][0]['Status'];

					if($_resp['Messages'][0]['Status']=='success'){
						$jsondata['estado'] = "final";
					}else{
						echo "Error";
		                exit; 
					}


		            // $jsondata['estado'] = "final";


				}				
							
				echo json_encode($jsondata);
			}
		}
	}


	public function calcular()
	{
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){

				// echo "<pre>";print_r($_POST);exit;

				$_id = (int) $_POST['_id'];
				$_data = $this->homeGestion->calculateChildSupport($_id);

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
		

				echo json_encode($jsondata);

			}
		}
	}


	public function calcular2()
	{
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){

				// echo "<pre>";print_r($_POST);exit;

				$_id = (int) $_POST['_id'];
				$_data = $this->homeGestion->calculateChildSupport($_id);

				// echo "<pre>";print_r($_data);exit;

				if($_data){

					$_html = '<tr>
                                <td width="70%">Monthly Net Income</td>
                                <td width="15%" class="_mother_income">$'.number_format($_data['mother_income'], 2, '.', ',').'</td>
                                <td width="15%" class="_father_income">$'.number_format($_data['father_income'], 2, '.', ',').'</td>
                            </tr>
                            <tr>
                                <td width="70%">Monthly Child Support Obligation</td>
                                <td width="15%" class="_mother_child_support_oblig">$'.number_format($_data['mother_child_support_oblig'], 2, '.', ',').'</td>
                                <td width="15%" class="_father_child_support_oblig">$'.number_format($_data['father_child_support_oblig'], 2, '.', ',').'</td>
                            </tr>
                            <tr>
                                <td width="70%">Monthly Child Support Credits</td>
                                <td width="15%" class="_mother_child_support_credits">$'.number_format($_data['mother_child_support_credits'], 2, '.', ',').'</td>
                                <td width="15%" class="_father_child_support_credits">$'.number_format($_data['father_child_support_credits'], 2, '.', ',').'</td>
                            </tr>
                            <tr>
                                <td width="70%"><strong>Monthly Payment (Standard Calculation)</strong></td>
                                <td width="15%" class="_mother_payment"><strong>$'.number_format($_data['mother_payment'], 2, '.', ',').'</strong></td>
                                <td width="15%" class="_father_payment"><strong>$'.number_format($_data['father_payment'], 2, '.', ',').'</strong></td>
                            </tr>';

					// $jsondata['mother_income'] = number_format($_data['mother_income'], 2, '.', ',');
					// $jsondata['father_income'] = number_format($_data['father_income'], 2, '.', ',');
					// $jsondata['mother_child_support_oblig'] = number_format($_data['mother_child_support_oblig'], 2, '.', ',');
					// $jsondata['father_child_support_oblig'] = number_format($_data['father_child_support_oblig'], 2, '.', ',');
					// $jsondata['mother_child_support_credits'] = number_format($_data['mother_child_support_credits'], 2, '.', ',');
					// $jsondata['father_child_support_credits'] = number_format($_data['father_child_support_credits'], 2, '.', ',');				
					// $jsondata['mother_payment'] = number_format($_data['mother_payment'], 2, '.', ',');
					// $jsondata['father_payment'] = number_format($_data['father_payment'], 2, '.', ',');
				}
		

				echo $_html;

			}
		}
	}


	public function test()
	{

		// home::calculateChildSupport();
		// exit;

		$_form =  home::traerFormsPorIdStatic(7);

		if($_form['wife_monthly_income']!='' || $_form['husband_monthly_income']!=''){

			if($_form['wife_monthly_income']!=''){
				$_form['wife_monthly_income'] = unserialize(base64_decode($_form['wife_monthly_income']));
				$_mo_icome = array_chunk($_form['wife_monthly_income'], 10, true);
				unset($_mo_icome[1]['WifeFinancial-StateIndustrialInsurance']);
				$_mo_icome_sum = array_sum($_mo_icome[0]);
				$_mo_icome_res = array_sum($_mo_icome[1]);
				$_mother = $_mo_icome_sum - $_mo_icome_res;
			}else{
				$_mother = 0;
			}

			if($_form['husband_monthly_income']!=''){
				$_form['husband_monthly_income'] = unserialize(base64_decode($_form['husband_monthly_income']));
				$_fa_icome = array_chunk($_form['husband_monthly_income'], 10, true);
				unset($_fa_icome[1]['HusbandFinancial-StateIndustrialInsurance']);
				$_fa_icome_sum = array_sum($_fa_icome[0]);
				$_fa_icome_res = array_sum($_fa_icome[1]);
				$_father = $_fa_icome_sum - $_fa_icome_res;
			}else{
				$_father = 0;
			}


		}else{
			$_father = 0;
			$_mother = 0;
		}


		if($_form['children_from_the_marriage']!=''){

			$_form['children_from_the_marriage'] = unserialize(base64_decode($_form['children_from_the_marriage']));			
			$_children = count($_form['children_from_the_marriage']['child-firstname']);
		}else{
			$_children = 0;
		}



		if($_form['parenting_income_tax_exemptions']!=''){

			$_form['parenting_income_tax_exemptions'] = unserialize(base64_decode($_form['parenting_income_tax_exemptions']));			
			$_mother_child_spend = $_form['parenting_income_tax_exemptions']['custody-IncomeTaxes-percentMother'];
			$_father_child_spend = $_form['parenting_income_tax_exemptions']['custody-IncomeTaxes-percentFather'];
		}else{
			$_mother_child_spend = 0;
			$_father_child_spend = 0;
		}


		if($_form['wife_child_care_expenses']!=''){

			$_form['wife_child_care_expenses'] = unserialize(base64_decode($_form['wife_child_care_expenses']));			
			$_mo_childcare = array_chunk($_form['wife_child_care_expenses'], 4, true);
			$_mother_child_care = array_sum($_mo_childcare[0]);
		}else{
			$_mother_child_care = 0;
		}

		if($_form['husband_child_care_expenses']!=''){

			$_form['husband_child_care_expenses'] = unserialize(base64_decode($_form['husband_child_care_expenses']));			
			$_fa_childcare = array_chunk($_form['husband_child_care_expenses'], 4, true);
			$_father_child_care = array_sum($_fa_childcare[0]);
		}else{
			$_father_child_care = 0;
		}
		

		/*$_arr_mother=array();
		$_arr_father=array();

		if($_form['child_care']!=''){

			$_form['child_care'] = unserialize(base64_decode($_form['child_care']));			
			$_child_care = array_chunk($_form['child_care'], 8, true);

			if($_child_care[0]['ChildCare-ChildHealthInsurance-Parent'] == 'Mother'){
				$_arr_mother[] = $_child_care[0]['ChildCare-ChildHealthInsurance'];
			}else{
				$_arr_father[] = $_child_care[0]['ChildCare-ChildHealthInsurance'];
			}

			if($_child_care[0]['ChildCare-ExtraordinaryChildHealthCare-Parent'] == 'Mother'){
				$_arr_mother[] = $_child_care[0]['ChildCare-ExtraordinaryChildHealthCare'];
			}else{
				$_arr_father[] = $_child_care[0]['ChildCare-ExtraordinaryChildHealthCare'];
			}

			if($_child_care[0]['ChildCare-Daycare-Parent'] == 'Mother'){
				$_arr_mother[] = $_child_care[0]['ChildCare-Daycare'];
			}else{
				$_arr_father[] = $_child_care[0]['ChildCare-Daycare'];
			}

			if($_child_care[0]['ChildCare-Education-Parent'] == 'Mother'){
				$_arr_mother[] = $_child_care[0]['ChildCare-Education'];
			}else{
				$_arr_father[] = $_child_care[0]['ChildCare-Education'];
			}

			$_mother_child_care = array_sum($_arr_mother);
			$_father_child_care = array_sum($_arr_father);
		}else{
			$_mother_child_care = 0;
			$_father_child_care = 0;
		}*/



	

		// echo "<pre>";print_r($_father_child_care);exit;


		///////////////////////////

		$_father = 2000;
		$_mother = 2000;
		$_children = 2;
		$_mother_child_spend = 50;
		$_father_child_spend = 50;
		$_mother_child_care = 200;
		$_father_child_care = 200;


		if($_father == 0 && $_mother == 0){
			$_income_total = 0;
			$_mo_percent_financial = 0;
			$_fa_percent_financial = 0;
		}else{
			$_income_total = $_father + $_mother;
			$_mo_percent_financial = $_mother / $_income_total;
			$_fa_percent_financial = $_father / $_income_total;
		}	
		// $_mother_day_spend = (365 * $_mother_child_spend) /100;
		// $_father_day_spend = (365 * $_father_child_spend) /100;		
		$_parent_child_care = $_mother_child_care + $_father_child_care;

		// echo $_fa_percent_financial;exit;


		if($_income_total > 10000){

			$_parent_income =  $this->homeGestion->traerIncomeForChild(10000);

		}else{

			$_parent_income =  $this->homeGestion->traerIncomeForChild($_income_total);				

		}	


		if($_income_total < 800){

			$_parent_income =  $this->homeGestion->traerIncomeForChild(800);

		}else{

			$_parent_income =  $this->homeGestion->traerIncomeForChild($_income_total);				

		}	


		if($_parent_income){

			switch ($_children) {
			  case 1:
			    $_parent_children_income = $_parent_income['one'];
			    break;
			  case 2:
			    $_parent_children_income = $_parent_income['two'];
			    break;
			  case 3:
			    $_parent_children_income = $_parent_income['three'];
			    break;
			  case 4:
			    $_parent_children_income = $_parent_income['four'];
			    break;
			  case 5:
			    $_parent_children_income = $_parent_income['five'];
			    break;
			  case 6:
			    $_parent_children_income = $_parent_income['six'];
			    break;
			  default:
			    $_parent_children_income = $_parent_income['one'];
			}

		}

		// echo $_parent_children_income;exit;

		$_parent_dat = $_parent_children_income * 1.5;
		// $_fa_dat = $_father_children_income * 1.5;

		// echo $_parent_dat;exit;
		

		$_mo_basic_oblig = $_parent_dat * $_mo_percent_financial;
		$_fa_basic_oblig = $_parent_dat * $_fa_percent_financial;

		// echo $_fa_basic_oblig;exit;
		

		$_mo_monthly_child_obligation = ($_mo_basic_oblig * $_father_child_spend) / 100;
		$_fa_monthly_child_obligation = ($_fa_basic_oblig * $_mother_child_spend) / 100;
		$_child_care_mo = false;
		$_child_care_fa = false;

			$_mo_parcial = $_parent_child_care * $_mo_percent_financial;
			$_mo_parcial_2 = $_mo_parcial - 0;
			$_mo_monthly_child_obligation_2 = $_mo_monthly_child_obligation + $_mo_parcial_2;
			$_mo_parcial = $_mo_parcial - $_mother_child_care;
			$_mo_parcial = ($_mo_parcial>0) ? $_mo_parcial : 0;			
			$_mo_monthly_child_obligation = $_mo_monthly_child_obligation + $_mo_parcial;
			$_child_care_mo = true;

			$_fa_parcial = $_parent_child_care * $_fa_percent_financial;
			$_fa_parcial_2 = $_fa_parcial - 0;
			$_fa_monthly_child_obligation_2 = $_fa_monthly_child_obligation + $_fa_parcial_2;
			$_fa_parcial = $_fa_parcial - $_father_child_care;
			$_fa_parcial = ($_fa_parcial>0) ? $_fa_parcial : 0;
			$_fa_monthly_child_obligation = $_fa_monthly_child_obligation + $_fa_parcial;
			$_child_care_fa = true;

		/*if($_child_care_mo==false){
			$_mo_monthly_child_obligation_2 = $_mo_monthly_child_obligation;
		}

		if($_child_care_fa==false){
			$_fa_monthly_child_obligation_2 = $_fa_monthly_child_obligation;
		}*/


		if($_mo_monthly_child_obligation > $_fa_monthly_child_obligation){
			$_monthly_payment_mo = $_mo_monthly_child_obligation - $_fa_monthly_child_obligation;
			$_monthly_payment_fa = 0;
		}else if ($_fa_monthly_child_obligation > $_mo_monthly_child_obligation){
			$_monthly_payment_fa = $_fa_monthly_child_obligation - $_mo_monthly_child_obligation;
			$_monthly_payment_mo = 0;
		}else{
			$_monthly_payment_mo = $_fa_monthly_child_obligation - $_mo_monthly_child_obligation;	
			$_monthly_payment_fa = $_fa_monthly_child_obligation - $_mo_monthly_child_obligation;			
		}

		echo "mother: ".round($_mo_monthly_child_obligation, 2);
		echo "<br>";
		echo "father: ".round($_fa_monthly_child_obligation, 2);
		echo "<br>";
		echo "<br>";
		echo "mother: ".round($_mo_monthly_child_obligation_2, 2);
		echo "<br>";
		echo "father: ".round($_fa_monthly_child_obligation_2, 2);
		echo "<br>";
		echo "<br>";
		echo  "Monthly Payment father: ".round($_monthly_payment_fa, 2);
		echo "<br>";
		echo  "Monthly Payment mother: ".round($_monthly_payment_mo, 2);
		exit;



	}


    /*public function consolidarForm1()
    {
    	if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
				
				// echo "<pre>";print_r($_POST);exit;	
					
				$this->_view->datos = $_POST;

				unset($_POST['_csrf'],$_POST['_id_form'],$_POST['_item'],$_POST['_form_part']);

				$_data = base64_encode(serialize($this->_xss->xss_clean($_POST)));

				  // echo "_information<pre>";print_r($_information);//exit;
				  // echo "_residency<pre>";print_r($_residency);//exit;

				$_datos = contenidos_forms_respuesta::find(array('conditions' => array('id = ?', $this->_view->datos['_id_form'])));	
				if($_datos){
					$_datos->divorce_information = $_data;						
					$_datos->save();


				}

				$_check =  $this->homeGestion->checkFinalForm($_datos->id_producto, $this->_view->datos['_id_form']);
				if(!empty($_check)){
					echo "ok";
				}else{
					echo "final";
				}				
							
				// echo json_encode($jsondata);
			}
		}
	}

	public function consolidarForm2()
    {
    	if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
				
				// echo "<pre>";print_r($_POST);exit;	
					
				$this->_view->datos = $_POST;

				unset($_POST['_csrf'],$_POST['_id_form'],$_POST['_item'],$_POST['_form_part']);

				$_data = base64_encode(serialize($this->_xss->xss_clean($_POST)));

				  // echo "_information<pre>";print_r($_information);//exit;
				  // echo "_residency<pre>";print_r($_residency);//exit;

				$_datos = contenidos_forms_respuesta::find(array('conditions' => array('id = ?', $this->_view->datos['_id_form'])));	
				if($_datos){
					$_datos->divorce_residency = $_data;						
					$_datos->save();


				}

				$_check =  $this->homeGestion->checkFinalForm($_datos->id_producto, $this->_view->datos['_id_form']);
				if(!empty($_check)){
					echo "ok";
				}else{
					echo "final";
				}			
							
				// echo json_encode($jsondata);
			}
		}
	}


	public function consolidarForm3()
    {
    	if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
				
				// echo "<pre>";print_r($_POST);exit;	
					
				$this->_view->datos = $_POST;

				unset($_POST['_csrf'],$_POST['_id_form'],$_POST['_item'],$_POST['_form_part']);

				$_data = base64_encode(serialize($this->_xss->xss_clean($_POST)));

				  // echo "_information<pre>";print_r($_information);//exit;
				  // echo "_residency<pre>";print_r($_residency);//exit;

				$_datos = contenidos_forms_respuesta::find(array('conditions' => array('id = ?', $this->_view->datos['_id_form'])));	
				if($_datos){
					$_datos->wife_information = $_data;						
					$_datos->save();


				}

				$_check =  $this->homeGestion->checkFinalForm($_datos->id_producto, $this->_view->datos['_id_form']);
				if(!empty($_check)){
					echo "ok";
				}else{
					echo "final";
				}			
							
				// echo json_encode($jsondata);
			}
		}
	}


	public function consolidarForm4()
    {
    	if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
				
				// echo "<pre>";print_r($_POST);exit;	
					
				$this->_view->datos = $_POST;

				unset($_POST['_csrf'],$_POST['_id_form'],$_POST['_item'],$_POST['_form_part']);

				$_data = base64_encode(serialize($this->_xss->xss_clean($_POST)));

				  // echo "_information<pre>";print_r($_information);//exit;
				  // echo "_residency<pre>";print_r($_residency);//exit;

				$_datos = contenidos_forms_respuesta::find(array('conditions' => array('id = ?', $this->_view->datos['_id_form'])));	
				if($_datos){
					$_datos->wife_identification = $_data;						
					$_datos->save();


				}

				$_check =  $this->homeGestion->checkFinalForm($_datos->id_producto, $this->_view->datos['_id_form']);
				if(!empty($_check)){
					echo "ok";
				}else{
					echo "final";
				}			
							
				// echo json_encode($jsondata);
			}
		}
	}

	public function consolidarForm5()
    {
    	if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
				
				// echo "<pre>";print_r($_POST);exit;	
					
				$this->_view->datos = $_POST;

				unset($_POST['_csrf'],$_POST['_id_form'],$_POST['_item'],$_POST['_form_part']);

				$_data = base64_encode(serialize($this->_xss->xss_clean($_POST)));

				  // echo "_information<pre>";print_r($_information);//exit;
				  // echo "_residency<pre>";print_r($_residency);//exit;

				$_datos = contenidos_forms_respuesta::find(array('conditions' => array('id = ?', $this->_view->datos['_id_form'])));	
				if($_datos){
					$_datos->wife_employer_information = $_data;						
					$_datos->save();


				}

				$_check =  $this->homeGestion->checkFinalForm($_datos->id_producto, $this->_view->datos['_id_form']);
				if(!empty($_check)){
					echo "ok";
				}else{
					echo "final";
				}			
							
				// echo json_encode($jsondata);
			}
		}
	}

	public function consolidarForm6()
    {
    	if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
				
				// echo "<pre>";print_r($_POST);exit;	
					
				$this->_view->datos = $_POST;

				unset($_POST['_csrf'],$_POST['_id_form'],$_POST['_item'],$_POST['_form_part']);

				$_data = base64_encode(serialize($this->_xss->xss_clean($_POST)));

				  // echo "_information<pre>";print_r($_information);//exit;
				  // echo "_residency<pre>";print_r($_residency);//exit;

				$_datos = contenidos_forms_respuesta::find(array('conditions' => array('id = ?', $this->_view->datos['_id_form'])));	
				if($_datos){
					$_datos->wife_monthly_income = $_data;						
					$_datos->save();


				}

				$_check =  $this->homeGestion->checkFinalForm($_datos->id_producto, $this->_view->datos['_id_form']);
				if(!empty($_check)){
					echo "ok";
				}else{
					echo "final";
				}			
							
				// echo json_encode($jsondata);
			}
		}
	}


	public function consolidarForm7()
    {
    	if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
				
				// echo "<pre>";print_r($_POST);exit;	
					
				$this->_view->datos = $_POST;

				unset($_POST['_csrf'],$_POST['_id_form'],$_POST['_item'],$_POST['_form_part']);

				$_data = base64_encode(serialize($this->_xss->xss_clean($_POST)));

				  // echo "_information<pre>";print_r($_information);//exit;
				  // echo "_residency<pre>";print_r($_residency);//exit;

				$_datos = contenidos_forms_respuesta::find(array('conditions' => array('id = ?', $this->_view->datos['_id_form'])));	
				if($_datos){
					$_datos->husband_personal_information = $_data;						
					$_datos->save();


				}

				$_check =  $this->homeGestion->checkFinalForm($_datos->id_producto, $this->_view->datos['_id_form']);
				if(!empty($_check)){
					echo "ok";
				}else{
					echo "final";
				}			
							
				// echo json_encode($jsondata);
			}
		}
	}


	public function consolidarForm8()
    {
    	if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
				
				// echo "<pre>";print_r($_POST);exit;	
					
				$this->_view->datos = $_POST;

				unset($_POST['_csrf'],$_POST['_id_form'],$_POST['_item'],$_POST['_form_part']);

				$_data = base64_encode(serialize($this->_xss->xss_clean($_POST)));

				  // echo "_information<pre>";print_r($_information);//exit;
				  // echo "_residency<pre>";print_r($_residency);//exit;

				$_datos = contenidos_forms_respuesta::find(array('conditions' => array('id = ?', $this->_view->datos['_id_form'])));	
				if($_datos){
					$_datos->husband_identification = $_data;						
					$_datos->save();


				}

				$_check =  $this->homeGestion->checkFinalForm($_datos->id_producto, $this->_view->datos['_id_form']);
				if(!empty($_check)){
					echo "ok";
				}else{
					echo "final";
				}			
							
				// echo json_encode($jsondata);
			}
		}
	}

	public function consolidarForm9()
    {
    	if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
				
				// echo "<pre>";print_r($_POST);exit;	
					
				$this->_view->datos = $_POST;

				unset($_POST['_csrf'],$_POST['_id_form'],$_POST['_item'],$_POST['_form_part']);

				$_data = base64_encode(serialize($this->_xss->xss_clean($_POST)));

				  // echo "_information<pre>";print_r($_information);//exit;
				  // echo "_residency<pre>";print_r($_residency);//exit;

				$_datos = contenidos_forms_respuesta::find(array('conditions' => array('id = ?', $this->_view->datos['_id_form'])));	
				if($_datos){
					$_datos->husband_employer_information = $_data;						
					$_datos->save();


				}

				$_check =  $this->homeGestion->checkFinalForm($_datos->id_producto, $this->_view->datos['_id_form']);
				if(!empty($_check)){
					echo "ok";
				}else{
					echo "final";
				}			
							
				// echo json_encode($jsondata);
			}
		}
	}

	public function consolidarForm10()
    {
    	if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
				
				// echo "<pre>";print_r($_POST);exit;	
					
				$this->_view->datos = $_POST;

				unset($_POST['_csrf'],$_POST['_id_form'],$_POST['_item'],$_POST['_form_part']);

				$_data = base64_encode(serialize($this->_xss->xss_clean($_POST)));

				  // echo "_information<pre>";print_r($_information);//exit;
				  // echo "_residency<pre>";print_r($_residency);//exit;

				$_datos = contenidos_forms_respuesta::find(array('conditions' => array('id = ?', $this->_view->datos['_id_form'])));	
				if($_datos){
					$_datos->husband_monthly_income = $_data;						
					$_datos->save();


				}

				$_check =  $this->homeGestion->checkFinalForm($_datos->id_producto, $this->_view->datos['_id_form']);
				if(!empty($_check)){
					echo "ok";
				}else{
					echo "final";
				}			
							
				// echo json_encode($jsondata);
			}
		}
	}

	public function consolidarForm11()
    {
    	if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
				
				echo "<pre>";print_r($_POST);exit;	
					
				$this->_view->datos = $_POST;

				unset($_POST['_csrf'],$_POST['_id_form'],$_POST['_item'],$_POST['_form_part']);

				$_data = base64_encode(serialize($this->_xss->xss_clean($_POST)));

				  // echo "_information<pre>";print_r($_information);//exit;
				  // echo "_residency<pre>";print_r($_residency);//exit;

				$_datos = contenidos_forms_respuesta::find(array('conditions' => array('id = ?', $this->_view->datos['_id_form'])));	
				if($_datos){
					$_datos->property_division = $_data;						
					$_datos->save();


				}

				$_check =  $this->homeGestion->checkFinalForm($_datos->id_producto, $this->_view->datos['_id_form']);
				if(!empty($_check)){
					echo "ok";
				}else{
					echo "final";
				}			
							
				// echo json_encode($jsondata);
			}
		}
	}

	public function consolidarForm12()
    {
    	if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
				
				// echo "<pre>";print_r($_POST);exit;	
					
				$this->_view->datos = $_POST;

				unset($_POST['_csrf'],$_POST['_id_form'],$_POST['_item'],$_POST['_form_part']);

				$_data = base64_encode(serialize($this->_xss->xss_clean($_POST)));

				  // echo "_information<pre>";print_r($_information);//exit;
				  // echo "_residency<pre>";print_r($_residency);//exit;

				$_datos = contenidos_forms_respuesta::find(array('conditions' => array('id = ?', $this->_view->datos['_id_form'])));	
				if($_datos){
					$_datos->debt = $_data;						
					$_datos->save();


				}

				$_check =  $this->homeGestion->checkFinalForm($_datos->id_producto, $this->_view->datos['_id_form']);
				if(!empty($_check)){
					echo "ok";
				}else{
					echo "final";
				}			
							
				// echo json_encode($jsondata);
			}
		}
	}

	public function consolidarForm13()
    {
    	if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
				
				// echo "<pre>";print_r($_POST);exit;	
					
				$this->_view->datos = $_POST;

				unset($_POST['_csrf'],$_POST['_id_form'],$_POST['_item'],$_POST['_form_part']);

				$_data = base64_encode(serialize($this->_xss->xss_clean($_POST)));

				  // echo "_information<pre>";print_r($_information);//exit;
				  // echo "_residency<pre>";print_r($_residency);//exit;

				$_datos = contenidos_forms_respuesta::find(array('conditions' => array('id = ?', $this->_view->datos['_id_form'])));	
				if($_datos){
					$_datos->spousal_support = $_data;						
					$_datos->save();


				}

				$_check =  $this->homeGestion->checkFinalForm($_datos->id_producto, $this->_view->datos['_id_form']);
				if(!empty($_check)){
					echo "ok";
				}else{
					echo "final";
				}			
							
				// echo json_encode($jsondata);
			}
		}
	}

	public function consolidarForm14()
    {
    	if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
				
				// echo "<pre>";print_r($_POST);exit;	
					
				$this->_view->datos = $_POST;

				unset($_POST['_csrf'],$_POST['_id_form'],$_POST['_item'],$_POST['_form_part']);

				$_data = base64_encode(serialize($this->_xss->xss_clean($_POST)));

				  // echo "_information<pre>";print_r($_information);//exit;
				  // echo "_residency<pre>";print_r($_residency);//exit;

				$_datos = contenidos_forms_respuesta::find(array('conditions' => array('id = ?', $this->_view->datos['_id_form'])));	
				if($_datos){
					$_datos->name_change = $_data;						
					$_datos->save();
				}

				$_check =  $this->homeGestion->checkFinalForm($_datos->id_producto, $this->_view->datos['_id_form']);
				if(!empty($_check)){
					echo "ok";
				}else{
					echo "final";
				}

								
							
				// echo json_encode($jsondata);
			}
		}
	}*/



	////////////////////////////////////////////////////////////////////


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
		            /*$_body='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		                    <html xmlns="http://www.w3.org/1999/xhtml">
		                    <head>
		                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		                    <title>The Quick Divorce</title>
		                    </head>                 
		                    <body>
		                    <p>Click <a href="'.$this->_conf['base_url'].'getstarted/activation/'.$_token.'">here</a> to activate your account</p>
		                    </body>
		                    </html>';*/


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
							            <h3 class="text-big" style="margin: 0;padding-top:20px">COMPLETE YOUR REGISTRATION</h3>
							            <p>Click <a href="'.$this->_conf['base_url'].'user/activation/'.$_token.'">here</a> to activate your account</p>
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
				        );;                    
		            $this->envioMail->From ='notifications@thequickdivorce.com';
		            $this->envioMail->FromName ='The Quick Divorce';
		            $this->envioMail->Subject = 'Activate your account';               
		            $this->envioMail->Body = $_body;
		            $this->envioMail->AddAddress($this->_view->datos['email']);            
		            $this->envioMail->IsHTML(true); 
		            
		            $exito = $this->envioMail->Send();
		            
		            
		            
		            if(!$exito) {           
		                echo "Problems sending email to ".$this->envioMail->ErrorInfo;
		                exit;               
		            }else{

						// $this->_sess->destroy('carga_actual');
						// $this->_sess->set('autenticado_front', true);
						// $this->_sess->set('usuario_front', $user->nombre.' '.$user->apellido);
						// $this->_sess->set('id_usuario_front', $user->id);

						echo 'ok';
						exit;
					}*/



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
					        'Email' => $this->_view->datos['email'],
					        'Name' => $this->_xss->xss_clean(validador::getTexto('nombre')).' '.$this->_xss->xss_clean(validador::getTexto('apellido'))
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

					if($_resp['Messages'][0]['Status']=='success'){
						echo 'ok';
						exit;
					}else{
						echo "Problems sending email";
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

			$this->_view->status = 'Your account is already active. <a href="'.$this->_conf['base_url'].'user">Get started</a>';
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
		$this->_view->renderizar('activacion', 'user', 'default');

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
			            }

			            echo 'ok';
						exit;*/




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


    /*public function login()
    {

    	if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
			
			
				if(validador::getInt('enviar') == 1){
					
					
					$this->_view->datos = $_POST;
					
					// if(!validador::getAlphaNum('usuario')){
					if(!validador::validarEmail($this->_view->datos['email'])){
						// $this->_view->_error = 'Debe introducir un email valido';
						
						echo 'You must enter a valid email';
						exit;
					}
					
					//if(!validador::getSql('pass',$this->_conf['baseDatos'])){
					if(!validador::getAlphaNum('pass')){					
						
						echo 'You must enter your password';
						exit;
					}
									
					
					
					// 'find' si se busca un solo registro, 'all' si se busca solo 1
					$row = contenidos_user::find(array(
											'conditions' => array(
															'email = ? AND password = ?', 
															// $this->_xss->xss_clean(validador::getAlphaNum('usuario')), 
															$this->_xss->xss_clean($this->_view->datos['email']),
															Hash::getHash('md5', $this->_xss->xss_clean(validador::getPostParam('pass')), $this->_conf['hash_key'])
															// $this->_xss->xss_clean(validador::getPostParam('pass'))
															)
												)
										);
					
					
					if(!$row){
						
						echo 'Incorrect username and/or password';
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
				//$this->redireccionar('error/access/404');
				// $this->_view->_error = 'Hubo un error, vuelva a intentarlo mas tarde.';
				echo 'There was an error, please try again later.';
				exit;
			}
		}

    }

  	
	public function recuperarPass()
    {

    	if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){
			
			
				if(validador::getInt('enviar') == 1){
					
					
					$this->_view->datos = $_POST;
					
					// if(!validador::getAlphaNum('usuario')){
					if(!validador::validarEmail($this->_view->datos['email'])){						
						echo 'You must enter a valid email';
						exit;
					}
													
					
					
					// 'find' si se busca un solo registro, 'all' si se busca solo 1
					$row = contenidos_user::find(array('conditions' => array('email = ?', $this->_xss->xss_clean($this->_view->datos['email']))));					
					
					if(!$row){						
						echo 'The user is not registered';
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
				                    <title>Quick Divorce</title>
				                    </head>                 
				                    <body>
				                    <p>Your new password is <strong>'.$_token.'</strong></p>
				                    </body>
				                    </html>';
				            
				            // $this->envioMail->IsSMTP();
				            // $this->envioMail->SMTPAuth = true;
				            // $this->envioMail->Host = "smtphub.cencosud.cl";
				            // $this->envioMail->Username = "_MailCarteleria"; 
				            // $this->envioMail->Password = "a23uj8rs"; 
				            // $this->envioMail->Port = 25;                    
				            $this->envioMail->From ='notifications@quickdivorce.com';
				            $this->envioMail->FromName ='Quick Divorce';
				            $this->envioMail->Subject = 'Recover Password';               
				            $this->envioMail->Body = $_body;
				            // $this->envioMail->AddAddress($this->_view->datos['email']);            
				            $this->envioMail->IsHTML(true); 
				            
				            $exito = $this->envioMail->Send();
				            
				            $intentos=1;
				            
				            while ((!$exito) && ($intentos < 3)) {
				                sleep(5);           
				                $exito = $this->envioMail->Send();              
				                $intentos=$intentos+1;          
				            }
				            
				            if(!$exito) {           
				                echo "Problems sending email to ".$this->envioMail->ErrorInfo;
				                exit;               
				            }

				            echo 'ok';
							exit;

						}else{
							echo 'There was an error, please try again.';
							exit;
						}	


					}
					
					
				}

			}else{				
				echo 'There was an error, please try again later.';
				exit;
			}
		}

    }*/



	/////////////////////////////////////////////////////////


    public function changeName()
	{
		
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){


				if(!$this->_sess->get('autenticado_front')){
					echo "no";
					exit;
				}else{	

					 // echo "<pre>";print_r($_POST);exit;

					$this->_view->datos = $_POST;


					if(!validador::getPostParam('first-name')){
						// echo 'Debe introducir un nombre';
						echo $this->_conf['text_lang']['user']['info_name'][$_SESSION['_lang']]['validacion1'];
						exit;
					}

					if(!validador::getPostParam('last-name')){
						echo $this->_conf['text_lang']['user']['info_name'][$_SESSION['_lang']]['validacion2'];
						// echo 'Debe introducir un apellido';
						exit;
					}


							
					// exit('checkout');	
					if($_POST['_id']!=''){

						$user = contenidos_user::find($_POST['_id']);	
						if($user){
							$user->nombre = $this->_xss->xss_clean(validador::getTexto('first-name'));
							$user->apellido = $this->_xss->xss_clean(validador::getTexto('last-name'));						
							$user->save();
						}
						

					}
					
														
					
					echo 1;
					exit; 
				}
			}
		}
	}

	public function changeEmail()
	{
		
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){


				if(!$this->_sess->get('autenticado_front')){
					echo "no";
					exit;
				}else{	

					 // echo "<pre>";print_r($_POST);exit;

					$this->_view->datos = $_POST;

					if(!validador::getPostParam('email-user')){
						// echo 'Debe introducir un nombre';
						echo $this->_conf['text_lang']['user']['info_email'][$_SESSION['_lang']]['validacion1'];
						exit;
					}


					if(!validador::getPostParam('confirm-email')){
						// echo 'Debe introducir un nombre';
						echo $this->_conf['text_lang']['user']['info_email'][$_SESSION['_lang']]['validacion2'];
						exit;
					}


					if(!validador::validarEmail($this->_view->datos['email-user'])){
						echo $this->_conf['text_lang']['user']['info_email'][$_SESSION['_lang']]['validacion3'];
						// echo 'Debe introducir un email valido';
						exit;
					}

					if(!validador::validarEmail($this->_view->datos['confirm-email'])){
						echo $this->_conf['text_lang']['user']['info_email'][$_SESSION['_lang']]['validacion4'];
						// echo 'Debe introducir un email valido';
						exit;
					}

					if($this->_view->datos['email-user'] != $this->_view->datos['confirm-email']){					
						echo $this->_conf['text_lang']['user']['info_email'][$_SESSION['_lang']]['validacion5'];
						// echo 'El email y repetir email no coinciden';
						exit;
					}

							
					// exit('checkout');	
					if($_POST['_id']!=''){

						$user = contenidos_user::find($_POST['_id']);	
						if($user){
							$user->email = $this->_xss->xss_clean(validador::getTexto('email-user'));
							$user->save();
						}
						

					}
					
														
					
					echo 1;
					exit; 
				}
			}
		}
	}

	public function changePass()
	{
		
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){


				if(!$this->_sess->get('autenticado_front')){
					echo "no";
					exit;
				}else{	

					 // echo "<pre>";print_r($_POST);exit;

					$this->_view->datos = $_POST;


					if(!validador::getPostParam('password-user')){
						echo $this->_conf['text_lang']['user']['info_pass'][$_SESSION['_lang']]['validacion1'];
						// echo 'Debe introducir un email valido';
						exit;
					}

					if(!validador::getPostParam('confirm-password-email')){
						echo $this->_conf['text_lang']['user']['info_pass'][$_SESSION['_lang']]['validacion2'];
						// echo 'Debe introducir un email valido';
						exit;
					}

					if($this->_view->datos['password-user']!= $this->_view->datos['confirm-password-email']){					
						echo $this->_conf['text_lang']['user']['info_pass'][$_SESSION['_lang']]['validacion3'];
						// echo 'El email y repetir email no coinciden';
						exit;
					}

							
					// exit('checkout');	
					if($_POST['_id']!=''){

						$user = contenidos_user::find($_POST['_id']);	
						if($user){
							// $user->password = $this->_xss->xss_clean(validador::getTexto('password-user'));
							$user->password = hash('sha256', $this->_xss->xss_clean($this->_view->datos['password-user']));
							$user->save();
						}
						

					}
					
														
					
					echo 1;
					exit; 
				}
			}
		}
	}


	public function changeAddress()
	{
		
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){


				if(!$this->_sess->get('autenticado_front')){
					echo "no";
					exit;
				}else{	

					 // echo "<pre>";print_r($_POST);exit;

					$this->_view->datos = $_POST;

					if(!validador::getPostParam('addressName')){
						// echo 'Debe introducir un nombre';
						echo $this->_conf['text_lang']['user']['address_shipping'][$_SESSION['_lang']]['validacion1'];
						exit;
					}

					if(!validador::getPostParam('addressLine1')){
						// echo 'Debe introducir un nombre';
						echo $this->_conf['text_lang']['user']['address_shipping'][$_SESSION['_lang']]['validacion2'];
						exit;
					}

					/*if(!validador::getPostParam('addressLine2')){
						// echo 'Debe introducir un nombre';
						echo $this->_conf['text_lang']['user']['address_shipping'][$_SESSION['_lang']]['validacion3'];
						exit;
					}*/

					if(!validador::getPostParam('zipCode')){
						// echo 'Debe introducir un nombre';
						echo $this->_conf['text_lang']['user']['address_shipping'][$_SESSION['_lang']]['validacion6'];
						exit;
					}

					if(!validador::getPostParam('city')){
						// echo 'Debe introducir un nombre';
						echo $this->_conf['text_lang']['user']['address_shipping'][$_SESSION['_lang']]['validacion4'];
						exit;
					}

					if(!validador::getPostParam('state')){
						// echo 'Debe introducir un nombre';
						echo $this->_conf['text_lang']['user']['address_shipping'][$_SESSION['_lang']]['validacion5'];
						exit;
					}

					if(!validador::getPostParam('country')){
						// echo 'Debe introducir un nombre';
						echo $this->_conf['text_lang']['user']['address_shipping'][$_SESSION['_lang']]['validacion7'];
						exit;
					}



					if($_POST['_id']!=''){
							
						$_envio = contenidos_shippin::find(array('conditions' => array('id_user = ?', $_POST['_id'])));

						if($_envio){
							
							// $_envio->id_user = $this->_sess->get('id_usuario_front');
							$_envio->address_name = $this->_xss->xss_clean($_POST['addressName']);
							$_envio->address_line_1 = $this->_xss->xss_clean($_POST['addressLine1']);	
							$_envio->address_line_2 = $this->_xss->xss_clean($_POST['addressLine2']);
							$_envio->zipcode = $this->_xss->xss_clean($_POST['zipCode']);
							$_envio->city = $this->_xss->xss_clean($_POST['city']);
							$_envio->state = $this->_xss->xss_clean($_POST['state']);
							$_envio->country = $this->_xss->xss_clean($_POST['country']);
							$_envio->fecha = date('Y-m-d H:i:s');	
							$_envio->save();
							
							

						}else{

							$_envio = new contenidos_shippin();	
							$_envio->id_user = $_POST['_id'];
							$_envio->address_name = $this->_xss->xss_clean($_POST['addressName']);
							$_envio->address_line_1 = $this->_xss->xss_clean($_POST['addressLine1']);	
							$_envio->address_line_2 = $this->_xss->xss_clean($_POST['addressLine2']);
							$_envio->zipcode = $this->_xss->xss_clean($_POST['zipCode']);
							$_envio->city = $this->_xss->xss_clean($_POST['city']);
							$_envio->state = $this->_xss->xss_clean($_POST['state']);
							$_envio->country = $this->_xss->xss_clean($_POST['country']);
							$_envio->fecha = date('Y-m-d H:i:s');	
							$_envio->save();

						}	

					}			
									
					
					echo 1;
					exit;
				}
			}
		}
		
	}


	public function changeAddressBilling()
	{
		
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){


				if(!$this->_sess->get('autenticado_front')){
					echo "no";
					exit;
				}else{	

					 // echo "<pre>";print_r($_POST);exit;

					$this->_view->datos = $_POST;

					if(!validador::getPostParam('addressNameBilling')){
						// echo 'Debe introducir un nombre';
						echo $this->_conf['text_lang']['user']['address_billing'][$_SESSION['_lang']]['validacion1'];
						exit;
					}

					if(!validador::getPostParam('addressLine1Billing')){
						// echo 'Debe introducir un nombre';
						echo $this->_conf['text_lang']['user']['address_billing'][$_SESSION['_lang']]['validacion2'];
						exit;
					}

					/*if(!validador::getPostParam('addressLine2Billing')){
						// echo 'Debe introducir un nombre';
						echo $this->_conf['text_lang']['user']['address_billing'][$_SESSION['_lang']]['validacion3'];
						exit;
					}*/

					if(!validador::getPostParam('zipCodeBilling')){
						// echo 'Debe introducir un nombre';
						echo $this->_conf['text_lang']['user']['address_billing'][$_SESSION['_lang']]['validacion6'];
						exit;
					}

					if(!validador::getPostParam('cityBilling')){
						// echo 'Debe introducir un nombre';
						echo $this->_conf['text_lang']['user']['address_billing'][$_SESSION['_lang']]['validacion4'];
						exit;
					}

					if(!validador::getPostParam('stateBilling')){
						// echo 'Debe introducir un nombre';
						echo $this->_conf['text_lang']['user']['address_billing'][$_SESSION['_lang']]['validacion5'];
						exit;
					}

					if(!validador::getPostParam('countryBilling')){
						// echo 'Debe introducir un nombre';
						echo $this->_conf['text_lang']['user']['address_billing'][$_SESSION['_lang']]['validacion7'];
						exit;
					}



					if($_POST['_id']!=''){
							
						$_envio = contenidos_billin::find(array('conditions' => array('id_user = ?', $_POST['_id'])));

						if($_envio){
							
							// $_envio->id_user = $this->_sess->get('id_usuario_front');
							$_envio->address_name = $this->_xss->xss_clean($_POST['addressNameBilling']);
							$_envio->address_line_1 = $this->_xss->xss_clean($_POST['addressLine1Billing']);	
							$_envio->address_line_2 = $this->_xss->xss_clean($_POST['addressLine2Billing']);
							$_envio->zipcode = $this->_xss->xss_clean($_POST['zipCodeBilling']);
							$_envio->city = $this->_xss->xss_clean($_POST['cityBilling']);
							$_envio->state = $this->_xss->xss_clean($_POST['stateBilling']);
							$_envio->country = $this->_xss->xss_clean($_POST['countryBilling']);
							$_envio->fecha = date('Y-m-d H:i:s');	
							$_envio->save();
							
							

						}else{

							$_envio = new contenidos_billin();	
							$_envio->id_user = $_POST['_id'];
							$_envio->address_name = $this->_xss->xss_clean($_POST['addressNameBilling']);
							$_envio->address_line_1 = $this->_xss->xss_clean($_POST['addressLine1Billing']);	
							$_envio->address_line_2 = $this->_xss->xss_clean($_POST['addressLine2Billing']);
							$_envio->zipcode = $this->_xss->xss_clean($_POST['zipCodeBilling']);
							$_envio->city = $this->_xss->xss_clean($_POST['cityBilling']);
							$_envio->state = $this->_xss->xss_clean($_POST['stateBilling']);
							$_envio->country = $this->_xss->xss_clean($_POST['countryBilling']);
							$_envio->fecha = date('Y-m-d H:i:s');	
							$_envio->save();

						}	

					}			
									
					
					echo 1;
					exit;
				}
			}
		}
		
	}

	







/*
    public function facturacion()
    {
        if(!$this->_sess->get('autenticado_front')){
            $this->redireccionar();
        }


        $this->_view->categorias = $this->homeGestion->traerCategorias();
        $this->_view->banners = $this->homeGestion->traerBanners(1, 6);
		$this->_view->user = $this->homeGestion->traerUser($this->_sess->get('id_usuario_front'));
		$this->_view->facturacion = $this->homeGestion->traerDatosFacturacion($this->_sess->get('id_usuario_front'));
		$this->_view->direcciones = $this->homeGestion->traerDireccionesEnvios($this->_sess->get('id_usuario_front'));
		$this->_view->cfdi = $this->homeGestion->traerCFDI();
		$this->_view->estados = $this->homeGestion->traerEstados();
		
		// echo "<pre>";print_r($this->_view->facturacion);exit;
		
		
		$this->_view->titulo = 'Cuenta';
        $this->_view->renderizar('facturacion','user', 'default');
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
										<input type="text" name="cod_postal" id="cod_postal" value="'.$dir['codigo_postal'].'">
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
										<input type="text" name="estado" id="estado" value="'.home::convertirCaracteres($dir['estado']).'" required="">
									</div>
									<div class="mid">
										<label for="telefono">* Pais</label>
										<input type="text" name="pais" id="pais" value="'.home::convertirCaracteres($dir['pais']).'" required="">
									</div>
									<button id="btCargarDir">Editar dirección</button>
									</form>';

						$_html .='<script>
									$("#form_envio").on("submit", function(e){
										e.preventDefault();
										e.stopPropagation();	
										
										$.ajax({
											type: "POST",
											url: _root_+"cuenta/cargarEnvio",
											data: $("#form_envio").serialize(),
											beforeSend: function(){
												$("#cont_form_direcciones").slideUp(800);
												$(".address").slideUp(800);
											},
											success: function(data){
												$(".address").html(data).slideDown(800);
												$("#form_envio input[type=\"text\"],input[type=\"number\"]").val("");
												$("#_id").val("");				
												$("#btAgregarDir").show();
								  				$("#btCerrarDir").hide();
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

	public function eliminarEnvio()
	{
		
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){


				if(!$this->_sess->get('autenticado_front')){
					echo "no";
					exit;
				}else{	

					 // echo "<pre>";print_r($_POST);exit;

							
					// exit('checkout');	
					if($_POST['id']!=''){

						$_envio = contenidos_direccion_envio::find($_POST['id']);	
						if($_envio){								
							$_envio->delete();
						}
						

					}
					
						


					$_direcciones = $this->homeGestion->traerDireccionesEnvios($this->_sess->get('id_usuario_front')); 
					if($_direcciones){
						$_html='';
						foreach($_direcciones as $dir){
						$_html .='<div class="box">
									<h4>'.home::convertirCaracteres($dir['titulo']).'</h4>
									<p>'.home::convertirCaracteres($dir['calle']).' Nº '.$dir['numero_ext'].' <br>
									   '.home::convertirCaracteres($dir['colonia']).',  '.home::convertirCaracteres($dir['municipio']).'. <br>
									   '.home::convertirCaracteres($dir['estado']).', CP '.$dir['codigo_postal'].'
									</p>
									<a onclick="$().editarDireccion('.$dir['id'].');" href="javascript:void(0);">Editar</a>
									<a onclick="$().eliminarDireccion('.$dir['id'].');" href="javascript:void(0);">Eliminar</a>
								</div><br>';
						}	

					}
									
					
					echo $_html;
					exit;
				}
			}
		}
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

						$_envio = contenidos_direccion_envio::find($_POST['_id']);	
						if($_envio){
							$_envio->id_user = $this->_sess->get('id_usuario_front');
							$_envio->titulo = $_POST['name'];
							// $_envio->telefono = $_POST['telefono'];	
							$_envio->codigo_postal = $_POST['cod_postal'];	
							$_envio->calle = $_POST['calle'];
							$_envio->numero_int = $_POST['N_interior'];
							$_envio->numero_ext = $_POST['N_exterior'];
							// $_envio->tipo_hospedaje = $_POST['tipo_hospedaje'];
							$_envio->colonia = $_POST['colonia'];
							$_envio->municipio = $_POST['municipio'];
							$_envio->estado = $_POST['estado'];
							$_envio->pais = $_POST['pais'];
							$_envio->fecha = date('Y-m-d H:i:s');	
							$_envio->save();
						}
						

					}else{

						$_envio = new contenidos_direccion_envio();	
						$_envio->id_user = $this->_sess->get('id_usuario_front');
						$_envio->titulo = $_POST['name'];
						// $_envio->telefono = $_POST['telefono'];	
						$_envio->codigo_postal = $_POST['cod_postal'];	
						$_envio->calle = $_POST['calle'];
						$_envio->numero_int = $_POST['N_interior'];
						$_envio->numero_ext = $_POST['N_exterior'];
						// $_envio->tipo_hospedaje = $_POST['tipo_hospedaje'];
						$_envio->colonia = $_POST['colonia'];
						$_envio->municipio = $_POST['municipio'];
						$_envio->estado = $_POST['estado'];
						$_envio->pais = $_POST['pais'];
						$_envio->fecha = date('Y-m-d H:i:s');	
						$_envio->save();

					}
					
						


					$_direcciones = $this->homeGestion->traerDireccionesEnvios($this->_sess->get('id_usuario_front')); 
					if($_direcciones){
						$_html='';
						foreach($_direcciones as $dir){
						$_html .='<div class="box">
									<h4>'.home::convertirCaracteres($dir['titulo']).'</h4>
									<p>'.home::convertirCaracteres($dir['calle']).' Nº '.$dir['numero_ext'].' <br>
									   '.home::convertirCaracteres($dir['colonia']).',  '.home::convertirCaracteres($dir['municipio']).'. <br>
									   '.home::convertirCaracteres($dir['estado']).', CP '.$dir['codigo_postal'].'
									</p>
									<a onclick="$().editarDireccion('.$dir['id'].');" href="javascript:void(0);">Editar</a>
									<a onclick="$().eliminarDireccion('.$dir['id'].');" href="javascript:void(0);">Eliminar</a>
								</div><br>';
						}	

					}
									
					
					echo $_html;
					exit;
				}
			}
		}
	}

	public function editarUser()
	{
		
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){


				if(!$this->_sess->get('autenticado_front')){
					echo "no";
					exit;
				}else{	

					 // echo "<pre>";print_r($_POST);exit;

					$this->_view->datos = $_POST;

							
					// exit('checkout');	
					if($_POST['_id']!=''){

						$user = contenidos_user::find($_POST['_id']);	
						if($user){
							$user->nombre = $this->_xss->xss_clean(validador::getTexto('nombre'));
							$user->apellido = $this->_xss->xss_clean(validador::getTexto('apellido'));						
							$user->telefono = $this->_xss->xss_clean($this->_view->datos['telefono']);						
							$user->fecha_nacimiento = date('d-m-Y', strtotime($this->_view->datos['fecha_nacimiento']));
							$user->save();
						}
						

					}
					
						


					$_user = $this->homeGestion->traerUser($this->_sess->get('id_usuario_front')); 
					if($_user){
					$_html ='<div class="mid">
									<label for="nombre">Nombre</label>
									<p>'.home::convertirCaracteres($_user['nombre']).'</p>
								</div>			
								<div class="mid">
									<label for="apellido">Apellido</label>
									<p>'.home::convertirCaracteres($_user['apellido']).'</p>
								</div>					
								<div class="mid">
									<label for="telefono">Telefono</label>
									<p>'.$_user['telefono'].'</p>
								</div>
								<div class="mid">
									<label for="fecha_nacimiento">Fecha de nacimiento</label>
									<p>'.$_user['fecha_nacimiento'].'</p>
								</div>';
					}	

					$jsondata['html'] = $_html;
					$jsondata['nomb'] = home::convertirCaracteres($_user['nombre']);
					$jsondata['apellido'] = home::convertirCaracteres($_user['apellido']);
					$jsondata['telefono'] = $_user['telefono'];
					$jsondata['fecha_nacimiento'] = $_user['fecha_nacimiento'];
    				$jsondata['nombre'] = 'Hola, '.home::convertirCaracteres($_user['nombre']).' '.home::convertirCaracteres($_user['apellido']);
    				echo json_encode($jsondata);
									
					
					// echo $_html;
					exit; 
				}
			}
		}
	}


	public function editarCuenta()
	{
		
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){


				if(!$this->_sess->get('autenticado_front')){
					echo "no";
					exit;
				}else{	

					 // echo "<pre>";print_r($_POST);exit;

					$this->_view->datos = $_POST;

							
					// exit('checkout');	
					if($_POST['_id']!=''){

						$user = contenidos_user::find($_POST['_id']);	
						if($user){
							$user->email = $this->_xss->xss_clean($this->_view->datos['email']);
							// $user->password = $this->_xss->xss_clean($this->_view->datos['pass']);
							if($this->_view->datos['password']!=''){
								$user->password = Hash::getHash('md5', $this->_xss->xss_clean($this->_view->datos['password']), $this->_conf['hash_key']);
							}
							$user->save();
						}
						

					}
					
						


					$_user = $this->homeGestion->traerUser($this->_sess->get('id_usuario_front')); 
					if($_user){
					$_html ='<div class="box-cuenta">												
								<div class="mid">
									<label for="email">Email</label>
									<p>'.$_user['email'].'</p>
								</div>
								<div class="mid">
									<label for="password">Contraseña</label>
									<p>*************</p>
								</div>
							</div>';
					}	

					$jsondata['html'] = $_html;					
    				$jsondata['email'] = $_user['email'];
    				echo json_encode($jsondata);
									
					
					// echo $_html;
					exit; 
				}
			}
		}
	}


	public function editarFacturacion()
	{
		
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){


				if(!$this->_sess->get('autenticado_front')){
					echo "no";
					exit;
				}else{	

					 // echo "<pre>";print_r($_POST);exit;

					$this->_view->datos = $_POST;

					// $_tipo_dir = ($_POST['dirEnvio']=='si') ? 'env' : 'fac';

					if(isset($_POST['dirEnvio']) && $_POST['dirEnvio']=='si'){
						
						if(!isset($_POST['address']) || $_POST['address'] ==''){
							$jsondata['msg'] = 'Debe seleccionar una direccion de envio.';
							echo json_encode($jsondata);
							exit;
						}

						$_tipo_dir = 'env';
						$_id_dir = $_POST['address'];

						// echo "<pre>";print_r($_POST);exit;

					}else{

						if(!isset($_POST['calle']) || $_POST['calle'] ==''){
							$jsondata['msg'] = 'Debe seleccionar una calle.';
							echo json_encode($jsondata);
							exit;
						}						

						if(!isset($_POST['numero']) || $_POST['numero'] ==''){
							$jsondata['msg'] = 'Debe seleccionar un numero interior.';
							echo json_encode($jsondata);
							exit;
						}

						if(!isset($_POST['numero2']) || $_POST['numero2'] ==''){
							$jsondata['msg'] = 'Debe seleccionar un numero exterior.';
							echo json_encode($jsondata);
							exit;
						}

						if(!isset($_POST['codigo_postal']) || $_POST['codigo_postal'] ==''){
							$jsondata['msg'] = 'Debe seleccionar un codigo postal.';
							echo json_encode($jsondata);
							exit;
						}

						if(!isset($_POST['colonia']) || $_POST['colonia'] ==''){
							$jsondata['msg'] = 'Debe seleccionar una colonia.';
							echo json_encode($jsondata);
							exit;
						}
						

						if(!isset($_POST['municipio']) || $_POST['municipio'] ==''){
							$jsondata['msg'] = 'Debe seleccionar un municipio.';
							echo json_encode($jsondata);
							exit;
						}

						if(!isset($_POST['estado']) || $_POST['estado'] ==''){
							$jsondata['msg'] = 'Debe seleccionar un estado.';
							echo json_encode($jsondata);
							exit;
						}

						// echo "<pre>";print_r($_POST);exit;

						$_tipo_dir = 'fac';
						$fact = new contenidos_facturacion_direccione();
						$fact->codigo_postal = $this->_xss->xss_clean($this->_view->datos['codigo_postal']);
						$fact->calle = $this->_xss->xss_clean(validador::getTexto('calle'));						
						$fact->numero_int = $this->_xss->xss_clean($this->_view->datos['numero']);
						$fact->numero_ext = $this->_xss->xss_clean($this->_view->datos['numero2']);
						$fact->colonia = $this->_xss->xss_clean(validador::getTexto('colonia'));
						$fact->municipio = $this->_xss->xss_clean(validador::getTexto('municipio'));
						$fact->estado = $this->_xss->xss_clean(validador::getTexto('estado'));
						$fact->pais = 'México';
						$fact->fecha = date('Y-m-d H:i:s');
						$fact->save();

						$_id_dir = $fact->id;
					}
							
					// exit('checkout');	
					if($_POST['_id']!=''){

						$fact = contenidos_datos_facturacio::find(array('conditions' => array('id_user = ?', $_POST['_id'])));
						if($fact){
							// $fact->id_user = $_POST['_id'];
							$fact->tipo_persona = $this->_xss->xss_clean($this->_view->datos['persona']);
							$fact->nombre = $this->_xss->xss_clean(validador::getTexto('nombre'));
							$fact->apellido_paterno = $this->_xss->xss_clean(validador::getTexto('apellido-p'));
							$fact->apellido_materno = $this->_xss->xss_clean(validador::getTexto('apellido-m'));
							$fact->razon_social = $this->_xss->xss_clean(validador::getTexto('razon_social'));
							$fact->rfc = $this->_xss->xss_clean($this->_view->datos['rfc']);						
							$fact->cfdi = $this->_xss->xss_clean($this->_view->datos['cfdi']);
							$fact->email = $this->_xss->xss_clean($this->_view->datos['email']);
							$fact->telefono = $this->_xss->xss_clean($this->_view->datos['telefono']);
							$fact->tipo_direccion = $_tipo_dir;
							$fact->id_direccion = $_id_dir;	

							// $fact->calle = $this->_xss->xss_clean(validador::getTexto('calle'));						
							// $fact->numero = $this->_xss->xss_clean($this->_view->datos['numero']);
							// $fact->codigo_postal = $this->_xss->xss_clean($this->_view->datos['codigo_postal']);
							// $fact->colonia = $this->_xss->xss_clean(validador::getTexto('colonia'));
							// $fact->municipio = $this->_xss->xss_clean(validador::getTexto('municipio'));
							// $fact->estado = $this->_xss->xss_clean(validador::getTexto('estado'));
							$fact->save();
						}else{
							$fact = new contenidos_datos_facturacio();
							$fact->id_user = $_POST['_id'];
							$fact->tipo_persona = $this->_xss->xss_clean($this->_view->datos['persona']);
							$fact->nombre = $this->_xss->xss_clean(validador::getTexto('nombre'));
							$fact->apellido_paterno = $this->_xss->xss_clean(validador::getTexto('apellido-p'));
							$fact->apellido_materno = $this->_xss->xss_clean(validador::getTexto('apellido-m'));
							$fact->razon_social = $this->_xss->xss_clean(validador::getTexto('razon_social'));
							$fact->rfc = $this->_xss->xss_clean($this->_view->datos['rfc']);						
							$fact->cfdi = $this->_xss->xss_clean($this->_view->datos['cfdi']);
							$fact->email = $this->_xss->xss_clean($this->_view->datos['email']);
							$fact->telefono = $this->_xss->xss_clean($this->_view->datos['telefono']);
							$fact->tipo_direccion = $_tipo_dir;
							$fact->id_direccion = $_id_dir;	
							// $fact->calle = $this->_xss->xss_clean(validador::getTexto('calle'));						
							// $fact->numero = $this->_xss->xss_clean($this->_view->datos['numero']);
							// $fact->codigo_postal = $this->_xss->xss_clean($this->_view->datos['codigo_postal']);
							// $fact->colonia = $this->_xss->xss_clean(validador::getTexto('colonia'));
							// $fact->municipio = $this->_xss->xss_clean(validador::getTexto('municipio'));
							// $fact->estado = $this->_xss->xss_clean(validador::getTexto('estado'));
							$fact->fecha = date('Y-m-d H:i:s');
							$fact->save();

						}
						

					}
					
					
					// exit('checkout');

					

					$jsondata['msg'] = 'carga_ok';
					// $jsondata['html'] = $_html;
					// $jsondata['nomb'] = home::convertirCaracteres($_fact['nombre']);	
					// $jsondata['apellido_paterno'] = home::convertirCaracteres($_fact['apellido_paterno']);	
					// $jsondata['apellido_materno'] = home::convertirCaracteres($_fact['apellido_materno']);					
					// $jsondata['rfc'] = $_fact['rfc'];
					// $jsondata['cfdi'] = $_fact['cfdi'];
					// $jsondata['calle'] = home::convertirCaracteres($_dir_fact['calle']);
					// $jsondata['numero'] = $_dir_fact['numero_int'];
					// $jsondata['numero2'] = $_dir_fact['numero_ext'];
					// $jsondata['codigo_postal'] = $_dir_fact['codigo_postal'];
					// $jsondata['colonia'] = home::convertirCaracteres($_dir_fact['colonia']);
					// $jsondata['municipio'] = home::convertirCaracteres($_dir_fact['municipio']);
					// $jsondata['estado'] = home::convertirCaracteres($_dir_fact['estado']);

    				echo json_encode($jsondata);
									
					
					// echo $_html;
					exit; 
				}
			}
		}
	}
*/











    


   /* public function traerNumeroCliente()
    {

    	if($_POST){

    		if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){

	    		$_cliente = $_POST['cliente'];

	    		$row = contenidos_cliente::find(array('conditions' => array('numero_cliente = ?', $_cliente)));
	    		if($row){
	    			$roww = contenidos_user::find(array('conditions' => array('id_cliente = ?', $row->id)));
	    			if($roww){
	    				if($roww->password!=''){
		    				$jsondata['status'] = 'existe';
		    				echo json_encode($jsondata);
							exit;
		    			}else{
		    				$jsondata['status'] = 'ok';
		    				$jsondata['nombre'] = $roww->nombre;
		    				echo json_encode($jsondata);
							exit;
		    			}
	    			}else{
	    				$jsondata['status'] = 'ok';
	    				$jsondata['nombre'] = '';
	    				echo json_encode($jsondata);
						exit;
	    			}
	    			
	    			$jsondata['status'] = 'ok';
    				$jsondata['nombre'] = '';
    				echo json_encode($jsondata);
					exit;

					
				}else{
					$jsondata['status'] = 'no';
					echo json_encode($jsondata);
					exit;
				}
			}

    	}

    }*/

    

    public function signout()
	{
		$this->_sess->destroy('autenticado_front');
        $this->_sess->destroy('id_usuario_front');
        $this->_sess->destroy('usuario_front');
        // $this->_sess->destroy('_tipo_envio');
        // $this->_sess->destroy('_datos_compra');
        // $this->_sess->destroy('img_error_user');
        $this->redireccionar();
    }
	
	
}
?>