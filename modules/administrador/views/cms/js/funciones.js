$(document).ready(function() {
	
	 $('.color').colorPicker();  
	
	
	$('#cargar_trabajo').submit(function(){
		
			for (instance in CKEDITOR.instances) {
				CKEDITOR.instances[instance].updateElement();
			}
			
			
			/*var titulo= tinyMCE.get('titulo').getContent();
			$("#titulo").val(titulo);*/
			/*if ($("#titulo").val() == "") {
				alert("Debe completar el campo titulo");
				//$("#titulo").val('');
				$('#titulo').focus();			
				//$("#titulo").attr('placeholder', 'Titulo');
				return false;        
			}*/
			
			/*var desc= tinyMCE.get('desc').getContent();
			$("#desc").val(desc);
			if ($("#desc").val() == "") {
				alert("Debe completar el campo descripcion");
				//$("#titulo_form").val('');
				$('#desc').focus();			
				//$("#titulo_form").attr('placeholder', 'Titulo de Formulario');
				return false;        
			}*/
			
			if ($("#link").val() == "") {
				alert("Debe completar el campo link");
				//$("#titulo").val('');
				$('#link').focus();			
				//$("#titulo").attr('placeholder', 'Titulo');
				return false;        
			}
						
			
			/*if ($("#imagen").val() == "") {
				alert("Debe subir una imagen");
				$('#imagen').focus();			
				return false;        
			}*/
			
			
			return true;
		
	
	});
	
	
	$('#editar_trabajo').submit(function(){
		
			
			/*var titulo= tinyMCE.get('titulo').getContent();
			$("#titulo").val(titulo);*/
			if ($("#titulo").val() == "") {
				alert("Debe completar el campo titulo");
				//$("#titulo").val('');
				$('#titulo').focus();			
				//$("#titulo").attr('placeholder', 'Titulo');
				return false;        
			}
			
			var desc= tinyMCE.get('desc').getContent();
			$("#desc").val(desc);
			if ($("#desc").val() == "") {
				alert("Debe completar el campo descripcion");
				//$("#titulo_form").val('');
				$('#desc').focus();			
				//$("#titulo_form").attr('placeholder', 'Titulo de Formulario');
				return false;        
			}
			
			if ($("#link").val() == "") {
				alert("Debe completar el campo link");
				//$("#titulo").val('');
				$('#link').focus();			
				//$("#titulo").attr('placeholder', 'Titulo');
				return false;        
			}
						
			
			/*if ($("#imagen").val() == "") {
				alert("Debe subir una imagen");
				$('#imagen').focus();			
				return false;        
			}*/
			
			
			
			return true;
		
	
	});
	
	
});