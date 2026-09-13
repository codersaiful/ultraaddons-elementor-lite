<?php
namespace UltraAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * UltraAddons Circle Menu Widget
 *
 * A modern, performant, and fully featured radial / circular menu widget.
 * Features 13 distribution directions, GPU-accelerated trigonometry transforms,
 * click & hover triggers, tooltips, and unlimited menu items — 100% free.
 *
 * @since 1.1.0.9
 * @package UltraAddons
 */
class Circle_Menu extends Base {

    /**
     * Get widget keywords
     *
     * @since 1.1.0.9
     * @access public
     * @return array
     */
    public function get_keywords() {
        return [ 'ultraaddons-elementor-lite', 'ua', 'circle menu', 'circular menu', 'radial menu', 'floating menu', 'navigation', 'radial' ];
    }

    /**
     * Register widget controls.
     *
     * @since 1.1.0.9
     * @access protected
     */
    protected function register_controls() {
        $this->ua_items_content_controls();
        $this->ua_layout_content_controls();
        $this->ua_settings_content_controls();

        $this->ua_trigger_style_controls();
        $this->ua_items_style_controls();
        $this->ua_tooltip_style_controls();
    }

    /**
     * Content Tab: Menu Items
     */
    protected function ua_items_content_controls() {
        $this->start_controls_section(
            '_ua_cm_items_section',
            [
                'label' => esc_html__( 'Items', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'item_icon',
            [
                'label'   => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fas fa-home',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $repeater->add_control(
            'item_title',
            [
                'label'   => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Home', 'ultraaddons-elementor-lite' ),
                'dynamic' => [ 'active' => true ],
            ]
        );

        $repeater->add_control(
            'item_link',
            [
                'label'       => esc_html__( 'Link', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => 'https://your-link.com',
                'default'     => [
                    'url'         => '#',
                    'is_external' => false,
                    'nofollow'    => false,
                ],
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $repeater->add_control(
            'item_bg_color',
            [
                'label'     => esc_html__( 'Custom Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .ua-cm-link' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $repeater->add_control(
            'item_icon_color',
            [
                'label'     => esc_html__( 'Custom Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .ua-cm-link' => 'color: {{VALUE}} !important;',
                    '{{WRAPPER}} {{CURRENT_ITEM}} .ua-cm-link svg' => 'fill: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'ua_cm_items',
            [
                'label'       => esc_html__( 'Items', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ item_title }}}',
                'default'     => [
                    [
                        'item_title' => esc_html__( 'Home', 'ultraaddons-elementor-lite' ),
                        'item_icon'  => [ 'value' => 'fas fa-home', 'library' => 'fa-solid' ],
                    ],
                    [
                        'item_title' => esc_html__( 'Explore', 'ultraaddons-elementor-lite' ),
                        'item_icon'  => [ 'value' => 'fas fa-compass', 'library' => 'fa-solid' ],
                    ],
                    [
                        'item_title' => esc_html__( 'Portfolio', 'ultraaddons-elementor-lite' ),
                        'item_icon'  => [ 'value' => 'fas fa-briefcase', 'library' => 'fa-solid' ],
                    ],
                    [
                        'item_title' => esc_html__( 'Blog', 'ultraaddons-elementor-lite' ),
                        'item_icon'  => [ 'value' => 'fas fa-newspaper', 'library' => 'fa-solid' ],
                    ],
                    [
                        'item_title' => esc_html__( 'Contact', 'ultraaddons-elementor-lite' ),
                        'item_icon'  => [ 'value' => 'fas fa-envelope', 'library' => 'fa-solid' ],
                    ],
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content Tab: Layout
     */
    protected function ua_layout_content_controls() {
        $this->start_controls_section(
            '_ua_cm_layout_section',
            [
                'label' => esc_html__( 'Layout', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ua_cm_direction',
            [
                'label'   => esc_html__( 'Menu Direction', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'full',
                'options' => [
                    'full'         => esc_html__( 'Full Circle (360°)', 'ultraaddons-elementor-lite' ),
                    'top'          => esc_html__( 'Top Arc (90°)', 'ultraaddons-elementor-lite' ),
                    'right'        => esc_html__( 'Right Arc (90°)', 'ultraaddons-elementor-lite' ),
                    'bottom'       => esc_html__( 'Bottom Arc (90°)', 'ultraaddons-elementor-lite' ),
                    'left'         => esc_html__( 'Left Arc (90°)', 'ultraaddons-elementor-lite' ),
                    'top-half'     => esc_html__( 'Top Half (180°)', 'ultraaddons-elementor-lite' ),
                    'bottom-half'  => esc_html__( 'Bottom Half (180°)', 'ultraaddons-elementor-lite' ),
                    'left-half'    => esc_html__( 'Left Half (180°)', 'ultraaddons-elementor-lite' ),
                    'right-half'   => esc_html__( 'Right Half (180°)', 'ultraaddons-elementor-lite' ),
                    'top-left'     => esc_html__( 'Top Left Corner (90°)', 'ultraaddons-elementor-lite' ),
                    'top-right'    => esc_html__( 'Top Right Corner (90°)', 'ultraaddons-elementor-lite' ),
                    'bottom-left'  => esc_html__( 'Bottom Left Corner (90°)', 'ultraaddons-elementor-lite' ),
                    'bottom-right' => esc_html__( 'Bottom Right Corner (90°)', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_cm_distance',
            [
                'label'      => esc_html__( 'Circle Menu Distance', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 40,
                        'max'  => 350,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 120,
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_cm_btn_size',
            [
                'label'      => esc_html__( 'Button Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 30,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 50,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-circle-menu-wrap'  => '--ua-cm-btn-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-cm-trigger'        => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-cm-link'           => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-circle-menu-inner' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ua_cm_hide_titles',
            [
                'label'        => esc_html__( 'Hide Titles', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'ua_cm_title_display',
            [
                'label'     => esc_html__( 'Title Display Type', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'inside',
                'options'   => [
                    'inside'  => esc_html__( 'Inside Button', 'ultraaddons-elementor-lite' ),
                    'tooltip' => esc_html__( 'Floating Tooltip', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'ua_cm_hide_titles!' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_cm_align',
            [
                'label'        => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::CHOOSE,
                'options'      => [
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
                'default'              => 'center',
                'prefix_class'         => 'ua-cm-align%s-',
                'selectors_dictionary' => [
                    'left'   => 'flex-start',
                    'center' => 'center',
                    'right'  => 'flex-end',
                ],
                'selectors'            => [
                    '{{WRAPPER}} .ua-circle-menu-wrap' => 'justify-content: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content Tab: Settings
     */
    protected function ua_settings_content_controls() {
        $this->start_controls_section(
            '_ua_cm_settings_section',
            [
                'label' => esc_html__( 'Settings', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ua_cm_speed',
            [
                'label'      => esc_html__( 'Speed', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => [
                    'size' => 500,
                ],
                'range'      => [
                    'px' => [
                        'min'  => 100,
                        'step' => 10,
                        'max'  => 1500,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-circle-menu-wrap' => '--ua-cm-duration: {{SIZE}}ms;',
                ],
            ]
        );

        $this->add_control(
            'ua_cm_delay',
            [
                'label'      => esc_html__( 'Delay', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => [
                    'size' => 150,
                ],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'step' => 10,
                        'max'  => 2000,
                    ],
                ],
            ]
        );

        $this->add_control(
            'ua_cm_step_out',
            [
                'label'      => esc_html__( 'Step Out', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => [
                    'size' => 40,
                ],
                'range'      => [
                    'px' => [
                        'min'  => -200,
                        'step' => 5,
                        'max'  => 200,
                    ],
                ],
            ]
        );

        $this->add_control(
            'ua_cm_step_in',
            [
                'label'      => esc_html__( 'Step In', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => [
                    'size' => -30,
                ],
                'range'      => [
                    'px' => [
                        'min'  => -200,
                        'step' => 5,
                        'max'  => 200,
                    ],
                ],
                'separator'  => 'after',
            ]
        );

        $this->add_control(
            'ua_cm_trigger_action',
            [
                'label'   => esc_html__( 'Trigger', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'hover',
                'options' => [
                    'hover' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
                    'click' => esc_html__( 'Click', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'ua_cm_transition',
            [
                'label'     => esc_html__( 'Transition', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'ease',
                'options'   => [
                    'ease'                             => esc_html__( 'Ease', 'ultraaddons-elementor-lite' ),
                    'linear'                           => esc_html__( 'Linear', 'ultraaddons-elementor-lite' ),
                    'ease-in'                          => esc_html__( 'Ease In', 'ultraaddons-elementor-lite' ),
                    'ease-out'                         => esc_html__( 'Ease Out', 'ultraaddons-elementor-lite' ),
                    'ease-in-out'                      => esc_html__( 'Ease In Out', 'ultraaddons-elementor-lite' ),
                    'cubic-bezier(0.34, 1.56, 0.64, 1)' => esc_html__( 'Bouncy Spring', 'ultraaddons-elementor-lite' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-circle-menu-wrap' => '--ua-cm-timing: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_cm_open_icon',
            [
                'label'     => esc_html__( 'Open Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'fas fa-bars',
                    'library' => 'fa-solid',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'ua_cm_close_icon',
            [
                'label'   => esc_html__( 'Close Icon', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fas fa-times',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'ua_cm_trigger_aria',
            [
                'label'   => esc_html__( 'Accessibility Label', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Toggle Menu', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab: Trigger Button
     */
    protected function ua_trigger_style_controls() {
        $this->start_controls_section(
            '_ua_cm_trigger_style_section',
            [
                'label' => esc_html__( 'Trigger Button', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_trigger_style' );

        // Normal State
        $this->start_controls_tab(
            'tab_trigger_normal',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_cm_trigger_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0274be',
                'selectors' => [
                    '{{WRAPPER}} .ua-cm-trigger' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_cm_trigger_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-cm-trigger'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-cm-trigger svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_cm_trigger_shadow',
                'selector' => '{{WRAPPER}} .ua-cm-trigger',
            ]
        );

        $this->end_controls_tab();

        // Active / Hover State
        $this->start_controls_tab(
            'tab_trigger_hover',
            [
                'label' => esc_html__( 'Hover / Active', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_cm_trigger_bg_hover',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#005b94',
                'selectors' => [
                    '{{WRAPPER}} .ua-cm-trigger:hover, {{WRAPPER}} .ua-circle-menu-wrap.ua-cm-open .ua-cm-trigger' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_cm_trigger_icon_color_hover',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-cm-trigger:hover, {{WRAPPER}} .ua-circle-menu-wrap.ua-cm-open .ua-cm-trigger' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-cm-trigger:hover svg, {{WRAPPER}} .ua-circle-menu-wrap.ua-cm-open .ua-cm-trigger svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_cm_trigger_shadow_hover',
                'selector' => '{{WRAPPER}} .ua-cm-trigger:hover, {{WRAPPER}} .ua-circle-menu-wrap.ua-cm-open .ua-cm-trigger',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'ua_cm_trigger_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 10,
                        'max'  => 40,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'separator'  => 'before',
                'selectors'  => [
                    '{{WRAPPER}} .ua-cm-trigger :is(i, svg)' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_cm_trigger_border',
                'selector' => '{{WRAPPER}} .ua-cm-trigger',
            ]
        );

        $this->add_responsive_control(
            'ua_cm_trigger_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'    => [
                    'top'      => '50',
                    'right'    => '50',
                    'bottom'   => '50',
                    'left'     => '50',
                    'unit'     => '%',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-cm-trigger' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab: Menu Items
     */
    protected function ua_items_style_controls() {
        $this->start_controls_section(
            '_ua_cm_items_style_section',
            [
                'label' => esc_html__( 'Menu Items', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_items_style' );

        // Normal State
        $this->start_controls_tab(
            'tab_items_normal',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_cm_item_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0274be',
                'selectors' => [
                    '{{WRAPPER}} .ua-cm-link' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_cm_item_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-cm-link'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-cm-link svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_cm_item_title_color',
            [
                'label'     => esc_html__( 'Title Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'condition' => [
                    'ua_cm_hide_titles!'  => 'yes',
                    'ua_cm_title_display' => 'inside',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-cm-item-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'ua_cm_item_title_typography',
                'selector'  => '{{WRAPPER}} .ua-cm-item-title',
                'condition' => [
                    'ua_cm_hide_titles!'  => 'yes',
                    'ua_cm_title_display' => 'inside',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_cm_item_shadow',
                'selector' => '{{WRAPPER}} .ua-cm-link',
            ]
        );

        $this->end_controls_tab();

        // Hover State
        $this->start_controls_tab(
            'tab_items_hover',
            [
                'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_cm_item_bg_hover',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#005b94',
                'selectors' => [
                    '{{WRAPPER}} .ua-cm-link:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_cm_item_icon_color_hover',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-cm-link:hover'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-cm-link:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_cm_item_title_color_hover',
            [
                'label'     => esc_html__( 'Title Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'condition' => [
                    'ua_cm_hide_titles!'  => 'yes',
                    'ua_cm_title_display' => 'inside',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-cm-link:hover .ua-cm-item-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_cm_item_shadow_hover',
                'selector' => '{{WRAPPER}} .ua-cm-link:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'ua_cm_item_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 10,
                        'max'  => 36,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 18,
                ],
                'separator'  => 'before',
                'selectors'  => [
                    '{{WRAPPER}} .ua-cm-link :is(i, svg)' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_cm_item_border',
                'selector' => '{{WRAPPER}} .ua-cm-link',
            ]
        );

        $this->add_responsive_control(
            'ua_cm_item_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'    => [
                    'top'      => '50',
                    'right'    => '50',
                    'bottom'   => '50',
                    'left'     => '50',
                    'unit'     => '%',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-cm-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'ua_cm_item_typography',
                'condition' => [
                    'ua_cm_hide_titles!'  => 'yes',
                    'ua_cm_title_display' => 'inside',
                ],
                'selector'  => '{{WRAPPER}} .ua-cm-item-title',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab: Tooltip
     */
    protected function ua_tooltip_style_controls() {
        $this->start_controls_section(
            '_ua_cm_tooltip_style_section',
            [
                'label'     => esc_html__( 'Tooltip', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ua_cm_hide_titles!'  => 'yes',
                    'ua_cm_title_display' => 'tooltip',
                ],
            ]
        );

        $this->add_control(
            'ua_cm_tooltip_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1e293b',
                'selectors' => [
                    '{{WRAPPER}} .ua-cm-tooltip' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-cm-tooltip::after' => 'border-top-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_cm_tooltip_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-cm-tooltip' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_cm_tooltip_typography',
                'selector' => '{{WRAPPER}} .ua-cm-tooltip',
            ]
        );

        $this->add_responsive_control(
            'ua_cm_tooltip_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default'    => [
                    'top'      => '4',
                    'right'    => '4',
                    'bottom'   => '4',
                    'left'     => '4',
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-cm-tooltip' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on frontend
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['ua_cm_items'] ) ) {
            return;
        }

        $direction      = $settings['ua_cm_direction'] ?? 'full';
        $trigger_action = $settings['ua_cm_trigger_action'] ?? 'hover';
        $hide_titles    = 'yes' === ( $settings['ua_cm_hide_titles'] ?? 'no' );
        $title_display  = $hide_titles ? 'none' : ( ! empty( $settings['ua_cm_title_display'] ) ? $settings['ua_cm_title_display'] : 'inside' );
        $radius         = isset( $settings['ua_cm_distance']['size'] ) ? (float) $settings['ua_cm_distance']['size'] : 120;
        $speed          = isset( $settings['ua_cm_speed']['size'] ) ? (int) $settings['ua_cm_speed']['size'] : ( ! empty( $settings['ua_cm_speed'] ) && is_numeric( $settings['ua_cm_speed'] ) ? (int) $settings['ua_cm_speed'] : 500 );
        $delay          = isset( $settings['ua_cm_delay']['size'] ) ? (int) $settings['ua_cm_delay']['size'] : ( ! empty( $settings['ua_cm_delay'] ) && is_numeric( $settings['ua_cm_delay'] ) ? (int) $settings['ua_cm_delay'] : 150 );
        $step_out       = isset( $settings['ua_cm_step_out']['size'] ) ? (int) $settings['ua_cm_step_out']['size'] : ( isset( $settings['ua_cm_step_out'] ) && is_numeric( $settings['ua_cm_step_out'] ) ? (int) $settings['ua_cm_step_out'] : 40 );
        $step_in        = isset( $settings['ua_cm_step_in']['size'] ) ? (int) $settings['ua_cm_step_in']['size'] : ( isset( $settings['ua_cm_step_in'] ) && is_numeric( $settings['ua_cm_step_in'] ) ? (int) $settings['ua_cm_step_in'] : -30 );
        $transition     = ! empty( $settings['ua_cm_transition'] ) ? $settings['ua_cm_transition'] : 'ease';

        $config = [
            'direction'  => $direction,
            'trigger'    => $trigger_action,
            'radius'     => $radius,
            'speed'      => $speed,
            'delay'      => $delay,
            'stepOut'    => $step_out,
            'stepIn'     => $step_in,
            'transition' => $transition,
        ];

        $align = ! empty( $settings['ua_cm_align'] ) ? $settings['ua_cm_align'] : 'center';

        $this->add_render_attribute( 'wrapper', [
            'class'         => [
                'ua-circle-menu-wrap',
                'ua-cm-trigger-' . esc_attr( $trigger_action ),
                'ua-cm-titles-' . esc_attr( $title_display ),
                'ua-cm-align-' . esc_attr( $align ),
            ],
            'data-settings' => wp_json_encode( $config ),
        ] );

        $aria_label = ! empty( $settings['ua_cm_trigger_aria'] ) ? $settings['ua_cm_trigger_aria'] : esc_html__( 'Toggle Menu', 'ultraaddons-elementor-lite' );
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <div class="ua-circle-menu-inner">
                <button type="button" class="ua-cm-trigger" aria-label="<?php echo esc_attr( $aria_label ); ?>" aria-expanded="false">
                    <span class="ua-cm-icon-open">
                        <?php Icons_Manager::render_icon( $settings['ua_cm_open_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                    </span>
                    <span class="ua-cm-icon-close">
                        <?php Icons_Manager::render_icon( $settings['ua_cm_close_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                    </span>
                </button>

                <ul class="ua-cm-items" role="menu">
                    <?php foreach ( $settings['ua_cm_items'] as $index => $item ) :
                        $item_id   = $item['_id'];
                        $link_key  = 'link_' . $item_id;
                        $has_link  = ! empty( $item['item_link']['url'] );

                        if ( $has_link ) {
                            $this->add_link_attributes( $link_key, $item['item_link'] );
                        }
                        $this->add_render_attribute( $link_key, 'class', 'ua-cm-link' );
                        $this->add_render_attribute( $link_key, 'role', 'menuitem' );
                        if ( ! empty( $item['item_title'] ) ) {
                            $this->add_render_attribute( $link_key, 'aria-label', $item['item_title'] );
                        }
                    ?>
                        <li class="ua-cm-item elementor-repeater-item-<?php echo esc_attr( $item_id ); ?>" data-index="<?php echo esc_attr( $index ); ?>">
                            <<?php echo $has_link ? 'a' : 'div'; ?> <?php echo $this->get_render_attribute_string( $link_key ); ?>>
                                <span class="ua-cm-item-icon">
                                    <?php if ( ! empty( $item['item_icon']['value'] ) ) : ?>
                                        <?php Icons_Manager::render_icon( $item['item_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                    <?php endif; ?>
                                </span>

                                <?php if ( 'inside' === $title_display && ! empty( $item['item_title'] ) ) : ?>
                                    <span class="ua-cm-item-title"><?php echo esc_html( $item['item_title'] ); ?></span>
                                <?php elseif ( 'tooltip' === $title_display && ! empty( $item['item_title'] ) ) : ?>
                                    <span class="ua-cm-tooltip"><?php echo esc_html( $item['item_title'] ); ?></span>
                                <?php endif; ?>
                            </<?php echo $has_link ? 'a' : 'div'; ?>>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php
    }
}
