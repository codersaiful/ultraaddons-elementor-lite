<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

/**
 * UltraAddons - Post Title Widget
 *
 * A modern, lightweight, dynamic title widget for WordPress single blog posts,
 * pages, custom post types, and archive headers. Supports SEO tags (H1-H6), prefix/suffix,
 * dynamic excerpt/subtitle, clickable links, accent divider bars, and gradient text.
 *
 * @package UltraAddons
 * @version 2.0.3.6
 * @author Saiful Islam <codersaiful@gmail.com>
 */
class Post_Title extends Base {

    /**
     * Constructor: Register widget styles.
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        $css_file = ULTRA_ADDONS_DIR . 'assets/css/widgets/post-title.css';
        $css_ver  = file_exists( $css_file ) ? filemtime( $css_file ) : ULTRA_ADDONS_VERSION;

        wp_register_style(
            'ultraaddons-post-title',
            ULTRA_ADDONS_ASSETS . 'css/widgets/post-title.css',
            [],
            $css_ver,
            'all'
        );
    }

    /**
     * Widget Keywords.
     */
    public function get_keywords() {
        return [ 'post title', 'page title', 'post & page title', 'heading', 'title', 'archive title', 'hero title', 'ultraaddons' ];
    }

    /**
     * Style Dependencies.
     */
    public function get_style_depends() {
        return [
            'ultraaddons-widgets-style',
            'ultraaddons-post-title',
        ];
    }

    /**
     * Register Widget Controls.
     */
    protected function register_controls() {
        // Content Sections
        $this->content_title_controls();
        $this->content_prefix_suffix_controls();
        $this->content_subtitle_controls();
        $this->content_accent_controls();

        // Style Sections
        $this->style_title_controls();
        $this->style_prefix_suffix_controls();
        $this->style_subtitle_controls();
        $this->style_accent_controls();
        $this->style_container_controls();
    }

