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
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * UltraAddons WC Mini Cart Widget
 * 
 * High-performance WooCommerce Mini Cart widget supporting Dropdown & Off-Canvas Sidebar
 * with live AJAX fragments, custom SVG icon presets, item badges, and complete styling.
 * 
 * @since 2.0.3
 */
class Cart extends Base {

    /**
     * Constructor — register style and script dependencies
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        wp_register_style(
            'ultraaddons-cart',
            ULTRA_ADDONS_ASSETS . 'css/widgets/cart.css',
            [],
            ULTRA_ADDONS_VERSION,
            'all'
        );
        wp_enqueue_style( 'ultraaddons-cart' );

        wp_register_script(
            'ultraaddons-cart',
            ULTRA_ADDONS_ASSETS . 'js/frontend-cart.js',
            [ 'jquery' ],
            ULTRA_ADDONS_VERSION,
            true
        );
        wp_enqueue_script( 'ultraaddons-cart' );
    }

    public function get_style_depends() {
        return [ 'ultraaddons-cart' ];
    }

    public function get_script_depends() {
        return [ 'jquery', 'ultraaddons-cart' ];
    }

    public function is_reload_preview_required() {
        return true;
    }

    public function get_keywords() {
        return [ 'ultraaddons-elementor-lite', 'ua', 'cart', 'mini cart', 'wc', 'woocommerce', 'shop', 'bag', 'basket', 'checkout' ];
    }

    /**
     * Register Widget Controls
     */
    protected function register_controls() {
        // CONTENT TAB
        $this->content_general_controls();
        $this->content_elements_controls();

        // STYLE TAB
        $this->style_button_controls();
        $this->style_badge_controls();
        $this->style_panel_controls();
        $this->style_items_controls();
        $this->style_actions_controls();
        $this->style_empty_controls();
    }

