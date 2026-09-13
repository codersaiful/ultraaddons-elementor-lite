<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * UltraAddons - Timeline (Story & Post Timeline) Widget
 *
 * A high-performance, modern timeline widget supporting both Custom Story / Roadmap
 * milestones and Dynamic WordPress Posts & Custom Post Types.
 * Features 4 versatile layouts (Zig-Zag Centered, Line Left, Line Right, Horizontal Carousel),
 * scroll-driven progress line fill, modern card styling, and AJAX pagination.
 *
 * @package UltraAddons
 * @since 1.1.0
 */
class Timeline extends Base {

    /**
     * Constructor.
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        $css_file = ULTRA_ADDONS_DIR . 'assets/css/widgets/timeline.css';
        $css_ver  = file_exists( $css_file ) ? filemtime( $css_file ) : ULTRA_ADDONS_VERSION;

        $js_file  = ULTRA_ADDONS_DIR . 'assets/js/widgets/timeline.js';
        $js_ver   = file_exists( $js_file ) ? filemtime( $js_file ) : ULTRA_ADDONS_VERSION;

        wp_register_style(
            'ultraaddons-timeline',
            ULTRA_ADDONS_ASSETS . 'css/widgets/timeline.css',
            [ 'e-swiper' ],
            $css_ver,
            'all'
        );

        wp_register_script(
            'ultraaddons-timeline',
            ULTRA_ADDONS_ASSETS . 'js/widgets/timeline.js',
            [ 'jquery', 'swiper' ],
            $js_ver,
            true
        );

        wp_localize_script(
            'ultraaddons-timeline',
            'uaTimelineData',
            [
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'ua-timeline-nonce' ),
            ]
        );

        add_action( 'wp_ajax_ua_timeline_load_posts', [ __CLASS__, 'ajax_load_posts' ] );
        add_action( 'wp_ajax_nopriv_ua_timeline_load_posts', [ __CLASS__, 'ajax_load_posts' ] );
    }

    /**
     * Widget Name.
     */
    public function get_name() {
        return 'ultraaddons-timeline';
    }

    /**
     * Widget Title.
     */
    public function get_title() {
        return esc_html__( 'Timeline', 'ultraaddons-elementor-lite' );
    }

    /**
     * Widget Icon.
     */
    public function get_icon() {
        return 'ultraaddons eicon-time-line';
    }

    /**
     * Widget Keywords.
     */
    public function get_keywords() {
        return [ 'ultraaddons', 'timeline', 'post timeline', 'story', 'history', 'roadmap', 'milestone', 'blog', 'news', 'carousel' ];
    }

    /**
     * Script Dependencies.
     */
    public function get_script_depends() {
        return [ 'jquery', 'swiper', 'ultraaddons-timeline' ];
    }

    /**
     * Style Dependencies.
     */
    public function get_style_depends() {
        return [ 'e-swiper', 'ultraaddons-timeline' ];
    }

    /**
     * Helper to get public post types.
     */
    protected function get_post_types() {
        $post_types = get_post_types( [ 'public' => true ], 'objects' );
        $options    = [];

        foreach ( $post_types as $slug => $pt ) {
            if ( in_array( $slug, [ 'attachment', 'elementor_library' ], true ) ) {
                continue;
            }
            $options[ $slug ] = $pt->labels->singular_name ?: $pt->label;
        }

        return $options;
    }

    /**
     * Helper to get categories/taxonomies.
     */
    protected function get_all_categories() {
        $categories = get_categories( [ 'hide_empty' => false ] );
        $options    = [];

        if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
            foreach ( $categories as $cat ) {
                $options[ $cat->term_id ] = $cat->name;
            }
        }

