<?php
namespace UltraAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use UltraAddons\Core\Custom_Product;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * UltraAddons WooCommerce Single Product Tabs Widget
 *
 * Provides a modern, customizable, and versatile WooCommerce Single Product Tabs
 * widget with presets, custom tab sorting, renaming, visibility toggle, and icon integration.
 *
 * @since 1.1.0.14
 * @package UltraAddons
 */
class Product_Tabs extends Base {

    /**
     * Constructor — register and enqueue widget style
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        wp_register_style(
            'ultraaddons-product-tabs',
            ULTRA_ADDONS_ASSETS . 'css/widgets/product-tabs.css',
            [],
            ULTRA_ADDONS_VERSION,
            'all'
        );
        wp_enqueue_style( 'ultraaddons-product-tabs' );
    }

    /**
     * Widget style dependencies
     *
     * @return array
     */
    public function get_style_depends() {
        return [ 'ultraaddons-product-tabs' ];
    }

    /**
     * Set widget keywords for Elementor editor search
     *
     * @return array
     */
    public function get_keywords() {
        return [ 'ultraaddons-elementor-lite', 'ua', 'wc', 'woocommerce', 'product', 'tabs', 'product tabs', 'woo product tabs', 'woo tabs', 'reviews', 'description', 'additional info', 'specifications', 'shop' ];
    }

    /**
     * Register widget controls.
     */
    protected function register_controls() {
        $this->register_content_controls();
        $this->register_nav_style_controls();
        $this->register_panel_style_controls();
        $this->register_attributes_style_controls();
        $this->register_reviews_style_controls();
    }

    /**
     * Retrieve Elementor saved templates list
     */
    protected function get_elementor_templates() {
        if ( ! class_exists( '\Elementor\Plugin' ) ) {
            return [];
        }

        $templates = \Elementor\Plugin::instance()->templates_manager->get_source( 'local' )->get_items();
        $options   = [ '0' => esc_html__( '— Select Template —', 'ultraaddons-elementor-lite' ) ];

        if ( ! empty( $templates ) ) {
            foreach ( $templates as $template ) {
                $options[ $template['template_id'] ] = $template['title'] . ' (' . $template['type'] . ')';
            }
        }

        return $options;
    }

