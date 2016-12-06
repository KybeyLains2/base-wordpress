(function($){

	function enterSlideEffect( $elem ){
		$elem.find('.animated').each(function(index, el) {
			var $this = $(el),
				effect = $this.data('effect');

			$this.addClass(effect);
		});
	}

	function leaveSlideEffect( $elem ){
		$elem.find('.animated').each(function(index, el) {
			var $this = $(el),
				effect = $this.data('effect');

			$this.removeClass(effect);
		});
	}

	$(window).load(function(){
		var $sameHeightSizes = new Array(),
			$sameHeightContainer = $('.same-height');

		if ( $sameHeightContainer ) {
			$sameHeightContainer.each(function(index, el) {
				var $container = $(this);

				if ( $container.data( 'target' ) ) {
					var $sameHeightTarget = $container.data( 'target' ),
						$sameHeightInside = $container.data( 'inside' );

					$($sameHeightTarget).each(function(index, el) {
						$sameHeightSizes.push($(this).height());
					});
					$($sameHeightInside).css( 'min-height', Math.max.apply( Math, $sameHeightSizes ) + 'px' );
				}
			});
		}
	});

	$('.fixed').affix({
		offset: {
			top: function () {
				return ( this.top = $('.header').offset().top + 10 );
			},
			bottom: function () {
				var limit = $(document).outerHeight(true) - $('.footer').offset().top;
				return ( this.bottom = limit + 150 )
			}
		}
	});

	$(".owl-carousel").owlCarousel();

	$('.js-scroll').jscroll({
		loadingHtml: 	$('.js-infinite-load').html(),
		nextSelector: 	'.infinite-pagination-link'
	});

})(jQuery);
