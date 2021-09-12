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
            
            var ModuleBase = elementorModules.frontend.handlers.Base;
            var CusttomCSS;


            CusttomCSS = ModuleBase.extend({
                bindEvents: function(){
                    this.run();
                },
                onElementChange:function(){
                    this.run();
                },
                getDefaultSettings:function(){
                    return {
                        target: this.$element
                    }
                },
                getCss:function(){
                    return this.getElementSettings('ua_custom_css');
                },
                run:function(){
                    var cssRules = this.getCss();
                    $('<style>' + cssRules + '</style>').appendTo('head');
                }
            });

            EF.hooks.addAction( 'frontend/element_ready/widget', function( $scope ) {
                EF.elementsHandler.addHandler( CusttomCSS, { $element: $scope });
            });
            
            
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
                            var external_animation = this.getElementSettings('external_animation');
                            var settings = {
                                    autoplay: !! this.getElementSettings('autoplay'),
                                    autoplayHoverPause: !! this.getElementSettings('pause_on_hover'),
                                    autoplaySpeed: this.getElementSettings('autoplay_speed'),
                                    loop: !! this.getElementSettings('loop'),
                                    autoplayTimeout: this.getElementSettings('autoplayTimeout'),
                                    nav: false,
                                    margin: 20,
                                    //#f7fcff
                                    animateOut: external_animation,//animate__lightSpeedInRight //animate__flipOutY
                                    animateIn: external_animation,//animate__lightSpeedInRight//this.getElementSettings('animateIn'),//animate__lightSpeedInRight
                                    
                            };

