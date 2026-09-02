<?php
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

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * UltraAddons Image Accordion Widget.
 *
 * Combines the best features of top Elementor addons:
 * - Ultra-smooth Flexbox transition engine (Essential Addons)
 * - On Hover and On Click triggers with responsive layout (Essential Addons)
 * - Subtitle / Badge, CTA Button, and smart link modes (ElementsKit)
 * - Ken Burns background zoom, dual overlays, and content reveal animations (Royal Addons)
 * - Keyboard accessibility (A11y) and mobile touch optimization.
 *
 * @package UltraAddons
 * @since 2.0.3.5
 */
class Image_Accordion extends Base {

    /**
     * Constructor to register widget scripts.
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        $css_file = ULTRA_ADDONS_DIR . 'assets/css/widgets/image-accordion.css';
        $css_ver  = file_exists( $css_file ) ? filemtime( $css_file ) : ULTRA_ADDONS_VERSION;

        wp_register_style(
            'ultraaddons-image-accordion',
            ULTRA_ADDONS_ASSETS . 'css/widgets/image-accordion.css',
            [ 'ultraaddons-widgets-style' ],
            $css_ver,
            'all'
        );

        $js_file = ULTRA_ADDONS_DIR . 'assets/js/frontend-image-accordion.js';
        $js_ver  = file_exists( $js_file ) ? filemtime( $js_file ) : ULTRA_ADDONS_VERSION;

        wp_register_script(
            'ultraaddons-image-accordion',
            ULTRA_ADDONS_ASSETS . 'js/frontend-image-accordion.js',
            [ 'jquery', 'elementor-frontend' ],
            $js_ver,
            true
        );
    }

    /**
     * Declare widget script dependencies.
     *
     * @return array
     */
    public function get_script_depends() {
        return [
            'ultraaddons-elementor-frontend',
            'ultraaddons-image-accordion',
        ];
    }

    /**
     * Widget Keywords.
     *
     * @return array
     */
    public function get_keywords() {
        return [ 'ultraaddons', 'image', 'accordion', 'gallery', 'slider', 'interactive', 'banner', 'portfolio' ];
    }

    /**
     * Register widget controls.
     */
    protected function register_controls() {
        $this->register_content_controls();
        $this->register_settings_controls();
        $this->register_style_container_controls();
        $this->register_style_item_controls();
        $this->register_style_content_controls();
        $this->register_style_icon_controls();
        $this->register_style_subtitle_controls();
        $this->register_style_title_controls();
        $this->register_style_description_controls();
        $this->register_style_button_controls();
    }

