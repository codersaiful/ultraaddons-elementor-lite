<?php
namespace UltraAddons\WP;

class Shortcode{
    public static function init() {
        /**
         * our supported atts
         * id
         * template_id
         */
        add_shortcode( 'UltraAddons_Template', [__CLASS__, 'show_template'] );
    }
    
    public static function show_template( $atts ) {
        if( empty( $atts['id'] ) ){
            return;
        }
        $pairs = array( 'exclude' => false );
        extract( shortcode_atts( $pairs, $atts ) );
        $post_id = (int) $atts['id'];
        
        if( ! $post_id ){
            return;
        }
        
        (int) $select_post_id = $post_id;
        if ( \Elementor\Plugin::instance()->documents->get( $select_post_id ) ) {
            return \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $select_post_id );
        }
        return;
    }
}