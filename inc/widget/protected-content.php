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
 * Protected Content Widget for UltraAddons Elementor Lite.
 *
 * Provides Password Protection (Single/Multiple), User Role Gating,
 * Login Status Protection, Elementor Template Embedding, Ajax Instant Unlock,
 * Eye Toggle, Blur Teaser Mode, and Custom Cookie Durations.
 *
 * @since 2.0.3.4
 * @package UltraAddons
 */
class Protected_Content extends Base {

    /**
     * Constructor — registers Ajax handlers and localizes frontend script.
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        add_action( 'wp_ajax_ultraaddons_verify_protected_content', [ __CLASS__, 'ajax_verify_password' ] );
        add_action( 'wp_ajax_nopriv_ultraaddons_verify_protected_content', [ __CLASS__, 'ajax_verify_password' ] );

        // Enqueue and localize script
        wp_register_style(
            'ultraaddons-protected-content',
            ULTRA_ADDONS_ASSETS . 'css/widgets/protected-content.css',
            [],
            defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( ULTRA_ADDONS_DIR . 'assets/css/widgets/protected-content.css' ) : ULTRA_ADDONS_VERSION
        );

        wp_register_script(
            'ultraaddons-protected-content',
            ULTRA_ADDONS_ASSETS . 'js/frontend-protected-content.js',
            [ 'jquery' ],
            defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( ULTRA_ADDONS_DIR . 'assets/js/frontend-protected-content.js' ) : ULTRA_ADDONS_VERSION,
            true
        );

        wp_localize_script(
            'ultraaddons-protected-content',
            'ultraaddons_protected_content',
            [
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'ua_protected_content_nonce' ),
            ]
        );
    }

    /**
     * Widget Style Dependencies.
     */
    public function get_style_depends() {
        return [ 'ultraaddons-protected-content', 'font-awesome-5-all' ];
    }

    /**
     * Widget Script Dependencies.
     */
    public function get_script_depends() {
        return [ 'ultraaddons-protected-content' ];
    }

    /**
     * Get Widget Name.
     */
    public function get_name() {
        return 'ultraaddons-protected-content';
    }

    /**
     * Get Widget Title.
     */
    public function get_title() {
        return esc_html__( 'Protected Content', 'ultraaddons-elementor-lite' );
    }

    /**
     * Get Widget Icon.
     */
    public function get_icon() {
        return 'ultraaddons eicon-lock';
    }

    /**
     * Get Widget Keywords.
     */
    public function get_keywords() {
        return [ 'ultraaddons', 'protected content', 'password', 'restrict', 'lock', 'role', 'gate', 'membership', 'vip' ];
    }

    /**
     * Register Widget Controls.
     */
    protected function register_controls() {
        $this->register_content_source_controls();
        $this->register_protection_settings_controls();
        $this->register_lock_screen_controls();
        $this->register_preview_controls();

        // Style Sections
        $this->register_style_card_controls();
        $this->register_style_lock_icon_controls();
        $this->register_style_typography_controls();
        $this->register_style_form_controls();
        $this->register_style_cta_controls();
    }

