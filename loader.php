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
    
    public $widgetsArray = array();
    /**
     * Widget List, it will come from an another file.
     * currently we insert at the bottom of this class
     *
     * @var array List of Widgets.  
     * 
     * @access public
     */
    public $ultraaddons_widgetsArray = array();


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
         * Widget array is deferred to the `init` hook (priority 5) so that
         * __() calls inside widgets-array.php happen after `after_setup_theme`
         * fires, preventing the WP 6.7 _load_textdomain_just_in_time notice.
         * 
         * @since 2.0.2
         */
        add_action( 'init', [ $this, 'setup_widgets_array' ], 5 );

        // Register widgets through Elementor's current widget manager API.
        add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
        
        //add_action( 'elementor/controls/controls_registered', [ $this, 'init_controls' ] );
        add_action( 'elementor/elements/categories_registered', [ $this, 'add_categories' ] );

        // Register assets early; Elementor queues the dependencies declared by
        // widgets that are actually used on the current document.
        add_action( 'wp_enqueue_scripts', [ $this, 'register_frontend_assets' ], 5 );
        add_action( 'elementor/frontend/widget/before_render', [ $this, 'enqueue_widget_assets' ] );
    
        /**
         * For Admin and FrontEnd Enqueue 
         * 
         * Mainly I added our font
         * 
         * @since 1.0.2.0
         */
        add_action( 'admin_enqueue_scripts', [ $this, 'icon_enqueue_scripts' ] );

        //For Editor Screen
        add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'elementor_screen_style' ] );

        // Register WC Products AJAX handlers early so they are available
        // on admin-ajax.php requests (outside Elementor's widget registration flow).
        add_action( 'wp_ajax_ua_wc_products_load_more', [ $this, 'handle_wc_products_ajax' ] );
        add_action( 'wp_ajax_nopriv_ua_wc_products_load_more', [ $this, 'handle_wc_products_ajax' ] );

        // Register Recent Blog / Post Grid AJAX handlers early
        add_action( 'wp_ajax_ua_recent_blog_load_posts', [ $this, 'handle_recent_blog_ajax' ] );
        add_action( 'wp_ajax_nopriv_ua_recent_blog_load_posts', [ $this, 'handle_recent_blog_ajax' ] );
        
    }

    

    /**
     * Populate the widgets array on `init` hook (priority 5) so that
     * translation calls inside widgets-array.php happen after
     * `after_setup_theme` has fired (WP 6.7 notice prevention).
     *
     * @since 2.0.2
     */
    public function setup_widgets_array() {
        $this->widgetsArray = Widgets_Manager::activeWidgets();
        if ( ! is_array( $this->widgetsArray ) ) {
            $this->widgetsArray = [];
        }
    }

    /**
     * Included Base Class for our All Widgets
     * will include button common file here
     * 
     * @since 1.0.0.1
     */
    private function include_widget_base() {
        $base_file = ULTRA_ADDONS_DIR . 'inc/base/base.php';
        include_once $base_file;
    }

    /**
     * Register enabled widgets using Elementor's current API.
     *
     * @param \Elementor\Widgets_Manager $widgets_manager Elementor widget manager.
     * @return void
     */
    public function register_widgets( $widgets_manager ) {
        $this->include_widget_base();

        if ( empty( $this->widgetsArray ) ) {
            $this->setup_widgets_array();
        }

        foreach ( $this->widgetsArray as $widget_key => $widget ) {
            $class_name = '\\UltraAddons\\Widget\\' . str_replace( '-', '_', ucwords( strtolower( str_replace( '_', '-', $widget_key ) ), '-' ) );

            if ( class_exists( $class_name ) ) {
                $widgets_manager->register( new $class_name() );
            }
        }
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
        /**
         * Extensions_Manager::init() is deferred to the `init` hook because
         * extensions-array.php contains top-level __() calls that would
         * trigger the WP 6.7 _load_textdomain_just_in_time notice if run
         * during `plugins_loaded`.
         *
         * @since 2.0.2
         */
        add_action( 'init', function() {
            \UltraAddons\Core\Extensions_Manager::init();
        }, 1 );

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

        /**
         * Custom Fonts Include/Change/Handle 
         * 
         * ***********************
         * What we did here [Plan]
         * ***********************
         * 
         * register term for font
         * font upload feature
         * with font variant
         * 
         * We will take title as Font Name
         * 
         * ******************************
         * //\UltraAddons\WP\Custom_Fonts_Taxonomy::init();
         * it was called here, but currently we called it inside 
         * \UltraAddons\Core\Custom_Fonts_Handle::init();
         * 
         * updated calling @Version 1.1.0.4
         * ******************************************
         * 
         * @since 1.1.0.2
         */
        \UltraAddons\Core\Custom_Fonts_Handle::init();
        
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
        $this->register_widgets( ultraaddons_elementor()->widgets_manager );
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
    public function icon_enqueue_scripts() {

        /**
         * UltrAddons font added
         * Enqueue here ultraaddons-icon font
         * Location is: assets/icons/font
         */

        $handle = 'ultraaddons-icon-font';
        $src = ULTRA_ADDONS_ASSETS . 'icons/ultraaddons/css/ultraaddons.css';
        wp_register_style( $handle, $src, array(), ULTRA_ADDONS_VERSION );//, $deps, $ver, $media


        /**
         * Extra Custom Icon, we actually created it for all other icon 
         * which is not our made custom icon.
         * we have collected these icon from fontello.com
         * 
         * #############
         * Icon has collected by Rafiul
         * and added here by Saiful Islam
         * #############
         * 
         * @author Saiful Islam <codersaiful@gmail.com>
         * @since 1.1.0.9
         */
        wp_register_style( 'ultraaddons-extra-icons-style', ULTRA_ADDONS_ASSETS . 'icons/ultra-addons-extra/css/fontello.css', array(), ULTRA_ADDONS_VERSION );
    }

    /**
     * Register all frontend assets without adding them to the page queue.
     *
     * @return void
     */
    public function register_frontend_assets() {
        $this->icon_enqueue_scripts();
        $this->wp_enqueue_style();
        $this->wp_enqueue_scripts();
        $this->widget_enqueue();
    }

    /**
     * Queue shared and per-widget assets only when an UltraAddons widget renders.
     * This also covers legacy widgets that override Elementor dependency methods.
     *
     * @param \Elementor\Widget_Base $widget Current widget instance.
     * @return void
     */
    public function enqueue_widget_assets( $widget ) {
        $widget_name = $widget->get_name();

        if ( 0 !== strpos( $widget_name, 'ultraaddons-' ) ) {
            return;
        }

        wp_enqueue_style( 'ultraaddons-widgets-style' );
        wp_enqueue_style( 'ultraaddons-animate' );
        wp_enqueue_style( 'ultraaddons-icon-font' );
        wp_enqueue_style( 'ultraaddons-extra-icons-style' );
        wp_enqueue_script( 'ultraaddons-elementor-frontend' );

        if ( wp_style_is( $widget_name, 'registered' ) ) {
            wp_enqueue_style( $widget_name );
        }
    }

    /**
     * Our frontend js file loaded here
     * It was both actually and we changed it later
     * How here loading only js file
     * 
     * @since 1.0.0.0
     * @by Saiful
     * @date Fri 15.1.2021 at Home
     */
    public function wp_enqueue_scripts(){
        
        
        //Naming of Args
        $frontend_js_name           = 'ultraaddons-elementor-frontend';
        $js_file_url    = apply_filters( 'ultraaddons_elementor_frontend', ULTRA_ADDONS_ASSETS . 'js/frontend.js' );
        $dependency     =  apply_filters( 'ultraaddons_elementor_frontend_dependency', ['jquery'] );//['jquery'];
        $version        = ULTRA_ADDONS_VERSION;
        $in_footer  = true;
        
        wp_register_script( $frontend_js_name, $js_file_url, $dependency, $version, $in_footer );
        
        $ajax_url = admin_url( 'admin-ajax.php' );
        $version = ULTRA_ADDONS_VERSION;
        $ULTRAADDONS_DATA = array(
            'ajax_url' => $ajax_url,
        );
        $ULTRAADDONS_DATA = apply_filters( 'ultraaddons_localize_data', $ULTRAADDONS_DATA );
        wp_localize_script( $frontend_js_name, 'ULTRAADDONS_DATA', $ULTRAADDONS_DATA );
       
    }

    /**
     * Only Common Style file loaded here
     * Actually we changed it since 1.1.0.8
     * 
     * @since 1.1.0.8
     *
     * @return void
     */
    public function wp_enqueue_style(){
        
        
                
        //Animate CSS Load
        wp_register_style( 'ultraaddons-animate', ULTRA_ADDONS_ASSETS . 'vendor/css/animate.min.css', array(), ULTRA_ADDONS_VERSION );
        

        /**
         * Common CSS file for all Widgets
         * 
         * @since 1.0.0.0
         */
        wp_register_style( 'ultraaddons-widgets-style', ULTRA_ADDONS_ASSETS . 'css/widgets.css', array(), ULTRA_ADDONS_VERSION );
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
        $this->icon_enqueue_scripts();
        
        /**
         * Load at elementor editing screen 
         * 
         * Mainly I have added an icon for our Elementor Widget
         * over this CSS file
         */
        wp_register_style( 'ultraaddons-screen-style', ULTRA_ADDONS_ASSETS . 'css/elementor-style.css', array(), ULTRA_ADDONS_VERSION );
        wp_enqueue_style( 'ultraaddons-screen-style' );
        wp_enqueue_style( 'ultraaddons-icon-font' );
        wp_enqueue_style( 'ultraaddons-extra-icons-style' );
    }

    
    /**
     * Enqueue CSS file based on Widgets Class
     * 
     * @since 1.0.0.1
     */
    public function widget_enqueue() {
        
        foreach( $this->widgetsArray as $widget_key => $widget ){

            $ultraaddons_name = $widget_key;//isset( $widget['name'] ) ? $widget['name'] : '';

            $ultraaddons_name = str_replace('_','-', $ultraaddons_name);
            $ultraaddons_name = strtolower( $ultraaddons_name );
            $handle = 'ultraaddons-' . $ultraaddons_name;
            
            $deps = ['ultraaddons-widgets-style'];
            $ver  = ULTRA_ADDONS_VERSION;
            $media= 'all';
            
            $src = ULTRA_ADDONS_ASSETS . 'css/widgets/' . strtolower( $ultraaddons_name ) . '.css';
            $css_file_dir = ULTRA_ADDONS_DIR . 'assets/css/widgets/' . strtolower( $ultraaddons_name ) . '.css';
            
            /**
             * CSS file load based on Element/Widget
             * 
             * we will load CSS file,
             * 
             * @since 1.0.0.12
             * 
             * Integration with pro
             * @since 1.0.7.27
             */
            $pass_css = false; //Actually if found CSS file in Pro folder, we will direct pass
            if( defined( 'ULTRA_ADDONS_PRO_ASSETS' ) && isset( $widget['is_pro'] ) && $widget['is_pro'] ){
              
                $src_pro = ULTRA_ADDONS_PRO_ASSETS . 'css/widgets/' . strtolower( $ultraaddons_name ) . '.css';
                $css_file_dir_pro = ULTRA_ADDONS_PRO_DIR . 'assets/css/widgets/' . strtolower( $ultraaddons_name ) . '.css';

                if( is_file( $css_file_dir_pro ) ){
                    //Direct pass as we founded it in Pro folder
                    $pass_css = true;
                    $src = $src_pro;
                    $css_file_dir = $css_file_dir_pro;
                }
            }

            if( $pass_css || is_file( $css_file_dir ) ){ //$pass_css - If true, we will not check again file exist
                 wp_register_style( $handle, $src, $deps, $ver, $media );
            }
            
        }
        

    }
    
    /**
     * AJAX proxy: Load More & Category Filter for WC Products widget.
     * Ensures the WC_Products class file is loaded before calling the handler.
     */
    public function handle_wc_products_ajax() {
        $this->include_widget_base();
        if ( class_exists( '\\UltraAddons\\Widget\\WC_Products' ) ) {
            \UltraAddons\Widget\WC_Products::ajax_load_products();
        } else {
            wp_send_json_error( [ 'message' => 'Widget class not found.' ] );
        }
    }

    /**
     * AJAX proxy: Load More, Pagination & Category Filter for Recent Blog widget.
     * Ensures the Recent_Blog class file is loaded before calling the handler.
     */
    public function handle_recent_blog_ajax() {
        $this->include_widget_base();
        $blog_widget_file = ULTRA_ADDONS_DIR . 'inc/widget/recent-blog.php';
        if ( file_exists( $blog_widget_file ) ) {
            include_once $blog_widget_file;
        }
        if ( class_exists( '\\UltraAddons\\Widget\\Recent_Blog' ) ) {
            \UltraAddons\Widget\Recent_Blog::ajax_load_posts();
        } else {
            wp_send_json_error( [ 'message' => 'Recent Blog widget class not found.' ] );
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
        $elements_manager->add_category('ultraaddons-elementor-lite', 
                [
                    'title'     => esc_html__( 'UltraAddons', 'ultraaddons-elementor-lite' ), 
                    'icon'      => 'uicon-ultraaddons'
                ]
        );
        
        $elements_manager->add_category('ultraaddons-wc', 
                [
                    'title'     => esc_html__( 'Ultra WooCommerce', 'ultraaddons-elementor-lite' ), 
                    'icon'      => 'uicon-ultraaddons'
                ]
        );
        
        
    }

    /**
     * AJAX Endpoint: Product View / Quick View Modal
     * 
     * @since 2.0.3
     */
    public function ajax_product_quick_view() {
        if ( class_exists( '\UltraAddons\Widget\Product_List' ) ) {
            \UltraAddons\Widget\Product_List::ajax_quick_view();
        }
        wp_die();
    }

    /**
     * AJAX Endpoint: Product List Load More
     * 
     * @since 2.0.3
     */
    public function ajax_product_list_load_more() {
        if ( class_exists( '\UltraAddons\Widget\Product_List' ) ) {
            \UltraAddons\Widget\Product_List::ajax_load_more();
        }
        wp_die();
    }

    /**
     * AJAX Endpoint: Quick View Modal Add to Cart
     * 
     * @since 2.0.3
     */
    public function ajax_modal_add_to_cart() {
        if ( class_exists( '\UltraAddons\Widget\Product_List' ) ) {
            \UltraAddons\Widget\Product_List::ajax_modal_add_to_cart();
        }
        wp_die();
    }

}

new Loader();//( $ultraaddons_widgetsArray );
