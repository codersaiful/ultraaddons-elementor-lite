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
 * UltraAddons Advanced Accordion Widget
 *
 * An advanced, responsive, and accessible Accordion & Toggle widget for Elementor.
 * Supports custom WYSIWYG content, Elementor Saved Templates, URL hash deep linking,
 * custom closed/opened icons, rotating toggle arrows, and FAQ Schema (JSON-LD).
 *
 * @since 1.2.0
 * @package UltraAddons
 */
class Advanced_Accordion extends Base {

    /**
     * Constructor — register style dependency
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        wp_register_style(
            'ultraaddons-advanced-accordion',
            ULTRA_ADDONS_ASSETS . 'css/widgets/advanced-accordion.css',
            [],
            ULTRA_ADDONS_VERSION,
            'all'
        );
        wp_enqueue_style( 'ultraaddons-advanced-accordion' );
    }

    /**
     * Style dependency
     */
    public function get_style_depends() {
        return [ 'ultraaddons-advanced-accordion' ];
    }

    /**
     * Widget Title
     */
    public function get_title() {
        return esc_html__( 'Advanced Accordion', 'ultraaddons-elementor-lite' );
    }

    /**
     * Widget Icon in Elementor panel
     */
    public function get_icon() {
        return 'ultraaddons eicon-accordion';
    }

    /**
     * Widget keywords
     */
    public function get_keywords() {
        return [ 'ultraaddons-elementor-lite', 'ua', 'accordion', 'advanced accordion', 'toggle', 'faq', 'collapsible', 'expand' ];
    }

    /**
     * Retrieve Elementor saved templates list
     */
    protected function get_elementor_templates() {
        $templates = Plugin::instance()->templates_manager->get_source( 'local' )->get_items();
        $options = [ '0' => esc_html__( '— Select Template —', 'ultraaddons-elementor-lite' ) ];

        if ( ! empty( $templates ) ) {
            foreach ( $templates as $template ) {
                $options[ $template['template_id'] ] = $template['title'] . ' (' . $template['type'] . ')';
            }
        }

        return $options;
    }

    /**
     * Register all controls
     */
    protected function register_controls() {
        // Content Tab
        $this->content_general_controls();
        $this->content_tabs_controls();

        // Style Tab
        $this->style_general_controls();
        $this->style_header_controls();
        $this->style_icon_controls();
        $this->style_content_controls();
    }

