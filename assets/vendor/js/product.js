;(function ($, w) {
    'use strict';
    
    var $window = $(w);

    $.fn.ultraDataAttr = function( dataAttr ) {
            return this.data( dataAttr );
    };

    
    $window.on( 'elementor/frontend/init', function() {
        
            var EF = elementorFrontend;
            var ModuleBase = elementorModules.frontend.handlers.Base;
//Product Filter
EF.hooks.addAction(
	'frontend/element_ready/ultraaddons-product-tabs.default',
	function ($scope) {
	
	/**isotope js*/
		var $projects = $('.projects');

		$projects.isotope({
			itemSelector: '.items',
			layoutMode: 'fitRows'
		});

		$('.pf-filter-btn > li').on('click', function(e){
			e.preventDefault();
			var $data = $(this).data('cat');
			console.log($data );
		/* 	$.ajax({
				type: "post",
				dataType: "json",
				url: ULTRAADDONS_DATA.ajax_url,
				data: {
					action:$data
				},
				success: function(data){
					console.log(data);
				}
			}); */
			var filter = $(this).attr('data-filter');

			$('.pf-filter-btn > li').removeClass('active');
			$(this).addClass('active');

			$projects.isotope({filter: filter});

		});

	  
	}
);


});// Init wrapup

} (jQuery, window));