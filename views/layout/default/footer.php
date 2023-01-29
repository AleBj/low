<!-- btn up-->
<button class="subir hidden">
    <svg aria-hidden="true" focusable="false" data-prefix="fas" class="" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M240.971 130.524l194.343 194.343c9.373 9.373 9.373 24.569 0 33.941l-22.667 22.667c-9.357 9.357-24.522 9.375-33.901.04L224 227.495 69.255 381.516c-9.379 9.335-24.544 9.317-33.901-.04l-22.667-22.667c-9.373-9.373-9.373-24.569 0-33.941L207.03 130.525c9.372-9.373 24.568-9.373 33.941-.001z"></path></svg>
</button>
<!--fin btn-->

<footer>
    <div class="container">
        <div>
            <img src="<?php echo $_params['ruta_img']?>footer_logo.png" alt="The Quick Divorce">
            <br>
            <br>

            <p class="gothamlight">
                2601 S. Bayshore Drive, 18th Floor<br>Coral Gables, FL 33133<br> 
                <br>
                &copy; 2021 TheQuickDivorce.com | All Rights Reserved. 
                <br>
                All information and depictions shown on this website remain subject to change. Please contact TheQuickDivorce.com for more information. <br> <strong>Site Designed and Powered by NewStarMedia</strong>
            </p>

            <a href="<?=$this->_conf['base_url']?>termsandconditions">Terms and Conditions</a>
        </div>
        

        <div>
            <h3>EXPLORE</h3>
            <ul class="foot__nav--explore">
               <!--  <li><a href="./how-the-quick-divorce-works.html">How It Works</a></li>
                <li><a href="./get-started.html">Get Started</a></li>
                <li><a href="./why-us.html">Why Us</a></li>
                <li><a href="./blog-online-divorce.html">Blog</a></li>
                <li><a href="./disclaimer-thequickdivorce-com.html">Disclaimer</a></li> -->

                <li><a href="<?=$this->_conf['base_url']?>howitworks">How It Works</a></li>
                <li><a href="<?=$this->_conf['base_url']?>getstarted">Get Started</a></li>
                <li><a href="<?=$this->_conf['base_url']?>whyus">Why Us</a></li>
                <li><a href="<?=$this->_conf['base_url']?>blog">Blog</a></li>
                <li><a href="<?=$this->_conf['base_url']?>disclaimer">Disclaimer</a></li>
            </ul>                
        </div>


        <div>
            <h3>CALL US (305) 614.4304</h3>
            <h3>se habla español</h3>

            <a href="mailto:info@thequickdivorce.com">Customer Contact</a>

            <ul class="footer__social-nav">
                <li><a href="https://www.facebook.com/thequickdivorce"><i class="fab fa-facebook-f"></i></a></li>
                <li><a href="https://www.instagram.com/thequickdivorce/"><i class="fab fa-instagram"></i></a></li>
                <li><a href="https://www.linkedin.com/in/aliettecarolan/"><i class="fab fa-linkedin-in"></i></a></li>
            </ul>
        </div>
    </div>
</footer>
<!-- scripts -->
<!-- <script src="<?php echo $_params['ruta_js']?>jquery-3.6.0.js"></script> -->
<script src="<?php echo $_params['ruta_js']?>app.js"></script>
<script type="text/javascript"> var _root_ = '<?php echo $this->_conf['base_url']?>';</script>

