<?php
namespace UltraAddons\Extension;

use Elementor\Controls_Manager;
use Elementor\Element_Base;

defined('ABSPATH') || die();

class Ultra_Effects {

	public static function init() {
		add_action( 'elementor/element/common/_section_style/after_section_end', [ __CLASS__, 'register_control' ], 1 );
	}

	public static function register_control( Element_Base $element) {
		$element->start_controls_section(
			'_section_ulltra_effects',
			[
				'label' => __( 'Ultra Effects', 'ultraaddons' ) . ultraaddons_icon_markup(),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
                        'ex_hover_animationssss',
                        [
                                'label' => __( 'Animation', 'ultraaddons' ),
                                'type' => Controls_Manager::HOVER_ANIMATION,
//                                'prefix_class' => 'elementor-animation-',
                        ]
                );

		$element->end_controls_section();
                
                
	}


	public static function before_section_render( Element_Base $element ) {
		$settings = $element->get_settings_for_display();
                //_inner_wrapper, _widget_wrapper, _wrapper

	}
}

Ultra_Effects::init();
