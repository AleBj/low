<?php

namespace Nucleo\Acl;

use Nucleo\Registro\Registro;
use ActiveRecord;
use \Exception;

class Acl
{
    private $_registro;
    private $_id;
    private $_role;
    private $_permisos;
    
    public function __construct($id = false)
    {
        $this->_registro = Registro::tomarInstancia();
		
		if($id){
            $this->_id = (int) $id;
        }else{
            if($this->_registro->_sess->get('level')){
                $this->_id = $this->_registro->_sess->get('level');
            }else{
                $this->_id = 0;
            }
        }
        $this->_role = $this->getRole();
        $this->_permisos = $this->getPermisosRole();
        $this->compilarAcl();
    }
    
    public function compilarAcl()
    {
        $this->_permisos = array_merge(
                	$this->_permisos,
                	$this->getPermisosUsuario()
                );
    }
    
    public function getRole()
    {
		if($this->_id){
			$role = role::find($this->_id);
			
			if($role){
				return $role->id_role;
			}
		}
    }
    
    public function getPermisosRoleId()
    {
		$id = array();
		
		if($this->_role){
			
			$ids = permisos_role::find('all', array('select' => 'permiso', 'conditions' => "role = " . $this->_role));
			
			foreach($ids as $idds){
				$id[] = $idds->permiso;
			}
		}
		return $id;
    }
    
    public function getPermisosRole()
    {	
		$permisos = permisos_role::find('all', array('conditions' => array('role = ?', $this->_role)));
		
		$data = array();
		
		foreach($permisos as $perm){
			
			$key = $this->getPermisoKey($perm->permiso);
			if($key == '') continue;
			
			$v = ($perm->valor == 1) ? true : false;

			$data[$key] = array(
                'key' => $key,
                'permiso' => $this->getPermisoNombre($perm->permiso),
                'valor' => $v,
                'heredado' => true,
                'id' => $perm->permiso,
            );
		}
        return $data;
    }
    
    public function getPermisoKey($permisoID)
    {
        $permisoID = (int) $permisoID;
		
		$key = permiso::find($permisoID);
		
		return $key->key;
    }
    
    public function getPermisoNombre($permisoID)
    {
        $permisoID = (int) $permisoID;
		
		$key = permiso::find($permisoID);
		
		return $key->permiso;
    }
    
    public function getPermisosUsuario()
    {
        $ids = $this->getPermisosRoleId();
		$data = array();
		$permisos = (count($ids)) ? permisos_usuario::all(array('conditions' => array('usuario = ? AND permiso in (?)', $this->_id, implode(",", $ids)))) : array();

		if(count($permisos)){
			
			foreach($permisos as $perm){
				$key = $this->getPermisoKey($perm->permiso);
				if($key == '') continue;
				
				$v = ($perm->valor == 1) ? true : false;
				
				$data[$key] = array(
					'key' => $key,
					'permiso' => $this->getPermisoNombre($perm->permiso),
					'valor' => $v,
					'heredado' => false,
					'id' => $perm->permiso,
				);	
			}	
		}
        
        return $data;
    }
    
    public function getPermisos()
    {
        if(isset($this->_permisos) && count($this->_permisos))
            return $this->_permisos;
    }
    
    public function permiso($key)
    {
        if(array_key_exists($key, $this->_permisos)){
            if($this->_permisos[$key]['valor'] == true || $this->_permisos[$key]['valor'] == 1){
                return true;
            }
        }
        return false;
    }
    
    public function acceso($key)
    {   
        if($this->permiso($key)){
            $this->_registro->_sess->tiempo();
            return;
        }

        if(!$this->_registro->_sess->get('autenticado')){
            // $this->redireccionar('administrador');
            header("location:" . $this->_registro->_conf['base_url'] . "admin");
            exit;
        }
        
        header("location:" . $this->_registro->_conf['base_url'] . "error/access/5050");
        exit;
    }
	
	public function traerPermisos()
	{
		$_permisos = array();
		
		$_valores = permiso::all();
		
		foreach($_valores as $permiso){
			$_permisos[] = $permiso->key;
		}
		
		return $_permisos;
	}
}

class role extends ActiveRecord\Model{}
class permisos_role extends ActiveRecord\Model{}
class permiso extends ActiveRecord\Model{}
class permisos_usuario extends ActiveRecord\Model{}