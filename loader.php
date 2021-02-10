<?php
namespace UltraAddons;

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
    public $errors = array();
    public $widgetsArray = array();


    public function __construct( $widgetsArray = false ) {

        
        $this->widgetsArray = $widgetsArray;
        
        if( $this->widgetsArray && is_array( $this->widgetsArray ) ){
            
            //Register and Including Base and common Class file
            add_action( 'elementor/widgets/widgets_registered', [ $this, 'register' ],1 );
            
            //Register Widgets All
            add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
            //add_action( 'elementor/controls/controls_registered', [ $this, 'init_controls' ] );
            add_action( 'elementor/elements/categories_registered', [ $this, 'add_categories' ] );

            //Add Style for Widgets
            add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'widget_enqueue' ] );
            add_action( 'wp_enqueue_scripts', [ $this, 'wp_enqueue_scripts' ] );
            
        }else{
            $this->widgetsArray = array();
        }
        //var_dump($this->widgetsArray);
        
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
            $name = isset( $widget['name'] ) ? $widget['name'] : '';

            $name = str_replace('_','-', $name);
            
            $class_name = str_replace( '-','_', $name );
            $class_name =  '\UltraAddons\Widget\\' . ucwords( $class_name, '_' );


            $file = ULTRA_ADDONS_DIR . 'inc/widgets/'. $name . '.php';

            if( file_exists( $file ) ){
                include_once $file;
            }
            
            if( $class_name && class_exists( $class_name ) ){
                ultraaddons_elementor()->widgets_manager->register_widget_type( new $class_name() );
            }
            
        }
        
      

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
         * Load at elementor editing screen 
         * 
         * Mainly I have added an icon for our Elementor Widget
         * over this CSS file
         */
        wp_register_style( 'ultraaddons-screen-style', ULTRA_ADDONS_ASSETS . 'css/elementor-style.css' );
        wp_enqueue_style( 'ultraaddons-screen-style' );
        
        /**
         * Common CSS file for all Widgets
         * 
         * @since 1.0.0.0
         */
        wp_register_style( 'ultraaddons-widgets-style', ULTRA_ADDONS_ASSETS . 'css/widgets.css' );
        wp_enqueue_style( 'ultraaddons-widgets-style' );
        
        
        //Naming of Args
        $name           = 'ultraaddons-elementor-frontend';
        $js_file_url    = apply_filters( 'ultraaddons_elementor_frontend', ULTRA_ADDONS_ASSETS . 'js/frontend.js' );
        $dependency     =  apply_filters( 'ultraaddons_elementor_frontend_dependency', ['jquery'] );//['jquery'];
        $version        = ULTRA_ADDONS_VERSION;
        $in_footer  = true;
        
        wp_register_script( $name, $js_file_url, $dependency, $version, $in_footer );
        wp_enqueue_script( $name );     
        
    }
    
    /**
     * Enqueue CSS file based on Widgets Class
     * 
     * @since 1.0.0.1
     */
    public function widget_enqueue() {
        
        
        foreach( $this->widgetsArray as $widget_key => $widget ){
            $name = isset( $widget['name'] ) ? $widget['name'] : '';

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
            $src = ULTRA_ADDONS_ASSETS . 'css/widgets/' . $name . '.css';
            $css_file_dir = ULTRA_ADDONS_DIR . 'assets/css/widgets/' . $name . '.css';
            
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
     * @since 1.0
     */
    public function add_categories( $elements_manager ) {
        $elements_manager->add_category('ultraaddons', ['title' => 'UltraAddons', 'icon' => 'fa fa-chat']);
    }


}

$widgetsArray = [
    
    'button'=> [
            'name'  => 'Button',
    ],
    
    'Advance_Heading'=> [
            'name'  => 'Advance_Heading',
    ],
    
];

new Loader( $widgetsArray );//( $widgetsArray );
