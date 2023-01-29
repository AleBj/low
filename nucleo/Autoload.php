<?php

 
 // Autoload chequea varios parametros antes de iniciar 
 // el sistema.
 
 // Chequea que la clase
 // que se requiere exista y la devuelve
function autoloadCore($class)
{
	$parts = explode('\\', $class);
	if(file_exists(NUCLEO_PATH . ucfirst(strtolower(end($parts))) . '.php'))
		require_once NUCLEO_PATH . ucfirst(strtolower(end($parts))) . '.php';
}

// autoloadLibs carga las librerias requeridas
// una vez comprobada su existencia.
function autoloadLibs($class)
{
	if(file_exists(RAIZ . 'libs' . DS . 'class.' . strtolower($class) . '.php'))
		include_once RAIZ . 'libs' . DS . 'class.' . strtolower($class) . '.php';
}

// La funciones PHP 'spl_autoload_register' registra
// las funciones dadas como implementación de __autoload()

spl_autoload_register('autoloadCore');
spl_autoload_register('autoloadLibs');

// Iniciamos la libreria AR
include_once RAIZ . 'libs' . DS . 'ar' . DS . 'ActiveRecord.php'; 