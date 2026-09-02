<?php
/**
 * Content Ticker Widget
 *
 * A modern, ultra-smart, 100% Free Content & News Ticker for Elementor.
 * Features Continuous 60fps Marquee, Typewriter Animation, Vertical Slide,
 * Horizontal Slide, Fade, Dynamic Post Query, Custom Repeater items,
 * and a Live TV-style pulsating recording badge.
 *
 * @package UltraAddons
 * @since 2.0.3.5
 */

namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Content_Ticker extends Base {

    /**
     * Constructor: Register widget CSS and JS dependencies.
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        $css_file = ULTRA_ADDONS_DIR . 'assets/css/widgets/content-ticker.css';
        $css_ver  = file_exists( $css_file ) ? filemtime( $css_file ) : ULTRA_ADDONS_VERSION;

        wp_register_style(
            'ultraaddons-content-ticker',
            ULTRA_ADDONS_ASSETS . 'css/widgets/content-ticker.css',
            [ 'ultraaddons-widgets-style' ],
            $css_ver
        );

        $js_file = ULTRA_ADDONS_DIR . 'assets/js/frontend-content-ticker.js';
        $js_ver  = file_exists( $js_file ) ? filemtime( $js_file ) : ULTRA_ADDONS_VERSION;

        wp_register_script(
            'ultraaddons-content-ticker',
            ULTRA_ADDONS_ASSETS . 'js/frontend-content-ticker.js',
            [ 'jquery', 'elementor-frontend' ],
            $js_ver,
            true
        );
    }

    /**
     * Widget Style Dependencies.
     */
    public function get_style_depends() {
        return [ 'ultraaddons-content-ticker', 'e-swiper' ];
    }

    /**
     * Widget Script Dependencies.
     */
    public function get_script_depends() {
        return [ 'ultraaddons-content-ticker', 'swiper' ];
    }

    /**
     * Get Widget Name.
     */
    public function get_name() {
        return 'ultraaddons-content-ticker';
    }

    /**
     * Get Widget Title.
     */
    public function get_title() {
        return esc_html__( 'Content Ticker', 'ultraaddons-elementor-lite' );
    }

    /**
     * Get Widget Icon.
     */
    public function get_icon() {
        return 'ultraaddons eicon-carousel';
    }

    /**
     * Get Widget Categories.
     */
    public function get_categories() {
        return [ 'ultraaddons-elementor-lite', 'header-footer-elementor' ];
    }

    /**
     * Get Widget Search Keywords.
     */
    public function get_keywords() {
        return [ 'ultraaddons', 'content ticker', 'news ticker', 'ticker', 'marquee', 'trending', 'breaking news', 'post ticker' ];
    }

    /**
     * Helper: Fetch categories for dynamic query dropdown.
     */
    protected function get_post_categories() {
        $categories = get_categories( [
            'hide_empty' => false,
        ] );

        $options = [];
        if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
            foreach ( $categories as $category ) {
                $options[ $category->term_id ] = $category->name;
            }
        }
        return $options;
    }

    /**
     * Register Controls.
     */
    protected function register_controls() {
        $this->register_general_controls();
        $this->register_custom_items_controls();
        $this->register_query_controls();
        $this->register_heading_controls();
        $this->register_settings_controls();
        $this->register_meta_controls();

        // Style Sections
        $this->register_style_heading_controls();
        $this->register_style_ticker_controls();
        $this->register_style_meta_controls();
        $this->register_style_nav_controls();
        $this->register_style_box_controls();
    }

    /**
     * Content Section: Source Selection.
     */
    protected function register_general_controls() {
        $this->start_controls_section(
            'section_general',
            [
                'label' => esc_html__( 'Content Source', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'source_type',
            [
                'label'   => esc_html__( 'Select Source', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'custom',
                'options' => [
                    'custom'  => esc_html__( 'Custom Items (Headlines)', 'ultraaddons-elementor-lite' ),
                    'dynamic' => esc_html__( 'Dynamic WordPress Posts', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content Section: Custom Items (Repeater).
     */
    protected function register_custom_items_controls() {
        $this->start_controls_section(
            'section_custom_items',
            [
                'label'     => esc_html__( 'Ticker Headlines', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'source_type' => 'custom',
                ],
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'title',
            [
                'label'       => esc_html__( 'Headline Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'Global tech summit announces breakthrough in clean fusion energy',
                'label_block' => true,
                'dynamic'     => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'badge',
            [
                'label'       => esc_html__( 'Category / Tag Badge', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'TECH',
                'placeholder' => 'TECH, NEWS, SALE',
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label'       => esc_html__( 'Link', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'ultraaddons-elementor-lite' ),
                'default'     => [
                    'url' => '#',
                ],
                'dynamic'     => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'custom_image',
            [
                'label'   => esc_html__( 'Optional Thumbnail', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'ticker_items',
            [
                'label'       => esc_html__( 'Headlines List', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'title' => 'Global tech summit announces major breakthrough in clean fusion energy',
                        'badge' => 'TECH',
                        'link'  => [ 'url' => '#' ],
                    ],
                    [
                        'title' => 'Stock markets reach new all-time highs as worldwide inflation cools down',
                        'badge' => 'MARKETS',
                        'link'  => [ 'url' => '#' ],
                    ],
                    [
                        'title' => 'Next-gen electric aircraft completes first zero-emission cross-country flight',
                        'badge' => 'INNOVATION',
                        'link'  => [ 'url' => '#' ],
                    ],
                    [
                        'title' => 'Deep space observatory detects signs of water vapor on habitable exoplanet',
                        'badge' => 'SCIENCE',
                        'link'  => [ 'url' => '#' ],
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content Section: Dynamic Posts Query.
     */
    protected function register_query_controls() {
        $this->start_controls_section(
            'section_query',
            [
                'label'     => esc_html__( 'Post Query', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'source_type' => 'dynamic',
                ],
            ]
        );

        $post_types = [
            'post' => esc_html__( 'Posts', 'ultraaddons-elementor-lite' ),
            'page' => esc_html__( 'Pages', 'ultraaddons-elementor-lite' ),
        ];

        if ( post_type_exists( 'product' ) ) {
            $post_types['product'] = esc_html__( 'WooCommerce Products', 'ultraaddons-elementor-lite' );
        }

        $this->add_control(
            'query_post_type',
            [
                'label'   => esc_html__( 'Post Type', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'post',
                'options' => $post_types,
            ]
        );

        $this->add_control(
            'query_categories',
            [
                'label'       => esc_html__( 'Filter Categories', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT2,
                'multiple'    => true,
                'options'     => $this->get_post_categories(),
                'label_block' => true,
                'condition'   => [
                    'query_post_type' => 'post',
                ],
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label'   => esc_html__( 'Number of Items', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 8,
                'min'     => 1,
                'max'     => 30,
            ]
        );

        $this->add_control(
            'offset',
            [
                'label'   => esc_html__( 'Offset', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 0,
                'min'     => 0,
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
                    'modified'      => esc_html__( 'Last Modified', 'ultraaddons-elementor-lite' ),
                    'title'         => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
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
                    'DESC' => esc_html__( 'Descending', 'ultraaddons-elementor-lite' ),
                    'ASC'  => esc_html__( 'Ascending', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content Section: Heading / Badge.
     */
    protected function register_heading_controls() {
        $this->start_controls_section(
            'section_heading',
            [
                'label' => esc_html__( 'Heading Badge', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_heading',
            [
                'label'        => esc_html__( 'Show Heading Badge', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'heading_text',
            [
                'label'       => esc_html__( 'Badge Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'TRENDING NOW',
                'placeholder' => 'BREAKING NEWS, HOT, etc.',
                'condition'   => [
                    'show_heading' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'heading_icon_type',
            [
                'label'     => esc_html__( 'Badge Indicator', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'pulse',
                'options'   => [
                    'pulse' => esc_html__( 'Live Pulsing Dot', 'ultraaddons-elementor-lite' ),
                    'icon'  => esc_html__( 'FontAwesome Icon', 'ultraaddons-elementor-lite' ),
                    'none'  => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'show_heading' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'heading_icon',
            [
                'label'     => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'fas fa-bolt',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_heading'      => 'yes',
                    'heading_icon_type' => 'icon',
                ],
            ]
        );

        $this->add_control(
            'heading_triangle',
            [
                'label'        => esc_html__( 'Triangle Arrow Pointer', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'show_heading' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'heading_position',
            [
                'label'     => esc_html__( 'Badge Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => 'left',
                'options'   => [
                    'left'  => [
                        'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'condition' => [
                    'show_heading' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'heading_link',
            [
                'label'       => esc_html__( 'Badge Link (Optional)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'show_heading' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content Section: Ticker Animation & Behavior Settings.
     */
    protected function register_settings_controls() {
        $this->start_controls_section(
            'section_ticker_settings',
            [
                'label' => esc_html__( 'Ticker Animation & Settings', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ticker_mode',
            [
                'label'   => esc_html__( 'Animation Effect', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'marquee',
                'options' => [
                    'marquee'    => esc_html__( 'Continuous Marquee (Smooth Tape)', 'ultraaddons-elementor-lite' ),
                    'typing'     => esc_html__( 'Typewriter (Typing Effect)', 'ultraaddons-elementor-lite' ),
                    'vertical'   => esc_html__( 'Vertical Slide (Classic TV)', 'ultraaddons-elementor-lite' ),
                    'horizontal' => esc_html__( 'Horizontal Slide', 'ultraaddons-elementor-lite' ),
                    'fade'       => esc_html__( 'Fade Transition', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'slide_direction',
            [
                'label'     => esc_html__( 'Slide Direction', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'right_to_left',
                'options'   => [
                    'right_to_left' => esc_html__( 'Slide from Right (Right to Left)', 'ultraaddons-elementor-lite' ),
                    'left_to_right' => esc_html__( 'Slide from Left (Left to Right)', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'ticker_mode' => 'horizontal',
                ],
            ]
        );

        $this->add_control(
            'vertical_direction',
            [
                'label'     => esc_html__( 'Vertical Direction', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'up',
                'options'   => [
                    'up'   => esc_html__( 'Slide Up (Bottom to Top)', 'ultraaddons-elementor-lite' ),
                    'down' => esc_html__( 'Slide Down (Top to Bottom)', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'ticker_mode' => 'vertical',
                ],
            ]
        );

        $this->add_control(
            'marquee_direction',
            [
                'label'     => esc_html__( 'Marquee Direction', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'left',
                'options'   => [
                    'left'  => esc_html__( 'Slide from Right to Left (Scroll Left)', 'ultraaddons-elementor-lite' ),
                    'right' => esc_html__( 'Slide from Left to Right (Scroll Right)', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'ticker_mode' => 'marquee',
                ],
            ]
        );

        $this->add_control(
            'marquee_speed',
            [
                'label'       => esc_html__( 'Marquee Speed (Duration in Seconds)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 28,
                'min'         => 6,
                'max'         => 120,
                'step'        => 1,
                'description' => esc_html__( 'Higher duration creates a slower, smoother crawl.', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'ticker_mode' => 'marquee',
                ],
            ]
        );

        $this->add_control(
            'typing_speed',
            [
                'label'     => esc_html__( 'Typing Speed (ms per char)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 50,
                'min'       => 20,
                'max'       => 250,
                'condition' => [
                    'ticker_mode' => 'typing',
                ],
            ]
        );

        $this->add_control(
            'autoplay_delay',
            [
                'label'     => esc_html__( 'Autoplay Delay (Seconds)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 3.5,
                'min'       => 1,
                'max'       => 15,
                'step'      => 0.5,
                'condition' => [
                    'ticker_mode!' => 'marquee',
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
            ]
        );

        $this->add_control(
            'show_nav',
            [
                'label'        => esc_html__( 'Navigation Controls (Next / Prev)', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'nav_position',
            [
                'label'     => esc_html__( 'Navigation Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'right',
                'options'   => [
                    'right' => esc_html__( 'Right Side', 'ultraaddons-elementor-lite' ),
                    'left'  => esc_html__( 'Left Side', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'show_nav' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_gap',
            [
                'label'      => esc_html__( 'Item Gap / Spacing (px)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 36,
                ],
                'selectors'  => [
                    '{{WRAPPER}}' => '--ua-ticker-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content Section: Metadata Elements.
     */
    protected function register_meta_controls() {
        $this->start_controls_section(
            'section_meta',
            [
                'label' => esc_html__( 'Metadata & Display', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_thumb',
            [
                'label'        => esc_html__( 'Show Thumbnail Image', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'show_badge',
            [
                'label'        => esc_html__( 'Show Category Badge', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'show_date',
            [
                'label'        => esc_html__( 'Show Date', 'ultraaddons-elementor-lite' ),
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
     * Style Section: Heading Badge.
     */
    protected function register_style_heading_controls() {
        $this->start_controls_section(
            'section_style_heading',
            [
                'label' => esc_html__( 'Heading Badge', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'heading_typography',
                'selector' => '{{WRAPPER}} .ua-ticker-badge',
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-ticker-badge, {{WRAPPER}} .ua-ticker-badge a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'heading_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ef4444',
                'selectors' => [
                    '{{WRAPPER}} .ua-ticker-badge'         => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-ticker-triangle-left'  => 'border-left-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-ticker-triangle-right' => 'border-right-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'live_pulse_color',
            [
                'label'     => esc_html__( 'Live Pulse Dot Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}}' => '--ua-pulse-dot-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'heading_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'top'      => 10,
                    'right'    => 20,
                    'bottom'   => 10,
                    'left'     => 18,
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-ticker-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Section: Ticker Headlines / Content.
     */
    protected function register_style_ticker_controls() {
        $this->start_controls_section(
            'section_style_ticker',
            [
                'label' => esc_html__( 'Headlines / Content', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ticker_typography',
                'selector' => '{{WRAPPER}} .ua-ticker-item-title',
            ]
        );

        $this->start_controls_tabs( 'tabs_ticker_title_style' );

        $this->start_controls_tab(
            'tab_ticker_title_normal',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ticker_title_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .ua-ticker-item-title, {{WRAPPER}} .ua-ticker-item-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_ticker_title_hover',
            [
                'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ticker_title_hover_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ef4444',
                'selectors' => [
                    '{{WRAPPER}} .ua-ticker-item:hover .ua-ticker-item-title, {{WRAPPER}} .ua-ticker-item:hover .ua-ticker-item-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /**
     * Style Section: Metadata (Badges & Dates & Thumbs).
     */
    protected function register_style_meta_controls() {
        $this->start_controls_section(
            'section_style_meta',
            [
                'label' => esc_html__( 'Metadata & Badges', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'heading_meta_badge',
            [
                'label'     => esc_html__( 'Category Badge', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'meta_badge_color',
            [
                'label'     => esc_html__( 'Badge Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-ticker-item-badge' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'meta_badge_bg',
            [
                'label'     => esc_html__( 'Badge Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .ua-ticker-item-badge' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'heading_meta_date',
            [
                'label'     => esc_html__( 'Date', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'meta_date_color',
            [
                'label'     => esc_html__( 'Date Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#9ca3af',
                'selectors' => [
                    '{{WRAPPER}} .ua-ticker-item-date' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Section: Navigation Buttons.
     */
    protected function register_style_nav_controls() {
        $this->start_controls_section(
            'section_style_nav',
            [
                'label' => esc_html__( 'Navigation Controls', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'nav_size',
            [
                'label'      => esc_html__( 'Button Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 24,
                        'max' => 60,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 32,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-ticker-nav-btn' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_nav_style' );

        $this->start_controls_tab(
            'tab_nav_normal',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'nav_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} .ua-ticker-nav-btn' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(0, 0, 0, 0.05)',
                'selectors' => [
                    '{{WRAPPER}} .ua-ticker-nav-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_nav_hover',
            [
                'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'nav_hover_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-ticker-nav-btn:hover' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_hover_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ef4444',
                'selectors' => [
                    '{{WRAPPER}} .ua-ticker-nav-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /**
     * Style Section: Main Box Container.
     */
    protected function register_style_box_controls() {
        $this->start_controls_section(
            'section_style_box',
            [
                'label' => esc_html__( 'Ticker Container', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'box_height',
            [
                'label'      => esc_html__( 'Container Height', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 36,
                        'max' => 90,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 48,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-content-ticker' => 'min-height: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}' => '--ua-ticker-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'box_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-content-ticker',
                'default'  => [
                    'background' => 'classic',
                    'color'      => '#ffffff',
                ],
            ]
        );

        $this->add_responsive_control(
            'box_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'    => [
                    'top'      => 6,
                    'right'    => 6,
                    'bottom'   => 6,
                    'left'     => 6,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-content-ticker' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'box_border',
                'selector' => '{{WRAPPER}} .ua-content-ticker',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'box_shadow',
                'selector' => '{{WRAPPER}} .ua-content-ticker',
                'default'  => [
                    'horizontal' => 0,
                    'vertical'   => 2,
                    'blur'       => 12,
                    'spread'     => 0,
                    'color'      => 'rgba(0, 0, 0, 0.06)',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Get Ticker Items (either from Custom repeater or Dynamic WP Query).
     */
    protected function get_ticker_items( $settings ) {
        $items = [];

        if ( 'dynamic' === $settings['source_type'] ) {
            $post_type      = ! empty( $settings['query_post_type'] ) ? $settings['query_post_type'] : 'post';
            $posts_per_page = ! empty( $settings['posts_per_page'] ) ? absint( $settings['posts_per_page'] ) : 8;
            $offset         = ! empty( $settings['offset'] ) ? absint( $settings['offset'] ) : 0;
            $orderby        = ! empty( $settings['orderby'] ) ? $settings['orderby'] : 'date';
            $order          = ! empty( $settings['order'] ) ? $settings['order'] : 'DESC';

            $args = [
                'post_type'      => $post_type,
                'posts_per_page' => $posts_per_page,
                'offset'         => $offset,
                'orderby'        => $orderby,
                'order'          => $order,
                'post_status'    => 'publish',
            ];

            if ( 'post' === $post_type && ! empty( $settings['query_categories'] ) ) {
                $args['category__in'] = $settings['query_categories'];
            }

            $query = new \WP_Query( $args );

            if ( $query->have_posts() ) {
                while ( $query->have_posts() ) {
                    $query->the_post();
                    $post_id   = get_the_ID();
                    $category  = '';
                    $cats      = get_the_category( $post_id );
                    if ( ! empty( $cats ) && ! is_wp_error( $cats ) ) {
                        $category = $cats[0]->name;
                    }

                    $thumb_url = get_the_post_thumbnail_url( $post_id, 'thumbnail' );

                    $items[] = [
                        'title'     => get_the_title(),
                        'link'      => [ 'url' => get_permalink() ],
                        'badge'     => $category,
                        'date'      => human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) . ' ' . esc_html__( 'ago', 'ultraaddons-elementor-lite' ),
                        'thumb_url' => $thumb_url ? $thumb_url : '',
                    ];
                }
                wp_reset_postdata();
            }
        } else {
            // Custom Items
            $repeater_items = ! empty( $settings['ticker_items'] ) ? $settings['ticker_items'] : [];
            foreach ( $repeater_items as $r_item ) {
                $thumb_url = ! empty( $r_item['custom_image']['url'] ) ? $r_item['custom_image']['url'] : '';
                $items[]   = [
                    'title'     => ! empty( $r_item['title'] ) ? $r_item['title'] : '',
                    'link'      => ! empty( $r_item['link'] ) ? $r_item['link'] : [ 'url' => '#' ],
                    'badge'     => ! empty( $r_item['badge'] ) ? $r_item['badge'] : '',
                    'date'      => '',
                    'thumb_url' => $thumb_url,
                ];
            }
        }

        return $items;
    }

    /**
     * Render widget output on frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $items    = $this->get_ticker_items( $settings );

        if ( empty( $items ) ) {
            return;
        }

        $mode         = ! empty( $settings['ticker_mode'] ) ? $settings['ticker_mode'] : 'marquee';
        $direction    = ! empty( $settings['marquee_direction'] ) ? $settings['marquee_direction'] : 'left';
        $slide_dir    = ! empty( $settings['slide_direction'] ) ? $settings['slide_direction'] : 'right_to_left';
        $vertical_dir = ! empty( $settings['vertical_direction'] ) ? $settings['vertical_direction'] : 'up';
        $pos          = ! empty( $settings['heading_position'] ) ? $settings['heading_position'] : 'left';
        $nav_pos      = ! empty( $settings['nav_position'] ) ? $settings['nav_position'] : 'right';

        $this->add_render_attribute( 'wrapper', 'class', [
            'ua-content-ticker-wrap',
            'ua-ticker-mode-' . esc_attr( $mode ),
            'ua-ticker-pos-' . esc_attr( $pos ),
            'ua-ticker-nav-pos-' . esc_attr( $nav_pos ),
            'ua-ticker-slide-dir-' . esc_attr( $slide_dir ),
            'ua-ticker-vert-dir-' . esc_attr( $vertical_dir ),
        ] );

        $this->add_render_attribute( 'wrapper', 'data-mode', esc_attr( $mode ) );
        $this->add_render_attribute( 'wrapper', 'data-direction', esc_attr( $direction ) );
        $this->add_render_attribute( 'wrapper', 'data-nav-position', esc_attr( $nav_pos ) );
        $this->add_render_attribute( 'wrapper', 'data-slide-direction', esc_attr( $slide_dir ) );
        $this->add_render_attribute( 'wrapper', 'data-vertical-direction', esc_attr( $vertical_dir ) );
        $this->add_render_attribute( 'wrapper', 'data-speed', esc_attr( ! empty( $settings['marquee_speed'] ) ? $settings['marquee_speed'] : 28 ) );
        $this->add_render_attribute( 'wrapper', 'data-typing-speed', esc_attr( ! empty( $settings['typing_speed'] ) ? $settings['typing_speed'] : 50 ) );
        $this->add_render_attribute( 'wrapper', 'data-autoplay-delay', esc_attr( ! empty( $settings['autoplay_delay'] ) ? $settings['autoplay_delay'] : 3.5 ) );
        $this->add_render_attribute( 'wrapper', 'data-pause-hover', esc_attr( ! empty( $settings['pause_on_hover'] ) ? $settings['pause_on_hover'] : 'yes' ) );
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <div class="ua-content-ticker">
                
                <?php
                // Heading Badge (if Position is Left)
                if ( 'yes' === $settings['show_heading'] && 'left' === $pos ) {
                    $this->render_heading( $settings );
                }
                ?>

                <!-- Ticker Track / Carousel -->
                <?php
                $is_swiper_mode = in_array( $mode, [ 'horizontal', 'vertical', 'fade' ], true );
                $track_wrap_cls = $is_swiper_mode ? 'ua-ticker-track-wrap swiper ua-ticker-swiper' : 'ua-ticker-track-wrap';
                $track_cls      = $is_swiper_mode ? 'ua-ticker-track swiper-wrapper' : 'ua-ticker-track';
                ?>
                <div class="<?php echo esc_attr( $track_wrap_cls ); ?>">
                    <div class="<?php echo esc_attr( $track_cls ); ?>">
                        <?php foreach ( $items as $idx => $item ) : ?>
                            <?php
                            $item_key = 'item_' . $idx;
                            $this->add_render_attribute( $item_key, 'class', 'ua-ticker-item' );
                            if ( $is_swiper_mode ) {
                                $this->add_render_attribute( $item_key, 'class', 'swiper-slide' );
                            }
                            if ( 0 === $idx ) {
                                $this->add_render_attribute( $item_key, 'class', 'ua-ticker-item-active' );
                            }
                            ?>
                            <div <?php echo $this->get_render_attribute_string( $item_key ); ?>>
                                
                                <?php if ( 'yes' === $settings['show_thumb'] && ! empty( $item['thumb_url'] ) ) : ?>
                                    <span class="ua-ticker-item-thumb">
                                        <img src="<?php echo esc_url( $item['thumb_url'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>">
                                    </span>
                                <?php endif; ?>

                                <?php if ( 'yes' === $settings['show_badge'] && ! empty( $item['badge'] ) ) : ?>
                                    <span class="ua-ticker-item-badge"><?php echo esc_html( $item['badge'] ); ?></span>
                                <?php endif; ?>

                                <span class="ua-ticker-item-title">
                                    <?php if ( ! empty( $item['link']['url'] ) && '#' !== $item['link']['url'] ) : ?>
                                        <?php
                                        $link_key = 'link_' . $idx;
                                        $this->add_link_attributes( $link_key, $item['link'] );
                                        ?>
                                        <a <?php echo $this->get_render_attribute_string( $link_key ); ?>>
                                            <?php echo esc_html( $item['title'] ); ?>
                                        </a>
                                    <?php else : ?>
                                        <span><?php echo esc_html( $item['title'] ); ?></span>
                                    <?php endif; ?>
                                </span>

                                <?php if ( 'yes' === $settings['show_date'] && ! empty( $item['date'] ) ) : ?>
                                    <span class="ua-ticker-item-date"><?php echo esc_html( $item['date'] ); ?></span>
                                <?php endif; ?>

                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php
                // Heading Badge (if Position is Right)
                if ( 'yes' === $settings['show_heading'] && 'right' === $pos ) {
                    $this->render_heading( $settings );
                }
                ?>

                <?php
                // Navigation Arrows
                if ( 'yes' === $settings['show_nav'] ) :
                    ?>
                    <div class="ua-ticker-nav">
                        <button type="button" class="ua-ticker-nav-btn ua-ticker-prev" aria-label="<?php esc_attr_e( 'Previous', 'ultraaddons-elementor-lite' ); ?>">
                            <i class="fas fa-chevron-left" aria-hidden="true"></i>
                        </button>
                        <button type="button" class="ua-ticker-nav-btn ua-ticker-next" aria-label="<?php esc_attr_e( 'Next', 'ultraaddons-elementor-lite' ); ?>">
                            <i class="fas fa-chevron-right" aria-hidden="true"></i>
                        </button>
                    </div>
                <?php endif; ?>

            </div>
        </div>
        <?php
    }

    /**
     * Render Heading Badge with Indicator and Triangle Arrow.
     */
    protected function render_heading( $settings ) {
        $has_triangle = 'yes' === $settings['heading_triangle'];
        $pos          = ! empty( $settings['heading_position'] ) ? $settings['heading_position'] : 'left';
        ?>
        <div class="ua-ticker-badge-wrap">
            <div class="ua-ticker-badge">
                
                <?php if ( 'pulse' === $settings['heading_icon_type'] ) : ?>
                    <span class="ua-ticker-pulse" aria-hidden="true">
                        <span class="ua-ticker-pulse-dot"></span>
                        <span class="ua-ticker-pulse-ring"></span>
                    </span>
                <?php elseif ( 'icon' === $settings['heading_icon_type'] && ! empty( $settings['heading_icon']['value'] ) ) : ?>
                    <span class="ua-ticker-badge-icon" aria-hidden="true">
                        <?php Icons_Manager::render_icon( $settings['heading_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                    </span>
                <?php endif; ?>

                <span class="ua-ticker-badge-text">
                    <?php if ( ! empty( $settings['heading_link']['url'] ) ) : ?>
                        <a href="<?php echo esc_url( $settings['heading_link']['url'] ); ?>">
                            <?php echo esc_html( $settings['heading_text'] ); ?>
                        </a>
                    <?php else : ?>
                        <?php echo esc_html( $settings['heading_text'] ); ?>
                    <?php endif; ?>
                </span>

            </div>

            <?php if ( $has_triangle ) : ?>
                <span class="ua-ticker-triangle <?php echo 'left' === $pos ? 'ua-ticker-triangle-left' : 'ua-ticker-triangle-right'; ?>" aria-hidden="true"></span>
            <?php endif; ?>
        </div>
        <?php
    }
}
