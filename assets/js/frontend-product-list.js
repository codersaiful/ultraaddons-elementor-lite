/**
 * UltraAddons Woo Product List Frontend Script
 * 
 * Handles AJAX Load More, Infinite Scroll, and View Product (Quick View) Modal Popup.
 * 
 * @since 2.0.3
 * @package UltraAddons
 */
(function ($) {
    'use strict';

    var UltraAddonsProductList = {

        init: function ($scope) {
            var $wrapper = $scope ? $scope.find('.ua-product-list-wrapper') : $('.ua-product-list-wrapper');
            if (!$wrapper.length) return;

            $wrapper.each(function () {
                var $this = $(this);
                var pagination = $this.data('pagination') || $this.attr('data-pagination');

                // Initialize Load More Button
                if (pagination === 'load_more') {
                    UltraAddonsProductList.initLoadMore($this);
                } else if (pagination === 'infinite') {
                    UltraAddonsProductList.initInfiniteScroll($this);
                }
            });

            // Bind Quick View globally
            UltraAddonsProductList.initQuickView();
        },

        getAjaxConfig: function () {
            var ajaxUrl = (typeof uaProductListConfig !== 'undefined' && uaProductListConfig.ajax_url) ? uaProductListConfig.ajax_url : '/wp-admin/admin-ajax.php';
            var nonce   = (typeof uaProductListConfig !== 'undefined' && uaProductListConfig.nonce) ? uaProductListConfig.nonce : '';
            return {
                url: ajaxUrl,
                nonce: nonce
            };
        },

        /**
         * Load More Button Handler
         */
        initLoadMore: function ($wrapper) {
            var $btn = $wrapper.find('.ua-load-more-btn');
            if (!$btn.length) return;

            $btn.off('click.uaProductList').on('click.uaProductList', function (e) {
                e.preventDefault();

                if ($btn.hasClass('ua-loading')) return;

                var paged    = parseInt($wrapper.attr('data-paged'), 10) || 1;
                var maxPages = parseInt($wrapper.attr('data-max-pages'), 10) || 1;
                var settings = $wrapper.data('settings') || $wrapper.attr('data-settings');
                if (typeof settings === 'string') {
                    try { settings = JSON.parse(settings); } catch (err) {}
                }
                var nextPage = paged + 1;

                if (nextPage > maxPages) {
                    $btn.fadeOut();
                    return;
                }

                $btn.addClass('ua-loading');
                var config = UltraAddonsProductList.getAjaxConfig();

                $.ajax({
                    url: config.url,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'ua_product_list_load_more',
                        security: config.nonce,
                        paged: nextPage,
                        settings: settings
                    },
                    success: function (res) {
                        $btn.removeClass('ua-loading');

                        if (res.success && res.data && res.data.html) {
                            var $items = $(res.data.html).hide();
                            $wrapper.find('.ua-product-list-items-wrap').append($items);
                            $items.fadeIn(300);

                            $wrapper.attr('data-paged', nextPage);

                            if (res.data.no_more || nextPage >= maxPages) {
                                $btn.fadeOut();
                            }
                        } else {
                            $btn.fadeOut();
                        }
                    },
                    error: function () {
                        $btn.removeClass('ua-loading');
                    }
                });
            });
        },

        /**
         * Infinite Scroll Handler
         */
        initInfiniteScroll: function ($wrapper) {
            var $loader = $wrapper.find('.ua-infinite-scroll-loader');
            if (!$loader.length) return;

            var loading = false;
            var offset = parseInt($wrapper.data('offset'), 10) || -200;

            if ('IntersectionObserver' in window) {
                var observer = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting && !loading) {
                            var paged    = parseInt($wrapper.attr('data-paged'), 10) || 1;
                            var maxPages = parseInt($wrapper.attr('data-max-pages'), 10) || 1;
                            var nextPage = paged + 1;

                            if (nextPage > maxPages) {
                                $loader.fadeOut();
                                observer.disconnect();
                                return;
                            }

                            loading = true;
                            var settings = $wrapper.data('settings') || $wrapper.attr('data-settings');
                            if (typeof settings === 'string') {
                                try { settings = JSON.parse(settings); } catch (err) {}
                            }
                            var config = UltraAddonsProductList.getAjaxConfig();

                            $.ajax({
                                url: config.url,
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    action: 'ua_product_list_load_more',
                                    security: config.nonce,
                                    paged: nextPage,
                                    settings: settings
                                },
                                success: function (res) {
                                    loading = false;
                                    if (res.success && res.data && res.data.html) {
                                        var $items = $(res.data.html).hide();
                                        $wrapper.find('.ua-product-list-items-wrap').append($items);
                                        $items.fadeIn(300);

                                        $wrapper.attr('data-paged', nextPage);

                                        if (res.data.no_more || nextPage >= maxPages) {
                                            $loader.fadeOut();
                                            observer.disconnect();
                                        }
                                    } else {
                                        $loader.fadeOut();
                                        observer.disconnect();
                                    }
                                },
                                error: function () {
                                    loading = false;
                                }
                            });
                        }
                    });
                }, { rootMargin: '0px 0px ' + Math.abs(offset) + 'px 0px' });

                observer.observe($loader[0]);
            }
        },

        /**
         * View Product (Quick View) Modal Popup
         */
        initQuickView: function () {
            // Ensure Modal Container exists in DOM
            var $modal = $('.ua-quick-view-modal');
            if (!$modal.length) {
                $modal = $(
                    '<div class="ua-quick-view-modal">' +
                        '<div class="ua-quick-view-dialog">' +
                            '<button type="button" class="ua-quick-view-close" aria-label="Close">&times;</button>' +
                            '<div class="ua-quick-view-content">' +
                                '<div class="ua-infinite-scroll-loader"><div class="ua-infinite-spinner"></div></div>' +
                            '</div>' +
                        '</div>' +
                    '</div>'
                );
                $('body').append($modal);
            }

            // Click Trigger Handler
            $(document).off('click.uaQuickView', '.ua-quick-view-trigger, .ua-product-list-quick-view-button a')
                       .on('click.uaQuickView', '.ua-quick-view-trigger, .ua-product-list-quick-view-button a', function (e) {
                var $btn = $(this);
                var productId = $btn.data('product-id') || $btn.attr('data-product-id') || $btn.closest('[data-product-id]').data('product-id');
                
                if (!productId) {
                    // Fallback to navigating to the product page
                    return;
                }

                e.preventDefault();

                var $wrapper = $btn.closest('.elementor-widget-ultraaddons-product-list, .elementor-element');
                var widgetId = $wrapper.data('id') || $wrapper.attr('data-id') || '';

                $modal.find('.ua-quick-view-content').html('<div class="ua-infinite-scroll-loader"><div class="ua-infinite-spinner"></div></div>');
                $modal.attr('class', 'ua-quick-view-modal ua-qv-open' + (widgetId ? ' elementor-element-' + widgetId + ' ua-modal-' + widgetId : ''));
                $('body').css('overflow', 'hidden');

                var config = UltraAddonsProductList.getAjaxConfig();

                $.ajax({
                    url: config.url,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'ua_product_quick_view',
                        security: config.nonce,
                        product_id: productId
                    },
                    success: function (res) {
                        if (res.success && res.data && res.data.html) {
                            $modal.find('.ua-quick-view-content').html(res.data.html);

                            // Trigger WooCommerce variation form initialization if variable product
                            if (typeof wc_add_to_cart_variation_params !== 'undefined' && $.fn.wc_variation_form) {
                                $modal.find('.variations_form').each(function () {
                                    $(this).wc_variation_form();
                                });
                            }
                        } else {
                            $modal.find('.ua-quick-view-content').html('<p style="padding:40px;text-align:center;color:#64748b;">Product details could not be loaded.</p>');
                        }
                    },
                    error: function () {
                        $modal.find('.ua-quick-view-content').html('<p style="padding:40px;text-align:center;color:#ef4444;">Error loading product details.</p>');
                    }
                });
            });

            // Close Modal Handlers
            $modal.find('.ua-quick-view-close').off('click.uaClose').on('click.uaClose', function (e) {
                e.preventDefault();
                $modal.removeClass('ua-qv-open');
                $('body').css('overflow', '');
            });

            $modal.off('click.uaBackdrop').on('click.uaBackdrop', function (e) {
                if ($(e.target).hasClass('ua-quick-view-modal')) {
                    $modal.removeClass('ua-qv-open');
                    $('body').css('overflow', '');
                }
            });

            $(document).off('keyup.uaQVEsc').on('keyup.uaQVEsc', function (e) {
                if (e.key === 'Escape' || e.keyCode === 27) {
                    $modal.removeClass('ua-qv-open');
                    $('body').css('overflow', '');
                }
            });

            // AJAX Add to Cart from Quick View Modal (keeps customer inside popup)
            $(document).off('click.uaModalCartBtn', '.ua-quick-view-modal .single_add_to_cart_button')
                       .on('click.uaModalCartBtn', '.ua-quick-view-modal .single_add_to_cart_button', function (e) {
                var $button = $(this);
                var $form   = $button.closest('form.cart');

                if (!$form.length) {
                    return;
                }

                // If variable product and variation not selected or disabled
                if ($button.is('.disabled, .wc-variation-selection-needed')) {
                    return;
                }

                e.preventDefault();
                e.stopPropagation();

                if ($button.hasClass('loading') || $button.data('ua-locked')) {
                    return;
                }

                $button.data('ua-locked', true);
                $button.addClass('loading').removeClass('added ua-added');
                $button.find('.ua-cart-check-icon').remove();
                $form.find('.added_to_cart').remove();

                var formData  = $form.serializeArray();
                var productId = $form.find('input[name="product_id"]').val() || $button.val() || $form.find('button[name="add-to-cart"]').val() || $button.attr('value') || $form.find('input[name="add-to-cart"]').val();
                
                if (!productId) {
                    var $modalProduct = $form.closest('.ua-quick-view-product');
                    if ($modalProduct.length && $modalProduct.attr('id')) {
                        productId = $modalProduct.attr('id').replace('product-', '');
                    }
                }

                var dataObj = {
                    action: 'ua_ajax_add_to_cart',
                    security: UltraAddonsProductList.getAjaxConfig().nonce,
                    product_id: productId
                };

                $.each(formData, function (i, field) {
                    if (field.name !== 'add-to-cart') {
                        dataObj[field.name] = field.value;
                    }
                });

                delete dataObj['add-to-cart'];

                var config = UltraAddonsProductList.getAjaxConfig();

                $.ajax({
                    url: config.url,
                    type: 'POST',
                    dataType: 'json',
                    data: dataObj,
                    success: function (res) {
                        $button.removeClass('loading');
                        $button.data('ua-locked', false);

                        if (res && (res.fragments || (res.data && res.data.fragments) || res.cart_hash || (res.data && res.data.cart_hash) || res.success)) {
                            var fragments = res.fragments || (res.data && res.data.fragments);
                            var cartHash  = res.cart_hash  || (res.data && res.data.cart_hash);

                            // Add tick checkmark
                            $button.addClass('added ua-added');
                            $button.find('.ua-cart-check-icon').remove();
                            $button.append(' <span class="ua-cart-check-icon" style="display:inline-flex;align-items:center;margin-left:6px;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg></span>');

                            // 1. Direct DOM fragment replacements (updates mini carts immediately)
                            if (fragments) {
                                $.each(fragments, function (key, value) {
                                    $(key).replaceWith(value);
                                });
                            }

                            // 2. Trigger WooCommerce core fragment refresh events
                            $(document.body).trigger('added_to_cart', [fragments, cartHash, $button]);
                            $(document.body).trigger('wc_fragments_refreshed');
                            $(document.body).trigger('wc_fragments_loaded');
                            $(document.body).trigger('wc_fragment_refresh');

                            // 3. Update sessionStorage cache if available
                            if (typeof wc_cart_fragments_params !== 'undefined' && window.sessionStorage && fragments) {
                                try {
                                    sessionStorage.setItem(wc_cart_fragments_params.fragment_name, JSON.stringify(fragments));
                                    sessionStorage.setItem('wc_cart_hash', cartHash);
                                    sessionStorage.setItem('wc_fragments_created', (new Date()).getTime());
                                } catch (err) {}
                            }

                            // Ensure no extra separate button is shown (only the tick icon on Add to Cart)
                            $form.find('.added_to_cart').remove();
                            $('.ua-quick-view-modal .added_to_cart').remove();
                            setTimeout(function () {
                                $form.find('.added_to_cart').remove();
                                $('.ua-quick-view-modal .added_to_cart').remove();
                            }, 100);
                        } else if (res && res.data && res.data.product_url) {
                            window.location = res.data.product_url;
                        }
                    },
                    error: function () {
                        $button.removeClass('loading');
                        $button.data('ua-locked', false);
                    }
                });
            });

            // Prevent standard form submission from triggering double submit / page reload
            $(document).off('submit.uaModalCart', '.ua-quick-view-modal form.cart')
                       .on('submit.uaModalCart', '.ua-quick-view-modal form.cart', function (e) {
                e.preventDefault();
            });
        }
    };

    // DOM Ready
    $(document).ready(function () {
        UltraAddonsProductList.init();
    });

    // Elementor Hooks
    $(window).on('elementor/frontend/init', function () {
        if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
            elementorFrontend.hooks.addAction('frontend/element_ready/ultraaddons-product-list.default', function ($scope) {
                UltraAddonsProductList.init($scope);
            });
            elementorFrontend.hooks.addAction('frontend/element_ready/Product_List.default', function ($scope) {
                UltraAddonsProductList.init($scope);
            });
        }
    });

})(jQuery);
