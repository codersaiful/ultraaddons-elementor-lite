;(function ($, w) {
	'use strict';
	function advPriceTable(){
    var e = $(".filt-monthly"),
				d = $(".filt-hourly"),
				t = $(".switcher"),
				m = $(".monthly"),
				y = $(".hourly");
			
			if (e) {
				e.click(function () {
					t.checked = false;
					e.addClass("toggler--is-active");
					d.removeClass("toggler--is-active");
					m.removeClass("hide");
					y.addClass("hide");
				});
			}
			if (d) {
				d.click(function ()  {
					t.checked = true;
					d.addClass("toggler--is-active");
					e.removeClass("toggler--is-active");
					m.addClass("hide");
					y.removeClass("hide");
				});
			}
			if (t) {
				t.click(function ()  {
					d.toggleClass("toggler--is-active");
					e.toggleClass("toggler--is-active");
					m.toggleClass("hide");
					y.toggleClass("hide");
				})
		}
	}
var $window = $(w);

$window.on( 'elementor/frontend/init', function() {
    
    var EF = elementorFrontend,
        EM = elementorModules;
    
    var ModuleBase = elementorModules.frontend.handlers.Base;

    var pricing_table = EM.frontend.handlers.Base.extend({
        onInit: function(){
            this.run();
        },
        onChange: function(){
            this.run();
        },
        run: function(){

            var $scope = this.$element;
            /**
             * get data on editor mode
             */
			var $settings = this.getElementSettings();
			
			advPriceTable();
	}

	//advPricingTable();
    });

    /**
     * Ad
     
     * @author B M Rafiul Alam <bmrafiul.alam@gmail.com>
     * @since 1.1.0.8
     */

    // Moving_Letters Hooked Here
    EF.hooks.addAction(
        'frontend/element_ready/ultraaddons-advance-pricing-table.default',
        function ($scope) {
                EF.elementsHandler.addHandler(pricing_table, {
                        $element: $scope,
                });
        }
    );
});
advPriceTable();
} (jQuery, window));