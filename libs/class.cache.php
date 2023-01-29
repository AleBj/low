<?php
 
use \Exception; 

class cache
{
	public $_data;
	
	public function __construct()
	{
		$this->_data = array();	
	}
	
	// Esta funcion comprueba si existe el directorio
	// adecuado para cargar el elemento. Si no es así,
	// lo crea con su albol de dependencia.
	public function gestionDirectoriosCache($_ruta_cache, $_fecha)
	{
		// Primer paso, dividimos la fecha para explorar
		// si existen los directorios:
		//	- año
		//	- mes
		//  - fecha
		// en forma anidada
		// Si no esxisten, se crea.
		$_directorios = explode('-',$_fecha);
		
		// Primero, si existe el directorio
		// referente al año
		if(!file_exists($_ruta_cache . $_directorios[0])){
			mkdir($_ruta_cache . $_directorios[0], 0777);
		}
			
		// chequeamos el mes, sino creamos
		if(!file_exists($_ruta_cache . $_directorios[0] . '/' . $_directorios[1])){
			mkdir($_ruta_cache . $_directorios[0] . '/' . $_directorios[1], 0777);
		}
		
		// y por ultimo, el dia
		if(!file_exists($_ruta_cache . $_directorios[0] . '/' . $_directorios[1] . '/' . $_directorios[2])){
			mkdir($_ruta_cache . $_directorios[0] . '/' . $_directorios[1] . '/' . $_directorios[2], 0777);
		}
		
		// Por ultimo, devolvemos la ruta del directorio de la noticia
		return $_ruta_cache . $_directorios[0] . '/' . $_directorios[1] . '/' . $_directorios[2] . '/';
	}
	public function combertirCacheMultiple(array $identificadores,$urlBase,$rutaCache)
	{
		// chekeamos que los identificadores vengan como un array
		// y que este array tengan elementos
		if(is_array($identificadores) && count($identificadores)){
			
			// loopeamos los elementos.
			foreach($identificadores as $archivos){
				
				// Creamos la ruta para la busqueda
				$_directorios = $this->cambiarSeparador($archivos->fecha,'-','/') . '/';
				
				if(is_readable($rutaCache . $_directorios . $archivos->identificador . '.txt')){
					
					// Ahora chekamos que el archivo no este vacio.
					if(filesize($rutaCache .  $_directorios . $archivos->identificador . '.txt') > 0){
						
						$contenido = file_get_contents($urlBase . $rutaCache .  $_directorios . $archivos->identificador . '.txt',FILE_USE_INCLUDE_PATH);
						$base_64 = base64_decode($contenido);
						$this->_data[] = unserialize($base_64);	
						
					}else{
						throw new Exception('el archivo ' . $archivos->identificador . '.txt' . ' esta vacio. Buscar en base de datos!');	
					}
				}else{
					throw new Exception('el archivo ' . $archivos->identificador . '.txt' . ' no existe o esta corrupto. Buscar en base de datos!');		
				}
			}
		}else{
			//throw new Exception('No hay elementos para el cache: aqui se debe buscar los datos en la base');	
		}	
		return $this->_data;
	}
	
