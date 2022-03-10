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
/**
 * Post Timeline
 * Post Timeline is a cool interactive post timeline.
 * 
 * 
 * @since 1.1.0.8
 * @package UltraAddons
 * @author Saiful islam <codersaiful@gmail.com>
 * @author B M Rafiul Alam <bmrafiul.alam@gmail.com>
 */

class Post_Timeline extends Base{
    
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
        return [ 'ultraaddons','ua', 'post', 'timeline', 'time','line' ];
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
        //Query Controls
        $this->query_controls();
        //Style Controls
        $this->style_controls();
        //Box Style
        $this->box_style();
        //Line Style
        $this->line_style();
    }
   
    /**
     * Query Controls
    */
    protected function query_controls() {
		
        $this->start_controls_section(
            'query_content',
            [
                'label'     => esc_html__( 'Query Settings', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
			'_ua_post_type',
			[
				'label' => esc_html__( 'Post Type', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_post_type(),
				'default' => 'post',
			]
		);
        $this->add_control(
			'_ua_title_truncate',
			[
				'label' => __( 'Title Length', 'ultraaddons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 300,
				'step' => 1,
				'default' => 5,
			]
		);
		$this->add_control(
			'_ua_text_truncate',
			[
				'label' => __( 'Description Length', 'ultraaddons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 5,
				'max' => 300,
				'step' => 5,
				'default' => 10,
			]
		);
		$this->add_control(
			'_ua_post_per_page',
			[
				'label' => __( 'Show Item', 'ultraaddons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 300,
				'step' => 1,
				'default' => 6,
			]
		);
		$this->add_control(
			'_ua_post_page_number',
			[
				'label' => __( 'Page Number', 'ultraaddons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				//'max' => 300,
				'step' => 1,
				'default' => 1,
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

		 /* $this->add_control(
            'cat_ids',
            [
                'label' => esc_html__( 'Select category', 'ultraaddons' ),
                'type' => Controls_Manager::SELECT2,
                'options' => $this->get_taxanomy(),
                'multiple' => 'true'
            ]
        );

		
		$this->add_control(
            'tag_ids',
            [
                'label' => esc_html__( 'Select Tag', 'ultraaddons' ),
                'type' => Controls_Manager::SELECT2,
                'options' => $this->product_tax_options( 'product_tag' ),
                'multiple' => 'true'
            ]
        );  */


		$this->add_control(
			'_ua_query_post_in',
			[
				'label' => __( 'Product by included IDs', 'ultraaddons' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => __( '1,2,3,4,20,33', 'ultraaddons' ),
				'description' => __('Add multiple ids by comma separated.'),
				'label_block' => true,
			]
		);
		$this->add_control(
			'_ua_query_post_not_in',
			[
				'label' => __( 'Product by excluded IDs', 'ultraaddons' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => __( '1,2,3,4,20,33', 'ultraaddons' ),
				'description' => __('Add multiple ids by comma separated.'),
				'label_block' => true,
			]
		);
        $this->add_control(
			'date_format',
			[
				'label' => __('Date Format', 'ultraaddons'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'd M Y'     => date("d M Y"),
					'm.d.y'     => date("m.d.y"),
					'j, n, Y'   => date("j, n, Y"),
					'Ymd'       => date("Ymd"),
					'D M j, Y'  => date("D M j, Y"),
					'F j, Y'    => date("F j, Y"),
					'j M, Y'    => date("j M, Y"),
					'Y-m-d'     => date("Y-m-d"),
					'Y/m/d'     => date("Y/m/d"),
				],
				'default' => 'd M Y',
			]
		);
	
		$this->end_controls_section();
	}
    

    /**
     * General Style
     */
    protected function style_controls() {
        $this->start_controls_section(
            'style_tab',
            [
                'label'     => esc_html__( 'Style', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
			'_ua_text_alignment',
			[
				'label' => esc_html__( 'Alignment', 'ultraaddons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Left', 'ultraaddons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'ultraaddons' ),
						'icon' => 'eicon-text-align-center',
					],
					'end' => [
						'title' => esc_html__( 'Right', 'ultraaddons' ),
						'icon' => 'eicon-text-align-right',
					]
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .ua-post-timeline' => 'text-align: {{VALUE}};',
				],
                'separator'=> 'after'
			]
		);
       	$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'tilte_typography',
					'label' => 'Title Typography',
					'selector' => '{{WRAPPER}} .ua-post-title',
			]
        );
		$this->add_control(
			'_title_color', [
				'label' => __( 'Title Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-post-title' => 'color: {{VALUE}};',
				],
			]
        );
        $this->add_responsive_control(
			'_ua_title_margin',
			[
				'label'       => esc_html__( 'Title Margin', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', '%' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-post-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'separator'=> 'after'
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'date_typography',
					'label' => 'Date Typography',
					'selector' => '{{WRAPPER}} .ua-pt-txt time',
			]
        );
		$this->add_control(
			'_date_color', [
				'label' => __( 'Date Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-pt-txt time' => 'color: {{VALUE}};',
				],
			]
        );
        $this->add_responsive_control(
			'_ua_time_margin',
			[
				'label'       => esc_html__( 'Date Margin', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', '%' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-pt-txt time' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'separator'=> 'after'
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'content_typography',
					'label' => 'Content Typography',
					'selector' => '{{WRAPPER}} .ua-pt-txt p',
			]
        );
		$this->add_control(
			'_content_color', [
				'label' => __( 'Content Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-pt-txt p' => 'color: {{VALUE}};',
				],
			]
        );
        $this->add_responsive_control(
			'_ua_content_margin',
			[
				'label'       => esc_html__( 'Content Margin', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', '%' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'separator'=> 'after',
				'selectors'   => [
					'{{WRAPPER}} .ua-pt-txt p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'_ua_image_radius',
			[
				'label'       => esc_html__( 'Image Radius', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', '%' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-pt-thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'separator'=> 'after'
			]
		);
		
    $this->end_controls_tab();
    $this->end_controls_section();
    }
    /**
     * Box Style
     */
    protected function box_style() {
        $this->start_controls_section(
            'box_style_tab',
            [
                'label'     => esc_html__( 'Box', 'ultraaddons' ),
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
					'{{WRAPPER}} .ua-post-timeline ul li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
         $this->add_responsive_control(
			'_ua_box_padding',
			[
				'label'       => esc_html__( 'Box Padding', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', '%' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-post-timeline ul li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'label' => __( 'Box Shadow', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} .ua-post-timeline ul li a',
			]
		);

    $this->end_controls_tab();
    $this->end_controls_section();

    }
    /**
     * Line  Style
    */
    protected function line_style() {
        $this->start_controls_section(
            'line_style_tab',
            [
                'label'     => esc_html__( 'Line', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'h_line_border',
				'label' => __( 'Horizontal Line', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} .ua-post-timeline ul li .ua-pt-line, .ua-post-timeline ul li:nth-child(odd)::after',
			]
		);
      
        $this->add_control(
			'_line_btn_bg', [
				'label' => __( 'Node Background', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-post-timeline ul li .ua-pt-thumbnail::after, .ua-post-timeline ul::before' => 'background-color: {{VALUE}};',
				],
			]
        );

    $this->end_controls_tab();
    $this->end_controls_section();

    }
    
    protected function excerpt($limit) {
        $content = wp_strip_all_tags(get_the_content() , true );
        echo wp_trim_words($content, $limit);
    }

     /**
     * Getting Category list of WooCommerce product
     * 
     *
     * @return void
     */
   
    public function product_tax_options( $taxonomy = 'product_cat' ) {
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
    /**
     * Get Post Type
    */
    protected function get_post_type(){
        
        $post_types = get_post_types([], 'objects');
       // $post_types = get_post_types( array( 'public' => true, '_builtin' => true ), 'names', 'and' );
        unset( $post_types['page'] );
       
        $posts = array();
        foreach ($post_types as $post_type) {
            $posts[$post_type->name] = $post_type->labels->singular_name;
        }
        return $posts;
    }
    /**
	 * SHorter Description
	 */
	public function title_shortener($text, $words=10, $sp='...'){
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
     * Render oEmbed widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
    
        $settings       = $this->get_settings_for_display();
        $lenght         = $settings['_ua_text_truncate'];
        $date_format    = $settings['date_format'];
        $post_type      = $settings['_ua_post_type'];
        //$GLOBALS['post_type'] = $post_type;

        ?>
    <div class="ua-post-timeline">
        <ul>
        <?php
        $args = array(
            'post_type' 	=> $settings['_ua_post_type'],
            'posts_per_page'=> $settings['_ua_post_per_page'],
			'paged'=> ! empty( $settings['_ua_post_page_number'] ) ? $settings['_ua_post_page_number'] : 1,
			'order'			=> $settings['_ua_product_order'],
			'orderby'		=> $settings['_ua_product_orderby'],
            );
		if(! empty( $settings['_ua_query_post_in'] )){
			$include_ids = explode(',',$settings['_ua_query_post_in']);
			$args['post__in'] = $include_ids;
		}
		if(! empty( $settings['_ua_query_post_not_in'] )){
			$exclude_ids = explode(',',$settings['_ua_query_post_not_in']);
			$args['post__not_in'] = $exclude_ids;
		}

	 	if( ! empty( $settings['cat_ids'] ) ){
			$args['tax_query'] = array(
				array(
					'taxonomy'  => 'product_cat',
					'field'     => 'id', 
					'terms'     => $settings['cat_ids'],
				)
			);
		}
		
		if( ! empty( $settings['tag_ids'] ) ){
			$args['tax_query'] = array(
				array(
					'taxonomy'  => 'product_tag',
					'field'     => 'id', 
					'terms'     => $settings['tag_ids'],
				)
			);
		} 
         $loop = new \WP_Query( $args ); 
            while ( $loop->have_posts() ) : $loop->the_post();
            if ( has_post_thumbnail() ):
            ?>
            <li>
                <a href="<?php echo get_the_permalink(); ?>">
                    <div class="ua-pt-thumbnail">
                        <?php echo get_the_post_thumbnail( $loop->ID, 'large' ); ?>
                    </div>
                    <div class="ua-pt-txt">
                        <time><?php echo get_the_date($date_format); ?></time>
                        <h3 class="ua-post-title">
                            <?php $title = get_the_title();?>
                             <?php echo $this->title_shortener($title, $settings['_ua_title_truncate']);?>
                        </h3>
                        <p>
                            <?php echo $this->excerpt($lenght);?>
                            
                        </p>
                    </div>
                </a>
                <div class="ua-pt-line"></div>
            </li>
            <?php
            endif;
            endwhile;
             wp_reset_postdata();
            ?>
        </ul>
    </div>
    <?php
        
    }
//end class
}


