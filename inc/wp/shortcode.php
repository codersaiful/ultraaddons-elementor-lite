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
        add_shortcode( 'UA_Template', [__CLASS__, 'show_template'] );
        add_shortcode( 'ULTRAADDONS_TEMPLATE', [__CLASS__, 'show_template'] );
    }
    
    public static function show_template( $atts ) {
        if( empty( $atts['id'] ) ){
            return;
        }
        $post_id = ! empty( $atts['id'] ) ? (int) $atts['id'] : 0;

        if( ! $post_id ){
            $post_id = ! empty( $atts['template_id'] ) ? (int) $atts['template_id'] : 0;
        }
        if( ! $post_id ){
            $post_id = ! empty( $atts['post_id'] ) ? (int) $atts['post_id'] : 0;
        }
        if( ! $post_id ){
            return;
        }

        $select_post_id = (int) $post_id;
        if ( \Elementor\Plugin::instance()->documents->get( $select_post_id ) ) {
            if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
                $css_file = new \Elementor\Core\Files\CSS\Post( $select_post_id );
            } elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
                // Load elementor styles.
                $css_file = new \Elementor\Post_CSS_File( $select_post_id );
            }
            if(isset($css_file)){
                $css_file->enqueue();
            }
            return \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $select_post_id );
        }
        return;
    }
}