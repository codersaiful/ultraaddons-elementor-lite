<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

/**
 * UltraAddons - Stats Counter Widget
 *
 * An advanced animated counter supporting 6 distinct layout modes, Odometer / Vertical Rolling Digits,
 * Number Divider styles, FontAwesome/SVG/Image media, milestone badges, and watermark big numbers.
 *
 * @package UltraAddons
 * @version 2.0.3.5
 * @author Saiful Islam <codersaiful@gmail.com>
 */
class Stats_Counter extends Base {

    /**
     * Constructor: Register widget styles and scripts.
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        $css_file = ULTRA_ADDONS_DIR . 'assets/css/widgets/stats-counter.css';
        $css_ver  = file_exists( $css_file ) ? filemtime( $css_file ) : ULTRA_ADDONS_VERSION;

        $js_file  = ULTRA_ADDONS_DIR . 'assets/js/frontend-stats-counter.js';
        $js_ver   = file_exists( $js_file ) ? filemtime( $js_file ) : ULTRA_ADDONS_VERSION;

        wp_register_style(
            'ultraaddons-stats-counter',
            ULTRA_ADDONS_ASSETS . 'css/widgets/stats-counter.css',
            [],
            $css_ver,
            'all'
        );

        wp_register_script(
            'ultraaddons-stats-counter',
            ULTRA_ADDONS_ASSETS . 'js/frontend-stats-counter.js',
            [ 'jquery' ],
            $js_ver,
            true
        );
    }

    /**
     * Widget Keywords.
     */
    public function get_keywords() {
        return [ 'counter', 'stats', 'number', 'fun facts', 'odometer', 'rolling number', 'milestone', 'ultraaddons' ];
    }

    /**
     * Style Dependencies.
     */
    public function get_style_depends() {
        return [
            'ultraaddons-widgets-style',
            'ultraaddons-stats-counter',
        ];
    }

    /**
     * Script Dependencies.
     */
    public function get_script_depends() {
        return [
            'ultraaddons-stats-counter',
        ];
    }

    /**
     * Register Widget Controls.
     */
    protected function register_controls() {
        // Content Sections
        $this->content_layout_controls();
        $this->content_counter_controls();
        $this->content_media_badge_controls();
        $this->content_text_controls();
        $this->content_divider_controls();
        $this->content_spacing_controls();

        // Style Sections
        $this->style_container_controls();
        $this->style_number_controls();
        $this->style_divider_controls();
        $this->style_media_controls();
        $this->style_text_controls();
        $this->style_badge_controls();
        $this->style_watermark_controls();
    }

