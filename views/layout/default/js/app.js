//--------------- efecto menu ---------------//
let header = $('header')
function headerHeight (){
    header.toggleClass('size75', scrollY > 200)
    $('.menu__btn').toggleClass('topSize', scrollY > 200)
}

window.addEventListener('scroll', headerHeight)

//--------------- menu despleagable ---------------//
function menuToggle (){
    let menuFoot = $('.burguer-menu');
    let btnMenu = $('.menu__btn');  
    
    $('.menu__btn span:first-child').toggleClass('spanTop')
    $('.menu__btn span:nth-child(2)').toggleClass('spanMid')
    $('.menu__btn span:last-child').toggleClass('spanBot')

    menuFoot.toggle();
}
$('.menu__btn').on('click', menuToggle)

//---------------Scroll para boton "#subir"---------------//
scrollTopButton('.subir')

function scrollTopButton (btn){
    const $scrollBtn = $(btn);
    //console.log($ScrollBtn)

    $(window).scroll(function(){//llamo windows con jQuery
        let scrollTop = $(this).scrollTop() //metodo de jQuery
        //console.log(scrollTop)
        scrollTop > 400 ? $scrollBtn.removeClass('hidden') : $scrollBtn.addClass('hidden')//esto es como un if else  (?= if)/ (:= else)
    })
    
    // Volver al top
    $scrollBtn.click(function(){
        let btnClass = btn.substr(1) 

        if($(this).hasClass(btnClass)){
            window.scrollTo({//llamo windos con jS
                behavior: 'smooth',
                top: 0
            })
        }
    })
}

//------------------- FAQs -------------------//

function desplegarFAQ0(){

    $('#plus0').toggle()
    $('#less0').toggle()
    $('#answ0').toggle()

    $('#answ1').hide()
    $('#answ2').hide()
    $('#answ3').hide()
    $('#answ4').hide()
    $('#answ5').hide()
    $('#answ6').hide()
    $('#answ7').hide()
    $('#answ8').hide()
    
    $('#less1').hide()
    $('#less2').hide()
    $('#less3').hide()
    $('#less4').hide()
    $('#less5').hide()
    $('#less6').hide()
    $('#less7').hide()
    $('#less8').hide()

    $('#plus1').show()
    $('#plus2').show()
    $('#plus3').show()
    $('#plus4').show()
    $('#plus5').show()
    $('#plus6').show()
    $('#plus7').show()
    $('#plus8').show() 
} 
$('#quest0').on('click', desplegarFAQ0)

function desplegarFAQ1(){

    $('#plus1').toggle()
    $('#less1').toggle()
    $('#answ1').toggle()

    $('#answ0').hide()
    $('#answ2').hide()
    $('#answ3').hide()
    $('#answ4').hide()
    $('#answ5').hide()
    $('#answ6').hide()
    $('#answ7').hide()
    $('#answ8').hide()
    
    $('#less0').hide()
    $('#less2').hide()
    $('#less3').hide()
    $('#less4').hide()
    $('#less5').hide()
    $('#less6').hide()
    $('#less7').hide()
    $('#less8').hide()

    $('#plus0').show()
    $('#plus2').show()
    $('#plus3').show()
    $('#plus4').show()
    $('#plus5').show()
    $('#plus6').show()
    $('#plus7').show()
    $('#plus8').show()
} 
$('#quest1').on('click', desplegarFAQ1)

function desplegarFAQ2(){

    $('#plus2').toggle()
    $('#less2').toggle()
    $('#answ2').toggle()

    $('#answ0').hide()
    $('#answ1').hide()
    $('#answ3').hide()
    $('#answ4').hide()
    $('#answ5').hide()
    $('#answ6').hide()
    $('#answ7').hide()
    $('#answ8').hide()
    
    $('#less0').hide()
    $('#less1').hide()
    $('#less3').hide()
    $('#less4').hide()
    $('#less5').hide()
    $('#less6').hide()
    $('#less7').hide()
    $('#less8').hide()

    $('#plus0').show()
    $('#plus1').show()
    $('#plus3').show()
    $('#plus4').show()
    $('#plus5').show()
    $('#plus6').show()
    $('#plus7').show()
    $('#plus8').show()
} 
$('#quest2').on('click', desplegarFAQ2)

function desplegarFAQ3(){

    $('#plus3').toggle()
    $('#less3').toggle()
    $('#answ3').toggle()

    $('#answ0').hide()
    $('#answ1').hide()
    $('#answ2').hide()
    $('#answ4').hide()
    $('#answ5').hide()
    $('#answ6').hide()
    $('#answ7').hide()
    $('#answ8').hide()
    
    $('#less0').hide()
    $('#less1').hide()
    $('#less2').hide()
    $('#less4').hide()
    $('#less5').hide()
    $('#less6').hide()
    $('#less7').hide()
    $('#less8').hide()

    $('#plus0').show()
    $('#plus1').show()
    $('#plus2').show()
    $('#plus4').show()
    $('#plus5').show()
    $('#plus6').show()
    $('#plus7').show()
    $('#plus8').show()
} 
$('#quest3').on('click', desplegarFAQ3)

