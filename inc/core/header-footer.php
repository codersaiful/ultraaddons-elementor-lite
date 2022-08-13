<?php
namespace UltraAddons\Core;

use UltraAddons\WP\Header_Footer_Post as HF_Post;
use UltraAddons\Classes\Header_Footer_Render as HF_Render;

use WP_Query;

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
    
    public static $settings = null;
    /**
	 * Current page type
	 *
	 * @since  1.0.0
	 *
	 * @var $current_page_type
	 */
	private static $current_page_type = null;

    /**
	 * CUrrent page data
	 *
	 * @since  1.0.0
	 *
	 * @var $current_page_data
	 */
	private static $current_page_data = array();


    /**
     * Default data for header id, and footer id.
     *
     * @var Array 
     */
    public static $data = [
        'header_id' => false,
        'footer_id' =>  false,
        'type'      => 'css', //It will php and css. among php,css,additional
        'wrapper'   => 'box', //Default value is: box, among box and flued
    ];

    protected static $body_class = [];


    
    public static function init() {

        if( is_admin() ) return;
        
        $heder_footer = get_option( self::$key );
        
        if( empty( $heder_footer ) ){
            HF_Post::update_option();
            $heder_footer = get_option( self::$key );
        }
        
        if( empty( $heder_footer ) || ! is_array( $heder_footer ) ) return;
        HF_Render::init($heder_footer);
        
        

        // $loc = array_filter($heder_footer,function($item){
        //     // var_dump($item['position']);
        //     return $item['position']=='header';
        // });

        // var_dump($locs,$heder_footer);
        // self::$settings = $heder_footer;
        // var_dump(self::get_current_page_type());
        
        // add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
        return;
        // var_dump($heder_footer); 

        
        $type = self::get_type();
        self::$body_class[] = 'ultraaddons-wrapper-' . self::get_wrapper();
        
        if( self::get_header_id() ){
            self::$body_class[] = 'ultraaddons-header-' . $type;
            if( $type == 'php' ){
                add_action( 'get_header', [__CLASS__, 'show_header'], 10, 2 );
            }else{ //else if( $type == 'css' )
                add_action( 'wp_body_open', [__CLASS__, 'add_header'] );
            }
            
        }
        
        if( self::get_footer_id() ){
            self::$body_class[] = 'ultraaddons-footer-' . $type;
//            add_action( 'get_footer', [__CLASS__, 'show_footer'], 10, 2 );
            if( $type == 'php' ){
                add_action( 'get_footer', [__CLASS__, 'show_footer'], 10, 2 );
            }else{//else if( $type == 'css' )
                add_action( 'wp_footer', [__CLASS__, 'add_footer'] );
            }
        }
        
        if( self::get_header_id() || self::get_footer_id() ){
            add_filter( 'body_class', [__CLASS__, 'body_class'] );
        }
        
    }
    
    

    public static function enqueue_scripts(){
        
        
        $header_id = 231;
        if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
            $css_file = new \Elementor\Core\Files\CSS\Post( $header_id );
        } elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
            $css_file = new \Elementor\Post_CSS_File( $header_id );
        }

        $css_file->enqueue();
    }


    /**
	 * Get current page type
	 *
	 * @since  1.0.0
	 *
	 * @return string Page Type.
	 */
	public static function get_current_page_type() {
		if ( null === self::$current_page_type ) {
			$page_type  = '';
			$current_id = false;

			if ( is_404() ) {
				$page_type = 'is_404';
			} elseif ( is_search() ) {
				$page_type = 'is_search';
			} elseif ( is_archive() ) {
				$page_type = 'is_archive';

				if ( is_category() || is_tag() || is_tax() ) {
					$page_type = 'is_tax';
				} elseif ( is_date() ) {
					$page_type = 'is_date';
				} elseif ( is_author() ) {
					$page_type = 'is_author';
				} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
					$page_type = 'is_woo_shop_page';
				}
			} elseif ( is_home() ) {
				$page_type = 'is_home';
			} elseif ( is_front_page() ) {
				$page_type  = 'is_front_page';
				$current_id = get_the_id();
			} elseif ( is_singular() ) {
				$page_type  = 'is_singular';
				$current_id = get_the_id();
			} else {
				$current_id = get_the_id();
			}

			self::$current_page_data['ID'] = $current_id;
			self::$current_page_type       = $page_type;
		}

		return self::$current_page_type;
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
     * Getting wrapper,
     * Mainly box size of Page container.
     * 
     * we will set page container.
     * such: <div id="page" class="hfeed site ultraaddons-container">
     * for this container, we have set width 100% for flued wrapper.
     * 
     * and for box, we will set standard width
     * 
     * @since 1.0.0.10
     * @access public
     * 
     * @return String wrapper can be box or flued
     */
    public static function get_wrapper() {
        $return = 'flued';
        $data = self::get_data();
        if( isset( $data['wrapper'] ) && ! empty( $data['wrapper'] ) ){
            $return = $data['wrapper'];
        }
        
        return apply_filters( 'ultraaddons/header_footer/wrapper', $return );
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

//Header_Footer::init();