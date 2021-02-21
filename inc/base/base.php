<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use UltraAddons\Core\Widgets_Manager;

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
     * Get widget name. Actually I will not re-declare widget name from main Widget
     * Generate dynamic get_name() from this Base Class/Object
     * 
     * ********
     * convert to lower case
     * replace - to _
     * added 'ultraaddons' as prefix of each widget name
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
        $name = strtolower( $this->get_pure_name() );
        $name = str_replace( '_', '-', $name );
        return 'ultraaddons-' . $name;
    }
    
    /**
     * 
     * @return string by default, 
     */
    public function get_icon() {
        $widgetsArray = Widgets_Manager::widgets();
        $title = $this->get_pure_name();
        $icon = 'ultraaddons ';
        if( is_array( $widgetsArray ) && isset( $widgetsArray[$title]['icon'] ) && ! empty( $widgetsArray[$title]['icon'] ) ){
            $icon .= $widgetsArray[$title]['icon'];
        }else{
            $icon .= 'eicon-check-circle-o';
        }
        
        return $icon;
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
        $widgetsArray = Widgets_Manager::widgets();

        $title = $this->get_pure_name();
        
        if( is_array( $widgetsArray ) && isset( $widgetsArray[$title]['name'] ) && ! empty( $widgetsArray[$title]['name'] ) ){
            return $widgetsArray[$title]['name'];
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
        
        $default = [ 'ultraaddons' ];
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

        //Check if null or array
        if( empty( $widget_category ) || ! is_array( $widget_category ) ){
            $widget_category = $default;
        }
        
        return $widget_category; //Here was Static 'ultraaddons'
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
        
        $name = $this->get_pure_name();
        return ultraaddons_help_url( $name, $this );
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
        return [ 'ultraaddons', 'addons', 'basic', 'ua', 'latest' ];
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
        $html_class = parent::get_html_wrapper_class();
        $html_class .= ' ultraaddons-element';
        $html_class .= ' ua-element-' . $wr_class . ' ';
        $html_class .= ' ua-' . $this->get_name() . ' ';
        
        return rtrim( $html_class );
    }
    
    /**
     * Retrieve This Class name, Without NameSpace
     * 
     * & Removed Slash from the Right
     * 
     * @since 1.0.0
     * @access protected
     * @author Saiful Islam
     * 
     * @return String name of Class
     */
    protected function get_pure_name(){
        $name = str_replace( __NAMESPACE__, '', $this->get_class_name() );
        return ltrim( $name, '\\' );
    }
}