    /*==========================================================================
     * CONTENT TAB — General Settings
     *========================================================================*/
    protected function content_general_controls() {
        $this->start_controls_section(
            'ua_section_adv_accordion_general_settings',
            [
                'label' => esc_html__( 'General Settings', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ua_adv_accordion_type',
            [
                'label'       => esc_html__( 'Accordion Type', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'accordion',
                'options'     => [
                    'accordion' => esc_html__( 'Accordion (One open at a time)', 'ultraaddons-elementor-lite' ),
                    'toggle'    => esc_html__( 'Toggle (Multiple open at once)', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'ua_adv_accordion_trigger',
            [
                'label'       => esc_html__( 'Trigger Event', 'ultraaddons-elementor-lite' ),
                'description' => esc_html__( 'Choose whether items open on click or on hover.', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'click',
                'options'     => [
                    'click' => esc_html__( 'On Click', 'ultraaddons-elementor-lite' ),
                    'hover' => esc_html__( 'On Hover', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'ua_adv_accordion_default_state',
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
            ]
        );

        $this->add_control(
            'ua_adv_accordion_title_tag',
            [
                'label'   => esc_html__( 'Tab Title Tag', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'span',
                'options' => [
                    'h1'   => 'H1',
                    'h2'   => 'H2',
                    'h3'   => 'H3',
                    'h4'   => 'H4',
                    'h5'   => 'H5',
                    'h6'   => 'H6',
                    'span' => 'SPAN',
                    'p'    => 'P',
                    'div'  => 'DIV',
                ],
            ]
        );

        $this->add_control(
            'ua_adv_accordion_icon_show',
            [
                'label'        => esc_html__( 'Enable Toggle Icon', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'ua_adv_accordion_toggle_icon_postion',
            [
                'label'        => esc_html__( 'Toggle Icon Position', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::CHOOSE,
                'options'      => [
                    'left'  => [
                        'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'default'      => 'right',
                'condition'    => [
                    'ua_adv_accordion_icon_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ua_adv_accordion_icon_new',
            [
                'label'     => esc_html__( 'Toggle Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'fas fa-angle-right',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'ua_adv_accordion_icon_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ua_adv_accordion_toggle_speed',
            [
                'label'       => esc_html__( 'Toggle Speed (ms)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 300,
                'min'         => 50,
                'max'         => 2000,
                'step'        => 50,
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'ua_adv_accordion_scroll_onclick',
            [
                'label'        => esc_html__( 'Scroll on Click', 'ultraaddons-elementor-lite' ),
                'description'  => esc_html__( 'Smoothly scroll the page to the opened tab header.', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'no',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'ua_adv_accordion_scroll_speed',
            [
                'label'     => esc_html__( 'Scroll Speed (ms)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 300,
                'min'       => 50,
                'max'       => 2000,
                'condition' => [
                    'ua_adv_accordion_scroll_onclick' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ua_adv_accordion_custom_id_offset',
            [
                'label'       => esc_html__( 'Custom ID offset (px)', 'ultraaddons-elementor-lite' ),
                'description' => esc_html__( 'Offset for fixed headers when scrolling.', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 0,
                'condition'   => [
                    'ua_adv_accordion_scroll_onclick' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ua_adv_accordion_faq_schema_show',
            [
                'label'        => esc_html__( 'Enable FAQ Schema', 'ultraaddons-elementor-lite' ),
                'description'  => esc_html__( 'Automatically injects JSON-LD FAQPage Schema for SEO.', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'no',
                'return_value' => 'yes',
                'separator'    => 'before',
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * CONTENT TAB — Tabs (Repeater)
     *========================================================================*/
    protected function content_tabs_controls() {
        $this->start_controls_section(
            'ua_section_adv_accordion_content_settings',
            [
                'label' => esc_html__( 'Content Settings', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'ua_adv_accordion_tab_default_active',
            [
                'label'        => esc_html__( 'Active as Default', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'no',
                'return_value' => 'yes',
            ]
        );

        $repeater->add_control(
            'ua_adv_accordion_tab_icon_show',
            [
                'label'        => esc_html__( 'Enable Tab Icon', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
            ]
        );

        $repeater->start_controls_tabs( 'ua_tab_icons_repeater' );

        $repeater->start_controls_tab(
            'ua_opened_tab',
            [
                'label'     => esc_html__( 'Opened Tab', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'ua_adv_accordion_tab_icon_show' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'ua_adv_accordion_tab_title_icon_new_opened',
            [
                'label'     => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'fas fa-minus',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'ua_adv_accordion_tab_icon_show' => 'yes',
                ],
            ]
        );

        $repeater->end_controls_tab();

        $repeater->start_controls_tab(
            'ua_closed_tab',
            [
                'label'     => esc_html__( 'Closed Tab', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'ua_adv_accordion_tab_icon_show' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'ua_adv_accordion_tab_title_icon_new',
            [
                'label'     => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'fas fa-plus',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'ua_adv_accordion_tab_icon_show' => 'yes',
                ],
            ]
        );

        $repeater->end_controls_tab();

        $repeater->end_controls_tabs();

        $repeater->add_control(
            'ua_adv_accordion_tab_title',
            [
                'label'       => esc_html__( 'Tab Title', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Accordion Tab Title', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'label_block' => true,
                'separator'   => 'before',
            ]
        );

        $repeater->add_control(
            'ua_adv_accordion_text_type',
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
                    'ua_adv_accordion_text_type' => 'template',
                ],
            ]
        );

        $repeater->add_control(
            'ua_adv_accordion_tab_content',
            [
                'label'       => esc_html__( 'Tab Content', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::WYSIWYG,
                'default'     => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'condition'   => [
                    'ua_adv_accordion_text_type' => 'content',
                ],
            ]
        );

        $repeater->add_control(
            'ua_adv_accordion_tab_id',
            [
                'label'       => esc_html__( 'Custom ID', 'ultraaddons-elementor-lite' ),
                'description' => esc_html__( 'Custom ID will be added as an anchor tag (e.g. test becomes https://example.com/#test and opens the respective tab directly).', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
            ]
        );

        $repeater->add_control(
            'ua_adv_accordion_tab_faq_schema_text',
            [
                'label'       => esc_html__( 'FAQ Schema Text', 'ultraaddons-elementor-lite' ),
                'description' => esc_html__( 'For saved template, FAQ Schema Text can be added manually on each tab.', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => [ 'active' => true ],
                'separator'   => 'before',
                'condition'   => [
                    'ua_adv_accordion_text_type' => 'template',
                ],
            ]
        );

        $this->add_control(
            'ua_adv_accordion_tab',
            [
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'ua_adv_accordion_tab_title'          => esc_html__( 'Accordion Tab Title 1', 'ultraaddons-elementor-lite' ),
                        'ua_adv_accordion_tab_default_active' => 'yes',
                        'ua_adv_accordion_tab_content'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', 'ultraaddons-elementor-lite' ),
                    ],
                    [
                        'ua_adv_accordion_tab_title'          => esc_html__( 'Accordion Tab Title 2', 'ultraaddons-elementor-lite' ),
                        'ua_adv_accordion_tab_content'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', 'ultraaddons-elementor-lite' ),
                    ],
                    [
                        'ua_adv_accordion_tab_title'          => esc_html__( 'Accordion Tab Title 3', 'ultraaddons-elementor-lite' ),
                        'ua_adv_accordion_tab_content'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', 'ultraaddons-elementor-lite' ),
                    ],
                ],
                'title_field' => '{{{ ua_adv_accordion_tab_title }}}',
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — General Accordion Style
     *========================================================================*/
    protected function style_general_controls() {
        $this->start_controls_section(
            'ua_section_adv_accordion_style_settings',
            [
                'label' => esc_html__( 'General Style', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'ua_adv_accordion_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'    => [
                    'top'      => '0',
                    'right'    => '0',
                    'bottom'   => '10',
                    'left'     => '0',
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-list' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_adv_accordion_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-adv-accordion' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_adv_accordion_border',
                'selector' => '{{WRAPPER}} .ua-adv-accordion .ua-accordion-list',
            ]
        );

        $this->add_responsive_control(
            'ua_adv_accordion_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-list' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_adv_accordion_shadow',
                'selector' => '{{WRAPPER}} .ua-adv-accordion .ua-accordion-list',
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Tab Header Style
     *========================================================================*/
    protected function style_header_controls() {
        $this->start_controls_section(
            'ua_section_adv_accordion_tab_style_settings',
            [
                'label' => esc_html__( 'Tab Style', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_adv_accordion_tab_typography',
                'selector' => '{{WRAPPER}} .ua-adv-accordion .ua-accordion-header, {{WRAPPER}} .ua-adv-accordion .ua-accordion-tab-title',
            ]
        );

        $this->add_responsive_control(
            'ua_adv_accordion_tab_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'    => [
                    'top'      => '15',
                    'right'    => '15',
                    'bottom'   => '15',
                    'left'     => '15',
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_adv_accordion_tab_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'ua_adv_accordion_tab_style_tabs' );

        // Normal Tab
        $this->start_controls_tab(
            'ua_adv_accordion_tab_normal',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_adv_accordion_tab_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f1f1f1',
                'selectors' => [
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-header' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_adv_accordion_tab_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-header'          => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-tab-title'      => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_adv_accordion_tab_border',
                'selector' => '{{WRAPPER}} .ua-adv-accordion .ua-accordion-header',
            ]
        );

        $this->add_responsive_control(
            'ua_adv_accordion_tab_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_adv_accordion_tab_shadow',
                'selector' => '{{WRAPPER}} .ua-adv-accordion .ua-accordion-header',
            ]
        );

        $this->end_controls_tab();

        // Hover Tab
        $this->start_controls_tab(
            'ua_adv_accordion_tab_hover',
            [
                'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_adv_accordion_tab_color_hover',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#414141',
                'selectors' => [
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-header:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_adv_accordion_tab_text_color_hover',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-header:hover'          => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-header:hover .ua-accordion-tab-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_adv_accordion_tab_border_hover',
                'selector' => '{{WRAPPER}} .ua-adv-accordion .ua-accordion-header:hover',
            ]
        );

        $this->add_responsive_control(
            'ua_adv_accordion_tab_border_radius_hover',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-header:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_adv_accordion_tab_shadow_hover',
                'selector' => '{{WRAPPER}} .ua-adv-accordion .ua-accordion-header:hover',
            ]
        );

        $this->end_controls_tab();

        // Active Tab
        $this->start_controls_tab(
            'ua_adv_accordion_tab_active',
            [
                'label' => esc_html__( 'Active', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_adv_accordion_tab_color_active',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#444444',
                'selectors' => [
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-header.active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_adv_accordion_tab_text_color_active',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-header.active'          => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-header.active .ua-accordion-tab-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_adv_accordion_tab_border_active',
                'selector' => '{{WRAPPER}} .ua-adv-accordion .ua-accordion-header.active',
            ]
        );

        $this->add_responsive_control(
            'ua_adv_accordion_tab_border_radius_active',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-header.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_adv_accordion_tab_shadow_active',
                'selector' => '{{WRAPPER}} .ua-adv-accordion .ua-accordion-header.active',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Caret / Icons Style
     *========================================================================*/
    protected function style_icon_controls() {
        $this->start_controls_section(
            'ua_section_adv_accordion_caret_settings',
            [
                'label' => esc_html__( 'Caret / Icons Style', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Tab Icon Styling
        $this->add_control(
            'ua_heading_tab_icon_style',
            [
                'label'     => esc_html__( 'Tab Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'ua_adv_accordion_tab_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'size' => 16,
                    'unit' => 'px',
                ],
                'range'      => [
                    'px' => [
                        'min' => 10,
                        'max' => 50,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ua_adv_accordion_tab_icon_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-icon'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_adv_accordion_tab_icon_hover_color',
            [
                'label'     => esc_html__( 'Hover Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-header:hover .ua-accordion-icon'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-header:hover .ua-accordion-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_adv_accordion_tab_icon_active_color',
            [
                'label'     => esc_html__( 'Active Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-header.active .ua-accordion-icon'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-header.active .ua-accordion-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_adv_accordion_tab_icon_gap',
            [
                'label'      => esc_html__( 'Icon Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default'    => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Toggle Icon Styling
        $this->add_control(
            'ua_heading_toggle_icon_style',
            [
                'label'     => esc_html__( 'Toggle Arrow Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'ua_adv_accordion_toggle_icon_size',
            [
                'label'      => esc_html__( 'Toggle Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'size' => 16,
                    'unit' => 'px',
                ],
                'range'      => [
                    'px' => [
                        'min' => 10,
                        'max' => 50,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-adv-accordion .ua-toggle i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-adv-accordion .ua-toggle svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ua_adv_accordion_toggle_icon_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .ua-adv-accordion .ua-toggle'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-adv-accordion .ua-toggle svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_adv_accordion_toggle_icon_hover_color',
            [
                'label'     => esc_html__( 'Hover Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-header:hover .ua-toggle'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-header:hover .ua-toggle svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_adv_accordion_toggle_icon_active_color',
            [
                'label'     => esc_html__( 'Active Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-header.active .ua-toggle'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-header.active .ua-toggle svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Tab Content Style
     *========================================================================*/
    protected function style_content_controls() {
        $this->start_controls_section(
            'ua_section_adv_accordion_tab_content_style_settings',
            [
                'label' => esc_html__( 'Content Style', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'ua_adv_accordion_content_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-content' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_adv_accordion_content_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_adv_accordion_content_typography',
                'selector' => '{{WRAPPER}} .ua-adv-accordion .ua-accordion-content',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_adv_accordion_content_border',
                'selector' => '{{WRAPPER}} .ua-adv-accordion .ua-accordion-content',
            ]
        );

        $this->add_responsive_control(
            'ua_adv_accordion_content_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_adv_accordion_content_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'    => [
                    'top'      => '15',
                    'right'    => '15',
                    'bottom'   => '15',
                    'left'     => '15',
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_adv_accordion_content_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-adv-accordion .ua-accordion-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render toggle arrow icon
     */
    protected function print_toggle_icon( $settings ) {
        if ( ! empty( $settings['ua_adv_accordion_icon_new']['value'] ) ) {
            echo '<span class="ua-toggle">';
            Icons_Manager::render_icon( $settings['ua_adv_accordion_icon_new'], [ 'aria-hidden' => 'true' ] );
            echo '</span>';
        }
    }

    /*==========================================================================
     * MAIN RENDER METHOD
     *========================================================================*/
    protected function render() {
        $settings = $this->get_settings_for_display();
        $tabs     = ! empty( $settings['ua_adv_accordion_tab'] ) ? $settings['ua_adv_accordion_tab'] : [];

        if ( empty( $tabs ) ) {
            return;
        }

        $accordion_type     = ! empty( $settings['ua_adv_accordion_type'] ) ? $settings['ua_adv_accordion_type'] : 'accordion';
        $trigger_event      = ! empty( $settings['ua_adv_accordion_trigger'] ) ? $settings['ua_adv_accordion_trigger'] : 'click';
        $default_state      = ! empty( $settings['ua_adv_accordion_default_state'] ) ? $settings['ua_adv_accordion_default_state'] : 'first_open';
        $title_tag          = ! empty( $settings['ua_adv_accordion_title_tag'] ) ? Utils::validate_html_tag( $settings['ua_adv_accordion_title_tag'] ) : 'span';
        $enable_toggle_icon = ( 'yes' === ( $settings['ua_adv_accordion_icon_show'] ?? 'yes' ) );
        $toggle_icon_pos    = ! empty( $settings['ua_adv_accordion_toggle_icon_postion'] ) ? $settings['ua_adv_accordion_toggle_icon_postion'] : 'right';
        $toggle_speed       = ! empty( $settings['ua_adv_accordion_toggle_speed'] ) ? (int) $settings['ua_adv_accordion_toggle_speed'] : 300;
        $scroll_onclick     = ( 'yes' === ( $settings['ua_adv_accordion_scroll_onclick'] ?? 'no' ) ) ? 'yes' : 'no';
        $scroll_speed       = ! empty( $settings['ua_adv_accordion_scroll_speed'] ) ? (int) $settings['ua_adv_accordion_scroll_speed'] : 300;
        $custom_id_offset   = ! empty( $settings['ua_adv_accordion_custom_id_offset'] ) ? (int) $settings['ua_adv_accordion_custom_id_offset'] : 0;
        $enable_faq_schema  = ( 'yes' === ( $settings['ua_adv_accordion_faq_schema_show'] ?? 'no' ) );
        $id_int             = substr( $this->get_id_int(), 0, 3 );

        $faq_schema_data = [];

        $this->add_render_attribute( 'ua_adv_accordion_wrap', [
            'class'                 => [ 'ua-adv-accordion', 'ua-adv-accordion-' . $this->get_id() ],
            'data-accordion-type'   => $accordion_type,
            'data-trigger-event'    => $trigger_event,
            'data-toogle-speed'     => $toggle_speed,
            'data-custom-id-offset' => $custom_id_offset,
            'data-scroll-on-click'  => $scroll_onclick,
            'data-scroll-speed'     => $scroll_speed,
        ] );
        ?>
        <div <?php $this->print_render_attribute_string( 'ua_adv_accordion_wrap' ); ?>>
            <?php foreach ( $tabs as $index => $tab ) :
                $tab_count = $index + 1;

                // Determine active state based on Initial State setting
                $is_active = false;
                if ( 'all_collapsed' === $default_state ) {
                    $is_active = false;
                } elseif ( 'first_open' === $default_state ) {
                    $is_active = ( 0 === $index );
                } elseif ( 'all_open' === $default_state ) {
                    $is_active = true;
                } else { // 'custom'
                    $is_active = ( 'yes' === ( $tab['ua_adv_accordion_tab_default_active'] ?? 'no' ) );
                }

                $title_text = ! empty( $tab['ua_adv_accordion_tab_title'] ) ? $tab['ua_adv_accordion_tab_title'] : '';
                $tab_id     = ! empty( $tab['ua_adv_accordion_tab_id'] ) ? sanitize_title( $tab['ua_adv_accordion_tab_id'] ) : 'ua-tab-' . $id_int . $tab_count;
                $content_id = 'elementor-tab-content-' . $id_int . $tab_count;
                $show_icon  = ( 'yes' === ( $tab['ua_adv_accordion_tab_icon_show'] ?? 'yes' ) );

                $header_key  = 'ua_tab_header_' . $index;
                $content_key = 'ua_tab_content_' . $index;

                $this->add_render_attribute( $header_key, [
                    'id'            => $tab_id,
                    'class'         => [ 'elementor-tab-title', 'ua-accordion-header', $is_active ? 'active-default' : '' ],
                    'tabindex'      => '0',
                    'data-tab'      => $tab_count,
                    'aria-controls' => $content_id,
                    'role'          => 'button',
                    'aria-expanded' => $is_active ? 'true' : 'false',
                ] );

                $this->add_render_attribute( $content_key, [
                    'id'              => $content_id,
                    'class'           => [ 'ua-accordion-content', 'clearfix', $is_active ? 'active-default' : '' ],
                    'data-tab'        => $tab_count,
                    'aria-labelledby' => $tab_id,
                    'role'            => 'region',
                ] );

                // FAQ Schema preparation
                if ( $enable_faq_schema && ! empty( $title_text ) ) {
                    $schema_answer = '';
                    if ( 'content' === ( $tab['ua_adv_accordion_text_type'] ?? 'content' ) ) {
                        $schema_answer = wp_strip_all_tags( $tab['ua_adv_accordion_tab_content'] ?? '' );
                    } else {
                        $schema_answer = ! empty( $tab['ua_adv_accordion_tab_faq_schema_text'] ) ? wp_strip_all_tags( $tab['ua_adv_accordion_tab_faq_schema_text'] ) : '';
                    }
                    if ( ! empty( $schema_answer ) ) {
                        $faq_schema_data[] = [
                            '@type'          => 'Question',
                            'name'           => esc_html( $title_text ),
                            'acceptedAnswer' => [
                                '@type' => 'Answer',
                                'text'  => esc_html( $schema_answer ),
                            ],
                        ];
                    }
                }
            ?>
                <div class="ua-accordion-list">
                    <div <?php $this->print_render_attribute_string( $header_key ); ?>>

                        <?php if ( $enable_toggle_icon && 'left' === $toggle_icon_pos ) : ?>
                            <?php $this->print_toggle_icon( $settings ); ?>
                        <?php endif; ?>

                        <?php if ( 'left' === $toggle_icon_pos ) : ?>
                            <<?php echo esc_html( $title_tag ); ?> class="ua-accordion-tab-title">
                                <?php echo wp_kses_post( $title_text ); ?>
                            </<?php echo esc_html( $title_tag ); ?>>
                        <?php endif; ?>

                        <?php if ( $show_icon ) : ?>
                            <span class="ua-accordion-icon ua-accordion-icon-closed">
                                <?php if ( ! empty( $tab['ua_adv_accordion_tab_title_icon_new']['value'] ) ) : ?>
                                    <?php Icons_Manager::render_icon( $tab['ua_adv_accordion_tab_title_icon_new'], [ 'aria-hidden' => 'true' ] ); ?>
                                <?php endif; ?>
                            </span>
                            <span class="ua-accordion-icon ua-accordion-icon-opened">
                                <?php if ( ! empty( $tab['ua_adv_accordion_tab_title_icon_new_opened']['value'] ) ) : ?>
                                    <?php Icons_Manager::render_icon( $tab['ua_adv_accordion_tab_title_icon_new_opened'], [ 'aria-hidden' => 'true' ] ); ?>
                                <?php endif; ?>
                            </span>
                        <?php endif; ?>

                        <?php if ( 'right' === $toggle_icon_pos || empty( $toggle_icon_pos ) ) : ?>
                            <<?php echo esc_html( $title_tag ); ?> class="ua-accordion-tab-title">
                                <?php echo wp_kses_post( $title_text ); ?>
                            </<?php echo esc_html( $title_tag ); ?>>
                        <?php endif; ?>

                        <?php if ( $enable_toggle_icon && ( 'right' === $toggle_icon_pos || empty( $toggle_icon_pos ) ) ) : ?>
                            <?php $this->print_toggle_icon( $settings ); ?>
                        <?php endif; ?>

                    </div>

                    <div <?php $this->print_render_attribute_string( $content_key ); ?>>
                        <?php
                        if ( 'template' === ( $tab['ua_adv_accordion_text_type'] ?? 'content' ) ) {
                            $template_id = ! empty( $tab['ua_primary_templates'] ) ? (int) $tab['ua_primary_templates'] : 0;
                            if ( $template_id > 0 ) {
                                echo Plugin::instance()->frontend->get_builder_content_for_display( $template_id );
                            }
                        } else {
                            echo wp_kses_post( $tab['ua_adv_accordion_tab_content'] ?? '' );
                        }
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php
        // Inject FAQ Schema (JSON-LD)
        if ( $enable_faq_schema && ! empty( $faq_schema_data ) ) :
            $json_schema = [
                '@context'   => 'https://schema.org',
                '@type'      => 'FAQPage',
                'mainEntity' => $faq_schema_data,
            ];
            ?>
            <script type="application/ld+json">
                <?php echo wp_json_encode( $json_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ); ?>
            </script>
        <?php endif;
    }
}
