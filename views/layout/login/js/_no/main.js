$(document).ready(function() {
	
	var base_url='http://'+document.domain+'/';
	
	
	
	$.fn.definirLang = function(_lang){	
		var url = window.location.href;
		/*url = url.split('/');
		url = url.filter(Boolean);
		var num = url.length-1;
		var url_final = url[num];
		alert (url_final);*/
		var datastring = "lang="+_lang+"&url="+url;
		
		//alert(url)
		
		$.ajax({
				type: 'POST',
				url: base_url+"index/definirIdioma",
				data: datastring,
				beforeSend: function(){
					/*$('#contenedor').fadeOut('fast', function(){
						$('.loader').fadeIn('slow');				
					});*/
				},				
				success: function(data){
					//alert(data);
					window.location = data;
					//console.log(data);
					/*if(data!=''){
						$('#contenedor').html(data);
					}else{
						$('#contenedor').html('<p>No hay resultados disponibles</p>');
					}*/
					/*$('#contenedor').html(data);
					$('.loader').fadeOut('fast', function(){
						$('#contenedor').fadeIn('slow');				
					});*/					
				}
			});
		
		
		//alert( this.value ); // or $(this).val()
	};
	
    
});
