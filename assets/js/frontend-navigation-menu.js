/**
 * UltraAddons - Modern Navigation Menu Frontend Handler
 * 
 * Clean, lightweight, zero-dependency navigation controller.
 * Handles mobile toggle, smooth off-canvas drawer, multi-level accordion submenus,
 * keyboard accessibility, and one-page sticky anchor scrolling.
 * 
 * @package UltraAddons
 * @author Saiful Islam <codersaiful@gmail.com>
 * @version 2.0.0
 */
(function ($) {
    'use strict';

    var UltraNavMenuHandler = function ($scope) {
        var $wrapper = $scope.find('.ua-nav-menu-wrapper');
        if (!$wrapper.length) {
            return;
        }

        var $toggleBtn    = $wrapper.find('.ua-nav-toggle-btn');
        var $dropdown     = $wrapper.find('.ua-mobile-dropdown');
        var $drawer       = $wrapper.find('.ua-nav-drawer');
        var $overlay      = $wrapper.find('.ua-drawer-overlay');
        var $drawerClose  = $wrapper.find('.ua-nav-drawer-close');
        var settingsRaw   = $wrapper.attr('data-ua-nav-settings');
        var settings      = {};

        if (settingsRaw) {
            try {
                settings = JSON.parse(settingsRaw);
            } catch (e) {
                settings = {};
            }
        }

        var mobileLayout = settings.mobile_layout || 'dropdown';
        var stickyOffset = settings.sticky_offset ? parseInt(settings.sticky_offset, 10) : 0;

        // -------------------------------------------------------------
        // 1. Mobile Menu Open / Close
        // -------------------------------------------------------------
        var openMobileMenu = function () {
            $toggleBtn.addClass('ua-toggle-active').attr('aria-expanded', 'true');
            if (mobileLayout === 'drawer') {
                $drawer.addClass('ua-drawer-open');
                $overlay.addClass('ua-drawer-open');
                $('body').addClass('ua-nav-drawer-active');
            } else {
                $dropdown.addClass('ua-dropdown-open');
            }
        };

        var closeMobileMenu = function () {
            $toggleBtn.removeClass('ua-toggle-active').attr('aria-expanded', 'false');
            if (mobileLayout === 'drawer') {
                $drawer.removeClass('ua-drawer-open');
                $overlay.removeClass('ua-drawer-open');
                $('body').removeClass('ua-nav-drawer-active');
            } else {
                $dropdown.removeClass('ua-dropdown-open');
            }
        };

        $toggleBtn.off('click.uanav').on('click.uanav', function (e) {
            e.preventDefault();
            if ($toggleBtn.hasClass('ua-toggle-active')) {
                closeMobileMenu();
            } else {
                openMobileMenu();
            }
        });

        $overlay.off('click.uanav').on('click.uanav', closeMobileMenu);
        $drawerClose.off('click.uanav').on('click.uanav', closeMobileMenu);

        // Close on ESC key
        $(document).off('keyup.uanav').on('keyup.uanav', function (e) {
            if (e.key === 'Escape' || e.keyCode === 27) {
                closeMobileMenu();
            }
        });

        // -------------------------------------------------------------
        // 2. Mobile Submenu Accordion Expansion
        // -------------------------------------------------------------
        $wrapper.find('.ua-mobile-dropdown, .ua-nav-drawer').on('click', '.menu-item-has-children > .ua-nav-link', function (e) {
            var $link = $(this);
            var $item = $link.parent();
            var $submenu = $item.children('.ua-sub-menu');

            if ($submenu.length) {
                e.preventDefault();
                $item.toggleClass('ua-sub-open');
                $submenu.toggleClass('ua-sub-expanded').slideToggle(220);
            }
        });

        // -------------------------------------------------------------
        // 3. Desktop Submenu Click Trigger (if configured)
        // -------------------------------------------------------------
        if (settings.submenu_trigger === 'click') {
            $wrapper.find('.ua-desktop-nav .menu-item-has-children > .ua-nav-link').off('click.uasub').on('click.uasub', function (e) {
                var $item = $(this).parent();
                var isOpen = $item.hasClass('ua-sub-open');

                // Close sibling open submenus
                $item.siblings('.menu-item-has-children').removeClass('ua-sub-open');

                if (!isOpen) {
                    e.preventDefault();
                    $item.addClass('ua-sub-open');
                }
            });

            // Close when clicking outside
            $(document).on('click.uaclose', function (e) {
                if (!$(e.target).closest('.ua-nav-menu-wrapper').length) {
                    $wrapper.find('.ua-desktop-nav .ua-sub-open').removeClass('ua-sub-open');
                }
            });
        }

        // -------------------------------------------------------------
        // 4. One-Page Smooth Anchor Scroll
        // -------------------------------------------------------------
        $wrapper.find('a[href*="#"]').off('click.uascroll').on('click.uascroll', function (e) {
            var href = $(this).attr('href');
            var hashIndex = href.indexOf('#');
            if (hashIndex === -1) {
                return;
            }

            var hash = href.substring(hashIndex);
            if (hash.length > 1 && $(hash).length) {
                var targetTop = $(hash).offset().top - stickyOffset;
                e.preventDefault();
                closeMobileMenu();

                $('html, body').animate({
                    scrollTop: targetTop
                }, 450);
            }
        });
    };

    // Robust Elementor registration
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/ultraaddons-navigation-menu.default',
            UltraNavMenuHandler
        );
    });

})(jQuery);