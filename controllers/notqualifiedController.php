<?php

use Nucleo\Controller\Controller;

class notqualifiedController extends Controller
{
	public $homeGestion;
	
    public function __construct()
	{
        parent::__construct();
		
		$this->getLibrary('class.home');		
		$this->homeGestion = new home();			
				
		$this->getLibrary('class.validador');		
				
    }
    
   	public function index()
   	{

   		/*if(!$this->_sess->get('autenticado_front')){

   			$this->redireccionar();
			
		}else{

			$this->_view->user = home::traerUserPorId($this->_sess->get('id_usuario_front'));

		}*/
   		// echo "<pre>";print_r($this->_view->aboutus);echo "</pre>";exit;

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
		$this->_view->renderizar('index', 'notqualified', 'default');
   	}	


   

	
}
?>