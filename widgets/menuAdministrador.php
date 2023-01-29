<?php
use Nucleo\Registro\Registro;
use Nucleo\Widget\Widget;

class menuAdministradorWidget extends Widget
{
	private $_registro;
	private $_sess;
    private $modelo;
    
    public function __construct(){
		$this->_registro = Registro::tomarInstancia();
		$this->_sess = $this->_registro->_sess;
        $this->modelo = $this->loadModel('menuAdministrador');
    }
    
    public function getMenu($item = false)
    {
		// ATENCION: el valor, en este caso 'menuSecundario' es 
		// tomado como referencia al pasarle el array a un metodo
		// que utliliza la funcion nativa de php 'extract'.
		// por lo tanto, en la vista, la variable que contiene los
		// datos sera $menuSecundario.
		$item = $this->_sess->get('item');
        $data['menuAdministrador'] = $this->modelo->getMenu($item);
        return $this->render('menu-administrador', $data);
    }
    
    public function getConfig()
    {
        return array(
			// aqui se coloca el nombre del widguet. Este nombre
			// debe ser el mismo que se especifica en el archivo
			// de configuración. Se verifica y se ejecuta el wid
			// get.
            'nombre' => 'menu_administrador',
			// Si el widget se deja visible para todas
			// las vistas, simplemente dejar el array vacio.
            'ocultar' => array(),
        );
    }
}