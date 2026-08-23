<?php
namespace UltraAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || die();

/**
 * Progress Bar widget.
 *
 * A native UltraAddons implementation inspired by the interaction model of
 * modern Elementor progress widgets. It has no dependency on Royal Addons or
 * the legacy jQuery Barfiller library.
 */
class Progress_Bar extends Base {

	/**
	 * Widget search keywords.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'progress bar', 'skill bar', 'percentage', 'circle progress', 'vertical progress', 'ultraaddons' ];
	}

	/**
	 * Register widget controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->register_content_controls();
		$this->register_animation_controls();
		$this->register_layout_style_controls();
		$this->register_progress_style_controls();
		$this->register_text_style_controls();
	}

	/**
	 * Content controls.
	 *
	 * @return void
	 */
	private function register_content_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Progress Bar', 'ultraaddons-elementor-lite' ),
			]
		);

		$this->add_control(
			'layout',
			[
				'label'   => esc_html__( 'Layout', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => esc_html__( 'Horizontal Line', 'ultraaddons-elementor-lite' ),
					'circle'     => esc_html__( 'Circle', 'ultraaddons-elementor-lite' ),
					'vertical'   => esc_html__( 'Vertical Line', 'ultraaddons-elementor-lite' ),
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'max_value',
			[
				'label'   => esc_html__( 'Maximum Value', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 100,
				'min'     => 1,
				'step'    => 1,
				'dynamic' => [ 'active' => true ],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'counter_value',
			[
				'label'   => esc_html__( 'Current Value', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 70,
				'min'     => 0,
				'step'    => 1,
				'dynamic' => [ 'active' => true ],
			]
		);

		$this->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Project Progress', 'ultraaddons-elementor-lite' ),
				'label_block' => true,
				'dynamic'     => [ 'active' => true ],
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'subtitle',
			[
				'label'       => esc_html__( 'Subtitle', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'dynamic'     => [ 'active' => true ],
			]
		);

		$this->add_control(
			'title_position',
			[
				'label'   => esc_html__( 'Title Position', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'inside',
				'options' => [
					'inside'  => esc_html__( 'Inside', 'ultraaddons-elementor-lite' ),
					'outside' => esc_html__( 'Outside', 'ultraaddons-elementor-lite' ),
				],
			]
		);

		$this->add_control(
			'show_counter',
			[
				'label'        => esc_html__( 'Show Counter', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'counter_position',
			[
				'label'   => esc_html__( 'Counter Position', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'inside',
				'options' => [
					'inside'  => esc_html__( 'Inside', 'ultraaddons-elementor-lite' ),
					'outside' => esc_html__( 'Outside', 'ultraaddons-elementor-lite' ),
				],
				'condition' => [ 'show_counter' => 'yes' ],
			]
		);

		$this->add_control(
			'counter_follow_line',
			[
				'label'        => esc_html__( 'Follow Progress Line', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'show_counter'     => 'yes',
					'counter_position' => 'inside',
					'layout'           => 'horizontal',
				],
			]
		);

		$this->add_control(
			'counter_prefix',
			[
				'label'       => esc_html__( 'Counter Prefix', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'dynamic'     => [ 'active' => true ],
				'condition'   => [ 'show_counter' => 'yes' ],
			]
		);

		$this->add_control(
			'counter_suffix',
			[
				'label'       => esc_html__( 'Counter Suffix', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '%',
				'label_block' => true,
				'dynamic'     => [ 'active' => true ],
				'condition'   => [ 'show_counter' => 'yes' ],
			]
		);

		$this->add_control(
			'counter_separator',
			[
				'label'        => esc_html__( 'Thousands Separator', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition'    => [ 'show_counter' => 'yes' ],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Animation controls.
	 *
	 * @return void
	 */
	private function register_animation_controls() {
		$this->start_controls_section(
			'section_animation',
			[
				'label' => esc_html__( 'Animation', 'ultraaddons-elementor-lite' ),
			]
		);

		$this->add_control(
			'animation_duration',
			[
				'label'   => esc_html__( 'Duration (Seconds)', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 1,
				'min'     => 0,
				'max'     => 10,
				'step'    => 0.1,
			]
		);

		$this->add_control(
			'animation_delay',
			[
				'label'   => esc_html__( 'Delay (Seconds)', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 0,
				'min'     => 0,
				'max'     => 5,
				'step'    => 0.1,
			]
		);

		$this->add_control(
			'animation_timing',
			[
				'label'   => esc_html__( 'Timing Function', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'ease',
				'options' => [
					'linear'      => esc_html__( 'Linear', 'ultraaddons-elementor-lite' ),
					'ease'        => esc_html__( 'Ease', 'ultraaddons-elementor-lite' ),
					'ease-in'     => esc_html__( 'Ease In', 'ultraaddons-elementor-lite' ),
					'ease-out'    => esc_html__( 'Ease Out', 'ultraaddons-elementor-lite' ),
					'ease-in-out' => esc_html__( 'Ease In Out', 'ultraaddons-elementor-lite' ),
				],
				'selectors' => [
					'{{WRAPPER}} .ua-progress-line-inner, {{WRAPPER}} .ua-progress-circle-value' => 'transition-timing-function: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'animation_loop',
			[
				'label'        => esc_html__( 'Animation Loop', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'animation_loop_delay',
			[
				'label'     => esc_html__( 'Loop Pause (Seconds)', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1.5,
				'min'       => 0.25,
				'max'       => 20,
				'step'      => 0.25,
				'condition' => [ 'animation_loop' => 'yes' ],
			]
		);

		$this->add_control(
			'show_stripes',
			[
				'label'        => esc_html__( 'Show Stripes', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
				'condition'    => [ 'layout!' => 'circle' ],
			]
		);

		$this->add_control(
			'stripe_animation',
			[
				'label'   => esc_html__( 'Stripe Animation', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'none'  => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
					'right' => esc_html__( 'Move Right', 'ultraaddons-elementor-lite' ),
					'left'  => esc_html__( 'Move Left', 'ultraaddons-elementor-lite' ),
				],
				'condition' => [
					'layout!'      => 'circle',
					'show_stripes' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Layout style controls.
	 *
	 * @return void
	 */
	private function register_layout_style_controls() {
		$this->start_controls_section(
			'section_layout_style',
			[
				'label' => esc_html__( 'Layout', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'circle_size',
			[
				'label'      => esc_html__( 'Circle Size', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [ 'px' => [ 'min' => 100, 'max' => 600 ] ],
				'default'    => [ 'unit' => 'px', 'size' => 200 ],
				'selectors'  => [ '{{WRAPPER}} .ua-progress-circle' => 'max-width: {{SIZE}}{{UNIT}};' ],
				'condition'  => [ 'layout' => 'circle' ],
			]
		);

		$this->add_control(
			'circle_line_width',
			[
				'label'   => esc_html__( 'Circle Line Width', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => [ 'px' => [ 'min' => 2, 'max' => 50 ] ],
				'default' => [ 'unit' => 'px', 'size' => 14 ],
				'condition' => [ 'layout' => 'circle' ],
				'render_type' => 'template',
			]
		);

		$this->add_responsive_control(
			'line_size',
			[
				'label'      => esc_html__( 'Line Size', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [ 'px' => [ 'min' => 4, 'max' => 100 ] ],
				'default'    => [ 'unit' => 'px', 'size' => 28 ],
				'selectors'  => [
					'{{WRAPPER}} .ua-progress-layout-horizontal .ua-progress-line' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ua-progress-layout-vertical .ua-progress-line'   => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [ 'layout!' => 'circle' ],
			]
		);

		$this->add_responsive_control(
			'vertical_height',
			[
				'label'      => esc_html__( 'Vertical Line Height', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [ 'px' => [ 'min' => 100, 'max' => 800 ] ],
				'default'    => [ 'unit' => 'px', 'size' => 280 ],
				'selectors'  => [ '{{WRAPPER}} .ua-progress-layout-vertical .ua-progress-line' => 'height: {{SIZE}}{{UNIT}};' ],
				'condition'  => [ 'layout' => 'vertical' ],
			]
		);

		$this->add_responsive_control(
			'content_gap',
			[
				'label'      => esc_html__( 'Content Gap', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
				'default'    => [ 'unit' => 'px', 'size' => 10 ],
				'selectors'  => [
					'{{WRAPPER}} .ua-progress-content-outside.ua-progress-content-before' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ua-progress-content-outside.ua-progress-content-after'  => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Progress line style controls.
	 *
	 * @return void
	 */
	private function register_progress_style_controls() {
		$this->start_controls_section(
			'section_progress_style',
			[
				'label' => esc_html__( 'Progress Line', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'track_color',
			[
				'label'   => esc_html__( 'Track Color', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#e8e8ee',
				'selectors' => [
					'{{WRAPPER}} .ua-progress-line'         => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .ua-progress-circle-track' => 'stroke: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'progress_background',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .ua-progress-line-inner',
				'condition' => [ 'layout!' => 'circle' ],
			]
		);

		$this->add_control(
			'circle_progress_type',
			[
				'label'   => esc_html__( 'Circle Color Type', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'classic',
				'options' => [
					'classic'  => [ 'title' => esc_html__( 'Classic', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-paint-brush' ],
					'gradient' => [ 'title' => esc_html__( 'Gradient', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-barcode' ],
				],
				'condition' => [ 'layout' => 'circle' ],
			]
		);

		$this->add_control(
			'circle_progress_color',
			[
				'label'     => esc_html__( 'Progress Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#605be5',
				'condition' => [ 'layout' => 'circle', 'circle_progress_type' => 'classic' ],
			]
		);

		$this->add_control(
			'circle_gradient_start',
			[
				'label'     => esc_html__( 'Gradient Start', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#61ce70',
				'condition' => [ 'layout' => 'circle', 'circle_progress_type' => 'gradient' ],
			]
		);

		$this->add_control(
			'circle_gradient_end',
			[
				'label'     => esc_html__( 'Gradient End', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#4054b2',
				'condition' => [ 'layout' => 'circle', 'circle_progress_type' => 'gradient' ],
			]
		);

		$this->add_control(
			'rounded_line',
			[
				'label'        => esc_html__( 'Rounded Progress Line', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'selectors'    => [ '{{WRAPPER}} .ua-progress-circle-value' => 'stroke-linecap: round;' ],
				'separator'    => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'track_border',
				'selector'  => '{{WRAPPER}} .ua-progress-line',
				'condition' => [ 'layout!' => 'circle' ],
			]
		);

		$this->add_responsive_control(
			'track_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [ 'top' => 8, 'right' => 8, 'bottom' => 8, 'left' => 8, 'unit' => 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .ua-progress-line'       => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ua-progress-line-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [ 'layout!' => 'circle' ],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'track_shadow',
				'selector' => '{{WRAPPER}} .ua-progress-line, {{WRAPPER}} .ua-progress-circle-svg',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Text style controls.
	 *
	 * @return void
	 */
	private function register_text_style_controls() {
		$this->start_controls_section(
			'section_text_style',
			[
				'label' => esc_html__( 'Text', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Title Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#2f3045',
				'selectors' => [ '{{WRAPPER}} .ua-progress-title' => 'color: {{VALUE}};' ],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .ua-progress-title',
			]
		);

		$this->add_control(
			'subtitle_color',
			[
				'label'     => esc_html__( 'Subtitle Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#73758c',
				'selectors' => [ '{{WRAPPER}} .ua-progress-subtitle' => 'color: {{VALUE}};' ],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'subtitle_typography',
				'selector' => '{{WRAPPER}} .ua-progress-subtitle',
			]
		);

		$this->add_control(
			'counter_color',
			[
				'label'     => esc_html__( 'Counter Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#2f3045',
				'selectors' => [ '{{WRAPPER}} .ua-progress-counter' => 'color: {{VALUE}};' ],
				'separator' => 'before',
				'condition' => [ 'show_counter' => 'yes' ],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'counter_typography',
				'selector'  => '{{WRAPPER}} .ua-progress-counter',
				'condition' => [ 'show_counter' => 'yes' ],
			]
		);

		$this->add_responsive_control(
			'inside_padding',
			[
				'label'      => esc_html__( 'Inside Padding', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'default'    => [ 'top' => 0, 'right' => 12, 'bottom' => 0, 'left' => 12, 'unit' => 'px' ],
				'selectors'  => [ '{{WRAPPER}} .ua-progress-content-inside' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
				'separator'  => 'before',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render a counter.
	 *
	 * @param array $settings Widget settings.
	 * @param bool  $follow Whether this counter follows the line.
	 * @return void
	 */
	private function render_counter( $settings, $follow = false ) {
		if ( 'yes' !== $settings['show_counter'] ) {
			return;
		}

		$classes = 'ua-progress-counter';
		if ( $follow ) {
			$classes .= ' ua-progress-follow-counter';
		}
		?>
		<div class="<?php echo esc_attr( $classes ); ?>" aria-hidden="true">
			<?php if ( '' !== $settings['counter_prefix'] ) : ?>
				<span class="ua-progress-counter-prefix"><?php echo esc_html( $settings['counter_prefix'] ); ?></span>
			<?php endif; ?>
			<span class="ua-progress-counter-value">0</span>
			<?php if ( '' !== $settings['counter_suffix'] ) : ?>
				<span class="ua-progress-counter-suffix"><?php echo esc_html( $settings['counter_suffix'] ); ?></span>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render title, subtitle, and counter for a requested position.
	 *
	 * @param string $position inside|outside.
	 * @param array  $settings Widget settings.
	 * @param string $placement before|after.
	 * @return void
	 */
	private function render_progress_content( $position, $settings, $placement = 'before' ) {
		$show_title = '' !== $settings['title'] && $position === $settings['title_position'];
		$show_subtitle = '' !== $settings['subtitle'] && $position === $settings['title_position'];
		$counter_follows = 'horizontal' === $settings['layout'] && 'inside' === $position && 'yes' === $settings['counter_follow_line'];
		$show_counter = 'yes' === $settings['show_counter'] && $position === $settings['counter_position'] && ! $counter_follows;

		if ( ! $show_title && ! $show_subtitle && ! $show_counter ) {
			return;
		}

		$classes = 'ua-progress-content ua-progress-content-' . $position;
		if ( 'outside' === $position ) {
			$classes .= ' ua-progress-content-' . $placement;
		}
		?>
		<div class="<?php echo esc_attr( $classes ); ?>">
			<?php if ( $show_title || $show_subtitle ) : ?>
				<div class="ua-progress-title-wrap">
					<?php if ( $show_title ) : ?>
						<div class="ua-progress-title"><?php echo esc_html( $settings['title'] ); ?></div>
					<?php endif; ?>
					<?php if ( $show_subtitle ) : ?>
						<div class="ua-progress-subtitle"><?php echo esc_html( $settings['subtitle'] ); ?></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<?php if ( $show_counter ) : ?>
				<?php $this->render_counter( $settings ); ?>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render the horizontal progress bar.
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	private function render_horizontal( $settings ) {
		$this->render_progress_content( 'outside', $settings, 'before' );
		?>
		<div class="ua-progress-line">
			<div class="ua-progress-line-inner">
				<?php if ( 'yes' === $settings['show_counter'] && 'inside' === $settings['counter_position'] && 'yes' === $settings['counter_follow_line'] ) : ?>
					<?php $this->render_counter( $settings, true ); ?>
				<?php endif; ?>
			</div>
			<?php $this->render_progress_content( 'inside', $settings ); ?>
		</div>
		<?php
	}

	/**
	 * Render the vertical progress bar.
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	private function render_vertical( $settings ) {
		$this->render_progress_content( 'outside', $settings, 'before' );
		?>
		<div class="ua-progress-line">
			<div class="ua-progress-line-inner"></div>
			<?php $this->render_progress_content( 'inside', $settings ); ?>
		</div>
		<?php
	}

	/**
	 * Render the circular progress bar.
	 *
	 * @param array $settings Widget settings.
	 * @param float $percentage Calculated percentage.
	 * @return void
	 */
	private function render_circle( $settings, $percentage ) {
		$circle_size = isset( $settings['circle_size']['size'] ) ? max( 100, (float) $settings['circle_size']['size'] ) : 200;
		$line_width = isset( $settings['circle_line_width']['size'] ) ? max( 2, (float) $settings['circle_line_width']['size'] ) : 14;
		$line_width = min( $line_width, $circle_size / 2 );
		$half_size = $circle_size / 2;
		$radius = $half_size - ( $line_width / 2 );
		$perimeter = 2 * M_PI * $radius;
		$target_offset = $perimeter - ( $perimeter * $percentage / 100 );
		$gradient_id = 'ua-progress-gradient-' . $this->get_id();

		$this->render_progress_content( 'outside', $settings, 'before' );
		?>
		<div class="ua-progress-circle">
			<svg class="ua-progress-circle-svg" viewBox="0 0 <?php echo esc_attr( $circle_size ); ?> <?php echo esc_attr( $circle_size ); ?>" role="presentation" focusable="false">
				<?php if ( 'gradient' === $settings['circle_progress_type'] ) : ?>
					<defs>
						<linearGradient id="<?php echo esc_attr( $gradient_id ); ?>" x1="0" y1="0" x2="1" y2="1">
							<stop offset="0%" stop-color="<?php echo esc_attr( $settings['circle_gradient_start'] ); ?>"></stop>
							<stop offset="100%" stop-color="<?php echo esc_attr( $settings['circle_gradient_end'] ); ?>"></stop>
						</linearGradient>
					</defs>
				<?php endif; ?>
				<circle class="ua-progress-circle-track" cx="<?php echo esc_attr( $half_size ); ?>" cy="<?php echo esc_attr( $half_size ); ?>" r="<?php echo esc_attr( $radius ); ?>" fill="none" stroke-width="<?php echo esc_attr( $line_width ); ?>"></circle>
				<circle
					class="ua-progress-circle-value"
					cx="<?php echo esc_attr( $half_size ); ?>"
					cy="<?php echo esc_attr( $half_size ); ?>"
					r="<?php echo esc_attr( $radius ); ?>"
					fill="none"
					stroke="<?php echo 'gradient' === $settings['circle_progress_type'] ? 'url(#' . esc_attr( $gradient_id ) . ')' : esc_attr( $settings['circle_progress_color'] ); ?>"
					stroke-width="<?php echo esc_attr( $line_width ); ?>"
					stroke-dasharray="<?php echo esc_attr( $perimeter ); ?>"
					stroke-dashoffset="<?php echo esc_attr( $perimeter ); ?>"
					data-initial-offset="<?php echo esc_attr( $perimeter ); ?>"
					data-target-offset="<?php echo esc_attr( $target_offset ); ?>"
				></circle>
			</svg>
			<?php $this->render_progress_content( 'inside', $settings ); ?>
		</div>
		<?php
	}

	/**
	 * Render widget output.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$allowed_layouts = [ 'horizontal', 'circle', 'vertical' ];
		$layout = in_array( $settings['layout'], $allowed_layouts, true ) ? $settings['layout'] : 'horizontal';
		$max_value = max( 1, (float) $settings['max_value'] );
		$counter_value = min( $max_value, max( 0, (float) $settings['counter_value'] ) );
		$percentage = min( 100, max( 0, ( $counter_value / $max_value ) * 100 ) );
		$duration = max( 0, (float) $settings['animation_duration'] ) * 1000;
		$delay = max( 0, (float) $settings['animation_delay'] ) * 1000;
		$loop_delay = max( 0.25, (float) $settings['animation_loop_delay'] ) * 1000;
		$options = [
			'value'      => $counter_value,
			'percentage' => round( $percentage, 4 ),
			'duration'   => $duration,
			'delay'      => $delay,
			'loop'       => 'yes' === $settings['animation_loop'] ? 'yes' : 'no',
			'loopDelay'  => $loop_delay,
			'separator'  => 'yes' === $settings['counter_separator'] ? 'yes' : 'no',
		];
		$classes = [
			'ua-progress-bar',
			'ua-progress-layout-' . $layout,
			'ua-progress-title-' . sanitize_html_class( $settings['title_position'] ),
			'ua-progress-counter-' . sanitize_html_class( $settings['counter_position'] ),
		];

		if ( 'yes' === $settings['rounded_line'] ) {
			$classes[] = 'ua-progress-rounded';
		}

		if ( 'circle' !== $layout && 'yes' === $settings['show_stripes'] ) {
			$classes[] = 'ua-progress-striped';
			$classes[] = 'ua-progress-stripe-' . sanitize_html_class( $settings['stripe_animation'] );
		}

		$this->add_render_attribute(
			'wrapper',
			[
				'class'         => $classes,
				'role'          => 'progressbar',
				'aria-valuemin' => '0',
				'aria-valuemax' => $max_value,
				'aria-valuenow' => $counter_value,
				'aria-label'    => '' !== $settings['title'] ? $settings['title'] : esc_html__( 'Progress', 'ultraaddons-elementor-lite' ),
				'data-options'  => wp_json_encode( $options ),
			]
		);
		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php
			if ( 'circle' === $layout ) {
				$this->render_circle( $settings, $percentage );
			} elseif ( 'vertical' === $layout ) {
				$this->render_vertical( $settings );
			} else {
				$this->render_horizontal( $settings );
			}
			?>
		</div>
		<?php
	}
}