//                            settings.animateOut = this.getElementSettings('animateOut');
//                            settings.animateIn = this.getElementSettings('animateIn');
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
                                    nav:false
                                };
                            
                            settings.responsive[EF.config.breakpoints.md] = {
                                    items: (this.getElementSettings('slides_to_show_tablet') || settings.items),
//                                    nav:true
                                };
                            
                            settings.responsive[EF.config.breakpoints.lg] = {
                                    items: settings.items
                                };

                            return $.extend({}, this.getDefaultSettings(), settings);
                    },

                    run: function() {
                        //console.log(this.getReadySettings());
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
    
            //Testimonial Slider
            EF.hooks.addAction(
                    'frontend/element_ready/ultraaddons-testimonial-slider.default',
                    function ($scope) {
                            EF.elementsHandler.addHandler(SliderBase, {
                                    $element: $scope,
                                    selectors: {
                                            container: '.ua-testimonial-slider-wrapper',
                                    },
                                    autoplay: true,
                            });
                    }
            );

            EF.hooks.addAction(
                'frontend/element_ready/ultraaddons-accordion.default',
                function($scope, $) {
           
                    var t = $scope.find(".ua-accordion-wrapper"),
                        h = $scope.find(".ua_accordion_item_title"),
                        r = $scope.data("type"),
                        s = 400;
                        h.each(function () {
                            $(this).hasClass("ua-active-default") && ($(this).addClass("ua-open ua-active"), $(this).next().slideDown(s));
                        }),
                        h.click(function (e) {
                            e.preventDefault();
                            var $this = $(this);
                            // $this.closest('.ua-accordion-wrapper').toggleClass('ua-active-wrapper'),
                            $this.hasClass("ua-open") ? ($this.removeClass("ua-open ua-active"), $this.next().slideUp(s)) : ($this.parent().parent().find(h).removeClass("ua-open ua-active"), 
                            $this.parent().parent().find(".ua_accordion_panel").slideUp(s), 
                            $this.toggleClass("ua-open ua-active"), $this.next().slideToggle(s))
                        });
                });

            EF.hooks.addAction(
                'frontend/element_ready/ultraaddons-post-masonry.default',
                function($scope, $) {
           
                    var $selector = $scope.find('.ua_addons_grid_wrapper');

                    if( typeof $selector == 'object' && typeof $selector.uaAddonsGridLayout == 'function' ){
                        $selector.uaAddonsGridLayout();
                    }
                    
                });
    
    
//                //Elementor Open Editor https://code.elementor.com/js-hooks/#panelopen_editorelementType 
//                //console.log(elementor);
//                elementor.hooks.addAction('panel/open_editor/widget', function (panel, model, view) {
//                    console.log(panel, model, view);
//                    var $element = view.$el.find('.elementor-selector');
//
//                    if ($element.length) {
//                        $element.click(function () {
//                            alert('Some Message');
//                        });
//                    }
//                });
            
            
//            // WC Categories still working
//            EF.hooks.addAction(
//                    'frontend/element_ready/ultraaddons-wc-categories.default',
//                    function ($scope) {
//                        
//                        var content = $scope.find('.products .product-category .product').text();
//                        console.log(content);
//                        if( content === "" || content === " " ){
//                            $scope.addClass('ua-need-apply-change');
//                            var title = 'WooCommerce Product Category Area';
//                            var default_message = 'Need update change.'
//                            
//                            var display_message = '<h2>' + title + '</h2>';
//                            display_message += '<p>' + default_message + '</p>';
//                            //$scope.find('.elementor-widget-container>*').html( display_message );
//                        }
//                    }
//            );
            
            EF.hooks.addAction( 'frontend/element_ready/ultraaddons-slider.default', add_number_inside_bullets);
           
           
            // Cart Update in Editor Screen
            EF.hooks.addAction(
                    'frontend/element_ready/ultraaddons-cart.default',
                    function ($scope) {
                        trigger_cart_update();
                    }
            );
            
        
//            //for new Elemenment to Each Widget and column
//            EF.hooks.addAction(
//                    'frontend/element_ready/widget',
//                    function ($scope) {
//                        $scope.find('.elementor-widget-container').prepend('<div class="ua-widget-background-overlay">Widget</div>');
//                    }
//            );
            
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
                    var link = $(this).data('_ua_element_link');
                    $(this).on('click', function(saiful) {
                        console.log();
                        if (link.is_external) {
                                window.open(link.url);
                        } else {
                                location.href = link.url;
                        }
                    });
            });
            
            
            
            let UltraAddonsMap = {
                /**
                 * Skillbar
                 * using barfiller
                 * 
                 * @since 1.0.5
                 * taken from medilac-core
                 */
                skillBar:function( $scope, $ ){
                    var items = $scope.find('.ua-skill-wrapper');
                    $(items).each(function(a, b){
                        let color = $(b).attr('aria-color');
                        let id = $(b).attr('aria-id');
                        let parentID = $(b).closest('.ua-element-skill-bar').data('id');
                        $('#bar-' + parentID + '-' + id + '-' + (a+1)).barfiller({ barColor: color });
                    });
                },
                //Alert
                Alert:function($scope){
                    var $item = $scope.find('.ua_alert_close');
                    $($item).on("click", function(){
                        $(this).parents(".ua_alert_box").hide();
                    });
                },

                //Owl Carousel
                UA_Owl_Carousel: function($scope) {
                    var $owlContainer = $scope.find('.ua_timeline_inner'),
                        controls = null,
                        show_slider_dots = true,
                        show_slider_arrow = true,
                        autoplay = true,
                        slider_speed = 2000,
                        slider_loop = true,
                        slider_space = 60,
                        slider_item = 3,
                        slider_drag = false,
                        slider_center = false,
                        next_icon = 'fas fa-angle-right',
                        prev_icon = 'fas fa-angle-left';

                    if ($owlContainer.attr('data-controls')) {
                        var controls = JSON.parse($owlContainer.attr('data-controls'));
                        show_slider_dots = controls.show_slider == "yes" ? controls.slider_pagi_type == 'dot' ? true : false : false;
                        show_slider_arrow = controls.show_slider == "yes" ? controls.slider_pagi_type == 'arrow' ? true : false : false;
                        autoplay = controls.slide_autoplay == "yes" ? true : false;
                        slider_speed = controls.slider_speed;
                        slider_loop = controls.slider_loop == "yes" ? true : false;
                        slider_space = controls.slider_space;
                        slider_item = controls.slider_item;
                        slider_center = controls.slider_center == "yes" ? true : false;  
                        slider_drag = controls.slider_drag == "yes" ? true : false;  
                        next_icon = controls.next_icon.library == 'svg' ? "<img src='"+controls.next_icon.value.url+"'>" : "<i class='"+controls.next_icon.value+"'></i>" ;  
                        prev_icon = controls.prev_icon.library == 'svg' ? "<img src='"+controls.prev_icon.value.url+"'>" : "<i class='"+controls.prev_icon.value+"'></i>";  
                    }

                $owlContainer.owlCarousel({
                        items: slider_item,
                        loop: slider_loop,
                        margin: slider_space,
                        smartSpeed: slider_speed,
                        dots: show_slider_dots,
                        nav: show_slider_arrow,
                        navText: [prev_icon, next_icon],
                        autoplay: autoplay,
                        mouseDrag: slider_drag,
                        center: slider_center,
                    });
                    
                },
                
                //Addd new all - one by one with comma
                
                
            };
            
            let elementReadyMap = {
                'ultraaddons-alert.default'     : UltraAddonsMap.Alert,
                'ultraaddons-timeline.default'  : UltraAddonsMap.UA_Owl_Carousel,
                'ultraaddons-skill-bar.default'  : UltraAddonsMap.skillBar,
            };
    
            $.each( elementReadyMap, function( elementKey, elementReadyMap ) {
                    EF.hooks.addAction( 'frontend/element_ready/' + elementKey, elementReadyMap );
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
        var selector = ".ua-number-slider-wrapper .owl-dots .owl-dot";
        var selector_dots = ".ua-number-slider-wrapper .owl-dots";
        var dots = document.querySelectorAll(selector);
        $(selector_dots).addClass('nav-type-number');
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
    
    
    /*     Magnific Popup js
     -------------------------------------*
    $('.video_btn').magnificPopup({
        type: 'iframe',
        mainClass: 'mfp-fade',
        preloader: true,
    });

    //Magnific popup video
     $('.play-btn').magnificPopup({
        type: 'iframe',
        iframe: {
            markup: '<div class="mfp-iframe-scaler">' +
                '<div class="mfp-close"></div>' +
                '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>' +
                '</div>',

            patterns: {
                youtube: {
                    index: 'youtube.com/',
                    id: 'v=',
                    src: 'https://www.youtube.com/embed/%id%?autoplay=1'
                },
                vimeo: {
                    index: 'vimeo.com/',
                    id: '/',
                    src: 'https://player.vimeo.com/video/%id%?autoplay=1'
                },
                gmaps: {
                    index: 'https://maps.google.',
                    src: '%id%&output=embed'
                }
            },
            srcAction: 'iframe_src',
        }
        // other options
    });
    //*************************************/
                    
                    
   $('.ua-counter-text').appear(function () {
        var element = $(this);
        var timeSet = setTimeout(function () {
            if (element.hasClass('ua-counter-text')) {
                element.find('.ua-counter-value').countTo();
            }
        });
    });

   
        
        
        
} (jQuery, window));