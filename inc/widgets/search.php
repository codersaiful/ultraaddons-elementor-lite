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

class Search extends Base{
    
    /**
     * Set your widget name keyword
     *
     *
     * @since 1.0.0
     * @access public
     *
     * @return string keywords
     */
    public function get_keywords() {
        return [ 'ultraaddons', 'search', 'find' ];
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

       
        //General Control of Style for menu
        $this->content_general_style();
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
        
        
        $this->add_render_attribute( 'wrapper', 'id', 'ultraaddons-search-box-wrapper' );
        $type = $settings['type'];
        
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <div class="ua-serach-inside">
                <?php
                if( class_exists( 'WooCommerce' ) && $type === 'wc' ){
                    echo get_product_search_form();
                }elseif( $type === 'wp' ){
                    echo get_search_form();
                }
                ?>
            </div>
            
        </div>
        <?php

    }
    
    /**
     * General Style Section for Content Controls
     * 
     * @since 1.0.2.1
     */
    protected function content_general_style(){
            $this->start_controls_section(
                    'general_style',
                    [
                            'label' => __( 'General', 'ultraaddons' ),
                            'tab' => Controls_Manager::TAB_STYLE,
                    ]
            );
            
            $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                            'name' => 'border_wrapper',
                            'label' => __( 'Wrapper Border', 'ultraaddons' ),
                            'selector' => '{{WRAPPER}} form.search-form,{{WRAPPER}} form.woocommerce-product-search',
                    ]
            );
            
            $this->start_controls_tabs( 'search_style_general_tab' );
            //Tab Normal
            $this->start_controls_tab(
                'tab_icon_bg',
                [
                    'label'  => esc_html__( 'Normal', 'ultraaddons' )
                ]
            );
            $this->add_control(
                'icon_bg',
                [
                        'label' => __( 'Button Background', 'ultraaddons' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                                '{{WRAPPER}} form.search-form input.search-submit' => 'background-color: {{VALUE}}',
                                '{{WRAPPER}} form.woocommerce-product-search button' => 'background-color: {{VALUE}}',
                        ],
                ]
            );
            
            $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                            'name' => 'border',
                            'label' => __( 'Border', 'ultraaddons' ),
                            'selector' => '{{WRAPPER}} form.search-form input.search-field,{{WRAPPER}} form.woocommerce-product-search input.search-field',
                    ]
            );
            
            
            $this->add_control(
                    'border_radius',
                    [
                            'label' => __( 'Border Radius', 'ultraaddons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px' ],
                            'selectors' => [
                                    '{{WRAPPER}} form.search-form input.search-field,{{WRAPPER}} form.woocommerce-product-search input.search-field' => 'border-radius: {{TOP}}{{UNIT}} 0{{UNIT}} 0{{UNIT}} {{LEFT}}{{UNIT}};',
                                    '{{WRAPPER}} form.search-form input.search-submit, {{WRAPPER}} form.woocommerce-product-search button' => 'border-radius: 0{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} 0{{UNIT}};',
                                '{{WRAPPER}} form.search-form,{{WRAPPER}} form.woocommerce-product-search' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                    ]
            );

            $this->add_group_control(
                    Group_Control_Box_Shadow::get_type(),
                    [
                            'name' => 'box_shadow',
                            'label' => __( 'Box Shadow', 'ultraaddons' ),
                            'selector' => '{{WRAPPER}} form.search-form,{{WRAPPER}} form.woocommerce-product-search',
                    ]
            );
            
            
            $this->end_controls_tab();
            
            
            
            //Tab Hover
            $this->start_controls_tab(
                'tab_icon_hover_bg',
                [
                    'label'  => esc_html__( 'Hover', 'ultraaddons' )
                ]
            );
            $this->add_control(
                'icon_hover_bg',
                [
                        'label' => __( 'Button Hover Background', 'ultraaddons' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                                '{{WRAPPER}} form.search-form:hover input.search-submit' => 'background-color: {{VALUE}}',
                                '{{WRAPPER}} form.woocommerce-product-search:hover button' => 'background-color: {{VALUE}}',
                        ],
                ]
            );
            
            $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                            'name' => 'border_hover',
                            'label' => __( 'Border', 'ultraaddons' ),
                           'selector' => '{{WRAPPER}} form.search-form:hover input.search-field,{{WRAPPER}} form.woocommerce-product-search:hover input.search-field',
                        ]
            );
            
            
            $this->add_control(
                    'border_radius_hover-ua',
                    [
                            'label' => __( 'Border Radius', 'ultraaddons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px' ],
                            'selectors' => [
                                    '{{WRAPPER}} form.search-form:hover input.search-field,{{WRAPPER}} form.woocommerce-product-search:hover input.search-field' => 'border-radius: {{TOP}}{{UNIT}} 0{{UNIT}} 0{{UNIT}} {{LEFT}}{{UNIT}};',
                                    '{{WRAPPER}} form.search-form:hover input.search-submit, {{WRAPPER}} form.woocommerce-product-search:hover button' => 'border-radius: 0{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} 0{{UNIT}};',
                                    '{{WRAPPER}} form.search-form:hover,{{WRAPPER}} form.woocommerce-product-search:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                    ]
            );

            $this->add_group_control(
                    Group_Control_Box_Shadow::get_type(),
                    [
                            'name' => 'box_shadow_hover',
                            'label' => __( 'Box Shadow', 'ultraaddons' ),
                            'selector' => '{{WRAPPER}} form.search-form:hover,{{WRAPPER}} form.woocommerce-product-search:hover',
                    ]
            );
            
            
            $this->end_controls_tab();
            $this->end_controls_tabs();
            
            
            
            
            
            $this->end_controls_section();
    }
    
    
    /**
     * General Section for Content Controls
     * 
     * @since 1.0.0.9
     */
    protected function content_general_controls() {
        $this->start_controls_section(
                'general',
                [
                        'label' => __( 'General', 'ultraaddons' ),
                ]
        );
        
        
        
        $this->add_control(
                'type',
                [
                        'label'        => __( 'Type', 'ultraaddons' ),
                        'type'         => Controls_Manager::SELECT,
                        'options'      => [
                            'wp'    => __( 'Default Search', 'ultraaddons' ),
                            'wc'    => __( 'WooCommerce Product Search', 'ultraaddons' ),
                        ],
                        'default'      => 'wp',
                        'save_default' => true,
                ]
        );
        
//        $this->add_control(
//                    'add_icon',
//                    [
//                            'label' => __( 'Menu Icon', 'ultraaddons' ),
//                            'type' => Controls_Manager::ICONS,
//                            'fa4compatibility' => 'icon',
//                            'default' => [
//                                    'value' => 'fas fa-shopping-cart', //<i class="fas fa-shopping-cart"></i>
//                                    'library' => 'fa-solid',
//                            ],
//                            'description'   => esc_html__( 'Only for Mobile Menu, If any user want to show rext for Mobile' ),
//                            
//                    ]
//            );
        
        
        
//        $this->add_control(
//                'placeholder_text',
//                [
//                        'label'        => __( 'Placeholder Text', 'ultraaddons' ),
//                        'type'         => Controls_Manager::TEXT,
//                        'placeholder'  => __( 'Primary Menu', 'ultraaddons' ),
//                        'default'      => __( 'Primary Menu', 'ultraaddons' ),//'Primary Menu',
//                        'save_default' => true,
//                        'dynamic'       => ['active' => true],
//                ]
//        );
//        
        
        $this->end_controls_section();
    }
    
    
}