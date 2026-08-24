/**
 * UltraAddons WooCommerce Product Carousel Frontend Script
 *
 * @package UltraAddons
 * @since 1.1.0.8
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

        var Product_Carousel = EM.frontend.handlers.Base.extend({
            onInit: function () {
                this.run();
            },
            onChange: function () {
                this.run();
            },
            run: function () {
                var $scope = this.$element;
                var $wrapper = $scope.find('.ua-product-carousel-wrapper');
                if (!$wrapper.length) {
                    return;
                }

                var $slider = $wrapper.find('.ua-product-carousel-slider');
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
                var spaceBetween = parseInt(config.spaceBetween, 10) || 20;
                var spaceBetweenTablet = parseInt(config.spaceBetweenTablet, 10) || 15;
                var spaceBetweenMobile = parseInt(config.spaceBetweenMobile, 10) || 10;
                var slidesPerGroup = parseInt(config.slidesPerGroup, 10) || 1;
                var effect = config.effect || 'slide';

                var swiperOptions = {
                    direction: 'horizontal',
                    speed: parseInt(config.speed, 10) || 600,
                    effect: effect,
                    rewind: true,
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
                }

                if (config.autoplay) {
                    swiperOptions.autoplay = {
                        delay: parseInt(config.autoplaySpeed, 10) || 3500,
                        disableOnInteraction: false,
                        pauseOnMouseEnter: !!config.pauseOnHover
                    };
                } else {
                    swiperOptions.autoplay = false;
                }

                if (config.arrows) {
                    var $prevBtn = $wrapper.find('.ua-swiper-button-prev');
                    var $nextBtn = $wrapper.find('.ua-swiper-button-next');
                    if ($prevBtn.length && $nextBtn.length) {
                        swiperOptions.navigation = {
                            prevEl: $prevBtn[0],
                            nextEl: $nextBtn[0]
                        };
                    }
                }

                if (config.dots) {
                    var $pagination = $wrapper.find('.ua-swiper-pagination');
                    if ($pagination.length) {
                        var dotsType = config.dotsType || 'bullets';
                        var paginationType = (dotsType === 'bullets' || dotsType === 'dynamic') ? 'bullets' : dotsType;

                        swiperOptions.pagination = {
                            el: $pagination[0],
                            clickable: true,
                            type: paginationType,
                            dynamicBullets: dotsType === 'dynamic'
                        };
                    }
                }

                var swiperInstance = null;
                if (typeof Swiper !== 'undefined') {
                    swiperInstance = new Swiper(sliderEl, swiperOptions);
                    sliderEl.swiper = swiperInstance;
                } else if (typeof EF.utils !== 'undefined' && EF.utils.swiper) {
                    new EF.utils.swiper(sliderEl, swiperOptions).then(function (inst) {
                        sliderEl.swiper = inst;
                    });
                }
            }
        });

        EF.hooks.addAction(
            'frontend/element_ready/ultraaddons-product-carousel.default',
            function ($scope) {
                EF.elementsHandler.addHandler(Product_Carousel, {
                    $element: $scope
                });
            }
        );
        EF.hooks.addAction(
            'frontend/element_ready/Product_Carousel.default',
            function ($scope) {
                EF.elementsHandler.addHandler(Product_Carousel, {
                    $element: $scope
                });
            }
        );
    });

    /**
     * 1-Click "Buy Now" Button Handler
     */
    $(document).on('click', '.ua-product-carousel-wrapper .ua-btn-buy-now', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var $btn = $(this);
        if ($btn.hasClass('loading')) {
            return;
        }

        var productId = parseInt($btn.data('product-id'), 10);
        var checkoutUrl = $btn.data('checkout-url') || $btn.attr('href');

        if (!productId) {
            window.location.href = checkoutUrl;
            return;
        }

        $btn.addClass('loading');

        var ajaxUrl = '';
        if (typeof wc_add_to_cart_params !== 'undefined' && wc_add_to_cart_params.wc_ajax_url) {
            ajaxUrl = wc_add_to_cart_params.wc_ajax_url.replace('%%endpoint%%', 'add_to_cart');
        } else {
            ajaxUrl = window.location.pathname + '?wc-ajax=add_to_cart';
        }

        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
                product_id: productId,
                quantity: 1
            },
            dataType: 'json',
            success: function (response) {
                if (response && response.error && response.product_url) {
                    window.location.href = response.product_url;
                    return;
                }

                if (response && response.fragments) {
                    $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $btn]);
                    $(document.body).trigger('wc_fragment_refresh');
                }

                window.location.href = checkoutUrl;
            },
            error: function () {
                window.location.href = $btn.attr('href') || checkoutUrl;
            },
            complete: function () {
                $btn.removeClass('loading');
            }
        });
    });

    /**
     * Live WooCommerce AJAX Add-to-cart Button Visual State
     */
    $(document.body).on('adding_to_cart', function (e, $btn) {
        if ($btn && $btn.hasClass('ua-btn-add-to-cart')) {
            $btn.addClass('loading');
        }
    });

    $(document.body).on('added_to_cart', function (e, fragments, cart_hash, $btn) {
        if ($btn && $btn.hasClass('ua-btn-add-to-cart')) {
            $btn.removeClass('loading').addClass('added');
            setTimeout(function () {
                $btn.removeClass('added');
            }, 2500);
        }
    });

})(jQuery, window);
