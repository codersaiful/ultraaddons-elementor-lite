<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Contact Form 7 Widget for UltraAddons Elementor Lite.
 *
 * Modern, high-performance, and interactive styler for Contact Form 7.
 * Includes 5 interactive design presets, focus glow rings, custom checkboxes/radios,
 * button micro-interactions, and comprehensive typography/color controls.
 *
 * @since 1.0.0.1
 * @updated 2.0.3.4
 * @package UltraAddons
 */
class Contact_Form7 extends Base {

    /**
     * Constructor — registers and localizes styles & scripts.
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        wp_register_style(
            'ultraaddons-contact-form7',
            ULTRA_ADDONS_ASSETS . 'css/widgets/contact-form7.css',
            [],
            defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( ULTRA_ADDONS_DIR . 'assets/css/widgets/contact-form7.css' ) : ULTRA_ADDONS_VERSION
        );

        wp_register_script(
            'ultraaddons-contact-form7',
            ULTRA_ADDONS_ASSETS . 'js/frontend-contact-form7.js',
            [ 'jquery' ],
            defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( ULTRA_ADDONS_DIR . 'assets/js/frontend-contact-form7.js' ) : ULTRA_ADDONS_VERSION,
            true
        );
    }

    /**
     * Widget Style Dependencies.
     */
    public function get_style_depends() {
        return [ 'ultraaddons-contact-form7' ];
    }

    /**
     * Widget Script Dependencies.
     */
    public function get_script_depends() {
        return [ 'jquery', 'ultraaddons-contact-form7' ];
    }

    /**
     * Get Widget Name.
     */
    public function get_name() {
        return 'ultraaddons-contact-form7';
    }

    /**
     * Get Widget Title.
     */
    public function get_title() {
        return esc_html__( 'Contact Form 7', 'ultraaddons-elementor-lite' );
    }

    /**
     * Get Widget Icon.
     */
    public function get_icon() {
        return 'ultraaddons eicon-form-horizontal';
    }

    /**
     * Get Widget Keywords.
     */
    public function get_keywords() {
        return [ 'ultraaddons', 'contact', 'form', 'contact form 7', 'cf7', 'styler', 'feedback', 'appointment', 'inquiry' ];
    }

    /**
     * Register Widget Controls.
     */
    protected function register_controls() {
        $this->register_content_controls();
        $this->register_header_controls();
        $this->register_options_controls();

        // Style Tab Controls
        $this->register_style_container_controls();
        $this->register_style_header_controls();
        $this->register_style_labels_controls();
        $this->register_style_inputs_controls();
        $this->register_style_radio_checkbox_controls();
        $this->register_style_submit_controls();
        $this->register_style_messages_controls();
    }

