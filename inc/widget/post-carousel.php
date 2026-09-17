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
 * UltraAddons Post Carousel Widget
 *
 * A modern, responsive, and customizable post slider & carousel widget for Elementor.
 * Features 4 design presets (Classic, Overlay, Magazine, Minimal), flexible query options,
 * Swiper-powered slider controls, and extensive typography & styling settings.
 *
 * @package UltraAddons
 * @since 1.1.0.9
 * @author UltraAddons Team
 */
class Post_Carousel extends Base {

	/**
	 * Constructor: Register required assets
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

		// Register post carousel frontend script
		wp_register_script(
			'frontend-post-carousel',
			ULTRA_ADDONS_ASSETS . 'js/frontend-post-carousel.js',
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

		// Register post carousel CSS
		wp_register_style(
			'ultraaddons-post-carousel',
			ULTRA_ADDONS_ASSETS . 'css/widgets/post-carousel.css',
			[ 'swiper' ],
			ULTRA_ADDONS_VERSION
		);
	}

	/**
	 * Widget title
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Post Carousel', 'ultraaddons-elementor-lite' );
	}

	/**
	 * Widget icon
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'ultraaddons eicon-posts-carousel';
	}

	/**
	 * Keywords
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'ultraaddons-elementor-lite', 'ua', 'post', 'carousel', 'slider', 'blog', 'news', 'magazine', 'swiper' ];
	}

	/**
	 * Style dependencies
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array_merge( parent::get_style_depends(), [ 'swiper', 'ultraaddons-post-carousel' ] );
	}

	/**
	 * Script dependencies
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array_merge( parent::get_script_depends(), [ 'jquery', 'swiper', 'frontend-post-carousel' ] );
	}

	/**
	/**
	 * Helper: Get public content post types (excluding internal templates & builders)
	 *
	 * @return array
	 */
	public function get_post_types_options() {
		$post_types = get_post_types( [ 'public' => true ], 'objects' );
		$options = [];

		// Blacklist of internal, builder, and template post types
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
			// UltraAddons internals
			'ultra_mega_menu',
			'header_footer',
			// Royal Addons internals
			'wpr_templates',
			'wpr_theme_builder',
			'wpr_mega_menu',
			'wpr_floating_elements',
			'wpr_custom_css',
			// Other popular addons internals
			'elementskit_template',
			'elementskit_widget',
			'happy_template',
			'happy_mega_menu',
			'ha_library',
			'eael_library',
		];

		if ( ! empty( $post_types ) ) {
			foreach ( $post_types as $slug => $post_type ) {
				// 1. Skip explicitly blacklisted slugs
				if ( in_array( $slug, $blacklisted, true ) ) {
					continue;
				}

				// 2. Skip any builder / template / menu patterns in slug
				if ( preg_match( '/(template|mega_menu|mega-menu|theme_builder|theme-builder|header_footer|header-footer|floating_element)/i', $slug ) ) {
					continue;
				}

				// 3. Skip template patterns in label
				$label = ! empty( $post_type->labels->name ) ? $post_type->labels->name : $slug;
				if ( preg_match( '/(template|theme builder|mega menu|header & footer|floating element)/i', $label ) ) {
					continue;
				}

				$options[ $slug ] = $label;
			}
		}

		return apply_filters( 'ultraaddons/post_carousel/post_types', $options );
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
		$this->register_layout_controls();
		$this->register_query_controls();
		$this->register_carousel_controls();

