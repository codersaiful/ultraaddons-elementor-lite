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
	
	
		$('.pf-filter-btn > li').on('click', function(e){
			e.preventDefault();
			
	
		/* var $data = $(this).data('cat'),
			 $count = $(this).data('count');	
			 
			 $.ajax({
				type: 'POST',
				url: '/projects/wp-admin/admin-ajax.php',
				dataType: 'html',
				data: {
				  action: 'my_category',
				  category:  $data,
				  count:$count,
				},
				success: function(res) {
				  //console.log(res);
				  $('.projects').html(res);
				}
			  }); */

			/**isotope js*/
				var $projects = $('.projects');

				$projects.isotope({
					itemSelector: '.items',
					layoutMode: 'fitRows'
				});

			var filter = $(this).attr('data-filter');

			$('.pf-filter-btn > li').removeClass('active');
			$(this).addClass('active');

			$projects.isotope({filter: filter});

		});
	}
);


});// Init wrapup

} (jQuery, window));