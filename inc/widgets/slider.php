<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Utils;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Slider extends Base{
    
        
        /**
         * Find by search
         *
         * Retrieve widget title.
         *
         * @since 1.0.0
         * @access public
         *
         * @return string keywords
         */
        public function get_keywords() {
            return [ 'ultraaddons', 'slider' ];
        }
        
        /**
         * Retrieve the list of scripts the counter widget depended on.
         *
         * Used to set scripts dependencies required to run the widget.
         *
         * @since 1.0.0.13
         * @access public
         *
         * @return array Widget scripts dependencies.
         * @by Saiful
         */
        public function get_script_depends() {
                return [ 'jquery' ];
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
            $this->slider_controls();

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
        
                $this->add_render_attribute( 'wrapper', [
                        'class' => 'ua-slider-wrapper',
                ] );


                //navigation
                if( $settings['navigation_type'] == 'number' ){
                    $this->add_render_attribute( 'wrapper', 'class', 'ua-number-slider-wrapper' );
                }


                ?>
            <div class="ua-slider-main-wrapper">
                <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
                    
                    <?php
                    foreach( $settings['slides'] as $key => $slide ){
                        $repeater_key = $this->get_repeater_setting_key( 'title', 'slides', $key );
                        
                        $_id = !empty( $slide['_id'] ) ? $slide['_id'] : false;
                        $image = isset( $slide['image']['url'] ) && ! empty( $slide['image']['url'] ) ? $slide['image']['url'] : false;
                        $title = !empty( $slide['title'] ) ? $slide['title'] : false;
                        $content = !empty( $slide['content'] ) ? $slide['content'] : false;
                        $button = !empty( $slide['button'] ) ? $slide['button'] : false;
                        
                        
                        $link = false;
                        if ( ! empty( $slide['button_link']['url'] ) ) {
                            $this->add_link_attributes( $repeater_key . '.button', $slide['button_link'] );
                            $this->add_render_attribute( $repeater_key . '.button', 'class', 'slider-button' );
                            $link = true;
                        }
                        
                        $slide_template = $slide['slide_template'];
                        //var_dump($slide_template);
                        if( ! empty( $slide_template ) && is_numeric( $slide_template ) ){
                            (int) $select_post_id = $slide_template;
                            if ( \Elementor\Plugin::instance()->db->is_built_with_elementor( $select_post_id ) ) {
                                echo wp_kses_post( '<div class="ua-slider-item">' );
                                echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $select_post_id );
                                echo wp_kses_post( '</div>' );
                            }
                        }else{
                    ?>
                        <div class="ua-slider-item" 
                             <?php if( $image ){ ?>
                             style="background-image: url(<?php echo esc_url( $image ); ?>);"
                             <?php } ?>
                             >
                            <div class="ultraaddons-slider-container">
                                <div class="row">
                                    <div class="ua-slide-content-wrap">
                                        <div class="ua-hero-text">
                                            <h1><?php echo wp_kses_post( $title ); ?></h1>
                                            <p class="para"><?php echo wp_kses_post( $content ); ?></p>
                                            <div class="hero-btn">
                                                <a <?php echo $this->get_render_attribute_string( $repeater_key . '.button' ); ?>><?php echo esc_html( $button ); ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    <?php
                        }
                        
                    }                
                    ?>
                    
                </div> <!-- end of inside wrapper -->
            </div>
                <?php
        
        }
    
        /**
         * General Section for Content Controls
         * 
         * @since 1.0.0.9
         */
        protected function content_general_controls() {
        
                $this->start_controls_section(
                        'general',
			[
				'label' => __( 'General', 'ultraaddons' ),
			]
                );
                
                
                
        
                $repeater = new Repeater();
                $repeater->add_control(
                            'slide_type',
                            [
                                    'label' => __( 'Slide Type', 'ultraaddons' ),
                                    'type' => Controls_Manager::SELECT,
                                    'options' => [
                                        'template' => __( 'Elementor Template', 'ultraaddons' ),
                                        'default' => __( 'Default', 'ultraaddons' ),
                                    ],
                                    'default' => 'default',
                            ]
                    );
                
                /**
                 * 'condition' => [
                                        'autoplay' => 'yes',
                                ]
                 * 
                 */
                $args = array(
                    'post_type'     =>  'elementor_library',
                    'post_status'   =>  'publish'
                );
                $query = get_posts( $args );
                $template_choices = array();

                //If found post, then itarate
                if( is_array( $query ) && count( $query ) > 0 ){
                    foreach( $query as $q_post ){
                        $id = (int) $q_post->ID;
                        $template_choices[$id] = $q_post->post_title;
                    }
                    
                    
                    $repeater->add_control(
                            'slide_template',
                            [
                                    'label' => __( 'Template as Slide', 'ultraaddons' ),
                                    'type' => Controls_Manager::SELECT,
                                    'options' => $template_choices,
                                    'condition' => [
                                            'slide_type' => 'template',
                                    ]
                            ]
                    );
                }else{
                    $repeater->add_control(
                            'no_slide_template',
                            [
                                    'label' => __( 'Template Not founded', 'ultraaddons' ),
                                    'type' => Controls_Manager::RAW_HTML,
                                    'description' => esc_html__( 'There is no existing Elementor Template. Please create first.' ),
                                    'condition' => [
                                                'slide_type' => 'template',
                                        ]
                            ]
                    );
                }
                
                
                
                
                $repeater->add_control(
                        'image',
                        [
                                'label' => __( 'Background Image', 'ultraaddons' ),
                                'type' => Controls_Manager::MEDIA,
                                'default' => [
                                        'url' => Utils::get_placeholder_image_src(),
                                ],
                                'dynamic' => [
                                        'active' => true,
                                ],
                                'condition' => [
                                            'slide_type' => 'default',
                                ],

                        ]
                );
                $repeater->add_control(
                        'title',
			[
				'label' => __( 'Title', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'Enter your title', 'ultraaddons' ),
				'default' => __( 'Slider Title Text', 'ultraaddons' ),
				'label_block' => true,
                                'condition' => [
                                            'slide_type' => 'default',
                                ],
			]
                );
                
                $repeater->add_control(
                        'content',
			[
				'label' => __( 'Content', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'Enter your content...', 'ultraaddons' ),
				'default' => __( "Lorem Ipsum is simply dumy text of the printing & typesetting industry Lorem Ipsum has been the industry's standard dummy text ever since the 1975, when an unknown printer.", 'ultraaddons' ),
				'label_block' => true,
                                'condition' => [
                                            'slide_type' => 'default',
                                ],
			]
                );
                
                $repeater->add_control(
                        'button',
                        [
                                'label' => __( 'Text', 'ultraaddons' ),
                                'type' => Controls_Manager::TEXT,
                                'dynamic' => [
                                        'active' => true,
                                ],
                                'default' => __( 'Click here', 'ultraaddons' ),
                                'placeholder' => __( 'Click here', 'ultraaddons' ),
                                'condition' => [
                                            'slide_type' => 'default',
                                ],
                        ]
                );

                $repeater->add_control(
                        'button_link',
                        [
                                'label' => __( 'Link', 'ultraaddons' ),
                                'type' => Controls_Manager::URL,
                                'dynamic' => [
                                        'active' => true,
                                ],
                                'placeholder' => __( 'https://your-link.com', 'ultraaddons' ),
                                'default' => [
                                        'url' => '#',
                                ],
                                'condition' => [
                                            'slide_type' => 'default',
                                ],
                        ]
                );
                
//                
//		$repeater->add_group_control(
//			Group_Control_Typography::get_type(),
//			[
//				'name' => 'repeater_typography',
//				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .heading',
//				'global' => [
//					'default' => Global_Typography::TYPOGRAPHY_TEXT,
//				],
//			]
//		);
//                
                $this->add_control(
			'slides',
			[
				'label' => __( 'Slides', 'ultraaddons' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'image' => Utils::get_placeholder_image_src(),
						'title' => __( 'Slide One', 'ultraaddons' ),
						'content' => __( 'This is Sample Content ...', 'ultraaddons' ),
						'button' => __( 'Click Here', 'ultraaddons' ),
						'button_link' => '#',
                                                
					],
                                    
					[
						'image' => Utils::get_placeholder_image_src(),
						'title' => __( 'Slide Two Text', 'ultraaddons' ),
						'content' => __( 'This is Sample Content ...', 'ultraaddons' ),
						'button' => __( 'Click Here', 'ultraaddons' ),
						'button_link' => '#',
                                                
					],
                                    
					[
						'image' => Utils::get_placeholder_image_src(),
						'title' => __( 'Slide Three', 'ultraaddons' ),
						'content' => __( 'This is Sample Content ...', 'ultraaddons' ),
						'button' => __( 'Click Here', 'ultraaddons' ),
						'button_link' => '#',
                                                
					],
                                    
					
				],
				'title_field' => '{{{ title }}}',
			]
		);
                
