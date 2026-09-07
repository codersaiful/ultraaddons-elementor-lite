<?php
namespace UltraAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use UltraAddons\Core\Mega_Menu as Core_Mega_Menu;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once ULTRA_ADDONS_DIR . 'inc/core/mega-menu/class-ultra-nav-walker.php';

/**
 * UltraAddons Dedicated Mega Menu Widget
 *
 * A modern, responsive, Elementor-integrated Mega Menu widget.
 * Features full Elementor canvas builder templates, custom width strategies,
 * animated status badges (pulse dot, shimmer), custom menu item icons,
 * mobile drawer/dropdown support, and on-demand mobile AJAX caching.
 *
 * @package UltraAddons
 * @author Saiful Islam <codersaiful@gmail.com>
 * @version 2.2.0
 */
class Mega_Menu extends Base {

    /**
     * Constructor: registers widget specific CSS and JS
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
            'mega menu',
            'mega',
            'menu',
            'navigation',
            'nav',
            'header',
            'navbar',
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
     * Content Tab: Menu Selection, Mega Menu Instructions & Alignment
     */
    protected function register_content_controls() {
        $this->start_controls_section(
            '_section_ua_mega_content',
            [
                'label' => esc_html__( 'Menu & Mega Setup', 'ultraaddons-elementor-lite' ),
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
                        __( 'Configure mega menu templates, icons & badges in <a href="%s" target="_blank">Appearance > Menus</a>.', 'ultraaddons-elementor-lite' ),
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

        // Informative tip box
        $this->add_control(
            'ua_mega_notice',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => sprintf(
                    /* translators: %s: URL to Appearance > Menus */
                    __( '<div style="background:#eef2ff; border-left:4px solid #4f46e5; padding:10px 12px; font-size:12px; color:#312e81; border-radius:4px; line-height:1.5;"><strong>⚡ How Ultra Mega Menu Works:</strong><br>Go to <a href="%s" target="_blank" style="color:#4f46e5; font-weight:600; text-decoration:underline;">Appearance &gt; Menus</a>, click the <strong>"⚡ Ultra Mega Menu"</strong> button on any item to design with Elementor, set width, add icons, or animated badges!</div>', 'ultraaddons-elementor-lite' ),
                    admin_url( 'nav-menus.php' )
                ),
            ]
        );

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
            'ua_nav_submenu_trigger',
            [
                'label'   => esc_html__( 'Submenu Trigger', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'hover',
                'options' => [
                    'hover' => esc_html__( 'Mouse Hover', 'ultraaddons-elementor-lite' ),
                    'click' => esc_html__( 'Mouse Click', 'ultraaddons-elementor-lite' ),
                ],
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
     * Content Tab: Hover Pointer Effects
     */
    protected function register_pointer_controls() {
        $this->start_controls_section(
            '_section_ua_nav_pointer',
            [
                'label' => esc_html__( 'Pointer & Hover Effect', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ua_nav_pointer',
            [
                'label'   => esc_html__( 'Pointer', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'underline',
                'options' => [
                    'none'        => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                    'underline'   => esc_html__( 'Underline', 'ultraaddons-elementor-lite' ),
                    'overline'    => esc_html__( 'Overline', 'ultraaddons-elementor-lite' ),
                    'double-line' => esc_html__( 'Double Line', 'ultraaddons-elementor-lite' ),
                    'framed'      => esc_html__( 'Framed', 'ultraaddons-elementor-lite' ),
                    'pill'        => esc_html__( 'Background Pill', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'ua_nav_pointer_animation',
            [
                'label'     => esc_html__( 'Animation', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'fade',
                'options'   => [
                    'fade'  => esc_html__( 'Fade', 'ultraaddons-elementor-lite' ),
                    'slide' => esc_html__( 'Slide', 'ultraaddons-elementor-lite' ),
                    'grow'  => esc_html__( 'Grow', 'ultraaddons-elementor-lite' ),
                    'drop'  => esc_html__( 'Drop', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'ua_nav_pointer!' => [ 'none', 'pill' ],
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content Tab: Mega Dropdown & Submenu Settings
     */
    protected function register_submenu_controls() {
        $this->start_controls_section(
            '_section_ua_nav_submenu',
            [
                'label' => esc_html__( 'Mega Dropdown Settings', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ua_nav_submenu_offset',
            [
                'label'      => esc_html__( 'Dropdown Top Offset (px)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 50 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 0 ],
                'selectors'  => [
                    '{{WRAPPER}}' => '--ua-nav-submenu-offset: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ua_nav_transition_speed',
            [
                'label'      => esc_html__( 'Animation Speed (ms)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'ms' ],
                'range'      => [
                    'ms' => [ 'min' => 100, 'max' => 800, 'step' => 50 ],
                ],
                'default'    => [ 'unit' => 'ms', 'size' => 250 ],
                'selectors'  => [
                    '{{WRAPPER}}' => '--ua-nav-transition-speed: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content Tab: Mobile Menu & Off-Canvas Drawer
     */
    protected function register_mobile_controls() {
        $this->start_controls_section(
            '_section_ua_nav_mobile',
            [
                'label' => esc_html__( 'Mobile Menu & Drawer', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ua_nav_mobile_breakpoint',
            [
                'label'   => esc_html__( 'Breakpoint', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'tablet',
                'options' => [
                    'tablet' => esc_html__( 'Tablet & Mobile (<= 1024px)', 'ultraaddons-elementor-lite' ),
                    'mobile' => esc_html__( 'Mobile Only (<= 767px)', 'ultraaddons-elementor-lite' ),
                    'none'   => esc_html__( 'None (Always Desktop)', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'ua_nav_mobile_layout',
            [
                'label'     => esc_html__( 'Mobile Menu Layout', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'dropdown',
                'options'   => [
                    'dropdown' => esc_html__( 'Collapsible Dropdown', 'ultraaddons-elementor-lite' ),
                    'drawer'   => esc_html__( 'Off-Canvas Slide Drawer', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'ua_nav_mobile_breakpoint!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'ua_nav_drawer_side',
            [
                'label'     => esc_html__( 'Drawer Slide From', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => 'left',
                'options'   => [
                    'left'  => [
                        'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'condition' => [
                    'ua_nav_mobile_breakpoint!' => 'none',
                    'ua_nav_mobile_layout'      => 'drawer',
                ],
            ]
        );

        $this->add_control(
            'ua_nav_drawer_title',
            [
                'label'     => esc_html__( 'Drawer Title', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Navigation Menu', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'ua_nav_mobile_breakpoint!' => 'none',
                    'ua_nav_mobile_layout'      => 'drawer',
                ],
            ]
        );

        $this->add_control(
            'ua_nav_toggle_text',
            [
                'label'       => esc_html__( 'Toggle Button Text (Optional)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Menu', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'ua_nav_mobile_breakpoint!' => 'none',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab: Comprehensive Styling for Top Level Items, Mega Panel, and Mobile
     */
    protected function register_style_controls() {

        // 1. Top Level Menu Items
        $this->start_controls_section(
            '_section_ua_style_main_items',
            [
                'label' => esc_html__( 'Main Menu Items', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_nav_item_typography',
                'selector' => '{{WRAPPER}} .ua-desktop-nav .ua-nav-top-item > .ua-nav-link',
            ]
        );

        $this->start_controls_tabs( 'ua_nav_item_tabs' );

        // Normal Tab
        $this->start_controls_tab(
            'ua_nav_item_tab_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'ua_nav_item_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#334155',
                'selectors' => [
                    '{{WRAPPER}} .ua-desktop-nav .ua-nav-top-item > .ua-nav-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_nav_item_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-desktop-nav .ua-nav-top-item > .ua-nav-link' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover Tab
        $this->start_controls_tab(
            'ua_nav_item_tab_hover',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'ua_nav_item_color_hover',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4f46e5',
                'selectors' => [
                    '{{WRAPPER}} .ua-desktop-nav .ua-nav-top-item:hover > .ua-nav-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_nav_pointer_color',
            [
                'label'     => esc_html__( 'Pointer / Hover Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4f46e5',
                'selectors' => [
                    '{{WRAPPER}}' => '--ua-nav-pointer-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Active Tab
        $this->start_controls_tab(
            'ua_nav_item_tab_active',
            [ 'label' => esc_html__( 'Active', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'ua_nav_item_color_active',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4f46e5',
                'selectors' => [
                    '{{WRAPPER}} .ua-nav-item.current-menu-item > .ua-nav-link, {{WRAPPER}} .ua-nav-item.ua-active-item > .ua-nav-link' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'ua_nav_item_padding',
            [
                'label'      => esc_html__( 'Item Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'top'      => 14,
                    'right'    => 18,
                    'bottom'   => 14,
                    'left'     => 18,
                    'isLinked' => false,
                ],
                'separator'  => 'before',
                'selectors'  => [
                    '{{WRAPPER}} .ua-desktop-nav .ua-nav-top-item > .ua-nav-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // 2. Mega Dropdown Panel Styling
        $this->start_controls_section(
            '_section_ua_style_mega_panel',
            [
                'label' => esc_html__( 'Mega Dropdown Panel', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'ua_mega_panel_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-mega-dropdown' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_mega_panel_border',
                'selector' => '{{WRAPPER}} .ua-mega-dropdown',
            ]
        );

        $this->add_responsive_control(
            'ua_mega_panel_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'    => [
                    'top' => 8, 'right' => 8, 'bottom' => 8, 'left' => 8, 'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-mega-dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_mega_panel_shadow',
                'selector' => '{{WRAPPER}} .ua-mega-dropdown',
            ]
        );

        $this->add_responsive_control(
            'ua_mega_panel_padding',
            [
                'label'      => esc_html__( 'Panel Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'top' => 20, 'right' => 20, 'bottom' => 20, 'left' => 20, 'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-mega-dropdown' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // 3. Submenu Indicator Icon Style
        $this->start_controls_section(
            '_section_ua_style_indicator',
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

        // 4. Mobile Toggle Button Style
        $this->start_controls_section(
            '_section_ua_style_toggle',
            [
                'label' => esc_html__( 'Mobile Toggle Button', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'ua_nav_toggle_color',
            [
                'label'     => esc_html__( 'Icon / Bar Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1e293b',
                'selectors' => [
                    '{{WRAPPER}} .ua-toggle-bar' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_nav_toggle_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f1f5f9',
                'selectors' => [
                    '{{WRAPPER}} .ua-nav-toggle-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render Widget Output
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $menu_slug = ! empty( $settings['ua_nav_menu_slug'] ) ? $settings['ua_nav_menu_slug'] : '';
        if ( empty( $menu_slug ) ) {
            $menus = $this->get_available_menus();
            $menu_slug = ! empty( $menus ) ? array_key_first( $menus ) : '';
        }

        if ( empty( $menu_slug ) ) {
            return;
        }

        $layout      = ! empty( $settings['ua_nav_layout'] ) ? $settings['ua_nav_layout'] : 'horizontal';
        $pointer     = ! empty( $settings['ua_nav_pointer'] ) ? $settings['ua_nav_pointer'] : 'underline';
        $animation   = ! empty( $settings['ua_nav_pointer_animation'] ) ? $settings['ua_nav_pointer_animation'] : 'fade';
        $breakpoint  = ! empty( $settings['ua_nav_mobile_breakpoint'] ) ? $settings['ua_nav_mobile_breakpoint'] : 'tablet';
        $mob_layout  = ! empty( $settings['ua_nav_mobile_layout'] ) ? $settings['ua_nav_mobile_layout'] : 'dropdown';
        $drawer_side = ! empty( $settings['ua_nav_drawer_side'] ) ? $settings['ua_nav_drawer_side'] : 'left';
        $drawer_tit  = ! empty( $settings['ua_nav_drawer_title'] ) ? $settings['ua_nav_drawer_title'] : esc_html__( 'Menu', 'ultraaddons-elementor-lite' );
        $toggle_txt  = ! empty( $settings['ua_nav_toggle_text'] ) ? $settings['ua_nav_toggle_text'] : '';
        $sub_trigger = ! empty( $settings['ua_nav_submenu_trigger'] ) ? $settings['ua_nav_submenu_trigger'] : 'hover';
        $offset      = isset( $settings['ua_nav_submenu_offset']['size'] ) ? $settings['ua_nav_submenu_offset']['size'] : 0;
        $align       = ! empty( $settings['ua_nav_align'] ) ? $settings['ua_nav_align'] : 'left';
        $show_ind    = ! empty( $settings['ua_nav_indicator_show'] ) ? $settings['ua_nav_indicator_show'] : 'yes';
        $ind_type    = ! empty( $settings['ua_nav_indicator_type'] ) ? $settings['ua_nav_indicator_type'] : 'classic';
        $ind_rotate  = ! empty( $settings['ua_nav_indicator_rotate'] ) ? $settings['ua_nav_indicator_rotate'] : 'yes';

        $wrapper_classes = [
            'ua-nav-menu-wrapper',
            'ua-mega-menu-wrapper',
            'ua-layout-' . sanitize_html_class( $layout ),
            'ua-pointer-' . sanitize_html_class( $pointer ),
            'ua-anim-' . sanitize_html_class( $animation ),
            'ua-trigger-' . sanitize_html_class( $sub_trigger ),
            'ua-bp-' . sanitize_html_class( $breakpoint ),
            'ua-nav-align-' . sanitize_html_class( $align ),
            'ua-indicator-show-' . sanitize_html_class( $show_ind ),
            'ua-indicator-type-' . sanitize_html_class( $ind_type ),
            'ua-indicator-rotate-' . sanitize_html_class( $ind_rotate ),
        ];

        $js_settings = [
            'mobile_layout'   => $mob_layout,
            'breakpoint'      => $breakpoint,
            'submenu_trigger' => $sub_trigger,
            'sticky_offset'   => $offset,
        ];

        ?>
        <div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>" data-ua-nav-settings="<?php echo esc_attr( wp_json_encode( $js_settings ) ); ?>">
            <div class="ua-nav-menu-container">

                <!-- Desktop Navigation / Mega Menu -->
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
                    <!-- Off-Canvas Slide Drawer -->
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
