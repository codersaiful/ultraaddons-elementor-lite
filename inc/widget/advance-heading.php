<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * UltraAddons Advance Heading / Dual Color Heading Widget
 *
 * Feature-rich Dual Color Heading widget with multiple layouts, dual/multi-colored titles,
 * gradient text effects, icons, custom separators, and rich sub-text styling.
 *
 * @since 1.2.0
 */
class Advance_Heading extends Base {

    /**
     * Constructor — register style dependency
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        wp_register_style(
            'ultraaddons-advance-heading',
            ULTRA_ADDONS_ASSETS . 'css/widgets/advance-heading.css',
            [],
            ULTRA_ADDONS_VERSION,
            'all'
        );
        wp_enqueue_style( 'ultraaddons-advance-heading' );
    }

    public function get_style_depends() {
        return [ 'ultraaddons-advance-heading' ];
    }

    public function get_keywords() {
        return [ 'ultraaddons-elementor-lite', 'ua', 'heading', 'header', 'title', 'dual heading', 'dual color', 'advance heading' ];
    }

    /**
     * Register all controls
     */
    protected function register_controls() {
        // Content Tab
        $this->content_settings_controls();
        $this->content_separator_controls();

        // Style Tab
        $this->style_container_controls();
        $this->style_icon_controls();
        $this->style_title_controls();
        $this->style_separator_controls();
    }