    /* =========================================================================
       SECTION 1: Content to Protect
       ========================================================================= */
    protected function register_content_source_controls() {
        $this->start_controls_section(
            'section_protected_content',
            [
                'label' => esc_html__( 'Protected Content', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'content_source',
            [
                'label'   => esc_html__( 'Content Source', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'wysiwyg',
                'options' => [
                    'wysiwyg'  => esc_html__( 'WYSIWYG / Rich Editor', 'ultraaddons-elementor-lite' ),
                    'template' => esc_html__( 'Elementor Saved Template (100% Free)', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'protected_wysiwyg',
            [
                'label'       => esc_html__( 'Content', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::WYSIWYG,
                'default'     => __( '<h3>🎉 Congratulations! You have unlocked this VIP content.</h3><p>This is your exclusive restricted content, sensitive data, member resources, or premium downloads.</p>', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'content_source' => 'wysiwyg',
                ],
            ]
        );

        $this->add_control(
            'protected_template',
            [
                'label'       => esc_html__( 'Select Elementor Template', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'options'     => $this->get_elementor_templates(),
                'default'     => '0',
                'condition'   => [
                    'content_source' => 'template',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       SECTION 2: Protection Settings
       ========================================================================= */
    protected function register_protection_settings_controls() {
        $this->start_controls_section(
            'section_protection_settings',
            [
                'label' => esc_html__( 'Protection Type & Rules', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'protection_type',
            [
                'label'   => esc_html__( 'Protection Type', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'password',
                'options' => [
                    'password'    => esc_html__( 'Password Protection', 'ultraaddons-elementor-lite' ),
                    'role'        => esc_html__( 'User Role Based', 'ultraaddons-elementor-lite' ),
                    'user_status' => esc_html__( 'Login Status (Logged-in / Guest)', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        // Password Mode: Single vs Multiple
        $this->add_control(
            'password_mode',
            [
                'label'     => esc_html__( 'Password Format', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'single',
                'options'   => [
                    'single'   => esc_html__( 'Single Password', 'ultraaddons-elementor-lite' ),
                    'multiple' => esc_html__( 'Multiple Passwords (One per line)', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'protection_type' => 'password',
                ],
            ]
        );

        $this->add_control(
            'password_single',
            [
                'label'       => esc_html__( 'Set Password', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '123456',
                'placeholder' => esc_html__( 'e.g. secretPass123', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'protection_type' => 'password',
                    'password_mode'   => 'single',
                ],
            ]
        );

        $this->add_control(
            'passwords_list',
            [
                'label'       => esc_html__( 'Allowed Passwords', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXTAREA,
                'rows'        => 4,
                'default'     => "pass123\nsecret2026\nvip_access",
                'description' => esc_html__( 'Enter one password per line. Any of these will unlock the content.', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'protection_type' => 'password',
                    'password_mode'   => 'multiple',
                ],
            ]
        );

        $this->add_control(
            'cookie_expiry_days',
            [
                'label'       => esc_html__( 'Remember Unlock (Days)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 1,
                'min'         => 0.05,
                'max'         => 90,
                'step'        => 0.5,
                'description' => esc_html__( 'How many days the browser should remember that the user entered the correct password.', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'protection_type' => 'password',
                ],
            ]
        );

        $this->add_control(
            'show_eye_toggle',
            [
                'label'        => esc_html__( 'Show / Hide Password Icon (👁️)', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'protection_type' => 'password',
                ],
            ]
        );

        // User Roles
        $this->add_control(
            'user_roles',
            [
                'label'       => esc_html__( 'Allowed User Roles', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT2,
                'multiple'    => true,
                'options'     => $this->get_user_roles_list(),
                'default'     => [ 'administrator' ],
                'condition'   => [
                    'protection_type' => 'role',
                ],
            ]
        );

        // User Status
        $this->add_control(
            'user_status_rule',
            [
                'label'     => esc_html__( 'Who Can View?', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'logged_in',
                'options'   => [
                    'logged_in'  => esc_html__( 'Logged-in Users Only', 'ultraaddons-elementor-lite' ),
                    'logged_out' => esc_html__( 'Guest / Logged-out Users Only', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'protection_type' => 'user_status',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       SECTION 3: Lock Screen & Form
       ========================================================================= */
    protected function register_lock_screen_controls() {
        $this->start_controls_section(
            'section_lock_screen',
            [
                'label' => esc_html__( 'Lock Screen & Form', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_lock_icon',
            [
                'label'        => esc_html__( 'Show Lock Icon', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'lock_icon',
            [
                'label'       => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::ICONS,
                'default'     => [
                    'value'   => 'fas fa-lock',
                    'library' => 'fa-solid',
                ],
                'condition'   => [
                    'show_lock_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'lock_title',
            [
                'label'       => esc_html__( 'Restricted Title', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Restricted Content', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'e.g. Members Only Area', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'lock_message',
            [
                'label'       => esc_html__( 'Restricted Message', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXTAREA,
                'rows'        => 3,
                'default'     => esc_html__( 'This content is protected. Please enter the password or sign in with an authorized account to access.', 'ultraaddons-elementor-lite' ),
            ]
        );

        // Password Form Options
        $this->add_control(
            'heading_form_fields',
            [
                'label'     => esc_html__( 'Password Form Setup', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'protection_type' => 'password',
                ],
            ]
        );

        $this->add_control(
            'form_layout',
            [
                'label'     => esc_html__( 'Form Layout', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'inline',
                'options'   => [
                    'inline'  => esc_html__( 'Inline (Input & Button in one row)', 'ultraaddons-elementor-lite' ),
                    'stacked' => esc_html__( 'Stacked (Full Width)', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'protection_type' => 'password',
                ],
            ]
        );

        $this->add_control(
            'input_placeholder',
            [
                'label'       => esc_html__( 'Input Placeholder', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Enter password...', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'protection_type' => 'password',
                ],
            ]
        );

        $this->add_control(
            'submit_btn_text',
            [
                'label'       => esc_html__( 'Submit Button Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Unlock', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'protection_type' => 'password',
                ],
            ]
        );

        // Call to Action (Login / Register Button)
        $this->add_control(
            'heading_cta',
            [
                'label'     => esc_html__( 'Call to Action (Login / Register)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'protection_type' => [ 'role', 'user_status' ],
                ],
            ]
        );

        $this->add_control(
            'show_cta_btn',
            [
                'label'        => esc_html__( 'Show Login / Register Button', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'protection_type' => [ 'role', 'user_status' ],
                ],
            ]
        );

        $this->add_control(
            'cta_btn_text',
            [
                'label'       => esc_html__( 'Button Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Log In to Access', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'protection_type' => [ 'role', 'user_status' ],
                    'show_cta_btn'    => 'yes',
                ],
            ]
        );

        $this->add_control(
            'cta_btn_link',
            [
                'label'       => esc_html__( 'Button Link', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-site.com/login', 'ultraaddons-elementor-lite' ),
                'default'     => [
                    'url' => wp_login_url(),
                ],
                'condition'   => [
                    'protection_type' => [ 'role', 'user_status' ],
                    'show_cta_btn'    => 'yes',
                ],
            ]
        );

        // Blur Teaser Mode
        $this->add_control(
            'heading_teaser',
            [
                'label'     => esc_html__( 'Teaser Effect', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'enable_blur_teaser',
            [
                'label'        => esc_html__( 'Enable Blurred Content Teaser', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'description'  => esc_html__( 'Renders a blurred preview of the content behind the lock card to entice users.', 'ultraaddons-elementor-lite' ),
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       SECTION 4: Editor Preview
       ========================================================================= */
    protected function register_preview_controls() {
        $this->start_controls_section(
            'section_preview_settings',
            [
                'label' => esc_html__( 'Editor Preview Settings', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'preview_unlocked_content',
            [
                'label'        => esc_html__( 'Preview Unlocked Content in Editor', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => '',
                'description'  => esc_html__( 'Enable to preview and edit your protected content. Leave disabled to style and preview the visitor lock screen.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE SECTION 1: Card Container
       ========================================================================= */
    protected function register_style_card_controls() {
        $this->start_controls_section(
            'section_style_card',
            [
                'label' => esc_html__( 'Lock Screen Card', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'card_max_width',
            [
                'label'      => esc_html__( 'Card Max Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [ 'min' => 280, 'max' => 1000 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 580 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pc-card' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'card_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-pc-card',
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pc-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'card_border',
                'selector' => '{{WRAPPER}} .ua-pc-card',
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pc-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'card_shadow',
                'selector' => '{{WRAPPER}} .ua-pc-card',
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE SECTION 2: Lock Icon
       ========================================================================= */
    protected function register_style_lock_icon_controls() {
        $this->start_controls_section(
            'section_style_lock_icon',
            [
                'label'     => esc_html__( 'Lock Icon', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_lock_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ef4444',
                'selectors' => [
                    '{{WRAPPER}} .ua-pc-icon-wrap' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-pc-icon-wrap svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fef2f2',
                'selectors' => [
                    '{{WRAPPER}} .ua-pc-icon-wrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label'      => esc_html__( 'Icon Size (px)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [ 'px' => [ 'min' => 16, 'max' => 60 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 28 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pc-icon-wrap i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-pc-icon-wrap svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_box_size',
            [
                'label'      => esc_html__( 'Badge Circle Size (px)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [ 'px' => [ 'min' => 40, 'max' => 120 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 68 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pc-icon-wrap' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE SECTION 3: Title & Message Typography
       ========================================================================= */
    protected function register_style_typography_controls() {
        $this->start_controls_section(
            'section_style_text',
            [
                'label' => esc_html__( 'Title & Message', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Title Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#111827',
                'selectors' => [
                    '{{WRAPPER}} .ua-pc-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .ua-pc-title',
            ]
        );

        $this->add_control(
            'message_color',
            [
                'label'     => esc_html__( 'Message Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#6b7280',
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .ua-pc-message' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'message_typography',
                'selector' => '{{WRAPPER}} .ua-pc-message',
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE SECTION 4: Password Form & Submit Button
       ========================================================================= */
    protected function register_style_form_controls() {
        $this->start_controls_section(
            'section_style_form',
            [
                'label'     => esc_html__( 'Password Form & Button', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'protection_type' => 'password',
                ],
            ]
        );

        $this->add_control(
            'heading_input_style',
            [
                'label' => esc_html__( 'Input Field', 'ultraaddons-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'input_bg_color',
            [
                'label'     => esc_html__( 'Input Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f9fafb',
                'selectors' => [
                    '{{WRAPPER}} .ua-pc-input' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_text_color',
            [
                'label'     => esc_html__( 'Input Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .ua-pc-input' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#d1d5db',
                'selectors' => [
                    '{{WRAPPER}} .ua-pc-input' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'input_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pc-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'heading_btn_style',
            [
                'label'     => esc_html__( 'Submit Button', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'btn_bg_color',
            [
                'label'     => esc_html__( 'Button Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .ua-pc-submit-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_bg_hover_color',
            [
                'label'     => esc_html__( 'Button Background (Hover)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#111827',
                'selectors' => [
                    '{{WRAPPER}} .ua-pc-submit-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_text_color',
            [
                'label'     => esc_html__( 'Button Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-pc-submit-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_border_radius',
            [
                'label'      => esc_html__( 'Button Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pc-submit-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE SECTION 5: CTA Button
       ========================================================================= */
    protected function register_style_cta_controls() {
        $this->start_controls_section(
            'section_style_cta',
            [
                'label'     => esc_html__( 'Call to Action Button', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'protection_type' => [ 'role', 'user_status' ],
                    'show_cta_btn'    => 'yes',
                ],
            ]
        );

        $this->add_control(
            'cta_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#3b82f6',
                'selectors' => [
                    '{{WRAPPER}} .ua-pc-cta-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cta_bg_hover_color',
            [
                'label'     => esc_html__( 'Background Color (Hover)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#2563eb',
                'selectors' => [
                    '{{WRAPPER}} .ua-pc-cta-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cta_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-pc-cta-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'cta_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pc-cta-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       HELPER METHODS
       ========================================================================= */

    /**
     * Get list of Elementor Saved Templates for dropdown.
     */
    protected function get_elementor_templates() {
        $templates = [ '0' => esc_html__( '— Select a Saved Template —', 'ultraaddons-elementor-lite' ) ];

        $posts = get_posts( [
            'post_type'      => 'elementor_library',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        ] );

        if ( ! empty( $posts ) ) {
            foreach ( $posts as $post ) {
                $templates[ $post->ID ] = $post->post_title;
            }
        }

        return $templates;
    }

    /**
     * Get WordPress User Roles for select options.
     */
    protected function get_user_roles_list() {
        global $wp_roles;
        $roles = [];

        if ( isset( $wp_roles->roles ) && is_array( $wp_roles->roles ) ) {
            foreach ( $wp_roles->roles as $role_key => $role_data ) {
                $roles[ $role_key ] = $role_data['name'];
            }
        }

        return $roles;
    }

    /**
     * Check if visitor has unlocked content via cookie.
     */
    protected function check_cookie_authorized( $settings, $widget_id ) {
        $passwords = $this->get_configured_passwords( $settings );

        foreach ( $passwords as $pwd ) {
            $cookie_name = 'ua_pc_' . md5( $widget_id . $pwd );
            if ( isset( $_COOKIE[ $cookie_name ] ) && $_COOKIE[ $cookie_name ] === md5( $pwd ) ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Extract array of configured passwords from widget settings.
     */
    protected static function get_configured_passwords( $settings ) {
        $passwords = [];
        $mode = isset( $settings['password_mode'] ) ? $settings['password_mode'] : 'single';

        if ( 'multiple' === $mode && ! empty( $settings['passwords_list'] ) ) {
            $lines = explode( "\n", str_replace( "\r", '', $settings['passwords_list'] ) );
            foreach ( $lines as $line ) {
                $line = trim( $line );
                if ( '' !== $line ) {
                    $passwords[] = $line;
                }
            }
        } elseif ( ! empty( $settings['password_single'] ) ) {
            $passwords[] = trim( $settings['password_single'] );
        }

        return $passwords;
    }

    /**
     * Check if current user role matches allowed roles.
     */
    protected function check_role_authorized( $allowed_roles ) {
        if ( ! is_user_logged_in() ) {
            return false;
        }

        $current_user = wp_get_current_user();
        return ! empty( array_intersect( (array) $current_user->roles, (array) $allowed_roles ) );
    }

    /* =========================================================================
     * AJAX VERIFY PASSWORD ENDPOINT
     * ========================================================================= */
    public static function ajax_verify_password() {
        check_ajax_referer( 'ua_protected_content_nonce', 'nonce' );

        $widget_id = isset( $_POST['widget_id'] ) ? sanitize_text_field( wp_unslash( $_POST['widget_id'] ) ) : '';
        $post_id   = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
        $password  = isset( $_POST['password'] ) ? sanitize_text_field( wp_unslash( $_POST['password'] ) ) : '';

        if ( empty( $widget_id ) || empty( $post_id ) || empty( $password ) ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Invalid request parameters.', 'ultraaddons-elementor-lite' ) ] );
        }

        // Get Elementor data for the post
        $elementor_data = get_post_meta( $post_id, '_elementor_data', true );
        if ( empty( $elementor_data ) ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Page data not found.', 'ultraaddons-elementor-lite' ) ] );
        }

        $elements = json_decode( $elementor_data, true );
        if ( ! is_array( $elements ) ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Invalid element data.', 'ultraaddons-elementor-lite' ) ] );
        }

        // Find widget in Elementor elements tree
        $widget_data = self::find_widget_by_id( $elements, $widget_id );
        if ( ! $widget_data || empty( $widget_data['settings'] ) ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Protected widget not found.', 'ultraaddons-elementor-lite' ) ] );
        }

        $settings  = $widget_data['settings'];
        $passwords = self::get_configured_passwords( $settings );

        if ( ! in_array( $password, $passwords, true ) ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Incorrect password. Please try again.', 'ultraaddons-elementor-lite' ) ] );
        }

        // Correct Password! Set Cookie
        $expiry_days = ! empty( $settings['cookie_expiry_days'] ) ? floatval( $settings['cookie_expiry_days'] ) : 1;
        $cookie_name = 'ua_pc_' . md5( $widget_id . $password );
        $expire_time = time() + intval( $expiry_days * DAY_IN_SECONDS );
        $secure      = is_ssl();

        setcookie( $cookie_name, md5( $password ), $expire_time, COOKIEPATH, COOKIE_DOMAIN, $secure, true );

        // Render content
        $content_html = '';
        $source = isset( $settings['content_source'] ) ? $settings['content_source'] : 'wysiwyg';

        if ( 'template' === $source && ! empty( $settings['protected_template'] ) ) {
            $template_id = absint( $settings['protected_template'] );
            $content_html = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $template_id );
        } else {
            $content_html = do_shortcode( wp_kses_post( ! empty( $settings['protected_wysiwyg'] ) ? $settings['protected_wysiwyg'] : '' ) );
        }

        wp_send_json_success( [ 'content' => $content_html ] );
    }

    /**
     * Recursively find a widget by its ID in the Elementor data tree.
     */
    protected static function find_widget_by_id( $elements, $widget_id ) {
        foreach ( $elements as $element ) {
            if ( isset( $element['id'] ) && $element['id'] === $widget_id ) {
                return $element;
            }

            if ( ! empty( $element['elements'] ) ) {
                $found = self::find_widget_by_id( $element['elements'], $widget_id );
                if ( $found ) {
                    return $found;
                }
            }
        }

        return null;
    }

    /* =========================================================================
       MAIN RENDER METHOD
       ========================================================================= */
    protected function render() {
        $settings  = $this->get_settings_for_display();
        $widget_id = $this->get_id();
        $is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();

        // 1. Authorization check
        $authorized = false;
        $type       = ! empty( $settings['protection_type'] ) ? $settings['protection_type'] : 'password';

        if ( 'password' === $type ) {
            $authorized = $this->check_cookie_authorized( $settings, $widget_id );
        } elseif ( 'role' === $type ) {
            $allowed_roles = ! empty( $settings['user_roles'] ) ? $settings['user_roles'] : [];
            $authorized    = $this->check_role_authorized( $allowed_roles );
        } elseif ( 'user_status' === $type ) {
            $rule       = ! empty( $settings['user_status_rule'] ) ? $settings['user_status_rule'] : 'logged_in';
            $authorized = ( 'logged_in' === $rule ) ? is_user_logged_in() : ! is_user_logged_in();
        } else {
            $authorized = $this->check_cookie_authorized( $settings, $widget_id );
        }

        // In Elementor Editor: show lock screen by default, show unlocked content only when toggle is on
        if ( $is_editor ) {
            $authorized = ( 'yes' === $settings['preview_unlocked_content'] );
        }

        echo '<div class="ua-protected-content-wrap">';

        if ( $authorized ) {
            // Unlocked Content
            echo '<div class="ua-pc-unlocked-content">';
            $this->render_unlocked_content( $settings );
            echo '</div>';
        } else {
            // Lock Screen / Gatekeeper
            $teaser = 'yes' === $settings['enable_blur_teaser'];

            if ( $teaser ) {
                echo '<div class="ua-pc-teaser-wrap">';
                echo '<div class="ua-pc-teaser-preview">';
                $this->render_unlocked_content( $settings );
                echo '</div>';
                echo '<div class="ua-pc-teaser-overlay">';
            }

            echo '<div class="ua-pc-gate-container">';
            $this->render_lock_screen( $settings, $widget_id );
            echo '</div>';

            if ( $teaser ) {
                echo '</div>'; // .ua-pc-teaser-overlay
                echo '</div>'; // .ua-pc-teaser-wrap
            }
        }

        echo '</div>'; // .ua-protected-content-wrap
    }

    /**
     * Render the Unlocked Content.
     */
    protected function render_unlocked_content( $settings ) {
        $source = ! empty( $settings['content_source'] ) ? $settings['content_source'] : 'wysiwyg';

        if ( 'template' === $source && ! empty( $settings['protected_template'] ) ) {
            $template_id = absint( $settings['protected_template'] );
            echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $template_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            echo do_shortcode( wp_kses_post( ! empty( $settings['protected_wysiwyg'] ) ? $settings['protected_wysiwyg'] : '' ) );
        }
    }

    /**
     * Render the Lock Screen / Gatekeeper Card.
     */
    protected function render_lock_screen( $settings, $widget_id ) {
        $type = ! empty( $settings['protection_type'] ) ? $settings['protection_type'] : 'password';
        $post_id = get_the_ID();
        ?>
        <div class="ua-pc-card">
            
            <?php if ( 'yes' === $settings['show_lock_icon'] ) : ?>
                <div class="ua-pc-icon-wrap">
                    <?php
                    if ( ! empty( $settings['lock_icon']['value'] ) ) {
                        Icons_Manager::render_icon( $settings['lock_icon'], [ 'aria-hidden' => 'true' ] );
                    } else {
                        echo '<i class="fas fa-lock" aria-hidden="true"></i>';
                    }
                    ?>
                </div>
            <?php endif; ?>

            <?php if ( ! empty( $settings['lock_title'] ) ) : ?>
                <h3 class="ua-pc-title"><?php echo esc_html( $settings['lock_title'] ); ?></h3>
            <?php endif; ?>

            <?php if ( ! empty( $settings['lock_message'] ) ) : ?>
                <div class="ua-pc-message"><?php echo wp_kses_post( $settings['lock_message'] ); ?></div>
            <?php endif; ?>

            <?php if ( 'password' === $type || empty( $type ) || 'hybrid' === $type ) : ?>
                <?php
                $form_layout = ! empty( $settings['form_layout'] ) ? $settings['form_layout'] : 'inline';
                $form_class  = 'ua-pc-form ua-pc-form-' . esc_attr( $form_layout );
                ?>
                <form class="<?php echo esc_attr( $form_class ); ?>"
                      data-widget-id="<?php echo esc_attr( $widget_id ); ?>"
                      data-post-id="<?php echo esc_attr( $post_id ); ?>"
                      data-nonce="<?php echo esc_attr( wp_create_nonce( 'ua_protected_content_nonce' ) ); ?>">

                    <div class="ua-pc-input-wrap">
                        <input type="password"
                               class="ua-pc-input"
                               placeholder="<?php echo esc_attr( ! empty( $settings['input_placeholder'] ) ? $settings['input_placeholder'] : 'Enter password...' ); ?>"
                               autocomplete="current-password" />

                        <?php if ( 'yes' === $settings['show_eye_toggle'] ) : ?>
                            <button type="button" class="ua-pc-eye-btn" aria-label="<?php esc_attr_e( 'Toggle Password Visibility', 'ultraaddons-elementor-lite' ); ?>">
                                <i class="fas fa-eye" aria-hidden="true"></i>
                            </button>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="ua-pc-submit-btn">
                        <?php echo esc_html( ! empty( $settings['submit_btn_text'] ) ? $settings['submit_btn_text'] : 'Unlock' ); ?>
                    </button>

                    <div class="ua-pc-error"></div>
                </form>
            <?php else : ?>
                <?php if ( 'yes' === $settings['show_cta_btn'] && ! empty( $settings['cta_btn_text'] ) ) : ?>
                    <?php
                    $cta_url = ! empty( $settings['cta_btn_link']['url'] ) ? $settings['cta_btn_link']['url'] : wp_login_url();
                    $target  = ! empty( $settings['cta_btn_link']['is_external'] ) ? ' target="_blank"' : '';
                    $nofollow= ! empty( $settings['cta_btn_link']['nofollow'] ) ? ' rel="nofollow"' : '';
                    ?>
                    <div class="ua-pc-cta-wrap">
                        <a href="<?php echo esc_url( $cta_url ); ?>" class="ua-pc-cta-btn"<?php echo $target . $nofollow; ?>>
                            <?php echo esc_html( $settings['cta_btn_text'] ); ?>
                        </a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

        </div>
        <?php
    }
}
