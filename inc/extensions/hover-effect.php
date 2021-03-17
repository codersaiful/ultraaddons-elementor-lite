<?php
namespace UltraAddons\Extension;

use Elementor\Controls_Manager;
use Elementor\Element_Base;

defined('ABSPATH') || die();

class Hover_Effect {

	public static function init() {
		add_action( 'elementor/element/column/section_advanced/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );
		add_action( 'elementor/element/common/_section_style/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );

//		add_action( 'elementor/frontend/before_render', [ __CLASS__, 'before_section_render' ], 1 );
//                add_action( 'elementor/element/before_add_attributes', [__CLASS__, 'before_section_render'] );
//                add_action( 'elementor/element/after_add_attributes', [__CLASS__, 'add_render_attributes'] );
	}

	public static function add_controls_section( Element_Base $element) {
		$tabs = Controls_Manager::TAB_CONTENT;

		if ( 'section' === $element->get_name() || 'column' === $element->get_name() ) {
			$tabs = Controls_Manager::TAB_LAYOUT;
		}

		
		$element->start_controls_section(
			'_ua_section_hover_animation',
			[
				'label' => __( 'Hover Effect', 'ultraaddons' ) . ultraaddons_icon_markup(),
				'tab'   => $tabs,
			]
		);

		$element->add_control(
                        'ex_hover_animation',
                        [
                                'label' => __( 'Animation', 'ultraaddons' ),
                                'type' => Controls_Manager::HOVER_ANIMATION,
                                'prefix_class' => 'elementor-animation-',
                        ]
                );

		$element->end_controls_section();
                
                
	}


	public static function before_section_render( Element_Base $element ) {
		$settings = $element->get_settings_for_display();
                //_inner_wrapper, _widget_wrapper, _wrapper

	}
}

Hover_Effect::init();
