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
        if ($container.hasClass('ua-timeline-horizontal') || $container.hasClass('ua-timeline-horizontal-bottom') || $container.find('.ua-timeline-swiper').length) {
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

        // Ensure all slides are active and visible immediately
        $container.find('.ua-timeline-item').addClass('is-active ua-animated');

        var configAttr = $container.attr('data-swiper-config');
        var config = {};

        if (configAttr) {
            try {
                config = JSON.parse(configAttr);
            } catch (e) {
                config = {};
            }
        }

        // Resolve navigation buttons and pagination DOM elements
        var widgetId = $container.attr('id') ? $container.attr('id').replace('ua-timeline-', '') : '';
        if (widgetId) {
            var $prevBtn = $container.find('.ua-swiper-prev-' + widgetId);
            var $nextBtn = $container.find('.ua-swiper-next-' + widgetId);
            var $pagination = $container.find('.ua-swiper-pagination-' + widgetId);

            if ($prevBtn.length && $nextBtn.length) {
                config.navigation = {
                    prevEl: $prevBtn[0],
                    nextEl: $nextBtn[0]
                };
            }
            if ($pagination.length) {
                config.pagination = {
                    el: $pagination[0],
                    type: 'progressbar'
                };
            }
        }

        // If swiper already initialized, destroy first
        if ($swiperEl[0].swiper && typeof $swiperEl[0].swiper.destroy === 'function') {
            $swiperEl[0].swiper.destroy(true, true);
        }

        var onSwiperInit = function (swiperInstance) {
            $swiperEl[0].swiper = swiperInstance;
            $container.find('.ua-timeline-item').addClass('is-active ua-animated');
            $container.css('opacity', 1);
            // Update Swiper size and slides
            setTimeout(function () {
                if (swiperInstance && typeof swiperInstance.update === 'function') {
                    swiperInstance.update();
                }
            }, 100);
        };

        // 1. Modern Elementor async Swiper (Elementor 3.x+)
        if (typeof elementorFrontend !== 'undefined' && elementorFrontend.utils && elementorFrontend.utils.swiper) {
            var asyncSwiper = elementorFrontend.utils.swiper;
            new asyncSwiper($swiperEl[0], config).then(function (swiperInstance) {
                onSwiperInit(swiperInstance);
            });
        }
        // 2. Global window.Swiper fallback
        else if (typeof Swiper !== 'undefined') {
            var swiperInstance = new Swiper($swiperEl[0], config);
            onSwiperInit(swiperInstance);
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
        var ajaxUrl = $paginationWrap.attr('data-ajax-url') || (typeof uaTimelineData !== 'undefined' ? uaTimelineData.ajax_url : (window.ajaxurl || '/wp-admin/admin-ajax.php'));
        var nonce = $paginationWrap.attr('data-nonce') || (typeof uaTimelineData !== 'undefined' ? uaTimelineData.nonce : '');
        var isLoading = false;

        var $loadMoreBtn = $paginationWrap.find('.ua-timeline-load-more-btn');
        var $btnText = $loadMoreBtn.find('.ua-btn-text');
        var $btnLoader = $loadMoreBtn.find('.ua-btn-loader');
        var $infiniteLoader = $paginationWrap.find('.ua-timeline-infinite-loader');
        var $infiniteTrigger = $paginationWrap.find('.ua-timeline-infinite-trigger');
        var $noMore = $paginationWrap.find('.ua-timeline-no-more');
        var $itemsWrap = $container.find('.ua-timeline-items-wrap');

        // Cleanup any prior scroll listener on re-init
        var prevNamespace = $paginationWrap.data('ua-infinite-ns');
        if (prevNamespace) {
            $(window).off(prevNamespace);
        }
        var instanceNs = '.uaTimelineInfinite_' + Math.random().toString(36).substring(2, 9);
        $paginationWrap.data('ua-infinite-ns', instanceNs);

        var observer = null;

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
                url: ajaxUrl,
                type: 'POST',
                data: {
                    action: 'ua_timeline_load_posts',
                    nonce: nonce,
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

                        if (res.data.max_pages) {
                            maxPages = parseInt(res.data.max_pages, 10);
                            $paginationWrap.attr('data-max-pages', maxPages);
                        }

                        if (res.data.last_year) {
                            $paginationWrap.attr('data-last-year', res.data.last_year);
                        }

                        // Animate and activate new items
                        if (!$container.hasClass('has-entrance-animation')) {
                            $newItems.addClass('is-active');
                        } else {
                            setTimeout(function () {
                                $(window).trigger('scroll');
                            }, 50);
                        }

                        // Trigger scroll progress check for new items and line fill
                        $(window).trigger('scroll');

                        if (!res.data.has_more || currentPage >= maxPages) {
                            $loadMoreBtn.hide();
                            $infiniteLoader.hide();
                            $noMore.show();
                            if (observer) {
                                observer.disconnect();
                            }
                            $(window).off(instanceNs);
                        } else if (paginationType === 'infinite_scroll') {
                            // Check if next page needs to be triggered if trigger is still in view
                            setTimeout(function () {
                                checkInfiniteScroll();
                            }, 300);
                        }
                    } else {
                        $loadMoreBtn.hide();
                        $infiniteLoader.hide();
                        $noMore.show();
                        if (observer) {
                            observer.disconnect();
                        }
                        $(window).off(instanceNs);
                    }
                },
                error: function () {
                    $btnText.show();
                    $btnLoader.hide();
                    $infiniteLoader.hide();
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
            $loadMoreBtn.off('click').on('click', function (e) {
                e.preventDefault();
                loadNextPage();
            });
        }

        // Infinite Scroll check
        function checkInfiniteScroll() {
            if (isLoading || currentPage >= maxPages) return;
            var triggerEl = $infiniteTrigger.length ? $infiniteTrigger[0] : $paginationWrap[0];
            if (!triggerEl) return;
            var rect = triggerEl.getBoundingClientRect();
            var windowHeight = window.innerHeight || document.documentElement.clientHeight;
            if (rect.top <= windowHeight + 350) {
                loadNextPage();
            }
        }

        // Infinite Scroll initialization
        if (paginationType === 'infinite_scroll') {
            var triggerEl = $infiniteTrigger.length ? $infiniteTrigger[0] : $paginationWrap[0];

            // 1. IntersectionObserver
            if ('IntersectionObserver' in window && triggerEl) {
                observer = new IntersectionObserver(function (entries) {
                    if (entries[0] && entries[0].isIntersecting) {
                        loadNextPage();
                    }
                }, {
                    root: null,
                    rootMargin: '350px 0px 350px 0px',
                    threshold: 0
                });

                observer.observe(triggerEl);
            }

            // 2. Scroll & resize listener fallback with throttling
            var scrollThrottle = null;
            var onScrollCheck = function () {
                if (isLoading || currentPage >= maxPages) return;
                if (scrollThrottle) return;
                scrollThrottle = setTimeout(function () {
                    scrollThrottle = null;
                    checkInfiniteScroll();
                }, 100);
            };

            $(window).on('scroll' + instanceNs + ' resize' + instanceNs, onScrollCheck);

            // Initial check on load
            setTimeout(function () {
                checkInfiniteScroll();
            }, 200);
        }
    }

    // Hook into Elementor frontend
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/ultraaddons-timeline.default', function ($scope) {
            initTimeline($scope);
        });
        elementorFrontend.hooks.addAction('frontend/element_ready/widget', function ($scope) {
            if ($scope.hasClass('elementor-widget-ultraaddons-timeline')) {
                initTimeline($scope);
            }
        });
    });

    // Fallback document ready
    $(document).ready(function () {
        $('.ua-timeline-container').each(function () {
            var $widget = $(this).closest('.elementor-widget');
            initTimeline($widget.length ? $widget : $(this).parent());
        });
    });

})(jQuery);
