<?php

use controllers\adminController\adminController;

class indexController extends adminController
{
    private $_usuarios;
  

    public function __construct() 
    {
        parent::__construct();
    }

   
    public function index()
    {
		$this->redireccionar('admin/login');		
     

    }
    

}

