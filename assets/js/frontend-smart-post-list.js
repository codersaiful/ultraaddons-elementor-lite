/**
 * UltraAddons - Smart Post List Widget Frontend Script
 *
 * Handles AJAX Category Filtering tabs, Live Search with debounce,
 * and Prev/Next page navigation with loading spinner states.
 *
 * @package UltraAddons
 * @version 1.1.0.9
 */
(function ($) {
    'use strict';

    /**
     * Debounce helper
     *
     * @param {Function} func
     * @param {number} wait
     * @returns {Function}
     */
    function debounce(func, wait) {
        var timeout;
        return function () {
            var context = this;
            var args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                func.apply(context, args);
            }, wait);
        };
    }

    /**
     * Main Handler for Smart Post List Widget
     *
     * @param {jQuery} $scope
     * @param {jQuery} $
     */
    var UltraSmartPostListHandler = function ($scope, $) {
        var $wrapper = $scope.find('.ua-smart-post-list-wrapper');
        if (!$wrapper.length) {
            return;
        }

        // Avoid double initialization
        if ($wrapper.data('ua-smart-initialized')) {
            return;
        }
        $wrapper.data('ua-smart-initialized', true);

        // Elements
        var $topBar = $wrapper.find('.ua-smart-top-bar');
        var $catTabs = $topBar.find('.ua-smart-cat-tab');
        var $searchInput = $topBar.find('.ua-smart-search-input');
        var $searchClear = $topBar.find('.ua-smart-search-clear');
        var $prevBtn = $topBar.find('.ua-smart-nav-prev');
        var $nextBtn = $topBar.find('.ua-smart-nav-next');
        var $featuredCol = $wrapper.find('.ua-smart-featured-col');
        var $listWrap = $wrapper.find('.ua-smart-list-wrap');
        var $loading = $wrapper.find('.ua-smart-loading');

        // State variables
        var activeCatId = parseInt($wrapper.attr('data-cat-id'), 10) || 0;
        var currentPage = parseInt($wrapper.attr('data-paged'), 10) || 1;
        var maxPages = parseInt($wrapper.attr('data-max-pages'), 10) || 1;
        var searchTerm = '';
        var isFetching = false;

        // Configuration
        var config = window.uaSmartPostListConfig || {
            ajax_url: '/wp-admin/admin-ajax.php',
            nonce: ''
        };

        /**
         * Update Navigation Buttons UI
         *
         * @param {boolean} hasPrev
         * @param {boolean} hasNext
         */
        function updateNavState(hasPrev, hasNext) {
            if (hasPrev) {
                $prevBtn.removeClass('disabled').prop('disabled', false);
            } else {
                $prevBtn.addClass('disabled').prop('disabled', true);
            }

            if (hasNext) {
                $nextBtn.removeClass('disabled').prop('disabled', false);
            } else {
                $nextBtn.addClass('disabled').prop('disabled', true);
            }
        }

        /**
         * Execute AJAX Post Query
         *
         * @param {number} targetPage
         * @param {Function} [onComplete]
         */
        function fetchPosts(targetPage, onComplete) {
            if (isFetching) {
                return;
            }

            var settingsData = $wrapper.attr('data-settings') || '{}';

            isFetching = true;
            $wrapper.addClass('is-loading');
            $loading.stop(true, true).fadeIn(180);
            $prevBtn.prop('disabled', true);
            $nextBtn.prop('disabled', true);

            var postData = {
                action: 'ua_smart_post_list_query',
                nonce: config.nonce,
                paged: targetPage,
                category_id: activeCatId,
                search_term: searchTerm,
                settings: settingsData
            };

            $.ajax({
                url: config.ajax_url,
                type: 'POST',
                data: postData,
                dataType: 'json',
                success: function (res) {
                    if (res && res.success && res.data) {
                        var data = res.data;
                        currentPage = parseInt(data.paged, 10) || 1;
                        maxPages = parseInt(data.max_pages, 10) || 1;

                        $wrapper.attr('data-paged', currentPage);
                        $wrapper.attr('data-max-pages', maxPages);

                        // Smooth transition for Featured Col
                        if ($featuredCol.length) {
                            if (data.html_featured) {
                                $featuredCol.html(data.html_featured).show();
                            } else {
                                $featuredCol.empty().hide();
                            }
                        }

                        // Smooth transition for List Wrap
                        if ($listWrap.length) {
                            $listWrap.html(data.html_list);
                        }

                        // Update Navigation Buttons
                        updateNavState(data.has_prev, data.has_next);
                    }
                },
                error: function (xhr, status, error) {
                    // Fail gracefully
                    console.error('UltraAddons Smart Post List AJAX Error:', error);
                },
                complete: function () {
                    isFetching = false;
                    $loading.stop(true, true).fadeOut(200);
                    $wrapper.removeClass('is-loading');

                    // Restore nav state for current page
                    updateNavState(currentPage > 1, currentPage < maxPages);

                    if (typeof onComplete === 'function') {
                        onComplete();
                    }
                }
            });
        }

        // ==========================================
        // 1. Category Filter Tabs Event Handling
        // ==========================================
        $catTabs.on('click', function (e) {
            e.preventDefault();
            var $this = $(this);

            if ($this.hasClass('active') || isFetching) {
                return;
            }

            $catTabs.removeClass('active');
            $this.addClass('active');

            activeCatId = parseInt($this.attr('data-cat-id'), 10) || 0;
            $wrapper.attr('data-cat-id', activeCatId);
            currentPage = 1;

            fetchPosts(1);
        });

        // ==========================================
        // 2. Live Search Event Handling (Debounced)
        // ==========================================
        var handleSearchInput = debounce(function () {
            var val = $.trim($searchInput.val());
            if (val === searchTerm) {
                return;
            }

            searchTerm = val;
            currentPage = 1;

            if (searchTerm.length > 0) {
                $searchClear.show();
            } else {
                $searchClear.hide();
            }

            fetchPosts(1);
        }, 350);

        $searchInput.on('input keyup', handleSearchInput);

        // Search clear button
        $searchClear.on('click', function (e) {
            e.preventDefault();
            $searchInput.val('');
            $searchClear.hide();
            searchTerm = '';
            currentPage = 1;
            fetchPosts(1);
        });

        // ==========================================
        // 3. Navigation Prev / Next Page Buttons
        // ==========================================
        $prevBtn.on('click', function (e) {
            e.preventDefault();
            if ($(this).hasClass('disabled') || isFetching || currentPage <= 1) {
                return;
            }
            fetchPosts(currentPage - 1);
        });

        $nextBtn.on('click', function (e) {
            e.preventDefault();
            if ($(this).hasClass('disabled') || isFetching || currentPage >= maxPages) {
                return;
            }
            fetchPosts(currentPage + 1);
        });
    };

    /**
     * Elementor Frontend Hook Registration
     */
    function registerElementorHooks() {
        if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
            elementorFrontend.hooks.addAction('frontend/element_ready/ultraaddons-smart-post-list.default', UltraSmartPostListHandler);
            elementorFrontend.hooks.addAction('frontend/element_ready/Smart_Post_List.default', UltraSmartPostListHandler);
            elementorFrontend.hooks.addAction('frontend/element_ready/smart-post-list.default', UltraSmartPostListHandler);
        }
    }

    $(document).ready(function () {
        $('.ua-smart-post-list-wrapper').each(function () {
            var $scope = $(this).closest('.elementor-element');
            if ($scope.length && !$scope.hasClass('ua-smart-ready')) {
                $scope.addClass('ua-smart-ready');
                UltraSmartPostListHandler($scope, $);
            }
        });
    });

    if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
        registerElementorHooks();
    } else {
        $(window).on('elementor/frontend/init', registerElementorHooks);
    }

})(jQuery);