//                $this->add_control( 
//                        'display_percentage',
//                        [
//                                'label' => __( 'Display Percentage', 'ultraaddons' ),
//                                'type' => Controls_Manager::SWITCHER,
//                                'label_on' => __( 'Show', 'ultraaddons' ),
//				'label_off' => __( 'Hide', 'ultraaddons' ),
//                                'return_value' => 'yes',
//                                'default' => 'yes',
//                        ]
//                );
        
               
        
                $this->end_controls_section();
        }
    
        /**
         * Alignment Section for Style Tab
         * 
         * @since 1.0.0.9
         */
        protected function style_general_controls() {
                
                $this->start_controls_section(
			'section_progress_style',
			[
				'label' => __( 'General', 'ultraaddons' ),
				'tab' => Controls_Manager::TAB_STYLE,
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
                                                'icon' => 'fa fa-align-left',
                                        ],
                                        'center' => [
                                                'title' => __( 'Center', 'ultraaddons' ),
                                                'icon' => 'fa fa-align-center',
                                        ],
                                        'right' => [
                                                'title' => __( 'Right', 'ultraaddons' ),
                                                'icon' => 'fa fa-align-right',
                                        ],
                                ],
                                'default' => 'center',
                                'toggle' => true,
                                'prefix_class' => 'elementor-align-',
                                'default' => 'left',
                        ]
                );
                
                $this->add_control(
			'title_options',
			[
				'label' => __( 'Title', 'ultraaddons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
                
                $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Typography', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} .ua-slider-item .ua-slide-content-wrap .ua-hero-text h1',
			]
		);
                
                $this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
                                'default' => '#021429',
				'selectors' => [
					'{{WRAPPER}} .ua-slider-item .ua-slide-content-wrap .ua-hero-text h1' => 'color: {{VALUE}}',
				],
			]
		);
                
                $this->add_control(
			'subtitle_options',
			[
				'label' => __( 'Sub-Title', 'ultraaddons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
                
                $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sub_title_typography',
				'label' => __( 'Typography', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} .ua-slider-item .ua-slide-content-wrap .ua-hero-text p',
			]
		);
                
                $this->add_control(
			'sub_title_color',
			[
				'label' => __( 'Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
                                'default' => '#5C6B79',
				'selectors' => [
					'{{WRAPPER}} .ua-slider-item .ua-slide-content-wrap .ua-hero-text p' => 'color: {{VALUE}}',
				],
			]
		);
                
                $this->add_control(
			'button_options',
			[
				'label' => __( 'Button', 'ultraaddons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
                
                $this->start_controls_tabs( 'slider_btn' );
                
                $this->start_controls_tab(
                        'tab_button_content_normal',
                        [
                                'label'  => esc_html__( 'Normal', 'ultraaddons' )
                        ]
                );
                
                $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'label' => __( 'Typography', 'ultraaddons' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .ua-slider-wrapper .ua-slider-item .slider-button',
			]
		);
                
                $this->add_control(
			'button_color',
			[
				'label' => __( 'Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
                                'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .ua-slider-wrapper .ua-slider-item .slider-button' => 'color: {{VALUE}}',
				],
			]
		);
                
                $this->add_control(
			'button_bg_color',
			[
				'label' => __( 'Background Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
                                'default' => '#0FC392',
				'selectors' => [
					'{{WRAPPER}} .ua-slider-wrapper .ua-slider-item .slider-button' => 'background-color: {{VALUE}}',
				],
			]
		);
                
                $this->end_controls_tab();
                
                $this->start_controls_tab(
                        'tab_button_content_hover',
                        [
                                'label'  => esc_html__( 'Hover', 'ultraaddons' )
                        ]
                );
                
                $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_hover_typography',
				'label' => __( 'Typography', 'ultraaddons' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .ua-slider-wrapper .ua-slider-item .slider-button:hover',
			]
		);
                
                $this->add_control(
			'button_hover_color',
			[
				'label' => __( 'Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#0FC392',
				'selectors' => [
					'{{WRAPPER}} .ua-slider-wrapper .ua-slider-item .slider-button:hover' => 'color: {{VALUE}}',
				],
			]
		);
                
                $this->add_control(
			'button_hover_bg_color',
			[
				'label' => __( 'Background Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'transparent',
				'selectors' => [
					'{{WRAPPER}} .ua-slider-wrapper .ua-slider-item .slider-button:hover' => 'background-color: {{VALUE}}',
				],
			]
		);
                
                $this->end_controls_tab();
                
                
                $this->end_controls_tabs();
                
                
                
       
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
                                        'none'      => __( 'None', 'ultraaddons' ),
                                        'arrow'     => __( 'Arrow', 'ultraaddons' ),
                                        'dots'      => __( 'Dots/Number', 'ultraaddons' ),
                                        'both'      => __( 'Arrow & Dots/Number', 'ultraaddons' ),
                                ],
                                'default' => 'dots',
                                'frontend_available' => true,
                                'style_transfer' => true,
                        ]
                );
                $this->add_control(
                        'navigation_type',
                        [
                                'label' => __( 'Navigation Type', 'ultraaddons' ),
                                'type' => Controls_Manager::SELECT,
                                'options' => [
                                        'dots'      => __( 'Dots', 'ultraaddons' ),
                                        'number'    => __( 'Number', 'ultraaddons' ),
                                ],
                                'condition' => [
                                        'navigation' => ['dots', 'both'],
                                ],
                                'default' => 'number',
                        ]
                );
                
                $this->add_control(
                        'navigation_arrow_position',
                        [
                                'label' => __( 'Arrow Position', 'ultraaddons' ),
                                'type' => Controls_Manager::SELECT,
                                'options' => [
                                        'top'      => __( 'Top', 'ultraaddons' ),
                                        'center'    => __( 'Center', 'ultraaddons' ),
                                        'bottom'    => __( 'Bottom', 'ultraaddons' ),
                                ],
                                'condition' => [
                                        'navigation' => ['arrow', 'both'],
                                ],
                                'default' => 'center',
                                'prefix_class' => 'navigation-arrow-position-',
                        ]
                );

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
                                'desktop_default' => 1,
                                'tablet_default' => 1,
                                'mobile_default' => 1,
                                'frontend_available' => true,
                                'style_transfer' => true,
                        ]
                );

                
//                $animation_options = [
//                    '' => __( 'None', 'ultraaddons' ),
//                    'animate__bounce' => __( 'animate__bounce', 'ultraaddons' ),
//                    'animate__flash' => __( 'animate__flash', 'ultraaddons' ),
//                    'animate__pulse' => __( 'animate__pulse', 'ultraaddons' ),
//                    'animate__rubberBand' => __( 'animate__rubberBand', 'ultraaddons' ),
//                    'animate__shakeX' => __( 'animate__shakeX', 'ultraaddons' ),
//                    'animate__shakeY' => __( 'animate__shakeY', 'ultraaddons' ),
//                    'animate__lightSpeedInRight' => __( 'animate__lightSpeedInRight', 'ultraaddons' ),
//                ];
//                
//                $this->add_control(
//                        'animateIn',
//                        [
//                                'label' => __( 'External In Animation', 'ultraaddons' ),
////                                'description' => esc_html__( 'Not mandatory, But you can apply. Generate from Animate.style', 'ultraaddons' ),
//                                'type' => Controls_Manager::SELECT,
//                                'options' => $animation_options,
//                                'default' => '',
//                                'frontend_available' => true,
////                                'style_transfer' => true,
//                        ]
//                );
//                
                $this->end_controls_section();
        }
        
        
    
}