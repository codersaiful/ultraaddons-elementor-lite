<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use UltraAddons\Core\Widgets_Manager;
use UltraAddons\Core\Settings;

defined( 'ABSPATH' ) || die();

/**
 * Base Widget File
 * Our All widget will extend this Base Class
 * 
 * Already declared dynamic name
 * Already declared dynamic title
 * Already declared dynamic wrapper class
 * Already declared category of widget
 * 
 * *************************
 * Declared a new method get_pure_name()
 * this will return Class name without Namespace
 * *************************
 * 
 * ************************
 * ####### IMPORTANT #######
 * This class name space has now UltraAddons\Widget
 * But if we use it as Base
 * then we have to add 'use UltraAddons\Base\Base as Base;' in each widget
 * ************************
 * 
 * @since 1.0.0.1
 * 
 * @author Saiful Islam
 * 
 * @todo Remove return [ 'basic' ]; from get_catogories method.
 */
class Base extends Widget_Base{
    
    /**
     * Mainly called for register Style
     * Added at V1.0.9.1 at: 8 Sept, 2021
     * By: Saiful Islam
     * 
     * @since 1.0.9.1
     * @author Saiful Islam<codersaiful@gmail.com>
     * 
     * @param Array $data Construction param
     * @param Array $ultraaddons_args Construction param
     */
//    public function __construct($data = [], $ultraaddons_args = null) {
//        parent::__construct($data, $ultraaddons_args);
//       
//        $ultraaddons_name = $this->get_pure_name();
//        $ultraaddons_name = str_replace('_','-', $ultraaddons_name);
//        $ultraaddons_name = strtolower( $ultraaddons_name );
//        $handle = 'ultraaddons-' . $ultraaddons_name;
//        $handle = $this->get_css_handle();
//
//        $deps = ['ultraaddons-widgets-style'];
//        $ver  = ULTRA_ADDONS_VERSION;
//        $media= 'all';
//
//        $src = ULTRA_ADDONS_ASSETS . 'css/widgets/' . strtolower( $ultraaddons_name ) . '.css';
//
//        $css_file_dir = ULTRA_ADDONS_DIR . 'assets/css/widgets/' . strtolower( $ultraaddons_name ) . '.css';
//
//
//        $pass_css = false; 
//        if( defined( 'ULTRA_ADDONS_PRO_ASSETS' ) && isset( $widget['is_pro'] ) && $widget['is_pro'] ){
//
//            $src_pro = ULTRA_ADDONS_PRO_ASSETS . 'css/widgets/' . strtolower( $ultraaddons_name ) . '.css';
//            $css_file_dir_pro = ULTRA_ADDONS_PRO_DIR . 'assets/css/widgets/' . strtolower( $ultraaddons_name ) . '.css';
//
//            if( is_file( $css_file_dir_pro ) ){
//                //Direct pass as we founded it in Pro folder
//                $pass_css = true;
//                $src = $src_pro;
//                $css_file_dir = $css_file_dir_pro;
//            }
//        }
//
//        if( $pass_css || is_file( $css_file_dir ) ){ //$pass_css - If true, we will not check again file exist
//             wp_register_style( $handle, $src, $deps, $ver, $media );
//        }
//    }
    
    /**
     * Method Override.
     * Used Elementor's defined method.
     * 
     * @since 1.0.9.1
     * @author Saiful Islam<codersaiful@gmail.com>
     * 
     * @return Array return as Array
     */
//    public function get_style_depends() {
//        $ultraaddons_name = $this->get_pure_name();
//        $ultraaddons_name = str_replace('_','-', $ultraaddons_name);
//        $ultraaddons_name = strtolower( $ultraaddons_name );
//        $handle = 'ultraaddons-' . $ultraaddons_name;
//        $handle = $this->get_css_handle();
//        return [$handle];
//    }

    /**
     * Custom Made Method
     * Basically to catch handle name from class name.
     * 
     * Our defined handle name has come from Class name
     * 
     * @since 1.0.9.1
     * @author Saiful Islam<codersaiful@gmail.com>
     * 
     * @return String
     */
//    protected function get_css_handle(){
//        $ultraaddons_name = $this->get_pure_name();
//        $ultraaddons_name = str_replace('_','-', $ultraaddons_name);
//        $ultraaddons_name = strtolower( $ultraaddons_name );
//        $handle = 'ultraaddons-' . $ultraaddons_name;
//        return $handle;
//    }


    /**
     * Get widget name. Actually I will not re-declare widget name from main Widget
     * Generate dynamic get_name() from this Base Class/Object
     * 
     * ********
     * convert to lower case
     * replace - to _
     * added 'ultraaddons-elementor-lite' as prefix of each widget name
     * ********
     *
     * @since 1.0.0
     * @access public
     * @author Saiful
     *
     * @return string Widget name.
     */
    public function get_name() {
        
        /**
         * Automatically generate widget name from class
         */
        $ultraaddons_name = strtolower( $this->get_pure_name() );
        $ultraaddons_name = str_replace( '_', '-', $ultraaddons_name );
        return 'ultraaddons-' . $ultraaddons_name;
    }
    
    /**
     * 
     * @return string by default, 
     */
    public function get_icon() {
        $ultraaddons_widgetsArray = Widgets_Manager::widgets();
        $title = $this->get_pure_name();
        $ultraaddons_icon = 'ultraaddons ';
        if( is_array( $ultraaddons_widgetsArray ) && isset( $ultraaddons_widgetsArray[$title]['icon'] ) && ! empty( $ultraaddons_widgetsArray[$title]['icon'] ) ){
            $ultraaddons_icon .= $ultraaddons_widgetsArray[$title]['icon'];
        }else{
            $ultraaddons_icon .= 'eicon-check-circle-o';
        }
        
        return $ultraaddons_icon;
    }
    
