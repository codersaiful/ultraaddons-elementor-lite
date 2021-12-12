<?php
namespace UltraAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Repeater;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Product Carousel
 * Product Carousel is a cool interactive product slider. It's work for WooCommerce Product carousel.
 * 
 * Credit: https://github.com/dynamicweb/swiffy-slider
 * 
 * 
 * @since 1.1.0.8
 * @package UltraAddons
 * @author Saiful islam <codersaiful@gmail.com>
 * @author B M Rafiul Alam <bmrafiul.alam@gmail.com>
 */

class Product_Carousel extends Base{
    
    /**
     * mainly to call specific depends
     * we have called this __construct() method
     * 
     * @param Array $data
     * @param Array $args
     * 
     * @by Saiful Islam
     */
    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);

        //Naming of Args for swiffySlider
        $name           = 'swiffySlider';
        $js_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/js/swiffy-slider.min.js';
        $dependency     =  ['jquery'];//['jquery'];
        $version        = ULTRA_ADDONS_VERSION;
        $in_footer  	= true;

        wp_register_script( $name, $js_file_url, $dependency, $version, $in_footer );
        wp_enqueue_script( $name );
		
        //CSS file for dependency
		$name           = 'swiffySlider';
        $css_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/css/swiffy-slider.min.css';
        $dependency     =  [];//kaj ta ses hoyni. pore abar try korte hobe.
        $version        = ULTRA_ADDONS_VERSION;
        $media  	= 'all';
        wp_register_style('swiffySlider', $css_file_url,$dependency,$version, $media ); //product-carousel
        wp_enqueue_style('swiffySlider' );

    }

    /**
     * By B M Rafiul Alam
     * depend css for this widget
     * 
     * @return Array
     */
    public function get_style_depends() {
        return ['swiffySlider'];
    }

    /**
     * Retrieve the list of scripts the skill bar widget depended on.
     *
     * Used to set scripts dependencies required to run the widget.
     *
     * @since 1.0.9.2
     * @access public
     *
     * @return array Widget scripts dependencies.
     * @by Saiful
     */
    public function get_script_depends() {
            return [ 'jquery','swiffySlider' ];
    }
    
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
        return [ 'ultraaddons', 'product', 'slider', 'carousel' ];
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
        //For Query Tab
        $this->query_controls();
        //For General Section
        $this->content_general_controls();
         //For Navigation Tab
        $this->nav_controls();
        //For General Style Tab
        $this->style_general_controls();
		//For Content Style Tab
        $this->style_content_controls();
		//For Box Style Tab
        $this->style_box_controls();
		//For Navigation Style Tab
        $this->style_navi_controls();
        //For sale flash Tab
        $this->pc_sale_flash_controls();
        //For cart Tab
        $this->cart_style_controls();
       
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
				'label' => __( 'Show Products', 'ultraaddons' ),
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

		$this->add_control(
            'cat_ids',
            [
                'label' => esc_html__( 'Select category', 'ultraaddons' ),
                'type' => Controls_Manager::SELECT2,
                'options' => $this->product_tax_options(),
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
        );


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
	
		$this->end_controls_section();
	}
   
    /**
     * Navigation Section 
     */
     protected function nav_controls() {
		
        $this->start_controls_section(
            'nav_settings',
            [
                'label'     => esc_html__( 'Navigation Settings', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
		$this->add_control(
			'_slider_navigation',
			[
				'label' => esc_html__( 'Navigation', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => 'None',
					'slider-nav-chevron' => 'Chevron',
					'slider-nav-arrow' => 'Arrow',
					'slider-nav-caret' => 'Caret',
					'slider-nav-caretfill' => 'Caret filled',
					'slider-nav-round' => 'Round with arrow',
					'slider-nav-square' => 'Square with chevron',
				],
				'default' => 'slider-nav-square',
			]
		);
        $this->add_control(
			'_slider_nav_dark',
			[
				'label' => __( 'Dark', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ultraaddons' ),
				'label_off' => __( 'No', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'_slider_nav_visible',
			[
				'label' => __( 'Always Visible', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ultraaddons' ),
				'label_off' => __( 'No', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'_slider_nav_outside',
			[
				'label' => __( 'Nav Outside', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ultraaddons' ),
				'label_off' => __( 'No', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
		/* $this->add_control(
			'_slider_mouse_drag',
			[
				'label' => __( 'Mouse Drag', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ultraaddons' ),
				'label_off' => __( 'No', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		); */
        $this->add_control(
			'_slider_nav_small',
			[
				'label' => __( 'Small', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ultraaddons' ),
				'label_off' => __( 'No', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'_slider_indicator',
			[
				'label' => __( 'Indicators', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ultraaddons' ),
				'label_off' => __( 'No', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

	$this->end_controls_section();
	}

    
    /**
     * General Section for Content Controls
     * 
     * @since 1.0.0.9
     */
  protected function content_general_controls() {
		
        $this->start_controls_section(
            'general_content',
            [
                'label'     => esc_html__( 'Slider Options', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );

		 $this->add_control(
			'_slider_to_show',
			[
				'label' => esc_html__( 'Slider to Show', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'1' => 'One',
					'2' => 'Two',
					'3' => 'Three',
					'4' => 'Four',
					'5' => 'Five',
					'6' => 'Six',
				],
				'default' => '3',
			]
		);
        $this->add_control(
			'_slider_gap',
			[
				'label' => __( 'Gap', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ultraaddons' ),
				'label_off' => __( 'No', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
         $this->add_control(
			'_slider_auto_play',
			[
				'label' => __( 'Auto Play', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ultraaddons' ),
				'label_off' => __( 'No', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
        $this->add_control(
			'_slider_speed',
			[
				'label' => __( 'Auto Play Interval', 'ultraaddons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1500,
				'max' => 6000,
				'step' => 10,
				'default' => 2500,
			]
		);
        $this->add_control(
			'_slider_pause',
			[
				'label' => __( 'Pause on Hover', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ultraaddons' ),
				'label_off' => __( 'No', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
         $this->add_control(
			'_slider_reveal',
			[
				'label' => __( 'Reveal', 'ultraaddons' ),
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
     *  Section for Style Tab
     * 
     * @since 1.0.0.9
     */
    protected function style_general_controls() {
        $this->start_controls_section(
            'style_general',
            [
                'label'     => esc_html__( 'General', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
			'_ua_front_title_tag',
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
					'div' => 'div',
				],
				'default' => 'h2',
			]
		);
        
		
    $this->end_controls_tab();
    $this->end_controls_section();
       
    }
	//Content Style Tab
	protected function style_content_controls() {
        $this->start_controls_section(
            'style_content',
            [
                'label'     => esc_html__( 'Content', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
       	$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'tilte_typography',
					'label' => 'Title Typography',
					'selector' => '{{WRAPPER}} .ua-product-title',
			]
        );
		$this->add_control(
			'_title_color', [
				'label' => __( 'Title Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-product-title' => 'color: {{VALUE}};',
				],
				'separator'=> 'after'
			]
        );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'cat_typography',
					'label' => 'Category Typography',
					'selector' => '{{WRAPPER}} .ua-card-text',
			]
        );
		$this->add_control(
			'_cat_color', [
				'label' => __( 'Category Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-card-text' => 'color: {{VALUE}};',
				],
				'separator'=> 'after'
			]
        );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'price_typography',
					'label' => 'Price Typography',
					'selector' => '{{WRAPPER}} .ua-pc-price',
			]
        );
		$this->add_control(
			'_price_color', [
				'label' => __( 'Price Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-pc-price' => 'color: {{VALUE}};',
				],
				'separator'=> 'after'
			]
        );
		
    $this->end_controls_tab();
    $this->end_controls_section();
	
    }

	//Box Style Tab
	protected function style_box_controls() {
        $this->start_controls_section(
            'style_box',
            [
                'label'     => esc_html__( 'Box', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
      $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'label' => __( 'Box Shadow', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} .slider-container .ua-card',
			]
		);
		$this->add_responsive_control(
			'_box_radius',
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
					'{{WRAPPER}} .slider-container .ua-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'box_border',
				'label' => __( 'Border', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} .slider-container .ua-card',
			]
		);
		
		
    $this->end_controls_tab();
    $this->end_controls_section();

    }
	//Navigation Style Tab
	protected function style_navi_controls() {
        $this->start_controls_section(
            'style_navi',
            [
                'label'     => esc_html__( 'Navigation', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
     
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'nav_shadow',
				'label' => __( 'Nav Shadow', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} .slider-nav-dark.slider-nav-round .slider-nav::before, .slider-nav-dark.slider-nav-square .slider-nav::before',
			]
		);
		$this->add_control(
			'_nav_bg', [
				'label' => __( 'Nav Background', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .slider-nav-dark.slider-nav-round .slider-nav::before, .slider-nav-dark.slider-nav-square .slider-nav::before' => 'background: {{VALUE}};',
				],
			]
        );
    $this->end_controls_tab();
    $this->end_controls_section();

    }
	/**
	 * Sale Flash Style Controls
	 */
	protected function pc_sale_flash_controls() {
		
        $this->start_controls_section(
            'sale_flash_style',
            [
                'label'     => esc_html__( 'Sale Flash', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		
		$this->add_control(
			'_ua_sale_flash_bg', [
				'label' => __( 'Flash Background', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-onsale' => 'background-color: {{VALUE}};',
				],
				'default'=>'#111'
			]
        );
		$this->add_control(
			'_ua_flash_color', [
				'label' => __( 'Flash Text Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-onsale' => 'color: {{VALUE}};',
				],
				'default'=>'#fff',
			]
        );
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'flash_typography',
					'label' => 'Flash Typography',
					'selector' => '{{WRAPPER}} .ua-onsale',
			]
        );
		$this->add_responsive_control(
			'_ua_flash_margin',
			[
				'label'       => esc_html__( 'Flash Margin', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', '%' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-onsale' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'_ua_flash_padding',
			[
				'label'       => esc_html__( 'Flash Padding', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', '%' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-onsale' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'_ua_flash_radius',
			[
				'label'       => esc_html__( 'Flash Radius', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', '%' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-onsale' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
	}
	/**
	 * Cart Controls style
	 */
	protected function cart_style_controls(){
		$this->start_controls_section(
			'pc_cart_style',
			[
				'label' => __( 'Cart', 'ultraaddons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => 'Cart Typography',
				'name' => 'cart_typography',
				'selector' => '{{WRAPPER}} .ua-thumbnail .cart-links a.add-card span',
				'font_size' => [ 
					'default' => [ 
					'unit' => 'px', 
					'size' => 14 ]
					]
			]
		);
		$this->add_control(
			'aep_blog_readmore_spacing',
			[
				'label' => __( 'Content Spacing', 'ultraaddons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ua-thumbnail .cart-links' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden',
				],
			]
		);
        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'label' => 'Box Shadow',
				'name' => 'carts_box_shadow',
				'selector' => '{{WRAPPER}} .ua-thumbnail .cart-links .add-card',
			]
		);
		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'ultraaddons' ),
			]
		);
		
		$this->add_control(
			'aep_cart_color',
			[
				'label' => __( 'Text Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333',
				'selectors' => [
					'{{WRAPPER}} .ua-thumbnail .cart-links a, .ua-thumbnail .cart-links a i ' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'aep_cart_bg_color', [
				'label' => __( 'Cart Background', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-thumbnail .cart-links a' => 'background: {{VALUE}};',
				],
				'default' => '#fff',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'aep_cart_border',
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .ua-thumbnail .cart-links a',
				'separator' => 'before',
			]
		);
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'ultraaddons' ),
			]
		);
		$this->add_control(
			'hover_color',
			[
				'label' => __( 'Text Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-thumbnail .cart-links a:hover' => 'color: {{VALUE}};',
				],
			]
		);
	
		$this->end_controls_tab();
		
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
    //Intrigate with WooCommerce
    if( ! class_exists( 'WooCommerce' ) ){
        echo "<div class='ua-alert'>" . esc_html__( "WooCommerce is not Activated.", 'ultraaddons' ) . "</div>";
        return;
    }
    $settings   	= $this->get_settings_for_display();
    $gap        	= ($settings['_slider_gap']=='yes') ? '' : ' slider-item-nogap';
    $reveal     	= ($settings['_slider_reveal']=='yes') ? ' slider-item-reveal' : '';
    $to_show    	= $settings['_slider_to_show'] ? ' slider-item-show'. $settings['_slider_to_show'] : '';
    $navigation 	= $settings['_slider_navigation'] ? $settings['_slider_navigation'] : '';
    $dark       	= $settings['_slider_nav_dark'] ? ' slider-nav-dark' : '';
    $small      	= $settings['_slider_nav_small'] ? ' slider-nav-sm' : '';
    $autoPlay   	= $settings['_slider_auto_play'] ? ' slider-nav-autoplay' : '';
    $pause      	= $settings['_slider_pause'] ? ' slider-nav-autopause' : '';
    $indicator 		= ($settings['_slider_indicator']!='no') ? ' slider-indicators' : '';
    $nav_visible  	= ($settings['_slider_nav_visible']=='yes') ? ' slider-nav-visible' : '';
    $nav_outside  	= ($settings['_slider_nav_outside']=='yes') ? ' slider-nav-outside' : '';
   //$mouse_drag  	= ($settings['_slider_mouse_drag']=='yes') ? ' slider-nav-mousedrag' : '';
    
    $this->add_render_attribute(
		'slider_options',
		[
			'class' => 'swiffy-slider'. $to_show . $gap . $reveal . " " . $navigation . $dark . $small . $autoPlay . $indicator . $nav_visible . $nav_outside . $pause ,
            'data-slider-nav-autoplay-interval'=> $settings['_slider_speed']
		]
	);
    ?>

<div <?php echo $this->get_render_attribute_string( 'slider_options' ); ?>>
    <ul class="slider-container">
        <?php
		
        $args = array(
            'post_type' 	=> 'product',
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
        if ( $loop->have_posts() ) {
            while ( $loop->have_posts() ) : $loop->the_post();
				$id 		 = $loop->post->ID;
                $product   	 = wc_get_product( $id );
				$image_id  	 = $product->get_image_id();
				$image_url 	 = wp_get_attachment_image_url( $image_id, 'full' );
				$description = $loop->post->post_excerpt;
                $category 	 = get_the_category( $id );
                if(!empty($category)){
                    echo $category[0]->cat_name;
                }
				?>
        <li>
            <div class="ua-card shadow ua-h-100">
                <div class="ua-thumbnail">
					<?php if ( $product->is_on_sale() ) : 
					echo apply_filters( 'woocommerce_sale_flash', '<span class="ua-onsale">' 
					. esc_html__( 'Sale!', 'ultraaddons' ) . '</span>', $product );
					endif;
					?>
					<div class="cart-links">
						<a href="?add-to-cart=<?php echo esc_attr($id); ?>"  class="add-card button add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo esc_attr($id); ?>"  aria-label="Add '<?php echo get_the_title(); ?>' to your cart" rel="nofollow">
							<i class="fa fa-shopping-cart"></i>
							<span>
								<?php echo esc_html__('ADD TO CART', 'ultraaddons'); ?>
							</span>
						</a>
					</div>
                    <?php echo woocommerce_get_product_thumbnail('woocommerce_full_size');?>
			
                </div>
                <div class="ua-card-body ua-d-flex ua-flex-column ua-flex-md-row">
                    <div class="ua-flex-grow-1">
                        <a href="<?php echo get_the_permalink(); ?>">
                            <?php
                            echo '<' . $settings['_ua_front_title_tag'] . ' class="ua-product-title">' . $loop->post->post_title . 
                                    '</' . $settings['_ua_front_title_tag'] . '>';
                            ?>
                        </a>
                        <p class="ua-card-text">
                            <?php 
                            foreach( wp_get_post_terms( get_the_id(), 'product_cat' ) as $term ){
                            if( $term ){
                                    echo $term->name; // product category name
                                }
                            }
                            ?>
                        </p>
                    </div>
                    <div class="ua-px-md-2 ua-pc-price">
						<?php echo $product->get_price_html();?> 
					</div>
                </div>
        </li>
        <?php
	 endwhile;
	} else {
		 echo "<div class='ua-alert'>" . esc_html__( "No products found!", 'ultraaddons' ) . "</div>";
	}
	wp_reset_postdata();
	?>
    </ul>
	<?php
	if( $navigation !='none'):
	?>
    <button type="button" class="slider-nav" aria-label="Go to previous"></button>
    <button type="button" class="slider-nav slider-nav-next" aria-label="Go to next"></button>
	<?php endif;?>
	
</div>
<?php
        
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

}
