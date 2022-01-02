<?php

if ( ! function_exists( 'wc_set_loop_prop' ) ) {
	return;
}

extract(
	shortcode_atts(
		array(
			'shortcode'                => 'products',
			'title'                    => '',
			'title_link'               => '',
			'desc'                     => '',
			'status'                   => '',
			'count'                    => 4,
			'filter'                   => '',
			'title_pos'                => '',
			'filter_pos'               => 'bottom',
			'hide_empty_cat'           => '',
			'orderby'                  => 'date',
			'order'                    => 'desc',
			'order_from'               => '',
			'order_from_date'          => '',
			'order_to'                 => '',
			'order_to_date'            => '',
			'hide_out_date'            => false,
			'total_sales'              => '',
			'ids'                      => '',
			'category'                 => '',
			'load_more'                => '',
			'view_more_label'          => '',
			'view_more_icon'           => '',
			'product_border'           => false,

			'layout_mode'              => '',
			'spacing'                  => '',
			'cols_upper_desktop'       => '',
			'columns'                  => 4,
			'columns_tablet'           => '',
			'columns_mobile'           => '',
			'cols_under_mobile'        => '',
			'product_slider_nav_pos'   => '',
			'product_slider_nav_type'  => '',
			'slider_nav'               => false,
			'slider_nav_show'          => 'yes',
			'slider_nav_tablet'        => false,
			'slider_nav_mobile'        => false,
			'slider_dot'               => false,
			'slider_dot_tablet'        => false,
			'slider_dot_mobile'        => false,
			'slider_loop'              => false,
			'slider_auto_play'         => false,
			'slider_auto_play_time'    => 10000,
			'slider_center'            => false,

			'type'                     => '',
			'product_style'            => 'default',
			'product_align'            => 'center',
			't_x_pos'                  => 'center',
			't_y_pos'                  => 'center',
			'product_hover'            => 'yes',
			'product_vertical_animate' => 'fade-left',
			'visible_options'          => array(
				'cat',
				'price',
				'rating',
				'cart',
				'quickview',
				'wishlist',
				'deal',
			),
			'product_read_more'        => 'yes',
			'product_label_type'       => '',
			'product_labels'           => array(
				'featured',
				'new',
				'onsale',
				'outstock',
			),
			'quickview_pos'            => '',
			'wishlist_pos'             => '',
			'wishlist_style'           => 'no',
			'out_stock_style'          => 'no',
			'product_icon_hide'        => 'no',
			'product_label_hide'       => 'no',
			'disable_product_out'      => 'no',
			'action_icon_top'          => 'no',
			'divider_type'             => '',
			'thumbnail_size'           => 'woocommerce_thumbnail',
		),
		$atts
	)
);

// For running shortcode
if ( is_string( $title_link ) ) {
	$title_link = json_decode( $title_link, true );
}
if ( is_string( $visible_options ) ) {
	$visible_options = explode( ',', $visible_options );
}
if ( is_string( $product_labels ) ) {
	$product_labels = explode( ',', $product_labels );
}
if ( is_string( $view_more_icon ) ) {
	$view_more_icon = json_decode( $view_more_icon, true );
}

$output       = '';
$heading_html = '';

$more_atts = array();

if ( $title ) {
	$heading_html = $title;

	if ( $title_link && isset( $title_link['url'] ) && $title_link['url'] ) {
		$heading_html = sprintf( '<a href="%1$s"' . ( $title_link['is_external'] ? ' target="nofollow"' : '' ) . ( $title_link['nofollow'] ? ' rel="_blank"' : '' ) . '>%2$s</a>', esc_url( $title_link['url'] ), $heading_html );
	}

	$heading_html = '<h2 class="heading-title">' . $heading_html . '</h2>';
}
if ( $desc ) {
	$heading_html .= '<p class="heading-desc">' . $desc . '</p>';
}

if ( $heading_html ) {
	$heading_html = '<div class="title-wrapper">' . $heading_html . '</div>';
}

$cat_ids = array();

if ( $category ) {

	if ( ! is_array( $category ) ) {
		$category = explode( ',', $category );
	}

	for ( $i = 0; $i < count( $category );  $i ++ ) {
		if ( '0' !== $category[ $i ] && ! intval( $category[ $i ] ) ) {
			$category[ $i ] = get_term_by( 'slug', $category[ $i ], 'product_cat' );
			$category[ $i ] = $category[ $i ] ? $category[ $i ]->term_id : -1;
		}
		if ( get_term( $category[ $i ], 'product_cat' ) ) {
			$cat_ids[] = $category[ $i ];
		}
	}
}

