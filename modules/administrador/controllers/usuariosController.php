<?php

use controllers\administradorController\administradorController;

class usuariosController extends administradorController
{
	public $usuariosGestion;
	public $_filtro;
	public $_error;
	
    public function __construct() 
    {
        parent::__construct();
		$this->getLibrary('class.validador');
		
		$this->getLibrary('class.usuarios');
		$this->usuariosGestion = new usuarios();
		
		$this->_filtro = '';
		$this->_error = 'has-error';
    }
    
    public function index()
    {
		$this->_acl->acceso('admin_access');

		$this->redireccionar('administrador/usuarios/listar');	
        $this->_view->titulo = 'Administrador - Usuarios';
        $this->_view->renderizar('index', 'usuarios');
    }
	
	public function listar($pagina = false)
	{
		$this->_acl->acceso('admin_access');
		
		//$pagina = (!validador::filtrarInt($pagina)) ? false : (int) $pagina;
		//$paginador = new Paginador();
		
		// Traemos los roles
		$this->_view->roles = $this->usuariosGestion->traerRoles();
		
		if($_POST){
			if($_POST['_filtrar'] == 1){
				$this->_filtro = validador::getPostParam('_roles');
			}
		}
		
		// Y los carhamos en el paginador
		$this->_view->usuarios = $this->usuariosGestion->TraerUsuarios($this->_filtro);
		//$this->_view->usuarios = $paginador->paginar($this->usuariosGestion->TraerUsuarios($this->_filtro), $pagina, 20);
		
		// Creamos la paginacion
		//$this->_view->paginacion = $paginador->getView('paginador-bootstrap', 'administrador/usuarios/listar');
        $this->_view->titulo = 'Administrador - Listar Usuarios';
        $this->_view->renderizar('listar', 'usuarios');
	}
	
	public function cargar()
	{
		$this->_acl->acceso('admin_access');
		
		// Traemos los roles
		$this->_view->roles = $this->usuariosGestion->traerRoles();
		
		if($_POST){
			if($_POST['envio01'] == 1){
				$this->_view->data = $_POST;
				
				if(!validador::getTexto('usuario_nombre')){
					$this->_view->errorNombre = $this->_error;
					$this->_view->_error = 'Debe colocar un Nombre';
					$this->_view->titulo = 'Administrador - Cargar Usuarios';
					$this->_view->renderizar('cargar', 'usuarios');
					exit;
				}
				
				if(!validador::getTexto('usuario_usuario')){
					$this->_view->errorUsuario = $this->_error;
					$this->_view->_error = 'Debe colocar un Usuario';
					$this->_view->titulo = 'Administrador - Cargar Usuarios';
					$this->_view->renderizar('cargar', 'usuarios');
					exit;
				}
				
				if(!validador::getTexto('usuario_email')){
					$this->_view->errorEmail = $this->_error;
					$this->_view->_error = 'Debe colocar un Email';
					$this->_view->titulo = 'Administrador - Cargar Usuarios';
					$this->_view->renderizar('cargar', 'usuarios');
					exit;
				}
				
				if(!validador::validarEmail(validador::getPostParam('usuario_email'))){
					$this->_view->errorEmail = $this->_error;
					$this->_view->_error = 'Debe colocar un Email V&aacute;lido';
					$this->_view->titulo = 'Administrador - Cargar Usuarios';
					$this->_view->renderizar('cargar', 'usuarios');
					exit;
				}
				
				if($this->usuariosGestion->verificarEmail(validador::getPostParam('usuario_email'))){
					$this->_view->_error = 'Este email ya esta registrado';
					$this->_view->titulo = 'Administrador - Cargar Usuarios';
					$this->_view->renderizar('cargar', 'usuarios');
					exit;
				}
				
				if(!validador::getTexto('usuario_pass')){
					$this->_view->errorPass = $this->_error;
					$this->_view->_error = 'Debe colocar un Password';
					$this->_view->titulo = 'Administrador - Cargar Usuarios';
					$this->_view->renderizar('cargar', 'usuarios');
					exit;
				}
				
				if(!validador::getTexto('usuario_repetir_pass')){
					$this->_view->errorPassRepetir = $this->_error;
					$this->_view->_error = 'Debe repetir el Password';
					$this->_view->titulo = 'Administrador - Cargar Usuarios';
					$this->_view->renderizar('cargar', 'usuarios');
					exit;
				}
				
				if(validador::getTexto('usuario_pass') != validador::getTexto('usuario_repetir_pass')){
					$this->_view->_error = 'Los Password no coinciden';
					$this->_view->titulo = 'Administrador - Cargar Usuarios';
					$this->_view->renderizar('cargar', 'usuarios');
					exit;
				}
				
				$this->usuariosGestion->registrarUsuario(
											validador::getTexto('usuario_nombre'), 
											validador::getTexto('usuario_usuario'), 
											validador::getPostParam('usuario_email'), 
											validador::getPostParam('usuario_rol'), 
											validador::getTexto('usuario_pass'), 
											1, 
											$this->_conf['hash_key']
										);

				$this->redireccionar('administrador/usuarios/listar');	
			}	
		}
		
        $this->_view->titulo = 'Administrador - Cargar Usuarios';
        $this->_view->renderizar('cargar', 'usuarios');
	}
	
