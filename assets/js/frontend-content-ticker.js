/**
 * UltraAddons Content Ticker Engine
 * Supports Swiper Step-by-Step Slide (from Right/Left/Up/Down),
 * Continuous 60fps Marquee, and Typewriter animations.
 */

(function ($) {
    'use strict';

    var UAContentTicker = function ($scope) {
        var $wrapper = $scope.find('.ua-content-ticker-wrap');
        if (!$wrapper.length) {
            return;
        }

        var mode          = $wrapper.data('mode') || 'marquee';
        var direction     = $wrapper.data('direction') || 'left';
        var slideDir      = $wrapper.data('slide-direction') || 'right_to_left';
        var verticalDir   = $wrapper.data('vertical-direction') || 'up';
        var speed         = parseFloat($wrapper.data('speed')) || 28;
        var typingSpeed   = parseInt($wrapper.data('typing-speed'), 10) || 50;
        var autoplayDelay = (parseFloat($wrapper.data('autoplay-delay')) || 3.5) * 1000;
        var pauseOnHover  = $wrapper.data('pause-hover') === 'yes';

        var $container = $wrapper.find('.ua-content-ticker');
        var $trackWrap = $wrapper.find('.ua-ticker-track-wrap');
        var $track     = $wrapper.find('.ua-ticker-track');
        var $items     = $track.children('.ua-ticker-item');
        var totalItems = $items.length;

        if (totalItems === 0) {
            return;
        }

        /* ==================================================
           1. CONTINUOUS MARQUEE MODE (Seamless Loop)
           ================================================== */
        if (mode === 'marquee') {
            // Clone items to ensure seamless infinite crawl without blank gap
            if (!$track.data('marquee-cloned')) {
                var clonedHtml = $track.html();
                $track.append(clonedHtml);
                $track.data('marquee-cloned', true);
            }

            var animName = direction === 'right' ? 'ua-ticker-scroll-right' : 'ua-ticker-scroll-left';
            $track.css({
                'animation': animName + ' ' + speed + 's linear infinite'
            });

            if (pauseOnHover) {
                $container.on('mouseenter', function () {
                    $track.css('animation-play-state', 'paused');
                }).on('mouseleave', function () {
                    $track.css('animation-play-state', 'running');
                });
            }

            // Navigation for Marquee (shifts track slightly or toggles play/pause)
            $scope.find('.ua-ticker-next').on('click', function () {
                $track.css('animation-play-state', $track.css('animation-play-state') === 'paused' ? 'running' : 'paused');
            });
            $scope.find('.ua-ticker-prev').on('click', function () {
                $track.css('animation-play-state', $track.css('animation-play-state') === 'paused' ? 'running' : 'paused');
            });

            return;
        }

        /* ==================================================
           2. TYPEWRITER EFFECT
           ================================================== */
        if (mode === 'typing') {
            var currentTypeIdx = 0;
            var typingTimeout  = null;
            var typeTimer      = null;
            var isTypeHovered  = false;

            function showTypeItem(index) {
                if (index < 0) {
                    index = totalItems - 1;
                } else if (index >= totalItems) {
                    index = 0;
                }
                currentTypeIdx = index;

                $items.removeClass('ua-ticker-item-active');
                var $activeItem = $items.eq(currentTypeIdx);
                $activeItem.addClass('ua-ticker-item-active');

                runTypewriter($activeItem);
            }

            function runTypewriter($item) {
                clearTimeout(typingTimeout);
                clearTimeout(typeTimer);

                var $titleContainer = $item.find('.ua-ticker-item-title');
                var $targetElem     = $titleContainer.find('a').length ? $titleContainer.find('a') : $titleContainer.find('span');

                if (!$targetElem.data('full-text')) {
                    $targetElem.data('full-text', $targetElem.text().trim());
                }

                var fullText = $targetElem.data('full-text');
                $targetElem.html('');

                $titleContainer.find('.ua-ticker-cursor').remove();
                var $cursor = $('<span class="ua-ticker-cursor">|</span>');
                $titleContainer.append($cursor);

                var charIdx = 0;

                function typeChar() {
                    if (charIdx < fullText.length) {
                        $targetElem.text(fullText.substring(0, charIdx + 1));
                        charIdx++;
                        typingTimeout = setTimeout(typeChar, typingSpeed);
                    } else {
                        if (!isTypeHovered) {
                            typeTimer = setTimeout(function () {
                                showTypeItem(currentTypeIdx + 1);
                            }, autoplayDelay);
                        }
                    }
                }

                typeChar();
            }

            if (pauseOnHover) {
                $container.on('mouseenter', function () {
                    isTypeHovered = true;
                    clearTimeout(typeTimer);
                }).on('mouseleave', function () {
                    isTypeHovered = false;
                    typeTimer = setTimeout(function () {
                        showTypeItem(currentTypeIdx + 1);
                    }, autoplayDelay);
                });
            }

            $scope.find('.ua-ticker-next').on('click', function (e) {
                e.preventDefault();
                showTypeItem(currentTypeIdx + 1);
            });
            $scope.find('.ua-ticker-prev').on('click', function (e) {
                e.preventDefault();
                showTypeItem(currentTypeIdx - 1);
            });

            showTypeItem(0);
            return;
        }

        /* ==================================================
           3. SWIPER CAROUSEL (Horizontal Slide from Right, Vertical, Fade)
           ================================================== */
        var sliderEl = $trackWrap[0];
        if (!sliderEl) {
            return;
        }

        // Clean up previous instance when updating in Elementor Editor
        if (sliderEl.swiper && typeof sliderEl.swiper.destroy === 'function') {
            sliderEl.swiper.destroy(true, true);
        }

        var isVertical = (mode === 'vertical');
        var isFade     = (mode === 'fade');

        var swiperOptions = {
            direction: isVertical ? 'vertical' : 'horizontal',
            loop: totalItems > 1,
            speed: 550,
            effect: isFade ? 'fade' : 'slide',
            fadeEffect: {
                crossFade: true
            },
            slidesPerView: 1,
            spaceBetween: 0,
            autoHeight: false,
            grabCursor: true,
            observer: true,
            observeParents: true,
            observeSlideChildren: true,
            autoplay: {
                delay: autoplayDelay,
                disableOnInteraction: false,
                pauseOnMouseEnter: pauseOnHover,
            },
            navigation: {
                nextEl: $scope.find('.ua-ticker-next')[0],
                prevEl: $scope.find('.ua-ticker-prev')[0],
            }
        };

        var initSwiperInstance = function () {
            var SwiperConstructor = typeof Swiper !== 'undefined' ? Swiper : (typeof elementorFrontend !== 'undefined' && elementorFrontend.utils && elementorFrontend.utils.swiper ? elementorFrontend.utils.swiper : null);

            if (SwiperConstructor) {
                if (typeof SwiperConstructor === 'function') {
                    var inst = new SwiperConstructor(sliderEl, swiperOptions);
                    if (inst && typeof inst.then === 'function') {
                        inst.then(function (actualInst) {
                            sliderEl.swiper = actualInst;
                        });
                    } else {
                        sliderEl.swiper = inst;
                    }
                } else if (typeof SwiperConstructor.init === 'function') {
                    sliderEl.swiper = SwiperConstructor.init(sliderEl, swiperOptions);
                }
            }
        };

        initSwiperInstance();
    };

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/ultraaddons-content-ticker.default',
            UAContentTicker
        );
    });

    // Fallback on DOM ready
    $(document).ready(function () {
        if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/ultraaddons-content-ticker.default',
                UAContentTicker
            );
        } else {
            $('.ua-content-ticker-wrap').each(function () {
                UAContentTicker($(this).closest('.elementor-widget'));
            });
        }
    });

})(jQuery);
