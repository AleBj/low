<?php
use Nucleo\Registro\Registro;

class Paginador
{
	private $_registro;
    private $_datos;
    private $_paginacion;
	protected $_conf;
    
    public function __construct(){
		$this->_registro = Registro::tomarInstancia();
		$this->_conf = $this->_registro->_conf;
        $this->_datos = array();
        $this->_paginacion = array();
    }
    
    public function paginar($query, $pagina = false, $limite = false, $paginacion = false)
    {

		$pagina = ($pagina && is_numeric($pagina)) ? $pagina : 1;
		$limite = ($limite && is_numeric($limite)) ? $limite : 10;
		$inicio = ($pagina && is_numeric($pagina)) ? ($pagina - 1) * $limite : 0;
        
         if ( is_array( $query ) ) { 
            $registros = count($query);
        } else {   
          $registros = 0;
        }
        
        $total = ceil($registros / $limite);
        if ( is_array( $query ) ) { 
         $this->_datos = array_slice($query, $inicio, $limite);
        } 

        $paginacion = array();
        $paginacion['actual'] = $pagina;
        $paginacion['total'] = $total;
        $paginacion['limite'] = $limite;
        
        if($pagina > 1){
            $paginacion['primero'] = 1;
            $paginacion['anterior'] = $pagina - 1;
        } else {
            $paginacion['primero'] = '';
            $paginacion['anterior'] = '';
        }
        
        if($pagina < $total){
            $paginacion['ultimo'] = $total;
            $paginacion['siguiente'] = $pagina + 1;
        } else {
            $paginacion['ultimo'] = '';
            $paginacion['siguiente'] = '';
        }
        
        $this->_paginacion = $paginacion;
		$this->_rangoPaginacion($paginacion);
        
        return $this->_datos;
    }
    
    private function _rangoPaginacion($limite = false)
    {
		$limite = ($limite && is_numeric($limite)) ? $limite : 10;
        
        $total_paginas = $this->_paginacion['total'];
        $pagina_seleccionada = $this->_paginacion['actual'];
        $rango = ceil($limite / 2);
        $paginas = array();
        
        $rango_derecho = $total_paginas - $pagina_seleccionada;
		$resto = ($rango_derecho < $rango) ? $rango - $rango_derecho : 0;
        $rango_izquierdo = $pagina_seleccionada - ($rango + $resto);
        
        for($i = $pagina_seleccionada; $i > $rango_izquierdo; $i--){
            if($i == 0){
                break;
            }
			
            $paginas[] = $i;
        }
        
        sort($paginas);
		$rango_derecho = ($pagina_seleccionada < $rango) ? $limite : $pagina_seleccionada + $rango;
        
        for($i = $pagina_seleccionada + 1; $i <= $rango_derecho; $i++){
            if($i > $total_paginas){
                break;
            }
            
            $paginas[] = $i;
        }
        
        $this->_paginacion['rango'] = $paginas;
        
        return $this->_paginacion;
        
    }

	public function getView($vista, $link = false)
	{
		$rutaView = RAIZ . 'views' . DS . 'paginador' . DS . $vista . '.php';
		
		if($link)
		$link = $this->_conf['base_url'] . $link . '/';
		
		if(is_readable($rutaView)){
			
			ob_start();
			include $rutaView;
			$contenido = ob_get_contents();
			ob_end_clean();
			
			return $contenido;
		}
		
		throw new Exception('Error de paginacion');		
	}
}