    /* =========================================================================
       CONTENT TAB: Section 1 - Form Configuration & Presets
       ========================================================================= */
    protected function register_content_controls() {
        $this->start_controls_section(
            'section_cf7_config',
            [
                'label' => esc_html__( 'Form Setup & Presets', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Interactive Design Presets
        $this->add_control(
            'preset_style',
            [
                'label'       => esc_html__( 'Design Preset', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'clean-classic',
                'options'     => [
                    'clean-classic'      => esc_html__( 'Clean Classic (Crisp Border)', 'ultraaddons-elementor-lite' ),
                    'modern-boxed'       => esc_html__( 'Modern Boxed (Soft Background)', 'ultraaddons-elementor-lite' ),
                    'minimal-underline'  => esc_html__( 'Minimal Underline (Bottom Line)', 'ultraaddons-elementor-lite' ),
                    'soft-shadow'        => esc_html__( 'Soft Shadow (Floating Elevation)', 'ultraaddons-elementor-lite' ),
                    'rounded-pill'       => esc_html__( 'Rounded Pill (Sleek Capsule)', 'ultraaddons-elementor-lite' ),
                ],
                'description' => esc_html__( 'Choose an interactive 1-click design preset. You can still customize colors and typography below.', 'ultraaddons-elementor-lite' ),
            ]
        );

        if ( ! $this->is_cf7_active() ) {
            $this->add_control(
                'cf7_missing_notice',
                [
                    'type'            => Controls_Manager::RAW_HTML,
                    'raw'             => sprintf(
                        '<div class="ua-cf7-missing-box">
                            <div class="ua-cf7-missing-icon">⚠️</div>
                            <div class="ua-cf7-missing-title">%1$s</div>
                            <div class="ua-cf7-missing-desc">%2$s</div>
                            <a href="%3$s" class="ua-cf7-missing-btn" target="_blank">%4$s</a>
                        </div>',
                        esc_html__( 'Contact Form 7 Missing', 'ultraaddons-elementor-lite' ),
                        esc_html__( 'Contact Form 7 plugin is required for this widget to function. Please install and activate Contact Form 7.', 'ultraaddons-elementor-lite' ),
                        esc_url( admin_url( 'plugin-install.php?s=Contact+Form+7&tab=search&type=term' ) ),
                        esc_html__( 'Install Contact Form 7 →', 'ultraaddons-elementor-lite' )
                    ),
                    'content_classes' => 'elementor-descriptor',
                ]
            );

            $this->end_controls_section();
            return;
        }

        $forms = $this->get_cf7_forms_list();

        if ( empty( $forms ) || count( $forms ) <= 1 ) {
            $this->add_control(
                'no_forms_notice',
                [
                    'type'            => Controls_Manager::RAW_HTML,
                    'raw'             => sprintf(
                        '<div style="padding:14px;background:#fff8e6;border:1px solid #ffe08a;border-radius:8px;color:#946c00;font-size:13px;line-height:1.5;">
                            <strong>%1$s</strong><br>%2$s <a href="%3$s" target="_blank" style="color:#0f172a;font-weight:600;text-decoration:underline;">%4$s</a>
                        </div>',
                        esc_html__( 'No Contact Forms Found!', 'ultraaddons-elementor-lite' ),
                        esc_html__( 'You have not created any contact forms in Contact Form 7 yet.', 'ultraaddons-elementor-lite' ),
                        esc_url( admin_url( 'admin.php?page=wpcf7-new' ) ),
                        esc_html__( 'Create your first form here →', 'ultraaddons-elementor-lite' )
                    ),
                    'content_classes' => 'elementor-descriptor',
                ]
            );
        }

        $this->add_control(
            'form_id',
            [
                'label'       => esc_html__( 'Select Form', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => '0',
                'options'     => $forms,
                'description' => esc_html__( 'Select the Contact Form 7 form you want to display.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       CONTENT TAB: Section 2 - Form Header (Title & Subtitle)
       ========================================================================= */
    protected function register_header_controls() {
        $this->start_controls_section(
            'section_cf7_header',
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
            'header_title',
            [
                'label'       => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Get in Touch', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'e.g. Contact Us', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'show_header' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'header_title_tag',
            [
                'label'     => esc_html__( 'Title HTML Tag', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'h3',
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
                    'show_header' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'header_desc',
            [
                'label'       => esc_html__( 'Description', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXTAREA,
                'rows'        => 3,
                'default'     => esc_html__( 'Have any questions or need a quotation? Feel free to drop us a message below.', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'Short description under title...', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'show_header' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       CONTENT TAB: Section 3 - Additional Options
       ========================================================================= */
    protected function register_options_controls() {
        $this->start_controls_section(
            'section_cf7_options',
            [
                'label' => esc_html__( 'Additional Options', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'form_layout',
            [
                'label'       => esc_html__( 'Grid Layout', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'single-col',
                'options'     => [
                    'single-col'   => esc_html__( '1 Column (Full Width)', 'ultraaddons-elementor-lite' ),
                    'two-cols-top' => esc_html__( '2 Columns (Name & Email in 1 Row)', 'ultraaddons-elementor-lite' ),
                    'two-cols-all' => esc_html__( '2 Columns (All Inputs 2 Columns)', 'ultraaddons-elementor-lite' ),
                ],
                'description' => esc_html__( 'Place Name and Email side-by-side in one row automatically.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'auto_placeholders',
            [
                'label'        => esc_html__( 'Auto Placeholders from Labels', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'description'  => esc_html__( 'Automatically generates placeholder text inside inputs using their label text. Great when labels are hidden or forms lack placeholders.', 'ultraaddons-elementor-lite' ),
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
                'default'      => '',
                'description'  => esc_html__( 'Hide field label text to create a compact, placeholder-only form.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE TAB: Section 1 - Form Container
       ========================================================================= */
    protected function register_style_container_controls() {
        $this->start_controls_section(
            'section_style_container',
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
                    'px' => [ 'min' => 250, 'max' => 1200 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-cf7-wrapper' => 'max-width: {{SIZE}}{{UNIT}}; margin-left: auto; margin-right: auto;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'container_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-cf7-wrapper',
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-cf7-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'container_border',
                'selector' => '{{WRAPPER}} .ua-cf7-wrapper',
            ]
        );

        $this->add_responsive_control(
            'container_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-cf7-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'container_shadow',
                'selector' => '{{WRAPPER}} .ua-cf7-wrapper',
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE TAB: Section 2 - Form Header Style
       ========================================================================= */
    protected function register_style_header_controls() {
        $this->start_controls_section(
            'section_style_header',
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
                'label'     => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [ 'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-center' ],
                    'right'  => [ 'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default'   => 'left',
                'selectors' => [
                    '{{WRAPPER}} .ua-cf7-header' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'heading_title_style',
            [
                'label'     => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Title Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0f172a',
                'selectors' => [
                    '{{WRAPPER}} .ua-cf7-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .ua-cf7-title',
            ]
        );

        $this->add_control(
            'heading_desc_style',
            [
                'label'     => esc_html__( 'Description', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label'     => esc_html__( 'Description Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#64748b',
                'selectors' => [
                    '{{WRAPPER}} .ua-cf7-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'desc_typography',
                'selector' => '{{WRAPPER}} .ua-cf7-description',
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE TAB: Section 3 - Field Labels
       ========================================================================= */
    protected function register_style_labels_controls() {
        $this->start_controls_section(
            'section_style_labels',
            [
                'label'     => esc_html__( 'Labels', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'hide_labels!' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label'     => esc_html__( 'Label Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#334155',
                'selectors' => [
                    '{{WRAPPER}} .wpcf7 label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'label_typography',
                'selector' => '{{WRAPPER}} .wpcf7 label',
            ]
        );

        $this->add_responsive_control(
            'label_bottom_spacing',
            [
                'label'      => esc_html__( 'Bottom Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 8 ],
                'selectors'  => [
                    '{{WRAPPER}} .wpcf7 label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'required_asterisk_color',
            [
                'label'     => esc_html__( 'Required Asterisk (*) Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ef4444',
                'selectors' => [
                    '{{WRAPPER}} .wpcf7 label .wpcf7-required' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE TAB: Section 4 - Inputs & Textarea
       ========================================================================= */
    protected function register_style_inputs_controls() {
        $this->start_controls_section(
            'section_style_inputs',
            [
                'label' => esc_html__( 'Inputs & Textarea', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'field_gap',
            [
                'label'      => esc_html__( 'Fields Spacing (Row Gap)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 6, 'max' => 50 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 18 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-cf7-wrapper' => '--ua-cf7-row-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'column_gap',
            [
                'label'      => esc_html__( 'Column Gap (Side-by-side)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 6, 'max' => 50 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 20 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-cf7-wrapper' => '--ua-cf7-col-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'input_height',
            [
                'label'      => esc_html__( 'Input Height', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 36, 'max' => 70 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 48 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-cf7-wrapper' => '--ua-cf7-input-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'textarea_height',
            [
                'label'      => esc_html__( 'Textarea Height', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 80, 'max' => 300 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 130 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-cf7-wrapper' => '--ua-cf7-textarea-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'input_typography',
                'selector' => '{{WRAPPER}} .wpcf7 input:not([type="submit"]):not([type="checkbox"]):not([type="radio"]), {{WRAPPER}} .wpcf7 select, {{WRAPPER}} .wpcf7 textarea',
            ]
        );

        // State Tabs: Normal vs Focus
        $this->start_controls_tabs( 'tabs_input_states' );

        // --- Tab: Normal ---
        $this->start_controls_tab(
            'tab_input_normal',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'input_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7 input:not([type="submit"]):not([type="checkbox"]):not([type="radio"]), {{WRAPPER}} .wpcf7 select, {{WRAPPER}} .wpcf7 textarea' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_placeholder_color',
            [
                'label'     => esc_html__( 'Placeholder Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7 input::placeholder, {{WRAPPER}} .wpcf7 textarea::placeholder' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7 input:not([type="submit"]):not([type="checkbox"]):not([type="radio"]), {{WRAPPER}} .wpcf7 select, {{WRAPPER}} .wpcf7 textarea' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'input_border',
                'selector' => '{{WRAPPER}} .wpcf7 input:not([type="submit"]):not([type="checkbox"]):not([type="radio"]), {{WRAPPER}} .wpcf7 select, {{WRAPPER}} .wpcf7 textarea',
            ]
        );

        $this->add_responsive_control(
            'input_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .wpcf7 input:not([type="submit"]):not([type="checkbox"]):not([type="radio"]), {{WRAPPER}} .wpcf7 select, {{WRAPPER}} .wpcf7 textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // --- Tab: Focus ---
        $this->start_controls_tab(
            'tab_input_focus',
            [
                'label' => esc_html__( 'Focus', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'input_focus_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7 input:not([type="submit"]):not([type="checkbox"]):not([type="radio"]):focus, {{WRAPPER}} .wpcf7 select:focus, {{WRAPPER}} .wpcf7 textarea:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_focus_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7 input:not([type="submit"]):not([type="checkbox"]):not([type="radio"]):focus, {{WRAPPER}} .wpcf7 select:focus, {{WRAPPER}} .wpcf7 textarea:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_focus_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7 input:not([type="submit"]):not([type="checkbox"]):not([type="radio"]):focus, {{WRAPPER}} .wpcf7 select:focus, {{WRAPPER}} .wpcf7 textarea:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'input_focus_shadow',
                'selector' => '{{WRAPPER}} .wpcf7 input:not([type="submit"]):not([type="checkbox"]):not([type="radio"]):focus, {{WRAPPER}} .wpcf7 select:focus, {{WRAPPER}} .wpcf7 textarea:focus',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE TAB: Section 5 - Checkboxes & Radio Buttons
       ========================================================================= */
    protected function register_style_radio_checkbox_controls() {
        $this->start_controls_section(
            'section_style_radio_checkbox',
            [
                'label' => esc_html__( 'Radio & Checkbox', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'check_size',
            [
                'label'      => esc_html__( 'Size (px)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [ 'px' => [ 'min' => 14, 'max' => 32 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 20 ],
                'selectors'  => [
                    '{{WRAPPER}} .wpcf7-checkbox input[type="checkbox"], {{WRAPPER}} .wpcf7-radio input[type="radio"]' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'check_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#cbd5e1',
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-checkbox input[type="checkbox"], {{WRAPPER}} .wpcf7-radio input[type="radio"]' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'check_active_color',
            [
                'label'     => esc_html__( 'Checked / Active Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#3b82f6',
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-checkbox input[type="checkbox"]:checked' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
                    '{{WRAPPER}} .wpcf7-radio input[type="radio"]:checked' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .wpcf7-radio input[type="radio"]:checked::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'check_label_color',
            [
                'label'     => esc_html__( 'Label Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#475569',
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-list-item label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE TAB: Section 6 - Submit Button
       ========================================================================= */
    protected function register_style_submit_controls() {
        $this->start_controls_section(
            'section_style_submit',
            [
                'label' => esc_html__( 'Submit Button', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'button_align',
            [
                'label'     => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'    => [ 'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-left' ],
                    'center'  => [ 'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-center' ],
                    'right'   => [ 'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-right' ],
                    'justify' => [ 'title' => esc_html__( 'Full Width', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-justify' ],
                ],
                'default'   => 'left',
                'prefix_class' => 'ua-cf7-btn-align-',
            ]
        );

        $this->add_responsive_control(
            'button_height',
            [
                'label'      => esc_html__( 'Button Height', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 38, 'max' => 70 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 50 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-cf7-wrapper' => '--ua-cf7-btn-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .wpcf7 input[type="submit"], {{WRAPPER}} .wpcf7 button[type="submit"]',
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .wpcf7 input[type="submit"], {{WRAPPER}} .wpcf7 button[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // State Tabs: Normal vs Hover
        $this->start_controls_tabs( 'tabs_button_states' );

        // --- Normal Tab ---
        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .wpcf7 input[type="submit"], {{WRAPPER}} .wpcf7 button[type="submit"]' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'button_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .wpcf7 input[type="submit"], {{WRAPPER}} .wpcf7 button[type="submit"]',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'button_border',
                'selector' => '{{WRAPPER}} .wpcf7 input[type="submit"], {{WRAPPER}} .wpcf7 button[type="submit"]',
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .wpcf7 input[type="submit"], {{WRAPPER}} .wpcf7 button[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'button_shadow',
                'selector' => '{{WRAPPER}} .wpcf7 input[type="submit"], {{WRAPPER}} .wpcf7 button[type="submit"]',
            ]
        );

        $this->end_controls_tab();

        // --- Hover Tab ---
        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'button_hover_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .wpcf7 input[type="submit"]:hover, {{WRAPPER}} .wpcf7 button[type="submit"]:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'button_hover_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .wpcf7 input[type="submit"]:hover, {{WRAPPER}} .wpcf7 button[type="submit"]:hover',
            ]
        );

        $this->add_control(
            'button_hover_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7 input[type="submit"]:hover, {{WRAPPER}} .wpcf7 button[type="submit"]:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'button_hover_shadow',
                'selector' => '{{WRAPPER}} .wpcf7 input[type="submit"]:hover, {{WRAPPER}} .wpcf7 button[type="submit"]:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE TAB: Section 7 - Messages & Alerts
       ========================================================================= */
    protected function register_style_messages_controls() {
        $this->start_controls_section(
            'section_style_messages',
            [
                'label' => esc_html__( 'Alerts & Validation', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'heading_validation_style',
            [
                'label' => esc_html__( 'Validation Error Tip', 'ultraaddons-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'validation_error_color',
            [
                'label'     => esc_html__( 'Error Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ef4444',
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-not-valid-tip' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'validation_error_typography',
                'selector' => '{{WRAPPER}} .wpcf7-not-valid-tip',
            ]
        );

        $this->add_control(
            'heading_success_style',
            [
                'label'     => esc_html__( 'Success Notice Box', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'success_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f0fdf4',
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-response-output.wpcf7-mail-sent-ok' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'success_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#166534',
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-response-output.wpcf7-mail-sent-ok' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'success_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#86efac',
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-response-output.wpcf7-mail-sent-ok' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'heading_error_style',
            [
                'label'     => esc_html__( 'Error Notice Box', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'error_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fef2f2',
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-response-output.wpcf7-validation-errors, {{WRAPPER}} .wpcf7-response-output.wpcf7-mail-sent-ng' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'error_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#991b1b',
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-response-output.wpcf7-validation-errors, {{WRAPPER}} .wpcf7-response-output.wpcf7-mail-sent-ng' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'error_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fca5a5',
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-response-output.wpcf7-validation-errors, {{WRAPPER}} .wpcf7-response-output.wpcf7-mail-sent-ng' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       HELPER METHODS
       ========================================================================= */

    /**
     * Check if Contact Form 7 plugin is active.
     */
    protected function is_cf7_active() {
        return class_exists( 'WPCF7' );
    }

    /**
     * Retrieve all Contact Form 7 posts for the dropdown selector.
     */
    protected function get_cf7_forms_list() {
        $forms = [ '0' => esc_html__( '— Select a Contact Form —', 'ultraaddons-elementor-lite' ) ];

        if ( ! $this->is_cf7_active() ) {
            return $forms;
        }

        $posts = get_posts( [
            'post_type'      => 'wpcf7_contact_form',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'title',
            'order'          => 'ASC',
        ] );

        if ( ! empty( $posts ) && ! is_wp_error( $posts ) ) {
            foreach ( $posts as $post ) {
                $forms[ $post->ID ] = $post->post_title;
            }
        }

        return $forms;
    }

    /* =========================================================================
       MAIN RENDER METHOD
       ========================================================================= */
    protected function render() {
        if ( ! $this->is_cf7_active() ) {
            echo '<div class="ua-cf7-missing-box">';
            echo '<div class="ua-cf7-missing-icon">⚠️</div>';
            echo '<div class="ua-cf7-missing-title">' . esc_html__( 'Contact Form 7 Missing', 'ultraaddons-elementor-lite' ) . '</div>';
            echo '<div class="ua-cf7-missing-desc">' . esc_html__( 'Please install and activate the Contact Form 7 plugin to render this form.', 'ultraaddons-elementor-lite' ) . '</div>';
            echo '<a href="' . esc_url( admin_url( 'plugin-install.php?s=Contact+Form+7&tab=search&type=term' ) ) . '" class="ua-cf7-missing-btn" target="_blank">' . esc_html__( 'Install Contact Form 7 →', 'ultraaddons-elementor-lite' ) . '</a>';
            echo '</div>';
            return;
        }

        $settings = $this->get_settings_for_display();
        $form_id  = ! empty( $settings['form_id'] ) ? absint( $settings['form_id'] ) : 0;

        if ( empty( $form_id ) ) {
            echo '<div style="padding:24px;background:#f8fafc;border:1.5px dashed #cbd5e1;border-radius:10px;text-align:center;color:#64748b;font-size:14px;">';
            echo '<strong>' . esc_html__( 'Contact Form 7', 'ultraaddons-elementor-lite' ) . '</strong>: ' . esc_html__( 'Please select a form from the widget settings panel.', 'ultraaddons-elementor-lite' );
            echo '</div>';
            return;
        }

        $preset = ! empty( $settings['preset_style'] ) ? esc_attr( $settings['preset_style'] ) : 'clean-classic';
        $wrapper_classes = [ 'ua-cf7-wrapper', 'ua-cf7-preset-' . $preset ];

        if ( 'yes' === $settings['hide_labels'] ) {
            $wrapper_classes[] = 'ua-cf7-hide-labels';
        }

        if ( 'yes' === $settings['auto_placeholders'] ) {
            $wrapper_classes[] = 'ua-cf7-auto-placeholders';
        }

        $layout = ! empty( $settings['form_layout'] ) ? esc_attr( $settings['form_layout'] ) : 'single-col';
        $wrapper_classes[] = 'ua-cf7-layout-' . $layout;

        echo '<div class="' . esc_attr( implode( ' ', $wrapper_classes ) ) . '">';

        // Render Form Header if enabled
        if ( 'yes' === $settings['show_header'] ) {
            $allowed_tags = [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p' ];
            $tag = ! empty( $settings['header_title_tag'] ) && in_array( $settings['header_title_tag'], $allowed_tags, true ) ? $settings['header_title_tag'] : 'h3';
            echo '<div class="ua-cf7-header">';
            if ( ! empty( $settings['header_title'] ) ) {
                echo '<' . $tag . ' class="ua-cf7-title">' . esc_html( $settings['header_title'] ) . '</' . $tag . '>';
            }
            if ( ! empty( $settings['header_desc'] ) ) {
                echo '<p class="ua-cf7-description">' . wp_kses_post( $settings['header_desc'] ) . '</p>';
            }
            echo '</div>';
        }

        // Render CF7 Form Shortcode
        $form_post = get_post( $form_id );
        $form_title = $form_post ? $form_post->post_title : '';

        echo do_shortcode( '[contact-form-7 id="' . $form_id . '" title="' . esc_attr( $form_title ) . '"]' );

        echo '</div>'; // .ua-cf7-wrapper
    }
}
