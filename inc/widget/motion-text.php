<?php
namespace UltraAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * UltraAddons Motion Text Widget
 *
 * High-performance, zero-dependency kinetic typography animation widget.
 * Features 18 modern reveal styles (Apple mask reveal, Gaussian blur, 3D tilts, etc.)
 * with word, character, and line split capabilities.
 *
 * @package UltraAddons
 * @author Saiful Islam <codersaiful@gmail.com>
 * @version 1.2.0
 */
class Motion_Text extends Base {

    /**
     * Constructor: registers widget specific CSS and JS with cache-busting
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        $css_file = ULTRA_ADDONS_DIR . 'assets/css/widgets/motion-text.css';
        $css_ver  = file_exists( $css_file ) ? filemtime( $css_file ) : ULTRA_ADDONS_VERSION;

        wp_register_style(
            'ultraaddons-motion-text',
            ULTRA_ADDONS_ASSETS . 'css/widgets/motion-text.css',
            [],
            $css_ver,
            'all'
        );

        $js_file = ULTRA_ADDONS_DIR . 'assets/js/frontend-motion-text.js';
        $js_ver  = file_exists( $js_file ) ? filemtime( $js_file ) : ULTRA_ADDONS_VERSION;

        wp_register_script(
            'frontend-motion-text',
            ULTRA_ADDONS_ASSETS . 'js/frontend-motion-text.js',
            [ 'jquery' ],
            $js_ver,
            true
        );
    }

    /**
     * Retrieve widget style dependencies
     *
     * @return array
     */
    public function get_style_depends() {
        return [ 'ultraaddons-motion-text' ];
    }

    /**
     * Retrieve widget script dependencies
     *
     * @return array
     */
    public function get_script_depends() {
        return array_merge( parent::get_script_depends(), [ 'frontend-motion-text' ] );
    }

    /**
     * Search keywords for Elementor panel
     *
     * @return array
     */
    public function get_keywords() {
        return [
            'ultraaddons-elementor-lite',
            'motion text',
            'split text',
            'text reveal',
            'kinetic',
            'headline',
            'animated heading',
            'blur text',
            'typography',
        ];
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        $this->register_content_controls();
        $this->register_animation_controls();
        $this->register_style_controls();
    }

