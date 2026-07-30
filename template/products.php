<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
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
if ( is_string( $ultraaddons_title_link ) ) {
	$ultraaddons_title_link = json_decode( $ultraaddons_title_link, true );
}
if ( is_string( $ultraaddons_visible_options ) ) {
	$ultraaddons_visible_options = explode( ',', $ultraaddons_visible_options );
}
if ( is_string( $ultraaddons_product_labels ) ) {
	$ultraaddons_product_labels = explode( ',', $ultraaddons_product_labels );
}
if ( is_string( $ultraaddons_view_more_icon ) ) {
	$ultraaddons_view_more_icon = json_decode( $ultraaddons_view_more_icon, true );
}

$ultraaddons_output       = '';
$ultraaddons_heading_html = '';

$ultraaddons_more_atts = array();

if ( $title ) {
	$ultraaddons_heading_html = $title;

	if ( $ultraaddons_title_link && isset( $ultraaddons_title_link['url'] ) && $ultraaddons_title_link['url'] ) {
		$ultraaddons_heading_html = sprintf( '<a href="%1$s"' . ( $ultraaddons_title_link['is_external'] ? ' target="nofollow"' : '' ) . ( $ultraaddons_title_link['nofollow'] ? ' rel="_blank"' : '' ) . '>%2$s</a>', esc_url( $ultraaddons_title_link['url'] ), $ultraaddons_heading_html );
	}

	$ultraaddons_heading_html = '<h2 class="heading-title">' . $ultraaddons_heading_html . '</h2>';
}
if ( $desc ) {
	$ultraaddons_heading_html .= '<p class="heading-desc">' . $desc . '</p>';
}

if ( $ultraaddons_heading_html ) {
	$ultraaddons_heading_html = '<div class="title-wrapper">' . $ultraaddons_heading_html . '</div>';
}

$ultraaddons_cat_ids = array();

if ( $ultraaddons_category ) {

	if ( ! is_array( $ultraaddons_category ) ) {
		$ultraaddons_category = explode( ',', $ultraaddons_category );
	}

	for ( $ultraaddons_i = 0; $ultraaddons_i < count( $ultraaddons_category );  $ultraaddons_i ++ ) {
		if ( '0' !== $ultraaddons_category[ $ultraaddons_i ] && ! intval( $ultraaddons_category[ $ultraaddons_i ] ) ) {
			$ultraaddons_category[ $ultraaddons_i ] = get_term_by( 'slug', $ultraaddons_category[ $ultraaddons_i ], 'product_cat' );
			$ultraaddons_category[ $ultraaddons_i ] = $ultraaddons_category[ $ultraaddons_i ] ? $ultraaddons_category[ $ultraaddons_i ]->term_id : -1;
		}
		if ( get_term( $ultraaddons_category[ $ultraaddons_i ], 'product_cat' ) ) {
			$ultraaddons_cat_ids[] = $ultraaddons_category[ $ultraaddons_i ];
		}
	}
}

