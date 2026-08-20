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
use Elementor\Plugin;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * UltraAddons Advanced Tabs Widget
 *
 * A modern, responsive, and accessible Advanced Tabs widget for Elementor.
 * Supports horizontal & vertical layouts, inline/stacked icons & images,
 * WYSIWYG content, Elementor Saved Templates, date-time scheduling,
 * URL hash deep-linking with auto-scroll, caret styling, and nested widget refreshing.
 *
 * @since 1.2.0
 * @package UltraAddons
 */
class Advanced_Tabs extends Base {

    /**
     * Constructor — register style dependency
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        wp_register_style(
            'ultraaddons-advanced-tabs',
            ULTRA_ADDONS_ASSETS . 'css/widgets/advanced-tabs.css',
            [],
            ULTRA_ADDONS_VERSION,
            'all'
        );
        wp_enqueue_style( 'ultraaddons-advanced-tabs' );
    }

    /**
     * Style dependency
     */
    public function get_style_depends() {
        return [ 'ultraaddons-advanced-tabs' ];
    }

    /**
     * Widget Title
     */
    public function get_title() {
        return esc_html__( 'Advanced Tabs', 'ultraaddons-elementor-lite' );
    }

    /**
     * Widget Icon in Elementor panel
     */
    public function get_icon() {
        return 'ultraaddons eicon-tabs';
    }

    /**
     * Widget keywords
     */
    public function get_keywords() {
        return [ 'ultraaddons-elementor-lite', 'ua', 'tab', 'tabs', 'advanced tabs', 'panel', 'navigation', 'toggle', 'group', 'content tabs' ];
    }

    /**
     * Retrieve Elementor saved templates list
     */
    protected function get_elementor_templates() {
        $templates = Plugin::instance()->templates_manager->get_source( 'local' )->get_items();
        $options   = [ '0' => esc_html__( '— Select Template —', 'ultraaddons-elementor-lite' ) ];

        if ( ! empty( $templates ) ) {
            foreach ( $templates as $template ) {
                $options[ $template['template_id'] ] = $template['title'] . ' (' . $template['type'] . ')';
            }
        }

        return $options;
    }

    /**
     * Register all widget controls
     */
    protected function register_controls() {
        // Content Tab
        $this->content_general_controls();
        $this->content_tabs_controls();

        // Style Tab
        $this->style_general_controls();
        $this->style_tab_title_controls();
        $this->style_caret_controls();
        $this->style_content_controls();
        $this->style_responsive_controls();
    }