    /**
     * Content Tab: Heading Text & Structure Controls
     */
    protected function register_content_controls() {
        $this->start_controls_section(
            '_section_ua_mt_content',
            [
                'label' => esc_html__( 'Content', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ua_mt_heading_text',
            [
                'label'       => esc_html__( 'Heading Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXTAREA,
                'rows'        => 3,
                'default'     => esc_html__( 'Craft Exceptional Web Experiences', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'Enter your headline text here...', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'ua_mt_split_mode',
            [
                'label'       => esc_html__( 'Split By', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'word',
                'render_type' => 'template',
                'options'     => [
                    'word' => esc_html__( 'Words (Natural Wrapping)', 'ultraaddons-elementor-lite' ),
                    'char' => esc_html__( 'Characters (Kinetic Letters)', 'ultraaddons-elementor-lite' ),
                    'line' => esc_html__( 'Lines', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'ua_mt_html_tag',
            [
                'label'       => esc_html__( 'HTML Tag', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'h2',
                'render_type' => 'template',
                'separator'   => 'before',
                'options'     => [
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
            ]
        );

        $this->add_control(
            'ua_mt_link',
            [
                'label'       => esc_html__( 'Link', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $this->add_responsive_control(
            'ua_mt_align',
            [
                'label'   => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-justify',
                    ],
                ],
                'default'   => 'left',
                'selectors' => [
                    '{{WRAPPER}} .ua-motion-text-wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content Tab: Kinetic Animation Settings (18 Modern Reveal Styles)
     */
    protected function register_animation_controls() {
        $this->start_controls_section(
            '_section_ua_mt_animation',
            [
                'label' => esc_html__( 'Animation Settings', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ua_mt_animation_effect',
            [
                'label'       => esc_html__( 'Animation Effect', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'reveal-up',
                'render_type' => 'template',
                'options'     => [
                    'reveal-up'       => esc_html__( 'Mask Slide Up (Apple Style)', 'ultraaddons-elementor-lite' ),
                    'reveal-down'     => esc_html__( 'Mask Slide Down', 'ultraaddons-elementor-lite' ),
                    'reveal-left'     => esc_html__( 'Mask Slide Left', 'ultraaddons-elementor-lite' ),
                    'reveal-right'    => esc_html__( 'Mask Slide Right', 'ultraaddons-elementor-lite' ),
                    'blur-in'         => esc_html__( 'Gaussian Blur In', 'ultraaddons-elementor-lite' ),
                    'blur-up'         => esc_html__( 'Blur & Slide Up (Linear Style)', 'ultraaddons-elementor-lite' ),
                    'blur-scale'      => esc_html__( 'Blur & Zoom In', 'ultraaddons-elementor-lite' ),
                    '3d-tilt'         => esc_html__( '3D Isometric Tilt Up', 'ultraaddons-elementor-lite' ),
                    '3d-flip-down'    => esc_html__( '3D Flip Down', 'ultraaddons-elementor-lite' ),
                    '3d-rotate-y'     => esc_html__( '3D Horizontal Flip (Door Swing)', 'ultraaddons-elementor-lite' ),
                    'pop-scale'       => esc_html__( 'Soft Pop & Scale', 'ultraaddons-elementor-lite' ),
                    'zoom-in-fade'    => esc_html__( 'Zoom In from Foreground', 'ultraaddons-elementor-lite' ),
                    'elastic-bounce'  => esc_html__( 'Elastic Spring Bounce', 'ultraaddons-elementor-lite' ),
                    'skew-slide'      => esc_html__( 'Dynamic Skew & Slide', 'ultraaddons-elementor-lite' ),
                    'rotate-swing'    => esc_html__( 'Creative Angle Swing', 'ultraaddons-elementor-lite' ),
                    'wave-drop'       => esc_html__( 'Cascade Wave Drop', 'ultraaddons-elementor-lite' ),
                    'shimmer-flow'    => esc_html__( 'Luminescent Shimmer Flow', 'ultraaddons-elementor-lite' ),
                    'typewriter-fade' => esc_html__( 'Typewriter Stepped Fade', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'ua_mt_trigger',
            [
                'label'       => esc_html__( 'Trigger Mode', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'scroll',
                'render_type' => 'template',
                'options'     => [
                    'scroll' => esc_html__( 'On Scroll Into View (Observer)', 'ultraaddons-elementor-lite' ),
                    'load'   => esc_html__( 'Instant On Page Load', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'ua_mt_play_once',
            [
                'label'        => esc_html__( 'Play Once', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'render_type'  => 'template',
                'description'  => esc_html__( 'When enabled, animation only plays once. If disabled, re-triggers each time user scrolls into view.', 'ultraaddons-elementor-lite' ),
                'condition'    => [
                    'ua_mt_trigger' => 'scroll',
                ],
            ]
        );

        $this->add_control(
            'ua_mt_duration',
            [
                'label'       => esc_html__( 'Duration (Seconds)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => [ 's' ],
                'render_type' => 'template',
                'range'       => [
                    's' => [
                        'min'  => 0.2,
                        'max'  => 3.0,
                        'step' => 0.05,
                    ],
                ],
                'default'     => [
                    'unit' => 's',
                    'size' => 0.75,
                ],
            ]
        );

        $this->add_control(
            'ua_mt_stagger_delay',
            [
                'label'       => esc_html__( 'Stagger Delay (Seconds)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => [ 's' ],
                'render_type' => 'template',
                'range'       => [
                    's' => [
                        'min'  => 0.01,
                        'max'  => 0.3,
                        'step' => 0.01,
                    ],
                ],
                'default'     => [
                    'unit' => 's',
                    'size' => 0.05,
                ],
            ]
        );

        $this->add_control(
            'ua_mt_easing',
            [
                'label'       => esc_html__( 'Transition Easing', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'luxury',
                'render_type' => 'template',
                'options'     => [
                    'luxury' => esc_html__( 'Luxury Smooth (Exponential)', 'ultraaddons-elementor-lite' ),
                    'smooth' => esc_html__( 'Ease Out', 'ultraaddons-elementor-lite' ),
                    'bounce' => esc_html__( 'Elastic Bounce', 'ultraaddons-elementor-lite' ),
                    'linear' => esc_html__( 'Linear', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'ua_mt_threshold',
            [
                'label'       => esc_html__( 'Viewport Scroll Threshold', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => [ '%' ],
                'render_type' => 'template',
                'range'       => [
                    '%' => [
                        'min'  => 5,
                        'max'  => 60,
                        'step' => 5,
                    ],
                ],
                'default'     => [
                    'unit' => '%',
                    'size' => 15,
                ],
                'condition'   => [
                    'ua_mt_trigger' => 'scroll',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab: Typography & Visual Appearance Controls
     */
    protected function register_style_controls() {
        $this->start_controls_section(
            '_section_ua_mt_style_heading',
            [
                'label' => esc_html__( 'Heading Style', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_mt_typography',
                'selector' => '{{WRAPPER}} .ua-motion-heading, {{WRAPPER}} .ua-mt-unit',
            ]
        );

        $this->add_control(
            'ua_mt_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#2563eb',
                'selectors' => [
                    '{{WRAPPER}} .ua-motion-heading .ua-mt-unit' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Gradient Fill Feature
        $this->add_control(
            'ua_mt_is_gradient',
            [
                'label'        => esc_html__( 'Gradient Text', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'ua_mt_gradient_color_1',
            [
                'label'     => esc_html__( 'Gradient Color 1', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#3b82f6',
                'condition' => [
                    'ua_mt_is_gradient' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ua_mt_gradient_color_2',
            [
                'label'     => esc_html__( 'Gradient Color 2', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#9333ea',
                'condition' => [
                    'ua_mt_is_gradient' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ua_mt_gradient_angle',
            [
                'label'      => esc_html__( 'Gradient Angle (deg)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'deg' ],
                'range'      => [
                    'deg' => [
                        'min'  => 0,
                        'max'  => 360,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'deg',
                    'size' => 135,
                ],
                'condition'  => [
                    'ua_mt_is_gradient' => 'yes',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-motion-heading' => '--ua-mt-gradient: linear-gradient({{SIZE}}deg, {{ua_mt_gradient_color_1.VALUE}}, {{ua_mt_gradient_color_2.VALUE}});',
                ],
            ]
        );

        // Text Stroke (Outline) Feature
        $this->add_control(
            'ua_mt_is_stroke',
            [
                'label'        => esc_html__( 'Text Stroke (Outline)', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'ua_mt_stroke_width',
            [
                'label'      => esc_html__( 'Stroke Width', 'ultraaddons-elementor-lite' ),
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
                    'size' => 1,
                ],
                'condition'  => [
                    'ua_mt_is_stroke' => 'yes',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-motion-heading' => '--ua-mt-stroke-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ua_mt_stroke_color',
            [
                'label'     => esc_html__( 'Stroke Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1f2937',
                'condition' => [
                    'ua_mt_is_stroke' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-motion-heading' => '--ua-mt-stroke-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_mt_stroke_transparent',
            [
                'label'        => esc_html__( 'Transparent Fill (Outline Only)', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'condition'    => [
                    'ua_mt_is_stroke' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'      => 'ua_mt_text_shadow',
                'selector'  => '{{WRAPPER}} .ua-motion-heading .ua-mt-unit',
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on frontend and editor preview
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $raw_text = ! empty( $settings['ua_mt_heading_text'] ) ? $settings['ua_mt_heading_text'] : '';
        if ( empty( $raw_text ) ) {
            return;
        }

        // Validate HTML tag
        $valid_tags = [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p' ];
        $tag = ! empty( $settings['ua_mt_html_tag'] ) && in_array( strtolower( $settings['ua_mt_html_tag'] ), $valid_tags, true )
            ? strtolower( $settings['ua_mt_html_tag'] )
            : 'h2';

        // Link handling
        $link_url      = ! empty( $settings['ua_mt_link']['url'] ) ? esc_url( $settings['ua_mt_link']['url'] ) : '';
        $link_target   = ! empty( $settings['ua_mt_link']['is_external'] ) ? ' target="_blank"' : '';
        $link_nofollow = ! empty( $settings['ua_mt_link']['nofollow'] ) ? ' rel="nofollow"' : '';

        $split_mode = ! empty( $settings['ua_mt_split_mode'] ) ? $settings['ua_mt_split_mode'] : 'word';
        $effect     = ! empty( $settings['ua_mt_animation_effect'] ) ? $settings['ua_mt_animation_effect'] : 'reveal-up';

        $heading_classes = [
            'ua-motion-heading',
            'ua-mt-effect-' . sanitize_html_class( $effect ),
            'ua-mt-split-' . sanitize_html_class( $split_mode ),
        ];

        if ( ! empty( $settings['ua_mt_is_gradient'] ) && 'yes' === $settings['ua_mt_is_gradient'] ) {
            $heading_classes[] = 'ua-mt-is-gradient';
        }

        if ( ! empty( $settings['ua_mt_is_stroke'] ) && 'yes' === $settings['ua_mt_is_stroke'] ) {
            $heading_classes[] = 'ua-mt-is-stroke';
            if ( ! empty( $settings['ua_mt_stroke_transparent'] ) && 'yes' === $settings['ua_mt_stroke_transparent'] ) {
                $heading_classes[] = 'ua-mt-stroke-transparent';
            }
        }

        $duration   = isset( $settings['ua_mt_duration']['size'] ) ? (float) $settings['ua_mt_duration']['size'] : 0.75;
        $stagger    = isset( $settings['ua_mt_stagger_delay']['size'] ) ? (float) $settings['ua_mt_stagger_delay']['size'] : 0.05;
        $threshold  = isset( $settings['ua_mt_threshold']['size'] ) ? ( (float) $settings['ua_mt_threshold']['size'] / 100 ) : 0.15;
        $play_once  = ! isset( $settings['ua_mt_play_once'] ) || 'yes' === $settings['ua_mt_play_once'];
        $trigger    = ! empty( $settings['ua_mt_trigger'] ) ? $settings['ua_mt_trigger'] : 'scroll';
        $easing     = ! empty( $settings['ua_mt_easing'] ) ? $settings['ua_mt_easing'] : 'luxury';

        $js_settings = [
            'effect'    => $effect,
            'trigger'   => $trigger,
            'play_once' => $play_once,
            'duration'  => $duration,
            'stagger'   => $stagger,
            'threshold' => $threshold,
            'easing'    => $easing,
        ];

        $split_content = $this->build_split_markup( $raw_text, $split_mode );

        ?>
        <div class="ua-motion-text-wrapper" data-ua-mt-settings="<?php echo esc_attr( wp_json_encode( $js_settings ) ); ?>">
            <<?php echo esc_attr( $tag ); ?> class="<?php echo esc_attr( implode( ' ', $heading_classes ) ); ?>">
                <?php if ( ! empty( $link_url ) ) : ?>
                    <a href="<?php echo esc_url( $link_url ); ?>"<?php echo $link_target . $link_nofollow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
                        <?php echo $split_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </a>
                <?php else : ?>
                    <?php echo $split_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php endif; ?>
            </<?php echo esc_attr( $tag ); ?>>
        </div>
        <?php
    }

    /**
     * Helper: Splits plain or formatted text into structured span masks and units
     *
     * @param string $text Raw input text.
     * @param string $mode Split mode ('word', 'char', 'line').
     * @return string Structured HTML markup.
     */
    protected function build_split_markup( $text, $mode ) {
        $text = wp_strip_all_tags( $text );
        $output = '';
        $global_index = 0;

        if ( 'line' === $mode ) {
            $lines = preg_split( '/\r\n|\r|\n/', $text );
            foreach ( $lines as $line ) {
                $trimmed = trim( $line );
                if ( '' === $trimmed ) {
                    continue;
                }
                $output .= '<span class="ua-mt-line-wrap"><span class="ua-mt-mask"><span class="ua-mt-unit" style="--ua-mt-index: ' . (int) $global_index . ';">' . esc_html( $trimmed ) . '</span></span></span>';
                $global_index++;
            }
            return $output;
        }

        $words = preg_split( '/\s+/', trim( $text ) );
        $word_count = count( $words );

        foreach ( $words as $w_idx => $word ) {
            if ( '' === $word ) {
                continue;
            }

            if ( 'char' === $mode ) {
                $chars = preg_split( '//u', $word, -1, PREG_SPLIT_NO_EMPTY );
                $output .= '<span class="ua-mt-word-block" style="display: inline-block; white-space: nowrap;">';
                foreach ( $chars as $char ) {
                    $output .= '<span class="ua-mt-mask"><span class="ua-mt-unit" style="--ua-mt-index: ' . (int) $global_index . ';">' . esc_html( $char ) . '</span></span>';
                    $global_index++;
                }
                $output .= '</span>';
            } else {
                $output .= '<span class="ua-mt-mask"><span class="ua-mt-unit" style="--ua-mt-index: ' . (int) $global_index . ';">' . esc_html( $word ) . '</span></span>';
                $global_index++;
            }

            if ( $w_idx < ( $word_count - 1 ) ) {
                $output .= '<span class="ua-mt-spacer"> </span>';
            }
        }

        return $output;
    }
}