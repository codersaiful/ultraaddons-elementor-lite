<?php
namespace UltraAddons\Extensions;

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
                        '_ua_transform_on_off',
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
					'size' => '',
					'unit' => 'deg',
				],
				'range' => [
					'deg' => [
						'min' => -360,
						'max' => 360,
					],
				],
                                
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'transform:rotate({{SIZE}}{{UNIT}});',
				],
			];
                
                $rotateX_args = Handle_Controls::replace_selector_value( $rotate_args, 'rotate', 'rotateX' );
                $rotateY_args = Handle_Controls::replace_selector_value( $rotate_args, 'rotate', 'rotateY' );

                
                
                $element->start_controls_tabs( '_transition_tabs',
                            [
                                    'condition' => [
                                            '_ua_transform_on_off' => 'yes',
                                    ],
                            ]
                        );

		$element->start_controls_tab(
			'_ua_transform_tab_normal',
			[
				'label' => __( 'Normal', 'ultraaddons' ),
			]
		);
                
                $element->add_control(
			'_ua_transform_rotation_heading',
			[
				'label' => __( 'Rotation', 'ultraaddons' ),
                                'type' => Controls_Manager::HEADING,
                                'separator'   => 'after', 
			]
		);
                $element->add_control( '_ua_transform_rotate', $rotate_args );
                $element->add_control( '_ua_transform_rotateX', $rotateX_args );
                $element->add_control( '_ua_transform_rotateY', $rotateY_args );

                $element->end_controls_tab(); //End of Normal Tab
                
                
                /**
                 * Hover Tab
                 */
                $element->start_controls_tab(
			'_ua_transform_tab_hover',
			[
				'label' => __( 'Hover', 'ultraaddons' ),
			]
		);
                
                $element->add_control( '_ua_transform_rotate_hover', Handle_Controls::convert_hover( $rotate_args ) );
                $element->add_control( '_ua_transform_rotateX_hover', Handle_Controls::convert_hover( $rotateX_args ) );
                $element->add_control( '_ua_transform_rotateY_hover', Handle_Controls::convert_hover( $rotateY_args ) );
                
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
                $_ua_transform_on_off = $settings['_ua_transform_on_off'];

                if ( empty( $_ua_transform_on_off ) ) {
                    return false;
                }
                
                $data_transform = [
                    '_ua_transform_on_off' => $_ua_transform_on_off,
                    'transforms' => [
                        'rotate'      => $settings['_ua_transform_rotate'],
                        'rotateX'      => $settings['_ua_transform_rotateX'],
                        'rotateY'      => $settings['_ua_transform_rotateY'],
                    ],
                    
                ];
                
                /**
                 * Convert data_transform transform
                 */
                foreach( $data_transform['transforms'] as $transform_key => $transform_value ){
                    $target_setngs_key = $transform_key . '_hover';
                    $data_transform['transforms_hover'][$transform_key] = isset( $settings[$target_setngs_key] ) ? $settings[$target_setngs_key] : false;
                }

                


                /**
                 * Adding Class where already Transform Selected
                 */
                $element->add_render_attribute(
                        '_wrapper',
                        [
                                'data-transform' => json_encode( $data_transform ),
                                'class' => 'ua-transformed'
                        ]
                );
	}
}

//No need to call, we have called automatically to initit method from extenstion manager file.
//Transform::init();
