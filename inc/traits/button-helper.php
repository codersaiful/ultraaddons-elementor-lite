<?php
namespace UltraAddons\Traits;

use Elementor\Controls_Manager;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Border;
use Elementor\Utils;

trait Button_Helper{
    
    public function render_button() {
            $settings = $this->get_settings_for_display();

            $has_button_link = ! empty( $settings['btn_link']['url'] );
            
            if( $has_button_link ) {
                $this->add_link_attributes( 'btn_link', $settings['btn_link'] );
                $this->add_render_attribute( 'btn_link', 'class', 'elementor-button-link' );
                
                $this->add_render_attribute( 'infobox_button_wrapper', 'class', 'infobox-button-wrapper' );
            }
            
            $this->add_render_attribute( 'btn_link', 'class', 'elementor-button' );
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
                    'text' => [
                            'class' => 'elementor-button-text',
                    ],
            ] );

            ?>

        <div <?php echo $this->get_render_attribute_string( 'infobox_button_wrapper' ); ?>>
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
                            <span <?php echo $this->get_render_attribute_string( 'text' ); ?>><?php echo $settings['btn_text']; ?></span>
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
                    'section_style',
                    [
                            'label' => __( 'Button', 'ultraaddons' ),
                            'tab' => Controls_Manager::TAB_STYLE,
                    ]
            );
            
            $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                            'name' => 'button_typography',
                            'label' => 'Typography',
                            'selector' => '{{WRAPPER}} .elementor-button',
                            'global' => [
                                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                            ],

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
                    'button_text_color',
                    [
                            'label' => __( 'Text Color', 'ultraaddons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#21272c',
                            'selectors' => [
                                    '{{WRAPPER}} .elementor-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
                            ],
                    ]
            );
            
            $this->add_control(
                    'background_color',
                    [
                            'label' => __( 'Background Color', 'ultraaddons' ),
                            'type' => Controls_Manager::COLOR,
//                            'global' => [
//                                    'default' => Global_Colors::COLOR_ACCENT,
//                            ],
                            'default' => '#fff',
                            'selectors' => [
                                    '{{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
                            ],
                    ]
            );
            
            $this->add_control(
                    'button_border_type',
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
                                    '{{SELECTOR}} .elementor-button' => 'border-style: {{VALUE}};',
                            ],
                    ]
            );
            
            $this->add_control(
                    'button_border_color',
                    [
                            'label' => __( 'Color', 'ultraaddons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#21272C',
                            'selectors' => [
                                    '{{WRAPPER}} .elementor-button' => 'border-color: {{VALUE}};',
                            ],
                    ]
            );
            
            $this->add_control(
                    'button_border_width',
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
                                    '{{WRAPPER}} .elementor-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                    ]
            );

            $this->add_control(
                    'button_border_radius',
                    [
                            'label' => __( 'Border Radius', 'ultraaddons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors' => [
                                    '{{WRAPPER}} .elementor-button, {{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    'hover_color',
                    [
                            'label' => __( 'Text Color', 'ultraaddons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#FFF',
                            'selectors' => [
                                    '{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'color: {{VALUE}};',
                                    '{{WRAPPER}} .elementor-button:hover svg, {{WRAPPER}} .elementor-button:focus svg' => 'fill: {{VALUE}};',
                            ],
                    ]
            );

            $this->add_control(
                    'button_background_hover_color',
                    [
                            'label' => __( 'Background Color', 'ultraaddons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#0FC392',
                            'selectors' => [
                                    '{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'background-color: {{VALUE}};',
                            ],
                    ]
            );
            
            $this->add_control(
                    'button_hover_border_type',
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
                                    '{{WRAPPER}} .elementor-button:hover' => 'border-style: {{VALUE}};',
                                    '{{WRAPPER}} .elementor-button:focus' => 'border-style: {{VALUE}};',
                            ],
                    ]
            );
            
            $this->add_control(
                    'button_hover_border_width',
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
                                    '{{WRAPPER}} .elementor-button:hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                    '{{WRAPPER}} .elementor-button:focus' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                    ]
            );

            $this->add_control(
                    'button_hover_border_radius',
                    [
                            'label' => __( 'Border Radius', 'ultraaddons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors' => [
                                    '{{WRAPPER}} .elementor-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                    '{{WRAPPER}} .elementor-button:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                    ]
            );

            $this->add_control(
                    'button_hover_border_color',
                    [
                            'label' => __( 'Border Color', 'ultraaddons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#0FC392',
                            'selectors' => [
                                    '{{WRAPPER}} .elementor-button:hover' => 'border-color: {{VALUE}};',
                                    '{{WRAPPER}} .elementor-button:focus' => 'border-color: {{VALUE}};',
                            ],
                    ]
            );            

            $this->end_controls_tab();

            $this->end_controls_tabs();           
            
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
                                        'max' => 50,
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



        $this->end_controls_section();

        $this->start_controls_section(
                'section_style_general',
                [
                        'label' => __( 'General', 'ultraaddons' ),
                        'tab'   => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_responsive_control(
                'infobox_padding',
                [
                        'label' => __( 'Padding', 'ultraaddons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%' ],
                        'default'   => [
                                'top' => 50,
                                'bottom' => 50,
                                'left' => 50,
                                'right' => 50,
                                'unit' => 'px',
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .mc-info-box-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ]
        );


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