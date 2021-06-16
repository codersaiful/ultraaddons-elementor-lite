<?php
namespace UltraAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Offer_Card extends Base{
    
    /**
     * Get your widget by keywords
     *
     * @since 1.0.0
     * @access public
     *
     * @return string keywords
     */
    public function get_keywords() {
        return [ 'ultraaddons', 'offer', 'card', 'product card', 'product board', 'buy' ];
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
        //$this->style_typography_controls();

       
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
        
        $this->add_render_attribute( 'wrapper', 'class', 'ua-offer-wrapper' );
        $heading = $settings['heading'];
        $highlighted_title = $settings['highlighted_title'];
        
        if ( ! empty( $settings['link']['url'] ) ) {
                        
                $this->add_link_attributes( 'button', $settings['link'] );
                $this->add_render_attribute( 'button', 'class', 'offer-btn btn v3' );
                $this->add_render_attribute( 'button', 'role', 'button' );

        }
        $button_text = !empty( $settings['text'] ) ? $settings['text'] : false;
        
        $image = !empty( $settings['image']['url'] ) ? $settings['image']['url'] : false;
        $image_position = !empty( $settings['image_align'] ) ? $settings['image_align'] : false;
        $this->add_render_attribute( 'offer-image', 'class', 'ua-offer-img' );
        $this->add_render_attribute( 'offer-image', 'class', $image_position );
         ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <div class="ua-offer-inner">
                <div class="ua-offer-texts">
                    <?php if( ! empty( $highlighted_title ) ) : ?>
                    <p class="offer-subheading"><?php echo esc_html( $highlighted_title ); ?></p>
                    <?php endif; ?>
                    
                    <?php if( ! empty( $heading ) ) : ?>
                    <p class="offer-heading"><?php echo wp_kses_data( $heading ); ?></p>
                    <?php endif; ?>
                    
                    <?php if( ! empty( $settings['link']['url'] ) ) : ?>
                    <a <?php echo $this->get_render_attribute_string( 'button' ); ?>><?php echo esc_html( $button_text ); ?></a>
                    <?php endif; ?>
                </div>
                <?php if( $image ){ ?>
                <div <?php echo $this->get_render_attribute_string( 'offer-image' ); ?>>
                    <img src="<?php echo esc_url( $image ); ?>" alt="">
                </div>
                <?php } ?>
            </div> 
        </div>
        <?php
        
    }
    
    protected function _content_template() {}
    
