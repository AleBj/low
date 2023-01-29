<?php

use Nucleo\Model\Model;

class setIdiomaModelWidget extends Model
{
	public $_gestion_idioma;
    public function __construct()
	{
        parent::__construct();
		
		require_once($this->_conf['raiz'] . 'widgets/libs/class.idioma.php');
		$this->_gestion_idioma = new Idioma();
    }
    
    public function getIdioma()
    {	
		return 'idiomass';
    }
	
}