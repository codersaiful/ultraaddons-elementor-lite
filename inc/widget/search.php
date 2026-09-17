<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

/**
 * UltraAddons Search Widget
 * 
 * High-performance, modern Search widget for Elementor.
 * Supports both Live AJAX dropdown search and Classic Search forms,
 * with deep WooCommerce integration, category filtering, and customizable result cards.
 * 
 * @since 2.0.4
 * @package UltraAddons
 */
class Search extends Base {

    /**
     * Widget keywords for Elementor panel search.
     *
     * @return array
     */
    public function get_keywords() {
        return [ 'ultraaddons', 'ua', 'search', 'ajax search', 'live search', 'find', 'product search', 'woocommerce' ];
    }

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        $css_file = ULTRA_ADDONS_DIR . 'assets/css/widgets/search.css';
        $css_ver  = file_exists( $css_file ) ? filemtime( $css_file ) : ULTRA_ADDONS_VERSION;

        $js_file  = ULTRA_ADDONS_DIR . 'assets/js/frontend-search.js';
        $js_ver   = file_exists( $js_file ) ? filemtime( $js_file ) : ULTRA_ADDONS_VERSION;

        wp_register_style(
            'ultraaddons-search',
            ULTRA_ADDONS_ASSETS . 'css/widgets/search.css',
            [],
            $css_ver,
            'all'
        );

        wp_register_script(
            'ultraaddons-search',
            ULTRA_ADDONS_ASSETS . 'js/frontend-search.js',
            [ 'jquery' ],
            $js_ver,
            true
        );

