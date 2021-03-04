<?php
namespace UltraAddons\Extension;

use UltraAddons\Controls\Handle_Controls;
use Elementor\Controls_Manager;
use Elementor\Element_Base;

defined('ABSPATH') || die();

class Transform {

	public static function init() {
		add_action( 'elementor/element/column/section_advanced/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );
		add_action( 'elementor/element/common/_section_style/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );

                add_action( 'elementor/frontend/before_render', [ __CLASS__, 'before_section_render' ], 1 );
	}

	public static function add_controls_section( Element_Base $element) {
		$tabs = Controls_Manager::TAB_STYLE;

		if ( 'section' === $element->get_name() || 'column' === $element->get_name() ) {
			$tabs = Controls_Manager::TAB_LAYOUT;
		}

		
		$element->start_controls_section(
			'_ua_transform',
			[
				'label' => __( 'Transform', 'ultraaddons' ) . ultraaddons_icon_markup(),
				'tab'   => $tabs,
			]
		);
                
                $element->add_control(
                        'on_off',
                        [
                                'label' => __( 'Transform', 'ultraaddons' ),
                                'description' => __( 'Cart Item will stay exanded always.', 'ultraaddons' ),
                                'type' => Controls_Manager::SWITCHER,
                                'label_on' => __( 'On', 'ultraaddons' ),
                                'label_off' => __( 'Off', 'ultraaddons' ),
                                'return_value' => 'yes',
                                'default' => '',
                                'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'transition: 1s all;',
				],
                        ]
                );
                
                $rotate_args = [
				'label' => __( 'Rotation', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
					'unit' => 'deg',
				],
				'range' => [
					'deg' => [
						'min' => 0,
						'max' => 360,
					],
				],
                                
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'transform:rotate({{SIZE}}{{UNIT}});',
				],
			];
                
                
                $element->start_controls_tabs( 'transition_tabs',
                            [
                                    'condition' => [
                                            'on_off' => 'yes',
                                    ],
                            ]
                        );

		$element->start_controls_tab(
			'tab_normal',
			[
				'label' => __( 'Normal', 'ultraaddons' ),
			]
		);
                
                $element->add_control( 'rotate', $rotate_args );

                $element->end_controls_tab(); //End of Normal Tab
                
                
                /**
                 * Hover Tab
                 */
                $element->start_controls_tab(
			'tab_hover',
			[
				'label' => __( 'Hover', 'ultraaddons' ),
			]
		);
                
//                $element->add_control( 'rotate_hover', $rotate_args );
                $element->add_control( 'rotate_hover', Handle_Controls::convert_hover( $rotate_args ) );
                
                $element->end_controls_tab(); //End of Hover Tab
                
                
                
                
                $element->end_controls_tabs(); //End of Tab System
		

		$element->end_controls_section();
                
                
	}


        /**
         * Adding data attribute on selected Elementor or Section and 
         * Do Transition.
         * For now, I will try only Rotation
         * Using CSS
         * 
         * @param Element_Base $element
         */
	public static function before_section_render( Element_Base $element ) {
		$settings = $element->get_settings_for_display();
                $on_off = $settings['on_off'];
                $data_transform = [
                    'on_off' => $on_off,
                    'transforms' => [
                        'rotate'      => $settings['rotate'],
                    ],
                ];
		if ( $on_off && ! empty( $on_off ) ) {
			$element->add_render_attribute(
				'_wrapper',
				[
					'data-transform' => json_encode( $data_transform ),
					'class' => 'ua-transformed'
				]
			);
		}

	}
}

Transform::init();
