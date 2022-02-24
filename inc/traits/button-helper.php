<?php
namespace UltraAddons\Traits;

use Elementor\Controls_Manager;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Utils;

trait Button_Helper{
    
    public $btn_color = '#ffffff';
    public $btn_text_color = '#21272c';
    public $btn_hover_color = '#13c392';
    public $btn_text_hover_color = '#ffffff';
    
    public $btn_border_color = '#21272c';
    public $btn_border_hover_color = '#0fc392';
    
    public $btn_align = 'left';
    

    public function button_render() {
            $settings = $this->get_settings_for_display();

            $has_button_link = ! empty( $settings['btn_link']['url'] );
            
            if( ! $has_button_link ) {
                return;
            }
            
            $this->add_link_attributes( 'btn_link', $settings['btn_link'] );
            $this->add_render_attribute( 'btn_link', 'class', 'elementor-button-link' );

            
            
            $btn_class = ! empty( $settings['btn_class'] ) ? $settings['btn_class'] : '';

            if( $btn_class ) {
                $this->add_render_attribute( 'btn_link', 'class', $btn_class );
            }
            
            
            $this->add_render_attribute( 'btn_wrapper', 'class', 'btn-wrapper' );
            
            $this->add_render_attribute( 'btn_link', 'class', 'elementor-button' );
            $this->add_render_attribute( 'btn_link', 'class', 'btn-size-' . $settings['btn_size'] );
            $this->add_render_attribute( 'btn_link', 'class', 'ua-button' );
            $this->add_render_attribute( 'btn_link', 'role', 'button' );
            
            
            
            $migrated = isset( $settings['__fa4_migrated']['btn_icon'] );
            $is_new = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();

            if ( ! $is_new && empty( $settings['btn_icon_align'] ) ) {
                    // @todo: remove when deprecated
                    // added as bc in 2.6
                    //old default
                    $settings['btn_icon_align'] = $this->get_settings( 'btn_icon_align' );
            }

            $this->add_render_attribute( [
                    'content-wrapper' => [
                            'class' => 'elementor-button-content-wrapper',
                    ],
                    'icon-align' => [
                            'class' => [
                                    'elementor-button-icon',
                                    'elementor-align-icon-' . $settings['btn_icon_align'],
                            ],
                    ],
                    'btn_text' => [
                            'class' => 'elementor-button-text',
                    ],
            ] );

            
            //Inline Editing of Button Text
            $this->add_inline_editing_attributes( 'btn_text' );
            ?>

        <div <?php echo $this->get_render_attribute_string( 'btn_wrapper' ); ?>>
                <a <?php echo $this->get_render_attribute_string( 'btn_link' ); ?>>
                    <span <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
                            <?php if ( ! empty( $settings['icon'] ) || ! empty( $settings['btn_icon']['value'] ) ) : ?>
                            <span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
                                    <?php if ( $is_new || $migrated ) :
                                            Icons_Manager::render_icon( $settings['btn_icon'], [ 'aria-hidden' => 'true' ] );
                                    else : ?>
                                            <i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
                                    <?php endif; ?>
                            </span>
                            <?php endif; ?>
                            <span <?php echo $this->get_render_attribute_string( 'btn_text' ); ?>><?php echo $settings['btn_text']; ?></span>
                    </span>
                </a>
        </div>

            
            <?php
    }
    
    public function button_register_controls(){
        $this->button_content_control();
        $this->button_style_control();
    }

    public function button_style_control(){
        $this->start_controls_section(
                    'btn_section_style',
                    [
                            'label' => __( 'Button', 'ultraaddons' ),
                            'tab' => Controls_Manager::TAB_STYLE,
                    ]
            );
            if( $this->get_name() == 'ultraaddons-button' ){
            $this->add_responsive_control(
                    'btn_align',
                    [
                            'label' => __( 'Alignment', 'ultraaddons' ),
                            'type' => Controls_Manager::CHOOSE,
                            'options' => [
                                    'left'    => [
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
                                    'justify' => [
                                            'title' => __( 'Justified', 'ultraaddons' ),
                                            'icon' => 'eicon-text-align-justify',
                                    ],
                            ],
                            'prefix_class' => 'elementor%s-align-',
                            'default' => $this->btn_align,//'left',
                    ]
            );
            }
            $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                            'name' => 'btn_typography',
                            'label' => 'Typography',
                            'selector' => '{{WRAPPER}} .elementor-button',
                            'global' => [
                                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                            ],

                    ]
            );
            
