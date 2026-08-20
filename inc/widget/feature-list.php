<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * UltraAddons Feature List Widget
 *
 * An interactive, responsive, and visually appealing Feature List widget.
 * Supports Left, Center, and Right layouts, Square & Rhombus icon shapes,
 * animated connector lines, customizable icons & images, and individual item styles.
 *
 * @since 1.2.0
 * @package UltraAddons
 */
class Feature_List extends Base {

    /**
     * Constructor — register style dependency
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        wp_register_style(
            'ultraaddons-feature-list',
            ULTRA_ADDONS_ASSETS . 'css/widgets/feature-list.css',
            [],
            ULTRA_ADDONS_VERSION,
            'all'
        );
        wp_enqueue_style( 'ultraaddons-feature-list' );
    }

    /**
     * Style dependency
     */
    public function get_style_depends() {
        return [ 'ultraaddons-feature-list' ];
    }

    /**
     * Widget Title
     */
    public function get_title() {
        return esc_html__( 'Feature List', 'ultraaddons-elementor-lite' );
    }

    /**
     * Widget Icon
     */
    public function get_icon() {
        return 'ultraaddons eicon-editor-list-ul';
    }

    /**
     * Widget Keywords
     */
    public function get_keywords() {
        return [ 'ultraaddons-elementor-lite', 'ua', 'features', 'feature list', 'icon list', 'services list', 'info list' ];
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        // Content Tab
        $this->content_general_controls();
        $this->content_list_controls();

        // Style Tab
        $this->style_general_controls();
        $this->style_media_controls();
        $this->style_line_controls();
        $this->style_title_description_controls();
    }

