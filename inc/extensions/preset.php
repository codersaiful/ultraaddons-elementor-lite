<?php
namespace UltraAddons\Extensions;

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Group_Control_Background;

defined('ABSPATH') || die();

/**
 * preset extension, where we will store
 * 
 * @link https://app.asana.com/0/1200602135381433/1200746765086290
 */
class Preset {

	public static function init() {
		add_action( 'elementor/element/column/section_advanced/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );
		add_action( 'elementor/element/common/_section_style/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );

	}

        

        public static function add_controls_section( Element_Base $element) {
		$tabs = Controls_Manager::TAB_STYLE;
                
		if ( 'section' === $element->get_name() || 'column' === $element->get_name() ) {
			$tabs = Controls_Manager::TAB_LAYOUT;
		}

		$element->start_controls_section(
			'_ua_preset_settings_title',
			[
				'label' => esc_html__( 'Preset Setting', 'ultraaddons' ) . ultraaddons_icon_markup(),
				'tab'   => $tabs,
			]
		);

                
                $element->add_control(
                        '_ua_preset_name',
                        [
                                'label' => esc_html__( 'Preset Switch', 'ultraaddons' ),
                                'description' => esc_html__( 'Current setting will be override.', 'ultraaddons' ),
                                'type' => Controls_Manager::SELECT2,
                                'multiple' => false,
                                'label_block' => true,
                                'options'   => ['aaa' => 'AAA', 'bbb' => 'BBB', 'ccc' => 'CCC']
                                
                        ]
                );
                


                /**
                 * We will try to add this feature 
                 * later
                 * 
                 * @todo restriction gradient, I will try to add later.
                 */
             

		$element->end_controls_section();
                
                
	}
        
        

}