	public function editar($_id)
    {
		$this->_acl->acceso('admin_access');
		validador::validarParametroInt($_id,$this->_conf['base_url']);
		
		$this->_view->usuario = $this->usuariosGestion->traerUsuario($_id);
		$this->_view->roles = $this->usuariosGestion->traerRolesEditar($this->_view->usuario->role);
		
		if($_POST){
			if($_POST['envio01'] == 1){
				if(!validador::getTexto('usuario_nombre')){
					$this->_view->errorNombre = $this->_error;
					$this->_view->_error = 'Debe colocar un Nombre';
					$this->_view->titulo = 'Administrador - Editar Usuarios';
					$this->_view->renderizar('editar', 'usuarios');
					exit;
				}
				
				if(!validador::getTexto('usuario_usuario')){
					$this->_view->errorUsuario = $this->_error;
					$this->_view->_error = 'Debe colocar un Usuario';
					$this->_view->titulo = 'Administrador - Editar Usuarios';
					$this->_view->renderizar('editar', 'usuarios');
					exit;
				}
				
				if(!validador::getTexto('usuario_email')){
					$this->_view->errorEmail = $this->_error;
					$this->_view->_error = 'Debe colocar un Email';
					$this->_view->titulo = 'Administrador - Editar Usuarios';
					$this->_view->renderizar('editar', 'usuarios');
					exit;
				}
				
				if(!validador::validarEmail(validador::getPostParam('usuario_email'))){
					$this->_view->errorEmail = $this->_error;
					$this->_view->_error = 'Debe colocar un Email V&aacute;lido';
					$this->_view->titulo = 'Administrador - Editar Usuarios';
					$this->_view->renderizar('editar', 'usuarios');
					exit;
				}
				
				if(validador::getPostParam('usuario_email') != $this->_view->usuario->email){
					if($this->usuariosGestion->verificarEmail(validador::getPostParam('usuario_email'))){
						$this->_view->_error = 'Este email ya esta registrado';
						$this->_view->titulo = 'Administrador - Editar Usuarios';
						$this->_view->renderizar('editar', 'usuarios');
						exit;
					}
				}
				
				if(validador::getTexto('usuario_pass')){
					
					if(!validador::getTexto('usuario_repetir_pass')){
						$this->_view->errorPassRepetir = $this->_error;
						$this->_view->_error = 'Debe repetir el Password';
						$this->_view->titulo = 'Administrador - Editar Usuarios';
						$this->_view->renderizar('editar', 'usuarios');
						exit;
					}
					
					if(validador::getTexto('usuario_pass') != validador::getTexto('usuario_repetir_pass')){
						$this->_view->_error = 'Los Password no coinciden';
						$this->_view->titulo = 'Administrador - Cargar Usuarios';
						$this->_view->renderizar('editar', 'usuarios');
						exit;
					}
					$_nuevo_pass = validador::getTexto('usuario_pass');
				}else{
					$_nuevo_pass = '';	
				}
				
				$this->usuariosGestion->editarUsuario(
											$this->_view->usuario->id, 
											validador::getTexto('usuario_nombre'), 
											validador::getTexto('usuario_usuario'), 
											validador::getPostParam('usuario_email'), 
											validador::getPostParam('usuario_rol'), 
											$_nuevo_pass, 
											$this->_conf['hash_key']
										);

				$this->redireccionar('administrador/usuarios/listar');
			}
		}
        $this->_view->titulo = 'Administrador - Usuarios - Editar';
        $this->_view->renderizar('editar', 'usuarios');
    }
	
	public function borrar($_id)
	{
		$this->_acl->acceso('admin_access');
		validador::validarParametroInt($_id,$this->_conf['base_url']);
		
		$this->usuariosGestion->borrarUsuario($_id);
		
		$this->redireccionar('administrador/usuarios/listar');
	}
	
	
	
	
}
