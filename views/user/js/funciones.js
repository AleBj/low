$(document).ready(function(e) {

	var base_url='http://'+document.domain;
	
	$("#form form").submit(function(e){
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
			
		var serv_tecnico = form.find("#service");
		if (serv_tecnico.val() == "") {
			alert("Debe completar el campo service");
			serv_tecnico.val('');
			serv_tecnico.focus();			
			return false;        
		}
		
		 var provincia = form.find("#provincia");
		 if (provincia.val() == "") {
			alert("Debe completar el campo Provincia");
			provincia.val('');
			provincia.focus();
			return false;	
		}
		
		var localidad = form.find("#localidad");
		if (localidad.val() == "") {
			alert("Debe completar el campo localidad");
			localidad.val('');
			localidad.focus();
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
		
		var consulta = form.find("#consulta");
		if (consulta.val() == "") {
			alert("Debe completar el campo consultas");
			consulta.val('');
			consulta.focus();
			return false;
		}
		
	
		if(validForm){
			
			var action = form.attr('action');
			var data = form.serializeObject();
			//var overlay = form.prev();
			$.ajax({
				type: 'POST',
				url: base_url+"index/procesarContacto",
				data: data,
				error: function(data){
					$('#form form button').addClass('error');
					sending=false;
				},
				success: function(data){
					//console.log(data);
					if(data=='ok'){
						//alert('Formulario enviado con exito');
						$('#form form button').addClass('ok');
						form.resetear();
					}else{
						$('#form form button').addClass('error');
					}
					
					sending=false;
				}
			});
		}
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
}
