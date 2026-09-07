/**
 * UltraAddons - Modern Navigation Menu Frontend Handler
 * 
 * Clean, lightweight, zero-dependency navigation controller.
 * Handles mobile toggle, smooth off-canvas drawer, multi-level accordion submenus,
 * smart boundary flyout flipping, one-page sticky anchor scrolling,
 * and seamless active state switching in editor and frontend.
 * 
 * @package UltraAddons
 * @author Saiful Islam <codersaiful@gmail.com>
 * @version 2.2.0
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

        var mobileLayout   = settings.mobile_layout || 'dropdown';
        var stickyOffset   = settings.sticky_offset ? parseInt(settings.sticky_offset, 10) : 0;
        var submenuTrigger = settings.submenu_trigger || 'hover';
        var isEditMode     = (window.elementorFrontend && typeof elementorFrontend.isEditMode === 'function' && elementorFrontend.isEditMode());

        var isRealUrl = function (href) {
            if (!href) return false;
            var cleanHref = href.trim();
            if (cleanHref === '' || cleanHref === '#' || cleanHref.indexOf('#') === 0) {
                return false;
            }
            if (cleanHref.indexOf('javascript:') === 0) {
                return false;
            }
            return true;
        };

        // -------------------------------------------------------------
        // 1. Interactive Active State Switcher
        // -------------------------------------------------------------
        var setActiveItem = function ($link) {
            if (!$link || !$link.length) return;

            var $topItem = $link.closest('.ua-nav-top-item');
            if (!$topItem.length) {
                $topItem = $link.parent('.ua-nav-item');
            }
            if (!$topItem.length) return;

            // Remove all active & current classes from every item in this widget
            $wrapper.find('.ua-nav-item').removeClass(
                'current-menu-item current_page_item current-menu-parent current_page_parent current-menu-ancestor current_page_ancestor ua-active-item'
            );

            // Add active classes to the top-level item
            $topItem.addClass('ua-active-item current-menu-item');

            // If a nested submenu link was clicked, mark the item and give parent ancestor classes
            var $clickedItem = $link.parent('.ua-nav-item');
            if ($clickedItem.length && !$clickedItem.hasClass('ua-nav-top-item')) {
                $clickedItem.addClass('ua-active-item current-menu-item');
                $topItem.addClass('current-menu-parent current-menu-ancestor');
            }

            // Synchronize active item index between desktop, mobile dropdown, and drawer
            var topIndex = $topItem.index();
            $wrapper.find('.ua-desktop-nav, .ua-mobile-dropdown, .ua-nav-drawer').each(function () {
                var $containers = $(this);
                $containers.find('> .ua-nav-list > .ua-nav-top-item, .ua-nav-drawer-body > .ua-nav-list > .ua-nav-top-item')
                    .eq(topIndex)
                    .addClass('ua-active-item current-menu-item');
            });
        };

        // If no item is currently active in editor preview, activate the first one
        if (isEditMode && !$wrapper.find('.ua-nav-top-item.current-menu-item, .ua-nav-top-item.ua-active-item').length) {
            $wrapper.find('.ua-nav-top-item').first().addClass('ua-active-item current-menu-item');
        }

        // Universal link click handler for active state
        $wrapper.off('click.uaactive', '.ua-nav-link').on('click.uaactive', '.ua-nav-link', function (e) {
            var $link = $(this);
            setActiveItem($link);

            // In Elementor editor, prevent following page links so active preview is smooth
            if (isEditMode) {
                var href = $link.attr('href');
                if (!href || href === '#' || href.indexOf('http') === 0 || href.indexOf('/') === 0) {
                    e.preventDefault();
                }
            }
        });

        // -------------------------------------------------------------
        // 2. Mobile Menu Open / Close
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
        // 3. Mobile Submenu Multi-Level Accordion (Click & Hover Support)
        // -------------------------------------------------------------
        var $mobileContainers = $wrapper.find('.ua-mobile-dropdown, .ua-nav-drawer');

        // Click Handler (Always enabled for touchscreen & manual toggles)
        $mobileContainers.off('click.uasub').on('click.uasub', '.menu-item-has-children > .ua-nav-link', function (e) {
            var $link = $(this);
            var $item = $link.parent('.menu-item-has-children');
            var $submenu = $item.children('.ua-sub-menu');
            var isOpen = $item.hasClass('ua-sub-open');
            var isIndicator = $(e.target).closest('.ua-sub-indicator').length > 0;
            var href = $link.attr('href');
            var hasRealUrl = isRealUrl(href);

            // Switch active item on click immediately
            setActiveItem($link);

            if ($submenu.length) {
                if (isIndicator || !hasRealUrl) {
                    // Tapping indicator arrow or dummy '#' link toggles accordion
                    e.preventDefault();
                    e.stopPropagation();

                    if (isOpen) {
                        $item.removeClass('ua-sub-open');
                        $submenu.stop(true, true).slideUp(220);
                    } else {
                        $item.addClass('ua-sub-open');
                        $submenu.stop(true, true).slideDown(220);
                    }
                } else {
                    // Parent link has a real destination URL (e.g. Home, Checkout, Cart)
                    if (!isOpen) {
                        // First tap: expand accordion so user can discover submenu items
                        e.preventDefault();
                        e.stopPropagation();
                        $item.addClass('ua-sub-open');
                        $submenu.stop(true, true).slideDown(220);
                    } else {
                        // Submenu is already open: allow navigation to parent page!
                        if (isEditMode) {
                            e.preventDefault();
                        } else {
                            closeMobileMenu();
                            // Browser navigates to href naturally
                        }
                    }
                }
            }
        });

        // Hover Handler (Active when Submenu Trigger is 'Mouse Hover')
        if (submenuTrigger === 'hover') {
            $mobileContainers
                .off('mouseenter.uasub mouseleave.uasub')
                .on('mouseenter.uasub', '.menu-item-has-children', function (e) {
                    var $item = $(this);
                    var $submenu = $item.children('.ua-sub-menu');
                    if ($submenu.length) {
                        $item.addClass('ua-sub-open');
                        $submenu.stop(true, true).slideDown(220);
                    }
                })
                .on('mouseleave.uasub', '.menu-item-has-children', function (e) {
                    var $item = $(this);
                    var $submenu = $item.children('.ua-sub-menu');
                    if ($submenu.length) {
                        $item.removeClass('ua-sub-open');
                        $submenu.stop(true, true).slideUp(220);
                    }
                });
        }

        // -------------------------------------------------------------
        // 4. Desktop Submenu Flyout Smart Boundary Detection
        // -------------------------------------------------------------
        $wrapper.find('.ua-desktop-nav .ua-sub-menu .menu-item-has-children').on('mouseenter', function () {
            var $item = $(this);
            var $nestedSub = $item.children('.ua-sub-menu');
            if (!$nestedSub.length) return;

            var windowWidth = $(window).width();
            var itemOffset = $item.offset();
            var itemWidth = $item.outerWidth();
            var subWidth = $nestedSub.outerWidth() || 220;

            // If opening to the right would overflow the screen edge, flip to the left
            if (itemOffset && (itemOffset.left + itemWidth + subWidth > windowWidth)) {
                $nestedSub.addClass('ua-flyout-left');
            } else {
                $nestedSub.removeClass('ua-flyout-left');
            }
        });

        // -------------------------------------------------------------
        // 5. Desktop Submenu Click Trigger (if configured)
        // -------------------------------------------------------------
        if (settings.submenu_trigger === 'click') {
            var widgetId = $scope.data('id') || Math.random().toString(36).substring(2);

            $wrapper.find('.ua-desktop-nav .menu-item-has-children > .ua-nav-link').off('click.uaclick').on('click.uaclick', function (e) {
                var $link = $(this);
                var $item = $link.parent('.menu-item-has-children');
                var $submenu = $item.children('.ua-sub-menu');
                var isOpen = $item.hasClass('ua-sub-open');
                var isIndicator = $(e.target).closest('.ua-sub-indicator').length > 0;
                var href = $link.attr('href');
                var hasRealUrl = isRealUrl(href);

                // Switch active item on click immediately
                setActiveItem($link);

                if ($submenu.length) {
                    if (isIndicator || !hasRealUrl) {
                        // Clicking indicator arrow or dummy '#' link: toggle submenu
                        e.preventDefault();
                        e.stopPropagation();

                        if (isOpen) {
                            $item.removeClass('ua-sub-open');
                        } else {
                            // Close sibling open submenus at this level
                            $item.siblings('.menu-item-has-children').removeClass('ua-sub-open');
                            $item.addClass('ua-sub-open');
                        }
                    } else {
                        // Parent link has a real destination URL (e.g. Home, Checkout, Cart)
                        if (!isOpen) {
                            // First click opens the dropdown/flyout so user can see submenu
                            e.preventDefault();
                            e.stopPropagation();

                            $item.siblings('.menu-item-has-children').removeClass('ua-sub-open');
                            $item.addClass('ua-sub-open');
                        } else {
                            // Submenu is ALREADY open: clicking the parent link follows the URL!
                            if (isEditMode) {
                                e.preventDefault();
                            }
                            // In frontend, do NOT call e.preventDefault() -> Browser navigates to href!
                        }
                    }
                }
            });

            // Close when clicking outside
            $(document).off('click.uaclose_' + widgetId).on('click.uaclose_' + widgetId, function (e) {
                if (!$(e.target).closest($wrapper).length) {
                    $wrapper.find('.ua-desktop-nav .ua-sub-open').removeClass('ua-sub-open');
                }
            });
        }

        // -------------------------------------------------------------
        // 6. One-Page Smooth Anchor Scroll
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

    // Robust registration for both Elementor hook & standard DOM ready
    var registerHandler = function () {
        if (window.elementorFrontend && window.elementorFrontend.hooks) {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/ultraaddons-navigation-menu.default',
                UltraNavMenuHandler
            );
        } else {
            $(window).on('elementor/frontend/init', function () {
                if (window.elementorFrontend && window.elementorFrontend.hooks) {
                    elementorFrontend.hooks.addAction(
                        'frontend/element_ready/ultraaddons-navigation-menu.default',
                        UltraNavMenuHandler
                    );
                }
            });
        }
    };
    registerHandler();

    // Direct DOM ready initialization fallback
    $(function () {
        $('.elementor-widget-ultraaddons-navigation-menu').each(function () {
            UltraNavMenuHandler($(this));
        });
    });

})(jQuery);