    /**
     * Retrieve default Title name from Class name without Name space
     * Need Override this method actually.
     * 
     * But if any Widget not declare the get_title() method, then title will come from this base class's method
     * 
     * @since 1.0.0
     * @access public
     * @author Saiful
     * 
     * @return String
     */
    public function get_title() {
        $ultraaddons_widgetsArray = Widgets_Manager::widgets();

        $title = $this->get_pure_name();
        
        if( is_array( $ultraaddons_widgetsArray ) && isset( $ultraaddons_widgetsArray[$title]['name'] ) && ! empty( $ultraaddons_widgetsArray[$title]['name'] ) ){
            return $ultraaddons_widgetsArray[$title]['name'];
        }
        
        return str_replace( '_', ' ', $title );
    }

    /**
     * Get widget categories.
     * Category set from one place, from this Base Class
     * 
     * No need re-declare from Widget.
     *
     * @since 1.0.0
     * @access public
     * @author Saiful
     *
     * @return array Widget categories.
     * 
     * @todo basic will removed after complete
     */
    public function get_categories() {
        
        /**
         * Default Category based on 
         * free or pro
         * 
         * We created pro group in pro version
         * we added and register category pro in pro version.
         * 
         * @since 1.0.7.27
         */
        if( $this->is_wc() ){
            $default = [ 'ultraaddons-wc' ];
        }else if( $this->is_pro() ){
            $default = [ 'ultraaddons-pro' ];
        }else{
            $default = [ 'ultraaddons-elementor-lite' ];
        }
        


        //var_dump($this->get_pure_name(),$this->get_widget_args());


        /**
         * Filter for Change Category for All for any specific 
         * 
         * User and any addons plugin/theme will able to change category
         * 
         * If any user want to set category for any specific Widget, then able to 
         * set category.
         * 
         * *****************************
         * **** To set Category *****
         * To be confirm that, That widget Category is available.
         * ******************************
         * 
         * @return String Widget category name/slug for Elemetor of UltraAddons Plugin
         */
        $widget_category = apply_filters( 'ultraaddons_widget_category', $default, $this );

        /**
         * Widget showing in Desired Category
         * it will come from database
         * which is sroted from Setting page of UltraAddons
         * 
         * @since 1.0.2.1
         */
        if( Settings::get_widget_category() && is_array( $widget_category ) && ! $this->is_pro() ){
            array_push( $widget_category, Settings::get_widget_category() );
        }

        //Check if null or array
        if( empty( $widget_category ) || ! is_array( $widget_category ) ){
            $widget_category = $default;
        }

        return $widget_category; //Here was Static 'ultraaddons-elementor-lite'
    }

    /**
     * Retrieve Help your
     * Temp: https://example.com/widgets/[Class_Name]
     * 
     * Help URL
     *
     * @since 1.0.0
     * @access public
     * @author Saiful
     *
     * @var int Widget Icon.
     */
    public function get_custom_help_url() {
        
        $ultraaddons_name = $this->get_pure_name();
        return ultraaddons_help_url( $ultraaddons_name, $this );
    }
    
    /**
     * set Default keywords
     * 
     * @since 1.0.0
     * @access public
     * @author Saiful
     * 
     * @return array Widget keyword's Array
     */
    public function get_keywords() {
        return [ 'ultraaddons-elementor-lite', 'addons', 'basic', 'ua', 'latest' ];
    }
    
    /**
     * Overriding default function to add custom html class.
     *
     * @since 1.0.0
     * @access public
     * @author Saiful
     * 
     * @return string Wrapper class for html markup
     */
    public function get_html_wrapper_class() {
        $wr_class = strtolower( str_replace( '_', '-', $this->get_pure_name() ) );
        $ultraaddons_html_class = parent::get_html_wrapper_class();
        $ultraaddons_html_class .= ' ua-element';
        $ultraaddons_html_class .= ' ua-element-' . $wr_class . ' ';
        //$ultraaddons_html_class .= ' ua-' . $this->get_name() . ' ';
        
        return rtrim( $ultraaddons_html_class );
    }
    
    /**
     * Retrieve This Class name, Without NameSpace
     * 
     * & Removed Slash from the Right
     * 
     * @since 1.0.0
     * @access public
     * @author Saiful Islam
     * 
     * @return String name of Class
     */
    public function get_pure_name(){
        $ultraaddons_name = str_replace( __NAMESPACE__, '', $this->get_class_name() );
        return ltrim( $ultraaddons_name, '\\' );
    }
    
    /**
     * Getting Current/Selected widget Args
     * Details
     * based on widget kew from
     * Widgets array file
     * 
     * @since 1.0.7.27
     */
    protected function get_widget_args(){
        $widgetKey = $this->get_pure_name();
        return Widgets_Manager::getWidget( $widgetKey );
    }
    
    /**
     * Getting free or pro info from here
     * 
     * @return Boolean
     */
    public function is_pro(){
        $ultraaddons_args = $this->get_widget_args();
        return isset( $ultraaddons_args['is_pro'] ) ? $ultraaddons_args['is_pro'] : false;
    }
    
    /**
     * Getting bool if is WooCommerce Widget
     * 
     * @since 1.1.0.7
     * @return Boolean
     */
    public function is_wc(){
        $ultraaddons_args = $this->get_widget_args();
        return isset( $ultraaddons_args['is_wc'] ) ? $ultraaddons_args['is_wc'] : false;
    }

}