/**
 * UltraAddons Content Toggle Frontend Script
 *
 * @package UltraAddons
 */
;(function ($, w) {
    'use strict';

    var UltraAddonsContentToggle = function( $scope ) {

        var $contentToggle     = $scope.find( '.ua-content-toggle' ).first(),
            $switcherContainer = $contentToggle.find( '.ua-switcher-container' ).first(),
            $switcherOuter     = $contentToggle.find( '.ua-switcher-outer' ).first(),
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

        // Active Switcher Index (0-based)
        var rawActive = parseInt( $switcherContainer.attr( 'data-active-switcher' ), 10 );
        var activeSwitcherIndex = ( ! isNaN( rawActive ) && rawActive > 0 ) ? ( rawActive - 1 ) : 0;

        if ( activeSwitcherIndex >= $switcherList.length ) {
            activeSwitcherIndex = 0;
        }

        // Helper to position sliding pill indicator for Multi or Inside styles
        function updatePillPosition( index ) {
            if ( isDualOuter || ! $switcherList.length ) {
                return;
            }

            var $activeTab = $switcherList.eq( index );
            if ( ! $activeTab.length ) {
                return;
            }

            var tabPos = $activeTab.position();
            if ( ! tabPos ) {
                return;
            }

            var tabWidth  = $activeTab.outerWidth();
            var tabHeight = $activeTab.outerHeight();
            var tabLeft   = tabPos.left;
            var tabTop    = tabPos.top;

            $switcherBg.css({
                'width' : tabWidth + 'px',
                'height': tabHeight + 'px',
                'left'  : tabLeft + 'px',
                'top'   : tabTop + 'px'
            });
        }

        // Apply initial active state
        $switcherList.removeClass( 'ua-switcher-active' );
        $contentList.removeClass( 'ua-switcher-content-active ua-animation-enter' );

        $switcherList.eq( activeSwitcherIndex ).addClass( 'ua-switcher-active' );
        $contentList.eq( activeSwitcherIndex ).addClass( 'ua-switcher-content-active ua-animation-enter' );

        // Initial pill layout
        setTimeout( function() {
            updatePillPosition( activeSwitcherIndex );
        }, 50 );

        // Switch to a specific tab index
        function switchTab( index ) {
            var $activeSwitcher     = $switcherList.eq( index ),
                $activeContent      = $contentList.eq( index ),
                activeContentHeight = 'auto';

            if ( ! $activeContent.length ) {
                return;
            }

            if ( ! isDualOuter ) {
                $switcherList.removeClass( 'ua-switcher-active' );
                $activeSwitcher.addClass( 'ua-switcher-active' );
                $switcherContainer.attr( 'data-active-switcher', index + 1 );
                updatePillPosition( index );
            }

            // Smooth animated height transition
            $contentWrap.css( { 'height': $contentWrap.outerHeight( true ) } );

            $contentList.removeClass( 'ua-switcher-content-active ua-animation-enter' );

            activeContentHeight = $activeContent.outerHeight( true );
            var borderTop    = parseInt( $contentWrap.css( 'border-top-width' ), 10 ) || 0;
            var borderBottom = parseInt( $contentWrap.css( 'border-bottom-width' ), 10 ) || 0;
            activeContentHeight += borderTop + borderBottom;

            $activeContent.addClass( 'ua-switcher-content-active ua-animation-enter' );

            $contentWrap.css( { 'height': activeContentHeight } );

            setTimeout( function() {
                $contentWrap.css( { 'height': 'auto' } );
            }, 500 );
        }

        // Event Listeners
        if ( isDualOuter ) {
            // Clicking the switch toggle
            $switcherWrap.off( 'click.uaToggle' ).on( 'click.uaToggle', function() {
                var $active = $switcherWrap.find( '.ua-switcher-active' );
                var currentVal = parseInt( $active.attr( 'data-switcher' ), 10 ) || 1;
                var nextIndex = ( 1 === currentVal ) ? 1 : 0;

                $switcherWrap.children( '.ua-switcher' ).removeClass( 'ua-switcher-active' );
                $switcherWrap.children( '.ua-switcher' ).eq( nextIndex ).addClass( 'ua-switcher-active' );
                $switcherContainer.attr( 'data-active-switcher', nextIndex + 1 );
                switchTab( nextIndex );
            });

            // Clicking the First label
            $contentToggle.find( '.ua-switcher-first' ).off( 'click.uaToggle' ).on( 'click.uaToggle', function() {
                $switcherWrap.children( '.ua-switcher' ).removeClass( 'ua-switcher-active' );
                $switcherWrap.children( '.ua-switcher' ).eq( 0 ).addClass( 'ua-switcher-active' );
                $switcherContainer.attr( 'data-active-switcher', 1 );
                switchTab( 0 );
            });

            // Clicking the Second label
            $contentToggle.find( '.ua-switcher-second' ).off( 'click.uaToggle' ).on( 'click.uaToggle', function() {
                $switcherWrap.children( '.ua-switcher' ).removeClass( 'ua-switcher-active' );
                $switcherWrap.children( '.ua-switcher' ).eq( 1 ).addClass( 'ua-switcher-active' );
                $switcherContainer.attr( 'data-active-switcher', 2 );
                switchTab( 1 );
            });

        } else {
            // Multi or Inside tab clicks
            $switcherList.off( 'click.uaToggle' ).on( 'click.uaToggle', function( e ) {
                e.preventDefault();
                var clickedIndex = $( this ).index();
                switchTab( clickedIndex );
            });
        }

        // Window resize repositioning
        $(w).on( 'resize.uaToggle', function() {
            var curActive = parseInt( $switcherContainer.attr( 'data-active-switcher' ), 10 ) - 1;
            updatePillPosition( isNaN( curActive ) ? 0 : curActive );
        });
    };

    $(w).on( 'elementor/frontend/init', function() {
        if ( elementorFrontend && elementorFrontend.hooks ) {
            elementorFrontend.hooks.addAction( 'frontend/element_ready/ultraaddons-content-toggle.default', UltraAddonsContentToggle );
        }
    });

})(jQuery, window);
