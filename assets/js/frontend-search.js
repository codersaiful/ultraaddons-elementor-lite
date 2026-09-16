/**
 * UltraAddons Search (AJAX) Frontend Script
 * 
 * Handles live AJAX search, debouncing, keyboard navigation, 
 * category selection, outside click, and load more pagination.
 * 
 * @package UltraAddons
 * @since 2.0.4
 */
(function ($) {
    'use strict';

    var UltraAddonsSearch = function ($scope) {
        var $wrapper = $scope.find('.ua-search-wrapper');
        if (!$wrapper.length) {
            $wrapper = $scope.hasClass('ua-search-wrapper') ? $scope : $scope.find('.ua-search-wrapper');
        }
        if (!$wrapper.length) return;

        var searchMode = $wrapper.data('search-mode') || 'live_ajax';
        if (searchMode !== 'live_ajax') return;

        var $input       = $wrapper.find('.ua-search-input'),
            $clearBtn    = $wrapper.find('.ua-search-clear-btn'),
            $spinner     = $wrapper.find('.ua-search-spinner'),
            $dropdown    = $wrapper.find('.ua-search-results-dropdown'),
            $list        = $wrapper.find('.ua-search-results-list'),
            $footer      = $wrapper.find('.ua-search-footer'),
            $loadMoreBtn = $wrapper.find('.ua-search-load-more-btn'),
            $catSelect   = $wrapper.find('.ua-search-category-select');

        var minChars        = parseInt($wrapper.data('min-chars'), 10) || 2,
            resultsPerPage  = parseInt($wrapper.data('results-per-page'), 10) || 5,
            targetType      = $wrapper.data('target-type') || 'any',
            showThumbs      = $wrapper.data('show-thumbs') || 'no',
            excludeNoThumb  = $wrapper.data('exclude-no-thumb') || 'no',
            showDesc        = $wrapper.data('show-desc') || 'no',
            excerptLength   = parseInt($wrapper.data('excerpt-length'), 10) || 12,
            showPrice       = $wrapper.data('show-price') || 'no',
            showBadge       = $wrapper.data('show-badge') || 'no',
            showViewBtn     = $wrapper.data('show-view-btn') || 'no',
            viewBtnText     = $wrapper.data('view-btn-text') || 'View',
            openNewTab      = $wrapper.data('open-new-tab') || '_self',
            enableLoadMore  = $wrapper.data('load-more') === 'yes',
            noResultsText   = $wrapper.data('no-results-text') || 'No results found.';

        var currentOffset = 0,
            activeXhr     = null,
            searchTimer   = null,
            selectedIndex = -1;

        // Focus / Blur styling
        $input.on('focus', function () {
            $wrapper.addClass('is-focused');
            if ($list.children().length > 0 && $input.val().trim().length >= minChars) {
                openDropdown();
            }
        });

        $input.on('blur', function () {
            $wrapper.removeClass('is-focused');
        });

        function openDropdown() {
            $dropdown.stop(true, true).fadeIn(150);
            $input.attr('aria-expanded', 'true');
        }

        function closeDropdown() {
            $dropdown.stop(true, true).fadeOut(150);
            $input.attr('aria-expanded', 'false');
            selectedIndex = -1;
            $list.find('.ua-search-item').removeClass('is-selected');
        }

        function showSpinner() {
            $clearBtn.hide();
            $spinner.show();
        }

        function hideSpinner() {
            $spinner.hide();
            if ($input.val().length > 0) {
                $clearBtn.show();
            }
        }

        function performSearch(isAppend) {
            var keyword = $input.val().trim();
            if (keyword.length < minChars) {
                closeDropdown();
                $list.empty();
                $footer.hide();
                return;
            }

            if (!isAppend) {
                currentOffset = 0;
                selectedIndex = -1;
                $list.empty();
                $footer.hide();
            }

            // Cancel any in-flight request to prevent race conditions
            if (activeXhr && activeXhr.readyState !== 4) {
                activeXhr.abort();
            }

            var ajaxUrl = (typeof uaSearchConfig !== 'undefined' && uaSearchConfig.ajax_url)
                ? uaSearchConfig.ajax_url
                : ((typeof ULTRAADDONS_DATA !== 'undefined' && ULTRAADDONS_DATA.ajax_url) ? ULTRAADDONS_DATA.ajax_url : '/wp-admin/admin-ajax.php');

            var nonce = (typeof uaSearchConfig !== 'undefined' && uaSearchConfig.nonce)
                ? uaSearchConfig.nonce
                : ((typeof ULTRAADDONS_DATA !== 'undefined' && ULTRAADDONS_DATA.search_nonce) ? ULTRAADDONS_DATA.search_nonce : '');

            var selectedCat  = $catSelect.length ? $catSelect.val() : '';
            var taxonomyType = ($catSelect.length && $catSelect.find('option:selected').data('taxonomy'))
                ? $catSelect.find('option:selected').data('taxonomy')
                : '';

            if (!isAppend) {
                showSpinner();
            } else {
                $loadMoreBtn.find('.ua-search-load-more-spinner').show();
                $loadMoreBtn.find('.ua-search-load-more-text').css('opacity', '0.5');
            }

            activeXhr = $.ajax({
                url: ajaxUrl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'ultraaddons_ajax_search',
                    nonce: nonce,
                    keyword: keyword,
                    post_type: targetType,
                    category: selectedCat,
                    taxonomy: taxonomyType,
                    per_page: resultsPerPage,
                    offset: currentOffset,
                    exclude_no_thumb: excludeNoThumb,
                    show_thumbs: showThumbs,
                    show_desc: showDesc,
                    excerpt_len: excerptLength,
                    show_price: showPrice,
                    show_badge: showBadge,
                    show_view_btn: showViewBtn,
                    view_btn_text: viewBtnText,
                    open_new_tab: openNewTab,
                    no_results_text: noResultsText
                },
                success: function (res) {
                    hideSpinner();
                    $loadMoreBtn.find('.ua-search-load-more-spinner').hide();
                    $loadMoreBtn.find('.ua-search-load-more-text').css('opacity', '1');

                    if (res && res.success && res.data) {
                        if (isAppend) {
                            $list.append(res.data.html);
                        } else {
                            $list.html(res.data.html);
                        }

                        openDropdown();
                        currentOffset = res.data.offset_next || (currentOffset + res.data.count);

                        if (enableLoadMore && res.data.has_more) {
                            $footer.show();
                        } else {
                            $footer.hide();
                        }
                    } else if (!isAppend) {
                        $list.html('<li class="ua-search-no-results"><p>' + noResultsText + '</p></li>');
                        openDropdown();
                        $footer.hide();
                    }
                },
                error: function (xhr, status) {
                    if (status === 'abort') return;
                    hideSpinner();
                    $loadMoreBtn.find('.ua-search-load-more-spinner').hide();
                    $loadMoreBtn.find('.ua-search-load-more-text').css('opacity', '1');
                }
            });
        }

        // Keyup / input on search input with 350ms debounce
        $input.on('input keyup', function (e) {
            // Ignore Arrow keys, Enter, Escape on keyup
            if ([38, 40, 13, 27].indexOf(e.which) !== -1) {
                return;
            }

            var val = $(this).val();
            if (val.length > 0) {
                $clearBtn.show();
            } else {
                $clearBtn.hide();
                closeDropdown();
                $list.empty();
                $footer.hide();
                return;
            }

            if (searchTimer) {
                clearTimeout(searchTimer);
            }

            searchTimer = setTimeout(function () {
                performSearch(false);
            }, 350);
        });

        // Keyboard navigation (ArrowDown, ArrowUp, Enter, Escape)
        $input.on('keydown', function (e) {
            var $items = $list.find('.ua-search-item');
            if (!$items.length || !$dropdown.is(':visible')) {
                if (e.which === 27) {
                    closeDropdown();
                }
                return;
            }

            if (e.which === 40) { // Arrow Down
                e.preventDefault();
                selectedIndex = (selectedIndex + 1) % $items.length;
                $items.removeClass('is-selected');
                var $active = $items.eq(selectedIndex).addClass('is-selected');
                scrollIntoView($active);
            } else if (e.which === 38) { // Arrow Up
                e.preventDefault();
                selectedIndex = (selectedIndex - 1 + $items.length) % $items.length;
                $items.removeClass('is-selected');
                var $active = $items.eq(selectedIndex).addClass('is-selected');
                scrollIntoView($active);
            } else if (e.which === 13) { // Enter
                if (selectedIndex >= 0 && selectedIndex < $items.length) {
                    e.preventDefault();
                    var $targetLink = $items.eq(selectedIndex).find('.ua-search-item-title a');
                    if ($targetLink.length) {
                        var href   = $targetLink.attr('href');
                        var target = $targetLink.attr('target') || '_self';
                        window.open(href, target);
                    }
                }
            } else if (e.which === 27) { // Escape
                e.preventDefault();
                closeDropdown();
            }
        });

        function scrollIntoView($item) {
            if (!$item.length) return;
            var listHeight = $list.innerHeight();
            var itemTop = $item.position().top;
            var itemHeight = $item.outerHeight();
            if (itemTop + itemHeight > listHeight) {
                $list.scrollTop($list.scrollTop() + itemTop + itemHeight - listHeight);
            } else if (itemTop < 0) {
                $list.scrollTop($list.scrollTop() + itemTop);
            }
        }

        // Clear Button
        $clearBtn.on('click', function (e) {
            e.preventDefault();
            $input.val('').focus();
            $clearBtn.hide();
            closeDropdown();
            $list.empty();
            $footer.hide();
            currentOffset = 0;
        });

        // Category select change triggers instant refresh
        if ($catSelect.length) {
            $catSelect.on('change', function () {
                if ($input.val().trim().length >= minChars) {
                    performSearch(false);
                }
            });
        }

        // Load More Button
        $loadMoreBtn.on('click', function (e) {
            e.preventDefault();
            performSearch(true);
        });

        // Clicking an item in results redirects
        $list.on('click', '.ua-search-item', function (e) {
            if ($(e.target).closest('a').length) return;
            var $link = $(this).find('.ua-search-item-title a');
            if ($link.length) {
                var href   = $link.attr('href');
                var target = $link.attr('target') || '_self';
                window.open(href, target);
            }
        });

        // Outside click closes dropdown
        $(document).on('click.uaSearchOutside', function (e) {
            if (!$(e.target).closest($wrapper).length) {
                closeDropdown();
            }
        });
    };

    // Register with Elementor frontend hook
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/ultraaddons-search.default', UltraAddonsSearch);
    });

    // Document ready fallback
    $(document).ready(function () {
        $('.ua-search-wrapper.ua-search-mode-live_ajax').each(function () {
            var $scope = $(this).closest('.elementor-widget');
            UltraAddonsSearch($scope.length ? $scope : $(this));
        });
    });

})(jQuery);
