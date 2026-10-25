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

                // Close Button Click (delegated for dynamically replaced panels)
                $this.off('click.uaCartClose', '.ua-mini-cart-close-btn').on('click.uaCartClose', '.ua-mini-cart-close-btn', function (e) {
                    e.preventDefault();
                    $this.removeClass('ua-is-open');
                    $('body').removeClass('ua-mini-cart-open-body');
                });

                // Backdrop Click (for Sidebar)
                $this.off('click.uaCartBackdrop', '.ua-mini-cart-backdrop').on('click.uaCartBackdrop', '.ua-mini-cart-backdrop', function (e) {
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
                e.stopPropagation();

                var $btn = $(this);
                var $item = $btn.closest('.ua-mini-cart-item');
                var removeUrl = $btn.attr('href');
                var cartItemKey = $btn.data('cart_item_key');

                if (!removeUrl || removeUrl === '#' || $item.hasClass('ua-item-removing')) {
                    return;
                }

                $item.addClass('ua-item-removing');

                $.ajax({
                    type: 'GET',
                    url: removeUrl,
                    dataType: 'html',
                    success: function (responseHtml) {
                        var $response = $('<div>').append($.parseHTML(responseHtml));

                        // 1. Sync Mini Cart panels (Drawer / Dropdown contents)
                        var $newFragmentsWrap = $response.find('.ua-mini-cart-fragments-wrap');
                        if ($newFragmentsWrap.length) {
                            $('.ua-mini-cart-fragments-wrap').each(function (i) {
                                var $matching = $newFragmentsWrap.eq(i);
                                if ($matching.length) {
                                    $(this).replaceWith($matching);
                                }
                            });
                        } else {
                            // Fallback: smoothly slide up and remove this item
                            $item.slideUp(200, function () {
                                $(this).remove();
                                var $list = $('.ua-mini-cart-items-list');
                                if ($list.length && $list.children('.ua-mini-cart-item').length === 0) {
                                    $('.ua-mini-cart-footer').hide();
                                    $('.ua-mini-cart-body').html(
                                        '<div class="ua-mini-cart-empty-state"><div class="ua-mini-cart-empty-icon"><svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg></div><p class="ua-mini-cart-empty-msg">No products in the cart.</p></div>'
                                    );
                                }
                            });
                        }

                        // 2. Sync Mini Cart badges and prices
                        var $newBadge = $response.find('.ua-mini-cart-badge');
                        var $newPrice = $response.find('.ua-mini-cart-price');
                        if ($newBadge.length) {
                            $('.ua-mini-cart-badge').replaceWith($newBadge);
                        }
                        if ($newPrice.length) {
                            $('.ua-mini-cart-price').replaceWith($newPrice);
                        }

                        // 3. Sync Woo Cart page widget if present on the page
                        var $wooCartWrap = $('.ua-woo-cart-wrapper');
                        if ($wooCartWrap.length) {
                            var $newEmpty = $response.find('.ua-woo-cart-empty');
                            var $standardEmpty = $response.find('.woocommerce-cart-empty, .cart-empty');

                            if ($newEmpty.length) {
                                $wooCartWrap.find('.ua-woo-cart-main').hide();
                                var $currentEmpty = $wooCartWrap.find('.ua-woo-cart-empty');
                                if ($currentEmpty.length) {
                                    $currentEmpty.replaceWith($newEmpty).show();
                                } else {
                                    $wooCartWrap.append($newEmpty);
                                }
                            } else if ($standardEmpty.length && !$response.find('.ua-woo-cart-table').length) {
                                $wooCartWrap.find('.ua-woo-cart-main').hide();
                                $wooCartWrap.find('.ua-woo-cart-empty').show();
                            } else {
                                var $newTable = $response.find('.ua-woo-cart-table-wrap');
                                var $newActions = $response.find('.ua-woo-cart-actions');
                                var $newTotals = $response.find('.ua-woo-cart-totals');

                                if ($newTable.length) {
                                    $wooCartWrap.find('.ua-woo-cart-table-wrap').replaceWith($newTable);
                                }
                                if ($newActions.length) {
                                    $wooCartWrap.find('.ua-woo-cart-actions').replaceWith($newActions);
                                }
                                if ($newTotals.length) {
                                    $wooCartWrap.find('.ua-woo-cart-totals').replaceWith($newTotals);
                                }
                            }

                            var $newNotices = $response.find('.woocommerce-notices-wrapper');
                            if ($newNotices.length) {
                                var $currentNotices = $wooCartWrap.find('.woocommerce-notices-wrapper');
                                if ($currentNotices.length) {
                                    $currentNotices.replaceWith($newNotices);
                                } else {
                                    $wooCartWrap.prepend($newNotices);
                                }
                            }
                        }

                        // 4. Trigger WooCommerce events
                        $(document.body).trigger('removed_from_cart', [null, cartItemKey]);
                        $(document.body).trigger('updated_wc_div');
                        $(document.body).trigger('updated_cart_totals');
                        $(document.body).trigger('wc_fragment_refresh');

                        // 5. Re-bind mini cart listeners
                        UltraAddonsMiniCart.init();
                    },
                    error: function () {
                        window.location.href = removeUrl;
                    }
                });
            });
        },

        updateFragments: function () {
            // Re-bind events whenever WooCommerce refreshes fragments or cart updates
            $(document.body).on('wc_fragments_refreshed wc_fragments_loaded added_to_cart removed_from_cart updated_wc_div updated_cart_totals', function () {
                UltraAddonsMiniCart.init();
            });
        }
    };

    // Expose globally for cross-widget synchronization (e.g. Woo Cart widget)
    window.UltraAddonsMiniCart = UltraAddonsMiniCart;

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
