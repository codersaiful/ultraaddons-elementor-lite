<?php
namespace UltraAddons\Extensions;


use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Plugin;


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
		
		// add_action( 'elementor/element/column/section_advanced/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );
		// add_action( 'elementor/element/common/_section_style/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );

		//add_action( 'elementor/frontend/before_render', [ __CLASS__, 'before_render' ], 1 );
		// add_filter( 'elementor/element/get_child_type', [ __CLASS__, 'get_child_type' ], 10, 3 );
		add_filter('elementor/frontend/section/should_render', [ __CLASS__, 'should_render' ], 99, 2);
	}

	public static function should_render( $should_render, Element_Base $element )
	{
		
		if( 'section' !== $element->get_name() ) return $should_render;

		if( Plugin::$instance->editor->is_edit_mode() ) return $should_render;


		
		$settings = $element->get_settings_for_display();
		
		$switch = $settings['_ua_condc_switch'] ?? '';
		
		if( $switch !== 'on' ) return $should_render;
		
		//var_dump($settings['_ua_condc_visibility']);
        $visibility = $settings['_ua_condc_visibility'] ?? '';
        $conds_post_ID = $settings['_ua_condc_post_ID'] ?? '';
        $conds_user_role = $settings['_ua_condc_user_role'] ?? '';
		$conds_post_ID = (int) $conds_post_ID;

		
		if( empty( $conds_post_ID ) && empty( $conds_user_role ) ) return $should_render;
		
		$c_post_ID = get_the_ID();
		$c_user_ID = get_current_user_id();

		// Get the user object.
		$user = get_userdata( $c_user_ID );

		// Get all the user roles as an array.
		$user_roles = $user->roles;
		$g_child_type = $should_render;
		
		if( $visibility == 'hide' ){
			$g_child_type =  $c_post_ID == $conds_post_ID || in_array( $conds_user_role, $user_roles ) ? false : $should_render;
		}else if( $visibility == 'show' ){
			$g_child_type = $c_post_ID == $conds_post_ID || in_array( $conds_user_role, $user_roles ) ? $should_render : false;
			
		}
		return $g_child_type;
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
				'prefix_class'=> 'ua-conditional-content-'
			]
		);

		$element->add_control(
			'_ua_condc_visibility',
			[
				'label' => esc_html__( 'Content/Section Visibility', 'plugin-name' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'hide',
				'label_block' => true,
				'options' => [
					'show'  => esc_html__( 'Show', 'plugin-name' ),
					'hide' => esc_html__( 'Hide', 'plugin-name' ),
				],
				'condition' => [
                    '_ua_condc_switch'  => ['on'],
                ],
			]
		);

		$element->add_control(
			'_ua_condc_post_ID',
			[
				'label' => __( 'Post ID','ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Enter your POST ID (Optional)','ultraaddons' ),
				'label_block' => true,
				'condition' => [
                    '_ua_condc_switch'  => ['on'],
                ],
			]
		);

		$element->add_control(
			'_ua_condc_user_role',
			[
				'label' => __( 'User Role','ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Enter your User ID (Optional)','ultraaddons' ),
				'label_block' => true,
				'condition' => [
                    '_ua_condc_switch'  => ['on'],
                ],
			]
		);

		/*************************************
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

		 //************************************/
        
		
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
		
        if( 'section' !== $element->get_name() ) return;

		$settings = $element->get_settings_for_display();
		$switch = $settings['_ua_condc_switch'] ?? '';

		if( $switch !== 'on' ) return;
		
		


        $visibility = $settings['_ua_condc_visibility'] ?? '';
        $conds_post_ID = $settings['_ua_condc_post_ID'] ?? '';
        $conds_user_role = $settings['_ua_condc_user_role'] ?? '';

		if( empty( $conds_post_ID ) && empty( $conds_user_role ) ) return;

		$c_post_ID = get_the_ID();
		$c_user_ID = get_current_user_id();

		// Get the user object.
		$user = get_userdata( $c_user_ID );

		// Get all the user roles as an array.
		$user_roles = $user->roles;
		$display_type = 'hidden_content';

		if( $visibility == 'show' ){
			if($c_post_ID == $conds_post_ID || in_array( $conds_user_role, $user_roles )){
				$display_type = 'hidden_content';
			}
			$display_type =  'display_content';
			
		}else if( $visibility == 'hide' ){
			if($c_post_ID == $conds_post_ID || in_array( $conds_user_role, $user_roles )){
				$display_type =  'display_content';
			}
			$display_type = 'hidden_content';
		}
		
		$style = "";
		if( $display_type == 'hidden_content' ){
			$style = "display: none !important";
		}

		$element->add_render_attribute(
			'_wrapper',
			[
				'style' => $style,//'cursor: pointer',
				'class' => 'ua-conditional-content ua-condition-' . $display_type
			]
		);

        
    }


}