	public function combertirCacheMultipleSlider(array $identificadores,$urlBase,$rutaCache)
	{
		// chekeamos que los identificadores vengan como un array
		// y que este array tengan elementos
		if(is_array($identificadores) && count($identificadores)){
			
			// loopeamos los elementos.
			foreach($identificadores as $archivos){
				
				if(is_readable($rutaCache . $archivos->identificador . '.txt')){
					
					// Ahora chekamos que el archivo no este vacio.
					if(filesize($rutaCache . $archivos->identificador . '.txt') > 0){
						
						$contenido = file_get_contents($urlBase . $rutaCache . $archivos->identificador . '.txt',FILE_USE_INCLUDE_PATH);
						$base_64 = base64_decode($contenido);
						$this->_data[] = unserialize($base_64);	
						
					}else{
						throw new Exception('el archivo ' . $archivos->identificador . '.txt' . ' esta vacio. Buscar en base de datos!');	
					}
				}else{
					throw new Exception('el archivo ' . $archivos->identificador . '.txt' . ' no existe o esta corrupto. Buscar en base de datos!');		
				}
			}
		}else{
			//throw new Exception('No hay elementos para el cache: aqui se debe buscar los datos en la base');	
		}	
		return $this->_data;
	}
	public static function procesarCache($_carpeta){
		if($_carpeta){
			$_dir = scandir($_carpeta); 
			foreach($_dir as $archivos){ 
				if($archivos!='.' && $archivos!='..'){
					if(is_dir($_carpeta.'/'.$archivos)){
						borrarDir($_carpeta.'/'.$archivos);
					}else{
						unlink($_carpeta.'/'.$archivos);
					}
				}
			}
			rmdir($_carpeta);
		}else{
			throw new Exception('no se proceso el cache');	
		}
	}
	public function combertirCacheSimple($identificador,$_fecha,$urlBase,$rutaCache)
	{
		$_directorios = $this->cambiarSeparador($_fecha,'-','/') . '/';
		
		if(is_readable($rutaCache . $_directorios . $identificador . '.txt')){
			if(filesize($rutaCache . $_directorios . $identificador . '.txt') > 0){
				
				$contenido = file_get_contents($urlBase . $rutaCache . $_directorios . $identificador . '.txt',FILE_USE_INCLUDE_PATH);
				$base_64 = base64_decode($contenido);
				$_data = unserialize($base_64);	
				
			}else{
				throw new Exception('el archivo ' . $identificador . '.txt' . ' esta vacio. Buscar en base de datos!');		
			}
		}else{
			throw new Exception('el archivo ' . $identificador . '.txt' . ' no existe o esta corrupto. Buscar en base de datos!');		
		}
		return $_data;
	}
	public function combertirCacheSimpleSlider($identificador,$urlBase,$rutaCache)
	{		
		if(is_readable($rutaCache . $identificador . '.txt')){
			if(filesize($rutaCache . $identificador . '.txt') > 0){
				
				$contenido = file_get_contents($urlBase . $rutaCache . $identificador . '.txt',FILE_USE_INCLUDE_PATH);
				$base_64 = base64_decode($contenido);
				$_data = unserialize($base_64);	
				
			}else{
				throw new Exception('el archivo ' . $identificador . '.txt' . ' esta vacio. Buscar en base de datos!');		
			}
		}else{
			throw new Exception('el archivo ' . $identificador . '.txt' . ' no existe o esta corrupto. Buscar en base de datos!');		
		}
		return $_data;
	}
	// Funcion VOID: No devuelve nada.
	public function crearCacheNoticias($id, $rutaCache, $_id_usuario)
	{
		$matriz = contenidos_noticia::find(array('conditions' => array('id = ?', $id)));
		$contenedor = array();
		$cache = array();
		$_autor = array();
		
		// Categoria
		$_categoria = contenidos_categoria::find(array('conditions' => array('id = ?', $matriz->categoria_id)));
		$_categoria = $_categoria->valor;
		
		// Palabras Clave
		$palabras_clave_ids = explode(',', $matriz->palabras_clave);
		$palabras_clave = array();
		
		foreach($palabras_clave_ids as $id){
			$palabra = contenidos_palabras_clave::find(array('conditions' => array('id = ?', $id)));
			$palabras_clave[] = trim($palabra->valor);
		}
		
		// Imagenes
		if(!empty($matriz->imagenes)){
			$imagenes_ids = explode(',',$matriz->imagenes);
			$imagenes = array();
			
			foreach($imagenes_ids as $ids){
				$imagen = contenidos_imagene::find(array('conditions' => array('id = ?', $ids)));
				$imagenes[] = $imagen->path;
			}
		}else{
			$imagenes = '';	
		}
		
		// Videos
		if(!empty($matriz->videos)){
			$videos_ids = explode(',',$matriz->videos);
			$videos = array();
			
			foreach($videos_ids as $idss){
				$video = contenidos_video::find(array('conditions' => array('id = ?', $idss)));
				$videos[] = array(
								'id' => $video->id,
								'path' => $video->path, 
								'origen_id' => $video->origen_id
							);
			}
		}else{
			$videos = '';	
		}
		
		// Autor
		$_traer_autor = autore::find(array('conditions' => array('usuario_id = ?', $_id_usuario)));
		$_imagen_autor = autores_imagene::find(array('conditions' => array('usuario_id = ?', $_id_usuario)));
		
		$_autor['usuario_id'] 			= $_traer_autor->usuario_id;
		$_autor['nombre_apellido']		= $_traer_autor->nombre_apellido;
		$_autor['imagen_id']			= $_imagen_autor->path;
		$_autor['biografia']			= $_traer_autor->biografia;
		$_autor['red_social']			= $_traer_autor->red_social;
		$cache['id'] 					= $matriz->id;
		$cache['categoria'] 			= $_categoria;
		$cache['categoria_id'] 			= $matriz->categoria_id;
		$cache['idioma_id'] 			= $matriz->idioma_id;
		$cache['copete'] 				= $matriz->copete;
		$cache['url']					= $this->crearTitulo($matriz->titulo_largo);
		$cache['titulo_largo'] 			= $matriz->titulo_largo;
		$cache['titulo_corto'] 			= $matriz->titulo_corto;
		$cache['bajada_larga'] 			= $matriz->bajada_larga;
		$cache['bajada_corta'] 			= $matriz->bajada_corta;
		$cache['contenido'] 			= $matriz->contenido;
		$cache['fuentes'] 				= $matriz->fuentes;
		$cache['meta_descripcion'] 		= $matriz->meta_descripcion;
		$cache['palabras_clave_ids']	= $matriz->palabras_clave;
		$cache['palabras_clave'] 		= $palabras_clave;
		$cache['imagenes'] 				= $imagenes;
		$cache['imagenes_ids']			= $matriz->imagenes;
		$cache['videos'] 				= $videos;
		$cache['orden_multimedia'] 		= $matriz->orden_multimedia;
		$cache['mostrar_autor']			= $matriz->mostrar_autor;
		$cache['fecha'] 				= $matriz->fecha;
		$cache['hora'] 					= $matriz->hora;
		$cache['identificador'] 		= $matriz->identificador;
		$cache['version'] 				= $matriz->version;
		$cache['autor']					= $_autor;
		
		$contenedor[] = $cache;
		
		// Si el archivo es legible, entonces EXISTE, 
		// por lo tanto es una version posterior de la noticia
		// La version actual la ubicamos siempre por defecto
		// como valor 0 del arreglo.
		
		// Creamos rutas
		$rutaCache = $this->gestionDirectoriosCache($rutaCache, $matriz->fecha);
		
		if(is_readable($rutaCache . $matriz->identificador . '.txt')){
			
			// Primero, tomamos el archivo
			$_contenido = file_get_contents($rutaCache . $matriz->identificador . '.txt',FILE_USE_INCLUDE_PATH);
			
			// Lo desencriptamos
			$base_64 = base64_decode($_contenido);
			
			// Y lo deserializamos
			$_data = unserialize($base_64);
			
			// $_data es un array, le agregamos la nueva
			// version de la noticia al principio.
			array_unshift($_data,$cache);
			
			// y volvemos encriptar los datos 
			$serializar = serialize($_data);
			// Y lo encriptamos en md64
			$codificar = base64_encode($serializar);
		}else{
			// Serielizamos el archivo
			$serializar = serialize($contenedor);
			// Y lo encriptamos en md64
			$codificar = base64_encode($serializar);
			
			// Luego creamos el archivo final y lo escribimos.
			// ATENCION: si el archi existe, se sobrescribe.
			fopen($rutaCache . $matriz->identificador . '.txt',"w+");
		}
		if(is_readable($rutaCache . $matriz->identificador . '.txt')){
			file_put_contents($rutaCache . $matriz->identificador . '.txt', $codificar);
		}else{
			throw new Exception('No se pudo crear Cache de elemento');	
		}
	}
	
