<?php
 
namespace Nucleo\View;
use Nucleo\Registro\Registro;
use \Exception;

class View
{
	private $_idioma;
	private $_sess;
	private $_conf;
    private $_js;
    private $_metas;
	private $_css;
    private $_rutas;
    private $_jsPlugin;
	private $_cssPlugin;
	private $_template;
	private $_ruta_template;
	private $_item;
    
    public function __construct()
    {
		$this->_idioma = Registro::tomarInstancia()->_idioma->establecerIdioma();
		$this->_sess = Registro::tomarInstancia()->_sess;
		$this->_conf = Registro::tomarInstancia()->_conf;
        $this->_js = array();
        $this->_metas = array();
		$this->_css = array();
        $this->_rutas = array();
        $this->_jsPlugin = array();
		$this->_cssPlugin = array();
		$this->_template = ($this->setLayoutPorRoles() != false) ? $this->setLayoutPorRoles() : Registro::tomarInstancia()->_conf['layout_principal'];
		$this->_ruta_template = RAIZ . 'views' . DS . 'layout' . DS . $this->_template . DS;
		$this->_item = '';
		
        $modulo = Registro::tomarInstancia()->_request->getModulo();
        $controlador = Registro::tomarInstancia()->_request->getControlador();
		
        if($modulo){
            $this->_rutas['view'] = RAIZ . 'modules' . DS . $modulo . DS . 'views' . DS . $controlador . DS;
            $this->_rutas['js'] = Registro::tomarInstancia()->_conf['base_url'] . 'modules/' . $modulo . '/views/' . $controlador . '/js/';
			$this->_rutas['css'] = Registro::tomarInstancia()->_conf['base_url'] . 'modules/' . $modulo . '/views/' . $controlador . '/css/';
        }else{
            $this->_rutas['view'] = RAIZ . 'views' . DS . $controlador . DS;
            $this->_rutas['js'] = Registro::tomarInstancia()->_conf['base_url'] . 'views/' . $controlador . '/js/';
			$this->_rutas['css'] = Registro::tomarInstancia()->_conf['base_url'] . 'views/' . $controlador . '/css/';
        }
    }
	
    public function renderizar($vista, $item = false, $_template = false)
    {	
		$this->_item = ($item) ? $item : '';
		//$this->_nav = ($nav) ? $nav : '';
		//echo $this->_item;exit;
		
		if($_template != false){
			if(file_exists(RAIZ . 'views' . DS . 'layout' . DS . $_template)){
				$this->_template = $_template;
				$this->_ruta_template = RAIZ . 'views' . DS . 'layout' . DS . $_template . DS;	
			}else throw new Exception('El Template "' . $_template . '" no existe');	
		}
		
		
		// Parametros locales de cada vista.  
        $_params = array(
            'ruta_css' 		=> Registro::tomarInstancia()->_conf['base_url'] . 'views/layout/' . $this->_template . '/css/',
			'ruta_font' 	=> Registro::tomarInstancia()->_conf['base_url'] . 'views/layout/' . $this->_template . '/fonts/',
            'ruta_img' 		=> Registro::tomarInstancia()->_conf['base_url'] . 'views/layout/' . $this->_template . '/img/',
            'ruta_js' 		=> Registro::tomarInstancia()->_conf['base_url'] . 'views/layout/' . $this->_template . '/js/',
            'item' 			=> $this->_item,
            'meta'		 	=> $this->_metas,
			//'nav' 			=> $this->_nav,
            'js'		 	=> $this->_js,
			'css'		 	=> $this->_css,
            'js_plugin' 	=> $this->_jsPlugin,
			'css_plugin' 	=> $this->_cssPlugin,
			'idioma' 		=> $this->setLanguage(),
			'url' 			=> Registro::tomarInstancia()->_conf['base_url'],
			'url_enl' 		=> Registro::tomarInstancia()->_conf['url_enlace'],
        );
		
		// Aqui se resuleven los datos
		// principales de las vistas.
		$rutaVista = $this->_rutas['view'] . $vista . '.phtml';
		$_acl = Registro::tomarInstancia()->_acl;
		if($this->_template!='forms'){
			$widgets = $this->getWidgets();
		}
		
		
		if(is_readable($rutaVista)){
			if(is_readable($this->_ruta_template . 'header.php') && is_readable($this->_ruta_template . 'footer.php')){	
				require_once $this->_ruta_template . 'header.php';
				require_once $rutaVista;
				require_once $this->_ruta_template . 'footer.php';
			}else{
				throw new Exception('error de template. El template no exite.');	
			}
		}else{
			throw new Exception('error de vista');
		}
    }
	
	// En esta funcion, cargamos los datos del idioma pre-definido
	public function setLanguage()
	{
		if(!empty($this->_idioma)){
			return Registro::tomarInstancia()->_conf['traslation'][$this->_idioma];
		}else{
			return Registro::tomarInstancia()->_conf['traslation'][Registro::tomarInstancia()->_conf['idioma']];
		}
	}
    
	// Esta clase setea los layouts en funcion de
	// los roles de los usuarios que estan en sesion. 
	public function setLayoutPorRoles()
	{	
		if(isset(Registro::tomarInstancia()->_conf['roles']) && is_array(Registro::tomarInstancia()->_conf['roles'])){
			if(isset(Registro::tomarInstancia()->_conf['roles'][Registro::tomarInstancia()->_sess->get('level')])){
				return Registro::tomarInstancia()->_conf['roles'][Registro::tomarInstancia()->_sess->get('level')];
			}
			return false;
		}else{
			throw new Exception('error de seteo de roles');	
		}
	}// metodo reescrito el 16 de noviembre de 2013
	
