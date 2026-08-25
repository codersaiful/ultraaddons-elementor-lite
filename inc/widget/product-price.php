<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * UltraAddons WooCommerce Product Price Widget
 *
 * Dynamically displays the WooCommerce product price with regular/sale price formatting,
 * currency styling, flexible ordering, stacked layout, customizable badges, and prefix/suffix affixes.
 *
 * @since 1.1.0.12
 * @package UltraAddons
 */
class Product_Price extends Base {

    /**
     * Constructor — register and enqueue style
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        wp_register_style(
            'ultraaddons-product-price',
            ULTRA_ADDONS_ASSETS . 'css/widgets/product-price.css',
            [],
            ULTRA_ADDONS_VERSION,
            'all'
        );
        wp_enqueue_style( 'ultraaddons-product-price' );
    }

    public function get_style_depends() {
        return [ 'ultraaddons-product-price' ];
    }

    /**
     * Set widget keywords for Elementor editor search
     *
     * @return array
     */
    public function get_keywords() {
        return [ 'ultraaddons-elementor-lite', 'ua', 'wc', 'woocommerce', 'product', 'price', 'product price', 'sale price', 'offer price', 'discount', 'shop' ];
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        // Content Tab
        $this->content_general_controls();

        // Style Tab
        $this->style_price_controls();
        $this->style_discount_badge_controls();
        $this->style_affix_controls( 'prefix' );
        $this->style_affix_controls( 'suffix' );
        $this->style_container_controls();
    }

