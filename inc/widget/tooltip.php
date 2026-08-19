<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Plugin;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * UltraAddons Tooltip Widget
 *
 * An interactive, accessible, and highly customizable tooltip widget.
 * Supports icons, text, images, and shortcodes with smooth directional
 * animations (Top, Bottom, Left, Right) and responsive touch support.
 *
 * @since 1.2.0
 * @package UltraAddons
 */
class Tooltip extends Base {

    /**
     * Constructor — register style dependency
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        wp_register_style(
            'ultraaddons-tooltip',
            ULTRA_ADDONS_ASSETS . 'css/widgets/tooltip.css',
            [],
            ULTRA_ADDONS_VERSION,
            'all'
        );
        wp_enqueue_style( 'ultraaddons-tooltip' );
    }

    /**
     * Style dependency
     */
    public function get_style_depends() {
        return [ 'ultraaddons-tooltip' ];
    }

    /**
     * Widget keywords
     */
    public function get_keywords() {
        return [ 'ultraaddons-elementor-lite', 'ua', 'tooltip', 'popover', 'hint', 'info', 'hover', 'floating text', 'glossary' ];
    }

    /**
     * Register all controls
     */
    protected function register_controls() {
        // Content Tab
        $this->content_trigger_controls();
        $this->content_tooltip_controls();

        // Style Tab
        $this->style_trigger_controls();
        $this->style_tooltip_controls();
    }

