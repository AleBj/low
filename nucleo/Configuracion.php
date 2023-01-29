<?php

namespace Nucleo\Configuracion;

require 'configuracion' . DS . 'baseDatos' . DS . 'baseDatos.php';
require 'configuracion' . DS . 'traslate' . DS . 'traslate.php';
require 'configuracion' . DS . 'widgets' . DS . 'widgets.php';
require 'configuracion' . DS . 'baseUrl' . DS . 'baseUrl.php';
require 'configuracion' . DS . 'urlEnlace' . DS . 'urlEnlace.php';
require 'configuracion' . DS . 'clavesEmail' . DS . 'clavesEmail.php';
require 'configuracion' . DS . 'clavesStripe' . DS . 'clavesStripe.php';
require 'configuracion' . DS . 'clavesDocusign' . DS . 'clavesDocusign.php';
require 'configuracion' . DS . 'Tokens' . DS . 'Tokens.php';

class Configuracion
{	
	public function __construct() {
		
		//$this->_lang = $_lang;
		/*if(!$this->_sess->get('lang')){
			$this->_sess->set('lang', 'es');
		}*/
	
	}
	
	public static function datos()
	{
		return array(
			// Sitio
			// Version Apache mínima requerida
			'version_php'				=> '7.4',
			
			// Raiz real del sitio
			'raiz'						=> RAIZ,
			// Definicion de modulos.
			// Aqui se colocan los modulos activos actuales de la aplicacion
			'modulos'					=> array('administrador','usuarios', 'admin'),
			
			// Definimos la url que sera la base del ruteo en la aplicacion
			'base_url' 					=> \BaseUrl\BaseUrl::data(),
			
			// Definimos la url que servira de enlace en las vistas
			'url_enlace' 				=> \UrlEnlace\UrlEnlace::data(),

			// Claves SMTP
			'smtp' 						=> \clavesEmail\clavesEmail::data(),

			// Claves Stripe
			'stripe' 					=> \ClavesStripe\ClavesStripe::data(),

			// Claves Docusign
			'docusign' 					=> \ClavesDocusign\ClavesDocusign::data(),

			// Tokens
			'tokens' 					=> \Tokens\Tokens::data(),

			// Geo IP Country
			// 'geo_country' 				=> \GeoIp\GeoIp::data(),

			// Extension que se transfiere a los archivos finales
			'extension'					=> '.html',
			
			'errores'					=> array(
											'acceso' => array(
															'autenticado' => '5050', // acceso autenticado
															'nivel' => '6060', // acceso de nivel
															'extricto' => '7070',
															'tiempo' => '8080',
														),
											),
			
			// Se define el controlador por defecto de la aplicacion.
			'default_controller'	 	=> 'index',
			
			// Se define el Layout que se cargara por defecto.
			'layout_principal'			=> 'default',
											
			// Se definen los niveles de usuario y sus layout correspondientes
			'roles'					=> array(
										 1 => 'admin',
										 2 => 'admin',
										 3 => 'admin'
										),
			
			// Se establece el nombre, slogan y la compania de la aplicacion
			'app_nombre'				=> '',
			'app_slogan'				=> 'Administrador',
			'app_compania'				=> '',
			
			// Idioma por default
			'idioma'					=> 1,
			
			// Tiempo por defecto de la sesiones
			'tiempo_sesion' 			=> '60',
			
			// Hash Key para la encriptacion en md5. Se puede modificar.
			'hash_key'					=> '4f6a6d832be79',
			
			
			// Idioma
			'traslation' 				=> \Traslate\Traslate::data(),
			
			
			
			// cache de elementos
			'ruta_cache'				=> array(
											'noticias' => 'tmp/cache/noticias/',
											'slider' => 'tmp/cache/slider/',
											'home' => 'tmp/cache/home/',
											'widgets' => array(
													'menus' => 'tmp/cache/widgets/menus/',	
												),
											),
			
			// Accesos de la base de datos primaria	
			'baseDatos'				    =>	\BaseDatos\BaseDatos::data(),
			
			// Directorios de modelos
			'directorio_modelos'		=> 'modelos', 
			
			// Datos para la construccion del sitio			
			// peso imagenes
			'peso_maximo_imagenes' 			=> '3', //MB

			'peso_maximo_videos' 			=> '24', //MB

			'peso_maximo_archivos' 			=> '5', //MB
									
			// Tamaño de imagenes cargadas
			'formatos_img'				=> array(

										'seo_grandes' => array(
															'ancho' => 1200,
															'alto' 	=> 630,
														),
										'seo_thumb' => array(
															'ancho' => 200,
															'alto' 	=> 105,
														),

										'blog_grandes' => array(
															'ancho' => 495,
															'alto' 	=> 400,
														),
										'blog_thumb' => array(
															'ancho' => 360,
															'alto' 	=> 273,
														),
										'press_grandes' => array(
															'ancho' => 300,
															'alto' 	=> 300,
														),
										'press_thumb' => array(
															'ancho' => 150,
															'alto' 	=> 150,
														),
																	
										),	
											
			// En este archivo se colocan las imagenes subidas
			// por defecto. Se puede cambiar, por supuesto

			'ruta_img_cargadas'			=> RAIZ . 'public' . DS . 'img' . DS . 'subidas' . DS,
			
			'ruta_archivos_descargas'	=> RAIZ . 'public' . DS . 'descargas' . DS,

			'ruta_archivos_templates'	=> RAIZ . 'public' . DS . 'templates' . DS,
			
			'ruta_archivos'				=> RAIZ . 'public' . DS . 'files' . DS,
			
			'ruta_videos'				=> RAIZ . 'public' . DS . 'videos' . DS,
			
			'ruta_swf_cargados'			=> RAIZ . 'public' . DS . 'swf' . DS,
			
			'rango_pag'					=> array(
											'home' => 10,
										),
			// Widgets
			'widgets'					=> \Widgets\Widgets::data(),
			);	
	}
}