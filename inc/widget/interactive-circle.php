<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * UltraAddons Interactive Circle Widget
 *
 * A modern, performant, circular process and roadmap widget featuring:
 * - Dynamic trigonometry distribution (no CSS static position bloat)
 * - Rock-solid static center stage that never tilts or flips
 * - Counter-rotating node engine (keeps icons and labels upright at all times)
 * - 4 Visual Presets: Full Orbit, Half Moon Arch, Cyber Spoke Network, Minimal Flow
 * - Click and Hover triggers with smooth content transitions
 * - Autoplay rotation with pause-on-hover & continuous orbit animations
 * - Full SVG spoke lines connecting nodes to center
 * - Fully responsive with dynamic CSS custom properties
 *
 * @package UltraAddons
 * @author Saiful Islam <codersaiful@gmail.com>
 * @version 1.0.1
 */
class Interactive_Circle extends Base {

    /**
     * Constructor: register widget specific CSS and JS
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        $css_file = ULTRA_ADDONS_DIR . 'assets/css/widgets/interactive-circle.css';
        $css_ver  = file_exists( $css_file ) ? filemtime( $css_file ) : ULTRA_ADDONS_VERSION;

        wp_register_style(
            'ultraaddons-interactive-circle',
            ULTRA_ADDONS_ASSETS . 'css/widgets/interactive-circle.css',
            [],
            $css_ver,
            'all'
        );

        $js_file = ULTRA_ADDONS_DIR . 'assets/js/frontend-interactive-circle.js';
        $js_ver  = file_exists( $js_file ) ? filemtime( $js_file ) : ULTRA_ADDONS_VERSION;

        wp_register_script(
            'frontend-interactive-circle',
            ULTRA_ADDONS_ASSETS . 'js/frontend-interactive-circle.js',
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
        return 'ultraaddons-interactive-circle';
    }

    /**
     * Retrieve widget title
     *
     * @return string
     */
    public function get_title() {
        return esc_html__( 'Interactive Circle', 'ultraaddons-elementor-lite' );
    }

