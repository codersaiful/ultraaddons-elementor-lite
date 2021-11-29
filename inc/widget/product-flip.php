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
 * Flip Box Widget
 * FlipBox is a cool user interface for web. Interactive way with before and after effects.
 * 
 * Credit: https://codepen.io/Aoyue/pen/pLJqgE
 * 
 * 
 * @since 1.1.0.7
 * @package UltraAddons
 * @author Saiful islam <codersaiful@gmail.com>
 * @author Rafiul <bmrafiul.alam@gmail.com>
 */
class Product_Flip extends Base{

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
        return [ 'ultraaddons', 'ua', 'flipbox', 'product', 'flip', 'box' ];
    }
	
	function word_shortener($text, $words=10, $sp='...'){
		  $all = explode(' ', $text);
		  $str = '';
		  $count = 1;

		  foreach($all as $key){
			$str .= $key . ($count >= $words ? '' : ' ');
			$count++;
			if($count > $words){
			  break;
			}
		  }
		  return $str . (count($all) <= $words ? '' : $sp);
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
		//For Query Tab
        $this->query_controls();
       //For Content Section
        $this->content_general_controls();
        //For Design Section Style Tab
        $this->style_design_controls();
		//For Typography Style Tab
        $this->style_typography_controls();
		//For Box Style Tab
        $this->style_box_controls();
	
    }
	protected function query_controls() {
		
        $this->start_controls_section(
            'query_content',
            [
                'label'     => esc_html__( 'Query Settings', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
		$this->add_control(
			'_ua_text_truncate',
			[
				'label' => __( 'Description Truncate', 'ultraaddons' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 5,
				'max' => 300,
				'step' => 5,
				'default' => 10,
			]
		);
		$this->add_control(
			'_ua_post_per_page',
			[
				'label' => __( 'Show Products', 'ultraaddons' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 5,
				'max' => 300,
				'step' => 1,
				'default' => 8,
			]
		);
		$this->add_control(
			'_ua_product_order',
			[
				'label' => esc_html__( 'Order', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'asc' => 'Asc',
					'desc' => 'Desc',
				],
				'default' => 'desc',
			]
		);
		$this->add_control(
			'_ua_product_orderby',
			[
				'label' => esc_html__( 'Orderby', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => 'None',
					'id' => 'ID',
					'name' => 'Name',
					'type' => 'Type',
					'rand' => 'Random',
					'date' => 'Date',
					'modified' => 'Modified',
				],
				'default' => 'date',
			]
		);
		 
		
	$this->end_controls_section();
	}
	protected function content_general_controls() {
		
        $this->start_controls_section(
            'general_content',
            [
                'label'     => esc_html__( 'Settings', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
		$this->add_control(
			'_ua_flipbox_animation_type',
			[
				'label' => __( 'Flip Style', 'ultraaddons' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => __( 'Horizontal', 'ultraaddons' ),
					'vertical'  => __( 'Vertical', 'ultraaddons' ),
				],
			]
		);
		$this->add_control(
			'_ua_col',
			[
				'label' => esc_html__( 'Column', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'1' => '1 Column',
					'2' => '2 Column',
					'3' => '3 Column',
					'4' => '4 Column',
				],
				'default' => '3',
			]
		);
		 
		$this->add_control(
			'_ua_front_title_tag',
			[
				'label' => esc_html__( 'Select Front Title Tag', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
				],
				'default' => 'h2',
			]
		);
		
		$this->add_control(
			'_ua_back_title_tag',
			[
				'label' => esc_html__( 'Select Back Title Tag', 'ultraaddons' ),
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
	$this->end_controls_section();
	}
	protected function style_design_controls() {
        $this->start_controls_section(
            '_ua_flipbox_style',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
      $this->add_control(
			'_ua_flipbox_bg_front', [
				'label' => __( 'Front Background', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-product-flip .front:after' => 'background-color: {{VALUE}};',
				],
			]
        );
		
		$this->add_control(
			'_ua_front_title_color', [
				'label' => __( 'Front Title Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-product-flip .front-title' => 'color: {{VALUE}};',
				],
				'default'=>'#ffffff'
			]
        );
	
		$this->add_control(
			'_ua_flipbox_bg_back', [
				'label' => __( 'Back Background', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-product-flip .back' => 'background: {{VALUE}};',
				],
				'separator'=>'before'
			]
        );
		
		$this->add_control(
			'_ua_flipbox_title_back', [
				'label' => __( 'Back Title Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-product-flip .back-title' => 'color: {{VALUE}};',
				],
				'default'=>'#ffffff'
			]
        );
		$this->add_control(
			'_ua_flipbox_content_color', [
				'label' => __( 'Content Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-product-flip .back p' => 'color: {{VALUE}};',
				],
				'separator'=>'before'
			]
        );
		
		$this->end_controls_section();
	}
	protected function style_typography_controls() {
        $this->start_controls_section(
            'flipbox_typo_style',
            [
                'label'     => esc_html__( 'Typography', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		
		$this->add_responsive_control(
			'_ua_text_alignment',
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
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .ua-product-flip .front' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .ua-product-flip .back' => 'text-align: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'front_title_typography',
					'label' => 'Front Title Typography',
					'selector' => '{{WRAPPER}} .ua-product-flip .front-title',

			]
        );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'back_title_typography',
					'label' => 'Back Title Typography',
					'selector' => '{{WRAPPER}} .ua-product-flip .back-title',

			]
        );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'content_typography',
					'label' => 'Content Typography',
					'selector' => '{{WRAPPER}} .ua-product-flip .back p',

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
		
		$this->add_responsive_control(
			'_ua_box_radius',
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
					'{{WRAPPER}} .ua-product-flip .front, .ua-product-flip .back, .ua-product-flip .front:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'label' => __( 'Box Shadow', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} .ua-product-flip .front, .ua-product-flip .back',
			]
		);
		
		$this->end_controls_section();
	}

    protected function render() {
		$settings 		= $this->get_settings_for_display();
		$col = $settings['_ua_col'];
	?>
	<div class="ua-row">
	<?php
        $args = array(
            'post_type' 		=> 'product',
            'posts_per_page' 	=> $settings['_ua_post_per_page'],
			'order'				=> $settings['_ua_product_order'],
			'orderby'			=> $settings['_ua_product_orderby'],
            );
        $loop = new \WP_Query( $args );
        if ( $loop->have_posts() ) {
            while ( $loop->have_posts() ) : $loop->the_post();
                $product   = wc_get_product( $loop->post->ID );
				$image_id  = $product->get_image_id();
				$image_url = wp_get_attachment_image_url( $image_id, 'full' );
				$description = $loop->post->post_excerpt;
    ?>
	<div class="ua-product-flip ua-col-<?php echo $col;?> flip-<?php echo $settings['_ua_flipbox_animation_type']; ?>">
		<div class="front" style="background:url(<?php echo esc_url($image_url);?>)">
		   <?php
		   echo '<' . $settings['_ua_front_title_tag'] . ' class="front-title">' . $loop->post->post_title . 
				'</' . $settings['_ua_front_title_tag'] . '>';
		   ?>
		   <span><?php echo $product->get_price_html();?> </span>
		</div>
		<div class="back">
		   <?php
		   echo '<' . $settings['_ua_back_title_tag'] . ' class="back-title">' . $loop->post->post_title . 
				'</' . $settings['_ua_back_title_tag'] . '>';
		   ?>
		   <p><?php echo $this->word_shortener($description, $settings['_ua_text_truncate']);?></p>
		</div>
	</div>
	<?php
	 endwhile;
	} else {
		echo __( 'No products found' );
	}
	wp_reset_postdata();
	?>
	</div>
<?php }
}