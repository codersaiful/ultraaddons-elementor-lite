<?php
namespace UltraAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * UltraAddons Back to Top Widget
 *
 * A fixed or inline "scroll to top" button with customizable icon,
 * text, animation, and full styling controls.
 *
 * @since 2.0.4
 * @package UltraAddons
 */
class Back_To_Top extends Base {

    /**
     * Widget keywords for Elementor search.
     *
     * @return array
     */
    public function get_keywords() {
        return [ 'ultraaddons', 'ua', 'back to top', 'scroll', 'scroll to top', 'top', 'arrow' ];
    }

    /**
     * Register controls.
     */
    protected function register_controls() {
        $this->ua_content_general_controls();
        $this->ua_style_button_controls();
    }

    /* =========================================================================
       CONTENT TAB — General
       ========================================================================= */
    protected function ua_content_general_controls() {

        $this->start_controls_section(
            '_ua_stt_content_section',
            [
                'label' => esc_html__( 'General', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        /* --- Position: Fixed / Inline --- */
        $this->add_control(
            'ua_stt_position',
            [
                'label'       => esc_html__( 'Button Position', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'fixed',
                'label_block' => false,
                'options'     => [
                    'fixed'  => esc_html__( 'Fixed', 'ultraaddons-elementor-lite' ),
                    'inline' => esc_html__( 'Inline', 'ultraaddons-elementor-lite' ),
                ],
                'render_type'  => 'template',
                'prefix_class' => 'ua-stt-pos-',
            ]
        );

        /* --- Inline alignment --- */
        $this->add_responsive_control(
            'ua_stt_inline_align',
            [
                'label'   => esc_html__( 'Inline Alignment', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-stt-wrapper' => 'justify-content: {{VALUE}};',
                ],
                'condition' => [
                    'ua_stt_position' => 'inline',
                ],
                'separator' => 'before',
            ]
        );

        /* --- Fixed corner --- */
        $this->add_control(
            'ua_stt_fixed_corner',
            [
                'label'   => esc_html__( 'Fixed Corner', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'right',
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Bottom Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Bottom Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'condition'    => [ 'ua_stt_position' => 'fixed' ],
                'prefix_class' => 'ua-stt-corner-',
                'render_type'  => 'template',
                'separator'    => 'before',
            ]
        );

        /* --- Distance X (right) --- */
        $this->add_responsive_control(
            'ua_stt_distance_x_right',
            [
                'label'      => esc_html__( 'Distance Right', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 300 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 30 ],
                'selectors'  => [
                    '{{WRAPPER}}.ua-stt-corner-right .ua-stt-btn, {{WRAPPER}} .ua-stt-fixed-right' => 'right: {{SIZE}}{{UNIT}} !important; left: auto !important;',
                ],
                'condition' => [
                    'ua_stt_position'     => 'fixed',
                    'ua_stt_fixed_corner' => 'right',
                ],
            ]
        );

        /* --- Distance Y (right) --- */
        $this->add_responsive_control(
            'ua_stt_distance_y_right',
            [
                'label'      => esc_html__( 'Distance Bottom', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 300 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 30 ],
                'selectors'  => [
                    '{{WRAPPER}}.ua-stt-corner-right .ua-stt-btn, {{WRAPPER}} .ua-stt-fixed-right' => 'bottom: {{SIZE}}{{UNIT}} !important;',
                ],
                'condition' => [
                    'ua_stt_position'     => 'fixed',
                    'ua_stt_fixed_corner' => 'right',
                ],
            ]
        );

        /* --- Distance X (left) --- */
        $this->add_responsive_control(
            'ua_stt_distance_x_left',
            [
                'label'      => esc_html__( 'Distance Left', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 300 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 30 ],
                'selectors'  => [
                    '{{WRAPPER}}.ua-stt-corner-left .ua-stt-btn, {{WRAPPER}} .ua-stt-fixed-left' => 'left: {{SIZE}}{{UNIT}} !important; right: auto !important;',
                ],
                'condition' => [
                    'ua_stt_position'     => 'fixed',
                    'ua_stt_fixed_corner' => 'left',
                ],
            ]
        );

        /* --- Distance Y (left) --- */
        $this->add_responsive_control(
            'ua_stt_distance_y_left',
            [
                'label'      => esc_html__( 'Distance Bottom', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 300 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 30 ],
                'selectors'  => [
                    '{{WRAPPER}}.ua-stt-corner-left .ua-stt-btn, {{WRAPPER}} .ua-stt-fixed-left' => 'bottom: {{SIZE}}{{UNIT}} !important;',
                ],
                'condition' => [
                    'ua_stt_position'     => 'fixed',
                    'ua_stt_fixed_corner' => 'left',
                ],
            ]
        );

        /* --- Icon --- */
        $this->add_control(
            'ua_stt_icon',
            [
                'label'       => esc_html__( 'Select Icon', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::ICONS,
                'skin'        => 'inline',
                'label_block' => false,
                'default'     => [
                    'value'   => 'fas fa-angle-up',
                    'library' => 'fa-solid',
                ],
                'recommended' => [
                    'fa-solid' => [ 'angle-up', 'arrow-up', 'chevron-up', 'arrow-circle-up', 'long-arrow-alt-up' ],
                ],
                'separator' => 'before',
            ]
        );

        /* --- Button Text Show/Hide --- */
        $this->add_control(
            'ua_stt_text_show',
            [
                'label'        => esc_html__( 'Button Text', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'render_type'  => 'template',
            ]
        );

        /* --- Icon Layout --- */
        $this->add_control(
            'ua_stt_icon_layout',
            [
                'label'   => esc_html__( 'Icon Align', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'top'    => esc_html__( 'Top', 'ultraaddons-elementor-lite' ),
                    'bottom' => esc_html__( 'Bottom', 'ultraaddons-elementor-lite' ),
                    'left'   => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                    'right'  => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                ],
                'condition'    => [
                    'ua_stt_icon[value]!' => '',
                    'ua_stt_text_show'    => 'yes',
                ],
                'prefix_class' => 'ua-stt-icon-',
            ]
        );

        /* --- Button Text --- */
        $this->add_control(
            'ua_stt_text',
            [
                'label'     => esc_html__( 'Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => 'Back to Top',
                'dynamic'   => [ 'active' => true ],
                'condition' => [ 'ua_stt_text_show' => 'yes' ],
            ]
        );

        /* --- Animation Type --- */
        $this->add_control(
            'ua_stt_animation',
            [
                'label'   => esc_html__( 'Animation', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'fade',
                'options' => [
                    'fade'  => esc_html__( 'Fade', 'ultraaddons-elementor-lite' ),
                    'slide' => esc_html__( 'Slide', 'ultraaddons-elementor-lite' ),
                    'none'  => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                ],
                'separator' => 'before',
                'condition' => [ 'ua_stt_position' => 'fixed' ],
            ]
        );

        /* --- Scroll Offset --- */
        $this->add_responsive_control(
            'ua_stt_offset',
            [
                'label'     => esc_html__( 'Show after scroll (px)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 300,
                'min'       => 0,
                'condition' => [ 'ua_stt_position' => 'fixed' ],
            ]
        );

        /* --- Animation Speed --- */
        $this->add_control(
            'ua_stt_anim_speed',
            [
                'label'     => esc_html__( 'Show up speed (ms)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 200,
                'min'       => 0,
                'condition' => [
                    'ua_stt_position'   => 'fixed',
                    'ua_stt_animation!' => 'none',
                ],
            ]
        );

        /* --- Scroll Speed --- */
        $this->add_control(
            'ua_stt_scroll_speed',
            [
                'label'   => esc_html__( 'Scrolling speed (ms)', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 800,
                'min'     => 0,
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE TAB — Button
       ========================================================================= */
    protected function ua_style_button_controls() {

        $this->start_controls_section(
            '_ua_stt_style_section',
            [
                'label' => esc_html__( 'Button', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        /* --- Normal / Hover Tabs --- */
        $this->start_controls_tabs( 'ua_stt_color_tabs' );

        /* Normal */
        $this->start_controls_tab(
            'ua_stt_tab_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'ua_stt_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-stt-btn'         => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-stt-icon'         => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-stt-icon svg'     => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_stt_bg',
            [
                'label'     => esc_html__( 'Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#7320e4',
                'selectors' => [
                    '{{WRAPPER}} .ua-stt-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_stt_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#7320e4',
                'selectors' => [
                    '{{WRAPPER}} .ua-stt-btn' => 'border-color: {{VALUE}};',
                ],
                'condition' => [ 'ua_stt_border_switch' => 'yes' ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_stt_shadow',
                'selector' => '{{WRAPPER}} .ua-stt-btn',
            ]
        );

        $this->end_controls_tab();

        /* Hover */
        $this->start_controls_tab(
            'ua_stt_tab_hover',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'ua_stt_color_hover',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-stt-btn:hover'             => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-stt-btn:hover .ua-stt-icon'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-stt-btn:hover .ua-stt-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_stt_bg_hover',
            [
                'label'     => esc_html__( 'Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#5419b4',
                'selectors' => [
                    '{{WRAPPER}} .ua-stt-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_stt_border_color_hover',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#5419b4',
                'selectors' => [
                    '{{WRAPPER}} .ua-stt-btn:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [ 'ua_stt_border_switch' => 'yes' ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_stt_shadow_hover',
                'selector' => '{{WRAPPER}} .ua-stt-btn:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        /* --- Hover Transition Duration --- */
        $this->add_control(
            'ua_stt_hover_duration',
            [
                'label'     => esc_html__( 'Hover Transition (s)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 0,
                'max'       => 5,
                'step'      => 0.1,
                'default'   => 0.3,
                'selectors' => [
                    '{{WRAPPER}} .ua-stt-btn'     => 'transition: color {{VALUE}}s ease-in-out, background-color {{VALUE}}s ease-in-out, border-color {{VALUE}}s ease-in-out, box-shadow {{VALUE}}s ease-in-out, transform {{VALUE}}s ease-in-out;',
                    '{{WRAPPER}} .ua-stt-btn svg' => 'transition: fill {{VALUE}}s ease-in-out;',
                ],
                'separator' => 'before',
            ]
        );

        /* --- Typography --- */
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'ua_stt_typography',
                'selector'  => '{{WRAPPER}} .ua-stt-text',
                'separator' => 'before',
                'condition' => [ 'ua_stt_text_show' => 'yes' ],
            ]
        );

        /* --- Icon Size --- */
        $this->add_control(
            'ua_stt_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 16 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-stt-icon'     => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-stt-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
                'condition' => [ 'ua_stt_icon[value]!' => '' ],
            ]
        );

        /* --- Icon Distance --- */
        $this->add_control(
            'ua_stt_icon_distance',
            [
                'label'      => esc_html__( 'Icon Distance', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 25 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 8 ],
                'selectors'  => [
                    '{{WRAPPER}}.ua-stt-icon-top .ua-stt-icon'    => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ua-stt-icon-left .ua-stt-icon'   => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ua-stt-icon-right .ua-stt-icon'  => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ua-stt-icon-bottom .ua-stt-icon' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'ua_stt_icon[value]!' => '',
                    'ua_stt_text_show'    => 'yes',
                ],
            ]
        );

        /* --- Padding --- */
        $this->add_responsive_control(
            'ua_stt_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default'    => [
                    'top'    => 15,
                    'right'  => 15,
                    'bottom' => 15,
                    'left'   => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-stt-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        /* --- Border Switch --- */
        $this->add_control(
            'ua_stt_border_switch',
            [
                'label'        => esc_html__( 'Border', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator'    => 'before',
            ]
        );

        /* --- Border Type --- */
        $this->add_control(
            'ua_stt_border_type',
            [
                'label'     => esc_html__( 'Border Type', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'none'   => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                    'solid'  => esc_html__( 'Solid', 'ultraaddons-elementor-lite' ),
                    'double' => esc_html__( 'Double', 'ultraaddons-elementor-lite' ),
                    'dotted' => esc_html__( 'Dotted', 'ultraaddons-elementor-lite' ),
                    'dashed' => esc_html__( 'Dashed', 'ultraaddons-elementor-lite' ),
                ],
                'default'   => 'solid',
                'selectors' => [
                    '{{WRAPPER}} .ua-stt-btn' => 'border-style: {{VALUE}};',
                ],
                'condition' => [ 'ua_stt_border_switch' => 'yes' ],
            ]
        );

        /* --- Border Width --- */
        $this->add_responsive_control(
            'ua_stt_border_width',
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
                'selectors' => [
                    '{{WRAPPER}} .ua-stt-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'ua_stt_border_switch' => 'yes',
                    'ua_stt_border_type!'  => 'none',
                ],
            ]
        );

        /* --- Border Radius --- */
        $this->add_responsive_control(
            'ua_stt_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'    => [
                    'top'    => 4,
                    'right'  => 4,
                    'bottom' => 4,
                    'left'   => 4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-stt-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       RENDER
       ========================================================================= */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $position    = ! empty( $settings['ua_stt_position'] ) ? $settings['ua_stt_position'] : 'fixed';
        $corner      = ! empty( $settings['ua_stt_fixed_corner'] ) ? $settings['ua_stt_fixed_corner'] : 'right';
        $animation   = ! empty( $settings['ua_stt_animation'] ) ? $settings['ua_stt_animation'] : 'fade';
        $offset      = isset( $settings['ua_stt_offset'] ) ? absint( $settings['ua_stt_offset'] ) : 300;
        $anim_speed  = isset( $settings['ua_stt_anim_speed'] ) ? absint( $settings['ua_stt_anim_speed'] ) : 200;
        $scroll_speed = isset( $settings['ua_stt_scroll_speed'] ) ? absint( $settings['ua_stt_scroll_speed'] ) : 800;

        $btn_classes = [ 'ua-stt-btn' ];
        if ( 'fixed' === $position ) {
            $btn_classes[] = 'ua-stt-fixed';
            $btn_classes[] = 'ua-stt-fixed-' . esc_attr( $corner );
        }

        $stt_data = [
            'position'    => esc_attr( $position ),
            'animation'   => esc_attr( $animation ),
            'offset'      => $offset,
            'animSpeed'   => $anim_speed,
            'scrollSpeed' => $scroll_speed,
        ];

        $aria_label = ! empty( $settings['ua_stt_text'] ) ? $settings['ua_stt_text'] : esc_html__( 'Back to top', 'ultraaddons-elementor-lite' );

        echo '<div class="ua-stt-wrapper ua-stt-wrapper-' . esc_attr( $position ) . '">';
        echo '<button type="button" class="' . esc_attr( implode( ' ', $btn_classes ) ) . '" aria-label="' . esc_attr( $aria_label ) . '" data-settings="' . esc_attr( wp_json_encode( $stt_data ) ) . '">';

        // Icon
        if ( ! empty( $settings['ua_stt_icon']['value'] ) ) {
            echo '<span class="ua-stt-icon">';
            \Elementor\Icons_Manager::render_icon( $settings['ua_stt_icon'], [ 'aria-hidden' => 'true' ] );
            echo '</span>';
        }

        // Text
        if ( 'yes' === ( $settings['ua_stt_text_show'] ?? '' ) && ! empty( $settings['ua_stt_text'] ) ) {
            echo '<span class="ua-stt-text">' . esc_html( $settings['ua_stt_text'] ) . '</span>';
        }

        echo '</button>';
        echo '</div>';
    }
}
