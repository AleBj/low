<!DOCTYPE html>
<html lang="es">
<head>
  	<title>Aspect Ratio with Preview Pane</title>
  	<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
      <!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script> -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- <script src="<?=$this->_conf['url_enlace']?>views/layout/recorte/js/jquery.min.js"></script> -->
    <script src="<?=$this->_conf['url_enlace']?>views/layout/recorte/js/jquery.Jcrop.js"></script>
    <!-- <script src="<?=$this->_conf['url_enlace']?>views/layout/recorte/js/bootstrap.min.js"></script> -->

    <script type="text/javascript">
      jQuery(function($){

        // Create variables (in this scope) to hold the API and image size
        var jcrop_api,
            boundx,
            boundy,

            // Grab some information about the preview pane
            $preview = $('#preview-pane'),
            $pcnt = $('#preview-pane .preview-container'),
            $pimg = $('#preview-pane .preview-container img'),

            xsize = $pcnt.width(),
            ysize = $pcnt.height();
        
        console.log('init',[xsize,ysize]);
        $('#target').Jcrop({
    	 
          onChange: updatePreview,
           onSelect: updateCoords,
          aspectRatio: xsize / ysize
        },function(){
          // Use the API to get the real image size
          var bounds = this.getBounds();
          boundx = bounds[0];
          boundy = bounds[1];
          // Store the API in the jcrop_api variable
          jcrop_api = this;

          // Move the preview into the jcrop container for css positioning
          $preview.appendTo(jcrop_api.ui.holder);
        });

        function updatePreview(c)
        {
          if (parseInt(c.w) > 0)
          {
            var rx = xsize / c.w;
            var ry = ysize / c.h;

            $pimg.css({
              width: Math.round(rx * boundx) + 'px',
              height: Math.round(ry * boundy) + 'px',
              marginLeft: '-' + Math.round(rx * c.x) + 'px',
              marginTop: '-' + Math.round(ry * c.y) + 'px'
            });
          }
        };
      });

      function updateCoords(c)
      {
        $('#x').val(c.x);
        $('#y').val(c.y);
        $('#w').val(c.w);
        $('#h').val(c.h);
      };

      function checkCoords()
      {
        if (parseInt($('#w').val())) return true;
        alert('Please select a crop region then press submit.');
        return false;
      };
    </script>

<link rel="stylesheet" href="<?=$this->_conf['url_enlace'] ?>views/layout/recorte/css/main_.css" type="text/css">
<link rel="stylesheet" href="<?=$this->_conf['url_enlace'] ?>views/layout/recorte/css/demos.css" type="text/css">
<link rel="stylesheet" href="<?=$this->_conf['url_enlace'] ?>views/layout/recorte/css/jquery.Jcrop.css" type="text/css">

