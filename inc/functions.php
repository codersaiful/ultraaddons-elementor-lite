<?php

defined( 'ABSPATH' ) || die();

/**
 * Getting Help your for Widget.
 * 
 * Link's prefix, I mean: first part of URL has taken from constant
 * 
 * @param string $class_name
 * @param type $object
 * @return string Full URL link for Class
 */
function ultraaddons_help_url( $class_name, $object = false ){
    return ULTRA_ADDONS_WIDGET_HELP_ULR . $class_name;
}

/**
 * Get Elementor instance
 *
 * @return \Elementor\Plugin
 */
function ultraaddons_elementor() {
	return \Elementor\Plugin::instance();
}