if ( $filter ) {
	$terms     = array();
	$term_args = array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => boolval( $hide_empty_cat ),
	);
	if ( 1 == count( $cat_ids ) ) {
		$term_args['parent'] = $cat_ids[0];
	} elseif ( 1 < count( $cat_ids ) ) {
		$term_args['include'] = implode( ',', $cat_ids );
		$term_args['orderby'] = 'include';
	} else {
		$term_args['parent'] = 0;
	}

	$terms = get_terms( 'product_cat', $term_args );

	if ( count( $terms ) > 1 ) {
		$slugs         = array();
		$category_html = '';

		do_action( 'ua_save_used_widget', 'tabs' );

		foreach ( $terms as $term_cat ) {
			$id             = $term_cat->term_id;
			$name           = $term_cat->name;
			$slug           = $term_cat->slug;
			$slugs[]        = $slug;
			$category_html .= '<li class="nav-item"><a href="' . esc_url( get_term_link( $id, 'product_cat' ) ) . '" class="' . esc_attr( $slug ) . '" data-filter=".' . esc_attr( $slug ) . '">' . esc_html( $name ) . '</a></li>';
		}
		$category_html  = '<ul class="nav nav-filter cat-filter' . ( 'right' == $filter_pos ? ' ml-auto' : '' ) . '"><li class="nav-item active nav-item-all"><a href="#" data-filter="' . implode( ',', $slugs ) . '">' . esc_html__( 'All', 'ultraaddons' ) . '</a></li>' . $category_html;
		$category_html .= '</ul>';

		if ( 'top' == $filter_pos || 'left' == $filter_pos ) {
			$output .= $category_html;
			if ( $heading_html ) {
				$output .= $heading_html;
			}
		} else {
			if ( $heading_html ) {
				$output .= $heading_html;
			}
			$output .= $category_html;
		}

		if ( 'left' == $filter_pos || 'right' == $filter_pos ) {
			$output = '<div class="heading heading-with-filter justify-content-between mb-0">' . $output . '</div>';
		} elseif ( ! $title_pos ) {
			$output = '<div class="heading d-block">' . $output . '</div>';
		}
		if ( $title_pos ) {
			$output = '<div class="heading side' . ( 'right' == $title_pos ? ' order-last' : '' ) . '">' . $output . '</div>';
		}
	}
} else {
	if ( $heading_html ) {
		$output .= $heading_html;
	}
}

$output = '<div class="ua-product-wrapper' . ( $title_pos ? ' d-flex' : '' ) . '">' . $output;

$more_atts['columns'] = intval( $columns );

if ( 'featured' == $status ) {
	$more_atts['visibility'] = 'featured';
} elseif ( 'on_sale' == $status ) {
	$more_atts['on_sale'] = '1';
} elseif ( 'pre_order' == $status ) {
	$more_atts['visibility'] = 'pre_order';
}

if ( isset( $total_sales ) && $total_sales ) {
	if ( 'count' == $total_sales || 'percent' == $total_sales ) {
		$more_atts['total_sales'] = $total_sales;
	}
}

$ids_filtered = '';

if ( $ids ) {
	if ( ! is_array( $ids ) ) {
		$ids = str_replace( ' ', '', $ids );
		$ids = explode( ',', $ids );
	}
	for ( $i = 0; $i < count( $ids );  $i ++ ) {
		if ( '0' !== $ids[ $i ] && ! intval( $ids[ $i ] ) ) {
			if ( defined( 'ULTRAADDONS_VERSION' ) ) {
				$ids[ $i ] = ua_get_post_id_by_name( 'product', $ids[ $i ] );
			}
		}
	}
	$ids_filtered = implode( ',', $ids );
}

if ( $ids_filtered ) {
	$more_atts['ids'] = esc_attr( $ids_filtered );
	$orderby          = 'post__in';
}
if ( count( $cat_ids ) ) {
	$more_atts['category'] = esc_attr( implode( ',', $cat_ids ) );
}
if ( $orderby ) {
	$more_atts['orderby'] = esc_attr( $orderby );
}
if ( $order ) {
	$more_atts['order'] = esc_attr( $order );
}
if ( $order_from ) {
	if ( 'custom' == $order_from && $order_from_date ) {
		set_query_var( 'order_from', esc_attr( $order_from_date ) );
	} elseif ( 'custom' != $order_from ) {
		set_query_var( 'order_from', esc_attr( $order_from ) );
	}
}
if ( $order_to ) {
	if ( 'custom' == $order_to && $order_to_date ) {
		set_query_var( 'order_to', esc_attr( $order_to_date ) );
	} elseif ( 'custom' != $order_to ) {
		set_query_var( 'order_to', esc_attr( $order_to ) );
	}
}
set_query_var( 'hide_out_date', $hide_out_date );

if ( $status ) {
	$more_atts['status'] = esc_attr( $status );
}

if ( is_array( $count ) && 0 === $count['size'] ) {
	echo $output . '</div>';
	return;
}

if ( $count ) {
	if ( is_array( $count ) ) {
		$more_atts['per_page'] = intval( $count['size'] );
	} else {
		$more_atts['per_page'] = intval( $count );
	}
}

if ( $spacing ) {
	if ( is_array( $spacing ) ) {
		wc_set_loop_prop( 'spacing', esc_attr( $spacing['size'] ) );
	} else {
		wc_set_loop_prop( 'spacing', esc_attr( $spacing ) );
	}
}

if ( 'yes' == $out_stock_style ) {
	$out_stock_style = 'text';
} else {
	$out_stock_style = '';
}

