<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

/**
 * Product Compare Widget
 *
 * Allows users to compare multiple WooCommerce products side-by-side
 * in a clean, fully responsive matrix table.
 *
 * @since 2.0.4.0
 * @package UltraAddons\Widget
 */
class Product_Compare extends Base {

    /**
     * Set widget keywords
     *
     * @return array
     */
    public function get_keywords() {
        return [ 'ultraaddons', 'ua', 'product compare', 'compare', 'comparison', 'woocommerce compare', 'product table', 'woo', 'specification' ];
    }

    /**
     * Retrieve list of WooCommerce products for dropdowns
     *
     * @return array
     */
    protected function get_product_options() {
        if ( ! function_exists( 'wc_get_products' ) ) {
            return [];
        }

        $products = wc_get_products( [
            'limit'   => 150,
            'status'  => 'publish',
            'orderby' => 'date',
            'order'   => 'DESC',
            'return'  => 'objects',
        ] );

        $options = [];
        if ( ! empty( $products ) ) {
            foreach ( $products as $product ) {
                if ( is_object( $product ) ) {
                    $options[ $product->get_id() ] = $product->get_name() . ' (#' . $product->get_id() . ')';
                }
            }
        }
        return $options;
    }

    /**
     * Retrieve custom attribute taxonomies from WooCommerce
     *
     * @return array
     */
    public static function get_wc_attribute_taxonomies_list() {
        if ( ! function_exists( 'wc_get_attribute_taxonomies' ) ) {
            return [];
        }
        $attributes_tax = wc_get_attribute_taxonomies();
        $data = [];
        if ( ! empty( $attributes_tax ) ) {
            foreach ( $attributes_tax as $item ) {
                $tax_name = wc_attribute_taxonomy_name( $item->attribute_name );
                $data[ $tax_name ] = $item->attribute_label;
            }
        }
        return $data;
    }

    /**
     * Available field types for comparison
     *
     * @return array
     */
    public static function get_field_types() {
        $standard_fields = [
            'image'       => esc_html__( 'Image', 'ultraaddons-elementor-lite' ),
            'title'       => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
            'price'       => esc_html__( 'Price', 'ultraaddons-elementor-lite' ),
            'description' => esc_html__( 'Description', 'ultraaddons-elementor-lite' ),
            'add_to_cart' => esc_html__( 'Add to cart', 'ultraaddons-elementor-lite' ),
            'rating'      => esc_html__( 'Rating', 'ultraaddons-elementor-lite' ),
            'stock'       => esc_html__( 'Stock', 'ultraaddons-elementor-lite' ),
            'sku'         => esc_html__( 'SKU', 'ultraaddons-elementor-lite' ),
            'weight'      => esc_html__( 'Weight', 'ultraaddons-elementor-lite' ),
            'dimensions'  => esc_html__( 'Dimensions', 'ultraaddons-elementor-lite' ),
        ];

        $attributes = self::get_wc_attribute_taxonomies_list();
        return array_merge( $standard_fields, $attributes );
    }

    /**
     * Default fields list for repeater
     *
     * @return array
     */
    public static function get_default_fields() {
        return [
            [
                'field_type'  => 'image',
                'field_label' => esc_html__( 'Image', 'ultraaddons-elementor-lite' ),
            ],
            [
                'field_type'  => 'title',
                'field_label' => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
            ],
            [
                'field_type'  => 'price',
                'field_label' => esc_html__( 'Price', 'ultraaddons-elementor-lite' ),
            ],
            [
                'field_type'  => 'description',
                'field_label' => esc_html__( 'Description', 'ultraaddons-elementor-lite' ),
            ],
            [
                'field_type'  => 'add_to_cart',
                'field_label' => esc_html__( 'Add to cart', 'ultraaddons-elementor-lite' ),
            ],
            [
                'field_type'  => 'rating',
                'field_label' => esc_html__( 'Rating', 'ultraaddons-elementor-lite' ),
            ],
            [
                'field_type'  => 'stock',
                'field_label' => esc_html__( 'Stock', 'ultraaddons-elementor-lite' ),
            ],
            [
                'field_type'  => 'sku',
                'field_label' => esc_html__( 'SKU', 'ultraaddons-elementor-lite' ),
            ],
            [
                'field_type'  => 'weight',
                'field_label' => esc_html__( 'Weight', 'ultraaddons-elementor-lite' ),
            ],
            [
                'field_type'  => 'dimensions',
                'field_label' => esc_html__( 'Dimensions', 'ultraaddons-elementor-lite' ),
            ],
        ];
    }

