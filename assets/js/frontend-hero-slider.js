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
            console.log($settings);

                if($settings ){
                    var $swiper = new Swiper('.ua-hero', {
                        //parameters
                        //loop: ($settings.loop =='yes') ? true : false,
                        speed: $settings.speed,
                        effect: $settings.effect,
                        direction: $settings.direction,
                        slidesPerView: parseInt($settings.slidesPerView),
                        parallax: ($settings.parallax =='yes') ? true : false,
                        freeMode: true,

                        autoplay: {
                            delay: $settings.delay,
                          },

                          navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },

                        pagination: {
                            el: '.swiper-pagination',
                            type: $settings.pagination_type,
                            clickable:true,
                          },
                      });
                      
                if($settings.stopOnHover=='yes'){
                    $(".ua-hero").mouseenter(function() {
                        $swiper.autoplay.stop();
                    });

                    $(".ua-hero").mouseleave(function() {
                        $swiper.autoplay.start();
                    });
                }
            }
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