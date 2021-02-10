<?php

defined( 'ABSPATH' ) || die();

function ultraaddons_help_url( $class_name, $object = false ){
    
    return 'https://example.com/widgets/' . $class_name;
}

/**
 * Get elementor instance
 *
 * @return \Elementor\Plugin
 */
function ultraaddons_elementor() {
	return \Elementor\Plugin::instance();
}