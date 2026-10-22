/**
 * UltraAddons Woo Cart Page Widget Frontend Script
 *
 * Handles AJAX cart quantity stepper, auto-update, item removal,
 * coupon application, and Elementor editor preview re-init.
 *
 * @package UltraAddons
 * @since 3.0.1
 */

(function ($) {
    'use strict';

    var UAWooCart = {
        debounceTimer: null,

        /**
         * Initialize all cart widgets on the page
         */
        init: function ($scope) {
            var $wrapper = $scope && $scope.length ? $scope.find('.ua-woo-cart-wrapper') : $('.ua-woo-cart-wrapper');
            if (!$wrapper.length) {
                return;
            }

            $wrapper.each(function () {
                var $this = $(this);
                UAWooCart.initSticky($this);
                UAWooCart.bindEvents($this);
            });
        },

        /**
         * Initialize sticky right column offset
         */
        initSticky: function ($wrapper) {
            var $sticky = $wrapper.find('.ua-woo-cart-right.ua-sticky');
            if ($sticky.length) {
                var offset = $sticky.data('sticky-offset') || 30;
                $sticky[0].style.setProperty('--ua-sticky-offset', parseInt(offset, 10) + 'px');
            }
        },

        /**
         * Bind interactive events using delegation
         */
        bindEvents: function ($wrapper) {
            // Prevent double-binding
            if ($wrapper.data('ua-cart-initialized')) {
                return;
            }
            $wrapper.data('ua-cart-initialized', true);

            // Quantity Stepper: Minus
            $wrapper.on('click', '.ua-qty-minus', function (e) {
                e.preventDefault();
                UAWooCart.handleQuantityStep($(this), -1);
            });

            // Quantity Stepper: Plus
            $wrapper.on('click', '.ua-qty-plus', function (e) {
                e.preventDefault();
                UAWooCart.handleQuantityStep($(this), 1);
            });

            // Quantity input manual typing
            $wrapper.on('input change', '.ua-qty-input', function () {
                var $wrap = $(this).closest('.ua-woo-cart-wrapper');
                $wrap.find('.ua-cart-update-btn').prop('disabled', false).removeAttr('disabled');
                UAWooCart.triggerAutoUpdate($wrap);
            });

            // Remove Item (×) AJAX
            $wrapper.on('click', '.ua-cart-remove-btn', function (e) {
                e.preventDefault();
                UAWooCart.handleRemoveItem($(this));
            });

            // Coupon: Apply Button Click
            $wrapper.on('click', '.ua-coupon-apply-btn', function (e) {
                e.preventDefault();
                UAWooCart.handleApplyCoupon($(this));
            });

            // Coupon: Enter key in input
            $wrapper.on('keydown', '.ua-coupon-input', function (e) {
                if (e.which === 13) {
                    e.preventDefault();
                    UAWooCart.handleApplyCoupon($(this).siblings('.ua-coupon-apply-btn'));
                }
            });

            // Manual "Update Cart" Button Click
            $wrapper.on('click', '.ua-cart-update-btn', function (e) {
                e.preventDefault();
                var $wrap = $(this).closest('.ua-woo-cart-wrapper');
                UAWooCart.performCartUpdate($wrap);
            });
        },

        /**
         * Step quantity input by +/- direction
         */
        handleQuantityStep: function ($btn, direction) {
            var $wrap = $btn.closest('.ua-woo-cart-wrapper');
            var $input = $btn.siblings('.ua-qty-input');
            if (!$input.length) {
                return;
            }

            var currentVal = parseFloat($input.val()) || 0;
            var step = parseFloat($input.attr('step')) || 1;
            var min = parseFloat($input.attr('min')) || 1;
            var max = parseFloat($input.attr('max'));
            var newVal = currentVal + (direction * step);

            if (newVal < min) {
                newVal = min;
            }
            if (!isNaN(max) && newVal > max) {
                newVal = max;
            }

            if (newVal !== currentVal) {
                $input.val(newVal).trigger('change');
                $wrap.find('.ua-cart-update-btn').prop('disabled', false).removeAttr('disabled');
                UAWooCart.triggerAutoUpdate($wrap);
            }
        },

        /**
         * Trigger auto-update with debounce if enabled
         */
        triggerAutoUpdate: function ($wrapper) {
            var autoUpdate = $wrapper.data('auto-update');
            if (autoUpdate === 'no' || autoUpdate === false) {
                return;
            }

            clearTimeout(UAWooCart.debounceTimer);
            UAWooCart.debounceTimer = setTimeout(function () {
                UAWooCart.performCartUpdate($wrapper);
            }, 600);
        },

        /**
         * Submit cart update via AJAX
         */
        performCartUpdate: function ($wrapper) {
            var $form = $wrapper.find('.ua-woo-cart-form');
            var actionUrl = $form.attr('action') || window.location.href;

            UAWooCart.showLoading($wrapper);

            // Serialize current cart quantities
            var formData = $form.serialize();
            if (formData.indexOf('update_cart=') === -1) {
                formData += '&update_cart=Update+Cart';
            }

            $.ajax({
                type: 'POST',
                url: actionUrl,
                data: formData,
                dataType: 'html',
                success: function (response) {
                    UAWooCart.refreshFromHtml($wrapper, response);
                },
                error: function () {
                    // Fallback: reload page on unexpected error
                    window.location.reload();
                },
                complete: function () {
                    UAWooCart.hideLoading($wrapper);
                }
            });
        },

        /**
         * Handle AJAX remove item click
         */
        handleRemoveItem: function ($btn) {
            var $wrapper = $btn.closest('.ua-woo-cart-wrapper');
            var removeUrl = $btn.attr('href');

            if (!removeUrl || removeUrl === '#') {
                return;
            }

            UAWooCart.showLoading($wrapper);

            $.ajax({
                type: 'GET',
                url: removeUrl,
                dataType: 'html',
                success: function (response) {
                    UAWooCart.refreshFromHtml($wrapper, response);
                },
                error: function () {
                    window.location.href = removeUrl;
                },
                complete: function () {
                    UAWooCart.hideLoading($wrapper);
                }
            });
        },

        /**
         * Handle AJAX coupon apply
         */
        handleApplyCoupon: function ($btn) {
            var $wrapper = $btn.closest('.ua-woo-cart-wrapper');
            var $input = $wrapper.find('.ua-coupon-input');
            var couponCode = $.trim($input.val());

            if (!couponCode) {
                $input.addClass('ua-shake');
                setTimeout(function () {
                    $input.removeClass('ua-shake');
                }, 450);
                return;
            }

            var $form = $wrapper.find('.ua-woo-cart-form');
            var actionUrl = $form.attr('action') || window.location.href;

            UAWooCart.showLoading($wrapper);

            var formData = $form.serialize();
            formData += '&apply_coupon=Apply+coupon&coupon_code=' + encodeURIComponent(couponCode);

            $.ajax({
                type: 'POST',
                url: actionUrl,
                data: formData,
                dataType: 'html',
                success: function (response) {
                    UAWooCart.refreshFromHtml($wrapper, response);
                },
                error: function () {
                    window.location.reload();
                },
                complete: function () {
                    UAWooCart.hideLoading($wrapper);
                }
            });
        },

        /**
         * Refresh cart widget sections from HTML response
         */
        refreshFromHtml: function ($wrapper, responseHtml) {
            var $response = $('<div>').append($.parseHTML(responseHtml));

            // Check if cart is now empty
            var $newEmpty = $response.find('.ua-woo-cart-empty');
            var $standardEmpty = $response.find('.woocommerce-cart-empty, .cart-empty');

            if ($newEmpty.length) {
                $wrapper.find('.ua-woo-cart-main').hide();
                var $currentEmpty = $wrapper.find('.ua-woo-cart-empty');
                if ($currentEmpty.length) {
                    $currentEmpty.replaceWith($newEmpty).show();
                } else {
                    $wrapper.append($newEmpty);
                }
                $(document.body).trigger('wc_cart_emptied');
                return;
            } else if ($standardEmpty.length && !$response.find('.ua-woo-cart-table').length) {
                $wrapper.find('.ua-woo-cart-main').hide();
                $wrapper.find('.ua-woo-cart-empty').show();
                $(document.body).trigger('wc_cart_emptied');
                return;
            }

            // Replace Cart Table wrap
            var $newTable = $response.find('.ua-woo-cart-table-wrap');
            if ($newTable.length) {
                $wrapper.find('.ua-woo-cart-table-wrap').replaceWith($newTable);
            }

            // Replace Cart Actions (coupon, buttons)
            var $newActions = $response.find('.ua-woo-cart-actions');
            if ($newActions.length) {
                $wrapper.find('.ua-woo-cart-actions').replaceWith($newActions);
            }

            // Replace Cart Totals
            var $newTotals = $response.find('.ua-woo-cart-totals');
            if ($newTotals.length) {
                $wrapper.find('.ua-woo-cart-totals').replaceWith($newTotals);
            }

            // Replace Notices if present
            var $newNotices = $response.find('.woocommerce-notices-wrapper, .woocommerce-message, .woocommerce-error, .woocommerce-info');
            if ($newNotices.length) {
                $wrapper.find('.woocommerce-notices-wrapper').replaceWith($newNotices);
            }

            // Disable update cart button until new change
            $wrapper.find('.ua-cart-update-btn').prop('disabled', true).attr('disabled', 'disabled');

            // Trigger WooCommerce events for mini carts, fragments, and header carts
            $(document.body).trigger('updated_wc_div');
            $(document.body).trigger('updated_cart_totals');
            $(document.body).trigger('wc_fragment_refresh');

            // Re-initialize sticky positioning
            UAWooCart.initSticky($wrapper);
        },

        /**
         * Show loading state
         */
        showLoading: function ($wrapper) {
            $wrapper.find('.ua-woo-cart-table-wrap').addClass('ua-cart-loading');
            $wrapper.find('.ua-cart-update-btn').prop('disabled', true).attr('disabled', 'disabled');
        },

        /**
         * Hide loading state
         */
        hideLoading: function ($wrapper) {
            $wrapper.find('.ua-woo-cart-table-wrap').removeClass('ua-cart-loading');
        }
    };

    // Initialize on DOM ready
    $(document).ready(function () {
        UAWooCart.init();
    });

    // Re-initialize when WooCommerce updates cart fragments
    $(document.body).on('updated_wc_div updated_cart_totals', function () {
        $('.ua-woo-cart-wrapper').each(function () {
            $(this).data('ua-cart-initialized', false);
            UAWooCart.initSticky($(this));
            UAWooCart.bindEvents($(this));
        });
    });

    // Elementor frontend hook (for live editor preview & page loading)
    $(window).on('elementor/frontend/init', function () {
        if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
            elementorFrontend.hooks.addAction('frontend/element_ready/ultraaddons-woo-cart.default', function ($scope) {
                UAWooCart.init($scope);
            });
            elementorFrontend.hooks.addAction('frontend/element_ready/ua-woo-cart.default', function ($scope) {
                UAWooCart.init($scope);
            });
        }
    });

})(jQuery);
