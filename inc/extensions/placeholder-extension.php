<?php
namespace UltraAddons\Extensions;

use Elementor\Controls_Manager;
use Elementor\Element_Base;

class Placeholder_Extension {
    protected $name;
    protected $extension = array();
    protected $message = "Premium Extension.";
    public function __construct( $extension = false, $message = false ){

        //$extension_name = $extension['name'];
        if( ! $extension || ! isset( $extension['name'] ) ){
            return;
        }
        $this->extension = $extension;
        $this->name = $extension['name'];
        if( ! empty( $message ) ){
            $this->message = $message;
        }

        add_action( 'elementor/element/column/section_advanced/after_section_end', [ $this, 'add_controls_section' ], 1 );
	add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'add_controls_section' ], 1 );
	add_action( 'elementor/element/common/_section_style/after_section_end', [ $this, 'add_controls_section' ], 1 );
    }
    
    public function add_controls_section( Element_Base $element) {
            $tabs =  isset( $this->extension['tab'] ) ? $this->extension['tab'] : Controls_Manager::TAB_CONTENT;
//            $tabs = Controls_Manager::TAB_CONTENT;
            $element_get_name = $element->get_name();
            if ( 'section' === $element_get_name || 'column' === $element_get_name ) {
                    $tabs = Controls_Manager::TAB_LAYOUT;
            }

            $slug = preg_replace('/(\s+)/', '-', $this->name);
            $label = $this->name . ' <sup class="ultaaddons-pro-badge">Pro</sup> '; 
            $element->start_controls_section(
                    '_ua_' . $element_get_name . '_' . $slug,
                    [
                            'label' => $label . ultraaddons_icon_markup(),
                            'tab'   => $tabs,
                    ]
            );

            $element->add_control(
                    'ua_' . $element_get_name . '_control_' . $slug,
                    [
                            'label' => __( 'Important Note', 'ultraaddons' ),
                            'type' => Controls_Manager::RAW_HTML,
                            'raw' => __( 'A very important message to show in the panel.', 'ultraaddons' ),
                            
                    ]
            );

            $element->end_controls_section();


    }
}