<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

/**
 * Woo Checkout Widget
 *
 * Full-featured high-converting WooCommerce checkout widget with:
 * - 4 Layouts: Two Columns (Split), Single Column, Multi-Step Wizard, Collapsible Accordion
 * - 4 Design Presets: Modern Rounded, Clean Minimal, Classic Card, Elegant Border
 * - Product thumbnails inside the Order Review table
 * - Interactive Quantity Stepper (+/-) & Item Removal in checkout
 * - High-converting Inline Coupon box inside Order Summary
 * - Trust Badges & Guarantee Seals (SSL, Money-back guarantee, Payment icons)
 * - Sticky Order Review sidebar on scroll
 * - Realistic Editor Mock Preview when cart is empty
 * - Custom Empty Cart state with CTA
 *
 * @since 3.1.0
 * @package UltraAddons\Widget
 */
class Woo_Checkout extends Base {

    /**
     * Constructor — register style and script dependencies
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        wp_register_style(
            'ultraaddons-woo-checkout',
            ULTRA_ADDONS_ASSETS . 'css/widgets/woo-checkout.css',
            [],
            ULTRA_ADDONS_VERSION,
            'all'
        );
        wp_enqueue_style( 'ultraaddons-woo-checkout' );

        wp_register_script(
            'ultraaddons-woo-checkout',
            ULTRA_ADDONS_ASSETS . 'js/widgets/woo-checkout.js',
            [ 'jquery' ],
            ULTRA_ADDONS_VERSION,
            true
        );
        wp_enqueue_script( 'ultraaddons-woo-checkout' );

        // Localize script for AJAX cart updates and notices
        wp_localize_script(
            'ultraaddons-woo-checkout',
            'uaCheckoutConfig',
            [
                'ajax_url'    => admin_url( 'admin-ajax.php' ),
                'nonce'       => wp_create_nonce( 'ua_checkout_nonce' ),
                'wc_ajax_url' => \WC_AJAX::get_endpoint( '%%endpoint%%' ),
                'i18n'        => [
                    'applying'         => esc_html__( 'Applying...', 'ultraaddons-elementor-lite' ),
                    'removing'         => esc_html__( 'Removing...', 'ultraaddons-elementor-lite' ),
                    'step_error'       => esc_html__( 'Please complete all required fields before proceeding.', 'ultraaddons-elementor-lite' ),
                    'confirm_remove'   => esc_html__( 'Remove this item from your order?', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );
    }

    /**
     * Widget Name
     */
    public function get_name() {
        return 'ultraaddons-woo-checkout';
    }

    /**
     * Widget Title
     */
    public function get_title() {
        return esc_html__( 'Woo Checkout', 'ultraaddons-elementor-lite' );
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
        return [ 'ultraaddons', 'ua', 'checkout', 'woo checkout', 'order', 'payment', 'cart', 'billing', 'shipping', 'multistep', 'woocommerce' ];
    }

    /**
     * Style Dependencies
     */
    public function get_style_depends() {
        return [ 'ultraaddons-woo-checkout' ];
    }

    /**
     * Script Dependencies
     */
    public function get_script_depends() {
        return [ 'jquery', 'ultraaddons-woo-checkout' ];
    }

    /**
     * Reload preview on layout or critical setting changes
     */
    public function is_reload_preview_required() {
        return true;
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        $this->register_content_controls();
        $this->register_style_controls();
    }

