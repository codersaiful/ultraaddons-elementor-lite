/**
 * UltraAddons Image Comparison (Before/After) Frontend Script
 *
 * High-performance, hardware-accelerated image comparison engine
 * built on modern W3C Pointer Events API with setPointerCapture,
 * multi-trigger support (Drag, Hover, Click), smart label collision auto-fading,
 * keyboard accessibility (WCAG), and responsive auto-sweep on scroll.
 *
 * @package UltraAddons
 * @since 2.0.3.6
 */
;(function($) {
    'use strict';

    var UAImageComparisonHandler = function($scope, $) {
        var $wrap = $scope.find('.ua-image-comparison-wrap');
        if (!$wrap.length) {
            return;
        }

        var wrapEl = $wrap[0];
        var container = $wrap.find('.ua-ic-container')[0];
        var dividerWrap = $wrap.find('.ua-ic-divider-wrap')[0];
        var $labelBefore = $wrap.find('.ua-ic-label-before');
        var $labelAfter = $wrap.find('.ua-ic-label-after');

        if (!container || !dividerWrap) {
            return;
        }

        if (container.dataset.uaIcInitialized) {
            return;
        }
        container.dataset.uaIcInitialized = 'true';

        // Configuration
        var orientation  = $wrap.data('orientation') || 'horizontal';
        var trigger      = $wrap.data('trigger') || 'drag';
        var initialPos   = parseFloat($wrap.data('initial-pos')) || 50;
        var introSweep   = $wrap.data('intro-sweep') === 'yes';
        var hoverReset   = $wrap.data('hover-reset') === 'yes';
        var labelMode    = $wrap.data('label-mode') || 'auto_fade';
        var isHorizontal = orientation === 'horizontal';

        var currentPos   = initialPos;
        var isDragging   = false;
        var hasCaptured  = false;
        var startX       = 0;
        var startY       = 0;
        var rafId        = null;
        var transitionTimer = null;

        /**
         * Update Slider Position via CSS Variable
         */
        function updatePosition(pos, withTransition) {
            // Clamp between 0% and 100%
            pos = Math.max(0, Math.min(100, pos));
            currentPos = pos;

            if (withTransition) {
                $wrap.addClass('ua-has-transition');
                clearTimeout(transitionTimer);
                transitionTimer = setTimeout(function() {
                    $wrap.removeClass('ua-has-transition');
                }, 480);
            } else {
                $wrap.removeClass('ua-has-transition');
            }

            wrapEl.style.setProperty('--ua-ic-pos', pos + '%');
            dividerWrap.setAttribute('aria-valuenow', Math.round(pos));

            // Smart Label Collision Handling
            if (labelMode === 'auto_fade') {
                checkLabelCollision(pos);
            }
        }

        /**
         * Detect if divider is overlapping or too close to labels
         */
        function checkLabelCollision(pos) {
            // Near start: hide before label
            if (pos <= 18) {
                $labelBefore.addClass('ua-ic-label-hidden');
            } else {
                $labelBefore.removeClass('ua-ic-label-hidden');
            }

            // Near end: hide after label
            if (pos >= 82) {
                $labelAfter.addClass('ua-ic-label-hidden');
            } else {
                $labelAfter.removeClass('ua-ic-label-hidden');
            }
        }

        /**
         * Calculate Percentage Position from Pointer Event Coordinates
         */
        function calcPosFromEvent(e) {
            var rect = container.getBoundingClientRect();
            if (rect.width === 0 || rect.height === 0) {
                return currentPos;
            }

            var clientX = e.clientX !== undefined ? e.clientX : (e.touches && e.touches[0] ? e.touches[0].clientX : 0);
            var clientY = e.clientY !== undefined ? e.clientY : (e.touches && e.touches[0] ? e.touches[0].clientY : 0);

            var pct;
            if (isHorizontal) {
                pct = ((clientX - rect.left) / rect.width) * 100;
            } else {
                pct = ((clientY - rect.top) / rect.height) * 100;
            }

            return Math.max(0, Math.min(100, pct));
        }

        // Initialize to starting position
        updatePosition(initialPos, false);

        // ============================================
        // Trigger Mode: DRAG & SLIDE (Native Pointer Events)
        // ============================================
        if (trigger === 'drag') {
            function onPointerDown(e) {
                if (e.button !== undefined && e.button !== 0 && e.pointerType === 'mouse') {
                    return;
                }

                startX = e.clientX !== undefined ? e.clientX : 0;
                startY = e.clientY !== undefined ? e.clientY : 0;
                isDragging = true;
                hasCaptured = false;

                var isOnHandle = Boolean(e.target && e.target.closest && e.target.closest('.ua-ic-divider-wrap'));

                if (isOnHandle || e.pointerType === 'mouse') {
                    $wrap.addClass('ua-is-dragging');
                    if (container.setPointerCapture && e.pointerId !== undefined) {
                        try {
                            container.setPointerCapture(e.pointerId);
                            hasCaptured = true;
                        } catch (err) {}
                    }
                    var newPos = calcPosFromEvent(e);
                    updatePosition(newPos, false);
                    e.preventDefault();
                }
            }

            function onPointerMove(e) {
                if (!isDragging) return;

                var clientX = e.clientX !== undefined ? e.clientX : 0;
                var clientY = e.clientY !== undefined ? e.clientY : 0;
                var diffX = Math.abs(clientX - startX);
                var diffY = Math.abs(clientY - startY);

                if (!hasCaptured && e.pointerType === 'touch') {
                    if (isHorizontal && diffX > diffY && diffX > 6) {
                        if (container.setPointerCapture && e.pointerId !== undefined) {
                            try {
                                container.setPointerCapture(e.pointerId);
                                hasCaptured = true;
                            } catch (err) {}
                        }
                        $wrap.addClass('ua-is-dragging');
                    } else if (!isHorizontal && diffY > diffX && diffY > 6) {
                        if (container.setPointerCapture && e.pointerId !== undefined) {
                            try {
                                container.setPointerCapture(e.pointerId);
                                hasCaptured = true;
                            } catch (err) {}
                        }
                        $wrap.addClass('ua-is-dragging');
                    } else if ((isHorizontal && diffY > 10) || (!isHorizontal && diffX > 10)) {
                        isDragging = false;
                        return;
                    }
                }

                if (rafId) cancelAnimationFrame(rafId);
                rafId = requestAnimationFrame(function() {
                    var newPos = calcPosFromEvent(e);
                    updatePosition(newPos, false);
                });
            }

            function onPointerUp(e) {
                if (!isDragging) return;
                isDragging = false;
                $wrap.removeClass('ua-is-dragging');

                if (hasCaptured && container.releasePointerCapture && e.pointerId !== undefined) {
                    try {
                        container.releasePointerCapture(e.pointerId);
                    } catch (err) {}
                }
                hasCaptured = false;
            }

            container.addEventListener('pointerdown', onPointerDown, { passive: false });
            container.addEventListener('pointermove', onPointerMove, { passive: true });
            container.addEventListener('pointerup', onPointerUp, { passive: true });
            container.addEventListener('pointercancel', onPointerUp, { passive: true });
        }

        // ============================================
        // Trigger Mode: MOUSE HOVER
        // ============================================
        else if (trigger === 'hover') {
            container.addEventListener('pointermove', function(e) {
                if (rafId) cancelAnimationFrame(rafId);
                rafId = requestAnimationFrame(function() {
                    var newPos = calcPosFromEvent(e);
                    updatePosition(newPos, false);
                });
            }, { passive: true });

            if (hoverReset) {
                container.addEventListener('pointerleave', function() {
                    updatePosition(initialPos, true);
                }, { passive: true });
            }
        }

        // ============================================
        // Trigger Mode: CLICK TO MOVE
        // ============================================
        else if (trigger === 'click') {
            container.addEventListener('click', function(e) {
                var newPos = calcPosFromEvent(e);
                updatePosition(newPos, true);
            });
        }

        // ============================================
        // Keyboard Accessibility (WCAG / ADA)
        // ============================================
        dividerWrap.addEventListener('keydown', function(e) {
            var step = e.shiftKey ? 10 : 2;
            var changed = false;
            var targetPos = currentPos;

            if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                targetPos -= step;
                changed = true;
            } else if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                targetPos += step;
                changed = true;
            } else if (e.key === 'Home') {
                targetPos = 0;
                changed = true;
            } else if (e.key === 'End') {
                targetPos = 100;
                changed = true;
            }

            if (changed) {
                e.preventDefault();
                updatePosition(targetPos, true);
            }
        });

        // ============================================
        // Intro Demo Sweep Animation (IntersectionObserver)
        // (Skipped in Elementor edit mode for undisturbed editing)
        // ============================================
        var isEditMode = typeof elementorFrontend !== 'undefined' && elementorFrontend.isEditMode && elementorFrontend.isEditMode();
        if (introSweep && !isEditMode && 'IntersectionObserver' in window) {
            var sweepObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        sweepObserver.disconnect();

                        // Gentle intro sweep to signal interactivity
                        setTimeout(function() {
                            if (isDragging) return;
                            var sweep1 = Math.max(20, initialPos - 22);
                            var sweep2 = Math.min(80, initialPos + 22);

                            updatePosition(sweep1, true);

                            setTimeout(function() {
                                if (isDragging) return;
                                updatePosition(sweep2, true);

                                setTimeout(function() {
                                    if (isDragging) return;
                                    updatePosition(initialPos, true);
                                }, 450);
                            }, 450);
                        }, 300);
                    }
                });
            }, { threshold: 0.35 });

            sweepObserver.observe(container);
        }
    };

    /**
     * Elementor Frontend Hook Registration
     */
    function registerImageComparisonHook() {
        if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/ultraaddons-image-comparison.default',
                UAImageComparisonHandler
            );
        }
    }

    if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
        registerImageComparisonHook();
    } else {
        $(window).on('elementor/frontend/init', registerImageComparisonHook);
    }

    // Static DOM Ready Fallback for Cached / Non-Elementor Execution
    $(function() {
        if (typeof elementorFrontend === 'undefined' || !elementorFrontend.isEditMode()) {
            $('.elementor-widget-ultraaddons-image-comparison').each(function() {
                UAImageComparisonHandler($(this), jQuery);
            });
        }
    });

})(jQuery);
