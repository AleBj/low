<?php

use Nucleo\Controller\Controller;

class notfoundController extends Controller
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
		
		$this->_view->titulo = '404 - Page Not Found';   
        $this->_view->renderizar('index', 'notfound', 'default');
		
    }
	
	
}
?>