<?php
namespace UltraAddons\Extensions;

use Elementor\Controls_Manager;
use UltraAddons\WP\Custom_Fonts_Taxonomy;

defined('ABSPATH') || die();

/**
 * it's Actually a Extension 
 * But it will handle based on our Extension list.
 * It will be enable always. If a user want to do it
 * 
 * Custom Font will available in Extension list from Dashboard
 * 
 * @since 1.1.0.3
 * @author Saiful Islam<codersaiful@gmail.com>
 * @package UltraAddons
 */
class Custom_Fonts{


    public static $fonts = null;
    public static $font_group_key = 'ultraaddons-font-group';
    
    /**
     * Initializing method
     * 
     * Handle like Extension and
     * Mainly for front End.
     * I mean: fonts choosing and doing something from Elementor Edit Screen
     * 
     * @since 1.1.0.3
     * @author Saiful
     */
    public static function init() {
        add_filter( 'elementor/fonts/groups', array( __CLASS__, 'font_group' ) );
        add_filter( 'elementor/fonts/additional_fonts', array( __CLASS__, 'additional_fonts' ) );

        
    }

    public static function font_group( $font_groups ){
        $font_group_key = self::get_font_group();
        $new_group[$font_group_key] = __( 'Custom Fonts - UltraAddons', 'ultraaddons' );
        $font_groups                   = $new_group + $font_groups;
        return $font_groups;
    }
    
    public static function additional_fonts( $fonts ){
        
        $ua_fonts = self::get_fonts();
        
        if( empty( $ua_fonts ) ) return $fonts;
        
        // foreach( $ua_fonts as $font_name => $val ){
        //     $fonts[$font_name] = self::get_font_group();
        // }

        return array_merge( $fonts, $ua_fonts );
    }

    public static function get_font_group(){
        return Custom_Fonts_Taxonomy::get_term_name();
    }
    
    /**
     * Get fonts
     *
     * @since 1.0.0
     * @return array $fonts fonts array of fonts.
     */
    public static function get_fonts() {

        if ( is_null( self::$fonts ) ) {

            self::$fonts = array();
            $args = array(
                'hide_empty' => false
            );
            $term_name = self::get_font_group();
            $terms = get_terms( $term_name, $args );

            if ( ! empty( $terms ) ) {
                foreach ( $terms as $term ) {
                    self::$fonts[ $term->name ] = $term_name;
                }
            }

        }
        return self::$fonts;
    }

    
}