function desplegarFAQ4(){

    $('#plus4').toggle()
    $('#less4').toggle()
    $('#answ4').toggle()

    $('#answ0').hide()
    $('#answ1').hide()
    $('#answ2').hide()
    $('#answ3').hide()
    $('#answ5').hide()
    $('#answ6').hide()
    $('#answ7').hide()
    $('#answ8').hide()
    
    $('#less0').hide()
    $('#less1').hide()
    $('#less2').hide()
    $('#less3').hide()
    $('#less5').hide()
    $('#less6').hide()
    $('#less7').hide()
    $('#less8').hide()

    $('#plus0').show()
    $('#plus1').show()
    $('#plus2').show()
    $('#plus3').show()
    $('#plus5').show()
    $('#plus6').show()
    $('#plus7').show()
    $('#plus8').show()
} 
$('#quest4').on('click', desplegarFAQ4)

function desplegarFAQ5(){

    $('#plus5').toggle()
    $('#less5').toggle()
    $('#answ5').toggle()

    $('#answ0').hide()
    $('#answ1').hide()
    $('#answ2').hide()
    $('#answ3').hide()
    $('#answ4').hide()
    $('#answ6').hide()
    $('#answ7').hide()
    $('#answ8').hide()
    
    $('#less0').hide()
    $('#less1').hide()
    $('#less2').hide()
    $('#less3').hide()
    $('#less4').hide()
    $('#less6').hide()
    $('#less7').hide()
    $('#less8').hide()

    $('#plus0').show()
    $('#plus1').show()
    $('#plus2').show()
    $('#plus3').show()
    $('#plus4').show()
    $('#plus6').show()
    $('#plus7').show()
    $('#plus8').show()
} 
$('#quest5').on('click', desplegarFAQ5)

function desplegarFAQ6(){

    $('#plus6').toggle()
    $('#less6').toggle()
    $('#answ6').toggle()

    $('#answ0').hide()
    $('#answ1').hide()
    $('#answ2').hide()
    $('#answ3').hide()
    $('#answ4').hide()
    $('#answ5').hide()
    $('#answ7').hide()
    $('#answ8').hide()
    
    $('#less0').hide()
    $('#less1').hide()
    $('#less2').hide()
    $('#less3').hide()
    $('#less4').hide()
    $('#less5').hide()
    $('#less7').hide()
    $('#less8').hide()

    $('#plus0').show()
    $('#plus1').show()
    $('#plus2').show()
    $('#plus3').show()
    $('#plus4').show()
    $('#plus5').show()
    $('#plus7').show()
    $('#plus8').show()
} 
$('#quest6').on('click', desplegarFAQ6)

function desplegarFAQ7(){

    $('#plus7').toggle()
    $('#less7').toggle()
    $('#answ7').toggle()

    $('#answ0').hide()
    $('#answ1').hide()
    $('#answ2').hide()
    $('#answ3').hide()
    $('#answ4').hide()
    $('#answ5').hide()
    $('#answ6').hide()
    $('#answ8').hide()
    
    $('#less0').hide()
    $('#less1').hide()
    $('#less2').hide()
    $('#less3').hide()
    $('#less4').hide()
    $('#less5').hide()
    $('#less6').hide()
    $('#less8').hide()

    $('#plus0').show()
    $('#plus1').show()
    $('#plus2').show()
    $('#plus3').show()
    $('#plus4').show()
    $('#plus5').show()
    $('#plus6').show()
    $('#plus8').show()
} 
$('#quest7').on('click', desplegarFAQ7)

function desplegarFAQ8(){

    $('#plus8').toggle()
    $('#less8').toggle()
    $('#answ8').toggle()

    $('#answ0').hide()
    $('#answ1').hide()
    $('#answ2').hide()
    $('#answ3').hide()
    $('#answ4').hide()
    $('#answ5').hide()
    $('#answ6').hide()
    $('#answ7').hide()
    
    $('#less0').hide()
    $('#less1').hide()
    $('#less2').hide()
    $('#less3').hide()
    $('#less4').hide()
    $('#less5').hide()
    $('#less6').hide()
    $('#less7').hide()

    $('#plus0').show()
    $('#plus1').show()
    $('#plus2').show()
    $('#plus3').show()
    $('#plus4').show()
    $('#plus5').show()
    $('#plus6').show()
    $('#plus7').show()
} 
$('#quest8').on('click', desplegarFAQ8)


//--------------------------------------------------------//