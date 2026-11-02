/**
 * UltraAddons Stacked Cards Frontend Interactivity
 *
 * Handles scroll-based card stacking depth, active card detection,
 * counter updating, and clickable dot navigation.
 *
 * @package UltraAddons
 */
(function ($) {
    'use strict';

    var StackedCardsHandler = function ($scope, $) {
        var $wrap = $scope.find('.ua-stkcards-wrap');
        if (!$wrap.length) return;

        var $cards = $wrap.find('.ua-stkcards-item');
        if (!$cards.length) return;

        var $dots = $wrap.find('.ua-stkcards-dot');
        var $counterCurr = $wrap.find('.ua-stkcards-counter-curr');
        var totalCards = $cards.length;

        var currentIndex = 0;
        var ticking = false;

        /**
         * Update active index across DOM, dots, and counter
         */
        function updateActiveCard(index) {
            if (index < 0) index = 0;
            if (index >= totalCards) index = totalCards - 1;

            if (currentIndex === index && $cards.eq(index).hasClass('ua-stkcards-active')) {
                return;
            }

            currentIndex = index;

            // Update cards
            $cards.each(function (i) {
                var $card = $(this);
                if (i === index) {
                    $card.addClass('ua-stkcards-active').removeClass('ua-scrolled-past');
                    $card.css('--ua-stkcards-depth', 0);
                } else if (i < index) {
                    $card.removeClass('ua-stkcards-active').addClass('ua-scrolled-past');
                    $card.css('--ua-stkcards-depth', index - i);
                } else {
                    $card.removeClass('ua-stkcards-active ua-scrolled-past');
                    $card.css('--ua-stkcards-depth', 0);
                }
            });

            // Update dots
            if ($dots.length) {
                $dots.removeClass('ua-active');
                $dots.filter('[data-card-index="' + index + '"]').addClass('ua-active');
            }

            // Update counter
            if ($counterCurr.length) {
                var formatted = (index + 1) < 10 ? '0' + (index + 1) : (index + 1);
                $counterCurr.text(formatted);
            }
        }

        /**
         * Calculate which card is active based on viewport scroll
         */
        function onScroll() {
            var scrollY = window.pageYOffset || document.documentElement.scrollTop;
            var windowH = window.innerHeight;

            var activeIdx = 0;

            $cards.each(function (i) {
                var cardEl = this;
                var rect = cardEl.getBoundingClientRect();
                var cardTop = rect.top;

                // Threshold: when card reaches the upper sticky viewport area
                var threshold = 180 + (i * 15);

                if (cardTop <= threshold) {
                    activeIdx = i;
                }
            });

            updateActiveCard(activeIdx);
            ticking = false;
        }

        function requestScrollUpdate() {
            if (!ticking) {
                window.requestAnimationFrame(onScroll);
                ticking = true;
            }
        }

        // Attach scroll listener
        $(window).on('scroll.uaStackedCards resize.uaStackedCards', requestScrollUpdate);

        // Clickable navigation dots
        if ($dots.length) {
            $dots.on('click', function (e) {
                e.preventDefault();
                var targetIdx = parseInt($(this).attr('data-card-index'), 10);
                if (isNaN(targetIdx) || targetIdx < 0 || targetIdx >= totalCards) return;

                var $targetCard = $cards.eq(targetIdx);
                if ($targetCard.length) {
                    var cardOffsetTop = $targetCard.offset().top;
                    var stickyOffset = 100 + (targetIdx * 24);

                    $('html, body').stop().animate({
                        scrollTop: cardOffsetTop - stickyOffset
                    }, 500, 'swing', function () {
                        updateActiveCard(targetIdx);
                    });
                }
            });
        }

        // Initial trigger
        requestScrollUpdate();
    };

    $(window).on('elementor/frontend/init', function () {
        if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/ultraaddons-stacked-cards.default',
                StackedCardsHandler
            );
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/Stacked_Cards.default',
                StackedCardsHandler
            );
        }
    });

})(jQuery);
