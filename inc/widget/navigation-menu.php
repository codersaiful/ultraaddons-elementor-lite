<?php
namespace UltraAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use UltraAddons\Core\Mega_Menu;
use Ultra_Nav_Walker;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once ULTRA_ADDONS_DIR . 'inc/core/mega-menu/class-ultra-nav-walker.php';

/**
 * UltraAddons Navigation Menu Widget
 *
 * A modern, zero-dependency, flexbox-powered navigation menu widget.
 * Features customizable hover pointer animations, multi-level dropdown submenus,
 * responsive collapsible dropdowns, and an off-canvas slide drawer.
 *
 * @package UltraAddons
 * @author Saiful Islam <codersaiful@gmail.com>
 * @version 2.0.0
 */
class Navigation_Menu extends Base {

    /**
     * Constructor: registers widget specific CSS and JS with cache-busting
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        $css_file = ULTRA_ADDONS_DIR . 'assets/css/widgets/navigation-menu.css';
        $css_ver  = file_exists( $css_file ) ? filemtime( $css_file ) : ULTRA_ADDONS_VERSION;

        wp_register_style(
            'ultraaddons-navigation-menu',
            ULTRA_ADDONS_ASSETS . 'css/widgets/navigation-menu.css',
            [],
            $css_ver,
            'all'
        );

        $js_file = ULTRA_ADDONS_DIR . 'assets/js/frontend-navigation-menu.js';
        $js_ver  = file_exists( $js_file ) ? filemtime( $js_file ) : ULTRA_ADDONS_VERSION;

        wp_register_script(
            'frontend-navigation-menu',
            ULTRA_ADDONS_ASSETS . 'js/frontend-navigation-menu.js',
            [ 'jquery' ],
            $js_ver,
            true
        );

        wp_localize_script(
            'frontend-navigation-menu',
            'ua_nav_params',
            [
                'ajax_url' => admin_url( 'admin-ajax.php' ),
            ]
        );
    }

    /**
     * Retrieve widget style dependencies
     *
     * @return array
     */
    public function get_style_depends() {
        return array_merge( parent::get_style_depends(), [ 'ultraaddons-navigation-menu' ] );
    }

    /**
     * Retrieve widget script dependencies
     *
     * @return array
     */
    public function get_script_depends() {
        return array_merge( parent::get_script_depends(), [ 'frontend-navigation-menu' ] );
    }

    /**
     * Search keywords for Elementor panel
     *
     * @return array
     */
    public function get_keywords() {
        return [
            'ultraaddons-elementor-lite',
            'ua',
            'nav',
            'menu',
            'navigation',
            'header',
            'navbar',
            'mega menu',
            'mobile menu',
            'drawer',
        ];
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        $this->register_content_controls();
        $this->register_pointer_controls();
        $this->register_submenu_controls();
        $this->register_mobile_controls();
        $this->register_style_controls();
    }

    /**
     * Helper: retrieve available WordPress navigation menus
     *
     * @return array
     */
    protected function get_available_menus() {
        $menus = wp_get_nav_menus();
        $options = [];
        if ( ! empty( $menus ) ) {
            foreach ( $menus as $menu ) {
                $options[ $menu->slug ] = $menu->name;
            }
        }
        return $options;
    }

