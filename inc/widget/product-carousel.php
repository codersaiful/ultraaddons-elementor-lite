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
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * UltraAddons Product Carousel Widget
 *
 * A modern, responsive, interactive WooCommerce Product Carousel widget for Elementor.
 * Includes 4 design presets, comprehensive query options, live AJAX add-to-cart,
 * 1-click Buy Now direct checkout, smart discount calculation, and deep styling controls.
 *
 * @package UltraAddons
 * @since 1.1.0.8
 * @author Saiful Islam <codersaiful@gmail.com>
 * @author UltraAddons Team
 */
class Product_Carousel extends Base {

	/**
	 * Constructor to register required assets
	 *
	 * @param array $data
	 * @param array|null $args
	 */
	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		// Register swiper script
		wp_register_script(
			'swiper',
			ULTRA_ADDONS_ASSETS . 'vendor/swiper/js/swiper.min.js',
			[ 'jquery' ],
			ULTRA_ADDONS_VERSION,
			true
		);

		// Register widget frontend script
		wp_register_script(
			'frontend-product-carousel',
			ULTRA_ADDONS_ASSETS . 'js/frontend-product-carousel.js',
			[ 'jquery', 'swiper' ],
			ULTRA_ADDONS_VERSION,
			true
		);

		// Register swiper CSS
		wp_register_style(
			'swiper',
			ULTRA_ADDONS_ASSETS . 'vendor/swiper/css/swiper.min.css',
			[],
			ULTRA_ADDONS_VERSION
		);
	}

	/**
	 * Retrieve widget title
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Product Carousel', 'ultraaddons-elementor-lite' );
	}

	/**
	 * Retrieve widget icon
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'ultraaddons eicon-products';
	}

	/**
	 * Retrieve widget keywords
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'ultraaddons-elementor-lite', 'ua', 'product', 'slider', 'carousel', 'woocommerce', 'shop', 'store', 'woo' ];
	}

	/**
	 * Widget style dependencies
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array_merge( parent::get_style_depends(), [ 'swiper', 'ultraaddons-product-carousel' ] );
	}

	/**
	 * Widget script dependencies
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array_merge( parent::get_script_depends(), [ 'jquery', 'swiper', 'frontend-product-carousel' ] );
	}

	/**
	 * Helper: Get taxonomy options for select controls
	 *
	 * @param string $taxonomy
	 * @return array
	 */
	public function product_tax_options( $taxonomy = 'product_cat' ) {
		$options = [];
		if ( ! taxonomy_exists( $taxonomy ) ) {
			return $options;
		}

		$terms = get_terms( [
			'taxonomy'   => $taxonomy,
			'hide_empty' => true,
		] );

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$options[ $term->slug ] = $term->name . ' (' . $term->count . ')';
			}
		}

		return $options;
	}

	/**
	 * Helper: Get all products list for manual selection
	 *
	 * @return array
	 */
	public function get_all_products_options() {
		$options = [];
		if ( ! function_exists( 'wc_get_products' ) ) {
			return $options;
		}

		$products = wc_get_products( [
			'status' => 'publish',
			'limit'  => 50,
		] );

		if ( ! empty( $products ) ) {
			foreach ( $products as $prod ) {
				$options[ $prod->get_id() ] = $prod->get_name();
			}
		}

		return $options;
	}

	/**
	 * Register widget controls
	 */
	protected function register_controls() {
		// Content Controls
		$this->register_layout_controls();
		$this->register_badges_controls();
		$this->register_query_controls();
		$this->register_carousel_controls();

		// Style Controls
		$this->register_card_style_controls();
		$this->register_image_style_controls();
		$this->register_content_style_controls();
		$this->register_title_style_controls();
		$this->register_price_style_controls();
		$this->register_rating_style_controls();
		$this->register_meta_style_controls();
		$this->register_badges_style_controls();
		$this->register_buttons_style_controls();
		$this->register_arrows_style_controls();
		$this->register_dots_style_controls();
	}

	/**
	 * -------------------------------------------------------------
	 * CONTENT TAB: Layout Settings
	 * -------------------------------------------------------------
	 */
	protected function register_layout_controls() {
		$this->start_controls_section(
			'section_layout_settings',
			[
				'label' => esc_html__( 'Layout Settings', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'layout_preset',
			[
				'label'   => esc_html__( 'Layout Preset', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'preset-1',
				'options' => [
					'preset-1' => esc_html__( 'Preset 1 - Overlay Action Icons', 'ultraaddons-elementor-lite' ),
					'preset-2' => esc_html__( 'Preset 2 - Side Action Bar', 'ultraaddons-elementor-lite' ),
					'preset-3' => esc_html__( 'Preset 3 - Bottom Action Bar', 'ultraaddons-elementor-lite' ),
					'preset-4' => esc_html__( 'Preset 4 - Modern Minimal Card', 'ultraaddons-elementor-lite' ),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image_size',
				'default'   => 'woocommerce_thumbnail',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'image_clickable',
			[
				'label'        => esc_html__( 'Image Clickable Link', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
				'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'show_title',
			[
				'label'        => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label'     => esc_html__( 'Title HTML Tag', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h3',
				'options'   => [
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
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'title_length',
			[
				'label'       => esc_html__( 'Title Word Limit', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 1,
				'max'         => 50,
				'description' => esc_html__( 'Leave empty for full title.', 'ultraaddons-elementor-lite' ),
				'condition'   => [
					'show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'title_clickable',
			[
				'label'        => esc_html__( 'Title Clickable Link', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
				'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_category',
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
			'show_rating',
			[
				'label'        => esc_html__( 'Star Rating', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'show_rating_count',
			[
				'label'        => esc_html__( 'Rating Count Text', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => [
					'show_rating' => 'yes',
				],
			]
		);

		$this->add_control(
			'rating_format',
			[
				'label'       => esc_html__( 'Rating Format', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '([rating_count])',
				'description' => esc_html__( 'Use [avg_rating] or [rating_count]', 'ultraaddons-elementor-lite' ),
				'condition'   => [
					'show_rating'       => 'yes',
					'show_rating_count' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_price',
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
			'show_excerpt',
			[
				'label'        => esc_html__( 'Short Description / Excerpt', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'excerpt_length',
			[
				'label'     => esc_html__( 'Excerpt Word Limit', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 5,
				'max'       => 100,
				'default'   => 12,
				'condition' => [
					'show_excerpt' => 'yes',
				],
			]
		);

		$this->add_control(
			'heading_action_buttons',
			[
				'label'     => esc_html__( 'Action Buttons', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'show_add_to_cart',
			[
				'label'        => esc_html__( 'Add to Cart Button', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'show_buy_now',
			[
				'label'        => esc_html__( 'Buy Now (1-Click Checkout)', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'buy_now_text',
			[
				'label'     => esc_html__( 'Buy Now Tooltip / Text', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Buy Now', 'ultraaddons-elementor-lite' ),
				'condition' => [
					'show_buy_now' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_quick_view',
			[
				'label'        => esc_html__( 'Quick View Button', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'show_details',
			[
				'label'        => esc_html__( 'View Details Link Button', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * -------------------------------------------------------------
	 * CONTENT TAB: Badges Settings
	 * -------------------------------------------------------------
	 */
	protected function register_badges_controls() {
		$this->start_controls_section(
			'section_badges_settings',
			[
				'label' => esc_html__( 'Badges Settings', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_sale_badge',
			[
				'label'        => esc_html__( 'Sale Badge', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'sale_badge_type',
			[
				'label'     => esc_html__( 'Sale Badge Type', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'percentage',
				'options'   => [
					'percentage' => esc_html__( 'Discount Percentage (-XX%)', 'ultraaddons-elementor-lite' ),
					'text'       => esc_html__( 'Custom Text', 'ultraaddons-elementor-lite' ),
				],
				'condition' => [
					'show_sale_badge' => 'yes',
				],
			]
		);

		$this->add_control(
			'sale_badge_text',
			[
				'label'     => esc_html__( 'Sale Badge Text', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Sale!', 'ultraaddons-elementor-lite' ),
				'condition' => [
					'show_sale_badge' => 'yes',
					'sale_badge_type' => 'text',
				],
			]
		);

		$this->add_control(
			'sale_badge_pos',
			[
				'label'     => esc_html__( 'Sale Badge Position', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'  => [
						'title' => esc_html__( 'Top Left', 'ultraaddons-elementor-lite' ),
						'icon'  => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Top Right', 'ultraaddons-elementor-lite' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'   => 'left',
				'condition' => [
					'show_sale_badge' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_stockout_badge',
			[
				'label'        => esc_html__( 'Stock Out Badge', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'stockout_badge_text',
			[
				'label'     => esc_html__( 'Stock Out Text', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Sold Out', 'ultraaddons-elementor-lite' ),
				'condition' => [
					'show_stockout_badge' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * -------------------------------------------------------------
	 * CONTENT TAB: Query Settings
	 * -------------------------------------------------------------
	 */
	protected function register_query_controls() {
		$this->start_controls_section(
			'section_query_settings',
			[
				'label' => esc_html__( 'Query Settings', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'product_filter',
			[
				'label'   => esc_html__( 'Filter Products By', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'recent',
				'options' => [
					'recent'       => esc_html__( 'Recent Products', 'ultraaddons-elementor-lite' ),
					'featured'     => esc_html__( 'Featured Products', 'ultraaddons-elementor-lite' ),
					'best_selling' => esc_html__( 'Best Selling Products', 'ultraaddons-elementor-lite' ),
					'sale'         => esc_html__( 'On Sale Products', 'ultraaddons-elementor-lite' ),
					'top_rated'    => esc_html__( 'Top Rated Products', 'ultraaddons-elementor-lite' ),
					'manual'       => esc_html__( 'Manual Product Selection', 'ultraaddons-elementor-lite' ),
				],
			]
		);

		$this->add_control(
			'manual_products',
			[
				'label'       => esc_html__( 'Select Products', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => $this->get_all_products_options(),
				'label_block' => true,
				'condition'   => [
					'product_filter' => 'manual',
				],
			]
		);

		$this->add_control(
			'categories',
			[
				'label'       => esc_html__( 'Include Categories', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => $this->product_tax_options( 'product_cat' ),
				'label_block' => true,
				'condition'   => [
					'product_filter!' => 'manual',
				],
			]
		);

		$this->add_control(
			'tags',
			[
				'label'       => esc_html__( 'Include Tags', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => $this->product_tax_options( 'product_tag' ),
				'label_block' => true,
				'condition'   => [
					'product_filter!' => 'manual',
				],
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label'       => esc_html__( 'Total Products Limit', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => -1,
				'max'         => 200,
				'default'     => 8,
				'description' => esc_html__( 'Enter -1 to show all products.', 'ultraaddons-elementor-lite' ),
			]
		);

		$this->add_control(
			'offset',
			[
				'label'   => esc_html__( 'Offset', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 0,
				'max'     => 50,
				'default' => 0,
				'condition' => [
					'product_filter!' => 'manual',
				],
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'     => esc_html__( 'Order By', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'date',
				'options'   => [
					'date'       => esc_html__( 'Date (Published)', 'ultraaddons-elementor-lite' ),
					'title'      => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
					'price'      => esc_html__( 'Price', 'ultraaddons-elementor-lite' ),
					'popularity' => esc_html__( 'Popularity (Sales)', 'ultraaddons-elementor-lite' ),
					'rating'     => esc_html__( 'Rating', 'ultraaddons-elementor-lite' ),
					'rand'       => esc_html__( 'Random', 'ultraaddons-elementor-lite' ),
					'menu_order' => esc_html__( 'Menu Order', 'ultraaddons-elementor-lite' ),
				],
				'condition' => [
					'product_filter!' => 'manual',
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label'     => esc_html__( 'Order Direction', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'DESC',
				'options'   => [
					'DESC' => esc_html__( 'Descending (DESC)', 'ultraaddons-elementor-lite' ),
					'ASC'  => esc_html__( 'Ascending (ASC)', 'ultraaddons-elementor-lite' ),
				],
				'condition' => [
					'product_filter!' => 'manual',
				],
			]
		);

		$this->add_control(
			'hide_out_of_stock',
			[
				'label'        => esc_html__( 'Hide Out of Stock Products', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
				'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * -------------------------------------------------------------
	 * CONTENT TAB: Carousel Settings
	 * -------------------------------------------------------------
	 */
	protected function register_carousel_controls() {
		$this->start_controls_section(
			'section_carousel_settings',
			[
				'label' => esc_html__( 'Carousel Settings', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'carousel_effect',
			[
				'label'   => esc_html__( 'Transition Effect', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'slide',
				'options' => [
					'slide'     => esc_html__( 'Slide', 'ultraaddons-elementor-lite' ),
					'coverflow' => esc_html__( '3D Coverflow', 'ultraaddons-elementor-lite' ),
				],
			]
		);

		$this->add_responsive_control(
			'slides_to_show',
			[
				'label'          => esc_html__( 'Slides Per View', 'ultraaddons-elementor-lite' ),
				'type'           => Controls_Manager::NUMBER,
				'min'            => 1,
				'max'            => 8,
				'default'        => 3,
				'tablet_default' => 2,
				'mobile_default' => 1,
			]
		);

		$this->add_responsive_control(
			'slides_to_scroll',
			[
				'label'          => esc_html__( 'Slides to Scroll', 'ultraaddons-elementor-lite' ),
				'type'           => Controls_Manager::NUMBER,
				'min'            => 1,
				'max'            => 8,
				'default'        => 1,
				'tablet_default' => 1,
				'mobile_default' => 1,
			]
		);

		$this->add_responsive_control(
			'slide_gap',
			[
				'label'          => esc_html__( 'Item Space / Gap (px)', 'ultraaddons-elementor-lite' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => [ 'px' ],
				'range'          => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'default'        => [
					'unit' => 'px',
					'size' => 20,
				],
				'tablet_default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'mobile_default' => [
					'unit' => 'px',
					'size' => 10,
				],
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'        => esc_html__( 'Autoplay', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
				'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label'     => esc_html__( 'Autoplay Speed (ms)', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1000,
				'max'       => 15000,
				'step'      => 500,
				'default'   => 3500,
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'pause_on_hover',
			[
				'label'        => esc_html__( 'Pause on Hover', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
				'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'infinite_loop',
			[
				'label'        => esc_html__( 'Infinite Loop', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
				'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'speed',
			[
				'label'       => esc_html__( 'Transition Speed (ms)', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 200,
				'max'         => 3000,
				'step'        => 100,
				'default'     => 600,
			]
		);

		$this->add_control(
			'grab_cursor',
			[
				'label'        => esc_html__( 'Grab Cursor', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
				'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'heading_navigation',
			[
				'label'     => esc_html__( 'Navigation & Pagination', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'arrows',
			[
				'label'        => esc_html__( 'Navigation Arrows', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'dots',
			[
				'label'        => esc_html__( 'Pagination Dots', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'dots_type',
			[
				'label'     => esc_html__( 'Pagination Dots Type', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'bullets',
				'options'   => [
					'bullets'     => esc_html__( 'Bullets', 'ultraaddons-elementor-lite' ),
					'dynamic'     => esc_html__( 'Dynamic Scaling Bullets', 'ultraaddons-elementor-lite' ),
					'fraction'    => esc_html__( 'Fraction (1 / 5)', 'ultraaddons-elementor-lite' ),
					'progressbar' => esc_html__( 'Progress Bar', 'ultraaddons-elementor-lite' ),
				],
				'condition' => [
					'dots' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * -------------------------------------------------------------
	 * STYLE TAB: Product Card Style
	 * -------------------------------------------------------------
	 */
	protected function register_card_style_controls() {
		$this->start_controls_section(
			'section_style_card',
			[
				'label' => esc_html__( 'Product Card', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'card_padding',
			[
				'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ua-product-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'card_background',
				'label'    => esc_html__( 'Background', 'ultraaddons-elementor-lite' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ua-product-card',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'card_border',
				'label'    => esc_html__( 'Border', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ua-product-card',
			]
		);

		$this->add_responsive_control(
			'card_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ua-product-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_card_shadow' );

		$this->start_controls_tab(
			'tab_card_shadow_normal',
			[
				'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'card_box_shadow',
				'selector' => '{{WRAPPER}} .ua-product-card',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_card_shadow_hover',
			[
				'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'card_box_shadow_hover',
				'selector' => '{{WRAPPER}} .ua-product-card:hover',
			]
		);

		$this->add_control(
			'card_hover_lift',
			[
				'label'        => esc_html__( 'Hover Lift Effect', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
				'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * -------------------------------------------------------------
	 * STYLE TAB: Product Image Style
	 * -------------------------------------------------------------
	 */
	protected function register_image_style_controls() {
		$this->start_controls_section(
			'section_style_image',
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
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ua-product-thumb, {{WRAPPER}} .ua-product-thumb img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'image_hover_zoom',
			[
				'label'        => esc_html__( 'Hover Zoom Effect', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
				'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'image_overlay_color',
			[
				'label'     => esc_html__( 'Hover Overlay Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-product-thumb .ua-thumb-overlay' => 'background: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * -------------------------------------------------------------
	 * STYLE TAB: Content / Details Style
	 * -------------------------------------------------------------
	 */
	protected function register_content_style_controls() {
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content / Details Area', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_alignment',
			[
				'label'     => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
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
					'{{WRAPPER}} .ua-product-details' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label'      => esc_html__( 'Content Padding', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ua-product-details' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * -------------------------------------------------------------
	 * STYLE TAB: Title Style
	 * -------------------------------------------------------------
	 */
	protected function register_title_style_controls() {
		$this->start_controls_section(
			'section_style_title',
			[
				'label'     => esc_html__( 'Product Title', 'ultraaddons-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .ua-product-title, {{WRAPPER}} .ua-product-title a',
			]
		);

		$this->start_controls_tabs( 'tabs_title_color' );

		$this->start_controls_tab(
			'tab_title_normal',
			[
				'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-product-title, {{WRAPPER}} .ua-product-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_title_hover',
			[
				'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
			]
		);

		$this->add_control(
			'title_color_hover',
			[
				'label'     => esc_html__( 'Hover Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-product-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'title_margin',
			[
				'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ua-product-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * -------------------------------------------------------------
	 * STYLE TAB: Price Style
	 * -------------------------------------------------------------
	 */
	protected function register_price_style_controls() {
		$this->start_controls_section(
			'section_style_price',
			[
				'label'     => esc_html__( 'Product Price', 'ultraaddons-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_price' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'price_typography',
				'selector' => '{{WRAPPER}} .ua-product-price, {{WRAPPER}} .ua-product-price span',
			]
		);

		$this->add_control(
			'regular_price_color',
			[
				'label'     => esc_html__( 'Regular Price Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-product-price, {{WRAPPER}} .ua-product-price del' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sale_price_color',
			[
				'label'     => esc_html__( 'Sale Deal Price Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-product-price ins, {{WRAPPER}} .ua-product-price .amount' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'price_margin',
			[
				'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ua-product-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * -------------------------------------------------------------
	 * STYLE TAB: Star Rating Style
	 * -------------------------------------------------------------
	 */
	protected function register_rating_style_controls() {
		$this->start_controls_section(
			'section_style_rating',
			[
				'label'     => esc_html__( 'Star Rating', 'ultraaddons-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_rating' => 'yes',
				],
			]
		);

		$this->add_control(
			'rating_active_color',
			[
				'label'     => esc_html__( 'Filled Star Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffb800',
				'selectors' => [
					'{{WRAPPER}} .ua-star-rating .star-active' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'rating_inactive_color',
			[
				'label'     => esc_html__( 'Empty Star Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#e0e0e0',
				'selectors' => [
					'{{WRAPPER}} .ua-star-rating .star-empty' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'rating_star_size',
			[
				'label'      => esc_html__( 'Star Icon Size (px)', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 8,
						'max' => 30,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 13,
				],
				'selectors'  => [
					'{{WRAPPER}} .ua-star-rating i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'rating_margin',
			[
				'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ua-star-rating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * -------------------------------------------------------------
	 * STYLE TAB: Category & Excerpt Style
	 * -------------------------------------------------------------
	 */
	protected function register_meta_style_controls() {
		$this->start_controls_section(
			'section_style_meta',
			[
				'label' => esc_html__( 'Category & Description', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_category_style',
			[
				'label'     => esc_html__( 'Category Style', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => [
					'show_category' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'category_typography',
				'selector'  => '{{WRAPPER}} .ua-product-category, {{WRAPPER}} .ua-product-category a',
				'condition' => [
					'show_category' => 'yes',
				],
			]
		);

		$this->add_control(
			'category_color',
			[
				'label'     => esc_html__( 'Category Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-product-category, {{WRAPPER}} .ua-product-category a' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_category' => 'yes',
				],
			]
		);

		$this->add_control(
			'heading_excerpt_style',
			[
				'label'     => esc_html__( 'Excerpt / Description Style', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_excerpt' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'excerpt_typography',
				'selector'  => '{{WRAPPER}} .ua-product-excerpt',
				'condition' => [
					'show_excerpt' => 'yes',
				],
			]
		);

		$this->add_control(
			'excerpt_color',
			[
				'label'     => esc_html__( 'Excerpt Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-product-excerpt' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_excerpt' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * -------------------------------------------------------------
	 * STYLE TAB: Badges Style
	 * -------------------------------------------------------------
	 */
	protected function register_badges_style_controls() {
		$this->start_controls_section(
			'section_style_badges',
			[
				'label' => esc_html__( 'Badges', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_sale_badge_style',
			[
				'label' => esc_html__( 'Sale Badge Style', 'ultraaddons-elementor-lite' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'sale_badge_typography',
				'selector' => '{{WRAPPER}} .ua-badge-sale',
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

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'sale_badge_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ua-badge-sale',
			]
		);

		$this->add_responsive_control(
			'sale_badge_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ua-badge-sale' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sale_badge_padding',
			[
				'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ua-badge-sale' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_stockout_badge_style',
			[
				'label'     => esc_html__( 'Stock Out Badge Style', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'stockout_badge_color',
			[
				'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ua-badge-stockout' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'stockout_badge_bg',
			[
				'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ef4444',
				'selectors' => [
					'{{WRAPPER}} .ua-badge-stockout' => 'background: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * -------------------------------------------------------------
	 * STYLE TAB: Action Buttons Style
	 * -------------------------------------------------------------
	 */
	protected function register_buttons_style_controls() {
		$this->start_controls_section(
			'section_style_buttons',
			[
				'label' => esc_html__( 'Action Buttons', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'btn_size',
			[
				'label'      => esc_html__( 'Button Box Size (px)', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 28,
						'max' => 60,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 38,
				],
				'selectors'  => [
					'{{WRAPPER}} .ua-action-btn' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'btn_icon_size',
			[
				'label'      => esc_html__( 'Icon Size (px)', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 10,
						'max' => 30,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 14,
				],
				'selectors'  => [
					'{{WRAPPER}} .ua-action-btn i, {{WRAPPER}} .ua-action-btn svg' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_btn_style' );

		$this->start_controls_tab(
			'tab_btn_normal',
			[
				'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
			]
		);

		$this->add_control(
			'btn_color',
			[
				'label'     => esc_html__( 'Icon / Text Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333333',
				'selectors' => [
					'{{WRAPPER}} .ua-action-btn' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'btn_bg',
			[
				'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ua-action-btn' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'btn_box_shadow',
				'selector' => '{{WRAPPER}} .ua-action-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_btn_hover',
			[
				'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
			]
		);

		$this->add_control(
			'btn_color_hover',
			[
				'label'     => esc_html__( 'Hover Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ua-action-btn:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'btn_bg_hover',
			[
				'label'     => esc_html__( 'Hover Background', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6841fa',
				'selectors' => [
					'{{WRAPPER}} .ua-action-btn:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'btn_box_shadow_hover',
				'selector' => '{{WRAPPER}} .ua-action-btn:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'btn_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ua-action-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * -------------------------------------------------------------
	 * STYLE TAB: Navigation Arrows Style
	 * -------------------------------------------------------------
	 */
	protected function register_arrows_style_controls() {
		$this->start_controls_section(
			'section_style_arrows',
			[
				'label'     => esc_html__( 'Navigation Arrows', 'ultraaddons-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'arrows' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'arrow_size',
			[
				'label'      => esc_html__( 'Arrow Box Size', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 24,
						'max' => 70,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 44,
				],
				'selectors'  => [
					'{{WRAPPER}} .ua-swiper-button-prev, {{WRAPPER}} .ua-swiper-button-next' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'arrow_icon_size',
			[
				'label'      => esc_html__( 'Arrow Icon Size', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 10,
						'max' => 36,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 16,
				],
				'selectors'  => [
					'{{WRAPPER}} .ua-swiper-button-prev i, {{WRAPPER}} .ua-swiper-button-next i, {{WRAPPER}} .ua-swiper-button-prev svg, {{WRAPPER}} .ua-swiper-button-next svg' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_arrow_colors' );

		$this->start_controls_tab(
			'tab_arrow_normal',
			[
				'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
			]
		);

		$this->add_control(
			'arrow_color',
			[
				'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333333',
				'selectors' => [
					'{{WRAPPER}} .ua-swiper-button-prev, {{WRAPPER}} .ua-swiper-button-next' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrow_bg',
			[
				'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ua-swiper-button-prev, {{WRAPPER}} .ua-swiper-button-next' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'arrow_box_shadow',
				'selector' => '{{WRAPPER}} .ua-swiper-button-prev, {{WRAPPER}} .ua-swiper-button-next',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_arrow_hover',
			[
				'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
			]
		);

		$this->add_control(
			'arrow_color_hover',
			[
				'label'     => esc_html__( 'Hover Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ua-swiper-button-prev:hover, {{WRAPPER}} .ua-swiper-button-next:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrow_bg_hover',
			[
				'label'     => esc_html__( 'Hover Background', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6841fa',
				'selectors' => [
					'{{WRAPPER}} .ua-swiper-button-prev:hover, {{WRAPPER}} .ua-swiper-button-next:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'arrow_box_shadow_hover',
				'selector' => '{{WRAPPER}} .ua-swiper-button-prev:hover, {{WRAPPER}} .ua-swiper-button-next:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'arrow_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'    => '50',
					'right'  => '50',
					'bottom' => '50',
					'left'   => '50',
					'unit'   => '%',
				],
				'selectors'  => [
					'{{WRAPPER}} .ua-swiper-button-prev, {{WRAPPER}} .ua-swiper-button-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * -------------------------------------------------------------
	 * STYLE TAB: Pagination Dots Style
	 * -------------------------------------------------------------
	 */
	protected function register_dots_style_controls() {
		$this->start_controls_section(
			'section_style_dots',
			[
				'label'     => esc_html__( 'Pagination Dots', 'ultraaddons-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'dots' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'dots_size',
			[
				'label'      => esc_html__( 'Dot Size (px)', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 4,
						'max' => 20,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 8,
				],
				'selectors'  => [
					'{{WRAPPER}} .ua-swiper-pagination .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'dots_color',
			[
				'label'     => esc_html__( 'Dot Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#d1d5db',
				'selectors' => [
					'{{WRAPPER}} .ua-swiper-pagination .swiper-pagination-bullet' => 'background: {{VALUE}}; opacity: 1;',
				],
			]
		);

		$this->add_control(
			'dots_active_color',
			[
				'label'     => esc_html__( 'Active Dot Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6841fa',
				'selectors' => [
					'{{WRAPPER}} .ua-swiper-pagination .swiper-pagination-bullet-active' => 'background: {{VALUE}};',
					'{{WRAPPER}} .ua-swiper-pagination .swiper-pagination-progressbar-fill' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'dots_active_width',
			[
				'label'      => esc_html__( 'Active Dot Expansion Width (px)', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 8,
						'max' => 40,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 24,
				],
				'selectors'  => [
					'{{WRAPPER}} .ua-swiper-pagination .swiper-pagination-bullet-active' => 'width: {{SIZE}}{{UNIT}}; border-radius: 12px;',
				],
			]
		);

		$this->add_responsive_control(
			'dots_top_spacing',
			[
				'label'      => esc_html__( 'Top Spacing (px)', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors'  => [
					'{{WRAPPER}} .ua-swiper-pagination' => 'margin-top: {{SIZE}}{{UNIT}}; position: relative; bottom: 0;',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Build WP_Query args safely based on user controls
	 *
	 * @return array
	 */
	protected function get_query_args() {
		$settings = $this->get_settings_for_display();

		$posts_per_page = isset( $settings['posts_per_page'] ) && '' !== $settings['posts_per_page'] ? intval( $settings['posts_per_page'] ) : 8;
		$offset         = ! empty( $settings['offset'] ) ? absint( $settings['offset'] ) : 0;
		$orderby        = ! empty( $settings['orderby'] ) ? sanitize_key( $settings['orderby'] ) : 'date';
		$order          = ! empty( $settings['order'] ) ? sanitize_key( $settings['order'] ) : 'DESC';

		$args = [
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'posts_per_page'      => $posts_per_page,
			'ignore_sticky_posts' => 1,
		];

		if ( $offset > 0 ) {
			$args['offset'] = $offset;
		}

		// Manual selection mode
		if ( 'manual' === $settings['product_filter'] && ! empty( $settings['manual_products'] ) ) {
			$args['post__in'] = array_map( 'absint', (array) $settings['manual_products'] );
			$args['orderby']  = 'post__in';
			return $args;
		}

		// Filter modes
		switch ( $settings['product_filter'] ) {
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
				$product_ids_on_sale = wc_get_product_ids_on_sale();
				$args['post__in']    = ! empty( $product_ids_on_sale ) ? $product_ids_on_sale : [ 0 ];
				break;

			case 'top_rated':
				$args['meta_key'] = '_wc_average_rating';
				$args['orderby']  = 'meta_value_num';
				$args['order']    = 'DESC';
				break;

			case 'recent':
			default:
				// Ordering
				switch ( $orderby ) {
					case 'price':
						$args['meta_key'] = '_price';
						$args['orderby']  = 'meta_value_num';
						$args['order']    = $order;
						break;

					case 'popularity':
						$args['meta_key'] = 'total_sales';
						$args['orderby']  = 'meta_value_num';
						$args['order']    = 'DESC';
						break;

					case 'rating':
						$args['meta_key'] = '_wc_average_rating';
						$args['orderby']  = 'meta_value_num';
						$args['order']    = 'DESC';
						break;

					default:
						$args['orderby'] = $orderby;
						$args['order']   = $order;
						break;
				}
				break;
		}

		// Categories filter
		if ( ! empty( $settings['categories'] ) ) {
			$args['tax_query'][] = [
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => (array) $settings['categories'],
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

		// Hide out of stock items
		if ( 'yes' === $settings['hide_out_of_stock'] || 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
			$args['meta_query'][] = [
				'key'     => '_stock_status',
				'value'   => 'instock',
				'compare' => '=',
			];
		}

		return $args;
	}

	/**
	 * Helper: Calculate discount percentage
	 *
	 * @param \WC_Product $product
	 * @return int
	 */
	protected function get_discount_percentage( $product ) {
		if ( ! $product->is_on_sale() ) {
			return 0;
		}

		if ( $product->is_type( 'variable' ) ) {
			$available_variations = $product->get_available_variations();
			$max_percentage       = 0;

			foreach ( $available_variations as $variation ) {
				$regular = floatval( $variation['display_regular_price'] );
				$sale    = floatval( $variation['display_price'] );
				if ( $regular > 0 && $sale < $regular ) {
					$percentage = round( ( ( $regular - $sale ) / $regular ) * 100 );
					if ( $percentage > $max_percentage ) {
						$max_percentage = $percentage;
					}
				}
			}
			return $max_percentage;
		} else {
			$regular = floatval( $product->get_regular_price() );
			$sale    = floatval( $product->get_sale_price() );

			if ( $regular > 0 && $sale > 0 && $sale < $regular ) {
				return round( ( ( $regular - $sale ) / $regular ) * 100 );
			}
		}

		return 0;
	}

	/**
	 * Render widget output
	 */
	protected function render() {
		if ( ! function_exists( 'WC' ) ) {
			echo '<div class="ua-alert-warning">' . esc_html__( 'WooCommerce must be installed and active to use this Product Carousel.', 'ultraaddons-elementor-lite' ) . '</div>';
			return;
		}

		$settings = $this->get_settings_for_display();
		$query_args = $this->get_query_args();
		$query = new \WP_Query( $query_args );

		if ( ! $query->have_posts() ) {
			echo '<div class="ua-no-products-found">' . esc_html__( 'No products found matching your criteria.', 'ultraaddons-elementor-lite' ) . '</div>';
			return;
		}

		$preset = ! empty( $settings['layout_preset'] ) ? sanitize_key( $settings['layout_preset'] ) : 'preset-1';
		$widget_id = $this->get_id();

		// Carousel configuration object for frontend script
		$slider_config = [
			'effect'          => ! empty( $settings['carousel_effect'] ) ? $settings['carousel_effect'] : 'slide',
			'slidesPerView'   => ! empty( $settings['slides_to_show'] ) ? absint( $settings['slides_to_show'] ) : 3,
			'slidesPerViewTablet' => ! empty( $settings['slides_to_show_tablet'] ) ? absint( $settings['slides_to_show_tablet'] ) : 2,
			'slidesPerViewMobile' => ! empty( $settings['slides_to_show_mobile'] ) ? absint( $settings['slides_to_show_mobile'] ) : 1,
			'slidesPerGroup'  => ! empty( $settings['slides_to_scroll'] ) ? absint( $settings['slides_to_scroll'] ) : 1,
			'spaceBetween'    => isset( $settings['slide_gap']['size'] ) ? absint( $settings['slide_gap']['size'] ) : 20,
			'spaceBetweenTablet' => isset( $settings['slide_gap_tablet']['size'] ) ? absint( $settings['slide_gap_tablet']['size'] ) : 15,
			'spaceBetweenMobile' => isset( $settings['slide_gap_mobile']['size'] ) ? absint( $settings['slide_gap_mobile']['size'] ) : 10,
			'autoplay'        => 'yes' === $settings['autoplay'],
			'autoplaySpeed'   => ! empty( $settings['autoplay_speed'] ) ? absint( $settings['autoplay_speed'] ) : 3500,
			'pauseOnHover'    => 'yes' === $settings['pause_on_hover'],
			'loop'            => 'yes' === $settings['infinite_loop'],
			'speed'           => ! empty( $settings['speed'] ) ? absint( $settings['speed'] ) : 600,
			'grabCursor'      => 'yes' === $settings['grab_cursor'],
			'arrows'          => 'yes' === $settings['arrows'],
			'dots'            => 'yes' === $settings['dots'],
			'dotsType'        => ! empty( $settings['dots_type'] ) ? $settings['dots_type'] : 'bullets',
		];

		$wrapper_classes = [
			'ua-product-carousel-wrapper',
			'ua-preset-' . $preset,
			'ua-carousel-' . $widget_id,
		];

		if ( 'yes' === $settings['card_hover_lift'] ) {
			$wrapper_classes[] = 'ua-card-hover-lift';
		}
		if ( 'yes' === $settings['image_hover_zoom'] ) {
			$wrapper_classes[] = 'ua-img-hover-zoom';
		}

		$checkout_url = wc_get_checkout_url();
		?>
		<div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>" data-swiper-config="<?php echo esc_attr( wp_json_encode( $slider_config ) ); ?>">
			<div class="swiper ua-product-carousel-slider ua-swiper-container-<?php echo esc_attr( $widget_id ); ?>">
				<div class="swiper-wrapper">
					<?php
					while ( $query->have_posts() ) :
						$query->the_post();
						$product = wc_get_product( get_the_ID() );
						if ( ! $product ) {
							continue;
						}

						$product_id   = $product->get_id();
						$permalink    = get_permalink( $product_id );
						$title        = get_the_title();
						$is_in_stock  = $product->is_in_stock();
						$is_on_sale   = $product->is_on_sale();
						$discount_pct = $this->get_discount_percentage( $product );

						// Title length truncation
						if ( ! empty( $settings['title_length'] ) ) {
							$title = wp_trim_words( $title, absint( $settings['title_length'] ), '...' );
						}

						// Title Tag
						$title_tag = ! empty( $settings['title_tag'] ) ? sanitize_key( $settings['title_tag'] ) : 'h3';
						?>
						<div <?php wc_product_class( 'swiper-slide ua-product-slide', $product ); ?>>
							<div class="ua-product-card ua-product-<?php echo esc_attr( $preset ); ?>">
								
								<!-- Product Thumbnail Area -->
								<div class="ua-product-thumb">
									<!-- Badges -->
									<div class="ua-product-badges ua-badge-pos-<?php echo esc_attr( ! empty( $settings['sale_badge_pos'] ) ? $settings['sale_badge_pos'] : 'left' ); ?>">
										<?php if ( ! $is_in_stock && 'yes' === $settings['show_stockout_badge'] ) : ?>
											<span class="ua-badge ua-badge-stockout">
												<?php echo esc_html( ! empty( $settings['stockout_badge_text'] ) ? $settings['stockout_badge_text'] : __( 'Sold Out', 'ultraaddons-elementor-lite' ) ); ?>
											</span>
										<?php elseif ( $is_on_sale && 'yes' === $settings['show_sale_badge'] ) : ?>
											<span class="ua-badge ua-badge-sale">
												<?php
												if ( 'percentage' === $settings['sale_badge_type'] && $discount_pct > 0 ) {
													echo '-' . esc_html( $discount_pct ) . '%';
												} else {
													echo esc_html( ! empty( $settings['sale_badge_text'] ) ? $settings['sale_badge_text'] : __( 'Sale!', 'ultraaddons-elementor-lite' ) );
												}
												?>
											</span>
										<?php endif; ?>
									</div>

									<!-- Product Image -->
									<div class="ua-thumb-inner">
										<?php if ( 'yes' === $settings['image_clickable'] ) : ?>
											<a href="<?php echo esc_url( $permalink ); ?>" class="ua-thumb-link">
										<?php endif; ?>

										<?php
										$img_size = ! empty( $settings['image_size_size'] ) ? $settings['image_size_size'] : 'woocommerce_thumbnail';
										echo $product->get_image( $img_size, [ 'loading' => 'lazy', 'class' => 'ua-product-main-img' ] );
										?>

										<?php if ( 'yes' === $settings['image_clickable'] ) : ?>
											</a>
										<?php endif; ?>
										<div class="ua-thumb-overlay"></div>
									</div>

									<!-- Action Buttons for Preset 1 & 2 -->
									<?php if ( in_array( $preset, [ 'preset-1', 'preset-2' ], true ) ) : ?>
										<div class="ua-action-buttons-wrap ua-action-style-<?php echo esc_attr( $preset ); ?>">
											<?php $this->render_action_buttons( $product, $settings, $checkout_url ); ?>
										</div>
									<?php endif; ?>
								</div>

								<!-- Product Details Body -->
								<div class="ua-product-details">
									<!-- Category -->
									<?php if ( 'yes' === $settings['show_category'] ) : ?>
										<div class="ua-product-category">
											<?php
											$categories = get_the_terms( $product_id, 'product_cat' );
											if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
												$cat_links = [];
												foreach ( $categories as $cat ) {
													$cat_links[] = '<a href="' . esc_url( get_term_link( $cat ) ) . '">' . esc_html( $cat->name ) . '</a>';
												}
												echo implode( ', ', array_slice( $cat_links, 0, 2 ) );
											}
											?>
										</div>
									<?php endif; ?>

									<!-- Title -->
									<?php if ( 'yes' === $settings['show_title'] ) : ?>
										<<?php echo esc_attr( $title_tag ); ?> class="ua-product-title">
											<?php if ( 'yes' === $settings['title_clickable'] ) : ?>
												<a href="<?php echo esc_url( $permalink ); ?>">
													<?php echo esc_html( $title ); ?>
												</a>
											<?php else : ?>
												<?php echo esc_html( $title ); ?>
											<?php endif; ?>
										</<?php echo esc_attr( $title_tag ); ?>>
									<?php endif; ?>

									<!-- Star Rating -->
									<?php if ( 'yes' === $settings['show_rating'] ) : ?>
										<?php $this->render_star_rating( $product, $settings ); ?>
									<?php endif; ?>

									<!-- Excerpt -->
									<?php if ( 'yes' === $settings['show_excerpt'] && has_excerpt( $product_id ) ) : ?>
										<div class="ua-product-excerpt">
											<?php echo wp_trim_words( get_the_excerpt( $product_id ), absint( $settings['excerpt_length'] ), '...' ); ?>
										</div>
									<?php endif; ?>

									<!-- Price -->
									<?php if ( 'yes' === $settings['show_price'] ) : ?>
										<div class="ua-product-price">
											<?php echo $product->get_price_html(); ?>
										</div>
									<?php endif; ?>

									<!-- Action Buttons for Preset 3 & 4 (Bottom Bar / Integrated) -->
									<?php if ( in_array( $preset, [ 'preset-3', 'preset-4' ], true ) ) : ?>
										<div class="ua-action-buttons-wrap ua-action-style-<?php echo esc_attr( $preset ); ?>">
											<?php $this->render_action_buttons( $product, $settings, $checkout_url ); ?>
										</div>
									<?php endif; ?>
								</div>

							</div>
						</div>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>

				<!-- Navigation Arrows -->
				<?php if ( 'yes' === $settings['arrows'] ) : ?>
					<div class="ua-swiper-nav-btn ua-swiper-button-prev ua-swiper-prev-<?php echo esc_attr( $widget_id ); ?>" tabindex="0" role="button" aria-label="<?php esc_attr_e( 'Previous slide', 'ultraaddons-elementor-lite' ); ?>">
						<i class="eicon-chevron-left"></i>
					</div>
					<div class="ua-swiper-nav-btn ua-swiper-button-next ua-swiper-next-<?php echo esc_attr( $widget_id ); ?>" tabindex="0" role="button" aria-label="<?php esc_attr_e( 'Next slide', 'ultraaddons-elementor-lite' ); ?>">
						<i class="eicon-chevron-right"></i>
					</div>
				<?php endif; ?>

				<!-- Pagination Dots -->
				<?php if ( 'yes' === $settings['dots'] ) : ?>
					<div class="swiper-pagination ua-swiper-pagination ua-swiper-pagination-<?php echo esc_attr( $widget_id ); ?>"></div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Helper: Render Action Buttons (Add to Cart, Buy Now, Quick View, Details)
	 *
	 * @param \WC_Product $product
	 * @param array $settings
	 * @param string $checkout_url
	 */
	protected function render_action_buttons( $product, $settings, $checkout_url ) {
		$product_id = $product->get_id();
		$permalink  = get_permalink( $product_id );
		?>
		<div class="ua-buttons-group">
			<!-- 1. Add to Cart Button -->
			<?php if ( 'yes' === $settings['show_add_to_cart'] && $product->is_in_stock() ) : ?>
				<?php
				$cart_url  = esc_url( $product->add_to_cart_url() );
				$aria_desc = esc_attr( $product->add_to_cart_description() );
				$is_simple = $product->is_type( 'simple' );
				$ajax_cls  = $is_simple && $product->is_purchasable() ? 'ajax_add_to_cart' : '';
				?>
				<a href="<?php echo $cart_url; ?>"
				   data-quantity="1"
				   class="ua-action-btn ua-btn-add-to-cart button product_type_<?php echo esc_attr( $product->get_type() ); ?> <?php echo esc_attr( $ajax_cls ); ?>"
				   data-product_id="<?php echo esc_attr( $product_id ); ?>"
				   data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>"
				   aria-label="<?php echo $aria_desc; ?>"
				   rel="nofollow"
				   data-tooltip="<?php echo esc_attr( $product->add_to_cart_text() ); ?>"
				   title="<?php echo esc_attr( $product->add_to_cart_text() ); ?>">
					<i class="eicon-cart-medium"></i>
				</a>
			<?php endif; ?>

			<!-- 2. Buy Now (Direct Checkout) -->
			<?php if ( 'yes' === $settings['show_buy_now'] && $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() ) : ?>
				<?php
				$buy_text = ! empty( $settings['buy_now_text'] ) ? $settings['buy_now_text'] : __( 'Buy Now', 'ultraaddons-elementor-lite' );
				$buy_url  = add_query_arg( 'add-to-cart', $product_id, $checkout_url );
				?>
				<a href="<?php echo esc_url( $buy_url ); ?>"
				   class="ua-action-btn ua-btn-buy-now"
				   data-product-id="<?php echo esc_attr( $product_id ); ?>"
				   data-checkout-url="<?php echo esc_url( $checkout_url ); ?>"
				   data-tooltip="<?php echo esc_attr( $buy_text ); ?>"
				   title="<?php echo esc_attr( $buy_text ); ?>"
				   rel="nofollow">
					<i class="eicon-bag-medium"></i>
				</a>
			<?php endif; ?>

			<!-- 3. Quick View Button -->
			<?php if ( 'yes' === $settings['show_quick_view'] ) : ?>
				<a href="<?php echo esc_url( $permalink ); ?>"
				   class="ua-action-btn ua-btn-quick-view open-quick-view-btn"
				   data-product-id="<?php echo esc_attr( $product_id ); ?>"
				   data-tooltip="<?php esc_attr_e( 'Quick View', 'ultraaddons-elementor-lite' ); ?>"
				   title="<?php esc_attr_e( 'Quick View', 'ultraaddons-elementor-lite' ); ?>">
					<i class="eicon-preview-medium"></i>
				</a>
			<?php endif; ?>

			<!-- 4. View Details Link -->
			<?php if ( 'yes' === $settings['show_details'] ) : ?>
				<a href="<?php echo esc_url( $permalink ); ?>"
				   class="ua-action-btn ua-btn-details"
				   data-tooltip="<?php esc_attr_e( 'View Details', 'ultraaddons-elementor-lite' ); ?>"
				   title="<?php esc_attr_e( 'View Details', 'ultraaddons-elementor-lite' ); ?>">
					<i class="eicon-link"></i>
				</a>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Helper: Render Star Rating
	 *
	 * @param \WC_Product $product
	 * @param array $settings
	 */
	protected function render_star_rating( $product, $settings ) {
		$avg_rating   = floatval( $product->get_average_rating() );
		$rating_count = intval( $product->get_rating_count() );
		?>
		<div class="ua-star-rating" title="<?php echo sprintf( esc_attr__( 'Rated %s out of 5', 'ultraaddons-elementor-lite' ), $avg_rating ); ?>">
			<div class="ua-stars-inner">
				<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
					<?php if ( $avg_rating >= $i ) : ?>
						<i class="eicon-star star-active"></i>
					<?php elseif ( $avg_rating >= ( $i - 0.5 ) ) : ?>
						<i class="eicon-star-half star-active"></i>
					<?php else : ?>
						<i class="eicon-star-o star-empty"></i>
					<?php endif; ?>
				<?php endfor; ?>
			</div>
			<?php if ( 'yes' === $settings['show_rating_count'] && $rating_count > 0 ) : ?>
				<?php
				$format_text = ! empty( $settings['rating_format'] ) ? $settings['rating_format'] : '([rating_count])';
				$rendered_count = str_replace(
					[ '[avg_rating]', '[rating_count]' ],
					[ number_format( $avg_rating, 1 ), $rating_count ],
					$format_text
				);
				?>
				<span class="ua-rating-count"><?php echo esc_html( $rendered_count ); ?></span>
			<?php endif; ?>
		</div>
		<?php
	}
}
