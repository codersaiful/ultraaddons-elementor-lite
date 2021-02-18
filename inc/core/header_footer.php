<?php
namespace UltraAddons\Core;

defined( 'ABSPATH' ) || die();
/**
 * Description of Header_Footer
 *
 * @author CODES
 */
class Header_Footer {
    
    public static function init() {
        //add_action( 'get_header', [__CLASS__, 'show_header'], 10, 2 );
        add_action( 'wp_body_open', [__CLASS__, 'show_header']);
    }
    
    public static function show_header() {

        (int) $select_post_id = 2304;
        if ( \Elementor\Plugin::instance()->db->is_built_with_elementor( $select_post_id ) ) {
            echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $select_post_id );
        }
    }
}

Header_Footer::init();