    /*==========================================================================
     * CONTENT TAB — General Settings
     *========================================================================*/
    protected function content_general_controls() {
        $this->start_controls_section(
            'ua_section_feature_list_general',
            [
                'label' => esc_html__( 'General', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'ua_feature_list_layout',
            [
                'label'        => esc_html__( 'Layout', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::CHOOSE,
                'label_block'  => false,
                'default'      => 'left',
                'options'      => [
                    'left'   => [
                        'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'prefix_class' => 'ua-feature-list-',
                'render_type'  => 'template',
                'selectors'    => [
                    '{{WRAPPER}} .ua-feature-list-item' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_feature_list_icon_vertical_align',
            [
                'label'       => esc_html__( 'Vertical Align', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default'     => 'center',
                'options'     => [
                    'flex-start' => [
                        'title' => esc_html__( 'Top', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'center'     => [
                        'title' => esc_html__( 'Middle', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-v-align-middle',
                    ],
                    'flex-end'   => [
                        'title' => esc_html__( 'Bottom', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ],
                ],
                'render_type' => 'template',
                'selectors'   => [
                    '{{WRAPPER}} .ua-feature-list-left .ua-feature-list-item, {{WRAPPER}}.ua-feature-list-left .ua-feature-list-item, {{WRAPPER}}[class*="ua-feature-list-"][class*="left"] .ua-feature-list-item'  => 'align-items: {{VALUE}};',
                    '{{WRAPPER}} .ua-feature-list-right .ua-feature-list-item, {{WRAPPER}}.ua-feature-list-right .ua-feature-list-item, {{WRAPPER}}[class*="ua-feature-list-"][class*="right"] .ua-feature-list-item' => 'align-items: {{VALUE}};',
                ],
                'condition'   => [
                    'ua_feature_list_layout!' => 'center',
                ],
            ]
        );

        $this->add_control(
            'ua_feature_list_content_alignment',
            [
                'label'        => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::CHOOSE,
                'options'      => [
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
                'prefix_class' => 'ua-feature-list-align-',
                'render_type'  => 'template',
                'default'      => 'center',
                'selectors'    => [
                    '{{WRAPPER}} .ua-feature-list-item' => 'align-items: {{VALUE}};',
                ],
                'condition'    => [
                    'ua_feature_list_layout' => 'center',
                ],
            ]
        );

        $this->add_control(
            'ua_feature_list_icon_shape',
            [
                'label'        => esc_html__( 'Icon Shape', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SELECT,
                'default'      => 'square',
                'label_block'  => false,
                'options'      => [
                    'square'  => esc_html__( 'Square', 'ultraaddons-elementor-lite' ),
                    'circle'  => esc_html__( 'Circle', 'ultraaddons-elementor-lite' ),
                    'rhombus' => esc_html__( 'Rhombus', 'ultraaddons-elementor-lite' ),
                ],
                'separator'    => 'before',
                'prefix_class' => 'ua-feature-list-',
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'    => 'thumbnail',
                'exclude' => [ 'custom' ],
                'include' => [],
                'default' => 'large',
            ]
        );

        $this->add_control(
            'ua_feature_list_title_tag',
            [
                'label'   => esc_html__( 'Title HTML Tag', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'h1'   => 'H1',
                    'h2'   => 'H2',
                    'h3'   => 'H3',
                    'h4'   => 'H4',
                    'h5'   => 'H5',
                    'h6'   => 'H6',
                    'div'  => 'DIV',
                    'span' => 'SPAN',
                    'p'    => 'P',
                ],
                'default' => 'h2',
            ]
        );

        $this->add_control(
            'ua_feature_list_line',
            [
                'label'        => esc_html__( 'Show Line', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'render_type'  => 'template',
                'prefix_class' => 'ua-feature-list-line-',
                'separator'    => 'before',
                'default'      => 'yes',
                'return_value' => 'yes',
                'condition'    => [
                    'ua_feature_list_layout' => [ 'left', 'right' ],
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_feature_list_item_spacing_v',
            [
                'label'       => esc_html__( 'Vertical Spacing', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => [ 'px' ],
                'range'       => [
                    'px' => [ 'min' => 0, 'max' => 100 ],
                ],
                'default'     => [
                    'unit' => 'px',
                    'size' => 35,
                ],
                'selectors'   => [
                    '{{WRAPPER}} .ua-feature-list-item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-feature-list-line'                  => 'height: calc(100% + {{SIZE}}{{UNIT}});',
                ],
                'render_type' => 'template',
                'separator'   => 'before',
            ]
        );

        $this->add_responsive_control(
            'ua_feature_list_item_spacing_h',
            [
                'label'      => esc_html__( 'Horizontal Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 100 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 25,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-feature-list-left .ua-feature-list-icon-wrap, {{WRAPPER}}.ua-feature-list-left .ua-feature-list-icon-wrap, {{WRAPPER}}[class*="ua-feature-list-"][class*="left"] .ua-feature-list-icon-wrap'  => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-feature-list-right .ua-feature-list-icon-wrap, {{WRAPPER}}.ua-feature-list-right .ua-feature-list-icon-wrap, {{WRAPPER}}[class*="ua-feature-list-"][class*="right"] .ua-feature-list-icon-wrap' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'ua_feature_list_layout!' => 'center',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_feature_list_title_distance',
            [
                'label'       => esc_html__( 'Title Distance', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => [ 'px' ],
                'range'       => [
                    'px' => [ 'min' => 0, 'max' => 100 ],
                ],
                'default'     => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'selectors'   => [
                    '{{WRAPPER}} .ua-feature-list-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'ua_feature_list_media_distance',
            [
                'label'      => esc_html__( 'Media Distance', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 100 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-feature-list-icon-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'ua_feature_list_layout' => 'center',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * CONTENT TAB — Repeater List
     *========================================================================*/
    protected function content_list_controls() {
        $this->start_controls_section(
            'ua_section_feature_list_content',
            [
                'label' => esc_html__( 'Content', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->start_controls_tabs( 'ua_list_tabs' );

        // Tab: Content (Inside Item)
        $repeater->start_controls_tab(
            'ua_item_content_tab',
            [
                'label' => esc_html__( 'Content', 'ultraaddons-elementor-lite' ),
            ]
        );

        $repeater->add_control(
            'ua_feature_list_media_type',
            [
                'label'       => esc_html__( 'Media Type', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'options'     => [
                    'icon'  => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                    'image' => esc_html__( 'Image', 'ultraaddons-elementor-lite' ),
                ],
                'default'     => 'icon',
                'label_block' => false,
            ]
        );

        $repeater->add_control(
            'ua_list_icon',
            [
                'label'       => esc_html__( 'Select Icon', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::ICONS,
                'default'     => [
                    'value'   => 'fas fa-star',
                    'library' => 'solid',
                ],
                'label_block' => false,
                'condition'   => [
                    'ua_feature_list_media_type' => 'icon',
                ],
            ]
        );

        $repeater->add_control(
            'ua_list_image',
            [
                'label'     => esc_html__( 'Choose Image', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::MEDIA,
                'dynamic'   => [
                    'active' => true,
                ],
                'default'   => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'ua_feature_list_media_type' => 'image',
                ],
            ]
        );

        $repeater->add_control(
            'ua_list_title',
            [
                'label'       => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => [
                    'active' => true,
                ],
                'default'     => esc_html__( 'List Title', 'ultraaddons-elementor-lite' ),
                'separator'   => 'before',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'ua_list_title_url',
            [
                'label'       => esc_html__( 'Title Link', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'dynamic'     => [
                    'active' => true,
                ],
                'placeholder' => esc_html__( 'https://your-link.com', 'ultraaddons-elementor-lite' ),
                'default'     => [
                    'url'         => '',
                    'is_external' => true,
                    'nofollow'    => true,
                ],
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'ua_list_content',
            [
                'label'       => esc_html__( 'Content', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXTAREA,
                'dynamic'     => [
                    'active' => true,
                ],
                'default'     => esc_html__( 'List Content', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'Type your description here', 'ultraaddons-elementor-lite' ),
                'rows'        => 8,
            ]
        );

        $repeater->end_controls_tab();

        // Tab: Style (Inside Item)
        $repeater->start_controls_tab(
            'ua_item_styles_tab',
            [
                'label' => esc_html__( 'Style', 'ultraaddons-elementor-lite' ),
            ]
        );

        $repeater->add_control(
            'ua_feature_list_custom_styles',
            [
                'label'        => esc_html__( 'Custom Styles', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $repeater->add_control(
            'ua_feature_list_title_color_unique',
            [
                'label'     => esc_html__( 'Title Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .ua-feature-list-title'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} {{CURRENT_ITEM}} .ua-feature-list-title a' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'ua_feature_list_custom_styles' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'ua_feature_list_icon_color_unique',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .ua-feature-list-icon-inner-wrap i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} {{CURRENT_ITEM}} .ua-feature-list-icon-inner-wrap svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'ua_feature_list_custom_styles' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'ua_feature_list_icon_wrapper_bg_color_unique',
            [
                'label'     => esc_html__( 'Icon Bg Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#00B4D8',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .ua-feature-list-icon-inner-wrap' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'ua_feature_list_custom_styles' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'ua_feature_list_icon_wrapper_border_color_unique',
            [
                'label'     => esc_html__( 'Icon Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#3872FA',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .ua-feature-list-icon-inner-wrap' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'ua_feature_list_custom_styles' => 'yes',
                ],
            ]
        );

        $repeater->end_controls_tab();

        $repeater->end_controls_tabs();

        $this->add_control(
            'ua_feature_list',
            [
                'label'       => esc_html__( 'Repeater List', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'ua_list_title'   => esc_html__( 'Feature List', 'ultraaddons-elementor-lite' ),
                        'ua_list_content' => esc_html__( 'Add multiple feature items, set different icons or images for each feature and also give custom links if needed.', 'ultraaddons-elementor-lite' ),
                        'ua_list_icon'    => [
                            'value'   => 'fas fa-rocket',
                            'library' => 'solid',
                        ],
                    ],
                    [
                        'ua_list_title'                                => esc_html__( 'Key Features', 'ultraaddons-elementor-lite' ),
                        'ua_list_content'                              => esc_html__( 'Choose your style from three different layouts and two unique icon background shapes.', 'ultraaddons-elementor-lite' ),
                        'ua_list_icon'                                 => [
                            'value'   => 'far fa-flag',
                            'library' => 'solid',
                        ],
                        'ua_feature_list_custom_styles'                => 'yes',
                        'ua_feature_list_icon_wrapper_bg_color_unique' => '#00B4D8',
                    ],
                    [
                        'ua_list_title'   => esc_html__( 'Connector Line', 'ultraaddons-elementor-lite' ),
                        'ua_list_content' => esc_html__( 'Show a connector line between each icon, changes its color and style to fit your unique design.', 'ultraaddons-elementor-lite' ),
                        'ua_list_icon'    => [
                            'value'   => 'fas fa-grip-lines-vertical',
                            'library' => 'solid',
                        ],
                    ],
                    [
                        'ua_list_title'   => esc_html__( 'Custom Styles', 'ultraaddons-elementor-lite' ),
                        'ua_list_content' => esc_html__( 'Easily customize every aspect of your list from widget styles but also you can give custom colors to each item as well.', 'ultraaddons-elementor-lite' ),
                        'ua_list_icon'    => [
                            'value'   => 'fas fa-paint-brush',
                            'library' => 'solid',
                        ],
                    ],
                ],
                'title_field' => '{{{ ua_list_title }}}',
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — General / List Spacing
     *========================================================================*/
    protected function style_general_controls() {
        $this->start_controls_section(
            'ua_section_feature_list_general_styles',
            [
                'label' => esc_html__( 'General / List', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'ua_feature_list_wrap_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default'    => [
                    'top'      => '15',
                    'right'    => '15',
                    'bottom'   => '15',
                    'left'     => '15',
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-feature-list-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_feature_list_wrap_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-feature-list-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_feature_list_wrap_border',
                'selector' => '{{WRAPPER}} .ua-feature-list-wrap',
            ]
        );

        $this->add_responsive_control(
            'ua_feature_list_wrap_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-feature-list-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_feature_list_wrap_shadow',
                'selector' => '{{WRAPPER}} .ua-feature-list-wrap',
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Media (Icon / Image Box)
     *========================================================================*/
    protected function style_media_controls() {
        $this->start_controls_section(
            'ua_section_feature_list_icon_styles',
            [
                'label' => esc_html__( 'Media', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'ua_feature_list_icon_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-feature-list-icon-inner-wrap i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-feature-list-icon-inner-wrap svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_feature_list_icon_wrapper_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#3872FA',
                'selectors' => [
                    '{{WRAPPER}} .ua-feature-list-icon-inner-wrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_feature_list_icon_wrapper_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .ua-feature-list-icon-inner-wrap' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_feature_list_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [ 'min' => 5, 'max' => 100 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 25,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-feature-list-icon-wrap i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-feature-list-icon-wrap svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
                'separator'  => 'before',
            ]
        );

        $this->add_responsive_control(
            'ua_feature_list_icon_wrapper_size',
            [
                'label'       => esc_html__( 'Box Size', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SLIDER,
                'render_type' => 'template',
                'range'       => [
                    'px' => [ 'min' => 5, 'max' => 200 ],
                ],
                'default'     => [
                    'unit' => 'px',
                    'size' => 75,
                ],
                'selectors'   => [
                    '{{WRAPPER}} .ua-feature-list-icon-inner-wrap' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ua_feature_list_icon_wrapper_border_type',
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
                    '{{WRAPPER}} .ua-feature-list-icon-inner-wrap' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'ua_feature_list_icon_wrapper_border_width',
            [
                'label'      => esc_html__( 'Border Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default'    => [
                    'top'    => 1,
                    'right'  => 1,
                    'bottom' => 1,
                    'left'   => 1,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-feature-list-icon-inner-wrap' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'ua_feature_list_icon_wrapper_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'ua_feature_list_icon_wrapper_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default'    => [
                    'top'    => 5,
                    'right'  => 5,
                    'bottom' => 5,
                    'left'   => 5,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-feature-list-icon-inner-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_feature_list_icon_wrapper_shadow',
                'selector' => '{{WRAPPER}} .ua-feature-list-icon-inner-wrap',
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Line (Connector)
     *========================================================================*/
    protected function style_line_controls() {
        $this->start_controls_section(
            'ua_section_feature_list_line_styles',
            [
                'label'     => esc_html__( 'Line', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ua_feature_list_line' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ua_feature_list_line_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#3872FA',
                'selectors' => [
                    '{{WRAPPER}} .ua-feature-list-line' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_feature_list_line_width',
            [
                'label'      => esc_html__( 'Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 1, 'max' => 10 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 2,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-feature-list-line' => 'border-left-width: {{SIZE}}{{UNIT}};',
                ],
                'separator'  => 'before',
            ]
        );

        $this->add_control(
            'ua_feature_list_line_border_type',
            [
                'label'     => esc_html__( 'Type', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'solid',
                'options'   => [
                    'solid'  => esc_html__( 'Solid', 'ultraaddons-elementor-lite' ),
                    'dashed' => esc_html__( 'Dashed', 'ultraaddons-elementor-lite' ),
                    'dotted' => esc_html__( 'Dotted', 'ultraaddons-elementor-lite' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-feature-list-line' => 'border-left-style: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Title & Description
     *========================================================================*/
    protected function style_title_description_controls() {
        $this->start_controls_section(
            'ua_section_feature_list_title_description_styles',
            [
                'label' => esc_html__( 'Title & Description', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'ua_title_heading',
            [
                'label'     => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'ua_feature_list_title_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .ua-feature-list-title'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-feature-list-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'ua_feature_list_title_typography',
                'selector'       => '{{WRAPPER}} .ua-feature-list-title, {{WRAPPER}} .ua-feature-list-title a',
                'fields_options' => [
                    'typography'  => [
                        'default' => 'custom',
                    ],
                    'font_weight' => [
                        'default' => '500',
                    ],
                    'font_family' => [
                        'default' => 'Roboto',
                    ],
                    'font_size'   => [
                        'default' => [
                            'size' => '20',
                            'unit' => 'px',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'ua_description_heading',
            [
                'label'     => esc_html__( 'Description', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'ua_feature_list_description_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#6E6B6B',
                'selectors' => [
                    '{{WRAPPER}} .ua-feature-list-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'ua_feature_list_description_typography',
                'selector'       => '{{WRAPPER}} .ua-feature-list-description',
                'fields_options' => [
                    'typography'  => [
                        'default' => 'custom',
                    ],
                    'font_weight' => [
                        'default' => '400',
                    ],
                    'font_family' => [
                        'default' => 'Roboto',
                    ],
                    'font_size'   => [
                        'default' => [
                            'size' => '14',
                            'unit' => 'px',
                        ],
                    ],
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * MAIN RENDER METHOD
     *========================================================================*/
    protected function render() {
        $settings = $this->get_settings_for_display();
        $items    = ! empty( $settings['ua_feature_list'] ) ? $settings['ua_feature_list'] : [];

        if ( empty( $items ) ) {
            return;
        }

        $title_tag = ! empty( $settings['ua_feature_list_title_tag'] ) ? Utils::validate_html_tag( $settings['ua_feature_list_title_tag'] ) : 'h2';
        $layout    = ! empty( $settings['ua_feature_list_layout'] ) ? $settings['ua_feature_list_layout'] : 'left';
        $shape     = ! empty( $settings['ua_feature_list_icon_shape'] ) ? $settings['ua_feature_list_icon_shape'] : 'square';
        $line      = ! empty( $settings['ua_feature_list_line'] ) ? 'yes' : 'no';

        $this->add_render_attribute( 'ua_feature_list_wrap', 'class', [
            'ua-feature-list-wrap',
            'ua-feature-list-' . esc_attr( $layout ),
            'ua-feature-list-' . esc_attr( $shape ),
            'ua-feature-list-line-' . esc_attr( $line ),
        ] );
        $this->add_render_attribute( 'ua_feature_list', 'class', 'ua-feature-list' );
        ?>
        <div <?php $this->print_render_attribute_string( 'ua_feature_list_wrap' ); ?>>
            <ul <?php $this->print_render_attribute_string( 'ua_feature_list' ); ?>>
                <?php foreach ( $items as $index => $item ) :
                    $item_key     = 'ua_feature_item_' . $index;
                    $url_key      = 'ua_feature_url_' . $index;
                    $repeater_id  = ! empty( $item['_id'] ) ? $item['_id'] : $index;
                    $media_type   = ! empty( $item['ua_feature_list_media_type'] ) ? $item['ua_feature_list_media_type'] : 'icon';
                    $item_title   = ! empty( $item['ua_list_title'] ) ? $item['ua_list_title'] : '';
                    $item_content = ! empty( $item['ua_list_content'] ) ? $item['ua_list_content'] : '';

                    $this->add_render_attribute( $item_key, [
                        'class' => [
                            'ua-feature-list-item',
                            'elementor-repeater-item-' . esc_attr( $repeater_id ),
                        ],
                    ] );

                    if ( ! empty( $item['ua_list_title_url']['url'] ) ) {
                        $this->add_link_attributes( $url_key, $item['ua_list_title_url'] );
                        $this->add_render_attribute( $url_key, 'class', 'ua-feature-list-url' );
                    }
                ?>
                    <li <?php $this->print_render_attribute_string( $item_key ); ?>>
                        <div class="ua-feature-list-icon-wrap">
                            <span class="ua-feature-list-line"></span>
                            <div class="ua-feature-list-icon-inner-wrap">
                                <?php if ( 'icon' === $media_type && ! empty( $item['ua_list_icon'] ) ) : ?>
                                    <?php Icons_Manager::render_icon( $item['ua_list_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                <?php elseif ( 'image' === $media_type && ! empty( $item['ua_list_image']['id'] ) ) : ?>
                                    <?php echo Group_Control_Image_Size::get_attachment_image_html( $item, 'thumbnail', 'ua_list_image' ); ?>
                                <?php elseif ( 'image' === $media_type && ! empty( $item['ua_list_image']['url'] ) ) : ?>
                                    <img src="<?php echo esc_url( $item['ua_list_image']['url'] ); ?>" alt="<?php echo esc_attr( $item_title ); ?>">
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="ua-feature-list-content-wrap">
                            <?php if ( ! empty( $item_title ) ) : ?>
                                <<?php echo esc_html( $title_tag ); ?> class="ua-feature-list-title">
                                    <?php if ( ! empty( $item['ua_list_title_url']['url'] ) ) : ?>
                                        <a <?php $this->print_render_attribute_string( $url_key ); ?>>
                                            <?php echo wp_kses_post( $item_title ); ?>
                                        </a>
                                    <?php else : ?>
                                        <?php echo wp_kses_post( $item_title ); ?>
                                    <?php endif; ?>
                                </<?php echo esc_html( $title_tag ); ?>>
                            <?php endif; ?>

                            <?php if ( ! empty( $item_content ) ) : ?>
                                <p class="ua-feature-list-description">
                                    <?php echo wp_kses_post( $item_content ); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
    }
}
