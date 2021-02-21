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

class Info_Box extends Base {
    
    /**
     * Set Keyword for search in
     * 
     * @return type
     */
    public function get_keywords() {
            return [ 'ultraaddons', 'info', 'service', 'box' ];
    }
    
    public static function get_button_sizes() {
            return [
                    'xs' => __( 'Extra Small', 'ultraaddons' ),
                    'sm' => __( 'Small', 'ultraaddons' ),
                    'md' => __( 'Medium', 'ultraaddons' ),
                    'lg' => __( 'Large', 'ultraaddons' ),
                    'xl' => __( 'Extra Large', 'ultraaddons' ),
            ];
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
                                    'value' => 'fas fa-star',
                                    'library' => 'fa-solid',
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
                    'infobox_button_text',
                    [
                            'label' => __( 'Button Text', 'ultraaddons' ),
                            'type' => Controls_Manager::TEXT,
                            'dynamic' => [
                                    'active' => true,
                            ],
                            'default' => __( 'Click here', 'ultraaddons' ),
                            'placeholder' => __( 'Click here', 'ultraaddons' ),
                            'separator' => 'before',
                    ]
            );

            $this->add_control(
                    'infobox_button_link',
                    [
                            'label' => __( 'Link', 'ultraaddons' ),
                            'type' => Controls_Manager::URL,
                            'dynamic' => [
                                    'active' => true,
                            ],
                            'placeholder' => __( 'https://your-link.com', 'ultraaddons' ),
                            'default' => [
                                    'url' => '#',
                            ],
                    ]
            );
            
            $this->add_control(
                    'infobox_button_selected_icon',
                    [
                            'label' => __( 'Icon', 'ultraaddons' ),
                            'type' => Controls_Manager::ICONS,
                            'fa4compatibility' => 'icon',
                            'default' => [
                                    'value' => 'fas fa-angle-double-right',
                                    'library' => 'solid',
                            ],
                    ]
            );
            
            $this->add_control(
                    'infobox_button_icon_align',
                    [
                            'label' => __( 'Icon Position', 'ultraaddons' ),
                            'type' => Controls_Manager::SELECT,
                            'default' => 'right',
                            'options' => [
                                    'left' => __( 'Before', 'ultraaddons' ),
                                    'right' => __( 'After', 'ultraaddons' ),
                            ],
                            'condition' => [
                                    'infobox_button_selected_icon[value]!' => '',
                            ],
                    ]
            );

