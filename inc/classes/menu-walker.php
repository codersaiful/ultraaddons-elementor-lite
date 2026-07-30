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
	 * @param string $ultraaddons_output Output HTML.
	 * @param object $ultraaddons_item Individual Menu item.
	 * @param int    $depth Depth.
	 * @param array  $ultraaddons_args Arguments array.
	 * @param int    $id Menu ID.
	 * @access public
	 */
	public function start_el( &$ultraaddons_output, $ultraaddons_item, $depth = 0, $ultraaddons_args = [], $id = 0 ) {

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$ultraaddons_args   = (object) $ultraaddons_args;

		$class_names = '';
		$ultraaddons_value       = '';
		$rel_xfn     = '';
		$rel_blank   = '';

		$classes = empty( $ultraaddons_item->classes ) ? [] : (array) $ultraaddons_item->classes;
		$submenu = $ultraaddons_args->has_children ? ' ua-has-submenu' : '';

		if ( 0 === $depth ) {
			array_push( $classes, 'parent' );
		}
		$class_names = join( ' ', apply_filters( 'ultraaddons_nav_menu_css_class', array_filter( $classes ), $ultraaddons_item, $ultraaddons_args, $depth ) );
		$class_names = ' class="' . esc_attr( $class_names ) . $submenu . ' ua-creative-menu"';

		$ultraaddons_output .= $indent . '<li id="menu-item-' . $ultraaddons_item->ID . '"' . $ultraaddons_value . $class_names . '>';

		if ( isset( $ultraaddons_item->target ) && '_blank' === $ultraaddons_item->target && isset( $ultraaddons_item->xfn ) && false === strpos( $ultraaddons_item->xfn, 'noopener' ) ) {
			$rel_xfn = ' noopener';
		}
		if ( isset( $ultraaddons_item->target ) && '_blank' === $ultraaddons_item->target && isset( $ultraaddons_item->xfn ) && empty( $ultraaddons_item->xfn ) ) {
			$rel_blank = 'rel="noopener"';
		}

		$attributes  = ! empty( $ultraaddons_item->attr_title ) ? ' title="' . esc_attr( $ultraaddons_item->attr_title ) . '"' : '';
		$attributes .= ! empty( $ultraaddons_item->target ) ? ' target="' . esc_attr( $ultraaddons_item->target ) . '"' : '';
		$attributes .= ! empty( $ultraaddons_item->xfn ) ? ' rel="' . esc_attr( $ultraaddons_item->xfn ) . $rel_xfn . '"' : '' . $rel_blank;
		$attributes .= ! empty( $ultraaddons_item->url ) ? ' href="' . esc_attr( $ultraaddons_item->url ) . '"' : '';

		$atts = apply_filters( 'ultraaddons_ua_nav_menu_attrs', $attributes );

		$item_output  = $ultraaddons_args->has_children ? '<div class="ua-has-submenu-container">' : '';
		$item_output .= $ultraaddons_args->before;
		$item_output .= '<a' . $atts;
		if ( 0 === $depth ) {
			$item_output .= ' class = "ua-menu-item"';
		} else {
			$item_output .= in_array( 'current-menu-item', $ultraaddons_item->classes ) ? ' class = "ua-sub-menu-item ua-sub-menu-item-active"' : ' class = "ua-sub-menu-item"';
		}

		$item_output .= '>';
		//phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		$item_output .= $ultraaddons_args->link_before . apply_filters( 'the_title', $ultraaddons_item->title, $ultraaddons_item->ID ) . $ultraaddons_args->link_after;
		if ( $ultraaddons_args->has_children ) {
			$item_output .= "<span class='ua-menu-toggle sub-arrow ua-menu-child-";
			$item_output .= $depth;
			$item_output .= "'><i class='fa'></i></span>";
		}
		$item_output .= '</a>';

		$item_output .= $ultraaddons_args->after;
		$item_output .= $ultraaddons_args->has_children ? '</div>' : '';

		$ultraaddons_output .= apply_filters( 'ultraaddons_walker_nav_menu_start_el', $item_output, $ultraaddons_item, $depth, $ultraaddons_args );
	}

	/**
	 * Display element
	 *
	 * @since 1.3.0
	 * @param object $element Individual Menu element.
	 * @param object $children_elements Child Elements.
	 * @param int    $max_depth Maximum Depth.
	 * @param int    $depth Depth.
	 * @param array  $ultraaddons_args Arguments array.
	 * @param string $ultraaddons_output Output HTML.
	 * @access public
	 */
	function display_element( $element, &$children_elements, $max_depth, $depth, $ultraaddons_args, &$ultraaddons_output ) {

		$id_field = $this->db_fields['id'];

		if ( is_object( $ultraaddons_args[0] ) ) {
			$ultraaddons_args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );
		}
		return parent::display_element( $element, $children_elements, $max_depth, $depth, $ultraaddons_args, $ultraaddons_output );
	}
}

