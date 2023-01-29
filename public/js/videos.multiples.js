//	Esta es una variable de control para mantener nombres
//	diferentes de cada campo creado dinamicamente.
var you = 0;
var padre = 0;
var vim = 0; 

//	Esta funcion nos devuelve el tipo de evento disparado.          
evento = function (evt) { 
   return (!evt) ? event : evt;
}

//	Funcion que crea los elementos
addVideo = function () { 

	// span you tube
	spanYouTube = document.createElement('span');
	spanYouTube.innerHTML = ' You Tube &nbsp;';
	
	// span vimeo
	spanVimeo = document.createElement('span');
	spanVimeo.innerHTML = ' Vimeo';
	
	//	Div General Video
   	divVideo = document.createElement('div');
   	divVideo.className = 'row';
   	divVideo.id = 'video_' + (++padre);
	divVideo.setAttribute('style','margin:0 0 10px 0px;');
	
	//	Div Video 03
   	divVideoTres = document.createElement('div');
   	divVideoTres.className = 'col-lg-3';
   	divVideoTres.id = 'video_03';
	
	//	Div Video 08
   	divVideoOcho = document.createElement('div');
   	divVideoOcho.className = 'col-lg-8';
   	divVideoOcho.id = 'video_08';
	
	//	Div Video 01
   	divVideoUno = document.createElement('div');
   	divVideoUno.className = 'col-lg-1';
   	divVideoUno.id = 'video_01';
	divVideoUno.setAttribute('style','line-height:2.5');
	
	// Radio Button You Tube
	radioYouTube = document.createElement('input');
	radioYouTube.setAttribute('type','radio');
	radioYouTube.setAttribute('checked','checked');
	radioYouTube.name = 'noticia_video_opcion_' + (++you);
	radioYouTube.id = 'you_tube';
	radioYouTube.value = 1;
	
	// Radio Button Vimeo
	radioVimeo = document.createElement('input');
	radioVimeo.setAttribute('type','radio');
	radioVimeo.name = 'noticia_video_opcion_' + (++vim);
	radioVimeo.id = 'vimeo';
	radioVimeo.value = 2;
	//radioVimeo.innerHTML = ' Vimeo';
	
	// Input path de video
	inputPathVideo = document.createElement('input');
	inputPathVideo.className = 'form-control';
	inputPathVideo.name = 'noticia_video_path[]';
	inputPathVideo.id = '';
	inputPathVideo.setAttribute('style','width:475px');
	inputPathVideo.setAttribute('placeholder','Colocar aqui el path');
	
	//	Eliminar los elementos
   	a = document.createElement('a');
	//El link debe tener el mismo nombre de la div padre, para efectos de localizarla y eliminarla
   	a.name = divVideo.id;
	a.id = 'cerrar';
   	a.href = 'javascript:void(0);';
	//Establecemos que dispare esta funcion en click
   	a.onclick = elimCamp;
	a.className = 'glyphicon glyphicon-minus';
   	
	divVideoTres.appendChild(radioYouTube);
	divVideoTres.appendChild(spanYouTube);
	divVideoTres.appendChild(radioVimeo);
	divVideoTres.appendChild(spanVimeo);
	divVideoOcho.appendChild(inputPathVideo);
	divVideoUno.appendChild(a);
	divVideo.appendChild(divVideoTres);
	divVideo.appendChild(divVideoOcho);
	divVideo.appendChild(divVideoUno);
	
	//	Instanciamos el div sobre el cual se crearan los elementos
   	container = document.getElementById('mas_videos');
	
	//	Y colocamos el div general en el el
	container.appendChild(divVideo);
}
	//	Con esta función eliminamos los elmentos a partir del elemento padre
elimCamp = function (evt){
   evt = evento(evt);
   nCampo = rObj(evt);
   div = document.getElementById(nCampo.name);
   div.parentNode.removeChild(div);
   --numero;
}
	//	Con esta función recuperamos una instancia del objeto que disparo el evento.
rObj = function (evt) { 
   return evt.srcElement ?  evt.srcElement : evt.target;
}