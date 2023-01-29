<?php

namespace Nucleo\Translation;

class Translation
{
	public function establecerIdioma()
	{
		// primero, buscamos la cookie del idioma
		/*if(isset($_COOKIE['idioma'])){
			return $_COOKIE['idioma'];
		}else{
			setcookie( "idioma", 1, time() + (60 * 60 * 24 * 30), "/");
			return (isset($_COOKIE['idioma'])) ? $_COOKIE['idioma'] : 1;
		}*/

		if(isset($_SESSION['_lang'] )){
			return $_SESSION['_lang'];
		}else{
			$_SESSION['_lang'] = 1;
			return $_SESSION['_lang'];
		}
	}	
}