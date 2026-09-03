<?php
namespace UltraAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Fluent Forms Widget for UltraAddons Elementor Lite
 * 
 * Featuring 5 Interactive Presets, Header Controls, Field Display Toggles, and Full Styling Suite.
 * 
 * @since 1.1.X
 * @package UltraAddons
 */
class Fluent_Forms extends Base {

    /**
     * Get widget keywords.
     */
    public function get_keywords() {
        return [ 'ultraaddons', 'fluent', 'forms', 'fluentform', 'contact', 'form', 'builder', 'wpmanage' ];
    }

    /**
     * Register widget styles.
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        $css_file = ULTRA_ADDONS_DIR . 'assets/css/widgets/fluent-forms.css';

        wp_register_style(
            'ultraaddons-fluent-forms',
            ULTRA_ADDONS_ASSETS . 'css/widgets/fluent-forms.css',
            [],
            defined( 'WP_DEBUG' ) && WP_DEBUG && file_exists( $css_file ) ? filemtime( $css_file ) : ULTRA_ADDONS_VERSION
        );
    }

    /**
     * Enqueue widget styles.
     */
    public function get_style_depends() {
        return [ 'ultraaddons-fluent-forms' ];
    }

    /**
     * Register controls.
     */
    protected function register_controls() {
        // CONTENT TAB
        $this->register_setup_controls();
        $this->register_header_controls();
        $this->register_fields_controls();
        $this->register_button_content_controls();

        // STYLE TAB
        $this->register_header_style_controls();
        $this->register_container_style_controls();
        $this->register_label_style_controls();
        $this->register_input_style_controls();
        $this->register_button_style_controls();
        $this->register_message_style_controls();
    }

