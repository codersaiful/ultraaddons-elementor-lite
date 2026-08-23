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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * UltraAddons Content Toggle Widget
 *
 * Feature-complete Content Toggle / Switcher widget ported and enhanced from Royal Addons.
 * Supports Dual and Multi styles, Inside/Outside label placement, WYSIWYG & Template content.
 *
 * @since 1.2.0
 * @package UltraAddons
 */
class Content_Toggle extends Base {

	/**
	 * Constructor — register style and script dependencies
	 */
	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		wp_register_style(
			'ultraaddons-content-toggle',
			ULTRA_ADDONS_ASSETS . 'css/widgets/content-toggle.css',
			[],
			ULTRA_ADDONS_VERSION,
			'all'
		);
		wp_enqueue_style( 'ultraaddons-content-toggle' );

		wp_register_script(
			'ultraaddons-content-toggle',
			ULTRA_ADDONS_ASSETS . 'js/frontend-content-toggle.js',
			[ 'jquery' ],
			ULTRA_ADDONS_VERSION,
			true
		);
		wp_enqueue_script( 'ultraaddons-content-toggle' );
	}

	public function get_name() {
		return 'ultraaddons-content-toggle';
	}

	public function get_title() {
		return esc_html__( 'Content Toggle', 'ultraaddons-elementor-lite' );
	}

	public function get_icon() {
		return 'ultraaddons eicon-toggle';
	}

	public function get_categories() {
		return [ 'ultraaddons', 'general' ];
	}

	public function get_keywords() {
		return [ 'ultraaddons', 'royal', 'content toggle', 'content switcher', 'pricing toggle', 'toggle price plan', 'pricing table', 'switcher' ];
	}

	public function get_style_depends() {
		return [ 'ultraaddons-content-toggle' ];
	}

	public function get_script_depends() {
		return [ 'jquery', 'ultraaddons-content-toggle' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	/**
	 * Retrieve Elementor saved templates list.
	 *
	 * @return array
	 */
	public static function get_elementor_templates() {
		$templates = [
			'' => esc_html__( '— Select Template —', 'ultraaddons-elementor-lite' ),
		];

		$posts = get_posts( [
			'post_type'      => 'elementor_library',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'orderby'        => 'title',
			'order'          => 'ASC',
		] );

		if ( ! empty( $posts ) && ! is_wp_error( $posts ) ) {
			foreach ( $posts as $post ) {
				$templates[ $post->ID ] = $post->post_title;
			}
		}

		return $templates;
	}

	/**
	 * Render Elementor Template content by ID.
	 *
	 * @param int|string $id
	 * @return string
	 */
	public function render_template_content( $id ) {
		if ( empty( $id ) ) {
			return '';
		}

		if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
			$default_language_code = apply_filters( 'wpml_default_language', null );
			if ( ICL_LANGUAGE_CODE !== $default_language_code ) {
				$id = apply_filters( 'wpml_object_id', $id, 'elementor_library', true );
			}
		}

		$frontend = \Elementor\Plugin::instance()->frontend;
		if ( method_exists( $frontend, 'get_builder_content_for_display' ) ) {
			return $frontend->get_builder_content_for_display( $id );
		}

		return '';
	}

	/**
	 * Register all widget controls.
	 */
	protected function register_controls() {

		// CSS Selectors map matching Royal Elementor Addons architecture
		$css_selector = [
			'general'           => '> .elementor-widget-container > .ua-content-toggle',
			'control_container' => '> .elementor-widget-container > .ua-content-toggle > .ua-switcher-container',
			'control_outer'     => '> .elementor-widget-container > .ua-content-toggle > .ua-switcher-container > .ua-switcher-outer',
			'control_wrap'      => '> .elementor-widget-container > .ua-content-toggle > .ua-switcher-container > .ua-switcher-outer > .ua-switcher-wrap',
			'control_list'      => '> .elementor-widget-container > .ua-content-toggle > .ua-switcher-container > .ua-switcher-outer > .ua-switcher-wrap > .ua-switcher',
			'control_bg'        => '> .elementor-widget-container > .ua-content-toggle > .ua-switcher-container > .ua-switcher-outer > .ua-switcher-wrap > .ua-switcher-bg',
			'content_wrap'      => '> .elementor-widget-container > .ua-content-toggle > .ua-switcher-content-wrap',
			'content_list'      => '> .elementor-widget-container > .ua-content-toggle > .ua-switcher-content-wrap > .ua-switcher-content',
			'control_icon'      => '.ua-switcher-icon',
		];

		if ( ! $this->has_widget_inner_wrapper() ) {
			$css_selector['general']           = '> .ua-content-toggle';
			$css_selector['control_container'] = '> .ua-content-toggle > .ua-switcher-container';
			$css_selector['control_outer']     = '> .ua-content-toggle > .ua-switcher-container > .ua-switcher-outer';
			$css_selector['control_wrap']      = '> .ua-content-toggle > .ua-switcher-container > .ua-switcher-outer > .ua-switcher-wrap';
			$css_selector['control_list']      = '> .ua-content-toggle > .ua-switcher-container > .ua-switcher-outer > .ua-switcher-wrap > .ua-switcher';
			$css_selector['control_bg']        = '> .ua-content-toggle > .ua-switcher-container > .ua-switcher-outer > .ua-switcher-wrap > .ua-switcher-bg';
			$css_selector['content_wrap']      = '> .ua-content-toggle > .ua-switcher-content-wrap';
			$css_selector['content_list']      = '> .ua-content-toggle > .ua-switcher-content-wrap > .ua-switcher-content';
		}

		/*----------------------------------------------------------------------
		 * SECTION: GENERAL (Content Switcher Items)
		 *--------------------------------------------------------------------*/
		$this->start_controls_section(
			'section_switcher_general',
			[
				'label' => esc_html__( 'General', 'ultraaddons-elementor-lite' ),
			]
		);

		$this->add_control(
			'switcher_style',
			[
				'label'        => esc_html__( 'Switcher Style', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'dual',
				'options'      => [
					'dual'  => esc_html__( 'Dual', 'ultraaddons-elementor-lite' ),
					'multi' => esc_html__( 'Multi', 'ultraaddons-elementor-lite' ),
				],
				'prefix_class' => 'ua-switcher-style-',
				'render_type'  => 'template',
			]
		);

		$this->add_control(
			'switcher_label_style',
			[
				'label'        => esc_html__( 'Label Position', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'outer',
				'options'      => [
					'outer' => esc_html__( 'Outside', 'ultraaddons-elementor-lite' ),
					'inner' => esc_html__( 'Inside', 'ultraaddons-elementor-lite' ),
				],
				'prefix_class' => 'ua-switcher-label-style-',
				'render_type'  => 'template',
				'condition'    => [
					'switcher_style' => 'dual',
				],
			]
		);

		$this->add_control(
			'switcher_style_divider',
			[
				'type'  => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		/*----------------------------------------------------------------------
		 * DUAL SWITCHER TABS (First / Second)
		 *--------------------------------------------------------------------*/
		$this->start_controls_tabs(
			'tab_switcher_settings',
			[
				'condition' => [
					'switcher_style' => 'dual',
				],
			]
		);

		// First Item Tab
		$this->start_controls_tab(
			'tab_switcher_first_settings',
			[
				'label'     => esc_html__( 'First', 'ultraaddons-elementor-lite' ),
				'condition' => [
					'switcher_style' => 'dual',
				],
			]
		);

		$this->add_control(
			'switcher_first_label',
			[
				'label'     => esc_html__( 'Label', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => [ 'active' => true ],
				'default'   => 'Annual',
				'condition' => [
					'switcher_style' => 'dual',
				],
			]
		);

		$this->add_control(
			'switcher_first_show_icon',
			[
				'label'     => esc_html__( 'Show Icon', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'condition' => [
					'switcher_style' => 'dual',
				],
			]
		);

		$this->add_control(
			'switcher_first_icon',
			[
				'label'       => esc_html__( 'Select Icon', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'default'     => [
					'value'   => 'fas fa-angle-right',
					'library' => 'fa-solid',
				],
				'condition'   => [
					'switcher_first_show_icon' => 'yes',
					'switcher_style'           => 'dual',
				],
			]
		);

		/*----------------------------------------------------------------------
		 * DYNAMIC PRICING CARDS REPEATER
		 *--------------------------------------------------------------------*/
		$pricing_repeater = new Repeater();

		$pricing_repeater->add_control(
			'card_featured',
			[
				'label'     => esc_html__( 'Featured / Highlighted Card', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'separator' => 'before',
			]
		);

		$pricing_repeater->add_control(
			'card_badge_text',
			[
				'label'       => esc_html__( 'Badge Text', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [ 'active' => true ],
				'default'     => '✨ Best Value',
				'placeholder' => '✨ Best Value / Popular',
				'condition'   => [
					'card_featured' => 'yes',
				],
			]
		);

		$pricing_repeater->add_control(
			'card_badge_bg',
			[
				'label'     => esc_html__( 'Badge Background Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ede9fe',
				'condition' => [
					'card_featured' => 'yes',
				],
			]
		);

		$pricing_repeater->add_control(
			'card_badge_color',
			[
				'label'     => esc_html__( 'Badge Text Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6841fa',
				'condition' => [
					'card_featured' => 'yes',
				],
			]
		);

		$pricing_repeater->add_control(
			'card_title',
			[
				'label'       => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [ 'active' => true ],
				'default'     => 'Unlimited',
				'label_block' => true,
			]
		);

		$pricing_repeater->add_control(
			'card_subtitle',
			[
				'label'       => esc_html__( 'Subtitle', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [ 'active' => true ],
				'default'     => '',
				'label_block' => true,
			]
		);

		$pricing_repeater->add_control(
			'card_icon',
			[
				'label'       => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
			]
		);

		$pricing_repeater->add_control(
			'card_currency',
			[
				'label'   => esc_html__( 'Currency Symbol', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => [ 'active' => true ],
				'default' => '$',
			]
		);

		$pricing_repeater->add_control(
			'card_price',
			[
				'label'       => esc_html__( 'Price', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [ 'active' => true ],
				'default'     => '59',
				'label_block' => true,
			]
		);

		$pricing_repeater->add_control(
			'card_original_price',
			[
				'label'       => esc_html__( 'Original Price (Strikethrough)', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [ 'active' => true ],
				'default'     => '',
				'placeholder' => '99',
			]
		);

		$pricing_repeater->add_control(
			'card_period',
			[
				'label'       => esc_html__( 'Period (e.g. / Month, / Year)', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [ 'active' => true ],
				'default'     => '',
				'placeholder' => '/mo',
			]
		);

		$pricing_repeater->add_control(
			'card_billing_note',
			[
				'label'       => esc_html__( 'Billing Note (e.g. *Billed Annually)', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [ 'active' => true ],
				'default'     => '',
				'placeholder' => '*Billed Annually',
			]
		);

		$pricing_repeater->add_control(
			'card_features',
			[
				'label'       => esc_html__( 'Features (One per line, start with x or - for disabled)', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => [ 'active' => true ],
				'default'     => "Unlimited Active Sites\n1 Year of Support & Updates\n100+ Widgets\n15+ Extensions\nFree Access to Templately PRO (3 Months)",
				'rows'        => 5,
				'label_block' => true,
			]
		);

		$pricing_repeater->add_control(
			'card_active_icon',
			[
				'label'       => esc_html__( 'Active Feature Icon', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'default'     => [
					'value'   => 'fas fa-check',
					'library' => 'fa-solid',
				],
			]
		);

		$pricing_repeater->add_control(
			'card_disabled_icon',
			[
				'label'       => esc_html__( 'Disabled Feature Icon', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'default'     => [
					'value'   => 'fas fa-times',
					'library' => 'fa-solid',
				],
			]
		);

		$pricing_repeater->add_control(
			'card_btn_text',
			[
				'label'   => esc_html__( 'Button Text', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => [ 'active' => true ],
				'default' => esc_html__( 'Get Started', 'ultraaddons-elementor-lite' ),
			]
		);

		$pricing_repeater->add_control(
			'card_btn_url',
			[
				'label'       => esc_html__( 'Button Link', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => [ 'active' => true ],
				'placeholder' => 'https://your-link.com',
				'default'     => [
					'url' => '#',
				],
			]
		);

		$pricing_repeater->add_control(
			'card_bg_color_from',
			[
				'label'   => esc_html__( 'Background Color (Gradient From)', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::COLOR,
			]
		);

		$pricing_repeater->add_control(
			'card_bg_color_to',
			[
				'label'   => esc_html__( 'Background Color (Gradient To)', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::COLOR,
			]
		);

		$pricing_repeater->add_control(
			'card_btn_color',
			[
				'label'   => esc_html__( 'Button Text Color', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'switcher_first_content_type',
			[
				'label'     => esc_html__( 'Select Content Type', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'pricing',
				'options'   => [
					'pricing'  => esc_html__( 'Dynamic Pricing Cards', 'ultraaddons-elementor-lite' ),
					'showcase' => esc_html__( 'Product / Food Showcase (Split View)', 'ultraaddons-elementor-lite' ),
					'template' => esc_html__( 'Elementor Template', 'ultraaddons-elementor-lite' ),
					'editor'   => esc_html__( 'Rich Editor (HTML)', 'ultraaddons-elementor-lite' ),
				],
				'separator' => 'before',
				'condition' => [
					'switcher_style' => 'dual',
				],
			]
		);

		$this->add_control(
			'switcher_first_showcase_image',
			[
				'label'     => esc_html__( 'Showcase Image', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::MEDIA,
				'dynamic'   => [ 'active' => true ],
				'default'   => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'switcher_first_content_type' => 'showcase',
					'switcher_style'              => 'dual',
				],
			]
		);

		$this->add_control(
			'switcher_first_showcase_title',
			[
				'label'       => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [ 'active' => true ],
				'default'     => 'Cheesecake',
				'label_block' => true,
				'condition'   => [
					'switcher_first_content_type' => 'showcase',
					'switcher_style'              => 'dual',
				],
			]
		);

		$this->add_control(
			'switcher_first_showcase_desc',
			[
				'label'       => esc_html__( 'Description', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => [ 'active' => true ],
				'default'     => "Let us show you how to make cheesecake that's baked to perfection, firm and fudgy with a tangy topping, with vanilla and strawberries. Because once you learn this you'll have friends for life.",
				'rows'        => 4,
				'label_block' => true,
				'condition'   => [
					'switcher_first_content_type' => 'showcase',
					'switcher_style'              => 'dual',
				],
			]
		);

		$this->add_control(
			'switcher_first_showcase_price',
			[
				'label'     => esc_html__( 'Price', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => [ 'active' => true ],
				'default'   => '15$',
				'condition' => [
					'switcher_first_content_type' => 'showcase',
					'switcher_style'              => 'dual',
				],
			]
		);

		$this->add_control(
			'switcher_first_showcase_btn_text',
			[
				'label'     => esc_html__( 'Button Text (Optional)', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => [ 'active' => true ],
				'default'   => '',
				'condition' => [
					'switcher_first_content_type' => 'showcase',
					'switcher_style'              => 'dual',
				],
			]
		);

		$this->add_control(
			'switcher_first_showcase_btn_url',
			[
				'label'       => esc_html__( 'Button Link', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => [ 'active' => true ],
				'placeholder' => 'https://your-link.com',
				'default'     => [
					'url' => '#',
				],
				'condition'   => [
					'switcher_first_content_type' => 'showcase',
					'switcher_style'              => 'dual',
				],
			]
		);

		$this->add_control(
			'switcher_first_pricing_cards',
			[
				'label'       => esc_html__( 'Pricing Cards', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $pricing_repeater->get_controls(),
				'title_field' => '{{{ card_title }}} ({{{ card_currency }}}{{{ card_price }}})',
				'default'     => [
					[
						'card_title'         => 'Basic',
						'card_subtitle'      => 'For Individual Users',
						'card_currency'      => '$',
						'card_price'         => '59',
						'card_featured'      => 'no',
						'card_bg_color_from' => '#7653fc',
						'card_bg_color_to'   => '#6841fa',
						'card_btn_color'     => '#6841fa',
						'card_btn_text'      => 'Sign Up',
						'card_features'      => "20 GB Disk Space\n25 GB Monthly Bandwidth\nx Unlimited Users\nx 5 Domains",
						'card_icon'          => [
							'value'   => 'fas fa-globe',
							'library' => 'fa-solid',
						],
					],
					[
						'card_title'         => 'Premium',
						'card_subtitle'      => 'For small business',
						'card_featured'      => 'yes',
						'card_badge_text'    => '✨ Most Popular',
						'card_badge_bg'      => 'rgba(255, 255, 255, 0.28)',
						'card_badge_color'   => '#ffffff',
						'card_currency'      => '$',
						'card_price'         => '259',
						'card_bg_color_from' => '#fbb03b',
						'card_bg_color_to'   => '#f39c12',
						'card_btn_color'     => '#f39c12',
						'card_btn_text'      => 'Sign Up',
						'card_features'      => "1 TB Disk Space\n1 TB Monthly Bandwidth\nUnlimited Users\nUnlimited Domains",
						'card_icon'          => [
							'value'   => 'fas fa-space-shuttle',
							'library' => 'fa-solid',
						],
					],
					[
						'card_title'         => 'Plus',
						'card_subtitle'      => 'For All Pro Users',
						'card_currency'      => '$',
						'card_price'         => '159',
						'card_featured'      => 'no',
						'card_bg_color_from' => '#ff5779',
						'card_bg_color_to'   => '#f62f63',
						'card_btn_color'     => '#f62f63',
						'card_btn_text'      => 'Sign Up',
						'card_features'      => "20 GB Disk Space\n30 GB Monthly Bandwidth\nUnlimited Users\nx 150 Domains",
						'card_icon'          => [
							'value'   => 'fas fa-rocket',
							'library' => 'fa-solid',
						],
					],
				],
				'condition'   => [
					'switcher_first_content_type' => 'pricing',
					'switcher_style'              => 'dual',
				],
			]
		);

		$this->add_control(
			'switcher_first_content',
			[
				'label'       => esc_html__( 'Content', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::WYSIWYG,
				'placeholder' => esc_html__( 'Tab Content', 'ultraaddons-elementor-lite' ),
				'default'     => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.',
				'condition'   => [
					'switcher_first_content_type' => 'editor',
					'switcher_style'              => 'dual',
				],
			]
		);

		$this->add_control(
			'switcher_first_select_template',
			[
				'label'       => esc_html__( 'Select Template', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => self::get_elementor_templates(),
				'label_block' => true,
				'condition'   => [
					'switcher_first_content_type' => 'template',
					'switcher_style'              => 'dual',
				],
			]
		);

		$this->end_controls_tab();

		// Second Item Tab
		$this->start_controls_tab(
			'tab_switcher_second_settings',
			[
				'label'     => esc_html__( 'Second', 'ultraaddons-elementor-lite' ),
				'condition' => [
					'switcher_style' => 'dual',
				],
			]
		);

		$this->add_control(
			'switcher_second_label',
			[
				'label'     => esc_html__( 'Label', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => [ 'active' => true ],
				'default'   => 'Lifetime',
				'condition' => [
					'switcher_style' => 'dual',
				],
			]
		);

		$this->add_control(
			'switcher_second_show_icon',
			[
				'label'     => esc_html__( 'Show Icon', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'condition' => [
					'switcher_style' => 'dual',
				],
			]
		);

		$this->add_control(
			'switcher_second_icon',
			[
				'label'       => esc_html__( 'Select Icon', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'default'     => [
					'value'   => 'fas fa-angle-right',
					'library' => 'fa-solid',
				],
				'condition'   => [
					'switcher_second_show_icon' => 'yes',
					'switcher_style'            => 'dual',
				],
			]
		);

		$this->add_control(
			'switcher_second_content_type',
			[
				'label'     => esc_html__( 'Select Content Type', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'pricing',
				'options'   => [
					'pricing'  => esc_html__( 'Dynamic Pricing Cards', 'ultraaddons-elementor-lite' ),
					'showcase' => esc_html__( 'Product / Food Showcase (Split View)', 'ultraaddons-elementor-lite' ),
					'template' => esc_html__( 'Elementor Template', 'ultraaddons-elementor-lite' ),
					'editor'   => esc_html__( 'Rich Editor (HTML)', 'ultraaddons-elementor-lite' ),
				],
				'separator' => 'before',
				'condition' => [
					'switcher_style' => 'dual',
				],
			]
		);

		$this->add_control(
			'switcher_second_showcase_image',
			[
				'label'     => esc_html__( 'Showcase Image', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::MEDIA,
				'dynamic'   => [ 'active' => true ],
				'default'   => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'switcher_second_content_type' => 'showcase',
					'switcher_style'               => 'dual',
				],
			]
		);

		$this->add_control(
			'switcher_second_showcase_title',
			[
				'label'       => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [ 'active' => true ],
				'default'     => 'Cherrypie',
				'label_block' => true,
				'condition'   => [
					'switcher_second_content_type' => 'showcase',
					'switcher_style'               => 'dual',
				],
			]
		);

		$this->add_control(
			'switcher_second_showcase_desc',
			[
				'label'       => esc_html__( 'Description', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => [ 'active' => true ],
				'default'     => "An easy cherry loaf cake that's moist and tender with ground almonds that just needs a cup of tea. Remember to coat the cherries in a little flour so they don't all sink to the bottom of the cake.",
				'rows'        => 4,
				'label_block' => true,
				'condition'   => [
					'switcher_second_content_type' => 'showcase',
					'switcher_style'               => 'dual',
				],
			]
		);

		$this->add_control(
			'switcher_second_showcase_price',
			[
				'label'     => esc_html__( 'Price', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => [ 'active' => true ],
				'default'   => '25$',
				'condition' => [
					'switcher_second_content_type' => 'showcase',
					'switcher_style'               => 'dual',
				],
			]
		);

		$this->add_control(
			'switcher_second_showcase_btn_text',
			[
				'label'     => esc_html__( 'Button Text (Optional)', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => [ 'active' => true ],
				'default'   => '',
				'condition' => [
					'switcher_second_content_type' => 'showcase',
					'switcher_style'               => 'dual',
				],
			]
		);

		$this->add_control(
			'switcher_second_showcase_btn_url',
			[
				'label'       => esc_html__( 'Button Link', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => [ 'active' => true ],
				'placeholder' => 'https://your-link.com',
				'default'     => [
					'url' => '#',
				],
				'condition'   => [
					'switcher_second_content_type' => 'showcase',
					'switcher_style'               => 'dual',
				],
			]
		);

		$this->add_control(
			'switcher_second_pricing_cards',
			[
				'label'       => esc_html__( 'Pricing Cards', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $pricing_repeater->get_controls(),
				'title_field' => '{{{ card_title }}} ({{{ card_currency }}}{{{ card_price }}})',
				'default'     => [
					[
						'card_title'         => 'Basic',
						'card_subtitle'      => 'For Individual Users',
						'card_currency'      => '$',
						'card_price'         => '199',
						'card_featured'      => 'no',
						'card_bg_color_from' => '#7653fc',
						'card_bg_color_to'   => '#6841fa',
						'card_btn_color'     => '#6841fa',
						'card_btn_text'      => 'Sign Up',
						'card_features'      => "20 GB Disk Space\n25 GB Monthly Bandwidth\nx Unlimited Users\nx 5 Domains",
						'card_icon'          => [
							'value'   => 'fas fa-globe',
							'library' => 'fa-solid',
						],
					],
					[
						'card_title'         => 'Premium',
						'card_subtitle'      => 'For small business',
						'card_featured'      => 'yes',
						'card_badge_text'    => '✨ Most Popular',
						'card_badge_bg'      => 'rgba(255, 255, 255, 0.28)',
						'card_badge_color'   => '#ffffff',
						'card_currency'      => '$',
						'card_price'         => '699',
						'card_bg_color_from' => '#fbb03b',
						'card_bg_color_to'   => '#f39c12',
						'card_btn_color'     => '#f39c12',
						'card_btn_text'      => 'Sign Up',
						'card_features'      => "1 TB Disk Space\n1 TB Monthly Bandwidth\nUnlimited Users\nUnlimited Domains",
						'card_icon'          => [
							'value'   => 'fas fa-space-shuttle',
							'library' => 'fa-solid',
						],
					],
					[
						'card_title'         => 'Plus',
						'card_subtitle'      => 'For All Pro Users',
						'card_currency'      => '$',
						'card_price'         => '399',
						'card_featured'      => 'no',
						'card_bg_color_from' => '#ff5779',
						'card_bg_color_to'   => '#f62f63',
						'card_btn_color'     => '#f62f63',
						'card_btn_text'      => 'Sign Up',
						'card_features'      => "20 GB Disk Space\n30 GB Monthly Bandwidth\nUnlimited Users\nx 150 Domains",
						'card_icon'          => [
							'value'   => 'fas fa-rocket',
							'library' => 'fa-solid',
						],
					],
				],
				'condition'   => [
					'switcher_second_content_type' => 'pricing',
					'switcher_style'               => 'dual',
				],
			]
		);

		$this->add_control(
			'switcher_second_content',
			[
				'label'       => esc_html__( 'Content', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::WYSIWYG,
				'placeholder' => esc_html__( 'Tab Content', 'ultraaddons-elementor-lite' ),
				'default'     => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.',
				'condition'   => [
					'switcher_second_content_type' => 'editor',
					'switcher_style'               => 'dual',
				],
			]
		);

		$this->add_control(
			'switcher_second_select_template',
			[
				'label'       => esc_html__( 'Select Template', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => self::get_elementor_templates(),
				'label_block' => true,
				'condition'   => [
					'switcher_second_content_type' => 'template',
					'switcher_style'               => 'dual',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		/*----------------------------------------------------------------------
		 * MULTI SWITCHER REPEATER
		 *--------------------------------------------------------------------*/
		$repeater = new Repeater();

		$repeater->add_control(
			'item_label',
			[
				'label'       => esc_html__( 'Label', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [ 'active' => true ],
				'default'     => esc_html__( 'Option', 'ultraaddons-elementor-lite' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'item_show_icon',
			[
				'label'     => esc_html__( 'Show Icon', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'item_icon',
			[
				'label'       => esc_html__( 'Select Icon', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'default'     => [
					'value'   => 'fas fa-angle-right',
					'library' => 'fa-solid',
				],
				'condition'   => [
					'item_show_icon' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'item_content_type',
			[
				'label'     => esc_html__( 'Select Content Type', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'editor',
				'options'   => [
					'template' => esc_html__( 'Elementor Template', 'ultraaddons-elementor-lite' ),
					'editor'   => esc_html__( 'Editor', 'ultraaddons-elementor-lite' ),
				],
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'item_content',
			[
				'label'       => esc_html__( 'Content', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::WYSIWYG,
				'placeholder' => esc_html__( 'Tab Content', 'ultraaddons-elementor-lite' ),
				'default'     => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima incidunt voluptates nemo, dolor optio quia architecto quis delectus perspiciatis.',
				'condition'   => [
					'item_content_type' => 'editor',
				],
			]
		);

		$repeater->add_control(
			'item_select_template',
			[
				'label'       => esc_html__( 'Select Template', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => self::get_elementor_templates(),
				'label_block' => true,
				'condition'   => [
					'item_content_type' => 'template',
				],
			]
		);

		$this->add_control(
			'switcher_items',
			[
				'label'       => esc_html__( 'Switcher Items', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ item_label }}}',
				'default'     => [
					[
						'item_label'   => esc_html__( 'Monthly', 'ultraaddons-elementor-lite' ),
						'item_content' => 'Nobis atque id hic neque possimus voluptatum voluptatibus tenetur, perspiciatis consequuntur. Lorem ipsum dolor sit amet, consectetur adipisicing elit.',
					],
					[
						'item_label'   => esc_html__( 'Annually', 'ultraaddons-elementor-lite' ),
						'item_content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima incidunt voluptates nemo, dolor optio quia architecto quis delectus perspiciatis.',
					],
					[
						'item_label'   => esc_html__( 'Lifetime', 'ultraaddons-elementor-lite' ),
						'item_content' => 'Perspiciatis consequuntur nobis atque id hic neque possimus voluptatum voluptatibus tenetur. Minima incidunt voluptates nemo, dolor optio.',
					],
				],
				'condition'   => [
					'switcher_style' => 'multi',
				],
			]
		);

		$this->end_controls_section(); // End General Section

		/*----------------------------------------------------------------------
		 * SECTION: SETTINGS (Animation & Alignment)
		 *--------------------------------------------------------------------*/
		$this->start_controls_section(
			'section_switcher_settings',
			[
				'label' => esc_html__( 'Settings', 'ultraaddons-elementor-lite' ),
			]
		);

		$this->add_control(
			'active_switcher',
			[
				'label'   => esc_html__( 'Active Switcher', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 1,
				'min'     => 1,
				'max'     => 20,
				'step'    => 1,
			]
		);

		$this->add_control(
			'content_animation',
			[
				'label'   => esc_html__( 'Content Animation', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => [
					'none'  => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
					'fade'  => esc_html__( 'Fade', 'ultraaddons-elementor-lite' ),
					'scale' => esc_html__( 'Scale', 'ultraaddons-elementor-lite' ),
					'slide' => esc_html__( 'Slide', 'ultraaddons-elementor-lite' ),
				],
			]
		);

		$this->add_responsive_control(
			'switcher_alignment',
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
				'default'   => 'center',
				'selectors' => [
					'{{WRAPPER}} .ua-switcher-container' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section(); // End Settings Section

		/*----------------------------------------------------------------------
		 * SECTION: FEATURE ICONS (Pick custom icons from Icon Library)
		 *--------------------------------------------------------------------*/
		$this->start_controls_section(
			'section_pricing_icons',
			[
				'label' => esc_html__( 'Pricing Feature Icons', 'ultraaddons-elementor-lite' ),
			]
		);

		$this->add_control(
			'pricing_active_feature_icon',
			[
				'label'       => esc_html__( 'Active Feature Icon', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'default'     => [
					'value'   => 'fas fa-check',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'pricing_disabled_feature_icon',
			[
				'label'       => esc_html__( 'Disabled Feature Icon', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'default'     => [
					'value'   => 'fas fa-xmark',
					'library' => 'fa-solid',
				],
			]
		);

		$this->end_controls_section();

		/*----------------------------------------------------------------------
		 * STYLE TAB: SECTION SWITCHER
		 *--------------------------------------------------------------------*/
		$this->start_controls_section(
			'section_style_switcher',
			[
				'label' => esc_html__( 'Switcher', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Multi Style Tabs
		$this->start_controls_tabs( 'tab_style' );

		$this->start_controls_tab(
			'tab_normal_style',
			[
				'label'     => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
				'condition' => [
					'switcher_style' => 'multi',
				],
			]
		);

		$this->add_control(
			'switcher_color',
			[
				'label'     => esc_html__( 'Label Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} ' . $css_selector['control_list'] => 'color: {{VALUE}};',
				],
				'condition' => [
					'switcher_style' => 'multi',
				],
			]
		);

		$this->add_control(
			'switcher_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} ' . $css_selector['control_outer'] => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'switcher_style' => 'multi',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_active_style',
			[
				'label'     => esc_html__( 'Active', 'ultraaddons-elementor-lite' ),
				'condition' => [
					'switcher_style' => 'multi',
				],
			]
		);

		$this->add_control(
			'switcher_active_color',
			[
				'label'     => esc_html__( 'Label Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} ' . $css_selector['control_list'] . '.ua-switcher-active' => 'color: {{VALUE}};',
				],
				'condition' => [
					'switcher_style' => 'multi',
				],
			]
		);

		$this->add_control(
			'switcher_active_bg_color',
			[
				'label'     => esc_html__( 'Handler Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} ' . $css_selector['control_bg'] => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'switcher_style' => 'multi',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// Dual Style Tabs (First / Second Active)
		$this->start_controls_tabs( 'switcher_dual_style' );

		$this->start_controls_tab(
			'switcher_first_style',
			[
				'label'     => esc_html__( 'First', 'ultraaddons-elementor-lite' ),
				'condition' => [
					'switcher_style' => 'dual',
				],
			]
		);

		$this->add_control(
			'handler_first_color',
			[
				'label'     => esc_html__( 'Label Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f59e0b',
				'selectors' => [
					'{{WRAPPER}}' . $css_selector['control_container'] . '[data-active-switcher*="1"] .ua-switcher-first' => 'color: {{VALUE}};',
				],
				'condition' => [
					'switcher_style' => 'dual',
				],
			]
		);

		$this->add_control(
			'handler_first_bg_color',
			[
				'label'     => esc_html__( 'Handler Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} ' . $css_selector['control_container'] . '[data-active-switcher*="1"] .ua-switcher-bg' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'switcher_style' => 'dual',
				],
			]
		);

		$this->add_control(
			'switcher_first_color',
			[
				'label'     => esc_html__( 'Inactive Text Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f59e0b',
				'selectors' => [
					'{{WRAPPER}} ' . $css_selector['control_container'] . '[data-active-switcher*="1"] .ua-switcher-second' => 'color: {{VALUE}};',
				],
				'condition' => [
					'switcher_style' => 'dual',
				],
			]
		);

		$this->add_control(
			'switcher_first_bg_color',
			[
				'label'     => esc_html__( 'Inactive Background Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f59e0b',
				'selectors' => [
					'{{WRAPPER}} ' . $css_selector['control_container'] . '[data-active-switcher*="1"] > .ua-switcher-outer' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'switcher_style' => 'dual',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_switcher_second_style',
			[
				'label'     => esc_html__( 'Second', 'ultraaddons-elementor-lite' ),
				'condition' => [
					'switcher_style' => 'dual',
				],
			]
		);

		$this->add_control(
			'handler_second_color',
			[
				'label'     => esc_html__( 'Label Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f59e0b',
				'selectors' => [
					'{{WRAPPER}}' . $css_selector['control_container'] . '[data-active-switcher*="2"] .ua-switcher-second' => 'color: {{VALUE}};',
				],
				'condition' => [
					'switcher_style' => 'dual',
				],
			]
		);

		$this->add_control(
			'handler_second_bg_color',
			[
				'label'     => esc_html__( 'Handler Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} ' . $css_selector['control_container'] . '[data-active-switcher*="2"] .ua-switcher-bg' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'switcher_style' => 'dual',
				],
			]
		);

		$this->add_control(
			'switcher_second_color',
			[
				'label'     => esc_html__( 'Inactive Text Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f59e0b',
				'selectors' => [
					'{{WRAPPER}} ' . $css_selector['control_container'] . '[data-active-switcher*="2"] .ua-switcher-first' => 'color: {{VALUE}};',
				],
				'condition' => [
					'switcher_style' => 'dual',
				],
			]
		);

		$this->add_control(
			'switcher_second_bg_color',
			[
				'label'     => esc_html__( 'Inactive Background Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f59e0b',
				'selectors' => [
					'{{WRAPPER}} ' . $css_selector['control_container'] . '[data-active-switcher*="2"] > .ua-switcher-outer' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'switcher_style' => 'dual',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// Divider
		$this->add_control(
			'switcher_box_shadow_divider',
			[
				'type'  => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'switcher_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_selector['control_outer'],
			]
		);

		$this->add_control(
			'switcher_typography_divider',
			[
				'type'  => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'switcher_typography',
				'selector' => '{{WRAPPER}} .ua-switcher-label',
			]
		);

		$this->add_responsive_control(
			'switcher_outer_label_distance',
			[
				'label'      => esc_html__( 'Label Distance', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors'  => [
					'{{WRAPPER}} ' . $css_selector['control_container'] . ' > .ua-switcher-first'  => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_selector['control_container'] . ' > .ua-switcher-second' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'separator'  => 'before',
				'condition'  => [
					'switcher_style'       => 'dual',
					'switcher_label_style' => 'outer',
				],
			]
		);

		$this->add_responsive_control(
			'switcher_width',
			[
				'label'      => esc_html__( 'Wrapper Width', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors'  => [
					'{{WRAPPER}}.ua-switcher-style-dual.ua-switcher-label-style-outer ' . $css_selector['control_wrap'] => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ua-switcher-style-dual.ua-switcher-label-style-outer ' . $css_selector['control_wrap'] => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator'  => 'before',
				'condition'  => [
					'switcher_style'       => 'dual',
					'switcher_label_style' => 'outer',
				],
			]
		);

		$this->add_responsive_control(
			'switcher_height',
			[
				'label'      => esc_html__( 'Wrapper Height', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 18,
				],
				'selectors'  => [
					'{{WRAPPER}}.ua-switcher-style-dual.ua-switcher-label-style-outer ' . $css_selector['control_wrap'] => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ua-switcher-style-dual.ua-switcher-label-style-outer ' . $css_selector['control_wrap'] => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'switcher_style'       => 'dual',
					'switcher_label_style' => 'outer',
				],
			]
		);

		$this->add_control(
			'handler_offset',
			[
				'label'      => esc_html__( 'Wrapper Padding', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors'  => [
					'{{WRAPPER}}.ua-switcher-style-dual.ua-switcher-label-style-outer ' . $css_selector['control_wrap'] => 'margin: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ua-switcher-style-dual.ua-switcher-label-style-outer ' . $css_selector['control_wrap'] => 'margin: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'switcher_style'       => 'dual',
					'switcher_label_style' => 'outer',
				],
			]
		);

		$this->add_responsive_control(
			'switcher_tab_padding',
			[
				'label'      => esc_html__( 'Tab Padding', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'default'    => [
					'top'      => 10,
					'right'    => 24,
					'bottom'   => 10,
					'left'     => 24,
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .ua-switcher' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'switcher_style' => 'multi',
				],
			]
		);

		$this->add_responsive_control(
			'handler_width',
			[
				'label'      => esc_html__( 'Handler Width', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 10,
						'max' => 50,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors'  => [
					'{{WRAPPER}}.ua-switcher-style-dual.ua-switcher-label-style-outer ' . $css_selector['control_bg'] => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ua-switcher-style-dual.ua-switcher-label-style-outer ' . $css_selector['control_bg'] => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator'  => 'before',
				'condition'  => [
					'switcher_style'       => 'dual',
					'switcher_label_style' => 'outer',
				],
			]
		);

		$this->add_control(
			'switcher_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors'  => [
					'{{WRAPPER}} ' . $css_selector['control_outer'] => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_selector['control_bg']    => 'border-radius: calc({{SIZE}}{{UNIT}} - {{switcher_border_width.SIZE}}{{switcher_border_width.UNIT}});',
				],
				'separator'  => 'before',
			]
		);

		$this->add_responsive_control(
			'switcher_margin',
			[
				'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default'    => [
					'top'      => 10,
					'right'    => 10,
					'bottom'   => 10,
					'left'     => 10,
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}} ' . $css_selector['control_container'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_control(
			'switcher_border_type',
			[
				'label'     => esc_html__( 'Border Type', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'none'   => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
					'solid'  => esc_html__( 'Solid', 'ultraaddons-elementor-lite' ),
					'double' => esc_html__( 'Double', 'ultraaddons-elementor-lite' ),
					'dotted' => esc_html__( 'Dotted', 'ultraaddons-elementor-lite' ),
					'dashed' => esc_html__( 'Dashed', 'ultraaddons-elementor-lite' ),
					'groove' => esc_html__( 'Groove', 'ultraaddons-elementor-lite' ),
				],
				'default'   => 'none',
				'selectors' => [
					'{{WRAPPER}} ' . $css_selector['control_outer'] => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'switcher_border_width',
			[
				'label'      => esc_html__( 'Border Width', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors'  => [
					'{{WRAPPER}} ' . $css_selector['control_outer'] => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'switcher_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'switcher_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#e5e5e5',
				'selectors' => [
					'{{WRAPPER}} ' . $css_selector['control_outer'] => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'switcher_border_type!' => 'none',
				],
			]
		);

		// Icon section
		$this->add_control(
			'switcher_icon_section',
			[
				'label'     => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'switcher_icon_position',
			[
				'label'        => esc_html__( 'Position', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::CHOOSE,
				'label_block'  => false,
				'default'      => 'right',
				'options'      => [
					'left'  => [
						'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
						'icon'  => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'ua-switcher-icon-position-',
			]
		);

		$this->add_responsive_control(
			'switcher_icon_size',
			[
				'label'      => esc_html__( 'Size', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 18,
				],
				'selectors'  => [
					'{{WRAPPER}} .ua-switcher-icon'     => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ua-switcher-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'switcher_icon_distance',
			[
				'label'      => esc_html__( 'Distance', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors'  => [
					'{{WRAPPER}}.ua-switcher-icon-position-left' . $css_selector['control_container'] . ' > .ua-switcher-inner > .ua-switcher-label ~ ' . $css_selector['control_icon'] => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.ua-switcher-icon-position-left' . $css_selector['control_list'] . ' > .ua-switcher-inner > .ua-switcher-label ~ ' . $css_selector['control_icon']      => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.ua-switcher-icon-position-right' . $css_selector['control_container'] . ' > .ua-switcher-inner > .ua-switcher-label ~ ' . $css_selector['control_icon'] => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.ua-switcher-icon-position-right' . $css_selector['control_list'] . ' > .ua-switcher-inner > .ua-switcher-label ~ ' . $css_selector['control_icon']      => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Switcher Style Section

		/*----------------------------------------------------------------------
		 * STYLE TAB: SECTION CONTENT
		 *--------------------------------------------------------------------*/
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_color',
			[
				'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} ' . $css_selector['content_list'] => 'color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'transparent',
				'selectors' => [
					'{{WRAPPER}} ' . $css_selector['content_wrap'] => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'content_typography_divider',
			[
				'type'  => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} ' . $css_selector['content_list'],
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => [
					'top'      => 0,
					'right'    => 0,
					'bottom'   => 0,
					'left'     => 0,
					'isLinked' => true,
				],
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} ' . $css_selector['content_list'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_control(
			'content_border_type',
			[
				'label'     => esc_html__( 'Border Type', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'none'   => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
					'solid'  => esc_html__( 'Solid', 'ultraaddons-elementor-lite' ),
					'double' => esc_html__( 'Double', 'ultraaddons-elementor-lite' ),
					'dotted' => esc_html__( 'Dotted', 'ultraaddons-elementor-lite' ),
					'dashed' => esc_html__( 'Dashed', 'ultraaddons-elementor-lite' ),
					'groove' => esc_html__( 'Groove', 'ultraaddons-elementor-lite' ),
				],
				'default'   => 'none',
				'selectors' => [
					'{{WRAPPER}} ' . $css_selector['content_wrap'] => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'content_border_width',
			[
				'label'      => esc_html__( 'Border Width', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default'    => [
					'top'      => 1,
					'right'    => 1,
					'bottom'   => 1,
					'left'     => 1,
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}} ' . $css_selector['content_wrap'] => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'content_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'content_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} ' . $css_selector['content_wrap'] => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'content_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'content_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default'    => [
					'top'      => 0,
					'right'    => 0,
					'bottom'   => 0,
					'left'     => 0,
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}} ' . $css_selector['content_wrap'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_control(
			'content_box_shadow_divider',
			[
				'type'  => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'content_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_selector['content_wrap'],
			]
		);

		$this->end_controls_section(); // End Content Style Section

		/*----------------------------------------------------------------------
		 * STYLE TAB: PRICING CARDS
		 *--------------------------------------------------------------------*/
		$this->start_controls_section(
			'section_style_pricing_cards',
			[
				'label' => esc_html__( 'Pricing Cards', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'pricing_card_padding',
			[
				'label'      => esc_html__( 'Card Padding', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'default'    => [
					'top'      => 45,
					'right'    => 30,
					'bottom'   => 40,
					'left'     => 30,
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .ua-pricing-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pricing_card_border_radius',
			[
				'label'      => esc_html__( 'Card Border Radius', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 28,
				],
				'selectors'  => [
					'{{WRAPPER}} .ua-pricing-card' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'pricing_style_icons_heading',
			[
				'label'     => esc_html__( 'Feature Icons Style', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'pricing_feature_icon_size',
			[
				'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 10,
						'max' => 40,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 16,
				],
				'selectors'  => [
					'{{WRAPPER}} .ua-feature-icon'     => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ua-feature-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'pricing_feature_active_icon_color',
			[
				'label'     => esc_html__( 'Active Icon Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ua-feature-icon-active'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .ua-feature-icon-active svg' => 'fill: {{VALUE}}; stroke: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pricing_feature_disabled_icon_color',
			[
				'label'     => esc_html__( 'Disabled Icon Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255, 255, 255, 0.7)',
				'selectors' => [
					'{{WRAPPER}} .ua-feature-icon-disabled'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .ua-feature-icon-disabled svg' => 'fill: {{VALUE}}; stroke: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'pricing_title_typography',
				'label'    => esc_html__( 'Title Typography', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ua-pricing-title',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'pricing_price_typography',
				'label'    => esc_html__( 'Price Typography', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ua-pricing-price',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'pricing_features_typography',
				'label'    => esc_html__( 'Features Typography', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ua-pricing-features li',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'pricing_btn_typography',
				'label'    => esc_html__( 'Button Typography', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ua-pricing-btn',
			]
		);

		$this->end_controls_section(); // End Pricing Cards Style Section
	}

	/**
	 * Helper to render an icon.
	 *
	 * @param array $icon
	 */
	protected function render_item_icon( $icon ) {
		if ( empty( $icon['value'] ) ) {
			return;
		}

		echo '<div class="ua-switcher-icon">';
		Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] );
		echo '</div>';
	}

	/**
	 * Render Dual Switcher with Outside Labels.
	 */
	public function render_dual_switcher_outer( $settings ) {
		?>
		<div class="ua-switcher-inner ua-switcher-first">
			<?php if ( '' !== $settings['switcher_first_label'] ) : ?>
				<div class="ua-switcher-label"><?php echo esc_html( $settings['switcher_first_label'] ); ?></div>
			<?php endif; ?>

			<?php if ( 'yes' === $settings['switcher_first_show_icon'] && ! empty( $settings['switcher_first_icon']['value'] ) ) : ?>
				<?php $this->render_item_icon( $settings['switcher_first_icon'] ); ?>
			<?php endif; ?>
		</div>

		<div class="ua-switcher-outer">
			<div class="ua-switcher-wrap">
				<div class="ua-switcher" data-switcher="1"></div>
				<div class="ua-switcher" data-switcher="2"></div>
				<div class="ua-switcher-bg"></div>
			</div>
		</div>

		<div class="ua-switcher-inner ua-switcher-second">
			<?php if ( '' !== $settings['switcher_second_label'] ) : ?>
				<div class="ua-switcher-label"><?php echo esc_html( $settings['switcher_second_label'] ); ?></div>
			<?php endif; ?>

			<?php if ( 'yes' === $settings['switcher_second_show_icon'] && ! empty( $settings['switcher_second_icon']['value'] ) ) : ?>
				<?php $this->render_item_icon( $settings['switcher_second_icon'] ); ?>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render Dual Switcher with Inside Labels.
	 */
	public function render_dual_switcher_inner( $settings ) {
		?>
		<div class="ua-switcher-outer">
			<div class="ua-switcher-wrap">

				<div class="ua-switcher" data-switcher="1">
					<div class="ua-switcher-inner ua-switcher-first">
						<?php if ( '' !== $settings['switcher_first_label'] ) : ?>
							<div class="ua-switcher-label"><?php echo esc_html( $settings['switcher_first_label'] ); ?></div>
						<?php endif; ?>

						<?php if ( 'yes' === $settings['switcher_first_show_icon'] && ! empty( $settings['switcher_first_icon']['value'] ) ) : ?>
							<?php $this->render_item_icon( $settings['switcher_first_icon'] ); ?>
						<?php endif; ?>
					</div>
				</div>

				<div class="ua-switcher" data-switcher="2">
					<div class="ua-switcher-inner ua-switcher-second">
						<?php if ( '' !== $settings['switcher_second_label'] ) : ?>
							<div class="ua-switcher-label"><?php echo esc_html( $settings['switcher_second_label'] ); ?></div>
						<?php endif; ?>

						<?php if ( 'yes' === $settings['switcher_second_show_icon'] && ! empty( $settings['switcher_second_icon']['value'] ) ) : ?>
							<?php $this->render_item_icon( $settings['switcher_second_icon'] ); ?>
						<?php endif; ?>
					</div>
				</div>

				<div class="ua-switcher-bg"></div>

			</div>
		</div>
		<?php
	}

	/**
	 * Render Multi Switcher with Repeater Items.
	 */
	public function render_multi_switcher( $settings ) {
		$items = ! empty( $settings['switcher_items'] ) ? $settings['switcher_items'] : [];
		if ( empty( $items ) ) {
			return;
		}
		?>
		<div class="ua-switcher-outer">
			<div class="ua-switcher-wrap">
				<?php foreach ( $items as $index => $item ) :
					$item_index = $index + 1;
				?>
					<div class="ua-switcher" data-switcher="<?php echo esc_attr( $item_index ); ?>">
						<div class="ua-switcher-inner">
							<?php if ( ! empty( $item['item_label'] ) ) : ?>
								<div class="ua-switcher-label"><?php echo esc_html( $item['item_label'] ); ?></div>
							<?php endif; ?>

							<?php if ( 'yes' === ( isset( $item['item_show_icon'] ) ? $item['item_show_icon'] : 'no' ) && ! empty( $item['item_icon']['value'] ) ) : ?>
								<?php $this->render_item_icon( $item['item_icon'] ); ?>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
				<div class="ua-switcher-bg"></div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render dynamic pricing cards from repeater.
	 *
	 * @param array $cards
	 * @param array $settings
	 */
	public function render_pricing_cards( $cards, $settings = [] ) {
		if ( empty( $cards ) || ! is_array( $cards ) ) {
			return;
		}

		$global_active_icon   = ! empty( $settings['pricing_active_feature_icon']['value'] ) ? $settings['pricing_active_feature_icon'] : [ 'value' => 'fas fa-check', 'library' => 'fa-solid' ];
		$global_disabled_icon = ! empty( $settings['pricing_disabled_feature_icon']['value'] ) ? $settings['pricing_disabled_feature_icon'] : [ 'value' => 'fas fa-times', 'library' => 'fa-solid' ];
		?>
		<div class="ua-pricing-grid">
			<?php
			foreach ( $cards as $index => $card ) :
				$title       = ! empty( $card['card_title'] ) ? $card['card_title'] : '';
				$subtitle    = ! empty( $card['card_subtitle'] ) ? $card['card_subtitle'] : '';
				$currency    = isset( $card['card_currency'] ) ? $card['card_currency'] : '$';
				$price       = isset( $card['card_price'] ) ? $card['card_price'] : '';
				$orig_price  = isset( $card['card_original_price'] ) ? $card['card_original_price'] : '';
				$period      = ! empty( $card['card_period'] ) ? $card['card_period'] : '';
				$bill_note   = ! empty( $card['card_billing_note'] ) ? $card['card_billing_note'] : '';
				$btn_text    = ! empty( $card['card_btn_text'] ) ? $card['card_btn_text'] : esc_html__( 'Get Started', 'ultraaddons-elementor-lite' );
				$btn_url     = ! empty( $card['card_btn_url']['url'] ) ? $card['card_btn_url']['url'] : '#';
				$bg_from     = ! empty( $card['card_bg_color_from'] ) ? $card['card_bg_color_from'] : '';
				$bg_to       = ! empty( $card['card_bg_color_to'] ) ? $card['card_bg_color_to'] : '';
				$btn_color   = ! empty( $card['card_btn_color'] ) ? $card['card_btn_color'] : '';

				$is_featured = ( isset( $card['card_featured'] ) && 'yes' === $card['card_featured'] );
				$badge_text  = ! empty( $card['card_badge_text'] ) ? $card['card_badge_text'] : '';
				$badge_bg    = ! empty( $card['card_badge_bg'] ) ? $card['card_badge_bg'] : '#ede9fe';
				$badge_color = ! empty( $card['card_badge_color'] ) ? $card['card_badge_color'] : '#6841fa';

				$card_active_icon   = ! empty( $card['card_active_icon']['value'] ) ? $card['card_active_icon'] : $global_active_icon;
				$card_disabled_icon = ! empty( $card['card_disabled_icon']['value'] ) ? $card['card_disabled_icon'] : $global_disabled_icon;

				$card_classes = [ 'ua-pricing-card' ];
				if ( $is_featured ) {
					$card_classes[] = 'ua-pricing-card-featured';
				}

				$card_style = '';
				if ( $bg_from && $bg_to ) {
					$card_style = 'style="background: linear-gradient(180deg, ' . esc_attr( $bg_from ) . ' 0%, ' . esc_attr( $bg_to ) . ' 100%);"';
				} elseif ( $bg_from ) {
					$card_style = 'style="background-color: ' . esc_attr( $bg_from ) . ';"';
				}

				$btn_style = '';
				if ( $btn_color ) {
					$btn_style = 'style="color: ' . esc_attr( $btn_color ) . ' !important;"';
				}
				?>
				<div class="<?php echo esc_attr( implode( ' ', $card_classes ) ); ?>" <?php echo $card_style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<?php if ( $is_featured && $badge_text ) : ?>
						<div class="ua-pricing-badge" style="background-color: <?php echo esc_attr( $badge_bg ); ?>; color: <?php echo esc_attr( $badge_color ); ?>;">
							<?php echo esc_html( $badge_text ); ?>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $card['card_icon']['value'] ) ) : ?>
						<div class="ua-pricing-icon">
							<?php Icons_Manager::render_icon( $card['card_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</div>
					<?php endif; ?>

					<?php if ( $title ) : ?>
						<h3 class="ua-pricing-title"><?php echo esc_html( $title ); ?></h3>
					<?php endif; ?>

					<?php if ( $subtitle ) : ?>
						<p class="ua-pricing-subtitle"><?php echo esc_html( $subtitle ); ?></p>
					<?php endif; ?>

					<?php if ( '' !== $price ) : ?>
						<div class="ua-pricing-price-wrap">
							<div class="ua-pricing-price">
								<?php if ( $currency ) : ?>
									<span class="ua-pricing-currency"><?php echo esc_html( $currency ); ?></span>
								<?php endif; ?>
								<span class="ua-pricing-amount"><?php echo esc_html( $price ); ?></span>
								<?php if ( '' !== $orig_price ) : ?>
									<del class="ua-pricing-original-price"><?php echo ( $currency ? esc_html( $currency ) : '' ) . esc_html( $orig_price ); ?></del>
								<?php endif; ?>
								<?php if ( $period ) : ?>
									<span class="ua-pricing-period"><?php echo esc_html( $period ); ?></span>
								<?php endif; ?>
							</div>
							<?php if ( $bill_note ) : ?>
								<p class="ua-pricing-billing-note"><?php echo esc_html( $bill_note ); ?></p>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if ( $btn_text ) : ?>
						<a href="<?php echo esc_url( $btn_url ); ?>" class="ua-pricing-btn" <?php echo $btn_style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
							<?php echo esc_html( $btn_text ); ?>
						</a>
					<?php endif; ?>

					<?php if ( ! empty( $card['card_features'] ) ) : ?>
						<ul class="ua-pricing-features">
							<?php
							$lines = explode( "\n", str_replace( "\r", '', $card['card_features'] ) );
							foreach ( $lines as $line ) :
								$line = trim( $line );
								if ( '' === $line ) {
									continue;
								}
								$is_disabled = false;
								$clean_text  = $line;

								if ( preg_match( '/^([xX\-✕×✗✘])\s*(.*)$/u', $line, $matches ) ) {
									$is_disabled = true;
									$clean_text  = $matches[2];
								} elseif ( preg_match( '/^([✓✔])\s*(.*)$/u', $line, $matches ) ) {
									$is_disabled = false;
									$clean_text  = $matches[2];
								}
								$class = $is_disabled ? 'ua-feature-disabled' : '';
								?>
								<li class="<?php echo esc_attr( $class ); ?>">
									<span class="ua-feature-icon <?php echo $is_disabled ? 'ua-feature-icon-disabled' : 'ua-feature-icon-active'; ?>">
										<?php
										if ( $is_disabled ) {
											Icons_Manager::render_icon( $card_disabled_icon, [ 'aria-hidden' => 'true' ] );
										} else {
											Icons_Manager::render_icon( $card_active_icon, [ 'aria-hidden' => 'true' ] );
										}
										?>
									</span>
									<?php echo esc_html( $clean_text ); ?>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Render Product / Food Showcase (Split View).
	 *
	 * @param array $settings
	 * @param string $prefix
	 */
	public function render_showcase_content( $settings, $prefix = 'switcher_first' ) {
		$image_data = ! empty( $settings[ $prefix . '_showcase_image'] ) ? $settings[ $prefix . '_showcase_image'] : [];
		$image_url  = ! empty( $image_data['url'] ) ? $image_data['url'] : '';
		$title      = ! empty( $settings[ $prefix . '_showcase_title'] ) ? $settings[ $prefix . '_showcase_title'] : '';
		$desc       = ! empty( $settings[ $prefix . '_showcase_desc'] ) ? $settings[ $prefix . '_showcase_desc'] : '';
		$price      = ! empty( $settings[ $prefix . '_showcase_price'] ) ? $settings[ $prefix . '_showcase_price'] : '';
		$btn_text   = ! empty( $settings[ $prefix . '_showcase_btn_text'] ) ? $settings[ $prefix . '_showcase_btn_text'] : '';
		$btn_url    = ! empty( $settings[ $prefix . '_showcase_btn_url']['url'] ) ? $settings[ $prefix . '_showcase_btn_url']['url'] : '#';
		?>
		<div class="ua-showcase-container">
			<div class="ua-showcase-media-wrap">
				<div class="ua-showcase-decor-line"></div>
				<?php if ( $image_url ) : ?>
					<div class="ua-showcase-img-holder">
						<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" class="ua-showcase-img" />
					</div>
				<?php endif; ?>
			</div>
			<div class="ua-showcase-content">
				<?php if ( $title ) : ?>
					<h2 class="ua-showcase-title"><?php echo esc_html( $title ); ?></h2>
				<?php endif; ?>

				<?php if ( $desc ) : ?>
					<p class="ua-showcase-desc"><?php echo esc_html( $desc ); ?></p>
				<?php endif; ?>

				<?php if ( $price ) : ?>
					<div class="ua-showcase-price"><?php echo esc_html( $price ); ?></div>
				<?php endif; ?>

				<?php if ( $btn_text ) : ?>
					<a href="<?php echo esc_url( $btn_url ); ?>" class="ua-showcase-btn">
						<?php echo esc_html( $btn_text ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render and sanitize rich WYSIWYG editor content safely.
	 *
	 * @param string $content
	 * @return string
	 */
	public function render_editor_content( $content ) {
		if ( empty( $content ) ) {
			return '';
		}

		if ( strpos( $content, '&lt;' ) !== false && strpos( $content, '<' ) === false ) {
			$content = html_entity_decode( $content, ENT_QUOTES, 'UTF-8' );
		}

		return wp_kses_post( $content );
	}

	/**
	 * Render widget output in frontend.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$style        = ! empty( $settings['switcher_style'] ) ? $settings['switcher_style'] : 'dual';
		$label_style  = ! empty( $settings['switcher_label_style'] ) ? $settings['switcher_label_style'] : 'outer';
		$active_index = ! empty( $settings['active_switcher'] ) ? (int) $settings['active_switcher'] : 1;
		$animation    = ! empty( $settings['content_animation'] ) ? $settings['content_animation'] : 'fade';

		if ( 'dual' === $style && $active_index > 2 ) {
			$active_index = 2;
		}

		echo '<div class="ua-content-toggle ua-switcher-style-' . esc_attr( $style ) . ' ua-switcher-label-style-' . esc_attr( $label_style ) . ' ua-animation-' . esc_attr( $animation ) . '">';

		echo '<div class="ua-switcher-container" data-active-switcher="' . esc_attr( $active_index ) . '">';
		if ( 'multi' === $style ) {
			$this->render_multi_switcher( $settings );
		} else {
			if ( 'inner' === $label_style ) {
				$this->render_dual_switcher_inner( $settings );
			} else {
				$this->render_dual_switcher_outer( $settings );
			}
		}
		echo '</div>';

		echo '<div class="ua-switcher-content-wrap">';
		if ( 'multi' === $style ) {
			$items = ! empty( $settings['switcher_items'] ) ? $settings['switcher_items'] : [];
			foreach ( $items as $index => $item ) :
				$item_index = $index + 1;
				echo '<div class="ua-switcher-content" data-switcher="' . esc_attr( $item_index ) . '">';
				echo '<div class="ua-switcher-content-inner">';
				if ( 'template' === $item['item_content_type'] ) {
					echo $this->render_template_content( $item['item_select_template'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				} else {
					echo $this->render_editor_content( $item['item_content'] );
				}
				echo '</div>';
				echo '</div>';
			endforeach;
		} else {
			echo '<div class="ua-switcher-content" data-switcher="1">';
			echo '<div class="ua-switcher-content-inner">';
			if ( 'pricing' === $settings['switcher_first_content_type'] ) {
				$this->render_pricing_cards( isset( $settings['switcher_first_pricing_cards'] ) ? $settings['switcher_first_pricing_cards'] : [], $settings );
			} elseif ( 'showcase' === $settings['switcher_first_content_type'] ) {
				$this->render_showcase_content( $settings, 'switcher_first' );
			} elseif ( 'template' === $settings['switcher_first_content_type'] ) {
				echo $this->render_template_content( $settings['switcher_first_select_template'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				echo $this->render_editor_content( $settings['switcher_first_content'] );
			}
			echo '</div>';
			echo '</div>';

			echo '<div class="ua-switcher-content" data-switcher="2">';
			echo '<div class="ua-switcher-content-inner">';
			if ( 'pricing' === $settings['switcher_second_content_type'] ) {
				$this->render_pricing_cards( isset( $settings['switcher_second_pricing_cards'] ) ? $settings['switcher_second_pricing_cards'] : [], $settings );
			} elseif ( 'showcase' === $settings['switcher_second_content_type'] ) {
				$this->render_showcase_content( $settings, 'switcher_second' );
			} elseif ( 'template' === $settings['switcher_second_content_type'] ) {
				echo $this->render_template_content( $settings['switcher_second_select_template'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				echo $this->render_editor_content( $settings['switcher_second_content'] );
			}
			echo '</div>';
			echo '</div>';
		}
		echo '</div>';

		echo '</div>';
	}
}
