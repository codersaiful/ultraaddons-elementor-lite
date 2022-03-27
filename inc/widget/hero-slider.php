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
use Elementor\Utils;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Hero_Slider extends Base{

    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);

        //Naming of Args for swiper
        $name           = 'swiper';
        $js_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/swiper/js/swiper.min.js';
        $dependency     =  ['jquery'];//['jquery'];
        $version        = ULTRA_ADDONS_VERSION;
        $in_footer      = true;

        wp_register_script( $name, $js_file_url, $dependency, $version, $in_footer );
        wp_enqueue_script( $name );

        $name          = 'frontend-hero-slider';
        $js_file_url   = ULTRA_ADDONS_ASSETS . 'js/frontend-hero-slider.js';
        $dependency    =  [];//['jquery'];
        $version       = ULTRA_ADDONS_VERSION;
        $in_footer  	  = true;

        wp_register_script( $name , $js_file_url, $dependency, $version, $in_footer );
        wp_enqueue_script( $name );

        //CSS file swiper
        wp_register_style('swiper', ULTRA_ADDONS_ASSETS . 'vendor/swiper/css/swiper.min.css' );
        wp_enqueue_style('swiper' );

    }
	/**
     * By Saiful Islam
     * depend css for this widget
     * 
     * @return Array
     */
    public function get_style_depends() {
        return ['swiper'];
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
		return [ 'jquery','swiper','frontend-hero-slider' ];
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
        return [ 'ultraaddons','ua', 'slider', 'hero', 'carousel','banner' ];
    }
    
    
    /**
     * Register oEmbed widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls() {
        //For General Section
        $this->content_general_controls();
        $this->slider_settings_controls();
        $this->slider_general_style();
        $this->slider_btn_style();
        $this->slider_pagination_style();
        $this->slider_navigation_style();
    }
    
    protected function slider_settings_controls(){
        $this->start_controls_section(
            'slider_settings',
            [
                'label'     => esc_html__( 'Slider Settings . ', 'ultraaddons' ),
            ]
        );

		$this->add_control(
			'loop',
			[
				'label' => __( 'Loop', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ultraaddons' ),
				'label_off' => __( 'No', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'frontend_available' => true,
			]
		);	
		
		$this->add_control(
			'speed',
			[
				'label' => __( 'Speed', 'ultraaddons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 500,
				'max' => 3000,
				'step' => 500,
				'default' => 1000,
				'frontend_available' => true,
			]
		);
        $this->add_control(
			'delay',
			[
				'label' => __( 'Delay', 'ultraaddons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1000,
				'max' => 7000,
				'step' => 1000,
				'default' => 5000,
				'frontend_available' => true,
			]
		);
	

        $this->add_control(
			'stopOnHover',
			[
				'label' => __( 'Stop On Hover', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ultraaddons' ),
				'label_off' => __( 'No', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'yes',
                'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'effect',
			[
				'label' => __( 'Effects', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fade',
				'frontend_available' => true,
				'options' => [
					'fade'  => __( 'Fade', 'ultraaddons' ),
					'flip' => __( 'Flip', 'ultraaddons' ),
				],
			]
		);
       /*  $this->add_control(
			'slidesPerView',
			[
				'label' => __( 'Slides View', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'frontend_available' => true,
				'options' => [
                    'auto'  => __( 'Auto', 'ultraaddons' ),
					'1'  => __( 'One', 'ultraaddons' ),
					'2' => __( 'Two', 'ultraaddons' ),
					'3' => __( 'Three', 'ultraaddons' ),
					'4' => __( 'Four', 'ultraaddons' ),
				],
			]
		); */
       /*  $this->add_control(
			'spaceBetween',
			[
				'label' => __( 'Space Between', 'ultraaddons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 100,
				'step' => 2,
				'default' => 50,
				'frontend_available' => true,
                'condition' => [
                    'slidesPerView!' => 'default',
                ],
			]
		); */
		/* $this->add_control(
			'direction',
			[
				'label' => __( 'Direction', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'vertical',
				'frontend_available' => true,
				'options' => [
					'vertical'  => __( 'Vertical', 'ultraaddons' ),
					'horizontal' => __( 'Horizontal', 'ultraaddons' ),
				],
			]
		); */
        $this->add_control(
			'navigation',
			[
				'label' => __( 'Navigation', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ultraaddons' ),
				'label_off' => __( 'No', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'yes',
                'frontend_available' => true,
			]
		);
        $this->add_control(
			'pagination',
			[
				'label' => __( 'Pagination', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ultraaddons' ),
				'label_off' => __( 'No', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'yes',
                'frontend_available' => true,
			]
		);
        //'bullets' | 'fraction' | 'progressbar' | 'custom'
        $this->add_control(
			'pagination_type',
			[
				'label' => __( 'Pagination Type', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'bullets',
				'frontend_available' => true,
				'options' => [
					'bullets'  => __( 'Bullets', 'ultraaddons' ),
					'fraction' => __( 'Fraction', 'ultraaddons' ),
					'progressbar' => __( 'Progressbar', 'ultraaddons' ),
				],
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
                'label'     => esc_html__( 'General', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
        $repeater = new \Elementor\Repeater();

		$repeater->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'image',
				'label' => esc_html__( 'Slider Background', 'ultraaddons' ),
				'types' => [ 'classic' ],
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}.swiper-slide',
			]
		);

		$repeater->add_control(
			'list_title', [
				'label' => esc_html__( 'Title', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'List Title' , 'ultraaddons' ),
				'label_block' => true,
			]
		);
        $repeater->add_control(
			'list_content', [
				'label' => esc_html__( 'Content', 'ultraaddons' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Lorem ipsum dolor sit amet consectetur adipisicing elit.' , 'ultraaddons' ),
				'label_block' => true,
				'separator' => 'after'
			]
		);

        $repeater->add_control(
			'list_btn', [
				'label' => esc_html__( 'Button Text', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Read More' , 'ultraaddons' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'action_btn_link',
			[
				'label' => esc_html__( 'Button Link', 'ultraaddons' ),
				'type' => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'ultraaddonse' ),
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
					'custom_attributes' => '',
				],
				'separator' => 'after'
			]
		);
		
		$repeater->add_control(
			'_top_title_animation',
			[
				'label' => esc_html__( 'Top Title Animation', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'options' => ultraaddons_animation(),
				'default' => 'fadeInDown',
				'selectors' => [
					'{{WRAPPER}} .swiper-slide-active {{CURRENT_ITEM}}.ua-slider-sub-title' => 'animation: {{VALUE}} 1s;'
				],
			]
		);
		$repeater->add_control(
			'_title_animation',
			[
				'label' => esc_html__( 'Title Animation', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'options' => ultraaddons_animation(),
				'default' => 'fadeInLeft',
				'selectors' => [
					'{{WRAPPER}} .swiper-slide-active {{CURRENT_ITEM}}.ua-slider-title' => 'animation: {{VALUE}} 1.5s;'
				],
			]
		);
		$repeater->add_control(
			'_button_animation',
			[
				'label' => esc_html__( 'Button Animation', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'options' => ultraaddons_animation(),
				'default' => 'fadeInUp',
				'selectors' => [
					'{{WRAPPER}} .swiper-slide-active {{CURRENT_ITEM}}.ua-slider-buttton' => 'animation: {{VALUE}} 2s;'
				],
			]
		);
        $this->add_control(
			'list',
			[
				'label' => esc_html__( 'Repeater List', 'ultraaddons' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'list_title' => esc_html__( 'iPhone', 'ultraaddons' ),
					],
					[
						'list_title' => esc_html__( 'Mackbook', 'ultraaddons' ),
					],

				],
				'title_field' => '{{{ list_title }}}',
			]
		);

        
        $this->end_controls_section();
    }
     /**
     * General Section for Content Controls
     * 
     * @since 1.0.0.9
     */
    protected function slider_general_style() {
        $this->start_controls_section(
            'general_style',
            [
                'label'     => esc_html__( 'General Style', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
			'_slider_text_alignment',
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
					'{{WRAPPER}} .ua-slider-container' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'slider_height',
			[
				'label' => esc_html__( 'Slider Height', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px','vh' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
						'step' => 10,
					],
					'vh' => [
						'min' => 0,
						'max' => 100,
						'step' => 10,
					],
				],
				'default' => [
					'size' => '450',
				],
				'selectors' => [
					'{{WRAPPER}} .ua-slider-container' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator'=> 'after',
			]
		);
		
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
                'name' => 'slider_title_typo',
                'label' => 'Title Typography',
                'selector' => '{{WRAPPER}} .ua-slider-title',
			]
        );
        $this->add_control(
			'slider_title', [
				'label' => __( 'Title Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-slider-title' => 'color: {{VALUE}};',
				],
				'default'=>'#0b1220'
			]
        );
       
		$this->add_responsive_control(
			'_slide_title_margin',
			[
				'label'       => esc_html__( 'Title Margin', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', '%' ],
				'default' => [
					'top'    => '30',
					'right'  => '0',
					'bottom' => '40',
					'left'   => '0',
					'unit' => 'px',
				],
				
				'selectors'   => [
					'{{WRAPPER}} .ua-slider-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'=> 'after',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
                'name' => 'slider_small_title_typo',
                'label' => 'Small Title Typography',
                'selector' => '{{WRAPPER}} .ua-slider-sub-title',
				'separator'=> 'before',
			]
        );
        $this->add_control(
			'slider_small_title', [
				'label' => __( 'Small Title Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-slider-sub-title' => 'color: {{VALUE}};',
				],
                'separator'=> 'after',
				'default'=>'#144368'
			]
        );

		$this->add_responsive_control(
			'_content_padding',
			[
				'label'       => esc_html__( 'Content Padding', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', '%' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .animated-area' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'overlay',
			[
				'label' => __( 'Overlay', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ultraaddons' ),
				'label_off' => __( 'No', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'separator' => 'before',
				'frontend_available' => true,
			]
		);
		$this->add_control(
			'overlay_bg', [
				'label' => __( 'Overlay Background', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-hero-slider-overlay ' => 'background-color: {{VALUE}};',
				],
                'separator'=> 'after',
				'default'=>'#33333373'
			]
        );
        
        $this->end_controls_section();
    }
    /**
	 * Button Style.
	 */
	protected function slider_btn_style(){
		$id = $this->get_id();
		$this->start_controls_section(
            'slide_btn_style',
            [
                'label'     => esc_html__( 'Button', 'ultraaddons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->start_controls_tabs(
			'slide_btn_normal_tabs'
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
			'_ua_slide_btn_bg', [
				'label' => __( 'Button Background', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .hero-slider-'.$id .' .ua-slider-container .ua-slider-buttton' => 'background: {{VALUE}};',
				]
			]
        );
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'slider_btn_border',
				'label' => esc_html__( 'Button Border', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} .hero-slider-'.$id .' .ua-slider-buttton',
			]
		);
		$this->add_control(
			'_ua_slide_btn_color', [
				'label' => __( 'Button Text Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-slider-container .ua-slider-buttton, i.uicon.uicon-cart' => 'color: {{VALUE}};',
				]
			]
        );
	
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'slide_btn_typography',
					'label' => 'Button Typography',
					'selector' => '{{WRAPPER}} .ua-slider-buttton',
			]
        );
		$this->add_responsive_control(
			'_slide_btn_radius',
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
					'{{WRAPPER}} .ua-hero .ua-slider-container .ua-slider-buttton' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ua-hero .ua-slider-container .ua-slider-buttton:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'_slide_btn_padding',
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
					'{{WRAPPER}} .ua-slider-container .ua-slider-buttton' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_tab();
		/**
		 * Button Hover tab
		 */
		$this->start_controls_tab(
			'slide_btn_hover_tabs',
			[
				'label' => __( 'Hover', 'ultraaddons' ),
			]
		);
		$this->add_control(
			'_ua_btn_animation',
			[
				'label' => esc_html__( 'Select Animation', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'options' => ultraaddons_button_hover(),
				'default' => 'hvr-fade',
			]
		);
		$this->add_control(
			'_ua_slide_btn_hover_bg', [
				'label' => __( 'Button Background', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .hero-slider-'.$id .' .ua-slider-container .ua-slider-buttton:before, .ua-slider-container .ua-slider-buttton:hover' => 'background: {{VALUE}};',
				]
			]
        );
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'slider_btn_hover_border',
				'label' => esc_html__( 'Button Border', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} .ua-slider-container a.ua-slider-buttton:hover',
			]
		);
		$this->add_control(
			'_ua_slide_btn_hover_color', [
				'label' => __( 'Button Text Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-slider-container .ua-slider-buttton' => 'color: {{VALUE}};',
				]
			]
        );

	
		$this->end_controls_tabs();
		
		$this->end_controls_tab();
		
		$this->end_controls_section();
	}
	protected function slider_pagination_style() {
		$id = $this->get_id();
        $this->start_controls_section(
            'pagination_style',
            [
                'label'     => esc_html__( 'Pagination', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_responsive_control(
			'slider_bullet_radius',
			[
				'label'       => esc_html__( 'Bullets Radius', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', '%' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-hero .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'pagination_type' => 'bullets'
				],
			]
		);
		$this->add_control(
			'slider_bullet_height',
			[
				'label' => esc_html__( 'Bullets Height', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					],
				],
				'default' => [
					'size' => '12',
				],
				'selectors' => [
					'{{WRAPPER}} .ua-hero .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'pagination_type' => 'bullets'
				],
			]
		);
		$this->add_control(
			'slider_bullet_width',
			[
				'label' => esc_html__( 'Bullets Width', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					],
				],
				'default' => [
					'size' => '12',
				],
				'selectors' => [
					'{{WRAPPER}} .ua-hero .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'pagination_type' => 'bullets'
				],
			]
		);

		$this->add_control(
			'slider_bullet_color', [
				'label' => __( 'Bullet Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-hero .swiper-pagination-bullet' => 'background: {{VALUE}};',
				],
				'condition' => [
					'pagination_type' => 'bullets'
				],
			]
        );
	
		$this->add_control(
			'slider_progress_fill_color', [
				'label' => __( 'Progress Fill Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-hero .swiper-pagination-progressbar .swiper-pagination-progressbar-fill' => 'background: {{VALUE}};',
				],
				'condition' => [
					'pagination_type' => 'progressbar'
				],
			
			]
        );
		$this->add_control(
			'slider_progress_color', [
				'label' => __( 'Progress Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-hero .swiper-pagination-progressbar' => 'background: {{VALUE}};',
				],
				'condition' => [
					'pagination_type' => 'progressbar'
				],
			]
        );
		$this->add_control(
			'slider_fraction_color', [
				'label' => __( 'Fraction Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-hero .swiper-pagination-fraction' => 'color: {{VALUE}};',
				],
				'condition' => [
					'pagination_type' => 'fraction'
				],
			]
        );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
                'name' => 'fraction_typo',
                'label' => 'Fraction Typography',
                'selector' => '{{WRAPPER}} .ua-hero .swiper-pagination-fraction',
				'condition' => [
					'pagination_type' => 'fraction'
				],
			]
			
        );
		

        $this->end_controls_section();
    }
	protected function slider_navigation_style() {
		$id = $this->get_id();
        $this->start_controls_section(
            'navigation_style',
            [
                'label'     => esc_html__( 'Navigation', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_control(
			'slider_navigation_color', [
				'label' => __( 'Navigation Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .swiper-button-next:after, .swiper-button-prev:after' => 'color: {{VALUE}};',
				],
			]
        );
		$this->add_control(
			'icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 200,
						'step' => 10,
					],
				],
				'default' => [
					'size' => '44',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-button-next:after, .swiper-button-prev:after' => 'font-size: {{SIZE}}{{UNIT}};',
				],
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
        $settings = $this->get_settings_for_display();
		$id= $this->get_id();
        ?>
   	<div class="ua-hero hero-slider-<?php echo $id;?>">
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper">
            <!-- Slides -->
            <?php 
            if ( $settings['list'] ) {
                $count=0;
                foreach (  $settings['list'] as $item ) {
                    $count 			= $count+1;
					$url			= (!empty( $item['action_btn_link']['url'] )) ? $item['action_btn_link']['url']  : '';
					$is_external	= ( $item['action_btn_link']['is_external']=='on') ? 'target="_blank"' : '';
					$nofollow		= ( $item['action_btn_link']['nofollow']=='on') ? 'rel="nofollow"' :'';
            ?>
            <div class="swiper-slide slide-<?php echo $count;?> elementor-repeater-item-<?php echo $item['_id']; ?>">
                <div class="ua-image">
                    <div class="ua-slider-container">
                        <div class="animated-area">
							<h4 class="ua-slider-sub-title elementor-repeater-item-<?php echo $item['_id']; ?>">
								<?php echo $item['list_title'];?>
							</h4>
							<h1 class="ua-slider-title elementor-repeater-item-<?php echo $item['_id']; ?>"><?php echo $item['list_content'];?></h1>
							<a href="<?php echo esc_url($url); ?>" <?php echo esc_attr($is_external);?> <?php echo esc_attr($nofollow);?> class="ua-slider-buttton elementor-repeater-item-<?php echo $item['_id']; ?> <?php echo $settings['_ua_btn_animation'];?>"><?php echo $item['list_btn'];?></a>
                        </div>
                    </div>
                </div>
				<?php if('yes'===$settings['overlay']):?>
					<div class="ua-hero-slider-overlay"></div>
				<?php endif;?>
            </div>
            <?php }
        }
        ?>
        </div>

         <?php if( 'yes'== $settings['pagination'] ):?>
            <div class="swiper-pagination"></div>
         <?php endif;?>
         <?php if( 'yes'== $settings['navigation'] ):?>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        <?php endif;?>
    
    </div>
        <?php
        
    }
    
    
}
