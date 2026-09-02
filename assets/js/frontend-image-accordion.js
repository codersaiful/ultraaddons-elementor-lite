/**
 * UltraAddons Image Accordion Frontend Script
 *
 * Smooth Flexbox transitions, hover/click triggers,
 * mobile touch handling, keyboard accessibility,
 * and smooth GPU-accelerated CSS transitions.
 *
 * @package UltraAddons
 * @since 2.0.3.5
 */
;(function($) {
    'use strict';

    var UAImageAccordionHandler = function($scope, $) {
        var $accordion = $scope.find('.ua-image-accordion');
        if (!$accordion.length) {
            return;
        }

        var trigger = $accordion.data('trigger') || 'hover';
        var defaultIndex = parseInt($accordion.data('active-index'), 10);
        var $items = $accordion.find('.ua-ia-item');

        if (!$items.length) {
            return;
        }

        function setActive($item) {
            if ($item.hasClass('ua-ia-active')) {
                return;
            }

            // Deactivate all items
            $items.removeClass('ua-ia-active').attr('aria-expanded', 'false');

            // Activate the target item
            $item.addClass('ua-ia-active').attr('aria-expanded', 'true');
        }

        // Set default active item if specified and valid
        if (!isNaN(defaultIndex) && defaultIndex > 0 && defaultIndex <= $items.length) {
            $items.removeClass('ua-ia-active').attr('aria-expanded', 'false');
            $items.eq(defaultIndex - 1).addClass('ua-ia-active').attr('aria-expanded', 'true');
        } else if (!$items.filter('.ua-ia-active').length) {
            // Default to first item if none active
            $items.first().addClass('ua-ia-active').attr('aria-expanded', 'true');
        }

        if (trigger === 'click') {
            $items.on('click', function(e) {
                var $target = $(e.target);

                // If already active and user clicks a link/button, allow navigation
                if ($(this).hasClass('ua-ia-active')) {
                    if ($target.closest('.ua-ia-btn').length || $target.closest('.ua-ia-full-link').length) {
                        return;
                    }
                }

                // If inactive, expand the item and prevent instant navigation
                if (!$(this).hasClass('ua-ia-active')) {
                    e.preventDefault();
                    setActive($(this));
                }
            });
        } else {
            // Hover trigger for desktop
            $items.on('mouseenter', function() {
                setActive($(this));
            });

            // Touch support for mobile devices
            $items.on('touchstart', function(e) {
                if (!$(this).hasClass('ua-ia-active')) {
                    e.preventDefault();
                    setActive($(this));
                }
            });
        }

        // Keyboard Accessibility (Enter or Space to activate)
        $items.on('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ' || e.keyCode === 13 || e.keyCode === 32) {
                e.preventDefault();
                setActive($(this));
            }
        });
    };

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/ultraaddons-image-accordion.default', UAImageAccordionHandler);
    });

})(jQuery);
