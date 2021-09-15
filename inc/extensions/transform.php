<?php
namespace UltraAddons\Extensions;

use UltraAddons\Controls\Handle_Controls;
use Elementor\Controls_Manager;
use Elementor\Element_Base;

defined('ABSPATH') || die();

class Transform {

	public static function init() {
		add_action( 'elementor/element/common/_section_style/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );
                
                /**
                 * CSS Transform /
                 * Inline Script
                 */
                add_action( 'elementor/frontend/after_enqueue_styles', [ __CLASS__, 'enqueue_inline_scripts' ] );
                add_action( 'elementor/preview/enqueue_scripts', [ __CLASS__, 'enqueue_inline_scripts' ] );
	}

        public static function enqueue_inline_scripts() {
            $css = '';
                    $common_css = ULTRA_ADDONS_DIR . 'assets/css/common.min.css';


            if ( is_readable( $common_css ) ) {
                $css .= file_get_contents( $common_css );
            };
            wp_add_inline_style(
                'elementor-frontend',
                $css
            );

        }
        
        
	public static function add_controls_section( Element_Base $element) {
		$tabs = Controls_Manager::TAB_STYLE;

		if ( 'section' === $element->get_name() || 'column' === $element->get_name() ) {
			$tabs = Controls_Manager::TAB_LAYOUT;
		}

		
		$element->start_controls_section(
			'_ua_section_css_transform',
			[
					'label' => __( 'CSS Transform', 'ultraaddons' ) . ultraaddons_icon_markup(),
					'tab'   => $tabs,
			]
		);

		$element->add_control(
			'ua_transform_fx',
			[
				'label' => __( 'Enable', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'prefix_class' => 'ua-css-transform-',
			]
		);
                
		$element->start_controls_tabs(
			'_tabs_ua_transform',
			[
				'condition' => [
					'ua_transform_fx' => 'yes',
				],
			]
		);

		$element->start_controls_tab(
			'_tabs_ua_transform_normal',
			[
				'label' => __( 'Normal', 'ultraaddons' ),
				'condition' => [
					'ua_transform_fx' => 'yes',
				],
			]
		);

		$element->add_control(
			'ua_transform_fx_translate_toggle',
			[
				'label' => __( 'Translate', 'ultraaddons' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',
				'condition' => [
					'ua_transform_fx' => 'yes',
				],
			]
		);

		$element->start_popover();

		$element->add_responsive_control(
			'ua_transform_fx_translate_x',
			[
				'label' => __( 'Translate X', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					]
				],
				'condition' => [
					'ua_transform_fx_translate_toggle' => 'yes',
					'ua_transform_fx' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ua-tfx-translate-x: {{SIZE}}px;'
				],
			]
		);

		$element->add_responsive_control(
			'ua_transform_fx_translate_y',
			[
				'label' => __( 'Translate Y', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					]
				],
				'condition' => [
					'ua_transform_fx_translate_toggle' => 'yes',
					'ua_transform_fx' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ua-tfx-translate-y: {{SIZE}}px;'
				],
			]
		);

		$element->end_popover();

		$element->add_control(
			'ua_transform_fx_rotate_toggle',
			[
				'label' => __( 'Rotate', 'ultraaddons' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'condition' => [
					'ua_transform_fx' => 'yes',
				],
			]
		);

		$element->start_popover();

		$element->add_control(
			'ua_transform_fx_rotate_mode',
			[
				'label' => __( 'Mode', 'ultraaddons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'compact' => [
						'title' => __( 'Compact', 'ultraaddons' ),
						'icon' => 'eicon-plus-circle',
					],
					'loose' => [
						'title' => __( 'Loose', 'ultraaddons' ),
						'icon' => 'eicon-minus-circle',
					],
				],
				'default' => 'loose',
				'toggle' => false
			]
		);

		$element->add_control(
			'ua_transform_fx_rotate_hr',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$element->add_responsive_control(
			'ua_transform_fx_rotate_x',
			[
				'label' => __( 'Rotate X', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -180,
						'max' => 180,
					]
				],
				'condition' => [
					'ua_transform_fx_rotate_toggle' => 'yes',
					'ua_transform_fx' => 'yes',
					'ua_transform_fx_rotate_mode' => 'loose'
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ua-tfx-rotate-x: {{SIZE}}deg;'
				],
			]
		);

		$element->add_responsive_control(
			'ua_transform_fx_rotate_y',
			[
				'label' => __( 'Rotate Y', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -180,
						'max' => 180,
					]
				],
				'condition' => [
					'ua_transform_fx_rotate_toggle' => 'yes',
					'ua_transform_fx' => 'yes',
					'ua_transform_fx_rotate_mode' => 'loose'
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ua-tfx-rotate-y: {{SIZE}}deg;'
				],
			]
		);

		$element->add_responsive_control(
			'ua_transform_fx_rotate_z',
			[
				'label' => __( 'Rotate (Z)', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -180,
						'max' => 180,
					]
				],
				'condition' => [
					'ua_transform_fx_rotate_toggle' => 'yes',
					'ua_transform_fx' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ua-tfx-rotate-z: {{SIZE}}deg;'
				],
			]
		);