wc_set_loop_prop( 'load_more', esc_attr( $load_more ) );
if ( 'button' == $load_more && ! $ids ) {
	wc_set_loop_prop( 'view_more_label', esc_attr( $view_more_label ? $view_more_label : esc_html__( 'View more products', 'ultraaddons' ) ) );
	wc_set_loop_prop( 'view_more_icon', esc_attr( $view_more_icon['value'] ) );
	
}
wc_set_loop_prop( 'product_border', esc_attr( $product_border ) );
wc_set_loop_prop( 'layout_mode', esc_attr( $layout_mode ) );
wc_set_loop_prop( 'cols_upper_desktop', esc_attr( $cols_upper_desktop ) );
wc_set_loop_prop( 'cols_tablet', esc_attr( $columns_tablet ) );
wc_set_loop_prop( 'cols_mobile', esc_attr( $columns_mobile ) );
wc_set_loop_prop( 'cols_under_mobile', esc_attr( $cols_under_mobile ) );
wc_set_loop_prop( 'slider_nav_pos', esc_attr( $product_slider_nav_pos ) );
wc_set_loop_prop( 'slider_nav_type', esc_attr( $product_slider_nav_type ) );
wc_set_loop_prop( 'widget', 'ua-product' );
wc_set_loop_prop( 'elem', 'product' );
wc_set_loop_prop( 'type', $type );
wc_set_loop_prop( 'product_style', $product_style );
wc_set_loop_prop( 'product_align', $product_align );
wc_set_loop_prop( 't_x_pos', $t_x_pos );
wc_set_loop_prop( 't_y_pos', $t_y_pos );
wc_set_loop_prop( 'product_hover', $product_hover );
wc_set_loop_prop( 'product_vertical_animate', $product_vertical_animate );
wc_set_loop_prop( 'product_show_op', $visible_options );
wc_set_loop_prop( 'product_read_more', $product_read_more );
wc_set_loop_prop( 'product_label_type', $product_label_type );
wc_set_loop_prop( 'product_labels', $product_labels );
wc_set_loop_prop( 'quickview_pos', $quickview_pos );
wc_set_loop_prop( 'wishlist_pos', $wishlist_pos );
wc_set_loop_prop( 'wishlist_style', $wishlist_style );
wc_set_loop_prop( 'out_stock_style', $out_stock_style );
wc_set_loop_prop( 'product_icon_hide', $product_icon_hide );
wc_set_loop_prop( 'product_label_hide', $product_label_hide );
wc_set_loop_prop( 'disable_product_out', $disable_product_out );
wc_set_loop_prop( 'action_icon_top', $action_icon_top );
wc_set_loop_prop( 'divider_type', $divider_type );
wc_set_loop_prop( 'thumbnail_size', $thumbnail_size );
wc_set_loop_prop(
	'slider_nav',
	array(
		esc_attr( $slider_nav ) == 'yes' ? true : false,
		esc_attr( $slider_nav_tablet ) == 'yes' ? true : false,
		esc_attr( $slider_nav_mobile ) == 'yes' ? true : false,
	)
);
wc_set_loop_prop( 'slider_nav_show', $slider_nav_show );
wc_set_loop_prop(
	'slider_dot',
	array(
		esc_attr( $slider_dot ) == 'yes' ? true : false,
		esc_attr( $slider_dot_tablet ) == 'yes' ? true : false,
		esc_attr( $slider_dot_mobile ) == 'yes' ? true : false,
	)
);
wc_set_loop_prop( 'slider_loop', 'yes' == $slider_loop ? true : false );
wc_set_loop_prop( 'slider_auto_play', 'yes' == $slider_auto_play ? true : false );
if ( 'yes' == $slider_auto_play ) {
	wc_set_loop_prop( 'slider_auto_play_time', $slider_auto_play_time );
}
wc_set_loop_prop( 'slider_center', 'yes' == $slider_center ? true : false );

wc_set_loop_prop( 'extra_atts', $more_atts );

if ( ! $ids && '' != $load_more ) {
	$more_atts['paginate'] = 1;
}

wc_set_loop_prop( 'page', 1 );

$extra_atts = ' ';
foreach ( $more_atts as $key => $value ) {
	$extra_atts .= $key . '=' . json_encode( $value ) . ' ';
}

do_action( 'ua_save_used_widget', 'products' );
if ( 'slider' == $layout_mode ) {
	do_action( 'ua_save_used_widget', 'slider' );
}
if ( $type ) {
	if ( is_array( $visible_options ) && in_array( 'deal', $visible_options ) ) {
		do_action( 'ua_save_used_widget', 'countdown' );
	}
} else {
	if ( defined( 'ULTRAADDONS_VERSION' ) && ( in_array( 'deal', get_option( 'product_show_op' ) ) || in_array( 'deal', get_option( 'public_product_show_op' ) ) ) ) {
		do_action( 'ua_save_used_widget', 'countdown' );
	}
}
$output .= do_shortcode( '[products ' . $extra_atts . ']' );

$output .= '</div>';

echo $output;
