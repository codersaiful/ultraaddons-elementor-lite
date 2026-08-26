<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Utils;
use WP_Query;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * UltraAddons WooCommerce Products Widget
 * 
 * An advanced, high-performance, all-in-one WooCommerce Products widget
 * with flexible Query sources (Recent, Best Selling, Top Rated, Featured, On Sale, Custom),
 * responsive Grid & List layouts, 4 Design Presets, Hover Gallery Flip, Smart Badges,
 * AJAX Category Filter, AJAX Load More, and built-in Quick View Modal.
 * 
 * @since 2.0.3.5
 * @package UltraAddons
 */
class WC_Products extends Base {

    /**
     * Constructor — Register assets & AJAX hooks.
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        $css_file = ULTRA_ADDONS_DIR . 'assets/css/widgets/wc-products.css';
        $css_ver  = file_exists( $css_file ) ? filemtime( $css_file ) : ULTRA_ADDONS_VERSION;

        $js_file  = ULTRA_ADDONS_DIR . 'assets/js/frontend-wc-products.js';
        $js_ver   = file_exists( $js_file ) ? filemtime( $js_file ) : ULTRA_ADDONS_VERSION;

        wp_register_style(
            'ultraaddons-wc-products',
            ULTRA_ADDONS_ASSETS . 'css/widgets/wc-products.css',
            [],
            $css_ver,
            'all'
        );

        wp_register_script(
            'ultraaddons-wc-products',
            ULTRA_ADDONS_ASSETS . 'js/frontend-wc-products.js',
            [ 'jquery' ],
            $js_ver,
            true
        );

        wp_localize_script(
            'ultraaddons-wc-products',
            'uaWCProductsConfig',
            [
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'ua-wc-products-nonce' ),
                'i18n'     => [
                    'loading'       => esc_html__( 'Loading...', 'ultraaddons-elementor-lite' ),
                    'no_more'       => esc_html__( 'No more products to load', 'ultraaddons-elementor-lite' ),
                    'added_to_cart' => esc_html__( 'Added to cart!', 'ultraaddons-elementor-lite' ),
                    'view_cart'     => esc_html__( 'View Cart', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );
    }

    /**
     * Get widget keywords.
     *
     * @return array
     */
    public function get_keywords() {
        return [ 'ultraaddons', 'ua', 'wc', 'product', 'products', 'woocommerce', 'shop', 'grid', 'store', 'items' ];
    }

    /**
     * Script dependencies.
     *
     * @return array
     */
    public function get_script_depends() {
        return array_merge( parent::get_script_depends(), [ 'jquery', 'ultraaddons-wc-products' ] );
    }

    /**
     * Style dependencies.
     *
     * @return array
     */
    public function get_style_depends() {
        return array_merge( parent::get_style_depends(), [ 'ultraaddons-wc-products' ] );
    }

    /**
     * Register widget controls.
     */
    protected function register_controls() {
        // Content Tab
        $this->content_layout_controls();
        $this->content_query_controls();
        $this->content_elements_controls();
        $this->content_filter_controls();

        // Style Tab
        $this->style_card_controls();
        $this->style_image_controls();
        $this->style_badges_controls();
        $this->style_content_controls();
        $this->style_cart_btn_controls();
        $this->style_action_icons_controls();
        $this->style_filter_bar_controls();
        $this->style_pagination_controls();
    }