if ( $filter ) {
	$ultraaddons_terms     = array();
	$ultraaddons_term_args = array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => boolval( $hide_empty_cat ),
	);
	if ( 1 == count( $ultraaddons_cat_ids ) ) {
		$ultraaddons_term_args['parent'] = $ultraaddons_cat_ids[0];
	} elseif ( 1 < count( $ultraaddons_cat_ids ) ) {
		$ultraaddons_term_args['include'] = implode( ',', $ultraaddons_cat_ids );
		$ultraaddons_term_args['orderby'] = 'include';
	} else {
		$ultraaddons_term_args['parent'] = 0;
	}

	$ultraaddons_terms = get_terms( $ultraaddons_term_args );

	if ( count( $ultraaddons_terms ) > 1 ) {
		$ultraaddons_slugs         = array();
		$ultraaddons_category_html = '';

		do_action( 'ultraaddons_save_used_widget', 'tabs' );

		foreach ( $ultraaddons_terms as $ultraaddons_term_cat ) {
			$id             = $ultraaddons_term_cat->term_id;
			$ultraaddons_name           = $ultraaddons_term_cat->name;
			$ultraaddons_slug           = $ultraaddons_term_cat->slug;
			$ultraaddons_slugs[]        = $ultraaddons_slug;
			$ultraaddons_category_html .= '<li class="nav-item"><a href="' . esc_url( get_term_link( $id, 'product_cat' ) ) . '" class="' . esc_attr( $ultraaddons_slug ) . '" data-filter=".' . esc_attr( $ultraaddons_slug ) . '">' . esc_html( $ultraaddons_name ) . '</a></li>';
		}
		$ultraaddons_category_html  = '<ul class="nav nav-filter cat-filter' . ( 'right' == $filter_pos ? ' ml-auto' : '' ) . '"><li class="nav-item active nav-item-all"><a href="#" data-filter="' . implode( ',', $ultraaddons_slugs ) . '">' . esc_html__( 'All', 'ultraaddons-elementor-lite' ) . '</a></li>' . $ultraaddons_category_html;
		$ultraaddons_category_html .= '</ul>';

		if ( 'top' == $filter_pos || 'left' == $filter_pos ) {
			$ultraaddons_output .= $ultraaddons_category_html;
			if ( $ultraaddons_heading_html ) {
				$ultraaddons_output .= $ultraaddons_heading_html;
			}
		} else {
			if ( $ultraaddons_heading_html ) {
				$ultraaddons_output .= $ultraaddons_heading_html;
			}
			$ultraaddons_output .= $ultraaddons_category_html;
		}

		if ( 'left' == $filter_pos || 'right' == $filter_pos ) {
			$ultraaddons_output = '<div class="heading heading-with-filter justify-content-between mb-0">' . $ultraaddons_output . '</div>';
		} elseif ( ! $title_pos ) {
			$ultraaddons_output = '<div class="heading d-block">' . $ultraaddons_output . '</div>';
		}
		if ( $title_pos ) {
			$ultraaddons_output = '<div class="heading side' . ( 'right' == $title_pos ? ' order-last' : '' ) . '">' . $ultraaddons_output . '</div>';
		}
	}
} else {
	if ( $ultraaddons_heading_html ) {
		$ultraaddons_output .= $ultraaddons_heading_html;
	}
}

$ultraaddons_output = '<div class="ua-product-wrapper' . ( $title_pos ? ' d-flex' : '' ) . '">' . $ultraaddons_output;

$ultraaddons_more_atts['columns'] = intval( $columns );

if ( 'featured' == $status ) {
	$ultraaddons_more_atts['visibility'] = 'featured';
} elseif ( 'on_sale' == $status ) {
	$ultraaddons_more_atts['on_sale'] = '1';
} elseif ( 'pre_order' == $status ) {
	$ultraaddons_more_atts['visibility'] = 'pre_order';
}

if ( isset( $total_sales ) && $total_sales ) {
	if ( 'count' == $total_sales || 'percent' == $total_sales ) {
		$ultraaddons_more_atts['total_sales'] = $total_sales;
	}
}

$ultraaddons_ids_filtered = '';

if ( $ultraaddons_ids ) {
	if ( ! is_array( $ultraaddons_ids ) ) {
		$ultraaddons_ids = str_replace( ' ', '', $ultraaddons_ids );
		$ultraaddons_ids = explode( ',', $ultraaddons_ids );
	}
	for ( $ultraaddons_i = 0; $ultraaddons_i < count( $ultraaddons_ids );  $ultraaddons_i ++ ) {
		if ( '0' !== $ultraaddons_ids[ $ultraaddons_i ] && ! intval( $ultraaddons_ids[ $ultraaddons_i ] ) ) {
			if ( defined( 'ULTRAADDONS_VERSION' ) ) {
				$ultraaddons_ids[ $ultraaddons_i ] = ua_get_post_id_by_name( 'product', $ultraaddons_ids[ $ultraaddons_i ] );
			}
		}
	}
	$ultraaddons_ids_filtered = implode( ',', $ultraaddons_ids );
}

