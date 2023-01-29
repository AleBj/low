<script type="text/javascript"> var _root_ = '<?php echo $this->_conf['base_url']?>';</script>
<script type="text/javascript">
	$(document).ready(function(){		
		

		//Mandamos un toque de fade a lo botone
		$('body, html').animate({'opacity':1}, 1000);
		$('#busqueda article a, .btPostu').on('click', function(e){
	    	event.preventDefault();
	    	var lnk = $(this).attr('href');
	    	$('body').animate({'opacity':0}, 800, function(){
	    	 	location.href = lnk;
	    	});
	    })
		
	});

	
</script>
</body>
</html>
