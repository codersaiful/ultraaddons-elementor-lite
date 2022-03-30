<?php
namespace UltraAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Repeater;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Testimonial_Box extends Base{
    
    
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
        return [ 'ultraaddons', 'ua', 'testimonial', 'review', 'feedback', 'user', 'rating' ];
    }
    
    /**
     * Retrieve the list of scripts the counter widget depended on.
     *
     * Used to set scripts dependencies required to run the widget.
     *
     * @since 1.0.0.13
     * @access public
     *
     * @return array Widget scripts dependencies.
     * @by Saiful
     */
    public function get_script_depends() {
            return [ 'jquery', 'swiper' ];
    }
    
    /**
     * Totally new Created
     * We will use this format to Add
     * Additional JS Library
     * for our Element/Widget
     * 
     * @return Array
     */
    public function ultraaddons_settings(){
        //return; //On Apply, need to comment out this line
        return [
//            'additional_scripts'       => [
//                'swiper-min' =>    [
//                    'url'               =>  ULTRA_ADDONS_URL . '/assets/vendor/js/swiper-min.js',
//                    'dependency'        =>  ['jquery'],
//                    'version'           => false,
//                    'in_footer'         =>  true,
//                ],
//            ],
            'dependency'       =>  ['jquery','swiper'],
            'version'       =>  '1.0.0.12',
            'in_footer'     =>  true,
        ];
    }
    
    /**
     * Register oEmbed widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls() {
        //For General Section
        $this->content_general_controls();

        //For Design Section Style Tab
        $this->style_general_controls();
        
        //Section in Style Tab
        $this->style_title_controls();
        $this->style_subtitle_controls();
        $this->style_quote_controls();
        $this->style_avatar_controls();
        $this->style_box_style();
        $this->style_review_controls();

    }
    
  
    
    /**
     * General Section for Content Controls
     * 
     * @since 1.0.0.9
     */
    protected function content_general_controls() {
        
        $placeholder_image = ULTRA_ADDONS_URL . 'assets/images/team.jpg';
        
        $this->start_controls_section(
            'general_content',
            [
                'label'     => esc_html__( 'General', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
        
        
        $this->add_control(
                'title',
                [
                    'label' => __( 'Title', 'ultraaddons' ),
                    'type' => Controls_Manager::TEXT,
                    'default'       => __( 'John Doe', 'ultraaddons' ),
                    'label_block'   => TRUE,
                    'dynamic'       => ['active' => true],
                ]
        );
        
        $this->add_control(
                'sub-title',
                [
                    'label' => __( 'Position/Designation', 'ultraaddons' ),
                    'type' => Controls_Manager::TEXT,
                    'default'       => __( 'Business Consultant', 'ultraaddons' ),
                    'label_block'   => TRUE,
                    'dynamic'       => ['active' => true],
                ]
        );
          $this->add_control(
                'quote_title',
                [
                    'label' => __( 'Quote Title', 'ultraaddons' ),
                    'type' => Controls_Manager::TEXT,
                    'default'       => __( 'Awesome Products & Services', 'ultraaddons' ),
                    'label_block'   => TRUE,
                ]
        );
        
        $this->add_control(
                'quote',
                [
                    'label' => __( 'Quote', 'ultraaddons' ),
                    'type' => Controls_Manager::TEXTAREA,
                    'default'       => __( 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incdidunt ut labore et dolore magna do aliqua quis ipsum suspendisse ces gravida. Risus commodo viverra maecenas.', 'ultraaddons' ),
                    'label_block'   => TRUE,
                    'dynamic'       => ['active' => true],
                    'rows' => 5,
                    'separator' => 'after',
                ]
        );
        
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
                'quote_icon',
                [
                        'label' => esc_html__( 'Quote Icon', 'ultraaddons' ),
                        'type' => Controls_Manager::ICONS,
                        'default' => [
                                'value' => 'fas fa-quote-left',
                                'library' => 'solid',
                        ],
                ]
        );
        $this->add_control(
                'customer_review',
                [
                        'label' => __( 'Add Review?', 'ultraaddons' ),
                        'type' => Controls_Manager::SWITCHER,
                        'label_on' => __( 'Yes', 'ultraaddons' ),
                        'label_off' => __( 'No', 'ultraaddons' ),
                        'default' => 'yes',
                        'return_value' => 'yes',
                ]
        );
        $this->add_control(
                'rating_number',
                [
                        'label' => __( 'Rating', 'ultraaddons' ),
                        'type' => Controls_Manager::NUMBER,
                        'min' => 1,
                        'step' => 1,
                        'max' => 5,
                        'default' => 5,
                        'condition' => [
                                'customer_review' => 'yes',
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
    protected function style_general_controls() {
        $this->start_controls_section(
            'style_general',
            [
                'label'     => esc_html__( 'General', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
                'text_align',
                [
                        'label' => __( 'Alignment', 'ultraaddons' ),
                        'type' => Controls_Manager::CHOOSE,
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
                        'default' => 'center',
                        'toggle' => true,
                        'prefix_class' => 'elementor-align-',
                        'default' => 'center',
                ]
        );
        $this->add_control(
                'content_position',
                [
                        'label' => __( 'Content Position', 'ultraaddons' ),
                        'type' => Controls_Manager::CHOOSE,
                        'options' => [
                                'bottom' => [
                                        'title' => __( 'Bottom', 'ultraaddons' ),
                                        'icon' => 'eicon-arrow-down',
                                ],
                                'top' => [
                                        'title' => __( 'Top', 'ultraaddons' ),
                                        'icon' => 'eicon-arrow-up',
                                ],
                        ],
                        'default' => 'top',
                ]
        );
        
        
        $this->start_controls_tabs('testimonial-style-tabs');
        $this->start_controls_tab('testimonial-stl-tab-normal', 
                [
                    'label' => __( 'Normal', 'ultraaddons' ),
                ]
        );
        
        
        
        $this->add_control(
                'quote_icon_color',
                [
                        'label' => __( 'Quote Icon Color', 'ultraaddons' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '#C4C4C4',
                        'selectors' => [
                                '{{WRAPPER}} .ua-testimonial-box .client-quote-box .quote-icon i' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .ua-testimonial-box .client-quote-box .quote-icon svg' => 'fill: {{VALUE}}',
                        ],
                ]
        );
        
        
        
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('testimonial-stl-tab-hover', 
                [
                    'label' => __( 'Hover', 'ultraaddons' ),
                ]
        );
        
        $this->add_control(
                'quote_icon_color_hover',
                [
                        'label' => __( 'Quote Icon Color', 'ultraaddons' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                                '{{WRAPPER}}:hover .ua-testimonial-box .client-quote-box .quote-icon i' => 'color: {{VALUE}}',
                                '{{WRAPPER}}:hover .ua-testimonial-box .client-quote-box .quote-icon svg' => 'fill: {{VALUE}}',
                        ],
                ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->add_control(
                'quote-icon-size',
                [
                        'label' => __( 'Quote Icon Size', 'ultraaddons' ),
                        'type' => Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%' ],
                        'range' => [
                                'px' => [
                                        'min' => 0,
                                        'max' => 100,
                                        'step' => 1,
                                ],
                                '%' => [
                                        'min' => 0,
                                        'max' => 100,
                                ],
                        ],
                        'default' => [
                                'unit' => 'px',
                                'size' => 50,
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .ua-testimonial-box .client-quote-box .quote-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .ua-testimonial-box .client-quote-box .quote-icon svg' => 'width: {{SIZE}}{{UNIT}};',
                        ],
                ]
        );
        
        
       
        $this->end_controls_section();
    }
    
    /**
     * Section for Title in Style Tab
     * 
     * @since 1.0.0.15
     */
    protected function style_title_controls() {
        $this->start_controls_section(
            'title-typography',
            [
                'label'     => esc_html__( 'Title', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'title_typography',
                        'label' => 'Title Typography',
                        'selector' => '{{WRAPPER}} .ua-testimonial-box .client-quote-box .ua-testimonial-title',
                ]
        );
        
        $this->add_control(
                'title_color',
                [
                        'label' => __( 'Color', 'ultraaddons' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '#5C6B79',
                        'selectors' => [
                                '{{WRAPPER}} .ua-testimonial-box .client-quote-box .ua-testimonial-title' => 'color: {{VALUE}}',
                        ],
                ]
        );
         $this->add_responsive_control(
                'title_margin',
                [
                        'label'       => esc_html__( 'Title Margin', 'ultraaddons' ),
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
                                '{{WRAPPER}} .client-info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ]
        );
        
        
        $this->end_controls_section();
    }
    
    /**
     * Section for Sub Title in Style Tab
     * 
     * @since 1.0.0.15
     */
    protected function style_subtitle_controls() {
        $this->start_controls_section(
            'subtitle-typography',
            [
                'label'     => esc_html__( 'Position', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'position_typography',
                        'label' => 'Position Typography',
                        'selector' => '{{WRAPPER}} .ua-testimonial-box .client-quote-box .ua-testimonial-subtitle',
                ]
        );
        
        $this->add_control(
                'subtitle_color',
                [
                        'label' => __( 'Color', 'ultraaddons' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '#5C6B79',
                        'selectors' => [
                                '{{WRAPPER}} .ua-testimonial-box .client-quote-box .ua-testimonial-subtitle' => 'color: {{VALUE}}',
                        ],
                ]
        );
        $this->add_responsive_control(
                'subtitle_margin',
                [
                        'label'       => esc_html__( 'Subtitle Margin', 'ultraaddons' ),
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
                                '{{WRAPPER}} .ua-testimonial-box .client-quote-box .ua-testimonial-subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ]
        );
        
        
        $this->end_controls_section();
    }
    
    /**
     * Section for Quote in Style Tab
     * 
     * @since 1.0.0.15
     */
    protected function style_quote_controls() {
        $this->start_controls_section(
            'quote-typography',
            [
                'label'     => esc_html__( 'Quote', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
         $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'quote_title_typography',
                        'label' => 'Quote Title Typography',
                        'selector' => '{{WRAPPER}} .ua-testimonial-box .client-quote-box .quote-title',
                ]
        );
        
        $this->add_control(
                'quote_title_color',
                [
                        'label' => __( 'Quote Title Color', 'ultraaddons' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '#54595F',
                        'selectors' => [
                                '{{WRAPPER}} .ua-testimonial-box .client-quote-box .quote-title' => 'color: {{VALUE}}',
                        ],
                ]
        );
        $this->add_responsive_control(
                'quote_title_margin',
                [
                        'label'       => esc_html__( 'Quote Title Margin', 'ultraaddons' ),
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
                                '{{WRAPPER}} .ua-testimonial-box .client-quote-box .quote-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'quote_typography',
                        'label' => 'Quote Typography',
                        'selector' => '{{WRAPPER}} .ua-testimonial-box .client-quote-box .ua-testimonial-quote',
                ]
        );
        
        $this->add_control(
                'quote_color',
                [
                        'label' => __( 'Color', 'ultraaddons' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '#54595F',
                        'selectors' => [
                                '{{WRAPPER}} .ua-testimonial-box .client-quote-box .ua-testimonial-quote' => 'color: {{VALUE}}',
                        ],
                ]
        );
       
        
        
        $this->end_controls_section();
    }
    
    /**
     * Section for Quote in Style Tab
     * 
     * @since 1.0.0.15
     */
    protected function style_avatar_controls() {
        $this->start_controls_section(
            'avatar',
            [
                'label'     => esc_html__( 'Avatar', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'avatar-border',
                'label'       => esc_html__( 'Border', 'ultraaddons' ),
                'placeholder' => '1px',
                'default'     => '',
                'selector'    => '{{WRAPPER}} .ua-testimonial-box .client-info .client-avatar img',
            )
        );
        
        $this->add_control(
                'avatar-size',
                [
                        'label' => __( 'Size', 'ultraaddons' ),
                        'type' => Controls_Manager::SLIDER,
                        'size_units' => [ 'px' ],
                        'range' => [
                                'px' => [
                                        'min' => 0,
                                        'max' => 200,
                                        'step' => 1,
                                ],
                        ],
                        'default' => [
                                'unit' => 'px',
                                'size' => 120,
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .ua-testimonial-box .client-info .client-avatar img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                        ],
                ]
        );
         $this->add_responsive_control(
                'avatar_radius',
                [
                        'label'       => esc_html__( 'Avatar Radius', 'ultraaddons' ),
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
                                '{{WRAPPER}} .ua-testimonial-box .client-info .client-avatar img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ]
        );
        
        $this->end_controls_section();
    }
    protected function style_review_controls() {
        $this->start_controls_section(
            'review',
            [
                'label'     => esc_html__( 'Review', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
       $this->add_control(
                'review_icon_color',
                [
                        'label' => __( 'Review Icon Color', 'ultraaddons' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '#FF9900',
                        'selectors' => [
                                '{{WRAPPER}} .customer-review i' => 'color: {{VALUE}}',
                        ],
                ]
        );
        $this->add_control(
                'review_icon_size',
                [
                        'label' => esc_html__( 'Icon Size', 'ultraaddons' ),
                        'type' => Controls_Manager::SLIDER,
                        'size_units' => [ 'px' ],
                        'range' => [
                                'px' => [
                                        'min' => 12,
                                        'max' => 100,
                                        'step' => 5,
                                ],
                                
                        ],
                        'default' => [
                                'unit' => 'px',
                                'size' => 20,
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .customer-review i' => 'font-size: {{SIZE}}{{UNIT}};',
                        ],
                ]
        );
        $this->add_responsive_control(
                'review_margin',
                [
                        'label'       => esc_html__( 'Review Margin', 'ultraaddons' ),
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
                                '{{WRAPPER}} .customer-review' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ]
        );
        
        
        $this->end_controls_section();
    }
    /**
     * @author B M Rafiul Alam
     * @since 1.1.0.12
     */
     protected function style_box_style() {
        $this->start_controls_section(
            'box_style',
            [
                'label'     => esc_html__( 'Box', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
         $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                        'name' => 'box_background',
                        'label' => esc_html__( 'Background', 'ultraaddons' ),
                        'types' => [ 'classic', 'gradient' ],
                        'selector' => '{{WRAPPER}} .ua-testimonial-box',
                ]
        );
       

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                        'name' => 'box_border',
                        'label' => __( 'Some Border', 'elementor' ),
                        'fields_options' => [
                                'border' => [
                                        'default' => 'solid',
                                ],
                                'width' => [
                                        'default' => [
                                                'top' => '1',
                                                'right' => '1',
                                                'bottom' => '1',
                                                'left' => '1',
                                                'isLinked' => false,
                                        ],
                                ],
                                'color' => [
                                        'default' => '#F0F0F0',
                                ],
                        ],
                        'selector' => '{{WRAPPER}} .ua-testimonial-box',
                ]
        );
                $this->add_responsive_control(
                        'box_radius',
                        [
                                'label'       => esc_html__( 'Box Radius', 'ultraaddons' ),
                                'type'        => Controls_Manager::DIMENSIONS,
                                'size_units'  => [ '%', 'px' ],
                                'placeholder' => [
                                        'top'    => '0',
                                        'right'  => '0',
                                        'bottom' => '0',
                                        'left'   => '0',
                                ],
                                'selectors'   => [
                                        '{{WRAPPER}} .ua-testimonial-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                        ]
                );
                 $this->add_responsive_control(
                        'box_padding',
                        [
                                'label'       => esc_html__( 'Box Padding', 'ultraaddons' ),
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
                                        '{{WRAPPER}} .ua-testimonial-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                        ]
                );
                 $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'box_shadow',
                            'selector' => '{{WRAPPER}} .ua-testimonial-box',
                        ]
                    );
                
        $this->end_controls_section();
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
        $position = $settings['content_position'];
        $quote_title =  $settings['quote_title'];

        $this->add_render_attribute( 'wrapper', 'class', 'ua-testimonial-box' );
        $this->add_render_attribute( 'item', 'class', 'ua-testimonial' );
        $this->add_render_attribute( 'title', 'class', 'ua-testimonial-title' );
        $this->add_render_attribute( 'sub-title', 'class', 'ua-testimonial-subtitle' );
        $this->add_render_attribute( 'quote', 'class', 'ua-testimonial-quote' );
        
        $this->add_inline_editing_attributes( 'title', 'none' );
        $this->add_inline_editing_attributes( 'sub-title', 'none' );
        $this->add_inline_editing_attributes( 'quote', 'advanced' );

        $image = !empty( $settings['image']['url'] ) ? $settings['image']['url'] : false;
        if( empty( $image ) ){
            $this->add_render_attribute( 'wrapper', 'class', 'no-profile-image' );
        }
        ?>
    <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
        <div <?php echo $this->get_render_attribute_string( 'item' ); ?>>
            <div class="client-quote-box">
                 <?php if($position=='top'):?>
                <div class="client-info">
                        <div class="client-avatar">
                                <img src="<?php echo esc_attr( $image ); ?>">
                        </div>
                    <div class="user-name">
                        <?php echo '<p ' . $this->get_render_attribute_string( 'title' ) . '>' . $settings['title'] . '</p>'; ?>
                        <?php echo '<span ' . $this->get_render_attribute_string( 'sub-title' ) . '>' . $settings['sub-title'] . '</span>'; ?>
                    </div>
                </div>
                <?php endif;?>
                <div class="quote-icon">
                        <?php \Elementor\Icons_Manager::render_icon( $settings['quote_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                </div>
                <?php if( $quote_title ): ?>
                        <h2 class="quote-title">
                                <?php echo $quote_title; ?>
                        </h2>
                <?php endif;?>
                <?php if($settings['customer_review']=='yes'): ?>
                        <div class="customer-review">
                        <?php
                        $r=$settings['rating_number'];
                        for ($x = 1; $x <= $r; $x++) {?>
                        <i class="fas fa fa-star checked"></i>
                        <?php }?>
                        </div>
                <?php endif;?>
                <p <?php echo $this->get_render_attribute_string( 'quote' );?> >
                        <?php echo $settings['quote'];?>
                </p>
                <?php if($position=='bottom'):?>
                <div class="client-info">
                        <div class="client-avatar">
                                <img src="<?php echo esc_attr( $image ); ?>">
                        </div>
                        <div class="user-name">
                                <?php echo '<p ' . $this->get_render_attribute_string( 'title' ) . '>' . $settings['title'] . '</p>'; ?>
                                <?php echo '<span ' . $this->get_render_attribute_string( 'sub-title' ) . '>' . $settings['sub-title'] . '</span>'; ?>
                        </div>
                </div>
                <?php endif;?>
            </div>
        </div>

    </div>
        <?php
        
    }
    
       
    
}