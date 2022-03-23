<?php
namespace UltraAddons\Widget;


use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WPForms extends Base{
       
        public function get_keywords() {
                return [ 'ultraaddons', 'ua', 'appointment', 'contact', 'quote', 'form', 'schedule', 'formidable', 'contact form', ];
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
                if( class_exists( 'WPForms\WPForms' ) ){
                        $this->general_style();
                        $this->label_style();
                        $this->input_style();
                        $this->placeholder_style();
                        $this->button_style();
                        $this->error_style();
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
                if( class_exists( 'WPForms\WPForms' ) ){
                        $this->add_control(
                                'form_id',
                                array(
                                'label'   => __( 'Form', 'ultraaddons' ),
                                'type'    => Controls_Manager::SELECT2,
                                'options' => ultraaddons_get_wpform_list(),
                                'default' =>0
                                )
                        );
                        $this->add_control(
                                'title',
                                [
                                        'label' => esc_html__( 'Show Form Title', 'ultraaddons' ),
                                        'type' => Controls_Manager::SWITCHER,
                                        'label_on' => esc_html__( 'Show', 'ultraaddons' ),
                                        'label_off' => esc_html__( 'Hide', 'ultraaddons' ),
                                        'return_value' => 'yes',
                                        'default' => 'yes',
                                ]
                        );
                       
                        $this->add_control(
                            'title_tag',
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
                                    'div'=>	'div',
                                ],
                                'default' => 'h2',
                                'condition' => [
                                    'title' => 'yes'
                                ],
                            ]
                        );
                        $this->add_control(
                                'description',
                                [
                                        'label' => esc_html__( 'Show Form Description', 'ultraaddons' ),
                                        'type' => Controls_Manager::SWITCHER,
                                        'label_on' => esc_html__( 'Show', 'ultraaddons' ),
                                        'label_off' => esc_html__( 'Hide', 'ultraaddons' ),
                                        'return_value' => 'yes',
                                        'default' => 'yes',
                                ]
                        );
                }else{
                        $this->add_control(
                                'form_error',[
                                    'type'            => Controls_Manager::RAW_HTML,
                                    'raw'             => sprintf( __( '<strong>Please install or activate WPForms.</strong><br>Go to the <a href="%s" target="_blank" style="color:#93003c">Plugin page</a> to actions.', 'ultraaddons' ), admin_url( 'plugin-install.php?s=wpforms&tab=search&type=term' ) ),
                                    'separator'       => 'after',
                                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                                ]
                            );
                }

                $this->end_controls_section();

                $this->start_controls_section(
                        'section_errors',
                        [
                            'label'                 => __('Errors', 'ultraaddons'),
                        ]
                    );
        
                    $this->add_control(
                        'error_messages',
                        [
                            'label'                 => __('Error Messages', 'ultraaddons'),
                            'type'                  => Controls_Manager::SELECT,
                            'default'               => 'show',
                            'options'               => [
                                'show'          => __('Show', 'ultraaddons'),
                                'hide'          => __('Hide', 'ultraaddons'),
                            ],
                            'selectors_dictionary'  => [
                                'show'          => 'block',
                                'hide'          => 'none',
                            ],
                            'selectors'             => [
                                '{{WRAPPER}} .ua-form.wpform label.wpforms-error' => 'display: {{VALUE}} !important;',
                            ],
                        ]
                    );
        
                    $this->end_controls_section();
        }

        protected function label_style(){
                
                $this->start_controls_section(
                        'label_style',
                        [
                                'label' =>  __( 'Label & Sub Label', 'ultraaddons' ) ,
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
                                'selector' => '{{WRAPPER}} .ua-form .wpforms-field-label',
        
                        ]
                );
                $this->add_control(
                        'label_color',
                        [
                                'label' => __( 'Form Label Color', 'ultraaddons' ),
                                'type' => Controls_Manager::COLOR,
                                'selectors' => [
                                        '{{WRAPPER}} .ua-form .wpforms-field-label' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .ua-form .wpforms-field-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                'selector' => '{{WRAPPER}} .ua-form .wpforms-field-sublabel',
                        ]
                );
                $this->add_control(
                        'sub_label_color',
                        [
                                'label' => __( 'Sub Label Color', 'ultraaddons' ),
                                'type' => Controls_Manager::COLOR,
                                'separator'=>'after',
                                'selectors' => [
                                        '{{WRAPPER}} .ua-form .wpforms-field-sublabel' => 'color: {{VALUE}};',
                                       
                                ],
                        ]
                );
                $this->end_controls_section();
        }
        protected function general_style(){
                $this->start_controls_section(
                        'from_style',
                        [
                                'label' =>  __( 'Title &  Description', 'ultraaddons' ) ,
                                'tab' => Controls_Manager::TAB_STYLE,
                        ]
                );
                $this->add_control(
                        'align',
                            [
                                'label'         => esc_html__( 'Align', 'ultraaddons' ),
                                'type'          => Controls_Manager::CHOOSE,
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
                                'default' => 'left',
                                'selectors' => [
                                        '{{WRAPPER}} .ua-form .ua-wp-form-title, .ua-wpform-description' => 'text-align:{{VALUE}};',
                                ],
                               
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
                                'selector' => '{{WRAPPER}} .ua-form .ua-wpform-description',
        
                        ]
                );

                $this->add_control(
                        'desc_color',
                        [
                                'label' => __( 'Deccription Color', 'ultraaddons' ),
                                'type' => Controls_Manager::COLOR,
                                'default' => '#333',
                                'selectors' => [
                                        '{{WRAPPER}} .ua-form .ua-wpform-description' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .ua-form .ua-wpform-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                                'separator'=>'after',
			]
		);
               
                $this->end_controls_section();
        }
        protected function input_style(){
           foreach( ultraaddons_get_wpform_list() as $key=>$val){
                $key = $key;
            }
           
                
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
                                        #wpforms-form-'. $key .' input[type="text"], 
                                        #wpforms-form-'. $key .' input[type=email], 
                                        #wpforms-form-'. $key .' input[type="number"], 
                                        #wpforms-form-'. $key .' input[type="range"], 
                                        #wpforms-form-'. $key .' input[type="password"],
                                        #wpforms-form-'. $key .' input[type="search"], 
                                        #wpforms-form-'. $key .' input[type="tel"], 
                                        #wpforms-form-'. $key .' input[type="url"],
                                        #wpforms-form-'. $key .' input[type="time"], 
                                        #wpforms-form-'. $key .' input[type="week"], 
                                        #wpforms-form-'. $key .' input[type="datetime"], 
                                        #wpforms-form-'. $key .' input[type="date"], 
                                        #wpforms-form-'. $key .' select,
                                        #wpforms-form-'. $key .' .choices__inner,
                                        #wpforms-form-'. $key .' textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} #wpforms-form-'. $key .' input[type="text"], 
                                        #wpforms-form-'. $key .' input[type=email], 
                                        #wpforms-form-'. $key .' input[type="number"], 
                                        #wpforms-form-'. $key .' input[type="range"], 
                                        #wpforms-form-'. $key .' input[type="password"],
                                        #wpforms-form-'. $key .' input[type="search"], 
                                        #wpforms-form-'. $key .' input[type="tel"], 
                                        #wpforms-form-'. $key .' input[type="url"],
                                        #wpforms-form-'. $key .' input[type="time"], 
                                        #wpforms-form-'. $key .' input[type="week"], 
                                        #wpforms-form-'. $key .' input[type="datetime"], 
                                        #wpforms-form-'. $key .' input[type="date"],
                                        #wpforms-form-'. $key .' select,
                                        #wpforms-form-'. $key .' .choices__inner,
                                        #wpforms-form-'. $key .' textarea' => 'background-color: {{VALUE}};',
				],
			]
		);
                $this->add_control(
			'input_text_color',
			[
				'label' => __( 'Text Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} #wpforms-form-'. $key .' input[type="text"], 
                                        #wpforms-form-'. $key .' input[type=email], 
                                        #wpforms-form-'. $key .' input[type="number"], 
                                        #wpforms-form-'. $key .' input[type="range"], 
                                        #wpforms-form-'. $key .' input[type="password"],
                                        #wpforms-form-'. $key .' input[type="search"], 
                                        #wpforms-form-'. $key .' input[type="tel"], 
                                        #wpforms-form-'. $key .' input[type="url"],
                                        #wpforms-form-'. $key .' input[type="time"], 
                                        #wpforms-form-'. $key .' input[type="week"], 
                                        #wpforms-form-'. $key .' input[type="datetime"], 
                                        #wpforms-form-'. $key .' input[type="date"],
                                        #wpforms-form-'. $key .' select,
                                        #wpforms-form-'. $key .' .choices__inner,
                                        #wpforms-form-'. $key .' textarea' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'input_typography',
				'selector' => '{{WRAPPER}} 
                                #wpforms-form-'. $key .' input[type="text"], 
                                #wpforms-form-'. $key .' input[type=email], 
                                #wpforms-form-'. $key .' input[type="number"], 
                                #wpforms-form-'. $key .' input[type="range"], 
                                #wpforms-form-'. $key .' input[type="password"],
                                #wpforms-form-'. $key .' input[type="search"], 
                                #wpforms-form-'. $key .' input[type="tel"], 
                                #wpforms-form-'. $key .' input[type="url"],
                                #wpforms-form-'. $key .' input[type="time"], 
                                #wpforms-form-'. $key .' input[type="week"], 
                                #wpforms-form-'. $key .' input[type="datetime"], 
                                #wpforms-form-'. $key .' input[type="date"],  
                                #wpforms-form-'. $key .' textarea,
                                #wpforms-form-'. $key .' .choices__inner,
                                #wpforms-form-'. $key .' select',
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
					'{{WRAPPER}} #wpforms-form-'. $key .' input[type="text"],
                                        #wpforms-form-'. $key .' input[type=email], 
                                        #wpforms-form-'. $key .' input[type="number"], 
                                        #wpforms-form-'. $key .' input[type="range"], 
                                        #wpforms-form-'. $key .' input[type="password"],
                                        #wpforms-form-'. $key .' input[type="search"], 
                                        #wpforms-form-'. $key .' input[type="tel"], 
                                        #wpforms-form-'. $key .' input[type="url"],
                                        #wpforms-form-'. $key .' input[type="time"], 
                                        #wpforms-form-'. $key .' input[type="week"], 
                                        #wpforms-form-'. $key .' input[type="datetime"], 
                                        #wpforms-form-'. $key .' input[type="date"], 
                                        #wpforms-form-'. $key .' select' => 'height: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} #wpforms-form-'. $key .' textarea' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
                $this->end_controls_section();
        }

        protected function button_style(){
                
                $this->start_controls_section(
                        'button_style',
                        [
                                'label' =>  __( 'Submit Button', 'ultraaddons' ) ,
                                'tab' => Controls_Manager::TAB_STYLE,
                        ]
                );

                $this->start_controls_tabs(
			'style_tabs'
		);
                //Normal Tab
                $this->start_controls_tab(
                        'btn_normal_tab',
                        [
                                'label' => esc_html__( 'Normal', 'ultraaddons' ),
                        ]
                );
                $this->add_control(
                        '_btn_bg_color', [
                                'label' => __( 'Button Background', 'ultraaddons' ),
                                'type'      => Controls_Manager::COLOR,
                                'selectors' => [
                                        '{{WRAPPER}} .ua-form .wpforms-submit' => 'background-color: {{VALUE}};',
                                ],
                        ]
                );
                $this->add_control(
                        '_btn_text_color', [
                                'label' => __( 'Button Text Color', 'ultraaddons' ),
                                'type'      => Controls_Manager::COLOR,
                                'selectors' => [
                                                '{{WRAPPER}} .ua-form .wpforms-submit' => 'color: {{VALUE}};',
                                ],
                        ]
                );
        
                $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                                        'name' => 'btn_typography',
                                        'label' => 'Button Typography',
                                        'selector' => '{{WRAPPER}} .ua-form .wpforms-submit',

                        ]
                );
                $this->add_responsive_control(
                        '_btn_padding',
                        [
                                'label'       => esc_html__( 'Button Padding', 'ultraaddons' ),
                                'type'        => Controls_Manager::DIMENSIONS,
                                'size_units'  => [ '%', 'px' ],
                                'placeholder' => [
                                        'top'    => '',
                                        'right'  => '',
                                        'bottom' => '',
                                        'left'   => '',
                                ],
                                'selectors'   => [
                                        '{{WRAPPER}} .ua-form .wpforms-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                        ]
                        );
                $this->add_responsive_control(
                        '_btn_radius',
                        [
                                'label'       => esc_html__( 'Button Radius', 'ultraaddons' ),
                                'type'        => Controls_Manager::DIMENSIONS,
                                'size_units'  => [ '%', 'px' ],
                                'placeholder' => [
                                        'top'    => '',
                                        'right'  => '',
                                        'bottom' => '',
                                        'left'   => '',
                                ],
                                'separator' =>'after',
                                'selectors'   => [
                                        '{{WRAPPER}} .ua-form .wpforms-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                        ]
                        );

                $this->end_controls_tab();

                //Hover Tab
                $this->start_controls_tab(
                        'btn_hover_tab',
                        [
                                'label' => esc_html__( 'Hover', 'ultraaddonse' ),
                        ]
                );
                $this->add_control(
                        '_btn_bg_hover_bg', [
                                'label' => __( 'Hover Background', 'ultraaddons' ),
                                'type'      => Controls_Manager::COLOR,
                                'selectors' => [
                                        '{{WRAPPER}} .ua-form .wpforms-submit:hover' => 'background: {{VALUE}};',
                                ],
                        ]
                );
                $this->add_control(
                        '_btn_text_hover_color', [
                                'label' => __( 'Button Text Color', 'ultraaddons' ),
                                'type'      => Controls_Manager::COLOR,
                                'selectors' => [
                                        '{{WRAPPER}} .ua-form .wpforms-submit:hover' => 'color: {{VALUE}};',
                                ],
                                'default' =>'#333'
                        ]
                );
                $this->end_controls_tab();
                $this->end_controls_tabs();

                $this->end_controls_section();
        }
        //Error Style Tabs
        protected function error_style(){
                $this->start_controls_section(
                        'error_style_section',
                        [
                                'label' =>  __( 'Errors', 'ultraaddons' ) ,
                                'tab' => Controls_Manager::TAB_STYLE,
                                'condition'             => [
                                        'error_messages' => 'show',
                                ],
                        ]
                );
    
            $this->add_control(
                'error_message_text_color',
                [
                    'label'                 => __('Text Color', 'ultraaddons'),
                    'type'                  => Controls_Manager::COLOR,
                    'default'               => '',
                    'selectors'             => [
                        '{{WRAPPER}} .ua-form.wpform label.wpforms-error' => 'color: {{VALUE}}',
                    ],
                    'condition'             => [
                        'error_messages' => 'show',
                    ],
                ]
            );
    
            $this->add_control(
                'error_field_input_border_color',
                [
                    'label'                 => __('Error Field Input Border Color', 'ultraaddons'),
                    'type'                  => Controls_Manager::COLOR,
                    'default'               => '',
                    'selectors'             => [
                        '{{WRAPPER}} .ua-form.wpform input.wpforms-error, {{WRAPPER}} .ua-wpforms textarea.wpforms-error' => 'border-color: {{VALUE}}',
                    ],
                    'condition'             => [
                        'error_messages' => 'show',
                    ],
                ]
            );
    
            $this->add_control(
                'error_field_input_border_width',
                [
                    'label'                 => __('Error Field Input Border Width', 'ultraaddons'),
                    'type'                  => Controls_Manager::NUMBER,
                    'default'               => 1,
                    'min'                   => 1,
                    'max'                   => 10,
                    'step'                  => 1,
                    'selectors'             => [
                        '{{WRAPPER}} .ua-form.wpform input.wpforms-error, {{WRAPPER}} .ua-wpforms textarea.wpforms-error' => 'border-width: {{VALUE}}px',
                    ],
                    'condition'             => [
                        'error_messages' => 'show',
                    ],
                ]
            );
    
         $this->end_controls_section();
        }

        //Container Style Tabs
        protected function container_style(){

                $this->start_controls_section(
                        'container_style',
                        [
                            'label'                 => __('Form Container', 'ultraaddons'),
                            'tab'                   => Controls_Manager::TAB_STYLE,
                        ]
                    );
                
                    $this->add_control(
                        'ua_contact_form_background',
                        [
                            'label' => esc_html__('Form Background Color', 'ultraaddons'),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ua-form.wpform' => 'background: {{VALUE}};',
                            ],
                        ]
                    );
                
                    $this->add_responsive_control(
                        'ua_contact_form_max_width',
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
                                '{{WRAPPER}} .ua-form.wpform' => 'max-width: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );
                
                
                    $this->add_responsive_control(
                        'ua_contact_form_margin',
                        [
                            'label' => esc_html__('Form Margin', 'ultraaddons'),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => ['px', 'em', '%'],
                            'selectors' => [
                                '{{WRAPPER}} .ua-form.wpform' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
                
                    $this->add_responsive_control(
                        'ua_contact_form_padding',
                        [
                            'label' => esc_html__('Form Padding', 'ultraaddons'),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => ['px', 'em', '%'],
                            'selectors' => [
                                '{{WRAPPER}} .ua-form.wpform' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
                
                
                    $this->add_control(
                        'ua_contact_form_border_radius',
                        [
                            'label' => esc_html__('Border Radius', 'ultraaddons'),
                            'type' => Controls_Manager::DIMENSIONS,
                            'separator' => 'before',
                            'size_units' => ['px'],
                            'selectors' => [
                                '{{WRAPPER}} .ua-form.wpform' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
                
                
                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'ua_contact_form_border',
                            'selector' => '{{WRAPPER}} .ua-form.wpform',
                        ]
                    );
                
                
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'ua_contact_form_box_shadow',
                            'selector' => '{{WRAPPER}} .ua-form.wpform',
                        ]
                    );
                
                    $this->end_controls_section();
        }
        
        protected function placeholder_style(){
                $this->start_controls_section(
                        'placeholder_section',
                        [
                                'label' =>  __( 'Placeholder', 'ultraaddons' ) ,
                                'tab' => Controls_Manager::TAB_STYLE,
                        ]
                );
                $this->add_control(
                        'placeholder_color',
                                [
                                'label' => esc_html__('Placeholder Color', 'ultraaddons'),
                                'type' => Controls_Manager::COLOR,
                                'selectors' => [
                                        '{{WRAPPER}} input::placeholder, textarea::placeholder, select::placeholder' => 'color: {{VALUE}};',
                                ],
                                ]
                        );
                        $this->add_group_control(
                                Group_Control_Typography::get_type(),
                                [
                                    'name'                  => 'placeholder_typography',
                                    'label'                 => __('Typography', 'ultraaddons'),
                                    'selector'              => '{{WRAPPER}} input::placeholder, textarea::placeholder, select::placeholder',
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
                if( ! class_exists( 'WPForms\WPForms' ) )
                return;
                $settings    = $this->get_settings_for_display();

                $form_id     = isset( $settings['form_id'] ) ? absint( $settings['form_id'] ) : 0;

                foreach( ultraaddons_get_wpform_list() as $key=>$val){ 
                    $id     =  $key;
                    $title  =  $val;
                }

        $this->add_render_attribute(
                'ua_wpform_class',
                ['class' => 'ua-form wpform']
        );
        ?>
        <?php
        if(!empty( $form_id )):
        ?>
        <div <?php echo $this->get_render_attribute_string( 'ua_wpform_class' );?>>
            <?php 
                if('yes' === $settings['title']){
                    echo '<' . $settings['title_tag'] . ' class="ua-wp-form-title">' .  $title. 
                            '</' . $settings['title_tag'] . '>';
                }
            ?>
            <p class="ua-wpform-description">
            <?php 
                if('yes' === $settings['description']){
                $post = get_post( $id );
                echo $excerpt = ( $post->post_excerpt ) ? $post->post_excerpt : $post->post_content;
                }
            ?>
            </p>
            <?php
                echo do_shortcode(
                    '[wpforms id="'. $form_id .'" ]'
                );
            ?>
        </div>

        <?php 
        else:
         echo "<div class='ua-alert'>" . esc_html__( "Please select WPForms.", 'ultraaddons' ) . "</div>";
        endif;
        ?>
        <?php
        }
}
