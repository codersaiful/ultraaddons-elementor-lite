<?php
namespace UltraAddons\Extension;

use UltraAddons\Controls\Handle_Controls;
use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Css_Filter;

defined('ABSPATH') || die();

class Background_Overlay {

	public static function init() {
                add_action( 'elementor/element/common/_section_style/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );
	}



        public static function add_controls_section( Element_Base $element ) {
            
		$tabs = Controls_Manager::TAB_STYLE;
//                $tabs = Controls_Manager::TAB_CONTENT;
                $selector = "{{WRAPPER}}.ua-background-overlay-yes .elementor-widget-container:before";
		if ( 'column' === $element->get_name() ) { //'section' === $element->get_name() || 
			$tabs = Controls_Manager::TAB_LAYOUT;
                        $selector = "{{WRAPPER}}.ua-background-overlay-yes:before";
		}

                $element_type = $element->get_type();
		           
		$element->start_controls_section(
			'_ua_background_overlay',
			[
				'label' => __( 'Background Overlay', 'ultraaddons' ) . ultraaddons_icon_markup(),
				'tab'   => $tabs,
			]
		);
                
                $element->add_control(
                        '_ua_overlay_bg_on_off',
                        [
                                'label' => __( 'Overlay Background', 'ultraaddons' ),
                                'description' => __( 'Description will come here.', 'ultraaddons' ),
                                'type' => Controls_Manager::SWITCHER,
                                'label_on' => __( 'On', 'ultraaddons' ),
                                'label_off' => __( 'Off', 'ultraaddons' ),
                                'return_value' => 'yes',
                                'default' => '',
                                'prefix_class' => 'ua-background-overlay-',
                        ]
                );
                
                $element->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => '_ua_overlay_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => $selector,
                                'separator' => 'before',
                                'condition' => [
                                    '_ua_overlay_bg_on_off' => 'yes',
                                ],
			]
		);
                
                $element->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => '_ua_css_filters',
				'selector' => $selector,//'{{WRAPPER}} > .elementor-element-populated >  .elementor-background-overlay',
                                'condition' => [
                                    '_ua_overlay_bg_on_off' => 'yes',
                                ],
			]
		);
                
                $element->add_control(
			'_ua_background_overlay_opacity',
			[
				'label' => __( 'Opacity', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'max' => 1,
						'step' => 0.01,
					],
				],
				'selectors' => [
					$selector => 'opacity: {{SIZE}};',
//					'{{WRAPPER}} > .elementor-element-populated >  .elementor-background-overlay' => 'opacity: {{SIZE}};',
				],
                                'condition' => [
                                    '_ua_overlay_bg_on_off' => 'yes',
                                ],
//				'condition' => [
//					'background_overlay_background' => [ 'classic', 'gradient' ],
//				],
			]
		);

		$element->add_control(
			'_ua_overlay_blend_mode',
			[
				'label' => __( 'Blend Mode', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Normal', 'elementor' ),
					'multiply' => 'Multiply',
					'screen' => 'Screen',
					'overlay' => 'Overlay',
					'darken' => 'Darken',
					'lighten' => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'saturation' => 'Saturation',
					'color' => 'Color',
					'luminosity' => 'Luminosity',
				],
				'selectors' => [
					$selector => 'mix-blend-mode: {{VALUE}}',
//					'{{WRAPPER}} > .elementor-element-populated > .elementor-background-overlay' => 'mix-blend-mode: {{VALUE}}',
				],
                                'condition' => [
                                    '_ua_overlay_bg_on_off' => 'yes',
                                ],
			]
		);

                
            
                $element->add_responsive_control(
                    '_ua_overlay_radius',
                    [
                        'label'      => __( 'Radius', 'ultraaddons' ),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%', 'em' ],
                        'selectors'  => [
                            $selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                        'condition' => [
                                '_ua_overlay_bg_on_off' => 'yes',
                            ],
                    ]
                );
                
                
                /**
                 * 

                $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background_overlay',
				'selector' => '{{WRAPPER}} > .elementor-element-populated >  .elementor-background-overlay',
			]
		);

		$this->add_control(
			'background_overlay_opacity',
			[
				'label' => __( 'Opacity', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => .5,
				],
				'range' => [
					'px' => [
						'max' => 1,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} > .elementor-element-populated >  .elementor-background-overlay' => 'opacity: {{SIZE}};',
				],
				'condition' => [
					'background_overlay_background' => [ 'classic', 'gradient' ],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'css_filters',
				'selector' => '{{WRAPPER}} > .elementor-element-populated >  .elementor-background-overlay',
			]
		);

		$this->add_control(
			'overlay_blend_mode',
			[
				'label' => __( 'Blend Mode', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Normal', 'elementor' ),
					'multiply' => 'Multiply',
					'screen' => 'Screen',
					'overlay' => 'Overlay',
					'darken' => 'Darken',
					'lighten' => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'saturation' => 'Saturation',
					'color' => 'Color',
					'luminosity' => 'Luminosity',
				],
				'selectors' => [
					'{{WRAPPER}} > .elementor-element-populated > .elementor-background-overlay' => 'mix-blend-mode: {{VALUE}}',
				],
			]
		);



                 * 
                 */

		$element->end_controls_section();
                
                
	}

}
//No need to call, we have called automatically to initit method from extenstion manager file.
//Background_Overlay::init();
