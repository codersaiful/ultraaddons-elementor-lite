<?php
namespace UltraAddons\Core;

defined( 'ABSPATH' ) || die();
/**
 * Description of Header_Footer
 *
 * @author CODES
 */
class Header_Footer {
    
    public static $key = 'ultraaddons_header_footer';
    public static $data = [
        'header_id' => false,
        'footer_id' =>  false,
        'type'    => 'php' //It will php and css. In
    ];

    public static function init() {
        $h_f_data = self::get_data();

        if( ! empty( $h_f_data['header_id'] ) ){
            add_action( 'get_header', [__CLASS__, 'show_header'], 10, 2 );
        }
        
        if( ! empty( $h_f_data['footer_id'] ) ){
            add_action( 'get_footer', [__CLASS__, 'show_footer'], 10, 2 );
        }
        
        

    }
    
    public static function show_footer( $name, $args ) {
        include ULTRA_ADDONS_DIR . 'template/footer.php';
        
        
        $templates   = [];
        $templates[] = 'footer.php';
        // Avoid running wp_footer hooks again.
        remove_all_actions( 'wp_footer' );
        ob_start();
        locate_template( $templates, true );
        ob_get_clean();
    }
    public static function show_header( $name, $args ) {
        include ULTRA_ADDONS_DIR . 'template/header.php';
        
        $templates   = [];
        $templates[] = 'header.php';
        // Avoid running wp_head hooks again.
        remove_all_actions( 'wp_head' );
        ob_start();
        locate_template( $templates, true );
        ob_get_clean();
    }
    
    
    
    /**
     * Getting templateing type.
     * Such: PHP or CSS
     * 
     * *********************
     * in CSS type:
     * Original header footer will be hide by CSS
     * 
     * in PHP type:
     * template file will be override by our own php header file
     * **********************
     * 
     * @access public
     * 
     * @return string Getting templateing type.
     */
    public static function get_type() {
        $return = 'php';
        $data = self::$data;
        if( isset( $data['type'] ) && !empty( $data['type'] ) ){
            $return = $data['type'];
        }
        
        return apply_filters( 'ultraaddons/header_footer/tepe', $return );
    }
    
    public static function get_data() {
        return get_option( self::$key, self::$data );
    }
}

Header_Footer::init();