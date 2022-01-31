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
use Elementor\Repeater;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class List_Item extends Base{
    
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
        return [ 'ultraaddons', 'ua','list', 'item', 'ul', 'ol', 'list item', 'list-item','item-list' ];
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
        $settings           = $this->get_settings_for_display();
        
        $this->add_render_attribute( 'wrapper', 'class', 'ua-list-item-wrapper' );
        
        
        $items = $settings['list_items'];

        ?>
    <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
        <ul class="ua-list-items">
            <?php
            $serial = 1;
            foreach( $items as $key => $item ){

                $_id = !empty( $item['_id'] ) ? $item['_id'] : false;
                $title = !empty( $item['title'] ) ? $item['title'] : false;
                $description = !empty( $item['description'] ) ? $item['description'] : false;
                $icon     = !empty( $item['icon']['value'] ) && is_string( $item['icon']['value'] ) ? $item['icon']['value'] : false;

                ?>
            <li class="list-item list-item-<?php echo esc_attr( $serial ); ?> elementor-repeater-item-<?php echo esc_attr( $_id ); ?>"> 
                <div class="list-item-inside">
                
                    <?php if( $icon ){ ?>
                    <i class="<?php echo esc_attr( $icon ); ?>"></i>
                    <?php } ?>

                    <?php if( $title ){  ?>
                    <h4 class="list-item-title"><?php echo esc_html( $title ); ?></h4>
                    <?php } ?>

                    <?php if( $description ){  ?>
                    <span class="list-item-description"><?php echo wp_kses_post( $description ); ?></span>
                    <?php } ?>    
                    
                    
                </div>
            </li>    
            <?php
                
                $serial++;
            }
            ?>
            
        </ul> 
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
        
        
        $repeater = new Repeater();
        
        $default_icon = [
                                'value' => 'far fa-check-square',
                                'library' => 'regular',
                        ];
        
        $repeater->add_control(
                'icon',
                [
                        'label'     => __( 'Icon', 'ultraaddons' ),
                        'type'      => Controls_Manager::ICONS,
                        'default'   => $default_icon,
                ]
        );
        
        $repeater->add_control(
                'title',
                [
                        'label'     => __( 'Title', 'ultraaddons' ),
                        'type'      => Controls_Manager::TEXT,
                        'default'   => __( 'List Item', 'ultraaddons' ),
                ]
        );
        
        
        $repeater->add_control(
                'description',
                [
                        'label'     => __( 'Description', 'ultraaddons' ),
                        'type'      => Controls_Manager::TEXT,
                        'default'   => __( 'Description of List Item', 'ultraaddons' ),
                ]
        );
        
        $repeater->add_control(
            'icon_color',
            [
                'label'     => __( 'Icon Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-list-item-wrapper .ua-list-items {{CURRENT_ITEM}} i' => 'color: {{VALUE}}',
                ],
                'default'   => '#0fc392',
            ]
        );
        
        
        $repeater->add_control(
            'icon_each_bg_color',
            [
                'label'     => __( 'Icon Background', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-list-item-wrapper .ua-list-items {{CURRENT_ITEM}} i,.elementor-element.ua-list-temp-temp-2 .ua-list-items {{CURRENT_ITEM}}::after' => 'background-color: {{VALUE}}',
                ],
                'default'   => 'transparent',
                
            ]
        );
        
        $repeater->add_control(
            'item_background',
            [
                'label'     => __( 'Item Background', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-list-item-wrapper .ua-list-items {{CURRENT_ITEM}}' => 'background-color: {{VALUE}}',
                ],
                'default'   => 'transparent',
                
            ]
        );
        
        $this->add_control(
                'list_items',
                [
                        'type' => Controls_Manager::REPEATER,
                        'fields' => $repeater->get_controls(),
                        'default' => [
                                [
                                        'icon' => $default_icon,
                                        'title' => __( 'List Item #1', 'ultraaddons' ),
                                        'description' => __( 'Description of List Item', 'ultraaddons' ),
                                ],
                                
                                [
                                        'icon' => $default_icon,
                                        'title' => __( 'List Item #2', 'ultraaddons' ),
                                        'description' => __( 'Description of List Item', 'ultraaddons' ),
                                ],
                                
                                [
                                        'icon' => $default_icon,
                                        'title' => __( 'List Item #3', 'ultraaddons' ),
                                        'description' => __( 'Description of List Item', 'ultraaddons' ),
                                ],
                                
                        ],
                        'title_field' => '{{{ title }}}',
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
            'style_general',
            [
                'label'     => esc_html__( 'Design', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'template',
                [
                    'label'         => esc_html__( 'Template', 'ultraaddons' ),
                    'type'          => Controls_Manager::SELECT,
                    'options' => [
                            'default'   => __( 'Default', 'ultraaddons' ),
                            'temp-2'    => __( 'Template Two', 'ultraaddons' ),
                            'temp-3'    => __( 'Template Three', 'ultraaddons' ),
                    ],
                    'default' => 'default',
                    'prefix_class' => 'ua-list-temp-',
                ]
        );
        
        $this->add_responsive_control(
            'list-column',
                [
                    'label'         => esc_html__( 'Column', 'ultraaddons' ),
                    'type'          => Controls_Manager::SELECT,
                    'options' => [
                            '100%'    => __( 'One Column', 'ultraaddons' ),
                            '48%'     => __( 'Two Column', 'ultraaddons' ),
                            '30.33%'  => __( 'Three Column', 'ultraaddons' ),
                            '23%'     => __( 'Four Column', 'ultraaddons' ),
                    ],
                    'desktop_default' => '30.33%',
                    'tablet_default' => '48%',
                    'mobile_default' => '100%',
                    'selectors' => [
                                        '{{WRAPPER}} .ua-list-items li' => 'width: {{VALUE}};',
                                ],
                ]
        );
        
        
        
        $this->add_control(
            'title_color',
            [
                'label'     => __( 'Title Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-list-item-wrapper .ua-list-items li .list-item-title' => 'color: {{VALUE}}',
                ],
                'default'   => '#21272c',
            ]
        );
        
        $this->add_control(
            'description_color',
            [
                'label'     => __( 'Description Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-list-item-wrapper .ua-list-items li .list-item-description' => 'color: {{VALUE}}',
                ],
                'default'   => '#5C6B79',
            ]
        );
        
                
        $this->add_responsive_control(
                'padding',
                [
                        'label' => __( 'Padding', 'ultraaddons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%' ],
                        'default'   => [
                                'size' => 55,
                                'unit' => 'px',
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .ua-list-items li.list-item .list-item-inside' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ]
        );
        
        $this->add_responsive_control(
                'margin',
                [
                        'label' => __( 'Margin', 'ultraaddons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%' ],
                        'default'   => [
                                'size' => 55,
                                'unit' => 'px',
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .ua-list-items li.list-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
            'typography',
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
                        'selector' => '{{WRAPPER}} .ua-list-item-wrapper .list-item-title',
//                        'global' => [
//                                'default' => Global_Typography::TYPOGRAPHY_ACCENT,
//                        ],

                ]
        );
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'desc_typography',
                        'label' => 'Description Typography',
                        'selector' => '{{WRAPPER}} .ua-list-item-wrapper span.list-item-description',
//                        'global' => [
//                                'default' => Global_Typography::TYPOGRAPHY_ACCENT,
//                        ],

                ]
        );
        
        
        $this->end_controls_section();
    }
       
    
}