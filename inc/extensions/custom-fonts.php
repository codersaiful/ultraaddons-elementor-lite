<?php
namespace UltraAddons\Extensions;

use Elementor\Controls_Manager;

defined('ABSPATH') || die();

class Custom_Fonts{
    public static $font_group_key = 'ultraaddons-font-group';
    
    public static function init() {
        
        add_filter( 'elementor/fonts/groups', array( __CLASS__, 'font_group' ) );
        add_filter( 'elementor/fonts/additional_fonts', array( __CLASS__, 'additional_fonts' ) );

    }

    public static function font_group( $font_groups ){
        $new_group[self::$font_group_key] = __( 'Custom Fonts - UltraAddons', 'ultraaddons' );
        $font_groups                   = $new_group + $font_groups;
        return $font_groups;
    }
    
    public static function additional_fonts( $fonts ){
        $fonts['ANOTHER FONT'] = self::$font_group_key;
        return $fonts;
    }


}