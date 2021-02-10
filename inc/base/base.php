<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;

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
 * @since 1.0.0.1
 * 
 * @author Saiful Islam
 */
class Base extends Widget_Base{

    /**
     * Get widget name.
     *
     * @since 1.0.0
     * @access public
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
     * Retrieve default Title name from Class name without Name space
     * 
     * @since 1.0.0
     * @access public
     * @return String
     */
    public function get_title() {
        return $this->get_pure_name();
    }

    /**
     * Get widget categories.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Widget categories.
     * @todo basic will removed after complete
     */
    public function get_categories() {
        return [ 'basic' ]; //Will remove this line
        return [ 'ultraaddons' ];
    }

    /**
     * Retrieve Help your
     * Temp: https://example.com/widgets/[Class_Name]
     * 
     * Help URL
     *
     * @since 1.0.0
     * @access public
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
     * 
     * @return string Wrapper class for html markup
     */
    public function get_html_wrapper_class() {
        $html_class = parent::get_html_wrapper_class();
        $html_class .= ' ultraaddons-element';
        $html_class .= ' ' . $this->get_name() . ' ';
        return rtrim( $html_class );
    }
    
    /**
     * Retrieve This Class name, Without NameSpace
     * 
     * & Removed Slash from the Right
     * 
     * @return String name of Class
     * @since 1.0.0
     * @access protected
     * @author Saiful Islam
     */
    protected function get_pure_name(){
        $name = str_replace( __NAMESPACE__, '', $this->get_class_name() );
        return ltrim( $name, '\\' );
    }
}