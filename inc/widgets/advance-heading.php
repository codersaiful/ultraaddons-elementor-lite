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

class Advance_Heading extends Base{
    
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
        return [ 'ultraaddons', 'heading', 'header', 'title' ];
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
        
        // Advance Heading Style
        //$this->register_general_style();
        // Register pricing style
        //$this->register_general_controls();
        // General Settings
        //$this->register_heading_align_style();
        // Register Sub Heading
        //$this->register_sub_heading_style_controls();
        // Heading Style
        //$this->register_heading_style_controls();
        
        
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
        $settings           = $this->get_settings_for_display();
        //var_dump($settings['avd_heading_alignment']);
        
        //$this->add_inline_editing_attributes( 'avd_heading', 'basic' );
        $this->add_inline_editing_attributes( 'avd_heading', 'none' );
        $this->add_render_attribute( 'avd_heading', 'class', 'heading-tag' );
        $this->add_render_attribute( 'avd_sub_heading', 'class', 'sub-heading-wrapper' );

        $this->add_inline_editing_attributes( 'avd_sub_heading', 'none' );
        
        if( ! isset( $settings['avd_heading'] ) || ! isset( $settings['avd_sub_heading'] ) ){
            return;
        }

        $alignment = !empty( $settings['avd_heading_alignment'] ) ? $settings['avd_heading_alignment'] : 'left';
        ?>
        <div class="advance-heading-wrapper <?php echo esc_attr( $alignment ); ?>" >
            <?php if( ! empty( $settings['avd_sub_heading'] ) ){ ?>
            <span <?php echo $this->get_render_attribute_string( 'avd_sub_heading' ); ?>>
                <span class="spb"><?php echo wp_kses_post( $settings['avd_sub_heading'] ); ?></span>
            </span>
            <?php
            }
            if( ! empty( $settings['avd_heading'] ) ){
            ?>
            <h4 <?php echo $this->get_render_attribute_string( 'avd_heading' ); ?>>
                <?php echo wp_kses_post( $settings['avd_heading'] ); ?>
            </h4>
            <?php } ?>
        </div>    
        <?php
        
    }
    
        
    /**
     * General Section for Content Controls
     * 
     * @since 1.0.0.9
     */
    protected function content_general_controls() {
        $this->start_controls_section(
            'avd_general_content',
            [
                'label'     => esc_html__( 'General', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'avd_heading',
                [
                    'label'         => esc_html__( 'Heading', 'ultraaddons' ),
                    'type'          => Controls_Manager::TEXT,
                    'placeholder'   => __( 'Lorem Ipsum is simply dummy text', 'ultraaddons' ),
                    'default'       => __( 'Lorem Ipsum is simply dummy text', 'ultraaddons' ),
                    'label_block'   => TRUE,
                    'dynamic'       => ['active' => true],
                ]
        );
        
        $this->add_control(
            'avd_sub_heading',
                [
                    'label'         => esc_html__( 'Sub Heading', 'ultraaddons' ),
                    'type'          => Controls_Manager::TEXT,
                    'placeholder'   => __( 'About Heading', 'ultraaddons' ),
                    'default'       => __( 'About Heading', 'ultraaddons' ),
                    'label_block'   => TRUE,
                    'dynamic'       => ['active' => true],
                ]
        );
        
        $this->end_controls_section();
    }
    
    /**
     * Alignment Section for Style Tab
     * 
     * @since 1.0.0.9
     */
    protected function style_design_controls() {
        $this->start_controls_section(
            'avd_heading_design_style',
            [
                'label'     => esc_html__( 'Design', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'avd_heading_alignment',
                [
                    'label'         => esc_html__( 'Heading', 'ultraaddons' ),
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
                    'toggle' => true,
                ]
        );
        
        
        $this->add_control(
            'avd_heading_color',
            [
                'label'     => __( 'Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .advance-heading-wrapper span.sub-heading-wrapper' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .advance-heading-wrapper span.sub-heading-wrapper:after,{{WRAPPER}} .advance-heading-wrapper span.sub-heading-wrapper:before' => 'background-color: {{VALUE}}',
                ],
                'default'   => '#0fc392',
            ]
        );
        
        $this->add_control(
            'avd_sub_heading_color',
            [
                'label'     => __( 'Heading Text Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .advance-heading-wrapper .heading-tag' => 'color: {{VALUE}}',
                ],
                'default'   => '#021429',
            ]
        );
        
        
        $this->add_responsive_control(
                'avd_head_h_space',
                [
                        'label' => __( 'Horizontal Spacing', 'ultraaddons' ),
                        'type' => Controls_Manager::SLIDER,
                        'default' => [
                                'size' => 0,
                        ],
                        'range' => [
                                'px' => [
                                        'min' => 0,
                                        'max' => 100,
                                ],
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .advance-heading-wrapper .heading-tag' => 'padding-top: {{SIZE}}{{UNIT}};',
                        ],
                ]
        );
        
        $this->add_responsive_control(
                'avd_head_v_space',
                [
                        'label' => __( 'Vertical Spacing', 'ultraaddons' ),
                        'type' => Controls_Manager::SLIDER,
                        'default' => [
                                'size' => 0,
                        ],
                        'range' => [
                                'px' => [
                                        'min' => 0,
                                        'max' => 100,
                                ],
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .advance-heading-wrapper span.sub-heading-wrapper span.spb' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
                        ],
                ]
        );
        
        
        
        $this->add_responsive_control(
                'avd_line_height',
                [
                        'label' => __( 'Line Width', 'ultraaddons' ),
                        'type' => Controls_Manager::SLIDER,
                        'default' => [
                                'size' => 2,
                        ],
                        'range' => [
                                'px' => [
                                        'min' => 0,
                                        'max' => 50,
                                ],
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .advance-heading-wrapper.right span:before,{{WRAPPER}} .advance-heading-wrapper.center span:before,{{WRAPPER}} .advance-heading-wrapper span.sub-heading-wrapper:after' => 'height: {{SIZE}}{{UNIT}};',
                        ],
                ]
        );
        
        $this->add_responsive_control(
                'avd_line_length',
                [
                        'label' => __( 'Line Length', 'ultraaddons' ),
                        'type' => Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%' ],
                        'default' => [
                                'size' => 30,
                                'unit' => '%'
                        ],
                        'range' => [
                                'px' => [
                                        'min' => 0,
                                        'max' => 500,
                                ],
                                '%' => [
                                        'min' => 0,
                                        'max' => 100,
                                ],
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .advance-heading-wrapper span.sub-heading-wrapper:after, {{WRAPPER}} .advance-heading-wrapper span.sub-heading-wrapper:before' => 'width: {{SIZE}}{{UNIT}};',
                        ],
                ]
        );
        
        
        
        
        $this->end_controls_section();
    }
    
    /**
     * Typography Section for Style Tab
     * 
     * @since 1.0.0.9
     */
    protected function style_typography_controls() {
        $this->start_controls_section(
            'mc_avd_heading_typography',
            [
                'label'     => esc_html__( 'Typography', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'title_typography',
                        'label' => 'Heading Typography',
                        'selector' => '{{WRAPPER}} .advance-heading-wrapper .heading-tag',
                        'global' => [
                                'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                        ],

                ]
        );
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'subhead_typography',
                        'label' => 'Sub Heading Typography',
                        'selector' => '{{WRAPPER}} .advance-heading-wrapper span.sub-heading-wrapper',
                        'global' => [
                                'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                        ],

                ]
        );
        
        
        $this->end_controls_section();
    }
    
    /**
     * Register Icon Box General Controls.
     * 
     * @access protected
     * 
     * @since 1.0.0
     */
    protected function register_general_style(){
        $this->start_controls_section(
            'heading_style_settings',
            [
                'label'     => esc_html__( 'General', 'ultraaddons' ),
            ]
        );
        $this->add_control(
            'heading_style',
            [
                'label'     => esc_html__( 'Heading Style', 'ultraaddons' ),
                'type'      => Controls_Manager::SELECT,
                'label_block'   => true,
                'options'       => [
                    '1'         => esc_html__( 'Style 01', 'ultraaddons' ),
                    '2'         => esc_html__( 'Style 02', 'ultraaddons' ),
                ],
                'default'       => '1',
            ]
        );
        $this->end_controls_section();
    }
    
    /**
     * Register Price Table General Controls.
     * 
     * @access protected
     * 
     * @since 1.0.0
     */
    protected function register_general_controls(){
        $this->start_controls_section(
            'advance_heading_settings',
            [
                'label'     => esc_html__( 'Content', 'ultraaddons' ),
            ]
        );
        $this->add_control(
            'advance_sub_heading',
                [
                    'label'     => esc_html__( 'Sub Heading', 'ultraaddons' ),
                    'type'          => Controls_Manager::TEXT,
                    'placeholder'   => __( 'Our Services', 'ultraaddons' ),
                    'label_block'   => TRUE,
                ]
        );
        $this->add_control(
            'advance_heading',
                [
                    'label'     => esc_html__( 'Heading', 'ultraaddons' ),
                    'type'          => Controls_Manager::TEXTAREA,
                    'label_block'   => TRUE,
                    'placeholder'   => __( 'We Provide Best Services sFor Your Health', 'ultraaddons' ),
                ]
        );
        $this->end_controls_section();
    }
    
    /**
     * Register heading style
     * 
     * @access protected
     * 
     * @since 1.0.0
     */
    protected function register_heading_align_style(){
	    $this->start_controls_section(
	            'advance_heading_general_setting',
                [
                    'label'    => __( 'General Settings', 'ultraaddons' ),
                    'tab'      => Controls_Manager::TAB_STYLE,
                    'show_label' => false,
                ]
        );
        $this->add_responsive_control(
            'heading_general_padding',
            [
                'label'      => __( 'Padding', 'ultraaddons' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .content-title.ultraaddons-advance-heading, .section-title.v1.ultraaddons-advance-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'heading_tag_general_margin',
            [
                'label'      => __( 'Margin', 'ultraaddons' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .content-title.ultraaddons-advance-heading, .section-title.v1.ultraaddons-advance-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'heading_tag_general_text_align',
            [
                'label' => __( 'Alignment', 'ultraaddons' ),
                'type' => Controls_Manager::CHOOSE,
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
                'selectors' => [
                    '{{WRAPPER}} .content-title.ultraaddons-advance-heading, .section-title.v1.ultraaddons-advance-heading' => 'text-align: {{VALUE}}',
                ],
                'default' => '',
                'toggle' => true,
            ]
        );
        $this->end_controls_section();
    }

    /**
     * Register Sub Heading
     * 
     * @access protected
     * 
     * @since 1.0.0
     */
    protected function register_sub_heading_style_controls(){
	    $this->start_controls_section(
	            'advance_sub_heading_style_setting',
                [
                    'label'    => __( 'Sub Heading', 'ultraaddons' ),
                    'tab'      => Controls_Manager::TAB_STYLE,
                    'show_label' => false,
                ]
        );
        $this->add_control(
            'sub_heading_color',
            [
                'label'     => __( 'Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .content-title .ultraaddons-sub-heading.elementor-inline-editing, .section-title.v1.ultraaddons-advance-heading span' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'sub_border_heading_color',
            [
                'label'     => __( 'After Border Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .content-title .ultraaddons-sub-heading.elementor-inline-editing:after, .section-title.v1.ultraaddons-advance-heading span:before, .section-title.v1.ultraaddons-advance-heading span:after' => 'background: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'sub_heading_typography',
                'selector' => '{{WRAPPER}} .content-title .ultraaddons-sub-heading.elementor-inline-editing, .section-title.v1.ultraaddons-advance-heading span',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
            ]
        );
        $this->add_responsive_control(
            'sub_header_padding',
            [
                'label'      => __( 'Padding', 'ultraaddons' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .content-title .ultraaddons-sub-heading.elementor-inline-editing, .section-title.v1.ultraaddons-advance-heading span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'sub_header_margin',
            [
                'label'      => __( 'Margin', 'ultraaddons' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .content-title .ultraaddons-sub-heading.elementor-inline-editing, .section-title.v1.ultraaddons-advance-heading span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'sub_heading_border',
                'label' => __( 'Border', 'ultraaddons' ),
                'selector' => '{{WRAPPER}} .content-title .ultraaddons-sub-heading.elementor-inline-editing, .section-title.v1.ultraaddons-advance-heading span',
            ]
        );
        $this->end_controls_section();
    }
   

    /**
     * Register heading style
     * 
     * @access protected
     * 
     * @since 1.0.0
     */
    protected function register_heading_style_controls(){
	    $this->start_controls_section(
	            'advance_heading_style_setting',
                [
                    'label'    => __( 'Heading', 'ultraaddons' ),
                    'tab'      => Controls_Manager::TAB_STYLE,
                    'show_label' => false,
                ]
        );
        $this->add_control(
            'heading_tag_colors',
            [
                'label'     => __( 'Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .content-title span.ultraaddons-sub-heading, .section-title.v1.ultraaddons-advance-heading h3.ultraaddons-heading' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'heading_tag_typography',
                'selector' => '{{WRAPPER}} .pricing-box-item .pricing-icon .ultraaddons-price-table-heading, .section-title.v1.ultraaddons-advance-heading h3.ultraaddons-heading',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
            ]
        );
        $this->add_responsive_control(
            'heading_tag_padding',
            [
                'label'      => __( 'Padding', 'ultraaddons' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .pricing-box-item .pricing-icon .ultraaddons-price-table-heading, .section-title.v1.ultraaddons-advance-heading h3.ultraaddons-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'heading_tag_margin',
            [
                'label'      => __( 'Margin', 'ultraaddons' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .pricing-box-item .pricing-icon .ultraaddons-price-table-heading, .section-title.v1.ultraaddons-advance-heading h3.ultraaddons-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'heading_tag_border',
                'label' => __( 'Border', 'ultraaddons' ),
                'selector' => '{{WRAPPER}} .pricing-box-item .pricing-icon .ultraaddons-price-table-heading, .section-title.v1.ultraaddons-advance-heading h3.ultraaddons-heading',
            ]
        );
        $this->end_controls_section();
    }
    
    

    /**
     * Get Sub Heading Content.
     * 
     * @access protected
     * 
     * @since 1.0.0
     */
    public function get_sub_heading_content(){
        $settings = $this->get_settings_for_display();
        $advance_sub_heading = isset( $settings['advance_sub_heading'] ) ? $settings['advance_sub_heading'] : '';
        if(!empty( $advance_sub_heading )) : ?> 
            <span class="ultraaddons-sub-heading elementor-inline-editing"><?php echo esc_html( $advance_sub_heading );?></span>
        <?php endif;
    }
    
    /**
     * Get Heading Content.
     * 
     * @access protected
     * 
     * @since 1.0.0
     */
    public function get_heading_content(){
        $settings = $this->get_settings_for_display();
        $advance_heading = isset( $settings['advance_heading'] ) ? $settings['advance_heading'] : '';
        if(!empty( $advance_heading )) : ?> 
            <h3 class="ultraaddons-heading elementor-inline-editing"><?php echo esc_html( $advance_heading );?></h3>
        <?php endif;
    }

}