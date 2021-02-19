<?php
namespace UltraAddons\Core;

defined( 'ABSPATH' ) || die();

/**
 * Control of Header_Footer
 * To show Custom Header which is made by elementor Page Builder
 * 
 * Primarily we will use Elementor Template as header and footer. 
 *
 * @todo Header_Footer will load based on database. we have to handle it later.
 * @author Saiful
 * @since 1.0.1.0
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

        if( ! empty( $h_f_data['header_id'] ) && self::get_type() == 'php' ){
            add_action( 'get_header', [__CLASS__, 'show_header'], 10, 2 );
        }
        
        if( ! empty( $h_f_data['footer_id'] ) && self::get_type() == 'php' ){
            add_action( 'get_footer', [__CLASS__, 'show_footer'], 10, 2 );
        }else{
            
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
        if( isset( $data['type'] ) && ! empty( $data['type'] ) ){
            $return = $data['type'];
        }
        
        return apply_filters( 'ultraaddons/header_footer/type', $return );
    }
    
    /**
     * Getting header footer data from
     * database
     * I have taken data based on sell:$key which is 'ultraaddons_header_footer'
     * Actually if not found any data in database, based on this key
     * then it will return default data from property
     * 
     * @Hook Available hook is ultraaddons/header_footer/data. can be call from pro version.
     * 
     * @return array|null
     */
    public static function get_data() {
        /**
         * if not found data based on key, then it will return default 
         * data from property
         * 
         * @since 1.0.1.0
         */
        $data = get_option( self::$key, self::$data );
        return apply_filters( 'ultraaddons/header_footer/data', $data, self );
    }
}

Header_Footer::init();