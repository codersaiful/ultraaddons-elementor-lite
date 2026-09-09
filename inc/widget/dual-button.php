<?php
namespace UltraAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;

defined( 'ABSPATH' ) || die();

/**
 * Dual Button (Double Button) Widget for UltraAddons.
 *
 * Provides a high-converting, fully unlocked double button group
 * with Connected Capsule and Separated Spaced layouts, Middle Connector Badge
 * (Text/Icon/Diamond/Circle), Pure CSS Tooltips, and Premium Hover Animations.
 *
 * @since 2.0.3.6
 * @package UltraAddons
 */
class Dual_Button extends Base {

    /**
     * Constructor: Register widget CSS dependencies.
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        if ( ! wp_style_is( 'hover-css', 'registered' ) ) {
            wp_register_style(
                'hover-css',
                ULTRA_ADDONS_ASSETS . 'vendor/hover-css/css/hover-min.css',
                [],
                ULTRA_ADDONS_VERSION,
                'all'
            );
        }

        $css_file = ULTRA_ADDONS_DIR . 'assets/css/widgets/dual-button.css';
        $css_ver  = file_exists( $css_file ) ? filemtime( $css_file ) : ULTRA_ADDONS_VERSION;

        wp_register_style(
            'ultraaddons-dual-button',
            ULTRA_ADDONS_ASSETS . 'css/widgets/dual-button.css',
            [ 'ultraaddons-widgets-style', 'hover-css' ],
            $css_ver
        );
    }

    /**
     * Widget Style Dependencies.
     */
    public function get_style_depends() {
        return [
            'ultraaddons-widgets-style',
            'hover-css',
            'ultraaddons-dual-button',
        ];
    }

