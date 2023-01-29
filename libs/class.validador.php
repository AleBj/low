<?php
use Nucleo\Registro\Registro;
class validador
{
	public static function getTexto($clave)
    {
        if(isset($_POST[$clave]) && !empty($_POST[$clave])){
            $_POST[$clave] = htmlspecialchars($_POST[$clave], ENT_QUOTES);
			//$_POST[$clave] = self::tildesHtml($_POST[$clave]);
            return $_POST[$clave];
        }
        return '';
    }	
	
	public static function getTextos($clave,$indice)
    {
        if(isset($_POST[$clave][$indice]) && !empty($_POST[$clave][$indice])){
            $_POST[$clave][$indice] = htmlspecialchars($_POST[$clave][$indice], ENT_QUOTES);
			//$_POST[$clave] = self::tildesHtml($_POST[$clave]);
            return $_POST[$clave][$indice];
        }
        return '';
    }
	
	public static function getInt($clave)
    {
        if(isset($_POST[$clave]) && !empty($_POST[$clave])){
            $_POST[$clave] = filter_input(INPUT_POST, $clave, FILTER_VALIDATE_INT);
            return $_POST[$clave];
        }
        
        return 0;
    }
	
	public static function getIntFloat($clave)
	{
		if(isset($_POST[$clave]) && !empty($_POST[$clave])){
            $_POST[$clave] = filter_input(INPUT_POST, $clave, FILTER_VALIDATE_FLOAT);
            return $_POST[$clave];
        }
        
        return 0;
	}
	
	public static function numerico($_data)
	{
		if(is_numeric($_data)){
			return true;
		}else{
			return false;	
		}
	}
	
	public static function validarParametroInt($_parametro,$_baseUrl)
	{
		if($_parametro == null){
			header("location:" . $_baseUrl . "error/access/9090");
			exit;
		}else{
			$_parametro = (int) $_parametro;
			
			if($_parametro == 0){
				header("location:" . $_baseUrl . "error/access/9090");
				exit;
			}
			return;	
		}
	}
	
	public static function filtrarInt($int)
    {
        $int = (int) $int;
        
        if(is_int($int))
            return $int;
    }
    
    public static function getPostParam($clave)
    {
        /*if(isset($_POST[$clave]))
            return $_POST[$clave];*/
            
        $clave = htmlentities($clave, ENT_QUOTES);

        if(isset($_POST[$clave])){
            return $_POST[$clave];
        }else{
        	return false;
        }
    }
    
    public static function getAlphaNum($clave)
    {
        if(isset($_POST[$clave]) && !empty($_POST[$clave]))
            $_POST[$clave] = (string) preg_replace('/[^A-Z0-9_]/i', '', $_POST[$clave]);
            return trim($_POST[$clave]);
    }
    
    public static function validarEmail($email)
    {	
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return false;
        }
        return true;
    }
    
    public static function formatPermiso($clave)
    {
        if(isset($_POST[$clave]) && !empty($_POST[$clave]))
            $_POST[$clave] = (string) preg_replace('/[^A-Z_]/i', '', $_POST[$clave]);
            return trim($_POST[$clave]);
    }
	
	public static function getSql($clave,$db)
    {
        if(isset($_POST[$clave]) && !empty($_POST[$clave])){
            $_POST[$clave] = strip_tags($_POST[$clave]);
            
            if(!get_magic_quotes_gpc()){
                $_POST[$clave] = mysql_real_escape_string($_POST[$clave], 
								 mysql_connect(
								 	Registro::tomarInstancia()->_conf['baseDatos'][Registro::tomarInstancia()->_conf['baseDatos']['actual']]['db_host'], 
									Registro::tomarInstancia()->_conf['baseDatos'][Registro::tomarInstancia()->_conf['baseDatos']['actual']]['db_usuario'], 
									Registro::tomarInstancia()->_conf['baseDatos'][Registro::tomarInstancia()->_conf['baseDatos']['actual']]['db_pass']
								));
            }
            return trim($_POST[$clave]);
        }
    }
	
	public static function validarIdRegistro($_id)
	{
		$_id = (int) $_id;
		$_registro = permiso::find($_id);
        
        if($_registro){
			
			return $_registro;
	
		}else 
			return false;
	}
	
	public static function largoCadena($_cadena, $_largo)
	{
		$_contar = strlen($_cadena);
		
		if($_contar < $_largo || $_contar > $_largo)
			return false;
				else return true;
	}

	public static function largoCadenaMin($_cadena, $_largo)
	{
		$_contar = strlen($_cadena);

		if($_contar < $_largo)
			return false;
				else return true;
	}


	public static function largoCadenaMax($_cadena, $_largo)
	{
		$_contar = strlen($_cadena);

		if($_contar > $_largo)
			return false;
				else return true;
	}
	
	public static function validarStr($_cadena, $_largo)
	{
		$_perm = "/^[A-Z üÜáéíóúÁÉÍÓÚñÑ]{1," . $_largo . "}$/i";
		
		if(preg_match($_perm,$_cadena)){
      		return true; // Campo permitido 
		}
		return false;
	}
	
	public static function rangoNum($_valor, $_inicio , $_final)
	{
		if($_valor < $_inicio || $_valor > $_final){
			return false;
		}else{
			return true;
		}
	
	}
	
	public static function validarUrl($_url)
	{		
		return (!filter_var($_url, FILTER_VALIDATE_URL)) ? false : true;
	}

	public static function validarUrlExist($_url)
	{	
		return (!file_get_contents($_url)) ? false : true;
	}
	
	public function DefinirCodigo($_codigo = false)
	{
		if($_codigo != false){
			
			$_codigo = self::filtrarInt($_codigo);
		
            if(is_int($_codigo))
                $_codigo = $_codigo;
        }else{
            $_codigo = 'default';
        }
		return $_codigo;
	}

	public static function validadorAccion(array $_data, $_accion)
	{
		if(!in_array($_accion, $_data)){
			//return false;
			throw new Exception(header("location:" . $_baseUrl . "error/access/9090"));
		}
		return true;
	}
	
	
	
	
}