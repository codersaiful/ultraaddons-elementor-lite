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
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Product_Tabs extends Base{
    

	public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);
		
        //Naming of Args 
        $name           = 'isotop';
        $js_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/js/isotope.pkgd.min.js';
        $dependency     =  ['jquery'];//['jquery'];
        $version        = ULTRA_ADDONS_VERSION;
        $in_footer  	= true;

        wp_register_script( $name, $js_file_url, $dependency, $version, $in_footer );
        wp_enqueue_script( $name );

		//Naming of Args 
        $name           = 'product';
        $js_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/js/product.js';
        $dependency     =  ['jquery'];//['jquery'];
        $version        = ULTRA_ADDONS_VERSION;
        $in_footer  	= true;

        wp_register_script( $name, $js_file_url, $dependency, $version, $in_footer );
        wp_enqueue_script( $name );

    }
	/**
     * By B M Rafiul Alam
     * depend css for this widget
     * 
     * @return Array
     */
    public function get_style_depends() {
        return ['isotop', 'hoverIntent'];
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
        return [ 'ultraaddons', 'ua', 'product', 'woo', 'woocommerce' ];
    }
    
    
    /**
     * Register widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls() {
		//For Query Tab
        $this->query_controls();
		//For front style Tab
        $this->general_controls();
		//For col Tab
		$this->card_col_settings();
		//For Box Style Tab
        $this->style_box_controls();
		//For cart btn Tab
        $this->cart_btn_controls();
		//For sale flash Tab
        $this->sale_flash_controls();
		//For Pagination Tab
        $this->pagination_controls();
		//For Filter button Tab
        $this->filter_btn_controls();
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
				'default' => 'rand',
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
	 * Content settings Tab
	*/
	protected function card_col_settings() {
		$this->start_controls_section(
		'_ua_card_content_settings_tab',
            [
                'label'     => esc_html__( 'Grid Settings', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
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

		
	$this->end_controls_section();
	}


	/**
	 * Front Style Controls
	 */
	protected function general_controls() {
		
        $this->start_controls_section(
            'general_style',
            [
                'label'     => esc_html__( 'General', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
	
		$this->add_control(
			'_ua_title_color', [
				'label' => __( 'Title Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .pgf .title-product a' => 'color: {{VALUE}};',
				],
				'default'=>'#111'
			]
        );
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'product_title_typography',
					'label' => 'Title Typography',
					'selector' => '{{WRAPPER}} .pgf .title-product a',
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
					'{{WRAPPER}} .pgf .title-product a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'_ua_product_price_color', [
				'label' => __( 'Price Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .pgf .price' => 'color: {{VALUE}};',
				],
                'separator' =>'before'
			]
        );
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'price_typography',
					'label' => 'Price Typography',
					'selector' => '{{WRAPPER}} .pgf .price',

			]
        );
	
		$this->add_control(
			'_ua_content_color', [
				'label' => __( 'Description Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .pgf .description-prod p' => 'color: {{VALUE}};',
				],
				'separator' => 'before'
			]
        );
	
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'content_typography',
					'label' => 'Description Typography',
					'selector' => '{{WRAPPER}} .pgf .description-prod p',
			]
		);
		$this->add_responsive_control(
			'_ua_back_desc_margin',
			[
				'label'       => esc_html__( 'Description Margin', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', '%' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .pgf .description-prod p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'_ua_box_padding',
			[
				'label'       => esc_html__( 'Description Padding', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .pgf .description-prod' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	
		$this->add_control(
			'_ua_cat_color', [
				'label' => __( 'Category Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'default'	=> '#a1a1a1',
				'selectors' => [
						'{{WRAPPER}} .pgf .category > span' => 'color: {{VALUE}};',
				],
				'separator' => 'before'
			]
        );
	
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'cat_typography',
					'label' => 'Category Typography',
					'selector' => '{{WRAPPER}} .pgf .category > span',
			]
		);
		$this->add_responsive_control(
			'_ua_cat_padding',
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
					'{{WRAPPER}} .pgf .ua-category span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'_ua_cat_radius',
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
					'{{WRAPPER}} .pgf .ua-category span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'cat_shadow',
				'label' => __( 'Category Shadow', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} .pgf .ua-category span',
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
                'separator' => 'before'
			]
		);
		$this->add_responsive_control(
			'_ua_card_text_alignment',
			[
				'label' => esc_html__( 'Text Alignment', 'ultraaddons' ),
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
					'{{WRAPPER}} .ua-cp-text ' => 'text-align: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_section();
	}

	protected function style_box_controls() {
        $this->start_controls_section(
            '_ua_product_box_style',
            [
                'label'     => esc_html__( 'Box', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_control(
			'_ua_box_bg', [
				'label' => __( 'Box Background', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
				'selectors' => [
						'{{WRAPPER}} .pgf .ua-cp-product' => 'background: {{VALUE}};',
				]
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
					'{{WRAPPER}} .pgf .ua-cp-product' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'label' => __( 'Box Shadow', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} .pgf .ua-cp-product',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'box_border',
				'label' => esc_html__( 'Border', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} .pgf .ua-cp-product',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'footer_line_border',
				'label' => esc_html__( 'Footer Border', 'ultraaddons' ),
				'show_label'=>true,
				'selector' => '{{WRAPPER}} .pgf .ua-card-footer',
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
	
		$this->add_control(
			'_ua_cart_btn_color', [
				'label' => __( 'Button Text Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .pgf .add-card' => 'color: {{VALUE}};',
				]
			]
        );
	
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'cart_btn_typography',
					'label' => 'Button Typography',
					'selector' => '{{WRAPPER}}  .pgf .add-card',
			]
        );
	
		$this->add_responsive_control(
			'_cart_btn_padding',
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
					'{{WRAPPER}} .pgf .ua-card-footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
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
		$this->add_control(
			'_ua_cart_btn_hover_color', [
				'label' => __( 'Button Text Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}}  .pgf .add-card:hover' => 'color: {{VALUE}};',
				]
			]
        );
	
		$this->end_controls_tabs();
		
		$this->end_controls_tab();
		
		$this->end_controls_section();
	}
	/**
	 * Sale Flash Style Controls
	 */
	protected function sale_flash_controls() {
		
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
						'{{WRAPPER}} .ua-badge .ua-onsale' => 'background-color: {{VALUE}};',
				],
				'default'=>'#111'
			]
        );
		$this->add_control(
			'_ua_flash_color', [
				'label' => __( 'Flash Text Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-badge .ua-onsale' => 'color: {{VALUE}};',
				],
				'default'=>'#fff',
			]
        );
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'flash_typography',
					'label' => 'Flash Typography',
					'selector' => '{{WRAPPER}} .ua-badge .ua-onsale',
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
					'{{WRAPPER}} .ua-badge .ua-onsale' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .ua-badge .ua-onsale' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .ua-badge .ua-onsale' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
	}
    /**
	 * Pagination Style Controls
	 */
	protected function pagination_controls() {
		
        $this->start_controls_section(
            'pagination_style',
            [
                'label'     => esc_html__( 'Pagination', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->start_controls_tabs(
			'pagi_normal_tabs'
		);
		/**
		 * Normal tab
		 */
		$this->start_controls_tab(
			'pagi_normal_tab',
			[
				'label' => __( 'Normal', 'ultraaddons' ),
			]
		);
		$this->add_control(
			'_ua_pagi_bg', [
				'label' => __( 'Background', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-pagination .page-numbers' => 'background-color: {{VALUE}};',
				],
				'default'=>'#fff'
			]
        );

		$this->add_control(
			'_ua_pagi_color', [
				'label' => __( 'Text Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-pagination .page-numbers' => 'color: {{VALUE}};',
				],
				'default'=>'#111',
			]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => esc_html__( 'Border', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} .ua-pagination .page-numbers',
			]
		);
        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'pagi_shadow',
				'label' => __( 'Box Shadow', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} .ua-pagination .page-numbers',
			]
		);
		$this->add_responsive_control(
			'_ua_pagi_margin',
			[
				'label'       => esc_html__( 'Margin', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', '%' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'_ua_pagi_number_margin',
			[
				'label'       => esc_html__( 'Number Margin', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', '%' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-pagination .page-numbers' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'_ua_pagi_padding',
			[
				'label'       => esc_html__( 'Padding', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', '%' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-pagination .page-numbers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'_ua_pagi_radius',
			[
				'label'       => esc_html__( 'Radius', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', '%' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-pagination .page-numbers' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->end_controls_tab();
		/**
		 * Button Hover tab
		 */
		$this->start_controls_tab(
			'pagi_btn_hover_tabs',
			[
				'label' => __( 'Hover', 'ultraaddons' ),
			]
		);

        $this->add_control(
			'_ua_pagi_hover_bg', [
				'label' => __( 'Background Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-pagination a.page-numbers:hover' => 'background: {{VALUE}};',
				]
			]
        );
	
		$this->add_control(
			'_ua_pagi_hover_color', [
				'label' => __( 'Text Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-pagination a.page-numbers:hover' => 'color: {{VALUE}};',
				]
			]
        );
		$this->end_controls_tab();

        $this->start_controls_tab(
			'style_active_tab',
			[
				'label' => esc_html__( 'Active', 'plugin-name' ),
			]
		);
        $this->add_control(
			'_ua_pagi_active_bg', [
				'label' => __( 'Background Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}}  .ua-pagination .page-numbers.current' => 'background: {{VALUE}};',
				]
			]
        );
	
		$this->add_control(
			'_ua_pagi_active_color', [
				'label' => __( 'Text Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}}  .ua-pagination .page-numbers.current' => 'color: {{VALUE}};',
				]
			]
        );

		$this->end_controls_tab();

        $this->end_controls_tabs();
		
		$this->end_controls_section();
	}
	/**
	 * Filter Button Style.
	 */
	protected function filter_btn_controls(){
		$this->start_controls_section(
            'filter_btn_style',
            [
                'label'     => esc_html__( 'Filter Button', 'ultraaddons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->start_controls_tabs(
			'filter_btn_normal_tabs'
		);
		/**
		 * Normal tab
		 */
		$this->start_controls_tab(
			'filter_normal_tab',
			[
				'label' => __( 'Normal', 'ultraaddons' ),
			]
		);
		$this->add_control(
			'_ua_filter_btn_bg', [
				'label' => __( 'Button Background Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .pf-filter-btn li a' => 'background-color: {{VALUE}};',
				]
			]
        );
		$this->add_control(
			'_ua_filter_btn_color', [
				'label' => __( 'Button Text Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .pf-filter-btn li a' => 'color: {{VALUE}};',
				]
			]
        );
	
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'filter_btn_typography',
					'label' => 'Button Typography',
					'selector' => '{{WRAPPER}} .pf-filter-btn li a',
			]
        );
	
		$this->add_responsive_control(
			'_filter_btn_padding',
			[
				'label'       => esc_html__( 'Button Padding', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px','em' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .pf-filter-btn li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'_filter_btn_margin',
			[
				'label'       => esc_html__( 'Button Margin', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px','em' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .pf-filter-btn li a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'_filter_btn_radius',
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
					'{{WRAPPER}} .pf-filter-btn li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_tab();
		/**
		 * Button Hover tab
		 */
		$this->start_controls_tab(
			'filter_btn_hover_tabs',
			[
				'label' => __( 'Hover', 'ultraaddons' ),
			]
		);
		$this->add_control(
			'_ua_filter_btn_hover_bg', [
				'label' => __( 'Background Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .pf-filter-btn li a:hover' => 'background-color: {{VALUE}};',
				]
			]
        );
		$this->add_control(
			'_ua_filter_btn_hover_color', [
				'label' => __( 'Button Text Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}}  .pf-filter-btn li a:hover' => 'color: {{VALUE}};',
				]
			]
        );
		$this->end_controls_tab();
		/**
		 * Button Active tab
		 */
		$this->start_controls_tab(
			'filter_btn_active_tabs',
			[
				'label' => __( 'Active', 'ultraaddons' ),
			]
		);
		$this->add_control(
			'_ua_filter_btn_active_bg', [
				'label' => __( 'Background Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .pf-filter-btn li.active a' => 'background-color: {{VALUE}};',
				]
			]
        );
		$this->add_control(
			'_ua_filter_btn_active_color', [
				'label' => __( 'Button Text Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}}  .pf-filter-btn li.active a' => 'color: {{VALUE}};',
				]
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
			$settings 	= $this->get_settings_for_display();
			$col 		= $settings['_ua_col'];
			$flex_col='';
			$flex_row='';
			if ( 'yes'==$settings['_ua_card_direction'] ) {
				$flex_col='flex-col';
				$flex_row='flex-row';
			}
			$colOrder='';
			if ( 'right'== $settings['_ua_card_order'] ) {
				$colOrder='card-col-order';
			}
			$justifyContent ='';
			if($settings['_ua_card_justify_content']){
				$justifyContent= 'flex-justify-content-' . $settings['_ua_card_justify_content'];
			}
	
			$this->add_render_attribute(
				'thumb_class',
				[
					'class' => 'ua-product-tumb ' . $flex_col . ' ' . $colOrder . ' ' . $justifyContent  ,
				]
			);
	
			$this->add_render_attribute(
				'ua_product_details',
				[
					'class' => 'ua-product-details ' . $flex_col . ' ' . $justifyContent ,
				]
			);
		?>
		<div class="ua-row pf">
			<div class="ua-col-1">
					<?php
					if(empty($settings['cat_ids'])):?>
						<ul class="pf-filter-btn">
							<li class="list active" data-filter="*"><a href="#">All</a></li>
							<?php
							$args = array(
								'orderby'       => 'ID',
								'order'         => 'DESC',
								'hide_empty'    => true,
								'taxonomy'      => 'product_cat'
							);
							//Filter List by category 
							$categories = get_categories( $args );
							if(is_array($categories) && count($categories) > 0):
								foreach ($categories as $cat):
								$id = $cat->term_id;
								?>
								<li class="list" data-cat="<?php echo  $cat->slug;?>" data-filter=".<?php echo  $cat->slug;?>">
									<a href="<?php echo esc_url( get_term_link( $id, 'product_cat' ) );?>"> <?php echo  $cat->name;?> (<?php echo $cat->count; ?>)</a>
								</li>  
							<?php
								endforeach;
							endif;
							?>
						</ul>
					<?php 
					else:
					?>
					<ul class="pf-filter-btn">
						<li class="list active" data-filter="*"><a href="#">All</a></li>
						<?php
						//If Selected manual from frontend
						$selected_cat = $settings['cat_ids'];
						foreach($selected_cat as $selected_cats):?>
							<li class="list"  data-filter=".<?php echo strtolower(get_the_category_by_ID( $selected_cats ));?>">
							<a href="#">
								<?php echo get_the_category_by_ID( $selected_cats );?>
							</a>
							</li> 
						<?php
						endforeach;
						?>
					</ul>
					<?php
					endif;
					?>
			</div>
		</div>

		<div class="pgf ua-row projects">
		<?php
			$paged = (get_query_var('paged')) ? get_query_var('paged') : $settings['_ua_post_page_number'] ;
			$all_categories = get_categories( $args );
			$cat_ids = array();

			foreach ($all_categories as $cat){
				array_push($cat_ids, $cat->term_id);
			}
			//print_r($cat_ids);
			$args = array(
				'post_type' 	=> 'product',
				'posts_per_page'=> $settings['_ua_post_per_page'],
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
	
			$args['tax_query'] = array(
				array(
					'taxonomy'  => 'product_cat',
					'field'     => 'id', 
					'terms'     => (!empty($settings['cat_ids'])) ?  $settings['cat_ids'] : $cat_ids,
				)
			);
			
			
			$loop = new \WP_Query( $args );
			if ( $loop->have_posts() ) {
				while ( $loop->have_posts() ) : $loop->the_post();
					$id 		= $loop->post->ID;
					$product   	= wc_get_product( $id );
					$image_id  	= $product->get_image_id();
					$image_url 	= wp_get_attachment_image_url( $image_id, 'full' );
					$description = $loop->post->post_excerpt;
					//Get Category list for fliter
				$data = array();
				foreach( wp_get_post_terms( get_the_id(), 'product_cat' ) as $term ){
					if( $term ){
						$data[] = $term->slug; // product category name
					}
				}
		?>
		<div class="ua-col-<?php echo $col;?> items <?php echo implode(" ", $data);?>">
		<div class="ua-cp-product">
          <div class="ua-cp-img">
		  <?php if ( $product->is_on_sale() ) : ?>
				<div class="ua-badge">
					<?php
					echo apply_filters( 'woocommerce_sale_flash', '<span class="ua-onsale">' 
					. esc_html__( 'Sale!', 'ultraaddons' ) . '</span>', $product );
					?>
				</div>
				<?php endif; ?>
				<?php echo woocommerce_get_product_thumbnail('woocommerce_full_size');?>
			</div>
          <div class="ua-cp-text">
            <div class="ua-category">
              <span>
				  <?php
					foreach( wp_get_post_terms( get_the_id(), 'product_cat' ) as $term ){
						if( $term ){
						 $cat= $term->name; // product category name
						}
					}
					echo $cat;
					?>
				</span>
            </div>
            <div class="title-product">
			<a href="<?php echo get_the_permalink(); ?>">
			<?php
			echo '<' . $settings['_ua_front_title_tag'] . ' class="ua-product-title">' 
				. $loop->post->post_title . 
				'</' . $settings['_ua_front_title_tag'] . '>';
			?>
			</a>
            </div>
            <div class="description-prod">
              <p><?php echo $this->word_shortener($description, $settings['_ua_text_truncate']);?></p>
            </div>
            <div class="ua-card-footer">
              <div class="ua-left"><span class="price"><?php echo $product->get_price_html();?> </span></div>
              <div class="ua-right">	
					<a href="?add-to-cart=<?php echo esc_attr($id); ?>"  class="buy-btn add-card button add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo esc_attr($id); ?>"  aria-label="Add '<?php echo get_the_title(); ?>' to your cart" rel="nofollow">
						<i class="uicon uicon-cart"></i>
						<?php
						if ( 'yes'!=$settings['_ua_card_direction'] ):
						?>
						<?php endif; ?>
					</a>
				</div>
            </div>
          </div>
        </div>
        </div>

		
		<?php
		endwhile;
		?>
		
		 <?php
		} else {
			 echo "<div class='ua-alert'>" . esc_html__( "No products found!", 'ultraaddons' ) . "</div>";
		}
		?>

		<?php
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
		 * Shorter Description
		 */
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
		
}