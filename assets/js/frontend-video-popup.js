;(function ($, w) {
    'use strict';
    
var $window = $(w);

$window.on( 'elementor/frontend/init', function() {
    
    var EF = elementorFrontend,
        EM = elementorModules;
    
    var ModuleBase = elementorModules.frontend.handlers.Base;

    var Video_Popup = EM.frontend.handlers.Base.extend({
        onInit: function(){
            this.run();
        },
        onChange: function(){
            this.run();
        },
        run: function(){

            var $scope = this.$element;
             var $id        = $scope[0].dataset.id;
            /**
             * get data on editor mode
             */
            var $settings   = this.getElementSettings();
            console.log($settings);

            if($settings.video_type=='youtube'){

                var autoplay    = ($settings.autoplay==1)  ? 1 : 0;
                var controls    = ($settings.controls==1)  ? 1 : 0;
                var loop        = ($settings.loop==0)      ? 0 : 1;

                new ModalVideo('.js-modal-btn-'+$id, {
                    youtube:{
                        autoplay: autoplay,
                        controls: controls,
                        loop: loop, 
                    },
                });
            }
            else if($settings.video_type=='vimeo'){

                    var autoplay    = ($settings.vautoplay==1) ? true : false;
                   // var controls    = ($settings.vcontrols==1) ? true : false;
                    var loop        = ($settings.vloop==1)     ? true : false;
                    
                    new ModalVideo('.js-modal-btn-'+$id,{
                        channel:'vimeo',
                        vimeo:{
                        autoplay:autoplay,
                        //controls:controls,
                        loop:loop
                    },
                });
            }

        }
    });

    /**
     * Video Popup Finalized Here
     *
     * @author Saiful Islam <codersaiful@gmail.com>
     * @author B M Rafiul Alam <bmrafiul.alam@gmail.com>
     * @since 1.1.0.12
     */

    // video-popup Hooked Here
    EF.hooks.addAction(
        'frontend/element_ready/ultraaddons-video-popup.default',
        function ($scope) {
                EF.elementsHandler.addHandler(Video_Popup, {
                        $element: $scope,
                });
        }
    );
});

} (jQuery, window));