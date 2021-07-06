<?php
namespace UltraAddons\Extensions;

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Group_Control_Background;

defined('ABSPATH') || die();

class Gradient_Text {

	public static function init() {
		add_action( 'elementor/element/column/section_advanced/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );
		add_action( 'elementor/element/common/_section_style/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );

	}

        private static function get_selector( $hover = false ){

            $full_wrapper = "{{WRAPPER}}.ua-gradient-text-switch-yes .elementor-widget-container";

            $selector = ''; 
            $selector .= "{$full_wrapper} .elementor-heading-title{$hover},";
            $selector .= "{$full_wrapper} .elementor-widget-text-editor{$hover},";
            $selector .= "{$full_wrapper} p{$hover},";
            $selector .= "{$full_wrapper} h1{$hover},";
            $selector .= "{$full_wrapper} h2{$hover},";
            $selector .= "{$full_wrapper} h3{$hover},";
            $selector .= "{$full_wrapper} h4{$hover},";
            $selector .= "{$full_wrapper} h5{$hover},";
            $selector .= "{$full_wrapper} h6{$hover},";
            $selector .= "{$full_wrapper} span{$hover},";
            $selector .= "{$full_wrapper} a{$hover},";
            $selector .= "{$full_wrapper} strong{$hover},";
            $selector .= "{$full_wrapper} b{$hover},";
            
            
            $selector .= "{$full_wrapper} .gradient-text{$hover}";

            return $selector;
        }

        public static function add_controls_section( Element_Base $element) {
		$tabs = Controls_Manager::TAB_STYLE;
                
                
//                $settings           = $element->get_controls_settings();
//                $settingss           = $element->get_active_settings();
                
		if ( 'section' === $element->get_name() || 'column' === $element->get_name() ) {
			$tabs = Controls_Manager::TAB_LAYOUT;
		}

		$element->start_controls_section(
			'_ua_section_gradient_text',
			[
				'label' => __( 'Gradient Text', 'ultraaddons' ) . ultraaddons_icon_markup(),
				'tab'   => $tabs,
			]
		);

                
                $element->add_control(
                        '_ua_gradient_text_on_off',
                        [
                                'label' => __( 'Switch', 'ultraaddons' ),
                                'description' => __( 'Gradient Text enable or disable option.', 'ultraaddons' ),
                                'type' => Controls_Manager::SWITCHER,
                                'label_on' => __( 'On', 'ultraaddons' ),
                                'label_off' => __( 'Off', 'ultraaddons' ),
                                'return_value' => 'yes',
                                'default' => '',
                                'prefix_class' => 'ua-gradient-text-switch-',
                        ]
                );
                
                
                self::get_gradient_tabs( $element );

                /**
                 * We will try to add this feature 
                 * later
                 * 
                 * @todo restriction gradient, I will try to add later.
                 */
             

		$element->end_controls_section();
                
                
	}
        
        protected static function get_gradient_tabs( Element_Base $element ){
            
                $selector = self::get_selector();
                $selector_hover = self::get_selector(':hover');
            
                $element->start_controls_tabs( '_ua_gradient_tabs',[
                    'condition' => [
                                    '_ua_gradient_text_on_off' => 'yes',
                                ]
                ]);
                
                $element->start_controls_tab(
                        '_ua_gradient_normal',
                        [
                                'label' => __( 'Normal', 'ultraaddons' ),
                        ]
                );
                
                $element->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => '_ua_gradient_text_bg',
				'types' => [ 'classic', 'gradient' ],
				'selector' => $selector,
                                'separator' => 'before',
			]
		);
                $element->end_controls_tab();
                
                $element->start_controls_tab(
                        '_ua_gradient_hover',
                        [
                                'label' => __( 'Normal', 'ultraaddons' ),
                        ]
                );

                $element->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => '_ua_gradient_text_hover',
				'types' => [ 'classic', 'gradient' ],
				'selector' => $selector_hover,
                                'separator' => 'before',
                                
			]
		);
                
                $element->end_controls_tab();
                
                $element->end_controls_tabs();
        }

}

//No need to call, we have called automatically to initit method from extenstion manager file.
//Gradient_Text::init();
