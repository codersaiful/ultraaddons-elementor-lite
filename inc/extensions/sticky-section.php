<?php
namespace UltraAddons\Extensions;


use Elementor\Controls_Manager;
use Elementor\Element_Base;


defined('ABSPATH') || die();

/**
 * Sticky Secttion 
 * 
 * ************************
 * EXTENSION
 * ************************
 * 
 * will be available for only section actually
 * 
 * we can add this feature for any element at later
 * 
 * @credit https://www.w3schools.com/howto/howto_css_sticky_element.asp
 * 
 * @package UltraAddons
 * @since 1.1.0.7
 * @author Saiful islam <codersaiful@gmail.com>
 */
class Sticky_Section{

    /**
     * Initialize Sticky section
     *
     * @return void
     */
    public static function init(){
        add_action( 'elementor/element/section/section_advanced/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );
        //add_action( 'elementor/element/common/_section_style/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );
    }

    /**
     * Adding control for Sticky Section Extension
     *
     * @param Element_Base $element
     * @return void
     */
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
                        '{{WRAPPER}}.ua-sticky-yes' => 'position: sticky;width: 100%;',
                    ],
            ]
        );
        $element->add_responsive_control(
            '_ua_sticky_margin_top',
            [
                'label' => esc_html__('Margin/Space Top', 'ultraaddons'),
                'description' => __( 'Margin top / White space from top.', 'ultraaddons' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
                    'unit' => 'px',
                ],
                'size_units' => ['px','%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.ua-sticky-yes' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    '_ua_sticky_section_switch' => 'yes'
                ]
            ]
        );

        $element->add_control(
            '_ua_sticky_z_index',
            [
                'label' => esc_html__('Z-Index', 'ultraaddons'),
                'description' => __( 'Indicate Layer postion for your Sticky section.', 'ultraaddons' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1000,
                    //'unit' => 'px',
                ],
                //'size_units' => ['px','%'],
                'range' => [
                    'px' => [
                        'min' => 1000,
                        'max' => 1100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.ua-sticky-yes' => 'z-index: {{SIZE}};',
                ],
                'condition' => [
                    '_ua_sticky_section_switch' => 'yes'
                ]
            ]
        );

        $element->end_controls_section();
        
    }
}