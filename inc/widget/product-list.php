<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * UltraAddons Woo Product List Widget
 * 
 * Displays WooCommerce products in clean, responsive horizontal card layouts
 * with 3 Layout Presets, 3 Badge Presets, Total Sold Progress Meter,
 * Static & On-Hover Button placements, AJAX Load More & Infinite Scroll,
 * and built-in View Product (Quick View) modal popup.
 * 
 * @since 2.0.3
 * @package UltraAddons
 */
class Product_List extends Base {

    /**
     * Constructor — Register assets
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        wp_register_style(
            'ultraaddons-product-list',
            ULTRA_ADDONS_ASSETS . 'css/widgets/product-list.css',
            [],
            ULTRA_ADDONS_VERSION,
            'all'
        );
        wp_enqueue_style( 'ultraaddons-product-list' );

        wp_register_script(
            'ultraaddons-product-list',
            ULTRA_ADDONS_ASSETS . 'js/frontend-product-list.js',
            [ 'jquery' ],
            ULTRA_ADDONS_VERSION,
            true
        );
        wp_localize_script(
            'ultraaddons-product-list',
            'uaProductListConfig',
            [
                'ajax_url'   => admin_url( 'admin-ajax.php' ),
                'nonce'      => wp_create_nonce( 'ua-product-list-nonce' ),
                'i18n'       => [
                    'loading'      => esc_html__( 'Loading...', 'ultraaddons-elementor-lite' ),
                    'no_more'      => esc_html__( 'No more products to load', 'ultraaddons-elementor-lite' ),
                    'added_cart'   => esc_html__( 'Added to Cart!', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );
        wp_enqueue_script( 'ultraaddons-product-list' );
    }

    public function get_style_depends() {
        return [ 'ultraaddons-product-list', 'font-awesome-5-all' ];
    }

    public function get_script_depends() {
        return [ 'jquery', 'ultraaddons-product-list' ];
    }

    public function get_keywords() {
        return [ 'ultraaddons', 'ua', 'woo', 'product', 'product list', 'woocommerce', 'shop', 'store', 'list view', 'products', 'view product' ];
    }

    /**
     * Register Widget Controls
     */
    protected function register_controls() {
        // CONTENT TAB
        $this->content_layout_controls();
        $this->content_query_controls();
        $this->content_image_controls();
        $this->content_settings_controls();
        $this->content_load_more_controls();

        // STYLE TAB
        $this->style_container_controls();
        $this->style_item_controls();
        $this->style_image_controls();
        $this->style_content_controls();
        $this->style_color_typography_controls();
        $this->style_load_more_controls();
        $this->style_quick_view_controls();
    }