    /*==========================================================================
     * CONTENT TAB — General Settings
     *========================================================================*/
    protected function content_general_controls() {
        $this->start_controls_section(
            'ua_section_adv_tabs_general_settings',
            [
                'label' => esc_html__( 'General Settings', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ua_adv_tabs_layout',
            [
                'label'   => esc_html__( 'Layout', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'ua-tabs-horizontal',
                'options' => [
                    'ua-tabs-horizontal' => esc_html__( 'Horizontal', 'ultraaddons-elementor-lite' ),
                    'ua-tabs-vertical'   => esc_html__( 'Vertical', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'ua_adv_tabs_icon_show',
            [
                'label'        => esc_html__( 'Enable Icon', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'ua_adv_tabs_icon_position',
            [
                'label'     => esc_html__( 'Icon Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'ua-tab-inline-icon',
                'options'   => [
                    'ua-tab-inline-icon'  => esc_html__( 'Inline', 'ultraaddons-elementor-lite' ),
                    'ua-tab-top-icon'     => esc_html__( 'Stacked (Top)', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'ua_adv_tabs_icon_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ua_adv_tabs_icon_alignment',
            [
                'label'       => esc_html__( 'Icon Alignment', 'ultraaddons-elementor-lite' ),
                'description' => esc_html__( 'Set icon position before or after the tab title.', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::CHOOSE,
                'default'     => 'before',
                'options'     => [
                    'before' => [
                        'title' => esc_html__( 'Before', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'after'  => [
                        'title' => esc_html__( 'After', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'condition'   => [
                    'ua_adv_tabs_icon_show'     => 'yes',
                    'ua_adv_tabs_icon_position' => 'ua-tab-inline-icon',
                ],
            ]
        );

        $this->add_control(
            'ua_adv_tabs_default_state',
            [
                'label'       => esc_html__( 'Initial State', 'ultraaddons-elementor-lite' ),
                'description' => esc_html__( 'Choose whether tabs are open or collapsed by default on page load.', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'first_open',
                'options'     => [
                    'first_open'    => esc_html__( 'First Tab Open', 'ultraaddons-elementor-lite' ),
                    'all_collapsed' => esc_html__( 'All Collapsed (Closed)', 'ultraaddons-elementor-lite' ),
                    'custom'        => esc_html__( 'Custom (Set per Tab item below)', 'ultraaddons-elementor-lite' ),
                    'all_open'      => esc_html__( 'All Tabs Open', 'ultraaddons-elementor-lite' ),
                ],
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'ua_adv_tabs_toggle_tab',
            [
                'label'        => esc_html__( 'Toggle Tab', 'ultraaddons-elementor-lite' ),
                'description'  => esc_html__( 'Enables clicking active tab to expand and collapse.', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'no',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'ua_adv_tabs_scroll_onclick',
            [
                'label'        => esc_html__( 'Scroll on Click', 'ultraaddons-elementor-lite' ),
                'description'  => esc_html__( 'Smoothly scroll the page to the tab content on click.', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'no',
                'return_value' => 'yes',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'ua_adv_tabs_scroll_speed',
            [
                'label'     => esc_html__( 'Scroll Speed (ms)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 300,
                'min'       => 50,
                'max'       => 2000,
                'condition' => [
                    'ua_adv_tabs_scroll_onclick' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ua_adv_tabs_custom_id_offset',
            [
                'label'       => esc_html__( 'Custom ID offset (px)', 'ultraaddons-elementor-lite' ),
                'description' => esc_html__( 'Offset for fixed headers when scrolling.', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 0,
                'condition'   => [
                    'ua_adv_tabs_scroll_onclick' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * CONTENT TAB — Tabs (Repeater)
     *========================================================================*/
    protected function content_tabs_controls() {
        $this->start_controls_section(
            'ua_section_adv_tabs_content_settings',
            [
                'label' => esc_html__( 'Content Settings', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'ua_adv_tabs_tab_default_active',
            [
                'label'        => esc_html__( 'Active as Default', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'no',
                'return_value' => 'yes',
            ]
        );

        $repeater->add_control(
            'ua_adv_tabs_tab_show_as_scheduled',
            [
                'label'        => esc_html__( 'Active as Scheduled', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'no',
                'return_value' => 'yes',
                'description'  => esc_html__( 'When enabled, this tab will become active automatically if the current date matches the scheduled date/time.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $repeater->add_control(
            'ua_adv_tabs_schedule_date',
            [
                'label'          => esc_html__( 'Start Date & Time', 'ultraaddons-elementor-lite' ),
                'type'           => Controls_Manager::DATE_TIME,
                'default'        => gmdate( 'Y-m-d H:i', current_time( 'timestamp', 0 ) ),
                'picker_options' => [
                    'enableTime' => true,
                    'altInput'   => true,
                    'altFormat'  => 'M j, Y h:i K',
                    'dateFormat' => 'Y-m-d H:i',
                ],
                'condition'      => [
                    'ua_adv_tabs_tab_show_as_scheduled' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'ua_adv_tabs_schedule_end_date',
            [
                'label'          => esc_html__( 'End Date & Time', 'ultraaddons-elementor-lite' ),
                'type'           => Controls_Manager::DATE_TIME,
                'default'        => '',
                'picker_options' => [
                    'enableTime' => true,
                    'altInput'   => true,
                    'altFormat'  => 'M j, Y h:i K',
                    'dateFormat' => 'Y-m-d H:i',
                ],
                'condition'      => [
                    'ua_adv_tabs_tab_show_as_scheduled' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'ua_adv_tabs_icon_type',
            [
                'label'       => esc_html__( 'Icon Type', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::CHOOSE,
                'default'     => 'icon',
                'options'     => [
                    'none'  => [
                        'title' => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-ban',
                    ],
                    'icon'  => [
                        'title' => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-icon-box',
                    ],
                    'image' => [
                        'title' => esc_html__( 'Image', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-image-bold',
                    ],
                ],
                'separator'   => 'before',
            ]
        );

        $repeater->add_control(
            'ua_adv_tabs_tab_title_icon_new',
            [
                'label'     => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'fas fa-star',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'ua_adv_tabs_icon_type' => 'icon',
                ],
            ]
        );

        $repeater->add_control(
            'ua_adv_tabs_tab_title_image',
            [
                'label'     => esc_html__( 'Image', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::MEDIA,
                'default'   => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'ua_adv_tabs_icon_type' => 'image',
                ],
            ]
        );

        $repeater->add_control(
            'ua_adv_tabs_tab_title',
            [
                'label'       => esc_html__( 'Tab Title', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Tab Title', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'label_block' => true,
                'separator'   => 'before',
            ]
        );

        $repeater->add_control(
            'ua_adv_tabs_tab_title_html_tag',
            [
                'label'   => esc_html__( 'Title HTML Tag', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'span',
                'options' => [
                    'h1'   => 'H1',
                    'h2'   => 'H2',
                    'h3'   => 'H3',
                    'h4'   => 'H4',
                    'h5'   => 'H5',
                    'h6'   => 'H6',
                    'div'  => 'DIV',
                    'span' => 'SPAN',
                    'p'    => 'P',
                ],
            ]
        );

        $repeater->add_control(
            'ua_adv_tabs_text_type',
            [
                'label'   => esc_html__( 'Content Type', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'content',
                'options' => [
                    'content'  => esc_html__( 'Content', 'ultraaddons-elementor-lite' ),
                    'template' => esc_html__( 'Saved Templates', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $repeater->add_control(
            'ua_primary_templates',
            [
                'label'       => esc_html__( 'Choose Template', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'options'     => $this->get_elementor_templates(),
                'default'     => '0',
                'label_block' => true,
                'condition'   => [
                    'ua_adv_tabs_text_type' => 'template',
                ],
            ]
        );

        $repeater->add_control(
            'ua_adv_tabs_tab_content',
            [
                'label'       => esc_html__( 'Tab Content', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::WYSIWYG,
                'default'     => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'condition'   => [
                    'ua_adv_tabs_text_type' => 'content',
                ],
            ]
        );

        $repeater->add_control(
            'ua_adv_tabs_tab_id',
            [
                'label'       => esc_html__( 'Custom ID', 'ultraaddons-elementor-lite' ),
                'description' => esc_html__( 'Custom ID will be added as an anchor tag (e.g. test becomes https://example.com/#test and opens the respective tab directly).', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
            ]
        );

        $this->add_control(
            'ua_adv_tabs_tab',
            [
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'ua_adv_tabs_tab_title'          => esc_html__( 'Tab 1', 'ultraaddons-elementor-lite' ),
                        'ua_adv_tabs_tab_title_icon_new' => [
                            'value'   => 'fas fa-layer-group',
                            'library' => 'fa-solid',
                        ],
                        'ua_adv_tabs_tab_content'        => esc_html__( 'Explore cutting-edge features designed to improve your web design workflow. Organize content cleanly and create engaging experiences effortlessly.', 'ultraaddons-elementor-lite' ),
                    ],
                    [
                        'ua_adv_tabs_tab_title'          => esc_html__( 'Tab 2', 'ultraaddons-elementor-lite' ),
                        'ua_adv_tabs_tab_title_icon_new' => [
                            'value'   => 'fas fa-sliders-h',
                            'library' => 'fa-solid',
                        ],
                        'ua_adv_tabs_tab_content'        => esc_html__( 'Customize every single element with flexible styling controls, responsive spacing, dynamic colors, and smooth animations.', 'ultraaddons-elementor-lite' ),
                    ],
                    [
                        'ua_adv_tabs_tab_title'          => esc_html__( 'Tab 3', 'ultraaddons-elementor-lite' ),
                        'ua_adv_tabs_tab_title_icon_new' => [
                            'value'   => 'fas fa-life-ring',
                            'library' => 'fa-solid',
                        ],
                        'ua_adv_tabs_tab_content'        => esc_html__( 'Enjoy lightweight, highly-optimized performance backed by reliable updates and dedicated support for all your Elementor projects.', 'ultraaddons-elementor-lite' ),
                    ],
                ],
                'title_field' => '{{{ ua_adv_tabs_tab_title }}}',
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — General Style
     *========================================================================*/
    protected function style_general_controls() {
        $this->start_controls_section(
            'ua_section_adv_tabs_style_settings',
            [
                'label' => esc_html__( 'General', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'ua_adv_tabs_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-adv-tabs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_adv_tabs_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-adv-tabs' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_adv_tabs_border',
                'selector' => '{{WRAPPER}} .ua-adv-tabs',
            ]
        );

        $this->add_responsive_control(
            'ua_adv_tabs_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-adv-tabs' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_adv_tabs_box_shadow',
                'selector' => '{{WRAPPER}} .ua-adv-tabs',
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Tab Title (Navigation Bar)
     *========================================================================*/
    protected function style_tab_title_controls() {
        $this->start_controls_section(
            'ua_section_adv_tabs_tab_title_style_settings',
            [
                'label' => esc_html__( 'Tab Title', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_adv_tabs_tab_title_typography',
                'selector' => '{{WRAPPER}} .ua-adv-tabs .ua-tabs-nav > ul li, {{WRAPPER}} .ua-adv-tabs .ua-tabs-nav > ul li .ua-tab-title',
            ]
        );

        $this->add_responsive_control(
            'ua_adv_tabs_title_width',
            [
                'label'      => esc_html__( 'Title Min Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', '%' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 1000 ],
                    'em' => [ 'min' => 0, 'max' => 50 ],
                    '%'  => [ 'min' => 0, 'max' => 100 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-adv-tabs.ua-tabs-vertical > .ua-tabs-nav' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'ua_adv_tabs_layout' => 'ua-tabs-vertical',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_adv_tabs_tab_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => [ 'size' => 16, 'unit' => 'px' ],
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 200 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-adv-tabs .ua-tabs-nav > ul li i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-adv-tabs .ua-tabs-nav > ul li img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                    '{{WRAPPER}} .ua-adv-tabs .ua-tabs-nav > ul li svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_adv_tabs_tab_icon_gap',
            [
                'label'      => esc_html__( 'Icon Gap', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => [ 'size' => 10, 'unit' => 'px' ],
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 100 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-tab-inline-icon li .title-before-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-tab-inline-icon li .title-after-icon'  => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-tab-top-icon li i, {{WRAPPER}} .ua-tab-top-icon li img, {{WRAPPER}} .ua-tab-top-icon li svg' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_adv_tabs_tab_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'    => [
                    'top'      => '14',
                    'right'    => '20',
                    'bottom'   => '14',
                    'left'     => '20',
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-adv-tabs .ua-tabs-nav ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_adv_tabs_tab_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-adv-tabs .ua-tabs-nav ul li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'ua_adv_tabs_header_tabs' );

        // Normal State Tab
        $this->start_controls_tab(
            'ua_adv_tabs_header_normal',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'ua_adv_tabs_tab_bgtype',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-adv-tabs .ua-tabs-nav > ul li.ua-tab-nav-item',
            ]
        );

        $this->add_control(
            'ua_adv_tabs_tab_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .ua-adv-tabs .ua-tabs-nav > ul li'                 => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-adv-tabs .ua-tabs-nav > ul li .ua-tab-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_adv_tabs_tab_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .ua-adv-tabs .ua-tabs-nav > ul li i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-adv-tabs .ua-tabs-nav > ul li svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'ua_adv_tabs_icon_show' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_adv_tabs_tab_border',
                'selector' => '{{WRAPPER}} .ua-adv-tabs .ua-tabs-nav > ul li',
            ]
        );

        $this->add_responsive_control(
            'ua_adv_tabs_tab_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-adv-tabs .ua-tabs-nav > ul li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover State Tab
        $this->start_controls_tab(
            'ua_adv_tabs_header_hover',
            [
                'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'ua_adv_tabs_tab_bgtype_hover',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-adv-tabs .ua-tabs-nav > ul li.ua-tab-nav-item:hover:not(.active)',
            ]
        );

        $this->add_control(
            'ua_adv_tabs_tab_text_color_hover',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-adv-tabs .ua-tabs-nav > ul li:hover'                 => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-adv-tabs .ua-tabs-nav > ul li:hover .ua-tab-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_adv_tabs_tab_icon_color_hover',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-adv-tabs .ua-tabs-nav > ul li:hover i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-adv-tabs .ua-tabs-nav > ul li:hover svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'ua_adv_tabs_icon_show' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_adv_tabs_tab_border_hover',
                'selector' => '{{WRAPPER}} .ua-adv-tabs .ua-tabs-nav > ul li:hover',
            ]
        );

        $this->add_responsive_control(
            'ua_adv_tabs_tab_border_radius_hover',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-adv-tabs .ua-tabs-nav > ul li:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Active State Tab
        $this->start_controls_tab(
            'ua_adv_tabs_header_active',
            [
                'label' => esc_html__( 'Active', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'ua_adv_tabs_tab_bgtype_active',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-adv-tabs .ua-tabs-nav > ul li.active',
            ]
        );

        $this->add_control(
            'ua_adv_tabs_tab_text_color_active',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-adv-tabs .ua-tabs-nav > ul li.active'                 => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-adv-tabs .ua-tabs-nav > ul li.active .ua-tab-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_adv_tabs_tab_icon_color_active',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-adv-tabs .ua-tabs-nav > ul li.active i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-adv-tabs .ua-tabs-nav > ul li.active svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'ua_adv_tabs_icon_show' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_adv_tabs_tab_border_active',
                'selector' => '{{WRAPPER}} .ua-adv-tabs .ua-tabs-nav > ul li.active',
            ]
        );

        $this->add_responsive_control(
            'ua_adv_tabs_tab_border_radius_active',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-adv-tabs .ua-tabs-nav > ul li.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Caret (Active Pointer Arrow)
     *========================================================================*/
    protected function style_caret_controls() {
        $this->start_controls_section(
            'ua_section_adv_tabs_tab_caret_style_settings',
            [
                'label' => esc_html__( 'Caret (Active Pointer)', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'ua_adv_tabs_tab_caret_show',
            [
                'label'        => esc_html__( 'Show Caret on Active Tab', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'ua_adv_tabs_tab_caret_size',
            [
                'label'     => esc_html__( 'Caret Size (px)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [ 'size' => 10 ],
                'range'     => [
                    'px' => [ 'min' => 4, 'max' => 50 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-adv-tabs.ua-tabs-horizontal > .ua-tabs-nav > ul li:after' => 'border-width: {{SIZE}}px; bottom: -{{SIZE}}px;',
                    '{{WRAPPER}} .ua-adv-tabs.ua-tabs-vertical > .ua-tabs-nav > ul li:after'   => 'border-width: {{SIZE}}px; right: -{{SIZE}}px; top: calc(50% - {{SIZE}}px) !important;',
                    '.rtl {{WRAPPER}} .ua-adv-tabs.ua-tabs-vertical > .ua-tabs-nav > ul li:after' => 'right: auto; left: -{{SIZE}}px !important; top: calc(50% - {{SIZE}}px) !important;',
                ],
                'condition' => [
                    'ua_adv_tabs_tab_caret_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ua_adv_tabs_tab_caret_color',
            [
                'label'     => esc_html__( 'Caret Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#444444',
                'selectors' => [
                    '{{WRAPPER}} .ua-adv-tabs.ua-tabs-horizontal > .ua-tabs-nav > ul li:after' => 'border-top-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-adv-tabs.ua-tabs-vertical > .ua-tabs-nav > ul li:after'   => 'border-left-color: {{VALUE}};',
                    '.rtl {{WRAPPER}} .ua-adv-tabs.ua-tabs-vertical > .ua-tabs-nav > ul li:after' => 'border-right-color: {{VALUE}};',
                ],
                'condition' => [
                    'ua_adv_tabs_tab_caret_show' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Content Style
     *========================================================================*/
    protected function style_content_controls() {
        $this->start_controls_section(
            'ua_section_adv_tabs_tab_content_style_settings',
            [
                'label' => esc_html__( 'Content', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'ua_adv_tabs_content_bgtype',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-adv-tabs .ua-tabs-content > div.ua-tab-content-item',
            ]
        );

        $this->add_control(
            'ua_adv_tabs_content_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .ua-adv-tabs .ua-tabs-content > div' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_adv_tabs_content_typography',
                'selector' => '{{WRAPPER}} .ua-adv-tabs .ua-tabs-content > div',
            ]
        );

        $this->add_responsive_control(
            'ua_adv_tabs_content_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'    => [
                    'top'      => '20',
                    'right'    => '20',
                    'bottom'   => '20',
                    'left'     => '20',
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-adv-tabs .ua-tabs-content > div' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_adv_tabs_content_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-adv-tabs .ua-tabs-content > div' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_adv_tabs_content_border',
                'selector' => '{{WRAPPER}} .ua-adv-tabs .ua-tabs-content > div.ua-tab-content-item, {{WRAPPER}} .ua-adv-tabs .ua-tabs-content > div',
            ]
        );

        $this->add_responsive_control(
            'ua_adv_tabs_content_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-adv-tabs .ua-tabs-content'                               => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ua-adv-tabs .ua-tabs-content > div'                         => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ua-adv-tabs .ua-tabs-content > div.ua-tab-content-item'     => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_adv_tabs_content_shadow',
                'selector' => '{{WRAPPER}} .ua-adv-tabs .ua-tabs-content > div.ua-tab-content-item, {{WRAPPER}} .ua-adv-tabs .ua-tabs-content > div',
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Responsive Controls
     *========================================================================*/
    protected function style_responsive_controls() {
        $this->start_controls_section(
            'ua_section_adv_tabs_responsive_controls',
            [
                'label' => esc_html__( 'Responsive Controls', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'ua_adv_tabs_responsive_vertical',
            [
                'label'        => esc_html__( 'Stack Tabs on Mobile', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
                'description'  => esc_html__( 'Stacks navigation items vertically on small mobile screens.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Determine which tab should be active based on date/time scheduling
     *
     * @param array $settings Widget settings
     * @return int|null Index of the matching tab or null
     */
    private function get_scheduled_active_tab( $settings ) {
        if ( empty( $settings['ua_adv_tabs_tab'] ) || ! is_array( $settings['ua_adv_tabs_tab'] ) ) {
            return null;
        }

        $current_timestamp = current_time( 'timestamp' );
        $matching_tabs     = [];

        foreach ( $settings['ua_adv_tabs_tab'] as $index => $tab ) {
            if ( 'yes' === ( $tab['ua_adv_tabs_tab_show_as_scheduled'] ?? 'no' ) && ! empty( $tab['ua_adv_tabs_schedule_date'] ) ) {
                $start_datetime = trim( $tab['ua_adv_tabs_schedule_date'] );
                $end_datetime   = ! empty( $tab['ua_adv_tabs_schedule_end_date'] ) ? trim( $tab['ua_adv_tabs_schedule_end_date'] ) : '';

                $start_timestamp = strtotime( $start_datetime );
                $end_timestamp   = ! empty( $end_datetime ) ? strtotime( $end_datetime ) : null;

                $is_active = false;
                if ( $start_timestamp ) {
                    if ( $end_timestamp ) {
                        $is_active = ( $current_timestamp >= $start_timestamp && $current_timestamp <= $end_timestamp );
                    } else {
                        $is_active = ( $current_timestamp >= $start_timestamp );
                    }
                }

                if ( $is_active ) {
                    $matching_tabs[] = [
                        'index'           => $index,
                        'start_timestamp' => $start_timestamp,
                    ];
                }
            }
        }

        if ( empty( $matching_tabs ) ) {
            return null;
        }

        // Most recent schedule takes priority
        usort( $matching_tabs, function( $a, $b ) {
            return $b['start_timestamp'] - $a['start_timestamp'];
        } );

        return $matching_tabs[0]['index'];
    }

    /*==========================================================================
     * MAIN RENDER METHOD
     *========================================================================*/
    protected function render() {
        $settings = $this->get_settings_for_display();
        $tabs     = ! empty( $settings['ua_adv_tabs_tab'] ) ? $settings['ua_adv_tabs_tab'] : [];

        if ( empty( $tabs ) ) {
            return;
        }

        $layout              = ! empty( $settings['ua_adv_tabs_layout'] ) ? $settings['ua_adv_tabs_layout'] : 'ua-tabs-horizontal';
        $icon_show           = ( 'yes' === ( $settings['ua_adv_tabs_icon_show'] ?? 'yes' ) );
        $icon_position       = ! empty( $settings['ua_adv_tabs_icon_position'] ) ? $settings['ua_adv_tabs_icon_position'] : 'ua-tab-inline-icon';
        $icon_alignment      = ! empty( $settings['ua_adv_tabs_icon_alignment'] ) ? $settings['ua_adv_tabs_icon_alignment'] : 'before';
        $default_state       = ! empty( $settings['ua_adv_tabs_default_state'] ) ? $settings['ua_adv_tabs_default_state'] : 'first_open';
        $toggle_tab          = ( 'yes' === ( $settings['ua_adv_tabs_toggle_tab'] ?? 'no' ) );
        $scroll_onclick      = ( 'yes' === ( $settings['ua_adv_tabs_scroll_onclick'] ?? 'no' ) );
        $scroll_speed        = ! empty( $settings['ua_adv_tabs_scroll_speed'] ) ? (int) $settings['ua_adv_tabs_scroll_speed'] : 300;
        $custom_id_offset    = ! empty( $settings['ua_adv_tabs_custom_id_offset'] ) ? (int) $settings['ua_adv_tabs_custom_id_offset'] : 0;
        $show_caret          = ( 'yes' === ( $settings['ua_adv_tabs_tab_caret_show'] ?? 'yes' ) );
        $stack_mobile        = ( 'yes' === ( $settings['ua_adv_tabs_responsive_vertical'] ?? 'yes' ) );
        $id_int              = substr( $this->get_id_int(), 0, 3 );

        $scheduled_active_index = $this->get_scheduled_active_tab( $settings );

        $this->add_render_attribute( 'ua_adv_tabs_wrap', [
            'id'                     => 'ua-advance-tabs-' . $this->get_id(),
            'class'                  => [
                'ua-adv-tabs',
                $layout,
                $toggle_tab ? 'ua-tab-toggle' : '',
                $show_caret ? 'ua-caret-enabled' : 'ua-caret-disabled',
                $stack_mobile ? 'ua-responsive-stack' : '',
            ],
            'data-tabid'             => $this->get_id(),
            'data-scroll-on-click'   => $scroll_onclick ? 'yes' : 'no',
            'data-scroll-speed'      => $scroll_speed,
            'data-custom-id-offset'  => $custom_id_offset,
        ] );

        $this->add_render_attribute( 'ua_tabs_nav_list', [
            'class' => [ 'ua-tab-nav-list', $icon_position ],
            'role'  => 'tablist',
        ] );
        ?>
        <div <?php $this->print_render_attribute_string( 'ua_adv_tabs_wrap' ); ?>>
            <div class="ua-tabs-nav">
                <ul <?php $this->print_render_attribute_string( 'ua_tabs_nav_list' ); ?>>
                    <?php foreach ( $tabs as $index => $tab ) :
                        $tab_count = $index + 1;

                        // Calculate active state
                        $is_active = false;
                        if ( null !== $scheduled_active_index ) {
                            $is_active = ( $index === $scheduled_active_index );
                        } elseif ( 'all_collapsed' === $default_state ) {
                            $is_active = false;
                        } elseif ( 'first_open' === $default_state ) {
                            $is_active = ( 0 === $index );
                        } elseif ( 'all_open' === $default_state ) {
                            $is_active = true;
                        } else { // 'custom'
                            $is_active = ( 'yes' === ( $tab['ua_adv_tabs_tab_default_active'] ?? 'no' ) );
                        }

                        $tab_title  = ! empty( $tab['ua_adv_tabs_tab_title'] ) ? $tab['ua_adv_tabs_tab_title'] : '';
                        $title_tag  = ! empty( $tab['ua_adv_tabs_tab_title_html_tag'] ) ? Utils::validate_html_tag( $tab['ua_adv_tabs_tab_title_html_tag'] ) : 'span';
                        $tab_id     = ! empty( $tab['ua_adv_tabs_tab_id'] ) ? sanitize_title( $tab['ua_adv_tabs_tab_id'] ) : 'ua-tab-' . $id_int . $tab_count;
                        $content_id = $tab_id . '-content';
                        $icon_type  = ! empty( $tab['ua_adv_tabs_icon_type'] ) ? $tab['ua_adv_tabs_icon_type'] : 'icon';

                        $nav_item_key = 'ua_nav_item_' . $index;
                        $this->add_render_attribute( $nav_item_key, [
                            'id'            => $tab_id,
                            'class'         => [
                                'ua-tab-nav-item',
                                'ua-tab-item-trigger',
                                $is_active ? 'active active-default' : 'inactive',
                            ],
                            'role'          => 'tab',
                            'tabindex'      => $is_active ? '0' : '-1',
                            'aria-selected' => $is_active ? 'true' : 'false',
                            'aria-controls' => $content_id,
                            'aria-expanded' => $is_active ? 'true' : 'false',
                            'data-tab'      => $tab_count,
                        ] );
                    ?>
                        <li <?php $this->print_render_attribute_string( $nav_item_key ); ?>>
                            <?php if ( $icon_show && 'ua-tab-inline-icon' === $icon_position && 'after' === $icon_alignment ) : ?>
                                <<?php echo esc_html( $title_tag ); ?> class="ua-tab-title title-before-icon">
                                    <?php echo wp_kses_post( $tab_title ); ?>
                                </<?php echo esc_html( $title_tag ); ?>>
                            <?php endif; ?>

                            <?php if ( $icon_show ) : ?>
                                <?php if ( 'icon' === $icon_type && ! empty( $tab['ua_adv_tabs_tab_title_icon_new']['value'] ) ) : ?>
                                    <span class="ua-tab-icon">
                                        <?php Icons_Manager::render_icon( $tab['ua_adv_tabs_tab_title_icon_new'], [ 'aria-hidden' => 'true' ] ); ?>
                                    </span>
                                <?php elseif ( 'image' === $icon_type && ! empty( $tab['ua_adv_tabs_tab_title_image']['url'] ) ) : ?>
                                    <span class="ua-tab-image">
                                        <img src="<?php echo esc_url( $tab['ua_adv_tabs_tab_title_image']['url'] ); ?>" alt="<?php echo esc_attr( $tab_title ); ?>">
                                    </span>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php if ( ! $icon_show || 'ua-tab-top-icon' === $icon_position || 'before' === $icon_alignment ) : ?>
                                <<?php echo esc_html( $title_tag ); ?> class="ua-tab-title <?php echo esc_attr( $icon_show && 'ua-tab-inline-icon' === $icon_position ? 'title-after-icon' : '' ); ?>">
                                    <?php echo wp_kses_post( $tab_title ); ?>
                                </<?php echo esc_html( $title_tag ); ?>>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="ua-tabs-content">
                <?php foreach ( $tabs as $index => $tab ) :
                    $tab_count = $index + 1;

                    $is_active = false;
                    if ( null !== $scheduled_active_index ) {
                        $is_active = ( $index === $scheduled_active_index );
                    } elseif ( 'all_collapsed' === $default_state ) {
                        $is_active = false;
                    } elseif ( 'first_open' === $default_state ) {
                        $is_active = ( 0 === $index );
                    } elseif ( 'all_open' === $default_state ) {
                        $is_active = true;
                    } else { // 'custom'
                        $is_active = ( 'yes' === ( $tab['ua_adv_tabs_tab_default_active'] ?? 'no' ) );
                    }

                    $tab_id     = ! empty( $tab['ua_adv_tabs_tab_id'] ) ? sanitize_title( $tab['ua_adv_tabs_tab_id'] ) : 'ua-tab-' . $id_int . $tab_count;
                    $content_id = $tab_id . '-content';

                    $content_item_key = 'ua_content_item_' . $index;
                    $this->add_render_attribute( $content_item_key, [
                        'id'              => $content_id,
                        'class'           => [
                            'ua-tab-content-item',
                            'clearfix',
                            $is_active ? 'active active-default' : 'inactive',
                        ],
                        'role'            => 'tabpanel',
                        'aria-labelledby' => $tab_id,
                        'data-title-link' => $tab_id,
                        'tabindex'        => '0',
                    ] );
                ?>
                    <div <?php $this->print_render_attribute_string( $content_item_key ); ?>>
                        <?php
                        if ( 'template' === ( $tab['ua_adv_tabs_text_type'] ?? 'content' ) ) {
                            $template_id = ! empty( $tab['ua_primary_templates'] ) ? (int) $tab['ua_primary_templates'] : 0;
                            if ( $template_id > 0 ) {
                                echo Plugin::instance()->frontend->get_builder_content_for_display( $template_id );
                            }
                        } else {
                            echo wp_kses_post( $tab['ua_adv_tabs_tab_content'] ?? '' );
                        }
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}