		$this->register_card_style_controls();
		$this->register_image_style_controls();
		$this->register_badge_style_controls();
		$this->register_title_style_controls();
		$this->register_meta_style_controls();
		$this->register_excerpt_style_controls();
		$this->register_button_style_controls();
		$this->register_navigation_style_controls();
		$this->register_pagination_style_controls();
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
				'default' => 'classic',
				'options' => [
					'classic'  => esc_html__( 'Classic Card', 'ultraaddons-elementor-lite' ),
					'overlay'  => esc_html__( 'Overlay / Hero', 'ultraaddons-elementor-lite' ),
					'magazine' => esc_html__( 'Magazine Style', 'ultraaddons-elementor-lite' ),
					'minimal'  => esc_html__( 'Minimal / Clean', 'ultraaddons-elementor-lite' ),
				],
			]
		);

		$this->add_control(
			'header_title',
			[
				'label'       => esc_html__( 'Section Title', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'e.g. Latest News & Articles', 'ultraaddons-elementor-lite' ),
				'label_block' => true,
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'header_title_tag',
			[
				'label'     => esc_html__( 'Section Title Tag', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h3',
				'options'   => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'div'  => 'div',
					'span' => 'span',
				],
				'condition' => [
					'header_title!' => '',
				],
			]
		);

		$this->add_control(
			'show_thumb',
			[
				'label'        => esc_html__( 'Featured Image', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'thumb_size',
				'default'   => 'medium_large',
				'condition' => [
					'show_thumb' => 'yes',
				],
			]
		);

		$this->add_control(
			'thumb_hover_effect',
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
					'show_thumb' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_badge',
			[
				'label'        => esc_html__( 'Category Badge', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'show_title',
			[
				'label'        => esc_html__( 'Post Title', 'ultraaddons-elementor-lite' ),
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
				'label'     => esc_html__( 'Title Tag', 'ultraaddons-elementor-lite' ),
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
					'p'    => 'p',
				],
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'title_limit',
			[
				'label'       => esc_html__( 'Title Word Limit', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'max'         => 50,
				'placeholder' => esc_html__( 'Unlimited', 'ultraaddons-elementor-lite' ),
				'condition'   => [
					'show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_meta',
			[
				'label'        => esc_html__( 'Post Meta Info', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'show_author',
			[
				'label'        => esc_html__( 'Show Author', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'show_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_author_avatar',
			[
				'label'        => esc_html__( 'Author Avatar', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'show_meta'   => 'yes',
					'show_author' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_date',
			[
				'label'        => esc_html__( 'Show Date', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'show_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_comments',
			[
				'label'        => esc_html__( 'Show Comments Count', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => [
					'show_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_reading_time',
			[
				'label'        => esc_html__( 'Show Reading Time', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => [
					'show_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_excerpt',
			[
				'label'        => esc_html__( 'Excerpt / Summary', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'excerpt_limit',
			[
				'label'     => esc_html__( 'Excerpt Words', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 5,
				'max'       => 100,
				'default'   => 16,
				'condition' => [
					'show_excerpt' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_read_more',
			[
				'label'        => esc_html__( 'Read More Button', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'read_more_text',
			[
				'label'     => esc_html__( 'Button Text', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Read More', 'ultraaddons-elementor-lite' ),
				'condition' => [
					'show_read_more' => 'yes',
				],
			]
		);

		$this->add_control(
			'read_more_icon',
			[
				'label'     => esc_html__( 'Button Icon', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'fas fa-arrow-right',
					'library' => 'solid',
				],
				'condition' => [
					'show_read_more' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Content: Query Filter Controls
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
				'label'   => esc_html__( 'Post Type', 'ultraaddons-elementor-lite' ),
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
				'multiple'    => true,
				'options'     => $this->get_categories_options(),
				'label_block' => true,
				'condition'   => [
					'post_type' => 'post',
				],
			]
		);

		$this->add_control(
			'tags',
			[
				'label'       => esc_html__( 'Filter Tags', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => $this->get_tags_options(),
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
				'multiple'    => true,
				'options'     => $this->get_product_categories_options(),
				'label_block' => true,
				'condition'   => [
					'post_type' => 'product',
				],
			]
		);

		$this->add_control(
			'authors',
			[
				'label'       => esc_html__( 'Filter Authors', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => $this->get_authors_options(),
				'label_block' => true,
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label'   => esc_html__( 'Posts Count', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 6,
				'min'     => 1,
				'max'     => 50,
			]
		);

		$this->add_control(
			'offset',
			[
				'label'   => esc_html__( 'Offset (Skip Posts)', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 0,
				'min'     => 0,
				'max'     => 50,
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'   => esc_html__( 'Order By', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date'          => esc_html__( 'Date', 'ultraaddons-elementor-lite' ),
					'title'         => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
					'modified'      => esc_html__( 'Last Modified', 'ultraaddons-elementor-lite' ),
					'rand'          => esc_html__( 'Random', 'ultraaddons-elementor-lite' ),
					'comment_count' => esc_html__( 'Comment Count', 'ultraaddons-elementor-lite' ),
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
			'exclude_current',
			[
				'label'        => esc_html__( 'Exclude Current Post', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => esc_html__( 'Prevents displaying the post you are currently viewing.', 'ultraaddons-elementor-lite' ),
			]
		);

		$this->add_control(
			'exclude_ids',
			[
				'label'       => esc_html__( 'Exclude Post IDs', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( '12, 45, 89', 'ultraaddons-elementor-lite' ),
				'description' => esc_html__( 'Separate IDs with comma.', 'ultraaddons-elementor-lite' ),
				'label_block' => true,
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Content: Carousel Settings
	 */
	protected function register_carousel_controls() {
		$this->start_controls_section(
			'section_carousel',
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
					'coverflow' => esc_html__( 'Coverflow (3D)', 'ultraaddons-elementor-lite' ),
					'fade'      => esc_html__( 'Fade (Single Item)', 'ultraaddons-elementor-lite' ),
				],
			]
		);

		$this->add_responsive_control(
			'slides_per_view',
			[
				'label'          => esc_html__( 'Visible Posts per View', 'ultraaddons-elementor-lite' ),
				'type'           => Controls_Manager::NUMBER,
				'min'            => 1,
				'max'            => 6,
				'default'        => 3,
				'tablet_default' => 2,
				'mobile_default' => 1,
				'condition'      => [
					'carousel_effect!' => 'fade',
				],
			]
		);

		$this->add_responsive_control(
			'space_between',
			[
				'label'          => esc_html__( 'Space Between Posts (px)', 'ultraaddons-elementor-lite' ),
				'type'           => Controls_Manager::NUMBER,
				'min'            => 0,
				'max'            => 60,
				'default'        => 24,
				'tablet_default' => 18,
				'mobile_default' => 12,
				'condition'      => [
					'carousel_effect!' => 'fade',
				],
			]
		);

		$this->add_control(
			'slides_per_group',
			[
				'label'   => esc_html__( 'Slides to Scroll', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'1' => esc_html__( '1 Slide at a Time', 'ultraaddons-elementor-lite' ),
					'2' => esc_html__( '2 Slides at a Time', 'ultraaddons-elementor-lite' ),
					'3' => esc_html__( '3 Slides at a Time', 'ultraaddons-elementor-lite' ),
				],
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'        => esc_html__( 'Autoplay', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'autoplay_delay',
			[
				'label'     => esc_html__( 'Autoplay Delay (ms)', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 3500,
				'min'       => 1000,
				'max'       => 15000,
				'step'      => 500,
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
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'loop',
			[
				'label'        => esc_html__( 'Infinite Loop', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'grab_cursor',
			[
				'label'        => esc_html__( 'Grab Cursor', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'show_arrows',
			[
				'label'        => esc_html__( 'Navigation Arrows', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'arrow_position',
			[
				'label'     => esc_html__( 'Arrows Position', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'sides',
				'options'   => [
					'sides'       => esc_html__( 'Sides (Floating Inside)', 'ultraaddons-elementor-lite' ),
					'sides-outer' => esc_html__( 'Sides (Outside with Padding)', 'ultraaddons-elementor-lite' ),
					'top-right'   => esc_html__( 'Top Right (Inline Header)', 'ultraaddons-elementor-lite' ),
					'bottom'      => esc_html__( 'Bottom Centered', 'ultraaddons-elementor-lite' ),
				],
				'condition' => [
					'show_arrows' => 'yes',
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
					'show_arrows' => 'yes',
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
					'show_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_dots',
			[
				'label'        => esc_html__( 'Pagination / Dots', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'pagination_type',
			[
				'label'     => esc_html__( 'Pagination Type', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'bullets',
				'options'   => [
					'bullets'     => esc_html__( 'Bullets / Dots', 'ultraaddons-elementor-lite' ),
					'fraction'    => esc_html__( 'Fraction (1 / 6)', 'ultraaddons-elementor-lite' ),
					'progressbar' => esc_html__( 'Progress Bar', 'ultraaddons-elementor-lite' ),
				],
				'condition' => [
					'show_dots' => 'yes',
				],
			]
		);

		$this->add_control(
			'dynamic_bullets',
			[
				'label'        => esc_html__( 'Dynamic Scaling Bullets', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => [
					'show_dots'       => 'yes',
					'pagination_type' => 'bullets',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Style: Card Item
	 */
	protected function register_card_style_controls() {
		$this->start_controls_section(
			'section_style_card',
			[
				'label' => esc_html__( 'Post Card', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'card_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ua-post-card',
			]
		);

		$this->add_responsive_control(
			'card_padding',
			[
				'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ua-post-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'card_border',
				'selector' => '{{WRAPPER}} .ua-post-card',
			]
		);

		$this->add_responsive_control(
			'card_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ua-post-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'card_box_shadow',
				'selector' => '{{WRAPPER}} .ua-post-card',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'card_hover_box_shadow',
				'label'    => esc_html__( 'Hover Box Shadow', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ua-post-card:hover',
			]
		);

		$this->add_responsive_control(
			'card_height_overlay',
			[
				'label'      => esc_html__( 'Card Height', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range'      => [
					'px' => [
						'min'  => 280,
						'max'  => 750,
						'step' => 5,
					],
					'vh' => [
						'min' => 20,
						'max' => 90,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 440,
				],
				'condition'  => [
					'layout_preset' => 'overlay',
				],
				'selectors'  => [
					'{{WRAPPER}} .ua-preset-overlay .ua-post-card' => 'min-height: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'card_overlay_gradient',
				'label'     => esc_html__( 'Overlay Gradient', 'ultraaddons-elementor-lite' ),
				'types'     => [ 'classic', 'gradient' ],
				'condition' => [
					'layout_preset' => 'overlay',
				],
				'selector'  => '{{WRAPPER}} .ua-preset-overlay .ua-post-card::after',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Style: Featured Image
	 */
	protected function register_image_style_controls() {
		$this->start_controls_section(
			'section_style_image',
			[
				'label' => esc_html__( 'Featured Image', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'image_height',
			[
				'label'      => esc_html__( 'Image Height (px)', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range'      => [
					'px' => [
						'min' => 120,
						'max' => 600,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ua-post-thumb-wrap' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ua-post-thumb-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Style: Category Badge
	 */
	protected function register_badge_style_controls() {
		$this->start_controls_section(
			'section_style_badge',
			[
				'label' => esc_html__( 'Category Badge', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'badge_typography',
				'selector' => '{{WRAPPER}} .ua-post-badge',
			]
		);

		$this->add_control(
			'badge_color',
			[
				'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-post-badge' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'badge_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-post-badge' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'badge_padding',
			[
				'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ua-post-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'badge_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ua-post-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Style: Title
	 */
	protected function register_title_style_controls() {
		$this->start_controls_section(
			'section_style_title',
			[
				'label' => esc_html__( 'Post Title', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .ua-post-title',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Title Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-post-title, {{WRAPPER}} .ua-post-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label'     => esc_html__( 'Title Hover Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-post-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ua-post-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Style: Meta Information
	 */
	protected function register_meta_style_controls() {
		$this->start_controls_section(
			'section_style_meta',
			[
				'label' => esc_html__( 'Post Meta', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'meta_typography',
				'selector' => '{{WRAPPER}} .ua-post-meta',
			]
		);

		$this->add_control(
			'meta_color',
			[
				'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-post-meta, {{WRAPPER}} .ua-post-meta-item a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'meta_icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-post-meta-item i, {{WRAPPER}} .ua-post-meta-item svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'meta_hover_color',
			[
				'label'     => esc_html__( 'Hover Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-post-meta-item a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'meta_margin',
			[
				'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ua-post-meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Style: Excerpt
	 */
	protected function register_excerpt_style_controls() {
		$this->start_controls_section(
			'section_style_excerpt',
			[
				'label' => esc_html__( 'Excerpt / Summary', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'excerpt_typography',
				'selector' => '{{WRAPPER}} .ua-post-excerpt',
			]
		);

		$this->add_control(
			'excerpt_color',
			[
				'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-post-excerpt' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'excerpt_margin',
			[
				'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ua-post-excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Style: Read More Button
	 */
	protected function register_button_style_controls() {
		$this->start_controls_section(
			'section_style_button',
			[
				'label' => esc_html__( 'Read More Button', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .ua-post-readmore-btn',
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		// Normal
		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
			]
		);

		$this->add_control(
			'button_color',
			[
				'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-post-readmore-btn' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-post-readmore-btn' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		// Hover
		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-post-readmore-btn:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-post-readmore-btn:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'button_padding',
			[
				'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'separator'  => 'before',
				'selectors'  => [
					'{{WRAPPER}} .ua-post-readmore-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ua-post-readmore-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Style: Navigation Arrows
	 */
	protected function register_navigation_style_controls() {
		$this->start_controls_section(
			'section_style_nav',
			[
				'label'     => esc_html__( 'Navigation Arrows', 'ultraaddons-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_arrows' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'arrow_size',
			[
				'label'      => esc_html__( 'Button Box Size (px)', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 28,
						'max' => 70,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ua-carousel-nav-btn' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'arrow_icon_size',
			[
				'label'      => esc_html__( 'Icon Size (px)', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 10,
						'max' => 32,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ua-carousel-nav-btn i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ua-carousel-nav-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'arrow_vertical_pos',
			[
				'label'      => esc_html__( 'Vertical Position', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range'      => [
					'%'  => [
						'min' => 5,
						'max' => 95,
					],
					'px' => [
						'min' => 20,
						'max' => 600,
					],
				],
				'default'    => [
					'unit' => '%',
					'size' => 40,
				],
				'condition'  => [
					'show_arrows'     => 'yes',
					'arrow_position!' => [ 'top-right', 'bottom' ],
				],
				'selectors'  => [
					'{{WRAPPER}} .ua-carousel-nav-prev, {{WRAPPER}} .ua-carousel-nav-next' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'arrow_horizontal_offset',
			[
				'label'      => esc_html__( 'Horizontal Offset (px)', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => -50,
						'max'  => 80,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 14,
				],
				'condition'  => [
					'show_arrows'     => 'yes',
					'arrow_position!' => [ 'top-right', 'bottom' ],
				],
				'selectors'  => [
					'{{WRAPPER}} .ua-carousel-nav-prev' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ua-carousel-nav-next' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_arrow_style' );

		// Normal
		$this->start_controls_tab(
			'tab_arrow_normal',
			[
				'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
			]
		);

		$this->add_control(
			'arrow_color',
			[
				'label'     => esc_html__( 'Arrow Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-carousel-nav-btn' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ua-carousel-nav-btn svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrow_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-carousel-nav-btn' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrow_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-carousel-nav-btn' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		// Hover
		$this->start_controls_tab(
			'tab_arrow_hover',
			[
				'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
			]
		);

		$this->add_control(
			'arrow_hover_color',
			[
				'label'     => esc_html__( 'Arrow Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-carousel-nav-btn:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ua-carousel-nav-btn:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrow_hover_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-carousel-nav-btn:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrow_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-carousel-nav-btn:hover' => 'border-color: {{VALUE}};',
				],
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
				'separator'  => 'before',
				'selectors'  => [
					'{{WRAPPER}} .ua-carousel-nav-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Style: Pagination / Dots
	 */
	protected function register_pagination_style_controls() {
		$this->start_controls_section(
			'section_style_dots',
			[
				'label'     => esc_html__( 'Pagination / Dots', 'ultraaddons-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_dots' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'dot_size',
			[
				'label'      => esc_html__( 'Dot Size (px)', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 6,
						'max' => 24,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ua-carousel-pagination .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'dot_color',
			[
				'label'     => esc_html__( 'Dot Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-carousel-pagination .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'dot_active_color',
			[
				'label'     => esc_html__( 'Active Dot Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-carousel-pagination .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .ua-carousel-pagination.swiper-pagination-progressbar .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render single post card
	 *
	 * @param \WP_Post $post
	 * @param array $settings
	 */
	protected function render_post_card( $post, $settings ) {
		$preset = ! empty( $settings['layout_preset'] ) ? $settings['layout_preset'] : 'classic';
		$permalink = esc_url( get_permalink( $post->ID ) );
		$post_title = get_the_title( $post->ID );
		$title_limit = ! empty( $settings['title_limit'] ) ? intval( $settings['title_limit'] ) : 0;
		if ( $title_limit > 0 ) {
			$post_title = wp_trim_words( $post_title, $title_limit, '...' );
		}

		$title_tag = ! empty( $settings['title_tag'] ) ? esc_attr( $settings['title_tag'] ) : 'h3';
		$hover_effect = ! empty( $settings['thumb_hover_effect'] ) ? esc_attr( $settings['thumb_hover_effect'] ) : 'effect-zoom';

		// Get Primary Category or Taxonomy Term
		$primary_cat_name = '';
		$primary_cat_link = '';
		$categories = get_the_category( $post->ID );
		if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
			$primary_cat_name = $categories[0]->name;
			$primary_cat_link = get_category_link( $categories[0]->term_id );
		} elseif ( 'post' !== $post->post_type ) {
			$taxonomies = get_object_taxonomies( $post->post_type, 'objects' );
			foreach ( $taxonomies as $tax_slug => $tax_obj ) {
				if ( ! empty( $tax_obj->hierarchical ) ) {
					$terms = get_the_terms( $post->ID, $tax_slug );
					if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
						$primary_cat_name = $terms[0]->name;
						$term_link = get_term_link( $terms[0], $tax_slug );
						$primary_cat_link = ! is_wp_error( $term_link ) ? $term_link : '';
						break;
					}
				}
			}
		}

		// Reading time calculation (~200 words / min)
		$content = get_post_field( 'post_content', $post->ID );
		$word_count = str_word_count( strip_tags( $content ) );
		$reading_time = max( 1, (int) ceil( $word_count / 200 ) );

		$show_thumb       = ! empty( $settings['show_thumb'] ) && 'yes' === $settings['show_thumb'];
		$show_badge       = ! empty( $settings['show_badge'] ) && 'yes' === $settings['show_badge'];
		$show_meta        = ! empty( $settings['show_meta'] ) && 'yes' === $settings['show_meta'];
		$show_author      = ! empty( $settings['show_author'] ) && 'yes' === $settings['show_author'];
		$show_author_avatar = ! empty( $settings['show_author_avatar'] ) && 'yes' === $settings['show_author_avatar'];
		$show_date        = ! empty( $settings['show_date'] ) && 'yes' === $settings['show_date'];
		$show_comments    = ! empty( $settings['show_comments'] ) && 'yes' === $settings['show_comments'];
		$show_reading_time = ! empty( $settings['show_reading_time'] ) && 'yes' === $settings['show_reading_time'];
		$show_title       = ! empty( $settings['show_title'] ) && 'yes' === $settings['show_title'];
		$show_excerpt     = ! empty( $settings['show_excerpt'] ) && 'yes' === $settings['show_excerpt'];
		$show_read_more   = ! empty( $settings['show_read_more'] ) && 'yes' === $settings['show_read_more'];
		$read_more_text   = ! empty( $settings['read_more_text'] ) ? $settings['read_more_text'] : esc_html__( 'Read More', 'ultraaddons-elementor-lite' );

		?>
		<div class="swiper-slide">
			<article class="ua-post-card ua-preset-<?php echo esc_attr( $preset ); ?>">

				<?php if ( $show_thumb ) : ?>
					<div class="ua-post-thumb-wrap">
						<a href="<?php echo $permalink; ?>" class="ua-post-thumb-link" aria-label="<?php echo esc_attr( get_the_title( $post->ID ) ); ?>">
							<?php
							$thumb_size = ! empty( $settings['thumb_size_size'] ) ? sanitize_key( $settings['thumb_size_size'] ) : 'medium_large';
							if ( has_post_thumbnail( $post->ID ) ) {
								echo get_the_post_thumbnail(
									$post->ID,
									$thumb_size,
									[
										'class'   => 'ua-post-img ' . $hover_effect,
										'loading' => 'lazy',
									]
								);
							} else {
								$fallback_img = defined( 'ULTRA_ADDONS_URL' ) ? ULTRA_ADDONS_URL . 'assets/images/no-image.png' : '';
								if ( $fallback_img ) {
									echo '<img src="' . esc_url( $fallback_img ) . '" class="ua-post-img ' . esc_attr( $hover_effect ) . '" alt="' . esc_attr( get_the_title( $post->ID ) ) . '" loading="lazy">';
								}
							}
							?>
						</a>

						<?php if ( $show_badge && ! empty( $primary_cat_name ) ) : ?>
							<a href="<?php echo esc_url( $primary_cat_link ); ?>" class="ua-post-badge">
								<?php echo esc_html( $primary_cat_name ); ?>
							</a>
						<?php endif; ?>

						<?php if ( 'magazine' === $preset && $show_date ) : ?>
							<div class="ua-magazine-date-box">
								<span class="ua-day"><?php echo get_the_date( 'd', $post->ID ); ?></span>
								<span class="ua-month"><?php echo get_the_date( 'M', $post->ID ); ?></span>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<div class="ua-post-content">

					<?php if ( $show_meta ) : ?>
						<div class="ua-post-meta">
							<?php if ( $show_author ) : ?>
								<span class="ua-post-meta-item ua-meta-author">
									<?php if ( $show_author_avatar ) : ?>
										<?php echo get_avatar( get_the_author_meta( 'ID', $post->post_author ), 22, '', '', [ 'class' => 'ua-author-avatar' ] ); ?>
									<?php else : ?>
										<i class="far fa-user" aria-hidden="true"></i>
									<?php endif; ?>
									<a href="<?php echo esc_url( get_author_posts_url( $post->post_author ) ); ?>">
										<?php echo esc_html( get_the_author_meta( 'display_name', $post->post_author ) ); ?>
									</a>
								</span>
							<?php endif; ?>

							<?php if ( $show_date && 'magazine' !== $preset ) : ?>
								<span class="ua-post-meta-item ua-meta-date">
									<i class="far fa-calendar-alt" aria-hidden="true"></i>
									<?php echo esc_html( get_the_date( '', $post->ID ) ); ?>
								</span>
							<?php endif; ?>

							<?php if ( $show_comments ) : ?>
								<span class="ua-post-meta-item ua-meta-comments">
									<i class="far fa-comment" aria-hidden="true"></i>
									<?php echo esc_html( get_comments_number( $post->ID ) ); ?>
								</span>
							<?php endif; ?>

							<?php if ( $show_reading_time ) : ?>
								<span class="ua-post-meta-item ua-meta-reading-time">
									<i class="far fa-clock" aria-hidden="true"></i>
									<?php printf( esc_html__( '%d min read', 'ultraaddons-elementor-lite' ), $reading_time ); ?>
								</span>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if ( $show_title ) : ?>
						<<?php echo $title_tag; ?> class="ua-post-title">
							<a href="<?php echo $permalink; ?>">
								<?php echo esc_html( $post_title ); ?>
							</a>
						</<?php echo $title_tag; ?>>
					<?php endif; ?>

					<?php if ( $show_excerpt ) : ?>
						<div class="ua-post-excerpt">
							<?php
							$excerpt_length = ! empty( $settings['excerpt_limit'] ) ? intval( $settings['excerpt_limit'] ) : 16;
							$raw_excerpt = has_excerpt( $post->ID ) ? get_the_excerpt( $post->ID ) : $content;
							echo wp_trim_words( strip_shortcodes( $raw_excerpt ), $excerpt_length, '...' );
							?>
						</div>
					<?php endif; ?>

					<?php if ( $show_read_more ) : ?>
						<div class="ua-post-readmore-wrap">
							<a href="<?php echo $permalink; ?>" class="ua-post-readmore-btn">
								<span><?php echo esc_html( $read_more_text ); ?></span>
								<?php if ( ! empty( $settings['read_more_icon']['value'] ) ) : ?>
									<span class="ua-btn-icon">
										<?php Icons_Manager::render_icon( $settings['read_more_icon'], [ 'aria-hidden' => 'true' ] ); ?>
									</span>
								<?php endif; ?>
							</a>
						</div>
					<?php endif; ?>

				</div>

			</article>
		</div>
		<?php
	}

	/**
	 * Main Render
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		// Build query arguments
		$post_type = ! empty( $settings['post_type'] ) ? $settings['post_type'] : 'post';
		$posts_count = ! empty( $settings['posts_per_page'] ) ? intval( $settings['posts_per_page'] ) : 6;
		$offset = ! empty( $settings['offset'] ) ? intval( $settings['offset'] ) : 0;
		$orderby = ! empty( $settings['orderby'] ) ? $settings['orderby'] : 'date';
		$order = ! empty( $settings['order'] ) ? $settings['order'] : 'DESC';

		$query_args = [
			'post_type'           => $post_type,
			'post_status'         => 'publish',
			'posts_per_page'      => $posts_count,
			'offset'              => $offset,
			'orderby'             => $orderby,
			'order'               => $order,
			'ignore_sticky_posts' => true,
		];

		// Taxonomies
		$tax_query = [];
		if ( 'post' === $post_type && ! empty( $settings['categories'] ) ) {
			$tax_query[] = [
				'taxonomy' => 'category',
				'field'    => 'term_id',
				'terms'    => (array) $settings['categories'],
			];
		}

		if ( 'post' === $post_type && ! empty( $settings['tags'] ) ) {
			$tax_query[] = [
				'taxonomy' => 'post_tag',
				'field'    => 'term_id',
				'terms'    => (array) $settings['tags'],
			];
		}

		if ( 'product' === $post_type && ! empty( $settings['product_categories'] ) ) {
			$tax_query[] = [
				'taxonomy' => 'product_cat',
				'field'    => 'term_id',
				'terms'    => (array) $settings['product_categories'],
			];
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

		$post_query = new \WP_Query( $query_args );

		if ( ! $post_query->have_posts() ) {
			if ( Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="ua-no-posts-alert" style="padding: 20px; background: #f8fafc; border: 1px dashed #cbd5e1; text-align: center; border-radius: 8px;">';
				echo esc_html__( 'No posts found matching your criteria.', 'ultraaddons-elementor-lite' );
				echo '</div>';
			}
			return;
		}

		// Swiper JSON configuration
		$swiper_config = [
			'effect'              => ! empty( $settings['carousel_effect'] ) ? $settings['carousel_effect'] : 'slide',
			'slidesPerView'       => ! empty( $settings['slides_per_view'] ) ? intval( $settings['slides_per_view'] ) : 3,
			'slidesPerViewTablet' => ! empty( $settings['slides_per_view_tablet'] ) ? intval( $settings['slides_per_view_tablet'] ) : 2,
			'slidesPerViewMobile' => ! empty( $settings['slides_per_view_mobile'] ) ? intval( $settings['slides_per_view_mobile'] ) : 1,
			'spaceBetween'        => isset( $settings['space_between'] ) ? intval( $settings['space_between'] ) : 24,
			'spaceBetweenTablet'  => isset( $settings['space_between_tablet'] ) ? intval( $settings['space_between_tablet'] ) : 18,
			'spaceBetweenMobile'  => isset( $settings['space_between_mobile'] ) ? intval( $settings['space_between_mobile'] ) : 12,
			'slidesPerGroup'      => ! empty( $settings['slides_per_group'] ) ? intval( $settings['slides_per_group'] ) : 1,
			'autoplay'            => ! empty( $settings['autoplay'] ) && 'yes' === $settings['autoplay'],
			'autoplayDelay'       => ! empty( $settings['autoplay_delay'] ) ? intval( $settings['autoplay_delay'] ) : 3500,
			'pauseOnHover'        => ! empty( $settings['pause_on_hover'] ) && 'yes' === $settings['pause_on_hover'],
			'loop'                => ! empty( $settings['loop'] ) && 'yes' === $settings['loop'],
			'grabCursor'          => ! empty( $settings['grab_cursor'] ) && 'yes' === $settings['grab_cursor'],
			'paginationType'      => ! empty( $settings['pagination_type'] ) ? $settings['pagination_type'] : 'bullets',
			'dynamicBullets'      => ! empty( $settings['dynamic_bullets'] ) && 'yes' === $settings['dynamic_bullets'],
			'speed'               => 600,
		];

		$arrow_pos = ! empty( $settings['arrow_position'] ) ? $settings['arrow_position'] : 'sides';
		$layout_preset = ! empty( $settings['layout_preset'] ) ? $settings['layout_preset'] : 'classic';
		$show_arrows = ! empty( $settings['show_arrows'] ) && 'yes' === $settings['show_arrows'];
		$show_dots = ! empty( $settings['show_dots'] ) && 'yes' === $settings['show_dots'];

		$wrapper_classes = [
			'ua-post-carousel-wrapper',
			'ua-preset-' . esc_attr( $layout_preset ),
			'nav-pos-' . esc_attr( $arrow_pos ),
		];

		?>
		<div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>" data-swiper-config="<?php echo esc_attr( wp_json_encode( $swiper_config ) ); ?>">

			<?php if ( ! empty( $settings['header_title'] ) || ( $show_arrows && 'top-right' === $arrow_pos ) ) : ?>
				<div class="ua-carousel-header-wrap">
					<?php if ( ! empty( $settings['header_title'] ) ) : ?>
						<?php $h_tag = ! empty( $settings['header_title_tag'] ) ? esc_attr( $settings['header_title_tag'] ) : 'h3'; ?>
						<<?php echo $h_tag; ?> class="ua-carousel-header-title">
							<?php echo esc_html( $settings['header_title'] ); ?>
						</<?php echo $h_tag; ?>>
					<?php else : ?>
						<div></div>
					<?php endif; ?>

					<?php if ( $show_arrows && 'top-right' === $arrow_pos ) : ?>
						<div class="ua-carousel-nav-wrap">
							<div class="ua-carousel-nav-btn ua-carousel-nav-prev" tabindex="0" role="button" aria-label="<?php esc_attr_e( 'Previous', 'ultraaddons-elementor-lite' ); ?>">
								<?php if ( ! empty( $settings['prev_arrow_icon'] ) ) { Icons_Manager::render_icon( $settings['prev_arrow_icon'], [ 'aria-hidden' => 'true' ] ); } ?>
							</div>
							<div class="ua-carousel-nav-btn ua-carousel-nav-next" tabindex="0" role="button" aria-label="<?php esc_attr_e( 'Next', 'ultraaddons-elementor-lite' ); ?>">
								<?php if ( ! empty( $settings['next_arrow_icon'] ) ) { Icons_Manager::render_icon( $settings['next_arrow_icon'], [ 'aria-hidden' => 'true' ] ); } ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="ua-post-carousel-slider-wrap">
				<div class="ua-post-carousel-slider swiper-container">
					<div class="swiper-wrapper">
						<?php
						while ( $post_query->have_posts() ) {
							$post_query->the_post();
							$this->render_post_card( $post_query->post, $settings );
						}
						wp_reset_postdata();
						?>
					</div>
				</div>

				<?php if ( $show_arrows && in_array( $arrow_pos, [ 'sides', 'sides-outer' ], true ) ) : ?>
					<div class="ua-carousel-nav-btn ua-carousel-nav-prev" tabindex="0" role="button" aria-label="<?php esc_attr_e( 'Previous', 'ultraaddons-elementor-lite' ); ?>">
						<?php if ( ! empty( $settings['prev_arrow_icon'] ) ) { Icons_Manager::render_icon( $settings['prev_arrow_icon'], [ 'aria-hidden' => 'true' ] ); } ?>
					</div>
					<div class="ua-carousel-nav-btn ua-carousel-nav-next" tabindex="0" role="button" aria-label="<?php esc_attr_e( 'Next', 'ultraaddons-elementor-lite' ); ?>">
						<?php if ( ! empty( $settings['next_arrow_icon'] ) ) { Icons_Manager::render_icon( $settings['next_arrow_icon'], [ 'aria-hidden' => 'true' ] ); } ?>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( $show_arrows && 'bottom' === $arrow_pos ) : ?>
				<div class="ua-carousel-nav-wrap">
					<div class="ua-carousel-nav-btn ua-carousel-nav-prev" tabindex="0" role="button" aria-label="<?php esc_attr_e( 'Previous', 'ultraaddons-elementor-lite' ); ?>">
						<?php if ( ! empty( $settings['prev_arrow_icon'] ) ) { Icons_Manager::render_icon( $settings['prev_arrow_icon'], [ 'aria-hidden' => 'true' ] ); } ?>
					</div>
					<div class="ua-carousel-nav-btn ua-carousel-nav-next" tabindex="0" role="button" aria-label="<?php esc_attr_e( 'Next', 'ultraaddons-elementor-lite' ); ?>">
						<?php if ( ! empty( $settings['next_arrow_icon'] ) ) { Icons_Manager::render_icon( $settings['next_arrow_icon'], [ 'aria-hidden' => 'true' ] ); } ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( $show_dots ) : ?>
				<div class="ua-carousel-pagination swiper-pagination"></div>
			<?php endif; ?>

		</div>
		<?php
	}
}