    /*==========================================================================
     * CONTENT TAB — Layout
     *========================================================================*/
    protected function content_layout_controls() {
        $this->start_controls_section(
            '_ua_section_layout',
            [
                'label' => esc_html__( 'Layout', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            '_ua_layout',
            [
                'label'   => esc_html__( 'Layout', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'preset-1',
                'options' => [
                    'preset-1' => esc_html__( 'Preset 1 (Classic List)', 'ultraaddons-elementor-lite' ),
                    'preset-2' => esc_html__( 'Preset 2 (Ribbon Card)', 'ultraaddons-elementor-lite' ),
                    'preset-3' => esc_html__( 'Preset 3 (Compact Inline)', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            '_ua_heading_pagination',
            [
                'label'     => esc_html__( 'General', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            '_ua_pagination_type',
            [
                'label'   => esc_html__( 'Load More', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'none',
                'options' => [
                    'none'      => [
                        'title' => esc_html__( 'Disable', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-ban',
                    ],
                    'load_more' => [
                        'title' => esc_html__( 'Button', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-button',
                    ],
                    'infinite'  => [
                        'title' => esc_html__( 'Infinity Scroll', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-image-box',
                    ],
                ],
            ]
        );

        $this->add_control(
            '_ua_infinite_scroll_offset',
            [
                'label'     => esc_html__( 'Scroll Offset (px)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => -200,
                'condition' => [
                    '_ua_pagination_type' => 'infinite',
                ],
            ]
        );

        $this->add_control(
            '_ua_heading_header_elements',
            [
                'label'     => esc_html__( 'Content Header', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            '_ua_show_badge',
            [
                'label'        => esc_html__( 'Badge', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            '_ua_show_rating',
            [
                'label'        => esc_html__( 'Rating', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            '_ua_show_review_count',
            [
                'label'        => esc_html__( 'Review Count', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    '_ua_show_rating' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_show_category',
            [
                'label'        => esc_html__( 'Category', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            '_ua_heading_body_elements',
            [
                'label'     => esc_html__( 'Content Body', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            '_ua_show_title',
            [
                'label'        => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            '_ua_show_excerpt',
            [
                'label'        => esc_html__( 'Excerpt', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            '_ua_show_price',
            [
                'label'        => esc_html__( 'Price', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            '_ua_heading_footer_elements',
            [
                'label'     => esc_html__( 'Content Footer', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            '_ua_show_total_sold',
            [
                'label'        => esc_html__( 'Total Sold', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => '',
                'condition'    => [
                    '_ua_layout' => 'preset-1',
                ],
            ]
        );

        $this->add_control(
            '_ua_show_total_sold_preset_2_3',
            [
                'label'        => esc_html__( 'Total Sold', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    '_ua_layout!' => 'preset-1',
                ],
            ]
        );

        $this->add_control(
            '_ua_show_add_to_cart',
            [
                'label'        => esc_html__( 'Add to Cart', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            '_ua_show_quick_view',
            [
                'label'        => esc_html__( 'View Product', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            '_ua_show_link_btn',
            [
                'label'        => esc_html__( 'Link', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * CONTENT TAB — Query
     *========================================================================*/
    protected function content_query_controls() {
        $this->start_controls_section(
            '_ua_section_query',
            [
                'label' => esc_html__( 'Query', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            '_ua_query_filter',
            [
                'label'   => esc_html__( 'Filter By', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'recent',
                'options' => [
                    'recent'       => esc_html__( 'Recent Products', 'ultraaddons-elementor-lite' ),
                    'featured'     => esc_html__( 'Featured Products', 'ultraaddons-elementor-lite' ),
                    'best_selling' => esc_html__( 'Best Selling Products', 'ultraaddons-elementor-lite' ),
                    'sale'         => esc_html__( 'Sale Products', 'ultraaddons-elementor-lite' ),
                    'top_rated'    => esc_html__( 'Top Rated Products', 'ultraaddons-elementor-lite' ),
                    'manual'       => esc_html__( 'Manual Selection', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            '_ua_categories',
            [
                'label'       => esc_html__( 'Categories', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT2,
                'multiple'    => true,
                'options'     => $this->get_product_categories(),
                'label_block' => true,
                'condition'   => [
                    '_ua_query_filter!' => 'manual',
                ],
            ]
        );

        $this->add_control(
            '_ua_manual_product_ids',
            [
                'label'       => esc_html__( 'Select Products (IDs)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'e.g. 12, 45, 89', 'ultraaddons-elementor-lite' ),
                'label_block' => true,
                'condition'   => [
                    '_ua_query_filter' => 'manual',
                ],
            ]
        );

        $this->add_control(
            '_ua_posts_per_page',
            [
                'label'   => esc_html__( 'Count', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 4,
                'min'     => 1,
                'max'     => 1000,
                'step'    => 1,
            ]
        );

        $this->add_control(
            '_ua_offset',
            [
                'label'   => esc_html__( 'Offset', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 0,
                'min'     => 0,
                'max'     => 100,
                'condition' => [
                    '_ua_query_filter!' => 'manual',
                ],
            ]
        );

        $this->add_control(
            '_ua_orderby',
            [
                'label'   => esc_html__( 'Order By', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'date'       => esc_html__( 'Date', 'ultraaddons-elementor-lite' ),
                    'title'      => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                    'price'      => esc_html__( 'Price', 'ultraaddons-elementor-lite' ),
                    'sku'        => esc_html__( 'SKU', 'ultraaddons-elementor-lite' ),
                    'rand'       => esc_html__( 'Random', 'ultraaddons-elementor-lite' ),
                    'menu_order' => esc_html__( 'Menu Order', 'ultraaddons-elementor-lite' ),
                    'ID'         => esc_html__( 'ID', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    '_ua_query_filter!' => [ 'best_selling', 'top_rated' ],
                ],
            ]
        );

        $this->add_control(
            '_ua_order',
            [
                'label'   => esc_html__( 'Order', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'desc',
                'options' => [
                    'asc'  => [
                        'title' => esc_html__( 'Ascending', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-arrow-up',
                    ],
                    'desc' => [
                        'title' => esc_html__( 'Descending', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-arrow-down',
                    ],
                ],
            ]
        );

        $this->add_control(
            '_ua_show_out_of_stock',
            [
                'label'        => esc_html__( 'Out of Stock', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            '_ua_user_order_filter',
            [
                'label'   => esc_html__( 'Product Type (Logged-in User)', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'all',
                'options' => [
                    'all'           => esc_html__( 'Both', 'ultraaddons-elementor-lite' ),
                    'purchased'     => esc_html__( 'Purchased', 'ultraaddons-elementor-lite' ),
                    'not_purchased' => esc_html__( 'Not Purchased', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * CONTENT TAB — Image
     *========================================================================*/
    protected function content_image_controls() {
        $this->start_controls_section(
            '_ua_section_image',
            [
                'label' => esc_html__( 'Image', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'        => '_ua_image_size',
                'default'     => 'medium',
                'label_block' => true,
            ]
        );

        $this->add_control(
            '_ua_image_clickable',
            [
                'label'        => esc_html__( 'Clickable', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $this->add_control(
            '_ua_image_alignment',
            [
                'label'   => esc_html__( 'Image Alignment', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'left',
                'options' => [
                    'left'  => [
                        'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * CONTENT TAB — Content Settings
     *========================================================================*/
    protected function content_settings_controls() {
        $this->start_controls_section(
            '_ua_section_content_settings',
            [
                'label' => esc_html__( 'Content', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            '_ua_heading_content_general',
            [
                'label' => esc_html__( 'General', 'ultraaddons-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            '_ua_button_position',
            [
                'label'   => esc_html__( 'Button Position', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'both',
                'options' => [
                    'both'     => esc_html__( 'Both', 'ultraaddons-elementor-lite' ),
                    'static'   => esc_html__( 'Static', 'ultraaddons-elementor-lite' ),
                    'on_hover' => esc_html__( 'On Hover', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            '_ua_heading_header_settings',
            [
                'label'     => esc_html__( 'Content Header', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            '_ua_header_position',
            [
                'label'   => esc_html__( 'Position', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'before_title',
                'options' => [
                    'before_title' => [
                        'title' => esc_html__( 'Before Title', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'after_title'  => [
                        'title' => esc_html__( 'After Title', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ],
                ],
                'condition' => [
                    '_ua_layout' => 'preset-1',
                ],
            ]
        );

        $this->add_control(
            '_ua_header_position_preset_2_3',
            [
                'label'   => esc_html__( 'Position', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'after_title',
                'options' => [
                    'before_title' => [
                        'title' => esc_html__( 'Before Title', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'after_title'  => [
                        'title' => esc_html__( 'After Title', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ],
                ],
                'condition' => [
                    '_ua_layout!' => 'preset-1',
                ],
            ]
        );

        $this->add_control(
            '_ua_header_direction',
            [
                'label'   => esc_html__( 'Direction', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'ltr',
                'options' => [
                    'ltr' => [
                        'title' => esc_html__( 'Left to Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'rtl' => [
                        'title' => esc_html__( 'Right to Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
            ]
        );

        $this->add_control(
            '_ua_badge_heading',
            [
                'label'     => esc_html__( 'Badge', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'condition' => [
                    '_ua_show_badge' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_badge_preset',
            [
                'label'     => esc_html__( 'Preset', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'badge_1',
                'options'   => [
                    'badge_1' => esc_html__( 'Preset 1 (Starburst SVG)', 'ultraaddons-elementor-lite' ),
                    'badge_2' => esc_html__( 'Preset 2 (Corner Ribbon)', 'ultraaddons-elementor-lite' ),
                    'badge_3' => esc_html__( 'Preset 3 (Rounded Pill)', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    '_ua_show_badge' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_badge_alignment',
            [
                'label'     => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => 'left',
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
                'condition' => [
                    '_ua_show_badge' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_badge_sale_text',
            [
                'label'     => esc_html__( 'Sale Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Sale', 'ultraaddons-elementor-lite' ),
                'dynamic'   => [ 'active' => true ],
                'condition' => [
                    '_ua_show_badge' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_badge_stockout_text',
            [
                'label'     => esc_html__( 'Stock Out Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Stock Out', 'ultraaddons-elementor-lite' ),
                'dynamic'   => [ 'active' => true ],
                'condition' => [
                    '_ua_show_badge' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_heading_body_settings',
            [
                'label'     => esc_html__( 'Content Body', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            '_ua_title_tag',
            [
                'label'   => esc_html__( 'Title Tag', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'h2',
                'options' => [
                    'h1'   => [ 'title' => 'H1', 'icon' => 'eicon-editor-h1' ],
                    'h2'   => [ 'title' => 'H2', 'icon' => 'eicon-editor-h2' ],
                    'h3'   => [ 'title' => 'H3', 'icon' => 'eicon-editor-h3' ],
                    'h4'   => [ 'title' => 'H4', 'icon' => 'eicon-editor-h4' ],
                    'h5'   => [ 'title' => 'H5', 'icon' => 'eicon-editor-h5' ],
                    'h6'   => [ 'title' => 'H6', 'icon' => 'eicon-editor-h6' ],
                    'div'  => [ 'title' => 'div', 'icon' => 'eicon-editor-paragraph' ],
                    'span' => [ 'title' => 'span', 'icon' => 'eicon-editor-paragraph' ],
                    'p'    => [ 'title' => 'p', 'icon' => 'eicon-editor-paragraph' ],
                ],
                'condition' => [
                    '_ua_show_title' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_title_clickable',
            [
                'label'        => esc_html__( 'Title Clickable', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    '_ua_show_title' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_excerpt_words',
            [
                'label'     => esc_html__( 'Excerpt Words Count', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 30,
                'min'       => 5,
                'max'       => 200,
                'condition' => [
                    '_ua_show_excerpt' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_excerpt_indicator',
            [
                'label'     => esc_html__( 'Expansion Indicator', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => '...',
                'condition' => [
                    '_ua_show_excerpt' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_heading_footer_settings',
            [
                'label'     => esc_html__( 'Content Footer', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            '_ua_total_sold_remaining_show',
            [
                'label'        => esc_html__( 'Remaining (Show / Hide)', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'conditions'   => [
                    'relation' => 'or',
                    'terms'    => [
                        [
                            'name'     => '_ua_show_total_sold',
                            'operator' => '===',
                            'value'    => 'yes',
                        ],
                        [
                            'name'     => '_ua_show_total_sold_preset_2_3',
                            'operator' => '===',
                            'value'    => 'yes',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            '_ua_total_sold_text',
            [
                'label'      => esc_html__( 'Total Sold Text', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::TEXT,
                'default'    => esc_html__( 'Total Sold:', 'ultraaddons-elementor-lite' ),
                'dynamic'    => [ 'active' => true ],
                'conditions' => [
                    'relation' => 'or',
                    'terms'    => [
                        [
                            'name'     => '_ua_show_total_sold',
                            'operator' => '===',
                            'value'    => 'yes',
                        ],
                        [
                            'name'     => '_ua_show_total_sold_preset_2_3',
                            'operator' => '===',
                            'value'    => 'yes',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            '_ua_remaining_text',
            [
                'label'      => esc_html__( 'Remaining Text', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::TEXT,
                'default'    => esc_html__( 'Remaining:', 'ultraaddons-elementor-lite' ),
                'dynamic'    => [ 'active' => true ],
                'conditions' => [
                    'relation' => 'and',
                    'terms'    => [
                        [
                            'name'     => '_ua_total_sold_remaining_show',
                            'operator' => '===',
                            'value'    => 'yes',
                        ],
                        [
                            'relation' => 'or',
                            'terms'    => [
                                [
                                    'name'     => '_ua_show_total_sold',
                                    'operator' => '===',
                                    'value'    => 'yes',
                                ],
                                [
                                    'name'     => '_ua_show_total_sold_preset_2_3',
                                    'operator' => '===',
                                    'value'    => 'yes',
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            '_ua_cart_custom_text_show',
            [
                'label'        => esc_html__( 'Add to Cart Custom Text', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => '',
                'condition'    => [
                    '_ua_show_add_to_cart' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_cart_simple_text',
            [
                'label'     => esc_html__( 'Simple Product Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Buy Now', 'ultraaddons-elementor-lite' ),
                'dynamic'   => [ 'active' => true ],
                'condition' => [
                    '_ua_show_add_to_cart'      => 'yes',
                    '_ua_cart_custom_text_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_cart_variable_text',
            [
                'label'     => esc_html__( 'Variable Product Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Select options', 'ultraaddons-elementor-lite' ),
                'dynamic'   => [ 'active' => true ],
                'condition' => [
                    '_ua_show_add_to_cart'      => 'yes',
                    '_ua_cart_custom_text_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_cart_grouped_text',
            [
                'label'     => esc_html__( 'Grouped Product Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'View products', 'ultraaddons-elementor-lite' ),
                'dynamic'   => [ 'active' => true ],
                'condition' => [
                    '_ua_show_add_to_cart'      => 'yes',
                    '_ua_cart_custom_text_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_cart_external_text',
            [
                'label'     => esc_html__( 'External Product Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Buy Now', 'ultraaddons-elementor-lite' ),
                'dynamic'   => [ 'active' => true ],
                'condition' => [
                    '_ua_show_add_to_cart'      => 'yes',
                    '_ua_cart_custom_text_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_quick_view_text',
            [
                'label'     => esc_html__( 'View Product Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'View Product', 'ultraaddons-elementor-lite' ),
                'dynamic'   => [ 'active' => true ],
                'condition' => [
                    '_ua_show_quick_view' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_not_found_text',
            [
                'label'     => esc_html__( 'Products Not Found Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'No products found!', 'ultraaddons-elementor-lite' ),
                'dynamic'   => [ 'active' => true ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * CONTENT TAB — Load More
     *========================================================================*/
    protected function content_load_more_controls() {
        $this->start_controls_section(
            '_ua_section_load_more_content',
            [
                'label'     => esc_html__( 'Load More', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    '_ua_pagination_type' => 'load_more',
                ],
            ]
        );

        $this->add_control(
            '_ua_load_more_btn_text',
            [
                'label'   => esc_html__( 'Button Text', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Load More', 'ultraaddons-elementor-lite' ),
                'dynamic' => [ 'active' => true ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Container
     *========================================================================*/
    protected function style_container_controls() {
        $this->start_controls_section(
            '_ua_section_style_container',
            [
                'label' => esc_html__( 'Container Style', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            '_ua_container_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-list-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_container_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default'    => [
                    'top'    => 60,
                    'right'  => 60,
                    'bottom' => 60,
                    'left'   => 60,
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-list-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_container_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
                'default'    => [ 'size' => 10, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-list-container' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_container_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#F4F5F7',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-list-container' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => '_ua_container_border',
                'selector' => '{{WRAPPER}} .ua-product-list-container',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => '_ua_container_shadow',
                'selector' => '{{WRAPPER}} .ua-product-list-container',
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Product Item Card
     *========================================================================*/
    protected function style_item_controls() {
        $this->start_controls_section(
            '_ua_section_style_item',
            [
                'label' => esc_html__( 'Item Style', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            '_ua_item_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default'    => [
                    'top'    => 30,
                    'right'  => 0,
                    'bottom' => 0,
                    'left'   => 0,
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-list-item:not(:first-child)' => 'margin-top: {{TOP}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_item_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default'    => [
                    'top'    => 64,
                    'right'  => 64,
                    'bottom' => 64,
                    'left'   => 64,
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-list-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_item_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-list-item' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_item_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-product-list-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => '_ua_item_border',
                'selector' => '{{WRAPPER}} .ua-product-list-item',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => '_ua_item_shadow',
                'selector' => '{{WRAPPER}} .ua-product-list-item',
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Thumbnail / Image Wrap
     *========================================================================*/
    protected function style_image_controls() {
        $this->start_controls_section(
            '_ua_section_style_image',
            [
                'label' => esc_html__( 'Image Style', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            '_ua_image_wrap_width',
            [
                'label'      => esc_html__( 'Image Wrapper Width (%)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range'      => [
                    '%' => [ 'min' => 0, 'max' => 100 ],
                ],
                'default'    => [ 'size' => 30, 'unit' => '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-list-image-wrap'   => 'width: {{SIZE}}{{UNIT}}; flex: 0 0 {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-product-list-content-wrap' => 'width: calc(100% - {{SIZE}}{{UNIT}}); flex: 0 0 calc(100% - {{SIZE}}{{UNIT}}); max-width: calc(100% - {{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->add_control(
            '_ua_image_overlay_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-product-list-image-wrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_image_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-list-image-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_image_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-list-image-wrap' => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-product-list-image-wrap img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Content Area
     *========================================================================*/
    protected function style_content_controls() {
        $this->start_controls_section(
            '_ua_section_style_content_wrap',
            [
                'label' => esc_html__( 'Content Style', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            '_ua_content_wrapper_width',
            [
                'label'      => esc_html__( 'Content Wrapper Width (%)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range'      => [
                    '%' => [ 'min' => 0, 'max' => 100 ],
                ],
                'default'    => [ 'size' => 70, 'unit' => '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-list-content-wrap' => 'width: {{SIZE}}{{UNIT}}; flex: 0 0 {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_content_padding',
            [
                'label'      => esc_html__( 'Content Wrapper Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default'    => [
                    'top'    => 0,
                    'right'  => 0,
                    'bottom' => 0,
                    'left'   => 70,
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-list-content-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Color & Typography (All Elements)
     *========================================================================*/
    protected function style_color_typography_controls() {
        $this->start_controls_section(
            '_ua_section_style_typography',
            [
                'label' => esc_html__( 'Color & Typography', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // --- BADGE ---
        $this->add_control(
            '_ua_heading_style_badge',
            [
                'label'     => esc_html__( 'Badge', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'condition' => [
                    '_ua_show_badge' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs( '_ua_tabs_badge_style' );

        $this->start_controls_tab(
            '_ua_tab_badge_sale',
            [
                'label'     => esc_html__( 'Sale', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    '_ua_show_badge' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_badge_sale_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-product-list-wrapper .ua-product-list-badge-wrap.is-on-sale p' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-product-list-wrapper .ua-product-list-badge-wrap.is-on-sale.badge-preset-3' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    '_ua_show_badge' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_badge_sale_bg',
            [
                'label'     => esc_html__( 'Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-product-list-wrapper .ua-product-list-badge-wrap.badge-preset-2.is-on-sale p' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .ua-product-list-wrapper .ua-product-list-badge-wrap.badge-preset-3.is-on-sale'   => 'background: {{VALUE}};',
                    '{{WRAPPER}} .ua-product-list-wrapper .ua-product-list-badge-wrap.is-on-sale svg path'         => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .ua-product-list-wrapper .ua-product-list-badge-wrap.badge-preset-2.is-on-sale.badge-alignment-left::after'  => 'border-right: 10px solid {{VALUE}}; filter: brightness(0.7);',
                    '{{WRAPPER}} .ua-product-list-wrapper .ua-product-list-badge-wrap.badge-preset-2.is-on-sale::before'                      => 'border-bottom: 10px solid {{VALUE}}; filter: brightness(0.7);',
                    '{{WRAPPER}} .ua-product-list-wrapper .ua-product-list-badge-wrap.badge-preset-2.is-on-sale.badge-alignment-right::after' => 'border-left: 10px solid {{VALUE}}; filter: brightness(0.7);',
                ],
                'condition' => [
                    '_ua_show_badge' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => '_ua_badge_sale_typography',
                'selector'  => '{{WRAPPER}} .ua-product-list-wrapper .ua-product-list-badge-wrap.is-on-sale p',
                'condition' => [
                    '_ua_show_badge'    => 'yes',
                    '_ua_badge_preset!' => 'badge_2',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_ua_tab_badge_stockout',
            [
                'label'     => esc_html__( 'Stock Out', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    '_ua_show_badge' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_badge_stockout_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-product-list-wrapper .ua-product-list-badge-wrap.stock-out p' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    '_ua_show_badge' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_badge_stockout_bg',
            [
                'label'     => esc_html__( 'Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-product-list-wrapper .ua-product-list-badge-wrap.badge-preset-2.stock-out' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .ua-product-list-wrapper .ua-product-list-badge-wrap.badge-preset-3.stock-out' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .ua-product-list-wrapper .ua-product-list-badge-wrap.stock-out svg path'       => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    '_ua_show_badge' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => '_ua_badge_stockout_typography',
                'selector'  => '{{WRAPPER}} .ua-product-list-wrapper .ua-product-list-badge-wrap.stock-out p',
                'condition' => [
                    '_ua_show_badge' => 'yes',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
            '_ua_badge_size',
            [
                'label'      => esc_html__( 'Badge Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'max' => 500,
                    ],
                ],
                'default'    => [
                    'size' => 100,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-list-wrapper .ua-product-list-badge-wrap.badge-preset-1 .ua-product-list-badge-bg svg' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
                    '{{WRAPPER}} .ua-product-list-wrapper .ua-product-list-badge-wrap.badge-preset-1' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
                ],
                'condition'  => [
                    '_ua_show_badge'    => 'yes',
                    '_ua_badge_preset!' => 'badge_2',
                ],
                'separator'  => 'after',
            ]
        );

        // --- RATING & REVIEWS ---
        $this->add_control(
            '_ua_heading_style_rating',
            [
                'label' => esc_html__( 'Star Rating & Reviews', 'ultraaddons-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            '_ua_rating_star_color',
            [
                'label'     => esc_html__( 'Rating Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#FF9900',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-list-rating .star-rating' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-product-list-rating .star-rating span::before, {{WRAPPER}} .ua-product-list-rating .star-rating::before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_rating_star_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 10, 'max' => 50 ] ],
                'default'    => [ 'size' => 15, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-list-rating .star-rating' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_review_count_color',
            [
                'label'     => esc_html__( 'Review Count Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#5F6368',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-list-review-count' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => '_ua_review_count_typography',
                'selector' => '{{WRAPPER}} .ua-product-list-review-count',
            ]
        );

        // --- CATEGORY ---
        $this->add_control(
            '_ua_heading_style_category',
            [
                'label'     => esc_html__( 'Category', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            '_ua_category_color',
            [
                'label'     => esc_html__( 'Category Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-product-list-category, {{WRAPPER}} .ua-product-list-category p, {{WRAPPER}} .ua-product-list-category i, {{WRAPPER}} .ua-product-list-category svg, {{WRAPPER}} .ua-product-list-notice p, {{WRAPPER}} .ua-product-list-notice p i' => 'color: {{VALUE}}; fill: {{VALUE}}; stroke: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => '_ua_category_typography',
                'selector' => '{{WRAPPER}} .ua-product-list-category, {{WRAPPER}} .ua-product-list-category p, {{WRAPPER}} .ua-product-list-notice p',
            ]
        );

        // --- TITLE ---
        $this->add_control(
            '_ua_heading_style_title',
            [
                'label'     => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs( '_ua_tabs_title_style' );

        $this->start_controls_tab(
            '_ua_tab_title_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            '_ua_title_color',
            [
                'label'     => esc_html__( 'Title Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#343434',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-list-title, {{WRAPPER}} .ua-product-list-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_ua_tab_title_hover',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            '_ua_title_hover_color',
            [
                'label'     => esc_html__( 'Title Hover Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-product-list-title a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => '_ua_title_typography',
                'selector' => '{{WRAPPER}} .ua-product-list-title, {{WRAPPER}} .ua-product-list-title a',
            ]
        );

        // --- EXCERPT ---
        $this->add_control(
            '_ua_heading_style_excerpt',
            [
                'label'     => esc_html__( 'Excerpt', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            '_ua_excerpt_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#5F6368',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-list-excerpt' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => '_ua_excerpt_typography',
                'selector' => '{{WRAPPER}} .ua-product-list-excerpt',
            ]
        );

        // --- PRICE ---
        $this->add_control(
            '_ua_heading_style_price',
            [
                'label'     => esc_html__( 'Price', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            '_ua_regular_price_color',
            [
                'label'     => esc_html__( 'Regular Price Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#757C86',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-list-price del, {{WRAPPER}} .ua-product-list-price del .amount' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => '_ua_regular_price_typography',
                'label'    => esc_html__( 'Regular Price Typography', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-product-list-price del',
            ]
        );

        $this->add_control(
            '_ua_price_color',
            [
                'label'     => esc_html__( 'Sale Price Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#343434',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-list-price, {{WRAPPER}} .ua-product-list-price ins, {{WRAPPER}} .ua-product-list-price .amount' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => '_ua_price_typography',
                'label'    => esc_html__( 'Sale Price Typography', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-product-list-price, {{WRAPPER}} .ua-product-list-price ins, {{WRAPPER}} .ua-product-list-price .amount',
            ]
        );

        // --- TOTAL SOLD PROGRESS METER ---
        $this->add_control(
            '_ua_heading_style_total_sold',
            [
                'label'     => esc_html__( 'Total Sold', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            '_ua_total_sold_label_color',
            [
                'label'     => esc_html__( 'Total Sold Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#515151',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-list-progress .ua-product-list-progress-info h4, {{WRAPPER}} .ua-product-list-progress .ua-product-list-progress-info .ua-product-list-progress-count, {{WRAPPER}} .ua-product-list-progress-info h4' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_total_sold_count_color',
            [
                'label'     => esc_html__( 'Total Sold Number Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#343434',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-list-progress .ua-product-list-progress-info h4 span, {{WRAPPER}} .ua-product-list-progress .ua-product-list-progress-info .ua-product-list-progress-count span, {{WRAPPER}} .ua-product-list-progress-info h4 span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_remaining_label_color',
            [
                'label'     => esc_html__( 'Remaining Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#515151',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-list-progress .ua-product-list-progress-info h4.ua-product-list-progress-remaining, {{WRAPPER}} .ua-product-list-progress .ua-product-list-progress-remaining' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_remaining_count_color',
            [
                'label'     => esc_html__( 'Remaining Number Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#343434',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-list-progress .ua-product-list-progress-info h4.ua-product-list-progress-remaining span, {{WRAPPER}} .ua-product-list-progress .ua-product-list-progress-remaining span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_progress_outer_bg',
            [
                'label'     => esc_html__( 'Progress Bar Outer Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#EFE4E4',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-list-progress .ua-product-list-progress-bar-outer, {{WRAPPER}} .ua-product-list-progress-bar-outer' => 'background: {{VALUE}} !important; background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            '_ua_progress_inner_bg',
            [
                'label'     => esc_html__( 'Progress Bar Inner Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#C29F9D',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-list-progress .ua-product-list-progress-bar-inner, {{WRAPPER}} .ua-product-list-progress-bar-inner' => 'background: {{VALUE}} !important; background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_progress_bar_height',
            [
                'label'      => esc_html__( 'Progress Bar Height', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 2, 'max' => 50 ] ],
                'default'    => [ 'size' => 3, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-list-progress .ua-product-list-progress-bar-outer, {{WRAPPER}} .ua-product-list-progress .ua-product-list-progress-bar-inner, {{WRAPPER}} .ua-product-list-progress-bar-outer, {{WRAPPER}} .ua-product-list-progress-bar-inner' => 'height: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_progress_bar_radius',
            [
                'label'      => esc_html__( 'Progress Bar Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
                'default'    => [ 'size' => 100, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-list-progress .ua-product-list-progress-bar-outer, {{WRAPPER}} .ua-product-list-progress .ua-product-list-progress-bar-inner, {{WRAPPER}} .ua-product-list-progress-bar-outer, {{WRAPPER}} .ua-product-list-progress-bar-inner' => 'border-radius: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => '_ua_total_sold_typography',
                'selector' => '{{WRAPPER}} .ua-product-list-progress .ua-product-list-progress-info h4, {{WRAPPER}} .ua-product-list-progress .ua-product-list-progress-info .ua-product-list-progress-count, {{WRAPPER}} .ua-product-list-progress .ua-product-list-progress-info .ua-product-list-progress-remaining',
            ]
        );

        // --- STATIC FOOTER BUTTONS ---
        $this->add_control(
            '_ua_heading_style_footer_buttons',
            [
                'label'     => esc_html__( 'Add to Cart Button (Static)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs( '_ua_tabs_static_cart_style' );

        $this->start_controls_tab(
            '_ua_tab_static_cart_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            '_ua_static_cart_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-list-add-to-cart-btn a, {{WRAPPER}} .ua-product-list-buttons .ua-product-list-add-to-cart-button a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_static_cart_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-product-list-add-to-cart-btn a, {{WRAPPER}} .ua-product-list-buttons .ua-product-list-add-to-cart-button a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_ua_tab_static_cart_hover',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            '_ua_static_cart_hover_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-list-add-to-cart-btn a:hover, {{WRAPPER}} .ua-product-list-buttons .ua-product-list-add-to-cart-button a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_static_cart_hover_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-list-add-to-cart-btn a:hover, {{WRAPPER}} .ua-product-list-buttons .ua-product-list-add-to-cart-button a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => '_ua_static_cart_typography',
                'selector' => '{{WRAPPER}} .ua-product-list-add-to-cart-btn a, {{WRAPPER}} .ua-product-list-buttons .ua-product-list-add-to-cart-button a',
            ]
        );

        // --- VIEW PRODUCT (STATIC) ---
        $this->add_control(
            '_ua_heading_style_qv_static',
            [
                'label'     => esc_html__( 'View Product Button (Static)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs( '_ua_tabs_static_qv_style' );

        $this->start_controls_tab(
            '_ua_tab_static_qv_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            '_ua_static_qv_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#515151',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-list-quick-view-btn a, {{WRAPPER}} .ua-product-list-buttons .ua-product-list-quick-view-button a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_ua_tab_static_qv_hover',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            '_ua_static_qv_hover_color',
            [
                'label'     => esc_html__( 'Hover Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-product-list-quick-view-btn a:hover, {{WRAPPER}} .ua-product-list-buttons .ua-product-list-quick-view-button a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => '_ua_static_qv_typography',
                'selector' => '{{WRAPPER}} .ua-product-list-quick-view-btn a, {{WRAPPER}} .ua-product-list-buttons .ua-product-list-quick-view-button a',
            ]
        );

        // --- ON-HOVER THUMBNAIL BUTTONS ---
        $this->add_control(
            '_ua_heading_style_hover_buttons',
            [
                'label'     => esc_html__( 'On Hover Buttons', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs( '_ua_tabs_hover_action_icons' );

        $this->start_controls_tab(
            '_ua_tab_hover_icon_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            '_ua_hover_icon_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#343434',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-list-buttons-on-hover li a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_hover_icon_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-product-list-buttons-on-hover li a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_ua_tab_hover_icon_active',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            '_ua_hover_icon_active_color',
            [
                'label'     => esc_html__( 'Hover Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-product-list-buttons-on-hover li a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_hover_icon_active_bg',
            [
                'label'     => esc_html__( 'Hover Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-product-list-buttons-on-hover li a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Load More Button
     *========================================================================*/
    protected function style_load_more_controls() {
        $this->start_controls_section(
            '_ua_section_style_load_more',
            [
                'label'     => esc_html__( 'Load More Button', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    '_ua_pagination_type' => 'load_more',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_load_more_align',
            [
                'label'     => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => 'center',
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
                'selectors' => [
                    '{{WRAPPER}} .ua-load-more-wrap' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => '_ua_load_more_typography',
                'selector' => '{{WRAPPER}} .ua-load-more-btn',
            ]
        );

        $this->start_controls_tabs( '_ua_tabs_load_more_btn' );

        $this->start_controls_tab(
            '_ua_tab_load_more_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            '_ua_load_more_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-load-more-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_load_more_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#29d8d8',
                'selectors' => [
                    '{{WRAPPER}} .ua-load-more-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => '_ua_load_more_border',
                'selector' => '{{WRAPPER}} .ua-load-more-btn',
            ]
        );

        $this->add_responsive_control(
            '_ua_load_more_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-load-more-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => '_ua_load_more_shadow',
                'selector' => '{{WRAPPER}} .ua-load-more-btn',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_ua_tab_load_more_hover',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            '_ua_load_more_hover_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-load-more-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_load_more_hover_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#27bdbd',
                'selectors' => [
                    '{{WRAPPER}} .ua-load-more-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_load_more_hover_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-load-more-btn:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
            '_ua_load_more_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'top'    => 12,
                    'right'  => 28,
                    'bottom' => 12,
                    'left'   => 28,
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-load-more-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Quick View / View Product Popup
     *========================================================================*/
    protected function style_quick_view_controls() {
        $this->start_controls_section(
            '_ua_section_style_quick_view',
            [
                'label' => esc_html__( 'Popup Style', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            '_ua_qv_modal_bg',
            [
                'label'     => esc_html__( 'Modal Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-quick-view-dialog, {{WRAPPER}}.ua-quick-view-modal .ua-quick-view-dialog, .ua-modal-{{ID}} .ua-quick-view-dialog, .ua-quick-view-modal .ua-quick-view-dialog' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_qv_modal_radius',
            [
                'label'      => esc_html__( 'Modal Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'    => [
                    'top'    => 16,
                    'right'  => 16,
                    'bottom' => 16,
                    'left'   => 16,
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-quick-view-dialog, {{WRAPPER}}.ua-quick-view-modal .ua-quick-view-dialog, .ua-modal-{{ID}} .ua-quick-view-dialog, .ua-quick-view-modal .ua-quick-view-dialog' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => '_ua_qv_modal_box_shadow',
                'selector' => '{{WRAPPER}} .ua-quick-view-dialog, {{WRAPPER}}.ua-quick-view-modal .ua-quick-view-dialog, .ua-modal-{{ID}} .ua-quick-view-dialog, .ua-quick-view-modal .ua-quick-view-dialog',
            ]
        );

        // --- CLOSE BUTTON ---
        $this->add_control(
            '_ua_qv_close_btn_heading',
            [
                'label'     => esc_html__( 'Close Button', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs( '_ua_qv_close_btn_tabs' );

        $this->start_controls_tab(
            '_ua_qv_close_btn_tab_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            '_ua_qv_close_btn_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .ua-quick-view-close, {{WRAPPER}}.ua-quick-view-modal .ua-quick-view-close, .ua-modal-{{ID}} .ua-quick-view-close, .ua-quick-view-modal .ua-quick-view-close' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            '_ua_qv_close_btn_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-quick-view-close, {{WRAPPER}}.ua-quick-view-modal .ua-quick-view-close, .ua-modal-{{ID}} .ua-quick-view-close, .ua-quick-view-modal .ua-quick-view-close' => 'background: {{VALUE}} !important; background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_ua_qv_close_btn_tab_hover',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            '_ua_qv_close_btn_hover_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .ua-quick-view-close:hover, {{WRAPPER}}.ua-quick-view-modal .ua-quick-view-close:hover, .ua-modal-{{ID}} .ua-quick-view-close:hover, .ua-quick-view-modal .ua-quick-view-close:hover' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            '_ua_qv_close_btn_hover_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f1f5f9',
                'selectors' => [
                    '{{WRAPPER}} .ua-quick-view-close:hover, {{WRAPPER}}.ua-quick-view-modal .ua-quick-view-close:hover, .ua-modal-{{ID}} .ua-quick-view-close:hover, .ua-quick-view-modal .ua-quick-view-close:hover' => 'background: {{VALUE}} !important; background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            '_ua_qv_close_btn_font_size',
            [
                'label'      => esc_html__( 'Icon / Font Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 10, 'max' => 50 ] ],
                'default'    => [ 'size' => 20, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-quick-view-close, {{WRAPPER}}.ua-quick-view-modal .ua-quick-view-close, .ua-modal-{{ID}} .ua-quick-view-close, .ua-quick-view-modal .ua-quick-view-close' => 'font-size: {{SIZE}}{{UNIT}} !important; line-height: 1 !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_qv_close_btn_dimension',
            [
                'label'      => esc_html__( 'Button Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 20, 'max' => 80 ] ],
                'default'    => [ 'size' => 40, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-quick-view-close, {{WRAPPER}}.ua-quick-view-modal .ua-quick-view-close, .ua-modal-{{ID}} .ua-quick-view-close, .ua-quick-view-modal .ua-quick-view-close' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important; min-height: {{SIZE}}{{UNIT}} !important; max-width: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_qv_close_btn_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'default'    => [ 'size' => 50, 'unit' => '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-quick-view-close, {{WRAPPER}}.ua-quick-view-modal .ua-quick-view-close, .ua-modal-{{ID}} .ua-quick-view-close, .ua-quick-view-modal .ua-quick-view-close' => 'border-radius: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => '_ua_qv_close_btn_box_shadow',
                'selector' => '{{WRAPPER}} .ua-quick-view-close, {{WRAPPER}}.ua-quick-view-modal .ua-quick-view-close, .ua-modal-{{ID}} .ua-quick-view-close, .ua-quick-view-modal .ua-quick-view-close',
            ]
        );

        // --- ADD TO CART BUTTON IN MODAL ---
        $this->add_control(
            '_ua_qv_cart_btn_heading',
            [
                'label'     => esc_html__( 'Add to Cart Button', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            '_ua_qv_btn_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button, {{WRAPPER}}.ua-quick-view-modal .single_add_to_cart_button, .ua-modal-{{ID}} .single_add_to_cart_button, .ua-quick-view-modal .single_add_to_cart_button' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            '_ua_qv_btn_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button, {{WRAPPER}}.ua-quick-view-modal .single_add_to_cart_button, .ua-modal-{{ID}} .single_add_to_cart_button, .ua-quick-view-modal .single_add_to_cart_button' => 'background-color: {{VALUE}} !important; background: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * RENDER METHODS
     *========================================================================*/

    /**
     * Get Product Categories for Select2 option
     */
    protected function get_product_categories() {
        $options = [];
        if ( taxonomy_exists( 'product_cat' ) ) {
            $categories = get_terms( [
                'taxonomy'   => 'product_cat',
                'hide_empty' => true,
            ] );
            if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
                foreach ( $categories as $category ) {
                    $options[ $category->slug ] = $category->name . ' (' . $category->count . ')';
                }
            }
        }
        return $options;
    }

    /**
     * Build WP_Query args from widget settings
     */
    public static function build_query_args( $settings, $paged = 1 ) {
        $filter         = ! empty( $settings['_ua_query_filter'] ) ? $settings['_ua_query_filter'] : 'recent';
        $posts_per_page = ! empty( $settings['_ua_posts_per_page'] ) ? intval( $settings['_ua_posts_per_page'] ) : 4;
        $offset         = ! empty( $settings['_ua_offset'] ) ? intval( $settings['_ua_offset'] ) : 0;
        $orderby        = ! empty( $settings['_ua_orderby'] ) ? $settings['_ua_orderby'] : 'date';
        $order          = ! empty( $settings['_ua_order'] ) ? strtoupper( $settings['_ua_order'] ) : 'DESC';

        // Calculate offset with pagination
        $calculated_offset = $offset + ( ( $paged - 1 ) * $posts_per_page );

        $args = [
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => $posts_per_page,
            'offset'         => $calculated_offset,
            'order'          => $order,
        ];

        // Stock Visibility
        if ( empty( $settings['_ua_show_out_of_stock'] ) || $settings['_ua_show_out_of_stock'] !== 'yes' ) {
            $args['meta_query'][] = [
                'key'     => '_stock_status',
                'value'   => 'instock',
                'compare' => '=',
            ];
        }

        // Filter: Categories
        if ( ! empty( $settings['_ua_categories'] ) && $filter !== 'manual' ) {
            $args['tax_query'][] = [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => (array) $settings['_ua_categories'],
                'operator' => 'IN',
            ];
        }

        // Filter Specific Types
        switch ( $filter ) {
            case 'featured':
                $args['tax_query'][] = [
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured',
                    'operator' => 'IN',
                ];
                break;

            case 'best_selling':
                $args['meta_key'] = 'total_sales';
                $args['orderby']  = 'meta_value_num';
                $args['order']    = 'DESC';
                break;

            case 'sale':
                $sale_ids = function_exists( 'wc_get_product_ids_on_sale' ) ? wc_get_product_ids_on_sale() : [];
                $args['post__in'] = ! empty( $sale_ids ) ? $sale_ids : [ 0 ];
                break;

            case 'top_rated':
                $args['meta_key'] = '_wc_average_rating';
                $args['orderby']  = 'meta_value_num';
                $args['order']    = 'DESC';
                break;

            case 'manual':
                if ( ! empty( $settings['_ua_manual_product_ids'] ) ) {
                    $ids = array_map( 'trim', explode( ',', $settings['_ua_manual_product_ids'] ) );
                    $args['post__in'] = array_filter( array_map( 'intval', $ids ) );
                }
                break;

            default: // 'recent'
                if ( $orderby === 'price' ) {
                    $args['meta_key'] = '_price';
                    $args['orderby']  = 'meta_value_num';
                } elseif ( $orderby === 'sku' ) {
                    $args['meta_key'] = '_sku';
                    $args['orderby']  = 'meta_value';
                } else {
                    $args['orderby']  = $orderby;
                }
                break;
        }

        // Customer Purchase Filter (Logged-in users)
        if ( ! empty( $settings['_ua_user_order_filter'] ) && $settings['_ua_user_order_filter'] !== 'all' && is_user_logged_in() ) {
            $user_id = get_current_user_id();
            $customer_orders = wc_get_orders( [
                'customer_id' => $user_id,
                'status'      => [ 'wc-completed', 'wc-processing' ],
                'limit'       => -1,
            ] );

            $ordered_product_ids = [];
            foreach ( $customer_orders as $order_obj ) {
                foreach ( $order_obj->get_items() as $item ) {
                    $ordered_product_ids[] = $item->get_product_id();
                }
            }
            $ordered_product_ids = array_unique( $ordered_product_ids );

            if ( $settings['_ua_user_order_filter'] === 'purchased' ) {
                $args['post__in'] = ! empty( $ordered_product_ids ) ? $ordered_product_ids : [ 0 ];
            } elseif ( $settings['_ua_user_order_filter'] === 'not_purchased' && ! empty( $ordered_product_ids ) ) {
                $args['post__not_in'] = $ordered_product_ids;
            }
        }

        return $args;
    }

    /**
     * Render Starburst SVG Path (Exact 24-point Polygon)
     */
    public static function render_starburst_svg() {
        return '<div class="ua-product-list-badge-bg"><svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100" fill="none"><path d="M50 0L59.861 13.1982L75 6.69873L76.9408 23.0592L93.3013 25L86.8018 40.139L100 50L86.8018 59.861L93.3013 75L76.9408 76.9408L75 93.3013L59.861 86.8018L50 100L40.139 86.8018L25 93.3013L23.0592 76.9408L6.69873 75L13.1982 59.861L0 50L13.1982 40.139L6.69873 25L23.0592 23.0592L25 6.69873L40.139 13.1982L50 0Z" fill="#DBEC73"/></svg></div>';
    }

    /**
     * Render Badge
     */
    protected function render_badge( $product, $settings ) {
        if ( empty( $settings['_ua_show_badge'] ) || $settings['_ua_show_badge'] !== 'yes' ) {
            return;
        }

        $is_in_stock = $product->is_in_stock();
        $is_on_sale  = $product->is_on_sale();

        if ( ! $is_on_sale && $is_in_stock ) {
            return; // No badge needed
        }

        $layout    = ! empty( $settings['_ua_layout'] ) ? $settings['_ua_layout'] : 'preset-1';
        $preset    = ! empty( $settings['_ua_badge_preset'] ) ? $settings['_ua_badge_preset'] : ( $layout === 'preset-2' ? 'badge_2' : 'badge_1' );
        $alignment = ! empty( $settings['_ua_badge_alignment'] ) ? $settings['_ua_badge_alignment'] : 'left';

        $badge_type  = ! $is_in_stock ? 'ua-badge-stockout stock-out' : 'ua-badge-sale is-on-sale';
        $badge_text  = ! $is_in_stock ? ( ! empty( $settings['_ua_badge_stockout_text'] ) ? $settings['_ua_badge_stockout_text'] : __( 'Stock Out', 'ultraaddons-elementor-lite' ) )
                                      : ( ! empty( $settings['_ua_badge_sale_text'] ) ? $settings['_ua_badge_sale_text'] : __( 'Sale', 'ultraaddons-elementor-lite' ) );

        $preset_num  = str_replace( 'badge_', '', $preset ); // 1, 2, 3
        $badge_classes = [
            'ua-product-list-badge-wrap',
            'ua-product-list-badge',
            'badge-preset-' . $preset_num,
            'ua-badge-preset-' . $preset_num,
            'ua-badge-' . $preset_num,
            'badge-alignment-' . $alignment,
            'ua-badge-align-' . $alignment,
            $badge_type,
        ];

        ?>
        <div class="<?php echo esc_attr( implode( ' ', $badge_classes ) ); ?>">
            <?php if ( $preset === 'badge_1' ) : ?>
                <?php echo self::render_starburst_svg(); ?>
                <p><strong><?php echo esc_html( $badge_text ); ?></strong></p>
            <?php elseif ( $preset === 'badge_2' ) : ?>
                <p><?php echo esc_html( $badge_text ); ?></p>
            <?php else : ?>
                <p><?php echo esc_html( $badge_text ); ?></p>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Render Star Rating & Reviews
     */
    protected function render_rating( $product, $settings ) {
        if ( empty( $settings['_ua_show_rating'] ) || $settings['_ua_show_rating'] !== 'yes' ) {
            return;
        }

        $rating_count = $product->get_rating_count();
        $review_count = $product->get_review_count();
        $average      = $product->get_average_rating();

        ?>
        <div class="ua-product-list-rating">
            <?php
            if ( $average > 0 ) {
                echo wc_get_rating_html( $average, $rating_count );
            } else {
                echo '<div class="star-rating" role="img" aria-label="' . esc_attr__( 'Rated 0 out of 5', 'ultraaddons-elementor-lite' ) . '"><span style="width:0%"></span></div>';
            }
            ?>
            <?php if ( ! empty( $settings['_ua_show_review_count'] ) && $settings['_ua_show_review_count'] === 'yes' && $review_count > 0 ) : ?>
                <a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>#reviews" class="woocommerce-review-link ua-product-list-review-count" rel="nofollow">(<?php echo esc_html( $review_count ); ?>)</a>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Render Category Notice
     */
    protected function render_category( $product, $settings ) {
        $show_cat = isset( $settings['_ua_show_category'] ) ? $settings['_ua_show_category'] === 'yes' : true;
        if ( ! $show_cat ) {
            return;
        }

        $category_name = self::get_product_category_name( $product->get_id() );
        if ( empty( $category_name ) ) {
            return;
        }

        ?>
        <div class="ua-product-list-notice ua-product-list-notice-category">
            <p>
                <i class="fas fa-box" aria-hidden="true"></i>
                <?php echo esc_html( $category_name ); ?>
            </p>
        </div>
        <?php
    }

    /**
     * Get Product Category Name (excluding uncategorized)
     */
    public static function get_product_category_name( $product_id ) {
        $terms = get_the_terms( $product_id, 'product_cat' );
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                if ( 'uncategorized' !== $term->slug ) {
                    return $term->name;
                }
            }
        }
        return '';
    }

    /**
     * Get Product Image Size setting value
     */
    public static function get_product_image_size( $settings ) {
        if ( ! empty( $settings['_ua_image_size_size'] ) ) {
            $size = $settings['_ua_image_size_size'];
            if ( 'custom' === $size && ! empty( $settings['_ua_image_size_custom_dimension'] ) ) {
                $w = ! empty( $settings['_ua_image_size_custom_dimension']['width'] ) ? intval( $settings['_ua_image_size_custom_dimension']['width'] ) : 300;
                $h = ! empty( $settings['_ua_image_size_custom_dimension']['height'] ) ? intval( $settings['_ua_image_size_custom_dimension']['height'] ) : 300;
                return [ $w, $h ];
            }
            return $size;
        }

        if ( ! empty( $settings['_ua_image_size'] ) ) {
            $size = $settings['_ua_image_size'];
            if ( 'custom' === $size && ! empty( $settings['_ua_image_custom_dimension'] ) ) {
                $w = ! empty( $settings['_ua_image_custom_dimension']['width'] ) ? intval( $settings['_ua_image_custom_dimension']['width'] ) : 300;
                $h = ! empty( $settings['_ua_image_custom_dimension']['height'] ) ? intval( $settings['_ua_image_custom_dimension']['height'] ) : 300;
                return [ $w, $h ];
            }
            return $size;
        }

        return 'medium';
    }

    /**
     * Render Product Title
     */
    protected function render_title( $product, $settings ) {
        $show_title = isset( $settings['_ua_show_title'] ) ? $settings['_ua_show_title'] === 'yes' : true;
        if ( ! $show_title ) {
            return;
        }

        $tag       = ! empty( $settings['_ua_title_tag'] ) ? $settings['_ua_title_tag'] : 'h2';
        $clickable = ! empty( $settings['_ua_title_clickable'] ) && $settings['_ua_title_clickable'] === 'yes';
        $title     = $product->get_name();
        $permalink = $product->get_permalink();

        echo '<' . esc_attr( $tag ) . ' class="ua-product-list-title">';
        if ( $clickable ) {
            echo '<a href="' . esc_url( $permalink ) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">' . esc_html( $title ) . '</a>';
        } else {
            echo esc_html( $title );
        }
        echo '</' . esc_attr( $tag ) . '>';
    }

    /**
     * Render Product Excerpt
     */
    protected function render_excerpt( $product, $settings ) {
        if ( empty( $settings['_ua_show_excerpt'] ) || $settings['_ua_show_excerpt'] !== 'yes' ) {
            return;
        }

        $limit     = ! empty( $settings['_ua_excerpt_words'] ) ? intval( $settings['_ua_excerpt_words'] ) : 30;
        $indicator = ! empty( $settings['_ua_excerpt_indicator'] ) ? $settings['_ua_excerpt_indicator'] : '...';

        $excerpt = $product->get_short_description();
        if ( empty( $excerpt ) ) {
            $excerpt = $product->get_description();
        }

        $excerpt = wp_strip_all_tags( $excerpt );
        $words   = explode( ' ', $excerpt );

        if ( count( $words ) > $limit ) {
            $excerpt = implode( ' ', array_slice( $words, 0, $limit ) ) . $indicator;
        }

        if ( ! empty( $excerpt ) ) {
            echo '<div class="ua-product-list-excerpt">' . esc_html( $excerpt ) . '</div>';
        }
    }

    /**
     * Render Total Sold Progress Bar
     */
    protected function render_total_sold( $product, $settings, $is_preset_3 = false ) {
        $layout = ! empty( $settings['_ua_layout'] ) ? $settings['_ua_layout'] : 'preset-1';
        $show_sold = ( $layout === 'preset-1' )
            ? ( ! empty( $settings['_ua_show_total_sold'] ) && $settings['_ua_show_total_sold'] === 'yes' )
            : ( isset( $settings['_ua_show_total_sold_preset_2_3'] ) ? $settings['_ua_show_total_sold_preset_2_3'] === 'yes' : ( ! empty( $settings['_ua_show_total_sold'] ) ? $settings['_ua_show_total_sold'] === 'yes' : true ) );

        if ( ! $show_sold ) {
            return;
        }

        $total_sales    = intval( $product->get_total_sales() );
        $stock_quantity = $product->get_stock_quantity();
        $show_remaining = ! empty( $settings['_ua_total_sold_remaining_show'] ) && $settings['_ua_total_sold_remaining_show'] === 'yes';

        $total_units = $total_sales + ( $stock_quantity ? $stock_quantity : 0 );
        $percent     = ( $total_units > 0 ) ? round( ( $total_sales / $total_units ) * 100 ) : 0;
        $percent     = min( 100, max( 0, $percent ) );

        $sold_label      = ! empty( $settings['_ua_total_sold_text'] ) ? $settings['_ua_total_sold_text'] : __( 'Total Sold:', 'ultraaddons-elementor-lite' );
        $remaining_label = ! empty( $settings['_ua_remaining_text'] ) ? $settings['_ua_remaining_text'] : __( 'Remaining:', 'ultraaddons-elementor-lite' );

        ?>
        <div class="ua-product-list-progress">
            <div class="ua-product-list-progress-info">
                <h4 class="ua-product-list-progress-count">
                    <?php if ( $is_preset_3 ) : ?>
                        <span><?php echo esc_html( $total_sales ); ?></span> <?php echo esc_html__( 'Item', 'ultraaddons-elementor-lite' ); ?>
                    <?php else : ?>
                        <?php echo esc_html( $sold_label ); ?> <span><?php echo esc_html( $total_sales ); ?></span>
                    <?php endif; ?>
                </h4>
                <?php if ( ! $is_preset_3 && $product->managing_stock() && $show_remaining && $stock_quantity !== null ) : ?>
                    <h4 class="ua-product-list-progress-remaining">
                        <?php echo esc_html( $remaining_label ); ?> <span><?php echo esc_html( $stock_quantity ); ?></span>
                    </h4>
                <?php endif; ?>
            </div>
            <div class="ua-product-list-progress-bar-outer">
                <div class="ua-product-list-progress-bar-inner" style="width: <?php echo esc_attr( $percent ); ?>%;"></div>
            </div>
        </div>
        <?php
    }

    /**
     * Render Action Button Text based on product type
     */
    public static function get_add_to_cart_text( $product, $settings ) {
        if ( ! empty( $settings['_ua_cart_custom_text_show'] ) && $settings['_ua_cart_custom_text_show'] === 'yes' ) {
            if ( $product->is_type( 'simple' ) && ! empty( $settings['_ua_cart_simple_text'] ) ) {
                return $settings['_ua_cart_simple_text'];
            } elseif ( $product->is_type( 'variable' ) && ! empty( $settings['_ua_cart_variable_text'] ) ) {
                return $settings['_ua_cart_variable_text'];
            } elseif ( $product->is_type( 'grouped' ) && ! empty( $settings['_ua_cart_grouped_text'] ) ) {
                return $settings['_ua_cart_grouped_text'];
            } elseif ( $product->is_type( 'external' ) && ! empty( $settings['_ua_cart_external_text'] ) ) {
                return $settings['_ua_cart_external_text'];
            }
        }
        return $product->add_to_cart_text();
    }

    /**
     * Render On-Hover Action Buttons (Thumbnail Overlay)
     */
    protected function render_on_hover_buttons( $product, $settings ) {
        $btn_position = ! empty( $settings['_ua_button_position'] ) ? $settings['_ua_button_position'] : 'both';
        if ( $btn_position === 'static' ) return;

        $show_cart         = ! empty( $settings['_ua_show_add_to_cart'] ) && $settings['_ua_show_add_to_cart'] === 'yes';
        $show_view_product = ! empty( $settings['_ua_show_quick_view'] ) && $settings['_ua_show_quick_view'] === 'yes';
        $show_link         = ! empty( $settings['_ua_show_link_btn'] ) && $settings['_ua_show_link_btn'] === 'yes';

        if ( ! $show_cart && ! $show_view_product && ! $show_link ) return;

        ?>
        <ul class="ua-product-list-buttons-on-hover">
            <?php if ( $show_cart ) : ?>
                <li class="ua-product-list-add-to-cart-button">
                    <?php
                    echo sprintf(
                        '<a href="%s" data-quantity="%s" class="%s" %s title="%s"><i class="fas fa-shopping-cart"></i></a>',
                        esc_url( $product->add_to_cart_url() ),
                        esc_attr( 1 ),
                        esc_attr( 'button product_type_' . $product->get_type() . ' add_to_cart_button ajax_add_to_cart' ),
                        'data-product_id="' . esc_attr( $product->get_id() ) . '" data-product_sku="' . esc_attr( $product->get_sku() ) . '"',
                        esc_attr( self::get_add_to_cart_text( $product, $settings ) )
                    );
                    ?>
                </li>
            <?php endif; ?>

            <?php if ( $show_view_product ) : ?>
                <li class="ua-product-list-quick-view-button">
                    <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="ua-quick-view-trigger open-popup-link" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" title="<?php esc_attr_e( 'View Product', 'ultraaddons-elementor-lite' ); ?>">
                        <i class="fas fa-eye" aria-hidden="true"></i>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ( $show_link ) : ?>
                <li class="ua-product-list-link-button">
                    <a href="<?php echo esc_url( $product->get_permalink() ); ?>" title="<?php esc_attr_e( 'View Product Details', 'ultraaddons-elementor-lite' ); ?>">
                        <i class="fas fa-link" aria-hidden="true"></i>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
        <?php
    }

    /**
     * Render Static Action Buttons (Content Footer)
     */
    protected function render_static_buttons( $product, $settings ) {
        $btn_position = ! empty( $settings['_ua_button_position'] ) ? $settings['_ua_button_position'] : 'both';
        if ( $btn_position === 'on_hover' ) return;

        $show_cart         = ! empty( $settings['_ua_show_add_to_cart'] ) && $settings['_ua_show_add_to_cart'] === 'yes';
        $show_view_product = ! empty( $settings['_ua_show_quick_view'] ) && $settings['_ua_show_quick_view'] === 'yes';

        if ( ! $show_cart && ! $show_view_product ) return;

        ?>
        <div class="ua-product-list-buttons">
            <?php if ( $show_cart ) : ?>
                <div class="ua-product-list-add-to-cart-button">
                    <?php
                    $cart_text = self::get_add_to_cart_text( $product, $settings );
                    echo sprintf(
                        '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
                        esc_url( $product->add_to_cart_url() ),
                        esc_attr( 1 ),
                        esc_attr( 'button product_type_' . $product->get_type() . ' add_to_cart_button ajax_add_to_cart' ),
                        'data-product_id="' . esc_attr( $product->get_id() ) . '" data-product_sku="' . esc_attr( $product->get_sku() ) . '"',
                        esc_html( $cart_text )
                    );
                    ?>
                </div>
            <?php endif; ?>

            <?php if ( $show_view_product ) : ?>
                <div class="ua-product-list-quick-view-button">
                    <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="ua-quick-view-trigger open-popup-link" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
                        <?php echo esc_html( ! empty( $settings['_ua_quick_view_text'] ) ? $settings['_ua_quick_view_text'] : __( 'View Product', 'ultraaddons-elementor-lite' ) ); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Render Single Product Item HTML
     */
    public static function render_product_item_html( $product, $settings, $instance ) {
        $layout          = ! empty( $settings['_ua_layout'] ) ? $settings['_ua_layout'] : 'preset-1';
        $image_alignment = ! empty( $settings['_ua_image_alignment'] ) ? $settings['_ua_image_alignment'] : 'left';
        $badge_preset    = ! empty( $settings['_ua_badge_preset'] ) ? $settings['_ua_badge_preset'] : ( $layout === 'preset-2' ? 'badge_2' : 'badge_1' );
        
        // Preset default header position: Preset 1 is before_title, Presets 2 & 3 are after_title
        $header_position = ( $layout === 'preset-1' )
            ? ( ! empty( $settings['_ua_header_position'] ) ? $settings['_ua_header_position'] : 'before_title' )
            : ( ! empty( $settings['_ua_header_position_preset_2_3'] ) ? $settings['_ua_header_position_preset_2_3'] : ( ! empty( $settings['_ua_header_position'] ) ? $settings['_ua_header_position'] : 'after_title' ) );
        $header_dir      = ! empty( $settings['_ua_header_direction'] ) ? $settings['_ua_header_direction'] : 'ltr';
        $image_size      = self::get_product_image_size( $settings );
        $image_clickable = ! empty( $settings['_ua_image_clickable'] ) && $settings['_ua_image_clickable'] === 'yes';

        $item_classes = [
            'ua-product-list-item',
            'ua-layout-' . $layout,
            'image-alignment-' . $image_alignment,
            'ua-image-align-' . $image_alignment,
            'product',
            'post-' . $product->get_id(),
        ];

        ?>
        <div class="<?php echo esc_attr( implode( ' ', $item_classes ) ); ?>" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">

            <!-- Corner Ribbon Badge if Badge Preset 2 -->
            <?php if ( $badge_preset === 'badge_2' ) : ?>
                <?php $instance->render_badge( $product, $settings ); ?>
            <?php endif; ?>

            <!-- 1. Thumbnail Wrap -->
            <div class="ua-product-list-image-wrap">
                <?php if ( $badge_preset !== 'badge_2' ) : ?>
                    <?php $instance->render_badge( $product, $settings ); ?>
                <?php endif; ?>

                <?php if ( $image_clickable ) : ?>
                    <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="ua-product-image-link woocommerce-LoopProduct-link woocommerce-loop-product__link">
                        <?php echo $product->get_image( $image_size, [ 'loading' => 'eager', 'alt' => $product->get_title() ] ); ?>
                    </a>
                <?php else : ?>
                    <?php echo $product->get_image( $image_size, [ 'loading' => 'eager', 'alt' => $product->get_title() ] ); ?>
                <?php endif; ?>

                <?php $instance->render_on_hover_buttons( $product, $settings ); ?>
            </div>

            <!-- 2. Content Wrap -->
            <div class="ua-product-list-content-wrap">

                <!-- Title Before Header (if selected) -->
                <?php if ( $header_position === 'after_title' ) : ?>
                    <?php $instance->render_title( $product, $settings ); ?>
                <?php endif; ?>

                <!-- Content Header -->
                <div class="ua-product-list-content-header ua-dir-<?php echo esc_attr( $header_dir ); ?>">
                    <?php $instance->render_rating( $product, $settings ); ?>
                    <?php if ( $layout !== 'preset-3' ) : ?>
                        <?php $instance->render_category( $product, $settings ); ?>
                    <?php endif; ?>
                </div>

                <!-- Content Body -->
                <div class="ua-product-list-content-body">
                    <?php if ( $header_position === 'before_title' ) : ?>
                        <?php $instance->render_title( $product, $settings ); ?>
                    <?php endif; ?>

                    <?php $instance->render_excerpt( $product, $settings ); ?>

                    <?php if ( ! empty( $settings['_ua_show_price'] ) && $settings['_ua_show_price'] === 'yes' ) : ?>
                        <h3 class="ua-product-list-price">
                            <?php echo $product->get_price_html(); ?>
                        </h3>
                    <?php endif; ?>
                </div>

                <!-- Content Footer -->
                <div class="ua-product-list-content-footer">
                    <?php if ( $layout === 'preset-3' ) : ?>
                        <!-- Preset 3 Inline Layout: Add to Cart + Progress Bar + Category -->
                        <div class="ua-product-list-buttons">
                            <?php if ( ! empty( $settings['_ua_show_add_to_cart'] ) && $settings['_ua_show_add_to_cart'] === 'yes' ) : ?>
                                <div class="ua-product-list-add-to-cart-button">
                                    <?php
                                    $cart_text = self::get_add_to_cart_text( $product, $settings );
                                    echo sprintf(
                                        '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
                                        esc_url( $product->add_to_cart_url() ),
                                        esc_attr( 1 ),
                                        esc_attr( 'button product_type_' . $product->get_type() . ' add_to_cart_button ajax_add_to_cart' ),
                                        'data-product_id="' . esc_attr( $product->get_id() ) . '" data-product_sku="' . esc_attr( $product->get_sku() ) . '"',
                                        esc_html( $cart_text )
                                    );
                                    ?>
                                </div>
                            <?php endif; ?>

                            <?php $instance->render_total_sold( $product, $settings, true ); ?>
                            <?php $instance->render_category( $product, $settings ); ?>
                        </div>
                    <?php else : ?>
                        <!-- Presets 1 & 2: Progress Bar above Buttons -->
                        <?php $instance->render_total_sold( $product, $settings ); ?>
                        <?php $instance->render_static_buttons( $product, $settings ); ?>
                    <?php endif; ?>
                </div>

            </div>

        </div>
        <?php
    }

    /**
     * Render Widget Main Output
     */
    protected function render() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            ?>
            <div class="ua-product-list-notice">
                <p><?php esc_html_e( 'WooCommerce must be installed and active to use the Product List widget.', 'ultraaddons-elementor-lite' ); ?></p>
            </div>
            <?php
            return;
        }

        $settings        = $this->get_settings_for_display();
        $layout          = ! empty( $settings['_ua_layout'] ) ? $settings['_ua_layout'] : 'preset-1';
        $pagination_type = ! empty( $settings['_ua_pagination_type'] ) ? $settings['_ua_pagination_type'] : 'none';
        $scroll_offset   = ! empty( $settings['_ua_infinite_scroll_offset'] ) ? intval( $settings['_ua_infinite_scroll_offset'] ) : -200;

        $query_args = self::build_query_args( $settings, 1 );
        $query      = new \WP_Query( $query_args );

        $total_pages = $query->max_num_pages;

        $wrapper_classes = [
            'ua-product-list-wrapper',
            $layout,
            'ua-' . $layout,
            'ua-pagination-' . $pagination_type,
        ];

        // Prepare sanitized settings for AJAX pagination
        $safe_settings = [
            '_ua_layout'                    => $layout,
            '_ua_image_alignment'           => ! empty( $settings['_ua_image_alignment'] ) ? $settings['_ua_image_alignment'] : 'left',
            '_ua_header_position'           => ! empty( $settings['_ua_header_position'] ) ? $settings['_ua_header_position'] : ( $layout === 'preset-1' ? 'before_title' : 'after_title' ),
            '_ua_header_position_preset_2_3' => ! empty( $settings['_ua_header_position_preset_2_3'] ) ? $settings['_ua_header_position_preset_2_3'] : 'after_title',
            '_ua_header_direction'          => ! empty( $settings['_ua_header_direction'] ) ? $settings['_ua_header_direction'] : 'ltr',
            '_ua_image_clickable'           => ! empty( $settings['_ua_image_clickable'] ) ? $settings['_ua_image_clickable'] : '',
            '_ua_image_size_size'           => ! empty( $settings['_ua_image_size_size'] ) ? $settings['_ua_image_size_size'] : ( ! empty( $settings['_ua_image_size'] ) ? $settings['_ua_image_size'] : 'medium' ),
            '_ua_image_size'                => ! empty( $settings['_ua_image_size'] ) ? $settings['_ua_image_size'] : ( ! empty( $settings['_ua_image_size_size'] ) ? $settings['_ua_image_size_size'] : 'medium' ),
            '_ua_show_badge'                => ! empty( $settings['_ua_show_badge'] ) ? $settings['_ua_show_badge'] : 'yes',
            '_ua_badge_preset'              => ! empty( $settings['_ua_badge_preset'] ) ? $settings['_ua_badge_preset'] : ( $layout === 'preset-2' ? 'badge_2' : 'badge_1' ),
            '_ua_badge_alignment'           => ! empty( $settings['_ua_badge_alignment'] ) ? $settings['_ua_badge_alignment'] : 'left',
            '_ua_badge_sale_text'           => ! empty( $settings['_ua_badge_sale_text'] ) ? $settings['_ua_badge_sale_text'] : '',
            '_ua_badge_stockout_text'       => ! empty( $settings['_ua_badge_stockout_text'] ) ? $settings['_ua_badge_stockout_text'] : '',
            '_ua_show_rating'               => ! empty( $settings['_ua_show_rating'] ) ? $settings['_ua_show_rating'] : 'yes',
            '_ua_show_review_count'         => ! empty( $settings['_ua_show_review_count'] ) ? $settings['_ua_show_review_count'] : 'yes',
            '_ua_show_category'             => ! empty( $settings['_ua_show_category'] ) ? $settings['_ua_show_category'] : 'yes',
            '_ua_show_title'                => ! empty( $settings['_ua_show_title'] ) ? $settings['_ua_show_title'] : 'yes',
            '_ua_title_tag'                 => ! empty( $settings['_ua_title_tag'] ) ? $settings['_ua_title_tag'] : 'h2',
            '_ua_title_clickable'           => ! empty( $settings['_ua_title_clickable'] ) ? $settings['_ua_title_clickable'] : 'yes',
            '_ua_show_excerpt'              => ! empty( $settings['_ua_show_excerpt'] ) ? $settings['_ua_show_excerpt'] : 'yes',
            '_ua_excerpt_words'             => ! empty( $settings['_ua_excerpt_words'] ) ? $settings['_ua_excerpt_words'] : 30,
            '_ua_excerpt_indicator'         => ! empty( $settings['_ua_excerpt_indicator'] ) ? $settings['_ua_excerpt_indicator'] : '...',
            '_ua_show_price'                => ! empty( $settings['_ua_show_price'] ) ? $settings['_ua_show_price'] : 'yes',
            '_ua_show_total_sold'           => ! empty( $settings['_ua_show_total_sold'] ) ? $settings['_ua_show_total_sold'] : '',
            '_ua_show_total_sold_preset_2_3' => ! empty( $settings['_ua_show_total_sold_preset_2_3'] ) ? $settings['_ua_show_total_sold_preset_2_3'] : ( $layout !== 'preset-1' ? 'yes' : '' ),
            '_ua_total_sold_remaining_show' => ! empty( $settings['_ua_total_sold_remaining_show'] ) ? $settings['_ua_total_sold_remaining_show'] : 'yes',
            '_ua_total_sold_text'           => ! empty( $settings['_ua_total_sold_text'] ) ? $settings['_ua_total_sold_text'] : '',
            '_ua_remaining_text'            => ! empty( $settings['_ua_remaining_text'] ) ? $settings['_ua_remaining_text'] : '',
            '_ua_button_position'           => ! empty( $settings['_ua_button_position'] ) ? $settings['_ua_button_position'] : 'both',
            '_ua_show_add_to_cart'          => ! empty( $settings['_ua_show_add_to_cart'] ) ? $settings['_ua_show_add_to_cart'] : 'yes',
            '_ua_show_quick_view'           => ! empty( $settings['_ua_show_quick_view'] ) ? $settings['_ua_show_quick_view'] : 'yes',
            '_ua_show_link_btn'             => ! empty( $settings['_ua_show_link_btn'] ) ? $settings['_ua_show_link_btn'] : 'yes',
            '_ua_cart_custom_text_show'     => ! empty( $settings['_ua_cart_custom_text_show'] ) ? $settings['_ua_cart_custom_text_show'] : '',
            '_ua_cart_simple_text'          => ! empty( $settings['_ua_cart_simple_text'] ) ? $settings['_ua_cart_simple_text'] : '',
            '_ua_cart_variable_text'        => ! empty( $settings['_ua_cart_variable_text'] ) ? $settings['_ua_cart_variable_text'] : '',
            '_ua_cart_grouped_text'         => ! empty( $settings['_ua_cart_grouped_text'] ) ? $settings['_ua_cart_grouped_text'] : '',
            '_ua_cart_external_text'        => ! empty( $settings['_ua_cart_external_text'] ) ? $settings['_ua_cart_external_text'] : '',
            '_ua_quick_view_text'           => ! empty( $settings['_ua_quick_view_text'] ) ? $settings['_ua_quick_view_text'] : '',
            '_ua_query_filter'              => ! empty( $settings['_ua_query_filter'] ) ? $settings['_ua_query_filter'] : 'recent',
            '_ua_categories'                => ! empty( $settings['_ua_categories'] ) ? $settings['_ua_categories'] : [],
            '_ua_manual_product_ids'        => ! empty( $settings['_ua_manual_product_ids'] ) ? $settings['_ua_manual_product_ids'] : '',
            '_ua_posts_per_page'            => ! empty( $settings['_ua_posts_per_page'] ) ? $settings['_ua_posts_per_page'] : 4,
            '_ua_offset'                    => ! empty( $settings['_ua_offset'] ) ? $settings['_ua_offset'] : 0,
            '_ua_orderby'                   => ! empty( $settings['_ua_orderby'] ) ? $settings['_ua_orderby'] : 'date',
            '_ua_order'                     => ! empty( $settings['_ua_order'] ) ? $settings['_ua_order'] : 'desc',
            '_ua_show_out_of_stock'         => ! empty( $settings['_ua_show_out_of_stock'] ) ? $settings['_ua_show_out_of_stock'] : 'yes',
            '_ua_user_order_filter'         => ! empty( $settings['_ua_user_order_filter'] ) ? $settings['_ua_user_order_filter'] : 'all',
        ];
        ?>
        <div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>"
             data-pagination="<?php echo esc_attr( $pagination_type ); ?>"
             data-offset="<?php echo esc_attr( $scroll_offset ); ?>"
             data-paged="1"
             data-max-pages="<?php echo esc_attr( $total_pages ); ?>"
             data-settings="<?php echo esc_attr( wp_json_encode( $safe_settings ) ); ?>">

            <div class="ua-product-list-container">
                <?php if ( $query->have_posts() ) : ?>
                    <div class="ua-product-list-items-wrap">
                        <?php
                        while ( $query->have_posts() ) :
                            $query->the_post();
                            $product = wc_get_product( get_the_ID() );
                            if ( ! $product ) continue;
                            self::render_product_item_html( $product, $settings, $this );
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </div>

                    <?php if ( $pagination_type === 'load_more' && $total_pages > 1 ) : ?>
                        <div class="ua-load-more-wrap">
                            <button type="button" class="ua-load-more-btn">
                                <span class="ua-btn-spinner"></span>
                                <span class="ua-btn-text"><?php echo esc_html( ! empty( $settings['_ua_load_more_btn_text'] ) ? $settings['_ua_load_more_btn_text'] : __( 'Load More', 'ultraaddons-elementor-lite' ) ); ?></span>
                            </button>
                        </div>
                    <?php elseif ( $pagination_type === 'infinite' && $total_pages > 1 ) : ?>
                        <div class="ua-infinite-scroll-loader" data-offset="<?php echo esc_attr( $scroll_offset ); ?>">
                            <div class="ua-infinite-spinner"></div>
                        </div>
                    <?php endif; ?>

                <?php else : ?>
                    <div class="ua-no-products-found">
                        <p><?php echo esc_html( ! empty( $settings['_ua_not_found_text'] ) ? $settings['_ua_not_found_text'] : __( 'No products found!', 'ultraaddons-elementor-lite' ) ); ?></p>
                    </div>
                <?php endif; ?>
            </div>

        </div>
        <?php
    }

    /**
     * AJAX Endpoint: Load More Products
     */
    public static function ajax_load_more() {
        check_ajax_referer( 'ua-product-list-nonce', 'security' );

        $paged    = isset( $_POST['paged'] ) ? intval( $_POST['paged'] ) : 1;
        $settings = isset( $_POST['settings'] ) ? (array) $_POST['settings'] : [];

        if ( empty( $settings ) ) {
            wp_send_json_error( [ 'message' => 'Invalid settings' ] );
        }

        $query_args = self::build_query_args( $settings, $paged );
        $query      = new \WP_Query( $query_args );

        if ( ! $query->have_posts() ) {
            wp_send_json_success( [ 'html' => '', 'no_more' => true ] );
        }

        $instance = new self();

        ob_start();
        while ( $query->have_posts() ) {
            $query->the_post();
            $product = wc_get_product( get_the_ID() );
            if ( ! $product ) continue;
            self::render_product_item_html( $product, $settings, $instance );
        }
        wp_reset_postdata();
        $html = ob_get_clean();

        wp_send_json_success( [
            'html'      => $html,
            'paged'     => $paged,
            'max_pages' => $query->max_num_pages,
            'no_more'   => ( $paged >= $query->max_num_pages ),
        ] );
    }

    /**
     * AJAX Endpoint: Quick View / View Product Modal Content (Matching EA structure)
     */
    public static function ajax_quick_view() {
        if ( isset( $_POST['security'] ) ) {
            check_ajax_referer( 'ua-product-list-nonce', 'security', false );
        }

        $product_id = isset( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : 0;
        if ( ! $product_id ) {
            wp_send_json_error( [ 'message' => 'Invalid product ID' ] );
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            wp_send_json_error( [ 'message' => 'Product not found' ] );
        }

        global $post;
        $post = get_post( $product_id );
        if ( $post ) {
            setup_postdata( $post );
        }

        ob_start();
        ?>
        <div id="product-<?php echo esc_attr( $product->get_id() ); ?>" class="product ua-quick-view-product">
            <div class="ua-quick-view-gallery">
                <?php if ( ! $product->is_in_stock() ) : ?>
                    <span class="ua-modal-onsale outofstock"><?php esc_html_e( 'Stock Out', 'ultraaddons-elementor-lite' ); ?></span>
                <?php elseif ( $product->is_on_sale() ) : ?>
                    <span class="ua-modal-onsale"><?php esc_html_e( 'Sale!', 'ultraaddons-elementor-lite' ); ?></span>
                <?php endif; ?>
                <div class="woocommerce-product-gallery">
                    <div class="woocommerce-product-gallery__image">
                        <?php echo $product->get_image( 'shop_single', [ 'alt' => esc_attr( $product->get_title() ) ] ); ?>
                    </div>
                </div>
            </div>
            <div class="ua-quick-view-summary entry-summary">
                <h1 class="product_title entry-title">
                    <a href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
                </h1>
                
                <?php if ( function_exists( 'woocommerce_template_single_rating' ) ) : ?>
                    <?php woocommerce_template_single_rating(); ?>
                <?php else : ?>
                    <div class="woocommerce-product-rating">
                        <?php echo wc_get_rating_html( $product->get_average_rating(), $product->get_rating_count() ); ?>
                    </div>
                <?php endif; ?>

                <?php if ( function_exists( 'woocommerce_template_single_price' ) ) : ?>
                    <?php woocommerce_template_single_price(); ?>
                <?php else : ?>
                    <div class="price"><?php echo $product->get_price_html(); ?></div>
                <?php endif; ?>

                <?php if ( function_exists( 'woocommerce_template_single_excerpt' ) ) : ?>
                    <?php woocommerce_template_single_excerpt(); ?>
                <?php else : ?>
                    <div class="woocommerce-product-details__short-description"><?php echo wpautop( $product->get_short_description() ); ?></div>
                <?php endif; ?>

                <?php
                if ( function_exists( 'woocommerce_template_single_add_to_cart' ) ) {
                    woocommerce_template_single_add_to_cart();
                } elseif ( $product->is_type( 'simple' ) && function_exists( 'woocommerce_simple_add_to_cart' ) ) {
                    woocommerce_simple_add_to_cart();
                } elseif ( $product->is_type( 'variable' ) && function_exists( 'woocommerce_variable_add_to_cart' ) ) {
                    woocommerce_variable_add_to_cart();
                } elseif ( $product->is_type( 'grouped' ) && function_exists( 'woocommerce_grouped_add_to_cart' ) ) {
                    woocommerce_grouped_add_to_cart();
                } elseif ( $product->is_type( 'external' ) && function_exists( 'woocommerce_external_add_to_cart' ) ) {
                    woocommerce_external_add_to_cart();
                }
                ?>

                <?php if ( function_exists( 'woocommerce_template_single_meta' ) ) : ?>
                    <?php woocommerce_template_single_meta(); ?>
                <?php else : ?>
                    <div class="product_meta">
                        <?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>
                            <span class="sku_wrapper"><?php esc_html_e( 'SKU:', 'woocommerce' ); ?> <span class="sku"><?php echo ( $sku = $product->get_sku() ) ? esc_html( $sku ) : esc_html__( 'N/A', 'woocommerce' ); ?></span></span>
                        <?php endif; ?>
                        <?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . esc_html__( 'Categories:', 'woocommerce' ) . ' ', '</span>' ); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        wp_reset_postdata();
        $html = ob_get_clean();

        wp_send_json_success( [ 'html' => $html ] );
    }

    /**
     * AJAX Handler: Quick View Add to Cart (Keep user inside popup)
     * 
     * @since 2.0.3
     */
    public static function ajax_modal_add_to_cart() {
        if ( isset( $_POST['security'] ) ) {
            check_ajax_referer( 'ua-product-list-nonce', 'security', false );
        }

        if ( ! class_exists( 'WooCommerce' ) ) {
            wp_send_json_error( [ 'message' => __( 'WooCommerce not active.', 'ultraaddons-elementor-lite' ) ] );
        }

        $product_id   = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
        $quantity     = isset( $_POST['quantity'] ) && ! is_array( $_POST['quantity'] ) ? max( 1, wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) ) : 1;
        $variation_id = isset( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
        $variations   = [];

        if ( ! $product_id && isset( $_POST['add-to-cart'] ) ) {
            $product_id = absint( $_POST['add-to-cart'] );
        }

        // Collect attribute_* fields for variable products
        foreach ( $_POST as $key => $value ) {
            if ( strpos( $key, 'attribute_' ) === 0 ) {
                $variations[ sanitize_text_field( $key ) ] = wp_unslash( $value );
            }
        }

        // Grouped products handling
        $is_grouped = isset( $_POST['quantity'] ) && is_array( $_POST['quantity'] );
        $added_keys = [];

        if ( $is_grouped ) {
            $quantities = $_POST['quantity'];
            foreach ( $quantities as $item_id => $qty ) {
                $item_id = absint( $item_id );
                $qty     = wc_stock_amount( $qty );
                if ( $qty > 0 && $item_id > 0 ) {
                    $key = WC()->cart->add_to_cart( $item_id, $qty );
                    if ( $key ) {
                        $added_keys[] = $key;
                    }
                }
            }
        } else {
            $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variations );

            if ( $passed_validation ) {
                $cart_item_key = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variations );
                if ( $cart_item_key ) {
                    $added_keys[] = $cart_item_key;
                }
            }
        }

        if ( ! empty( $added_keys ) ) {
            do_action( 'woocommerce_ajax_added_to_cart', $product_id );

            // Generate fresh mini cart HTML
            ob_start();
            woocommerce_mini_cart();
            $mini_cart = ob_get_clean();

            $fragments = apply_filters( 'woocommerce_add_to_cart_fragments', [
                'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
            ] );

            $cart_hash = apply_filters( 'woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5( json_encode( WC()->cart->get_cart_for_session() ) ) : '', WC()->cart->get_cart_for_session() );

            wp_send_json( [
                'fragments' => $fragments,
                'cart_hash' => $cart_hash,
                'success'   => true,
            ] );
        } else {
            wp_send_json_error( [
                'message'     => __( 'Could not add item to cart.', 'ultraaddons-elementor-lite' ),
                'product_url' => get_permalink( $product_id ),
            ] );
        }

        wp_die();
    }
}
