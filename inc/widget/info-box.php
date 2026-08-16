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
use Elementor\Group_Control_Background;
use Elementor\Utils;
use Elementor\Group_Control_Box_Shadow;


class Info_Box extends Base {
    use \UltraAddons\Traits\Button_Helper;

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        $this->btn_align = 'center';
        $this->btn_text_color = '#ffffff';
        $this->btn_border_color = '#ffffff';
    }

    /**
     * Set Keyword for search in
     * 
     * @return type
     */
    public function get_keywords() {
            return [ 'ultraaddons-elementor-lite', 'ua','info', 'service', 'box','icon' ];
    }
    
    protected function content_infobox(){
        $box_title = $this->get_title();
        $this->start_controls_section(
                    'section_sliders',
                    [
                            'label' => $box_title,
                    ]
            );
            
            $this->add_responsive_control(
                    'infobox_align',
                    [
                            'label' => __( 'Alignment', 'ultraaddons-elementor-lite' ),
                            'type' => Controls_Manager::CHOOSE,
                            'options' => [
                                    'left'    => [
                                             'title' => __( 'Left', 'ultraaddons-elementor-lite' ),
                                             'icon' => 'eicon-text-align-left',
                                    ],
                                    'center' => [
                                             'title' => __( 'Center', 'ultraaddons-elementor-lite' ),
                                             'icon' => 'eicon-text-align-center',
                                    ],
                                    'right' => [
                                             'title' => __( 'Right', 'ultraaddons-elementor-lite' ),
                                             'icon' => 'eicon-text-align-right',
                                    ],
                            ],
                            'default' => 'center',
                            'selectors' => [
                                    '{{WRAPPER}} .ua-info-box-wrapper' => 'text-align: {{VALUE}};',
                            ],
                    ]
            );
            
            $this->add_responsive_control(
                    'icon_position',
                    [
                            'label' => __( 'Icon Position', 'ultraaddons-elementor-lite' ),
                            'type' => Controls_Manager::CHOOSE,
                            'options' => [
                                    'left' => [
                                            'title' => __( 'Left', 'ultraaddons-elementor-lite' ),
                                            'icon' => 'eicon-h-align-left',
                                    ],
                                    'top' => [
                                            'title' => __( 'Top', 'ultraaddons-elementor-lite' ),
                                            'icon' => 'eicon-v-align-top',
                                    ],
                                    'right' => [
                                            'title' => __( 'Right', 'ultraaddons-elementor-lite' ),
                                            'icon' => 'eicon-h-align-right',
                                    ],
                            ],
                            'default' => 'top',
                            'prefix_class' => 'ua-icon-box-position-',
                    ]
            );

            $this->add_responsive_control(
                    'icon_vertical_align',
                    [
                            'label' => __( 'Vertical Alignment', 'ultraaddons-elementor-lite' ),
                            'type' => Controls_Manager::CHOOSE,
                            'options' => [
                                    'top' => [
                                            'title' => __( 'Top', 'ultraaddons-elementor-lite' ),
                                            'icon' => 'eicon-v-align-top',
                                    ],
                                    'middle' => [
                                            'title' => __( 'Middle', 'ultraaddons-elementor-lite' ),
                                            'icon' => 'eicon-v-align-middle',
                                    ],
                                    'bottom' => [
                                            'title' => __( 'Bottom', 'ultraaddons-elementor-lite' ),
                                            'icon' => 'eicon-v-align-bottom',
                                    ],
                            ],
                            'default' => 'middle',
                            'prefix_class' => 'ua-icon-box-valign-',
                            'condition' => [
                                    'icon_position!' => 'top',
                            ],
                    ]
            );
            
            $this->add_control(
                    'icon_style',
                    [
                            'label'     => esc_html__( 'Select Icon Or Image', 'ultraaddons-elementor-lite' ),
                            'type'      => Controls_Manager::SELECT,
                            'options'   => [
                                'icon'      => __( 'Icon', 'ultraaddons-elementor-lite' ),
                                'image'     => __( 'Image', 'ultraaddons-elementor-lite')
                            ],
                            'default'       => 'icon',

                    ]
            );
            
            $this->add_control(
                    'add_icon',
                    [
                            'label' => __( 'Icon', 'ultraaddons-elementor-lite' ),
                            'type' => Controls_Manager::ICONS,
                            'fa4compatibility' => 'icon',
                            'default' => [
                                    'value' => 'fas fa-star',
                                    'library' => 'solid',
                            ],
                            'condition' => [
                                    'icon_style' => 'icon',
                            ],
                    ]
            );
            
            $this->add_control(
                    'add_image',
                    [
                            'label'     => __( 'Select Image', 'ultraaddons-elementor-lite' ),
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
                            'label' => __( 'View', 'ultraaddons-elementor-lite' ),
                            'type' => Controls_Manager::SELECT,
                            'options' => [
                                    'default' => __( 'Default', 'ultraaddons-elementor-lite' ),
                                    'stacked' => __( 'Stacked', 'ultraaddons-elementor-lite' ),
                                    'framed' => __( 'Framed', 'ultraaddons-elementor-lite' ),
                            ],
                            'default' => 'default',
                            'prefix_class' => 'elementor-view-',
                            'condition' => [
                                    'icon_style' => 'icon',
                            ],
                    ]
            );

            $this->add_control(
                    'shape',
                    [
                            'label' => __( 'Shape', 'ultraaddons-elementor-lite' ),
                            'type' => Controls_Manager::SELECT,
                            'options' => [
                                    'circle' => __( 'Circle', 'ultraaddons-elementor-lite' ),
                                    'square' => __( 'Square', 'ultraaddons-elementor-lite' ),
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
                            'label' => __( 'Title & Description', 'ultraaddons-elementor-lite' ),
                            'dynamic' => [
                                    'active' => true,
                            ],
                            'default' => __( 'Title', 'ultraaddons-elementor-lite' ),
                            'placeholder' => __( 'Enter your title', 'ultraaddons-elementor-lite' ),
                            'label_block' => true,
                    ]
            );
            $this->add_control(
                    'count_text',
                    [
                            'label' => __( 'Count Text', 'ultraaddons-elementor-lite' ),
                            'type' => Controls_Manager::TEXT,
                            'label_block' => true,
                    ]
            );
            $this->add_control(
                    'title_size',
                    [
                            'label' => __( 'Title HTML Tag', 'ultraaddons-elementor-lite' ),
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
                            'default' => 'h2',
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
                            'default' => __( 'Lorem ipsum dolor sit amet.', 'ultraaddons-elementor-lite' ),
                            'placeholder' => __( 'Enter your description', 'ultraaddons-elementor-lite' ),
                            'rows' => 10,
                            'separator' => 'none',
                            'show_label' => false,
                    ]
            );
            
            $this->add_control(
                    'link_type',
                    [
                            'label' => __( 'Link Type', 'ultraaddons-elementor-lite' ),
                            'type' => Controls_Manager::SELECT,
                            'options' => [
                                    'none'  => __( 'None', 'ultraaddons-elementor-lite' ),
                                    'title' => __( 'Title', 'ultraaddons-elementor-lite' ),
                                    'btn'   => __( 'Button', 'ultraaddons-elementor-lite' ),
                                    'box'   => __( 'Box (Wrapper)', 'ultraaddons-elementor-lite' ),
                            ],
                            'default' => 'btn',
                            'separator' => 'before',
                    ]
            );

            $this->add_control(
                    'box_link',
                    [
                            'label' => __( 'Link URL', 'ultraaddons-elementor-lite' ),
                            'type' => Controls_Manager::URL,
                            'dynamic' => [
                                    'active' => true,
                            ],
                            'placeholder' => __( 'https://your-link.com', 'ultraaddons-elementor-lite' ),
                            'default' => [
                                    'url' => '#',
                            ],
                            'condition' => [
                                    'link_type' => [ 'title', 'box' ],
                            ],
                    ]
            );
            
            $this->add_control(
                    'wrapper_link_switch',
                    [
                            'label' => __( 'Legacy Wrapper Link', 'ultraaddons-elementor-lite' ),
                            'type' => Controls_Manager::SWITCHER,
                            'label_on' => __( 'Yes', 'ultraaddons-elementor-lite' ),
                            'label_off' => __( 'No', 'ultraaddons-elementor-lite' ),
                            'return_value' => 'yes',
                            'condition' => [
                                    'link_type' => 'btn',
                            ],
                    ]
            );
            
            $this->add_control(
                    'wrapper_link',
                    [
                            'label' => __( 'Wrapper Link', 'ultraaddons-elementor-lite' ),
                            'type' => Controls_Manager::URL,
                            'dynamic' => [
                                    'active' => true,
                            ],
                            'placeholder' => __( 'https://your-link.com', 'ultraaddons-elementor-lite' ),
                            'default' => [
                                    'url' => '#',
                            ],
                            'condition' => [
                                    'wrapper_link_switch' => 'yes',
                                    'link_type' => 'btn',
                            ],
                    ]
            );
            
            $this->end_controls_section();
            
    }

    protected function content_badge() {
        $this->start_controls_section(
            'section_badge',
            [
                'label' => __( 'Badge / Ribbon', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'badge_style',
            [
                'label' => __( 'Badge Style', 'ultraaddons-elementor-lite' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'corner',
                'options' => [
                    'none'   => __( 'None', 'ultraaddons-elementor-lite' ),
                    'corner' => __( 'Corner Ribbon', 'ultraaddons-elementor-lite' ),
                    'circle' => __( 'Circle Badge', 'ultraaddons-elementor-lite' ),
                    'flag'   => __( 'Flag Badge', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'badge_title',
            [
                'label' => __( 'Badge Text', 'ultraaddons-elementor-lite' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __( 'Hot', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'badge_style!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'badge_position',
            [
                'label' => __( 'Position', 'ultraaddons-elementor-lite' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'right',
                'condition' => [
                    'badge_style!' => 'none',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function content_watermark() {
        $this->start_controls_section(
            'section_watermark',
            [
                'label' => __( 'Watermark', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'watermark_type',
            [
                'label' => __( 'Watermark Type', 'ultraaddons-elementor-lite' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none'  => __( 'None', 'ultraaddons-elementor-lite' ),
                    'icon'  => __( 'Icon', 'ultraaddons-elementor-lite' ),
                    'image' => __( 'Image', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'watermark_icon',
            [
                'label' => __( 'Watermark Icon', 'ultraaddons-elementor-lite' ),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-shield-alt',
                    'library' => 'solid',
                ],
                'condition' => [
                    'watermark_type' => 'icon',
                ],
            ]
        );

        $this->add_control(
            'watermark_image',
            [
                'label' => __( 'Watermark Image', 'ultraaddons-elementor-lite' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'watermark_type' => 'image',
                ],
            ]
        );

        $this->add_control(
            'watermark_h_position',
            [
                'label' => __( 'Horizontal Position', 'ultraaddons-elementor-lite' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'ultraaddons-elementor-lite' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'right',
                'condition' => [
                    'watermark_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'watermark_v_position',
            [
                'label' => __( 'Vertical Position', 'ultraaddons-elementor-lite' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'top' => [
                        'title' => __( 'Top', 'ultraaddons-elementor-lite' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'middle' => [
                        'title' => __( 'Middle', 'ultraaddons-elementor-lite' ),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => __( 'Bottom', 'ultraaddons-elementor-lite' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'bottom',
                'condition' => [
                    'watermark_type!' => 'none',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function style_infobox(){
        $this->start_controls_section(
                    'section_style_icon',
                    [
                            'label' => __( 'Icon', 'ultraaddons-elementor-lite' ),
                            'tab'   => Controls_Manager::TAB_STYLE,
                           'condition' => [
                                    'icon_style' => 'icon',
                            ],
                    ]
            );

            $this->start_controls_tabs( 'icon_colors' );

            $this->start_controls_tab(
                    'icon_colors_normal',
                    [
                            'label' => __( 'Normal', 'ultraaddons-elementor-lite' ),
                    ]
            );

            $this->add_control(
                    'primary_color',
                    [
                            'label' => __( 'Icon Color', 'ultraaddons-elementor-lite' ),
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
                            'label' => __( 'Background Color', 'ultraaddons-elementor-lite' ),
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
                            'label' => __( 'Spacing', 'ultraaddons-elementor-lite' ),
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
                            'label' => __( 'Icon Size', 'ultraaddons-elementor-lite' ),
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
                            'label' => __( 'Padding', 'ultraaddons-elementor-lite' ),
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
                            'label' => __( 'Rotate', 'ultraaddons-elementor-lite' ),
                            'type' => Controls_Manager::SLIDER,
                            'default' => [
                                    'size' => 0,
                                    'unit' => 'deg',
                            ],
                            'selectors' => [
                                    '{{WRAPPER}} .elementor-icon i, {{WRAPPER}} .elementor-icon svg' => 'transform: rotate({{SIZE}}{{UNIT}});',
                            ],
                    ]
            );

            $this->add_control(
                    'border_width',
                    [
                            'label' => __( 'Border Width', 'ultraaddons-elementor-lite' ),
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
                            'label' => __( 'Border Radius', 'ultraaddons-elementor-lite' ),
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
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                        'name' => '_iconshadow',
                        'label' => __( 'Icon Shadow', 'ultraaddons-elementor-lite' ),
                        'selector' => '{{WRAPPER}} .elementor-icon',
                ]
        );

            $this->end_controls_tab();

            $this->start_controls_tab(
                    'icon_colors_hover',
                    [
                            'label' => __( 'Hover', 'ultraaddons-elementor-lite' ),
                    ]
            );

            $this->add_control(
                    'hover_primary_color',
                    [
                            'label' => __( 'Icon Color', 'ultraaddons-elementor-lite' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '',
                            'selectors' => [
                                    '{{WRAPPER}}.elementor-view-stacked:hover .elementor-icon' => 'fill: {{VALUE}}; color: {{VALUE}};',
                                    '{{WRAPPER}}.elementor-view-framed:hover .elementor-icon, {{WRAPPER}}.elementor-view-default:hover .elementor-icon' => 'fill: {{VALUE}}; color: {{VALUE}}; border-color: {{VALUE}};',
                            ],
                    ]
            );

            $this->add_control(
                    'hover_secondary_color',
                    [
                            'label' => __( 'Background Color', 'ultraaddons-elementor-lite' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '',
                            'condition' => [
                                    'view!' => 'default',
                            ],
                            'selectors' => [
                                    '{{WRAPPER}}.elementor-view-framed:hover .elementor-icon' => 'background-color: {{VALUE}};',
                                    '{{WRAPPER}}.elementor-view-stacked:hover .elementor-icon' => 'background-color: {{VALUE}};',
                            ],
                    ]
            );
            
            $this->add_responsive_control(
                    'icon_space_hover',
                    [
                            'label' => __( 'Spacing', 'ultraaddons-elementor-lite' ),
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
                            'label' => __( 'Size', 'ultraaddons-elementor-lite' ),
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
                            'label' => __( 'Padding', 'ultraaddons-elementor-lite' ),
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
                            'label' => __( 'Rotate', 'ultraaddons-elementor-lite' ),
                            'type' => Controls_Manager::SLIDER,
                            'selectors' => [
                                    '{{WRAPPER}}:hover .elementor-icon i, {{WRAPPER}}:hover .elementor-icon svg' => 'transform: rotate({{SIZE}}{{UNIT}});',
                            ],
                    ]
            );

            $this->add_control(
                    'hover_border_width',
                    [
                            'label' => __( 'Border Width', 'ultraaddons-elementor-lite' ),
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
                            'label' => __( 'Border Radius', 'ultraaddons-elementor-lite' ),
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

            $this->add_control(
                    'icon_hover_animation',
                    [
                            'label' => __( 'Hover Animation', 'ultraaddons-elementor-lite' ),
                            'type' => Controls_Manager::SELECT,
                            'default' => '',
                            'options' => [
                                    '' => __( 'None', 'ultraaddons-elementor-lite' ),
                                    'grow' => __( 'Grow', 'ultraaddons-elementor-lite' ),
                                    'shrink' => __( 'Shrink', 'ultraaddons-elementor-lite' ),
                                    'pulse' => __( 'Pulse', 'ultraaddons-elementor-lite' ),
                                    'rotate' => __( 'Rotate', 'ultraaddons-elementor-lite' ),
                                    'bob' => __( 'Bob / Float', 'ultraaddons-elementor-lite' ),
                                    'wobble' => __( 'Wobble', 'ultraaddons-elementor-lite' ),
                            ],
                            'prefix_class' => 'ua-icon-anim-',
                            'separator' => 'before',
                    ]
            );

            $this->end_controls_section();
    }

    protected function style_content(){
        $this->start_controls_section(
                'section_style_content',
                [
                        'label' => __( 'Content', 'ultraaddons-elementor-lite' ),
                        'tab'   => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->start_controls_tabs( 'content_tabs' );

        $this->start_controls_tab(
                'style_content_normal',
                [
                        'label' => __( 'Normal', 'ultraaddons-elementor-lite' ),
                ]
        );
        
        
        
        $this->add_control(
                'heading_title',
                [
                        'label' => __( 'Title', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::HEADING,
                        'separator' => 'before',
                ]
        );

        $this->add_responsive_control(
                'title_bottom_space',
                [
                        'label' => __( 'Spacing', 'ultraaddons-elementor-lite' ),
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
                        'label' => __( 'Color', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '#ffffff',
                        'selectors' => [
                                '{{WRAPPER}} .ua-info-box-content .elementor-icon-box-title' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .ua-info-box-content .elementor-icon-box-title a' => 'color: {{VALUE}};',
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
        $this->add_responsive_control(
                'title_padding',
                [
                        'label' => __( 'Title Padding', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px'],
                        'selectors' => [
                                '{{WRAPPER}} .elementor-icon-box-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ]
        );

        $this->add_control(
                'heading_description',
                [
                        'label' => __( 'Description', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::HEADING,
                        'separator' => 'before',
                ]
        );
        
        $this->add_responsive_control(
                'description_bottom_space',
                [
                        'label' => __( 'Spacing', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::SLIDER,
                        'range' => [
                                'px' => [
                                        'min' => 0,
                                        'max' => 100,
                                ],
                        ],
                        'default' => [
                                'size' => 22,
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .elementor-icon-box-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                        ],
                ]
        );


        $this->add_control(
                'description_color',
                [
                        'label' => __( 'Color', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '#ffffff',
                        'selectors' => [
                                '{{WRAPPER}} .ua-info-box-content .elementor-icon-box-description' => 'color: {{VALUE}};',
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
       
        $this->add_responsive_control(
                'description_padding',
                [
                        'label' => __( 'Description Padding', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px'],
                        'selectors' => [
                                '{{WRAPPER}} .elementor-icon-box-description'=> 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ]
        );
        $this->end_controls_tab();
        
        
        
        $this->start_controls_tab(
                'style_content_normal_hover',
                [
                        'label' => __( 'Hover', 'ultraaddons-elementor-lite' ),
                ]
        );
        
        
        $this->add_control(
                'heading_title_hover',
                [
                        'label' => __( 'Title', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::HEADING,
                        'separator' => 'before',
                ]
        );

        $this->add_responsive_control(
                'title_bottom_space_hover',
                [
                        'label' => __( 'Spacing', 'ultraaddons-elementor-lite' ),
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
                        'label' => __( 'Color', 'ultraaddons-elementor-lite' ),
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
                        'label' => __( 'Description', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::HEADING,
                        'separator' => 'before',
                ]
        );

        $this->add_responsive_control(
                'description_bottom_space_hover',
                [
                        'label' => __( 'Spacing', 'ultraaddons-elementor-lite' ),
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
                        'label' => __( 'Color', 'ultraaddons-elementor-lite' ),
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
     * email: bmrafiul.alam@gamil.
     * @since 1.1.0.11
     */
        protected function style_box(){

                        $this->start_controls_section(
                                'box_section',
                                [
                                        'label' => esc_html__( 'Box', 'ultraaddons-elementor-lite' ),
                                        'tab' => Controls_Manager::TAB_STYLE,
                                ]
                        );

                $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                                'name' => 'box_background',
                                'types' => [ 'classic', 'gradient' ],
                                'selector' => '{{WRAPPER}} .elementor-widget-container',
                        ]
                );

                $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                                'name' => 'box_border',
                                'label' => esc_html__( 'Border', 'ultraaddons-elementor-lite' ),
                                'selector' => '{{WRAPPER}} .elementor-widget-container',
                        ]
                );

                $this->add_responsive_control(
                        'wrapper_border_radius',
                        [
                                'label' => __( 'Radius', 'ultraaddons-elementor-lite' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px', '%' ],
                                'selectors' => [
                                        '{{WRAPPER}} .elementor-widget-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                        ]
                );

                $this->add_responsive_control(
                        'wrapper_link_padding',
                        [
                                'label' => __( 'Padding', 'ultraaddons-elementor-lite' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'description' => __( 'For setting wrapper link padding, Please set zero padding for main box from Advance Tab.', 'ultraaddons-elementor-lite' ),
                                'size_units' => [ 'px', '%' ],
                                'default'   => [
                                        'top' => 30,
                                        'left' => 15,
                                        'right' => 15,
                                        'bottom' => 30,
                                        'unit' => 'px',
                                ],
                                'selectors' => [
                                        '{{WRAPPER}} .elementor-widget-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                        ]
                );
        
                $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                                'name' => 'box_shadow',
                                'label' => esc_html__( 'Box Shadow', 'ultraaddons-elementor-lite' ),
                                'selector' => '{{WRAPPER}} .elementor-widget-container',
                        ]
                );


                        $this->end_controls_section();
        }
         protected function style_image(){

                $this->start_controls_section(
                        'image_section',
                        [
                                'label' => esc_html__( 'Image', 'ultraaddons-elementor-lite' ),
                                'tab' => Controls_Manager::TAB_STYLE,
                                'condition' => [
                                    'icon_style' => 'image',
                                ],
                        ]
                );
                $this->add_responsive_control(
                    'image_size',
                    [
                            'label' => __( 'Image Size', 'ultraaddons-elementor-lite' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'default' => [
                                    'size' => 60,
                            ],
                            'range' => [
                                    'px' => [
                                            'min' => 0,
                                            'max' => 1000,
                                    ],
                                    '%' => [
                                            'min' => 0,
                                            'max' => 100,
                                    ],
                            ],
                            'selectors' => [
                                    '{{WRAPPER}} .infobox-image' => 'width: {{SIZE}}{{UNIT}}; height: auto; max-width: 100%; object-fit: cover;',
                            ],
                    ]
                );
                $this->add_control(
                'image_margin',
                [
                        'label' => __( 'Image Margin', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%' ],
                        'selectors' => [
                                '{{WRAPPER}} .infobox-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ]
        );
         $this->add_control(
                'image_radius',
                [
                        'label' => __( 'Image Radius', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%' ],
                        'selectors' => [
                                '{{WRAPPER}} .infobox-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ]
        );

                $this->end_controls_section();
        }
        
    protected function style_count(){

        $this->start_controls_section(
                'counter_section',
                [
                        'label' => esc_html__( 'Counter', 'ultraaddons-elementor-lite' ),
                        'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_control(
                'count_alignment',
                [
                        'type' => Controls_Manager::CHOOSE,
                        'label' => esc_html__( 'Horizontal Position', 'ultraaddons-elementor-lite' ),
                        'options' => [
                                'left' => [
                                        'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                                        'icon' => 'eicon-arrow-left',
                                ],
                                'right' => [
                                        'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
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
                        'label' => esc_html__( 'Vertical Position', 'ultraaddons-elementor-lite' ),
                        'options' => [
                                'top' => [
                                        'title' => esc_html__( 'Top', 'ultraaddons-elementor-lite' ),
                                        'icon' => 'eicon-arrow-up',
                                ],
                                'bottom' => [
                                        'title' => esc_html__( 'Bottom', 'ultraaddons-elementor-lite' ),
                                        'icon' => 'eicon-arrow-down',
                                ],
                               
                        ],
                        'toggle' => true,
                        'default' => 'top',
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
                        'label' => __( 'Text Color', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '#222',
                        'selectors' => [
                                '{{WRAPPER}} .count-text' => 'color: {{VALUE}};',
                        ],
                ]
        );
        $this->add_control(
                'counter_bg',
                [
                        'label' => __( 'Background Color', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '#ddd',
                        'selectors' => [
                                '{{WRAPPER}} .count-text' => 'background-color: {{VALUE}};',
                        ],
                ]
        );
        $this->add_control(
                'count_radius',
                [
                        'label' => __( 'Border Radius', 'ultraaddons-elementor-lite' ),
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
                        'label' => __( 'Padding', 'ultraaddons-elementor-lite' ),
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
                        'label' => __( 'Size', 'ultraaddons-elementor-lite' ),
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
       
        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                        'name' => 'count_shadow',
                        'label' => esc_html__( 'Box Shadow', 'ultraaddons-elementor-lite' ),
                        'selector' => '{{WRAPPER}} .count-text',
                ]
        );

        $this->end_controls_section();
            

    }
    protected function style_badge() {
        $this->start_controls_section(
            'section_style_badge',
            [
                'label' => __( 'Badge / Ribbon', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'badge_style!' => 'none',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'badge_typography',
                'selector' => '{{WRAPPER}} .ua-badge-inner',
            ]
        );

        $this->start_controls_tabs( 'tabs_badge_style' );

        $this->start_controls_tab(
            'tab_badge_normal',
            [
                'label' => __( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'badge_text_color',
            [
                'label'     => __( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .ua-badge-inner' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_bg_color',
            [
                'label'     => __( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0FC392',
                'selectors' => [
                    '{{WRAPPER}} .ua-badge-inner' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-badge-flag .ua-badge-inner:after' => 'border-top-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'badge_box_shadow',
                'selector' => '{{WRAPPER}} .ua-badge-inner',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_badge_hover',
            [
                'label' => __( 'Hover', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'badge_text_color_hover',
            [
                'label'     => __( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}:hover .ua-badge-inner' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_bg_color_hover',
            [
                'label'     => __( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}:hover .ua-badge-inner' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}:hover .ua-badge-flag .ua-badge-inner:after' => 'border-top-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'badge_box_shadow_hover',
                'selector' => '{{WRAPPER}}:hover .ua-badge-inner',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'badge_padding',
            [
                'label'      => __( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'separator'  => 'before',
                'selectors'  => [
                    '{{WRAPPER}} .ua-badge-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'badge_style!' => 'corner',
                ],
            ]
        );

        $this->add_responsive_control(
            'badge_radius',
            [
                'label'      => __( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-badge-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'badge_style' => 'circle',
                ],
            ]
        );

        $this->add_responsive_control(
            'badge_size',
            [
                'label'      => __( 'Badge Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 20,
                        'max' => 120,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-badge-circle .ua-badge-inner' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'badge_style' => 'circle',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function style_watermark() {
        $this->start_controls_section(
            'section_style_watermark',
            [
                'label' => __( 'Watermark', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'watermark_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'watermark_size',
            [
                'label'     => __( 'Size', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 30,
                        'max' => 400,
                    ],
                ],
                'default'   => [
                    'size' => 120,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-watermark-icon i, {{WRAPPER}} .ua-watermark-icon svg' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-watermark-image' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->add_control(
            'watermark_color',
            [
                'label'     => __( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(0,0,0,0.06)',
                'selectors' => [
                    '{{WRAPPER}} .ua-watermark-icon' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
                'condition' => [
                    'watermark_type' => 'icon',
                ],
            ]
        );

        $this->add_control(
            'watermark_color_hover',
            [
                'label'     => __( 'Hover Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}:hover .ua-watermark-icon' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
                'condition' => [
                    'watermark_type' => 'icon',
                ],
            ]
        );

        $this->add_control(
            'watermark_opacity',
            [
                'label'     => __( 'Opacity', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1,
                        'step' => 0.05,
                    ],
                ],
                'default'   => [
                    'size' => 0.6,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-info-box-watermark' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'watermark_opacity_hover',
            [
                'label'     => __( 'Hover Opacity', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1,
                        'step' => 0.05,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}:hover .ua-info-box-watermark' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'watermark_rotation',
            [
                'label'     => __( 'Rotation', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'deg' => [
                        'min' => -180,
                        'max' => 180,
                    ],
                ],
                'default'   => [
                    'size' => 0,
                    'unit' => 'deg',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-info-box-watermark' => 'transform: rotate({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->add_control(
            'watermark_hover_animation',
            [
                'label'     => __( 'Hover Animation', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'none',
                'options'   => [
                    'none'   => __( 'None', 'ultraaddons-elementor-lite' ),
                    'fade'   => __( 'Fade In', 'ultraaddons-elementor-lite' ),
                    'zoom'   => __( 'Zoom / Scale', 'ultraaddons-elementor-lite' ),
                    'slide'  => __( 'Slide Up', 'ultraaddons-elementor-lite' ),
                    'rotate' => __( 'Rotate', 'ultraaddons-elementor-lite' ),
                ],
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
    protected function register_controls() {

        $this->btn_border_color = '#e2ebf1';
        
        $this->content_infobox();
        $this->content_badge();
        $this->content_watermark();
        
        $this->style_infobox();
        $this->style_image();
        $this->style_content();
            
        /**
         * Button Control Load using Trait
         * from Button Helper Trait
         */
        $this->button_register_controls();

        $this->style_box();
        $this->style_badge();
        $this->style_watermark();
        $this->style_count();
    }
    
    /**
     * Render Badge Output.
     * 
     * @since 2.0.2
     */
    public function render_badge() {
        $settings = $this->get_settings_for_display();
        $badge_style = ! empty( $settings['badge_style'] ) ? $settings['badge_style'] : 'none';
        if ( 'none' === $badge_style || empty( $settings['badge_title'] ) ) {
            return;
        }

        $badge_pos = ! empty( $settings['badge_position'] ) ? $settings['badge_position'] : 'right';
        $this->add_render_attribute( 'badge_wrapper', 'class', [
            'ua-info-box-badge',
            'ua-badge-' . $badge_style,
            'ua-badge-' . $badge_pos,
        ] );
        $this->add_inline_editing_attributes( 'badge_title', 'none' );
        ?>
        <div <?php $this->print_render_attribute_string( 'badge_wrapper' ); ?>>
            <span class="ua-badge-inner" <?php $this->print_render_attribute_string( 'badge_title' ); ?>>
                <?php echo esc_html( $settings['badge_title'] ); ?>
            </span>
        </div>
        <?php
    }

    /**
     * Render Watermark Output.
     * 
     * @since 2.0.2
     */
    public function render_watermark() {
        $settings = $this->get_settings_for_display();
        $wm_type = ! empty( $settings['watermark_type'] ) ? $settings['watermark_type'] : 'none';
        if ( 'none' === $wm_type ) {
            return;
        }

        $h_pos = ! empty( $settings['watermark_h_position'] ) ? $settings['watermark_h_position'] : 'right';
        $v_pos = ! empty( $settings['watermark_v_position'] ) ? $settings['watermark_v_position'] : 'bottom';
        $anim = ! empty( $settings['watermark_hover_animation'] ) ? $settings['watermark_hover_animation'] : 'none';

        $this->add_render_attribute( 'watermark_wrapper', 'class', [
            'ua-info-box-watermark',
            'ua-wm-' . $h_pos,
            'ua-wm-' . $v_pos,
            'ua-wm-anim-' . $anim,
        ] );
        ?>
        <div <?php $this->print_render_attribute_string( 'watermark_wrapper' ); ?>>
            <?php if ( 'icon' === $wm_type && ! empty( $settings['watermark_icon']['value'] ) ) : ?>
                <span class="ua-watermark-icon">
                    <?php Icons_Manager::render_icon( $settings['watermark_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                </span>
            <?php elseif ( 'image' === $wm_type && ! empty( $settings['watermark_image']['url'] ) ) : ?>
                <img class="ua-watermark-image" src="<?php echo esc_url( $settings['watermark_image']['url'] ); ?>" alt="" />
            <?php endif; ?>
        </div>
        <?php
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

        $has_icon = ! empty( $settings['add_icon']['value'] );
        $icon_style = isset( $settings['icon_style'] ) ? $settings['icon_style'] : 'icon';
        $add_image  = isset( $settings['add_image']['url'] ) ? $settings['add_image']['url'] : '';

        if ( $has_icon || 'image' === $icon_style ) { ?>
        <div <?php $this->print_render_attribute_string( 'icon_wrapper' ); ?>>
            <?php if( 'icon' === $icon_style && $has_icon ) { ?>
                <span <?php $this->print_render_attribute_string( 'icon' ); ?>>
                    <?php Icons_Manager::render_icon( $settings['add_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                </span>
            <?php } elseif( 'image' === $icon_style && ! empty( $add_image ) ) { ?>
                    <img class="infobox-image" src="<?php echo esc_url( $add_image ); ?>" alt="<?php echo esc_attr( $settings['title_text'] ?? '' ); ?>" />
            <?php } ?>
            <?php if( ! empty( $settings['count_text'] ) ): ?>
                <div class="count-text count-<?php echo esc_attr( $settings['count_alignment'] ?? 'right' ); ?> count-<?php echo esc_attr( $settings['count_position'] ?? 'top' ); ?>">
                        <?php echo esc_html( $settings['count_text'] ); ?>
                </div>
             <?php endif; ?>
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
        $this->add_render_attribute( 'wrapper-tag', 'class', 'ua-info-box-wrapper' );
        $this->add_render_attribute( 'description_text', 'class', 'elementor-icon-box-description' );
        $this->add_inline_editing_attributes( 'title_text', 'none' );
        $this->add_inline_editing_attributes( 'description_text' );

        $link_type = ! empty( $settings['link_type'] ) ? $settings['link_type'] : 'btn';
        $wrapper_link_switch = ! empty( $settings['wrapper_link_switch'] ) && 'yes' === $settings['wrapper_link_switch'];
        $box_url = ! empty( $settings['box_link']['url'] ) ? $settings['box_link'] : ( ! empty( $settings['wrapper_link']['url'] ) ? $settings['wrapper_link'] : [] );

        $button_show = false;
        $wrapper_tag = 'div';

        if ( 'box' === $link_type || $wrapper_link_switch ) {
            if ( ! empty( $box_url['url'] ) ) {
                $wrapper_tag = 'a';
                $this->add_link_attributes( 'wrapper-tag', $box_url );
            }
        } elseif ( 'btn' === $link_type ) {
            $button_show = true;
        }

        $title_tag = ! empty( $settings['title_size'] ) ? Utils::validate_html_tag( $settings['title_size'] ) : 'h3';
    ?>
    <<?php echo esc_attr( $wrapper_tag ); ?> <?php $this->print_render_attribute_string( 'wrapper-tag' ); ?>>
        <?php $this->render_watermark(); ?>
        <?php $this->render_badge(); ?>
        <?php $this->get_image_icon(); ?>
        <div class="ua-info-box-content">
            <<?php echo esc_attr( $title_tag ); ?> class="elementor-icon-box-title">
                <?php if ( 'title' === $link_type && ! empty( $box_url['url'] ) ) : 
                    $this->add_link_attributes( 'title-link', $box_url );
                ?>
                    <a class="ua-info-box-title-link" <?php $this->print_render_attribute_string( 'title-link' ); ?>>
                        <span <?php $this->print_render_attribute_string( 'title_text' ); ?>><?php echo esc_html( $settings['title_text'] ); ?></span>
                    </a>
                <?php else : ?>
                    <span <?php $this->print_render_attribute_string( 'title_text' ); ?>><?php echo esc_html( $settings['title_text'] ); ?></span>
                <?php endif; ?>
            </<?php echo esc_attr( $title_tag ); ?>>
            <?php if ( ! ultraaddons_widget_data_is_empty( $settings['description_text'] ) ) : ?>
            <p <?php $this->print_render_attribute_string( 'description_text' ); ?>><?php echo esc_html( $settings['description_text'] ); ?></p>
            <?php endif; ?>
            <?php 
            if ( $button_show ) {
                $this->button_render();
            }
            ?>
        </div>
    </<?php echo esc_attr( $wrapper_tag ); ?>>
    <?php

    }

}

