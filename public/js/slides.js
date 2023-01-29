$(function(){

	$('#slides').slides({

		preload: true,

		play: 3000,

		generateNextPrev: true

	});

});

$('#slideSecond').carouFredSel({
		auto: false,
		prev: '#prev',
		next: '#next',
		pagination: false,
		mousewheel: true,
		swipe: {
			onMouse: true,
			onTouch: true
		}
});


$(function() {

	

	$('#slideVisitados').carouFredSel({

		auto: false,

		prev: '#prev2',

		next: '#next2',

		pagination: false,

		mousewheel: true,

		swipe: {

			onMouse: true,

			onTouch: true

		}

	});



});