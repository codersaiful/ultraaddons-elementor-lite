<?php
namespace UltraAddons\Extensions;

use Elementor\Controls_Manager;

defined('ABSPATH') || die();

class Custom_CSS {

    public static function elementor() {
		return \Elementor\Plugin::$instance;
	}
    
    public static function init() {

            add_action( 'elementor/element/common/_section_style/after_section_end', [ __CLASS__, 'add_controls_section' ], 10, 2 );
    }



    public static function add_controls_section( $element ) {

        $tabs = Controls_Manager::TAB_STYLE;

        $element->start_controls_section(
			'ua_section_custom_css',
			[
				'label' => __( 'Custom CSS', 'ultraaddons' ) . ultraaddons_icon_markup(),
				'tab' => $tabs,//$old_section['tab'],
			]
		);

		$element->add_control(
            '_ua_css_switch',
            [
                    'label' => __( 'Switch', 'ultraaddons' ),
                    'description' => __( 'Custom CSS any where.', 'ultraaddons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'On', 'ultraaddons' ),
                    'label_off' => __( 'Off', 'ultraaddons' ),
                    'return_value' => 'yes',
                    'default' => '',
                    'prefix_class' => 'custom-css-applied-',
            ]
        );

		$element->add_control(
			'ua_custom_css_title',
			[
				'raw' => __( 'Add your own custom CSS here', 'ultraaddons' ),
				'type' => Controls_Manager::RAW_HTML,
				'condition' => [
					'_ua_css_switch' => 'yes',
				],
			]
		);

		$element->add_control(
			'ua_custom_css',
			[
				'type' => Controls_Manager::CODE,
				'label' => __( 'Custom CSS', 'ultraaddons' ),
				'language' => 'css',
				'render_type' => 'ui',
				'show_label' => false,
				'separator' => 'none',
                'frontend_available' => true,
				'condition' => [
					'_ua_css_switch' => 'yes',
				],
			]
		);

		$element->add_control(
			'ua_custom_css_description',
			[
				'raw' => __( 'Use "selector" to target wrapper element. Examples:<br>selector {color: red;} // For main element<br>selector .child-element {margin: 10px;} // For child element<br>.my-class {text-align: center;} // Or use any custom selector', 'ultraaddons' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
				'condition' => [
					'_ua_css_switch' => 'yes',
				],
			]
		);

		$element->end_controls_section();

    }

    public static function localize_settings( array $settings ) {
            $settings['i18n']['custom_css'] = __( 'Custom CSS', 'ultraaddons' );

            return $settings;
    }

}