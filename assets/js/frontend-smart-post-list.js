/**
 * UltraAddons - Smart Post List Widget Frontend Script
 *
 * Handles AJAX Category Filtering tabs, Live Search with debounce,
 * and Prev/Next page navigation with loading spinner states.
 * Uses robust global document event delegation for full compatibility
 * with Elementor editor live preview re-rendering.
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
     * Update Navigation Buttons UI
     *
     * @param {jQuery}  $wrapper
     * @param {number}  currentPage
     * @param {number}  maxPages
     * @param {boolean} isLoop
     */
    function updateNavState($wrapper, currentPage, maxPages, isLoop) {
        var $prevBtn = $wrapper.find('.ua-smart-nav-prev');
        var $nextBtn = $wrapper.find('.ua-smart-nav-next');

        if (maxPages <= 1) {
            $prevBtn.addClass('disabled').prop('disabled', true);
            $nextBtn.addClass('disabled').prop('disabled', true);
            return;
        }

        if (isLoop) {
            $prevBtn.removeClass('disabled').prop('disabled', false);
            $nextBtn.removeClass('disabled').prop('disabled', false);
            return;
        }

        if (currentPage > 1) {
            $prevBtn.removeClass('disabled').prop('disabled', false);
        } else {
            $prevBtn.addClass('disabled').prop('disabled', true);
        }

        if (currentPage < maxPages) {
            $nextBtn.removeClass('disabled').prop('disabled', false);
        } else {
            $nextBtn.addClass('disabled').prop('disabled', true);
        }
    }

    /**
     * Fetch posts via AJAX for a specific Smart Post List wrapper
     *
     * @param {jQuery}   $wrapper
     * @param {number}   targetPage
     * @param {Function} [onComplete]
     */
    function fetchPosts($wrapper, targetPage, onComplete) {
        if (!$wrapper.length || $wrapper.hasClass('is-loading')) {
            return;
        }

        var $loading = $wrapper.find('.ua-smart-loading');
        var $featuredCol = $wrapper.find('.ua-smart-featured-col');
        var $listWrap = $wrapper.find('.ua-smart-list-wrap');
        var activeCatId = parseInt($wrapper.attr('data-cat-id'), 10) || 0;
        var searchTerm = $wrapper.data('ua-search-term') || '';
        var isLoop = $wrapper.attr('data-loop') === 'yes';

        var config = window.uaSmartPostListConfig || {};
        var ajaxUrl = $wrapper.attr('data-ajax-url') || config.ajax_url || (typeof ajaxurl !== 'undefined' ? ajaxurl : '/wp-admin/admin-ajax.php');
        var nonce = $wrapper.attr('data-nonce') || config.nonce || '';
        var settingsData = $wrapper.attr('data-settings') || '{}';

        $wrapper.addClass('is-loading');
        $loading.stop(true, true).fadeIn(150);

        var postData = {
            action: 'ua_smart_post_list_query',
            nonce: nonce,
            paged: targetPage,
            category_id: activeCatId,
            search_term: searchTerm,
            settings: settingsData
        };

        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: postData,
            dataType: 'json',
            success: function (res) {
                if (res && res.success && res.data) {
                    var data = res.data;
                    var currentPage = parseInt(data.paged, 10) || 1;
                    var maxPages = parseInt(data.max_pages, 10) || 1;

                    $wrapper.attr('data-paged', currentPage);
                    $wrapper.attr('data-max-pages', maxPages);

                    // Update Featured Post
                    if ($featuredCol.length) {
                        if (data.html_featured) {
                            $featuredCol.html(data.html_featured).show();
                        } else {
                            $featuredCol.empty().hide();
                        }
                    }

                    // Update List Posts
                    if ($listWrap.length) {
                        $listWrap.html(data.html_list);
                    }

                    // Update Navigation Buttons State
                    updateNavState($wrapper, currentPage, maxPages, isLoop);
                } else {
                    console.warn('UltraAddons Smart Post List response error:', res);
                }
            },
            error: function (xhr, status, error) {
                console.error('UltraAddons Smart Post List AJAX Error:', error, xhr.responseText);
            },
            complete: function () {
                $loading.stop(true, true).fadeOut(200);
                $wrapper.removeClass('is-loading');

                var curPage = parseInt($wrapper.attr('data-paged'), 10) || 1;
                var totalPages = parseInt($wrapper.attr('data-max-pages'), 10) || 1;
                updateNavState($wrapper, curPage, totalPages, isLoop);

                if (typeof onComplete === 'function') {
                    onComplete();
                }
            }
        });
    }

    // ==============================================================
    // 1. Next Page Button Event Delegation
    // ==============================================================
    $(document).on('click', '.ua-smart-post-list-wrapper .ua-smart-nav-next', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $wrapper = $btn.closest('.ua-smart-post-list-wrapper');
        if (!$wrapper.length || $wrapper.hasClass('is-loading')) {
            return;
        }

        var currentPage = parseInt($wrapper.attr('data-paged'), 10) || 1;
        var maxPages = parseInt($wrapper.attr('data-max-pages'), 10) || 1;
        var isLoop = $wrapper.attr('data-loop') === 'yes';

        if (maxPages <= 1) {
            return;
        }

        var targetPage = currentPage + 1;
        if (targetPage > maxPages) {
            if (isLoop) {
                targetPage = 1;
            } else {
                return;
            }
        }

        fetchPosts($wrapper, targetPage);
    });

    // ==============================================================
    // 2. Previous Page Button Event Delegation
    // ==============================================================
    $(document).on('click', '.ua-smart-post-list-wrapper .ua-smart-nav-prev', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $wrapper = $btn.closest('.ua-smart-post-list-wrapper');
        if (!$wrapper.length || $wrapper.hasClass('is-loading')) {
            return;
        }

        var currentPage = parseInt($wrapper.attr('data-paged'), 10) || 1;
        var maxPages = parseInt($wrapper.attr('data-max-pages'), 10) || 1;
        var isLoop = $wrapper.attr('data-loop') === 'yes';

        if (maxPages <= 1) {
            return;
        }

        var targetPage = currentPage - 1;
        if (targetPage < 1) {
            if (isLoop) {
                targetPage = maxPages;
            } else {
                return;
            }
        }

        fetchPosts($wrapper, targetPage);
    });

    // ==============================================================
    // 3. Category Filter Tabs Event Delegation
    // ==============================================================
    $(document).on('click', '.ua-smart-post-list-wrapper .ua-smart-cat-tab', function (e) {
        e.preventDefault();
        var $tab = $(this);
        var $wrapper = $tab.closest('.ua-smart-post-list-wrapper');
        if (!$wrapper.length || $tab.hasClass('active') || $wrapper.hasClass('is-loading')) {
            return;
        }

        $wrapper.find('.ua-smart-cat-tab').removeClass('active');
        $tab.addClass('active');

        var catId = parseInt($tab.attr('data-cat-id'), 10) || 0;
        $wrapper.attr('data-cat-id', catId);
        $wrapper.attr('data-paged', 1);

        fetchPosts($wrapper, 1);
    });

    // ==============================================================
    // 4. Live Search Input (Debounced) Event Delegation
    // ==============================================================
    var debouncedSearch = debounce(function ($input) {
        var $wrapper = $input.closest('.ua-smart-post-list-wrapper');
        if (!$wrapper.length) {
            return;
        }

        var val = $.trim($input.val());
        var prevVal = $wrapper.data('ua-search-term') || '';
        if (val === prevVal) {
            return;
        }

        $wrapper.data('ua-search-term', val);
        $wrapper.attr('data-paged', 1);

        var $clearBtn = $wrapper.find('.ua-smart-search-clear');
        if (val.length > 0) {
            $clearBtn.show();
        } else {
            $clearBtn.hide();
        }

        fetchPosts($wrapper, 1);
    }, 350);

    $(document).on('input keyup', '.ua-smart-post-list-wrapper .ua-smart-search-input', function () {
        debouncedSearch($(this));
    });

    // ==============================================================
    // 5. Search Clear Button Event Delegation
    // ==============================================================
    $(document).on('click', '.ua-smart-post-list-wrapper .ua-smart-search-clear', function (e) {
        e.preventDefault();
        var $clearBtn = $(this);
        var $wrapper = $clearBtn.closest('.ua-smart-post-list-wrapper');
        if (!$wrapper.length) {
            return;
        }

        $wrapper.find('.ua-smart-search-input').val('');
        $clearBtn.hide();
        $wrapper.data('ua-search-term', '');
        $wrapper.attr('data-paged', 1);

        fetchPosts($wrapper, 1);
    });

    // ==============================================================
    // 6. Elementor Ready Hook Initializer
    // ==============================================================
    var UltraSmartPostListHandler = function ($scope, $) {
        var $wrapper = $scope.find('.ua-smart-post-list-wrapper');
        if (!$wrapper.length) {
            return;
        }

        var currentPage = parseInt($wrapper.attr('data-paged'), 10) || 1;
        var maxPages = parseInt($wrapper.attr('data-max-pages'), 10) || 1;
        var isLoop = $wrapper.attr('data-loop') === 'yes';

        updateNavState($wrapper, currentPage, maxPages, isLoop);
    };

    function registerElementorHooks() {
        if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
            elementorFrontend.hooks.addAction('frontend/element_ready/ultraaddons-smart-post-list.default', UltraSmartPostListHandler);
            elementorFrontend.hooks.addAction('frontend/element_ready/Smart_Post_List.default', UltraSmartPostListHandler);
            elementorFrontend.hooks.addAction('frontend/element_ready/smart-post-list.default', UltraSmartPostListHandler);
        }
    }

    $(document).ready(function () {
        $('.ua-smart-post-list-wrapper').each(function () {
            var $wrap = $(this);
            var currentPage = parseInt($wrap.attr('data-paged'), 10) || 1;
            var maxPages = parseInt($wrap.attr('data-max-pages'), 10) || 1;
            var isLoop = $wrap.attr('data-loop') === 'yes';
            updateNavState($wrap, currentPage, maxPages, isLoop);
        });
    });

    if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
        registerElementorHooks();
    } else {
        $(window).on('elementor/frontend/init', registerElementorHooks);
    }

})(jQuery);
