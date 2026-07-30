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
     * we will change $ultraaddons_args['label'] value with our new name
     * 
     * @param array $ultraaddons_args
     * @param type $new_name
     * @return Array
     */
    public static function replace_label( $ultraaddons_args = [], $new_name = false ) {
        if( ! is_array( $ultraaddons_args ) || ! $new_name || empty( $new_name ) ){
            return $ultraaddons_args;
        }
        $ultraaddons_args['label'] = $new_name;
        
        return $ultraaddons_args;
    }
    
    
    /**
     * Changing anything from selector of Control Args
     * 
     * to change something from Selecto
     * we can use this method
     * 
     * @param type $ultraaddons_args main Args of add_control.
     * @param type $target_peroperty_name
     * @param type $new_peroperty_name
     * @return Array Always Array
     */
    public static function replace_selector_value( $ultraaddons_args = [], $target_peroperty_name = false,$new_peroperty_name = false ) {
        if( ! is_array( $ultraaddons_args ) || ! $target_peroperty_name || ! $new_peroperty_name ){
            return $ultraaddons_args;
        }
        $ultraaddons_args['label'] = $new_peroperty_name;
        if( isset( $ultraaddons_args['selector'] ) && is_string( $ultraaddons_args['selector'] ) ){
            $ultraaddons_args['selector'] = str_replace( $target_peroperty_name, $new_peroperty_name, $ultraaddons_args['selector'] );
        }
        
        if( isset( $ultraaddons_args['selectors'] ) && is_array( $ultraaddons_args['selectors'] ) ){
            $temp_selectos = [];
            foreach( $ultraaddons_args['selectors'] as $selector => $ultraaddons_value ){
                $final_value = str_replace( $target_peroperty_name, $new_peroperty_name, $ultraaddons_value );
                $temp_selectos[$selector] = $final_value;
            }
            $ultraaddons_args['selectors'] = $temp_selectos;
        }
        return $ultraaddons_args;
    }
    
    
    /**
     * Changing anything from selector value of Control Args
     * 
     * Here, I will convert Mainly transforms name from selector
     * Suppose: some where, already available rotate()
     * In this place, I will replace it with rotateX,
     * then we have to use this
     * 
     * @param type $ultraaddons_args
     * @param type $target_peroperty_name
     * @param type $new_peroperty_name
     * @return Array Always Array
     */
    public static function replace_selector( $ultraaddons_args = [], $target_peroperty_name = false,$new_peroperty_name = false ) {
        if( ! is_array( $ultraaddons_args ) || ! $target_peroperty_name || ! $new_peroperty_name ){
            return $ultraaddons_args;
        }
        
        if( isset( $ultraaddons_args['selector'] ) && is_string( $ultraaddons_args['selector'] ) ){
            $ultraaddons_args['selector'] = str_replace( $target_peroperty_name, $new_peroperty_name, $ultraaddons_args['selector'] );
        }
        
        if( isset( $ultraaddons_args['selectors'] ) && is_array( $ultraaddons_args['selectors'] ) ){
            $temp_selectos = [];
            foreach( $ultraaddons_args['selectors'] as $selector => $ultraaddons_value ){
                $final_selector = str_replace( $target_peroperty_name, $new_peroperty_name, $selector );
                $temp_selectos[$final_selector] = $ultraaddons_value;
            }
            $ultraaddons_args['selectors'] = $temp_selectos;
        }
        return $ultraaddons_args;
    }
    
    /**
     * Here, We will convert second params of default add_control.
     * Only adding over to .elementor-widget-container
     * 
     * @param type $ultraaddons_args
     * @param type $hover_selector
     * @return type
     */
    public static function convert_hover( $ultraaddons_args = [], $hover_selector = '.elementor-widget-container' ) {
        if( ! is_array( $ultraaddons_args ) ){
            return $ultraaddons_args;
        }
        
        if( isset( $ultraaddons_args['selector'] ) && is_string( $ultraaddons_args['selector'] ) ){
            $ultraaddons_args['selector'] = str_replace( $hover_selector, $hover_selector . ':hover', $ultraaddons_args['selector'] );
        }
        //var_dump($ultraaddons_args['selectors']);
        if( isset( $ultraaddons_args['selectors'] ) && is_array( $ultraaddons_args['selectors'] ) ){
            $temp_selectos = [];
            foreach( $ultraaddons_args['selectors'] as $selector => $ultraaddons_value ){
                $final_selector = str_replace( $hover_selector, $hover_selector . ':hover', $selector );
                $temp_selectos[$final_selector] = $ultraaddons_value;
            }
            $ultraaddons_args['selectors'] = $temp_selectos;
        }
        return $ultraaddons_args;
    }
//    public function add_control( $ultraaddons_args, $control_name = false ) {
//        if( ! is_array( $ultraaddons_args ) ){
//            return;
//        }
//        
//        if( empty( $control_name ) || ! is_string( $control_name ) ){
//            return;
//        }
//        
//        $this->element->add_control( $control_name, $ultraaddons_args);
//        
//        
//    }
//    
//    public function add_hover_control( $ultraaddons_args, $control_name = false ) {
//        if( ! is_array( $ultraaddons_args ) ){
//            return;
//        }
//        
//        if( empty( $control_name ) || ! is_string( $control_name ) ){
//            return;
//        }
//        $control_name = $control_name . '_hover';
//        
//        $this->element->add_control( $control_name, $ultraaddons_args);
//    }
}