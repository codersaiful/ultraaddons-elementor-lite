<?php
namespace UltraAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * UltraAddons - Recent Blog / Post Grid Widget
 *
 * An ultra-modern, high-performance blog & post grid widget for Elementor.
 * Supports Grid, List, and Hero layouts, live AJAX category filter,
 * AJAX pagination, Load More button, reading time calculation, and smart meta badges.
 *
 * @package UltraAddons
 * @version 2.0.3.5
 */
class Recent_Blog extends Base {

    /**
     * Constructor — Register assets & AJAX hooks.
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        $css_file = ULTRA_ADDONS_DIR . 'assets/css/widgets/recent-blog.css';
        $css_ver  = file_exists( $css_file ) ? filemtime( $css_file ) : ULTRA_ADDONS_VERSION;

        $js_file  = ULTRA_ADDONS_DIR . 'assets/js/frontend-recent-blog.js';
        $js_ver   = file_exists( $js_file ) ? filemtime( $js_file ) : ULTRA_ADDONS_VERSION;

        wp_register_style(
            'ultraaddons-recent-blog',
            ULTRA_ADDONS_ASSETS . 'css/widgets/recent-blog.css',
            [],
            $css_ver,
            'all'
        );

        wp_register_script(
            'ultraaddons-recent-blog',
            ULTRA_ADDONS_ASSETS . 'js/frontend-recent-blog.js',
            [ 'jquery' ],
            $js_ver,
            true
        );

        wp_localize_script(
            'ultraaddons-recent-blog',
            'uaBlogConfig',
            [
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'ua-recent-blog-nonce' ),
                'i18n'     => [
                    'loading' => esc_html__( 'Loading...', 'ultraaddons-elementor-lite' ),
                    'no_more' => esc_html__( 'No more posts', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        // AJAX handlers
        add_action( 'wp_ajax_ua_recent_blog_load_posts', [ __CLASS__, 'ajax_load_posts' ] );
        add_action( 'wp_ajax_nopriv_ua_recent_blog_load_posts', [ __CLASS__, 'ajax_load_posts' ] );
    }

    /**
     * Widget Name.
     */
    public function get_name() {
        return 'ultraaddons-recent-blog';
    }

    /**
     * Widget Title.
     */
    public function get_title() {
        return esc_html__( 'Recent Blog / Post Grid', 'ultraaddons-elementor-lite' );
    }

    /**
     * Widget Icon.
     */
    public function get_icon() {
        return 'uicon-blog-list';
    }

    /**
     * Widget Categories.
     */
    public function get_categories() {
        return [ 'general', 'ultraaddons' ];
    }

    /**
     * Widget Keywords.
     */
    public function get_keywords() {
        return [
            'ultraaddons',
            'blog',
            'posts',
            'post grid',
            'recent blog',
            'news',
            'magazine',
            'article',
            'grid',
            'list',
        ];
    }

    /**
     * Style Dependencies.
     */
    public function get_style_depends() {
        return [ 'ultraaddons-recent-blog' ];
    }

    /**
     * Script Dependencies.
     */
    public function get_script_depends() {
        return [ 'ultraaddons-recent-blog' ];
    }

