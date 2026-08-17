<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Utils;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * UltraAddons Countdown Timer Widget
 * 
 * High-performance, multi-type countdown timer (Due Date & Evergreen)
 * with expired actions, custom labels, separators, and advanced styling.
 * 
 * @since 2.0.3
 */
class Countdown_Timer extends Base {

    /**
     * Constructor — register style and script dependencies
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        wp_register_style(
            'ultraaddons-countdown-timer',
            ULTRA_ADDONS_ASSETS . 'css/widgets/countdown-timer.css',
            [],
            ULTRA_ADDONS_VERSION,
            'all'
        );
        wp_enqueue_style( 'ultraaddons-countdown-timer' );

        wp_register_script(
            'ultraaddons-countdown-timer',
            ULTRA_ADDONS_ASSETS . 'js/frontend-countdown-timer.js',
            [ 'jquery' ],
            ULTRA_ADDONS_VERSION,
            true
        );
        wp_enqueue_script( 'ultraaddons-countdown-timer' );
    }

    public function get_style_depends() {
        return [ 'ultraaddons-countdown-timer' ];
    }

    public function get_script_depends() {
        return [ 'jquery', 'ultraaddons-countdown-timer' ];
    }

    public function is_reload_preview_required() {
        return true;
    }

    public function get_keywords() {
        return [ 'ultraaddons-elementor-lite', 'ua', 'countdown', 'timer', 'clock', 'evergreen', 'due date', 'watch' ];
    }

    /**
     * Get Saved Elementor Templates list
     */
    protected function get_elementor_templates() {
        $templates = get_posts([
            'post_type'      => 'elementor_library',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        ]);

        $options = [ '' => esc_html__( '— Select Template —', 'ultraaddons-elementor-lite' ) ];

        if ( ! empty( $templates ) && ! is_wp_error( $templates ) ) {
            foreach ( $templates as $template ) {
                $options[ $template->ID ] = $template->post_title;
            }
        }

        return $options;
    }

    /**
     * Register Widget Controls
     */
    protected function register_controls() {
        $this->content_general_controls();
        $this->content_label_controls();
        $this->content_actions_controls();

        $this->style_general_controls();
        $this->style_content_controls();
        $this->style_separator_controls();
        $this->style_message_controls();
    }