    /*==========================================================================
     * CONTENT TAB — Trigger Content Settings
     *========================================================================*/
    protected function content_trigger_controls() {
        $this->start_controls_section(
            'ua_section_tooltip_trigger_settings',
            [
                'label' => esc_html__( 'Content Settings', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ua_tooltip_type',
            [
                'label'       => esc_html__( 'Content Type', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::CHOOSE,
                'label_block' => true,
                'options'     => [
                    'icon'      => [
                        'title' => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-info-circle',
                    ],
                    'text'      => [
                        'title' => esc_html__( 'Text', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-t-letter',
                    ],
                    'image'     => [
                        'title' => esc_html__( 'Image', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-image',
                    ],
                    'shortcode' => [
                        'title' => esc_html__( 'Shortcode', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-shortcode',
                    ],
                ],
                'default'     => 'icon',
            ]
        );

        // Icon
        $this->add_control(
            'ua_tooltip_icon',
            [
                'label'     => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'fas fa-info-circle',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'ua_tooltip_type' => 'icon',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_tooltip_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', '%' ],
                'default'    => [
                    'size' => 40,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'size' => 34,
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'size' => 28,
                    'unit' => 'px',
                ],
                'range'      => [
                    'px' => [
                        'min' => 10,
                        'max' => 150,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-tooltip-trigger i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-tooltip-trigger svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'ua_tooltip_type' => 'icon',
                ],
            ]
        );

        // Text
        $this->add_control(
            'ua_tooltip_text_content',
            [
                'label'       => esc_html__( 'Content', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::WYSIWYG,
                'label_block' => true,
                'default'     => esc_html__( 'Hover Me!', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'condition'   => [
                    'ua_tooltip_type' => 'text',
                ],
            ]
        );

        $this->add_control(
            'ua_tooltip_content_tag',
            [
                'label'     => esc_html__( 'Content Tag', 'ultraaddons-elementor-lite' ),
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
                    'ua_tooltip_type' => 'text',
                ],
            ]
        );

        // Image
        $this->add_control(
            'ua_tooltip_image',
            [
                'label'     => esc_html__( 'Image', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::MEDIA,
                'default'   => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'ua_tooltip_type' => 'image',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'      => 'image_size',
                'default'   => 'full',
                'condition' => [
                    'ua_tooltip_type' => 'image',
                ],
            ]
        );

        // Shortcode
        $this->add_control(
            'ua_tooltip_shortcode',
            [
                'label'       => esc_html__( 'Shortcode', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'default'     => '[your-shortcode-here]',
                'condition'   => [
                    'ua_tooltip_type' => 'shortcode',
                ],
            ]
        );

        // Alignment
        $this->add_responsive_control(
            'ua_tooltip_content_alignment',
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
                        'title' => esc_html__( 'Justified', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-justify',
                    ],
                ],
                'default'   => 'left',
                'selectors_dictionary' => [
                    'left'    => 'text-align: left;',
                    'center'  => 'text-align: center;',
                    'right'   => 'text-align: right;',
                    'justify' => 'display: flex; justify-content: center; align-items: center;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-tooltip-wrapper' => '{{VALUE}}',
                ],
            ]
        );

        // Link
        $this->add_control(
            'ua_tooltip_enable_link',
            [
                'label'        => esc_html__( 'Enable Link', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'return_value' => 'yes',
                'separator'    => 'before',
                'condition'    => [
                    'ua_tooltip_type!' => 'shortcode',
                ],
            ]
        );

        $this->add_control(
            'ua_tooltip_link',
            [
                'label'       => esc_html__( 'Link URL', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'dynamic'     => [
                    'active'     => true,
                    'categories' => [
                        TagsModule::POST_META_CATEGORY,
                        TagsModule::URL_CATEGORY,
                    ],
                ],
                'placeholder' => 'https://example.com',
                'default'     => [
                    'url' => '#',
                ],
                'condition'   => [
                    'ua_tooltip_enable_link' => 'yes',
                    'ua_tooltip_type!'        => 'shortcode',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * CONTENT TAB — Tooltip Bubble Settings
     *========================================================================*/
    protected function content_tooltip_controls() {
        $this->start_controls_section(
            'ua_section_tooltip_bubble_settings',
            [
                'label' => esc_html__( 'Tooltip Settings', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ua_tooltip_hover_content',
            [
                'label'       => esc_html__( 'Tooltip Content', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::WYSIWYG,
                'label_block' => true,
                'default'     => esc_html__( 'This is the tooltip content.', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'ua_tooltip_direction',
            [
                'label'   => esc_html__( 'Hover Direction', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'top',
                'options' => [
                    'top'    => esc_html__( 'Top', 'ultraaddons-elementor-lite' ),
                    'bottom' => esc_html__( 'Bottom', 'ultraaddons-elementor-lite' ),
                    'left'   => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                    'right'  => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'ua_tooltip_hover_speed',
            [
                'label'      => esc_html__( 'Animation Speed', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'ms', 's' ],
                'range'      => [
                    'ms' => [
                        'min'  => 100,
                        'step' => 10,
                        'max'  => 2000,
                    ],
                    's'  => [
                        'min'  => 0.1,
                        'step' => 0.1,
                        'max'  => 2,
                    ],
                ],
                'default'    => [
                    'unit' => 'ms',
                    'size' => 300,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-tooltip:hover .ua-tooltip-bubble, {{WRAPPER}} .ua-tooltip-trigger:focus + .ua-tooltip-bubble' => 'animation-duration: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Trigger Content Style
     *========================================================================*/
    protected function style_trigger_controls() {
        $this->start_controls_section(
            'ua_section_style_trigger',
            [
                'label' => esc_html__( 'Content Style', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'ua_tooltip_content_max_width',
            [
                'label'      => esc_html__( 'Max Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [
                        'min' => 20,
                        'max' => 1000,
                    ],
                    '%'  => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-tooltip' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_tooltip_content_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'    => [
                    'top'      => '12',
                    'right'    => '24',
                    'bottom'   => '12',
                    'left'     => '24',
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'tablet_default' => [
                    'top'      => '10',
                    'right'    => '20',
                    'bottom'   => '10',
                    'left'     => '20',
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'mobile_default' => [
                    'top'      => '8',
                    'right'    => '16',
                    'bottom'   => '8',
                    'left'     => '16',
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-tooltip' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_tooltip_content_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-tooltip' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'ua_tooltip_content_style_tabs' );

        // Normal State
        $this->start_controls_tab(
            'ua_tooltip_content_tab_normal',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_tooltip_content_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .ua-tooltip' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_tooltip_content_color',
            [
                'label'     => esc_html__( 'Text / Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .ua-tooltip'       => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-tooltip a'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-tooltip svg'   => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_tooltip_shadow',
                'selector' => '{{WRAPPER}} .ua-tooltip',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_tooltip_border',
                'label'    => esc_html__( 'Border', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-tooltip',
            ]
        );

        $this->end_controls_tab();

        // Hover State
        $this->start_controls_tab(
            'ua_tooltip_content_tab_hover',
            [
                'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_tooltip_content_hover_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .ua-tooltip:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_tooltip_content_hover_color',
            [
                'label'     => esc_html__( 'Text / Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#7048ff',
                'selectors' => [
                    '{{WRAPPER}} .ua-tooltip:hover'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-tooltip:hover a'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-tooltip:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_tooltip_hover_shadow',
                'selector' => '{{WRAPPER}} .ua-tooltip:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_tooltip_hover_border',
                'label'    => esc_html__( 'Border', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-tooltip:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_tooltip_content_typography',
                'selector' => '{{WRAPPER}} .ua-tooltip, {{WRAPPER}} .ua-tooltip-trigger',
            ]
        );

        $this->add_responsive_control(
            'ua_tooltip_content_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-tooltip' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Tooltip Bubble Style
     *========================================================================*/
    protected function style_tooltip_controls() {
        $this->start_controls_section(
            'ua_section_style_tooltip_bubble',
            [
                'label' => esc_html__( 'Tooltip Style', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'ua_tooltip_hover_width',
            [
                'label'      => esc_html__( 'Tooltip Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => [
                    'size' => 160,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'size' => 150,
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'size' => 140,
                    'unit' => 'px',
                ],
                'range'      => [
                    'px' => [
                        'min'  => 50,
                        'max'  => 600,
                        'step' => 5,
                    ],
                    '%'  => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-tooltip-bubble' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_tooltip_hover_max_width',
            [
                'label'      => esc_html__( 'Tooltip Max Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 50,
                        'max' => 800,
                    ],
                    '%'  => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-tooltip-bubble' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_tooltip_bubble_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'    => [
                    'top'      => '10',
                    'right'    => '14',
                    'bottom'   => '10',
                    'left'     => '14',
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-tooltip-bubble' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ua_tooltip_hover_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#222222',
                'selectors' => [
                    '{{WRAPPER}} .ua-tooltip-bubble' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_tooltip_hover_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-tooltip-bubble' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_tooltip_hover_typography',
                'selector' => '{{WRAPPER}} .ua-tooltip-bubble',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_tooltip_bubble_shadow',
                'selector' => '{{WRAPPER}} .ua-tooltip-bubble',
            ]
        );

        $this->add_responsive_control(
            'ua_tooltip_bubble_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default'    => [
                    'top'      => '5',
                    'right'    => '5',
                    'bottom'   => '5',
                    'left'     => '5',
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-tooltip-bubble' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_tooltip_arrow_size',
            [
                'label'      => esc_html__( 'Arrow Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => [
                    'size' => 6,
                    'unit' => 'px',
                ],
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 40,
                        'step' => 1,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-tooltip-bubble:after'               => 'border-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-tooltip-bubble.ua-tooltip--left:after'   => 'top: calc( 50% - {{SIZE}}{{UNIT}} );',
                    '{{WRAPPER}} .ua-tooltip-bubble.ua-tooltip--right:after'  => 'top: calc( 50% - {{SIZE}}{{UNIT}} );',
                    '{{WRAPPER}} .ua-tooltip-bubble.ua-tooltip--top:after'    => 'left: calc( 50% - {{SIZE}}{{UNIT}} );',
                    '{{WRAPPER}} .ua-tooltip-bubble.ua-tooltip--bottom:after' => 'left: calc( 50% - {{SIZE}}{{UNIT}} );',
                ],
            ]
        );

        $this->add_control(
            'ua_tooltip_arrow_color',
            [
                'label'     => esc_html__( 'Arrow Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#222222',
                'selectors' => [
                    '{{WRAPPER}} .ua-tooltip-bubble.ua-tooltip--top:after'    => 'border-top-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-tooltip-bubble.ua-tooltip--bottom:after' => 'border-bottom-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-tooltip-bubble.ua-tooltip--left:after'   => 'border-left-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-tooltip-bubble.ua-tooltip--right:after'  => 'border-right-color: {{VALUE}};',
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

        $content_type = ! empty( $settings['ua_tooltip_type'] ) ? $settings['ua_tooltip_type'] : 'icon';
        $direction    = ! empty( $settings['ua_tooltip_direction'] ) ? $settings['ua_tooltip_direction'] : 'top';
        $enable_link  = ( 'yes' === ( $settings['ua_tooltip_enable_link'] ?? '' ) && ! empty( $settings['ua_tooltip_link']['url'] ) );
        $content_tag  = ! empty( $settings['ua_tooltip_content_tag'] ) ? Utils::validate_html_tag( $settings['ua_tooltip_content_tag'] ) : 'span';
        $unique_id    = $this->get_id();

        // Image handling with WPML
        $image = $settings['ua_tooltip_image'] ?? [];
        if ( ! empty( $image['id'] ) ) {
            $image['id'] = apply_filters( 'wpml_object_id', $image['id'], 'attachment', true );
            if ( $image['id'] ) {
                $image['url'] = wp_get_attachment_url( $image['id'] );
            }
        }
        $image_url = Group_Control_Image_Size::get_attachment_image_src( $image['id'] ?? 0, 'image_size', $settings );
        if ( empty( $image_url ) && ! empty( $image['url'] ) ) {
            $image_url = $image['url'];
        }

        // Link attributes
        if ( $enable_link ) {
            $this->add_link_attributes( 'ua_tooltip_link', $settings['ua_tooltip_link'] );
        }
        ?>
        <div class="ua-tooltip-wrapper">
            <div class="ua-tooltip">

                <!-- TRIGGER CONTENT -->
                <?php if ( 'text' === $content_type ) : ?>
                    <<?php echo esc_html( $content_tag ); ?> class="ua-tooltip-trigger" tabindex="0" aria-describedby="ua-tooltip-bubble-<?php echo esc_attr( $unique_id ); ?>">
                        <?php if ( $enable_link ) : ?><a <?php $this->print_render_attribute_string( 'ua_tooltip_link' ); ?>><?php endif; ?>
                        <?php echo wp_kses_post( $settings['ua_tooltip_text_content'] ); ?>
                        <?php if ( $enable_link ) : ?></a><?php endif; ?>
                    </<?php echo esc_html( $content_tag ); ?>>

                <?php elseif ( 'icon' === $content_type ) : ?>
                    <span class="ua-tooltip-trigger ua-tooltip-icon-trigger" tabindex="0" aria-describedby="ua-tooltip-bubble-<?php echo esc_attr( $unique_id ); ?>">
                        <?php if ( $enable_link ) : ?><a <?php $this->print_render_attribute_string( 'ua_tooltip_link' ); ?>><?php endif; ?>
                        <?php if ( ! empty( $settings['ua_tooltip_icon']['value'] ) ) : ?>
                            <?php Icons_Manager::render_icon( $settings['ua_tooltip_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                        <?php endif; ?>
                        <?php if ( $enable_link ) : ?></a><?php endif; ?>
                    </span>

                <?php elseif ( 'image' === $content_type ) : ?>
                    <span class="ua-tooltip-trigger ua-tooltip-image-trigger" tabindex="0" aria-describedby="ua-tooltip-bubble-<?php echo esc_attr( $unique_id ); ?>">
                        <?php if ( $enable_link ) : ?><a <?php $this->print_render_attribute_string( 'ua_tooltip_link' ); ?>><?php endif; ?>
                        <?php if ( ! empty( $image_url ) ) : ?>
                            <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( get_post_meta( $image['id'] ?? 0, '_wp_attachment_image_alt', true ) ); ?>">
                        <?php endif; ?>
                        <?php if ( $enable_link ) : ?></a><?php endif; ?>
                    </span>

                <?php elseif ( 'shortcode' === $content_type ) : ?>
                    <div class="ua-tooltip-trigger ua-tooltip-shortcode-trigger" tabindex="0" aria-describedby="ua-tooltip-bubble-<?php echo esc_attr( $unique_id ); ?>">
                        <?php echo do_shortcode( $settings['ua_tooltip_shortcode'] ); ?>
                    </div>
                <?php endif; ?>

                <!-- TOOLTIP BUBBLE -->
                <span id="ua-tooltip-bubble-<?php echo esc_attr( $unique_id ); ?>" class="ua-tooltip-bubble ua-tooltip--<?php echo esc_attr( $direction ); ?>" role="tooltip">
                    <?php echo wp_kses_post( $settings['ua_tooltip_hover_content'] ); ?>
                </span>

            </div>
        </div>
        <?php
    }
}
