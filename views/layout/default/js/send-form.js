$("#form-coovaeco").find('input:not([type="hidden"]), textarea').on('keydown', function(){
	$(this).removeClass('error');
});
var sending = false;

$("#form-coovaeco").on('submit', function(e){
	e.preventDefault();

	if(sending) return false;
	sending=true;

	var form = $(this);
	$('#form-coovaeco button').html('Enviando');
	var validForm = true;
	form.find('input:not([type="hidden"]), textarea').each(function(){
		if( $(this).val().trim() ==''){
			$(this).focus();
			$(this).addClass('error');
			validForm = false;
			sending=false;
			return false;
		}
	})

	if(validForm){

		var action = form.attr('action');
		var data = form.serializeObject();

		console.log(action);
		console.log(data);


		$.ajax({
			type: 'POST',
			url: action,
			data: data,
			error: function(data){
				console.log("form:error");
				sending=false;
				$('#form-coovaeco button').html('ERROR');
			},
			success: function(data){
				console.log("form:success");
				window.location = 'gracias.html'
				sending=false;
			}
		});


	}
	else{
		console.log('Error al enviar Formulario...')

	}


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