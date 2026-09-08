<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * UltraAddons Advanced Google Map Widget
 *
 * A smart, modular, high-performance Google Map widget featuring:
 * - Smart Zero-Key Embed Mode (instant responsive setup with CSS filter styling)
 * - Advanced JavaScript API Mode (multi-marker pins, rich InfoWindow cards)
 * - Curated Ultra Map Themes (Silver, Midnight, Retro, Cobalt, Aubergine) + Custom JSON
 * - Interactive Location Directory Switcher Tabs
 * - Auto-fit bounding box & smooth panning
 *
 * @package UltraAddons
 * @author Saiful Islam <codersaiful@gmail.com>
 * @version 1.0.0
 */
class Google_Map extends Base {

    /**
     * Constructor: registers widget specific CSS and JS
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        $css_file = ULTRA_ADDONS_DIR . 'assets/css/widgets/google-map.css';
        $css_ver  = file_exists( $css_file ) ? filemtime( $css_file ) : ULTRA_ADDONS_VERSION;

        wp_register_style(
            'ultraaddons-google-map',
            ULTRA_ADDONS_ASSETS . 'css/widgets/google-map.css',
            [],
            $css_ver,
            'all'
        );

        $js_file = ULTRA_ADDONS_DIR . 'assets/js/frontend-google-map.js';
        $js_ver  = file_exists( $js_file ) ? filemtime( $js_file ) : ULTRA_ADDONS_VERSION;

        wp_register_script(
            'frontend-google-map',
            ULTRA_ADDONS_ASSETS . 'js/frontend-google-map.js',
            [ 'jquery' ],
            $js_ver,
            true
        );
    }

    /**
     * Retrieve widget name
     *
     * @return string
     */
    public function get_name() {
        return 'ultraaddons-google-map';
    }

    /**
     * Retrieve widget title
     *
     * @return string
     */
    public function get_title() {
        return esc_html__( 'Google Map', 'ultraaddons-elementor-lite' );
    }

    /**
     * Retrieve widget icon
     *
     * @return string
     */
    public function get_icon() {
        return 'ultraaddons eicon-google-maps';
    }

    /**
     * Retrieve widget categories
     *
     * @return array
     */
    public function get_categories() {
        return [ 'ultraaddons' ];
    }

    /**
     * Optimized markup experiment compatibility for Elementor containers
     *
     * @return bool
     */
    public function has_widget_inner_wrapper(): bool {
        return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
    }

    /**
     * Retrieve search keywords
     *
     * @return array
     */
    public function get_keywords() {
        return [ 'ultraaddons', 'google map', 'map', 'embed', 'location', 'places', 'marker', 'gmap' ];
    }

    /**
     * Retrieve style dependencies
     *
     * @return array
     */
    public function get_style_depends() {
        return [ 'ultraaddons-google-map' ];
    }

    /**
     * Retrieve script dependencies
     *
     * @return array
     */
    public function get_script_depends() {
        return [ 'frontend-google-map' ];
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        $this->register_general_controls();
        $this->register_locations_controls();
        $this->register_map_ui_controls();
        $this->register_directory_controls();
        $this->register_style_container_controls();
        $this->register_style_theme_controls();
        $this->register_style_directory_controls();
        $this->register_style_card_controls();
    }

