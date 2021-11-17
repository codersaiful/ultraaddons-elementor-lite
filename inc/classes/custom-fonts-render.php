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
        add_action( 'admin_head', [__CLASS__,'render_style'] );
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
     * Generate multiple @fontface based on Font details array
     * 
     * We are getting help from method font_args_by_name()
     * and this method will return like bellow array
     * 
     * CAN BE NEED: $term = get_term_by('name',$name,Fonts::$font_group_key);
     * 
array (size=3)
  0 => 
    array (size=4)
      'font-family' => string '"Arial"' (length=7)
      'font-weight' => string '400' (length=3)
      'font-display' => string 'auto' (length=4)
      'src' => string 'url(mylink/FONT-Thin.woff2) format('woff2'),url(mylink/FONT-SemiBold.woff2) format('woff2')' (length=207)
  1 => 
    array (size=4)
      'font-family' => string '"Arial"' (length=7)
      'font-weight' => string '500' (length=3)
      'font-display' => string 'auto' (length=4)
      'src' => string 'url(mylink/FONT-ThicccAF.ttf) format('ttf')' (length=101)
     *
     * @param String $font_name
     * @return String with details font face.
     */
    public static function fontface_by_name( $font_name ){
        $trangient_name = "ua_font_trangient_" . $font_name;
        $fonts_args = get_transient( $trangient_name );
        
        if( ! $fonts_args ){
            $fonts_args = self::font_args_by_name( $font_name );
            set_transient( $trangient_name, $fonts_args );
        }

        if( empty( $fonts_args ) ) return;

        $fontface = "";
        foreach( $fonts_args as $args ){
            $fontface .= self::fontface_each_by_args( $args ) . "\n";
        }

        return $fontface;
        
    }

    /**
     * Generate individual font face from each args. Each args like bellow:
  1 => 
    array (size=4)
      'font-family' => string '"Arial"' (length=7)
      'font-weight' => string '500' (length=3)
      'font-display' => string 'auto' (length=4)
      'src' => string 'url(mylink/FONT-ThicccAF.ttf) format('ttf')' (length=101)
     *
     * @param Array $args
     * @return String
     */
    protected static function fontface_each_by_args( $args ){
        $property = '';
        foreach( $args as $property_name => $property_value ){
            $property .= "$property_name: $property_value;"; //\n
        }

        if( empty( $property ) ) return;

        return "@font-face {" . $property . "}";
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
        
        $font_datas = get_term_meta( $term_id,Fonts::$meta_key,true );
    
        if( ! isset($font_datas['variants']) ) return false;

        /***
         * Fallback and Display and font name will be use for all variant
         */
        $fallback = ! empty( $font_datas['fallback'] ) ? $font_datas['fallback'] : false;
        $display = ! empty( $font_datas['display'] ) ? $font_datas['display'] : false;

        $variants = $font_datas['variants'];

        $final_fonts = array();
        foreach( $variants as $variant_key => $variant ){
            $urls = $variant['url'];
            if( empty( end($urls) ) ) continue;
            
            $weight = ! empty( $variant['weight'] ) ? $variant['weight'] : false;

            //will use for fornt src
            $formats = $variant['format'];

            $font_details = array();
            $font_details['font-family'] = '"' . $term->name . '"';
            $font_details['font-weight'] = $weight;
            $font_details['font-display'] = $display;
            $font_details['font-fallback'] = $fallback;

            $font_src = "";
            foreach( $urls as $key => $url ){
                
                $format = $formats[$key];
                $font_src .= ! empty( $url ) ? "url($url) format('$format')," : '';
            }
            $font_src = rtrim( $font_src, ',' );
            $font_details['src'] = $font_src;

            //Now Assigning in $final_fonts
            $final_fonts[] = array_filter( $font_details );
        }
        
        return $final_fonts;

    }
}
