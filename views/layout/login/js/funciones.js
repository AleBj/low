  // $fn.scrollSpeed(step, speed, easing);
////preloadHTMLImages();
// function preloadHTMLImages(){      
//    //var imgNodes = document.getElementsByTagName('img');
//    var imgNodes = ['img/img-slider-01.jpg', 'img/about_mask-lines.png', 'img/bg-por-01.jpg', 'img/bg-por-02.jpg', 'img/bg-por-03.jpg', 'img/bg-por-03-d.jpg', 'img/logo-datego.png'];
//    var imgs = []; 
//    var counter = 0; 
//    var limit = imgNodes.length;
       
//    var incrFn = function(){ 
//       counter++;
      
//       if(counter >= limit){
// 		 $('#loader div').animate({'width':0, 'right':'-40%'}, 500, function(){
// 		 	$('#loader').fadeOut(300, function(){
// 				$('#home .mask .bg-slide').addClass('this');
// 				/*$('#home h2').addClass('view');*/	 		
// 		 	})
// 		 })
//       }
//    };
   
//    for(var i = 0; i < limit; i++){
//       imgs[i] = new Image();
//       //imgs[i].src = imgNodes[i].getAttribute('src');      
//       imgs[i].src = imgNodes[i];
//       imgs[i].onload = incrFn;
//       imgs[i].onerror = incrFn;
	  
//    }
// }


//form
$('input:not([type="hidden"]), textarea').on('keydown', function(){$(this).removeClass('error');});
//sending
$('#form-contact .verMas').on('click', function(e){
   e.preventDefault();
    var form = $('#form-contact');
    var action= form.attr('action');
    var fields = form.serializeObject();    
    var email = $("input[type='email']").val();
    var validacion_email = /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;
    var validForm = true;
    
   form.find('input:not([type="hidden"]), textarea, select').each(function(){
    if( $(this).val().trim() ==''){
      $(this).addClass('error');
      validForm = false;
      return false;
    }else if(email == "" || !validacion_email.test(email)){
      $("input[type='email']").focus();
      $("input[type='email']").addClass('error');
      validForm = false;
      return false;
    }
   });
   console.log(fields);
   if(validForm){
    $.ajax({
      type: 'POST',
      url: action,
      data: fields,
      error: function(data){
        console.log("form-register:submit:error");
        console.log(data);
        $('#msg').fadeIn(300).html("hubo un error , intentalo de nuevo").delay(2000).fadeOut(200);
      },
      success: function(data){
       console.log("form:success");  
       $('#form-contact').fadeOut(300)
       $('#msg').fadeIn(300).html("Gracias, nos contactaremos")
       $('input').val('');
      }
    });
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