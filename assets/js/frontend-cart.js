/**
 * UltraAddons WC Mini Cart Frontend Script
 * 
 * Handles Dropdown & Off-Canvas drawer toggles (Click & Hover),
 * AJAX item removal, and live WooCommerce cart fragments synchronization.
 * 
 * @since 2.0.3
 */
(function ($) {
    'use strict';

    var UltraAddonsMiniCart = {
        init: function ($scope) {
            var $container = $scope ? $scope.find('.ua-mini-cart-container') : $('.ua-mini-cart-container');
            if (!$container.length) return;

            $container.each(function () {
                var $this = $(this);
                var contentType = $this.data('content-type');
                var trigger = $this.data('trigger');
                var hoverTimer;

                // Clear previous event bindings
                $this.off('.uaCart');
                $this.find('.ua-mini-cart-toggle-btn').off('.uaCart');
                $this.find('.ua-mini-cart-close-btn').off('.uaCart');
                $this.find('.ua-mini-cart-backdrop').off('.uaCart');

                // Hover Trigger handling (Dropdown & Sidebar)
                if (trigger === 'hover' && contentType !== 'none') {
                    $this.on('mouseenter.uaCart', function () {
                        clearTimeout(hoverTimer);
                        $this.addClass('ua-is-open');
                        if (contentType === 'sidebar') {
                            $('body').addClass('ua-mini-cart-open-body');
                        }
                    }).on('mouseleave.uaCart', function () {
                        hoverTimer = setTimeout(function () {
                            $this.removeClass('ua-is-open');
                            if (contentType === 'sidebar') {
                                $('body').removeClass('ua-mini-cart-open-body');
                            }
                        }, 250);
                    });
                }

                // Click Trigger handling & Touch/Fallback
                $this.find('.ua-mini-cart-toggle-btn').on('click.uaCart', function (e) {
                    if (contentType === 'none') {
                        return true; // Navigate to cart page
                    }

                    e.preventDefault();
                    e.stopPropagation();

                    $this.toggleClass('ua-is-open');
                    if (contentType === 'sidebar') {
                        if ($this.hasClass('ua-is-open')) {
                            $('body').addClass('ua-mini-cart-open-body');
                        } else {
                            $('body').removeClass('ua-mini-cart-open-body');
                        }
                    }
                });

                // Close Button Click
                $this.find('.ua-mini-cart-close-btn').on('click.uaCart', function (e) {
                    e.preventDefault();
                    $this.removeClass('ua-is-open');
                    $('body').removeClass('ua-mini-cart-open-body');
                });

                // Backdrop Click (for Sidebar)
                $this.find('.ua-mini-cart-backdrop').on('click.uaCart', function (e) {
                    e.preventDefault();
                    $this.removeClass('ua-is-open');
                    $('body').removeClass('ua-mini-cart-open-body');
                });
            });

            // Global Outside Click listener for Dropdowns
            $(document).off('click.uaCartDropdown').on('click.uaCartDropdown', function (e) {
                if (!$(e.target).closest('.ua-mini-cart-container').length) {
                    $('.ua-mini-cart-container.ua-content-dropdown').removeClass('ua-is-open');
                }
            });

            // Global ESC key listener to close Sidebar / Dropdown
            $(document).off('keyup.uaCartESC').on('keyup.uaCartESC', function (e) {
                if (e.key === 'Escape' || e.keyCode === 27) {
                    $('.ua-mini-cart-container').removeClass('ua-is-open');
                    $('body').removeClass('ua-mini-cart-open-body');
                }
            });

            // Live AJAX Item Removal from Mini Cart
            $container.off('click.uaCartRemove', '.ua-mini-cart-remove-btn').on('click.uaCartRemove', '.ua-mini-cart-remove-btn', function (e) {
                e.preventDefault();
                var $btn = $(this);
                var $item = $btn.closest('.ua-mini-cart-item');
                var removeUrl = $btn.attr('href');
                var cartItemKey = $btn.data('cart_item_key');

                if (!cartItemKey) return;

                $item.addClass('ua-item-removing');

                $.ajax({
                    type: 'GET',
                    url: removeUrl,
                    dataType: 'html',
                    success: function () {
                        // Trigger WooCommerce fragments refresh
                        $(document.body).trigger('wc_fragment_refresh');
                        $(document.body).trigger('removed_from_cart', [null, cartItemKey]);
                    },
                    error: function () {
                        $item.removeClass('ua-item-removing');
                    }
                });
            });
        },

        updateFragments: function () {
            // Re-bind events whenever WooCommerce refreshes fragments
            $(document.body).on('wc_fragments_refreshed wc_fragments_loaded added_to_cart removed_from_cart', function () {
                UltraAddonsMiniCart.init();
            });
        }
    };

    // DOM Ready Initialization
    $(document).ready(function () {
        UltraAddonsMiniCart.init();
        UltraAddonsMiniCart.updateFragments();
    });

    // Elementor Preview / Frontend Initialization
    $(window).on('elementor/frontend/init', function () {
        if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
            elementorFrontend.hooks.addAction('frontend/element_ready/Cart.default', function ($scope) {
                UltraAddonsMiniCart.init($scope);
            });
            elementorFrontend.hooks.addAction('frontend/element_ready/ultraaddons-cart.default', function ($scope) {
                UltraAddonsMiniCart.init($scope);
            });
        }
    });

})(jQuery);
