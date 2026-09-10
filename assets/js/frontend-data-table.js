/**
 * UltraAddons - Data Table Frontend Script
 *
 * Handles:
 * 1. Interactive Column Sorting (numbers, dates, currency, text)
 * 2. Mobile Card/Stack View Responsive Breakpoint Switcher
 * 3. Client-side CSV Table Export
 *
 * @package UltraAddons
 * @version 2.0.4
 * @author Saiful Islam <codersaiful@gmail.com>
 */
(function ($) {
    'use strict';

    var UltraAddonsDataTable = {
        /**
         * Initialize all data table widgets on page.
         */
        init: function ($scope) {
            var $wrapper = $scope.find('.ua-data-table-wrapper');
            if (!$wrapper.length) {
                return;
            }

            UltraAddonsDataTable.initSorting($wrapper);
            UltraAddonsDataTable.initResponsive($wrapper);
            UltraAddonsDataTable.initExport($wrapper);
        },

        /**
         * Table Column Sorting Handler.
         */
        initSorting: function ($wrapper) {
            if ($wrapper.data('sorting') !== true && $wrapper.data('sorting') !== 'true') {
                return;
            }

            var $table = $wrapper.find('.ua-data-table');
            var $headers = $table.find('.ua-table-th');
            var $tbody = $table.find('.ua-table-body');

            $headers.on('click', function () {
                var $th = $(this);
                var colIndex = $th.index();
                var currentSort = $th.hasClass('ua-sorted-asc') ? 'asc' : ($th.hasClass('ua-sorted-desc') ? 'desc' : 'none');
                var newSort = (currentSort === 'asc') ? 'desc' : 'asc';

                // Reset indicators on sibling headers
                $headers.removeClass('ua-sorted-asc ua-sorted-desc');
                $th.addClass(newSort === 'asc' ? 'ua-sorted-asc' : 'ua-sorted-desc');

                var $rows = $tbody.find('.ua-table-row').get();

                $rows.sort(function (rowA, rowB) {
                    var cellA = $(rowA).children('.ua-table-td').eq(colIndex).find('.ua-cell-content').text().trim();
                    var cellB = $(rowB).children('.ua-table-td').eq(colIndex).find('.ua-cell-content').text().trim();

                    // Strip currency and symbols for numeric comparison
                    var cleanA = cellA.replace(/[^0-9.-]+/g, '');
                    var cleanB = cellB.replace(/[^0-9.-]+/g, '');

                    var isNumA = cleanA !== '' && !isNaN(cleanA);
                    var isNumB = cleanB !== '' && !isNaN(cleanB);

                    if (isNumA && isNumB) {
                        var numA = parseFloat(cleanA);
                        var numB = parseFloat(cleanB);
                        return (newSort === 'asc') ? (numA - numB) : (numB - numA);
                    }

                    // Fallback to text comparison
                    return (newSort === 'asc') ? cellA.localeCompare(cellB) : cellB.localeCompare(cellA);
                });

                $.each($rows, function (idx, row) {
                    $tbody.append(row);
                });
            });
        },

        /**
         * Mobile Card Mode Responsive Checker.
         */
        initResponsive: function ($wrapper) {
            if (!$wrapper.hasClass('ua-responsive-card')) {
                return;
            }

            var breakpoint = parseInt($wrapper.data('breakpoint'), 10) || 768;

            function checkBreakpoint() {
                var windowWidth = window.innerWidth || $(window).width();
                if (windowWidth <= breakpoint) {
                    $wrapper.addClass('ua-is-mobile-card');
                } else {
                    $wrapper.removeClass('ua-is-mobile-card');
                }
            }

            checkBreakpoint();

            var resizeTimer;
            $(window).on('resize.uaDataTable', function () {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(checkBreakpoint, 150);
            });
        },

        /**
         * Client-side CSV Export.
         */
        initExport: function ($wrapper) {
            var $exportBtn = $wrapper.find('.ua-table-export-btn');
            if (!$exportBtn.length) {
                return;
            }

            $exportBtn.on('click', function (e) {
                e.preventDefault();

                var $table = $wrapper.find('.ua-data-table');
                var rows = [];

                // 1. Headers
                var headers = [];
                $table.find('.ua-table-th').each(function () {
                    var title = $(this).find('.ua-header-text').text().trim() || $(this).text().trim();
                    headers.push('"' + title.replace(/"/g, '""') + '"');
                });
                if (headers.length) {
                    rows.push(headers.join(','));
                }

                // 2. Rows
                $table.find('.ua-table-body .ua-table-row').each(function () {
                    var rowData = [];
                    $(this).find('.ua-table-td').each(function () {
                        // Extract only cell content, omit mobile header label
                        var text = $(this).find('.ua-cell-content').text().trim();
                        if (!text) {
                            text = $(this).clone().find('.ua-mobile-th').remove().end().text().trim();
                        }
                        rowData.push('"' + text.replace(/"/g, '""') + '"');
                    });
                    if (rowData.length) {
                        rows.push(rowData.join(','));
                    }
                });

                if (!rows.length) {
                    return;
                }

                var csvContent = '\uFEFF' + rows.join('\r\n');
                var blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                var url = URL.createObjectURL(blob);

                var link = document.createElement('a');
                var timestamp = new Date().toISOString().slice(0, 10);
                link.setAttribute('href', url);
                link.setAttribute('download', 'ultraaddons-data-table-' + timestamp + '.csv');
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(url);
            });
        }
    };

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/ultraaddons-data-table.default', UltraAddonsDataTable.init);
    });

    $(document).ready(function () {
        if (!window.elementorFrontend) {
            $('.ua-data-table-wrapper').each(function () {
                UltraAddonsDataTable.init($(this).closest('.elementor-widget'));
            });
        }
    });

})(jQuery);