    public function setJs(array $js)
    {
        if(is_array($js) && count($js)){
            for($i=0; $i < count($js); $i++){
                $this->_js[] = $this->_rutas['js'] . $js[$i] . '.js';
            }
        }else{
            throw new Exception('Error de js');
        }
    }
	

	public function setMetas(array $meta)
    {
        if(is_array($meta) && count($meta)){
            for($i=0; $i < count($meta); $i++){
				$this->_metas[] = $meta[$i];
            }
        }else{
            throw new Exception('Error de metas');
        }
    }
    
	 public function setCss(array $css)
    {
        if(is_array($css) && count($css)){
            for($i=0; $i < count($css); $i++){
                $this->_css[] = $this->_rutas['css'] . $css[$i] . '.css';
            }
        }else throw new Exception('Error de css');
    }
    
    public function setJsPlugin(array $js)
    {
        if(is_array($js) && count($js)){
            for($i=0; $i < count($js); $i++){
                $this->_jsPlugin[] = Registro::tomarInstancia()->_conf['base_url'] . 'public/js/' .  $js[$i] . '.js';
            }
        }else{
            throw new Exception('Error de js plugin');
        }
    }
	
	public function setCssPlugin(array $css)
    {
        if(is_array($css) && count($css)){
            for($i=0; $i < count($css); $i++){
                $this->_cssPlugin[] = Registro::tomarInstancia()->_conf['base_url'] . 'public/css/' .  $css[$i] . '.css';
            }
        }else{
            throw new Exception('Error de Css plugin');
        }
    }
	public function setTemplate($template)
	{
		$this->_template = (string) $template;
	}
	
	// Funciones para Widgets
    private function getWidgets()
    {
		$widConf = (isset(Registro::tomarInstancia()->_conf['widgets'])) ? Registro::tomarInstancia()->_conf['widgets'] : array();
		$widgets = array();
		$nombreWidget = array();
		
		# Establecemos la matriz que contendra los widgets
		$valFij = array('config' => array(),'content' => array());
		
		foreach($widConf as $valor => $f){
			
			$valFij['config'] = $this->widget($f['clase'], $f['metodos']['conf']);
			
			// ATENCION:
			// Aqui se resuelve el tema del paso de parametros:
			// Si esta definido el parametro 'parametros', significa
			// que el metodo definido en 'cont' los posee y necesita.
			// De esta forma se los envia en un array en forma de 
			// tercer parámetro.
			if(isset($f['metodos']['parametros'])){
				if(is_array($f['metodos']['parametros']) && !empty($f['metodos']['parametros'])){
					$valFij['content'] = array($f['clase'],$f['metodos']['cont'],$f['metodos']['parametros']);
				}
			}else{
				$valFij['content'] = array($f['clase'],$f['metodos']['cont']);
			}
			
			$widgets[$valor] = $valFij;			
			$nombreWidget[$valor] = array();		
		}
		
        $positions = $nombreWidget;
        $keys = array_keys($widgets);
        foreach($keys as $k){
			// Primero, chequeamos si la vista que estamos renderizando
			// tiene seteado el widget para su vista
            if(array_key_exists($widgets[$k]['config']['nombre'],$positions)){	
				// Si esta especificado, si es una matriz, si no esta en esa matriz
        		if(isset($widgets[$k]['config']['ocultar']) && 
					is_array($widgets[$k]['config']['ocultar']) && 
						!in_array($this->_item, $widgets[$k]['config']['ocultar'])){
       						// entonces llenamos la posicion del layout.	
							$positions[$k] = $this->getWidgetContent($widgets[$k]['content']);
      			}
            }else{
				$positions = array();	
			}
        }
        return $positions;
    }
    
    public function getWidgetContent(array $content)
    {
        if(!isset($content[0]) || !isset($content[1])){
            throw new Exception('Error contenido widget');
            return;
        }
        
        if(!isset($content[2])){
            $content[2] = array();
        }
        return $this->widget($content[0],$content[1],$content[2]);
    }
	
	public function widget($widget, $method, $options = array())
    {
        if(!is_array($options)){
            $options = array($options);
        }
        
        if(is_readable(RAIZ . 'widgets' . DS . $widget . '.php')){
			
            include_once RAIZ . 'widgets' . DS . $widget . '.php';
            $widgetClass = $widget . 'Widget';
            
            if(!class_exists($widgetClass)){
                throw new Exception('Error clase widget');
            }
            
            if(is_callable($widgetClass, $method)){
                if(count($options)){
                    return call_user_func_array(array(new $widgetClass, $method), $options);
                }else{
                    return call_user_func(array(new $widgetClass, $method));
                }
            }
            throw new Exception('Error metodo widget');
        } 
        throw new Exception('Error de widget');
    }
	
	/*public function getLayoutPositions()
    {
        if(is_readable(RAIZ . 'views' . DS . 'layout' . DS . $this->_template . DS . 'configs.php')){
            include_once RAIZ . 'views' . DS . 'layout' . DS . $this->_template . DS . 'configs.php';
            
            return get_layout_positions($this->_conf);
        }
        throw new Exception('Error configuracion layout');
    }*/
}