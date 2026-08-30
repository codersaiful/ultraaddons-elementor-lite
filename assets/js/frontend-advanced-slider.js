/**
 * UltraAddons - Advanced Slider Frontend JS
 *
 * Initializes modern Swiper slider/carousel with dynamic settings,
 * responsive breakpoints, animations, and smooth controls.
 *
 * @package UltraAddons
 * @version 2.0.3.6
 */
(function ($) {
    'use strict';

    var UltraAddonsAdvancedSlider = function ($scope) {
        var $sliderContainer = $scope.find('.ua-adv-slider-container');
        if (!$sliderContainer.length) return;

        var $swiperElem = $sliderContainer.find('.ua-adv-slider');
        if (!$swiperElem.length) return;

        var rawSettings = $sliderContainer.data('slider-settings') || {};

        // Parse swiper options
        var swiperOptions = {
            direction: 'horizontal',
            speed: rawSettings.speed || 800,
            effect: rawSettings.effect || 'slide',
            loop: rawSettings.loop === true,
            grabCursor: true,
            watchSlidesProgress: true,
            slidesPerView: rawSettings.slidesPerView_mobile || 1,
            spaceBetween: rawSettings.spaceBetween_mobile || 0,
            breakpoints: {
                768: {
                    slidesPerView: rawSettings.slidesPerView_tablet || 1,
                    spaceBetween: rawSettings.spaceBetween_tablet || 0
                },
                1025: {
                    slidesPerView: rawSettings.slidesPerView_desktop || 1,
                    spaceBetween: rawSettings.spaceBetween_desktop || 0
                }
            }
        };

        // Effect-specific configs
        if (rawSettings.effect === 'fade') {
            swiperOptions.fadeEffect = { crossFade: true };
        } else if (rawSettings.effect === 'coverflow') {
            swiperOptions.coverflowEffect = {
                rotate: 30,
                stretch: 0,
                depth: 100,
                modifier: 1,
                slideShadows: true
            };
        } else if (rawSettings.effect === 'flip') {
            swiperOptions.flipEffect = {
                slideShadows: true,
                limitRotation: true
            };
        }

        // Autoplay
        if (rawSettings.autoplay) {
            swiperOptions.autoplay = {
                delay: rawSettings.autoplayDelay || 5000,
                disableOnInteraction: false,
                pauseOnMouseEnter: rawSettings.pauseOnHover === true
            };
        } else {
            swiperOptions.autoplay = false;
        }

        // Navigation
        var $arrowPrev = $sliderContainer.find('.ua-slider-arrow-prev');
        var $arrowNext = $sliderContainer.find('.ua-slider-arrow-next');
        if ($arrowPrev.length && $arrowNext.length) {
            swiperOptions.navigation = {
                prevEl: $arrowPrev[0],
                nextEl: $arrowNext[0]
            };
        }

        // Pagination
        var $pagination = $sliderContainer.find('.ua-slider-pagination');
        if ($pagination.length) {
            swiperOptions.pagination = {
                el: $pagination[0],
                clickable: true,
                type: rawSettings.paginationType || 'bullets',
                dynamicBullets: rawSettings.dynamicBullets === true
            };
        }

        // Initialize Swiper (Elementor Core Swiper constructor)
        var SwiperConstructor = typeof Swiper !== 'undefined' ? Swiper : (typeof elementorFrontend !== 'undefined' && elementorFrontend.utils && elementorFrontend.utils.swiper ? elementorFrontend.utils.swiper : null);

        if (SwiperConstructor) {
            if (typeof SwiperConstructor === 'function') {
                new SwiperConstructor($swiperElem[0], swiperOptions);
            } else if (typeof SwiperConstructor.init === 'function') {
                SwiperConstructor.init($swiperElem[0], swiperOptions);
            }
        }
    };

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/ultraaddons-advanced-slider.default', UltraAddonsAdvancedSlider);
    });

})(jQuery);
