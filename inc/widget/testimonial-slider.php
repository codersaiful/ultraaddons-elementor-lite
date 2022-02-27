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

class Testimonial_Slider extends Base{
    
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

        //Naming of Args for owlCarousel
        $name           = 'owlCarousel';
        $js_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/js/owl.carousel.min.js';
        $dependency     =  ['jquery'];//['jquery'];
        $version        = ULTRA_ADDONS_VERSION;
        $in_footer  = true;

        wp_register_script( $name, $js_file_url, $dependency, $version, $in_footer );
        wp_enqueue_script( $name );


        //CSS file for Slider Script Owl Carousel Slider
        wp_register_style('owlCarousel', ULTRA_ADDONS_ASSETS . 'vendor/css/owl.carousel.css' );
        wp_enqueue_style('owlCarousel' );

        wp_register_style('owlCarousel-theme', ULTRA_ADDONS_ASSETS . 'vendor/css/owl/owl.theme.default.css' );
        wp_enqueue_style( 'owlCarousel-theme' );

    }

        /**
         * By Saiful Islam
         * depend css for this widget
         * 
         * @return Array
         */
    public function get_style_depends() {
        return ['owlCarousel','owlCarousel-theme'];
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
            return [ 'jquery','owlCarousel' ];
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
        return [ 'ultraaddons', 'ua', 'testimonial', 'review', 'feedback', 'user', 'rating', 'slider' ];
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
        $this->content_general_controls();

        //For Design Section Style Tab
        $this->style_general_controls();
        
        //Section in Style Tab
        $this->style_title_controls();
        $this->style_subtitle_controls();
        $this->style_quote_controls();
        $this->style_avatar_controls();
        $this->slider_controls();
        $this->style_navigation_controls();

       
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

        $this->add_render_attribute( 'wrapper', 'class', 'ua-testimonial-slider-wrapper' );
        $slider_items = $settings['testimonial_items'];
        ?>
        <div class="ua-testimonial-main-wrapper">
            <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
                <?php 
                foreach( $slider_items as $key => $item ){
                    $_id = !empty( $item['_id'] ) ? $item['_id'] : false;

                    $this->add_render_attribute( 'item' . '-' . $_id, 'class', 'ua-testimonial ua-testimonial-' . $_id );
                    $this->add_render_attribute( 'title' . '-' . $_id, 'class', 'ua-testimonial-title ua-testimonial-title-' . $_id );
                    $this->add_render_attribute( 'sub-title' . '-' . $_id, 'class', 'ua-testimonial-subtitle ua-testimonial-subtitle-' . $_id );
                    $this->add_render_attribute( 'quote' . '-' . $_id, 'class', 'ua-testimonial-quote ua-testimonial-quote-' . $_id );

//                    $this->add_inline_editing_attributes( 'title'       . '-' . $_id, 'none' );
//                    $this->add_inline_editing_attributes( 'sub-title'   . '-' . $_id, 'none' );
//                    $this->add_inline_editing_attributes( 'quote'       . '-' . $_id, 'advanced' );

                    $image = !empty( $item['image']['url'] ) ? $item['image']['url'] : false;
                    if( empty( $image ) ){
                        $this->add_render_attribute( 'item' . '-' . $_id, 'class', 'no-profile-image' );
                    }
                ?>
                <div <?php echo $this->get_render_attribute_string( 'item' . '-' . $_id ); ?>>
                    <div class="client-quote-box">
                        <span class="quote-icon">
                            <i class="fas fa-quote-left"></i>
                        </span>
                        <?php echo '<p ' . $this->get_render_attribute_string( 'quote' . '-' . $_id ) . '>' . $item['quote'] . '</p>'; ?>
                        <div class="client-info">
                            <div class="user-avatar" 
                                <?php if( $image ){ ?>
                                    style="background-image: url(<?php echo esc_attr( $image ); ?>);"
                                <?php } ?> 
                                 ></div>
                            <div class="user-name">
                                <?php echo '<p ' . $this->get_render_attribute_string( 'title' . '-' . $_id ) . '>' . $item['title'] . '</p>'; ?>
                                <?php echo '<span ' . $this->get_render_attribute_string( 'sub-title' . '-' . $_id ) . '>' . $item['sub-title'] . '</span>'; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>

            </div>
        </div>
        <?php
        
    }
    
    protected function content_template() {}
    
    /**
     * General Section for Content Controls
     * 
     * @since 1.0.0.9
     */
    protected function content_general_controls() {
        
        $placeholder_image = ULTRA_ADDONS_URL . 'assets/images/user.png';
        
        $this->start_controls_section(
            'general_content',
            [
                'label'     => esc_html__( 'General', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $repeater = new Repeater();
        $repeater->add_control(
                'title',
                [
                    'label' => __( 'Title', 'ultraaddons' ),
                    'type' => Controls_Manager::TEXT,
                    'default'       => __( 'Jonny Robartson', 'ultraaddons' ),
                    'label_block'   => TRUE,
                    'dynamic'       => ['active' => true],
                ]
        );
        
        $repeater->add_control(
                'sub-title',
                [
                    'label' => __( 'Position/Designation', 'ultraaddons' ),
                    'type' => Controls_Manager::TEXT,
                    'default'       => __( 'UI/UX Designer', 'ultraaddons' ),
                    'label_block'   => TRUE,
                    'dynamic'       => ['active' => true],
                ]
        );
        
        $repeater->add_control(
                'quote',
                [
                    'label' => __( 'Quote', 'ultraaddons' ),
                    'type' => Controls_Manager::TEXTAREA,
                    'default'       => __( 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incdidunt ut labore et dolore magna do aliqua quis ipsum suspendisse ces gravida. Risus commodo viverra maecenas.', 'ultraaddons' ),
                    'label_block'   => TRUE,
                    'dynamic'       => ['active' => true],
                    'rows' => 5,
                    'separator' => 'after',
                ]
        );
        
        $repeater->add_control(
                'image',
                [
                        'label' => __( 'Photo', 'ultraaddons' ),
                        'type' => Controls_Manager::MEDIA,
                        'default' => [
                                'url' => $placeholder_image,//Utils::get_placeholder_image_src(),
                        ],
                        'dynamic' => [
                                'active' => true,
                        ]
                ]
        );
        $default_icon = [
                        'value' => 'far fa-check-square',
                        'library' => 'regular',
                ];
        $this->add_control(
                'testimonial_items',
                [
                        'type' => Controls_Manager::REPEATER,
                        'fields' => $repeater->get_controls(),
                        'default' => [
                                [
                                        'icon' => $default_icon,
                                        'title' => __( 'Saiful Islam', 'ultraaddons' ),
                                        'sub-title' => __( 'Web Developer', 'ultraaddons' ),
                                        'quote' => __( 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incdidunt ut labore et dolore magna do aliqua quis ipsum suspendisse ces gravida. Risus commodo viverra maecenas.', 'ultraaddons' ),
                                        'image' => [
                                                'url' => $placeholder_image,
                                        ],
                                ],
                                [
                                        'icon' => $default_icon,
                                        'title' => __( 'Mukul Robartson', 'ultraaddons' ),
                                        'sub-title' => __( 'UI/UX Designer', 'ultraaddons' ),
                                        'quote' => __( 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incdidunt ut labore et dolore magna do aliqua quis ipsum suspendisse ces gravida. Risus commodo viverra maecenas.', 'ultraaddons' ),
                                        'image' => [
                                                'url' => $placeholder_image,
                                        ],
                                ],
                                [
                                        'icon' => $default_icon,
                                        'title' => __( 'Jonny Robartson', 'ultraaddons' ),
                                        'sub-title' => __( 'UI/UX Designer', 'ultraaddons' ),
                                        'quote' => __( 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incdidunt ut labore et dolore magna do aliqua quis ipsum suspendisse ces gravida. Risus commodo viverra maecenas.', 'ultraaddons' ),
                                        'image' => [
                                                'url' => $placeholder_image,
                                        ],
                                ],
                                [
                                        'icon' => $default_icon,
                                        'title' => __( 'Jonny Robartson', 'ultraaddons' ),
                                        'sub-title' => __( 'UI/UX Designer', 'ultraaddons' ),
                                        'quote' => __( 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incdidunt ut labore et dolore magna do aliqua quis ipsum suspendisse ces gravida. Risus commodo viverra maecenas.', 'ultraaddons' ),
                                        'image' => [
                                                'url' => $placeholder_image,
                                        ],
                                ],
                                
                        ],
                        'title_field' => '{{{ title }}}',
                ]
        );
        
               
        
        $this->end_controls_section();
    }
    
    /**
     * Alignment Section for Style Tab
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
                'text_align',
                [
                        'label' => __( 'Alignment', 'ultraaddons' ),
                        'type' => Controls_Manager::CHOOSE,
                        'options' => [
                                'left' => [
                                        'title' => __( 'Left', 'ultraaddons' ),
                                        'icon' => 'eicon-text-align-left',
                                ],
                                'center' => [
                                        'title' => __( 'Center', 'ultraaddons' ),
                                        'icon' => 'eicon-text-align-center',
                                ],
                                'right' => [
                                        'title' => __( 'Right', 'ultraaddons' ),
                                        'icon' => 'eicon-text-align-right',
                                ],
                        ],
                        'default' => 'center',
                        'toggle' => true,
                        'prefix_class' => 'elementor-align-',
                        'default' => 'left',
                ]
        );
        
        
        
        
        $this->start_controls_tabs('testimonial-style-tabs');
        
        
        $this->start_controls_tab('testimonial-stl-tab-normal', 
                [
                    'label' => __( 'Normal', 'ultraaddons' ),
                ]
        );
        
        $this->add_control(
            'bg-color',
            [
                'label'     => __( 'Background Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-slider-wrapper .client-quote-box' => 'background-color: {{VALUE}}',
                ],
                'default'   => '#F4F9FC',
            ]
        );
        
        
        $this->add_responsive_control(
                'padding',
                [
                        'label' => __( 'Padding', 'ultraaddons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%' ],
                        'default'   => [
                                'top' => 50,
                                'right' => 45,
                                'bottom' => 50,
                                'left' => 45,
                                'unit' => 'px',
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .ua-testimonial-slider-wrapper .client-quote-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'border',
                'label'       => esc_html__( 'Border', 'ultraaddons' ),
                'placeholder' => '1px',
                'default'     => '',
                'selector'    => '{{WRAPPER}} .ua-testimonial-slider-wrapper .client-quote-box',
            )
        );
        
        $this->add_control(
                'quote-icon-size',
                [
                        'label' => __( 'Quote Icon Size', 'ultraaddons' ),
                        'type' => Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%' ],
                        'range' => [
                                'px' => [
                                        'min' => 0,
                                        'max' => 100,
                                        'step' => 1,
                                ],
                                '%' => [
                                        'min' => 0,
                                        'max' => 100,
                                ],
                        ],
                        'default' => [
                                'unit' => 'px',
                                'size' => 50,
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .ua-testimonial-slider-wrapper .client-quote-box span.quote-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                        ],
                ]
        );
        
        $this->add_control(
                'quote_icon_color',
                [
                        'label' => __( 'Quote Icon Color', 'ultraaddons' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '#0FC392',
                        'selectors' => [
                                '{{WRAPPER}} .ua-testimonial-slider-wrapper .client-quote-box span.quote-icon' => 'color: {{VALUE}}',
                        ],
                ]
        );
        
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'testimonial-shadow',
                'selector' => '{{WRAPPER}} .ua-testimonial-slider-wrapper .client-quote-box',
            )
        );
        
        
        $this->end_controls_tab();
        
        
        
        
        
        $this->start_controls_tab('testimonial-stl-tab-hover', 
                [
                    'label' => __( 'Hover', 'ultraaddons' ),
                ]
        );
        
        $this->add_control(
            'bg-color-hover',
            [
                'label'     => __( 'Background Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-slider-wrapper .client-quote-box:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        
        $this->add_responsive_control(
                'padding-hover',
                [
                        'label' => __( 'Padding', 'ultraaddons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%' ],
                        'selectors' => [
                                '{{WRAPPER}} .ua-testimonial-slider-wrapper .client-quote-box:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'border-hover',
                'label'       => esc_html__( 'Border', 'ultraaddons' ),
                'placeholder' => '1px',
                'default'     => '',
                'selector'    => '{{WRAPPER}} .ua-testimonial-slider-wrapper .client-quote-box:hover',
            )
        );
        
        $this->add_control(
                'quote-icon-size-hover',
                [
                        'label' => __( 'Quote Icon Size', 'ultraaddons' ),
                        'type' => Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%' ],
                        'range' => [
                                'px' => [
                                        'min' => 0,
                                        'max' => 100,
                                        'step' => 1,
                                ],
                                '%' => [
                                        'min' => 0,
                                        'max' => 100,
                                ],
                        ],

                        'selectors' => [
                                '{{WRAPPER}} .ua-testimonial-slider-wrapper .client-quote-box:hover span.quote-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                        ],
                ]
        );
        
        $this->add_control(
                'quote_icon_color-hover',
                [
                        'label' => __( 'Quote Icon Color', 'ultraaddons' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                                '{{WRAPPER}} .ua-testimonial-slider-wrapper .client-quote-box:hover span.quote-icon' => 'color: {{VALUE}}',
                        ],
                ]
        );
        
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'testimonial-shadow-hover',
                'selector' => '{{WRAPPER}} .ua-testimonial-slider-wrapper .client-quote-box:hover',
            )
        );
        
        $this->end_controls_tab();
        
        
        
        
        
        
        
        
        
        
        $this->end_controls_tabs();
        
        
        
        
        
        
       
        $this->end_controls_section();
    }
    
    /**
     * Section for Title in Style Tab
     * 
     * @since 1.0.0.15
     */
    protected function style_title_controls() {
        $this->start_controls_section(
            'title-typography',
            [
                'label'     => esc_html__( 'Title', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'title_typography',
                        'label' => 'Title Typography',
                        'selector' => '{{WRAPPER}} .ua-testimonial-slider-wrapper .client-quote-box .ua-testimonial-title',
                ]
        );
        
        $this->add_control(
                'title_color',
                [
                        'label' => __( 'Color', 'ultraaddons' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '#5C6B79',
                        'selectors' => [
                                '{{WRAPPER}} .ua-testimonial-slider-wrapper .client-quote-box .ua-testimonial-title' => 'color: {{VALUE}}',
                        ],
                ]
        );
        
        
        $this->end_controls_section();
    }
    
    /**
     * Section for Sub Title in Style Tab
     * 
     * @since 1.0.0.15
     */
    protected function style_subtitle_controls() {
        $this->start_controls_section(
            'subtitle-typography',
            [
                'label'     => esc_html__( 'Designation', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'position_typography',
                        'label' => 'Designation Typography',
                        'selector' => '{{WRAPPER}} .ua-testimonial-slider-wrapper .client-quote-box .ua-testimonial-subtitle',
                ]
        );
        
        $this->add_control(
                'subtitle_color',
                [
                        'label' => __( 'Color', 'ultraaddons' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '#5C6B79',
                        'selectors' => [
                                '{{WRAPPER}} .ua-testimonial-slider-wrapper .client-quote-box .ua-testimonial-subtitle' => 'color: {{VALUE}}',
                        ],
                ]
        );
        
        
        $this->end_controls_section();
    }
    
    /**
     * Section for Quote in Style Tab
     * 
     * @since 1.0.0.15
     */
    protected function style_quote_controls() {
        $this->start_controls_section(
            'quote-typography',
            [
                'label'     => esc_html__( 'Quote', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'quote_typography',
                        'label' => 'Quote Typography',
                        'selector' => '{{WRAPPER}} .ua-testimonial-slider-wrapper .client-quote-box .ua-testimonial-quote',
                ]
        );
        
        $this->add_control(
                'quote_color',
                [
                        'label' => __( 'Color', 'ultraaddons' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '#54595F',
                        'selectors' => [
                                '{{WRAPPER}} .ua-testimonial-slider-wrapper .client-quote-box .ua-testimonial-quote' => 'color: {{VALUE}}',
                        ],
                ]
        );
        
        
        $this->end_controls_section();
    }
    
    /**
     * Section for Navigation
     * @author B M Rafiul Alam
     * 
     * @since 1.1.0.11
     */
    protected function style_navigation_controls() {
        $this->start_controls_section(
            'nav',
            [
                'label'     => esc_html__( 'Navigation', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'nav_border',
                'label'       => esc_html__( 'Border', 'ultraaddons' ),
                'fields_options' => [
			'border' => [
				'default' => 'solid',
			],
			'width' => [
				'default' => [
					'top' => '1',
					'right' => '1',
					'bottom' => '1',
					'left' => '1',
					'isLinked' => false,
				],
			],
			'color' => [
				'default' => '#1C9792',
			],
		],
                'selector'    => '{{WRAPPER}} .ua-testimonial-slider-wrapper button.owl-prev, {{WRAPPER}} .ua-testimonial-slider-wrapper button.owl-next',
            )
        );
        $this->add_control(
                'navigation_icon_color',
                [
                        'label' => __( 'Navigation Icon Color', 'ultraaddons' ),
                        'type' => Controls_Manager::COLOR,
//                        'default' => '#0FC392',
                        'selectors' => [
                                '{{WRAPPER}} .ua-testimonial-slider-wrapper .owl-dots button.owl-dot' => 'background-color: {{VALUE}}',
                                '{{WRAPPER}} .ua-testimonial-slider-wrapper .owl-dots button.owl-dot.active' => 'border-color: {{VALUE}}; background-color: #FFF',
                                '{{WRAPPER}} .ua-testimonial-slider-wrapper button.owl-prev' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .ua-testimonial-slider-wrapper button.owl-next' => 'color: {{VALUE}}'
                        ],
                ]
        );
       
        $this->add_control(
                'navigation_bg_color',
                [
                        'label' => __( 'Navigation BG Color', 'ultraaddons' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                                '{{WRAPPER}} .ua-testimonial-main-wrapper .owl-nav button.owl-next, .ua-testimonial-main-wrapper .owl-nav button.owl-prev' => 'background-color: {{VALUE}};border-color: {{VALUE}};',
                        ],
                ]
        );
        
        $this->add_responsive_control(
                'nav_radius',
                [
                        'label' => __( 'Radius', 'ultraaddons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%' ],
                        'selectors' => [
                                '{{WRAPPER}} .ua-testimonial-slider-wrapper button.owl-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .ua-testimonial-slider-wrapper button.owl-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                      ],
                ]
        );
         $this->add_responsive_control(
                'nav_padding',
                [
                        'label' => __( 'Padding', 'ultraaddons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%' ],
                        'selectors' => [
                                '{{WRAPPER}} .ua-testimonial-slider-wrapper button.owl-prev' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .ua-testimonial-slider-wrapper button.owl-next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                      ],
                ]
        );
       
        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                'name' => 'navigation_shadow',
                'label' => esc_html__( 'Navigation Shadow', 'plugin-name' ),
                'selector' => '{{WRAPPER}} .ua-testimonial-slider-wrapper button.owl-prev, .ua-testimonial-slider-wrapper button.owl-next',
             
                ]
        );
        $this->end_controls_section();
    }

     /**
     * Section for Quote in Style Tab
     * 
     * @since 1.0.0.15
     */
    protected function style_avatar_controls() {
        $this->start_controls_section(
            'avatar',
            [
                'label'     => esc_html__( 'Avatar', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'avatar-border',
                'label'       => esc_html__( 'Border', 'ultraaddons' ),
                'fields_options' => [
			'border' => [
				'default' => 'solid',
			],
			'width' => [
				'default' => [
					'top' => '1',
					'right' => '1',
					'bottom' => '1',
					'left' => '1',
					'isLinked' => false,
				],
			],
			'color' => [
				'default' => '#1C9792',
			],
		],
                'selector'    => '{{WRAPPER}} .ua-testimonial-slider-wrapper .client-info .user-avatar',
            )
        );
        
        $this->add_control(
                'avatar-size',
                [
                        'label' => __( 'Size', 'ultraaddons' ),
                        'type' => Controls_Manager::SLIDER,
                        'size_units' => [ 'px' ],
                        'range' => [
                                'px' => [
                                        'min' => 0,
                                        'max' => 100,
                                        'step' => 1,
                                ],
                        ],
                        'default' => [
                                'unit' => 'px',
                                'size' => 50,
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .ua-testimonial-slider-wrapper .client-info .user-avatar' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                        ],
                ]
        );
        
        
        $this->end_controls_section();
    }
    
    
    /**
     * 
     * @since 1.0.0.15
     */
    protected function slider_controls(){
        $this->start_controls_section(
            'slider-settings',
            [
                'label'     => esc_html__( 'Slider Settings', 'ultraaddons' ),
            ]
        );
        
        $this->add_control(
                'autoplay',
                [
                        'label' => __( 'Autoplay?', 'ultraaddons' ),
                        'type' => Controls_Manager::SWITCHER,
                        'label_on' => __( 'Yes', 'ultraaddons' ),
                        'label_off' => __( 'No', 'ultraaddons' ),
                        'default' => 'yes',
                        'return_value' => 'yes',
                        'frontend_available' => true,
                ]
        );

        $this->add_control(
                'pause_on_hover',
                [
                        'label' => __( 'Pause on Hover', 'ultraaddons' ),
                        'type' => Controls_Manager::SWITCHER,
                        'label_on' => __( 'Yes', 'ultraaddons' ),
                        'label_off' => __( 'No', 'ultraaddons' ),
                        'default' => 'yes',
                        'return_value' => 'yes',
                        'condition' => [
                                'autoplay' => 'yes',
                        ],
                        'frontend_available' => true,
                ]
        );

        $this->add_control(
                'autoplay_speed',
                [
                        'label' => __( 'Autoplay Speed', 'ultraaddons' ),
                        'type' => Controls_Manager::NUMBER,
                        'min' => 100,
                        'step' => 100,
                        'max' => 10000,
                        'default' => 3000,
                        'description' => __( 'Autoplay speed in milliseconds', 'ultraaddons' ),
                        'condition' => [
                                'autoplay' => 'yes',
                        ],
                        'frontend_available' => true,
                ]
        );

        // Loop requires a re-render so no 'render_type = none'
        $this->add_control(
                'loop',
                [
                        'label' => __( 'Infinite Loop?', 'ultraaddons' ),
                        'type' => Controls_Manager::SWITCHER,
                        'label_on' => __( 'Yes', 'ultraaddons' ),
                        'label_off' => __( 'No', 'ultraaddons' ),
                        'default' => 'yes',
                        'return_value' => 'yes',
                        'frontend_available' => true,
                ]
        );

        $this->add_control(
                'autoplayTimeout',
                [
                        'label' => __( 'Autoplay Delay', 'ultraaddons' ),
                        'type' => Controls_Manager::NUMBER,
                        'min' => 1000,
                        'step' => 1000,
                        'max' => 10000,
                        'default' => 3000,
                        'description' => __( 'Autoplay delay in milliseconds', 'ultraaddons' ),
                        'frontend_available' => true,
                ]
        );
        
        $this->add_control(
                'navigation',
                [
                        'label' => __( 'Navigation', 'ultraaddons' ),
                        'type' => Controls_Manager::SELECT,
                        'options' => [
                                'none' => __( 'None', 'ultraaddons' ),
                                'arrow' => __( 'Arrow', 'ultraaddons' ),
                                'dots' => __( 'Dots', 'ultraaddons' ),
                                'both' => __( 'Arrow & Dots', 'ultraaddons' ),
                        ],
                        'default' => 'arrow',
                        'frontend_available' => true,
                        'style_transfer' => true,
                ]
        );
        
        
        
                
        $this->add_control(
                'navigation_arrow_position',
                [
                        'label' => __( 'Arrow Position', 'ultraaddons' ),
                        'type' => Controls_Manager::SELECT,
                        'options' => [
                                'top-right'      => __( 'Top Right', 'ultraaddons' ),
                                'top-left'      => __( 'Top Left', 'ultraaddons' ),
//                                'center'    => __( 'Center', 'ultraaddons' ),
                                'bottom-right'    => __( 'Bottom Right', 'ultraaddons' ),
                                'bottom-left'    => __( 'Bottom Left', 'ultraaddons' ),
                        ],
                        'condition' => [
                                'navigation' => ['arrow','both'],
                                //'navigation_type' => ['arrow'],
                        ],
                        'default' => 'top-right',
                        'prefix_class' => 'navigation-arrow-position-',
                ]
        );
           
        $this->add_control(
                'next_prev_spacing',
                [
                        'label' => __( 'Navigation Button Spacing', 'elementor' ),
                        'type' => Controls_Manager::SLIDER,
                        'default' => [
                                'size' => 70,
                        ],
                        'range' => [
                                'px' => [
                                        'min' => 1,
                                        'max' => 250,
                                        'step' => 1,
                                ],
                        ],
                        'condition' => [
                                'navigation_arrow_position' => ['center','top-right','top-left','bottom-right','bottom-left'],
                                'navigation' => ['arrow','both'],
                        ],
                        'selectors' => [
                                '{{WRAPPER}}.navigation-arrow-position-bottom-right .ua-testimonial-main-wrapper .owl-nav,{{WRAPPER}}.navigation-arrow-position-bottom-left .ua-testimonial-main-wrapper .owl-nav' => 'bottom: -{{SIZE}}{{UNIT}};',
                                '{{WRAPPER}}.navigation-arrow-position-top-left .ua-testimonial-main-wrapper .owl-nav,{{WRAPPER}}.navigation-arrow-position-top-right .ua-testimonial-main-wrapper .owl-nav' => 'top: -{{SIZE}}{{UNIT}};',
//                                '{{WRAPPER}}.navigation-arrow-position-center .ua-testimonial-main-wrapper .owl-nav' => 'top: {{SIZE}}%;',
                        ],

                ]
        );
        
//        $this->add_control(
//                'next_prev_center_spacing',
//                [
//                        'label' => __( 'Navigation Spacing', 'elementor' ),
//                        'type' => Controls_Manager::SLIDER,
////                                'size_units' => [ 'px' ],
//                        'default' => [
////                                        'unit' => '%',
//                                'size' => 96,
//                        ],
//                        'range' => [
//                                'px' => [
//                                        'min' => 1,
//                                        'max' => 100,
//                                        'step' => 1,
//                                ],
//                        ],
//                        'condition' => [
//                                'navigation_arrow_position' => ['center'],
//
//                        ],
//                        'selectors' => [
////                                        '{{WRAPPER}}.navigation-arrow-position-center .ua-slider-wrapper .owl-nav' => 'width: {{SIZE}}{{UNIT}};',
//                                '{{WRAPPER}}.navigation-arrow-position-center .ua-testimonial-main-wrapper .owl-nav' => 'width: {{SIZE}}%;margin-left: calc((100% - {{SIZE}}%)/2);',
//                        ],
//
//                ]
//        );        

        
        $this->add_responsive_control(
                'slides_to_show',
                [
                        'label' => __( 'Slides To Show', 'ultraaddons' ),
                        'type' => Controls_Manager::SELECT,
                        'options' => [
                                1 => __( '1 Slide', 'ultraaddons' ),
                                2 => __( '2 Slides', 'ultraaddons' ),
                                3 => __( '3 Slides', 'ultraaddons' ),
                                4 => __( '4 Slides', 'ultraaddons' ),
                                5 => __( '5 Slides', 'ultraaddons' ),
                                6 => __( '6 Slides', 'ultraaddons' ),
                        ],
                        'desktop_default' => 3,
                        'tablet_default' => 2,
                        'mobile_default' => 1,
                        'frontend_available' => true,
                        'style_transfer' => true,
                ]
        );
        
        $this->end_controls_section();
    }
       
    
}