	public function crearCacheSlider($id, $rutaCache)
	{
		$matriz = contenidos_slider::find(array('conditions' => array('id = ?', $id)));
		$contenedor = array();
		$cache = array();
		
		// Elementos
		if(!empty($matriz->id_elementos)){
			$imagenes_ids = explode(',',$matriz->id_elementos);
			$imagenes = array();
			
			foreach($imagenes_ids as $ids){
				$element = contenidos_sliders_elemento::find(array('conditions' => array('id = ?', $ids)));
				$imagenes[] = array(
								'id' => $element->id,
								'identificador' => $element->identificador,
								'path' => $element->path,
								'descripcion' => $element->descripcion,
								'titulo' => $element->titulo,
								'link' => $element->link,
								'cliente' => $element->cliente_id,
								'orden' => $element->orden,
				);
			}
		}else{
			$imagenes = '';	
		}
		
		// Tipo
		$_tipo = contenidos_slider_tipo::find(array('conditions' => array('id = ?', $matriz->tipo)));
		$_tipo = array(
					'id' => $_tipo->id,
					'valor' => $_tipo->valor
				);
		
		$cache['id'] 					= $matriz->id;
		$cache['elementos'] 			= $imagenes;
		$cache['nombre'] 				= $matriz->nombre;
		$cache['tipo'] 					= $_tipo;
		$cache['alta'] 					= $matriz->alta;
		$cache['identificador'] 		= $matriz->identificador;
		
		$contenedor[] = $cache;
		
		// Si el archivo es legible, entonces EXISTE, 
		// por lo tanto es una version posterior de la noticia
		// La version actual la ubicamos siempre por defecto
		// como valor 0 del arreglo.
		if(is_readable($rutaCache . $matriz->identificador . '.txt')){
			
			// Primero, tomamos el archivo
			$_contenido = file_get_contents($rutaCache . $matriz->identificador . '.txt',FILE_USE_INCLUDE_PATH);
			
			// Lo desencriptamos
			$base_64 = base64_decode($_contenido);
			
			// Y lo deserializamos
			$_data = unserialize($base_64);
			
			// $_data es un array, le agregamos la nueva
			// version de la noticia al principio.
			array_unshift($_data,$cache);
			
			// y volvemos encriptar los datos 
			$serializar = serialize($_data);
			// Y lo encriptamos en md64
			$codificar = base64_encode($serializar);
		}else{
			// Serielizamos el archivo
			$serializar = serialize($contenedor);
			// Y lo encriptamos en md64
			$codificar = base64_encode($serializar);
			
			// Luego creamos el archivo final y lo escribimos.
			// ATENCION: si el archi existe, se sobrescribe.
			fopen($rutaCache . $matriz->identificador . '.txt',"w+");
		}
		if(is_readable($rutaCache . $matriz->identificador . '.txt')){
			file_put_contents($rutaCache . $matriz->identificador . '.txt', $codificar);
		}else{
			throw new Exception('No se pudo crear Cache de elemento');	
		}
	}
	