    /**
     * Accordion Items Repeater Controls.
     */
    protected function register_content_controls() {
        $this->start_controls_section(
            'section_items',
            [
                'label' => esc_html__( 'Accordion Items', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'image',
            [
                'label'   => esc_html__( 'Background Image', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'item_icon',
            [
                'label'   => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::ICONS,
                'default' => [],
            ]
        );

        $repeater->add_control(
            'subtitle',
            [
                'label'       => esc_html__( 'Badge / Subtitle', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Featured', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'e.g. 01 / Portfolio', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label'       => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Accordion Item Title', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'Enter title', 'ultraaddons-elementor-lite' ),
                'label_block' => true,
                'dynamic'     => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label'       => esc_html__( 'Description', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__( 'Explore stunning visual showcases with our modern and responsive image accordion.', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'Enter item description', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'btn_text',
            [
                'label'       => esc_html__( 'Button Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Read More', 'ultraaddons-elementor-lite' ),
                'separator'   => 'before',
                'dynamic'     => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'btn_link',
            [
                'label'       => esc_html__( 'Button Link', 'ultraaddons-elementor-lite' ),
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
            'btn_icon',
            [
                'label'   => esc_html__( 'Button Icon', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fas fa-arrow-right',
                    'library' => 'solid',
                ],
            ]
        );

        $repeater->add_control(
            'btn_icon_align',
            [
                'label'     => esc_html__( 'Icon Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'right',
                'options'   => [
                    'left'  => esc_html__( 'Before (Left)', 'ultraaddons-elementor-lite' ),
                    'right' => esc_html__( 'After (Right)', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'btn_icon[value]!' => '',
                ],
            ]
        );

        $this->add_control(
            'items',
            [
                'label'       => esc_html__( 'Items', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'subtitle'    => esc_html__( '01 / EXPLORE', 'ultraaddons-elementor-lite' ),
                        'title'       => esc_html__( 'Creative Concept', 'ultraaddons-elementor-lite' ),
                        'description' => esc_html__( 'Innovative designs crafted for modern web experiences with dynamic interactions.', 'ultraaddons-elementor-lite' ),
                        'btn_text'    => esc_html__( 'Discover', 'ultraaddons-elementor-lite' ),
                    ],
                    [
                        'subtitle'    => esc_html__( '02 / SHOWCASE', 'ultraaddons-elementor-lite' ),
                        'title'       => esc_html__( 'Visual Architecture', 'ultraaddons-elementor-lite' ),
                        'description' => esc_html__( 'Captivating imagery and seamless transitions that keep your visitors engaged.', 'ultraaddons-elementor-lite' ),
                        'btn_text'    => esc_html__( 'View Gallery', 'ultraaddons-elementor-lite' ),
                    ],
                    [
                        'subtitle'    => esc_html__( '03 / BRANDING', 'ultraaddons-elementor-lite' ),
                        'title'       => esc_html__( 'Design Precision', 'ultraaddons-elementor-lite' ),
                        'description' => esc_html__( 'Pixel-perfect typography and controls built to adapt to any screen size.', 'ultraaddons-elementor-lite' ),
                        'btn_text'    => esc_html__( 'Learn More', 'ultraaddons-elementor-lite' ),
                    ],
                    [
                        'subtitle'    => esc_html__( '04 / STORY', 'ultraaddons-elementor-lite' ),
                        'title'       => esc_html__( 'Impactful Moments', 'ultraaddons-elementor-lite' ),
                        'description' => esc_html__( 'Tell your story through high-impact imagery and fluid accordions.', 'ultraaddons-elementor-lite' ),
                        'btn_text'    => esc_html__( 'Get Started', 'ultraaddons-elementor-lite' ),
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Layout & Behavior Settings Controls.
     */
    protected function register_settings_controls() {
        $this->start_controls_section(
            'section_settings',
            [
                'label' => esc_html__( 'Layout & Settings', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'direction',
            [
                'label'        => esc_html__( 'Orientation', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::CHOOSE,
                'options'      => [
                    'horizontal' => [
                        'title' => esc_html__( 'Horizontal', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-stretch',
                    ],
                    'vertical'   => [
                        'title' => esc_html__( 'Vertical', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-v-align-stretch',
                    ],
                ],
                'default'      => 'horizontal',
                'prefix_class' => 'ua-ia-dir%s-',
                'selectors_dictionary' => [
                    'horizontal' => 'row',
                    'vertical'   => 'column',
                ],
                'selectors'    => [
                    '{{WRAPPER}} .ua-image-accordion' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'trigger',
            [
                'label'   => esc_html__( 'Interaction Trigger', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'hover',
                'options' => [
                    'hover' => esc_html__( 'On Hover', 'ultraaddons-elementor-lite' ),
                    'click' => esc_html__( 'On Click', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'default_active_index',
            [
                'label'       => esc_html__( 'Default Active Item', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::NUMBER,
                'min'         => 0,
                'max'         => 20,
                'step'        => 1,
                'default'     => 1,
                'description' => esc_html__( '1 for first item, 2 for second, etc. Set 0 for none.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'active_flex',
            [
                'label'       => esc_html__( 'Active Item Ratio', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SLIDER,
                'range'       => [
                    'px' => [
                        'min'  => 1.5,
                        'max'  => 7,
                        'step' => 0.1,
                    ],
                ],
                'default'     => [
                    'unit' => 'px',
                    'size' => 3.5,
                ],
                'description' => esc_html__( 'Multiplier for active item width/height compared to inactive items.', 'ultraaddons-elementor-lite' ),
                'selectors'   => [
                    '{{WRAPPER}} .ua-image-accordion' => '--ua-ia-active-flex: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'enable_zoom',
            [
                'label'        => esc_html__( 'Background Zoom Effect', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'content_animation',
            [
                'label'        => esc_html__( 'Content Entrance Animation', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SELECT,
                'default'      => 'slide-up',
                'prefix_class' => 'ua-ia-anim-',
                'render_type'  => 'template',
                'options'      => [
                    'slide-up'    => esc_html__( 'Slide Up', 'ultraaddons-elementor-lite' ),
                    'slide-down'  => esc_html__( 'Slide Down', 'ultraaddons-elementor-lite' ),
                    'slide-left'  => esc_html__( 'Slide Left', 'ultraaddons-elementor-lite' ),
                    'slide-right' => esc_html__( 'Slide Right', 'ultraaddons-elementor-lite' ),
                    'zoom-in'     => esc_html__( 'Zoom In', 'ultraaddons-elementor-lite' ),
                    'zoom-out'    => esc_html__( 'Zoom Out', 'ultraaddons-elementor-lite' ),
                    'fade-in'     => esc_html__( 'Fade In', 'ultraaddons-elementor-lite' ),
                    'none'        => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label'   => esc_html__( 'Title HTML Tag', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'h3',
                'options' => [
                    'h1'   => 'H1',
                    'h2'   => 'H2',
                    'h3'   => 'H3',
                    'h4'   => 'H4',
                    'h5'   => 'H5',
                    'h6'   => 'H6',
                    'div'  => 'div',
                    'span' => 'span',
                ],
            ]
        );

        $this->add_control(
            'link_whole_item',
            [
                'label'        => esc_html__( 'Link Entire Accordion Item', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'description'  => esc_html__( 'If enabled, clicking anywhere on the item opens its button link.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'enable_skew',
            [
                'label'        => esc_html__( 'Skew Images (Slanted Cards)', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before',
                'render_type'  => 'template',
                'prefix_class' => 'ua-ia-skew-',
            ]
        );

        $this->add_responsive_control(
            'skew_angle',
            [
                'label'       => esc_html__( 'Skew Angle (deg)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SLIDER,
                'range'       => [
                    'px' => [
                        'min'  => -25,
                        'max'  => 25,
                        'step' => 1,
                    ],
                ],
                'default'     => [
                    'unit' => 'px',
                    'size' => 4,
                ],
                'selectors'   => [
                    '{{WRAPPER}}'                      => '--ua-ia-skew: {{SIZE}}deg;',
                    '{{WRAPPER}} .ua-image-accordion' => '--ua-ia-skew: {{SIZE}}deg;',
                ],
                'condition'   => [
                    'enable_skew' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'skew_gap',
            [
                'label'       => esc_html__( 'Skew Card Spacing (px)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SLIDER,
                'range'       => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 30,
                        'step' => 1,
                    ],
                ],
                'default'     => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors'   => [
                    '{{WRAPPER}} .ua-ia-item' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition'   => [
                    'enable_skew' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Section: Container.
     */
    protected function register_style_container_controls() {
        $this->start_controls_section(
            'section_style_container',
            [
                'label' => esc_html__( 'Container', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'container_height',
            [
                'label'      => esc_html__( 'Height', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh' ],
                'range'      => [
                    'px' => [
                        'min' => 200,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 480,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-image-accordion' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_spacing',
            [
                'label'      => esc_html__( 'Item Gutter / Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-image-accordion' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'container_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-image-accordion' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'container_border',
                'label'    => esc_html__( 'Border', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-image-accordion',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'container_box_shadow',
                'label'    => esc_html__( 'Box Shadow', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-image-accordion',
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-image-accordion' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Section: Item & Overlay.
     */
    protected function register_style_item_controls() {
        $this->start_controls_section(
            'section_style_item',
            [
                'label' => esc_html__( 'Item & Overlay', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'transition_duration',
            [
                'label'      => esc_html__( 'Transition Duration (s)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 0.2,
                        'max'  => 2.0,
                        'step' => 0.1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 0.5,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-image-accordion' => '--ua-ia-duration: {{SIZE}}s;',
                ],
            ]
        );

        $this->add_control(
            'item_border_radius',
            [
                'label'      => esc_html__( 'Item Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_overlay' );

        // Normal State Tab
        $this->start_controls_tab(
            'tab_overlay_normal',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'normal_overlay_bg',
                'label'    => esc_html__( 'Overlay Background', 'ultraaddons-elementor-lite' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-image-accordion .ua-ia-overlay-normal',
            ]
        );

        $this->add_control(
            'normal_overlay_opacity',
            [
                'label'     => esc_html__( 'Overlay Opacity', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1,
                        'step' => 0.05,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-overlay-normal' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Active State Tab
        $this->start_controls_tab(
            'tab_overlay_active',
            [
                'label' => esc_html__( 'Active', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'active_overlay_bg',
                'label'    => esc_html__( 'Overlay Background', 'ultraaddons-elementor-lite' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-image-accordion .ua-ia-overlay-active',
            ]
        );

        $this->add_control(
            'active_overlay_opacity',
            [
                'label'     => esc_html__( 'Overlay Opacity', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1,
                        'step' => 0.05,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-item.ua-ia-active .ua-ia-overlay-active' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /**
     * Style Section: Content Box.
     */
    protected function register_style_content_controls() {
        $this->start_controls_section(
            'section_style_content',
            [
                'label' => esc_html__( 'Content Area', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'content_valign',
            [
                'label'     => esc_html__( 'Vertical Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'flex-start' => [
                        'title' => esc_html__( 'Top', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'center'     => [
                        'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-v-align-middle',
                    ],
                    'flex-end'   => [
                        'title' => esc_html__( 'Bottom', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ],
                ],
                'default'   => 'flex-end',
                'selectors' => [
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-content-wrap' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_halign',
            [
                'label'     => esc_html__( 'Horizontal Alignment', 'ultraaddons-elementor-lite' ),
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
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-content-wrap' => 'justify-content: {{VALUE}};',
                ],
                'selectors_dictionary' => [
                    'left'   => 'flex-start',
                    'center' => 'center',
                    'right'  => 'flex-end',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_text_align',
            [
                'label'     => esc_html__( 'Text Alignment', 'ultraaddons-elementor-lite' ),
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
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-content' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_box_width',
            [
                'label'     => esc_html__( 'Content Box Width', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'fit-content',
                'options'   => [
                    'fit-content' => esc_html__( 'Fit to Content (Auto)', 'ultraaddons-elementor-lite' ),
                    '100%'        => esc_html__( 'Full Width (100%)', 'ultraaddons-elementor-lite' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-content' => 'width: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label'      => esc_html__( 'Wrap Outer Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'    => [
                    'top'    => '30',
                    'right'  => '30',
                    'bottom' => '30',
                    'left'   => '30',
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-content-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_max_width',
            [
                'label'      => esc_html__( 'Content Max Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [
                        'min' => 150,
                        'max' => 1200,
                    ],
                    '%'  => [
                        'min' => 20,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-content' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => 'content_bg',
                'label'     => esc_html__( 'Content Background', 'ultraaddons-elementor-lite' ),
                'types'     => [ 'classic', 'gradient' ],
                'selector'  => '{{WRAPPER}} .ua-image-accordion .ua-ia-content::before',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'content_bg_opacity',
            [
                'label'      => esc_html__( 'Background Opacity', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 0.05,
                        'max'  => 1,
                        'step' => 0.05,
                    ],
                ],
                'default'    => [
                    'size' => 1,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-content::before' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'content_backdrop_blur',
            [
                'label'      => esc_html__( 'Backdrop Blur (Glass Effect)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 30,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'size' => 0,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-content::before' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_box_padding',
            [
                'label'      => esc_html__( 'Box Inner Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'content_border',
                'selector' => '{{WRAPPER}} .ua-image-accordion .ua-ia-content',
            ]
        );

        $this->add_responsive_control(
            'content_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'content_box_shadow',
                'selector' => '{{WRAPPER}} .ua-image-accordion .ua-ia-content',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Section: Icon.
     */
    protected function register_style_icon_controls() {
        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label'      => esc_html__( 'Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [
                        'min' => 10,
                        'max' => 80,
                    ],
                ],
                'default'    => [
                    'size' => 24,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-icon'     => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_spacing',
            [
                'label'      => esc_html__( 'Spacing Below Icon (px)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default'    => [
                    'size' => 12,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_icon_style' );

        $this->start_controls_tab(
            'tab_icon_normal',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-icon'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_icon_hover',
            [
                'label' => esc_html__( 'Active / Hover', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'icon_hover_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-item:hover .ua-ia-icon, {{WRAPPER}} .ua-image-accordion .ua-ia-item.ua-ia-active .ua-ia-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-item:hover .ua-ia-icon svg, {{WRAPPER}} .ua-image-accordion .ua-ia-item.ua-ia-active .ua-ia-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /**
     * Style Section: Badge / Subtitle.
     */
    protected function register_style_subtitle_controls() {
        $this->start_controls_section(
            'section_style_subtitle',
            [
                'label' => esc_html__( 'Badge / Subtitle', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .ua-image-accordion .ua-ia-subtitle',
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'subtitle_spacing',
            [
                'label'      => esc_html__( 'Spacing Below (px)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 8,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Section: Title.
     */
    protected function register_style_title_controls() {
        $this->start_controls_section(
            'section_style_title',
            [
                'label' => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .ua-image-accordion .ua-ia-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Title Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_active_color',
            [
                'label'     => esc_html__( 'Active State Title Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-item.ua-ia-active .ua-ia-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label'      => esc_html__( 'Spacing Below (px)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Section: Description.
     */
    protected function register_style_description_controls() {
        $this->start_controls_section(
            'section_style_description',
            [
                'label' => esc_html__( 'Description', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'desc_typography',
                'selector' => '{{WRAPPER}} .ua-image-accordion .ua-ia-description',
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label'     => esc_html__( 'Description Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(255, 255, 255, 0.85)',
                'selectors' => [
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'desc_spacing',
            [
                'label'      => esc_html__( 'Spacing Below (px)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Section: CTA Button.
     */
    protected function register_style_button_controls() {
        $this->start_controls_section(
            'section_style_button',
            [
                'label' => esc_html__( 'CTA Button', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'btn_typography',
                'selector' => '{{WRAPPER}} .ua-image-accordion .ua-ia-btn',
            ]
        );

        $this->start_controls_tabs( 'tabs_button_style' );

        // Normal State
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
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#3f51b5',
                'selectors' => [
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'btn_border',
                'label'    => esc_html__( 'Border', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-image-accordion .ua-ia-btn',
            ]
        );

        $this->end_controls_tab();

        // Hover State
        $this->start_controls_tab(
            'tab_btn_hover',
            [
                'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'btn_hover_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#303f9f',
                'selectors' => [
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-btn:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'btn_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'separator'  => 'before',
                'selectors'  => [
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'top'    => '10',
                    'right'  => '22',
                    'bottom' => '10',
                    'left'   => '22',
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_icon_spacing',
            [
                'label'      => esc_html__( 'Icon Spacing (px)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 8,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-btn' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [
                        'min' => 8,
                        'max' => 40,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-btn i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-image-accordion .ua-ia-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $items    = ! empty( $settings['items'] ) && is_array( $settings['items'] ) ? $settings['items'] : [];

        if ( empty( $items ) ) {
            return;
        }

        $id                   = $this->get_id();
        $trigger              = ! empty( $settings['trigger'] ) ? $settings['trigger'] : 'hover';
        $default_active_index = isset( $settings['default_active_index'] ) ? (int) $settings['default_active_index'] : 1;
        $enable_zoom          = ! empty( $settings['enable_zoom'] ) && $settings['enable_zoom'] === 'yes' ? 'yes' : 'no';
        $content_anim         = ! empty( $settings['content_animation'] ) ? $settings['content_animation'] : 'slide-up';
        $title_tag            = ! empty( $settings['title_tag'] ) ? Utils::validate_html_tag( $settings['title_tag'] ) : 'h3';
        $link_whole_item      = ! empty( $settings['link_whole_item'] ) && $settings['link_whole_item'] === 'yes';

        $direction = ! empty( $settings['direction'] ) ? $settings['direction'] : 'horizontal';
        $enable_skew = ! empty( $settings['enable_skew'] ) && $settings['enable_skew'] === 'yes';

        $wrapper_classes = [
            'ua-image-accordion',
            'ua-image-accordion-' . esc_attr( $id ),
            'ua-ia-anim-' . esc_attr( $content_anim ),
            'ua-ia-dir-' . esc_attr( $direction ),
        ];

        if ( $enable_skew && $direction !== 'vertical' ) {
            $wrapper_classes[] = 'ua-ia-skew-yes';
        }
        ?>
        <div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>"
             data-trigger="<?php echo esc_attr( $trigger ); ?>"
             data-active-index="<?php echo esc_attr( $default_active_index ); ?>"
             data-zoom="<?php echo esc_attr( $enable_zoom ); ?>"
             role="tablist">
            <?php
            foreach ( $items as $index => $item ) :
                $item_key   = $index + 1;
                $is_active  = ( $item_key === $default_active_index );
                $item_class = 'ua-ia-item elementor-repeater-item-' . esc_attr( $item['_id'] ?? $item_key );
                if ( $is_active ) {
                    $item_class .= ' ua-ia-active';
                }

                $image_url = ! empty( $item['image']['url'] ) ? $item['image']['url'] : Utils::get_placeholder_image_src();

                $btn_url     = ! empty( $item['btn_link']['url'] ) ? $item['btn_link']['url'] : '';
                $is_external = ! empty( $item['btn_link']['is_external'] );
                $nofollow    = ! empty( $item['btn_link']['nofollow'] );
                $link_attrs  = '';

                if ( ! empty( $btn_url ) ) {
                    if ( $is_external ) {
                        $link_attrs .= ' target="_blank"';
                    }
                    if ( $nofollow ) {
                        $link_attrs .= ' rel="nofollow"';
                    }
                }

                $has_btn = ! empty( $item['btn_text'] ) || ( ! empty( $item['btn_icon']['value'] ) );
                $icon_align = ! empty( $item['btn_icon_align'] ) ? $item['btn_icon_align'] : 'right';
                ?>
                <div class="<?php echo esc_attr( $item_class ); ?>"
                     role="tab"
                     aria-expanded="<?php echo $is_active ? 'true' : 'false'; ?>"
                     tabindex="0"
                     data-index="<?php echo esc_attr( $item_key ); ?>">

                    <!-- Background Image Layer -->
                    <div class="ua-ia-bg" style="background-image: url('<?php echo esc_url( $image_url ); ?>');"></div>

                    <!-- Dual Overlays -->
                    <div class="ua-ia-overlay ua-ia-overlay-normal"></div>
                    <div class="ua-ia-overlay ua-ia-overlay-active"></div>

                    <!-- Content Area -->
                    <div class="ua-ia-content-wrap">
                        <div class="ua-ia-content">

                            <?php if ( ! empty( $item['item_icon']['value'] ) ) : ?>
                                <div class="ua-ia-icon">
                                    <?php Icons_Manager::render_icon( $item['item_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                </div>
                            <?php endif; ?>

                            <?php if ( ! empty( $item['subtitle'] ) ) : ?>
                                <span class="ua-ia-subtitle"><?php echo esc_html( $item['subtitle'] ); ?></span>
                            <?php endif; ?>

                            <?php if ( ! empty( $item['title'] ) ) : ?>
                                <<?php echo esc_attr( $title_tag ); ?> class="ua-ia-title">
                                    <?php echo esc_html( $item['title'] ); ?>
                                </<?php echo esc_attr( $title_tag ); ?>>
                            <?php endif; ?>

                            <?php if ( ! empty( $item['description'] ) ) : ?>
                                <div class="ua-ia-description">
                                    <?php echo wp_kses_post( $item['description'] ); ?>
                                </div>
                            <?php endif; ?>

                            <?php if ( $has_btn && ! empty( $btn_url ) ) : ?>
                                <a class="ua-ia-btn" href="<?php echo esc_url( $btn_url ); ?>"<?php echo $link_attrs; ?>>
                                    <?php if ( $icon_align === 'left' && ! empty( $item['btn_icon']['value'] ) ) : ?>
                                        <?php Icons_Manager::render_icon( $item['btn_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                    <?php endif; ?>

                                    <?php if ( ! empty( $item['btn_text'] ) ) : ?>
                                        <span><?php echo esc_html( $item['btn_text'] ); ?></span>
                                    <?php endif; ?>

                                    <?php if ( $icon_align === 'right' && ! empty( $item['btn_icon']['value'] ) ) : ?>
                                        <?php Icons_Manager::render_icon( $item['btn_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                    <?php endif; ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if ( $link_whole_item && ! empty( $btn_url ) ) : ?>
                        <a class="ua-ia-full-link"
                           href="<?php echo esc_url( $btn_url ); ?>"
                           <?php echo $link_attrs; ?>
                           aria-label="<?php echo esc_attr( ! empty( $item['title'] ) ? $item['title'] : __( 'Accordion item link', 'ultraaddons-elementor-lite' ) ); ?>">
                        </a>
                    <?php endif; ?>

                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}
