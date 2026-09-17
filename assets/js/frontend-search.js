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

    function initUltraAddonsSearch($wrapper) {
        if (!$wrapper.length) return;

        // Prevent double initialization
        if ($wrapper.data('ua-search-init')) {
            return;
        }
        $wrapper.data('ua-search-init', true);

        var searchMode = $wrapper.data('search-mode') || 'live_ajax';
        if (searchMode !== 'live_ajax') {
            return;
        }

        var $form        = $wrapper.find('.ua-search-form'),
            $input       = $wrapper.find('.ua-search-input'),
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

        // Focus & Blur styling
        $input.on('focus', function () {
            $wrapper.addClass('is-focused');
            var kw = $input.val().trim();
            if ($list.children().length > 0 && kw.length >= minChars) {
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
            var keyword      = $input.val().trim();
            var selectedCat  = $catSelect.length ? $catSelect.val() : '';
            var $selectedOpt = $catSelect.length ? $catSelect.find('option:selected') : null;
            var taxonomy     = ($selectedOpt && $selectedOpt.data('taxonomy')) ? $selectedOpt.data('taxonomy') : '';
            var postTypeCat  = ($selectedOpt && $selectedOpt.data('post-type')) ? $selectedOpt.data('post-type') : '';
            var postType     = postTypeCat || targetType;

            // Live search dropdown requires a keyword with minChars
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

            // Cancel any pending request
            if (activeXhr && activeXhr.readyState !== 4) {
                activeXhr.abort();
            }

            var ajaxUrl = (typeof uaSearchConfig !== 'undefined' && uaSearchConfig.ajax_url)
                ? uaSearchConfig.ajax_url
                : ((typeof ULTRAADDONS_DATA !== 'undefined' && ULTRAADDONS_DATA.ajax_url) ? ULTRAADDONS_DATA.ajax_url : '/wp-admin/admin-ajax.php');

            var nonce = (typeof uaSearchConfig !== 'undefined' && uaSearchConfig.nonce)
                ? uaSearchConfig.nonce
                : ((typeof ULTRAADDONS_DATA !== 'undefined' && ULTRAADDONS_DATA.search_nonce) ? ULTRAADDONS_DATA.search_nonce : '');

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
                    post_type: postType,
                    category: selectedCat,
                    taxonomy: taxonomy,
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

        // Form submission handling in AJAX mode
        $form.on('submit', function (e) {
            // If an item in the dropdown is currently highlighted, go to it
            var $selected = $list.find('.ua-search-item.is-selected');
            if ($selected.length) {
                e.preventDefault();
                var $link = $selected.find('.ua-search-item-title a');
                if ($link.length) {
                    var href   = $link.attr('href');
                    var target = $link.attr('target') || '_self';
                    window.open(href, target);
                    return false;
                }
            }

            var kw = $input.val().trim();
            var $selectedOpt = $catSelect.length ? $catSelect.find('option:selected') : null;
            var catLink = ($selectedOpt && $selectedOpt.data('link')) ? $selectedOpt.data('link') : '';

            // If keyword is empty and category is selected with archive link, navigate to that category archive
            if (kw.length === 0 && catLink) {
                e.preventDefault();
                window.location.href = catLink;
                return false;
            }

            // If keyword is too short, do not trigger dropdown
            if (kw.length < minChars) {
                e.preventDefault();
                return false;
            }

            // If results already visible and user hits enter, open first result
            var $firstItem = $list.find('.ua-search-item').first();
            if ($dropdown.is(':visible') && $firstItem.length) {
                e.preventDefault();
                var $firstLink = $firstItem.find('.ua-search-item-title a');
                if ($firstLink.length) {
                    var href   = $firstLink.attr('href');
                    var target = $firstLink.attr('target') || '_self';
                    window.open(href, target);
                    return false;
                }
            }

            // Otherwise, perform live search immediately
            e.preventDefault();
            performSearch(false);
            return false;
        });

        // Keyup / input on search input with 300ms debounce
        $input.on('input keyup', function (e) {
            // Ignore navigation keys here (handled in keydown)
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
                var kw = $input.val().trim();
                if (kw.length >= minChars) {
                    performSearch(false);
                } else {
                    closeDropdown();
                    $list.empty();
                    $footer.hide();
                }
            }, 300);
        });

        // Keyboard navigation (ArrowDown, ArrowUp, Enter, Escape)
        $input.on('keydown', function (e) {
            var $items = $list.find('.ua-search-item');

            if (e.which === 13) { // Enter key
                e.preventDefault();
                $form.trigger('submit');
                return;
            }

            if (e.which === 27) { // Escape
                e.preventDefault();
                closeDropdown();
                return;
            }

            if (!$items.length || !$dropdown.is(':visible')) {
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

        // Category select change triggers instant refresh only if keyword is typed
        if ($catSelect.length) {
            $catSelect.on('change', function () {
                var kw = $input.val().trim();
                if (kw.length >= minChars) {
                    performSearch(false);
                } else {
                    closeDropdown();
                    $list.empty();
                    $footer.hide();
                }
            });
        }

        // Load More Button
        $loadMoreBtn.on('click', function (e) {
            e.preventDefault();
            performSearch(true);
        });

        // Clicking an item redirects to its target URL
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
    }

    // Hook into Elementor frontend if initialized
    $(window).on('elementor/frontend/init', function () {
        if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
            elementorFrontend.hooks.addAction('frontend/element_ready/ultraaddons-search.default', function ($scope) {
                var $wrap = $scope.find('.ua-search-wrapper');
                if (!$wrap.length && $scope.hasClass('ua-search-wrapper')) {
                    $wrap = $scope;
                }
                initUltraAddonsSearch($wrap);
            });
        }
    });

    // Direct DOM ready runner
    $(function () {
        $('.ua-search-wrapper').each(function () {
            initUltraAddonsSearch($(this));
        });
    });

    // Fallback for late-loaded or AJAX rendered content
    $(window).on('load', function () {
        $('.ua-search-wrapper').each(function () {
            initUltraAddonsSearch($(this));
        });
    });

})(jQuery);
