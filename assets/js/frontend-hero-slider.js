;(function ($, w) {
    'use strict';
    
var $window = $(w);

$window.on( 'elementor/frontend/init', function() {
    
    var EF = elementorFrontend,
        EM = elementorModules;
    
    var ModuleBase = elementorModules.frontend.handlers.Base;

    var Hero_Slider = EM.frontend.handlers.Base.extend({
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
            var swiper = new Swiper(".ua-hero", {
                //slidesPerView: $settings.slidesPerView!='auto' ? parseInt($settings.slidesPerView) : 'auto',
               //spaceBetween: $settings.spaceBetween,
                effect: $settings.effect,
                speed: $settings.speed,
                loop:$settings.loop=='yes' ? true : false,
                rewind:true,
                autoplay: {
                    delay: $settings.delay,
                  },

                pagination: {
                  el: ".swiper-pagination",
                  type:$settings.pagination_type,
                  clickable: true,
                },

                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
              });
        }
    });

    /**
     * Hero Slider Finalized Here
     * 
     * Actually most of the part of this Widget has done by B M Rafiul Alam but
     * JS part had developed by(Me) Saiful Islam
     * 
     * @author Saiful Islam <codersaiful@gmail.com>
     * @author B M Rafiul Alam <bmrafiul.alam@gmail.com>
     * @since 1.1.0.8
     */

    // Moving_Letters Hooked Here
    EF.hooks.addAction(
        'frontend/element_ready/ultraaddons-hero-slider.default',
        function ($scope) {
                EF.elementsHandler.addHandler(Hero_Slider, {
                        $element: $scope,
                });
        }
    );
           

});

} (jQuery, window));