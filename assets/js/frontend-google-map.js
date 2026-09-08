/**
 * UltraAddons - Advanced Google Map Frontend Controller
 * 
 * High-performance, modular Google Maps handler supporting:
 * - Smart Embed mode with interactive directory switching
 * - JavaScript API mode with multi-markers & rich InfoWindow cards
 * - Auto-fit bounding box & smooth panning
 * - Curated Ultra Map themes (Silver, Midnight, Retro, Cobalt, Aubergine)
 * 
 * @package UltraAddons
 * @author Saiful Islam <codersaiful@gmail.com>
 * @version 1.0.0
 */

(function ($) {
    'use strict';

    var UltraAddonsGoogleMap = {
        /**
         * Curated Ultra Map Theme Styles
         */
        themes: {
            ultra_silver: [
                { elementType: 'geometry', stylers: [{ color: '#f5f5f5' }] },
                { elementType: 'labels.icon', stylers: [{ visibility: 'off' }] },
                { elementType: 'labels.text.fill', stylers: [{ color: '#616161' }] },
                { elementType: 'labels.text.stroke', stylers: [{ color: '#f5f5f5' }] },
                { featureType: 'administrative.land_parcel', elementType: 'labels.text.fill', stylers: [{ color: '#bdbdbd' }] },
                { featureType: 'poi', elementType: 'geometry', stylers: [{ color: '#eeeeee' }] },
                { featureType: 'poi', elementType: 'labels.text.fill', stylers: [{ color: '#757575' }] },
                { featureType: 'poi.park', elementType: 'geometry', stylers: [{ color: '#e5e5e5' }] },
                { featureType: 'poi.park', elementType: 'labels.text.fill', stylers: [{ color: '#9e9e9e' }] },
                { featureType: 'road', elementType: 'geometry', stylers: [{ color: '#ffffff' }] },
                { featureType: 'road.arterial', elementType: 'labels.text.fill', stylers: [{ color: '#757575' }] },
                { featureType: 'road.highway', elementType: 'geometry', stylers: [{ color: '#dadada' }] },
                { featureType: 'road.highway', elementType: 'labels.text.fill', stylers: [{ color: '#616161' }] },
                { featureType: 'road.local', elementType: 'labels.text.fill', stylers: [{ color: '#9e9e9e' }] },
                { featureType: 'transit.line', elementType: 'geometry', stylers: [{ color: '#e5e5e5' }] },
                { featureType: 'transit.station', elementType: 'geometry', stylers: [{ color: '#eeeeee' }] },
                { featureType: 'water', elementType: 'geometry', stylers: [{ color: '#c9c9c9' }] },
                { featureType: 'water', elementType: 'labels.text.fill', stylers: [{ color: '#9e9e9e' }] }
            ],
            ultra_midnight: [
                { elementType: 'geometry', stylers: [{ color: '#1e293b' }] },
                { elementType: 'labels.text.stroke', stylers: [{ color: '#0f172a' }] },
                { elementType: 'labels.text.fill', stylers: [{ color: '#94a3b8' }] },
                { featureType: 'administrative.locality', elementType: 'labels.text.fill', stylers: [{ color: '#cbd5e1' }] },
                { featureType: 'poi', elementType: 'labels.text.fill', stylers: [{ color: '#64748b' }] },
                { featureType: 'poi.park', elementType: 'geometry', stylers: [{ color: '#0f172a' }] },
                { featureType: 'poi.park', elementType: 'labels.text.fill', stylers: [{ color: '#475569' }] },
                { featureType: 'road', elementType: 'geometry', stylers: [{ color: '#334155' }] },
                { featureType: 'road', elementType: 'geometry.stroke', stylers: [{ color: '#1e293b' }] },
                { featureType: 'road', elementType: 'labels.text.fill', stylers: [{ color: '#94a3b8' }] },
                { featureType: 'road.highway', elementType: 'geometry', stylers: [{ color: '#475569' }] },
                { featureType: 'road.highway', elementType: 'geometry.stroke', stylers: [{ color: '#1e293b' }] },
                { featureType: 'road.highway', elementType: 'labels.text.fill', stylers: [{ color: '#f1f5f9' }] },
                { featureType: 'transit', elementType: 'geometry', stylers: [{ color: '#1e293b' }] },
                { featureType: 'transit.station', elementType: 'labels.text.fill', stylers: [{ color: '#64748b' }] },
                { featureType: 'water', elementType: 'geometry', stylers: [{ color: '#090d16' }] },
                { featureType: 'water', elementType: 'labels.text.fill', stylers: [{ color: '#475569' }] },
                { featureType: 'water', elementType: 'labels.text.stroke', stylers: [{ color: '#090d16' }] }
            ],
            ultra_retro: [
                { elementType: 'geometry', stylers: [{ color: '#ebe3cd' }] },
                { elementType: 'labels.text.fill', stylers: [{ color: '#523735' }] },
                { elementType: 'labels.text.stroke', stylers: [{ color: '#f5f1e6' }] },
                { featureType: 'administrative', elementType: 'geometry.stroke', stylers: [{ color: '#c9b2a6' }] },
                { featureType: 'administrative.land_parcel', elementType: 'geometry.stroke', stylers: [{ color: '#dcd2be' }] },
                { featureType: 'administrative.land_parcel', elementType: 'labels.text.fill', stylers: [{ color: '#ae9e90' }] },
                { featureType: 'landscape.natural', elementType: 'geometry', stylers: [{ color: '#dfd2ae' }] },
                { featureType: 'poi', elementType: 'geometry', stylers: [{ color: '#dfd2ae' }] },
                { featureType: 'poi', elementType: 'labels.text.fill', stylers: [{ color: '#938561' }] },
                { featureType: 'poi.park', elementType: 'geometry', stylers: [{ color: '#a5b076' }] },
                { featureType: 'poi.park', elementType: 'labels.text.fill', stylers: [{ color: '#447530' }] },
                { featureType: 'road', elementType: 'geometry', stylers: [{ color: '#f5f1e6' }] },
                { featureType: 'road.arterial', elementType: 'geometry', stylers: [{ color: '#fdfcf8' }] },
                { featureType: 'road.highway', elementType: 'geometry', stylers: [{ color: '#f8c967' }] },
                { featureType: 'road.highway', elementType: 'geometry.stroke', stylers: [{ color: '#e9bc62' }] },
                { featureType: 'road.local', elementType: 'labels.text.fill', stylers: [{ color: '#806b63' }] },
                { featureType: 'transit.line', elementType: 'geometry', stylers: [{ color: '#dfd2ae' }] },
                { featureType: 'water', elementType: 'geometry.fill', stylers: [{ color: '#b9d3c2' }] }
            ],
            ultra_cobalt: [
                { featureType: 'all', elementType: 'all', stylers: [{ invert_lightness: true }, { saturation: 10 }, { lightness: 10 }, { gamma: 0.8 }, { hue: '#002244' }] },
                { featureType: 'water', elementType: 'geometry', stylers: [{ color: '#0f2b48' }] },
                { featureType: 'road', elementType: 'geometry', stylers: [{ color: '#1a4168' }] }
            ],
            ultra_aubergine: [
                { elementType: 'geometry', stylers: [{ color: '#1d2c4d' }] },
                { elementType: 'labels.text.fill', stylers: [{ color: '#8ec3b9' }] },
                { elementType: 'labels.text.stroke', stylers: [{ color: '#1a3646' }] },
                { featureType: 'administrative.country', elementType: 'geometry.stroke', stylers: [{ color: '#4b6878' }] },
                { featureType: 'landscape.man_made', elementType: 'geometry.stroke', stylers: [{ color: '#334e87' }] },
                { featureType: 'landscape.natural', elementType: 'geometry', stylers: [{ color: '#023e58' }] },
                { featureType: 'poi', elementType: 'geometry', stylers: [{ color: '#283d6a' }] },
                { featureType: 'road', elementType: 'geometry', stylers: [{ color: '#304a7d' }] },
                { featureType: 'road', elementType: 'labels.text.fill', stylers: [{ color: '#98a5be' }] },
                { featureType: 'road.highway', elementType: 'geometry', stylers: [{ color: '#2c6675' }] },
                { featureType: 'water', elementType: 'geometry', stylers: [{ color: '#0e1626' }] }
            ]
        },

        /**
         * Dynamic Google Maps Script Loader
         */
        loadScript: function (apiKey, callback) {
            if (window.google && window.google.maps) {
                callback();
                return;
            }

            if (window.uaGmapLoading) {
                var interval = setInterval(function () {
                    if (window.google && window.google.maps) {
                        clearInterval(interval);
                        callback();
                    }
                }, 100);
                return;
            }

            window.uaGmapLoading = true;
            var script = document.createElement('script');
            script.type = 'text/javascript';
            script.src = 'https://maps.googleapis.com/maps/api/js?key=' + encodeURIComponent(apiKey) + '&callback=UltraAddonsGoogleMap_OnLoad';
            script.async = true;
            script.defer = true;

            window.UltraAddonsGoogleMap_OnLoad = function () {
                window.uaGmapLoading = false;
                callback();
            };

            script.onerror = function () {
                window.uaGmapLoading = false;
                console.error('UltraAddons: Failed to load Google Maps JavaScript API. Please check your API key and network connection.');
            };

            document.head.appendChild(script);
        },

        /**
         * Main Elementor Widget Initialization
         */
        init: function ($scope) {
            var $wrapper   = $scope.find('.ua-google-map-wrapper');
            var $container = $wrapper.find('.ua-gmap-container');
            var mode       = $wrapper.data('mode') || 'embed';

            // 1. Embed Mode (Zero API Key)
            if ('embed' === mode) {
                UltraAddonsGoogleMap.initEmbed($wrapper, $container);
                return;
            }

            // 2. JavaScript API Mode
            var apiKey = $wrapper.data('api-key') || '';
            if (!apiKey) {
                // If API key is missing in JS API mode, show notice
                $container.html('<div class="ua-gmap-admin-notice"><strong>Google Maps API Key Required:</strong> Please provide a valid Google Maps API Key in widget settings or UltraAddons plugin settings to activate Advanced JS features. Or switch Integration Mode to <em>"Without API Key"</em>.</div>');
                return;
            }

            UltraAddonsGoogleMap.loadScript(apiKey, function () {
                UltraAddonsGoogleMap.initJsMap($wrapper, $container);
            });
        },

        /**
         * Initialize Embed Mode with Interactive Directory Switcher & Fullscreen Button
         */
        initEmbed: function ($wrapper, $container) {
            var $iframe = $container.find('.ua-gmap-iframe');
            var $pills  = $wrapper.find('.ua-gmap-dir-pill');
            var $fsBtn  = $container.find('.ua-gmap-fs-toggle');

            if ($fsBtn.length) {
                $fsBtn.off('click').on('click', function (e) {
                    e.preventDefault();
                    var elem = $container[0];
                    var isFs = document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement;

                    if (!isFs) {
                        if (elem.requestFullscreen) {
                            elem.requestFullscreen();
                        } else if (elem.webkitRequestFullscreen) {
                            elem.webkitRequestFullscreen();
                        } else if (elem.mozRequestFullScreen) {
                            elem.mozRequestFullScreen();
                        } else if (elem.msRequestFullscreen) {
                            elem.msRequestFullscreen();
                        }
                    } else {
                        if (document.exitFullscreen) {
                            document.exitFullscreen();
                        } else if (document.webkitExitFullscreen) {
                            document.webkitExitFullscreen();
                        } else if (document.mozCancelFullScreen) {
                            document.mozCancelFullScreen();
                        } else if (document.msExitFullscreen) {
                            document.msExitFullscreen();
                        }
                    }
                });

                $(document).off('fullscreenchange.uagmap webkitfullscreenchange.uagmap mozfullscreenchange.uagmap MSFullscreenChange.uagmap')
                    .on('fullscreenchange.uagmap webkitfullscreenchange.uagmap mozfullscreenchange.uagmap MSFullscreenChange.uagmap', function () {
                        var isFs = !!(document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement);
                        if (isFs) {
                            $fsBtn.addClass('is-fullscreen');
                            $fsBtn.find('.ua-fs-icon-open').hide();
                            $fsBtn.find('.ua-fs-icon-close').show();
                        } else {
                            $fsBtn.removeClass('is-fullscreen');
                            $fsBtn.find('.ua-fs-icon-open').show();
                            $fsBtn.find('.ua-fs-icon-close').hide();
                        }
                    });
            }

            if (!$pills.length || !$iframe.length) {
                return;
            }

            $pills.on('click', function () {
                var $pill  = $(this);
                var query  = $pill.data('query');
                var zoom   = $pill.data('zoom') || 14;

                if (!query) {
                    return;
                }

                $pills.removeClass('ua-gmap-dir-active');
                $pill.addClass('ua-gmap-dir-active');

                var newSrc = 'https://maps.google.com/maps?q=' + encodeURIComponent(query) + '&z=' + parseInt(zoom, 10) + '&output=embed&iwloc=near';
                $iframe.attr('src', newSrc);
            });
        },

        /**
         * Initialize Advanced Google Maps JavaScript API
         */
        initJsMap: function ($wrapper, $container) {
            var $canvas = $container.find('.ua-gmap-canvas');
            if (!$canvas.length) {
                return;
            }

            var settings  = $wrapper.data('settings') || {};
            var controls  = $wrapper.data('controls') || {};
            var locations = $wrapper.data('locations') || [];

            // Theme style parsing
            var mapStyle = [];
            if (settings.theme && UltraAddonsGoogleMap.themes[settings.theme]) {
                mapStyle = UltraAddonsGoogleMap.themes[settings.theme];
            } else if ('custom' === settings.theme && settings.custom_style) {
                try {
                    mapStyle = typeof settings.custom_style === 'string' ? JSON.parse(settings.custom_style) : settings.custom_style;
                } catch (e) {
                    mapStyle = [];
                }
            }

            var mapOptions = {
                zoom: parseInt(settings.zoom || 14, 10),
                mapTypeId: settings.type || 'roadmap',
                styles: mapStyle,
                gestureHandling: settings.gesture_handling || 'cooperative',
                mapTypeControl: !!controls.map_type,
                fullscreenControl: !!controls.fullscreen,
                zoomControl: !!controls.zoom,
                streetViewControl: !!controls.street_view
            };

            var map = new google.maps.Map($canvas[0], mapOptions);
            var bounds = new google.maps.LatLngBounds();
            var markers = [];
            var currentInfoWindow = null;

            // Create markers
            $.each(locations, function (idx, loc) {
                var lat = parseFloat(loc.latitude);
                var lng = parseFloat(loc.longitude);

                if (isNaN(lat) || isNaN(lng)) {
                    return;
                }

                var position = new google.maps.LatLng(lat, lng);
                var markerOpts = {
                    position: position,
                    map: map,
                    title: loc.title || ''
                };

                // Marker animation
                if (loc.animation === 'drop') {
                    markerOpts.animation = google.maps.Animation.DROP;
                } else if (loc.animation === 'bounce') {
                    markerOpts.animation = google.maps.Animation.BOUNCE;
                }

                // Custom marker icon
                if (loc.custom_icon && loc.icon_url) {
                    var iconW = parseInt(loc.icon_width || 36, 10);
                    var iconH = parseInt(loc.icon_height || 36, 10);
                    markerOpts.icon = {
                        url: loc.icon_url,
                        scaledSize: new google.maps.Size(iconW, iconH),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(iconW / 2, iconH)
                    };
                }

                var marker = new google.maps.Marker(markerOpts);
                marker.locationIndex = idx;
                markers.push(marker);
                bounds.extend(position);

                // Build Rich InfoWindow Card
                if (loc.info_trigger !== 'none') {
                    var cardHtml = UltraAddonsGoogleMap.buildCardHtml(loc, lat, lng);
                    var infoWindow = new google.maps.InfoWindow({
                        content: cardHtml,
                        maxWidth: parseInt(loc.card_width || 300, 10)
                    });

                    marker.infoWindow = infoWindow;

                    var openInfo = function () {
                        if (currentInfoWindow) {
                            currentInfoWindow.close();
                        }
                        infoWindow.open(map, marker);
                        currentInfoWindow = infoWindow;
                    };

                    if (loc.info_trigger === 'load') {
                        openInfo();
                    }

                    marker.addListener('click', openInfo);
                }
            });

            // Auto-fit bounds or center
            if (markers.length > 1) {
                map.fitBounds(bounds);
                // Respect user zoom ceiling if bounds zoom is too deep
                google.maps.event.addListenerOnce(map, 'bounds_changed', function () {
                    var userZoom = parseInt(settings.zoom || 14, 10);
                    if (map.getZoom() > userZoom) {
                        map.setZoom(userZoom);
                    }
                });
            } else if (markers.length === 1) {
                map.setCenter(markers[0].getPosition());
                map.setZoom(parseInt(settings.zoom || 14, 10));
            }

            // Interactive Location Switcher Pills
            var $pills = $wrapper.find('.ua-gmap-dir-pill');
            $pills.on('click', function () {
                var $pill = $(this);
                var targetIdx = parseInt($pill.data('index'), 10);

                var targetMarker = null;
                $.each(markers, function (i, m) {
                    if (m.locationIndex === targetIdx) {
                        targetMarker = m;
                        return false;
                    }
                });

                if (!targetMarker) {
                    return;
                }

                $pills.removeClass('ua-gmap-dir-active');
                $pill.addClass('ua-gmap-dir-active');

                map.panTo(targetMarker.getPosition());
                var targetZoom = parseInt(settings.zoom || 15, 10);
                map.setZoom(targetZoom);

                if (targetMarker.infoWindow) {
                    if (currentInfoWindow) {
                        currentInfoWindow.close();
                    }
                    targetMarker.infoWindow.open(map, targetMarker);
                    currentInfoWindow = targetMarker.infoWindow;
                }
            });
        },

        /**
         * Helper: Build Semantic Rich Card HTML for InfoWindow
         */
        buildCardHtml: function (loc, lat, lng) {
            var html = '<div class="ua-gmap-card">';

            // Image Thumbnail
            if (loc.image_url) {
                html += '<img class="ua-gmap-card-thumb" src="' + loc.image_url + '" alt="' + (loc.title || '') + '" />';
            }

            // Header (Title + Badge)
            html += '<div class="ua-gmap-card-header">';
            if (loc.title) {
                html += '<h4 class="ua-gmap-card-title">' + loc.title + '</h4>';
            }
            if (loc.badge) {
                html += '<span class="ua-gmap-card-badge">' + loc.badge + '</span>';
            }
            html += '</div>';

            // Description
            if (loc.description) {
                html += '<p class="ua-gmap-card-desc">' + loc.description + '</p>';
            }

            // Meta Details
            var hasMeta = loc.address || loc.phone || loc.website;
            if (hasMeta) {
                html += '<div class="ua-gmap-card-meta">';
                if (loc.address) {
                    html += '<div class="ua-gmap-meta-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg><span>' + loc.address + '</span></div>';
                }
                if (loc.phone) {
                    html += '<div class="ua-gmap-meta-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg><a href="tel:' + loc.phone + '">' + loc.phone + '</a></div>';
                }
                html += '</div>';
            }

            // 1-Click Get Directions Button
            var directionUrl = 'https://www.google.com/maps/dir/?api=1&destination=' + lat + ',' + lng;
            html += '<a class="ua-gmap-directions-btn" href="' + directionUrl + '" target="_blank" rel="noopener noreferrer"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="3 11 22 2 13 21 11 13 3 11"></polygon></svg><span>Get Directions</span></a>';

            html += '</div>';
            return html;
        }
    };

    // Register with Elementor Frontend
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/ultraaddons-google-map.default',
            UltraAddonsGoogleMap.init
        );
    });

})(jQuery);
