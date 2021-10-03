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
            
            //Animated Headline
            var AnimatedHeadline = EM.frontend.handlers.Base.extend({
                svgPaths: {
                    circle: ['M325,18C228.7-8.3,118.5,8.3,78,21C22.4,38.4,4.6,54.6,5.6,77.6c1.4,32.4,52.2,54,142.6,63.7 c66.2,7.1,212.2,7.5,273.5-8.3c64.4-16.6,104.3-57.6,33.8-98.2C386.7-4.9,179.4-1.4,126.3,20.7'],
                    underline_zigzag: ['M9.3,127.3c49.3-3,150.7-7.6,199.7-7.4c121.9,0.4,189.9,0.4,282.3,7.2C380.1,129.6,181.2,130.6,70,139 c82.6-2.9,254.2-1,335.9,1.3c-56,1.4-137.2-0.3-197.1,9'],
                    x: ['M497.4,23.9C301.6,40,155.9,80.6,4,144.4', 'M14.1,27.6c204.5,20.3,393.8,74,467.3,111.7'],
                    strikethrough: ['M3,75h493.5'],
                    curly: ['M3,146.1c17.1-8.8,33.5-17.8,51.4-17.8c15.6,0,17.1,18.1,30.2,18.1c22.9,0,36-18.6,53.9-18.6 c17.1,0,21.3,18.5,37.5,18.5c21.3,0,31.8-18.6,49-18.6c22.1,0,18.8,18.8,36.8,18.8c18.8,0,37.5-18.6,49-18.6c20.4,0,17.1,19,36.8,19 c22.9,0,36.8-20.6,54.7-18.6c17.7,1.4,7.1,19.5,33.5,18.8c17.1,0,47.2-6.5,61.1-15.6'],
                    diagonal: ['M13.5,15.5c131,13.7,289.3,55.5,475,125.5'],
                    double: ['M8.4,143.1c14.2-8,97.6-8.8,200.6-9.2c122.3-0.4,287.5,7.2,287.5,7.2', 'M8,19.4c72.3-5.3,162-7.8,216-7.8c54,0,136.2,0,267,7.8'],
                    double_underline: ['M5,125.4c30.5-3.8,137.9-7.6,177.3-7.6c117.2,0,252.2,4.7,312.7,7.6', 'M26.9,143.8c55.1-6.1,126-6.3,162.2-6.1c46.5,0.2,203.9,3.2,268.9,6.4'],
                    underline: ['M7.7,145.6C109,125,299.9,116.2,401,121.3c42.1,2.2,87.6,11.8,87.3,25.7']
                },

                getDefaultSettings() {
                    const iterationDelay = this.getElementSettings('rotate_iteration_delay'),
                    settings = {
                        animationDelay: iterationDelay || 2500,
                        //letters effect
                        lettersDelay: iterationDelay * 0.02 || 50,
                        //typing effect
                        typeLettersDelay: iterationDelay * 0.06 || 150,
                        selectionDuration: iterationDelay * 0.2 || 500,
                        //clip effect
                        revealDuration: iterationDelay * 0.24 || 600,
                        revealAnimationDelay: iterationDelay * 0.6 || 1500,
                        // Highlighted headline
                        highlightAnimationDuration: this.getElementSettings('highlight_animation_duration') || 1200,
                        highlightAnimationDelay: this.getElementSettings('highlight_iteration_delay') || 8000
                    };
                    settings.typeAnimationDelay = settings.selectionDuration + 800;
                    settings.selectors = {
                        headline: '.elementor-headline',
                        dynamicWrapper: '.elementor-headline-dynamic-wrapper',
                        dynamicText: '.elementor-headline-dynamic-text'
                    };
                    settings.classes = {
                        dynamicText: 'elementor-headline-dynamic-text',
                        dynamicLetter: 'elementor-headline-dynamic-letter',
                        textActive: 'elementor-headline-text-active',
                        textInactive: 'elementor-headline-text-inactive',
                        letters: 'elementor-headline-letters',
                        animationIn: 'elementor-headline-animation-in',
                        typeSelected: 'elementor-headline-typing-selected',
                        activateHighlight: 'e-animated',
                        hideHighlight: 'e-hide-highlight'
                    };
                    return settings;
                },

                getDefaultElements() {
                    var selectors = this.getSettings('selectors');
                    return {
                        $headline: this.$element.find(selectors.headline),
                        $dynamicWrapper: this.$element.find(selectors.dynamicWrapper),
                        $dynamicText: this.$element.find(selectors.dynamicText)
                    };
                },

                getNextWord($word) {
                    return $word.is(':last-child') ? $word.parent().children().eq(0) : $word.next();
                },
                
                switchWord($oldWord, $newWord) {
                    $oldWord.removeClass('elementor-headline-text-active').addClass('elementor-headline-text-inactive');
                    $newWord.removeClass('elementor-headline-text-inactive').addClass('elementor-headline-text-active');
                    this.setDynamicWrapperWidth($newWord);
                },

                singleLetters() {
                    var classes = this.getSettings('classes');
                    this.elements.$dynamicText.each(function () {
                        var $word = jQuery(this),
                            letters = $word.text().split(''),
                            isActive = $word.hasClass(classes.textActive);
                            $word.empty();
                            letters.forEach(function (letter) {
                                var $letter = jQuery('<span>', {
                                    class: classes.dynamicLetter
                                }).text(letter);
                
                                if (isActive) {
                                    $letter.addClass(classes.animationIn);
                                }
                
                                $word.append($letter);
                            });
                        $word.css('opacity', 1);
                    });
                },

                showLetter($letter, $word, bool, duration) {
                    var self = this,
                        classes = this.getSettings('classes');
                    $letter.addClass(classes.animationIn);

                    if (!$letter.is(':last-child')) {
                        setTimeout(function () {
                            self.showLetter($letter.next(), $word, bool, duration);
                        }, duration);
                    } else if (!bool) {
                        setTimeout(function () {
                            self.hideWord($word);
                        }, self.getSettings('animationDelay'));
                    }
                },

                hideLetter($letter, $word, bool, duration) {
                    var self = this,
                        settings = this.getSettings();
                    $letter.removeClass(settings.classes.animationIn);

                    if (!$letter.is(':last-child')) {
                        setTimeout(function () {
                            self.hideLetter($letter.next(), $word, bool, duration);
                        }, duration);
                    } else if (bool) {
                        setTimeout(function () {
                            self.hideWord(self.getNextWord($word));
                        }, self.getSettings('animationDelay'));
                    }
                },

                showWord($word, $duration) {
                    var self = this,
                        settings = self.getSettings(),
                        animationType = self.getElementSettings('animation_type');

                    if ('typing' === animationType) {
                        self.showLetter($word.find('.' + settings.classes.dynamicLetter).eq(0), $word, false, $duration);
                        $word.addClass(settings.classes.textActive).removeClass(settings.classes.textInactive);
                    } else if ('clip' === animationType) {
                        self.elements.$dynamicWrapper.animate({
                            width: $word.width() + 10
                        }, settings.revealDuration, function () {
                            setTimeout(function () {
                                self.hideWord($word);
                            }, settings.revealAnimationDelay);
                        });
                    }
                },

                hideWord($word) {
                    var self = this,
                        settings = self.getSettings(),
                        classes = settings.classes,
                        letterSelector = '.' + classes.dynamicLetter,
                        animationType = self.getElementSettings('animation_type'),
                        nextWord = self.getNextWord($word);

                    if (!this.isLoopMode && $word.is(':last-child')) {
                        return;
                    }

                    if ('typing' === animationType) {
                        self.elements.$dynamicWrapper.addClass(classes.typeSelected);
                        setTimeout(function () {
                            self.elements.$dynamicWrapper.removeClass(classes.typeSelected);
                            $word.addClass(settings.classes.textInactive).removeClass(classes.textActive).children(letterSelector).removeClass(classes.animationIn);
                        }, settings.selectionDuration);
                        setTimeout(function () {
                            self.showWord(nextWord, settings.typeLettersDelay);
                        }, settings.typeAnimationDelay);
                    } else if (self.elements.$headline.hasClass(classes.letters)) {
                        var bool = $word.children(letterSelector).length >= nextWord.children(letterSelector).length;
                        self.hideLetter($word.find(letterSelector).eq(0), $word, bool, settings.lettersDelay);
                        self.showLetter(nextWord.find(letterSelector).eq(0), nextWord, bool, settings.lettersDelay);
                        self.setDynamicWrapperWidth(nextWord);
                    } else if ('clip' === animationType) {
                        self.elements.$dynamicWrapper.animate({
                            width: '2px'
                        }, settings.revealDuration, function () {
                            self.switchWord($word, nextWord);
                            self.showWord(nextWord);
                        });
                    } else {
                        self.switchWord($word, nextWord);
                        setTimeout(function () {
                            self.hideWord(nextWord);
                        }, settings.animationDelay);
                    }
                },

                setDynamicWrapperWidth($word) {
                    const animationType = this.getElementSettings('animation_type');

                    if ('clip' !== animationType && 'typing' !== animationType) {
                        this.elements.$dynamicWrapper.css('width', $word.width());
                    }
                },

                animateHeadline() {
                    var self = this,
                        animationType = self.getElementSettings('animation_type'),
                        $dynamicWrapper = self.elements.$dynamicWrapper;

                    if ('clip' === animationType) {
                        $dynamicWrapper.width($dynamicWrapper.width() + 10);
                    } else if ('typing' !== animationType) {
                        self.setDynamicWrapperWidth(self.elements.$dynamicText);
                    } //trigger animation


                    setTimeout(function () {
                        self.hideWord(self.elements.$dynamicText.eq(0));
                    }, self.getSettings('animationDelay'));
                },

                getSvgPaths(pathName) {
                    var pathsInfo = this.svgPaths[pathName],
                        $paths = jQuery();
                    pathsInfo.forEach(function (pathInfo) {
                        $paths = $paths.add(jQuery('<path>', {
                            d: pathInfo
                        }));
                    });
                    return $paths;
                },

                addHighlight() {
                    const elementSettings = this.getElementSettings(),
                        $svg = jQuery('<svg>', {
                            xmlns: 'http://www.w3.org/2000/svg',
                            viewBox: '0 0 500 150',
                            preserveAspectRatio: 'none'
                        }).html(this.getSvgPaths(elementSettings.marker));
                    this.elements.$dynamicWrapper.append($svg[0].outerHTML);
                },

                rotateHeadline() {
                    var settings = this.getSettings(); //insert <span> for each letter of a changing word

                    if (this.elements.$headline.hasClass(settings.classes.letters)) {
                        this.singleLetters();
                    } //initialise headline animation


                    this.animateHeadline();
                },

                initHeadline() {
                    const headlineStyle = this.getElementSettings('headline_style');

                    if ('rotate' === headlineStyle) {
                        this.rotateHeadline();
                    } else if ('highlight' === headlineStyle) {
                        this.addHighlight();
                        this.activateHighlightAnimation();
                    }

                    this.deactivateScrollListener();
                },

                activateHighlightAnimation() {
                    const settings = this.getSettings(),
                        classes = settings.classes,
                        $headline = this.elements.$headline;
                    $headline.removeClass(classes.hideHighlight).addClass(classes.activateHighlight);

                    if (!this.isLoopMode) {
                        return;
                    }

                    setTimeout(() => {
                        $headline.removeClass(classes.activateHighligh).addClass(classes.hideHighlight);
                    }, settings.highlightAnimationDuration + settings.highlightAnimationDelay * .8);
                    setTimeout(() => {
                        this.activateHighlightAnimation(false);
                    }, settings.highlightAnimationDuration + settings.highlightAnimationDelay);
                },

                activateScrollListener() {
                    var _scroll = EM.utils.Scroll;
                    const scrollBuffer = -100;
                    this.intersectionObservers.startAnimation.observer = _scroll.scrollObserver({
                        offset: `0px 0px ${scrollBuffer}px`,
                        callback: event => {
                            if (event.isInViewport) {
                                this.initHeadline();
                            }
                        }
                    });
                    this.intersectionObservers.startAnimation.element = this.elements.$headline[0];
                    this.intersectionObservers.startAnimation.observer.observe(this.intersectionObservers.startAnimation.element);
                },

                deactivateScrollListener() {
                    this.intersectionObservers.startAnimation.observer.unobserve(this.intersectionObservers.startAnimation.element);
                },

                onInit() {
                    elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
                    this.intersectionObservers = {
                        startAnimation: {
                            observer: null,
                            element: null
                        }
                    };
                    this.isLoopMode = 'yes' === this.getElementSettings('loop');
                    this.activateScrollListener();
                }

            });

            EF.hooks.addAction(
                    'frontend/element_ready/ultraaddons-animated-headline.default',
                    function ($scope) {
                            // console.log($scope);
                            EF.elementsHandler.addHandler(AnimatedHeadline, {
                                $element: $scope,
                                selectors: {
                                        container: '.elementor-headline',
                                },
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
           
           
//            EF.hooks.addAction( 'frontend/element_ready/widget', function( $scope ) {
//                EF.elementsHandler.addHandler( CusttomCSS, { $element: $scope });
//            });
            // Wrapper Link
           $('.ua-wrapper-link').each(function() {
                    var link = $(this).data('_ua_element_link');
                    $(this).on('click', function(e) {
                        //console.log($(this),e.target.tagName);
                        let tag = e.target.tagName;
                        
                        if( tag === 'STRONG' || tag === 'B' || tag === 'SPAN' || tag === 'A' || tag === 'BUTTON' || tag === 'INPUT' ){
                            return;
                        }

                        if (link.is_external) {
                                window.open(link.url);
                        } else {
                                location.href = link.url;
                        }
                        
                    });
            });


//            EF.hooks.addAction(
//                'frontend/element_ready/widgett',
//                function($scope, $) {
//                    var link = $(this).data('_ua_element_link');
//                    
//                    
//                });
            
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
                
                //Counter
                Counter:function($scope){
                    var $item = $scope.find('.ua-counter-text');
                    $($item).appear(function () {
                        var element = $(this);
                        var timeSet = setTimeout(function () {
                            if (element.hasClass('ua-counter-text')) {
                                element.find('.ua-counter-value').countTo();
                            }
                        });
                    });
                },
                
                //Addd new all - one by one with comma
                
                
            };
            
            let elementReadyMap = {
                'ultraaddons-alert.default'     : UltraAddonsMap.Alert,
                'ultraaddons-timeline.default'  : UltraAddonsMap.UA_Owl_Carousel,
                'ultraaddons-skill-bar.default'  : UltraAddonsMap.skillBar,
                'ultraaddons-counter.default'  : UltraAddonsMap.Counter,
            };
    
            $.each( elementReadyMap, function( elementKey, elementReadyMap ) {
                    EF.hooks.addAction( 'frontend/element_ready/' + elementKey, elementReadyMap );
            });
            

    });
                   
//   $('.ua-counter-text').appear(function () {
//        var element = $(this);
//        var timeSet = setTimeout(function () {
//            if (element.hasClass('ua-counter-text')) {
//                element.find('.ua-counter-value').countTo();
//            }
//        });
//    });
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
                    
   

   
        
        
        
} (jQuery, window));