        return $options;
    }

    /**
     * Helper to get authors.
     */
    protected function get_authors() {
        $authors = get_users( [ 'capability' => [ 'edit_posts' ] ] );
        $options = [];

        if ( ! empty( $authors ) && ! is_wp_error( $authors ) ) {
            foreach ( $authors as $user ) {
                $options[ $user->ID ] = $user->display_name;
            }
        }

        return $options;
    }

    /**
     * Helper to get tags.
     */
    protected function get_all_tags() {
        $tags    = get_tags( [ 'hide_empty' => false ] );
        $options = [];

        if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) {
            foreach ( $tags as $tag ) {
                $options[ $tag->term_id ] = $tag->name;
            }
        }

        return $options;
    }

    /**
     * Register Widget Controls.
     */
    protected function register_controls() {
        $this->register_content_layout_controls();
        $this->register_content_items_controls();
        $this->register_content_query_controls();
        $this->register_content_elements_controls();
        $this->register_content_pagination_controls();

        $this->register_style_line_controls();
        $this->register_style_marker_controls();
        $this->register_style_badge_controls();
        $this->register_style_group_divider_controls();
        $this->register_style_card_controls();
        $this->register_style_media_controls();
        $this->register_style_typography_controls();
        $this->register_style_button_controls();
        $this->register_style_carousel_controls();
        $this->register_style_pagination_controls();
    }

    /**
     * Content Tab: Layout Section.
     */
    protected function register_content_layout_controls() {
        $this->start_controls_section(
            'section_layout',
            [
                'label' => esc_html__( 'Layout', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'timeline_content',
            [
                'label'       => esc_html__( 'Timeline Content', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'dynamic',
                'options'     => [
                    'dynamic' => esc_html__( 'Dynamic', 'ultraaddons-elementor-lite' ),
                    'custom'  => esc_html__( 'Custom', 'ultraaddons-elementor-lite' ),
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'timeline_layout',
            [
                'label'       => esc_html__( 'Layout', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'centered',
                'options'     => [
                    'centered'       => esc_html__( 'Zig-Zag', 'ultraaddons-elementor-lite' ),
                    'one-sided-left' => esc_html__( 'Line Left', 'ultraaddons-elementor-lite' ),
                    'one-sided-right'=> esc_html__( 'Line Right', 'ultraaddons-elementor-lite' ),
                    'horizontal'     => esc_html__( 'Horizontal Carousel', 'ultraaddons-elementor-lite' ),
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'media_position',
            [
                'label'   => esc_html__( 'Media Position', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'top',
                'options' => [
                    'top'    => esc_html__( 'Top', 'ultraaddons-elementor-lite' ),
                    'bottom' => esc_html__( 'Bottom', 'ultraaddons-elementor-lite' ),
                    'none'   => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'      => 'media_image_size',
                'default'   => 'full',
                'separator' => 'none',
                'condition' => [
                    'media_position!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'date_format',
            [
                'label'     => esc_html__( 'Date Format', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'F j, Y',
                'options'   => [
                    'F j, Y' => date_i18n( 'F j, Y' ),
                    'Y-m-d'  => date_i18n( 'Y-m-d' ),
                    'M j, Y' => date_i18n( 'M j, Y' ),
                    'd/m/Y'  => date_i18n( 'd/m/Y' ),
                    'm/d/Y'  => date_i18n( 'm/d/Y' ),
                ],
                'condition' => [
                    'timeline_content' => 'dynamic',
                ],
            ]
        );

        $this->add_control(
            'timeline_fill',
            [
                'label'        => esc_html__( 'Main Line Fill', 'ultraaddons-elementor-lite' ),
                'description'  => esc_html__( 'Fills the main line dynamically as the visitor scrolls down.', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'timeline_layout!' => 'horizontal',
                ],
            ]
        );

        $this->add_control(
            'posts_icon',
            [
                'label'       => esc_html__( 'Main Line Icon', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::ICONS,
                'default'     => [
                    'value'   => 'eicon-time-line',
                    'library' => 'elementor',
                ],
            ]
        );

        $this->add_control(
            'show_badge',
            [
                'label'        => esc_html__( 'Show Extra Label', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'extra_label_source',
            [
                'label'     => esc_html__( 'Extra Label Source', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'publish_date',
                'options'   => [
                    'publish_date' => esc_html__( 'Publish Date', 'ultraaddons-elementor-lite' ),
                    'custom_field' => esc_html__( 'Custom Meta Field', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'show_badge'       => 'yes',
                    'timeline_content' => 'dynamic',
                ],
            ]
        );

        $this->add_control(
            'extra_label_meta_key',
            [
                'label'       => esc_html__( 'Meta Field Key', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => 'e.g. event_date',
                'condition'   => [
                    'show_badge'         => 'yes',
                    'timeline_content'   => 'dynamic',
                    'extra_label_source' => 'custom_field',
                ],
            ]
        );

        $this->add_control(
            'carousel_loop',
            [
                'label'        => esc_html__( 'Loop', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'carousel_autoplay',
            [
                'label'        => esc_html__( 'Autoplay', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'entrance_animation',
            [
                'label'     => esc_html__( 'Entrance Animation', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'fade',
                'separator' => 'before',
                'options'   => [
                    'none'       => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                    'fade'       => esc_html__( 'Fade', 'ultraaddons-elementor-lite' ),
                    'zoom-in'    => esc_html__( 'Zoom In', 'ultraaddons-elementor-lite' ),
                    'zoom-out'   => esc_html__( 'Zoom Out', 'ultraaddons-elementor-lite' ),
                    'slide-up'   => esc_html__( 'Slide Up', 'ultraaddons-elementor-lite' ),
                    'slide-down' => esc_html__( 'Slide Down', 'ultraaddons-elementor-lite' ),
                    'flip-down'  => esc_html__( 'Flip Down', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'animation_offset',
            [
                'label'     => esc_html__( 'Animation Offset', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 150,
                'min'       => 0,
                'max'       => 600,
                'condition' => [
                    'entrance_animation!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'animation_duration',
            [
                'label'     => esc_html__( 'Animation Duration', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 600,
                'min'       => 100,
                'max'       => 3000,
                'step'      => 50,
                'condition' => [
                    'entrance_animation!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'show_pagination',
            [
                'label'        => esc_html__( 'Show Pagination', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before',
                'condition'    => [
                    'timeline_content' => 'dynamic',
                ],
            ]
        );

        // Additional Carousel-Only Controls
        $this->add_control(
            'carousel_line_position',
            [
                'label'     => esc_html__( 'Carousel Line Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'line-bottom',
                'options'   => [
                    'line-bottom' => esc_html__( 'Line Bottom', 'ultraaddons-elementor-lite' ),
                    'line-top'    => esc_html__( 'Line Top', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'timeline_layout' => 'horizontal',
                ],
            ]
        );

        $this->add_responsive_control(
            'slides_to_show',
            [
                'label'          => esc_html__( 'Slides to Show', 'ultraaddons-elementor-lite' ),
                'type'           => Controls_Manager::NUMBER,
                'min'            => 1,
                'max'            => 10,
                'default'        => 3,
                'tablet_default' => 2,
                'mobile_default' => 1,
                'condition'      => [
                    'timeline_layout' => 'horizontal',
                ],
            ]
        );

        $this->add_responsive_control(
            'slides_gutter',
            [
                'label'     => esc_html__( 'Slides Gap (px)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [ 'min' => 0, 'max' => 60 ],
                ],
                'default'   => [
                    'unit' => 'px',
                    'size' => 24,
                ],
                'condition' => [
                    'timeline_layout' => 'horizontal',
                ],
            ]
        );

        $this->add_control(
            'carousel_autoplay_speed',
            [
                'label'     => esc_html__( 'Autoplay Speed (ms)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 1000,
                'max'       => 10000,
                'step'      => 500,
                'default'   => 3500,
                'condition' => [
                    'timeline_layout'   => 'horizontal',
                    'carousel_autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'carousel_speed',
            [
                'label'     => esc_html__( 'Transition Speed (ms)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 200,
                'max'       => 3000,
                'step'      => 100,
                'default'   => 600,
                'condition' => [
                    'timeline_layout' => 'horizontal',
                ],
            ]
        );

        $this->add_control(
            'carousel_pause_on_hover',
            [
                'label'        => esc_html__( 'Pause on Hover', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'timeline_layout'   => 'horizontal',
                    'carousel_autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'carousel_arrows',
            [
                'label'        => esc_html__( 'Navigation Arrows', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'timeline_layout' => 'horizontal',
                ],
            ]
        );

        $this->add_control(
            'carousel_pagination',
            [
                'label'     => esc_html__( 'Pagination Dots', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'dots',
                'options'   => [
                    'none'        => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                    'dots'        => esc_html__( 'Bullets / Dots', 'ultraaddons-elementor-lite' ),
                    'progressbar' => esc_html__( 'Progress Bar', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'timeline_layout' => 'horizontal',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content Tab: Custom Repeater Items.
     */
    protected function register_content_items_controls() {
        $this->start_controls_section(
            'section_timeline_items',
            [
                'label'     => esc_html__( 'Timeline Items', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'timeline_content' => 'custom',
                ],
            ]
        );

        $repeater = new Repeater();

        $repeater->start_controls_tabs( 'item_tabs' );

        // Tab Content
        $repeater->start_controls_tab(
            'tab_item_content',
            [ 'label' => esc_html__( 'Content', 'ultraaddons-elementor-lite' ) ]
        );

        $repeater->add_control(
            'item_group_divider',
            [
                'label'       => esc_html__( 'Center Line Year / Group Divider', 'ultraaddons-elementor-lite' ),
                'description' => esc_html__( 'Displays a central divider pill (e.g. 2022, 2023) directly on the center line above this item.', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'placeholder' => 'e.g. 2022 or Milestone Phase',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'item_badge',
            [
                'label'       => esc_html__( 'Milestone / Year Badge', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '2024',
                'placeholder' => 'e.g. 2024 or Milestone 1',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'item_tag',
            [
                'label'       => esc_html__( 'Sub-tag / Category', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'Milestone',
                'placeholder' => 'e.g. Q1 Launch',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'item_date',
            [
                'label'       => esc_html__( 'Date / Period', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'January 2024',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'item_icon',
            [
                'label'   => esc_html__( 'Marker Node Icon', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fas fa-flag',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $repeater->add_control(
            'item_title',
            [
                'label'       => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'Timeline Milestone',
                'label_block' => true,
                'separator'   => 'before',
            ]
        );

        $repeater->add_control(
            'item_link',
            [
                'label'         => esc_html__( 'Title / Card Link', 'ultraaddons-elementor-lite' ),
                'type'          => Controls_Manager::URL,
                'placeholder'   => esc_html__( 'https://your-link.com', 'ultraaddons-elementor-lite' ),
                'show_external' => true,
                'default'       => [ 'url' => '' ],
            ]
        );

        $repeater->add_control(
            'item_description',
            [
                'label'   => esc_html__( 'Description', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::WYSIWYG,
                'default' => esc_html__( 'Write your story, milestone details, or achievement description here.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $repeater->add_control(
            'item_media_type',
            [
                'label'     => esc_html__( 'Media Type', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'image',
                'options'   => [
                    'none'      => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                    'image'     => esc_html__( 'Image', 'ultraaddons-elementor-lite' ),
                    'icon'      => esc_html__( 'Media Icon', 'ultraaddons-elementor-lite' ),
                    'video_url' => esc_html__( 'Video (YouTube / Vimeo)', 'ultraaddons-elementor-lite' ),
                ],
                'separator' => 'before',
            ]
        );

        $repeater->add_control(
            'item_image',
            [
                'label'     => esc_html__( 'Choose Image', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::MEDIA,
                'default'   => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'item_media_type' => 'image',
                ],
            ]
        );

        $repeater->add_control(
            'item_media_icon',
            [
                'label'     => esc_html__( 'Media Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'fas fa-rocket',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'item_media_type' => 'icon',
                ],
            ]
        );

        $repeater->add_control(
            'item_video_url',
            [
                'label'       => esc_html__( 'Video Embed URL', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => 'https://www.youtube.com/watch?v=...',
                'condition'   => [
                    'item_media_type' => 'video_url',
                ],
            ]
        );

        $repeater->add_control(
            'item_btn_text',
            [
                'label'       => esc_html__( 'Button Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'placeholder' => esc_html__( 'e.g. Read More', 'ultraaddons-elementor-lite' ),
                'separator'   => 'before',
            ]
        );

        $repeater->add_control(
            'item_btn_url',
            [
                'label'         => esc_html__( 'Button URL', 'ultraaddons-elementor-lite' ),
                'type'          => Controls_Manager::URL,
                'placeholder'   => esc_html__( 'https://your-link.com', 'ultraaddons-elementor-lite' ),
                'show_external' => true,
                'condition'     => [
                    'item_btn_text!' => '',
                ],
            ]
        );

        $repeater->end_controls_tab();

        // Tab Custom Styling
        $repeater->start_controls_tab(
            'tab_item_style',
            [ 'label' => esc_html__( 'Style', 'ultraaddons-elementor-lite' ) ]
        );

        $repeater->add_control(
            'custom_colors',
            [
                'label'        => esc_html__( 'Override Colors for this Item', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $repeater->add_control(
            'custom_marker_color',
            [
                'label'     => esc_html__( 'Marker Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .ua-timeline-marker' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
                    '{{WRAPPER}} {{CURRENT_ITEM}} .ua-timeline-marker-icon' => 'color: #ffffff;',
                ],
                'condition' => [
                    'custom_colors' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'custom_badge_color',
            [
                'label'     => esc_html__( 'Badge Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .ua-timeline-badge' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'custom_colors' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'custom_card_bg',
            [
                'label'     => esc_html__( 'Card Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .ua-timeline-card' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} {{CURRENT_ITEM}} .ua-timeline-arrow' => 'border-right-color: {{VALUE}}; border-left-color: {{VALUE}}; border-top-color: {{VALUE}}; border-bottom-color: {{VALUE}};',
                ],
                'condition' => [
                    'custom_colors' => 'yes',
                ],
            ]
        );

        $repeater->end_controls_tab();

        $repeater->end_controls_tabs();

        $this->add_control(
            'timeline_items',
            [
                'label'       => esc_html__( 'Milestone Items', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ item_badge }}} - {{{ item_title }}}',
                'default'     => [
                    [
                        'item_badge'       => '2022',
                        'item_tag'         => 'Foundation',
                        'item_date'        => 'January 2022',
                        'item_title'       => 'Company Founded',
                        'item_description' => 'Started our journey with a small team and big vision to build transformative web tools.',
                        'item_icon'        => [ 'value' => 'fas fa-flag', 'library' => 'fa-solid' ],
                        'item_media_type'  => 'image',
                        'item_image'       => [ 'url' => Utils::get_placeholder_image_src() ],
                        'item_btn_text'    => '',
                    ],
                    [
                        'item_badge'       => '2023',
                        'item_tag'         => 'Milestone',
                        'item_date'        => 'June 2023',
                        'item_title'       => '10,000+ Active Users',
                        'item_description' => 'Achieved rapid adoption across the globe with our fast, versatile Elementor widgets suite.',
                        'item_icon'        => [ 'value' => 'fas fa-rocket', 'library' => 'fa-solid' ],
                        'item_media_type'  => 'image',
                        'item_image'       => [ 'url' => Utils::get_placeholder_image_src() ],
                        'item_btn_text'    => '',
                    ],
                    [
                        'item_badge'       => '2024',
                        'item_tag'         => 'Innovation',
                        'item_date'        => 'March 2024',
                        'item_title'       => 'Platform 2.0 Release',
                        'item_description' => 'Re-architected all components for ultra-fast rendering, zero bloat, and modern design standards.',
                        'item_icon'        => [ 'value' => 'fas fa-trophy', 'library' => 'fa-solid' ],
                        'item_media_type'  => 'image',
                        'item_image'       => [ 'url' => Utils::get_placeholder_image_src() ],
                        'item_btn_text'    => '',
                    ],
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content Tab: Dynamic Query Section.
     */
    protected function register_content_query_controls() {
        $this->start_controls_section(
            'section_query',
            [
                'label'     => esc_html__( 'Query', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'timeline_content' => 'dynamic',
                ],
            ]
        );

        $this->add_control(
            'query_post_type',
            [
                'label'   => esc_html__( 'Post Type', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'post',
                'options' => $this->get_post_types(),
            ]
        );

        $this->add_control(
            'query_selection',
            [
                'label'   => esc_html__( 'Selection', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'dynamic',
                'options' => [
                    'dynamic' => esc_html__( 'Dynamic', 'ultraaddons-elementor-lite' ),
                    'manual'  => esc_html__( 'Manual', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'query_manual_ids',
            [
                'label'       => esc_html__( 'Select Posts', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'e.g. 12, 45, 99', 'ultraaddons-elementor-lite' ),
                'description' => esc_html__( 'Enter comma-separated post IDs.', 'ultraaddons-elementor-lite' ),
                'label_block' => true,
                'condition'   => [
                    'query_selection' => 'manual',
                ],
            ]
        );

        $this->add_control(
            'query_authors',
            [
                'label'       => esc_html__( 'Authors', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT2,
                'multiple'    => true,
                'options'     => $this->get_authors(),
                'label_block' => true,
                'condition'   => [
                    'query_selection' => 'dynamic',
                ],
            ]
        );

        $this->add_control(
            'query_categories',
            [
                'label'       => esc_html__( 'Categories', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT2,
                'multiple'    => true,
                'options'     => $this->get_all_categories(),
                'label_block' => true,
                'condition'   => [
                    'query_selection' => 'dynamic',
                    'query_post_type' => 'post',
                ],
            ]
        );

        $this->add_control(
            'query_tags',
            [
                'label'       => esc_html__( 'Tags', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT2,
                'multiple'    => true,
                'options'     => $this->get_all_tags(),
                'label_block' => true,
                'condition'   => [
                    'query_selection' => 'dynamic',
                    'query_post_type' => 'post',
                ],
            ]
        );

        $this->add_control(
            'query_exclude_ids',
            [
                'label'       => esc_html__( 'Exclude Posts', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'e.g. 12, 45, 89', 'ultraaddons-elementor-lite' ),
                'description' => esc_html__( 'Enter comma-separated post IDs to exclude.', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'query_selection' => 'dynamic',
                ],
            ]
        );

        $this->add_control(
            'query_posts_per_page',
            [
                'label'   => esc_html__( 'Posts Per Page', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 3,
                'min'     => 1,
                'max'     => 100,
            ]
        );

        $this->add_control(
            'query_orderby',
            [
                'label'   => esc_html__( 'Order By', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'date'          => esc_html__( 'Date', 'ultraaddons-elementor-lite' ),
                    'title'         => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                    'ID'            => esc_html__( 'ID', 'ultraaddons-elementor-lite' ),
                    'rand'          => esc_html__( 'Random', 'ultraaddons-elementor-lite' ),
                    'menu_order'    => esc_html__( 'Menu Order', 'ultraaddons-elementor-lite' ),
                    'modified'      => esc_html__( 'Last Modified', 'ultraaddons-elementor-lite' ),
                    'comment_count' => esc_html__( 'Comments Count', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'query_order',
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

        $this->add_control(
            'query_no_posts_text',
            [
                'label'       => esc_html__( 'Not Found Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'No Posts Found!', 'ultraaddons-elementor-lite' ),
                'label_block' => true,
                'dynamic'     => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'query_exclude_no_thumb',
            [
                'label'        => esc_html__( 'Exclude Items without Thumbnail', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content Tab: Content Elements & Alignment Section.
     */
    protected function register_content_elements_controls() {
        $this->start_controls_section(
            'section_elements',
            [
                'label' => esc_html__( 'Content', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'content_align',
            [
                'label'     => esc_html__( 'Content Align', 'ultraaddons-elementor-lite' ),
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
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-card-inner' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_align_right',
            [
                'label'     => esc_html__( 'Content Align (Right)', 'ultraaddons-elementor-lite' ),
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
                    'timeline_layout' => 'centered',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-item.is-right .ua-timeline-card-inner' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'show_image_overlay',
            [
                'label'        => esc_html__( 'Show Image Overlay', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label'        => esc_html__( 'Show Title', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label'     => esc_html__( 'Title HTML Tag', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'span',
                'options'   => [
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
                'condition' => [
                    'show_title' => 'yes',
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
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'date_source',
            [
                'label'     => esc_html__( 'Date Source', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'publish_date',
                'options'   => [
                    'publish_date'  => esc_html__( 'Publish Date', 'ultraaddons-elementor-lite' ),
                    'modified_date' => esc_html__( 'Modified Date', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'show_date'        => 'yes',
                    'timeline_content' => 'dynamic',
                ],
            ]
        );

        $this->add_control(
            'show_description',
            [
                'label'        => esc_html__( 'Show Description', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'excerpt_length',
            [
                'label'     => esc_html__( 'Excerpt Count', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 20,
                'min'       => 1,
                'max'       => 100,
                'condition' => [
                    'timeline_content' => 'dynamic',
                    'show_description' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_read_more',
            [
                'label'        => esc_html__( 'Show Read More', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'read_more_text',
            [
                'label'       => esc_html__( 'Read More Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Read More', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'show_read_more' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_pointer_arrow',
            [
                'label'        => esc_html__( 'Show Pointer Arrow', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
                'separator'    => 'before',
                'condition'    => [
                    'timeline_layout!' => 'horizontal',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content Tab: Dynamic Pagination Section.
     */
    protected function register_content_pagination_controls() {
        $this->start_controls_section(
            'section_pagination',
            [
                'label'     => esc_html__( 'Pagination', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'timeline_content' => 'dynamic',
                    'show_pagination'  => 'yes',
                    'timeline_layout!' => 'horizontal',
                ],
            ]
        );

        $this->add_control(
            'pagination_type',
            [
                'label'   => esc_html__( 'Pagination Mode', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'load_more',
                'options' => [
                    'load_more'       => esc_html__( 'Load More Button', 'ultraaddons-elementor-lite' ),
                    'infinite_scroll' => esc_html__( 'Infinite Scroll', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'load_more_btn_text',
            [
                'label'     => esc_html__( 'Button Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Load More', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'pagination_type' => 'load_more',
                ],
            ]
        );

        $this->add_control(
            'pagination_loading_text',
            [
                'label'     => esc_html__( 'Loading State Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Loading...', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'pagination_no_more_text',
            [
                'label'     => esc_html__( 'End of Timeline Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'No More Posts', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab: Main Line.
     */
    protected function register_style_line_controls() {
        $this->start_controls_section(
            'section_style_line',
            [
                'label' => esc_html__( 'Timeline Line', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'line_width',
            [
                'label'     => esc_html__( 'Line Width (Thickness)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [ 'min' => 1, 'max' => 20 ],
                ],
                'default'   => [
                    'unit' => 'px',
                    'size' => 3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-line' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-timeline-fill' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-timeline-horizontal-line' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'line_color',
            [
                'label'     => esc_html__( 'Line Base Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#e2e8f0',
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-line' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-timeline-horizontal-line' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'line_fill_color',
            [
                'label'     => esc_html__( 'Scroll Progress Fill Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0274be',
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-fill' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-timeline-horizontal-fill' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_distance_from_line',
            [
                'label'          => esc_html__( 'Card Distance from Line', 'ultraaddons-elementor-lite' ),
                'type'           => Controls_Manager::SLIDER,
                'range'          => [
                    'px' => [ 'min' => 10, 'max' => 100 ],
                ],
                'default'        => [
                    'unit' => 'px',
                    'size' => 36,
                ],
                'tablet_default' => [
                    'unit' => 'px',
                    'size' => 24,
                ],
                'mobile_default' => [
                    'unit' => 'px',
                    'size' => 16,
                ],
                'selectors'      => [
                    '{{WRAPPER}}' => '--ua-timeline-distance: {{SIZE}}{{UNIT}};',
                ],
                'condition'      => [
                    'timeline_layout!' => 'horizontal',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab: Marker / Node.
     */
    protected function register_style_marker_controls() {
        $this->start_controls_section(
            'section_style_marker',
            [
                'label' => esc_html__( 'Marker Node / Icon', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'marker_size',
            [
                'label'     => esc_html__( 'Marker Circle Size', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [ 'min' => 20, 'max' => 80 ],
                ],
                'default'   => [
                    'unit' => 'px',
                    'size' => 42,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-marker' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'marker_icon_size',
            [
                'label'     => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [ 'min' => 10, 'max' => 40 ],
                ],
                'default'   => [
                    'unit' => 'px',
                    'size' => 18,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-marker-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-timeline-marker-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'marker_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-marker' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'marker_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0274be',
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-marker-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-timeline-marker-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'marker_active_bg_color',
            [
                'label'     => esc_html__( 'Active / Reached Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0274be',
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-item.is-active .ua-timeline-marker' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'marker_active_icon_color',
            [
                'label'     => esc_html__( 'Active / Reached Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-item.is-active .ua-timeline-marker-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-timeline-item.is-active .ua-timeline-marker-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'marker_border',
                'selector' => '{{WRAPPER}} .ua-timeline-marker',
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'top'    => 3,
                            'right'  => 3,
                            'bottom' => 3,
                            'left'   => 3,
                            'unit'   => 'px',
                        ],
                    ],
                    'color' => [
                        'default' => '#0274be',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'marker_border_radius',
            [
                'label'     => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [ 'min' => 0, 'max' => 50 ],
                    '%'  => [ 'min' => 0, 'max' => 50 ],
                ],
                'default'   => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-marker' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'marker_box_shadow',
                'selector' => '{{WRAPPER}} .ua-timeline-marker',
                'fields_options' => [
                    'box_shadow_type' => [ 'default' => 'yes' ],
                    'box_shadow' => [
                        'default' => [
                            'horizontal' => 0,
                            'vertical'   => 4,
                            'blur'       => 12,
                            'spread'     => 0,
                            'color'      => 'rgba(2, 116, 190, 0.25)',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'marker_pulse',
            [
                'label'        => esc_html__( 'Active Pulse Animation', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab: Year / Milestone Badge.
     */
    protected function register_style_badge_controls() {
        $this->start_controls_section(
            'section_style_badge',
            [
                'label'     => esc_html__( 'Milestone / Year Badge', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_badge' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'badge_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-badge' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0274be',
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-badge' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'badge_typography',
                'selector' => '{{WRAPPER}} .ua-timeline-badge',
            ]
        );

        $this->add_responsive_control(
            'badge_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'top'    => 6,
                    'right'  => 16,
                    'bottom' => 6,
                    'left'   => 16,
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-timeline-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'badge_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 50 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-timeline-badge' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'badge_box_shadow',
                'selector' => '{{WRAPPER}} .ua-timeline-badge',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab: Center Line Year / Group Divider.
     */
    protected function register_style_group_divider_controls() {
        $this->start_controls_section(
            'section_style_group_divider',
            [
                'label'     => esc_html__( 'Center Line Year Divider', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'timeline_layout!' => 'horizontal',
                ],
            ]
        );

        $this->add_control(
            'group_divider_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-group-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'group_divider_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0274be',
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-group-label' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'group_divider_typography',
                'selector' => '{{WRAPPER}} .ua-timeline-group-label',
            ]
        );

        $this->add_responsive_control(
            'group_divider_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'top'    => 6,
                    'right'  => 20,
                    'bottom' => 6,
                    'left'   => 20,
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-timeline-group-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'group_divider_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'    => [
                    'top'    => 50,
                    'right'  => 50,
                    'bottom' => 50,
                    'left'   => 50,
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-timeline-group-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'group_divider_border',
                'selector' => '{{WRAPPER}} .ua-timeline-group-label',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'group_divider_shadow',
                'selector' => '{{WRAPPER}} .ua-timeline-group-label',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab: Card Box.
     */
    protected function register_style_card_controls() {
        $this->start_controls_section(
            'section_style_card',
            [
                'label' => esc_html__( 'Timeline Card Box', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-card' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-timeline-arrow' => 'border-right-color: {{VALUE}}; border-left-color: {{VALUE}}; border-top-color: {{VALUE}}; border-bottom-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'top'    => 24,
                    'right'  => 24,
                    'bottom' => 24,
                    'left'   => 24,
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-timeline-card-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'card_border',
                'selector' => '{{WRAPPER}} .ua-timeline-card',
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'    => [
                    'top'    => 12,
                    'right'  => 12,
                    'bottom' => 12,
                    'left'   => 12,
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-timeline-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'card_box_shadow',
                'selector' => '{{WRAPPER}} .ua-timeline-card',
                'fields_options' => [
                    'box_shadow_type' => [ 'default' => 'yes' ],
                    'box_shadow' => [
                        'default' => [
                            'horizontal' => 0,
                            'vertical'   => 8,
                            'blur'       => 24,
                            'spread'     => -4,
                            'color'      => 'rgba(0, 0, 0, 0.07)',
                        ],
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'card_hover_box_shadow',
                'label'    => esc_html__( 'Hover Box Shadow', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-timeline-card:hover',
                'fields_options' => [
                    'box_shadow_type' => [ 'default' => 'yes' ],
                    'box_shadow' => [
                        'default' => [
                            'horizontal' => 0,
                            'vertical'   => 14,
                            'blur'       => 32,
                            'spread'     => -4,
                            'color'      => 'rgba(2, 116, 190, 0.12)',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label'     => esc_html__( 'Pointer Arrow Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-item.is-right .ua-timeline-arrow' => 'border-right-color: {{VALUE}} !important;',
                    '{{WRAPPER}} .ua-timeline-item.is-left .ua-timeline-arrow'  => 'border-left-color: {{VALUE}} !important;',
                    '{{WRAPPER}} .ua-timeline-carousel-line-top .ua-timeline-arrow' => 'border-bottom-color: {{VALUE}} !important;',
                    '{{WRAPPER}} .ua-timeline-carousel-line-bottom .ua-timeline-arrow' => 'border-top-color: {{VALUE}} !important;',
                ],
                'condition' => [
                    'show_pointer_arrow' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab: Media.
     */
    protected function register_style_media_controls() {
        $this->start_controls_section(
            'section_style_media',
            [
                'label'     => esc_html__( 'Media (Image / Video)', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_media' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'media_height',
            [
                'label'     => esc_html__( 'Image Height', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [ 'min' => 100, 'max' => 500 ],
                ],
                'default'   => [
                    'unit' => 'px',
                    'size' => 200,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-media img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover;',
                    '{{WRAPPER}} .ua-timeline-media iframe' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-timeline-media-icon' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'media_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'    => [
                    'top'    => 8,
                    'right'  => 8,
                    'bottom' => 8,
                    'left'   => 8,
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-timeline-media' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->add_responsive_control(
            'media_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'top'    => 0,
                    'right'  => 0,
                    'bottom' => 16,
                    'left'   => 0,
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-timeline-media' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Overlay Mode Specific Styling
        $this->add_control(
            'overlay_heading',
            [
                'label'     => esc_html__( 'Overlay Mode Settings', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'media_position' => 'overlay',
                ],
            ]
        );

        $this->add_control(
            'overlay_gradient_color',
            [
                'label'     => esc_html__( 'Overlay Gradient Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(15, 23, 42, 0.88)',
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-card.has-media-overlay .ua-timeline-card-inner' => 'background: linear-gradient(to top, {{VALUE}} 0%, rgba(15, 23, 42, 0.45) 60%, transparent 100%);',
                ],
                'condition' => [
                    'media_position' => 'overlay',
                ],
            ]
        );

        $this->add_responsive_control(
            'overlay_card_height',
            [
                'label'     => esc_html__( 'Card Height', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [ 'min' => 200, 'max' => 600 ],
                ],
                'default'   => [
                    'unit' => 'px',
                    'size' => 320,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-card.has-media-overlay' => 'min-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-timeline-card.has-media-overlay .ua-timeline-media' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-timeline-card.has-media-overlay img.ua-timeline-img' => 'height: {{SIZE}}{{UNIT}} !important;',
                ],
                'condition' => [
                    'media_position' => 'overlay',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab: Typography & Colors.
     */
    protected function register_style_typography_controls() {
        $this->start_controls_section(
            'section_style_typography',
            [
                'label' => esc_html__( 'Typography & Colors', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Date / Tag Meta
        $this->add_control(
            'heading_style_meta',
            [
                'label'     => esc_html__( 'Date & Meta Tag', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'condition' => [
                    'show_date' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'meta_color',
            [
                'label'     => esc_html__( 'Meta Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0274be',
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-meta' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_date' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'meta_typography',
                'selector'  => '{{WRAPPER}} .ua-timeline-meta',
                'condition' => [
                    'show_date' => 'yes',
                ],
            ]
        );

        // Title
        $this->add_control(
            'heading_style_title',
            [
                'label'     => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Title Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1e293b',
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-title, {{WRAPPER}} .ua-timeline-title a' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label'     => esc_html__( 'Title Hover Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0274be',
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-title a:hover' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'title_typography',
                'selector'  => '{{WRAPPER}} .ua-timeline-title',
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label'      => esc_html__( 'Title Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'top'    => 0,
                    'right'  => 0,
                    'bottom' => 12,
                    'left'   => 0,
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-timeline-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'show_title' => 'yes',
                ],
            ]
        );

        // Description
        $this->add_control(
            'heading_style_desc',
            [
                'label'     => esc_html__( 'Description / Excerpt', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_excerpt' => 'yes',
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
                    '{{WRAPPER}} .ua-timeline-desc, {{WRAPPER}} .ua-timeline-desc p' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_excerpt' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'desc_typography',
                'selector'  => '{{WRAPPER}} .ua-timeline-desc, {{WRAPPER}} .ua-timeline-desc p',
                'condition' => [
                    'show_excerpt' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'desc_margin',
            [
                'label'      => esc_html__( 'Description Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'top'    => 0,
                    'right'  => 0,
                    'bottom' => 16,
                    'left'   => 0,
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-timeline-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'show_excerpt' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab: Read More Button.
     */
    protected function register_style_button_controls() {
        $this->start_controls_section(
            'section_style_button',
            [
                'label'     => esc_html__( 'Read More Button', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'btn_typography',
                'selector' => '{{WRAPPER}} .ua-timeline-btn',
            ]
        );

        $this->start_controls_tabs( 'tabs_btn_style' );

        $this->start_controls_tab(
            'tab_btn_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'btn_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0274be',
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'btn_border',
                'selector' => '{{WRAPPER}} .ua-timeline-btn',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_btn_hover',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'btn_hover_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#005a96',
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-btn:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'btn_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'top'    => 8,
                    'right'  => 18,
                    'bottom' => 8,
                    'left'   => 18,
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-timeline-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'before',
            ]
        );

        $this->add_responsive_control(
            'btn_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 50 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 6,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-timeline-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'btn_box_shadow',
                'selector' => '{{WRAPPER}} .ua-timeline-btn',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab: Carousel Navigation & Pagination.
     */
    protected function register_style_carousel_controls() {
        $this->start_controls_section(
            'section_style_carousel',
            [
                'label'     => esc_html__( 'Carousel Arrows & Dots', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'timeline_layout' => 'horizontal',
                ],
            ]
        );

        $this->add_control(
            'heading_arrows_style',
            [
                'label'     => esc_html__( 'Navigation Arrows', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'condition' => [
                    'carousel_arrows' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'arrow_color_ctrl',
            [
                'label'     => esc_html__( 'Arrow Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0274be',
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-nav-btn' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'carousel_arrows' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'arrow_bg_color',
            [
                'label'     => esc_html__( 'Arrow Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-nav-btn' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'carousel_arrows' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'arrow_hover_color',
            [
                'label'     => esc_html__( 'Arrow Hover Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-nav-btn:hover' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'carousel_arrows' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'arrow_hover_bg_color',
            [
                'label'     => esc_html__( 'Arrow Hover Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0274be',
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-nav-btn:hover' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'carousel_arrows' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'heading_dots_style',
            [
                'label'     => esc_html__( 'Pagination Dots', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'carousel_pagination!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'dot_color',
            [
                'label'     => esc_html__( 'Dot Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#cbd5e1',
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-pagination .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'carousel_pagination' => 'dots',
                ],
            ]
        );

        $this->add_control(
            'dot_active_color',
            [
                'label'     => esc_html__( 'Active Dot / Progress Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0274be',
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-pagination .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-timeline-pagination.swiper-pagination-progressbar .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'carousel_pagination!' => 'none',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab: Pagination (Load More Button).
     */
    protected function register_style_pagination_controls() {
        $this->start_controls_section(
            'section_style_pagination_btn',
            [
                'label'     => esc_html__( 'Load More Button', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'timeline_content' => 'dynamic',
                    'pagination_type'  => 'load_more',
                    'timeline_layout!' => 'horizontal',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'pagination_btn_typography',
                'selector' => '{{WRAPPER}} .ua-timeline-load-more-btn',
            ]
        );

        $this->start_controls_tabs( 'tabs_pagination_btn' );

        $this->start_controls_tab(
            'tab_p_btn_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'p_btn_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-load-more-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'p_btn_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0274be',
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-load-more-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_p_btn_hover',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'p_btn_hover_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-load-more-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'p_btn_hover_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#005a96',
                'selectors' => [
                    '{{WRAPPER}} .ua-timeline-load-more-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'p_btn_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'top'    => 12,
                    'right'  => 30,
                    'bottom' => 12,
                    'left'   => 30,
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-timeline-load-more-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'before',
            ]
        );

        $this->add_responsive_control(
            'p_btn_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 50 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-timeline-load-more-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Build Dynamic WP_Query Arguments.
     */
    public function get_query_args( $settings, $paged = 1 ) {
        $selection = $settings['query_selection'] ?? 'dynamic';

        if ( 'manual' === $selection && ! empty( $settings['query_manual_ids'] ) ) {
            $post_ids = array_filter( array_map( 'intval', array_map( 'trim', explode( ',', $settings['query_manual_ids'] ) ) ) );
            if ( ! empty( $post_ids ) ) {
                return [
                    'post_type'           => ! empty( $settings['query_post_type'] ) ? $settings['query_post_type'] : 'any',
                    'post__in'            => $post_ids,
                    'orderby'             => 'post__in',
                    'posts_per_page'      => count( $post_ids ),
                    'paged'               => 1,
                    'post_status'         => 'publish',
                    'ignore_sticky_posts' => true,
                ];
            }
        }

        $args = [
            'post_type'           => ! empty( $settings['query_post_type'] ) ? $settings['query_post_type'] : 'post',
            'posts_per_page'      => ! empty( $settings['query_posts_per_page'] ) ? intval( $settings['query_posts_per_page'] ) : 6,
            'paged'               => $paged,
            'orderby'             => ! empty( $settings['query_orderby'] ) ? $settings['query_orderby'] : 'date',
            'order'               => ! empty( $settings['query_order'] ) ? $settings['query_order'] : 'DESC',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
        ];

        // Categories
        if ( ! empty( $settings['query_categories'] ) && 'post' === $args['post_type'] ) {
            $args['category__in'] = $settings['query_categories'];
        }

        // Tags
        if ( ! empty( $settings['query_tags'] ) && 'post' === $args['post_type'] ) {
            $args['tag__in'] = $settings['query_tags'];
        }

        // Authors
        if ( ! empty( $settings['query_authors'] ) ) {
            $args['author__in'] = $settings['query_authors'];
        }

        // Exclude IDs
        if ( ! empty( $settings['query_exclude_ids'] ) ) {
            $exclude_ids = explode( ',', $settings['query_exclude_ids'] );
            $args['post__not_in'] = array_map( 'intval', array_map( 'trim', $exclude_ids ) );
        }

        // Exclude posts without thumbnail
        if ( 'yes' === ( $settings['query_exclude_no_thumb'] ?? '' ) ) {
            $args['meta_key'] = '_thumbnail_id';
        }

        return $args;
    }

    /**
     * Render Card Media.
     */
    protected function render_card_media( $media_data, $settings ) {
        if ( 'none' === ( $settings['media_position'] ?? 'top' ) || empty( $media_data['type'] ) || 'none' === $media_data['type'] ) {
            return;
        }

        $media_type = $media_data['type'];
        $link       = $media_data['link'] ?? '';
        $img_size   = ! empty( $settings['media_image_size_size'] ) ? $settings['media_image_size_size'] : 'full';

        echo '<div class="ua-timeline-media">';

        if ( 'image' === $media_type && ! empty( $media_data['image_url'] ) ) {
            if ( ! empty( $link ) ) {
                echo '<a href="' . esc_url( $link ) . '">';
            }
            if ( ! empty( $media_data['image_id'] ) ) {
                echo wp_get_attachment_image( $media_data['image_id'], $img_size, false, [ 'class' => 'ua-timeline-img' ] );
            } else {
                echo '<img src="' . esc_url( $media_data['image_url'] ) . '" alt="' . esc_attr( $media_data['title'] ?? '' ) . '" class="ua-timeline-img" />';
            }
            if ( ! empty( $link ) ) {
                echo '</a>';
            }
        } elseif ( 'video_url' === $media_type && ! empty( $media_data['video_url'] ) ) {
            $embed_url = $this->get_embed_video_url( $media_data['video_url'] );
            if ( $embed_url ) {
                echo '<div class="ua-timeline-video-wrap"><iframe src="' . esc_url( $embed_url ) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>';
            }
        } elseif ( 'icon' === $media_type && ! empty( $media_data['icon'] ) ) {
            echo '<div class="ua-timeline-media-icon">';
            Icons_Manager::render_icon( $media_data['icon'], [ 'aria-hidden' => 'true' ] );
            echo '</div>';
        }

        echo '</div>';
    }

    /**
     * Get Embed Video URL.
     */
    protected function get_embed_video_url( $url ) {
        if ( preg_match( '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $match ) ) {
            return 'https://www.youtube.com/embed/' . $match[1];
        } elseif ( preg_match( '/(?:vimeo\.com\/)([0-9]+)/', $url, $match ) ) {
            return 'https://player.vimeo.com/video/' . $match[1];
        }
        return '';
    }

    /**
     * Render Single Timeline Item (Card).
     */
    protected function render_timeline_item( $item_data, $settings, $index, $is_even ) {
        $layout          = $settings['timeline_layout'];
        $side_class      = $is_even ? 'is-left' : 'is-right';
        $item_repeater_id= $item_data['repeater_id'] ?? $index;
        $title_tag       = Utils::validate_html_tag( $settings['title_tag'] ?? 'span' );
        $media_pos       = $settings['media_position'];

        if ( 'one-sided-left' === $layout ) {
            $side_class = 'is-right';
        } elseif ( 'one-sided-right' === $layout ) {
            $side_class = 'is-left';
        }

        $item_classes = [
            'ua-timeline-item',
            $side_class,
            'elementor-repeater-item-' . esc_attr( $item_repeater_id ),
        ];

        if ( 'horizontal' === $layout ) {
            $item_classes[] = 'swiper-slide';
        }

        if ( ! empty( $item_data['group_divider'] ) && 'horizontal' !== $layout ) :
            ?>
            <div class="ua-timeline-group-divider">
                <span class="ua-timeline-group-label"><?php echo esc_html( $item_data['group_divider'] ); ?></span>
            </div>
            <?php
        endif;

        $card_classes = [ 'ua-timeline-card' ];
        if ( 'overlay' === $media_pos || 'yes' === ( $settings['show_image_overlay'] ?? 'no' ) ) {
            $card_classes[] = 'has-media-overlay';
        }
        ?>
        <div class="<?php echo esc_attr( implode( ' ', $item_classes ) ); ?>" data-index="<?php echo esc_attr( $index ); ?>">
            
            <?php if ( 'yes' === ( $settings['show_badge'] ?? 'yes' ) && ! empty( $item_data['badge'] ) ) : ?>
                <div class="ua-timeline-badge-wrap">
                    <span class="ua-timeline-badge"><?php echo esc_html( $item_data['badge'] ); ?></span>
                </div>
            <?php endif; ?>

            <!-- Timeline Marker Node -->
            <div class="ua-timeline-marker-wrap">
                <div class="ua-timeline-marker">
                    <span class="ua-timeline-marker-icon">
                        <?php
                        if ( ! empty( $item_data['icon'] ) ) {
                            Icons_Manager::render_icon( $item_data['icon'], [ 'aria-hidden' => 'true' ] );
                        } elseif ( ! empty( $settings['posts_icon'] ) ) {
                            Icons_Manager::render_icon( $settings['posts_icon'], [ 'aria-hidden' => 'true' ] );
                        }
                        ?>
                    </span>
                </div>
            </div>

            <!-- Card Box -->
            <div class="<?php echo esc_attr( implode( ' ', $card_classes ) ); ?>">
                <?php if ( 'yes' === ( $settings['show_pointer_arrow'] ?? 'yes' ) ) : ?>
                    <span class="ua-timeline-arrow"></span>
                <?php endif; ?>

                <?php if ( ( 'top' === $media_pos || 'overlay' === $media_pos ) && 'none' !== $media_pos ) : ?>
                    <?php $this->render_card_media( $item_data['media'], $settings ); ?>
                <?php endif; ?>

                <div class="ua-timeline-card-inner">
                    <?php if ( 'yes' === ( $settings['show_date'] ?? 'yes' ) && ( ! empty( $item_data['date'] ) || ! empty( $item_data['tag'] ) || ! empty( $item_data['author'] ) ) ) : ?>
                        <div class="ua-timeline-meta">
                            <?php if ( ! empty( $item_data['tag'] ) ) : ?>
                                <span class="ua-timeline-tag"><?php echo esc_html( $item_data['tag'] ); ?></span>
                            <?php endif; ?>
                            <?php if ( ! empty( $item_data['date'] ) ) : ?>
                                <span class="ua-timeline-date"><?php echo esc_html( $item_data['date'] ); ?></span>
                            <?php endif; ?>
                            <?php if ( ! empty( $item_data['author'] ) ) : ?>
                                <span class="ua-timeline-author"><?php echo esc_html( $item_data['author'] ); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( 'yes' === ( $settings['show_title'] ?? 'yes' ) && ! empty( $item_data['title'] ) ) : ?>
                        <<?php echo esc_attr( $title_tag ); ?> class="ua-timeline-title">
                            <?php if ( ! empty( $item_data['link'] ) ) : ?>
                                <a href="<?php echo esc_url( $item_data['link'] ); ?>">
                                    <?php echo esc_html( $item_data['title'] ); ?>
                                </a>
                            <?php else : ?>
                                <?php echo esc_html( $item_data['title'] ); ?>
                            <?php endif; ?>
                        </<?php echo esc_attr( $title_tag ); ?>>
                    <?php endif; ?>

                    <?php if ( ! empty( $item_data['description'] ) ) : ?>
                        <div class="ua-timeline-desc">
                            <?php echo wp_kses_post( $item_data['description'] ); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( 'yes' === ( $settings['show_read_more'] ?? 'yes' ) && ! empty( $item_data['button_text'] ) && ! empty( $item_data['button_link'] ) ) : ?>
                        <div class="ua-timeline-btn-wrap">
                            <a href="<?php echo esc_url( $item_data['button_link'] ); ?>" class="ua-timeline-btn">
                                <?php echo esc_html( $item_data['button_text'] ); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ( 'bottom' === $media_pos && 'none' !== $media_pos ) : ?>
                    <?php $this->render_card_media( $item_data['media'], $settings ); ?>
                <?php endif; ?>
            </div>

        </div>
        <?php
    }

    /**
     * Render Custom Repeater Items.
     */
    protected function render_custom_items( $settings ) {
        $items = $settings['timeline_items'];

        if ( empty( $items ) || ! is_array( $items ) ) {
            return;
        }

        $show_desc = ! empty( $settings['show_description'] ) ? ( 'yes' === $settings['show_description'] ) : ( 'yes' === ( $settings['show_excerpt'] ?? 'yes' ) );

        foreach ( $items as $index => $item ) {
            $is_even = ( 0 === $index % 2 );

            $button_link = '';
            if ( ! empty( $item['item_btn_url']['url'] ) ) {
                $button_link = $item['item_btn_url']['url'];
            } elseif ( ! empty( $item['item_link']['url'] ) ) {
                $button_link = $item['item_link']['url'];
            }

            $item_data = [
                'repeater_id'   => $item['_id'] ?? $index,
                'group_divider' => $item['item_group_divider'] ?? '',
                'badge'         => $item['item_badge'] ?? '',
                'tag'           => $item['item_tag'] ?? '',
                'date'          => $item['item_date'] ?? '',
                'icon'          => $item['item_icon'] ?? '',
                'title'         => $item['item_title'] ?? '',
                'link'          => $item['item_link']['url'] ?? '',
                'description'   => $show_desc ? ( $item['item_description'] ?? '' ) : '',
                'button_text'   => ( 'yes' === ( $settings['show_read_more'] ?? 'yes' ) ) ? ( $item['item_btn_text'] ?? ( $settings['read_more_text'] ?? esc_html__( 'Read More', 'ultraaddons-elementor-lite' ) ) ) : '',
                'button_link'   => $button_link,
                'media'         => [
                    'type'      => $item['item_media_type'] ?? 'none',
                    'image_id'  => $item['item_image']['id'] ?? '',
                    'image_url' => $item['item_image']['url'] ?? '',
                    'icon'      => $item['item_media_icon'] ?? '',
                    'video_url' => $item['item_video_url'] ?? '',
                    'link'      => $item['item_link']['url'] ?? '',
                    'title'     => $item['item_title'] ?? '',
                ],
            ];

            $this->render_timeline_item( $item_data, $settings, $index, $is_even );
        }
    }

    /**
     * Render Dynamic Post Items.
     */
    protected function render_dynamic_items( $settings, $paged = 1 ) {
        $query_args = $this->get_query_args( $settings, $paged );
        $query      = new \WP_Query( $query_args );

        if ( ! $query->have_posts() ) {
            echo '<div class="ua-timeline-no-posts"><p>' . esc_html( $settings['query_no_posts_text'] ) . '</p></div>';
            wp_reset_postdata();
            return [ 'max_pages' => 0, 'last_year' => '' ];
        }

        $index       = ( $paged - 1 ) * intval( $settings['query_posts_per_page'] );
        $date_format = ! empty( $settings['date_format'] ) ? $settings['date_format'] : 'F j, Y';
        $date_source = $settings['date_source'] ?? 'publish_date';
        $last_year   = '';

        while ( $query->have_posts() ) {
            $query->the_post();
            $post_id    = get_the_ID();
            $is_even    = ( 0 === $index % 2 );
            $post_date  = ( 'modified_date' === $date_source ) ? get_the_modified_date( $date_format ) : get_the_date( $date_format );
            $year_badge = get_the_date( 'Y' );

            $extra_label = $year_badge;
            if ( 'custom_field' === ( $settings['extra_label_source'] ?? 'publish_date' ) && ! empty( $settings['extra_label_meta_key'] ) ) {
                $meta_lbl = get_post_meta( $post_id, sanitize_key( $settings['extra_label_meta_key'] ), true );
                if ( ! empty( $meta_lbl ) ) {
                    $extra_label = $meta_lbl;
                }
            }

            $group_divider = '';
            if ( 'yes' === ( $settings['group_by_year'] ?? 'no' ) && $year_badge !== $last_year ) {
                $group_divider = $year_badge;
                $last_year     = $year_badge;
            }

            $cats = get_the_category();
            $tag  = ! empty( $cats ) ? $cats[0]->name : '';

            $author = '';
            if ( 'yes' === ( $settings['show_author'] ?? 'no' ) ) {
                $author = get_the_author();
            }

            $img_size  = ! empty( $settings['media_image_size_size'] ) ? $settings['media_image_size_size'] : 'full';
            $image_id  = get_post_thumbnail_id( $post_id );
            $image_url = get_the_post_thumbnail_url( $post_id, $img_size );

            $show_desc   = ! empty( $settings['show_description'] ) ? ( 'yes' === $settings['show_description'] ) : ( 'yes' === ( $settings['show_excerpt'] ?? 'yes' ) );
            $excerpt_len = ! empty( $settings['excerpt_length'] ) ? intval( $settings['excerpt_length'] ) : 20;
            $excerpt     = wp_trim_words( get_the_excerpt(), $excerpt_len );

            $item_data = [
                'repeater_id'   => 'post-' . $post_id,
                'group_divider' => $group_divider,
                'badge'         => $extra_label,
                'tag'           => $tag,
                'date'          => $post_date,
                'author'        => $author,
                'icon'          => $settings['posts_icon'] ?? '',
                'title'         => get_the_title(),
                'link'          => get_permalink(),
                'description'   => $show_desc ? $excerpt : '',
                'button_text'   => ( 'yes' === ( $settings['show_read_more'] ?? 'yes' ) ) ? ( $settings['read_more_text'] ?? esc_html__( 'Read More', 'ultraaddons-elementor-lite' ) ) : '',
                'button_link'   => get_permalink(),
                'media'         => [
                    'type'      => ! empty( $image_url ) ? 'image' : 'none',
                    'image_id'  => $image_id,
                    'image_url' => $image_url,
                    'link'      => get_permalink(),
                    'title'     => get_the_title(),
                ],
            ];

            $this->render_timeline_item( $item_data, $settings, $index, $is_even );
            $index++;
        }

        $max_pages = $query->max_num_pages;
        wp_reset_postdata();

        return [
            'max_pages' => $max_pages,
            'last_year' => $last_year,
        ];
    }

    /**
     * Main Render Method.
     */
    protected function render() {
        $settings   = $this->get_settings_for_display();
        $widget_id  = $this->get_id();
        $layout     = $settings['timeline_layout'];
        $is_custom  = ( 'custom' === $settings['timeline_content'] );
        $is_horiz   = ( 'horizontal' === $layout );

        $anim          = $settings['entrance_animation'] ?? 'none';
        $anim_offset   = ! empty( $settings['animation_offset'] ) ? intval( $settings['animation_offset'] ) : 150;
        $anim_duration = ! empty( $settings['animation_duration'] ) ? intval( $settings['animation_duration'] ) : 600;

        $container_classes = [
            'ua-timeline-container',
            'ua-timeline-' . esc_attr( $layout ),
            'ua-timeline-' . esc_attr( $settings['timeline_content'] ),
        ];

        if ( 'none' !== $anim ) {
            $container_classes[] = 'has-entrance-animation';
            $container_classes[] = 'ua-anim-' . esc_attr( $anim );
        }

        if ( $is_horiz ) {
            $line_pos = ! empty( $settings['carousel_line_position'] ) ? $settings['carousel_line_position'] : 'line-bottom';
            $container_classes[] = 'ua-timeline-carousel-' . esc_attr( $line_pos );
        }

        if ( 'yes' === ( $settings['timeline_fill'] ?? 'yes' ) && ! $is_horiz ) {
            $container_classes[] = 'has-line-fill';
        }

        if ( 'yes' === ( $settings['marker_pulse'] ?? 'yes' ) ) {
            $container_classes[] = 'has-marker-pulse';
        }

        // Swiper slider configuration
        $swiper_config = [];
        if ( $is_horiz ) {
            $slides_desktop = ! empty( $settings['slides_to_show'] ) ? intval( $settings['slides_to_show'] ) : 3;
            $slides_tablet  = ! empty( $settings['slides_to_show_tablet'] ) ? intval( $settings['slides_to_show_tablet'] ) : 2;
            $slides_mobile  = ! empty( $settings['slides_to_show_mobile'] ) ? intval( $settings['slides_to_show_mobile'] ) : 1;
            $gutter         = isset( $settings['slides_gutter']['size'] ) ? intval( $settings['slides_gutter']['size'] ) : 24;

            $swiper_config = [
                'slidesPerView'  => $slides_mobile,
                'spaceBetween'   => $gutter,
                'speed'          => ! empty( $settings['carousel_speed'] ) ? intval( $settings['carousel_speed'] ) : 600,
                'loop'           => ( 'yes' === ( $settings['carousel_loop'] ?? 'yes' ) ),
                'autoplay'       => ( 'yes' === ( $settings['carousel_autoplay'] ?? 'no' ) ) ? [
                    'delay'                => ! empty( $settings['carousel_autoplay_speed'] ) ? intval( $settings['carousel_autoplay_speed'] ) : 3500,
                    'disableOnInteraction' => ( 'yes' === ( $settings['carousel_pause_on_hover'] ?? 'yes' ) ),
                    'pauseOnMouseEnter'    => ( 'yes' === ( $settings['carousel_pause_on_hover'] ?? 'yes' ) ),
                ] : false,
                'breakpoints'    => [
                    768 => [
                        'slidesPerView' => $slides_tablet,
                        'spaceBetween'  => $gutter,
                    ],
                    1025 => [
                        'slidesPerView' => $slides_desktop,
                        'spaceBetween'  => $gutter,
                    ],
                ],
            ];

            if ( 'yes' === ( $settings['carousel_arrows'] ?? 'yes' ) ) {
                $swiper_config['navigation'] = [
                    'nextEl' => '.ua-swiper-next-' . esc_attr( $widget_id ),
                    'prevEl' => '.ua-swiper-prev-' . esc_attr( $widget_id ),
                ];
            }

            if ( 'none' !== ( $settings['carousel_pagination'] ?? 'dots' ) ) {
                $swiper_config['pagination'] = [
                    'el'        => '.ua-swiper-pagination-' . esc_attr( $widget_id ),
                    'type'      => ( 'progressbar' === $settings['carousel_pagination'] ) ? 'progressbar' : 'bullets',
                    'clickable' => true,
                ];
            }
        }

        ?>
        <div class="<?php echo esc_attr( implode( ' ', $container_classes ) ); ?>"
             id="ua-timeline-<?php echo esc_attr( $widget_id ); ?>"
             data-layout="<?php echo esc_attr( $layout ); ?>"
             data-animation="<?php echo esc_attr( $anim ); ?>"
             data-animation-offset="<?php echo esc_attr( $anim_offset ); ?>"
             data-animation-duration="<?php echo esc_attr( $anim_duration ); ?>"
             style="--ua-anim-duration: <?php echo esc_attr( $anim_duration ); ?>ms;"
             <?php if ( $is_horiz ) : ?>
                 data-swiper-config="<?php echo esc_attr( wp_json_encode( $swiper_config ) ); ?>"
             <?php endif; ?>>

            <?php if ( ! $is_horiz ) : ?>
                <!-- Vertical Center or One-Sided Line Structure -->
                <div class="ua-timeline-line-wrap">
                    <div class="ua-timeline-line"></div>
                    <?php if ( 'yes' === ( $settings['timeline_fill'] ?? 'yes' ) ) : ?>
                        <div class="ua-timeline-fill"></div>
                    <?php endif; ?>
                </div>

                <div class="ua-timeline-items-wrap">
                    <?php
                    $max_pages = 1;
                    $last_year = '';
                    if ( $is_custom ) {
                        $this->render_custom_items( $settings );
                    } else {
                        $dyn_data  = $this->render_dynamic_items( $settings );
                        $max_pages = is_array( $dyn_data ) ? ( $dyn_data['max_pages'] ?? 1 ) : $dyn_data;
                        $last_year = is_array( $dyn_data ) ? ( $dyn_data['last_year'] ?? '' ) : '';
                    }
                    ?>
                </div>

                <?php if ( ! $is_custom && 'yes' === ( $settings['show_pagination'] ?? 'no' ) && 'none' !== ( $settings['pagination_type'] ?? 'load_more' ) && $max_pages > 1 ) : ?>
                    <div class="ua-timeline-pagination-wrap" 
                         data-max-pages="<?php echo esc_attr( $max_pages ); ?>"
                         data-current-page="1"
                         data-last-year="<?php echo esc_attr( $last_year ); ?>"
                         data-pagination-type="<?php echo esc_attr( $settings['pagination_type'] ?? 'load_more' ); ?>"
                         data-query="<?php echo esc_attr( wp_json_encode( $this->get_query_args( $settings, 1 ) ) ); ?>"
                         data-settings="<?php echo esc_attr( wp_json_encode( [
                             'timeline_layout'      => $settings['timeline_layout'],
                             'media_position'       => $settings['media_position'],
                             'show_badge'           => $settings['show_badge'] ?? 'yes',
                             'extra_label_source'   => $settings['extra_label_source'] ?? 'publish_date',
                             'extra_label_meta_key' => $settings['extra_label_meta_key'] ?? '',
                             'show_image_overlay'   => $settings['show_image_overlay'] ?? 'no',
                             'media_image_size_size'=> $settings['media_image_size_size'] ?? 'full',
                             'show_title'           => $settings['show_title'] ?? 'yes',
                             'title_tag'            => $settings['title_tag'] ?? 'span',
                             'show_date'            => $settings['show_date'] ?? 'yes',
                             'date_source'          => $settings['date_source'] ?? 'publish_date',
                             'show_description'     => $settings['show_description'] ?? ( $settings['show_excerpt'] ?? 'yes' ),
                             'excerpt_length'       => $settings['excerpt_length'] ?? 20,
                             'show_read_more'       => $settings['show_read_more'] ?? 'yes',
                             'read_more_text'       => $settings['read_more_text'] ?? esc_html__( 'Read More', 'ultraaddons-elementor-lite' ),
                             'show_pointer_arrow'   => $settings['show_pointer_arrow'] ?? 'yes',
                             'date_format'          => $settings['date_format'] ?? 'F j, Y',
                             'posts_icon'           => $settings['posts_icon'] ?? '',
                         ] ) ); ?>">

                        <?php if ( 'load_more' === ( $settings['pagination_type'] ?? 'load_more' ) ) : ?>
                            <button type="button" class="ua-timeline-load-more-btn">
                                <span class="ua-btn-text"><?php echo esc_html( $settings['load_more_btn_text'] ?? esc_html__( 'Load More', 'ultraaddons-elementor-lite' ) ); ?></span>
                                <span class="ua-btn-loader" style="display: none;"><?php echo esc_html( $settings['pagination_loading_text'] ?? esc_html__( 'Loading...', 'ultraaddons-elementor-lite' ) ); ?></span>
                            </button>
                        <?php elseif ( 'infinite_scroll' === $settings['pagination_type'] ) : ?>
                            <div class="ua-timeline-infinite-loader" style="display: none;">
                                <span class="ua-spinner"></span>
                                <span class="ua-loading-text"><?php echo esc_html( $settings['pagination_loading_text'] ?? esc_html__( 'Loading...', 'ultraaddons-elementor-lite' ) ); ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="ua-timeline-no-more" style="display: none;">
                            <p><?php echo esc_html( $settings['pagination_no_more_text'] ?? esc_html__( 'No More Posts', 'ultraaddons-elementor-lite' ) ); ?></p>
                        </div>
                    </div>
                <?php endif; ?>

            <?php else : ?>
                <!-- Horizontal Carousel Structure -->
                <div class="ua-timeline-horizontal-line-wrap">
                    <div class="ua-timeline-horizontal-line"></div>
                </div>

                <div class="swiper ua-timeline-swiper ua-timeline-swiper-<?php echo esc_attr( $widget_id ); ?>">
                    <div class="swiper-wrapper">
                        <?php
                        if ( $is_custom ) {
                            $this->render_custom_items( $settings );
                        } else {
                            $this->render_dynamic_items( $settings );
                        }
                        ?>
                    </div>
                </div>

                <?php if ( 'yes' === ( $settings['carousel_arrows'] ?? 'yes' ) ) : ?>
                    <div class="ua-timeline-nav-btn ua-timeline-prev ua-swiper-prev-<?php echo esc_attr( $widget_id ); ?>" tabindex="0" role="button" aria-label="<?php esc_attr_e( 'Previous', 'ultraaddons-elementor-lite' ); ?>">
                        <i class="eicon-chevron-left" aria-hidden="true"></i>
                    </div>
                    <div class="ua-timeline-nav-btn ua-timeline-next ua-swiper-next-<?php echo esc_attr( $widget_id ); ?>" tabindex="0" role="button" aria-label="<?php esc_attr_e( 'Next', 'ultraaddons-elementor-lite' ); ?>">
                        <i class="eicon-chevron-right" aria-hidden="true"></i>
                    </div>
                <?php endif; ?>

                <?php if ( 'none' !== ( $settings['carousel_pagination'] ?? 'dots' ) ) : ?>
                    <div class="ua-timeline-pagination ua-swiper-pagination-<?php echo esc_attr( $widget_id ); ?>"></div>
                <?php endif; ?>

            <?php endif; ?>

        </div>
        <?php
    }

    /**
     * AJAX Handler for Load More / Infinite Scroll.
     */
    public static function ajax_load_posts() {
        check_ajax_referer( 'ua-timeline-nonce', 'nonce' );

        $paged      = isset( $_POST['paged'] ) ? intval( $_POST['paged'] ) : 1;
        $last_year  = isset( $_POST['last_year'] ) ? sanitize_text_field( $_POST['last_year'] ) : '';
        $query_args = isset( $_POST['query'] ) ? json_decode( stripslashes( $_POST['query'] ), true ) : [];
        $settings   = isset( $_POST['settings'] ) ? json_decode( stripslashes( $_POST['settings'] ), true ) : [];

        if ( empty( $query_args ) ) {
            wp_send_json_error( [ 'message' => 'Invalid query arguments' ] );
        }

        $query_args['paged'] = $paged;
        $query = new \WP_Query( $query_args );

        if ( ! $query->have_posts() ) {
            wp_send_json_success( [ 'html' => '', 'has_more' => false, 'last_year' => $last_year ] );
        }

        ob_start();
        $index       = ( $paged - 1 ) * intval( $query_args['posts_per_page'] );
        $date_format = ! empty( $settings['date_format'] ) ? $settings['date_format'] : 'F j, Y';
        $date_source = $settings['date_source'] ?? 'publish_date';
        $title_tag   = Utils::validate_html_tag( $settings['title_tag'] ?? 'h3' );
        $layout      = $settings['timeline_layout'] ?? 'centered';
        $media_pos   = $settings['media_position'] ?? 'top';

        while ( $query->have_posts() ) {
            $query->the_post();
            $post_id    = get_the_ID();
            $is_even    = ( 0 === $index % 2 );
            $post_date  = ( 'modified_date' === $date_source ) ? get_the_modified_date( $date_format ) : get_the_date( $date_format );
            $year_badge = get_the_date( 'Y' );

            $extra_label = $year_badge;
            if ( 'custom_field' === ( $settings['extra_label_source'] ?? 'publish_date' ) && ! empty( $settings['extra_label_meta_key'] ) ) {
                $meta_lbl = get_post_meta( $post_id, sanitize_key( $settings['extra_label_meta_key'] ), true );
                if ( ! empty( $meta_lbl ) ) {
                    $extra_label = $meta_lbl;
                }
            }

            if ( 'yes' === ( $settings['group_by_year'] ?? 'no' ) && $year_badge !== $last_year ) {
                ?>
                <div class="ua-timeline-group-divider">
                    <span class="ua-timeline-group-label"><?php echo esc_html( $year_badge ); ?></span>
                </div>
                <?php
                $last_year = $year_badge;
            }

            $cats = get_the_category();
            $tag  = ! empty( $cats ) ? $cats[0]->name : '';

            $author = '';
            if ( 'yes' === ( $settings['show_author'] ?? '' ) ) {
                $author = get_the_author();
            }

            $img_size  = $settings['media_image_size_size'] ?? 'full';
            $image_id  = get_post_thumbnail_id( $post_id );
            $image_url = get_the_post_thumbnail_url( $post_id, $img_size );

            $show_desc   = ! empty( $settings['show_description'] ) ? ( 'yes' === $settings['show_description'] ) : ( 'yes' === ( $settings['show_excerpt'] ?? 'yes' ) );
            $excerpt_len = ! empty( $settings['excerpt_length'] ) ? intval( $settings['excerpt_length'] ) : 20;
            $excerpt     = wp_trim_words( get_the_excerpt(), $excerpt_len );

            $side_class = $is_even ? 'is-left' : 'is-right';
            if ( 'one-sided-left' === $layout ) {
                $side_class = 'is-right';
            } elseif ( 'one-sided-right' === $layout ) {
                $side_class = 'is-left';
            }

            $card_classes = [ 'ua-timeline-card' ];
            if ( 'overlay' === $media_pos || 'yes' === ( $settings['show_image_overlay'] ?? 'no' ) ) {
                $card_classes[] = 'has-media-overlay';
            }
            ?>
            <div class="ua-timeline-item <?php echo esc_attr( $side_class ); ?> elementor-repeater-item-post-<?php echo esc_attr( $post_id ); ?>" data-index="<?php echo esc_attr( $index ); ?>">
                <?php if ( 'yes' === ( $settings['show_badge'] ?? 'yes' ) ) : ?>
                    <div class="ua-timeline-badge-wrap">
                        <span class="ua-timeline-badge"><?php echo esc_html( $extra_label ); ?></span>
                    </div>
                <?php endif; ?>

                <div class="ua-timeline-marker-wrap">
                    <div class="ua-timeline-marker">
                        <span class="ua-timeline-marker-icon">
                            <?php
                            if ( ! empty( $settings['posts_icon'] ) ) {
                                Icons_Manager::render_icon( $settings['posts_icon'], [ 'aria-hidden' => 'true' ] );
                            }
                            ?>
                        </span>
                    </div>
                </div>

                <div class="<?php echo esc_attr( implode( ' ', $card_classes ) ); ?>">
                    <?php if ( 'yes' === ( $settings['show_pointer_arrow'] ?? 'yes' ) ) : ?>
                        <span class="ua-timeline-arrow"></span>
                    <?php endif; ?>

                    <?php if ( ( 'top' === $media_pos || 'overlay' === $media_pos ) && 'none' !== $media_pos && ! empty( $image_url ) ) : ?>
                        <div class="ua-timeline-media">
                            <a href="<?php echo esc_url( get_permalink() ); ?>">
                                <?php echo wp_get_attachment_image( $image_id, $img_size, false, [ 'class' => 'ua-timeline-img' ] ); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="ua-timeline-card-inner">
                        <?php if ( 'yes' === ( $settings['show_date'] ?? 'yes' ) ) : ?>
                            <div class="ua-timeline-meta">
                                <?php if ( ! empty( $tag ) ) : ?>
                                    <span class="ua-timeline-tag"><?php echo esc_html( $tag ); ?></span>
                                <?php endif; ?>
                                <span class="ua-timeline-date"><?php echo esc_html( $post_date ); ?></span>
                                <?php if ( ! empty( $author ) ) : ?>
                                    <span class="ua-timeline-author"><?php echo esc_html( $author ); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ( 'yes' === ( $settings['show_title'] ?? 'yes' ) ) : ?>
                            <<?php echo esc_attr( $title_tag ); ?> class="ua-timeline-title">
                                <a href="<?php echo esc_url( get_permalink() ); ?>">
                                    <?php echo esc_html( get_the_title() ); ?>
                                </a>
                            </<?php echo esc_attr( $title_tag ); ?>>
                        <?php endif; ?>

                        <?php if ( $show_desc && ! empty( $excerpt ) ) : ?>
                            <div class="ua-timeline-desc">
                                <?php echo wp_kses_post( $excerpt ); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ( 'yes' === ( $settings['show_read_more'] ?? 'yes' ) ) : ?>
                            <div class="ua-timeline-btn-wrap">
                                <a href="<?php echo esc_url( get_permalink() ); ?>" class="ua-timeline-btn">
                                    <?php echo esc_html( $settings['read_more_text'] ?? esc_html__( 'Read More', 'ultraaddons-elementor-lite' ) ); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if ( 'bottom' === $media_pos && 'none' !== $media_pos && ! empty( $image_url ) ) : ?>
                        <div class="ua-timeline-media">
                            <a href="<?php echo esc_url( get_permalink() ); ?>">
                                <?php echo wp_get_attachment_image( $image_id, $img_size, false, [ 'class' => 'ua-timeline-img' ] ); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php
            $index++;
        }

        $html = ob_get_clean();
        $has_more = ( $paged < $query->max_num_pages );
        wp_reset_postdata();

        wp_send_json_success( [
            'html'      => $html,
            'has_more'  => $has_more,
            'last_year' => $last_year,
        ] );
    }
}
