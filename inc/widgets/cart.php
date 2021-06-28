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
use Elementor\Icons_Manager;
use Elementor\Utils;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Cart extends Base{
    
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
        return [ 'ultraaddons', 'cart', 'wc', 'woocommerce', 'minicart', 'mini cart' ];
    }
    
    
    /**
     * Whether the reload preview is required or not.
     *
     * Used to determine whether the reload preview is required.
     *
     * @since 1.0.0
     * @access public
     *
     * @return bool Whether the reload preview is required.
     */
    public function is_reload_preview_required() {
            return true;
    }
    
    
    /**
     * Register Control Handle from Here
     * 
     * @since 1.0.0
     * @access protected
     * 
     * @author Saiful
     */
    protected function _register_controls() {
        //For General Section
        $this->content_general_controls();
        
        $this->content_general_style();
        $this->content_icon_style();
        $this->content_label_style();
        $this->content_header_style();

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

        /**
         * If not found WooCommerce
         */
        if ( ! class_exists( 'WooCommerce' ) ) {
        ?>
        <h3><?php echo esc_html__( 'WooComemrce is not activated.', 'ultraaddons' ); ?></h3>
        <?php    
            return;
        }
        
        
        $this->add_render_attribute( 'wrapper', 'class', 'ultraaddons-cart-wrapper' );
        $this->add_render_attribute( 'cart_link', 'class', 'cart-link-li' );
        $this->add_render_attribute( 'cart_text', 'class', 'cart-text-li' );
        
        $this->add_render_attribute( 'cart', 'class', 'site-elementor-cart' );
        $this->add_render_attribute( 'cart', 'id', 'site-elementor-cart' );
        
        
        $title = $settings['cart_title'];
        $cart_text = $settings['cart_label'];
        

        $has_icon = ! empty( $settings['add_icon'] );

        if ( $has_icon ) {
                $this->add_render_attribute( 'icon-wrapper', 'class', 'icon-wrapper' );
                $this->add_render_attribute( 'i', 'class', $settings['add_icon'] );
                $this->add_render_attribute( 'i', 'aria-hidden', 'true' );
        }
        $svg        = !empty( $settings['add_icon']['value']['url'] ) && is_string( $settings['add_icon']['value']['url'] ) ? $settings['add_icon']['value']['url'] : false;
        if ( $svg ) {
                $this->add_render_attribute( 'icon-wrapper', 'class', 'icon-svg' );
        }
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            
            <ul <?php echo $this->get_render_attribute_string( 'cart' ); ?>>
			<li <?php echo $this->get_render_attribute_string( 'cart_text' ); ?>>
				<?php echo $cart_text; ?>
			</li>
			<li <?php echo $this->get_render_attribute_string( 'cart_link' ); ?>>
				<?php ultraaddons_woocommerce_cart_link(); ?>
                            <div <?php echo $this->get_render_attribute_string( 'icon-wrapper' ); ?>>
                                <?php if( $svg ){ ?>
                                <img class="ua-cart-icon-image" src="<?php echo esc_url( $svg ); ?>">
                                <?php }else{ ?>
                                <i <?php echo $this->get_render_attribute_string( 'i' ); ?>></i>
                                <?php } ?>
                            </div>
			</li>
                        <li class="minicart-content-wrapper">
				<?php
                                /**
                                 * Do Insert something at the Top of the Mincart
                                 */
                                do_action( 'ultraaddons_minicart_top' );
                                
				$instance = array(
					//'title' => esc_html( 'My Cart', 'ultraaddons' ),
					'title' => $title,
				);
                                $instance = apply_filters( 'ultraaddons_minicart_args', $instance );
				the_widget( 'WC_Widget_Cart', $instance );
                                
                                /**
                                 * Do Insert something at the Top of the Mincart
                                 */
                                do_action( 'ultraaddons_minicart_bottom' );
                                
				?>
			</li>
                        
                        
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
                'general',
                [
                        'label' => __( 'General', 'ultraaddons' ),
                ]
        );
        
        $this->add_responsive_control(
                    'align',
                    [
                            'label' => __( 'Alignment', 'ultraaddons' ),
                            'type' => Controls_Manager::CHOOSE,
                            'options' => [
                                    'left'    => [
                                            'title' => __( 'Left', 'ultraaddons' ),
                                            'icon' => 'eicon-h-align-left',
                                    ],
                                    'center' => [
                                            'title' => __( 'Center', 'ultraaddons' ),
                                            'icon' => 'eicon-h-align-center',
                                    ],
                                    'right' => [
                                            'title' => __( 'Right', 'ultraaddons' ),
                                            'icon' => 'eicon-h-align-right',
                                    ]
                            ],
                            'prefix_class' => 'elementor%s-align-',
                            'default' => 'right',
                            'toggle' => false,
                    ]
            );
            
        
        $this->add_control(
                    'add_icon',
                    [
                            'label' => __( 'Cart Icon', 'ultraaddons' ),
                            'type' => Controls_Manager::ICONS,
                            'fa4compatibility' => 'icon',
                            'default' => [
                                    'value' => 'fas fa-shopping-cart', //<i class="fas fa-shopping-cart"></i>
                                    'library' => 'fa-solid',
                            ],
                    ]
            );
        
        
        
        $this->add_control(
                'cart_label',
                [
                        'label' => __( 'Cart Label', 'ultraaddons' ),
                        'type' => Controls_Manager::TEXT,
                        'dynamic' => [
                                'active' => true,
                        ],
                        'default' => __( 'Shopping Cart', 'ultraaddons' ),
                ]
        );
        
        
        $this->add_control(
                'cart_title',
                [
                        'label' => __( 'Cart Title', 'ultraaddons' ),
                        'type' => Controls_Manager::TEXT,
                        'dynamic' => [
                                'active' => true,
                        ],
                        'default' => '',
                ]
        );
        
        $this->add_control(
                'expand_always',
                [
                        'label' => __( 'Expand Always', 'ultraaddons' ),
                        'description' => __( 'Cart Item will stay exanded always.', 'ultraaddons' ),
                        'type' => Controls_Manager::SWITCHER,
                        'label_on' => __( 'On', 'ultraaddons' ),
                        'label_off' => __( 'Off', 'ultraaddons' ),
                        'return_value' => 'yes',
                        'default' => '',
                        'prefix_class' => 'expand-always-'
                ]
        );
        
        $this->add_control(
                'see_hover',
                [
                        'label' => __( 'Show Hover', 'ultraaddons' ),
                        'description' => __( 'Only for Admin Screen. When user want to apply design on Cart items.', 'ultraaddons' ),
                        'type' => Controls_Manager::SWITCHER,
                        'label_on' => __( 'Show', 'ultraaddons' ),
                        'label_off' => __( 'Hide', 'ultraaddons' ),
                        'return_value' => 'yes',
                        'default' => '',
                        'prefix_class' => 'see-hover-in-admin-',
                        'condition' => [
                                'expand_always!' => 'yes',
                        ],
                ]
        );
        
        
        $this->add_responsive_control(
                'item_box_space',
                [
                        'label' => __( 'Item Box Spacing', 'ultraaddons' ),
                        'type' => Controls_Manager::SLIDER,
                        'default' => [
                                'size' => 30,
                        ],
                        'range' => [
                                'px' => [
                                        'min' => 0,
                                        'max' => 100,
                                ],
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .ultraaddons-cart-wrapper ul.site-elementor-cart li.minicart-content-wrapper .widget_shopping_cart' => 'margin-top: {{SIZE}}{{UNIT}};',
                        ],
                ]
        );
        
        
        $this->add_responsive_control(
                'item_box_width',
                [
                        'label' => __( 'Item Box Width', 'ultraaddons' ),
                        'type' => Controls_Manager::SLIDER,
                        'default' => [
                                'size' => 400,
                        ],
                        'range' => [
                                'px' => [
                                        'min' => 200,
                                        'max' => 800,
                                ],
                                
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .ultraaddons-cart-wrapper ul.site-elementor-cart > li.minicart-content-wrapper' => 'width: {{SIZE}}{{UNIT}};',
                        ],
                ]
        );
        
        
        
        $this->end_controls_section();
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
            
            
            $this->add_control(
                    'wrapper_bg_color',
                    [
                            'label'     => __( 'Background', 'ultraaddons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                    '{{WRAPPER}} .ultraaddons-cart-wrapper ul.site-elementor-cart' => 'background-color: {{VALUE}}',
                            ],
                    ]
            );
            
            $this->add_responsive_control(
                    'wrapper_padding',
                    [
                            'label' => __( 'Padding', 'ultraaddons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'default'   => [
                                    'top' => 5,
                                    'bottom' => 5,
                                    'unit' => 'px',
                            ],
                            'selectors' => [
                                    '{{WRAPPER}} .ultraaddons-cart-wrapper ul.site-elementor-cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                    ]
            );
            
            $this->end_controls_section();
    }
    
    
    /**
     * General Style Section for Content Controls
     * 
     * @since 1.0.2.1
     */
    protected function content_icon_style(){
            $this->start_controls_section(
                    'icon_style',
                    [
                            'label' => __( 'Cart Icon', 'ultraaddons' ),
                            'tab' => Controls_Manager::TAB_STYLE,
                    ]
            );
            
            
            $this->add_control(
                    'icon_color',
                    [
                            'label'     => __( 'Color', 'ultraaddons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                    '{{WRAPPER}} .ultraaddons-cart-wrapper .site-elementor-cart li.cart-link-li .icon-wrapper i' => 'color: {{VALUE}}',
                                    '{{WRAPPER}} .ultraaddons-cart-wrapper .site-elementor-cart li.cart-link-li .icon-wrapper' => 'color: {{VALUE}}',
                            ],
                    ]
            );
            
            $this->add_control(
                    'icon_bg_color',
                    [
                            'label'     => __( 'Background', 'ultraaddons' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => 'transparent',
                            'selectors' => [
                                    '{{WRAPPER}} .ultraaddons-cart-wrapper .site-elementor-cart li.cart-link-li .icon-wrapper' => 'background-color: {{VALUE}}',
                            ],
                    ]
            );
            
            
            $this->add_responsive_control(
                    'icon-padding',
                    [
                            'label' => __( 'Padding', 'ultraaddons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'default'   => [
                                    'top' => 5,
                                    'bottom' => 5,
                                    'unit' => 'px',
                            ],
                            'selectors' => [
                                    '{{WRAPPER}} .ultraaddons-cart-wrapper .site-elementor-cart li.cart-link-li .icon-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                    ]
            );
            
            $this->end_controls_section();
    }
    
    /**
     * Header Label Section for Mini Cart
     * 
     * @since 1.0.2.1
     */
    protected function content_label_style(){
            $this->start_controls_section(
                    'label_style',
                    [
                            'label' => __( 'Label', 'ultraaddons' ),
                            'tab' => Controls_Manager::TAB_STYLE,
                    ]
            );
            
            $this->add_control(
                    'label_style_heading_1',
                    [
                            'label' => __( 'Label', 'ultraaddons' ),
                            'type' => Controls_Manager::HEADING,
                    ]
            );
            
            $this->add_control(
                    'label_color',
                    [
                            'label'     => __( 'Label Color', 'ultraaddons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                    '{{WRAPPER}} .ultraaddons-cart-wrapper ul.site-elementor-cart li.cart-text-li' => 'color: {{VALUE}}',
                            ],
                    ]
            );
            
            $this->add_control(
                    'label_bg_color',
                    [
                            'label'     => __( 'Label Background Color', 'ultraaddons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                    '{{WRAPPER}} .ultraaddons-cart-wrapper ul.site-elementor-cart li.cart-text-li' => 'background-color: {{VALUE}}',
                            ],
                    ]
            );
            
            $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                            'name' => 'label_typography',
                            'label' => 'Typography',
                            'selector' => '{{WRAPPER}} .ultraaddons-cart-wrapper ul.site-elementor-cart li.cart-text-li',
                    ]
            );
            
            $this->add_responsive_control(
                    'label_padding',
                    [
                            'label' => __( 'Label Padding', 'ultraaddons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'default'   => [
                                    'left' => 5,
                                    'right' => 5,
                                    'unit' => 'px',
                            ],
                            'selectors' => [
                                    '{{WRAPPER}} .ultraaddons-cart-wrapper ul.site-elementor-cart li.cart-text-li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                    ]
            );
            
            $this->add_control(
                    'label_style_heading_2',
                    [
                            'label' => __( 'Price', 'ultraaddons' ),
                            'type' => Controls_Manager::HEADING,
                            'separator' => 'before',
                    ]
            );
            
            $this->add_control(
                    'price_color',
                    [
                            'label'     => __( 'Price Color', 'ultraaddons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                    '{{WRAPPER}} .ultraaddons-cart-wrapper .site-elementor-cart li.cart-link-li span.woocommerce-Price-amount.amount' => 'color: {{VALUE}}',
                            ],
                    ]
            );
            
            $this->add_control(
                    'price_bg_color',
                    [
                            'label'     => __( 'Price Background Color', 'ultraaddons' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '#13c392',
                            'selectors' => [
                                    '{{WRAPPER}} .ultraaddons-cart-wrapper .site-elementor-cart li.cart-link-li span.woocommerce-Price-amount.amount' => 'background-color: {{VALUE}}',
                            ],
                    ]
            );
            
            $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                            'name' => 'price_typography',
                            'label' => 'Typography',
                            'selector' => '{{WRAPPER}} .ultraaddons-cart-wrapper .site-elementor-cart li.cart-link-li span.woocommerce-Price-amount.amount',
                    ]
            );
            
            $this->add_responsive_control(
                    'price_padding',
                    [
                            'label' => __( 'Price Padding', 'ultraaddons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'default'   => [
                                    'left' => 5,
                                    'right' => 5,
                                    'unit' => 'px',
                            ],
                            'selectors' => [
                                    '{{WRAPPER}} .ultraaddons-cart-wrapper .site-elementor-cart li.cart-link-li span.woocommerce-Price-amount.amount' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                    ]
            );
            
            $this->add_control(
                    'label_style_heading_3',
                    [
                            'label' => __( 'Quantity', 'ultraaddons' ),
                            'type' => Controls_Manager::HEADING,
                            'separator' => 'before',
                    ]
            );
            
            $this->add_control(
                    'qty_color',
                    [
                            'label'     => __( 'Quantity Color', 'ultraaddons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                    '{{WRAPPER}} .ultraaddons-cart-wrapper .site-elementor-cart li.cart-link-li span.count' => 'color: {{VALUE}}',
                            ],
                    ]
            );
            
            $this->add_control(
                    'qty_bg_color',
                    [
                            'label'     => __( 'Quantity Background Color', 'ultraaddons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                    '{{WRAPPER}} .ultraaddons-cart-wrapper .site-elementor-cart li.cart-link-li span.count' => 'background-color: {{VALUE}}',
                            ],
                    ]
            );
            
            $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                            'name' => 'qty_typography',
                            'label' => 'Typography',
                            'selector' => '{{WRAPPER}} .ultraaddons-cart-wrapper .site-elementor-cart li.cart-link-li span.count',
                    ]
            );
            
            $this->add_responsive_control(
                    'qty_padding',
                    [
                            'label' => __( 'Quantity Padding', 'ultraaddons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'default'   => [
                                    'left' => 5,
                                    'right' => 5,
                                    'unit' => 'px',
                            ],
                            'selectors' => [
                                    '{{WRAPPER}} .ultraaddons-cart-wrapper .site-elementor-cart li.cart-link-li span.count' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                    ]
            );
            
            $this->add_control(
                    'hide_count',
                    [
                            'label' => __( 'Hide Count Item', 'ultraaddons' ),
                            'type' => Controls_Manager::SWITCHER,
                            'label_on' => __( 'Hide', 'ultraaddons' ),
                            'label_off' => __( 'Show', 'ultraaddons' ),
                            'return_value' => 'yes',
                            'default' => '',
                            'selectors' => [
                                    '{{WRAPPER}} .ultraaddons-cart-wrapper .site-elementor-cart li.cart-link-li span.count' => 'display:none !important;',
                            ]
                    ]
            );
            
            $this->end_controls_section();
            
    }
    
    /**
     * Header Style Section for Mini Cart
     * 
     * @since 1.0.2.1
     */
    protected function content_header_style(){
            $this->start_controls_section(
                    'header_style',
                    [
                            'label' => __( 'Header', 'ultraaddons' ),
                            'tab' => Controls_Manager::TAB_STYLE,
                    ]
            );
            
            $this->add_responsive_control(
                    'cart_title_align',
                    [
                            'label' => __( 'Alignment', 'ultraaddons' ),
                            'type' => Controls_Manager::CHOOSE,
                            'options' => [
                                    'left'    => [
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
                                    ]
                            ],
                            'prefix_class' => 'cart-title-align-',
                            'default' => 'center',
                            'toggle' => false,
                    ]
            );
            
            $this->add_control(
                    'title_color',
                    [
                            'label'     => __( 'Color', 'ultraaddons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                    '{{WRAPPER}} .widget_shopping_cart h2.widgettitle' => 'color: {{VALUE}}',
                            ],
                    ]
            );
            
            $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                            'name' => 'title_typography',
                            'label' => 'Typography',
                            'selector' => '{{WRAPPER}} .widget_shopping_cart h2.widgettitle',
                    ]
            );
            
            $this->end_controls_section();
            
    }
    
    
}