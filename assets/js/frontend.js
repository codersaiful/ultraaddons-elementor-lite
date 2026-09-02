;(function ($, w) {
    'use strict';
    
    var $window = $(w);

    $.fn.ultraDataAttr = function( dataAttr ) {
            return this.data( dataAttr );
    };

    /**
     * For Deleveloper Only
     */
    
    $window.on( 'elementor/frontend/init', function() {


        //Navigation Menu Dropdown issues
        var $openClass = "open open-position";
        var $hasChildren = "menu-item-has-children";
        
        if ($hasChildren.length > 0) {
          jQuery(".navbar").on("click", "." + $hasChildren, function () {
            jQuery(this).toggleClass($openClass);
          });
        }
        
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
                
                getId:function(){
                    return this.$element.data('id');
                },

                run:function(){
                    var cssRules = this.getCss();
                    var element_id = this.getId();
                    if(cssRules){
                        $('<style id="ua-custom-css-'+element_id+'" >' + cssRules + '</style>').appendTo('head');
                    }else{
                        $('#ua-custom-css-' + element_id).remove();
                    }
                    
                }
            });

            EF.hooks.addAction( 'frontend/element_ready/widget', function( $scope ) {
                var $item = $scope.find('div').closest('.custom-css-applied-yes');
                var len = $item.length;
                var element_id = $item.data('id');
                
                if(len){
                    EF.elementsHandler.addHandler( CusttomCSS, { $element: $scope,id: element_id});
                }else{
                    $('#ua-custom-css-' + element_id).remove();
                }
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

            //Start Here for Developer_Test_Object
            var Developer_Test_Object = EM.frontend.handlers.Base.extend({
                onInit: function(){
                    this.run();
                },
                onChange: function(){
                    this.run();
                },
                getReadySettings: function(){
                    var settings = this.getElementSettings();
                    var $readySettings = {
                        delayTimer: settings.delayTimer,
                        play: !! settings.play, //Actually for Yes, or Switch value
                    };

                    return $readySettings;
                },

                run: function(){
                    var item = this.$element.find('.developer_test_element h2 span');
                    var delayTimer = this.getReadySettings().delayTimer;
                    item.html(delayTimer);
                    //console.log(this.getReadySettings());
                    //console.log(this.getReadySettings(),this.getElementSettings());
                    //console.log(delayTimer);
                }
            });

            // Developer_Test
            EF.hooks.addAction(
                'frontend/element_ready/ultraaddons-developer-test.default',
                function ($scope) {
                        
                        EF.elementsHandler.addHandler(Developer_Test_Object, {
                                $element: $scope,
                                selectors: {
                                        container: '.developer_test_element',
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
                    'frontend/element_ready/ultraaddons-wc-mini-cart.default',
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

            };
            
            let elementReadyMap = {
                'ultraaddons-alert.default'     : UltraAddonsMap.Alert,
                //'ultraaddons-timeline.default'  : UltraAddonsMap.UA_Owl_Carousel, //It has removed actually
                'ultraaddons-skill-bar.default' : UltraAddonsMap.skillBar,
                'ultraaddons-counter.default'  	: UltraAddonsMap.Counter,
            };
			
			
            $.each( elementReadyMap, function( elementKey, elementReadyMap ) {
                    EF.hooks.addAction( 'frontend/element_ready/' + elementKey, elementReadyMap );
            });
            



			 /**
             * Skill Chart
             * 
             * @author B M Rafiul Alam <bmrafiul.alam@gmail.com>
             * @since 1.1.0.8
             */
			var Skill_Chart = EM.frontend.handlers.Base.extend({
                onInit: function(){
                    this.run();
                },
                onChange: function(){
                    this.run();
                },
                getReadySettings: function(){
                    var settings = this.getElementSettings();
                    return Object.assign(settings);
                },

                run: function(){
                    var targetElement = this.$element.find('.ua-skill-chart');
                   
                    targetElement.easyPieChart( this.getReadySettings() );
                }
            });

            // Skill_Chart Hooked Here
            EF.hooks.addAction(
                'frontend/element_ready/ultraaddons-skill-chart.default',
                function ($scope) {
                        
                        EF.elementsHandler.addHandler(Skill_Chart, {
                                $element: $scope,
                                selectors: {
                                      container: '.ua-skill-chart',
                                },
                                scaleColor: "#ecf0f1",
                        });
                }
            );

            
            //Portfolio Gallery
            EF.hooks.addAction(
                'frontend/element_ready/ultraaddons-product-filter-gallery.default',
                function ($scope) {
                    $('.list').click(function(){
                        const value = $(this).attr('data-filter');
                        if (value == 'all'){
                          $('.itemsbox').show('1000');
                        }
                        else {
                          $('.itemsbox').not('.'+value).hide('1000');
                          $('.itemsbox').filter('.'+value).show('1000');
                        }
                      })
                    //add active class on selected item
                    $('.list').click(function(){
                      $(this).addClass('active').siblings().removeClass('active');    
                    });
                }
            );

            // Testimonial Box / Carousel Handler
            var widgetTestimonialCarousel = function( $scope, $ ) {
                var $carousel = $scope.find( '.ua-testimonial-carousel, .wpr-testimonial-carousel' );
                if ( ! $carousel.length || ! $.fn.slick ) {
                    return;
                }

                if ( $carousel.hasClass('slick-initialized') ) {
                    $carousel.slick('unslick');
                }

                var rawData = $carousel.attr( 'data-slick' );
                var settings = rawData ? JSON.parse( rawData ) : {};

                var colsDesktop = ( settings.slidesToShow !== undefined && settings.slidesToShow !== '' ) ? parseInt(settings.slidesToShow, 10) : 2,
                    colsTablet   = ( settings.columnsTablet !== undefined && settings.columnsTablet !== '' ) ? parseInt(settings.columnsTablet, 10) : ( colsDesktop > 2 ? 2 : colsDesktop ),
                    colsMobile   = ( settings.columnsMobile !== undefined && settings.columnsMobile !== '' ) ? parseInt(settings.columnsMobile, 10) : 1,
                    slidesToScroll = settings.slidesToScroll ? parseInt(settings.slidesToScroll, 10) : 1,
                    slideEffect  = $carousel.attr('data-slide-effect') || 'slide';

                if ( ! colsDesktop || isNaN(colsDesktop) ) {
                    var sliderClass = ( $scope.attr('class') || '' ) + ' ' + ( $carousel.closest('.elementor-widget').attr('class') || '' );
                    var desktopMatch = sliderClass.match(/(?:ua|wpr)-testimonial-slider-columns(?:-desktop)?-(\d)/);
                    colsDesktop  = desktopMatch ? parseInt(desktopMatch[1], 10) : 2;
                    colsTablet   = colsDesktop > 2 ? 2 : colsDesktop;
                }

                $carousel.slick({
                    rtl: !! settings.rtl,
                    infinite: settings.infinite !== false,
                    speed: settings.speed || 700,
                    arrows: !! settings.arrows,
                    dots: !! settings.dots,
                    autoplay: !! settings.autoplay,
                    autoplaySpeed: settings.autoplaySpeed || 4000,
                    pauseOnHover: settings.pauseOnHover !== false,
                    prevArrow: $scope.find('.ua-testimonial-prev-arrow, .wpr-testimonial-prev-arrow'),
                    nextArrow: $scope.find('.ua-testimonial-next-arrow, .wpr-testimonial-next-arrow'),
                    appendDots: $scope.find('.ua-testimonial-dots, .wpr-testimonial-dots'),
                    customPaging: function () {
                        return '<span class="ua-testimonial-dot"></span>';
                    },
                    slidesToShow: colsDesktop,
                    slidesToScroll: slidesToScroll,
                    fade: ( colsDesktop === 1 && slideEffect === 'fade' ),
                    responsive: [
                        {
                            breakpoint: 1024,
                            settings: {
                                slidesToShow: colsTablet,
                                slidesToScroll: ( slidesToScroll > colsTablet ? colsTablet : slidesToScroll ),
                                fade: ( colsTablet === 1 && slideEffect === 'fade' )
                            }
                        },
                        {
                            breakpoint: 767,
                            settings: {
                                slidesToShow: colsMobile,
                                slidesToScroll: 1,
                                fade: ( colsMobile === 1 && slideEffect === 'fade' )
                            }
                        }
                    ]
                });
            };

            EF.hooks.addAction( 'frontend/element_ready/ultraaddons-testimonial-box.default', widgetTestimonialCarousel );
            EF.hooks.addAction( 'frontend/element_ready/testimonial-box.default', widgetTestimonialCarousel );

            // Team Box / Carousel Handler
            var widgetTeamCarousel = function( $scope, $ ) {
                var $carousel = $scope.find( '.ua-team-carousel' );
                if ( ! $carousel.length || ! $.fn.slick ) {
                    return;
                }

                if ( $carousel.hasClass('slick-initialized') ) {
                    $carousel.slick('unslick');
                }

                var rawData = $carousel.attr( 'data-slick' );
                var settings = rawData ? JSON.parse( rawData ) : {};

                var colsDesktop = ( settings.slidesToShow !== undefined && settings.slidesToShow !== '' ) ? parseInt(settings.slidesToShow, 10) : 1,
                    colsTablet   = ( settings.columnsTablet !== undefined && settings.columnsTablet !== '' ) ? parseInt(settings.columnsTablet, 10) : ( colsDesktop > 1 ? colsDesktop : 1 ),
                    colsMobile   = ( settings.columnsMobile !== undefined && settings.columnsMobile !== '' ) ? parseInt(settings.columnsMobile, 10) : 1,
                    slidesToScroll = settings.slidesToScroll ? parseInt(settings.slidesToScroll, 10) : 1,
                    slideEffect  = $carousel.attr('data-slide-effect') || 'slide';

                $carousel.slick({
                    rtl: !! settings.rtl,
                    infinite: settings.infinite !== false,
                    speed: settings.speed || 700,
                    arrows: !! settings.arrows,
                    dots: !! settings.dots,
                    autoplay: !! settings.autoplay,
                    autoplaySpeed: settings.autoplaySpeed || 4000,
                    pauseOnHover: settings.pauseOnHover !== false,
                    prevArrow: $scope.find('.ua-team-prev-arrow'),
                    nextArrow: $scope.find('.ua-team-next-arrow'),
                    appendDots: $scope.find('.ua-team-dots'),
                    slidesToShow: colsDesktop,
                    slidesToScroll: slidesToScroll,
                    fade: ( colsDesktop === 1 && slideEffect === 'fade' ),
                    responsive: [
                        {
                            breakpoint: 1024,
                            settings: {
                                slidesToShow: colsTablet,
                                slidesToScroll: ( slidesToScroll > colsTablet ? colsTablet : slidesToScroll ),
                                fade: ( colsTablet === 1 && slideEffect === 'fade' )
                            }
                        },
                        {
                            breakpoint: 767,
                            settings: {
                                slidesToShow: colsMobile,
                                slidesToScroll: 1,
                                fade: ( colsMobile === 1 && slideEffect === 'fade' )
                            }
                        }
                    ]
                });
            };

            EF.hooks.addAction( 'frontend/element_ready/ultraaddons-team-box.default', widgetTeamCarousel );



            /**
             * UltraAddons Flip Box Handler
             *
             * @param {jQuery} $scope
             * @param {jQuery} $
             */
            var UltraAddonsFlipBox = function( $scope, $ ) {
                var $wrapper = $scope.find( '.ua-flip-box-container' );
                if ( ! $wrapper.length ) {
                    return;
                }

                function setAutoMaxHeight() {
                    var $front = $wrapper.find( '.ua-flip-box-front' );
                    var $back = $wrapper.find( '.ua-flip-box-back' );
                    var frontHeight = $front.outerHeight() || 0;
                    var backHeight = $back.outerHeight() || 0;
                    var maxHeight = Math.max( frontHeight, backHeight );
                    if ( maxHeight > 0 ) {
                        $wrapper.find( '.ua-flip-box-card' ).css( 'height', maxHeight + 'px' );
                        $wrapper.css( 'height', maxHeight + 'px' );
                    }
                }

                function setDynamicHeight() {
                    var $front = $wrapper.find( '.ua-flip-box-front' );
                    var $back = $wrapper.find( '.ua-flip-box-back' );
                    var frontHeight = $front.outerHeight() || 0;
                    var backHeight = $back.outerHeight() || 0;
                    var targetHeight = ( $wrapper.hasClass( 'ua-flip-box-active' ) || $wrapper.hasClass( '--active' ) ) ? backHeight : frontHeight;
                    if ( targetHeight > 0 ) {
                        $wrapper.find( '.ua-flip-box-card' ).css( 'height', targetHeight + 'px' );
                        $wrapper.css( 'height', targetHeight + 'px' );
                    }
                }

                // Click event toggle
                $wrapper.filter( '.ua-flip-box-click' ).add( $scope.find( '.ua-flip-box-click' ) ).off( 'click.uaFlipBox' ).on( 'click.uaFlipBox', function( e ) {
                    if ( $( e.target ).closest( '.ua-flip-box-button, a[href]:not([href="#"])' ).length && ! $( this ).hasClass( 'ua-flip-box-active' ) ) {
                        return;
                    }
                    $( this ).toggleClass( 'ua-flip-box-active --active' );
                } );

                // Hover event class toggle
                $wrapper.filter( '.ua-flip-box-hover' ).add( $scope.find( '.ua-flip-box-hover' ) ).off( 'mouseenter.uaFlipBox mouseleave.uaFlipBox' ).on( 'mouseenter.uaFlipBox mouseleave.uaFlipBox', function() {
                    $( this ).toggleClass( 'ua-flip-box-active --active' );
                } );

                // Touch support for hover trigger on mobile / tablet
                if ( 'ontouchstart' in window || navigator.maxTouchPoints > 0 ) {
                    $wrapper.filter( '.ua-flip-box-hover' ).add( $scope.find( '.ua-flip-box-hover' ) ).off( 'touchend.uaFlipBox' ).on( 'touchend.uaFlipBox', function( e ) {
                        if ( $( e.target ).closest( '.ua-flip-box-button, a[href]:not([href="#"])' ).length && $( this ).hasClass( 'ua-flip-box-active' ) ) {
                            return;
                        }
                        $( this ).toggleClass( 'ua-flip-box-active --active' );
                    } );
                }

                // Auto height handling with responsive resize support
                if ( $wrapper.hasClass( 'ua-flip-box-auto-height' ) ) {
                    if ( $wrapper.hasClass( 'ua-flipbox-max' ) ) {
                        setAutoMaxHeight();
                        var interval = setInterval( setAutoMaxHeight, 250 );
                        setTimeout( function() {
                            clearInterval( interval );
                        }, 4000 );

                        $( window ).off( 'resize.uaFlipBox' + $scope.data( 'id' ) ).on( 'resize.uaFlipBox' + $scope.data( 'id' ), function() {
                            setAutoMaxHeight();
                        } );
                    } else if ( $wrapper.hasClass( 'ua-flipbox-dynamic' ) ) {
                        setDynamicHeight();
                        $wrapper.on( 'click.uaFlipBox mouseenter.uaFlipBox mouseleave.uaFlipBox touchend.uaFlipBox', function() {
                            setTimeout( setDynamicHeight, 50 );
                        } );

                        $( window ).off( 'resize.uaFlipBox' + $scope.data( 'id' ) ).on( 'resize.uaFlipBox' + $scope.data( 'id' ), function() {
                            setDynamicHeight();
                        } );
                    }
                }
            };

            EF.hooks.addAction( 'frontend/element_ready/ultraaddons-flip-box.default', UltraAddonsFlipBox );

            /**
             * UltraAddons Tooltip Handler
             *
             * @param {jQuery} $scope
             * @param {jQuery} $
             */
            var UltraAddonsTooltip = function( $scope, $ ) {
                var $tooltip = $scope.find( '.ua-tooltip' );
                if ( ! $tooltip.length ) {
                    return;
                }

                // Touch support for mobile / tablet devices
                if ( 'ontouchstart' in window || navigator.maxTouchPoints > 0 ) {
                    $tooltip.off( 'touchend.uaTooltip' ).on( 'touchend.uaTooltip', function( e ) {
                        if ( $( e.target ).closest( 'a[href]:not([href="#"])' ).length && $( this ).hasClass( 'ua-tooltip-active' ) ) {
                            return;
                        }
                        $( this ).toggleClass( 'ua-tooltip-active' );
                    } );

                    // Close tooltip on tapping outside
                    $( document ).on( 'touchend.uaTooltipDoc' + $scope.data( 'id' ), function( e ) {
                        if ( ! $( e.target ).closest( $tooltip ).length ) {
                            $tooltip.removeClass( 'ua-tooltip-active' );
                        }
                    } );
                }
            };

            EF.hooks.addAction( 'frontend/element_ready/ultraaddons-tooltip.default', UltraAddonsTooltip );

            /**
             * UltraAddons Advanced Accordion Handler
             *
             * @param {jQuery} $scope
             * @param {jQuery} $
             */
            var UltraAddonsAccordion = function( $scope, $ ) {
                var hashTag = window.location.hash ? window.location.hash.substring( 1 ) : '',
                    hashTagExists = false,
                    $advAccordion = $scope.find( '.ua-adv-accordion' ),
                    $accordionHeader = $scope.find( '.ua-accordion-header' ),
                    accordionType = $advAccordion.data( 'accordion-type' ) || 'accordion',
                    triggerEvent = $advAccordion.data( 'trigger-event' ) || 'click',
                    accordionSpeed = parseInt( $advAccordion.data( 'toogle-speed' ), 10 ) || 300,
                    customIdOffset = parseInt( $advAccordion.data( 'custom-id-offset' ), 10 ) || 0,
                    scrollOnClick = $advAccordion.data( 'scroll-on-click' ) === 'yes',
                    scrollSpeed = parseInt( $advAccordion.data( 'scroll-speed' ), 10 ) || 300;

                if ( ! $advAccordion.length ) {
                    return;
                }

                // Initial open state: URL hash deep-link takes priority over default active
                if ( hashTag || scrollOnClick ) {
                    $accordionHeader.each( function() {
                        if ( scrollOnClick ) {
                            $( this ).attr( 'data-scroll', $( this ).offset().top );
                        }
                        if ( hashTag && $( this ).attr( 'id' ) === hashTag ) {
                            hashTagExists = true;
                            $( this ).addClass( 'active' ).attr( 'aria-expanded', 'true' );
                            $( this ).next( '.ua-accordion-content' ).slideDown( accordionSpeed );
                        }
                    } );
                }

                if ( ! hashTagExists ) {
                    $accordionHeader.each( function() {
                        if ( $( this ).hasClass( 'active-default' ) ) {
                            $( this ).addClass( 'active' ).attr( 'aria-expanded', 'true' );
                            $( this ).next( '.ua-accordion-content' ).slideDown( accordionSpeed );
                        }
                    } );
                }

                // Core Toggle Handler function
                function toggleTab( $header, forceOpen ) {
                    if ( $header.hasClass( 'ua-triggered' ) ) {
                        return;
                    }

                    var isOpen = $header.hasClass( 'active' );

                    if ( accordionType === 'accordion' ) {
                        if ( isOpen && ! forceOpen ) {
                            $header.removeClass( 'active' ).attr( 'aria-expanded', 'false' );
                            $header.next( '.ua-accordion-content' ).slideUp( accordionSpeed );
                        } else if ( ! isOpen ) {
                            $header.closest( '.ua-adv-accordion' ).find( '.ua-accordion-header' ).removeClass( 'active' ).attr( 'aria-expanded', 'false' );
                            $header.closest( '.ua-adv-accordion' ).find( '.ua-accordion-content' ).slideUp( accordionSpeed );
                            $header.addClass( 'active' ).attr( 'aria-expanded', 'true' );
                            $header.next( '.ua-accordion-content' ).slideDown( accordionSpeed );
                        }
                    } else {
                        // Toggle mode (multi-open)
                        if ( isOpen && ! forceOpen ) {
                            $header.removeClass( 'active' ).attr( 'aria-expanded', 'false' );
                            $header.next( '.ua-accordion-content' ).slideUp( accordionSpeed );
                        } else if ( ! isOpen ) {
                            $header.addClass( 'active' ).attr( 'aria-expanded', 'true' );
                            $header.next( '.ua-accordion-content' ).slideDown( accordionSpeed );
                        }
                    }

                    if ( scrollOnClick && $header.hasClass( 'active' ) ) {
                        $( 'html, body' ).animate( {
                            scrollTop: $header.offset().top - customIdOffset
                        }, scrollSpeed );
                    }

                    setTimeout( function() {
                        $header.addClass( 'ua-triggered' );
                        setTimeout( function() {
                            $header.removeClass( 'ua-triggered' );
                        }, 100 );
                    }, 50 );
                }

                // Remove previous event handlers for nested accordions
                $accordionHeader.off( 'click.uaAdvAccordion mouseenter.uaAdvAccordion' );

                // Click event
                $accordionHeader.on( 'click.uaAdvAccordion', function( e ) {
                    e.preventDefault();
                    toggleTab( $( this ), false );
                } );

                // Hover event (if enabled)
                if ( triggerEvent === 'hover' ) {
                    $accordionHeader.on( 'mouseenter.uaAdvAccordion', function( e ) {
                        toggleTab( $( this ), true );
                    } );
                }

                // Keyboard navigation (Enter & Space)
                $scope.off( 'keydown.uaAdvAccordion', '.ua-accordion-header' ).on( 'keydown.uaAdvAccordion', '.ua-accordion-header', function( e ) {
                    if ( e.which === 13 || e.which === 32 ) {
                        e.preventDefault();
                        $( this ).trigger( 'click.uaAdvAccordion' );
                    }
                } );
            };

            EF.hooks.addAction( 'frontend/element_ready/ultraaddons-advanced-accordion.default', UltraAddonsAccordion );
            EF.hooks.addAction( 'frontend/element_ready/ultraaddons-accordion.default', UltraAddonsAccordion );

            /**
             * UltraAddons Advanced Tabs Handler
             *
             * @param {jQuery} $scope
             * @param {jQuery} $
             */
            var UltraAddonsAdvancedTabs = function( $scope, $ ) {
                var $advTabs = $scope.find( '.ua-adv-tabs' );
                if ( ! $advTabs.length ) {
                    return;
                }

                var $navItems = $advTabs.find( '> .ua-tabs-nav ul li.ua-tab-nav-item' ),
                    $contentItems = $advTabs.find( '> .ua-tabs-content > .ua-tab-content-item' ),
                    scrollOnClick = $advTabs.data( 'scroll-on-click' ) === 'yes',
                    scrollSpeed = parseInt( $advTabs.data( 'scroll-speed' ), 10 ) || 300,
                    customIdOffset = parseInt( $advTabs.data( 'custom-id-offset' ), 10 ) || 0,
                    isToggleMode = $advTabs.hasClass( 'ua-tab-toggle' ),
                    hashTag = window.location.hash ? window.location.hash.substring( 1 ) : '',
                    hashMatched = false;

                // Function to re-calculate child sliders/swiper/galleries
                function refreshNestedElements( $container ) {
                    if ( ! $container || ! $container.length ) {
                        return;
                    }
                    var $swipers = $container.find( '.swiper, .swiper-container' );
                    $swipers.each( function() {
                        if ( this.swiper ) {
                            this.swiper.update();
                            this.swiper.updateSize();
                            this.swiper.updateSlides();
                        }
                    } );
                    var $slick = $container.find( '.slick-slider' );
                    if ( $slick.length && typeof $slick.slick === 'function' ) {
                        $slick.slick( 'setPosition' );
                    }
                    var $isotope = $container.find( '.ua-grid-masonry, .isotope' );
                    if ( $isotope.length && typeof $isotope.isotope === 'function' ) {
                        $isotope.isotope( 'layout' );
                    }
                    $( window ).trigger( 'resize' );
                }

                // Activate specific tab by index
                function activateTab( index, doScroll ) {
                    var $targetNav = $navItems.eq( index ),
                        $targetContent = $contentItems.eq( index );

                    if ( ! $targetNav.length ) {
                        return;
                    }

                    if ( isToggleMode && $targetNav.hasClass( 'active' ) ) {
                        $targetNav.removeClass( 'active active-default' ).addClass( 'inactive' ).attr( 'aria-selected', 'false' ).attr( 'aria-expanded', 'false' ).attr( 'tabindex', '-1' );
                        $targetContent.removeClass( 'active active-default' ).addClass( 'inactive' );
                        return;
                    }

                    $navItems.removeClass( 'active active-default' ).addClass( 'inactive' ).attr( 'aria-selected', 'false' ).attr( 'aria-expanded', 'false' ).attr( 'tabindex', '-1' );
                    $contentItems.removeClass( 'active active-default' ).addClass( 'inactive' );

                    $targetNav.removeClass( 'inactive' ).addClass( 'active' ).attr( 'aria-selected', 'true' ).attr( 'aria-expanded', 'true' ).attr( 'tabindex', '0' );
                    $targetContent.removeClass( 'inactive' ).addClass( 'active' );

                    setTimeout( function() {
                        refreshNestedElements( $targetContent );
                    }, 50 );

                    if ( doScroll && scrollOnClick ) {
                        var scrollPos = $targetNav.offset().top - customIdOffset;
                        $( 'html, body' ).animate( { scrollTop: scrollPos }, scrollSpeed );
                    }
                }

                // Check URL hash for direct tab deep-linking
                if ( hashTag ) {
                    $navItems.each( function( idx ) {
                        if ( $( this ).attr( 'id' ) === hashTag ) {
                            hashMatched = true;
                            activateTab( idx, true );
                        }
                    } );
                }

                // Initial open state fallback
                if ( ! hashMatched ) {
                    var initialFound = false;
                    $navItems.each( function( idx ) {
                        if ( $( this ).hasClass( 'active-default' ) ) {
                            activateTab( idx, false );
                            initialFound = true;
                        }
                    } );
                    if ( ! initialFound && $navItems.filter( '.active' ).length ) {
                        $navItems.filter( '.active' ).each( function() {
                            var idx = $( this ).index();
                            activateTab( idx, false );
                        } );
                    }
                }

                // Click event
                $navItems.off( 'click.uaAdvTabs' ).on( 'click.uaAdvTabs', function( e ) {
                    e.preventDefault();
                    var index = $( this ).index();
                    activateTab( index, true );
                } );

                // Keyboard arrow navigation
                $navItems.off( 'keydown.uaAdvTabs' ).on( 'keydown.uaAdvTabs', function( e ) {
                    var currentIndex = $navItems.index( this );
                    if ( e.key === 'ArrowRight' || e.key === 'ArrowDown' ) {
                        e.preventDefault();
                        var nextIndex = ( currentIndex + 1 ) % $navItems.length;
                        $navItems.eq( nextIndex ).focus();
                        activateTab( nextIndex, false );
                    } else if ( e.key === 'ArrowLeft' || e.key === 'ArrowUp' ) {
                        e.preventDefault();
                        var prevIndex = ( currentIndex - 1 + $navItems.length ) % $navItems.length;
                        $navItems.eq( prevIndex ).focus();
                        activateTab( prevIndex, false );
                    } else if ( e.key === 'Enter' || e.key === ' ' ) {
                        e.preventDefault();
                        activateTab( currentIndex, true );
                    }
                } );

                // Listen to hash changes in window
                $( window ).off( 'hashchange.uaAdvTabs' + $scope.data( 'id' ) ).on( 'hashchange.uaAdvTabs' + $scope.data( 'id' ), function() {
                    var newHash = window.location.hash ? window.location.hash.substring( 1 ) : '';
                    if ( newHash ) {
                        $navItems.each( function( idx ) {
                            if ( $( this ).attr( 'id' ) === newHash ) {
                                activateTab( idx, true );
                            }
                        } );
                    }
                } );
            };

            EF.hooks.addAction( 'frontend/element_ready/ultraaddons-advanced-tabs.default', UltraAddonsAdvancedTabs );

    });// Init hook wrapup
   

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

//                   setFragmentsRefresh( response );
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

    // Document Ready Fallback for Testimonial Carousel
    $(document).ready(function() {
        if ( typeof $.fn.slick !== 'undefined' ) {
            $('.ua-testimonial-carousel, .wpr-testimonial-carousel').not('.slick-initialized').each(function() {
                var $carousel = $(this);
                var $scope = $carousel.closest('.elementor-widget');
                var rawData = $carousel.attr('data-slick');
                var settings = rawData ? JSON.parse(rawData) : {};
                var colsDesktop = ( settings.slidesToShow !== undefined && settings.slidesToShow !== '' ) ? parseInt(settings.slidesToShow, 10) : 2,
                    colsTablet   = ( settings.columnsTablet !== undefined && settings.columnsTablet !== '' ) ? parseInt(settings.columnsTablet, 10) : ( colsDesktop > 2 ? 2 : colsDesktop ),
                    colsMobile   = ( settings.columnsMobile !== undefined && settings.columnsMobile !== '' ) ? parseInt(settings.columnsMobile, 10) : 1,
                    slidesToScroll = settings.slidesToScroll ? parseInt(settings.slidesToScroll, 10) : 1,
                    slideEffect  = $carousel.attr('data-slide-effect') || 'slide';

                if ( ! colsDesktop || isNaN(colsDesktop) ) {
                    var sliderClass = ( $scope.attr('class') || '' ) + ' ' + ( $carousel.closest('.elementor-widget').attr('class') || '' );
                    var desktopMatch = sliderClass.match(/(?:ua|wpr)-testimonial-slider-columns(?:-desktop)?-(\d)/);
                    colsDesktop  = desktopMatch ? parseInt(desktopMatch[1], 10) : 2;
                    colsTablet   = colsDesktop > 2 ? 2 : colsDesktop;
                }

                $carousel.slick({
                    rtl: !! settings.rtl,
                    infinite: settings.infinite !== false,
                    speed: settings.speed || 700,
                    arrows: !! settings.arrows,
                    dots: !! settings.dots,
                    autoplay: !! settings.autoplay,
                    autoplaySpeed: settings.autoplaySpeed || 4000,
                    pauseOnHover: settings.pauseOnHover !== false,
                    prevArrow: $scope.find('.ua-testimonial-prev-arrow, .wpr-testimonial-prev-arrow'),
                    nextArrow: $scope.find('.ua-testimonial-next-arrow, .wpr-testimonial-next-arrow'),
                    appendDots: $scope.find('.ua-testimonial-dots, .wpr-testimonial-dots'),
                    customPaging: function () {
                        return '<span class="ua-testimonial-dot"></span>';
                    },
                    slidesToShow: colsDesktop,
                    slidesToScroll: slidesToScroll,
                    fade: ( colsDesktop === 1 && slideEffect === 'fade' ),
                    responsive: [
                        {
                            breakpoint: 1024,
                            settings: {
                                slidesToShow: colsTablet,
                                slidesToScroll: ( slidesToScroll > colsTablet ? colsTablet : slidesToScroll ),
                                fade: ( colsTablet === 1 && slideEffect === 'fade' )
                            }
                        },
                        {
                            breakpoint: 767,
                            settings: {
                                slidesToShow: colsMobile,
                                slidesToScroll: 1,
                                fade: ( colsMobile === 1 && slideEffect === 'fade' )
                            }
                        }
                    ]
                });
            });

            // Document Ready Fallback for Team Carousel
            $('.ua-team-carousel').not('.slick-initialized').each(function() {
                var $carousel = $(this);
                var $scope = $carousel.closest('.elementor-widget');
                var rawData = $carousel.attr('data-slick');
                var settings = rawData ? JSON.parse(rawData) : {};
                var colsDesktop = ( settings.slidesToShow !== undefined && settings.slidesToShow !== '' ) ? parseInt(settings.slidesToShow, 10) : 1,
                    colsTablet   = ( settings.columnsTablet !== undefined && settings.columnsTablet !== '' ) ? parseInt(settings.columnsTablet, 10) : ( colsDesktop > 1 ? colsDesktop : 1 ),
                    colsMobile   = ( settings.columnsMobile !== undefined && settings.columnsMobile !== '' ) ? parseInt(settings.columnsMobile, 10) : 1,
                    slidesToScroll = settings.slidesToScroll ? parseInt(settings.slidesToScroll, 10) : 1,
                    slideEffect  = $carousel.attr('data-slide-effect') || 'slide';

                $carousel.slick({
                    rtl: !! settings.rtl,
                    infinite: settings.infinite !== false,
                    speed: settings.speed || 700,
                    arrows: !! settings.arrows,
                    dots: !! settings.dots,
                    autoplay: !! settings.autoplay,
                    autoplaySpeed: settings.autoplaySpeed || 4000,
                    pauseOnHover: settings.pauseOnHover !== false,
                    prevArrow: $scope.find('.ua-team-prev-arrow'),
                    nextArrow: $scope.find('.ua-team-next-arrow'),
                    appendDots: $scope.find('.ua-team-dots'),
                    slidesToShow: colsDesktop,
                    slidesToScroll: slidesToScroll,
                    fade: ( colsDesktop === 1 && slideEffect === 'fade' ),
                    responsive: [
                        {
                            breakpoint: 1024,
                            settings: {
                                slidesToShow: colsTablet,
                                slidesToScroll: ( slidesToScroll > colsTablet ? colsTablet : slidesToScroll ),
                                fade: ( colsTablet === 1 && slideEffect === 'fade' )
                            }
                        },
                        {
                            breakpoint: 767,
                            settings: {
                                slidesToShow: colsMobile,
                                slidesToScroll: 1,
                                fade: ( colsMobile === 1 && slideEffect === 'fade' )
                            }
                        }
                    ]
                });
            });
        }

        // Document Ready Fallback for Flip Box click triggers
        $('.ua-flip-box-click').off('click.uaFlipBoxDoc').on('click.uaFlipBoxDoc', function(e) {
            if ($(e.target).closest('.ua-flip-box-button, a[href]:not([href="#"])').length && !$(this).hasClass('ua-flip-box-active')) {
                return;
            }
            $(this).toggleClass('ua-flip-box-active --active');
        });
    });

} (jQuery, window));
