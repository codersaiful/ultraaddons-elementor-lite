<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * UltraAddons Content Toggle Widget
 *
 * Feature-complete Content Toggle / Switcher widget supporting Dual & Multi modes,
 * Inside/Outside label placement, rich WYSIWYG and Elementor Library templates.
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
		return 'ultraaddons eicon-dual-button';
	}

	public function get_categories() {
		return [ 'ultraaddons', 'general' ];
	}

	public function get_keywords() {
		return [ 'ultraaddons', 'content toggle', 'content switcher', 'pricing toggle', 'toggle price plan', 'pricing table', 'switcher', 'toggle' ];
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
	 * Retrieve saved Elementor templates.
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
			$content = $frontend->get_builder_content_for_display( $id );
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				$edit_link = '<div class="ua-template-edit-link" style="text-align:center; padding: 6px; font-size: 11px; opacity: 0.7;"><a href="' . esc_url( admin_url( 'post.php?post=' . $id . '&action=elementor' ) ) . '" target="_blank" style="color: #6841fa; font-weight: 600;">✏️ ' . esc_html__( 'Edit Template', 'ultraaddons-elementor-lite' ) . '</a></div>';
				$content .= $edit_link;
			}
			return $content;
		}

		return '';
	}

	/**
	 * Render rich WYSIWYG editor content safely.
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
	 * Render an icon helper.
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
	 * Register widget controls.
	 */
	protected function register_controls() {

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

		// Dual Items Settings (First / Second Tabs)
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
				'default'   => 'Monthly',
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
					'value'   => 'fas fa-check',
					'library' => 'fa-solid',
				],
				'condition'   => [
					'switcher_first_show_icon' => 'yes',
					'switcher_style'            => 'dual',
				],
			]
		);

		$this->add_control(
			'switcher_first_content_type',
			[
				'label'     => esc_html__( 'Select Content Type', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'editor',
				'options'   => [
					'template' => esc_html__( 'Elementor Template', 'ultraaddons-elementor-lite' ),
					'editor'   => esc_html__( 'Editor', 'ultraaddons-elementor-lite' ),
				],
				'separator' => 'before',
				'condition' => [
					'switcher_style' => 'dual',
				],
			]
		);

		$this->add_control(
			'switcher_first_content',
			[
				'label'       => esc_html__( 'Content', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::WYSIWYG,
				'placeholder' => esc_html__( 'Tab Content', 'ultraaddons-elementor-lite' ),
				'default'     => '<h3>Monthly Subscription</h3><p>Enjoy flexible month-to-month access to all essential tools, standard features, and regular updates. Perfect for freelancers and individual creators starting out.</p>',
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
				'default'   => 'Yearly (Save 20%)',
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
					'value'   => 'fas fa-fire',
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
				'default'   => 'editor',
				'options'   => [
					'template' => esc_html__( 'Elementor Template', 'ultraaddons-elementor-lite' ),
					'editor'   => esc_html__( 'Editor', 'ultraaddons-elementor-lite' ),
				],
				'separator' => 'before',
				'condition' => [
					'switcher_style' => 'dual',
				],
			]
		);

		$this->add_control(
			'switcher_second_content',
			[
				'label'       => esc_html__( 'Content', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::WYSIWYG,
				'placeholder' => esc_html__( 'Tab Content', 'ultraaddons-elementor-lite' ),
				'default'     => '<h3>Yearly Subscription (Save 20%)</h3><p>Unlock everything with our annual plan and get 2 months free! Includes all premium widgets, priority 24/7 dedicated support, and unlimited project access.</p>',
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

		// Multi Items Repeater (Multi Mode)
		$repeater = new Repeater();

		$repeater->add_control(
			'item_label',
			[
				'label'       => esc_html__( 'Label', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [ 'active' => true ],
				'default'     => esc_html__( 'Plan', 'ultraaddons-elementor-lite' ),
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
					'value'   => 'fas fa-star',
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
				'default'     => '<p>Customize this plan content with your own text, features, or saved Elementor templates.</p>',
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
						'item_label'   => esc_html__( 'Basic', 'ultraaddons-elementor-lite' ),
						'item_content' => '<h3>Basic Plan</h3><p>Core features and essential tools designed for personal projects, blogs, and small websites.</p>',
					],
					[
						'item_label'   => esc_html__( 'Professional', 'ultraaddons-elementor-lite' ),
						'item_content' => '<h3>Professional Plan</h3><p>Advanced capabilities, premium integrations, and dedicated tools for growing businesses and teams.</p>',
					],
					[
						'item_label'   => esc_html__( 'Enterprise', 'ultraaddons-elementor-lite' ),
						'item_content' => '<h3>Enterprise Plan</h3><p>High performance, unlimited scale, dedicated VIP support, and custom workflows for large organizations.</p>',
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
					'none'         => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
					'fade'         => esc_html__( 'Fade', 'ultraaddons-elementor-lite' ),
					'slide-top'    => esc_html__( 'Slide Down', 'ultraaddons-elementor-lite' ),
					'slide-bottom' => esc_html__( 'Slide Up', 'ultraaddons-elementor-lite' ),
					'slide-left'   => esc_html__( 'Slide Left', 'ultraaddons-elementor-lite' ),
					'slide-right'  => esc_html__( 'Slide Right', 'ultraaddons-elementor-lite' ),
					'zoom-in'      => esc_html__( 'Zoom In', 'ultraaddons-elementor-lite' ),
					'zoom-out'     => esc_html__( 'Zoom Out', 'ultraaddons-elementor-lite' ),
				],
			]
		);

		$this->add_control(
			'content_anim_size',
			[
				'label'     => esc_html__( 'Animation Size', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'large',
				'options'   => [
					'small'  => esc_html__( 'Small', 'ultraaddons-elementor-lite' ),
					'medium' => esc_html__( 'Medium', 'ultraaddons-elementor-lite' ),
					'large'  => esc_html__( 'Large', 'ultraaddons-elementor-lite' ),
				],
				'condition' => [
					'content_animation!' => 'none',
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
				'default'   => '#64748b',
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
				'default'   => '#f1f5f9',
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
				'default'   => '#ffffff',
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
				'default'   => '#6841fa',
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
				'default'   => '#0f172a',
				'selectors' => [
					'{{WRAPPER}} .ua-switcher-container[data-active-switcher*="1"] .ua-switcher-first' => 'color: {{VALUE}};',
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
				'default'   => '#64748b',
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
				'default'   => '#6841fa',
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
				'default'   => '#0f172a',
				'selectors' => [
					'{{WRAPPER}} .ua-switcher-container[data-active-switcher*="2"] .ua-switcher-second' => 'color: {{VALUE}};',
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
				'default'   => '#64748b',
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
				'default'   => '#5a2bd8',
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
					'{{WRAPPER}} ' . $css_selector['control_wrap'] => 'width: {{SIZE}}{{UNIT}};',
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
					'size' => 24,
				],
				'selectors'  => [
					'{{WRAPPER}} ' . $css_selector['control_wrap'] => 'height: {{SIZE}}{{UNIT}};',
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
					'size' => 3,
				],
				'selectors'  => [
					'{{WRAPPER}} ' . $css_selector['control_wrap'] => 'margin: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'switcher_style'       => 'dual',
					'switcher_label_style' => 'outer',
				],
			]
		);

		$this->add_responsive_control(
			'tab_items_padding',
			[
				'label'      => esc_html__( 'Item Padding', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'top'      => 8,
					'right'    => 22,
					'bottom'   => 8,
					'left'     => 22,
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .ua-switcher' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
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
					'{{WRAPPER}} ' . $css_selector['control_bg'] => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_selector['control_list'] . '.ua-switcher-active' => 'max-width: {{SIZE}}{{UNIT}};',
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

		// Icon Heading
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
				'default'   => '#ffffff',
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
					'top'      => 25,
					'right'    => 25,
					'bottom'   => 25,
					'left'     => 25,
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
				'default'   => 'solid',
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
	 * Render widget output in frontend.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$style        = ! empty( $settings['switcher_style'] ) ? $settings['switcher_style'] : 'dual';
		$label_style  = ( 'dual' === $style && ! empty( $settings['switcher_label_style'] ) ) ? $settings['switcher_label_style'] : 'inner';
		$active_index = ! empty( $settings['active_switcher'] ) ? (int) $settings['active_switcher'] : 1;
		$animation    = ! empty( $settings['content_animation'] ) ? $settings['content_animation'] : 'fade';
		$anim_size    = ! empty( $settings['content_anim_size'] ) ? $settings['content_anim_size'] : 'large';

		if ( 'dual' === $style && $active_index > 2 ) {
			$active_index = 2;
		}

		echo '<div class="ua-content-toggle ua-switcher-style-' . esc_attr( $style ) . ' ua-switcher-label-style-' . esc_attr( $label_style ) . '">';

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
				echo '<div class="ua-switcher-content-inner ua-anim-size-' . esc_attr( $anim_size ) . ' ua-overlay-' . esc_attr( $animation ) . '">';
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
			echo '<div class="ua-switcher-content-inner ua-anim-size-' . esc_attr( $anim_size ) . ' ua-overlay-' . esc_attr( $animation ) . '">';
			if ( 'template' === $settings['switcher_first_content_type'] ) {
				echo $this->render_template_content( $settings['switcher_first_select_template'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				echo $this->render_editor_content( $settings['switcher_first_content'] );
			}
			echo '</div>';
			echo '</div>';

			echo '<div class="ua-switcher-content" data-switcher="2">';
			echo '<div class="ua-switcher-content-inner ua-anim-size-' . esc_attr( $anim_size ) . ' ua-overlay-' . esc_attr( $animation ) . '">';
			if ( 'template' === $settings['switcher_second_content_type'] ) {
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
