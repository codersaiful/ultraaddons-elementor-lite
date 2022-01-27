<?php
namespace UltraAddons\Widget;


use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Ninja_Forms extends Base{
       
        public function get_keywords() {
                return [ 'ultraaddons', 'ua', 'contact', 'quote', 'forms', 'form', 'ninja', ];
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
                $this->register_content_controls();
                if( class_exists( 'Ninja_Forms' ) ){
                        $this->title_style();
                        $this->input_style();
                        $this->label_style();
                        $this->button_style();
                        $this->error_style();
                        $this->placeholder_style();
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
                if( class_exists( 'Ninja_Forms' ) ){
                        $this->add_control(
                                'form_id',
                                array(
                                'label'   => __( 'Form', 'ultraaddons' ),
                                'type'    => Controls_Manager::SELECT,
                                'options' => ultraaddons_get_ninja_form_list(),
                                'default' => '0',
                                )

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
        }

        protected function title_style(){
                
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
                                'selector' => '{{WRAPPER}} .ua-ninja-form .nf-form-title',
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
                                'default' => '#333',
                                'selectors' => [
                                        '{{WRAPPER}} .ua-ninja-form .nf-form-title' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .ua-ninja-form .nf-form-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                                'separator'=>'after',
			]
		);
                $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                                'name' => 'desc_typography',
                                'label' => 'Description Typography',
                                'selector' => '{{WRAPPER}} .ua-ninja-form .nf-before-form-content',
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
                                'default' => '#333',
                                'selectors' => [
                                        '{{WRAPPER}} .ua-ninja-form .nf-before-form-content' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .ua-ninja-form .nf-before-form-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                                'separator'=>'after',
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
                        .ua-ninja-form .nf-form-content  input[type="text"],
                        .ua-ninja-form .nf-form-content input[type="email"], 
                        .ua-ninja-form .nf-form-content input[type="number"], 
                        .ua-ninja-form .nf-form-content input[type="range"], 
                        .ua-ninja-form .nf-form-content input[type="password"],
                        .ua-ninja-form .nf-form-content input[type="search"], 
                        .ua-ninja-form .nf-form-content input[type="tel"], 
                        .ua-ninja-form .nf-form-content input[type="url"],
                        .ua-ninja-form .nf-form-content input[type="time"], 
                        .ua-ninja-form .nf-form-content input[type="week"], 
                        .ua-ninja-form .nf-form-content input[type="datetime"], 
                        .ua-ninja-form .nf-form-content input[type="date"],  
                        .ua-ninja-form .nf-form-content textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                .ua-ninja-form .nf-form-content input[type="text"],
                                .ua-ninja-form .nf-form-content input[type="email"], 
                                .ua-ninja-form .nf-form-content input[type="number"], 
                                .ua-ninja-form .nf-form-content input[type="range"], 
                                .ua-ninja-form .nf-form-content input[type="password"],
                                .ua-ninja-form .nf-form-content input[type="search"], 
                                .ua-ninja-form .nf-form-content input[type="tel"], 
                                .ua-ninja-form .nf-form-content input[type="url"],
                                .ua-ninja-form .nf-form-content input[type="time"], 
                                .ua-ninja-form .nf-form-content input[type="week"], 
                                .ua-ninja-form .nf-form-content input[type="datetime"], 
                                .ua-ninja-form .nf-form-content input[type="date"],  
                                .ua-ninja-form .nf-form-content textarea' => 'background-color: {{VALUE}};',
                ],
            ]
            );
            $this->add_group_control(
            Group_Control_Typography::get_type(),
                        [
                        'name' => 'input_typography',
                        'selector' => '{{WRAPPER}} 
                        .ua-ninja-form .nf-form-content input[type="text"],
                        .ua-ninja-form .nf-form-content input[type=email], 
                        .ua-ninja-form .nf-form-content input[type="number"], 
                        .ua-ninja-form .nf-form-content input[type="range"], 
                        .ua-ninja-form .nf-form-content input[type="password"],
                        .ua-ninja-form .nf-form-content input[type="search"], 
                        .ua-ninja-form .nf-form-content input[type="tel"], 
                        .ua-ninja-form .nf-form-content input[type="url"],
                        .ua-ninja-form .nf-form-content input[type="time"], 
                        .ua-ninja-form .nf-form-content input[type="week"], 
                        .ua-ninja-form .nf-form-content input[type="datetime"], 
                        .ua-ninja-form .nf-form-content input[type="date"],  
                        .ua-ninja-form .nf-form-content textarea',
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
                    .ua-ninja-form .nf-form-content input[type="text"],
                    .ua-ninja-form .nf-form-content input[type=email], 
                    .ua-ninja-form .nf-form-content input[type="number"], 
                    .ua-ninja-form .nf-form-content input[type="range"], 
                    .ua-ninja-form .nf-form-content input[type="password"],
                    .ua-ninja-form .nf-form-content input[type="search"], 
                    .ua-ninja-form .nf-form-content input[type="tel"], 
                    .ua-ninja-form .nf-form-content input[type="url"],
                    .ua-ninja-form .nf-form-content input[type="time"], 
                    .ua-ninja-form .nf-form-content input[type="week"], 
                    .ua-ninja-form .nf-form-content input[type="datetime"], 
                    .ua-ninja-form .nf-form-content input[type="date"]' => 'height: {{SIZE}}{{UNIT}};',
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

        protected function label_style(){
                $this->start_controls_section(
                        'label_style',
                        [
                                'label' =>  __( 'Label', 'ultraaddons' ) ,
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
                                'selector' => '{{WRAPPER}} .ua-ninja-form .nf-field-label',
        
                        ]
                );
                $this->add_control(
                        'label_color',
                        [
                                'label' => __( 'Form Label Color', 'ultraaddons' ),
                                'type' => Controls_Manager::COLOR,
                                'default' => '#333',
                                'selectors' => [
                                        '{{WRAPPER}} .ua-ninja-form .nf-field-label' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .ua-ninja-form .nf-field-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                                'separator'=>'after',
			]
		);
                $this->end_controls_section();
        }

        protected function error_style(){
                $this->start_controls_section(
                    'section_error_style',
                    [
                        'label' => __('Errors', 'ultraaddons'),
                        'tab' => Controls_Manager::TAB_STYLE,
                    ]
                );
            
                $this->add_control(
                    'error_messages_heading',
                    [
                        'label' => __('Error Messages', 'ultraaddons'),
                        'type' => Controls_Manager::HEADING,
                    ]
                );
            
                $this->add_control(
                    'error_message_text_color',
                    [
                        'label' => __('Text Color', 'ultraaddons'),
                        'type' => Controls_Manager::COLOR,
                        'default' => '',
                        'selectors' => [
                            '{{WRAPPER}} .ua-ninja-form .nf-error-wrap .nf-error-required-error' => 'color: {{VALUE}}',
                        ],
                    ]
                );
            
                $this->add_control(
                    'validation_errors_heading',
                    [
                        'label' => __('Validation Errors', 'ultraaddons'),
                        'type' => Controls_Manager::HEADING,
                        'separator' => 'before',
                    ]
                );
            
                $this->add_control(
                    'validation_error_description_color',
                    [
                        'label' => __('Error Description Color', 'ultraaddons'),
                        'type' => Controls_Manager::COLOR,
                        'default' => '',
                        'selectors' => [
                            '{{WRAPPER}} .ua-ninja-form .nf-form-errors .nf-error-field-errors' => 'color: {{VALUE}}',
                        ],
                    ]
                );
                $this->add_control(
                    'validation_error_text_color',
                    [
                        'label' => __('Error Text Color', 'ultraaddons'),
                        'type' => Controls_Manager::COLOR,
                        'default' => '',
                        'selectors' => [
                            '{{WRAPPER}} .ua-ninja-form .nf-error .nf-error-msg' => 'color: {{VALUE}}',
                        ],
                    ]
                );
            
                $this->add_control(
                    'validation_error_field_input_border_color',
                    [
                        'label' => __('Error Border Color', 'ultraaddons'),
                        'type' => Controls_Manager::COLOR,
                        'default' => '',
                        'selectors' => [
                            '{{WRAPPER}} .ua-ninja-form .nf-error .ninja-forms-field, .ua-ninja-form .nf-error.field-wrap .nf-field-element:after' => 'border-color: {{VALUE}} !important',
                            '{{WRAPPER}} .ua-ninja-form .nf-error.field-wrap .nf-field-element:after' => 'background: {{VALUE}} !important',
                        ],
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
                                        '{{WRAPPER}} .ua-ninja-form input::placeholder, .ua-ninja-form textarea::placeholder, .ua-ninja-form select::placeholder' => 'color: {{VALUE}};',
                                ],
                                ]
                        );
                        $this->add_group_control(
                                Group_Control_Typography::get_type(),
                                [
                                    'name'                  => 'placeholder_typography',
                                    'label'                 => __('Typography', 'ultraaddons'),
                                    'selector'              => '{{WRAPPER}} .ua-ninja-form input::placeholder, .ua-ninja-form textarea::placeholder, .ua-ninja-form select::placeholder',
                                ]
                            );
               
         $this->end_controls_section();
        }
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
                                '{{WRAPPER}} .ua-ninja-form' => 'background: {{VALUE}};',
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
                                '{{WRAPPER}} .ua-ninja-form' => 'max-width: {{SIZE}}{{UNIT}};',
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
                                '{{WRAPPER}} .ua-ninja-form' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                '{{WRAPPER}} .ua-ninja-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                '{{WRAPPER}} .ua-ninja-form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
                
                
                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'ua_contact_form_border',
                            'selector' => '{{WRAPPER}} .ua-ninja-form',
                        ]
                    );
                
                
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'ua_contact_form_box_shadow',
                            'selector' => '{{WRAPPER}} .ua-ninja-form',
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
                                                '{{WRAPPER}} .ua-ninja-form .submit-container input[type="button"]' => 'background-color: {{VALUE}};',
                                        ],
                                ]
                        );
                        $this->add_control(
                                '_btn_text_color', [
                                        'label' => __( 'Button Text Color', 'ultraaddons' ),
                                        'type'      => Controls_Manager::COLOR,
                                        'selectors' => [
                                                        '{{WRAPPER}} .ua-ninja-form .submit-container input[type="button"]' => 'color: {{VALUE}};',
                                        ],
                                ]
                        );
                    
                        $this->add_group_control(
                                Group_Control_Typography::get_type(),
                                [
                                                'name' => 'btn_typography',
                                                'label' => 'Button Typography',
                                                'selector' => '{{WRAPPER}} .ua-ninja-form .submit-container input[type="button"]',
                    
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
                                                '{{WRAPPER}} .ua-ninja-form .submit-container input[type="button"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                                '{{WRAPPER}} .ua-ninja-form .submit-container input[type="button"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                                '{{WRAPPER}} .ua-ninja-form .submit-container input[type="button"]:hover' => 'background: {{VALUE}};',
                                        ],
                                ]
                        );
                        $this->add_control(
                                '_btn_text_hover_color', [
                                        'label' => __( 'Button Text Color', 'ultraaddons' ),
                                        'type'      => Controls_Manager::COLOR,
                                        'selectors' => [
                                                '{{WRAPPER}} .ua-ninja-form .submit-container input[type="button"]:hover' => 'color: {{VALUE}};',
                                        ],
                                        'default' =>'#333'
                                ]
                        );
                        $this->end_controls_tab();
                        $this->end_controls_tabs();
                    
                        $this->end_controls_section();
                    }
        private function add_basic_switcher_control( $key, $title ) {
                $this->add_control(
                        $key,
                        array(
                                'label' => $title,
                                'type'  => Controls_Manager::SWITCHER,
                        )
                );
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

                if (!class_exists('Ninja_Forms')) {
                        return;
                    }
                $settings    = $this->get_settings_for_display();
                $form_id     =  $settings['form_id'];

        $this->add_render_attribute('ua_ninjaforms_class',[
                        'class' => 'ua-ninja-form',
                ]
	);
        ?>
        <?php
        if(!empty( $form_id )):
        ?>
        <div <?php echo $this->get_render_attribute_string( 'ua_ninjaforms_class' );?>>
            <?php
                echo do_shortcode(
                    '[ninja_form id="'. $form_id .'" ]'
                );
            ?>
        </div>
        <?php 
        else:
         echo "<div class='ua-alert'>" . esc_html__( "Please select Ninja Forms.", 'ultraaddons' ) . "</div>";
        endif;
        }
}
