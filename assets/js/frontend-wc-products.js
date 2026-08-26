/**
 * UltraAddons - WooCommerce Products Widget Frontend Scripts
 *
 * @package UltraAddons
 * @version 2.0.3.5
 */
(function ($) {
    'use strict';

    var UA_WC_Products = {

        getAjaxUrl: function () {
            if (typeof uaWCProductsConfig !== 'undefined' && uaWCProductsConfig.ajax_url) {
                return uaWCProductsConfig.ajax_url;
            }
            if (typeof ULTRAADDONS_DATA !== 'undefined' && ULTRAADDONS_DATA.ajax_url) {
                return ULTRAADDONS_DATA.ajax_url;
            }
            return '/wp-admin/admin-ajax.php';
        },

        getNonce: function () {
            if (typeof uaWCProductsConfig !== 'undefined' && uaWCProductsConfig.nonce) {
                return uaWCProductsConfig.nonce;
            }
            return '';
        },

        init: function ($scope) {
            var self = this;
            var $wrapper = $scope.hasClass('ua-wc-products-wrapper') ? $scope : $scope.find('.ua-wc-products-wrapper');

            if (!$wrapper.length) {
                return;
            }

            self.initCategoryFilter($wrapper);
            self.initLoadMore($wrapper);
        },

        getSettings: function ($wrapper) {
            var raw = $wrapper.attr('data-settings');
            if (raw) {
                try {
                    return (typeof raw === 'string') ? JSON.parse(raw) : raw;
                } catch (e) {
                    return $wrapper.data('settings') || {};
                }
            }
            return $wrapper.data('settings') || {};
        },

        /**
         * 1. AJAX Category Filter
         */
        initCategoryFilter: function ($wrapper) {
            var self = this;
            var $grid = $wrapper.find('.ua-wc-products-grid');
            var $filterBtns = $wrapper.find('.ua-filter-btn');
            var $loadMoreBtn = $wrapper.find('.ua-load-more-btn');

            $filterBtns.off('click.uaFilter').on('click.uaFilter', function (e) {
                e.preventDefault();
                var $btn = $(this);

                if ($btn.hasClass('ua-active') || $grid.hasClass('ua-loading')) {
                    return;
                }

                $filterBtns.removeClass('ua-active');
                $btn.addClass('ua-active');

                var category = $btn.attr('data-cat') || $btn.data('cat') || 'all';
                var settings = self.getSettings($wrapper);
                $grid.addClass('ua-loading');

                var ajaxData = {
                    action: 'ua_wc_products_load_more',
                    nonce: self.getNonce(),
                    page: 1,
                    category: category,
                    settings: (typeof settings === 'string') ? settings : JSON.stringify(settings)
                };

                $.ajax({
                    url: self.getAjaxUrl(),
                    type: 'POST',
                    data: ajaxData,
                    success: function (res) {
                        $grid.removeClass('ua-loading');
                        if (res.success && res.data.html) {
                            $grid.html(res.data.html);

                            // Update or toggle Load More button
                            if ($loadMoreBtn.length) {
                                $loadMoreBtn.data('page', 1);
                                $loadMoreBtn.data('max-pages', res.data.max_pages);
                                if (res.data.max_pages > 1) {
                                    $loadMoreBtn.closest('.ua-load-more-wrapper').show();
                                    $loadMoreBtn.removeClass('ua-loading').show();
                                } else {
                                    $loadMoreBtn.closest('.ua-load-more-wrapper').hide();
                                }
                            }
                        } else {
                            var noMoreText = (typeof uaWCProductsConfig !== 'undefined' && uaWCProductsConfig.i18n && uaWCProductsConfig.i18n.no_more) ? uaWCProductsConfig.i18n.no_more : 'No products found.';
                            $grid.html('<p class="ua-wc-no-products">' + noMoreText + '</p>');
                            if ($loadMoreBtn.length) {
                                $loadMoreBtn.closest('.ua-load-more-wrapper').hide();
                            }
                        }
                    },
                    error: function () {
                        $grid.removeClass('ua-loading');
                    }
                });
            });
        },

        /**
         * 2. AJAX Load More Pagination
         */
        initLoadMore: function ($wrapper) {
            var self = this;
            var $grid = $wrapper.find('.ua-wc-products-grid');
            var $loadMoreBtn = $wrapper.find('.ua-load-more-btn');

            $loadMoreBtn.off('click.uaLoadMore').on('click.uaLoadMore', function (e) {
                e.preventDefault();
                var $btn = $(this);

                if ($btn.hasClass('ua-loading')) {
                    return;
                }

                var currentPage = parseInt($btn.data('page'), 10) || 1;
                var maxPages = parseInt($btn.data('max-pages'), 10) || 1;
                var nextPage = currentPage + 1;
                var activeCategory = $wrapper.find('.ua-filter-btn.ua-active').attr('data-cat') || $wrapper.find('.ua-filter-btn.ua-active').data('cat') || 'all';
                var settings = self.getSettings($wrapper);

                if (nextPage > maxPages) {
                    $btn.fadeOut();
                    return;
                }

                $btn.addClass('ua-loading');
                $btn.find('.ua-btn-text').hide();
                $btn.find('.ua-spinner').show();

                var ajaxData = {
                    action: 'ua_wc_products_load_more',
                    nonce: self.getNonce(),
                    page: nextPage,
                    category: activeCategory,
                    settings: (typeof settings === 'string') ? settings : JSON.stringify(settings)
                };

                $.ajax({
                    url: self.getAjaxUrl(),
                    type: 'POST',
                    data: ajaxData,
                    success: function (res) {
                        $btn.removeClass('ua-loading');
                        $btn.find('.ua-spinner').hide();
                        $btn.find('.ua-btn-text').show();

                        if (res.success && res.data.html) {
                            var $newItems = $(res.data.html).css('opacity', 0);
                            $grid.append($newItems);
                            $newItems.animate({ opacity: 1 }, 300);

                            $btn.data('page', nextPage);

                            if (nextPage >= res.data.max_pages) {
                                $btn.fadeOut(300, function () {
                                    var noMoreText = (typeof uaWCProductsConfig !== 'undefined' && uaWCProductsConfig.i18n && uaWCProductsConfig.i18n.no_more) ? uaWCProductsConfig.i18n.no_more : 'No more products to load';
                                    $btn.closest('.ua-load-more-wrapper').html('<p class="ua-wc-no-more">' + noMoreText + '</p>');
                                });
                            }
                        } else {
                            $btn.fadeOut();
                        }
                    },
                    error: function () {
                        $btn.removeClass('ua-loading');
                        $btn.find('.ua-spinner').hide();
                        $btn.find('.ua-btn-text').show();
                    }
                });
            });
        }
    };

    /**
     * Elementor Hook and Document Ready
     */
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/ultraaddons-wc-products.default', function ($scope) {
            UA_WC_Products.init($scope);
        });
        elementorFrontend.hooks.addAction('frontend/element_ready/ultraaddons_wc_products.default', function ($scope) {
            UA_WC_Products.init($scope);
        });
    });

    $(document).ready(function () {
        $('.ua-wc-products-wrapper').each(function () {
            UA_WC_Products.init($(this));
        });
    });

})(jQuery);
