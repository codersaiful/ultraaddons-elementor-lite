/**
 * UltraAddons - Motion Text Frontend Engine
 * 
 * High-performance, zero-dependency kinetic typography animation handler
 * Driven by native IntersectionObserver and hardware-accelerated CSS transforms.
 * 
 * @package UltraAddons
 * @author Saiful Islam <codersaiful@gmail.com>
 * @version 1.2.0
 */
(function ($) {
    'use strict';

    /**
     * Motion Text Elementor Widget Handler
     *
     * @param {jQuery} $scope The widget wrapper element.
     */
    var UltraMotionTextHandler = function ($scope) {
        var $wrapper = $scope.find('.ua-motion-text-wrapper');
        if (!$wrapper.length) {
            return;
        }

        var wrapperEl = $wrapper[0];
        var rawSettings = $wrapper.attr('data-ua-mt-settings');
        var settings = {};

        if (rawSettings) {
            try {
                settings = JSON.parse(rawSettings);
            } catch (e) {
                settings = {};
            }
        }

        var trigger = settings.trigger || 'scroll';
        var playOnce = settings.play_once !== false && settings.play_once !== 'no';
        var duration = settings.duration ? parseFloat(settings.duration) : 0.75;
        var stagger = settings.stagger ? parseFloat(settings.stagger) : 0.05;
        var threshold = settings.threshold ? parseFloat(settings.threshold) : 0.15;
        var easingMap = {
            'luxury': 'cubic-bezier(0.16, 1, 0.3, 1)',
            'smooth': 'ease-out',
            'bounce': 'cubic-bezier(0.34, 1.56, 0.64, 1)',
            'linear': 'linear'
        };
        var easing = easingMap[settings.easing] || settings.easing || 'cubic-bezier(0.16, 1, 0.3, 1)';

        // Apply CSS custom properties
        wrapperEl.style.setProperty('--ua-mt-duration', duration + 's');
        wrapperEl.style.setProperty('--ua-mt-stagger', stagger + 's');
        wrapperEl.style.setProperty('--ua-mt-easing', easing);

        // Assign unit indices
        var $units = $wrapper.find('.ua-mt-unit');
        $units.each(function (index) {
            this.style.setProperty('--ua-mt-index', index);
        });

        // Animation state toggles
        var playAnimation = function () {
            $wrapper.removeClass('ua-mt-no-transition');
            $wrapper.addClass('ua-mt-animated');
        };

        var resetAnimation = function () {
            $wrapper.addClass('ua-mt-no-transition');
            $wrapper.removeClass('ua-mt-animated');
            void wrapperEl.offsetHeight; // Force DOM reflow
            $wrapper.removeClass('ua-mt-no-transition');
        };

        // Check if inside Elementor Editor
        var isEditMode = (typeof elementorFrontend !== 'undefined' && elementorFrontend.isEditMode && elementorFrontend.isEditMode()) || $('body').hasClass('elementor-editor-active');

        if (isEditMode) {
            resetAnimation();
            setTimeout(playAnimation, 80);

            $wrapper.off('click.uamt').on('click.uamt', function () {
                resetAnimation();
                setTimeout(playAnimation, 60);
            });
            return;
        }

        // Frontend mode: Immediate on load
        if (trigger === 'load') {
            setTimeout(playAnimation, 60);
            return;
        }

        // Frontend mode: Viewport Scroll Trigger
        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function (entries, currentObserver) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        playAnimation();
                        if (playOnce) {
                            currentObserver.unobserve(entry.target);
                        }
                    } else {
                        if (!playOnce) {
                            resetAnimation();
                        }
                    }
                });
            }, {
                root: null,
                threshold: threshold
            });

            observer.observe(wrapperEl);
        } else {
            playAnimation();
        }
    };

    // Register handler robustly
    var registerHandler = function () {
        if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/ultraaddons-motion-text.default',
                UltraMotionTextHandler
            );
        }
    };

    if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
        registerHandler();
    } else {
        $(window).on('elementor/frontend/init', registerHandler);
    }

})(jQuery);