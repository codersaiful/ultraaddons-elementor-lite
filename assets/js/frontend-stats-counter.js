/**
 * UltraAddons - Stats Counter Widget Frontend Script
 *
 * Provides real mechanical Odometer / Vertical Rolling Digits (Slot Machine Wheel)
 * with both Bottom-to-Top (Up) and Top-to-Bottom (Down) roll directions,
 * and smooth exponential counter animations with IntersectionObserver viewport trigger.
 *
 * @package UltraAddons
 * @version 2.0.3.6
 */
(function ($) {
    'use strict';

    var UltraAddonsCounter = {

        /**
         * Initialize all counter widgets on the page.
         */
        init: function () {
            $('.ua-stats-counter-wrapper').each(function () {
                UltraAddonsCounter.initWidget($(this));
            });
        },

        /**
         * Initialize a single counter instance.
         */
        initWidget: function ($wrapper) {
            if ($wrapper.data('ua-counter-initialized')) {
                return;
            }
            $wrapper.data('ua-counter-initialized', true);

            var $numberEl = $wrapper.find('.ua-counter-number');
            if (!$numberEl.length) {
                return;
            }

            var startVal    = parseFloat($numberEl.attr('data-start')) || 0;
            var endVal      = parseFloat($numberEl.attr('data-end')) || 0;
            var duration    = parseInt($numberEl.attr('data-duration'), 10) || 2000;
            var decimals    = parseInt($numberEl.attr('data-decimals'), 10) || 0;
            var thousandSep = $numberEl.attr('data-thousand-sep') || '';
            var decimalSep  = $numberEl.attr('data-decimal-sep') || '.';
            var autoShorten = $numberEl.attr('data-auto-shorten') === 'yes';
            var direction   = $numberEl.attr('data-direction') || 'up';
            var animType    = $numberEl.attr('data-anim-type') || 'odometer';
            var delay       = parseInt($numberEl.attr('data-delay'), 10) || 0;

            if (thousandSep === 'none') {
                thousandSep = '';
            }

            var hasAnimated = false;

            // Format number helper function
            function formatNumber(val) {
                if (autoShorten) {
                    if (Math.abs(val) >= 1e9) {
                        return (val / 1e9).toFixed(decimals > 0 ? decimals : 1) + 'B';
                    }
                    if (Math.abs(val) >= 1e6) {
                        return (val / 1e6).toFixed(decimals > 0 ? decimals : 1) + 'M';
                    }
                    if (Math.abs(val) >= 1e3) {
                        return (val / 1e3).toFixed(decimals > 0 ? decimals : 1) + 'K';
                    }
                }

                var fixedStr = val.toFixed(decimals);
                var parts    = fixedStr.split('.');
                var intPart  = parts[0];
                var decPart  = parts.length > 1 ? parts[1] : '';

                if (thousandSep) {
                    intPart = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, thousandSep);
                }

                return decPart ? intPart + decimalSep + decPart : intPart;
            }

            /**
             * Build Odometer Rolling Digits HTML
             * Always uses full target ending number so all digits are preserved
             */
            function setupOdometer() {
                var targetStr = formatNumber(endVal);
                var html = '';

                for (var i = 0; i < targetStr.length; i++) {
                    var char = targetStr[i];
                    if (/[0-9]/.test(char)) {
                        var digit = parseInt(char, 10);
                        // Ribbon with 20 numbers: 0-9 and 0-9
                        html += '<span class="ua-odometer-digit" data-digit="' + digit + '">';
                        html += '<span class="ua-odometer-ribbon">';
                        for (var r = 0; r < 20; r++) {
                            html += '<span>' + (r % 10) + '</span>';
                        }
                        html += '</span></span>';
                    } else {
                        html += '<span class="ua-odometer-sep">' + char + '</span>';
                    }
                }

                $numberEl.html(html);

                // If rolling Down (Top to Bottom), initialize ribbon at the bottom offset
                if (direction === 'down') {
                    $numberEl.find('.ua-odometer-digit').each(function () {
                        var digitVal = parseInt($(this).attr('data-digit'), 10) || 0;
                        var startY = (10 + digitVal) * 5;
                        $(this).find('.ua-odometer-ribbon').css({
                            'transform': 'translateY(-' + startY + '%)',
                            'transition': 'none'
                        });
                    });
                }
            }

            /**
             * Animate Odometer Digits Rolling vertically
             * - direction 'up': rolls from bottom to top
             * - direction 'down': rolls from top to bottom
             */
            function animateOdometer() {
                $wrapper.addClass('ua-counter-animated');
                var $digits = $numberEl.find('.ua-odometer-digit');

                // Force browser reflow
                $numberEl[0].offsetHeight;

                $digits.each(function (index) {
                    var $digitEl  = $(this);
                    var digitVal  = parseInt($digitEl.attr('data-digit'), 10) || 0;
                    var $ribbon   = $digitEl.find('.ua-odometer-ribbon');
                    var digitDuration = duration + (index * 60);

                    if (direction === 'down') {
                        // Roll from bottom to top position (downward motion)
                        var targetY = digitVal * 5;
                        $ribbon.css({
                            'transition': 'transform ' + digitDuration + 'ms cubic-bezier(0.12, 0.8, 0.2, 1)',
                            'transform': 'translateY(-' + targetY + '%)'
                        });
                    } else {
                        // Roll from top to bottom position (upward motion)
                        var targetY = (10 + digitVal) * 5;
                        $ribbon.css({
                            'transition': 'transform ' + digitDuration + 'ms cubic-bezier(0.12, 0.8, 0.2, 1)',
                            'transform': 'translateY(-' + targetY + '%)'
                        });
                    }
                });
            }

            /**
             * Animate Standard Digital Counter (smooth continuous counting)
             */
            function animateDigitalCounter() {
                $wrapper.addClass('ua-counter-animated');
                var fromVal = (direction === 'down') ? endVal : startVal;
                var toVal   = (direction === 'down') ? startVal : endVal;
                var startTime = null;

                function easeOutExpo(t) {
                    return t === 1 ? 1 : 1 - Math.pow(2, -10 * t);
                }

                function step(timestamp) {
                    if (!startTime) startTime = timestamp;
                    var progress = Math.min((timestamp - startTime) / duration, 1);
                    var currentVal = fromVal + (toVal - fromVal) * easeOutExpo(progress);

                    $numberEl.text(formatNumber(currentVal));

                    if (progress < 1) {
                        window.requestAnimationFrame(step);
                    } else {
                        $numberEl.text(formatNumber(toVal));
                    }
                }

                window.requestAnimationFrame(step);
            }

            // Setup initial display
            if (animType === 'odometer') {
                setupOdometer();
            } else {
                $numberEl.text(formatNumber(direction === 'down' ? endVal : startVal));
            }

            function triggerAnimation() {
                if (hasAnimated) return;
                hasAnimated = true;

                if (delay > 0) {
                    setTimeout(function () {
                        if (animType === 'odometer') {
                            animateOdometer();
                        } else {
                            animateDigitalCounter();
                        }
                    }, delay);
                } else {
                    if (animType === 'odometer') {
                        animateOdometer();
                    } else {
                        animateDigitalCounter();
                    }
                }
            }

            // Use IntersectionObserver for viewport trigger
            if ('IntersectionObserver' in window) {
                var observer = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            triggerAnimation();
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.15
                });

                observer.observe($wrapper[0]);
            } else {
                triggerAnimation();
            }
        }
    };

    $(document).ready(function () {
        UltraAddonsCounter.init();
    });

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/ultraaddons-stats-counter.default', function ($scope) {
            UltraAddonsCounter.initWidget($scope.find('.ua-stats-counter-wrapper'));
        });
    });

})(jQuery);