    /* =========================================================================
       CONTENT TAB: Section 1 - Form Setup & Design Presets
       ========================================================================= */
    protected function register_setup_controls() {
        $this->start_controls_section(
            'section_ff_setup',
            [
                'label' => esc_html__( 'Form Setup & Presets', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        // 5 Interactive Design Presets
        $this->add_control(
            'preset_style',
            [
                'label'       => esc_html__( 'Design Preset', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'clean-classic',
                'options'     => [
                    'clean-classic'     => esc_html__( 'Clean Classic (Crisp Border)', 'ultraaddons-elementor-lite' ),
                    'modern-boxed'      => esc_html__( 'Modern Boxed (Soft Background)', 'ultraaddons-elementor-lite' ),
                    'minimal-underline' => esc_html__( 'Minimal Underline (Bottom Line)', 'ultraaddons-elementor-lite' ),
                    'soft-shadow'       => esc_html__( 'Soft Shadow (Floating Elevation)', 'ultraaddons-elementor-lite' ),
                    'rounded-pill'      => esc_html__( 'Rounded Pill (Sleek Capsule)', 'ultraaddons-elementor-lite' ),
                ],
                'description' => esc_html__( 'Choose a 1-click design preset. You can still customize colors and typography in the Style tab.', 'ultraaddons-elementor-lite' ),
            ]
        );

        // Check if Fluent Forms plugin is installed & active
        if ( ! function_exists( 'wpFluentForm' ) && ! defined( 'FLUENTFORM' ) ) {
            $this->add_control(
                'ff_missing_notice',
                [
                    'type'            => Controls_Manager::RAW_HTML,
                    'raw'             => sprintf(
                        '<div style="padding:14px;background:#fff8e6;border:1px solid #ffe08a;border-radius:8px;color:#946c00;font-size:13px;line-height:1.5;">
                            <strong>%1$s</strong><br>%2$s<br><br>
                            <a href="%3$s" class="elementor-button elementor-button-default" target="_blank" style="display:inline-block;padding:7px 14px;background:#0284c7;color:#fff;border-radius:4px;text-decoration:none;font-size:12px;font-weight:600;">%4$s</a>
                        </div>',
                        esc_html__( 'Fluent Forms Not Installed', 'ultraaddons-elementor-lite' ),
                        esc_html__( 'Fluent Forms plugin is required for this widget to function. Please install and activate Fluent Forms.', 'ultraaddons-elementor-lite' ),
                        esc_url( admin_url( 'plugin-install.php?s=Fluent+Forms&tab=search&type=term' ) ),
                        esc_html__( 'Install Fluent Forms →', 'ultraaddons-elementor-lite' )
                    ),
                    'content_classes' => 'elementor-descriptor',
                ]
            );

            $this->end_controls_section();
            return;
        }

        $forms = function_exists( 'ultraaddons_get_fluent_form_list' ) ? ultraaddons_get_fluent_form_list() : [];

        $this->add_control(
            'form_id',
            [
                'label'       => esc_html__( 'Select Form', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'options'     => $forms,
                'default'     => '0',
                'description' => esc_html__( 'Select a Fluent Form to display on your page.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       CONTENT TAB: Section 2 - Form Header (Title & Description)
       ========================================================================= */
    protected function register_header_controls() {
        $this->start_controls_section(
            'section_ff_header',
            [
                'label' => esc_html__( 'Form Header', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_header',
            [
                'label'        => esc_html__( 'Show Form Header', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $this->add_control(
            'custom_title',
            [
                'label'       => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Leave empty to use form name', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'show_header' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label'     => esc_html__( 'Title HTML Tag', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
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
                'default'   => 'h3',
                'condition' => [
                    'show_header' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'header_description',
            [
                'label'       => esc_html__( 'Description', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXTAREA,
                'rows'        => 3,
                'placeholder' => esc_html__( 'Enter form subtitle or description...', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'show_header' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       CONTENT TAB: Section 3 - Field Display Options
       ========================================================================= */
    protected function register_fields_controls() {
        $this->start_controls_section(
            'section_ff_fields',
            [
                'label' => esc_html__( 'Field Display Options', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'hide_labels',
            [
                'label'        => esc_html__( 'Hide Field Labels', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'description'  => esc_html__( 'Hide outer field labels for a modern placeholder-only layout.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'hide_placeholders',
            [
                'label'        => esc_html__( 'Hide Placeholders', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'description'  => esc_html__( 'Hide placeholder text inside form inputs.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'hide_errors',
            [
                'label'        => esc_html__( 'Hide Validation Errors', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'description'  => esc_html__( 'Hide inline validation error messages.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       CONTENT TAB: Section 4 - Submit Button Options
       ========================================================================= */
    protected function register_button_content_controls() {
        $this->start_controls_section(
            'section_ff_button_content',
            [
                'label' => esc_html__( 'Submit Button', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label'       => esc_html__( 'Button Text Override', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Leave empty for form default', 'ultraaddons-elementor-lite' ),
                'description' => esc_html__( 'Override the button text defined inside Fluent Forms.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_responsive_control(
            'button_align',
            [
                'label'        => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::CHOOSE,
                'options'      => [
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
                        'title' => esc_html__( 'Full Width', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-justify',
                    ],
                ],
                'default'      => 'left',
                'prefix_class' => 'ua-ff-btn-align-',
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE TAB: Section 1 - Form Header
       ========================================================================= */
    protected function register_header_style_controls() {
        $this->start_controls_section(
            'section_ff_style_header',
            [
                'label'     => esc_html__( 'Form Header', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_header' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'header_align',
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
                'default'      => 'left',
                'selectors'    => [
                    '{{WRAPPER}} .ua-fluent-forms-wrapper .ua-ff-title, {{WRAPPER}} .ua-fluent-forms-wrapper .ua-ff-description' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'heading_title_style',
            [
                'label' => esc_html__( 'Title Style', 'ultraaddons-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .ua-fluent-forms-wrapper .ua-ff-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-fluent-forms-wrapper .ua-ff-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label'      => esc_html__( 'Margin Bottom', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-fluent-forms-wrapper .ua-ff-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'heading_desc_style',
            [
                'label'     => esc_html__( 'Description Style', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'desc_typography',
                'selector' => '{{WRAPPER}} .ua-fluent-forms-wrapper .ua-ff-description',
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-fluent-forms-wrapper .ua-ff-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'desc_spacing',
            [
                'label'      => esc_html__( 'Margin Bottom', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-fluent-forms-wrapper .ua-ff-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE TAB: Section 2 - Form Container
       ========================================================================= */
    protected function register_container_style_controls() {
        $this->start_controls_section(
            'section_ff_style_container',
            [
                'label' => esc_html__( 'Form Container', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'container_max_width',
            [
                'label'      => esc_html__( 'Max Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [ 'min' => 200, 'max' => 1200 ],
                    '%'  => [ 'min' => 10, 'max' => 100 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-fluent-forms-wrapper' => 'max-width: {{SIZE}}{{UNIT}}; margin-left: auto; margin-right: auto;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'container_bg',
                'selector' => '{{WRAPPER}} .ua-fluent-forms-wrapper',
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'    => [
                    'top'      => '20',
                    'right'    => '20',
                    'bottom'   => '20',
                    'left'     => '20',
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-fluent-forms-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'container_border',
                'selector' => '{{WRAPPER}} .ua-fluent-forms-wrapper',
            ]
        );

        $this->add_responsive_control(
            'container_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-fluent-forms-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'container_shadow',
                'selector' => '{{WRAPPER}} .ua-fluent-forms-wrapper',
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE TAB: Section 3 - Field Labels
       ========================================================================= */
    protected function register_label_style_controls() {
        $this->start_controls_section(
            'section_ff_style_labels',
            [
                'label' => esc_html__( 'Field Labels', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'label_typography',
                'selector' => '{{WRAPPER}} .ua-fluent-forms-wrapper .ff-el-input--label label',
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-fluent-forms-wrapper .ff-el-input--label label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'label_spacing',
            [
                'label'      => esc_html__( 'Margin Bottom', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 40 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-fluent-forms-wrapper .ff-el-input--label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'asterisk_color',
            [
                'label'     => esc_html__( 'Required (*) Mark Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-fluent-forms-wrapper .fluentform .ff-el-input--label.ff-el-is-required label:after, {{WRAPPER}} .ua-fluent-forms-wrapper .fluentform .ff-el-input--label.ff-el-is-required label:before, {{WRAPPER}} .ua-fluent-forms-wrapper .ff-el-input--label.ff-el-is-required label:after, {{WRAPPER}} .ua-fluent-forms-wrapper .ff-el-input--label.ff-el-is-required label:before' => 'color: {{VALUE}} !important;',
                    '{{WRAPPER}} .ua-fluent-forms-wrapper .ff-el-asterisk' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE TAB: Section 4 - Input & Textarea
       ========================================================================= */
    protected function register_input_style_controls() {
        $this->start_controls_section(
            'section_ff_style_inputs',
            [
                'label' => esc_html__( 'Input & Textarea', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'input_typography',
                'selector' => '{{WRAPPER}} .ua-fluent-forms-wrapper .ff-el-form-control, {{WRAPPER}} .ua-fluent-forms-wrapper select',
            ]
        );

        $this->add_responsive_control(
            'input_height',
            [
                'label'      => esc_html__( 'Input Height', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 30, 'max' => 80 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-fluent-forms-wrapper input.ff-el-form-control, {{WRAPPER}} .ua-fluent-forms-wrapper select.ff-el-form-control' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'input_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-fluent-forms-wrapper .ff-el-form-control, {{WRAPPER}} .ua-fluent-forms-wrapper select.ff-el-form-control' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_ff_input_style' );

        // Normal State
        $this->start_controls_tab(
            'tab_ff_input_normal',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'input_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-fluent-forms-wrapper .ff-el-form-control' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'input_placeholder_color',
            [
                'label'     => esc_html__( 'Placeholder Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-fluent-forms-wrapper .ff-el-form-control::placeholder' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'input_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-fluent-forms-wrapper .ff-el-form-control' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'input_border',
                'selector' => '{{WRAPPER}} .ua-fluent-forms-wrapper .ff-el-form-control',
            ]
        );

        $this->add_responsive_control(
            'input_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-fluent-forms-wrapper .ff-el-form-control' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->end_controls_tab();

        // Focus State
        $this->start_controls_tab(
            'tab_ff_input_focus',
            [
                'label' => esc_html__( 'Focus', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'input_focus_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-fluent-forms-wrapper .ff-el-form-control:focus' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'input_focus_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-fluent-forms-wrapper .ff-el-form-control:focus' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'input_focus_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-fluent-forms-wrapper .ff-el-form-control:focus' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'input_focus_shadow',
                'selector' => '{{WRAPPER}} .ua-fluent-forms-wrapper .ff-el-form-control:focus',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE TAB: Section 5 - Submit Button
       ========================================================================= */
    protected function register_button_style_controls() {
        $this->start_controls_section(
            'section_ff_style_button',
            [
                'label' => esc_html__( 'Submit Button', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .ua-fluent-forms-wrapper .ff-btn-submit',
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-fluent-forms-wrapper .ff-btn-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_ff_button_style' );

        // Normal
        $this->start_controls_tab(
            'tab_ff_button_normal',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-fluent-forms-wrapper .ff-btn-submit' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-fluent-forms-wrapper .ff-btn-submit' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'button_border',
                'selector' => '{{WRAPPER}} .ua-fluent-forms-wrapper .ff-btn-submit',
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-fluent-forms-wrapper .ff-btn-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'button_shadow',
                'selector' => '{{WRAPPER}} .ua-fluent-forms-wrapper .ff-btn-submit',
            ]
        );

        $this->end_controls_tab();

        // Hover
        $this->start_controls_tab(
            'tab_ff_button_hover',
            [
                'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'button_hover_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-fluent-forms-wrapper .ff-btn-submit:hover' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'button_hover_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-fluent-forms-wrapper .ff-btn-submit:hover' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'button_hover_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-fluent-forms-wrapper .ff-btn-submit:hover' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'button_hover_shadow',
                'selector' => '{{WRAPPER}} .ua-fluent-forms-wrapper .ff-btn-submit:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE TAB: Section 6 - Feedback Messages
       ========================================================================= */
    protected function register_message_style_controls() {
        $this->start_controls_section(
            'section_ff_style_messages',
            [
                'label' => esc_html__( 'Feedback Messages', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Success Message
        $this->add_control(
            'heading_success_msg',
            [
                'label'     => esc_html__( 'Success Message', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'success_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-fluent-forms-wrapper .ff-message-success' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'success_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-fluent-forms-wrapper .ff-message-success' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'success_border',
                'selector' => '{{WRAPPER}} .ua-fluent-forms-wrapper .ff-message-success',
            ]
        );

        // Error Message
        $this->add_control(
            'heading_error_msg',
            [
                'label'     => esc_html__( 'Validation & Error Message', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'error_text_color',
            [
                'label'     => esc_html__( 'Error Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-fluent-forms-wrapper .text-danger, {{WRAPPER}} .ua-fluent-forms-wrapper .error' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'error_bg_color',
            [
                'label'     => esc_html__( 'Error Box Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-fluent-forms-wrapper .ff-errors-in-stack' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on the frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $form_id  = ! empty( $settings['form_id'] ) ? absint( $settings['form_id'] ) : 0;

        // Check if Fluent Forms is installed and active
        if ( ! function_exists( 'wpFluentForm' ) && ! defined( 'FLUENTFORM' ) ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo '<div class="ua-alert ua-alert-warning">' . esc_html__( 'Please install and activate the Fluent Forms plugin to display this form.', 'ultraaddons-elementor-lite' ) . '</div>';
            }
            return;
        }

        // Check if a form is selected
        if ( empty( $form_id ) ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo '<div class="ua-alert ua-alert-info">' . esc_html__( 'Please select a Fluent Form from the dropdown.', 'ultraaddons-elementor-lite' ) . '</div>';
            }
            return;
        }

        global $wpdb;
        $form_title = '';
        if ( 'yes' === $settings['show_header'] ) {
            $table_name = $wpdb->prefix . 'fluentform_forms';
            $form_row   = $wpdb->get_row( $wpdb->prepare( "SELECT title FROM {$table_name} WHERE id = %d", $form_id ) );
            if ( $form_row ) {
                $form_title = $form_row->title;
            }
        }

        $preset = ! empty( $settings['preset_style'] ) ? sanitize_html_class( $settings['preset_style'] ) : 'clean-classic';

        $wrapper_classes = [
            'ua-fluent-forms-wrapper',
            'ua-ff-preset-' . $preset,
        ];

        if ( 'yes' === $settings['hide_labels'] ) {
            $wrapper_classes[] = 'ua-ff-hide-labels';
        }

        if ( 'yes' === $settings['hide_placeholders'] ) {
            $wrapper_classes[] = 'ua-ff-hide-placeholders';
        }

        if ( 'yes' === $settings['hide_errors'] ) {
            $wrapper_classes[] = 'ua-ff-hide-errors';
        }

        echo '<div class="' . esc_attr( implode( ' ', $wrapper_classes ) ) . '">';

        // Form Header (Title & Description)
        if ( 'yes' === $settings['show_header'] ) {
            $title_text = ! empty( $settings['custom_title'] ) ? $settings['custom_title'] : $form_title;
            if ( ! empty( $title_text ) ) {
                $tag = ! empty( $settings['title_tag'] ) ? $settings['title_tag'] : 'h3';
                echo '<' . esc_html( $tag ) . ' class="ua-ff-title">' . esc_html( $title_text ) . '</' . esc_html( $tag ) . '>';
            }

            if ( ! empty( $settings['header_description'] ) ) {
                echo '<p class="ua-ff-description">' . wp_kses_post( $settings['header_description'] ) . '</p>';
            }
        }

        // Render Fluent Forms Shortcode
        $rendered_form = do_shortcode( sprintf( '[fluentform id="%d"]', $form_id ) );

        // Button text override if provided
        if ( ! empty( $settings['button_text'] ) ) {
            $custom_btn = esc_html( $settings['button_text'] );
            $rendered_form = preg_replace(
                '/(<button[^>]*class="[^"]*ff-btn-submit[^"]*"[^>]*>)(.*?)(<\/button>)/is',
                '$1' . $custom_btn . '$3',
                $rendered_form
            );
        }

        echo $rendered_form; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

        echo '</div>';
    }
}