    /**
     * Get Complete Animation List with Option Groups
     *
     * @return array
     */
    public static function get_animations_list() {
        return [
            'groups' => [
                [
                    'label'   => esc_html__( 'Animations', 'ultraaddons-elementor-lite' ),
                    'options' => [
                        'none'        => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                        'winona'      => esc_html__( 'Winona', 'ultraaddons-elementor-lite' ),
                        'ray-left'    => esc_html__( 'Ray Left', 'ultraaddons-elementor-lite' ),
                        'ray-right'   => esc_html__( 'Ray Right', 'ultraaddons-elementor-lite' ),
                        'wayra-left'  => esc_html__( 'Wayra Left', 'ultraaddons-elementor-lite' ),
                        'wayra-right' => esc_html__( 'Wayra Right', 'ultraaddons-elementor-lite' ),
                        'isi-left'    => esc_html__( 'Isi Left', 'ultraaddons-elementor-lite' ),
                        'isi-right'   => esc_html__( 'Isi Right', 'ultraaddons-elementor-lite' ),
                        'aylen'       => esc_html__( 'Aylen', 'ultraaddons-elementor-lite' ),
                        'antiman'     => esc_html__( 'Antiman', 'ultraaddons-elementor-lite' ),
                        'shine'       => esc_html__( 'Shine Sweep', 'ultraaddons-elementor-lite' ),
                        'slide-fill'  => esc_html__( 'Slide Fill Up', 'ultraaddons-elementor-lite' ),
                        'icon-shift'  => esc_html__( 'Icon Shift', 'ultraaddons-elementor-lite' ),
                        'ripple'      => esc_html__( 'Radar Ripple', 'ultraaddons-elementor-lite' ),
                    ],
                ],
                [
                    'label'   => esc_html__( '2D Animations', 'ultraaddons-elementor-lite' ),
                    'options' => [
                        'hvr-grow'                   => esc_html__( 'Grow', 'ultraaddons-elementor-lite' ),
                        'hvr-shrink'                 => esc_html__( 'Shrink', 'ultraaddons-elementor-lite' ),
                        'hvr-pulse'                  => esc_html__( 'Pulse', 'ultraaddons-elementor-lite' ),
                        'hvr-pulse-grow'             => esc_html__( 'Pulse Grow', 'ultraaddons-elementor-lite' ),
                        'hvr-pulse-shrink'           => esc_html__( 'Pulse Shrink', 'ultraaddons-elementor-lite' ),
                        'hvr-push'                   => esc_html__( 'Push', 'ultraaddons-elementor-lite' ),
                        'hvr-pop'                    => esc_html__( 'Pop', 'ultraaddons-elementor-lite' ),
                        'hvr-bounce-in'              => esc_html__( 'Bounce In', 'ultraaddons-elementor-lite' ),
                        'hvr-bounce-out'             => esc_html__( 'Bounce Out', 'ultraaddons-elementor-lite' ),
                        'hvr-rotate'                 => esc_html__( 'Rotate', 'ultraaddons-elementor-lite' ),
                        'hvr-grow-rotate'            => esc_html__( 'Grow Rotate', 'ultraaddons-elementor-lite' ),
                        'hvr-float'                  => esc_html__( 'Float', 'ultraaddons-elementor-lite' ),
                        'hvr-sink'                   => esc_html__( 'Sink', 'ultraaddons-elementor-lite' ),
                        'hvr-bob'                    => esc_html__( 'Bob', 'ultraaddons-elementor-lite' ),
                        'hvr-hang'                   => esc_html__( 'Hang', 'ultraaddons-elementor-lite' ),
                        'hvr-skew'                   => esc_html__( 'Skew', 'ultraaddons-elementor-lite' ),
                        'hvr-skew-forward'           => esc_html__( 'Skew Forward', 'ultraaddons-elementor-lite' ),
                        'hvr-skew-backward'          => esc_html__( 'Skew Backward', 'ultraaddons-elementor-lite' ),
                        'hvr-wobble-horizontal'      => esc_html__( 'Wobble Horizontal', 'ultraaddons-elementor-lite' ),
                        'hvr-wobble-vertical'        => esc_html__( 'Wobble Vertical', 'ultraaddons-elementor-lite' ),
                        'hvr-wobble-to-bottom-right' => esc_html__( 'Wobble To Bottom Right', 'ultraaddons-elementor-lite' ),
                        'hvr-wobble-to-top-right'    => esc_html__( 'Wobble To Top Right', 'ultraaddons-elementor-lite' ),
                        'hvr-wobble-top'             => esc_html__( 'Wobble Top', 'ultraaddons-elementor-lite' ),
                        'hvr-wobble-bottom'          => esc_html__( 'Wobble Bottom', 'ultraaddons-elementor-lite' ),
                        'hvr-wobble-skew'            => esc_html__( 'Wobble Skew', 'ultraaddons-elementor-lite' ),
                        'hvr-buzz'                   => esc_html__( 'Buzz', 'ultraaddons-elementor-lite' ),
                        'hvr-buzz-out'               => esc_html__( 'Buzz Out', 'ultraaddons-elementor-lite' ),
                        'hvr-forward'                => esc_html__( 'Forward', 'ultraaddons-elementor-lite' ),
                        'hvr-backward'               => esc_html__( 'Backward', 'ultraaddons-elementor-lite' ),
                    ],
                ],
                [
                    'label'   => esc_html__( 'Background Animations', 'ultraaddons-elementor-lite' ),
                    'options' => [
                        'hvr-back-pulse'             => esc_html__( 'Back Pulse', 'ultraaddons-elementor-lite' ),
                        'hvr-sweep-to-right'         => esc_html__( 'Sweep To Right', 'ultraaddons-elementor-lite' ),
                        'hvr-sweep-to-left'          => esc_html__( 'Sweep To Left', 'ultraaddons-elementor-lite' ),
                        'hvr-sweep-to-bottom'        => esc_html__( 'Sweep To Bottom', 'ultraaddons-elementor-lite' ),
                        'hvr-sweep-to-top'           => esc_html__( 'Sweep To Top', 'ultraaddons-elementor-lite' ),
                        'hvr-bounce-to-right'        => esc_html__( 'Bounce To Right', 'ultraaddons-elementor-lite' ),
                        'hvr-bounce-to-left'         => esc_html__( 'Bounce To Left', 'ultraaddons-elementor-lite' ),
                        'hvr-bounce-to-bottom'       => esc_html__( 'Bounce To Bottom', 'ultraaddons-elementor-lite' ),
                        'hvr-bounce-to-top'          => esc_html__( 'Bounce To Top', 'ultraaddons-elementor-lite' ),
                        'hvr-radial-out'             => esc_html__( 'Radial Out', 'ultraaddons-elementor-lite' ),
                        'hvr-radial-in'              => esc_html__( 'Radial In', 'ultraaddons-elementor-lite' ),
                        'hvr-rectangle-in'           => esc_html__( 'Rectangle In', 'ultraaddons-elementor-lite' ),
                        'hvr-rectangle-out'          => esc_html__( 'Rectangle Out', 'ultraaddons-elementor-lite' ),
                        'hvr-shutter-in-horizontal'  => esc_html__( 'Shutter In Horizontal', 'ultraaddons-elementor-lite' ),
                        'hvr-shutter-out-horizontal' => esc_html__( 'Shutter Out Horizontal', 'ultraaddons-elementor-lite' ),
                        'hvr-shutter-in-vertical'    => esc_html__( 'Shutter In Vertical', 'ultraaddons-elementor-lite' ),
                        'hvr-shutter-out-vertical'   => esc_html__( 'Shutter Out Vertical', 'ultraaddons-elementor-lite' ),
                    ],
                ],
                [
                    'label'   => esc_html__( 'Border Animations', 'ultraaddons-elementor-lite' ),
                    'options' => [
                        'hvr-underline-from-left'   => esc_html__( 'Underline From Left', 'ultraaddons-elementor-lite' ),
                        'hvr-underline-from-center' => esc_html__( 'Underline From Center', 'ultraaddons-elementor-lite' ),
                        'hvr-underline-from-right'  => esc_html__( 'Underline From Right', 'ultraaddons-elementor-lite' ),
                        'hvr-underline-reveal'      => esc_html__( 'Underline Reveal', 'ultraaddons-elementor-lite' ),
                        'hvr-overline-reveal'       => esc_html__( 'Overline Reveal', 'ultraaddons-elementor-lite' ),
                        'hvr-overline-from-left'    => esc_html__( 'Overline From Left', 'ultraaddons-elementor-lite' ),
                        'hvr-overline-from-center'  => esc_html__( 'Overline From Center', 'ultraaddons-elementor-lite' ),
                        'hvr-overline-from-right'   => esc_html__( 'Overline From Right', 'ultraaddons-elementor-lite' ),
                        'hvr-ripple-out'            => esc_html__( 'Ripple Out', 'ultraaddons-elementor-lite' ),
                        'hvr-ripple-in'             => esc_html__( 'Ripple In', 'ultraaddons-elementor-lite' ),
                    ],
                ],
            ],
        ];
    }

