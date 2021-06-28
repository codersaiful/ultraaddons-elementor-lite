<?php
namespace UltraAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
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
        return [ 'ultraaddons', 'testimonial', 'review', 'feedback', 'user', 'rating' ];
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
    protected function _register_controls() {
        

        //For General Section
        $this->content_general_controls();

        //For Design Section Style Tab
        $this->style_general_controls();
        
        //Section in Style Tab
        $this->style_title_controls();
        $this->style_subtitle_controls();
        $this->style_quote_controls();
        $this->style_avatar_controls();

       
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

        $this->add_render_attribute( 'wrapper', 'class', 'ua-testimonial-wrapper' );
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
                <span class="quote-icon">
                    <i class="fas fa-quote-left"></i>
                </span>
                <?php echo '<p ' . $this->get_render_attribute_string( 'quote' ) . '>' . $settings['quote'] . '</p>'; ?>
                <div class="client-info">
                    <div class="user-avatar" 
                        <?php if( $image ){ ?>
                            style="background-image: url(<?php echo esc_attr( $image ); ?>);"
                        <?php } ?> 
                         ></div>
                    <div class="user-name">
                        <?php echo '<p ' . $this->get_render_attribute_string( 'title' ) . '>' . $settings['title'] . '</p>'; ?>
                        <?php echo '<span ' . $this->get_render_attribute_string( 'sub-title' ) . '>' . $settings['sub-title'] . '</span>'; ?>
                    </div>
                </div>
            </div>
        </div>

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
     * General Section for Content Controls
     * 
     * @since 1.0.0.9
     */
    protected function content_general_controls() {
        
        $placeholder_image = ULTRA_ADDONS_URL . 'assets/images/user.png';
        
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
                    'default'       => __( 'Jonny Robartson', 'ultraaddons' ),
                    'label_block'   => TRUE,
                    'dynamic'       => ['active' => true],
                ]
        );
        
        $this->add_control(
                'sub-title',
                [
                    'label' => __( 'Position/Designation', 'ultraaddons' ),
                    'type' => Controls_Manager::TEXT,
                    'default'       => __( 'UI/UX Designer', 'ultraaddons' ),
                    'label_block'   => TRUE,
                    'dynamic'       => ['active' => true],
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
                        'type' => \Elementor\Controls_Manager::CHOOSE,
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
                        'default' => 'center',
                        'toggle' => true,
                        'prefix_class' => 'elementor-align-',
                        'default' => 'left',
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
                        'default' => '#0FC392',
                        'selectors' => [
                                '{{WRAPPER}} .ua-testimonial-wrapper .client-quote-box span.quote-icon' => 'color: {{VALUE}}',
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
                                '{{WRAPPER}}:hover .ua-testimonial-wrapper .client-quote-box span.quote-icon' => 'color: {{VALUE}}',
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
                                '{{WRAPPER}} .ua-testimonial-wrapper .client-quote-box span.quote-icon' => 'font-size: {{SIZE}}{{UNIT}};',
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
                        'selector' => '{{WRAPPER}} .ua-testimonial-wrapper .client-quote-box .ua-testimonial-title',
                ]
        );
        
        $this->add_control(
                'title_color',
                [
                        'label' => __( 'Color', 'ultraaddons' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '#5C6B79',
                        'selectors' => [
                                '{{WRAPPER}} .ua-testimonial-wrapper .client-quote-box .ua-testimonial-title' => 'color: {{VALUE}}',
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
                        'selector' => '{{WRAPPER}} .ua-testimonial-wrapper .client-quote-box .ua-testimonial-subtitle',
                ]
        );
        
        $this->add_control(
                'subtitle_color',
                [
                        'label' => __( 'Color', 'ultraaddons' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '#5C6B79',
                        'selectors' => [
                                '{{WRAPPER}} .ua-testimonial-wrapper .client-quote-box .ua-testimonial-subtitle' => 'color: {{VALUE}}',
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
                        'name' => 'quote_typography',
                        'label' => 'Quote Typography',
                        'selector' => '{{WRAPPER}} .ua-testimonial-wrapper .client-quote-box .ua-testimonial-quote',
                ]
        );
        
        $this->add_control(
                'quote_color',
                [
                        'label' => __( 'Color', 'ultraaddons' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '#54595F',
                        'selectors' => [
                                '{{WRAPPER}} .ua-testimonial-wrapper .client-quote-box .ua-testimonial-quote' => 'color: {{VALUE}}',
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
                'selector'    => '{{WRAPPER}} .ua-testimonial-wrapper .client-info .user-avatar',
            )
        );
        
        $this->add_control(
                'avatar-size',
                [
                        'label' => __( 'Size', 'ultraaddons' ),
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
                                '{{WRAPPER}} .ua-testimonial-wrapper .client-info .user-avatar' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                        ],
                ]
        );
        
        
        $this->end_controls_section();
    }
       
    
}