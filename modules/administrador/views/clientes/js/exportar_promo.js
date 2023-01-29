$(document).ready(function() {
	
	//var base_url='http://'+document.domain+'/';
	var base_url='http://'+document.domain+'/trabajos/agencia_capitan/skip_promos/';
	
 
   
   
	$.fn.exportarPromos = function(_val){	
		
		$.ajax({
			type: "POST",
			url: base_url+"administrador/promociones/exportarPromos",
			data: {"id":_val},
			beforeSend: function(){
			},
			success: function(datos){
				$("#exportar").html(datos);
				var $filename = 'registros_promos_'+_val;
				$("#exportTabla").btechco_excelexport({
				    containerid: "exportTabla"
				   , datatype: $datatype.Table
				   , filename: $filename
				});
			}
		});
	
	}
   
	
	
	
});