    /**
     * Retrieve widget icon
     *
     * @return string
     */
    public function get_icon() {
        return 'ultraaddons eicon-circle-o';
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
     * Retrieve search keywords
     *
     * @return array
     */
    public function get_keywords() {
        return [ 'ultraaddons', 'interactive circle', 'circle info', 'process', 'infographic', 'roadmap', 'cycle', 'timeline' ];
    }

    /**
     * Elementor optimized markup experiment compatibility
     *
     * @return bool
     */
    public function has_widget_inner_wrapper(): bool {
        return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
    }

    /**
     * Retrieve style dependencies
     *
     * @return array
     */
    public function get_style_depends() {
        return [ 'ultraaddons-interactive-circle' ];
    }

    /**
     * Retrieve script dependencies
     *
     * @return array
     */
    public function get_script_depends() {
        return [ 'frontend-interactive-circle' ];
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        $this->register_general_controls();
        $this->register_items_controls();
        $this->register_motion_controls();
        $this->register_style_orbit_controls();
        $this->register_style_nodes_controls();
        $this->register_style_center_controls();
        $this->register_style_button_controls();
    }

    /**
     * General Layout & Preset Controls
     */
    protected function register_general_controls() {
        $this->start_controls_section(
            'section_layout',
            [
                'label' => esc_html__( 'Layout & Preset', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'circle_preset',
            [
                'label'       => esc_html__( 'Style Preset', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'full_orbit',
                'options'     => [
                    'full_orbit'     => esc_html__( 'Full Orbit (360°)', 'ultraaddons-elementor-lite' ),
                    'half_moon'      => esc_html__( 'Half Moon (Arch)', 'ultraaddons-elementor-lite' ),
                    'cyber_spoke'    => esc_html__( 'Cyber Spoke Network', 'ultraaddons-elementor-lite' ),
                    'inward_pointer' => esc_html__( 'Inward Pointer Badges', 'ultraaddons-elementor-lite' ),
                    'minimal_flow'   => esc_html__( 'Minimal Flow', 'ultraaddons-elementor-lite' ),
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'trigger_type',
            [
                'label'       => esc_html__( 'Trigger On', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'click',
                'options'     => [
                    'click' => esc_html__( 'Mouse Click', 'ultraaddons-elementor-lite' ),
                    'hover' => esc_html__( 'Mouse Hover', 'ultraaddons-elementor-lite' ),
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'transition_effect',
            [
                'label'        => esc_html__( 'Content Transition', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SELECT,
                'default'      => 'fade',
                'options'      => [
                    'fade'     => esc_html__( 'Smooth Fade', 'ultraaddons-elementor-lite' ),
                    'zoom'     => esc_html__( 'Zoom In (Scale Pop)', 'ultraaddons-elementor-lite' ),
                    'slide_up' => esc_html__( 'Slide Up (Glide)', 'ultraaddons-elementor-lite' ),
                    'flip'     => esc_html__( '3D Flip (Perspective)', 'ultraaddons-elementor-lite' ),
                ],
                'prefix_class' => 'ua-ic-trans-',
                'render_type'  => 'template',
            ]
        );

        $this->add_control(
            'transition_duration',
            [
                'label'      => esc_html__( 'Transition Speed (ms)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'ms' ],
                'range'      => [
                    'ms' => [
                        'min'  => 150,
                        'max'  => 1000,
                        'step' => 25,
                    ],
                ],
                'default'    => [
                    'unit' => 'ms',
                    'size' => 350,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-interactive-circle-wrap' => '--ua-ic-trans-duration: {{SIZE}}ms;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Items Repeater Controls
     */
    protected function register_items_controls() {
        $this->start_controls_section(
            'section_items',
            [
                'label' => esc_html__( 'Circle Nodes', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'title',
            [
                'label'       => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Discovery', 'ultraaddons-elementor-lite' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'subtitle',
            [
                'label'       => esc_html__( 'Subtitle', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Phase 01', 'ultraaddons-elementor-lite' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'badge_type',
            [
                'label'   => esc_html__( 'Node Badge Type', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'icon'   => [
                        'title' => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-star',
                    ],
                    'number' => [
                        'title' => esc_html__( 'Number/Text', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-number-field',
                    ],
                ],
                'default' => 'icon',
                'toggle'  => false,
            ]
        );

        $repeater->add_control(
            'node_icon',
            [
                'label'     => esc_html__( 'Node Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'fas fa-compass',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'badge_type' => 'icon',
                ],
            ]
        );

        $repeater->add_control(
            'node_number',
            [
                'label'     => esc_html__( 'Node Number/Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => '01',
                'condition' => [
                    'badge_type' => 'number',
                ],
            ]
        );

        $repeater->add_control(
            'content_image',
            [
                'label' => esc_html__( 'Center Image/Icon (Optional)', 'ultraaddons-elementor-lite' ),
                'type'  => Controls_Manager::MEDIA,
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label'   => esc_html__( 'Description', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Initial research, user interviews, and goal mapping to define the project foundation.', 'ultraaddons-elementor-lite' ),
                'rows'    => 4,
            ]
        );

        $repeater->add_control(
            'button_text',
            [
                'label'   => esc_html__( 'CTA Button Text', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Explore Details', 'ultraaddons-elementor-lite' ),
            ]
        );

        $repeater->add_control(
            'button_url',
            [
                'label'       => esc_html__( 'CTA Link URL', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://example.com', 'ultraaddons-elementor-lite' ),
            ]
        );

        $repeater->add_control(
            'item_accent_color',
            [
                'label'     => esc_html__( 'Node Accent Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '--ua-node-accent: {{VALUE}}; --ua-node-accent-rgb: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'circle_items',
            [
                'label'       => esc_html__( 'Items', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ title }}}',
                'default'     => [
                    [
                        'title'       => esc_html__( 'Discovery', 'ultraaddons-elementor-lite' ),
                        'subtitle'    => esc_html__( 'Phase 01', 'ultraaddons-elementor-lite' ),
                        'badge_type'  => 'icon',
                        'node_icon'   => [ 'value' => 'fas fa-compass', 'library' => 'fa-solid' ],
                        'node_number' => '01',
                        'description' => esc_html__( 'Initial research, user interviews, and goal mapping to define project foundations.', 'ultraaddons-elementor-lite' ),
                        'button_text' => esc_html__( 'Explore Details', 'ultraaddons-elementor-lite' ),
                    ],
                    [
                        'title'       => esc_html__( 'Strategy', 'ultraaddons-elementor-lite' ),
                        'subtitle'    => esc_html__( 'Phase 02', 'ultraaddons-elementor-lite' ),
                        'badge_type'  => 'icon',
                        'node_icon'   => [ 'value' => 'fas fa-lightbulb', 'library' => 'fa-solid' ],
                        'node_number' => '02',
                        'description' => esc_html__( 'Developing tailored blueprints, architecture plans, and creative technical concepts.', 'ultraaddons-elementor-lite' ),
                        'button_text' => esc_html__( 'View Strategy', 'ultraaddons-elementor-lite' ),
                    ],
                    [
                        'title'       => esc_html__( 'Design', 'ultraaddons-elementor-lite' ),
                        'subtitle'    => esc_html__( 'Phase 03', 'ultraaddons-elementor-lite' ),
                        'badge_type'  => 'icon',
                        'node_icon'   => [ 'value' => 'fas fa-pencil-ruler', 'library' => 'fa-solid' ],
                        'node_number' => '03',
                        'description' => esc_html__( 'Crafting responsive, high-converting interfaces with pixel-perfect visual aesthetics.', 'ultraaddons-elementor-lite' ),
                        'button_text' => esc_html__( 'See Designs', 'ultraaddons-elementor-lite' ),
                    ],
                    [
                        'title'       => esc_html__( 'Development', 'ultraaddons-elementor-lite' ),
                        'subtitle'    => esc_html__( 'Phase 04', 'ultraaddons-elementor-lite' ),
                        'badge_type'  => 'icon',
                        'node_icon'   => [ 'value' => 'fas fa-code', 'library' => 'fa-solid' ],
                        'node_number' => '04',
                        'description' => esc_html__( 'Engineered with clean code, robust performance benchmarks, and thorough test suites.', 'ultraaddons-elementor-lite' ),
                        'button_text' => esc_html__( 'Inspect Code', 'ultraaddons-elementor-lite' ),
                    ],
                    [
                        'title'       => esc_html__( 'Launch', 'ultraaddons-elementor-lite' ),
                        'subtitle'    => esc_html__( 'Phase 05', 'ultraaddons-elementor-lite' ),
                        'badge_type'  => 'icon',
                        'node_icon'   => [ 'value' => 'fas fa-rocket', 'library' => 'fa-solid' ],
                        'node_number' => '05',
                        'description' => esc_html__( 'Seamless deployment, real-time telemetry, monitoring, and ongoing product optimization.', 'ultraaddons-elementor-lite' ),
                        'button_text' => esc_html__( 'Go Live', 'ultraaddons-elementor-lite' ),
                    ],
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Motion & Animation Controls
     */
    protected function register_motion_controls() {
        $this->start_controls_section(
            'section_motion',
            [
                'label' => esc_html__( 'Motion & Animations', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label'        => esc_html__( 'Autoplay Cycle', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Off', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => '',
                'render_type'  => 'template',
            ]
        );

        $this->add_control(
            'autoplay_interval',
            [
                'label'       => esc_html__( 'Autoplay Interval (ms)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::NUMBER,
                'min'         => 1000,
                'max'         => 15000,
                'step'        => 500,
                'default'     => 3500,
                'condition'   => [
                    'autoplay' => 'yes',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'pause_on_hover',
            [
                'label'        => esc_html__( 'Pause on Hover', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'render_type'  => 'template',
            ]
        );

        $this->add_control(
            'continuous_rotation',
            [
                'label'        => esc_html__( 'Continuous Orbit Rotation', 'ultraaddons-elementor-lite' ),
                'description'  => esc_html__( 'Smoothly rotates the circle orbit while keeping center and node items constantly upright.', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Off', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'circle_preset!' => 'half_moon',
                ],
                'render_type'  => 'template',
            ]
        );

        $this->add_control(
            'rotation_speed',
            [
                'label'      => esc_html__( 'Rotation Duration (seconds)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 's' ],
                'range'      => [
                    's' => [
                        'min'  => 5,
                        'max'  => 60,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 's',
                    'size' => 25,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-interactive-circle-wrap' => '--ua-ic-rot-speed: {{SIZE}}s;',
                ],
                'condition'  => [
                    'continuous_rotation' => 'yes',
                    'circle_preset!'      => 'half_moon',
                ],
            ]
        );

        $this->add_control(
            'active_node_effect',
            [
                'label'       => esc_html__( 'Active Node Outer Effect', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'none',
                'options'     => [
                    'none'        => esc_html__( 'None (Simple & Clean)', 'ultraaddons-elementor-lite' ),
                    'pulse'       => esc_html__( 'Radar Pulse (Expanding Wave)', 'ultraaddons-elementor-lite' ),
                    'solid_ring'  => esc_html__( 'Solid Halo Ring (Static)', 'ultraaddons-elementor-lite' ),
                    'double_ring' => esc_html__( 'Double Concentric Ring', 'ultraaddons-elementor-lite' ),
                    'glow'        => esc_html__( 'Breathing Glow Aura', 'ultraaddons-elementor-lite' ),
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'active_effect_color',
            [
                'label'     => esc_html__( 'Active Effect Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(79, 70, 229, 0.4)',
                'condition' => [
                    'active_node_effect!' => 'none',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-interactive-circle-wrap' => '--ua-ic-active-effect-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'show_node_labels',
            [
                'label'        => esc_html__( 'Show Node Titles / Badges', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'render_type'  => 'template',
            ]
        );

        $this->add_control(
            'node_title_position',
            [
                'label'       => esc_html__( 'Title Position', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'inside',
                'options'     => [
                    'inside'  => esc_html__( 'Inside Button (Compact)', 'ultraaddons-elementor-lite' ),
                    'outside' => esc_html__( 'Outside / Below Button', 'ultraaddons-elementor-lite' ),
                ],
                'condition'   => [
                    'show_node_labels' => 'yes',
                ],
                'render_type' => 'template',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Orbit Canvas Controls
     */
    protected function register_style_orbit_controls() {
        $this->start_controls_section(
            'section_style_orbit',
            [
                'label' => esc_html__( 'Orbit Ring & Canvas', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'orbit_size',
            [
                'label'      => esc_html__( 'Orbit Canvas Diameter', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vw' ],
                'range'      => [
                    'px' => [
                        'min' => 280,
                        'max' => 900,
                    ],
                    'vw' => [
                        'min' => 20,
                        'max' => 90,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 500,
                ],
                'tablet_default' => [
                    'unit' => 'px',
                    'size' => 380,
                ],
                'mobile_default' => [
                    'unit' => 'px',
                    'size' => 310,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-interactive-circle-wrap' => '--ua-ic-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ring_border_width',
            [
                'label'      => esc_html__( 'Ring Border Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 15,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 2,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-interactive-circle-wrap' => '--ua-ic-ring-border-width: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_control(
            'ring_border_color',
            [
                'label'     => esc_html__( 'Ring Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(99, 102, 241, 0.2)',
                'selectors' => [
                    '{{WRAPPER}} .ua-interactive-circle-wrap' => '--ua-ic-ring-border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ring_bg_color',
            [
                'label'     => esc_html__( 'Orbit Stage Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-ic-orbit-stage' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'spoke_color',
            [
                'label'     => esc_html__( 'Spoke Line Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(99, 102, 241, 0.25)',
                'selectors' => [
                    '{{WRAPPER}} .ua-interactive-circle-wrap' => '--ua-ic-spoke-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'spoke_active_color',
            [
                'label'     => esc_html__( 'Active Spoke Line Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4f46e5',
                'selectors' => [
                    '{{WRAPPER}} .ua-interactive-circle-wrap' => '--ua-ic-spoke-active-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Node Buttons Controls
     */
    protected function register_style_nodes_controls() {
        $this->start_controls_section(
            'section_style_nodes',
            [
                'label' => esc_html__( 'Node Buttons', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'node_btn_size',
            [
                'label'      => esc_html__( 'Node Button Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 30,
                        'max' => 120,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 70,
                ],
                'tablet_default' => [
                    'unit' => 'px',
                    'size' => 56,
                ],
                'mobile_default' => [
                    'unit' => 'px',
                    'size' => 48,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-interactive-circle-wrap' => '--ua-ic-node-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'node_icon_size',
            [
                'label'      => esc_html__( 'Icon / Number Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 10,
                        'max' => 50,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-ic-node-icon'     => 'font-size: {{SIZE}}px;',
                    '{{WRAPPER}} .ua-ic-node-icon svg' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
                    '{{WRAPPER}} .ua-ic-node-num'      => 'font-size: {{SIZE}}px;',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_node_states' );

        // Normal State
        $this->start_controls_tab(
            'tab_node_normal',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'node_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-interactive-circle-wrap' => '--ua-ic-node-bg: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'node_text_color',
            [
                'label'     => esc_html__( 'Icon / Number Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4f46e5',
                'selectors' => [
                    '{{WRAPPER}} .ua-interactive-circle-wrap' => '--ua-ic-node-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'node_border',
                'selector' => '{{WRAPPER}} .ua-ic-node-btn',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'node_box_shadow',
                'selector' => '{{WRAPPER}} .ua-ic-node-btn',
            ]
        );

        $this->end_controls_tab();

        // Hover State
        $this->start_controls_tab(
            'tab_node_hover',
            [
                'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'node_bg_hover',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-ic-node-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'node_color_hover',
            [
                'label'     => esc_html__( 'Icon / Number Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-ic-node-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'node_border_hover',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-ic-node-btn:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'node_box_shadow_hover',
                'selector' => '{{WRAPPER}} .ua-ic-node-btn:hover',
            ]
        );

        $this->end_controls_tab();

        // Active State
        $this->start_controls_tab(
            'tab_node_active',
            [
                'label' => esc_html__( 'Active', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'node_bg_active',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4f46e5',
                'selectors' => [
                    '{{WRAPPER}} .ua-interactive-circle-wrap' => '--ua-ic-node-active-bg: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'node_color_active',
            [
                'label'     => esc_html__( 'Icon / Number Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-interactive-circle-wrap' => '--ua-ic-node-active-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'node_border_active',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-ic-node-item.ua-ic-active .ua-ic-node-btn' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'node_box_shadow_active',
                'selector' => '{{WRAPPER}} .ua-ic-node-item.ua-ic-active .ua-ic-node-btn',
            ]
        );

        $this->add_control(
            'node_active_outer_ring_color',
            [
                'label'     => esc_html__( 'Active Outer Ring / Effect Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(79, 70, 229, 0.4)',
                'selectors' => [
                    '{{WRAPPER}} .ua-interactive-circle-wrap' => '--ua-ic-active-effect-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        // Node Title Styles
        $this->add_control(
            'heading_node_titles',
            [
                'label'       => esc_html__( 'Node Badge Title', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::HEADING,
                'separator'   => 'before',
                'condition'   => [
                    'show_node_labels' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'node_title_color',
            [
                'label'       => esc_html__( 'Title Color', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => [
                    '{{WRAPPER}} .ua-ic-node-title' => 'color: {{VALUE}};',
                ],
                'condition'   => [
                    'show_node_labels' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'node_title_hover_color',
            [
                'label'       => esc_html__( 'Hover Title Color', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => [
                    '{{WRAPPER}} .ua-ic-node-item:hover .ua-ic-node-title' => 'color: {{VALUE}};',
                ],
                'condition'   => [
                    'show_node_labels' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'node_title_active_color',
            [
                'label'       => esc_html__( 'Active Title Color', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => [
                    '{{WRAPPER}} .ua-ic-node-item.ua-ic-active .ua-ic-node-title' => 'color: {{VALUE}};',
                ],
                'condition'   => [
                    'show_node_labels' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'node_title_typography',
                'selector'  => '{{WRAPPER}} .ua-ic-node-title',
                'condition' => [
                    'show_node_labels' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Center Content Stage Controls
     */
    protected function register_style_center_controls() {
        $this->start_controls_section(
            'section_style_center',
            [
                'label' => esc_html__( 'Center Stage Content', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'center_size',
            [
                'label'      => esc_html__( 'Center Stage Diameter', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vw' ],
                'range'      => [
                    'px' => [
                        'min' => 120,
                        'max' => 500,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 240,
                ],
                'tablet_default' => [
                    'unit' => 'px',
                    'size' => 180,
                ],
                'mobile_default' => [
                    'unit' => 'px',
                    'size' => 150,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-interactive-circle-wrap' => '--ua-ic-center-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'center_bg_color',
            [
                'label'     => esc_html__( 'Stage Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'condition' => [
                    'circle_preset!' => 'half_moon',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-ic-center-stage' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'center_border',
                'selector'  => '{{WRAPPER}} .ua-ic-center-stage',
                'condition' => [
                    'circle_preset!' => 'half_moon',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'center_box_shadow',
                'selector'  => '{{WRAPPER}} .ua-ic-center-stage',
                'condition' => [
                    'circle_preset!' => 'half_moon',
                ],
            ]
        );

        $this->add_responsive_control(
            'center_padding',
            [
                'label'      => esc_html__( 'Content Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-ic-content-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Subtitle Typography & Color
        $this->add_control(
            'heading_subtitle_style',
            [
                'label'     => esc_html__( 'Subtitle', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label'     => esc_html__( 'Subtitle Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#6366f1',
                'selectors' => [
                    '{{WRAPPER}} .ua-ic-content-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .ua-ic-content-subtitle',
            ]
        );

        // Title Typography & Color
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
                'default'   => '#1e293b',
                'selectors' => [
                    '{{WRAPPER}} .ua-ic-content-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .ua-ic-content-title',
            ]
        );

        // Description Typography & Color
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
                    '{{WRAPPER}} .ua-ic-content-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'desc_typography',
                'selector' => '{{WRAPPER}} .ua-ic-content-desc',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Center CTA Button Controls
     */
    protected function register_style_button_controls() {
        $this->start_controls_section(
            'section_style_button',
            [
                'label' => esc_html__( 'CTA Button', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .ua-ic-content-btn',
            ]
        );

        $this->start_controls_tabs( 'tabs_button_states' );

        // Button Normal
        $this->start_controls_tab(
            'tab_btn_normal',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'btn_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4f46e5',
                'selectors' => [
                    '{{WRAPPER}} .ua-ic-content-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-ic-content-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'btn_border',
                'selector' => '{{WRAPPER}} .ua-ic-content-btn',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'btn_box_shadow',
                'selector' => '{{WRAPPER}} .ua-ic-content-btn',
            ]
        );

        $this->end_controls_tab();

        // Button Hover
        $this->start_controls_tab(
            'tab_btn_hover',
            [
                'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'btn_bg_hover',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4338ca',
                'selectors' => [
                    '{{WRAPPER}} .ua-ic-content-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_text_hover',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-ic-content-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_border_hover',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-ic-content-btn:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'btn_box_shadow_hover',
                'selector' => '{{WRAPPER}} .ua-ic-content-btn:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'btn_padding',
            [
                'label'      => esc_html__( 'Button Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'separator'  => 'before',
                'selectors'  => [
                    '{{WRAPPER}} .ua-ic-content-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-ic-content-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget frontend output
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $items    = $settings['circle_items'] ?? [];

        if ( empty( $items ) || ! is_array( $items ) ) {
            return;
        }

        $preset            = ! empty( $settings['circle_preset'] ) ? esc_attr( $settings['circle_preset'] ) : 'full_orbit';
        $trigger           = ! empty( $settings['trigger_type'] ) ? esc_attr( $settings['trigger_type'] ) : 'click';
        $transition        = ! empty( $settings['transition_effect'] ) ? esc_attr( $settings['transition_effect'] ) : 'fade';
        $autoplay          = ( 'yes' === ( $settings['autoplay'] ?? '' ) ) ? '1' : '0';
        $interval          = ! empty( $settings['autoplay_interval'] ) ? intval( $settings['autoplay_interval'] ) : 3500;
        $pause_hover       = ( 'yes' === ( $settings['pause_on_hover'] ?? 'yes' ) ) ? '1' : '0';
        $orbit_val         = $settings['continuous_rotation'] ?? 'yes';
        $orbiting          = ( 'half_moon' !== $preset ) && ( 'yes' === $orbit_val );
        $active_effect     = ! empty( $settings['active_node_effect'] ) ? $settings['active_node_effect'] : ( ( 'yes' === ( $settings['pulse_animation'] ?? '' ) ) ? 'pulse' : 'none' );
        $effect_cls        = ( 'none' !== $active_effect ) ? 'ua-ic-effect-' . esc_attr( $active_effect ) : '';
        $show_node_labels  = ( 'yes' === ( $settings['show_node_labels'] ?? '' ) );
        $title_pos         = ! empty( $settings['node_title_position'] ) ? $settings['node_title_position'] : 'inside';

        $wrapper_classes = [
            'ua-interactive-circle-wrap',
            'ua-ic-preset-' . $preset,
            'ua-ic-trans-' . $transition,
        ];

        if ( $orbiting ) {
            $wrapper_classes[] = 'ua-ic-orbiting';
        }

        if ( '1' === $pause_hover ) {
            $wrapper_classes[] = 'ua-ic-pause-hover';
        }
        ?>
        <div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>"
             data-preset="<?php echo esc_attr( $preset ); ?>"
             data-trigger="<?php echo esc_attr( $trigger ); ?>"
             data-autoplay="<?php echo esc_attr( $autoplay ); ?>"
             data-interval="<?php echo esc_attr( $interval ); ?>"
             data-pause-hover="<?php echo esc_attr( $pause_hover ); ?>">

            <!-- Center Content Stage: Sits in dead center and NEVER rotates -->
            <div class="ua-ic-center-stage">
                <?php foreach ( $items as $index => $item ) :
                    $active_cls = ( 0 === $index ) ? 'ua-ic-active' : '';
                    $title      = $item['title'] ?? '';
                    $subtitle   = $item['subtitle'] ?? '';
                    $desc       = $item['description'] ?? '';
                    $btn_text   = $item['button_text'] ?? '';
                    $btn_url    = $item['button_url']['url'] ?? '';
                    $is_external= ! empty( $item['button_url']['is_external'] ) ? ' target="_blank"' : '';
                    $nofollow   = ! empty( $item['button_url']['nofollow'] ) ? ' rel="nofollow"' : '';
                    $media      = $item['content_image'] ?? [];
                ?>
                    <div class="ua-ic-content-item <?php echo esc_attr( $active_cls ); ?>" data-index="<?php echo esc_attr( $index ); ?>">
                        <?php if ( ! empty( $media['url'] ) ) : ?>
                            <div class="ua-ic-content-media">
                                <img src="<?php echo esc_url( $media['url'] ); ?>" alt="<?php echo esc_attr( $title ); ?>">
                            </div>
                        <?php endif; ?>

                        <?php if ( ! empty( $subtitle ) ) : ?>
                            <span class="ua-ic-content-subtitle"><?php echo esc_html( $subtitle ); ?></span>
                        <?php endif; ?>

                        <?php if ( ! empty( $title ) ) : ?>
                            <h4 class="ua-ic-content-title"><?php echo esc_html( $title ); ?></h4>
                        <?php endif; ?>

                        <?php if ( ! empty( $desc ) ) : ?>
                            <p class="ua-ic-content-desc"><?php echo esc_html( $desc ); ?></p>
                        <?php endif; ?>

                        <?php if ( ! empty( $btn_text ) ) : ?>
                            <a href="<?php echo ! empty( $btn_url ) ? esc_url( $btn_url ) : '#'; ?>" class="ua-ic-content-btn"<?php echo $is_external . $nofollow; ?>>
                                <?php echo esc_html( $btn_text ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Orbit Canvas Stage: Rotates smoothly if Orbit Rotation is enabled -->
            <div class="ua-ic-orbit-stage">
                <!-- Background Orbit Ring -->
                <div class="ua-ic-orbit-ring"></div>

                <!-- SVG Spoke Lines Layer -->
                <svg class="ua-ic-spoke-layer"></svg>

                <!-- Orbit Nodes Container -->
                <div class="ua-ic-nodes-wrap">
                    <?php foreach ( $items as $index => $item ) :
                        $active_cls    = ( 0 === $index ) ? 'ua-ic-active' : '';
                        $badge_type    = $item['badge_type'] ?? 'icon';
                        $title         = $item['title'] ?? '';
                        $item_id       = $item['_id'] ?? ( $index + 1 );
                        $custom_accent = ! empty( $item['item_accent_color'] ) ? 'style="--ua-node-accent: ' . esc_attr( $item['item_accent_color'] ) . '; --ua-node-accent-rgb: ' . esc_attr( $item['item_accent_color'] ) . ';"' : '';
                    ?>
                        <div class="ua-ic-node-item elementor-repeater-item-<?php echo esc_attr( $item_id ); ?> <?php echo esc_attr( $active_cls ); ?>" data-index="<?php echo esc_attr( $index ); ?>" <?php echo $custom_accent; ?>>
                            <div class="ua-ic-node-pointer-shape"></div>
                            <div class="ua-ic-node-rotator">
                                <div class="ua-ic-node-btn <?php echo esc_attr( $effect_cls ); ?>" tabindex="0" role="button" aria-label="<?php echo esc_attr( $title ); ?>">
                                    <div class="ua-ic-node-btn-inner">
                                        <?php if ( 'icon' === $badge_type && ! empty( $item['node_icon'] ) ) : ?>
                                            <span class="ua-ic-node-icon">
                                                <?php Icons_Manager::render_icon( $item['node_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                            </span>
                                        <?php else : ?>
                                            <span class="ua-ic-node-num">
                                                <?php echo esc_html( $item['node_number'] ?? ( $index + 1 ) ); ?>
                                            </span>
                                        <?php endif; ?>

                                        <?php if ( $show_node_labels && ! empty( $title ) && 'inside' === $title_pos ) : ?>
                                            <span class="ua-ic-node-title ua-ic-title-inside"><?php echo esc_html( $title ); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <?php if ( $show_node_labels && ! empty( $title ) && 'outside' === $title_pos ) : ?>
                                    <span class="ua-ic-node-title ua-ic-title-outside"><?php echo esc_html( $title ); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
        <?php
    }
}