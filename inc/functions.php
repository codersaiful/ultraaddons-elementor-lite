<?php

defined( 'ABSPATH' ) || die();

/**
 * Get elementor instance
 *
 * @return \Elementor\Plugin
 */
function ua_elementor() {
	return \Elementor\Plugin::instance();
}