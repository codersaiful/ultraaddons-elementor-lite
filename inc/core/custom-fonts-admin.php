<?php
namespace UltraAddons\Core;

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
    
    /**
     * key for update and get data from database.
     *
     * @var string option key for update and get data from database. 
     */
    public static $key = 'ultraaddons_header_footer';
    

    public static function init() {
        $fonts = \UltraAddons\WP\Custom_Fonts_Taxonomy::get_fonts();
        //var_dump($fonts);
        //Taxonomy of Custom Fonts will handle from UltraAddons\WP\Custom_Fonts_Taxonomy
        \UltraAddons\WP\Custom_Fonts_Taxonomy::init();
    }
    
}

//Custom_Fonts_Admin::init();