    /**
     * CONTENT TAB: General Settings
     */
    protected function register_general_controls() {
        $this->start_controls_section(
            'section_ua_gmap_general',
            [
                'label' => esc_html__( 'General', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ua_gmap_integration',
            [
                'label'   => esc_html__( 'Integration Mode', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'api_key',
                'options' => [
                    'api_key'         => esc_html__( 'API Key', 'ultraaddons-elementor-lite' ),
                    'without_api_key' => esc_html__( 'Without API Key', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        // API Key input for JS API mode
        $global_key = get_option( 'ultraaddons_google_map_api_key', '' );
        if ( empty( $global_key ) ) {
            $global_key = get_option( 'wpr_google_map_api_key', '' ); // Smart compatibility check
        }

        $this->add_control(
            'ua_gmap_api_key',
            [
                'label'       => esc_html__( 'Google Maps API Key', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => $global_key,
                'placeholder' => esc_html__( 'AIzaSy...', 'ultraaddons-elementor-lite' ),
                'description' => esc_html__( 'Enter your Google Cloud API key with Maps JavaScript API enabled.', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'ua_gmap_integration' => 'api_key',
                ],
            ]
        );

        // Embed Mode: Address / Coordinates
        $this->add_control(
            'ua_gmap_embed_address',
            [
                'label'       => esc_html__( 'Location Address', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'Eiffel Tower, Paris, France',
                'placeholder' => esc_html__( 'e.g. 10 Downing Street, London, UK', 'ultraaddons-elementor-lite' ),
                'label_block' => true,
                'dynamic'     => [ 'active' => true ],
                'condition'   => [
                    'ua_gmap_integration' => [ 'without_api_key', 'embed' ],
                ],
            ]
        );

        $this->add_control(
            'ua_gmap_embed_coords',
            [
                'label'       => esc_html__( 'Coordinates (Lat, Long)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'placeholder' => 'e.g. 24.3745, 88.6042',
                'description' => esc_html__( 'Optional: Enter latitude, longitude if not using location address.', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'condition'   => [
                    'ua_gmap_integration' => [ 'without_api_key', 'embed' ],
                ],
            ]
        );

        // Map Type
        $this->add_control(
            'ua_gmap_type',
            [
                'label'     => esc_html__( 'Map Type', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'roadmap',
                'options'   => [
                    'roadmap'   => esc_html__( 'Roadmap', 'ultraaddons-elementor-lite' ),
                    'satellite' => esc_html__( 'Satellite', 'ultraaddons-elementor-lite' ),
                    'hybrid'    => esc_html__( 'Hybrid', 'ultraaddons-elementor-lite' ),
                    'terrain'   => esc_html__( 'Terrain', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        // Zoom Level
        $this->add_control(
            'ua_gmap_zoom',
            [
                'label'   => esc_html__( 'Zoom Level', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 14,
                ],
                'range'   => [
                    'px' => [
                        'min'  => 1,
                        'max'  => 20,
                        'step' => 1,
                    ],
                ],
            ]
        );

        // Map Height
        $this->add_responsive_control(
            'ua_gmap_height',
            [
                'label'       => esc_html__( 'Map Height', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => [ 'px', 'vh' ],
                'default'     => [
                    'size' => 460,
                    'unit' => 'px',
                ],
                'range'       => [
                    'px' => [
                        'min'  => 200,
                        'max'  => 1200,
                        'step' => 10,
                    ],
                    'vh' => [
                        'min'  => 20,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'selectors'   => [
                    '{{WRAPPER}} .ua-gmap-container' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ua_gmap_embed_fullscreen',
            [
                'label'        => esc_html__( 'Show FullScreen Button', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
                'separator'    => 'before',
                'condition'    => [
                    'ua_gmap_integration' => [ 'without_api_key', 'embed' ],
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * CONTENT TAB: Locations (JS API Mode)
     */
    protected function register_locations_controls() {
        $this->start_controls_section(
            'section_ua_gmap_locations',
            [
                'label'     => esc_html__( 'Locations', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'ua_gmap_integration' => 'api_key',
                ],
            ]
        );

        $this->add_control(
            'ua_gmap_coords_helper',
            [
                'type'      => Controls_Manager::RAW_HTML,
                'raw'       => sprintf(
                    '<a href="https://www.latlong.net/" target="_blank" rel="noopener noreferrer" style="color:#4f46e5; font-weight:600; text-decoration:underline;">%s</a> %s',
                    esc_html__( 'Click Here', 'ultraaddons-elementor-lite' ),
                    esc_html__( 'to find Coordinates of your location.', 'ultraaddons-elementor-lite' )
                ),
                'separator' => 'after',
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'title',
            [
                'label'       => esc_html__( 'Location Title', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Eiffel Tower', 'ultraaddons-elementor-lite' ),
                'label_block' => true,
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $repeater->add_control(
            'badge',
            [
                'label'       => esc_html__( 'Badge / Tag', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Must Visit', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'e.g. Headquarters, Branch 1', 'ultraaddons-elementor-lite' ),
            ]
        );

        $repeater->add_control(
            'latitude',
            [
                'label'       => esc_html__( 'Latitude', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '48.858372',
                'placeholder' => '48.858372',
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $repeater->add_control(
            'longitude',
            [
                'label'       => esc_html__( 'Longitude', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '2.294481',
                'placeholder' => '2.294481',
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $repeater->add_control(
            'image',
            [
                'label'   => esc_html__( 'Popup Photo / Thumbnail', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::MEDIA,
                'dynamic' => [ 'active' => true ],
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label'       => esc_html__( 'Description', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__( 'Iconic wrought-iron lattice tower on the Champ de Mars in Paris.', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $repeater->add_control(
            'address',
            [
                'label'       => esc_html__( 'Full Address', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Champ de Mars, 5 Av. Anatole France, 75007 Paris, France', 'ultraaddons-elementor-lite' ),
                'label_block' => true,
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $repeater->add_control(
            'phone',
            [
                'label'       => esc_html__( 'Phone Number', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => '+33 892 70 12 39',
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $repeater->add_control(
            'custom_icon',
            [
                'label'        => esc_html__( 'Custom Marker Pin', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'no',
                'separator'    => 'before',
            ]
        );

        $repeater->add_control(
            'icon_image',
            [
                'label'     => esc_html__( 'Marker Icon Image / SVG', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::MEDIA,
                'condition' => [
                    'custom_icon' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'icon_width',
            [
                'label'     => esc_html__( 'Marker Icon Width (px)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 36,
                'min'       => 16,
                'max'       => 120,
                'condition' => [
                    'custom_icon' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'icon_height',
            [
                'label'     => esc_html__( 'Marker Icon Height (px)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 36,
                'min'       => 16,
                'max'       => 120,
                'condition' => [
                    'custom_icon' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'animation',
            [
                'label'     => esc_html__( 'Marker Animation', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'drop',
                'options'   => [
                    'none'   => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                    'drop'   => esc_html__( 'Drop Down', 'ultraaddons-elementor-lite' ),
                    'bounce' => esc_html__( 'Continuous Bounce', 'ultraaddons-elementor-lite' ),
                ],
                'separator' => 'before',
            ]
        );

        $repeater->add_control(
            'info_trigger',
            [
                'label'   => esc_html__( 'Info Popup Trigger', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'click',
                'options' => [
                    'click' => esc_html__( 'On Marker Click', 'ultraaddons-elementor-lite' ),
                    'load'  => esc_html__( 'Open by Default on Page Load', 'ultraaddons-elementor-lite' ),
                    'none'  => esc_html__( 'Disable Info Popup', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $repeater->add_control(
            'card_width',
            [
                'label'     => esc_html__( 'Popup Max Width (px)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 300,
                'min'       => 180,
                'max'       => 500,
                'condition' => [
                    'info_trigger!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'ua_gmap_locations',
            [
                'label'       => esc_html__( 'Locations', 'ultraaddons-elementor-lite' ),
                'show_label'  => false,
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'title'        => esc_html__( 'Eiffel Tower', 'ultraaddons-elementor-lite' ),
                        'badge'        => esc_html__( 'Must Visit', 'ultraaddons-elementor-lite' ),
                        'latitude'     => '48.858372',
                        'longitude'    => '2.294481',
                        'address'      => esc_html__( 'Champ de Mars, 5 Av. Anatole France, 75007 Paris', 'ultraaddons-elementor-lite' ),
                        'description'  => esc_html__( 'Iconic wrought-iron lattice tower on the Champ de Mars in Paris.', 'ultraaddons-elementor-lite' ),
                        'animation'    => 'drop',
                        'info_trigger' => 'click',
                    ],
                    [
                        'title'        => esc_html__( 'Louvre Museum', 'ultraaddons-elementor-lite' ),
                        'badge'        => esc_html__( 'Art & Culture', 'ultraaddons-elementor-lite' ),
                        'latitude'     => '48.860611',
                        'longitude'    => '2.337644',
                        'address'      => esc_html__( 'Rue de Rivoli, 75001 Paris, France', 'ultraaddons-elementor-lite' ),
                        'description'  => esc_html__( 'World largest art museum and historic monument in Paris.', 'ultraaddons-elementor-lite' ),
                        'animation'    => 'drop',
                        'info_trigger' => 'click',
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * CONTENT TAB: Interactive Location Directory Switcher
     */
    protected function register_directory_controls() {
        $this->start_controls_section(
            'section_ua_gmap_directory',
            [
                'label' => esc_html__( 'Location Switcher Tabs', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ua_gmap_show_directory',
            [
                'label'        => esc_html__( 'Enable Location Tabs', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'no',
                'description'  => esc_html__( 'Display interactive clickable pill tabs to quickly pan and focus on locations.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_gmap_directory_position',
            [
                'label'     => esc_html__( 'Tabs Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'top',
                'options'   => [
                    'top'    => esc_html__( 'Top (Above Map)', 'ultraaddons-elementor-lite' ),
                    'bottom' => esc_html__( 'Bottom (Below Map)', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'ua_gmap_show_directory' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * CONTENT TAB: Map UI Controls (JS API Mode)
     */
    protected function register_map_ui_controls() {
        $this->start_controls_section(
            'section_ua_gmap_controls',
            [
                'label'     => esc_html__( 'Controls', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'ua_gmap_integration' => 'api_key',
                ],
            ]
        );

        $this->add_control(
            'ua_gmap_control_type',
            [
                'label'   => esc_html__( 'Show Map Type Control', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'ua_gmap_control_fullscreen',
            [
                'label'   => esc_html__( 'Show FullScreen Control', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'ua_gmap_control_zoom',
            [
                'label'   => esc_html__( 'Show Zoom Control', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'ua_gmap_control_streetview',
            [
                'label'   => esc_html__( 'Show Street View Control', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'ua_gmap_gesture_handling',
            [
                'label'       => esc_html__( 'Gesture Handling', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'cooperative',
                'options'     => [
                    'cooperative' => esc_html__( 'Cooperative (2-finger scroll on mobile)', 'ultraaddons-elementor-lite' ),
                    'greedy'      => esc_html__( 'Greedy (Always zoom on scroll)', 'ultraaddons-elementor-lite' ),
                    'none'        => esc_html__( 'None (Disable map gestures)', 'ultraaddons-elementor-lite' ),
                ],
                'description' => esc_html__( '"Cooperative" avoids trapping users on mobile while scrolling through the page.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->end_controls_section();
    }

    /**
     * STYLE TAB: Map Container & CSS Filters
     */
    protected function register_style_container_controls() {
        $this->start_controls_section(
            'section_style_ua_gmap_container',
            [
                'label' => esc_html__( 'Map Container & Effects', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_gmap_border',
                'selector' => '{{WRAPPER}} .ua-gmap-container',
            ]
        );

        $this->add_control(
            'ua_gmap_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-gmap-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_gmap_box_shadow',
                'selector' => '{{WRAPPER}} .ua-gmap-container',
            ]
        );

        $this->add_control(
            'heading_ua_gmap_filters',
            [
                'label'     => esc_html__( 'CSS Filter Effects (Works on Embed & JS)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'ua_gmap_filter_grayscale',
            [
                'label'     => esc_html__( 'Grayscale (%)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [ 'min' => 0, 'max' => 100, 'step' => 5 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-gmap-canvas, {{WRAPPER}} .ua-gmap-iframe' => 'filter: grayscale({{SIZE}}%);',
                ],
            ]
        );

        $this->add_control(
            'ua_gmap_filter_contrast',
            [
                'label'     => esc_html__( 'Contrast (%)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [ 'min' => 50, 'max' => 150, 'step' => 5 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-gmap-canvas, {{WRAPPER}} .ua-gmap-iframe' => 'filter: contrast({{SIZE}}%);',
                ],
            ]
        );

        $this->add_control(
            'ua_gmap_filter_invert',
            [
                'label'       => esc_html__( 'Invert / Dark Tone (%)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SLIDER,
                'range'       => [
                    'px' => [ 'min' => 0, 'max' => 100, 'step' => 5 ],
                ],
                'description' => esc_html__( 'Easily emulate a dark mode map look on Without API Key mode.', 'ultraaddons-elementor-lite' ),
                'selectors'   => [
                    '{{WRAPPER}} .ua-gmap-canvas, {{WRAPPER}} .ua-gmap-iframe' => 'filter: invert({{SIZE}}%);',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * STYLE TAB: Curated Ultra Themes (JS API Mode)
     */
    protected function register_style_theme_controls() {
        $this->start_controls_section(
            'section_style_ua_gmap_theme',
            [
                'label'     => esc_html__( 'Map Theme & Color Schemes', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ua_gmap_integration' => 'api_key',
                ],
            ]
        );

        $this->add_control(
            'ua_gmap_theme',
            [
                'label'   => esc_html__( 'Color Theme', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'ultra_silver',
                'options' => [
                    'ultra_default'   => esc_html__( 'Natural Google', 'ultraaddons-elementor-lite' ),
                    'ultra_silver'    => esc_html__( 'Ultra Clean Silver', 'ultraaddons-elementor-lite' ),
                    'ultra_midnight'  => esc_html__( 'Ultra Midnight Dark', 'ultraaddons-elementor-lite' ),
                    'ultra_retro'     => esc_html__( 'Ultra Warm Retro', 'ultraaddons-elementor-lite' ),
                    'ultra_cobalt'    => esc_html__( 'Ultra Cobalt Blue', 'ultraaddons-elementor-lite' ),
                    'ultra_aubergine' => esc_html__( 'Ultra Aubergine Violet', 'ultraaddons-elementor-lite' ),
                    'custom'          => esc_html__( 'Custom Snazzy Maps JSON', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'ua_gmap_custom_style',
            [
                'label'       => esc_html__( 'Snazzy Maps Custom JSON', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXTAREA,
                'placeholder' => '[ { "elementType": "geometry", ... } ]',
                'description' => sprintf(
                    '%s <a href="https://snazzymaps.com/" target="_blank" rel="noopener noreferrer" style="color:#4f46e5; text-decoration:underline;">Snazzy Maps</a> %s',
                    esc_html__( 'Copy any JSON style code from', 'ultraaddons-elementor-lite' ),
                    esc_html__( 'and paste it here.', 'ultraaddons-elementor-lite' )
                ),
                'condition'   => [
                    'ua_gmap_theme' => 'custom',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * STYLE TAB: Directory Switcher Tabs
     */
    protected function register_style_directory_controls() {
        $this->start_controls_section(
            'section_style_ua_gmap_directory',
            [
                'label'     => esc_html__( 'Location Switcher Tabs', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ua_gmap_show_directory' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_gmap_dir_align',
            [
                'label'     => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'flex-start' => [ 'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-left' ],
                    'center'     => [ 'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-center' ],
                    'flex-end'   => [ 'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default'   => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .ua-gmap-directory-bar' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_gmap_dir_typo',
                'selector' => '{{WRAPPER}} .ua-gmap-dir-pill',
            ]
        );

        $this->start_controls_tabs( 'ua_gmap_dir_tabs' );

        $this->start_controls_tab(
            'ua_gmap_dir_tab_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'ua_gmap_dir_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-gmap-dir-pill' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_gmap_dir_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-gmap-dir-pill' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_gmap_dir_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-gmap-dir-pill' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'ua_gmap_dir_tab_active',
            [ 'label' => esc_html__( 'Active / Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'ua_gmap_dir_active_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-gmap-dir-pill:hover, {{WRAPPER}} .ua-gmap-dir-pill.ua-gmap-dir-active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_gmap_dir_active_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-gmap-dir-pill:hover, {{WRAPPER}} .ua-gmap-dir-pill.ua-gmap-dir-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_gmap_dir_active_border',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-gmap-dir-pill:hover, {{WRAPPER}} .ua-gmap-dir-pill.ua-gmap-dir-active' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /**
     * STYLE TAB: Rich InfoWindow Card (JS API Mode)
     */
    protected function register_style_card_controls() {
        $this->start_controls_section(
            'section_style_ua_gmap_card',
            [
                'label'     => esc_html__( 'Info Popup Card', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ua_gmap_integration' => 'api_key',
                ],
            ]
        );

        $this->add_control(
            'ua_gmap_card_bg',
            [
                'label'     => esc_html__( 'Card Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gm-style .gm-style-iw-c' => 'background-color: {{VALUE}} !important;',
                    '{{WRAPPER}} .gm-style .gm-style-iw-tc::after' => 'background: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'ua_gmap_card_title_color',
            [
                'label'     => esc_html__( 'Title Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-gmap-card-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_gmap_card_title_typo',
                'selector' => '{{WRAPPER}} .ua-gmap-card-title',
            ]
        );

        $this->add_control(
            'ua_gmap_card_badge_bg',
            [
                'label'     => esc_html__( 'Badge Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-gmap-card-badge' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_gmap_card_badge_color',
            [
                'label'     => esc_html__( 'Badge Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-gmap-card-badge' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_gmap_card_btn_bg',
            [
                'label'     => esc_html__( '"Get Directions" Button Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-gmap-directions-btn' => 'background-color: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on frontend
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $raw_mode = ! empty( $settings['ua_gmap_integration'] ) ? $settings['ua_gmap_integration'] : 'api_key';
        $mode     = ( 'without_api_key' === $raw_mode || 'embed' === $raw_mode ) ? 'embed' : 'api_key';
        $zoom     = ! empty( $settings['ua_gmap_zoom']['size'] ) ? (int) $settings['ua_gmap_zoom']['size'] : 14;

        $show_dir = ( ! empty( $settings['ua_gmap_show_directory'] ) && 'yes' === $settings['ua_gmap_show_directory'] );
        $dir_pos  = ! empty( $settings['ua_gmap_directory_position'] ) ? $settings['ua_gmap_directory_position'] : 'top';

        // Smart Embed query resolution
        $address = ! empty( $settings['ua_gmap_embed_address'] ) ? trim( $settings['ua_gmap_embed_address'] ) : '';
        $coords  = ! empty( $settings['ua_gmap_embed_coords'] ) ? trim( $settings['ua_gmap_embed_coords'] ) : '';

        // Priority: When address is entered, it should always be used.
        // Ignore legacy Paris coords default ('48.858372, 2.294481') so typed address is never blocked.
        $legacy_paris = '48.858372, 2.294481';
        if ( ! empty( $address ) && ( empty( $coords ) || $coords === $legacy_paris ) ) {
            $embed_query = $address;
        } elseif ( ! empty( $coords ) && $coords !== $legacy_paris ) {
            $embed_query = $coords;
        } elseif ( ! empty( $address ) ) {
            $embed_query = $address;
        } elseif ( ! empty( $coords ) ) {
            $embed_query = $coords;
        } else {
            $embed_query = 'London Eye, London, United Kingdom';
        }

        // Prepare Directory HTML
        $directory_html = '';
        if ( $show_dir ) {
            $directory_html .= '<div class="ua-gmap-directory-bar">';
            if ( 'embed' === $mode ) {
                $directory_html .= '<button type="button" class="ua-gmap-dir-pill ua-gmap-dir-active" data-query="' . esc_attr( $embed_query ) . '" data-zoom="' . (int) $zoom . '">';
                $directory_html .= '<span class="ua-gmap-pill-title">' . esc_html( $address ?: $embed_query ) . '</span>';
                $directory_html .= '</button>';
            } else {
                $locations = ! empty( $settings['ua_gmap_locations'] ) ? $settings['ua_gmap_locations'] : [];
                foreach ( $locations as $idx => $loc ) {
                    $active_class = ( 0 === $idx ) ? ' ua-gmap-dir-active' : '';
                    $directory_html .= '<button type="button" class="ua-gmap-dir-pill' . $active_class . '" data-index="' . (int) $idx . '">';
                    if ( ! empty( $loc['badge'] ) ) {
                        $directory_html .= '<span class="ua-gmap-pill-badge">' . esc_html( $loc['badge'] ) . '</span>';
                    }
                    $directory_html .= '<span class="ua-gmap-pill-title">' . esc_html( $loc['title'] ?: 'Location ' . ( $idx + 1 ) ) . '</span>';
                    $directory_html .= '</button>';
                }
            }
            $directory_html .= '</div>';
        }

        $wrapper_classes = [
            'ua-google-map-wrapper',
            'ua-gmap-mode-' . $mode,
            'ua-gmap-dir-pos-' . $dir_pos,
        ];

        echo '<div class="' . esc_attr( implode( ' ', $wrapper_classes ) ) . '" data-mode="' . esc_attr( $mode ) . '"';

        if ( 'embed' === $mode ) {
            echo '>';
            if ( 'top' === $dir_pos ) {
                echo $directory_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }

            $type_param = '';
            if ( 'satellite' === $settings['ua_gmap_type'] ) {
                $type_param = '&t=k';
            } elseif ( 'hybrid' === $settings['ua_gmap_type'] ) {
                $type_param = '&t=h';
            } elseif ( 'terrain' === $settings['ua_gmap_type'] ) {
                $type_param = '&t=p';
            } else {
                $type_param = '&t=m';
            }

            $embed_url = 'https://maps.google.com/maps?q=' . rawurlencode( $embed_query ) . '&z=' . (int) $zoom . $type_param . '&output=embed&iwloc=near';

            $show_fs = true;
            if ( isset( $settings['ua_gmap_embed_fullscreen'] ) ) {
                $show_fs = ( 'yes' === $settings['ua_gmap_embed_fullscreen'] );
            } elseif ( isset( $settings['ua_gmap_control_fullscreen'] ) ) {
                $show_fs = ( 'yes' === $settings['ua_gmap_control_fullscreen'] );
            }

            echo '<div class="ua-gmap-container">';
            if ( $show_fs ) {
                echo '<button type="button" class="ua-gmap-fs-toggle" title="' . esc_attr__( 'Toggle Fullscreen', 'ultraaddons-elementor-lite' ) . '" aria-label="' . esc_attr__( 'Toggle Fullscreen', 'ultraaddons-elementor-lite' ) . '">';
                echo '<svg class="ua-fs-icon-open" viewBox="0 0 18 18" width="18" height="18" aria-hidden="true"><path fill="#555" d="M0 0v6h2V2h4V0H0zm16 0h-4v2h4v4h2V0h-2zm0 16h-4v2h6v-6h-2v4zM2 12H0v6h6v-2H2v-4z"/></svg>';
                echo '<svg class="ua-fs-icon-close" viewBox="0 0 18 18" width="18" height="18" aria-hidden="true" style="display:none;"><path fill="#555" d="M4 4h2v2H4V4zm8 0h2v2h-2V4zM4 12h2v2H4v-2zm8 0h2v2h-2v-2zM0 0v4h2V2h2V0H0zm18 0h-4v2h2v2h2V0zm0 18v-4h-2v2h-2v2h4zM0 18h4v-2H2v-2H0v4z"/></svg>';
                echo '</button>';
            }
            echo '<iframe class="ua-gmap-iframe" src="' . esc_url( $embed_url ) . '" width="100%" height="100%" style="border:0; width:100%; height:100%;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="' . esc_attr__( 'Google Map', 'ultraaddons-elementor-lite' ) . '"></iframe>';
            echo '</div>';

            if ( 'bottom' === $dir_pos ) {
                echo $directory_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
            echo '</div>';
        } else {
            // Advanced JS API Mode
            $api_key = ! empty( $settings['ua_gmap_api_key'] ) ? trim( $settings['ua_gmap_api_key'] ) : get_option( 'ultraaddons_google_map_api_key', '' );
            if ( empty( $api_key ) ) {
                $api_key = get_option( 'wpr_google_map_api_key', '' );
            }

            $map_settings = [
                'type'             => ! empty( $settings['ua_gmap_type'] ) ? $settings['ua_gmap_type'] : 'roadmap',
                'zoom'             => $zoom,
                'theme'            => ! empty( $settings['ua_gmap_theme'] ) ? $settings['ua_gmap_theme'] : 'ultra_silver',
                'custom_style'     => ! empty( $settings['ua_gmap_custom_style'] ) ? $settings['ua_gmap_custom_style'] : '',
                'gesture_handling' => ! empty( $settings['ua_gmap_gesture_handling'] ) ? $settings['ua_gmap_gesture_handling'] : 'cooperative',
            ];

            $map_controls = [
                'map_type'    => ( ! empty( $settings['ua_gmap_control_type'] ) && 'yes' === $settings['ua_gmap_control_type'] ),
                'fullscreen'  => ( ! empty( $settings['ua_gmap_control_fullscreen'] ) && 'yes' === $settings['ua_gmap_control_fullscreen'] ),
                'zoom'        => ( ! empty( $settings['ua_gmap_control_zoom'] ) && 'yes' === $settings['ua_gmap_control_zoom'] ),
                'street_view' => ( ! empty( $settings['ua_gmap_control_streetview'] ) && 'yes' === $settings['ua_gmap_control_streetview'] ),
            ];

            $locations_data = [];
            $raw_locations  = ! empty( $settings['ua_gmap_locations'] ) ? $settings['ua_gmap_locations'] : [];

            foreach ( $raw_locations as $loc ) {
                $item = [
                    'title'        => sanitize_text_field( $loc['title'] ?? '' ),
                    'badge'        => sanitize_text_field( $loc['badge'] ?? '' ),
                    'latitude'     => sanitize_text_field( $loc['latitude'] ?? '' ),
                    'longitude'    => sanitize_text_field( $loc['longitude'] ?? '' ),
                    'image_url'    => ! empty( $loc['image']['url'] ) ? esc_url( $loc['image']['url'] ) : '',
                    'description'  => sanitize_textarea_field( $loc['description'] ?? '' ),
                    'address'      => sanitize_text_field( $loc['address'] ?? '' ),
                    'phone'        => sanitize_text_field( $loc['phone'] ?? '' ),
                    'animation'    => sanitize_key( $loc['animation'] ?? 'drop' ),
                    'info_trigger' => sanitize_key( $loc['info_trigger'] ?? 'click' ),
                    'card_width'   => ! empty( $loc['card_width'] ) ? (int) $loc['card_width'] : 300,
                    'custom_icon'  => ( ! empty( $loc['custom_icon'] ) && 'yes' === $loc['custom_icon'] ),
                    'icon_url'     => ! empty( $loc['icon_image']['url'] ) ? esc_url( $loc['icon_image']['url'] ) : '',
                    'icon_width'   => ! empty( $loc['icon_width'] ) ? (int) $loc['icon_width'] : 36,
                    'icon_height'  => ! empty( $loc['icon_height'] ) ? (int) $loc['icon_height'] : 36,
                ];
                $locations_data[] = $item;
            }

            echo ' data-api-key="' . esc_attr( $api_key ) . '"';
            echo ' data-settings="' . esc_attr( wp_json_encode( $map_settings ) ) . '"';
            echo ' data-controls="' . esc_attr( wp_json_encode( $map_controls ) ) . '"';
            echo ' data-locations="' . esc_attr( wp_json_encode( $locations_data ) ) . '"';
            echo '>';

            if ( 'top' === $dir_pos ) {
                echo $directory_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }

            echo '<div class="ua-gmap-container">';
            echo '<div class="ua-gmap-canvas"></div>';
            echo '</div>';

            if ( 'bottom' === $dir_pos ) {
                echo $directory_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }

            echo '</div>';
        }
    }
}
