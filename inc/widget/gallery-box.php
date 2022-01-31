<?php
namespace UltraAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Gallery_Box extends Base{
        
    /**
     * Get your widget by keywords
     *
     *
     * @since 1.0.0
     * @access public
     *
     * @return string keywords
     */
    public function get_keywords() {
        return [ 'ultraaddons','ua', 'product', 'gallery', 'image', 'gallery image' ];
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
        
        $settings           = $this->get_settings_for_display();
        $this->add_render_attribute( 'wrapper', 'class', [
            'ua-gallery-box-wrapper',
            //'ua-gallery-box-template-' . $settings['style'],
        ] );
        $image = !empty( $settings['image']['url'] ) ? $settings['image']['url'] : false;
        
//        $this->add_render_attribute( 'gallery_item', 'class', 'ua-gallery-item' );
//        if( $image ){
//            $this->add_render_attribute( 'gallery_item', 'src', $image );
//        }
        
        $heading = $settings['heading'];
        $box_sub_title = $settings['box_sub_title'];
        $has_button_link = ! empty( $settings['gallery_btn_link']['url'] );
            
        if( $has_button_link ) {
            $this->add_link_attributes( 'gallery_btn_link', $settings['gallery_btn_link'] );
        }
        $this->add_render_attribute( 'gallery_btn_link', 'role', 'button' );
        
        $add_icon   = !empty( $settings['add_icon']['value'] ) && is_string( $settings['add_icon']['value'] ) ? $settings['add_icon']['value'] : false;
        $svg        = !empty( $settings['add_icon']['value']['url'] ) && is_string( $settings['add_icon']['value']['url'] ) ? $settings['add_icon']['value']['url'] : false;
        if( $add_icon ){
            $icon_html = '<i class="' . esc_attr( $add_icon ) . '"></i>';
        }elseif( $svg ){
            $icon_html = '<img class="" src="' . esc_url( $svg ) . '" alt="' . esc_attr__( 'Follow Me', 'ultraaddons' ) .'">';
        }
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>            
            <div class="ua-gallery-item" <?php if( $image ){ echo "style='background-image:url({$image})'"; } ?>>
<!--                <img src="<?php echo esc_url( $image ); ?>" alt="Image">-->
                <div class="ua-gallery-item-info">
                    <div class="ua-gallery-info-title">
                        <span><?php echo wp_kses_data( $box_sub_title ); ?></span>
                        <p><?php echo wp_kses_data( $heading ); ?></p>
                    </div>
                    <?php if( $add_icon && ! empty( $settings['gallery_btn_link']['url'] ) ) : ?>
                    <div class="ua-gallery-info-link">
                        <a <?php echo $this->get_render_attribute_string( 'gallery_btn_link' ); ?>><?php echo $icon_html; ?></a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>   
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
            'general_content',
            [
                'label'     => esc_html__( 'General', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
                'style',
                [
                        'label' => __( 'Template', 'ultraaddons' ),
                        'type' => Controls_Manager::SELECT,
                        'default' => '1',
                        'options' => [
                                '1' => __( 'Style 1', 'ultraaddons' ),
                                '2' => __( 'Style 2', 'ultraaddons' ),
                        ],
                        'prefix_class' => 'ua-gallery-style-',
                ]
        );
        
        
        $this->add_control(
                'image',
                [
                        'label' => __( 'Photo', 'ultraaddons' ),
                        'type' => Controls_Manager::MEDIA,
                        'default' => [
                                'url' => Utils::get_placeholder_image_src(),
                        ],
                        'dynamic' => [
                                'active' => true,
                        ]
                ]
        );
        
        $this->add_control(
            'heading',
                [
                    'label'         => esc_html__( 'Heading', 'ultraaddons' ),
                    'type'          => Controls_Manager::TEXT,
                    'default'       => __( 'Gallery Image Title', 'ultraaddons' ),
                    'label_block'   => TRUE,
                    'dynamic'       => ['active' => true],
                ]
        );
        
        $this->add_control(
            'box_sub_title',
                [
                    'label'         => esc_html__( 'Sub Title', 'ultraaddons' ),
                    'type'          => Controls_Manager::TEXT,
                    'default'       => __( 'Sub Title', 'ultraaddons' ),
                    'label_block'   => TRUE,
                    'dynamic'       => ['active' => true],
                ]
        );

        $this->add_control(
            'gallery_btn_link',
            [
                'label'     => esc_html__( 'Link', 'ultraaddons' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'ultraaddons' ),
                'show_external' => true,
                'default' => [
                        'url' => '',
                        'is_external' => true,
                        'nofollow' => true,
                ],
            ]
        );
        
        $this->add_control(
            'add_icon',
                [
                        'label'     => __( 'Icon', 'ultraaddons' ),
                        'type'      => Controls_Manager::ICONS,
                        'default'   => [
                                'value' => 'fas fa-plus',
                                'library' => 'solid',
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
            'avd_heading_design_style',
            [
                'label'     => esc_html__( 'Design', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        
        
        
        $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                        'name' => 'gradient',
                        'label' => __( 'Gradient', 'ultraaddons' ),
                        'types' => [ 'gradient' ],
                        'selector' => '{{WRAPPER}} .ua-gallery-box-wrapper .ua-gallery-item-info',
                ]
        );
        
        $this->add_control(
                'title_color', [
                        'label' => __( 'Title Color', 'ultraaddons' ),
                        'type'      => Controls_Manager::COLOR,
                        'selectors' => [
                                '{{WRAPPER}} .ua-gallery-info-title p' => 'color: {{VALUE}};',
                        ],
                ]
        );
        
        $this->add_control(
                'sub_title_color', [
                        'label' => __( 'Sub Title Color', 'ultraaddons' ),
                        'type'      => Controls_Manager::COLOR,
                        'selectors' => [
                                '{{WRAPPER}} .ua-gallery-info-title span' => 'color: {{VALUE}};',
                        ],
                ]
        );
        
        $this->add_control(
                'icon_color', [
                        'label' => __( 'Icon Color', 'ultraaddons' ),
                        'type'      => Controls_Manager::COLOR,
                        'selectors' => [
                                '{{WRAPPER}} .ua-gallery-info-link a' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                                '{{WRAPPER}} .ua-gallery-box-wrapper .ua-gallery-item .ua-gallery-info-link a:hover' => 'color:white;background-color: {{VALUE}};',
//                                '{{WRAPPER}}.ua-gallery-style-2 .ua-gallery-box-wrapper .ua-gallery-item .ua-gallery-info-link a:hover' => 'color:white;background-color: {{VALUE}};',
                        ],
                ]
        );
        
        $this->add_control(
                'gb_box_height',
                [
                        'label' => __( 'Box Height', 'ultraaddons' ),
                        'type' => Controls_Manager::SLIDER,
                        'size_units' => [ 'px' ],
                        'range' => [
                                'px' => [
                                        'min' => 100,
                                        'max' => 500,
                                        'step' => 1,
                                ],
                        ],
                        'default' => [
                                'unit' => 'px',
                                'size' => 250,
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .ua-gallery-box-wrapper .ua-gallery-item' => 'height: {{SIZE}}{{UNIT}};',
                        ],
                ]
        );
        
        
        
        
        $this->end_controls_section();
    
        $this->start_controls_section(
            'mc_style_typography',
            [
                'label'     => esc_html__( 'Typography', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'title_typography',
                        'label' => 'Title Typography',
                        'selector' => '{{WRAPPER}} .ua-gallery-info-title p',
                        'global' => [
                                'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                        ],

                ]
        );
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'sub_title_typography',
                        'label' => 'Sub Title Typography',
                        'selector' => '{{WRAPPER}} .ua-gallery-info-title span',
                        'global' => [
                                'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                        ],

                ]
        );
    
        $this->end_controls_section();
    
    }
}