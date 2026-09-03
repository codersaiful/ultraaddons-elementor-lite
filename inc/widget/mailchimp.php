<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Mailchimp Widget for UltraAddons Elementor Lite.
 *
 * Direct Mailchimp API Integration with no 3rd-party plugin requirements.
 * Includes 5 design presets, Inline Newsletter joined layout, customizable
 * fields (First Name, Last Name, Phone, GDPR), and AJAX submission.
 *
 * @since 2.0.3.4
 * @package UltraAddons
 */
class Mailchimp extends Base {

    /**
     * Constructor — registers styles, scripts, and AJAX endpoints.
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        $css_file = ULTRA_ADDONS_DIR . 'assets/css/widgets/mailchimp.css';
        $js_file  = ULTRA_ADDONS_DIR . 'assets/js/frontend-mailchimp.js';

        wp_register_style(
            'ultraaddons-mailchimp',
            ULTRA_ADDONS_ASSETS . 'css/widgets/mailchimp.css',
            [],
            defined( 'WP_DEBUG' ) && WP_DEBUG && file_exists( $css_file ) ? filemtime( $css_file ) : ULTRA_ADDONS_VERSION
        );

        wp_register_script(
            'ultraaddons-mailchimp',
            ULTRA_ADDONS_ASSETS . 'js/frontend-mailchimp.js',
            [ 'jquery' ],
            defined( 'WP_DEBUG' ) && WP_DEBUG && file_exists( $js_file ) ? filemtime( $js_file ) : ULTRA_ADDONS_VERSION,
            true
        );

        // Register AJAX subscription actions
        add_action( 'wp_ajax_ultraaddons_mailchimp_subscribe', [ __CLASS__, 'ajax_subscribe' ] );
        add_action( 'wp_ajax_nopriv_ultraaddons_mailchimp_subscribe', [ __CLASS__, 'ajax_subscribe' ] );
    }

    /**
     * Widget Style Dependencies.
     */
    public function get_style_depends() {
        return [ 'ultraaddons-mailchimp' ];
    }

    /**
     * Widget Script Dependencies.
     */
    public function get_script_depends() {
        return [ 'jquery', 'ultraaddons-mailchimp' ];
    }

    /**
     * Get Widget Name.
     */
    public function get_name() {
        return 'ultraaddons-mailchimp';
    }

    /**
     * Get Widget Title.
     */
    public function get_title() {
        return esc_html__( 'Mailchimp', 'ultraaddons-elementor-lite' );
    }

    /**
     * Get Widget Icon.
     */
    public function get_icon() {
        return 'ultraaddons eicon-mailchimp';
    }

    /**
     * Get Widget Keywords.
     */
    public function get_keywords() {
        return [ 'ultraaddons', 'mailchimp', 'newsletter', 'subscribe', 'email', 'lead', 'optin', 'campaign' ];
    }

    /**
     * Register Widget Controls.
     */
    protected function register_controls() {
        $this->register_account_controls();
        $this->register_layout_presets_controls();
        $this->register_fields_controls();
        $this->register_button_controls();
        $this->register_header_controls();
        $this->register_messages_controls();

        // Style Tab Controls
        $this->register_style_container_controls();
        $this->register_style_header_controls();
        $this->register_style_labels_controls();
        $this->register_style_inputs_controls();
        $this->register_style_button_controls();
        $this->register_style_gdpr_controls();
        $this->register_style_messages_controls();
    }

