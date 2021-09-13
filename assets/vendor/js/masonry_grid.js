(function($) {
		'use strict';
		$.fn.uaAddonsGridLayout = function () {
			var $el, $grid, resizeTimer;
			/**
			 * Calculate size for grid items
			 */
			function calculateMasonrySize(isotopeOptions) {
				var tabletBreakPoint = 1025,
					mobileBreakPoint = 768,
					windowWidth = window.innerWidth,
					gridWidth = $grid[0].getBoundingClientRect().width,
					gridColumns = 1,
					gridGutter = 0,
					zigzagHeight = 0,
					settings = $el.data('grid'),
					lgGutter = settings.gutter ? settings.gutter : 0,
					mdGutter = settings.gutterTablet ? settings.gutterTablet : lgGutter,
					smGutter = settings.gutterMobile ? settings.gutterMobile : mdGutter,
					lgColumns = settings.columns ? settings.columns : 1,
					mdColumns = settings.columnsTablet ? settings.columnsTablet : lgColumns,
					smColumns = settings.columnsMobile ? settings.columnsMobile : mdColumns,
					lgZigzagHeight = settings.zigzagHeight ? settings.zigzagHeight : 0,
					mdZigzagHeight = settings.zigzagHeightTablet ? settings.zigzagHeightTablet : lgZigzagHeight,
					smZigzagHeight = settings.zigzagHeightMobile ? settings.zigzagHeightMobile : mdZigzagHeight,
					zigzagReversed = settings.zigzagReversed && settings.zigzagReversed === 1 ? true : false;

				if (typeof elementorFrontendConfig !== 'undefined') {
					tabletBreakPoint = elementorFrontendConfig.breakpoints.lg;
					mobileBreakPoint = elementorFrontendConfig.breakpoints.md;
				}

				if (windowWidth >= tabletBreakPoint) {
					gridColumns = lgColumns;
					gridGutter = lgGutter;
					zigzagHeight = lgZigzagHeight;
				} else if (windowWidth >= mobileBreakPoint) {
					gridColumns = mdColumns;
					gridGutter = mdGutter;
					zigzagHeight = mdZigzagHeight;
				} else {
					gridColumns = smColumns;
					gridGutter = smGutter;
					zigzagHeight = smZigzagHeight;
				}

				var totalGutterPerRow = (
					gridColumns - 1
				) * gridGutter;

				var columnWidth = (
					gridWidth - totalGutterPerRow
				) / gridColumns;

				columnWidth = Math.floor(columnWidth);

				var columnWidth2 = columnWidth;
				if (gridColumns > 1) {
					columnWidth2 = columnWidth * 2 + gridGutter;
				}

				$grid.children('.grid-sizer').css({
					'width': columnWidth + 'px'
				});

				var columnHeight = 0,
					columnHeight2 = 0, // 200%.
					columnHeight7 = 0, // 70%.
					columnHeight13 = 0, // 130%.
					isMetro = false,
					ratioW = 1,
					ratioH = 1;

				if (settings.ratio) {
					ratioH = settings.ratio;
					isMetro = true;
				}

				// Calculate item height for only metro type.
				if (isMetro) {
					columnHeight = columnWidth * ratioH / ratioW;
					columnHeight = Math.floor(columnHeight);

					if (gridColumns > 1) {
						columnHeight2 = columnHeight * 2 + gridGutter;
						columnHeight13 = parseInt(columnHeight * 1.3);
						columnHeight7 = columnHeight2 - gridGutter - columnHeight13;
					} else {
						columnHeight2 = columnHeight7 = columnHeight13 = columnHeight;
					}
				}

				$grid.children('.grid-item').each(function (index) {
					var gridItem = $(this);

					// Zigzag.
					if (
						zigzagHeight > 0 // Has zigzag.
						&&
						gridColumns > 1 // More than 1 column.
						&&
						index + 1 <= gridColumns // On top items.
					) {
						if (zigzagReversed === false) { // Is odd item.
							if (index % 2 === 0) {
								gridItem.css({
									'marginTop': zigzagHeight + 'px'
								});
							} else {
								gridItem.css({
									'marginTop': '0px'
								});
							}
						} else {
							if (index % 2 !== 0) {
								gridItem.css({
									'marginTop': zigzagHeight + 'px'
								});
							} else {
								gridItem.css({
									'marginTop': '0px'
								});
							}
						}

					} else {
						gridItem.css({
							'marginTop': '0px'
						});
					}

					if (gridItem.data('width') === 2) {
						gridItem.css({
							'width': columnWidth2 + 'px'
						});
					} else {
						gridItem.css({
							'width': columnWidth + 'px'
						});
					}

					if ('grid' === settings.type) {
						gridItem.css({
							'marginBottom': gridGutter + 'px'
						});
					}

					if (isMetro) {
						var $itemHeight;

						if (gridItem.hasClass('grid-item-height')) {
							$itemHeight = gridItem;
						} else {
							$itemHeight = gridItem.find('.grid-item-height');
						}

						if (gridItem.data('height') === 2) {
							$itemHeight.css({
								'height': columnHeight2 + 'px'
							});
						} else if (gridItem.data('height') === 1.3) {
							$itemHeight.css({
								'height': columnHeight13 + 'px'
							});
						} else if (gridItem.data('height') === 0.7) {
							$itemHeight.css({
								'height': columnHeight7 + 'px'
							});
						} else {
							$itemHeight.css({
								'height': columnHeight + 'px'
							});
						}
					}
				});

				if (isotopeOptions) {
					isotopeOptions.packery.gutter = gridGutter;
					isotopeOptions.fitRows.gutter = gridGutter;
					$grid.isotope(isotopeOptions);
				}

				$grid.isotope('layout');
			}
			return this.each(function () {
				$el = $(this);
				$grid = $el.find('.ua_addons_grid');

				var settings = $el.data('grid');
				var gridData;

				if ($grid.length > 0 && settings && typeof settings.type !== 'undefined') {
					var isotopeOptions = {
						itemSelector: '.grid-item',
						percentPosition: true,
						// transitionDuration: 0,
						packery: {
							columnWidth: '.grid-sizer',
						},
						fitRows: {
							gutter: 10
						}
					};

					if ('masonry' === settings.type || 'metro' === settings.type) {
						isotopeOptions.layoutMode = 'packery';
					} else {
						isotopeOptions.layoutMode = 'fitRows';
					}

					gridData = $grid.imagesLoaded(function () {
						calculateMasonrySize(isotopeOptions);
						$grid.addClass('loaded');
					});				

					$(window).resize(function () {
						calculateMasonrySize(isotopeOptions);

						// Sometimes layout can be overlap. then re-cal layout one time.
						clearTimeout(resizeTimer);
						resizeTimer = setTimeout(function () {
							// Run code here, resizing has "stopped"
							calculateMasonrySize(isotopeOptions);
						}, 500); // DO NOT decrease the time. Sometime, It'll make layout overlay on resize.
					});
				} 
		
            });
		};
}(jQuery));