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
        add_action( 'get_header', [__CLASS__, 'show_header'], 10, 2 );
    }
    
    public static function show_header( $name, $args ) {
        (int) $select_post_id = 2304;
        if ( \Elementor\Plugin::instance()->db->is_built_with_elementor( $select_post_id ) ) {
            echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $select_post_id );
        }
    }
}

Header_Footer::init();