    /**
     * Content: Layout Section
     */
    protected function content_layout_controls() {
        $this->start_controls_section(
            'section_layout',
            [
                'label' => esc_html__( 'Layout & Structure', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'layout_style',
            [
                'label'   => esc_html__( 'Layout Style', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'card',
                'options' => [
                    'card'      => esc_html__( 'Modern Accent Card', 'ultraaddons-elementor-lite' ),
                    'clean'     => esc_html__( 'Clean Minimalist (Unboxed)', 'ultraaddons-elementor-lite' ),
                    'inline'    => esc_html__( 'Horizontal / Side-by-Side', 'ultraaddons-elementor-lite' ),
                    'watermark' => esc_html__( 'Watermark / Big Number Backdrop', 'ultraaddons-elementor-lite' ),
                    'glass'     => esc_html__( 'Glassmorphism (Frosted Glass)', 'ultraaddons-elementor-lite' ),
                    'bordered'  => esc_html__( 'Modern Outline / Bordered', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'alignment',
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
                'condition' => [
                    'layout_style!' => 'inline',
                ],
            ]
        );

        $this->add_control(
            'inline_valign',
            [
                'label'     => esc_html__( 'Vertical Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'center',
                'options'   => [
                    'top'    => esc_html__( 'Top', 'ultraaddons-elementor-lite' ),
                    'center' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ),
                    'bottom' => esc_html__( 'Bottom', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'layout_style' => 'inline',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content: Counter Value & Formatting Section
     */
    protected function content_counter_controls() {
        $this->start_controls_section(
            'section_counter_data',
            [
                'label' => esc_html__( 'Counter Numbers & Animation', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'counter_animation_type',
            [
                'label'   => esc_html__( 'Animation Type', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'odometer',
                'options' => [
                    'odometer'     => esc_html__( 'Vertical Rolling Digits (Odometer Reel)', 'ultraaddons-elementor-lite' ),
                    'smooth_count' => esc_html__( 'Smooth Fast Counting (Standard Digital)', 'ultraaddons-elementor-lite' ),
                ],
                'description' => esc_html__( 'Vertical Rolling Digits rolls each number vertically from bottom to top like a slot machine reel.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'starting_number',
            [
                'label'   => esc_html__( 'Starting Number', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 0,
            ]
        );

        $this->add_control(
            'ending_number',
            [
                'label'   => esc_html__( 'Ending Number', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 1500,
            ]
        );

        $this->add_control(
            'prefix',
            [
                'label'       => esc_html__( 'Number Prefix', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => '$',
                'default'     => '',
            ]
        );

        $this->add_control(
            'suffix',
            [
                'label'       => esc_html__( 'Number Suffix', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => '+',
                'default'     => '+',
            ]
        );

        $this->add_control(
            'duration',
            [
                'label'   => esc_html__( 'Animation Duration (ms)', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 2000,
                'min'     => 500,
                'max'     => 10000,
                'step'    => 100,
            ]
        );

        $this->add_control(
            'animation_delay',
            [
                'label'   => esc_html__( 'Animation Delay (ms)', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 0,
                'min'     => 0,
                'max'     => 2000,
                'step'    => 50,
            ]
        );

        $this->add_control(
            'thousand_separator',
            [
                'label'     => esc_html__( 'Thousand Separator', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'comma',
                'separator' => 'before',
                'options'   => [
                    'comma' => esc_html__( 'Comma (1,000)', 'ultraaddons-elementor-lite' ),
                    'dot'   => esc_html__( 'Dot (1.000)', 'ultraaddons-elementor-lite' ),
                    'space' => esc_html__( 'Space (1 000)', 'ultraaddons-elementor-lite' ),
                    'none'  => esc_html__( 'None (1000)', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'decimal_places',
            [
                'label'   => esc_html__( 'Decimal Places', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 0,
                'min'     => 0,
                'max'     => 4,
            ]
        );

        $this->add_control(
            'decimal_separator',
            [
                'label'     => esc_html__( 'Decimal Separator', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'dot',
                'options'   => [
                    'dot'   => esc_html__( 'Dot (4.9)', 'ultraaddons-elementor-lite' ),
                    'comma' => esc_html__( 'Comma (4,9)', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'decimal_places!' => 0,
                ],
            ]
        );

        $this->add_control(
            'auto_shorten',
            [
                'label'        => esc_html__( 'Auto Shorten (K/M/B)', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'description'  => esc_html__( 'Converts 1,500 into 1.5K and 2,000,000 into 2M.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'count_direction',
            [
                'label'     => esc_html__( 'Count Direction', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'up',
                'options'   => [
                    'up'   => esc_html__( 'Count Up (Bottom to Top)', 'ultraaddons-elementor-lite' ),
                    'down' => esc_html__( 'Count Down (Top to Bottom)', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content: Media & Badge Section
     */
    protected function content_media_badge_controls() {
        $this->start_controls_section(
            'section_media_badge',
            [
                'label' => esc_html__( 'Icon, Image & Badge', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_media',
            [
                'label'        => esc_html__( 'Show Icon / Image', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'media_type',
            [
                'label'     => esc_html__( 'Media Type', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'icon',
                'options'   => [
                    'icon'  => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                    'image' => esc_html__( 'Custom Image', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'show_media' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'media_position',
            [
                'label'     => esc_html__( 'Icon / Media Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'top',
                'options'   => [
                    'top'           => esc_html__( 'Top (Above Number)', 'ultraaddons-elementor-lite' ),
                    'left'          => esc_html__( 'Left (Side-by-Side)', 'ultraaddons-elementor-lite' ),
                    'right'         => esc_html__( 'Right (Side-by-Side)', 'ultraaddons-elementor-lite' ),
                    'inline-number' => esc_html__( 'Beside Number (Inline)', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'show_media' => 'yes',
                    'layout_style!' => 'inline',
                ],
            ]
        );

        $this->add_control(
            'selected_icon',
            [
                'label'     => esc_html__( 'Choose Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'fas fa-trophy',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_media' => 'yes',
                    'media_type' => 'icon',
                ],
            ]
        );

        $this->add_control(
            'icon_shape',
            [
                'label'     => esc_html__( 'Icon Background Shape', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'circle',
                'options'   => [
                    'none'    => esc_html__( 'None (Plain Icon)', 'ultraaddons-elementor-lite' ),
                    'circle'  => esc_html__( 'Circle', 'ultraaddons-elementor-lite' ),
                    'square'  => esc_html__( 'Square', 'ultraaddons-elementor-lite' ),
                    'rounded' => esc_html__( 'Rounded Pill', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'show_media' => 'yes',
                    'media_type' => 'icon',
                ],
            ]
        );

        $this->add_control(
            'image',
            [
                'label'     => esc_html__( 'Choose Image', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::MEDIA,
                'default'   => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'show_media' => 'yes',
                    'media_type' => 'image',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'      => 'image_size',
                'default'   => 'thumbnail',
                'condition' => [
                    'show_media' => 'yes',
                    'media_type' => 'image',
                ],
            ]
        );

        // Milestone Badge
        $this->add_control(
            'show_badge',
            [
                'label'        => esc_html__( 'Show Milestone Badge', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'badge_text',
            [
                'label'     => esc_html__( 'Badge Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Top 1%', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'show_badge' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'badge_position',
            [
                'label'     => esc_html__( 'Badge Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'top-right',
                'options'   => [
                    'top-right' => esc_html__( 'Top Right', 'ultraaddons-elementor-lite' ),
                    'top-left'  => esc_html__( 'Top Left', 'ultraaddons-elementor-lite' ),
                    'inline'    => esc_html__( 'Inline / Above Title', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'show_badge' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content: Number Divider Section
     */
    protected function content_divider_controls() {
        $this->start_controls_section(
            'section_divider_content',
            [
                'label' => esc_html__( 'Number Divider Line', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_divider',
            [
                'label'        => esc_html__( 'Show Divider Line', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'divider_style',
            [
                'label'     => esc_html__( 'Divider Style', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'solid',
                'options'   => [
                    'solid'  => esc_html__( 'Solid Line', 'ultraaddons-elementor-lite' ),
                    'dashed' => esc_html__( 'Dashed Line', 'ultraaddons-elementor-lite' ),
                    'dotted' => esc_html__( 'Dotted Line', 'ultraaddons-elementor-lite' ),
                    'double' => esc_html__( 'Double Line', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'show_divider' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content: Title & Description Section
     */
    protected function content_text_controls() {
        $this->start_controls_section(
            'section_text_content',
            [
                'label' => esc_html__( 'Title & Description', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label'       => esc_html__( 'Title / Label', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Happy Clients', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'Enter counter title', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label'   => esc_html__( 'Title HTML Tag', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'h4',
                'options' => [
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
            ]
        );

        $this->add_control(
            'show_description',
            [
                'label'        => esc_html__( 'Show Description', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'description',
            [
                'label'       => esc_html__( 'Description Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__( 'Delivering trusted solutions across 50+ countries.', 'ultraaddons-elementor-lite' ),
                'rows'        => 3,
                'condition'   => [
                    'show_description' => 'yes',
                ],
            ]
        );

        // Watermark Controls
        $this->add_control(
            'watermark_text',
            [
                'label'       => esc_html__( 'Watermark Big Text / Number', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '01',
                'placeholder' => '01',
                'separator'   => 'before',
                'condition'   => [
                    'layout_style' => 'watermark',
                ],
            ]
        );

        $this->add_control(
            'watermark_position',
            [
                'label'     => esc_html__( 'Watermark Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'center',
                'options'   => [
                    'center'       => esc_html__( 'Centered Backdrop (Behind Content)', 'ultraaddons-elementor-lite' ),
                    'bottom-right' => esc_html__( 'Bottom Right Corner', 'ultraaddons-elementor-lite' ),
                    'top-right'    => esc_html__( 'Top Right Corner', 'ultraaddons-elementor-lite' ),
                    'bottom-left'  => esc_html__( 'Bottom Left Corner', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'layout_style' => 'watermark',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content: Individual Gap / Spacing Controls
     */
    protected function content_spacing_controls() {
        $this->start_controls_section(
            'section_gaps',
            [
                'label' => esc_html__( 'Element Spacing & Gaps', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'icon_gap',
            [
                'label'      => esc_html__( 'Media / Icon Bottom Gap', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 60 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 16 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-counter-media' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'show_media' => 'yes',
                    'layout_style!' => 'inline',
                ],
            ]
        );

        $this->add_responsive_control(
            'inline_media_gap',
            [
                'label'      => esc_html__( 'Side Gap between Icon & Text', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 60 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 20 ],
                'selectors'  => [
                    '{{WRAPPER}}.layout-inline, {{WRAPPER}}.media-pos-left, {{WRAPPER}}.media-pos-right' => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'show_media' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'number_gap',
            [
                'label'      => esc_html__( 'Number Bottom Gap', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 60 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 8 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-counter-number-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_gap',
            [
                'label'      => esc_html__( 'Title Bottom Gap', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 60 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 6 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-counter-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Container / Card
     */
    protected function style_container_controls() {
        $this->start_controls_section(
            'section_style_container',
            [
                'label' => esc_html__( 'Container / Card Box', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'card_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-stats-counter-wrapper',
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-stats-counter-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-stats-counter-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'card_border',
                'selector' => '{{WRAPPER}} .ua-stats-counter-wrapper',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'card_shadow',
                'selector' => '{{WRAPPER}} .ua-stats-counter-wrapper',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'card_hover_shadow',
                'label'    => esc_html__( 'Hover Box Shadow', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-stats-counter-wrapper:hover',
            ]
        );

        $this->add_control(
            'card_hover_lift',
            [
                'label'        => esc_html__( 'Hover Lift Animation', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Counter Number
     */
    protected function style_number_controls() {
        $this->start_controls_section(
            'section_style_number',
            [
                'label' => esc_html__( 'Counter Number', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'number_color',
            [
                'label'     => esc_html__( 'Number Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0f172a',
                'selectors' => [
                    '{{WRAPPER}} .ua-counter-number, {{WRAPPER}} .ua-odometer-digit span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'number_typography',
                'selector' => '{{WRAPPER}} .ua-counter-number, {{WRAPPER}} .ua-odometer-digit, {{WRAPPER}} .ua-odometer-sep',
            ]
        );

        // Prefix Style
        $this->add_control(
            'heading_prefix_style',
            [
                'label'     => esc_html__( 'Prefix Styling', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'prefix_color',
            [
                'label'     => esc_html__( 'Prefix Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0fc392',
                'selectors' => [
                    '{{WRAPPER}} .ua-counter-prefix' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'prefix_typography',
                'selector' => '{{WRAPPER}} .ua-counter-prefix',
            ]
        );

        $this->add_responsive_control(
            'prefix_gap',
            [
                'label'      => esc_html__( 'Prefix Right Gap', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-counter-prefix' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Suffix Style
        $this->add_control(
            'heading_suffix_style',
            [
                'label'     => esc_html__( 'Suffix Styling', 'ultraaddons-elementor-lite' ),
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
                    '{{WRAPPER}} .ua-counter-suffix' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'suffix_typography',
                'selector' => '{{WRAPPER}} .ua-counter-suffix',
            ]
        );

        $this->add_responsive_control(
            'suffix_gap',
            [
                'label'      => esc_html__( 'Suffix Left Gap', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-counter-suffix' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        // Separator (Comma / Dot) Style
        $this->add_control(
            'heading_separator_style',
            [
                'label'     => esc_html__( 'Separator (Comma / Dot) Styling', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'separator_color',
            [
                'label'     => esc_html__( 'Separator Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-odometer-sep' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'separator_size',
            [
                'label'      => esc_html__( 'Separator Font Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', '%' ],
                'range'      => [
                    'px' => [ 'min' => 10, 'max' => 80 ],
                    'em' => [ 'min' => 0.2, 'max' => 1.5, 'step' => 0.05 ],
                    '%'  => [ 'min' => 20, 'max' => 150 ],
                ],
                'default'    => [ 'unit' => 'em', 'size' => 0.6 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-odometer-sep' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'separator_spacing',
            [
                'label'      => esc_html__( 'Separator Horizontal Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 20 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 2 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-odometer-sep' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'separator_vertical_align',
            [
                'label'      => esc_html__( 'Separator Vertical Shift', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => -20, 'max' => 20 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 0 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-odometer-sep' => 'transform: translateY({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Number Divider Line
     */
    protected function style_divider_controls() {
        $this->start_controls_section(
            'section_style_divider',
            [
                'label'     => esc_html__( 'Number Divider Line', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_divider' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'divider_color',
            [
                'label'     => esc_html__( 'Divider Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0fc392',
                'selectors' => [
                    '{{WRAPPER}} .ua-counter-divider' => 'border-color: {{VALUE}}; background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'divider_width',
            [
                'label'      => esc_html__( 'Divider Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [ 'min' => 10, 'max' => 300 ],
                    '%'  => [ 'min' => 10, 'max' => 100 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 45 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-counter-divider' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'divider_height',
            [
                'label'      => esc_html__( 'Divider Thickness / Height', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 1, 'max' => 12 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 3 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-counter-divider' => 'border-top-width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'divider_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 20 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 3 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-counter-divider' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'divider_margin',
            [
                'label'      => esc_html__( 'Divider Margin / Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'top'      => '6',
                    'bottom'   => '12',
                    'left'     => '0',
                    'right'    => '0',
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-counter-divider-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Media / Icon
     */
    protected function style_media_controls() {
        $this->start_controls_section(
            'section_style_media',
            [
                'label'     => esc_html__( 'Media / Icon Style', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_media' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label'      => esc_html__( 'Icon / Media Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 12, 'max' => 120 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 30 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-counter-media i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-counter-media svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-counter-media img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_box_size',
            [
                'label'      => esc_html__( 'Box / Shape Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 30, 'max' => 150 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 64 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-counter-media' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'icon_shape!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0fc392',
                'selectors' => [
                    '{{WRAPPER}} .ua-counter-media i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-counter-media svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(15, 195, 146, 0.1)',
                'selectors' => [
                    '{{WRAPPER}} .ua-counter-media' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'icon_shape!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'icon_hover_color',
            [
                'label'     => esc_html__( 'Hover Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-stats-counter-wrapper:hover .ua-counter-media i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-stats-counter-wrapper:hover .ua-counter-media svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_hover_bg_color',
            [
                'label'     => esc_html__( 'Hover Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-stats-counter-wrapper:hover .ua-counter-media' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'icon_shape!' => 'none',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Title & Description
     */
    protected function style_text_controls() {
        $this->start_controls_section(
            'section_style_text',
            [
                'label' => esc_html__( 'Title & Description Style', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Title Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1e293b',
                'selectors' => [
                    '{{WRAPPER}} .ua-counter-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .ua-counter-title',
            ]
        );

        $this->add_control(
            'heading_desc_style',
            [
                'label'     => esc_html__( 'Description Styling', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_description' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label'     => esc_html__( 'Description Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#64748b',
                'selectors' => [
                    '{{WRAPPER}} .ua-counter-description' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_description' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'desc_typography',
                'selector'  => '{{WRAPPER}} .ua-counter-description',
                'condition' => [
                    'show_description' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Milestone Badge
     */
    protected function style_badge_controls() {
        $this->start_controls_section(
            'section_style_badge',
            [
                'label'     => esc_html__( 'Milestone Badge Style', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_badge' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'badge_color',
            [
                'label'     => esc_html__( 'Badge Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-counter-badge' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'badge_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-counter-badge',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'badge_typography',
                'selector' => '{{WRAPPER}} .ua-counter-badge',
            ]
        );

        $this->add_responsive_control(
            'badge_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-counter-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'badge_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-counter-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Watermark Big Text
     */
    protected function style_watermark_controls() {
        $this->start_controls_section(
            'section_style_watermark',
            [
                'label'     => esc_html__( 'Watermark Big Number', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'layout_style' => 'watermark',
                ],
            ]
        );

        $this->add_responsive_control(
            'watermark_size',
            [
                'label'      => esc_html__( 'Watermark Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 50, 'max' => 300 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 150 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-counter-watermark' => 'font-size: {{SIZE}}{{UNIT}}; line-height: 0.9;',
                ],
            ]
        );

        $this->add_control(
            'watermark_opacity',
            [
                'label'     => esc_html__( 'Watermark Opacity', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [ 'min' => 0.01, 'max' => 0.4, 'step' => 0.01 ],
                ],
                'default'   => [ 'size' => 0.06 ],
                'selectors' => [
                    '{{WRAPPER}} .ua-counter-watermark' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'watermark_color',
            [
                'label'     => esc_html__( 'Watermark Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0f172a',
                'selectors' => [
                    '{{WRAPPER}} .ua-counter-watermark' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'watermark_typography',
                'selector' => '{{WRAPPER}} .ua-counter-watermark',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render Output on Frontend.
     */
    protected function render() {
        wp_enqueue_script( 'ultraaddons-stats-counter' );
        wp_enqueue_style( 'ultraaddons-stats-counter' );
        $settings = $this->get_settings_for_display();

        $layout_style     = ! empty( $settings['layout_style'] ) ? sanitize_key( $settings['layout_style'] ) : 'card';
        $alignment        = ! empty( $settings['alignment'] ) ? sanitize_key( $settings['alignment'] ) : 'center';
        $inline_valign    = ! empty( $settings['inline_valign'] ) ? sanitize_key( $settings['inline_valign'] ) : 'center';
        $hover_lift_class = ( ! empty( $settings['card_hover_lift'] ) && 'yes' === $settings['card_hover_lift'] ) ? 'ua-counter-lift-yes' : '';

        // Media Settings
        $show_media     = ! empty( $settings['show_media'] ) && 'yes' === $settings['show_media'];
        $media_type     = ! empty( $settings['media_type'] ) ? sanitize_key( $settings['media_type'] ) : 'icon';
        $icon_shape     = ! empty( $settings['icon_shape'] ) ? sanitize_key( $settings['icon_shape'] ) : 'circle';
        $media_position = ! empty( $settings['media_position'] ) ? sanitize_key( $settings['media_position'] ) : 'top';

        if ( 'inline' === $layout_style ) {
            $media_position = 'left';
        }

        // Animation Type & Delay
        $anim_type  = ! empty( $settings['counter_animation_type'] ) ? sanitize_key( $settings['counter_animation_type'] ) : 'odometer';
        $anim_delay = isset( $settings['animation_delay'] ) ? (int) $settings['animation_delay'] : 0;

        // Divider
        $show_divider  = ! empty( $settings['show_divider'] ) && 'yes' === $settings['show_divider'];
        $divider_style = ! empty( $settings['divider_style'] ) ? sanitize_key( $settings['divider_style'] ) : 'solid';

        $wrapper_classes = [
            'ua-stats-counter-wrapper',
            'layout-' . $layout_style,
            'media-pos-' . $media_position,
            'anim-type-' . $anim_type,
            $hover_lift_class,
        ];

        if ( 'inline' === $layout_style || 'left' === $media_position || 'right' === $media_position ) {
            $wrapper_classes[] = 'valign-' . $inline_valign;
        } else {
            $wrapper_classes[] = 'align-' . $alignment;
        }

        // Counter Parameters
        $starting_number   = isset( $settings['starting_number'] ) ? (float) $settings['starting_number'] : 0;
        $ending_number     = isset( $settings['ending_number'] ) ? (float) $settings['ending_number'] : 1500;
        $duration          = ! empty( $settings['duration'] ) ? (int) $settings['duration'] : 2000;
        $thousand_sep      = ! empty( $settings['thousand_separator'] ) ? $settings['thousand_separator'] : 'comma';
        $decimals          = isset( $settings['decimal_places'] ) ? (int) $settings['decimal_places'] : 0;
        $decimal_sep       = ! empty( $settings['decimal_separator'] ) && 'comma' === $settings['decimal_separator'] ? ',' : '.';
        $auto_shorten      = ! empty( $settings['auto_shorten'] ) && 'yes' === $settings['auto_shorten'] ? 'yes' : 'no';
        $count_direction   = ! empty( $settings['count_direction'] ) ? sanitize_key( $settings['count_direction'] ) : 'up';

        // Thousand Separator Map
        $sep_map = [
            'comma' => ',',
            'dot'   => '.',
            'space' => ' ',
            'none'  => '',
        ];
        $actual_thousand_sep = $sep_map[ $thousand_sep ] ?? ',';

        // Badge Settings
        $show_badge     = ! empty( $settings['show_badge'] ) && 'yes' === $settings['show_badge'];
        $badge_text     = ! empty( $settings['badge_text'] ) ? $settings['badge_text'] : '';
        $badge_position = ! empty( $settings['badge_position'] ) ? sanitize_key( $settings['badge_position'] ) : 'top-right';

        // Text Content
        $title              = ! empty( $settings['title'] ) ? $settings['title'] : '';
        $title_tag          = ! empty( $settings['title_tag'] ) ? sanitize_key( $settings['title_tag'] ) : 'h4';
        $show_description   = ! empty( $settings['show_description'] ) && 'yes' === $settings['show_description'];
        $description        = ! empty( $settings['description'] ) ? $settings['description'] : '';
        $watermark_text     = ! empty( $settings['watermark_text'] ) ? $settings['watermark_text'] : '01';
        $watermark_position = ! empty( $settings['watermark_position'] ) ? sanitize_key( $settings['watermark_position'] ) : 'center';
        ?>
        <div class="<?php echo esc_attr( implode( ' ', array_filter( $wrapper_classes ) ) ); ?>">

            <!-- Background Watermark Text -->
            <?php if ( 'watermark' === $layout_style && ! empty( $watermark_text ) ) : ?>
                <div class="ua-counter-watermark pos-<?php echo esc_attr( $watermark_position ); ?>" aria-hidden="true">
                    <?php echo esc_html( $watermark_text ); ?>
                </div>
            <?php endif; ?>

            <!-- Milestone Badge (Top-Right or Top-Left) -->
            <?php if ( $show_badge && ! empty( $badge_text ) && 'inline' !== $badge_position ) : ?>
                <span class="ua-counter-badge pos-<?php echo esc_attr( $badge_position ); ?>">
                    <?php echo esc_html( $badge_text ); ?>
                </span>
            <?php endif; ?>

            <!-- Media: Icon or Image (Top, Left, or Right) -->
            <?php if ( $show_media && 'inline-number' !== $media_position ) : ?>
                <div class="ua-counter-media shape-<?php echo esc_attr( $icon_shape ); ?>">
                    <?php if ( 'icon' === $media_type && ! empty( $settings['selected_icon']['value'] ) ) : ?>
                        <?php Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                    <?php elseif ( 'image' === $media_type && ! empty( $settings['image']['url'] ) ) : ?>
                        <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'image_size', 'image' ); ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Content Area (Number, Divider, Title, Description) -->
            <div class="ua-counter-content-wrap">

                <!-- Inline Milestone Badge -->
                <?php if ( $show_badge && ! empty( $badge_text ) && 'inline' === $badge_position ) : ?>
                    <span class="ua-counter-badge pos-inline">
                        <?php echo esc_html( $badge_text ); ?>
                    </span>
                <?php endif; ?>

                <!-- Animated Counter Number Block -->
                <div class="ua-counter-number-wrap">

                    <!-- Inline-number Media Icon -->
                    <?php if ( $show_media && 'inline-number' === $media_position ) : ?>
                        <span class="ua-counter-media shape-<?php echo esc_attr( $icon_shape ); ?>">
                            <?php if ( 'icon' === $media_type && ! empty( $settings['selected_icon']['value'] ) ) : ?>
                                <?php Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                            <?php elseif ( 'image' === $media_type && ! empty( $settings['image']['url'] ) ) : ?>
                                <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'image_size', 'image' ); ?>
                            <?php endif; ?>
                        </span>
                    <?php endif; ?>

                    <?php if ( ! empty( $settings['prefix'] ) ) : ?>
                        <span class="ua-counter-prefix"><?php echo esc_html( $settings['prefix'] ); ?></span>
                    <?php endif; ?>

                    <span class="ua-counter-number"
                        data-start="<?php echo esc_attr( $starting_number ); ?>"
                        data-end="<?php echo esc_attr( $ending_number ); ?>"
                        data-duration="<?php echo esc_attr( $duration ); ?>"
                        data-thousand-sep="<?php echo esc_attr( $actual_thousand_sep ); ?>"
                        data-decimals="<?php echo esc_attr( $decimals ); ?>"
                        data-decimal-sep="<?php echo esc_attr( $decimal_sep ); ?>"
                        data-auto-shorten="<?php echo esc_attr( $auto_shorten ); ?>"
                        data-direction="<?php echo esc_attr( $count_direction ); ?>"
                        data-anim-type="<?php echo esc_attr( $anim_type ); ?>"
                        data-delay="<?php echo esc_attr( $anim_delay ); ?>">
                        <?php echo esc_html( $starting_number ); ?>
                    </span>

                    <?php if ( ! empty( $settings['suffix'] ) ) : ?>
                        <span class="ua-counter-suffix"><?php echo esc_html( $settings['suffix'] ); ?></span>
                    <?php endif; ?>
                </div>

                <!-- Number Divider Line -->
                <?php if ( $show_divider ) : ?>
                    <div class="ua-counter-divider-wrap">
                        <span class="ua-counter-divider divider-style-<?php echo esc_attr( $divider_style ); ?>"></span>
                    </div>
                <?php endif; ?>

                <!-- Title -->
                <?php if ( ! empty( $title ) ) : ?>
                    <<?php echo esc_attr( $title_tag ); ?> class="ua-counter-title">
                        <?php echo esc_html( $title ); ?>
                    </<?php echo esc_attr( $title_tag ); ?>>
                <?php endif; ?>

                <!-- Description -->
                <?php if ( $show_description && ! empty( $description ) ) : ?>
                    <p class="ua-counter-description">
                        <?php echo esc_html( $description ); ?>
                    </p>
                <?php endif; ?>

            </div>

        </div>
        <?php
    }
}