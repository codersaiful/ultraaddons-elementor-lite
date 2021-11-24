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
 * Card Widget
 * Create Incredibly powerful widget to demonstrate your products, articles, news, creative posts using a beautiful combination of texts, links, badge and image. 

 * @since 1.1.0.8
 * @package UltraAddons
 * @author Saiful islam <codersaiful@gmail.com>
 * @author Rafiul <bmrafiul.alam@gmail.com>
 */
class Card extends Base{
	
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
        return [ 'ultraaddons', 'ua', 'Card', 'profile', 'info box' ];
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
        $this->card_content_controls(); 
		 //For Design Section Style Tab
		$this->card_content_style(); 
		//For Box Style Tab
		$this->card_box_style();
		//For button Style Tab
		$this->card_button_style();   
    }
	/**
	 * Here should comment actually
	 * 
	 * It's actually content control part
	 */
	protected function card_content_controls() {
		
        $this->start_controls_section(
		
            '_ua_card_content_tab',
            [
                'label'     => esc_html__( 'Content', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
		
			 $this->add_control(
			'_ua_card_image',
			[
				'label' => __( 'Card Image', 'ultraaddons' ),
				'type' => Controls_Manager::MEDIA,
				/* 'default' => [
						'url' => $placeholder_image,//Utils::get_placeholder_image_src(),
				], */
				'dynamic' => [
						'active' => true,
				],

			]
        );
		$this->add_control(
			'_ua_card_title',
			[
				'label' => __( 'Card Title', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Card Title', 'ultraaddons' ),
				'placeholder' => __( 'Enter your title', 'ultraaddons' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'_ua_card_sub_title',
			[
				'label' => __( 'Card Sub Title', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Card Sub Title', 'ultraaddons' ),
				'placeholder' => __( 'Enter your title', 'ultraaddons' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'_ua_card_content',
			[
				'label' => __( 'Card Content', 'ultraaddons' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'Minim dolor in amet nulla laboris enim dolore consequat proident fugiat culpa eiusmod.', 'ultraaddons' ),
				'placeholder' => __( 'Enter Content', 'ultraaddons' ),
				'label_block' => true,
				'separator' =>'after'
			]
		);
		$this->add_control(
			'_ua_card_button',
			[
				'label' => __( 'Button Text', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'View Profile.', 'ultraaddons' ),
				'label_block' => true,
			]
		);
			$this->add_control(
			'_ua_card_button_link',
			[
				'label' => __( 'Link', 'ultraaddons' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'ultraaddons' ),
				'show_external' => true,
				'separator' =>'after',
				'default' => [
					'url' => '#',
					'is_external' => true,
					'nofollow' => true,
				],
			]
		);
		$this->add_control(
			'_ua_card_title_tag',
			[
				'label' => esc_html__( 'Select Title Tag', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div'=>	'div',
				],
				'default' => 'h2',
			]
		);
		$this->add_control(
			'_ua_card_sub_title_tag',
			[
				'label' => esc_html__( 'Select Sub Title Tag', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div'=>	'div',
				],
				'default' => 'h5',
			]
		);
	$this->end_controls_section();
	}
    /**
     * Retrive setting for card control
     * 
     * @author Saiful <codersaiful@gmail.com>
     *
     * @return void
     */
    protected function card_content_style(){
       $this->start_controls_section(
            '_ua_card_style',
            [
                'label'     => esc_html__( 'Card Style', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_responsive_control(
			'_ua_box_radius',
			[
				'label'       => esc_html__( 'Image Radius', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ '%', 'px' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'separator'=>'after',
				'selectors'   => [
					'{{WRAPPER}} .ua-card-avatar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'card_title_typography',
					'label' => 'Title Typography',
					'selector' => '{{WRAPPER}} .ua-card-title',

			]
        );
		$this->add_control(
			'_ua_card_title_color', [
				'label' => __( 'Title Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-card-title' => 'color: {{VALUE}};',
				],
				'separator'=>'after'
			]
        );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'card_sub_title_typography',
					'label' => 'Sub Title Typography',
					'selector' => '{{WRAPPER}} .ua-card-sub-title',

			]
        );
		$this->add_control(
			'_ua_card_sub_title_color', [
				'label' => __( 'Sub Title Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-card-sub-title' => 'color: {{VALUE}};',
				],
				'separator'=>'after'
			]
        );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'card_content_typography',
					'label' => 'Content Typography',
					'selector' => '{{WRAPPER}} .ua-card-text',

			]
        );
		$this->add_control(
			'_ua_card_content_color', [
				'label' => __( 'Content Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-card-text' => 'color: {{VALUE}};',
				],
				'separator'=>'after'
			]
        );

        $this->end_controls_section();
    }
	
	 protected function card_button_style(){
       $this->start_controls_section(
            '_ua_card_button_style',
            [
                'label'     => esc_html__( 'Button Style', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'card_btn_typography',
					'label' => 'Button Typography',
					'selector' => '{{WRAPPER}} .ua-card-button',
					'separator'=>'after'
			]
        );
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_background',
				'label' => __( 'Button Background', 'ultraaddons' ),
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} .ua-card-button',
			]
		);
		$this->add_control(
			'_ua_btn_text_color', [
				'label' => __( 'Button Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-card-button' => 'color: {{VALUE}};',
				],
				'separator'=>'after'
			]
        );
		
	 $this->end_controls_section();
    }
	 protected function card_box_style(){
       $this->start_controls_section(
            '_ua_card_box_style',
            [
                'label'     => esc_html__( 'Box Style', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'box_background',
				'label' => __( 'Box Background', 'ultraaddons' ),
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} .ua-card',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'card_box_shadow',
				'label' => __( 'Box Shadow', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} .ua-card',
			]
		);
		
	 $this->end_controls_section();
    }
	
	
	
   protected function render() {
		$settings 	= $this->get_settings_for_display();
		$target 	= $settings['_ua_card_button_link']['is_external'] ? ' target="_blank"' : '';;
		$nofollow 	= $settings['_ua_card_button_link']['nofollow'] ? ' rel="nofollow"' : '';
		$url		= $settings['_ua_card_button_link']['url'];
		
	?>
	<div class="ua-card-content">
	  <div class="ua-card">
		<div class="ua-card-header">
		  <div class="ua-card-avatar-content">
			<?php 
			if(!empty($url)){
				echo '<a href="' . $url. '"' . $target . $nofollow . ' class="ua-card-avatar-link">
				<img  class="ua-card-avatar" src="' . $settings['_ua_card_image']['url'] .'"/>
				</a>';
			}
			?>
		  </div>
			<?php
			echo '<' . $settings['_ua_card_title_tag'] . ' class="ua-card-title">' . esc_html($settings['_ua_card_title']) . 
				'</' . $settings['_ua_card_title_tag'] . '>';
			?>
			<?php
			   echo '<' . $settings['_ua_card_sub_title_tag'] . ' class="ua-card-sub-title">' . esc_html($settings['_ua_card_title']) . 
					'</' . $settings['_ua_card_sub_title_tag'] . '>';
			?>
		</div>
		<div class="ua-card-body">
		  <p class="ua-card-text"><?php echo $settings['_ua_card_content']; ?></p>
		</div>
		<div class="ua-card-footer">
		<?php 
			if(!empty($url)){
				echo '<a href="' . $url. '"' . $target . $nofollow . ' class="ua-card-button">
				 ' .  $settings['_ua_card_button'] . '
				</a>';
			}
		?>
		</div>
	  </div>
	</div>
<?php }
}