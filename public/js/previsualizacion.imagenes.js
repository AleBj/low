// JavaScript Document
$(document).ready(function(){
  
	var wrapper    = $("<div/>").css({height:0,width:0, 'overflow': 'hidden'});
    var fileInput0 = $("#file-0").wrap(wrapper),
        fileInput1 = $("#file-1").wrap(wrapper),
        fileInput2 = $("#file-2").wrap(wrapper),
		fileInput3 = $("#file-3").wrap(wrapper),
		fileInput4 = $("#file-4").wrap(wrapper),
		fileInput5 = $("#file-5").wrap(wrapper),
		fileInput6 = $("#file-6").wrap(wrapper),
		fileInput7 = $("#file-7").wrap(wrapper),
		fileInput8 = $("#file-8").wrap(wrapper),
		fileInput9 = $("#file-9").wrap(wrapper);

    $("#photo-0").on("click",function(){    
       fileInput0.click(); 
    }).show();

    $("#photo-1").on("click",function(){    
       fileInput1.click(); 
    }).show();

    $("#photo-2").on("click",function(){    
       fileInput2.click(); 
    }).show();
	
	$("#photo-3").on("click",function(){    
       fileInput3.click(); 
    }).show();
	
	$("#photo-4").on("click",function(){    
       fileInput4.click(); 
    }).show();
	
	$("#photo-5").on("click",function(){    
       fileInput5.click(); 
    }).show();
	
	$("#photo-6").on("click",function(){    
       fileInput6.click(); 
    }).show();
	
	$("#photo-7").on("click",function(){    
       fileInput7.click(); 
    }).show();
	
	$("#photo-8").on("click",function(){    
       fileInput8.click(); 
    }).show();
	
	$("#photo-9").on("click",function(){    
       fileInput9.click(); 
    }).show();

    fileInput0.on("change", function(){
        // object file, nombre del archivo, tamaño, type 
        var file     = this.files[0],
            fileName = file.name,
            fileSize = file.size,
            fileType = file.type;
       
	if(fileType.match('image.*')){
		//FileReader API HTML5,
        var reader = new FileReader();
   			reader.onload = function(e){
				// console.log("<img src='"+ e.target.result +"'/>");
				//$("#resultados").append("<img src='"+ e.target.result +"'/>");
				$("#photo-0").html("");
				$("#cerrar-0").html("");
				  
				$("#photo-0").append("<img src='"+ e.target.result +"' id='thumb-0' class='thumb' title='" + fileName + "' />");
				$("#cerrar-0").show(function(){
					$("#cerrar-0").append("<img src='http://server/eltaladro/public/img/close.png' width='20' height='20' alt='close'/>");
				});   
 			} 
        reader.readAsDataURL(file); 
      }else{
         alert("Solo se permiten JPG, GIF, PNG");
      }
    }); // Fin preload Photo1

	fileInput1.on("change", function(){
        // object file, nombre del archivo, tamaño, type 
		var file     = this.files[0],
            fileName = file.name,
            fileSize = file.size,
            fileType = file.type;
       
		if(fileType.match('image.*')){
 			//Validamos el tipo de archivo o file que deseamos subir.
          
			//FileReader API HTML5,
        	var reader = new FileReader();
            reader.onload = function(e){
            	// console.log("<img src='"+ e.target.result +"'/>");
            	//$("#resultados").append("<img src='"+ e.target.result +"'/>");
              	$("#photo-1").html("");
              	$("#cerrar-1").html("");
              	$("#photo-1").append("<img src='"+ e.target.result +"' id='thumb-1' class='thumb' title='" + fileName + "' />");
              	$("#cerrar-1").show(function(){
                	$("#cerrar-1").append("<img src='http://server/eltaladro/public/img/close.png' width='20' height='20' alt='close'/>");
              	});   
            } 
        	reader.readAsDataURL(file); 
		}else{
  			alert("Solo se permiten JPG, GIF, PNG");
      	}
	}); // Fin preload Photo2
 
	fileInput2.on("change", function(){
		// object file, nombre del archivo, tamaño, type 
        var file     = this.files[0],
            fileName = file.name,
            fileSize = file.size,
            fileType = file.type;
       
		if(fileType.match('image.*')){
 			//Validamos el tipo de archivo o file que deseamos subir.
          
   			//FileReader API HTML5,
        	var reader = new FileReader();
            reader.onload = function(e){
            	// console.log("<img src='"+ e.target.result +"'/>");
            	//$("#resultados").append("<img src='"+ e.target.result +"'/>");
              	$("#photo-2").html("");
              	$("#cerrar-2").html("");
              	$("#photo-2").append("<img src='"+ e.target.result +"' id='thumb-2' class='thumb' title='" + fileName + "' />");
              	$("#cerrar-2").show(function(){
           			$("#cerrar-2").append("<img src='http://server/eltaladro/public/img/close.png' width='20' height='20' alt='close'/>");
       			});   
    		} 
        	reader.readAsDataURL(file); 
		}else{
 			alert("Solo se permiten JPG, GIF, PNG");
		}
    }); // Fin preload Photo3

	fileInput3.on("change", function(){
		// object file, nombre del archivo, tamaño, type 
        var file     = this.files[0],
            fileName = file.name,
            fileSize = file.size,
            fileType = file.type;
       
		if(fileType.match('image.*')){
 			//Validamos el tipo de archivo o file que deseamos subir.
          
   			//FileReader API HTML5,
        	var reader = new FileReader();
            reader.onload = function(e){
            	// console.log("<img src='"+ e.target.result +"'/>");
            	//$("#resultados").append("<img src='"+ e.target.result +"'/>");
              	$("#photo-3").html("");
              	$("#cerrar-3").html("");
              	$("#photo-3").append("<img src='"+ e.target.result +"' id='thumb-3' class='thumb' title='" + fileName + "' />");
              	$("#cerrar-3").show(function(){
           			$("#cerrar-3").append("<img src='http://server/eltaladro/public/img/close.png' width='20' height='20' alt='close'/>");
       			});   
    		} 
        	reader.readAsDataURL(file); 
		}else{
 			alert("Solo se permiten JPG, GIF, PNG");
		}
    }); // Fin preload Photo4

	fileInput4.on("change", function(){
		// object file, nombre del archivo, tamaño, type 
        var file     = this.files[0],
            fileName = file.name,
            fileSize = file.size,
            fileType = file.type;
       
		if(fileType.match('image.*')){
 			//Validamos el tipo de archivo o file que deseamos subir.
          
   			//FileReader API HTML5,
        	var reader = new FileReader();
            reader.onload = function(e){
            	// console.log("<img src='"+ e.target.result +"'/>");
            	//$("#resultados").append("<img src='"+ e.target.result +"'/>");
              	$("#photo-4").html("");
              	$("#cerrar-4").html("");
              	$("#photo-4").append("<img src='"+ e.target.result +"' id='thumb-4' class='thumb' title='" + fileName + "' />");
              	$("#cerrar-4").show(function(){
           			$("#cerrar-4").append("<img src='http://server/eltaladro/public/img/close.png' width='20' height='20' alt='close'/>");
       			});   
    		} 
        	reader.readAsDataURL(file); 
		}else{
 			alert("Solo se permiten JPG, GIF, PNG");
		}
    }); // Fin preload Photo5

	fileInput5.on("change", function(){
		// object file, nombre del archivo, tamaño, type 
        var file     = this.files[0],
            fileName = file.name,
            fileSize = file.size,
            fileType = file.type;
       
		if(fileType.match('image.*')){
 			//Validamos el tipo de archivo o file que deseamos subir.
          
   			//FileReader API HTML5,
        	var reader = new FileReader();
            reader.onload = function(e){
            	// console.log("<img src='"+ e.target.result +"'/>");
            	//$("#resultados").append("<img src='"+ e.target.result +"'/>");
              	$("#photo-5").html("");
              	$("#cerrar-5").html("");
              	$("#photo-5").append("<img src='"+ e.target.result +"' id='thumb-5' class='thumb' title='" + fileName + "' />");
              	$("#cerrar-5").show(function(){
           			$("#cerrar-5").append("<img src='http://server/eltaladro/public/img/close.png' width='20' height='20' alt='close'/>");
       			});   
    		} 
        	reader.readAsDataURL(file); 
		}else{
 			alert("Solo se permiten JPG, GIF, PNG");
		}
    }); // Fin preload Photo5
	
	fileInput6.on("change", function(){
		// object file, nombre del archivo, tamaño, type 
        var file     = this.files[0],
            fileName = file.name,
            fileSize = file.size,
            fileType = file.type;
       
		if(fileType.match('image.*')){
 			//Validamos el tipo de archivo o file que deseamos subir.
          
   			//FileReader API HTML5,
        	var reader = new FileReader();
            reader.onload = function(e){
            	// console.log("<img src='"+ e.target.result +"'/>");
            	//$("#resultados").append("<img src='"+ e.target.result +"'/>");
              	$("#photo-6").html("");
              	$("#cerrar-6").html("");
              	$("#photo-6").append("<img src='"+ e.target.result +"' id='thumb-6' class='thumb' title='" + fileName + "' />");
              	$("#cerrar-6").show(function(){
           			$("#cerrar-6").append("<img src='http://server/eltaladro/public/img/close.png' width='20' height='20' alt='close'/>");
       			});   
    		} 
        	reader.readAsDataURL(file); 
		}else{
 			alert("Solo se permiten JPG, GIF, PNG");
		}
    }); // Fin preload Photo5
	
	fileInput7.on("change", function(){
		// object file, nombre del archivo, tamaño, type 
        var file     = this.files[0],
            fileName = file.name,
            fileSize = file.size,
            fileType = file.type;
       
		if(fileType.match('image.*')){
 			//Validamos el tipo de archivo o file que deseamos subir.
          
   			//FileReader API HTML5,
        	var reader = new FileReader();
            reader.onload = function(e){
            	// console.log("<img src='"+ e.target.result +"'/>");
            	//$("#resultados").append("<img src='"+ e.target.result +"'/>");
              	$("#photo-7").html("");
              	$("#cerrar-7").html("");
              	$("#photo-7").append("<img src='"+ e.target.result +"' id='thumb-7' class='thumb' title='" + fileName + "' />");
              	$("#cerrar-7").show(function(){
           			$("#cerrar-7").append("<img src='http://server/eltaladro/public/img/close.png' width='20' height='20' alt='close'/>");
       			});   
    		} 
        	reader.readAsDataURL(file); 
		}else{
 			alert("Solo se permiten JPG, GIF, PNG");
		}
    }); // Fin preload Photo5
	
	fileInput8.on("change", function(){
		// object file, nombre del archivo, tamaño, type 
        var file     = this.files[0],
            fileName = file.name,
            fileSize = file.size,
            fileType = file.type;
       
		if(fileType.match('image.*')){
 			//Validamos el tipo de archivo o file que deseamos subir.
          
   			//FileReader API HTML5,
        	var reader = new FileReader();
            reader.onload = function(e){
            	// console.log("<img src='"+ e.target.result +"'/>");
            	//$("#resultados").append("<img src='"+ e.target.result +"'/>");
              	$("#photo-8").html("");
              	$("#cerrar-8").html("");
              	$("#photo-8").append("<img src='"+ e.target.result +"' id='thumb-8' class='thumb' title='" + fileName + "' />");
              	$("#cerrar-8").show(function(){
           			$("#cerrar-8").append("<img src='http://server/eltaladro/public/img/close.png' width='20' height='20' alt='close'/>");
       			});   
    		} 
        	reader.readAsDataURL(file); 
		}else{
 			alert("Solo se permiten JPG, GIF, PNG");
		}
    }); // Fin preload Photo5
	
	fileInput9.on("change", function(){
		// object file, nombre del archivo, tamaño, type 
        var file     = this.files[0],
            fileName = file.name,
            fileSize = file.size,
            fileType = file.type;
       
		if(fileType.match('image.*')){
 			//Validamos el tipo de archivo o file que deseamos subir.
          
   			//FileReader API HTML5,
        	var reader = new FileReader();
            reader.onload = function(e){
            	// console.log("<img src='"+ e.target.result +"'/>");
            	//$("#resultados").append("<img src='"+ e.target.result +"'/>");
              	$("#photo-9").html("");
              	$("#cerrar-9").html("");
              	$("#photo-9").append("<img src='"+ e.target.result +"' id='thumb-9' class='thumb' title='" + fileName + "' />");
              	$("#cerrar-9").show(function(){
           			$("#cerrar-9").append("<img src='http://server/eltaladro/public/img/close.png' width='20' height='20' alt='close'/>");
       			});   
    		} 
        	reader.readAsDataURL(file); 
		}else{
 			alert("Solo se permiten JPG, GIF, PNG");
		}
    }); // Fin preload Photo5
    
	// Eventos la funcionalidad boton cerrar
  	$("#cerrar-0").on("click",function(){
       $("#thumb-0, #cerrar-0").hide();
       //fileInput0.val('');  chrome, firefox, safari, No esta probado con IE u opera.
       fileInput0.replaceWith(fileInput0 = fileInput0.val('').clone(true));
  	});
   	$("#cerrar-1").on("click",function(){
       $("#thumb-1, #cerrar-1").hide();
       fileInput1.replaceWith(fileInput1 = fileInput1.val('').clone(true));
  	});
    $("#cerrar-2").on("click",function(){
       $("#thumb-2, #cerrar-2").hide();
       fileInput2.replaceWith(fileInput2 = fileInput2.val('').clone(true));
  	});
	$("#cerrar-3").on("click",function(){
       $("#thumb-3, #cerrar-3").hide();
       fileInput3.replaceWith(fileInput3 = fileInput3.val('').clone(true));
  	});
	$("#cerrar-4").on("click",function(){
       $("#thumb-4, #cerrar-4").hide();
       fileInput4.replaceWith(fileInput4 = fileInput4.val('').clone(true));
  	});
	$("#cerrar-5").on("click",function(){
       $("#thumb-5, #cerrar-5").hide();
       fileInput5.replaceWith(fileInput5 = fileInput5.val('').clone(true));
  	});
	$("#cerrar-6").on("click",function(){
       $("#thumb-6, #cerrar-6").hide();
       fileInput6.replaceWith(fileInput6 = fileInput6.val('').clone(true));
  	});
	$("#cerrar-7").on("click",function(){
       $("#thumb-7, #cerrar-5").hide();
       fileInput7.replaceWith(fileInput7 = fileInput7.val('').clone(true));
  	});
	$("#cerrar-8").on("click",function(){
       $("#thumb-8, #cerrar-8").hide();
       fileInput8.replaceWith(fileInput8 = fileInput8.val('').clone(true));
  	});
	$("#cerrar-9").on("click",function(){
       $("#thumb-9, #cerrar-9").hide();
       fileInput9.replaceWith(fileInput9 = fileInput9.val('').clone(true));
  	});
	
});// Fin Jquery DOM