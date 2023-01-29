<!doctype html>
<html lang="en">
<head>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-ZV1HP3YWTY"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-ZV1HP3YWTY');
  gtag('config', 'AW-11013596747');
</script>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?=(isset($this->titulo) && $this->titulo!='') ? $this->titulo : 'A CONSCIOUS SEPARATION POWERED BY THE QUICK DIVORCE PLATFORM'?></title>
<meta name="description" content="<?=(isset($this->description) && $this->description!='') ? $this->description : ''?>">
<link rel="shortcut icon" href="<?php echo $_params['ruta_img']?>TQD_logoCuts-01-300x300.png" type="image/x-icon">

<?php if(isset($_params['meta']) && count($_params['meta'])):?>
<?php for($i=0;$i<count($_params['meta']);$i++):?>
<?php echo $_params['meta'][$i]?>
<?php endfor?>
<?php endif?>

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
<!--fonts-->
<link rel="stylesheet" href="<?php echo $_params['ruta_font']?>uaf.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Karla:wght@300&display=swap" rel="stylesheet">
<link href="<?php echo $_params['ruta_css']?>normalize.css" rel="stylesheet">
<link href="<?php echo $_params['ruta_css']?>style.css?454" rel="stylesheet">
<?php if($this->_item == 'forms'):?>
<link href="<?php echo $_params['ruta_css']?>forms.css?4" rel="stylesheet">
<?php endif?>

<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>


<?php if(isset($_params['css_plugin']) && count($_params['css_plugin'])):?>
<?php for($i=0;$i<count($_params['css_plugin']);$i++):?>
<link href="<?php echo $_params['css_plugin'][$i]?>" rel="stylesheet" type="text/css">
<?php endfor?>
<?php endif?> 

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

<!-- <script>
$(document).ready(function() {

    $('#social .cart').on('click', function(e){
        e.preventDefault()
        $('#cart').fadeIn(400)
    })
    $('#cart .fa-close').on('click',  function(e){
        e.preventDefault()
        $('#cart').fadeOut(400)
    })

    $('#cart .wellBlue').on('click',  function(e){
        e.preventDefault()
        $('#cart').fadeOut(400)
    })
   

    $('.error').hide();
    $('.ok').hide();
   
    

    $.fn.eliminarProdPopUp = function(_val){  
        //var url = window.location.href;
        //var datastring = "data="+_data+"&posicion="+_pos+"&valor="+_val;
        
        //alert(url)
        
        $.ajax({
          type: 'POST',
          url: _root_+"cart/eliminarProdHeader",
          //data: datastring,
          dataType: "json",
          data: {"id" : _val, "_csrf" : "<?=$this->_sess->get('_csrf')?>"},       
          beforeSend: function(){
            //$('.formPostulante').animate({'opacity': '0'}, 300);
          },
          success: function(data){
            // $('#tr_'+data.id).remove();
            $('#cart .contentCart .table').html(data.cart);
            // $('#cartpopupMob').html(data.cart);
            // $('header .navbar .navbar-collapse ul.navbar-nav li.cart a span .inCart').html(data.cant_item);
            $('#social a.cart span').html(data.cant_item);
            // $('#cartpopup .totals .item .num strong').html(data.total);
            /*if(data.total == '$0,00'){
              $('#finalizar').hide();
            }*/
            
            
          }
        });
    }; 


    $('#btCodDesc').click(function(e){

        e.preventDefault();
        e.stopPropagation();

        var _val = $("#cod_desc").val(); 

        // console.log(e);
        //  return false;

        if(_val.length ==8){    

            $('#_codesc').val(_val);                    
 
            $.ajax({
                type: 'POST',
                url: _root_+"cart/traerCodigoDescuento",
                //data: datastring,
                dataType: "json",
                data: {"_cod" : _val, "_csrf" : "<?=$this->_sess->get('_csrf')?>"},             
                beforeSend: function(){
                    //$('.formPostulante').animate({'opacity': '0'}, 300);

                },
                success: function(data){

                    if (data.total!='vacio') {
                        $('.table .row-table .cupon').fadeOut(400);
                        $('.table .row-table.desc .head.sbt').html(data.codesc);             
                        $('.table .row-table.total .head.sbt').html(data.total);

                    }else{
                        alert('El código no existe');
                    }
                }
            });          


            return false;

        }else{
            return false;
        }

    });





});

    
</script> -->



</head>

<body>


<header>
        <div class="container">
            <a href="<?=$this->_conf['base_url']?>">
                <img src="<?php echo $_params['ruta_img']?>TQD_logo.png" alt="The Quick Divorce" class="logo">
            </a>

            <nav>
                <ul>
                    <li>
                        <a href="<?=$this->_conf['base_url']?>disclaimer">Disclaimer</a>
                    </li>
                    <li>
                        <a href="<?=$this->_conf['base_url']?>cart">
                            <svg aria-hidden="true" focusable="false" data-prefix="fas" class="" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M528.12 301.319l47.273-208C578.806 78.301 567.391 64 551.99 64H159.208l-9.166-44.81C147.758 8.021 137.93 0 126.529 0H24C10.745 0 0 10.745 0 24v16c0 13.255 10.745 24 24 24h69.883l70.248 343.435C147.325 417.1 136 435.222 136 456c0 30.928 25.072 56 56 56s56-25.072 56-56c0-15.674-6.447-29.835-16.824-40h209.647C430.447 426.165 424 440.326 424 456c0 30.928 25.072 56 56 56s56-25.072 56-56c0-22.172-12.888-41.332-31.579-50.405l5.517-24.276c3.413-15.018-8.002-29.319-23.403-29.319H218.117l-6.545-32h293.145c11.206 0 20.92-7.754 23.403-18.681z"></path></svg>
                        </a>
                    </li>
                    <li>
                        <a href="<?=$this->_conf['base_url']?>user">
                            <img src="<?php echo $_params['ruta_img']?>user.png" alt="The Quick Divorce" class="logo_user">
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>


    <!-- menu flotante -->
    <a href="#burguer-menu" class="menu__btn">
        <span></span>
        <span></span>
        <span></span>
    </a>

    <!--menu desplegable-->
    <div class="burguer-menu" id="burguer-menu">
        <ul>
            <li><a href="<?=$this->_conf['base_url']?>howitworks">How It Works</a></li>
            <li><a href="<?=$this->_conf['base_url']?>getstarted">Get Started</a></li>
            <li><a href="<?=$this->_conf['base_url']?>whyus">Why Us</a></li>
            <li><a href="<?=$this->_conf['base_url']?>faqs">FAQ</a></li>
            <li><a href="<?=$this->_conf['base_url']?>product">Services</a></li>
            <li><a href="<?=$this->_conf['base_url']?>blog">Blog</a></li>
            <li><a href="<?=$this->_conf['base_url']?>disclaimer">Disclaimer</a></li>
        </ul>

    </div>