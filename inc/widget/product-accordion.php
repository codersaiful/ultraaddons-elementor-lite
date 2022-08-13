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
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;


class Product_Accordion extends Base{
    
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
        return [ 'ultraaddons','ua', 'product', 'accordion' ];
    }

    /**
     * Register image accordion widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls() {

        // For General Section
        $this->content_general_contents_controls();
        $this->content_general_settings_controls();
        $this->accordion_title_settings_controls();
        $this->accordion_price_settings_controls();
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
                'ua_product_id',
                [
                    'label' => esc_html__( 'Product ID', 'ultraaddons' ),
                    'type' => Controls_Manager::SELECT2,
                    'multiple' => false,
                    'options' => $this->get_product_ids(),
                ]
            );

            $repeater->add_control(
                'ua_img_accordion_bg_enable',
                [
                    'label'         => esc_html__( 'Enable Custom Background', 'ultraaddons' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => esc_html__( 'Yes', 'ultraaddons' ),
                    'label_off'     => esc_html__( 'No', 'ultraaddons' ),
                    'return_value'  => 'yes',
                    'default'       => '',
                ]
            );

            $repeater->add_control(
                'ua_img_accordion_bg',
                [
                    'label'     => esc_html__( 'Background Image', 'ultraaddons' ),
                    'type'      => Controls_Manager::MEDIA,
                    'default'   => [
                        'url'   => Utils::get_placeholder_image_src(),
                        'id'    => -1
                    ],
                    'condition' => [
                        'ua_img_accordion_bg_enable' => 'yes',
                        'ua_product_id!' => '',
                    ]
                ]
            );
            $repeater->add_control(
                'ua_img_accordion_bg_empty_id',
                [
                    'label'     => esc_html__( 'Background Image', 'ultraaddons' ),
                    'type'      => Controls_Manager::MEDIA,
                    'default'   => [
                        'url'   => Utils::get_placeholder_image_src(),
                        'id'    => -1
                    ],
                    'condition' => [
                        'ua_img_accordion_bg_enable' => '',
                        'ua_product_id' => '',
                    ]
                ]
            );

            $repeater->add_control(
                'ua_img_accordion_title',
                [
                    'label'         => esc_html__('Title', 'ultraaddons' ),
                    'type'          => Controls_Manager::TEXT,
                    'label_block'   => true,
                    'default'       => esc_html__('Image accordion Title', 'ultraaddons' ),
                    'placeholder'   => esc_html__( 'Start typing to override', 'ultraaddons' ),
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
                'ua_img_accordion_button_label_for_product',
                [
                    'label'         => esc_html__('Button Label', 'ultraaddons' ),
                    'type'          => Controls_Manager::TEXT,
                    'label_block'   => true,
                    'default'       => esc_html__('Add to Cart','ultraaddons' ),
                    'condition'     => [
                        'ua_img_accordion_enable_button' => 'yes',
                        'ua_product_id!' => '',
                    ],
                ]
            );
            $repeater->add_control(
                'ua_img_accordion_button_label',
                [
                    'label'         => esc_html__('Button Label', 'ultraaddons' ),
                    'type'          => Controls_Manager::TEXT,
                    'label_block'   => true,
                    'default'       => esc_html__('Learn More','ultraaddons' ),
                    'condition'     => [
                        'ua_img_accordion_enable_button' => 'yes',
                        'ua_product_id' => '',
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

           /*  $repeater->add_control(
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
            ); */

           /*  $repeater->add_control(
                'ua_img_accordion_pup_up_icons',
                [
                    'label'             => esc_html__('Popup Icon', 'ultraaddons' ),
                    'type'              => Controls_Manager::ICONS,
                    'fa4compatibility'  => 'ua_img_accordion_pup_up_icon',
                    'default'           => [
                        'value'     => 'fas fa-plus',
                        'library'   => 'fa-solid'
                    ],
                    'label_block'       => true,
                    'condition'         => [
                        'ua_img_accordion_enable_pupup' => 'yes'
                    ]
                ]
            ); */

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
                        'value'     => 'fas fa-link',
                        'library'   => 'fa-solid'
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
                        [ 'ua_img_accordion_button_label' => esc_html__('Add to Cart','ultraaddons' ) ],
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
                    'prefix_class'  => 'ua-product-accordion%s-',
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
                    'prefix_class'  => 'ua-product-accordion-',
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
                    '{{WRAPPER}} .ultraaddons-single-product-accordion' => 'min-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ultraaddons-product-accordion-wrapper' => 'height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .ultraaddons-product-accordion-wrapper .ultraaddons-single-product-accordion' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ultraaddons-product-accordion-wrapper' => 'margin-left: -{{SIZE}}{{UNIT}}; margin-right: -{{SIZE}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .ultraaddons-single-product-accordion:before',
                'exclude' =>['image']

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
                    '{{WRAPPER}} .ultraaddons-product-accordion-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .ultraaddons-product-accordion-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'ua_img_accordion_border_group',
                'label' => esc_html__( 'Border', 'ultraaddons' ),
                'selector' => '{{WRAPPER}} .ultraaddons-product-accordion-wrapper',
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
                    '{{WRAPPER}} .ultraaddons-product-accordion-wrapper' => 'border-radius: {{SIZE}}px;',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'ua_img_accordion_shadow',
                'selector' => '{{WRAPPER}} .ultraaddons-product-accordion-wrapper',
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
                    '{{WRAPPER}} .ultraaddons-single-product-accordion .ultraaddons-accordion-title-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ua_img_accordion_section_img_accordion_title_icon_spacing',
            [
                'label' => esc_html_x( 'Title Icon Spacing', 'Border Control', 'ultraaddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .ultraaddons-single-product-accordion .ultraaddons-accordion-title-wrapper .icon-title > i, {{WRAPPER}} .ultraaddons-single-product-accordion .ultraaddons-accordion-title-wrapper .icon-title > svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .ultraaddons-product-accordion-wrapper .ultraaddons-accordion-title-wrapper .ultraaddons-accordion-title ' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ultraaddons-product-accordion-wrapper .ultraaddons-accordion-title-wrapper .ultraaddons-accordion-title svg path' => 'stroke: {{VALUE}}; fill: {{VALUE}};',
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
                    '{{WRAPPER}} .ultraaddons-product-accordion-wrapper .ultraaddons-accordion-title-wrapper .ultraaddons-accordion-title i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ultraaddons-product-accordion-wrapper .ultraaddons-accordion-title-wrapper .ultraaddons-accordion-title svg' => 'max-width: {{SIZE}}{{UNIT}}; height: auto',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ua_img_accordion_title_typography_group',
                'selector' => '{{WRAPPER}} .ultraaddons-product-accordion-wrapper .ultraaddons-accordion-title-wrapper .ultraaddons-accordion-title',
            ]
        );

        $this->end_controls_section();

    }

    protected function accordion_price_settings_controls(){

        $this->start_controls_section(
            'ua_img_accordion_section_img_accordion_price_settings',
            [
                'label' => esc_html__( 'Price', 'ultraaddons' ),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'ua_img_accordion_section_img_accordion_price',
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
                    '{{WRAPPER}} .ultraaddons-single-product-accordion .ultraaddons-accordion-price-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        

        $this->start_controls_tabs( 'ua_img_accordion_tabs_price_style' );

        $this->start_controls_tab(
            'ua_img_accordion_tab_regular_price',
            [
                'label' =>esc_html__( 'Regular & Sale Price', 'ultraaddons' ),
            ]
        );

        $this->add_control(
			'ua_img_accordion_price_sale_color',
			[
                'label' => esc_html__( 'Color', 'ultraaddons' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .ultraaddons-product-accordion-wrapper .ultraaddons-accordion-price-wrapper .ultraaddons-accordion-price, {{WRAPPER}} .ultraaddons-product-accordion-wrapper .ultraaddons-accordion-price-wrapper .ultraaddons-accordion-price ins' => 'color: {{VALUE}};',
                ],
			]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ua_img_accordion_regular_price_typography_group',
                'selector' => '{{WRAPPER}} .ultraaddons-product-accordion-wrapper .ultraaddons-accordion-price-wrapper .ultraaddons-accordion-price, {{WRAPPER}} .ultraaddons-product-accordion-wrapper .ultraaddons-accordion-price-wrapper .ultraaddons-accordion-price ins',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'ua_img_accordion_tab_sale_price',
            [
                'label' =>esc_html__( 'Old Price', 'ultraaddons' ),
            ]
        );

        $this->add_control(
			'ua_img_accordion_price_regular_color',
			[
                'label' => esc_html__( 'Color', 'ultraaddons' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .ultraaddons-product-accordion-wrapper .ultraaddons-accordion-price-wrapper .ultraaddons-accordion-price del' => 'color: {{VALUE}};',
                ],
			]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ua_img_accordion_sale_price_typography_group',
                'selector' => '{{WRAPPER}} .ultraaddons-product-accordion-wrapper .ultraaddons-accordion-price-wrapper .ultraaddons-accordion-price del',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

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
                    'left' => [
                            'title' => __( 'Left', 'ultraaddons' ),
                            'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                            'title' => __( 'Center', 'ultraaddons' ),
                            'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                            'title' => __( 'Right', 'ultraaddons' ),
                            'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ultraaddons-single-product-accordion .ultraaddons-accordion-content' => 'text-align: {{VALUE}};'
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
                    '{{WRAPPER}} .ultraaddons-single-product-accordion .ultraaddons-accordion-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .ultraaddons-product-accordion-wrapper .ultraaddons-single-product-accordion' => 'align-items: {{VALUE}}',
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
                    '{{WRAPPER}} .ultraaddons-accordion-content .ultraaddons-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ua_img_accordion_btn_typography',
                'label' =>esc_html__( 'Typography', 'ultraaddons' ),
                'selector' => '{{WRAPPER}} .ultraaddons-accordion-content .ultraaddons-btn',
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
                    '{{WRAPPER}} .ultraaddons-accordion-content .ultraaddons-btn' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name' => 'ua_img_accordion_btn_bg_color_group',
				'label' => esc_html__( 'Background', 'ultraaddons' ),
                'selector' => '{{WRAPPER}} .ultraaddons-accordion-content .ultraaddons-btn',
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
                'selector' => '{{WRAPPER}} .ultraaddons-accordion-content .ultraaddons-btn',
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
                    '{{WRAPPER}} .ultraaddons-accordion-content .ultraaddons-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .ultraaddons-accordion-content .ultraaddons-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'ua_img_accordion_btn_bg_hover_color_group',
                'default' => '',
                'selector' => '{{WRAPPER}} .ultraaddons-accordion-content .ultraaddons-btn:hover',
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
                'selector' => '{{WRAPPER}} .ultraaddons-accordion-content .ultraaddons-btn:hover',
            ]
        );

        $this->add_control(
            'btn_border_radius_hover',
            [
                'label' => esc_html__( 'Border Radius', 'ultraaddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ultraaddons-accordion-content .ultraaddons-btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .ua-product-accordion-actions > a' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .ultraaddons-single-product-accordion .ultraaddons-icon-wrapper > a:not(:last-child)' => 'margin-right: {{SIZE}}px',
                ],
            ]
        );

        $this->add_control(
            'ua_img_accordion_section_img_accordion_icon_spacing',
            [
                'label' => esc_html_x( 'Icon Container Spacing', 'Border Control', 'ultraaddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .ultraaddons-single-product-accordion .ultraaddons-icon-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .ua-product-accordion-actions > a' => 'border-width: {{VALUE}}px;',
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
                    '{{WRAPPER}} .ultraaddons-icon-wrapper a:first-child' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ultraaddons-icon-wrapper a:first-child svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_img_accordion_pup_up_project_color',
            [
                'label' => esc_html__( 'Link Icon Color', 'ultraaddons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ultraaddons-icon-wrapper a:last-child' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ultraaddons-icon-wrapper a:last-child svg path'   => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'action_btn_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-product-accordion-actions > a' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
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
                    '{{WRAPPER}} .ultraaddons-icon-wrapper a:first-child:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .ultraaddons-icon-wrapper a:first-child:hover svg path'   => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_img_accordion_pup_up_project_color_hover',
            [
                'label' => esc_html__( 'Link Icon color', 'ultraaddons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ultraaddons-icon-wrapper a:last-child:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ultraaddons-icon-wrapper a:last-child:hover svg path'   => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'action_btn_bg_hover',
            [
                'label'     => esc_html__( 'Background Color (Hover)', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-product-accordion-actions > a:hover' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

    }
    protected function render() {
        $settings = $this->get_settings_for_display();
        extract($settings);
        
        ?>
        <div class="ua-widget-container">
            <div class="ua-product-accordion ultraaddons-product-accordion-wrapper">
                <?php foreach ( $ua_img_accordion_items as $key => $item ) : 
                    // var_dump($item);
                    $bg_link = $src_url = $product_title = $price = $add_to_cart_url = '';
                    $show_product = isset( $item['ua_product_id'] ) && ! empty( $item['ua_product_id'] ) ? true : false;
                    if( $show_product ){
                        global $wp;
                        $product = wc_get_product( $item['ua_product_id'] );
                        $attachment_id = $product->get_image_id();
                        $product_title = $product->get_title();
                        $price = $product->get_price_html();
                        $add_to_cart_url = home_url( $wp->request ) . '/?add-to-cart='. $item['ua_product_id'] .'&quantity=1';
                        // var_dump($add_to_cart_url);
                        $src_url = wp_get_attachment_url( $attachment_id );
                        if( isset( $item['ua_img_accordion_bg_enable'] ) && $item['ua_img_accordion_bg_enable'] == 'yes' ){
                            $bg_link = $item['ua_img_accordion_bg']['url'];
                        }elseif ( $src_url ) {
                            $bg_link = $src_url;
                        }else{
                            $bg_link = $item['ua_img_accordion_bg_empty_id']['url'];
                        }
                    }else{
                        if( isset( $item['ua_img_accordion_title'] ) && ! empty( $item['ua_img_accordion_title'] ) ){
                            $product_title = $item['ua_img_accordion_title'];
                        }
                        $bg_link = $item['ua_img_accordion_bg_empty_id']['url'];
                    }
                    ?>
                    <input type="radio" name="ua_id_<?php echo esc_attr($this->get_id()); ?>" id="ua_id_<?php echo esc_attr($this->get_id()) .'_'. $key; ?>" class="ultraaddons-single-product-accordion--input" <?php echo esc_attr( $item['ua_img_accordion_active'] == 'yes' ? 'checked' : '' ); ?> hidden>
                    <label for="ua_id_<?php echo esc_attr($this->get_id()) .'_'. $key; ?>" class="ultraaddons-single-product-accordion ua-product-accordion-item" style="background-image: url(<?php echo esc_url( $bg_link ); ?>)">
                        <span class="ultraaddons-accordion-content">
                        <?php if( $item['ua_img_accordion_enable_project_link'] == 'yes') {

                            if (!empty($item['ua_img_accordion_project_link']['url'])) {

                                $this->add_render_attribute('projectlink', 'href', $item['ua_img_accordion_project_link']['url']);

                                if ($item['ua_img_accordion_project_link']['is_external']) {
                                    $this->add_render_attribute('projectlink', 'target', '_blank');
                                }

                                if (!empty($item['ua_img_accordion_project_link']['nofollow'])) {
                                    $this->add_render_attribute('projectlink', 'rel', 'nofollow');
                                }
                            }

                            ?>
                            <span class="ultraaddons-icon-wrapper ua-product-accordion-actions">
                            <?php 
                            /* if($item['ua_img_accordion_enable_pupup'] == 'yes') { ?>
                                    <a href="<?php echo esc_url($item['ua_img_accordion_bg']['url']); ?>" class="icon-outline circle" data-elementor-open-lightbox="yes">
                                    <?php

                                        $migrated = isset( $item['__fa4_migrated']['ua_img_accordion_pup_up_icons'] );
                                        // Check if its a new widget without previously selected icon using the old Icon control
                                        $is_new = empty( $item['ua_img_accordion_pup_up_icon'] );
                                        if ( $is_new || $migrated ) {

                                            // new icon
                                            Icons_Manager::render_icon( $item['ua_img_accordion_pup_up_icons'], [ 'aria-hidden' => 'true'] );
                                        } else {
                                            ?>
                                            <i class="<?php echo esc_attr($item['ua_img_accordion_pup_up_icon']); ?>" aria-hidden="true"></i>
                                            <?php
                                        } </a>*/
                                    ?>
                                    
                            <?php //} ?>
                            <?php if($item['ua_img_accordion_enable_project_link'] == 'yes') {
                                    if ( ! empty( $item['ua_img_accordion_project_link']['url'] ) ) {
                                        $this->add_link_attributes( 'button-2' . $key, $item['ua_img_accordion_project_link'] );
                                    }
                                ?>
                                    <a <?php echo $this->get_render_attribute_string( 'button-2' . $key ); ?> class="icon-outline circle">
                                    <?php
                                        $migrated = isset( $item['__fa4_migrated']['ua_img_accordion_project_link_icons'] );
                                        // Check if its a new widget without previously selected icon using the old Icon control
                                        $is_new = empty( $item['ua_img_accordion_project_link_icon'] );
                                        if ( $is_new || $migrated ) {

                                            // new icon
                                            Icons_Manager::render_icon( $item['ua_img_accordion_project_link_icons'], [ 'aria-hidden' => 'true'] );
                                        } else {
                                            ?>
                                            <i class="<?php echo esc_attr($item['ua_img_accordion_project_link_icon']); ?>" aria-hidden="true"></i>
                                            <?php
                                        }
                                    ?>
                                    </a>
                                <?php } ?>
                            </span>
                            <?php } ?>
                            <span class="ultraaddons-accordion-title-wrapper">
                                <span class="ultraaddons-accordion-title <?php echo esc_attr($item['ua_img_accordion_title_icons'] != '') ? 'icon-title' : ''?>">
                                <?php if($item['ua_img_accordion_enable_icon']  == 'yes'): ?>
                                <?php if($item['ua_img_accordion_title_icon_position'] == 'left'): ?>
                                    <!-- same-1 -->
                                    <?php

                                        $migrated = isset( $item['__fa4_migrated']['ua_img_accordion_title_icons'] );
                                        // Check if its a new widget without previously selected icon using the old Icon control
                                        $is_new = empty( $item['ua_img_accordion_title_icon'] );
                                        if ( $is_new || $migrated ) {

                                            // new icon
                                            Icons_Manager::render_icon( $item['ua_img_accordion_title_icons'], [ 'aria-hidden' => 'true'] );
                                        } else {
                                            ?>
                                            <i class="<?php echo esc_attr($item['ua_img_accordion_title_icon']); ?>" aria-hidden="true"></i>
                                            <?php
                                        }
                                    ?>
                                <?php endif; ?>
                                <?php endif; ?>

                                <?php echo esc_html($product_title); ?>

                                <?php if($item['ua_img_accordion_enable_icon']  == 'yes'): ?>
                                <?php if($item['ua_img_accordion_title_icon_position'] == 'right'): ?>
                                    <!-- same-1 -->
                                    <?php

                                        $migrated = isset( $item['__fa4_migrated']['ua_img_accordion_title_icons'] );
                                        // Check if its a new widget without previously selected icon using the old Icon control
                                        $is_new = empty( $item['ua_img_accordion_title_icon'] );
                                        if ( $is_new || $migrated ) {

                                            // new icon
                                            Icons_Manager::render_icon( $item['ua_img_accordion_title_icons'], [ 'aria-hidden' => 'true'] );
                                        } else {
                                            ?>
                                            <i class="<?php echo esc_attr($item['ua_img_accordion_title_icon']); ?>" aria-hidden="true"></i>
                                            <?php
                                        }
                                    ?>
                                <?php endif; ?>
                                <?php endif; ?>
                                </span>
                            </span>

                            <?php if( $show_product ) : ?>
                            <span class="ultraaddons-accordion-price-wrapper">
                                <span class="ultraaddons-accordion-price">
                                    <?php echo wp_kses_post( $price ); ?>
                                </span>
                            </span>
                            <?php endif; ?>

                            <?php
                            if($item['ua_img_accordion_enable_button'] == 'yes'):
                                if ( ! empty( $item['ua_img_accordion_button_url']['url'] ) ) {
                                    $this->add_link_attributes( 'button-' . $key, $item['ua_img_accordion_button_url'] );
                                }elseif( ! empty( $add_to_cart_url ) ){
                                    $custom_attributes = array(
                                        'url' => $add_to_cart_url,
                                        'is_external' => '',
                                        'nofollow' => '',
                                        'custom_attributes' =>'',
                                    );
                                    $this->add_link_attributes( 'button-' . $key, $custom_attributes );
                                }
                            ?>
                                <span class="ultraaddons-btn-wrapper">
                                    <a class="ua-product-accordion--btn ultraaddons-btn whitespace--normal" <?php echo $this->get_render_attribute_string( 'button-' . $key ); ?>>
                                        <?php if($show_product){
                                            echo esc_html($item['ua_img_accordion_button_label_for_product']);
                                        }else{
                                            echo esc_html($item['ua_img_accordion_button_label']);
                                        } ?>
                                    </a>
                                </span>
                            <?php endif; ?>
                        </span>
                    </label>


                <?php endforeach; ?>

            </div>
        </div>
        <?php
    }

public function get_product_ids() {
   $all_ids = get_posts( array(
        'post_type' => 'product',
        'numberposts' => -1,
        'post_status' => 'publish',
        'fields' => 'ids',
   ) );
    $options = [];
   foreach ( $all_ids as $id ) {
         $options [$id]= $id;
   }
   return $options;
}

}
