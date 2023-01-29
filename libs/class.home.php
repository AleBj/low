<?php
use Nucleo\Pd\Pd;
use Nucleo\Registro\Registro;
use \Exception as EX;

class home
{
	
	// PRODUCTS


	public static function traerProductoPorTipoStatic($_id, $_tipo)
	{
		if($_tipo == 'fijo'){
			return contenidos_producto::find(array('conditions' => array('id = ?', $_id)));
		}else{
			return contenidos_productos_variable::find(array('conditions' => array('id = ?', $_id)));
		}
		
	}

	public function traerProductos($_estado)
	{
		$_lanz = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_productos` as cp WHERE cp.estado = :estado ORDER BY cp.id DESC");
		$_lanz->execute(array(":estado" => $_estado));
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}

	public function traerProductosVariables($_estado)
	{
		$_lanz = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_productos_variables` as cp WHERE cp.estado = :estado ORDER BY cp.id DESC");
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
	
	public static function traerProductoPorId($_id)
	{
		return contenidos_producto::find($_id);
	}

	public static function traerProductoVariablePorId($_id)
	{
		return contenidos_productos_variable::find($_id);
	}

	public static function traerProductoPorItemStatic($_val)
	{
		if($_val == 'ppa' || $_val == 'msa' || $_val == 'msappa'){
			return contenidos_producto::find(array('conditions' => array('item = ?', $_val)));
		}else{
			return contenidos_productos_variable::find(array('conditions' => array('item = ?', $_val)));
		}
		
	}

	public static function traerProductoStatic($_id)
	{
		$result = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_productos` as cp WHERE cp.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerProductoPorItem($_item)
	{
		$result = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_productos` as cp WHERE cp.item = :item");
		$result->execute(array(":item" => $_item));
		$result = $result->fetch(PDO::FETCH_ASSOC);		

		return ($result) ? $result : false;
	}

	public function traerProductoVariablePorItem($_item)
	{
		$result = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_productos_variables` as cp WHERE cp.item = :item");
		$result->execute(array(":item" => $_item));
		$result = $result->fetch(PDO::FETCH_ASSOC);		

		return ($result) ? $result : false;
	}

	// BLOG

	public function traerBlogs()
	{
		$result = Pd::instancia()->prepare("SELECT cs.* FROM `contenidos_blog` as cs ORDER BY cs.id DESC");
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
			
	}

	public function traerBlogPorId($_id)
	{
		// return contenidos_user::find($_id);
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cs.* FROM `contenidos_blog` as cs WHERE cs.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerBlog($_item)
	{
		// return contenidos_user::find($_id);
		// $_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cs.* FROM `contenidos_blog` as cs WHERE cs.item = :item");
		$result->execute(array(":item" => $_item));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}


	public function traerBlogRelacionados($_id, $_limit)
	{
		// return contenidos_producto::find('all',array('conditions' => array('id_categoria = ? AND id != ?', $_cat, $_id),'order' => 'id desc','limit' => $_limit));

		$result = Pd::instancia()->prepare("SELECT cs.* FROM `contenidos_blog` as cs WHERE cs.id != :id ORDER BY cs.id DESC LIMIT $_limit");
		$result->execute(array(":id" => $_id));
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	// HOW IT WORKS

	public function traerHowItWorks()
	{
		$_lanz = Pd::instancia()->prepare("SELECT cpf.* FROM `contenidos_howitworks` as cpf ORDER BY cpf.id ASC");
		$_lanz->execute();
		$_lanz = $_lanz->fetch(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}

	// Terms and Conditions

	public function traerTermsandconditions()
	{
		$_lanz = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_termsandconditions` as cp ORDER BY cp.id ASC");
		$_lanz->execute();
		$_lanz = $_lanz->fetch(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}

	// WHY US

	public function traerWhyUs()
	{
		$_lanz = Pd::instancia()->prepare("SELECT cpf.* FROM `contenidos_whyus` as cpf ORDER BY cpf.id ASC");
		$_lanz->execute();
		$_lanz = $_lanz->fetch(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}



	// DISCLAIMER

	public function traerDisclaimer()
	{
		$_lanz = Pd::instancia()->prepare("SELECT cpf.* FROM `contenidos_disclaimer` as cpf ORDER BY cpf.id ASC");
		$_lanz->execute();
		$_lanz = $_lanz->fetch(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}
	

	// FAQ

	public function traerFaqs()
	{
		$_lanz = Pd::instancia()->prepare("SELECT cpf.* FROM `contenidos_faqs` as cpf ORDER BY cpf.id ASC");
		$_lanz->execute();
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}

	// USER

	public static function traerUserPorId($_id)
	{
		$_id = (int) $_id;
		return  contenidos_user::find(array('conditions' => array('id = ?', $_id)));
	}

	public function traerUser($_id)
	{
		// return contenidos_user::find($_id);

		$result = Pd::instancia()->prepare("SELECT cu.* FROM `contenidos_users` as cu WHERE cu.id = :id");
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

	public function traerIncomeBigForChild()
	{

		$result = Pd::instancia()->prepare("SELECT cu.* FROM `contenidos_calculos_mayores` as cu WHERE cu.id = :id");
		$result->execute(array(":id" => 1));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}


	public function calculateChildSupport($_id_form)
	{

		// $_id_form = 7;

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

		// if($_mother_child_care >0){
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

		if($_mo_monthly_child_obligation_2 > $_fa_monthly_child_obligation_2){
			$_monthly_payment_mo = $_mo_monthly_child_obligation_2 - $_fa_monthly_child_obligation_2;
			$_monthly_payment_fa = 0;
		}else if ($_fa_monthly_child_obligation_2 > $_mo_monthly_child_obligation_2){
			$_monthly_payment_fa = $_fa_monthly_child_obligation_2 - $_mo_monthly_child_obligation_2;
			$_monthly_payment_mo = 0;
		}else{
			$_monthly_payment_mo = $_fa_monthly_child_obligation_2 - $_mo_monthly_child_obligation_2;	
			$_monthly_payment_fa = $_fa_monthly_child_obligation_2 - $_mo_monthly_child_obligation_2;			
		}


		$data = array();
		$data['mother_income'] = $_mother;
		$data['father_income'] = $_father;
		$data['mother_child_support_oblig'] = round($_mo_monthly_child_obligation_2, 2);
		$data['father_child_support_oblig'] = round($_fa_monthly_child_obligation_2, 2);
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




	// ORDERS

	public function traerOrders($_id, $_estado)
	{
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cc.* FROM `contenidos_compras` as cc WHERE cc.id_user = :id AND cc.estado = :estado ORDER BY cc.fecha DESC");
		$result->execute(array(":id" => $_id, ":estado" => $_estado));
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerOrder($_id)
	{
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cc.* FROM `contenidos_compras` as cc WHERE cc.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerOrder2($_id)
	{
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cc.*,cu.email as 'email_user' FROM `contenidos_compras` as cc
											LEFT JOIN  `contenidos_users` as cu 
											ON cc.id_user = cu.id
											WHERE cc.id = :id");

		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerDataOrder($_id)
	{
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT ccd.* FROM `contenidos_compras_detalle` as ccd WHERE ccd.id_compra = :id ORDER BY ccd.fecha ASC");
		$result->execute(array(":id" => $_id));
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	// FORMS

	public function traerFormsPorUser($_id, $_compra)
	{
		$_id = (int) $_id;
		$_compra = (int) $_compra;
		$result = Pd::instancia()->prepare("SELECT cc.* FROM `contenidos_forms_respuestas` as cc WHERE cc.id_user = :id AND cc.id_compra = :compra ORDER BY cc.fecha ASC");
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
	
	public function traerFormsPorId2($_id, $_user)
	{
		$_id = (int) $_id;
		$_user = (int) $_user;
		$result = Pd::instancia()->prepare("SELECT cc.* FROM `contenidos_forms_respuestas` as cc WHERE cc.id = :id AND id_user = :user");
		$result->execute(array(":id" => $_id, ":user" => $_user));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public static function traerFormsPorIdStatic($_id)
	{
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cc.* FROM `contenidos_forms_respuestas` as cc WHERE cc.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerFormsModules($_id)
	{
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cc.* FROM `contenidos_forms_modulos` as cc WHERE cc.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}


	public function checkFinalForm($_id_prod, $_id_form)
	{
		$_modules =  $this->traerFormsModules($_id_prod);
		unset($_modules['id'], $_modules['id_producto']);

		foreach ($_modules as $key => $val) {
			if($val !='si'){
				unset($_modules[$key]);
			}
		}

		$_form_resp =  $this->traerFormsPorId($_id_form);
		unset($_form_resp['id'], $_form_resp['id_compra'], $_form_resp['id_user'], $_form_resp['id_producto'], $_form_resp['estado'], $_form_resp['img'], $_form_resp['fecha']);

		foreach ($_form_resp as $key => $val) {
			if($val ==''){
				unset($_form_resp[$key]);
			}else{
				$_form_resp[$key]='si';
			}
		}

		$_comparar = array_diff_assoc($_modules, $_form_resp);
		if(empty($_comparar)){
			$_datos = contenidos_forms_respuesta::find(array('conditions' => array('id = ?', $_id_form)));	
			if($_datos){
				$_datos->estado = 'complete';						
				$_datos->save();
			}
		}

		return $_comparar;
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





	public function traerSliders($_estado)
	{
		$_data = Pd::instancia()->prepare("SELECT cep.* FROM `contenidos_elementos_portada` as cep WHERE cep.estado = :estado ORDER BY cep.posicion ASC");
		$_data->execute(array(':estado' => $_estado));
		$_data = $_data->fetchAll(PDO::FETCH_ASSOC);

		if($_data){

			for ($i=0; $i <count($_data); $i++) { 
				$_datos = Pd::instancia()->prepare("SELECT ci.* FROM `contenidos_imagenes` as ci WHERE ci.identificador = :identificador ORDER BY ci.id ASC");
				$_datos->execute(array(':identificador' => $_data[$i]['identificador']));
				$_datos = $_datos->fetchAll(PDO::FETCH_ASSOC);
				$_data[$i]['imagenes'] = $_datos;
			}
		}

		return ($_data) ? $_data : false;
	}


	public function traerCursos($_estado)
	{
		$_data = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_cursos` as cp WHERE cp.estado = :estado ORDER BY cp.id DESC");
		$_data->execute(array(":estado" => $_estado));
		$_data = $_data->fetchAll(PDO::FETCH_ASSOC);

		return ($_data) ? $_data : false;
	}

	public function traerCursosPorSuc($_suc, $_estado)
	{
		$_suc = (int) $_suc;
		$_data = Pd::instancia()->prepare("SELECT cp.*,ccc.nombre as 'categoria' FROM `contenidos_cursos` as cp 
											INNER JOIN `contenidos_cursos_categorias` as ccc
											ON cp.id_categoria = ccc.id
											WHERE cp.id_sucursal = :suc AND cp.estado = :estado 
											ORDER BY cp.id DESC");

		$_data->execute(array(":suc" => $_suc, ":estado" => $_estado));
		$_data = $_data->fetchAll(PDO::FETCH_ASSOC);

		return ($_data) ? $_data : false;
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

	public static function traerCursoPorId($_id)
	{
		return contenidos_curso::find(array('conditions' => array('id = ?', $_id)));
	}


	public function traerSucursales()
	{
		$result = Pd::instancia()->prepare("SELECT cs.*,ci.path FROM `contenidos_sucursales` as cs 
											INNER JOIN `contenidos_imagenes` as ci
											ON cs.identificador = ci.identificador
											ORDER BY cs.id ASC");
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
			
	}

	public function traerSucursal($_id)
	{
		// return contenidos_user::find($_id);
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cs.* FROM `contenidos_sucursales` as cs WHERE cs.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerGrupo($_id)
	{
		// return contenidos_user::find($_id);
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cg.* FROM `contenidos_grupos` as cg WHERE cg.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}


	public function traerCompras($_id)
	{
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cc.* FROM `contenidos_compras` as cc WHERE cc.id_user = :id ORDER BY cc.fecha DESC");
		$result->execute(array(":id" => $_id));
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerDataCompras($_id)
	{
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT ccd.* FROM `contenidos_compras_detalle` as ccd WHERE ccd.id_compra = :id ORDER BY ccd.fecha ASC");
		$result->execute(array(":id" => $_id));
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerCompraPorId($_id)
	{
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cc.*,cu.nombre,cu.apellido,cu.email 
											FROM `contenidos_compras` as cc											
											LEFT JOIN  `contenidos_users` as cu 
											ON cu.id = cc.id_user
											WHERE cc.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		if($result){

			$result['detalle_compra'] = self::traerDatosCarrito($result['id']);
		}

		return ($result) ? $result : false;
	}


	public function traerDatosCarrito($_compra)
	{

		$_lanz = Pd::instancia()->prepare("SELECT cc.* FROM `contenidos_compras_detalle` as cc WHERE cc.id_compra = :compra ORDER BY cc.id ASC");
		$_lanz->execute(array(":compra" => $_compra));
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
			
	}

	public static function traerCompraStatic($_id)
	{
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cc.* FROM `contenidos_compras` as cc WHERE cc.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
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

	public function guardarEncuestaRegistro()
	{
		$_asig = new contenidos_encuesta_registro();		
		$_asig->fecha = date('Y-m-d');
		$_asig->fecha_hora = date('Y-m-d H:i:s');
		$_asig->save();

		return ($_asig->save()) ? $_asig->id : false;
	}
	
	public function guardarEncuestaRespuesta($_id_encuesta, $_id_suc, $_id_cat, $_id_preg, $_instructor, $_respuesta)
	{
		$_asig = new contenidos_encuesta_respuesta();		
		$_asig->id_encuesta = $_id_encuesta;
		$_asig->id_sucursal = $_id_suc;
		$_asig->id_categoria = $_id_cat;
		$_asig->id_pregunta = $_id_preg;
		$_asig->nombre_instructor = $_instructor;
		$_asig->respuesta = ($_id_preg!=7) ? $_respuesta : 0;
		$_asig->respuesta_texto = ($_id_preg==7) ? $_respuesta : '';
		$_asig->save();

		return ($_asig->save()) ? true : false;
	}


	//////////////////////////////////////////////////////////////////////////////////////////////////


















	public function traerDestacados()
	{
		$result = Pd::instancia()->prepare("SELECT cd.*,cp.nombre,ci.path FROM `contenidos_destacados` as cd
											INNER JOIN `contenidos_productos` as cp
											ON cd.id_producto = cp.id 
											INNER JOIN `contenidos_imagenes` as ci
											ON cp.identificador = ci.identificador AND ci.posicion = 'principal'
											ORDER BY cd.posicion ASC");
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerOurbestsellers()
	{
		// $result = Pd::instancia()->prepare("SELECT co.* FROM `contenidos_ourbestsellers` as co ORDER BY co.posicion ASC");
		$result = Pd::instancia()->prepare("SELECT co.posicion,cp.*,ci.path FROM `contenidos_ourbestsellers` as co
											INNER JOIN `contenidos_productos` as cp
											ON co.id_producto = cp.id 
											INNER JOIN `contenidos_imagenes` as ci
											ON cp.identificador = ci.identificador AND ci.posicion = 'principal'
											ORDER BY co.posicion ASC");

		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerTestimonios($_estado)
	{
		$result = Pd::instancia()->prepare("SELECT ct.* FROM `contenidos_testimonios` as ct WHERE ct.estado = :estado ORDER BY ct.fecha DESC LIMIT 2");
		$result->execute(array(':estado' => $_estado));
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerTodosTestimonios($_estado)
	{
		$result = Pd::instancia()->prepare("SELECT ct.* FROM `contenidos_testimonios` as ct WHERE ct.estado = :estado ORDER BY ct.fecha DESC");
		$result->execute(array(':estado' => $_estado));
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}


	public function traerTodosProductos($_estado)
	{
		$_data = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_productos` as cp WHERE cp.estado = :estado ORDER BY cp.id DESC");
		$_data->execute(array(":estado" => $_estado));
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
				// $_data[$i]['imagenes'] = implode(';', $_arrayImg);
				$_data[$i]['imagenes'] = $_arrayImg;
			}
			

		}	



		return ($_data) ? $_data : false;
	}

	public function traerProdPresentaciones()
	{
		$_lanz = Pd::instancia()->prepare("SELECT cpp.* FROM `contenidos_productos_presentacion` as cpp ORDER BY cpp.id ASC");
		$_lanz->execute();
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}

	

	public function traerPreguntasFrecuentes()
	{
		$_lanz = Pd::instancia()->prepare("SELECT cpf.* FROM `contenidos_preguntas_frecuentes` as cpf ORDER BY cpf.id DESC");
		$_lanz->execute();
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}


	public function traerTodosAboutus()
	{
		$_data = Pd::instancia()->prepare("SELECT ca.* FROM `contenidos_aboutus` as ca ORDER BY ca.id ASC LIMIT 2");
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
				// $_data[$i]['imagenes'] = implode(';', $_arrayImg);
				$_data[$i]['imagenes'] = $_arrayImg;
			}


			for ($i=0; $i < count($_data); $i++) { 
				$_vids = Pd::instancia()->prepare("SELECT cv.* FROM `contenidos_videos` as cv WHERE cv.identificador = :identificador ORDER BY cv.orden ASC");
				$_vids->execute(array(':identificador' => $_data[$i]['identificador']));
				$_vids = $_vids->fetchAll(PDO::FETCH_ASSOC);
				$_arrayVid=array();
				foreach ($_vids as $val) {
					$_arrayVid[]=$val['path'];
				}
				$_data[$i]['videos'] = $_arrayVid;
			}
			

		}	

		return ($_data) ? $_data : false;
	}


	public function traerShipping($_id)
	{
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_shipping` as cp WHERE cp.id_user = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerShippingPorId($_id)
	{
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_shipping` as cp WHERE cp.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public static function traerShippingStatic($_id)
	{
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_shipping` as cp WHERE cp.id_user = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerBilling($_id)
	{
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cb.* FROM `contenidos_billing` as cb WHERE cb.id_user = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerBillingPorId($_id)
	{
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cb.* FROM `contenidos_billing` as cb WHERE cb.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	

	//////////////////////////////////////////////////////////////////////////////////////

	public function traerCategorias()
	{
		$_lanz = Pd::instancia()->prepare("SELECT cc.* FROM `contenidos_categorias` as cc ORDER BY cc.id ASC");
		$_lanz->execute();
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}
	/*public function traerCategoriasRel()
	{
		$_lanz = Pd::instancia()->prepare("SELECT cc.* FROM `contenidos_categorias` as cc ORDER BY rand() LIMIT 10");
		$_lanz->execute();
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}*/
	public static function traerCategoriaPorItem($_item)
	{
		$result = Pd::instancia()->prepare("SELECT cc.* FROM `contenidos_categorias` as cc WHERE cc.item = :item");
		$result->execute(array(":item" => $_item));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public static function traerCategoriaPorId($_id)
	{
		$result = Pd::instancia()->prepare("SELECT cc.* FROM `contenidos_categorias` as cc WHERE cc.id= :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerPromociones($_estado)
	{
		$_estado = (int) $_estado;
		$_lanz = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_promociones` as cp WHERE cp.estado = :estado ORDER BY cp.id DESC");
		$_lanz->execute(array(":estado" => $_estado));
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}


	public static function traerSubcategoriasPorCat($_id)
	{
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT csc.* FROM `contenidos_subcategorias` as csc WHERE csc.id_cat = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public static function traerSubcategoriaPorItem($_item)
	{
		$result = Pd::instancia()->prepare("SELECT csc.* FROM `contenidos_subcategorias` as csc WHERE csc.item = :item");
		$result->execute(array(":item" => $_item));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}


	/*public function traerProductos($_estado)
	{
		$_estado = (int) $_estado;
		$_lanz = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_productos` as cp WHERE cp.estado = :estado ORDER BY cp.id DESC");
		$_lanz->execute(array(":estado" => $_estado));
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}

	public function traerProductosPorCat($_estado, $_cat)
	{
		$_estado = (int) $_estado;
		$_cat = (int) $_cat;
		$_lanz = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_productos` as cp WHERE cp.id_categoria = :cat AND cp.estado = :estado ORDER BY cp.precio DESC");
		$_lanz->execute(array(":estado" => $_estado, ":cat" => $_cat));
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}

	public function traerProductosRel($_estado)
	{
		$_estado = (int) $_estado;
		// $_cat = (int) $_cat;
		$_lanz = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_productos` as cp WHERE cp.estado = :estado ORDER BY rand() LIMIT 10");
		$_lanz->execute(array(":estado" => $_estado));
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}

	public function traerProductosPorCatRel($_estado, $_cat)
	{
		$_estado = (int) $_estado;
		$_cat = (int) $_cat;
		$_lanz = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_productos` as cp WHERE cp.id_categoria = :cat AND cp.estado = :estado ORDER BY rand() LIMIT 10");
		$_lanz->execute(array(":estado" => $_estado, ":cat" => $_cat));
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}

	public function traerProductosPorCatSubcat($_estado, $_cat, $_subcat)
	{
		$_estado = (int) $_estado;
		$_cat = (int) $_cat;
		$_subcat = (int) $_subcat;
		$_lanz = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_productos` as cp WHERE cp.id_categoria = :cat AND cp.id_subcategoria = :subcat AND cp.estado = :estado ORDER BY cp.precio DESC");
		$_lanz->execute(array(":estado" => $_estado, ":cat" => $_cat, ":subcat" => $_subcat));
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}


	public static function traerProductosPorItem($_item)
	{
		$result = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_productos` as cp WHERE cp.item = :item");
		$result->execute(array(":item" => $_item));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public static function traerProductosPorId($_id)
	{
		$result = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_productos` as cp WHERE cp.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}


	public function traerProdPorOrden($_estado, $_cat, $_orden)
	{
		$_estado = (int) $_estado;
		$_cat = (int) $_cat;
		// $_orden = (int) $_orden;
		if($_orden == 1){
			$_order = 'cp.precio DESC';
		}else if($_orden == 2){
			$_order = 'cp.precio ASC';
		}
		$_lanz = Pd::instancia()->prepare("SELECT cp.* FROM `contenidos_productos` as cp WHERE cp.id_categoria = :cat AND cp.estado = :estado ORDER BY $_order");
		$_lanz->execute(array(":estado" => $_estado, ":cat" => $_cat));
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
	}*/


	public function traerBanners($_sec, $_estado)
	{
		// return  contenidos_banner::find('all',array('conditions' => array('estado = ?', $_estado),'order' => 'id desc'));

		$_lanz = Pd::instancia()->prepare("SELECT cb.* FROM `contenidos_banners` as cb WHERE cb.id_seccion = :seccion AND cb.estado = :estado ORDER BY cb.id DESC");
		$_lanz->execute(array(":seccion" => $_sec, ":estado" => $_estado));
		$_lanz = $_lanz->fetchAll(PDO::FETCH_ASSOC);

		return ($_lanz) ? $_lanz : false;
			
	}

	/*BUSCADOR*/

	/*public function traerBuscador($_valor)
	{
		$result = Pd::instancia()->prepare("(SELECT ca.id,ca.titulo,ca.descripcion,'anuario' as tabla FROM `contenidos_anuario` as ca WHERE ca.titulo LIKE '%".$_valor."%' OR ca.descripcion LIKE '%".$_valor."%')
											UNION ALL 
											(SELECT cc.id,cc.titulo,cc.descripcion,'calendario' as tabla FROM `contenidos_calendario` as cc WHERE cc.titulo LIKE '%".$_valor."%' OR cc.descripcion LIKE '%".$_valor."%')
											UNION ALL 
											(SELECT cd.id,cd.titulo,cd.descripcion,'directorio' as tabla FROM `contenidos_directorio` as cd WHERE cd.titulo LIKE '%".$_valor."%')
											UNION ALL 
											(SELECT cid.id,cid.titulo,cid.descripcion,'infodeportiva' as tabla FROM `contenidos_info_deportiva` as cid WHERE cid.titulo LIKE '%".$_valor."%') ORDER BY id DESC");



		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return  $result;
		
	}*/

	public function traerBuscadorProd($_valor)
	{
		$result = Pd::instancia()->prepare("SELECT cp.id,cp.nombre,cp.item,cp.identificador,'productos' as tabla FROM `contenidos_productos` as cp WHERE cp.nombre LIKE '%".$_valor."%' ORDER BY cp.id DESC");

		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return  $result;
		
	}


	public function traerBuscadorCat($_valor)
	{
		$result = Pd::instancia()->prepare("SELECT cc.id,cc.nombre,cc.item,cc.identificador,'categorias' as tabla FROM `contenidos_categorias` as cc WHERE cc.nombre LIKE '%".$_valor."%' ORDER BY cc.id DESC");

		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return  $result;
		
	}

	public function traerBuscadorSubcat($_valor)
	{
		$result = Pd::instancia()->prepare("SELECT csc.id,csc.id_cat,csc.nombre,csc.item,csc.identificador,'subcategorias' as tabla FROM `contenidos_subcategorias` as csc WHERE csc.nombre LIKE '%".$_valor."%' ORDER BY csc.id DESC");

		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return  $result;
		
	}

	public static function armarLink($_tabla, $_ruta, $_item='', $_id_cat='', $_id='')
	{
		$_tabla = (string) $_tabla;		

		switch ($_tabla) {		   
		    case 'categorias':
		        return $_ruta.'productos/filtrar/'.$_item;
		        break;
		    case 'subcategorias':
		       	$_dat = self::traerCategoriaPorId($_id_cat);
		       	return $_ruta.'productos/filtrar/'.$_dat['item'].'/'.$_item;
		        break;
		    case 'productos':
		    	// $_dat = self::traerProductosPorId($_id);
		        return $_ruta.'productos/detalle/'.$_item;
		        break;
		    default:
		    	return '';
		} 
	}

	


	public function traerCodigoDescuento($_cod)
	{
		$result = Pd::instancia()->prepare("SELECT cd.* FROM `contenidos_descuentos` as cd WHERE cd.codigo = :cod");
		$result->execute(array(":cod" => $_cod));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public static function traerCodigoDescuentoPorId($_id)
	{
		$_id = (int) $_id;
		return  contenidos_descuento::find(array('conditions' => array('id = ?', $_id)));
	}

	/*ENVIO*/

	public function traerDireccionesEnvios($_id)
	{
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cde.* FROM `contenidos_direccion_envios` as cde WHERE cde.id_user = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerDireccionEnvio($_id)
	{
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cde.* FROM `contenidos_direccion_envios` as cde WHERE cde.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public static function traerDireccionEnvioStatic($_id)
	{
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cde.* FROM `contenidos_direccion_envios` as cde WHERE cde.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerDatosFacturacion($_id)
	{
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cdf.* FROM `contenidos_datos_facturacion` as cdf WHERE cdf.id_user = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	public function traerCFDI()
	{
		$result = Pd::instancia()->prepare("SELECT cfc.* FROM `contenidos_facturacion_cfdi` as cfc ORDER BY cfc.id ASC");
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
			
	}

	public static function traerCFDIporId($_valor)
	{
		return  contenidos_facturacion_cfd::find(array('conditions' => array('id = ?', $_valor)));
	}

	public function traerDireccionFacturacion($_id)
	{
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cfd.* FROM `contenidos_facturacion_direcciones` as cfd WHERE cfd.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}
	public static function traerDireccionFacturacionStatic($_id)
	{
		$_id = (int) $_id;
		$result = Pd::instancia()->prepare("SELECT cfd.* FROM `contenidos_facturacion_direcciones` as cfd WHERE cfd.id = :id");
		$result->execute(array(":id" => $_id));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}

	
	public function traerEstados()
	{
		$result = Pd::instancia()->prepare("SELECT cec.* FROM `contenidos_envios_codpostales` as cec ORDER BY cec.id ASC");
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
			
	}

	public static function traerEstadoPorAbv($_valor)
	{
		return  contenidos_envios_codpostale::find(array('conditions' => array('abreviatura = ?', $_valor)));
	}


	public function traerMedidasCaja()
	{
		$result = Pd::instancia()->prepare("SELECT cmc.* FROM `contenidos_medidas_cajas` as cmc ORDER BY cmc.piezas DESC");
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
			
	}

	public static function traerMedidasCajaPorId($_valor)
	{
		// return  contenidos_medidas_caja::find(array('conditions' => array('piezas = ?', $_valor)));

		$result = Pd::instancia()->prepare("SELECT cmc.* FROM `contenidos_medidas_cajas` as cmc WHERE cmc.id = :valor");
		$result->execute(array(':valor' => $_valor));
		$result = $result->fetch(PDO::FETCH_ASSOC);

		return ($result) ? $result : false;
	}


////////////////////////////////////////



	/*public function traerProductos($_estado)
	{
		return  contenidos_producto::find('all',array('conditions' => array('estado = ?', $_estado),'order' => 'id desc'));
			
	}*/
	
	public function traerProductosFiltro($_estado, $_ancho, $_perfil, $_rodado)
	{
		
		//return  contenidos_trabajo::find('all',array('conditions' => array('estado = ?', $_estado),'order' => 'fecha_publicacion asc'));
		//return contenidos_trabajo::find_by_sql("SELECT id FROM contenidos_trabajos WHERE estado = ".$_estado." OR id_area IN (".$_area.") OR provincia IN (".$_prov.") ORDER BY fecha_publicacion ASC");
		//return contenidos_trabajo::find_by_sql("SELECT id FROM contenidos_trabajos WHERE estado = 1 OR id_area IN (0) OR provincia IN (9) ORDER BY fecha_publicacion ASC");
		
		
		$arrayCont = array();
		$_html	='SELECT * FROM contenidos_productos WHERE ';
		
		if($_ancho!=0){
			$arrayCont[] = 'ancho IN ('.$_ancho.')';
		}
		
		if($_perfil!=0){
			$arrayCont[] = 'perfil IN ('.$_perfil.')';
		}
		
		if($_rodado!=0){
			$arrayCont[] = 'rodado IN ('.$_rodado.')';
		}
		
		foreach ($arrayCont as $arr){
			$_html .= $arr. " AND ";																									
		}
		
		$_html .= "estado = ".$_estado." ORDER BY id DESC";
		
		$_data = contenidos_producto::find_by_sql($_html);
		
		return $_data;
		
			
	}
	
	/*public function traerProductosPorCat($_cat, $_estado)
	{
		return  contenidos_producto::find('all',array('conditions' => array('id_categoria = ? AND estado = ?', $_cat, $_estado),'order' => 'id desc'));
			
	}*/
	
	/*public function traerProductosAnchos($_estado)
	{
		return contenidos_producto::find_by_sql("SELECT DISTINCT ancho FROM contenidos_productos WHERE estado = ".$_estado." ORDER BY ancho DESC");
			
	}
	
	public function traerProductosPerfiles($_estado)
	{
		return contenidos_producto::find_by_sql("SELECT DISTINCT perfil FROM contenidos_productos WHERE estado = ".$_estado." ORDER BY perfil DESC");
			
	}
	
	public function traerProductosRodados($_estado)
	{
		return contenidos_producto::find_by_sql("SELECT DISTINCT rodado FROM contenidos_productos WHERE estado = ".$_estado." ORDER BY rodado DESC");
			
	}
	
	
	public static function traerProductosPorIdPromo($_promo, $_estado)
	{
		return  contenidos_producto::find(array('conditions' => array('id_promo = ? AND estado = ?', $_promo, $_estado),'order' => 'id desc'));
			
	}
	
	public function traerProducto($_id)
	{
		return contenidos_producto::find($_id);
	}
	
	public static function traerProductoPorId($_id)
	{
		return contenidos_producto::find($_id);
	}*/
	
	/*public function traerPromociones($_estado, $_limit)
	{
		$_data = contenidos_promocione::find(array('conditions' => array('estado = ?', $_estado),'select' => 'count(*) as cant'));
		$_fila = $_data->cant;
		$_aleatorio = rand(0, $_fila-1);
		return  contenidos_promocione::find('all',array('conditions' => array('estado = ?', $_estado),'order' => 'id desc','limit' => $_limit, 'offset' => $_aleatorio));
			
	}*/
	
	public function traerPromocionesPorCat($_cat, $_estado, $_limit)
	{
		$_data = contenidos_promocione::find(array('conditions' => array('id_seccion = ? AND estado = ?',$_cat, $_estado),'select' => 'count(*) as cant'));
		$_fila = $_data->cant;
		$_aleatorio = rand(0, $_fila-1);
		return  contenidos_promocione::find('all',array('conditions' => array('id_seccion = ? AND estado = ?', $_cat, $_estado),'order' => 'id desc','limit' => $_limit, 'offset' => $_aleatorio));
			
	}
	public function traerPromo($_id)
	{
		return contenidos_promocione::find($_id);
	}
	
	public static function traerPromoPorId($_id)
	{
		return contenidos_promocione::find($_id);
	}
	
	/*public function traerBanners($_estado, $_limit)
	{
		$_data = contenidos_banner::find(array('conditions' => array('estado = ?', $_estado),'select' => 'count(*) as cant'));
		$_fila = $_data->cant;
		$_aleatorio = rand(0, $_fila-1);
		//contenidos_producto::find('all', array('limit' => 10, 'offset' => 5));
		return  contenidos_banner::find('all',array('conditions' => array('estado = ?', $_estado),'order' => 'id desc','limit' => $_limit, 'offset' => $_aleatorio));
			
	}
	
	public function traerBannersPorCat($_cat, $_estado, $_limit)
	{
		$_data = contenidos_banner::find(array('conditions' => array('id_seccion = ? AND estado = ?',$_cat, $_estado),'select' => 'count(*) as cant'));
		$_fila = $_data->cant;
		$_aleatorio = rand(0, $_fila-1);
		return  contenidos_banner::find('all',array('conditions' => array('id_seccion = ? AND estado = ?',$_cat, $_estado),'order' => 'id desc','limit' => $_limit, 'offset' => $_aleatorio));
	}*/
	
	/*public static function traerCategoria($_id)
	{
		return contenidos_categoria::find($_id);
	}
	
	
	public function traerProductosRelacionados($_id, $_cat, $_limit)
	{
		//return  contenidos_datos_imagene::find('all',array('conditions' => array('dia = ?', $_dia)));
		return contenidos_producto::find('all',array('conditions' => array('id_categoria = ? AND id != ?', $_cat, $_id),'order' => 'id desc','limit' => $_limit));
	}*/
	
	
	public static function traerImgLista($_identificador, $_tipo, $_pos, $_orden)
	{
		return  contenidos_imagene::find(array('conditions' => array('identificador = ? AND tipo = ? AND posicion = ? AND orden = ?', $_identificador, $_tipo, $_pos, $_orden)));
	}
	
	public static function traerImg($_identificador, $_tipo, $_pos='')
	{
		return  contenidos_imagene::find(array('conditions' => array('identificador = ? AND tipo = ? AND posicion = ?', $_identificador, $_tipo, $_pos)));
	}

	
	
	public function traerImgGal($_identificador, $_tipo, $_pos)
	{
		return  contenidos_imagene::find('all',array('conditions' => array('identificador = ? AND tipo = ? AND posicion = ?', $_identificador, $_tipo, $_pos),'order' => 'orden asc'));
	}
	
	public static function traerUserPorIdenficador($_identificador)
	{
		return  contenidos_user::find(array('conditions' => array('identificador = ?', $_identificador)));
	}
	
	
	public static function traerPedidoPorId($_id)
	{
		return contenidos_pedido::find($_id);
	}
	
	public static function calcularPorcentaje($_cantidad,$_porciento,$_decimales='')
	{
		$_cantidad = self::getFloat($_cantidad);
		$_cantidadDesc = number_format($_cantidad*$_porciento/100,$_decimales);		
				
		return self::calcularDescuento($_cantidad, $_cantidadDesc);
		
		
	}
	
	private static function calcularDescuento($_monto_total, $_monto_descuento)
	{
		return $_monto_total - $_monto_descuento;
	}
	
	
	private static function getFloat($str)
	{ 
		  if(strstr($str, ",")) { 
			$str = str_replace(".", "", $str); // replace dots (thousand seps) with blancs 
			$str = str_replace(",", ".", $str); // replace ',' with '.' 
		  } 
		  
		  if(preg_match("#([0-9\.]+)#", $str, $match)) { // search for number that may contain '.' 
			return floatval($match[0]); 
		  } else { 
			return floatval($str); // take some last chances with floatval 
		  } 
	} 
	
	////////////////////////////////////////////////////////////////////////////
	
	
	
	
	
	
	
	
	
	
	public function traerPostulacionEspontanea()
	{
		return contenidos_trabajo::find(array('conditions' => array('id_area = ? AND estado = ?', 0, 3)));
			
	}
	
	
	public function traerTrabajo($_id)
	{
		return contenidos_trabajo::find(array('conditions' => array('identificador = ?', $_id)));
			
	}
	
	public static function traerProvincia($_id)
	{
		return contenidos_provincia::find($_id);
	}
	
	public static function traerLocalidad($_id)
	{
		return contenidos_localidade::find($_id);
	}
	
	public static function traerLocalidadPorId($_id)
	{
		//return contenidos_localidade::find($_id);
		return contenidos_localidade::find(array('conditions' => array('id_localidad = ?', $_id)));
	}
	
	public function traerSexo()
	{
		return contenidos_sex::all(array('order' => 'id asc'));
	}
	
	public function traerEstadoCivil()
	{
		return contenidos_estado_civi::all(array('order' => 'id asc'));
	}
	
	public function traerNacionalidades()
	{
		return contenidos_nacionalidade::all(array('order' => 'nombre asc'));
	}
	
	public function traerProvincias()
	{
		return contenidos_provincia::all(array('order' => 'nombre asc'));
	}
	
	public function traerLocalidadesPorId($_id)
	{
		return contenidos_localidade::find('all',array('conditions' => array('id_prov = ?', $_id),'order' => 'nombre asc'));
			
	}
	public function traerUniversidades()
	{
		return contenidos_universidade::all(array('order' => 'nombre asc'));
	}
	
	public function traerCarreras()
	{
		return contenidos_carrera::all(array('order' => 'nombre asc'));
	}
	
	public function traerTipoDoc()
	{
		return contenidos_tipo_document::all(array('order' => 'id asc'));
	}
	
		public function traerAreas()
	{
		return contenidos_area::all(array('order' => 'nombre asc'));
	}
	
	public function traerArea($_id)
	{
		return contenidos_area::find($_id);
	}
	
	public function traerSectores()
	{
		return  contenidos_sectore::find('all',array('order' => 'id asc'));
			
	}
	
	public static function traerAreaPorID($_id)
	{
		return contenidos_area::find($_id);
	}
	
	public static function traerDataImagenPorIdentificador($_identificador, $_pos)
	{	
		return  contenidos_imagene::find(array('conditions' => array('identificador = ? AND posicion = ?', $_identificador, $_pos)));		
	}
	
	
	
	
	
	
	
	
	/*public function traerUser($_id)
	{
		return contenidos_user::find($_id);
	}*/
	
	/*public static function traerUserPorId($_id)
	{
		return contenidos_user::find($_id);
	}
	*/
	public static function traerDataImagen($_id)
	{
	
		return  contenidos_imagene::find(array('conditions' => array('id = ?', $_id)));
		
	}
	
	
	
	public static function traerImgMod($_identificador, $_modulo, $_pos)
	{
		return  contenidos_imagene::find(array('conditions' => array('identificador = ? AND modulo = ? AND posicion = ?', $_identificador, $_modulo, $_pos)));
	}
	
	public static function traerImgMedio($_identificador, $_id)
	{
		return  contenidos_imagene::find(array('conditions' => array('identificador = ? AND medio = ?', $_identificador, $_id)));
	}
	
	
	
	
	public function traerEquipoCompleto()
	{
		$results = contenidos_equip::all(array('order' => 'orden asc'));
		$_datos = $this->armarArray($results);
		return $_datos;
	}
	
	private function armarArray($_val)
	{
		$arrayResult = array_map(function($res){
		  return $res->attributes();
		}, $_val);
		return $arrayResult;
	}
	public function traerNoticias()
	{
		$results = contenidos_blo::all(array('order' => 'orden asc'));
		$_datos = $this->armarArray($results);
		return $_datos;
	}
	
	public function traerCatNoticias()
	{
		$results = contenidos_blo::all(array('select' => 'DISTINCT categoria','order' => 'categoria asc'));
		$_datos = $this->armarArray($results);
		return $_datos;
	}
	
	public function traerNoticia($_id)
	{
		return contenidos_blo::find($_id);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function traerDatosImagenes($_dia, $_limit)
	{
		//return  contenidos_datos_imagene::find('all',array('conditions' => array('dia = ?', $_dia)));
		return contenidos_datos_imagene::find('all',array('conditions' => array('dia = ?', $_dia),'order' => 'id asc','limit' => $_limit));
	}
	
	public static function traerImgChica($_identificador)
	{
		return  contenidos_imagene::find(array('conditions' => array('identificador = ?', $_identificador)));
	}
	
	
	public static function contarRegistros($_dia)
	{
		return contenidos_datos_imagene::find(array('conditions' => array('dia = ?', $_dia),'select' => 'count(*) as cant'));
	}
	
	public function paginador($_dia,$_pag,$_limit)
	{
		//contenidos_producto::find('all', array('limit' => 10, 'offset' => 5));
		//"select * from persona order by nombre limit %d offset %d ", 5, ($pag-1)*5);
		return contenidos_datos_imagene::find('all',array('conditions' => array('dia = ?', $_dia),'order' => 'id asc','limit' => $_limit,'offset' => ($_pag-1)*$_limit));
	}
	
	
	
	
	
	
	
	
	public function traerDestacadoModuloActivo($_modulo)
	{
		return contenidos_destacados_modulo::find(array('conditions' => array('modulo = ?', $_modulo)));
	}
	
	/*public function traerDestacados()
	{
		return  contenidos_destacado::all(array('order' => 'orden asc'));
			
	}*/
	
	/*public static function traerImg($_identificador)
	{
		return  contenidos_imagene::find(array('conditions' => array('identificador = ?', $_identificador)));
	}*/
	
	public function traerBeneficios($_estado)
	{
		return  contenidos_beneficio::find('all',array('conditions' => array('estado = ?', $_estado),'order' => 'orden asc'));
			
	}
	
	public function traerManuales()
	{
		return  contenidos_manuale::all(array('order' => 'orden asc'));
			
	}
	
	public function traerCapacitaciones()
	{
		return  contenidos_capacitacione::all(array('order' => 'orden asc'));
			
	}
	
	public static function traerArchivo($_identificador)
	{
		return  contenidos_archivo::find(array('conditions' => array('identificador = ?', $_identificador)));
	}
	
	public static function traerJuegos()
	{
		return  contenidos_juego::all(array('order' => 'orden asc'));
			
	}
	
	
	
	
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////
	
	public function traerPromos($_estado)
	{
		return  contenidos_promo::find('all',array('conditions' => array('estado = ?', $_estado),'order' => 'orden asc'));		
	}
	
	public static function traerImgPpal($_identificador)
	{
		return  contenidos_imagene::find(array('conditions' => array('identificador = ? AND orden = ?', $_identificador,0)));
	}
	
	public static function traerImgFondo($_identificador)
	{
		return  contenidos_imagene::find(array('conditions' => array('identificador = ? AND orden = ?', $_identificador,1)));
	}
	
	public static function traerTermino($_identificador)
	{
		return  contenidos_termino::find(array('conditions' => array('identificador = ?', $_identificador)));
	}
	
	
	/**************************************************************************************************/
	public function CapturarAnchoAlto($recurso)											
	{
		$dimension = array();
		
		list($ancho, $alto) = getimagesize($recurso);
		$dimension[] = $ancho;
		$dimension[] = $alto;
		return $dimension;
	}
	
	public static function convertirCaracteres($_cadena)
	{
		return htmlspecialchars_decode(htmlspecialchars(html_entity_decode($_cadena)));
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
	
	public static function tildesHtml($cadena) 
	{ 
		return str_replace(array("á","é","í","ó","ú","ñ","Á","É","Í","Ó","Ú","Ñ"),
							array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&ntilde;","&Aacute;","&Eacute;","&Iacute;","&Oacute;","&Uacute;","&Ntilde;"),
							$cadena);     
	}
	
	public static function crearTitulo($_titulo)
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
	
	
	public static function crearUrl($_id, $_titulo)
	{
		$_id = (int) $_id;
		$a = array('Ã€', 'Ã�', 'Ã‚', 'Ãƒ', 'Ã„', 'Ã…', 'Ã†', 'Ã‡', 'Ãˆ', 'Ã‰', 'ÃŠ', 'Ã‹', 'ÃŒ', 'Ã�', 'ÃŽ', 'Ã�', 'Ã�', 'Ã‘', 'Ã’', 'Ã“', 'Ã”', 'Ã•', 'Ã–', 'Ã˜', 'Ã™', 'Ãš', 'Ã›', 'Ãœ', 'Ã�', 'ÃŸ', 'Ã ', 'Ã¡', 'Ã¢', 'Ã£', 'Ã¤', 'Ã¥', 'Ã¦', 'Ã§', 'Ã¨', 'Ã©', 'Ãª', 'Ã«', 'Ã¬', 'Ã­', 'Ã®', 'Ã¯', 'Ã±', 'Ã²', 'Ã³', 'Ã´', 'Ãµ', 'Ã¶', 'Ã¸', 'Ã¹', 'Ãº', 'Ã»', 'Ã¼', 'Ã½', 'Ã¿', 'Ä€', 'Ä�', 'Ä‚', 'Äƒ', 'Ä„', 'Ä…', 'Ä†', 'Ä‡', 'Äˆ', 'Ä‰', 'ÄŠ', 'Ä‹', 'ÄŒ', 'Ä�', 'ÄŽ', 'Ä�', 'Ä�', 'Ä‘', 'Ä’', 'Ä“', 'Ä”', 'Ä•', 'Ä–', 'Ä—', 'Ä˜', 'Ä™', 'Äš', 'Ä›', 'Äœ', 'Ä�', 'Äž', 'ÄŸ', 'Ä ', 'Ä¡', 'Ä¢', 'Ä£', 'Ä¤', 'Ä¥', 'Ä¦', 'Ä§', 'Ä¨', 'Ä©', 'Äª', 'Ä«', 'Ä¬', 'Ä­', 'Ä®', 'Ä¯', 'Ä°', 'Ä±', 'Ä²', 'Ä³', 'Ä´', 'Äµ', 'Ä¶', 'Ä·', 'Ä¹', 'Äº', 'Ä»', 'Ä¼', 'Ä½', 'Ä¾', 'Ä¿', 'Å€', 'Å�', 'Å‚', 'Åƒ', 'Å„', 'Å…', 'Å†', 'Å‡', 'Åˆ', 'Å‰', 'ÅŒ', 'Å�', 'ÅŽ', 'Å�', 'Å�', 'Å‘', 'Å’', 'Å“', 'Å”', 'Å•', 'Å–', 'Å—', 'Å˜', 'Å™', 'Åš', 'Å›', 'Åœ', 'Å�', 'Åž', 'ÅŸ', 'Å ', 'Å¡', 'Å¢', 'Å£', 'Å¤', 'Å¥', 'Å¦', 'Å§', 'Å¨', 'Å©', 'Åª', 'Å«', 'Å¬', 'Å­', 'Å®', 'Å¯', 'Å°', 'Å±', 'Å²', 'Å³', 'Å´', 'Åµ', 'Å¶', 'Å·', 'Å¸', 'Å¹', 'Åº', 'Å»', 'Å¼', 'Å½', 'Å¾', 'Å¿', 'Æ’', 'Æ ', 'Æ¡', 'Æ¯', 'Æ°', 'Ç�', 'ÇŽ', 'Ç�', 'Ç�', 'Ç‘', 'Ç’', 'Ç“', 'Ç”', 'Ç•', 'Ç–', 'Ç—', 'Ç˜', 'Ç™', 'Çš', 'Ç›', 'Çœ', 'Çº', 'Ç»', 'Ç¼', 'Ç½', 'Ç¾', 'Ç¿');
	   	$b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
	   	$_titulo = str_replace($a, $b, $_titulo);
	   	$_titulo = strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'), array('', '-', ''), $_titulo));

	   	 return $_id . '/' . $_titulo;
	}

	public static function crearUrlDos($_id, $_titulo, $_lang='')
	{
		$_id = (int) $_id;
		$find = array('á', 'é', 'í', 'ó', 'ú', 'ñ','aacute','eacute','iacute','oacute','uacute','ntilde');
		$repl = array('a', 'e', 'i', 'o', 'u', 'n','a', 'e', 'i', 'o', 'u', 'n');
		$_titulo = str_replace ($find, $repl, $_titulo);
		/*$a = array('Ã€', 'Ã�', 'Ã‚', 'Ãƒ', 'Ã„', 'Ã…', 'Ã†', 'Ã‡', 'Ãˆ', 'Ã‰', 'ÃŠ', 'Ã‹', 'ÃŒ', 'Ã�', 'ÃŽ', 'Ã�', 'Ã�', 'Ã‘', 'Ã’', 'Ã“', 'Ã”', 'Ã•', 'Ã–', 'Ã˜', 'Ã™', 'Ãš', 'Ã›', 'Ãœ', 'Ã�', 'ÃŸ', 'Ã ', 'Ã¡', 'Ã¢', 'Ã£', 'Ã¤', 'Ã¥', 'Ã¦', 'Ã§', 'Ã¨', 'Ã©', 'Ãª', 'Ã«', 'Ã¬', 'Ã­', 'Ã®', 'Ã¯', 'Ã±', 'Ã²', 'Ã³', 'Ã´', 'Ãµ', 'Ã¶', 'Ã¸', 'Ã¹', 'Ãº', 'Ã»', 'Ã¼', 'Ã½', 'Ã¿', 'Ä€', 'Ä�', 'Ä‚', 'Äƒ', 'Ä„', 'Ä…', 'Ä†', 'Ä‡', 'Äˆ', 'Ä‰', 'ÄŠ', 'Ä‹', 'ÄŒ', 'Ä�', 'ÄŽ', 'Ä�', 'Ä�', 'Ä‘', 'Ä’', 'Ä“', 'Ä”', 'Ä•', 'Ä–', 'Ä—', 'Ä˜', 'Ä™', 'Äš', 'Ä›', 'Äœ', 'Ä�', 'Äž', 'ÄŸ', 'Ä ', 'Ä¡', 'Ä¢', 'Ä£', 'Ä¤', 'Ä¥', 'Ä¦', 'Ä§', 'Ä¨', 'Ä©', 'Äª', 'Ä«', 'Ä¬', 'Ä­', 'Ä®', 'Ä¯', 'Ä°', 'Ä±', 'Ä²', 'Ä³', 'Ä´', 'Äµ', 'Ä¶', 'Ä·', 'Ä¹', 'Äº', 'Ä»', 'Ä¼', 'Ä½', 'Ä¾', 'Ä¿', 'Å€', 'Å�', 'Å‚', 'Åƒ', 'Å„', 'Å…', 'Å†', 'Å‡', 'Åˆ', 'Å‰', 'ÅŒ', 'Å�', 'ÅŽ', 'Å�', 'Å�', 'Å‘', 'Å’', 'Å“', 'Å”', 'Å•', 'Å–', 'Å—', 'Å˜', 'Å™', 'Åš', 'Å›', 'Åœ', 'Å�', 'Åž', 'ÅŸ', 'Å ', 'Å¡', 'Å¢', 'Å£', 'Å¤', 'Å¥', 'Å¦', 'Å§', 'Å¨', 'Å©', 'Åª', 'Å«', 'Å¬', 'Å­', 'Å®', 'Å¯', 'Å°', 'Å±', 'Å²', 'Å³', 'Å´', 'Åµ', 'Å¶', 'Å·', 'Å¸', 'Å¹', 'Åº', 'Å»', 'Å¼', 'Å½', 'Å¾', 'Å¿', 'Æ’', 'Æ ', 'Æ¡', 'Æ¯', 'Æ°', 'Ç�', 'ÇŽ', 'Ç�', 'Ç�', 'Ç‘', 'Ç’', 'Ç“', 'Ç”', 'Ç•', 'Ç–', 'Ç—', 'Ç˜', 'Ç™', 'Çš', 'Ç›', 'Çœ', 'Çº', 'Ç»', 'Ç¼', 'Ç½', 'Ç¾', 'Ç¿');
	   	$b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
	   	$_titulo = str_replace($a, $b, $_titulo);*/
	   	$_titulo = strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'), array('', '-', ''), $_titulo));

	   	//return $_id . '/' . $_titulo . '/' . $_lang;
		return $_id . '/' . $_titulo . ".html";
	}

	public static function crearUrlTres($_cat, $_subcat='')
	{
		// $_id = (int) $_id;
		// if($_subid!=''){$_subid = (int) $_subid;}
		
		$a = array('Ã€', 'Ã�', 'Ã‚', 'Ãƒ', 'Ã„', 'Ã…', 'Ã†', 'Ã‡', 'Ãˆ', 'Ã‰', 'ÃŠ', 'Ã‹', 'ÃŒ', 'Ã�', 'ÃŽ', 'Ã�', 'Ã�', 'Ã‘', 'Ã’', 'Ã“', 'Ã”', 'Ã•', 'Ã–', 'Ã˜', 'Ã™', 'Ãš', 'Ã›', 'Ãœ', 'Ã�', 'ÃŸ', 'Ã ', 'Ã¡', 'Ã¢', 'Ã£', 'Ã¤', 'Ã¥', 'Ã¦', 'Ã§', 'Ã¨', 'Ã©', 'Ãª', 'Ã«', 'Ã¬', 'Ã­', 'Ã®', 'Ã¯', 'Ã±', 'Ã²', 'Ã³', 'Ã´', 'Ãµ', 'Ã¶', 'Ã¸', 'Ã¹', 'Ãº', 'Ã»', 'Ã¼', 'Ã½', 'Ã¿', 'Ä€', 'Ä�', 'Ä‚', 'Äƒ', 'Ä„', 'Ä…', 'Ä†', 'Ä‡', 'Äˆ', 'Ä‰', 'ÄŠ', 'Ä‹', 'ÄŒ', 'Ä�', 'ÄŽ', 'Ä�', 'Ä�', 'Ä‘', 'Ä’', 'Ä“', 'Ä”', 'Ä•', 'Ä–', 'Ä—', 'Ä˜', 'Ä™', 'Äš', 'Ä›', 'Äœ', 'Ä�', 'Äž', 'ÄŸ', 'Ä ', 'Ä¡', 'Ä¢', 'Ä£', 'Ä¤', 'Ä¥', 'Ä¦', 'Ä§', 'Ä¨', 'Ä©', 'Äª', 'Ä«', 'Ä¬', 'Ä­', 'Ä®', 'Ä¯', 'Ä°', 'Ä±', 'Ä²', 'Ä³', 'Ä´', 'Äµ', 'Ä¶', 'Ä·', 'Ä¹', 'Äº', 'Ä»', 'Ä¼', 'Ä½', 'Ä¾', 'Ä¿', 'Å€', 'Å�', 'Å‚', 'Åƒ', 'Å„', 'Å…', 'Å†', 'Å‡', 'Åˆ', 'Å‰', 'ÅŒ', 'Å�', 'ÅŽ', 'Å�', 'Å�', 'Å‘', 'Å’', 'Å“', 'Å”', 'Å•', 'Å–', 'Å—', 'Å˜', 'Å™', 'Åš', 'Å›', 'Åœ', 'Å�', 'Åž', 'ÅŸ', 'Å ', 'Å¡', 'Å¢', 'Å£', 'Å¤', 'Å¥', 'Å¦', 'Å§', 'Å¨', 'Å©', 'Åª', 'Å«', 'Å¬', 'Å­', 'Å®', 'Å¯', 'Å°', 'Å±', 'Å²', 'Å³', 'Å´', 'Åµ', 'Å¶', 'Å·', 'Å¸', 'Å¹', 'Åº', 'Å»', 'Å¼', 'Å½', 'Å¾', 'Å¿', 'Æ’', 'Æ ', 'Æ¡', 'Æ¯', 'Æ°', 'Ç�', 'ÇŽ', 'Ç�', 'Ç�', 'Ç‘', 'Ç’', 'Ç“', 'Ç”', 'Ç•', 'Ç–', 'Ç—', 'Ç˜', 'Ç™', 'Çš', 'Ç›', 'Çœ', 'Çº', 'Ç»', 'Ç¼', 'Ç½', 'Ç¾', 'Ç¿');
	   	$b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
	   	$_cat = str_replace($a, $b, $_cat);
	   	$_cat = strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'), array('', '-', ''), $_cat));

	   	if($_subcat!=''){	   	
			$_subcat = str_replace($a, $b, $_subcat);
	   		$_subcat = strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'), array('', '-', ''), $_subcat));
	   	}
	   	// return $_id . '/' . $_titulo;
	   	return ($_subcat!='') ? $_cat. '/' . $_subcat : $_cat;
	}
	
	public static function cortarTexto($texto,$tamano,$colilla="...")
	{
		//strip_tags($texto);
		$largo = strlen($texto);		

		if($largo > $tamano){
			$texto=substr($texto, 0,$tamano);
			$index=strrpos($texto, " ");
			$texto=substr($texto, 0,$index); 
			$texto.=$colilla;
		}
		
		return $texto;
	} 
	
	public static function limitarTitulo($string, $length = 50, $ellipsis = "...")
	{
		$words = explode(' ', $string);
		if (count($words) > $length){
			return implode(' ', array_slice($words, 0, $length)) ." ". $ellipsis;
		}else{
			return $string;
		}
	}
	
	public static function convertirString($_array)
	{
		return implode(",", $_array);
	}
	
	public static function convertirArray($_string)
	{
		return explode(",", $_string);
	}
	
	public static function ultimoOrdenImagen($_identificador)
	{
		//$orden = contenidos_sliders_elemento::find(array('select' => 'max(orden) as orden'));
		$orden = contenidos_imagene::find(array('conditions' => array('identificador = ?', $_identificador),'select' => 'max(orden) as orden'));
		//$orden = contenidos_sliders_elemento::find_by_sql('select max(orden) as orden from contenidos_sliders_elementos where identificador = '.$_identificador);
		return $orden;
	}

	public static function formatearFecha($_fecha)
	{
		$_fecha = explode("-", $_fecha);
		$_mes = self::convertirMes($_fecha[1]);
		return $_fecha[2] . " " . strtolower($_mes) . ", " . $_fecha[0];
	}
	
	public static function convertirMes($_mes,$_lang=1)
	{
		switch($_mes){
			case '01':
				$_mes = ($_lang==1) ? 'Enero' : 'January';
				break;
			case '02':
				$_mes = ($_lang==1) ? 'Febrero' : 'February';
				break;
			case '03':
				$_mes = ($_lang==1) ? 'Marzo' : 'March';
				break;
			case '04':
				$_mes = ($_lang==1) ? 'Abril' : 'April';
				break;
			case '05':
				$_mes = ($_lang==1) ? 'Mayo' : 'May';
				break;
			case '06':
				$_mes = ($_lang==1) ? 'Junio' : 'June';
				break;
			case '07':
				$_mes = ($_lang==1) ? 'Julio' : 'July';
				break;
			case '08':
				$_mes = ($_lang==1) ? 'Agosto' : 'August';
				break;
			case '09':
				$_mes = ($_lang==1) ? 'Septiembre' : 'September';
				break;
			case '10':
				$_mes = ($_lang==1) ? 'Octubre' : 'October';
				break;
			case '11':
				$_mes = ($_lang==1) ? 'Noviembre' : 'November';
				break;
			case '12':
				$_mes = ($_lang==1) ? 'Diciembre' : 'December';
				break;
			
		}
		return $_mes;
	}


	public static function getUserIpAddr(){

	    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
	        //ip from share internet
	        $ip = $_SERVER['HTTP_CLIENT_IP'];
	    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
	        //ip pass from proxy
	        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    }else{
	        $ip = $_SERVER['REMOTE_ADDR'];
	    }
	    return $ip;
	    
	}


	public static function generateSecureToken($length, $lengthType) {

	    // Work out byte length
	    switch($lengthType) {

	        case 'bits':
	            $byteLength = ceil($length / 8);
	            break;

	        case 'bytes':
	            $byteLength = $length;
	            break;

	        case 'chars':
	            $byteLength = $length / 2; // In hex one char = 4 bits, i.e. 2 chars per byte
	            break;

	        default:
	            return false;
	            break;

	    }

	    // Try getting a cryptographically secure token
	    $token = openssl_random_pseudo_bytes($byteLength);

	    if ($token !== false) {

	        $token = bin2hex($token);

	    }
	    else {

	        // openssl_random_pseudo_bytes failed
	        // return false;
	        self::generateSecureToken($length, $lengthType);

	    }


	    $_cliente =contenidos_user::find('all',array('conditions' => array('token = ?', $token)));
	    if($_cliente){
	    	self::generateSecureToken($length, $lengthType);
	    }else{
	    	return $token;
	    }

	}

	/////////////////////SEO/////////////////////////////

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

	
	public static function getCurrentUrl()
	{
		$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$CurPageURL = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; 

		return $CurPageURL; 
	}
	
	
}