    /**
     * Register controls
     */
    protected function register_controls() {
        // Content Tab
        $this->register_content_product_controls();
        $this->register_content_table_settings();

        // Style Tab
        $this->register_style_table_controls();
        $this->register_style_header_col_controls();
        $this->register_style_product_cols_controls();
        $this->register_style_image_controls();
        $this->register_style_typography_controls();
        $this->register_style_button_controls();
    }

    /**
     * Content: Product Selection
     */
    protected function register_content_product_controls() {
        $this->start_controls_section(
            'ua_compare_products_section',
            [
                'label' => esc_html__( 'Product Compare', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        if ( ! function_exists( 'WC' ) ) {
            $this->add_control(
                'ua_wc_missing_notice',
                [
                    'type'            => Controls_Manager::RAW_HTML,
                    'raw'             => esc_html__( 'WooCommerce is not active. Please install and activate WooCommerce to use this widget.', 'ultraaddons-elementor-lite' ),
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                ]
            );
            $this->end_controls_section();
            return;
        }

        $this->add_control(
            'product_ids',
            [
                'label'       => esc_html__( 'Products', 'ultraaddons-elementor-lite' ),
                'type'        => 'ua-select2',
                'multiple'    => true,
                'label_block' => true,
                'source_type' => 'product',
                'source_name' => 'post_type',
                'placeholder' => esc_html__( 'Search by Product Name', 'ultraaddons-elementor-lite' ),
                'description' => esc_html__( 'Please enter 3 or more characters to search products.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'preset',
            [
                'label'   => esc_html__( 'Style Preset', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    ''        => esc_html__( 'Default', 'ultraaddons-elementor-lite' ),
                    'style-1' => esc_html__( 'Style 1', 'ultraaddons-elementor-lite' ),
                    'style-2' => esc_html__( 'Style 2', 'ultraaddons-elementor-lite' ),
                    'style-3' => esc_html__( 'Style 3', 'ultraaddons-elementor-lite' ),
                    'style-4' => esc_html__( 'Style 4', 'ultraaddons-elementor-lite' ),
                    'style-5' => esc_html__( 'Style 5', 'ultraaddons-elementor-lite' ),
                    'style-6' => esc_html__( 'Style 6', 'ultraaddons-elementor-lite' ),
                ],
                'default' => '',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content: Table Settings
     */
    protected function register_content_table_settings() {
        $this->start_controls_section(
            'ua_compare_table_settings_section',
            [
                'label' => esc_html__( 'Table Fields & Layout', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'table_title',
            [
                'label'       => esc_html__( 'Table Title', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'placeholder' => esc_html__( 'e.g. Product Comparison', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'table_title_tag',
            [
                'label'   => esc_html__( 'Title HTML Tag', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
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
                'default'   => 'h3',
                'condition' => [
                    'table_title!' => '',
                ],
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'field_type',
            [
                'label'   => esc_html__( 'Field Type', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'options' => self::get_field_types(),
                'default' => 'title',
            ]
        );

        $repeater->add_control(
            'field_label',
            [
                'label'       => esc_html__( 'Field Label', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => [ 'active' => true ],
                'default'     => esc_html__( 'Field Label', 'ultraaddons-elementor-lite' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'fields',
            [
                'label'       => esc_html__( 'Comparison Fields', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => self::get_default_fields(),
                'title_field' => '{{ field_label }}',
            ]
        );

        $this->add_control(
            'link_image',
            [
                'label'        => esc_html__( 'Link Product Image', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'link_title',
            [
                'label'        => esc_html__( 'Link Product Title', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'repeat_price',
            [
                'label'        => esc_html__( 'Repeat Price at Bottom', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'repeat_add_to_cart',
            [
                'label'        => esc_html__( 'Repeat Add to Cart at Bottom', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'field_icon_show',
            [
                'label'        => esc_html__( 'Show Attribute Icon', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'field_icon',
            [
                'label'     => esc_html__( 'Attribute Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'fas fa-check-circle',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'field_icon_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'empty_text',
            [
                'label'   => esc_html__( 'Fallback for Empty Data', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'default' => '-',
            ]
        );

        $this->add_control(
            'no_products_text',
            [
                'label'   => esc_html__( 'No Products Message', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'No products selected for comparison.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Table Container
     */
    protected function register_style_table_controls() {
        $this->start_controls_section(
            'ua_section_style_table',
            [
                'label' => esc_html__( 'Table Container', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'table_width',
            [
                'label'      => esc_html__( 'Max Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'vw' ],
                'range'      => [
                    'px' => [ 'min' => 300, 'max' => 1800 ],
                    '%'  => [ 'min' => 10, 'max' => 100 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-compare-wrapper' => 'max-width: {{SIZE}}{{UNIT}}; margin-left: auto; margin-right: auto;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'table_background',
                'label'    => esc_html__( 'Background', 'ultraaddons-elementor-lite' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-compare-table',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'table_border',
                'label'    => esc_html__( 'Border', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-compare-table',
            ]
        );

        $this->add_responsive_control(
            'table_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-compare-table, {{WRAPPER}} .ua-compare-table-responsive' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'table_box_shadow',
                'selector' => '{{WRAPPER}} .ua-compare-table',
            ]
        );

        $this->add_responsive_control(
            'table_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-compare-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Left Attribute Headers Column
     */
    protected function register_style_header_col_controls() {
        $this->start_controls_section(
            'ua_section_style_header_col',
            [
                'label' => esc_html__( 'Attribute Column (Header)', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'header_col_width',
            [
                'label'      => esc_html__( 'Column Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [ 'min' => 100, 'max' => 400 ],
                    '%'  => [ 'min' => 10, 'max' => 50 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-compare-field-th' => 'width: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'header_col_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-compare-field-th, {{WRAPPER}} .ua-compare-table th.ua-compare-field-th' => 'background-color: {{VALUE}} !important; background: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'header_col_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-compare-field-th, {{WRAPPER}} .ua-compare-field-label, {{WRAPPER}} .ua-compare-table th.ua-compare-field-th, {{WRAPPER}} .ua-compare-table th.ua-compare-field-th .ua-compare-field-label' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'header_col_typography',
                'selector' => '{{WRAPPER}} .ua-compare-field-th, {{WRAPPER}} .ua-compare-field-label',
            ]
        );

        $this->add_responsive_control(
            'header_col_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-compare-field-th, {{WRAPPER}} .ua-compare-table th.ua-compare-field-th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_control(
            'header_col_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-compare-field-icon i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-compare-field-icon svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'field_icon_show' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'header_col_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 8, 'max' => 40 ] ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-compare-field-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-compare-field-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'field_icon_show' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Product Data Columns
     */
    protected function register_style_product_cols_controls() {
        $this->start_controls_section(
            'ua_section_style_product_cols',
            [
                'label' => esc_html__( 'Product Columns', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'cell_text_align',
            [
                'label'     => esc_html__( 'Text Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [ 'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-center' ],
                    'right'  => [ 'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .ua-compare-product-td, {{WRAPPER}} .ua-compare-table td' => 'text-align: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'cell_padding',
            [
                'label'      => esc_html__( 'Cell Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-compare-product-td, {{WRAPPER}} .ua-compare-table td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_control(
            'cell_border_color',
            [
                'label'     => esc_html__( 'Dividers Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-compare-table th, {{WRAPPER}} .ua-compare-table td' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'col_odd_bg',
            [
                'label'     => esc_html__( 'Odd Columns Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-compare-product-td.ua-odd, {{WRAPPER}} .ua-compare-table td.ua-odd' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'col_even_bg',
            [
                'label'     => esc_html__( 'Even Columns Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-compare-product-td.ua-even, {{WRAPPER}} .ua-compare-table td.ua-even' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'heading_highlight_style',
            [
                'label'     => esc_html__( 'Highlighted / Featured Column', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'highlight_col_bg',
                'label'    => esc_html__( 'Highlighted Background', 'ultraaddons-elementor-lite' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-compare-product-td.ua-is-highlighted',
            ]
        );

        $this->add_control(
            'highlight_col_border_color',
            [
                'label'     => esc_html__( 'Highlighted Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-compare-product-td.ua-is-highlighted' => 'border-left-color: {{VALUE}} !important; border-right-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'badge_bg_color',
            [
                'label'     => esc_html__( 'Badge Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-compare-badge' => 'background-color: {{VALUE}} !important; background: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'badge_text_color',
            [
                'label'     => esc_html__( 'Badge Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-compare-badge' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'badge_typography',
                'selector' => '{{WRAPPER}} .ua-compare-badge',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Product Image
     */
    protected function register_style_image_controls() {
        $this->start_controls_section(
            'ua_section_style_image',
            [
                'label' => esc_html__( 'Product Image', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_max_width',
            [
                'label'      => esc_html__( 'Image Max Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [ 'min' => 40, 'max' => 350 ],
                    '%'  => [ 'min' => 10, 'max' => 100 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-compare-image-wrap img' => 'max-width: {{SIZE}}{{UNIT}} !important; width: 100%; height: auto;',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-compare-image-wrap img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'image_box_shadow',
                'selector' => '{{WRAPPER}} .ua-compare-image-wrap img',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Typography & Colors
     */
    protected function register_style_typography_controls() {
        $this->start_controls_section(
            'ua_section_style_typography',
            [
                'label' => esc_html__( 'Typography & Colors', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Title
        $this->add_control(
            'heading_title_style',
            [
                'label' => esc_html__( 'Product Title', 'ultraaddons-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Title Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-compare-product-title, {{WRAPPER}} .ua-compare-product-title a, {{WRAPPER}} .ua-compare-title-link, {{WRAPPER}} .ua-compare-table .ua-compare-product-title a' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label'     => esc_html__( 'Title Hover Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-compare-product-title a:hover, {{WRAPPER}} .ua-compare-title-link:hover, {{WRAPPER}} .ua-compare-table .ua-compare-product-title a:hover' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .ua-compare-product-title, {{WRAPPER}} .ua-compare-product-title a, {{WRAPPER}} .ua-compare-title-link',
            ]
        );

        // Price
        $this->add_control(
            'heading_price_style',
            [
                'label'     => esc_html__( 'Price', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label'     => esc_html__( 'Price Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-compare-price, {{WRAPPER}} .ua-compare-price .amount, {{WRAPPER}} .ua-compare-price bdi, {{WRAPPER}} .ua-compare-price ins, {{WRAPPER}} .ua-compare-price ins .amount' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'sale_price_color',
            [
                'label'     => esc_html__( 'Regular Price (On Sale)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-compare-price del, {{WRAPPER}} .ua-compare-price del .amount, {{WRAPPER}} .ua-compare-price del bdi' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'price_typography',
                'selector' => '{{WRAPPER}} .ua-compare-price, {{WRAPPER}} .ua-compare-price .amount, {{WRAPPER}} .ua-compare-price bdi',
            ]
        );

        // Description
        $this->add_control(
            'heading_desc_style',
            [
                'label'     => esc_html__( 'Description', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label'     => esc_html__( 'Description Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-compare-description, {{WRAPPER}} .ua-compare-description p' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'desc_typography',
                'selector' => '{{WRAPPER}} .ua-compare-description, {{WRAPPER}} .ua-compare-description p',
            ]
        );

        // Rating
        $this->add_control(
            'heading_rating_style',
            [
                'label'     => esc_html__( 'Rating Stars', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'rating_star_color',
            [
                'label'     => esc_html__( 'Star Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-compare-rating .star-rating, {{WRAPPER}} .ua-compare-rating .star-rating:before, {{WRAPPER}} .ua-compare-rating .star-rating span:before' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        // Stock
        $this->add_control(
            'heading_stock_style',
            [
                'label'     => esc_html__( 'Stock Status', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'instock_color',
            [
                'label'     => esc_html__( 'In Stock Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-compare-stock.ua-stock-instock' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'outofstock_color',
            [
                'label'     => esc_html__( 'Out of Stock Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-compare-stock.ua-stock-outofstock' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Add to Cart Button
     */
    protected function register_style_button_controls() {
        $this->start_controls_section(
            'ua_section_style_button',
            [
                'label' => esc_html__( 'Add to Cart Button', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .ua-compare-add-to-cart .button, {{WRAPPER}} .ua-compare-add-to-cart a.button',
            ]
        );

        $this->start_controls_tabs( 'ua_compare_button_tabs' );

        // Normal Tab
        $this->start_controls_tab(
            'ua_compare_button_tab_normal',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-compare-add-to-cart .button, {{WRAPPER}} .ua-compare-add-to-cart a.button, {{WRAPPER}} .ua-compare-table .ua-compare-add-to-cart .button, {{WRAPPER}} .ua-compare-table td .button' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-compare-add-to-cart .button, {{WRAPPER}} .ua-compare-add-to-cart a.button, {{WRAPPER}} .ua-compare-table .ua-compare-add-to-cart .button, {{WRAPPER}} .ua-compare-table td .button' => 'background: {{VALUE}} !important; background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'button_background',
                'label'    => esc_html__( 'Background (Gradient/Image)', 'ultraaddons-elementor-lite' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-compare-add-to-cart .button, {{WRAPPER}} .ua-compare-add-to-cart a.button, {{WRAPPER}} .ua-compare-table td .button',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'button_border',
                'selector' => '{{WRAPPER}} .ua-compare-add-to-cart .button, {{WRAPPER}} .ua-compare-add-to-cart a.button',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .ua-compare-add-to-cart .button, {{WRAPPER}} .ua-compare-add-to-cart a.button',
            ]
        );

        $this->end_controls_tab();

        // Hover Tab
        $this->start_controls_tab(
            'ua_compare_button_tab_hover',
            [
                'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'button_hover_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-compare-add-to-cart .button:hover, {{WRAPPER}} .ua-compare-add-to-cart a.button:hover, {{WRAPPER}} .ua-compare-table .ua-compare-add-to-cart .button:hover, {{WRAPPER}} .ua-compare-table td .button:hover' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'button_hover_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-compare-add-to-cart .button:hover, {{WRAPPER}} .ua-compare-add-to-cart a.button:hover, {{WRAPPER}} .ua-compare-table .ua-compare-add-to-cart .button:hover, {{WRAPPER}} .ua-compare-table td .button:hover' => 'background: {{VALUE}} !important; background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'button_hover_background',
                'label'    => esc_html__( 'Background (Gradient/Image)', 'ultraaddons-elementor-lite' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-compare-add-to-cart .button:hover, {{WRAPPER}} .ua-compare-add-to-cart a.button:hover, {{WRAPPER}} .ua-compare-table td .button:hover',
            ]
        );

        $this->add_control(
            'button_hover_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-compare-add-to-cart .button:hover, {{WRAPPER}} .ua-compare-add-to-cart a.button:hover' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'button_hover_box_shadow',
                'selector' => '{{WRAPPER}} .ua-compare-add-to-cart .button:hover, {{WRAPPER}} .ua-compare-add-to-cart a.button:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'button_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'separator'  => 'before',
                'selectors'  => [
                    '{{WRAPPER}} .ua-compare-add-to-cart .button, {{WRAPPER}} .ua-compare-add-to-cart a.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-compare-add-to-cart .button:not(.added), {{WRAPPER}} .ua-compare-add-to-cart a.button:not(.added)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Helper: Render single product field value
     *
     * @param string $field_type Field type identifier.
     * @param \WC_Product $product WooCommerce product object.
     * @param array $settings Display settings.
     * @param bool $is_highlighted Whether the product is highlighted.
     * @return void
     */
    protected function render_field_cell( $field_type, $wc_product, $settings, $is_highlighted ) {
        $link_image  = ( $settings['link_image'] ?? 'yes' ) === 'yes';
        $link_title  = ( $settings['link_title'] ?? 'yes' ) === 'yes';
        $badge_text  = ! empty( $settings['highlight_badge_text'] ) ? $settings['highlight_badge_text'] : esc_html__( 'Featured', 'ultraaddons-elementor-lite' );
        $empty_text  = isset( $settings['empty_text'] ) ? $settings['empty_text'] : '-';
        $product_id  = $wc_product->get_id();

        switch ( $field_type ) {
            case 'image':
                echo '<div class="ua-compare-image-wrap">';
                if ( $is_highlighted && ! empty( $badge_text ) ) {
                    echo '<span class="ua-compare-badge">' . esc_html( $badge_text ) . '</span>';
                }
                if ( $link_image ) {
                    echo '<a href="' . esc_url( $wc_product->get_permalink() ) . '" class="ua-compare-img-link">';
                }
                echo $wc_product->get_image( 'woocommerce_thumbnail' );
                if ( $link_image ) {
                    echo '</a>';
                }
                echo '</div>';
                break;

            case 'title':
                if ( $link_title ) {
                    echo '<h4 class="ua-compare-product-title"><a href="' . esc_url( $wc_product->get_permalink() ) . '" class="ua-compare-title-link">' . esc_html( $wc_product->get_name() ) . '</a></h4>';
                } else {
                    echo '<h4 class="ua-compare-product-title"><span class="ua-compare-title-link">' . esc_html( $wc_product->get_name() ) . '</span></h4>';
                }
                break;

            case 'rating':
                $rating_count = $wc_product->get_rating_count();
                $average      = $wc_product->get_average_rating();
                echo '<div class="ua-compare-rating">';
                if ( function_exists( 'wc_get_rating_html' ) ) {
                    echo wc_get_rating_html( $average, $rating_count );
                }
                if ( $rating_count > 0 ) {
                    echo '<span class="ua-compare-review-count">(' . esc_html( $rating_count ) . ')</span>';
                } else {
                    echo '<span class="ua-compare-no-rating">' . esc_html__( 'No reviews', 'ultraaddons-elementor-lite' ) . '</span>';
                }
                echo '</div>';
                break;

            case 'price':
                $price_html = $wc_product->get_price_html();
                echo '<div class="ua-compare-price">' . ( ! empty( $price_html ) ? $price_html : esc_html( $empty_text ) ) . '</div>';
                break;

            case 'add_to_cart':
            case 'add-to-cart':
                global $product;
                $prev_product = $product;
                $product      = $wc_product;
                echo '<div class="ua-compare-add-to-cart">';
                ob_start();
                woocommerce_template_loop_add_to_cart();
                $cart_html = ob_get_clean();
                if ( ! empty( $cart_html ) ) {
                    echo $cart_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                } else {
                    $button_url  = $wc_product->add_to_cart_url();
                    $button_text = $wc_product->add_to_cart_text();
                    echo sprintf(
                        '<a href="%s" class="button add_to_cart_button product_type_%s" data-product_id="%d">%s</a>',
                        esc_url( $button_url ),
                        esc_attr( $wc_product->get_type() ),
                        esc_attr( $wc_product->get_id() ),
                        esc_html( $button_text )
                    );
                }
                echo '</div>';
                $product = $prev_product;
                break;

            case 'description':
                $desc = $wc_product->get_short_description();
                if ( empty( $desc ) ) {
                    $desc = wp_trim_words( $wc_product->get_description(), 18 );
                }
                echo '<div class="ua-compare-description">' . ( ! empty( $desc ) ? wp_kses_post( $desc ) : esc_html( $empty_text ) ) . '</div>';
                break;

            case 'sku':
                $sku = $wc_product->get_sku();
                echo '<span class="ua-compare-sku">' . ( ! empty( $sku ) ? esc_html( $sku ) : esc_html( $empty_text ) ) . '</span>';
                break;

            case 'stock':
                $stock_status = $wc_product->get_stock_status();
                $is_in_stock  = $wc_product->is_in_stock();
                $availability = $wc_product->get_availability();
                $stock_text   = ! empty( $availability['availability'] ) ? $availability['availability'] : ( $is_in_stock ? esc_html__( 'In Stock', 'ultraaddons-elementor-lite' ) : esc_html__( 'Out of Stock', 'ultraaddons-elementor-lite' ) );
                echo '<span class="ua-compare-stock ua-stock-' . esc_attr( $stock_status ) . '">' . esc_html( $stock_text ) . '</span>';
                break;

            case 'weight':
                $weight = $wc_product->get_weight();
                if ( $weight ) {
                    echo '<span class="ua-compare-weight">' . esc_html( wc_format_localized_decimal( $weight ) . ' ' . get_option( 'woocommerce_weight_unit' ) ) . '</span>';
                } else {
                    echo '<span class="ua-compare-empty">' . esc_html( $empty_text ) . '</span>';
                }
                break;

            case 'dimensions':
                $dimensions = function_exists( 'wc_format_dimensions' ) ? wc_format_dimensions( $wc_product->get_dimensions( false ) ) : $wc_product->get_dimensions();
                echo '<span class="ua-compare-dimensions">' . ( ! empty( $dimensions ) ? esc_html( $dimensions ) : esc_html( $empty_text ) ) . '</span>';
                break;

            default:
                if ( taxonomy_exists( $field_type ) ) {
                    $terms = get_the_terms( $product_id, $field_type );
                    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                        $term_names = wp_list_pluck( $terms, 'name' );
                        echo '<span class="ua-compare-attr">' . esc_html( implode( ', ', $term_names ) ) . '</span>';
                    } else {
                        echo '<span class="ua-compare-empty">' . esc_html( $empty_text ) . '</span>';
                    }
                } else {
                    $attr_val = $wc_product->get_attribute( $field_type );
                    echo '<span class="ua-compare-attr">' . ( ! empty( $attr_val ) ? esc_html( $attr_val ) : esc_html( $empty_text ) ) . '</span>';
                }
                break;
        }
    }

    /**
     * Render widget frontend output
     */
    protected function render() {
        if ( ! function_exists( 'WC' ) || ! function_exists( 'wc_get_product' ) ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo '<div class="ua-alert ua-alert-warning">' . esc_html__( 'WooCommerce is not active. Please install and activate WooCommerce to use Product Compare widget.', 'ultraaddons-elementor-lite' ) . '</div>';
            }
            return;
        }

        $settings = $this->get_settings_for_display();
        $raw_product_ids = ! empty( $settings['product_ids'] ) ? (array) $settings['product_ids'] : [];
        $product_ids = array_map( 'intval', array_filter( $raw_product_ids ) );
        $highlighted_id = 0;
        $fields = ! empty( $settings['fields'] ) && is_array( $settings['fields'] ) ? $settings['fields'] : self::get_default_fields();
        $table_title = ! empty( $settings['table_title'] ) ? $settings['table_title'] : '';
        $table_title_tag = ! empty( $settings['table_title_tag'] ) ? Utils::validate_html_tag( $settings['table_title_tag'] ) : 'h3';
        $preset = ! empty( $settings['preset'] ) ? str_replace( 'theme-', 'style-', $settings['preset'] ) : 'default';
        $repeat_price = ( $settings['repeat_price'] ?? 'no' ) === 'yes';
        $repeat_add_to_cart = ( $settings['repeat_add_to_cart'] ?? 'yes' ) === 'yes';
        $show_icon = ( $settings['field_icon_show'] ?? 'no' ) === 'yes';
        $field_icon = ! empty( $settings['field_icon'] ) ? $settings['field_icon'] : [];
        $no_products_text = ! empty( $settings['no_products_text'] ) ? $settings['no_products_text'] : esc_html__( 'No products selected for comparison.', 'ultraaddons-elementor-lite' );

        // Query WooCommerce products
        $products = [];
        if ( ! empty( $product_ids ) ) {
            foreach ( $product_ids as $pid ) {
                $p = wc_get_product( $pid );
                if ( $p && $p->is_visible() ) {
                    $products[ $pid ] = $p;
                }
            }
        }

        if ( empty( $products ) ) {
            echo '<div class="ua-product-compare-empty">';
            echo '<p>' . esc_html( $no_products_text ) . '</p>';
            echo '</div>';
            return;
        }
        ?>
        <div class="ua-product-compare-wrapper ua-preset-<?php echo esc_attr( $preset ); ?>">
            <?php if ( ! empty( $table_title ) ) : ?>
                <<?php echo esc_attr( $table_title_tag ); ?> class="ua-compare-table-title">
                    <?php echo esc_html( $table_title ); ?>
                </<?php echo esc_attr( $table_title_tag ); ?>>
            <?php endif; ?>

            <div class="ua-compare-table-responsive">
                <table class="ua-compare-table">
                    <tbody>
                        <?php foreach ( $fields as $field_item ) :
                            $f_type  = $field_item['field_type'] ?? '';
                            $f_label = $field_item['field_label'] ?? '';
                            if ( empty( $f_type ) ) {
                                continue;
                            }
                            if ( ( $f_type === 'add_to_cart' || $f_type === 'add-to-cart' ) && $f_label === 'Purchase' ) {
                                $f_label = esc_html__( 'Add to cart', 'ultraaddons-elementor-lite' );
                            }
                        ?>
                            <tr class="ua-compare-row ua-row-<?php echo esc_attr( str_replace( '_', '-', $f_type ) ); ?>">
                                <th class="ua-compare-field-th">
                                    <div class="ua-compare-field-inner">
                                        <?php if ( $show_icon && ! empty( $field_icon['value'] ) ) : ?>
                                            <span class="ua-compare-field-icon">
                                                <?php Icons_Manager::render_icon( $field_icon, [ 'aria-hidden' => 'true' ] ); ?>
                                            </span>
                                        <?php endif; ?>
                                        <span class="ua-compare-field-label"><?php echo esc_html( $f_label ); ?></span>
                                    </div>
                                </th>

                                <?php
                                $col_idx = 0;
                                foreach ( $products as $pid => $product ) :
                                    $is_highlighted = ( $pid === $highlighted_id );
                                    $col_classes = [
                                        'ua-compare-product-td',
                                        'ua-col-' . $col_idx,
                                        'ua-product-' . $pid,
                                        ( $col_idx % 2 === 0 ? 'ua-odd' : 'ua-even' ),
                                    ];
                                    if ( $is_highlighted ) {
                                        $col_classes[] = 'ua-is-highlighted';
                                    }
                                ?>
                                    <td class="<?php echo esc_attr( implode( ' ', $col_classes ) ); ?>">
                                        <?php $this->render_field_cell( $f_type, $product, $settings, $is_highlighted ); ?>
                                    </td>
                                <?php
                                    $col_idx++;
                                endforeach;
                                ?>
                            </tr>
                        <?php endforeach; ?>

                        <?php if ( $repeat_price ) : ?>
                            <tr class="ua-compare-row ua-row-price ua-row-repeated">
                                <th class="ua-compare-field-th">
                                    <div class="ua-compare-field-inner">
                                        <?php if ( $show_icon && ! empty( $field_icon['value'] ) ) : ?>
                                            <span class="ua-compare-field-icon">
                                                <?php Icons_Manager::render_icon( $field_icon, [ 'aria-hidden' => 'true' ] ); ?>
                                            </span>
                                        <?php endif; ?>
                                        <span class="ua-compare-field-label"><?php esc_html_e( 'Price', 'ultraaddons-elementor-lite' ); ?></span>
                                    </div>
                                </th>
                                <?php
                                $col_idx = 0;
                                foreach ( $products as $pid => $product ) :
                                    $is_highlighted = ( $pid === $highlighted_id );
                                    $col_classes = [
                                        'ua-compare-product-td',
                                        'ua-col-' . $col_idx,
                                        ( $col_idx % 2 === 0 ? 'ua-odd' : 'ua-even' ),
                                    ];
                                    if ( $is_highlighted ) {
                                        $col_classes[] = 'ua-is-highlighted';
                                    }
                                ?>
                                    <td class="<?php echo esc_attr( implode( ' ', $col_classes ) ); ?>">
                                        <div class="ua-compare-price"><?php echo $product->get_price_html(); ?></div>
                                    </td>
                                <?php
                                    $col_idx++;
                                endforeach;
                                ?>
                            </tr>
                        <?php endif; ?>

                        <?php
                        $last_field      = end( $fields );
                        $last_field_type = $last_field['field_type'] ?? '';
                        reset( $fields );
                        if ( $repeat_add_to_cart && $last_field_type !== 'add_to_cart' && $last_field_type !== 'add-to-cart' ) :
                        ?>
                            <tr class="ua-compare-row ua-row-add-to-cart ua-row-repeated">
                                <th class="ua-compare-field-th">
                                    <div class="ua-compare-field-inner">
                                        <?php if ( $show_icon && ! empty( $field_icon['value'] ) ) : ?>
                                            <span class="ua-compare-field-icon">
                                                <?php Icons_Manager::render_icon( $field_icon, [ 'aria-hidden' => 'true' ] ); ?>
                                            </span>
                                        <?php endif; ?>
                                        <span class="ua-compare-field-label"><?php esc_html_e( 'Add to cart', 'ultraaddons-elementor-lite' ); ?></span>
                                    </div>
                                </th>
                                <?php
                                $col_idx = 0;
                                foreach ( $products as $pid => $prod_item ) :
                                    $is_highlighted = ( $pid === $highlighted_id );
                                    $col_classes = [
                                        'ua-compare-product-td',
                                        'ua-col-' . $col_idx,
                                        ( $col_idx % 2 === 0 ? 'ua-odd' : 'ua-even' ),
                                    ];
                                    if ( $is_highlighted ) {
                                        $col_classes[] = 'ua-is-highlighted';
                                    }
                                ?>
                                    <td class="<?php echo esc_attr( implode( ' ', $col_classes ) ); ?>">
                                        <div class="ua-compare-add-to-cart">
                                            <?php
                                            global $product;
                                            $prev_product = $product;
                                            $product      = $prod_item;
                                            ob_start();
                                            woocommerce_template_loop_add_to_cart();
                                            $cart_html = ob_get_clean();
                                            if ( ! empty( $cart_html ) ) {
                                                echo $cart_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                            } else {
                                                echo sprintf(
                                                    '<a href="%s" class="button add_to_cart_button product_type_%s" data-product_id="%d">%s</a>',
                                                    esc_url( $prod_item->add_to_cart_url() ),
                                                    esc_attr( $prod_item->get_type() ),
                                                    esc_attr( $prod_item->get_id() ),
                                                    esc_html( $prod_item->add_to_cart_text() )
                                                );
                                            }
                                            $product = $prev_product;
                                            ?>
                                        </div>
                                    </td>
                                <?php
                                    $col_idx++;
                                endforeach;
                                ?>
                            </tr>
                        <?php endif; ?>

                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }
}
