<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Repeater;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;


class Image_Accordion extends Base{
    
    /**
     * Get your widget name
     *
     * Retrieve image accordion widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string keywords
     */
    public function get_keywords() {
        return [ 'ultraaddons', 'image', 'accordion' ];
    }

    /**
     * Register image accordion widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        // For General Section
        $this->content_general_contents_controls();
        $this->content_general_settings_controls();
        $this->accordion_title_settings_controls();
        $this->accordion_content_settings_controls();
        $this->accordion_button_style_setting_controls();
        $this->accordion_action_icon_style_section();
        
    }

    /**
     * Render image accordion widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
    }

    /**
     * Register controls for content tab general section
     */
    protected function content_general_contents_controls(){

        $this->start_controls_section(
            'ua_img_accordion_content_tab',
            [
                'label' => esc_html__('Content', 'ultraaddons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

            $repeater->add_control(
                'ua_img_accordion_active',
                [
                    'label'     => esc_html__('Active ? ', 'ultraaddons' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'default'   => 'no',
                    'label_on'  => esc_html__( 'Yes', 'ultraaddons' ),
                    'label_off' => esc_html__( 'No', 'ultraaddons' ),
                ]
            );

            $repeater->add_control(
                'ua_img_accordion_bg',
                [
                    'label'     => esc_html__( 'Background Image', 'ultraaddons' ),
                    'type'      => Controls_Manager::MEDIA,
                    'default'   => [
                        'url' => Utils::get_placeholder_image_src(),
                        'id'    => -1
                    ],
                ]
            );

            $repeater->add_control(
                'ua_img_accordion_title',
                [
                    'label'         => esc_html__('Title', 'ultraaddons' ),
                    'type'          => Controls_Manager::TEXT,
                    'label_block'   => true,
                    'default'       => esc_html__('Image accordion Title', 'ultraaddons' ),
                ]
            );

            $repeater->add_control(
                'ua_img_accordion_enable_icon',
                [
                    'label'         => esc_html__( 'Enable Icon', 'ultraaddons' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => esc_html__( 'Yes', 'ultraaddons' ),
                    'label_off'     => esc_html__( 'No', 'ultraaddons' ),
                    'return_value'  => 'yes',
                    'default'       => '',
                ]
            );

            $repeater->add_control(
                'ua_img_accordion_title_icons',
                [
                    'label'             => esc_html__('Icon for title', 'ultraaddons' ),
                    'type'              => Controls_Manager::ICONS,
                    'fa4compatibility'  => 'ua_img_accordion_title_icon',
                    'default'           => [
                        'value' => '',
                    ],
                    'condition'         => [
                        'ua_img_accordion_enable_icon' => 'yes',
                    ]
                ]
            );

            $repeater->add_control(
                'ua_img_accordion_title_icon_position',
                [
                    'label'     => esc_html__( 'Icon Position', 'ultraaddons' ),
                    'type'      => Controls_Manager::SELECT,
                    'default'   => 'left',
                    'options'   => [
                        'left'      => esc_html__( 'Before', 'ultraaddons' ),
                        'right'     => esc_html__( 'After', 'ultraaddons' ),
                    ],
                    'condition' => [
                        'ua_img_accordion_title_icons!' => '',
                        'ua_img_accordion_enable_icon' => 'yes',
                    ],
                ]
            );

            $repeater->add_control(
                'ua_img_accordion_enable_button',
                [
                    'label'         => esc_html__( 'Enable Button', 'ultraaddons' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => esc_html__( 'Yes', 'ultraaddons' ),
                    'label_off'     => esc_html__( 'No', 'ultraaddons' ),
                    'return_value'  => 'yes',
                    'default'       => 'yes',
                    'separator'     => 'before',
                ]
            );

            $repeater->add_control(
                'ua_img_accordion_button_label',
                [
                    'label'         => esc_html__('Button Label', 'ultraaddons' ),
                    'type'          => Controls_Manager::TEXT,
                    'label_block'   => true,
                    'default'       => esc_html__('Read More','ultraaddons' ),
                    'condition'     => [
                        'ua_img_accordion_enable_button' => 'yes',
                    ],
                ]
            );

            $repeater->add_control(
                'ua_img_accordion_button_url',
                [
                    'label'     => esc_html__('Button URL', 'ultraaddons' ),
                    'type'      => Controls_Manager::URL,
                    'condition' => [
                        'ua_img_accordion_enable_button' => 'yes',
                    ],
                ]
            );

            $repeater->add_control(
                'ua_img_accordion_enable_pupup',
                [
                    'label'         => esc_html__( 'Enable Popup', 'ultraaddons' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => esc_html__( 'Yes', 'ultraaddons' ),
                    'label_off'     => esc_html__( 'No', 'ultraaddons' ),
                    'return_value'  => 'yes',
                    'default'       => '',
                    'separator'     => 'before',
                ]
            );

            $repeater->add_control(
                'ua_img_accordion_pup_up_icons',
                [
                    'label'             => esc_html__('Pupup Icon', 'ultraaddons' ),
                    'type'              => Controls_Manager::ICONS,
                    'fa4compatibility'  => 'ua_img_accordion_pup_up_icon',
                    'default'           => [
                        'value'     => 'icon icon-plus',
                        'library'   => 'ekiticons'
                    ],
                    'label_block'       => true,
                    'condition'         => [
                        'ua_img_accordion_enable_pupup' => 'yes'
                    ]
                ]
            );

            $repeater->add_control(
                'ua_img_accordion_enable_project_link',
                [
                    'label'         => esc_html__( 'Enable Project Link', 'ultraaddons' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => esc_html__( 'Yes', 'ultraaddons' ),
                    'label_off'     => esc_html__( 'No', 'ultraaddons' ),
                    'return_value'  => 'yes',
                    'separator'     => 'before',
                ]
            );

            $repeater->add_control(
                'ua_img_accordion_project_link',
                [
                    'label'         => esc_html__( 'Project Link', 'ultraaddons' ),
                    'type'          => Controls_Manager::URL,
                    'placeholder'   => esc_html__( 'https://example.com', 'ultraaddons' ),
                    'condition'     => [
                        'ua_img_accordion_enable_project_link' => 'yes'
                    ],
                ]
            );

            $repeater->add_control(
                'ua_img_accordion_project_link_icons',
                [
                    'label'             => esc_html__('Project Link Icon', 'ultraaddons' ),
                    'type'              => Controls_Manager::ICONS,
                    'fa4compatibility'  => 'ua_img_accordion_project_link_icon',
                    'default'           => [
                        'value'     => 'icon icon icon-link',
                        'library'   => 'ekiticons'
                    ],
                    'label_block'       => true,
                    'condition'         => [
                        'ua_img_accordion_enable_project_link' => 'yes'
                    ],
                ]
            );

            $this->add_control(
                'ua_img_accordion_items',
                [
                    'label' => esc_html__('Accordion Items', 'ultraaddons' ),
                    'type' => Controls_Manager::REPEATER,
                    'default' => [
                        [ 'ua_img_accordion_title' => esc_html__('This is title','ultraaddons' ) ],
                        [ 'ua_img_accordion_icon' => esc_attr('icon icon-minus' ) ],
                        [ 'ua_img_accordion_link' => esc_url('#' ) ],
                        [ 'ua_img_accordion_button_label' => esc_html__('Read More','ultraaddons' ) ],
                    ],
                    'fields' => $repeater->get_controls(),
                    'title_field' => '{{ ua_img_accordion_title }}',
                ]
            );

            $this->add_responsive_control(
                'items_style',
                [
                    'label'         => esc_html__('Style', 'ultraaddons' ),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => [
                        ''              => esc_html__('Default', 'ultraaddons' ),
                        'horizontal'    => esc_html__('Horizontal', 'ultraaddons' ),
                        'vertical'      => esc_html__('Vertical', 'ultraaddons' ),
                    ],
                    'default'       => 'horizontal',
                    'prefix_class'  => 'ekit-image-accordion%s-',
                ]
            );

            $this->add_control(
                'active_behavior',
                [
                    'label'         => esc_html__('Active Behaivor', 'ultraaddons' ),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => [
                        'click' => esc_html__('Click', 'ultraaddons' ),
                        'hover' => esc_html__('Hover', 'ultraaddons' ),
                    ],
                    'default'       => 'click',
                    'prefix_class'  => 'ekit-image-accordion-',
                ]
            );

            $this->end_controls_section();

    }

    /**
     * Register controls for style tab settings section
     */
    protected function content_general_settings_controls(){

        $this->start_controls_section(
            'ua_img_accordion_general_settings',
            [
              'label' => esc_html__( 'General', 'ultraaddons' ),
              'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_responsive_control(
            'ua_img_accordion_min_height',
            [
                'label' => esc_html__( 'Min Height', 'ultraaddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
    
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 460,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskit-single-image-accordion' => 'min-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementskit-image-accordion-wraper' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
    
    
        $this->add_responsive_control(
            'ua_img_accordion_gutter',
            [
                'label' => esc_html__( 'Gutter', 'ultraaddons' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskit-image-accordion-wraper .elementskit-single-image-accordion' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementskit-image-accordion-wraper' => 'margin-left: -{{SIZE}}{{UNIT}}; margin-right: -{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'ua_img_accordion_active_background_text',
            [
                'label' => esc_html__( 'Active Item Background', 'ultraaddons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
    
        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'ua_img_accordion_bg_active_color',
                'default' => '',
                'selector' => '{{WRAPPER}} .elementskit-single-image-accordion:before',

            )
        );
        $this->add_responsive_control(
            'ua_img_accordion_container_padding',
            [
                'label' => esc_html__( 'Padding', 'ultraaddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .elementskit-image-accordion-wraper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
    
        $this->add_responsive_control(
            'ua_img_accordion_container_margin',
            [
                'label' => esc_html__( 'Margin', 'ultraaddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskit-image-accordion-wraper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'ua_img_accordion_border_group',
                'label' => esc_html__( 'Border', 'ultraaddons' ),
                'selector' => '{{WRAPPER}} .elementskit-image-accordion-wraper',
            ]
        );

        $this->add_control(
            'ua_img_accordion_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'ultraaddons' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskit-image-accordion-wraper' => 'border-radius: {{SIZE}}px;',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'ua_img_accordion_shadow',
                'selector' => '{{WRAPPER}} .elementskit-image-accordion-wraper',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register controls for style tab accordion title settings section
     */
    protected function accordion_title_settings_controls(){

        $this->start_controls_section(
            'ua_img_accordion_section_img_accordion_title_settings',
            [
                'label' => esc_html__( 'Title', 'ultraaddons' ),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'ua_img_accordion_section_img_accordion_icon_title',
            [
                'label' => esc_html_x( 'Margin', 'Border Control', 'ultraaddons' ),
                'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => '0',
					'bottom' => '20',
					'left' => '0',
					'right' => '0',
					'unit' => 'px',
				],
                'selectors' => [
                    '{{WRAPPER}} .elementskit-single-image-accordion .elementskit-accordion-title-wraper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ua_img_accordion_section_img_accordion_title_icon_spacing',
            [
                'label' => esc_html_x( 'Title Icon Spacing', 'Border Control', 'ultraaddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .elementskit-single-image-accordion .elementskit-accordion-title-wraper .icon-title > i, {{WRAPPER}} .elementskit-single-image-accordion .elementskit-accordion-title-wraper .icon-title > svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_control(
			'ua_img_accordion_title_color',
			[
                'label' => esc_html__( 'Color', 'ultraaddons' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .elementskit-image-accordion-wraper .elementskit-accordion-title-wraper .elementskit-accordion-title ' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementskit-image-accordion-wraper .elementskit-accordion-title-wraper .elementskit-accordion-title svg path' => 'stroke: {{VALUE}}; fill: {{VALUE}};',
                ],
			]
        );
        
        $this->add_responsive_control(
            'ua_img_accordion_title_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'ultraaddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskit-image-accordion-wraper .elementskit-accordion-title-wraper .elementskit-accordion-title i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementskit-image-accordion-wraper .elementskit-accordion-title-wraper .elementskit-accordion-title svg' => 'max-width: {{SIZE}}{{UNIT}}; height: auto',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ua_img_accordion_title_typography_group',
                'selector' => '{{WRAPPER}} .elementskit-image-accordion-wraper .elementskit-accordion-title-wraper .elementskit-accordion-title',
            ]
        );

        $this->end_controls_section();

    }

    /**
     * Register controls for style tab accordion content settings section
     */
    protected function accordion_content_settings_controls(){

        $this->start_controls_section(
            'ua_img_accordion_section_img_accordion_content_settings',
            [
                'label' => esc_html__( 'Content', 'ultraaddons' ),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );
        
        $this->add_responsive_control(
            'ua_img_accordion_section_img_accordion_content_align',
            [
                'label' =>esc_html__( 'Alignment', 'ultraaddons' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left'    => [
                        'title' =>esc_html__( 'Left', 'ultraaddons' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' =>esc_html__( 'Center', 'ultraaddons' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' =>esc_html__( 'Right', 'ultraaddons' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskit-single-image-accordion .elementskit-accordion-content' => 'text-align: {{VALUE}};'
                ],
                'default' => 'center',
            ]
        );

        $this->add_responsive_control(
            'ua_img_accordion_section_img_accordion_content_padding',
            [
                'label' =>esc_html__( 'Padding', 'ultraaddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskit-single-image-accordion .elementskit-accordion-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_img_accordion_section_img_accordion_content_position',
            [
                'label' => esc_html__( 'Vertical Position', 'ultraaddons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Top', 'ultraaddons' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'ultraaddons' ),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Bottom', 'ultraaddons' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .elementskit-image-accordion-wraper .elementskit-single-image-accordion' => 'align-items: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register controls for style tab accordion button settings section
     */
    protected function accordion_button_style_setting_controls(){

        $this->start_controls_section(
            'ua_img_accordion_button_style_settings',
            [
                'label' => esc_html__( 'Button', 'ultraaddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'ua_img_accordion_text_padding',
            [
                'label' =>esc_html__( 'Padding', 'ultraaddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default' => [
                    'top' => 15,
                    'right' => 20,
                    'bottom' => 15,
                    'left' => 20,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskit-accordion-content .elementskit-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ua_img_accordion_btn_typography',
                'label' =>esc_html__( 'Typography', 'ultraaddons' ),
                'selector' => '{{WRAPPER}} .elementskit-accordion-content .elementskit-btn',
            ]
        );

        $this->start_controls_tabs( 'ua_img_accordion_tabs_button_style' );

        $this->start_controls_tab(
            'ua_img_accordion_tab_button_normal',
            [
                'label' =>esc_html__( 'Normal', 'ultraaddons' ),
            ]
        );

        $this->add_control(
            'ua_img_accordion_btn_text_color',
            [
                'label' =>esc_html__( 'Text Color', 'ultraaddons' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementskit-accordion-content .elementskit-btn' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name' => 'ua_img_accordion_btn_bg_color_group',
				'label' => esc_html__( 'Background', 'ultraaddons' ),
                'selector' => '{{WRAPPER}} .elementskit-accordion-content .elementskit-btn',
				'fields_options' => [
                    'background' => [
						'color' => [
                            'default' => '#fff'
                        ],
                    ],

				],

            )
        );

		$this->add_control(
            'ua_img_accordion_btn_border_color',
            [
                'label' => esc_html__( 'Border', 'ultraaddons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',

            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'ua_img_accordion_btn_border_group',
                'label' => esc_html__( 'Border', 'ultraaddons' ),
                'selector' => '{{WRAPPER}} .elementskit-accordion-content .elementskit-btn',
				'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'top' => '1',
                            'right' => '1',
                            'bottom' => '1',
                            'left' => '1',
                            'unit' => 'px'
                        ],
                    ],
                    'color' => [
                        'default' => '#ffffff',
                    ],
                ],
            ]
        );

        $this->add_control(
            'ua_img_accordion_btn_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'ultraaddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
				'default' => ['top' => '5', 'bottom' => '5', 'left' => '5', 'right' => '5', 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .elementskit-accordion-content .elementskit-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'ua_img_accordion_btn_tab_button_hover',
            [
                'label' =>esc_html__( 'Hover', 'ultraaddons' ),
            ]
        );

        $this->add_control(
            'ua_img_accordion_btn_hover_color',
            [
                'label' =>esc_html__( 'Text Color', 'ultraaddons' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .elementskit-accordion-content .elementskit-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'ua_img_accordion_btn_bg_hover_color_group',
                'default' => '',
                'selector' => '{{WRAPPER}} .elementskit-accordion-content .elementskit-btn:hover',
            )
        );

        $this->add_control(
            'ua_img_accordion_btn_border_color_hover',
            [
                'label' => esc_html__( 'Border', 'ultraaddons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'ua_img_accordion_btn_border_hover_group',
                'label' => esc_html__( 'Border', 'ultraaddons' ),
                'selector' => '{{WRAPPER}} .elementskit-accordion-content .elementskit-btn:hover',
            ]
        );

        $this->add_control(
            'btn_border_radius_hover',
            [
                'label' => esc_html__( 'Border Radius', 'ultraaddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskit-accordion-content .elementskit-btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->end_controls_section();

    }

    /**
     * Register controls for style tab accordion button settings section
     */
    protected function accordion_action_icon_style_section(){

        $this->start_controls_section(
            'ua_img_accordion_style_section',
            [
                'label' => esc_html__( 'Action Icon', 'ultraaddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'actions_width',
            [
                'label'     => esc_html__( 'Width', 'ultraaddons' ),
                'type'      => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .ekit-image-accordion-actions > a' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'ua_img_accordion_section_img_accordion_icon_left_spacing',
            [
                'label' => esc_html__( 'Icon Left Spacing', 'ultraaddons' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskit-single-image-accordion .elementskit-icon-wraper > a:not(:last-child)' => 'margin-right: {{SIZE}}px',
                ],
            ]
        );

        $this->add_control(
            'ua_img_accordion_section_img_accordion_icon_spacing',
            [
                'label' => esc_html_x( 'Icon Container Spacing', 'Border Control', 'ultraaddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .elementskit-single-image-accordion .elementskit-icon-wraper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'actions_border_width',
            [
                'label'         => esc_html__( 'Border Width', 'ultraaddons' ),
                'type'          => Controls_Manager::NUMBER,
                'placeholder'   => '1',
                'selectors'     => [
                    '{{WRAPPER}} .ekit-image-accordion-actions > a' => 'border-width: {{VALUE}}px;',
                ],
            ]
        );

        $this->start_controls_tabs('ua_img_accordion_pup_up_style_tabs' );

        $this->start_controls_tab(
            'ua_img_accordion_pupup_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons' ),
            ]
        );

        $this->add_control(
            'ua_img_accordion_pup_up_icon_color',
            [
                'label' => esc_html__( 'Popup Icon Color', 'ultraaddons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskit-icon-wraper a:first-child' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementskit-icon-wraper a:first-child svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_img_accordion_pup_up_project_color',
            [
                'label' => esc_html__( 'Link Icon Color', 'ultraaddons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskit-icon-wraper a:last-child' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementskit-icon-wraper a:last-child svg path'   => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'action_btn_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ekit-image-accordion-actions > a' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'ua_img_accordion_pup_up_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'ultraaddons' ),
            ]
        );

        $this->add_control(
            'ua_img_accordion_pup_up_icon_color_hover',
            [
                'label' => esc_html__( 'Popup Icon color', 'ultraaddons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskit-icon-wraper a:first-child:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .elementskit-icon-wraper a:first-child:hover svg path'   => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_img_accordion_pup_up_project_color_hover',
            [
                'label' => esc_html__( 'Link Icon color', 'ultraaddons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskit-icon-wraper a:last-child:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementskit-icon-wraper a:last-child:hover svg path'   => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'action_btn_bg_hover',
            [
                'label'     => esc_html__( 'Background Color (Hover)', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ekit-image-accordion-actions > a:hover' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

    }

}
