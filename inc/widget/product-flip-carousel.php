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
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Product_Flip_Carousel extends Base{

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
        $js_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/swiffy-slider/dist/js/swiffy-slider.min.js';
        $dependency     =  ['jquery'];//['jquery'];
        $version        = ULTRA_ADDONS_VERSION;
        $in_footer  	= true;

        wp_register_script( $name, $js_file_url, $dependency, $version, $in_footer );
        wp_enqueue_script( $name );
		
        //CSS file for dependency
		$name           = 'swiffySlider';
        $css_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/swiffy-slider/dist/css/swiffy-slider.min.css';
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
        return [ 'ultraaddons', 'flip', 'product', 'carousel' ];
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
       //For Carousel Controls
        $this->carousel_settings();
       //For Nav Controls
        $this->nav_controls();
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
		//For sale flash Tab
        $this->sale_flash_controls();
		//For Navigation Style
        $this->style_navigation();
    }
	 
    /**
     * Carousel Settings Controls
     * 
     * @since 1.1.0.8
     */
	protected function carousel_settings() {
		
        $this->start_controls_section(
            'carousel_controls',
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
			'indicator_part',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( '<h2 class="ua-inner-text">Indicators Settings</h2>', 'ultraaddons' ),
			]
		);
		$this->add_control(
			'_slider_indicator_shape',
			[
				'label' => esc_html__( 'Indicator Shape ', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => 'Default',
					'slider-indicators-round' => 'Round',
					'slider-indicators-square' => 'Square'
				],
				'default' => 'default',
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
		$this->add_control(
			'_slider_indicator_outside',
			[
				'label' => __( 'Indicators Outside', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ultraaddons' ),
				'label_off' => __( 'No', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
		$this->add_control(
			'_slider_indicator_highlight',
			[
				'label' => __( 'Indicators Highlight', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ultraaddons' ),
				'label_off' => __( 'No', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
		$this->add_control(
			'_slider_indicator_dark',
			[
				'label' => __( 'Indicators Dark', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ultraaddons' ),
				'label_off' => __( 'No', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
		$this->add_control(
			'_slider_indicator_visible_sm',
			[
				'label' => __( 'Visible on Small Devices', 'ultraaddons' ),
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
	 * Query Settings
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

	protected function content_general_controls() {
		
        $this->start_controls_section(
            'general_content',
            [
                'label'     => esc_html__( 'Settings', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
		
		$this->add_control(
			'_ua_product_flip_animation_type',
			[
				'label' => __( 'Animation Style', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => __( 'Horizontal', 'ultraaddons' ),
					'vertical'  => __( 'Vertical', 'ultraaddons' ),
				],
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
				'label' => __( 'Front Overlay', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-product-flip .front:before' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .ua-product-flip .front' => 'align-items: {{VALUE}};',
					'{{WRAPPER}} .ua-product-flip .back' => 'align-items: {{VALUE}}; text-align: {{VALUE}};',
					
				],
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
						'{{WRAPPER}} .ua-product-flip .back .ua-desc' => 'color: {{VALUE}};',
				],
				'separator' => 'after'
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
					'selector' => '{{WRAPPER}} .ua-product-flip .back .ua-desc',

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
					'{{WRAPPER}} .ua-product-flip .back .ua-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'important_note',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __( 'This option is only for live design purposes. Please turn it off after the backside design is finished.', 'ultraaddons' ),
				'content_classes' => 'ua-alert',
				'separator' => 'before',
				'condition' => ['_ua_back_view'=>'yes']
			]
		);
		$this->add_control(
			'_ua_back_view',
			[
				'label' => __( 'View Back', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'ultraaddons' ),
				'label_off' => __( 'Hide', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
		
		$this->end_controls_section();
	}
	/**
	 * Box Style
	 */

	protected function style_box_controls() {
        $this->start_controls_section(
            '_ua_product_flip_box_style',
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
					'top'    => '0',
					'right'  => '0',
					'bottom' => '0',
					'left'   => '0',
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
		$this->add_control(
			'_ua_cart_btn_bg', [
				'label' => __( 'Button Background', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-cart a.button' => 'background: {{VALUE}};',
				]
			]
        );
		$this->add_control(
			'_ua_cart_btn_color', [
				'label' => __( 'Button Text Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-cart a.button, i.uicon.uicon-cart' => 'color: {{VALUE}};',
				]
			]
        );
	
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'cart_btn_typography',
					'label' => 'Button Typography',
					'selector' => '{{WRAPPER}} .ua-cart a.button',
			]
        );
		$this->add_responsive_control(
			'_cart_btn_radius',
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
					'{{WRAPPER}} .ua-cart a.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
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
					'{{WRAPPER}} .ua-cart a.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			'_ua_cart_btn_hover_bg', [
				'label' => __( 'Button Background', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-cart a.button:hover' => 'background: {{VALUE}};',
				]
			]
        );
		$this->add_control(
			'_ua_cart_btn_hover_color', [
				'label' => __( 'Button Text Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-cart a.button:hover, .ua-cart a.button:hover' => 'color: {{VALUE}};',
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
    //Navigation Style Tab
	protected function style_navigation() {
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
						'{{WRAPPER}} .slider-nav-dark.slider-nav-round .slider-nav::before, .slider-nav-dark.slider-nav-square .slider-nav::before' => 'background: {{VALUE}} !important;',
				],
			]
        );
		$this->add_control(
			'_indicator_bg', [
				'label' => __( 'Indicator Background', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .slider-indicators>*' => 'background-color: {{VALUE}}; filter:none',
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
        $settings 	= $this->get_settings_for_display();
       //Intrigate with WooCommerce
        if( ! class_exists( 'WooCommerce' ) ){
            echo "<div class='ua-alert'>" . esc_html__( "WooCommerce is not Activated.", 'ultraaddons' ) . "</div>";
			return;
        }
		$gap        		= ($settings['_slider_gap']=='yes') ? '' : ' slider-item-nogap';
		$reveal     		= ($settings['_slider_reveal']=='yes') ? ' slider-item-reveal' : '';
		$to_show    		= $settings['_slider_to_show'] ? ' slider-item-show'. $settings['_slider_to_show'] : '';
		$navigation 		= $settings['_slider_navigation'] ? $settings['_slider_navigation'] : '';
		$dark       		= $settings['_slider_nav_dark'] ? ' slider-nav-dark' : '';
		$small      		= $settings['_slider_nav_small'] ? ' slider-nav-sm' : '';
		$autoPlay   		= $settings['_slider_auto_play'] ? ' slider-nav-autoplay' : '';
		$pause      		= $settings['_slider_pause'] ? ' slider-nav-autopause' : '';
		$indicator_outside 	= ($settings['_slider_indicator_outside']!='no') ? ' slider-indicators-outside' : '';
		$indicator_highlight = ($settings['_slider_indicator_highlight']!='no') ? ' slider-indicators-highlight' : '';
		$indicator_visible_sm = ($settings['_slider_indicator_visible_sm']!='no') ? ' slider-indicators-sm' : '';
		$indicator_dark 	= ($settings['_slider_indicator_dark']!='no') ? ' slider-indicators-dark' : '';
		$indicator_shape 	= $settings['_slider_indicator_shape'] ? $settings['_slider_indicator_shape'] : '';
		$nav_visible  		= ($settings['_slider_nav_visible']=='yes') ? ' slider-nav-visible' : '';
		$nav_outside  		= ($settings['_slider_nav_outside']=='yes') ? ' slider-nav-outside' : '';
	   //$mouse_drag  	= ($settings['_slider_mouse_drag']=='yes') ? ' slider-nav-mousedrag' : '';
		
		$this->add_render_attribute(
			'slider_options',
			[
				'class' => 'ua-pc swiffy-slider'. $to_show . $gap . $reveal . " " . $navigation . $dark . $small . $autoPlay  
				. $nav_visible . $nav_outside . $pause . $indicator_outside . $indicator_highlight . $indicator_visible_sm . " ". $indicator_shape . $indicator_dark  ,
				'data-slider-nav-autoplay-interval'=> $settings['_slider_speed']
			]
		);
		$back_view 	= ( $settings['_ua_back_view'] =='yes' && Plugin::$instance->editor->is_edit_mode() ) ? 'style="opacity:1; transform:rotateY(-20deg)" ' :'';
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
			$count=0;
			$number=array();
            while ( $loop->have_posts() ) : $loop->the_post();
				$id 		= $loop->post->ID;
                $product   	= wc_get_product( $id );
				$image_id  	= $product->get_image_id();
				$image_url 	= wp_get_attachment_image_url( $image_id, 'full' );
				$description = $loop->post->post_excerpt;
				$count		= $count+1;
				$number[]	=$count;
            ?>
        <li>
			<div class="ua-product-flip flip-<?php echo $settings['_ua_product_flip_animation_type']; ?>">
				<div class="front" style="background:url(<?php echo esc_url($image_url);?>)">
					<?php if ( $product->is_on_sale() ) : 
					echo apply_filters( 'woocommerce_sale_flash', '<span class="ua-onsale">' . esc_html__( 'Sale!', 'ultraaddons' ) . '</span>', $product );
					endif;
					?>
					<a href="<?php echo get_the_permalink(); ?>">
						<?php
						echo '<' . $settings['_ua_front_title_tag'] . ' class="front-title">' . $loop->post->post_title . 
								'</' . $settings['_ua_front_title_tag'] . '>';
						?>
					</a>
					<span class="ua-product-price">
						<?php echo $product->get_price_html();?> 
					</span>
				</div>
				<div class="back" <?php echo $back_view; ?>>
					<a href="<?php echo get_the_permalink(); ?>">
						<?php
						echo '<' . $settings['_ua_back_title_tag'] . ' class="back-title">' . $loop->post->post_title . 
								'</' . $settings['_ua_back_title_tag'] . '>';
						?>
					</a>
					<div class="ua-desc">
						<?php echo $this->word_shortener($description, $settings['_ua_text_truncate']);?>
					</div>
					<div class="ua-cart">
						<?php 
						
						woocommerce_template_loop_add_to_cart();
						/**
							* For after Add to cart button on cart page.
							* 
							* @hook ultraaddons/widget/product_flip/after_cart
							* 
							* @author Saiful Islam <codersaiful@gmail.com>
							* @since 1.1.0.8
							*/
						do_action( 'ultraaddons/widget/product_flip/after_cart' );
						?>
					</div>
				</div>
				<?php
				endwhile;
				} else {
					echo "<div class='ua-alert'>" . esc_html__( "No products found!", 'ultraaddons' ) . "</div>";
				}
				wp_reset_postdata();
				?>
        </li>
    </ul>

	<?php
	if( $navigation !='none'):?>
		<button type="button" class="slider-nav" aria-label="Go to previous"></button>
		<button type="button" class="slider-nav slider-nav-next" aria-label="Go to next"></button>
	<?php endif;?>

	<?php
	if('yes'== $settings['_slider_indicator']){
	?>
	<ul class="slider-indicators">
		<?php 
		foreach($number as $numbers){
			if($numbers==1){ 
				echo '<li class="active"></li>';
			}
			else{
				echo '<li></li>';
			}
		}
		?>
	</ul>
	<?php
	}
	?>

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
	/**
	 * SHorter Description
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
    
    
}//End Class
