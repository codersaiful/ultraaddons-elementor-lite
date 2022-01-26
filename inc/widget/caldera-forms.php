<?php
namespace UltraAddons\Widget;


use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Caldera_Forms extends Base{
       
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
                if( class_exists( 'Caldera_Forms' ) ){
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
                if( class_exists( 'Caldera_Forms' ) ){
                        $this->add_control(
                                'form_id',
                                array(
                                'label'   => __( 'Form', 'ultraaddons' ),
                                'type'    => Controls_Manager::SELECT2,
                                'options' => ultraaddons_get_caldera_form_list(),
                                'default' =>0
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

        protected function general_style(){
                
                $this->start_controls_section(
                        'from_style',
                        [
                                'label' =>  __( 'General Style', 'ultraaddons' ) ,
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
                                'default' => 'left',
                                'selectors' => [
                                        '{{WRAPPER}} .ua-form.caldera-forms h1, .ua-form.caldera-forms h2, 
                                        .ua-form.caldera-forms h3, .ua-form.caldera-forms h4, 
                                        .ua-form.caldera-forms h5, .ua-form.caldera-forms h6, 
                                        .ua-form.caldera-forms p' => 'text-align:{{VALUE}};',
                                ],
                               
                            ]
                    );
                    
                $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                                'name' => 'title_typography',
                                'label' => 'Title Typography',
                                'selector' => '{{WRAPPER}} .ua-form.caldera-forms h1, .ua-form.caldera-forms h2, 
                                .ua-form.caldera-forms h3, .ua-form.caldera-forms h4, .ua-form.caldera-forms h5, .ua-form.caldera-forms h6',
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
                                        '{{WRAPPER}} .ua-form.caldera-forms h1, .ua-form.caldera-forms h2, 
                                        .ua-form.caldera-forms h3, .ua-form.caldera-forms h4, .ua-form.caldera-forms h5, .ua-form.caldera-forms h6' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .ua-form.caldera-forms h1, .ua-form.caldera-forms h2, 
                    .ua-form.caldera-forms h3, .ua-form.caldera-forms h4, .ua-form.caldera-forms h5, .ua-form.caldera-forms h6' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                                'separator'=>'after',
			]
		);
                $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                                'name' => 'desc_typography',
                                'label' => 'Description Typography',
                                'selector' => '{{WRAPPER}} .ua-form.caldera-forms p',
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
                                        '{{WRAPPER}} .ua-form.caldera-forms p' => 'color: {{VALUE}};',
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
                                'selector' => '{{WRAPPER}} .ua-form.caldera-forms .caldera-grid label',
        
                        ]
                );
                $this->add_control(
                        'label_color',
                        [
                                'label' => __( 'Form Label Color', 'ultraaddons' ),
                                'type' => Controls_Manager::COLOR,
                                'default' => '#333',
                                'selectors' => [
                                        '{{WRAPPER}} .ua-form.caldera-forms .caldera-grid label' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .ua-form.caldera-forms .caldera-grid label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}}  .ua-form.caldera-forms .form-control' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .ua-form.caldera-forms .form-control' => 'background-color: {{VALUE}};',
				],
			]
		);
        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'input_border',
				'label' => esc_html__( 'Border', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} .ua-form.caldera-forms .form-control',
			]
		);
        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'input_shadow',
				'label' => esc_html__( 'Input Shadow', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} .ua-form.caldera-forms .form-control',
			]
		);
		$this->add_group_control(
		Group_Control_Typography::get_type(),
			[
				'name' => 'input_typography',
				'selector' => '{{WRAPPER}} .ua-form.caldera-forms .form-control',
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
					'{{WRAPPER}} .ua-form.caldera-forms .form-control' => 'height: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .ua-form.caldera-forms textarea.form-control' => 'height: {{SIZE}}{{UNIT}};',
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
                                        '{{WRAPPER}} .ua-form.caldera-forms .btn' => 'background-color: {{VALUE}};',
                                ],
                        ]
                );
                $this->add_control(
                        '_btn_text_color', [
                                'label' => __( 'Button Text Color', 'ultraaddons' ),
                                'type'      => Controls_Manager::COLOR,
                                'selectors' => [
                                                '{{WRAPPER}} .ua-form.caldera-forms .btn' => 'color: {{VALUE}};',
                                ],
                        ]
                );
        
                $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                                        'name' => 'btn_typography',
                                        'label' => 'Button Typography',
                                        'selector' => '{{WRAPPER}} .ua-form.caldera-forms .btn',

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
                                        '{{WRAPPER}} .ua-form.caldera-forms .btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                        '{{WRAPPER}} .ua-form.caldera-forms .btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                        '{{WRAPPER}} .ua-form.caldera-forms .btn:hover' => 'background: {{VALUE}};',
                                ],
                        ]
                );
                $this->add_control(
                        '_btn_text_hover_color', [
                                'label' => __( 'Button Text Color', 'ultraaddons' ),
                                'type'      => Controls_Manager::COLOR,
                                'selectors' => [
                                        '{{WRAPPER}} .ua-form.caldera-forms .btn:hover' => 'color: {{VALUE}};',
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
                if( ! class_exists( 'Caldera_Forms' ) )
                return;

                $settings       = $this->get_settings_for_display();
                $form_id        = $settings['form_id'];

        $this->add_render_attribute(
                'ua_caldera_forms_class',
                ['class' => 'ua-form caldera-forms' ]
        );
        ?>
        <div <?php echo $this->get_render_attribute_string( 'ua_caldera_forms_class' );?>>
            <?php
                echo do_shortcode(
                    '[caldera_form id="'. $form_id .'" ]'
                );
            ?>
        </div>
        <?php
        if(Plugin::$instance->editor->is_edit_mode() ){
            echo '<style>
            .elementor-element.elementor-widget-empty{
                background-color: transparent;
            }
            .eicon-facebook-comments:before{
                content:none
            }
            .caldera-grid .row {
                margin-left: -7.5px;
                margin-right: -7.5px;
                max-width: 100%;
            }
            .caldera-grid .col-sm-4 {
                width: 33.33333333%;
            }
            .caldera-grid .col-sm-12 {
                width: 100%;
            }
            .layout-grid .col-xs-4 {
                width: 33.33333333333333%;
            }
            .caldera-grid .col-sm-1, .caldera-grid .col-sm-10, .caldera-grid .col-sm-11, .caldera-grid .col-sm-12, .caldera-grid .col-sm-2, .caldera-grid .col-sm-3, .caldera-grid .col-sm-4, .caldera-grid .col-sm-5, .caldera-grid .col-sm-6, .caldera-grid .col-sm-7, .caldera-grid .col-sm-8, .caldera-grid .col-sm-9 {
                float: left;
            }
            .caldera-grid .col-lg-1, .caldera-grid .col-lg-10, .caldera-grid .col-lg-11, .caldera-grid .col-lg-12, .caldera-grid .col-lg-2, .caldera-grid .col-lg-3, .caldera-grid .col-lg-4, .caldera-grid .col-lg-5, .caldera-grid .col-lg-6, .caldera-grid .col-lg-7, .caldera-grid .col-lg-8, .caldera-grid .col-lg-9, .caldera-grid .col-md-1, .caldera-grid .col-md-10, .caldera-grid .col-md-11, .caldera-grid .col-md-12, .caldera-grid .col-md-2, .caldera-grid .col-md-3, .caldera-grid .col-md-4, .caldera-grid .col-md-5, .caldera-grid .col-md-6, .caldera-grid .col-md-7, .caldera-grid .col-md-8, .caldera-grid .col-md-9, .caldera-grid .col-sm-1, .caldera-grid .col-sm-10, .caldera-grid .col-sm-11, .caldera-grid .col-sm-12, .caldera-grid .col-sm-2, .caldera-grid .col-sm-3, .caldera-grid .col-sm-4, .caldera-grid .col-sm-5, .caldera-grid .col-sm-6, .caldera-grid .col-sm-7, .caldera-grid .col-sm-8, .caldera-grid .col-sm-9, .caldera-grid .col-xs-1, .caldera-grid .col-xs-10, .caldera-grid .col-xs-11, .caldera-grid .col-xs-12, .caldera-grid .col-xs-2, .caldera-grid .col-xs-3, .caldera-grid .col-xs-4, .caldera-grid .col-xs-5, .caldera-grid .col-xs-6, .caldera-grid .col-xs-7, .caldera-grid .col-xs-8, .caldera-grid .col-xs-9 {
                position: relative;
                padding-left: 7.5px;
                padding-right: 7.5px;
            }
            </style>';
        }
        }
}