    /*==========================================================================
     * CONTENT TAB — Content Settings
     *========================================================================*/
    protected function content_settings_controls() {
        $this->start_controls_section(
            '_ua_dch_content_section',
            [
                'label' => esc_html__( 'Content', 'ultraaddons-elementor-lite' ),
            ]
        );

        // Layout Style
        $this->add_control(
            '_ua_dch_type',
            [
                'label'   => esc_html__( 'Style', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'dch-default',
                'options' => [
                    'dch-default'               => esc_html__( 'Default', 'ultraaddons-elementor-lite' ),
                    'dch-icon-on-top'           => esc_html__( 'Icon on top', 'ultraaddons-elementor-lite' ),
                    'dch-icon-subtext-on-top'   => esc_html__( 'Icon & sub-text on top', 'ultraaddons-elementor-lite' ),
                    'dch-subtext-on-top'        => esc_html__( 'Sub-text on top', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        // Separator Toggle
        $this->add_control(
            '_ua_show_dch_separator',
            [
                'label'        => esc_html__( 'Separator', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        // Icon Toggle
        $this->add_control(
            '_ua_show_dch_icon',
            [
                'label'        => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
            ]
        );

        // Icon Selector
        $this->add_control(
            '_ua_dch_icon',
            [
                'label'            => esc_html__( 'Select Icon', 'ultraaddons-elementor-lite' ),
                'type'             => Controls_Manager::ICONS,
                'fa4compatibility' => '_ua_dch_icon_old',
                'default'          => [
                    'value'   => 'fas fa-feather-alt',
                    'library' => 'fa-solid',
                ],
                'condition'        => [
                    '_ua_show_dch_icon' => 'yes',
                ],
            ]
        );

        // Title Section Heading
        $this->add_control(
            '_ua_dch_title_heading_control',
            [
                'label'     => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        // Enable Multiple Headings (Repeater)
        $this->add_control(
            '_ua_dch_enable_multiple_titles',
            [
                'label'        => esc_html__( 'Enable Multiple Headings', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'return_value' => 'yes',
            ]
        );

        // First Part (Dual mode)
        $this->add_control(
            '_ua_dch_first_title',
            [
                'label'       => esc_html__( 'First Part', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => esc_html__( 'Dual Color', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'condition'   => [
                    '_ua_dch_enable_multiple_titles!' => 'yes',
                ],
            ]
        );

        // Last Part (Dual mode)
        $this->add_control(
            '_ua_dch_last_title',
            [
                'label'       => esc_html__( 'Last Part', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => esc_html__( 'Heading', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'condition'   => [
                    '_ua_dch_enable_multiple_titles!' => 'yes',
                ],
            ]
        );

        // Multiple Titles Repeater
        $multiple_titles = new Repeater();

        $multiple_titles->add_control(
            '_ua_dch_title_item',
            [
                'label'       => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $multiple_titles->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => '_ua_dch_title_item_typography',
                'selector' => '{{WRAPPER}} .ua-dual-header .ua-dch-title .ua-dch-title-text{{CURRENT_ITEM}}',
            ]
        );

        $multiple_titles->add_control(
            '_ua_dch_title_item_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-dual-header .ua-dch-title .ua-dch-title-text{{CURRENT_ITEM}}' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    '_ua_dch_title_item_use_gradient!' => 'yes',
                ],
            ]
        );

        $multiple_titles->add_control(
            '_ua_dch_title_item_use_gradient',
            [
                'label'        => esc_html__( 'Use Gradient Color', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
            ]
        );

        $multiple_titles->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'           => '_ua_dch_title_item_gradient',
                'types'          => [ 'gradient' ],
                'selector'       => '{{WRAPPER}} .ua-dual-header .ua-dch-title .ua-dch-title-text{{CURRENT_ITEM}}',
                'fields_options' => [
                    'background' => [ 'default' => 'gradient' ],
                    'color'      => [ 'default' => '#13c392' ],
                    'color_b'    => [ 'default' => '#3f51b5' ],
                ],
                'condition'      => [
                    '_ua_dch_title_item_use_gradient' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_dch_multiple_titles',
            [
                'label'       => '',
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $multiple_titles->get_controls(),
                'default'     => [
                    [
                        '_ua_dch_title_item' => esc_html__( 'Awesome', 'ultraaddons-elementor-lite' ),
                    ],
                    [
                        '_ua_dch_title_item'       => esc_html__( 'Dual Heading', 'ultraaddons-elementor-lite' ),
                        '_ua_dch_title_item_color' => '#13c392',
                    ],
                    [
                        '_ua_dch_title_item'              => esc_html__( 'Design', 'ultraaddons-elementor-lite' ),
                        '_ua_dch_title_item_use_gradient' => 'yes',
                    ],
                ],
                'title_field' => '{{{ _ua_dch_title_item }}}',
                'button_text' => esc_html__( 'Add Title Segment', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    '_ua_dch_enable_multiple_titles' => 'yes',
                ],
            ]
        );

        // HTML Tag
        $this->add_control(
            '_ua_dch_title_tag',
            [
                'label'       => esc_html__( 'HTML Tag', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::CHOOSE,
                'label_block' => true,
                'default'     => 'h2',
                'options'     => [
                    'h1'   => [ 'title' => 'H1', 'text' => 'H1' ],
                    'h2'   => [ 'title' => 'H2', 'text' => 'H2' ],
                    'h3'   => [ 'title' => 'H3', 'text' => 'H3' ],
                    'h4'   => [ 'title' => 'H4', 'text' => 'H4' ],
                    'h5'   => [ 'title' => 'H5', 'text' => 'H5' ],
                    'h6'   => [ 'title' => 'H6', 'text' => 'H6' ],
                    'span' => [ 'title' => 'SPAN', 'text' => 'SPAN' ],
                    'p'    => [ 'title' => 'P', 'text' => 'P' ],
                    'div'  => [ 'title' => 'DIV', 'text' => 'DIV' ],
                ],
                'toggle'      => false,
            ]
        );

        // Sub Text (WYSIWYG)
        $this->add_control(
            '_ua_dch_subtext',
            [
                'label'       => esc_html__( 'Sub Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::WYSIWYG,
                'label_block' => true,
                'default'     => esc_html__( 'Insert a meaningful line to describe or highlight your headline message.', 'ultraaddons-elementor-lite' ),
                'separator'   => 'before',
                'dynamic'     => [ 'active' => true ],
            ]
        );

        // Alignment
        $this->add_responsive_control(
            '_ua_dch_alignment',
            [
                'label'        => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::CHOOSE,
                'options'      => [
                    'left'   => [
                        'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'      => 'center',
                'prefix_class' => 'ua-dual-header%s-align-',
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * CONTENT TAB — Separator Settings
     *========================================================================*/
    protected function content_separator_controls() {
        $this->start_controls_section(
            '_ua_dch_separator_content_section',
            [
                'label'     => esc_html__( 'Separator', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    '_ua_show_dch_separator' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_dch_separator_position',
            [
                'label'   => esc_html__( 'Position', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'after_title',
                'options' => [
                    'before_title' => [
                        'title' => esc_html__( 'Before Title', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'after_title'  => [
                        'title' => esc_html__( 'After Title', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ],
                ],
                'toggle'  => false,
            ]
        );

        $this->add_control(
            '_ua_dch_separator_type',
            [
                'label'   => esc_html__( 'Type', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'line',
                'options' => [
                    'line' => [
                        'title' => esc_html__( 'Line', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-e-divider',
                    ],
                    'icon' => [
                        'title' => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-star',
                    ],
                ],
                'toggle'  => false,
            ]
        );

        $this->add_control(
            '_ua_dch_separator_icon',
            [
                'label'            => esc_html__( 'Separator Icon', 'ultraaddons-elementor-lite' ),
                'type'             => Controls_Manager::ICONS,
                'fa4compatibility' => '_ua_dch_separator_icon_old',
                'default'          => [
                    'value'   => 'fas fa-star',
                    'library' => 'fa-solid',
                ],
                'condition'        => [
                    '_ua_dch_separator_type' => 'icon',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Container Style
     *========================================================================*/
    protected function style_container_controls() {
        $this->start_controls_section(
            '_ua_dch_container_style_section',
            [
                'label' => esc_html__( 'Container', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            '_ua_dch_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .ua-dual-header' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_dch_container_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-dual-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_dch_container_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-dual-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => '_ua_dch_border',
                'label'    => esc_html__( 'Border', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-dual-header',
            ]
        );

        $this->add_responsive_control(
            '_ua_dch_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-dual-header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => '_ua_dch_box_shadow',
                'selector' => '{{WRAPPER}} .ua-dual-header',
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Icon Style
     *========================================================================*/
    protected function style_icon_controls() {
        $this->start_controls_section(
            '_ua_dch_icon_style_section',
            [
                'label'     => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    '_ua_show_dch_icon' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_dch_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 36,
                ],
                'range'      => [
                    'px' => [ 'min' => 10, 'max' => 200, 'step' => 1 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-dual-header .ua-dch-icon-wrap i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-dual-header .ua-dch-icon-wrap svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-dual-header .ua-dch-svg-icon svg'  => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_dch_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#13c392',
                'selectors' => [
                    '{{WRAPPER}} .ua-dual-header .ua-dch-icon-wrap i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-dual-header .ua-dch-icon-wrap svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .ua-dual-header .ua-dch-svg-icon svg'  => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_dch_icon_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-dual-header .ua-dch-icon-wrap, {{WRAPPER}} .ua-dual-header .ua-dch-svg-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Color & Typography
     *========================================================================*/
    protected function style_title_controls() {
        $this->start_controls_section(
            '_ua_dch_title_style_section',
            [
                'label' => esc_html__( 'Color & Typography', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Title Section Label
        $this->add_control(
            '_ua_dch_title_style_heading',
            [
                'label' => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        // Common Color (for multiple titles mode)
        $this->add_control(
            '_ua_dch_title_common_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#222222',
                'selectors' => [
                    '{{WRAPPER}} .ua-dual-header .ua-dch-title' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    '_ua_dch_enable_multiple_titles' => 'yes',
                ],
            ]
        );

        // Base Title Color (for dual mode)
        $this->add_control(
            '_ua_dch_base_title_color',
            [
                'label'     => esc_html__( 'Main Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#222222',
                'selectors' => [
                    '{{WRAPPER}} .ua-dual-header .ua-dch-title' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    '_ua_dch_enable_multiple_titles!' => 'yes',
                ],
            ]
        );

        // Dual Color Type Selector (Solid vs Gradient)
        $this->add_control(
            '_ua_dch_dual_color_type',
            [
                'label'     => esc_html__( 'Dual Color Mode', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'solid-color'    => [
                        'title' => esc_html__( 'Solid Color', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-paint-brush',
                    ],
                    'gradient-color' => [
                        'title' => esc_html__( 'Gradient Color', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-barcode',
                    ],
                ],
                'default'   => 'solid-color',
                'toggle'    => false,
                'condition' => [
                    '_ua_dch_enable_multiple_titles!' => 'yes',
                ],
            ]
        );

        // Solid Color for First Part
        $this->add_control(
            '_ua_dch_dual_solid_color',
            [
                'label'     => esc_html__( 'First Part Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#13c392',
                'selectors' => [
                    '{{WRAPPER}} .ua-dual-header .ua-dch-title span.ua-dch-title-lead' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    '_ua_dch_dual_color_type'         => 'solid-color',
                    '_ua_dch_enable_multiple_titles!' => 'yes',
                ],
            ]
        );

        // Gradient First Color
        $this->add_control(
            '_ua_dch_gradient_color_1',
            [
                'label'     => esc_html__( 'Gradient First Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#13c392',
                'selectors' => [
                    '{{WRAPPER}} .ua-dual-header .ua-dch-title .gradient-color' => '--ua-dch-gradient-1: {{VALUE}};',
                ],
                'condition' => [
                    '_ua_dch_dual_color_type'         => 'gradient-color',
                    '_ua_dch_enable_multiple_titles!' => 'yes',
                ],
            ]
        );

        // Gradient Second Color
        $this->add_control(
            '_ua_dch_gradient_color_2',
            [
                'label'     => esc_html__( 'Gradient Second Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#3f51b5',
                'selectors' => [
                    '{{WRAPPER}} .ua-dual-header .ua-dch-title .gradient-color' => '--ua-dch-gradient-2: {{VALUE}};',
                ],
                'condition' => [
                    '_ua_dch_dual_color_type'         => 'gradient-color',
                    '_ua_dch_enable_multiple_titles!' => 'yes',
                ],
            ]
        );

        // Title Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => '_ua_dch_title_typography',
                'global'   => [ 'default' => Global_Typography::TYPOGRAPHY_PRIMARY ],
                'selector' => '{{WRAPPER}} .ua-dual-header .ua-dch-title, {{WRAPPER}} .ua-dual-header .ua-dch-title span',
            ]
        );

        // Sub Text Section Label
        $this->add_control(
            '_ua_dch_subtext_style_heading',
            [
                'label'     => esc_html__( 'Sub Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            '_ua_dch_subtext_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .ua-dual-header .ua-dch-subtext' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => '_ua_dch_subtext_typography',
                'global'   => [ 'default' => Global_Typography::TYPOGRAPHY_TEXT ],
                'selector' => '{{WRAPPER}} .ua-dual-header .ua-dch-subtext, {{WRAPPER}} .ua-dual-header .ua-dch-subtext p',
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Separator Style
     *========================================================================*/
    protected function style_separator_controls() {
        $this->start_controls_section(
            '_ua_dch_separator_style_section',
            [
                'label'     => esc_html__( 'Separator', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    '_ua_show_dch_separator' => 'yes',
                ],
            ]
        );

        // Alignment
        $this->add_control(
            '_ua_dch_separator_alignment',
            [
                'label'     => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center'     => [
                        'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'flex-end'   => [
                        'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .ua-dch-separator-wrap' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        // Margin
        $this->add_responsive_control(
            '_ua_dch_separator_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-dch-separator-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Icon Size (Icon type)
        $this->add_responsive_control(
            '_ua_dch_separator_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => [ 'size' => 20, 'unit' => 'px' ],
                'range'      => [
                    'px' => [ 'min' => 10, 'max' => 100, 'step' => 1 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-dch-separator-wrap i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-dch-separator-wrap svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    '_ua_dch_separator_type' => 'icon',
                ],
            ]
        );

        // Icon Color (Icon type)
        $this->add_control(
            '_ua_dch_separator_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#13c392',
                'selectors' => [
                    '{{WRAPPER}} .ua-dch-separator-wrap i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-dch-separator-wrap svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    '_ua_dch_separator_type' => 'icon',
                ],
            ]
        );

        // Distance Between Lines (Line type)
        $this->add_responsive_control(
            '_ua_dch_separator_distance',
            [
                'label'      => esc_html__( 'Distance Between Lines', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'default'    => [ 'size' => 5, 'unit' => 'px' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 100, 'step' => 1 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-dch-separator-wrap .separator-one' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-dch-separator-wrap .separator-two' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    '_ua_dch_separator_type' => 'line',
                ],
            ]
        );

        // Line Tabs (Left / Right)
        $this->start_controls_tabs(
            '_ua_dch_separator_tabs',
            [
                'condition' => [
                    '_ua_dch_separator_type' => 'line',
                ],
            ]
        );

        // Left Line Tab
        $this->start_controls_tab(
            '_ua_dch_separator_left_tab',
            [ 'label' => esc_html__( 'Left Line', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_responsive_control(
            '_ua_dch_separator_left_width',
            [
                'label'      => esc_html__( 'Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'default'    => [ 'size' => 15, 'unit' => '%' ],
                'range'      => [
                    'px' => [ 'min' => 10, 'max' => 500, 'step' => 5 ],
                    '%'  => [ 'min' => 1, 'max' => 100 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-dch-separator-wrap .separator-one' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_dch_separator_left_height',
            [
                'label'      => esc_html__( 'Height', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default'    => [ 'size' => 4, 'unit' => 'px' ],
                'range'      => [
                    'px' => [ 'min' => 1, 'max' => 50, 'step' => 1 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-dch-separator-wrap .separator-one' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_dch_separator_left_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-dch-separator-wrap .separator-one' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => '_ua_dch_separator_left_bg',
                'label'    => esc_html__( 'Background', 'ultraaddons-elementor-lite' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-dch-separator-wrap .separator-one',
            ]
        );

        $this->end_controls_tab();

        // Right Line Tab
        $this->start_controls_tab(
            '_ua_dch_separator_right_tab',
            [ 'label' => esc_html__( 'Right Line', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_responsive_control(
            '_ua_dch_separator_right_width',
            [
                'label'      => esc_html__( 'Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'default'    => [ 'size' => 15, 'unit' => '%' ],
                'range'      => [
                    'px' => [ 'min' => 10, 'max' => 500, 'step' => 5 ],
                    '%'  => [ 'min' => 1, 'max' => 100 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-dch-separator-wrap .separator-two' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_dch_separator_right_height',
            [
                'label'      => esc_html__( 'Height', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default'    => [ 'size' => 4, 'unit' => 'px' ],
                'range'      => [
                    'px' => [ 'min' => 1, 'max' => 50, 'step' => 1 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-dch-separator-wrap .separator-two' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_dch_separator_right_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-dch-separator-wrap .separator-two' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => '_ua_dch_separator_right_bg',
                'label'    => esc_html__( 'Background', 'ultraaddons-elementor-lite' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-dch-separator-wrap .separator-two',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /*==========================================================================
     * RENDER — Frontend & Editor Output
     *========================================================================*/
    protected function render() {
        $settings = $this->get_settings_for_display();

        $icon_migrated = isset( $settings['__fa4_migrated']['_ua_dch_icon'] );
        $icon_is_new   = empty( $settings['_ua_dch_icon_old'] );
        $show_icon     = ( ! empty( $settings['_ua_show_dch_icon'] ) && $settings['_ua_show_dch_icon'] === 'yes' );

        // Separator HTML Markup
        $separator_markup = '';
        if ( ! empty( $settings['_ua_show_dch_separator'] ) && $settings['_ua_show_dch_separator'] === 'yes' ) {
            $separator_markup .= '<div class="ua-dch-separator-wrap">';
            if ( ! empty( $settings['_ua_dch_separator_type'] ) && $settings['_ua_dch_separator_type'] === 'icon' ) {
                ob_start();
                Icons_Manager::render_icon( $settings['_ua_dch_separator_icon'], [ 'aria-hidden' => 'true' ] );
                $separator_markup .= ob_get_clean();
            } else {
                $separator_markup .= '<span class="separator-one"></span><span class="separator-two"></span>';
            }
            $separator_markup .= '</div>';
        }

        // Title HTML Tag Validation
        $allowed_tags = [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'span', 'p', 'div' ];
        $title_tag    = ! empty( $settings['_ua_dch_title_tag'] ) && in_array( $settings['_ua_dch_title_tag'], $allowed_tags, true )
            ? $settings['_ua_dch_title_tag']
            : 'h2';

        // Title HTML Construction
        $title_html = '<' . esc_attr( $title_tag ) . ' class="ua-dch-title">';

        if ( ! empty( $settings['_ua_dch_enable_multiple_titles'] ) && $settings['_ua_dch_enable_multiple_titles'] === 'yes' ) {
            if ( ! empty( $settings['_ua_dch_multiple_titles'] ) && is_array( $settings['_ua_dch_multiple_titles'] ) ) {
                foreach ( $settings['_ua_dch_multiple_titles'] as $title_item ) {
                    $classes = 'ua-dch-title-text elementor-repeater-item-' . esc_attr( $title_item['_id'] );
                    if ( ! empty( $title_item['_ua_dch_title_item_use_gradient'] ) && $title_item['_ua_dch_title_item_use_gradient'] === 'yes' ) {
                        $classes .= ' ua-dch-title-gradient';
                    }
                    $title_html .= '<span class="' . esc_attr( $classes ) . '">' . esc_html( $title_item['_ua_dch_title_item'] ) . '</span> ';
                }
            }
        } else {
            $dual_mode  = ! empty( $settings['_ua_dch_dual_color_type'] ) ? $settings['_ua_dch_dual_color_type'] : 'solid-color';
            $first_part = ! empty( $settings['_ua_dch_first_title'] ) ? $settings['_ua_dch_first_title'] : '';
            $last_part  = ! empty( $settings['_ua_dch_last_title'] ) ? $settings['_ua_dch_last_title'] : '';

            $title_html .= '<span class="ua-dch-title-text ua-dch-title-lead ' . esc_attr( $dual_mode ) . '">' . esc_html( $first_part ) . '</span> ';
            $title_html .= '<span class="ua-dch-title-text">' . esc_html( $last_part ) . '</span>';
        }

        $title_html .= '</' . esc_attr( $title_tag ) . '>';

        // Icon Render Helper Function
        $render_icon = function() use ( $show_icon, $settings, $icon_is_new, $icon_migrated ) {
            if ( ! $show_icon ) {
                return;
            }
            if ( $icon_is_new || $icon_migrated ) {
                echo '<span class="ua-dch-svg-icon">';
                Icons_Manager::render_icon( $settings['_ua_dch_icon'], [ 'aria-hidden' => 'true' ] );
                echo '</span>';
            } elseif ( ! empty( $settings['_ua_dch_icon_old'] ) ) {
                echo '<span class="ua-dch-icon-wrap"><i class="' . esc_attr( $settings['_ua_dch_icon_old'] ) . '"></i></span>';
            }
        };

        $sep_position = ! empty( $settings['_ua_dch_separator_position'] ) ? $settings['_ua_dch_separator_position'] : 'after_title';
        $dch_type     = ! empty( $settings['_ua_dch_type'] ) ? $settings['_ua_dch_type'] : 'dch-default';
        ?>
        <div class="ua-dual-header">

            <?php if ( $dch_type === 'dch-default' ) : ?>

                <?php echo ( $sep_position === 'before_title' ? $separator_markup : '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php echo $title_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php echo ( $sep_position === 'after_title' ? $separator_markup : '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

                <?php if ( ! empty( $settings['_ua_dch_subtext'] ) ) : ?>
                    <div class="ua-dch-subtext"><?php echo wp_kses_post( $settings['_ua_dch_subtext'] ); ?></div>
                <?php endif; ?>

                <?php $render_icon(); ?>

            <?php elseif ( $dch_type === 'dch-icon-on-top' ) : ?>

                <?php $render_icon(); ?>

                <?php echo ( $sep_position === 'before_title' ? $separator_markup : '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php echo $title_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php echo ( $sep_position === 'after_title' ? $separator_markup : '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

                <?php if ( ! empty( $settings['_ua_dch_subtext'] ) ) : ?>
                    <div class="ua-dch-subtext"><?php echo wp_kses_post( $settings['_ua_dch_subtext'] ); ?></div>
                <?php endif; ?>

            <?php elseif ( $dch_type === 'dch-icon-subtext-on-top' ) : ?>

                <?php $render_icon(); ?>

                <?php if ( ! empty( $settings['_ua_dch_subtext'] ) ) : ?>
                    <div class="ua-dch-subtext"><?php echo wp_kses_post( $settings['_ua_dch_subtext'] ); ?></div>
                <?php endif; ?>

                <?php echo ( $sep_position === 'before_title' ? $separator_markup : '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php echo $title_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php echo ( $sep_position === 'after_title' ? $separator_markup : '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

            <?php elseif ( $dch_type === 'dch-subtext-on-top' ) : ?>

                <?php if ( ! empty( $settings['_ua_dch_subtext'] ) ) : ?>
                    <div class="ua-dch-subtext"><?php echo wp_kses_post( $settings['_ua_dch_subtext'] ); ?></div>
                <?php endif; ?>

                <?php echo ( $sep_position === 'before_title' ? $separator_markup : '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php echo $title_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php echo ( $sep_position === 'after_title' ? $separator_markup : '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

                <?php $render_icon(); ?>

            <?php endif; ?>

        </div>
        <?php
    }
}
