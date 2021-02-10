<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;

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
         *
         * Card will be card
         * Blog_Card will be blog-card
         */
        $name = str_replace( strtolower(__NAMESPACE__), '', strtolower($this->get_class_name()) );
        $name = str_replace( '_', '-', $name );
        $name = ltrim( $name, '\\' );
        return 'ua-' . $name;
    }
    
    public function get_title() {
        return __( 'UltraAddons Testing Base', 'medilac' );
    }

    public function get_icon() {
        return 'ultraaddons eicon-button';
    }
    
    public function get_keywords() {
        return [ 'ultraaddons', 'test', 'btn', 'bt', 'ua' ];
    }
    
    /**
     * Get widget categories.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return [ 'basic' ];
    }

    /**
     * Override from addon to add custom wrapper class.
     *
     * @return string
     */
    protected function get_custom_wrapper_class() {
        return '';
    }

    /**
     * Overriding default function to add custom html class.
     *
     * @return string
     */
    public function get_html_wrapper_class() {
        $html_class = parent::get_html_wrapper_class();
        $html_class .= ' ultraaddons-element';
        $html_class .= ' ' . $this->get_name();
        $html_class .= ' ' . $this->get_custom_wrapper_class();
        return rtrim( $html_class );
    }
    
    protected function renderss() {
        ?>
            
<h3>Hello </h3>    
<h3>Hello </h3>    
<h3>Hello </h3>    
<h3>Hello </h3>    
<h3>Hello </h3>    
<h3>Hello </h3>    
<h3>Hello </h3>    
<h3>Hello </h3>    
<h3>Hello </h3>    
        <?php
    }
    
}

class Saiful extends Base{
    function get_name() {
        return 'saiful-i-another';
    }
    
    public function get_title() {
        return __( 'UltraAddons Saiful Base', 'medilac' );
    }

    public function get_icon() {
        return 'ultraaddons eicon-button';
    }
    
    public function get_keywords() {
        return [ 'ultraaddons', 'test', 'btn', 'bt', 'ua' ];
    }
    
    protected function render() {
        ?>
            
<h1>Nothing To Do </h1>    
   
  
        <?php
    }
}