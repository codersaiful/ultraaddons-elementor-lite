<?php
namespace UltraAddons\Classes;

//use UltraAddons\Extensions\Custom_Fonts as Ex_Fonts;
use UltraAddons\Core\Custom_Fonts_Handle as Fonts;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Custom_Fonts_Render {
   
    public static function init(){
        add_action( 'wp_head', [__CLASS__,'render_style'] );
        add_action( 'elementor/editor/before_enqueue_scripts', [__CLASS__,'render_style'] );
    }

    public static function render_style(){
        $fonts = Fonts::get_fonts();
        if( empty( $fonts ) ) return;
        $fonts = array_keys( $fonts ); //Making new array with key,which actually font name 

        $style_code = "<style type='text/css' id='ultraaddons-custom-font-css'>";
        foreach( $fonts as $font_name ){
            $style_code .= self::fontface_by_name( $font_name );
            
        }
        $style_code .= "</style>";
        echo $style_code;
    }

    public static function fontface_by_name( $font_name ){
        
        $args = self::font_args_by_name($font_name);
        if( empty( $args ) ) return;

        $property = '';
        foreach( $args as $property_name => $property_value ){
            $property .= "$property_name: $property_value;";
        }

        if( empty( $property ) ) return;

        return "@font-face {" . $property . "}";;
    }

    /**
     * Getting details array of Font name
     * based on Font name
     * 
     * @param string $name Font name specially Font taxonomy title actually
     * @return Array|null|false for success, returna a array, otherwise return null
     * 
     */
    public static function font_args_by_name( $name ){
        $term = get_term_by('name',$name,Fonts::$font_group_key);
        if( ! $term ) return false;
        $term_id = $term->term_id;

        $font_extras = get_term_meta($term_id,Fonts::$meta_key,true);

        if( empty( $font_extras ) ) return false;
        $font_details = array();
        $fallback = 'Sans-serif';
        $url = 'http://localhost/wordpress_theme/wp-content/uploads/2021/11/Montserrat-SemiBold.otf';
        $format = 'woff2';

        $font_details['font-family'] = '"' . $term->name . '"';
        $font_details['font-weight'] = 200;
        $font_details['font-display'] = 'auto';
        $font_details['font-fallback'] = $fallback;
        $font_details['src'] = "url($url) format('$format')";

        return $font_details;
    }
}
