/**
 * UltraAddons - Timeline Widget Frontend Script
 *
 * Handles Swiper carousel initialization, smooth scroll progress line fill,
 * active marker node highlighting, and dynamic AJAX Load More / Infinite Scroll.
 *
 * @package UltraAddons
 * @version 1.1.0
 */

(function ($) {
    'use strict';

    /**
     * Initialize Timeline Widget
     */
    function initTimeline($scope) {
        var $container = $scope.find('.ua-timeline-container');
        if (!$container.length) return;

        // 1. Initialize Horizontal Carousel (Swiper)
        if ($container.hasClass('ua-timeline-horizontal')) {
            initHorizontalCarousel($container);
            return;
        }

        // 2. Initialize Vertical Scroll Progress Fill & Active Nodes
        initScrollProgress($container);

        // 3. Initialize AJAX Pagination (Load More / Infinite Scroll)
        initAjaxPagination($container);
    }

    /**
     * Initialize Swiper for Horizontal Carousel Layout
     */
    function initHorizontalCarousel($container) {
        var $swiperEl = $container.find('.ua-timeline-swiper');
        if (!$swiperEl.length) return;

        var configAttr = $container.attr('data-swiper-config');
        var config = {};

        if (configAttr) {
            try {
                config = JSON.parse(configAttr);
            } catch (e) {
                config = {};
            }
        }

        // If swiper already initialized, destroy first
        if ($swiperEl[0].swiper) {
            $swiperEl[0].swiper.destroy(true, true);
        }

        // Initialize Swiper (Elementor provides Swiper globally)
        if (typeof Swiper !== 'undefined') {
            new Swiper($swiperEl[0], config);
        }
    }

    /**
     * Initialize Scroll Progress Line & Item Activation
     */
    function initScrollProgress($container) {
        var $fill = $container.find('.ua-timeline-fill');
        var ticking = false;

        function updateProgress() {
            var $items = $container.find('.ua-timeline-item');
            if (!$items.length) return;

            var containerRect = $container[0].getBoundingClientRect();
            var windowHeight = window.innerHeight || document.documentElement.clientHeight;
            var animOffset = parseInt($container.attr('data-animation-offset'), 10);
            var triggerPoint = (!isNaN(animOffset) && animOffset > 0) ? (windowHeight - animOffset) : (windowHeight * 0.65);

            // Calculate fill height
            if ($fill.length) {
                var totalHeight = containerRect.height;
                var scrolled = triggerPoint - containerRect.top;
                var progress = Math.min(Math.max(scrolled / totalHeight, 0), 1);
                $fill.css('height', (progress * 100) + '%');
            }

            // Highlight reached items
            var hasAnim = $container.hasClass('has-entrance-animation');
            $items.each(function () {
                var itemRect = this.getBoundingClientRect();
                if (itemRect.top <= triggerPoint) {
                    $(this).addClass('is-active ua-animated');
                } else if (!hasAnim) {
                    $(this).removeClass('is-active');
                }
            });

            ticking = false;
        }

        function onScroll() {
            if (!ticking) {
                window.requestAnimationFrame(updateProgress);
                ticking = true;
            }
        }

        $(window).on('scroll resize', onScroll);
        // Initial check on load
        setTimeout(updateProgress, 100);
    }

    /**
     * Initialize AJAX Pagination (Load More & Infinite Scroll)
     */
    function initAjaxPagination($container) {
        var $paginationWrap = $container.find('.ua-timeline-pagination-wrap');
        if (!$paginationWrap.length) return;

        var paginationType = $paginationWrap.attr('data-pagination-type');
        var maxPages = parseInt($paginationWrap.attr('data-max-pages'), 10) || 1;
        var currentPage = parseInt($paginationWrap.attr('data-current-page'), 10) || 1;
        var queryArgs = $paginationWrap.attr('data-query');
        var settingsArgs = $paginationWrap.attr('data-settings');
        var isLoading = false;

        var $loadMoreBtn = $paginationWrap.find('.ua-timeline-load-more-btn');
        var $btnText = $loadMoreBtn.find('.ua-btn-text');
        var $btnLoader = $loadMoreBtn.find('.ua-btn-loader');
        var $infiniteLoader = $paginationWrap.find('.ua-timeline-infinite-loader');
        var $noMore = $paginationWrap.find('.ua-timeline-no-more');
        var $itemsWrap = $container.find('.ua-timeline-items-wrap');

        function loadNextPage() {
            if (isLoading || currentPage >= maxPages) return;
            isLoading = true;

            var nextPage = currentPage + 1;

            if (paginationType === 'load_more') {
                $btnText.hide();
                $btnLoader.show();
            } else if (paginationType === 'infinite_scroll') {
                $infiniteLoader.show();
            }

            $.ajax({
                url: (typeof uaTimelineData !== 'undefined') ? uaTimelineData.ajax_url : '/wp-admin/admin-ajax.php',
                type: 'POST',
                data: {
                    action: 'ua_timeline_load_posts',
                    nonce: (typeof uaTimelineData !== 'undefined') ? uaTimelineData.nonce : '',
                    paged: nextPage,
                    query: queryArgs,
                    settings: settingsArgs,
                    last_year: $paginationWrap.attr('data-last-year') || ''
                },
                success: function (res) {
                    if (res && res.success && res.data && res.data.html) {
                        var $newItems = $(res.data.html);
                        $itemsWrap.append($newItems);
                        currentPage = nextPage;
                        $paginationWrap.attr('data-current-page', currentPage);

                        if (res.data.last_year) {
                            $paginationWrap.attr('data-last-year', res.data.last_year);
                        }

                        // Trigger scroll progress check for new items
                        $(window).trigger('scroll');

                        if (!res.data.has_more || currentPage >= maxPages) {
                            $loadMoreBtn.hide();
                            $infiniteLoader.hide();
                            $noMore.show();
                        }
                    } else {
                        $loadMoreBtn.hide();
                        $infiniteLoader.hide();
                        $noMore.show();
                    }
                },
                complete: function () {
                    isLoading = false;
                    $btnText.show();
                    $btnLoader.hide();
                    if (paginationType === 'infinite_scroll' && currentPage < maxPages) {
                        $infiniteLoader.hide();
                    }
                }
            });
        }

        // Load More button click
        if (paginationType === 'load_more' && $loadMoreBtn.length) {
            $loadMoreBtn.on('click', function (e) {
                e.preventDefault();
                loadNextPage();
            });
        }

        // Infinite Scroll with IntersectionObserver
        if (paginationType === 'infinite_scroll' && $infiniteLoader.length && 'IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function (entries) {
                if (entries[0].isIntersecting) {
                    loadNextPage();
                }
            }, { rootMargin: '200px' });

            observer.observe($infiniteLoader[0]);
        }
    }

    // Hook into Elementor frontend
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/ultraaddons-timeline.default', function ($scope) {
            initTimeline($scope);
        });
    });

    // Fallback document ready for non-Elementor preview
    $(document).ready(function () {
        if (!window.elementorFrontend) {
            $('.ua-timeline-container').each(function () {
                initTimeline($(this).closest('.elementor-widget'));
            });
        }
    });

})(jQuery);
