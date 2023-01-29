$("#form_epidata").find('input:not([type="hidden"]), textarea').on('keydown', function(){
	$(this).removeClass('error');
	$("#form_epidata").find('.valid-text').html('');
});
var sending = false;

$("#form_epidata").on('submit', function(e){
	e.preventDefault();

	if(sending) return false;
	sending=true;

	var form = $(this);

	var validForm = true;
	form.find('input:not([type="hidden"]), textarea, select').each(function(){
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

		console.log(action);console.log(data);


		$.ajax({
			type: 'POST',
			url: action,
			data: data,
			error: function(data){
				console.log("form:error");
				form.html('Error al enviar tu mensaje, por favor intentá nuevamente');
				sending=false;
			},
			success: function(data){
				console.log("form:success");
				form.animate({'opacity': 0}, 500,
					function(){
						form.find('input:not([type="hidden"]), textarea, select').each(function(){
							$(this).val('');
						})
						$('#msg').fadeIn(300).delay(3000).fadeOut(300, function(){
							form.animate({'opacity': 1}, 300)
						})
					}
				)
				
				sending=false;
			}
		});


	}
	else{
		form.find('#form_epidata').html('Nooooop');

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