<?php
// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
// header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE'); 
// header('X-Frame-Options: DENY');
ini_set('display_errors', 1);
ini_set('memory_limit', '-1');
// ini_set('upload_max_filesize ', '25M');
// ini_set('post_max_size', '25M');
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
// $protocol ="http://";
$domainName = $_SERVER['HTTP_HOST'].'/';
$_site_url = $protocol.$domainName;
//ini_set('session.cookie_lifetime', 0);
date_default_timezone_set('America/Argentina/Buenos_Aires');
// Se definen las rutas primarias del sitio
// Separador de directorios sensible a sistema operativo
define('DS', DIRECTORY_SEPARATOR);
define('RAIZ', realpath(dirname(__FILE__)) . DS);
define('NUCLEO_PATH', RAIZ . 'nucleo' . DS);
// La versión siempre definirla como un float. Por ejemplo: 5.0, 5.38, etc.
define('VERSION_PHP', 7.4);
define('SITE_URL', $_site_url);

try{
	require_once NUCLEO_PATH . 'Autoload.php';
	require_once NUCLEO_PATH . 'Version.php'; 
	
	new Version(VERSION_PHP);
	
	// Se inicia el registro
	$registro = Nucleo\Registro\Registro::tomarInstancia();
	
	// Se carga el archivo de configuracion
	$registro->_conf = Nucleo\Configuracion\Configuracion::datos();
	
	// Se inicia el request
	$registro->_request = new Nucleo\Request\Request();
	
	// Se carga y se inicia el archivo de session
	$registro->_sess = new Nucleo\Session\Session;
	//$registro->_sess->init();
	
	// Iniciamos el idioma por defecto
	$registro->_idioma = new Nucleo\Translation\Translation;
	
	// Iniciamos la sesion de la base de datos
	$registro->_ar = Nucleo\Ar\Ar::arrancar();
	
	// Se inicia la clase 'control de acceso'
	$registro->_acl = new Nucleo\Acl\Acl();
	
	// Se arranca la aplicacion
	Nucleo\Bootstrap\Bootstrap::run();

}
catch(Exception $e){
    echo $e->getMessage();
}