    /**
     * General Section for Content Controls
     * 
     * @since 1.0.0.9
     */
    protected function content_general_controls() {
        $this->start_controls_section(
            'general_content',
            [
                'label'     => esc_html__( 'General', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_responsive_control(
            'orientation',
                [
                    'label'         => esc_html__( 'Orientation', 'ultraaddons' ),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => [
                            'portrait' => __( 'Portrait', 'ultraaddons' ),
                            'landscape' => __( 'Landscape', 'ultraaddons' ),
                    ],
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'default'       => 'landscape',
                    'prefix_class'  => 'card-orientation-%s-',
                ]
        );
        
        $this->add_control(
            'heading',
                [
                    'label'         => esc_html__( 'Heading', 'ultraaddons' ),
                    'type'          => Controls_Manager::TEXT,
                    'default'       => __( 'Product Biggest Offer', 'ultraaddons' ),
                    'label_block'   => TRUE,
                    'dynamic'       => ['active' => true],
                ]
        );
        
        $this->add_control(
            'highlighted_title',
                [
                    'label'         => esc_html__( 'Highlighted Title', 'ultraaddons' ),
                    'type'          => Controls_Manager::TEXT,
                    'default'       => __( 'Hot Offer', 'ultraaddons' ),
                    'label_block'   => TRUE,
                    'dynamic'       => ['active' => true],
                ]
        );
        $placeholder_image = ULTRA_ADDONS_URL . '/assets/images/no-image.png';
        
        $this->add_control(
                'image',
                [
                        'label' => __( 'Photo', 'ultraaddons' ),
                        'type' => Controls_Manager::MEDIA,
                        'default' => [
                                'url' => $placeholder_image,//Utils::get_placeholder_image_src(),
                        ],
                        'dynamic' => [
                                'active' => true,
                        ]
                ]
        );
        
        $this->add_control(
                'button_heading',
                [
                        'label' => __( 'Button', 'ultraaddons' ),
                        'type' => Controls_Manager::HEADING,
                        'label_block' => true,
                        'separator' => 'before',
                ]
        );
        
        $this->add_control(
                'text',
                [
                        'label' => __( 'Text', 'ultraaddons' ),
                        'type' => Controls_Manager::TEXT,
                        'placeholder' => __( 'Click Here', 'ultraaddons' ),
                        'default' => __( 'Click Here', 'ultraaddons' ),
                ]
        );
        
        $this->add_control(
                'link',
                [
                        'label' => __( 'Link', 'ultraaddons' ),
                        'type' => Controls_Manager::URL,
                        'placeholder' => __( 'https://example.com', 'ultraaddons' ),
                        'default' => [
                                'url' => '#',
                        ],
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
            'offer_design_style',
            [
                'label'     => esc_html__( 'General', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'offer_alignment',
                [
                    'label'         => esc_html__( 'Alignment', 'ultraaddons' ),
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
                    'prefix_class' => 'elementor-align-',
                    'default' => 'left',
                    'toggle' => true,
                ]
        );        
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'offer_title_style',
            [
                'label'     => esc_html__( 'Title', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'offer_heading_color',
            [
                'label'     => __( 'Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-offer-texts p.offer-heading' => 'color: {{VALUE}}',
                ],
                'default'   => '#021429',
            ]
        );
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'title_typography',
                        'selector' => '{{WRAPPER}} .ua-offer-texts p.offer-heading',
                        'global' => [
                                'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                        ],

                ]
        );
        
        $this->add_responsive_control(
                'title_margin',
                [
                        'label' => __( 'Margin', 'ultraaddons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%', 'em' ],
                        'selectors' => [
                                '{{WRAPPER}} .ua-offer-texts p.offer-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'offer_subtitle_style',
            [
                'label'     => esc_html__( 'Sub Title', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'offer_subheading_color',
            [
                'label'     => __( 'Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-offer-texts p.offer-subheading' => 'color: {{VALUE}}',
                ],
                'default'   => '#fd5a5a',
            ]
        );
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'sub_title_typography',
                        'selector' => '{{WRAPPER}} .ua-offer-texts p.offer-subheading',
                        'global' => [
                                'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                        ],

                ]
        );
        
        $this->add_responsive_control(
                'subtitle_margin',
                [
                        'label' => __( 'Margin', 'ultraaddons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%', 'em' ],
                        'selectors' => [
                                '{{WRAPPER}} .ua-offer-texts p.offer-subheading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'offer_image_style',
            [
                'label'     => esc_html__( 'Image', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
                'image_align',
                [
                        'label' => __( 'Vertical Position', 'ultraaddons' ),
                        'type' => Controls_Manager::CHOOSE,
                        'options' => [
                                'top' => [
                                        'title' => __( 'Top', 'ultraaddons' ),
                                        'icon' => 'eicon-v-align-top',
                                ],
                                'middle' => [
                                        'title' => __( 'Middle', 'ultraaddons' ),
                                        'icon' => 'eicon-v-align-middle',
                                ],
                                'bottom' => [
                                        'title' => __( 'Bottom', 'ultraaddons' ),
                                        'icon' => 'eicon-v-align-bottom',
                                ],
                        ],
                        'default' => 'middle',
                        'prefix_class'  => 'image-location-%s-',
                        'toggle' => true,
                ]
        );
        
        $this->add_responsive_control(
                'image_margin',
                [
                        'label' => __( 'Margin', 'ultraaddons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%', 'em' ],
                        'selectors' => [
                                '{{WRAPPER}} .ua-offer-img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'offer_button_style',
            [
                'label'     => esc_html__( 'Button', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
                'button_margin',
                [
                        'label' => __( 'Margin', 'ultraaddons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%', 'em' ],
                        'selectors' => [
                                '{{WRAPPER}} .ua-offer-texts .offer-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ]
        );
        
        $this->start_controls_tabs( 'button_hover_controls' );
        $this->start_controls_tab(
            'tab_button_content_normal',
            [
                'label'  => esc_html__( 'Normal', 'ultraaddons' )
            ]
        );
        
        $this->add_control(
            'offer_button_color',
            [
                'label'     => __( 'Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-offer-texts .offer-btn' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'offer_button_bg_color',
            [
                'label'     => __( 'Background', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-offer-texts .offer-btn' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                        'name' => 'offer_button_border',
                        'label' => __( 'Border', 'ultraaddons' ),
                        'selector' => '{{WRAPPER}} .ua-offer-texts .offer-btn',
                ]
        );
        
        $this->add_control(
                'offer_button_border_radius',
                [
                        'label' => __( 'Border Radius', 'ultraaddons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%' ],
                        'selectors' => [
                                '{{WRAPPER}} .ua-offer-texts .offer-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ]
        );
        
        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                        'name' => 'offer_button_box_shadow',
                        'label' => __( 'Box Shadow', 'ultraaddons' ),
                        'selector' => '{{WRAPPER}} .ua-offer-texts .offer-btn',
                ]
        );
        
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_button_content_hover',
            [
                'label'  => esc_html__( 'Hover', 'ultraaddons' )
            ]
        );
        
        $this->add_control(
            'offer_button_color_hover',
            [
                'label'     => __( 'Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-offer-texts .offer-btn:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'offer_button_bg_color_hover',
            [
                'label'     => __( 'Background', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-offer-texts .offer-btn:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                        'name' => 'offer_button_border_hover',
                        'label' => __( 'Border', 'ultraaddons' ),
                        'selector' => '{{WRAPPER}} .ua-offer-texts .offer-btn:hover',
                ]
        );
        
        $this->add_control(
                'offer_button_border_radius_hover',
                [
                        'label' => __( 'Border Radius', 'ultraaddons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%' ],
                        'selectors' => [
                                '{{WRAPPER}} .ua-offer-texts .offer-btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ]
        );
        
        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                        'name' => 'offer_button_box_shadow_hover',
                        'label' => __( 'Box Shadow', 'ultraaddons' ),
                        'selector' => '{{WRAPPER}} .ua-offer-texts .offer-btn:hover',
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
            'ua_avd_heading_typography',
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
                        'selector' => '{{WRAPPER}} .advance-heading-wrapper span',
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
        $settings       = $this->get_settings_for_display();
        $advance_sub_heading        = isset( $settings['advance_sub_heading'] ) ? $settings['advance_sub_heading'] : '';
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
        $settings       = $this->get_settings_for_display();
        $advance_heading        = isset( $settings['advance_heading'] ) ? $settings['advance_heading'] : '';
        if(!empty( $advance_heading )) : ?> 
            <h3 class="ultraaddons-heading elementor-inline-editing"><?php echo esc_html( $advance_heading );?></h3>
        <?php endif;
    }
    
  

    
    
}