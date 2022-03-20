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

class Product_Category_Grid extends Base{
    
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
        return [ 'ultraaddons', 'ua', 'product', 'category', 'grid', 'woo','woocommerce' ];
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
        //For General Section
        $this->query_controls();
        $this->content_style();
        $this->category_style();
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
			'_cat_show_number',
			[
				'label' => __( 'Show Category', 'ultraaddons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 300,
				'step' => 1,
				'default' => 4,
			]
		);
		
		$this->add_control(
			'_ua_cat_order',
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
			'_ua_cat_orderby',
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
			'_ua_cat_count',
			[
				'label' => __( 'Show Count', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'ultraaddons' ),
				'label_off' => __( 'Hide', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		
		$this->end_controls_section();
	}

    protected function content_style() {
        $this->start_controls_section(
            'style_',
            [
                'label'     => esc_html__( 'General Style', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
			'_cat_image_radius',
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
					'{{WRAPPER}} .ua-cat-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'label' => __( 'Box Shadow', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} .ua-cat-box',
			]
		);

        $this->add_control(
			'col_gap',
			[
				'label' => esc_html__( 'Column Gap', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 30,
						'step' => 1,
					],
				
				],
				'selectors' => [
					'{{WRAPPER}} .ua-col' => 'padding-right: {{SIZE}}{{UNIT}}; padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->end_controls_section();
    }
    protected function category_style() {
        $this->start_controls_section(
            'cat_style',
            [
                'label'     => esc_html__( 'Category Style', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
			'top',
			[
				'label' => esc_html__( 'Top Positions', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .ua-cat-box .cat-name' => 'top: {{SIZE}}{{UNIT}};',
				],
                'condition' => [
                    '_ua_cat_bottom!' => 'yes',
                ]
			]
		);
        $this->add_control(
			'left',
			[
				'label' => esc_html__( 'Left Positions', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .ua-cat-box .cat-name' => 'left: {{SIZE}}{{UNIT}};',
				],
                'condition' => [
                    '_ua_cat_bottom!' => 'yes',
                ],
                'separator' => 'after'
			]
		);

        $this->add_responsive_control(
			'_ua_badge',
			[
				'label'       => esc_html__( 'Category Radius', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-cat-box .cat-name' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->add_responsive_control(
			'_ua_badge_padding',
			[
				'label'       => esc_html__( 'Category Padding', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-cat-box .cat-name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->add_control(
			'_ua_cat_color', [
				'label' => __( 'Category Text', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'default'	=> '#a1a1a1',
				'selectors' => [
						'{{WRAPPER}} .ua-cat-box .cat-name' => 'color: {{VALUE}};',
				],
				'separator' => 'before'
			]
        );
        $this->add_control(
			'_ua_cat_hover_color', [
				'label' => __( 'Category Text Hover', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'default'	=> '#a1a1a1',
				'selectors' => [
						'{{WRAPPER}} .ua-cat-box .cat-name:hover' => 'color: {{VALUE}};',
				],
			]
        );
	
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'cat_typography',
					'label' => 'Category Typography',
					'selector' => '{{WRAPPER}} .ua-cat-box .cat-name',
			]
		);
        $this->add_control(
			'_category_bg', [
				'label' => __( 'Background', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-cat-box .cat-name' => 'background-color: {{VALUE}};',
				],
			]
        );
        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'cat_shadow',
				'label' => __( 'Category Shadow', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} .ua-cat-box .cat-name',
			]
		);
        $this->add_control(
			'_ua_cat_bottom',
			[
				'label' => __( 'Show on Bottom', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ultraaddons' ),
				'label_off' => __( 'No', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

        $this->end_controls_section();
    }
    
 /**
     * Render oEmbed widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
    $settings  = $this->get_settings_for_display();
    $on_bottom = $settings['_ua_cat_bottom'];
    $reset = ($on_bottom == 'yes') ? 'reset' : '';
    ?>
    <div class="ua-row">
    <?php 
     //Intrigate with WooCommerce
     if( ! class_exists( 'WooCommerce' ) ){
        echo "<div class='ua-alert'>" . esc_html__( "WooCommerce is not Activated.", 'ultraaddons' ) . "</div>";
        return;
    }

    $args = array(
        'orderby'       => $settings['_ua_cat_orderby'],
        'order'         => $settings['_ua_cat_order'],
        'hide_empty'    => true,
        'taxonomy'      => 'product_cat'
    );
    //Filter List by category 
    $categories = get_categories( $args );
    if(is_array($categories) && count($categories) > 0):
        $limit  = $settings['_cat_show_number'];
        $counter=0;
        foreach ($categories as $cat):
            $thumb_id    = get_term_meta ( $cat->term_id, 'thumbnail_id', true );
            $img_src    = wp_get_attachment_url( $thumb_id );
			$img_url = $img_src ? $img_src: wc_placeholder_img_src();
                if($counter < $limit):
         ?>
        <div class="ua-col ua-col-4 mb-20">
            <div class="ua-cat-box">
                <a href="<?php echo  get_term_link( $cat->slug, 'product_cat' );?>">
                <div class="cat-image">
                    <img src="<?php echo $img_url;?>">
                </div>

                <div class="cat-name <?php echo $reset;?>">
                     <?php echo $cat->name;?> 
                     <?php if($settings['_ua_cat_count']=='yes'):?>
                     (<?php echo $cat->count;?>)
                     <?php endif;?>
                </div>
                </a>
            </div><!-- ua-cat-box -->
        </div><!--col-->
        <?php
        $counter++;
            endif;
            endforeach;
        endif;
        ?>
    </div>
        <?php
        
    }
}