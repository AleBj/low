<?php

use controllers\administradorController\administradorController;

class indexController extends administradorController
{
	public $_trabajosGestion;
	//public $homeGestion;
	//public $_cache;
	
    public function __construct() 
    {
        parent::__construct();
		
	
		$this->getLibrary('class.admin');
		$this->_trabajosGestion = new admin();
		
		// $this->getLibrary('class.cache');
		// $this->_cache = new cache();
    }
    
    public function index()
    {
		$this->_acl->acceso('login_access');     	
		
		$this->redireccionar('administrador/productos');
		
    }

    /*public function data()
    {
		$this->_acl->acceso('encargado_access');
		
		//$this->_view->setJs(array('jquery.btechco.excelexport','jquery.base64','exportar_data'));
		$this->_view->setCssPlugin(array('jquery-ui-timepicker-addon'));		
		$this->_view->setJs(array('jquery-ui-timepicker-addon'));
		
		$this->_view->fecha = '2021-03-26';
		$this->_view->fecha = date('Y-m-d');
		// $_fecha = '2021-03-26';
		$_mes = explode('-', $this->_view->fecha);

		// Ventas por mes
		$this->_view->datos = $this->_trabajosGestion->traerVentasPorMes($_mes[1],'aprobado');

		// cursos mas vendidos
		$this->_view->datos1 = $this->_trabajosGestion->traerCursosMasVendidos($this->_view->fecha,'aprobado');

		// ventas por sucursales
		$this->_view->datos2 = $this->_trabajosGestion->traerVentasPorSucursales($this->_view->fecha,'aprobado');

		// ventas por plataforma de pago
		$this->_view->datos3 = $this->_trabajosGestion->traerVentasPorPlataformas($this->_view->fecha,'aprobado');

		// echo "<pre>";print_r($this->_view->datos);exit;
			
		$this->_view->titulo = 'Administrador - Seguimiento';
        $this->_view->renderizar('index', 'home');	
    }

    public function buscadorData()
	{
		$this->_acl->acceso('encargado_access');
		
		if($_POST){

			if(validador::getPostParam('_csrf') == $this->_sess->get('_csrf')){	


				// echo "<pre>";print_r($_POST);echo"</pre>";exit;
			
				
				$_fecha = date("Y-m-d", strtotime($_POST['valor']));
				// $_fecha = '2021-03-26';
				$_mes = explode('-', $_fecha);

				// Ventas por mes
				$_datos = $this->_trabajosGestion->traerVentasPorMes($_mes[1],'aprobado');

				// cursos mas vendidos
				$_datos1 = $this->_trabajosGestion->traerCursosMasVendidos($_fecha,'aprobado');

				// ventas por sucursales
				$_datos2 = $this->_trabajosGestion->traerVentasPorSucursales($_fecha,'aprobado');

				// ventas por plataforma de pago
				$_datos3 = $this->_trabajosGestion->traerVentasPorPlataformas($_fecha,'aprobado');
				
				// echo "<pre>";print_r($_datos3);echo"</pre>";exit;

				$_html = '';


				if($_datos){

													        							
					$_html .= '<div class="ibox-content">  
						       <h2>Cantidad ventas en el mes: '.count($_datos).'</h2>
						    </div> ';
				
				}else{
					$_html .= '';
				}
				
				if($_datos1){

													        							
					$_html .= '<div class="ibox-content">  
							       <div id="chart1"></div>
							    </div>';

			        $_html .= "<script>					        			
						        $(document).ready(function () {
						                Highcharts.chart('chart1', {
										    chart: {
										        plotBackgroundColor: null,
										        plotBorderWidth: null,
										        plotShadow: false,
										        type: 'pie'
										    },
										    title: {
										        text: 'Cursos mas vendidos'
										    },
										    tooltip: {
										        pointFormat: '{series.name}<b>{point.percentage:.1f}%</b>'
										    },
										    accessibility: {
										        point: {
										            valueSuffix: '%'
										        }
										    },
										    plotOptions: {
										        pie: {
										            allowPointSelect: true,
										            cursor: 'pointer',
										            dataLabels: {
										                enabled: true,
										                format: '{point.name}<br> {point.percentage:.1f} %'
										            }
										        }
										    },
										    series: [{
										        name: '',
										        colorByPoint: true,
										        data: [";
										        foreach ($_datos1 as $val){
										        $_html .= "{
											            name: '".$val['nombre']."<br>Sucursal: ".$val['sucursal']."',
											            y: ".$val['cantidad'].",
											            sliced: true,
											            selected: true
											        },";
											    }
											    $_html .= "]
										    }]
										});
						            });
						        </script>";
		        
					
				
				}else{
					$_html .= '';
				}


				if($_datos2){

													        							
					$_html .= '<div class="ibox-content">  
							       <div id="chart2"></div>
							    </div>';

			        $_html .= "<script>					        			
						        $(document).ready(function () {
						                Highcharts.chart('chart2', {
										    chart: {
										        plotBackgroundColor: null,
										        plotBorderWidth: null,
										        plotShadow: false,
										        type: 'pie'
										    },
										    title: {
										        text: 'Ventas por sucursales'
										    },
										    tooltip: {
										        pointFormat: '{series.name}<b>{point.y} ventas<br>{point.percentage:.1f}%</b>'
										    },
										    accessibility: {
										        point: {
										            valueSuffix: '%'
										        }
										    },
										    plotOptions: {
										        pie: {
										            allowPointSelect: true,
										            cursor: 'pointer',
										            dataLabels: {
										                enabled: true,
										                format: '{point.name}:<br> {point.y} ventas<br>{point.percentage:.1f} %'
										            }
										        }
										    },
										    series: [{
										        name: '',
										        colorByPoint: true,
										        data: [";
										        foreach ($_datos2 as $val){
										        $_html .= "{
											            name: '".$val['nombre']."',
											            y: ".$val['cantidad'].",
											            sliced: true,
											            selected: true
											        },";
											    }
											    $_html .= "]
										    }]
										});
						            });
						        </script>";
		        
					
				
				}else{
					$_html .= '';
				}


				if($_datos3){

													        							
					$_html .= '<div class="ibox-content">  
							       <div id="chart3"></div>
							    </div>';

			        $_html .= "<script>					        			
						        $(document).ready(function () {
						                Highcharts.chart('chart3', {
										    chart: {
										        plotBackgroundColor: null,
										        plotBorderWidth: null,
										        plotShadow: false,
										        type: 'pie'
										    },
										    title: {
										        text: 'Plataformas de pago mas usadas'
										    },
										    tooltip: {
										        pointFormat: '{series.name}<b>{point.percentage:.1f}%</b>'
										    },
										    accessibility: {
										        point: {
										            valueSuffix: '%'
										        }
										    },
										    plotOptions: {
										        pie: {
										            allowPointSelect: true,
										            cursor: 'pointer',
										            dataLabels: {
										                enabled: true,
										                format: '{point.name}<br>{point.percentage:.1f} %'
										            }
										        }
										    },
										    series: [{
										        name: '',
										        colorByPoint: true,
										        data: [";
										        foreach ($_datos3 as $val){
										        $_html .= "{
											            name: '".$val['nombre']."',
											            y: ".$val['cantidad'].",
											            sliced: true,
											            selected: true
											        },";
											    }
											    $_html .= "]
										    }]
										});
						            });
						        </script>";
		        
					
				
				}else{
					$_html .= '';
				}

				
				
				
				echo $_html;

			}else{
				$this->redireccionar('error/access/404');
			}
			
		}
	}
	*/
	
}
