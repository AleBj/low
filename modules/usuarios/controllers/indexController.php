<?php

use controllers\usuariosController\usuariosController;

class indexController extends usuariosController
{
    private $_usuarios;
  

    public function __construct() 
    {
        parent::__construct();
    }

   
    public function index()
    {
		$this->redireccionar('usuarios/login');		
     

    }
    

}

