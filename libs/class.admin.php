<?php
use Nucleo\Pd\Pd;
use Nucleo\Registro\Registro;
use \Exception as EX;

class admin
{
	
	public function armarArray($_val)
	{
		$arrayResult = array_map(function($res){
		  return $res->attributes();
		}, $_val);
		return $arrayResult;
	}

	public function traerFaqs()
	{
		$_lanz = Pd::instancia()->prepare("SELECT cpf.* FROM `contenidos_faqs` as cpf ORDER BY cpf.id DESC");
		$_lanz->execute();
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}

	public function traerFaq($_id)
	{
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cpf.* FROM `contenidos_faqs` as cpf WHERE cpf.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function borrarFaqs($_id)
	{		
		$_id = (int) $_id;		
		$borrar = contenidos_faq::find($_id);	
		if($borrar){
			$borrar->delete();
			return true;
		}else{
			return false;
		}

	}

	public function traerBuscadorFaqs($_valor)
	{
		$result = Pd::instancia()->prepare("SELECT cpf.* FROM `contenidos_faqs` as cpf WHERE cpf.pregunta LIKE '%".$_valor."%' ORDER BY cpf.id DESC");
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
		
	}



	public function traerNoticias()
	{
		$_lanz = Pd::instancia()->prepare("SELECT cn.* FROM `contenidos_noticias` as cn ORDER BY cn.id DESC");
		$_lanz->execute();
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}

	public function traerNoticia($_id)
	{
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cn.* FROM `contenidos_noticias` as cn WHERE cn.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerBuscadorNoticias($_valor)
	{
		$result = Pd::instancia()->prepare("SELECT cn.* FROM `contenidos_noticias` as cn WHERE cn.titulo LIKE '%".$_valor."%' ORDER BY cn.id DESC");
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
		
	}

	public function borrarNoticias($_id, $_ruta, $_tipo)
	{		
		
		$borrar = contenidos_noticia::find($_id);	
		$imgBorrar = contenidos_imagene::find('all',array('conditions' => array('identificador = ?', $borrar->identificador)));
		if($imgBorrar && is_array($imgBorrar)){
			foreach($imgBorrar as $idss){
				$dat_por_identificador[] = $idss->id;
			}		
			$this->eliminarImagenes($dat_por_identificador, $_ruta, $_tipo);
		}		

		$borrar->delete();

		return true;

	}

	/*SLIDER*/

	public function traerSliders()
	{
		$_lanz = Pd::instancia()->prepare("SELECT cep.* FROM `contenidos_elementos_portada` as cep ORDER BY cep.id DESC");
		$_lanz->execute();
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}

	public function traerSlidersPorPos($_estado)
	{
		$_lanz = Pd::instancia()->prepare("SELECT cep.* FROM `contenidos_elementos_portada` as cep WHERE cep.estado = :estado ORDER BY cep.posicion ASC");
		$_lanz->execute(array(":estado" => $_estado));
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}

	public function traerSlider($_id)
	{
		$result = Pd::instancia()->prepare("SELECT cep.* FROM `contenidos_elementos_portada` as cep WHERE cep.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}
	
	
	public function borrarSlider($_id, $_ruta, $_tipo)
	{		
		$borrar = contenidos_elementos_portad::find($_id);	
		//$this->ordenarProductos($borrar->orden);				
		$imgBorrar = contenidos_imagene::find('all',array('conditions' => array('identificador = ?', $borrar->identificador)));
		if($imgBorrar && is_array($imgBorrar)){
			foreach($imgBorrar as $_img){
				$this->eliminarImagenes2($_img->path, $_ruta, $_tipo);
				$_img->delete();
			}
		}
		$borrar->delete();

		return true;
	}
	
	public function traerSliderBuscador($_valor)
	{
		$result = Pd::instancia()->prepare("SELECT cep.* FROM `contenidos_elementos_portada` as cep WHERE cep.titulo LIKE '%".$_valor."%' ORDER BY cep.id DESC");
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
		
	}


	public function OrdenSliderPrincipal($_ids)
	{
		$_ids = explode(',', $_ids);	

		$_sliders = $this->traerSliders();	
		// $_cont = 0;
		if(is_array($_ids)){

			for($a=0;$a<count($_ids);$a++){

				$_asig = contenidos_elementos_portad::find($_ids[$a]);				
				$_asig->posicion = ($a+1);
				$_asig->estado = 1;				
				$_asig->save();
			}
		}

		if(is_array($_sliders)){	


			for($a=0;$a<count($_sliders);$a++){

				// if($_ids[$a] == $_sliders[$a]['id']){
				if(!in_array($_sliders[$a]['id'], $_ids)){
					$_asig = contenidos_elementos_portad::find($_sliders[$a]['id']);				
					$_asig->posicion = 0;
					$_asig->estado = 2;				
					$_asig->save();

				}
				
			}
		}		
		
		return true;
	}


	public function traerDestacados()
	{
		$result = Pd::instancia()->prepare("SELECT cd.* FROM `contenidos_destacados` as cd ORDER BY cd.posicion ASC");
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function armarDestacado($_data)
	{	
		$result = Pd::instancia()->prepare("TRUNCATE TABLE `contenidos_destacados`");
		$result->execute();
		foreach ($_data as $key => $val) {
			if($val!=''){
				$_arr = explode('_', $val);
				// $_tabla = 'contenidos_'.$_arr[0];
				$_id_prod = $_arr[1];
				$_pos = $key;
				$_date = date('Y-m-d');
				
				$result = Pd::instancia()->prepare("INSERT INTO `contenidos_destacados` (id_producto,posicion,fecha) VALUES  (:id_prod, :posicion, :fecha)");
				$result->execute(array(":id_prod" => $_id_prod, ":posicion" => $_pos, ":fecha" => $_date));
			}			
		
		}
		
		return ($result) ? true : false;
	}


	/*OUR BESTSELLERS*/ 

	public function traerOurbestsellers()
	{
		$result = Pd::instancia()->prepare("SELECT co.* FROM `contenidos_ourbestsellers` as co ORDER BY co.posicion ASC");
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}


	public function armarOurbestseller($_data)
	{	
		$result = Pd::instancia()->prepare("TRUNCATE TABLE `contenidos_ourbestsellers`");
		$result->execute();
		foreach ($_data as $key => $val) {
			if($val!=''){
				// $_arr = explode('_', $val);
				$_id_prod = $val;
				$_pos = $key;
				$_date = date('Y-m-d');
				
				$result = Pd::instancia()->prepare("INSERT INTO `contenidos_ourbestsellers` (id_producto,posicion,fecha) VALUES  (:id_prod, :posicion, :fecha)");
				$result->execute(array(":id_prod" => $_id_prod, ":posicion" => $_pos, ":fecha" => $_date));
			}			
		
		}
		
		return ($result) ? true : false;
	}


	/*TESTIMONIOS*/

	public function traerTestimonios()
	{
		$result = Pd::instancia()->prepare("SELECT ct.* FROM `contenidos_testimonios` as ct ORDER BY ct.fecha DESC");
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function cambiarEstadoTestimonio($_id, $_val)
	{
		$_estado = contenidos_testimonio::find(array('conditions' => array('id = ?', $_id)));
		$_estado->estado = $_val;
		$_estado->save();
	}

	public function borrarTestimonio($_id, $_ruta, $_tipo)
	{		
		
		$borrar = contenidos_testimonio::find($_id);	
		$imgBorrar = contenidos_imagene::find('all',array('conditions' => array('identificador = ?', $borrar->identificador)));
		if($imgBorrar && is_array($imgBorrar)){
			foreach($imgBorrar as $idss){
				$dat_por_identificador[] = $idss->id;
			}		
			$this->eliminarImagenes($dat_por_identificador, $_ruta, $_tipo);
		}		

		$borrar->delete();

		return true;

	}



	/*CURSOS*/

	public function traerTodosCursos()
	{
		$_data = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_cursos` as cp ORDER BY cp.id DESC");
		$_data->execute();
		$_data = $_data->fetchAll(PDO::FETCH_ASSOC);

		if($_data){

			for ($i=0; $i < count($_data); $i++) { 
				$_imgs = Pd::instancia()->prepare("SELECT ci.* FROM `contenidos_imagenes` as ci WHERE ci.identificador = :identificador ORDER BY ci.orden ASC");
				$_imgs->execute(array(':identificador' => $_data[$i]['identificador']));
				$_imgs = $_imgs->fetchAll(PDO::FETCH_ASSOC);
				$_arrayImg=array();
				foreach ($_imgs as $val) {
					$_arrayImg[]=$val['path'];
				}
				$_data[$i]['imagenes'] = implode(';', $_arrayImg);
			}
			

		}	



		return ($_data) ? $_data : false;
	}

	public function traerCursos($_estado)
	{
		$_lanz = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_cursos` as cp WHERE cp.estado = :estado ORDER BY cp.id DESC");
		$_lanz->execute(array(":estado" => $_estado));
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}
	

	public function traerCurso($_id)
	{
		$result = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_cursos` as cp WHERE cp.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public static function traerCursoStatic($_id)
	{
		$result = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_cursos` as cp WHERE cp.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}
	
	/*public function traerProductos($_estado)
	{
		return  contenidos_producto::find('all',array('conditions' => array('estado = ?', $_estado),'order' => 'id desc'));
			
	}
	
	public function traerProducto($_id)
	{
		return contenidos_producto::find($_id);
	}*/
	
	public function borrarCurso($_id, $_ruta, $_tipo)
	{		
		$borrar = contenidos_curso::find($_id);	
		//$this->ordenarProductos($borrar->orden);				
		$imgBorrar = contenidos_imagene::find('all',array('conditions' => array('identificador = ?', $borrar->identificador)));
		if($imgBorrar && is_array($imgBorrar)){
			foreach($imgBorrar as $idss){
				$dat_por_identificador[] = $idss->id;
			}		
			$this->eliminarImagenes($dat_por_identificador, $_ruta, $_tipo);
		}
		$borrar->delete();

		return true;
	}

	/*public function traerProdAltaBuscador($_valor, $_estado)
	{
		$_valor = htmlentities($_valor);
		return contenidos_user::find_by_sql('SELECT * FROM contenidos_cursos WHERE estado = '.$_estado.' AND titulo LIKE "%'.$_valor.'%" ORDER BY id DESC');
		
	}*/

	public function traerCursoBuscador($_valor, $_estado)
	{
		$result = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_cursos` as cp WHERE cp.nombre LIKE '%".$_valor."%' AND cp.estado = :estado ORDER BY cp.id DESC");
		$result->execute(array(":estado" => $_estado));
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
		
	}


	/* AOBUT US*/


	public function traerTodosAboutus()
	{
		$result = Pd::instancia()->prepare("SELECT ca.* FROM `contenidos_aboutus` as ca ORDER BY ca.id DESC");
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerAboutus($_id)
	{
		$result = Pd::instancia()->prepare("SELECT ca.* FROM `contenidos_aboutus` as ca WHERE ca.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function borrarAboutus($_id, $_ruta, $_tipo)
	{		
		
		$borrar = contenidos_aboutu::find($_id);	
		$imgBorrar = contenidos_imagene::find('all',array('conditions' => array('identificador = ?', $borrar->identificador)));
		if($imgBorrar && is_array($imgBorrar)){
			foreach($imgBorrar as $idss){
				$dat_por_identificador[] = $idss->id;
			}		
			$this->eliminarImagenes($dat_por_identificador, $_ruta, $_tipo);
		}		

		$borrar->delete();

		return true;

	}


	public function traerAboutusBuscador($_valor)
	{
		$result = Pd::instancia()->prepare("SELECT ca.* FROM `contenidos_aboutus` as ca WHERE ca.titulo LIKE '%".$_valor."%' ORDER BY cp.id DESC");
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
		
	}








	////////////////////////////////////////////////////////////////////



	public static function traerCategoriasStatic()
	{
		$_lanz = Pd::instancia()->prepare("SELECT cc.* FROM `contenidos_cursos_categorias` as cc");
		$_lanz->execute();
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}

	public static function traerCategoriaStatic($_id)
	{
		$result = Pd::instancia()->prepare("SELECT cc.* FROM `contenidos_cursos_categorias` as cc WHERE cc.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerCategorias()
	{
		$_lanz = Pd::instancia()->prepare("SELECT cc.* FROM `contenidos_cursos_categorias` as cc ORDER BY cc.id DESC");
		$_lanz->execute();
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}

	public function traerCategoria($_id)
	{
		$result = Pd::instancia()->prepare("SELECT cc.* FROM `contenidos_cursos_categorias` as cc WHERE cc.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerBuscadorCategoria($_valor)
	{
		$result = Pd::instancia()->prepare("SELECT cc.* FROM `contenidos_cursos_categorias` as cc WHERE cc.nombre LIKE '%".$_valor."%' ORDER BY cc.id DESC");
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
		
	}

	public function borrarCategoria($_id)
	{		
		
		$borrar = contenidos_cursos_categoria::find($_id);	
		$borrar->delete();

		return true;

	}

	public function borrarCategorias($_id, $_ruta, $_tipo)
	{		
		
		$borrar = contenidos_categoria::find($_id);	
		$imgBorrar = contenidos_imagene::find('all',array('conditions' => array('identificador = ?', $borrar->identificador)));
		if($imgBorrar && is_array($imgBorrar)){
			foreach($imgBorrar as $idss){
				$dat_por_identificador[] = $idss->id;
			}		
			$this->eliminarImagenes($dat_por_identificador, $_ruta, $_tipo);
		}

		$borrar_sub = contenidos_subcategoria::find('all',array('conditions' => array('id_cat = ?', $borrar->id)));		
		if($borrar_sub){
			$imgBorrarSub = contenidos_imagene::find('all',array('conditions' => array('identificador = ?', $borrar_sub->identificador)));
			if($imgBorrarSub && is_array($imgBorrarSub)){
				foreach($imgBorrarSub as $idssub){
					$dat_por_identificador_sub[] = $idssub->id;
				}		
				$this->eliminarImagenes($dat_por_identificador_sub, $_ruta, 'subcategorias');
			}

			if($borrar_sub){
				foreach($borrar_sub as $_sub){
					$_sub->delete();
				}		
			}
		}


		$borrar->delete();

		return true;


		/*$borrar = contenidos_categoria::find($_id);	
		//$this->borrarSubcategorias($borrar->orden);				
		$imgBorrar = contenidos_imagene::find('all',array('conditions' => array('identificador = ?', $borrar->identificador)));
		if($imgBorrar && is_array($imgBorrar)){
			foreach($imgBorrar as $idss){
				$dat_por_identificador[] = $idss->id;
			}		
			$this->eliminarImagenes($dat_por_identificador, $_ruta, $_tipo);
		}
		$borrar->delete();
				*/
	}


	public static function crearItems($_titulo)
	{		
		
		$a = array('Ã€', 'Ã�', 'Ã‚', 'Ãƒ', 'Ã„', 'Ã…', 'Ã†', 'Ã‡', 'Ãˆ', 'Ã‰', 'ÃŠ', 'Ã‹', 'ÃŒ', 'Ã�', 'ÃŽ', 'Ã�', 'Ã�', 'Ã‘', 'Ã’', 'Ã“', 'Ã”', 'Ã•', 'Ã–', 'Ã˜', 'Ã™', 'Ãš', 'Ã›', 'Ãœ', 'Ã�', 'ÃŸ', 'Ã ', 'Ã¡', 'Ã¢', 'Ã£', 'Ã¤', 'Ã¥', 'Ã¦', 'Ã§', 'Ã¨', 'Ã©', 'Ãª', 'Ã«', 'Ã¬', 'Ã­', 'Ã®', 'Ã¯', 'Ã±', 'Ã²', 'Ã³', 'Ã´', 'Ãµ', 'Ã¶', 'Ã¸', 'Ã¹', 'Ãº', 'Ã»', 'Ã¼', 'Ã½', 'Ã¿', 'Ä€', 'Ä�', 'Ä‚', 'Äƒ', 'Ä„', 'Ä…', 'Ä†', 'Ä‡', 'Äˆ', 'Ä‰', 'ÄŠ', 'Ä‹', 'ÄŒ', 'Ä�', 'ÄŽ', 'Ä�', 'Ä�', 'Ä‘', 'Ä’', 'Ä“', 'Ä”', 'Ä•', 'Ä–', 'Ä—', 'Ä˜', 'Ä™', 'Äš', 'Ä›', 'Äœ', 'Ä�', 'Äž', 'ÄŸ', 'Ä ', 'Ä¡', 'Ä¢', 'Ä£', 'Ä¤', 'Ä¥', 'Ä¦', 'Ä§', 'Ä¨', 'Ä©', 'Äª', 'Ä«', 'Ä¬', 'Ä­', 'Ä®', 'Ä¯', 'Ä°', 'Ä±', 'Ä²', 'Ä³', 'Ä´', 'Äµ', 'Ä¶', 'Ä·', 'Ä¹', 'Äº', 'Ä»', 'Ä¼', 'Ä½', 'Ä¾', 'Ä¿', 'Å€', 'Å�', 'Å‚', 'Åƒ', 'Å„', 'Å…', 'Å†', 'Å‡', 'Åˆ', 'Å‰', 'ÅŒ', 'Å�', 'ÅŽ', 'Å�', 'Å�', 'Å‘', 'Å’', 'Å“', 'Å”', 'Å•', 'Å–', 'Å—', 'Å˜', 'Å™', 'Åš', 'Å›', 'Åœ', 'Å�', 'Åž', 'ÅŸ', 'Å ', 'Å¡', 'Å¢', 'Å£', 'Å¤', 'Å¥', 'Å¦', 'Å§', 'Å¨', 'Å©', 'Åª', 'Å«', 'Å¬', 'Å­', 'Å®', 'Å¯', 'Å°', 'Å±', 'Å²', 'Å³', 'Å´', 'Åµ', 'Å¶', 'Å·', 'Å¸', 'Å¹', 'Åº', 'Å»', 'Å¼', 'Å½', 'Å¾', 'Å¿', 'Æ’', 'Æ ', 'Æ¡', 'Æ¯', 'Æ°', 'Ç�', 'ÇŽ', 'Ç�', 'Ç�', 'Ç‘', 'Ç’', 'Ç“', 'Ç”', 'Ç•', 'Ç–', 'Ç—', 'Ç˜', 'Ç™', 'Çš', 'Ç›', 'Çœ', 'Çº', 'Ç»', 'Ç¼', 'Ç½', 'Ç¾', 'Ç¿', 'ñ', 'Ñ', 'á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú');
	   	$b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', 'n', 'N', 'a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U');
	   	$_titulo = str_replace($a, $b, $_titulo);
	   	$_titulo = strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'), array('', '-', ''), $_titulo));

	   	return $_titulo;
	}





	public function traerSubcategorias()
	{
		$_lanz = Pd::instancia()->prepare("SELECT csc.* FROM `contenidos_subcategorias` as csc ORDER BY csc.id DESC");
		$_lanz->execute();
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}

	public static function traerSubcategoriasStatic($_id)
	{
		$result = Pd::instancia()->prepare("SELECT csc.* FROM `contenidos_subcategorias` as csc WHERE csc.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerSubcategoria($_id)
	{
		$result = Pd::instancia()->prepare("SELECT csc.* FROM `contenidos_subcategorias` as csc WHERE csc.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public static function traerSubcategoriasPorCat($_id)
	{
		$result = Pd::instancia()->prepare("SELECT csc.* FROM `contenidos_subcategorias` as csc WHERE csc.id_cat = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerBuscadorSubcategoria($_valor)
	{
		$result = Pd::instancia()->prepare("SELECT csc.* FROM `contenidos_subcategorias` as csc WHERE cc.nombre LIKE '%".$_valor."%' ORDER BY csc.id DESC");
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
		
	}

	public function borrarSubcategorias($_id, $_ruta, $_tipo)
	{		
		
		/*$borrar = contenidos_subcategoria::find($_id);	
		$borrar->delete();

		return true;*/


		$borrar = contenidos_subcategoria::find($_id);	
		//$this->ordenarProductos($borrar->orden);				
		$imgBorrar = contenidos_imagene::find('all',array('conditions' => array('identificador = ?', $borrar->identificador)));
		if($imgBorrar && is_array($imgBorrar)){
			foreach($imgBorrar as $idss){
				$dat_por_identificador[] = $idss->id;
			}		
			$this->eliminarImagenes($dat_por_identificador, $_ruta, $_tipo);
		}
		$borrar->delete();

		return true;
				
	}


	
	
	


	public static function traerArchivoExcelId($_identificador)
	{
		return  contenidos_archivo::find(array('conditions' => array('identificador = ?', $_identificador)));
	}


	public static function traerExcelId($_identificador)
	{
		return  contenidos_archivos_exce::find(array('conditions' => array('identificador = ?', $_identificador)));
	}

	public function subirExcel($_tipo, $_identificador, $_data, $_nombre, $_ruta)
	{
		//echo "<pre>";print_r($_FILES[$_data]);echo "</pre>";//exit;
		//echo $_FILES[$_data]['name'];exit;
		$_mode = 0777;
		$_nombre = htmlentities($_nombre, ENT_QUOTES);
		$_mime = $this->mimeContentType($_FILES[$_data]['name']);
		//$_nombreArchivo = $this->crearNombre($_nombre);
		$_nombreArchivo = self::cadenaAleatoriaSegura(15);
		if(!$_mime) throw new Exception("ERROR: Archivo Invalido");
		if($_mime[0] != $_tipo) throw new Exception("ERROR: Tipo de Archivo Incorrecto");
		
		$_temp  = $_FILES[$_data]['tmp_name'];
		if(!file_exists($_ruta)){
			mkdir($_ruta, 0777, true);
		}
		$_archivo = $_ruta . $_nombreArchivo . '.' . $_mime[0];
		/*echo "<pre>";print_r($_mime);echo "</pre>";//exit;
		echo $_archivo;*/
		$_vid_ant = self::traerExcelId($_identificador);
		if($_vid_ant){
			if(file_exists($_ruta. $_vid_ant->path)){
				@unlink($_ruta. $_vid_ant->path);
			}
		}
		
		if(file_exists($_archivo)){
			@unlink($_archivo);
			@copy($_temp, $_archivo);
			@chmod($_archivo, $_mode);
			$_vid = $this->cargarDataExcel($_nombreArchivo.'.'.$_mime[0], $_nombre,$_identificador);
			return $_vid;
		}else{
			//@copy($_temp, $_archivo);
			move_uploaded_file($_temp,$_archivo);
			@chmod($_archivo, $_mode);
			$_vid = $this->cargarDataExcel($_nombreArchivo.'.'.$_mime[0], $_nombre,$_identificador);
			return $_vid;
		}
		throw new Exception("ERROR: al intentar subir el archivo");
	}

	public function mimeContentType($filename) {
    	$_mime = array(
            'txt' 	=> 'text/plain',
            'htm' 	=> 'text/html',
            'html' 	=> 'text/html',
            'php' 	=> 'application/x-httpd-php',
            'css' 	=> 'text/css',
            'js' 	=> 'application/javascript',
            'json' 	=> 'application/json',
            'xml' 	=> 'application/xml',
            'swf' 	=> 'application/x-shockwave-flash',
            'flv' 	=> 'video/x-flv',
            'png' 	=> 'image/png',
            'jpe' 	=> 'image/jpeg',
            'jpeg' 	=> 'image/jpeg',
            'jpg' 	=> 'image/jpeg',
            'gif' 	=> 'image/gif',
            'bmp' 	=> 'image/bmp',
            'ico' 	=> 'image/vnd.microsoft.icon',
            'tiff' 	=> 'image/tiff',
            'tif' 	=> 'image/tiff',
            'svg' 	=> 'image/svg+xml',
            'svgz' 	=> 'image/svg+xml',
            'zip' 	=> 'application/zip',
            'rar' 	=> 'application/x-rar-compressed',
            'exe' 	=> 'application/x-msdownload',
            'msi' 	=> 'application/x-msdownload',
            'cab' 	=> 'application/vnd.ms-cab-compressed',
            'mp3' 	=> 'audio/mpeg', // audio/video
            'mp4' 	=> 'video/mp4',
            'qt' 	=> 'video/quicktime',
            'mov' 	=> 'video/quicktime', // <--
            'pdf' 	=> 'application/pdf',
            'psd' 	=> 'image/vnd.adobe.photoshop',
            'ai' 	=> 'application/postscript',
            'eps' 	=> 'application/postscript',
            'ps' 	=> 'application/postscript',
            'doc' 	=> 'application/msword',
            'rtf' 	=> 'application/rtf',
            'xls' 	=> 'application/vnd.ms-excel',
            'xlsx' 	=> 'application/vnd.ms-excel',
            'ppt' 	=> 'application/vnd.ms-powerpoint',
            'odt' 	=> 'application/vnd.oasis.opendocument.text',
            'ods' 	=> 'application/vnd.oasis.opendocument.spreadsheet',
        );
        $_name = explode('.', $filename);
        $ext = strtolower(array_pop($_name));
        return (array_key_exists($ext, $_mime)) ? array($ext, $_mime[$ext]) : false;
    }
	

	public function cargarDataExcel($_archivo, $_nombre, $_identificador)
	{
		$_nombre = htmlentities($_nombre, ENT_QUOTES);
		// $_vid = self::traerExcelId($_identificador);
		$_vid = contenidos_archivos_exce::find(array('conditions' => array('id = ?', 1)));
		$_fechaBd = date('Y-m-d');
		if($_vid){
			//$video = new contenidos_video();
			$_vid->nombre = $_nombre;
			//$_vid->identificador = $_identificador;
			$_vid->path = $_archivo;
			//$_vid->orientacion = '';
			//$_vid->orden = 0;
			//$_vid->fecha_alt = date('Y-m-d');
			return ($_vid->save()) ? $_vid->id : false;
		}else{
			$video = new contenidos_archivos_exce();
			$video->nombre = $_nombre;
			$video->identificador = $_identificador;
			$video->path = $_archivo;
			$video->orientacion = '';
			$video->orden = 0;
			$video->fecha_alt = "$_fechaBd";
			return ($video->save()) ? $video->id : false;
		}
		
		
	}

	public function traerDataExcel($_id)
	{
		return  contenidos_archivos_exce::find(array('conditions' => array('id = ?', $_id)));
	}


	public function traerTags()
	{
		$_lanz = Pd::instancia()->prepare("SELECT ct.* FROM `contenidos_tags` as ct");
		$_lanz->execute();
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}
	
	
	/*public static function ultimoOrdenProductos()
	{
		$orden = contenidos_producto::find(array('select' => 'max(orden) as orden'));
		if (isset($orden)){
			return $orden;
		}else{
			return 0;
		}
	}
	
	public function ordenarProductos($_orden)
	{
		$arrayOrden = contenidos_producto::find('all',array('conditions' => array('orden > ? AND estado = ?', $_orden, 1),'order' => 'orden asc'));
		for($i=0;$i<count($arrayOrden);$i++){
			$arrayOrden[$i]->orden = $arrayOrden[$i]->orden-1;
			$arrayOrden[$i]->save();		
		}
	}
	
	public function cambiarOrdenProductos($_id, $_orden)
	{			
		$act = contenidos_producto::find(array('conditions' => array('id = ?', $_id)));
		$act->orden = $_orden;
		$act->save();
	}*/
	



	/*PROMOCIONES*/
	

	public function traerPromociones($_estado)
	{
		$_lanz = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_promociones` as cp WHERE cp.estado = :estado ORDER BY cp.id DESC");
		$_lanz->execute(array(":estado" => $_estado));
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}

	

	public function traerPromocion($_id)
	{
		$result = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_promociones` as cp WHERE cp.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function borrarPromocion($_id, $_ruta, $_tipo)
	{		
		$borrar = contenidos_promocione::find($_id);	
		//$this->ordenarProductos($borrar->orden);				
		$imgBorrar = contenidos_imagene::find('all',array('conditions' => array('identificador = ?', $borrar->identificador)));
		if($imgBorrar && is_array($imgBorrar)){
			foreach($imgBorrar as $idss){
				$dat_por_identificador[] = $idss->id;
			}		
			$this->eliminarImagenes($dat_por_identificador, $_ruta, $_tipo);
		}
		$borrar->delete();

		return true;
	}

	public function traerPromoBuscador($_valor, $_estado)
	{
		$result = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_promociones` as cp WHERE cp.titulo LIKE '%".$_valor."%' AND cp.estado = :estado ORDER BY cp.id DESC");
		$result->execute(array(":estado" => $_estado));
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
		
	}


	/*BANNERS*/
	
	public function traerBanners($_estado)
	{
		// return  contenidos_banner::find('all',array('conditions' => array('estado = ?', $_estado),'order' => 'id desc'));

		$_lanz = Pd::instancia()->prepare("SELECT cb.* FROM `contenidos_banners` as cb WHERE cb.estado = :estado ORDER BY cb.id DESC");
		$_lanz->execute(array(":estado" => $_estado));
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
			
	}
	
	public function traerBanner($_id)
	{
		// return contenidos_banner::find($_id);

		$result = Pd::instancia()->prepare("SELECT cb.* FROM `contenidos_banners` as cb WHERE cb.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}
	
	public function traerSecciones()
	{
		// return contenidos_seccione::all(array('order' => 'id asc'));

		$_lanz = Pd::instancia()->prepare("SELECT cs.* FROM `contenidos_secciones` as cs ORDER BY cs.id ASC");
		$_lanz->execute();
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}

	public static function traerSeccionPorId($_id)
	{
		return contenidos_seccione::find($_id);
	}
	
	public function borrarBanner($_id, $_ruta, $_tipo)
	{		
		$borrar = contenidos_banner::find($_id);	
		//$this->ordenarProductos($borrar->orden);				
		$imgBorrar = contenidos_imagene::find('all',array('conditions' => array('identificador = ?', $borrar->identificador)));
		if($imgBorrar && is_array($imgBorrar)){
			foreach($imgBorrar as $idss){
				$dat_por_identificador[] = $idss->id;
			}		
			$this->eliminarImagenes($dat_por_identificador, $_ruta, $_tipo);
		}
		$borrar->delete();

		return true;
	}


	public function traerBannerBuscador($_valor, $_estado)
	{
		$result = Pd::instancia()->prepare("SELECT cb.* FROM `contenidos_banners` as cb WHERE cb.titulo LIKE '%".$_valor."%' AND cb.estado = :estado ORDER BY cb.id DESC");
		$result->execute(array(":estado" => $_estado));
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
		
	}

	/*DESCUENTOS*/

	public function traerDescuentos()
	{
		$_lanz = Pd::instancia()->prepare("SELECT cd.* FROM `contenidos_descuentos` as cd ORDER BY cd.id DESC");
		$_lanz->execute();
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}

	public function traerDescuento($_id)
	{
		$result = Pd::instancia()->prepare("SELECT cd.* FROM `contenidos_descuentos` as cd WHERE cd.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function borrarDescuento($_id)
	{		
		$borrar = contenidos_descuento::find($_id);			
		$borrar->delete();

		return true;
	}

	public function traerBuscadorDescuento($_valor)
	{
		$result = Pd::instancia()->prepare("SELECT cd.* FROM `contenidos_descuentos` as cd WHERE cd.nombre LIKE '%".$_valor."%' OR cd.descuento LIKE '%".$_valor."%' ORDER BY cd.id DESC");
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
		
	}

	public function traerBuscadorDescuento2($_valor)
	{
		$result = Pd::instancia()->prepare("SELECT cd.* FROM `contenidos_descuentos` as cd WHERE cd.codigo LIKE '%".$_valor."%' OR cd.descuento LIKE '%".$_valor."%' ORDER BY cd.id DESC");
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
		
	}

	/*ESTADISTICAS*/

	
	public function traerVentasPorMes($_fecha, $_estado)
	{

		$_lanz = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_compras` as cp WHERE MONTH(cp.fecha) = :fecha AND cp.estado = :estado ORDER BY cp.id DESC");
		$_lanz->execute(array(":estado" => $_estado, ":fecha" => $_fecha));
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
			
	}

	public function traerVentasPorPeriodo($_fecha1, $_fecha2, $_estado)
	{

		$_lanz = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_compras` as cp WHERE DATE(cp.fecha) >= :fecha1 AND DATE(cp.fecha) <= :fecha2 AND cp.estado = :estado ORDER BY cp.id DESC");
		$_lanz->execute(array(":estado" => $_estado, ":fecha1" => $_fecha1, ":fecha2" => $_fecha2));
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
			
	}
	
	public function traerCursosMasVendidos($_fecha1, $_fecha2, $_estado)
	{

		/*$_lanz = Pd::instancia()->prepare("SELECT cpd.id_curso FROM `contenidos_compras` as cp 
											LEFT JOIN `contenidos_compras_detalle` as cpd
											ON cp.id = cpd.id_compra
											WHERE DATE(cp.fecha) = :fecha AND cp.estado = :estado 
											ORDER BY cp.id DESC");*/

		/*$_lanz = Pd::instancia()->prepare("SELECT cpd.id_curso FROM `contenidos_compras_detalle` as cpd
											WHERE DATE(cpd.fecha) = :fecha");*/

		$_lanz = Pd::instancia()->prepare("SELECT cpd.id_curso, cs.nombre,cp.estado,css.nombre as 'sucursal', COUNT(cpd.id_curso) as 'cantidad' 
											FROM `contenidos_compras_detalle` as cpd
											LEFT JOIN `contenidos_compras` as cp
											ON cpd.id_compra = cp.id
											LEFT JOIN `contenidos_cursos` as cs
											ON cpd.id_curso = cs.id
											LEFT JOIN `contenidos_sucursales` as css
											ON cs.id_sucursal = css.id
											WHERE DATE(cpd.fecha) >= :fecha1 AND DATE(cpd.fecha) <= :fecha2 AND cp.estado = :estado
											GROUP BY cpd.id_curso 
											ORDER BY cantidad DESC");

		// $_lanz->execute(array(":estado" => $_estado, ":fecha" => $_fecha));
		$_lanz->execute(array(":estado" => $_estado, ":fecha1" => $_fecha1, ":fecha2" => $_fecha2));
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
			
	}


	public function traerVentasPorSucursales($_fecha1, $_fecha2, $_estado)
	{

		$_lanz = Pd::instancia()->prepare("SELECT cp.id_sucursal, cs.nombre, cp.estado, COUNT(cp.id_sucursal) as 'cantidad' 
											FROM `contenidos_compras` as cp
											LEFT JOIN `contenidos_sucursales` as cs
											ON cp.id_sucursal = cs.id
											 WHERE DATE(cp.fecha) >= :fecha1 AND DATE(cp.fecha) <= :fecha2 AND cp.estado = :estado
											 GROUP BY cp.id_sucursal
											 ORDER BY cantidad DESC");
		$_lanz->execute(array(":estado" => $_estado, ":fecha1" => $_fecha1, ":fecha2" => $_fecha2));
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
			
	}

	public function traerVentasPorPlataformas($_fecha1, $_fecha2, $_estado)
	{

		$_lanz = Pd::instancia()->prepare("SELECT cp.plataforma_pago,cpp.nombre,cp.estado, COUNT(cp.plataforma_pago) as 'cantidad'
											 FROM `contenidos_compras` as cp
											 LEFT JOIN `contenidos_plataformas_pago` as cpp
											 ON cp.plataforma_pago = cpp.item
											 WHERE DATE(cp.fecha) >= :fecha1 AND DATE(cp.fecha) <= :fecha2 AND cp.estado = :estado
											 GROUP BY cp.plataforma_pago
											 ORDER BY cantidad DESC");
		$_lanz->execute(array(":estado" => $_estado, ":fecha1" => $_fecha1, ":fecha2" => $_fecha2));
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
			
	}

	public static function traerPlataformaPorItem($_item)
	{
		$result = Pd::instancia()->prepare("SELECT cpp.nombre FROM `contenidos_plataformas_pago` as cpp WHERE cpp.item = :item");
		$result->execute(array(":item" => $_item));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result['nombre'] : false;
	}





	/*COMPRAS*/
	
	
	public function traerPedidos($_estado)
	{

		$_lanz = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_compras` as cp WHERE cp.estado = :estado ORDER BY cp.id DESC");
		$_lanz->execute(array(":estado" => $_estado));
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
			
	}
	
	public function traerPedido($_id)
	{
		// return contenidos_pedido::find($_id);

		$result = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_compras` as cp WHERE cp.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}
	
	public function traerDatosCarrito($_compra)
	{

		$_lanz = Pd::instancia()->prepare("SELECT cc.* FROM `contenidos_compras_detalle` as cc WHERE cc.id_compra = :compra ORDER BY cc.id ASC");
		$_lanz->execute(array(":compra" => $_compra));
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
			
	}

	public function traerOrder($_id)
	{
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cc.* FROM `contenidos_compras` as cc WHERE cc.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerBuscadorCompras($_valor, $_estado)
	{
		$_val = is_int($_valor);
		if($_val){
			$result = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_compras` as cp WHERE cp.id LIKE '%".$_valor."%' AND cp.estado = :estado ORDER BY cp.id DESC");
		}else{

			$result = Pd::instancia()->prepare("SELECT cu.nombre,cu.apellido,cp.id,cp.id_user,cp.fecha 
											FROM `contenidos_users` as cu 
											INNER JOIN  `contenidos_compras` as cp 
											ON cu.id = cp.id_user 
											WHERE cu.nombre LIKE '%".$_valor."%' OR cu.apellido LIKE '%".$_valor."%'
											AND cp.estado = :estado ORDER BY cp.id DESC");

		}
		//return contenidos_user::find_by_sql('SELECT * FROM contenidos_users WHERE numero_cliente = "'.$_valor.'" OR (razon_social LIKE "%'.$_valor.'%") ORDER BY id DESC');

		// $result = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_compras` as cp WHERE cp.id_user LIKE '%".$_valor."%' OR cp.id LIKE '%".$_valor."%' AND cp.estado = :estado ORDER BY cp.id DESC");
		// $result = Pd::instancia()->prepare("SELECT cu.* FROM `contenidos_users` as cu WHERE cu.nombre LIKE '%".$_valor."%' OR cu.apellido LIKE '%".$_valor."%' AND cp.estado = :estado ORDER BY cp.id DESC");

		/*$result = Pd::instancia()->prepare("SELECT DISTINCT cp.id,cp.id_user,cp.id_sucursal,cp.fecha FROM `contenidos_compras` as cp
											INNER JOIN  `contenidos_users` as cu 
											ON cu.nombre LIKE '%".$_valor."%' OR cu.apellido LIKE '%".$_valor."%'
											OR cp.id LIKE '%".$_valor."%' 
											WHERE cp.estado = :estado ORDER BY cp.id DESC");*/

		/*$result = Pd::instancia()->prepare("SELECT DISTINCT cp.id,cp.id_user,cp.id_sucursal,cp.fecha FROM `contenidos_compras` as cp
											LEFT JOIN  `contenidos_users` as cu 
											ON cu.nombre LIKE CONCAT('%', $_valor, '%') OR cu.apellido LIKE CONCAT('%', $_valor, '%')
											OR cp.id LIKE CONCAT('%', $_valor, '%') 
											WHERE cp.estado = :estado ORDER BY cp.id DESC");*/


		$result->execute(array(':estado' => $_estado));
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
		
	}

	public function traerBuscadorForms($_valor)
	{
		$_val = (int) $_valor;
		
		$result = Pd::instancia()->prepare("SELECT cc.id,cc.id_user,cc.id_compra,cc.id_producto,cc.estado,cc.fecha, cp.titulo
											FROM `contenidos_forms_respuestas` as cc 
											LEFT JOIN `contenidos_productos` as cp
											ON cc.id_producto = cp.id
											WHERE cc.id_compra = :compra											
											ORDER BY cc.id DESC");

		$result->execute(array(':compra' => $_val));
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
		
	}

	public function traerBuscadorFormsEstado($_estado)
	{
		// $_val = (int) $_valor;
		
		$result = Pd::instancia()->prepare("SELECT cc.id,cc.id_user,cc.id_compra,cc.id_producto,cc.estado,cc.fecha, cp.titulo, cp.item
											FROM `contenidos_forms_respuestas` as cc 
											LEFT JOIN `contenidos_productos` as cp
											ON cc.id_producto = cp.id
											WHERE cc.estado = :estado											
											ORDER BY cc.id DESC");

		$result->execute(array(':estado' => $_estado));
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
		
	}


	public function traerComprasExport($_estado)
	{
		// $_lanz = Pd::instancia()->prepare("SELECT cu.* FROM `contenidos_compras` as cu ORDER BY cu.id DESC");

		$result = Pd::instancia()->prepare("SELECT cp.*,cu.nombre,cu.apellido,cu.dni,cu.telefono,cu.email FROM `contenidos_compras` as cp
											LEFT JOIN  `contenidos_users` as cu 
											ON cp.id_user = cu.id
											WHERE cp.estado = :estado ORDER BY cp.id DESC");

		$result->execute(array(':estado' => $_estado));
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	
	public static function traerUserPorId($_id)
	{
		 $result = contenidos_user::find(array('conditions' => array('id = ?', $_id)));

		return ($result) ? $result : false;
		
	}
	
	
	public function traerUsers($_estado)
	{
		$result = Pd::instancia()->prepare("SELECT cu.* FROM `contenidos_users` as cu WHERE cu.estado = :estado ORDER BY cu.id DESC");
		$result->execute(array(":estado" => $_estado));
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
			
	}
	
	public function traerUser($_id)
	{
		// return contenidos_user::find($_id);

		$result = Pd::instancia()->prepare("SELECT cu.* FROM `contenidos_users` as cu WHERE cu.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function borrarUser($_id)
	{		
		$borrar = contenidos_user::find(array('conditions' => array('id = ?', $_id)));
		if($borrar){

			$borrar_compra = contenidos_compra::find('all',array('conditions' => array('id_user = ?', $borrar->id)));
			if($borrar_compra && is_array($borrar_compra)){
				foreach($borrar_compra as $compra){
					$borrar_detalle = contenidos_compras_detall::find('all',array('conditions' => array('id_compra = ?', $compra->id)));
					if($borrar_detalle && is_array($borrar_detalle)){
						foreach ($borrar_detalle as $detail) {
							$detail->delete();
						}
					}
					$compra->delete();
				}

						
			}
			

			$borrar->delete();

			return true;

		}else{
			return false;
		}		
		
	}

	public function traerBuscadorUsers($_valor, $_estado)
	{
		//return contenidos_user::find_by_sql('SELECT * FROM contenidos_users WHERE numero_cliente = "'.$_valor.'" OR (razon_social LIKE "%'.$_valor.'%") ORDER BY id DESC');
		$result = Pd::instancia()->prepare("SELECT cu.* FROM `contenidos_users` as cu WHERE cu.estado = :estado AND (cu.nombre LIKE '%".$_valor."%' OR cu.apellido LIKE '%".$_valor."%' OR cu.email LIKE '%".$_valor."%') ORDER BY cu.id DESC");
		$result->execute(array(":estado" => $_estado));
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
		
	}

	public function traerUsersExportUser($_id)
	{
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cu.* FROM `contenidos_users` as cu WHERE cu.id = :id");
		/*$result = Pd::instancia()->prepare("SELECT cu.*,cs.address_name,address_line_1,address_line_2,zipcode,city,state,country FROM `contenidos_users` as cu 
											LEFT JOIN `contenidos_shipping` as cs 
											ON cu.id = cs.id_user
											WHERE cu.id = :id");*/
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerUsersExportAll($_estado)
	{
		$result = Pd::instancia()->prepare("SELECT cu.* FROM `contenidos_users` as cu WHERE cu.estado = :estado ORDER BY cu.id DESC");		
		$result->execute(array(":estado" => $_estado));
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}


	public function traerDireccionesEnviosUsers($_id)
	{
		$result = Pd::instancia()->prepare("SELECT cs.* FROM `contenidos_shipping` as cs WHERE cs.id_user = :id ORDER BY cs.id ASC");
		$result->execute(array(":id" => $_id));
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
			
	}

	public function traerDireccionesBillingUsers($_id)
	{
		$result = Pd::instancia()->prepare("SELECT cb.* FROM `contenidos_billing` as cb WHERE cb.id_user = :id ORDER BY cb.id ASC");
		$result->execute(array(":id" => $_id));
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
			
	}

	// FORMS

	public function traerAllForms()
	{
		$result = Pd::instancia()->prepare("SELECT cc.id,cc.id_user,cc.id_compra,cc.id_producto,cc.estado,cc.fecha,cu.nombre,cu.apellido,cp.titulo,cp.item
											FROM `contenidos_forms_respuestas` as cc 
											LEFT JOIN `contenidos_users` as cu
											ON cc.id_user = cu.id
											LEFT JOIN `contenidos_productos` as cp
											ON cc.id_producto = cp.id
											WHERE cu.estado = 'customer'
											ORDER BY cc.id DESC");
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public static function traerDocuSignPorId($_id)
	{
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cc.* FROM `contenidos_forms_docs` as cc WHERE cc.id_form = :id ORDER BY cc.id DESC");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerFormsPorUser($_id, $_compra)
	{
		$_id = (int) $_id;
		$_compra = (int) $_compra;
		$result = Pd::instancia()->prepare("SELECT cc.* FROM `contenidos_forms_respuestas` as cc WHERE cc.id_user = :id AND cc.id_compra = :compra ORDER BY cc.fecha DESC");
		$result->execute(array(":id" => $_id, ":compra" => $_compra));
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerFormsPorId($_id)
	{
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cc.* FROM `contenidos_forms_respuestas` as cc WHERE cc.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerFormsCircuit($_county)
	{
		$result = Pd::instancia()->prepare("SELECT cc.* FROM `contenidos_forms_circuits` as cc WHERE cc.county = :county");
		$result->execute(array(":county" => $_county));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result['circuit'] : false;
	}


	

	public function traerFormsCondados()
	{
		$result = Pd::instancia()->prepare("SELECT cc.* FROM `contenidos_forms_condados` as cc ORDER BY cc.id ASC");
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerFormsStates()
	{
		$result = Pd::instancia()->prepare("SELECT cc.* FROM `contenidos_forms_states` as cc ORDER BY cc.id ASC");
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function agregarImg($_id, $_data)
	{
		$_id = (int) $_id;

		$_filteredData = substr($_data, strpos($_data, ",")+1);
        $_unencodedData = base64_decode($_filteredData);
        $_nombre_archivo = rand();

        file_put_contents(Registro::tomarInstancia()->_conf['ruta_img_cargadas'] . "capturas" . DS . $_nombre_archivo . '.jpeg', $_unencodedData);

        // require RAIZ.'libs/fpdf.php';

        /*$pdf = new FPDF();
		$pdf->AddPage();
		$pdf->SetCompression(true);
		$pdf->SetAutoPageBreak(true, 5);
		// $pdf->Image(Registro::tomarInstancia()->_conf['ruta_img_cargadas'] . "capturas" . DS . $_nombre_archivo . '.jpeg', 15, $pdf->GetY());
		$pdf->Image(Registro::tomarInstancia()->_conf['ruta_img_cargadas'] . "capturas" . DS . $_nombre_archivo . '.jpeg', 15,0);
		$pdf->Output(Registro::tomarInstancia()->_conf['ruta_archivos'] . "pdf" . DS . $_nombre_archivo . '.pdf','F');*/


		// usage:
		require RAIZ.'libs/pdf.php';
		$pdf = new PDF();
		$pdf->AddPage();
		$pdf->centreImage(Registro::tomarInstancia()->_conf['ruta_img_cargadas'] . "capturas" . DS . $_nombre_archivo . '.jpeg');
		$pdf->Output(Registro::tomarInstancia()->_conf['ruta_archivos'] . "pdf" . DS . $_nombre_archivo . '.pdf','F');


    	$_cargar_img = contenidos_forms_respuesta::find(array('conditions' => array('id = ?', $_id)));

    	if($_cargar_img){

    		if($_cargar_img->img != ''){
    			if(is_readable(Registro::tomarInstancia()->_conf['ruta_img_cargadas'] . "capturas" . DS . $_cargar_img->img)){
	    			unlink(Registro::tomarInstancia()->_conf['ruta_img_cargadas'] . "capturas" . DS . $_cargar_img->img);
	    		}
    		}
			$_cargar_img->img = $_nombre_archivo . '.jpeg';
			return ($_cargar_img->save()) ? true : false;
		}
		return false;
	}

	public function descargarPDF($_imagen)
	{
		if(substr_count($_imagen, '.')){
			$_str = explode('.', $_imagen);
			$_archivo = $_str[0] . '.pdf';
		}else{
			return false;
		}

		$_pdf = Registro::tomarInstancia()->_conf['base_url'] . "public" . DS . "files" . DS . "pdf" . DS . $_archivo;
		return $_pdf;
	}

	// BLOG

	public function traerBlogs()
	{
		$result = Pd::instancia()->prepare("SELECT cs.* FROM `contenidos_blog` as cs ORDER BY cs.id DESC");
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
			
	}

	public function traerBlog($_id)
	{
		// return contenidos_user::find($_id);
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cs.* FROM `contenidos_blog` as cs WHERE cs.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public static function traerBlogId($_id)
	{		
		return contenidos_blo::find(array('conditions' => array('id = ?', $_id)));	

	}

	public function traerBuscadorBlogs($_valor)
	{
		$result = Pd::instancia()->prepare("SELECT cs.* FROM `contenidos_blog` as cs WHERE cs.titulo LIKE '%".$_valor."%' ORDER BY cs.id DESC");
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
		
	}

	/*public function borrarSucursal($_id)
	{		
		$borrar = contenidos_sucursale::find(array('conditions' => array('id = ?', $_id)));	
		$borrar->delete();

		return true;
	}*/

	public function borrarBlogs($_id, $_ruta, $_tipo)
	{		
		$borrar = contenidos_blo::find(array('conditions' => array('id = ?', $_id)));	
		//$this->ordenarProductos($borrar->orden);				
		$imgBorrar = contenidos_imagene::find('all',array('conditions' => array('identificador = ?', $borrar->identificador)));
		if($imgBorrar && is_array($imgBorrar)){
			foreach($imgBorrar as $idss){
				$dat_por_identificador[] = $idss->id;
			}		
			$this->eliminarImagenes($dat_por_identificador, $_ruta, $_tipo);
		}
		$borrar->delete();

		return true;
	}

	// PRESS

	public function traerPresses()
	{
		$result = Pd::instancia()->prepare("SELECT cs.* FROM `contenidos_press` as cs ORDER BY cs.id DESC");
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
			
	}

	public function traerPress($_id)
	{
		// return contenidos_user::find($_id);
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cs.*, ca.path FROM `contenidos_press` as cs 
											LEFT JOIN `contenidos_archivos` as ca 
											ON cs.identificador = ca.identificador
											WHERE cs.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public static function traerPressId($_id)
	{		
		return contenidos_pres::find(array('conditions' => array('id = ?', $_id)));	

	}

	public function traerBuscadorPress($_valor)
	{
		$result = Pd::instancia()->prepare("SELECT cs.* FROM `contenidos_press` as cs WHERE cs.titulo LIKE '%".$_valor."%' ORDER BY cs.id DESC");
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
		
	}

	
	public function borrarPress($_id, $_ruta, $_tipo)
	{		
		$borrar = contenidos_pres::find(array('conditions' => array('id = ?', $_id)));	
		//$this->ordenarProductos($borrar->orden);				
		$imgBorrar = contenidos_imagene::find('all',array('conditions' => array('identificador = ?', $borrar->identificador)));
		if($imgBorrar && is_array($imgBorrar)){
			foreach($imgBorrar as $idss){
				$dat_por_identificador[] = $idss->id;
			}		
			$this->eliminarImagenes($dat_por_identificador, $_ruta, $_tipo);
		}
		$borrar->delete();

		return true;
	}

	// PRODUCTOS

	public function traerProductos($_estado)
	{
		$_lanz = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_productos` as cp WHERE cp.estado = :estado ORDER BY cp.id DESC");
		$_lanz->execute(array(":estado" => $_estado));
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}

	public function traerProducto($_id)
	{
		$result = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_productos` as cp WHERE cp.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerProductosVariables($_estado)
	{
		$_lanz = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_productos_variables` as cp WHERE cp.estado = :estado ORDER BY cp.id DESC");
		$_lanz->execute(array(":estado" => $_estado));
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}

	public function traerProductoVariable($_id)
	{
		$result = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_productos_variables` as cp WHERE cp.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}
	
	public static function traerProductoPorId($_id)
	{
		return contenidos_producto::find($_id);
	}

	public static function traerProductoVariablePorId($_id)
	{
		return contenidos_productos_variable::find($_id);
	}

	public static function traerProductoStatic($_id)
	{
		$result = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_productos` as cp WHERE cp.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public static function traerProductoPorItemStatic($_val)
	{
		if($_val == 'ppa' || $_val == 'msa' || $_val == 'msappa'){
			return contenidos_producto::find(array('conditions' => array('item = ?', $_val)));
		}else{
			return contenidos_productos_variable::find(array('conditions' => array('item = ?', $_val)));
		}
		
	}

	public static function traerProductoPorTipoStatic($_id, $_tipo)
	{
		if($_tipo == 'fijo'){
			return contenidos_producto::find(array('conditions' => array('id = ?', $_id)));
		}else{
			return contenidos_productos_variable::find(array('conditions' => array('id = ?', $_id)));
		}
		
	}

	public static function traerProductosPorItem($_item)
	{
		$result = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_productos` as cp WHERE cp.item = :item");
		$result->execute(array(":item" => $_item));
		$result = $result->fetch(PDO::FETCH_ASSOC);


		if($result){

			
			$_imgs = Pd::instancia()->prepare("SELECT ci.* FROM `contenidos_imagenes` as ci WHERE ci.identificador = :identificador AND ci.posicion = 'principal' ORDER BY ci.orden ASC");
			$_imgs->execute(array(':identificador' => $result['identificador']));
			$_imgs = $_imgs->fetchAll(PDO::FETCH_ASSOC);
			// $_arrayImg=array();
			foreach ($_imgs as $val) {
				$_arrayImg=$val['path'];
			}
			// $_data[$i]['imagenes'] = implode(';', $_arrayImg);
			$result['imagenes'] = $_arrayImg;
			
			

		}

		return ($result) ? $result : false;
	}


	public function traerProdBuscador($_tipo, $_valor, $_estado='alta')
	{
		if($_tipo == 'fijos'){
			$result = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_productos` as cp WHERE cp.titulo LIKE '%".$_valor."%' AND cp.estado = :estado ORDER BY cp.id DESC");
		}else{
			$result = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_productos_variables` as cp WHERE cp.titulo LIKE '%".$_valor."%' AND cp.estado = :estado ORDER BY cp.id DESC");
		}
		
		$result->execute(array(":estado" => $_estado));
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
		
	}

	public function borrarProducto($_id, $_tipo)
	{		
		if($_tipo == 'fijos'){
			$borrar = contenidos_producto::find($_id);			
			$borrar->delete();
		}else{
			$borrar = contenidos_productos_variable::find($_id);			
			$borrar->delete();
		}

		return true;
	}

	// CMS

	public function traerDisclaimer()
	{
		$_lanz = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_disclaimer` as cp WHERE cp.id = :id");
		$_lanz->execute(array(":id" => 1));
		$_lanz = $_lanz->fetch(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}

	public function traerHowitworks()
	{
		$_lanz = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_howitworks` as cp WHERE cp.id = :id");
		$_lanz->execute(array(":id" => 1));
		$_lanz = $_lanz->fetch(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}
	public function traerTermsandconditions()
	{
		$_lanz = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_termsandconditions` as cp WHERE cp.id = :id");
		$_lanz->execute(array(":id" => 1));
		$_lanz = $_lanz->fetch(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}


	public function traerWhyus()
	{
		$_lanz = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_whyus` as cp WHERE cp.id = :id");
		$_lanz->execute(array(":id" => 1));
		$_lanz = $_lanz->fetch(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}


	// Grupos

	public function traerGrupos()
	{
		$result = Pd::instancia()->prepare("SELECT cs.* FROM `contenidos_grupos` as cs ORDER BY cs.id DESC");
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
			
	}

	public function traerGrupo($_id)
	{
		// return contenidos_user::find($_id);
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cs.* FROM `contenidos_grupos` as cs WHERE cs.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerSucporGrupo($_id)
	{
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cs.* FROM `contenidos_blog` as cs WHERE cs.id_grupo = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}


	public function traerBuscadorGrupo($_valor)
	{
		$result = Pd::instancia()->prepare("SELECT cs.* FROM `contenidos_grupos` as cs WHERE cs.nombre LIKE '%".$_valor."%' OR cs.email LIKE '%".$_valor."%' OR cs.mp_public_key LIKE '%".$_valor."%' OR cs.mp_access_token LIKE '%".$_valor."%' OR cs.tp_api_key LIKE '%".$_valor."%' OR cs.transferencia LIKE '%".$_valor."%' ORDER BY cs.id DESC");
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
		
	}

	public function borrarGrupo($_id)
	{		
		$borrar = contenidos_grupo::find($_id);	
		$borrar->delete();

		return true;
	}


	///////////////////////////////////////////////



	public function vaciarDatosSucursales()
	{		
		$result = Pd::instancia()->prepare("TRUNCATE TABLE `contenidos_sucursales`");
		$result->execute();
		
		return true;
	}

	
	public static function traerCursoPorId($_id)
	{
		return contenidos_curso::find($_id);
	}
	
	public static function traerPromoPorId($_id)
	{
		return contenidos_promocione::find($_id);
	}
	
	public static function traerImgLista($_identificador, $_tipo, $_pos, $_orden)
	{
		return  contenidos_imagene::find(array('conditions' => array('identificador = ? AND tipo = ? AND posicion = ? AND orden = ?', $_identificador, $_tipo, $_pos, $_orden)));
	}




	/*public static function traerImgExportar($_identificador)
	{
		return  contenidos_imagene::find('all',array('conditions' => array('identificador = ?', $_identificador),'order' => 'orden asc'));
	}*/

	public static function traerImgExportar($_identificador)
	{
		$_lanz = Pd::instancia()->prepare("SELECT ci.path FROM `contenidos_imagenes` as ci WHERE ci.identificador = :identificador ORDER BY ci.orden ASC");
		$_lanz->execute(array(':identificador' => $_identificador));
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}

	public static function traerImg($_identificador, $_tipo, $_pos='')
	{
		return  contenidos_imagene::find(array('conditions' => array('identificador = ? AND tipo = ? AND posicion = ?', $_identificador, $_tipo, $_pos)));
	}


	
	public static function traerImg2($_identificador, $_tipo)
	{
		return  contenidos_imagene::find(array('conditions' => array('identificador = ? AND tipo = ?', $_identificador, $_tipo)));
	}
	
	public static function traerImgGal($_identificador, $_tipo, $_pos)
	{
		return  contenidos_imagene::find('all',array('conditions' => array('identificador = ? AND tipo = ? AND posicion = ?', $_identificador, $_tipo, $_pos),'order' => 'orden asc'));
	}
	
	public function cargarImgDB($_identificador, $_path, $_nombre, $_pos, $_tipo, $_ruta)
	{
		//$_categoria_id = (int) $_categoria_id;
		$_nombre = strtolower(trim($_nombre));
		$_nombre = htmlentities($_nombre, ENT_QUOTES | ENT_IGNORE, "UTF-8");



		
		$_editar = contenidos_imagene::find(array('conditions' => array('identificador = ? AND posicion = ? AND tipo = ?', $_identificador, $_pos, $_tipo)));
		
		if($_editar){
			
			$_borrarImg = $this->EliminarImagenEnDirectorio($_ruta, $_editar->path, $_tipo, $_pos);			
			if($_borrarImg==true){			
				$_editar->nombre = $_nombre;
				$_editar->identificador = $_identificador;
				$_editar->path = $_path;
				$_editar->posicion = $_pos;	
				$_editar->tipo = $_tipo;		
				$_editar->orden = 0;				
				$_editar->fecha_alt = date("Y/m/d");
				$_editar->save();
				return $_editar->id;
			}
		}else{
			$imagen = new contenidos_imagene();
			//$imagen->categoria_id = $_categoria_id;
			$imagen->nombre = $_nombre;
			$imagen->identificador = $_identificador;
			$imagen->path = $_path;			
			$imagen->posicion = $_pos;
			$imagen->tipo = $_tipo;		
			$imagen->orden = 0;
			$imagen->fecha_alt = date("Y/m/d");
			$imagen->save();	
			return $imagen->id;	;	
		}
		
		
		
				
	}
	
	public function cargarImgDBLote($_identificador, $_path, $_nombre, $_pos, $_tipo, $_ruta)
	{
		//$_categoria_id = (int) $_categoria_id;
		$_nombre = strtolower(trim($_nombre));
		$_nombre = htmlentities($_nombre, ENT_QUOTES | ENT_IGNORE, "UTF-8");		
		
		
		/*if($_modulo!=0){
			$_editar = contenidos_imagene::find(array('conditions' => array('identificador = ? AND posicion = ? AND modulo = ?', $_identificador, $_pos, $_modulo)));
		}else{
			$_editar = contenidos_imagene::find(array('conditions' => array('identificador = ? AND posicion = ?', $_identificador, $_pos)));
		}
		
		if($_editar){
			
			$_borrarImg = $this->EliminarImagenEnDirectorio($_ruta, $_editar->path, $_tipo, $_pos);			
			if($_borrarImg==true){			
				$_editar->nombre = $_nombre;
				$_editar->identificador = $_identificador;
				$_editar->path = $_path;
				$_editar->posicion = $_pos;	
				$_editar->modulo = $_modulo;						
				$_editar->fecha_alt = date("Y/m/d");
				$_editar->save();
				return $_editar->id;
			}
		}else{*/
			$imagen = new contenidos_imagene();
			//$imagen->categoria_id = $_categoria_id;
			$imagen->nombre = $_nombre;
			$imagen->identificador = $_identificador;
			$imagen->path = $_path;			
			$imagen->posicion = $_pos;
			$imagen->tipo = $_tipo;
			$imagen->orden = self::ultimoOrdenImg($_identificador, $_pos, $_tipo)->orden+1;		
			$imagen->fecha_alt = date("Y/m/d");
			$imagen->save();	
			return $imagen->id;
		//}
		
		
		
		
		
	}
	
	public function cargarImgDBLoteEditar($_identificador, $_path, $_nombre, $_pos, $_tipo, $_ruta)
	{
		//$_categoria_id = (int) $_categoria_id;
		$_nombre = strtolower(trim($_nombre));
		$_nombre = htmlentities($_nombre, ENT_QUOTES | ENT_IGNORE, "UTF-8");		
		
		
		$_editar = contenidos_imagene::find(array('conditions' => array('identificador = ? AND posicion = ? AND tipo = ?', $_identificador, $_pos, $_tipo)));
		
		if($_editar){
			
			//$_borrarImg = $this->EliminarImagenEnDirectorio($_ruta, $_editar->path, $_tipo, $_pos);			
			if($_borrarImg==true){			
				$_editar->nombre = $_nombre;
				$_editar->identificador = $_identificador;
				$_editar->path = $_path;
				$_editar->posicion = $_pos;	
				$_editar->tipo = $_tipo;	
				$_editar->orden = self::ultimoOrdenImg($_identificador, $_pos, $_tipo)->orden+1;						
				$_editar->fecha_alt = date("Y/m/d");
				$_editar->save();
				return $_editar->id;
			}
		}
		
		
		
	}
	
	public static function ultimoOrdenImg($_identificador, $_pos, $_tipo)
	{
		$orden = contenidos_imagene::find(array('conditions' => array('identificador = ? AND tipo = ? AND posicion = ?', $_identificador, $_tipo, $_pos),'select' => 'max(orden) as orden'));
		//return  contenidos_trabajo::find('all',array('conditions' => array('estado = ?', $_estado),'order' => 'id asc'));
		if (isset($orden)){
			return $orden;
		}else{
			return 0;
		}
	}
	
	public function traerDataBasicaImagen($_id)
	{
		return  contenidos_imagene::find(array('conditions' => array('id = ?', $_id)));
	}

	public function eliminarImagenes2($_path, $_ruta, $_seccion)
	{
			
		/*if(is_readable($_ruta . $_seccion . '/'.$_path)){
			@unlink($_ruta . $_seccion . '/'. $_path);
		}*/			
		if(is_readable($_ruta  . $_seccion . '/grandes/'. $_path)){
			@unlink($_ruta . $_seccion . '/grandes/'. $_path);
		}
		if(is_readable($_ruta  . $_seccion . '/thumb/'. $_path)){
			@unlink($_ruta . $_seccion . '/thumb/'. $_path);
		}

		/*if(is_readable($_ruta  . $_seccion . '/principal/'. $_path)){
			@unlink($_ruta . $_seccion . '/principal/'. $_path);
		}*/
		if($_seccion=='novedades'){
			if(is_readable($_ruta  . $_seccion . '/principal/grandes/'. $_path)){
				@unlink($_ruta . $_seccion . '/principal/grandes/'. $_path);
			}
			if(is_readable($_ruta  . $_seccion . '/principal/thumb/'. $_path)){
				@unlink($_ruta . $_seccion . '/principal/thumb/'. $_path);
			}
			if(is_readable($_ruta  . $_seccion . '/galeria/grandes/'. $_path)){
				@unlink($_ruta . $_seccion . '/galeria/grandes/'. $_path);
			}
			if(is_readable($_ruta  . $_seccion . '/galeria/thumb/'. $_path)){
				@unlink($_ruta . $_seccion . '/galeria/thumb/'. $_path);
			}
		}
		
		return true;
	}
	
	
	public function eliminarImagenes(array $_elementos, $_ruta, $_tipo)
	{
		// Eliminamos los elemento seleccionados de la base de datos y de los directorios.
		
		foreach($_elementos as $eliminar){
			
			//Traemos el registro
			$_elemento = contenidos_imagene::find($eliminar); 
			//echo $_elemento->path;		
			
			if($_tipo=='productos'){	
			
				if(is_readable($_ruta . $_tipo . '/galeria/grandes/'.$_elemento->path)){
					@unlink($_ruta . $_tipo . '/galeria/grandes/'. $_elemento->path);
				}			
				if(is_readable($_ruta  . $_tipo . '/galeria/thumb/'. $_elemento->path)){
					@unlink($_ruta . $_tipo . '/galeria/thumb/'. $_elemento->path);
				}
				
				if(is_readable($_ruta . $_tipo . '/beneficios/grandes/'.$_elemento->path)){
					@unlink($_ruta . $_tipo . '/beneficios/grandes/'. $_elemento->path);
				}			
				if(is_readable($_ruta  . $_tipo . '/beneficios/thumb/'. $_elemento->path)){
					@unlink($_ruta . $_tipo . '/beneficios/thumb/'. $_elemento->path);
				}
				
				if(is_readable($_ruta . $_tipo . '/medidas/grandes/'.$_elemento->path)){
					@unlink($_ruta . $_tipo . '/medidas/grandes/'. $_elemento->path);
				}			
				if(is_readable($_ruta  . $_tipo . '/medidas/thumb/'. $_elemento->path)){
					@unlink($_ruta . $_tipo . '/medidas/thumb/'. $_elemento->path);
				}
			
			}else if($_tipo=='noticias'){	
			
				if(is_readable($_ruta . $_tipo . '/principal/grandes/'.$_elemento->path)){
					@unlink($_ruta . $_tipo . '/principal/grandes/'. $_elemento->path);
				}			
				if(is_readable($_ruta  . $_tipo . '/chica/thumb/'. $_elemento->path)){
					@unlink($_ruta . $_tipo . '/chica/thumb/'. $_elemento->path);
				}				
				
			
			}else{
			
				if(is_readable($_ruta . $_tipo . '/grandes/'.$_elemento->path)){
					@unlink($_ruta . $_tipo . '/grandes/'. $_elemento->path);
				}			
				if(is_readable($_ruta  . $_tipo . '/thumb/'. $_elemento->path)){
					@unlink($_ruta . $_tipo . '/thumb/'. $_elemento->path);
				}
			
			}
				
			$_elemento->delete();	
		}
	}
	
	public function EliminarImagenEnDirectorio($_ruta, $_path, $_tipo, $_pos)
	{		
		//$borrar = contenidos_imagene::find($_id);
		//$borrar = contenidos_imagene::find(array('conditions' => array('id = ?', $_id)));
		
		//if($borrar){
			
			// eliminamos la grande
		if($_path!='prod_default.jpg'){


			if(is_readable($_ruta . $_tipo. '/'.$_pos . '/thumb/'.$_path)){
				@unlink($_ruta . $_tipo. '/'.$_pos . '/thumb/'.$_path);
			}
			if(is_readable($_ruta . $_tipo. '/'.$_pos . '/grandes/'.$_path)){
				@unlink($_ruta . $_tipo. '/'.$_pos . '/grandes/'.$_path);
			}			
			// eliminamos la mediana
			/*if(is_readable($_ruta  . $_seccion . '/thumb/'. $_path)){
				@unlink($_ruta . $_seccion . '/thumb/'. $_path);
			}
			
			if($_seccion=='blog' || $_seccion=='productos'){			
				if(is_readable($_ruta  . $_seccion . '/miniatura/'. $_path)){
					@unlink($_ruta . $_seccion . '/miniatura/'. $_path);
				}
			}*/
		}	
		
		return true;
		/*}else
			throw new Exception('La imagen no se pudo encontrar');*/	
	}

	public function eliminarImgSlider($_id, $_ruta, $_tipo, $_pos){
		
		$borrar = contenidos_imagene::find($_id);
		// self::ordenarImgGal($borrar->orden, $borrar->identificador, $_tipo, $_pos);
		//$borrar = contenidos_imagene::find(array('conditions' => array('id = ?', $_id)));
		
		if($borrar){

			
			// eliminamos la grande
			if(file_exists($_ruta . $_tipo. '/'.$_pos . '/thumb/'.$borrar->path)){
				@unlink($_ruta . $_tipo. '/'.$_pos . '/thumb/'. $borrar->path);
			}			
			// eliminamos la mediana
			if(file_exists($_ruta . $_tipo. '/'.$_pos . '/grandes/'. $borrar->path)){
				@unlink($_ruta . $_tipo. '/'.$_pos . '/grandes/'. $borrar->path);
			}
				

			$borrar->delete();
			return true;

		}else
			return false;
	}
	
	
	public function eliminarImgGal($_id, $_ruta, $_tipo, $_pos){
		
		$borrar = contenidos_imagene::find($_id);
		self::ordenarImgGal($borrar->orden, $borrar->identificador, $_tipo, $_pos);
		//$borrar = contenidos_imagene::find(array('conditions' => array('id = ?', $_id)));
		
		if($borrar){

			if($borrar->path!='prod_default.jpg'){
			
				// eliminamos la grande
				if(file_exists($_ruta . $_tipo. '/'.$_pos . '/thumb/'.$borrar->path)){
					@unlink($_ruta . $_tipo. '/'.$_pos . '/thumb/'. $borrar->path);
				}			
				// eliminamos la mediana
				if(file_exists($_ruta . $_tipo. '/'.$_pos . '/grandes/'. $borrar->path)){
					@unlink($_ruta . $_tipo. '/'.$_pos . '/grandes/'. $borrar->path);
				}
				
			}

			$borrar->delete();
			return true;

		}else
			return false;
	}

	public function eliminarVideos($_id, $_ruta){
		
		$borrar = contenidos_video::find(array('conditions' => array('identificador = ?', $_id)));
		
		if($borrar){
			
				
				if(file_exists($_ruta . $borrar->path)){
					@unlink($_ruta . $borrar->path);
				}
				

			$borrar->delete();
			return true;

		}else
			return false;
	}
	
	
	public function ordenarImgGal($_orden, $_identificador, $_tipo, $_pos)
	{
		$arrayOrden = contenidos_imagene::find('all',array('conditions' => array('identificador = ? AND tipo = ? AND posicion = ? AND orden > ?', $_identificador, $_tipo, $_pos, $_orden),'order' => 'orden asc'));
		for($i=0;$i<count($arrayOrden);$i++){
			$arrayOrden[$i]->orden = $arrayOrden[$i]->orden-1;
			$arrayOrden[$i]->save();		
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	public static function convertirCaracteres($_cadena)
	{
		return htmlspecialchars_decode(html_entity_decode($_cadena));
	}
	
	public static function convertirCaracteres2($_cadena)
	{
		return htmlspecialchars(html_entity_decode($_cadena));
	}
	
	public static function convertirCaracteres3($_cadena)
	{
		$_cadena = htmlspecialchars_decode($_cadena);
		$_cadena = html_entity_decode($_cadena);
		return utf8_decode($_cadena);
	}
	public static function convertirCaracteres4($_cadena)
	{
		$_cadena = htmlspecialchars_decode($_cadena);
		$_cadena = html_entity_decode($_cadena);
		return $_cadena;
	}
	
	public static function convertirCaracteres5($_cadena)
	{
		return utf8_decode($_cadena);
	}
	
	///////////////////////////////////////////////////////////////////////////
	
	public function traerAreas()
	{
		return contenidos_area::all(array('order' => 'nombre asc'));
	}
	
	public function traerArea($_id)
	{
		return contenidos_area::find($_id);
	}
	
	public function borrarArea($_id, $_ruta, $_seccion)
	{		
		$borrar = contenidos_area::find($_id);	
		//$this->ordenarEquipo($borrar->orden);				
		$imgBorrar = contenidos_imagene::find('all',array('conditions' => array('identificador = ?', $borrar->identificador)));
		if($imgBorrar && is_array($imgBorrar)){
			foreach($imgBorrar as $idss){
				$dat_por_identificador[] = $idss->id;
			}		
			$this->eliminarImagenes($dat_por_identificador, $_ruta, $_seccion);
		}
		$borrar->delete();
	}
	
	
	public function traerTrabajos($_estado)
	{
		return  contenidos_trabajo::find('all',array('conditions' => array('estado = ?', $_estado),'order' => 'id asc'));
		//return  contenidos_sectore::find('all',array('order' => 'id asc'));
			
	}
	
	public function traerSectores()
	{
		return  contenidos_sectore::find('all',array('order' => 'id asc'));
			
	}
	
	public function traerTrabajo($_id)
	{
		return contenidos_trabajo::find($_id);
	}
	
	public function borrarTrabajo($_id)
	{		
		$borrar = contenidos_trabajo::find($_id);		
		$borrar->delete();
	}
	
	public function traerProvincias()
	{
		return contenidos_provincia::all(array('order' => 'nombre asc'));
		//return contenidos_provincia::find_by_sql("SELECT id,nombre_completo FROM contenidos_candidatos WHERE FIND_IN_SET ('".$_trabajo."',ids_trabajos)");
	}
	
	public function traerLocalidades()
	{
		return contenidos_localidade::all(array('order' => 'nombre asc'));
	}
	
	public function traerLocalidadesPorId($_id)
	{
		return contenidos_localidade::find('all',array('conditions' => array('id_prov = ?', $_id),'select' => 'id_localidad, nombre','order' => 'nombre asc'));
		//Book::find('all', array('select' => 'id, title'));
			
	}
	
	public function traerEstados()
	{
		return contenidos_estado::all(array('order' => 'id asc'));
	}
	
	public function traerCandidatos($_trabajo)
	{
		//return contenidos_candidato::all(array('order' => 'id asc'));
		return contenidos_candidato::find_by_sql("SELECT id,nombre_completo FROM contenidos_candidatos WHERE FIND_IN_SET ('".$_trabajo."',ids_trabajos)");
	}
	
	public function traerCandidato($_id)
	{
		return contenidos_candidato::find($_id);
	}
	
	public function borrarCandidato($_id, $_ruta)
	{		
		$borrar = contenidos_candidato::find($_id);	
		
		if(file_exists($_ruta. $borrar->cv)){
			unlink($_ruta. $borrar->cv);
		}		
			
		$borrar->delete();
	}
	
	public function traerCarreras()
	{
		return contenidos_carrera::all(array('order' => 'nombre asc'));
	}
	
	public function traerNombres($_trabajo)
	{
		//return contenidos_candidato::all(array('select' => 'DISTINCT nombre_completo', 'order' => 'nombre asc'));
		return contenidos_candidato::find_by_sql("SELECT DISTINCT nombre_completo FROM contenidos_candidatos WHERE FIND_IN_SET ('".$_trabajo."',ids_trabajos) ORDER BY nombre ASC");
	}
	
	public function traerEdades($_trabajo)
	{
		//return contenidos_candidato::all(array('select' => 'DISTINCT edad', 'order' => 'edad asc'));
		return contenidos_candidato::find_by_sql("SELECT DISTINCT edad FROM contenidos_candidatos WHERE FIND_IN_SET ('".$_trabajo."',ids_trabajos) ORDER BY edad ASC");
	}
		
	
	public function traerFiltro($_nombres,$_provincias,$_localidades,$_carreras,$_edades,$_trabajo)
	{
		$arrayCont = array();
		$_html	='SELECT id,nombre_completo FROM contenidos_candidatos WHERE ';
		if(!empty($_nombres)){
			$a = count($_nombres);
			if($a<=1){
				$_nomb = "'" .$_nombres[0]. "'";
			}else{
				$_nomb ="'" . implode("','", $_nombres) . "'";
			}
			$arrayCont[] = 'nombre_completo IN ('.$_nomb.')';
		}else{
			$_nomb = '';
		}
		
		if(!empty($_provincias)){
			$_prov = implode(',',$_provincias);
			$arrayCont[] = 'provincia IN ('.$_prov.')';
		}else{
			$_prov = 0;
		}
		
		if(!empty($_localidades)){
			$_loc = implode(',',$_localidades);
			$arrayCont[] = 'localidad IN ('.$_loc.')';
		}else{
			$_loc = 0;
		}
		if(!empty($_carreras)){
			$_car = implode(',',$_carreras);
			$arrayCont[] = 'carrera IN ('.$_car.')';
		}else{
			$_car = 0;
		}
		
		if(!empty($_edades)){
			$_ed = implode(',',$_edades);
			$arrayCont[] = 'edad IN ('.$_ed.')';
		}else{
			$_ed = 0;
		}
		//for($i=0;$i<count($arrayCont);$i++){
		/*foreach ($arrayCont as $arr){
			if($arr === end($arrayCont)){
				$_html .= $arr;
			}else{
				$_html .= $arr. " AND ";
			}																									
		}*/
		
		foreach ($arrayCont as $arr){
			$_html .= $arr. " AND ";																									
		}
		
		$_html .= "FIND_IN_SET ('".$_trabajo."',ids_trabajos)";
		
		$_data = contenidos_candidato::find_by_sql($_html);
													
													/* nombre_completo IN ('.$_nomb.') AND 
													 provincia IN ('.$_prov.') AND 
													 localidad IN ('.$_loc.') AND 
													 carrera IN ('.$_car.') AND 
													 edad IN ('.$_ed.')');*/
		
		return $_data;
	}
	public static function traerTipoDocPorId($_id)
	{
		return contenidos_tipo_document::find($_id);
	}
	
	public static function traerSexoPorId($_id)
	{
		return contenidos_sex::find($_id);
	}
	
	public static function traerNacPorId($_id)
	{
		return contenidos_nacionalidade::find($_id);
	}
	
	public static function traerProvPorId($_id)
	{
		return contenidos_provincia::find($_id);
	}
	
	public static function traerLocPorId($_id)
	{
		return contenidos_localidade::find(array('conditions' => array('id_localidad = ?', $_id)));
	}
	
	public static function traerUniversidadPorId($_id)
	{
		return contenidos_universidade::find($_id);
	}
	
	public static function traerCarreraPorId($_id)
	{
		return contenidos_carrera::find($_id);
	}
	
	public static function traerCodRefPorId($_id)
	{
		return contenidos_trabajo::find($_id);
	}
	
	public static function traerAreaPorId($_id)
	{
		return contenidos_area::find($_id);
	}
	
	public static function traerSectoresPorId($_id)
	{
		return contenidos_sectore::find($_id);
	}
	
	
	public static function traerTrabajoPorId($_id)
	{
		return contenidos_trabajo::find($_id);
	}
	
	public static function traerDataPorId($_data, $_id)
	{
		if($_data == 'provincias'){
			$_valor = contenidos_provincia::find($_id)->nombre;
			return ucwords(strtolower($_valor));
		}else if($_data == 'localidades'){
			$_valor = contenidos_localidade::find(array('conditions' => array('id_localidad = ?', $_id)))->nombre;
			return ucwords(strtolower($_valor));
		}else if($_data == 'carreras'){
			$_valor = contenidos_carrera::find($_id)->nombre;
			return ucwords(strtolower($_valor));
		}else{
			return $_id;
		}
		
		//return contenidos_provincia::find($_id);
	}
	
	
	
	
	
	public function traerDatos($_val)
	{
		if($_val=='peluquerias'){
			return  contenidos_peluqueria::all();
		}else{
			return  contenidos_persona::all();
		}
		
			
	}
	
	public function traerDato($_val, $_id)
	{
		if($_val=='peluquerias'){
			return contenidos_peluqueria::find($_id);
		}else{
			return contenidos_persona::find($_id);
		}
		
	}
	
	
	public function traerPeluquerias()
	{
		return contenidos_peluqueria::all(array('order' => 'nombre asc'));
	}
	
	public static function traerPeluqueriaPorId($_id)
	{
		return contenidos_peluqueria::find($_id);
	}
	
	public function borrarPeluquerias($_id)
	{		
		$borrar = contenidos_peluqueria::find($_id);		
		$borrar->delete();
	}
	
	
	public function borrarPersonas($_id)
	{		
		$borrar = contenidos_persona::find($_id);		
		$borrar->delete();
	}
	
	
	/*private function armarArray($_val)
	{
		$arrayResult = array_map(function($res){
		  return $res->attributes();
		}, $_val);
		return $arrayResult;
	}*/
	
	
		
	
	
	
	
	
	public function eliminarImagenMedio($_id, $_modulo, $_pos, $_ruta, $_seccion)
	{
		$_editar = contenidos_imagene::find(array('conditions' => array('identificador = ? AND modulo = ? AND  posicion = ?', $_id, $_modulo, $_pos)));
		
		if($_editar){
			$_borrarImg = $this->EliminarImagenEnDirectorio($_ruta, $_editar->path, $_seccion);
			/*if($_borrarImg==true){
				return true;
			}*/
			$_editar->delete();
			
		}
		return true;
	}
	
	
	
	
	
	
	public function borrarImagenesModulo($_ruta, $_seccion, $_modulo)
	{		
		//$borrar = contenidos_imagene::find($_id);
		$imgBorrar = contenidos_imagene::find('all',array('conditions' => array('modulo = ?', $_modulo)));
		if($imgBorrar && is_array($imgBorrar)){
			foreach($imgBorrar as $idss){
				$dat_por_identificador[] = $idss->id;
			}		
			$this->eliminarImagenes($dat_por_identificador, $_ruta, $_seccion);
		}
		
		
	}
	
	
	
	public static function cadenaAleatoriaSegura($_caracter = 8)
	{      
	    $_chars = 'bcdfghjklmnprstvwxzaeiou0123456789';
	    $_result = '';
	    
	    for($p = 0; $p < $_caracter; $p++){
	        $_result .= ($p%2) ? $_chars[mt_rand(19, 33)] : $_chars[mt_rand(0, 18)];
	    }
	    return $_result;
	}
	
	
	
	
	public function traerImagenesPorLote($_ids)
	{
		// return contenidos_imagene::find('all', array('conditions' => array('id IN (?)', $_ids)));
		 return contenidos_imagene::find_by_sql('SELECT * FROM contenidos_imagenes WHERE id IN ('.$_ids.')');
	}
	
	public static function traerDataGaleriaPorIdentificador($_identificador,$_pos)
	{
		return  contenidos_imagene::find('all',array('conditions' => array('identificador = ? AND posicion = ?', $_identificador, $_pos),'order' => 'id asc'));
	}
	
	public static function traerDataImagen($_id, $_pos)
	{
		//return  contenidos_imagene::find(array('conditions' => array('id = ?', $_id)));
		return  contenidos_imagene::find(array('conditions' => array('id = ? AND posicion = ?', $_id, $_pos)));
	}
	
	public static function traerDataImagenPorIdentificador($_identificador, $_pos)
	{
		return  contenidos_imagene::find(array('conditions' => array('identificador = ? AND posicion = ?', $_identificador, $_pos)));
	}
	
	
	
	public static function traerImgMod($_identificador, $_modulo, $_pos)
	{
		return  contenidos_imagene::find(array('conditions' => array('identificador = ? AND modulo = ? AND posicion = ?', $_identificador, $_modulo, $_pos)));
	}
	
	public static function traerImgMedio($_identificador, $_id)
	{
		return  contenidos_imagene::find(array('conditions' => array('identificador = ? AND medio = ?', $_identificador, $_id)));
	}
	
	
	public function traerDatosImagenes()
	{
		return  contenidos_datos_imagene::all();
	}
	
	public function traerDatosImagen($_id)
	{
		return contenidos_datos_imagene::find($_id);
	}
	
	public static function traerImgChica($_identificador)
	{
		return  contenidos_imagene::find(array('conditions' => array('identificador = ?', $_identificador)));
	}
	
	
	
	public function traerImgBuscador($_valor)
	{
		return contenidos_datos_imagene::find_by_sql('SELECT * FROM contenidos_datos_imagenes WHERE (titulo LIKE "%'.$_valor.'%" OR descripcion LIKE "%'.$_valor.'%"  OR dia LIKE "%'.$_valor.'%" ) ORDER BY id DESC');
		
	}
	
	
	public static function formatFechaSql($_fecha)
	{
		$_fecha = explode("-", $_fecha);
		return $_fecha[2] . "-" . $_fecha[1] . "-" . $_fecha[0];
	}
	
	////////////////////////////////////////////////////////////////////////////
	
	/*public function traerNoticias()
	{
		return  contenidos_blo::all(array('order' => 'orden asc'));
	}
	
	public function traerNoticia($_id)
	{
		return contenidos_blo::find($_id);
	}
	*/
	public static function ultimoOrdenBlog()
	{
		$orden = contenidos_blo::find(array('select' => 'max(orden) as orden'));
		if (isset($orden)){
			return $orden;
		}else{
			return 0;
		}
	}
	
	public function ordenarBlog($_orden)
	{
		$arrayOrden = contenidos_blo::find('all',array('conditions' => array('orden > ?', $_orden),'order' => 'orden asc'));
		for($i=0;$i<count($arrayOrden);$i++){
			$arrayOrden[$i]->orden = $arrayOrden[$i]->orden-1;
			$arrayOrden[$i]->save();		
		}
	}
	
	public function cambiarOrdenBlog($_id, $_orden)
	{			
		$act = contenidos_blo::find(array('conditions' => array('id = ?', $_id)));
		$act->orden = $_orden;
		$act->save();
	}
	
	public function borrarBlog($_id, $_ruta, $_seccion)
	{		
		$borrar = contenidos_blo::find($_id);	
		$this->ordenarBlog($borrar->orden);				
		$imgBorrar = contenidos_imagene::find('all',array('conditions' => array('identificador = ?', $borrar->identificador)));
		if($imgBorrar && is_array($imgBorrar)){
			foreach($imgBorrar as $idss){
				$dat_por_identificador[] = $idss->id;
			}		
			$this->eliminarImagenes($dat_por_identificador, $_ruta, $_seccion);
		}
		$borrar->delete();
	}
	
	
	
	
	
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	/*public function traerSliders()
	{
		return  contenidos_slider::all(array('order' => 'orden asc'));
	}
	
	public function traerSlider($_id)
	{
		return contenidos_slider::find($_id);
	}
	
	
	public function cambiarOrdenSlider($_id, $_orden)
	{
			
		$act = contenidos_slider::find(array('conditions' => array('id = ?', $_id)));
		$act->orden = $_orden;
		$act->save();
	}
	
	public static function ultimoOrdenSlider()
	{
		$orden = contenidos_slider::find(array('select' => 'max(orden) as orden'));
		if (isset($orden)){
			return $orden;
		}else{
			return 0;
		}
	}
	
	public function ordenarSlider($_orden)
	{
		$arrayOrden = contenidos_slider::find('all',array('conditions' => array('orden > ?', $_orden),'order' => 'orden asc'));
		for($i=0;$i<count($arrayOrden);$i++){
			$arrayOrden[$i]->orden = $arrayOrden[$i]->orden-1;
			$arrayOrden[$i]->save();		
		}
	}
	
	public function borrarSlider($_id, $_ruta, $_seccion)
	{		
		$borrar = contenidos_slider::find($_id);
		$this->ordenarSlider($borrar->orden);
				
		$imgBorrar = contenidos_imagene::find('all',array('conditions' => array('identificador = ?', $borrar->identificador)));
		if($imgBorrar && is_array($imgBorrar)){
			foreach($imgBorrar as $idss){
				$dat_por_identificador[] = $idss->id;
			}		
			$this->eliminarImagenes($dat_por_identificador, $_ruta, $_seccion);
		}
		$borrar->delete();
	}*/
	
	
	public function traerEquipoCompleto()
	{
		return  contenidos_equip::all(array('order' => 'orden asc'));
	}
	
	public function traerEquipo($_id)
	{
		return contenidos_equip::find($_id);
	}
	
	
	public function cambiarOrdenEquipo($_id, $_orden)
	{
			
		$act = contenidos_equip::find(array('conditions' => array('id = ?', $_id)));
		$act->orden = $_orden;
		$act->save();
	}
	
	public static function ultimoOrdenEquipo()
	{
		$orden = contenidos_equip::find(array('select' => 'max(orden) as orden'));
		if (isset($orden)){
			return $orden;
		}else{
			return 0;
		}
	}
	
	public function ordenarEquipo($_orden)
	{
		$arrayOrden = contenidos_equip::find('all',array('conditions' => array('orden > ?', $_orden),'order' => 'orden asc'));
		for($i=0;$i<count($arrayOrden);$i++){
			$arrayOrden[$i]->orden = $arrayOrden[$i]->orden-1;
			$arrayOrden[$i]->save();		
		}
	}
	
	public function borrarEquipo($_id, $_ruta, $_seccion)
	{		
		$borrar = contenidos_equip::find($_id);	
		$this->ordenarEquipo($borrar->orden);				
		$imgBorrar = contenidos_imagene::find('all',array('conditions' => array('identificador = ?', $borrar->identificador)));
		if($imgBorrar && is_array($imgBorrar)){
			foreach($imgBorrar as $idss){
				$dat_por_identificador[] = $idss->id;
			}		
			$this->eliminarImagenes($dat_por_identificador, $_ruta, $_seccion);
		}
		$borrar->delete();
	}
	
	
	public function traerSoluciones()
	{
		return  contenidos_solucione::all(array('order' => 'orden asc'));
	}
	
	public function traerSolucion($_id)
	{
		return contenidos_solucione::find($_id);
	}
	
	public static function traerEstadosSoluciones($_identificador, $modulo)
	{
		return  contenidos_estados_solucione::find(array('conditions' => array('id_solucion = ? AND modulo = ?', $_identificador, $modulo)));
	}
	
	public static function traerModulosSolucion($_identificador, $modulo)
	{
		$_dato = contenidos_modulos_solucione::find(array('conditions' => array('identificador = ? AND modulo = ?', $_identificador, $modulo)));
		return (isset($_dato)) ? $_dato : '';
	}
	
	
	public static function ultimoOrdenSoluciones()
	{
		$orden = contenidos_solucione::find(array('select' => 'max(orden) as orden'));
		if (isset($orden)){
			return $orden;
		}else{
			return 0;
		}
	}
	
	public function cambiarOrdenSoluciones($_id, $_orden)
	{
			
		$act = contenidos_solucione::find(array('conditions' => array('id = ?', $_id)));
		$act->orden = $_orden;
		$act->save();
	}
	
	public function ordenarSoluciones($_orden)
	{
		$arrayOrden = contenidos_solucione::find('all',array('conditions' => array('orden > ?', $_orden),'order' => 'orden asc'));
		for($i=0;$i<count($arrayOrden);$i++){
			$arrayOrden[$i]->orden = $arrayOrden[$i]->orden-1;
			$arrayOrden[$i]->save();		
		}
	}
	
	public function borrarSoluciones($_id, $_ruta, $_seccion)
	{		
		$borrar = contenidos_solucione::find($_id);	
		$this->ordenarSoluciones($borrar->orden);				
		$imgBorrar = contenidos_imagene::find('all',array('conditions' => array('identificador = ?', $borrar->identificador)));
		if($imgBorrar && is_array($imgBorrar)){
			foreach($imgBorrar as $idss){
				$dat_por_identificador[] = $idss->id;
			}		
			$this->eliminarImagenes($dat_por_identificador, $_ruta, $_seccion);
		}
		
		$borrar_modulos = contenidos_modulos_solucione::find('all',array('conditions' => array('identificador = ?', $borrar->identificador)));
		if($borrar_modulos && is_array($borrar_modulos)){
			foreach($borrar_modulos as $mod){
				$mod->delete();
			}		
		}
		
		$borrar_estados = contenidos_estados_solucione::find('all',array('conditions' => array('id_solucion = ?', $borrar->identificador)));
		if($borrar_estados && is_array($borrar_estados)){
			foreach($borrar_estados as $est){
				$est->delete();
			}		
		}
		$borrar->delete();
	}
	
	
	
	
	
	
	
	public function traerCasos()
	{
		return  contenidos_caso::all(array('order' => 'orden asc'));
	}
	
	public function traerCaso($_id)
	{
		return contenidos_caso::find($_id);
	}
	
	public static function ultimoOrdenCasos()
	{
		$orden = contenidos_caso::find(array('select' => 'max(orden) as orden'));
		if (isset($orden)){
			return $orden;
		}else{
			return 0;
		}
	}
	
	public function cambiarOrdenCasos($_id, $_orden)
	{			
		$act = contenidos_caso::find(array('conditions' => array('id = ?', $_id)));
		$act->orden = $_orden;
		$act->save();
	}
	
	public function ordenarCasos($_orden)
	{
		$arrayOrden = contenidos_caso::find('all',array('conditions' => array('orden > ?', $_orden),'order' => 'orden asc'));
		for($i=0;$i<count($arrayOrden);$i++){
			$arrayOrden[$i]->orden = $arrayOrden[$i]->orden-1;
			$arrayOrden[$i]->save();		
		}
	}
	
	public function borrarCasos($_id, $_ruta, $_seccion)
	{		
		$borrar = contenidos_caso::find($_id);
		$_borrar_medios = contenidos_medio::find('all',array('conditions' => array('identificador = ?', $borrar->identificador)));
		if($_borrar_medios){
			foreach($_borrar_medios as $b){
				$b->delete();	
			}
		}
		
		$this->ordenarCasos($borrar->orden);				
		$imgBorrar = contenidos_imagene::find('all',array('conditions' => array('identificador = ?', $borrar->identificador)));
		if($imgBorrar && is_array($imgBorrar)){
			foreach($imgBorrar as $idss){
				$dat_por_identificador[] = $idss->id;
			}		
			$this->eliminarImagenes($dat_por_identificador, $_ruta, $_seccion);
		}
		$borrar->delete();
	}
	
	public static function ultimoOrdenMedios($_identificador)
	{
		$orden = contenidos_medio::find(array('conditions' => array('identificador = ?', $_identificador),'select' => 'max(orden) as orden'));
		if (isset($orden)){
			return $orden;
		}else{
			return 0;
		}
	}
	
	public function traerMedios($_identificador)
	{
		return  contenidos_medio::find('all',array('conditions' => array('identificador = ?', $_identificador),'order' => 'orden asc'));
	}
	
	
	public function borrarMedio($_id, $_ruta, $_seccion)
	{		
		$borrar = contenidos_medio::find($_id);	
		
		if($borrar->orden == 1){
			$_pos = 5;		
		} else if($borrar->orden == 2){
			$_pos = 6;		
		}else if($borrar->orden == 3){
			$_pos = 7;		
		}		
		
			
		
		$_eliminar_img = $this->eliminarImagenMedio($borrar->identificador, 0, $_pos, $_ruta, $_seccion);			
		if($_eliminar_img==true){
			$this->ordenarMedios($borrar->identificador, $borrar->orden);
		}
		/*$imgBorrar = contenidos_imagene::find('all',array('conditions' => array('identificador = ?', $borrar->identificador)));
		if($imgBorrar && is_array($imgBorrar)){
			foreach($imgBorrar as $idss){
				$dat_por_identificador[] = $idss->id;
			}		
			$this->eliminarImagenes($dat_por_identificador, $_ruta, $_seccion);
		}*/
		$borrar->delete();
	}
	
	
	public function ordenarMedios($_id, $_orden)
	{
		$_arrayOrden = contenidos_medio::find('all',array('conditions' => array('identificador = ?  AND orden > ?',$_id, $_orden),'order' => 'orden asc'));
		if($_arrayOrden && is_array($_arrayOrden)){
			for($i=0;$i<count($_arrayOrden);$i++){
				$this->ordenarImgMedios($_id, $_arrayOrden[$i]->orden);
				//$dat_por_identificador[] = $arrayOrden[$i]->orden;
				$_arrayOrden[$i]->orden = $_arrayOrden[$i]->orden-1;
				$_arrayOrden[$i]->save();		
			}
		}
		/*foreach($dat_por_identificador as $dato){
			$this->ordenarImgMedios($_id, $dato);
		}
		*/
		
	}
	
	public function ordenarImgMedios($_id, $_orden)
	{
		if($_orden == 1){
			$_pos = 5;		
		} else if($_orden == 2){
			$_pos = 6;		
		}else if($_orden == 3){
			$_pos = 7;		
		}	
		
		$orden = contenidos_imagene::find(array('conditions' => array('identificador = ? AND posicion = ?', $_id, $_pos)));
		if($orden){
			$orden->posicion = $orden->posicion-1;
			$orden->save();		
		}
	}
	
	
	
	
	// Encuestas

	public function traerCatPreguntas()
	{
		$result = Pd::instancia()->prepare("SELECT cs.* FROM `contenidos_encuesta_categorias` as cs ORDER BY cs.id ASC");
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
			
	}

	public function traerPreguntas()
	{
		$result = Pd::instancia()->prepare("SELECT cs.* FROM `contenidos_encuesta_preguntas` as cs ORDER BY cs.id ASC");
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
			
	}
	
	public static function traerPregPorCat($_id)
	{
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cc.* FROM `contenidos_encuesta_preguntas` as cc WHERE cc.id_categoria = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerEncuestaRegistros()
	{
		$result = Pd::instancia()->prepare("SELECT cs.* FROM `contenidos_encuesta_registros` as cs ORDER BY cs.fecha_hora DESC");
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
			
	}
	
	
	public function traerBuscadorEncuesta($_valor)
	{
		//return contenidos_user::find_by_sql('SELECT * FROM contenidos_users WHERE numero_cliente = "'.$_valor.'" OR (razon_social LIKE "%'.$_valor.'%") ORDER BY id DESC');
		$result = Pd::instancia()->prepare("SELECT cer.* FROM `contenidos_encuesta_registros` as cer WHERE cer.id LIKE '%".$_valor."%' OR cer.fecha LIKE '%".$_valor."%' ORDER BY cer.fecha_hora DESC");
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
		
	}
	
	public function traerEncuestaRegistro($_id)
	{
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cc.*, cer.nombre_instructor, cer.id_sucursal FROM `contenidos_encuesta_registros` as cc 
											INNER JOIN `contenidos_encuesta_respuestas` as cer 
											ON cc.id = cer.id_encuesta
											WHERE cc.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}
	
	public function traerEncuesta($_id)
	{
		// return contenidos_pedido::find($_id);
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cer.* FROM `contenidos_encuesta_respuestas` as cer WHERE cer.id_encuesta = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerEncuestaPuntaje($_id)
	{
		// return contenidos_pedido::find($_id);
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT SUM(cer.respuesta) as 'total' FROM `contenidos_encuesta_respuestas` as cer WHERE cer.id_encuesta = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}
	
	
	/*public static function traerEncuestaRespuesta($_id_encuesta, $_id_cat, $_id_preg)
	{
		$_id_encuesta = (int) $_id_encuesta;
		$_id_cat = (int) $_id_cat;
		$_id_preg = (int) $_id_preg;
		$result = Pd::instancia()->prepare("SELECT cer.* FROM `contenidos_encuesta_respuestas` as cer WHERE cer.id_encuesta = :id_encuesta AND cer.id_categoria = :id_cat AND cer.id_pregunta = :id_preg");
		$result->execute(array(":id_encuesta" => $_id_encuesta, ":id_cat" => $_id_cat, ":id_preg" => $_id_preg));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result['respuesta'] : false;
	}

	public static function traerEncuestaRespuestaFinal($_id_encuesta, $_id_cat, $_id_preg)
	{
		$_id_encuesta = (int) $_id_encuesta;
		$_id_cat = (int) $_id_cat;
		$_id_preg = (int) $_id_preg;
		$result = Pd::instancia()->prepare("SELECT cer.* FROM `contenidos_encuesta_respuestas` as cer WHERE cer.id_encuesta = :id_encuesta AND cer.id_categoria = :id_cat AND cer.id_pregunta = :id_preg");
		$result->execute(array(":id_encuesta" => $_id_encuesta, ":id_cat" => $_id_cat, ":id_preg" => $_id_preg));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result['respuesta_texto'] : false;
	}*/
	
	public static function traerEncuestaRespuesta($_id_encuesta, $_id_preg)
	{
		$_id_encuesta = (int) $_id_encuesta;
		$_id_preg = (int) $_id_preg;
		$result = Pd::instancia()->prepare("SELECT cer.* FROM `contenidos_encuesta_respuestas` as cer WHERE cer.id_encuesta = :id_encuesta AND cer.id_pregunta = :id_preg");
		$result->execute(array(":id_encuesta" => $_id_encuesta, ":id_preg" => $_id_preg));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result['respuesta'] : false;
	}

	public static function traerEncuestaRespuestaFinal($_id_encuesta, $_id_preg)
	{
		$_id_encuesta = (int) $_id_encuesta;
		$_id_preg = (int) $_id_preg;
		$result = Pd::instancia()->prepare("SELECT cer.* FROM `contenidos_encuesta_respuestas` as cer WHERE cer.id_encuesta = :id_encuesta AND cer.id_pregunta = :id_preg");
		$result->execute(array(":id_encuesta" => $_id_encuesta, ":id_preg" => $_id_preg));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result['respuesta_texto'] : false;
	}

	public function traerEncuestasPorFecha($_fecha1, $_fecha2)
	{

		$_data = Pd::instancia()->prepare("SELECT cer.* FROM `contenidos_encuesta_registros` as cer WHERE DATE(cer.fecha) >= :fecha1 AND DATE(cer.fecha) <= :fecha2 ORDER BY cer.id DESC");
		$_data->execute(array(":fecha1" => $_fecha1, ":fecha2" => $_fecha2));
		$_data = $_data->fetchAll(PDO::FETCH_ASSOC);

		return ($_data) ? $_data : false;
			
	}

	public function traerEncuestasPorCat($_fecha1, $_fecha2)
	{

		$_lanz = Pd::instancia()->prepare("SELECT cs.id_categoria, cec.categoria, SUM(cs.respuesta) as 'sum_respuesta' 
											FROM `contenidos_encuesta_registros` as cp
											LEFT JOIN `contenidos_encuesta_respuestas` as cs
											ON cp.id = cs.id_encuesta
											LEFT JOIN `contenidos_encuesta_categorias` as cec
											ON cs.id_categoria = cec.id
											WHERE DATE(cp.fecha) >= :fecha1 AND DATE(cp.fecha) <= :fecha2
											GROUP BY cs.id_categoria
											ORDER BY sum_respuesta DESC");

		$_lanz->execute(array(":fecha1" => $_fecha1, ":fecha2" => $_fecha2));
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
			
	}

	public function traerEncuestasPorSuc($_fecha1, $_fecha2)
	{

		$_lanz = Pd::instancia()->prepare("SELECT cs.id_categoria,cs.id_sucursal, css.nombre, SUM(cs.respuesta) as 'sum_respuesta' 
											FROM `contenidos_encuesta_registros` as cp
											LEFT JOIN `contenidos_encuesta_respuestas` as cs
											ON cp.id = cs.id_encuesta
											LEFT JOIN `contenidos_sucursales` as css
											ON cs.id_sucursal = css.id
											WHERE DATE(cp.fecha) >= :fecha1 AND DATE(cp.fecha) <= :fecha2
											GROUP BY cs.id_sucursal
											ORDER BY sum_respuesta DESC");

		$_lanz->execute(array(":fecha1" => $_fecha1, ":fecha2" => $_fecha2));
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
			
	}

	public function traerEncuestasPorPreg($_fecha1, $_fecha2)
	{

		$_lanz = Pd::instancia()->prepare("SELECT cs.id_categoria, cs.id_pregunta, cep.pregunta, cep.cant_resp, SUM(cs.respuesta) as 'sum_respuesta' 
											FROM `contenidos_encuesta_registros` as cp
											LEFT JOIN `contenidos_encuesta_respuestas` as cs
											ON cp.id = cs.id_encuesta
											LEFT JOIN `contenidos_encuesta_preguntas` as cep
											ON cs.id_pregunta = cep.id
											WHERE DATE(cp.fecha) >= :fecha1 AND DATE(cp.fecha) <= :fecha2
											GROUP BY cs.id_pregunta
											ORDER BY sum_respuesta DESC");

		$_lanz->execute(array(":fecha1" => $_fecha1, ":fecha2" => $_fecha2));
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
			
	}
	
	
	
	///////////////////////////////////////////////////////////////////////////////
	
	
	
	
	
	public function traerBeneficios($_estado)
	{
		return  contenidos_beneficio::find('all',array('conditions' => array('estado = ?', $_estado),'order' => 'orden asc'));
			
	}
	
	public function traerBeneficio($_id)
	{
		return contenidos_beneficio::find($_id);
	}
	
	public static function ultimoOrdenBeneficios()
	{
		$orden = contenidos_beneficio::find(array('select' => 'max(orden) as orden'));
		if (isset($orden)){
			return $orden;
		}else{
			return 0;
		}
	}
	
	public function ordenarBeneficios($_orden)
	{
		$arrayOrden = contenidos_beneficio::find('all',array('conditions' => array('orden > ? AND estado = ?', $_orden,2),'order' => 'orden asc'));
		for($i=0;$i<count($arrayOrden);$i++){
			$arrayOrden[$i]->orden = $arrayOrden[$i]->orden-1;
			$arrayOrden[$i]->save();		
		}
	}
	
	public static function traerImgPpal($_identificador)
	{
		return  contenidos_imagene::find(array('conditions' => array('identificador = ? AND orden = ?', $_identificador,0)));
	}
	
	public function cambiarOrdenBeneficios($_id, $_orden)
	{
			
		$act = contenidos_beneficio::find($_id);
		$act->orden = $_orden;
		$act->save();
	}
	
	public function traerManuales()
	{
		return  contenidos_manuale::all(array('order' => 'orden asc'));
			
	}
	
	public function traerManual($_id)
	{
		return contenidos_manuale::find($_id);
	}
	
	public static function ultimoOrdenManuales()
	{
		$orden = contenidos_manuale::find(array('select' => 'max(orden) as orden'));
		if (isset($orden)){
			return $orden;
		}else{
			return 0;
		}
	}
	
	public function ordenarManuales($_orden)
	{
		$arrayOrden = contenidos_manuale::find('all',array('conditions' => array('orden > ?', $_orden),'order' => 'orden asc'));
		for($i=0;$i<count($arrayOrden);$i++){
			$arrayOrden[$i]->orden = $arrayOrden[$i]->orden-1;
			$arrayOrden[$i]->save();		
		}
	}
	
	public function cambiarOrdenManuales($_id, $_orden)
	{
			
		$act = contenidos_manuale::find($_id);
		$act->orden = $_orden;
		$act->save();
	}
	
	public static function traerArchivo($_identificador)
	{
		return  contenidos_archivo::find(array('conditions' => array('identificador = ?', $_identificador)));
	}
	
	
	public function borrarManual($_id, $_ruta)
	{		
		$borrar = contenidos_manuale::find($_id);		
		self::ordenarManual($borrar->orden);
		
		/*$archivoBorrar = contenidos_archivo::find(array('conditions' => array('identificador = ?', $borrar->identificador)));
		$this->eliminarArchivos($archivoBorrar->id, $_rutaArchivos);*/
				
		$imgBorrar = contenidos_imagene::find('all',array('conditions' => array('identificador = ?', $borrar->identificador)));
		if($imgBorrar && is_array($imgBorrar)){
			foreach($imgBorrar as $idss){
				$dat_por_identificador[] = $idss->id;
			}
		}
		$this->eliminarImagenes($dat_por_identificador, $_ruta, 'manuales');
		
		$borrar->delete();
	}
	
	public static function ordenarManual($_orden)
	{
		$arrayOrden = contenidos_manuale::find('all',array('conditions' => array('orden > ?', $_orden),'order' => 'orden asc'));
		for($i=0;$i<count($arrayOrden);$i++){
			$arrayOrden[$i]->orden = $arrayOrden[$i]->orden-1;
			$arrayOrden[$i]->save();		
		}
	
	}
	
	
	public function traerCapacitaciones()
	{
		return  contenidos_capacitacione::all(array('order' => 'orden asc'));
			
	}
	
	public function traerCapacitacion($_id)
	{
		return contenidos_capacitacione::find($_id);
	}
	
	public static function ultimoOrdenCapacitacion()
	{
		$orden = contenidos_capacitacione::find(array('select' => 'max(orden) as orden'));
		if (isset($orden)){
			return $orden;
		}else{
			return 0;
		}
	}
	
	public function ordenarCapacitaciones($_orden)
	{
		$arrayOrden = contenidos_capacitacione::find('all',array('conditions' => array('orden > ?', $_orden),'order' => 'orden asc'));
		for($i=0;$i<count($arrayOrden);$i++){
			$arrayOrden[$i]->orden = $arrayOrden[$i]->orden-1;
			$arrayOrden[$i]->save();		
		}
	}
	public function cambiarOrdenCapacitaciones($_id, $_orden)
	{
			
		$act = contenidos_capacitacione::find($_id);
		$act->orden = $_orden;
		$act->save();
	}
	
	public function borrarCapacitacion($_id, $_ruta)
	{		
		$borrar = contenidos_capacitacione::find($_id);		
		self::ordenarCapacitacion($borrar->orden);		
				
		$imgBorrar = contenidos_imagene::find('all',array('conditions' => array('identificador = ?', $borrar->identificador)));
		if($imgBorrar && is_array($imgBorrar)){
			foreach($imgBorrar as $idss){
				$dat_por_identificador[] = $idss->id;
			}
		}
		$this->eliminarImagenes($dat_por_identificador, $_ruta, 'capacitaciones');
		
		$borrar->delete();
	}
	
	public static function ordenarCapacitacion($_orden)
	{
		$arrayOrden = contenidos_capacitacione::find('all',array('conditions' => array('orden > ?', $_orden),'order' => 'orden asc'));
		for($i=0;$i<count($arrayOrden);$i++){
			$arrayOrden[$i]->orden = $arrayOrden[$i]->orden-1;
			$arrayOrden[$i]->save();		
		}
	
	}
	
	/*public function traerDestacados()
	{
		return  contenidos_destacado::all(array('order' => 'orden asc'));
			
	}*/
	
	public function traerDestacado($_id)
	{
		return contenidos_destacado::find($_id);
	}
	
	public static function ultimoOrdenDestacado()
	{
		$orden = contenidos_destacado::find(array('select' => 'max(orden) as orden'));
		if (isset($orden)){
			return $orden;
		}else{
			return 0;
		}
	}
	
	public function ordenarDestacados($_orden)
	{
		$arrayOrden = contenidos_destacado::find('all',array('conditions' => array('orden > ?', $_orden),'order' => 'orden asc'));
		for($i=0;$i<count($arrayOrden);$i++){
			$arrayOrden[$i]->orden = $arrayOrden[$i]->orden-1;
			$arrayOrden[$i]->save();		
		}
	}
	public function cambiarOrdenDestacados($_id, $_orden)
	{
			
		$act = contenidos_destacado::find($_id);
		$act->orden = $_orden;
		$act->save();
	}
	
	public function borrarDestacado($_id, $_ruta)
	{		
		$borrar = contenidos_destacado::find($_id);		
		self::ordenarDest($borrar->orden);		
				
		$imgBorrar = contenidos_imagene::find('all',array('conditions' => array('identificador = ?', $borrar->identificador)));
		if($imgBorrar && is_array($imgBorrar)){
			foreach($imgBorrar as $idss){
				$dat_por_identificador[] = $idss->id;
			}
		}
		$this->eliminarImagenes($dat_por_identificador, $_ruta, 'destacados');
		
		$borrar->delete();
	}
	
	public static function ordenarDest($_orden)
	{
		$arrayOrden = contenidos_destacado::find('all',array('conditions' => array('orden > ?', $_orden),'order' => 'orden asc'));
		for($i=0;$i<count($arrayOrden);$i++){
			$arrayOrden[$i]->orden = $arrayOrden[$i]->orden-1;
			$arrayOrden[$i]->save();		
		}
	
	}
	
	public function traerDestacadosModulos()
	{
		return  contenidos_destacados_modulo::all(array('order' => 'id desc'));
			
	}
	
	public function traerDestacadoModulo($_id)
	{
		return contenidos_destacados_modulo::find($_id);
	}
	
	public function traerDestacadoModuloActivo($_modulo)
	{
		return contenidos_destacados_modulo::find(array('conditions' => array('modulo = ?', $_modulo)));
	}
	
	
	
	/*public static function ordenarDestModulo($_orden)
	{
		$arrayOrden = contenidos_destacados_modulo::find('all',array('conditions' => array('orden > ?', $_orden),'order' => 'orden asc'));
		for($i=0;$i<count($arrayOrden);$i++){
			$arrayOrden[$i]->orden = $arrayOrden[$i]->orden-1;
			$arrayOrden[$i]->save();		
		}
	
	}*/
	
	
	public function traerUserAltaBuscador($_valor, $_estado)
	{
		return contenidos_user::find_by_sql('SELECT * FROM contenidos_users WHERE estado = '.$_estado.' AND (nombre LIKE "%'.$_valor.'%" OR nombre_serv_tecnico LIKE "%'.$_valor.'%"  OR localidad LIKE "%'.$_valor.'%" OR provincia LIKE "%'.$_valor.'%" OR telefono LIKE "%'.$_valor.'%" OR email LIKE "%'.$_valor.'%" OR num_credencial LIKE "%'.$_valor.'%") ORDER BY id DESC');
		
	}
	
	public function traerJuegos()
	{
		return  contenidos_juego::all(array('order' => 'orden asc'));
			
	}
	
	public function traerJuego($_id)
	{
		return contenidos_juego::find($_id);
	}
	
	public function cambiarOrdenJuegos($_id, $_orden)
	{
			
		$act = contenidos_juego::find($_id);
		$act->orden = $_orden;
		$act->save();
	}
	
	public static function ultimoOrdenJuegos()
	{
		$orden = contenidos_juego::find(array('select' => 'max(orden) as orden'));
		if (isset($orden)){
			return $orden;
		}else{
			return 0;
		}
	}
	
	
	public function borrarJuegos($_id)
	{		
		$borrar = contenidos_juego::find($_id);		
		self::ordenarJuego($borrar->orden);
		$borrar->delete();
	}
	
	public static function ordenarJuego($_orden)
	{
		$arrayOrden = contenidos_juego::find('all',array('conditions' => array('orden > ?', $_orden),'order' => 'orden asc'));
		for($i=0;$i<count($arrayOrden);$i++){
			$arrayOrden[$i]->orden = $arrayOrden[$i]->orden-1;
			$arrayOrden[$i]->save();		
		}
	
	}
	
	
	
	//////////////////////////////////////////////////////////////////////////////////////////
	
	public function traerPromos($_estado)
	{
		if($_estado==1){
			return  contenidos_promo::find('all',array('conditions' => array('estado = ?', $_estado),'order' => 'orden asc'));
		}else{
			return  contenidos_promo::find('all',array('conditions' => array('estado = ?', $_estado),'order' => 'id desc'));		
		}
			
	}
	
	public function traerPromo($_id)
	{
		return contenidos_promo::find($_id);
	}
	
	
	
		
	public static function traerImgFondo($_identificador)
	{
		return  contenidos_imagene::find(array('conditions' => array('identificador = ? AND orden = ?', $_identificador,1)));
	}
	
	public static function traerTermino($_identificador)
	{
		return  contenidos_termino::find(array('conditions' => array('identificador = ?', $_identificador)));
	}
	
	
	public static function excelRegistros($_id,$_ruta)
	{
		$registros = contenidos_promo::find($_id);
		$participantes  = contenidos_participante::find('all',array('conditions' => array('id_promo = ?', $registros->id)));
		$contenido ='';
		if($participantes){
			/*$contenido .="<table id='exportTabla'>
						<thead>
							<tr>
								<th>TITULO</th>
								<th>DESCRIPCION</th>
								<th>NOMBRE</th>
								<th>APELLIDO</th>
							</tr>
						</thead>
						<tbody>";
						
			foreach($participantes as $regs){
				$contenido .= "<tr><td>".html_entity_decode(ucwords(strtolower(Encriptar::decrypt($registros->titulo))))."</td>";
				$contenido .= "<td>".self::convertirCaracteres(self::tildesHtml(ucwords(strtolower(Encriptar::decrypt($registros->descripcion)))))."</td>";
				$contenido .= "<td>".self::convertirCaracteres(self::tildesHtml(ucwords(strtolower(Encriptar::decrypt($regs->nombre)))))."</td>";
				$contenido .= "<td>".self::convertirCaracteres(self::tildesHtml(ucwords(strtolower(Encriptar::decrypt($regs->apellido)))))."</td></tr>";
			}
			$contenido .="</tbody></table>";*/
			
			foreach($participantes as $regs){
				$contenido .= html_entity_decode(self::convertirCaracteres(self::tildesHtml(ucwords(strtolower(Encriptar::decrypt($registros->titulo))))));
				$contenido .= "\t".self::convertirCaracteres(self::tildesHtml(ucwords(strtolower(Encriptar::decrypt($registros->descripcion)))));
				$contenido .= "\t".self::convertirCaracteres(self::tildesHtml(ucwords(strtolower(Encriptar::decrypt($regs->nombre)))));
				$contenido .= "\t".self::convertirCaracteres(self::tildesHtml(ucwords(strtolower(Encriptar::decrypt($regs->apellido)))));
				$contenido .= "\t".Encriptar::decrypt($regs->dni);
				$contenido .= "\t".self::convertirCaracteres(self::tildesHtml(ucwords(strtolower(Encriptar::decrypt($regs->localidad)))));
				$contenido .= "\t".self::convertirCaracteres(self::tildesHtml(ucwords(strtolower(Encriptar::decrypt($regs->provincia)))));
				$contenido .= "\t".Encriptar::decrypt($regs->telefono);
				$contenido .= "\t".Encriptar::decrypt($regs->email);
				$contenido .= "\t".Encriptar::decrypt($regs->factura);
				$contenido .= "\t".self::convertirCaracteres(self::tildesHtml(ucwords(strtolower(Encriptar::decrypt($regs->compra)))))."\n";
			}
			
			$cabecera="TITULO\tDESCRIPCION\tNOMBRE\tAPELLIDO\tDNI\tLOCALIDAD\tPROVINCIA\tTELEFONO\tEMAIL\tNUMERO FACTURA\tDONDE REALIZASTE TU COMPRA\n";
			
		}else{
			$contenido .= self::convertirCaracteres(self::tildesHtml(ucwords(strtolower(Encriptar::decrypt($registros->titulo)))));
			$contenido .= "\t".self::convertirCaracteres(self::tildesHtml(ucwords(strtolower(Encriptar::decrypt($registros->descripcion)))))."\n";
			
			$cabecera="TITULO\tDESCRIPCION\n";
		}
		
		
		$nombre = "registros_promos_". $_id .".xls";
		$url = $_ruta.$nombre;
		//$url = $this->_conf['base_url']."public/descargas/".$nombre;
	
		$p=fopen("$url","w");
		if($p){			
			fwrite($p,$cabecera);
			fwrite($p,$contenido);
		}	
		fclose($p);
		
		return $nombre;
	}
	
	
	
	
	
	public function ordenarPromos($_orden)
	{
		$arrayOrden = contenidos_promo::find('all',array('conditions' => array('orden > ? AND estado = ?', $_orden,1),'order' => 'orden asc'));
		for($i=0;$i<count($arrayOrden);$i++){
			$arrayOrden[$i]->orden = $arrayOrden[$i]->orden-1;
			$arrayOrden[$i]->save();		
		}
	}
	
	//////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////
	
	
	
	public static function traerImgPorId($_id)
	{
		$_id = (int) $_id;
		return contenidos_imagene::find($_id);
	}
	
	public function traerImgPorIdentificador($_identificador)
	{
		return  contenidos_imagene::find(array('conditions' => array('identificador = ?', $_identificador)));
	}
	
	
	
	
	public static function ultimoOrden()
	{
		$orden = contenidos_noticia::find(array('select' => 'max(orden) as orden'));
		if (isset($orden)){
			return $orden;
		}else{
			return 0;
		}
	}
	
	
	public function imagenesVinculadasPorIdentificador($_identificador)
	{		
		// Traemos las imagenes por Identificador
		$por_identificador = contenidos_imagene::find('all',array('conditions' => array('identificador = ?', $_identificador)));
		
		// inicializamos los arreglos 
		$dat_por_identificador = array();	
		// Filtramos Ids por Identificadores
		if($por_identificador && is_array($por_identificador)){
			foreach($por_identificador as $idss){
				$dat_por_identificador[] = $idss->id;
			}
		}
		
		if(!empty($dat_por_identificador)){
			$ids = implode($dat_por_identificador,',');
		}else{
			$ids = '';	
		}
		return $ids;
	}
	
	public function traerImgPorIds($_ids)
	{
		//$dat_por_identificador = array();	
		return $ids= explode(',',$_ids);
	}
	
	public function eliminarArchivos($_elementos, $_ruta)
	{
		$_elemento = contenidos_archivo::find($_elementos); 
		
		if(is_readable($_ruta. $_elemento->path)){
			@unlink($_ruta. $_elemento->path);
		}
		$_elemento->delete();	
	}
	
	
	
	
	public function traerImagenes($_identificador)
	{
		$por_identificador = contenidos_imagene::find('all',array('conditions' => array('identificador = ?', $_identificador),'order' => 'orden asc'));
		
		return $por_identificador;
		
	}

	// Video y archivos

	public function subirVideo($_tipo, $_identificador, $_data, $_nombre, $_ruta)
	{
		//echo "<pre>";print_r($_FILES[$_data]);echo "</pre>";//exit;
		//echo $_FILES[$_data]['name'];exit;
		$_mode = 0777;
		$_nombre = htmlentities($_nombre, ENT_QUOTES);
		$_mime = $this->mimeContentType($_FILES[$_data]['name']);
		//$_nombreArchivo = $this->crearNombre($_nombre);
		$_nombreArchivo = self::cadenaAleatoriaSegura(15);
		if(!$_mime) throw new Exception("ERROR: Archivo Invalido");
		if($_mime[0] != $_tipo) throw new Exception("ERROR: Tipo de Archivo Incorrecto");
		
		$_temp  = $_FILES[$_data]['tmp_name'];
		if(!file_exists($_ruta)){
			mkdir($_ruta, 0777, true);
		}
		$_archivo = $_ruta . $_nombreArchivo . '.' . $_mime[0];
		/*echo "<pre>";print_r($_mime);echo "</pre>";//exit;
		echo $_archivo;*/
		$_vid_ant = self::traerVidId($_identificador);
		if($_vid_ant){
			if(file_exists($_ruta. $_vid_ant->path)){
				@unlink($_ruta. $_vid_ant->path);
			}
		}
		
		if(file_exists($_archivo)){
			@unlink($_archivo);
			@copy($_temp, $_archivo);
			@chmod($_archivo, $_mode);
			$_vid = $this->cargarDataVideo($_nombreArchivo.'.'.$_mime[0], $_nombre,$_identificador);
			return $_vid;
		}else{
			//@copy($_temp, $_archivo);
			move_uploaded_file($_temp,$_archivo);
			@chmod($_archivo, $_mode);
			$_vid = $this->cargarDataVideo($_nombreArchivo.'.'.$_mime[0], $_nombre,$_identificador);
			return $_vid;
		}
		throw new Exception("ERROR: al intentar subir el archivo");
	}
	
	public function editarVideo($_tipo, $_identificador, $_data, $_nombre, $_ruta)
	{
		$_mode = 0777;
		$_nombre = htmlentities($_nombre, ENT_QUOTES);
		$_mime = $this->mimeContentType($_FILES[$_data]['name']);
		//$_nombreArchivo = $this->crearNombre($_nombre);
		$_nombreArchivo = self::cadenaAleatoriaSegura(15);
		if(!$_mime) throw new Exception("ERROR: Archivo Invalido");
		if($_mime[0] != $_tipo) throw new Exception("ERROR: Tipo de Archivo Incorrecto");
		
		$_temp  = $_FILES[$_data]['tmp_name'];
		if(!file_exists($_ruta)){
			mkdir($_ruta, 0777, true);
		}
		$_archivo = $_ruta . $_nombreArchivo . '.' . $_mime[0];
		$_vid_ant = self::traerVidId($_identificador);
		if(file_exists($_ruta. $_vid_ant->path)){
			@unlink($_ruta. $_vid_ant->path);
		}
		if(file_exists($_archivo)){
			@unlink($_archivo);
			@copy($_temp, $_archivo);
			@chmod($_archivo, $_mode);
			$_vid = $this->cargarDataVideo($_nombreArchivo.'.'.$_mime[0], $_nombre,$_identificador);
			return $_vid;
		}else{
			@copy($_temp, $_archivo);
			@chmod($_archivo, $_mode);
			$_vid = $this->cargarDataVideo($_nombreArchivo.'.'.$_mime[0], $_nombre,$_identificador);
			return $_vid;
		}
		throw new Exception("ERROR: al intentar subir el archivo");
	}
	public function cargarDataVideo($_archivo, $_nombre, $_identificador)
	{
		$_nombre = htmlentities($_nombre, ENT_QUOTES);
		$_vid = self::traerVidId($_identificador);
		$_fechaBd = date('Y-m-d');
		if($_vid){
			//$video = new contenidos_video();
			$_vid->nombre = $_nombre;
			//$_vid->identificador = $_identificador;
			$_vid->path = $_archivo;
			//$_vid->orientacion = '';
			//$_vid->orden = 0;
			//$_vid->fecha_alt = date('Y-m-d');
			return ($_vid->save()) ? $_vid->id : false;
		}else{
			$video = new contenidos_video();
			$video->nombre = $_nombre;
			$video->identificador = $_identificador;
			$video->path = $_archivo;
			$video->orientacion = '';
			$video->orden = 0;
			$video->fecha_alt = "$_fechaBd";
			return ($video->save()) ? $video->id : false;
		}
		
		
	}
	public function traerDataVideo($_id)
	{
		return  contenidos_video::find(array('conditions' => array('id = ?', $_id)));
	}
	public static function traerVidId($_identificador)
	{
		return  contenidos_video::find(array('conditions' => array('identificador = ?', $_identificador)));
	}
	public static function traerArchivoId($_identificador)
	{
		return  contenidos_archivo::find(array('conditions' => array('identificador = ?', $_identificador)));
	}

	public static function traerArchivosIdentificador($_identificador)
	{
		return  contenidos_archivo::find('all',array('conditions' => array('identificador = ?', $_identificador)));
	}

	public function subirArchivo($_tipo, $_identificador, $_data, $_nombre, $_ruta)
	{
		//echo "<pre>";print_r($_FILES[$_data]);echo "</pre>";//exit;
		//echo $_FILES[$_data]['name'];exit;
		$_mode = 0777;
		$_nombre = htmlentities($_nombre, ENT_QUOTES);
		$_mime = $this->mimeContentType($_FILES[$_data]['name']);
		//$_nombreArchivo = $this->crearNombre($_nombre);
		$_nombreArchivo = self::cadenaAleatoriaSegura(15);
		if(!$_mime) throw new Exception("ERROR: Archivo Invalido");
		if($_mime[0] != $_tipo) throw new Exception("ERROR: Tipo de Archivo Incorrecto");
		
		$_temp  = $_FILES[$_data]['tmp_name'];
		if(!file_exists($_ruta)){
			mkdir($_ruta, 0777, true);
		}
		$_archivo = $_ruta . $_nombreArchivo . '.' . $_mime[0];
		/*echo "<pre>";print_r($_mime);echo "</pre>";//exit;
		echo $_archivo;*/
		/*$_vid_ant = self::traerArchivoId($_identificador);
		if($_vid_ant){
			if(file_exists($_ruta. $_vid_ant->path)){
				@unlink($_ruta. $_vid_ant->path);
			}
		}*/
		
		if(file_exists($_archivo)){
			@unlink($_archivo);
			@copy($_temp, $_archivo);
			@chmod($_archivo, $_mode);
			$_vid = $this->cargarDataArchivo($_nombreArchivo.'.'.$_mime[0], $_tipo, $_nombre,$_identificador);
			return $_vid;
		}else{
			//@copy($_temp, $_archivo);
			move_uploaded_file($_temp,$_archivo);
			@chmod($_archivo, $_mode);
			$_vid = $this->cargarDataArchivo($_nombreArchivo.'.'.$_mime[0], $_tipo, $_nombre,$_identificador);
			return $_vid;
		}
		throw new Exception("ERROR: al intentar subir el archivo");
	}
	

	public function cargarDataArchivo($_archivo, $_formato, $_nombre, $_identificador)
	{
		$_nombre = htmlentities($_nombre, ENT_QUOTES);
		// $_vid = self::traerArchivoId($_identificador);
		// $_fechaBd = date('Y-m-d');
		/*if($_vid){
			//$video = new contenidos_video();
			$_vid->nombre = $_nombre;
			//$_vid->identificador = $_identificador;
			$_vid->path = $_archivo;
			$_vid->formato = $_formato;
			//$_vid->orientacion = '';
			//$_vid->orden = 0;
			//$_vid->fecha_alt = date('Y-m-d');
			return ($_vid->save()) ? $_vid->id : false;
		}else{
			$video = new contenidos_archivo();
			$video->nombre = $_nombre;
			$video->identificador = $_identificador;
			$video->path = $_archivo;
			$video->formato = $_formato;
			$video->orientacion = '';
			$video->orden = 0;
			$video->fecha_alt = "$_fechaBd";
			return ($video->save()) ? $video->id : false;
		}*/
		$video = new contenidos_archivo();
		$video->nombre = $_nombre;
		$video->identificador = $_identificador;
		$video->path = $_archivo;
		$video->formato = $_formato;
		$video->orientacion = '';
		$video->orden = 0;
		$video->fecha_alt = date('Y-m-d');
		return ($video->save()) ? $video->id : false;
		
		
	}

	public function traerDataArchivo($_id)
	{
		return  contenidos_archivo::find(array('conditions' => array('id = ?', $_id)));
	}

	public function borrarArchivo($_id, $_ruta)
	{		
		
		$borrar = contenidos_archivo::find(array('conditions' => array('id = ?', $_id)));		
		if($borrar){
			$_archivo = $_ruta . $borrar->path;
			if(file_exists($_archivo)){
				@unlink($_archivo);
			}
			$borrar->delete();
		}
		
		
		return true;		
	}

	public function borrarArchivoPorIdentificador($_identificador, $_ruta)
	{		
		
		$borrar = contenidos_archivo::find(array('conditions' => array('identificador = ?', $_identificador)));			
		if($borrar){
			$_archivo = $_ruta . $borrar->path;
			if(file_exists($_archivo)){
				@unlink($_archivo);
			}
			$borrar->delete();
		}
		
		
		return true;		
	}



	public function generarZip($source, $destination)
	{

	    if(!extension_loaded('zip') || !file_exists($source)) {
	        // die("error: zip extension or error source");
	        return false;
	    }

	    if(file_exists($destination)){
	    	unlink($destination);
	    }

	    $zip = new ZipArchive();
	    if(!$zip->open($destination, ZIPARCHIVE::CREATE)) {
	        // die("error: zip file create");
	        return false;
	    }

	    /*if (PHP_OS != "Linux") {
	        $source = str_replace('\\', '/', realpath($source));
	    }*/

	    if(is_dir($source) === true){
	        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
	        foreach ($files as $file){
	            $file = str_replace('\\', '/', $file);
	            // Ignore "." and ".." folders
	            if(in_array(substr($file, strrpos($file, '/')+1), array('.', '..'))){
	                continue;
	            }

	            $file = realpath($file);
	            if(is_dir($file) === true){
	                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
	            }else if(is_file($file) === true){
	                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
	            }
	        }
	    }else if(is_file($source) === true){
	        $zip->addFromString(basename($source), file_get_contents($source));
	    }

	    $zip->close();

	    return true;
	}




	///////////////////////////////////////////////////////
	
	public static function quitarTags($cadena)
	{
		return str_replace(array('<p>','</p>'),array('',''),$cadena);
	}
	
	public static function tildesHtml($cadena) 
	{ 
		return str_replace(array("á","é","í","ó","ú","ñ","Á","É","Í","Ó","Ú","Ñ"),
							array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&ntilde;","&Aacute;","&Eacute;","&Iacute;","&Oacute;","&Uacute;","&Ntilde;"),
							$cadena);     
	}
	
	public static function cortarTexto($texto,$tamano,$colilla="...")
	{
		$texto=substr($texto, 0,$tamano);
		$index=strrpos($texto, " ");
		$texto=substr($texto, 0,$index); $texto.=$colilla;
		return $texto;
	} 
	
	
	public static function CapturarAnchoAlto($recurso)											
	{
		$dimension = array();
		
		list($ancho, $alto) = getimagesize($recurso);
		$dimension[] = $ancho;
		$dimension[] = $alto;
		return $dimension;
	}
	
	public static function crearTitulo($_titulo)
    {
        $_titulo = strtolower($_titulo);
        return str_replace(" ","-",$_titulo);
    }

    public static function crearTitulo2($_titulo)
    {       
		$_titulo = mb_strtolower ($_titulo, 'UTF-8');
		$find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
		$repl = array('a', 'e', 'i', 'o', 'u', 'n');
		$_titulo = str_replace ($find, $repl, $_titulo);
		$find = array(' ', '&', '\r\n', '\n', '+');
		$_titulo = str_replace ($find, '-', $_titulo);
		$find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
		$repl = array('', '-', '');
		$_titulo = preg_replace ($find, $repl, $_titulo);
		return $_titulo;
	
    }

    public static function convertirNumTxt($_val)
    {

    	switch ($_val) {
		  case 'fondo':
		    return 'uno';
		    break;
		  case 'png':
		    return 'tres';
		    break;
		  default:
		    return 'uno';
		}

    }

    public static function calcular_edad($fechanacimiento){

    	if(strpos($fechanacimiento, '/') !== false){
		    list($ano,$mes,$dia) = explode("/",$fechanacimiento);
		} else{
		    list($ano,$mes,$dia) = explode("-",$fechanacimiento);
		}
		
		$ano_diferencia  = date("Y") - $ano;
		$mes_diferencia = date("m") - $mes;
		$dia_diferencia   = date("d") - $dia;
		if ($dia_diferencia < 0 || $mes_diferencia < 0)
		$ano_diferencia--;
		return $ano_diferencia;
	}

	public function calculateChildSupport($_id_form)
	{

		// $_id_form = 1693;

		$_form =  self::traerFormsPorIdStatic($_id_form);

		if($_form['wife_monthly_income']!='' || $_form['husband_monthly_income']!=''){

			if($_form['wife_monthly_income']!=''){
				$_form['wife_monthly_income'] = unserialize(base64_decode($_form['wife_monthly_income']));
				$_mo_icome = array_chunk($_form['wife_monthly_income'], 10, true);
				unset($_mo_icome[1]['WifeFinancial-StateIndustrialInsurance']);
				$_mo_icome_sum = array_sum($_mo_icome[0]);
				$_mo_icome_res = array_sum($_mo_icome[1]);
				$_mother = $_mo_icome_sum - $_mo_icome_res;
			}else{
				$_mother = 0;
			}

			if($_form['husband_monthly_income']!=''){
				$_form['husband_monthly_income'] = unserialize(base64_decode($_form['husband_monthly_income']));
				$_fa_icome = array_chunk($_form['husband_monthly_income'], 10, true);
				unset($_fa_icome[1]['HusbandFinancial-StateIndustrialInsurance']);
				$_fa_icome_sum = array_sum($_fa_icome[0]);
				$_fa_icome_res = array_sum($_fa_icome[1]);
				$_father = $_fa_icome_sum - $_fa_icome_res;
			}else{
				$_father = 0;
			}


		}else{
			$_father = 0;
			$_mother = 0;
		}


		if($_form['children_from_the_marriage']!=''){

			$_form['children_from_the_marriage'] = unserialize(base64_decode($_form['children_from_the_marriage']));			
			$_children = count($_form['children_from_the_marriage']['child-firstname']);
		}else{
			$_children = 0;
		}



		if($_form['parenting_income_tax_exemptions']!=''){

			$_form['parenting_income_tax_exemptions'] = unserialize(base64_decode($_form['parenting_income_tax_exemptions']));			
			$_mother_child_spend = $_form['parenting_income_tax_exemptions']['custody-IncomeTaxes-percentMother'];
			$_father_child_spend = $_form['parenting_income_tax_exemptions']['custody-IncomeTaxes-percentFather'];
		}else{
			$_mother_child_spend = 0;
			$_father_child_spend = 0;
		}


		/*if($_form['wife_child_care_expenses']!=''){

			$_form['wife_child_care_expenses'] = unserialize(base64_decode($_form['wife_child_care_expenses']));			
			$_mo_childcare = array_chunk($_form['wife_child_care_expenses'], 4, true);
			$_mother_child_care = array_sum($_mo_childcare[0]);
		}else{
			$_mother_child_care = 0;
		}

		if($_form['husband_child_care_expenses']!=''){

			$_form['husband_child_care_expenses'] = unserialize(base64_decode($_form['husband_child_care_expenses']));			
			$_fa_childcare = array_chunk($_form['husband_child_care_expenses'], 4, true);
			$_father_child_care = array_sum($_fa_childcare[0]);
		}else{
			$_father_child_care = 0;
		}*/


		$_arr_mother=array();
		$_arr_father=array();

		if($_form['child_care']!=''){

			$_form['child_care'] = unserialize(base64_decode($_form['child_care']));			
			$_child_care = array_chunk($_form['child_care'], 8, true);

			if($_child_care[0]['ChildCare-ChildHealthInsurance-Parent'] == 'Mother'){
				$_arr_mother[] = $_child_care[0]['ChildCare-ChildHealthInsurance'];
			}else{
				$_arr_father[] = $_child_care[0]['ChildCare-ChildHealthInsurance'];
			}

			/*if($_child_care[0]['ChildCare-ExtraordinaryChildHealthCare-Parent'] == 'Mother'){
				$_arr_mother[] = $_child_care[0]['ChildCare-ExtraordinaryChildHealthCare'];
			}else{
				$_arr_father[] = $_child_care[0]['ChildCare-ExtraordinaryChildHealthCare'];
			}*/

			/*if($_child_care[0]['ChildCare-Daycare-Parent'] == 'Mother'){
				$_arr_mother[] = $_child_care[0]['ChildCare-Daycare'];
			}else{
				$_arr_father[] = $_child_care[0]['ChildCare-Daycare'];
			}

			if($_child_care[0]['ChildCare-Education-Parent'] == 'Mother'){
				$_arr_mother[] = $_child_care[0]['ChildCare-Education'];
			}else{
				$_arr_father[] = $_child_care[0]['ChildCare-Education'];
			}*/

			$_mother_child_care = array_sum($_arr_mother);
			$_father_child_care = array_sum($_arr_father);
		}else{
			$_mother_child_care = 0;
			$_father_child_care = 0;
		}



		// echo $_father_child_care;

		// echo "<pre>";print_r($_fa_childcare);exit;

		///////////////////////////

		// $_father = 2400;
		// $_mother = 2400;
		// $_children = 2;
		// $_mother_child_spend = 50;
		// $_father_child_spend = 50;
		// $_mother_child_care = 200;
		// $_father_child_care = 100;

		if($_father == 0 && $_mother == 0){
			$_income_total = 0;
			$_mo_percent_financial = 0;
			$_fa_percent_financial = 0;
		}else{
			$_income_total = $_father + $_mother;
			$_mo_percent_financial = $_mother / $_income_total;
			$_fa_percent_financial = $_father / $_income_total;
		}


		// $_income_total = $_father + $_mother;
		// $_mo_percent_financial = $_mother / $_income_total;
		// $_fa_percent_financial = $_father / $_income_total;		
		$_mother_day_spend = (365 * $_mother_child_spend) /100;
		$_father_day_spend = (365 * $_father_child_spend) /100;		
		$_parent_child_care = $_mother_child_care + $_father_child_care;

		// echo $_fa_percent_financial;exit;


		if($_income_total > 10000){

			// echo $_income_total;

			$_parent_income =  self::traerIncomeForChild(10000);

		}else if($_income_total < 800){

			$_parent_income =  self::traerIncomeForChild(800);				

		}else{

			$_parent_income =  self::traerIncomeForChild($_income_total);
			if(!$_parent_income){
				$_parent_income =  self::traerIncomeForChildBis($_income_total);
			}

		}	


		/*if($_income_total < 800){

			$_parent_income =  self::traerIncomeForChild(800);

		}else{

			$_parent_income =  self::traerIncomeForChild($_income_total);				

		}*/

		// echo "<pre>";print_r($_parent_income);exit;


		if($_parent_income){

			switch ($_children) {
			  case 1:
			    $_parent_children_income = $_parent_income['one'];
			    break;
			  case 2:
			    $_parent_children_income = $_parent_income['two'];
			    break;
			  case 3:
			    $_parent_children_income = $_parent_income['three'];
			    break;
			  case 4:
			    $_parent_children_income = $_parent_income['four'];
			    break;
			  case 5:
			    $_parent_children_income = $_parent_income['five'];
			    break;
			  case 6:
			    $_parent_children_income = $_parent_income['six'];
			    break;
			  default:
			    $_parent_children_income = $_parent_income['one'];
			}

		}

		if($_income_total > 10000){

			$_income_dif = $_income_total - 10000;
			$_porcent_big = self::traerIncomeBigForChild();

			switch ($_children) {
			  case 1:
			    $_porcent_children_income = $_porcent_big['one'];
			    break;
			  case 2:
			    $_porcent_children_income = $_porcent_big['two'];
			    break;
			  case 3:
			    $_porcent_children_income = $_porcent_big['three'];
			    break;
			  case 4:
			    $_porcent_children_income = $_porcent_big['four'];
			    break;
			  case 5:
			    $_porcent_children_income = $_porcent_big['five'];
			    break;
			  case 6:
			    $_porcent_children_income = $_porcent_big['six'];
			    break;
			  default:
			    $_porcent_children_income = $_porcent_big['six'];
			}

			$_parent_children_income = $_parent_children_income + ($_income_dif * $_porcent_children_income) /100;

		}


		// echo $_parent_children_income;exit;

		$_parent_dat = $_parent_children_income * 1.5;
		// $_fa_dat = $_father_children_income * 1.5;

		// echo $_parent_dat;exit;
		

		$_mo_basic_oblig = $_parent_dat * $_mo_percent_financial;
		$_fa_basic_oblig = $_parent_dat * $_fa_percent_financial;

		// echo $_fa_basic_oblig;exit;
		

		$_mo_monthly_child_obligation = ($_mo_basic_oblig * $_father_child_spend) / 100;
		$_fa_monthly_child_obligation = ($_fa_basic_oblig * $_mother_child_spend) / 100;
		$_child_care_mo = false;
		$_child_care_fa = false;

		/*// if($_mother_child_care >0){
			$_mo_parcial = $_parent_child_care * $_mo_percent_financial;
			$_mo_parcial_2 = $_mo_parcial - 0;
			$_mo_monthly_child_obligation_2 = $_mo_monthly_child_obligation + $_mo_parcial_2;
			$_mo_parcial = $_mo_parcial - $_mother_child_care;
			$_mo_parcial = ($_mo_parcial>0) ? $_mo_parcial : 0;			
			$_mo_monthly_child_obligation = $_mo_monthly_child_obligation + $_mo_parcial;
			$_child_care_mo = true;
		// }

		// if($_father_child_care >0){
			$_fa_parcial = $_parent_child_care * $_fa_percent_financial;
			$_fa_parcial_2 = $_fa_parcial - 0;
			$_fa_monthly_child_obligation_2 = $_fa_monthly_child_obligation + $_fa_parcial_2;
			$_fa_parcial = $_fa_parcial - $_father_child_care;
			$_fa_parcial = ($_fa_parcial>0) ? $_fa_parcial : 0;
			$_fa_monthly_child_obligation = $_fa_monthly_child_obligation + $_fa_parcial;
			$_child_care_fa = true;
		// }*/


		// if($_mother_child_care >0){
			$_mo_parcial = $_parent_child_care * $_mo_percent_financial;

			$_mo_parcial_2 = $_mo_parcial - 0;
			$_mo_monthly_child_obligation_2 = $_mo_monthly_child_obligation + $_mo_parcial_2;
			
			$_mo_parcial = $_mo_parcial - $_mother_child_care;
			$_mo_parcial = ($_mo_parcial>0) ? $_mo_parcial : 0;			
			$_mo_monthly_child_obligation_3 = $_mo_monthly_child_obligation + $_mo_parcial;

			$_child_care_mo = true;
		// }

		// if($_father_child_care >0){
			$_fa_parcial = $_parent_child_care * $_fa_percent_financial;

			$_fa_parcial_2 = $_fa_parcial - 0;
			$_fa_monthly_child_obligation_2 = $_fa_monthly_child_obligation + $_fa_parcial_2;

			$_fa_parcial = $_fa_parcial - $_father_child_care;
			$_fa_parcial = ($_fa_parcial>0) ? $_fa_parcial : 0;
			$_fa_monthly_child_obligation_3 = $_fa_monthly_child_obligation + $_fa_parcial;

			$_child_care_fa = true;
		// }

		/*if($_child_care_mo==false){
			$_mo_monthly_child_obligation_2 = $_mo_monthly_child_obligation;
		}

		if($_child_care_fa==false){
			$_fa_monthly_child_obligation_2 = $_fa_monthly_child_obligation;
		}*/

		/*if($_mo_monthly_child_obligation > $_fa_monthly_child_obligation){
			$_monthly_payment_mo = $_mo_monthly_child_obligation - $_fa_monthly_child_obligation;
			$_monthly_payment_fa = 0;
		}else if ($_fa_monthly_child_obligation > $_mo_monthly_child_obligation){
			$_monthly_payment_fa = $_fa_monthly_child_obligation - $_mo_monthly_child_obligation;
			$_monthly_payment_mo = 0;
		}else{
			$_monthly_payment_mo = $_fa_monthly_child_obligation - $_mo_monthly_child_obligation;	
			$_monthly_payment_fa = $_fa_monthly_child_obligation - $_mo_monthly_child_obligation;			
		}*/


		/*if($_mo_monthly_child_obligation_2 > $_fa_monthly_child_obligation_2){
			$_monthly_payment_mo = $_mo_monthly_child_obligation_2 - $_fa_monthly_child_obligation_2;
			$_monthly_payment_fa = 0;
		}else if ($_fa_monthly_child_obligation_2 > $_mo_monthly_child_obligation_2){
			$_monthly_payment_fa = $_fa_monthly_child_obligation_2 - $_mo_monthly_child_obligation_2;
			$_monthly_payment_mo = 0;
		}else{
			$_monthly_payment_mo = $_fa_monthly_child_obligation_2 - $_mo_monthly_child_obligation_2;	
			$_monthly_payment_fa = $_fa_monthly_child_obligation_2 - $_mo_monthly_child_obligation_2;			
		}*/


		if($_mo_monthly_child_obligation_3 > $_fa_monthly_child_obligation_3){
			$_monthly_payment_mo = $_mo_monthly_child_obligation_3 - $_fa_monthly_child_obligation_3;
			$_monthly_payment_fa = 0;
		}else if ($_fa_monthly_child_obligation_3 > $_mo_monthly_child_obligation_3){
			$_monthly_payment_fa = $_fa_monthly_child_obligation_3 - $_mo_monthly_child_obligation_3;
			$_monthly_payment_mo = 0;
		}else{
			$_monthly_payment_mo = $_fa_monthly_child_obligation_3 - $_mo_monthly_child_obligation_3;	
			$_monthly_payment_fa = $_fa_monthly_child_obligation_3 - $_mo_monthly_child_obligation_3;			
		}

		$data = array();
		$data['mother_income'] = $_mother;
		$data['father_income'] = $_father;
		$data['mother_child_support_oblig'] = round($_mo_monthly_child_obligation, 2);
		$data['father_child_support_oblig'] = round($_fa_monthly_child_obligation, 2);
		$data['mother_child_support_credits'] = $_mother_child_care;
		$data['father_child_support_credits'] = $_father_child_care;
		$data['mother_payment'] = round($_monthly_payment_mo, 2);
		$data['father_payment'] = round($_monthly_payment_fa, 2);

		

		// echo "mother: ".round($_mo_monthly_child_obligation_2, 2);
		// echo "<br>";
		// echo "father: ".round($_fa_monthly_child_obligation_2, 2);
		// echo "<br>";
		// echo "<br>";
		// echo  "Monthly Payment father: ".round($_monthly_payment_fa, 2);
		// echo "<br>";
		// echo  "Monthly Payment mother: ".round($_monthly_payment_mo, 2);
		// exit;

		return $data;

	}

	
	public function calculateChildSupportExcel($_id_form)
	{

		// $_id_form = 1693;

		$_form =  self::traerFormsPorIdStatic($_id_form);

		// echo "<pre>";print_r($_form);exit;

		if($_form['wife_monthly_income']!='' || $_form['husband_monthly_income']!=''){

			if($_form['wife_monthly_income']!=''){
				$_form['wife_monthly_income'] = unserialize(base64_decode($_form['wife_monthly_income']));
				$_mo_icome = array_chunk($_form['wife_monthly_income'], 10, true);
				unset($_mo_icome[1]['WifeFinancial-StateIndustrialInsurance']);
				$_mo_icome_sum = array_sum($_mo_icome[0]);
				$_mo_icome_res = array_sum($_mo_icome[1]);
				$_mother = $_mo_icome_sum - $_mo_icome_res;
			}else{
				$_mother = 0;
			}

			if($_form['husband_monthly_income']!=''){
				$_form['husband_monthly_income'] = unserialize(base64_decode($_form['husband_monthly_income']));
				$_fa_icome = array_chunk($_form['husband_monthly_income'], 10, true);
				unset($_fa_icome[1]['HusbandFinancial-StateIndustrialInsurance']);
				$_fa_icome_sum = array_sum($_fa_icome[0]);
				$_fa_icome_res = array_sum($_fa_icome[1]);
				$_father = $_fa_icome_sum - $_fa_icome_res;
			}else{
				$_father = 0;
			}


		}else{
			$_father = 0;
			$_mother = 0;
		}


		// echo "<pre>";print_r($_mo_icome);//exit;


		if($_form['children_from_the_marriage']!=''){

			$_form['children_from_the_marriage'] = unserialize(base64_decode($_form['children_from_the_marriage']));			
			$_children = count($_form['children_from_the_marriage']['child-firstname']);
		}else{
			$_children = 0;
		}



		if($_form['parenting_income_tax_exemptions']!=''){

			$_form['parenting_income_tax_exemptions'] = unserialize(base64_decode($_form['parenting_income_tax_exemptions']));			
			$_mother_child_spend = $_form['parenting_income_tax_exemptions']['custody-IncomeTaxes-percentMother'];
			$_father_child_spend = $_form['parenting_income_tax_exemptions']['custody-IncomeTaxes-percentFather'];
		}else{
			$_mother_child_spend = 0;
			$_father_child_spend = 0;
		}


		/*if($_form['wife_child_care_expenses']!=''){

			$_form['wife_child_care_expenses'] = unserialize(base64_decode($_form['wife_child_care_expenses']));			
			$_mo_childcare = array_chunk($_form['wife_child_care_expenses'], 4, true);
			$_mother_child_care = array_sum($_mo_childcare[0]);
		}else{
			$_mother_child_care = 0;
		}

		if($_form['husband_child_care_expenses']!=''){

			$_form['husband_child_care_expenses'] = unserialize(base64_decode($_form['husband_child_care_expenses']));			
			$_fa_childcare = array_chunk($_form['husband_child_care_expenses'], 4, true);
			$_father_child_care = array_sum($_fa_childcare[0]);
		}else{
			$_father_child_care = 0;
		}*/


		$_arr_mother=array();
		$_arr_father=array();

		if($_form['child_care']!=''){

			$_form['child_care'] = unserialize(base64_decode($_form['child_care']));			
			$_child_care = array_chunk($_form['child_care'], 8, true);



			if($_child_care[0]['ChildCare-ChildHealthInsurance-Parent'] == 'Mother'){
				$_arr_mother[] = $_child_care[0]['ChildCare-ChildHealthInsurance'];
			}else{
				$_arr_father[] = $_child_care[0]['ChildCare-ChildHealthInsurance'];
			}

			/*if($_child_care[0]['ChildCare-ExtraordinaryChildHealthCare-Parent'] == 'Mother'){
				$_arr_mother[] = $_child_care[0]['ChildCare-ExtraordinaryChildHealthCare'];
			}else{
				$_arr_father[] = $_child_care[0]['ChildCare-ExtraordinaryChildHealthCare'];
			}*/

			/*if($_child_care[0]['ChildCare-Daycare-Parent'] == 'Mother'){
				$_arr_mother[] = $_child_care[0]['ChildCare-Daycare'];
			}else{
				$_arr_father[] = $_child_care[0]['ChildCare-Daycare'];
			}

			if($_child_care[0]['ChildCare-Education-Parent'] == 'Mother'){
				$_arr_mother[] = $_child_care[0]['ChildCare-Education'];
			}else{
				$_arr_father[] = $_child_care[0]['ChildCare-Education'];
			}*/

			// echo "<pre>";print_r($_arr_mother);exit;

			$_mother_child_care = array_sum($_arr_mother);
			$_father_child_care = array_sum($_arr_father);
		}else{
			$_mother_child_care = 0;
			$_father_child_care = 0;
		}



		// echo $_father_child_care;

		// echo "<pre>";print_r($_fa_childcare);exit;

		///////////////////////////

		// $_father = 698.87;
		// $_mother = 11273.28;
		// $_children = 2;
		// $_mother_child_spend = 80;
		// $_father_child_spend = 20;
		// $_mother_child_care = 400;
		// $_father_child_care = 0;

		if($_father == 0 && $_mother == 0){
			$_income_total = 0;
			$_mo_percent_financial = 0;
			$_fa_percent_financial = 0;
		}else{
			$_income_total = $_father + $_mother;
			$_mo_percent_financial = $_mother / $_income_total;
			$_fa_percent_financial = $_father / $_income_total;
		}


		// $_income_total = $_father + $_mother;
		// $_mo_percent_financial = $_mother / $_income_total;
		// $_fa_percent_financial = $_father / $_income_total;		
		$_mother_day_spend = (365 * $_mother_child_spend) /100;
		$_father_day_spend = (365 * $_father_child_spend) /100;		
		$_parent_child_care = $_mother_child_care + $_father_child_care;

		// echo $_fa_percent_financial;exit;


		if($_income_total > 10000){

			// echo $_income_total."<br>";

			$_parent_income =  self::traerIncomeForChild(10000);


		}else if($_income_total < 800){

			$_parent_income =  self::traerIncomeForChild(800);				

		}else{

			$_parent_income =  self::traerIncomeForChild($_income_total);
			if(!$_parent_income){
				$_parent_income =  self::traerIncomeForChildBis($_income_total);
			}

		}	


		/*if($_income_total < 800){

			$_parent_income =  self::traerIncomeForChild(800);

		}else{

			$_parent_income =  self::traerIncomeForChild($_income_total);				

		}*/

		// echo "<pre>";print_r($_parent_income);exit;


		if($_parent_income){

			switch ($_children) {
			  case 1:
			    $_parent_children_income = $_parent_income['one'];
			    break;
			  case 2:
			    $_parent_children_income = $_parent_income['two'];
			    break;
			  case 3:
			    $_parent_children_income = $_parent_income['three'];
			    break;
			  case 4:
			    $_parent_children_income = $_parent_income['four'];
			    break;
			  case 5:
			    $_parent_children_income = $_parent_income['five'];
			    break;
			  case 6:
			    $_parent_children_income = $_parent_income['six'];
			    break;
			  default:
			    $_parent_children_income = $_parent_income['one'];
			}

		}

		if($_income_total > 10000){

			$_income_dif = $_income_total - 10000;
			$_porcent_big = self::traerIncomeBigForChild();

			switch ($_children) {
			  case 1:
			    $_porcent_children_income = $_porcent_big['one'];
			    break;
			  case 2:
			    $_porcent_children_income = $_porcent_big['two'];
			    break;
			  case 3:
			    $_porcent_children_income = $_porcent_big['three'];
			    break;
			  case 4:
			    $_porcent_children_income = $_porcent_big['four'];
			    break;
			  case 5:
			    $_porcent_children_income = $_porcent_big['five'];
			    break;
			  case 6:
			    $_porcent_children_income = $_porcent_big['six'];
			    break;
			  default:
			    $_porcent_children_income = $_porcent_big['six'];
			}

			$_parent_children_income = $_parent_children_income + ($_income_dif * $_porcent_children_income) /100;

		}

		// echo round($_parent_children_income,2);exit;

		// $_parent_children_income = 2375.91;

		$_parent_dat = $_parent_children_income * 1.5;
		// $_fa_dat = $_father_children_income * 1.5;

		// echo $_parent_dat;exit;
		

		$_mo_basic_oblig = $_parent_dat * $_mo_percent_financial;
		$_fa_basic_oblig = $_parent_dat * $_fa_percent_financial;

		// echo $_mo_basic_oblig;exit;
		

		$_mo_monthly_child_obligation = ($_mo_basic_oblig * $_father_child_spend) / 100;
		$_fa_monthly_child_obligation = ($_fa_basic_oblig * $_mother_child_spend) / 100;
		$_child_care_mo = false;
		$_child_care_fa = false;

		// echo round($_mo_monthly_child_obligation,2);//exit;

		// echo "<br>";

		// if($_mother_child_care >0){
			$_mo_parcial = $_parent_child_care * $_mo_percent_financial;

			// echo round($_mo_parcial,2);

			$_mo_parcial_2 = $_mo_parcial - 0;
			$_mo_monthly_child_obligation_2 = $_mo_monthly_child_obligation + $_mo_parcial_2;
			
			$_mo_parcial = $_mo_parcial - $_mother_child_care;
			$_mo_parcial = ($_mo_parcial>0) ? $_mo_parcial : 0;			
			$_mo_monthly_child_obligation_3 = $_mo_monthly_child_obligation + $_mo_parcial;

			$_child_care_mo = true;
		// }

		// if($_father_child_care >0){
			$_fa_parcial = $_parent_child_care * $_fa_percent_financial;

			// echo "<br>";
			// echo round($_fa_parcial,2);

			$_fa_parcial_2 = $_fa_parcial - 0;
			$_fa_monthly_child_obligation_2 = $_fa_monthly_child_obligation + $_fa_parcial_2;

			$_fa_parcial = $_fa_parcial - $_father_child_care;
			$_fa_parcial = ($_fa_parcial>0) ? $_fa_parcial : 0;
			$_fa_monthly_child_obligation_3 = $_fa_monthly_child_obligation + $_fa_parcial;

			$_child_care_fa = true;
		// }

			// echo $_fa_parcial;

		/*if($_child_care_mo==false){
			$_mo_monthly_child_obligation_2 = $_mo_monthly_child_obligation;
		}

		if($_child_care_fa==false){
			$_fa_monthly_child_obligation_2 = $_fa_monthly_child_obligation;
		}*/

		if($_mo_monthly_child_obligation_3 > $_fa_monthly_child_obligation_3){
			$_monthly_payment_mo = $_mo_monthly_child_obligation_3 - $_fa_monthly_child_obligation_3;
			$_monthly_payment_fa = 0;
		}else if ($_fa_monthly_child_obligation_3 > $_mo_monthly_child_obligation_3){
			$_monthly_payment_fa = $_fa_monthly_child_obligation_3 - $_mo_monthly_child_obligation_3;
			$_monthly_payment_mo = 0;
		}else{
			$_monthly_payment_mo = $_fa_monthly_child_obligation_3 - $_mo_monthly_child_obligation_3;	
			$_monthly_payment_fa = $_fa_monthly_child_obligation_3 - $_mo_monthly_child_obligation_3;			
		}


		/*if($_mo_monthly_child_obligation_2 > $_fa_monthly_child_obligation_2){
			$_monthly_payment_mo = $_mo_monthly_child_obligation_2 - $_fa_monthly_child_obligation_2;
			$_monthly_payment_fa = 0;
		}else if ($_fa_monthly_child_obligation_2 > $_mo_monthly_child_obligation_2){
			$_monthly_payment_fa = $_fa_monthly_child_obligation_2 - $_mo_monthly_child_obligation_2;
			$_monthly_payment_mo = 0;
		}else{
			$_monthly_payment_mo = $_fa_monthly_child_obligation_2 - $_mo_monthly_child_obligation_2;	
			$_monthly_payment_fa = $_fa_monthly_child_obligation_2 - $_mo_monthly_child_obligation_2;			
		}*/


		$data = array();
		// $data['mother_income'] = $_mother;
		// $data['father_income'] = $_father;
		// $data['mother_child_support_oblig'] = round($_mo_monthly_child_obligation, 2);
		// $data['father_child_support_oblig'] = round($_fa_monthly_child_obligation, 2);
		// $data['mother_child_support_credits'] = $_mother_child_care;
		// $data['father_child_support_credits'] = $_father_child_care;
		// $data['mother_payment'] = round($_monthly_payment_mo, 2);
		// $data['father_payment'] = round($_monthly_payment_fa, 2);


		$data['net_monthly_income_mother'] = $_mother;
		$data['net_monthly_income_father'] = $_father;		
		$data['children'] = $_children;		
		$data['overnight_percentage_mother'] = $_mother_child_spend."%";
		$data['overnight_percentage_father'] = $_father_child_spend."%";
		$data['overnights_mother'] = $_mother_day_spend;
		$data['overnights_father'] = $_father_day_spend;
		$data['payment_share_to_other_mother'] = round($_mo_monthly_child_obligation, 2);
		$data['payment_share_to_other_father'] = round($_fa_monthly_child_obligation, 2);
		$data['health_insurance_premiums_mother'] = $_mother_child_care;
		$data['health_insurance_premiums_father'] = $_father_child_care;
		$data['presumed_amt_paid_mother'] = round($_monthly_payment_mo, 2);
		$data['presumed_amt_paid_father'] = round($_monthly_payment_fa, 2);

		// echo "<pre>";print_r($data);exit;
		

		// echo "mother: ".round($_mo_monthly_child_obligation_2, 2);
		// echo "<br>";
		// echo "father: ".round($_fa_monthly_child_obligation_2, 2);
		// echo "<br>";
		// echo "<br>";
		// echo  "Monthly Payment father: ".round($_monthly_payment_fa, 2);
		// echo "<br>";
		// echo  "Monthly Payment mother: ".round($_monthly_payment_mo, 2);
		// exit;

		return $data;

	}


	public function calculateChildSupportTEST($_id_form)
	{

		// $_id_form = 1693;

		$_form =  self::traerFormsPorIdStatic($_id_form);

		// echo "<pre>";print_r($_form);exit;

		if($_form['wife_monthly_income']!='' || $_form['husband_monthly_income']!=''){

			if($_form['wife_monthly_income']!=''){
				$_form['wife_monthly_income'] = unserialize(base64_decode($_form['wife_monthly_income']));
				$_mo_icome = array_chunk($_form['wife_monthly_income'], 10, true);
				unset($_mo_icome[1]['WifeFinancial-StateIndustrialInsurance']);
				$_mo_icome_sum = array_sum($_mo_icome[0]);
				$_mo_icome_res = array_sum($_mo_icome[1]);
				$_mother = $_mo_icome_sum - $_mo_icome_res;
			}else{
				$_mother = 0;
			}

			if($_form['husband_monthly_income']!=''){
				$_form['husband_monthly_income'] = unserialize(base64_decode($_form['husband_monthly_income']));
				$_fa_icome = array_chunk($_form['husband_monthly_income'], 10, true);
				unset($_fa_icome[1]['HusbandFinancial-StateIndustrialInsurance']);
				$_fa_icome_sum = array_sum($_fa_icome[0]);
				$_fa_icome_res = array_sum($_fa_icome[1]);
				$_father = $_fa_icome_sum - $_fa_icome_res;
			}else{
				$_father = 0;
			}


		}else{
			$_father = 0;
			$_mother = 0;
		}


		// echo "<pre>";print_r($_mo_icome);//exit;


		if($_form['children_from_the_marriage']!=''){

			$_form['children_from_the_marriage'] = unserialize(base64_decode($_form['children_from_the_marriage']));			
			$_children = count($_form['children_from_the_marriage']['child-firstname']);
		}else{
			$_children = 0;
		}



		if($_form['parenting_income_tax_exemptions']!=''){

			$_form['parenting_income_tax_exemptions'] = unserialize(base64_decode($_form['parenting_income_tax_exemptions']));			
			$_mother_child_spend = $_form['parenting_income_tax_exemptions']['custody-IncomeTaxes-percentMother'];
			$_father_child_spend = $_form['parenting_income_tax_exemptions']['custody-IncomeTaxes-percentFather'];
		}else{
			$_mother_child_spend = 0;
			$_father_child_spend = 0;
		}


		/*if($_form['wife_child_care_expenses']!=''){

			$_form['wife_child_care_expenses'] = unserialize(base64_decode($_form['wife_child_care_expenses']));			
			$_mo_childcare = array_chunk($_form['wife_child_care_expenses'], 4, true);
			$_mother_child_care = array_sum($_mo_childcare[0]);
		}else{
			$_mother_child_care = 0;
		}

		if($_form['husband_child_care_expenses']!=''){

			$_form['husband_child_care_expenses'] = unserialize(base64_decode($_form['husband_child_care_expenses']));			
			$_fa_childcare = array_chunk($_form['husband_child_care_expenses'], 4, true);
			$_father_child_care = array_sum($_fa_childcare[0]);
		}else{
			$_father_child_care = 0;
		}*/


		$_arr_mother=array();
		$_arr_father=array();

		if($_form['child_care']!=''){

			$_form['child_care'] = unserialize(base64_decode($_form['child_care']));			
			$_child_care = array_chunk($_form['child_care'], 8, true);



			if($_child_care[0]['ChildCare-ChildHealthInsurance-Parent'] == 'Mother'){
				$_arr_mother[] = $_child_care[0]['ChildCare-ChildHealthInsurance'];
			}else{
				$_arr_father[] = $_child_care[0]['ChildCare-ChildHealthInsurance'];
			}

			/*if($_child_care[0]['ChildCare-ExtraordinaryChildHealthCare-Parent'] == 'Mother'){
				$_arr_mother[] = $_child_care[0]['ChildCare-ExtraordinaryChildHealthCare'];
			}else{
				$_arr_father[] = $_child_care[0]['ChildCare-ExtraordinaryChildHealthCare'];
			}*/

			/*if($_child_care[0]['ChildCare-Daycare-Parent'] == 'Mother'){
				$_arr_mother[] = $_child_care[0]['ChildCare-Daycare'];
			}else{
				$_arr_father[] = $_child_care[0]['ChildCare-Daycare'];
			}

			if($_child_care[0]['ChildCare-Education-Parent'] == 'Mother'){
				$_arr_mother[] = $_child_care[0]['ChildCare-Education'];
			}else{
				$_arr_father[] = $_child_care[0]['ChildCare-Education'];
			}*/

			// echo "<pre>";print_r($_arr_mother);exit;

			$_mother_child_care = array_sum($_arr_mother);
			$_father_child_care = array_sum($_arr_father);
		}else{
			$_mother_child_care = 0;
			$_father_child_care = 0;
		}



		// echo $_father_child_care;

		// echo "<pre>";print_r($_fa_childcare);exit;

		///////////////////////////

		// $_father = 698.87;
		// $_mother = 11273.28;
		// $_children = 2;
		// $_mother_child_spend = 80;
		// $_father_child_spend = 20;
		// $_mother_child_care = 400;
		// $_father_child_care = 0;

		if($_father == 0 && $_mother == 0){
			$_income_total = 0;
			$_mo_percent_financial = 0;
			$_fa_percent_financial = 0;
		}else{
			$_income_total = $_father + $_mother;
			$_mo_percent_financial = $_mother / $_income_total;
			$_fa_percent_financial = $_father / $_income_total;
		}


		// $_income_total = $_father + $_mother;
		// $_mo_percent_financial = $_mother / $_income_total;
		// $_fa_percent_financial = $_father / $_income_total;		
		$_mother_day_spend = (365 * $_mother_child_spend) /100;
		$_father_day_spend = (365 * $_father_child_spend) /100;		
		$_parent_child_care = $_mother_child_care + $_father_child_care;

		// echo $_fa_percent_financial;exit;
		echo $_income_total."<br>";

		if($_income_total > 10000){

			// echo $_income_total."<br>";

			$_parent_income =  self::traerIncomeForChild(10000);


		}else if($_income_total < 800){

			$_parent_income =  self::traerIncomeForChild(800);				

		}else{

			$_parent_income =  self::traerIncomeForChild($_income_total);
			if(!$_parent_income){
				$_parent_income =  self::traerIncomeForChildBis($_income_total);
			}

			// SELECT * FROM `contenidos_calculos` ORDER BY ABS(income - 5275) LIMIT 1;

		}	


		/*if($_income_total < 800){

			$_parent_income =  self::traerIncomeForChild(800);

		}else{

			$_parent_income =  self::traerIncomeForChild($_income_total);				

		}*/

		// echo "<pre>";print_r($_parent_income);exit;


		if($_parent_income){

			switch ($_children) {
			  case 1:
			    $_parent_children_income = $_parent_income['one'];
			    break;
			  case 2:
			    $_parent_children_income = $_parent_income['two'];
			    break;
			  case 3:
			    $_parent_children_income = $_parent_income['three'];
			    break;
			  case 4:
			    $_parent_children_income = $_parent_income['four'];
			    break;
			  case 5:
			    $_parent_children_income = $_parent_income['five'];
			    break;
			  case 6:
			    $_parent_children_income = $_parent_income['six'];
			    break;
			  default:
			    $_parent_children_income = $_parent_income['one'];
			}

		}

		if($_income_total > 10000){

			$_income_dif = $_income_total - 10000;
			$_porcent_big = self::traerIncomeBigForChild();

			switch ($_children) {
			  case 1:
			    $_porcent_children_income = $_porcent_big['one'];
			    break;
			  case 2:
			    $_porcent_children_income = $_porcent_big['two'];
			    break;
			  case 3:
			    $_porcent_children_income = $_porcent_big['three'];
			    break;
			  case 4:
			    $_porcent_children_income = $_porcent_big['four'];
			    break;
			  case 5:
			    $_porcent_children_income = $_porcent_big['five'];
			    break;
			  case 6:
			    $_porcent_children_income = $_porcent_big['six'];
			    break;
			  default:
			    $_porcent_children_income = $_porcent_big['six'];
			}

			$_parent_children_income = $_parent_children_income + ($_income_dif * $_porcent_children_income) /100;

		}

		// echo round($_parent_children_income,2);exit;

		// $_parent_children_income = 2375.91;

		$_parent_dat = $_parent_children_income * 1.5;
		// $_fa_dat = $_father_children_income * 1.5;

		// echo $_parent_dat;exit;
		

		$_mo_basic_oblig = $_parent_dat * $_mo_percent_financial;
		$_fa_basic_oblig = $_parent_dat * $_fa_percent_financial;

		// echo $_mo_basic_oblig;exit;
		

		$_mo_monthly_child_obligation = ($_mo_basic_oblig * $_father_child_spend) / 100;
		$_fa_monthly_child_obligation = ($_fa_basic_oblig * $_mother_child_spend) / 100;
		$_child_care_mo = false;
		$_child_care_fa = false;

		// echo round($_mo_monthly_child_obligation,2);//exit;

		// echo "<br>";

		// if($_mother_child_care >0){
			$_mo_parcial = $_parent_child_care * $_mo_percent_financial;

			echo round($_mo_parcial,2);

			$_mo_parcial_2 = $_mo_parcial - 0;
			$_mo_monthly_child_obligation_2 = $_mo_monthly_child_obligation + $_mo_parcial_2;
			
			$_mo_parcial = $_mo_parcial - $_mother_child_care;
			$_mo_parcial = ($_mo_parcial>0) ? $_mo_parcial : 0;			
			$_mo_monthly_child_obligation_3 = $_mo_monthly_child_obligation + $_mo_parcial;

			$_child_care_mo = true;
		// }

		// if($_father_child_care >0){
			$_fa_parcial = $_parent_child_care * $_fa_percent_financial;

			echo "<br>";
			echo round($_fa_parcial,2);

			$_fa_parcial_2 = $_fa_parcial - 0;
			$_fa_monthly_child_obligation_2 = $_fa_monthly_child_obligation + $_fa_parcial_2;

			$_fa_parcial = $_fa_parcial - $_father_child_care;
			$_fa_parcial = ($_fa_parcial>0) ? $_fa_parcial : 0;
			$_fa_monthly_child_obligation_3 = $_fa_monthly_child_obligation + $_fa_parcial;

			$_child_care_fa = true;
		// }

			// echo $_fa_parcial;

		/*if($_child_care_mo==false){
			$_mo_monthly_child_obligation_2 = $_mo_monthly_child_obligation;
		}

		if($_child_care_fa==false){
			$_fa_monthly_child_obligation_2 = $_fa_monthly_child_obligation;
		}*/

		if($_mo_monthly_child_obligation_3 > $_fa_monthly_child_obligation_3){
			$_monthly_payment_mo = $_mo_monthly_child_obligation_3 - $_fa_monthly_child_obligation_3;
			$_monthly_payment_fa = 0;
		}else if ($_fa_monthly_child_obligation_3 > $_mo_monthly_child_obligation_3){
			$_monthly_payment_fa = $_fa_monthly_child_obligation_3 - $_mo_monthly_child_obligation_3;
			$_monthly_payment_mo = 0;
		}else{
			$_monthly_payment_mo = $_fa_monthly_child_obligation_3 - $_mo_monthly_child_obligation_3;	
			$_monthly_payment_fa = $_fa_monthly_child_obligation_3 - $_mo_monthly_child_obligation_3;			
		}


		/*if($_mo_monthly_child_obligation_2 > $_fa_monthly_child_obligation_2){
			$_monthly_payment_mo = $_mo_monthly_child_obligation_2 - $_fa_monthly_child_obligation_2;
			$_monthly_payment_fa = 0;
		}else if ($_fa_monthly_child_obligation_2 > $_mo_monthly_child_obligation_2){
			$_monthly_payment_fa = $_fa_monthly_child_obligation_2 - $_mo_monthly_child_obligation_2;
			$_monthly_payment_mo = 0;
		}else{
			$_monthly_payment_mo = $_fa_monthly_child_obligation_2 - $_mo_monthly_child_obligation_2;	
			$_monthly_payment_fa = $_fa_monthly_child_obligation_2 - $_mo_monthly_child_obligation_2;			
		}*/


		$data = array();
		// $data['mother_income'] = $_mother;
		// $data['father_income'] = $_father;
		// $data['mother_child_support_oblig'] = round($_mo_monthly_child_obligation, 2);
		// $data['father_child_support_oblig'] = round($_fa_monthly_child_obligation, 2);
		// $data['mother_child_support_credits'] = $_mother_child_care;
		// $data['father_child_support_credits'] = $_father_child_care;
		// $data['mother_payment'] = round($_monthly_payment_mo, 2);
		// $data['father_payment'] = round($_monthly_payment_fa, 2);


		$data['net_monthly_income_mother'] = $_mother;
		$data['net_monthly_income_father'] = $_father;		
		$data['children'] = $_children;		
		$data['overnight_percentage_mother'] = $_mother_child_spend."%";
		$data['overnight_percentage_father'] = $_father_child_spend."%";
		$data['overnights_mother'] = $_mother_day_spend;
		$data['overnights_father'] = $_father_day_spend;
		$data['payment_share_to_other_mother'] = round($_mo_monthly_child_obligation, 2);
		$data['payment_share_to_other_father'] = round($_fa_monthly_child_obligation, 2);
		$data['health_insurance_premiums_mother'] = $_mother_child_care;
		$data['health_insurance_premiums_father'] = $_father_child_care;
		$data['presumed_amt_paid_mother'] = round($_monthly_payment_mo, 2);
		$data['presumed_amt_paid_father'] = round($_monthly_payment_fa, 2);

		echo "<pre>";print_r($data);exit;
		

		// echo "mother: ".round($_mo_monthly_child_obligation_2, 2);
		// echo "<br>";
		// echo "father: ".round($_fa_monthly_child_obligation_2, 2);
		// echo "<br>";
		// echo "<br>";
		// echo  "Monthly Payment father: ".round($_monthly_payment_fa, 2);
		// echo "<br>";
		// echo  "Monthly Payment mother: ".round($_monthly_payment_mo, 2);
		// exit;

		return $data;

	}

	public static function traerFormsPorIdStatic($_id)
	{
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cc.* FROM `contenidos_forms_respuestas` as cc WHERE cc.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}
	
	public static function traerIncomeForChild($_icome)
	{

		$result = Pd::instancia()->prepare("SELECT cu.* FROM `contenidos_calculos` as cu WHERE cu.income = :icome");
		$result->execute(array(":icome" => $_icome));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public static function traerIncomeForChildBis($_icome)
	{

		$result = Pd::instancia()->prepare("SELECT cu.* FROM `contenidos_calculos` as cu ORDER BY ABS(cu.income - :icome) LIMIT 1");
		$result->execute(array(":icome" => $_icome));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		// SELECT * FROM `contenidos_calculos` ORDER BY ABS(income - 5275) LIMIT 1;

		return ($result) ? $result : false;
	}



	public function traerIncomeBigForChild()
	{

		$result = Pd::instancia()->prepare("SELECT cu.* FROM `contenidos_calculos_mayores` as cu WHERE cu.id = :id");
		$result->execute(array(":id" => 1));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	// SEO

	public function traerSeo($_id, $_item, $_tipo)
	{
		$_id = (int) $_id;
		$_reg = Pd::instancia('produccion')->prepare("SELECT cs.* FROM `contenidos_seo` as cs WHERE cs.id_seccion = :id AND cs.item = :item AND cs.tipo = :tipo");
		$_reg->execute(array(':item' => $_item, ':tipo' => $_tipo, ':id' => $_id));
		$_reg = $_reg->fetch(PDO::FETCH_ASSOC);

		return ($_reg) ? $_reg : false;
	}

	public function traerSeoSeccion($_item, $_tipo)
	{
		$_reg = Pd::instancia('produccion')->prepare("SELECT cs.* FROM `contenidos_seo` as cs WHERE cs.item = :item AND cs.tipo = :tipo");
		$_reg->execute(array(':item' => $_item, ':tipo' => $_tipo));
		$_reg = $_reg->fetch(PDO::FETCH_ASSOC);

		return ($_reg) ? $_reg : false;
	}

	public function traerSeoSeccion2($_item, $_item2, $_tipo)
	{
		$_reg = Pd::instancia('produccion')->prepare("SELECT cs.* FROM `contenidos_seo` as cs WHERE (cs.item = :item OR cs.item = :item2) AND cs.tipo = :tipo");
		$_reg->execute(array(':item' => $_item, ':item2' => $_item2, ':tipo' => $_tipo));
		$_reg = $_reg->fetch(PDO::FETCH_ASSOC);

		return ($_reg) ? $_reg : false;
	}


	public function cargarSeo($_id_seccion, $_item, $_tipo, $_titulo, $_desc, $_img_id, $_identificador, $_canonical='')
	{
		
		$_tarjeta = new contenidos_se();
		$_tarjeta->titulo = $_titulo;
		$_tarjeta->descripcion = $_desc;
		$_tarjeta->canonical = $_canonical;
		$_tarjeta->id_img = $_img_id;
		$_tarjeta->id_seccion = $_id_seccion;
		$_tarjeta->item = $_item;
		$_tarjeta->tipo = $_tipo;
		$_tarjeta->identificador = $_identificador;
		$_tarjeta->fecha = date('Y-m-d');

		return ($_tarjeta->save()) ? true: false;
	}

	public function editarSeo($_id_seccion, $_item, $_tipo, $_titulo, $_desc, $_img_id, $_identificador, $_canonical='')
	{
		
		$_tarjeta = contenidos_se::find(array('conditions' => array('id_seccion = ? AND item = ? AND tipo = ?', $_id_seccion, $_item, $_tipo)));
		if($_tarjeta){
			$_tarjeta->titulo = $_titulo;
			$_tarjeta->descripcion = $_desc; 
			$_tarjeta->canonical = $_canonical;
			$_tarjeta->id_img = $_img_id;
			$_tarjeta->id_seccion = $_id_seccion;
			$_tarjeta->item = $_item;
			$_tarjeta->tipo = $_tipo;
			$_tarjeta->identificador = $_identificador;
			$_tarjeta->fecha = date('Y-m-d');

			return ($_tarjeta->save()) ? true: false;

		}else{
			// $this->cargarSeo($_id_seccion, $_item, $_titulo, $_desc, $_canonical);
			$_cargar = $this->cargarSeo($_id_seccion, $_item, $_tipo, $_titulo, $_desc, $_img_id, $_identificador);
			if($_cargar){
				return true;
			}else{
				return false;
			}
		}	

		
	}
	
}