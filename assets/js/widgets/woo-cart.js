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
            // Unbind any previous namespaced events on this wrapper first to guarantee no duplicate listeners
            $wrapper.off('.uacart');

            // Quantity Stepper: Minus
            $wrapper.on('click.uacart', '.ua-qty-minus', function (e) {
                e.preventDefault();
                UAWooCart.handleQuantityStep($(this), -1);
            });

            // Quantity Stepper: Plus
            $wrapper.on('click.uacart', '.ua-qty-plus', function (e) {
                e.preventDefault();
                UAWooCart.handleQuantityStep($(this), 1);
            });

            // Quantity input manual typing
            $wrapper.on('input.uacart change.uacart', '.ua-qty-input', function () {
                var $wrap = $(this).closest('.ua-woo-cart-wrapper');
                UAWooCart.triggerAutoUpdate($wrap);
            });

            // Remove Item (×) AJAX
            $wrapper.on('click.uacart', '.ua-cart-remove-btn', function (e) {
                e.preventDefault();
                UAWooCart.handleRemoveItem($(this));
            });

            // Coupon: Apply Button Click
            $wrapper.on('click.uacart', '.ua-coupon-apply-btn', function (e) {
                e.preventDefault();
                UAWooCart.handleApplyCoupon($(this));
            });

            // Coupon: Enter key in input
            $wrapper.on('keydown.uacart', '.ua-coupon-input', function (e) {
                if (e.which === 13) {
                    e.preventDefault();
                    UAWooCart.handleApplyCoupon($(this).siblings('.ua-coupon-apply-btn'));
                }
            });

            // Coupon: Remove Link Click [Remove]
            $wrapper.on('click.uacart', '.woocommerce-remove-coupon', function (e) {
                e.preventDefault();
                UAWooCart.handleRemoveCoupon($(this));
            });

            // Prevent natural form submission (everything is handled via AJAX)
            $wrapper.on('submit.uacart', '.ua-woo-cart-form', function (e) {
                e.preventDefault();
            });

        },

        /**
         * Step quantity input by +/- direction
         */
        handleQuantityStep: function ($btn, direction) {
            var $wrap = $btn.closest('.ua-woo-cart-wrapper');

            // Prevent spam clicks while an AJAX update is already in progress
            if ($wrap.find('.ua-woo-cart-table-wrap').hasClass('ua-cart-loading')) {
                return;
            }

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
                // Set the value directly without triggering redundant change event
                $input.val(newVal);
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
            var actionUrl = window.location.href;

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
            var actionUrl = window.location.href;

            UAWooCart.showLoading($wrapper);

            // Serialize all form fields except the coupon input to prevent duplicate fields
            var formParams = $form.find(':input:not(.ua-coupon-input)').serialize();
            if (formParams) {
                formParams += '&';
            }
            formParams += 'coupon_code=' + encodeURIComponent(couponCode) + '&apply_coupon=Apply+coupon';

            $.ajax({
                type: 'POST',
                url: actionUrl,
                data: formParams,
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
         * Handle AJAX coupon removal
         */
        handleRemoveCoupon: function ($btn) {
            var $wrapper = $btn.closest('.ua-woo-cart-wrapper');
            var couponCode = $btn.data('coupon') || '';
            var removeUrl = $btn.attr('href') || '';

            // If coupon code is not in data attribute, extract from URL
            if (!couponCode && removeUrl) {
                var match = removeUrl.match(/[?&]remove_coupon=([^&#]*)/);
                if (match && match[1]) {
                    couponCode = decodeURIComponent(match[1]);
                }
            }

            if (!couponCode) {
                return;
            }

            UAWooCart.showLoading($wrapper);

            // Send request to current URL with remove_coupon parameter to avoid redirecting to default cart page
            var currentUrl = window.location.href;
            var requestUrl = currentUrl + (currentUrl.indexOf('?') !== -1 ? '&' : '?') + 'remove_coupon=' + encodeURIComponent(couponCode);

            $.ajax({
                type: 'GET',
                url: requestUrl,
                dataType: 'html',
                success: function (response) {
                    UAWooCart.refreshFromHtml($wrapper, response);
                },
                error: function () {
                    if (removeUrl) {
                        window.location.href = removeUrl;
                    } else {
                        window.location.reload();
                    }
                },
                complete: function () {
                    UAWooCart.hideLoading($wrapper);
                }
            });
        },

        /**
         * Synchronize header/page mini carts and external fragments from response HTML
         */
        syncExternalCarts: function ($response) {
            // 1. Sync UltraAddons Mini Cart containers
            var $newMiniCarts = $response.find('.ua-mini-cart-container');
            if ($newMiniCarts.length) {
                $('.ua-mini-cart-container').each(function (index) {
                    var $matchingNew = $newMiniCarts.eq(index);
                    if ($matchingNew.length) {
                        var wasOpen = $(this).hasClass('ua-is-open');
                        if (wasOpen) {
                            $matchingNew.addClass('ua-is-open');
                        }
                        $(this).replaceWith($matchingNew);
                    }
                });
                if (window.UltraAddonsMiniCart && window.UltraAddonsMiniCart.init) {
                    window.UltraAddonsMiniCart.init();
                }
            } else {
                // If only badge/price elements exist
                var $newPrice = $response.find('.ua-mini-cart-price');
                var $newBadge = $response.find('.ua-mini-cart-badge');
                if ($newPrice.length) {
                    $('.ua-mini-cart-price').replaceWith($newPrice);
                }
                if ($newBadge.length) {
                    $('.ua-mini-cart-badge').replaceWith($newBadge);
                }
            }

            // 2. Sync Elementor Pro / theme header carts
            var externalSelectors = [
                '.elementor-menu-cart__toggle',
                '.elementor-menu-cart__container',
                'a.cart-contents',
                '.site-header-cart',
                '.ast-header-woo-cart',
                '.widget_shopping_cart_content'
            ];
            $.each(externalSelectors, function (_, selector) {
                var $newEl = $response.find(selector);
                if ($newEl.length) {
                    $(selector).replaceWith($newEl);
                }
            });
        },

        /**
         * Refresh cart widget sections from HTML response
         */
        refreshFromHtml: function ($wrapper, responseHtml) {
            var $response = $('<div>').append($.parseHTML(responseHtml));

            // Sync any header/page mini carts or counters immediately
            UAWooCart.syncExternalCarts($response);

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
                $(document.body).trigger('updated_wc_div');
                $(document.body).trigger('updated_cart_totals');
                $(document.body).trigger('wc_fragment_refresh');
                return;
            } else if ($standardEmpty.length && !$response.find('.ua-woo-cart-table').length) {
                $wrapper.find('.ua-woo-cart-main').hide();
                $wrapper.find('.ua-woo-cart-empty').show();
                $(document.body).trigger('wc_cart_emptied');
                $(document.body).trigger('updated_wc_div');
                $(document.body).trigger('updated_cart_totals');
                $(document.body).trigger('wc_fragment_refresh');
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
            var $newNotices = $response.find('.woocommerce-notices-wrapper');
            if ($newNotices.length) {
                var $currentNotices = $wrapper.find('.woocommerce-notices-wrapper');
                if ($currentNotices.length) {
                    $currentNotices.replaceWith($newNotices);
                } else {
                    var $oldMsg = $wrapper.find('.woocommerce-message, .woocommerce-error, .woocommerce-info');
                    if ($oldMsg.length) {
                        $oldMsg.replaceWith($newNotices);
                    } else {
                        $wrapper.prepend($newNotices);
                    }
                }
            } else {
                $wrapper.find('.woocommerce-notices-wrapper').empty();
            }


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

    // Re-initialize sticky positioning when WooCommerce cart updates
    $(document.body).on('updated_wc_div updated_cart_totals', function () {
        $('.ua-woo-cart-wrapper').each(function () {
            UAWooCart.initSticky($(this));
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
