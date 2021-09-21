<?php
namespace UltraAddons\Base;

use Elementor\Widget_Base;

/**
 * Placeholder for Pro Widget
 * we will register all pro widget
 * in Screen Area widget list
 * 
 * @param String $widget_key 
 * @param Array $widget 
 * 
 * @since 1.0.9.3
 * @by Saiful Islam
 */
class Placeholder extends Widget_Base{
    public $name;
    public $icon;
    public $title;
    public function __construct( String $widget_key, Array $widget = null ) {
        $this->name = $widget_key;
        $this->icon = isset($widget['icon']) ? 'ultraaddons ua-pro ' . $widget['icon'] : 'uicon-ultraaddons';
        $this->title = isset($widget['name']) ? $widget['name'] : 'UA PRO';
        
    }
    
    public function get_name() {
        return $this->name;
    }
    
    public function get_icon() {
        return $this->icon;
    }
    
    public function get_title() {
        return $this->title;
    }
    
    
    public function get_categories() {
        return [ 'ultraaddons-pro-placeholder' ];
    }
    
}