    /**
     * Content: Title Section
     */
    protected function content_title_controls() {
        $this->start_controls_section(
            'section_title_content',
            [
                'label' => esc_html__( 'Title & SEO Hierarchy', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'html_tag',
            [
                'label'   => esc_html__( 'HTML Tag (SEO)', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'h1',
                'options' => [
                    'h1'   => 'H1 (Main Heading)',
                    'h2'   => 'H2',
                    'h3'   => 'H3',
                    'h4'   => 'H4',
                    'h5'   => 'H5',
                    'h6'   => 'H6',
                    'div'  => 'div',
                    'span' => 'span',
                    'p'    => 'p',
                ],
                'description' => esc_html__( 'Choose H1 for standard page/post main headings to ensure optimal SEO.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'custom_post_id',
            [
                'label'       => esc_html__( 'Custom Post ID (Optional)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => '',
                'placeholder' => esc_html__( 'Leave blank for current page/post', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'fallback_title',
            [
                'label'       => esc_html__( 'Fallback Title', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'placeholder' => esc_html__( 'Enter fallback title if empty', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'link_type',
            [
                'label'   => esc_html__( 'Link', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none'     => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                    'post_url' => esc_html__( 'Current Post / Page URL', 'ultraaddons-elementor-lite' ),
                    'custom'   => esc_html__( 'Custom URL', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'custom_url',
            [
                'label'         => esc_html__( 'Custom Link URL', 'ultraaddons-elementor-lite' ),
                'type'          => Controls_Manager::URL,
                'placeholder'   => esc_html__( 'https://your-link.com', 'ultraaddons-elementor-lite' ),
                'show_external' => true,
                'default'       => [
                    'url'         => '',
                    'is_external' => false,
                    'nofollow'    => false,
                ],
                'condition'     => [
                    'link_type' => 'custom',
                ],
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label'     => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'    => [
                        'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center'  => [
                        'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'   => [
                        'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justify', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-justify',
                    ],
                ],
                'default'   => 'left',
                'selectors' => [
                    '{{WRAPPER}} .ua-post-title-container' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content: Prefix & Suffix Section
     */
    protected function content_prefix_suffix_controls() {
        $this->start_controls_section(
            'section_prefix_suffix_content',
            [
                'label' => esc_html__( 'Prefix & Suffix (Before/After)', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_prefix',
            [
                'label'        => esc_html__( 'Show Prefix (Before Text)', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'prefix_text',
            [
                'label'       => esc_html__( 'Prefix Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Article:', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'Enter prefix text', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'show_prefix' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_suffix',
            [
                'label'        => esc_html__( 'Show Suffix (After Text)', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'suffix_text',
            [
                'label'       => esc_html__( 'Suffix Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( '— 2026', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'Enter suffix text', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'show_suffix' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'prefix_suffix_display',
            [
                'label'     => esc_html__( 'Display Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'inline',
                'options'   => [
                    'inline' => esc_html__( 'Inline (Same Line with Title)', 'ultraaddons-elementor-lite' ),
                    'block'  => esc_html__( 'Block (Separate Lines Above/Below)', 'ultraaddons-elementor-lite' ),
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content: Subtitle / Excerpt Section
     */
    protected function content_subtitle_controls() {
        $this->start_controls_section(
            'section_subtitle_content',
            [
                'label' => esc_html__( 'Subtitle / Post Excerpt', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_subtitle',
            [
                'label'        => esc_html__( 'Show Subtitle / Excerpt', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'subtitle_source',
            [
                'label'     => esc_html__( 'Subtitle Source', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'custom',
                'options'   => [
                    'custom'  => esc_html__( 'Custom Subtitle Text', 'ultraaddons-elementor-lite' ),
                    'excerpt' => esc_html__( 'Dynamic Post Excerpt', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'show_subtitle' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'custom_subtitle',
            [
                'label'       => esc_html__( 'Custom Subtitle Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__( 'Explore comprehensive insights, guides, and updates.', 'ultraaddons-elementor-lite' ),
                'rows'        => 3,
                'condition'   => [
                    'show_subtitle'   => 'yes',
                    'subtitle_source' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'excerpt_length',
            [
                'label'     => esc_html__( 'Excerpt Word Limit', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 25,
                'min'       => 5,
                'max'       => 100,
                'condition' => [
                    'show_subtitle'   => 'yes',
                    'subtitle_source' => 'excerpt',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content: Accent Divider / Border Section
     */
    protected function content_accent_controls() {
        $this->start_controls_section(
            'section_accent_content',
            [
                'label' => esc_html__( 'Accent Divider / Border Bar', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_accent',
            [
                'label'        => esc_html__( 'Show Accent Bar / Divider', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'accent_position',
            [
                'label'     => esc_html__( 'Accent Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'bottom-line',
                'options'   => [
                    'bottom-line' => esc_html__( 'Underline / Bottom Divider Bar', 'ultraaddons-elementor-lite' ),
                    'left-bar'    => esc_html__( 'Left Vertical Stripe Bar', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'show_accent' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'accent_style',
            [
                'label'     => esc_html__( 'Divider Style', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'solid',
                'options'   => [
                    'solid'  => esc_html__( 'Solid', 'ultraaddons-elementor-lite' ),
                    'dashed' => esc_html__( 'Dashed', 'ultraaddons-elementor-lite' ),
                    'dotted' => esc_html__( 'Dotted', 'ultraaddons-elementor-lite' ),
                    'double' => esc_html__( 'Double', 'ultraaddons-elementor-lite' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-post-title-accent' => 'border-top-style: {{VALUE}} !important;',
                ],
                'condition' => [
                    'show_accent'     => 'yes',
                    'accent_position' => 'bottom-line',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Title Section
     */
    protected function style_title_controls() {
        $this->start_controls_section(
            'section_style_title',
            [
                'label' => esc_html__( 'Title Style', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0f172a',
                'selectors' => [
                    '{{WRAPPER}} .ua-post-title-text, {{WRAPPER}} .ua-post-title-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label'     => esc_html__( 'Hover Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-post-title-link:hover' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'link_type!' => 'none',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .ua-post-title-heading, {{WRAPPER}} .ua-post-title-text, {{WRAPPER}} .ua-post-title-link',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'title_text_shadow',
                'selector' => '{{WRAPPER}} .ua-post-title-heading',
            ]
        );

        // Gradient Fill Toggle
        $this->add_control(
            'enable_gradient',
            [
                'label'        => esc_html__( 'Gradient Text Fill', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => 'title_gradient_bg',
                'types'     => [ 'gradient' ],
                'selector'  => '{{WRAPPER}} .ua-post-title-heading.ua-title-gradient .ua-post-title-text, {{WRAPPER}} .ua-post-title-heading.ua-title-gradient .ua-post-title-link',
                'condition' => [
                    'enable_gradient' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Prefix & Suffix Section
     */
    protected function style_prefix_suffix_controls() {
        $this->start_controls_section(
            'section_style_prefix_suffix',
            [
                'label' => esc_html__( 'Prefix & Suffix Style', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Prefix
        $this->add_control(
            'heading_prefix_style',
            [
                'label' => esc_html__( 'Prefix (Before Text)', 'ultraaddons-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'prefix_color',
            [
                'label'     => esc_html__( 'Prefix Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0fc392',
                'selectors' => [
                    '{{WRAPPER}} .ua-post-title-prefix' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'prefix_typography',
                'selector' => '{{WRAPPER}} .ua-post-title-prefix, {{WRAPPER}} .ua-post-title-heading .ua-post-title-prefix',
            ]
        );

        $this->add_responsive_control(
            'prefix_gap',
            [
                'label'      => esc_html__( 'Prefix Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 40 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 8 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-post-title-prefix.pos-inline' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-post-title-prefix.pos-block'  => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Suffix
        $this->add_control(
            'heading_suffix_style',
            [
                'label'     => esc_html__( 'Suffix (After Text)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'suffix_color',
            [
                'label'     => esc_html__( 'Suffix Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0fc392',
                'selectors' => [
                    '{{WRAPPER}} .ua-post-title-suffix' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'suffix_typography',
                'selector' => '{{WRAPPER}} .ua-post-title-suffix, {{WRAPPER}} .ua-post-title-heading .ua-post-title-suffix',
            ]
        );

        $this->add_responsive_control(
            'suffix_gap',
            [
                'label'      => esc_html__( 'Suffix Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 40 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 8 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-post-title-suffix.pos-inline' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-post-title-suffix.pos-block'  => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Subtitle / Excerpt Section
     */
    protected function style_subtitle_controls() {
        $this->start_controls_section(
            'section_style_subtitle',
            [
                'label'     => esc_html__( 'Subtitle / Excerpt Style', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_subtitle' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label'     => esc_html__( 'Subtitle Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#64748b',
                'selectors' => [
                    '{{WRAPPER}} .ua-post-title-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .ua-post-title-subtitle',
            ]
        );

        $this->add_responsive_control(
            'subtitle_margin',
            [
                'label'      => esc_html__( 'Margin Top', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 50 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 12 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-post-title-subtitle' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Accent Bar Section
     */
    protected function style_accent_controls() {
        $this->start_controls_section(
            'section_style_accent',
            [
                'label'     => esc_html__( 'Accent Divider Style', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_accent' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'accent_color',
            [
                'label'     => esc_html__( 'Accent Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0fc392',
                'selectors' => [
                    '{{WRAPPER}} .ua-post-title-accent' => 'border-top-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-post-title-left-bar' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'accent_width',
            [
                'label'      => esc_html__( 'Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [ 'min' => 10, 'max' => 400 ],
                    '%'  => [ 'min' => 5, 'max' => 100 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 50 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-post-title-accent' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-post-title-left-bar' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'accent_height',
            [
                'label'      => esc_html__( 'Thickness / Height', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 1, 'max' => 12 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 3 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-post-title-accent' => 'border-top-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-post-title-left-bar' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'accent_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 20 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 3 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-post-title-accent' => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-post-title-left-bar' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'accent_margin',
            [
                'label'      => esc_html__( 'Spacing / Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'top'      => '10',
                    'bottom'   => '10',
                    'left'     => '0',
                    'right'    => '0',
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-post-title-accent-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ua-post-title-left-bar'    => 'margin-right: {{RIGHT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Container Box Section
     */
    protected function style_container_controls() {
        $this->start_controls_section(
            'section_style_container',
            [
                'label' => esc_html__( 'Container Box', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'container_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-post-title-container',
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-post-title-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'container_border',
                'selector' => '{{WRAPPER}} .ua-post-title-container',
            ]
        );

        $this->add_responsive_control(
            'container_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-post-title-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'container_shadow',
                'selector' => '{{WRAPPER}} .ua-post-title-container',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render Output on Frontend.
     */
    protected function render() {
        wp_enqueue_style( 'ultraaddons-post-title' );
        $settings = $this->get_settings_for_display();

        $custom_post_id = ! empty( $settings['custom_post_id'] ) ? (int) $settings['custom_post_id'] : 0;
        $fallback_title = ! empty( $settings['fallback_title'] ) ? $settings['fallback_title'] : '';

        // Dynamic Title Resolution
        $title = '';
        if ( $custom_post_id > 0 ) {
            $title = get_the_title( $custom_post_id );
        } elseif ( is_archive() ) {
            $title = get_the_archive_title();
        } elseif ( is_search() ) {
            $title = sprintf( esc_html__( 'Search Results for: %s', 'ultraaddons-elementor-lite' ), get_search_query() );
        } elseif ( is_404() ) {
            $title = esc_html__( '404 — Page Not Found', 'ultraaddons-elementor-lite' );
        } else {
            $title = get_the_title();
        }

        if ( empty( $title ) && ! empty( $fallback_title ) ) {
            $title = $fallback_title;
        }

        if ( empty( $title ) ) {
            $title = esc_html__( 'Sample Post Title', 'ultraaddons-elementor-lite' );
        }

        // HTML Tag
        $allowed_tags = [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p' ];
        $html_tag     = ( ! empty( $settings['html_tag'] ) && in_array( $settings['html_tag'], $allowed_tags, true ) ) ? $settings['html_tag'] : 'h1';

        // Display Position (Inline vs Block)
        $display_mode = ! empty( $settings['prefix_suffix_display'] ) ? sanitize_key( $settings['prefix_suffix_display'] ) : 'inline';

        // Prefix & Suffix
        $show_prefix = ! empty( $settings['show_prefix'] ) && 'yes' === $settings['show_prefix'];
        $prefix_text = ! empty( $settings['prefix_text'] ) ? $settings['prefix_text'] : '';

        $show_suffix = ! empty( $settings['show_suffix'] ) && 'yes' === $settings['show_suffix'];
        $suffix_text = ! empty( $settings['suffix_text'] ) ? $settings['suffix_text'] : '';

        // Subtitle / Excerpt
        $show_subtitle   = ! empty( $settings['show_subtitle'] ) && 'yes' === $settings['show_subtitle'];
        $subtitle_source = ! empty( $settings['subtitle_source'] ) ? $settings['subtitle_source'] : 'custom';
        $subtitle_text   = '';

        if ( $show_subtitle ) {
            if ( 'excerpt' === $subtitle_source ) {
                $target_id = $custom_post_id > 0 ? $custom_post_id : get_the_ID();
                $post_obj  = get_post( $target_id );
                if ( $post_obj ) {
                    $raw_excerpt = has_excerpt( $post_obj ) ? $post_obj->post_excerpt : $post_obj->post_content;
                    $word_limit  = ! empty( $settings['excerpt_length'] ) ? (int) $settings['excerpt_length'] : 25;
                    $subtitle_text = wp_trim_words( wp_strip_all_tags( $raw_excerpt ), $word_limit, '...' );
                }
            } else {
                $subtitle_text = ! empty( $settings['custom_subtitle'] ) ? $settings['custom_subtitle'] : '';
            }
        }

        // Accent Divider
        $show_accent     = ! empty( $settings['show_accent'] ) && 'yes' === $settings['show_accent'];
        $accent_position = ! empty( $settings['accent_position'] ) ? $settings['accent_position'] : 'bottom-line';
        $accent_style    = ! empty( $settings['accent_style'] ) ? $settings['accent_style'] : 'solid';

        // Gradient class
        $gradient_class = ( ! empty( $settings['enable_gradient'] ) && 'yes' === $settings['enable_gradient'] ) ? 'ua-title-gradient' : '';

        // Link
        $link_type = ! empty( $settings['link_type'] ) ? $settings['link_type'] : 'none';
        $link_url  = '';
        $link_attr = '';

        if ( 'post_url' === $link_type ) {
            $link_url = $custom_post_id > 0 ? get_permalink( $custom_post_id ) : get_permalink();
        } elseif ( 'custom' === $link_type && ! empty( $settings['custom_url']['url'] ) ) {
            $link_url = $settings['custom_url']['url'];
            if ( ! empty( $settings['custom_url']['is_external'] ) ) {
                $link_attr .= ' target="_blank"';
            }
            if ( ! empty( $settings['custom_url']['nofollow'] ) ) {
                $link_attr .= ' rel="nofollow"';
            }
        }

        $wrapper_classes = [
            'ua-post-title-container',
            'display-' . $display_mode,
            $show_accent ? 'has-accent accent-' . $accent_position : '',
        ];
        ?>
        <div class="<?php echo esc_attr( implode( ' ', array_filter( $wrapper_classes ) ) ); ?>">

            <div class="ua-post-title-inner">

                <!-- Left Accent Stripe Bar -->
                <?php if ( $show_accent && 'left-bar' === $accent_position ) : ?>
                    <span class="ua-post-title-left-bar" aria-hidden="true"></span>
                <?php endif; ?>

                <div class="ua-post-title-text-wrap">

                    <!-- Block Prefix -->
                    <?php if ( $show_prefix && ! empty( $prefix_text ) && 'block' === $display_mode ) : ?>
                        <span class="ua-post-title-prefix pos-block"><?php echo esc_html( $prefix_text ); ?></span>
                    <?php endif; ?>

                    <!-- Main Dynamic Heading -->
                    <<?php echo esc_attr( $html_tag ); ?> class="ua-post-title-heading <?php echo esc_attr( $gradient_class ); ?>">

                        <!-- Inline Prefix -->
                        <?php if ( $show_prefix && ! empty( $prefix_text ) && 'inline' === $display_mode ) : ?>
                            <span class="ua-post-title-prefix pos-inline"><?php echo esc_html( $prefix_text ); ?></span>
                        <?php endif; ?>

                        <!-- Title Text / Link -->
                        <?php if ( ! empty( $link_url ) ) : ?>
                            <a href="<?php echo esc_url( $link_url ); ?>" class="ua-post-title-link"<?php echo $link_attr; ?>>
                                <?php echo esc_html( $title ); ?>
                            </a>
                        <?php else : ?>
                            <span class="ua-post-title-text"><?php echo esc_html( $title ); ?></span>
                        <?php endif; ?>

                        <!-- Inline Suffix -->
                        <?php if ( $show_suffix && ! empty( $suffix_text ) && 'inline' === $display_mode ) : ?>
                            <span class="ua-post-title-suffix pos-inline"><?php echo esc_html( $suffix_text ); ?></span>
                        <?php endif; ?>

                    </<?php echo esc_attr( $html_tag ); ?>>

                    <!-- Block Suffix -->
                    <?php if ( $show_suffix && ! empty( $suffix_text ) && 'block' === $display_mode ) : ?>
                        <span class="ua-post-title-suffix pos-block"><?php echo esc_html( $suffix_text ); ?></span>
                    <?php endif; ?>

                    <!-- Underline / Bottom Accent Divider -->
                    <?php if ( $show_accent && 'bottom-line' === $accent_position ) : ?>
                        <div class="ua-post-title-accent-wrap">
                            <span class="ua-post-title-accent style-<?php echo esc_attr( $accent_style ); ?>" aria-hidden="true"></span>
                        </div>
                    <?php endif; ?>

                    <!-- Subtitle / Post Excerpt -->
                    <?php if ( $show_subtitle && ! empty( $subtitle_text ) ) : ?>
                        <p class="ua-post-title-subtitle">
                            <?php echo esc_html( $subtitle_text ); ?>
                        </p>
                    <?php endif; ?>

                </div>

            </div>

        </div>
        <?php
    }
}