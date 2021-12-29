jQuery(document).ready(function($) {
	'use strict';

	/***************** PREVENT DEFAULT CLICK ON # ******************/
	$('[href="#"]').click(function(e){
		e.preventDefault();
	});


	/***************** SMOOTH SCROLLING ******************/
	$('a[href*=#]:not([href=#]):not([data-toggle="tab"])').click(function() {
		if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
			var target = $(this.hash);
			target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
			if (target.length) {
				var $padding = parseInt($(this).attr('data-padding'), 10);
				$padding = (($padding >= 1) ? $padding : 0);
				$('html,body').animate({
					scrollTop: target.offset().top - $padding
				}, 1000);
				return false;
			}
		}
	});

	/***************** SLIDES ******************/
	if ($('#highlight-slide').length){
		var owl_highlight = $('#highlight-slide');
		owl_highlight.owlCarousel({
			autoPlay: 8000,
			navigation : false,
			singleItem:true
		});
	}

	if ($('#calendar-slide').length){
		var owl_calendar = $('#calendar-slide');
		owl_calendar.owlCarousel({
			autoPlay: 3000,
			navigation : false,
			items : 5,
			itemsDesktop : [1000,4],
			itemsDesktopSmall : [900,3],
			itemsTablet: [600,2],
			itemsMobile : false
		});
	}

	if ($('#testimonial-slide').length){
		var owl_testimonial = $('#testimonial-slide');
		owl_testimonial.owlCarousel({
			autoPlay: 8000,
			navigation : false,
			singleItem: true,
			autoHeight : false
		});
	}

	/***************** VIDEO BOX ******************/
	if ($('.modal').length){
		$('.modal').venobox();
	}

	/***************** TESTIMONIAL MODAL ******************/
	$('a[id^=btn-]').click(function() {
		var $atrr = jQuery(this).attr('href');

		$('body').css({'overflow': 'hidden'});
		$($atrr).addClass('proposals-modal-open');
	});

	$('.icon-close').click(function() {
		jQuery(this).parent().removeClass('proposals-modal-open');
		$('body').css({'overflow': 'auto'});
	});
});