    /* -------------------------------------------------------------------------
     * CONTENT CONTROLS
     * ---------------------------------------------------------------------- */
    protected function register_content_controls() {

        // ==========================================
        // SECTION: General & Layout Settings
        // ==========================================
        $this->start_controls_section(
            'ua_checkout_section_general',
            [
                'label' => esc_html__( 'Layout & Presets', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'preset',
            [
                'label'   => esc_html__( 'Design Preset', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'modern',
                'options' => [
                    'modern'   => esc_html__( 'Modern Rounded (Recommended)', 'ultraaddons-elementor-lite' ),
                    'clean'    => esc_html__( 'Clean Minimal', 'ultraaddons-elementor-lite' ),
                    'classic'  => esc_html__( 'Classic Card', 'ultraaddons-elementor-lite' ),
                    'bordered' => esc_html__( 'Elegant Bordered', 'ultraaddons-elementor-lite' ),
                ],
                'prefix_class' => 'ua-checkout-preset-',
            ]
        );

        $this->add_control(
            'checkout_layout',
            [
                'label'   => esc_html__( 'Checkout Layout', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'two-columns',
                'options' => [
                    'two-columns' => esc_html__( 'Two Columns (Split Side-by-Side)', 'ultraaddons-elementor-lite' ),
                    'one-column'  => esc_html__( 'One Column (Vertical Streamlined)', 'ultraaddons-elementor-lite' ),
                    'multistep'   => esc_html__( 'Multi-Step Wizard (High-Converting)', 'ultraaddons-elementor-lite' ),
                    'accordion'   => esc_html__( 'Collapsible Accordion', 'ultraaddons-elementor-lite' ),
                ],
                'prefix_class' => 'ua-checkout-layout-',
            ]
        );

        $this->add_responsive_control(
            'columns_split_ratio',
            [
                'label'     => esc_html__( 'Left Column Width', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units'=> [ '%', 'px' ],
                'range'     => [
                    '%'  => [ 'min' => 40, 'max' => 75 ],
                    'px' => [ 'min' => 400, 'max' => 1000 ],
                ],
                'default'   => [
                    'unit' => '%',
                    'size' => 60,
                ],
                'selectors' => [
                    '{{WRAPPER}}.ua-checkout-layout-two-columns .ua-checkout-columns' => '--ua-checkout-left-col: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'checkout_layout' => 'two-columns',
                ],
            ]
        );

        $this->add_control(
            'sticky_summary',
            [
                'label'        => esc_html__( 'Sticky Order Summary', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'checkout_layout' => 'two-columns',
                ],
            ]
        );

        $this->add_responsive_control(
            'sticky_top_offset',
            [
                'label'     => esc_html__( 'Sticky Top Offset', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units'=> [ 'px' ],
                'range'     => [
                    'px' => [ 'min' => 0, 'max' => 200 ],
                ],
                'default'   => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-checkout-sticky-inner' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'checkout_layout' => 'two-columns',
                    'sticky_summary'  => 'yes',
                ],
            ]
        );

        $this->end_controls_section();


        // ==========================================
        // SECTION: Order Review & Items Settings
        // ==========================================
        $this->start_controls_section(
            'ua_checkout_section_review',
            [
                'label' => esc_html__( 'Order Review Table', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_product_thumbnails',
            [
                'label'        => esc_html__( 'Show Product Thumbnails', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'description'  => esc_html__( 'Displays high-quality product images inside the order review table.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_responsive_control(
            'thumbnail_size',
            [
                'label'     => esc_html__( 'Thumbnail Size', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units'=> [ 'px' ],
                'range'     => [
                    'px' => [ 'min' => 30, 'max' => 120 ],
                ],
                'default'   => [
                    'unit' => 'px',
                    'size' => 54,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-checkout-product-img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_product_thumbnails' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'thumbnail_border_radius',
            [
                'label'      => esc_html__( 'Thumbnail Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'    => [
                    'top'      => 8,
                    'right'    => 8,
                    'bottom'   => 8,
                    'left'     => 8,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-checkout-product-img, {{WRAPPER}} .ua-checkout-product-img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_product_thumbnails' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'enable_interactive_qty',
            [
                'label'        => esc_html__( 'Interactive Quantity (+/-)', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'description'  => esc_html__( 'Allows customers to change product quantities directly on the checkout page with instant AJAX updates.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'enable_item_removal',
            [
                'label'        => esc_html__( 'Allow Item Removal', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'description'  => esc_html__( 'Renders a quick delete button next to each product to remove it without leaving checkout.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'product_link',
            [
                'label'        => esc_html__( 'Link Product Title', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->end_controls_section();


        // ==========================================
        // SECTION: Coupon Form Settings
        // ==========================================
        $this->start_controls_section(
            'ua_checkout_section_coupon',
            [
                'label' => esc_html__( 'Coupon & Discount', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'coupon_position',
            [
                'label'   => esc_html__( 'Coupon Placement', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'inline',
                'options' => [
                    'inline'  => esc_html__( 'Inline Inside Order Review (High Converting)', 'ultraaddons-elementor-lite' ),
                    'default' => esc_html__( 'Default WooCommerce (Top Alert Toggle)', 'ultraaddons-elementor-lite' ),
                    'hide'    => esc_html__( 'Hide Coupon Completely', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'coupon_box_title',
            [
                'label'     => esc_html__( 'Coupon Box Title', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Have a promo code?', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'coupon_position' => 'inline',
                ],
            ]
        );

        $this->add_control(
            'coupon_placeholder',
            [
                'label'     => esc_html__( 'Input Placeholder', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Coupon code...', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'coupon_position' => 'inline',
                ],
            ]
        );

        $this->add_control(
            'coupon_btn_text',
            [
                'label'     => esc_html__( 'Apply Button Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Apply', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'coupon_position' => 'inline',
                ],
            ]
        );

        $this->end_controls_section();


        // ==========================================
        // SECTION: Multi-Step Wizard Settings
        // ==========================================
        $this->start_controls_section(
            'ua_checkout_section_multistep',
            [
                'label'     => esc_html__( 'Multi-Step Wizard', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'checkout_layout' => 'multistep',
                ],
            ]
        );

        $this->add_control(
            'step_1_title',
            [
                'label'   => esc_html__( 'Step 1 Title', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Billing Details', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'step_1_desc',
            [
                'label'   => esc_html__( 'Step 1 Subtitle', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Your address & contact', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'step_2_title',
            [
                'label'   => esc_html__( 'Step 2 Title', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Shipping & Notes', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'step_2_desc',
            [
                'label'   => esc_html__( 'Step 2 Subtitle', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Delivery preferences', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'step_3_title',
            [
                'label'   => esc_html__( 'Step 3 Title', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Order & Payment', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'step_3_desc',
            [
                'label'   => esc_html__( 'Step 3 Subtitle', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Review and confirm', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'next_btn_text',
            [
                'label'   => esc_html__( 'Next Button Text', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Proceed to Next Step →', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'prev_btn_text',
            [
                'label'   => esc_html__( 'Previous Button Text', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( '← Back to Previous Step', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->end_controls_section();


        // ==========================================
        // SECTION: Trust Badges & Guarantees
        // ==========================================
        $this->start_controls_section(
            'ua_checkout_section_trust',
            [
                'label' => esc_html__( 'Trust Badges & Guarantees', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'enable_trust_badges',
            [
                'label'        => esc_html__( 'Enable Trust Badges', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'badge_title',
            [
                'label'   => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( '256-Bit SSL Encryption', 'ultraaddons-elementor-lite' ),
            ]
        );

        $repeater->add_control(
            'badge_subtitle',
            [
                'label'   => esc_html__( 'Subtitle', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Guaranteed safe & secure checkout', 'ultraaddons-elementor-lite' ),
            ]
        );

        $repeater->add_control(
            'badge_icon',
            [
                'label'   => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fas fa-lock',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'trust_badges_list',
            [
                'label'       => esc_html__( 'Badges List', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ badge_title }}}',
                'default'     => [
                    [
                        'badge_title'    => esc_html__( 'SSL 256-Bit Secure', 'ultraaddons-elementor-lite' ),
                        'badge_subtitle' => esc_html__( 'Guaranteed safe payment', 'ultraaddons-elementor-lite' ),
                        'badge_icon'     => [ 'value' => 'fas fa-shield-alt', 'library' => 'fa-solid' ],
                    ],
                    [
                        'badge_title'    => esc_html__( '30-Day Guarantee', 'ultraaddons-elementor-lite' ),
                        'badge_subtitle' => esc_html__( 'Hassle-free return policy', 'ultraaddons-elementor-lite' ),
                        'badge_icon'     => [ 'value' => 'fas fa-sync-alt', 'library' => 'fa-solid' ],
                    ],
                    [
                        'badge_title'    => esc_html__( 'Fast & Reliable Delivery', 'ultraaddons-elementor-lite' ),
                        'badge_subtitle' => esc_html__( 'Tracked directly to your door', 'ultraaddons-elementor-lite' ),
                        'badge_icon'     => [ 'value' => 'fas fa-truck', 'library' => 'fa-solid' ],
                    ],
                ],
                'condition' => [
                    'enable_trust_badges' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_payment_cards_row',
            [
                'label'        => esc_html__( 'Show Payment Cards Banner', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'enable_trust_badges' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();


        // ==========================================
        // SECTION: Empty Cart State
        // ==========================================
        $this->start_controls_section(
            'ua_checkout_section_empty',
            [
                'label' => esc_html__( 'Empty Cart Screen', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'empty_cart_title',
            [
                'label'   => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Your Cart is Currently Empty', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'empty_cart_desc',
            [
                'label'   => esc_html__( 'Description', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'You do not have any items in your shopping cart to checkout. Browse our store to discover great deals!', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'empty_cart_btn_text',
            [
                'label'   => esc_html__( 'Button Text', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Return to Shop', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'empty_cart_btn_url',
            [
                'label'       => esc_html__( 'Button URL', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-store.com/shop', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->end_controls_section();
    }


    /* -------------------------------------------------------------------------
     * STYLE CONTROLS
     * ---------------------------------------------------------------------- */
    protected function register_style_controls() {

        // ==========================================
        // STYLE SECTION: Containers & Cards
        // ==========================================
        $this->start_controls_section(
            'ua_checkout_style_cards',
            [
                'label' => esc_html__( 'General & Cards', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_bg_color',
            [
                'label'     => esc_html__( 'Card Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-checkout-card, {{WRAPPER}} .woocommerce-checkout #payment, {{WRAPPER}} .ua-checkout-review-sticky-inner' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'card_border',
                'selector' => '{{WRAPPER}} .ua-checkout-card, {{WRAPPER}} .ua-checkout-review-sticky-inner, {{WRAPPER}} .woocommerce-checkout #payment',
            ]
        );

        $this->add_control(
            'card_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'    => [
                    'top'      => 14,
                    'right'    => 14,
                    'bottom'   => 14,
                    'left'     => 14,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-checkout-card, {{WRAPPER}} .ua-checkout-review-sticky-inner, {{WRAPPER}} .woocommerce-checkout #payment' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'card_box_shadow',
                'selector' => '{{WRAPPER}} .ua-checkout-card, {{WRAPPER}} .ua-checkout-review-sticky-inner, {{WRAPPER}} .woocommerce-checkout #payment',
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label'      => esc_html__( 'Card Inner Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'top'      => 28,
                    'right'    => 28,
                    'bottom'   => 28,
                    'left'     => 28,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-checkout-card, {{WRAPPER}} .ua-checkout-review-sticky-inner, {{WRAPPER}} .woocommerce-checkout #payment' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'cards_gap',
            [
                'label'     => esc_html__( 'Column & Section Gutter', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units'=> [ 'px' ],
                'range'     => [
                    'px' => [ 'min' => 10, 'max' => 60 ],
                ],
                'default'   => [
                    'unit' => 'px',
                    'size' => 28,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-checkout-columns' => 'gap: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-checkout-card'    => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


        // ==========================================
        // STYLE SECTION: Multi-Step Navigation
        // ==========================================
        $this->start_controls_section(
            'ua_checkout_style_multistep_nav',
            [
                'label'     => esc_html__( 'Multi-Step Navigation', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'checkout_layout' => 'multistep',
                ],
            ]
        );

        $this->add_control(
            'step_active_color',
            [
                'label'     => esc_html__( 'Active Step Accent Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#5b51d8',
                'selectors' => [
                    '{{WRAPPER}} .ua-checkout-step.is-active .ua-step-number' => 'background-color: {{VALUE}}; border-color: {{VALUE}}; color: #ffffff;',
                    '{{WRAPPER}} .ua-checkout-step.is-active .ua-step-title'  => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-step-progress-fill'                     => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'step_completed_color',
            [
                'label'     => esc_html__( 'Completed Step Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#10b981',
                'selectors' => [
                    '{{WRAPPER}} .ua-checkout-step.is-completed .ua-step-number' => 'background-color: {{VALUE}}; border-color: {{VALUE}}; color: #ffffff;',
                    '{{WRAPPER}} .ua-checkout-step.is-completed .ua-step-title'  => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'step_inactive_color',
            [
                'label'     => esc_html__( 'Inactive Step Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#9ca3af',
                'selectors' => [
                    '{{WRAPPER}} .ua-checkout-step .ua-step-number' => 'background-color: #f3f4f6; border-color: #e5e7eb; color: {{VALUE}};',
                    '{{WRAPPER}} .ua-checkout-step .ua-step-title'  => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'step_title_typography',
                'label'    => esc_html__( 'Step Title Typography', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-step-title',
            ]
        );

        $this->end_controls_section();


        // ==========================================
        // STYLE SECTION: Headings
        // ==========================================
        $this->start_controls_section(
            'ua_checkout_style_headings',
            [
                'label' => esc_html__( 'Section Headings', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#111827',
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-billing-fields h3, {{WRAPPER}} .woocommerce-shipping-fields h3, {{WRAPPER}} #order_review_heading, {{WRAPPER}} .woocommerce-additional-fields h3, {{WRAPPER}} .ua-checkout-section-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'heading_typography',
                'selector' => '{{WRAPPER}} .woocommerce-billing-fields h3, {{WRAPPER}} .woocommerce-shipping-fields h3, {{WRAPPER}} #order_review_heading, {{WRAPPER}} .woocommerce-additional-fields h3, {{WRAPPER}} .ua-checkout-section-title',
            ]
        );

        $this->add_control(
            'heading_border_color',
            [
                'label'     => esc_html__( 'Separator Line Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f3f4f6',
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-billing-fields h3, {{WRAPPER}} .woocommerce-shipping-fields h3, {{WRAPPER}} #order_review_heading, {{WRAPPER}} .ua-checkout-section-title' => 'border-bottom-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'heading_spacing',
            [
                'label'     => esc_html__( 'Bottom Spacing', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units'=> [ 'px' ],
                'range'     => [
                    'px' => [ 'min' => 5, 'max' => 40 ],
                ],
                'default'   => [
                    'unit' => 'px',
                    'size' => 18,
                ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-billing-fields h3, {{WRAPPER}} .woocommerce-shipping-fields h3, {{WRAPPER}} #order_review_heading, {{WRAPPER}} .ua-checkout-section-title' => 'padding-bottom: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


        // ==========================================
        // STYLE SECTION: Form Fields & Inputs
        // ==========================================
        $this->start_controls_section(
            'ua_checkout_style_fields',
            [
                'label' => esc_html__( 'Form Fields & Inputs', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'field_label_color',
            [
                'label'     => esc_html__( 'Label Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#374151',
                'selectors' => [
                    '{{WRAPPER}} .form-row label, {{WRAPPER}} #ship-to-different-address label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'field_label_typography',
                'label'    => esc_html__( 'Label Typography', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .form-row label, {{WRAPPER}} #ship-to-different-address label',
            ]
        );

        $this->start_controls_tabs( 'field_input_tabs' );

        // Normal State
        $this->start_controls_tab(
            'field_input_tab_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'field_text_color',
            [
                'label'     => esc_html__( 'Input Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .input-text, {{WRAPPER}} select, {{WRAPPER}} .select2-container .select2-selection' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'field_bg_color',
            [
                'label'     => esc_html__( 'Input Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f9fafb',
                'selectors' => [
                    '{{WRAPPER}} .input-text, {{WRAPPER}} select, {{WRAPPER}} .select2-container .select2-selection' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'field_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#e5e7eb',
                'selectors' => [
                    '{{WRAPPER}} .input-text, {{WRAPPER}} select, {{WRAPPER}} .select2-container .select2-selection' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Focus State
        $this->start_controls_tab(
            'field_input_tab_focus',
            [ 'label' => esc_html__( 'Focus', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'field_focus_text_color',
            [
                'label'     => esc_html__( 'Focus Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#111827',
                'selectors' => [
                    '{{WRAPPER}} .input-text:focus, {{WRAPPER}} select:focus, {{WRAPPER}} .select2-container--focus .select2-selection' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'field_focus_bg_color',
            [
                'label'     => esc_html__( 'Focus Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .input-text:focus, {{WRAPPER}} select:focus, {{WRAPPER}} .select2-container--focus .select2-selection' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'field_focus_border_color',
            [
                'label'     => esc_html__( 'Focus Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#5b51d8',
                'selectors' => [
                    '{{WRAPPER}} .input-text:focus, {{WRAPPER}} select:focus, {{WRAPPER}} .select2-container--focus .select2-selection' => 'border-color: {{VALUE}} !important; box-shadow: 0 0 0 3px rgba(91, 81, 216, 0.15) !important;',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'field_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'    => [
                    'top'      => 8,
                    'right'    => 8,
                    'bottom'   => 8,
                    'left'     => 8,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .input-text, {{WRAPPER}} select, {{WRAPPER}} .select2-container .select2-selection' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'before',
            ]
        );

        $this->add_responsive_control(
            'field_padding',
            [
                'label'      => esc_html__( 'Input Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'top'      => 12,
                    'right'    => 16,
                    'bottom'   => 12,
                    'left'     => 16,
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .input-text, {{WRAPPER}} select, {{WRAPPER}} .select2-container .select2-selection' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; min-height: 44px;',
                ],
            ]
        );

        $this->add_responsive_control(
            'field_row_spacing',
            [
                'label'     => esc_html__( 'Fields Row Spacing', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units'=> [ 'px' ],
                'range'     => [
                    'px' => [ 'min' => 5, 'max' => 35 ],
                ],
                'default'   => [
                    'unit' => 'px',
                    'size' => 16,
                ],
                'selectors' => [
                    '{{WRAPPER}} .form-row' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->end_controls_section();


        // ==========================================
        // STYLE SECTION: Order Review Table
        // ==========================================
        $this->start_controls_section(
            'ua_checkout_style_order_review',
            [
                'label' => esc_html__( 'Order Review Table', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'table_header_bg',
            [
                'label'     => esc_html__( 'Header Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f9fafb',
                'selectors' => [
                    '{{WRAPPER}} table.woocommerce-checkout-review-order-table th' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'table_header_color',
            [
                'label'     => esc_html__( 'Header Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} table.woocommerce-checkout-review-order-table th' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'table_row_border_color',
            [
                'label'     => esc_html__( 'Row Divider Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f3f4f6',
                'selectors' => [
                    '{{WRAPPER}} table.woocommerce-checkout-review-order-table td, {{WRAPPER}} table.woocommerce-checkout-review-order-table th' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'product_name_color',
            [
                'label'     => esc_html__( 'Product Title Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .ua-checkout-product-title, {{WRAPPER}} table.woocommerce-checkout-review-order-table .product-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'product_name_typography',
                'label'    => esc_html__( 'Product Title Typography', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-checkout-product-title, {{WRAPPER}} table.woocommerce-checkout-review-order-table .product-name',
            ]
        );

        $this->add_control(
            'product_price_color',
            [
                'label'     => esc_html__( 'Price Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#111827',
                'selectors' => [
                    '{{WRAPPER}} table.woocommerce-checkout-review-order-table .product-total, {{WRAPPER}} table.woocommerce-checkout-review-order-table .cart-subtotal td' => 'color: {{VALUE}}; font-weight: 600;',
                ],
            ]
        );

        $this->add_control(
            'order_total_color',
            [
                'label'     => esc_html__( 'Total Highlight Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#5b51d8',
                'selectors' => [
                    '{{WRAPPER}} table.woocommerce-checkout-review-order-table .order-total td strong, {{WRAPPER}} table.woocommerce-checkout-review-order-table .order-total td' => 'color: {{VALUE}} !important; font-size: 1.25em;',
                ],
            ]
        );

        $this->end_controls_section();


        // ==========================================
        // STYLE SECTION: Inline Coupon Box
        // ==========================================
        $this->start_controls_section(
            'ua_checkout_style_coupon',
            [
                'label'     => esc_html__( 'Inline Coupon Box', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'coupon_position' => 'inline',
                ],
            ]
        );

        $this->add_control(
            'inline_coupon_bg',
            [
                'label'     => esc_html__( 'Coupon Box Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f9fafb',
                'selectors' => [
                    '{{WRAPPER}} .ua-checkout-inline-coupon-box' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'inline_coupon_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'    => [
                    'top'      => 10,
                    'right'    => 10,
                    'bottom'   => 10,
                    'left'     => 10,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-checkout-inline-coupon-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'inline_coupon_btn_color',
            [
                'label'     => esc_html__( 'Apply Button Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .ua-checkout-apply-coupon-btn' => 'background-color: {{VALUE}}; border-color: {{VALUE}}; color: #ffffff;',
                ],
            ]
        );

        $this->add_control(
            'inline_coupon_btn_hover',
            [
                'label'     => esc_html__( 'Apply Button Hover', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#5b51d8',
                'selectors' => [
                    '{{WRAPPER}} .ua-checkout-apply-coupon-btn:hover' => 'background-color: {{VALUE}}; border-color: {{VALUE}}; color: #ffffff;',
                ],
            ]
        );

        $this->end_controls_section();


        // ==========================================
        // STYLE SECTION: Payment Gateways
        // ==========================================
        $this->start_controls_section(
            'ua_checkout_style_payment',
            [
                'label' => esc_html__( 'Payment Gateways Box', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'payment_box_bg',
            [
                'label'     => esc_html__( 'Container Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f9fafb',
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-checkout #payment' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'payment_method_active_border',
            [
                'label'     => esc_html__( 'Selected Method Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#5b51d8',
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-checkout #payment ul.payment_methods li.payment_method_selected, {{WRAPPER}} .woocommerce-checkout #payment ul.payment_methods li input[type="radio"]:checked + label' => 'color: {{VALUE}}; font-weight: 600;',
                ],
            ]
        );

        $this->add_control(
            'payment_tooltip_bg',
            [
                'label'     => esc_html__( 'Description Box (Tooltip) BG', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-checkout #payment div.payment_box' => 'background-color: {{VALUE}}; border-color: #e5e7eb;',
                    '{{WRAPPER}} .woocommerce-checkout #payment div.payment_box::before' => 'border-bottom-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'payment_tooltip_color',
            [
                'label'     => esc_html__( 'Description Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-checkout #payment div.payment_box p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'privacy_link_color',
            [
                'label'     => esc_html__( 'Privacy Policy Link Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#5b51d8',
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-privacy-policy-link' => 'color: {{VALUE}}; text-decoration: underline;',
                ],
            ]
        );

        $this->end_controls_section();


        // ==========================================
        // STYLE SECTION: Place Order Button
        // ==========================================
        $this->start_controls_section(
            'ua_checkout_style_place_order',
            [
                'label' => esc_html__( 'Place Order Button', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'place_order_typography',
                'label'    => esc_html__( 'Typography', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} #place_order, {{WRAPPER}} .ua-checkout-next-btn',
            ]
        );

        $this->start_controls_tabs( 'place_order_btn_tabs' );

        // Normal State
        $this->start_controls_tab(
            'place_order_btn_tab_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'place_order_btn_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} #place_order, {{WRAPPER}} .ua-checkout-next-btn' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'place_order_btn_bg',
                'label'    => esc_html__( 'Background', 'ultraaddons-elementor-lite' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} #place_order, {{WRAPPER}} .ua-checkout-next-btn',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'place_order_btn_shadow',
                'selector' => '{{WRAPPER}} #place_order, {{WRAPPER}} .ua-checkout-next-btn',
            ]
        );

        $this->end_controls_tab();

        // Hover State
        $this->start_controls_tab(
            'place_order_btn_tab_hover',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'place_order_btn_hover_color',
            [
                'label'     => esc_html__( 'Hover Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} #place_order:hover, {{WRAPPER}} .ua-checkout-next-btn:hover' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'place_order_btn_hover_bg',
                'label'    => esc_html__( 'Hover Background', 'ultraaddons-elementor-lite' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} #place_order:hover, {{WRAPPER}} .ua-checkout-next-btn:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'place_order_btn_hover_shadow',
                'selector' => '{{WRAPPER}} #place_order:hover, {{WRAPPER}} .ua-checkout-next-btn:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'place_order_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'    => [
                    'top'      => 10,
                    'right'    => 10,
                    'bottom'   => 10,
                    'left'     => 10,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} #place_order, {{WRAPPER}} .ua-checkout-next-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'before',
            ]
        );

        $this->add_responsive_control(
            'place_order_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'top'      => 16,
                    'right'    => 32,
                    'bottom'   => 16,
                    'left'     => 32,
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} #place_order, {{WRAPPER}} .ua-checkout-next-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; width: 100%; display: block; font-weight: 700; text-align: center;',
                ],
            ]
        );

        $this->end_controls_section();


        // ==========================================
        // STYLE SECTION: Trust Badges
        // ==========================================
        $this->start_controls_section(
            'ua_checkout_style_trust_badges',
            [
                'label'     => esc_html__( 'Trust Badges & Seals', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'enable_trust_badges' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'trust_badge_bg',
            [
                'label'     => esc_html__( 'Badge Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f9fafb',
                'selectors' => [
                    '{{WRAPPER}} .ua-trust-badge-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'trust_badge_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#10b981',
                'selectors' => [
                    '{{WRAPPER}} .ua-trust-badge-icon i, {{WRAPPER}} .ua-trust-badge-icon svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'trust_badge_title_color',
            [
                'label'     => esc_html__( 'Title Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#111827',
                'selectors' => [
                    '{{WRAPPER}} .ua-trust-badge-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'trust_badge_desc_color',
            [
                'label'     => esc_html__( 'Subtitle Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#6b7280',
                'selectors' => [
                    '{{WRAPPER}} .ua-trust-badge-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }


    /* -------------------------------------------------------------------------
     * RENDER OUTPUT & HOOKS
     * ---------------------------------------------------------------------- */
    protected function render() {
        if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo '<div class="ua-alert ua-alert-warning">' . esc_html__( 'WooCommerce is not active. Please install and activate WooCommerce to use the Woo Checkout widget.', 'ultraaddons-elementor-lite' ) . '</div>';
            }
            return;
        }

        $settings  = $this->get_settings_for_display();
        $is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();

        // If Cart is empty in live frontend, render custom Empty Cart screen
        if ( ! $is_editor && WC()->cart->is_empty() ) {
            $this->render_empty_cart( $settings );
            return;
        }

        // If Cart is empty in Elementor Editor, render a realistic visual preview!
        if ( $is_editor && WC()->cart->is_empty() ) {
            $this->render_editor_mock_preview( $settings );
            return;
        }

        // Handle Logged-out simulation in Editor so all fields (first name, billing, etc.) show up!
        if ( $is_editor ) {
            $stored_user = wp_get_current_user()->ID;
            wp_set_current_user( 0 );
        }

        // Setup filter hooks
        $this->setup_checkout_hooks( $settings );

        // Render Checkout Form
        $wrapper_classes = [
            'ua-woo-checkout',
            'ua-checkout-preset-' . esc_attr( $settings['preset'] ?? 'modern' ),
            'ua-checkout-layout-' . esc_attr( $settings['checkout_layout'] ?? 'two-columns' ),
        ];

        if ( ( $settings['sticky_summary'] ?? 'yes' ) === 'yes' && ( $settings['checkout_layout'] ?? 'two-columns' ) === 'two-columns' ) {
            $wrapper_classes[] = 'ua-has-sticky-summary';
        }

        echo '<div class="' . esc_attr( implode( ' ', $wrapper_classes ) ) . '" data-layout="' . esc_attr( $settings['checkout_layout'] ?? 'two-columns' ) . '">';

        // Render Multi-step progress bar if Multi-step layout is chosen
        if ( ( $settings['checkout_layout'] ?? '' ) === 'multistep' ) {
            $this->render_multistep_nav( $settings );
        }

        // Execute standard WooCommerce checkout
        echo do_shortcode( '[woocommerce_checkout]' );

        echo '</div>'; // .ua-woo-checkout

        // Clean up filter hooks
        $this->teardown_checkout_hooks( $settings );

        // Restore user if simulated in editor
        if ( $is_editor && isset( $stored_user ) ) {
            wp_set_current_user( $stored_user );
        }
    }


    /* -------------------------------------------------------------------------
     * HOOKS MANAGEMENT
     * ---------------------------------------------------------------------- */
    protected function setup_checkout_hooks( $settings ) {
        // Thumbnail and interactive quantity
        if ( ( $settings['show_product_thumbnails'] ?? 'yes' ) === 'yes' || ( $settings['enable_interactive_qty'] ?? 'yes' ) === 'yes' ) {
            add_filter( 'woocommerce_cart_item_name', [ $this, 'filter_cart_item_name' ], 20, 3 );
            // Suppress WooCommerce default duplicate quantity text (e.g. "× 7") below the flex item
            add_filter( 'woocommerce_checkout_cart_item_quantity', [ $this, 'filter_cart_item_quantity' ], 20, 3 );
        }

        // Coupon placement
        $coupon_pos = $settings['coupon_position'] ?? 'inline';
        if ( 'inline' === $coupon_pos || 'hide' === $coupon_pos ) {
            remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
        }

        // Inline coupon inside review table
        if ( 'inline' === $coupon_pos ) {
            add_action( 'woocommerce_review_order_before_payment', [ $this, 'render_inline_coupon' ], 5 );
        }

        // Trust badges
        if ( ( $settings['enable_trust_badges'] ?? '' ) === 'yes' ) {
            add_action( 'woocommerce_review_order_after_payment', [ $this, 'render_trust_badges' ], 20 );
        }

        // Column Wrappers for Two Columns and One Column layouts
        $layout = $settings['checkout_layout'] ?? 'two-columns';
        if ( in_array( $layout, [ 'two-columns', 'one-column' ], true ) ) {
            add_action( 'woocommerce_checkout_before_customer_details', [ $this, 'hook_col_left_start' ], 1 );
            add_action( 'woocommerce_checkout_after_customer_details', [ $this, 'hook_col_left_end' ], 999 );
            add_action( 'woocommerce_checkout_before_order_review_heading', [ $this, 'hook_col_right_start' ], 1 );
            add_action( 'woocommerce_checkout_after_order_review', [ $this, 'hook_col_right_end' ], 999 );
        }
    }

    protected function teardown_checkout_hooks( $settings ) {
        remove_filter( 'woocommerce_cart_item_name', [ $this, 'filter_cart_item_name' ], 20 );
        remove_filter( 'woocommerce_checkout_cart_item_quantity', [ $this, 'filter_cart_item_quantity' ], 20 );

        $coupon_pos = $settings['coupon_position'] ?? 'inline';
        if ( 'inline' === $coupon_pos || 'hide' === $coupon_pos ) {
            add_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
        }

        if ( 'inline' === $coupon_pos ) {
            remove_action( 'woocommerce_review_order_before_payment', [ $this, 'render_inline_coupon' ], 5 );
        }

        if ( ( $settings['enable_trust_badges'] ?? '' ) === 'yes' ) {
            remove_action( 'woocommerce_review_order_after_payment', [ $this, 'render_trust_badges' ], 20 );
        }

        $layout = $settings['checkout_layout'] ?? 'two-columns';
        if ( in_array( $layout, [ 'two-columns', 'one-column' ], true ) ) {
            remove_action( 'woocommerce_checkout_before_customer_details', [ $this, 'hook_col_left_start' ], 1 );
            remove_action( 'woocommerce_checkout_after_customer_details', [ $this, 'hook_col_left_end' ], 999 );
            remove_action( 'woocommerce_checkout_before_order_review_heading', [ $this, 'hook_col_right_start' ], 1 );
            remove_action( 'woocommerce_checkout_after_order_review', [ $this, 'hook_col_right_end' ], 999 );
        }
    }

    /**
     * Suppress default WooCommerce duplicate item quantity
     */
    public function filter_cart_item_quantity( $qty_html, $cart_item, $cart_item_key ) {
        return '';
    }

    public function hook_col_left_start() {
        echo '<div class="ua-checkout-col-left ua-checkout-card">';
    }

    public function hook_col_left_end() {
        echo '</div><!-- /.ua-checkout-col-left -->';
    }

    public function hook_col_right_start() {
        echo '<div class="ua-checkout-col-right"><div class="ua-checkout-review-sticky-inner ua-checkout-card">';
    }

    public function hook_col_right_end() {
        echo '</div></div><!-- /.ua-checkout-col-right -->';
    }


    /* -------------------------------------------------------------------------
     * ITEM ENHANCEMENT: THUMBNAILS & QUANTITY STEPPER
     * ---------------------------------------------------------------------- */
    public function filter_cart_item_name( $product_name, $cart_item, $cart_item_key ) {
        if ( ! is_checkout() && ! \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
            return $product_name;
        }

        $settings = $this->get_settings_for_display();
        $product  = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

        if ( ! $product || ! $product->exists() ) {
            return $product_name;
        }

        $show_thumb  = ( $settings['show_product_thumbnails'] ?? 'yes' ) === 'yes';
        $allow_link  = ( $settings['product_link'] ?? 'yes' ) === 'yes';
        $enable_qty  = ( $settings['enable_interactive_qty'] ?? 'yes' ) === 'yes';
        $enable_rm   = ( $settings['enable_item_removal'] ?? 'yes' ) === 'yes';
        $product_url = $product->is_visible() && $allow_link ? $product->get_permalink( $cart_item ) : '';

        ob_start();
        ?>
        <div class="ua-checkout-product-row" data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>">
            <?php if ( $show_thumb ) : ?>
                <div class="ua-checkout-product-img">
                    <?php
                    $thumb_id  = $product->get_image_id();
                    $thumb_url = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'thumbnail' ) : wc_placeholder_img_src();
                    ?>
                    <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>" loading="lazy" />
                    <?php if ( ! $enable_qty ) : ?>
                        <span class="ua-checkout-qty-badge"><?php echo esc_html( $cart_item['quantity'] ); ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="ua-checkout-product-info">
                <span class="ua-checkout-product-title">
                    <?php if ( $product_url ) : ?>
                        <a href="<?php echo esc_url( $product_url ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
                    <?php else : ?>
                        <?php echo esc_html( $product->get_name() ); ?>
                    <?php endif; ?>
                </span>

                <?php
                // Display item variations/attributes if any
                if ( function_exists( 'wc_get_formatted_cart_item_data' ) ) {
                    echo wc_get_formatted_cart_item_data( $cart_item );
                }
                ?>

                <?php if ( $enable_qty ) : ?>
                    <div class="ua-checkout-item-qty-stepper">
                        <button type="button" class="ua-qty-btn ua-qty-minus" aria-label="<?php esc_attr_e( 'Decrease quantity', 'ultraaddons-elementor-lite' ); ?>">−</button>
                        <input type="number" class="ua-qty-input" value="<?php echo esc_attr( $cart_item['quantity'] ); ?>" min="1" step="1" data-key="<?php echo esc_attr( $cart_item_key ); ?>" readonly />
                        <button type="button" class="ua-qty-btn ua-qty-plus" aria-label="<?php esc_attr_e( 'Increase quantity', 'ultraaddons-elementor-lite' ); ?>">+</button>
                    </div>
                <?php endif; ?>

                <?php if ( $enable_rm ) : ?>
                    <button type="button" class="ua-checkout-remove-item" data-key="<?php echo esc_attr( $cart_item_key ); ?>" title="<?php esc_attr_e( 'Remove product', 'ultraaddons-elementor-lite' ); ?>">
                        ✕ <?php esc_html_e( 'Remove', 'ultraaddons-elementor-lite' ); ?>
                    </button>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }


    /* -------------------------------------------------------------------------
     * INLINE COUPON BOX
     * ---------------------------------------------------------------------- */
    public function render_inline_coupon() {
        if ( ! wc_coupons_enabled() ) {
            return;
        }

        $settings    = $this->get_settings_for_display();
        $title       = ! empty( $settings['coupon_box_title'] ) ? $settings['coupon_box_title'] : esc_html__( 'Have a promo code?', 'ultraaddons-elementor-lite' );
        $placeholder = ! empty( $settings['coupon_placeholder'] ) ? $settings['coupon_placeholder'] : esc_html__( 'Coupon code...', 'ultraaddons-elementor-lite' );
        $btn_text    = ! empty( $settings['coupon_btn_text'] ) ? $settings['coupon_btn_text'] : esc_html__( 'Apply', 'ultraaddons-elementor-lite' );
        ?>
        <div class="ua-checkout-inline-coupon-box">
            <?php if ( $title ) : ?>
                <div class="ua-coupon-title"><?php echo esc_html( $title ); ?></div>
            <?php endif; ?>
            <div class="ua-coupon-field-group">
                <input type="text" class="ua-coupon-code-input" placeholder="<?php echo esc_attr( $placeholder ); ?>" autocomplete="off" />
                <button type="button" class="ua-checkout-apply-coupon-btn"><?php echo esc_html( $btn_text ); ?></button>
            </div>
            <div class="ua-coupon-ajax-msg"></div>
        </div>
        <?php
    }


    /* -------------------------------------------------------------------------
     * TRUST BADGES & GUARANTEES
     * ---------------------------------------------------------------------- */
    public function render_trust_badges() {
        $settings = $this->get_settings_for_display();
        $badges   = $settings['trust_badges_list'] ?? [];
        $show_cards = ( $settings['show_payment_cards_row'] ?? 'yes' ) === 'yes';

        if ( empty( $badges ) && ! $show_cards ) {
            return;
        }
        ?>
        <div class="ua-checkout-trust-section">
            <?php if ( ! empty( $badges ) ) : ?>
                <div class="ua-trust-badges-grid">
                    <?php foreach ( $badges as $badge ) : ?>
                        <div class="ua-trust-badge-card">
                            <div class="ua-trust-badge-icon">
                                <?php
                                if ( ! empty( $badge['badge_icon'] ) ) {
                                    Icons_Manager::render_icon( $badge['badge_icon'], [ 'aria-hidden' => 'true' ] );
                                }
                                ?>
                            </div>
                            <div class="ua-trust-badge-text">
                                <span class="ua-trust-badge-title"><?php echo esc_html( $badge['badge_title'] ); ?></span>
                                <?php if ( ! empty( $badge['badge_subtitle'] ) ) : ?>
                                    <span class="ua-trust-badge-desc"><?php echo esc_html( $badge['badge_subtitle'] ); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ( $show_cards ) : ?>
                <div class="ua-payment-cards-banner">
                    <span class="ua-cards-banner-text"><?php esc_html_e( 'Guaranteed Safe & Secure Checkout', 'ultraaddons-elementor-lite' ); ?></span>
                    <div class="ua-cards-icons">
                        <span class="ua-card-pill">VISA</span>
                        <span class="ua-card-pill">Mastercard</span>
                        <span class="ua-card-pill">AMEX</span>
                        <span class="ua-card-pill">PayPal</span>
                        <span class="ua-card-pill">Stripe</span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }


    /* -------------------------------------------------------------------------
     * MULTI-STEP NAVIGATION WIZARD
     * ---------------------------------------------------------------------- */
    protected function render_multistep_nav( $settings ) {
        $steps = [
            1 => [
                'title' => ! empty( $settings['step_1_title'] ) ? $settings['step_1_title'] : esc_html__( 'Billing Details', 'ultraaddons-elementor-lite' ),
                'desc'  => ! empty( $settings['step_1_desc'] ) ? $settings['step_1_desc'] : esc_html__( 'Customer Info', 'ultraaddons-elementor-lite' ),
            ],
            2 => [
                'title' => ! empty( $settings['step_2_title'] ) ? $settings['step_2_title'] : esc_html__( 'Shipping Details', 'ultraaddons-elementor-lite' ),
                'desc'  => ! empty( $settings['step_2_desc'] ) ? $settings['step_2_desc'] : esc_html__( 'Delivery Info', 'ultraaddons-elementor-lite' ),
            ],
            3 => [
                'title' => ! empty( $settings['step_3_title'] ) ? $settings['step_3_title'] : esc_html__( 'Payment & Review', 'ultraaddons-elementor-lite' ),
                'desc'  => ! empty( $settings['step_3_desc'] ) ? $settings['step_3_desc'] : esc_html__( 'Place Order', 'ultraaddons-elementor-lite' ),
            ],
        ];
        ?>
        <div class="ua-checkout-multistep-bar">
            <div class="ua-step-progress-track">
                <div class="ua-step-progress-fill" style="width: 33%;"></div>
            </div>
            <div class="ua-checkout-steps-wrap">
                <?php foreach ( $steps as $idx => $step ) : ?>
                    <div class="ua-checkout-step <?php echo $idx === 1 ? 'is-active' : ''; ?>" data-step="<?php echo esc_attr( $idx ); ?>">
                        <div class="ua-step-number"><?php echo esc_html( $idx ); ?></div>
                        <div class="ua-step-info">
                            <span class="ua-step-title"><?php echo esc_html( $step['title'] ); ?></span>
                            <span class="ua-step-desc"><?php echo esc_html( $step['desc'] ); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }


    /* -------------------------------------------------------------------------
     * REALISTIC EDITOR PREVIEW (When cart is empty in Elementor Editor)
     * ---------------------------------------------------------------------- */
    protected function render_editor_mock_preview( $settings ) {
        ?>
        <div class="ua-woo-checkout ua-checkout-preset-<?php echo esc_attr( $settings['preset'] ?? 'modern' ); ?> ua-checkout-layout-<?php echo esc_attr( $settings['checkout_layout'] ?? 'two-columns' ); ?>">
            <div class="ua-editor-preview-notice">
                <span>✦ <?php esc_html_e( 'Elementor Editor Preview Mode: Displaying sample checkout fields & live styling.', 'ultraaddons-elementor-lite' ); ?></span>
            </div>

            <?php if ( ( $settings['checkout_layout'] ?? '' ) === 'multistep' ) : ?>
                <?php $this->render_multistep_nav( $settings ); ?>
            <?php endif; ?>

            <div class="ua-checkout-columns">
                <!-- Left Column: Billing & Shipping Preview -->
                <div class="ua-checkout-col-left ua-checkout-card">
                    <div class="woocommerce-billing-fields">
                        <h3><?php esc_html_e( 'Billing details', 'ultraaddons-elementor-lite' ); ?></h3>
                        <div class="woocommerce-billing-fields__field-wrapper">
                            <p class="form-row form-row-first">
                                <label><?php esc_html_e( 'First name', 'ultraaddons-elementor-lite' ); ?> <abbr class="required">*</abbr></label>
                                <input type="text" class="input-text" value="Alex" readonly />
                            </p>
                            <p class="form-row form-row-last">
                                <label><?php esc_html_e( 'Last name', 'ultraaddons-elementor-lite' ); ?> <abbr class="required">*</abbr></label>
                                <input type="text" class="input-text" value="Morgan" readonly />
                            </p>
                            <p class="form-row form-row-wide">
                                <label><?php esc_html_e( 'Street address', 'ultraaddons-elementor-lite' ); ?> <abbr class="required">*</abbr></label>
                                <input type="text" class="input-text" value="742 Evergreen Terrace" readonly />
                            </p>
                            <p class="form-row form-row-first">
                                <label><?php esc_html_e( 'Town / City', 'ultraaddons-elementor-lite' ); ?> <abbr class="required">*</abbr></label>
                                <input type="text" class="input-text" value="Springfield" readonly />
                            </p>
                            <p class="form-row form-row-last">
                                <label><?php esc_html_e( 'Postcode / ZIP', 'ultraaddons-elementor-lite' ); ?> <abbr class="required">*</abbr></label>
                                <input type="text" class="input-text" value="97477" readonly />
                            </p>
                            <p class="form-row form-row-first">
                                <label><?php esc_html_e( 'Phone', 'ultraaddons-elementor-lite' ); ?> <abbr class="required">*</abbr></label>
                                <input type="tel" class="input-text" value="+1 (555) 019-2834" readonly />
                            </p>
                            <p class="form-row form-row-last">
                                <label><?php esc_html_e( 'Email address', 'ultraaddons-elementor-lite' ); ?> <abbr class="required">*</abbr></label>
                                <input type="email" class="input-text" value="alex.morgan@example.com" readonly />
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Order Review Preview -->
                <div class="ua-checkout-col-right">
                    <div class="ua-checkout-review-sticky-inner ua-checkout-card">
                        <h3 id="order_review_heading"><?php esc_html_e( 'Your order', 'ultraaddons-elementor-lite' ); ?></h3>

                        <table class="shop_table woocommerce-checkout-review-order-table">
                            <thead>
                                <tr>
                                    <th class="product-name"><?php esc_html_e( 'Product', 'ultraaddons-elementor-lite' ); ?></th>
                                    <th class="product-total"><?php esc_html_e( 'Subtotal', 'ultraaddons-elementor-lite' ); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="cart_item">
                                    <td class="product-name">
                                        <div class="ua-checkout-product-row">
                                            <?php if ( ( $settings['show_product_thumbnails'] ?? 'yes' ) === 'yes' ) : ?>
                                                <div class="ua-checkout-product-img">
                                                    <img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" alt="Sample Product" />
                                                </div>
                                            <?php endif; ?>
                                            <div class="ua-checkout-product-info">
                                                <span class="ua-checkout-product-title">Premium Wireless Headphones</span>
                                                <span class="ua-checkout-meta">Color: Midnight Black</span>
                                                <div class="ua-checkout-item-qty-stepper">
                                                    <button type="button" class="ua-qty-btn">−</button>
                                                    <input type="number" class="ua-qty-input" value="1" readonly />
                                                    <button type="button" class="ua-qty-btn">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="product-total">
                                        <span class="woocommerce-Price-amount amount">$129.00</span>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="cart-subtotal">
                                    <th><?php esc_html_e( 'Subtotal', 'ultraaddons-elementor-lite' ); ?></th>
                                    <td><span class="woocommerce-Price-amount amount">$129.00</span></td>
                                </tr>
                                <tr class="shipping">
                                    <th><?php esc_html_e( 'Shipping', 'ultraaddons-elementor-lite' ); ?></th>
                                    <td><span>Free Express Shipping</span></td>
                                </tr>
                                <tr class="order-total">
                                    <th><?php esc_html_e( 'Total', 'ultraaddons-elementor-lite' ); ?></th>
                                    <td><strong><span class="woocommerce-Price-amount amount">$129.00</span></strong></td>
                                </tr>
                            </tfoot>
                        </table>

                        <?php if ( ( $settings['coupon_position'] ?? 'inline' ) === 'inline' ) : ?>
                            <?php $this->render_inline_coupon(); ?>
                        <?php endif; ?>

                        <!-- Sample Payment Methods Preview -->
                        <div id="payment" class="woocommerce-checkout-payment">
                            <ul class="wc_payment_methods payment_methods methods">
                                <li class="wc_payment_method payment_method_cod">
                                    <input id="payment_method_cod" type="radio" class="input-radio" name="payment_method" value="cod" checked="checked" />
                                    <label for="payment_method_cod"><?php esc_html_e( 'Cash on delivery', 'ultraaddons-elementor-lite' ); ?></label>
                                    <div class="payment_box payment_method_cod">
                                        <p><?php esc_html_e( 'Pay with cash upon delivery of your items.', 'ultraaddons-elementor-lite' ); ?></p>
                                    </div>
                                </li>
                            </ul>
                            <div class="form-row place-order">
                                <button type="button" class="button alt" id="place_order" value="Place order"><?php esc_html_e( 'Place order', 'ultraaddons-elementor-lite' ); ?></button>
                            </div>
                        </div>

                        <?php if ( ( $settings['enable_trust_badges'] ?? '' ) === 'yes' ) : ?>
                            <?php $this->render_trust_badges(); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }


    /* -------------------------------------------------------------------------
     * EMPTY CART SCREEN
     * ---------------------------------------------------------------------- */
    protected function render_empty_cart( $settings ) {
        $title    = ! empty( $settings['empty_cart_title'] ) ? $settings['empty_cart_title'] : esc_html__( 'Your Cart is Currently Empty', 'ultraaddons-elementor-lite' );
        $desc     = ! empty( $settings['empty_cart_desc'] ) ? $settings['empty_cart_desc'] : esc_html__( 'You do not have any items in your shopping cart to checkout.', 'ultraaddons-elementor-lite' );
        $btn_text = ! empty( $settings['empty_cart_btn_text'] ) ? $settings['empty_cart_btn_text'] : esc_html__( 'Return to Shop', 'ultraaddons-elementor-lite' );
        $shop_url = ! empty( $settings['empty_cart_btn_url']['url'] ) ? $settings['empty_cart_btn_url']['url'] : wc_get_page_permalink( 'shop' );
        ?>
        <div class="ua-woo-checkout-empty">
            <div class="ua-empty-icon-wrap">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
            </div>
            <h2 class="ua-empty-title"><?php echo esc_html( $title ); ?></h2>
            <p class="ua-empty-desc"><?php echo esc_html( $desc ); ?></p>
            <a href="<?php echo esc_url( $shop_url ); ?>" class="ua-empty-shop-btn">
                <?php echo esc_html( $btn_text ); ?>
            </a>
        </div>
        <?php
    }

    /**
     * AJAX Endpoint: Update or remove cart item quantity from checkout table
     */
    public static function ajax_update_cart_item() {
        check_ajax_referer( 'ua_checkout_nonce', 'nonce' );

        if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
            wp_send_json_error( [ 'message' => esc_html__( 'WooCommerce cart not found.', 'ultraaddons-elementor-lite' ) ] );
        }

        $cart_item_key = isset( $_POST['cart_item_key'] ) ? sanitize_text_field( wp_unslash( $_POST['cart_item_key'] ) ) : '';
        $quantity      = isset( $_POST['quantity'] ) ? intval( $_POST['quantity'] ) : 1;

        if ( ! $cart_item_key ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Invalid item key.', 'ultraaddons-elementor-lite' ) ] );
        }

        if ( $quantity <= 0 ) {
            WC()->cart->remove_cart_item( $cart_item_key );
        } else {
            WC()->cart->set_quantity( $cart_item_key, $quantity, true );
        }

        WC()->cart->calculate_totals();

        wp_send_json_success( [
            'cart_count' => WC()->cart->get_cart_contents_count(),
            'is_empty'   => WC()->cart->is_empty(),
        ] );
    }
}
