<?php
 
class usuarios
{
	public function traerRoles()
	{
		//return role::find('all', array('conditions' => array('role != ?', 'Administrador')));
		return role::all();	
	}
	
	public function traerRolesEditar($_id)
	{
		//return role::find('all', array('conditions' => array('role != ? AND id_role != ?', "Administrador", $_id)));
		return role::find('all', array('conditions' => array('role != ?', $_id)));	
	}
	
	public function verificarEmail($_email)
	{
		return usuario::find(array('conditions' => array('email = ?', $_email)));
	}
	
	public function registrarUsuario($_nombre, $_usuario, $_email, $_rol, $_password, $_estado, $_hash)
	{		
		// Carga de usuario nuevo en base
		$_fechaBd = date('Y-m-d H:i:s', strtotime('now'));

		$_nuevo_usuario = new usuario();
		$_nuevo_usuario->nombre = $_nombre;
		$_nuevo_usuario->usuario = $_usuario;
		$_nuevo_usuario->pass = Hash::getHash('sha512', $_password, $_hash);
		$_nuevo_usuario->email = $_email;
		$_nuevo_usuario->role = $_rol;
		$_nuevo_usuario->estado = $_estado;
		$_nuevo_usuario->fecha = "$_fechaBd";
		$_nuevo_usuario->codigo = $this->crearCodigo();
		$_nuevo_usuario->save();
		return $_nuevo_usuario->id;
	}
	
	public function editarUsuario($_id, $_nombre, $_usuario, $_email, $_rol, $_password, $_hash)
	{
		$_fechaBd = date('Y-m-d H:i:s', strtotime('now'));

		$_editar_usuario = usuario::find($_id);
		$_editar_usuario->nombre = $_nombre;
		$_editar_usuario->usuario = $_usuario;
		if($_password != ''){
			$_editar_usuario->pass = Hash::getHash('sha512', $_password, $_hash);
		}
		$_editar_usuario->email = $_email;
		$_editar_usuario->role = $_rol;
		$_editar_usuario->fecha = "$_fechaBd";
		$_editar_usuario->save();	
		return true;
	}
	
	public function registrarUsuarioGestion($_id, $_rol, $_prov, $_super='')
	{		
		$_editar_usuario_gestion = usuarios_gestio::find(array('conditions' => array('id_user = ?', $_id)));	
		$_fechaBd = date('Y-m-d');	

		if($_editar_usuario_gestion){
			$_editar_usuario_gestion->rol = $_rol;
			$_editar_usuario_gestion->supermercados = $_super;
			$_editar_usuario_gestion->provincias = $_prov;
			$_editar_usuario_gestion->save();
		}else{
			$_nuevo_usuario_gestion = new usuarios_gestio();
			$_nuevo_usuario_gestion->id_user = $_id;
			$_nuevo_usuario_gestion->rol = $_rol;
			$_nuevo_usuario_gestion->supermercados = $_super;
			$_nuevo_usuario_gestion->provincias = $_prov;
			$_nuevo_usuario_gestion->fecha = "$_fechaBd";
			$_nuevo_usuario_gestion->save();
		}
		return true;
	}
	public function editarUsuarioGestion($_id, $_rol, $_prov, $_super='')
	{
		$_editar_usuario_gestion = usuarios_gestio::find(array('conditions' => array('id_user = ?', $_id)));	
		$_fechaBd = date('Y-m-d');	

		if($_editar_usuario_gestion){
			$_editar_usuario_gestion->rol = $_rol;
			$_editar_usuario_gestion->supermercados = $_super;
			$_editar_usuario_gestion->provincias = $_prov;
			$_editar_usuario_gestion->save();
		}else{
			$_nuevo_usuario_gestion = new usuarios_gestio();
			$_nuevo_usuario_gestion->id_user = $_id;
			$_nuevo_usuario_gestion->rol = $_rol;
			$_nuevo_usuario_gestion->supermercados = $_super;
			$_nuevo_usuario_gestion->provincias = $_prov;
			$_nuevo_usuario_gestion->fecha = "$_fechaBd";
			$_nuevo_usuario_gestion->save();
		}
		
		return true;	
	}
	public function crearCodigo()
	{
		return rand((int)1182598471, (int)9999999999);
	}
	
	public function TraerUsuarios($_filtro)
	{
		$_filtro = (int) $_filtro;
		return ($_filtro == '') ? usuario::all() : usuario::find('all', array('conditions' => array('role = ?', $_filtro)));	
	}
	
	public function traerUsuario($_id)
	{
		$id = (int) $_id;
		return usuario::find($_id);
	}

	public static function traerUsuarioPorId($_id)
	{
		$id = (int) $_id;
		return usuario::find($_id);
	}
	
	public static function traerUsuarioPorRol($_rol)
	{
		//$rol = (int) $_rol;
		//return usuario::find($_id);
		return usuario::find('all',array('conditions' => array('role = ?', $_rol)));
	}
	
	public function traerUsuarioGestion($_id)
	{
		$id = (int) $_id;
		return usuarios_gestio::find(array('conditions' => array('id_user = ?', $_id)));
	}
	public static function combertirRole($_id)
	{
		$id = (int) $_id;
		return role::find(array('conditions' => array('id_role = ?', $_id)));
	}
	
	public static function combertirEstado($_estado)
	{
		$_estado = (int) $_estado;
		
		switch($_estado){
			case 1 : return 'Activo'; break;
			default : return 'Inactivo'; break;
		}
	}
	
	public function borrarUsuario($_id)
	{
		// El metodo de borrado de usuario, tranfiere
		// los datos a la tabla de usuarios frezados.
		// Los datos nunca se pierden.
		
		// Primero traemos los datos del usuario
		$_usuario = usuario::find($_id);
		$_alta = true;
		
		foreach($_usuario->fecha as $alta){
			$_alta = $alta;
			break;
		}
		
		
		// luego tranferimos los datos.
		// Carga de usuario nuevo en base
		$_fechaBd = date('Y-m-d');

		$_usuario_off = new usuarios_off();
		$_usuario_off->nombre = $_usuario->nombre;
		$_usuario_off->usuario = $_usuario->usuario;
		$_usuario_off->email = $_usuario->email;
		$_usuario_off->role = $_usuario->role;
		$_usuario_off->alta = "$_alta";
		$_usuario_off->baja = "$_fechaBd";
		$_usuario_off->save();	
		
		/*$_usuario_gestion_off = usuarios_gestio::find(array('conditions' => array('id_user = ?', $_id)));		
		if($_usuario_gestion_off){
			$_nuevo_usuario_gestion_off = new usuarios_gestion_off();
			$_nuevo_usuario_gestion_off->id_user = $_usuario_off->id;
			$_nuevo_usuario_gestion_off->rol = $_usuario_gestion_off->rol;
			$_nuevo_usuario_gestion_off->supermercados = $_usuario_gestion_off->supermercados;
			$_nuevo_usuario_gestion_off->provincias = $_usuario_gestion_off->provincias;
			$_nuevo_usuario_gestion_off->fecha = "$_fechaBd";
			$_nuevo_usuario_gestion_off->save();
		}
		$_usuario_gestion_off->delete();*/
		
		$_usuario->delete();
		
		return true;
	}
	
	public static function traerProvGestion($_id)
    {
        return usuarios_gestio::find(array('conditions' => array('id_user = ?', $_id)));
    }
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}