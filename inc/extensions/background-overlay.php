<?php
namespace UltraAddons\Extension;

use UltraAddons\Controls\Handle_Controls;
use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Group_Control_Background;

defined('ABSPATH') || die();

class Background_Overlay {

	public static function init() {
		add_action( 'elementor/element/column/section_advanced/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );
		add_action( 'elementor/element/common/_section_style/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );

                add_action( 'elementor/frontend/before_render', [ __CLASS__, 'before_section_render' ], 1 );
	}

	public static function add_controls_section( Element_Base $element) {
		$tabs = Controls_Manager::TAB_STYLE;
//                $tabs = Controls_Manager::TAB_CONTENT;
                
		if ( 'section' === $element->get_name() || 'column' === $element->get_name() ) {
			$tabs = Controls_Manager::TAB_LAYOUT;
		}

		
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
                        ]
                );
                
                $element->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => '_ua_container_background',
				'types' => [ 'classic', 'gradient' ],
//				'selector' => '{{WRAPPER}} div.elementor-element',
				'selector' => '{{WRAPPER}} .ua-background-overlay',
                                'separator' => 'before',
				'fields_options' => [
					'background' => [
						'frontend_available' => true,
					],
					'color' => [
						'dynamic' => [],
					],
					'color_b' => [
						'dynamic' => [],
					],
				],
                                'condition' => [
                                    '_ua_overlay_bg_on_off' => 'yes',
                                ],
			]
		);
            
                

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
                $_ua_overlay_bg_on_off = $settings['_ua_overlay_bg_on_off'];

                if ( empty( $_ua_overlay_bg_on_off ) ) {
                    return false;
                }

                /**
                 * Adding Class where already Transform Selected
                 */
                $element->add_render_attribute(
                        '_wrapper',
                        [
                                //'data-transform' => json_encode( $data_transform ),
                                'class' => 'ua-background-overlay'
                        ]
                );
	}
}

Background_Overlay::init();
