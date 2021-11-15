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


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Flip_box_3d extends Base{

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
        return [ 'flipbox', '3d', 'flip', 'box' ];
    }
	
	 /**
     * Register oEmbed widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {
        //For Content Section
        $this->content_general_controls();
        //For Design Section Style Tab
        $this->style_design_controls();
    }
	protected function content_general_controls() {
		$placeholder_image = ULTRA_ADDONS_URL . 'assets/images/user.png';
        $this->start_controls_section(
            'general_content',
            [
                'label'     => esc_html__( 'Content', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
		 $this->add_control(
			'front_image',
			[
				'label' => __( 'Front Image', 'ultraaddons' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
						'url' => $placeholder_image,//Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
						'active' => true,
				],

			]
        );
		 $this->add_control(
			'back_image',
			[
				'label' => __( 'Back Image', 'ultraaddons' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
						'url' => $placeholder_image,//Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
						'active' => true,
				],

			]
        );
			$this->add_control(
			'_ua_flipbox_front_title',
			[
				'label' => __( 'Front Title', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Flip Front', 'ultraaddons' ),
				'placeholder' => __( 'Enter your title', 'ultraaddons' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'_ua_flipbox_back_title',
			[
				'label' => __( 'Back Title', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Flip Back', 'ultraaddons' ),
				'placeholder' => __( 'Enter your title', 'ultraaddons' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'_ua_flipbox_content',
			[
				'label' => __( 'Content', 'ultraaddons' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'The quickest & easiest service provider. Loem Ipsum doler sit amit.', 'ultraaddons' ),
				'placeholder' => __( 'Enter your title', 'ultraaddons' ),
				'label_block' => true,
			]
		);
		
	$this->end_controls_section();
	}
	protected function style_design_controls() {
        $this->start_controls_section(
            'flipbox_style',
            [
                'label'     => esc_html__( 'Background', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
			'flipbox_bg_default', [
				'label' => __( 'Default Background', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .default-state' => 'background: {{VALUE}};',
				],
			]
        );  
		$this->add_control(
			'flipbox_bg_back', [
				'label' => __( 'Back Background', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .back' => 'background: {{VALUE}};',
				],
			]
        );
		
		$this->add_control(
			'flipbox_title_back', [
				'label' => __( 'Title Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .back-title' => 'color: {{VALUE}};',
				],
			]
        );
		$this->add_control(
			'flipbox_content_back', [
				'label' => __( 'Content Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .back p' => 'color: {{VALUE}};',
				],
			]
        );
		$this->end_controls_section();
	}

    protected function render() {
		$settings 		= $this->get_settings_for_display();
		$front_image 	= $settings['front_image'];
		$back_image 	= $settings['back_image'];
	?>
		<div class="flip-container">
		  <div class="flipper">
			<div class="front" style="background-image:url(<?php echo esc_url($front_image['url']);?>)">
			  <span class="name"><?php echo esc_html($settings['_ua_flipbox_front_title']); ?></span>
			</div>
			<div class="back">
			  <div class="back-logo" style="background-image:url(<?php echo esc_url($back_image['url']);?>)"></div>
			  <div class="back-title"><?php echo esc_html($settings['_ua_flipbox_back_title']); ?></div>
			  <p><?php echo esc_html($settings['_ua_flipbox_content']); ?></p>
			</div>
		  </div>
		</div>
<?php }
}