        wp_localize_script(
            'ultraaddons-search',
            'uaSearchConfig',
            [
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'ultraaddons_search_nonce' ),
            ]
        );
    }

    /**
     * Style dependencies for this widget.
     *
     * @return array
     */
    public function get_style_depends() {
        return array_merge( parent::get_style_depends(), [ 'ultraaddons-search' ] );
    }

    /**
     * Script dependencies for this widget.
     *
     * @return array
     */
    public function get_script_depends() {
        return array_merge( parent::get_script_depends(), [ 'jquery', 'ultraaddons-search' ] );
    }

    /**
     * Register controls for the Search widget.
     */
    protected function register_controls() {
        $this->register_content_controls();
        $this->register_style_controls();
    }

    /**
     * Content Controls
     */
    protected function register_content_controls() {

        // --- Section: Search Settings ---
        $this->start_controls_section(
            'section_search_general',
            [
                'label' => esc_html__( 'Search Settings', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'search_mode',
            [
                'label'       => esc_html__( 'Search Mode', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'live_ajax',
                'options'     => [
                    'live_ajax' => esc_html__( 'Live AJAX Search', 'ultraaddons-elementor-lite' ),
                    'classic'   => esc_html__( 'Classic Search Form', 'ultraaddons-elementor-lite' ),
                ],
                'description' => esc_html__( 'Choose Live AJAX for instant dropdown results, or Classic for standard form submission.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'search_query_type',
            [
                'label'       => esc_html__( 'Target Post Type', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'any',
                'options'     => $this->get_available_post_types(),
                'description' => esc_html__( 'Select which post type to search through.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'enable_category_filter',
            [
                'label'        => esc_html__( 'Category Filter Dropdown', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'all_categories_text',
            [
                'label'     => esc_html__( 'All Categories Label', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'All Categories', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'enable_category_filter' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'search_placeholder',
            [
                'label'       => esc_html__( 'Placeholder', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Search anything...', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'Type placeholder...', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'search_button_enable',
            [
                'label'        => esc_html__( 'Search Button', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'search_button_position',
            [
                'label'     => esc_html__( 'Button Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'inner',
                'options'   => [
                    'inner' => esc_html__( 'Inside Input Box', 'ultraaddons-elementor-lite' ),
                    'outer' => esc_html__( 'Outside Input Box', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'search_button_enable' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'search_button_type',
            [
                'label'     => esc_html__( 'Button Type', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'icon',
                'options'   => [
                    'icon' => esc_html__( 'Icon Only', 'ultraaddons-elementor-lite' ),
                    'text' => esc_html__( 'Text Only', 'ultraaddons-elementor-lite' ),
                    'both' => esc_html__( 'Icon & Text', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'search_button_enable' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'search_button_text',
            [
                'label'     => esc_html__( 'Button Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Search', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'search_button_enable' => 'yes',
                    'search_button_type'   => [ 'text', 'both' ],
                ],
            ]
        );

        $this->add_control(
            'search_button_icon',
            [
                'label'     => esc_html__( 'Button Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'fas fa-search',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'search_button_enable' => 'yes',
                    'search_button_type'   => [ 'icon', 'both' ],
                ],
            ]
        );

        $this->add_control(
            'enable_clear_button',
            [
                'label'        => esc_html__( 'Clear (X) Button', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'search_mode' => 'live_ajax',
                ],
            ]
        );

        $this->add_control(
            'open_in_new_tab',
            [
                'label'        => esc_html__( 'Open Result in New Tab', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->end_controls_section();

        // --- Section: Live Results Display Options ---
        $this->start_controls_section(
            'section_search_results',
            [
                'label'     => esc_html__( 'Live Results Settings', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'search_mode' => 'live_ajax',
                ],
            ]
        );

        $this->add_control(
            'results_per_page',
            [
                'label'   => esc_html__( 'Results Limit', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 5,
                'min'     => 1,
                'max'     => 30,
                'step'    => 1,
            ]
        );

        $this->add_control(
            'min_characters',
            [
                'label'       => esc_html__( 'Minimum Characters to Trigger', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 2,
                'min'         => 1,
                'max'         => 10,
                'description' => esc_html__( 'Minimum letters the visitor must type before live search runs.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'show_thumbnails',
            [
                'label'        => esc_html__( 'Show Featured Image', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'exclude_without_thumb',
            [
                'label'        => esc_html__( 'Exclude Posts Without Image', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'condition'    => [
                    'show_thumbnails' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_description',
            [
                'label'        => esc_html__( 'Show Excerpt / Description', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'excerpt_length',
            [
                'label'     => esc_html__( 'Excerpt Word Limit', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 12,
                'min'       => 3,
                'max'       => 50,
                'condition' => [
                    'show_description' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_product_price',
            [
                'label'        => esc_html__( 'Show WooCommerce Price', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'show_badge',
            [
                'label'        => esc_html__( 'Show Post Type / Category Badge', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'show_view_result_btn',
            [
                'label'        => esc_html__( 'Show "View" Button', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'view_result_btn_text',
            [
                'label'     => esc_html__( '"View" Button Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'View', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'show_view_result_btn' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'enable_load_more',
            [
                'label'        => esc_html__( 'Enable "Load More" in Dropdown', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'load_more_text',
            [
                'label'     => esc_html__( 'Load More Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Load More Results', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'enable_load_more' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'no_results_text',
            [
                'label'   => esc_html__( 'No Results Message', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'No results found. Try another search.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Controls
     */
    protected function register_style_controls() {

        // --- Section: Input Field Style ---
        $this->start_controls_section(
            'section_style_input',
            [
                'label' => esc_html__( 'Search Input', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'input_height',
            [
                'label'      => esc_html__( 'Height', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 30, 'max' => 100 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 48 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-search-input'           => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-search-category-select' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-search-submit-btn'      => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'input_typography',
                'selector' => '{{WRAPPER}} .ua-search-input',
            ]
        );

        $this->add_control(
            'input_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-search-input' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#222222',
                'selectors' => [
                    '{{WRAPPER}} .ua-search-input' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_placeholder_color',
            [
                'label'     => esc_html__( 'Placeholder Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#888888',
                'selectors' => [
                    '{{WRAPPER}} .ua-search-input::-webkit-input-placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-search-input::-moz-placeholder'          => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-search-input:-ms-input-placeholder'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-search-input::placeholder'               => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'input_border',
                'selector' => '{{WRAPPER}} .ua-search-input-wrap',
            ]
        );

        $this->add_responsive_control(
            'input_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-search-input-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ua-search-input'      => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'input_box_shadow',
                'selector' => '{{WRAPPER}} .ua-search-input-wrap',
            ]
        );

        $this->add_responsive_control(
            'input_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-search-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // --- Section: Category Filter Style ---
        $this->start_controls_section(
            'section_style_category',
            [
                'label'     => esc_html__( 'Category Filter', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'enable_category_filter' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'cat_width',
            [
                'label'      => esc_html__( 'Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [ 'min' => 80, 'max' => 300 ],
                    '%'  => [ 'min' => 10, 'max' => 50 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-search-category-select' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'cat_typography',
                'selector' => '{{WRAPPER}} .ua-search-category-select',
            ]
        );

        $this->add_control(
            'cat_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f4f6f8',
                'selectors' => [
                    '{{WRAPPER}} .ua-search-category-select' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cat_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#444444',
                'selectors' => [
                    '{{WRAPPER}} .ua-search-category-select' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'cat_border',
                'selector' => '{{WRAPPER}} .ua-search-category-select',
            ]
        );

        $this->end_controls_section();

        // --- Section: Search Button Style ---
        $this->start_controls_section(
            'section_style_button',
            [
                'label'     => esc_html__( 'Search Button', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'search_button_enable' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_width',
            [
                'label'      => esc_html__( 'Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [ 'min' => 30, 'max' => 200 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-search-submit-btn' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 10, 'max' => 40 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 16 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-search-submit-btn i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-search-submit-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'button_typography',
                'selector'  => '{{WRAPPER}} .ua-search-submit-btn',
                'condition' => [
                    'search_button_type' => [ 'text', 'both' ],
                ],
            ]
        );

        $this->start_controls_tabs( 'button_style_tabs' );

        // Normal Tab
        $this->start_controls_tab(
            'button_tab_normal',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0284c7',
                'selectors' => [
                    '{{WRAPPER}} .ua-search-submit-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label'     => esc_html__( 'Text / Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-search-submit-btn'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-search-submit-btn svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover Tab
        $this->start_controls_tab(
            'button_tab_hover',
            [
                'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'button_bg_color_hover',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0369a1',
                'selectors' => [
                    '{{WRAPPER}} .ua-search-submit-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_text_color_hover',
            [
                'label'     => esc_html__( 'Text / Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-search-submit-btn:hover'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-search-submit-btn:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-search-submit-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'before',
            ]
        );

        $this->end_controls_section();

        // --- Section: Live Results Dropdown Style ---
        $this->start_controls_section(
            'section_style_dropdown',
            [
                'label'     => esc_html__( 'Results Dropdown', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'search_mode' => 'live_ajax',
                ],
            ]
        );

        $this->add_responsive_control(
            'dropdown_offset',
            [
                'label'      => esc_html__( 'Top Distance (Offset)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 50 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 6 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-search-results-dropdown' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dropdown_max_height',
            [
                'label'      => esc_html__( 'Max Height', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh' ],
                'range'      => [
                    'px' => [ 'min' => 150, 'max' => 800 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 380 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-search-results-list' => 'max-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'dropdown_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-search-results-dropdown' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'dropdown_border',
                'selector' => '{{WRAPPER}} .ua-search-results-dropdown',
            ]
        );

        $this->add_responsive_control(
            'dropdown_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-search-results-dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'dropdown_box_shadow',
                'selector' => '{{WRAPPER}} .ua-search-results-dropdown',
            ]
        );

        $this->end_controls_section();

        // --- Section: Result Items Style ---
        $this->start_controls_section(
            'section_style_items',
            [
                'label'     => esc_html__( 'Result Cards', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'search_mode' => 'live_ajax',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label'      => esc_html__( 'Item Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-search-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'item_hover_bg_color',
            [
                'label'     => esc_html__( 'Hover Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f8fafc',
                'selectors' => [
                    '{{WRAPPER}} .ua-search-item:hover, {{WRAPPER}} .ua-search-item.is-selected' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'item_separator_color',
            [
                'label'     => esc_html__( 'Separator Line Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f1f5f9',
                'selectors' => [
                    '{{WRAPPER}} .ua-search-item:not(:last-child)' => 'border-bottom: 1px solid {{VALUE}};',
                ],
            ]
        );

        // Title
        $this->add_control(
            'heading_title_style',
            [
                'label'     => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'item_title_typography',
                'selector' => '{{WRAPPER}} .ua-search-item-title',
            ]
        );

        $this->add_control(
            'item_title_color',
            [
                'label'     => esc_html__( 'Title Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0f172a',
                'selectors' => [
                    '{{WRAPPER}} .ua-search-item-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'item_title_hover_color',
            [
                'label'     => esc_html__( 'Title Hover Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0284c7',
                'selectors' => [
                    '{{WRAPPER}} .ua-search-item:hover .ua-search-item-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Thumbnail
        $this->add_control(
            'heading_thumb_style',
            [
                'label'     => esc_html__( 'Thumbnail Image', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_thumbnails' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'thumb_size',
            [
                'label'      => esc_html__( 'Image Size (Width & Height)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 30, 'max' => 120 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 52 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-search-item-thumb'     => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-search-item-thumb img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'show_thumbnails' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'thumb_border_radius',
            [
                'label'      => esc_html__( 'Image Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-search-item-thumb, {{WRAPPER}} .ua-search-item-thumb img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'show_thumbnails' => 'yes',
                ],
            ]
        );

        // Description / Excerpt
        $this->add_control(
            'heading_desc_style',
            [
                'label'     => esc_html__( 'Description / Excerpt', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_description' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'item_desc_typography',
                'selector'  => '{{WRAPPER}} .ua-search-item-excerpt',
                'condition' => [
                    'show_description' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'item_desc_color',
            [
                'label'     => esc_html__( 'Description Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#64748b',
                'selectors' => [
                    '{{WRAPPER}} .ua-search-item-excerpt' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_description' => 'yes',
                ],
            ]
        );

        // WooCommerce Price
        $this->add_control(
            'heading_price_style',
            [
                'label'     => esc_html__( 'WooCommerce Price', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_product_price' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'item_price_typography',
                'selector'  => '{{WRAPPER}} .ua-search-item-price',
                'condition' => [
                    'show_product_price' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'item_price_color',
            [
                'label'     => esc_html__( 'Price Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0284c7',
                'selectors' => [
                    '{{WRAPPER}} .ua-search-item-price' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_product_price' => 'yes',
                ],
            ]
        );

        // Badge Style
        $this->add_control(
            'heading_badge_style',
            [
                'label'     => esc_html__( 'Badge', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_badge' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'badge_bg_color',
            [
                'label'     => esc_html__( 'Badge Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#e0f2fe',
                'selectors' => [
                    '{{WRAPPER}} .ua-search-item-badge' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'show_badge' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'badge_text_color',
            [
                'label'     => esc_html__( 'Badge Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0369a1',
                'selectors' => [
                    '{{WRAPPER}} .ua-search-item-badge' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_badge' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // --- Section: Loader & Load More Style ---
        $this->start_controls_section(
            'section_style_extras',
            [
                'label'     => esc_html__( 'Loader & Load More', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'search_mode' => 'live_ajax',
                ],
            ]
        );

        $this->add_control(
            'spinner_color',
            [
                'label'     => esc_html__( 'Spinner Loading Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0284c7',
                'selectors' => [
                    '{{WRAPPER}} .ua-search-spinner' => 'color: {{VALUE}}; border-top-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'load_more_btn_bg',
            [
                'label'     => esc_html__( 'Load More Button Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f1f5f9',
                'selectors' => [
                    '{{WRAPPER}} .ua-search-load-more-btn' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'enable_load_more' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'load_more_btn_color',
            [
                'label'     => esc_html__( 'Load More Button Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#334155',
                'selectors' => [
                    '{{WRAPPER}} .ua-search-load-more-btn' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'enable_load_more' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Get available public post types for selection.
     *
     * @return array
     */
    protected function get_available_post_types() {
        $types = [
            'any'     => esc_html__( 'All Content', 'ultraaddons-elementor-lite' ),
            'post'    => esc_html__( 'Posts', 'ultraaddons-elementor-lite' ),
            'page'    => esc_html__( 'Pages', 'ultraaddons-elementor-lite' ),
        ];

        if ( class_exists( 'WooCommerce' ) ) {
            $types['product'] = esc_html__( 'WooCommerce Products', 'ultraaddons-elementor-lite' );
        }

        // Add registered custom post types
        $cpts = get_post_types( [ 'public' => true, '_builtin' => false ], 'objects' );
        foreach ( $cpts as $cpt ) {
            if ( 'product' !== $cpt->name ) {
                $types[ $cpt->name ] = $cpt->labels->singular_name;
            }
        }

        return $types;
    }

    /**
     * Render the widget on the frontend.
     */
    protected function render() {
        wp_enqueue_style( 'ultraaddons-search' );
        wp_enqueue_script( 'ultraaddons-search' );

        $settings = $this->get_settings_for_display();

        $search_mode        = ! empty( $settings['search_mode'] ) ? $settings['search_mode'] : 'live_ajax';
        $search_query_type  = ! empty( $settings['search_query_type'] ) ? $settings['search_query_type'] : 'any';
        $enable_cat         = ! empty( $settings['enable_category_filter'] ) && 'yes' === $settings['enable_category_filter'];
        $btn_enable         = ! empty( $settings['search_button_enable'] ) && 'yes' === $settings['search_button_enable'];
        $btn_pos            = ! empty( $settings['search_button_position'] ) ? $settings['search_button_position'] : 'inner';
        $open_new_tab       = ! empty( $settings['open_in_new_tab'] ) && 'yes' === $settings['open_in_new_tab'] ? '_blank' : '_self';
        $placeholder        = ! empty( $settings['search_placeholder'] ) ? $settings['search_placeholder'] : esc_html__( 'Search...', 'ultraaddons-elementor-lite' );
        $enable_clear       = ! empty( $settings['enable_clear_button'] ) && 'yes' === $settings['enable_clear_button'];

        $wrapper_classes = [
            'ua-search-wrapper',
            'ua-search-mode-' . sanitize_html_class( $search_mode ),
            'ua-search-btn-' . sanitize_html_class( $btn_pos ),
        ];

        if ( $enable_cat ) {
            $wrapper_classes[] = 'ua-search-has-cat';
        }

        // Data attributes for JS handling
        $this->add_render_attribute( 'wrapper', [
            'class'                  => implode( ' ', $wrapper_classes ),
            'data-search-mode'       => esc_attr( $search_mode ),
            'data-target-type'       => esc_attr( $search_query_type ),
            'data-results-per-page'  => esc_attr( ! empty( $settings['results_per_page'] ) ? $settings['results_per_page'] : 5 ),
            'data-min-chars'         => esc_attr( ! empty( $settings['min_characters'] ) ? $settings['min_characters'] : 2 ),
            'data-show-thumbs'       => esc_attr( ! empty( $settings['show_thumbnails'] ) ? $settings['show_thumbnails'] : 'no' ),
            'data-exclude-no-thumb'  => esc_attr( ! empty( $settings['exclude_without_thumb'] ) ? $settings['exclude_without_thumb'] : 'no' ),
            'data-show-desc'         => esc_attr( ! empty( $settings['show_description'] ) ? $settings['show_description'] : 'no' ),
            'data-excerpt-length'    => esc_attr( ! empty( $settings['excerpt_length'] ) ? $settings['excerpt_length'] : 12 ),
            'data-show-price'        => esc_attr( ! empty( $settings['show_product_price'] ) ? $settings['show_product_price'] : 'no' ),
            'data-show-badge'        => esc_attr( ! empty( $settings['show_badge'] ) ? $settings['show_badge'] : 'no' ),
            'data-show-view-btn'     => esc_attr( ! empty( $settings['show_view_result_btn'] ) ? $settings['show_view_result_btn'] : 'no' ),
            'data-view-btn-text'     => esc_attr( ! empty( $settings['view_result_btn_text'] ) ? $settings['view_result_btn_text'] : esc_html__( 'View', 'ultraaddons-elementor-lite' ) ),
            'data-open-new-tab'      => esc_attr( $open_new_tab ),
            'data-load-more'         => esc_attr( ! empty( $settings['enable_load_more'] ) ? $settings['enable_load_more'] : 'no' ),
            'data-load-more-text'    => esc_attr( ! empty( $settings['load_more_text'] ) ? $settings['load_more_text'] : esc_html__( 'Load More Results', 'ultraaddons-elementor-lite' ) ),
            'data-no-results-text'   => esc_attr( ! empty( $settings['no_results_text'] ) ? $settings['no_results_text'] : esc_html__( 'No results found.', 'ultraaddons-elementor-lite' ) ),
        ] );

        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>

            <form role="search" method="get" class="ua-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">

                <?php if ( $enable_cat ) : ?>
                    <div class="ua-search-cat-wrap">
                        <select name="ua_search_category" class="ua-search-category-select" aria-label="<?php esc_attr_e( 'Search Category', 'ultraaddons-elementor-lite' ); ?>">
                            <option value=""><?php echo esc_html( ! empty( $settings['all_categories_text'] ) ? $settings['all_categories_text'] : __( 'All Categories', 'ultraaddons-elementor-lite' ) ); ?></option>
                            <?php $this->render_category_options( $search_query_type ); ?>
                        </select>
                    </div>
                <?php endif; ?>

                <div class="ua-search-input-wrap">
                    <input 
                        type="search" 
                        class="ua-search-input" 
                        name="s" 
                        value="<?php echo esc_attr( get_search_query() ); ?>" 
                        placeholder="<?php echo esc_attr( $placeholder ); ?>"
                        aria-label="<?php esc_attr_e( 'Search', 'ultraaddons-elementor-lite' ); ?>"
                        autocomplete="off"
                        role="combobox"
                        aria-expanded="false"
                        aria-autocomplete="list"
                    >

                    <?php if ( 'any' !== $search_query_type ) : ?>
                        <input type="hidden" name="post_type" value="<?php echo esc_attr( $search_query_type ); ?>">
                    <?php endif; ?>

                    <?php if ( $enable_clear ) : ?>
                        <button type="button" class="ua-search-clear-btn" aria-label="<?php esc_attr_e( 'Clear Search', 'ultraaddons-elementor-lite' ); ?>" style="display: none;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    <?php endif; ?>

                    <span class="ua-search-spinner" aria-hidden="true" style="display: none;"></span>

                    <?php if ( $btn_enable && 'inner' === $btn_pos ) : ?>
                        <?php $this->render_submit_button( $settings ); ?>
                    <?php endif; ?>
                </div>

                <?php if ( $btn_enable && 'outer' === $btn_pos ) : ?>
                    <?php $this->render_submit_button( $settings ); ?>
                <?php endif; ?>

            </form>

            <?php if ( 'live_ajax' === $search_mode ) : ?>
                <div class="ua-search-results-dropdown" role="listbox" style="display: none;">
                    <ul class="ua-search-results-list"></ul>
                    <div class="ua-search-footer" style="display: none;">
                        <button type="button" class="ua-search-load-more-btn">
                            <span class="ua-search-load-more-text"><?php echo esc_html( ! empty( $settings['load_more_text'] ) ? $settings['load_more_text'] : __( 'Load More Results', 'ultraaddons-elementor-lite' ) ); ?></span>
                            <span class="ua-search-load-more-spinner" style="display: none;"></span>
                        </button>
                    </div>
                </div>
            <?php endif; ?>

        </div>
        <?php
    }

    /**
     * Render the submit button.
     *
     * @param array $settings
     */
    protected function render_submit_button( $settings ) {
        $btn_type = ! empty( $settings['search_button_type'] ) ? $settings['search_button_type'] : 'icon';
        $btn_text = ! empty( $settings['search_button_text'] ) ? $settings['search_button_text'] : esc_html__( 'Search', 'ultraaddons-elementor-lite' );
        ?>
        <button type="submit" class="ua-search-submit-btn" aria-label="<?php esc_attr_e( 'Submit Search', 'ultraaddons-elementor-lite' ); ?>">
            <?php if ( in_array( $btn_type, [ 'icon', 'both' ], true ) && ! empty( $settings['search_button_icon']['value'] ) ) : ?>
                <span class="ua-search-btn-icon">
                    <?php Icons_Manager::render_icon( $settings['search_button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                </span>
            <?php endif; ?>
            <?php if ( in_array( $btn_type, [ 'text', 'both' ], true ) && ! empty( $btn_text ) ) : ?>
                <span class="ua-search-btn-text"><?php echo esc_html( $btn_text ); ?></span>
            <?php endif; ?>
        </button>
        <?php
    }

    /**
     * Render category options for the category filter dropdown.
     *
     * @param string $post_type
     */
    protected function render_category_options( $post_type ) {
        if ( 'any' === $post_type ) {
            $has_wc = class_exists( 'WooCommerce' );

            $categories = get_terms( [
                'taxonomy'   => 'category',
                'hide_empty' => true,
            ] );

            if ( ! is_wp_error( $categories ) && ! empty( $categories ) ) {
                if ( $has_wc ) {
                    echo '<optgroup label="' . esc_attr__( 'Blog Categories', 'ultraaddons-elementor-lite' ) . '">';
                }
                foreach ( $categories as $category ) {
                    $link      = get_term_link( $category );
                    $data_link = ! is_wp_error( $link ) ? $link : '';
                    echo '<option value="' . esc_attr( $category->term_id ) . '" data-taxonomy="category" data-post-type="post" data-link="' . esc_url( $data_link ) . '">' . esc_html( $category->name ) . '</option>';
                }
                if ( $has_wc ) {
                    echo '</optgroup>';
                }
            }

            if ( $has_wc ) {
                $prod_cats = get_terms( [
                    'taxonomy'   => 'product_cat',
                    'hide_empty' => true,
                ] );
                if ( ! is_wp_error( $prod_cats ) && ! empty( $prod_cats ) ) {
                    echo '<optgroup label="' . esc_attr__( 'Product Categories', 'ultraaddons-elementor-lite' ) . '">';
                    foreach ( $prod_cats as $category ) {
                        $link      = get_term_link( $category );
                        $data_link = ! is_wp_error( $link ) ? $link : '';
                        echo '<option value="' . esc_attr( $category->term_id ) . '" data-taxonomy="product_cat" data-post-type="product" data-link="' . esc_url( $data_link ) . '">' . esc_html( $category->name ) . '</option>';
                    }
                    echo '</optgroup>';
                }
            }
        } elseif ( 'product' === $post_type && class_exists( 'WooCommerce' ) ) {
            $prod_cats = get_terms( [
                'taxonomy'   => 'product_cat',
                'hide_empty' => true,
            ] );
            if ( ! is_wp_error( $prod_cats ) && ! empty( $prod_cats ) ) {
                foreach ( $prod_cats as $category ) {
                    $link      = get_term_link( $category );
                    $data_link = ! is_wp_error( $link ) ? $link : '';
                    echo '<option value="' . esc_attr( $category->term_id ) . '" data-taxonomy="product_cat" data-post-type="product" data-link="' . esc_url( $data_link ) . '">' . esc_html( $category->name ) . '</option>';
                }
            }
        } else {
            $taxonomies = get_object_taxonomies( $post_type, 'names' );
            $taxonomy   = ! empty( $taxonomies ) ? reset( $taxonomies ) : 'category';
            $terms      = get_terms( [
                'taxonomy'   => $taxonomy,
                'hide_empty' => true,
            ] );
            if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
                foreach ( $terms as $category ) {
                    $link      = get_term_link( $category );
                    $data_link = ! is_wp_error( $link ) ? $link : '';
                    echo '<option value="' . esc_attr( $category->term_id ) . '" data-taxonomy="' . esc_attr( $taxonomy ) . '" data-post-type="' . esc_attr( $post_type ) . '" data-link="' . esc_url( $data_link ) . '">' . esc_html( $category->name ) . '</option>';
                }
            }
        }
    }

    /**
     * Static AJAX Search Handler
     * Called via loader.php on wp_ajax_ultraaddons_ajax_search and nopriv.
     */
    public static function ajax_search() {
        // Verify nonce
        check_ajax_referer( 'ultraaddons_search_nonce', 'nonce' );

        $keyword          = ! empty( $_POST['keyword'] ) ? sanitize_text_field( wp_unslash( $_POST['keyword'] ) ) : '';
        $post_type        = ! empty( $_POST['post_type'] ) ? sanitize_text_field( wp_unslash( $_POST['post_type'] ) ) : 'any';
        $category         = ! empty( $_POST['category'] ) ? absint( $_POST['category'] ) : 0;
        $taxonomy         = ! empty( $_POST['taxonomy'] ) ? sanitize_text_field( wp_unslash( $_POST['taxonomy'] ) ) : '';
        $per_page         = ! empty( $_POST['per_page'] ) ? min( 50, max( 1, absint( $_POST['per_page'] ) ) ) : 5;
        $offset           = ! empty( $_POST['offset'] ) ? absint( $_POST['offset'] ) : 0;
        $exclude_no_thumb = ! empty( $_POST['exclude_no_thumb'] ) && 'yes' === $_POST['exclude_no_thumb'];

        // Options for output rendering
        $options = [
            'show_thumbs'   => ! empty( $_POST['show_thumbs'] ) && 'yes' === $_POST['show_thumbs'],
            'show_desc'     => ! empty( $_POST['show_desc'] ) && 'yes' === $_POST['show_desc'],
            'excerpt_len'   => ! empty( $_POST['excerpt_len'] ) ? absint( $_POST['excerpt_len'] ) : 12,
            'show_price'    => ! empty( $_POST['show_price'] ) && 'yes' === $_POST['show_price'],
            'show_badge'    => ! empty( $_POST['show_badge'] ) && 'yes' === $_POST['show_badge'],
            'show_view_btn' => ! empty( $_POST['show_view_btn'] ) && 'yes' === $_POST['show_view_btn'],
            'view_btn_text' => ! empty( $_POST['view_btn_text'] ) ? sanitize_text_field( wp_unslash( $_POST['view_btn_text'] ) ) : esc_html__( 'View', 'ultraaddons-elementor-lite' ),
            'target'        => ! empty( $_POST['open_new_tab'] ) && '_blank' === $_POST['open_new_tab'] ? '_blank' : '_self',
        ];

        if ( empty( $keyword ) || mb_strlen( $keyword ) < 1 ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Please enter a search keyword.', 'ultraaddons-elementor-lite' ) ] );
        }

        $query_args = [
            'post_status'         => 'publish',
            'posts_per_page'      => $per_page,
            'offset'              => $offset,
            'ignore_sticky_posts' => true,
            'no_found_rows'       => false,
        ];

        if ( ! empty( $keyword ) ) {
            $query_args['s'] = $keyword;
        }

        // Category / Taxonomy query
        if ( ! empty( $category ) ) {
            $term = get_term( $category );
            $tax  = ( $term && ! is_wp_error( $term ) ) ? $term->taxonomy : ( ! empty( $taxonomy ) ? $taxonomy : 'category' );

            $query_args['tax_query'] = [
                [
                    'taxonomy' => $tax,
                    'field'    => 'term_id',
                    'terms'    => $category,
                ],
            ];

            // Post type hint from taxonomy if post_type is generic
            if ( 'product_cat' === $tax && ( 'any' === $post_type || empty( $post_type ) ) ) {
                $post_types = [ 'product' ];
            } elseif ( in_array( $tax, [ 'category', 'post_tag' ], true ) && ( 'any' === $post_type || empty( $post_type ) ) ) {
                $post_types = [ 'post' ];
            }
        }

        // Post types handling
        if ( empty( $post_types ) ) {
            if ( 'any' === $post_type ) {
                $all_types = get_post_types( [ 'public' => true ], 'names' );
                unset(
                    $all_types['attachment'],
                    $all_types['revision'],
                    $all_types['nav_menu_item'],
                    $all_types['custom_css'],
                    $all_types['customize_changeset'],
                    $all_types['elementor_library'],
                    $all_types['elementor_font'],
                    $all_types['elementor_icons'],
                    $all_types['header_footer'],
                    $all_types['ua_mega_menu'],
                    $all_types['wpr_mega_menu'],
                    $all_types['wpr_templates'],
                    $all_types['wp_block'],
                    $all_types['wp_template'],
                    $all_types['wp_template_part'],
                    $all_types['wp_navigation'],
                    $all_types['e-landing-page']
                );
                $post_types = array_values( $all_types );
            } else {
                $post_types = [ $post_type ];
            }
        }
        $query_args['post_type'] = $post_types;

        // Exclude without thumbnail
        if ( $exclude_no_thumb ) {
            $query_args['meta_query'] = [
                [
                    'key'     => '_thumbnail_id',
                    'compare' => 'EXISTS',
                ],
            ];
        }

        $the_query = new \WP_Query( $query_args );

        ob_start();

        if ( $the_query->have_posts() ) {
            while ( $the_query->have_posts() ) {
                $the_query->the_post();
                self::render_search_item( get_post(), $options );
            }
        } elseif ( 0 === $offset ) {
            $no_res_text = ! empty( $_POST['no_results_text'] ) ? sanitize_text_field( wp_unslash( $_POST['no_results_text'] ) ) : esc_html__( 'No results found.', 'ultraaddons-elementor-lite' );
            echo '<li class="ua-search-no-results"><p>' . esc_html( $no_res_text ) . '</p></li>';
        }

        $html = ob_get_clean();
        wp_reset_postdata();

        $total_found = (int) $the_query->found_posts;
        $loaded_so_far = $offset + (int) $the_query->post_count;
        $has_more = $loaded_so_far < $total_found;

        wp_send_json_success( [
            'html'        => $html,
            'count'       => (int) $the_query->post_count,
            'total'       => $total_found,
            'has_more'    => $has_more,
            'offset_next' => $loaded_so_far,
        ] );
    }

    /**
     * Render a single search result item (<li>).
     *
     * @param \WP_Post $post
     * @param array    $options
     */
    protected static function render_search_item( $post, $options ) {
        $permalink   = get_permalink( $post );
        $title       = get_the_title( $post );
        $post_type   = get_post_type( $post );
        $target_attr = ' target="' . esc_attr( $options['target'] ) . '"';

        ?>
        <li class="ua-search-item" data-post-id="<?php echo esc_attr( $post->ID ); ?>" role="option">

            <?php if ( $options['show_thumbs'] ) : ?>
                <div class="ua-search-item-thumb">
                    <a href="<?php echo esc_url( $permalink ); ?>"<?php echo $target_attr; ?> tabindex="-1">
                        <?php if ( has_post_thumbnail( $post ) ) : ?>
                            <?php echo get_the_post_thumbnail( $post, 'thumbnail' ); ?>
                        <?php else : ?>
                            <div class="ua-search-thumb-placeholder">
                                <i class="eicon-image" aria-hidden="true"></i>
                            </div>
                        <?php endif; ?>
                    </a>
                </div>
            <?php endif; ?>

            <div class="ua-search-item-content">

                <div class="ua-search-item-header">
                    <h4 class="ua-search-item-title">
                        <a href="<?php echo esc_url( $permalink ); ?>"<?php echo $target_attr; ?>>
                            <?php echo esc_html( $title ); ?>
                        </a>
                    </h4>

                    <?php if ( $options['show_badge'] ) : ?>
                        <span class="ua-search-item-badge">
                            <?php echo esc_html( self::get_item_badge_label( $post ) ); ?>
                        </span>
                    <?php endif; ?>
                </div>

                <?php if ( $options['show_desc'] ) : ?>
                    <p class="ua-search-item-excerpt">
                        <?php 
                        $excerpt = ! empty( $post->post_excerpt ) ? $post->post_excerpt : wp_strip_all_tags( $post->post_content );
                        echo esc_html( wp_trim_words( $excerpt, $options['excerpt_len'] ) ); 
                        ?>
                    </p>
                <?php endif; ?>

                <?php if ( $options['show_price'] && 'product' === $post_type && class_exists( 'WooCommerce' ) ) : ?>
                    <?php $product = wc_get_product( $post->ID ); ?>
                    <?php if ( $product ) : ?>
                        <div class="ua-search-item-price">
                            <?php echo wp_kses_post( $product->get_price_html() ); ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

            </div>

            <?php if ( $options['show_view_btn'] ) : ?>
                <div class="ua-search-item-action">
                    <a href="<?php echo esc_url( $permalink ); ?>" class="ua-search-view-btn"<?php echo $target_attr; ?>>
                        <?php echo esc_html( $options['view_btn_text'] ); ?>
                    </a>
                </div>
            <?php endif; ?>

        </li>
        <?php
    }

    /**
     * Helper to get a human-readable badge label (category or post type name).
     *
     * @param \WP_Post $post
     * @return string
     */
    protected static function get_item_badge_label( $post ) {
        if ( 'product' === $post->post_type && class_exists( 'WooCommerce' ) ) {
            $terms = get_the_terms( $post->ID, 'product_cat' );
            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                return $terms[0]->name;
            }
            return esc_html__( 'Product', 'ultraaddons-elementor-lite' );
        }

        $categories = get_the_category( $post->ID );
        if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
            return $categories[0]->name;
        }

        $obj = get_post_type_object( $post->post_type );
        return $obj ? $obj->labels->singular_name : ucfirst( $post->post_type );
    }

}
