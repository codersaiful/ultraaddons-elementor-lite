/**
 * UltraAddons - Post Carousel Frontend Script
 *
 * @package UltraAddons
 * @since 1.1.0.9
 */
;(function ($, w) {
    'use strict';

    var $window = $(w);

    $window.on('elementor/frontend/init', function () {
        var EF = elementorFrontend,
            EM = elementorModules;

        if (typeof EM === 'undefined' || typeof EM.frontend === 'undefined' || typeof EM.frontend.handlers === 'undefined') {
            return;
        }

        var Post_Carousel = EM.frontend.handlers.Base.extend({
            onInit: function () {
                this.run();
            },
            onChange: function () {
                this.run();
            },
            run: function () {
                var $scope = this.$element;
                var $wrapper = $scope.find('.ua-post-carousel-wrapper');
                if (!$wrapper.length) {
                    return;
                }

                var $slider = $wrapper.find('.ua-post-carousel-slider');
                if (!$slider.length) {
                    return;
                }

                var sliderEl = $slider[0];
                if (sliderEl.swiper && typeof sliderEl.swiper.destroy === 'function') {
                    sliderEl.swiper.destroy(true, false);
                }

                var rawConfig = $wrapper.attr('data-swiper-config');
                var config = {};
                try {
                    config = JSON.parse(rawConfig) || {};
                } catch (e) {
                    config = {};
                }

                var slidesPerView = parseInt(config.slidesPerView, 10) || 3;
                var slidesPerViewTablet = parseInt(config.slidesPerViewTablet, 10) || 2;
                var slidesPerViewMobile = parseInt(config.slidesPerViewMobile, 10) || 1;
                var spaceBetween = parseInt(config.spaceBetween, 10) || 24;
                var spaceBetweenTablet = parseInt(config.spaceBetweenTablet, 10) || 18;
                var spaceBetweenMobile = parseInt(config.spaceBetweenMobile, 10) || 12;
                var slidesPerGroup = parseInt(config.slidesPerGroup, 10) || 1;
                var effect = config.effect || 'slide';
                var isLoop = !!config.loop;

                var swiperOptions = {
                    direction: 'horizontal',
                    speed: parseInt(config.speed, 10) || 600,
                    effect: effect,
                    rewind: !isLoop,
                    loop: isLoop,
                    grabCursor: !!config.grabCursor,
                    slidesPerGroup: slidesPerGroup,
                    slidesPerView: slidesPerViewMobile,
                    spaceBetween: spaceBetweenMobile,
                    breakpoints: {
                        320: {
                            slidesPerView: slidesPerViewMobile,
                            spaceBetween: spaceBetweenMobile,
                            slidesPerGroup: slidesPerGroup
                        },
                        768: {
                            slidesPerView: slidesPerViewTablet,
                            spaceBetween: spaceBetweenTablet,
                            slidesPerGroup: slidesPerGroup
                        },
                        1024: {
                            slidesPerView: slidesPerView,
                            spaceBetween: spaceBetween,
                            slidesPerGroup: slidesPerGroup
                        }
                    }
                };

                if (effect === 'coverflow') {
                    swiperOptions.centeredSlides = true;
                    swiperOptions.coverflowEffect = {
                        rotate: 25,
                        stretch: 0,
                        depth: 80,
                        modifier: 1,
                        slideShadows: false
                    };
                } else if (effect === 'fade') {
                    swiperOptions.fadeEffect = {
                        crossFade: true
                    };
                }

                if (config.autoplay) {
                    swiperOptions.autoplay = {
                        delay: parseInt(config.autoplayDelay, 10) || 3500,
                        disableOnInteraction: false,
                        pauseOnMouseEnter: !!config.pauseOnHover
                    };
                }

                // Navigation Arrows
                var $prevBtn = $wrapper.find('.ua-carousel-nav-prev');
                var $nextBtn = $wrapper.find('.ua-carousel-nav-next');
                if ($prevBtn.length && $nextBtn.length) {
                    swiperOptions.navigation = {
                        prevEl: $prevBtn[0],
                        nextEl: $nextBtn[0]
                    };
                }

                // Pagination Dots
                var $pagination = $wrapper.find('.ua-carousel-pagination');
                if ($pagination.length) {
                    swiperOptions.pagination = {
                        el: $pagination[0],
                        clickable: true,
                        type: config.paginationType || 'bullets',
                        dynamicBullets: !!config.dynamicBullets
                    };
                }

                var SwiperConstructor = null;
                if (typeof Swiper !== 'undefined') {
                    SwiperConstructor = Swiper;
                } else if (EF.utils && EF.utils.swiper) {
                    SwiperConstructor = EF.utils.swiper;
                }

                if (SwiperConstructor) {
                    // Elementor 3.x async swiper support
                    if (typeof SwiperConstructor === 'function') {
                        new SwiperConstructor(sliderEl, swiperOptions);
                    } else if (typeof SwiperConstructor.prototype !== 'undefined' && typeof SwiperConstructor.prototype.init === 'function') {
                        new SwiperConstructor(sliderEl, swiperOptions);
                    }
                } else if (typeof $.fn.swiper !== 'undefined') {
                    $slider.swiper(swiperOptions);
                }
            }
        });

        EF.hooks.addAction('frontend/element_ready/ultraaddons-post-carousel.default', function ($scope) {
            new Post_Carousel({ $element: $scope });
        });
    });
})(jQuery, window);
