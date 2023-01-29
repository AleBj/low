<?php
 
class publico
{
	// Clases Estaticas
	public static function traerImgPorId($_id)
	{
		$_id = (int) $_id;
		return contenidos_imagene::find($_id);
	}
	
	public static function combertirCaracteres($_cadena)
	{
		return htmlspecialchars_decode(html_entity_decode($_cadena));
	}
	
	public static function crearTitulo($_titulo, $_extension)
	{
		$_titulo = strtolower($_titulo);
		return str_replace(" ","-",$_titulo) . $_extension;
	}
	
	public static function acortarParrafo($_parrafo, $_cantidad)
	{
		$_parrafo_acortado = array();
		$div = explode(' ', $_parrafo);
		
		for($i=0;$i<$_cantidad;$i++){
			$_parrafo_acortado[] = $div[$i];
		}	
		$_parrafo_acortado = implode($_parrafo_acortado, ' ');
		
		return $_parrafo_acortado;
	}
	
	// Conexion a base de datos
	public function traerReels($_categoria, $_estado)
	{
		return contenidos_reel::find(array('conditions' => array('id_categoria = ? AND estado = ?', $_categoria, $_estado)));
	}
	
	public function traerTrabajos($_categoria, $estado)
	{
		return contenidos_trabajo::find('all', array('conditions' => array('categoria_id = ? AND estado = ?', $_categoria, $estado)));
	}
	
	public function traerTrabajo($_id) 
	{
		return contenidos_trabajo::find($_id);
	}
	
	
	// Noticias
	public function traerNoticias($_estado)
	{
		return contenidos_noticia::find('all',array('conditions' => array('estado = ?', $_estado),'order' => 'id desc'));
	}
	
	public function traerNoticia($_id)
	{
		return contenidos_noticia::find($_id);
	}
	
	
	
	
	
	
	
	
	
	
	
	

}