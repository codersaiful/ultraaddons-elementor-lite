<?php
namespace UltraAddons\Widget;
/**
 * Info Box
 * 
 * @author Moktadir Rahman <codeastrology.dev2@gmail.com>
 */
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Controls_Manager;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Border;
use Elementor\Utils;
use Elementor\Group_Control_Box_Shadow;


class Info_Box extends Base {
    use \UltraAddons\Traits\Button_Helper;
    /**
     * Set Keyword for search in
     * 
     * @return type
     */
    public function get_keywords() {
            return [ 'ultraaddons', 'ua','info', 'service', 'box' ];
    }
    
    protected function content_infobox(){
        $this->start_controls_section(
                    'section_sliders',
                    [
                            'label' => __( 'Info Box', 'ultraaddons' ),
                    ]
            );
            
            $this->add_responsive_control(
                    'infobox_align',
                    [
                            'label' => __( 'Alignment', 'ultraaddons' ),
                            'type' => Controls_Manager::CHOOSE,
                            'options' => [
                                    'left'    => [
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
                                    'justify' => [
                                            'title' => __( 'Justified', 'ultraaddons' ),
                                            'icon' => 'eicon-text-align-justify',
                                    ],
                            ],
                            'prefix_class' => 'elementor-align-',
                            'default' => 'left',
                    ]
            );
            
            $this->add_control(
                    'icon_style',
                    [
                            'label'     => esc_html__( 'Select Icon Or Image', 'ultraaddons' ),
                            'type'      => Controls_Manager::SELECT,
                            'options'   => [
                                'icon'      => __( 'Icon', 'ultraaddons' ),
                                'image'     => __( 'Image', 'ultraaddons')
                            ],
                            'default'       => 'icon',

                    ]
            );
            
            $this->add_control(
                    'add_icon',
                    [
                            'label' => __( 'Icon', 'ultraaddons' ),
                            'type' => Controls_Manager::ICONS,
                            'fa4compatibility' => 'icon',
                            'default' => [
                                    'value' => 'uicon uicon-ultraaddons',
                                    'library' => 'ultraaddons',
                            ],
                            'condition' => [
                                    'icon_style' => 'icon',
                            ],
                    ]
            );
            
            $this->add_control(
                    'add_image',
                    [
                            'label'     => __( 'Select Image', 'ultraaddons' ),
                            'type'      => Controls_Manager::MEDIA,
                            'default'   => [
                                'url'       => Utils::get_placeholder_image_src(),
                            ],
                            'condition' => [
                                    'icon_style' => 'image',
                            ],
                    ]
            );

            $this->add_control(
                    'view',
                    [
                            'label' => __( 'View', 'ultraaddons' ),
                            'type' => Controls_Manager::SELECT,
                            'options' => [
                                    'default' => __( 'Default', 'ultraaddons' ),
                                    'stacked' => __( 'Stacked', 'ultraaddons' ),
                                    'framed' => __( 'Framed', 'ultraaddons' ),
                            ],
                            'default' => 'stacked',
                            'prefix_class' => 'elementor-view-',
                            'condition' => [
                                    'icon_style' => 'icon',
//                                    'add_icon[library]!' => 'svg',
                            ],
                    ]
            );

            $this->add_control(
                    'shape',
                    [
                            'label' => __( 'Shape', 'ultraaddons' ),
                            'type' => Controls_Manager::SELECT,
                            'options' => [
                                    'circle' => __( 'Circle', 'ultraaddons' ),
                                    'square' => __( 'Square', 'ultraaddons' ),
                            ],
                            'default' => 'square',
                            'condition' => [
                                    'view!' => 'default',
                                    'add_icon[value]!' => '',
                                    'icon_style' => 'icon',
                            ],
                            'prefix_class' => 'elementor-shape-',
                    ]
            );

            $this->add_control(
                    'title_text',
                    [
                            'label' => __( 'Title & Description', 'ultraaddons' ),
                            'type' => Controls_Manager::TEXT,
                            'dynamic' => [
                                    'active' => true,
                            ],
                            'default' => __( 'Info Box Title', 'ultraaddons' ),
                            'placeholder' => __( 'Enter your title', 'ultraaddons' ),
                            'label_block' => true,
                    ]
            );
            $this->add_control(
                    'count_text',
                    [
                            'label' => __( 'Count Text', 'ultraaddons' ),
                            'type' => Controls_Manager::TEXT,
                            'default' => __( '01/', 'ultraaddons' ),
                            'label_block' => true,
                    ]
            );
            $this->add_control(
                    'title_size',
                    [
                            'label' => __( 'Title HTML Tag', 'ultraaddons' ),
                            'type' => Controls_Manager::SELECT,
                            'options' => [
                                    'h1' => 'H1',
                                    'h2' => 'H2',
                                    'h3' => 'H3',
                                    'h4' => 'H4',
                                    'h5' => 'H5',
                                    'h6' => 'H6',
                                    'div' => 'div',
                                    'span' => 'span',
                                    'p' => 'p',
                            ],
                            'default' => 'h3',
                    ]
            );

            $this->add_control(
                    'description_text',
                    [
                            'label' => '',
                            'type' => Controls_Manager::TEXTAREA,
                            'dynamic' => [
                                    'active' => true,
                            ],
                            'default' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'ultraaddons' ),
                            'placeholder' => __( 'Enter your description', 'ultraaddons' ),
                            'rows' => 10,
                            'separator' => 'none',
                            'show_label' => false,
                    ]
            );
            
            
            $this->add_control(
                    'wrapper_link_switch',
                    [
                            'label' => __( 'Wrapper Link Switch', 'ultraaddons' ),
                            'type' => Controls_Manager::SWITCHER,
                            'label_on' => __( 'Yes', 'ultraaddons' ),
                            'label_off' => __( 'No', 'ultraaddons' ),
                            'return_value' => 'yes',
                    ]
            );
            
            $this->add_control(
                    'wrapper_link',
                    [
                            'label' => __( 'Wrapper Link', 'ultraaddons' ),
                            'type' => Controls_Manager::URL,
                            'dynamic' => [
                                    'active' => true,
                            ],
                            'placeholder' => __( 'https://your-link.com', 'ultraaddons' ),
                            'default' => [
                                    'url' => '#',
                            ],
                        'condition' => [
                            'wrapper_link_switch' => 'yes',
                        ],
                    ]
            );
            
            $this->add_responsive_control(
                    'wrapper_link_padding',
                    [
                            'label' => __( 'Padding', 'ultraaddons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'description' => __( 'For setting wrapper link padding, Please set zero padding for main box from Advance Tab.', 'ultraaddons' ),
                            'size_units' => [ 'px', '%' ],
                            'default'   => [
                                    'top' => 50,
                                    'left' => 50,
                                    'right' => 50,
                                    'bottom' => 50,
                                    'unit' => 'px',
                            ],
                            'selectors' => [
                                    '{{WRAPPER}} .elementor-widget-container a.ua-info-box-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
//                                    '{{WRAPPER}} .elementor-widget-container' => 'padding: 0px 0px 0px 0px !important;',
                            ],
                            'condition' => [
                                'wrapper_link_switch' => 'yes',
                            ],
                    ]
            );
            
            $this->end_controls_section();
            
    }

    protected function style_infobox(){
        $this->start_controls_section(
                    'section_style_icon',
                    [
                            'label' => __( 'Icon', 'ultraaddons' ),
                            'tab'   => Controls_Manager::TAB_STYLE,
                            'conditions' => [
                                    'relation' => 'or',
                                    'terms' => [
                                            [
                                                    'name' => 'add_icon[value]',
                                                    'operator' => '!=',
                                                    'value' => '',
                                            ],
                                    ],
                            ],
                    ]
            );

            $this->start_controls_tabs( 'icon_colors' );

            $this->start_controls_tab(
                    'icon_colors_normal',
                    [
                            'label' => __( 'Normal', 'ultraaddons' ),
                    ]
            );

            $this->add_control(
                    'primary_color',
                    [
                            'label' => __( 'Icon Color', 'ultraaddons' ),
                            'type' => Controls_Manager::COLOR,
                            'global' => [
                                    'default' => Global_Colors::COLOR_PRIMARY,
                            ],
                            'default' => '#fff',
                            'selectors' => [
                                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'fill: {{VALUE}}; color: {{VALUE}};',
                                    '{{WRAPPER}}.elementor-view-framed .elementor-icon, {{WRAPPER}}.elementor-view-default .elementor-icon' => 'fill: {{VALUE}}; color: {{VALUE}}; border-color: {{VALUE}};',
                            ],
                    ]
            );

            $this->add_control(
                    'secondary_color',
                    [
                            'label' => __( 'Background Color', 'ultraaddons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#0FC392',
                            'condition' => [
                                    'view!' => 'default',
                            ],
                            'selectors' => [
                                    '{{WRAPPER}}.elementor-view-framed .elementor-icon' => 'background-color: {{VALUE}};',
                                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}};',
                            ],
                    ]
            );
            
            $this->add_responsive_control(
                    'icon_space',
                    [
                            'label' => __( 'Spacing', 'ultraaddons' ),
                            'type' => Controls_Manager::SLIDER,
                            'default' => [
                                    'size' => 40,
                            ],
                            'range' => [
                                    'px' => [
                                            'min' => 0,
                                            'max' => 100,
                                    ],
                            ],
                            'selectors' => [
                                    '{{WRAPPER}} .ua-info-box-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                                    '(mobile){{WRAPPER}} .ua-info-box-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                            ],
                    ]
            );

            $this->add_responsive_control(
                    'icon_size',
                    [
                            'label' => __( 'Size', 'ultraaddons' ),
                            'type' => Controls_Manager::SLIDER,
                            'range' => [
                                    'px' => [
                                            'min' => 6,
                                            'max' => 300,
                                    ],
                            ],
                            'default' => [
                                    'size' => 30,
                            ],
                            'selectors' => [
                                    '{{WRAPPER}} .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                                    '{{WRAPPER}} .infobox-svg' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                    ]
            );

            $this->add_control(
                    'icon_padding',
                    [
                            'label' => __( 'Padding', 'ultraaddons' ),
                            'type' => Controls_Manager::SLIDER,
                            'selectors' => [
                                    '{{WRAPPER}} .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};',
                                    '{{WRAPPER}} .ua-info-box-icon.svg' => 'padding: {{SIZE}}{{UNIT}};',
                            ],
                            'range' => [
                                    'em' => [
                                            'min' => 0,
                                            'max' => 5,
                                    ],
                            ],
                            'condition' => [
                                    'view!' => 'default',
                            ],
                    ]
            );

            $this->add_control(
                    'rotate',
                    [
                            'label' => __( 'Rotate', 'ultraaddons' ),
                            'type' => Controls_Manager::SLIDER,
                            'default' => [
                                    'size' => 0,
                                    'unit' => 'deg',
                            ],
                            'selectors' => [
                                    '{{WRAPPER}} .elementor-icon i' => 'transform: rotate({{SIZE}}{{UNIT}});',
                            ],
                    ]
            );

            $this->add_control(
                    'border_width',
                    [
                            'label' => __( 'Border Width', 'ultraaddons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                    '{{WRAPPER}} .elementor-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'condition' => [
                                    'view' => 'framed',
                            ],
                    ]
            );

            $this->add_control(
                    'border_radius',
                    [
                            'label' => __( 'Border Radius', 'ultraaddons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors' => [
                                    '{{WRAPPER}} .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'condition' => [
                                    'view!' => 'default',
                            ],
                    ]
            );

            $this->end_controls_tab();

            $this->start_controls_tab(
                    'icon_colors_hover',
                    [
                            'label' => __( 'Hover', 'ultraaddons' ),
                    ]
            );

            $this->add_control(
                    'hover_primary_color',
                    [
                            'label' => __( 'Icon Color', 'ultraaddons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '',
                            'selectors' => [
                                    '{{WRAPPER}}.elementor-view-stacked:hover .elementor-icon' => 'background-color: {{VALUE}};',
                                    '{{WRAPPER}}.elementor-view-framed:hover .elementor-icon, {{WRAPPER}}.elementor-view-default:hover .elementor-icon' => 'fill: {{VALUE}}; color: {{VALUE}}; border-color: {{VALUE}};',
                            ],
                    ]
            );

            $this->add_control(
                    'hover_secondary_color',
                    [
                            'label' => __( 'Background Color', 'ultraaddons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '',
                            'condition' => [
                                    'view!' => 'default',
                            ],
                            'selectors' => [
                                    '{{WRAPPER}}.elementor-view-framed:hover .elementor-icon' => 'background-color: {{VALUE}};',
                                    '{{WRAPPER}}.elementor-view-stacked:hover .elementor-icon' => 'fill: {{VALUE}}; color: {{VALUE}};',
                            ],
                    ]
            );
            
            $this->add_responsive_control(
                    'icon_space_hover',
                    [
                            'label' => __( 'Spacing', 'ultraaddons' ),
                            'type' => Controls_Manager::SLIDER,

                            'range' => [
                                    'px' => [
                                            'min' => 0,
                                            'max' => 100,
                                    ],
                            ],
                            'selectors' => [
                                    '{{WRAPPER}}:hover .ua-info-box-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                                    '(mobile){{WRAPPER}}:hover .ua-info-box-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                            ],
                    ]
            );

            $this->add_responsive_control(
                    'hover_icon_size',
                    [
                            'label' => __( 'Size', 'ultraaddons' ),
                            'type' => Controls_Manager::SLIDER,
                            'range' => [
                                    'px' => [
                                            'min' => 6,
                                            'max' => 300,
                                    ],
                            ],
//                            'default' => [
//                                    'size' => 30,
//                            ],
                            'selectors' => [
                                    '{{WRAPPER}}:hover .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                            ],
                    ]
            );

            $this->add_control(
                    'hover_icon_padding',
                    [
                            'label' => __( 'Padding', 'ultraaddons' ),
                            'type' => Controls_Manager::SLIDER,
                            'selectors' => [
                                    '{{WRAPPER}}:hover .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};',
                            ],
                            'range' => [
                                    'em' => [
                                            'min' => 0,
                                            'max' => 5,
                                    ],
                            ],
                            'condition' => [
                                    'view!' => 'default',
                            ],
                    ]
            );

            $this->add_control(
                    'hover_rotate',
                    [
                            'label' => __( 'Rotate', 'ultraaddons' ),
                            'type' => Controls_Manager::SLIDER,
//                            'default' => [
//                                    'size' => 0,
//                                    'unit' => 'deg',
//                            ],
                            'selectors' => [
                                    '{{WRAPPER}}:hover .elementor-icon i' => 'transform: rotate({{SIZE}}{{UNIT}});',
                            ],
                    ]
            );

            $this->add_control(
                    'hover_border_width',
                    [
                            'label' => __( 'Border Width', 'ultraaddons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                    '{{WRAPPER}}:hover .elementor-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'condition' => [
                                    'view' => 'framed',
                            ],
                    ]
            );

            $this->add_control(
                    'hover_border_radius',
                    [
                            'label' => __( 'Border Radius', 'ultraaddons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors' => [
                                    '{{WRAPPER}}:hover .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'condition' => [
                                    'view!' => 'default',
                            ],
                    ]
            );

            $this->end_controls_tab();

            $this->end_controls_tabs();

            

            $this->end_controls_section();
    }

    protected function style_content(){
        $this->start_controls_section(
                'section_style_content',
                [
                        'label' => __( 'Content', 'ultraaddons' ),
                        'tab'   => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->start_controls_tabs( 'content_tabs' );

        $this->start_controls_tab(
                'style_content_normal',
                [
                        'label' => __( 'Normal', 'ultraaddons' ),
                ]
        );
        
        
        
        $this->add_control(
                'heading_title',
                [
                        'label' => __( 'Title', 'ultraaddons' ),
                        'type' => Controls_Manager::HEADING,
                        'separator' => 'before',
                ]
        );

        $this->add_responsive_control(
                'title_bottom_space',
                [
                        'label' => __( 'Spacing', 'ultraaddons' ),
                        'type' => Controls_Manager::SLIDER,
                        'range' => [
                                'px' => [
                                        'min' => 0,
                                        'max' => 100,
                                ],
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .elementor-icon-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                        ],
                ]
        );

        $this->add_control(
                'title_color',
                [
                        'label' => __( 'Color', 'ultraaddons' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '#21272c',
                        'selectors' => [
                                '{{WRAPPER}} .ua-info-box-content .elementor-icon-box-title' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .ua-info-box-content .elementor-icon-box-title a' => 'color: {{VALUE}};',
                        ],
                        'global' => [
                                'default' => Global_Colors::COLOR_PRIMARY,
                        ],
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'title_typography',
                        'selector' => '{{WRAPPER}} .ua-info-box-content .elementor-icon-box-title, {{WRAPPER}} .ua-info-box-content .elementor-icon-box-title a',
                        'global' => [
                                'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                        ],

                ]
        );

        $this->add_control(
                'heading_description',
                [
                        'label' => __( 'Description', 'ultraaddons' ),
                        'type' => Controls_Manager::HEADING,
                        'separator' => 'before',
                ]
        );
        
        $this->add_responsive_control(
                'description_bottom_space',
                [
                        'label' => __( 'Spacing', 'ultraaddons' ),
                        'type' => Controls_Manager::SLIDER,
                        'range' => [
                                'px' => [
                                        'min' => 0,
                                        'max' => 100,
                                ],
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .elementor-icon-box-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                        ],
                ]
        );


        $this->add_control(
                'description_color',
                [
                        'label' => __( 'Color', 'ultraaddons' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '',
                        'selectors' => [
                                '{{WRAPPER}} .ua-info-box-content .elementor-icon-box-description' => 'color: {{VALUE}};',
                        ],
                        'global' => [
                                'default' => Global_Colors::COLOR_TEXT,
                        ],
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'description_typography',
                        'selector' => '{{WRAPPER}} .ua-info-box-content .elementor-icon-box-description',
                        'global' => [
                                'default' => Global_Typography::TYPOGRAPHY_TEXT,
                        ],
                ]
        );
         $this->add_control(
                'content_padding',
                [
                        'label' => __( 'Box Padding', 'ultraaddons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%' ],
                        'separator' =>'before',
                        'selectors' => [
                                '{{WRAPPER}} .elementor-widget-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ]
        );
        $this->end_controls_tab();
        
        
        
        $this->start_controls_tab(
                'style_content_normal_hover',
                [
                        'label' => __( 'Hover', 'ultraaddons' ),
                ]
        );
        
        
        
        
        
        $this->add_control(
                'heading_title_hover',
                [
                        'label' => __( 'Title', 'ultraaddons' ),
                        'type' => Controls_Manager::HEADING,
                        'separator' => 'before',
                ]
        );

        $this->add_responsive_control(
                'title_bottom_space_hover',
                [
                        'label' => __( 'Spacing', 'ultraaddons' ),
                        'type' => Controls_Manager::SLIDER,
                        'range' => [
                                'px' => [
                                        'min' => 0,
                                        'max' => 100,
                                ],
                        ],
                        'selectors' => [
                                '{{WRAPPER}}:hover .elementor-icon-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                        ],
                ]
        );

        $this->add_control(
                'title_color_hover',
                [
                        'label' => __( 'Color', 'ultraaddons' ),
                        'type' => Controls_Manager::COLOR,
//                        'default' => '#21272c',
                        'selectors' => [
                                '{{WRAPPER}}:hover .ua-info-box-content .elementor-icon-box-title' => 'color: {{VALUE}};',
                                '{{WRAPPER}}:hover .ua-info-box-content .elementor-icon-box-title a' => 'color: {{VALUE}};',
                        ],
                        'global' => [
                                'default' => Global_Colors::COLOR_PRIMARY,
                        ],
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'title_typography_hover',
                        'selector' => '{{WRAPPER}} .ua-info-box-content .elementor-icon-box-title, {{WRAPPER}} .ua-info-box-content .elementor-icon-box-title a',
                        'global' => [
                                'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                        ],

                ]
        );

        $this->add_control(
                'heading_description_hover',
                [
                        'label' => __( 'Description', 'ultraaddons' ),
                        'type' => Controls_Manager::HEADING,
                        'separator' => 'before',
                ]
        );

        $this->add_responsive_control(
                'description_bottom_space_hover',
                [
                        'label' => __( 'Spacing', 'ultraaddons' ),
                        'type' => Controls_Manager::SLIDER,
                        'range' => [
                                'px' => [
                                        'min' => 0,
                                        'max' => 100,
                                ],
                        ],
                        'selectors' => [
                                '{{WRAPPER}}:hover .elementor-icon-box-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                        ],
                ]
        );
        
        $this->add_control(
                'description_color_hover',
                [
                        'label' => __( 'Color', 'ultraaddons' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '',
                        'selectors' => [
                                '{{WRAPPER}}:hover .ua-info-box-content .elementor-icon-box-description' => 'color: {{VALUE}};',
                        ],
                        'global' => [
                                'default' => Global_Colors::COLOR_TEXT,
                        ],
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'description_typography_hover',
                        'selector' => '{{WRAPPER}}:hover .ua-info-box-content .elementor-icon-box-description',
                        'global' => [
                                'default' => Global_Typography::TYPOGRAPHY_TEXT,
                        ],
                ]
        );

        $this->end_controls_tab();
        
        
        $this->end_controls_tabs();

        $this->end_controls_section();

    }
    /**
     * @author B M Rafiul Alam
     * email: bmrafiul.alam@gamil.com
     */
    protected function style_count(){

        $this->start_controls_section(
                'counter_section',
                [
                        'label' => esc_html__( 'Counter', 'ultraaddons' ),
                        'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_control(
                'count_alignment',
                [
                        'type' => Controls_Manager::CHOOSE,
                        'label' => esc_html__( 'Horizontal Position', 'ultraaddons' ),
                        'options' => [
                                'left' => [
                                        'title' => esc_html__( 'Left', 'ultraaddons' ),
                                        'icon' => 'eicon-arrow-left',
                                ],
                                'right' => [
                                        'title' => esc_html__( 'Right', 'ultraaddons' ),
                                        'icon' => 'eicon-arrow-right',
                                ],
                        ],
                        'default' => 'right',
                ]
	);
         $this->add_control(
                'count_position',
                [
                        'type' => Controls_Manager::CHOOSE,
                        'label' => esc_html__( 'Vertical Position', 'ultraaddons' ),
                        'options' => [
                                'top' => [
                                        'title' => esc_html__( 'Top', 'ultraaddons' ),
                                        'icon' => 'eicon-arrow-up',
                                ],
                                'bottom' => [
                                        'title' => esc_html__( 'Bottom', 'ultraaddons' ),
                                        'icon' => 'eicon-arrow-down',
                                ],
                               
                        ],
                        'default' => 'right',
                ]
	);
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'counter_typography',
                        'selector' => '{{WRAPPER}} .count-text',
                ]
        );
        $this->add_control(
                'counter_color',
                [
                        'label' => __( 'Text Color', 'ultraaddons' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '#222',
                        'selectors' => [
                                '{{WRAPPER}} .count-text' => 'color: {{VALUE}};',
                        ],
                ]
        );
            
        $this->add_control(
                'count_radius',
                [
                        'label' => __( 'Border Radius', 'ultraaddons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%' ],
                        'selectors' => [
                                '{{WRAPPER}} .count-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ]
        );
        
         $this->add_control(
                'count_padding',
                [
                        'label' => __( 'Padding', 'ultraaddons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%' ],
                        'selectors' => [
                                '{{WRAPPER}} .count-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ]
        );
        $this->add_responsive_control(
                'counter_size',
                [
                        'label' => __( 'Size', 'ultraaddons' ),
                        'type' => Controls_Manager::SLIDER,
                        'default' => [
                                'size' => 40,
                        ],
                        'range' => [
                                'px' => [
                                        'min' => 40,
                                        'max' => 100,
                                ],
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .count-text' => 'height:{{SIZE}}{{UNIT}}; width:{{SIZE}}{{UNIT}};', 
                        ],
                ]
        );
        $this->add_control(
                'counter_bg',
                [
                        'label' => __( 'Background Color', 'ultraaddons' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '#ddd',
                        'selectors' => [
                                '{{WRAPPER}} .count-text' => 'background-color: {{VALUE}};',
                        ],
                ]
        );
        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                        'name' => 'count_shadow',
                        'label' => esc_html__( 'Box Shadow', 'ultraaddons' ),
                        'selector' => '{{WRAPPER}} .count-text',
                ]
        );

        $this->end_controls_section();
            

    }
        /**
     * Register widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        $this->btn_border_color = '#e2ebf1';
        
        $this->content_infobox();
        
        $this->style_infobox();
        
        $this->style_content();
            
        /**
         * Button Control Load using Trait
         * from Button Helper Trait
         */
        $this->button_register_controls();

        $this->style_count();
    }
    
    
    
    /**
     * Get Image Icon.
     * 
     * @access protected
     * 
     * @since 1.0.0
     */
    public function get_image_icon(){
        $settings   = $this->get_settings_for_display();
        $this->add_render_attribute( 'icon', 'class', [ 'elementor-icon' ] );
        $this->add_render_attribute( 'icon_wrapper', 'class', 'ua-info-box-icon' );

        $icon_tag = 'span';

        if ( ! isset( $settings['add_icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
                // add old default
                $settings['add_icon'] = 'uicon uicon-ultraaddons';
        }

        $has_icon = ! empty( $settings['add_icon'] );

        if ( $has_icon ) {
            if( is_string( $settings['add_icon']['value'] ) ){
                $this->add_render_attribute( 'i', 'class', $settings['add_icon'] );
            }
                
                $this->add_render_attribute( 'i', 'aria-hidden', 'true' );
        }
        $svg_library_bool = false;
        if($settings['add_icon']['library'] == 'svg'){
            $svg_library_bool = true;
        }
        

        $icon_attributes = $this->get_render_attribute_string( 'icon' );


        if ( ! $has_icon && ! empty( $settings['add_icon']['value'] ) ) {
                $has_icon = true;
        }
        $migrated = isset( $settings['__fa4_migrated']['add_icon'] );
        $is_new = ! isset( $settings['add_icon'] ) && Icons_Manager::is_migration_allowed();
        
        $icon_style = isset( $settings['icon_style'] ) ? $settings['icon_style'] : 'icon';
        $add_image  = isset( $settings['add_image']['url'] ) ? $settings['add_image']['url'] : '';
        $add_icon   = !empty( $settings['add_icon']['value'] ) && is_string( $settings['add_icon']['value'] ) ? $settings['add_icon']['value'] : false;
        $svg        = !empty( $settings['add_icon']['value']['url'] ) && is_string( $settings['add_icon']['value']['url'] ) ? $settings['add_icon']['value']['url'] : false;

        if ( $has_icon || 'image' == $icon_style ) { ?>
        <div <?php echo $this->get_render_attribute_string( 'icon_wrapper' ); ?>>
            <?php if( 'icon' == $icon_style ) { ?>
                <<?php echo implode( ' ', [ $icon_tag, $icon_attributes ] ); ?>>
                    <?php
                    if ( $is_new || $svg_library_bool ) {
                            Icons_Manager::render_icon( $settings['add_icon'], [ 'aria-hidden' => 'true' ] );
                    } elseif ( ! empty( $settings['add_icon'] ) ) {?>
                            <i <?php echo $this->get_render_attribute_string( 'i' ); ?>></i>
                    <?php } ?>
                </<?php echo $icon_tag; ?>>
            <?php } elseif( 'image' == $icon_style ) { ?>
                    <img class="infobox-image" src="<?php echo esc_url( $add_image );?>" alt="" />
            <?php } ?>
             <div class="count-text count-<?php echo $settings['count_alignment'];?> count-<?php echo $settings['count_position'];?> "><?php echo $settings['count_text']; ?></div>
        </div>
        <?php 
        }
    }

    /**
     * Render widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
            $settings = $this->get_settings_for_display();
            $icon_tag = 'span';
            $this->add_render_attribute( 'wrapper-tag', 'class', 'ua-info-box-wrapper' );
            $this->add_render_attribute( 'description_text', 'class', 'elementor-icon-box-description' );
            $this->add_inline_editing_attributes( 'title_text', 'none' );
            $this->add_inline_editing_attributes( 'description_text' );
            
        
        $wrapper_link_switch = !empty( $settings['wrapper_link_switch'] ) ? true : false;
        $button_show = true;
        $wrapper_tag = 'div';
        
        if( $wrapper_link_switch & ! empty( $settings['btn_link']['url'] ) ){
            $wrapper_tag = 'a';
            $this->add_link_attributes( 'wrapper-tag', $settings['wrapper_link'] );
            $button_show = false;
           

        }
        
    ?>
    <<?php echo $wrapper_tag; ?> <?php echo $this->get_render_attribute_string( 'wrapper-tag' ); ?>>
        <?php $this->get_image_icon(); ?>
        <div class="ua-info-box-content">
            <<?php echo esc_attr( $settings['title_size'] ); ?> class="elementor-icon-box-title">
                    <<?php echo implode( ' ', [ $icon_tag ] ); ?><?php echo $this->get_render_attribute_string( 'title_text' ); ?>><?php echo $settings['title_text']; ?></<?php echo $icon_tag; ?>>
            </<?php echo esc_attr( $settings['title_size'] ); ?>>
            <?php if ( ! ultraaddons_widget_data_is_empty( $settings['description_text'] ) ) : ?>
            <p <?php echo $this->get_render_attribute_string( 'description_text' ); ?>><?php echo $settings['description_text']; ?></p>
            <?php endif; ?>
        </div>
        <?php 
        if( $button_show ){
            $this->button_render();
        }
        
        ?>
    </<?php echo $wrapper_tag; ?>>
    <?php
        
    }

}
