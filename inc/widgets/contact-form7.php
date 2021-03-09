<?php
namespace UltraAddons\Widget;


use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Contact_Form7 extends Base{
    
        public function get_keywords() {
                return [ 'ultraaddons', 'appointment', 'contact', 'quote', 'form', 'schedule', 'cf7', 'contact form', ];
        }
    
        protected function register_content_controls(){
            
                $this->start_controls_section(
                        '_section_cf7',
                        [
                                'label' => ultraaddons_is_cf7_activated() ? __( 'Contact Form 7', 'medilac' ) : __( 'Missing Notice', 'medilac' ),
                                'tab' => Controls_Manager::TAB_CONTENT,
                        ]
                );

                if ( ! ultraaddons_is_cf7_activated() ) {
                    $this->add_control(
                        '_cf7_missing_notice',
                        [
                            'type' => Controls_Manager::RAW_HTML,
                            'raw' => sprintf(
                                __( 'Hello %2$s, looks like %1$s is missing in your site. Please click on the link below and install/activate %1$s. Make sure to refresh this page after installation or activation.', 'medilac' ),
                                '<a href="'.esc_url( admin_url( 'plugin-install.php?s=Contact+Form+7&tab=search&type=term' ) )
                                .'" target="_blank" rel="noopener">Contact Form 7</a>',
                                medilac_get_current_user_display_name()
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
                        'label' => __( 'Select Your Form', 'medilac' ),
                        'type' => Controls_Manager::SELECT,
                        'label_block' => true,
                        'options' => medilac_get_cf7_forms(),
                    ]
                );

                $this->add_control(
                    'html_class',
                    [
                        'label' => __( 'HTML Class', 'medilac' ),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                        'description' => __( 'Add CSS custom class to the form.', 'medilac' ),
                    ]
                );

                $this->end_controls_section();
        }
        
        protected function register_general_style_controls(){
                
                $this->start_controls_section(
                        '_section_cf7_style_general',
                        [
                                'label' => __( 'General', 'medilac' ),
                                'tab'   => Controls_Manager::TAB_STYLE,
                        ]
                );

                $this->add_responsive_control(
                        '_cf7_align',
                        [
                                'label' => __( 'Alignment', 'medilac' ),
                                'type' => Controls_Manager::CHOOSE,
                                'options' => [
                                        'left'    => [
                                                'title' => __( 'Left', 'medilac' ),
                                                'icon' => 'eicon-text-align-left',
                                        ],
                                        'center' => [
                                                'title' => __( 'Center', 'medilac' ),
                                                'icon' => 'eicon-text-align-center',
                                        ],
                                        'right' => [
                                                'title' => __( 'Right', 'medilac' ),
                                                'icon' => 'eicon-text-align-right',
                                        ],
                                ],
                                'prefix_class' => 'elementor-align-',
                                'default' => 'left',
                        ]
                );

                $this->add_control(
                        'color',
                        [
                                'label' => __( 'Color', 'medilac' ),
                                'type' => Controls_Manager::COLOR,
                                'default' => '#5c6b79',
                                'selectors' => [
                                        '{{WRAPPER}} form.ultraaddons-cf7-form label' => 'color: {{VALUE}};',
                                ],
                        ]
                );

                $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                                'name' => 'form_typography',
                                'selector' => '{{WRAPPER}} form.ultraaddons-cf7-form label',
                                'global' => [
                                        'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                                ],

                        ]
                );

                $this->end_controls_section();
                
        }
        
        protected function register_input_style_controls(){
                
                $this->start_controls_section(
                        '_section_cf7_style_input',
                        [
                                'label' => __( 'Input Fields', 'medilac' ),
                                'tab'   => Controls_Manager::TAB_STYLE,
                        ]
                );

                $this->add_responsive_control(
                        '_cf7_input_border',
                        [   
                                'label' => __( 'Border', 'medilac' ),
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
                $this->add_control(
                        '_cf7_input_text_color',
                        [   
                                'label' => __( 'Text Color', 'medilac' ),
                                'type' => Controls_Manager::COLOR,
                                'default' => '#5C6B79',
                                'selectors' => [
                                        '{{WRAPPER}} form.ultraaddons-cf7-form input:not([type="submit"])' => 'color: {{VALUE}};',
                                        '{{WRAPPER}} form.ultraaddons-cf7-form select' => 'color: {{VALUE}};',
                                        '{{WRAPPER}} form.ultraaddons-cf7-form textarea' => 'color: {{VALUE}};',
                                ],
                        ]
                );

                $this->add_control(
                        '_cf7_input_border_color',
                        [   
                                'label' => __( 'Border Color', 'medilac' ),
                                'type' => Controls_Manager::COLOR,
                                'default' => '#EEF1F4',
                                'selectors' => [
                                        '{{WRAPPER}} form.ultraaddons-cf7-form input:not([type="submit"])' => 'border-color: {{VALUE}};',
                                        '{{WRAPPER}} form.ultraaddons-cf7-form select' => 'border-color: {{VALUE}};',
                                        '{{WRAPPER}} form.ultraaddons-cf7-form textarea' => 'border-color: {{VALUE}};',
                                ],
                        ]
                );

                $this->add_control(
                        '_cf7_input_bg_color',
                        [   
                                'label' => __( 'Background Color', 'medilac' ),
                                'type' => Controls_Manager::COLOR,
                                'default' => '#F4F9FC',
                                'selectors' => [
                                        '{{WRAPPER}} form.ultraaddons-cf7-form input:not([type="submit"])' => 'background-color: {{VALUE}};',
                                        '{{WRAPPER}} form.ultraaddons-cf7-form select' => 'background-color: {{VALUE}};',
                                        '{{WRAPPER}} form.ultraaddons-cf7-form textarea' => 'background-color: {{VALUE}};',
                                ],
                        ]
                );
                
                $this->add_control(
                        '_cf7_textarea_height',
                        [   
                                'label' => __( 'Text Area Height', 'medilac' ),
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
                                'label' => __( 'Button', 'medilac' ),
                                'tab'   => Controls_Manager::TAB_STYLE,
                        ]
                );

                $this->start_controls_tabs( 'tabs_button_style' );

                $this->start_controls_tab(
                        'tab_button_normal',
                        [
                                'label' => __( 'Normal', 'medilac' ),
                        ]
                );
                
                $this->add_responsive_control(
                        '_cf7_button_border',
                        [   
                                'label' => __( 'Border', 'medilac' ),
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
                $this->add_control(
                        '_cf7_button_text_color',
                        [   
                                'label' => __( 'Text Color', 'medilac' ),
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
                                'label' => __( 'Border Color', 'medilac' ),
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
                                'label' => __( 'Background Color', 'medilac' ),
                                'type' => Controls_Manager::COLOR,
                                'default' => '#0fc392',
                                'selectors' => [
                                        '{{WRAPPER}} form.ultraaddons-cf7-form input[type="submit"]' => 'background-color: {{VALUE}};',
                                ],
                        ]
                );

                $this->end_controls_tab();

                $this->start_controls_tab(
                        'tab_button_hover',
                        [
                                'label' => __( 'Hover', 'medilac' ),
                        ]
                );
                
                
                $this->add_control(
                        '_cf7_button_text_color_hover',
                        [   
                                'label' => __( 'Text Color', 'medilac' ),
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
                                'label' => __( 'Border Color', 'medilac' ),
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
                                'label' => __( 'Background Color', 'medilac' ),
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
                                'label' => __( 'Padding', 'medilac' ),
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
                                'label' => __( 'Others', 'medilac' ),
                                'tab'   => Controls_Manager::TAB_STYLE,
                        ]
                );
                
                $this->add_control(
                        '_cf7_required_text_color',
                        [   
                                'label' => __( 'Required Text Color', 'medilac' ),
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
                                'label' => __( 'Response Text Color', 'medilac' ),
                                'type' => Controls_Manager::COLOR,
                                'default' => '#00a0d2',
                                'selectors' => [
                                        '{{WRAPPER}} .wpcf7 form .wpcf7-response-output' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                                ],
                        ]
                );
                
                $this->add_control(
                        '_cf7_error_text_color',
                        [   
                                'label' => __( 'Error Text Border Color', 'medilac' ),
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
                
                $this->register_button_style_controls();
                
                $this->register_other_style_controls();

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

                if ( ! empty( $settings['form_id'] ) ) {
                        echo ultraaddons_do_shortcode( 'contact-form-7', [
                            'id' => $settings['form_id'],
                            'html_class' => 'ultraaddons-cf7-form ' . sanitize_html_class( $settings['html_class'] ),
                                    ] );
                }
        }
}

