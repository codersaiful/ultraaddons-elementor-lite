<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Button extends Base{
    

    
    
    /**
     * Widget Icon.
     *
     * Holds the Repeater counter data. Default is `0`.
     *
     * @since 1.0.0
     * @static
     *
     * @var int Widget Icon.
     */
    public function get_icon() {
        return 'medilac eicon-button';
    }
    
    /**
     * Get your widget name
     *
     * Retrieve oEmbed widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string keywords
     */
    public function get_keywords() {
        return [ 'medilac', 'button', 'btn', 'bt', 'recent content' ];
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
        $this->style_design_controls();
        
        //For Typography Section Style Tab
        $this->style_typography_controls();

       
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

        $this->add_render_attribute( 'wrapper', 'class', 'medilac-button-wrapper' );
        
        $buttons = isset( $settings[ 'mc_button' ] ) ? $settings[ 'mc_button' ] : [];
        if( count($buttons) < 1 ){
            return;
        }
        
        $this->add_render_attribute( 'wrapper', 'class', $settings['button_type'] );
        
        
        $text = ! empty( $settings['text'] ) ? $settings['text'] : __( 'Click Here', 'medilac' );
        
        
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <?php foreach( $buttons as $key => $button ) : 
//                var_dump($button);
                $_id = !empty( $button['_id'] ) ? $button['_id'] : false;
                $text = !empty( $button['text'] ) ? $button['text'] : false;
                $icon = !empty( $button['icon']['value'] ) ? $button['icon']['value'] : false;
                $position = !empty( $button['icon_position'] ) ? $button['icon_position'] : false;
                $this->add_render_attribute( $_id, 'class', 'medilac-button' );
                $this->add_render_attribute( $_id, 'class', 'elementor-repeater-item-' . $_id );
                $this->add_render_attribute( $_id . '_icon', 'class', 'medilac-button-icon align-' . $position );
                
                if ( ! empty( $button['link']['url'] ) ) {
                        
                        $this->add_link_attributes( $_id, $button['link'] );
                        $this->add_render_attribute( $_id, 'class', 'medilac-button-link' );
                        $this->add_render_attribute( $_id, 'class', $settings['size'] );
                        $this->add_render_attribute( $_id, 'role', 'button' );
                        
                }
                $icon_html = '';
                if( $icon ){
                    
                        $icon_html .= '<span '. $this->get_render_attribute_string( $_id . '_icon' ) .'>';
                        $icon_html .= '<i class="'. esc_attr( $icon ) .'"></i>';
                        $icon_html .= '</span>';
                }
                ?>
            
            <a <?php echo $this->get_render_attribute_string( $_id ); ?>>
                <?php echo $icon_html;?>
                <span class="medilac-button-text"><?php echo esc_html( $text );?></span>
            </a>
            
            <?php endforeach; ?>
        </div>
        <?php

    }
    
    protected function _content_template() {
        /*
        ?>
        <#
        view.addInlineEditingAttributes( 'avd_heading', 'none' );
        view.addInlineEditingAttributes( 'avd_sub_heading', 'none' );
        #>
        
        <div class="advance-heading-wrapper">
            <span {{{ view.getRenderAttributeString( 'avd_sub_heading' ) }}}>{{{ settings.avd_sub_heading }}}</span>
            <h4 class="heading-tag" {{{ view.getRenderAttributeString( 'avd_heading' ) }}}>{{{ settings.avd_heading }}}</h4>
        </div>
        <?php
        */
    }
    
    /**
     * Get button sizes.
     *
     * Retrieve an array of button sizes for the button widget.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @return array An array containing button sizes.
     */
    public static function get_button_sizes() {
            return [
                    'xs' => __( 'Extra Small', 'medilac' ),
                    'sm' => __( 'Small', 'medilac' ),
                    'md' => __( 'Medium', 'medilac' ),
                    'lg' => __( 'Large', 'medilac' ),
                    'xl' => __( 'Extra Large', 'medilac' ),
            ];
    }
    
    /**
     * General Section for Content Controls
     * 
     * @since 1.0.0.9
     */
    protected function content_general_controls() {
        $this->start_controls_section(
                'section_button',
                [
                        'label' => __( 'Button', 'medilac' ),
                ]
        );
        
        $this->add_control(
                'button_type',
                [
                        'label' => __( 'Type', 'medilac' ),
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
                                'fullwidth' => [
                                        'title' => __( 'Fullwidth', 'medilac' ),
                                        'icon' => 'eicon-text-align-justify',
                                ],
                        ],
                        'default' => 'left',
                        'toggle'    => false,
                
                ]
        );
        
        $this->add_control(
                'size',
                [
                        'label' => __( 'Size', 'medilac' ),
                        'type' => Controls_Manager::SELECT,
                        'default' => 'md',
                        'options' => self::get_button_sizes(),
                        'style_transfer' => true,
                ]
        );
        
        $repeater = new \Elementor\Repeater();
        
        $repeater->add_control(
                'icon',
                [
                        'label' => __( 'Icon', 'medilac' ),
                        'type' => Controls_Manager::ICONS,
                ]
        );
        
        $repeater->add_control(
                'icon_position',
                [
                        'label' => __( 'Icon', 'medilac' ),
                        'type' => Controls_Manager::SELECT,
                        'options' => [
                            'left'     => __( 'Left', 'medilac' ),
                            'right'     => __( 'Right', 'medilac' ),
                    ],
                    'default' => 'left',
                        
                ]
        );
        
        $repeater->add_control(
                'text_divider',
                [
                        'type' => Controls_Manager::DIVIDER,
                ]
        );
        
        $repeater->add_control(
                'text',
                [
                        'label' => __( 'Text', 'medilac' ),
                        'type' => Controls_Manager::TEXT,
                        'dynamic' => [
                                'active' => true,
                        ],
                        'default' => __( 'Click here', 'medilac' ),
                        'placeholder' => __( 'Click here', 'medilac' ),
                ]
        );

        $repeater->add_control(
                'link',
                [
                        'label' => __( 'Link', 'medilac' ),
                        'type' => Controls_Manager::URL,
                        'dynamic' => [
                                'active' => true,
                        ],
                        'placeholder' => __( 'https://your-link.com', 'medilac' ),
                        'default' => [
                                'url' => '#',
                        ],
                ]
        );
        
        $repeater->start_controls_tabs( 'button_hover_controls' );
        $repeater->start_controls_tab(
            'tab_button_content_normal',
            [
                'label'  => esc_html__( 'Normal', 'medilac' )
            ]
        );
        
        $repeater->add_control(
                'text_color',
                [
                        'label' => __( 'Text Color', 'medilac' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                                '{{WRAPPER}} {{CURRENT_ITEM}}.medilac-button .medilac-button-icon' => 'color: {{VALUE}}',
                                '{{WRAPPER}} {{CURRENT_ITEM}}.medilac-button .medilac-button-text' => 'color: {{VALUE}}',
                        ],
                ]
        );
        $repeater->add_control(
                'bg_color',
                [
                        'label' => __( 'Background', 'medilac' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                                '{{WRAPPER}} .medilac-button-wrapper {{CURRENT_ITEM}}.medilac-button' => 'background-color: {{VALUE}}',
                        ],
                ]
        );
        
        $repeater->add_group_control(
                Group_Control_Border::get_type(),
                [
                        'name' => 'border',
                        'label' => __( 'Border', 'medilac' ),
                        'selector' => '{{WRAPPER}} .medilac-button-wrapper {{CURRENT_ITEM}}.medilac-button',
                ]
        );
        
        $repeater->add_control(
                'border_radius',
                [
                        'label' => __( 'Border Radius', 'medilac' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px' ],
                        'selectors' => [
                                '{{WRAPPER}} .medilac-button-wrapper {{CURRENT_ITEM}}.medilac-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ]
        );
        
        $repeater->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                        'name' => 'box_shadow',
                        'label' => __( 'Box Shadow', 'medilac' ),
                        'selector' => '{{WRAPPER}} .medilac-button-wrapper {{CURRENT_ITEM}}.medilac-button',
                ]
        );
        
        $repeater->end_controls_tab();
        
        $repeater->start_controls_tab(
            'icon_boxes_btn_content_hover',
            [
                'label' => esc_html__( 'Hover', 'medilac' ),
            ]
        );
        
        $repeater->add_control(
                'text_color_hover',
                [
                        'label' => __( 'Text Color', 'medilac' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                                '{{WRAPPER}} {{CURRENT_ITEM}}:hover .medilac-button-icon' => 'color: {{VALUE}}',
                                '{{WRAPPER}} {{CURRENT_ITEM}}:hover  .medilac-button-text' => 'color: {{VALUE}}',
                        ],
                ]
        );
        $repeater->add_control(
                'bg_color_hover',
                [
                        'label' => __( 'Background', 'medilac' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                                '{{WRAPPER}} .medilac-button-wrapper {{CURRENT_ITEM}}.medilac-button:hover' => 'background-color: {{VALUE}}',
                        ],
                ]
        );
        
        $repeater->add_group_control(
                Group_Control_Border::get_type(),
                [
                        'name' => 'border_hover',
                        'label' => __( 'Border', 'medilac' ),
                        'selector' => '{{WRAPPER}} .medilac-button-wrapper {{CURRENT_ITEM}}.medilac-button:hover',
                ]
        );
        
        $repeater->add_control(
                'border_radius_hover',
                [
                        'label' => __( 'Border Radius', 'medilac' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px' ],
                        'selectors' => [
                                '{{WRAPPER}} .medilac-button-wrapper {{CURRENT_ITEM}}.medilac-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ]
        );
        
        $repeater->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                        'name' => 'box_shadow_hover',
                        'label' => __( 'Box Shadow', 'medilac' ),
                        'selector' => '{{WRAPPER}} .medilac-button-wrapper {{CURRENT_ITEM}}.medilac-button:hover',
                ]
        );
        
        $repeater->end_controls_tab();
        
        $repeater->end_controls_tabs();
        
        $this->add_control(
                'mc_button',
                [
                        'type' => Controls_Manager::REPEATER,
                        'fields' => $repeater->get_controls(),
                        'default' => [
                                [
                                        'size' => 'md',
                                        'text' => __( 'Click Here', 'medilac' ),
                                        'link'   =>  [
                                            'url' => '#ee'
                                        ],
                                ],   
                        ],
                        'title_field' => '{{{ text }}}',
                ]
        );
        
        
        $this->end_controls_section();
    }
    
    /**
     * Finding Category list as Array
     * What to show as Option for Query Section
     * 
     * @since 1.0.0.9
     */
    protected function get_cat_as_options(){
        $args = [
            'orderby'   =>  'count',
            'hide_empty'=>  0
        ];
        $categories = get_terms( 'category', $args );
        
        $options = [];
        if( is_array( $categories ) && count( $categories ) > 0 ){
            foreach( $categories as $category ){
                $options[$category->term_id]  = $category->name;
            }
        }

        return $options;
    }
    
    /**
     * Alignment Section for Style Tab
     * 
     * @since 1.0.0.9
     */
    protected function style_design_controls() {
        $this->start_controls_section(
            'design',
            [
                'label'     => esc_html__( 'Design', 'medilac' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'icon_space',
            [
                    'label'     => __( 'Icon Gap', 'medilac' ),
                    'type'      => Controls_Manager::SLIDER,
                    'default' => [
                                    'size' => 20,
                            ],
                            'range' => [
                                    'px' => [
                                            'min' => 0,
                                            'max' => 100,
                                    ],
                            ],
                            'selectors' => [
                                    '{{WRAPPER}} .medilac-button .medilac-button-icon.align-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                                    '{{WRAPPER}} .medilac-button .medilac-button-icon.align-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                            ],
            ]
        );
        
        $this->add_control(
            'button_space',
            [
                    'label'     => __( 'Button Gap', 'medilac' ),
                    'type'      => Controls_Manager::SLIDER,
                    'default' => [
                                    'size' => 20,
                            ],
                            'range' => [
                                    'px' => [
                                            'min' => 0,
                                            'max' => 100,
                                    ],
                            ],
                            'selectors' => [
                                    '{{WRAPPER}} .medilac-button-wrapper' => 'column-gap: {{SIZE}}{{UNIT}};',
                            ],
            ]
        );
        
        $this->start_controls_tabs( 'button_hover_controls_master' );
        $this->start_controls_tab(
            'tab_button_content_normal_master',
            [
                'label'  => esc_html__( 'Normal', 'medilac' )
            ]
        );
        
        $this->add_control(
            'bg_color_master',
            [
                'label'     => __( 'Background', 'medilac' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .medilac-button-wrapper .medilac-button' => 'background-color: {{VALUE}}',
                ],
                'default'   => '#0FC392',
            ]
        );
        
        
        
        $this->add_control(
                'text_color_master',
                [
                        'label' => __( 'Text Color', 'medilac' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                                '{{WRAPPER}} .medilac-button .medilac-button-icon' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .medilac-button .medilac-button-text' => 'color: {{VALUE}}',
                        ],
                ]
        );
        
        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                        'name' => 'border_master',
                        'label' => __( 'Border', 'medilac' ),
                        'selector' => '{{WRAPPER}} .medilac-button-wrapper .medilac-button',
                ]
        );
        
        $this->add_control(
                'border_radius_master',
                [
                        'label' => __( 'Border Radius', 'medilac' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px' ],
                        'selectors' => [
                                '{{WRAPPER}} .medilac-button-wrapper .medilac-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ]
        );
        
        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                        'name' => 'box_shadow_master',
                        'label' => __( 'Box Shadow', 'medilac' ),
                        'selector' => '{{WRAPPER}} .medilac-button-wrapper .medilac-button',
                ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'tab_button_content_hover_master',
            [
                'label'  => esc_html__( 'Hover', 'medilac' )
            ]
        );
        
        $this->add_control(
            'bg_color_hover_master',
            [
                'label'     => __( 'Background', 'medilac' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .medilac-button-wrapper .medilac-button:hover' => 'background-color: {{VALUE}}',
                ],
                'default'   => '#FFF',
            ]
        );
        
        
        
        $this->add_control(
                'text_color_hover_master',
                [
                        'label' => __( 'Text Color', 'medilac' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                                '{{WRAPPER}} .medilac-button:hover .medilac-button-icon' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .medilac-button:hover .medilac-button-text' => 'color: {{VALUE}}',
                        ],
                ]
        );
        
        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                        'name' => 'border_hover_master',
                        'label' => __( 'Border', 'medilac' ),
                        'selector' => '{{WRAPPER}} .medilac-button-wrapper .medilac-button:hover',
                ]
        );
        
        $this->add_control(
                'border_radius_hover_master',
                [
                        'label' => __( 'Border Radius', 'medilac' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px' ],
                        'selectors' => [
                                '{{WRAPPER}} .medilac-button-wrapper .medilac-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ]
        );
        
        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                        'name' => 'box_shadow_hover_master',
                        'label' => __( 'Box Shadow', 'medilac' ),
                        'selector' => '{{WRAPPER}} .medilac-button-wrapper .medilac-button:hover',
                ]
        );
        
        $this->end_controls_tab();
        
        
        $this->end_controls_tabs();
        
        
        
        
        $this->end_controls_section();
    }
    
    /**
     * Typography Section for Style Tab
     * 
     * @since 1.0.0.9
     */
    protected function style_typography_controls() {
        $this->start_controls_section(
            'mc_rc_typography',
            [
                'label'     => esc_html__( 'Typography', 'medilac' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'typography',
                        'global' => [
                                'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                        ],
                        'selector' => '{{WRAPPER}} .medilac-button',
                ]
        );
        
        $this->end_controls_section();
    }
    
    
}