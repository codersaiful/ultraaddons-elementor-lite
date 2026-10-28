/**
 * UltraAddons - Woo Account Widget Script
 *
 * Handles:
 * - Password Show/Hide toggle
 * - Login / Registration tab switcher
 * - Mobile responsive menu accordion
 * - Elementor editor live re-initialization
 *
 * @package UltraAddons
 * @version 3.1.2
 */

(function ($) {
    'use strict';

    var UltraAddonsWooAccount = {
        /**
         * Initialize all widget instances on page
         */
        init: function ($scope) {
            var $wrapper = $scope.find('.ua-woo-account');
            if (!$wrapper.length) {
                return;
            }

            UltraAddonsWooAccount.initPasswordToggle($wrapper);
            UltraAddonsWooAccount.initAuthTabs($wrapper);
            UltraAddonsWooAccount.initMobileNav($wrapper);
        },

        /**
         * Password visibility toggle (Eye icon)
         */
        initPasswordToggle: function ($wrapper) {
            $wrapper.on('click', '.ua-pwd-toggle', function (e) {
                e.preventDefault();
                var $btn = $(this);
                var $input = $btn.siblings('input');
                var $icon = $btn.find('i');

                if ($input.attr('type') === 'password') {
                    $input.attr('type', 'text');
                    $icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    $input.attr('type', 'password');
                    $icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        },

        /**
         * Logged-Out Switcher (Sign In vs Register tabs)
         */
        initAuthTabs: function ($wrapper) {
            $wrapper.on('click', '.ua-auth-tab', function (e) {
                e.preventDefault();
                var $tab = $(this);
                var target = $tab.data('auth-target'); // 'login' or 'register'
                var $box = $tab.closest('.ua-account-logged-out-box');

                $tab.siblings('.ua-auth-tab').removeClass('is-active');
                $tab.addClass('is-active');

                $box.find('.ua-auth-pane').removeClass('is-active');
                if (target === 'register') {
                    $box.find('.ua-auth-register').addClass('is-active');
                } else {
                    $box.find('.ua-auth-login').addClass('is-active');
                }
            });
        },

        /**
         * Mobile Navigation Collapsible
         */
        initMobileNav: function ($wrapper) {
            $wrapper.on('click', '.ua-mobile-nav-toggle', function (e) {
                e.preventDefault();
                var $wrap = $(this).closest('.ua-account-nav-wrap');
                $wrap.toggleClass('ua-menu-open');
            });
        }
    };

    // Document Ready Handler
    $(document).ready(function () {
        $('.ua-woo-account').each(function () {
            UltraAddonsWooAccount.init($(this).closest('.elementor-widget-ultraaddons-woo-account'));
        });
    });

    // Elementor Frontend Init Hook
    $(window).on('elementor/frontend/init', function () {
        if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/ultraaddons-woo-account.default',
                UltraAddonsWooAccount.init
            );
        }
    });

})(jQuery);
