/**
 * UltraAddons Image Hotspots Frontend Script
 *
 * Handles hover/click/always-open triggers, mobile touch events,
 * outside click dismissal, and keyboard accessibility.
 *
 * @package UltraAddons
 * @since 2.0.3.5
 */
;(function($) {
    'use strict';

    var UAImageHotspotsHandler = function($scope, $) {
        var $wrap = $scope.find('.ua-image-hotspots-wrap');
        if (!$wrap.length) {
            return;
        }

        var trigger  = $wrap.data('trigger') || 'hover';
        var widgetId = $scope.data('id') || Math.random().toString(36).substring(7);
        var $items   = $wrap.find('.ua-hotspot-item');

        if (!$items.length) {
            return;
        }

        // Cleanup any previous namespaced events for this widget instance
        $wrap.off('.uaHotspot');
        $(document).off('.uaHotspot_' + widgetId);

        if (trigger === 'always') {
            $items.addClass('ua-active').find('.ua-hotspot-pin-wrap').attr('aria-expanded', 'true');
            return;
        }

        if (trigger === 'click') {
            // Click Trigger: Delegated on $wrap for 100% reliable execution in Editor & Frontend
            $wrap.on('click.uaHotspot', '.ua-hotspot-pin-wrap', function(e) {
                var $pinWrap = $(this);

                // If marker action is a direct external URL, allow browser to follow it
                if ($pinWrap.is('a') && $pinWrap.attr('href')) {
                    return;
                }

                e.preventDefault();
                e.stopPropagation();

                var $item = $pinWrap.closest('.ua-hotspot-item');

                if ($item.hasClass('ua-active')) {
                    $item.removeClass('ua-active').find('.ua-hotspot-pin-wrap').attr('aria-expanded', 'false');
                } else {
                    $items.not($item).removeClass('ua-active').find('.ua-hotspot-pin-wrap').attr('aria-expanded', 'false');
                    $item.addClass('ua-active').find('.ua-hotspot-pin-wrap').attr('aria-expanded', 'true');
                }
            });

            // Prevent clicks inside tooltip card from bubbling to document (prevents accidental closing)
            $wrap.on('click.uaHotspot', '.ua-hotspot-tooltip', function(e) {
                e.stopPropagation();
            });

            // Dismiss active tooltip when clicking anywhere outside
            $(document).on('click.uaHotspot_' + widgetId, function(e) {
                if (!$(e.target).closest($wrap).length) {
                    $items.removeClass('ua-active').find('.ua-hotspot-pin-wrap').attr('aria-expanded', 'false');
                }
            });

        } else {
            // Hover Trigger (Desktop) with graceful leave timer
            var leaveTimers = {};

            $items.each(function() {
                var $item = $(this);
                var idx   = $item.data('index');

                $item.on('mouseenter.uaHotspot', function() {
                    if (leaveTimers[idx]) {
                        clearTimeout(leaveTimers[idx]);
                    }
                    $items.not($item).removeClass('ua-active');
                    $item.addClass('ua-active').find('.ua-hotspot-pin-wrap').attr('aria-expanded', 'true');
                });

                $item.on('mouseleave.uaHotspot', function() {
                    leaveTimers[idx] = setTimeout(function() {
                        $item.removeClass('ua-active').find('.ua-hotspot-pin-wrap').attr('aria-expanded', 'false');
                    }, 160);
                });

                // Mobile touch support for hover mode
                $item.find('.ua-hotspot-pin-wrap').on('click.uaHotspot', function(e) {
                    if ($(this).is('a') && $(this).attr('href')) {
                        return;
                    }

                    if (!$item.hasClass('ua-active')) {
                        e.preventDefault();
                        e.stopPropagation();
                        $items.not($item).removeClass('ua-active');
                        $item.addClass('ua-active').find('.ua-hotspot-pin-wrap').attr('aria-expanded', 'true');
                    }
                });
            });

            // Close on click/touch outside
            $(document).on('click.uaHotspot_' + widgetId, function(e) {
                if (!$(e.target).closest($wrap).length) {
                    $items.removeClass('ua-active').find('.ua-hotspot-pin-wrap').attr('aria-expanded', 'false');
                }
            });
        }

        // Keyboard Accessibility: Escape key closes active tooltip
        $(document).on('keydown.uaHotspot_' + widgetId, function(e) {
            if (e.key === 'Escape' || e.keyCode === 27) {
                var $active = $items.filter('.ua-active');
                if ($active.length) {
                    $active.removeClass('ua-active').find('.ua-hotspot-pin-wrap').attr('aria-expanded', 'false').focus();
                }
            }
        });
    };

    function registerHotspotsHook() {
        if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
            elementorFrontend.hooks.addAction('frontend/element_ready/ultraaddons-image-hotspots.default', UAImageHotspotsHandler);
        }
    }

    if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
        registerHotspotsHook();
    } else {
        $(window).on('elementor/frontend/init', registerHotspotsHook);
    }

    // Pure DOM ready fallback for live site pages
    $(function() {
        if (typeof elementorFrontend === 'undefined' || !elementorFrontend.isEditMode()) {
            $('.elementor-widget-ultraaddons-image-hotspots').each(function() {
                UAImageHotspotsHandler($(this), jQuery);
            });
        }
    });

})(jQuery);
