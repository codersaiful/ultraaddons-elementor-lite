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
use Elementor\Repeater;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * UltraAddons Stacked Cards Widget
 *
 * A modern, interactive stacked card component that creates an engaging
 * visual narrative on scroll or click with smooth stacking transitions,
 * multiple layout presets, custom media positions, and navigation dots.
 *
 * @since 1.1.0.9
 * @package UltraAddons
 */
class Stacked_Cards extends Base {

    /**
     * Constructor: Register widget scripts.
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        $js_file = ULTRA_ADDONS_DIR . 'assets/js/frontend-stacked-cards.js';
        $js_ver  = file_exists( $js_file ) ? filemtime( $js_file ) : ULTRA_ADDONS_VERSION;

        wp_register_script(
            'ultraaddons-stacked-cards-script',
            ULTRA_ADDONS_ASSETS . 'js/frontend-stacked-cards.js',
            [ 'jquery' ],
            $js_ver,
            true
        );
    }

    public function get_keywords() {
        return [ 'ultraaddons', 'stacked cards', 'cards', 'stack', 'card stack', 'interactive cards', 'sticky cards', 'scroll animation' ];
    }

    public function get_icon() {
        return 'ultraaddons eicon-posts-carousel';
    }

    public function get_script_depends() {
        return array_merge( parent::get_script_depends(), [ 'ultraaddons-stacked-cards-script' ] );
    }

    /**
     * Register widget controls.
     */
    protected function register_controls() {
        // CONTENT TAB
        $this->register_layout_controls();
        $this->register_cards_controls();
        $this->register_navigation_controls();

        // STYLE TAB
        $this->register_card_style_controls();
        $this->register_media_style_controls();
        $this->register_title_style_controls();
        $this->register_desc_style_controls();
        $this->register_button_style_controls();
        $this->register_nav_style_controls();
    }

