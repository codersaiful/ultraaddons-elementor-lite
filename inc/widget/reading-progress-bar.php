<?php
namespace UltraAddons\Widget;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * UltraAddons Reading Progress Bar Widget
 *
 * Lightweight, accessible, and high-performance reading progress indicator.
 * Supports tracking entire page scroll or targeted post/content sections.
 *
 * @since 1.1.0.9
 * @package UltraAddons
 */
class Reading_Progress_Bar extends Base {

    /**
     * Get widget keywords
     *
     * @since 1.1.0.9
     * @access public
     * @return array
     */
    public function get_keywords() {
        return [ 'ultraaddons-elementor-lite', 'ua', 'reading', 'progress', 'reading progress bar', 'scroll', 'scroll indicator' ];
    }

    /**
     * Register widget controls.
     *
     * @since 1.1.0.9
     * @access protected
     */
    protected function register_controls() {
        $this->ua_content_controls();
        $this->ua_style_controls();
    }

    /**
     * Content Controls
     *
     * @since 1.1.0.9
     * @access protected
     */
    protected function ua_content_controls() {
        $this->start_controls_section(
            '_ua_rpb_content_section',
            [
                'label' => esc_html__( 'Reading Progress', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ua_rpb_target_type',
            [
                'label'   => esc_html__( 'Progress Target', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'page',
                'options' => [
                    'page'     => esc_html__( 'Entire Page', 'ultraaddons-elementor-lite' ),
                    'selector' => esc_html__( 'Specific Content Area', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'ua_rpb_target_selector',
            [
                'label'       => esc_html__( 'Target CSS Selector', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '.entry-content',
                'placeholder' => esc_html__( 'e.g. .entry-content, article, #main', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'ua_rpb_target_type' => 'selector',
                ],
                'description' => esc_html__( 'Enter the CSS class or ID of the container you want to track.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_rpb_position',
            [
                'label'        => esc_html__( 'Position', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SELECT,
                'default'      => 'top',
                'options'      => [
                    'top'    => esc_html__( 'Top', 'ultraaddons-elementor-lite' ),
                    'bottom' => esc_html__( 'Bottom', 'ultraaddons-elementor-lite' ),
                ],
                'prefix_class' => 'ua-rpb-pos-',
            ]
        );

        $this->add_responsive_control(
            'ua_rpb_offset',
            [
                'label'      => esc_html__( 'Position Offset', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 200,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-reading-progress-bar-wrap.ua-rpb-pos-top'    => 'top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-reading-progress-bar-wrap.ua-rpb-pos-bottom' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Controls
     *
     * @since 1.1.0.9
     * @access protected
     */
    protected function ua_style_controls() {
        $this->start_controls_section(
            '_ua_rpb_style_section',
            [
                'label' => esc_html__( 'Progress Bar', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'ua_rpb_height',
            [
                'label'      => esc_html__( 'Height', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 1,
                        'max'  => 30,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 4,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-reading-progress-bar-wrap' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-reading-progress-bar-fill' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ua_rpb_fill_color',
            [
                'label'     => esc_html__( 'Fill Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0274be',
                'selectors' => [
                    '{{WRAPPER}} .ua-reading-progress-bar-fill' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_rpb_track_color',
            [
                'label'     => esc_html__( 'Track / Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(0, 0, 0, 0.08)',
                'selectors' => [
                    '{{WRAPPER}} .ua-reading-progress-bar-wrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_rpb_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 20,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-reading-progress-bar-wrap' => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-reading-progress-bar-fill' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ua_rpb_zindex',
            [
                'label'     => esc_html__( 'Z-Index', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 9999,
                'min'       => 0,
                'max'       => 9999999,
                'step'      => 1,
                'selectors' => [
                    '{{WRAPPER}} .ua-reading-progress-bar-wrap' => 'z-index: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on the frontend.
     *
     * @since 1.1.0.9
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $target_type     = ! empty( $settings['ua_rpb_target_type'] ) ? $settings['ua_rpb_target_type'] : 'page';
        $target_selector = ! empty( $settings['ua_rpb_target_selector'] ) ? sanitize_text_field( $settings['ua_rpb_target_selector'] ) : '.entry-content';
        $position        = ! empty( $settings['ua_rpb_position'] ) ? $settings['ua_rpb_position'] : 'top';
        $offset          = isset( $settings['ua_rpb_offset']['size'] ) ? (float) $settings['ua_rpb_offset']['size'] : 0;

        $this->add_render_attribute( 'ua-rpb-wrap', [
            'class'                => [ 'ua-reading-progress-bar-wrap', 'ua-rpb-pos-' . esc_attr( $position ) ],
            'data-target-type'     => esc_attr( $target_type ),
            'data-target-selector' => esc_attr( $target_selector ),
            'data-position'        => esc_attr( $position ),
            'data-offset'          => esc_attr( $offset ),
        ] );
        ?>
        <div <?php echo $this->get_render_attribute_string( 'ua-rpb-wrap' ); ?>>
            <div class="ua-reading-progress-bar-fill"></div>
        </div>
        <?php
    }
}