    /**
     * Register Content Controls Section
     */
    protected function register_content_controls() {

        // TAB ITEMS SECTION
        $this->start_controls_section(
            'ua_ptabs_section_items',
            [
                'label' => esc_html__( 'Tab Items', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'ua_ptabs_item_type',
            [
                'label'   => esc_html__( 'Tab Type', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'custom',
                'options' => [
                    'woocommerce' => esc_html__( 'WooCommerce Tab', 'ultraaddons-elementor-lite' ),
                    'custom'      => esc_html__( 'Custom Tab', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $repeater->add_control(
            'ua_ptabs_item_key',
            [
                'label'       => esc_html__( 'Tab Key / Slug', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'placeholder' => 'description / reviews / custom_slug',
                'description' => esc_html__( 'WooCommerce keys: "description", "additional_information", "reviews", or custom slug.', 'ultraaddons-elementor-lite' ),
                'label_block' => true,
                'condition'   => [
                    'ua_ptabs_item_type' => 'woocommerce',
                ],
            ]
        );

        $repeater->add_control(
            'ua_ptabs_item_title',
            [
                'label'       => esc_html__( 'Tab Title', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Tab Title', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'Enter Tab Title', 'ultraaddons-elementor-lite' ),
                'label_block' => true,
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $repeater->add_control(
            'ua_ptabs_item_show',
            [
                'label'        => esc_html__( 'Show Tab', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $repeater->add_control(
            'ua_ptabs_item_icon',
            [
                'label'       => esc_html__( 'Tab Icon', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::ICONS,
                'skin'        => 'inline',
                'label_block' => false,
            ]
        );

        $repeater->add_control(
            'ua_ptabs_item_content_type',
            [
                'label'     => esc_html__( 'Content Source', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'wysiwyg',
                'options'   => [
                    'wysiwyg'  => esc_html__( 'Custom Content / Text', 'ultraaddons-elementor-lite' ),
                    'template' => esc_html__( 'Elementor Saved Template', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'ua_ptabs_item_type' => 'custom',
                ],
                'separator' => 'before',
            ]
        );

        $repeater->add_control(
            'ua_ptabs_item_custom_content',
            [
                'label'     => esc_html__( 'Custom Content', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::WYSIWYG,
                'default'   => esc_html__( 'This is custom tab content. You can write description, shipping info, FAQs, size guides, or insert shortcodes here.', 'ultraaddons-elementor-lite' ),
                'dynamic'   => [ 'active' => true ],
                'condition' => [
                    'ua_ptabs_item_type'         => 'custom',
                    'ua_ptabs_item_content_type' => 'wysiwyg',
                ],
            ]
        );

        $repeater->add_control(
            'ua_ptabs_item_template_id',
            [
                'label'       => esc_html__( 'Choose Template', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'options'     => $this->get_elementor_templates(),
                'default'     => '0',
                'label_block' => true,
                'condition'   => [
                    'ua_ptabs_item_type'         => 'custom',
                    'ua_ptabs_item_content_type' => 'template',
                ],
            ]
        );

        $this->add_control(
            'ua_ptabs_items',
            [
                'label'       => esc_html__( 'Tabs List', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'ua_ptabs_item_type'  => 'woocommerce',
                        'ua_ptabs_item_key'   => 'description',
                        'ua_ptabs_item_title' => '',
                        'ua_ptabs_item_show'  => 'yes',
                    ],
                    [
                        'ua_ptabs_item_type'  => 'woocommerce',
                        'ua_ptabs_item_key'   => 'additional_information',
                        'ua_ptabs_item_title' => '',
                        'ua_ptabs_item_show'  => 'yes',
                    ],
                    [
                        'ua_ptabs_item_type'  => 'woocommerce',
                        'ua_ptabs_item_key'   => 'reviews',
                        'ua_ptabs_item_title' => '',
                        'ua_ptabs_item_show'  => 'yes',
                    ],
                ],
                'title_field' => '{{{ ua_ptabs_item_title ? ua_ptabs_item_title : ua_ptabs_item_key }}}',
            ]
        );

        $this->add_control(
            'ua_ptabs_nav_preset',
            [
                'label'     => esc_html__( 'Style Preset', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'pill'     => esc_html__( 'Modern Capsule / Pill', 'ultraaddons-elementor-lite' ),
                    'minimal'  => esc_html__( 'Minimal Underline', 'ultraaddons-elementor-lite' ),
                    'bordered' => esc_html__( 'Bordered Card', 'ultraaddons-elementor-lite' ),
                    'classic'  => esc_html__( 'Classic Bar', 'ultraaddons-elementor-lite' ),
                ],
                'default'   => 'pill',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'ua_ptabs_nav_align',
            [
                'label'     => esc_html__( 'Tabs Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'    => [
                        'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center'  => [
                        'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'   => [
                        'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justify / Full Width', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-justify',
                    ],
                ],
                'default'   => 'left',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper.ua-ptabs-layout-horizontal .woocommerce-tabs ul.tabs' => 'justify-content: {{VALUE}} !important;',
                    '{{WRAPPER}} .ua-product-tabs-wrapper.ua-ptabs-layout-vertical .woocommerce-tabs ul.tabs' => 'align-items: {{VALUE}} !important;',
                    '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs ul.tabs li a' => 'justify-content: {{VALUE}} !important;',
                ],
                'selectors_dictionary' => [
                    'left'    => 'flex-start',
                    'center'  => 'center',
                    'right'   => 'flex-end',
                    'justify' => 'stretch',
                ],
            ]
        );

        $this->add_control(
            'ua_ptabs_icon_position',
            [
                'label'     => esc_html__( 'Icon Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'before' => [
                        'title' => esc_html__( 'Before Title', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'after'  => [
                        'title' => esc_html__( 'After Title', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'default'   => 'before',
                'toggle'    => false,
            ]
        );

        $this->add_control(
            'ua_ptabs_layout',
            [
                'label'     => esc_html__( 'Layout Orientation', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'horizontal' => [
                        'title' => esc_html__( 'Horizontal', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'vertical'   => [
                        'title' => esc_html__( 'Vertical', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                ],
                'default'   => 'horizontal',
                'toggle'    => false,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'ua_ptabs_nav_width',
            [
                'label'      => esc_html__( 'Nav Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [ 'min' => 120, 'max' => 500 ],
                    '%'  => [ 'min' => 15, 'max' => 50 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 240,
                ],
                'condition'  => [
                    'ua_ptabs_layout' => 'vertical',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper.ua-ptabs-layout-vertical .woocommerce-tabs ul.tabs' => 'width: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_control(
            'ua_ptabs_vertical_align',
            [
                'label'     => esc_html__( 'Nav Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'  => [
                        'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'default'   => 'left',
                'toggle'    => false,
                'condition' => [
                    'ua_ptabs_layout' => 'vertical',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_ptabs_panel_gap',
            [
                'label'      => esc_html__( 'Panel Gap', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 80 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 24,
                ],
                'condition'  => [
                    'ua_ptabs_layout' => 'vertical',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper.ua-ptabs-layout-vertical .woocommerce-tabs' => 'gap: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register Tabs Header Navigation Style Controls
     */
    protected function register_nav_style_controls() {

        $this->start_controls_section(
            'ua_ptabs_section_nav_style',
            [
                'label' => esc_html__( 'Tabs Navigation', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_ptabs_nav_typography',
                'label'    => esc_html__( 'Typography', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs ul.tabs li a',
            ]
        );

        $this->add_responsive_control(
            'ua_ptabs_nav_gap',
            [
                'label'      => esc_html__( 'Tabs Gap', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 40 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs ul.tabs' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_ptabs_nav_padding',
            [
                'label'      => esc_html__( 'Tab Item Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs ul.tabs li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_ptabs_nav_radius',
            [
                'label'      => esc_html__( 'Tab Item Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs ul.tabs li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs ul.tabs li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // TABS STATE (NORMAL / HOVER / ACTIVE)
        $this->start_controls_tabs( 'ua_ptabs_nav_state_tabs' );

        // NORMAL STATE
        $this->start_controls_tab(
            'ua_ptabs_nav_tab_normal',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_ptabs_nav_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs ul.tabs li a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_ptabs_nav_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f3f4f6',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs ul.tabs li' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs ul.tabs li a' => 'background-color: transparent;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_ptabs_nav_border',
                'label'    => esc_html__( 'Border', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs ul.tabs li',
            ]
        );

        $this->end_controls_tab();

        // HOVER STATE
        $this->start_controls_tab(
            'ua_ptabs_nav_tab_hover',
            [
                'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_ptabs_nav_hover_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs ul.tabs li:hover a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_ptabs_nav_hover_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#e5e7eb',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs ul.tabs li:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_ptabs_nav_hover_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs ul.tabs li:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // ACTIVE STATE
        $this->start_controls_tab(
            'ua_ptabs_nav_tab_active',
            [
                'label' => esc_html__( 'Active', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_ptabs_nav_active_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs ul.tabs li.active a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_ptabs_nav_active_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4f46e5',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs ul.tabs li.active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_ptabs_nav_active_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs ul.tabs li.active' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_ptabs_nav_active_indicator_color',
            [
                'label'     => esc_html__( 'Active Indicator Line Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4f46e5',
                'condition' => [
                    'ua_ptabs_nav_preset' => [ 'minimal', 'classic' ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper.ua-ptabs-preset-minimal .woocommerce-tabs ul.tabs li.active a' => 'border-bottom-color: {{VALUE}} !important;',
                    '{{WRAPPER}} .ua-product-tabs-wrapper.ua-ptabs-preset-classic .woocommerce-tabs ul.tabs li.active' => 'border-top-color: {{VALUE}} !important; border-left-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_ptabs_nav_active_shadow',
                'label'    => esc_html__( 'Active Box Shadow', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs ul.tabs li.active',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        // ICON STYLING
        $this->add_control(
            'ua_ptabs_heading_icon_style',
            [
                'label'     => esc_html__( 'Icon Style', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'ua_ptabs_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range'      => [
                    'px' => [ 'min' => 8, 'max' => 40 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 16,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper .ua-ptab-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-product-tabs-wrapper .ua-ptab-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_ptabs_icon_gap',
            [
                'label'      => esc_html__( 'Icon Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 30 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 8,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper .ua-ptab-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-product-tabs-wrapper.ua-ptabs-icon-after .ua-ptab-icon' => 'margin-right: 0; margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ua_ptabs_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper .ua-ptab-icon i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-product-tabs-wrapper .ua-ptab-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_ptabs_icon_active_color',
            [
                'label'     => esc_html__( 'Active Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs ul.tabs li.active .ua-ptab-icon i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs ul.tabs li.active .ua-ptab-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register Content Panel Style Controls
     */
    protected function register_panel_style_controls() {

        $this->start_controls_section(
            'ua_ptabs_section_panel_style',
            [
                'label' => esc_html__( 'Content Panel', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'ua_ptabs_panel_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs .woocommerce-Tabs-panel' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_ptabs_panel_border',
                'label'    => esc_html__( 'Border', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs .woocommerce-Tabs-panel',
            ]
        );

        $this->add_responsive_control(
            'ua_ptabs_panel_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs .woocommerce-Tabs-panel' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_ptabs_panel_shadow',
                'label'    => esc_html__( 'Box Shadow', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs .woocommerce-Tabs-panel',
            ]
        );

        $this->add_responsive_control(
            'ua_ptabs_panel_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'    => [
                    'top'      => '25',
                    'right'    => '25',
                    'bottom'   => '25',
                    'left'     => '25',
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs .woocommerce-Tabs-panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_ptabs_panel_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'    => [
                    'top'      => '20',
                    'right'    => '0',
                    'bottom'   => '0',
                    'left'     => '0',
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs .woocommerce-Tabs-panel' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // HEADINGS INSIDE PANEL
        $this->add_control(
            'ua_ptabs_heading_panel_title',
            [
                'label'     => esc_html__( 'Panel Headings', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'ua_ptabs_panel_heading_color',
            [
                'label'     => esc_html__( 'Heading Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#111827',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs .woocommerce-Tabs-panel h2' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs .woocommerce-Tabs-panel h3' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_ptabs_panel_heading_typography',
                'label'    => esc_html__( 'Heading Typography', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs .woocommerce-Tabs-panel h2, {{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs .woocommerce-Tabs-panel h3',
            ]
        );

        // BODY CONTENT TYPOGRAPHY
        $this->add_control(
            'ua_ptabs_heading_panel_content',
            [
                'label'     => esc_html__( 'Panel Text & Paragraphs', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'ua_ptabs_panel_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs .woocommerce-Tabs-panel p'  => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs .woocommerce-Tabs-panel li' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs .woocommerce-Tabs-panel'    => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_ptabs_panel_text_typography',
                'label'    => esc_html__( 'Text Typography', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs .woocommerce-Tabs-panel, {{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-tabs .woocommerce-Tabs-panel p',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register Attributes Table Style Controls
     */
    protected function register_attributes_style_controls() {

        $this->start_controls_section(
            'ua_ptabs_section_attributes_style',
            [
                'label' => esc_html__( 'Specifications Table', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'ua_ptabs_table_label_color',
            [
                'label'     => esc_html__( 'Label Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-product-attributes th' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_ptabs_table_label_typography',
                'label'    => esc_html__( 'Label Typography', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-product-attributes th',
            ]
        );

        $this->add_control(
            'ua_ptabs_table_value_color',
            [
                'label'     => esc_html__( 'Value Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-product-attributes td' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_ptabs_table_value_typography',
                'label'    => esc_html__( 'Value Typography', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-product-attributes td',
            ]
        );

        $this->add_control(
            'ua_ptabs_table_border_color',
            [
                'label'     => esc_html__( 'Row Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#e5e7eb',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-product-attributes tr' => 'border-bottom-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-product-attributes th' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-product-attributes td' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_ptabs_table_row_alt_bg',
            [
                'label'     => esc_html__( 'Alternating Row Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f9fafb',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper .woocommerce-product-attributes tr:nth-child(even)' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register Reviews & Form Style Controls
     */
    protected function register_reviews_style_controls() {

        $this->start_controls_section(
            'ua_ptabs_section_reviews_style',
            [
                'label' => esc_html__( 'Reviews & Form Button', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'ua_ptabs_reviews_star_color',
            [
                'label'     => esc_html__( 'Review Star Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f59e0b',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper .star-rating span:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-product-tabs-wrapper .stars a'                 => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_ptabs_heading_submit_btn',
            [
                'label'     => esc_html__( 'Submit Review Button', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_ptabs_submit_typography',
                'label'    => esc_html__( 'Button Typography', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-product-tabs-wrapper #respond input#submit, {{WRAPPER}} .ua-product-tabs-wrapper .button',
            ]
        );

        $this->start_controls_tabs( 'ua_ptabs_submit_btn_tabs' );

        // NORMAL BUTTON
        $this->start_controls_tab(
            'ua_ptabs_submit_tab_normal',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_ptabs_submit_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper #respond input#submit' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-product-tabs-wrapper .button'               => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_ptabs_submit_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4f46e5',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper #respond input#submit' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-product-tabs-wrapper .button'               => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // HOVER BUTTON
        $this->start_controls_tab(
            'ua_ptabs_submit_tab_hover',
            [
                'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_ptabs_submit_hover_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper #respond input#submit:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-product-tabs-wrapper .button:hover'               => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_ptabs_submit_hover_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#3730a3',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper #respond input#submit:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-product-tabs-wrapper .button:hover'               => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'ua_ptabs_submit_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'    => [
                    'top'      => '12',
                    'right'    => '24',
                    'bottom'   => '12',
                    'left'     => '24',
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper #respond input#submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ua-product-tabs-wrapper .button'               => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'before',
            ]
        );

        $this->add_responsive_control(
            'ua_ptabs_submit_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'    => [
                    'top'      => '8',
                    'right'    => '8',
                    'bottom'   => '8',
                    'left'     => '8',
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-tabs-wrapper #respond input#submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ua-product-tabs-wrapper .button'               => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Filter and manage WooCommerce product tabs based on repeater settings
     *
     * @param array $tabs
     * @return array
     */
    public function manage_product_tabs( $tabs ) {
        $settings = $this->get_settings_for_display();
        $items    = $settings['ua_ptabs_items'] ?? [];

        if ( empty( $items ) || ! is_array( $items ) ) {
            return $tabs;
        }

        $processed_tabs = [];
        $priority       = 10;

        foreach ( $items as $index => $item ) {
            // Hide tab if switcher is OFF
            if ( 'yes' !== ( $item['ua_ptabs_item_show'] ?? 'yes' ) ) {
                continue;
            }

            $raw_key = trim( $item['ua_ptabs_item_key'] ?? '' );
            $type    = $item['ua_ptabs_item_type'] ?? 'woocommerce';
            $title   = trim( $item['ua_ptabs_item_title'] ?? '' );
            $item_id = $item['_id'] ?? ( $index + 1 );

            // Check if it's an existing WooCommerce core tab (and not explicitly set to custom)
            if ( 'custom' !== $type && ! empty( $raw_key ) && isset( $tabs[ $raw_key ] ) ) {
                $tab_data = $tabs[ $raw_key ];
                if ( ! empty( $title ) ) {
                    $tab_data['title'] = esc_html( $title );
                }
                $tab_data['priority'] = $priority;
                $processed_tabs[ $raw_key ] = $tab_data;
                $priority += 10;
                continue;
            }

            // Otherwise, it's a Custom Tab with guaranteed unique key!
            $tab_key = ! empty( $raw_key ) ? sanitize_key( $raw_key ) : 'custom_tab_' . sanitize_key( $item_id );
            if ( isset( $processed_tabs[ $tab_key ] ) ) {
                $tab_key .= '_' . ( $index + 1 );
            }

            $display_title = ! empty( $title ) ? $title : sprintf( esc_html__( 'Tab #%d', 'ultraaddons-elementor-lite' ), ( $index + 1 ) );

            $processed_tabs[ $tab_key ] = [
                'title'     => esc_html( $display_title ),
                'priority'  => $priority,
                'callback'  => [ $this, 'render_custom_tab_panel' ],
                'item_data' => $item,
            ];
            $priority += 10;
        }

        return $processed_tabs;
    }

    /**
     * Render callback for Custom Tabs
     *
     * @param string $key
     * @param array $tab
     */
    public function render_custom_tab_panel( $key, $tab ) {
        $item         = $tab['item_data'] ?? [];
        $content_type = $item['ua_ptabs_item_content_type'] ?? 'wysiwyg';
        $title        = $tab['title'] ?? '';

        if ( ! empty( $title ) ) {
            echo '<h2>' . esc_html( $title ) . '</h2>';
        }

        if ( 'template' === $content_type && ! empty( $item['ua_ptabs_item_template_id'] ) && '0' !== $item['ua_ptabs_item_template_id'] ) {
            if ( class_exists( '\Elementor\Plugin' ) ) {
                echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( (int) $item['ua_ptabs_item_template_id'] ); // phpcs:ignore
            }
        } else {
            $content = $item['ua_ptabs_item_custom_content'] ?? '';
            if ( empty( $content ) ) {
                $content = '<p>' . esc_html__( 'Add your custom content here using the Elementor panel on the left.', 'ultraaddons-elementor-lite' ) . '</p>';
            }
            echo do_shortcode( wp_kses_post( $content ) );
        }
    }

    /**
     * Helper to render icon HTML from Elementor icon control
     *
     * @param array $icon
     * @return string
     */
    protected function get_tab_icon_html( $icon ) {
        if ( empty( $icon ) || empty( $icon['value'] ) ) {
            return '';
        }

        ob_start();
        echo '<span class="ua-ptab-icon">';
        Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] );
        echo '</span>';
        return ob_get_clean();
    }

    /**
     * Safely inject tab icons into WooCommerce rendered tab anchors
     *
     * @param string $html
     * @param array $settings
     * @return string
     */
    protected function inject_tab_icons( $html, $settings ) {
        $items         = $settings['ua_ptabs_items'] ?? [];
        $icon_position = $settings['ua_ptabs_icon_position'] ?? 'before';

        if ( empty( $items ) || ! is_array( $items ) ) {
            return $html;
        }

        foreach ( $items as $index => $item ) {
            $show = 'yes' === ( $item['ua_ptabs_item_show'] ?? 'yes' );
            if ( ! $show || empty( $item['ua_ptabs_item_icon'] ) ) {
                continue;
            }

            $raw_key = trim( $item['ua_ptabs_item_key'] ?? '' );
            $type    = $item['ua_ptabs_item_type'] ?? 'woocommerce';
            $item_id = $item['_id'] ?? ( $index + 1 );

            if ( 'custom' === $type || empty( $raw_key ) ) {
                $key = ! empty( $raw_key ) ? sanitize_key( $raw_key ) : 'custom_tab_' . sanitize_key( $item_id );
            } else {
                $key = sanitize_key( $raw_key );
            }

            $icon_html = $this->get_tab_icon_html( $item['ua_ptabs_item_icon'] );
            if ( empty( $icon_html ) ) {
                continue;
            }

            $pattern = '~(href=["\']#tab-' . preg_quote( $key, '~' ) . '["\'][^>]*>)([\s\S]*?)(<\/a>)~i';
            if ( 'before' === $icon_position ) {
                $html = preg_replace( $pattern, '$1' . addcslashes( $icon_html, '\\$' ) . ' $2$3', $html, 1 );
            } else {
                $html = preg_replace( $pattern, '$1$2 ' . addcslashes( $icon_html, '\\$' ) . '$3', $html, 1 );
            }
        }

        return $html;
    }

    /**
     * Editor Mock Template when no WooCommerce product is available
     *
     * @param array $settings
     */
    protected function render_editor_mock( $settings ) {
        $items         = $settings['ua_ptabs_items'] ?? [];
        $icon_position = $settings['ua_ptabs_icon_position'] ?? 'before';

        $visible_items = [];
        foreach ( $items as $item ) {
            if ( 'yes' === ( $item['ua_ptabs_item_show'] ?? 'yes' ) ) {
                $visible_items[] = $item;
            }
        }

        if ( empty( $visible_items ) ) {
            $visible_items = [
                [ 'ua_ptabs_item_key' => 'description', 'ua_ptabs_item_title' => 'Description' ],
                [ 'ua_ptabs_item_key' => 'additional_information', 'ua_ptabs_item_title' => 'Additional Information' ],
                [ 'ua_ptabs_item_key' => 'reviews', 'ua_ptabs_item_title' => 'Reviews (3)' ],
            ];
        }
        ?>
        <div class="woocommerce-tabs wc-tabs-wrapper">
            <ul class="tabs wc-tabs" role="tablist">
                <?php foreach ( $visible_items as $index => $item ) :
                    $key       = $item['ua_ptabs_item_key'] ?? 'tab';
                    $title     = ! empty( $item['ua_ptabs_item_title'] ) ? $item['ua_ptabs_item_title'] : ucfirst( str_replace( '_', ' ', $key ) );
                    $icon_html = ! empty( $item['ua_ptabs_item_icon'] ) ? $this->get_tab_icon_html( $item['ua_ptabs_item_icon'] ) : '';
                    $is_active = 0 === $index ? 'active' : '';
                ?>
                    <li class="<?php echo esc_attr( $key ); ?>_tab <?php echo esc_attr( $is_active ); ?>" id="tab-title-<?php echo esc_attr( $key ); ?>" role="tab">
                        <a href="#tab-<?php echo esc_attr( $key ); ?>">
                            <?php if ( 'before' === $icon_position && $icon_html ) echo $icon_html; // phpcs:ignore ?>
                            <?php echo esc_html( $title ); ?>
                            <?php if ( 'after' === $icon_position && $icon_html ) echo $icon_html; // phpcs:ignore ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--description panel entry-content wc-tab" id="tab-description" style="display: block;">
                <h2><?php esc_html_e( 'Description', 'ultraaddons-elementor-lite' ); ?></h2>
                <p><?php esc_html_e( 'This is a live editor preview of your WooCommerce product tabs. When viewing on a live single product page, real product descriptions, specifications, and customer review submission forms will appear here seamlessly.', 'ultraaddons-elementor-lite' ); ?></p>
            </div>
        </div>
        <?php
    }

    /**
     * Render widget output on frontend.
     */
    protected function render() {
        if ( ! function_exists( 'WC' ) ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo '<div class="ua-alert ua-alert-warning">' . esc_html__( 'WooCommerce must be active to display Product Tabs.', 'ultraaddons-elementor-lite' ) . '</div>';
            }
            return;
        }

        $settings  = $this->get_settings_for_display();
        $is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();
        $preset    = $settings['ua_ptabs_nav_preset'] ?? 'pill';
        $icon_pos  = $settings['ua_ptabs_icon_position'] ?? 'before';

        // Resolve Product Context
        $current_product = false;
        if ( class_exists( '\UltraAddons\Core\Custom_Product' ) ) {
            $custom_product_id = Custom_Product::get_product();
            if ( $custom_product_id ) {
                $current_product = wc_get_product( $custom_product_id );
            }
        }

        if ( ! is_a( $current_product, 'WC_Product' ) ) {
            $current_product = wc_get_product( get_the_ID() );
        }

        if ( ! $current_product && $is_editor ) {
            $preview_products = wc_get_products( [
                'status'  => 'publish',
                'limit'   => 1,
                'orderby' => 'date',
                'order'   => 'DESC',
            ] );
            if ( ! empty( $preview_products ) ) {
                $current_product = $preview_products[0];
            }
        }

        $layout   = $settings['ua_ptabs_layout'] ?? 'horizontal';
        $v_side   = $settings['ua_ptabs_vertical_align'] ?? 'left';

        // Wrapper classes
        $wrapper_classes = [
            'ua-product-tabs-wrapper',
            'ua-ptabs-preset-' . sanitize_html_class( $preset ),
            'ua-ptabs-icon-' . sanitize_html_class( $icon_pos ),
            'ua-ptabs-layout-' . sanitize_html_class( $layout ),
            'ua-ptabs-vside-' . sanitize_html_class( $v_side ),
        ];

        $this->add_render_attribute( 'ua_ptabs_wrapper', 'class', $wrapper_classes );

        ?>
        <div <?php echo $this->get_render_attribute_string( 'ua_ptabs_wrapper' ); // phpcs:ignore ?>>
            <?php
            if ( $current_product && is_a( $current_product, 'WC_Product' ) ) {
                global $post, $product;
                $original_post    = $post;
                $original_product = $product;

                $post    = get_post( $current_product->get_id() );
                $product = $current_product;
                setup_postdata( $post );

                // Add tab customization filter with priority 99999
                add_filter( 'woocommerce_product_tabs', [ $this, 'manage_product_tabs' ], 99999 );

                // Buffer native WooCommerce tabs template
                ob_start();
                wc_get_template( 'single-product/tabs/tabs.php' );
                $tabs_html = ob_get_clean();

                // Clean up filter
                remove_filter( 'woocommerce_product_tabs', [ $this, 'manage_product_tabs' ], 99999 );

                // Reset post data
                $post    = $original_post;
                $product = $original_product;
                if ( $post ) {
                    setup_postdata( $post );
                } else {
                    wp_reset_postdata();
                }

                // Inject Icons safely into HTML output
                $tabs_html = $this->inject_tab_icons( $tabs_html, $settings );

                echo $tabs_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            } elseif ( $is_editor ) {
                $this->render_editor_mock( $settings );
            }
            ?>
        </div>

        <?php
        if ( $is_editor ) :
        ?>
            <script type="text/javascript">
                jQuery( function( $ ) {
                    $( '.wc-tabs-wrapper, .woocommerce-tabs, #rating' ).trigger( 'init' );
                } );
            </script>
        <?php
        endif;
    }
}
