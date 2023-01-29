<?php

use Nucleo\Controller\Controller;

class callbackController extends Controller
{
	public $homeGestion;
	
    public function __construct()
	{
        parent::__construct();
		
		// $this->getLibrary('class.home');		
		// $this->homeGestion = new home();			
				
		// $this->getLibrary('class.validador');		
				
    }
    
   	public function index()
   	{
   		
   		echo "<pre>";print_r($_GET);exit;

   		$this->_view->titulo = 'The Quick Divorce';	
		$this->_view->renderizar('index', 'callback', 'vacio');
   	}	


   

	
}
?>