    /* =========================================================================
       CONTENT TAB: Section 1 - Mailchimp Account & List
       ========================================================================= */
    protected function register_account_controls() {
        $this->start_controls_section(
            'section_mc_account',
            [
                'label' => esc_html__( 'Mailchimp Credentials', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'api_key',
            [
                'label'       => esc_html__( 'Mailchimp API Key', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'input_type'  => 'password',
                'description' => sprintf(
                    '%1$s <a href="%2$s" target="_blank">%3$s</a>',
                    esc_html__( 'Enter your Mailchimp API key.', 'ultraaddons-elementor-lite' ),
                    esc_url( 'https://mailchimp.com/help/about-api-keys/' ),
                    esc_html__( 'Where can I find my API key? →', 'ultraaddons-elementor-lite' )
                ),
            ]
        );

        $this->add_control(
            'list_id',
            [
                'label'       => esc_html__( 'Audience / List ID', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'e.g. 7a8b9c1d2e', 'ultraaddons-elementor-lite' ),
                'description' => sprintf(
                    '%1$s <a href="%2$s" target="_blank">%3$s</a>',
                    esc_html__( 'Enter your Mailchimp Audience (List) ID.', 'ultraaddons-elementor-lite' ),
                    esc_url( 'https://mailchimp.com/help/find-audience-id/' ),
                    esc_html__( 'Find your Audience ID →', 'ultraaddons-elementor-lite' )
                ),
            ]
        );

        $this->add_control(
            'tags',
            [
                'label'       => esc_html__( 'Subscriber Tags', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'newsletter, website-lead', 'ultraaddons-elementor-lite' ),
                'description' => esc_html__( 'Comma-separated tags to automatically attach to subscribers in Mailchimp.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'double_optin',
            [
                'label'        => esc_html__( 'Double Opt-In', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => '',
                'description'  => esc_html__( 'Send a confirmation email before adding the user to your active list.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       CONTENT TAB: Section 2 - Layout & Presets
       ========================================================================= */
    protected function register_layout_presets_controls() {
        $this->start_controls_section(
            'section_mc_presets',
            [
                'label' => esc_html__( 'Layout & Presets', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

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
                'description' => esc_html__( 'Select 1-click interactive design preset.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'form_layout',
            [
                'label'       => esc_html__( 'Form Layout', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'inline-joined',
                'options'     => [
                    'inline-joined' => esc_html__( 'Inline Newsletter (Joined Input & Button)', 'ultraaddons-elementor-lite' ),
                    'single-col'    => esc_html__( '1 Column (Stacked Full Width)', 'ultraaddons-elementor-lite' ),
                    'two-cols'      => esc_html__( '2 Columns (Side-by-side Fields)', 'ultraaddons-elementor-lite' ),
                ],
                'description' => esc_html__( 'Inline Newsletter connects the input and submit button seamlessly in one unified row.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       CONTENT TAB: Section 3 - Form Fields
       ========================================================================= */
    protected function register_fields_controls() {
        $this->start_controls_section(
            'section_mc_fields',
            [
                'label' => esc_html__( 'Form Fields', 'ultraaddons-elementor-lite' ),
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
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'email_placeholder',
            [
                'label'       => esc_html__( 'Email Placeholder', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Enter your email', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'show_fname',
            [
                'label'        => esc_html__( 'First Name Field', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => '',
                'condition'    => [
                    'form_layout!' => 'inline-joined',
                ],
            ]
        );

        $this->add_control(
            'fname_placeholder',
            [
                'label'       => esc_html__( 'First Name Placeholder', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'First name', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'show_fname'   => 'yes',
                    'form_layout!' => 'inline-joined',
                ],
            ]
        );

        $this->add_control(
            'show_lname',
            [
                'label'        => esc_html__( 'Last Name Field', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => '',
                'condition'    => [
                    'form_layout!' => 'inline-joined',
                ],
            ]
        );

        $this->add_control(
            'lname_placeholder',
            [
                'label'       => esc_html__( 'Last Name Placeholder', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Last name', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'show_lname'   => 'yes',
                    'form_layout!' => 'inline-joined',
                ],
            ]
        );

        $this->add_control(
            'show_phone',
            [
                'label'        => esc_html__( 'Phone Field', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => '',
                'condition'    => [
                    'form_layout!' => 'inline-joined',
                ],
            ]
        );

        $this->add_control(
            'phone_placeholder',
            [
                'label'       => esc_html__( 'Phone Placeholder', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Phone number', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'show_phone'   => 'yes',
                    'form_layout!' => 'inline-joined',
                ],
            ]
        );

        $this->add_control(
            'show_gdpr',
            [
                'label'        => esc_html__( 'GDPR / Terms Checkbox', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $this->add_control(
            'gdpr_text',
            [
                'label'       => esc_html__( 'GDPR Notice Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXTAREA,
                'rows'        => 2,
                'default'     => esc_html__( 'I agree to the privacy policy and consent to receive marketing emails.', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'show_gdpr' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       CONTENT TAB: Section 4 - Submit Button
       ========================================================================= */
    protected function register_button_controls() {
        $this->start_controls_section(
            'section_mc_button',
            [
                'label' => esc_html__( 'Submit Button', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'btn_text',
            [
                'label'       => esc_html__( 'Button Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Subscribe', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'e.g. Subscribe, Sign Up, →', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'btn_icon',
            [
                'label'       => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::ICONS,
                'default'     => [],
            ]
        );

        $this->add_control(
            'btn_icon_align',
            [
                'label'     => esc_html__( 'Icon Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'right',
                'options'   => [
                    'left'  => esc_html__( 'Before Text (Left)', 'ultraaddons-elementor-lite' ),
                    'right' => esc_html__( 'After Text (Right)', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'btn_icon[value]!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_align',
            [
                'label'        => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::CHOOSE,
                'options'      => [
                    'left'    => [ 'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-left' ],
                    'center'  => [ 'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-center' ],
                    'right'   => [ 'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-right' ],
                    'justify' => [ 'title' => esc_html__( 'Full Width', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-justify' ],
                ],
                'default'      => 'left',
                'prefix_class' => 'ua-mailchimp-btn-align-',
                'condition'    => [
                    'form_layout!' => 'inline-joined',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       CONTENT TAB: Section 5 - Form Header (Title & Desc)
       ========================================================================= */
    protected function register_header_controls() {
        $this->start_controls_section(
            'section_mc_header',
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
                'default'     => esc_html__( 'Subscribe to Our Newsletter', 'ultraaddons-elementor-lite' ),
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
                'default'     => esc_html__( 'Stay updated with our latest offers, articles, and products.', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'show_header' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       CONTENT TAB: Section 6 - Messages & Redirection
       ========================================================================= */
    protected function register_messages_controls() {
        $this->start_controls_section(
            'section_mc_messages',
            [
                'label' => esc_html__( 'Messages & Redirect', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'msg_success',
            [
                'label'       => esc_html__( 'Success Message', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Thank you for subscribing!', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'msg_already',
            [
                'label'       => esc_html__( 'Already Subscribed Message', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'You are already subscribed to our list!', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'msg_error',
            [
                'label'       => esc_html__( 'General Error Message', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Subscription failed. Please check your email and try again.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'redirect_url',
            [
                'label'       => esc_html__( 'Redirect URL (Optional)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://yourdomain.com/thank-you', 'ultraaddons-elementor-lite' ),
                'description' => esc_html__( 'Optionally redirect the user to a Thank You page upon successful subscription.', 'ultraaddons-elementor-lite' ),
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
                    'px' => [ 'min' => 200, 'max' => 1200 ],
                    '%'  => [ 'min' => 10, 'max' => 100 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-mailchimp-wrapper' => 'max-width: {{SIZE}}{{UNIT}}; margin-left: auto; margin-right: auto;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'container_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-mailchimp-wrapper',
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-mailchimp-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'container_border',
                'selector' => '{{WRAPPER}} .ua-mailchimp-wrapper',
            ]
        );

        $this->add_responsive_control(
            'container_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-mailchimp-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'container_shadow',
                'selector' => '{{WRAPPER}} .ua-mailchimp-wrapper',
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
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .ua-mailchimp-header' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Title Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0f172a',
                'selectors' => [
                    '{{WRAPPER}} .ua-mailchimp-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .ua-mailchimp-title',
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label'     => esc_html__( 'Description Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#64748b',
                'selectors' => [
                    '{{WRAPPER}} .ua-mailchimp-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'desc_typography',
                'selector' => '{{WRAPPER}} .ua-mailchimp-description',
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
                    '{{WRAPPER}} .ua-mailchimp-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'label_typography',
                'selector' => '{{WRAPPER}} .ua-mailchimp-label',
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE TAB: Section 4 - Inputs & Fields
       ========================================================================= */
    protected function register_style_inputs_controls() {
        $this->start_controls_section(
            'section_style_inputs',
            [
                'label' => esc_html__( 'Inputs & Fields', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'input_height',
            [
                'label'      => esc_html__( 'Input Height', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 36, 'max' => 70 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 50 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-mailchimp-wrapper' => '--ua-mc-input-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'row_gap',
            [
                'label'      => esc_html__( 'Row Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 6, 'max' => 50 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 16 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-mailchimp-wrapper' => '--ua-mc-row-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'col_gap',
            [
                'label'      => esc_html__( 'Column Gap (2-Cols)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 6, 'max' => 50 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 16 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-mailchimp-wrapper' => '--ua-mc-col-gap: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'form_layout' => 'two-cols',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'input_typography',
                'selector' => '{{WRAPPER}} .ua-mailchimp-input',
            ]
        );

        // State Tabs: Normal vs Focus
        $this->start_controls_tabs( 'tabs_input_states' );

        // Normal Tab
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
                    '{{WRAPPER}} .ua-mailchimp-wrapper .ua-mailchimp-input' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'input_placeholder_color',
            [
                'label'     => esc_html__( 'Placeholder Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-mailchimp-wrapper .ua-mailchimp-input::placeholder' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'input_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-mailchimp-wrapper .ua-mailchimp-input' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'input_border',
                'selector' => '{{WRAPPER}} .ua-mailchimp-wrapper .ua-mailchimp-input',
            ]
        );

        $this->add_responsive_control(
            'input_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-mailchimp-wrapper .ua-mailchimp-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->end_controls_tab();

        // Focus Tab
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
                    '{{WRAPPER}} .ua-mailchimp-wrapper .ua-mailchimp-input:focus' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'input_focus_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-mailchimp-wrapper .ua-mailchimp-input:focus' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'input_focus_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-mailchimp-wrapper .ua-mailchimp-input:focus' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'input_focus_shadow',
                'selector' => '{{WRAPPER}} .ua-mailchimp-wrapper .ua-mailchimp-input:focus',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE TAB: Section 5 - Submit Button
       ========================================================================= */
    protected function register_style_button_controls() {
        $this->start_controls_section(
            'section_style_button',
            [
                'label' => esc_html__( 'Submit Button', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
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
                    '{{WRAPPER}} .ua-mailchimp-wrapper' => '--ua-mc-btn-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .ua-mailchimp-submit',
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-mailchimp-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // State Tabs: Normal vs Hover
        $this->start_controls_tabs( 'tabs_btn_states' );

        // Normal
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
                    '{{WRAPPER}} .ua-mailchimp-submit' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'btn_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-mailchimp-submit',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'btn_border',
                'selector' => '{{WRAPPER}} .ua-mailchimp-submit',
            ]
        );

        $this->add_responsive_control(
            'btn_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-mailchimp-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'btn_shadow',
                'selector' => '{{WRAPPER}} .ua-mailchimp-submit',
            ]
        );

        $this->end_controls_tab();

        // Hover
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
                    '{{WRAPPER}} .ua-mailchimp-submit:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'btn_hover_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-mailchimp-submit:hover',
            ]
        );

        $this->add_control(
            'btn_hover_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-mailchimp-submit:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'btn_hover_shadow',
                'selector' => '{{WRAPPER}} .ua-mailchimp-submit:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE TAB: Section 6 - GDPR Checkbox
       ========================================================================= */
    protected function register_style_gdpr_controls() {
        $this->start_controls_section(
            'section_style_gdpr',
            [
                'label'     => esc_html__( 'GDPR Checkbox', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_gdpr' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'gdpr_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#64748b',
                'selectors' => [
                    '{{WRAPPER}} .ua-mailchimp-gdpr-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gdpr_typography',
                'selector' => '{{WRAPPER}} .ua-mailchimp-gdpr-label',
            ]
        );

        $this->add_control(
            'gdpr_active_color',
            [
                'label'     => esc_html__( 'Active / Check Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#3b82f6',
                'selectors' => [
                    '{{WRAPPER}} .ua-mailchimp-gdpr-checkbox:checked' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE TAB: Section 7 - Response Messages
       ========================================================================= */
    protected function register_style_messages_controls() {
        $this->start_controls_section(
            'section_style_messages',
            [
                'label' => esc_html__( 'Alerts & Messages', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'success_bg_color',
            [
                'label'     => esc_html__( 'Success Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f0fdf4',
                'selectors' => [
                    '{{WRAPPER}} .ua-mailchimp-message-success' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'success_text_color',
            [
                'label'     => esc_html__( 'Success Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#166534',
                'selectors' => [
                    '{{WRAPPER}} .ua-mailchimp-message-success' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'error_bg_color',
            [
                'label'     => esc_html__( 'Error Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fef2f2',
                'selectors' => [
                    '{{WRAPPER}} .ua-mailchimp-message-error' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'error_text_color',
            [
                'label'     => esc_html__( 'Error Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#991b1b',
                'selectors' => [
                    '{{WRAPPER}} .ua-mailchimp-message-error' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       MAIN RENDER METHOD
       ========================================================================= */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $api_key = ! empty( $settings['api_key'] ) ? trim( $settings['api_key'] ) : '';
        $list_id = ! empty( $settings['list_id'] ) ? trim( $settings['list_id'] ) : '';

        // In Elementor Editor: Show helpful setup notice if API key is missing
        if ( empty( $api_key ) || empty( $list_id ) ) {
            echo '<div class="ua-mailchimp-missing-box">';
            echo '<div class="ua-mailchimp-missing-icon">🐒</div>';
            echo '<div class="ua-mailchimp-missing-title">' . esc_html__( 'Mailchimp Setup Required', 'ultraaddons-elementor-lite' ) . '</div>';
            echo '<div class="ua-mailchimp-missing-desc">' . esc_html__( 'Please enter your Mailchimp API Key and Audience ID in the widget settings panel.', 'ultraaddons-elementor-lite' ) . '</div>';
            echo '</div>';
            return;
        }

        $preset  = ! empty( $settings['preset_style'] ) ? esc_attr( $settings['preset_style'] ) : 'clean-classic';
        $layout  = ! empty( $settings['form_layout'] ) ? esc_attr( $settings['form_layout'] ) : 'inline-joined';

        $wrapper_classes = [
            'ua-mailchimp-wrapper',
            'ua-mailchimp-preset-' . $preset,
            'ua-mailchimp-layout-' . $layout,
        ];

        if ( 'yes' === $settings['hide_labels'] ) {
            $wrapper_classes[] = 'ua-mailchimp-hide-labels';
        }

        $nonce = wp_create_nonce( 'ultraaddons_mailchimp_nonce' );

        $redirect_url = ! empty( $settings['redirect_url']['url'] ) ? esc_url( $settings['redirect_url']['url'] ) : '';

        // Data attributes for secure AJAX handling
        $data_attrs = [
            'data-ajax-url'          => esc_url( admin_url( 'admin-ajax.php' ) ),
            'data-nonce'             => esc_attr( $nonce ),
            'data-api-key'           => esc_attr( base64_encode( $api_key ) ),
            'data-list-id'           => esc_attr( $list_id ),
            'data-tags'              => esc_attr( ! empty( $settings['tags'] ) ? $settings['tags'] : '' ),
            'data-double-optin'      => esc_attr( $settings['double_optin'] === 'yes' ? 'yes' : 'no' ),
            'data-redirect-url'      => esc_attr( $redirect_url ),
            'data-msg-invalid-email' => esc_attr__( 'Please enter a valid email address.', 'ultraaddons-elementor-lite' ),
            'data-msg-gdpr-required' => esc_attr__( 'You must agree to the terms to subscribe.', 'ultraaddons-elementor-lite' ),
        ];

        $rendered_attrs = '';
        foreach ( $data_attrs as $k => $v ) {
            $rendered_attrs .= ' ' . $k . '="' . $v . '"';
        }

        echo '<div class="' . esc_attr( implode( ' ', $wrapper_classes ) ) . '"' . $rendered_attrs . '>';

        // Render Form Header if enabled
        if ( 'yes' === $settings['show_header'] ) {
            $allowed_tags = [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p' ];
            $tag = ! empty( $settings['header_title_tag'] ) && in_array( $settings['header_title_tag'], $allowed_tags, true ) ? $settings['header_title_tag'] : 'h3';

            echo '<div class="ua-mailchimp-header">';
            if ( ! empty( $settings['header_title'] ) ) {
                echo '<' . $tag . ' class="ua-mailchimp-title">' . esc_html( $settings['header_title'] ) . '</' . $tag . '>';
            }
            if ( ! empty( $settings['header_desc'] ) ) {
                echo '<p class="ua-mailchimp-description">' . wp_kses_post( $settings['header_desc'] ) . '</p>';
            }
            echo '</div>';
        }

        echo '<form class="ua-mailchimp-form" method="post">';
        echo '<div class="ua-mailchimp-row">';
        echo '<div class="ua-mailchimp-fields-wrap">';

        // First Name (optional)
        if ( 'yes' === $settings['show_fname'] && 'inline-joined' !== $layout ) {
            echo '<div class="ua-mailchimp-field-group ua-mailchimp-field-fname">';
            echo '<label class="ua-mailchimp-label">' . esc_html__( 'First Name', 'ultraaddons-elementor-lite' ) . '</label>';
            echo '<input type="text" name="ua_mc_fname" class="ua-mailchimp-input" placeholder="' . esc_attr( $settings['fname_placeholder'] ) . '">';
            echo '</div>';
        }

        // Last Name (optional)
        if ( 'yes' === $settings['show_lname'] && 'inline-joined' !== $layout ) {
            echo '<div class="ua-mailchimp-field-group ua-mailchimp-field-lname">';
            echo '<label class="ua-mailchimp-label">' . esc_html__( 'Last Name', 'ultraaddons-elementor-lite' ) . '</label>';
            echo '<input type="text" name="ua_mc_lname" class="ua-mailchimp-input" placeholder="' . esc_attr( $settings['lname_placeholder'] ) . '">';
            echo '</div>';
        }

        // Email (Required)
        echo '<div class="ua-mailchimp-field-group ua-mailchimp-field-email">';
        echo '<label class="ua-mailchimp-label">' . esc_html__( 'Email', 'ultraaddons-elementor-lite' ) . '<span class="ua-mailchimp-required-mark">*</span></label>';
        echo '<input type="email" name="ua_mc_email" class="ua-mailchimp-input" placeholder="' . esc_attr( $settings['email_placeholder'] ) . '" required>';
        echo '</div>';

        // Phone (optional)
        if ( 'yes' === $settings['show_phone'] && 'inline-joined' !== $layout ) {
            echo '<div class="ua-mailchimp-field-group ua-mailchimp-field-phone">';
            echo '<label class="ua-mailchimp-label">' . esc_html__( 'Phone', 'ultraaddons-elementor-lite' ) . '</label>';
            echo '<input type="tel" name="ua_mc_phone" class="ua-mailchimp-input" placeholder="' . esc_attr( $settings['phone_placeholder'] ) . '">';
            echo '</div>';
        }

        // Submit Button (If Inline Joined, placed inside fields-wrap right next to input)
        if ( 'inline-joined' === $layout ) {
            echo '<div class="ua-mailchimp-submit-wrap">';
            $this->render_submit_button( $settings );
            echo '</div>';
        }

        echo '</div>'; // .ua-mailchimp-fields-wrap

        // Submit Button (If Stacked or 2-Cols)
        if ( 'inline-joined' !== $layout ) {
            echo '<div class="ua-mailchimp-submit-wrap">';
            $this->render_submit_button( $settings );
            echo '</div>';
        }

        echo '</div>'; // .ua-mailchimp-row

        // GDPR Checkbox
        if ( 'yes' === $settings['show_gdpr'] ) {
            echo '<div class="ua-mailchimp-gdpr-wrap">';
            echo '<label class="ua-mailchimp-gdpr-label">';
            echo '<input type="checkbox" name="ua_mc_gdpr" class="ua-mailchimp-gdpr-checkbox" value="1" required>';
            echo '<span>' . wp_kses_post( $settings['gdpr_text'] ) . '</span>';
            echo '</label>';
            echo '</div>';
        }

        echo '<div class="ua-mailchimp-message" style="display:none;" role="alert"></div>';

        echo '</form>';
        echo '</div>'; // .ua-mailchimp-wrapper
    }

    /**
     * Helper to render submit button with icon and spinner.
     */
    protected function render_submit_button( $settings ) {
        $btn_text = ! empty( $settings['btn_text'] ) ? $settings['btn_text'] : esc_html__( 'Subscribe', 'ultraaddons-elementor-lite' );
        $has_icon = ! empty( $settings['btn_icon']['value'] );
        $icon_pos = ! empty( $settings['btn_icon_align'] ) ? $settings['btn_icon_align'] : 'right';

        echo '<button type="submit" class="ua-mailchimp-submit">';
        echo '<span class="ua-mailchimp-spinner"></span>';

        if ( $has_icon && 'left' === $icon_pos ) {
            Icons_Manager::render_icon( $settings['btn_icon'], [ 'aria-hidden' => 'true' ] );
        }

        echo '<span class="ua-mailchimp-btn-text">' . esc_html( $btn_text ) . '</span>';

        if ( $has_icon && 'right' === $icon_pos ) {
            Icons_Manager::render_icon( $settings['btn_icon'], [ 'aria-hidden' => 'true' ] );
        }

        echo '</button>';
    }

    /* =========================================================================
       AJAX SUBSCRIPTION ENDPOINT
       ========================================================================= */
    public static function ajax_subscribe() {
        check_ajax_referer( 'ultraaddons_mailchimp_nonce', 'nonce' );

        $email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

        if ( empty( $email ) || ! is_email( $email ) ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Please provide a valid email address.', 'ultraaddons-elementor-lite' ) ] );
        }

        $api_key_encoded = isset( $_POST['api_key'] ) ? sanitize_text_field( wp_unslash( $_POST['api_key'] ) ) : '';
        $api_key = base64_decode( $api_key_encoded );
        $list_id = isset( $_POST['list_id'] ) ? sanitize_text_field( wp_unslash( $_POST['list_id'] ) ) : '';

        if ( empty( $api_key ) || empty( $list_id ) ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Mailchimp API credentials are not configured.', 'ultraaddons-elementor-lite' ) ] );
        }

        // Determine Datacenter from API key (e.g. key ends with -us20)
        $key_parts = explode( '-', $api_key );
        $dc = isset( $key_parts[1] ) ? trim( $key_parts[1] ) : '';

        if ( empty( $dc ) ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Invalid Mailchimp API Key format.', 'ultraaddons-elementor-lite' ) ] );
        }

        $endpoint = sprintf( 'https://%s.api.mailchimp.com/3.0/lists/%s/members', $dc, $list_id );

        $double_optin = isset( $_POST['double_optin'] ) && 'yes' === $_POST['double_optin'];
        $status = $double_optin ? 'pending' : 'subscribed';

        $fname = isset( $_POST['fname'] ) ? sanitize_text_field( wp_unslash( $_POST['fname'] ) ) : '';
        $lname = isset( $_POST['lname'] ) ? sanitize_text_field( wp_unslash( $_POST['lname'] ) ) : '';
        $phone = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';

        $merge_fields = [];
        if ( ! empty( $fname ) ) $merge_fields['FNAME'] = $fname;
        if ( ! empty( $lname ) ) $merge_fields['LNAME'] = $lname;
        if ( ! empty( $phone ) ) $merge_fields['PHONE'] = $phone;

        $tags_raw = isset( $_POST['tags'] ) ? sanitize_text_field( wp_unslash( $_POST['tags'] ) ) : '';
        $tags = [];
        if ( ! empty( $tags_raw ) ) {
            $tag_list = explode( ',', $tags_raw );
            foreach ( $tag_list as $t ) {
                $t = trim( $t );
                if ( ! empty( $t ) ) $tags[] = $t;
            }
        }

        $payload = [
            'email_address' => $email,
            'status'        => $status,
        ];

        if ( ! empty( $merge_fields ) ) {
            $payload['merge_fields'] = $merge_fields;
        }

        if ( ! empty( $tags ) ) {
            $payload['tags'] = $tags;
        }

        $response = wp_remote_post( $endpoint, [
            'method'  => 'POST',
            'timeout' => 15,
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode( 'user:' . $api_key ),
                'Content-Type'  => 'application/json',
            ],
            'body'    => wp_json_encode( $payload ),
        ] );

        if ( is_wp_error( $response ) ) {
            wp_send_json_error( [ 'message' => $response->get_error_message() ] );
        }

        $code = wp_remote_retrieve_response_code( $response );
        $body = json_decode( wp_remote_retrieve_body( $response ), true );

        $redirect_url = isset( $_POST['redirect_url'] ) ? esc_url_raw( wp_unslash( $_POST['redirect_url'] ) ) : '';

        if ( 200 === $code || 204 === $code ) {
            wp_send_json_success( [
                'message'  => esc_html__( 'Thank you for subscribing!', 'ultraaddons-elementor-lite' ),
                'redirect' => $redirect_url,
            ] );
        } elseif ( 400 === $code && isset( $body['title'] ) && 'Member Exists' === $body['title'] ) {
            wp_send_json_error( [ 'message' => esc_html__( 'You are already subscribed to our newsletter!', 'ultraaddons-elementor-lite' ) ] );
        } else {
            $err_msg = ! empty( $body['detail'] ) ? $body['detail'] : esc_html__( 'Subscription failed. Please verify your details.', 'ultraaddons-elementor-lite' );
            wp_send_json_error( [ 'message' => $err_msg ] );
        }
    }
}
