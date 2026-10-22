<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

/**
 * Woo Cart Page Widget
 *
 * Full WooCommerce cart page replacement widget with interactive AJAX quantity steppers,
 * auto-updating calculations, coupon application, order summary, cross-sells,
 * custom empty cart state, and multiple design presets.
 *
 * @since 3.0.1
 * @package UltraAddons\Widget
 */
class Woo_Cart extends Base {

    /**
     * Constructor — register style and script dependencies
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        wp_register_style(
            'ultraaddons-woo-cart',
            ULTRA_ADDONS_ASSETS . 'css/widgets/woo-cart.css',
            [],
            ULTRA_ADDONS_VERSION,
            'all'
        );

        wp_register_script(
            'ultraaddons-woo-cart',
            ULTRA_ADDONS_ASSETS . 'js/widgets/woo-cart.js',
            [ 'jquery' ],
            ULTRA_ADDONS_VERSION,
            true
        );
    }

    /**
     * Widget Name
     */
    public function get_name() {
        return 'ultraaddons-woo-cart';
    }

    /**
     * Widget Title
     */
    public function get_title() {
        return esc_html__( 'Woo Cart', 'ultraaddons-elementor-lite' );
    }

    /**
     * Widget Icon
     */
    public function get_icon() {
        return 'ultraaddons uicon-cart';
    }

    /**
     * Widget Categories
     */
    public function get_categories() {
        return [ 'ultraaddons-wc', 'ultraaddons-elementor-lite' ];
    }

    /**
     * Widget Keywords
     */
    public function get_keywords() {
        return [ 'ultraaddons', 'ua', 'cart', 'woo cart', 'cart page', 'woocommerce cart', 'basket', 'checkout', 'shop', 'ecommerce' ];
    }

    /**
     * Enqueue styles
     */
    public function get_style_depends() {
        return [ 'ultraaddons-woo-cart' ];
    }

    /**
     * Enqueue scripts
     */
    public function get_script_depends() {
        return [ 'jquery', 'ultraaddons-woo-cart' ];
    }

    /**
     * Reload preview on change
     */
    public function is_reload_preview_required() {
        return true;
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        // Content Tab
        $this->register_content_general_controls();
        $this->register_content_table_controls();
        $this->register_content_coupon_controls();
        $this->register_content_totals_controls();
        $this->register_content_buttons_controls();
        $this->register_content_cross_sells_controls();
        $this->register_content_empty_controls();

        // Style Tab
        $this->register_style_table_container_controls();
        $this->register_style_table_header_controls();
        $this->register_style_table_rows_controls();
        $this->register_style_product_info_controls();
        $this->register_style_price_controls();
        $this->register_style_quantity_controls();
        $this->register_style_remove_btn_controls();
        $this->register_style_coupon_controls();
        $this->register_style_totals_box_controls();
        $this->register_style_checkout_btn_controls();
        $this->register_style_cross_sells_controls();
        $this->register_style_empty_cart_controls();
    }

    /* =========================================================================
       CONTENT TAB CONTROLS
       ========================================================================= */

