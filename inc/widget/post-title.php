<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Post_Title extends Base{
    
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
        return [ 'ultraaddons','ua', 'post', 'page title', 'title' ];
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
        $post_id = !empty( $settings['post_id'] ) ? $settings['post_id'] : 0;
        
        $this->add_render_attribute( 'heading', 'class', 'heading-tag' );

        //$alignment = !empty( $settings['heading_alignment'] ) ? $settings['heading_alignment'] : 'left';
        ?>
        <div class="post-title-wrapper" >
            <h4 <?php echo $this->get_render_attribute_string( 'heading' ); ?>>
                <?php echo get_the_title( $post_id ); ?>
            </h4>
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
            'post_id',
                [
                    'label'         => esc_html__( 'Post ID (Optional)', 'ultraaddons' ),
                    'type'          => Controls_Manager::NUMBER,
                    'default'       => false,
                    'label_block'   => false,
                    'dynamic'       => ['active' => true],
                ]
        );
        
        $this->add_control(
            'heading_alignment',
                [
                    'label'         => esc_html__( 'Heading', 'ultraaddons' ),
                    'type'          => Controls_Manager::CHOOSE,
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
                    'default' => 'left',
                    'toggle' => true,
                    'prefix_class' => 'elementor-align-',
                ]
        );
        
        $this->add_control(
            'heading_color',
            [
                'label'     => __( 'Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .post-title-wrapper h4.heading-tag' => 'color: {{VALUE}}',
                ],
                'default'   => '#0fc392',
            ]
        );

        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'title_typography',
                        'label' => 'Heading Typography',
                        'selector' => '{{WRAPPER}} .post-title-wrapper .heading-tag',
                        'global' => [
                                'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                        ],

                ]
        );
        
        $this->end_controls_section();
    }
    
    
    
    
}
