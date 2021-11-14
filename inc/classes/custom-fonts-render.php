<?php
namespace UltraAddons\Classes;

//use UltraAddons\Extensions\Custom_Fonts as Ex_Fonts;
use UltraAddons\Core\Custom_Fonts_Handle as Fonts;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Custom Font Rendoer for Fronend, Backend(Dashboard) and Elementor Editor Screen
 * 
 * *****************************
 * CUSTOM FONTS
 * ******************************
 * * Front End
 * * Backend (Dashboard)
 * * Elementor Editor Screen
 * 
 * ******************************
 * 
 * @since 1.1.0.5
 */
class Custom_Fonts_Render {
   
    /**
     * Initializing Font render here.
     * In this part, we rendered font to frontend head, backend head and elementor edit screen
     * 
     * @since 1.1.0.5
     *
     * @return void
     */
    public static function init(){
        add_action( 'wp_head', [__CLASS__,'render_style'] );
        add_action( 'elementor/editor/before_enqueue_scripts', [__CLASS__,'render_style'] );
    }

    /**
     * Generate full style tag, style tag markup
     * 
     * Based on selected font, which we have sellected/included in our Fonts Taxonomy
     * 
     * @since 1.0.0.5
     *
     * @return void
     */
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

    /**
     * Generate @fontface based on Font details array
     * 
     * We are getting help from method font_args_by_name()
     *
     * @param String $font_name
     * @return void
     */
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
     * Here will return one font's data
     * 
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

        $font_datas = get_term_meta($term_id,Fonts::$meta_key,true);
        //$font_datas['family'] = $term->name;
        
        //Check and confirmation, If empty url or not found font_datas
        if( empty( $font_datas ) || empty($font_datas['url']) ) return false;

        $urls = $font_datas['url'];

        //Confirm that, there is minimum 1 element/item available.
        if( empty( end($urls) ) ) return false;

        $formats = $font_datas['format'];

        //Assaigning a new array to return new array.
        $font_details = array();
        $fallback = ! empty( $font_datas['fallback'] ) ? $font_datas['fallback'] : false;
        $display = ! empty( $font_datas['display'] ) ? $font_datas['display'] : false;
        $weight = ! empty( $font_datas['weight'] ) ? $font_datas['weight'] : false;
        

        $font_details['font-family'] = '"' . $term->name . '"';
        $font_details['font-weight'] = $weight;
        $font_details['font-display'] = $display;
        $font_details['font-fallback'] = $fallback;

        $font_src = "";
        foreach( $urls as $key => $url ){
            $format = $formats[$key];
            $font_src .= "url($url) format('$format'),";
        }
        $font_src = rtrim( $font_src, ',' );
        $font_details['src'] = $font_src;


        //Filter $font_details to removed empty item
        return array_filter( $font_details );
    }
}
