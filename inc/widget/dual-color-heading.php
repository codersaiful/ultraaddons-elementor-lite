<?php 
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Dual Color Heading Widget
 * Do something awesome with heading elements
 * 
 * Credit:
 * 
 * 
 * @since 1.1.0.7
 * @package UltraAddons
 * @author Saiful islam <codersaiful@gmail.com>
 * @author Rafiul <bmrafiul.alam@gmail.com>
 */
class Dual_Color_Heading extends Base{

    /**
     * Get your widget name
     *
     * Retrieve oEmbed widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string keywords
     */
    public function get_keywords() {
        return [ 'ultraaddons', 'ua', 'heading', 'dual', 'header', 'title' ];
    }
	
	 /**
     * Register widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {
        //For Content Section
        $this->dual_heading_content_controls();
        //For Design Section Style Tab
        $this->dual_color_heading_style_controls();
		//For Typography Style Tab
        //$this->style_typography_controls();
		//For Box Style Tab
        //$this->style_box_controls();
    }
	protected function dual_heading_content_controls() {
		
		
        $this->start_controls_section(
            '_ua_dual_heading_content',
            [
                'label'     => esc_html__( 'Content', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
		$this->add_control(
			'_ua_dual_tag_selection',
			[
				'label'   => esc_html__( 'Select Tag', 'ultraaddons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h1'   => esc_html__( 'H1', 'ultraaddons' ),
					'h2'   => esc_html__( 'H2', 'ultraaddons' ),
					'h3'   => esc_html__( 'H3', 'ultraaddons' ),
					'h4'   => esc_html__( 'H4', 'ultraaddons' ),
					'h5'   => esc_html__( 'H5', 'ultraaddons' ),
					'h6'   => esc_html__( 'H6', 'ultraaddons' ),
					'div'  => esc_html__( 'div', 'ultraaddons' ),
					'span' => esc_html__( 'span', 'ultraaddons' ),
					'p'    => esc_html__( 'p', 'ultraaddons' ),
				],
				'default' => 'h3',
			]
		);
		$this->add_control(
			'_ua_dual_before_heading_text',
			[

				'label'    => esc_html__( 'Before Text', 'ultraaddons' ),
				'type'     => Controls_Manager::TEXT,
				'selector' => '{{WRAPPER}} .ua-heading-text',
				'dynamic'  => [
					'active' => true,
				],
				'default'  => esc_html__( 'I love', 'ultraaddons' ),
			]
		);
		$this->add_control(
			'_ua_dual_second_heading_text',
			[
				'label'    => esc_html__( 'Highlighted Text', 'ultraaddons' ),
				'type'     => Controls_Manager::TEXT,
				'selector' => '{{WRAPPER}} .ua-highlight-text',
				'dynamic'  => [
					'active' => true,
				],
				'default'  => esc_html__( 'this website', 'ultraaddons' ),
			]
		);
		$this->add_control(
			'_ua_dual_after_heading_text',
			[
				'label'    => esc_html__( 'After Text', 'ultraaddons' ),
				'type'     => Controls_Manager::TEXT,
				'dynamic'  => [
					'active' => true,
				],
				'selector' => '{{WRAPPER}} .ua-dual-heading-text',
			]
		);
		$this->add_control(
			'_ua_dual_heading_link',
			[
				'label'       => esc_html__( 'Link', 'ultraaddons' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'ultraaddons' ),
				'dynamic'     => [
					'active' => true,
				],
				'default'     => [
					'url' => '',
				],
			]
		);
		$this->end_controls_section();
	}
	 protected function dual_color_heading_style_controls() {
        $this->start_controls_section(
            '_ua_dual_color_heading_style',
            [
                'label'     => esc_html__( 'General', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		
		$this->add_responsive_control(
			'dual_color_alignment',
			[
				'label'     => esc_html__( 'Alignment', 'ultraaddons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'ultraaddons' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'ultraaddons' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'ultraaddons' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'   => 'left',
				'selectors' => [
					'{{WRAPPER}} .ua-dual-color-heading' => 'text-align: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'_ua_heading_layout',
			[
				'label'        => esc_html__( 'Layout', 'ultraaddons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Stack', 'ultraaddons' ),
				'label_off'    => esc_html__( 'Inline', 'ultraaddons' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'prefix_class' => 'ua-stack-desktop-',
			]
		);
		$this->add_control(
			'_ua_heading_stack_on',
			[
				'label'        => esc_html__( 'Responsive Support', 'ultraaddons' ),
				'description'  => esc_html__( 'Choose on what breakpoint the heading will stack.', 'ultraaddons' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'none',
				'options'      => [
					'none'   => esc_html__( 'No', 'ultraaddons' ),
					'tablet' => esc_html__( 'For Tablet & Mobile', 'ultraaddons' ),
					'mobile' => esc_html__( 'For Mobile Only', 'ultraaddons' ),
				],
				'condition'    => [
					'_ua_heading_layout!' => 'yes',
				],
				'prefix_class' => 'ua-heading-stack-',
			]
		);
		$this->add_responsive_control(
			'_ua_heading_margin',
			[
				'label'      => esc_html__( 'Spacing Between Headings', 'ultraaddons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'default'    => [
					'size' => '10',
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .ua-before-heading' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ua-after-heading'  => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.ua-stack-desktop-yes .ua-before-heading' => 'margin-bottom: {{SIZE}}{{UNIT}}; margin-right: 0px; display: inline-block;',
					'{{WRAPPER}}.ua-stack-desktop-yes .ua-after-heading' => 'margin-top: {{SIZE}}{{UNIT}}; margin-left: 0px; display: inline-block;',
					'(tablet){{WRAPPER}}.ua-heading-stack-tablet .ua-before-heading ' => 'margin-bottom: {{SIZE}}{{UNIT}}; margin-right: 0px; display: inline-block;',
					'(tablet){{WRAPPER}}.ua-heading-stack-tablet .ua-after-heading ' => 'margin-top: {{SIZE}}{{UNIT}}; margin-left: 0px; display: inline-block;',
					'(mobile){{WRAPPER}}.ua-heading-stack-mobile .ua-before-heading ' => 'margin-bottom: {{SIZE}}{{UNIT}}; margin-right: 0px; display: inline-block;',
					'(mobile){{WRAPPER}}.ua-heading-stack-mobile .ua-after-heading ' => 'margin-top: {{SIZE}}{{UNIT}}; margin-left: 0px; display: inline-block;',
				],
			]
		);
		
		$this->end_controls_section();
	}
	protected function style_box_controls() {
        $this->start_controls_section(
            '_ua_flipbox_box_style',
            [
                'label'     => esc_html__( 'Box Style', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		
		$this->end_controls_section();
	}

   protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="ua-module-content ua-dual-color-heading">
			<<?php echo $settings['_ua_dual_tag_selection']; ?>>
				<?php if ( ! empty( $settings['_ua_dual_heading_link']['url'] ) ) { ?>
					<a href="<?php echo esc_url( $settings['_ua_dual_heading_link']['url'] ); ?>"
					<?php if ( 'on' == $settings['_ua_dual_heading_link']['is_external'] ): ?>
						target="_blank"
					<?php endif; ?>
					<?php if ( 'on' == $settings['_ua_dual_heading_link']['nofollow'] ): ?>
						rel="nofollow"
					<?php endif; ?>>
				<?php } ?>
						<span class="ua-before-heading"><span class="ua-dual-heading-text ua-first-text">
						<?php echo $this->get_settings_for_display( '_ua_dual_before_heading_text'); ?></span></span><span class="ua-adv-heading-stack"><span class="ua-dual-heading-text ua-highlight-text">
						<?php echo $this->get_settings_for_display( '_ua_dual_second_heading_text'); ?></span></span><?php if ( ! empty( $settings['after_heading_text'] ) ) { ?><span class="ua-after-heading"><span class="ua-dual-heading-text ua-third-text"><?php echo $this->get_settings_for_display( 'after_heading_text'); ?></span></span><?php } ?>
				<?php if ( ! empty( $settings['_ua_dual_heading_link']['url'] ) ) { ?>
					</a>
				<?php } ?>
			</<?php echo $settings['_ua_dual_tag_selection']; ?>>
		</div>
		<?php
	}
}