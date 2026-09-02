<?php
/**
 * Image Hotspots Widget
 *
 * An interactive, responsive Image Hotspot widget for Elementor.
 * Features pulse/radar/glow animations, custom tooltips, rich content,
 * and direct WooCommerce product showcase with Add to Cart.
 *
 * @package UltraAddons
 * @since 2.0.3.5
 */

namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Image_Hotspots extends Base {

    /**
     * Constructor: Register widget CSS and JS dependencies.
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        $css_file = ULTRA_ADDONS_DIR . 'assets/css/widgets/image-hotspots.css';
        $css_ver  = file_exists( $css_file ) ? filemtime( $css_file ) : ULTRA_ADDONS_VERSION;

        wp_register_style(
            'ultraaddons-image-hotspots',
            ULTRA_ADDONS_ASSETS . 'css/widgets/image-hotspots.css',
            [ 'ultraaddons-widgets-style' ],
            $css_ver
        );

        $js_file = ULTRA_ADDONS_DIR . 'assets/js/frontend-image-hotspots.js';
        $js_ver  = file_exists( $js_file ) ? filemtime( $js_file ) : ULTRA_ADDONS_VERSION;

        wp_register_script(
            'ultraaddons-image-hotspots',
            ULTRA_ADDONS_ASSETS . 'js/frontend-image-hotspots.js',
            [ 'jquery', 'elementor-frontend' ],
            $js_ver,
            true
        );
    }

    /**
     * Widget Style Dependencies.
     */
    public function get_style_depends() {
        return [ 'ultraaddons-image-hotspots' ];
    }

    /**
     * Widget Script Dependencies.
     */
    public function get_script_depends() {
        return [ 'ultraaddons-image-hotspots' ];
    }

    /**
     * Get Widget Name.
     */
    public function get_name() {
        return 'ultraaddons-image-hotspots';
    }

    /**
     * Get Widget Title.
     */
    public function get_title() {
        return esc_html__( 'Image Hotspots', 'ultraaddons-elementor-lite' );
    }

    /**
     * Get Widget Icon.
     */
    public function get_icon() {
        return 'ultraaddons eicon-image-hotspot';
    }

    /**
     * Get Widget Categories.
     */
    public function get_categories() {
        return [ 'ultraaddons-elementor-lite', 'ultraaddons-wc' ];
    }

    /**
     * Get Widget Search Keywords.
     */
    public function get_keywords() {
        return [ 'ultraaddons', 'image', 'hotspot', 'hotspots', 'marker', 'pin', 'pointer', 'product', 'tooltip' ];
    }

    /**
     * Helper: Fetch WooCommerce products for dropdown selector.
     */
    protected function get_product_options() {
        if ( ! function_exists( 'wc_get_products' ) ) {
            return [];
        }

        $products = wc_get_products( [
            'limit'  => 60,
            'status' => 'publish',
            'return' => 'objects',
        ] );

        $options = [];
        if ( ! empty( $products ) ) {
            foreach ( $products as $product ) {
                if ( is_object( $product ) ) {
                    $options[ $product->get_id() ] = $product->get_name() . ' (#' . $product->get_id() . ')';
                }
            }
        }
        return $options;
    }

    /**
     * Register Controls.
     */
    protected function register_controls() {
        $this->register_image_controls();
        $this->register_hotspots_controls();
        $this->register_settings_controls();

        // Style Sections
        $this->register_style_image_controls();
        $this->register_style_pin_controls();
        $this->register_style_tooltip_controls();
        $this->register_style_content_controls();
        $this->register_style_button_controls();
    }

    /**
     * Content Section: Main Image.
     */
    protected function register_image_controls() {
        $this->start_controls_section(
            'section_image',
            [
                'label' => esc_html__( 'Image', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'image',
            [
                'label'   => esc_html__( 'Choose Image', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'      => 'image_size',
                'default'   => 'full',
                'separator' => 'none',
            ]
        );

        $this->add_control(
            'enable_overlay',
            [
                'label'        => esc_html__( 'Enable Dark/Color Overlay', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => '',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'overlay_color',
            [
                'label'     => esc_html__( 'Overlay Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(0, 0, 0, 0.25)',
                'selectors' => [
                    '{{WRAPPER}} .ua-hotspot-overlay' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'enable_overlay' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content Section: Hotspots (Repeater).
     */
    protected function register_hotspots_controls() {
        $this->start_controls_section(
            'section_hotspots',
            [
                'label' => esc_html__( 'Hotspot Markers', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->start_controls_tabs( 'tabs_hotspot_item' );

        // Tab: Content
        $repeater->start_controls_tab(
            'tab_item_content',
            [
                'label' => esc_html__( 'Content', 'ultraaddons-elementor-lite' ),
            ]
        );

        $repeater->add_control(
            'pin_type',
            [
                'label'   => esc_html__( 'Marker Type', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => [
                    'icon' => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                    'text' => esc_html__( 'Text / Number', 'ultraaddons-elementor-lite' ),
                    'dot'  => esc_html__( 'Minimal Dot', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $repeater->add_control(
            'pin_icon',
            [
                'label'            => esc_html__( 'Marker Icon', 'ultraaddons-elementor-lite' ),
                'type'             => Controls_Manager::ICONS,
                'default'          => [
                    'value'   => 'fas fa-plus',
                    'library' => 'fa-solid',
                ],
                'condition'        => [
                    'pin_type' => 'icon',
                ],
            ]
        );

        $repeater->add_control(
            'pin_text',
            [
                'label'       => esc_html__( 'Marker Text / Number', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '1',
                'placeholder' => '1, 2, A, View, etc.',
                'condition'   => [
                    'pin_type' => 'text',
                ],
            ]
        );

        $repeater->add_control(
            'action_type',
            [
                'label'     => esc_html__( 'Click Action', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'tooltip',
                'options'   => [
                    'tooltip' => esc_html__( 'Show Tooltip', 'ultraaddons-elementor-lite' ),
                    'link'    => esc_html__( 'Direct Link', 'ultraaddons-elementor-lite' ),
                ],
                'separator' => 'before',
            ]
        );

        $repeater->add_control(
            'pin_link',
            [
                'label'       => esc_html__( 'Target Link', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [
                    'active' => true,
                ],
                'condition'   => [
                    'action_type' => 'link',
                ],
            ]
        );

        $repeater->add_control(
            'content_source',
            [
                'label'     => esc_html__( 'Tooltip Content Source', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'custom',
                'options'   => [
                    'custom'  => esc_html__( 'Custom Content', 'ultraaddons-elementor-lite' ),
                    'product' => esc_html__( 'WooCommerce Product', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'action_type' => 'tooltip',
                ],
            ]
        );

        // --- Custom Content Fields ---
        $repeater->add_control(
            'tooltip_image',
            [
                'label'     => esc_html__( 'Tooltip Image', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::MEDIA,
                'dynamic'   => [
                    'active' => true,
                ],
                'condition' => [
                    'action_type'    => 'tooltip',
                    'content_source' => 'custom',
                ],
            ]
        );

        $repeater->add_control(
            'tooltip_subtitle',
            [
                'label'       => esc_html__( 'Subtitle / Tag', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'FURNITURE',
                'placeholder' => 'CATEGORY / TAG',
                'condition'   => [
                    'action_type'    => 'tooltip',
                    'content_source' => 'custom',
                ],
            ]
        );

        $repeater->add_control(
            'tooltip_title',
            [
                'label'       => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'Modern Lounge Chair',
                'label_block' => true,
                'condition'   => [
                    'action_type'    => 'tooltip',
                    'content_source' => 'custom',
                ],
            ]
        );

        $repeater->add_control(
            'tooltip_price',
            [
                'label'       => esc_html__( 'Price', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '$199.00',
                'placeholder' => '$99.00',
                'condition'   => [
                    'action_type'    => 'tooltip',
                    'content_source' => 'custom',
                ],
            ]
        );

        $repeater->add_control(
            'tooltip_desc',
            [
                'label'       => esc_html__( 'Description', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXTAREA,
                'rows'        => 3,
                'default'     => 'Scandinavian crafted oak wood with premium linen upholstery.',
                'condition'   => [
                    'action_type'    => 'tooltip',
                    'content_source' => 'custom',
                ],
            ]
        );

        $repeater->add_control(
            'tooltip_btn_text',
            [
                'label'       => esc_html__( 'Button Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'Shop Now',
                'placeholder' => 'View Details, Buy, etc.',
                'condition'   => [
                    'action_type'    => 'tooltip',
                    'content_source' => 'custom',
                ],
            ]
        );

        $repeater->add_control(
            'tooltip_btn_url',
            [
                'label'       => esc_html__( 'Button Link', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [
                    'active' => true,
                ],
                'condition'   => [
                    'action_type'       => 'tooltip',
                    'content_source'    => 'custom',
                    'tooltip_btn_text!' => '',
                ],
            ]
        );

        // --- WooCommerce Product Fields ---
        $repeater->add_control(
            'product_id',
            [
                'label'       => esc_html__( 'Select Product', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT2,
                'options'     => $this->get_product_options(),
                'label_block' => true,
                'condition'   => [
                    'action_type'    => 'tooltip',
                    'content_source' => 'product',
                ],
            ]
        );

        $repeater->add_control(
            'show_product_image',
            [
                'label'        => esc_html__( 'Show Product Thumbnail', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'action_type'    => 'tooltip',
                    'content_source' => 'product',
                ],
            ]
        );

        $repeater->add_control(
            'show_product_price',
            [
                'label'        => esc_html__( 'Show Price', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'action_type'    => 'tooltip',
                    'content_source' => 'product',
                ],
            ]
        );

        $repeater->add_control(
            'show_add_to_cart',
            [
                'label'        => esc_html__( 'Show Add to Cart Button', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'action_type'    => 'tooltip',
                    'content_source' => 'product',
                ],
            ]
        );

        // --- Custom Color Override for Pin ---
        $repeater->add_control(
            'custom_pin_colors',
            [
                'label'        => esc_html__( 'Custom Marker Colors', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => '',
                'separator'    => 'before',
            ]
        );

        $repeater->add_control(
            'pin_custom_bg',
            [
                'label'     => esc_html__( 'Marker Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .ua-hotspot-pin' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'custom_pin_colors' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'pin_custom_color',
            [
                'label'     => esc_html__( 'Marker Icon/Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .ua-hotspot-pin' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
                'condition' => [
                    'custom_pin_colors' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'pin_custom_glow',
            [
                'label'     => esc_html__( 'Pulse/Radar Ring Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .ua-hotspot-pulse-ring' => 'border-color: {{VALUE}}; background-color: {{VALUE}};',
                ],
                'condition' => [
                    'custom_pin_colors' => 'yes',
                ],
            ]
        );

        $repeater->end_controls_tab();

        // Tab: Position
        $repeater->start_controls_tab(
            'tab_item_position',
            [
                'label' => esc_html__( 'Position', 'ultraaddons-elementor-lite' ),
            ]
        );

        $repeater->add_responsive_control(
            'pos_x',
            [
                'label'      => esc_html__( 'Horizontal Position (%)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range'      => [
                    '%' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 0.5,
                    ],
                ],
                'default'    => [
                    'unit' => '%',
                    'size' => 30,
                ],
                'selectors'  => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'left: {{SIZE}}%;',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'pos_y',
            [
                'label'      => esc_html__( 'Vertical Position (%)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range'      => [
                    '%' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 0.5,
                    ],
                ],
                'default'    => [
                    'unit' => '%',
                    'size' => 40,
                ],
                'selectors'  => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'top: {{SIZE}}%;',
                ],
            ]
        );

        $repeater->add_control(
            'item_tooltip_pos',
            [
                'label'       => esc_html__( 'Tooltip Position Override', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'global',
                'options'     => [
                    'global' => esc_html__( 'Default (Global)', 'ultraaddons-elementor-lite' ),
                    'top'    => esc_html__( 'Top', 'ultraaddons-elementor-lite' ),
                    'bottom' => esc_html__( 'Bottom', 'ultraaddons-elementor-lite' ),
                    'left'   => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                    'right'  => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                ],
                'separator'   => 'before',
                'condition'   => [
                    'action_type' => 'tooltip',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'item_tooltip_distance',
            [
                'label'      => esc_html__( 'Distance Override (px)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 80,
                        'step' => 1,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '--ua-tooltip-offset: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'action_type' => 'tooltip',
                ],
            ]
        );

        $repeater->end_controls_tab();

        $repeater->end_controls_tabs();

        $this->add_control(
            'hotspot_items',
            [
                'label'       => esc_html__( 'Hotspot Items', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'pin_type'         => 'icon',
                        'pin_icon'         => [
                            'value'   => 'fas fa-plus',
                            'library' => 'fa-solid',
                        ],
                        'tooltip_subtitle' => 'FURNITURE',
                        'tooltip_title'    => 'Designer Lounge Chair',
                        'tooltip_price'    => '$289.00',
                        'tooltip_desc'     => 'Scandinavian crafted oak timber with breathable linen fabric.',
                        'tooltip_btn_text' => 'Shop Now',
                        'pos_x'            => [
                            'unit' => '%',
                            'size' => 28,
                        ],
                        'pos_y'            => [
                            'unit' => '%',
                            'size' => 38,
                        ],
                    ],
                    [
                        'pin_type'         => 'text',
                        'pin_text'         => '2',
                        'tooltip_subtitle' => 'LIGHTING',
                        'tooltip_title'    => 'Minimalist Brass Lamp',
                        'tooltip_price'    => '$95.00',
                        'tooltip_desc'     => 'Warm ambient glow with premium brushed gold brass finish.',
                        'tooltip_btn_text' => 'View Lamp',
                        'pos_x'            => [
                            'unit' => '%',
                            'size' => 68,
                        ],
                        'pos_y'            => [
                            'unit' => '%',
                            'size' => 22,
                        ],
                    ],
                    [
                        'pin_type'         => 'dot',
                        'tooltip_subtitle' => 'DECOR',
                        'tooltip_title'    => 'Handwoven Wool Rug',
                        'tooltip_price'    => '$149.00',
                        'tooltip_desc'     => 'Handmade organic New Zealand wool with plush tactile texture.',
                        'tooltip_btn_text' => 'Explore',
                        'pos_x'            => [
                            'unit' => '%',
                            'size' => 50,
                        ],
                        'pos_y'            => [
                            'unit' => '%',
                            'size' => 70,
                        ],
                    ],
                ],
                'title_field' => '{{{ pin_type === "text" ? pin_text : (tooltip_title ? tooltip_title : "Hotspot Item") }}}',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content Section: Settings & Behavior.
     */
    protected function register_settings_controls() {
        $this->start_controls_section(
            'section_settings',
            [
                'label' => esc_html__( 'Hotspot Settings', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'animation_type',
            [
                'label'        => esc_html__( 'Animation Effect', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SELECT,
                'default'      => 'pulse',
                'options'      => [
                    'pulse' => esc_html__( 'Pulse (Heartbeat Rings)', 'ultraaddons-elementor-lite' ),
                    'radar' => esc_html__( 'Radar (Sonar Waves)', 'ultraaddons-elementor-lite' ),
                    'glow'  => esc_html__( 'Soft Glow (Luminous Breathing)', 'ultraaddons-elementor-lite' ),
                    'none'  => esc_html__( 'None (Static)', 'ultraaddons-elementor-lite' ),
                ],
                'prefix_class' => 'ua-hotspot-anim-',
            ]
        );

        $this->add_control(
            'trigger',
            [
                'label'        => esc_html__( 'Tooltip Trigger', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SELECT,
                'default'      => 'hover',
                'options'      => [
                    'hover'  => esc_html__( 'On Hover', 'ultraaddons-elementor-lite' ),
                    'click'  => esc_html__( 'On Click', 'ultraaddons-elementor-lite' ),
                    'always' => esc_html__( 'Always Open', 'ultraaddons-elementor-lite' ),
                ],
                'prefix_class' => 'ua-hotspot-trigger-',
            ]
        );

        $this->add_control(
            'global_tooltip_pos',
            [
                'label'        => esc_html__( 'Global Tooltip Position', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SELECT,
                'default'      => 'top',
                'options'      => [
                    'top'    => esc_html__( 'Top', 'ultraaddons-elementor-lite' ),
                    'bottom' => esc_html__( 'Bottom', 'ultraaddons-elementor-lite' ),
                    'left'   => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                    'right'  => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                ],
                'prefix_class' => 'ua-hotspot-pos-',
            ]
        );

        $this->add_responsive_control(
            'tooltip_distance',
            [
                'label'      => esc_html__( 'Tooltip Distance / Spacing', 'ultraaddons-elementor-lite' ),
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
                    'size' => 12,
                ],
                'selectors'  => [
                    '{{WRAPPER}}' => '--ua-tooltip-offset: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'tooltip_arrow',
            [
                'label'        => esc_html__( 'Show Tooltip Arrow', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'prefix_class' => 'ua-hotspot-arrow-',
            ]
        );

        $this->add_control(
            'entrance_anim',
            [
                'label'        => esc_html__( 'Tooltip Entrance Animation', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SELECT,
                'default'      => 'zoom',
                'options'      => [
                    'fade'       => esc_html__( 'Fade In', 'ultraaddons-elementor-lite' ),
                    'zoom'       => esc_html__( 'Zoom In', 'ultraaddons-elementor-lite' ),
                    'slide-up'   => esc_html__( 'Slide Up', 'ultraaddons-elementor-lite' ),
                    'slide-down' => esc_html__( 'Slide Down', 'ultraaddons-elementor-lite' ),
                ],
                'prefix_class' => 'ua-hotspot-anim-in-',
            ]
        );

        $this->add_responsive_control(
            'tooltip_max_width',
            [
                'label'      => esc_html__( 'Tooltip Max Width (px)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 160,
                        'max'  => 500,
                        'step' => 5,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 260,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-hotspot-tooltip' => 'max-width: {{SIZE}}{{UNIT}}; width: max-content;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Section: Image.
     */
    protected function register_style_image_controls() {
        $this->start_controls_section(
            'section_style_image',
            [
                'label' => esc_html__( 'Main Image', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-image-hotspots, {{WRAPPER}} .ua-hotspot-image, {{WRAPPER}} .ua-hotspot-overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'image_box_shadow',
                'selector' => '{{WRAPPER}} .ua-image-hotspots',
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name'     => 'image_css_filters',
                'selector' => '{{WRAPPER}} .ua-hotspot-image',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Section: Hotspot Pin / Marker.
     */
    protected function register_style_pin_controls() {
        $this->start_controls_section(
            'section_style_pin',
            [
                'label' => esc_html__( 'Hotspot Marker Pin', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'pin_size',
            [
                'label'      => esc_html__( 'Marker Size (px)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 16,
                        'max'  => 80,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 32,
                ],
                'selectors'  => [
                    '{{WRAPPER}}' => '--ua-pin-size: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_responsive_control(
            'pin_icon_size',
            [
                'label'      => esc_html__( 'Icon / Text Font Size (px)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 8,
                        'max'  => 36,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 13,
                ],
                'selectors'  => [
                    '{{WRAPPER}}' => '--ua-pin-font-size: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_responsive_control(
            'pin_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'    => [
                    'top'      => 50,
                    'right'    => 50,
                    'bottom'   => 50,
                    'left'     => 50,
                    'unit'     => '%',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-hotspot-pin, {{WRAPPER}} .ua-hotspot-pulse-ring' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_pin_style' );

        // Normal Tab
        $this->start_controls_tab(
            'tab_pin_normal',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'pin_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#605BE5',
                'selectors' => [
                    '{{WRAPPER}}' => '--ua-pin-bg: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pin_color',
            [
                'label'     => esc_html__( 'Icon / Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}}' => '--ua-pin-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pin_pulse_color',
            [
                'label'     => esc_html__( 'Pulse / Radar Ring Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(96, 91, 229, 0.65)',
                'selectors' => [
                    '{{WRAPPER}}' => '--ua-pulse-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'pin_box_shadow',
                'selector' => '{{WRAPPER}} .ua-hotspot-pin',
            ]
        );

        $this->end_controls_tab();

        // Hover / Active Tab
        $this->start_controls_tab(
            'tab_pin_hover',
            [
                'label' => esc_html__( 'Hover / Active', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'pin_bg_hover',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4944d1',
                'selectors' => [
                    '{{WRAPPER}} .ua-hotspot-item:hover .ua-hotspot-pin, {{WRAPPER}} .ua-hotspot-item.ua-active .ua-hotspot-pin' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pin_color_hover',
            [
                'label'     => esc_html__( 'Icon / Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-hotspot-item:hover .ua-hotspot-pin, {{WRAPPER}} .ua-hotspot-item.ua-active .ua-hotspot-pin' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /**
     * Style Section: Tooltip Box.
     */
    protected function register_style_tooltip_controls() {
        $this->start_controls_section(
            'section_style_tooltip_box',
            [
                'label' => esc_html__( 'Tooltip Card Container', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'tooltip_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-hotspot-tooltip',
                'default'  => [
                    'background' => 'classic',
                    'color'      => '#ffffff',
                ],
            ]
        );

        $this->add_control(
            'enable_glassmorphism',
            [
                'label'        => esc_html__( 'Frosted Glass Effect', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => '',
                'selectors'    => [
                    '{{WRAPPER}} .ua-hotspot-tooltip' => 'backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); background-color: rgba(255, 255, 255, 0.82);',
                ],
            ]
        );

        $this->add_responsive_control(
            'tooltip_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'top'      => 16,
                    'right'    => 18,
                    'bottom'   => 16,
                    'left'     => 18,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-hotspot-tooltip' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tooltip_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'    => [
                    'top'      => 10,
                    'right'    => 10,
                    'bottom'   => 10,
                    'left'     => 10,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-hotspot-tooltip' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'tooltip_border',
                'selector' => '{{WRAPPER}} .ua-hotspot-tooltip',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'tooltip_box_shadow',
                'selector' => '{{WRAPPER}} .ua-hotspot-tooltip',
                'default'  => [
                    'horizontal' => 0,
                    'vertical'   => 10,
                    'blur'       => 28,
                    'spread'     => 0,
                    'color'      => 'rgba(0, 0, 0, 0.12)',
                ],
            ]
        );

        $this->add_control(
            'tooltip_arrow_color',
            [
                'label'     => esc_html__( 'Arrow Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}}' => '--ua-arrow-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Section: Tooltip Content Typography & Colors.
     */
    protected function register_style_content_controls() {
        $this->start_controls_section(
            'section_style_tooltip_content',
            [
                'label' => esc_html__( 'Tooltip Typography & Content', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Subtitle
        $this->add_control(
            'heading_subtitle',
            [
                'label'     => esc_html__( 'Subtitle / Category', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label'     => esc_html__( 'Subtitle Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#605BE5',
                'selectors' => [
                    '{{WRAPPER}} .ua-hotspot-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .ua-hotspot-subtitle',
            ]
        );

        // Title
        $this->add_control(
            'heading_title',
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
                'default'   => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .ua-hotspot-title, {{WRAPPER}} .ua-hotspot-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .ua-hotspot-title',
            ]
        );

        // Price
        $this->add_control(
            'heading_price',
            [
                'label'     => esc_html__( 'Price', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label'     => esc_html__( 'Price Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#10b981',
                'selectors' => [
                    '{{WRAPPER}} .ua-hotspot-price, {{WRAPPER}} .ua-hotspot-price ins, {{WRAPPER}} .ua-hotspot-price .woocommerce-Price-amount' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'price_typography',
                'selector' => '{{WRAPPER}} .ua-hotspot-price',
            ]
        );

        // Description
        $this->add_control(
            'heading_desc',
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
                'default'   => '#6b7280',
                'selectors' => [
                    '{{WRAPPER}} .ua-hotspot-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'desc_typography',
                'selector' => '{{WRAPPER}} .ua-hotspot-desc',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Section: Tooltip Button / CTA.
     */
    protected function register_style_button_controls() {
        $this->start_controls_section(
            'section_style_button',
            [
                'label' => esc_html__( 'Tooltip Button / Action', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'btn_typography',
                'selector' => '{{WRAPPER}} .ua-hotspot-btn',
            ]
        );

        $this->add_responsive_control(
            'btn_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'top'      => 8,
                    'right'    => 14,
                    'bottom'   => 8,
                    'left'     => 14,
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-hotspot-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'    => [
                    'top'      => 6,
                    'right'    => 6,
                    'bottom'   => 6,
                    'left'     => 6,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-hotspot-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_btn_style' );

        // Normal
        $this->start_controls_tab(
            'tab_btn_normal',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'btn_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-hotspot-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#605BE5',
                'selectors' => [
                    '{{WRAPPER}} .ua-hotspot-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'btn_border',
                'selector' => '{{WRAPPER}} .ua-hotspot-btn',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'btn_box_shadow',
                'selector' => '{{WRAPPER}} .ua-hotspot-btn',
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
            'btn_color_hover',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-hotspot-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_bg_hover',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4944d1',
                'selectors' => [
                    '{{WRAPPER}} .ua-hotspot-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_border_color_hover',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-hotspot-btn:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /**
     * Render widget output on frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['image']['url'] ) ) {
            return;
        }

        $items = ! empty( $settings['hotspot_items'] ) ? $settings['hotspot_items'] : [];

        $trigger       = ! empty( $settings['trigger'] ) ? $settings['trigger'] : 'hover';
        $global_pos    = ! empty( $settings['global_tooltip_pos'] ) ? $settings['global_tooltip_pos'] : 'top';
        $entrance_anim = ! empty( $settings['entrance_anim'] ) ? $settings['entrance_anim'] : 'zoom';

        $this->add_render_attribute( 'wrapper', 'class', [
            'ua-image-hotspots-wrap',
            'ua-hotspot-trigger-' . $trigger,
            'ua-hotspot-pos-' . $global_pos,
            'ua-hotspot-anim-in-' . $entrance_anim,
        ] );

        $this->add_render_attribute( 'wrapper', 'data-trigger', $trigger );
        $this->add_render_attribute( 'wrapper', 'data-anim-in', $entrance_anim );
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <div class="ua-image-hotspots">
                
                <!-- Main Image -->
                <div class="ua-hotspot-image-wrap">
                    <?php
                    if ( ! empty( $settings['image']['id'] ) ) {
                        echo wp_get_attachment_image(
                            $settings['image']['id'],
                            $settings['image_size_size'],
                            false,
                            [ 'class' => 'ua-hotspot-image' ]
                        );
                    } else {
                        ?>
                        <img class="ua-hotspot-image" src="<?php echo esc_url( $settings['image']['url'] ); ?>" alt="<?php echo esc_attr( $this->get_title() ); ?>">
                        <?php
                    }
                    ?>
                    <?php if ( 'yes' === $settings['enable_overlay'] ) : ?>
                        <div class="ua-hotspot-overlay"></div>
                    <?php endif; ?>
                </div>

                <!-- Hotspot Markers -->
                <div class="ua-hotspots-container">
                    <?php
                    foreach ( $items as $index => $item ) :
                        $item_key   = 'item_' . $index;
                        $pos_class  = ! empty( $item['item_tooltip_pos'] ) && 'global' !== $item['item_tooltip_pos']
                            ? 'ua-pos-' . esc_attr( $item['item_tooltip_pos'] )
                            : '';

                        $is_active = ( 'always' === $trigger ) ? 'ua-active' : '';

                        $this->add_render_attribute( $item_key, 'class', [
                            'ua-hotspot-item',
                            'elementor-repeater-item-' . esc_attr( $item['_id'] ),
                            $pos_class,
                            $is_active,
                        ] );

                        $this->add_render_attribute( $item_key, 'data-index', $index );
                        ?>
                        <div <?php echo $this->get_render_attribute_string( $item_key ); ?>>
                            
                            <!-- Marker Pin -->
                            <?php if ( 'link' === $item['action_type'] && ! empty( $item['pin_link']['url'] ) ) : ?>
                                <?php
                                $link_key = 'pin_link_' . $index;
                                $this->add_link_attributes( $link_key, $item['pin_link'] );
                                ?>
                                <a <?php echo $this->get_render_attribute_string( $link_key ); ?> class="ua-hotspot-pin-wrap" aria-label="<?php echo esc_attr( ! empty( $item['tooltip_title'] ) ? $item['tooltip_title'] : 'Hotspot' ); ?>">
                                    <?php $this->render_marker_pin( $item ); ?>
                                </a>
                            <?php else : ?>
                                <button type="button" class="ua-hotspot-pin-wrap" aria-expanded="<?php echo ( 'always' === $trigger ) ? 'true' : 'false'; ?>" aria-label="<?php echo esc_attr( ! empty( $item['tooltip_title'] ) ? $item['tooltip_title'] : 'Hotspot' ); ?>">
                                    <?php $this->render_marker_pin( $item ); ?>
                                </button>
                            <?php endif; ?>

                            <!-- Tooltip Card (when action_type is tooltip) -->
                            <?php if ( 'tooltip' === $item['action_type'] ) : ?>
                                <div class="ua-hotspot-tooltip" role="tooltip">
                                    <div class="ua-hotspot-tooltip-inner">
                                        <?php
                                        if ( 'product' === $item['content_source'] && ! empty( $item['product_id'] ) ) {
                                            $this->render_product_content( $item );
                                        } else {
                                            $this->render_custom_content( $item, $index );
                                        }
                                        ?>
                                    </div>
                                    <?php if ( 'yes' === $settings['tooltip_arrow'] ) : ?>
                                        <span class="ua-hotspot-arrow" aria-hidden="true"></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                        </div>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>
        <?php
    }

    /**
     * Render Marker Pin (Icon, Text, or Dot) with Pulse Ring.
     */
    protected function render_marker_pin( $item ) {
        ?>
        <span class="ua-hotspot-pin">
            <?php if ( 'icon' === $item['pin_type'] && ! empty( $item['pin_icon']['value'] ) ) : ?>
                <span class="ua-pin-icon">
                    <?php Icons_Manager::render_icon( $item['pin_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                </span>
            <?php elseif ( 'text' === $item['pin_type'] && ! empty( $item['pin_text'] ) ) : ?>
                <span class="ua-pin-text"><?php echo esc_html( $item['pin_text'] ); ?></span>
            <?php else : ?>
                <span class="ua-pin-dot"></span>
            <?php endif; ?>
        </span>
        <span class="ua-hotspot-pulse-ring" aria-hidden="true"></span>
        <?php
    }

    /**
     * Render Custom Tooltip Content.
     */
    protected function render_custom_content( $item, $index ) {
        ?>
        <?php if ( ! empty( $item['tooltip_image']['url'] ) ) : ?>
            <div class="ua-hotspot-thumb">
                <img src="<?php echo esc_url( $item['tooltip_image']['url'] ); ?>" alt="<?php echo esc_attr( ! empty( $item['tooltip_title'] ) ? $item['tooltip_title'] : '' ); ?>">
            </div>
        <?php endif; ?>

        <div class="ua-hotspot-body">
            <?php if ( ! empty( $item['tooltip_subtitle'] ) ) : ?>
                <span class="ua-hotspot-subtitle"><?php echo esc_html( $item['tooltip_subtitle'] ); ?></span>
            <?php endif; ?>

            <?php if ( ! empty( $item['tooltip_title'] ) ) : ?>
                <h4 class="ua-hotspot-title"><?php echo esc_html( $item['tooltip_title'] ); ?></h4>
            <?php endif; ?>

            <?php if ( ! empty( $item['tooltip_price'] ) ) : ?>
                <div class="ua-hotspot-price"><?php echo esc_html( $item['tooltip_price'] ); ?></div>
            <?php endif; ?>

            <?php if ( ! empty( $item['tooltip_desc'] ) ) : ?>
                <div class="ua-hotspot-desc"><?php echo wp_kses_post( $item['tooltip_desc'] ); ?></div>
            <?php endif; ?>

            <?php if ( ! empty( $item['tooltip_btn_text'] ) ) : ?>
                <?php
                $btn_key = 'btn_' . $index;
                $this->add_render_attribute( $btn_key, 'class', 'ua-hotspot-btn' );

                if ( ! empty( $item['tooltip_btn_url']['url'] ) ) {
                    $this->add_link_attributes( $btn_key, $item['tooltip_btn_url'] );
                } else {
                    $this->add_render_attribute( $btn_key, 'href', '#' );
                }
                ?>
                <a <?php echo $this->get_render_attribute_string( $btn_key ); ?>>
                    <span><?php echo esc_html( $item['tooltip_btn_text'] ); ?></span>
                </a>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Render WooCommerce Product Content inside Tooltip.
     */
    protected function render_product_content( $item ) {
        if ( ! function_exists( 'wc_get_product' ) ) {
            return;
        }

        $product_id = absint( $item['product_id'] );
        $product    = wc_get_product( $product_id );

        if ( ! is_object( $product ) ) {
            ?>
            <div class="ua-hotspot-body">
                <p class="ua-hotspot-desc"><?php esc_html_e( 'Product not found.', 'ultraaddons-elementor-lite' ); ?></p>
            </div>
            <?php
            return;
        }

        $product_title = $product->get_name();
        $product_url   = get_permalink( $product_id );
        ?>
        <div class="ua-hotspot-product-card">
            <?php if ( 'yes' === $item['show_product_image'] && $product->get_image_id() ) : ?>
                <div class="ua-hotspot-thumb">
                    <a href="<?php echo esc_url( $product_url ); ?>" tabindex="-1">
                        <?php echo $product->get_image( 'woocommerce_thumbnail' ); ?>
                    </a>
                </div>
            <?php endif; ?>

            <div class="ua-hotspot-body">
                <span class="ua-hotspot-subtitle"><?php echo esc_html( wc_get_product_category_list( $product_id, ', ' ) ); ?></span>
                
                <h4 class="ua-hotspot-title">
                    <a href="<?php echo esc_url( $product_url ); ?>">
                        <?php echo esc_html( $product_title ); ?>
                    </a>
                </h4>

                <?php if ( 'yes' === $item['show_product_price'] ) : ?>
                    <div class="ua-hotspot-price">
                        <?php echo $product->get_price_html(); ?>
                    </div>
                <?php endif; ?>

                <?php if ( 'yes' === $item['show_add_to_cart'] ) : ?>
                    <div class="ua-hotspot-product-action">
                        <?php
                        woocommerce_template_loop_add_to_cart( [
                            'class' => 'ua-hotspot-btn ua-hotspot-cart-btn button add_to_cart_button ajax_add_to_cart',
                        ] );
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}