            $this->add_control(
                    'infobox_button_icon_indent',
                    [
                            'label' => __( 'Icon Spacing', 'ultraaddons' ),
                            'type' => Controls_Manager::SLIDER,
                            'range' => [
                                    'px' => [
                                            'max' => 50,
                                    ],
                            ],
                            'default' => [
                                    'unit' => 'px',
                                    'size' => 15,
                            ],
                            'selectors' => [
                                    '{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                                    '{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                            ],
                    ]
            );

            

            $this->end_controls_section();
            
            $this->start_controls_section(
                    'section_style_general',
                    [
                            'label' => __( 'General', 'ultraaddons' ),
                            'tab'   => Controls_Manager::TAB_STYLE,
                    ]
            );
            
            $this->add_responsive_control(
                    'infobox_padding',
                    [
                            'label' => __( 'Padding', 'ultraaddons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'default'   => [
                                    'top' => 50,
                                    'bottom' => 50,
                                    'left' => 50,
                                    'right' => 50,
                                    'unit' => 'px',
                            ],
                            'selectors' => [
                                    '{{WRAPPER}} .mc-info-box-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                    ]
            );
            
            
            $this->end_controls_section();
            
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
                            'default' => '#0FC392',
                            'selectors' => [
                                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}};',
                                    '{{WRAPPER}}.elementor-view-framed .elementor-icon, {{WRAPPER}}.elementor-view-default .elementor-icon' => 'fill: {{VALUE}}; color: {{VALUE}}; border-color: {{VALUE}};',
                            ],
                    ]
            );

            $this->add_control(
                    'secondary_color',
                    [
                            'label' => __( 'Background Color', 'ultraaddons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#fff',
                            'condition' => [
                                    'view!' => 'default',
                            ],
                            'selectors' => [
                                    '{{WRAPPER}}.elementor-view-framed .elementor-icon' => 'background-color: {{VALUE}};',
                                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'fill: {{VALUE}}; color: {{VALUE}};',
                            ],
                    ]
            );
            
            $this->add_responsive_control(
                    'icon_space',
                    [
                            'label' => __( 'Spacing', 'ultraaddons' ),
                            'type' => Controls_Manager::SLIDER,
                            'default' => [
                                    'size' => 15,
                            ],
                            'range' => [
                                    'px' => [
                                            'min' => 0,
                                            'max' => 100,
                                    ],
                            ],
                            'selectors' => [
                                    '{{WRAPPER}} .mc-info-box-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                                    '(mobile){{WRAPPER}} .mc-info-box-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
                    'hover_icon_space',
                    [
                            'label' => __( 'Spacing', 'ultraaddons' ),
                            'type' => Controls_Manager::SLIDER,
                            'default' => [
                                    'size' => 15,
                            ],
                            'range' => [
                                    'px' => [
                                            'min' => 0,
                                            'max' => 100,
                                    ],
                            ],
                            'selectors' => [
                                    '{{WRAPPER}}.elementor-position-right:hover .mc-info-box-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
                                    '{{WRAPPER}}.elementor-position-left:hover .mc-info-box-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                                    '{{WRAPPER}}.elementor-position-top:hover .mc-info-box-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                                    '(mobile){{WRAPPER}}:hover .mc-info-box-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
                            'default' => [
                                    'size' => 30,
                            ],
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
                            'default' => [
                                    'size' => 0,
                                    'unit' => 'deg',
                            ],
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

            $this->start_controls_section(
                    'section_style_content',
                    [
                            'label' => __( 'Content', 'ultraaddons' ),
                            'tab'   => Controls_Manager::TAB_STYLE,
                    ]
            );

            $this->add_control(
                    'content_vertical_alignment',
                    [
                            'label' => __( 'Vertical Alignment', 'ultraaddons' ),
                            'type' => Controls_Manager::SELECT,
                            'options' => [
                                    'top' => __( 'Top', 'ultraaddons' ),
                                    'middle' => __( 'Middle', 'ultraaddons' ),
                                    'bottom' => __( 'Bottom', 'ultraaddons' ),
                            ],
                            'default' => 'top',
                            'prefix_class' => 'elementor-vertical-align-',
                    ]
            );
            
            $this->add_control(
                    'hover_animation',
                    [
                            'label' => __( 'Hover Animation', 'ultraaddons' ),
                            'type' => Controls_Manager::HOVER_ANIMATION,
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
                                    '{{WRAPPER}} .mc-info-box-content .elementor-icon-box-title' => 'color: {{VALUE}};',
                                    '{{WRAPPER}} .mc-info-box-content .elementor-icon-box-title a' => 'color: {{VALUE}};',
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
                            'selector' => '{{WRAPPER}} .mc-info-box-content .elementor-icon-box-title, {{WRAPPER}} .mc-info-box-content .elementor-icon-box-title a',
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

            $this->add_control(
                    'description_color',
                    [
                            'label' => __( 'Color', 'ultraaddons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '',
                            'selectors' => [
                                    '{{WRAPPER}} .mc-info-box-content .elementor-icon-box-description' => 'color: {{VALUE}};',
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
                            'selector' => '{{WRAPPER}} .mc-info-box-content .elementor-icon-box-description',
                            'global' => [
                                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                            ],
                    ]
            );
            
            

            $this->end_controls_section();
            
            $this->start_controls_section(
                    'section_style',
                    [
                            'label' => __( 'Button', 'ultraaddons' ),
                            'tab' => Controls_Manager::TAB_STYLE,
                    ]
            );
            
            $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                            'name' => 'button_typography',
                            'label' => 'Typography',
                            'selector' => '{{WRAPPER}} .elementor-button',
                            'global' => [
                                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                            ],

                    ]
            );
            
            $this->start_controls_tabs( 'tabs_button_style' );

            $this->start_controls_tab(
                    'tab_button_normal',
                    [
                            'label' => __( 'Normal', 'ultraaddons' ),
                    ]
            );
            
            $this->add_control(
                    'button_text_color',
                    [
                            'label' => __( 'Text Color', 'ultraaddons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#21272c',
                            'selectors' => [
                                    '{{WRAPPER}} .elementor-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
                            ],
                    ]
            );
            
            $this->add_control(
                    'background_color',
                    [
                            'label' => __( 'Background Color', 'ultraaddons' ),
                            'type' => Controls_Manager::COLOR,
//                            'global' => [
//                                    'default' => Global_Colors::COLOR_ACCENT,
//                            ],
                            'default' => '#fff',
                            'selectors' => [
                                    '{{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
                            ],
                    ]
            );
            
            $this->add_control(
                    'button_border_type',
                    [
                            'label' => _x( 'Border Type', 'Border Control', 'ultraaddons' ),
                            'type' => Controls_Manager::SELECT,
                            'options' => [
                                    '' => __( 'None', 'ultraaddons' ),
                                    'solid' => _x( 'Solid', 'Border Control', 'ultraaddons' ),
                                    'double' => _x( 'Double', 'Border Control', 'ultraaddons' ),
                                    'dotted' => _x( 'Dotted', 'Border Control', 'ultraaddons' ),
                                    'dashed' => _x( 'Dashed', 'Border Control', 'ultraaddons' ),
                            ],
                            'default' => 'solid',
                            'selectors' => [
                                    '{{SELECTOR}} .elementor-button' => 'border-style: {{VALUE}};',
                            ],
                    ]
            );
            
            $this->add_control(
                    'button_border_color',
                    [
                            'label' => __( 'Color', 'ultraaddons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#21272C',
                            'selectors' => [
                                    '{{WRAPPER}} .elementor-button' => 'border-color: {{VALUE}};',
                            ],
                    ]
            );
            
            $this->add_control(
                    'button_border_width',
                    [
                            'label' => __( 'Border Width', 'ultraaddons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'default' => [
                                    'top' => 2,
                                    'right' => 2,
                                    'bottom' => 2,
                                    'left' => 2,
                                    'unit' => 'px',
                            ],
                            'selectors' => [
                                    '{{WRAPPER}} .elementor-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                    ]
            );

            $this->add_control(
                    'button_border_radius',
                    [
                            'label' => __( 'Border Radius', 'ultraaddons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors' => [
                                    '{{WRAPPER}} .elementor-button, {{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                    ]
            );
            
            $this->end_controls_tab();
            
            $this->start_controls_tab(
                    'tab_button_hover',
                    [
                            'label' => __( 'Hover', 'ultraaddons' ),
                    ]
            );

            $this->add_control(
                    'hover_color',
                    [
                            'label' => __( 'Text Color', 'ultraaddons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#FFF',
                            'selectors' => [
                                    '{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'color: {{VALUE}};',
                                    '{{WRAPPER}} .elementor-button:hover svg, {{WRAPPER}} .elementor-button:focus svg' => 'fill: {{VALUE}};',
                            ],
                    ]
            );

            $this->add_control(
                    'button_background_hover_color',
                    [
                            'label' => __( 'Background Color', 'ultraaddons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#0FC392',
                            'selectors' => [
                                    '{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'background-color: {{VALUE}};',
                            ],
                    ]
            );
            
            $this->add_control(
                    'button_hover_border_type',
                    [
                            'label' => _x( 'Border Type', 'Border Control', 'ultraaddons' ),
                            'type' => Controls_Manager::SELECT,
                            'options' => [
                                    '' => __( 'None', 'ultraaddons' ),
                                    'solid' => _x( 'Solid', 'Border Control', 'ultraaddons' ),
                                    'double' => _x( 'Double', 'Border Control', 'ultraaddons' ),
                                    'dotted' => _x( 'Dotted', 'Border Control', 'ultraaddons' ),
                                    'dashed' => _x( 'Dashed', 'Border Control', 'ultraaddons' ),
                            ],
                            'default' => 'solid',
                            'selectors' => [
                                    '{{WRAPPER}} .elementor-button:hover' => 'border-style: {{VALUE}};',
                                    '{{WRAPPER}} .elementor-button:focus' => 'border-style: {{VALUE}};',
                            ],
                    ]
            );
            
            $this->add_control(
                    'button_hover_border_width',
                    [
                            'label' => __( 'Border Width', 'ultraaddons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'default' => [
                                    'top' => 2,
                                    'right' => 2,
                                    'bottom' => 2,
                                    'left' => 2,
                                    'unit' => 'px',
                            ],
                            'selectors' => [
                                    '{{WRAPPER}} .elementor-button:hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                    '{{WRAPPER}} .elementor-button:focus' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                    ]
            );

            $this->add_control(
                    'button_hover_border_radius',
                    [
                            'label' => __( 'Border Radius', 'ultraaddons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors' => [
                                    '{{WRAPPER}} .elementor-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                    '{{WRAPPER}} .elementor-button:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                    ]
            );

            $this->add_control(
                    'button_hover_border_color',
                    [
                            'label' => __( 'Border Color', 'ultraaddons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#0FC392',
                            'selectors' => [
                                    '{{WRAPPER}} .elementor-button:hover' => 'border-color: {{VALUE}};',
                                    '{{WRAPPER}} .elementor-button:focus' => 'border-color: {{VALUE}};',
                            ],
                    ]
            );            

            $this->end_controls_tab();

            $this->end_controls_tabs();           
            
            $this->end_controls_section();
            

    }
    
    protected function render_text() {
            $settings = $this->get_settings_for_display();

            $migrated = isset( $settings['__fa4_migrated']['infobox_button_selected_icon'] );
            $is_new = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();

            if ( ! $is_new && empty( $settings['infobox_button_icon_align'] ) ) {
                    // @todo: remove when deprecated
                    // added as bc in 2.6
                    //old default
                    $settings['infobox_button_icon_align'] = $this->get_settings( 'infobox_button_icon_align' );
            }

            $this->add_render_attribute( [
                    'content-wrapper' => [
                            'class' => 'elementor-button-content-wrapper',
                    ],
                    'icon-align' => [
                            'class' => [
                                    'elementor-button-icon',
                                    'elementor-align-icon-' . $settings['infobox_button_icon_align'],
                            ],
                    ],
                    'text' => [
                            'class' => 'elementor-button-text',
                    ],
            ] );

//            $this->add_inline_editing_attributes( 'text', 'none' );
            ?>
            <span <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
                    <?php if ( ! empty( $settings['icon'] ) || ! empty( $settings['infobox_button_selected_icon']['value'] ) ) : ?>
                    <span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
                            <?php if ( $is_new || $migrated ) :
                                    Icons_Manager::render_icon( $settings['infobox_button_selected_icon'], [ 'aria-hidden' => 'true' ] );
                            else : ?>
                                    <i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
                            <?php endif; ?>
                    </span>
                    <?php endif; ?>
                    <span <?php echo $this->get_render_attribute_string( 'text' ); ?>><?php echo $settings['infobox_button_text']; ?></span>
            </span>
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

        $icon_tag = 'span';

        if ( ! isset( $settings['add_icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
                // add old default
                $settings['add_icon'] = 'fa fa-star';
        }

        $has_icon = ! empty( $settings['add_icon'] );

        if ( $has_icon ) {
                $this->add_render_attribute( 'i', 'class', $settings['add_icon'] );
                $this->add_render_attribute( 'i', 'aria-hidden', 'true' );
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

        if ( $has_icon || 'image' == $icon_style ) : ?>
        <div class="mc-info-box-icon">
            <?php if( $has_icon ) : ?>
            <<?php echo implode( ' ', [ $icon_tag, $icon_attributes ] ); ?>>
            <?php
            if ( $is_new || $migrated ) {
                    Icons_Manager::render_icon( $add_icon, [ 'aria-hidden' => 'true' ] );
            } elseif ( ! empty( $settings['add_icon'] ) ) {
                    ?><i <?php echo $this->get_render_attribute_string( 'i' ); ?>></i><?php
            }
            ?>
            </<?php echo $icon_tag; ?>>
            <?php elseif( $svg ) : ?>
                <img class="infobox-image" src="<?php echo esc_url( $svg );?>" alt="" />
            <?php elseif( 'image' == $icon_style ) : ?>
                <img class="infobox-image" src="<?php echo esc_url( $add_image );?>" alt="" />
            <?php endif; ?>
        </div>
        <?php endif;
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
            $this->add_render_attribute( 'description_text', 'class', 'elementor-icon-box-description' );
            $this->add_inline_editing_attributes( 'title_text', 'none' );
            $this->add_inline_editing_attributes( 'description_text' );
            
            $has_button_link = ! empty( $settings['infobox_button_link']['url'] );
            
            if( $has_button_link ) {
                $this->add_link_attributes( 'infobox_button_link', $settings['infobox_button_link'] );
                $this->add_render_attribute( 'infobox_button_link', 'class', 'elementor-button-link' );
                
                $this->add_render_attribute( 'infobox_button_wrapper', 'class', 'infobox-button-wrapper' );
            }
            
            $this->add_render_attribute( 'infobox_button_link', 'class', 'elementor-button' );
            $this->add_render_attribute( 'infobox_button_link', 'role', 'button' );
        
    ?>
    <div class="mc-info-box-wrapper <?php echo esc_attr( 'elementor-animation-' . $settings['hover_animation'] ); ?>">
        <?php $this->get_image_icon(); ?>
        <div class="mc-info-box-content">
            <<?php echo esc_attr( $settings['title_size'] ); ?> class="elementor-icon-box-title">
                    <<?php echo implode( ' ', [ $icon_tag ] ); ?><?php echo $this->get_render_attribute_string( 'title_text' ); ?>><?php echo $settings['title_text']; ?></<?php echo $icon_tag; ?>>
            </<?php echo esc_attr( $settings['title_size'] ); ?>>
            <?php if ( ! ultraaddons_widget_data_is_empty( $settings['description_text'] ) ) : ?>
            <p <?php echo $this->get_render_attribute_string( 'description_text' ); ?>><?php echo $settings['description_text']; ?></p>
            <?php endif; ?>
        </div>
        <?php if ( $has_button_link ) : ?>
        <div <?php echo $this->get_render_attribute_string( 'infobox_button_wrapper' ); ?>>
                <a <?php echo $this->get_render_attribute_string( 'infobox_button_link' ); ?>>
                        <?php $this->render_text(); ?>
                </a>
        </div>
        <?php endif; ?>
    </div>
    <?php
        
    }
    
//    protected function _content_template() {
    
//    }
}
