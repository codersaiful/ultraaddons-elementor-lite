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
use Elementor\Group_Control_Text_Shadow;
use Elementor\Icons_Manager;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * UltraAddons WooCommerce Product Title Widget
 *
 * Displays the WooCommerce product title dynamically with custom HTML tags,
 * flexible permalink/custom URL linking, customizable prefixes/suffixes (text/badges/icons),
 * and advanced styling options.
 *
 * @since 1.1.0.12
 * @package UltraAddons
 */
class Product_Title extends Base {

    /**
     * Set widget keywords for Elementor editor search
     *
     * @return array
     */
    public function get_keywords() {
        return [ 'ultraaddons-elementor-lite', 'ua', 'wc', 'woocommerce', 'product', 'title', 'heading', 'product title', 'shop' ];
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        // Content Tab
        $this->content_general_controls();

        // Style Tab
        $this->style_title_controls();
        $this->style_affix_controls( 'prefix' );
        $this->style_affix_controls( 'suffix' );
    }

    /**
     * Content Tab: General Controls
     */
    protected function content_general_controls() {
        $this->start_controls_section(
            'ua_pt_content_section',
            [
                'label' => esc_html__( 'Content', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        // HTML Tag Selection
        $this->add_control(
            'ua_pt_tag',
            [
                'label'   => esc_html__( 'HTML Tag', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'h2',
                'options' => [
                    'h1'   => 'H1',
                    'h2'   => 'H2',
                    'h3'   => 'H3',
                    'h4'   => 'H4',
                    'h5'   => 'H5',
                    'h6'   => 'H6',
                    'div'  => 'div',
                    'span' => 'span',
                    'p'    => 'p',
                ],
            ]
        );

        // Optional Product ID
        $this->add_control(
            'ua_pt_product_id',
            [
                'label'       => esc_html__( 'Product ID (Optional)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::NUMBER,
                'description' => esc_html__( 'Leave empty to automatically use current product context.', 'ultraaddons-elementor-lite' ),
                'default'     => '',
                'dynamic'     => [ 'active' => true ],
            ]
        );

        // Title Link Toggle
        $this->add_control(
            'ua_pt_enable_link',
            [
                'label'        => esc_html__( 'Link', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        // Link Type Selection
        $this->add_control(
            'ua_pt_link_type',
            [
                'label'     => esc_html__( 'Link Type', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'product',
                'options'   => [
                    'product' => esc_html__( 'Product Permalink', 'ultraaddons-elementor-lite' ),
                    'custom'  => esc_html__( 'Custom URL', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'ua_pt_enable_link' => 'yes',
                ],
            ]
        );

        // Custom URL Field
        $this->add_control(
            'ua_pt_custom_url',
            [
                'label'         => esc_html__( 'Custom URL', 'ultraaddons-elementor-lite' ),
                'type'          => Controls_Manager::URL,
                'placeholder'   => esc_html__( 'https://example.com', 'ultraaddons-elementor-lite' ),
                'show_external' => true,
                'dynamic'       => [ 'active' => true ],
                'condition'     => [
                    'ua_pt_enable_link' => 'yes',
                    'ua_pt_link_type'   => 'custom',
                ],
            ]
        );

        // Prefix Controls
        $this->add_control(
            'ua_pt_show_prefix',
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
            'ua_pt_prefix_type',
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
                    'ua_pt_show_prefix' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ua_pt_prefix_text',
            [
                'label'       => esc_html__( 'Prefix Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => [ 'active' => true ],
                'default'     => esc_html__( 'Hot', 'ultraaddons-elementor-lite' ),
                'label_block' => false,
                'condition'   => [
                    'ua_pt_show_prefix' => 'yes',
                    'ua_pt_prefix_type' => 'text',
                ],
            ]
        );

        $this->add_control(
            'ua_pt_prefix_icon',
            [
                'label'     => esc_html__( 'Prefix Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'fas fa-fire',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'ua_pt_show_prefix' => 'yes',
                    'ua_pt_prefix_type' => 'icon',
                ],
            ]
        );

        // Suffix Controls
        $this->add_control(
            'ua_pt_show_suffix',
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
            'ua_pt_suffix_type',
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
                    'ua_pt_show_suffix' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ua_pt_suffix_text',
            [
                'label'       => esc_html__( 'Suffix Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => [ 'active' => true ],
                'default'     => esc_html__( 'New', 'ultraaddons-elementor-lite' ),
                'label_block' => false,
                'condition'   => [
                    'ua_pt_show_suffix' => 'yes',
                    'ua_pt_suffix_type' => 'text',
                ],
            ]
        );

        $this->add_control(
            'ua_pt_suffix_icon',
            [
                'label'     => esc_html__( 'Suffix Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'fas fa-bolt',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'ua_pt_show_suffix' => 'yes',
                    'ua_pt_suffix_type' => 'icon',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab: Title Controls
     */
    protected function style_title_controls() {
        $this->start_controls_section(
            'ua_pt_title_style_section',
            [
                'label' => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Alignment
        $this->add_responsive_control(
            'ua_pt_alignment',
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
                    '{{WRAPPER}} .ua-product-title-wrapper' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        // Vertical Alignment (Top, Center, Bottom, Baseline)
        $this->add_responsive_control(
            'ua_pt_vertical_align',
            [
                'label'     => esc_html__( 'Vertical Align', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'flex-start' => [
                        'title' => esc_html__( 'Top', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'center'     => [
                        'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-v-align-middle',
                    ],
                    'flex-end'   => [
                        'title' => esc_html__( 'Bottom', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ],
                    'baseline'   => [
                        'title' => esc_html__( 'Baseline', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-v-align-stretch',
                    ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-title-wrapper' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        // Gap between items (Prefix, Title, Suffix)
        $this->add_responsive_control(
            'ua_pt_items_gap',
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
                    '{{WRAPPER}} .ua-product-title-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Title Color Tabs (Normal & Hover)
        $this->start_controls_tabs( 'ua_pt_title_color_tabs' );

        $this->start_controls_tab(
            'ua_pt_title_color_normal',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_pt_title_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'global'    => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-pt-heading'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-pt-heading a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'ua_pt_title_color_hover',
            [
                'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_pt_title_hover_color',
            [
                'label'     => esc_html__( 'Hover Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'global'    => [
                    'default' => Global_Colors::COLOR_ACCENT,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-pt-heading a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        // Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_pt_typography',
                'global'   => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .ua-pt-heading',
            ]
        );

        // Text Shadow
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'ua_pt_text_shadow',
                'selector' => '{{WRAPPER}} .ua-pt-heading',
            ]
        );

        // Container Box Styling Heading
        $this->add_control(
            'ua_pt_box_heading',
            [
                'label'     => esc_html__( 'Container Box', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        // Margin
        $this->add_responsive_control(
            'ua_pt_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-title-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Padding
        $this->add_responsive_control(
            'ua_pt_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-title-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Background
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'ua_pt_background',
                'selector' => '{{WRAPPER}} .ua-product-title-wrapper',
            ]
        );

        // Border
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_pt_border',
                'selector' => '{{WRAPPER}} .ua-product-title-wrapper',
            ]
        );

        // Border Radius
        $this->add_responsive_control(
            'ua_pt_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-title-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Box Shadow
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_pt_box_shadow',
                'selector' => '{{WRAPPER}} .ua-product-title-wrapper',
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
        $prefix_key = 'ua_pt_' . $type;
        $is_prefix  = 'prefix' === $type;
        $label      = $is_prefix ? esc_html__( 'Prefix', 'ultraaddons-elementor-lite' ) : esc_html__( 'Suffix', 'ultraaddons-elementor-lite' );
        $selector   = '{{WRAPPER}} .ua-pt-' . $type;

        $this->start_controls_section(
            $prefix_key . '_style_section',
            [
                'label'     => $label,
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ua_pt_show_' . $type => 'yes',
                ],
            ]
        );

        // Vertical Alignment for this specific Prefix / Suffix
        $this->add_responsive_control(
            $prefix_key . '_vertical_align',
            [
                'label'     => esc_html__( 'Vertical Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'flex-start' => [
                        'title' => esc_html__( 'Top', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'center'     => [
                        'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-v-align-middle',
                    ],
                    'flex-end'   => [
                        'title' => esc_html__( 'Bottom', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ],
                    'baseline'   => [
                        'title' => esc_html__( 'Baseline', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-v-align-stretch',
                    ],
                ],
                'default'   => 'center',
                'selectors' => [
                    $selector => 'align-self: {{VALUE}};',
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
                    'ua_pt_' . $type . '_type' => 'text',
                ],
            ]
        );

        // Text Color
        $this->add_control(
            $prefix_key . '_text_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    $selector . '-badge' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'ua_pt_' . $type . '_type' => 'text',
                ],
            ]
        );

        // Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => $prefix_key . '_text_typography',
                'selector'  => $selector . '-badge',
                'condition' => [
                    'ua_pt_' . $type . '_type' => 'text',
                ],
            ]
        );

        // Background
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => $prefix_key . '_text_background',
                'types'     => [ 'classic', 'gradient' ],
                'selector'  => $selector . '-badge',
                'condition' => [
                    'ua_pt_' . $type . '_type' => 'text',
                ],
            ]
        );

        // Border
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => $prefix_key . '_text_border',
                'selector'  => $selector . '-badge',
                'condition' => [
                    'ua_pt_' . $type . '_type' => 'text',
                ],
            ]
        );

        // Border Radius
        $this->add_responsive_control(
            $prefix_key . '_text_border_radius',
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
                    $selector . '-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'ua_pt_' . $type . '_type' => 'text',
                ],
            ]
        );

        // Padding
        $this->add_responsive_control(
            $prefix_key . '_text_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'    => [
                    'top'      => '3',
                    'right'    => '8',
                    'bottom'   => '3',
                    'left'     => '8',
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'selectors'  => [
                    $selector . '-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'ua_pt_' . $type . '_type' => 'text',
                ],
            ]
        );

        // Margin
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
                    'ua_pt_' . $type . '_type' => 'text',
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
                    'ua_pt_' . $type . '_type' => 'icon',
                ],
            ]
        );

        // Icon Size
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
                    'ua_pt_' . $type . '_type' => 'icon',
                ],
            ]
        );

        // Icon Color
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
                    'ua_pt_' . $type . '_type' => 'icon',
                ],
            ]
        );

        // Icon Background
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => $prefix_key . '_icon_background',
                'types'     => [ 'classic', 'gradient' ],
                'selector'  => $selector . '-icon',
                'condition' => [
                    'ua_pt_' . $type . '_type' => 'icon',
                ],
            ]
        );

        // Icon Border Radius
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
                    'ua_pt_' . $type . '_type' => 'icon',
                ],
            ]
        );

        // Icon Padding
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
                    'ua_pt_' . $type . '_type' => 'icon',
                ],
            ]
        );

        // Icon Margin
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
                    'ua_pt_' . $type . '_type' => 'icon',
                ],
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
        $show_key = 'ua_pt_show_' . $type;
        $type_key = 'ua_pt_' . $type . '_type';

        if ( 'yes' !== ( $settings[ $show_key ] ?? '' ) ) {
            return '';
        }

        $affix_mode = $settings[ $type_key ] ?? 'text';

        if ( 'icon' === $affix_mode ) {
            $icon = $settings[ 'ua_pt_' . $type . '_icon' ] ?? [];
            if ( empty( $icon['value'] ) ) {
                return '';
            }

            ob_start();
            Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] );
            $icon_content = ob_get_clean();

            return sprintf(
                '<span class="ua-pt-affix ua-pt-%1$s ua-pt-%1$s-icon">%2$s</span>',
                esc_attr( $type ),
                $icon_content
            );
        }

        $text = $settings[ 'ua_pt_' . $type . '_text' ] ?? '';
        if ( '' === trim( $text ) ) {
            return '';
        }

        $allowed_html = function_exists( 'ultraaddons_allowed_html_tags' )
            ? ultraaddons_allowed_html_tags( 'advanced' )
            : [ 'span' => [], 'strong' => [], 'b' => [], 'i' => [], 'em' => [] ];

        return sprintf(
            '<span class="ua-pt-affix ua-pt-%1$s ua-pt-%1$s-badge">%2$s</span>',
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
                    <?php esc_html_e( 'Please install and activate WooCommerce to use the Product Title widget.', 'ultraaddons-elementor-lite' ); ?>
                </div>
                <?php
            }
            return;
        }

        $settings = $this->get_settings_for_display();

        // Resolve WooCommerce Product
        global $product;
        $current_product = $product;

        $custom_product_id = ! empty( $settings['ua_pt_product_id'] ) ? absint( $settings['ua_pt_product_id'] ) : 0;
        if ( $custom_product_id ) {
            $current_product = wc_get_product( $custom_product_id );
        }

        if ( ! is_a( $current_product, 'WC_Product' ) ) {
            $current_product = wc_get_product( get_the_ID() );
        }

        // Title and Permalink resolution
        if ( $current_product ) {
            $title_text        = $current_product->get_name();
            $product_permalink = get_permalink( $current_product->get_id() );
        } else {
            // If placed on a regular page without product context
            $post_title = get_the_title();
            if ( ! empty( $post_title ) ) {
                $title_text        = $post_title;
                $product_permalink = get_permalink();
            } elseif ( $is_editor ) {
                $title_text        = esc_html__( 'Product Title Preview', 'ultraaddons-elementor-lite' );
                $product_permalink = '#';
            } else {
                return;
            }
        }

        // Validate HTML tag
        $raw_tag = ! empty( $settings['ua_pt_tag'] ) ? $settings['ua_pt_tag'] : 'h2';
        $tag     = function_exists( 'ultraaddons_title_tag' ) ? ultraaddons_title_tag( $raw_tag ) : 'h2';

        $title_html = esc_html( $title_text );

        // Wrap in link if enabled
        if ( 'yes' === ( $settings['ua_pt_enable_link'] ?? '' ) ) {
            $link_type = $settings['ua_pt_link_type'] ?? 'product';

            if ( 'custom' === $link_type && ! empty( $settings['ua_pt_custom_url']['url'] ) ) {
                $this->add_link_attributes( 'ua_title_link', $settings['ua_pt_custom_url'] );
                $title_html = sprintf(
                    '<a %1$s>%2$s</a>',
                    $this->get_render_attribute_string( 'ua_title_link' ),
                    $title_html
                );
            } elseif ( 'product' === $link_type && ! empty( $product_permalink ) ) {
                $title_html = sprintf(
                    '<a href="%1$s">%2$s</a>',
                    esc_url( $product_permalink ),
                    $title_html
                );
            }
        }

        $prefix_html = $this->render_affix( $settings, 'prefix' );
        $suffix_html = $this->render_affix( $settings, 'suffix' );

        $this->add_render_attribute( 'ua_product_title_wrap', 'class', 'ua-product-title-wrapper' );
        ?>
        <div <?php echo $this->get_render_attribute_string( 'ua_product_title_wrap' ); ?>>
            <?php
            if ( ! empty( $prefix_html ) ) {
                echo $prefix_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
            ?>
            <<?php echo esc_attr( $tag ); ?> class="ua-pt-heading">
                <?php echo $title_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </<?php echo esc_attr( $tag ); ?>>
            <?php
            if ( ! empty( $suffix_html ) ) {
                echo $suffix_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
            ?>
        </div>
        <?php
    }
}