    /**
     * Get Flat Animation Options for Validation
     *
     * @return array
     */
    public static function get_animations_flat_options() {
        $data = self::get_animations_list();
        $flat = [ 'push' => esc_html__( 'Push', 'ultraaddons-elementor-lite' ) ]; // backward compatibility
        foreach ( $data['groups'] as $group ) {
            foreach ( $group['options'] as $k => $v ) {
                $flat[ $k ] = $v;
            }
        }
        return $flat;
    }

    /**
     * Resolve CSS Class for Animation
     *
     * @param string $animation
     * @return string
     */
    public static function get_animation_class( $animation ) {
        if ( empty( $animation ) || 'none' === $animation ) {
            return '';
        }
        if ( strpos( $animation, 'hvr-' ) === 0 ) {
            return esc_attr( $animation );
        }
        return 'ua-anim-' . esc_attr( $animation );
    }

    /**
     * Widget Keywords.
     */
    public function get_keywords() {
        return [
            'ultraaddons',
            'ua',
            'dual button',
            'double button',
            'two buttons',
            'button group',
            'cta buttons',
            'multi button',
            'call to action',
        ];
    }

    /**
     * Register Widget Controls.
     */
    protected function register_controls() {
        $this->register_content_controls();
        $this->register_style_controls();
    }

