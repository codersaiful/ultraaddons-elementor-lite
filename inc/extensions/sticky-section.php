<?php
namespace UltraAddons\Extensions;


use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Group_Control_Background;


defined('ABSPATH') || die();

class Sticky_Section{

    public static function init(){
        add_action( 'elementor/element/section/section_advanced/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );
        //add_action( 'elementor/element/common/_section_style/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );
    }

    public static function add_controls_section( Element_Base $element ){

        //Only for section, this feature will activating
        if ( 'section' !== $element->get_name() ) return;
        
        $tab = Controls_Manager::TAB_LAYOUT;

        $element->start_controls_section( 
            '_ua_sticky_section',
            [
                'label' => 'Sticky Section'. ultraaddons_icon_markup(),
                'tab' => $tab
            ] 
        );

        $element->add_control(
            '_ua_sticky_section_switch',
            [
                    'label' => __( 'Switch', 'ultraaddons' ),
                    'description' => __( 'Sticky Section for any section.', 'ultraaddons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'On', 'ultraaddons' ),
                    'label_off' => __( 'Off', 'ultraaddons' ),
                    'return_value' => 'yes',
                    'default' => '',
                    'prefix_class' => 'ua-sticky-',
                    'selectors' => [
                        '{{WRAPPER}}.ua-sticky-yes' => 'opacity: 0.33;',
    //					'{{WRAPPER}} > .elementor-element-populated >  .elementor-background-overlay' => 'opacity: {{SIZE}};',
                    ],
            ]
        );


        $element->end_controls_section();
        
    }
}