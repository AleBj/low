<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<title><?php echo $this->titulo?></title>
<meta name="description" content="description">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta name="robots" content="all" />
<link rel="shorcut icon" href="favicon.png" />
          
<meta property="og:title" content="OG:TITULO"/>
<meta property="og:type" content="website"/>
<meta property="og:url" content="https://www.OG:URL.com/"/>
<meta property="og:image" content="OG:IMAGE"/>
<meta property="og:site_name" content="OG:SITE NAME"/>
<meta property="og:description" content="OG:DESCRIPTION"/>

<link href="<?php echo $_params['ruta_css']?>reset.css" rel="stylesheet">
<link href="<?php echo $_params['ruta_css']?>css.css" rel="stylesheet">
<link href="<?php echo $_params['ruta_css']?>font-awesome.min.css" rel="stylesheet">
<link href="<?php echo $_params['ruta_font']?>fonts.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700|Playfair+Display:700&display=swap" rel="stylesheet">

<?php if(isset($_params['css_plugin']) && count($_params['css_plugin'])):?>
<?php for($i=0;$i<count($_params['css_plugin']);$i++):?>
<link href="<?php echo $_params['css_plugin'][$i]?>" rel="stylesheet" type="text/css">
<?php endfor?>
<?php endif?> 

<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

<?php if(isset($_params['js']) && count($_params['js'])):?>
<?php for($i=0;$i<count($_params['js']);$i++):?>
<script src="<?php echo $_params['js'][$i]?>" type="text/javascript"></script>
<?php endfor?>
<?php endif?>

<?php if(isset($_params['js_plugin']) && count($_params['js_plugin'])):?>
<?php for($i=0;$i<count($_params['js_plugin']);$i++):?>
<script src="<?php echo $_params['js_plugin'][$i]?>" type="text/javascript"></script>
<?php endfor?>
<?php endif?>

<!-- Just for debugging purposes. Don't actually copy this line! -->
<!--[if lt IE 9]><script src="../../docs-assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->

</head>
<body >
<header>
	<?php if(isset($this->_error)):?>
    <div id="_errl" class="alert alert-danger">
        <a class="close" data-dismiss="alert">x</a>
        <?php echo $this->_error?>
    </div>
    <?php endif?>
    
    <?php if(isset($this->_mensaje)):?>
    <div id="_errl" class="alert alert-success">
        <a class="close" data-dismiss="alert">x</a>
        <?php echo $this->_mensaje?>
    </div>
    <?php endif?>	
</header>