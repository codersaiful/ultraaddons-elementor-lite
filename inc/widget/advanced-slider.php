<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * UltraAddons - Advanced Slider Widget
 *
 * A modern, conversion-optimized, and high-performance Slider & Carousel widget.
 * Features:
 * - Powered by modern Elementor Swiper.js engine
 * - Custom Content & Elementor Saved Template support
 * - Video Backgrounds (MP4, YouTube, Vimeo)
 * - Ken Burns cinematic image zoom effect
 * - Multi-column responsive carousel mode
 * - Dual Action Buttons (Primary & Secondary CTA)
 * - Staggered Content Entrance Animations
 * - Navigation Arrows, Dots, Fraction, and Progressbar Pagination
 * - Scroll Down Section Indicator
 *
 * @package UltraAddons
 * @version 2.0.3.6
 * @author Saiful Islam <codersaiful@gmail.com>
 */
class Advanced_Slider extends Base {

    /**
     * Constructor: Register widget styles and scripts.
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        $css_file = ULTRA_ADDONS_DIR . 'assets/css/widgets/advanced-slider.css';
        $css_ver  = file_exists( $css_file ) ? filemtime( $css_file ) : ULTRA_ADDONS_VERSION;

        wp_register_style(
            'ultraaddons-advanced-slider',
            ULTRA_ADDONS_ASSETS . 'css/widgets/advanced-slider.css',
            [],
            $css_ver,
            'all'
        );

        $js_file = ULTRA_ADDONS_DIR . 'assets/js/frontend-advanced-slider.js';
        $js_ver  = file_exists( $js_file ) ? filemtime( $js_file ) : ULTRA_ADDONS_VERSION;

        wp_register_script(
            'ultraaddons-advanced-slider-js',
            ULTRA_ADDONS_ASSETS . 'js/frontend-advanced-slider.js',
            [ 'jquery' ],
            $js_ver,
            true
        );
    }

    /**
     * Widget Keywords.
     */
    public function get_keywords() {
        return [ 'slider', 'advanced slider', 'carousel', 'slides', 'hero slider', 'banner', 'ultraaddons' ];
    }

    /**
     * Style Dependencies.
     */
    public function get_style_depends() {
        return array_merge( parent::get_style_depends(), [
            'e-swiper',
            'elementor-icons-fa-solid',
            'elementor-icons-fa-regular',
            'elementor-icons-fa-brands',
            'ultraaddons-advanced-slider',
        ] );
    }

    /**
     * Script Dependencies.
     */
    public function get_script_depends() {
        return [
            'swiper',
            'ultraaddons-advanced-slider-js',
        ];
    }

    /**
     * Register Widget Controls.
     */
    protected function register_controls() {
        // Content Controls
        $this->content_slides_controls();
        $this->content_settings_controls();

        // Style Controls
        $this->style_container_controls();
        $this->style_subtitle_controls();
        $this->style_title_controls();
        $this->style_description_controls();
        $this->style_primary_button_controls();
        $this->style_secondary_button_controls();
        $this->style_arrows_controls();
        $this->style_pagination_controls();
    }

    /**
     * Helper to retrieve Elementor Saved Templates.
     */
    protected function get_saved_templates() {
        $templates = [ '' => esc_html__( '— Select Template —', 'ultraaddons-elementor-lite' ) ];
        $posts = get_posts( [
            'post_type'      => 'elementor_library',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        ] );

        if ( ! empty( $posts ) ) {
            foreach ( $posts as $post ) {
                $templates[ $post->ID ] = $post->post_title;
            }
        }
        return $templates;
    }