    /**
     * Content Tab: General Controls
     */
    protected function content_general_controls() {
        $this->start_controls_section(
            'ua_pp_content_section',
            [
                'label' => esc_html__( 'Content', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Optional Product ID
        $this->add_control(
            'ua_pp_product_id',
            [
                'label'       => esc_html__( 'Product ID (Optional)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::NUMBER,
                'description' => esc_html__( 'Leave empty to automatically use current product context.', 'ultraaddons-elementor-lite' ),
                'default'     => '',
                'dynamic'     => [ 'active' => true ],
            ]
        );

        // Sale Price Position
        $this->add_control(
            'ua_pp_sale_position',
            [
                'label'     => esc_html__( 'Sale Price Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'row',
                'options'   => [
                    'row'         => esc_html__( 'After Regular Price', 'ultraaddons-elementor-lite' ),
                    'row-reverse' => esc_html__( 'Before Regular Price', 'ultraaddons-elementor-lite' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-pp-price-inner' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );

        // Stacked Layout
        $this->add_control(
            'ua_pp_stacked',
            [
                'label'        => esc_html__( 'Stacked (Vertical Layout)', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => '',
                'prefix_class' => 'ua-pp-stacked-',
            ]
        );

        // Discount Badge Switcher
        $this->add_control(
            'ua_pp_show_discount_badge',
            [
                'label'        => esc_html__( 'Show Discount Badge', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => '',
                'separator'    => 'before',
            ]
        );

        // Discount Badge Type
        $this->add_control(
            'ua_pp_discount_badge_type',
            [
                'label'     => esc_html__( 'Badge Type', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'percentage',
                'options'   => [
                    'percentage' => esc_html__( 'Percentage (-20%)', 'ultraaddons-elementor-lite' ),
                    'text'       => esc_html__( 'Custom Text (SALE)', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'ua_pp_show_discount_badge' => 'yes',
                ],
            ]
        );

        // Discount Badge Custom Text
        $this->add_control(
            'ua_pp_discount_badge_text',
            [
                'label'     => esc_html__( 'Badge Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'SAVE', 'ultraaddons-elementor-lite' ),
                'dynamic'   => [ 'active' => true ],
                'condition' => [
                    'ua_pp_show_discount_badge' => 'yes',
                ],
            ]
        );

        // Prefix Controls
        $this->add_control(
            'ua_pp_show_prefix',
            [
                'label'        => esc_html__( 'Show Prefix', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => '',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'ua_pp_prefix_type',
            [
                'label'     => esc_html__( 'Prefix Type', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => 'text',
                'toggle'    => false,
                'options'   => [
                    'text' => [
                        'title' => esc_html__( 'Text / Badge', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-t-letter',
                    ],
                    'icon' => [
                        'title' => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-star',
                    ],
                ],
                'condition' => [
                    'ua_pp_show_prefix' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ua_pp_prefix_text',
            [
                'label'       => esc_html__( 'Prefix Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => [ 'active' => true ],
                'default'     => esc_html__( 'Special Price', 'ultraaddons-elementor-lite' ),
                'label_block' => false,
                'condition'   => [
                    'ua_pp_show_prefix' => 'yes',
                    'ua_pp_prefix_type' => 'text',
                ],
            ]
        );

        $this->add_control(
            'ua_pp_prefix_icon',
            [
                'label'     => esc_html__( 'Prefix Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'fas fa-tag',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'ua_pp_show_prefix' => 'yes',
                    'ua_pp_prefix_type' => 'icon',
                ],
            ]
        );

        // Suffix Controls
        $this->add_control(
            'ua_pp_show_suffix',
            [
                'label'        => esc_html__( 'Show Suffix', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => '',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'ua_pp_suffix_type',
            [
                'label'     => esc_html__( 'Suffix Type', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => 'text',
                'toggle'    => false,
                'options'   => [
                    'text' => [
                        'title' => esc_html__( 'Text / Badge', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-t-letter',
                    ],
                    'icon' => [
                        'title' => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-star',
                    ],
                ],
                'condition' => [
                    'ua_pp_show_suffix' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ua_pp_suffix_text',
            [
                'label'       => esc_html__( 'Suffix Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => [ 'active' => true ],
                'default'     => esc_html__( 'Flash Deal', 'ultraaddons-elementor-lite' ),
                'label_block' => false,
                'condition'   => [
                    'ua_pp_show_suffix' => 'yes',
                    'ua_pp_suffix_type' => 'text',
                ],
            ]
        );

        $this->add_control(
            'ua_pp_suffix_icon',
            [
                'label'     => esc_html__( 'Suffix Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'fas fa-clock',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'ua_pp_show_suffix' => 'yes',
                    'ua_pp_suffix_type' => 'icon',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab: Price Styling Controls
     */
    protected function style_price_controls() {
        $this->start_controls_section(
            'ua_pp_price_style_section',
            [
                'label' => esc_html__( 'Price', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Alignment
        $this->add_responsive_control(
            'ua_pp_alignment',
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
                'default'   => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-price-wrapper' => 'justify-content: {{VALUE}}; align-items: {{VALUE}};',
                ],
            ]
        );

        // Items Gap
        $this->add_responsive_control(
            'ua_pp_items_gap',
            [
                'label'      => esc_html__( 'Items Gap', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 50 ],
                    'em' => [ 'min' => 0, 'max' => 4, 'step' => 0.1 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-price-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // --- Regular Price Sub-heading ---
        $this->add_control(
            'ua_pp_regular_heading',
            [
                'label'     => esc_html__( 'Regular / Original Price', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'ua_pp_regular_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#777777',
                'selectors' => [
                    '{{WRAPPER}} .ua-pp-price-inner del'             => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-pp-price-inner del .amount bdi' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_pp_regular_typography',
                'selector' => '{{WRAPPER}} .ua-pp-price-inner del, {{WRAPPER}} .ua-pp-price-inner del .amount, {{WRAPPER}} .ua-pp-price-inner del bdi',
            ]
        );

        $this->add_control(
            'ua_pp_regular_strikethrough_color',
            [
                'label'     => esc_html__( 'Strikethrough Line Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-pp-price-inner del, {{WRAPPER}} .ua-pp-price-inner del *, {{WRAPPER}} .ua-product-price-wrapper del, {{WRAPPER}} .ua-product-price-wrapper del *' => 'text-decoration-color: {{VALUE}} !important; -webkit-text-decoration-color: {{VALUE}} !important;',
                ],
            ]
        );

        // --- Sale Price Sub-heading ---
        $this->add_control(
            'ua_pp_sale_heading',
            [
                'label'     => esc_html__( 'Sale / Active Price', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'ua_pp_sale_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'global'    => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-pp-price-inner ins'             => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-pp-price-inner ins .amount bdi' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-pp-price-inner > .amount'       => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-pp-price-inner > .amount bdi'   => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_pp_sale_typography',
                'global'   => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .ua-pp-price-inner ins, {{WRAPPER}} .ua-pp-price-inner > .amount, {{WRAPPER}} .ua-pp-price-inner ins .amount',
            ]
        );

        $this->add_responsive_control(
            'ua_pp_prices_gap',
            [
                'label'      => esc_html__( 'Regular & Sale Gap', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 40 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 8,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pp-price-inner' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // --- Currency Symbol Sub-heading ---
        $this->add_control(
            'ua_pp_currency_heading',
            [
                'label'     => esc_html__( 'Currency Symbol', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'ua_pp_currency_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-Price-currencySymbol' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_pp_currency_typography',
                'selector' => '{{WRAPPER}} .woocommerce-Price-currencySymbol',
            ]
        );

        $this->add_responsive_control(
            'ua_pp_currency_spacing',
            [
                'label'      => esc_html__( 'Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 20 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .woocommerce-Price-currencySymbol' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab: Discount Badge Controls
     */
    protected function style_discount_badge_controls() {
        $this->start_controls_section(
            'ua_pp_discount_badge_style_section',
            [
                'label'     => esc_html__( 'Discount Badge', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ua_pp_show_discount_badge' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ua_pp_badge_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-pp-discount-badge' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_pp_badge_typography',
                'selector' => '{{WRAPPER}} .ua-pp-discount-badge',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => 'ua_pp_badge_background',
                'types'     => [ 'classic', 'gradient' ],
                'selector'  => '{{WRAPPER}} .ua-pp-discount-badge',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_pp_badge_border',
                'selector' => '{{WRAPPER}} .ua-pp-discount-badge',
            ]
        );

        $this->add_responsive_control(
            'ua_pp_badge_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'    => [
                    'top'      => '4',
                    'right'    => '4',
                    'bottom'   => '4',
                    'left'     => '4',
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pp-discount-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_pp_badge_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'    => [
                    'top'      => '3',
                    'right'    => '7',
                    'bottom'   => '3',
                    'left'     => '7',
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pp-discount-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_pp_badge_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pp-discount-badge' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab: Prefix and Suffix Affix Controls
     *
     * @param string $type 'prefix' or 'suffix'
     */
    protected function style_affix_controls( $type = 'prefix' ) {
        $prefix_key = 'ua_pp_' . $type;
        $is_prefix  = 'prefix' === $type;
        $label      = $is_prefix ? esc_html__( 'Prefix', 'ultraaddons-elementor-lite' ) : esc_html__( 'Suffix', 'ultraaddons-elementor-lite' );
        $selector   = '{{WRAPPER}} .ua-pp-' . $type;

        $this->start_controls_section(
            $prefix_key . '_style_section',
            [
                'label'     => $label,
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ua_pp_show_' . $type => 'yes',
                ],
            ]
        );

        // --- Text / Badge Variant ---
        $this->add_control(
            $prefix_key . '_text_heading',
            [
                'label'     => esc_html__( 'Badge / Text Styling', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'condition' => [
                    'ua_pp_' . $type . '_type' => 'text',
                ],
            ]
        );

        $this->add_control(
            $prefix_key . '_text_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#555555',
                'selectors' => [
                    $selector . '-badge' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'ua_pp_' . $type . '_type' => 'text',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => $prefix_key . '_text_typography',
                'selector'  => $selector . '-badge',
                'condition' => [
                    'ua_pp_' . $type . '_type' => 'text',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => $prefix_key . '_text_background',
                'types'     => [ 'classic', 'gradient' ],
                'selector'  => $selector . '-badge',
                'condition' => [
                    'ua_pp_' . $type . '_type' => 'text',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => $prefix_key . '_text_border',
                'selector'  => $selector . '-badge',
                'condition' => [
                    'ua_pp_' . $type . '_type' => 'text',
                ],
            ]
        );

        $this->add_responsive_control(
            $prefix_key . '_text_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    $selector . '-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'ua_pp_' . $type . '_type' => 'text',
                ],
            ]
        );

        $this->add_responsive_control(
            $prefix_key . '_text_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    $selector . '-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'ua_pp_' . $type . '_type' => 'text',
                ],
            ]
        );

        $this->add_responsive_control(
            $prefix_key . '_text_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    $selector . '-badge' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'ua_pp_' . $type . '_type' => 'text',
                ],
            ]
        );

        // --- Icon Variant ---
        $this->add_control(
            $prefix_key . '_icon_heading',
            [
                'label'     => esc_html__( 'Icon Styling', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'condition' => [
                    'ua_pp_' . $type . '_type' => 'icon',
                ],
            ]
        );

        $this->add_responsive_control(
            $prefix_key . '_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range'      => [
                    'px' => [ 'min' => 6, 'max' => 100 ],
                    'em' => [ 'min' => 0.5, 'max' => 5, 'step' => 0.1 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 18,
                ],
                'selectors'  => [
                    $selector . '-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    $selector . '-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'ua_pp_' . $type . '_type' => 'icon',
                ],
            ]
        );

        $this->add_control(
            $prefix_key . '_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'global'    => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    $selector . '-icon i'   => 'color: {{VALUE}};',
                    $selector . '-icon svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'ua_pp_' . $type . '_type' => 'icon',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => $prefix_key . '_icon_background',
                'types'     => [ 'classic', 'gradient' ],
                'selector'  => $selector . '-icon',
                'condition' => [
                    'ua_pp_' . $type . '_type' => 'icon',
                ],
            ]
        );

        $this->add_responsive_control(
            $prefix_key . '_icon_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    $selector . '-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'ua_pp_' . $type . '_type' => 'icon',
                ],
            ]
        );

        $this->add_responsive_control(
            $prefix_key . '_icon_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    $selector . '-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'ua_pp_' . $type . '_type' => 'icon',
                ],
            ]
        );

        $this->add_responsive_control(
            $prefix_key . '_icon_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    $selector . '-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'ua_pp_' . $type . '_type' => 'icon',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab: Container Box Styling Controls
     */
    protected function style_container_controls() {
        $this->start_controls_section(
            'ua_pp_container_style_section',
            [
                'label' => esc_html__( 'Container Box', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'ua_pp_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-price-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_pp_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-price-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'ua_pp_background',
                'selector' => '{{WRAPPER}} .ua-product-price-wrapper',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_pp_border',
                'selector' => '{{WRAPPER}} .ua-product-price-wrapper',
            ]
        );

        $this->add_responsive_control(
            'ua_pp_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-price-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_pp_box_shadow',
                'selector' => '{{WRAPPER}} .ua-product-price-wrapper',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render Affix element (Prefix or Suffix)
     *
     * @param array  $settings Widget settings.
     * @param string $type     'prefix' or 'suffix'.
     * @return string HTML output.
     */
    protected function render_affix( $settings, $type = 'prefix' ) {
        $show_key = 'ua_pp_show_' . $type;
        $type_key = 'ua_pp_' . $type . '_type';

        if ( 'yes' !== ( $settings[ $show_key ] ?? '' ) ) {
            return '';
        }

        $affix_mode = $settings[ $type_key ] ?? 'text';

        if ( 'icon' === $affix_mode ) {
            $icon = $settings[ 'ua_pp_' . $type . '_icon' ] ?? [];
            if ( empty( $icon['value'] ) ) {
                return '';
            }

            ob_start();
            Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] );
            $icon_content = ob_get_clean();

            return sprintf(
                '<span class="ua-pp-affix ua-pp-%1$s ua-pp-%1$s-icon">%2$s</span>',
                esc_attr( $type ),
                $icon_content
            );
        }

        $text = $settings[ 'ua_pp_' . $type . '_text' ] ?? '';
        if ( '' === trim( $text ) ) {
            return '';
        }

        $allowed_html = function_exists( 'ultraaddons_allowed_html_tags' )
            ? ultraaddons_allowed_html_tags( 'advanced' )
            : [ 'span' => [], 'strong' => [], 'b' => [], 'i' => [], 'em' => [] ];

        return sprintf(
            '<span class="ua-pp-affix ua-pp-%1$s ua-pp-%1$s-badge">%2$s</span>',
            esc_attr( $type ),
            wp_kses( $text, $allowed_html )
        );
    }

    /**
     * Render Widget Output
     */
    protected function render() {
        $is_editor = Plugin::$instance->editor->is_edit_mode();

        // Check if WooCommerce is active
        if ( ! function_exists( 'WC' ) ) {
            if ( $is_editor ) {
                ?>
                <div class="ua-woocommerce-warning" style="padding: 15px; background: #fff3cd; color: #856404; border: 1px solid #ffeeba; border-radius: 4px;">
                    <strong><?php esc_html_e( 'WooCommerce Not Active:', 'ultraaddons-elementor-lite' ); ?></strong>
                    <?php esc_html_e( 'Please install and activate WooCommerce to use the Product Price widget.', 'ultraaddons-elementor-lite' ); ?>
                </div>
                <?php
            }
            return;
        }

        $settings = $this->get_settings_for_display();

        // Resolve WooCommerce Product
        global $product;
        $current_product = $product;

        $custom_product_id = ! empty( $settings['ua_pp_product_id'] ) ? absint( $settings['ua_pp_product_id'] ) : 0;
        if ( $custom_product_id ) {
            $current_product = wc_get_product( $custom_product_id );
        }

        if ( ! is_a( $current_product, 'WC_Product' ) ) {
            $current_product = wc_get_product( get_the_ID() );
        }

        $price_html       = '';
        $discount_percent = 0;
        $is_on_sale       = false;

        if ( $current_product ) {
            $is_on_sale = $current_product->is_on_sale();
            $regular    = 0.0;
            $sale       = 0.0;

            if ( $current_product->is_type( 'simple' ) || $current_product->is_type( 'external' ) ) {
                $regular = (float) $current_product->get_regular_price();
                $sale    = (float) $current_product->get_sale_price();
            } elseif ( $current_product->is_type( 'variable' ) ) {
                $regular = (float) $current_product->get_variation_regular_price( 'max', true );
                $sale    = (float) $current_product->get_variation_sale_price( 'min', true );
            }

            // If regular and sale price are the exact same, treat as normal price (no fake discount)
            if ( $regular > 0 && $sale > 0 && abs( $regular - $sale ) < 0.01 ) {
                $is_on_sale       = false;
                $discount_percent = 0;
                $price_html       = function_exists( 'wc_price' ) ? wc_price( $regular ) . $current_product->get_price_suffix() : $current_product->get_price_html();
            } elseif ( $is_on_sale && $regular > 0 && $sale > 0 && $sale < $regular ) {
                $diff             = $regular - $sale;
                $discount_percent = (int) round( ( $diff / $regular ) * 100 );
                $price_html       = $current_product->get_price_html();
            } else {
                $discount_percent = 0;
                $price_html       = $current_product->get_price_html();
            }
        } elseif ( $is_editor ) {
            // Editor preview dummy price
            $currency_sym = get_woocommerce_currency_symbol();
            $price_html = sprintf(
                '<del aria-hidden="true"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">%1$s</span>80.00</bdi></span></del> <ins aria-hidden="true"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">%1$s</span>59.00</bdi></span></ins>',
                esc_html( $currency_sym )
            );
            $discount_percent = 26;
            $is_on_sale       = true;
        } else {
            return;
        }

        $prefix_html = $this->render_affix( $settings, 'prefix' );
        $suffix_html = $this->render_affix( $settings, 'suffix' );

        // Discount Badge (only show when there is an actual discount > 0)
        $badge_html = '';
        if ( 'yes' === ( $settings['ua_pp_show_discount_badge'] ?? '' ) && $is_on_sale && $discount_percent > 0 ) {
            $badge_type = $settings['ua_pp_discount_badge_type'] ?? 'percentage';
            $badge_text = $settings['ua_pp_discount_badge_text'] ?? esc_html__( 'SAVE', 'ultraaddons-elementor-lite' );

            $badge_label = '';
            if ( 'percentage' === $badge_type ) {
                $badge_label = '-' . $discount_percent . '%';
            } else {
                $badge_label = esc_html( $badge_text );
            }

            if ( ! empty( $badge_label ) ) {
                $badge_html = sprintf(
                    '<span class="ua-pp-discount-badge">%s</span>',
                    $badge_label
                );
            }
        }

        $this->add_render_attribute( 'ua_product_price_wrap', 'class', 'ua-product-price-wrapper' );
        ?>
        <div <?php echo $this->get_render_attribute_string( 'ua_product_price_wrap' ); ?>>
            <?php
            if ( ! empty( $prefix_html ) ) {
                echo $prefix_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
            ?>
            <div class="ua-pp-price-inner">
                <?php echo $price_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
            <?php
            if ( ! empty( $badge_html ) ) {
                echo $badge_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
            if ( ! empty( $suffix_html ) ) {
                echo $suffix_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
            ?>
        </div>
        <?php
    }
}
