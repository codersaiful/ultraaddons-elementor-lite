<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

/**
 * Woo Account (My Account) Widget
 *
 * Full-featured WooCommerce customer account dashboard widget with:
 * - 4 Layouts: Vertical Left Sidebar, Vertical Right Sidebar, Horizontal Top Bar, Grid App Style
 * - 4 Design Presets: Modern Rounded, Clean Minimal, Classic Card, Luxury Dark Accent
 * - Profile Identity Header: User avatar, greeting, role badge, member since, quick stats counters
 * - Tab Navigation with custom icons, labels, reordering, and unlimited custom tabs (Elementor Templates / Shortcode)
 * - High-Converting Logged-Out Experience: Modern dual-tabbed Login & Register card with password toggle
 * - Realistic Elementor Editor Mock Preview for seamless live styling of tables, badges, and cards
 * - Deep granular styling controls for tabs, tables, status badges, forms, notices, and banners
 *
 * @since 3.1.2
 * @package UltraAddons\Widget
 */
class Woo_Account extends Base {

    /**
     * Active widget settings for AJAX and filter hooks
     */
    public static $active_settings = [];

    /**
     * Constructor — register style and script dependencies
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        wp_register_style(
            'ultraaddons-woo-account',
            ULTRA_ADDONS_ASSETS . 'css/widgets/woo-account.css',
            [],
            ULTRA_ADDONS_VERSION,
            'all'
        );
        wp_enqueue_style( 'ultraaddons-woo-account' );

        wp_register_script(
            'ultraaddons-woo-account',
            ULTRA_ADDONS_ASSETS . 'js/widgets/woo-account.js',
            [ 'jquery' ],
            ULTRA_ADDONS_VERSION,
            true
        );
        wp_enqueue_script( 'ultraaddons-woo-account' );

        wp_localize_script(
            'ultraaddons-woo-account',
            'uaAccountConfig',
            [
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'ua_account_nonce' ),
                'i18n'     => [
                    'show_password' => esc_html__( 'Show password', 'ultraaddons-elementor-lite' ),
                    'hide_password' => esc_html__( 'Hide password', 'ultraaddons-elementor-lite' ),
                    'toggle_menu'   => esc_html__( 'Toggle Account Menu', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );
    }

    /**
     * Widget Name
     */
    public function get_name() {
        return 'ultraaddons-woo-account';
    }

    /**
     * Widget Title
     */
    public function get_title() {
        return esc_html__( 'Woo Account', 'ultraaddons-elementor-lite' );
    }

    /**
     * Widget Icon
     */
    public function get_icon() {
        return 'ultraaddons eicon-my-account';
    }

    /**
     * Widget Categories
     */
    public function get_categories() {
        return [ 'ultraaddons-wc', 'ultraaddons-elementor-lite' ];
    }

    /**
     * Widget Keywords
     */
    public function get_keywords() {
        return [ 'ultraaddons', 'ua', 'account', 'woo account', 'my account', 'dashboard', 'orders', 'downloads', 'profile', 'login', 'register', 'woocommerce' ];
    }

    /**
     * Style Dependencies
     */
    public function get_style_depends() {
        return [ 'ultraaddons-woo-account' ];
    }

    /**
     * Script Dependencies
     */
    public function get_script_depends() {
        return [ 'jquery', 'ultraaddons-woo-account' ];
    }

    /**
     * Reload preview on layout or critical setting changes
     */
    public function is_reload_preview_required() {
        return true;
    }

    /**
     * Helper: Fetch published Elementor Library templates
     */
    public static function get_elementor_templates() {
        $templates = [ '' => esc_html__( '— Select Template —', 'ultraaddons-elementor-lite' ) ];
        $posts = get_posts( [
            'post_type'      => 'elementor_library',
            'posts_per_page' => 100,
            'post_status'    => 'publish',
            'orderby'        => 'title',
            'order'          => 'ASC',
        ] );

        if ( ! empty( $posts ) && ! is_wp_error( $posts ) ) {
            foreach ( $posts as $post ) {
                $templates[ $post->ID ] = $post->post_title;
            }
        }
        return $templates;
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        $this->register_content_controls();
        $this->register_style_controls();
    }

