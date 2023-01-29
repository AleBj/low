<?php

class acl
{
	public function tomarRole($_roleId)
	{
		$_role = role::find(array('conditions' => array('id_role = ?', $_roleId)));
		return $_role; 
	}
	
	public function tomarPermisosRoles($_roleId)
	{
		$_permisos = permisos_role::all(array('conditions' => array('role = ?', $_roleId)));
		
		foreach($_permisos as $permi){
			
			$key = $this->getPermisoKey($permi->permiso);
			
			if($key == ''){continue;}
				if($permi->valor == 1){
					$v = true;
				}else{
					$v = false;
				}
			
			$data[$key] = array(
				'key' => $key,
				'valor' => $v,
				'nombre' => $this->getPermisoNombre($permi->permiso),
				'id' => $permi->permiso,
				'permiso_id' => $permi->id
			);
		}
		
		$todos = $this->getPermisosAll();
		$final = (isset($data)) ? array_merge($todos,$data) : $todos;
        
        return $final;
	}
	
	public function getPermisoKey($_permisoId)
    {
        $_permisoId = (int) $_permisoId;
		$_key = permiso::find(array('conditions' => array('id_permiso = ?', $_permisoId)));
        
        return $_key->key;
    }
	
	public function getPermisoNombre($_permisoId)
    {
        $_permisoId = (int) $_permisoId;
		$_key = permiso::find(array('conditions' => array('id_permiso = ?', $_permisoId)));
        
        return $_key->permiso;
    }
	
	public function getPermisosAll()
    {
		$permisos = permiso::all();
		
		foreach($permisos as $datos){
			
			$data[$datos->key] = array(
				'key' => $datos->key,
				'valor' => 'x',
				'nombre' => $datos->permiso,
				'id' => $datos->id_permiso,
				'permiso_id' => $datos->id
			);		
		}
		return $data;
    }
	
	public function eliminarPermisoRole($_roleId, $_permisoId)
	{
		$_borrar = permisos_role::find(array('conditions' => array('permiso = ? AND role = ?', $_permisoId, $_roleId)));
		
		if($_borrar) $_borrar->delete();	
	}
	
	public function editarPermisoRole($_role, $_permiso, $_valor)
    {
		$buscar = permisos_role::find(array('conditions' => array('role = ? AND permiso = ?', $_role, $_permiso)));
		
		if($buscar){
			
			$editar = permisos_role::find($buscar->id);
			$editar->valor = $_valor;
			$editar->save();
			
		}else{
			
			permisos_role::create(array(
				'role' 		=> $_role,
				'permiso' 	=> $_permiso,
				'valor' 	=> $_valor
			));		
		}	
    }
	
	public function tomarPermisos()
	{	
		$_permisos = permiso::all();
		return $_permisos;
	}
	
	public function borrarPermiso($_id)
	{
		$_id = (int) $_id;
		
		$_permiso = permiso::find($_id);
		$_permisos_role = permisos_role::find('all', array('conditions' => array('permiso = ?', $_id)));
		
		if($_permiso){
			$_permiso->delete();
		}
		
		if($_permisos_role){
			foreach($_permisos_role as $_borrar){
				$_foo = permisos_role::find($_borrar->id);
				$_foo->delete();
			}	
		}
		return;
	}
	
	public function editarPermiso($_id, $_permiso,$_key)
	{
		$_id = (int) $_id;
		
		$_editar = permiso::find($_id);
		
		if($_editar)
			$_editar->permiso = $_permiso;
			$_editar->key = $_key;
			$_editar->save();
				return;
	}
}