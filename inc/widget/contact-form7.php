<?php
namespace UltraAddons\Widget;


use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Contact_Form7 extends Base{
    
        public function get_keywords() {
                return [ 'ultraaddons','ua', 'appointment', 'contact', 'quote', 'forms', 'schedule', 'cf7', 'contact form', ];
        }
    
        protected function register_content_controls(){
            
                $this->start_controls_section(
                        '_section_cf7',
                        [
                                'label' => ultraaddons_is_cf7_activated() ? __( 'Contact Form 7', 'ultraaddons' ) : __( 'Missing Notice', 'ultraaddons' ),
                                'tab' => Controls_Manager::TAB_CONTENT,
                        ]
                );

                if ( ! ultraaddons_is_cf7_activated() ) {
                    $this->add_control(
                        '_cf7_missing_notice',
                        [
                            'type' => Controls_Manager::RAW_HTML,
                            'raw' => sprintf(
                                __( 'Hello %2$s, looks like %1$s is missing in your site. Please click on the link below and install/activate %1$s. Make sure to refresh this page after installation or activation.', 'ultraaddons' ),
                                '<a href="'.esc_url( admin_url( 'plugin-install.php?s=Contact+Form+7&tab=search&type=term' ) )
                                .'" target="_blank" rel="noopener">Contact Form 7</a>',
                                ultraaddons_get_current_user_display_name()
                            ),
                            'content_classes' => 'elementor-panel-alert elementor-panel-alert-danger',
                        ]
                    );

                    $this->add_control(
                        '_cf7_install',
                        [
                            'type' => Controls_Manager::RAW_HTML,
                            'raw' => '<a href="'.esc_url( admin_url( 'plugin-install.php?s=Contact+Form+7&tab=search&type=term' ) ).'" target="_blank" rel="noopener">Click to install or activate Contact Form 7</a>',
                        ]
                    );

                    $this->end_controls_section();
                    return;
                }

                $this->add_control(
                    'form_id',
                    [
                        'label' => __( 'Select Your Form', 'ultraaddons' ),
                        'type' => Controls_Manager::SELECT,
                        'label_block' => true,
                        'options' => ultraaddons_get_cf7_forms(),
                    ]
                );
                $this->add_control(
			'_cf7_form_style',
			[
				'label' => esc_html__( 'Form Style', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => false,
                                'default'  => 'style-default',
				'options' => [
					'style-default' => __( 'Default', 'ultraaddons' ),
					'style-1'  => __( 'Style 1', 'ultraaddons' ),
					'style-2'  => __( 'Style 2', 'ultraaddons' ),
				],
			]
		);
                $this->add_control(
                    'html_class',
                    [
                        'label' => __( 'HTML Class', 'ultraaddons' ),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                        'description' => __( 'Add CSS custom class to the form.', 'ultraaddons' ),
                    ]
                );
                $this->add_control(
			'show_title',
			[
				'label' => esc_html__( 'Custom Title', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'ultraaddons' ),
				'label_off' => esc_html__( 'Hide', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

                $this->add_control(
			'show_desc',
			[
				'label' => esc_html__( 'Custom Description', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'ultraaddons' ),
				'label_off' => esc_html__( 'Hide', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'no',
                                 'condition'=>[
                                        'show_title'=>'yes',
                                ],
			]
		);

                $this->add_control(
			'custom_title',
			[
				'label' => esc_html__( 'Custom Title', 'ultraaddons' ),
                                'label_block' => true,
				'type' => Controls_Manager::TEXT,
                                'default' => esc_html__( 'Contact Us', 'ultraaddons' ),
                                'condition'=>[
                                        'show_title'=>'yes'
                                ],
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
                            ],
                            'default' => 'h2',
                            'condition' => [
                                'show_title' => 'yes'
                            ],
                        ]
                    );
                $this->add_control(
			'form_description',
			[
				'label' => esc_html__( 'Description', 'ultraaddons' ),
				'type' => Controls_Manager::TEXTAREA,
                                'default' => esc_html__( 'Please send us a message by filling out the form below and we will get back with you shortly.', 'ultraaddons' ),
				'rows' => 4,
                                'condition'=>[
                                        'show_desc'=>'yes'
                                ],
			]
		);

                $this->end_controls_section();
        }
        
        protected function register_general_style_controls(){
                
                $this->start_controls_section(
                        '_section_cf7_style_general',
                        [
                                'label' =>  __( 'Title &  Description', 'ultraaddons' ) ,
                                'tab'   => Controls_Manager::TAB_STYLE,
                                  'condition'=>[
                                        'show_title'=>'yes',
                                ],
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
                                        '{{WRAPPER}} .ua-form .ua-cf7-title, .ua-form .ua-cf7-description' => 'text-align:{{VALUE}};',
                                ],
                               
                            ]
                    );
                    
                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                                'name' => 'title_typography',
                                'label' => 'Title Typography',
                                'selector' => '{{WRAPPER}} .ua-form .ua-cf7-title',
                                'global' => [
                                        'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                                ],
                                'condition'=>[
                                        'show_title'=>'yes'
                                ],
                    
                        ]
                    );
                    $this->add_control(
                        'title_color',
                        [
                                'label' => __( 'Title Color', 'ultraaddons' ),
                                'type' => Controls_Manager::COLOR,
                                'selectors' => [
                                        '{{WRAPPER}} .ua-form .ua-cf7-title' => 'color: {{VALUE}};',
                                ],
                                'condition'=>[
                                        'show_title'=>'yes'
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
                                '{{WRAPPER}} .ua-form .ua-cf7-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                        'condition'=>[
                                        'show_title'=>'yes'
                                ],
                        'separator'=>'after',
                    ]
                );
                $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                                'name' => 'cf7_desc_typography',
                                'label' => 'Description Typography',
                                'selector' => '{{WRAPPER}} .ua-form .ua-cf7-description',
                                'condition'=>[
                                        'show_desc'=>'yes'
                                ],
                        ]
                );
                    
                $this->add_control(
                        'desc_color',
                        [
                                'label' => __( 'Deccription Color', 'ultraaddons' ),
                                'type' => Controls_Manager::COLOR,
                                'selectors' => [
                                        '{{WRAPPER}} .ua-form .ua-cf7-description' => 'color: {{VALUE}};',
                                ],
                                'condition'=>[
                                        'show_desc'=>'yes'
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
                        'condition'=>[
                                        'show_desc'=>'yes'
                                ],
                        'selectors'   => [
                                '{{WRAPPER}} .ua-form .ua-cf7-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                                'separator'=>'after',
                    ]
                );
                    

                $this->end_controls_section();
                
        }
        
        protected function register_input_style_controls(){
                
                $this->start_controls_section(
                        '_section_cf7_style_input',
                        [
                                'label' => __( 'Input & Textarea', 'ultraaddons' ),
                                'tab'   => Controls_Manager::TAB_STYLE,
                        ]
                );

                $this->add_responsive_control(
                        '_cf7_input_border',
                        [   
                                'label' => __( 'Border', 'ultraaddons' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px' ],
                                'default' => [
                                        'top' => 1,
                                        'right' => 1,
                                        'bottom' => 1,
                                        'left' => 1,
                                        'unit' => 'px',
                                ],
                                'selectors' => [
                                        '{{WRAPPER}} form.ultraaddons-cf7-form input:not([type="submit"])' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                        '{{WRAPPER}} form.ultraaddons-cf7-form select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                        '{{WRAPPER}} form.ultraaddons-cf7-form textarea' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                        ]
                );
                $this->add_responsive_control(
                        '_cf7_input_border_radius',
                        [   
                                'label' => __( 'Border Radius', 'ultraaddons' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px','%' ],
                                'selectors' => [
                                        '{{WRAPPER}} form.ultraaddons-cf7-form input:not([type="submit"])' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                        '{{WRAPPER}} form.ultraaddons-cf7-form select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                        '{{WRAPPER}} form.ultraaddons-cf7-form textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                        ]
                );
                $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'input_shadow',
				'label' => esc_html__( 'Input Shadow', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} form.ultraaddons-cf7-form input:not([type="submit"]), form.ultraaddons-cf7-form textarea, form.ultraaddons-cf7-form select',
			]
		);
                $this->add_control(
                        '_cf7_input_text_color',
                        [   
                                'label' => __( 'Text Color', 'ultraaddons' ),
                                'type' => Controls_Manager::COLOR,
                                'default' => '#5C6B79',
                                'selectors' => [
                                        '{{WRAPPER}} form.ultraaddons-cf7-form input:not([type="submit"])' => 'color: {{VALUE}};',
                                        '{{WRAPPER}} form.ultraaddons-cf7-form select' => 'color: {{VALUE}};',
                                        '{{WRAPPER}} form.ultraaddons-cf7-form textarea' => 'color: {{VALUE}};',
                                        '{{WRAPPER}} form.ultraaddons-cf7-form input::placeholder' => 'color: {{VALUE}}; opacity: 1;', /* Firefox */
                                        '{{WRAPPER}} form.ultraaddons-cf7-form input:-ms-input-placeholder' => 'color: {{VALUE}};', /* Internet Explorer 10-11 */
                                        '{{WRAPPER}} form.ultraaddons-cf7-form input::-ms-input-placeholder' => 'color: {{VALUE}};', /* Microsoft Edge */
                                ],
                        ]
                );

                $this->add_control(
                        '_cf7_input_border_color',
                        [   
                                'label' => __( 'Border Color', 'ultraaddons' ),
                                'type' => Controls_Manager::COLOR,
                                'default' => '#EEF1F4',
                                'selectors' => [
                                        '{{WRAPPER}} form.ultraaddons-cf7-form input:not([type="submit"])' => 'border-color: {{VALUE}};',
                                        '{{WRAPPER}} form.ultraaddons-cf7-form select' => 'border-color: {{VALUE}};',
                                        '{{WRAPPER}} form.ultraaddons-cf7-form textarea' => 'border-color: {{VALUE}};',
                                ],
                                'separator'=>'after'
                        ]
                );
                $this->add_control(
                        '_cf7_input_border_focus_color',
                        [   
                                'label' => __( 'Border Focus Color', 'ultraaddons' ),
                                'type' => Controls_Manager::COLOR,
                                'default' => '#EEF1F4',
                                'selectors' => [
                                        '{{WRAPPER}} form.ultraaddons-cf7-form select:focue' => 'border-color: {{VALUE}};',
                                        '{{WRAPPER}} form.ultraaddons-cf7-form textarea:focus' => 'border-color: {{VALUE}};',
                                        '{{WRAPPER}} form.ultraaddons-cf7-form input[type="text"]:focus' => 'border-color: {{VALUE}};',
                                        '{{WRAPPER}} form.ultraaddons-cf7-form input[type="email"]:focus' => 'border-color: {{VALUE}};',
                                ],
                        ]
                );

                $this->add_control(
                        '_cf7_input_bg_color',
                        [   
                                'label' => __( 'Background Color', 'ultraaddons' ),
                                'type' => Controls_Manager::COLOR,
                                'default' => '',
                                'selectors' => [
                                        '{{WRAPPER}} form.ultraaddons-cf7-form input:not([type="submit"])' => 'background-color: {{VALUE}};',
                                        '{{WRAPPER}} form.ultraaddons-cf7-form select' => 'background-color: {{VALUE}};',
                                        '{{WRAPPER}} form.ultraaddons-cf7-form textarea' => 'background-color: {{VALUE}};',
                                ],
                        ]
                );
                $this->add_control(
                        '_cf7_text_height',
                        [   
                                'label' => __( 'Text Height', 'ultraaddons' ),
                                'type' => Controls_Manager::SLIDER,
                                'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
                                'default' => [
					'unit' => 'px',
					'size' => 40,
				],
                                'selectors' => [
                                        '{{WRAPPER}} form.ultraaddons-cf7-form .wpcf7-text' => 'height: {{SIZE}}{{UNIT}};',
                                ],
                        ]
                );
                
                $this->add_control(
                        '_cf7_textarea_height',
                        [   
                                'label' => __( 'Text Area Height', 'ultraaddons' ),
                                'type' => Controls_Manager::SLIDER,
                                'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
				],
                                'default' => [
					'unit' => 'px',
					'size' => 200,
				],
                                'selectors' => [
                                        '{{WRAPPER}} form.ultraaddons-cf7-form textarea.wpcf7-textarea' => 'height: {{SIZE}}{{UNIT}};',
                                ],
                        ]
                );

                $this->end_controls_section();
            
        }
        
        protected function register_button_style_controls(){

                $this->start_controls_section(
                        '_section_cf7_style_button',
                        [
                                'label' => __( 'Submit Button', 'ultraaddons' ),
                                'tab'   => Controls_Manager::TAB_STYLE,
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
                        'button_align',
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
                                        '{{WRAPPER}} .wpcf7 p' => 'text-align:{{VALUE}};',
                                ],
                               
                            ]
                    );
                
                $this->add_responsive_control(
                        '_cf7_button_border',
                        [   
                                'label' => __( 'Border', 'ultraaddons' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px' ],
                                'default' => [
                                        'top' => 1,
                                        'right' => 1,
                                        'bottom' => 1,
                                        'left' => 1,
                                        'unit' => 'px',
                                ],
                                'selectors' => [
                                        '{{WRAPPER}} form.ultraaddons-cf7-form input[type="submit"]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                        ]
                );
                $this->add_responsive_control(
                        '_cf7_button_border_radius',
                        [   
                                'label' => __( 'Border Radius', 'ultraaddons' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px' ],
                                'selectors' => [
                                        '{{WRAPPER}} form.ultraaddons-cf7-form input[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                        ]
                );
                $this->add_control(
                        '_cf7_button_text_color',
                        [   
                                'label' => __( 'Text Color', 'ultraaddons' ),
                                'type' => Controls_Manager::COLOR,
                                'default' => '#FFF',
                                'selectors' => [
                                        '{{WRAPPER}} form.ultraaddons-cf7-form input[type="submit"]' => 'color: {{VALUE}};',
                                ],
                        ]
                );

                $this->add_control(
                        '_cf7_button_border_color',
                        [   
                                'label' => __( 'Border Color', 'ultraaddons' ),
                                'type' => Controls_Manager::COLOR,
                                'default' => '#0fc392',
                                'selectors' => [
                                        '{{WRAPPER}} form.ultraaddons-cf7-form input[type="submit"]' => 'border-color: {{VALUE}};',
                                ],
                        ]
                );

                $this->add_control(
                        '_cf7_button_bg_color',
                        [   
                                'label' => __( 'Background Color', 'ultraaddons' ),
                                'type' => Controls_Manager::COLOR,
                                'default' => '#0fc392',
                                'selectors' => [
                                        '{{WRAPPER}} form.ultraaddons-cf7-form input[type="submit"]' => 'background-color: {{VALUE}};',
                                ],
                        ]
                );
                $this->add_responsive_control(
                        'cf7_button_width',
                        [
                                'label' => __( 'Width', 'ultraaddons' ),
                                'type' => Controls_Manager::SLIDER,
                              'size_units' => [ '%','px', 'em',  ],
                              'range' => [
                                      'px' => [
                                              'min' => 10,
                                              'max' => 1200,
                                      ],
                                      'em' => [
                                              'min' => 1,
                                              'max' => 80,
                                      ],
                              ],
                              'selectors' => [
                                      '{{WRAPPER}} input.wpcf7-submit' => 'width: {{SIZE}}{{UNIT}};',
                              ],
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
                        '_cf7_button_text_color_hover',
                        [   
                                'label' => __( 'Text Color', 'ultraaddons' ),
                                'type' => Controls_Manager::COLOR,
                                'default' => '#0fc392',
                                'selectors' => [
                                        '{{WRAPPER}} form.ultraaddons-cf7-form input[type="submit"]:hover' => 'color: {{VALUE}};',
                                ],
                        ]
                );

                $this->add_control(
                        '_cf7_button_border_color_hover',
                        [   
                                'label' => __( 'Border Color', 'ultraaddons' ),
                                'type' => Controls_Manager::COLOR,
                                'default' => '#0fc392',
                                'selectors' => [
                                        '{{WRAPPER}} form.ultraaddons-cf7-form input[type="submit"]:hover' => 'border-color: {{VALUE}};',
                                ],
                        ]
                );

                $this->add_control(
                        '_cf7_button_bg_color_hover',
                        [   
                                'label' => __( 'Background Color', 'ultraaddons' ),
                                'type' => Controls_Manager::COLOR,
                                'default' => 'transparent',
                                'selectors' => [
                                        '{{WRAPPER}} form.ultraaddons-cf7-form input[type="submit"]:hover' => 'background-color: {{VALUE}};',
                                ],
                        ]
                );

                $this->end_controls_tab();

                $this->end_controls_tabs();
                
                $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                                'name' => 'button_typography',
                                'selector' => '{{WRAPPER}} form.ultraaddons-cf7-form input[type="submit"]',
                                'global' => [
                                        'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                                ],

                        ]
                );
                
                $this->add_responsive_control(
                        '_cf7_button_padding',
                        [   
                                'label' => __( 'Padding', 'ultraaddons' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px' ],
                                'default' => [
                                        'top' => 10,
                                        'right' => 50,
                                        'bottom' => 10,
                                        'left' => 50,
                                        'unit' => 'px',
                                ],
                                'selectors' => [
                                        '{{WRAPPER}} form.ultraaddons-cf7-form input[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                        ]
                );

                $this->end_controls_section();
        }
        
        protected function register_other_style_controls(){
                
                $this->start_controls_section(
                        '_section_cf7_style_other',
                        [
                                'label' => __( 'Errors', 'ultraaddons' ),
                                'tab'   => Controls_Manager::TAB_STYLE,
                        ]
                );
                
                $this->add_control(
                        '_cf7_required_text_color',
                        [   
                                'label' => __( 'Required Text Color', 'ultraaddons' ),
                                'type' => Controls_Manager::COLOR,
                                'default' => '#dc3232',
                                'selectors' => [
                                        '{{WRAPPER}} .wpcf7 form .wpcf7-not-valid-tip' => 'color: {{VALUE}};',
                                ],
                        ]
                );
                
                $this->add_control(
                        '_cf7_success_text_color',
                        [   
                                'label' => __( 'Response Text Color', 'ultraaddons' ),
                                'type' => Controls_Manager::COLOR,
                                'default' => '#06A703',
                                'selectors' => [
                                        '{{WRAPPER}} .wpcf7 form .wpcf7-response-output' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                                ],
                        ]
                );
                
                $this->add_control(
                        '_cf7_error_text_color',
                        [   
                                'label' => __( 'Error Text Border Color', 'ultraaddons' ),
                                'type' => Controls_Manager::COLOR,
                                'default' => '#ffb900',
                                'selectors' => [
                                        '{{WRAPPER}} .wpcf7 form.invalid .wpcf7-response-output' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                                        '{{WRAPPER}} ..wpcf7 form.unaccepted .wpcf7-response-output' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                                ],
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
                                '{{WRAPPER}} .ua-form.cf7-forms' => 'background: {{VALUE}};',
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
                                '{{WRAPPER}} .ua-form.cf7-forms' => 'max-width: {{SIZE}}{{UNIT}};',
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
                                '{{WRAPPER}} .ua-form.cf7-forms' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                '{{WRAPPER}} .ua-form.cf7-forms' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                '{{WRAPPER}} .ua-form.cf7-forms' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
                
                
                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'ua_contact_form_border',
                            'selector' => '{{WRAPPER}} .ua-form.cf7-forms',
                        ]
                    );
                
                
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'ua_contact_form_box_shadow',
                            'selector' => '{{WRAPPER}} .ua-form.cf7-forms',
                        ]
                    );
                
               
                $this->end_controls_section();
            }
            protected function label_style(){
                
                $this->start_controls_section(
                        'label_style',
                        [
                                'label' =>  __( 'Labels', 'ultraaddons' ) ,
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
                                'selector' => '{{WRAPPER}} .ua-form.cf7-forms label',
            
                        ]
                );
                $this->add_control(
                        'label_color',
                        [
                                'label' => __( 'Form Label Color', 'ultraaddons' ),
                                'type' => Controls_Manager::COLOR,
                                'selectors' => [
                                        '{{WRAPPER}} .ua-form.cf7-forms label' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .ua-form.cf7-forms label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                                'separator'=>'after',
            ]
            );
                $this->end_controls_section();
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
                
                $this->register_general_style_controls();
                
                $this->register_input_style_controls();

                $this->label_style();
                
                $this->register_button_style_controls();
                
                $this->register_other_style_controls();

                $this->container_style();

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
                if ( ! ultraaddons_is_cf7_activated() ) {
                        return;
                }

                $settings = $this->get_settings_for_display();
                $this->add_render_attribute(
                        'ua_form_class',
                        ['class' => 'ua-form cf7-forms']
                );
                ?>
        <div <?php echo $this->get_render_attribute_string( 'ua_form_class' );?>>
        <?php 
                if('yes' === $settings['show_title']){
                    echo '<' . $settings['title_tag'] . ' class="ua-cf7-title">' . $settings['custom_title']. 
                            '</' . $settings['title_tag'] . '>';
                }
            ?>
            <p class="ua-cf7-description">
            <?php 
                if('yes' === $settings['show_desc']){
                        echo $settings['form_description'];
                }
            ?>
            </p>
                <?php
                if ( ! empty( $settings['form_id'] ) ):
                        echo ultraaddons_do_shortcode( 'contact-form-7', [
                            'id' => $settings['form_id'],
                            'html_class' => 'ultraaddons-cf7-form ' . sanitize_html_class( $settings['html_class'] . $settings['_cf7_form_style'] ),
                        ] );
                else:
                 echo "<div class='ua-alert'>" . esc_html__( "Please select Contact form 7.", 'ultraaddons' ) . "</div>";
                endif;
                ?>
        </div>
        <?php
        }
}
