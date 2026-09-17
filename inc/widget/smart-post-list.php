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

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * UltraAddons Smart Post List Widget
 *
 * A modern, interactive, and high-performance magazine post list widget for Elementor.
 * Features an interactive Top Bar with live AJAX Category Filtering, Live Search,
 * Top-Bar Prev/Next Navigation, Large Featured Post + Compact List Post layout,
 * 4 design presets, and extensive styling controls.
 *
 * @package UltraAddons
 * @since 1.1.0.9
 * @author UltraAddons Team
 */
class Smart_Post_List extends Base {

	/**
	 * Constructor: Register assets and AJAX hooks
	 *
	 * @param array $data
	 * @param array|null $args
	 */
	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		// Register Smart Post List CSS
		wp_register_style(
			'ultraaddons-smart-post-list',
			ULTRA_ADDONS_ASSETS . 'css/widgets/smart-post-list.css',
			[],
			ULTRA_ADDONS_VERSION
		);

		// Register Smart Post List JS
		wp_register_script(
			'frontend-smart-post-list',
			ULTRA_ADDONS_ASSETS . 'js/frontend-smart-post-list.js',
			[ 'jquery' ],
			ULTRA_ADDONS_VERSION,
			true
		);

		// Localize AJAX config
		wp_localize_script(
			'frontend-smart-post-list',
			'uaSmartPostListConfig',
			[
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'ua-smart-post-list-nonce' ),
				'i18n'     => [
					'loading'   => esc_html__( 'Loading...', 'ultraaddons-elementor-lite' ),
					'no_posts'  => esc_html__( 'No posts found.', 'ultraaddons-elementor-lite' ),
					'min_read'  => esc_html__( '%d min read', 'ultraaddons-elementor-lite' ),
				],
			]
		);

		// Register AJAX endpoints
		add_action( 'wp_ajax_ua_smart_post_list_query', [ __CLASS__, 'ajax_query_posts' ] );
		add_action( 'wp_ajax_nopriv_ua_smart_post_list_query', [ __CLASS__, 'ajax_query_posts' ] );
	}

	/**
	 * Widget name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'ultraaddons-smart-post-list';
	}

	/**
	 * Widget title
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Smart Post List', 'ultraaddons-elementor-lite' );
	}

	/**
	 * Widget icon
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'ultraaddons eicon-post-list';
	}

	/**
	 * Keywords
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'ultraaddons', 'smart', 'post', 'list', 'blog', 'magazine', 'news', 'grid', 'ajax', 'featured' ];
	}

	/**
	 * Categories
	 *
	 * @return array
	 */
	public function get_categories() {
		return [ 'general', 'ultraaddons' ];
	}

	/**
	 * Style dependencies
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array_merge( parent::get_style_depends(), [ 'ultraaddons-smart-post-list' ] );
	}

	/**
	 * Script dependencies
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array_merge( parent::get_script_depends(), [ 'jquery', 'frontend-smart-post-list' ] );
	}

	/**
	 * Helper: Get public content post types (excluding internal templates & builders)
	 *
	 * @return array
	 */
	public function get_post_types_options() {
		$post_types = get_post_types( [ 'public' => true ], 'objects' );
		$options = [];

		$blacklisted = [
			'attachment',
			'elementor_library',
			'elementor-hf',
			'e-landing-page',
			'e-floating-buttons',
			'revision',
			'nav_menu_item',
			'custom_css',
			'customize_changeset',
			'oembed_cache',
			'user_request',
			'wp_block',
			'wp_template',
			'wp_template_part',
			'wp_navigation',
			'wp_global_styles',
			'ultra_mega_menu',
			'header_footer',
			'wpr_templates',
			'wpr_theme_builder',
			'wpr_mega_menu',
			'wpr_floating_elements',
			'wpr_custom_css',
			'elementskit_template',
			'elementskit_widget',
			'happy_template',
			'happy_mega_menu',
			'ha_library',
			'eael_library',
		];

		if ( ! empty( $post_types ) ) {
			foreach ( $post_types as $slug => $post_type ) {
				if ( in_array( $slug, $blacklisted, true ) ) {
					continue;
				}
				if ( preg_match( '/(template|mega_menu|mega-menu|theme_builder|theme-builder|header_footer|header-footer|floating_element)/i', $slug ) ) {
					continue;
				}
				$label = ! empty( $post_type->labels->name ) ? $post_type->labels->name : $slug;
				if ( preg_match( '/(template|theme builder|mega menu|header & footer|floating element)/i', $label ) ) {
					continue;
				}
				$options[ $slug ] = $label;
			}
		}

		return apply_filters( 'ultraaddons/smart_post_list/post_types', $options );
	}

	/**
	 * Helper: Get categories options
	 *
	 * @return array
	 */
	public function get_categories_options() {
		$categories = get_categories( [ 'hide_empty' => true ] );
		$options = [];
		if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
			foreach ( $categories as $cat ) {
				$options[ $cat->term_id ] = $cat->name . ' (' . $cat->count . ')';
			}
		}
		return $options;
	}

	/**
	 * Helper: Get product categories options
	 *
	 * @return array
	 */
	public function get_product_categories_options() {
		$options = [];
		if ( taxonomy_exists( 'product_cat' ) ) {
			$terms = get_terms( [
				'taxonomy'   => 'product_cat',
				'hide_empty' => true,
			] );
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					$options[ $term->term_id ] = $term->name . ' (' . $term->count . ')';
				}
			}
		}
		return $options;
	}

	/**
	 * Helper: Get tags options
	 *
	 * @return array
	 */
	public function get_tags_options() {
		$tags = get_tags( [ 'hide_empty' => true ] );
		$options = [];
		if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) {
			foreach ( $tags as $tag ) {
				$options[ $tag->term_id ] = $tag->name . ' (' . $tag->count . ')';
			}
		}
		return $options;
	}

	/**
	 * Helper: Get authors options
	 *
	 * @return array
	 */
	public function get_authors_options() {
		$users = get_users( [ 'capability' => 'publish_posts' ] );
		$options = [];
		if ( ! empty( $users ) ) {
			foreach ( $users as $user ) {
				$options[ $user->ID ] = $user->display_name;
			}
		}
		return $options;
	}

	/**
	 * Register controls
	 */
	protected function register_controls() {
		// Content Controls
		$this->register_layout_controls();
		$this->register_top_bar_controls();
		$this->register_query_controls();
		$this->register_featured_post_controls();
		$this->register_list_post_controls();

		// Style Controls
		$this->register_top_bar_style_controls();
		$this->register_featured_style_controls();
		$this->register_list_style_controls();
	}

	/**
	 * Content: Layout & Presets
	 */
	protected function register_layout_controls() {
		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout & Presets', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'layout_preset',
			[
				'label'   => esc_html__( 'Design Preset', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'classic-magazine',
				'options' => [
					'classic-magazine' => esc_html__( 'Classic Magazine (Side by Side)', 'ultraaddons-elementor-lite' ),
					'overlay-hero'     => esc_html__( 'Overlay Hero (Cinematic Card)', 'ultraaddons-elementor-lite' ),
					'top-banner'       => esc_html__( 'Top Banner (Featured on Top)', 'ultraaddons-elementor-lite' ),
					'two-column-list'  => esc_html__( 'Two Column Grid List', 'ultraaddons-elementor-lite' ),
				],
			]
		);

		$this->add_control(
			'featured_position',
			[
				'label'     => esc_html__( 'Featured Post Position', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => [
					'left'  => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
					'right' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
				],
				'condition' => [
					'layout_preset' => [ 'classic-magazine', 'overlay-hero' ],
				],
			]
		);

		$this->add_responsive_control(
			'featured_width',
			[
				'label'      => esc_html__( 'Featured Post Width (%)', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range'      => [
					'%' => [
						'min' => 30,
						'max' => 70,
					],
				],
				'default'    => [
					'unit' => '%',
					'size' => 50,
				],
				'condition'  => [
					'layout_preset' => [ 'classic-magazine', 'overlay-hero' ],
				],
				'selectors'  => [
					'{{WRAPPER}} .ua-smart-featured-col' => 'width: {{SIZE}}%;',
					'{{WRAPPER}} .ua-smart-list-col'     => 'width: calc(100% - {{SIZE}}%);',
				],
			]
		);

		$this->add_responsive_control(
			'column_gap',
			[
				'label'      => esc_html__( 'Column Gap (px)', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors'  => [
					'{{WRAPPER}} .ua-smart-posts-body' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Content: Top Bar (Header, Filter, Search, Nav)
	 */
	protected function register_top_bar_controls() {
		$this->start_controls_section(
			'section_top_bar',
			[
				'label' => esc_html__( 'Top Bar & Filters', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_top_bar',
			[
				'label'        => esc_html__( 'Show Top Bar', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'top_bar_title',
			[
				'label'       => esc_html__( 'Section Title', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Trending Stories', 'ultraaddons-elementor-lite' ),
				'placeholder' => esc_html__( 'e.g. Latest News', 'ultraaddons-elementor-lite' ),
				'condition'   => [
					'show_top_bar' => 'yes',
				],
			]
		);

		$this->add_control(
			'top_bar_title_tag',
			[
				'label'     => esc_html__( 'Title Tag', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h3',
				'options'   => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'span' => 'span',
					'div'  => 'div',
				],
				'condition' => [
					'show_top_bar'    => 'yes',
					'top_bar_title!'  => '',
				],
			]
		);

		$this->add_control(
			'show_category_filter',
			[
				'label'        => esc_html__( 'Category Filter Tabs', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
				'condition'    => [
					'show_top_bar' => 'yes',
				],
			]
		);

		$this->add_control(
			'all_filter_label',
			[
				'label'     => esc_html__( '"All" Tab Label', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'All', 'ultraaddons-elementor-lite' ),
				'condition' => [
					'show_top_bar'         => 'yes',
					'show_category_filter' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_live_search',
			[
				'label'        => esc_html__( 'Live Instant Search', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
				'separator'    => 'before',
				'condition'    => [
					'show_top_bar' => 'yes',
				],
			]
		);

		$this->add_control(
			'search_placeholder',
			[
				'label'     => esc_html__( 'Search Placeholder', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Search stories...', 'ultraaddons-elementor-lite' ),
				'condition' => [
					'show_top_bar'     => 'yes',
					'show_live_search' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_navigation',
			[
				'label'        => esc_html__( 'Prev/Next Arrows', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
				'condition'    => [
					'show_top_bar' => 'yes',
				],
			]
		);

		$this->add_control(
			'prev_arrow_icon',
			[
				'label'     => esc_html__( 'Prev Arrow Icon', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'fas fa-chevron-left',
					'library' => 'solid',
				],
				'condition' => [
					'show_top_bar'    => 'yes',
					'show_navigation' => 'yes',
				],
			]
		);

		$this->add_control(
			'next_arrow_icon',
			[
				'label'     => esc_html__( 'Next Arrow Icon', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'fas fa-chevron-right',
					'library' => 'solid',
				],
				'condition' => [
					'show_top_bar'    => 'yes',
					'show_navigation' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Content: Query Settings
	 */
	protected function register_query_controls() {
		$this->start_controls_section(
			'section_query',
			[
				'label' => esc_html__( 'Query Settings', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'post_type',
			[
				'label'   => esc_html__( 'Source / Post Type', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'post',
				'options' => $this->get_post_types_options(),
			]
		);

		$this->add_control(
			'categories',
			[
				'label'       => esc_html__( 'Filter Categories', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => $this->get_categories_options(),
				'default'     => [],
				'multiple'    => true,
				'label_block' => true,
				'condition'   => [
					'post_type' => 'post',
				],
			]
		);

		$this->add_control(
			'product_categories',
			[
				'label'       => esc_html__( 'Filter Product Categories', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => $this->get_product_categories_options(),
				'default'     => [],
				'multiple'    => true,
				'label_block' => true,
				'condition'   => [
					'post_type' => 'product',
				],
			]
		);

		$this->add_control(
			'tags',
			[
				'label'       => esc_html__( 'Filter Tags', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => $this->get_tags_options(),
				'default'     => [],
				'multiple'    => true,
				'label_block' => true,
				'condition'   => [
					'post_type' => 'post',
				],
			]
		);

		$this->add_control(
			'authors',
			[
				'label'       => esc_html__( 'Filter Authors', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => $this->get_authors_options(),
				'default'     => [],
				'multiple'    => true,
				'label_block' => true,
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label'   => esc_html__( 'Posts Per Page', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 2,
				'max'     => 30,
				'step'    => 1,
				'default' => 5,
			]
		);

		$this->add_control(
			'offset',
			[
				'label'   => esc_html__( 'Offset (Skip Posts)', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 0,
				'max'     => 100,
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
					'date'          => esc_html__( 'Date Published', 'ultraaddons-elementor-lite' ),
					'title'         => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
					'comment_count' => esc_html__( 'Comment Count (Popular)', 'ultraaddons-elementor-lite' ),
					'rand'          => esc_html__( 'Random', 'ultraaddons-elementor-lite' ),
					'modified'      => esc_html__( 'Last Modified', 'ultraaddons-elementor-lite' ),
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
					'DESC' => esc_html__( 'Descending (Newest First)', 'ultraaddons-elementor-lite' ),
					'ASC'  => esc_html__( 'Ascending (Oldest First)', 'ultraaddons-elementor-lite' ),
				],
			]
		);

		$this->add_control(
			'exclude_current',
			[
				'label'        => esc_html__( 'Exclude Current Post', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'exclude_ids',
			[
				'label'       => esc_html__( 'Exclude Specific IDs', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'e.g. 102, 145, 203', 'ultraaddons-elementor-lite' ),
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Content: Featured Post Settings
	 */
	protected function register_featured_post_controls() {
		$this->start_controls_section(
			'section_featured_post',
			[
				'label' => esc_html__( 'Featured Post Settings', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_featured',
			[
				'label'        => esc_html__( 'Enable Featured Post', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'featured_show_thumb',
			[
				'label'        => esc_html__( 'Featured Image', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'show_featured' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'featured_thumb_size',
				'default'   => 'medium_large',
				'condition' => [
					'show_featured'       => 'yes',
					'featured_show_thumb' => 'yes',
				],
			]
		);

		$this->add_control(
			'featured_hover_effect',
			[
				'label'     => esc_html__( 'Image Hover Effect', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'effect-zoom',
				'options'   => [
					'effect-zoom'      => esc_html__( 'Zoom In', 'ultraaddons-elementor-lite' ),
					'effect-rotate'    => esc_html__( 'Zoom & Subtle Rotate', 'ultraaddons-elementor-lite' ),
					'effect-grayscale' => esc_html__( 'Grayscale on Hover', 'ultraaddons-elementor-lite' ),
					'none'             => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
				],
				'condition' => [
					'show_featured'       => 'yes',
					'featured_show_thumb' => 'yes',
				],
			]
		);

		$this->add_control(
			'featured_show_badge',
			[
				'label'        => esc_html__( 'Category Badge', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
				'condition'    => [
					'show_featured' => 'yes',
				],
			]
		);

		$this->add_control(
			'featured_show_title',
			[
				'label'        => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'show_featured' => 'yes',
				],
			]
		);

		$this->add_control(
			'featured_title_tag',
			[
				'label'     => esc_html__( 'Title HTML Tag', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h3',
				'options'   => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'span' => 'span',
				],
				'condition' => [
					'show_featured'       => 'yes',
					'featured_show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'featured_show_meta',
			[
				'label'        => esc_html__( 'Meta Information', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
				'condition'    => [
					'show_featured' => 'yes',
				],
			]
		);

		$this->add_control(
			'featured_show_author',
			[
				'label'        => esc_html__( 'Author', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'show_featured'      => 'yes',
					'featured_show_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'featured_show_author_avatar',
			[
				'label'        => esc_html__( 'Author Avatar', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'show_featured'        => 'yes',
					'featured_show_meta'   => 'yes',
					'featured_show_author' => 'yes',
				],
			]
		);

		$this->add_control(
			'featured_show_date',
			[
				'label'        => esc_html__( 'Published Date', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'show_featured'      => 'yes',
					'featured_show_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'featured_show_comments',
			[
				'label'        => esc_html__( 'Comments Count', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'show_featured'      => 'yes',
					'featured_show_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'featured_show_reading_time',
			[
				'label'        => esc_html__( 'Reading Time', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'show_featured'      => 'yes',
					'featured_show_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'featured_show_excerpt',
			[
				'label'        => esc_html__( 'Excerpt / Snippet', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
				'condition'    => [
					'show_featured' => 'yes',
				],
			]
		);

		$this->add_control(
			'featured_excerpt_limit',
			[
				'label'     => esc_html__( 'Excerpt Word Limit', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 5,
				'max'       => 60,
				'default'   => 20,
				'condition' => [
					'show_featured'         => 'yes',
					'featured_show_excerpt' => 'yes',
				],
			]
		);

		$this->add_control(
			'featured_show_read_more',
			[
				'label'        => esc_html__( 'Read More Button', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
				'condition'    => [
					'show_featured' => 'yes',
				],
			]
		);

		$this->add_control(
			'featured_read_more_text',
			[
				'label'     => esc_html__( 'Button Text', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Read Story', 'ultraaddons-elementor-lite' ),
				'condition' => [
					'show_featured'           => 'yes',
					'featured_show_read_more' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Content: List Post Settings
	 */
	protected function register_list_post_controls() {
		$this->start_controls_section(
			'section_list_posts',
			[
				'label' => esc_html__( 'List Posts Settings', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'list_columns',
			[
				'label'   => esc_html__( 'List Columns', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'1' => esc_html__( '1 Column (Standard Vertical Stack)', 'ultraaddons-elementor-lite' ),
					'2' => esc_html__( '2 Columns (Side by Side Grid)', 'ultraaddons-elementor-lite' ),
				],
			]
		);

		$this->add_control(
			'list_show_thumb',
			[
				'label'        => esc_html__( 'Show Thumbnail', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'list_thumb_size',
				'default'   => 'medium',
				'condition' => [
					'list_show_thumb' => 'yes',
				],
			]
		);

		$this->add_control(
			'list_thumb_position',
			[
				'label'     => esc_html__( 'Thumbnail Position', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => [
					'left'  => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
					'right' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
				],
				'condition' => [
					'list_show_thumb' => 'yes',
				],
			]
		);

		$this->add_control(
			'list_show_badge',
			[
				'label'        => esc_html__( 'Category Badge', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'list_show_title',
			[
				'label'        => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'list_title_tag',
			[
				'label'     => esc_html__( 'Title Tag', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h4',
				'options'   => [
					'h3'  => 'H3',
					'h4'  => 'H4',
					'h5'  => 'H5',
					'h6'  => 'H6',
					'p'   => 'p',
					'div' => 'div',
				],
				'condition' => [
					'list_show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'list_title_limit',
			[
				'label'       => esc_html__( 'Title Word Limit', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'max'         => 40,
				'placeholder' => esc_html__( 'Unlimited', 'ultraaddons-elementor-lite' ),
				'condition'   => [
					'list_show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'list_show_meta',
			[
				'label'        => esc_html__( 'Meta Information', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'list_show_date',
			[
				'label'        => esc_html__( 'Published Date', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'list_show_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'list_show_author',
			[
				'label'        => esc_html__( 'Author', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => [
					'list_show_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'list_show_reading_time',
			[
				'label'        => esc_html__( 'Reading Time', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'list_show_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'list_show_excerpt',
			[
				'label'        => esc_html__( 'Excerpt / Snippet', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'list_excerpt_limit',
			[
				'label'     => esc_html__( 'Excerpt Word Limit', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 5,
				'max'       => 40,
				'default'   => 12,
				'condition' => [
					'list_show_excerpt' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Style: Top Bar
	 */
	protected function register_top_bar_style_controls() {
		$this->start_controls_section(
			'section_style_top_bar',
			[
				'label'     => esc_html__( 'Top Bar & Header', 'ultraaddons-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_top_bar' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'top_bar_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ua-smart-top-bar',
			]
		);

		$this->add_responsive_control(
			'top_bar_padding',
			[
				'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ua-smart-top-bar' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'top_bar_margin_bottom',
			[
				'label'      => esc_html__( 'Bottom Spacing (px)', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 24,
				],
				'selectors'  => [
					'{{WRAPPER}} .ua-smart-top-bar' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'top_bar_border',
				'selector' => '{{WRAPPER}} .ua-smart-top-bar',
			]
		);

		// Section Title
		$this->add_control(
			'heading_top_title_style',
			[
				'label'     => esc_html__( 'Section Title', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'top_title_color',
			[
				'label'     => esc_html__( 'Title Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0f172a',
				'selectors' => [
					'{{WRAPPER}} .ua-smart-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'top_title_typography',
				'selector' => '{{WRAPPER}} .ua-smart-title',
			]
		);

		// Category Tabs
		$this->add_control(
			'heading_cat_filter_style',
			[
				'label'     => esc_html__( 'Category Filter Tabs', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_category_filter' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_cat_filter_style' );

		// Tab: Normal
		$this->start_controls_tab(
			'tab_cat_filter_normal',
			[
				'label'     => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
				'condition' => [
					'show_category_filter' => 'yes',
				],
			]
		);

		$this->add_control(
			'cat_tab_color',
			[
				'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#64748b',
				'selectors' => [
					'{{WRAPPER}} .ua-smart-cat-tab' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'cat_tab_bg',
			[
				'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-smart-cat-tab' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		// Tab: Hover
		$this->start_controls_tab(
			'tab_cat_filter_hover',
			[
				'label'     => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
				'condition' => [
					'show_category_filter' => 'yes',
				],
			]
		);

		$this->add_control(
			'cat_tab_hover_color',
			[
				'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#3b82f6',
				'selectors' => [
					'{{WRAPPER}} .ua-smart-cat-tab:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'cat_tab_hover_bg',
			[
				'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-smart-cat-tab:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		// Tab: Active
		$this->start_controls_tab(
			'tab_cat_filter_active',
			[
				'label'     => esc_html__( 'Active', 'ultraaddons-elementor-lite' ),
				'condition' => [
					'show_category_filter' => 'yes',
				],
			]
		);

		$this->add_control(
			'cat_tab_active_color',
			[
				'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ua-smart-cat-tab.active' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'cat_tab_active_bg',
			[
				'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#3b82f6',
				'selectors' => [
					'{{WRAPPER}} .ua-smart-cat-tab.active' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// Navigation Arrows Style
		$this->add_control(
			'heading_nav_arrows_style',
			[
				'label'     => esc_html__( 'Navigation Arrows', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_navigation' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'nav_btn_size',
			[
				'label'      => esc_html__( 'Button Size (px)', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 28,
						'max' => 50,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 34,
				],
				'selectors'  => [
					'{{WRAPPER}} .ua-smart-nav-btn' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'show_navigation' => 'yes',
				],
			]
		);

		$this->add_control(
			'nav_btn_color',
			[
				'label'     => esc_html__( 'Arrow Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#334155',
				'selectors' => [
					'{{WRAPPER}} .ua-smart-nav-btn' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_navigation' => 'yes',
				],
			]
		);

		$this->add_control(
			'nav_btn_bg',
			[
				'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f1f5f9',
				'selectors' => [
					'{{WRAPPER}} .ua-smart-nav-btn' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'show_navigation' => 'yes',
				],
			]
		);

		$this->add_control(
			'nav_btn_hover_color',
			[
				'label'     => esc_html__( 'Hover Arrow Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ua-smart-nav-btn:hover:not(.disabled)' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_navigation' => 'yes',
				],
			]
		);

		$this->add_control(
			'nav_btn_hover_bg',
			[
				'label'     => esc_html__( 'Hover Background Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#3b82f6',
				'selectors' => [
					'{{WRAPPER}} .ua-smart-nav-btn:hover:not(.disabled)' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'show_navigation' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Style: Featured Post
	 */
	protected function register_featured_style_controls() {
		$this->start_controls_section(
			'section_style_featured',
			[
				'label'     => esc_html__( 'Featured Post Card', 'ultraaddons-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_featured' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'featured_card_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ua-smart-featured-card',
			]
		);

		$this->add_responsive_control(
			'featured_card_padding',
			[
				'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ua-smart-featured-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'featured_card_border',
				'selector' => '{{WRAPPER}} .ua-smart-featured-card',
			]
		);

		$this->add_responsive_control(
			'featured_card_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ua-smart-featured-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'featured_card_shadow',
				'selector' => '{{WRAPPER}} .ua-smart-featured-card',
			]
		);

		$this->add_responsive_control(
			'featured_img_height',
			[
				'label'      => esc_html__( 'Image Height (px)', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 160,
						'max' => 500,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ua-smart-featured-card .ua-smart-thumb-wrap' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Featured Title
		$this->add_control(
			'heading_featured_title_style',
			[
				'label'     => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'featured_title_color',
			[
				'label'     => esc_html__( 'Title Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0f172a',
				'selectors' => [
					'{{WRAPPER}} .ua-smart-featured-card .ua-smart-post-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'featured_title_hover_color',
			[
				'label'     => esc_html__( 'Title Hover Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#3b82f6',
				'selectors' => [
					'{{WRAPPER}} .ua-smart-featured-card .ua-smart-post-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'featured_title_typography',
				'selector' => '{{WRAPPER}} .ua-smart-featured-card .ua-smart-post-title',
			]
		);

		// Category Badge
		$this->add_control(
			'heading_featured_badge_style',
			[
				'label'     => esc_html__( 'Category Badge', 'ultraaddons-elementor-lite' ),
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
					'{{WRAPPER}} .ua-smart-featured-card .ua-smart-badge' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'featured_badge_bg',
			[
				'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#3b82f6',
				'selectors' => [
					'{{WRAPPER}} .ua-smart-featured-card .ua-smart-badge' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Style: List Posts
	 */
	protected function register_list_style_controls() {
		$this->start_controls_section(
			'section_style_list',
			[
				'label' => esc_html__( 'List Posts Items', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'list_item_spacing',
			[
				'label'      => esc_html__( 'Item Row Gap (px)', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 8,
						'max' => 45,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors'  => [
					'{{WRAPPER}} .ua-smart-list-wrap' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'list_thumb_width',
			[
				'label'      => esc_html__( 'Thumbnail Width (px)', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 60,
						'max' => 200,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 110,
				],
				'selectors'  => [
					'{{WRAPPER}} .ua-smart-list-item .ua-smart-thumb-wrap' => 'width: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'list_thumb_height',
			[
				'label'      => esc_html__( 'Thumbnail Height (px)', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 50,
						'max' => 160,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 85,
				],
				'selectors'  => [
					'{{WRAPPER}} .ua-smart-list-item .ua-smart-thumb-wrap' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'list_thumb_radius',
			[
				'label'      => esc_html__( 'Thumbnail Radius', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ua-smart-list-item .ua-smart-thumb-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'list_divider_color',
			[
				'label'     => esc_html__( 'Divider Border Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f1f5f9',
				'selectors' => [
					'{{WRAPPER}} .ua-smart-list-item' => 'border-bottom-color: {{VALUE}};',
				],
			]
		);

		// List Title
		$this->add_control(
			'heading_list_title_style',
			[
				'label'     => esc_html__( 'Title Style', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'list_title_color',
			[
				'label'     => esc_html__( 'Title Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#1e293b',
				'selectors' => [
					'{{WRAPPER}} .ua-smart-list-item .ua-smart-post-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'list_title_hover_color',
			[
				'label'     => esc_html__( 'Title Hover Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#3b82f6',
				'selectors' => [
					'{{WRAPPER}} .ua-smart-list-item .ua-smart-post-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'list_title_typography',
				'selector' => '{{WRAPPER}} .ua-smart-list-item .ua-smart-post-title',
			]
		);

		// List Meta
		$this->add_control(
			'heading_list_meta_style',
			[
				'label'     => esc_html__( 'Meta Style', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'list_meta_color',
			[
				'label'     => esc_html__( 'Meta Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#94a3b8',
				'selectors' => [
					'{{WRAPPER}} .ua-smart-list-item .ua-smart-meta' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'list_meta_typography',
				'selector' => '{{WRAPPER}} .ua-smart-list-item .ua-smart-meta',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Build query arguments from widget settings
	 *
	 * @param array $settings
	 * @param int $paged
	 * @param int|null $filter_cat
	 * @param string $search_term
	 * @return array
	 */
	public function build_query_args( $settings, $paged = 1, $filter_cat = null, $search_term = '' ) {
		$post_type   = ! empty( $settings['post_type'] ) ? sanitize_key( $settings['post_type'] ) : 'post';
		$posts_count = ! empty( $settings['posts_per_page'] ) ? max( 1, intval( $settings['posts_per_page'] ) ) : 5;
		$offset      = ! empty( $settings['offset'] ) ? intval( $settings['offset'] ) : 0;
		$orderby     = ! empty( $settings['orderby'] ) ? sanitize_key( $settings['orderby'] ) : 'date';
		$order       = ! empty( $settings['order'] ) ? sanitize_key( $settings['order'] ) : 'DESC';

		$query_args = [
			'post_type'           => $post_type,
			'post_status'         => 'publish',
			'posts_per_page'      => $posts_count,
			'paged'               => $paged,
			'orderby'             => $orderby,
			'order'               => $order,
			'ignore_sticky_posts' => true,
		];

		if ( $offset > 0 && 1 === $paged ) {
			$query_args['offset'] = $offset;
		}

		// Tax Query
		$tax_query = [];

		if ( 'post' === $post_type ) {
			if ( ! empty( $filter_cat ) && $filter_cat > 0 ) {
				$tax_query[] = [
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => [ (int) $filter_cat ],
				];
			} elseif ( ! empty( $settings['categories'] ) ) {
				$tax_query[] = [
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => (array) $settings['categories'],
				];
			}

			if ( ! empty( $settings['tags'] ) ) {
				$tax_query[] = [
					'taxonomy' => 'post_tag',
					'field'    => 'term_id',
					'terms'    => (array) $settings['tags'],
				];
			}
		}

		if ( 'product' === $post_type ) {
			if ( ! empty( $filter_cat ) && $filter_cat > 0 ) {
				$tax_query[] = [
					'taxonomy' => 'product_cat',
					'field'    => 'term_id',
					'terms'    => [ (int) $filter_cat ],
				];
			} elseif ( ! empty( $settings['product_categories'] ) ) {
				$tax_query[] = [
					'taxonomy' => 'product_cat',
					'field'    => 'term_id',
					'terms'    => (array) $settings['product_categories'],
				];
			}
		}

		if ( ! empty( $tax_query ) ) {
			$query_args['tax_query'] = $tax_query;
		}

		// Authors
		if ( ! empty( $settings['authors'] ) ) {
			$query_args['author__in'] = (array) $settings['authors'];
		}

		// Exclusions
		$exclude_ids = [];
		if ( ! empty( $settings['exclude_current'] ) && 'yes' === $settings['exclude_current'] && is_singular() ) {
			$exclude_ids[] = get_the_ID();
		}
		if ( ! empty( $settings['exclude_ids'] ) ) {
			$manual_ids = wp_parse_id_list( $settings['exclude_ids'] );
			$exclude_ids = array_merge( $exclude_ids, $manual_ids );
		}
		if ( ! empty( $exclude_ids ) ) {
			$query_args['post__not_in'] = array_unique( $exclude_ids );
		}

		// Search
		if ( ! empty( $search_term ) ) {
			$query_args['s'] = sanitize_text_field( $search_term );
		}

		return apply_filters( 'ultraaddons/smart_post_list/query_args', $query_args, $settings );
	}

	/**
	 * Render single Featured Post
	 *
	 * @param \WP_Post $post
	 * @param array $settings
	 */
	public function render_featured_post( $post, $settings ) {
		if ( ! $post instanceof \WP_Post ) {
			return;
		}

		$permalink = esc_url( get_permalink( $post->ID ) );
		$post_title = get_the_title( $post->ID );

		// Category Badge
		$cat_name = '';
		$cat_link = '#';
		$categories = get_the_category( $post->ID );
		if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
			$cat_name = $categories[0]->name;
			$cat_link = get_category_link( $categories[0]->term_id );
		} elseif ( 'product' === $post->post_type ) {
			$terms = get_the_terms( $post->ID, 'product_cat' );
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				$cat_name = $terms[0]->name;
				$cat_link = get_term_link( $terms[0] );
			}
		}

		// Reading time calculation
		$content = get_post_field( 'post_content', $post->ID );
		$word_count = str_word_count( strip_tags( $content ) );
		$reading_time = max( 1, (int) ceil( $word_count / 200 ) );

		$show_thumb       = ! empty( $settings['featured_show_thumb'] ) && 'yes' === $settings['featured_show_thumb'];
		$show_badge       = ! empty( $settings['featured_show_badge'] ) && 'yes' === $settings['featured_show_badge'];
		$show_title       = ! empty( $settings['featured_show_title'] ) && 'yes' === $settings['featured_show_title'];
		$title_tag        = ! empty( $settings['featured_title_tag'] ) ? sanitize_key( $settings['featured_title_tag'] ) : 'h3';
		$show_meta        = ! empty( $settings['featured_show_meta'] ) && 'yes' === $settings['featured_show_meta'];
		$show_author      = ! empty( $settings['featured_show_author'] ) && 'yes' === $settings['featured_show_author'];
		$show_avatar      = ! empty( $settings['featured_show_author_avatar'] ) && 'yes' === $settings['featured_show_author_avatar'];
		$show_date        = ! empty( $settings['featured_show_date'] ) && 'yes' === $settings['featured_show_date'];
		$show_comments    = ! empty( $settings['featured_show_comments'] ) && 'yes' === $settings['featured_show_comments'];
		$show_read_time   = ! empty( $settings['featured_show_reading_time'] ) && 'yes' === $settings['featured_show_reading_time'];
		$show_excerpt     = ! empty( $settings['featured_show_excerpt'] ) && 'yes' === $settings['featured_show_excerpt'];
		$excerpt_limit    = ! empty( $settings['featured_excerpt_limit'] ) ? intval( $settings['featured_excerpt_limit'] ) : 20;
		$show_read_more   = ! empty( $settings['featured_show_read_more'] ) && 'yes' === $settings['featured_show_read_more'];
		$read_more_text   = ! empty( $settings['featured_read_more_text'] ) ? $settings['featured_read_more_text'] : esc_html__( 'Read Story', 'ultraaddons-elementor-lite' );
		$hover_effect     = ! empty( $settings['featured_hover_effect'] ) ? sanitize_html_class( $settings['featured_hover_effect'] ) : 'effect-zoom';
		$thumb_size       = ! empty( $settings['featured_thumb_size_size'] ) ? sanitize_key( $settings['featured_thumb_size_size'] ) : 'medium_large';

		?>
		<article class="ua-smart-featured-card">
			<?php if ( $show_thumb ) : ?>
				<div class="ua-smart-thumb-wrap">
					<a href="<?php echo $permalink; ?>" class="ua-smart-thumb-link" aria-label="<?php echo esc_attr( $post_title ); ?>">
						<?php
						if ( has_post_thumbnail( $post->ID ) ) {
							echo get_the_post_thumbnail(
								$post->ID,
								$thumb_size,
								[
									'class'   => 'ua-smart-img ' . esc_attr( $hover_effect ),
									'loading' => 'lazy',
								]
							);
						} else {
							$fallback_img = defined( 'ULTRA_ADDONS_URL' ) ? ULTRA_ADDONS_URL . 'assets/images/no-image.png' : '';
							if ( $fallback_img ) {
								echo '<img src="' . esc_url( $fallback_img ) . '" class="ua-smart-img ' . esc_attr( $hover_effect ) . '" alt="' . esc_attr( $post_title ) . '" loading="lazy">';
							}
						}
						?>
					</a>
					<?php if ( $show_badge && ! empty( $cat_name ) ) : ?>
						<a href="<?php echo esc_url( $cat_link ); ?>" class="ua-smart-badge">
							<?php echo esc_html( $cat_name ); ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="ua-smart-featured-content">
				<?php if ( $show_meta ) : ?>
					<div class="ua-smart-meta">
						<?php if ( $show_author ) : ?>
							<span class="ua-smart-meta-item ua-meta-author">
								<?php if ( $show_avatar ) : ?>
									<?php echo get_avatar( get_the_author_meta( 'ID', $post->post_author ), 20, '', '', [ 'class' => 'ua-smart-avatar' ] ); ?>
								<?php else : ?>
									<i class="far fa-user" aria-hidden="true"></i>
								<?php endif; ?>
								<a href="<?php echo esc_url( get_author_posts_url( $post->post_author ) ); ?>">
									<?php echo esc_html( get_the_author_meta( 'display_name', $post->post_author ) ); ?>
								</a>
							</span>
						<?php endif; ?>

						<?php if ( $show_date ) : ?>
							<span class="ua-smart-meta-item ua-meta-date">
								<i class="far fa-calendar-alt" aria-hidden="true"></i>
								<?php echo esc_html( get_the_date( '', $post->ID ) ); ?>
							</span>
						<?php endif; ?>

						<?php if ( $show_comments ) : ?>
							<span class="ua-smart-meta-item ua-meta-comments">
								<i class="far fa-comment" aria-hidden="true"></i>
								<?php echo esc_html( get_comments_number( $post->ID ) ); ?>
							</span>
						<?php endif; ?>

						<?php if ( $show_read_time ) : ?>
							<span class="ua-smart-meta-item ua-meta-read-time">
								<i class="far fa-clock" aria-hidden="true"></i>
								<?php printf( esc_html__( '%d min read', 'ultraaddons-elementor-lite' ), $reading_time ); ?>
							</span>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( $show_title ) : ?>
					<<?php echo $title_tag; ?> class="ua-smart-post-title">
						<a href="<?php echo $permalink; ?>">
							<?php echo esc_html( $post_title ); ?>
						</a>
					</<?php echo $title_tag; ?>>
				<?php endif; ?>

				<?php if ( $show_excerpt ) : ?>
					<div class="ua-smart-excerpt">
						<?php
						$raw_excerpt = has_excerpt( $post->ID ) ? get_the_excerpt( $post->ID ) : $content;
						echo wp_trim_words( strip_shortcodes( $raw_excerpt ), $excerpt_limit, '...' );
						?>
					</div>
				<?php endif; ?>

				<?php if ( $show_read_more ) : ?>
					<div class="ua-smart-btn-wrap">
						<a href="<?php echo $permalink; ?>" class="ua-smart-btn">
							<span><?php echo esc_html( $read_more_text ); ?></span>
							<i class="fas fa-arrow-right" aria-hidden="true"></i>
						</a>
					</div>
				<?php endif; ?>
			</div>
		</article>
		<?php
	}

	/**
	 * Render single List Post item
	 *
	 * @param \WP_Post $post
	 * @param array $settings
	 * @param int $index
	 */
	public function render_list_post( $post, $settings, $index = 0 ) {
		if ( ! $post instanceof \WP_Post ) {
			return;
		}

		$permalink = esc_url( get_permalink( $post->ID ) );
		$post_title = get_the_title( $post->ID );
		$title_limit = ! empty( $settings['list_title_limit'] ) ? intval( $settings['list_title_limit'] ) : 0;
		if ( $title_limit > 0 ) {
			$post_title = wp_trim_words( $post_title, $title_limit, '...' );
		}

		// Category
		$cat_name = '';
		$cat_link = '#';
		$categories = get_the_category( $post->ID );
		if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
			$cat_name = $categories[0]->name;
			$cat_link = get_category_link( $categories[0]->term_id );
		} elseif ( 'product' === $post->post_type ) {
			$terms = get_the_terms( $post->ID, 'product_cat' );
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				$cat_name = $terms[0]->name;
				$cat_link = get_term_link( $terms[0] );
			}
		}

		// Reading time calculation
		$content = get_post_field( 'post_content', $post->ID );
		$word_count = str_word_count( strip_tags( $content ) );
		$reading_time = max( 1, (int) ceil( $word_count / 200 ) );

		$show_thumb     = ! empty( $settings['list_show_thumb'] ) && 'yes' === $settings['list_show_thumb'];
		$thumb_pos      = ! empty( $settings['list_thumb_position'] ) ? sanitize_key( $settings['list_thumb_position'] ) : 'left';
		$thumb_size     = ! empty( $settings['list_thumb_size_size'] ) ? sanitize_key( $settings['list_thumb_size_size'] ) : 'medium';
		$show_badge     = ! empty( $settings['list_show_badge'] ) && 'yes' === $settings['list_show_badge'];
		$show_title     = ! empty( $settings['list_show_title'] ) && 'yes' === $settings['list_show_title'];
		$title_tag      = ! empty( $settings['list_title_tag'] ) ? sanitize_key( $settings['list_title_tag'] ) : 'h4';
		$show_meta      = ! empty( $settings['list_show_meta'] ) && 'yes' === $settings['list_show_meta'];
		$show_author    = ! empty( $settings['list_show_author'] ) && 'yes' === $settings['list_show_author'];
		$show_date      = ! empty( $settings['list_show_date'] ) && 'yes' === $settings['list_show_date'];
		$show_read_time = ! empty( $settings['list_show_reading_time'] ) && 'yes' === $settings['list_show_reading_time'];
		$show_excerpt   = ! empty( $settings['list_show_excerpt'] ) && 'yes' === $settings['list_show_excerpt'];
		$excerpt_limit  = ! empty( $settings['list_excerpt_limit'] ) ? intval( $settings['list_excerpt_limit'] ) : 12;

		?>
		<article class="ua-smart-list-item thumb-pos-<?php echo esc_attr( $thumb_pos ); ?>" data-post-id="<?php echo esc_attr( $post->ID ); ?>">
			<?php if ( $show_thumb ) : ?>
				<div class="ua-smart-thumb-wrap">
					<a href="<?php echo $permalink; ?>" class="ua-smart-thumb-link" aria-label="<?php echo esc_attr( get_the_title( $post->ID ) ); ?>">
						<?php
						if ( has_post_thumbnail( $post->ID ) ) {
							echo get_the_post_thumbnail(
								$post->ID,
								$thumb_size,
								[
									'class'   => 'ua-smart-img',
									'loading' => 'lazy',
								]
							);
						} else {
							$fallback_img = defined( 'ULTRA_ADDONS_URL' ) ? ULTRA_ADDONS_URL . 'assets/images/no-image.png' : '';
							if ( $fallback_img ) {
								echo '<img src="' . esc_url( $fallback_img ) . '" class="ua-smart-img" alt="' . esc_attr( get_the_title( $post->ID ) ) . '" loading="lazy">';
							}
						}
						?>
					</a>
				</div>
			<?php endif; ?>

			<div class="ua-smart-list-content">
				<?php if ( $show_badge && ! empty( $cat_name ) ) : ?>
					<div class="ua-smart-badge-wrap">
						<a href="<?php echo esc_url( $cat_link ); ?>" class="ua-smart-badge">
							<?php echo esc_html( $cat_name ); ?>
						</a>
					</div>
				<?php endif; ?>

				<?php if ( $show_title ) : ?>
					<<?php echo $title_tag; ?> class="ua-smart-post-title">
						<a href="<?php echo $permalink; ?>">
							<?php echo esc_html( $post_title ); ?>
						</a>
					</<?php echo $title_tag; ?>>
				<?php endif; ?>

				<?php if ( $show_excerpt ) : ?>
					<div class="ua-smart-excerpt">
						<?php
						$raw_excerpt = has_excerpt( $post->ID ) ? get_the_excerpt( $post->ID ) : $content;
						echo wp_trim_words( strip_shortcodes( $raw_excerpt ), $excerpt_limit, '...' );
						?>
					</div>
				<?php endif; ?>

				<?php if ( $show_meta ) : ?>
					<div class="ua-smart-meta">
						<?php if ( $show_author ) : ?>
							<span class="ua-smart-meta-item ua-meta-author">
								<i class="far fa-user" aria-hidden="true"></i>
								<a href="<?php echo esc_url( get_author_posts_url( $post->post_author ) ); ?>">
									<?php echo esc_html( get_the_author_meta( 'display_name', $post->post_author ) ); ?>
								</a>
							</span>
						<?php endif; ?>

						<?php if ( $show_date ) : ?>
							<span class="ua-smart-meta-item ua-meta-date">
								<i class="far fa-calendar-alt" aria-hidden="true"></i>
								<?php echo esc_html( get_the_date( '', $post->ID ) ); ?>
							</span>
						<?php endif; ?>

						<?php if ( $show_read_time ) : ?>
							<span class="ua-smart-meta-item ua-meta-read-time">
								<i class="far fa-clock" aria-hidden="true"></i>
								<?php printf( esc_html__( '%d min read', 'ultraaddons-elementor-lite' ), $reading_time ); ?>
							</span>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</article>
		<?php
	}

	/**
	 * Render Top Bar with Title, Category Filter, Search, and Nav Arrows
	 *
	 * @param array $settings
	 * @param array $categories
	 * @param int $max_pages
	 * @param int $paged
	 */
	public function render_top_bar( $settings, $categories = [], $max_pages = 1, $paged = 1 ) {
		$show_top_bar   = ! empty( $settings['show_top_bar'] ) && 'yes' === $settings['show_top_bar'];
		if ( ! $show_top_bar ) {
			return;
		}

		$title           = ! empty( $settings['top_bar_title'] ) ? $settings['top_bar_title'] : '';
		$title_tag       = ! empty( $settings['top_bar_title_tag'] ) ? sanitize_key( $settings['top_bar_title_tag'] ) : 'h3';
		$show_filter     = ! empty( $settings['show_category_filter'] ) && 'yes' === $settings['show_category_filter'];
		$all_label       = ! empty( $settings['all_filter_label'] ) ? $settings['all_filter_label'] : esc_html__( 'All', 'ultraaddons-elementor-lite' );
		$show_search     = ! empty( $settings['show_live_search'] ) && 'yes' === $settings['show_live_search'];
		$placeholder     = ! empty( $settings['search_placeholder'] ) ? $settings['search_placeholder'] : esc_html__( 'Search stories...', 'ultraaddons-elementor-lite' );
		$show_navigation = ! empty( $settings['show_navigation'] ) && 'yes' === $settings['show_navigation'];

		?>
		<div class="ua-smart-top-bar">
			<div class="ua-smart-top-left">
				<?php if ( ! empty( $title ) ) : ?>
					<<?php echo $title_tag; ?> class="ua-smart-title">
						<?php echo esc_html( $title ); ?>
					</<?php echo $title_tag; ?>>
				<?php endif; ?>
			</div>

			<div class="ua-smart-top-right">
				<?php if ( $show_filter && ! empty( $categories ) ) : ?>
					<nav class="ua-smart-cat-filter" aria-label="<?php esc_attr_e( 'Categories', 'ultraaddons-elementor-lite' ); ?>">
						<button type="button" class="ua-smart-cat-tab active" data-cat-id="0">
							<?php echo esc_html( $all_label ); ?>
						</button>
						<?php foreach ( $categories as $cat_id => $cat_name ) : ?>
							<button type="button" class="ua-smart-cat-tab" data-cat-id="<?php echo esc_attr( $cat_id ); ?>">
								<?php echo esc_html( $cat_name ); ?>
							</button>
						<?php endforeach; ?>
					</nav>
				<?php endif; ?>

				<?php if ( $show_search ) : ?>
					<div class="ua-smart-search-wrap">
						<i class="fas fa-search ua-search-icon" aria-hidden="true"></i>
						<input type="text" class="ua-smart-search-input" placeholder="<?php echo esc_attr( $placeholder ); ?>" autocomplete="off">
						<button type="button" class="ua-smart-search-clear" style="display:none;" aria-label="<?php esc_attr_e( 'Clear Search', 'ultraaddons-elementor-lite' ); ?>">&times;</button>
					</div>
				<?php endif; ?>

				<?php if ( $show_navigation ) : ?>
					<div class="ua-smart-nav-wrap">
						<button type="button" class="ua-smart-nav-btn ua-smart-nav-prev <?php echo ( $paged <= 1 ) ? 'disabled' : ''; ?>" aria-label="<?php esc_attr_e( 'Previous Page', 'ultraaddons-elementor-lite' ); ?>">
							<?php if ( ! empty( $settings['prev_arrow_icon'] ) ) { Icons_Manager::render_icon( $settings['prev_arrow_icon'], [ 'aria-hidden' => 'true' ] ); } else { echo '<i class="fas fa-chevron-left"></i>'; } ?>
						</button>
						<button type="button" class="ua-smart-nav-btn ua-smart-nav-next <?php echo ( $paged >= $max_pages ) ? 'disabled' : ''; ?>" aria-label="<?php esc_attr_e( 'Next Page', 'ultraaddons-elementor-lite' ); ?>">
							<?php if ( ! empty( $settings['next_arrow_icon'] ) ) { Icons_Manager::render_icon( $settings['next_arrow_icon'], [ 'aria-hidden' => 'true' ] ); } else { echo '<i class="fas fa-chevron-right"></i>'; } ?>
						</button>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Main Render
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		// Query posts
		$paged = 1;
		$query_args = $this->build_query_args( $settings, $paged );
		$post_query = new \WP_Query( $query_args );

		if ( ! $post_query->have_posts() ) {
			if ( Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="ua-no-posts-alert" style="padding: 24px; background: #f8fafc; border: 1px dashed #cbd5e1; text-align: center; border-radius: 8px; color: #64748b;">';
				echo esc_html__( 'No posts found matching your criteria.', 'ultraaddons-elementor-lite' );
				echo '</div>';
			}
			return;
		}

		// Retrieve categories for Top Bar filter
		$filter_cats = [];
		if ( ! empty( $settings['show_category_filter'] ) && 'yes' === $settings['show_category_filter'] ) {
			$post_type = ! empty( $settings['post_type'] ) ? $settings['post_type'] : 'post';
			$taxonomy = ( 'product' === $post_type ) ? 'product_cat' : 'category';

			if ( ! empty( $settings['categories'] ) && 'post' === $post_type ) {
				$terms = get_terms( [
					'taxonomy'   => $taxonomy,
					'include'    => (array) $settings['categories'],
					'hide_empty' => true,
				] );
			} elseif ( ! empty( $settings['product_categories'] ) && 'product' === $post_type ) {
				$terms = get_terms( [
					'taxonomy'   => $taxonomy,
					'include'    => (array) $settings['product_categories'],
					'hide_empty' => true,
				] );
			} else {
				$terms = get_terms( [
					'taxonomy'   => $taxonomy,
					'number'     => 6,
					'hide_empty' => true,
				] );
			}

			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					$filter_cats[ $term->term_id ] = $term->name;
				}
			}
		}

		$layout_preset     = ! empty( $settings['layout_preset'] ) ? sanitize_key( $settings['layout_preset'] ) : 'classic-magazine';
		$featured_position = ! empty( $settings['featured_position'] ) ? sanitize_key( $settings['featured_position'] ) : 'left';
		$list_columns      = ! empty( $settings['list_columns'] ) ? intval( $settings['list_columns'] ) : 1;
		$show_featured     = ! empty( $settings['show_featured'] ) && 'yes' === $settings['show_featured'];

		$wrapper_classes = [
			'ua-smart-post-list-wrapper',
			'ua-smart-preset-' . esc_attr( $layout_preset ),
			'featured-pos-' . esc_attr( $featured_position ),
			'list-cols-' . esc_attr( $list_columns ),
		];

		// Pack sanitize settings for AJAX pagination & filtering
		$ajax_settings = [
			'post_type'                   => ! empty( $settings['post_type'] ) ? $settings['post_type'] : 'post',
			'categories'                  => ! empty( $settings['categories'] ) ? (array) $settings['categories'] : [],
			'product_categories'          => ! empty( $settings['product_categories'] ) ? (array) $settings['product_categories'] : [],
			'tags'                        => ! empty( $settings['tags'] ) ? (array) $settings['tags'] : [],
			'authors'                     => ! empty( $settings['authors'] ) ? (array) $settings['authors'] : [],
			'posts_per_page'              => ! empty( $settings['posts_per_page'] ) ? intval( $settings['posts_per_page'] ) : 5,
			'offset'                      => ! empty( $settings['offset'] ) ? intval( $settings['offset'] ) : 0,
			'orderby'                     => ! empty( $settings['orderby'] ) ? $settings['orderby'] : 'date',
			'order'                       => ! empty( $settings['order'] ) ? $settings['order'] : 'DESC',
			'exclude_current'             => ! empty( $settings['exclude_current'] ) ? $settings['exclude_current'] : 'no',
			'exclude_ids'                 => ! empty( $settings['exclude_ids'] ) ? $settings['exclude_ids'] : '',
			'show_featured'               => $show_featured ? 'yes' : 'no',
			'layout_preset'               => $layout_preset,
			'featured_show_thumb'         => ! empty( $settings['featured_show_thumb'] ) ? $settings['featured_show_thumb'] : 'yes',
			'featured_thumb_size_size'    => ! empty( $settings['featured_thumb_size_size'] ) ? $settings['featured_thumb_size_size'] : 'medium_large',
			'featured_hover_effect'       => ! empty( $settings['featured_hover_effect'] ) ? $settings['featured_hover_effect'] : 'effect-zoom',
			'featured_show_badge'         => ! empty( $settings['featured_show_badge'] ) ? $settings['featured_show_badge'] : 'yes',
			'featured_show_title'         => ! empty( $settings['featured_show_title'] ) ? $settings['featured_show_title'] : 'yes',
			'featured_title_tag'          => ! empty( $settings['featured_title_tag'] ) ? $settings['featured_title_tag'] : 'h3',
			'featured_show_meta'          => ! empty( $settings['featured_show_meta'] ) ? $settings['featured_show_meta'] : 'yes',
			'featured_show_author'        => ! empty( $settings['featured_show_author'] ) ? $settings['featured_show_author'] : 'yes',
			'featured_show_author_avatar' => ! empty( $settings['featured_show_author_avatar'] ) ? $settings['featured_show_author_avatar'] : 'yes',
			'featured_show_date'          => ! empty( $settings['featured_show_date'] ) ? $settings['featured_show_date'] : 'yes',
			'featured_show_comments'      => ! empty( $settings['featured_show_comments'] ) ? $settings['featured_show_comments'] : 'yes',
			'featured_show_reading_time'  => ! empty( $settings['featured_show_reading_time'] ) ? $settings['featured_show_reading_time'] : 'yes',
			'featured_show_excerpt'       => ! empty( $settings['featured_show_excerpt'] ) ? $settings['featured_show_excerpt'] : 'yes',
			'featured_excerpt_limit'      => ! empty( $settings['featured_excerpt_limit'] ) ? intval( $settings['featured_excerpt_limit'] ) : 20,
			'featured_show_read_more'     => ! empty( $settings['featured_show_read_more'] ) ? $settings['featured_show_read_more'] : 'yes',
			'featured_read_more_text'     => ! empty( $settings['featured_read_more_text'] ) ? $settings['featured_read_more_text'] : esc_html__( 'Read Story', 'ultraaddons-elementor-lite' ),
			'list_columns'                => $list_columns,
			'list_show_thumb'             => ! empty( $settings['list_show_thumb'] ) ? $settings['list_show_thumb'] : 'yes',
			'list_thumb_size_size'        => ! empty( $settings['list_thumb_size_size'] ) ? $settings['list_thumb_size_size'] : 'medium',
			'list_thumb_position'         => ! empty( $settings['list_thumb_position'] ) ? $settings['list_thumb_position'] : 'left',
			'list_show_badge'             => ! empty( $settings['list_show_badge'] ) ? $settings['list_show_badge'] : 'yes',
			'list_show_title'             => ! empty( $settings['list_show_title'] ) ? $settings['list_show_title'] : 'yes',
			'list_title_tag'              => ! empty( $settings['list_title_tag'] ) ? $settings['list_title_tag'] : 'h4',
			'list_title_limit'            => ! empty( $settings['list_title_limit'] ) ? intval( $settings['list_title_limit'] ) : 0,
			'list_show_meta'              => ! empty( $settings['list_show_meta'] ) ? $settings['list_show_meta'] : 'yes',
			'list_show_author'            => ! empty( $settings['list_show_author'] ) ? $settings['list_show_author'] : 'no',
			'list_show_date'              => ! empty( $settings['list_show_date'] ) ? $settings['list_show_date'] : 'yes',
			'list_show_reading_time'      => ! empty( $settings['list_show_reading_time'] ) ? $settings['list_show_reading_time'] : 'yes',
			'list_show_excerpt'           => ! empty( $settings['list_show_excerpt'] ) ? $settings['list_show_excerpt'] : 'no',
			'list_excerpt_limit'          => ! empty( $settings['list_excerpt_limit'] ) ? intval( $settings['list_excerpt_limit'] ) : 12,
		];

		$all_posts = $post_query->posts;
		$featured_post = null;
		$list_posts = [];

		if ( $show_featured && ! empty( $all_posts ) ) {
			$featured_post = array_shift( $all_posts );
			$list_posts = $all_posts;
		} else {
			$list_posts = $all_posts;
		}

		?>
		<div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>"
			data-settings="<?php echo esc_attr( wp_json_encode( $ajax_settings ) ); ?>"
			data-paged="1"
			data-max-pages="<?php echo esc_attr( $post_query->max_num_pages ); ?>"
			data-cat-id="0">

			<?php $this->render_top_bar( $settings, $filter_cats, $post_query->max_num_pages, 1 ); ?>

			<div class="ua-smart-posts-body">
				<?php if ( $show_featured && $featured_post ) : ?>
					<div class="ua-smart-featured-col">
						<?php $this->render_featured_post( $featured_post, $settings ); ?>
					</div>
				<?php endif; ?>

				<div class="ua-smart-list-col <?php echo ( ! $show_featured || ! $featured_post ) ? 'full-width' : ''; ?>">
					<div class="ua-smart-list-wrap">
						<?php
						if ( ! empty( $list_posts ) ) {
							foreach ( $list_posts as $idx => $post_item ) {
								$this->render_list_post( $post_item, $settings, $idx );
							}
						}
						?>
					</div>
				</div>
			</div>

			<div class="ua-smart-loading" style="display: none;">
				<div class="ua-smart-spinner"></div>
			</div>

		</div>
		<?php
		wp_reset_postdata();
	}

	/**
	 * AJAX Handler for Category Filtering, Search, and Page Navigation
	 */
	public static function ajax_query_posts() {
		check_ajax_referer( 'ua-smart-post-list-nonce', 'nonce' );

		$paged       = ! empty( $_POST['paged'] ) ? max( 1, intval( $_POST['paged'] ) ) : 1;
		$category_id = isset( $_POST['category_id'] ) ? intval( $_POST['category_id'] ) : 0;
		$search_term = ! empty( $_POST['search_term'] ) ? sanitize_text_field( wp_unslash( $_POST['search_term'] ) ) : '';
		$raw_settings = ! empty( $_POST['settings'] ) ? wp_unslash( $_POST['settings'] ) : '{}';

		$settings = json_decode( $raw_settings, true );
		if ( ! is_array( $settings ) ) {
			$settings = [];
		}

		$widget = new self();
		$query_args = $widget->build_query_args( $settings, $paged, $category_id, $search_term );
		$query = new \WP_Query( $query_args );

		$show_featured = ! empty( $settings['show_featured'] ) && 'yes' === $settings['show_featured'];
		$all_posts = $query->posts;

		$featured_post = null;
		$list_posts = [];

		if ( $show_featured && ! empty( $all_posts ) ) {
			$featured_post = array_shift( $all_posts );
			$list_posts = $all_posts;
		} else {
			$list_posts = $all_posts;
		}

		// Render Featured Post HTML
		ob_start();
		if ( $show_featured && $featured_post ) {
			$widget->render_featured_post( $featured_post, $settings );
		}
		$html_featured = ob_get_clean();

		// Render List Posts HTML
		ob_start();
		if ( ! empty( $list_posts ) ) {
			foreach ( $list_posts as $idx => $post_item ) {
				$widget->render_list_post( $post_item, $settings, $idx );
			}
		} elseif ( ! $featured_post ) {
			echo '<div class="ua-smart-no-results" style="padding: 30px; text-align: center; color: #64748b; font-size: 14px;">' . esc_html__( 'No posts found.', 'ultraaddons-elementor-lite' ) . '</div>';
		}
		$html_list = ob_get_clean();

		wp_reset_postdata();

		wp_send_json_success( [
			'html_featured' => $html_featured,
			'html_list'     => $html_list,
			'paged'         => $paged,
			'max_pages'     => $query->max_num_pages,
			'has_prev'      => ( $paged > 1 ),
			'has_next'      => ( $paged < $query->max_num_pages ),
			'total_posts'   => $query->found_posts,
		] );
	}
}
