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

        init() {
            this.layoutNodes();
            this.bindEvents();
            this.activate(0);

            if (this.autoplay) {
                this.startAutoplay();
            }

            // Recalculate on window resize with debounce
            let resizeTimer;
            $(window).on('resize.uacircle orientationchange.uacircle', () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => {
                    this.layoutNodes();
                }, 100);
            });

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

            // Radius calculation ensures nodes fit comfortably along the circular perimeter
            let radius = (stageWidth / 2) - (nodeWidth / 2);
            if (radius <= 0) radius = 100;

            const isHalfMoon = this.preset === 'half_moon';
            const cx = stageWidth / 2;
            const cy = isHalfMoon ? stageHeight - (nodeWidth / 2) - 10 : stageHeight / 2;

            // Prepare SVG canvas for spoke lines
            if (this.$spokeLayer.length) {
                this.$spokeLayer.empty();
                this.$spokeLayer.attr('viewBox', `0 0 ${stageWidth} ${stageHeight}`);
            }

            this.$nodes.each((i, el) => {
                let angle;
                if (isHalfMoon) {
                    // Spread along top semicircle: from PI (left) to 2*PI (right)
                    const step = this.total > 1 ? Math.PI / (this.total - 1) : 0;
                    angle = Math.PI + (step * i);
                } else {
                    // Full 360 circle starting at 12 o'clock (-PI / 2)
                    angle = ((2 * Math.PI) / this.total) * i - (Math.PI / 2);
                }

                const x = radius * Math.cos(angle);
                const y = radius * Math.sin(angle);

                // Set node transform relative to stage center
                el.style.transform = `translate(${x}px, ${y}px)`;

                // Generate SVG spoke lines if preset is cyber_spoke or enabled
                if (this.$spokeLayer.length) {
                    const spokeLine = document.createElementNS('http://www.w3.org/2000/svg', 'line');
                    spokeLine.setAttribute('x1', cx);
                    spokeLine.setAttribute('y1', cy);
                    spokeLine.setAttribute('x2', cx + x);
                    spokeLine.setAttribute('y2', cy + y);
                    spokeLine.setAttribute('class', `ua-ic-spoke-line ua-ic-spoke-${i}`);
                    this.$spokeLayer[0].appendChild(spokeLine);
                }
            });

            // Re-highlight current active spoke
            this.highlightSpoke(this.currentIndex);
        }

        bindEvents() {
            const self = this;

            this.$nodes.each(function (index) {
                const $node = $(this);

                if (self.trigger === 'hover') {
                    $node.on('mouseenter', function () {
                        self.activate(index);
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

        activate(index) {
            if (index < 0 || index >= this.total) return;

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