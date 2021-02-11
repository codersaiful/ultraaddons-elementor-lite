<?php
namespace UltraAddons\Extension;

use Elementor\Controls_Manager;
use Elementor\Element_Base;

defined('ABSPATH') || die();

class Wrapper_Link {

	public static function init() {
		add_action( 'elementor/element/column/section_advanced/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );
		add_action( 'elementor/element/common/_section_style/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );

		add_action( 'elementor/frontend/before_render', [ __CLASS__, 'before_section_render' ], 1 );
	}

	public static function add_controls_section( Element_Base $element) {
		$tabs = Controls_Manager::TAB_CONTENT;

		if ( 'section' === $element->get_name() || 'column' === $element->get_name() ) {
			$tabs = Controls_Manager::TAB_LAYOUT;
		}

		
		$element->start_controls_section(
			'_section_wrapper_link',
			[
				'label' => __( 'Wrapper Link', 'ultraaddons' ) . ultraaddons_icon_markup(),
				'tab'   => $tabs,
			]
		);

		$element->add_control(
			'element_link',
			[
				'label'       => __( 'Desired URL', 'ultraaddons' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => 'https://example.com',
			]
		);

		$element->end_controls_section();
                
                
	}

        /**
         * Adding Attribute in wrapper
         * which called as _wrapper
         * 
         * @access public
         * @since 1.0.0.4
         * 
         * @param Element_Base $element
         */
	public static function before_section_render( Element_Base $element ) {
		$link_settings = $element->get_settings_for_display( 'element_link' );

		if ( $link_settings && ! empty( $link_settings['url'] ) ) {
			$element->add_render_attribute(
				'_wrapper',
				[
					'data-element_link' => json_encode( $link_settings ),
					'style' => 'cursor: pointer'
				]
			);
		}
	}
}

Wrapper_Link::init();
