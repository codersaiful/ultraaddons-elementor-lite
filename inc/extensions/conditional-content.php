<?php
namespace UltraAddons\Extensions;


use Elementor\Controls_Manager;
use Elementor\Element_Base;


defined('ABSPATH') || die();

/**
 * Conditional Content Extension for setting logic condition to any section/column or to any content
 * First I will do ti for any section and colunm
 * 
 * Finally we also try to do it for any content/element 
 * 
 * 
 * @date 10/2/2022
 * @since 1.1.0.10
 * @author Saiful islam <codersaiful@gmail.com>
 */
class Conditional_Content
{
    /**
     * Initializing Conditional Content
     * 
     * @author Saiful Islam <codersaiful@gmail.com>
     * @since 1.1.0.10
     *
     * @return void
     */
    public static function init() 
    {
		add_action( 'elementor/element/column/section_advanced/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );
		add_action( 'elementor/element/common/_section_style/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );

		add_action( 'elementor/frontend/before_render', [ __CLASS__, 'before_render' ], 1 );
	}

    /**
     * Control section for Elementor Edit Screen
     *
     * @return void
     */
    public static function add_controls_section( Element_Base $element )
    {
        $tabs = Controls_Manager::TAB_CONTENT;

		if ( 'section' === $element->get_name() || 'column' === $element->get_name() ) {
			$tabs = Controls_Manager::TAB_LAYOUT;
		}

		
		$element->start_controls_section(
			'_ua_condc_title',
			[
				'label' => __( 'Conditional Content', 'ultraaddons' ) . ultraaddons_icon_markup(),
				'tab'   => $tabs,
			]
		);

		

		$element->add_control(
			'_ua_condc_switch',
			[
				'label' => esc_html__( 'Apply Conidtion', 'ultraaddons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'ultraaddons' ),
				'label_off' => esc_html__( 'Off', 'ultraaddons' ),
				'return_value' => 'on',
				'default' => 'off',
			]
		);

		$element->add_control(
			'_ua_condc_visibility',
			[
				'label' => esc_html__( 'Content/Section Visibility', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'show',
				'options' => [
					'show'  => esc_html__( 'Visiable', 'plugin-name' ),
					'hide' => esc_html__( 'Hidden', 'plugin-name' ),
				],
				'condition' => [
                    '_ua_condc_switch'  => ['on'],
                ],
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'_ua_condc_post_ID',
			[
				'label' => __( 'Post ID','ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Enter your POST ID (Optional)','ultraaddons' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'_ua_condc_user_ID',
			[
				'label' => __( 'User ID','ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Enter your User ID (Optional)','ultraaddons' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'_ua_condc_post_switch',
			[
				'label' => esc_html__( 'Visibility', 'ultraaddons' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'show' => [
						'title' => esc_html__( 'Visible', 'ultraaddons' ),
						'icon' => 'eicon-preview-medium',
					],
					'hide' => [
						'title' => esc_html__( 'Hidden', 'ultraaddons' ),
						'icon' => 'eicon-editor-close',
					],
				],
				'default' => 'hide',
				'toggle' => true,
			]
		);

		$element->add_control(
            '_ua_condc_options',
            [
                'label'       => __('Conditions','ultraaddons'),
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    ['_ua_condc_post_switch' => 'hide'],
                ],
                'title_field' => 'Cond: {{{ _ua_condc_post_switch }}} P: {{{ _ua_condc_post_ID }}}| U: {{{ _ua_condc_user_ID }}}',
				'condition' => [
                    '_ua_condc_switch'  => ['on'],
                ],
            ]
        );

        
		
		$element->add_control(
			'_ua_condc_title_note',
			[
				'label' => esc_html__( 'Important Note', 'ultraaddons' ),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => esc_html__( 'You able to set condition for Post/Page ID or user ID base.', 'ultraaddons' ),
				'content_classes' => 'ua-note',
			]
		);

		$element->end_controls_section();

    }

    public static function before_render( Element_Base $element )
    {
		
        $switch = $element->get_settings_for_display( '_ua_condc_switch' );
		if( $switch !== 'on' ){
			return;
		}
		

        $visibility = $element->get_settings_for_display( '_ua_condc_visibility' );
        $conds = $element->get_settings_for_display( '_ua_condc_options' );

		$c_post_ID = get_the_ID();
		$c_user_ID = get_current_user_id();

        
    }


}