$(document).ready(function() {    
	
	var base_url='http://'+document.domain+'/hairdressing/';
	
	
		
	
	$("#formLogin").submit(function(e){
		e.preventDefault();
		var sending;
		if(sending) return false;
		sending=true;
	
		var form = $(this);
		
		var validForm = true;	
		
		
		var user = $("#user");
		if (user.val() == "") {
			alert("Debe completar el campo usuario");
			user.val('');
			user.focus();
			return false;
		}
		
		var pass = $("#pass");
		if (pass.val() == "") {
			alert("Debe completar el campo Contraseña");
			pass.val('');
			pass.focus();
			return false;
		}
		
		
		//return true;
		
		if(validForm){
			
			//var action = form.attr('action');
			var data = form.serialize();
			//var overlay = form.prev();
			$.ajax({
				type: 'POST',
				url: base_url+"index/login",
				data: data,
				//dataType: "json",
				error: function(data){
					
				},
				success: function(data){
					//console.log(data);
					
					if(data=='login_ok'){
						//alert('Formulario enviado con exito');
						/*$('.login-nuevos button').addClass('ok');
						form.resetear();*/
						window.location.href = base_url+"index/panel";
					}else{
						alert('Usuario Inexistente');
					}
									
					sending=false;
				}
			});
		}
	});
	
	
	$("#formDataPeluqueria").submit(function(e){
		e.preventDefault();
		var sending;
		if(sending) return false;
		sending=true;
	
		var form = $(this);
		
		var validForm = true;	
		
		var nombre = form.find("#nombre_pelu");
		if (nombre.val() == "") {
			alert("Debe completar el campo nombre");
			nombre.val('');
			nombre.focus();			
			return false;        
		}
		
		
		
		var fecha_apertura = form.find("#fecha_apertura");
		if (fecha_apertura.val() == "") {
			alert("Debe completar el campo fecha apertura");
			fecha_apertura.val('');
			fecha_apertura.focus();			
			return false;        
		}
		
		var razon_social = form.find("#razon_social");
		if (razon_social.val() == "") {
			alert("Debe completar el campo razon social");
			razon_social.val('');
			razon_social.focus();			
			return false;        
		}
		
		 var direccion = form.find("#direccion_pelu");
		 if (direccion.val() == "") {
			alert("Debe completar el campo direccion");
			direccion.val('');
			direccion.focus();
			return false;
		}
		
		
		 var cod_postal = form.find("#cod_postal_pelu");
		 if (cod_postal.val() == "") {
			alert("Debe completar el campo codigo postal");
			cod_postal.val('');
			cod_postal.focus();
			return false;
		}
		
		
		var telefono = form.find("#telefono_pelu");
		if (telefono.val() == "") {
			alert("Debe completar el campo telefono");
			telefono.val('');
			telefono.focus();
			return false;
		}
		
		if(isNaN(telefono.val())){
			alert("El campo telefono debe ser un numero");
			telefono.val('');
			telefono.focus();
			return false;
		}
		
		var email = form.find("#email_pelu");
		if (email.val() == "") {
			alert("Debe completar el campo email");
			email.val('');
			email.focus();
			return false;
		}
		
		if(email.val().indexOf('@', 0) == -1 || email.val().indexOf('.', 0) == -1) {
			alert("Email incorrecto");
			email.val('');
			email.focus();
			return false;
		}
		
		var vendedor = $("#vendedor_pelu");
		if (vendedor.val() == "") {
			alert("Debe completar el campo vendedor");
			vendedor.val('');
			vendedor.focus();
			return false;
		}
		
	
		if(validForm){
			//var action = form.attr('action');
			var data = form.serialize();
			$.ajax({
				type: 'POST',
				url: base_url+"index/nuevaPeluqueria",
				data: data,
				error: function(data){
					//$('.login-nuevos button').addClass('error');
					sending=false;
				},
				success: function(data){
					//console.log(data);
					if(data=='ok'){
						//alert('carga exitosa!');
						$('#popUp p').html('El salón ha sido cargado con éxito')
						$('#popUp').fadeIn(300)
						//$('#formDataPeluqueria input').val('');
						$('#formDataPeluqueria')[0].reset(); 
						//window.location.reload();
					}
					
					
					//alert();
					/*form.find('button').html('FORMULARIO ENVIADO CON ÉXITO').addClass('ok');
					setTimeout(function() {
						form.find('button').html('DEJAR MIS DATOS Y PARTICIPAR').removeClass('ok');			
						if($('#form_promos').is(":visible")){
							$('#form_promos').slideToggle();
							$('#form_promos input').val('')
						}
					}, 2500);*/
					sending=false;
				}
			});
		}
	});
	
	
	
	$("#formDataPersona").submit(function(e){
		e.preventDefault();
		var sending;
		if(sending) return false;
		sending=true;
	
		var form = $(this);
		
		var validForm = true;	
		
		var nombre = form.find("#nombre");
		if (nombre.val() == "") {
			alert("Debe completar el campo nombre");
			nombre.val('');
			nombre.focus();			
			return false;        
		}
		
		var apellido = form.find("#apellido");
		if (apellido.val() == "") {
			alert("Debe completar el campo apellido");
			apellido.val('');
			apellido.focus();			
			return false;        
		}
		
		var sexo = form.find("#sexo");
		if (sexo.val() == "") {
			alert("Debe completar el campo sexo");
			/*sexo.val('');
			sexo.focus();*/			
			return false;        
		}
		
		
		
		 var fecha_nac = form.find("#fecha_nac");
		 if (fecha_nac.val() == "") {
			alert("Debe completar el campo fecha nacimiento");
			fecha_nac.val('');
			fecha_nac.focus();
			return false;
		}
		
		
		var dni = form.find("#dni");
		if (dni.val() == "") {
			alert("Debe completar el campo DNI");
			dni.val('');
			dni.focus();			
			return false;        
		}
		
		if(isNaN(dni.val())){
			alert("El campo DNI debe ser un numero");
			dni.val('');
			dni.focus();
			return false;
		}
		
		
		 var peluqueria = form.find("#peluqueria");
		 if (peluqueria.val() == "") {
			alert("Debe completar el campo peluqueria");
			peluqueria.val('');
			peluqueria.focus();
			return false;
		}
		
		
		 var direccion = form.find("#direccion");
		 if (direccion.val() == "") {
			alert("Debe completar el campo direccion");
			direccion.val('');
			direccion.focus();
			return false;
		}
		
		
		var telefono = form.find("#telefono");
		if (telefono.val() == "") {
			alert("Debe completar el campo telefono");
			telefono.val('');
			telefono.focus();
			return false;
		}
		
		if(isNaN(telefono.val())){
			alert("El campo telefono debe ser un numero");
			telefono.val('');
			telefono.focus();
			return false;
		}
		
			
		var email = form.find("#email");
		if (email.val() == "") {
			alert("Debe completar el campo email");
			email.val('');
			email.focus();
			return false;
		}
		
		if(email.val().indexOf('@', 0) == -1 || email.val().indexOf('.', 0) == -1) {
			alert("Email incorrecto");
			email.val('');
			email.focus();
			return false;
		}
		
		
		var hijos = form.find("#hijos");
		if (hijos.val() == "") {
			alert("Debe completar el campo hijos");
			/*hijos.val('');
			hijos.focus();*/			
			return false;        
		}
		
		
		var vendedor = $("#vendedor");
		if (vendedor.val() == "") {
			alert("Debe completar el campo vendedor");
			vendedor.val('');
			vendedor.focus();
			return false;
		}
		
	
		if(validForm){
			//var action = form.attr('action');
			var data = form.serialize();
			$.ajax({
				type: 'POST',
				url: base_url+"index/nuevaPersona",
				data: data,
				error: function(data){
					//$('.login-nuevos button').addClass('error');
					sending=false;
				},
				success: function(data){
					//console.log(data);
					if(data=='ok'){
						//alert('carga exitosa!');
						$('#popUp p').html('Tu cliente ha sido registrado con éxito')
						$('#popUp').fadeIn(300)
						/*$('#formDataPersona input').val('');
						$('#formDataPersona select').val('');*/
						$('#formDataPersona')[0].reset(); 
						//window.location.reload();	
					}
					
					
					//alert();
					/*form.find('button').html('FORMULARIO ENVIADO CON ÉXITO').addClass('ok');
					setTimeout(function() {
						form.find('button').html('DEJAR MIS DATOS Y PARTICIPAR').removeClass('ok');			
						if($('#form_promos').is(":visible")){
							$('#form_promos').slideToggle();
							$('#form_promos input').val('')
						}
					}, 2500);*/
					sending=false;
				}
			});
		}
	});
	
	
	
	
});
/*$(window).load(function() {
  $('#slider').flexslider({
    animation: "fade"
  });
});
// serializes a form into an object.
(function($,undefined){
  '$:nomunge'; // Used by YUI compressor.
  $.fn.serializeObject = function(){
    var obj = {};
    $.each( this.serializeArray(), function(i,o){
      var n = o.name,
        v = o.value;
        obj[n] = obj[n] === undefined ? v
          : $.isArray( obj[n] ) ? obj[n].concat( v )
          : [ obj[n], v ];
    });
    return obj;
  };
})(jQuery);
jQuery.fn.resetear = function () {
  $(this).each (function() { this.reset(); });
}*/