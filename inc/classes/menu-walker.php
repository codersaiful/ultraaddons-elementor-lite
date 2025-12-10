<?php
namespace UltraAddons\Classes;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class Menu_Walker.
 */
class Menu_Walker extends \Walker_Nav_Menu {

	/**
	 * Start element
	 *
	 * @since 1.3.0
	 * @param string $output Output HTML.
	 * @param object $ua_item Individual Menu item.
	 * @param int    $depth Depth.
	 * @param array  $args Arguments array.
	 * @param int    $id Menu ID.
	 * @access public
	 */
	public function start_el( &$output, $ua_item, $depth = 0, $args = [], $id = 0 ) {

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$args   = (object) $args;

		$class_names = '';
		$value       = '';
		$rel_xfn     = '';
		$rel_blank   = '';

		$classes = empty( $ua_item->classes ) ? [] : (array) $ua_item->classes;
		$submenu = $args->has_children ? ' ua-has-submenu' : '';

		if ( 0 === $depth ) {
			array_push( $classes, 'parent' );
		}
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $ua_item, $args, $depth ) );
		$class_names = ' class="' . esc_attr( $class_names ) . $submenu . ' ua-creative-menu"';

		$output .= $indent . '<li id="menu-item-' . $ua_item->ID . '"' . $value . $class_names . '>';

		if ( isset( $ua_item->target ) && '_blank' === $ua_item->target && isset( $ua_item->xfn ) && false === strpos( $ua_item->xfn, 'noopener' ) ) {
			$rel_xfn = ' noopener';
		}
		if ( isset( $ua_item->target ) && '_blank' === $ua_item->target && isset( $ua_item->xfn ) && empty( $ua_item->xfn ) ) {
			$rel_blank = 'rel="noopener"';
		}

		$attributes  = ! empty( $ua_item->attr_title ) ? ' title="' . esc_attr( $ua_item->attr_title ) . '"' : '';
		$attributes .= ! empty( $ua_item->target ) ? ' target="' . esc_attr( $ua_item->target ) . '"' : '';
		$attributes .= ! empty( $ua_item->xfn ) ? ' rel="' . esc_attr( $ua_item->xfn ) . $rel_xfn . '"' : '' . $rel_blank;
		$attributes .= ! empty( $ua_item->url ) ? ' href="' . esc_attr( $ua_item->url ) . '"' : '';

		$atts = apply_filters( 'ua_nav_menu_attrs', $attributes );

		$item_output  = $args->has_children ? '<div class="ua-has-submenu-container">' : '';
		$item_output .= $args->before;
		$item_output .= '<a' . $atts;
		if ( 0 === $depth ) {
			$item_output .= ' class = "ua-menu-item"';
		} else {
			$item_output .= in_array( 'current-menu-item', $ua_item->classes ) ? ' class = "ua-sub-menu-item ua-sub-menu-item-active"' : ' class = "ua-sub-menu-item"';
		}

		$item_output .= '>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $ua_item->title, $ua_item->ID ) . $args->link_after;
		if ( $args->has_children ) {
			$item_output .= "<span class='ua-menu-toggle sub-arrow ua-menu-child-";
			$item_output .= $depth;
			$item_output .= "'><i class='fa'></i></span>";
		}
		$item_output .= '</a>';

		$item_output .= $args->after;
		$item_output .= $args->has_children ? '</div>' : '';

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $ua_item, $depth, $args );
	}

	/**
	 * Display element
	 *
	 * @since 1.3.0
	 * @param object $element Individual Menu element.
	 * @param object $children_elements Child Elements.
	 * @param int    $max_depth Maximum Depth.
	 * @param int    $depth Depth.
	 * @param array  $args Arguments array.
	 * @param string $output Output HTML.
	 * @access public
	 */
	function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {

		$id_field = $this->db_fields['id'];

		if ( is_object( $args[0] ) ) {
			$args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );
		}
		return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}
}