if ( $ultraaddons_ids_filtered ) {
	$ultraaddons_more_atts['ids'] = esc_attr( $ultraaddons_ids_filtered );
	$orderby          = 'post__in';
}
if ( count( $ultraaddons_cat_ids ) ) {
	$ultraaddons_more_atts['category'] = esc_attr( implode( ',', $ultraaddons_cat_ids ) );
}
if ( $orderby ) {
	$ultraaddons_more_atts['orderby'] = esc_attr( $orderby );
}
if ( $order ) {
	$ultraaddons_more_atts['order'] = esc_attr( $order );
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
	$ultraaddons_more_atts['status'] = esc_attr( $status );
}

if ( is_array( $count ) && 0 === $count['size'] ) {
	echo wp_kses_post( $ultraaddons_output ) . '</div>';
	return;
}

if ( $count ) {
	if ( is_array( $count ) ) {
		$ultraaddons_more_atts['per_page'] = intval( $count['size'] );
	} else {
		$ultraaddons_more_atts['per_page'] = intval( $count );
	}
}

if ( $spacing ) {
	if ( is_array( $spacing ) ) {
		wc_set_loop_prop( 'spacing', esc_attr( $spacing['size'] ) );
	} else {
		wc_set_loop_prop( 'spacing', esc_attr( $spacing ) );
	}
}

if ( 'yes' == $ultraaddons_out_stock_style ) {
	$ultraaddons_out_stock_style = 'text';
} else {
	$ultraaddons_out_stock_style = '';
}

wc_set_loop_prop( 'load_more', esc_attr( $load_more ) );
if ( 'button' == $load_more && ! $ultraaddons_ids ) {
	wc_set_loop_prop( 'view_more_label', esc_attr( $view_more_label ? $view_more_label : esc_html__( 'View more products', 'ultraaddons-elementor-lite' ) ) );
	wc_set_loop_prop( 'view_more_icon', esc_attr( $ultraaddons_view_more_icon['value'] ) );
	
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
wc_set_loop_prop( 'product_show_op', $ultraaddons_visible_options );
wc_set_loop_prop( 'product_read_more', $product_read_more );
wc_set_loop_prop( 'product_label_type', $product_label_type );
wc_set_loop_prop( 'product_labels', $ultraaddons_product_labels );
wc_set_loop_prop( 'quickview_pos', $quickview_pos );
wc_set_loop_prop( 'wishlist_pos', $wishlist_pos );
wc_set_loop_prop( 'wishlist_style', $wishlist_style );
wc_set_loop_prop( 'out_stock_style', $ultraaddons_out_stock_style );
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

wc_set_loop_prop( 'extra_atts', $ultraaddons_more_atts );

if ( ! $ultraaddons_ids && '' != $load_more ) {
	$ultraaddons_more_atts['paginate'] = 1;
}

wc_set_loop_prop( 'page', 1 );

$ultraaddons_extra_atts = ' ';
foreach ( $ultraaddons_more_atts as $ultraaddons_key => $ultraaddons_value ) {
	$ultraaddons_extra_atts .= $ultraaddons_key . '=' . json_encode( $ultraaddons_value ) . ' ';
}

do_action( 'ultraaddons_save_used_widget', 'products' );
if ( 'slider' == $layout_mode ) {
	do_action( 'ultraaddons_save_used_widget', 'slider' );
}
if ( $type ) {
	if ( is_array( $ultraaddons_visible_options ) && in_array( 'deal', $ultraaddons_visible_options ) ) {
		do_action( 'ultraaddons_save_used_widget', 'countdown' );
	}
} else {
	if ( defined( 'ULTRAADDONS_VERSION' ) && ( in_array( 'deal', get_option( 'product_show_op' ) ) || in_array( 'deal', get_option( 'public_product_show_op' ) ) ) ) {
		do_action( 'ultraaddons_save_used_widget', 'countdown' );
	}
}
$ultraaddons_output .= do_shortcode( '[products ' . $ultraaddons_extra_atts . ']' );

$ultraaddons_output .= '</div>';

echo wp_kses_post( $ultraaddons_output );