    /* =========================================================================
       CONTENT TAB: Section 1 - Layout & Animation
       ========================================================================= */
    protected function register_layout_controls() {
        $this->start_controls_section(
            'section_ua_stkcards_layout',
            [
                'label' => esc_html__( 'Layout & Animation', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ua_stkcards_preset',
            [
                'label'       => esc_html__( 'Design Preset', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'classic',
                'options'     => [
                    'classic' => esc_html__( 'Classic Stack (Modern Sticky)', 'ultraaddons-elementor-lite' ),
                    'fan'     => esc_html__( 'Fan Spread (Slight Rotation)', 'ultraaddons-elementor-lite' ),
                    'slide'   => esc_html__( 'Slide Reveal (Smooth Fade & Slide)', 'ultraaddons-elementor-lite' ),
                ],
                'description' => esc_html__( 'Choose a stacking layout presentation.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_stkcards_effect',
            [
                'label'   => esc_html__( 'Stack Effect', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'scale',
                'options' => [
                    'scale'    => esc_html__( 'Scale Down Behind', 'ultraaddons-elementor-lite' ),
                    'slide-up' => esc_html__( 'Slide Up Cover', 'ultraaddons-elementor-lite' ),
                    'fade'     => esc_html__( 'Fade Transition', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_stkcards_sticky_top',
            [
                'label'      => esc_html__( 'Sticky Top Offset', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 300, 'step' => 5 ],
                    'vh' => [ 'min' => 0, 'max' => 50,  'step' => 1 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 100,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-stkcards-item' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_stkcards_stack_offset',
            [
                'label'      => esc_html__( 'Stack Card Offset', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 80, 'step' => 2 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 24,
                ],
                'description' => esc_html__( 'Vertical displacement between stacked cards.', 'ultraaddons-elementor-lite' ),
                'selectors'  => [
                    '{{WRAPPER}}' => '--ua-stkcards-offset: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_stkcards_min_height',
            [
                'label'      => esc_html__( 'Card Min Height', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh' ],
                'range'      => [
                    'px' => [ 'min' => 200, 'max' => 900, 'step' => 10 ],
                    'vh' => [ 'min' => 20,  'max' => 90,  'step' => 2 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 420,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-stkcards-item' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_stkcards_max_width',
            [
                'label'      => esc_html__( 'Stack Container Max Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'vw' ],
                'range'      => [
                    'px' => [ 'min' => 300, 'max' => 1400, 'step' => 10 ],
                    '%'  => [ 'min' => 50,  'max' => 100,  'step' => 1 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 880,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-stkcards-wrap' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       CONTENT TAB: Section 2 - Card Items (Repeater)
       ========================================================================= */
    protected function register_cards_controls() {
        $this->start_controls_section(
            'section_ua_stkcards_items',
            [
                'label' => esc_html__( 'Card Items', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->start_controls_tabs( 'ua_stkcards_item_tabs' );

        // Tab: Content
        $repeater->start_controls_tab(
            'tab_card_content',
            [ 'label' => esc_html__( 'Content', 'ultraaddons-elementor-lite' ) ]
        );

        $repeater->add_control(
            'card_title',
            [
                'label'       => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Innovative Solution', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'Enter card headline', 'ultraaddons-elementor-lite' ),
                'label_block' => true,
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $repeater->add_control(
            'title_tag',
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
                    'div'  => 'div',
                    'span' => 'span',
                    'p'    => 'p',
                ],
                'default' => 'h3',
            ]
        );

        $repeater->add_control(
            'card_subtitle',
            [
                'label'       => esc_html__( 'Badge / Subtitle', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'FEATURE 01', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'e.g. STEP 01 or NEW', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $repeater->add_control(
            'card_desc',
            [
                'label'       => esc_html__( 'Description', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::WYSIWYG,
                'default'     => esc_html__( 'Deliver seamless, responsive experiences that captivate your visitors and drive real conversion. Built with performance in mind.', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'Enter card description...', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $repeater->add_control(
            'btn_text',
            [
                'label'       => esc_html__( 'Button Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Learn More', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'e.g. Learn More', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $repeater->add_control(
            'btn_url',
            [
                'label'       => esc_html__( 'Button Link', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://example.com', 'ultraaddons-elementor-lite' ),
                'default'     => [
                    'url'         => '#',
                    'is_external' => false,
                    'nofollow'    => false,
                ],
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $repeater->end_controls_tab();

        // Tab: Media
        $repeater->start_controls_tab(
            'tab_card_media',
            [ 'label' => esc_html__( 'Media', 'ultraaddons-elementor-lite' ) ]
        );

        $repeater->add_control(
            'media_type',
            [
                'label'   => esc_html__( 'Media Type', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'none'  => [
                        'title' => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-ban',
                    ],
                    'image' => [
                        'title' => esc_html__( 'Image', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-image',
                    ],
                    'icon'  => [
                        'title' => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-star',
                    ],
                ],
                'default' => 'image',
                'toggle'  => false,
            ]
        );

        $repeater->add_control(
            'card_image',
            [
                'label'     => esc_html__( 'Choose Image', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::MEDIA,
                'default'   => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'dynamic'   => [ 'active' => true ],
                'condition' => [
                    'media_type' => 'image',
                ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'      => 'card_image_size',
                'default'   => 'large',
                'condition' => [
                    'media_type' => 'image',
                ],
            ]
        );

        $repeater->add_control(
            'card_icon',
            [
                'label'     => esc_html__( 'Choose Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'fas fa-layer-group',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'media_type' => 'icon',
                ],
            ]
        );

        $repeater->add_control(
            'media_position',
            [
                'label'       => esc_html__( 'Media Alignment', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'right',
                'options'     => [
                    'right' => esc_html__( 'Right Side (Text Left)', 'ultraaddons-elementor-lite' ),
                    'left'  => esc_html__( 'Left Side (Text Right)', 'ultraaddons-elementor-lite' ),
                    'top'   => esc_html__( 'Top (Stacked)', 'ultraaddons-elementor-lite' ),
                ],
                'condition'   => [
                    'media_type!' => 'none',
                ],
            ]
        );

        $repeater->end_controls_tab();

        // Tab: Card Color Theme
        $repeater->start_controls_tab(
            'tab_card_style',
            [ 'label' => esc_html__( 'Color Theme', 'ultraaddons-elementor-lite' ) ]
        );

        $repeater->add_control(
            'card_bg_color',
            [
                'label'     => esc_html__( 'Card Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_control(
            'card_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .ua-stkcards-title, {{WRAPPER}} {{CURRENT_ITEM}} .ua-stkcards-desc, {{WRAPPER}} {{CURRENT_ITEM}} .ua-stkcards-badge' => 'color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_control(
            'card_btn_bg',
            [
                'label'     => esc_html__( 'Button Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .ua-stkcards-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_control(
            'card_btn_color',
            [
                'label'     => esc_html__( 'Button Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .ua-stkcards-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $repeater->end_controls_tab();

        $repeater->end_controls_tabs();

        $this->add_control(
            'cards_list',
            [
                'label'       => esc_html__( 'Cards', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ card_title }}}',
                'default'     => [
                    [
                        'card_title'     => esc_html__( 'Next-Gen Performance', 'ultraaddons-elementor-lite' ),
                        'card_subtitle'  => esc_html__( 'PHASE 01', 'ultraaddons-elementor-lite' ),
                        'card_desc'      => esc_html__( 'Experience lightning-fast load times and seamless responsiveness across all screen sizes with optimized modern assets.', 'ultraaddons-elementor-lite' ),
                        'btn_text'       => esc_html__( 'Explore Speed', 'ultraaddons-elementor-lite' ),
                        'media_type'     => 'image',
                        'media_position' => 'right',
                        'card_bg_color'  => '#ffffff',
                    ],
                    [
                        'card_title'     => esc_html__( 'Pixel Perfect Design', 'ultraaddons-elementor-lite' ),
                        'card_subtitle'  => esc_html__( 'PHASE 02', 'ultraaddons-elementor-lite' ),
                        'card_desc'      => esc_html__( 'Carefully engineered layout components with exquisite typography, fluid micro-interactions, and harmonic color balance.', 'ultraaddons-elementor-lite' ),
                        'btn_text'       => esc_html__( 'View Showcase', 'ultraaddons-elementor-lite' ),
                        'media_type'     => 'image',
                        'media_position' => 'left',
                        'card_bg_color'  => '#f8fafc',
                    ],
                    [
                        'card_title'     => esc_html__( 'Seamless Conversions', 'ultraaddons-elementor-lite' ),
                        'card_subtitle'  => esc_html__( 'PHASE 03', 'ultraaddons-elementor-lite' ),
                        'card_desc'      => esc_html__( 'Drive user engagement and conversions with clear calls-to-action, sticky scrolling narrative, and tactile feedback.', 'ultraaddons-elementor-lite' ),
                        'btn_text'       => esc_html__( 'Start Today', 'ultraaddons-elementor-lite' ),
                        'media_type'     => 'image',
                        'media_position' => 'right',
                        'card_bg_color'  => '#ffffff',
                    ],
                ],
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       CONTENT TAB: Section 3 - Navigation & Indicators
       ========================================================================= */
    protected function register_navigation_controls() {
        $this->start_controls_section(
            'section_ua_stkcards_navigation',
            [
                'label' => esc_html__( 'Navigation & Indicators', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_dots',
            [
                'label'        => esc_html__( 'Show Navigation Dots', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'description'  => esc_html__( 'Displays clickable navigation indicator dots on the side.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'dots_position',
            [
                'label'     => esc_html__( 'Dots Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'right',
                'options'   => [
                    'right' => esc_html__( 'Right Side', 'ultraaddons-elementor-lite' ),
                    'left'  => esc_html__( 'Left Side', 'ultraaddons-elementor-lite' ),
                    'bottom' => esc_html__( 'Bottom Bar', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'show_dots' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_counter',
            [
                'label'        => esc_html__( 'Show Card Counter', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'description'  => esc_html__( 'Displays a "01 / 03" card progress counter.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE TAB: Section 1 - Card Box & Container
       ========================================================================= */
    protected function register_card_style_controls() {
        $this->start_controls_section(
            'section_ua_stkcards_style_card',
            [
                'label' => esc_html__( 'Card Container', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'card_global_bg',
                'label'    => esc_html__( 'Default Background', 'ultraaddons-elementor-lite' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-stkcards-item',
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label'      => esc_html__( 'Card Inner Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default'    => [
                    'top'      => '48',
                    'right'    => '48',
                    'bottom'   => '48',
                    'left'     => '48',
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-stkcards-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'card_border',
                'selector' => '{{WRAPPER}} .ua-stkcards-item',
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default'    => [
                    'top'      => '24',
                    'right'    => '24',
                    'bottom'   => '24',
                    'left'     => '24',
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-stkcards-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'card_box_shadow',
                'selector' => '{{WRAPPER}} .ua-stkcards-item',
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE TAB: Section 2 - Media (Image / Icon)
       ========================================================================= */
    protected function register_media_style_controls() {
        $this->start_controls_section(
            'section_ua_stkcards_style_media',
            [
                'label' => esc_html__( 'Media Style', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'media_width',
            [
                'label'      => esc_html__( 'Media Column Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px' ],
                'range'      => [
                    '%'  => [ 'min' => 20, 'max' => 70, 'step' => 1 ],
                    'px' => [ 'min' => 150, 'max' => 600, 'step' => 10 ],
                ],
                'default'    => [
                    'unit' => '%',
                    'size' => 45,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-stkcards-media' => 'flex: 0 0 {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'media_gap',
            [
                'label'      => esc_html__( 'Gap between Text & Media', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 10, 'max' => 80, 'step' => 2 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 40,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-stkcards-inner' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label'      => esc_html__( 'Image Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default'    => [
                    'top'      => '16',
                    'right'    => '16',
                    'bottom'   => '16',
                    'left'     => '16',
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-stkcards-media img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'image_shadow',
                'selector' => '{{WRAPPER}} .ua-stkcards-media img',
            ]
        );

        $this->add_control(
            'icon_heading',
            [
                'label'     => esc_html__( 'Icon Options', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 24, 'max' => 120, 'step' => 2 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 54,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-stkcards-icon-wrap' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-stkcards-icon-wrap svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-stkcards-icon-wrap' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-stkcards-icon-wrap svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_bg_color',
            [
                'label'     => esc_html__( 'Icon Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-stkcards-icon-wrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_box_size',
            [
                'label'      => esc_html__( 'Icon Container Box Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 40, 'max' => 200, 'step' => 4 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 96,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-stkcards-icon-wrap' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_box_radius',
            [
                'label'      => esc_html__( 'Icon Box Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'    => [
                    'top'      => '20',
                    'right'    => '20',
                    'bottom'   => '20',
                    'left'     => '20',
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-stkcards-icon-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE TAB: Section 3 - Title & Subtitle
       ========================================================================= */
    protected function register_title_style_controls() {
        $this->start_controls_section(
            'section_ua_stkcards_style_title',
            [
                'label' => esc_html__( 'Title & Subtitle', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Subtitle / Badge
        $this->add_control(
            'heading_subtitle_style',
            [
                'label' => esc_html__( 'Subtitle / Badge', 'ultraaddons-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .ua-stkcards-badge',
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label'     => esc_html__( 'Subtitle Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0284c7',
                'selectors' => [
                    '{{WRAPPER}} .ua-stkcards-badge' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'subtitle_bg',
            [
                'label'     => esc_html__( 'Subtitle Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(2, 132, 199, 0.08)',
                'selectors' => [
                    '{{WRAPPER}} .ua-stkcards-badge' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'subtitle_margin',
            [
                'label'      => esc_html__( 'Subtitle Margin Bottom', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 12,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-stkcards-badge-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .ua-stkcards-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Title Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0f172a',
                'selectors' => [
                    '{{WRAPPER}} .ua-stkcards-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label'      => esc_html__( 'Title Margin Bottom', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 16,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-stkcards-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE TAB: Section 4 - Description
       ========================================================================= */
    protected function register_desc_style_controls() {
        $this->start_controls_section(
            'section_ua_stkcards_style_desc',
            [
                'label' => esc_html__( 'Description', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'desc_typography',
                'selector' => '{{WRAPPER}} .ua-stkcards-desc, {{WRAPPER}} .ua-stkcards-desc p',
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label'     => esc_html__( 'Description Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#64748b',
                'selectors' => [
                    '{{WRAPPER}} .ua-stkcards-desc, {{WRAPPER}} .ua-stkcards-desc p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'desc_margin',
            [
                'label'      => esc_html__( 'Description Margin Bottom', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 24,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-stkcards-desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE TAB: Section 5 - Call to Action Button
       ========================================================================= */
    protected function register_button_style_controls() {
        $this->start_controls_section(
            'section_ua_stkcards_style_btn',
            [
                'label' => esc_html__( 'Action Button', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'btn_typography',
                'selector' => '{{WRAPPER}} .ua-stkcards-btn',
            ]
        );

        $this->add_responsive_control(
            'btn_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'top'      => '12',
                    'right'    => '28',
                    'bottom'   => '12',
                    'left'     => '28',
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-stkcards-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default'    => [
                    'top'      => '50',
                    'right'    => '50',
                    'bottom'   => '50',
                    'left'     => '50',
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-stkcards-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_ua_stkcards_btn' );

        // Normal
        $this->start_controls_tab(
            'tab_ua_stkcards_btn_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'btn_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-stkcards-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0f172a',
                'selectors' => [
                    '{{WRAPPER}} .ua-stkcards-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'btn_border',
                'selector' => '{{WRAPPER}} .ua-stkcards-btn',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'btn_shadow',
                'selector' => '{{WRAPPER}} .ua-stkcards-btn',
            ]
        );

        $this->end_controls_tab();

        // Hover
        $this->start_controls_tab(
            'tab_ua_stkcards_btn_hover',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'btn_hover_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-stkcards-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0284c7',
                'selectors' => [
                    '{{WRAPPER}} .ua-stkcards-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-stkcards-btn:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'btn_hover_shadow',
                'selector' => '{{WRAPPER}} .ua-stkcards-btn:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE TAB: Section 6 - Navigation Dots & Counter
       ========================================================================= */
    protected function register_nav_style_controls() {
        $this->start_controls_section(
            'section_ua_stkcards_style_nav',
            [
                'label'     => esc_html__( 'Navigation & Counter', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_dots' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'dot_size',
            [
                'label'      => esc_html__( 'Dot Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 6, 'max' => 24, 'step' => 1 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-stkcards-dot' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'dot_color',
            [
                'label'     => esc_html__( 'Inactive Dot Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(15, 23, 42, 0.25)',
                'selectors' => [
                    '{{WRAPPER}} .ua-stkcards-dot' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dot_active_color',
            [
                'label'     => esc_html__( 'Active Dot Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0284c7',
                'selectors' => [
                    '{{WRAPPER}} .ua-stkcards-dot.ua-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'heading_counter_style',
            [
                'label'     => esc_html__( 'Counter Style', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_counter' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'counter_typography',
                'selector'  => '{{WRAPPER}} .ua-stkcards-counter',
                'condition' => [
                    'show_counter' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'counter_color',
            [
                'label'     => esc_html__( 'Counter Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0f172a',
                'selectors' => [
                    '{{WRAPPER}} .ua-stkcards-counter' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_counter' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget frontend output.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $cards = ! empty( $settings['cards_list'] ) ? $settings['cards_list'] : [];
        if ( empty( $cards ) ) {
            return;
        }

        $preset     = ! empty( $settings['ua_stkcards_preset'] ) ? $settings['ua_stkcards_preset'] : 'classic';
        $effect     = ! empty( $settings['ua_stkcards_effect'] ) ? $settings['ua_stkcards_effect'] : 'scale';
        $show_dots  = ( 'yes' === ( $settings['show_dots'] ?? '' ) );
        $dots_pos   = ! empty( $settings['dots_position'] ) ? $settings['dots_position'] : 'right';
        $show_count = ( 'yes' === ( $settings['show_counter'] ?? '' ) );
        $total_cards = count( $cards );

        $wrap_classes = [
            'ua-stkcards-wrap',
            'ua-stkcards-preset-' . sanitize_html_class( $preset ),
            'ua-stkcards-effect-' . sanitize_html_class( $effect ),
            'ua-stkcards-dots-' . sanitize_html_class( $dots_pos ),
        ];
        ?>
        <div class="<?php echo esc_attr( implode( ' ', $wrap_classes ) ); ?>"
             data-preset="<?php echo esc_attr( $preset ); ?>"
             data-effect="<?php echo esc_attr( $effect ); ?>"
             data-count="<?php echo esc_attr( $total_cards ); ?>">

            <?php if ( $show_dots || $show_count ) : ?>
                <div class="ua-stkcards-nav-panel">
                    <?php if ( $show_count ) : ?>
                        <div class="ua-stkcards-counter">
                            <span class="ua-stkcards-counter-curr">01</span>
                            <span class="ua-stkcards-counter-sep">/</span>
                            <span class="ua-stkcards-counter-total"><?php echo esc_html( sprintf( '%02d', $total_cards ) ); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ( $show_dots ) : ?>
                        <div class="ua-stkcards-dots" role="tablist" aria-label="<?php esc_attr_e( 'Stacked cards navigation', 'ultraaddons-elementor-lite' ); ?>">
                            <?php foreach ( $cards as $idx => $card ) : ?>
                                <button type="button"
                                        class="ua-stkcards-dot <?php echo 0 === $idx ? 'ua-active' : ''; ?>"
                                        data-card-index="<?php echo esc_attr( $idx ); ?>"
                                        aria-label="<?php echo esc_attr( sprintf( __( 'Jump to card %d', 'ultraaddons-elementor-lite' ), $idx + 1 ) ); ?>">
                                </button>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="ua-stkcards-stack">
                <?php
                foreach ( $cards as $index => $item ) :
                    $item_key   = 'stkcard_' . $index;
                    $repeater_id = ! empty( $item['_id'] ) ? $item['_id'] : $index;
                    $title      = ! empty( $item['card_title'] ) ? $item['card_title'] : '';
                    $subtitle   = ! empty( $item['card_subtitle'] ) ? $item['card_subtitle'] : '';
                    $desc       = ! empty( $item['card_desc'] ) ? $item['card_desc'] : '';
                    $tag        = ! empty( $item['title_tag'] ) ? $item['title_tag'] : 'h3';
                    $btn_text   = ! empty( $item['btn_text'] ) ? $item['btn_text'] : '';
                    $btn_url    = ! empty( $item['btn_url'] ) ? $item['btn_url'] : [];
                    $media_type = ! empty( $item['media_type'] ) ? $item['media_type'] : 'none';
                    $media_pos  = ! empty( $item['media_position'] ) ? $item['media_position'] : 'right';

                    $item_classes = [
                        'ua-stkcards-item',
                        'elementor-repeater-item-' . esc_attr( $repeater_id ),
                        'ua-stkcards-pos-' . sanitize_html_class( $media_pos ),
                    ];
                    if ( 0 === $index ) {
                        $item_classes[] = 'ua-stkcards-active';
                    }

                    $btn_key = 'btn_' . $index;
                    if ( ! empty( $btn_text ) && ! empty( $btn_url['url'] ) ) {
                        $this->add_link_attributes( $btn_key, $btn_url );
                        $this->add_render_attribute( $btn_key, 'class', 'ua-stkcards-btn' );
                    }
                ?>
                    <article class="<?php echo esc_attr( implode( ' ', $item_classes ) ); ?>"
                             data-card-index="<?php echo esc_attr( $index ); ?>"
                             style="--ua-stkcards-index: <?php echo esc_attr( $index ); ?>;">
                        <div class="ua-stkcards-inner">

                            <div class="ua-stkcards-body">
                                <?php if ( ! empty( $subtitle ) ) : ?>
                                    <div class="ua-stkcards-badge-wrap">
                                        <span class="ua-stkcards-badge"><?php echo esc_html( $subtitle ); ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php if ( ! empty( $title ) ) : ?>
                                    <<?php echo esc_attr( $tag ); ?> class="ua-stkcards-title">
                                        <?php echo esc_html( $title ); ?>
                                    </<?php echo esc_attr( $tag ); ?>>
                                <?php endif; ?>

                                <?php if ( ! empty( $desc ) ) : ?>
                                    <div class="ua-stkcards-desc">
                                        <?php echo wp_kses_post( $desc ); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ( ! empty( $btn_text ) && ! empty( $btn_url['url'] ) ) : ?>
                                    <div class="ua-stkcards-btn-wrap">
                                        <a <?php $this->print_render_attribute_string( $btn_key ); ?>>
                                            <span><?php echo esc_html( $btn_text ); ?></span>
                                            <i class="eicon-arrow-right" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if ( 'none' !== $media_type ) : ?>
                                <div class="ua-stkcards-media">
                                    <?php if ( 'image' === $media_type && ! empty( $item['card_image']['url'] ) ) : ?>
                                        <div class="ua-stkcards-image-wrap">
                                            <?php
                                            echo Group_Control_Image_Size::get_attachment_image_html(
                                                $item,
                                                'card_image_size',
                                                'card_image'
                                            );
                                            ?>
                                        </div>
                                    <?php elseif ( 'icon' === $media_type && ! empty( $item['card_icon']['value'] ) ) : ?>
                                        <div class="ua-stkcards-icon-wrap" aria-hidden="true">
                                            <?php Icons_Manager::render_icon( $item['card_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

        </div>
        <?php
    }
}