    /**
     * Content: General & Layout Settings
     */
    protected function register_content_general_controls() {
        $this->start_controls_section(
            'ua_cart_section_general',
            [
                'label' => esc_html__( 'General & Layout', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'layout',
            [
                'label'   => esc_html__( 'Layout Mode', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'two-column' => esc_html__( 'Two Columns (Table + Summary)', 'ultraaddons-elementor-lite' ),
                    'one-column' => esc_html__( 'One Column (Stacked)', 'ultraaddons-elementor-lite' ),
                ],
                'default' => 'two-column',
            ]
        );

        $this->add_control(
            'preset',
            [
                'label'   => esc_html__( 'Style Preset', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'default' => esc_html__( 'Default (Clean Indigo)', 'ultraaddons-elementor-lite' ),
                    'modern'  => esc_html__( 'Modern (Vibrant Gradient)', 'ultraaddons-elementor-lite' ),
                    'minimal' => esc_html__( 'Minimal (Borderless)', 'ultraaddons-elementor-lite' ),
                    'bold'    => esc_html__( 'Bold (Dark Mode)', 'ultraaddons-elementor-lite' ),
                ],
                'default' => 'default',
            ]
        );

        $this->add_control(
            'sticky_summary',
            [
                'label'        => esc_html__( 'Sticky Summary Column', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'layout' => 'two-column',
                ],
                'description'  => esc_html__( 'Keeps the Cart Totals summary visible while scrolling long cart lists.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'sticky_offset',
            [
                'label'      => esc_html__( 'Sticky Top Offset', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 200, 'step' => 5 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'condition'  => [
                    'layout'         => 'two-column',
                    'sticky_summary' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'auto_update_cart',
            [
                'label'        => esc_html__( 'Auto Update Cart', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'description'  => esc_html__( 'Instantly recalculates prices and totals on quantity change without full page reload.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'show_continue_shopping',
            [
                'label'        => esc_html__( 'Show Continue Shopping Link', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'continue_shopping_text',
            [
                'label'     => esc_html__( 'Continue Shopping Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( '← Continue Shopping', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'show_continue_shopping' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'continue_shopping_url',
            [
                'label'       => esc_html__( 'Continue Shopping URL', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'Leave empty for default WooCommerce Shop URL', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'show_continue_shopping' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content: Table Components Visibility
     */
    protected function register_content_table_controls() {
        $this->start_controls_section(
            'ua_cart_section_table',
            [
                'label' => esc_html__( 'Table Components', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_thumbnail',
            [
                'label'        => esc_html__( 'Product Image', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'show_product_name',
            [
                'label'        => esc_html__( 'Product Name', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'show_unit_price',
            [
                'label'        => esc_html__( 'Unit Price', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'show_quantity',
            [
                'label'        => esc_html__( 'Quantity Stepper', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'show_subtotal',
            [
                'label'        => esc_html__( 'Subtotal', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'show_remove_btn',
            [
                'label'        => esc_html__( 'Remove (×) Button', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content: Coupon Section
     */
    protected function register_content_coupon_controls() {
        $this->start_controls_section(
            'ua_cart_section_coupon',
            [
                'label' => esc_html__( 'Coupon Code Form', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_coupon',
            [
                'label'        => esc_html__( 'Show Coupon Code Field', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'coupon_placeholder',
            [
                'label'     => esc_html__( 'Coupon Placeholder', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Coupon code', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'show_coupon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'apply_coupon_text',
            [
                'label'     => esc_html__( 'Apply Coupon Button Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Apply Coupon', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'show_coupon' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content: Cart Totals & Shipping Calculator
     */
    protected function register_content_totals_controls() {
        $this->start_controls_section(
            'ua_cart_section_totals',
            [
                'label' => esc_html__( 'Cart Totals & Summary', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'totals_heading',
            [
                'label'   => esc_html__( 'Section Title', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Cart Totals', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'totals_heading_tag',
            [
                'label'   => esc_html__( 'Title HTML Tag', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                ],
                'default' => 'h3',
            ]
        );

        $this->add_control(
            'show_shipping_calc',
            [
                'label'        => esc_html__( 'Shipping Calculator', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
                'description'  => esc_html__( 'Allows customers to calculate estimated shipping rates before checkout.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content: Buttons & Labels
     */
    protected function register_content_buttons_controls() {
        $this->start_controls_section(
            'ua_cart_section_buttons',
            [
                'label' => esc_html__( 'Buttons & Action Labels', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'update_cart_text',
            [
                'label'   => esc_html__( 'Update Cart Button Text', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Update Cart', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'checkout_text',
            [
                'label'   => esc_html__( 'Checkout Button Text', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Proceed to Checkout', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content: Cross-Sells
     */
    protected function register_content_cross_sells_controls() {
        $this->start_controls_section(
            'ua_cart_section_cross_sells',
            [
                'label' => esc_html__( 'Cross-Sells Products', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_cross_sells',
            [
                'label'        => esc_html__( 'Show Cross-Sells', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
                'description'  => esc_html__( 'Displays recommended products configured in WooCommerce Linked Products.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'cross_sells_title',
            [
                'label'     => esc_html__( 'Cross-Sells Heading', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'You may also like…', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'show_cross_sells' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'cross_sells_columns',
            [
                'label'     => esc_html__( 'Grid Columns', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    '2' => '2 Columns',
                    '3' => '3 Columns',
                    '4' => '4 Columns',
                ],
                'default'   => '3',
                'condition' => [
                    'show_cross_sells' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'cross_sells_limit',
            [
                'label'     => esc_html__( 'Max Products', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 1,
                'max'       => 12,
                'step'      => 1,
                'default'   => 3,
                'condition' => [
                    'show_cross_sells' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content: Empty Cart State
     */
    protected function register_content_empty_controls() {
        $this->start_controls_section(
            'ua_cart_section_empty',
            [
                'label' => esc_html__( 'Empty Cart State', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'empty_cart_icon',
            [
                'label'   => esc_html__( 'Empty Cart Icon', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fas fa-shopping-basket',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'empty_cart_title',
            [
                'label'   => esc_html__( 'Empty Title', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Your cart is currently empty.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'empty_cart_message',
            [
                'label'   => esc_html__( 'Empty Message', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Before proceed to checkout you must add some products to your shopping cart. You will find a lot of interesting products on our shop page.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'empty_cart_btn_text',
            [
                'label'   => esc_html__( 'Return to Shop Button Text', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Return to Shop', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'empty_cart_btn_url',
            [
                'label'       => esc_html__( 'Return to Shop URL', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'Leave empty for default WooCommerce Shop URL', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE TAB CONTROLS
       ========================================================================= */

    /**
     * Style: Table Container
     */
    protected function register_style_table_container_controls() {
        $this->start_controls_section(
            'ua_style_table_container',
            [
                'label' => esc_html__( 'Table Container', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'table_container_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-woo-cart-table-wrap',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'table_container_border',
                'selector' => '{{WRAPPER}} .ua-woo-cart-table-wrap',
            ]
        );

        $this->add_responsive_control(
            'table_container_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-woo-cart-table-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'table_container_shadow',
                'selector' => '{{WRAPPER}} .ua-woo-cart-table-wrap',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Table Header Row
     */
    protected function register_style_table_header_controls() {
        $this->start_controls_section(
            'ua_style_table_header',
            [
                'label' => esc_html__( 'Table Header', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'header_typography',
                'selector' => '{{WRAPPER}} .ua-woo-cart-table thead tr th',
            ]
        );

        $this->add_control(
            'header_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-woo-cart-table thead tr th' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'header_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-woo-cart-table thead tr th' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'header_padding',
            [
                'label'      => esc_html__( 'Header Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-woo-cart-table thead tr th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Table Rows
     */
    protected function register_style_table_rows_controls() {
        $this->start_controls_section(
            'ua_style_table_rows',
            [
                'label' => esc_html__( 'Product Rows', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'row_odd_bg',
            [
                'label'     => esc_html__( 'Odd Rows Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-woo-cart-table tbody tr.ua-cart-item:nth-child(odd)' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'row_even_bg',
            [
                'label'     => esc_html__( 'Even Rows Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-woo-cart-table tbody tr.ua-cart-item:nth-child(even)' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'row_hover_bg',
            [
                'label'     => esc_html__( 'Hover Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-woo-cart-table tbody tr.ua-cart-item:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'row_divider_color',
            [
                'label'     => esc_html__( 'Divider Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-woo-cart-table tbody tr td' => 'border-bottom-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'row_padding',
            [
                'label'      => esc_html__( 'Row Cell Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-woo-cart-table tbody tr td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Product Name & Image
     */
    protected function register_style_product_info_controls() {
        $this->start_controls_section(
            'ua_style_product_info',
            [
                'label' => esc_html__( 'Product Title & Thumbnail', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'product_title_typography',
                'selector' => '{{WRAPPER}} .ua-cart-product-name a',
            ]
        );

        $this->add_control(
            'product_title_color',
            [
                'label'     => esc_html__( 'Title Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-cart-product-name a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'product_title_hover_color',
            [
                'label'     => esc_html__( 'Title Hover Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-cart-product-name a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'thumb_border_radius',
            [
                'label'      => esc_html__( 'Thumbnail Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-cart-product-thumb' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Price & Subtotal
     */
    protected function register_style_price_controls() {
        $this->start_controls_section(
            'ua_style_price',
            [
                'label' => esc_html__( 'Price & Subtotal', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'unit_price_typography',
                'label'    => esc_html__( 'Unit Price Typography', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-cart-price-cell',
            ]
        );

        $this->add_control(
            'unit_price_color',
            [
                'label'     => esc_html__( 'Unit Price Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-cart-price-cell' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'subtotal_typography',
                'label'    => esc_html__( 'Subtotal Typography', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-cart-subtotal-cell',
            ]
        );

        $this->add_control(
            'subtotal_color',
            [
                'label'     => esc_html__( 'Subtotal Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-cart-subtotal-cell' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Quantity Stepper (+/-)
     */
    protected function register_style_quantity_controls() {
        $this->start_controls_section(
            'ua_style_quantity',
            [
                'label' => esc_html__( 'Quantity Stepper (+/−)', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'qty_btn_bg',
            [
                'label'     => esc_html__( 'Button Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-qty-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'qty_btn_color',
            [
                'label'     => esc_html__( 'Button Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-qty-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'qty_btn_hover_bg',
            [
                'label'     => esc_html__( 'Button Hover Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-qty-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'qty_btn_hover_color',
            [
                'label'     => esc_html__( 'Button Hover Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-qty-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'qty_wrap_border_color',
            [
                'label'     => esc_html__( 'Wrapper Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-cart-quantity-wrap' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Remove Button (×)
     */
    protected function register_style_remove_btn_controls() {
        $this->start_controls_section(
            'ua_style_remove_btn',
            [
                'label' => esc_html__( 'Remove (×) Button', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'remove_btn_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-cart-remove-btn' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'remove_btn_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-cart-remove-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'remove_btn_hover_color',
            [
                'label'     => esc_html__( 'Hover Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-cart-remove-btn:hover' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'remove_btn_hover_bg',
            [
                'label'     => esc_html__( 'Hover Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-cart-remove-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Coupon Form
     */
    protected function register_style_coupon_controls() {
        $this->start_controls_section(
            'ua_style_coupon',
            [
                'label' => esc_html__( 'Coupon Form & Actions', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'coupon_btn_bg',
            [
                'label'     => esc_html__( 'Apply Button Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-coupon-apply-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'coupon_btn_color',
            [
                'label'     => esc_html__( 'Apply Button Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-coupon-apply-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'coupon_btn_hover_bg',
            [
                'label'     => esc_html__( 'Apply Button Hover Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-coupon-apply-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Cart Totals Box
     */
    protected function register_style_totals_box_controls() {
        $this->start_controls_section(
            'ua_style_totals_box',
            [
                'label' => esc_html__( 'Cart Totals Box', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'totals_box_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-woo-cart-totals',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'totals_box_border',
                'selector' => '{{WRAPPER}} .ua-woo-cart-totals',
            ]
        );

        $this->add_responsive_control(
            'totals_box_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-woo-cart-totals' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'totals_box_shadow',
                'selector' => '{{WRAPPER}} .ua-woo-cart-totals',
            ]
        );

        $this->add_responsive_control(
            'totals_box_padding',
            [
                'label'      => esc_html__( 'Box Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-woo-cart-totals' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Proceed to Checkout Button
     */
    protected function register_style_checkout_btn_controls() {
        $this->start_controls_section(
            'ua_style_checkout_btn',
            [
                'label' => esc_html__( 'Checkout Button', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'checkout_btn_typography',
                'selector' => '{{WRAPPER}} .ua-checkout-btn',
            ]
        );

        $this->start_controls_tabs( 'checkout_btn_tabs' );

        // Normal State
        $this->start_controls_tab(
            'checkout_btn_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'checkout_btn_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-checkout-btn' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'checkout_btn_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-checkout-btn',
            ]
        );

        $this->end_controls_tab();

        // Hover State
        $this->start_controls_tab(
            'checkout_btn_hover',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'checkout_btn_hover_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-checkout-btn:hover' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'checkout_btn_hover_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-checkout-btn:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'checkout_btn_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'separator'  => 'before',
                'selectors'  => [
                    '{{WRAPPER}} .ua-checkout-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Cross-Sells Cards
     */
    protected function register_style_cross_sells_controls() {
        $this->start_controls_section(
            'ua_style_cross_sells',
            [
                'label' => esc_html__( 'Cross-Sells Cards', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'cross_sell_card_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-cross-sell-card',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'cross_sell_card_border',
                'selector' => '{{WRAPPER}} .ua-cross-sell-card',
            ]
        );

        $this->add_responsive_control(
            'cross_sell_card_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-cross-sell-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Empty Cart State
     */
    protected function register_style_empty_cart_controls() {
        $this->start_controls_section(
            'ua_style_empty_cart',
            [
                'label' => esc_html__( 'Empty Cart State', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'empty_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-empty-cart-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-empty-cart-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'empty_title_color',
            [
                'label'     => esc_html__( 'Title Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-empty-cart-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'empty_btn_bg',
            [
                'label'     => esc_html__( 'Button Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-empty-cart-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       RENDER OUTPUT
       ========================================================================= */

    /**
     * Render frontend output
     */
    protected function render() {
        if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo '<div class="ua-alert ua-alert-warning">' . esc_html__( 'WooCommerce is not active. Please install and activate WooCommerce.', 'ultraaddons-elementor-lite' ) . '</div>';
            }
            return;
        }

        $settings = $this->get_settings_for_display();

        $layout                 = ! empty( $settings['layout'] ) ? $settings['layout'] : 'two-column';
        $preset                 = ! empty( $settings['preset'] ) ? $settings['preset'] : 'default';
        $sticky_summary         = ( $settings['sticky_summary'] ?? 'yes' ) === 'yes';
        $sticky_offset          = ! empty( $settings['sticky_offset']['size'] ) ? intval( $settings['sticky_offset']['size'] ) : 30;
        $auto_update            = ( $settings['auto_update_cart'] ?? 'yes' ) === 'yes' ? 'yes' : 'no';
        $show_continue          = ( $settings['show_continue_shopping'] ?? 'yes' ) === 'yes';
        $continue_text          = ! empty( $settings['continue_shopping_text'] ) ? $settings['continue_shopping_text'] : esc_html__( '← Continue Shopping', 'ultraaddons-elementor-lite' );
        $continue_url           = ! empty( $settings['continue_shopping_url']['url'] ) ? $settings['continue_shopping_url']['url'] : wc_get_page_permalink( 'shop' );

        $show_thumb             = ( $settings['show_thumbnail'] ?? 'yes' ) === 'yes';
        $show_name              = ( $settings['show_product_name'] ?? 'yes' ) === 'yes';
        $show_price             = ( $settings['show_unit_price'] ?? 'yes' ) === 'yes';
        $show_qty               = ( $settings['show_quantity'] ?? 'yes' ) === 'yes';
        $show_subtotal          = ( $settings['show_subtotal'] ?? 'yes' ) === 'yes';
        $show_remove            = ( $settings['show_remove_btn'] ?? 'yes' ) === 'yes';

        $show_coupon            = ( $settings['show_coupon'] ?? 'yes' ) === 'yes';
        $coupon_placeholder     = ! empty( $settings['coupon_placeholder'] ) ? $settings['coupon_placeholder'] : esc_html__( 'Coupon code', 'ultraaddons-elementor-lite' );
        $apply_coupon_text      = ! empty( $settings['apply_coupon_text'] ) ? $settings['apply_coupon_text'] : esc_html__( 'Apply coupon', 'ultraaddons-elementor-lite' );

        $totals_heading         = ! empty( $settings['totals_heading'] ) ? $settings['totals_heading'] : esc_html__( 'Cart Totals', 'ultraaddons-elementor-lite' );
        $totals_heading_tag     = ! empty( $settings['totals_heading_tag'] ) ? Utils::validate_html_tag( $settings['totals_heading_tag'] ) : 'h3';
        $show_shipping_calc     = ( $settings['show_shipping_calc'] ?? 'yes' ) === 'yes';

        $update_cart_text       = ! empty( $settings['update_cart_text'] ) ? $settings['update_cart_text'] : esc_html__( 'Update Cart', 'ultraaddons-elementor-lite' );
        $checkout_text          = ! empty( $settings['checkout_text'] ) ? $settings['checkout_text'] : esc_html__( 'Proceed to Checkout', 'ultraaddons-elementor-lite' );

        $show_cross             = ( $settings['show_cross_sells'] ?? 'yes' ) === 'yes';
        $cross_title            = ! empty( $settings['cross_sells_title'] ) ? $settings['cross_sells_title'] : esc_html__( 'You may also like…', 'ultraaddons-elementor-lite' );
        $cross_cols             = ! empty( $settings['cross_sells_columns'] ) ? intval( $settings['cross_sells_columns'] ) : 3;
        $cross_limit            = ! empty( $settings['cross_sells_limit'] ) ? intval( $settings['cross_sells_limit'] ) : 3;

        $is_cart_empty          = WC()->cart->is_empty();

        // Wrapper classes
        $wrapper_classes = [
            'ua-woo-cart-wrapper',
            'ua-layout-' . esc_attr( $layout ),
            'ua-cart-preset-' . esc_attr( $preset ),
        ];
        ?>
        <div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>" 
             data-auto-update="<?php echo esc_attr( $auto_update ); ?>"
             style="--ua-cross-cols: <?php echo esc_attr( $cross_cols ); ?>;">

            <div class="woocommerce-notices-wrapper">
                <?php wc_print_notices(); ?>
            </div>

            <?php if ( $is_cart_empty ) : ?>
                <?php $this->render_empty_cart( $settings ); ?>
            <?php else : ?>
                <form class="ua-woo-cart-form woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
                    <div class="ua-woo-cart-main">
                        
                        <!-- Left Column: Cart Items Table & Actions -->
                        <div class="ua-woo-cart-left">
                            <div class="ua-woo-cart-table-wrap">
                                <div class="ua-cart-spinner"></div>
                                <table class="ua-woo-cart-table shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <?php if ( $show_remove ) : ?>
                                                <th class="ua-cart-col-remove">&nbsp;</th>
                                            <?php endif; ?>
                                            <?php if ( $show_thumb ) : ?>
                                                <th class="ua-cart-col-thumbnail">&nbsp;</th>
                                            <?php endif; ?>
                                            <?php if ( $show_name ) : ?>
                                                <th class="ua-cart-col-name"><?php esc_html_e( 'Product', 'ultraaddons-elementor-lite' ); ?></th>
                                            <?php endif; ?>
                                            <?php if ( $show_price ) : ?>
                                                <th class="ua-cart-col-price"><?php esc_html_e( 'Price', 'ultraaddons-elementor-lite' ); ?></th>
                                            <?php endif; ?>
                                            <?php if ( $show_qty ) : ?>
                                                <th class="ua-cart-col-quantity"><?php esc_html_e( 'Quantity', 'ultraaddons-elementor-lite' ); ?></th>
                                            <?php endif; ?>
                                            <?php if ( $show_subtotal ) : ?>
                                                <th class="ua-cart-col-subtotal"><?php esc_html_e( 'Subtotal', 'ultraaddons-elementor-lite' ); ?></th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                                            $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                                            $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                                            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) :
                                                $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                                        ?>
                                            <tr class="ua-cart-item woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>" data-cart-key="<?php echo esc_attr( $cart_item_key ); ?>">
                                                
                                                <?php if ( $show_remove ) : ?>
                                                    <td class="ua-cart-remove-cell product-remove">
                                                        <?php
                                                        echo apply_filters(
                                                            'woocommerce_cart_item_remove_link',
                                                            sprintf(
                                                                '<a href="%s" class="ua-cart-remove-btn remove" aria-label="%s" data-product_id="%s" data-product_sku="%s" data-cart-key="%s">&times;</a>',
                                                                esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                                                /* translators: %s is the product name */
                                                                esc_attr( sprintf( __( 'Remove %s from cart', 'ultraaddons-elementor-lite' ), wp_strip_all_tags( $_product->get_name() ) ) ),
                                                                esc_attr( $product_id ),
                                                                esc_attr( $_product->get_sku() ),
                                                                esc_attr( $cart_item_key )
                                                            ),
                                                            $cart_item_key
                                                        );
                                                        ?>
                                                    </td>
                                                <?php endif; ?>

                                                <?php if ( $show_thumb ) : ?>
                                                    <td class="ua-cart-thumbnail-cell product-thumbnail">
                                                        <div class="ua-cart-product-thumb">
                                                            <?php
                                                            $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'woocommerce_thumbnail' ), $cart_item, $cart_item_key );
                                                            if ( ! $product_permalink ) {
                                                                echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                                            } else {
                                                                printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                                            }
                                                            ?>
                                                        </div>
                                                    </td>
                                                <?php endif; ?>

                                                <?php if ( $show_name ) : ?>
                                                    <td class="ua-cart-name-cell product-name" data-title="<?php esc_attr_e( 'Product', 'ultraaddons-elementor-lite' ); ?>">
                                                        <div class="ua-cart-product-name">
                                                            <?php
                                                            if ( ! $product_permalink ) {
                                                                echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
                                                            } else {
                                                                echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
                                                            }

                                                            do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

                                                            // Meta data
                                                            echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

                                                            // Backorder notification
                                                            if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
                                                                echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'ultraaddons-elementor-lite' ) . '</p>', $product_id ) );
                                                            }
                                                            ?>
                                                        </div>
                                                    </td>
                                                <?php endif; ?>

                                                <?php if ( $show_price ) : ?>
                                                    <td class="ua-cart-price-cell product-price" data-title="<?php esc_attr_e( 'Price', 'ultraaddons-elementor-lite' ); ?>">
                                                        <?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                                    </td>
                                                <?php endif; ?>

                                                <?php if ( $show_qty ) : ?>
                                                    <td class="ua-cart-quantity-cell product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'ultraaddons-elementor-lite' ); ?>">
                                                        <?php
                                                        if ( $_product->is_sold_individually() ) {
                                                            $min_quantity = 1;
                                                            $max_quantity = 1;
                                                        } else {
                                                            $min_quantity = 0;
                                                            $max_quantity = $_product->get_max_purchase_quantity();
                                                        }
                                                        ?>
                                                        <div class="ua-cart-quantity-wrap">
                                                            <button type="button" class="ua-qty-btn ua-qty-minus" aria-label="<?php esc_attr_e( 'Decrease quantity', 'ultraaddons-elementor-lite' ); ?>">&minus;</button>
                                                            <input type="number" 
                                                                   class="ua-qty-input input-text qty text" 
                                                                   name="cart[<?php echo esc_attr( $cart_item_key ); ?>][qty]" 
                                                                   value="<?php echo esc_attr( $cart_item['quantity'] ); ?>" 
                                                                   aria-label="<?php esc_attr_e( 'Product quantity', 'ultraaddons-elementor-lite' ); ?>" 
                                                                   size="4" 
                                                                   min="<?php echo esc_attr( $min_quantity ); ?>" 
                                                                   max="<?php echo esc_attr( 0 < $max_quantity ? $max_quantity : '' ); ?>" 
                                                                   step="<?php echo esc_attr( apply_filters( 'woocommerce_quantity_input_step', '1', $_product ) ); ?>" 
                                                                   inputmode="numeric" 
                                                                   autocomplete="off" />
                                                            <button type="button" class="ua-qty-btn ua-qty-plus" aria-label="<?php esc_attr_e( 'Increase quantity', 'ultraaddons-elementor-lite' ); ?>">&plus;</button>
                                                        </div>
                                                    </td>
                                                <?php endif; ?>

                                                <?php if ( $show_subtotal ) : ?>
                                                    <td class="ua-cart-subtotal-cell product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'ultraaddons-elementor-lite' ); ?>">
                                                        <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                                    </td>
                                                <?php endif; ?>

                                            </tr>
                                        <?php
                                            endif;
                                        endforeach;
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Cart Actions Row: Coupon & Update Buttons -->
                            <div class="ua-woo-cart-actions">
                                <?php if ( $show_coupon && wc_coupons_enabled() ) : ?>
                                    <div class="ua-cart-coupon-wrap coupon">
                                        <input type="text" name="coupon_code" class="ua-coupon-input input-text" id="coupon_code" value="" placeholder="<?php echo esc_attr( $coupon_placeholder ); ?>" />
                                        <button type="submit" class="ua-coupon-apply-btn button" name="apply_coupon" value="<?php echo esc_attr( $apply_coupon_text ); ?>"><?php echo esc_html( $apply_coupon_text ); ?></button>
                                        <?php do_action( 'woocommerce_cart_coupon' ); ?>
                                    </div>
                                <?php endif; ?>

                                <div class="ua-cart-actions-right">
                                    <?php if ( $show_continue ) : ?>
                                        <a href="<?php echo esc_url( $continue_url ); ?>" class="ua-cart-continue-btn">
                                            <?php echo esc_html( $continue_text ); ?>
                                        </a>
                                    <?php endif; ?>

                                    <button type="submit" class="ua-cart-update-btn button" name="update_cart" value="<?php echo esc_attr( $update_cart_text ); ?>" disabled="disabled">
                                        <?php echo esc_html( $update_cart_text ); ?>
                                    </button>
                                    
                                    <?php do_action( 'woocommerce_cart_actions' ); ?>
                                    <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Cart Totals & Checkout -->
                        <div class="ua-woo-cart-right<?php echo ( $sticky_summary && $layout === 'two-column' ) ? ' ua-sticky' : ''; ?>" 
                             data-sticky-offset="<?php echo esc_attr( $sticky_offset ); ?>">
                            
                            <div class="ua-woo-cart-totals cart_totals">
                                <?php do_action( 'woocommerce_before_cart_totals' ); ?>

                                <div class="ua-cart-totals-heading">
                                    <<?php echo esc_attr( $totals_heading_tag ); ?>>
                                        <?php echo esc_html( $totals_heading ); ?>
                                    </<?php echo esc_attr( $totals_heading_tag ); ?>>
                                </div>

                                <table class="ua-cart-totals-table" cellspacing="0">
                                    <tr class="ua-totals-row ua-totals-subtotal cart-subtotal">
                                        <th><?php esc_html_e( 'Subtotal', 'ultraaddons-elementor-lite' ); ?></th>
                                        <td data-title="<?php esc_attr_e( 'Subtotal', 'ultraaddons-elementor-lite' ); ?>"><?php wc_cart_totals_subtotal_html(); ?></td>
                                    </tr>

                                    <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
                                        <tr class="ua-totals-row ua-totals-coupon cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                                            <th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
                                            <td data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
                                        </tr>
                                    <?php endforeach; ?>

                                    <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
                                        <?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>
                                        <tr class="ua-totals-row ua-totals-shipping">
                                            <th><?php esc_html_e( 'Shipping', 'ultraaddons-elementor-lite' ); ?></th>
                                            <td data-title="<?php esc_attr_e( 'Shipping', 'ultraaddons-elementor-lite' ); ?>">
                                                <?php wc_cart_totals_shipping_html(); ?>
                                            </td>
                                        </tr>
                                        <?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>
                                    <?php elseif ( WC()->cart->needs_shipping_address() ) : ?>
                                        <tr class="ua-totals-row ua-totals-shipping">
                                            <th><?php esc_html_e( 'Shipping', 'ultraaddons-elementor-lite' ); ?></th>
                                            <td data-title="<?php esc_attr_e( 'Shipping', 'ultraaddons-elementor-lite' ); ?>">
                                                <?php
                                                printf(
                                                    /* translators: %s: shipping destination */
                                                    esc_html__( 'Shipping to %s.', 'ultraaddons-elementor-lite' ),
                                                    '<strong>' . esc_html( WC()->customer->get_shipping_country() ) . '</strong>'
                                                );
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>

                                    <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
                                        <tr class="ua-totals-row ua-totals-fee fee">
                                            <th><?php echo esc_html( $fee->name ); ?></th>
                                            <td data-title="<?php echo esc_attr( $fee->name ); ?>"><?php wc_cart_totals_fee_html( $fee ); ?></td>
                                        </tr>
                                    <?php endforeach; ?>

                                    <?php
                                    if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) {
                                        $taxable_address = WC()->customer->get_taxable_address();
                                        $estimated_text  = '';

                                        if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
                                            /* translators: %s: location */
                                            $estimated_text = sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'ultraaddons-elementor-lite' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
                                        }

                                        if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
                                            foreach ( WC()->cart->get_tax_totals() as $code => $tax ) {
                                                ?>
                                                <tr class="ua-totals-row ua-totals-tax tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                                                    <th><?php echo esc_html( $tax->label ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
                                                    <td data-title="<?php echo esc_attr( $tax->label ); ?>"><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr class="ua-totals-row ua-totals-tax tax-total">
                                                <th><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
                                                <td data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>"><?php wc_cart_totals_taxes_total_html(); ?></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>

                                    <?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

                                    <tr class="ua-totals-row ua-totals-total order-total">
                                        <th><?php esc_html_e( 'Total', 'ultraaddons-elementor-lite' ); ?></th>
                                        <td data-title="<?php esc_attr_e( 'Total', 'ultraaddons-elementor-lite' ); ?>"><?php wc_cart_totals_order_total_html(); ?></td>
                                    </tr>

                                    <?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>
                                </table>

                                <?php if ( $show_shipping_calc && WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
                                    <div class="ua-cart-shipping-calc">
                                        <?php woocommerce_shipping_calculator(); ?>
                                    </div>
                                <?php endif; ?>

                                <div class="ua-cart-totals-checkout wc-proceed-to-checkout">
                                    <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="ua-checkout-btn checkout-button button alt wc-forward">
                                        <?php echo esc_html( $checkout_text ); ?>
                                    </a>
                                </div>

                                <?php do_action( 'woocommerce_after_cart_totals' ); ?>
                            </div>

                        </div>

                    </div>
                </form>

                <!-- Cross-Sells Section -->
                <?php
                if ( $show_cross ) {
                    $this->render_cross_sells( $cross_limit, $cross_title );
                }
                ?>

            <?php endif; ?>

        </div>
        <?php
    }

    /**
     * Render empty cart template
     *
     * @param array $settings Widget settings.
     */
    protected function render_empty_cart( $settings ) {
        $icon       = ! empty( $settings['empty_cart_icon'] ) ? $settings['empty_cart_icon'] : [];
        $title      = ! empty( $settings['empty_cart_title'] ) ? $settings['empty_cart_title'] : esc_html__( 'Your cart is currently empty.', 'ultraaddons-elementor-lite' );
        $message    = ! empty( $settings['empty_cart_message'] ) ? $settings['empty_cart_message'] : esc_html__( 'Before proceed to checkout you must add some products to your shopping cart.', 'ultraaddons-elementor-lite' );
        $btn_text   = ! empty( $settings['empty_cart_btn_text'] ) ? $settings['empty_cart_btn_text'] : esc_html__( 'Return to Shop', 'ultraaddons-elementor-lite' );
        $btn_url    = ! empty( $settings['empty_cart_btn_url']['url'] ) ? $settings['empty_cart_btn_url']['url'] : wc_get_page_permalink( 'shop' );
        ?>
        <div class="ua-woo-cart-empty">
            <?php if ( ! empty( $icon['value'] ) ) : ?>
                <div class="ua-empty-cart-icon">
                    <?php Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] ); ?>
                </div>
            <?php endif; ?>

            <?php if ( ! empty( $title ) ) : ?>
                <h2 class="ua-empty-cart-title"><?php echo esc_html( $title ); ?></h2>
            <?php endif; ?>

            <?php if ( ! empty( $message ) ) : ?>
                <p class="ua-empty-cart-message"><?php echo esc_html( $message ); ?></p>
            <?php endif; ?>

            <?php if ( ! empty( $btn_text ) ) : ?>
                <a href="<?php echo esc_url( $btn_url ); ?>" class="ua-empty-cart-btn button">
                    <?php echo esc_html( $btn_text ); ?>
                </a>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Render Cross-Sells Section
     *
     * @param int    $limit Max cross-sell items.
     * @param string $heading Section title.
     */
    protected function render_cross_sells( $limit = 3, $heading = '' ) {
        if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
            return;
        }

        $cross_sells = WC()->cart->get_cross_sells();
        if ( empty( $cross_sells ) ) {
            return;
        }

        $args = [
            'post_type'           => 'product',
            'ignore_sticky_posts' => 1,
            'no_found_rows'       => 1,
            'posts_per_page'      => $limit,
            'post__in'            => $cross_sells,
            'post__not_in'        => array_map( function( $item ) {
                return $item['product_id'];
            }, WC()->cart->get_cart() ),
        ];

        $query = new \WP_Query( $args );
        if ( ! $query->have_posts() ) {
            return;
        }
        ?>
        <div class="ua-woo-cart-cross-sells cross-sells">
            <?php if ( ! empty( $heading ) ) : ?>
                <div class="ua-cross-sells-heading">
                    <h3><?php echo esc_html( $heading ); ?></h3>
                </div>
            <?php endif; ?>

            <div class="ua-cross-sells-grid">
                <?php
                while ( $query->have_posts() ) :
                    $query->the_post();
                    global $product;
                    if ( ! is_object( $product ) ) {
                        continue;
                    }
                ?>
                    <div class="ua-cross-sell-card">
                        <div class="ua-cross-sell-thumb">
                            <a href="<?php the_permalink(); ?>">
                                <?php echo $product->get_image( 'woocommerce_thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            </a>
                        </div>
                        <div class="ua-cross-sell-info">
                            <h4 class="ua-cross-sell-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h4>
                            <div class="ua-cross-sell-price">
                                <?php echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            </div>
                            <?php if ( $product->get_rating_count() > 0 ) : ?>
                                <div class="ua-cross-sell-rating">
                                    <?php echo wc_get_rating_html( $product->get_average_rating() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                </div>
                            <?php endif; ?>
                            <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" 
                               class="ua-cross-sell-btn button add_to_cart_button product_type_<?php echo esc_attr( $product->get_type() ); ?>" 
                               data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" 
                               data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>">
                                <?php echo esc_html( $product->add_to_cart_text() ); ?>
                            </a>
                        </div>
                    </div>
                <?php
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
        </div>
        <?php
    }
}
