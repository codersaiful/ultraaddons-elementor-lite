<?php
namespace UltraAddons\Extensions;

use Elementor\Controls_Manager;
use Elementor\Element_Base;

class Placeholder_Extension {
    protected static $name;
    protected static $message = "Premium Extension.";
    public static function init( $name = false, $message = false ){
        if( ! $name ){
            return;
        }
        self::$name = $name;
        if( ! empty( $message ) ){
            self::$message = $message;
        }

        add_action( 'elementor/element/column/section_advanced/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );
	add_action( 'elementor/element/section/section_advanced/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );
	add_action( 'elementor/element/common/_section_style/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );
    }
    
    public static function add_controls_section( Element_Base $element) {
            $tabs = Controls_Manager::TAB_CONTENT;

            if ( 'section' === $element->get_name() || 'column' === $element->get_name() ) {
                    $tabs = Controls_Manager::TAB_LAYOUT;
            }

            $slug = preg_replace('/(\s+)/', '-', self::$name);
            
            $element->start_controls_section(
                    '_ua_section_' . $slug,
                    [
                            'label' => self::$name . ultraaddons_icon_markup(),
                            'tab'   => $tabs,
                    ]
            );

            $element->add_control(
                    'ua_ex_control_' . $slug,
                    [
                            'label' => __( 'Important Note', 'ultraaddons' ),
                            'type' => Controls_Manager::RAW_HTML,
                            'raw' => __( 'A very important message to show in the panel.', 'ultraaddons' ),
                            
                    ]
            );

            $element->end_controls_section();


    }
}