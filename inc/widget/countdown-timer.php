<?php
namespace UltraAddons\Widget; 

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Countdown_Timer extends Base {
    
    /**
     * Get widget keywords.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array
     */
    public function get_keywords() {
        return [ 'ultraaddons-elementor-lite', 'ua', 'timer', 'count', 'down', 'countdown', 'count down timer', 'count timer', 'clock', 'watch', 'royal' ];
    }
    
    /**
     * Register widget controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls() {
        $this->content_general_controls();
        $this->content_label();
        $this->content_expire_controls();

        $this->style_container_controls();
        $this->style_boxes_controls();
        $this->style_numbers_controls();
        $this->style_labels_controls();
        $this->style_separator_controls();
        $this->style_expire_controls();
    }

    /**
     * General Content Controls
     * 
     * @since 1.0.0.9
     */
    protected function content_general_controls() {
        $this->start_controls_section(
            'general_content',
            [
                'label' => esc_html__( 'General', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'date_time',
            [
                'label'       => esc_html__( 'Due Date', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::DATE_TIME,
                'default'     => date( 'Y-m-d H:i', strtotime( '+1 month' ) ),
                'dynamic'     => [ 'active' => true ],
                'description' => esc_html__( 'Set the date and time when countdown timer ends.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'show_days',
            [
                'label'        => esc_html__( 'Show Days', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'show_hours',
            [
                'label'        => esc_html__( 'Show Hours', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'show_minutes',
            [
                'label'        => esc_html__( 'Show Minutes', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'show_seconds',
            [
                'label'        => esc_html__( 'Show Seconds', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'general_timer_controls',
            [
                'label' => esc_html__( 'Timer Controls & Layout', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'view',
            [
                'label'        => esc_html__( 'View', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SELECT,
                'options'      => [
                    'default' => esc_html__( 'Default', 'ultraaddons-elementor-lite' ),
                    'stacked' => esc_html__( 'Stacked', 'ultraaddons-elementor-lite' ),
                    'framed'  => esc_html__( 'Framed', 'ultraaddons-elementor-lite' ),
                ],
                'default'      => 'framed',
                'prefix_class' => 'elementor-view-',
            ]
        );
        
        $this->add_control(
            'shape',
            [
                'label'        => esc_html__( 'Shape', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SELECT,
                'options'      => [
                    'circle' => esc_html__( 'Circle', 'ultraaddons-elementor-lite' ),
                    'square' => esc_html__( 'Square', 'ultraaddons-elementor-lite' ),
                ],
                'default'      => 'circle',
                'condition'    => [
                    'view!' => 'default',
                ],
                'prefix_class' => 'elementor-shape-',
            ]
        );
        
        $this->add_control(
            'show_separator',
            [
                'label'        => esc_html__( 'Show Separators', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'separator_text',
            [
                'label'       => esc_html__( 'Separator Symbol', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => ':',
                'placeholder' => ':',
                'condition'   => [
                    'show_separator' => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'box_size',
            [
                'label'      => esc_html__( 'Box Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 50,
                        'max'  => 300,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 120,
                ],
                'condition'  => [
                    'view!' => 'default',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .single-date' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'box_gap',
            [
                'label'      => esc_html__( 'Box Gap / Gutter', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 80,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .single-date' => 'margin-left: calc({{SIZE}}{{UNIT}}/2); margin-right: calc({{SIZE}}{{UNIT}}/2);',
                ],
            ]
        );
        
        $this->end_controls_section();
    }

    /**
     * Labels Content Controls
     * 
     * @since 1.0.0
     */
    protected function content_label() {
        $this->start_controls_section(
            'label',
            [
                'label' => esc_html__( 'Labels', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_labels',
            [
                'label'        => esc_html__( 'Show Labels', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'labels_position',
            [
                'label'     => esc_html__( 'Label Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'below',
                'options'   => [
                    'below'  => esc_html__( 'Below Numbers (Bottom)', 'ultraaddons-elementor-lite' ),
                    'above'  => esc_html__( 'Above Numbers (Top)', 'ultraaddons-elementor-lite' ),
                    'inline' => esc_html__( 'Inline (Next to Numbers)', 'ultraaddons-elementor-lite' ),
                    'block'  => esc_html__( 'Below Numbers (Legacy Block)', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'show_labels' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-coun-down-timer .single-date' => 'flex-direction: {{VALUE}} == "inline" ? "row" : "column";',
                    '{{WRAPPER}} .ua-coun-down-timer .timer_label' => 'order: {{VALUE}} == "above" ? -1 : 1;',
                ],
            ]
        );
        $this->add_responsive_control(
            'labels_inline_align',
            [
                'label'     => esc_html__( 'Inline Label Vertical Align', 'ultraaddons-elementor-lite' ),
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
                'default'   => 'center',
                'condition' => [
                    'show_labels'     => 'yes',
                    'labels_position' => 'inline',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-coun-down-timer .single-date.is-inline' => 'align-items: {{VALUE}} !important;',
                ],
            ]
        );
        
        $this->add_control(
            'days',
            [
                'label'       => esc_html__( 'Days (Plural)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => __( 'Days', 'ultraaddons-elementor-lite' ),
                'default'     => esc_html__( 'Days', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'condition'   => [ 'show_labels' => 'yes' ],
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'days_singular',
            [
                'label'       => esc_html__( 'Day (Singular)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => __( 'Day', 'ultraaddons-elementor-lite' ),
                'default'     => esc_html__( 'Day', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'condition'   => [ 'show_labels' => 'yes' ],
            ]
        );
        
        $this->add_control(
            'hours',
            [
                'label'       => esc_html__( 'Hours (Plural)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => __( 'Hours', 'ultraaddons-elementor-lite' ),
                'default'     => esc_html__( 'Hours', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'condition'   => [ 'show_labels' => 'yes' ],
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'hours_singular',
            [
                'label'       => esc_html__( 'Hour (Singular)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => __( 'Hour', 'ultraaddons-elementor-lite' ),
                'default'     => esc_html__( 'Hour', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'condition'   => [ 'show_labels' => 'yes' ],
            ]
        );
        
        $this->add_control(
            'minutes',
            [
                'label'       => esc_html__( 'Minutes (Plural)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => __( 'Minutes', 'ultraaddons-elementor-lite' ),
                'default'     => esc_html__( 'Minutes', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'condition'   => [ 'show_labels' => 'yes' ],
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'minutes_singular',
            [
                'label'       => esc_html__( 'Minute (Singular)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => __( 'Minute', 'ultraaddons-elementor-lite' ),
                'default'     => esc_html__( 'Minute', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'condition'   => [ 'show_labels' => 'yes' ],
            ]
        );
        
        $this->add_control(
            'seconds',
            [
                'label'       => esc_html__( 'Seconds (Plural)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => __( 'Seconds', 'ultraaddons-elementor-lite' ),
                'default'     => esc_html__( 'Seconds', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'condition'   => [ 'show_labels' => 'yes' ],
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'seconds_singular',
            [
                'label'       => esc_html__( 'Second (Singular)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => __( 'Second', 'ultraaddons-elementor-lite' ),
                'default'     => esc_html__( 'Second', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'condition'   => [ 'show_labels' => 'yes' ],
            ]
        );
        
        $this->end_controls_section();
    }

    /**
     * Expire Actions Controls
     * 
     * @since 1.0.0
     */
    protected function content_expire_controls() {
        $this->start_controls_section(
            'expire_actions_section',
            [
                'label' => esc_html__( 'Expire Actions', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'expire_action',
            [
                'label'   => esc_html__( 'Action After Timer Expires', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none'     => esc_html__( 'None (Keep 00:00:00)', 'ultraaddons-elementor-lite' ),
                    'message'  => esc_html__( 'Display Custom Message', 'ultraaddons-elementor-lite' ),
                    'hide'     => esc_html__( 'Hide Countdown Timer', 'ultraaddons-elementor-lite' ),
                    'redirect' => esc_html__( 'Redirect to URL', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'expire_message',
            [
                'label'       => esc_html__( 'Display Message', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::WYSIWYG,
                'placeholder' => __( 'e.g. Sorry, this special offer has ended!', 'ultraaddons-elementor-lite' ),
                'default'     => __( 'Sorry, this special offer has ended!', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'expire_action' => 'message',
                ],
            ]
        );

        $this->add_control(
            'expire_redirect_url',
            [
                'label'       => esc_html__( 'Redirect URL', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => 'https://yourwebsite.com/offer-ended',
                'dynamic'     => [ 'active' => true ],
                'condition'   => [
                    'expire_action' => 'redirect',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Container & Alignment Style Section
     * 
     * @since 1.0.0
     */
    protected function style_container_controls() {
        $this->start_controls_section(
            'style_container',
            [
                'label' => esc_html__( 'Container & Layout', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'general_width',
            [
                'label'      => esc_html__( 'Container Max Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px' ],
                'range'      => [
                    '%'  => [ 'min' => 10, 'max' => 100 ],
                    'px' => [ 'min' => 200, 'max' => 1200 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-coun-down-timer-wrapper' => 'max-width: {{SIZE}}{{UNIT}}; margin-left: auto; margin-right: auto;',
                ],
            ]
        );

        $this->add_responsive_control(
            'timer_alignment',
            [
                'label'     => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center'     => [
                        'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'flex-end'   => [
                        'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .ua-coun-down-timer-wrapper .ua-coun-down-timer' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Item Boxes Style Section
     * 
     * @since 1.0.0
     */
    protected function style_boxes_controls() {
        $this->start_controls_section(
            'style_general',
            [
                'label' => esc_html__( 'Item Boxes', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'general_padding',
            [
                'label'      => esc_html__( 'Box Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .single-date' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .single-date' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'box_bg',
                'label'    => esc_html__( 'Box Background Gradient', 'ultraaddons-elementor-lite' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .single-date',
            ]
        );

        $this->add_control(
            'box_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .single-date' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'border',
                'label'    => esc_html__( 'Border', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .single-date',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'box_shadow',
                'label'    => esc_html__( 'Box Shadow', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .single-date',
            ]
        );
        
        $this->end_controls_section();
    }

    /**
     * Numbers Style Controls
     * 
     * @since 1.0.0
     */
    protected function style_numbers_controls() {
        $this->start_controls_section(
            'style_numbers_section',
            [
                'label' => esc_html__( 'Numbers', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'time_color',
            [
                'label'     => esc_html__( 'Numbers Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .single-date span.timer_int' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'numbers_bg_color',
            [
                'label'     => esc_html__( 'Numbers Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .single-date span.timer_int' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'int_typography',
                'label'    => esc_html__( 'Numbers Typography', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-coun-down-timer .timer_int',
            ]
        );

        $this->add_responsive_control(
            'numbers_padding',
            [
                'label'      => esc_html__( 'Numbers Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .single-date span.timer_int' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Labels Style Controls
     * 
     * @since 1.0.0
     */
    protected function style_labels_controls() {
        $this->start_controls_section(
            'style_labels_section',
            [
                'label' => esc_html__( 'Labels', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label'     => esc_html__( 'Labels Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .single-date span.timer_label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'labels_bg_color',
            [
                'label'     => esc_html__( 'Labels Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .single-date span.timer_label' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'label_typography',
                'label'    => esc_html__( 'Labels Typography', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-coun-down-timer .timer_label',
            ]
        );

        $this->add_responsive_control(
            'labels_padding',
            [
                'label'      => esc_html__( 'Labels Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .single-date span.timer_label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Separator Style Controls
     * 
     * @since 1.0.0
     */
    protected function style_separator_controls() {
        $this->start_controls_section(
            'separator_style_section',
            [
                'label'     => esc_html__( 'Separator Style', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_separator' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'separator_color',
            [
                'label'     => esc_html__( 'Separator Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-coun-down-timer-wrapper .sep' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'separator_size',
            [
                'label'      => esc_html__( 'Separator Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 10,
                        'max' => 80,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-coun-down-timer-wrapper .sep' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }
    
    /**
     * Expire Message Style Controls
     * 
     * @since 1.0.0
     */
    protected function style_expire_controls() {
        $this->start_controls_section(
            'expire_style_section',
            [
                'label'     => esc_html__( 'Expire Message', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'expire_action' => 'message',
                ],
            ]
        );

        $this->add_control(
            'expire_message_color',
            [
                'label'     => esc_html__( 'Message Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-timer-expire-message' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'expire_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-timer-expire-message' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'expire_typography',
                'label'    => esc_html__( 'Typography', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-timer-expire-message',
            ]
        );

        $this->add_responsive_control(
            'expire_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-timer-expire-message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on frontend.
     * 
     * @since 1.0.0
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $unique_class = 'ua-count-down-' . wp_rand( 509, 1254 );
        $this->add_render_attribute( 'wrapper', 'class', 'ua-coun-down-timer-wrapper' );
        
        $date = ! empty( $settings['date_time'] ) ? $settings['date_time'] : '';
        $timestamp = ! empty( $date ) ? strtotime( $date ) : strtotime( '+1 month' );
        $date_iso = date( 'Y-m-d\TH:i:s', $timestamp );
        
        // Plural / Singular Labels
        $days_plural     = ! empty( $settings['days'] ) ? $settings['days'] : __( 'Days', 'ultraaddons-elementor-lite' );
        $days_singular   = ! empty( $settings['days_singular'] ) ? $settings['days_singular'] : __( 'Day', 'ultraaddons-elementor-lite' );
        
        $hours_plural    = ! empty( $settings['hours'] ) ? $settings['hours'] : __( 'Hours', 'ultraaddons-elementor-lite' );
        $hours_singular  = ! empty( $settings['hours_singular'] ) ? $settings['hours_singular'] : __( 'Hour', 'ultraaddons-elementor-lite' );
        
        $minutes_plural  = ! empty( $settings['minutes'] ) ? $settings['minutes'] : __( 'Minutes', 'ultraaddons-elementor-lite' );
        $minutes_singular= ! empty( $settings['minutes_singular'] ) ? $settings['minutes_singular'] : __( 'Minute', 'ultraaddons-elementor-lite' );
        
        $seconds_plural  = ! empty( $settings['seconds'] ) ? $settings['seconds'] : __( 'Seconds', 'ultraaddons-elementor-lite' );
        $seconds_singular= ! empty( $settings['seconds_singular'] ) ? $settings['seconds_singular'] : __( 'Second', 'ultraaddons-elementor-lite' );

        $show_labels = ! empty( $settings['show_labels'] ) && 'yes' === $settings['show_labels'];
        $labels_pos  = ! empty( $settings['labels_position'] ) ? $settings['labels_position'] : 'block';
        
        /**
         * Filter for Changing Date and time.
         * 
         * @since 1.0.0.9
         */
        $date_iso = apply_filters( 'ultraaddons/widget/count-down-timer/date_time', $date_iso, $this->get_name(), $this->get_id(), $this );

        $sep_symbol = isset( $settings['separator_text'] ) && '' !== $settings['separator_text'] ? $settings['separator_text'] : ':';
        $separator  = isset( $settings['show_separator'] ) && 'yes' === $settings['show_separator'] ? '<div class="sep"><span>' . esc_html( $sep_symbol ) . '</span></div>' : '';
        
        $expire_action       = ! empty( $settings['expire_action'] ) ? $settings['expire_action'] : 'none';
        $expire_message      = ! empty( $settings['expire_message'] ) ? $settings['expire_message'] : '';
        $expire_redirect_url = ! empty( $settings['expire_redirect_url']['url'] ) ? esc_url( $settings['expire_redirect_url']['url'] ) : '';

        $show_days    = ! empty( $settings['show_days'] ) && 'yes' === $settings['show_days'];
        $show_hours   = ! empty( $settings['show_hours'] ) && 'yes' === $settings['show_hours'];
        $show_minutes = ! empty( $settings['show_minutes'] ) && 'yes' === $settings['show_minutes'];
        $show_seconds = ! empty( $settings['show_seconds'] ) && 'yes' === $settings['show_seconds'];
        $pos_class    = ( 'inline' === $labels_pos ) ? 'is-inline' : ( ( 'above' === $labels_pos ) ? 'is-above' : 'is-below' );
        ?>
    <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
        
        <div class="ua-coun-down-timer <?php echo esc_attr( $unique_class ); ?>">
            <?php if ( $show_days ) : ?>
                <div class="single-date date-days <?php echo esc_attr( $pos_class ); ?>">
                    <span class="timer_int days">00</span>
                    <?php if ( $show_labels ) : ?>
                        <span class="timer_label label-days"><?php echo esc_html( $days_plural ); ?></span>
                    <?php endif; ?>
                </div>
                <?php if ( $show_hours || $show_minutes || $show_seconds ) echo $separator; ?>
            <?php endif; ?>

            <?php if ( $show_hours ) : ?>
                <div class="single-date date-hours <?php echo esc_attr( $pos_class ); ?>">
                    <span class="timer_int hrs">00</span>
                    <?php if ( $show_labels ) : ?>
                        <span class="timer_label label-hrs"><?php echo esc_html( $hours_plural ); ?></span>
                    <?php endif; ?>
                </div>
                <?php if ( $show_minutes || $show_seconds ) echo $separator; ?>
            <?php endif; ?>

            <?php if ( $show_minutes ) : ?>
                <div class="single-date date-minutes <?php echo esc_attr( $pos_class ); ?>">
                    <span class="timer_int mnts">00</span>
                    <?php if ( $show_labels ) : ?>
                        <span class="timer_label label-mnts"><?php echo esc_html( $minutes_plural ); ?></span>
                    <?php endif; ?>
                </div>
                <?php if ( $show_seconds ) echo $separator; ?>
            <?php endif; ?>

            <?php if ( $show_seconds ) : ?>
                <div class="single-date date-seconds <?php echo esc_attr( $pos_class ); ?>">
                    <span class="timer_int secs">00</span>
                    <?php if ( $show_labels ) : ?>
                        <span class="timer_label label-secs"><?php echo esc_html( $seconds_plural ); ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if ( 'message' === $expire_action && ! empty( $expire_message ) ) : ?>
            <div class="ua-timer-expire-message <?php echo esc_attr( $unique_class ); ?>-expire" style="display:none; text-align: center; margin-top: 15px;">
                <?php echo wp_kses_post( $expire_message ); ?>
            </div>
        <?php endif; ?>
    </div>

    <script type="text/javascript">
    (function() {
        var labelsConfig = {
            days: { singular: '<?php echo esc_js( $days_singular ); ?>', plural: '<?php echo esc_js( $days_plural ); ?>' },
            hours: { singular: '<?php echo esc_js( $hours_singular ); ?>', plural: '<?php echo esc_js( $hours_plural ); ?>' },
            minutes: { singular: '<?php echo esc_js( $minutes_singular ); ?>', plural: '<?php echo esc_js( $minutes_plural ); ?>' },
            seconds: { singular: '<?php echo esc_js( $seconds_singular ); ?>', plural: '<?php echo esc_js( $seconds_plural ); ?>' }
        };

        function getTimeRemaining(endtime) {
            var finis_time = Date.parse(endtime);
            var current_time = Date.parse(new Date());
            var total = finis_time - current_time;

            if (isNaN(total) || total <= 0) {
                return { total: 0, days: 0, hours: 0, minutes: 0, seconds: 0, isExpired: true };
            }

            var seconds = Math.floor((total / 1000) % 60);
            var minutes = Math.floor((total / 1000 / 60) % 60);
            var hours = Math.floor((total / (1000 * 60 * 60)) % 24);
            var days = Math.floor(total / (1000 * 60 * 60 * 24));

            return { total: total, days: days, hours: hours, minutes: minutes, seconds: seconds, isExpired: false };
        }

        function initializeClock(endtime) {
            try {
                var clock = document.querySelector('.<?php echo esc_js( $unique_class ); ?>');
                if (!clock) return;

                var daysSpan = clock.querySelector('.days');
                var hoursSpan = clock.querySelector('.hrs');
                var minutesSpan = clock.querySelector('.mnts');
                var secondsSpan = clock.querySelector('.secs');

                var daysLabel = clock.querySelector('.label-days');
                var hrsLabel = clock.querySelector('.label-hrs');
                var mntsLabel = clock.querySelector('.label-mnts');
                var secsLabel = clock.querySelector('.label-secs');

                var expireMessage = document.querySelector('.<?php echo esc_js( $unique_class ); ?>-expire');
                var expireAction = '<?php echo esc_js( $expire_action ); ?>';
                var redirectUrl = '<?php echo esc_js( $expire_redirect_url ); ?>';

                function updateClock() {
                    var t = getTimeRemaining(endtime);

                    if (daysSpan) daysSpan.innerHTML = t.days;
                    if (hoursSpan) hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
                    if (minutesSpan) minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
                    if (secondsSpan) secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);

                    // Singular vs Plural text auto-switch
                    if (daysLabel) daysLabel.innerHTML = (t.days === 1) ? labelsConfig.days.singular : labelsConfig.days.plural;
                    if (hrsLabel) hrsLabel.innerHTML = (t.hours === 1) ? labelsConfig.hours.singular : labelsConfig.hours.plural;
                    if (mntsLabel) mntsLabel.innerHTML = (t.minutes === 1) ? labelsConfig.minutes.singular : labelsConfig.minutes.plural;
                    if (secsLabel) secsLabel.innerHTML = (t.seconds === 1) ? labelsConfig.seconds.singular : labelsConfig.seconds.plural;

                    if (t.isExpired) {
                        if (typeof timeinterval !== 'undefined') clearInterval(timeinterval);

                        if ('hide' === expireAction) {
                            clock.style.display = 'none';
                        } else if ('message' === expireAction && expireMessage) {
                            expireMessage.style.display = 'block';
                        } else if ('redirect' === expireAction && redirectUrl) {
                            window.location.href = redirectUrl;
                        }
                    }
                }

                updateClock();
                var timeinterval = setInterval(updateClock, 1000);
            } catch(e) {
                return;
            }
        }

        var deadline = '<?php echo esc_js( $date_iso ); ?>';
        initializeClock(deadline);
    })();
    </script>
        <?php
    }
}