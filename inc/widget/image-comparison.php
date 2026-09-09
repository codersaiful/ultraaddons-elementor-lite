<?php
namespace UltraAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

/**
 * Image Comparison (Before/After) Widget for UltraAddons.
 *
 * Provides a modern, GPU-accelerated responsive image comparison slider
 * with horizontal & vertical orientations, multiple interaction triggers,
 * custom aspect ratios, smart label collision handling, and zero external dependencies.
 *
 * @since 2.0.3.6
 * @package UltraAddons
 */
class Image_Comparison extends Base {

    /**
     * Constructor: Register widget CSS and JS dependencies.
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        $css_file = ULTRA_ADDONS_DIR . 'assets/css/widgets/image-comparison.css';
        $css_ver  = file_exists( $css_file ) ? filemtime( $css_file ) : ULTRA_ADDONS_VERSION;

        wp_register_style(
            'ultraaddons-image-comparison',
            ULTRA_ADDONS_ASSETS . 'css/widgets/image-comparison.css',
            [ 'ultraaddons-widgets-style' ],
            $css_ver
        );

        $js_file = ULTRA_ADDONS_DIR . 'assets/js/frontend-image-comparison.js';
        $js_ver  = file_exists( $js_file ) ? filemtime( $js_file ) : ULTRA_ADDONS_VERSION;

        wp_register_script(
            'ultraaddons-image-comparison',
            ULTRA_ADDONS_ASSETS . 'js/frontend-image-comparison.js',
            [ 'jquery', 'elementor-frontend' ],
            $js_ver,
            true
        );
    }

    /**
     * Widget Style Dependencies.
     */
    public function get_style_depends() {
        return [
            'ultraaddons-widgets-style',
            'ultraaddons-image-comparison',
        ];
    }

    /**
     * Widget Script Dependencies.
     */
    public function get_script_depends() {
        return [ 'ultraaddons-image-comparison' ];
    }

    /**
     * Widget Keywords.
     */
    public function get_keywords() {
        return [
            'ultraaddons',
            'ua',
            'image comparison',
            'before after',
            'compare',
            'image compare',
            'slider',
            'diff',
            'photo compare',
        ];
    }

    /**
     * Register Widget Controls.
     */
    protected function register_controls() {
        $this->register_content_controls();
        $this->register_style_controls();
    }

