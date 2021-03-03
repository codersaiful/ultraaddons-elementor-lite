;(function ($, w) {
    'use strict';
    
    var $window = $(w);

    $.fn.ultraDataAttr = function( dataAttr ) {
            return this.data( dataAttr );
    };

    /**
     * For Deleveloper Only
     */
    console.log(ULTRAADDONS_DATA);
    
    $window.on( 'elementor/frontend/init', function() {
        
            var cx_settings;
            var EF = elementorFrontend,
                EM = elementorModules;
            
            /**
             * Default Slider is Carousel Slider for UltraAddons.
             * 
             * In future, we will use other slider. But for now
             * we have used only Carousel Slider.
             * 
             * @type Object of Slider
             */
            var SliderBase = EM.frontend.handlers.Base.extend({
                    onInit: function () {
                            EM.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
                            this.run();
                    },

                    getDefaultSettings: function() {
                            return {
                                    selectors: {
                                            container: '.ua-slider-wrapper'
                                    },
                                    navText: ['<i class="fa fa-chevron-left"></i>','<i class="fa fa-chevron-right"></i>'],

                            }
                    },

                    getDefaultElements: function () {
                            var selectors = this.getSettings('selectors');
                            return {
                                    $container: this.findElement(selectors.container)
                            };
                    },

                    onElementChange: function() {
                            this.elements.$container.owlCarousel('refresh');
                            this.run();
                    },

                    getReadySettings: function() {
                            var settings = {
                                    autoplay: !! this.getElementSettings('autoplay'),
                                    autoplayHoverPause: !! this.getElementSettings('pause_on_hover'),
                                    autoplaySpeed: this.getElementSettings('autoplay_speed'),
                                    loop: !! this.getElementSettings('loop'),
                                    autoplayTimeout: this.getElementSettings('autoplayTimeout'),
                                    nav: false,
                                    margin: 20,
                                    
                            };

                            switch (this.getElementSettings('navigation')) {
                                    case 'arrow':
                                            settings.nav = true;
                                            settings.dots = false;
                                            break;
                                    case 'dots':
                                            settings.dots = true;
                                            settings.nav = false;
                                            break;
                                    case 'both':
                                            settings.nav = true;
                                            settings.dots = true;
                                            break;
                                    default:
                                            settings.nav = false;
                                            settings.dots = false;
                                            break;
                            }
                            
                            settings.items = this.getElementSettings('slides_to_show') || 1;
                            settings.responsive={};
                            settings.responsive[EF.config.breakpoints.xs] = {
                                    items: (this.getElementSettings('slides_to_show_mobile') || this.getElementSettings('slides_to_show_tablet')) || settings.items,
                                    nav:true
                                };
                            
                            settings.responsive[EF.config.breakpoints.md] = {
                                    items: (this.getElementSettings('slides_to_show_tablet') || settings.items),
                                    nav:true
                                };
                            
                            settings.responsive[EF.config.breakpoints.lg] = {
                                    items: settings.items
                                };

                            return $.extend({}, this.getDefaultSettings(), settings);
                    },

                    run: function() {
                        this.elements.$container.owlCarousel(this.getReadySettings());
                    }
            });

            // Slider
            EF.hooks.addAction(
                    'frontend/element_ready/ultraaddons-slider.default',
                    function ($scope) {
                            EF.elementsHandler.addHandler(SliderBase, {
                                    $element: $scope,
                                    selectors: {
                                            container: '.ua-slider-wrapper',
                                    },
                                    autoplay: true,
                                    
                            });
                    }
            );
            
            EF.hooks.addAction( 'frontend/element_ready/ultraaddons-slider.default', add_number_inside_bullets);
           
           
            // Cart Update in Editor Screen
            EF.hooks.addAction(
                    'frontend/element_ready/ultraaddons-cart.default',
                    function ($scope) {
                        trigger_cart_update();
                    }
            );
            
            // Cart Update in Editor Screen
            EF.hooks.addAction(
                    'frontend/element_ready/ultraaddons-product-table.default',
                    function ($scope) {
                        $('.wpt_product_table_wrapper .search_select,select.filter_select').select2();
                        trigger_cart_update();
                        minicart_footer_load();
                    }
            );
            
           /*
           
                var fnHanlders = {
			'ha-image-compare.default'      : HandleImageCompare,
			'ha-number.default'             : NumberHandler,
			'ha-skills.default'             : SkillHandler,
			'ha-fun-factor.default'         : FunFactor,
			'ha-bar-chart.default'          : BarChart,
			'ha-twitter-feed.default'       : TwitterFeed,
			'ha-threesixty-rotation.default': Threesixty_Rotation,
			'ha-data-table.default'         : DataTable,
			'widget'                        : BackgroundOverlay,
		};

		$.each( fnHanlders, function( widgetName, handlerFn ) {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/' + widgetName, handlerFn );
		});

		var classHandlers = {
			'ha-image-grid.default'       : ImageGrid,
			'ha-justified-gallery.default': JustifiedGrid,
			'ha-news-ticker.default'      : NewsTicker,
			'ha-post-tab.default'         : PostTab
		};

		$.each( classHandlers, function( widgetName, handlerClass ) {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/' + widgetName, function( $scope ) {
				elementorFrontend.elementsHandler.addHandler( handlerClass, { $element: $scope });
			});
		});
           
           */
           
           // Wrapper Link
           $('.ua-wrapper-link').each(function() {
                    var link = $(this).data('element_link');
                    $(this).on('click', function() {
                        if (link.is_external) {
                                window.open(link.url);
                        } else {
                                location.href = link.url;
                        }
                    });
            });
    });
    
    /**
     * Created Outside of init/Elementtor
     * Imean: elementor/frontend/init
     * 
     * Because, If need this functionality, so that we can use this function any where.
     * 
     * @returns {undefined}
     */
    function add_number_inside_bullets(){
        var dots = document.querySelectorAll(".ua-number-slider-wrapper .owl-dots .owl-dot");
        
        let i=1;
        dots.forEach((elem)=>{
            var text = i;
            if(i < 10){
                text = "0" + i;
            }
            elem.innerHTML = text;
            i++;
        });
    }
    
    function trigger_cart_update(){
        $( document.body ).trigger( 'updated_cart_totals' );
        $( document.body ).trigger( 'wc_fragments_refreshed' );
        $( document.body ).trigger( 'wc_fragments_refreshed' );
        $( document.body ).trigger( 'wc_fragments_refresh' );
        $( document.body ).trigger( 'wc_fragment_refresh' );
        $( document.body ).trigger( 'removed_from_cart' );
//        $( document.body ).trigger( 'wpt_minicart_load' );
    }
    function minicart_footer_load(){
        var footer_cart = 'always_show';
        var footer_cart_size = '74'; 
        var footer_possition = 'bottom_right'; 


        $('body').append("<div class='wpt_notice_board'></div>");
        $('body').append('<div style="height: ' + footer_cart_size + 'px;width: ' + footer_cart_size + 'px;" class="wpt-footer-cart-wrapper '+ footer_possition +' '+ footer_cart +'"><a target="_blank" href="#"></a></div>');

        //$(window).trigger('wpt_minicart_now');

        var minicart_type = $('div.tables_cart_message_box').attr('data-type');

            $.ajax({
                type: 'POST',
                url: ULTRAADDONS_DATA.ajax_url,
                data: {
                    action: 'wpt_fragment_refresh'
                },
                success: function(response){

//                                    setFragmentsRefresh( response );
                    if(typeof minicart_type !== 'undefined'){
                        var cart_hash = response.cart_hash;
                        var fragments = response.fragments;
                        var html = '';
                        var supportedElement = ['div.widget_shopping_cart_content','a.cart-contents','a.footer-cart-contents'];
                        if ( fragments && cart_hash !== '' ) {
                            if(minicart_type === 'load'){
                                $.each( fragments, function( key, value ) {
                                    if('string' === typeof key && $.inArray(key, supportedElement) != -1 && typeof $( key ) === 'object') {
                                        html += value;
                                    }

                                });
                                $('div.tables_cart_message_box').attr('data-type','refresh');//Set
                                $('div.tables_cart_message_box').html(html);
                            }

                        }

                    }
                }
            });
    }
} (jQuery, window));