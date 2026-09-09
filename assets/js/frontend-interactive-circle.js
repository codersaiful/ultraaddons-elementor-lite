/**
 * UltraAddons - Interactive Circle Frontend JavaScript
 *
 * Dynamic trigonometry positioning, interactive triggers (click/hover),
 * autoplay cycle, dynamic SVG spoke lines, and responsive recalculations.
 *
 * @package UltraAddons
 * @since 1.1.8
 */

(function ($) {
    'use strict';

    class UAInteractiveCircle {
        constructor($scope) {
            this.$scope = $scope;
            this.$wrap = $scope.find('.ua-interactive-circle-wrap');
            if (!this.$wrap.length) return;

            // Clean up any existing instance on this scope during Elementor live re-renders
            const oldInstance = this.$scope.data('uaInteractiveCircle');
            if (oldInstance && typeof oldInstance.destroy === 'function') {
                oldInstance.destroy();
            }
            this.$scope.data('uaInteractiveCircle', this);

            this.$stage = this.$wrap.find('.ua-ic-orbit-stage');
            this.$nodes = this.$wrap.find('.ua-ic-node-item');
            this.$contents = this.$wrap.find('.ua-ic-content-item');
            this.$spokeLayer = this.$wrap.find('.ua-ic-spoke-layer');

            this.total = this.$nodes.length;
            if (this.total === 0) return;

            this.preset = this.$wrap.data('preset') || 'full_orbit';
            this.trigger = this.$wrap.data('trigger') || 'click';
            this.autoplay = Boolean(this.$wrap.data('autoplay'));
            this.interval = parseInt(this.$wrap.data('interval'), 10) || 3500;
            this.pauseOnHover = Boolean(this.$wrap.data('pause-hover'));

            this.currentIndex = 0;
            this.timer = null;
            this.isHovered = false;

            this.init();
        }

        destroy() {
            this.stopAutoplay();
            if (this.resizeHandler) {
                $(window).off('resize orientationchange', this.resizeHandler);
            }
        }

        init() {
            this.layoutNodes();
            this.bindEvents();
            this.activate(0);

            if (this.autoplay) {
                this.startAutoplay();
            }

            // Recalculate on window resize with debounce
            let resizeTimer;
            this.resizeHandler = () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => {
                    this.layoutNodes();
                }, 100);
            };
            $(window).on('resize orientationchange', this.resizeHandler);

            // Modern ResizeObserver if supported
            if (window.ResizeObserver && this.$stage[0]) {
                const ro = new ResizeObserver(() => {
                    this.layoutNodes();
                });
                ro.observe(this.$stage[0]);
            }
        }

        /**
         * Calculate precise geometric positions for each node using trigonometry
         */
        layoutNodes() {
            const stageWidth = this.$stage.outerWidth();
            const stageHeight = this.$stage.outerHeight();
            const nodeWidth = this.$nodes.first().outerWidth() || 60;

            const isHalfMoon = (this.preset === 'half_moon');
            const isSpoke    = (this.preset === 'cyber_spoke');
            const isPointer  = (this.preset === 'inward_pointer' || this.preset === 'pointer_badge');

            // Prepare SVG canvas
            if (this.$spokeLayer.length) {
                this.$spokeLayer.empty();
                this.$spokeLayer.attr('viewBox', `0 0 ${stageWidth} ${stageHeight}`);
            }

            if (isHalfMoon) {
                const cx = stageWidth / 2;
                const cy = stageHeight - (nodeWidth / 2) - 15;

                // Calculate safe radius so nodes never overflow horizontally or vertically
                const maxRadiusX = (stageWidth / 2) - (nodeWidth / 2) - 20;
                const maxRadiusY = cy - (nodeWidth / 2) - 20;
                const radius = Math.max(80, Math.min(maxRadiusX, maxRadiusY));

                // Offset relative to CSS top: 50%
                const yOrigin = cy - (stageHeight / 2);

                // Draw smooth SVG half-moon arch line
                if (this.$spokeLayer.length) {
                    const arch = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                    arch.setAttribute('d', `M ${cx - radius} ${cy} A ${radius} ${radius} 0 0 1 ${cx + radius} ${cy}`);
                    arch.setAttribute('class', 'ua-ic-arch-line');
                    this.$spokeLayer[0].appendChild(arch);
                }

                this.$nodes.each((i, el) => {
                    // Semicircle: from PI (left, 180deg) to 2*PI (right, 360deg/0deg)
                    const step = this.total > 1 ? Math.PI / (this.total - 1) : 0;
                    const angle = Math.PI + (step * i);

                    const relX = radius * Math.cos(angle);
                    const relY = yOrigin + (radius * Math.sin(angle));

                    el.style.transform = `translate(${relX}px, ${relY}px)`;
                });
            } else {
                // Full 360 circle presets (Full Orbit, Cyber Spoke, Inward Pointer, Minimal Flow)
                let radius = (stageWidth / 2) - (nodeWidth / 2);
                if (radius <= 0) radius = 100;
                const cx = stageWidth / 2;
                const cy = stageHeight / 2;

                this.$nodes.each((i, el) => {
                    // Full 360 circle starting at 12 o'clock (-PI / 2)
                    const angle = ((2 * Math.PI) / this.total) * i - (Math.PI / 2);

                    const x = radius * Math.cos(angle);
                    const y = radius * Math.sin(angle);

                    el.style.transform = `translate(${x}px, ${y}px)`;

                    // Inward Pointer Badges: exact radial angle pointing into center circle
                    if (isPointer) {
                        const pointerRotDeg = ((angle + Math.PI) * (180 / Math.PI)) - 45;
                        el.style.setProperty('--ua-ic-pointer-rot', `${pointerRotDeg}deg`);
                    }

                    // Cyber Spoke Network: Spoke lines & SVG junction dots
                    if (this.$spokeLayer.length && isSpoke) {
                        const spokeLine = document.createElementNS('http://www.w3.org/2000/svg', 'line');
                        spokeLine.setAttribute('x1', cx);
                        spokeLine.setAttribute('y1', cy);
                        spokeLine.setAttribute('x2', cx + x);
                        spokeLine.setAttribute('y2', cy + y);
                        spokeLine.setAttribute('class', `ua-ic-spoke-line ua-ic-spoke-${i}`);
                        this.$spokeLayer[0].appendChild(spokeLine);

                        // Junction dot at mid orbit ring (0.65 of stage)
                        const ringRadius = stageWidth * 0.325;
                        const dotX = cx + (ringRadius * Math.cos(angle));
                        const dotY = cy + (ringRadius * Math.sin(angle));
                        const dot = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                        dot.setAttribute('cx', dotX);
                        dot.setAttribute('cy', dotY);
                        dot.setAttribute('r', '4.5');
                        dot.setAttribute('class', `ua-ic-spoke-dot ua-ic-dot-${i}`);
                        this.$spokeLayer[0].appendChild(dot);
                    }
                });
            }

            // Re-highlight current active spoke
            this.highlightSpoke(this.currentIndex);
        }

        bindEvents() {
            const self = this;

            let hoverDebounceTimer = null;
            this.$nodes.each(function (index) {
                const $node = $(this);

                if (self.trigger === 'hover') {
                    $node.on('mouseenter', function () {
                        clearTimeout(hoverDebounceTimer);
                        hoverDebounceTimer = setTimeout(() => {
                            self.activate(index);
                        }, 40); // 40ms buffer eliminates twitchy accidental hover sweeps
                    });
                    $node.on('mouseleave', function () {
                        clearTimeout(hoverDebounceTimer);
                    });
                } else {
                    $node.on('click', function (e) {
                        e.preventDefault();
                        self.activate(index);
                    });
                }
            });

            if (this.autoplay && this.pauseOnHover) {
                this.$wrap.on('mouseenter', () => {
                    this.isHovered = true;
                    this.stopAutoplay();
                });

                this.$wrap.on('mouseleave', () => {
                    this.isHovered = false;
                    this.startAutoplay();
                });
            }

            // Keyboard navigation (Tab + Enter/Space or Arrow keys)
            this.$nodes.find('.ua-ic-node-btn').on('keydown', (e) => {
                if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                    e.preventDefault();
                    self.activate((self.currentIndex + 1) % self.total);
                } else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                    e.preventDefault();
                    self.activate((self.currentIndex - 1 + self.total) % self.total);
                }
            });
        }

        activate(index, force = false) {
            if (index < 0 || index >= this.total) return;

            // Guard against redundant activations on already active item
            if (!force && this.currentIndex === index && this.$contents.eq(index).hasClass('ua-ic-active')) {
                return;
            }

            this.currentIndex = index;

            // Update Nodes
            this.$nodes.removeClass('ua-ic-active');
            const $activeNode = this.$nodes.eq(index);
            $activeNode.addClass('ua-ic-active');

            // Update Content Stage
            this.$contents.removeClass('ua-ic-active');
            this.$contents.eq(index).addClass('ua-ic-active');

            // Update Spoke Lines
            this.highlightSpoke(index);
        }

        highlightSpoke(index) {
            if (!this.$spokeLayer.length) return;

            this.$spokeLayer.find('.ua-ic-spoke-line').removeClass('ua-ic-spoke-active');
            this.$spokeLayer.find(`.ua-ic-spoke-${index}`).addClass('ua-ic-spoke-active');

            this.$spokeLayer.find('.ua-ic-spoke-dot').removeClass('ua-ic-dot-active');
            this.$spokeLayer.find(`.ua-ic-dot-${index}`).addClass('ua-ic-dot-active');
        }

        startAutoplay() {
            this.stopAutoplay();
            this.timer = setInterval(() => {
                if (!this.isHovered) {
                    const nextIndex = (this.currentIndex + 1) % this.total;
                    this.activate(nextIndex);
                }
            }, this.interval);
        }

        stopAutoplay() {
            if (this.timer) {
                clearInterval(this.timer);
                this.timer = null;
            }
        }
    }

    // Elementor Hook Handler
    function initInteractiveCircle($scope) {
        new UAInteractiveCircle($scope);
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/ultraaddons-interactive-circle.default',
            initInteractiveCircle
        );
    });

    // Standalone / Standard DOM Ready initialization
    $(document).ready(function () {
        if (!window.elementorFrontend) {
            $('.ua-interactive-circle-wrap').each(function () {
                new UAInteractiveCircle($(this).closest('.elementor-widget'));
            });
        }
    });

})(jQuery);