    /* -------------------------------------------------------------------------
     * CONTENT CONTROLS
     * ---------------------------------------------------------------------- */
    protected function register_content_controls() {

        // ==========================================
        // SECTION: Layout & Presets
        // ==========================================
        $this->start_controls_section(
            'ua_account_section_general',
            [
                'label' => esc_html__( 'Layout & Presets', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'preset',
            [
                'label'   => esc_html__( 'Design Preset', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'modern',
                'options' => [
                    'modern'  => esc_html__( 'Modern Rounded', 'ultraaddons-elementor-lite' ),
                    'clean'   => esc_html__( 'Clean Minimal', 'ultraaddons-elementor-lite' ),
                    'classic' => esc_html__( 'Classic Card', 'ultraaddons-elementor-lite' ),
                    'luxury'  => esc_html__( 'Luxury Accent', 'ultraaddons-elementor-lite' ),
                ],
                'render_type'  => 'template',
                'prefix_class' => 'ua-account-preset-',
            ]
        );

        $this->add_control(
            'account_layout',
            [
                'label'   => esc_html__( 'Dashboard Layout', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'vertical-left',
                'options' => [
                    'vertical-left'  => esc_html__( 'Vertical Left Sidebar', 'ultraaddons-elementor-lite' ),
                    'vertical-right' => esc_html__( 'Vertical Right Sidebar', 'ultraaddons-elementor-lite' ),
                    'horizontal'     => esc_html__( 'Horizontal Top Bar', 'ultraaddons-elementor-lite' ),
                    'grid'           => esc_html__( 'App Grid Cards', 'ultraaddons-elementor-lite' ),
                ],
                'render_type'  => 'template',
                'prefix_class' => 'ua-account-layout-',
            ]
        );

        $this->add_responsive_control(
            'nav_col_width',
            [
                'label'     => esc_html__( 'Navigation Column Width', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units'=> [ '%', 'px' ],
                'range'     => [
                    '%'  => [ 'min' => 18, 'max' => 45 ],
                    'px' => [ 'min' => 200, 'max' => 450 ],
                ],
                'default'   => [
                    'unit' => '%',
                    'size' => 28,
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--ua-account-nav-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'account_layout' => [ 'vertical-left', 'vertical-right' ],
                ],
            ]
        );

        $this->add_responsive_control(
            'grid_gap',
            [
                'label'     => esc_html__( 'Grid / Section Gap', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units'=> [ 'px' ],
                'range'     => [
                    'px' => [ 'min' => 10, 'max' => 60 ],
                ],
                'default'   => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--ua-account-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'editor_preview_state',
            [
                'label'       => esc_html__( 'Editor Live Preview Mode', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'logged_in',
                'options'     => [
                    'logged_in'  => esc_html__( 'Preview as Logged-In User', 'ultraaddons-elementor-lite' ),
                    'logged_out' => esc_html__( 'Preview as Guest (Logged-Out)', 'ultraaddons-elementor-lite' ),
                ],
                'description' => esc_html__( 'Switch between Logged-In and Logged-Out preview inside Elementor to style both states easily.', 'ultraaddons-elementor-lite' ),
                'render_type' => 'template',
            ]
        );

        $this->end_controls_section();

        // ==========================================
        // SECTION: Profile Identity Banner
        // ==========================================
        $this->start_controls_section(
            'ua_account_section_profile',
            [
                'label' => esc_html__( 'Profile Header & Banner', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_profile_header',
            [
                'label'        => esc_html__( 'Show Profile Banner', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'avatar_shape',
            [
                'label'     => esc_html__( 'Avatar Shape', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'circle',
                'options'   => [
                    'circle'  => esc_html__( 'Circle', 'ultraaddons-elementor-lite' ),
                    'rounded' => esc_html__( 'Rounded Square', 'ultraaddons-elementor-lite' ),
                    'square'  => esc_html__( 'Square', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'show_profile_header' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'avatar_size',
            [
                'label'     => esc_html__( 'Avatar Size', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units'=> [ 'px' ],
                'range'     => [
                    'px' => [ 'min' => 48, 'max' => 140 ],
                ],
                'default'   => [
                    'unit' => 'px',
                    'size' => 74,
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--ua-account-avatar-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_profile_header' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'greeting_prefix',
            [
                'label'     => esc_html__( 'Greeting Prefix', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Hello,', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'show_profile_header' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_user_badge',
            [
                'label'        => esc_html__( 'Show Role Badge', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'show_profile_header' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_member_since',
            [
                'label'        => esc_html__( 'Show Member Since Date', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'show_profile_header' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_quick_stats',
            [
                'label'        => esc_html__( 'Show Quick Stats Cards', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'separator'    => 'before',
                'condition'    => [
                    'show_profile_header' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'stat_orders',
            [
                'label'        => esc_html__( 'Show Total Orders', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
                'condition'    => [
                    'show_profile_header' => 'yes',
                    'show_quick_stats'    => 'yes',
                ],
            ]
        );

        $this->add_control(
            'stat_pending',
            [
                'label'        => esc_html__( 'Show Processing Orders', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
                'condition'    => [
                    'show_profile_header' => 'yes',
                    'show_quick_stats'    => 'yes',
                ],
            ]
        );

        $this->add_control(
            'stat_downloads',
            [
                'label'        => esc_html__( 'Show Downloads Count', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
                'condition'    => [
                    'show_profile_header' => 'yes',
                    'show_quick_stats'    => 'yes',
                ],
            ]
        );

        $this->add_control(
            'stat_addresses',
            [
                'label'        => esc_html__( 'Show Saved Addresses', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
                'condition'    => [
                    'show_profile_header' => 'yes',
                    'show_quick_stats'    => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // ==========================================
        // SECTION: Navigation Tabs & Endpoints
        // ==========================================
        $this->start_controls_section(
            'ua_account_section_tabs',
            [
                'label' => esc_html__( 'Navigation Tabs & Endpoints', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'tab_title',
            [
                'label'       => esc_html__( 'Tab Title', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Tab Title', 'ultraaddons-elementor-lite' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'tab_icon',
            [
                'label'   => esc_html__( 'Tab Icon', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fas fa-th-large',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $repeater->add_control(
            'tab_type',
            [
                'label'   => esc_html__( 'Tab Type', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'native',
                'options' => [
                    'native'   => esc_html__( 'WooCommerce Native Endpoint', 'ultraaddons-elementor-lite' ),
                    'custom'   => esc_html__( 'Custom Content / Shortcode', 'ultraaddons-elementor-lite' ),
                    'template' => esc_html__( 'Elementor Saved Template', 'ultraaddons-elementor-lite' ),
                    'link'     => esc_html__( 'External Link', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $repeater->add_control(
            'native_endpoint',
            [
                'label'     => esc_html__( 'Select Native Endpoint', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'dashboard',
                'options'   => [
                    'dashboard'       => esc_html__( 'Dashboard', 'ultraaddons-elementor-lite' ),
                    'orders'          => esc_html__( 'Orders', 'ultraaddons-elementor-lite' ),
                    'downloads'       => esc_html__( 'Downloads', 'ultraaddons-elementor-lite' ),
                    'edit-address'    => esc_html__( 'Addresses', 'ultraaddons-elementor-lite' ),
                    'edit-account'    => esc_html__( 'Account Details', 'ultraaddons-elementor-lite' ),
                    'customer-logout' => esc_html__( 'Logout', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'tab_type' => 'native',
                ],
            ]
        );

        $repeater->add_control(
            'custom_content',
            [
                'label'       => esc_html__( 'Custom Content / Shortcode', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::WYSIWYG,
                'default'     => esc_html__( 'Insert any text, HTML, or shortcode here.', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'tab_type' => 'custom',
                ],
            ]
        );

        $repeater->add_control(
            'template_id',
            [
                'label'     => esc_html__( 'Saved Template', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => self::get_elementor_templates(),
                'condition' => [
                    'tab_type' => 'template',
                ],
            ]
        );

        $repeater->add_control(
            'custom_link',
            [
                'label'         => esc_html__( 'Custom Link URL', 'ultraaddons-elementor-lite' ),
                'type'          => Controls_Manager::URL,
                'placeholder'   => 'https://your-domain.com/support',
                'show_external' => true,
                'default'       => [
                    'url'         => '',
                    'is_external' => false,
                    'nofollow'    => false,
                ],
                'condition'     => [
                    'tab_type' => 'link',
                ],
            ]
        );

        $repeater->add_control(
            'hide_tab',
            [
                'label'        => esc_html__( 'Hide this Tab', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $this->add_control(
            'account_tabs',
            [
                'label'       => esc_html__( 'Tabs Items', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ tab_title }}}',
                'default'     => [
                    [
                        'tab_title'       => esc_html__( 'Dashboard', 'ultraaddons-elementor-lite' ),
                        'tab_type'        => 'native',
                        'native_endpoint' => 'dashboard',
                        'tab_icon'        => [ 'value' => 'fas fa-th-large', 'library' => 'fa-solid' ],
                    ],
                    [
                        'tab_title'       => esc_html__( 'Orders', 'ultraaddons-elementor-lite' ),
                        'tab_type'        => 'native',
                        'native_endpoint' => 'orders',
                        'tab_icon'        => [ 'value' => 'fas fa-shopping-bag', 'library' => 'fa-solid' ],
                    ],
                    [
                        'tab_title'       => esc_html__( 'Downloads', 'ultraaddons-elementor-lite' ),
                        'tab_type'        => 'native',
                        'native_endpoint' => 'downloads',
                        'tab_icon'        => [ 'value' => 'fas fa-download', 'library' => 'fa-solid' ],
                    ],
                    [
                        'tab_title'       => esc_html__( 'Addresses', 'ultraaddons-elementor-lite' ),
                        'tab_type'        => 'native',
                        'native_endpoint' => 'edit-address',
                        'tab_icon'        => [ 'value' => 'fas fa-map-marker-alt', 'library' => 'fa-solid' ],
                    ],
                    [
                        'tab_title'       => esc_html__( 'Account Details', 'ultraaddons-elementor-lite' ),
                        'tab_type'        => 'native',
                        'native_endpoint' => 'edit-account',
                        'tab_icon'        => [ 'value' => 'fas fa-user-cog', 'library' => 'fa-solid' ],
                    ],
                    [
                        'tab_title'       => esc_html__( 'Log Out', 'ultraaddons-elementor-lite' ),
                        'tab_type'        => 'native',
                        'native_endpoint' => 'customer-logout',
                        'tab_icon'        => [ 'value' => 'fas fa-sign-out-alt', 'library' => 'fa-solid' ],
                    ],
                ],
            ]
        );

        $this->end_controls_section();

        // ==========================================
        // SECTION: Logged-Out Settings
        // ==========================================
        $this->start_controls_section(
            'ua_account_section_logged_out',
            [
                'label' => esc_html__( 'Logged-Out Experience', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'logged_out_layout',
            [
                'label'   => esc_html__( 'Card Layout', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'tabs',
                'options' => [
                    'tabs'         => esc_html__( 'Modern Dual Switcher Tabs', 'ultraaddons-elementor-lite' ),
                    'side-by-side' => esc_html__( 'Two Columns (Side-by-Side)', 'ultraaddons-elementor-lite' ),
                    'login-only'   => esc_html__( 'Login Form Only', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'login_title',
            [
                'label'   => esc_html__( 'Login Form Title', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Welcome Back', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'login_subtitle',
            [
                'label'   => esc_html__( 'Login Subtitle', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Sign in to access your orders and profile.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'register_title',
            [
                'label'     => esc_html__( 'Register Form Title', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Create An Account', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'logged_out_layout!' => 'login-only',
                ],
            ]
        );

        $this->add_control(
            'register_subtitle',
            [
                'label'     => esc_html__( 'Register Subtitle', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Register today for fast checkout and order tracking.', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'logged_out_layout!' => 'login-only',
                ],
            ]
        );

        $this->add_control(
            'password_toggle',
            [
                'label'        => esc_html__( 'Password Show/Hide Toggle', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    /* -------------------------------------------------------------------------
     * STYLE CONTROLS
     * ---------------------------------------------------------------------- */
    protected function register_style_controls() {

        // ==========================================
        // STYLE: Profile Header & Banner
        // ==========================================
        $this->start_controls_section(
            'ua_style_section_profile',
            [
                'label'     => esc_html__( 'Profile Header Banner', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_profile_header' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'profile_bg',
                'label'    => esc_html__( 'Banner Background', 'ultraaddons-elementor-lite' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-account-profile-banner',
            ]
        );

        $this->add_responsive_control(
            'profile_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-account-profile-banner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'profile_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-account-profile-banner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'profile_shadow',
                'selector' => '{{WRAPPER}} .ua-account-profile-banner',
            ]
        );

        $this->add_control(
            'profile_greeting_heading',
            [
                'label'     => esc_html__( 'Greeting & Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'profile_greeting_color',
            [
                'label'     => esc_html__( 'Greeting Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-profile-greeting' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'profile_greeting_typo',
                'selector' => '{{WRAPPER}} .ua-profile-greeting',
            ]
        );

        $this->add_control(
            'profile_meta_color',
            [
                'label'     => esc_html__( 'Meta / Subtitle Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-profile-meta' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'profile_badge_heading',
            [
                'label'     => esc_html__( 'Role Badge', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_user_badge' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'badge_bg_color',
            [
                'label'     => esc_html__( 'Badge Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-profile-badge' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'show_user_badge' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'badge_text_color',
            [
                'label'     => esc_html__( 'Badge Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-profile-badge' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_user_badge' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'stats_card_heading',
            [
                'label'     => esc_html__( 'Quick Stats Cards', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_quick_stats' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'stats_card_bg',
            [
                'label'     => esc_html__( 'Stats Card Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-stat-card' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'show_quick_stats' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'stats_number_color',
            [
                'label'     => esc_html__( 'Number Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-stat-number' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_quick_stats' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'stats_label_color',
            [
                'label'     => esc_html__( 'Label Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-stat-label' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_quick_stats' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // ==========================================
        // STYLE: Navigation Tabs
        // ==========================================
        $this->start_controls_section(
            'ua_style_section_navigation',
            [
                'label' => esc_html__( 'Navigation Tabs', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'nav_bg_color',
            [
                'label'     => esc_html__( 'Nav Box Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-account-nav-wrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'nav_padding',
            [
                'label'      => esc_html__( 'Nav Box Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-account-nav-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'nav_box_border',
                'selector' => '{{WRAPPER}} .ua-account-nav-wrap',
            ]
        );

        $this->add_responsive_control(
            'nav_radius',
            [
                'label'      => esc_html__( 'Nav Box Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-account-nav-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'tab_item_typo',
                'selector' => '{{WRAPPER}} .ua-account-nav-item a',
            ]
        );

        $this->add_responsive_control(
            'tab_item_padding',
            [
                'label'      => esc_html__( 'Tab Item Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-account-nav-item a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tab_item_spacing',
            [
                'label'     => esc_html__( 'Space Between Tabs', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [ 'min' => 0, 'max' => 30 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-account-nav' => 'gap: {{SIZE}}px;',
                ],
            ]
        );

        $this->start_controls_tabs( 'ua_tabs_nav_states' );

        // Normal State
        $this->start_controls_tab(
            'ua_tab_nav_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'tab_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-account-nav-item a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tab_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-account-nav-item a .ua-tab-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tab_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-account-nav-item a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover State
        $this->start_controls_tab(
            'ua_tab_nav_hover',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'tab_hover_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-account-nav-item a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tab_hover_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-account-nav-item a:hover .ua-tab-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tab_hover_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-account-nav-item a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Active State
        $this->start_controls_tab(
            'ua_tab_nav_active',
            [ 'label' => esc_html__( 'Active', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'tab_active_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-account-nav-item.is-active a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tab_active_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-account-nav-item.is-active a .ua-tab-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tab_active_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-account-nav-item.is-active a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tab_active_indicator_color',
            [
                'label'     => esc_html__( 'Indicator Bar Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-account-nav-item.is-active a::before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // ==========================================
        // STYLE: Content Card / Area
        // ==========================================
        $this->start_controls_section(
            'ua_style_section_content',
            [
                'label' => esc_html__( 'Content Container', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'content_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-account-content-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-account-content-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'content_border',
                'selector' => '{{WRAPPER}} .ua-account-content-card',
            ]
        );

        $this->add_responsive_control(
            'content_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-account-content-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'content_shadow',
                'selector' => '{{WRAPPER}} .ua-account-content-card',
            ]
        );

        $this->end_controls_section();

        // ==========================================
        // STYLE: Tables & Status Badges
        // ==========================================
        $this->start_controls_section(
            'ua_style_section_tables',
            [
                'label' => esc_html__( 'Orders & Tables', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'table_header_bg',
            [
                'label'     => esc_html__( 'Table Header Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-orders-table th, {{WRAPPER}} .woocommerce-table th, {{WRAPPER}} .ua-mock-table th' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'table_header_color',
            [
                'label'     => esc_html__( 'Table Header Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-orders-table th, {{WRAPPER}} .woocommerce-table th, {{WRAPPER}} .ua-mock-table th' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'table_header_typo',
                'selector' => '{{WRAPPER}} .woocommerce-orders-table th, {{WRAPPER}} .woocommerce-table th, {{WRAPPER}} .ua-mock-table th',
            ]
        );

        $this->add_control(
            'table_row_border_color',
            [
                'label'     => esc_html__( 'Row Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-orders-table td, {{WRAPPER}} .woocommerce-table td, {{WRAPPER}} .ua-mock-table td' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'status_badges_heading',
            [
                'label'     => esc_html__( 'Status Badges Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'badge_completed_color',
            [
                'label'     => esc_html__( 'Completed / Success Badge', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-status-completed, {{WRAPPER}} .order-status.status-completed' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_processing_color',
            [
                'label'     => esc_html__( 'Processing Badge', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-status-processing, {{WRAPPER}} .order-status.status-processing' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_on_hold_color',
            [
                'label'     => esc_html__( 'On-Hold Badge', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-status-on-hold, {{WRAPPER}} .order-status.status-on-hold' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'table_action_button_heading',
            [
                'label'     => esc_html__( 'Action Buttons (View / Pay)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'action_btn_bg',
            [
                'label'     => esc_html__( 'Button Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-orders-table .woocommerce-button, {{WRAPPER}} .ua-mock-btn' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'action_btn_color',
            [
                'label'     => esc_html__( 'Button Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-orders-table .woocommerce-button, {{WRAPPER}} .ua-mock-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ==========================================
        // STYLE: Forms & Inputs
        // ==========================================
        $this->start_controls_section(
            'ua_style_section_forms',
            [
                'label' => esc_html__( 'Forms & Inputs', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'form_input_bg',
            [
                'label'     => esc_html__( 'Input Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .input-text, {{WRAPPER}} input[type="text"], {{WRAPPER}} input[type="password"], {{WRAPPER}} input[type="email"]' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'form_input_border',
            [
                'label'     => esc_html__( 'Input Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .input-text, {{WRAPPER}} input[type="text"], {{WRAPPER}} input[type="password"], {{WRAPPER}} input[type="email"]' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'form_input_focus_border',
            [
                'label'     => esc_html__( 'Input Focus Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .input-text:focus, {{WRAPPER}} input[type="text"]:focus, {{WRAPPER}} input[type="password"]:focus, {{WRAPPER}} input[type="email"]:focus' => 'border-color: {{VALUE}}; box-shadow: 0 0 0 3px {{VALUE}}26;',
                ],
            ]
        );

        $this->add_responsive_control(
            'form_input_radius',
            [
                'label'      => esc_html__( 'Input Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .input-text, {{WRAPPER}} input[type="text"], {{WRAPPER}} input[type="password"], {{WRAPPER}} input[type="email"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'submit_btn_heading',
            [
                'label'     => esc_html__( 'Submit Buttons', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'submit_btn_bg',
            [
                'label'     => esc_html__( 'Primary Button Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-button.button, {{WRAPPER}} .ua-account-submit-btn, {{WRAPPER}} .woocommerce-EditAccountForm button.button' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'submit_btn_color',
            [
                'label'     => esc_html__( 'Primary Button Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-button.button, {{WRAPPER}} .ua-account-submit-btn, {{WRAPPER}} .woocommerce-EditAccountForm button.button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'submit_btn_radius',
            [
                'label'      => esc_html__( 'Button Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .woocommerce-button.button, {{WRAPPER}} .ua-account-submit-btn, {{WRAPPER}} .woocommerce-EditAccountForm button.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ==========================================
        // STYLE: Logged-Out Card
        // ==========================================
        $this->start_controls_section(
            'ua_style_section_logged_out',
            [
                'label' => esc_html__( 'Logged-Out Card', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'logged_out_card_bg',
            [
                'label'     => esc_html__( 'Card Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-account-logged-out-box' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'logged_out_card_padding',
            [
                'label'      => esc_html__( 'Card Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-account-logged-out-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'logged_out_card_border',
                'selector' => '{{WRAPPER}} .ua-account-logged-out-box',
            ]
        );

        $this->add_responsive_control(
            'logged_out_card_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-account-logged-out-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'logged_out_card_shadow',
                'selector' => '{{WRAPPER}} .ua-account-logged-out-box',
            ]
        );

        $this->end_controls_section();
    }

    /* -------------------------------------------------------------------------
     * RENDER ENGINE
     * ---------------------------------------------------------------------- */

    /**
     * Main Render Method
     */
    protected function render() {
        if ( ! function_exists( 'WC' ) ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo '<div class="ua-alert ua-alert-warning">' . esc_html__( 'WooCommerce is required for the Woo Account widget.', 'ultraaddons-elementor-lite' ) . '</div>';
            }
            return;
        }

        $settings  = $this->get_settings_for_display();
        $is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();

        // Save active settings
        self::$active_settings = $settings;

        // In Elementor Editor Mode: Check user preview toggle
        if ( $is_editor ) {
            $preview_state = $settings['editor_preview_state'] ?? 'logged_in';
            if ( 'logged_out' === $preview_state ) {
                $this->render_logged_out_view( $settings, true );
                return;
            } else {
                $this->render_editor_mock_preview( $settings );
                return;
            }
        }

        // Live Frontend Rendering:
        if ( ! is_user_logged_in() ) {
            $this->render_logged_out_view( $settings, false );
        } else {
            $this->render_logged_in_dashboard( $settings );
        }
    }

    /**
     * Render Profile Header Banner
     */
    protected function render_profile_header( $settings, $user = null ) {
        if ( ( $settings['show_profile_header'] ?? 'yes' ) !== 'yes' ) {
            return;
        }

        if ( ! $user ) {
            $user = wp_get_current_user();
        }

        $user_id       = $user->ID ?? 0;
        $display_name  = ! empty( $user->display_name ) ? $user->display_name : esc_html__( 'Customer', 'ultraaddons-elementor-lite' );
        $first_name    = ! empty( $user->first_name ) ? $user->first_name : $display_name;
        $greeting_text = ( $settings['greeting_prefix'] ?? esc_html__( 'Hello,', 'ultraaddons-elementor-lite' ) ) . ' ' . esc_html( $first_name );
        $avatar_shape  = esc_attr( $settings['avatar_shape'] ?? 'circle' );

        // Member since string
        $registered_date = ! empty( $user->user_registered ) ? date_i18n( get_option( 'date_format' ), strtotime( $user->user_registered ) ) : date_i18n( get_option( 'date_format' ) );

        // Calculate Stats
        $order_count = ( $user_id > 0 && function_exists( 'wc_get_customer_order_count' ) ) ? wc_get_customer_order_count( $user_id ) : 0;
        
        // Processing / Pending orders
        $pending_count = 0;
        if ( $user_id > 0 && function_exists( 'wc_get_orders' ) ) {
            $pending_orders = wc_get_orders( [
                'customer_id' => $user_id,
                'status'      => [ 'wc-processing', 'wc-on-hold' ],
                'return'      => 'ids',
            ] );
            $pending_count = is_array( $pending_orders ) ? count( $pending_orders ) : 0;
        }

        // Available downloads
        $download_count = 0;
        if ( $user_id > 0 && function_exists( 'wc_get_customer_available_downloads' ) ) {
            $downloads = wc_get_customer_available_downloads( $user_id );
            $download_count = is_array( $downloads ) ? count( $downloads ) : 0;
        }

        // Saved addresses count (Billing and/or Shipping)
        $address_count = 0;
        if ( $user_id > 0 ) {
            if ( get_user_meta( $user_id, 'billing_address_1', true ) ) {
                $address_count++;
            }
            if ( get_user_meta( $user_id, 'shipping_address_1', true ) ) {
                $address_count++;
            }
        }
        ?>
        <div class="ua-account-profile-banner">
            <div class="ua-profile-main">
                <div class="ua-profile-avatar ua-avatar-shape-<?php echo esc_attr( $avatar_shape ); ?>">
                    <?php
                    if ( $user_id > 0 ) {
                        echo get_avatar( $user_id, 96 );
                    } else {
                        echo '<div class="ua-default-avatar"><i class="fas fa-user"></i></div>';
                    }
                    ?>
                </div>
                <div class="ua-profile-info">
                    <h2 class="ua-profile-greeting"><?php echo esc_html( $greeting_text ); ?></h2>
                    <div class="ua-profile-meta">
                        <?php if ( ( $settings['show_user_badge'] ?? 'yes' ) === 'yes' ) : ?>
                            <span class="ua-profile-badge"><?php esc_html_e( 'Customer', 'ultraaddons-elementor-lite' ); ?></span>
                        <?php endif; ?>

                        <?php if ( ( $settings['show_member_since'] ?? 'yes' ) === 'yes' ) : ?>
                            <span class="ua-member-since">
                                <i class="far fa-calendar-alt"></i> <?php printf( esc_html__( 'Member since %s', 'ultraaddons-elementor-lite' ), esc_html( $registered_date ) ); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if ( ( $settings['show_quick_stats'] ?? 'yes' ) === 'yes' ) : ?>
                <div class="ua-profile-stats">
                    <?php if ( ( $settings['stat_orders'] ?? 'yes' ) === 'yes' ) : ?>
                        <div class="ua-stat-card">
                            <span class="ua-stat-number"><?php echo esc_html( $order_count ); ?></span>
                            <span class="ua-stat-label"><?php esc_html_e( 'Total Orders', 'ultraaddons-elementor-lite' ); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ( ( $settings['stat_pending'] ?? 'yes' ) === 'yes' ) : ?>
                        <div class="ua-stat-card">
                            <span class="ua-stat-number"><?php echo esc_html( $pending_count ); ?></span>
                            <span class="ua-stat-label"><?php esc_html_e( 'Processing', 'ultraaddons-elementor-lite' ); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ( ( $settings['stat_downloads'] ?? 'yes' ) === 'yes' ) : ?>
                        <div class="ua-stat-card">
                            <span class="ua-stat-number"><?php echo esc_html( $download_count ); ?></span>
                            <span class="ua-stat-label"><?php esc_html_e( 'Downloads', 'ultraaddons-elementor-lite' ); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ( ( $settings['stat_addresses'] ?? 'yes' ) === 'yes' ) : ?>
                        <div class="ua-stat-card">
                            <span class="ua-stat-number"><?php echo esc_html( $address_count ); ?></span>
                            <span class="ua-stat-label"><?php esc_html_e( 'Addresses', 'ultraaddons-elementor-lite' ); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Render Logged-In Customer Dashboard (Live)
     */
    protected function render_logged_in_dashboard( $settings ) {
        $preset       = esc_attr( $settings['preset'] ?? 'modern' );
        $layout       = esc_attr( $settings['account_layout'] ?? 'vertical-left' );
        $current_user = wp_get_current_user();
        $tabs         = ! empty( $settings['account_tabs'] ) ? $settings['account_tabs'] : [];

        // Determine current endpoint
        global $wp;
        $current_endpoint = 'dashboard';
        if ( function_exists( 'WC' ) && isset( WC()->query->query_vars ) ) {
            foreach ( WC()->query->query_vars as $key => $value ) {
                if ( isset( $wp->query_vars[ $key ] ) ) {
                    $current_endpoint = $key;
                    break;
                }
            }
        }

        $wrapper_classes = [
            'ua-woo-account',
            'ua-account-preset-' . $preset,
            'ua-account-layout-' . $layout,
            'ua-account-logged-in',
        ];
        ?>
        <div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>">
            
            <?php $this->render_profile_header( $settings, $current_user ); ?>

            <div class="ua-account-main-grid">
                
                <!-- Navigation Column -->
                <div class="ua-account-nav-wrap">
                    <div class="ua-mobile-nav-toggle">
                        <span><i class="fas fa-bars"></i> <?php esc_html_e( 'Account Navigation', 'ultraaddons-elementor-lite' ); ?></span>
                        <i class="fas fa-chevron-down"></i>
                    </div>

                    <ul class="ua-account-nav">
                        <?php
                        foreach ( $tabs as $index => $tab ) {
                            if ( ( $tab['hide_tab'] ?? '' ) === 'yes' ) {
                                continue;
                            }

                            $tab_type        = $tab['tab_type'] ?? 'native';
                            $native_endpoint = $tab['native_endpoint'] ?? 'dashboard';
                            $title           = ! empty( $tab['tab_title'] ) ? $tab['tab_title'] : '';
                            $icon            = $tab['tab_icon'] ?? [];
                            $url             = '#';
                            $is_active       = false;

                            if ( 'native' === $tab_type ) {
                                $url = function_exists( 'wc_get_account_endpoint_url' ) ? wc_get_account_endpoint_url( $native_endpoint ) : '#';
                                if ( 'dashboard' === $native_endpoint ) {
                                    $is_active = ( 'dashboard' === $current_endpoint );
                                } else {
                                    $is_active = ( $current_endpoint === $native_endpoint );
                                }
                            } elseif ( 'link' === $tab_type ) {
                                $url = ! empty( $tab['custom_link']['url'] ) ? esc_url( $tab['custom_link']['url'] ) : '#';
                            } else {
                                $url = '#ua-custom-tab-' . $index;
                            }

                            $item_classes = [ 'ua-account-nav-item' ];
                            if ( $is_active ) {
                                $item_classes[] = 'is-active';
                            }
                            ?>
                            <li class="<?php echo esc_attr( implode( ' ', $item_classes ) ); ?>" data-tab="<?php echo esc_attr( $tab_type === 'native' ? $native_endpoint : 'custom-' . $index ); ?>">
                                <a href="<?php echo esc_url( $url ); ?>">
                                    <?php if ( ! empty( $icon['value'] ) ) : ?>
                                        <span class="ua-tab-icon">
                                            <?php Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] ); ?>
                                        </span>
                                    <?php endif; ?>
                                    <span class="ua-tab-text"><?php echo esc_html( $title ); ?></span>
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>

                <!-- Content Column -->
                <div class="ua-account-content-wrap">
                    <div class="ua-account-content-card">
                        <?php
                        // Check if viewing a custom repeater tab via URL hash or standard render
                        $custom_rendered = false;

                        // Standard WooCommerce content hook
                        if ( ! $custom_rendered ) {
                            do_action( 'woocommerce_account_content' );
                        }
                        ?>
                    </div>
                </div>

            </div>
        </div>
        <?php
    }

    /**
     * Render Editor Mock Preview
     */
    protected function render_editor_mock_preview( $settings ) {
        $preset = esc_attr( $settings['preset'] ?? 'modern' );
        $layout = esc_attr( $settings['account_layout'] ?? 'vertical-left' );
        $tabs   = ! empty( $settings['account_tabs'] ) ? $settings['account_tabs'] : [];

        // Mock User
        $mock_user = (object) [
            'ID'              => 1,
            'display_name'    => 'Sarah Connor',
            'first_name'      => 'Sarah',
            'user_registered' => '2023-04-15 10:00:00',
        ];

        $wrapper_classes = [
            'ua-woo-account',
            'ua-account-preset-' . $preset,
            'ua-account-layout-' . $layout,
            'ua-account-logged-in',
            'ua-editor-preview',
        ];
        ?>
        <div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>">
            <div class="ua-editor-preview-notice">
                <span>✦ <?php esc_html_e( 'Elementor Editor Preview Mode: Displaying full Woo Account dashboard with sample data & live styling.', 'ultraaddons-elementor-lite' ); ?></span>
            </div>

            <!-- Profile Header -->
            <?php $this->render_profile_header( $settings, $mock_user ); ?>

            <div class="ua-account-main-grid">
                
                <!-- Nav Sidebar -->
                <div class="ua-account-nav-wrap">
                    <ul class="ua-account-nav">
                        <?php
                        foreach ( $tabs as $index => $tab ) {
                            if ( ( $tab['hide_tab'] ?? '' ) === 'yes' ) {
                                continue;
                            }
                            $title     = ! empty( $tab['tab_title'] ) ? $tab['tab_title'] : 'Tab';
                            $icon      = $tab['tab_icon'] ?? [];
                            $is_active = ( 0 === $index );
                            ?>
                            <li class="ua-account-nav-item <?php echo $is_active ? 'is-active' : ''; ?>">
                                <a href="#">
                                    <?php if ( ! empty( $icon['value'] ) ) : ?>
                                        <span class="ua-tab-icon">
                                            <?php Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] ); ?>
                                        </span>
                                    <?php endif; ?>
                                    <span class="ua-tab-text"><?php echo esc_html( $title ); ?></span>
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>

                <!-- Mock Content Area -->
                <div class="ua-account-content-wrap">
                    <div class="ua-account-content-card">
                        
                        <div class="ua-mock-dashboard-welcome">
                            <h3><?php esc_html_e( 'Recent Orders', 'ultraaddons-elementor-lite' ); ?></h3>
                            <p><?php esc_html_e( 'Here is a sample overview of your account activity, styled live with Elementor controls.', 'ultraaddons-elementor-lite' ); ?></p>
                        </div>

                        <!-- Sample Orders Table -->
                        <div class="ua-table-responsive">
                            <table class="woocommerce-orders-table ua-mock-table">
                                <thead>
                                    <tr>
                                        <th><?php esc_html_e( 'Order', 'ultraaddons-elementor-lite' ); ?></th>
                                        <th><?php esc_html_e( 'Date', 'ultraaddons-elementor-lite' ); ?></th>
                                        <th><?php esc_html_e( 'Status', 'ultraaddons-elementor-lite' ); ?></th>
                                        <th><?php esc_html_e( 'Total', 'ultraaddons-elementor-lite' ); ?></th>
                                        <th><?php esc_html_e( 'Actions', 'ultraaddons-elementor-lite' ); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>#1048</strong></td>
                                        <td>Oct 24, 2026</td>
                                        <td><span class="ua-status-badge ua-status-completed"><?php esc_html_e( 'Completed', 'ultraaddons-elementor-lite' ); ?></span></td>
                                        <td>$149.00 for 2 items</td>
                                        <td><a href="#" class="woocommerce-button button ua-mock-btn"><?php esc_html_e( 'View', 'ultraaddons-elementor-lite' ); ?></a></td>
                                    </tr>
                                    <tr>
                                        <td><strong>#1049</strong></td>
                                        <td>Oct 27, 2026</td>
                                        <td><span class="ua-status-badge ua-status-processing"><?php esc_html_e( 'Processing', 'ultraaddons-elementor-lite' ); ?></span></td>
                                        <td>$65.50 for 1 item</td>
                                        <td><a href="#" class="woocommerce-button button ua-mock-btn"><?php esc_html_e( 'View', 'ultraaddons-elementor-lite' ); ?></a></td>
                                    </tr>
                                    <tr>
                                        <td><strong>#1050</strong></td>
                                        <td>Oct 28, 2026</td>
                                        <td><span class="ua-status-badge ua-status-on-hold"><?php esc_html_e( 'On Hold', 'ultraaddons-elementor-lite' ); ?></span></td>
                                        <td>$210.00 for 3 items</td>
                                        <td><a href="#" class="woocommerce-button button ua-mock-btn"><?php esc_html_e( 'View', 'ultraaddons-elementor-lite' ); ?></a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Sample Address Cards -->
                        <div class="ua-mock-addresses-preview">
                            <div class="ua-mock-address-card">
                                <h4><i class="fas fa-map-marker-alt"></i> <?php esc_html_e( 'Billing Address', 'ultraaddons-elementor-lite' ); ?></h4>
                                <address>
                                    Sarah Connor<br>
                                    742 Evergreen Terrace<br>
                                    Springfield, OR 97477<br>
                                    United States
                                </address>
                                <a href="#" class="ua-mock-link"><i class="fas fa-edit"></i> <?php esc_html_e( 'Edit Address', 'ultraaddons-elementor-lite' ); ?></a>
                            </div>

                            <div class="ua-mock-address-card">
                                <h4><i class="fas fa-truck"></i> <?php esc_html_e( 'Shipping Address', 'ultraaddons-elementor-lite' ); ?></h4>
                                <address>
                                    Sarah Connor<br>
                                    742 Evergreen Terrace<br>
                                    Springfield, OR 97477<br>
                                    United States
                                </address>
                                <a href="#" class="ua-mock-link"><i class="fas fa-edit"></i> <?php esc_html_e( 'Edit Address', 'ultraaddons-elementor-lite' ); ?></a>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <?php
    }

    /**
     * Render Logged-Out View (Login & Registration Form)
     */
    protected function render_logged_out_view( $settings, $is_editor = false ) {
        $preset            = esc_attr( $settings['preset'] ?? 'modern' );
        $logged_out_layout = esc_attr( $settings['logged_out_layout'] ?? 'tabs' );
        $enable_register   = ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) && ( 'login-only' !== $logged_out_layout );
        $has_pwd_toggle    = ( ( $settings['password_toggle'] ?? 'yes' ) === 'yes' );

        $wrapper_classes = [
            'ua-woo-account',
            'ua-account-preset-' . $preset,
            'ua-account-logged-out',
            'ua-layout-' . $logged_out_layout,
        ];
        ?>
        <div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>">
            
            <?php if ( $is_editor ) : ?>
                <div class="ua-editor-preview-notice">
                    <span>✦ <?php esc_html_e( 'Elementor Editor Preview Mode: Guest (Logged-Out) view.', 'ultraaddons-elementor-lite' ); ?></span>
                </div>
            <?php endif; ?>

            <div class="ua-account-logged-out-box">

                <?php if ( 'tabs' === $logged_out_layout && $enable_register ) : ?>
                    <!-- Tab Switcher -->
                    <div class="ua-auth-tabs">
                        <button type="button" class="ua-auth-tab is-active" data-auth-target="login">
                            <i class="fas fa-sign-in-alt"></i> <?php esc_html_e( 'Sign In', 'ultraaddons-elementor-lite' ); ?>
                        </button>
                        <button type="button" class="ua-auth-tab" data-auth-target="register">
                            <i class="fas fa-user-plus"></i> <?php esc_html_e( 'Register', 'ultraaddons-elementor-lite' ); ?>
                        </button>
                    </div>
                <?php endif; ?>

                <div class="ua-auth-forms-container <?php echo ( 'side-by-side' === $logged_out_layout && $enable_register ) ? 'ua-auth-split-grid' : ''; ?>">
                    
                    <!-- Login Form Box -->
                    <div class="ua-auth-pane ua-auth-login is-active">
                        <div class="ua-auth-header">
                            <h3><?php echo esc_html( $settings['login_title'] ?? esc_html__( 'Welcome Back', 'ultraaddons-elementor-lite' ) ); ?></h3>
                            <p><?php echo esc_html( $settings['login_subtitle'] ?? esc_html__( 'Sign in to access your orders and profile.', 'ultraaddons-elementor-lite' ) ); ?></p>
                        </div>

                        <?php do_action( 'woocommerce_before_customer_login_form' ); ?>

                        <form class="woocommerce-form woocommerce-form-login login ua-login-form" method="post">
                            <?php do_action( 'woocommerce_login_form_start' ); ?>

                            <div class="ua-form-group">
                                <label for="username"><?php esc_html_e( 'Username or email address', 'ultraaddons-elementor-lite' ); ?> <span class="required">*</span></label>
                                <div class="ua-input-wrap">
                                    <i class="fas fa-envelope ua-input-icon"></i>
                                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" placeholder="<?php esc_attr_e( 'yourname@email.com', 'ultraaddons-elementor-lite' ); ?>" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required />
                                </div>
                            </div>

                            <div class="ua-form-group">
                                <label for="password"><?php esc_html_e( 'Password', 'ultraaddons-elementor-lite' ); ?> <span class="required">*</span></label>
                                <div class="ua-input-wrap">
                                    <i class="fas fa-lock ua-input-icon"></i>
                                    <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" placeholder="••••••••" required />
                                    <?php if ( $has_pwd_toggle ) : ?>
                                        <button type="button" class="ua-pwd-toggle" title="<?php esc_attr_e( 'Toggle password visibility', 'ultraaddons-elementor-lite' ); ?>">
                                            <i class="far fa-eye"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <?php do_action( 'woocommerce_login_form' ); ?>

                            <div class="ua-form-actions-row">
                                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme ua-checkbox-label">
                                    <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
                                    <span><?php esc_html_e( 'Remember me', 'ultraaddons-elementor-lite' ); ?></span>
                                </label>
                                <a class="ua-lost-password-link" href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost password?', 'ultraaddons-elementor-lite' ); ?></a>
                            </div>

                            <button type="submit" class="woocommerce-button button woocommerce-form-login__submit ua-account-submit-btn" name="login" value="<?php esc_attr_e( 'Log in', 'ultraaddons-elementor-lite' ); ?>">
                                <?php esc_html_e( 'Sign In', 'ultraaddons-elementor-lite' ); ?> <i class="fas fa-arrow-right"></i>
                            </button>

                            <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
                            <?php do_action( 'woocommerce_login_form_end' ); ?>
                        </form>
                    </div>

                    <!-- Register Form Box (if enabled) -->
                    <?php if ( $enable_register ) : ?>
                        <div class="ua-auth-pane ua-auth-register <?php echo ( 'side-by-side' === $logged_out_layout ) ? 'is-active' : ''; ?>">
                            <div class="ua-auth-header">
                                <h3><?php echo esc_html( $settings['register_title'] ?? esc_html__( 'Create An Account', 'ultraaddons-elementor-lite' ) ); ?></h3>
                                <p><?php echo esc_html( $settings['register_subtitle'] ?? esc_html__( 'Register today for fast checkout and order tracking.', 'ultraaddons-elementor-lite' ) ); ?></p>
                            </div>

                            <form method="post" class="woocommerce-form woocommerce-form-register register ua-register-form" <?php do_action( 'woocommerce_register_form_tag' ); ?>>
                                <?php do_action( 'woocommerce_register_form_start' ); ?>

                                <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
                                    <div class="ua-form-group">
                                        <label for="reg_username"><?php esc_html_e( 'Username', 'ultraaddons-elementor-lite' ); ?> <span class="required">*</span></label>
                                        <div class="ua-input-wrap">
                                            <i class="fas fa-user ua-input-icon"></i>
                                            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required />
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="ua-form-group">
                                    <label for="reg_email"><?php esc_html_e( 'Email address', 'ultraaddons-elementor-lite' ); ?> <span class="required">*</span></label>
                                    <div class="ua-input-wrap">
                                        <i class="fas fa-envelope ua-input-icon"></i>
                                        <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" placeholder="<?php esc_attr_e( 'yourname@email.com', 'ultraaddons-elementor-lite' ); ?>" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" required />
                                    </div>
                                </div>

                                <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
                                    <div class="ua-form-group">
                                        <label for="reg_password"><?php esc_html_e( 'Password', 'ultraaddons-elementor-lite' ); ?> <span class="required">*</span></label>
                                        <div class="ua-input-wrap">
                                            <i class="fas fa-lock ua-input-icon"></i>
                                            <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" placeholder="••••••••" required />
                                            <?php if ( $has_pwd_toggle ) : ?>
                                                <button type="button" class="ua-pwd-toggle" title="<?php esc_attr_e( 'Toggle password visibility', 'ultraaddons-elementor-lite' ); ?>">
                                                    <i class="far fa-eye"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <p class="ua-pwd-generate-note">
                                        <i class="fas fa-info-circle"></i> <?php esc_html_e( 'A password will be sent to your email address.', 'ultraaddons-elementor-lite' ); ?>
                                    </p>
                                <?php endif; ?>

                                <?php do_action( 'woocommerce_register_form' ); ?>

                                <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit ua-account-submit-btn" name="register" value="<?php esc_attr_e( 'Register', 'ultraaddons-elementor-lite' ); ?>">
                                    <?php esc_html_e( 'Create Account', 'ultraaddons-elementor-lite' ); ?> <i class="fas fa-check"></i>
                                </button>

                                <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
                                <?php do_action( 'woocommerce_register_form_end' ); ?>
                            </form>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

            <?php do_action( 'woocommerce_after_customer_login_form' ); ?>
        </div>
        <?php
    }
}
