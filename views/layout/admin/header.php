<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Last-Modified" content="0">
    <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
    <meta http-equiv="Pragma" content="no-cache">

    <title>Quick Divorce | Backend</title>

    <!-- Css principales-->
    <link href="<?=$_params['ruta_css']?>bootstrap.min.css" rel="stylesheet">
    <link href="<?=$_params['ruta_css']?>font.min.css" rel="stylesheet">
    <link href="<?= $_params['ruta_css']?>bootstrap-glyphicons.css" rel="stylesheet" type="text/css">
    <link href="<?= $_params['ruta_css']?>jquery-ui.css" rel="stylesheet" type="text/css">
    <!-- css -->
    <?php if(isset($_params['css']) && count($_params['css'])):?>
    <?php for($i=0;$i<count($_params['css']);$i++):?>
    <link href="<?=$_params['css'][$i]?>" rel="stylesheet" type="text/css">
    <?php endfor?>
    <?php endif?>

    <!-- plugin css -->
    <?php if(isset($_params['css_plugin']) && count($_params['css_plugin'])):?>
    <?php for($i=0;$i<count($_params['css_plugin']);$i++):?>
    <link href="<?=$_params['css_plugin'][$i]?>" rel="stylesheet" type="text/css">
    <?php endfor?>
    <?php endif?>

    <!-- Css del administrador -->
    <link href="<?=$_params['ruta_css']?>animate.min.css" rel="stylesheet">
   <!--  <link href="<?=$_params['ruta_css']?>custom.css" rel="stylesheet"> -->
    <link href="<?=$_params['ruta_css']?>style.css" rel="stylesheet">

    <!-- Mainly scripts -->
    <!--   <script src="<?=$_params['ruta_js']?>jquery-3.0.0.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="<?= $_params['ruta_js']?>jquery-ui.min.js"></script>   
    <script src="<?=$_params['ruta_js']?>bootstrap.min.js"></script>
    <script src="<?=$_params['ruta_js']?>jquery.metisMenu.js"></script>
    <script src="<?=$_params['ruta_js']?>jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="<?=$_params['ruta_js']?>inspinia.js"></script>
    <script src="<?=$_params['ruta_js']?>pace.min.js"></script>

    <!-- js -->
    <?php if(isset($_params['js']) && count($_params['js'])):?>
    <?php for($i=0;$i<count($_params['js']);$i++):?>
    <script src="<?=$_params['js'][$i]?>" type="text/javascript"></script>
    <?php endfor?>
    <?php endif?>

    <!-- plugin js -->
    <?php if(isset($_params['js_plugin']) && count($_params['js_plugin'])):?>
    <?php for($i=0;$i<count($_params['js_plugin']);$i++):?>
    <script src="<?=$_params['js_plugin'][$i]?>" type="text/javascript"></script>
    <?php endfor?>
    <?php endif?> 
    
 
    
</head>

