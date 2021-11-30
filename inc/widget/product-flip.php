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
	
	public function word_shortener($text, $words=10, $sp='...'){
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
		//For front style Tab
        $this->front_controls();
		//For back Style Tab
        $this->back_controls();
		//For Box Style Tab
        $this->style_box_controls();
		//For cart btn Tab
        $this->cart_btn_controls();
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
				'label' => __( 'Description Length', 'ultraaddons' ),
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

		$this->add_control(
            'cat_ids',
            [
                'label' => esc_html__( 'Select category', 'ultraaddons' ),
                'type' => Controls_Manager::SELECT2,
                'options' => $this->product_cat_options(),
                'multiple' => 'true'
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
		$this->add_control(
			'_ua_product_flip_animation_type',
			[
				'label' => __( 'Animation Style', 'ultraaddons' ),
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
		/**
	 * Front Style Controls
	 */
	protected function front_controls() {
		
        $this->start_controls_section(
            'front_style',
            [
                'label'     => esc_html__( 'Front', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		 $this->add_control(
			'_ua_product_flip_bg_front', [
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
			'_ua_product_flip_price_color', [
				'label' => __( 'Price Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-product-flip .ua-product-price' => 'color: {{VALUE}};',
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
					'name' => 'price_typography',
					'label' => 'Price Typography',
					'selector' => '{{WRAPPER}} .ua-product-flip .ua-product-price',

			]
        );
		
		$this->end_controls_section();
	}
	/**
	 * Back Style Controls
	 */
	protected function back_controls() {
		
        $this->start_controls_section(
            'back_style',
            [
                'label'     => esc_html__( 'Back', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_control(
			'_ua_product_flip_bg_back', [
				'label' => __( 'Back Background', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-product-flip .back' => 'background: {{VALUE}};',
				]
			]
        );
		
		$this->add_control(
			'_ua_product_flip_title_back', [
				'label' => __( 'Back Title Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-product-flip .back-title' => 'color: {{VALUE}};',
				],
				'default'=>'#ffffff'
			]
        );
		$this->add_control(
			'_ua_product_flip_content_color', [
				'label' => __( 'Description Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-product-flip .back p' => 'color: {{VALUE}};',
				]
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
					'label' => 'Description Typography',
					'selector' => '{{WRAPPER}} .ua-product-flip .back p',

			]
			);
		$this->end_controls_section();
	}

	protected function style_box_controls() {
        $this->start_controls_section(
            '_ua_product_flip_box_style',
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
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'label' => __( 'Box Shadow', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} .ua-product-flip .front, .ua-product-flip .back',
			]
		);
		
		$this->end_controls_section();
	}
	/**
	 * Button Style.
	 */
	protected function cart_btn_controls(){
		$this->start_controls_section(
            'cart_btn_style',
            [
                'label'     => esc_html__( 'Cart Button', 'ultraaddons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->start_controls_tabs(
			'cart_btn_normal_tabs'
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
		
		
		
		$this->end_controls_tab();
		/**
		 * Button Hover tab
		 */
		$this->start_controls_tab(
			'cart_btn_hover_tabs',
			[
				'label' => __( 'Hover', 'ultraaddons' ),
			]
		);
	
	
		$this->end_controls_tabs();
		
		
		$this->end_controls_tab();
		
		
		$this->end_controls_section();
	}
    protected function render() {

		//Intrigate with WooCommerce
        if( ! class_exists( 'WooCommerce' ) ){
            echo "<div class='ua-alert'>" . esc_html__( "WooCommerce is not Activated.", 'ultraaddons' ) . "</div>";
			return;
        }

		$settings 		= $this->get_settings_for_display();
		$col = $settings['_ua_col'];
	?>
	<div class="ua-row">
	<?php
        $args = array(
            'post_type' 	=> 'product',
            'posts_per_page'=> $settings['_ua_post_per_page'],
			'order'			=> $settings['_ua_product_order'],
			'orderby'		=> $settings['_ua_product_orderby'],
            );

		if( ! empty( $settings['cat_ids'] ) ){
			$args['tax_query'] = array(
				array(
					'taxonomy'  => 'product_cat',
					'field'     => 'id', 
					'terms'     => $settings['cat_ids'],
				)
			);
		}	
		
        $loop = new \WP_Query( $args );
        if ( $loop->have_posts() ) {
            while ( $loop->have_posts() ) : $loop->the_post();
				$id 		= $loop->post->ID;
                $product   	= wc_get_product( $id );
				$image_id  	= $product->get_image_id();
				$image_url 	= wp_get_attachment_image_url( $image_id, 'full' );
				$description = $loop->post->post_excerpt;
    ?>
	<div class="ua-product-flip ua-col-<?php echo $col;?> flip-<?php echo $settings['_ua_product_flip_animation_type']; ?>">
		<div class="front" style="background:url(<?php echo esc_url($image_url);?>)">
		   <?php
		   echo '<' . $settings['_ua_front_title_tag'] . ' class="front-title">' . $loop->post->post_title . 
				'</' . $settings['_ua_front_title_tag'] . '>';
		   ?>
		   <span class="ua-product-price">
			<?php echo $product->get_price_html();?> 
		   </span>
		</div>
		<div class="back">
		   <?php
		   echo '<' . $settings['_ua_back_title_tag'] . ' class="back-title">' . $loop->post->post_title . 
				'</' . $settings['_ua_back_title_tag'] . '>';
		   ?>
		   <p><?php echo $this->word_shortener($description, $settings['_ua_text_truncate']);?></p>
		   <div class="ua-cart">
				<a href="?add-to-cart=<?php echo esc_attr($id); ?>" class="ua-cart-btn add-card button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo esc_attr($id); ?>" data-product_sku="" aria-label="Add '<?php echo get_the_title(); ?>' to your cart" rel="nofollow">
					<i class="uicon uicon-cart"></i><span> <?php echo esc_html__( 'Add to Cart', 'ultraaddons');?></span>
				</a>
			</div>
		</div>
	</div>
	<?php
	 endwhile;
	} else {
		 echo "<div class='ua-alert'>" . esc_html__( "No products found!", 'ultraaddons' ) . "</div>";
	}
	wp_reset_postdata();
	?>
	</div>
<?php }

	/**
     * Getting Category list of WooCommerce product
     * 
     *
     * @return void
     */
    public function product_cat_options() {
        $taxonomy = 'product_cat';
        $query_args = array(
            'orderby'       => 'ID',
            'order'         => 'DESC',
            'hide_empty'    => false,
            'taxonomy'      => $taxonomy
        );

        $categories = get_categories( $query_args );
        $options = array();
        if(is_array($categories) && count($categories) > 0){
            foreach ($categories as $cat){
                $options[$cat->term_id] = $cat->name;
            }
            return $options;
        }
    }
}