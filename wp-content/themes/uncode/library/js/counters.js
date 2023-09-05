(function($) {
	"use strict";

	UNCODE.counters = function() {
	var $counters = $('.uncode-counter:not(.counter-init)');
	$counters.each(function(){
		var $counter = $(this).addClass('counter-init');
		if ( $counter.closest( '.owl-carousel' ).length ) {
			return;
		}
		$counter.addClass('started').counterUp({
			delay: 10,
			time: 1500
		});
	});
};


})(jQuery);