<!-- <script>
$(document).ready(function(){
    $('#flexslider').flexslider()
    // Accordeon
    $('.item').on('click', function () {
        var act = $(this).hasClass('active');
        if (!act) {
            $('.item').removeClass('active');
            $('.item').find('.text p').slideUp(100)
            $(this).addClass('active');
            $(this).find('.text p').delay(100).slideDown(300)
        }
    });

    <?php if($this->_item=='home'):?>

    // Forms
    $('#puestoForm').on('change', function(){
        var val = $(this).val();
        if(val == 'Instructor/a'){
            $('#licenciaForm').css('display','block').attr('required', 'required')
        }else{
            $('#licenciaForm').css('display','none').removeAttr('required')
        }
    })
    $('#file-cv').on('change', function(){
        $(this).siblings('span').html($(this).val());
        console.log($(this).val())
    });
    $('#popUps .fa-close').on('click', function(){
        $('#popUps, #popUps form').fadeOut(400)
    })
    $('#formTrabajar').on('click', function(e){
        e.preventDefault()
        $('#popUps').fadeIn(400)
        $('#form-trabaja').css('display','flex')
    })
    $('#formCursos').on('click', function(e){
        e.preventDefault()
        $('#popUps').fadeIn(400)
        $('#form-cursos').css('display','flex')
    })
    $('#formContacto').on('click', function(e){
        e.preventDefault()
        $('#popUps').fadeIn(400)
        $('#form-contacto').css('display','flex')
    })



    $("#form-contacto").submit(function(e){

        e.preventDefault();
        e.returnValue = false;
        
        if ($("#nombre").val() == "") {
            $(".mensaje").html("Debe completar el campo nombre");
            $('#form-contacto input, #form-contacto select, #form-contacto textarea').removeAttr('style');
            $('#nombre').val('').focus().css('border','1px solid #ed5565');         
            return false;        
        }

        if ($("#apellido").val() == "") {
            $(".mensaje").html("Debe completar el campo apellido");
            $('#form-contacto input, #form-contacto select, #form-contacto textarea').removeAttr('style');
            $('#apellido').val('').focus().css('border','1px solid #ed5565');           
            return false;        
        }        
        

        if ($("#email").val() == "") {
            $(".mensaje").html("Debe completar el campo email");
            $('#form-contacto input, #form-contacto select, #form-contacto textarea').removeAttr('style');
            $('#email').val('').focus().css('border','1px solid #ed5565');           
            return false;        
        }

        if($("#email").val().indexOf('@', 0) == -1 || $("#email").val().indexOf('.', 0) == -1) {
            $(".mensaje").html("Debe ingresar un email valido");
            $('#form-contacto input, #form-contacto select, #form-contacto textarea').removeAttr('style');
            $('#email').val('').focus().css('border','1px solid #ed5565');
            return false;
        }   

        if ($("#telefono").val() == "") {
            $(".mensaje").html("Debe completar el campo telefono");
            $('#form-contacto input, #form-contacto select, #form-contacto textarea').removeAttr('style');
            $('#telefono').val('').focus().css('border','1px solid #ed5565');           
            return false;        
        } 

        if ($("#mensaje").val() == "") {
            $(".mensaje").html("Debe completar el campo mensaje");
            $('#form-contacto input, #form-contacto select, #form-contacto textarea').removeAttr('style');
            $('#mensaje').val('').focus().css('border','1px solid #ed5565');         
            return false;        
        }
        
        
        
        $('#enviar_data').prop('disabled', true);
        $(".mensaje").html("");

        $('#form-contacto input, #form-contacto select, #form-contacto textarea').removeAttr('style');
           
        $.ajax({
            type: "POST",
            url: _root_ + "contacto",
            data: $("#form-contacto").serialize(),            
            success: function(data){
                $(".mensaje").html("");
                data = $.trim(data);
                if(data=="ok"){
                    $('#form-contacto input:text, #form-contacto input:number, #form-contacto textarea').val('');
                    $(".mensaje").append('Envio exitoso!');
                    $('#enviar_data').prop('disabled', false);
                    // $('#enviar_data').attr('value', 'Enviado!');
                    //window.location = _root_+'contenidos/contacto/respuesta';

                    setTimeout(function() {
                        $('#popUps, #popUps form').fadeOut(400);
                        $(".mensaje").html("");
                    }, 2000);                    

                }else{
                    $('#enviar_data').prop('disabled', false);
                    $(".mensaje").append(data);
                }
            },
            error: function (err){
                console.log("Error");
                $(".mensaje").append(data);
            }
        });
        return false;
    });
    
    $("#form-cursos").submit(function(e){

        e.preventDefault();
        e.returnValue = false;
        
        if ($("#nombre_cursos").val() == "") {
            $(".mensaje_cursos").html("Debe completar el campo nombre");
            $('#form-cursos input, #form-cursos select, #form-cursos textarea').removeAttr('style');
            $('#nombre_cursos').val('').focus().css('border','1px solid #ed5565');         
            return false;        
        }

        if ($("#apellido_cursos").val() == "") {
            $(".mensaje_cursos").html("Debe completar el campo apellido");
            $('#form-cursos input, #form-cursos select, #form-cursos textarea').removeAttr('style');
            $('#apellido_cursos').val('').focus().css('border','1px solid #ed5565');           
            return false;        
        }        
        

        if ($("#email_cursos").val() == "") {
            $(".mensaje_cursos").html("Debe completar el campo email");
            $('#form-cursos input, #form-cursos select, #form-cursos textarea').removeAttr('style');
            $('#email_cursos').val('').focus().css('border','1px solid #ed5565');           
            return false;        
        }

        if($("#email_cursos").val().indexOf('@', 0) == -1 || $("#email_cursos").val().indexOf('.', 0) == -1) {
            $(".mensaje_cursos").html("Debe ingresar un email valido");
            $('#form-cursos input, #form-cursos select, #form-cursos textarea').removeAttr('style');
            $('#email_cursos').val('').focus().css('border','1px solid #ed5565');
            return false;
        }   

        if ($("#telefono_cursos").val() == "") {
            $(".mensaje_cursos").html("Debe completar el campo telefono");
            $('#form-cursos input, #form-cursos select, #form-cursos textarea').removeAttr('style');
            $('#telefono_cursos').val('').focus().css('border','1px solid #ed5565');           
            return false;        
        } 

        if ($("#mensaje_cursos").val() == "") {
            $(".mensaje_cursos").html("Debe completar el campo mensaje");
            $('#form-cursos input, #form-cursos select, #form-cursos textarea').removeAttr('style');
            $('#mensaje_cursos').val('').focus().css('border','1px solid #ed5565');         
            return false;        
        }
        
        
        
        $('#enviar_data_cursos').prop('disabled', true);
        $(".mensaje_cursos").html("");

        $('#form-cursos input, #form-cursos select, #form-cursos textarea').removeAttr('style');
           
        $.ajax({
            type: "POST",
            url: _root_ + "contacto/cursos",
            data: $("#form-cursos").serialize(),            
            success: function(data){
                $(".mensaje_cursos").html("");
                data = $.trim(data);
                if(data=="ok"){
                    $('#form-cursos input:text, #form-cursos input:number, #form-cursos textarea').val('');
                    $(".mensaje_cursos").append('Envio exitoso!');
                    $('#enviar_data_cursos').prop('disabled', false);
                    // $('#enviar_data').attr('value', 'Enviado!');
                    //window.location = _root_+'contenidos/contacto/respuesta';

                    setTimeout(function() {
                        $('#popUps, #popUps form').fadeOut(400);
                        $(".mensaje_cursos").html("");
                    }, 2000);                    

                }else{
                    $('#enviar_data_cursos').prop('disabled', false);
                    $(".mensaje_cursos").append(data);
                }
            },
            error: function (err){
                console.log("Error");
                $(".mensaje_cursos").append(data);
            }
        });
        return false;
    });


    $("#form-trabaja").submit(function(e){

        e.preventDefault();
        e.returnValue = false;
        
        if ($("#nombre_trabaja").val() == "") {
            $(".mensaje_trabaja").html("Debe completar el campo nombre");
            $('#form-trabaja input, #form-trabaja select, #form-trabaja textarea').removeAttr('style');
            $('#nombre_trabaja').val('').focus().css('border','1px solid #ed5565');         
            return false;        
        }

        if ($("#apellido_trabaja").val() == "") {
            $(".mensaje_trabaja").html("Debe completar el campo apellido");
            $('#form-trabaja input, #form-trabaja select, #form-trabaja textarea').removeAttr('style');
            $('#apellido_trabaja').val('').focus().css('border','1px solid #ed5565');           
            return false;        
        }        
        

        if ($("#email_trabaja").val() == "") {
            $(".mensaje_trabaja").html("Debe completar el campo email");
            $('#form-trabaja input, #form-trabaja select, #form-trabaja textarea').removeAttr('style');
            $('#email_trabaja').val('').focus().css('border','1px solid #ed5565');           
            return false;        
        }

        if($("#email_trabaja").val().indexOf('@', 0) == -1 || $("#email_trabaja").val().indexOf('.', 0) == -1) {
            $(".mensaje_trabaja").html("Debe ingresar un email valido");
            $('#form-trabaja input, #form-trabaja select, #form-trabaja textarea').removeAttr('style');
            $('#email_trabaja').val('').focus().css('border','1px solid #ed5565');
            return false;
        }   

        if ($("#telefono_trabaja").val() == "") {
            $(".mensaje_trabaja").html("Debe completar el campo telefono");
            $('#form-trabaja input, #form-trabaja select, #form-trabaja textarea').removeAttr('style');
            $('#telefono_trabaja').val('').focus().css('border','1px solid #ed5565');           
            return false;        
        } 

        if ($("#puestoForm").val() == "") {
            $(".mensaje_trabaja").html("Debe completar el campo postulate a");
            $('#form-trabaja input, #form-trabaja select, #form-trabaja textarea').removeAttr('style');
            $('#puestoForm').val('').focus().css('border','1px solid #ed5565');           
            return false;        
        } 

        if ($("#puestoForm").val() == "Instructor/a") {

            if ($("#licenciaForm").val() == "") {
                $(".mensaje_trabaja").html("Debe completar el campo licencia");
                $('#form-trabaja input, #form-trabaja select, #form-trabaja textarea').removeAttr('style');
                $('#licenciaForm').val('').focus().css('border','1px solid #ed5565');           
                return false;        
            } 
        }


        var file= $("#file-cv").val();
        // var reg = /(.*?)\.(jpg|jpeg|png|doc|docx|odt|rtf|pdf|xls|xlsx)$/;
        var reg = /(.*?)\.(doc|docx|pdf)$/;
        if(!file.match(reg)){
           // alert("Tipo de archivo no permitido");
            $(".mensaje_trabaja").html("Tipo de archivo no permitido");
            $('#form-trabaja input, #form-trabaja select, #form-trabaja textarea').removeAttr('style');
            $('#mensaje_trabaja').val('').focus().css('border','1px solid #ed5565'); 
           return false;
        }

        if ($("#file-cv").val() == "") {
            $(".mensaje_trabaja").html("Debe completar el campo carga CV");
            $('#form-trabaja input, #form-trabaja select, #form-trabaja textarea').removeAttr('style');
            $('#mensaje_trabaja').val('').focus().css('border','1px solid #ed5565');           
            return false;        
        }

        if ($("#mensaje_trabaja").val() == "") {
            $(".mensaje_trabaja").html("Debe completar el campo mensaje");
            $('#form-trabaja input, #form-trabaja select, #form-trabaja textarea').removeAttr('style');
            $('#mensaje_trabaja').val('').focus().css('border','1px solid #ed5565');         
            return false;        
        }
        
        
        
        $('#enviar_data_trabaja').prop('disabled', true);
        $(".mensaje_trabaja").html("");

        $('#form-trabaja input, #form-trabaja select, #form-trabaja textarea').removeAttr('style');

        var form = $("#form-trabaja");
           
        $.ajax({
            type: "POST",
            url: _root_ + "contacto/trabajo",
            // data: $("#form-trabaja").serialize(),
            data: new FormData(form[0]),
            cache: false,
            contentType: false,
            processData: false,            
            success: function(data){
                $(".mensaje_trabaja").html("");
                data = $.trim(data);
                if(data=="ok"){
                    $('#form-trabaja input:text, #form-trabaja input:number, #form-trabaja select, #form-trabaja textarea').val('');
                    $('#popUps .contentForms .file span').html('');
                    // $('#puestoForm').val('');
                    $(".mensaje_trabaja").append('Envio exitoso!');
                    $('#enviar_data_trabaja').prop('disabled', false);

                    setTimeout(function() {
                        $('#popUps, #popUps form').fadeOut(400);
                        $(".mensaje_trabaja").html("");
                    }, 2000);                    

                }else{
                    $('#enviar_data_trabaja').prop('disabled', false);
                    $(".mensaje_trabaja").append(data);
                }
            },
            error: function (err){
                console.log("Error");
                $(".mensaje_trabaja").append(data);
            }
        });
        return false;
    });

    <?php endif?>

});
</script> -->
</body>
</html> 