		$element->end_popover();

		$element->add_control(
			'ua_transform_fx_scale_toggle',
			[
				'label' => __( 'Scale', 'ultraaddons' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',
				'condition' => [
					'ua_transform_fx' => 'yes',
				],
			]
		);

		$element->start_popover();

		$element->add_control(
			'ua_transform_fx_scale_mode',
			[
				'label' => __( 'Mode', 'ultraaddons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'compact' => [
						'title' => __( 'Compact', 'ultraaddons' ),
						'icon' => 'eicon-plus-circle',
					],
					'loose' => [
						'title' => __( 'Loose', 'ultraaddons' ),
						'icon' => 'eicon-minus-circle',
					],
				],
				'default' => 'loose',
				'toggle' => false
			]
		);

		$element->add_control(
			'ua_transform_fx_scale_hr',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$element->add_responsive_control(
			'ua_transform_fx_scale_x',
			[
				'label' => __( 'Scale (X)', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'default' => [
					'size' => 1
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5,
						'step' => .1
					]
				],
				'condition' => [
					'ua_transform_fx_scale_toggle' => 'yes',
					'ua_transform_fx' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ua-tfx-scale-x: {{SIZE}}; --ua-tfx-scale-y: {{SIZE}};'
				],
			]
		);

		$element->add_responsive_control(
			'ua_transform_fx_scale_y',
			[
				'label' => __( 'Scale Y', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'default' => [
					'size' => 1
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5,
						'step' => .1
					]
				],
				'condition' => [
					'ua_transform_fx_scale_toggle' => 'yes',
					'ua_transform_fx' => 'yes',
					'ua_transform_fx_scale_mode' => 'loose',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ua-tfx-scale-y: {{SIZE}};'
				],
			]
		);

		$element->end_popover();

		$element->add_control(
			'ua_transform_fx_skew_toggle',
			[
				'label' => __( 'Skew', 'ultraaddons' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',
				'condition' => [
					'ua_transform_fx' => 'yes',
				],
			]
		);

		$element->start_popover();

		$element->add_responsive_control(
			'ua_transform_fx_skew_x',
			[
				'label' => __( 'Skew X', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['deg'],
				'range' => [
					'px' => [
						'min' => -180,
						'max' => 180,
					]
				],
				'condition' => [
					'ua_transform_fx_skew_toggle' => 'yes',
					'ua_transform_fx' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ua-tfx-skew-x: {{SIZE}}deg;'
				],
			]
		);

		$element->add_responsive_control(
			'ua_transform_fx_skew_y',
			[
				'label' => __( 'Skew Y', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['deg'],
				'range' => [
					'px' => [
						'min' => -180,
						'max' => 180,
					]
				],
				'condition' => [
					'ua_transform_fx_skew_toggle' => 'yes',
					'ua_transform_fx' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ua-tfx-skew-y: {{SIZE}}deg;'
				],
			]
		);

		$element->end_popover();

		$element->end_controls_tab();

		$element->start_controls_tab(
            '_tabs_ua_transform_hover',
            [
				'label' => __( 'Hover', 'ultraaddons' ),
				'condition' => [
					'ua_transform_fx' => 'yes',
				],
            ]
		);

		$element->add_control(
			'ua_transform_fx_translate_toggle_hover',
			[
				'label' => __( 'Translate', 'ultraaddons' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',
				'condition' => [
					'ua_transform_fx' => 'yes',
				],
			]
		);

		$element->start_popover();

		$element->add_responsive_control(
			'ua_transform_fx_translate_x_hover',
			[
				'label' => __( 'Translate X', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					]
				],
				'condition' => [
					'ua_transform_fx_translate_toggle_hover' => 'yes',
					'ua_transform_fx' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ua-tfx-translate-x-hover: {{SIZE}}px;'
				],
			]
		);

		$element->add_responsive_control(
			'ua_transform_fx_translate_y_hover',
			[
				'label' => __( 'Translate Y', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					]
				],
				'condition' => [
					'ua_transform_fx_translate_toggle_hover' => 'yes',
					'ua_transform_fx' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ua-tfx-translate-y-hover: {{SIZE}}px;'
				],
			]
		);

		$element->end_popover();

		$element->add_control(
			'ua_transform_fx_rotate_toggle_hover',
			[
				'label' => __( 'Rotate', 'ultraaddons' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'condition' => [
					'ua_transform_fx' => 'yes',
				],
			]
		);

		$element->start_popover();

		$element->add_control(
			'ua_transform_fx_rotate_mode_hover',
			[
				'label' => __( 'Mode', 'ultraaddons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'compact' => [
						'title' => __( 'Compact', 'ultraaddons' ),
						'icon' => 'eicon-plus-circle',
					],
					'loose' => [
						'title' => __( 'Loose', 'ultraaddons' ),
						'icon' => 'eicon-minus-circle',
					],
				],
				'default' => 'loose',
				'toggle' => false
			]
		);

		$element->add_control(
			'ua_transform_fx_rotate_hr_hover',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$element->add_responsive_control(
			'ua_transform_fx_rotate_x_hover',
			[
				'label' => __( 'Rotate X', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -180,
						'max' => 180,
					]
				],
				'condition' => [
					'ua_transform_fx_rotate_toggle_hover' => 'yes',
					'ua_transform_fx' => 'yes',
					'ua_transform_fx_rotate_mode_hover' => 'loose'
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ua-tfx-rotate-x-hover: {{SIZE}}deg;'
				],
			]
		);

		$element->add_responsive_control(
			'ua_transform_fx_rotate_y_hover',
			[
				'label' => __( 'Rotate Y', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -180,
						'max' => 180,
					]
				],
				'condition' => [
					'ua_transform_fx_rotate_toggle_hover' => 'yes',
					'ua_transform_fx' => 'yes',
					'ua_transform_fx_rotate_mode_hover' => 'loose'
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ua-tfx-rotate-y-hover: {{SIZE}}deg;'
				],
			]
		);

		$element->add_responsive_control(
			'ua_transform_fx_rotate_z_hover',
			[
				'label' => __( 'Rotate (Z)', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -180,
						'max' => 180,
					]
				],
				'condition' => [
					'ua_transform_fx_rotate_toggle_hover' => 'yes',
					'ua_transform_fx' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ua-tfx-rotate-z-hover: {{SIZE}}deg;'
				],
			]
		);

		$element->end_popover();

		$element->add_control(
			'ua_transform_fx_scale_toggle_hover',
			[
				'label' => __( 'Scale', 'ultraaddons' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',
				'condition' => [
					'ua_transform_fx' => 'yes',
				],
			]
		);

		$element->start_popover();

		$element->add_control(
			'ua_transform_fx_scale_mode_hover',
			[
				'label' => __( 'Mode', 'ultraaddons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'compact' => [
						'title' => __( 'Compact', 'ultraaddons' ),
						'icon' => 'eicon-plus-circle',
					],
					'loose' => [
						'title' => __( 'Loose', 'ultraaddons' ),
						'icon' => 'eicon-minus-circle',
					],
				],
				'default' => 'loose',
				'toggle' => false
			]
		);

		$element->add_control(
			'ua_transform_fx_scale_hr_hover',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$element->add_responsive_control(
			'ua_transform_fx_scale_x_hover',
			[
				'label' => __( 'Scale (X)', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'default' => [
					'size' => 1
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5,
						'step' => .1
					]
				],
				'condition' => [
					'ua_transform_fx_scale_toggle_hover' => 'yes',
					'ua_transform_fx' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ua-tfx-scale-x-hover: {{SIZE}}; --ua-tfx-scale-y-hover: {{SIZE}};'
				],
			]
		);

		$element->add_responsive_control(
			'ua_transform_fx_scale_y_hover',
			[
				'label' => __( 'Scale Y', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'default' => [
					'size' => 1
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5,
						'step' => .1
					]
				],
				'condition' => [
					'ua_transform_fx_scale_toggle_hover' => 'yes',
					'ua_transform_fx' => 'yes',
					'ua_transform_fx_scale_mode_hover' => 'loose',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ua-tfx-scale-y-hover: {{SIZE}};'
				],
			]
		);

		$element->end_popover();

		$element->add_control(
			'ua_transform_fx_skew_toggle_hover',
			[
				'label' => __( 'Skew', 'ultraaddons' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',
				'condition' => [
					'ua_transform_fx' => 'yes',
				],
			]
		);

		$element->start_popover();

		$element->add_responsive_control(
			'ua_transform_fx_skew_x_hover',
			[
				'label' => __( 'Skew X', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['deg'],
				'range' => [
					'px' => [
						'min' => -180,
						'max' => 180,
					]
				],
				'condition' => [
					'ua_transform_fx_skew_toggle_hover' => 'yes',
					'ua_transform_fx' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ua-tfx-skew-x-hover: {{SIZE}}deg;'
				],
			]
		);

		$element->add_responsive_control(
			'ua_transform_fx_skew_y_hover',
			[
				'label' => __( 'Skew Y', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['deg'],
				'range' => [
					'px' => [
						'min' => -180,
						'max' => 180,
					]
				],
				'condition' => [
					'ua_transform_fx_skew_toggle_hover' => 'yes',
					'ua_transform_fx' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ua-tfx-skew-y-hover: {{SIZE}}deg;'
				],
			]
		);

		$element->end_popover();

		$element->add_control(
			'ua_transform_fx_transition_duration',
			[
				'label' => __( 'Transition Duration', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'separator' => 'before',
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 3,
						'step' => .1,
					]
				],
				'condition' => [
					'ua_transform_fx' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ua-tfx-transition-duration: {{SIZE}}s;'
				],
			]
		);

		$element->end_controls_tab();

		$element->end_controls_tabs();
		

		$element->end_controls_section();
                
                
	}


}

//No need to call, we have called automatically to initit method from extenstion manager file.
//Transform::init();