    /**
     * Layout Controls Section
     */
    protected function content_layout_controls() {
        $this->start_controls_section(
            'section_layout',
            [
                'label' => esc_html__( 'Layout & Design', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label'          => esc_html__( 'Columns', 'ultraaddons-elementor-lite' ),
                'type'           => Controls_Manager::SELECT,
                'default'        => '4',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options'        => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-wc-products-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );

        $this->add_responsive_control(
            'columns_gap',
            [
                'label'      => esc_html__( 'Column Gap', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', '%' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 60 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 24 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-wc-products-grid' => 'column-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'rows_gap',
            [
                'label'      => esc_html__( 'Row Gap', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', '%' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 80 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 30 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-wc-products-grid' => 'row-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_align',
            [
                'label'     => esc_html__( 'Content Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
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
                'default'   => 'left',
                'selectors' => [
                    '{{WRAPPER}} .ua-wc-product-content-wrapper' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .ua-product-category'          => 'text-align: {{VALUE}} !important; display: block; width: 100%;',
                    '{{WRAPPER}} .ua-product-title'             => 'text-align: {{VALUE}} !important; width: 100%;',
                    '{{WRAPPER}} .ua-product-title a'           => 'text-align: {{VALUE}} !important;',
                    '{{WRAPPER}} .ua-product-rating'            => 'justify-content: {{VALUE}} !important; display: flex; width: 100%;',
                    '{{WRAPPER}} .ua-product-price'             => 'justify-content: {{VALUE}} !important; display: flex; width: 100%;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Query Controls Section
     */
    protected function content_query_controls() {
        $this->start_controls_section(
            'section_query',
            [
                'label' => esc_html__( 'Query Settings', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'query_type',
            [
                'label'   => esc_html__( 'Product Source', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'recent',
                'options' => [
                    'recent'       => esc_html__( 'Latest / Recent Products', 'ultraaddons-elementor-lite' ),
                    'best_selling' => esc_html__( 'Best Selling Products', 'ultraaddons-elementor-lite' ),
                    'top_rated'    => esc_html__( 'Top Rated Products', 'ultraaddons-elementor-lite' ),
                    'featured'     => esc_html__( 'Featured Products', 'ultraaddons-elementor-lite' ),
                    'on_sale'      => esc_html__( 'On Sale Products', 'ultraaddons-elementor-lite' ),
                    'custom'       => esc_html__( 'Manual Selection (by IDs)', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'product_ids',
            [
                'label'       => esc_html__( 'Product IDs', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => '12, 34, 56',
                'description' => esc_html__( 'Enter comma-separated product IDs to display.', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'query_type' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'categories',
            [
                'label'       => esc_html__( 'Categories', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT2,
                'options'     => $this->get_product_categories(),
                'multiple'    => true,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'tags',
            [
                'label'       => esc_html__( 'Tags', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT2,
                'options'     => $this->get_product_tags(),
                'multiple'    => true,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label'   => esc_html__( 'Products Per Page', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => 1,
                'max'     => 100,
                'step'    => 1,
                'default' => 8,
            ]
        );

        $this->add_control(
            'offset',
            [
                'label'   => esc_html__( 'Offset', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => 0,
                'max'     => 100,
                'step'    => 1,
                'default' => 0,
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label'   => esc_html__( 'Order By', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'date'       => esc_html__( 'Date', 'ultraaddons-elementor-lite' ),
                    'title'      => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                    'price'      => esc_html__( 'Price', 'ultraaddons-elementor-lite' ),
                    'popularity' => esc_html__( 'Popularity (Sales)', 'ultraaddons-elementor-lite' ),
                    'rating'     => esc_html__( 'Rating', 'ultraaddons-elementor-lite' ),
                    'rand'       => esc_html__( 'Random', 'ultraaddons-elementor-lite' ),
                    'menu_order' => esc_html__( 'Menu Order', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label'   => esc_html__( 'Order', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => [
                    'DESC' => esc_html__( 'Descending (DESC)', 'ultraaddons-elementor-lite' ),
                    'ASC'  => esc_html__( 'Ascending (ASC)', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'hide_out_of_stock',
            [
                'label'        => esc_html__( 'Hide Out of Stock', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'exclude_ids',
            [
                'label'       => esc_html__( 'Exclude Products', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => '10, 20, 30',
                'description' => esc_html__( 'Comma-separated IDs to exclude.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Card Elements Controls Section
     */
    protected function content_elements_controls() {
        $this->start_controls_section(
            'section_elements',
            [
                'label' => esc_html__( 'Card Elements', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Badges Group Heading
        $this->add_control(
            'heading_badges_content',
            [
                'label'     => esc_html__( 'Product Badges', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'badge_position',
            [
                'label'   => esc_html__( 'Badge Position', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'top-left',
                'options' => [
                    'top-left'     => esc_html__( 'Top Left', 'ultraaddons-elementor-lite' ),
                    'top-right'    => esc_html__( 'Top Right', 'ultraaddons-elementor-lite' ),
                    'bottom-left'  => esc_html__( 'Bottom Left', 'ultraaddons-elementor-lite' ),
                    'bottom-right' => esc_html__( 'Bottom Right', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'badge_shape',
            [
                'label'   => esc_html__( 'Badge Shape', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'rounded',
                'options' => [
                    'rounded' => esc_html__( 'Rounded (Default)', 'ultraaddons-elementor-lite' ),
                    'square'  => esc_html__( 'Square', 'ultraaddons-elementor-lite' ),
                    'pill'    => esc_html__( 'Pill Shape', 'ultraaddons-elementor-lite' ),
                    'circle'  => esc_html__( 'Circle Badge', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        // 1. Sale Badge
        $this->add_control(
            'show_badge',
            [
                'label'        => esc_html__( 'Show Sale Badge', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'badge_type',
            [
                'label'     => esc_html__( 'Sale Badge Type', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'percent',
                'options'   => [
                    'percent'     => esc_html__( 'Percentage (-25%)', 'ultraaddons-elementor-lite' ),
                    'percent_off' => esc_html__( 'Percentage Off (25% OFF)', 'ultraaddons-elementor-lite' ),
                    'save_amount' => esc_html__( 'Save Amount (SAVE $10)', 'ultraaddons-elementor-lite' ),
                    'text'        => esc_html__( 'Custom Text', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'show_badge' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'sale_badge_custom_text',
            [
                'label'       => esc_html__( 'Sale Badge Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'SALE', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'SALE / HOT DEAL', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'show_badge' => 'yes',
                    'badge_type' => 'text',
                ],
            ]
        );

        // 2. NEW Badge
        $this->add_control(
            'show_new_badge',
            [
                'label'        => esc_html__( 'Show "NEW" Badge', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'new_badge_days',
            [
                'label'       => esc_html__( 'New Badge Days Threshold', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 14,
                'description' => esc_html__( 'Products published within this number of days will show the "NEW" badge.', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'show_new_badge' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'new_badge_custom_text',
            [
                'label'     => esc_html__( 'NEW Badge Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'NEW', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'show_new_badge' => 'yes',
                ],
            ]
        );

        // 3. Featured Badge
        $this->add_control(
            'show_featured_badge',
            [
                'label'        => esc_html__( 'Show "Featured" Badge', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'featured_badge_custom_text',
            [
                'label'     => esc_html__( 'Featured Badge Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'FEATURED', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'show_featured_badge' => 'yes',
                ],
            ]
        );

        // 4. Hot / Best Seller Badge
        $this->add_control(
            'show_hot_badge',
            [
                'label'        => esc_html__( 'Show "HOT / Best Seller" Badge', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'hot_badge_sales_min',
            [
                'label'       => esc_html__( 'Minimum Sales for HOT Badge', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 10,
                'description' => esc_html__( 'Products with total sales equal or greater than this number will show the badge.', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'show_hot_badge' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'hot_badge_custom_text',
            [
                'label'     => esc_html__( 'HOT Badge Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'HOT', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'show_hot_badge' => 'yes',
                ],
            ]
        );

        // 5. Stock / Sold Out Badge
        $this->add_control(
            'show_stock_badge',
            [
                'label'        => esc_html__( 'Show "Sold Out" Badge', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'stock_badge_custom_text',
            [
                'label'     => esc_html__( 'Sold Out Badge Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Sold Out', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'show_stock_badge' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_secondary_image',
            [
                'label'        => esc_html__( 'Hover Gallery Image Flip', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'      => 'image_size',
                'default'   => 'woocommerce_thumbnail',
            ]
        );

        $this->add_responsive_control(
            'image_gap',
            [
                'label'      => esc_html__( 'Image Bottom Gap', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 60 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 0 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-thumb-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'separator'  => 'after',
            ]
        );

        $this->add_control(
            'show_category',
            [
                'label'        => esc_html__( 'Show Category', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_responsive_control(
            'category_gap',
            [
                'label'      => esc_html__( 'Category Bottom Gap', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 50 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 6 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-category' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'show_category' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label'        => esc_html__( 'Show Title', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label'     => esc_html__( 'Title HTML Tag', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'h3',
                'options'   => [
                    'h2'   => 'H2',
                    'h3'   => 'H3',
                    'h4'   => 'H4',
                    'h5'   => 'H5',
                    'h6'   => 'H6',
                    'div'  => 'div',
                    'span' => 'span',
                    'p'    => 'p',
                ],
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_gap',
            [
                'label'      => esc_html__( 'Title Bottom Gap', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 50 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 8 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'show_title' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_rating',
            [
                'label'        => esc_html__( 'Show Rating', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_responsive_control(
            'rating_gap',
            [
                'label'      => esc_html__( 'Rating Bottom Gap', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 50 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 8 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-rating' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'show_rating' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_price',
            [
                'label'        => esc_html__( 'Show Price', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_responsive_control(
            'price_gap',
            [
                'label'      => esc_html__( 'Price Bottom Gap', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 50 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 12 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-footer' => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'show_price' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_cart_btn',
            [
                'label'        => esc_html__( 'Show Add to Cart Button', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'cart_btn_text',
            [
                'label'     => esc_html__( 'Custom Cart Button Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => '',
                'placeholder' => esc_html__( 'Default / Add to Cart', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'show_cart_btn' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'cart_btn_icon',
            [
                'label'       => esc_html__( 'Cart Button Icon', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::ICONS,
                'default'     => [
                    'value'   => 'fas fa-shopping-basket',
                    'library' => 'fa-solid',
                ],
                'condition'   => [
                    'show_cart_btn' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Filter & Pagination Controls Section
     */
    protected function content_filter_controls() {
        $this->start_controls_section(
            'section_filter_pagination',
            [
                'label' => esc_html__( 'Filter & Pagination', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_category_filter',
            [
                'label'        => esc_html__( 'AJAX Category Filter Bar', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->add_responsive_control(
            'filter_bar_alignment',
            [
                'label'     => esc_html__( 'Filter Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
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
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .ua-products-filter-bar' => 'text-align: {{VALUE}} !important;',
                ],
                'condition' => [
                    'show_category_filter' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'filter_all_label',
            [
                'label'     => esc_html__( '"All" Tab Label', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'All Products', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'show_category_filter' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'filter_categories',
            [
                'label'       => esc_html__( 'Filter Categories', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT2,
                'options'     => $this->get_product_categories(),
                'multiple'    => true,
                'label_block' => true,
                'description' => esc_html__( 'Select which categories to show in the filter bar. Leave empty to auto-detect from your products.', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'show_category_filter' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_filter_count',
            [
                'label'        => esc_html__( 'Show Product Count Badge', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'condition'    => [
                    'show_category_filter' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'filter_gap',
            [
                'label'      => esc_html__( 'Button Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 50 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-products-filter-bar button.ua-filter-btn, {{WRAPPER}} .ua-products-filter-bar .ua-filter-btn' => 'margin-left: {{SIZE}}{{UNIT}} !important; margin-right: {{SIZE}}{{UNIT}} !important;',
                ],
                'condition'  => [
                    'show_category_filter' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'pagination_type',
            [
                'label'     => esc_html__( 'Pagination Type', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'none',
                'separator' => 'before',
                'options'   => [
                    'none'      => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                    'numbers'   => esc_html__( 'Page Numbers (Standard)', 'ultraaddons-elementor-lite' ),
                    'load_more' => esc_html__( 'AJAX Load More Button', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'load_more_text',
            [
                'label'     => esc_html__( 'Load More Button Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Load More Products', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'pagination_type' => 'load_more',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_alignment',
            [
                'label'     => esc_html__( 'Pagination Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
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
                'default'   => 'center',
                'selectors_dictionary' => [
                    'left'   => 'left; justify-content: flex-start',
                    'center' => 'center; justify-content: center',
                    'right'  => 'right; justify-content: flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-load-more-wrapper, {{WRAPPER}} .ua-pagination, {{WRAPPER}} .ua-wc-products-wrapper .ua-load-more-wrapper, {{WRAPPER}} .ua-wc-products-wrapper .ua-pagination' => 'text-align: {{VALUE}} !important;',
                ],
                'condition' => [
                    'pagination_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_items_gap',
            [
                'label'      => esc_html__( 'Page Number Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 40 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 6,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pagination a, {{WRAPPER}} .ua-pagination span, {{WRAPPER}} .ua-pagination .page-numbers' => 'margin-left: calc({{SIZE}}{{UNIT}} / 2) !important; margin-right: calc({{SIZE}}{{UNIT}} / 2) !important;',
                ],
                'condition'  => [
                    'pagination_type' => 'numbers',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_spacing',
            [
                'label'      => esc_html__( 'Pagination Top Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 120 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 35,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-load-more-wrapper, {{WRAPPER}} .ua-pagination' => 'margin-top: {{SIZE}}{{UNIT}} !important;',
                ],
                'condition'  => [
                    'pagination_type!' => 'none',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Card Box Style Section
     */
    protected function style_card_controls() {
        $this->start_controls_section(
            'style_card_section',
            [
                'label' => esc_html__( 'Product Card Box', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_card_style' );

        $this->start_controls_tab(
            'tab_card_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'card_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-wc-product-card',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'card_border',
                'selector' => '{{WRAPPER}} .ua-wc-product-card',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'card_shadow',
                'selector' => '{{WRAPPER}} .ua-wc-product-card',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_card_hover',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'card_bg_hover',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-wc-product-card:hover',
            ]
        );

        $this->add_control(
            'card_border_color_hover',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-wc-product-card:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'card_shadow_hover',
                'selector' => '{{WRAPPER}} .ua-wc-product-card:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'card_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'separator'  => 'before',
                'selectors'  => [
                    '{{WRAPPER}} .ua-wc-product-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-wc-product-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Image Style Section
     */
    protected function style_image_controls() {
        $this->start_controls_section(
            'style_image_section',
            [
                'label' => esc_html__( 'Product Image', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-thumb-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ua-product-thumb-wrapper img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_margin',
            [
                'label'      => esc_html__( 'Margin Bottom', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 50 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 15 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-thumb-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Badges Style Section
     */
    /**
     * Badges Style Section
     */
    protected function style_badges_controls() {
        $this->start_controls_section(
            'style_badges_section',
            [
                'label' => esc_html__( 'Product Badges', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'badge_typography',
                'selector' => '{{WRAPPER}} .ua-product-badge',
            ]
        );

        $this->add_responsive_control(
            'badge_offset_h',
            [
                'label'      => esc_html__( 'Horizontal Offset', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 50 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-badge-pos-top-left, {{WRAPPER}} .ua-badge-pos-bottom-left'   => 'left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-badge-pos-top-right, {{WRAPPER}} .ua-badge-pos-bottom-right' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'badge_offset_v',
            [
                'label'      => esc_html__( 'Vertical Offset', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 50 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-badge-pos-top-left, {{WRAPPER}} .ua-badge-pos-top-right'       => 'top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-badge-pos-bottom-left, {{WRAPPER}} .ua-badge-pos-bottom-right' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'badge_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'badge_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', 'rem' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'badge_shadow',
                'selector' => '{{WRAPPER}} .ua-product-badge',
            ]
        );

        // 1. Sale Badge Style
        $this->add_control(
            'sale_badge_heading',
            [
                'label'     => esc_html__( 'Sale Badge', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'sale_badge_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-badge-sale' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'sale_badge_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ff385c',
                'selectors' => [
                    '{{WRAPPER}} .ua-badge-sale' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // 2. New Badge Style
        $this->add_control(
            'new_badge_heading',
            [
                'label'     => esc_html__( 'New Badge', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'new_badge_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-badge-new' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'new_badge_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0fc392',
                'selectors' => [
                    '{{WRAPPER}} .ua-badge-new' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // 3. Featured Badge Style
        $this->add_control(
            'featured_badge_heading',
            [
                'label'     => esc_html__( 'Featured Badge', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'featured_badge_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-badge-featured' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'featured_badge_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#6366f1',
                'selectors' => [
                    '{{WRAPPER}} .ua-badge-featured' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // 4. Hot / Best Seller Badge Style
        $this->add_control(
            'hot_badge_heading',
            [
                'label'     => esc_html__( 'HOT Badge', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'hot_badge_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-badge-hot' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hot_badge_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f59e0b',
                'selectors' => [
                    '{{WRAPPER}} .ua-badge-hot' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // 5. Sold Out Badge Style
        $this->add_control(
            'stock_badge_heading',
            [
                'label'     => esc_html__( 'Sold Out Badge', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'stock_badge_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-badge-out-of-stock' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'stock_badge_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#64748b',
                'selectors' => [
                    '{{WRAPPER}} .ua-badge-out-of-stock' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content Typography & Colors Style Section
     */
    protected function style_content_controls() {
        $this->start_controls_section(
            'style_content_section',
            [
                'label' => esc_html__( 'Title, Price & Meta', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Category
        $this->add_control(
            'heading_category_style',
            [
                'label' => esc_html__( 'Category', 'ultraaddons-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'category_typography',
                'selector' => '{{WRAPPER}} .ua-product-category a',
            ]
        );

        $this->add_control(
            'category_color',
            [
                'label'     => esc_html__( 'Category Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-product-category a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'category_hover_color',
            [
                'label'     => esc_html__( 'Category Hover Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-product-category a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Title
        $this->add_control(
            'heading_title_style',
            [
                'label'     => esc_html__( 'Product Title', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .ua-product-title, {{WRAPPER}} .ua-product-title a',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Title Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-product-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label'     => esc_html__( 'Title Hover Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-product-title a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label'      => esc_html__( 'Title Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', 'rem' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Rating
        $this->add_control(
            'heading_rating_style',
            [
                'label'     => esc_html__( 'Star Rating', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'star_color',
            [
                'label'     => esc_html__( 'Star Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f59e0b',
                'selectors' => [
                    '{{WRAPPER}} .star-rating span:before, {{WRAPPER}} .ua-product-rating .star-rating' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'empty_star_color',
            [
                'label'     => esc_html__( 'Empty Star Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#e2e8f0',
                'selectors' => [
                    '{{WRAPPER}} .star-rating:before' => 'color: {{VALUE}};',
                ],
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

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'price_typography',
                'selector' => '{{WRAPPER}} .ua-product-price, {{WRAPPER}} .ua-product-price .amount',
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label'     => esc_html__( 'Price Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-product-price, {{WRAPPER}} .ua-product-price .amount, {{WRAPPER}} .ua-product-price ins .amount' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'regular_price_color',
            [
                'label'     => esc_html__( 'Regular/Strikethrough Price Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-product-price del, {{WRAPPER}} .ua-product-price del .amount' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'price_margin',
            [
                'label'      => esc_html__( 'Price Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', 'rem' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Add to Cart Button Style Section
     */
    protected function style_cart_btn_controls() {
        $this->start_controls_section(
            'style_cart_btn_section',
            [
                'label' => esc_html__( 'Add to Cart Button', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'cart_btn_typography',
                'selector' => '{{WRAPPER}} .ua-btn-cart',
            ]
        );

        $this->start_controls_tabs( 'tabs_cart_btn' );

        $this->start_controls_tab(
            'tab_cart_btn_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'cart_btn_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-btn-cart' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cart_btn_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-btn-cart' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'cart_btn_border',
                'selector' => '{{WRAPPER}} .ua-btn-cart',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_cart_btn_hover',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'cart_btn_hover_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-btn-cart:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cart_btn_hover_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-btn-cart:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cart_btn_hover_border',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-btn-cart:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'cart_btn_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'separator'  => 'before',
                'selectors'  => [
                    '{{WRAPPER}} .ua-btn-cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'cart_btn_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', 'rem' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-btn-cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Action Icons Style Section
     */
    protected function style_action_icons_controls() {
        $this->start_controls_section(
            'style_action_icons_section',
            [
                'label' => esc_html__( 'Quick Action Icons', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'action_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range'      => [
                    'px' => [ 'min' => 10, 'max' => 40 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-action-btn' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'action_btn_box_size',
            [
                'label'      => esc_html__( 'Button Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range'      => [
                    'px' => [ 'min' => 24, 'max' => 60 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-action-btn' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_action_icons' );

        $this->start_controls_tab(
            'tab_action_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'action_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-action-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'action_icon_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-action-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_action_hover',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'action_icon_hover_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-action-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'action_icon_hover_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-action-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'action_btn_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'separator'  => 'before',
                'selectors'  => [
                    '{{WRAPPER}} .ua-action-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Filter Bar Style Section
     */
    protected function style_filter_bar_controls() {
        $this->start_controls_section(
            'style_filter_bar_section',
            [
                'label'     => esc_html__( 'Category Filter Bar', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_category_filter' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'filter_tab_typography',
                'selector' => '{{WRAPPER}} .ua-filter-btn',
            ]
        );

        $this->start_controls_tabs( 'tabs_filter_btn' );

        $this->start_controls_tab(
            'tab_filter_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'filter_btn_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-filter-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'filter_btn_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-filter-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_filter_active',
            [ 'label' => esc_html__( 'Active / Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'filter_btn_active_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-filter-btn.ua-active, {{WRAPPER}} .ua-filter-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'filter_btn_active_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-filter-btn.ua-active, {{WRAPPER}} .ua-filter-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'filter_btn_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'separator'  => 'before',
                'selectors'  => [
                    '{{WRAPPER}} .ua-filter-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'filter_btn_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', 'rem' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-filter-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'filter_bar_margin',
            [
                'label'      => esc_html__( 'Filter Bar Margin Bottom', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 60 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 30 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-products-filter-bar' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Pagination & Load More Style Section
     */
    protected function style_pagination_controls() {
        $this->start_controls_section(
            'style_pagination_section',
            [
                'label'     => esc_html__( 'Pagination & Load More', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'pagination_type!' => 'none',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'pagination_typography',
                'selector' => '{{WRAPPER}} .ua-load-more-btn, {{WRAPPER}} .ua-pagination a, {{WRAPPER}} .ua-pagination span',
            ]
        );

        $this->start_controls_tabs( 'tabs_pagination' );

        $this->start_controls_tab(
            'tab_pagination_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'pagination_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-load-more-btn, {{WRAPPER}} .ua-pagination a, {{WRAPPER}} .ua-pagination span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-load-more-btn, {{WRAPPER}} .ua-pagination a, {{WRAPPER}} .ua-pagination span' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'pagination_border',
                'selector' => '{{WRAPPER}} .ua-load-more-btn, {{WRAPPER}} .ua-pagination a, {{WRAPPER}} .ua-pagination span',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_pagination_active_hover',
            [ 'label' => esc_html__( 'Active / Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'pagination_hover_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-load-more-btn:hover, {{WRAPPER}} .ua-pagination a:hover, {{WRAPPER}} .ua-pagination .current' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_hover_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-load-more-btn:hover, {{WRAPPER}} .ua-pagination a:hover, {{WRAPPER}} .ua-pagination .current' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_hover_border',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-load-more-btn:hover, {{WRAPPER}} .ua-pagination a:hover, {{WRAPPER}} .ua-pagination .current' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'pagination_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'separator'  => 'before',
                'selectors'  => [
                    '{{WRAPPER}} .ua-load-more-btn, {{WRAPPER}} .ua-pagination a, {{WRAPPER}} .ua-pagination span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', 'rem' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-load-more-btn, {{WRAPPER}} .ua-pagination a, {{WRAPPER}} .ua-pagination span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Get Product Categories for Select2.
     *
     * @return array
     */
    public function get_product_categories() {
        $categories = get_terms( [
            'taxonomy'   => 'product_cat',
            'hide_empty' => true,
        ] );

        $options = [];
        if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
            foreach ( $categories as $category ) {
                $options[ $category->slug ] = $category->name;
            }
        }
        return $options;
    }

    /**
     * Get Product Tags for Select2.
     *
     * @return array
     */
    public function get_product_tags() {
        $tags = get_terms( [
            'taxonomy'   => 'product_tag',
            'hide_empty' => true,
        ] );

        $options = [];
        if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) {
            foreach ( $tags as $tag ) {
                $options[ $tag->slug ] = $tag->name;
            }
        }
        return $options;
    }

    /**
     * Build WP_Query args based on widget settings.
     *
     * @param array $settings
     * @param int $paged
     * @param string|null $category_override
     * @return array
     */
    public static function build_query_args( $settings, $paged = 1, $category_override = null ) {
        $query_type     = ! empty( $settings['query_type'] ) ? $settings['query_type'] : 'recent';
        $posts_per_page = ! empty( $settings['posts_per_page'] ) ? (int) $settings['posts_per_page'] : 8;
        $offset         = ! empty( $settings['offset'] ) ? (int) $settings['offset'] : 0;
        $orderby        = ! empty( $settings['orderby'] ) ? $settings['orderby'] : 'date';
        $order          = ! empty( $settings['order'] ) ? $settings['order'] : 'DESC';

        $args = [
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
            'posts_per_page'      => $posts_per_page,
            'paged'               => $paged,
            'tax_query'           => [ 'relation' => 'AND' ],
            'meta_query'          => [ 'relation' => 'AND' ],
        ];

        // Offset calculation
        if ( $offset > 0 ) {
            $args['offset'] = ( ( $paged - 1 ) * $posts_per_page ) + $offset;
        }

        // Exclude specific IDs
        if ( ! empty( $settings['exclude_ids'] ) ) {
            $exclude_ids = array_filter( array_map( 'trim', explode( ',', $settings['exclude_ids'] ) ) );
            if ( ! empty( $exclude_ids ) ) {
                $args['post__not_in'] = $exclude_ids;
            }
        }

        // Hide out of stock products
        if ( ! empty( $settings['hide_out_of_stock'] ) && 'yes' === $settings['hide_out_of_stock'] ) {
            $args['meta_query'][] = [
                'key'     => '_stock_status',
                'value'   => 'outofstock',
                'compare' => '!=',
            ];
        }

        // Query Source type
        switch ( $query_type ) {
            case 'best_selling':
                $args['meta_key'] = 'total_sales';
                $args['orderby']  = 'meta_value_num';
                $args['order']    = 'DESC';
                break;

            case 'top_rated':
                $args['meta_key'] = '_wc_average_rating';
                $args['orderby']  = 'meta_value_num';
                $args['order']    = 'DESC';
                break;

            case 'featured':
                $args['tax_query'][] = [
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured',
                    'operator' => 'IN',
                ];
                break;

            case 'on_sale':
                $product_ids_on_sale = function_exists( 'wc_get_product_ids_on_sale' ) ? wc_get_product_ids_on_sale() : [];
                $args['post__in'] = ! empty( $product_ids_on_sale ) ? $product_ids_on_sale : [ 0 ];
                break;

            case 'custom':
                if ( ! empty( $settings['product_ids'] ) ) {
                    $custom_ids = array_filter( array_map( 'trim', explode( ',', $settings['product_ids'] ) ) );
                    $args['post__in'] = ! empty( $custom_ids ) ? $custom_ids : [ 0 ];
                }
                break;

            case 'recent':
            default:
                $args['orderby'] = $orderby;
                $args['order']   = $order;
                break;
        }

        // Category filter
        $cat_slugs = ! empty( $category_override ) ? [ $category_override ] : ( ! empty( $settings['categories'] ) ? (array) $settings['categories'] : [] );
        if ( ! empty( $cat_slugs ) && ! in_array( 'all', $cat_slugs, true ) ) {
            $args['tax_query'][] = [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $cat_slugs,
                'operator' => 'IN',
            ];
        }

        // Tags filter
        if ( ! empty( $settings['tags'] ) ) {
            $args['tax_query'][] = [
                'taxonomy' => 'product_tag',
                'field'    => 'slug',
                'terms'    => (array) $settings['tags'],
                'operator' => 'IN',
            ];
        }

        if ( ! empty( $args['tax_query'] ) && count( $args['tax_query'] ) > 1 ) {
            $args['tax_query']['relation'] = 'AND';
        }

        return $args;
    }

    /**
     * Render widget output on frontend.
     */
    protected function render() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            echo '<p class="ua-wc-notice">' . esc_html__( 'Please install and activate WooCommerce to use this widget.', 'ultraaddons-elementor-lite' ) . '</p>';
            return;
        }

        wp_enqueue_style( 'ultraaddons-wc-products' );
        wp_enqueue_script( 'ultraaddons-wc-products' );

        wp_localize_script(
            'ultraaddons-wc-products',
            'uaWCProductsConfig',
            [
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'ua-wc-products-nonce' ),
                'i18n'     => [
                    'loading'       => esc_html__( 'Loading...', 'ultraaddons-elementor-lite' ),
                    'no_more'       => esc_html__( 'No more products to load', 'ultraaddons-elementor-lite' ),
                    'added_to_cart' => esc_html__( 'Added to cart!', 'ultraaddons-elementor-lite' ),
                    'view_cart'     => esc_html__( 'View Cart', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $settings = $this->get_settings_for_display();

        $query_args = self::build_query_args( $settings, 1 );
        $query      = new WP_Query( $query_args );

        $wrapper_classes = [
            'ua-wc-products-wrapper',
        ];

        $encoded_settings = htmlspecialchars( wp_json_encode( [
            'query_type'        => $settings['query_type'] ?? 'recent',
            'posts_per_page'    => $settings['posts_per_page'] ?? 8,
            'categories'        => $settings['categories'] ?? [],
            'tags'              => $settings['tags'] ?? [],
            'orderby'           => $settings['orderby'] ?? 'date',
            'order'             => $settings['order'] ?? 'DESC',
            'hide_out_of_stock' => $settings['hide_out_of_stock'] ?? 'no',
            'exclude_ids'       => $settings['exclude_ids'] ?? '',
            'product_ids'       => $settings['product_ids'] ?? '',
            'badge_position'             => $settings['badge_position'] ?? 'top-left',
            'badge_shape'                => $settings['badge_shape'] ?? 'rounded',
            'show_badge'                 => $settings['show_badge'] ?? 'no',
            'badge_type'                 => $settings['badge_type'] ?? 'percent',
            'sale_badge_custom_text'     => $settings['sale_badge_custom_text'] ?? 'SALE',
            'show_new_badge'             => $settings['show_new_badge'] ?? 'no',
            'new_badge_days'             => $settings['new_badge_days'] ?? 14,
            'new_badge_custom_text'      => $settings['new_badge_custom_text'] ?? 'NEW',
            'show_featured_badge'        => $settings['show_featured_badge'] ?? 'no',
            'featured_badge_custom_text' => $settings['featured_badge_custom_text'] ?? 'FEATURED',
            'show_hot_badge'             => $settings['show_hot_badge'] ?? 'no',
            'hot_badge_sales_min'        => $settings['hot_badge_sales_min'] ?? 10,
            'hot_badge_custom_text'      => $settings['hot_badge_custom_text'] ?? 'HOT',
            'show_stock_badge'           => $settings['show_stock_badge'] ?? 'yes',
            'stock_badge_custom_text'    => $settings['stock_badge_custom_text'] ?? 'Sold Out',
            'show_low_stock_badge'       => $settings['show_low_stock_badge'] ?? 'no',
            'low_stock_threshold'        => $settings['low_stock_threshold'] ?? 5,
            'show_secondary_image' => $settings['show_secondary_image'] ?? 'yes',
            'show_category'     => $settings['show_category'] ?? 'yes',
            'show_title'        => $settings['show_title'] ?? 'yes',
            'title_tag'         => $settings['title_tag'] ?? 'h3',
            'show_rating'       => $settings['show_rating'] ?? 'yes',
            'show_price'        => $settings['show_price'] ?? 'yes',
            'show_cart_btn'     => $settings['show_cart_btn'] ?? 'yes',
            'cart_btn_text'     => $settings['cart_btn_text'] ?? '',
        ] ), ENT_QUOTES, 'UTF-8' );
        ?>

        <div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>" data-settings="<?php echo $encoded_settings; ?>">

            <?php
            // 1. AJAX Category Filter Bar
            if ( ! empty( $settings['show_category_filter'] ) && 'yes' === $settings['show_category_filter'] ) {
                $this->render_filter_bar( $settings );
            }
            ?>

            <!-- Products Grid Container -->
            <div class="ua-wc-products-grid">
                <?php
                if ( $query->have_posts() ) {
                    while ( $query->have_posts() ) {
                        $query->the_post();
                        $product = wc_get_product( get_the_ID() );
                        if ( $product ) {
                            self::render_single_product_card( $product, $settings );
                        }
                    }
                    wp_reset_postdata();
                } else {
                    echo '<p class="ua-wc-no-products">' . esc_html__( 'No products found.', 'ultraaddons-elementor-lite' ) . '</p>';
                }
                ?>
            </div>

            <?php
            // 2. Pagination / Load More
            if ( ! empty( $settings['pagination_type'] ) && 'none' !== $settings['pagination_type'] && $query->max_num_pages > 1 ) {
                if ( 'load_more' === $settings['pagination_type'] ) {
                    ?>
                    <div class="ua-load-more-wrapper">
                        <button type="button" class="ua-load-more-btn" data-page="1" data-max-pages="<?php echo esc_attr( $query->max_num_pages ); ?>">
                            <span class="ua-btn-text"><?php echo esc_html( ! empty( $settings['load_more_text'] ) ? $settings['load_more_text'] : __( 'Load More Products', 'ultraaddons-elementor-lite' ) ); ?></span>
                            <span class="ua-spinner" style="display:none;"><i class="fas fa-spinner fa-spin"></i></span>
                        </button>
                    </div>
                    <?php
                } elseif ( 'numbers' === $settings['pagination_type'] ) {
                    $big = 999999999;
                    echo '<div class="ua-pagination">';
                    echo paginate_links( [
                        'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                        'format'    => '?paged=%#%',
                        'current'   => max( 1, get_query_var( 'paged' ) ),
                        'total'     => $query->max_num_pages,
                        'prev_text' => '<i class="fas fa-angle-left"></i>',
                        'next_text' => '<i class="fas fa-angle-right"></i>',
                    ] );
                    echo '</div>';
                }
            }
            ?>
        </div>
        <script>
        (function(){
            if (typeof jQuery === 'undefined') return;
            jQuery(function($){
                var $w = $('div[data-settings]').filter('.ua-wc-products-wrapper').last();
                if (!$w.length) return;
                if ($w.data('ua-filter-init')) return;
                $w.data('ua-filter-init', true);

                var $grid = $w.find('.ua-wc-products-grid');
                var $filterBtns = $w.find('.ua-filter-btn');
                if (!$filterBtns.length) return;

                var ajaxUrl = <?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>;
                var nonce = <?php echo wp_json_encode( wp_create_nonce( 'ua-wc-products-nonce' ) ); ?>;
                var rawSettings = $w.attr('data-settings');
                var settings = {};
                try { settings = JSON.parse(rawSettings); } catch(e) {}

                $filterBtns.on('click', function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    var $btn = $(this);
                    if ($btn.hasClass('ua-active') || $grid.hasClass('ua-loading')) return;
                    $filterBtns.removeClass('ua-active');
                    $btn.addClass('ua-active');
                    var cat = $btn.attr('data-cat') || 'all';
                    $grid.addClass('ua-loading');
                    $.post(ajaxUrl, {
                        action: 'ua_wc_products_load_more',
                        nonce: nonce,
                        page: 1,
                        category: cat,
                        settings: JSON.stringify(settings)
                    }, function(res){
                        $grid.removeClass('ua-loading');
                        if (res.success && res.data.html) {
                            $grid.html(res.data.html);
                        } else {
                            $grid.html('<p class="ua-wc-no-products">No products found.</p>');
                        }
                    }).fail(function(){ $grid.removeClass('ua-loading'); });
                });
            });
        })();
        </script>
        <?php
    }

    /**
     * Render Category Filter Bar
     *
     * @param array $settings
     */
    protected function render_filter_bar( $settings ) {
        // 1. Use dedicated filter_categories if set
        $categories = ! empty( $settings['filter_categories'] ) ? (array) $settings['filter_categories'] : [];

        // 2. Fallback to query categories
        if ( empty( $categories ) && ! empty( $settings['categories'] ) ) {
            $categories = (array) $settings['categories'];
        }

        // 3. Auto-detect from products (max 6)
        if ( empty( $categories ) ) {
            $all_terms = get_terms( [
                'taxonomy'   => 'product_cat',
                'hide_empty' => true,
                'number'     => 6,
                'exclude'    => get_option( 'default_product_cat', 0 ),
            ] );
            if ( ! empty( $all_terms ) && ! is_wp_error( $all_terms ) ) {
                $categories = wp_list_pluck( $all_terms, 'slug' );
            }
        }

        if ( empty( $categories ) ) {
            return;
        }

        $show_count = ! empty( $settings['show_filter_count'] ) && 'yes' === $settings['show_filter_count'];
        $all_label  = ! empty( $settings['filter_all_label'] ) ? $settings['filter_all_label'] : esc_html__( 'All Products', 'ultraaddons-elementor-lite' );

        $all_count_html = '';
        if ( $show_count ) {
            $count_obj = wp_count_posts( 'product' );
            $total_published = isset( $count_obj->publish ) ? (int) $count_obj->publish : 0;
            $all_count_html = '<span class="ua-filter-count">' . $total_published . '</span>';
        }
        ?>
        <div class="ua-products-filter-bar">
            <button type="button" class="ua-filter-btn ua-active" data-cat="all">
                <span class="ua-filter-text"><?php echo esc_html( $all_label ); ?></span>
                <?php echo $all_count_html; ?>
            </button>
            <?php
            foreach ( $categories as $cat_slug ) {
                $term = get_term_by( 'slug', $cat_slug, 'product_cat' );
                if ( $term ) {
                    $term_count_html = $show_count ? '<span class="ua-filter-count">' . (int) $term->count . '</span>' : '';
                    ?>
                    <button type="button" class="ua-filter-btn" data-cat="<?php echo esc_attr( $term->slug ); ?>">
                        <span class="ua-filter-text"><?php echo esc_html( $term->name ); ?></span>
                        <?php echo $term_count_html; ?>
                    </button>
                    <?php
                }
            }
            ?>
        </div>
        <?php
    }

    /**
     * Render Single Product Card (Used in Render & AJAX calls)
     *
     * @param \WC_Product $product
     * @param array $settings
     */
    public static function render_single_product_card( $product, $settings = [] ) {
        if ( ! is_a( $product, 'WC_Product' ) ) {
            return;
        }

        $product_id   = $product->get_id();
        $permalink    = $product->get_permalink();
        $title        = $product->get_name();
        $price_html   = $product->get_price_html();
        $rating_html  = wc_get_rating_html( $product->get_average_rating() );
        $is_on_sale   = $product->is_on_sale();
        $is_in_stock  = $product->is_in_stock();

        $badge_position   = ! empty( $settings['badge_position'] ) ? sanitize_html_class( $settings['badge_position'] ) : 'top-left';
        $badge_shape      = ! empty( $settings['badge_shape'] ) ? sanitize_html_class( $settings['badge_shape'] ) : 'rounded';

        $show_badge       = ! empty( $settings['show_badge'] ) && 'yes' === $settings['show_badge'];
        $badge_type       = ! empty( $settings['badge_type'] ) ? $settings['badge_type'] : 'percent';
        $sale_custom_text = ! empty( $settings['sale_badge_custom_text'] ) ? $settings['sale_badge_custom_text'] : esc_html__( 'SALE', 'ultraaddons-elementor-lite' );

        $show_new_badge   = ! empty( $settings['show_new_badge'] ) && 'yes' === $settings['show_new_badge'];
        $new_badge_days   = ! empty( $settings['new_badge_days'] ) ? (int) $settings['new_badge_days'] : 14;
        $new_custom_text  = ! empty( $settings['new_badge_custom_text'] ) ? $settings['new_badge_custom_text'] : esc_html__( 'NEW', 'ultraaddons-elementor-lite' );

        $show_featured    = ! empty( $settings['show_featured_badge'] ) && 'yes' === $settings['show_featured_badge'];
        $featured_text    = ! empty( $settings['featured_badge_custom_text'] ) ? $settings['featured_badge_custom_text'] : esc_html__( 'FEATURED', 'ultraaddons-elementor-lite' );

        $show_hot         = ! empty( $settings['show_hot_badge'] ) && 'yes' === $settings['show_hot_badge'];
        $hot_sales_min    = ! empty( $settings['hot_badge_sales_min'] ) ? (int) $settings['hot_badge_sales_min'] : 10;
        $hot_text         = ! empty( $settings['hot_badge_custom_text'] ) ? $settings['hot_badge_custom_text'] : esc_html__( 'HOT', 'ultraaddons-elementor-lite' );

        $show_stock_badge     = ! empty( $settings['show_stock_badge'] ) && 'yes' === $settings['show_stock_badge'];
        $stock_text           = ! empty( $settings['stock_badge_custom_text'] ) ? $settings['stock_badge_custom_text'] : esc_html__( 'Sold Out', 'ultraaddons-elementor-lite' );

        $show_low_stock_badge = ! empty( $settings['show_low_stock_badge'] ) && 'yes' === $settings['show_low_stock_badge'];
        $low_stock_threshold  = ! empty( $settings['low_stock_threshold'] ) ? (int) $settings['low_stock_threshold'] : 5;

        $show_sec_image       = ! empty( $settings['show_secondary_image'] ) && 'yes' === $settings['show_secondary_image'];
        $show_category    = ! empty( $settings['show_category'] ) && 'yes' === $settings['show_category'];
        $show_title       = ! empty( $settings['show_title'] ) && 'yes' === $settings['show_title'];
        $title_tag        = ! empty( $settings['title_tag'] ) ? sanitize_key( $settings['title_tag'] ) : 'h3';
        $show_rating      = ! empty( $settings['show_rating'] ) && 'yes' === $settings['show_rating'];
        $show_price       = ! empty( $settings['show_price'] ) && 'yes' === $settings['show_price'];
        $show_cart_btn    = ! empty( $settings['show_cart_btn'] ) && 'yes' === $settings['show_cart_btn'];

        // Secondary Gallery Image for hover flip
        $gallery_image_ids = $product->get_gallery_image_ids();
        $secondary_image_id = ( $show_sec_image && ! empty( $gallery_image_ids ) ) ? $gallery_image_ids[0] : 0;

        // Determine if product is "New"
        $is_new = false;
        if ( $show_new_badge ) {
            $created_time = strtotime( $product->get_date_created() );
            if ( ( time() - $created_time ) < ( $new_badge_days * DAY_IN_SECONDS ) ) {
                $is_new = true;
            }
        }

        // Determine if product is "Hot" (based on sales)
        $is_hot = false;
        if ( $show_hot ) {
            $total_sales = (int) get_post_meta( $product_id, 'total_sales', true );
            if ( $total_sales >= $hot_sales_min ) {
                $is_hot = true;
            }
        }
        
        $regular    = (float) $product->get_regular_price();
        $sale       = (float) $product->get_sale_price();

        // Variable Product Price Calculation
        if ( $product->is_type( 'variable' ) ) {
            $prices = $product->get_variation_prices( true );
            if ( ! empty( $prices['regular_price'] ) && ! empty( $prices['sale_price'] ) ) {
                $max_reg  = (float) max( $prices['regular_price'] );
                $min_sale = (float) min( $prices['sale_price'] );
                if ( $max_reg > 0 && $min_sale < $max_reg ) {
                    $regular = $max_reg;
                    $sale    = $min_sale;
                }
            }
        }

        $has_real_discount = ( $regular > 0 && $sale > 0 && $sale < $regular );

        // Determine if product is "Low Stock"
        $is_low_stock = false;
        $stock_qty = $product->get_stock_quantity();
        if ( $show_low_stock_badge && $is_in_stock && ! is_null( $stock_qty ) && $stock_qty <= $low_stock_threshold ) {
            $is_low_stock = true;
        }

        $badge_container_classes = [
            'ua-product-badges',
            'ua-badge-pos-' . $badge_position,
            'ua-badge-shape-' . $badge_shape,
        ];
        ?>
        <div class="ua-wc-product-card product type-product status-publish has-post-thumbnail" data-product-id="<?php echo esc_attr( $product_id ); ?>">
            
            <!-- Thumbnail & Badges & Action Bar -->
            <div class="ua-product-thumb-wrapper <?php echo $secondary_image_id ? 'ua-has-secondary-image' : ''; ?>">
                
                <!-- Badges -->
                <div class="<?php echo esc_attr( implode( ' ', $badge_container_classes ) ); ?>">
                    <?php if ( ! $is_in_stock && $show_stock_badge ) : ?>
                        <span class="ua-product-badge ua-badge-out-of-stock"><?php echo esc_html( $stock_text ); ?></span>
                    <?php elseif ( $is_on_sale && $show_badge ) : ?>
                        <span class="ua-product-badge ua-badge-sale">
                            <?php
                            if ( $has_real_discount ) {
                                $percentage = round( ( ( $regular - $sale ) / $regular ) * 100 );
                                if ( 'percent' === $badge_type && $percentage > 0 ) {
                                    echo '-' . esc_html( $percentage ) . '%';
                                } elseif ( 'percent_off' === $badge_type && $percentage > 0 ) {
                                    echo esc_html( $percentage ) . '% ' . esc_html__( 'OFF', 'ultraaddons-elementor-lite' );
                                } elseif ( 'save_amount' === $badge_type ) {
                                    echo esc_html__( 'SAVE ', 'ultraaddons-elementor-lite' ) . wc_price( $regular - $sale );
                                } else {
                                    echo esc_html( $sale_custom_text );
                                }
                            } else {
                                echo esc_html( $sale_custom_text );
                            }
                            ?>
                        </span>
                    <?php endif; ?>

                    <?php if ( $is_new ) : ?>
                        <span class="ua-product-badge ua-badge-new"><?php echo esc_html( $new_custom_text ); ?></span>
                    <?php endif; ?>

                    <?php if ( $show_featured && $product->is_featured() ) : ?>
                        <span class="ua-product-badge ua-badge-featured"><?php echo esc_html( $featured_text ); ?></span>
                    <?php endif; ?>

                    <?php if ( $is_hot ) : ?>
                        <span class="ua-product-badge ua-badge-hot"><?php echo esc_html( $hot_text ); ?></span>
                    <?php endif; ?>
                    
                    <?php if ( $is_low_stock ) : ?>
                        <span class="ua-product-badge ua-badge-low-stock"><?php echo sprintf( '%s %d %s', esc_html__( 'Only', 'ultraaddons-elementor-lite' ), (int) $stock_qty, esc_html__( 'Left!', 'ultraaddons-elementor-lite' ) ); ?></span>
                    <?php endif; ?>
                </div>

                <!-- Product Image Link -->
                <a href="<?php echo esc_url( $permalink ); ?>" class="ua-product-img-link" tabindex="-1">
                    <?php
                    $primary_image = $product->get_image( 'woocommerce_thumbnail', [ 'class' => 'ua-primary-thumb' ] );
                    echo $primary_image ? $primary_image : wc_placeholder_img( 'woocommerce_thumbnail' );

                    if ( $secondary_image_id ) {
                        echo wp_get_attachment_image( $secondary_image_id, 'woocommerce_thumbnail', false, [ 'class' => 'ua-secondary-thumb' ] );
                    }
                    ?>
                </a>

            </div>

            <!-- Content Details -->
            <div class="ua-product-content-wrapper">
                
                <div class="ua-product-header">
                    <?php if ( $show_category ) : ?>
                        <div class="ua-product-category">
                            <?php
                            $categories = wc_get_product_category_list( $product_id, ', ' );
                            if ( $categories ) {
                                echo wp_kses_post( $categories );
                            }
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( $show_title ) : ?>
                        <<?php echo esc_attr( $title_tag ); ?> class="ua-product-title">
                            <a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a>
                        </<?php echo esc_attr( $title_tag ); ?>>
                    <?php endif; ?>

                    <?php if ( $show_rating && ! empty( $rating_html ) ) : ?>
                        <div class="ua-product-rating">
                            <?php echo wp_kses_post( $rating_html ); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Product Footer: Price & Cart Button -->
                <?php if ( ( $show_price && ! empty( $price_html ) ) || $show_cart_btn ) : ?>
                    <div class="ua-product-footer">
                        <?php if ( $show_price && ! empty( $price_html ) ) : ?>
                            <div class="ua-product-price">
                                <?php echo wp_kses_post( $price_html ); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ( $show_cart_btn ) : ?>
                            <div class="ua-product-cart-btn-wrapper">
                                <?php
                                $custom_btn_text = ! empty( $settings['cart_btn_text'] ) ? $settings['cart_btn_text'] : '';
                                self::render_add_to_cart_button( $product, $custom_btn_text );
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div>

        </div>
        <?php
    }

    /**
     * Render standard AJAX Add to Cart button for different product types.
     *
     * @param \WC_Product $product
     * @param string $custom_text
     */
    public static function render_add_to_cart_button( $product, $custom_text = '' ) {
        if ( ! is_a( $product, 'WC_Product' ) ) {
            return;
        }

        $product_id = $product->get_id();
        $button_text = ! empty( $custom_text ) ? $custom_text : $product->add_to_cart_text();
        $button_url  = $product->add_to_cart_url();

        $classes = [
            'ua-btn-cart',
            'button',
            'product_type_' . $product->get_type(),
        ];

        if ( $product->is_purchasable() && $product->is_in_stock() ) {
            if ( $product->supports( 'ajax_add_to_cart' ) ) {
                $classes[] = 'add_to_cart_button';
                $classes[] = 'ajax_add_to_cart';
            }
        }

        $attributes = [
            'data-product_id'  => $product_id,
            'data-product_sku' => $product->get_sku(),
            'data-quantity'    => 1,
            'rel'              => 'nofollow',
            'class'            => implode( ' ', array_map( 'sanitize_html_class', $classes ) ),
        ];

        $attr_string = '';
        foreach ( $attributes as $key => $val ) {
            $attr_string .= ' ' . esc_attr( $key ) . '="' . esc_attr( $val ) . '"';
        }

        echo sprintf(
            '<a href="%s"%s><span class="ua-cart-icon"><i class="fas fa-shopping-basket"></i></span> <span class="ua-cart-text">%s</span></a>',
            esc_url( $button_url ),
            $attr_string,
            esc_html( $button_text )
        );
    }

    /**
     * AJAX Handler for Load More & Category Filter
     */
    public static function ajax_load_products() {
        $nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
        if ( ! empty( $nonce ) && ! wp_verify_nonce( $nonce, 'ua-wc-products-nonce' ) ) {
            wp_send_json_error( [ 'html' => '<p class="ua-wc-notice">' . esc_html__( 'Security token expired. Please refresh the page.', 'ultraaddons-elementor-lite' ) . '</p>' ] );
        }

        $page     = isset( $_POST['page'] ) ? max( 1, (int) $_POST['page'] ) : 1;
        $category = isset( $_POST['category'] ) ? sanitize_text_field( $_POST['category'] ) : '';
        
        $settings = [];
        if ( isset( $_POST['settings'] ) ) {
            if ( is_array( $_POST['settings'] ) ) {
                $settings = $_POST['settings'];
            } else {
                $settings = json_decode( wp_unslash( $_POST['settings'] ), true );
            }
        }
        if ( ! is_array( $settings ) ) {
            $settings = [];
        }

        $query_args = self::build_query_args( $settings, $page, ( 'all' !== $category && ! empty( $category ) ) ? $category : null );
        $query      = new WP_Query( $query_args );

        ob_start();

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $product = wc_get_product( get_the_ID() );
                if ( $product ) {
                    self::render_single_product_card( $product, $settings );
                }
            }
            wp_reset_postdata();
        }

        $html = ob_get_clean();

        wp_send_json_success( [
            'html'      => $html,
            'max_pages' => $query->max_num_pages,
            'count'     => $query->post_count,
        ] );
    }

}

// Hook AJAX Actions
add_action( 'wp_ajax_ua_wc_products_load_more', [ '\UltraAddons\Widget\WC_Products', 'ajax_load_products' ] );
add_action( 'wp_ajax_nopriv_ua_wc_products_load_more', [ '\UltraAddons\Widget\WC_Products', 'ajax_load_products' ] );
