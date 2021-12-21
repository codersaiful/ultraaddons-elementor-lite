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
		//For Content Settings Tab
		$this->card_content_settings();   
		 //For Design Section Style Tab
		$this->card_content_style(); 
		//For Box Style Tab
		$this->card_box_style();
		//For button Style Tab
		$this->card_button_style();   
		
    }
	/**
	 * Widget controls Method
	 * 
	 * It's actually content control part
	 */
	protected function card_content_controls() {
		$placeholder_image = ULTRA_ADDONS_URL . 'assets/images/card.png';
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
				 'default' => [
						'url' => $placeholder_image,//Utils::get_placeholder_image_src(),
				], 
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
				'default' => __( 'Jhone Doe', 'ultraaddons' ),
				'placeholder' => __( 'Enter your title', 'ultraaddons' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'_ua_card_sub_title',
			[
				'label' => __( 'Card Sub Title', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'WordPress Developer', 'ultraaddons' ),
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
				'default' => __( 'View Profile', 'ultraaddons' ),
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
		$this->end_controls_section();
	}
	/**
	 * Content settings Tab
	 */
	protected function card_content_settings() {
		$this->start_controls_section(
		'_ua_card_content_settings_tab',
            [
                'label'     => esc_html__( 'Content Settings', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
		$this->add_control(
			'_ua_card_direction',
			[
				'label' => __( 'Direction', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Row', 'ultraaddons' ),
				'label_off' => __( 'Col', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
		
		$this->add_responsive_control(
			'_ua_card_order',
			[
				'label' => esc_html__( 'Column Order', 'ultraaddons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'ultraaddons' ),
						'icon' => 'eicon-arrow-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'ultraaddons' ),
						'icon' => 'eicon-arrow-right',
					],
				
				],
				'default' => 'left',
				'condition' => array(
					'_ua_card_direction' => 'yes',
				),
			]
		);
		$this->add_responsive_control(
			'_ua_card_justify_content',
			[
				'label' => esc_html__( 'Justify Content', 'ultraaddons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Top', 'ultraaddons' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Middle', 'ultraaddons' ),
						'icon' => 'eicon-v-align-middle',
					],
					'end' => [
						'title' => esc_html__( 'Bottom', 'ultraaddons' ),
						'icon' => 'eicon-v-align-bottom',
					],
				
				],
				'default' => 'left',
				'condition' => array(
					'_ua_card_direction' => 'yes',
				),
			]
		);
		$this->add_responsive_control(
			'_ua_card_text_alignment',
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
					'justify' => [
						'title' => esc_html__( 'justify', 'ultraaddons' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .ua-card-body ' => 'text-align: {{VALUE}};',
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
				'default' => 'div',
			]
		);

	$this->end_controls_section();
	}
  /**
   * Content Style Method
   */
    protected function card_content_style(){
       $this->start_controls_section(
            '_ua_card_style',
            [
                'label'     => esc_html__( 'Card', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_responsive_control(
			'_ua_image_radius',
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
				'selectors'   => [
					'{{WRAPPER}} .ua-card-avatar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'_ua_card_image_height',
			[
				'label' => __( 'Image Height', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 500,
						'step' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 200,
				],
				'selectors' => [
					'{{WRAPPER}} .ua-card-avatar' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'_ua_card_image_margin',
			[
				'label'       => esc_html__( 'Image Margin', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ '%', 'px' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'separator' =>'after',
				'selectors'   => [
					'{{WRAPPER}} .ua-card-avatar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'_ua_card_image_padding',
			[
				'label'       => esc_html__( 'Image Padding', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ '%', 'px' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-card-avatar' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			]
        );
		$this->add_responsive_control(
			'_ua_card_title_margin',
			[
				'label'       => esc_html__( 'Title Margin', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ '%', 'px' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'separator' =>'after',
				'selectors'   => [
					'{{WRAPPER}} .ua-card-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
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
			]
        );
		$this->add_responsive_control(
			'_ua_card_sub_title_margin',
			[
				'label'       => esc_html__( 'Sub Title Margin', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ '%', 'px' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'separator' =>'after',
				'selectors'   => [
					'{{WRAPPER}} .ua-card-sub-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
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
		$this->add_responsive_control(
			'_ua_card_content_padding',
			[
				'label'       => esc_html__( 'Content Padding', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ '%', 'px' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-card-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
        $this->end_controls_section();
    }
	/**
	 * Button style Method
	 */
	 protected function card_button_style(){
       $this->start_controls_section(
            '_ua_card_button_style',
            [
                'label'     => esc_html__( 'Button', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->start_controls_tabs(
			'style_tabs'
		);
		/**
		 * Normal tab
		 */
		$this->start_controls_tab(
			'style_normal_tab',
			[
				'label' => __( 'Normal', 'ultraaddons' ),
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
				'label' => __( 'Button Text Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-card-button' => 'color: {{VALUE}};',
				],
				'separator'=>'before'
			]
        );
		$this->add_responsive_control(
			'_ua_card_btn_radius',
			[
				'label'       => esc_html__( 'Button Radius', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', '%' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-card-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);
		$this->add_responsive_control(
			'_ua_card_btn_padding',
			[
				'label'       => esc_html__( 'Button Padding', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', '%' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-card-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);
		$this->end_controls_tab();
		/**
		 * Button Hover tab
		 */
		$this->start_controls_tab(
			'style_hover_tab',
			[
				'label' => __( 'Hover', 'ultraaddons' ),
			]
		);
		$this->add_control(
			'_ua_btn_text_hover_color', [
				'label' => __( 'Button Text Hover Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-card-button:hover' => 'color: {{VALUE}};',
				],
				'separator'=>'before'
			]
        );
		$this->add_control(
			'_ua_btn_bg_hover_color', [
				'label' => __( 'Button Background', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-card-button:hover' => 'background: {{VALUE}};',
				],
				'separator'=>'before'
			]
        );
		$this->end_controls_tabs();
		
	 $this->end_controls_section();
    }
	/**
	 * Card Box style Method
	 */
	 protected function card_box_style(){
       $this->start_controls_section(
            '_ua_card_box_style',
            [
                'label'     => esc_html__( 'Box', 'ultraaddons' ),
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
		$this->add_responsive_control(
			'_ua_box_radius',
			[
				'label'       => esc_html__( 'Box Radius', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ '%', 'px' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'separator'=>'before',
				'selectors'   => [
					'{{WRAPPER}} .ua-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'card_box_shadow',
				'label' => __( 'Box Shadow', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} .ua-card',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => '_ua_box_border',
				'label' => __( 'Border', 'plugin-domain' ),
				'selector' => '{{WRAPPER}} .ua-card',
			]
		);
		
	 $this->end_controls_section();
    }
	
	/**
     * Retrive setting for card control
     * 
     * @author B M Rafiul Alam <bmrafiul.alam@gmail.com>
     *
     * @return void
     */
	
   protected function render() {
		$settings 	= $this->get_settings_for_display();
		$target 	= $settings['_ua_card_button_link']['is_external'] ? ' target="_blank"' : '';
		$nofollow 	= $settings['_ua_card_button_link']['nofollow'] ? ' rel="nofollow"' : '';
		$url		= $settings['_ua_card_button_link']['url'];
		$col='';
		$row='';
		if ( 'yes'==$settings['_ua_card_direction'] ) {
			$col='card-col';
			$row='card-row';
		}
		$colOrder='';
		if ( 'right'== $settings['_ua_card_order'] ) {
			$colOrder='card-col-order';
		}
		$justifyContent ='';
		if($settings['_ua_card_justify_content']){
			$justifyContent= 'card-justify-content-' . $settings['_ua_card_justify_content'];
		}

		$this->add_render_attribute(
			'card_avatar_class',
			[
				'class' => 'ua-card-avatar-content ' . $col . ' ' . $colOrder ,
			]
		);

		$this->add_render_attribute(
			'card_body_class',
			[
				'class' => 'ua-card-body ' . $col . ' ' . $justifyContent ,
			]
		);
		
	?>
	<div class="ua-c ua-card-content">
		<div class="ua-card <?php echo $row; ?>">
			<div <?php echo $this->get_render_attribute_string( 'card_avatar_class' );?>>
			<?php 
			if(!empty($url)){
				echo '<a href="' . $url. '"' . $target . $nofollow . ' class="ua-card-avatar-link">
				<img  class="ua-card-avatar" src="' . $settings['_ua_card_image']['url'] .'"/>
				</a>';
			}
			?>
			</div>
			<div <?php echo $this->get_render_attribute_string( 'card_body_class' );?>>
				<?php
				echo '<' . $settings['_ua_card_title_tag'] . ' class="ua-card-title">' . esc_html($settings['_ua_card_title']) . 
					'</' . $settings['_ua_card_title_tag'] . '>';
				?>
				<?php
				echo '<' . $settings['_ua_card_sub_title_tag'] . ' class="ua-card-sub-title">' . esc_html($settings['_ua_card_sub_title']) . 
						'</' . $settings['_ua_card_sub_title_tag'] . '>';
				?>
				<p class="ua-card-text"><?php echo $settings['_ua_card_content']; ?></p>
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
	</div>
<?php }
}