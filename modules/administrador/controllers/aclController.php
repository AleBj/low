<?php

use controllers\administradorController\administradorController;

class aclController extends administradorController
{
	public $_aclGestion;
	public $_error;
	
    public function __construct() 
    {
        parent::__construct();
		$this->getLibrary('class.validador');
		
		$this->getLibrary('class.acl');
		$this->_aclGestion = new acl();
		
		$this->_error = 'has-error';
    }
    
    public function index()
    {
		//$this->_acl->acceso('admin_access');
		
        $this->_view->titulo = 'Administrador';
        $this->_view->renderizar('index', 'acl');
    }
	
	public function roles()
    {
		//$this->_acl->acceso('admin_access');
		
        $this->_view->titulo = 'Administracion de roles';
		$this->_view->roles = role::all();
        $this->_view->renderizar('roles', 'acl');
    }
	
	public function permisos_role($_roleID = null)
    {		
		validador::validarParametroInt($_roleID,$this->_conf['base_url']);
		
		$this->_view->role = $this->_aclGestion->tomarRole($_roleID);
        
        if(!$this->_view->role){
            $this->redireccionar('administrador/acl/roles');
        }
		
		$this->_view->titulo = 'Administracion de permisos role';
		
		if(validador::getInt('guardar') == 1){
			
            $values = array_keys($_POST);
            $ignorar = array();
			$editar = array(); 
            
            for($i = 0; $i < count($values); $i++){
                if(substr($values[$i],0,5) == 'perm_'){
                    $permiso = (strlen($values[$i]) - 5);
                    
                    if($_POST[$values[$i]] == 'x'){
						
                        $ignorar[] = array(
                            'role' => $_roleID,
                            'permiso' => substr($values[$i], -$permiso)
                        );
						
                    }else{	
						$v = ($_POST[$values[$i]] == 1) ? 1 : 0;
						
						$editar[] = array(
							'role' => $_roleID,
							'permiso' => substr($values[$i], -$permiso),
							'valor' => $v
						);
                    }
                }
            }
	
            for($i = 0; $i < count($ignorar); $i++){
                $this->_aclGestion->eliminarPermisoRole(
                        $ignorar[$i]['role'],
                        $ignorar[$i]['permiso']);
            }
           	
			for($i = 0; $i < count($editar); $i++){
                $this->_aclGestion->editarPermisoRole(
                        $editar[$i]['role'],
                        $editar[$i]['permiso'],
                        $editar[$i]['valor']);
            }
        }
			
		$this->_view->permisos = $this->_aclGestion->tomarPermisosRoles($_roleID);
        $this->_view->renderizar('permisos_role','acl');	
	}
	
	public function permisos()
    {
		$this->_acl->acceso('admin_access');
        $this->_view->titulo = 'Administrador - Acl - Permisos';

		if(validador::getInt('editar_permiso') == 1){
           
		   	if(!validador::validarIdRegistro(validador::getTexto('editar_permiso_id'))){
				$this->redireccionar('"error/access/9090"');
		   	}
            
      		if(!validador::getTexto('permiso')){
                $this->_view->_error = 'Debe introducir un Permiso';
                $this->_view->permisos = $this->_aclGestion->tomarPermisos();
        		$this->_view->renderizar('permisos', 'acl');
                exit;
            }
            
            if(!validador::getAlphaNum('key')){
                $this->_view->_error = 'Debe introducir una Key';
                $this->_view->permisos = $this->_aclGestion->tomarPermisos();
        		$this->_view->renderizar('permisos', 'acl');
                exit;
            }
            
			$this->_aclGestion->editarPermiso(
									validador::getTexto('editar_permiso_id'), 
									validador::getTexto('permiso'),
									validador::getAlphaNum('key'));
            
            $this->redireccionar('administrador/acl/permisos');
        }
		
		$this->_view->permisos = $this->_aclGestion->tomarPermisos();
        $this->_view->renderizar('permisos', 'acl');
    }
	
	public function nuevo_permiso()
    {
		$this->_acl->acceso('admin_access');
		
        $this->_view->titulo = 'Nuevo Permiso';
        
        if(validador::getInt('guardar') == 1){
            $this->_view->datos = $_POST;
            
            if(!validador::getTexto('permiso')){
				$this->_view->errorCopete = $this->_error;
                $this->_view->_error = 'Debe introducir el nombre del permiso';
                $this->_view->renderizar('nuevo_permiso', 'acl');
                exit;
            }
            
            if(!validador::getAlphaNum('key')){
				$this->_view->errorKey = $this->_error;
                $this->_view->_error = 'Debe introducir el key del permiso';
                $this->_view->renderizar('nuevo_permiso', 'acl');
                exit;
            }
            
			$noticia = permiso::create(array(
						'permiso'	=> validador::getTexto('permiso'),
						'key'		=> validador::getAlphaNum('key')
					));
            
            $this->redireccionar('administrador/acl/permisos');
        }
        $this->_view->renderizar('nuevo_permiso', 'acl');
    }
	
	public function borrar_permiso($_id = NULL)
	{   
        if(validador::validarParametroInt($_id,$this->_conf['base_url'])){
            $this->redireccionar('administrador/acl/permisos');
        }
		
		$borrar = $this->_aclGestion->borrarPermiso($_id);
		$this->redireccionar('administrador/acl/permisos');
	}
}