/**
 * UltraAddons - Filterable Gallery Widget Frontend Script
 * 
 * Handles Isotope grid/masonry, live search filtering, Layout 3 dropdown filters,
 * Load More dynamic pagination, URL hash deep linking, keyboard accessibility,
 * touch swipe gestures, vertical video mode, and built-in modal lightbox.
 * 
 * Includes realm-safe Elementor Editor Mode handler.
 * 
 * @package UltraAddons
 * @version 1.1.0
 */
(function ($) {
    'use strict';

    /**
     * Helper: Extract video embed URL (YouTube / Vimeo / MP4 / Shorts)
     */
    function parseVideoUrl(url) {
        if (!url) return null;

        // YouTube Shorts
        var shortsMatch = url.match(/youtube\.com\/shorts\/([a-zA-Z0-9_-]+)/i);
        if (shortsMatch && shortsMatch[1]) {
            return {
                type: 'iframe',
                src: 'https://www.youtube.com/embed/' + shortsMatch[1] + '?autoplay=1&rel=0',
                isVertical: true
            };
        }

        // Standard YouTube
        var ytMatch = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/|youtube-nocookie\.com\/embed\/)([^"&?\/\s]{11})/i);
        if (ytMatch && ytMatch[1]) {
            var domain = url.indexOf('youtube-nocookie.com') > -1 ? 'https://www.youtube-nocookie.com/embed/' : 'https://www.youtube.com/embed/';
            return {
                type: 'iframe',
                src: domain + ytMatch[1] + '?autoplay=1&rel=0',
                isVertical: false
            };
        }

        // Vimeo
        var vimeoMatch = url.match(/(?:vimeo\.com\/(?:video\/)?)([0-9]+)/i);
        if (vimeoMatch && vimeoMatch[1]) {
            return {
                type: 'iframe',
                src: 'https://player.vimeo.com/video/' + vimeoMatch[1] + '?autoplay=1',
                isVertical: false
            };
        }

        // Direct HTML5 Video (.mp4, .webm, .ogg)
        if (url.match(/\.(mp4|webm|ogg)(\?.*)?$/i)) {
            return {
                type: 'video',
                src: url,
                isVertical: false
            };
        }

        // Fallback
        return {
            type: 'iframe',
            src: url,
            isVertical: false
        };
    }

    /**
     * UltraAddons Lightbox Modal Manager with Touch & Swipe Support
     */
    var UAFGLightbox = {
        $modal: null,
        $content: null,
        $caption: null,
        $counter: null,
        items: [],
        currentIndex: 0,
        touchStartX: 0,
        touchEndX: 0,
        touchStartY: 0,
        touchEndY: 0,

        init: function () {
            if ($('#ua-fg-lightbox-modal').length) {
                this.$modal = $('#ua-fg-lightbox-modal');
                this.$content = this.$modal.find('.ua-fg-lightbox-body');
                this.$caption = this.$modal.find('.ua-fg-lightbox-caption');
                this.$counter = this.$modal.find('.ua-fg-lightbox-counter');
                return;
            }

            var html = [
                '<div id="ua-fg-lightbox-modal" class="ua-fg-lightbox-modal" role="dialog" aria-modal="true" aria-hidden="true">',
                '  <div class="ua-fg-lightbox-backdrop"></div>',
                '  <div class="ua-fg-lightbox-container">',
                '    <div class="ua-fg-lightbox-header">',
                '      <div class="ua-fg-lightbox-counter"></div>',
                '      <button type="button" class="ua-fg-lightbox-close" aria-label="Close">&times;</button>',
                '    </div>',
                '    <div class="ua-fg-lightbox-content-wrap">',
                '      <button type="button" class="ua-fg-lightbox-nav ua-fg-lightbox-prev" aria-label="Previous">&#10094;</button>',
                '      <div class="ua-fg-lightbox-body"></div>',
                '      <button type="button" class="ua-fg-lightbox-nav ua-fg-lightbox-next" aria-label="Next">&#10095;</button>',
                '    </div>',
                '    <div class="ua-fg-lightbox-footer">',
                '      <div class="ua-fg-lightbox-caption"></div>',
                '    </div>',
                '  </div>',
                '</div>'
            ].join('');

            $('body').append(html);

            this.$modal = $('#ua-fg-lightbox-modal');
            this.$content = this.$modal.find('.ua-fg-lightbox-body');
            this.$caption = this.$modal.find('.ua-fg-lightbox-caption');
            this.$counter = this.$modal.find('.ua-fg-lightbox-counter');

            this.bindEvents();
        },

        bindEvents: function () {
            var self = this;

            this.$modal.on('click', '.ua-fg-lightbox-close, .ua-fg-lightbox-backdrop', function (e) {
                e.preventDefault();
                self.close();
            });

            this.$modal.on('click', '.ua-fg-lightbox-prev', function (e) {
                e.preventDefault();
                self.prev();
            });

            this.$modal.on('click', '.ua-fg-lightbox-next', function (e) {
                e.preventDefault();
                self.next();
            });

            // Keyboard navigation
            $(document).on('keydown', function (e) {
                if (!self.$modal || !self.$modal.hasClass('ua-fg-lightbox-open')) return;

                if (e.key === 'Escape' || e.keyCode === 27) {
                    self.close();
                } else if (e.key === 'ArrowLeft' || e.keyCode === 37) {
                    self.prev();
                } else if (e.key === 'ArrowRight' || e.keyCode === 39) {
                    self.next();
                }
            });

            // Touch Swipe Support
            this.$modal.on('touchstart', function (e) {
                if (!e.originalEvent.touches) return;
                self.touchStartX = e.originalEvent.touches[0].clientX;
                self.touchStartY = e.originalEvent.touches[0].clientY;
            });

            this.$modal.on('touchend', function (e) {
                if (!e.originalEvent.changedTouches) return;
                self.touchEndX = e.originalEvent.changedTouches[0].clientX;
                self.touchEndY = e.originalEvent.changedTouches[0].clientY;
                self.handleGesture();
            });
        },

        handleGesture: function () {
            var diffX = this.touchEndX - this.touchStartX;
            var diffY = this.touchEndY - this.touchStartY;

            if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 40) {
                if (diffX > 0) {
                    this.prev();
                } else {
                    this.next();
                }
            }
        },

        open: function (items, index) {
            this.init();
            this.items = items || [];
            this.currentIndex = parseInt(index, 10) || 0;

            if (this.currentIndex < 0) this.currentIndex = 0;
            if (this.currentIndex >= this.items.length) this.currentIndex = this.items.length - 1;

            this.renderCurrent();
            this.$modal.addClass('ua-fg-lightbox-open').attr('aria-hidden', 'false');
            $('body').addClass('ua-fg-lightbox-active-body');
        },

        renderCurrent: function () {
            if (!this.items.length) return;
            var item = this.items[this.currentIndex];
            if (!item) return;

            this.$content.empty();

            if (item.type === 'video') {
                var isVertical = item.videoLayout === 'vertical';
                var videoInfo = parseVideoUrl(item.src);
                if (videoInfo && videoInfo.type === 'video') {
                    this.$content.html('<video class="ua-fg-lightbox-video ' + (isVertical ? 'ua-fg-vertical-video' : '') + '" controls autoplay playsinline src="' + item.src + '"></video>');
                } else if (videoInfo && videoInfo.type === 'iframe') {
                    var scalerClass = (isVertical || videoInfo.isVertical) ? 'ua-fg-lightbox-iframe-scaler ua-fg-vertical-video-popup' : 'ua-fg-lightbox-iframe-scaler';
                    this.$content.html('<div class="' + scalerClass + '"><iframe src="' + videoInfo.src + '" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe></div>');
                }
            } else {
                this.$content.html('<img class="ua-fg-lightbox-img" src="' + item.src + '" alt="' + (item.title || '') + '" />');
            }

            if (item.title) {
                this.$caption.text(item.title).show();
            } else {
                this.$caption.empty().hide();
            }

            if (this.items.length > 1) {
                this.$counter.text((this.currentIndex + 1) + ' / ' + this.items.length).show();
                this.$modal.find('.ua-fg-lightbox-nav').show();
            } else {
                this.$counter.empty().hide();
                this.$modal.find('.ua-fg-lightbox-nav').hide();
            }
        },

        prev: function () {
            if (!this.items.length) return;
            this.currentIndex = (this.currentIndex - 1 + this.items.length) % this.items.length;
            this.renderCurrent();
        },

        next: function () {
            if (!this.items.length) return;
            this.currentIndex = (this.currentIndex + 1) % this.items.length;
            this.renderCurrent();
        },

        close: function () {
            if (!this.$modal) return;
            this.$modal.removeClass('ua-fg-lightbox-open').attr('aria-hidden', 'true');
            $('body').removeClass('ua-fg-lightbox-active-body');
            this.$content.empty();
        }
    };

    /**
     * Filterable Gallery Main Handler
     */
    var UltraFilterableGalleryHandler = function ($scope, $) {
        var $wrapper = $scope.find('.ua-filterable-gallery-wrap');
        if (!$wrapper.length) {
            $wrapper = $scope.hasClass('ua-filterable-gallery-wrap') ? $scope : $scope.find('.ua-fg-wrapper');
        }
        if (!$wrapper.length) return;

        var isEditMode = (typeof elementorFrontend !== 'undefined' && typeof elementorFrontend.isEditMode === 'function' && elementorFrontend.isEditMode());

        var $container      = $wrapper.find('.ua-fg-container');
        var $searchBox      = $wrapper.find('.ua-fg-search-input');
        var $loadMoreBtn    = $wrapper.find('.ua-fg-load-more-btn');
        var $noItemsMsg     = $wrapper.find('.ua-fg-not-found-msg');
        var $dropdownMenu   = $wrapper.find('.ua-fg-dropdown-menu');
        var $filterTrigger  = $wrapper.find('.ua-fg-filter-trigger');

        var rawConfig = $wrapper.attr('data-config') || '{}';
        var config = {};
        try {
            config = JSON.parse(rawConfig);
        } catch (e) {
            config = {};
        }

        var gridStyle     = config.gridStyle || 'grid';
        var duration      = parseInt(config.duration, 10) || 500;
        var itemsToShow   = parseInt(config.itemsToShow, 10) || 6;
        var totalItems    = parseInt(config.totalItems, 10) || 0;
        var searchAll     = config.searchAll === true;
        var mobileScroll  = config.mobileScroll === true;
        var scrollOffset  = parseInt(config.scrollOffset, 10) || 0;

        /* ==========================================================================
           EDITOR PREVIEW MODE (Realm-Safe Direct DOM Filter)
           ========================================================================== */
        if (isEditMode) {
            var applyEditorFilter = function (filter) {
                var $items = $wrapper.find('.ua-fg-item');
                if (!filter || filter === '*') {
                    $items.show();
                } else {
                    $items.hide().filter(filter).show();
                }

                var visibleCount = $items.filter(':visible').length;
                if ($items.length > 0 && visibleCount === 0) {
                    $noItemsMsg.stop(true, true).fadeIn(150);
                } else {
                    $noItemsMsg.stop(true, true).fadeOut(150);
                }
            };

            // Standard buttons
            $wrapper.off('click.uafg', '.ua-fg-filter-btn').on('click.uafg', '.ua-fg-filter-btn', function (e) {
                e.preventDefault();
                var $btn = $(this);
                $wrapper.find('.ua-fg-filter-btn').removeClass('ua-fg-active').attr('aria-selected', 'false');
                $btn.addClass('ua-fg-active').attr('aria-selected', 'true');
                applyEditorFilter($btn.attr('data-filter'));
            });

            // Layout 3 Dropdown trigger
            if ($filterTrigger.length) {
                $filterTrigger.off('click.uafg').on('click.uafg', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $dropdownMenu.toggleClass('ua-fg-open-filters');
                });

                $wrapper.off('click.uafg', '.ua-fg-dropdown-item').on('click.uafg', '.ua-fg-dropdown-item', function (e) {
                    e.preventDefault();
                    var $item = $(this);
                    $wrapper.find('.ua-fg-dropdown-item').removeClass('ua-fg-active');
                    $item.addClass('ua-fg-active');
                    $filterTrigger.find('.ua-fg-trigger-text').text($item.text().trim());
                    $dropdownMenu.removeClass('ua-fg-open-filters');
                    applyEditorFilter($item.attr('data-filter'));
                });

                $(document).on('click.uafg_editor_outside', function (e) {
                    if (!$(e.target).closest('.ua-fg-dropdown-filter-wrap').length) {
                        $dropdownMenu.removeClass('ua-fg-open-filters');
                    }
                });
            }

            // Quick live search
            if ($searchBox.length) {
                $searchBox.off('input.uafg keyup.uafg').on('input.uafg keyup.uafg', function () {
                    var val = $(this).val().toLowerCase().trim();
                    var $items = $wrapper.find('.ua-fg-item');
                    $items.each(function () {
                        var $this = $(this);
                        var text = ($this.attr('data-title') || '') + ' ' + ($this.attr('data-categories') || '') + ' ' + $this.text();
                        if (!val || text.toLowerCase().indexOf(val) > -1) {
                            $this.show();
                        } else {
                            $this.hide();
                        }
                    });
                    var visibleCount = $items.filter(':visible').length;
                    if ($items.length > 0 && visibleCount === 0) {
                        $noItemsMsg.show();
                    } else {
                        $noItemsMsg.hide();
                    }
                });
            }

            // Built-in Lightbox Handler in Editor Mode
            $wrapper.off('click.uafg_lb', '.ua-fg-lightbox-trigger').on('click.uafg_lb', '.ua-fg-lightbox-trigger', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var $clicked = $(this);
                var $visibleItems = $wrapper.find('.ua-fg-item:visible .ua-fg-lightbox-trigger');

                var lightboxItems = [];
                var startIndex = 0;

                $visibleItems.each(function (idx) {
                    var $el = $(this);
                    var type = $el.attr('data-popup-type') || 'image';
                    var src = $el.attr('href');
                    var title = $el.attr('data-title') || '';
                    var videoLayout = $el.attr('data-video-layout') || 'horizontal';

                    if ($el.is($clicked) || $el.get(0) === $clicked.get(0)) {
                        startIndex = idx;
                    }

                    lightboxItems.push({
                        type: type,
                        src: src,
                        title: title,
                        videoLayout: videoLayout
                    });
                });

                if (lightboxItems.length > 0) {
                    UAFGLightbox.open(lightboxItems, startIndex);
                }
            });

            // Interactive Category Chips & Dropdown Suggestion in Elementor Editor Panel
            var syncPanelDatalist = function () {
                try {
                    var $top = (window.parent && window.parent.jQuery) ? window.parent.jQuery : $;
                    if (!$top) return;

                    // Inject Panel Styles once
                    if (!$top('#ua-fg-panel-chip-styles').length) {
                        var panelStyles = [
                            '<style id="ua-fg-panel-chip-styles">',
                            '  .ua-fg-category-chips-wrap { width: 100% !important; margin: 8px 0 6px 0 !important; display: flex !important; flex-wrap: wrap !important; align-items: center !important; gap: 5px !important; padding: 7px 9px !important; background: #f8fafc !important; border: 1px solid #e2e8f0 !important; border-radius: 6px !important; box-sizing: border-box !important; }',
                            '  .ua-fg-chips-title { width: 100% !important; font-size: 10px !important; font-weight: 700 !important; text-transform: uppercase !important; letter-spacing: 0.5px !important; color: #64748b !important; margin-bottom: 2px !important; }',
                            '  .ua-fg-cat-chip { display: inline-flex !important; align-items: center !important; gap: 3px !important; padding: 3px 9px !important; font-size: 11px !important; font-weight: 600 !important; font-family: inherit !important; line-height: 1.3 !important; border-radius: 12px !important; cursor: pointer !important; user-select: none !important; border: 1px solid #cbd5e1 !important; background: #ffffff !important; color: #334155 !important; transition: all 0.15s ease !important; outline: none !important; box-shadow: 0 1px 2px rgba(0,0,0,0.03) !important; }',
                            '  .ua-fg-cat-chip:hover { background: #f1f5f9 !important; border-color: #94a3b8 !important; color: #0f172a !important; }',
                            '  .ua-fg-cat-chip.ua-fg-chip-active { background: #2563eb !important; border-color: #1d4ed8 !important; color: #ffffff !important; box-shadow: 0 2px 6px rgba(37, 99, 235, 0.3) !important; }',
                            '</style>'
                        ].join('\n');
                        $top('head').append(panelStyles);
                    }

                    var categories = [];
                    // Extract from active rendered filter buttons
                    $wrapper.find('.ua-fg-filter-btn').each(function () {
                        var label = $(this).text().trim();
                        if (label && label.toLowerCase() !== 'all' && categories.indexOf(label) === -1) {
                            categories.push(label);
                        }
                    });

                    // Extract from panel Filter Categories repeater inputs
                    $top('.elementor-control-ua_fg_controls input[data-setting="ua_fg_control"]').each(function () {
                        var val = $(this).val();
                        if (val) {
                            val = val.trim();
                            if (val && categories.indexOf(val) === -1) {
                                categories.push(val);
                            }
                        }
                    });

                    if (categories.length === 0) {
                        categories.push('Gallery Filter');
                    }

                    // Render Interactive Category Chips below each Gallery Item Category Input
                    $top('.elementor-control-ua_fg_gallery_control_name').each(function () {
                        var $ctrl = $top(this);
                        var $input = $ctrl.find('input');
                        if (!$input.length) return;

                        // Ensure input is 100% width
                        $input.css({ 'width': '100%' });

                        var $chipsWrap = $ctrl.find('.ua-fg-category-chips-wrap');
                        if (!$chipsWrap.length) {
                            $chipsWrap = $top('<div class="ua-fg-category-chips-wrap"><div class="ua-fg-chips-title">Select Filters:</div></div>');
                            // Place below the field wrapper so it never squishes the input
                            $ctrl.find('.elementor-control-field').after($chipsWrap);
                        }

                        var currentVal = ($input.val() || '').trim();
                        var currentSelected = currentVal ? currentVal.split(',').map(function (s) { return s.trim().toLowerCase(); }) : [];

                        // Keep the title and refresh chips
                        $chipsWrap.find('.ua-fg-cat-chip').remove();

                        categories.forEach(function (cat) {
                            var isSelected = currentSelected.indexOf(cat.toLowerCase()) > -1;
                            var activeClass = isSelected ? 'ua-fg-chip-active' : '';
                            var prefix = isSelected ? '✓ ' : '+ ';
                            var $chip = $top('<button type="button" class="ua-fg-cat-chip ' + activeClass + '">' + prefix + cat + '</button>');

                            $chip.on('click', function (e) {
                                e.preventDefault();
                                e.stopPropagation();

                                var raw = ($input.val() || '').trim();
                                var tokens = raw ? raw.split(',').map(function (s) { return s.trim(); }).filter(Boolean) : [];
                                var lowerTokens = tokens.map(function (s) { return s.toLowerCase(); });

                                var idx = lowerTokens.indexOf(cat.toLowerCase());
                                if (idx > -1) {
                                    // Remove
                                    tokens.splice(idx, 1);
                                } else {
                                    // Add
                                    tokens.push(cat);
                                }

                                var newVal = tokens.join(', ');
                                $input.val(newVal).trigger('input').trigger('change');
                                syncPanelDatalist();
                            });

                            $chipsWrap.append($chip);
                        });
                    });

                } catch (e) {}
            };

            syncPanelDatalist();
            setTimeout(syncPanelDatalist, 350);

            // Listen for panel clicks and live typing in Filter Categories
            try {
                var $topWin = (window.parent && window.parent.jQuery) ? window.parent.jQuery : $;
                var topDoc = window.parent ? window.parent.document : document;

                $topWin(topDoc).off('click.uafg_row', '.elementor-repeater-row-tools, .elementor-repeater-row-item-title, .elementor-repeater-add, .elementor-repeater-tool-remove')
                    .on('click.uafg_row', '.elementor-repeater-row-tools, .elementor-repeater-row-item-title, .elementor-repeater-add, .elementor-repeater-tool-remove', function () {
                        setTimeout(syncPanelDatalist, 150);
                    });

                $topWin(topDoc).off('input.uafg_ctrl keyup.uafg_ctrl', '.elementor-control-ua_fg_controls input[data-setting="ua_fg_control"]')
                    .on('input.uafg_ctrl keyup.uafg_ctrl', '.elementor-control-ua_fg_controls input[data-setting="ua_fg_control"]', function () {
                        syncPanelDatalist();
                    });

                $topWin(topDoc).off('input.uafg_input', '.elementor-control-ua_fg_gallery_control_name input')
                    .on('input.uafg_input', '.elementor-control-ua_fg_gallery_control_name input', function () {
                        syncPanelDatalist();
                    });
            } catch (err) {}

            // Reflect initial active filter on load
            var $activeBtn = $wrapper.find('.ua-fg-filter-btn.ua-fg-active, .ua-fg-dropdown-item.ua-fg-active').first();
            if ($activeBtn.length) {
                applyEditorFilter($activeBtn.attr('data-filter'));
            }

            return; // Editor handling complete
        }

        /* ==========================================================================
           FRONTEND PUBLISHED MODE (Full Isotope Grid / Masonry + Load More + Lightbox)
           ========================================================================== */
        var layoutMode = (gridStyle === 'masonry') ? 'masonry' : 'fitRows';
        var isRTL      = $('body').hasClass('rtl');

        // Unpack all remaining items array from dataset
        var allItemsDataset = [];
        var rawDataset = $container.attr('data-items-dataset');
        if (rawDataset) {
            try {
                var jsonString = atob(rawDataset);
                allItemsDataset = JSON.parse(jsonString);
            } catch (err) {
                allItemsDataset = [];
            }
        }

        var remainingItems = allItemsDataset.slice($container.find('.ua-fg-item').length);
        var currentFilter  = '*';
        var currentSearch  = '';
        var searchTimeout  = null;
        var allItemsAppendedForSearch = false;
        var $grid          = null;

        if (typeof $.fn.isotope !== 'undefined') {
            $grid = $container.isotope({
                itemSelector: '.ua-fg-item',
                layoutMode: layoutMode,
                percentPosition: true,
                transitionDuration: duration + 'ms',
                isOriginLeft: !isRTL,
                filter: function () {
                    var $this = $(this);
                    var matchesFilter = (currentFilter === '*' || $this.is(currentFilter));

                    var matchesSearch = true;
                    if (currentSearch.trim() !== '') {
                        var title = ($this.attr('data-title') || '').toLowerCase();
                        var cats  = ($this.attr('data-categories') || '').toLowerCase();
                        var text  = $this.text().toLowerCase();
                        var searchVal = currentSearch.toLowerCase();

                        matchesSearch = (title.indexOf(searchVal) > -1 || cats.indexOf(searchVal) > -1 || text.indexOf(searchVal) > -1);
                    }

                    return matchesFilter && matchesSearch;
                }
            });

            $container.imagesLoaded().progress(function () {
                $grid.isotope('layout');
            });

            $grid.on('arrangeComplete', function () {
                updateUIStates();
            });
        }

        function triggerIsotopeFilter() {
            if (typeof $.fn.isotope !== 'undefined' && $grid && $grid.data('isotope')) {
                $grid.isotope({
                    filter: function () {
                        var $this = $(this);
                        var matchesFilter = (currentFilter === '*' || $this.is(currentFilter));

                        var matchesSearch = true;
                        if (currentSearch.trim() !== '') {
                            var title = ($this.attr('data-title') || '').toLowerCase();
                            var cats  = ($this.attr('data-categories') || '').toLowerCase();
                            var text  = $this.text().toLowerCase();
                            var searchVal = currentSearch.toLowerCase();

                            matchesSearch = (title.indexOf(searchVal) > -1 || cats.indexOf(searchVal) > -1 || text.indexOf(searchVal) > -1);
                        }

                        return matchesFilter && matchesSearch;
                    }
                });
            } else {
                var $items = $container.find('.ua-fg-item');
                $items.each(function () {
                    var $this = $(this);
                    var matchesFilter = (currentFilter === '*' || $this.is(currentFilter));

                    var matchesSearch = true;
                    if (currentSearch.trim() !== '') {
                        var title = ($this.attr('data-title') || '').toLowerCase();
                        var cats  = ($this.attr('data-categories') || '').toLowerCase();
                        var text  = $this.text().toLowerCase();
                        var searchVal = currentSearch.toLowerCase();

                        matchesSearch = (title.indexOf(searchVal) > -1 || cats.indexOf(searchVal) > -1 || text.indexOf(searchVal) > -1);
                    }

                    if (matchesFilter && matchesSearch) {
                        $this.stop(true, true).show();
                    } else {
                        $this.stop(true, true).hide();
                    }
                });
                updateUIStates();
            }
        }

        var resizeTimer;
        $(window).on('resize orientationchange', function () {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function () {
                if ($grid && typeof $grid.isotope === 'function') {
                    $grid.isotope('layout');
                }
            }, 150);
        });

        function updateUIStates() {
            var itemCount = $container.find('.ua-fg-item').length;
            var visibleCount = (typeof $.fn.isotope !== 'undefined' && $grid && $grid.data('isotope'))
                ? $grid.data('isotope').filteredItems.length
                : $container.find('.ua-fg-item:visible').length;

            if (itemCount > 0 && visibleCount === 0) {
                $noItemsMsg.stop(true, true).fadeIn(200);
            } else {
                $noItemsMsg.stop(true, true).fadeOut(200);
            }

            var remainingMatches = 0;
            if (currentFilter === '*') {
                remainingMatches = remainingItems.length;
            } else {
                var filterClass = currentFilter.replace('.', '');
                remainingMatches = remainingItems.filter(function (htmlStr) {
                    return htmlStr.indexOf(filterClass) > -1;
                }).length;
            }

            if (remainingMatches <= 0) {
                $loadMoreBtn.hide();
                if (remainingItems.length === 0 && totalItems > itemsToShow) {
                    $wrapper.find('.ua-fg-no-more-msg').show();
                }
            } else {
                $loadMoreBtn.show();
                $wrapper.find('.ua-fg-no-more-msg').hide();
            }
        }

        $wrapper.off('click.uafg', '.ua-fg-filter-btn').on('click.uafg', '.ua-fg-filter-btn', function (e) {
            e.preventDefault();
            var $btn = $(this);

            $wrapper.find('.ua-fg-filter-btn').removeClass('ua-fg-active').attr('aria-selected', 'false');
            $btn.addClass('ua-fg-active').attr('aria-selected', 'true');

            currentFilter = $btn.attr('data-filter') || '*';
            triggerIsotopeFilter();

            if (mobileScroll && $(window).width() <= 768) {
                var targetTop = $container.offset().top - scrollOffset;
                $('html, body').animate({ scrollTop: targetTop }, 400);
            }
        });

        if ($filterTrigger.length) {
            $filterTrigger.off('click.uafg').on('click.uafg', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $dropdownMenu.toggleClass('ua-fg-open-filters');
                var isExpanded = $dropdownMenu.hasClass('ua-fg-open-filters');
                $filterTrigger.attr('aria-expanded', isExpanded ? 'true' : 'false');
            });

            $wrapper.off('click.uafg', '.ua-fg-dropdown-item').on('click.uafg', '.ua-fg-dropdown-item', function (e) {
                e.preventDefault();
                var $item = $(this);

                $wrapper.find('.ua-fg-dropdown-item').removeClass('ua-fg-active');
                $item.addClass('ua-fg-active');

                var selectedText = $item.text().trim();
                $filterTrigger.find('.ua-fg-trigger-text').text(selectedText);
                $dropdownMenu.removeClass('ua-fg-open-filters');
                $filterTrigger.attr('aria-expanded', 'false');

                currentFilter = $item.attr('data-filter') || '*';
                triggerIsotopeFilter();

                if (mobileScroll && $(window).width() <= 768) {
                    var targetTop = $container.offset().top - scrollOffset;
                    $('html, body').animate({ scrollTop: targetTop }, 400);
                }
            });

            $(document).on('click.uafg_front_outside', function (e) {
                if (!$(e.target).closest('.ua-fg-dropdown-filter-wrap').length) {
                    $dropdownMenu.removeClass('ua-fg-open-filters');
                    $filterTrigger.attr('aria-expanded', 'false');
                }
            });
        }

        $wrapper.find('.ua-fg-controls').off('keydown.uafg').on('keydown.uafg', '.ua-fg-filter-btn', function (e) {
            if (e.key === 'ArrowRight' || e.key === 'ArrowLeft') {
                var $all = $wrapper.find('.ua-fg-filter-btn');
                var currIdx = $all.index(this);
                var nextIdx = 0;

                if (e.key === 'ArrowRight') {
                    nextIdx = (currIdx + 1) % $all.length;
                } else {
                    nextIdx = (currIdx - 1 + $all.length) % $all.length;
                }

                $all.eq(nextIdx).focus().trigger('click');
            }
        });

        if ($searchBox.length) {
            $searchBox.off('input.uafg keyup.uafg').on('input.uafg keyup.uafg', function () {
                var val = $(this).val();

                if (searchAll && !allItemsAppendedForSearch && remainingItems.length > 0) {
                    var $allNewElements = $(remainingItems.join(''));
                    remainingItems = [];

                    $container.append($allNewElements);
                    if ($grid && typeof $grid.isotope === 'function') {
                        $grid.isotope('appended', $allNewElements);
                        $container.imagesLoaded().progress(function () {
                            $grid.isotope('layout');
                        });
                    }

                    allItemsAppendedForSearch = true;
                    $loadMoreBtn.hide();
                }

                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function () {
                    currentSearch = val;
                    triggerIsotopeFilter();
                }, 250);
            });
        }

        $loadMoreBtn.off('click.uafg').on('click.uafg', function (e) {
            e.preventDefault();
            if (!remainingItems.length) return;

            var $btn = $(this);
            $btn.addClass('ua-fg-loading');

            var filterClass = (currentFilter === '*') ? '' : currentFilter.replace('.', '');
            var batchSize = itemsToShow;
            var batchHtml = [];
            var remainingUnpicked = [];

            for (var i = 0; i < remainingItems.length; i++) {
                var htmlStr = remainingItems[i];
                var matches = (!filterClass || htmlStr.indexOf(filterClass) > -1);

                if (matches && batchHtml.length < batchSize) {
                    batchHtml.push(htmlStr);
                } else {
                    remainingUnpicked.push(htmlStr);
                }
            }

            remainingItems = remainingUnpicked;

            if (batchHtml.length > 0) {
                var $newElements = $(batchHtml.join(''));
                $container.append($newElements);

                if ($grid && typeof $grid.isotope === 'function') {
                    $grid.isotope('appended', $newElements);
                    $container.imagesLoaded().progress(function () {
                        $grid.isotope('layout');
                    });
                }
            }

            $btn.removeClass('ua-fg-loading');
            updateUIStates();
        });

        $wrapper.off('click.uafg', '.ua-fg-lightbox-trigger').on('click.uafg', '.ua-fg-lightbox-trigger', function (e) {
            e.preventDefault();
            var $clicked = $(this);
            var $visibleItems = $container.find('.ua-fg-item:visible .ua-fg-lightbox-trigger');

            var lightboxItems = [];
            var startIndex = 0;

            $visibleItems.each(function (idx) {
                var $el = $(this);
                var type = $el.attr('data-popup-type') || 'image';
                var src = $el.attr('href');
                var title = $el.attr('data-title') || '';
                var videoLayout = $el.attr('data-video-layout') || 'horizontal';

                if ($el.is($clicked) || $el.get(0) === $clicked.get(0)) {
                    startIndex = idx;
                }

                lightboxItems.push({
                    type: type,
                    src: src,
                    title: title,
                    videoLayout: videoLayout
                });
            });

            if (lightboxItems.length > 0) {
                UAFGLightbox.open(lightboxItems, startIndex);
            }
        });

        if (window.location.hash) {
            var hashTarget = window.location.hash.substring(1);
            var $hashBtn = $wrapper.find('.ua-fg-filter-btn#' + hashTarget + ', .ua-fg-dropdown-item#' + hashTarget);
            if ($hashBtn.length) {
                setTimeout(function () {
                    $hashBtn.trigger('click');
                }, 100);
            }
        }

        setTimeout(function () {
            if ($grid && typeof $grid.isotope === 'function') {
                $grid.isotope('layout');
            }
            updateUIStates();
        }, 100);
    };

    /**
     * Elementor Frontend Hook Registration with immediate execution
     */
    function registerElementorHooks() {
        if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
            elementorFrontend.hooks.addAction('frontend/element_ready/ultraaddons-filterable-gallery.default', UltraFilterableGalleryHandler);
            elementorFrontend.hooks.addAction('frontend/element_ready/Filterable_Gallery.default', UltraFilterableGalleryHandler);
            elementorFrontend.hooks.addAction('frontend/element_ready/filterable-gallery.default', UltraFilterableGalleryHandler);
        }
    }

    $(document).ready(function () {
        $('.ua-filterable-gallery-wrap').each(function () {
            var $scope = $(this).closest('.elementor-element');
            if ($scope.length && !$scope.hasClass('ua-fg-ready')) {
                $scope.addClass('ua-fg-ready');
                UltraFilterableGalleryHandler($scope, $);
            }
        });
    });

    if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
        registerElementorHooks();
    } else {
        $(window).on('elementor/frontend/init', registerElementorHooks);
    }

})(jQuery);
