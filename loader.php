<?php
namespace UltraAddons;

use UltraAddons\Core\Widgets_Manager;

defined( 'ABSPATH' ) || die();

/**
 * Loader Class 
 * Here I will control all of loader file
 * 
 * Basically All Widget and Base widget,Control,Effect File will load from Here
 * 
 * Already did
 * * elementor screen css file
 * * base class included 
 * * js loaded for frontend and css common file loaded here
 * * category added
 * 
 * @since 1.0.0.2
 */
class Loader {
    
    /**
     * Array of Error, Such file not found, class is not exist etc.
     *
     * @access public
     * @var array retrieve list of Error,  
     */
    public $errors = array();
    
    /**
     * Widget List, it will come from an another file.
     * currently we insert at the bottom of this class
     *
     * @var array List of Widgets.  
     * 
     * @access public
     */
    public $widgetsArray = array();


    public function __construct() {
        
        /**
         * Call on Plugin Init, Mean: When UltraAddons Plugin will load
         * 
         * All Object Calling here,
         * Which is Mandetory on Plugin Load 
         * 
         * *********************************
         * Actually first it was called by init action like following:
         * add_action( 'init', [ $this, 'core_load_on_init' ] );
         * 
         * Finally we removed it and called directly
         * *********************************** 
         * 
         * @since 1.0.3.4
         */
        $this->core_load_on_init();
        
        /**
         * Widget has come from Plugin/ultraaddons-elementor-lite/inc/core/widgets_array.php file
         * Controll by Widgets_Manager Object/Class
         * 
         * In that file, The Array's Each Item array formate like bellow:
         * ******************************
         * 'Button'=> [
         *   'name'  => __( 'Button', 'ultraaddons' ),
         *   ],
         * ******************************
         * 
         * ### To that Array ####
         * 
         * Array key will be name of Class. and name should be like file name
         * Actually If Aray key: Advance_Title, file name shold be: advance-title.php in widgets folder and advance-title.css in css folder
         * 
         * ****************************
         * and Each $widgets['name'] will be title of the widgets
         * Actually we will handle also it from database.
         * 
         * Previous Code of WidgetArray is:
         * $widgetsArray = include ULTRA_ADDONS_DIR . 'inc/core/list/widgets-array.php';
         */
       
        $widgetsArray = Widgets_Manager::activeWidgets();
        
        if( ! is_array( $widgetsArray ) ){
            return;
        }

        /**
         * Assigning $this->widgetsArray Array
         * Over Constructor
         * 
         * @access public
         */
        $this->widgetsArray = $widgetsArray;
             
        //Register and Including Base and common Class file
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'register' ],1 );

        //Register Widgets All
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
        
        //add_action( 'elementor/controls/controls_registered', [ $this, 'init_controls' ] );
        add_action( 'elementor/elements/categories_registered', [ $this, 'add_categories' ] );

        //Add Style for Widgets
        add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'widget_enqueue' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'wp_enqueue_scripts' ] );
        
        /**
         * For Admin and FrontEnd Enqueue 
         * 
         * Mainly I added our font
         * 
         * @since 1.0.2.0
         */
        add_action( 'admin_enqueue_scripts', [ $this, 'icon_enqueue_scripts' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'icon_enqueue_scripts' ] );

        //For Editor Screen
        add_action('elementor/editor/before_enqueue_scripts', [ $this, 'elementor_screen_style' ]);
        
        //Mainly UltraAddons Icons font need to load in Elementor Screen.
        add_action('elementor/editor/before_enqueue_scripts', [ $this, 'icon_enqueue_scripts' ]);
        
    }

    /**
     * Included Base Class for our All Widgets
     * will include button common file here
     * 
     * @since 1.0.0.1
     */
    public function register() {
        $base_file = ULTRA_ADDONS_DIR . 'inc/base/base.php';
        include_once $base_file;
    }
    
    /**
     * Core Class/Object init call Here.
     * 
     * ************************
     * All Class/Object Method will call Here
     * Which will Coll without any Action Actually
     * ************************
     * 
     * In Future, we can handle it by any Function
     * and based on Condition Wise.
     * 
     * @since 1.0.1.1
     */
    public function core_load_on_init(){
        \UltraAddons\Core\Extensions_Manager::init();
        \UltraAddons\Core\Header_Footer::init();
        \UltraAddons\Core\Icons_Manager::init();
        
        /**
         * Library Manage
         */
        \UltraAddons\Library\Library_Manager::init();
        
        /**
         * Shortcode for Template
         * Sample shortcode is: [UltraAddons_Template id='123']
         * here, 123 is: POST_ID of template.
         * Even any POST ID will be work, If installed Elementor and UltraAddons
         * 
         * @since 1.0.3.4
         */
        \UltraAddons\WP\Shortcode::init();
        
        /**
         * Header Footer Post Manage
         * 
         * ********************************
         * What we did here:
         * ********************************
         * * It will show a new menu under UltraAddons menu
         * * Create Custom Post Type register header_footer
         * * Change template for single post when header footer on single.php
         * 
         * @since 1.0.4.0
         */
        \UltraAddons\WP\Header_Footer_Post::init();
    }

    /**
     * Init Widgets
     *
     * Include widgets files and register them
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function init_widgets() {

        foreach( $this->widgetsArray as $widget_key => $widget ){
            $name = $widget_key;//isset( $widget['name'] ) ? $widget['name'] : '';

            $name = str_replace('_','-', $name);
            
            $class_name = str_replace( '-','_', $name );
            $class_name =  '\UltraAddons\Widget\\' . ucwords( $class_name, '_' );


            $file = ULTRA_ADDONS_DIR . 'inc/widgets/'. strtolower( $name ) . '.php';
            $file = realpath( $file );
            if( is_readable( $file ) ){
                include_once $file;
            }else{
                $error = esc_html__( "The file ( %s ) of [%s] Class is not founded.", 'ultraaddons' );
                $this->errors[$widget_key] = $error;
                //printf( $error, $file, $name );
            }
            
            if( $class_name && class_exists( $class_name ) ){
                ultraaddons_elementor()->widgets_manager->register_widget_type( new $class_name() );
            }
            
        }
        
      

    }

    /**
     * Adding JS or CSS file for Admin Area and Front End
     * 
     * Actually I have added our own font file for both
     * Front AND
     * BackEnd
     * 
     * ***************************
     *         add_action( 'admin_enqueue_scripts', [ $this, 'icon_enqueue_scripts' ] );
     *         add_action( 'wp_enqueue_scripts', [ $this, 'icon_enqueue_scripts' ] );
     *         CALLED in constructor
     * ***************************
     * 
     * @since 1.0.2.0
     * @author Saiful
     */
    public function icon_enqueue_scripts( $hook_suffix ){
        //var_dump($hook_suffix);
        /**
         * UltrAddons font added
         * Enqueue here ultraaddons-icon font
         * Location is: assets/icons/font
         */

        $handle = 'ultraaddons-icon-font';
        $src = ULTRA_ADDONS_ASSETS . 'icons/ultraaddons/css/ultraaddons.css';
        wp_register_style( $handle, $src );//, $deps, $ver, $media
        wp_enqueue_style( $handle );
    }

    /**
     * Elementor Widget Related 
     * All script will stay here
     * 
     * Actually First time, we though, All Element Widget's js Code will stay for different file
     * with same name of Widget 
     * and inside Widget folder of js folder
     * 
     * But finally we decided to keep one file only
     * and which name will be frontend.js
     * 
     * Here Added Files:
     * - screen-style common css
     * - frontend js
     * - widget css 
     * 
     * 
     * @since 1.0.0.0
     * @by Saiful
     * @date Fri 15.1.2021 at Home
     */
    public function wp_enqueue_scripts(){
        
        /**
         * Common CSS file for all Widgets
         * 
         * @since 1.0.0.0
         */
        wp_register_style( 'ultraaddons-widgets-style', ULTRA_ADDONS_ASSETS . 'css/widgets.css' );
        wp_enqueue_style( 'ultraaddons-widgets-style' );
        
        
        //Naming of Args
        $frontend_js_name           = 'ultraaddons-elementor-frontend';
        $js_file_url    = apply_filters( 'ultraaddons_elementor_frontend', ULTRA_ADDONS_ASSETS . 'js/frontend.js' );
        $dependency     =  apply_filters( 'ultraaddons_elementor_frontend_dependency', ['jquery'] );//['jquery'];
        $version        = ULTRA_ADDONS_VERSION;
        $in_footer  = true;
        
        wp_register_script( $frontend_js_name, $js_file_url, $dependency, $version, $in_footer );
        wp_enqueue_script( $frontend_js_name );     
        
        $ajax_url = admin_url( 'admin-ajax.php' );
        $version = ULTRA_ADDONS_VERSION;
        $ULTRAADDONS_DATA = array( 
            'plugin_name'        => 'UltraAddons',
            'plugin_type'        => ultraaddons_plugin_version(),
            'version'            => $version,
            'active_widgets'     => $this->widgetsArray,
            'widgets'            => Widgets_Manager::widgets(),
            'ajaxurl'            => $ajax_url,
            'ajax_url'           => $ajax_url,
            'site_url'           => site_url(),
            );
            if( class_exists( '\WooCommerce' ) ){
                $ULTRAADDONS_DATA['checkout_url'] = wc_get_checkout_url();
                $ULTRAADDONS_DATA['cart_url'] = wc_get_cart_url();
            }
        $ULTRAADDONS_DATA = apply_filters( 'ultraaddons_localize_data', $ULTRAADDONS_DATA );
        wp_localize_script( $frontend_js_name, 'ULTRAADDONS_DATA', $ULTRAADDONS_DATA );
       
       
        
        //Naming of Args for owlCarousel
        $name           = 'owlCarousel';
        $js_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/js/owl.carousel.min.js';
        $dependency     =  ['jquery'];//['jquery'];
        $version        = ULTRA_ADDONS_VERSION;
        $in_footer  = true;
        
        wp_register_script( $name, $js_file_url, $dependency, $version, $in_footer );
        wp_enqueue_script( $name );
        
        //Naming of Barfiller
        $name           = 'barfiller';
        $js_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/js/barfiller.js';
        $dependency     =  ['jquery'];//['jquery'];
        $version        = ULTRA_ADDONS_VERSION;
        $in_footer  = true;
        
        wp_register_script( $name, $js_file_url, $dependency, $version, $in_footer );
        wp_enqueue_script( $name );
        
        
        //Naming Args For jQuery.Apear
        $name           = 'appear';
        $js_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/js/jquery.appear.js';
        $dependency     =  ['jquery'];//['jquery'];
        $version        = ULTRA_ADDONS_VERSION;
        $in_footer  = false;
        
        wp_register_script( $name, $js_file_url, $dependency, $version, $in_footer );
        wp_enqueue_script( $name );
        
        
        //Naming Args For jQuery.Apear
        $name           = 'jquery-count-to';
        $js_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/js/jquery-count-to.js';
        $dependency     =  ['jquery'];//['jquery'];
        $version        = ULTRA_ADDONS_VERSION;
        $in_footer  = false;
        
        wp_register_script( $name, $js_file_url, $dependency, $version, $in_footer );
        wp_enqueue_script( $name );
        
        
        //Third-party CSS file Load
        wp_enqueue_style('barfiller', ULTRA_ADDONS_ASSETS . 'vendor/css/barfiller.css' );
        
        //Animate CSS Load
        wp_enqueue_style('animate', ULTRA_ADDONS_ASSETS . 'vendor/css/animate.min.css' );
        
        //CSS file for Slider Script Owl Carousel Slider
        wp_enqueue_style('owlCarousel', ULTRA_ADDONS_ASSETS . 'vendor/css/owl.carousel.css' );
        wp_enqueue_style('owlCarousel-theme', ULTRA_ADDONS_ASSETS . 'vendor/css/owl/owl.theme.default.css' );

    }
    
    /**
     * Style for Elementor Load Screen
     * 
     * *******************
     * To Aply style on Elementor Editor Screen
     * such as for Section, Section Title, secion Icon, box icon
     * *******************
     * 
     * @access public
     * 
     * @since 1.0.0.4
     * @return void Adding Elementor Screen Style File
     */
    public function elementor_screen_style() {
        
        /**
         * Load at elementor editing screen 
         * 
         * Mainly I have added an icon for our Elementor Widget
         * over this CSS file
         */
        wp_register_style( 'ultraaddons-screen-style', ULTRA_ADDONS_ASSETS . 'css/elementor-style.css' );
        wp_enqueue_style( 'ultraaddons-screen-style' );
    }
    /**
     * Enqueue CSS file based on Widgets Class
     * 
     * @since 1.0.0.1
     */
    public function widget_enqueue() {
        
        foreach( $this->widgetsArray as $widget_key => $widget ){
            $name = $widget_key;//isset( $widget['name'] ) ? $widget['name'] : '';

            $name = str_replace('_','-', $name);
            $name = strtolower( $name );
            $handle = 'ultraaddons-' . $name;
            
            $deps = ['ultraaddons-widgets-style'];
            $ver  = ULTRA_ADDONS_VERSION;
            $media= 'all';
            /**
             * CSS file load based on Element/Widget
             * 
             * we will load CSS file,
             * If only Available JS file
             * 
             * @since 1.0.0.12
             */
            $src = ULTRA_ADDONS_ASSETS . 'css/widgets/' . strtolower( $name ) . '.css';
            $css_file_dir = ULTRA_ADDONS_DIR . 'assets/css/widgets/' . strtolower( $name ) . '.css';
            
            if( is_file( $css_file_dir ) ){
                 wp_register_style( $handle, $src, $deps, $ver, $media );
                 wp_enqueue_style( $handle );
            }
            
        }
        

    }
    
    /**
     * Init Controls
     *
     * Include controls files and register them
     *
     * @since 1.0.0
     *
     * @access public
     * 
     * @todo Controll is not empty. we will add it later.
     */
    public function init_controls() {

        // Include Control files
        //require_once( __DIR__ . '/assets/controls/test-control.php' );

        // Register control
        //\Elementor\Plugin::$instance->controls_manager->register_control('control-type-', new \Test_Control());
    }

    /**
     * Adding new categories
     * for custom cat
     * 
     * @since 1.0.0
     */
    public function add_categories( $elements_manager ) {
        $elements_manager->add_category('ultraaddons', 
                [
                    'title'     => __( 'Addons - UltraAddons', 'ultraaddons' ), 
                    'icon'      => 'uicon-ultraaddons'
                ]
        );
    }


}

new Loader();//( $widgetsArray );
