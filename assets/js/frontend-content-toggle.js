/**
 * UltraAddons Content Toggle Frontend Script
 *
 * @package UltraAddons
 * @since 1.2.0
 */
;(function ($, w) {
    'use strict';

    var UltraAddonsContentToggle = function( $scope ) {

        var $contentToggle     = $scope.find( '.ua-content-toggle' ).first(),
            $switcherContainer = $contentToggle.find( '.ua-switcher-container' ).first(),
            $switcherWrap      = $contentToggle.find( '.ua-switcher-wrap' ).first(),
            $contentWrap       = $contentToggle.find( '.ua-switcher-content-wrap' ).first(),
            $switcherBg        = $switcherWrap.find( '> .ua-switcher-bg' ),
            $switcherList      = $switcherWrap.find( '> .ua-switcher' ),
            $contentList       = $contentWrap.find( '> .ua-switcher-content' ),
            isMulti            = $scope.hasClass( 'ua-switcher-style-multi' ) || $contentToggle.hasClass( 'ua-switcher-style-multi' ),
            isInner            = $scope.hasClass( 'ua-switcher-label-style-inner' ) || $contentToggle.hasClass( 'ua-switcher-label-style-inner' ),
            isDualOuter        = ! isMulti && ! isInner;

        if ( ! $contentToggle.length ) {
            return;
        }

        // Active Tab (1-based from data-active-switcher)
        var rawActive = parseInt( $switcherContainer.attr( 'data-active-switcher' ), 10 );
        var activeSwitcherIndex = ! isNaN( rawActive ) && rawActive > 0 ? rawActive - 1 : 0;

        if ( activeSwitcherIndex >= $switcherList.length ) {
            activeSwitcherIndex = 0;
        }

        // Initial classes
        $switcherList.removeClass( 'ua-switcher-active' );
        $contentList.removeClass( 'ua-switcher-content-active' );

        $switcherList.eq( activeSwitcherIndex ).addClass( 'ua-switcher-active' );
        $contentList.eq( activeSwitcherIndex ).addClass( 'ua-switcher-content-active' );

        // Move background pill indicator (for Multi or Inside labels)
        function updatePillBg( index ) {
            if ( isDualOuter ) {
                return;
            }

            var $activeTab = $switcherList.eq( index );
            if ( $activeTab.length ) {
                var tabLeft  = $activeTab.position().left;
                var tabWidth = $activeTab.outerWidth();

                $switcherBg.css({
                    'left' : tabLeft + 'px',
                    'width': tabWidth + 'px'
                });
            }
        }

        // Initial pill placement
        setTimeout( function() {
            updatePillBg( activeSwitcherIndex );
        }, 50 );

        // Tab Switcher function
        function switchTab( index ) {
            var $activeSwitcher = $switcherList.eq( index ),
                $activeContent  = $contentList.eq( index ),
                activeContentHeight = 'auto';

            if ( ! $activeContent.length ) {
                return;
            }

            // Update active state
            $switcherList.removeClass( 'ua-switcher-active' );
            $activeSwitcher.addClass( 'ua-switcher-active' );
            $switcherContainer.attr( 'data-active-switcher', index + 1 );

            updatePillBg( index );

            // Smooth animated height on content wrapper
            $contentWrap.css( { 'height': $contentWrap.outerHeight( true ) } );

            $contentList.removeClass( 'ua-switcher-content-active' );

            activeContentHeight = $activeContent.outerHeight( true );
            var borderTop    = parseInt( $contentWrap.css( 'border-top-width' ) ) || 0;
            var borderBottom = parseInt( $contentWrap.css( 'border-bottom-width' ) ) || 0;
            activeContentHeight += borderTop + borderBottom;

            $activeContent.addClass( 'ua-switcher-content-active' );

            $contentWrap.css( { 'height': activeContentHeight } );

            setTimeout( function() {  
                $contentWrap.css( { 'height': 'auto' } );
            }, 500 );
        }

        // Events Binding
        if ( isDualOuter ) {
            // Dual Outside Switcher
            $switcherWrap.off( 'click.uaToggle' ).on( 'click.uaToggle', function() {
                var activeSwitcher = $switcherWrap.find( '.ua-switcher-active' );
                var currentVal = parseInt( activeSwitcher.attr( 'data-switcher' ), 10 ) || parseInt( $switcherContainer.attr( 'data-active-switcher' ), 10 ) || 1;
                var nextIndex = currentVal === 1 ? 1 : 0;

                $switcherWrap.children( '.ua-switcher' ).removeClass( 'ua-switcher-active' );
                $switcherWrap.children( '.ua-switcher' ).eq( nextIndex ).addClass( 'ua-switcher-active' );
                switchTab( nextIndex );
            });

            // First label click
            $contentToggle.find( '.ua-switcher-first' ).off( 'click.uaToggle' ).on( 'click.uaToggle', function() {
                $switcherWrap.children( '.ua-switcher' ).removeClass( 'ua-switcher-active' );
                $switcherWrap.children( '.ua-switcher' ).eq( 0 ).addClass( 'ua-switcher-active' );
                switchTab( 0 );
            });

            // Second label click
            $contentToggle.find( '.ua-switcher-second' ).off( 'click.uaToggle' ).on( 'click.uaToggle', function() {
                $switcherWrap.children( '.ua-switcher' ).removeClass( 'ua-switcher-active' );
                $switcherWrap.children( '.ua-switcher' ).eq( 1 ).addClass( 'ua-switcher-active' );
                switchTab( 1 );
            });

        } else {
            // Multi or Inside tabs: Clicking directly on any tab
            $switcherList.off( 'click.uaToggle' ).on( 'click.uaToggle', function( e ) {
                e.preventDefault();
                var clickedIndex = $( this ).index();
                switchTab( clickedIndex );
            });
        }

        // Resize recalculation
        $(w).on( 'resize', function() {
            var curActive = parseInt( $switcherContainer.attr( 'data-active-switcher' ), 10 ) - 1;
            updatePillBg( isNaN( curActive ) ? 0 : curActive );
        });
    };

    $(w).on( 'elementor/frontend/init', function() {
        if ( elementorFrontend && elementorFrontend.hooks ) {
            elementorFrontend.hooks.addAction( 'frontend/element_ready/ultraaddons-content-toggle.default', UltraAddonsContentToggle );
            elementorFrontend.hooks.addAction( 'frontend/element_ready/content-toggle.default', UltraAddonsContentToggle );
        }
    });

})(jQuery, window);
