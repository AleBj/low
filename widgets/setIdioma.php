<?php
use Nucleo\Registro\Registro;
use Nucleo\Widget\Widget;

class setIdiomaWidget extends Widget
{
	private $_registro;
	private $_sess;
	private $_idioma;
    
    public function __construct(){
		$this->_registro = Registro::tomarInstancia();
		$this->_sess = $this->_registro->_sess;
		$this->_idioma = $this->loadModel('setIdioma');
    }
    
    public function getIdioma()
    {
		// ATENCION: el valor, en este caso 'menuSecundario' es 
		// tomado como referencia al pasarle el array a un metodo
		// que utliliza la funcion nativa de php 'extract'.
		// por lo tanto, en la vista, la variable que contiene los
		// datos sera $menuSecundario.
        $data['setIdioma'] = $this->_idioma->getIdioma();
        return $this->render('set-idioma', $data);
    }
    
    public function getConfig()
    {
        return array(
			// aqui se coloca el nombre del widguet. Este nombre
			// debe ser el mismo que se especifica en el archivo
			// de configuración. Se verifica y se ejecuta el wid
			// get.
            'nombre' => 'set_idioma',
			// Si el widget se deja visible para todas
			// las vistas, simplemente dejar el array vacio.
            'ocultar' => array(),
        );
    }
}