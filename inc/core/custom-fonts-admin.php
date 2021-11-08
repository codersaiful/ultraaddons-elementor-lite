<?php
namespace UltraAddons\Core;

use UltraAddons\WP\Custom_Fonts_Taxonomy;

defined( 'ABSPATH' ) || die();

/**
 * Control of Custom_Fonts_Admin
 * To show Custom Header which is made by elementor Page Builder
 * 
 * We will handle Custom Fonts from register term 
 *
 * @author Saiful
 * @since 1.0.1.0
 */
class Custom_Fonts_Admin {
    
    public static $font_group_key;

    /**
     * key for update and get data from database.
     *
     * @var string option key for update and get data from database. 
     */
    //public static $key = 'ultraaddons_header_footer';
    

    public static function init() {
        self::$font_group_key = self::get_font_group();
        /**
         * Add Taxonomy for Custom Field
         * AND
         * Adding custom field to Taxonomy
         */
        \UltraAddons\WP\Custom_Fonts_Taxonomy::init();


        /**
         * I will handle Custom Field to adding Extra
         * Option for Each Font
         * I will add custom field for Taxonomy: Custom_Fonts
         * 
         * @since 1.1.0.3
         */
        $font_group_key = self::$font_group_key;
        add_filter( 'manage_edit-' . $font_group_key . '_columns', [__CLASS__, 'manage_columns'] );
        //add_action();
    }

    /**
     * Manage Columns Using wp Action hook
     *
     * @since 1.1.0.3
     * @param array $columns default columns.
     * @return array $columns updated columns.
     */
    public static function manage_columns( $columns ) {

        $screen = get_current_screen();
        // If current screen is add new custom fonts screen.
        if ( isset( $screen->base ) && 'edit-tags' == $screen->base ) {

            $old_columns = $columns;
            $columns     = array(
                'cb'   => $old_columns['cb'],
                'name' => $old_columns['name'],
            );

        }
        return $columns;
    }

    public static function get_font_group(){
        return Custom_Fonts_Taxonomy::get_term_name();
    }
    
}

//Custom_Fonts_Admin::init();