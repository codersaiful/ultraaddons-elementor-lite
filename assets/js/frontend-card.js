/**
 * UltraAddons Modern Card Widget Frontend Script
 *
 * Provides interactive enhancements such as wishlist toggle state.
 */
(function ($) {
    'use strict';

    var CardWidgetHandler = function ($scope, $) {
        var $wishBtn = $scope.find('.ua-card-wish-btn');

        if ($wishBtn.length) {
            $wishBtn.on('click', function (e) {
                var $this = $(this);
                // If it's a dummy link or '#', prevent default navigation and toggle active state
                if ($this.attr('href') === '#' || !$this.attr('href')) {
                    e.preventDefault();
                    $this.toggleClass('ua-card-wish-active');
                    
                    var $icon = $this.find('i');
                    if ($icon.length) {
                        if ($this.hasClass('ua-card-wish-active')) {
                            $icon.removeClass('far').addClass('fas');
                        }
                    }
                }
            });
        }
    };

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/Card.default',
            CardWidgetHandler
        );
    });

})(jQuery);
