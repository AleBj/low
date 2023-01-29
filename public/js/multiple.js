//	Esta es una variable de control para mantener nombres
//	diferentes de cada campo creado dinamicamente.
var cont = 0,
	camp = 0,
	foto = 0,
	image = 0,
	cerrar = 0,
	inp = 0,
	copete = 0;
	titulo=0
	clink = 0;
	cliente = 0;

//	Esta funcion nos devuelve el tipo de evento disparado.          
evento = function (evt) { 
   return (!evt) ? event : evt;
}

//	Funcion que crea los elementos
addImagen = function () { 

	//	div General
   	divCont = document.createElement('div');
   	divCont.className = 'panel panel-default';
   	divCont.id = 'cont_din-' + (++cont);
	
	// div contenedor de campos
	divContCampos = document.createElement('div');
   	divContCampos.className = 'cont_campos';
   	divContCampos.id = 'cont_campos-' + (++camp);
	
	// div imagen
	divImagen = document.createElement('div');
	divImagen.className = 'imagen';
	divImagen.id = 'imagen-'+ (++image);
	
	// div photo
	divFoto = document.createElement('div');
	divFoto.className = 'link';
	divFoto.id = 'photo-' + (++foto);
	
	// div cerrar
	divCerrar = document.createElement('div');
	divCerrar.className ='cerrar';
	divCerrar.id = 'cerrar-' + (++cerrar);
	
	// input file
	inputFile = document.createElement('input');
	inputFile.name = 'file[]';
	inputFile.id = 'file-' + (++inp);
	inputFile.type = 'file';
	
	//	Campo Copete
	contCampoCopete = document.createElement('div');
	contCampoCopete.className = 'cont_copete';
	contCampoCopete.id = 'cont_copete' + (++copete);
	
	// Input Copete
	/*campoCopete = document.createElement('input');
	campoCopete.name = 'copete[]';
	campoCopete.className = 'form-control ancho_input';
	campoCopete.type = 'text';
	campoCopete.placeholder = 'Copete';*/
	
	
	campoCopete = document.createElement('textarea');
	campoCopete.name = 'desc[]';
	campoCopete.className = 'form-control';
	campoCopete.placeholder = 'Descripcion';
	
	
	
	//	Campo titulo
	contCampoTitulo = document.createElement('div');
	contCampoTitulo.className = 'cont_titulo';
	contCampoTitulo.id = 'cont_titulo' + (++titulo);
	
	// Input titulo
	campoTitulo = document.createElement('input');
	campoTitulo.name = 'titulo[]';
	campoTitulo.className = 'form-control ancho_input';
	campoTitulo.type = 'text';
	campoTitulo.placeholder = 'Titulo';

	//	Campo Link
	contCampoLink = document.createElement('div');
	contCampoLink.className = 'cont_link';
	contCampoLink.id = 'cont_link' + (++clink);
	
	// Input Link
	campoLink = document.createElement('input');
	campoLink.name = 'link[]';
	campoLink.className = 'form-control ancho_input';
	campoLink.type = 'text';
	campoLink.placeholder = 'Link';
	
	//	Campo clientes
	contCampoCliente = document.createElement('div');
	contCampoCliente.className = 'cont_cliente';
	contCampoCliente.id = 'cont_cliente' + (++cliente);
	
	// Select Clientes
	campoCliente = document.createElement('select');
	campoCliente.name = 'cliente[]';
	campoCliente.className = 'form-control ancho_input';
	//campoCliente.placeholder = 'Link';
	/*opt = document.createElement('option');
	opt.value = 'nada';
	opt.innerHTML = 'algo';
	campoCliente.appendChild(opt);*/
	
	
	var url= _root_ + 'administrador/multimedia/selectClientesAjax';

	$.ajax({
		dataType: "json",
		url:   url,
        type:  'post',
		beforeSend: function(){
			//Lo que se hace antes de enviar el formulario
			},
        success: function(respuesta){
			//lo que se si el destino devuelve algo
			//$("#localidad").html(respuesta.html);
			campoCliente.innerHTML = respuesta.html;
			//alert(respuesta.html);
		},
		error:	function(xhr,err){ 
			alert("readyState: "+xhr.readyState+"\nstatus: "+xhr.status+"\n \n responseText: "+xhr.responseText);
		}
	});
	
	
	
	// anidamos los elementos
	contCampoCopete.appendChild(campoCopete);
	contCampoTitulo.appendChild(campoTitulo);
	contCampoLink.appendChild(campoLink);
	contCampoCliente.appendChild(campoCliente);
	
	divImagen.appendChild(contCampoCopete);
	divImagen.appendChild(inputFile);
	divImagen.appendChild(divFoto);
	
	// div data
	divData = document.createElement('div');
	divData.className = 'data';
	divData.id = 'data';
	
	// div acciones
	divAcciones = document.createElement('div');
	divAcciones.className = 'acciones';
	divAcciones.id = 'acciones';
	
	//	Eliminar los elementos
   	a = document.createElement('a');
	// El link debe tener el mismo nombre de la div padre, 
	// para efectos de localizarla y eliminarla
   	a.name = divCont.id;
	a.className = 'btn btn-danger';
	a.id = 'cerrar';
   	a.href = 'javascript:void(0);';
	//Establecemos que dispare esta funcion en click
   	a.onclick = elimCamp;
   	a.innerHTML = 'Eliminar';

	//	anidamos los divs
	divAcciones.appendChild(a);
	divData.appendChild(divAcciones);
	
	divCont.appendChild(divImagen);
	divCont.appendChild(divData);
	
	// Campos
	
	divCont.appendChild(contCampoCliente);
	divCont.appendChild(contCampoTitulo);
	divCont.appendChild(contCampoLink);
	divCont.appendChild(contCampoCopete);
	
	
	//	Instanciamos el div sobre el cual se crearan los elementos
   	container = document.getElementById('cargador');
	
	//	Y colocamos el div general en el el
	container.appendChild(divCont);
	
	// cada vez que que se ejecute la funcion
	// se recargara el script de previzualizacion
	preVisualizacion(cont);	
}
//  Con esta función eliminamos los elmentos a 
//  partir del elemento padre
elimCamp = function (evt){
	evt = evento(evt);
   	nCampo = rObj(evt);
   	div = document.getElementById(nCampo.name);
   	div.parentNode.removeChild(div);
   	--cont;
	--foto;
	--image;
	--cerrar;
	--inp;
}
//  Con esta función recuperamos una 
//  instancia del objeto que disparo el evento.
rObj = function (evt) { 
   return evt.srcElement ?  evt.srcElement : evt.target;
}

	