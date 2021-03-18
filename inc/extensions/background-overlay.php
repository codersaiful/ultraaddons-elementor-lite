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
//		add_action( 'elementor/element/section/section_advanced/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );
		add_action( 'elementor/element/common/_section_style/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );

                add_action( 'elementor/frontend/after_render', [ __CLASS__, 'before_section_render' ], 1 );
//                add_action( 'elementor/frontend/before_render', [ __CLASS__, 'before_section_render' ], 1 );
                
//                $element_type = 'widget';
//                $render_method = 'render_' . $element_type;
//
//                if( ! method_exists( __CLASS__, $render_method ) ){
//                    return;
//                }
//                add_action( "elementor/frontend/{$element_type}/after_render", [__CLASS__,$render_method] );
	}

        public static function render_widget( Element_Base $element ){
            $settings = $element->get_settings_for_display();
                $_ua_overlay_bg_on_off = $settings['_ua_overlay_bg_on_off'];

                if ( empty( $_ua_overlay_bg_on_off ) ) {
                    return false;
                }
                echo '<h2 class="saiful_islam_render_effect">HHHHHHHHHHHHHHHHHHHHELLLLLLLOOOOOOOOOOOOO</h2>';
        }

        public static function add_controls_section( Element_Base $element ) {
            
		$tabs = Controls_Manager::TAB_STYLE;
//                $tabs = Controls_Manager::TAB_CONTENT;
                $selector = "{{WRAPPER}} .elementor-widget-container>*";
		if ( 'column' === $element->get_name() ) { //'section' === $element->get_name() || 
			$tabs = Controls_Manager::TAB_LAYOUT;
                        $selector = "{{WRAPPER}}";
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
                        ]
                );
                
                $element->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => '_ua_container_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => $selector,
                                'separator' => 'before',
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
                return;
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
//No need to call, we have called automatically to initit method from extenstion manager file.
//Background_Overlay::init();
