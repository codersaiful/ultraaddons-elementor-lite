<?php
namespace UltraAddons\Controls;

use Elementor\Element_Base;

defined( 'ABSPATH' ) || die();


class Handle_Controls{
    
    public $element;
    public function __construct( Element_Base $element ) {
        $this->element = $element;
        return $this->element;
    }
    
    /**
     * Replace Args label value
     * we will change $args['label'] value with our new name
     * 
     * @param array $args
     * @param type $new_name
     * @return Array
     */
    public static function replace_label( $args = [], $new_name = false ) {
        if( ! is_array( $args ) || ! $new_name || empty( $new_name ) ){
            return $args;
        }
        $args['label'] = $new_name;
        
        return $args;
    }
    
    
    /**
     * Changing anything from selector of Control Args
     * 
     * to change something from Selecto
     * we can use this method
     * 
     * @param type $args main Args of add_control.
     * @param type $target_peroperty_name
     * @param type $new_peroperty_name
     * @return Array Always Array
     */
    public static function replace_selector_value( $args = [], $target_peroperty_name = false,$new_peroperty_name = false ) {
        if( ! is_array( $args ) || ! $target_peroperty_name || ! $new_peroperty_name ){
            return $args;
        }
        $args['label'] = $new_peroperty_name;
        if( isset( $args['selector'] ) && is_string( $args['selector'] ) ){
            $args['selector'] = str_replace( $target_peroperty_name, $new_peroperty_name, $args['selector'] );
        }
        
        if( isset( $args['selectors'] ) && is_array( $args['selectors'] ) ){
            $temp_selectos = [];
            foreach( $args['selectors'] as $selector => $value ){
                $final_value = str_replace( $target_peroperty_name, $new_peroperty_name, $value );
                $temp_selectos[$selector] = $final_value;
            }
            $args['selectors'] = $temp_selectos;
        }
        return $args;
    }
    
    
    /**
     * Changing anything from selector value of Control Args
     * 
     * Here, I will convert Mainly transforms name from selector
     * Suppose: some where, already available rotate()
     * In this place, I will replace it with rotateX,
     * then we have to use this
     * 
     * @param type $args
     * @param type $target_peroperty_name
     * @param type $new_peroperty_name
     * @return Array Always Array
     */
    public static function replace_selector( $args = [], $target_peroperty_name = false,$new_peroperty_name = false ) {
        if( ! is_array( $args ) || ! $target_peroperty_name || ! $new_peroperty_name ){
            return $args;
        }
        
        if( isset( $args['selector'] ) && is_string( $args['selector'] ) ){
            $args['selector'] = str_replace( $target_peroperty_name, $new_peroperty_name, $args['selector'] );
        }
        
        if( isset( $args['selectors'] ) && is_array( $args['selectors'] ) ){
            $temp_selectos = [];
            foreach( $args['selectors'] as $selector => $value ){
                $final_selector = str_replace( $target_peroperty_name, $new_peroperty_name, $selector );
                $temp_selectos[$final_selector] = $value;
            }
            $args['selectors'] = $temp_selectos;
        }
        return $args;
    }
    
    /**
     * Here, We will convert second params of default add_control.
     * Only adding over to .elementor-widget-container
     * 
     * @param type $args
     * @param type $hover_selector
     * @return type
     */
    public static function convert_hover( $args = [], $hover_selector = '.elementor-widget-container' ) {
        if( ! is_array( $args ) ){
            return $args;
        }
        
        if( isset( $args['selector'] ) && is_string( $args['selector'] ) ){
            $args['selector'] = str_replace( $hover_selector, $hover_selector . ':hover', $args['selector'] );
        }
        //var_dump($args['selectors']);
        if( isset( $args['selectors'] ) && is_array( $args['selectors'] ) ){
            $temp_selectos = [];
            foreach( $args['selectors'] as $selector => $value ){
                $final_selector = str_replace( $hover_selector, $hover_selector . ':hover', $selector );
                $temp_selectos[$final_selector] = $value;
            }
            $args['selectors'] = $temp_selectos;
        }
        return $args;
    }
//    public function add_control( $args, $control_name = false ) {
//        if( ! is_array( $args ) ){
//            return;
//        }
//        
//        if( empty( $control_name ) || ! is_string( $control_name ) ){
//            return;
//        }
//        
//        $this->element->add_control( $control_name, $args);
//        
//        
//    }
//    
//    public function add_hover_control( $args, $control_name = false ) {
//        if( ! is_array( $args ) ){
//            return;
//        }
//        
//        if( empty( $control_name ) || ! is_string( $control_name ) ){
//            return;
//        }
//        $control_name = $control_name . '_hover';
//        
//        $this->element->add_control( $control_name, $args);
//    }
}