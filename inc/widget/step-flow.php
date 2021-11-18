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
 * Step Flow Widget
 * Create excellent step by step visual diagram and instructions using this smart widget.
 * 
 * @since 1.1.0.7
 * @package UltraAddons
 * @author Saiful islam <codersaiful@gmail.com>
 * @author Rafiul <bmrafiul.alam@gmail.com>
 */
class Step_Flow extends Base{

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
        return [ 'ultraaddons', 'ua', 'step flow', 'step', 'flow' ];
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
        $this->content_controls();
        //For Design Section Style Tab
        $this->step_flow_style_controls();
		//For Typography Style Tab
        $this->icon_style_controls();
		//For Box Style Tab
        //$this->style_box_controls();
    }
	protected function content_controls() {
		
        $this->start_controls_section(
            '_ua_step_flow_content_tab',
            [
                'label'     => esc_html__( 'Content', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
		$this->add_control(
			'_ua_step_flow_badge',
			[
				'label' => __( 'Badge', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( '1', 'ultraaddons' ),
				'label_block' => false,
			]
		);
		$this->add_control(
			'_ua_step_flow_icon',
			[
				'label' => __( 'Icon', 'ultraaddons' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'solid',
				],
			]
		);
		$this->add_control(
			'_ua_step_flow_title',
			[
				'label' => __( 'Title', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Step Flow Title', 'ultraaddons' ),
				'placeholder' => __( 'Enter your title', 'ultraaddons' ),
				'label_block' => true,
			]
		);
		
		$this->add_control(
			'_ua_step_flow_content',
			[
				'label' => __( 'Content', 'ultraaddons' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'The quickest & easiest service provider. Loem Ipsum doler sit amit.', 'ultraaddons' ),
				'placeholder' => __( 'Enter your title', 'ultraaddons' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'_ua_step_flow_direction',
			[
				'label' => __( 'Direction Arrow', 'ultraaddons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'ultraaddons' ),
				'label_off' => __( 'Hide', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_responsive_control(
			'_ua_step_flow_text_alignment',
			[
				'label' => esc_html__( 'Alignment', 'ultraaddons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'ultraaddons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'ultraaddons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'ultraaddons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .ua-container' => 'text-align: {{VALUE}};',
				],
			]
		);
		
	$this->end_controls_section();
	}
	protected function icon_style_controls() {
      $this->start_controls_section(
            '_ua_step_style',
            [
                'label'     => esc_html__( 'Icon Box', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_control(
			'_ua_step_flow_icon_color', [
				'label' => __( 'Icon Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-steps-icon i' => 'color: {{VALUE}};',
				],
			]
        );
      $this->add_responsive_control(
			'_ua_icon_box_radius',
			[
				'label'       => esc_html__( 'Box Radius', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', '%' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-steps-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => '_ua_icon_box_shadow',
				'label' => __( 'Box Shadow', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} .ua-steps-icon',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => '_ua_step_flow_icon',
				'label' => __( 'Background', 'ultraaddons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ua-steps-icon',
			]
		);
		
		
		$this->end_controls_section();
	}
	protected function step_flow_style_controls() {
        $this->start_controls_section(
            'step_flow_content_style',
            [
                'label'     => esc_html__( 'Content', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_responsive_control(
			'_ua_step_flow_title_margin',
			[
				'label'       => esc_html__( 'Title Margin', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px'],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-steps-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'_ua_step_flow_title_color', [
				'label' => __( 'Title Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-steps-title' => 'color: {{VALUE}};',
				],
			]
        );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => '_ua_step_flow_title_typo',
					'label' => 'Title Typography',
					'selector' => '{{WRAPPER}} .ua-steps-title',
					'separator'=>'after'
			],
        );
		$this->add_control(
			'_ua_step_flow_content_color', [
				'label' => __( 'Description Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-step-description' => 'color: {{VALUE}};',
				],
				'separator'=>'before'
			]
        );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => '_ua_step_flow_content_typo',
					'label' => 'Description Typography',
					'selector' => '{{WRAPPER}} .ua-step-description',
			],
			
        );
		  
		$this->end_controls_section();
	}

    protected function render() {
		$settings 	= $this->get_settings_for_display();
		$direction 	= $settings['_ua_step_flow_direction'];
	?>
	<div class="ua-container">
		<div class="ua-steps-icon">
			<?php if('yes'=== $direction):  ?>
				<span class="ua-step-arrow"></span>
			<?php endif;?>
			<?php \Elementor\Icons_Manager::render_icon( $settings['_ua_step_flow_icon'], [ 'aria-hidden' => 'true' ] ); ?>
			<span class="ua-steps-label"><?php echo $settings['_ua_step_flow_badge']; ?></span>
		</div>
		<h2 class="ua-steps-title"><?php echo $settings['_ua_step_flow_title']; ?></h2>
		<p class="ua-step-description">
			<?php echo $settings['_ua_step_flow_content']; ?>
		</p>
	</div>
<?php }
}