    /**
     * Content: Slides Section
     */
    protected function content_slides_controls() {
        $this->start_controls_section(
            'section_content_slides',
            [
                'label' => esc_html__( 'Slides', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'slide_content_type',
            [
                'label'   => esc_html__( 'Slide Source', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'custom',
                'options' => [
                    'custom'   => esc_html__( 'Custom Content', 'ultraaddons-elementor-lite' ),
                    'template' => esc_html__( 'Elementor Saved Template', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $repeater->add_control(
            'slide_template_id',
            [
                'label'       => esc_html__( 'Select Template', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => '',
                'options'     => $this->get_saved_templates(),
                'condition'   => [
                    'slide_content_type' => 'template',
                ],
            ]
        );

        $repeater->start_controls_tabs( 'tabs_slide_item' );

        // Tab 1: Content
        $repeater->start_controls_tab(
            'tab_slide_content',
            [
                'label'     => esc_html__( 'Content', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'slide_content_type' => 'custom',
                ],
            ]
        );

        $repeater->add_control(
            'slide_subtitle',
            [
                'label'       => esc_html__( 'Subtitle / Badge', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Next-Gen Platform', 'ultraaddons-elementor-lite' ),
                'label_block' => true,
                'dynamic'     => [ 'active' => true ],
                'condition'   => [ 'slide_content_type' => 'custom' ],
            ]
        );

        $repeater->add_control(
            'slide_title',
            [
                'label'       => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Design with Speed', 'ultraaddons-elementor-lite' ),
                'label_block' => true,
                'dynamic'     => [ 'active' => true ],
                'condition'   => [ 'slide_content_type' => 'custom' ],
            ]
        );

        $repeater->add_control(
            'slide_title_tag',
            [
                'label'     => esc_html__( 'Title HTML Tag', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'h2',
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
                'condition' => [ 'slide_content_type' => 'custom' ],
            ]
        );

        $repeater->add_control(
            'slide_description',
            [
                'label'       => esc_html__( 'Description', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXTAREA,
                'rows'        => 3,
                'default'     => esc_html__( 'Craft stunning high-converting websites with powerful drag & drop blocks and fluid responsive animations.', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'condition'   => [ 'slide_content_type' => 'custom' ],
            ]
        );

        $repeater->add_control(
            'btn_1_heading',
            [
                'label'     => esc_html__( 'Primary Button', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [ 'slide_content_type' => 'custom' ],
            ]
        );

        $repeater->add_control(
            'show_btn_1',
            [
                'label'        => esc_html__( 'Show Primary Button', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [ 'slide_content_type' => 'custom' ],
            ]
        );

        $repeater->add_control(
            'btn_1_text',
            [
                'label'     => esc_html__( 'Button 1 Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Get Started', 'ultraaddons-elementor-lite' ),
                'dynamic'   => [ 'active' => true ],
                'condition' => [
                    'slide_content_type' => 'custom',
                    'show_btn_1'         => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'btn_1_url',
            [
                'label'       => esc_html__( 'Button 1 Link', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => 'https://example.com',
                'default'     => [ 'url' => '#' ],
                'dynamic'     => [ 'active' => true ],
                'condition'   => [
                    'slide_content_type' => 'custom',
                    'show_btn_1'         => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'btn_1_icon',
            [
                'label'     => esc_html__( 'Button 1 Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::ICONS,
                'condition' => [
                    'slide_content_type' => 'custom',
                    'show_btn_1'         => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'btn_2_heading',
            [
                'label'     => esc_html__( 'Secondary Button', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [ 'slide_content_type' => 'custom' ],
            ]
        );

        $repeater->add_control(
            'show_btn_2',
            [
                'label'        => esc_html__( 'Show Secondary Button', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [ 'slide_content_type' => 'custom' ],
            ]
        );

        $repeater->add_control(
            'btn_2_text',
            [
                'label'     => esc_html__( 'Button 2 Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Watch Video', 'ultraaddons-elementor-lite' ),
                'dynamic'   => [ 'active' => true ],
                'condition' => [
                    'slide_content_type' => 'custom',
                    'show_btn_2'         => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'btn_2_url',
            [
                'label'       => esc_html__( 'Button 2 Link', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => 'https://example.com/video',
                'default'     => [ 'url' => '#' ],
                'dynamic'     => [ 'active' => true ],
                'condition'   => [
                    'slide_content_type' => 'custom',
                    'show_btn_2'         => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'btn_2_icon',
            [
                'label'     => esc_html__( 'Button 2 Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'fas fa-play',
                    'library' => 'solid',
                ],
                'condition' => [
                    'slide_content_type' => 'custom',
                    'show_btn_2'         => 'yes',
                ],
            ]
        );

        $repeater->end_controls_tab();

        // Tab 2: Background
        $repeater->start_controls_tab(
            'tab_slide_background',
            [
                'label' => esc_html__( 'Background', 'ultraaddons-elementor-lite' ),
            ]
        );

        $repeater->add_control(
            'slide_bg_media_type',
            [
                'label'   => esc_html__( 'Background Type', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'image',
                'options' => [
                    'image' => esc_html__( 'Image', 'ultraaddons-elementor-lite' ),
                    'video' => esc_html__( 'Self-Hosted Video (MP4)', 'ultraaddons-elementor-lite' ),
                    'color' => esc_html__( 'Color / Gradient Only', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $repeater->add_control(
            'slide_bg_image',
            [
                'label'     => esc_html__( 'Background Image', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::MEDIA,
                'dynamic'   => [ 'active' => true ],
                'condition' => [
                    'slide_bg_media_type' => 'image',
                ],
            ]
        );

        $repeater->add_control(
            'slide_bg_size',
            [
                'label'     => esc_html__( 'Image Size', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'cover',
                'options'   => [
                    'cover'   => esc_html__( 'Cover', 'ultraaddons-elementor-lite' ),
                    'contain' => esc_html__( 'Contain', 'ultraaddons-elementor-lite' ),
                    'auto'    => esc_html__( 'Auto', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'slide_bg_media_type' => 'image',
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .ua-slide-bg-image' => 'background-size: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_control(
            'slide_kenburns',
            [
                'label'        => esc_html__( 'Ken Burns Zoom Effect', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'condition'    => [
                    'slide_bg_media_type' => 'image',
                ],
            ]
        );

        $repeater->add_control(
            'slide_video_url',
            [
                'label'       => esc_html__( 'Video URL (.mp4)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => 'https://example.com/video.mp4',
                'dynamic'     => [ 'active' => true ],
                'condition'   => [
                    'slide_bg_media_type' => 'video',
                ],
            ]
        );

        $repeater->add_control(
            'slide_overlay_heading',
            [
                'label'     => esc_html__( 'Background Overlay', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $repeater->add_control(
            'show_slide_overlay',
            [
                'label'        => esc_html__( 'Enable Overlay', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $repeater->add_control(
            'slide_overlay_color',
            [
                'label'     => esc_html__( 'Overlay Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(15, 23, 42, 0.65)',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .ua-slide-bg-overlay' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'show_slide_overlay' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'slide_blend_mode',
            [
                'label'     => esc_html__( 'Blend Mode', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'normal',
                'options'   => [
                    'normal'      => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
                    'multiply'    => esc_html__( 'Multiply', 'ultraaddons-elementor-lite' ),
                    'screen'      => esc_html__( 'Screen', 'ultraaddons-elementor-lite' ),
                    'overlay'     => esc_html__( 'Overlay', 'ultraaddons-elementor-lite' ),
                    'darken'      => esc_html__( 'Darken', 'ultraaddons-elementor-lite' ),
                    'lighten'     => esc_html__( 'Lighten', 'ultraaddons-elementor-lite' ),
                    'color-dodge' => esc_html__( 'Color Dodge', 'ultraaddons-elementor-lite' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .ua-slide-bg-overlay' => 'mix-blend-mode: {{VALUE}};',
                ],
                'condition' => [
                    'show_slide_overlay' => 'yes',
                ],
            ]
        );

        $repeater->end_controls_tab();

        $repeater->end_controls_tabs();

        $this->add_control(
            'slides_list',
            [
                'label'       => esc_html__( 'Slide Items', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'slide_title'       => esc_html__( 'Design with Speed', 'ultraaddons-elementor-lite' ),
                        'slide_subtitle'    => esc_html__( 'Next-Gen Platform', 'ultraaddons-elementor-lite' ),
                        'slide_description' => esc_html__( 'Craft stunning high-converting websites with powerful drag & drop blocks and fluid responsive animations.', 'ultraaddons-elementor-lite' ),
                        'btn_1_text'        => esc_html__( 'Get Started', 'ultraaddons-elementor-lite' ),
                        'btn_2_text'        => esc_html__( 'Watch Video', 'ultraaddons-elementor-lite' ),
                        'slide_overlay_color' => 'rgba(15, 23, 42, 0.65)',
                        'slide_kenburns'    => 'yes',
                    ],
                    [
                        'slide_title'       => esc_html__( 'Elevate Your Brand', 'ultraaddons-elementor-lite' ),
                        'slide_subtitle'    => esc_html__( 'Modern UI Suite', 'ultraaddons-elementor-lite' ),
                        'slide_description' => esc_html__( 'Unlock endless layout possibilities with pixel-perfect responsive sliders and interactive modules.', 'ultraaddons-elementor-lite' ),
                        'btn_1_text'        => esc_html__( 'Explore Now', 'ultraaddons-elementor-lite' ),
                        'btn_2_text'        => esc_html__( 'Live Demo', 'ultraaddons-elementor-lite' ),
                        'slide_overlay_color' => 'rgba(15, 23, 42, 0.70)',
                        'slide_kenburns'    => 'no',
                    ],
                    [
                        'slide_title'       => esc_html__( 'Built for Creators', 'ultraaddons-elementor-lite' ),
                        'slide_subtitle'    => esc_html__( 'Fast and Reliable', 'ultraaddons-elementor-lite' ),
                        'slide_description' => esc_html__( 'Ultra-fast load times, lightweight clean code, and seamless responsiveness across all device screens.', 'ultraaddons-elementor-lite' ),
                        'btn_1_text'        => esc_html__( 'Start Free', 'ultraaddons-elementor-lite' ),
                        'btn_2_text'        => esc_html__( 'View Demo', 'ultraaddons-elementor-lite' ),
                        'slide_overlay_color' => 'rgba(15, 23, 42, 0.65)',
                        'slide_kenburns'    => 'yes',
                    ],
                ],
                'title_field' => '{{{ slide_title }}}',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content: Settings Section
     */
    protected function content_settings_controls() {
        $this->start_controls_section(
            'section_content_settings',
            [
                'label' => esc_html__( 'Settings', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'slider_effect',
            [
                'label'   => esc_html__( 'Transition Effect', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'slide',
                'options' => [
                    'slide'     => esc_html__( 'Slide', 'ultraaddons-elementor-lite' ),
                    'fade'      => esc_html__( 'Fade', 'ultraaddons-elementor-lite' ),
                    'coverflow' => esc_html__( 'Coverflow', 'ultraaddons-elementor-lite' ),
                    'flip'      => esc_html__( 'Flip', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_height',
            [
                'label'      => esc_html__( 'Slider Height', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh' ],
                'range'      => [
                    'px' => [ 'min' => 200, 'max' => 1200 ],
                    'vh' => [ 'min' => 20, 'max' => 100 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 520 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-adv-slide' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slides_per_view',
            [
                'label'           => esc_html__( 'Columns / Slides to Show', 'ultraaddons-elementor-lite' ),
                'type'            => Controls_Manager::SELECT,
                'default'         => '1',
                'tablet_default'  => '1',
                'mobile_default'  => '1',
                'options'         => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'condition'       => [
                    'slider_effect' => [ 'slide', 'coverflow' ],
                ],
            ]
        );

        $this->add_responsive_control(
            'space_between',
            [
                'label'      => esc_html__( 'Gutter / Space Between (px)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 0 ],
            ]
        );

        $this->add_control(
            'slider_loop',
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
            'slider_autoplay',
            [
                'label'        => esc_html__( 'Autoplay', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'autoplay_delay',
            [
                'label'     => esc_html__( 'Autoplay Delay (ms)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 5000,
                'step'      => 500,
                'condition' => [
                    'slider_autoplay' => 'yes',
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
                    'slider_autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'transition_speed',
            [
                'label'   => esc_html__( 'Transition Speed (ms)', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 800,
                'step'    => 100,
            ]
        );

        $this->add_control(
            'content_animation',
            [
                'label'     => esc_html__( 'Content Entrance Animation', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'fadeInUp',
                'options'   => [
                    'none'     => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                    'fadeInUp' => esc_html__( 'Fade In Up', 'ultraaddons-elementor-lite' ),
                    'zoomIn'   => esc_html__( 'Zoom In', 'ultraaddons-elementor-lite' ),
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'nav_heading',
            [
                'label'     => esc_html__( 'Navigation & Pagination', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'show_arrows',
            [
                'label'        => esc_html__( 'Navigation Arrows', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'arrows_on_hover',
            [
                'label'        => esc_html__( 'Show Arrows on Hover Only', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'condition'    => [
                    'show_arrows' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_pagination',
            [
                'label'        => esc_html__( 'Pagination', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'pagination_type',
            [
                'label'     => esc_html__( 'Pagination Type', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'bullets',
                'options'   => [
                    'bullets'     => esc_html__( 'Dots / Bullets', 'ultraaddons-elementor-lite' ),
                    'fraction'    => esc_html__( 'Fraction (01 / 05)', 'ultraaddons-elementor-lite' ),
                    'progressbar' => esc_html__( 'Progress Bar', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'show_pagination' => 'yes',
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
                    'show_pagination' => 'yes',
                    'pagination_type' => 'bullets',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Container Section
     */
    protected function style_container_controls() {
        $this->start_controls_section(
            'section_style_container',
            [
                'label' => esc_html__( 'Slider Container', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_align',
            [
                'label'     => esc_html__( 'Content Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [ 'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-center' ],
                    'right'  => [ 'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .ua-slide-content-box' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_valign',
            [
                'label'     => esc_html__( 'Vertical Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'flex-start' => [ 'title' => esc_html__( 'Top', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-v-align-top' ],
                    'center'     => [ 'title' => esc_html__( 'Middle', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-v-align-middle' ],
                    'flex-end'   => [ 'title' => esc_html__( 'Bottom', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-v-align-bottom' ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .ua-adv-slide' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_max_width',
            [
                'label'      => esc_html__( 'Content Box Max Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'vw' ],
                'range'      => [
                    'px' => [ 'min' => 300, 'max' => 1200 ],
                    '%'  => [ 'min' => 30, 'max' => 100 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 780 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-slide-content-box' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label'      => esc_html__( 'Container Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default'    => [
                    'top'      => '40',
                    'right'    => '52',
                    'bottom'   => '40',
                    'left'     => '52',
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-slide-content-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'content_box_bg',
                'label'    => esc_html__( 'Content Box Background', 'ultraaddons-elementor-lite' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-slide-content-box',
            ]
        );

        $this->add_responsive_control(
            'content_box_padding',
            [
                'label'      => esc_html__( 'Content Box Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-slide-content-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_box_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-slide-content-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'slider_box_shadow',
                'selector' => '{{WRAPPER}} .ua-adv-slider-container',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Subtitle Section
     */
    protected function style_subtitle_controls() {
        $this->start_controls_section(
            'section_style_subtitle',
            [
                'label' => esc_html__( 'Subtitle / Badge', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0fc392',
                'selectors' => [
                    '{{WRAPPER}} .ua-slide-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .ua-slide-subtitle',
            ]
        );

        $this->add_responsive_control(
            'subtitle_margin',
            [
                'label'      => esc_html__( 'Spacing (Bottom)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 14 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-slide-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
                'label' => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-slide-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .ua-slide-title',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label'      => esc_html__( 'Spacing (Bottom)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 18 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-slide-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Description Section
     */
    protected function style_description_controls() {
        $this->start_controls_section(
            'section_style_description',
            [
                'label' => esc_html__( 'Description', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(255, 255, 255, 0.88)',
                'selectors' => [
                    '{{WRAPPER}} .ua-slide-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'desc_typography',
                'selector' => '{{WRAPPER}} .ua-slide-description',
            ]
        );

        $this->add_responsive_control(
            'desc_margin',
            [
                'label'      => esc_html__( 'Spacing (Bottom)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 28 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-slide-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Primary Button
     */
    protected function style_primary_button_controls() {
        $this->start_controls_section(
            'section_style_primary_btn',
            [
                'label' => esc_html__( 'Button Primary', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'buttons_layout',
            [
                'label'     => esc_html__( 'Buttons Layout', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'row'    => [ 'title' => esc_html__( 'Side by Side (Inline)', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-ellipsis-h' ],
                    'column' => [ 'title' => esc_html__( 'Stacked', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-ellipsis-v' ],
                ],
                'default'   => 'row',
                'selectors' => [
                    '{{WRAPPER}} .ua-slide-btn-wrap' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'buttons_gap',
            [
                'label'      => esc_html__( 'Space Between Buttons', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 14 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-slide-btn-wrap' => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'separator'  => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'btn_1_typography',
                'selector' => '{{WRAPPER}} .ua-slide-btn-primary',
            ]
        );

        $this->start_controls_tabs( 'tabs_btn_1_style' );

        $this->start_controls_tab(
            'tab_btn_1_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'btn_1_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-slide-btn-primary' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'btn_1_background',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-slide-btn-primary',
            ]
        );

        $this->add_control(
            'btn_1_border_type',
            [
                'label'     => esc_html__( 'Border Type', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'solid',
                'options'   => [
                    'none'   => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                    'solid'  => esc_html__( 'Solid', 'ultraaddons-elementor-lite' ),
                    'double' => esc_html__( 'Double', 'ultraaddons-elementor-lite' ),
                    'dotted' => esc_html__( 'Dotted', 'ultraaddons-elementor-lite' ),
                    'dashed' => esc_html__( 'Dashed', 'ultraaddons-elementor-lite' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-slide-btn-primary' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_1_border_width',
            [
                'label'      => esc_html__( 'Border Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default'    => [
                    'top'      => '2',
                    'right'    => '2',
                    'bottom'   => '2',
                    'left'     => '2',
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-slide-btn-primary' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'btn_1_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'btn_1_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0fc392',
                'selectors' => [
                    '{{WRAPPER}} .ua-slide-btn-primary' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'btn_1_border_type!' => 'none',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'btn_1_box_shadow',
                'selector' => '{{WRAPPER}} .ua-slide-btn-primary',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_btn_1_hover',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'btn_1_hover_text_color',
            [
                'label'     => esc_html__( 'Hover Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-slide-btn-primary:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'btn_1_hover_background',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-slide-btn-primary:hover',
            ]
        );

        $this->add_control(
            'btn_1_hover_border_color',
            [
                'label'     => esc_html__( 'Hover Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0da87d',
                'selectors' => [
                    '{{WRAPPER}} .ua-slide-btn-primary:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'btn_1_border_type!' => 'none',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'btn_1_hover_box_shadow',
                'selector' => '{{WRAPPER}} .ua-slide-btn-primary:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'btn_1_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default'    => [
                    'top'      => '8',
                    'right'    => '8',
                    'bottom'   => '8',
                    'left'     => '8',
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-slide-btn-primary' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'before',
            ]
        );

        $this->add_responsive_control(
            'btn_1_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default'    => [
                    'top'      => '14',
                    'right'    => '30',
                    'bottom'   => '14',
                    'left'     => '30',
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-slide-btn-primary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Secondary Button
     */
    protected function style_secondary_button_controls() {
        $this->start_controls_section(
            'section_style_secondary_btn',
            [
                'label' => esc_html__( 'Button Secondary', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'btn_2_typography',
                'selector' => '{{WRAPPER}} .ua-slide-btn-secondary',
            ]
        );

        $this->start_controls_tabs( 'tabs_btn_2_style' );

        $this->start_controls_tab(
            'tab_btn_2_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'btn_2_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-slide-btn-secondary' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'btn_2_background',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-slide-btn-secondary',
            ]
        );

        $this->add_control(
            'btn_2_border_type',
            [
                'label'     => esc_html__( 'Border Type', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'solid',
                'options'   => [
                    'none'   => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                    'solid'  => esc_html__( 'Solid', 'ultraaddons-elementor-lite' ),
                    'double' => esc_html__( 'Double', 'ultraaddons-elementor-lite' ),
                    'dotted' => esc_html__( 'Dotted', 'ultraaddons-elementor-lite' ),
                    'dashed' => esc_html__( 'Dashed', 'ultraaddons-elementor-lite' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-slide-btn-secondary' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_2_border_width',
            [
                'label'      => esc_html__( 'Border Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default'    => [
                    'top'      => '2',
                    'right'    => '2',
                    'bottom'   => '2',
                    'left'     => '2',
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-slide-btn-secondary' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'btn_2_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'btn_2_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(255, 255, 255, 0.4)',
                'selectors' => [
                    '{{WRAPPER}} .ua-slide-btn-secondary' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'btn_2_border_type!' => 'none',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'btn_2_box_shadow',
                'selector' => '{{WRAPPER}} .ua-slide-btn-secondary',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_btn_2_hover',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'btn_2_hover_text_color',
            [
                'label'     => esc_html__( 'Hover Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-slide-btn-secondary:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'btn_2_hover_background',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-slide-btn-secondary:hover',
            ]
        );

        $this->add_control(
            'btn_2_hover_border_color',
            [
                'label'     => esc_html__( 'Hover Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0fc392',
                'selectors' => [
                    '{{WRAPPER}} .ua-slide-btn-secondary:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'btn_2_border_type!' => 'none',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'btn_2_hover_box_shadow',
                'selector' => '{{WRAPPER}} .ua-slide-btn-secondary:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'btn_2_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default'    => [
                    'top'      => '8',
                    'right'    => '8',
                    'bottom'   => '8',
                    'left'     => '8',
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-slide-btn-secondary' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'before',
            ]
        );

        $this->add_responsive_control(
            'btn_2_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default'    => [
                    'top'      => '14',
                    'right'    => '30',
                    'bottom'   => '14',
                    'left'     => '30',
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-slide-btn-secondary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Navigation Arrows
     */
    protected function style_arrows_controls() {
        $this->start_controls_section(
            'section_style_arrows',
            [
                'label'     => esc_html__( 'Navigation Arrows', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_arrows' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_icon_size',
            [
                'label'      => esc_html__( 'Arrow Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 12, 'max' => 80 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 28 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-slider-arrow' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_arrows_style' );

        $this->start_controls_tab(
            'tab_arrows_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-slider-arrow' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_arrows_hover',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'arrow_hover_color',
            [
                'label'     => esc_html__( 'Hover Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0fc392',
                'selectors' => [
                    '{{WRAPPER}} .ua-slider-arrow:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'arrow_horizontal_offset',
            [
                'label'      => esc_html__( 'Horizontal Distance from Edge', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [ 'min' => -80, 'max' => 150 ],
                    '%'  => [ 'min' => -20, 'max' => 30 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 12 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-slider-arrow-prev' => 'left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-slider-arrow-next' => 'right: {{SIZE}}{{UNIT}};',
                ],
                'separator'  => 'before',
            ]
        );

        $this->add_responsive_control(
            'arrow_vertical_position',
            [
                'label'      => esc_html__( 'Vertical Position (%)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range'      => [ '%' => [ 'min' => 10, 'max' => 90 ] ],
                'default'    => [ 'unit' => '%', 'size' => 50 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-slider-arrow' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Pagination
     */
    protected function style_pagination_controls() {
        $this->start_controls_section(
            'section_style_pagination',
            [
                'label'     => esc_html__( 'Pagination', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_pagination' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'bullet_color',
            [
                'label'     => esc_html__( 'Inactive Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(255, 255, 255, 0.5)',
                'selectors' => [
                    '{{WRAPPER}} .ua-slider-pagination .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bullet_active_color',
            [
                'label'     => esc_html__( 'Active Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0fc392',
                'selectors' => [
                    '{{WRAPPER}} .ua-slider-pagination .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-slider-pagination.swiper-pagination-progressbar .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'bullet_size',
            [
                'label'      => esc_html__( 'Bullet Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 4, 'max' => 20 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 10 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-slider-pagination .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'pagination_type' => 'bullets',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_bottom_pos',
            [
                'label'      => esc_html__( 'Bottom Position', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 24 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-slider-pagination' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render Output on Frontend.
     */
    protected function render() {
        wp_enqueue_style( 'e-swiper' );
        wp_enqueue_style( 'ultraaddons-advanced-slider' );
        wp_enqueue_script( 'swiper' );
        wp_enqueue_script( 'ultraaddons-advanced-slider-js' );

        $settings = $this->get_settings_for_display();

        $slides_list       = ! empty( $settings['slides_list'] ) && is_array( $settings['slides_list'] ) ? $settings['slides_list'] : [];
        $slider_effect     = ! empty( $settings['slider_effect'] ) ? $settings['slider_effect'] : 'slide';
        $slider_loop       = ! empty( $settings['slider_loop'] ) && 'yes' === $settings['slider_loop'];
        $slider_autoplay   = ! empty( $settings['slider_autoplay'] ) && 'yes' === $settings['slider_autoplay'];
        $autoplay_delay    = ! empty( $settings['autoplay_delay'] ) ? (int) $settings['autoplay_delay'] : 5000;
        $pause_on_hover    = ! empty( $settings['pause_on_hover'] ) && 'yes' === $settings['pause_on_hover'];
        $transition_speed  = ! empty( $settings['transition_speed'] ) ? (int) $settings['transition_speed'] : 800;
        $content_anim      = ! empty( $settings['content_animation'] ) ? $settings['content_animation'] : 'fadeInUp';
        $show_arrows       = ! empty( $settings['show_arrows'] ) && 'yes' === $settings['show_arrows'];
        $arrows_on_hover   = ! empty( $settings['arrows_on_hover'] ) && 'yes' === $settings['arrows_on_hover'];
        $show_pagination   = ! empty( $settings['show_pagination'] ) && 'yes' === $settings['show_pagination'];
        $pagination_type   = ! empty( $settings['pagination_type'] ) ? $settings['pagination_type'] : 'bullets';
        $dynamic_bullets   = ! empty( $settings['dynamic_bullets'] ) && 'yes' === $settings['dynamic_bullets'];

        $slides_per_view_desktop = ! empty( $settings['slides_per_view'] ) ? (int) $settings['slides_per_view'] : 1;
        $slides_per_view_tablet  = ! empty( $settings['slides_per_view_tablet'] ) ? (int) $settings['slides_per_view_tablet'] : 1;
        $slides_per_view_mobile  = ! empty( $settings['slides_per_view_mobile'] ) ? (int) $settings['slides_per_view_mobile'] : 1;

        $space_between_desktop = isset( $settings['space_between']['size'] ) ? (int) $settings['space_between']['size'] : 0;
        $space_between_tablet  = isset( $settings['space_between_tablet']['size'] ) ? (int) $settings['space_between_tablet']['size'] : $space_between_desktop;
        $space_between_mobile  = isset( $settings['space_between_mobile']['size'] ) ? (int) $settings['space_between_mobile']['size'] : $space_between_tablet;

        // Compile slider settings JSON
        $slider_settings_data = [
            'effect'                 => $slider_effect,
            'speed'                  => $transition_speed,
            'loop'                   => $slider_loop,
            'autoplay'               => $slider_autoplay,
            'autoplayDelay'          => $autoplay_delay,
            'pauseOnHover'           => $pause_on_hover,
            'slidesPerView_desktop' => $slides_per_view_desktop,
            'slidesPerView_tablet'  => $slides_per_view_tablet,
            'slidesPerView_mobile'  => $slides_per_view_mobile,
            'spaceBetween_desktop'  => $space_between_desktop,
            'spaceBetween_tablet'   => $space_between_tablet,
            'spaceBetween_mobile'   => $space_between_mobile,
            'paginationType'         => $pagination_type,
            'dynamicBullets'         => $dynamic_bullets,
        ];

        $container_classes = [ 'ua-adv-slider-container' ];
        if ( $arrows_on_hover ) {
            $container_classes[] = 'ua-arrows-on-hover';
        }
        ?>

        <div class="<?php echo esc_attr( implode( ' ', $container_classes ) ); ?>"
             data-slider-settings="<?php echo esc_attr( wp_json_encode( $slider_settings_data ) ); ?>">

            <div class="ua-adv-slider swiper">
                <div class="swiper-wrapper">

                    <?php foreach ( $slides_list as $index => $slide ) : 
                        $content_type   = ! empty( $slide['slide_content_type'] ) ? $slide['slide_content_type'] : 'custom';
                        $template_id    = ! empty( $slide['slide_template_id'] ) ? (int) $slide['slide_template_id'] : 0;
                        $bg_media_type  = ! empty( $slide['slide_bg_media_type'] ) ? $slide['slide_bg_media_type'] : 'image';
                        $bg_image_url   = ! empty( $slide['slide_bg_image']['url'] ) ? $slide['slide_bg_image']['url'] : '';
                        $is_kenburns    = ! empty( $slide['slide_kenburns'] ) && 'yes' === $slide['slide_kenburns'];
                        $bg_video_url   = ! empty( $slide['slide_video_url'] ) ? $slide['slide_video_url'] : '';
                        $show_overlay   = ! empty( $slide['show_slide_overlay'] ) && 'yes' === $slide['show_slide_overlay'];

                        $slide_classes = [ 'ua-adv-slide', 'swiper-slide', 'elementor-repeater-item-' . ( $slide['_id'] ?? $index ) ];
                        if ( $is_kenburns ) {
                            $slide_classes[] = 'ua-kenburns-zoom';
                        }
                    ?>
                        <div class="<?php echo esc_attr( implode( ' ', $slide_classes ) ); ?>">

                            <!-- Slide Background & Overlay -->
                            <div class="ua-slide-bg-wrap">
                                <?php if ( 'video' === $bg_media_type && ! empty( $bg_video_url ) ) : ?>
                                    <video class="ua-slide-bg-video" autoplay muted loop playsinline src="<?php echo esc_url( $bg_video_url ); ?>"></video>
                                <?php elseif ( 'image' === $bg_media_type && ! empty( $bg_image_url ) ) : ?>
                                    <div class="ua-slide-bg-image" style="background-image: url('<?php echo esc_url( $bg_image_url ); ?>');"></div>
                                <?php endif; ?>

                                <?php if ( $show_overlay ) : ?>
                                    <div class="ua-slide-bg-overlay"></div>
                                <?php endif; ?>
                            </div>

                            <!-- Slide Content -->
                            <div class="ua-slide-content-wrap">
                                <?php if ( 'template' === $content_type && ! empty( $template_id ) ) : ?>
                                    <div class="ua-slide-template-box">
                                        <?php echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $template_id ); ?>
                                    </div>
                                <?php else : 
                                    $title_tag = ! empty( $slide['slide_title_tag'] ) ? $slide['slide_title_tag'] : 'h2';
                                    $anim_class = 'none' !== $content_anim ? 'ua-anim-' . esc_attr( $content_anim ) : '';
                                ?>
                                    <div class="ua-slide-content-box <?php echo esc_attr( $anim_class ); ?>">
                                        <?php if ( ! empty( $slide['slide_subtitle'] ) ) : ?>
                                            <span class="ua-slide-subtitle"><?php echo esc_html( $slide['slide_subtitle'] ); ?></span>
                                        <?php endif; ?>

                                        <?php if ( ! empty( $slide['slide_title'] ) ) : ?>
                                            <<?php echo esc_attr( $title_tag ); ?> class="ua-slide-title">
                                                <?php echo esc_html( $slide['slide_title'] ); ?>
                                            </<?php echo esc_attr( $title_tag ); ?>>
                                        <?php endif; ?>

                                        <?php if ( ! empty( $slide['slide_description'] ) ) : ?>
                                            <p class="ua-slide-description"><?php echo esc_html( $slide['slide_description'] ); ?></p>
                                        <?php endif; ?>

                                        <?php 
                                        $show_btn_1 = ! empty( $slide['show_btn_1'] ) && 'yes' === $slide['show_btn_1'] && ! empty( $slide['btn_1_text'] );
                                        $show_btn_2 = ! empty( $slide['show_btn_2'] ) && 'yes' === $slide['show_btn_2'] && ! empty( $slide['btn_2_text'] );
                                        ?>

                                        <?php if ( $show_btn_1 || $show_btn_2 ) : ?>
                                            <div class="ua-slide-btn-wrap">
                                                <?php if ( $show_btn_1 ) : 
                                                    $btn_1_url  = ! empty( $slide['btn_1_url']['url'] ) ? $slide['btn_1_url']['url'] : '#';
                                                    $btn_1_attr = '';
                                                    if ( ! empty( $slide['btn_1_url']['is_external'] ) ) $btn_1_attr .= ' target="_blank"';
                                                    if ( ! empty( $slide['btn_1_url']['nofollow'] ) ) $btn_1_attr .= ' rel="nofollow"';
                                                ?>
                                                    <a href="<?php echo esc_url( $btn_1_url ); ?>" class="ua-slide-btn ua-slide-btn-primary"<?php echo $btn_1_attr; ?>>
                                                        <?php if ( ! empty( $slide['btn_1_icon']['value'] ) ) : ?>
                                                            <span class="ua-slide-btn-icon" aria-hidden="true">
                                                                <?php Icons_Manager::render_icon( $slide['btn_1_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                                            </span>
                                                        <?php endif; ?>
                                                        <span><?php echo esc_html( $slide['btn_1_text'] ); ?></span>
                                                    </a>
                                                <?php endif; ?>

                                                <?php if ( $show_btn_2 ) : 
                                                    $btn_2_url  = ! empty( $slide['btn_2_url']['url'] ) ? $slide['btn_2_url']['url'] : '#';
                                                    $btn_2_attr = '';
                                                    if ( ! empty( $slide['btn_2_url']['is_external'] ) ) $btn_2_attr .= ' target="_blank"';
                                                    if ( ! empty( $slide['btn_2_url']['nofollow'] ) ) $btn_2_attr .= ' rel="nofollow"';
                                                ?>
                                                    <a href="<?php echo esc_url( $btn_2_url ); ?>" class="ua-slide-btn ua-slide-btn-secondary"<?php echo $btn_2_attr; ?>>
                                                        <?php if ( ! empty( $slide['btn_2_icon']['value'] ) ) : ?>
                                                            <span class="ua-slide-btn-icon" aria-hidden="true">
                                                                <?php Icons_Manager::render_icon( $slide['btn_2_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                                            </span>
                                                        <?php endif; ?>
                                                        <span><?php echo esc_html( $slide['btn_2_text'] ); ?></span>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>
                    <?php endforeach; ?>

                </div>

                <!-- Navigation Arrows -->
                <?php if ( $show_arrows ) : ?>
                    <div class="ua-slider-arrow ua-slider-arrow-prev" role="button" aria-label="<?php echo esc_attr__( 'Previous slide', 'ultraaddons-elementor-lite' ); ?>">
                        <i class="fas fa-chevron-left" aria-hidden="true"></i>
                    </div>
                    <div class="ua-slider-arrow ua-slider-arrow-next" role="button" aria-label="<?php echo esc_attr__( 'Next slide', 'ultraaddons-elementor-lite' ); ?>">
                        <i class="fas fa-chevron-right" aria-hidden="true"></i>
                    </div>
                <?php endif; ?>

                <!-- Pagination -->
                <?php if ( $show_pagination ) : ?>
                    <div class="ua-slider-pagination"></div>
                <?php endif; ?>

            </div>

        </div>
        <?php
    }
}
