$(document).ready(function(){
	$("._cont_desplegar").mouseover(function(){
			$(this).parent().children("._cont_acciones").slideDown("fast");
	})
	$("._cont_acciones").mouseleave(function(){
			$("._cont_acciones").slideUp("fast");
	})	
})


