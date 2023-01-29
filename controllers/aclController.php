<?php

use Nucleo\Controller\Controller;

class aclController extends Controller
{
    private $_aclm;
    
    public function __construct() 
    {
        parent::__construct();
        $this->_aclm = $this->loadModel('acl');
    }
    
    public function index()
    {
        $this->_view->titulo = 'Listas de acceso';
        $this->_view->renderizar('index', 'acl');
    }
    
    public function roles()
    {
        $this->_view->titulo = 'Administracion de roles';
        $this->_view->roles = $this->_aclm->getRoles();
        $this->_view->renderizar('roles', 'acl');
    }
	   
    public function permisos_role($roleID)
    {
        $id = $this->filtrarInt($roleID);
        
        if(!$id){
            $this->redireccionar('acl/roles');
        }
        
        $row = $this->_aclm->getRole($id);
        
        if(!$row){
            $this->redireccionar('acl/roles');
        }
        
        $this->_view->titulo = 'Administracion de permisos role';
        
        if($this->getInt('guardar') == 1){
            $values = array_keys($_POST);
            $replace = array();
            $eliminar = array();
            
            for($i = 0; $i < count($values); $i++){
                if(substr($values[$i],0,5) == 'perm_'){
                    $permiso = (strlen($values[$i]) - 5);
                    
                    if($_POST[$values[$i]] == 'x'){
                        $eliminar[] = array(
                            'role' => $id,
                            'permiso' => substr($values[$i], -$permiso)
                        );
                    }else{		
						$v = ($_POST[$values[$i]] == 1) ? 1 : 0;
                        $replace[] = array(
                            'role' => $id,
                            'permiso' => substr($values[$i], -$permiso),
                            'valor' => $v
                        );
                    }
                }
            }
            
            for($i = 0; $i < count($eliminar); $i++){
                $this->_aclm->eliminarPermisoRole(
                        $eliminar[$i]['role'],
                        $eliminar[$i]['permiso']);
            }
            
            for($i = 0; $i < count($replace); $i++){
                $this->_aclm->editarPermisoRole(
                        $replace[$i]['role'],
                        $replace[$i]['permiso'],
                        $replace[$i]['valor']);
            }
        }
        
        $this->_view->role = $row;
        $this->_view->permisos = $this->_aclm->getPermisosRole($id);
        $this->_view->renderizar('permisos_role');
    }

    public function nuevo_role()
    {
        $this->_view->titulo = 'Nuevo Role';
        
        if($this->getInt('guardar') == 1){
            $this->_view->datos = $_POST;
            
            if(!$this->getSql('role')){
                $this->_view->_error = 'Debe introducir el nombre del role';
                $this->_view->renderizar('nuevo_role', 'acl');
                exit;
            }
            
            $this->_aclm->insertarRole($this->getSql('role'));
            $this->redireccionar('acl/roles');
        }
        
        $this->_view->renderizar('nuevo_role', 'acl');
    }
    
    public function permisos()
    {
        $this->_view->titulo = 'Administracion de permisos';
        $this->_view->permisos = $this->_aclm->getPermisos();
        $this->_view->renderizar('permisos', 'acl');
    }
    
    public function nuevo_permiso()
    {
        $this->_view->titulo = 'Nuevo Permiso';
        
        if($this->getInt('guardar') == 1){
            $this->_view->datos = $_POST;
            
            if(!$this->getSql('permiso')){
                $this->_view->_error = 'Debe introducir el nombre del permiso';
                $this->_view->renderizar('nuevo_permiso', 'acl');
                exit;
            }
            
            if(!$this->getAlphaNum('key')){
                $this->_view->_error = 'Debe introducir el key del permiso';
                $this->_view->renderizar('nuevo_permiso', 'acl');
                exit;
            }
            
            $this->_aclm->insertarPermiso(
                    $this->getSql('permiso'), 
                    $this->getAlphaNum('key')
                    );
            
            $this->redireccionar('acl/permisos');
        }
        
        $this->_view->renderizar('nuevo_permiso', 'acl');
    }
}	