	public function crearTitulo($_titulo)
	{
		$_titulo = strtolower($_titulo);
		return str_replace(" ","-",$_titulo);
	}
	
	public function cambiarSeparador($_elemento,$_separador,$_nuevo_separador)
	{
		$_separar = explode("$_separador", $_elemento);
		$_juntar = implode($_separar,"$_nuevo_separador");
		return $_juntar;
	}
	
	//
	///
	////
	/////
	//////
	//	Cache para Home
	//////
	/////
	////
	///
	//
	
	public function crearCacheHome($urlBase, $rutaCache, $estado)
	{	
		// definimos el arreglo que contendra los datos finales
		$_data_noticias = array();
		
		// Lo primero, es traer la estructura de la página.
		// Gestionamos los resultados
		$_bloques = $this->gestionarBloques(espacios_bloque::all());
		
		// Y lo metemos en las noticias con valor 0 -siempre debe ser 0 - 
		$_data_noticias[] = $_bloques;
		
		// Luego, traemos todos los elementos 
		$_espacios = espacios_definicione::all(array('conditions' => array('estado = ?', 1))); 
		
		// Ahora, por cada elemento, buscamos sus datos y lo cargamos en un array general
		foreach($_espacios as $_elementos){
			
			// Si es slide, buscamos el cache en su carpeta
			if($_elementos->espacio_tipo == 1){
				
				// Buscamos el slider correpondiente
				$_slider = contenidos_slider::find(array('select' => 'identificador', 'conditions' => array('id = ?', $_elementos->elemento_id)));
				
				// Y traemos el cache correspondiente:
				if(is_readable($rutaCache['slider'] . $_slider->identificador . '.txt')){
					if(filesize($rutaCache['slider'] . $_slider->identificador . '.txt') > 0){
						$contenido = file_get_contents($urlBase . $rutaCache['slider'] . $_slider->identificador . '.txt',FILE_USE_INCLUDE_PATH);
						$base_64 = base64_decode($contenido);
						$_data_slider = unserialize($base_64);	
					}
				}
				
				// Ahora, colocamos la ultima version de ese
				// slide en el arreglo final
				$_data_noticias[$_elementos->espacio] = $_data_slider[0];
				
			}else{
				
				// Buscamos la noticia correpondiente
				$_noticia = contenidos_noticia::find(array('select' => 'fecha, identificador', 'conditions' => array('id = ?', $_elementos->elemento_id)));
				
				// Si se crea la version On line, se actualizan los 
				// estados de las noticias comprometidas.
				if($estado == 'home_online'){
					$_estado = contenidos_noticia::find($_elementos->elemento_id);
					$_estado->estado = 'activo';
					$_estado->save();
				}
				
				// Tranformamos la fecha
				$_directorios = $this->cambiarSeparador($_noticia->fecha,'-','/') . '/';
				
				// Y traemos el cache correspondiente:
				if(is_readable($rutaCache['noticias'] . $_directorios . $_noticia->identificador . '.txt')){
					if(filesize($rutaCache['noticias'] . $_directorios . $_noticia->identificador . '.txt') > 0){
						
						$contenido = file_get_contents($urlBase . $rutaCache['noticias'] . $_directorios . $_noticia->identificador . '.txt',FILE_USE_INCLUDE_PATH);
						$base_64 = base64_decode($contenido);
						$_data_noticia = unserialize($base_64);	
					}
				}
				
				// Ahora, colocamos la ultima version de ese
				// slide en el arreglo final
				$_data_noticias[$_elementos->espacio] = $_data_noticia[0];
			}
			
			// Por ultimo, creamos el cache de la home
			// Serielizamos el archivo
			$serializar = serialize($_data_noticias);
			// Y lo encriptamos en md64
			$codificar = base64_encode($serializar);
			
			// Luego creamos el archivo final y lo escribimos.
			// ATENCION: si el archi existe, se sobrescribe.
			fopen($rutaCache['home'] . $estado . '.txt',"w+");
		
			if(is_readable($rutaCache['home'] . $estado . '.txt')){
				file_put_contents($rutaCache['home'] . $estado . '.txt', $codificar);
			}else{
				throw new Exception('No se pudo crear Cache de elemento');	
			}	
		}
	}
	
	public function gestionarBloques(array $_data)
	{
		$_devolver = array();
		
		if(is_array($_data)){
			foreach($_data as $dat){
				$_devolver[$dat->bloque] = $dat->opcion;
			}
		}else
			throw new Exception('ERROR al Gestionar BLOQUES');
		return $_devolver;
	}
	
	public function combertirCacheHome($urlBase,$rutaCache, $_espacio)
	{		
		if(is_readable($rutaCache . $_espacio . '.txt')){
			if(filesize($rutaCache . $_espacio . '.txt') > 0){
				
				$contenido = file_get_contents($urlBase . $rutaCache . $_espacio . '.txt',FILE_USE_INCLUDE_PATH);
				$base_64 = base64_decode($contenido);
				$_data = unserialize($base_64);	
				
			}else{
				throw new Exception('el archivo ' . $rutaCache . $_espacio . '.txt' . ' esta vacio. Buscar en base de datos!');		
			}
		}else{
			throw new Exception('el archivo ' . $rutaCache . $_espacio . '.txt' . ' no existe o esta corrupto. Buscar en base de datos!');		
		}
		return $_data;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}