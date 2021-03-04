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