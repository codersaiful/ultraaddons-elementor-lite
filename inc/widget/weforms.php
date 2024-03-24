<?php
namespace UltraAddons\Widget;


use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WeForms extends Base{
       
        public function get_keywords() {
                return [ 'ultraaddons','ua', 'appointment', 'contact', 'quote', 'form', 'schedule','we', 'weforms', 'contact form' ];
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
                $this->register_content_controls();
                if( class_exists( 'WeForms' ) ){
                        $this->general_style();
                        $this->form_label_style();
                        $this->input_style();
                        $this->button_style();
                        $this->container_style();
                }
        }

        protected function register_content_controls(){
                
                $this->start_controls_section(
                        '_section_frm',
                        [
                                'label' =>  __( 'Contact Form', 'ultraaddons' ) ,
                                'tab' => Controls_Manager::TAB_CONTENT,
                        ]
                );
                if( class_exists( 'WeForms' ) ){
                        $this->add_control(
                                'form_id',
                                array(
                                'label'   => __( 'Form', 'ultraaddons' ),
                                'type'    => Controls_Manager::SELECT2,
                                'options' => ultraaddons_get_weform_list(),
                                'default' =>0
                                )
                        );
                }else{
                        $this->add_control(
                                'form_error',[
                                    'type'            => Controls_Manager::RAW_HTML,
                                    'raw'             => sprintf( __( '<strong>Please install or activate WPForms.</strong><br>Go to the <a href="%s" target="_blank" style="color:#93003c">Plugin page</a> to actions.', 'ultraaddons' ), admin_url( 'plugin-install.php?s=WeForms&tab=search&type=term' ) ),
                                    'separator'       => 'after',
                                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                                ]
                            );
                }

                $this->end_controls_section();
        }

        protected function general_style(){
                
                $this->start_controls_section(
                        'from_style',
                        [
                                'label' =>  __( 'Title & Description', 'ultraaddons' ) ,
                                'tab' => Controls_Manager::TAB_STYLE,
                        ]
                );
           
                    
                $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                                'name' => 'title_typography',
                                'label' => 'Title Typography',
                                'selector' => '{{WRAPPER}} .ua-form .ua-wp-form-title',
                                'global' => [
                                        'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                                ],
        
                        ]
                );
                $this->add_control(
                        'title_color',
                        [
                                'label' => __( 'Title Color', 'ultraaddons' ),
                                'type' => Controls_Manager::COLOR,
                                'selectors' => [
                                        '{{WRAPPER}} .ua-form .ua-wp-form-title' => 'color: {{VALUE}};',
                                ],
                        ]
                );
                $this->add_responsive_control(
                    'title_margin',
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
                            '{{WRAPPER}} .ua-form .ua-wp-form-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                                        'separator'=>'after',
                    ]
                );
                $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                                'name' => 'desc_typography',
                                'label' => 'Description Typography',
                                'selector' => '{{WRAPPER}} .ua-form .frm_description p',
                                'global' => [
                                        'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                                ],
        
                        ]
                );

                $this->add_control(
                        'desc_color',
                        [
                                'label' => __( 'Deccription Color', 'ultraaddons' ),
                                'type' => Controls_Manager::COLOR,
                                'selectors' => [
                                        '{{WRAPPER}} .ua-form .frm_description p' => 'color: {{VALUE}};',
                                ],
                        ]
                );
                $this->add_responsive_control(
			'desc_margin',
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
					'{{WRAPPER}} .ua-form .frm_description p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                    'separator'=>'after',
			]
		);

                $this->end_controls_section();
        }
        protected function form_label_style(){
                $this->start_controls_section(
                        'ua_form_label_styles',
                        [
                            'label' => esc_html__('Form Label', 'ultraaddons'),
                            'tab' => Controls_Manager::TAB_STYLE,
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                                'name' => 'label_typography',
                                'label' => 'Label Typography',
                                'global' => [
                                        'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                                ],
                                'selector' => '{{WRAPPER}} .ua-weforms .wpuf-label label',
        
                        ]
                );
                $this->add_control(
                        'label_color',
                        [
                                'label' => __( 'Form Label Color', 'ultraaddons' ),
                                'type' => Controls_Manager::COLOR,
                                'default' => '#333',
                                'selectors' => [
                                        '{{WRAPPER}} .ua-weforms .wpuf-label label' => 'color: {{VALUE}};',
                                ],
                        ]
                );
                $this->add_responsive_control(
                    'label_margin',
                    [
                        'label'       => esc_html__( 'Label Margin', 'ultraaddons' ),
                        'type'        => Controls_Manager::DIMENSIONS,
                        'size_units'  => [ 'px', '%' ],
                        'placeholder' => [
                            'top'    => '',
                            'right'  => '',
                            'bottom' => '',
                            'left'   => '',
                        ],
                        'selectors'   => [
                            '{{WRAPPER}} .ua-weforms .wpuf-label label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                                        'separator'=>'after',
                    ]
                );
                $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                                'name' => 'sub_label_typography',
                                'label' => 'Sub Label Typography',
                                'global' => [
                                        'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                                ],
                                'selector' => '{{WRAPPER}} .ua-weforms .wpuf-form-sub-label',
                        ]
                );
                $this->add_control(
                        'sub_label_color',
                        [
                                'label' => __( 'Sub Label Color', 'ultraaddons' ),
                                'type' => Controls_Manager::COLOR,
                                'default' => '#333',
                                'separator'=>'after',
                                'selectors' => [
                                        '{{WRAPPER}} .ua-weforms .wpuf-form-sub-label' => 'color: {{VALUE}};',
                                       
                                ],
                        ]
                );
                $this->end_controls_section();
        }
        protected function container_style(){
                $this->start_controls_section(
                        'us_section_weform_styles',
                        [
                            'label' => esc_html__('Form Container', 'ultraaddons'),
                            'tab' => Controls_Manager::TAB_STYLE,
                        ]
                    );
            
                    $this->add_control(
                        'us_weform_background',
                        [
                            'label' => esc_html__('Form Background Color', 'ultraaddons'),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ua-weforms' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );
            
            
                    $this->add_responsive_control(
                        'us_weform_width',
                        [
                            'label' => esc_html__('Form Width', 'ultraaddons'),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => ['px', 'em', '%'],
                            'range' => [
                                'px' => [
                                    'min' => 10,
                                    'max' => 1500,
                                ],
                                'em' => [
                                    'min' => 1,
                                    'max' => 80,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .ua-weforms' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );
            
                    $this->add_responsive_control(
                        'us_weform_max_width',
                        [
                            'label' => esc_html__('Form Max Width', 'ultraaddons'),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => ['px', 'em', '%'],
                            'range' => [
                                'px' => [
                                    'min' => 10,
                                    'max' => 1500,
                                ],
                                'em' => [
                                    'min' => 1,
                                    'max' => 80,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .ua-weforms' => 'max-width: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );
            
                    $this->add_responsive_control(
                        'us_weform_margin',
                        [
                            'label' => esc_html__('Form Margin', 'ultraaddons'),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => ['px', 'em', '%'],
                            'selectors' => [
                                '{{WRAPPER}} .ua-weforms' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
            
                    $this->add_responsive_control(
                        'us_weform_padding',
                        [
                            'label' => esc_html__('Form Padding', 'ultraaddons'),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => ['px', 'em', '%'],
                            'selectors' => [
                                '{{WRAPPER}} .ua-weforms' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
            
                    $this->add_control(
                        'us_weform_border_radius',
                        [
                            'label' => esc_html__('Border Radius', 'ultraaddons'),
                            'type' => Controls_Manager::DIMENSIONS,
                            'separator' => 'before',
                            'size_units' => ['px'],
                            'selectors' => [
                                '{{WRAPPER}} .ua-weforms' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
            
                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'us_weform_border',
                            'selector' => '{{WRAPPER}} .ua-weforms',
                        ]
                    );
            
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'us_weform_box_shadow',
                            'selector' => '{{WRAPPER}} .ua-weforms',
                        ]
                    );
            
                    $this->end_controls_section();
        }
        protected function input_style(){
                $this->start_controls_section(
                        'input_style',
                        [
                                'label' =>  __( 'Input & Textarea', 'ultraaddons' ) ,
                                'tab' => Controls_Manager::TAB_STYLE,
                        ]
                );
                $this->add_control(
			'input_radius',
			[
				'label' => __( 'Border Radius', 'ultraaddons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
                        '{{WRAPPER}} 
                        .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="text"],
                        .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="email"], 
                        .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="number"], 
                        .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="range"], 
                        .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="password"],
                        .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="search"], 
                        .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="tel"], 
                        .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="url"],
                        .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="time"], 
                        .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="week"], 
                        .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="datetime"], 
                        .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="date"],  
                        .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
                $this->add_control(
			'input_bg_color',
			[
                        'label' => __( 'Background Color', 'ultraaddons' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '',
                        'selectors' => [
                                '{{WRAPPER}}
                                .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="text"],
                                .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="email"], 
                                .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="number"], 
                                .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="range"], 
                                .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="password"],
                                .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="search"], 
                                .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="tel"], 
                                .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="url"],
                                .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="time"], 
                                .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="week"], 
                                .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="datetime"], 
                                .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="date"],  
                                .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields textarea' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
                        [
                        'name' => 'input_typography',
                        'selector' => '{{WRAPPER}} 
                        .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="text"],
                        .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type=email], 
                        .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="number"], 
                        .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="range"], 
                        .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="password"],
                        .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="search"], 
                        .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="tel"], 
                        .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="url"],
                        .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="time"], 
                        .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="week"], 
                        .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="datetime"], 
                        .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields input[type="date"],  
                        .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form .wpuf-fields textarea',
			]
		);
		$this->add_control(
			'text_height',
			[
				'label' => esc_html__( 'Input Text Height', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 100,
						'step' => 5,
					],
				],
				'default' => [
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} 
                    .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form input[type="text"],
                    .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form input[type=email], 
                    .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form input[type="number"], 
                    .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form input[type="range"], 
                    .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form input[type="password"],
                    .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form input[type="search"], 
                    .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form input[type="tel"], 
                    .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form input[type="url"],
                    .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form input[type="time"], 
                    .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form input[type="week"], 
                    .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form input[type="datetime"], 
                    .ua-weforms .wpuf-form-add.wpuf-style ul.wpuf-form input[type="date"]' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'textarea_height',
			[
				'label' => esc_html__( 'Textarea Height', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 150,
						'max' => 500,
						'step' => 10,
					],
				],
				'default' => [
					'size' => 150,
				],
				'selectors' => [
					'{{WRAPPER}} .ua-weforms textarea' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
                $this->end_controls_section();
        }

        protected function button_style(){
                
                $this->start_controls_section(
                        'ua_section_weform_submit_button_styles',
                        [
                            'label' => esc_html__('Submit Button', 'ultraaddons'),
                            'tab' => Controls_Manager::TAB_STYLE,
                        ]
                    );
                    $this->add_responsive_control(
                        'ua_weform_submit_btn_alignment',
                        [
                            'label' => esc_html__('Button Alignment', 'ultraaddons'),
                            'type' => Controls_Manager::CHOOSE,
                            'label_block' => true,
                            'options' => [
                                'default' => [
                                    'title' => __('Default', 'ultraaddons'),
                                    'icon' => ' eicon-ban',
                                ],
                                'left' => [
                                    'title' => esc_html__('Left', 'ultraaddons'),
                                    'icon' => 'eicon-text-align-left',
                                ],
                                'center' => [
                                    'title' => esc_html__('Center', 'ultraaddons'),
                                    'icon' => 'eicon-text-align-center',
                                ],
                                'right' => [
                                    'title' => esc_html__('Right', 'ultraaddons'),
                                    'icon' => 'eicon-text-align-right',
                                ],
                            ],
                            'default' => 'left',
                            'selectors' => [
                                '{{WRAPPER}} .wpuf-submit' => 'text-align: {{VALUE}};',
                            ],

                        ]
                    );
                    $this->add_responsive_control(
                        'ua_weform_submit_btn_width',
                        [
                            'label' => esc_html__('Button Width', 'ultraaddons'),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => ['px', 'em', '%'],
                            'range' => [
                                'px' => [
                                    'min' => 10,
                                    'max' => 1500,
                                ],
                                'em' => [
                                    'min' => 1,
                                    'max' => 80,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .ua-weforms ul.wpuf-form .wpuf-submit input[type="submit"]' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );
                    
                
                    
                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'ua_weform_submit_btn_typography',
                            'global' => [
                                'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                            ],
                            'selector' => '{{WRAPPER}} .ua-weforms ul.wpuf-form .wpuf-submit input[type="submit"]',
                        ]
                    );
                    
                    $this->add_responsive_control(
                        'ua_weform_submit_btn_margin',
                        [
                            'label' => esc_html__('Margin', 'ultraaddons'),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => ['px', 'em', '%'],
                            'selectors' => [
                                '{{WRAPPER}} .ua-weforms ul.wpuf-form .wpuf-submit input[type="submit"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
                    
                    $this->add_responsive_control(
                        'ua_weform_submit_btn_padding',
                        [
                            'label' => esc_html__('Padding', 'ultraaddons'),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => ['px', 'em', '%'],
                            'selectors' => [
                                '{{WRAPPER}} .ua-weforms ul.wpuf-form .wpuf-submit input[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
                    
                    $this->start_controls_tabs('ua_weform_submit_button_tabs');
                    
                    $this->start_controls_tab('normal', ['label' => esc_html__('Normal', 'ultraaddons')]);
                    
                    $this->add_control(
                        'ua_weform_submit_btn_text_color',
                        [
                            'label' => esc_html__('Text Color', 'ultraaddons'),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ua-weforms ul.wpuf-form .wpuf-submit input[type="submit"]' => 'color: {{VALUE}};',
                            ],
                        ]
                    );
                    
                    $this->add_control(
                        'ua_weform_submit_btn_background_color',
                        [
                            'label' => esc_html__('Background Color', 'ultraaddons'),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ua-weforms ul.wpuf-form .wpuf-submit input[type="submit"]' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );
                    
                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'ua_weform_submit_btn_border',
                            'selector' => '{{WRAPPER}} .ua-weforms ul.wpuf-form .wpuf-submit input[type="submit"]',
                        ]
                    );
                    
                    $this->add_control(
                        'ua_weform_submit_btn_border_radius',
                        [
                            'label' => esc_html__('Border Radius', 'ultraaddons'),
                            'type' => Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'max' => 100,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .ua-weforms ul.wpuf-form .wpuf-submit input[type="submit"]' => 'border-radius: {{SIZE}}px;',
                            ],
                        ]
                    );
                    
                    $this->end_controls_tab();
                    
                    $this->start_controls_tab('ua_weform_submit_btn_hover', ['label' => esc_html__('Hover', 'ultraaddons')]);
                    
                    $this->add_control(
                        'ua_weform_submit_btn_hover_text_color',
                        [
                            'label' => esc_html__('Text Color', 'ultraaddons'),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ua-weforms ul.wpuf-form .wpuf-submit input[type="submit"]:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );
                    
                    $this->add_control(
                        'ua_weform_submit_btn_hover_background_color',
                        [
                            'label' => esc_html__('Background Color', 'ultraaddons'),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ua-weforms ul.wpuf-form .wpuf-submit input[type="submit"]:hover' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );
                    
                    $this->add_control(
                        'ua_weform_submit_btn_hover_border_color',
                        [
                            'label' => esc_html__('Border Color', 'ultraaddons'),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ua-weforms ul.wpuf-form .wpuf-submit input[type="submit"]:hover' => 'border-color: {{VALUE}};',
                            ],
                        ]
                    );
                    
                    $this->end_controls_tab();
                    
                    $this->end_controls_tabs();
                    
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'ua_weform_submit_btn_box_shadow',
                            'selector' => '{{WRAPPER}} .ua-weforms ul.wpuf-form .wpuf-submit input[type="submit"]',
                        ]
                    );
                    
                    $this->end_controls_section();
        }

       

        /**
         * Render widget output on the frontend.
         *
         * Written in PHP and used to generate the final HTML.
         *
         * @since 1.0.0
         * @access protected
         */
        protected function render() {
            if (!function_exists('WeForms')) {
                return;
            }
            $settings    = $this->get_settings_for_display();
            $form_id     = isset( $settings['form_id'] ) ? absint( $settings['form_id'] ) : 0;

            
        $this->add_render_attribute(
                'ua_weforms_class',
                ['class' => 'ua-weforms']
        );
        ?>
        <?php 
        if (!empty($form_id)):
        ?>
        <div <?php echo $this->get_render_attribute_string( 'ua_weforms_class' );?>>
                <?php
                echo do_shortcode(
                        '[weforms id="'. $form_id .'" ]'
                );
                ?>
        </div>
        <?php 
        else:
         echo "<div class='ua-alert'>" . esc_html__( "Please select weForms.", 'ultraaddons' ) . "</div>";
        endif;
        }
}