    /**
     * Register Content Tab Controls.
     */
    protected function register_content_controls() {

        // ============================================
        // Section: Images
        // ============================================
        $this->start_controls_section(
            'ua_ic_section_images',
            [
                'label' => esc_html__( 'Images', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ua_ic_before_heading',
            [
                'label'     => esc_html__( 'Before Image (Base)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'none',
            ]
        );

        $this->add_control(
            'ua_ic_before_image',
            [
                'label'   => esc_html__( 'Choose Image', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'ua_ic_before_label',
            [
                'label'       => esc_html__( 'Before Label', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Before', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'Before', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'ua_ic_after_heading',
            [
                'label'     => esc_html__( 'After Image (Comparison)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'ua_ic_after_image',
            [
                'label'   => esc_html__( 'Choose Image', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'ua_ic_after_label',
            [
                'label'       => esc_html__( 'After Label', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'After', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'After', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [
                    'active' => true,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'      => 'ua_ic_image_size',
                'default'   => 'full',
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        // ============================================
        // Section: Comparison Settings
        // ============================================
        $this->start_controls_section(
            'ua_ic_section_settings',
            [
                'label' => esc_html__( 'Comparison Settings', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ua_ic_orientation',
            [
                'label'   => esc_html__( 'Orientation', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'horizontal' => esc_html__( 'Horizontal (Left / Right)', 'ultraaddons-elementor-lite' ),
                    'vertical'   => esc_html__( 'Vertical (Top / Bottom)', 'ultraaddons-elementor-lite' ),
                ],
                'default' => 'horizontal',
            ]
        );

        $this->add_control(
            'ua_ic_trigger',
            [
                'label'   => esc_html__( 'Interaction Mode', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'drag'  => esc_html__( 'Drag & Slide', 'ultraaddons-elementor-lite' ),
                    'hover' => esc_html__( 'Mouse Hover', 'ultraaddons-elementor-lite' ),
                    'click' => esc_html__( 'Click to Move', 'ultraaddons-elementor-lite' ),
                ],
                'default' => 'drag',
            ]
        );

        $this->add_control(
            'ua_ic_starting_position',
            [
                'label'   => esc_html__( 'Initial Divider Position (%)', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range'   => [
                    '%' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
            ]
        );

        $this->add_control(
            'ua_ic_intro_sweep',
            [
                'label'        => esc_html__( 'Intro Demo Sweep', 'ultraaddons-elementor-lite' ),
                'description'  => esc_html__( 'Performs a gentle preview sweep when scrolled into viewport to demonstrate interactivity.', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'ua_ic_hover_reset',
            [
                'label'        => esc_html__( 'Reset Position on Mouse Leave', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'condition'    => [
                    'ua_ic_trigger' => 'hover',
                ],
            ]
        );

        $this->end_controls_section();

        // ============================================
        // Section: Labels Settings
        // ============================================
        $this->start_controls_section(
            'ua_ic_section_labels',
            [
                'label' => esc_html__( 'Labels Settings', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ua_ic_show_labels',
            [
                'label'        => esc_html__( 'Show Labels', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'ua_ic_labels_visibility',
            [
                'label'   => esc_html__( 'Visibility Mode', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'always'    => esc_html__( 'Always Visible', 'ultraaddons-elementor-lite' ),
                    'hover'     => esc_html__( 'Show on Hover Only', 'ultraaddons-elementor-lite' ),
                    'auto_fade' => esc_html__( 'Auto-Hide Near Divider Handle', 'ultraaddons-elementor-lite' ),
                ],
                'default'   => 'auto_fade',
                'condition' => [
                    'ua_ic_show_labels' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ua_ic_label_position_h',
            [
                'label'   => esc_html__( 'Vertical Alignment', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'top'    => esc_html__( 'Top', 'ultraaddons-elementor-lite' ),
                    'center' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ),
                    'bottom' => esc_html__( 'Bottom', 'ultraaddons-elementor-lite' ),
                ],
                'default'   => 'top',
                'condition' => [
                    'ua_ic_show_labels' => 'yes',
                    'ua_ic_orientation' => 'horizontal',
                ],
            ]
        );

        $this->add_control(
            'ua_ic_label_position_v',
            [
                'label'   => esc_html__( 'Horizontal Alignment', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'left'   => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                    'center' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ),
                    'right'  => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                ],
                'default'   => 'left',
                'condition' => [
                    'ua_ic_show_labels' => 'yes',
                    'ua_ic_orientation' => 'vertical',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register Style Tab Controls.
     */
    protected function register_style_controls() {

        // ============================================
        // Style Section: Container & Layout
        // ============================================
        $this->start_controls_section(
            'ua_ic_style_container',
            [
                'label' => esc_html__( 'Container & Layout', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'ua_ic_aspect_ratio',
            [
                'label'       => esc_html__( 'Aspect Ratio', 'ultraaddons-elementor-lite' ),
                'description' => esc_html__( 'Locks both images to an identical aspect ratio to avoid distortion when images differ in natural dimensions.', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'options'     => [
                    'auto'   => esc_html__( 'Original / Natural', 'ultraaddons-elementor-lite' ),
                    '16-9'   => esc_html__( '16:9 (Widescreen)', 'ultraaddons-elementor-lite' ),
                    '4-3'    => esc_html__( '4:3 (Standard)', 'ultraaddons-elementor-lite' ),
                    '1-1'    => esc_html__( '1:1 (Square)', 'ultraaddons-elementor-lite' ),
                    '21-9'   => esc_html__( '21:9 (Cinematic)', 'ultraaddons-elementor-lite' ),
                    'custom' => esc_html__( 'Custom Height', 'ultraaddons-elementor-lite' ),
                ],
                'default'     => 'auto',
            ]
        );

        $this->add_responsive_control(
            'ua_ic_custom_height',
            [
                'label'      => esc_html__( 'Custom Height', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh' ],
                'range'      => [
                    'px' => [
                        'min' => 150,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 450,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-ic-container' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'ua_ic_aspect_ratio' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'ua_ic_object_fit',
            [
                'label'     => esc_html__( 'Image Fit Mode', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'cover'   => esc_html__( 'Cover (Crop & Fill)', 'ultraaddons-elementor-lite' ),
                    'contain' => esc_html__( 'Contain (Fit Entire Image)', 'ultraaddons-elementor-lite' ),
                    'fill'    => esc_html__( 'Stretch / Fill', 'ultraaddons-elementor-lite' ),
                ],
                'default'   => 'cover',
                'selectors' => [
                    '{{WRAPPER}} .ua-ic-image-wrap img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_ic_object_position',
            [
                'label'     => esc_html__( 'Image Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'center center' => esc_html__( 'Center Center', 'ultraaddons-elementor-lite' ),
                    'top center'    => esc_html__( 'Top Center', 'ultraaddons-elementor-lite' ),
                    'bottom center' => esc_html__( 'Bottom Center', 'ultraaddons-elementor-lite' ),
                    'center left'   => esc_html__( 'Center Left', 'ultraaddons-elementor-lite' ),
                    'center right'  => esc_html__( 'Center Right', 'ultraaddons-elementor-lite' ),
                ],
                'default'   => 'center center',
                'selectors' => [
                    '{{WRAPPER}} .ua-ic-image-wrap img' => 'object-position: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'ua_ic_container_border',
                'selector'  => '{{WRAPPER}} .ua-ic-container',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'ua_ic_container_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-ic-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_ic_container_shadow',
                'selector' => '{{WRAPPER}} .ua-ic-container',
            ]
        );

        $this->end_controls_section();

        // ============================================
        // Style Section: Divider Line
        // ============================================
        $this->start_controls_section(
            'ua_ic_style_divider',
            [
                'label' => esc_html__( 'Divider Line', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'ua_ic_line_color',
            [
                'label'     => esc_html__( 'Line Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}}' => '--ua-ic-line-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-ic-divider-line' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_ic_line_width',
            [
                'label'      => esc_html__( 'Line Thickness', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 1,
                        'max'  => 12,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 2,
                ],
                'selectors'  => [
                    '{{WRAPPER}}' => '--ua-ic-line-width: {{SIZE}}px;',
                    '{{WRAPPER}} .ua-image-comparison-wrap[data-orientation="horizontal"] .ua-ic-divider-line' => 'border-left-width: {{SIZE}}px;',
                    '{{WRAPPER}} .ua-image-comparison-wrap[data-orientation="vertical"] .ua-ic-divider-line'   => 'border-top-width: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_control(
            'ua_ic_line_style',
            [
                'label'     => esc_html__( 'Line Style', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'solid'  => esc_html__( 'Solid', 'ultraaddons-elementor-lite' ),
                    'dashed' => esc_html__( 'Dashed', 'ultraaddons-elementor-lite' ),
                    'dotted' => esc_html__( 'Dotted', 'ultraaddons-elementor-lite' ),
                ],
                'default'   => 'solid',
                'selectors' => [
                    '{{WRAPPER}} .ua-ic-divider-line' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_ic_line_shadow',
            [
                'label'        => esc_html__( 'Line Shadow / Glow', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->end_controls_section();

        // ============================================
        // Style Section: Handle Button
        // ============================================
        $this->start_controls_section(
            'ua_ic_style_handle',
            [
                'label' => esc_html__( 'Handle Button', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'ua_ic_handle_shape',
            [
                'label'   => esc_html__( 'Handle Shape', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'circle'  => esc_html__( 'Circle', 'ultraaddons-elementor-lite' ),
                    'rounded' => esc_html__( 'Rounded Square', 'ultraaddons-elementor-lite' ),
                    'pill'    => esc_html__( 'Capsule / Pill', 'ultraaddons-elementor-lite' ),
                    'bar'     => esc_html__( 'Clean Bar', 'ultraaddons-elementor-lite' ),
                ],
                'default' => 'circle',
            ]
        );

        $this->add_responsive_control(
            'ua_ic_handle_size',
            [
                'label'      => esc_html__( 'Handle Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 26,
                        'max'  => 72,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 42,
                ],
                'selectors'  => [
                    '{{WRAPPER}}' => '--ua-ic-handle-size: {{SIZE}}px;',
                    '{{WRAPPER}} .ua-ic-handle-btn:not(.ua-ic-shape-bar)' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_control(
            'ua_ic_handle_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-ic-handle-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_ic_handle_arrow_color',
            [
                'label'     => esc_html__( 'Arrows Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0274be',
                'selectors' => [
                    '{{WRAPPER}} .ua-ic-handle-btn' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_ic_handle_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 10,
                        'max'  => 32,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-ic-handle-arrows svg' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'ua_ic_handle_border',
                'selector'  => '{{WRAPPER}} .ua-ic-handle-btn',
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_ic_handle_shadow',
                'selector' => '{{WRAPPER}} .ua-ic-handle-btn',
            ]
        );

        $this->end_controls_section();

        // ============================================
        // Style Section: Labels Style
        // ============================================
        $this->start_controls_section(
            'ua_ic_style_labels',
            [
                'label'     => esc_html__( 'Labels Style', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ua_ic_show_labels' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ua_ic_badge_style',
            [
                'label'   => esc_html__( 'Badge Style', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'glass'   => esc_html__( 'Glassmorphism (Frosted Blur)', 'ultraaddons-elementor-lite' ),
                    'pill'    => esc_html__( 'Pill Capsule', 'ultraaddons-elementor-lite' ),
                    'soft'    => esc_html__( 'Soft Modern Badge', 'ultraaddons-elementor-lite' ),
                    'minimal' => esc_html__( 'Minimal Clean Tag', 'ultraaddons-elementor-lite' ),
                ],
                'default' => 'glass',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_ic_label_typography',
                'selector' => '{{WRAPPER}} .ua-ic-label',
            ]
        );

        $this->add_control(
            'ua_ic_label_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-ic-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_ic_label_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(0, 0, 0, 0.45)',
                'selectors' => [
                    '{{WRAPPER}} .ua-ic-label' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_ic_label_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-ic-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_ic_label_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-ic-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_ic_label_offset',
            [
                'label'      => esc_html__( 'Edge Spacing / Offset', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 4,
                        'max'  => 50,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 16,
                ],
                'selectors'  => [
                    '{{WRAPPER}}' => '--ua-ic-label-offset: {{SIZE}}px;',
                    '{{WRAPPER}} .ua-image-comparison-wrap[data-orientation="horizontal"] .ua-ic-label-before' => 'left: {{SIZE}}px;',
                    '{{WRAPPER}} .ua-image-comparison-wrap[data-orientation="horizontal"] .ua-ic-label-after'  => 'right: {{SIZE}}px;',
                    '{{WRAPPER}} .ua-image-comparison-wrap[data-orientation="vertical"] .ua-ic-label-before'   => 'top: {{SIZE}}px;',
                    '{{WRAPPER}} .ua-image-comparison-wrap[data-orientation="vertical"] .ua-ic-label-after'    => 'bottom: {{SIZE}}px;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render Widget Output in Frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $before_label = ! empty( $settings['ua_ic_before_label'] ) ? $settings['ua_ic_before_label'] : '';
        $after_label  = ! empty( $settings['ua_ic_after_label'] ) ? $settings['ua_ic_after_label'] : '';

        // Render Before Image HTML
        if ( ! empty( $settings['ua_ic_before_image']['id'] ) ) {
            $before_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'ua_ic_image_size', 'ua_ic_before_image' );
        } else {
            $before_url  = ! empty( $settings['ua_ic_before_image']['url'] ) ? $settings['ua_ic_before_image']['url'] : Utils::get_placeholder_image_src();
            $before_html = '<img src="' . esc_url( $before_url ) . '" alt="' . esc_attr( $before_label ) . '" class="ua-ic-img">';
        }

        // Render After Image HTML
        if ( ! empty( $settings['ua_ic_after_image']['id'] ) ) {
            $after_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'ua_ic_image_size', 'ua_ic_after_image' );
        } else {
            $after_url  = ! empty( $settings['ua_ic_after_image']['url'] ) ? $settings['ua_ic_after_image']['url'] : Utils::get_placeholder_image_src();
            $after_html = '<img src="' . esc_url( $after_url ) . '" alt="' . esc_attr( $after_label ) . '" class="ua-ic-img">';
        }

        $orientation    = ! empty( $settings['ua_ic_orientation'] ) ? $settings['ua_ic_orientation'] : 'horizontal';
        $trigger        = ! empty( $settings['ua_ic_trigger'] ) ? $settings['ua_ic_trigger'] : 'drag';
        $starting_pos   = isset( $settings['ua_ic_starting_position']['size'] ) ? (float) $settings['ua_ic_starting_position']['size'] : 50;
        $intro_sweep    = ! empty( $settings['ua_ic_intro_sweep'] ) && 'yes' === $settings['ua_ic_intro_sweep'] ? 'yes' : 'no';
        $hover_reset    = ! empty( $settings['ua_ic_hover_reset'] ) && 'yes' === $settings['ua_ic_hover_reset'] ? 'yes' : 'no';
        $show_labels    = ! empty( $settings['ua_ic_show_labels'] ) && 'yes' === $settings['ua_ic_show_labels'];
        $label_mode     = ! empty( $settings['ua_ic_labels_visibility'] ) ? $settings['ua_ic_labels_visibility'] : 'auto_fade';
        $badge_style    = ! empty( $settings['ua_ic_badge_style'] ) ? $settings['ua_ic_badge_style'] : 'glass';
        $handle_shape   = ! empty( $settings['ua_ic_handle_shape'] ) ? $settings['ua_ic_handle_shape'] : 'circle';
        $aspect_ratio   = ! empty( $settings['ua_ic_aspect_ratio'] ) ? $settings['ua_ic_aspect_ratio'] : 'auto';
        $line_shadow    = ! empty( $settings['ua_ic_line_shadow'] ) && 'yes' === $settings['ua_ic_line_shadow'] ? 'has-line-shadow' : '';

        $label_pos = 'horizontal' === $orientation
            ? ( ! empty( $settings['ua_ic_label_position_h'] ) ? $settings['ua_ic_label_position_h'] : 'top' )
            : ( ! empty( $settings['ua_ic_label_position_v'] ) ? $settings['ua_ic_label_position_v'] : 'left' );

        $widget_id = $this->get_id();
        $wrap_id   = 'ua-ic-' . $widget_id;
        ?>
        <div class="ua-image-comparison-wrap ua-ic-badge-<?php echo esc_attr( $badge_style ); ?> <?php echo esc_attr( $line_shadow ); ?>"
             id="<?php echo esc_attr( $wrap_id ); ?>"
             data-orientation="<?php echo esc_attr( $orientation ); ?>"
             data-trigger="<?php echo esc_attr( $trigger ); ?>"
             data-initial-pos="<?php echo esc_attr( $starting_pos ); ?>"
             data-intro-sweep="<?php echo esc_attr( $intro_sweep ); ?>"
             data-hover-reset="<?php echo esc_attr( $hover_reset ); ?>"
             data-label-mode="<?php echo esc_attr( $label_mode ); ?>"
             style="--ua-ic-pos: <?php echo esc_attr( $starting_pos ); ?>%;">

            <div class="ua-ic-container ua-ic-ratio-<?php echo esc_attr( $aspect_ratio ); ?>">
                <!-- Before Layer (Base Image) -->
                <div class="ua-ic-layer ua-ic-before-layer">
                    <div class="ua-ic-image-wrap">
                        <?php echo $before_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </div>
                    <?php if ( $show_labels && ! empty( $before_label ) ) : ?>
                        <span class="ua-ic-label ua-ic-label-before ua-ic-pos-<?php echo esc_attr( $label_pos ); ?>"><?php echo esc_html( $before_label ); ?></span>
                    <?php endif; ?>
                </div>

                <!-- After Layer (Comparison Image Clipped via CSS) -->
                <div class="ua-ic-layer ua-ic-after-layer">
                    <div class="ua-ic-image-wrap">
                        <?php echo $after_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </div>
                    <?php if ( $show_labels && ! empty( $after_label ) ) : ?>
                        <span class="ua-ic-label ua-ic-label-after ua-ic-pos-<?php echo esc_attr( $label_pos ); ?>"><?php echo esc_html( $after_label ); ?></span>
                    <?php endif; ?>
                </div>

                <!-- Divider Line and Interactive Handle -->
                <div class="ua-ic-divider-wrap"
                     role="slider"
                     aria-valuenow="<?php echo esc_attr( $starting_pos ); ?>"
                     aria-valuemin="0"
                     aria-valuemax="100"
                     aria-label="<?php esc_attr_e( 'Image comparison slider', 'ultraaddons-elementor-lite' ); ?>"
                     tabindex="0">
                    <div class="ua-ic-divider-line"></div>
                    <div class="ua-ic-handle-btn ua-ic-shape-<?php echo esc_attr( $handle_shape ); ?>">
                        <div class="ua-ic-handle-arrows">
                            <?php if ( 'vertical' === $orientation ) : ?>
                                <span class="ua-ic-arrow ua-ic-arrow-up">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor"><path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z"/></svg>
                                </span>
                                <span class="ua-ic-arrow ua-ic-arrow-down">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor"><path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6z"/></svg>
                                </span>
                            <?php else : ?>
                                <span class="ua-ic-arrow ua-ic-arrow-left">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
                                </span>
                                <span class="ua-ic-arrow ua-ic-arrow-right">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