    /**
     * Register Elementor Controls.
     */
    protected function register_controls() {
        $this->content_layout_controls();
        $this->content_query_controls();
        $this->content_elements_controls();
        $this->content_filter_pagination_controls();

        // Style Tab
        $this->style_card_controls();
        $this->style_image_controls();
        $this->style_badge_controls();
        $this->style_meta_controls();
        $this->style_title_controls();
        $this->style_excerpt_controls();
        $this->style_button_controls();
        $this->style_filter_controls();
        $this->style_pagination_controls();
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
            'layout_type',
            [
                'label'   => esc_html__( 'Layout Mode', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'grid',
                'options' => [
                    'grid' => esc_html__( 'Grid (Classic / Modern)', 'ultraaddons-elementor-lite' ),
                    'list' => esc_html__( 'List (Side-by-Side Row)', 'ultraaddons-elementor-lite' ),
                    'hero' => esc_html__( 'Hero (1st Post Featured)', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label'          => esc_html__( 'Columns', 'ultraaddons-elementor-lite' ),
                'type'           => Controls_Manager::SELECT,
                'default'        => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options'        => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'selectors'      => [
                    '{{WRAPPER}} .ua-recent-blog-grid' => '--ua-blog-cols: {{VALUE}};',
                ],
                'condition'      => [
                    'layout_type' => 'grid',
                ],
            ]
        );

        $this->add_responsive_control(
            'column_gap',
            [
                'label'      => esc_html__( 'Column Gap', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 60 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 30 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-recent-blog-grid' => '--ua-blog-col-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'row_gap',
            [
                'label'      => esc_html__( 'Row Gap', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 60 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 30 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-recent-blog-grid' => '--ua-blog-row-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_align',
            [
                'label'     => esc_html__( 'Content Alignment', 'ultraaddons-elementor-lite' ),
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
                'default'   => 'left',
                'selectors' => [
                    '{{WRAPPER}} .ua-blog-content' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .ua-blog-meta'    => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}} .ua-blog-footer'  => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content: Query Section
     */
    protected function content_query_controls() {
        $this->start_controls_section(
            'section_query',
            [
                'label' => esc_html__( 'Query & Filters', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'query_source',
            [
                'label'   => esc_html__( 'Query Source', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'recent',
                'options' => [
                    'recent'   => esc_html__( 'Recent Posts', 'ultraaddons-elementor-lite' ),
                    'category' => esc_html__( 'By Category', 'ultraaddons-elementor-lite' ),
                    'tag'      => esc_html__( 'By Tag', 'ultraaddons-elementor-lite' ),
                    'author'   => esc_html__( 'By Author', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        // Categories multi-select
        $this->add_control(
            'categories',
            [
                'label'       => esc_html__( 'Select Categories', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT2,
                'multiple'    => true,
                'options'     => $this->get_terms_options( 'category' ),
                'condition'   => [
                    'query_source' => 'category',
                ],
            ]
        );

        // Tags multi-select
        $this->add_control(
            'tags',
            [
                'label'       => esc_html__( 'Select Tags', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT2,
                'multiple'    => true,
                'options'     => $this->get_terms_options( 'post_tag' ),
                'condition'   => [
                    'query_source' => 'tag',
                ],
            ]
        );

        // Authors multi-select
        $this->add_control(
            'authors',
            [
                'label'       => esc_html__( 'Select Authors', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT2,
                'multiple'    => true,
                'options'     => $this->get_authors_options(),
                'condition'   => [
                    'query_source' => 'author',
                ],
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
                'step'    => 1,
            ]
        );

        $this->add_control(
            'offset',
            [
                'label'       => esc_html__( 'Offset (Skip Posts)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 0,
                'min'         => 0,
                'max'         => 50,
                'step'        => 1,
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label'   => esc_html__( 'Order By', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'date'          => esc_html__( 'Published Date', 'ultraaddons-elementor-lite' ),
                    'title'         => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                    'modified'      => esc_html__( 'Last Modified Date', 'ultraaddons-elementor-lite' ),
                    'comment_count' => esc_html__( 'Comment Count', 'ultraaddons-elementor-lite' ),
                    'rand'          => esc_html__( 'Random', 'ultraaddons-elementor-lite' ),
                    'menu_order'    => esc_html__( 'Menu Order', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label'   => esc_html__( 'Order Direction', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => [
                    'DESC' => esc_html__( 'Descending (3, 2, 1 / Z-A)', 'ultraaddons-elementor-lite' ),
                    'ASC'  => esc_html__( 'Ascending (1, 2, 3 / A-Z)', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'exclude_current',
            [
                'label'        => esc_html__( 'Exclude Current Post', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'ignore_sticky_posts',
            [
                'label'        => esc_html__( 'Ignore Sticky Posts', 'ultraaddons-elementor-lite' ),
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
     * Content: Card Elements Section
     */
    protected function content_elements_controls() {
        $this->start_controls_section(
            'section_card_elements',
            [
                'label' => esc_html__( 'Card Elements & Content', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        // 1. Image & Sizing Controls
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'    => 'image_size',
                'default' => 'medium_large',
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label'      => esc_html__( 'Image Height', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh', 'em', 'rem' ],
                'range'      => [
                    'px' => [ 'min' => 50, 'max' => 800, 'step' => 1 ],
                    'vh' => [ 'min' => 10, 'max' => 100 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 220 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-blog-thumb-wrap' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_object_fit',
            [
                'label'     => esc_html__( 'Image Fit', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'cover',
                'options'   => [
                    'cover'      => esc_html__( 'Cover (Crop to fill)', 'ultraaddons-elementor-lite' ),
                    'contain'    => esc_html__( 'Contain (Fit inside)', 'ultraaddons-elementor-lite' ),
                    'fill'       => esc_html__( 'Fill (Stretch)', 'ultraaddons-elementor-lite' ),
                    'scale-down' => esc_html__( 'Scale Down', 'ultraaddons-elementor-lite' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-blog-thumb-wrap img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'image_object_position',
            [
                'label'     => esc_html__( 'Image Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'center center',
                'options'   => [
                    'center center' => esc_html__( 'Center Center', 'ultraaddons-elementor-lite' ),
                    'center top'    => esc_html__( 'Center Top', 'ultraaddons-elementor-lite' ),
                    'center bottom' => esc_html__( 'Center Bottom', 'ultraaddons-elementor-lite' ),
                    'left center'   => esc_html__( 'Left Center', 'ultraaddons-elementor-lite' ),
                    'right center'  => esc_html__( 'Right Center', 'ultraaddons-elementor-lite' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-blog-thumb-wrap img' => 'object-position: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_gap',
            [
                'label'      => esc_html__( 'Image Bottom Gap', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 50 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 0 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-blog-thumb-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'separator'  => 'after',
            ]
        );

        // 2. Category Badge
        $this->add_control(
            'show_badge',
            [
                'label'        => esc_html__( 'Show Category Badge', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        // 3. Post Meta (Author, Date, Comments, Reading Time)
        $this->add_control(
            'show_meta',
            [
                'label'        => esc_html__( 'Show Post Meta Info', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'meta_author',
            [
                'label'        => esc_html__( 'Author Name', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'show_meta' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'meta_date',
            [
                'label'        => esc_html__( 'Published Date', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'show_meta' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'meta_comments',
            [
                'label'        => esc_html__( 'Comments Count', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'show_meta' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'meta_reading_time',
            [
                'label'        => esc_html__( 'Reading Time (e.g. 3 min read)', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'show_meta' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'meta_gap',
            [
                'label'      => esc_html__( 'Meta Bottom Gap', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 50 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 10 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-blog-meta' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'show_meta' => 'yes',
                ],
                'separator'  => 'after',
            ]
        );

        // 4. Post Title
        $this->add_control(
            'show_title',
            [
                'label'        => esc_html__( 'Show Title', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label'     => esc_html__( 'Title HTML Tag', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'h3',
                'options'   => [
                    'h2'  => 'H2',
                    'h3'  => 'H3',
                    'h4'  => 'H4',
                    'h5'  => 'H5',
                    'h6'  => 'H6',
                    'div' => 'div',
                ],
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_gap',
            [
                'label'      => esc_html__( 'Title Bottom Gap', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 50 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 10 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-blog-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'show_title' => 'yes',
                ],
                'separator'  => 'after',
            ]
        );

        // 5. Excerpt
        $this->add_control(
            'show_excerpt',
            [
                'label'        => esc_html__( 'Show Excerpt', 'ultraaddons-elementor-lite' ),
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
                'label'     => esc_html__( 'Excerpt Words Length', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 18,
                'min'       => 5,
                'max'       => 100,
                'step'      => 1,
                'condition' => [
                    'show_excerpt' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'excerpt_gap',
            [
                'label'      => esc_html__( 'Excerpt Bottom Gap', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 50 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 16 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-blog-excerpt' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'show_excerpt' => 'yes',
                ],
                'separator'  => 'after',
            ]
        );

        // 6. Read More Button
        $this->add_control(
            'show_read_more',
            [
                'label'        => esc_html__( 'Show Read More Button', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
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
            'read_more_style',
            [
                'label'     => esc_html__( 'Button Style', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'text',
                'options'   => [
                    'text'    => esc_html__( 'Text Link with Icon', 'ultraaddons-elementor-lite' ),
                    'button'  => esc_html__( 'Solid Button', 'ultraaddons-elementor-lite' ),
                    'outline' => esc_html__( 'Outline Button', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'read_more_align',
            [
                'label'     => esc_html__( 'Button Position / Alignment', 'ultraaddons-elementor-lite' ),
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
                'default'   => 'left',
                'selectors_dictionary' => [
                    'left'   => 'justify-content: flex-start;',
                    'center' => 'justify-content: center;',
                    'right'  => 'justify-content: flex-end;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-blog-footer' => '{{VALUE}}',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'read_more_full_width',
            [
                'label'        => esc_html__( 'Full Width Button', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'selectors'    => [
                    '{{WRAPPER}} .ua-blog-read-more' => 'width: 100%; justify-content: center;',
                ],
                'condition'    => [
                    'show_read_more'   => 'yes',
                    'read_more_style!' => 'text',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content: Filter & Pagination Section
     */
    protected function content_filter_pagination_controls() {
        $this->start_controls_section(
            'section_filter_pagination',
            [
                'label' => esc_html__( 'Filter Bar & Pagination', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Category Filter Bar
        $this->add_control(
            'show_filter',
            [
                'label'        => esc_html__( 'Show AJAX Category Filter', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'filter_style',
            [
                'label'     => esc_html__( 'Filter Bar Style', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'pill',
                'options'   => [
                    'pill'      => esc_html__( 'Pill Buttons', 'ultraaddons-elementor-lite' ),
                    'underline' => esc_html__( 'Minimal Underline', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'show_filter' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'filter_align',
            [
                'label'     => esc_html__( 'Filter Alignment', 'ultraaddons-elementor-lite' ),
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
                    '{{WRAPPER}} .ua-blog-filter-bar' => '--ua-filter-align: {{VALUE}};',
                ],
                'condition' => [
                    'show_filter' => 'yes',
                ],
            ]
        );

        // Pagination Type
        $this->add_control(
            'pagination_type',
            [
                'label'     => esc_html__( 'Pagination Type', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'none',
                'options'   => [
                    'none'      => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                    'numbered'  => esc_html__( 'AJAX Numbered Pagination', 'ultraaddons-elementor-lite' ),
                    'load_more' => esc_html__( 'AJAX Load More Button', 'ultraaddons-elementor-lite' ),
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'load_more_text',
            [
                'label'     => esc_html__( 'Load More Button Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Load More Posts', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'pagination_type' => 'load_more',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Card Box Section
     */
    protected function style_card_controls() {
        $this->start_controls_section(
            'section_style_card',
            [
                'label' => esc_html__( 'Card Container', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-blog-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default'    => [
                    'top'      => 12,
                    'right'    => 12,
                    'bottom'   => 12,
                    'left'     => 12,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-blog-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'card_border',
                'selector' => '{{WRAPPER}} .ua-blog-card',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'card_shadow',
                'selector' => '{{WRAPPER}} .ua-blog-card',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'card_hover_shadow',
                'label'    => esc_html__( 'Hover Box Shadow', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-blog-card:hover',
            ]
        );

        $this->add_responsive_control(
            'card_body_padding',
            [
                'label'      => esc_html__( 'Content Body Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'    => [
                    'top'      => 22,
                    'right'    => 22,
                    'bottom'   => 22,
                    'left'     => 22,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-blog-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Image Section
     */
    protected function style_image_controls() {
        $this->start_controls_section(
            'section_style_image',
            [
                'label' => esc_html__( 'Featured Image', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'style_image_height',
            [
                'label'      => esc_html__( 'Image Height', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh', 'em', '%' ],
                'range'      => [
                    'px' => [ 'min' => 50, 'max' => 800 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-blog-thumb-wrap' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-blog-thumb-wrap'     => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ua-blog-thumb-wrap img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_hover_effect',
            [
                'label'        => esc_html__( 'Hover Zoom Effect', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'prefix_class' => 'ua-blog-zoom-',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Badge Section
     */
    protected function style_badge_controls() {
        $this->start_controls_section(
            'section_style_badge',
            [
                'label'     => esc_html__( 'Category Badge', 'ultraaddons-elementor-lite' ),
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
                    '{{WRAPPER}} .ua-blog-badge' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'badge_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0fc392',
                'selectors' => [
                    '{{WRAPPER}} .ua-blog-badge' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'badge_typography',
                'selector' => '{{WRAPPER}} .ua-blog-badge',
            ]
        );

        $this->add_responsive_control(
            'badge_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-blog-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Meta Info Section
     */
    protected function style_meta_controls() {
        $this->start_controls_section(
            'section_style_meta',
            [
                'label'     => esc_html__( 'Meta Information', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_meta' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'meta_text_color',
            [
                'label'     => esc_html__( 'Meta Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#64748b',
                'selectors' => [
                    '{{WRAPPER}} .ua-blog-meta'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-blog-meta a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'meta_icon_color',
            [
                'label'     => esc_html__( 'Meta Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0fc392',
                'selectors' => [
                    '{{WRAPPER}} .ua-blog-meta i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'meta_typography',
                'selector' => '{{WRAPPER}} .ua-blog-meta',
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
                'label'     => esc_html__( 'Post Title', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
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
                    '{{WRAPPER}} .ua-blog-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label'     => esc_html__( 'Title Hover Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0fc392',
                'selectors' => [
                    '{{WRAPPER}} .ua-blog-title a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .ua-blog-title',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Excerpt Section
     */
    protected function style_excerpt_controls() {
        $this->start_controls_section(
            'section_style_excerpt',
            [
                'label'     => esc_html__( 'Post Excerpt', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_excerpt' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'excerpt_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#64748b',
                'selectors' => [
                    '{{WRAPPER}} .ua-blog-excerpt' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'excerpt_typography',
                'selector' => '{{WRAPPER}} .ua-blog-excerpt',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Button Section
     */
    protected function style_button_controls() {
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
                'selector' => '{{WRAPPER}} .ua-blog-read-more',
            ]
        );

        $this->start_controls_tabs( 'tabs_btn_style' );

        // Normal Tab
        $this->start_controls_tab(
            'tab_btn_normal',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'btn_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-blog-read-more' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'btn_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-blog-read-more.btn-style-button, {{WRAPPER}} .ua-blog-read-more.btn-style-outline' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'read_more_style!' => 'text',
                ],
            ]
        );

        $this->add_control(
            'btn_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-blog-read-more.btn-style-outline' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'read_more_style' => 'outline',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover Tab
        $this->start_controls_tab(
            'tab_btn_hover',
            [
                'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'btn_hover_text_color',
            [
                'label'     => esc_html__( 'Hover Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-blog-read-more:hover' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_bg_color',
            [
                'label'     => esc_html__( 'Hover Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-blog-read-more.btn-style-button:hover, {{WRAPPER}} .ua-blog-read-more.btn-style-outline:hover' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'read_more_style!' => 'text',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_border_color',
            [
                'label'     => esc_html__( 'Hover Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-blog-read-more.btn-style-outline:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'read_more_style' => 'outline',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'btn_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-blog-read-more' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'read_more_style!' => 'text',
                ],
                'separator'  => 'before',
            ]
        );

        $this->add_responsive_control(
            'btn_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-blog-read-more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'read_more_style!' => 'text',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Filter Bar Section
     */
    protected function style_filter_controls() {
        $this->start_controls_section(
            'section_style_filter',
            [
                'label'     => esc_html__( 'Category Filter Bar', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_filter' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'filter_btn_color',
            [
                'label'     => esc_html__( 'Button Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#64748b',
                'selectors' => [
                    '{{WRAPPER}} .ua-blog-filter-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'filter_btn_bg',
            [
                'label'     => esc_html__( 'Button Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f1f5f9',
                'selectors' => [
                    '{{WRAPPER}} .ua-blog-filter-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'filter_btn_active_color',
            [
                'label'     => esc_html__( 'Active Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-blog-filter-btn.active' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'filter_btn_active_bg',
            [
                'label'     => esc_html__( 'Active Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0fc392',
                'selectors' => [
                    '{{WRAPPER}} .ua-blog-filter-btn.active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'filter_typography',
                'selector' => '{{WRAPPER}} .ua-blog-filter-btn',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Pagination Section
     */
    protected function style_pagination_controls() {
        $this->start_controls_section(
            'section_style_pagination',
            [
                'label'     => esc_html__( 'Pagination & Load More', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'pagination_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'pag_color',
            [
                'label'     => esc_html__( 'Active / Accent Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0fc392',
                'selectors' => [
                    '{{WRAPPER}} .ua-blog-pagination .page-numbers.current' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-blog-load-more-btn'                   => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Build WP_Query Arguments from Settings.
     */
    protected function build_query_args( $settings, $paged = 1 ) {
        $posts_per_page = ! empty( $settings['posts_per_page'] ) ? (int) $settings['posts_per_page'] : 6;
        $offset         = ! empty( $settings['offset'] ) ? (int) $settings['offset'] : 0;
        $orderby        = ! empty( $settings['orderby'] ) ? sanitize_key( $settings['orderby'] ) : 'date';
        $order          = ! empty( $settings['order'] ) ? sanitize_key( $settings['order'] ) : 'DESC';

        $args = [
            'post_type'           => 'post',
            'post_status'         => 'publish',
            'posts_per_page'      => $posts_per_page,
            'paged'               => $paged,
            'orderby'             => $orderby,
            'order'               => $order,
            'ignore_sticky_posts' => ! empty( $settings['ignore_sticky_posts'] ) && 'yes' === $settings['ignore_sticky_posts'],
        ];

        // Handle Offset
        if ( $offset > 0 && 1 === (int) $paged ) {
            $args['offset'] = $offset;
        }

        // Exclude current post
        if ( ! empty( $settings['exclude_current'] ) && 'yes' === $settings['exclude_current'] && is_singular() ) {
            $args['post__not_in'] = [ get_the_ID() ];
        }

        // Active AJAX Filter Category
        if ( ! empty( $settings['active_category'] ) ) {
            $args['category_name'] = sanitize_title( $settings['active_category'] );
        } elseif ( ! empty( $settings['query_source'] ) ) {
            if ( 'category' === $settings['query_source'] && ! empty( $settings['categories'] ) ) {
                $args['category__in'] = (array) $settings['categories'];
            } elseif ( 'tag' === $settings['query_source'] && ! empty( $settings['tags'] ) ) {
                $args['tag__in'] = (array) $settings['tags'];
            } elseif ( 'author' === $settings['query_source'] && ! empty( $settings['authors'] ) ) {
                $args['author__in'] = (array) $settings['authors'];
            }
        }

        return $args;
    }

    /**
     * Render Widget Output on Frontend.
     */
    protected function render() {
        wp_enqueue_script( 'ultraaddons-recent-blog' );
        $settings = $this->get_settings_for_display();
        $query_args = $this->build_query_args( $settings, 1 );
        $query      = new \WP_Query( $query_args );

        $layout_type = ! empty( $settings['layout_type'] ) ? sanitize_key( $settings['layout_type'] ) : 'grid';

        // Prepare Frontend JSON Settings for AJAX
        $frontend_settings = [
            'layout_type'        => $layout_type,
            'posts_per_page'     => $settings['posts_per_page'] ?? 6,
            'orderby'            => $settings['orderby'] ?? 'date',
            'order'              => $settings['order'] ?? 'DESC',
            'query_source'       => $settings['query_source'] ?? 'recent',
            'categories'         => $settings['categories'] ?? [],
            'tags'               => $settings['tags'] ?? [],
            'authors'            => $settings['authors'] ?? [],
            'image_size'         => $settings['image_size'] ?? 'medium_large',
            'show_badge'         => $settings['show_badge'] ?? 'yes',
            'show_meta'          => $settings['show_meta'] ?? 'yes',
            'meta_author'        => $settings['meta_author'] ?? 'yes',
            'meta_date'          => $settings['meta_date'] ?? 'yes',
            'meta_comments'      => $settings['meta_comments'] ?? 'yes',
            'meta_reading_time'  => $settings['meta_reading_time'] ?? 'yes',
            'show_title'         => $settings['show_title'] ?? 'yes',
            'title_tag'          => $settings['title_tag'] ?? 'h3',
            'show_excerpt'       => $settings['show_excerpt'] ?? 'yes',
            'excerpt_length'     => $settings['excerpt_length'] ?? 18,
            'show_read_more'     => $settings['show_read_more'] ?? 'yes',
            'read_more_text'     => $settings['read_more_text'] ?? esc_html__( 'Read More', 'ultraaddons-elementor-lite' ),
            'read_more_style'    => $settings['read_more_style'] ?? 'text',
            'pagination_type'    => $settings['pagination_type'] ?? 'none',
        ];

        $encoded_settings = htmlspecialchars( wp_json_encode( $frontend_settings ), ENT_QUOTES, 'UTF-8' );

        $filter_style = ! empty( $settings['filter_style'] ) ? 'filter-style-' . sanitize_key( $settings['filter_style'] ) : 'filter-style-pill';
        $zoom_class   = ( ! empty( $settings['image_hover_effect'] ) && 'yes' === $settings['image_hover_effect'] ) ? 'ua-blog-zoom-yes' : 'ua-blog-zoom-no';
        ?>
        <div class="ua-recent-blog-wrapper layout-<?php echo esc_attr( $layout_type ); ?> <?php echo esc_attr( $zoom_class ); ?>" data-settings="<?php echo esc_attr( $encoded_settings ); ?>">

            <!-- Optional Live AJAX Category Filter Bar -->
            <?php if ( ! empty( $settings['show_filter'] ) && 'yes' === $settings['show_filter'] ) : ?>
                <div class="ua-blog-filter-bar <?php echo esc_attr( $filter_style ); ?>">
                    <button class="ua-blog-filter-btn active" data-category="">
                        <?php esc_html_e( 'All', 'ultraaddons-elementor-lite' ); ?>
                    </button>
                    <?php
                    $filter_categories = get_categories( [ 'hide_empty' => true ] );
                    foreach ( $filter_categories as $cat ) :
                        ?>
                        <button class="ua-blog-filter-btn" data-category="<?php echo esc_attr( $cat->slug ); ?>">
                            <?php echo esc_html( $cat->name ); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Post Grid Container -->
            <div class="ua-recent-blog-grid">
                <?php
                if ( $query->have_posts() ) :
                    while ( $query->have_posts() ) :
                        $query->the_post();
                        $this->render_single_card( get_post(), $settings );
                    endwhile;
                else :
                    ?>
                    <p class="ua-no-posts-found"><?php esc_html_e( 'No posts found.', 'ultraaddons-elementor-lite' ); ?></p>
                <?php
                endif;
                wp_reset_postdata();
                ?>
            </div>

            <!-- Pagination / Load More -->
            <?php if ( ! empty( $settings['pagination_type'] ) && 'none' !== $settings['pagination_type'] && $query->max_num_pages > 1 ) : ?>
                <div class="ua-blog-pagination-wrapper">
                    <?php if ( 'numbered' === $settings['pagination_type'] ) : ?>
                        <div class="ua-blog-pagination">
                            <?php
                            echo paginate_links( [
                                'total'     => $query->max_num_pages,
                                'current'   => 1,
                                'type'      => 'plain',
                                'prev_text' => '<i class="fas fa-angle-left"></i>',
                                'next_text' => '<i class="fas fa-angle-right"></i>',
                            ] );
                            ?>
                        </div>
                    <?php elseif ( 'load_more' === $settings['pagination_type'] ) : ?>
                        <button class="ua-blog-load-more-btn" data-page="1" data-max-pages="<?php echo esc_attr( $query->max_num_pages ); ?>">
                            <span class="ua-btn-text"><?php echo esc_html( $settings['load_more_text'] ?? esc_html__( 'Load More Posts', 'ultraaddons-elementor-lite' ) ); ?></span>
                            <i class="fas fa-spinner fa-spin ua-btn-spinner" style="display: none;"></i>
                        </button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>
        <?php
    }

    /**
     * Render Single Post Card Markup.
     */
    public function render_single_card( $post, $settings ) {
        if ( ! $post instanceof \WP_Post ) {
            return;
        }

        $post_id   = $post->ID;
        $permalink = get_permalink( $post_id );
        $title     = get_the_title( $post_id );

        // Thumbnail
        $thumb_size = ! empty( $settings['image_size'] ) ? sanitize_key( $settings['image_size'] ) : 'medium_large';
        $thumb_url  = has_post_thumbnail( $post_id )
            ? get_the_post_thumbnail_url( $post_id, $thumb_size )
            : ULTRA_ADDONS_URL . 'assets/images/no-image.png';

        // Reading Time Calculation
        $word_count   = str_word_count( strip_tags( $post->post_content ) );
        $reading_time = max( 1, (int) ceil( $word_count / 200 ) );

        // Primary Category for Badge
        $categories = get_the_category( $post_id );
        $primary_cat = ! empty( $categories ) ? $categories[0] : null;

        $show_badge       = ! empty( $settings['show_badge'] ) && 'yes' === $settings['show_badge'];
        $show_meta        = ! empty( $settings['show_meta'] ) && 'yes' === $settings['show_meta'];
        $show_title       = ! empty( $settings['show_title'] ) && 'yes' === $settings['show_title'];
        $title_tag        = ! empty( $settings['title_tag'] ) ? sanitize_key( $settings['title_tag'] ) : 'h3';
        $show_excerpt     = ! empty( $settings['show_excerpt'] ) && 'yes' === $settings['show_excerpt'];
        $excerpt_length   = ! empty( $settings['excerpt_length'] ) ? (int) $settings['excerpt_length'] : 18;
        $show_read_more   = ! empty( $settings['show_read_more'] ) && 'yes' === $settings['show_read_more'];
        $read_more_style  = ! empty( $settings['read_more_style'] ) ? sanitize_key( $settings['read_more_style'] ) : 'text';
        ?>
        <article class="ua-blog-card">

            <!-- Thumbnail & Badge -->
            <div class="ua-blog-thumb-wrap">
                <a href="<?php echo esc_url( $permalink ); ?>" class="ua-blog-thumb-link">
                    <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy" />
                </a>

                <?php if ( $show_badge && $primary_cat ) : ?>
                    <a href="<?php echo esc_url( get_category_link( $primary_cat->term_id ) ); ?>" class="ua-blog-badge">
                        <?php echo esc_html( $primary_cat->name ); ?>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Content Body -->
            <div class="ua-blog-content">

                <!-- Meta Info -->
                <?php if ( $show_meta ) : ?>
                    <div class="ua-blog-meta">
                        <?php if ( ! empty( $settings['meta_author'] ) && 'yes' === $settings['meta_author'] ) : ?>
                            <span class="ua-blog-meta-item ua-meta-author">
                                <i class="far fa-user"></i>
                                <?php echo esc_html( get_the_author_meta( 'display_name', $post->post_author ) ); ?>
                            </span>
                        <?php endif; ?>

                        <?php if ( ! empty( $settings['meta_date'] ) && 'yes' === $settings['meta_date'] ) : ?>
                            <span class="ua-blog-meta-item ua-meta-date">
                                <i class="far fa-calendar-alt"></i>
                                <?php echo esc_html( get_the_date( '', $post_id ) ); ?>
                            </span>
                        <?php endif; ?>

                        <?php if ( ! empty( $settings['meta_comments'] ) && 'yes' === $settings['meta_comments'] ) : ?>
                            <span class="ua-blog-meta-item ua-meta-comments">
                                <i class="far fa-comments"></i>
                                <?php echo esc_html( get_comments_number( $post_id ) ); ?>
                            </span>
                        <?php endif; ?>

                        <?php if ( ! empty( $settings['meta_reading_time'] ) && 'yes' === $settings['meta_reading_time'] ) : ?>
                            <span class="ua-blog-meta-item ua-meta-read-time">
                                <i class="far fa-clock"></i>
                                <?php printf( esc_html__( '%d min read', 'ultraaddons-elementor-lite' ), $reading_time ); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Title -->
                <?php if ( $show_title ) : ?>
                    <<?php echo esc_attr( $title_tag ); ?> class="ua-blog-title">
                        <a href="<?php echo esc_url( $permalink ); ?>">
                            <?php echo esc_html( $title ); ?>
                        </a>
                    </<?php echo esc_attr( $title_tag ); ?>>
                <?php endif; ?>

                <!-- Excerpt -->
                <?php if ( $show_excerpt ) : ?>
                    <div class="ua-blog-excerpt">
                        <?php
                        $excerpt = get_the_excerpt( $post_id );
                        echo esc_html( wp_trim_words( $excerpt, $excerpt_length, '...' ) );
                        ?>
                    </div>
                <?php endif; ?>

                <!-- Card Footer & Read More -->
                <?php if ( $show_read_more ) : ?>
                    <div class="ua-blog-footer">
                        <a href="<?php echo esc_url( $permalink ); ?>" class="ua-blog-read-more btn-style-<?php echo esc_attr( $read_more_style ); ?>">
                            <span><?php echo esc_html( $settings['read_more_text'] ?? esc_html__( 'Read More', 'ultraaddons-elementor-lite' ) ); ?></span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                <?php endif; ?>

            </div>

        </article>
        <?php
    }

    /**
     * AJAX Handler to Load Posts for Category Filter & Pagination.
     */
    public static function ajax_load_posts() {
        check_ajax_referer( 'ua-recent-blog-nonce', 'nonce' );

        $paged    = ! empty( $_POST['paged'] ) ? (int) $_POST['paged'] : 1;
        $category = ! empty( $_POST['category'] ) ? sanitize_title( wp_unslash( $_POST['category'] ) ) : '';
        $raw      = ! empty( $_POST['settings'] ) ? wp_unslash( $_POST['settings'] ) : '{}';
        $settings = json_decode( $raw, true );

        if ( ! is_array( $settings ) ) {
            $settings = [];
        }

        if ( ! empty( $category ) ) {
            $settings['active_category'] = $category;
        } else {
            unset( $settings['active_category'] );
        }

        $widget     = new self();
        $query_args = $widget->build_query_args( $settings, $paged );
        $query      = new \WP_Query( $query_args );

        ob_start();
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $widget->render_single_card( get_post(), $settings );
            }
        } else {
            echo '<p class="ua-no-posts-found">' . esc_html__( 'No more posts found.', 'ultraaddons-elementor-lite' ) . '</p>';
        }
        $html = ob_get_clean();
        wp_reset_postdata();

        // Generate Pagination HTML
        $pagination_html = '';
        if ( $query->max_num_pages > 1 ) {
            $pagination_type = ! empty( $settings['pagination_type'] ) ? $settings['pagination_type'] : 'numbered';
            if ( 'numbered' === $pagination_type ) {
                $pagination_links = paginate_links( [
                    'total'     => $query->max_num_pages,
                    'current'   => $paged,
                    'type'      => 'plain',
                    'prev_text' => '<i class="fas fa-angle-left"></i>',
                    'next_text' => '<i class="fas fa-angle-right"></i>',
                ] );
                $pagination_html = '<div class="ua-blog-pagination">' . $pagination_links . '</div>';
            } elseif ( 'load_more' === $pagination_type && $paged < $query->max_num_pages ) {
                $load_more_text  = $settings['load_more_text'] ?? esc_html__( 'Load More Posts', 'ultraaddons-elementor-lite' );
                $pagination_html = '<button class="ua-blog-load-more-btn" data-page="' . esc_attr( $paged ) . '" data-max-pages="' . esc_attr( $query->max_num_pages ) . '">
                    <span class="ua-btn-text">' . esc_html( $load_more_text ) . '</span>
                    <i class="fas fa-spinner fa-spin ua-btn-spinner" style="display: none;"></i>
                </button>';
            }
        }

        wp_send_json_success( [
            'html'       => $html,
            'pagination' => $pagination_html,
            'max_pages'  => $query->max_num_pages,
            'paged'      => $paged,
        ] );
    }

    /**
     * Helper: Get Categories or Tags as options array.
     */
    protected function get_terms_options( $taxonomy = 'category' ) {
        $options = [];
        $terms   = get_terms( [
            'taxonomy'   => $taxonomy,
            'hide_empty' => false,
        ] );

        if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
            foreach ( $terms as $term ) {
                $options[ $term->term_id ] = $term->name;
            }
        }

        return $options;
    }

    /**
     * Helper: Get Authors as options array.
     */
    protected function get_authors_options() {
        $options = [];
        $users   = get_users( [
            'capability' => [ 'edit_posts' ],
            'number'     => 50,
        ] );

        if ( ! empty( $users ) ) {
            foreach ( $users as $user ) {
                $options[ $user->ID ] = $user->display_name;
            }
        }

        return $options;
    }
}