    /*==========================================================================
     * CONTENT TAB — General (Icon, Prefix Text, Content Mode, Layout)
     *========================================================================*/
    protected function content_general_controls() {
        $this->start_controls_section(
            '_ua_cart_section_general',
            [
                'label' => esc_html__( 'General', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            '_ua_cart_icon_type',
            [
                'label'   => esc_html__( 'Icon Source', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'preset',
                'options' => [
                    'preset' => esc_html__( 'Icon Presets (SVG)', 'ultraaddons-elementor-lite' ),
                    'custom' => esc_html__( 'Custom Icon', 'ultraaddons-elementor-lite' ),
                    'none'   => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            '_ua_cart_icon_preset',
            [
                'label'     => esc_html__( 'Select Preset Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'cart-medium',
                'options'   => [
                    'cart-light'     => esc_html__( 'Cart (Light)', 'ultraaddons-elementor-lite' ),
                    'cart-medium'    => esc_html__( 'Cart (Medium)', 'ultraaddons-elementor-lite' ),
                    'cart-solid'     => esc_html__( 'Cart (Solid)', 'ultraaddons-elementor-lite' ),
                    'basket-light'   => esc_html__( 'Basket (Light)', 'ultraaddons-elementor-lite' ),
                    'basket-medium'  => esc_html__( 'Basket (Medium)', 'ultraaddons-elementor-lite' ),
                    'basket-solid'   => esc_html__( 'Basket (Solid)', 'ultraaddons-elementor-lite' ),
                    'bag-light'      => esc_html__( 'Bag (Light)', 'ultraaddons-elementor-lite' ),
                    'bag-medium'     => esc_html__( 'Bag (Medium)', 'ultraaddons-elementor-lite' ),
                    'bag-solid'      => esc_html__( 'Bag (Solid)', 'ultraaddons-elementor-lite' ),
                    'trolley-modern' => esc_html__( 'Trolley (Modern)', 'ultraaddons-elementor-lite' ),
                    'shopping-tote'  => esc_html__( 'Shopping Tote', 'ultraaddons-elementor-lite' ),
                    'cart-heart'     => esc_html__( 'Cart (Heart)', 'ultraaddons-elementor-lite' ),
                    'handbag-chic'   => esc_html__( 'Handbag (Chic)', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    '_ua_cart_icon_type' => 'preset',
                ],
            ]
        );

        $this->add_control(
            '_ua_cart_custom_icon',
            [
                'label'       => esc_html__( 'Custom Icon', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::ICONS,
                'default'     => [
                    'value'   => 'fas fa-shopping-bag',
                    'library' => 'fa-solid',
                ],
                'condition'   => [
                    '_ua_cart_icon_type' => 'custom',
                ],
            ]
        );

        $this->add_control(
            '_ua_cart_toggle_text',
            [
                'label'     => esc_html__( 'Toggle Text / Prefix', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'price',
                'options'   => [
                    'none'   => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                    'price'  => esc_html__( 'Total Price (Live Subtotal)', 'ultraaddons-elementor-lite' ),
                    'custom' => esc_html__( 'Custom Text', 'ultraaddons-elementor-lite' ),
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            '_ua_cart_toggle_title',
            [
                'label'       => esc_html__( 'Custom Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Cart', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'Cart', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'condition'   => [
                    '_ua_cart_toggle_text' => 'custom',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_cart_btn_align',
            [
                'label'     => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => 'right',
                'options'   => [
                    'left'   => [
                        'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-mini-cart-wrapper' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}}'                       => 'text-align: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            '_ua_cart_content_type',
            [
                'label'     => esc_html__( 'Cart Content Display', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'sidebar',
                'options'   => [
                    'none'     => esc_html__( 'None (Link to Cart Page)', 'ultraaddons-elementor-lite' ),
                    'dropdown' => esc_html__( 'Dropdown Popup', 'ultraaddons-elementor-lite' ),
                    'sidebar'  => esc_html__( 'Off-Canvas Sidebar Drawer', 'ultraaddons-elementor-lite' ),
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            '_ua_cart_trigger',
            [
                'label'     => esc_html__( 'Open Trigger', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'click',
                'options'   => [
                    'click' => esc_html__( 'On Click', 'ultraaddons-elementor-lite' ),
                    'hover' => esc_html__( 'On Hover', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    '_ua_cart_content_type' => [ 'dropdown', 'sidebar' ],
                ],
            ]
        );

        $this->add_control(
            '_ua_cart_sidebar_position',
            [
                'label'     => esc_html__( 'Sidebar Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'right',
                'options'   => [
                    'right' => esc_html__( 'Slide from Right', 'ultraaddons-elementor-lite' ),
                    'left'  => esc_html__( 'Slide from Left', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    '_ua_cart_content_type' => 'sidebar',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_cart_sidebar_width',
            [
                'label'      => esc_html__( 'Drawer Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'vw' ],
                'range'      => [
                    'px' => [ 'min' => 280, 'max' => 600, 'step' => 1 ],
                    '%'  => [ 'min' => 20, 'max' => 100, 'step' => 1 ],
                    'vw' => [ 'min' => 20, 'max' => 100, 'step' => 1 ],
                ],
                'default'    => [ 'size' => 380, 'unit' => 'px' ],
                'condition'  => [
                    '_ua_cart_content_type' => 'sidebar',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-mini-cart-drawer' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_cart_dropdown_width',
            [
                'label'      => esc_html__( 'Dropdown Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [ 'min' => 280, 'max' => 500, 'step' => 1 ],
                ],
                'default'    => [ 'size' => 340, 'unit' => 'px' ],
                'condition'  => [
                    '_ua_cart_content_type' => 'dropdown',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-mini-cart-dropdown' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_cart_header_title',
            [
                'label'       => esc_html__( 'Header Title', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Shopping Cart', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'Shopping Cart', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'condition'   => [
                    '_ua_cart_content_type!' => 'none',
                ],
                'separator'   => 'before',
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * CONTENT TAB — Cart Elements & Actions
     *========================================================================*/
    protected function content_elements_controls() {
        $this->start_controls_section(
            '_ua_cart_section_elements',
            [
                'label' => esc_html__( 'Elements & Visibility', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            '_ua_cart_show_badge',
            [
                'label'        => esc_html__( 'Cart Item Count Badge', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            '_ua_cart_hide_badge_empty',
            [
                'label'        => esc_html__( 'Hide Badge When Empty', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'condition'    => [
                    '_ua_cart_show_badge' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_cart_show_thumbnail',
            [
                'label'        => esc_html__( 'Product Thumbnail', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            '_ua_cart_show_price_qty',
            [
                'label'        => esc_html__( 'Price & Quantity', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            '_ua_cart_show_remove',
            [
                'label'        => esc_html__( 'Remove Item Button', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            '_ua_cart_show_subtotal',
            [
                'label'        => esc_html__( 'Subtotal Section', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            '_ua_cart_show_view_cart',
            [
                'label'        => esc_html__( 'View Cart Button', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            '_ua_cart_view_cart_text',
            [
                'label'       => esc_html__( 'View Cart Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'View Cart', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'condition'   => [
                    '_ua_cart_show_view_cart' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_cart_show_checkout',
            [
                'label'        => esc_html__( 'Checkout Button', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            '_ua_cart_checkout_text',
            [
                'label'       => esc_html__( 'Checkout Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Checkout', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'condition'   => [
                    '_ua_cart_show_checkout' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_cart_buttons_layout',
            [
                'label'     => esc_html__( 'Buttons Layout', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'inline',
                'options'   => [
                    'inline'  => esc_html__( 'Side by Side (Inline)', 'ultraaddons-elementor-lite' ),
                    'stacked' => esc_html__( 'Stacked (Full Width)', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            '_ua_cart_empty_message',
            [
                'label'       => esc_html__( 'Empty Cart Message', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'No products in the cart.', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'No products in the cart.', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            '_ua_cart_show_return_shop',
            [
                'label'        => esc_html__( 'Return To Shop Button', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            '_ua_cart_return_shop_text',
            [
                'label'       => esc_html__( 'Return Button Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Start Shopping', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'condition'   => [
                    '_ua_cart_show_return_shop' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Cart Toggle Button
     *========================================================================*/
    protected function style_button_controls() {
        $this->start_controls_section(
            '_ua_cart_style_button',
            [
                'label' => esc_html__( 'Cart Toggle Button', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Icon Heading
        $this->add_control(
            '_ua_cart_heading_icon',
            [
                'label' => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_responsive_control(
            '_ua_cart_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [ 'px' => [ 'min' => 10, 'max' => 60 ] ],
                'default'    => [ 'size' => 21, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-mini-cart-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-mini-cart-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_cart_icon_spacing',
            [
                'label'      => esc_html__( 'Icon & Text Gap', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
                'default'    => [ 'size' => 8, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-mini-cart-toggle-btn' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Text Heading
        $this->add_control(
            '_ua_cart_heading_text',
            [
                'label'     => esc_html__( 'Text / Price', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => '_ua_cart_text_typography',
                'selector' => '{{WRAPPER}} .ua-mini-cart-text, {{WRAPPER}} .ua-mini-cart-price',
            ]
        );

        // Normal / Hover Tabs
        $this->start_controls_tabs( '_ua_cart_tabs_button' );

        $this->start_controls_tab(
            '_ua_cart_tab_btn_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            '_ua_cart_btn_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1e293b',
                'selectors' => [
                    '{{WRAPPER}} .ua-mini-cart-icon i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-mini-cart-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_cart_btn_text_color',
            [
                'label'     => esc_html__( 'Text / Price Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1e293b',
                'selectors' => [
                    '{{WRAPPER}} .ua-mini-cart-text'  => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-mini-cart-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => '_ua_cart_btn_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-mini-cart-toggle-btn',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => '_ua_cart_btn_border',
                'selector' => '{{WRAPPER}} .ua-mini-cart-toggle-btn',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_ua_cart_tab_btn_hover',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            '_ua_cart_btn_icon_color_hover',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-mini-cart-toggle-btn:hover .ua-mini-cart-icon i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-mini-cart-toggle-btn:hover .ua-mini-cart-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_cart_btn_text_color_hover',
            [
                'label'     => esc_html__( 'Text / Price Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-mini-cart-toggle-btn:hover .ua-mini-cart-text'  => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-mini-cart-toggle-btn:hover .ua-mini-cart-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => '_ua_cart_btn_bg_hover',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-mini-cart-toggle-btn:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => '_ua_cart_btn_border_hover',
                'selector' => '{{WRAPPER}} .ua-mini-cart-toggle-btn:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
            '_ua_cart_btn_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default'    => [
                    'top'    => '6',
                    'right'  => '6',
                    'bottom' => '6',
                    'left'   => '6',
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-mini-cart-toggle-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'before',
            ]
        );

        $this->add_responsive_control(
            '_ua_cart_btn_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default'    => [
                    'top'    => '8',
                    'right'  => '14',
                    'bottom' => '8',
                    'left'   => '14',
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-mini-cart-toggle-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => '_ua_cart_btn_shadow',
                'selector' => '{{WRAPPER}} .ua-mini-cart-toggle-btn',
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Item Count Badge
     *========================================================================*/
    protected function style_badge_controls() {
        $this->start_controls_section(
            '_ua_cart_style_badge',
            [
                'label'     => esc_html__( 'Item Count Badge', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    '_ua_cart_show_badge' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_cart_badge_color',
            [
                'label'     => esc_html__( 'Badge Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-mini-cart-badge' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_cart_badge_bg',
            [
                'label'     => esc_html__( 'Badge Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#13c392',
                'selectors' => [
                    '{{WRAPPER}} .ua-mini-cart-badge' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_cart_badge_font_size',
            [
                'label'      => esc_html__( 'Font Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 8, 'max' => 24 ] ],
                'default'    => [ 'size' => 12, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-mini-cart-badge' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_cart_badge_size',
            [
                'label'      => esc_html__( 'Badge Size (Width / Height)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 14, 'max' => 36 ] ],
                'default'    => [ 'size' => 22, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-mini-cart-badge' => 'min-width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_cart_badge_pos_y',
            [
                'label'      => esc_html__( 'Vertical Position (Up / Down)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range'      => [
                    '%' => [ 'min' => 0, 'max' => 150, 'step' => 1 ],
                ],
                'default'    => [ 'size' => 81, 'unit' => '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-mini-cart-badge' => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_cart_badge_pos_x',
            [
                'label'      => esc_html__( 'Horizontal Position (Left / Right)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range'      => [
                    '%' => [ 'min' => 0, 'max' => 150, 'step' => 1 ],
                ],
                'default'    => [ 'size' => 75, 'unit' => '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-mini-cart-badge' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Cart Panel / Drawer
     *========================================================================*/
    protected function style_panel_controls() {
        $this->start_controls_section(
            '_ua_cart_style_panel',
            [
                'label'     => esc_html__( 'Cart Panel / Drawer', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    '_ua_cart_content_type!' => 'none',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => '_ua_cart_panel_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-mini-cart-panel',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => '_ua_cart_panel_border',
                'selector' => '{{WRAPPER}} .ua-mini-cart-panel',
            ]
        );

        $this->add_responsive_control(
            '_ua_cart_panel_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-mini-cart-panel' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => '_ua_cart_panel_shadow',
                'selector' => '{{WRAPPER}} .ua-mini-cart-panel',
            ]
        );

        $this->add_control(
            '_ua_cart_backdrop_color',
            [
                'label'     => esc_html__( 'Backdrop Overlay Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(0, 0, 0, 0.45)',
                'condition' => [
                    '_ua_cart_content_type' => 'sidebar',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-mini-cart-backdrop' => 'background-color: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        // Header Styling
        $this->add_control(
            '_ua_cart_heading_header',
            [
                'label'     => esc_html__( 'Panel Header', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            '_ua_cart_header_color',
            [
                'label'     => esc_html__( 'Title Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1e293b',
                'selectors' => [
                    '{{WRAPPER}} .ua-mini-cart-header-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => '_ua_cart_header_typography',
                'selector' => '{{WRAPPER}} .ua-mini-cart-header-title',
            ]
        );

        $this->add_control(
            '_ua_cart_close_btn_color',
            [
                'label'     => esc_html__( 'Close Button Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#64748b',
                'selectors' => [
                    '{{WRAPPER}} .ua-mini-cart-close-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_cart_close_btn_color_hover',
            [
                'label'     => esc_html__( 'Close Button Hover Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ef4444',
                'selectors' => [
                    '{{WRAPPER}} .ua-mini-cart-close-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Cart Items List
     *========================================================================*/
    protected function style_items_controls() {
        $this->start_controls_section(
            '_ua_cart_style_items',
            [
                'label'     => esc_html__( 'Products List', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    '_ua_cart_content_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_cart_item_padding',
            [
                'label'      => esc_html__( 'Item Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default'    => [
                    'top'    => '14',
                    'right'  => '0',
                    'bottom' => '14',
                    'left'   => '0',
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-mini-cart-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_cart_item_separator_color',
            [
                'label'     => esc_html__( 'Divider Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f1f5f9',
                'selectors' => [
                    '{{WRAPPER}} .ua-mini-cart-item' => 'border-bottom-color: {{VALUE}};',
                ],
            ]
        );

        // Product Title
        $this->add_control(
            '_ua_cart_heading_title',
            [
                'label'     => esc_html__( 'Product Title', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            '_ua_cart_item_title_color',
            [
                'label'     => esc_html__( 'Title Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1e293b',
                'selectors' => [
                    '{{WRAPPER}} .ua-mini-cart-item-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_cart_item_title_color_hover',
            [
                'label'     => esc_html__( 'Title Hover Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#13c392',
                'selectors' => [
                    '{{WRAPPER}} .ua-mini-cart-item-title a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => '_ua_cart_item_title_typography',
                'selector' => '{{WRAPPER}} .ua-mini-cart-item-title a',
            ]
        );

        // Price & Quantity
        $this->add_control(
            '_ua_cart_heading_price_qty',
            [
                'label'     => esc_html__( 'Price & Quantity', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            '_ua_cart_item_price_color',
            [
                'label'     => esc_html__( 'Price Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#13c392',
                'selectors' => [
                    '{{WRAPPER}} .ua-mini-cart-item-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => '_ua_cart_item_price_typography',
                'selector' => '{{WRAPPER}} .ua-mini-cart-item-price',
            ]
        );

        // Thumbnail
        $this->add_control(
            '_ua_cart_heading_thumbnail',
            [
                'label'     => esc_html__( 'Thumbnail', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    '_ua_cart_show_thumbnail' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_cart_thumbnail_size',
            [
                'label'      => esc_html__( 'Thumbnail Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 40, 'max' => 120 ] ],
                'default'    => [ 'size' => 64, 'unit' => 'px' ],
                'condition'  => [
                    '_ua_cart_show_thumbnail' => 'yes',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-mini-cart-item-img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_cart_thumbnail_radius',
            [
                'label'      => esc_html__( 'Thumbnail Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'    => [
                    'top'    => '6',
                    'right'  => '6',
                    'bottom' => '6',
                    'left'   => '6',
                    'unit'   => 'px',
                ],
                'condition'  => [
                    '_ua_cart_show_thumbnail' => 'yes',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-mini-cart-item-img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Remove Button
        $this->add_control(
            '_ua_cart_heading_remove_btn',
            [
                'label'     => esc_html__( 'Remove Button', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    '_ua_cart_show_remove' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_cart_remove_color',
            [
                'label'     => esc_html__( 'Remove Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#94a3b8',
                'condition' => [
                    '_ua_cart_show_remove' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-mini-cart-remove-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_cart_remove_color_hover',
            [
                'label'     => esc_html__( 'Remove Icon Hover Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ef4444',
                'condition' => [
                    '_ua_cart_show_remove' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-mini-cart-remove-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Subtotal & Action Buttons
     *========================================================================*/
    protected function style_actions_controls() {
        $this->start_controls_section(
            '_ua_cart_style_actions',
            [
                'label'     => esc_html__( 'Subtotal & Buttons', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    '_ua_cart_content_type!' => 'none',
                ],
            ]
        );

        // Subtotal Section
        $this->add_control(
            '_ua_cart_heading_subtotal',
            [
                'label' => esc_html__( 'Subtotal', 'ultraaddons-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            '_ua_cart_subtotal_label_color',
            [
                'label'     => esc_html__( 'Subtotal Label Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#64748b',
                'selectors' => [
                    '{{WRAPPER}} .ua-mini-cart-subtotal-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_cart_subtotal_amount_color',
            [
                'label'     => esc_html__( 'Subtotal Amount Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1e293b',
                'selectors' => [
                    '{{WRAPPER}} .ua-mini-cart-subtotal-amount' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => '_ua_cart_subtotal_typography',
                'selector' => '{{WRAPPER}} .ua-mini-cart-subtotal-wrap',
            ]
        );

        // View Cart Button
        $this->add_control(
            '_ua_cart_heading_view_cart_btn',
            [
                'label'     => esc_html__( 'View Cart Button', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    '_ua_cart_show_view_cart' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs( '_ua_cart_tabs_view_cart' );

        $this->start_controls_tab(
            '_ua_cart_tab_vc_normal',
            [
                'label'     => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
                'condition' => [ '_ua_cart_show_view_cart' => 'yes' ],
            ]
        );

        $this->add_control(
            '_ua_cart_vc_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1e293b',
                'selectors' => [
                    '{{WRAPPER}} .ua-mini-cart-btn-view-cart' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => '_ua_cart_vc_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-mini-cart-btn-view-cart',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => '_ua_cart_vc_border',
                'selector' => '{{WRAPPER}} .ua-mini-cart-btn-view-cart',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_ua_cart_tab_vc_hover',
            [
                'label'     => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
                'condition' => [ '_ua_cart_show_view_cart' => 'yes' ],
            ]
        );

        $this->add_control(
            '_ua_cart_vc_text_color_hover',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#13c392',
                'selectors' => [
                    '{{WRAPPER}} .ua-mini-cart-btn-view-cart:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => '_ua_cart_vc_bg_hover',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-mini-cart-btn-view-cart:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => '_ua_cart_vc_border_hover',
                'selector' => '{{WRAPPER}} .ua-mini-cart-btn-view-cart:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        // Checkout Button
        $this->add_control(
            '_ua_cart_heading_checkout_btn',
            [
                'label'     => esc_html__( 'Checkout Button', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    '_ua_cart_show_checkout' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs( '_ua_cart_tabs_checkout' );

        $this->start_controls_tab(
            '_ua_cart_tab_co_normal',
            [
                'label'     => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
                'condition' => [ '_ua_cart_show_checkout' => 'yes' ],
            ]
        );

        $this->add_control(
            '_ua_cart_co_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-mini-cart-btn-checkout' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => '_ua_cart_co_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-mini-cart-btn-checkout',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => '_ua_cart_co_border',
                'selector' => '{{WRAPPER}} .ua-mini-cart-btn-checkout',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_ua_cart_tab_co_hover',
            [
                'label'     => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
                'condition' => [ '_ua_cart_show_checkout' => 'yes' ],
            ]
        );

        $this->add_control(
            '_ua_cart_co_text_color_hover',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-mini-cart-btn-checkout:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => '_ua_cart_co_bg_hover',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-mini-cart-btn-checkout:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => '_ua_cart_co_border_hover',
                'selector' => '{{WRAPPER}} .ua-mini-cart-btn-checkout:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => '_ua_cart_buttons_typography',
                'selector'  => '{{WRAPPER}} .ua-mini-cart-actions-wrap .ua-mini-cart-btn',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            '_ua_cart_buttons_radius',
            [
                'label'      => esc_html__( 'Button Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'    => [
                    'top'    => '6',
                    'right'  => '6',
                    'bottom' => '6',
                    'left'   => '6',
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-mini-cart-actions-wrap .ua-mini-cart-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_cart_buttons_padding',
            [
                'label'      => esc_html__( 'Button Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'    => [
                    'top'    => '12',
                    'right'  => '18',
                    'bottom' => '12',
                    'left'   => '18',
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-mini-cart-actions-wrap .ua-mini-cart-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Empty Cart State
     *========================================================================*/
    protected function style_empty_controls() {
        $this->start_controls_section(
            '_ua_cart_style_empty',
            [
                'label'     => esc_html__( 'Empty Cart State', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    '_ua_cart_content_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            '_ua_cart_empty_icon_color',
            [
                'label'     => esc_html__( 'Empty Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#94a3b8',
                'selectors' => [
                    '{{WRAPPER}} .ua-mini-cart-empty-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_cart_empty_msg_color',
            [
                'label'     => esc_html__( 'Message Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#64748b',
                'selectors' => [
                    '{{WRAPPER}} .ua-mini-cart-empty-msg' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => '_ua_cart_empty_msg_typography',
                'selector' => '{{WRAPPER}} .ua-mini-cart-empty-msg',
            ]
        );

        $this->add_control(
            '_ua_cart_return_btn_color',
            [
                'label'     => esc_html__( 'Return Button Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#13c392',
                'selectors' => [
                    '{{WRAPPER}} .ua-mini-cart-btn-return' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            '_ua_cart_return_btn_bg',
            [
                'label'     => esc_html__( 'Return Button Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'transparent',
                'selectors' => [
                    '{{WRAPPER}} .ua-mini-cart-btn-return' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * RENDER HELPERS
     *========================================================================*/

    /**
     * Render SVG preset icons
     */
    protected function render_svg_preset( $preset ) {
        $icons = [
            'cart-light'     => '<svg class="ua-cart-svg" viewBox="0 0 1000 1000" xmlns="http://www.w3.org/2000/svg"><path d="M708 854C708 889 736 917 771 917 805 917 833 889 833 854 833 820 805 792 771 792 736 792 708 820 708 854ZM188 167L938 167C950 167 960 178 958 190L926 450C919 502 875 542 822 542L263 542 271 583C281 632 324 667 373 667L854 667C866 667 875 676 875 687 875 699 866 708 854 708L373 708C304 708 244 659 230 591L129 83 21 83C9 83 0 74 0 62 0 51 9 42 21 42L146 42C156 42 164 49 166 58L188 167ZM196 208L255 500 822 500C854 500 880 476 884 445L914 208 196 208ZM667 854C667 797 713 750 771 750 828 750 875 797 875 854 875 912 828 958 771 958 713 958 667 912 667 854ZM250 854C250 797 297 750 354 750 412 750 458 797 458 854 458 912 412 958 354 958 297 958 250 912 250 854ZM292 854C292 889 320 917 354 917 389 917 417 889 417 854 417 820 389 792 354 792 292 820 292 854Z"></path></svg>',
            'cart-medium'    => '<svg class="ua-cart-svg" viewBox="0 0 1000 1000" xmlns="http://www.w3.org/2000/svg"><path d="M740 854C740 883 763 906 792 906S844 883 844 854 820 802 792 802 740 825 740 854ZM217 156H958C977 156 992 173 989 191L957 452C950 509 901 552 843 552H297L303 581C311 625 350 656 395 656H875C892 656 906 670 906 687S892 719 875 719H394C320 719 255 666 241 593L141 94H42C25 94 10 80 10 62S25 31 42 31H167C182 31 195 42 198 56L217 156ZM230 219L284 490H843C869 490 891 470 895 444L923 219H230ZM677 854C677 791 728 740 792 740S906 791 906 854 855 969 792 969 677 918 677 854ZM260 854C260 791 312 740 375 740S490 791 490 854 438 969 375 969 260 918 260 854ZM323 854C323 883 346 906 375 906S427 883 427 854 404 802 375 802 323 825 323 854Z"></path></svg>',
            'cart-solid'     => '<svg class="ua-cart-svg" viewBox="0 0 1000 1000" xmlns="http://www.w3.org/2000/svg"><path d="M188 167H938C943 167 949 169 953 174 957 178 959 184 958 190L926 450C919 502 875 542 823 542H263L271 583C281 631 324 667 373 667H854C866 667 875 676 875 687S866 708 854 708H373C304 708 244 659 230 591L129 83H21C9 83 0 74 0 62S9 42 21 42H146C156 42 164 49 166 58L188 167ZM771 750C828 750 875 797 875 854S828 958 771 958 667 912 667 854 713 750 771 750ZM354 750C412 750 458 797 458 854S412 958 354 958 250 912 250 854 297 750 354 750Z"></path></svg>',
            'basket-light'   => '<svg class="ua-cart-svg" viewBox="0 0 1000 1000" xmlns="http://www.w3.org/2000/svg"><path d="M125 375C125 375 125 375 125 375H256L324 172C332 145 358 125 387 125H655C685 125 711 145 718 173L786 375H916C917 375 917 375 917 375H979C991 375 1000 384 1000 396S991 417 979 417H935L873 798C860 844 820 875 773 875H270C223 875 182 844 169 796L107 417H63C51 417 42 407 42 396S51 375 63 375H125ZM150 417L210 787C217 814 242 833 270 833H773C801 833 825 815 833 790L893 417H150ZM742 375L679 185C676 174 666 167 655 167H387C376 167 367 174 364 184L300 375H742ZM500 521C500 509 509 500 521 500S542 509 542 521V729C542 741 533 750 521 750S500 741 500 729V521ZM687 732C685 743 675 751 663 750 652 748 644 737 646 726L675 520C677 508 688 500 699 502 710 504 718 514 717 526L687 732ZM395 726C397 737 389 748 378 750 367 752 356 744 354 732L325 526C323 515 331 504 343 502 354 500 365 508 366 520L395 726Z"></path></svg>',
            'basket-medium'  => '<svg class="ua-cart-svg" viewBox="0 0 1000 1000" xmlns="http://www.w3.org/2000/svg"><path d="M104 365C104 365 105 365 105 365H208L279 168C288 137 320 115 355 115H646C681 115 713 137 723 170L793 365H896C896 365 897 365 897 365H958C975 365 990 379 990 396S975 427 958 427H923L862 801C848 851 803 885 752 885H249C198 885 152 851 138 798L78 427H42C25 427 10 413 10 396S25 365 42 365H104ZM141 427L199 785C205 807 225 823 249 823H752C775 823 796 807 801 788L860 427H141ZM726 365L663 189C660 182 654 177 645 177H355C346 177 340 182 338 187L274 365H726ZM469 521C469 504 483 490 500 490S531 504 531 521V729C531 746 517 760 500 760S469 746 469 729V521ZM677 734C674 751 658 762 641 760 624 758 613 742 615 725L644 519C647 502 663 490 680 492S708 510 706 527L677 734ZM385 725C388 742 375 757 358 760 341 762 325 750 323 733L293 527C291 510 303 494 320 492 337 489 353 501 355 518L385 725Z"></path></svg>',
            'basket-solid'   => '<svg class="ua-cart-svg" viewBox="0 0 1000 1000" xmlns="http://www.w3.org/2000/svg"><path d="M128 417H63C51 417 42 407 42 396S51 375 63 375H256L324 172C332 145 358 125 387 125H655C685 125 711 145 718 173L786 375H979C991 375 1000 384 1000 396S991 417 979 417H913L853 793C843 829 810 854 772 854H270C233 854 200 829 190 793L128 417ZM742 375L679 185C676 174 666 167 655 167H387C376 167 367 174 364 184L300 375H742ZM500 521V729C500 741 509 750 521 750S542 741 542 729V521C542 509 533 500 521 500S500 509 500 521ZM687 732L717 526C718 515 710 504 699 502 688 500 677 508 675 520L646 726C644 737 652 748 663 750 675 751 686 743 687 732ZM395 726L366 520C364 509 354 501 342 502 331 504 323 515 325 526L354 732C356 744 366 752 378 750 389 748 397 737 395 726Z"></path></svg>',
            'bag-light'      => '<svg class="ua-cart-svg" viewBox="0 0 1000 1000" xmlns="http://www.w3.org/2000/svg"><path d="M333 292L333 208C339 100 397 43 501 43 605 43 662 100 667 209V292H750C796 292 833 329 833 375V875C833 921 796 958 750 958H250C204 958 167 921 167 875V375C167 329 204 292 250 292H333ZM375 292H625L625 210C622 125 582 85 501 85 420 85 380 125 375 209L375 292ZM333 333H250C227 333 208 352 208 375V875C208 898 227 917 250 917H750C773 917 792 898 792 875V375C792 352 773 333 750 333H667V454C667 466 658 475 646 475S625 466 625 454L625 333H375L375 454C375 466 366 475 354 475 343 475 333 466 333 454L333 333Z"></path></svg>',
            'bag-medium'     => '<svg class="ua-cart-svg" viewBox="0 0 1000 1000" xmlns="http://www.w3.org/2000/svg"><path d="M323 292L323 207C329 95 391 33 501 33 610 33 673 95 677 209V292H750C796 292 833 329 833 375V875C833 921 796 958 750 958H250C204 958 167 921 167 875V375C167 329 204 292 250 292H323ZM385 292H615L615 210C611 130 577 95 501 95 425 95 390 130 385 209L385 292ZM323 354H250C238 354 229 363 229 375V875C229 887 238 896 250 896H750C762 896 771 887 771 875V375C771 363 762 354 750 354H677V454C677 471 663 485 646 485S615 471 615 454L615 354H385L385 454C385 471 371 485 354 485 337 485 323 471 323 454L323 354Z"></path></svg>',
            'bag-solid'      => '<svg class="ua-cart-svg" viewBox="0 0 1000 1000" xmlns="http://www.w3.org/2000/svg"><path d="M333 292L333 208C339 100 397 43 501 43 605 43 662 100 667 209V292H750C796 292 833 329 833 375V875C833 921 796 958 750 958H250C204 958 167 921 167 875V375C167 329 204 292 250 292H333ZM375 292H625L625 210C622 125 582 85 501 85 420 85 380 125 375 209L375 292Z"></path></svg>',
            'trolley-modern' => '<svg class="ua-cart-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1.5"></circle><circle cx="19" cy="21" r="1.5"></circle><path d="M2.5 2.5h3l2.7 12.4a2 2 0 0 0 2 1.6h8.8a2 2 0 0 0 2-1.6l1.5-7.4H6.5"></path></svg>',
            'shopping-tote'  => '<svg class="ua-cart-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>',
            'cart-heart'     => '<svg class="ua-cart-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path><path d="M14.5 9c-.83 0-1.5.67-1.5 1.5 0 1.2 1.5 2.5 1.5 2.5s1.5-1.3 1.5-2.5c0-.83-.67-1.5-1.5-1.5z" fill="currentColor"></path></svg>',
            'handbag-chic'   => '<svg class="ua-cart-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="8" width="18" height="13" rx="3"></rect><path d="M8 8V6a4 4 0 0 1 8 0v2"></path><circle cx="12" cy="14" r="1.5" fill="currentColor"></circle></svg>',
        ];

        if ( isset( $icons[ $preset ] ) ) {
            echo $icons[ $preset ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
    }

    /**
     * Render Toggle Button
     */
    protected function render_toggle_button( $settings ) {
        $cart_count = ( null !== WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
        $subtotal   = ( null !== WC()->cart ) ? WC()->cart->get_cart_subtotal() : '$0.00';
        $cart_url   = ( $settings['_ua_cart_content_type'] === 'none' && function_exists( 'wc_get_cart_url' ) ) ? wc_get_cart_url() : '#';
        $badge_hide = ( ! empty( $settings['_ua_cart_hide_badge_empty'] ) && $settings['_ua_cart_hide_badge_empty'] === 'yes' && $cart_count === 0 ) ? 'ua-badge-hidden' : '';
        ?>
        <a href="<?php echo esc_url( $cart_url ); ?>" class="ua-mini-cart-toggle-btn" aria-expanded="false">
            <?php if ( ! empty( $settings['_ua_cart_toggle_text'] ) && $settings['_ua_cart_toggle_text'] !== 'none' ) : ?>
                <?php if ( $settings['_ua_cart_toggle_text'] === 'price' ) : ?>
                    <span class="ua-mini-cart-price"><?php echo wp_kses_post( $subtotal ); ?></span>
                <?php elseif ( $settings['_ua_cart_toggle_text'] === 'custom' && ! empty( $settings['_ua_cart_toggle_title'] ) ) : ?>
                    <span class="ua-mini-cart-text"><?php echo esc_html( $settings['_ua_cart_toggle_title'] ); ?></span>
                <?php endif; ?>
            <?php endif; ?>

            <span class="ua-mini-cart-icon-wrap">
                <span class="ua-mini-cart-icon">
                    <?php
                    if ( $settings['_ua_cart_icon_type'] === 'preset' ) {
                        $this->render_svg_preset( $settings['_ua_cart_icon_preset'] );
                    } elseif ( $settings['_ua_cart_icon_type'] === 'custom' && ! empty( $settings['_ua_cart_custom_icon']['value'] ) ) {
                        Icons_Manager::render_icon( $settings['_ua_cart_custom_icon'], [ 'aria-hidden' => 'true' ] );
                    }
                    ?>
                </span>

                <?php if ( ! empty( $settings['_ua_cart_show_badge'] ) && $settings['_ua_cart_show_badge'] === 'yes' ) : ?>
                    <span class="ua-mini-cart-badge <?php echo esc_attr( $badge_hide ); ?>" data-count="<?php echo esc_attr( $cart_count ); ?>">
                        <?php echo esc_html( $cart_count ); ?>
                    </span>
                <?php endif; ?>
            </span>
        </a>
        <?php
    }

    /**
     * Render Mini Cart Content (Dropdown or Sidebar Panel)
     */
    public function render_mini_cart_panel( $settings ) {
        if ( null === WC()->cart ) {
            return;
        }

        $cart_items = WC()->cart->get_cart();
        $is_empty   = WC()->cart->is_empty();
        $shop_url   = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : get_permalink( wc_get_page_id( 'shop' ) );
        if ( ! empty( $settings['_ua_cart_return_shop_url']['url'] ) ) {
            $shop_url = $settings['_ua_cart_return_shop_url']['url'];
        }
        ?>
        <div class="ua-mini-cart-panel">
            <!-- Header -->
            <div class="ua-mini-cart-header">
                <h4 class="ua-mini-cart-header-title">
                    <?php echo esc_html( ! empty( $settings['_ua_cart_header_title'] ) ? $settings['_ua_cart_header_title'] : __( 'Shopping Cart', 'ultraaddons-elementor-lite' ) ); ?>
                </h4>
                <button type="button" class="ua-mini-cart-close-btn" aria-label="<?php esc_attr_e( 'Close', 'ultraaddons-elementor-lite' ); ?>">
                    <?php
                    if ( ! empty( $settings['_ua_cart_close_icon']['value'] ) ) {
                        Icons_Manager::render_icon( $settings['_ua_cart_close_icon'], [ 'aria-hidden' => 'true' ] );
                    } else {
                        echo '&times;';
                    }
                    ?>
                </button>
            </div>

            <!-- Cart Body / Items Wrapper -->
            <div class="ua-mini-cart-body">
                <?php if ( ! $is_empty ) : ?>
                    <ul class="ua-mini-cart-items-list">
                        <?php
                        foreach ( $cart_items as $cart_item_key => $cart_item ) {
                            $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                            $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                                $product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
                                $thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                                $product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                                $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                                ?>
                                <li class="ua-mini-cart-item" data-key="<?php echo esc_attr( $cart_item_key ); ?>">
                                    <?php if ( ! empty( $settings['_ua_cart_show_thumbnail'] ) && $settings['_ua_cart_show_thumbnail'] === 'yes' ) : ?>
                                        <div class="ua-mini-cart-item-img">
                                            <?php if ( ! empty( $product_permalink ) ) : ?>
                                                <a href="<?php echo esc_url( $product_permalink ); ?>">
                                                    <?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                                </a>
                                            <?php else : ?>
                                                <?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="ua-mini-cart-item-details">
                                        <h5 class="ua-mini-cart-item-title">
                                            <?php if ( ! empty( $product_permalink ) ) : ?>
                                                <a href="<?php echo esc_url( $product_permalink ); ?>">
                                                    <?php echo wp_kses_post( $product_name ); ?>
                                                </a>
                                            <?php else : ?>
                                                <?php echo wp_kses_post( $product_name ); ?>
                                            <?php endif; ?>
                                        </h5>

                                        <?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

                                        <?php if ( ! empty( $settings['_ua_cart_show_price_qty'] ) && $settings['_ua_cart_show_price_qty'] === 'yes' ) : ?>
                                            <div class="ua-mini-cart-item-price">
                                                <span class="ua-qty"><?php echo esc_html( $cart_item['quantity'] ); ?></span>
                                                <span class="ua-times">&times;</span>
                                                <span class="ua-amount"><?php echo wp_kses_post( $product_price ); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <?php if ( ! empty( $settings['_ua_cart_show_remove'] ) && $settings['_ua_cart_show_remove'] === 'yes' ) : ?>
                                        <div class="ua-mini-cart-item-remove">
                                            <a href="<?php echo esc_url( wc_get_cart_remove_url( $cart_item_key ) ); ?>"
                                               class="ua-mini-cart-remove-btn remove remove_from_cart_button"
                                               aria-label="<?php esc_attr_e( 'Remove this item', 'woocommerce' ); ?>"
                                               data-product_id="<?php echo esc_attr( $product_id ); ?>"
                                               data-cart_item_key="<?php echo esc_attr( $cart_item_key ); ?>"
                                               data-product_sku="<?php echo esc_attr( $_product->get_sku() ); ?>">
                                                &times;
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </li>
                                <?php
                            }
                        }
                        ?>
                    </ul>
                <?php else : ?>
                    <div class="ua-mini-cart-empty-state">
                        <div class="ua-mini-cart-empty-icon">
                            <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                        </div>
                        <p class="ua-mini-cart-empty-msg">
                            <?php echo esc_html( ! empty( $settings['_ua_cart_empty_message'] ) ? $settings['_ua_cart_empty_message'] : __( 'No products in the cart.', 'woocommerce' ) ); ?>
                        </p>
                        <?php if ( ! empty( $settings['_ua_cart_show_return_shop'] ) && $settings['_ua_cart_show_return_shop'] === 'yes' ) : ?>
                            <a href="<?php echo esc_url( $shop_url ); ?>" class="ua-mini-cart-btn ua-mini-cart-btn-return">
                                <?php echo esc_html( ! empty( $settings['_ua_cart_return_shop_text'] ) ? $settings['_ua_cart_return_shop_text'] : __( 'Return to Shop', 'ultraaddons-elementor-lite' ) ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Footer (Subtotal & Buttons) -->
            <?php if ( ! $is_empty ) : ?>
                <div class="ua-mini-cart-footer">
                    <?php if ( ! empty( $settings['_ua_cart_show_subtotal'] ) && $settings['_ua_cart_show_subtotal'] === 'yes' ) : ?>
                        <div class="ua-mini-cart-subtotal-wrap">
                            <span class="ua-mini-cart-subtotal-label"><?php esc_html_e( 'Subtotal:', 'woocommerce' ); ?></span>
                            <span class="ua-mini-cart-subtotal-amount"><?php echo wp_kses_post( WC()->cart->get_cart_subtotal() ); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php
                    $buttons_layout = ! empty( $settings['_ua_cart_buttons_layout'] ) ? $settings['_ua_cart_buttons_layout'] : 'inline';
                    ?>
                    <div class="ua-mini-cart-actions-wrap ua-layout-<?php echo esc_attr( $buttons_layout ); ?>">
                        <?php if ( ! empty( $settings['_ua_cart_show_view_cart'] ) && $settings['_ua_cart_show_view_cart'] === 'yes' ) : ?>
                            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="ua-mini-cart-btn ua-mini-cart-btn-view-cart">
                                <?php echo esc_html( ! empty( $settings['_ua_cart_view_cart_text'] ) ? $settings['_ua_cart_view_cart_text'] : __( 'View Cart', 'woocommerce' ) ); ?>
                            </a>
                        <?php endif; ?>

                        <?php if ( ! empty( $settings['_ua_cart_show_checkout'] ) && $settings['_ua_cart_show_checkout'] === 'yes' ) : ?>
                            <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="ua-mini-cart-btn ua-mini-cart-btn-checkout">
                                <?php echo esc_html( ! empty( $settings['_ua_cart_checkout_text'] ) ? $settings['_ua_cart_checkout_text'] : __( 'Checkout', 'woocommerce' ) ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Render Widget Output
     */
    protected function render() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            ?>
            <div class="ua-mini-cart-notice">
                <p><?php esc_html_e( 'WooCommerce must be installed and active to use the Mini Cart widget.', 'ultraaddons-elementor-lite' ); ?></p>
            </div>
            <?php
            return;
        }

        $settings     = $this->get_settings_for_display();
        $content_type = ! empty( $settings['_ua_cart_content_type'] ) ? $settings['_ua_cart_content_type'] : 'sidebar';
        $trigger      = ! empty( $settings['_ua_cart_trigger'] ) ? $settings['_ua_cart_trigger'] : 'click';
        $position     = ! empty( $settings['_ua_cart_sidebar_position'] ) ? $settings['_ua_cart_sidebar_position'] : 'right';

        $container_classes = [
            'ua-mini-cart-container',
            'ua-content-' . $content_type,
            'ua-trigger-' . $trigger,
            'ua-position-' . $position,
        ];
        ?>
        <div class="ua-mini-cart-wrapper">
            <div class="<?php echo esc_attr( implode( ' ', $container_classes ) ); ?>"
                 data-content-type="<?php echo esc_attr( $content_type ); ?>"
                 data-trigger="<?php echo esc_attr( $trigger ); ?>"
                 data-position="<?php echo esc_attr( $position ); ?>">

                <!-- Toggle Button -->
                <?php $this->render_toggle_button( $settings ); ?>

                <!-- Dropdown or Sidebar Wrapper -->
                <?php if ( $content_type === 'dropdown' ) : ?>
                    <div class="ua-mini-cart-dropdown">
                        <div class="ua-mini-cart-fragments-wrap">
                            <?php $this->render_mini_cart_panel( $settings ); ?>
                        </div>
                    </div>
                <?php elseif ( $content_type === 'sidebar' ) : ?>
                    <div class="ua-mini-cart-backdrop"></div>
                    <div class="ua-mini-cart-drawer ua-drawer-<?php echo esc_attr( $position ); ?>">
                        <div class="ua-mini-cart-fragments-wrap">
                            <?php $this->render_mini_cart_panel( $settings ); ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
        <?php
    }
}