    /*==========================================================================
     * CONTENT TAB — General (Type, Due Date, Evergreen)
     *========================================================================*/
    protected function content_general_controls() {
        $this->start_controls_section(
            '_ua_countdown_section_general',
            [
                'label' => esc_html__( 'General', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            '_ua_countdown_shape',
            [
                'label'   => esc_html__( 'Shape / Style', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'rounded',
                'options' => [
                    'rounded' => esc_html__( 'Rectangle Card (Rounded)', 'ultraaddons-elementor-lite' ),
                    'circle'  => esc_html__( 'Circle (1:1 Dial)', 'ultraaddons-elementor-lite' ),
                    'square'  => esc_html__( 'Square (1:1 Box)', 'ultraaddons-elementor-lite' ),
                    'flat'    => esc_html__( 'Flat (Minimal)', 'ultraaddons-elementor-lite' ),
                    'framed'  => esc_html__( 'Framed (Bordered)', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_countdown_box_size',
            [
                'label'      => esc_html__( 'Box Size (Width / Height)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 50, 'max' => 250, 'step' => 1 ],
                ],
                'default'    => [ 'size' => 90, 'unit' => 'px' ],
                'condition'  => [
                    '_ua_countdown_shape' => [ 'circle', 'square' ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-shape-circle .ua-countdown-item, {{WRAPPER}} .ua-shape-square .ua-countdown-item' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important; min-width: {{SIZE}}{{UNIT}} !important; min-height: {{SIZE}}{{UNIT}} !important; max-width: {{SIZE}}{{UNIT}} !important; max-height: {{SIZE}}{{UNIT}} !important; flex: 0 0 {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_control(
            '_ua_countdown_type',
            [
                'label'     => esc_html__( 'Countdown Type', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'due-date',
                'options'   => [
                    'due-date'  => esc_html__( 'Due Date (Fixed Date)', 'ultraaddons-elementor-lite' ),
                    'evergreen' => esc_html__( 'Evergreen Timer', 'ultraaddons-elementor-lite' ),
                ],
                'separator' => 'before',
            ]
        );

        $due_date_default = date(
            'Y-m-d H:i',
            strtotime( '+1 month' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS )
        );

        $this->add_control(
            '_ua_countdown_due_date',
            [
                'label'       => esc_html__( 'Due Date & Time', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::DATE_TIME,
                'default'     => $due_date_default,
                'description' => sprintf(
                    /* translators: %s: Timezone string */
                    esc_html__( 'Date set according to your website timezone: %s.', 'ultraaddons-elementor-lite' ),
                    Utils::get_timezone_string()
                ),
                'dynamic'     => [ 'active' => true ],
                'condition'   => [
                    '_ua_countdown_type' => 'due-date',
                ],
            ]
        );

        // Evergreen Hours
        $this->add_control(
            '_ua_countdown_evergreen_hours',
            [
                'label'     => esc_html__( 'Hours', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 0,
                'min'       => 0,
                'max'       => 72,
                'step'      => 1,
                'condition' => [
                    '_ua_countdown_type' => 'evergreen',
                ],
            ]
        );

        // Evergreen Minutes
        $this->add_control(
            '_ua_countdown_evergreen_minutes',
            [
                'label'     => esc_html__( 'Minutes', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 30,
                'min'       => 0,
                'max'       => 59,
                'step'      => 1,
                'condition' => [
                    '_ua_countdown_type' => 'evergreen',
                ],
            ]
        );

        // Evergreen Show Again Delay (Hours)
        $this->add_control(
            '_ua_countdown_evergreen_show_again',
            [
                'label'       => esc_html__( 'Restart Timer After (Hours)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 0,
                'min'         => 0,
                'description' => esc_html__( 'Set hours after expiry to restart the timer for the same user. 0 means it will not restart.', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    '_ua_countdown_type' => 'evergreen',
                ],
            ]
        );

        // Stop Showing after Hard Date Switcher
        $this->add_control(
            '_ua_countdown_evergreen_stop_date_switch',
            [
                'label'        => esc_html__( 'Stop Showing after Hard Date', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'return_value' => 'yes',
                'separator'    => 'before',
                'condition'    => [
                    '_ua_countdown_type' => 'evergreen',
                ],
            ]
        );

        $this->add_control(
            '_ua_countdown_evergreen_stop_date',
            [
                'label'     => esc_html__( 'Hard Stop Date', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::DATE_TIME,
                'dynamic'   => [ 'active' => true ],
                'condition' => [
                    '_ua_countdown_type'                     => 'evergreen',
                    '_ua_countdown_evergreen_stop_date_switch' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * CONTENT TAB — Content & Labels
     *========================================================================*/
    protected function content_label_controls() {
        $this->start_controls_section(
            '_ua_countdown_section_content',
            [
                'label' => esc_html__( 'Content & Labels', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            '_ua_countdown_show_days',
            [
                'label'        => esc_html__( 'Show Days', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            '_ua_countdown_show_hours',
            [
                'label'        => esc_html__( 'Show Hours', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            '_ua_countdown_show_minutes',
            [
                'label'        => esc_html__( 'Show Minutes', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            '_ua_countdown_show_seconds',
            [
                'label'        => esc_html__( 'Show Seconds', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            '_ua_countdown_show_labels',
            [
                'label'        => esc_html__( 'Show Labels', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            '_ua_countdown_labels_position',
            [
                'label'     => esc_html__( 'Labels Layout', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'block',
                'options'   => [
                    'block'  => esc_html__( 'Block (Stacked)', 'ultraaddons-elementor-lite' ),
                    'inline' => esc_html__( 'Inline', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    '_ua_countdown_show_labels' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_countdown_days_singular',
            [
                'label'     => esc_html__( 'Day (Singular)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Day', 'ultraaddons-elementor-lite' ),
                'dynamic'   => [ 'active' => true ],
                'condition' => [
                    '_ua_countdown_show_labels' => 'yes',
                    '_ua_countdown_show_days'   => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_countdown_days_plural',
            [
                'label'     => esc_html__( 'Days (Plural)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Days', 'ultraaddons-elementor-lite' ),
                'dynamic'   => [ 'active' => true ],
                'condition' => [
                    '_ua_countdown_show_labels' => 'yes',
                    '_ua_countdown_show_days'   => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_countdown_hours_singular',
            [
                'label'     => esc_html__( 'Hour (Singular)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Hour', 'ultraaddons-elementor-lite' ),
                'dynamic'   => [ 'active' => true ],
                'condition' => [
                    '_ua_countdown_show_labels' => 'yes',
                    '_ua_countdown_show_hours'  => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_countdown_hours_plural',
            [
                'label'     => esc_html__( 'Hours (Plural)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Hours', 'ultraaddons-elementor-lite' ),
                'dynamic'   => [ 'active' => true ],
                'condition' => [
                    '_ua_countdown_show_labels' => 'yes',
                    '_ua_countdown_show_hours'  => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_countdown_minutes_singular',
            [
                'label'     => esc_html__( 'Minute (Singular)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Minute', 'ultraaddons-elementor-lite' ),
                'dynamic'   => [ 'active' => true ],
                'condition' => [
                    '_ua_countdown_show_labels'  => 'yes',
                    '_ua_countdown_show_minutes' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_countdown_minutes_plural',
            [
                'label'     => esc_html__( 'Minutes (Plural)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Minutes', 'ultraaddons-elementor-lite' ),
                'dynamic'   => [ 'active' => true ],
                'condition' => [
                    '_ua_countdown_show_labels'  => 'yes',
                    '_ua_countdown_show_minutes' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_countdown_seconds_plural',
            [
                'label'     => esc_html__( 'Seconds Label', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Seconds', 'ultraaddons-elementor-lite' ),
                'dynamic'   => [ 'active' => true ],
                'condition' => [
                    '_ua_countdown_show_labels'  => 'yes',
                    '_ua_countdown_show_seconds' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_countdown_show_separator',
            [
                'label'        => esc_html__( 'Show Separators (Dots)', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
                'separator'    => 'before',
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * CONTENT TAB — Expire Actions
     *========================================================================*/
    protected function content_actions_controls() {
        $this->start_controls_section(
            '_ua_countdown_section_actions',
            [
                'label' => esc_html__( 'Expire Actions', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            '_ua_countdown_expire_actions',
            [
                'label'       => esc_html__( 'Actions After Timer Expires', 'ultraaddons-elementor-lite' ),
                'label_block' => true,
                'type'        => Controls_Manager::SELECT2,
                'multiple'    => true,
                'options'     => [
                    'hide-timer'    => esc_html__( 'Hide Timer', 'ultraaddons-elementor-lite' ),
                    'hide-element'  => esc_html__( 'Hide Target Element', 'ultraaddons-elementor-lite' ),
                    'message'       => esc_html__( 'Display Custom Message', 'ultraaddons-elementor-lite' ),
                    'redirect'      => esc_html__( 'Redirect to URL', 'ultraaddons-elementor-lite' ),
                    'load-template' => esc_html__( 'Load Saved Template', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            '_ua_countdown_hide_element_selector',
            [
                'label'       => esc_html__( 'CSS Selector to Hide', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => '.offer-box, #sale-section',
                'dynamic'     => [ 'active' => true ],
                'condition'   => [
                    '_ua_countdown_expire_actions' => 'hide-element',
                ],
            ]
        );

        $this->add_control(
            '_ua_countdown_expire_message',
            [
                'label'     => esc_html__( 'Expiration Message', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::WYSIWYG,
                'default'   => esc_html__( 'This offer has expired!', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    '_ua_countdown_expire_actions' => 'message',
                ],
            ]
        );

        $this->add_control(
            '_ua_countdown_redirect_url',
            [
                'label'       => esc_html__( 'Redirect URL', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => 'https://yourdomain.com/new-offer',
                'dynamic'     => [ 'active' => true ],
                'condition'   => [
                    '_ua_countdown_expire_actions' => 'redirect',
                ],
            ]
        );

        $this->add_control(
            '_ua_countdown_load_template',
            [
                'label'       => esc_html__( 'Select Template', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'label_block' => true,
                'options'     => $this->get_elementor_templates(),
                'condition'   => [
                    '_ua_countdown_expire_actions' => 'load-template',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — General Box (Item / Card)
     *========================================================================*/
    protected function style_general_controls() {
        $this->start_controls_section(
            '_ua_countdown_style_general',
            [
                'label' => esc_html__( 'General Box', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => '_ua_countdown_item_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-countdown-item',
            ]
        );

        $this->add_responsive_control(
            '_ua_countdown_wrap_max_width',
            [
                'label'      => esc_html__( 'Max Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px' ],
                'range'      => [
                    '%'  => [ 'min' => 10, 'max' => 100 ],
                    'px' => [ 'min' => 200, 'max' => 1200 ],
                ],
                'default'    => [ 'size' => 100, 'unit' => '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-countdown-wrap' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
                'separator'  => 'before',
            ]
        );

        $this->add_responsive_control(
            '_ua_countdown_item_gutter',
            [
                'label'      => esc_html__( 'Gutter (Spacing)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'default'    => [ 'size' => 12, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-countdown-item + .ua-countdown-separator + .ua-countdown-item' => 'margin-left: calc({{SIZE}}px / 2);',
                    '{{WRAPPER}} .ua-countdown-item' => 'margin-right: calc({{SIZE}}px / 2); margin-left: calc({{SIZE}}px / 2);',
                    '{{WRAPPER}} .ua-countdown-item:first-child' => 'margin-left: 0 !important;',
                    '{{WRAPPER}} .ua-countdown-item:last-of-type' => 'margin-right: 0 !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_countdown_item_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-countdown-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => '_ua_countdown_item_border',
                'selector' => '{{WRAPPER}} .ua-countdown-item',
            ]
        );

        $this->add_responsive_control(
            '_ua_countdown_item_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-countdown-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => '_ua_countdown_item_shadow',
                'selector' => '{{WRAPPER}} .ua-countdown-item',
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Content (Numbers & Labels)
     *========================================================================*/
    protected function style_content_controls() {
        $this->start_controls_section(
            '_ua_countdown_style_content',
            [
                'label' => esc_html__( 'Content & Typography', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            '_ua_countdown_content_align',
            [
                'label'     => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => 'center',
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
                'prefix_class' => 'ua-countdown-align-',
                'selectors'    => [
                    '{{WRAPPER}} .ua-countdown-item' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        // Numbers Heading
        $this->add_control(
            '_ua_countdown_heading_numbers',
            [
                'label'     => esc_html__( 'Digits / Numbers', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            '_ua_countdown_number_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-countdown-number' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            '_ua_countdown_number_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-countdown-number' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => '_ua_countdown_number_typography',
                'selector' => '{{WRAPPER}} .ua-countdown-number',
            ]
        );

        $this->add_responsive_control(
            '_ua_countdown_number_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-countdown-number' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Labels Heading
        $this->add_control(
            '_ua_countdown_heading_labels',
            [
                'label'     => esc_html__( 'Labels', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            '_ua_countdown_label_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-countdown-label' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            '_ua_countdown_label_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-countdown-label' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => '_ua_countdown_label_typography',
                'selector' => '{{WRAPPER}} .ua-countdown-label',
            ]
        );

        $this->add_responsive_control(
            '_ua_countdown_label_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-countdown-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Separators (Dots)
     *========================================================================*/
    protected function style_separator_controls() {
        $this->start_controls_section(
            '_ua_countdown_style_separator',
            [
                'label'     => esc_html__( 'Separators (Dots)', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    '_ua_countdown_show_separator' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_countdown_separator_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#13c392',
                'selectors' => [
                    '{{WRAPPER}} .ua-countdown-separator span' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_countdown_separator_size',
            [
                'label'      => esc_html__( 'Dot Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 2, 'max' => 30 ] ],
                'default'    => [ 'size' => 6, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-countdown-separator span' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_countdown_separator_dots_gap',
            [
                'label'      => esc_html__( 'Dots Vertical Gap', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 2, 'max' => 40 ] ],
                'default'    => [ 'size' => 10, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-countdown-separator span:first-child' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_countdown_separator_radius',
            [
                'label'        => esc_html__( 'Circle Shape', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
                'selectors'    => [
                    '{{WRAPPER}} .ua-countdown-separator span' => 'border-radius: 50%;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Expired Message
     *========================================================================*/
    protected function style_message_controls() {
        $this->start_controls_section(
            '_ua_countdown_style_message',
            [
                'label'     => esc_html__( 'Expired Message', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    '_ua_countdown_expire_actions' => 'message',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_countdown_message_align',
            [
                'label'     => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => 'center',
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
                'selectors' => [
                    '{{WRAPPER}} .ua-countdown-message' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_countdown_message_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1e293b',
                'selectors' => [
                    '{{WRAPPER}} .ua-countdown-message' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_countdown_message_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-countdown-message' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => '_ua_countdown_message_typography',
                'selector' => '{{WRAPPER}} .ua-countdown-message',
            ]
        );

        $this->add_responsive_control(
            '_ua_countdown_message_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-countdown-message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_countdown_message_top_distance',
            [
                'label'      => esc_html__( 'Top Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
                'default'    => [ 'size' => 20, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-countdown-message' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Format Due Date to Unix epoch timestamp adjusted for WP timezone
     */
    protected function get_due_date_timestamp( $date_string ) {
        if ( empty( $date_string ) ) {
            return time() + ( 86400 * 30 );
        }

        $desired_format = 'Y-m-d H:i';
        $d = \DateTime::createFromFormat( $desired_format, $date_string );
        if ( ! $d ) {
            $d = \DateTime::createFromFormat( 'd/m/Y h:i a', $date_string );
        }
        if ( ! $d ) {
            $d = \DateTime::createFromFormat( 'Y-m-d', $date_string );
        }

        if ( $d ) {
            $time = $d->getTimestamp();
        } else {
            $time = strtotime( $date_string );
        }

        if ( false === $time || -1 === $time ) {
            return time() + ( 86400 * 30 );
        }

        $gmt_offset = get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
        return $time - $gmt_offset;
    }

    /**
     * Build Expired Actions JSON string
     */
    protected function get_expired_actions_data( $settings ) {
        $actions = [];

        if ( ! empty( $settings['_ua_countdown_expire_actions'] ) && is_array( $settings['_ua_countdown_expire_actions'] ) ) {
            foreach ( $settings['_ua_countdown_expire_actions'] as $action ) {
                switch ( $action ) {
                    case 'hide-timer':
                        $actions['hide-timer'] = true;
                        break;
                    case 'hide-element':
                        if ( ! empty( $settings['_ua_countdown_hide_element_selector'] ) ) {
                            $actions['hide-element'] = sanitize_text_field( $settings['_ua_countdown_hide_element_selector'] );
                        }
                        break;
                    case 'message':
                        if ( ! empty( $settings['_ua_countdown_expire_message'] ) ) {
                            $actions['message'] = wp_kses_post( $settings['_ua_countdown_expire_message'] );
                        }
                        break;
                    case 'redirect':
                        if ( ! empty( $settings['_ua_countdown_redirect_url']['url'] ) ) {
                            $actions['redirect'] = esc_url( $settings['_ua_countdown_redirect_url']['url'] );
                        }
                        break;
                    case 'load-template':
                        if ( ! empty( $settings['_ua_countdown_load_template'] ) ) {
                            $actions['load-template'] = true;
                        }
                        break;
                }
            }
        }

        return $actions;
    }

    /**
     * Render Single Countdown Item (Box + Separator)
     */
    protected function render_single_item( $settings, $item_key ) {
        $show_labels = ( ! empty( $settings['_ua_countdown_show_labels'] ) && $settings['_ua_countdown_show_labels'] === 'yes' );

        $singular = ! empty( $settings[ '_ua_countdown_' . $item_key . '_singular' ] ) ? $settings[ '_ua_countdown_' . $item_key . '_singular' ] : ucfirst( $item_key );
        $plural   = ! empty( $settings[ '_ua_countdown_' . $item_key . '_plural' ] ) ? $settings[ '_ua_countdown_' . $item_key . '_plural' ] : ucfirst( $item_key );

        $labels_json = wp_json_encode([
            'singular' => $singular,
            'plural'   => $plural,
        ]);
        ?>
        <div class="ua-countdown-item">
            <span class="ua-countdown-number ua-countdown-<?php echo esc_attr( $item_key ); ?>" data-item="<?php echo esc_attr( $item_key ); ?>">00</span>
            <?php if ( $show_labels ) : ?>
                <span class="ua-countdown-label" data-labels="<?php echo esc_attr( $labels_json ); ?>">
                    <?php echo esc_html( $plural ); ?>
                </span>
            <?php endif; ?>
        </div>
        <?php if ( ! empty( $settings['_ua_countdown_show_separator'] ) && $settings['_ua_countdown_show_separator'] === 'yes' ) : ?>
            <span class="ua-countdown-separator">
                <span></span>
                <span></span>
            </span>
        <?php endif; ?>
        <?php
    }

    /**
     * Render Widget Output
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $type = ! empty( $settings['_ua_countdown_type'] ) ? $settings['_ua_countdown_type'] : 'due-date';

        // Check Hard Cutoff Date for Evergreen
        if ( $type === 'evergreen' && ! empty( $settings['_ua_countdown_evergreen_stop_date_switch'] ) && $settings['_ua_countdown_evergreen_stop_date_switch'] === 'yes' ) {
            if ( ! empty( $settings['_ua_countdown_evergreen_stop_date'] ) ) {
                $cutoff = strtotime( $settings['_ua_countdown_evergreen_stop_date'] );
                if ( time() > $cutoff ) {
                    return; // Past the hard cutoff date, do not render
                }
            }
        }

        // Calculate Interval
        if ( $type === 'evergreen' ) {
            $hours    = ! empty( $settings['_ua_countdown_evergreen_hours'] ) ? intval( $settings['_ua_countdown_evergreen_hours'] ) : 0;
            $minutes  = ! empty( $settings['_ua_countdown_evergreen_minutes'] ) ? intval( $settings['_ua_countdown_evergreen_minutes'] ) : 30;
            $interval = ( $hours * 3600 ) + ( $minutes * 60 );
            if ( $interval <= 0 ) {
                $interval = 1800; // default 30 mins
            }
        } else {
            $due_date = ! empty( $settings['_ua_countdown_due_date'] ) ? $settings['_ua_countdown_due_date'] : '';
            $interval = $this->get_due_date_timestamp( $due_date );
        }

        $show_again = ! empty( $settings['_ua_countdown_evergreen_show_again'] ) ? intval( $settings['_ua_countdown_evergreen_show_again'] ) : 0;
        $actions    = $this->get_expired_actions_data( $settings );

        $wrap_classes = [ 'ua-countdown-wrap' ];
        if ( ! empty( $settings['_ua_countdown_labels_position'] ) && $settings['_ua_countdown_labels_position'] === 'inline' ) {
            $wrap_classes[] = 'ua-labels-inline';
        }
        $shape = ! empty( $settings['_ua_countdown_shape'] ) ? $settings['_ua_countdown_shape'] : 'rounded';
        $wrap_classes[] = 'ua-shape-' . $shape;
        ?>
        <div class="ua-countdown-wrapper">
            <div class="<?php echo esc_attr( implode( ' ', $wrap_classes ) ); ?>"
                 data-type="<?php echo esc_attr( $type ); ?>"
                 data-interval="<?php echo esc_attr( $interval ); ?>"
                 data-show-again="<?php echo esc_attr( $show_again ); ?>"
                 data-actions="<?php echo esc_attr( wp_json_encode( $actions ) ); ?>">

                <?php
                // Days
                if ( ! empty( $settings['_ua_countdown_show_days'] ) && $settings['_ua_countdown_show_days'] === 'yes' ) {
                    $this->render_single_item( $settings, 'days' );
                }

                // Hours
                if ( ! empty( $settings['_ua_countdown_show_hours'] ) && $settings['_ua_countdown_show_hours'] === 'yes' ) {
                    $this->render_single_item( $settings, 'hours' );
                }

                // Minutes
                if ( ! empty( $settings['_ua_countdown_show_minutes'] ) && $settings['_ua_countdown_show_minutes'] === 'yes' ) {
                    $this->render_single_item( $settings, 'minutes' );
                }

                // Seconds
                if ( ! empty( $settings['_ua_countdown_show_seconds'] ) && $settings['_ua_countdown_show_seconds'] === 'yes' ) {
                    $this->render_single_item( $settings, 'seconds' );
                }
                ?>
            </div>

            <?php if ( ! empty( $actions['load-template'] ) && ! empty( $settings['_ua_countdown_load_template'] ) ) : ?>
                <div class="ua-countdown-template-wrap">
                    <?php echo Plugin::instance()->frontend->get_builder_content_for_display( $settings['_ua_countdown_load_template'] ); ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