            $this->start_controls_tabs( 'tabs_btn_style' );

            $this->start_controls_tab(
                    'tab_btn_normal',
                    [
                            'label' => __( 'Normal', 'ultraaddons' ),
                    ]
            );
            
            $this->add_control(
                    'btn_text_color',
                    [
                            'label' => __( 'Text Color', 'ultraaddons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#21272c',
                            'selectors' => [
                                    '{{WRAPPER}} .btn-wrapper .ua-button.elementor-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
                            ],
                    ]
            );
            

            $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .btn-wrapper .ua-button',
                                'separator' => 'before',
				'fields_options' => [
					'background' => [
						'frontend_available' => true,
					],
					'color' => [
						'dynamic' => [],
					],
					'color_b' => [
						'dynamic' => [],
					],
				],
			]
		);
            
            $this->add_control(
                    'btn_border_type',
                    [
                            'label' => _x( 'Border Type', 'Border Control', 'ultraaddons' ),
                            'type' => Controls_Manager::SELECT,
                            'separator' => 'before',
                            'options' => [
                                    '' => __( 'None', 'ultraaddons' ),
                                    'solid' => _x( 'Solid', 'Border Control', 'ultraaddons' ),
                                    'double' => _x( 'Double', 'Border Control', 'ultraaddons' ),
                                    'dotted' => _x( 'Dotted', 'Border Control', 'ultraaddons' ),
                                    'dashed' => _x( 'Dashed', 'Border Control', 'ultraaddons' ),
                            ],
                            'default' => 'solid',
                            'selectors' => [
                                    '{{SELECTOR}} .btn-wrapper .ua-button.elementor-button' => 'border-style: {{VALUE}};',
                            ],
                    ]
            );
            
            $this->add_control(
                    'btn_border_color',
                    [
                            'label' => __( 'Border Color', 'ultraaddons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => $this->btn_border_color,
//                            'default' => '#21272C',
                            'selectors' => [
                                    '{{WRAPPER}} .btn-wrapper .ua-button.elementor-button' => 'border-color: {{VALUE}};',
                            ],
                    ]
            );
            
            $this->add_control(
                    'btn_border_width',
                    [
                            'label' => __( 'Border Width', 'ultraaddons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'default' => [
                                    'top' => 2,
                                    'right' => 2,
                                    'bottom' => 2,
                                    'left' => 2,
                                    'unit' => 'px',
                            ],
                            'selectors' => [
                                    '{{WRAPPER}} .btn-wrapper .ua-button.elementor-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                    ]
            );

            $this->add_control(
                    'btn_border_radius',
                    [
                            'label' => __( 'Border Radius', 'ultraaddons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors' => [
                                    '{{WRAPPER}} .btn-wrapper .ua-button.elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                    ]
            );
              $this->add_responsive_control(
                'btn_padding',
                [
                        'label' => __( 'Padding', 'ultraaddons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%' ],

                        'selectors' => [
                                '{{WRAPPER}} .btn-wrapper .ua-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ]
        );

        $this->add_responsive_control(
                'btn_margin',
                [
                        'label' => __( 'Margin', 'ultraaddons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%' ],

                        'selectors' => [
                                '{{WRAPPER}} .btn-wrapper .ua-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ]
        );
            
//            $this->add_group_control(
//                    Group_Control_Border::get_type(),
//                    [
//                            'name' => 'btn_border',
//                            'label' => __( 'Box Shadow', 'ultraaddons' ),
//                            'selector' => '{{WRAPPER}} .btn-wrapper .ua-button.elementor-button',
//                    ]
//            );
            
            $this->add_group_control(
                    Group_Control_Box_Shadow::get_type(),
                    [
                            'name' => 'btn_box_shadow',
                            'label' => __( 'Box Shadow', 'ultraaddons' ),
                            'selector' => '{{WRAPPER}} .btn-wrapper .ua-button.elementor-button',
                    ]
            );
            
            $this->end_controls_tab();
            
            $this->start_controls_tab(
                    'tab_btn_hover',
                    [
                            'label' => __( 'Hover', 'ultraaddons' ),
                    ]
            );

            $this->add_control(
                    'btn_hover_color',
                    [
                            'label' => __( 'Text Color', 'ultraaddons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' =>  $this->btn_text_hover_color,// '#FFF',
                            'selectors' => [
                                    '{{WRAPPER}}:hover .btn-wrapper .ua-button.elementor-button, {{WRAPPER}} .btn-wrapper .ua-button.elementor-button:focus' => 'color: {{VALUE}};',
                                    '{{WRAPPER}}:hover .btn-wrapper .ua-button.elementor-button svg, {{WRAPPER}} .btn-wrapper .ua-button.elementor-button:focus svg' => 'fill: {{VALUE}};',
                            ],
                    ]
            );

            
            
            $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_background_hover_color',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}}:hover .btn-wrapper .ua-button,{{WRAPPER}} .btn-wrapper .ua-button:focus,{{WRAPPER}} .btn-wrapper .ua-button:active',
                                'separator' => 'before',
				'fields_options' => [
					'background' => [
						'frontend_available' => true,
					],
					'color' => [
						'dynamic' => [],
					],
					'color_b' => [
						'dynamic' => [],
					],
				],
			]
		);
            
            $this->add_control(
                    'btn_hover_border_type',
                    [
                            'label' => _x( 'Border Type', 'Border Control', 'ultraaddons' ),
                            'type' => Controls_Manager::SELECT,
                            'options' => [
                                    '' => __( 'None', 'ultraaddons' ),
                                    'solid' => _x( 'Solid', 'Border Control', 'ultraaddons' ),
                                    'double' => _x( 'Double', 'Border Control', 'ultraaddons' ),
                                    'dotted' => _x( 'Dotted', 'Border Control', 'ultraaddons' ),
                                    'dashed' => _x( 'Dashed', 'Border Control', 'ultraaddons' ),
                            ],
                            'default' => 'solid',
                            'selectors' => [
                                    '{{WRAPPER}}:hover .btn-wrapper .ua-button.elementor-button' => 'border-style: {{VALUE}};',
                                    '{{WRAPPER}} .btn-wrapper .ua-button.elementor-button:focus' => 'border-style: {{VALUE}};',
                            ],
                            'separator' => 'before',
                    ]
            );
            $this->add_control(
                    'btn_hover_border_color',
                    [
                            'label' => __( 'Border Color', 'ultraaddons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => $this->btn_border_hover_color,
                            'selectors' => [
                                    '{{WRAPPER}}:hover .btn-wrapper .ua-button.elementor-button' => 'border-color: {{VALUE}};',//{{WRAPPER}} .elementor-button
                                    '{{WRAPPER}} .btn-wrapper .ua-button.elementor-button:focus' => 'border-color: {{VALUE}};',
                            ],
                    ]
            ); 
            
            $this->add_control(
                    'btn_hover_border_width',
                    [
                            'label' => __( 'Border Width', 'ultraaddons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'default' => [
                                    'top' => 2,
                                    'right' => 2,
                                    'bottom' => 2,
                                    'left' => 2,
                                    'unit' => 'px',
                            ],
                            'selectors' => [
                                    '{{WRAPPER}}:hover .btn-wrapper .ua-button.elementor-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                    '{{WRAPPER}} .btn-wrapper .ua-button.elementor-button:focus' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                    ]
            );

            $this->add_control(
                    'btn_hover_border_radius',
                    [
                            'label' => __( 'Border Radius', 'ultraaddons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors' => [
                                    '{{WRAPPER}}:hover .btn-wrapper .ua-button.elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                    '{{WRAPPER}} .btn-wrapper .ua-button.elementor-button:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                    '{{WRAPPER}} .btn-wrapper .ua-button.elementor-button:active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                    ]
            );

//            $this->add_group_control(
//                    Group_Control_Border::get_type(),
//                    [
//                            'name' => 'btn_border_hover_hello',
//                            'label' => __( 'Box Shadow', 'ultraaddons' ),
//                            'selector' => '{{WRAPPER}}:hover .btn-wrapper .ua-button.elementor-button',
//                    ]
//            );

           $this->add_group_control(
                    Group_Control_Box_Shadow::get_type(),
                    [
                            'name' => 'btn_hover_box_shadow',
                            'label' => __( 'Box Shadow', 'ultraaddons' ),
                            'selector' => '{{WRAPPER}}:hover .btn-wrapper .ua-button.elementor-button',
                    ]
            );
                  

            $this->end_controls_tab();

            $this->end_controls_tabs();           
            
            $this->add_control(
                    'btn_class',
                    [
                            'label' => __( 'Button Class', 'ultraaddons' ),
                            'type' => Controls_Manager::TEXT,
                            'default' => '',
                            'separator'=>'before',
                    ]
            );  
            
            $this->end_controls_section();
    }
    /**
     * Control for Button Control
     * for Content Tab
     * 
     * @since 1.0.3.1
     */
    public function button_content_control(){
    $this->start_controls_section(
                'general',
                [
                        'label' => __( 'Button', 'ultraaddons' ),
                ]
        );
    $this->add_control(
                'btn_text',
                [
                        'label' => __( 'Button Text', 'ultraaddons' ),
                        'type' => Controls_Manager::TEXT,
                        'dynamic' => [
                                'active' => true,
                        ],
                        'default' => __( 'Click here', 'ultraaddons' ),
                        'placeholder' => __( 'Click here', 'ultraaddons' ),
                        'separator' => 'before',
                ]
        );

        $this->add_control(
                'btn_link',
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
                ]
        );

        $this->add_control(
                'btn_icon',
                [
                        'label' => __( 'Icon', 'ultraaddons' ),
                        'type' => Controls_Manager::ICONS,
                        'fa4compatibility' => 'icon',
                        'default' => [
                                'value' => 'fas fa-angle-double-right',
                                'library' => 'solid',
                        ],
                ]
        );

        $this->add_control(
                'btn_icon_align',
                [
                        'label' => __( 'Icon Position', 'ultraaddons' ),
                        'type' => Controls_Manager::SELECT,
                        'default' => 'right',
                        'options' => [
                                'left' => __( 'Before', 'ultraaddons' ),
                                'right' => __( 'After', 'ultraaddons' ),
                        ],
                        'condition' => [
                                'btn_icon[value]!' => '',
                        ],
                ]
        );

        $this->add_control(
                'btn_icon_spacing',
                [
                        'label' => __( 'Icon Spacing', 'ultraaddons' ),
                        'type' => Controls_Manager::SLIDER,
                        'range' => [
                                'px' => [
                                        'max' => 100,
                                ],
                        ],
                        'default' => [
                                'unit' => 'px',
                                'size' => 15,
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                        ],
                ]
        );

        $this->add_control(
			'btn_size',
			[
				'label' => __( 'Size', 'ultraaddons' ),
                                'type' => Controls_Manager::SELECT,
                                'default' => 'md',
                                'options' => self::get_button_sizes(),
                                'style_transfer' => true,
//				'condition' => [
//					'btn_text!' => '',
//				],
			]
		);

      

        if( $this->get_name() == 'ultraaddons-button' ){
                $this->add_responsive_control(
                    'btn_inline',
                    [
                            'label' => __( 'Display Inline', 'ultraaddons' ),
                            'type' => Controls_Manager::SWITCHER,
                            'label_on' => __( 'On', 'ultraaddons' ),
                            'label_off' => __( 'Off', 'ultraaddons' ),
                            'return_value' => 'auto',
                            'tablet_default' => '100%',
                            'mobile_default' => '100%',
                            'selectors' => [
                                    '{{WRAPPER}}' => 'width: {{VALUE}};',
                            ],
                            
                    ]
            );
        }
        

        $this->end_controls_section();
    }
    public static function get_button_sizes() {
            return [
                    'xs' => __( 'Extra Small', 'ultraaddons' ),
                    'sm' => __( 'Small', 'ultraaddons' ),
                    'md' => __( 'Medium', 'ultraaddons' ),
                    'lg' => __( 'Large', 'ultraaddons' ),
                    'xl' => __( 'Extra Large', 'ultraaddons' ),
            ];
    }
    
}