    /**
     * Content Tab: Menu Selection, Layout & Alignment
     */
    protected function register_content_controls() {
        $this->start_controls_section(
            '_section_ua_nav_content',
            [
                'label' => esc_html__( 'Menu & Layout', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $menus = $this->get_available_menus();

        if ( ! empty( $menus ) ) {
            $this->add_control(
                'ua_nav_menu_slug',
                [
                    'label'        => esc_html__( 'Select Menu', 'ultraaddons-elementor-lite' ),
                    'type'         => Controls_Manager::SELECT,
                    'options'      => $menus,
                    'default'      => array_key_first( $menus ),
                    'save_default' => true,
                    'render_type'  => 'template',
                    'description'  => sprintf(
                        /* translators: %s: URL to Menus admin page */
                        __( 'Manage menus in <a href="%s" target="_blank">Appearance > Menus</a>.', 'ultraaddons-elementor-lite' ),
                        admin_url( 'nav-menus.php' )
                    ),
                ]
            );
        } else {
            $this->add_control(
                'ua_nav_no_menu',
                [
                    'type'            => Controls_Manager::RAW_HTML,
                    'raw'             => sprintf(
                        /* translators: %s: URL to create menu */
                        __( '<strong>No menus found!</strong> Please <a href="%s" target="_blank">create a menu</a> first.', 'ultraaddons-elementor-lite' ),
                        admin_url( 'nav-menus.php?action=edit&menu=0' )
                    ),
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                ]
            );
        }

        $this->add_control(
            'ua_nav_layout',
            [
                'label'       => esc_html__( 'Layout', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'horizontal',
                'render_type' => 'template',
                'options'     => [
                    'horizontal' => esc_html__( 'Horizontal', 'ultraaddons-elementor-lite' ),
                    'vertical'   => esc_html__( 'Vertical', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_nav_align',
            [
                'label'                => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
                'type'                 => Controls_Manager::CHOOSE,
                'default'              => 'left',
                'options'              => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Space Between', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-stretch',
                    ],
                ],
                'prefix_class'         => 'ua-nav-align%s-',
                'selectors_dictionary' => [
                    'left'          => 'flex-start',
                    'center'        => 'center',
                    'right'         => 'flex-end',
                    'justify'       => 'space-between',
                    'space-between' => 'space-between',
                ],
                'selectors'            => [
                    '{{WRAPPER}}'                                => 'width: 100%; flex-grow: 1;',
                    '{{WRAPPER}} .elementor-widget-container'    => 'width: 100%;',
                    '{{WRAPPER}} .ua-nav-menu-wrapper'           => 'width: 100%;',
                    '{{WRAPPER}} .ua-nav-menu-container'         => 'justify-content: {{VALUE}} !important; width: 100%;',
                    '{{WRAPPER}} .ua-desktop-nav'                => 'display: flex; justify-content: {{VALUE}} !important; width: 100%;',
                    '{{WRAPPER}} .ua-desktop-nav > .ua-nav-list' => 'justify-content: {{VALUE}} !important; width: 100%;',
                ],
            ]
        );

        $this->add_control(
            'ua_nav_sticky_offset',
            [
                'label'       => esc_html__( 'Sticky Header Offset (px)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 0,
                'min'         => 0,
                'max'         => 200,
                'step'        => 5,
                'description' => esc_html__( 'Offset compensation for smooth scrolling to anchor sections (#section) under a sticky header.', 'ultraaddons-elementor-lite' ),
                'separator'   => 'before',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content Tab: Hover Pointers & Animations
     */
    protected function register_pointer_controls() {
        $this->start_controls_section(
            '_section_ua_nav_pointers',
            [
                'label' => esc_html__( 'Hover Pointers & Effects', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ua_nav_pointer',
            [
                'label'       => esc_html__( 'Pointer Effect', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'underline',
                'render_type' => 'template',
                'options'     => [
                    'none'        => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                    'underline'   => esc_html__( 'Underline', 'ultraaddons-elementor-lite' ),
                    'overline'    => esc_html__( 'Overline', 'ultraaddons-elementor-lite' ),
                    'double-line' => esc_html__( 'Double Line', 'ultraaddons-elementor-lite' ),
                    'pill'        => esc_html__( 'Background Pill (Capsule)', 'ultraaddons-elementor-lite' ),
                    'framed'      => esc_html__( 'Framed Box', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'ua_nav_pointer_anim',
            [
                'label'       => esc_html__( 'Pointer Animation', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'fade',
                'render_type' => 'template',
                'options'     => [
                    'fade'  => esc_html__( 'Fade', 'ultraaddons-elementor-lite' ),
                    'slide' => esc_html__( 'Slide Horizontal', 'ultraaddons-elementor-lite' ),
                    'grow'  => esc_html__( 'Grow from Center', 'ultraaddons-elementor-lite' ),
                    'drop'  => esc_html__( 'Drop Vertical', 'ultraaddons-elementor-lite' ),
                ],
                'condition'   => [
                    'ua_nav_pointer!' => 'none',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content Tab: Submenu Settings
     */
    protected function register_submenu_controls() {
        $this->start_controls_section(
            '_section_ua_nav_submenu',
            [
                'label' => esc_html__( 'Submenu Settings', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ua_nav_submenu_trigger',
            [
                'label'       => esc_html__( 'Submenu Trigger', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'hover',
                'render_type' => 'template',
                'options'     => [
                    'hover' => esc_html__( 'Mouse Hover', 'ultraaddons-elementor-lite' ),
                    'click' => esc_html__( 'Mouse Click', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'ua_nav_submenu_divider',
            [
                'label'        => esc_html__( 'Item Dividers', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'render_type'  => 'template',
            ]
        );

        $this->add_control(
            'ua_nav_indicator_show',
            [
                'label'        => esc_html__( 'Submenu Indicator Icon', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
                'prefix_class' => 'ua-indicator-show-',
                'render_type'  => 'template',
            ]
        );

        $this->add_control(
            'ua_nav_indicator_type',
            [
                'label'       => esc_html__( 'Indicator Type', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'classic',
                'render_type' => 'template',
                'options'     => [
                    'classic' => esc_html__( 'Classic Chevron', 'ultraaddons-elementor-lite' ),
                    'angle'   => esc_html__( 'Angle', 'ultraaddons-elementor-lite' ),
                    'caret'   => esc_html__( 'Caret', 'ultraaddons-elementor-lite' ),
                    'arrow'   => esc_html__( 'Arrow', 'ultraaddons-elementor-lite' ),
                    'plus'    => esc_html__( 'Plus / Minus', 'ultraaddons-elementor-lite' ),
                ],
                'condition'   => [
                    'ua_nav_indicator_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ua_nav_indicator_rotate',
            [
                'label'        => esc_html__( 'Rotate on Hover/Open', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
                'prefix_class' => 'ua-indicator-rotate-',
                'condition'    => [
                    'ua_nav_indicator_show' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content Tab: Mobile Menu Settings
     */
    protected function register_mobile_controls() {
        $this->start_controls_section(
            '_section_ua_nav_mobile',
            [
                'label' => esc_html__( 'Mobile & Responsive Navigation', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ua_nav_mobile_breakpoint',
            [
                'label'       => esc_html__( 'Activate Mobile On', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'tablet',
                'render_type' => 'template',
                'options'     => [
                    'tablet' => esc_html__( 'Tablet & Mobile (≤ 1024px)', 'ultraaddons-elementor-lite' ),
                    'mobile' => esc_html__( 'Mobile Only (≤ 767px)', 'ultraaddons-elementor-lite' ),
                    'none'   => esc_html__( 'Never (Always Desktop Layout)', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'ua_nav_mobile_layout',
            [
                'label'       => esc_html__( 'Mobile Display Style', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'drawer',
                'render_type' => 'template',
                'options'     => [
                    'drawer'   => esc_html__( 'Off-Canvas Slide Drawer', 'ultraaddons-elementor-lite' ),
                    'dropdown' => esc_html__( 'Collapsible Dropdown Accordion', 'ultraaddons-elementor-lite' ),
                ],
                'condition'   => [
                    'ua_nav_mobile_breakpoint!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'ua_nav_drawer_side',
            [
                'label'       => esc_html__( 'Drawer Slide From', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'right',
                'render_type' => 'template',
                'options'     => [
                    'left'  => esc_html__( 'Left Side', 'ultraaddons-elementor-lite' ),
                    'right' => esc_html__( 'Right Side', 'ultraaddons-elementor-lite' ),
                ],
                'condition'   => [
                    'ua_nav_mobile_breakpoint!' => 'none',
                    'ua_nav_mobile_layout'      => 'drawer',
                ],
            ]
        );

        $this->add_control(
            'ua_nav_drawer_title',
            [
                'label'       => esc_html__( 'Drawer Header Title', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Navigation Menu', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'ua_nav_mobile_breakpoint!' => 'none',
                    'ua_nav_mobile_layout'      => 'drawer',
                ],
            ]
        );

        $this->add_control(
            'ua_nav_toggle_text',
            [
                'label'       => esc_html__( 'Toggle Button Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Optional, e.g. Menu', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'ua_nav_mobile_breakpoint!' => 'none',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab: Typography, Pointers, Submenu & Drawer Styling
     */
    protected function register_style_controls() {
        // -------------------------------------------------------------
        // 1. MAIN MENU ITEMS
        // -------------------------------------------------------------
        $this->start_controls_section(
            '_section_ua_nav_style_main',
            [
                'label' => esc_html__( 'Main Menu Items', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_nav_typography',
                'selector' => '{{WRAPPER}} .ua-nav-link',
            ]
        );

        $this->start_controls_tabs( '_tabs_ua_nav_item_states' );

        // Normal State
        $this->start_controls_tab(
            '_tab_ua_nav_item_normal',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_nav_item_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1e293b',
                'selectors' => [
                    '{{WRAPPER}} .ua-nav-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_nav_item_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-nav-link' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover State
        $this->start_controls_tab(
            '_tab_ua_nav_item_hover',
            [
                'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_nav_item_color_hover',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4f46e5',
                'selectors' => [
                    '{{WRAPPER}}:not(.ua-pointer-pill) .ua-nav-link:hover, {{WRAPPER}}:not(.ua-pointer-pill) .ua-nav-item:hover > .ua-nav-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_nav_item_bg_hover',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-nav-link:hover, {{WRAPPER}} .ua-nav-item:hover > .ua-nav-link' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Active State
        $this->start_controls_tab(
            '_tab_ua_nav_item_active',
            [
                'label' => esc_html__( 'Active', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_nav_item_color_active',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4f46e5',
                'selectors' => [
                    '{{WRAPPER}}:not(.ua-pointer-pill) .ua-nav-item.current-menu-item > .ua-nav-link, {{WRAPPER}}:not(.ua-pointer-pill) .ua-nav-item.current_page_item > .ua-nav-link, {{WRAPPER}}:not(.ua-pointer-pill) .ua-nav-item.current-menu-parent > .ua-nav-link, {{WRAPPER}}:not(.ua-pointer-pill) .ua-nav-item.current-menu-ancestor > .ua-nav-link, {{WRAPPER}}:not(.ua-pointer-pill) .ua-nav-item.ua-active-item > .ua-nav-link, {{WRAPPER}}:not(.ua-pointer-pill) .ua-nav-item.ua-active-item:hover > .ua-nav-link, {{WRAPPER}}:not(.ua-pointer-pill) .ua-nav-item.current-menu-item:hover > .ua-nav-link' => 'color: {{VALUE}} !important;',
                    '{{WRAPPER}}:not(.ua-pointer-pill) .ua-nav-item.current-menu-item > .ua-nav-link .ua-nav-title, {{WRAPPER}}:not(.ua-pointer-pill) .ua-nav-item.current_page_item > .ua-nav-link .ua-nav-title, {{WRAPPER}}:not(.ua-pointer-pill) .ua-nav-item.ua-active-item > .ua-nav-link .ua-nav-title' => 'color: {{VALUE}} !important;',
                    '{{WRAPPER}}:not(.ua-pointer-pill) .ua-nav-item.current-menu-item > .ua-nav-link .ua-sub-indicator, {{WRAPPER}}:not(.ua-pointer-pill) .ua-nav-item.current_page_item > .ua-nav-link .ua-sub-indicator, {{WRAPPER}}:not(.ua-pointer-pill) .ua-nav-item.ua-active-item > .ua-nav-link .ua-sub-indicator' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'ua_nav_item_bg_active',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}:not(.ua-pointer-pill) .ua-nav-item.current-menu-item > .ua-nav-link, {{WRAPPER}}:not(.ua-pointer-pill) .ua-nav-item.current_page_item > .ua-nav-link, {{WRAPPER}}:not(.ua-pointer-pill) .ua-nav-item.current-menu-parent > .ua-nav-link, {{WRAPPER}}:not(.ua-pointer-pill) .ua-nav-item.current-menu-ancestor > .ua-nav-link, {{WRAPPER}}:not(.ua-pointer-pill) .ua-nav-item.ua-active-item > .ua-nav-link, {{WRAPPER}}:not(.ua-pointer-pill) .ua-nav-item.ua-active-item:hover > .ua-nav-link, {{WRAPPER}}:not(.ua-pointer-pill) .ua-nav-item.current-menu-item:hover > .ua-nav-link' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'ua_nav_item_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'separator'  => 'before',
                'selectors'  => [
                    '{{WRAPPER}} .ua-desktop-nav .ua-nav-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_nav_item_space_between',
            [
                'label'      => esc_html__( 'Space Between Items', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 50,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 4,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-desktop-nav > .ua-nav-list > .ua-nav-item:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ua-nav-layout-vertical .ua-desktop-nav .ua-nav-item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}; margin-right: 0;',
                ],
            ]
        );

        $this->end_controls_section();

        // -------------------------------------------------------------
        // 2. POINTER STYLES
        // -------------------------------------------------------------
        $this->start_controls_section(
            '_section_ua_nav_style_pointer',
            [
                'label'     => esc_html__( 'Pointer Style', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ua_nav_pointer!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'ua_nav_pointer_color',
            [
                'label'     => esc_html__( 'Pointer Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4f46e5',
                'selectors' => [
                    '{{WRAPPER}}' => '--ua-nav-pointer-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_nav_pointer_text_color',
            [
                'label'     => esc_html__( 'Pill Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'condition' => [
                    'ua_nav_pointer' => 'pill',
                ],
                'selectors' => [
                    '{{WRAPPER}}.ua-pointer-pill .ua-nav-top-item:hover > .ua-nav-link, {{WRAPPER}}.ua-pointer-pill .ua-nav-top-item.current-menu-item > .ua-nav-link, {{WRAPPER}}.ua-pointer-pill .ua-nav-top-item.ua-sub-open > .ua-nav-link, {{WRAPPER}}.ua-pointer-pill .ua-nav-top-item.current-menu-parent > .ua-nav-link, {{WRAPPER}}.ua-pointer-pill .ua-nav-top-item.ua-active-item > .ua-nav-link' => 'color: {{VALUE}} !important;',
                    '{{WRAPPER}}.ua-pointer-pill .ua-nav-top-item:hover > .ua-nav-link .ua-nav-title, {{WRAPPER}}.ua-pointer-pill .ua-nav-top-item.current-menu-item > .ua-nav-link .ua-nav-title, {{WRAPPER}}.ua-pointer-pill .ua-nav-top-item.ua-sub-open > .ua-nav-link .ua-nav-title, {{WRAPPER}}.ua-pointer-pill .ua-nav-top-item.current-menu-parent > .ua-nav-link .ua-nav-title, {{WRAPPER}}.ua-pointer-pill .ua-nav-top-item.ua-active-item > .ua-nav-link .ua-nav-title' => 'color: {{VALUE}} !important;',
                    '{{WRAPPER}}.ua-pointer-pill .ua-nav-top-item:hover > .ua-nav-link .ua-sub-indicator, {{WRAPPER}}.ua-pointer-pill .ua-nav-top-item.current-menu-item > .ua-nav-link .ua-sub-indicator, {{WRAPPER}}.ua-pointer-pill .ua-nav-top-item.ua-sub-open > .ua-nav-link .ua-sub-indicator, {{WRAPPER}}.ua-pointer-pill .ua-nav-top-item.current-menu-parent > .ua-nav-link .ua-sub-indicator, {{WRAPPER}}.ua-pointer-pill .ua-nav-top-item.ua-active-item > .ua-nav-link .ua-sub-indicator' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_nav_pointer_pill_radius',
            [
                'label'      => esc_html__( 'Pill Border Radius (px)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 50,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'condition'  => [
                    'ua_nav_pointer' => 'pill',
                ],
                'selectors'  => [
                    '{{WRAPPER}}' => '--ua-nav-pointer-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ua_nav_pointer_height',
            [
                'label'      => esc_html__( 'Pointer Line Thickness (px)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 1,
                        'max'  => 8,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 2,
                ],
                'selectors'  => [
                    '{{WRAPPER}}' => '--ua-nav-pointer-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ua_nav_pointer_offset',
            [
                'label'      => esc_html__( 'Pointer Distance / Offset (px)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 25,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 4,
                ],
                'selectors'  => [
                    '{{WRAPPER}}' => '--ua-nav-pointer-offset: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // -------------------------------------------------------------
        // 3. SUBMENU DROPDOWN STYLES
        // -------------------------------------------------------------
        $this->start_controls_section(
            '_section_ua_nav_style_submenu',
            [
                'label' => esc_html__( 'Submenu Dropdown', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_nav_submenu_typography',
                'selector' => '{{WRAPPER}} .ua-sub-menu .ua-nav-link',
            ]
        );

        $this->add_control(
            'ua_nav_submenu_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-sub-menu' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_nav_submenu_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#334155',
                'selectors' => [
                    '{{WRAPPER}} .ua-sub-menu .ua-nav-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_nav_submenu_color_hover',
            [
                'label'     => esc_html__( 'Hover Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4f46e5',
                'selectors' => [
                    '{{WRAPPER}} .ua-sub-menu .ua-nav-link:hover, {{WRAPPER}} .ua-sub-menu .ua-nav-item:hover > .ua-nav-link, {{WRAPPER}} .ua-sub-menu .ua-nav-item.ua-sub-open > .ua-nav-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_nav_submenu_bg_hover',
            [
                'label'     => esc_html__( 'Hover Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f8fafc',
                'selectors' => [
                    '{{WRAPPER}} .ua-sub-menu .ua-nav-link:hover, {{WRAPPER}} .ua-sub-menu .ua-nav-item:hover > .ua-nav-link, {{WRAPPER}} .ua-sub-menu .ua-nav-item.ua-sub-open > .ua-nav-link' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_nav_submenu_offset',
            [
                'label'      => esc_html__( 'Submenu Top Distance (px)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 40,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors'  => [
                    '{{WRAPPER}}' => '--ua-nav-submenu-offset: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_nav_nested_offset',
            [
                'label'      => esc_html__( 'Nested Submenu Gap (px)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 30,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 2,
                ],
                'selectors'  => [
                    '{{WRAPPER}}' => '--ua-nav-nested-offset: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_nav_submenu_width',
            [
                'label'      => esc_html__( 'Min Width (px)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 150,
                        'max'  => 450,
                        'step' => 5,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 220,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-sub-menu' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ua_nav_submenu_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-sub-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_nav_submenu_shadow',
                'selector' => '{{WRAPPER}} .ua-sub-menu',
            ]
        );

        $this->end_controls_section();

        // -------------------------------------------------------------
        // 4. SUBMENU INDICATOR STYLES
        // -------------------------------------------------------------
        $this->start_controls_section(
            '_section_ua_nav_style_indicator',
            [
                'label'     => esc_html__( 'Submenu Indicator Icon', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ua_nav_indicator_show' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_nav_indicator_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 6, 'max' => 32, 'step' => 1 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}}'                       => '--ua-nav-indicator-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-sub-indicator svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_nav_indicator_spacing',
            [
                'label'      => esc_html__( 'Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 40, 'step' => 1 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}}'                   => '--ua-nav-indicator-spacing: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-sub-indicator' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'ua_nav_indicator_tabs' );

        $this->start_controls_tab(
            'ua_nav_indicator_tab_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'ua_nav_indicator_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-sub-indicator' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'ua_nav_indicator_tab_hover',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'ua_nav_indicator_color_hover',
            [
                'label'     => esc_html__( 'Hover Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-nav-link:hover .ua-sub-indicator, {{WRAPPER}} .ua-nav-item.ua-sub-open > .ua-nav-link .ua-sub-indicator' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'ua_nav_indicator_tab_active',
            [ 'label' => esc_html__( 'Active', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'ua_nav_indicator_color_active',
            [
                'label'     => esc_html__( 'Active Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-nav-item.current-menu-item > .ua-nav-link .ua-sub-indicator, {{WRAPPER}} .ua-nav-item.ua-active-item > .ua-nav-link .ua-sub-indicator' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // -------------------------------------------------------------
        // 5. MOBILE TOGGLE & DRAWER STYLES
        // -------------------------------------------------------------
        $this->start_controls_section(
            '_section_ua_nav_style_mobile',
            [
                'label' => esc_html__( 'Mobile Toggle & Drawer', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'ua_nav_toggle_color',
            [
                'label'     => esc_html__( 'Toggle Button Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1e293b',
                'selectors' => [
                    '{{WRAPPER}} .ua-nav-toggle-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_nav_toggle_bg',
            [
                'label'     => esc_html__( 'Toggle Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-nav-toggle-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_nav_drawer_bg',
            [
                'label'     => esc_html__( 'Drawer / Dropdown Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-nav-drawer, {{WRAPPER}} .ua-mobile-dropdown' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on frontend and editor preview
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $menu_slug = ! empty( $settings['ua_nav_menu_slug'] ) ? $settings['ua_nav_menu_slug'] : '';
        if ( empty( $menu_slug ) ) {
            return;
        }

        $layout     = ! empty( $settings['ua_nav_layout'] ) ? $settings['ua_nav_layout'] : 'horizontal';
        $pointer    = ! empty( $settings['ua_nav_pointer'] ) ? $settings['ua_nav_pointer'] : 'underline';
        $anim       = ! empty( $settings['ua_nav_pointer_anim'] ) ? $settings['ua_nav_pointer_anim'] : 'fade';
        $breakpoint = ! empty( $settings['ua_nav_mobile_breakpoint'] ) ? $settings['ua_nav_mobile_breakpoint'] : 'tablet';
        $mob_layout = ! empty( $settings['ua_nav_mobile_layout'] ) ? $settings['ua_nav_mobile_layout'] : 'drawer';
        $drawer_side= ! empty( $settings['ua_nav_drawer_side'] ) ? $settings['ua_nav_drawer_side'] : 'right';
        $has_divider= ! empty( $settings['ua_nav_submenu_divider'] ) && 'yes' === $settings['ua_nav_submenu_divider'];
        $trigger    = ! empty( $settings['ua_nav_submenu_trigger'] ) ? $settings['ua_nav_submenu_trigger'] : 'hover';
        $offset     = isset( $settings['ua_nav_sticky_offset'] ) ? (int) $settings['ua_nav_sticky_offset'] : 0;
        $toggle_txt = ! empty( $settings['ua_nav_toggle_text'] ) ? $settings['ua_nav_toggle_text'] : '';
        $drawer_tit = ! empty( $settings['ua_nav_drawer_title'] ) ? $settings['ua_nav_drawer_title'] : esc_html__( 'Navigation Menu', 'ultraaddons-elementor-lite' );

        $align      = ! empty( $settings['ua_nav_align'] ) ? $settings['ua_nav_align'] : 'left';
        $show_ind   = ! empty( $settings['ua_nav_indicator_show'] ) ? $settings['ua_nav_indicator_show'] : 'yes';
        $ind_type   = ! empty( $settings['ua_nav_indicator_type'] ) ? $settings['ua_nav_indicator_type'] : 'classic';
        $ind_rotate = ! empty( $settings['ua_nav_indicator_rotate'] ) ? $settings['ua_nav_indicator_rotate'] : 'yes';

        $wrapper_classes = [
            'ua-nav-menu-wrapper',
            'ua-nav-layout-' . sanitize_html_class( $layout ),
            'ua-trigger-' . sanitize_html_class( $trigger ),
            'ua-nav-align-' . sanitize_html_class( $align ),
            'ua-indicator-show-' . sanitize_html_class( $show_ind ),
            'ua-indicator-type-' . sanitize_html_class( $ind_type ),
            'ua-indicator-rotate-' . sanitize_html_class( $ind_rotate ),
        ];

        if ( 'none' !== $pointer ) {
            $wrapper_classes[] = 'ua-pointer-' . sanitize_html_class( $pointer );
            $wrapper_classes[] = 'ua-anim-' . sanitize_html_class( $anim );
        }

        if ( $has_divider ) {
            $wrapper_classes[] = 'ua-submenu-has-divider';
        }

        if ( 'none' !== $breakpoint ) {
            $wrapper_classes[] = 'ua-bp-' . sanitize_html_class( $breakpoint );
        }

        $js_settings = [
            'mobile_layout'   => $mob_layout,
            'submenu_trigger' => $trigger,
            'sticky_offset'   => $offset,
        ];

        ?>
        <div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>" data-ua-nav-settings="<?php echo esc_attr( wp_json_encode( $js_settings ) ); ?>">
            <div class="ua-nav-menu-container">

                <!-- Desktop Navigation Menu -->
                <nav class="ua-desktop-nav" aria-label="<?php esc_attr_e( 'Main Navigation', 'ultraaddons-elementor-lite' ); ?>">
                    <?php
                    wp_nav_menu( [
                        'menu'            => $menu_slug,
                        'container'       => false,
                        'menu_class'      => 'ua-nav-list',
                        'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                        'depth'           => 0,
                        'walker'          => new \Ultra_Nav_Walker( false, $ind_type, $show_ind ),
                        'fallback_cb'     => false,
                    ] );
                    ?>
                </nav>

                <!-- Mobile Toggle Button -->
                <?php if ( 'none' !== $breakpoint ) : ?>
                    <button class="ua-nav-toggle-btn" aria-label="<?php esc_attr_e( 'Toggle Navigation', 'ultraaddons-elementor-lite' ); ?>" aria-expanded="false">
                        <span class="ua-toggle-bars" aria-hidden="true">
                            <span class="ua-toggle-bar"></span>
                            <span class="ua-toggle-bar"></span>
                            <span class="ua-toggle-bar"></span>
                        </span>
                        <?php if ( ! empty( $toggle_txt ) ) : ?>
                            <span class="ua-toggle-label"><?php echo esc_html( $toggle_txt ); ?></span>
                        <?php endif; ?>
                    </button>
                <?php endif; ?>

            </div>

            <!-- Mobile Navigation Layouts -->
            <?php if ( 'none' !== $breakpoint ) : ?>
                <?php if ( 'drawer' === $mob_layout ) : ?>
                    <!-- Off-Canvas Drawer -->
                    <div class="ua-drawer-overlay" aria-hidden="true"></div>
                    <div class="ua-nav-drawer ua-drawer-<?php echo esc_attr( $drawer_side ); ?>" role="dialog" aria-modal="true" aria-label="<?php echo esc_attr( $drawer_tit ); ?>">
                        <div class="ua-nav-drawer-header">
                            <h3 class="ua-nav-drawer-title"><?php echo esc_html( $drawer_tit ); ?></h3>
                            <button class="ua-nav-drawer-close" aria-label="<?php esc_attr_e( 'Close Menu', 'ultraaddons-elementor-lite' ); ?>">&times;</button>
                        </div>
                        <div class="ua-nav-drawer-body">
                            <?php
                            wp_nav_menu( [
                                'menu'        => $menu_slug,
                                'container'   => false,
                                'menu_class'  => 'ua-nav-list',
                                'items_wrap'  => '<ul class="%2$s">%3$s</ul>',
                                'depth'       => 0,
                                'walker'      => new \Ultra_Nav_Walker( true, $ind_type, $show_ind ),
                                'fallback_cb' => false,
                            ] );
                            ?>
                        </div>
                    </div>
                <?php else : ?>
                    <!-- Collapsible Dropdown -->
                    <div class="ua-mobile-dropdown">
                        <?php
                        wp_nav_menu( [
                            'menu'        => $menu_slug,
                            'container'   => false,
                            'menu_class'  => 'ua-nav-list',
                            'items_wrap'  => '<ul class="%2$s">%3$s</ul>',
                            'depth'       => 0,
                            'walker'      => new \Ultra_Nav_Walker( true, $ind_type, $show_ind ),
                            'fallback_cb' => false,
                        ] );
                        ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

        </div>
        <?php
    }
}