<body>
    <noscript><p>Para el correcto funcionamiento debe tener el soporte para javascript habilitado</p></noscript>

    <?php if(isset($this->_error)):?>
    <script>  
    $(document).ready(function(){
        $('#__error').modal({show:true});
    });
    </script>
    <div class="modal inmodal" id="__error" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content animated flipInY">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                    <i class="fa fa-exclamation-triangle modal-icon"></i>
                    <h4 class="modal-title">Alerta</h4>
                    <small class="font-bold">Información importante</small>
                </div>
                <div class="modal-body">
                    <p><?=$this->_error?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal" id="_cerrar" >Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <?php endif?> 

    <?php if(isset($this->_mensaje)):?>
    <script>  
    $(document).ready(function(){
        $('#__mensaje').modal({show:true});
    });
    </script>
    <div class="modal inmodal" id="__mensaje" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content animated flipInY">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <i class="fa fa-bell-o modal-icon"></i>
                    <h4 class="modal-title">Atención</h4>
                    <small class="font-bold">Información importante</small>
                </div>
                <div class="modal-body">
                    <p><?=$this->_mensaje?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal" id="_cerrar" >close</button>
                </div>
            </div>
        </div>
    </div>
    <?php endif?>

    <?php if(isset($this->_aut)):?>
    <script>  
    $(document).ready(function(){
        $('#__aut').modal({show:true});
    });
    </script>
    <div class="modal inmodal" id="__aut" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content animated flipInY">
                <div class="modal-header">
                    <i class="fa fa-hand-paper-o modal-icon"></i>
                    <h4 class="modal-title">Alerta</h4>
                    <small class="font-bold">Debe autentificarse</small>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 b-r"><h3 class="m-t-none m-b">Autentificate</h3>
                            <form role="form" method="post" action="<?=$this->_conf['base_url'] . 'administrador/usuarios/autentificacionEstricta'?>" name="_aut" id="_aut" autocomplete="off">
                                <input type="hidden" name="_csrf" value="<?=$this->_sess->get('_csrf')?>">
                                <input type="hidden" name="retorno" value="<?=$this->_aut?>">
                                <div class="form-group">
                                    <label>Password</label> 
                                    <input type="password" name="pass" placeholder="Password" class="form-control" autocomplete="off">
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong>Ejecutar</strong></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
    <?php endif?> 

    <div id="wrapper">
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="side-menu">
                    <li class="nav-header">
                        <?php if($this->_sess->get('autenticado')):?>
                        <div class="dropdown profile-element"> 
                            

                           <!--  <div class="clearfix" style="margin-bottom: 10px">
                                <a href="<?=$this->_conf['url_enlace']?>administrador">
                                    <img alt="Aliadas" src="<?php echo $this->_conf['base_url']?>views/layout/admin/img/logo-header.png" width="150">
                                </a>
                            </div> -->


                            <div class="profile clearfix">
					              <div class="profile_pic">
					              	<img alt="image" class="img-circle profile_img"	 src="<?php $_imgg = ($this->_sess->get('usuario_img') != '') ? $this->_sess->get('usuario_img') : 'user_default.jpg'; echo $this->_conf['base_url'] . "views/layout/admin/img/" . $_imgg;?>">
					              </div>
					              <div class="profile_info">
					                <span>Hello,</span>
					                <h2><?=$this->_sess->get('usuario')?></h2>
					              </div>
            				</div> 
                            
                           <!--  <span style="float: left; margin-right: 10px"> 
                                <img alt="image" class="img-circle" width="40" src="<?php $_imgg = ($this->_sess->get('usuario_img') != '') ? $this->_sess->get('usuario_img') : 'user_default.jpg'; echo $this->_conf['base_url'] . "views/layout/admin/img/" . $_imgg;?>">
                            </span>
                             
                            <span style="clear: both;"> 
                                <span class="m-t-xs"> 
                                    <strong class="font-bold"><?=$this->_sess->get('usuario')?></strong>
                                </span> 
                                <span class="text-muted text-xs">
                                    <?=($this->_sess->get('level') == 1) ? 'Super Administrador' : 'Usuario' ?>
                                </span>
                            </span>  -->
                            
                        </div>
                        <?php endif?>
                        <div class="logo-element" style="width: 50px; height: 50px;">
                         	<!-- <img alt="image"  width="50" src="<?php echo $this->_conf['base_url']?>views/layout/admin/img/logo-header2.png"> -->
                        </div>
                    </li>
                    
                    <!-- <?php if($this->_sess->get('level')==1112):?>
                    <li <?=($this->_item == 'sersocio') ? 'class="active"' : ''?>>
                       <a href="<?=$this->_conf['base_url']?>administrador/sersocio">
                            <i class="fa fa-list-alt fa-lg"></i> <span class="nav-label">Pre-Inscripción Socios</span>
                        </a>                        
                    </li>
					<?php else:?> -->

                    
                    <?php if($this->_sess->get('level')==1):?>

                    <li <?=($this->_item == 'usuarios') ? 'class="active"' : ''?>>
                        <a href="<?=$this->_conf['url_enlace']?>administrador/usuarios">
                            <!-- <i class="fa fa-user fa-lg"></i>  -->
                            <span class="nav-label">Backend users</span>
                        </a>                       
                    </li>
                    
                    <?php endif?>


                  

                     <!-- <li <?=($this->_item == 'categorias' || $this->_item == 'subcategorias') ? 'class="active"' : ''?>>
                        <a href="#"> <span class="nav-label">Categorias</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="<?php echo $this->_conf['url_enlace']?>administrador/categorias">Categorias</a></li>
                            <li><a href="<?php echo $this->_conf['url_enlace']?>administrador/subcategorias">Subcategorias</a></li>
                        </ul>
                    </li>

                    

                    


                    <li <?=($this->_item == 'promociones') ? 'class="active"' : ''?>>
                        <a href="#"> <span class="nav-label">Promociones</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="<?php echo $this->_conf['url_enlace']?>administrador/promociones/altas">Alta</a></li>
                            <li><a href="<?php echo $this->_conf['url_enlace']?>administrador/promociones/bajas">Baja</a></li>
                        </ul>
                    </li>


                    <li <?=($this->_item == 'banners') ? 'class="active"' : ''?>>
                        <a href="#"> <span class="nav-label">Banners</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="<?php echo $this->_conf['url_enlace']?>administrador/banners/altas">Alta</a></li>
                            <li><a href="<?php echo $this->_conf['url_enlace']?>administrador/banners/bajas">Baja</a></li>
                        </ul>
                    </li>

                    <li <?=($this->_item == 'users') ? 'class="active"' : ''?>>
                        <a href="<?=$this->_conf['url_enlace']?>administrador/users">
                            <span class="nav-label">Clientes</span>
                        </a>                        
                    </li>
                     
                    
                    <li <?=($this->_item == 'descuentos') ? 'class="active"' : ''?>>
                        <a href="<?=$this->_conf['url_enlace']?>administrador/descuentos">
                            <span class="nav-label">Descuentos</span>
                        </a>                        
                    </li>

                    <li <?=($this->_item == 'sucursales') ? 'class="active"' : ''?>>
                        <a href="<?=$this->_conf['url_enlace']?>administrador/sucursales">
                            <span class="nav-label">Sucursales</span>
                        </a>                        
                    </li> -->

                    <!-- ///////////////////////////////////////////////////////////////////////////////////////////////// -->


                   

                    <!-- <li <?=($this->_item == 'slider') ? 'class="active"' : ''?>>
                        <a href="<?=$this->_conf['url_enlace']?>administrador/slider">
                            <span class="nav-label">Slider</span>
                        </a>                        
                    </li> -->

                    <!-- <li <?=($this->_item == 'slider' || $this->_item == 'estadisticas') ? 'class="active"' : ''?>>
                        <a href="#"> <span class="nav-label">Home</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="<?php echo $this->_conf['url_enlace']?>administrador/slider">Slider</a></li>
                            <li><a href="<?php echo $this->_conf['url_enlace']?>administrador/estadisticas/index/aprobado">Estadísticas Aprobadas</a></li>
                            <li><a href="<?php echo $this->_conf['url_enlace']?>administrador/estadisticas/index/pendiente">Estadísticas Pendientes</a></li>
                            <li><a href="<?php echo $this->_conf['url_enlace']?>administrador/estadisticas/index/rechazado">Estadísticas Rechazadas</a></li>
                            <li><a href="<?php echo $this->_conf['url_enlace']?>administrador/estadisticas/index/devolucion">Estadísticas Devoluciones</a></li>
                        </ul>
                    </li> -->

                    <!-- <li <?=($this->_item == 'sucursales') ? 'class="active"' : ''?>>
                        <a href="<?=$this->_conf['url_enlace']?>administrador/sucursales">
                            <span class="nav-label">Sucursales</span>
                        </a>                        
                    </li> -->

                    <!--  <li <?=($this->_item == 'grupos') ? 'class="active"' : ''?>>
                        <a href="<?=$this->_conf['url_enlace']?>administrador/grupos">
                            <span class="nav-label">Grupos</span>
                        </a>                        
                    </li> -->

                    <!-- <li <?=($this->_item == 'descuentos') ? 'class="active"' : ''?>>
                        <a href="<?=$this->_conf['url_enlace']?>administrador/descuentos">
                            <span class="nav-label">Descuentos</span>
                        </a>                        
                    </li> -->

                    <!-- <li <?=($this->_item == 'cupondescuentos') ? 'class="active"' : ''?>>
                        <a href="<?=$this->_conf['url_enlace']?>administrador/cupondescuentos">
                            <span class="nav-label">Cupones de descuentos</span>
                        </a>                        
                    </li> -->

                    <!-- <li <?=($this->_item == 'cursos') ? 'class="active"' : ''?>>
                        <a href="#"> <span class="nav-label">Cursos</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="<?php echo $this->_conf['url_enlace']?>administrador/cursos/categorias">Categorias</a></li>
                            <li><a href="<?php echo $this->_conf['url_enlace']?>administrador/cursos/altas">Alta</a></li>
                            <li><a href="<?php echo $this->_conf['url_enlace']?>administrador/cursos/bajas">Baja</a></li>
                        </ul>
                    </li>-->

                     <li <?=($this->_item == 'home') ? 'class="active"' : ''?>>
                        <a href="#"> <span class="nav-label">Home</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="<?php echo $this->_conf['url_enlace']?>administrador/home/seo">SEO</a></li>
                        </ul>
                    </li> 
 
                    
                     <li <?=($this->_item == 'productos') ? 'class="active"' : ''?>>
                        <a href="#"> <span class="nav-label">Products</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="<?php echo $this->_conf['url_enlace']?>administrador/productos/fijos">Static</a></li>
                            <li><a href="<?php echo $this->_conf['url_enlace']?>administrador/productos/variables">Variables</a></li>

                            <li><a href="<?php echo $this->_conf['url_enlace']?>administrador/productos/seo_fijos">SEO Static</a></li>
                            <li><a href="<?php echo $this->_conf['url_enlace']?>administrador/productos/seo_variables">SEO Variables</a></li>
                        </ul>
                    </li> 
                   

                    <li <?=($this->_item == 'clientes') ? 'class="active"' : ''?>>
                        <a href="#"> <span class="nav-label">Clients</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="<?php echo $this->_conf['url_enlace']?>administrador/clientes/lead">Lead</a></li>
                            <li><a href="<?php echo $this->_conf['url_enlace']?>administrador/clientes/customer">Customer</a></li>
                            <li><a href="<?php echo $this->_conf['url_enlace']?>administrador/clientes/inactive">Inactive</a></li>
                        </ul>                      
                    </li>

                    
                    <li <?=($this->_item == 'compras') ? 'class="active"' : ''?>>
                        <a href="#"> <span class="nav-label">Orders</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="<?php echo $this->_conf['url_enlace']?>administrador/compras/aprobados">Approved</a></li>
                            <li><a href="<?php echo $this->_conf['url_enlace']?>administrador/compras/pendientes">Pending</a></li>
                            <li><a href="<?php echo $this->_conf['url_enlace']?>administrador/compras/rechazados">Rejected</a></li>
                        </ul>
                    </li> 

                    <li <?=($this->_item == 'forms') ? 'class="active"' : ''?>>
                        <a href="<?=$this->_conf['url_enlace']?>administrador/forms">
                            <span class="nav-label">Forms</span>
                        </a>                        
                    </li> 

                   


                    <!-- <li <?=($this->_item == 'blog') ? 'class="active"' : ''?>>
                        <a href="<?=$this->_conf['url_enlace']?>administrador/blog">
                            <span class="nav-label">Blog</span>
                        </a>                        
                    </li>-->

                     <li <?=($this->_item == 'blog') ? 'class="active"' : ''?>>
                        <a href="#"> <span class="nav-label">Blog</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">                            
                            <li><a href="<?php echo $this->_conf['url_enlace']?>administrador/blog">List</a></li>
                            <li><a href="<?php echo $this->_conf['url_enlace']?>administrador/blog/seo">SEO</a></li>
                        </ul>
                    </li> 

                   <!--  <li <?=($this->_item == 'press') ? 'class="active"' : ''?>>
                        <a href="<?=$this->_conf['url_enlace']?>administrador/press">
                            <span class="nav-label">Press</span>
                        </a>                        
                    </li> -->
 
                    <!-- <li <?=($this->_item == 'faqs') ? 'class="active"' : ''?>>
                        <a href="<?=$this->_conf['url_enlace']?>administrador/faqs">
                            <span class="nav-label">FAQs</span>
                        </a>                        
                    </li> -->

                    <li <?=($this->_item == 'faqs') ? 'class="active"' : ''?>>
                        <a href="#"> <span class="nav-label">FAQs</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">                            
                            <li><a href="<?php echo $this->_conf['url_enlace']?>administrador/faqs">List</a></li>
                            <li><a href="<?php echo $this->_conf['url_enlace']?>administrador/faqs/seo">SEO</a></li>
                        </ul>
                    </li> 

                    <li <?=($this->_item == 'cms') ? 'class="active"' : ''?>>
                        <a href="#"> <span class="nav-label">CMS</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="<?php echo $this->_conf['url_enlace']?>administrador/cms/disclaimer">Disclaimer</a></li>
                            <li><a href="<?php echo $this->_conf['url_enlace']?>administrador/cms/howitworks">How it works</a></li>
                            <li><a href="<?php echo $this->_conf['url_enlace']?>administrador/cms/whyus">Why us</a></li>
                            <li><a href="<?php echo $this->_conf['url_enlace']?>administrador/cms/termsandconditions">Terms and Conditions</a></li>
                        </ul>
                    </li> 

                    <!-- <?php endif?> -->
                    <li>
                        <a target="_blank" href="<?=$this->_conf['base_url']?>">
                            <!-- <i class="fa fa-desktop"></i>  -->
                            <span class="nav-label">Frontend</span>
                        </a>
                    </li>
                   
                </ul>
            </div>
        </nav>

        <div id="page-wrapper" class="gray-bg dashbard-1">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">

                    <div class="navbar-header">
                        <!-- <a class="navbar-minimalize minimalize-styl-2 btn btn-default " href="#"><i class="fa fa-bars"></i> </a> -->
                        <!--<form role="search" class="navbar-form-custom" method="post" action="search_results.html">
                            <div class="form-group">
                                <input type="text" placeholder="Buscar..." class="form-control" name="top-search" id="top-search">
                            </div>
                        </form>-->
                       <!-- <a class="minimalize-styl-2 btn btn-danger" href="javascript:void(0);" onClick="limpiarCache();"><i class="fa fa-trash"></i> Limpiar Caché</a>
                        <div class="minimalize-styl-2 btn btn-white _loading" style="display:none;">
                        	<i class="fa fa-cog fa-spin fa-lg"></i>
                        </div>-->
                        
                       <!--  <a class="minimalize-styl-2 btn btn-danger" id="limp_cach" href="javascript:void(0);"><i class="fa fa-trash"></i> Limpiar Caché</a>
                        <div class="minimalize-styl-2 btn btn-white _loader" style="display:none;">
                            <i class="fa fa-cog fa-spin fa-lg"></i>
                        </div> -->
                        
                        
                    </div>
                    
                    

                    <ul class="nav nav-sesion navbar-top-links navbar-right">
                        
                        
                        <?php if($this->_sess->get('autenticado')):?>
                       <!-- <li>
                            <span class="m-r-sm text-muted welcome-message">Administrador de Contenidos Walmart Argentina</span>
                            <span class="text-muted welcome-message"><?=($this->_sess->get('level') == 1) ? 'Super Administrador' : 'Usuario' ?></span>
                        </li>-->
                        
                        <!--  <li>
                            <a class="count-info" href="<?=$this->_conf['url_enlace']?>administrador/usuarios/mailbox">
                                <i class="fa fa-envelope"></i> <span class="label label-warning"><?=$this->_sess->get('mensajes_recibidos')?></span>
                            </a>
                        </li>   -->                     
                        
                        <li>							
                            <div class="dropdown"> 
                                
                                <a data-toggle="dropdown" class="menu_sesion dropdown-toggle" href="#">
                                	<span>
                                        <img alt="image" class="img-circle" width="40" src="<?php $_imgg = ($this->_sess->get('usuario_img') != '') ? $this->_sess->get('usuario_img') : 'user_default.jpg'; echo $this->_conf['base_url'] . "views/layout/admin/img/" . $_imgg;?>">
                                    </span>
                                   <!-- <span class="caret"></span>
                                    <span class="clear"> 
                                        <span class="block m-t-xs"> 
                                            <strong class="font-bold"><?=$this->_sess->get('usuario')?> <b class="caret"></b></strong>
                                        </span> 
                                        <span class="text-muted text-xs block">
                                            <?=($this->_sess->get('level') == 1) ? 'Super Administrador' : 'Usuario' ?> 
                                        </span>
                                    </span> --> 
                                </a>
                                <ul class="dropdown-menu animated m-t-xs">
                                	<li><a href="javascript:void(0);" style="cursor:default;"><strong class="font-bold"><?=$this->_sess->get('usuario')?></strong><br><span class="text-muted2"><?=($this->_sess->get('level') == 1) ? 'Administrator' : 'User' ?></span></a></li>
                                    <li class="divider"></li>
                                   <!--< <?php if($this->_sess->get('level')!=1112):?>
                                    <li><a href="<?=$this->_conf['url_enlace']?>administrador/usuarios/componermail">Enviar mensaje</a></li>
                                    <?php endif?>
                                    li class="divider"></li>-->
                                    <li><a href="<?=$this->_conf['url_enlace']?>usuarios/login/cerrar">Sign out</a></li>
                                </ul>
                            </div>                           
                        </li>
                         <?php endif?>
                        
                       <!-- <li>
                            <a href="<?=$this->_conf['url_enlace']?>registro/login/cerrar">
                                <i class="fa fa-sign-out"></i> Cerrar Sesión
                            </a>
                        </li>-->
                    </ul>

                </nav>
                <!--<div id="aviso_limpiar_cache" class="wrapper" style="display:none;">
                    <div class="alert alert-success" role="alert">
                      <strong>El caché ha sido borrado con exito!</strong>
                    </div>
                </div>-->
            </div>