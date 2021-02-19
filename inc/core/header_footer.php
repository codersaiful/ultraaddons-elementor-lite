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
    
    /**
     * key for update and get data from database.
     *
     * @var string option key for update and get data from database. 
     */
    public static $key = 'ultraaddons_header_footer';
    
    /**
     * Default data for header id, and footer id.
     *
     * @var Array 
     */
    public static $data = [
        'header_id' => false,
        'footer_id' =>  false,
        'type'    => 'php' //It will php and css. In
    ];

    protected static $body_class = [];


    public static function init() {
        $type = self::get_type();
        
        if( self::get_header_id() ){
            self::$body_class[] = 'ultraaddons-header-' . $type;
            if( $type == 'php' ){
                add_action( 'get_header', [__CLASS__, 'add_header'], 10, 2 );
            }else if( $type == 'css' ){
                add_action( 'wp_body_open', [__CLASS__, 'add_header'] );
            }
            
        }
        
        if( self::get_footer_id() ){
            self::$body_class[] = 'ultraaddons-footer-' . $type;
//            add_action( 'get_footer', [__CLASS__, 'show_footer'], 10, 2 );
            if( $type == 'php' ){
                add_action( 'get_footer', [__CLASS__, 'show_footer'], 10, 2 );
            }else if( $type == 'css' ){
                add_action( 'wp_footer', [__CLASS__, 'add_footer'], 0 );
            }
        }
        
        if( self::get_header_id() || self::get_footer_id() ){
            add_filter( 'body_class', [__CLASS__, 'body_class'] );
        }
        
    }
    
    public static function add_footer() {
        echo ultraaddons_elementor_display_content( self::get_footer_id() );
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
    
    public static function add_header() {
        echo ultraaddons_elementor_display_content( self::get_header_id() );
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
        $data = self::get_data();
        if( isset( $data['type'] ) && ! empty( $data['type'] ) ){
            $return = $data['type'];
        }
        
        return apply_filters( 'ultraaddons/header_footer/type', $return );
    }
    
    /**
     * Retrieve Header ID
     * 
     * @return int|false
     */
    public static function get_header_id(){
        $header_id = false;
        $data = self::get_data();
        if( isset( $data['header_id'] ) && ! empty( $data['header_id'] ) ){
            $header_id = (int) $data['header_id'];
        }
        
        return apply_filters( 'ultraaddons/header_footer/header_id', $header_id );
    }

    
    /**
     * Retrieve footer_id ID
     * 
     * @return int|false
     */
    public static function get_footer_id(){
        $footer_id = false;
        $data = self::get_data();
        if( isset( $data['footer_id'] ) && ! empty( $data['footer_id'] ) ){
            $footer_id = (int) $data['footer_id'];
        }
        
        return apply_filters( 'ultraaddons/header_footer/footer_id', $footer_id );
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
        return apply_filters( 'ultraaddons/header_footer/data', $data );
    }
    
    /**
     * adding body class. why we adding body class. Actually
     * if css type header footer, need custom body class
     * 
     * @param array $class Current array of class for site body tag
     * @return array
     */
    public static function body_class( $class ) {
        
        return array_merge( self::$body_class, $class );
    }
}

Header_Footer::init();