    /**
     * Register Content Tab Controls.
     */
    protected function register_content_controls() {

        // ============================================
        // Section: Layout & General
        // ============================================
        $this->start_controls_section(
            'ua_db_section_layout',
            [
                'label' => esc_html__( 'Layout & Alignment', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ua_db_layout_mode',
            [
                'label'       => esc_html__( 'Layout Mode', 'ultraaddons-elementor-lite' ),
                'description' => esc_html__( 'Connected Capsule combines both buttons into a unified pill; Joined with Divider creates sleek conjoined buttons with a vertical separator; Separated gives them independent spacing.', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'options'     => [
                    'connected' => esc_html__( 'Connected Capsule', 'ultraaddons-elementor-lite' ),
                    'divided'   => esc_html__( 'Joined with Divider', 'ultraaddons-elementor-lite' ),
                    'separated' => esc_html__( 'Separated Buttons', 'ultraaddons-elementor-lite' ),
                ],
                'default'     => 'connected',
            ]
        );

        $this->add_responsive_control(
            'ua_db_alignment',
            [
                'label'     => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center'     => [
                        'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'flex-end'   => [
                        'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                    'stretch'    => [
                        'title' => esc_html__( 'Justify / Equal Width', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-justify',
                    ],
                ],
                'default'   => 'center',
                'prefix_class' => 'ua-db-align%s-',
                'selectors' => [
                    '{{WRAPPER}} .ua-dual-button-wrap' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_db_mobile_stack',
            [
                'label'     => esc_html__( 'Mobile Stacking', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'mobile' => esc_html__( 'Stack on Mobile (< 768px)', 'ultraaddons-elementor-lite' ),
                    'tablet' => esc_html__( 'Stack on Tablet & Mobile (< 1025px)', 'ultraaddons-elementor-lite' ),
                    'none'   => esc_html__( 'Always Keep Side by Side', 'ultraaddons-elementor-lite' ),
                ],
                'default'   => 'mobile',
            ]
        );

        $this->add_control(
            'ua_db_mobile_reverse',
            [
                'label'        => esc_html__( 'Reverse Order on Stack', 'ultraaddons-elementor-lite' ),
                'description'  => esc_html__( 'When stacked vertically on mobile, places the second button above the first button.', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'condition'    => [
                    'ua_db_mobile_stack!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'ua_db_full_width_mobile',
            [
                'label'        => esc_html__( 'Full Width on Mobile', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'ua_db_mobile_stack!' => 'none',
                ],
            ]
        );

        $this->end_controls_section();

        // ============================================
        // Section: First Button
        // ============================================
        $this->start_controls_section(
            'ua_db_section_btn_first',
            [
                'label' => esc_html__( 'First Button', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ua_db_first_text',
            [
                'label'       => esc_html__( 'Button Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Get Started', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'Get Started', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'ua_db_first_link',
            [
                'label'       => esc_html__( 'Link URL', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'ultraaddons-elementor-lite' ),
                'default'     => [
                    'url'         => '#',
                    'is_external' => false,
                    'nofollow'    => false,
                ],
                'dynamic'     => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'ua_db_first_animation',
            [
                'label'   => esc_html__( 'Select Animation', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'groups'  => self::get_animations_list()['groups'],
                'options' => self::get_animations_flat_options(),
                'default' => 'none',
            ]
        );

        $this->add_control(
            'ua_db_first_duration',
            [
                'label'      => esc_html__( 'Effect Duration', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 's' ],
                'range'      => [
                    's' => [
                        'min'  => 0.1,
                        'max'  => 2.0,
                        'step' => 0.05,
                    ],
                ],
                'default'    => [
                    'unit' => 's',
                    'size' => 0.3,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-db-btn-first' => 'transition-duration: {{SIZE}}s;',
                    '{{WRAPPER}} .ua-db-btn-first .ua-db-icon' => 'transition-duration: {{SIZE}}s;',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_db_first_width',
            [
                'label'      => esc_html__( 'Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'vw' ],
                'range'      => [
                    'px' => [
                        'min'  => 40,
                        'max'  => 600,
                        'step' => 1,
                    ],
                    '%'  => [
                        'min'  => 10,
                        'max'  => 100,
                        'step' => 1,
                    ],
                    'vw' => [
                        'min'  => 10,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-db-btn-first' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_db_first_align',
            [
                'label'     => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center'     => [
                        'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'flex-end'   => [
                        'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .ua-db-btn-first' => 'justify-content: {{VALUE}}; text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_db_first_id',
            [
                'label'       => esc_html__( 'Button ID', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => [
                    'active' => true,
                ],
                'placeholder' => esc_html__( 'e.g. first-btn', 'ultraaddons-elementor-lite' ),
                'description' => esc_html__( 'Set custom HTML ID attribute for tracking or anchor links.', 'ultraaddons-elementor-lite' ),
                'separator'   => 'after',
            ]
        );

        $this->add_control(
            'ua_db_first_tooltip_enable',
            [
                'label'        => esc_html__( 'Enable Tooltip', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'ua_db_first_tooltip_text',
            [
                'label'       => esc_html__( 'Tooltip Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Click to get started!', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'Click to get started!', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'ua_db_first_tooltip_enable' => 'yes',
                ],
                'dynamic'     => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'ua_db_first_tooltip_pos',
            [
                'label'     => esc_html__( 'Tooltip Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'top'    => esc_html__( 'Top', 'ultraaddons-elementor-lite' ),
                    'bottom' => esc_html__( 'Bottom', 'ultraaddons-elementor-lite' ),
                    'left'   => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                    'right'  => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                ],
                'default'   => 'top',
                'condition' => [
                    'ua_db_first_tooltip_enable' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // ============================================
        // Section: First Button Icon
        // ============================================
        $this->start_controls_section(
            'ua_db_section_first_icon',
            [
                'label' => esc_html__( 'First Button Icon', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ua_db_first_icon',
            [
                'label'       => esc_html__( 'Select Icon', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::ICONS,
                'skin'        => 'inline',
                'label_block' => false,
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'ua_db_first_icon_align',
            [
                'label'       => esc_html__( 'Position', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default'     => 'left',
                'options'     => [
                    'left'  => [
                        'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'separator'   => 'before',
            ]
        );

        $this->add_responsive_control(
            'ua_db_first_icon_size',
            [
                'label'      => esc_html__( 'Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 18,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-db-btn-first .ua-db-icon'     => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-db-btn-first .ua-db-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_db_first_icon_indent',
            [
                'label'      => esc_html__( 'Distance', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-db-btn-first .ua-db-icon-left'  => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-db-btn-first .ua-db-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ============================================
        // Section: Second Button
        // ============================================
        $this->start_controls_section(
            'ua_db_section_btn_second',
            [
                'label' => esc_html__( 'Second Button', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ua_db_second_text',
            [
                'label'       => esc_html__( 'Button Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Learn More', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'Learn More', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'ua_db_second_link',
            [
                'label'       => esc_html__( 'Link URL', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'ultraaddons-elementor-lite' ),
                'default'     => [
                    'url'         => '#',
                    'is_external' => false,
                    'nofollow'    => false,
                ],
                'dynamic'     => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'ua_db_second_animation',
            [
                'label'   => esc_html__( 'Select Animation', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'groups'  => self::get_animations_list()['groups'],
                'options' => self::get_animations_flat_options(),
                'default' => 'none',
            ]
        );

        $this->add_control(
            'ua_db_second_duration',
            [
                'label'      => esc_html__( 'Effect Duration', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 's' ],
                'range'      => [
                    's' => [
                        'min'  => 0.1,
                        'max'  => 2.0,
                        'step' => 0.05,
                    ],
                ],
                'default'    => [
                    'unit' => 's',
                    'size' => 0.3,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-db-btn-second' => 'transition-duration: {{SIZE}}s;',
                    '{{WRAPPER}} .ua-db-btn-second .ua-db-icon' => 'transition-duration: {{SIZE}}s;',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_db_second_width',
            [
                'label'      => esc_html__( 'Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'vw' ],
                'range'      => [
                    'px' => [
                        'min'  => 40,
                        'max'  => 600,
                        'step' => 1,
                    ],
                    '%'  => [
                        'min'  => 10,
                        'max'  => 100,
                        'step' => 1,
                    ],
                    'vw' => [
                        'min'  => 10,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-db-btn-second' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_db_second_align',
            [
                'label'     => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center'     => [
                        'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'flex-end'   => [
                        'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .ua-db-btn-second' => 'justify-content: {{VALUE}}; text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_db_second_id',
            [
                'label'       => esc_html__( 'Button ID', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => [
                    'active' => true,
                ],
                'placeholder' => esc_html__( 'e.g. second-btn', 'ultraaddons-elementor-lite' ),
                'description' => esc_html__( 'Set custom HTML ID attribute for tracking or anchor links.', 'ultraaddons-elementor-lite' ),
                'separator'   => 'after',
            ]
        );

        $this->add_control(
            'ua_db_second_tooltip_enable',
            [
                'label'        => esc_html__( 'Enable Tooltip', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'ua_db_second_tooltip_text',
            [
                'label'       => esc_html__( 'Tooltip Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Explore more features', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'Explore more features', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'ua_db_second_tooltip_enable' => 'yes',
                ],
                'dynamic'     => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'ua_db_second_tooltip_pos',
            [
                'label'     => esc_html__( 'Tooltip Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'top'    => esc_html__( 'Top', 'ultraaddons-elementor-lite' ),
                    'bottom' => esc_html__( 'Bottom', 'ultraaddons-elementor-lite' ),
                    'left'   => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                    'right'  => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                ],
                'default'   => 'top',
                'condition' => [
                    'ua_db_second_tooltip_enable' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // ============================================
        // Section: Second Button Icon
        // ============================================
        $this->start_controls_section(
            'ua_db_section_second_icon',
            [
                'label' => esc_html__( 'Second Button Icon', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ua_db_second_icon',
            [
                'label'       => esc_html__( 'Select Icon', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::ICONS,
                'skin'        => 'inline',
                'label_block' => false,
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'ua_db_second_icon_align',
            [
                'label'       => esc_html__( 'Position', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default'     => 'right',
                'options'     => [
                    'left'  => [
                        'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'separator'   => 'before',
            ]
        );

        $this->add_responsive_control(
            'ua_db_second_icon_size',
            [
                'label'      => esc_html__( 'Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 18,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-db-btn-second .ua-db-icon'     => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-db-btn-second .ua-db-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_db_second_icon_indent',
            [
                'label'      => esc_html__( 'Distance', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-db-btn-second .ua-db-icon-left'  => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-db-btn-second .ua-db-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ============================================
        // Section: Middle Badge (Connector)
        // ============================================
        $this->start_controls_section(
            'ua_db_section_middle_badge',
            [
                'label' => esc_html__( 'Middle Badge Connector', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ua_db_badge_enable',
            [
                'label'        => esc_html__( 'Show Middle Badge', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'ua_db_badge_type',
            [
                'label'     => esc_html__( 'Badge Type', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'text' => esc_html__( 'Text / Word (e.g. OR)', 'ultraaddons-elementor-lite' ),
                    'icon' => esc_html__( 'Icon / Symbol', 'ultraaddons-elementor-lite' ),
                ],
                'default'   => 'text',
                'condition' => [
                    'ua_db_badge_enable' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ua_db_badge_text',
            [
                'label'       => esc_html__( 'Badge Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'OR', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'OR', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'ua_db_badge_enable' => 'yes',
                    'ua_db_badge_type'   => 'text',
                ],
                'dynamic'     => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'ua_db_badge_icon',
            [
                'label'     => esc_html__( 'Badge Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::ICONS,
                'condition' => [
                    'ua_db_badge_enable' => 'yes',
                    'ua_db_badge_type'   => 'icon',
                ],
            ]
        );

        $this->add_control(
            'ua_db_badge_shape',
            [
                'label'     => esc_html__( 'Badge Shape', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'circle'  => esc_html__( 'Circle', 'ultraaddons-elementor-lite' ),
                    'diamond' => esc_html__( 'Diamond (Rotated)', 'ultraaddons-elementor-lite' ),
                    'pill'    => esc_html__( 'Pill Capsule', 'ultraaddons-elementor-lite' ),
                    'square'  => esc_html__( 'Rounded Square', 'ultraaddons-elementor-lite' ),
                    'flat'    => esc_html__( 'Minimal Clean Tag', 'ultraaddons-elementor-lite' ),
                ],
                'default'   => 'circle',
                'condition' => [
                    'ua_db_badge_enable' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register Style Tab Controls.
     */
    protected function register_style_controls() {

        // ============================================
        // Style Section: General & Container
        // ============================================
        $this->start_controls_section(
            'ua_db_style_container',
            [
                'label' => esc_html__( 'Container & Spacing', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'ua_db_buttons_gap',
            [
                'label'      => esc_html__( 'Space Between Buttons', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 60,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 14,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-db-separated' => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'ua_db_layout_mode' => 'separated',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_db_capsule_radius',
            [
                'label'      => esc_html__( 'Capsule Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 60,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 50,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-db-connected .ua-db-btn-first'  => 'border-radius: {{SIZE}}px 0 0 {{SIZE}}px;',
                    '{{WRAPPER}} .ua-db-connected .ua-db-btn-second' => 'border-radius: 0 {{SIZE}}px {{SIZE}}px 0;',
                ],
                'condition'  => [
                    'ua_db_layout_mode' => 'connected',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_db_divided_radius',
            [
                'label'      => esc_html__( 'Outer Border Radius', 'ultraaddons-elementor-lite' ),
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
                    'size' => 6,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-db-divided .ua-db-btn-first'  => 'border-radius: {{SIZE}}px 0 0 {{SIZE}}px;',
                    '{{WRAPPER}} .ua-db-divided .ua-db-btn-second' => 'border-radius: 0 {{SIZE}}px {{SIZE}}px 0;',
                ],
                'condition'  => [
                    'ua_db_layout_mode' => 'divided',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_db_wrapper_padding',
            [
                'label'      => esc_html__( 'Wrapper Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-dual-button-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ============================================
        // Style Section: First Button
        // ============================================
        $this->start_controls_section(
            'ua_db_style_btn_first',
            [
                'label' => esc_html__( 'First Button Style', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_db_first_typography',
                'selector' => '{{WRAPPER}} .ua-db-btn-first',
            ]
        );

        $this->start_controls_tabs( 'ua_db_first_tabs' );

        // Normal Tab
        $this->start_controls_tab(
            'ua_db_first_tab_normal',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_db_first_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-db-btn-first' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_db_first_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-db-btn-first .ua-db-icon'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-db-btn-first .ua-db-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'ua_db_first_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-db-btn-first',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'default' => '#4f46e5',
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_db_first_border',
                'selector' => '{{WRAPPER}} .ua-db-btn-first',
            ]
        );

        $this->add_responsive_control(
            'ua_db_first_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-db-separated .ua-db-btn-first' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'ua_db_layout_mode' => 'separated',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_db_first_shadow',
                'selector' => '{{WRAPPER}} .ua-db-btn-first',
            ]
        );

        $this->end_controls_tab();

        // Hover Tab
        $this->start_controls_tab(
            'ua_db_first_tab_hover',
            [
                'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_db_first_hover_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-db-btn-first:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_db_first_hover_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-db-btn-first:hover .ua-db-icon'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-db-btn-first:hover .ua-db-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'ua_db_first_hover_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-db-btn-first:hover',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'default' => '#4338ca',
                    ],
                ],
            ]
        );

        $this->add_control(
            'ua_db_first_hover_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-db-btn-first:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_db_first_hover_shadow',
                'selector' => '{{WRAPPER}} .ua-db-btn-first:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
            'ua_db_first_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'top'      => 14,
                    'right'    => 28,
                    'bottom'   => 14,
                    'left'     => 28,
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-db-btn-first' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'before',
            ]
        );

        $this->end_controls_section();

        // ============================================
        // Style Section: Second Button
        // ============================================
        $this->start_controls_section(
            'ua_db_style_btn_second',
            [
                'label' => esc_html__( 'Second Button Style', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_db_second_typography',
                'selector' => '{{WRAPPER}} .ua-db-btn-second',
            ]
        );

        $this->start_controls_tabs( 'ua_db_second_tabs' );

        // Normal Tab
        $this->start_controls_tab(
            'ua_db_second_tab_normal',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_db_second_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0f172a',
                'selectors' => [
                    '{{WRAPPER}} .ua-db-btn-second' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_db_second_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-db-btn-second .ua-db-icon'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-db-btn-second .ua-db-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'ua_db_second_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-db-btn-second',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'default' => '#f8fafc',
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_db_second_border',
                'selector' => '{{WRAPPER}} .ua-db-btn-second',
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'top'    => '1',
                            'right'  => '1',
                            'bottom' => '1',
                            'left'   => '1',
                            'isLinked' => true,
                        ],
                    ],
                    'color' => [
                        'default' => '#e2e8f0',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_db_second_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-db-separated .ua-db-btn-second' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'ua_db_layout_mode' => 'separated',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_db_second_shadow',
                'selector' => '{{WRAPPER}} .ua-db-btn-second',
            ]
        );

        $this->end_controls_tab();

        // Hover Tab
        $this->start_controls_tab(
            'ua_db_second_tab_hover',
            [
                'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_db_second_hover_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0274be',
                'selectors' => [
                    '{{WRAPPER}} .ua-db-btn-second:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_db_second_hover_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-db-btn-second:hover .ua-db-icon'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-db-btn-second:hover .ua-db-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'ua_db_second_hover_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-db-btn-second:hover',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'default' => '#f1f5f9',
                    ],
                ],
            ]
        );

        $this->add_control(
            'ua_db_second_hover_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-db-btn-second:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_db_second_hover_shadow',
                'selector' => '{{WRAPPER}} .ua-db-btn-second:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
            'ua_db_second_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'top'      => 14,
                    'right'    => 28,
                    'bottom'   => 14,
                    'left'     => 28,
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-db-btn-second' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'before',
            ]
        );

        $this->end_controls_section();

        // ============================================
        // Style Section: Middle Divider Line
        // ============================================
        $this->start_controls_section(
            'ua_db_style_divider',
            [
                'label'     => esc_html__( 'Middle Divider Line', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ua_db_layout_mode' => 'divided',
                ],
            ]
        );

        $this->add_control(
            'ua_db_divider_color',
            [
                'label'     => esc_html__( 'Divider Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(255, 255, 255, 0.45)',
                'selectors' => [
                    '{{WRAPPER}} .ua-db-divider-line' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_db_divider_width',
            [
                'label'      => esc_html__( 'Line Width / Thickness', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 1,
                        'max'  => 10,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 1,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-db-divider-line' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_db_divider_height',
            [
                'label'      => esc_html__( 'Line Height', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px' ],
                'range'      => [
                    '%'  => [
                        'min'  => 10,
                        'max'  => 100,
                        'step' => 1,
                    ],
                    'px' => [
                        'min'  => 10,
                        'max'  => 80,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-db-divider-line' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ============================================
        // Style Section: Middle Badge
        // ============================================
        $this->start_controls_section(
            'ua_db_style_badge',
            [
                'label'     => esc_html__( 'Middle Badge Style', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ua_db_badge_enable' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_db_badge_size',
            [
                'label'      => esc_html__( 'Badge Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 20,
                        'max'  => 64,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 32,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-db-badge' => 'width: {{SIZE}}px; height: {{SIZE}}px; line-height: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_db_badge_typography',
                'selector' => '{{WRAPPER}} .ua-db-badge',
            ]
        );

        $this->add_control(
            'ua_db_badge_color',
            [
                'label'     => esc_html__( 'Text / Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#475569',
                'selectors' => [
                    '{{WRAPPER}} .ua-db-badge' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_db_badge_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-db-badge' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_db_badge_border',
                'selector' => '{{WRAPPER}} .ua-db-badge',
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'top'    => '2',
                            'right'  => '2',
                            'bottom' => '2',
                            'left'   => '2',
                            'isLinked' => true,
                        ],
                    ],
                    'color' => [
                        'default' => '#e2e8f0',
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_db_badge_shadow',
                'selector' => '{{WRAPPER}} .ua-db-badge',
            ]
        );

        $this->end_controls_section();

        // ============================================
        // Style Section: Tooltips
        // ============================================
        $this->start_controls_section(
            'ua_db_style_tooltip',
            [
                'label' => esc_html__( 'Tooltips Style', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_db_tooltip_typography',
                'selector' => '{{WRAPPER}} .ua-db-btn[data-tooltip]::after',
            ]
        );

        $this->add_control(
            'ua_db_tooltip_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-db-btn[data-tooltip]::after' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_db_tooltip_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0f172a',
                'selectors' => [
                    '{{WRAPPER}} .ua-db-btn[data-tooltip]::after' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-db-btn[data-tooltip-pos="top"]::before'    => 'border-top-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-db-btn[data-tooltip-pos="bottom"]::before' => 'border-bottom-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-db-btn[data-tooltip-pos="left"]::before'   => 'border-left-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-db-btn[data-tooltip-pos="right"]::before'  => 'border-right-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_db_tooltip_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default'    => [
                    'top'      => 6,
                    'right'    => 6,
                    'bottom'   => 6,
                    'left'     => 6,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-db-btn[data-tooltip]::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_db_tooltip_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'top'      => 6,
                    'right'    => 12,
                    'bottom'   => 6,
                    'left'     => 12,
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-db-btn[data-tooltip]::after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render Widget Output.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $layout_mode   = ! empty( $settings['ua_db_layout_mode'] ) ? $settings['ua_db_layout_mode'] : 'connected';
        $mobile_stack  = ! empty( $settings['ua_db_mobile_stack'] ) ? $settings['ua_db_mobile_stack'] : 'mobile';
        $is_reverse    = ! empty( $settings['ua_db_mobile_reverse'] ) && 'yes' === $settings['ua_db_mobile_reverse'] ? 'ua-db-stack-reverse' : '';
        $is_full_width = ! empty( $settings['ua_db_full_width_mobile'] ) && 'yes' === $settings['ua_db_full_width_mobile'] ? 'ua-db-full-mobile' : '';

        // Button 1 Setup
        $btn1_text = ! empty( $settings['ua_db_first_text'] ) ? $settings['ua_db_first_text'] : '';
        $btn1_id   = ! empty( $settings['ua_db_first_id'] ) ? ' id="' . esc_attr( trim( $settings['ua_db_first_id'] ) ) . '"' : '';
        $this->add_link_attributes( 'ua_db_btn1_link', $settings['ua_db_first_link'] );
        $btn1_anim = ! empty( $settings['ua_db_first_animation'] ) ? self::get_animation_class( $settings['ua_db_first_animation'] ) : '';

        $btn1_tooltip_attrs = '';
        if ( ! empty( $settings['ua_db_first_tooltip_enable'] ) && 'yes' === $settings['ua_db_first_tooltip_enable'] && ! empty( $settings['ua_db_first_tooltip_text'] ) ) {
            $pos = ! empty( $settings['ua_db_first_tooltip_pos'] ) ? $settings['ua_db_first_tooltip_pos'] : 'top';
            $btn1_tooltip_attrs = ' data-tooltip="' . esc_attr( $settings['ua_db_first_tooltip_text'] ) . '" data-tooltip-pos="' . esc_attr( $pos ) . '"';
        }

        // Button 2 Setup
        $btn2_text = ! empty( $settings['ua_db_second_text'] ) ? $settings['ua_db_second_text'] : '';
        $btn2_id   = ! empty( $settings['ua_db_second_id'] ) ? ' id="' . esc_attr( trim( $settings['ua_db_second_id'] ) ) . '"' : '';
        $this->add_link_attributes( 'ua_db_btn2_link', $settings['ua_db_second_link'] );
        $btn2_anim = ! empty( $settings['ua_db_second_animation'] ) ? self::get_animation_class( $settings['ua_db_second_animation'] ) : '';

        $btn2_tooltip_attrs = '';
        if ( ! empty( $settings['ua_db_second_tooltip_enable'] ) && 'yes' === $settings['ua_db_second_tooltip_enable'] && ! empty( $settings['ua_db_second_tooltip_text'] ) ) {
            $pos = ! empty( $settings['ua_db_second_tooltip_pos'] ) ? $settings['ua_db_second_tooltip_pos'] : 'top';
            $btn2_tooltip_attrs = ' data-tooltip="' . esc_attr( $settings['ua_db_second_tooltip_text'] ) . '" data-tooltip-pos="' . esc_attr( $pos ) . '"';
        }

        // Middle Badge Setup
        $show_badge = ! empty( $settings['ua_db_badge_enable'] ) && 'yes' === $settings['ua_db_badge_enable'];
        $badge_type = ! empty( $settings['ua_db_badge_type'] ) ? $settings['ua_db_badge_type'] : 'text';
        $badge_shape = ! empty( $settings['ua_db_badge_shape'] ) ? $settings['ua_db_badge_shape'] : 'circle';
        $badge_class = 'ua-db-badge ua-db-badge-shape-' . $badge_shape;
        ?>
        <div class="ua-dual-button-wrap ua-db-stack-<?php echo esc_attr( $mobile_stack ); ?> <?php echo esc_attr( $is_reverse ); ?> <?php echo esc_attr( $is_full_width ); ?>">
            <div class="ua-db-container ua-db-<?php echo esc_attr( $layout_mode ); ?>">

                <!-- Button One -->
                <a <?php echo $this->get_render_attribute_string( 'ua_db_btn1_link' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                   <?php echo $btn1_id; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                   class="ua-db-btn ua-db-btn-first <?php echo esc_attr( $btn1_anim ); ?>"
                   <?php echo $btn1_tooltip_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
                    <?php if ( ! empty( $settings['ua_db_first_icon']['value'] ) && 'left' === $settings['ua_db_first_icon_align'] ) : ?>
                        <span class="ua-db-icon ua-db-icon-left">
                            <?php Icons_Manager::render_icon( $settings['ua_db_first_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                        </span>
                    <?php endif; ?>

                    <span class="ua-db-text"><?php echo esc_html( $btn1_text ); ?></span>

                    <?php if ( ! empty( $settings['ua_db_first_icon']['value'] ) && 'right' === $settings['ua_db_first_icon_align'] ) : ?>
                        <span class="ua-db-icon ua-db-icon-right">
                            <?php Icons_Manager::render_icon( $settings['ua_db_first_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                        </span>
                    <?php endif; ?>
                </a>

                <!-- Middle Divider Line (Pic 2 Preset) -->
                <?php if ( 'divided' === $layout_mode ) : ?>
                    <span class="ua-db-divider-line" aria-hidden="true"></span>
                <?php endif; ?>

                <!-- Middle Badge Connector -->
                <?php if ( $show_badge ) : ?>
                    <span class="<?php echo esc_attr( $badge_class ); ?>">
                        <span class="ua-db-badge-inner">
                            <?php if ( 'icon' === $badge_type && ! empty( $settings['ua_db_badge_icon']['value'] ) ) : ?>
                                <?php Icons_Manager::render_icon( $settings['ua_db_badge_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                            <?php else : ?>
                                <?php echo esc_html( ! empty( $settings['ua_db_badge_text'] ) ? $settings['ua_db_badge_text'] : 'OR' ); ?>
                            <?php endif; ?>
                        </span>
                    </span>
                <?php endif; ?>

                <!-- Button Two -->
                <a <?php echo $this->get_render_attribute_string( 'ua_db_btn2_link' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                   <?php echo $btn2_id; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                   class="ua-db-btn ua-db-btn-second <?php echo esc_attr( $btn2_anim ); ?>"
                   <?php echo $btn2_tooltip_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
                    <?php if ( ! empty( $settings['ua_db_second_icon']['value'] ) && 'left' === $settings['ua_db_second_icon_align'] ) : ?>
                        <span class="ua-db-icon ua-db-icon-left">
                            <?php Icons_Manager::render_icon( $settings['ua_db_second_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                        </span>
                    <?php endif; ?>

                    <span class="ua-db-text"><?php echo esc_html( $btn2_text ); ?></span>

                    <?php if ( ! empty( $settings['ua_db_second_icon']['value'] ) && 'right' === $settings['ua_db_second_icon_align'] ) : ?>
                        <span class="ua-db-icon ua-db-icon-right">
                            <?php Icons_Manager::render_icon( $settings['ua_db_second_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                        </span>
                    <?php endif; ?>
                </a>

            </div>
        </div>
        <?php
    }
}
