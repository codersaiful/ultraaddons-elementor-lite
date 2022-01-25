<?php
namespace UltraAddons\Widget;


use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Ninja_Forms extends Base{
       
        public function get_keywords() {
                return [ 'ultraaddons', 'appointment', 'contact', 'quote', 'form', 'schedule', 'formidable', 'contact form', ];
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
                        $this->general_style();
                        $this->input_style();
                        $this->button_style();
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
                        $this->add_basic_switcher_control( 'title', __( 'Show Form Title', 'ultraaddons' ) );
                       
                        $this->add_control(
                            'title_tag',
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
                                'condition' => [
                                    'title' => 'yes'
                                ],
                            ]
                        );
                        $this->add_basic_switcher_control( 'description', __( 'Show Form Description', 'ultraaddons' ) );
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

        protected function general_style(){
                
                $this->start_controls_section(
                        'from_style',
                        [
                                'label' =>  __( 'General Style', 'ultraaddons' ) ,
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
                                'default' => '#333',
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
                                'default' => '#333',
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
                                'default' => '#333',
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
                                'default' => '#333',
                                'separator'=>'after',
                                'selectors' => [
                                        '{{WRAPPER}} .ua-form .wpforms-field-sublabel' => 'color: {{VALUE}};',
                                       
                                ],
                        ]
                );


                $this->end_controls_section();
        }
        protected function input_style(){
           foreach( ultraaddons_get_ninja_form_list() as $key=>$val){
                $key = $key;
            }
           
                
                $this->start_controls_section(
                        'input_style',
                        [
                                'label' =>  __( 'Input Style', 'ultraaddons' ) ,
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
                    #wpforms-form-'. $key .' textarea' => 'background-color: {{VALUE}};',
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
                #wpforms-form-'. $key .' textarea',
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
                    #wpforms-form-'. $key .' input[type="date"]' => 'height: {{SIZE}}{{UNIT}};',
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
                                'label' =>  __( 'Button Style', 'ultraaddons' ) ,
                                'tab' => Controls_Manager::TAB_STYLE,
                        ]
                );
            
                $this->add_control(
			'btn_block',
			[
				'label' => esc_html__( 'Button Block', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Block', 'ultraaddons' ),
				'label_off' => esc_html__( 'Inline', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'no',
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

                $form_id     = isset( $settings['form_id'] ) ? absint( $settings['form_id'] ) : 0;
                $btn_block   = ($settings['btn_block'] =='yes' ) ? 'btn_block' : '';

        $this->add_render_attribute(
                'ua_ninjaforms_class',
                [
                        'class' => 'ua-form ninjaforms ' . $btn_block,
                ]
	        );
        ?>
        <div <?php echo $this->get_render_attribute_string( 'ua_ninjaforms_class' );?>>
            <?php
                echo do_shortcode(
                    '[ninja_form id="'. $form_id .'" ]'
                );
            ?>
        </div>
        <?php
        }
}
