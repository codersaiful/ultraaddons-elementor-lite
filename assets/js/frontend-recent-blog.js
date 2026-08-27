/**
 * UltraAddons - Recent Blog / Post Grid Widget Frontend Scripts
 *
 * Handles live AJAX category filtering, AJAX numbered pagination, and Load More.
 *
 * @package UltraAddons
 * @version 2.0.3.5
 */
(function ($) {
    'use strict';

    var UltraAddonsBlog = {

        /**
         * Get Configuration with safe defaults.
         */
        getConfig: function () {
            return window.uaBlogConfig || {
                ajax_url: typeof ajaxurl !== 'undefined' ? ajaxurl : '/wp-admin/admin-ajax.php',
                nonce: '',
                i18n: {
                    loading: 'Loading...',
                    no_more: 'No more posts'
                }
            };
        },

        /**
         * Get Settings for a wrapper element.
         */
        getSettings: function ($wrapper) {
            var settings = $wrapper.data('ua-settings');
            if (!settings) {
                var raw = $wrapper.attr('data-settings');
                if (raw) {
                    try {
                        settings = JSON.parse(raw);
                    } catch (e) {
                        settings = {};
                    }
                } else {
                    settings = {};
                }
                $wrapper.data('ua-settings', settings);
            }
            return settings;
        },

        /**
         * Initialize all widgets on page.
         */
        init: function () {
            $('.ua-recent-blog-wrapper').each(function () {
                UltraAddonsBlog.getSettings($(this));
            });
        },

        /**
         * Fetch Posts via WordPress AJAX.
         *
         * @param {jQuery}   $wrapper
         * @param {number}   page
         * @param {boolean}  isAppend
         * @param {boolean}  doScroll
         * @param {Function} callback
         */
        fetchPosts: function ($wrapper, page, isAppend, doScroll, callback) {
            var $grid = $wrapper.find('.ua-recent-blog-grid');
            var settings = UltraAddonsBlog.getSettings($wrapper);
            var config = UltraAddonsBlog.getConfig();

            if (!isAppend) {
                $grid.addClass('ua-loading');
            }

            var postData = {
                action: 'ua_recent_blog_load_posts',
                nonce: config.nonce,
                paged: page,
                category: settings.active_category || '',
                settings: JSON.stringify(settings)
            };

            $.ajax({
                url: config.ajax_url,
                type: 'POST',
                data: postData,
                dataType: 'json',
                success: function (response) {
                    if (response && response.success && response.data) {
                        var html = response.data.html || '';
                        var paginationHtml = response.data.pagination || '';
                        var maxPages = response.data.max_pages || 1;

                        if (isAppend) {
                            var $newItems = $(html).hide();
                            $grid.append($newItems);
                            $newItems.fadeIn(350);
                        } else {
                            $grid.html(html);
                            $grid.removeClass('ua-loading');

                            var $paginationWrap = $wrapper.find('.ua-blog-pagination-wrapper');
                            if ($paginationWrap.length) {
                                $paginationWrap.html(paginationHtml);
                            }
                        }

                        if (doScroll) {
                            $('html, body').animate({
                                scrollTop: $wrapper.offset().top - 70
                            }, 350);
                        }

                        if (typeof callback === 'function') {
                            callback(page < maxPages);
                        }
                    } else {
                        $grid.removeClass('ua-loading');
                        if (typeof callback === 'function') {
                            callback(false);
                        }
                    }
                },
                error: function () {
                    $grid.removeClass('ua-loading');
                    if (typeof callback === 'function') {
                        callback(false);
                    }
                }
            });
        }
    };

    // 1. AJAX Category Filter Event Delegation
    $(document).on('click', '.ua-recent-blog-wrapper .ua-blog-filter-btn', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $wrapper = $btn.closest('.ua-recent-blog-wrapper');

        if ($btn.hasClass('active')) {
            return;
        }

        $btn.siblings().removeClass('active');
        $btn.addClass('active');

        var category = $btn.attr('data-category') || '';
        var settings = UltraAddonsBlog.getSettings($wrapper);
        settings.active_category = category;
        settings.paged = 1;
        $wrapper.data('ua-settings', settings);

        // Reset Load More Button if present
        var $loadMoreBtn = $wrapper.find('.ua-blog-load-more-btn');
        if ($loadMoreBtn.length) {
            $loadMoreBtn.attr('data-page', 1).show();
        }

        UltraAddonsBlog.fetchPosts($wrapper, 1, false, false);
    });

    // 2. AJAX Numbered Pagination Event Delegation
    $(document).on('click', '.ua-recent-blog-wrapper .ua-blog-pagination a.page-numbers', function (e) {
        e.preventDefault();
        var $link = $(this);
        var $wrapper = $link.closest('.ua-recent-blog-wrapper');
        var url = $link.attr('href') || '';
        var page = 1;

        var match = url.match(/paged=(\d+)/) || url.match(/page\/(\d+)/);
        if (match && match[1]) {
            page = parseInt(match[1], 10);
        } else {
            var text = $link.text().trim();
            if (!isNaN(parseInt(text, 10))) {
                page = parseInt(text, 10);
            }
        }

        var settings = UltraAddonsBlog.getSettings($wrapper);
        settings.paged = page;
        $wrapper.data('ua-settings', settings);

        UltraAddonsBlog.fetchPosts($wrapper, page, false, true);
    });

    // 3. AJAX Load More Button Event Delegation
    $(document).on('click', '.ua-recent-blog-wrapper .ua-blog-load-more-btn', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $wrapper = $btn.closest('.ua-recent-blog-wrapper');
        var config = UltraAddonsBlog.getConfig();

        if ($btn.hasClass('loading')) {
            return;
        }

        var page = parseInt($btn.attr('data-page'), 10) || 1;
        var maxPages = parseInt($btn.attr('data-max-pages'), 10) || 1;
        var nextPage = page + 1;

        if (nextPage > maxPages) {
            $btn.fadeOut();
            return;
        }

        $btn.addClass('loading');
        var $btnText = $btn.find('.ua-btn-text');
        var originalText = $btnText.text();
        $btnText.text(config.i18n.loading || 'Loading...');

        UltraAddonsBlog.fetchPosts($wrapper, nextPage, true, false, function (hasMore) {
            $btn.removeClass('loading');
            $btnText.text(originalText);
            $btn.attr('data-page', nextPage);

            if (!hasMore || nextPage >= maxPages) {
                $btn.fadeOut(300, function () {
                    $(this).remove();
                });
            }
        });
    });

    $(document).ready(function () {
        UltraAddonsBlog.init();
    });

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/ultraaddons-recent-blog.default', function ($scope) {
            UltraAddonsBlog.init();
        });
    });

})(jQuery);
