<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Product_Table extends Base{
    
        
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
            return [ 'ultraaddons', 'table', 'woo', 'product', 'wpt table', 'wc', 'tbl' ]; //'ultraaddons eicon-table'
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
                return [ 'jquery','select2' ];
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
	 * Register oEmbed widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {

                //For General/Content Tab
		$this->content_general();
                
                /**
                 * All other Section will be active,
                 * If found, Product Table installed properly
                 * 
                 * @since 1.0.1.0
                 */
                if( class_exists( '\WPT_Product_Table') ){
                    
//                    $this->style_table_general();
                    
                    //For Typography Section Style Tab
                    $this->style_table_head();
                    

                    //For Typography Section Style Tab
                    $this->style_table_body();

                    //Style and Design and Typography for Minicart
                    $this->style_mini_cart();
                    

                    //Style and Design and Typography for Minicart
                    $this->style_search_box();
                    
                    //For Typography Section Style Tab
//                    $this->style_column_design();
                    
                    //For Button Style
                    $this->style_button();
                    
                    //For Footer CArt
                    $this->style_footer_cart();
                }
                
                
                
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
            
            //var_dump(class_exists( '\WPT_Product_Table' ),\WPT_Product_Table::$columns_array);
            $settings = $this->get_settings_for_display();
            $table_id = isset( $settings['table_id'] ) && !empty( $settings['table_id'] ) ? $settings['table_id'] : false;
            if( $table_id && is_numeric( $table_id ) ){
                $name = get_the_title( $table_id );
                $shortcode = "[Product_Table id='{$table_id}' name='{$name}']";
                $shortcode = do_shortcode( shortcode_unautop( $shortcode ) );
		?>
                <div class="wpt-elementor-wrapper wpt-elementor-wrapper-<?php echo esc_attr( $table_id ); ?>">
                    <?php echo $shortcode; ?>
                </div>
		<?php
            }else{
                echo '<h2 class="wpt_elmnt_select_note">';
                echo esc_html__( 'Please select a Table.', 'wpt_pro' );
                echo '</h2>';
            }
	}
        
        protected function content_general() {
                $this->start_controls_section(
			'general',
			[
				'label' => __( 'General', 'wpt_pro' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
                
                $args = array(
                    'post_type' => 'wpt_product_table',
                    'posts_per_page'=> '-1',
                    'post_status' => 'publish',
                );
                $productTable = new \WP_Query( $args );
                $table_options = array();
                $wpt_extra_msg = false;
                if ($productTable->have_posts()) : 
                    
                    while ($productTable->have_posts()): $productTable->the_post();

                    $id = get_the_id();
                    $table_options[$id] = get_the_title();
                    endwhile;

                else:
                    $table_options = false;
                    //Controls_Manager::HEADING
                endif;
                
		
                wp_reset_postdata();
                wp_reset_query();
                if( $table_options && is_array( $table_options ) ){
                    $this->add_control(
                            'table_id',
                            [
                                    'label' => __( 'Table List', 'wpt_pro' ),
                                    'type' => Controls_Manager::SELECT,
                                    'options' => $table_options,
                                    //'default' => '',
                            ]
                    );
                    
                }else{
                    $wpt_extra_msg = __( 'There is not founded any table to your. ', 'wpt_pro' );
                }
                
                $this->add_control(
                        'table_notification',
                        [
                            'label' => __( 'Additional Information', 'wpt_pro' ),
                            'type' => Controls_Manager::RAW_HTML,
                            'raw' => $wpt_extra_msg . sprintf( 
                                    __( 'Create %sa new table%s.', 'wpt_pro' ), 
                                    '<a href="' . admin_url( 'post-new.php?post_type=wpt_product_table' ) . '">',
                                    '</a>'
                                    ),
                            'content_classes' => 'wpt_elementor_additional_info',
                        ]
                );
                
		$this->end_controls_section();

        }
        
        /**
         * Typography Section for Style Tab
         * 
         * @since 1.0.0.9
         */
        protected function style_table_general() {
            $this->start_controls_section(
                'style_general',
                [
                    'label'     => esc_html__( 'General', 'medilac' ),
                    'tab'       => Controls_Manager::TAB_STYLE,
                ]
            );
            
            
            
            
            
            $this->end_controls_section();
            
        }
        /**
         * Typography Section for Style Tab
         * 
         * @since 1.0.0.9
         */
        protected function style_table_head() {
            $this->start_controls_section(
                'thead',
                [
                    'label'     => esc_html__( 'Table Head', 'medilac' ),
                    'tab'       => Controls_Manager::TAB_STYLE,
                ]
            );

            $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                            'name' => 'thead_typography',
                            'global' => [
                                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                            ],
                            'selector' => '{{WRAPPER}} table.wpt_product_table thead tr th',
                    ]
            );

            $this->add_control(
                'thead-color',
                [
                    'label'     => __( 'Color', 'medilac' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} table.wpt_product_table thead tr th' => 'color: {{VALUE}}',
                    ],
                    'default'   => '#ffffff',
                ]
            );
            
            $this->add_control(
                'thead-bg-color',
                [
                    'label'     => __( 'Background Color', 'medilac' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} table.wpt_product_table thead tr th' => 'background-color: {{VALUE}}',
                    ],
                    'default'   => '#0a7f9c',
                ]
            );
            
            $this->add_control(
                'thead-border-color',
                [
                    'label'     => __( 'Border Color', 'medilac' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} table.wpt_product_table thead .wpt_table_header_row th' => 'border-color: {{VALUE}}',
                    ],
                    'default'   => '#0fc392',
                ]
            );
            
            $this->add_responsive_control(
                    'cell_gap',
                    [
                            'label' => __( 'Cell Padding', 'medilac' ),
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
                                    'size' => 10,
                            ],
                            'selectors' => [
                                    '{{WRAPPER}} .custom_table thead .wpt_table_header_row th' => 'padding: {{SIZE}}{{UNIT}};',
                            ],
                    ]
            );
            
            $this->end_controls_section();
        }
        
        protected function style_mini_cart() {
            $this->start_controls_section(
                'minicart',
                [
                    'label'     => esc_html__( 'Mini Cart', 'medilac' ),
                    'tab'       => Controls_Manager::TAB_STYLE,
                ]
            );
            
            $this->add_control(
                'mini_cart_bg_color',
                [
                    'label'     => __( 'Background', 'medilac' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wpt_product_table_wrapper div.tables_cart_message_box' => 'background-color: {{VALUE}}',
                    ],
                    'default'   => 'rgb(0 0 0 / 3%)',
                ]
            );
            
            $this->add_control(
                    'mini_header_1',
                    [
                            'label' => __( 'Cart Header', 'medilac' ),
                            'type' => Controls_Manager::HEADING,
                            'separator' => 'before',
                    ]
            );
            
            $this->add_control(
                'header-color',
                [
                    'label'     => __( 'Header Background', 'medilac' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                            '{{WRAPPER}} .wpt_product_table_wrapper div.tables_cart_message_box a.cart-contents' => 'background-color: {{VALUE}}',
                    ],
                    'default'   => '#5c6b79',
                ]
            );
            
            $this->add_control(
                'header-amount-color',
                [
                    'label'     => __( 'Header Price Text Color', 'medilac' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                            '{{WRAPPER}} .wpt_product_table_wrapper div.tables_cart_message_box a.cart-contents span.amount' => 'color: {{VALUE}}',
                    ],
                ]
            );
            
            $this->add_control(
                    'mini_header_2',
                    [
                            'label' => __( 'Cart Contents', 'medilac' ),
                            'type' => Controls_Manager::HEADING,
                            'separator' => 'before',
                    ]
            );

            $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                            'name' => 'mini_c_typography',
                            'global' => [
                                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                            ],
                            'selector' => '{{WRAPPER}} .wpt_product_table_wrapper .widget_shopping_cart_content ul li a:not(.remove), {{WRAPPER}} .wpt_product_table_wrapper .widget_shopping_cart_content ul li span.quantity',
                    ]
            );

            $this->add_control(
                'title-color',
                [
                    'label'     => __( 'Title Color', 'medilac' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                            '{{WRAPPER}} .wpt_product_table_wrapper .widget_shopping_cart_content ul li a:not(.remove)' => 'color: {{VALUE}}',
                    ],
                ]
            );
            
            $this->add_control(
                'price-color',
                [
                    'label'     => __( 'Quantity X Price Color', 'medilac' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                            '{{WRAPPER}} .wpt_product_table_wrapper .widget_shopping_cart_content ul li span.quantity' => 'color: {{VALUE}}',
                    ],
                ]
            );
            
            $this->add_control(
                    'mini_header_3',
                    [
                            'label' => __( 'Cart Total', 'medilac' ),
                            'type' => Controls_Manager::HEADING,
                            'separator' => 'before',
                    ]
            );
            
            $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                            'name' => 'mini_total_typography',
                            'global' => [
                                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                            ],
                            'selector' => '{{WRAPPER}} .wpt_product_table_wrapper .widget_shopping_cart_content p.woocommerce-mini-cart__total.total',
                    ]
            );
            
            $this->add_control(
                'mini_cart_total_price_label_color',
                [
                    'label'     => __( 'Total Price Label Color', 'medilac' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wpt_product_table_wrapper .widget_shopping_cart_content .total' => 'color: {{VALUE}}',
                    ],
                ]
            );
            
            $this->add_control(
                'mini_cart_total_price_color',
                [
                    'label'     => __( 'Total Price Text Color', 'medilac' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wpt_product_table_wrapper .widget_shopping_cart_content .total span.amount' => 'color: {{VALUE}}',
                    ],
                ]
            );
            
            $this->end_controls_section();
        }
        
        
        
        protected function style_search_box() {
            $this->start_controls_section(
                'search_box',
                [
                    'label'     => esc_html__( 'Search & Filter Box', 'medilac' ),
                    'tab'       => Controls_Manager::TAB_STYLE,
                ]
            );

            $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                            'name' => 'src_typography',
                            'global' => [
                                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                            ],
                            'selector' => '{{WRAPPER}} .wpt_filter_wrapper select, '
                        . '{{WRAPPER}} .wpt_filter_wrapper label, '
                        . '{{WRAPPER}} .wpt_filter_wrapper span, '
                        . '{{WRAPPER}} .wpt_filter_wrapper div, '
                        . '{{WRAPPER}} .wpt_filter_wrapper input, '
                        . '{{WRAPPER}} .search_box_wrapper select, '
                        . '{{WRAPPER}} .search_box_wrapper label, '
                        . '{{WRAPPER}} .search_box_wrapper span, '
                        . '{{WRAPPER}} .search_box_wrapper div, '
                        . '{{WRAPPER}} .search_box_wrapper input',
                    ]
            );

            $this->add_control(
                'color',
                [
                    'label'     => __( 'Text Color', 'medilac' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wpt_product_table_wrapper .wpt_filter_wrapper' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .wpt_product_table_wrapper .search_box_wrapper' => 'color: {{VALUE}}',
                    ],
                    'default'   => '#5c6b79',
                ]
            );
            
            $this->add_control(
                'search_box_title',
                [
                    'label'     => __( 'Show Title', 'medilac' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Yes', 'medilac' ),
                    'label_off' => __( 'No', 'medilac' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'prefix_class' => 'search-title-show-'
                ]
            );
            
            $this->end_controls_section();
        }
        
        
        
        /**
         * Typography Section for Style Tab
         * 
         * @since 1.0.0.9
         */
        protected function style_table_body() {
            
            
            $this->start_controls_section(
                'tbody',
                [
                    'label'     => esc_html__( 'Table Body', 'medilac' ),
                    'tab'       => Controls_Manager::TAB_STYLE,
                ]
            );

            $this->add_control(
                    'title_typo',
                    [
                            'label' => __( 'Product Title', 'medilac' ),
                            'type' => Controls_Manager::HEADING,
                    ]
            );
            
            $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                            'name' => 'tbody_title_typography',
                            'selector' => '{{WRAPPER}} table.wpt_product_table tbody tr td .product_title a:not(.product_description), {{WRAPPER}} table.wpt_product_table tbody tr td .product_title span:not(.product_description)',
                    ]
            );
            
            $this->add_control(
                'tbody_title_color',
                [
                    'label'     => __( 'Color', 'medilac' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} table.wpt_product_table tbody tr td .product_title a:not(.product_description), {{WRAPPER}} table.wpt_product_table tbody tr td .product_title span:not(.product_description)' => 'color: {{VALUE}}',
                    ],
                    'default'   => '#535353',
                ]
            );
            
            $this->add_control(
                    'links_typo',
                    [
                            'label' => __( 'Links', 'medilac' ),
                            'type' => Controls_Manager::HEADING,
                            'separator' => 'before',
                    ]
            );
            
            $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                            'name' => 'tbody_links_typography',
                            'selector' => '{{WRAPPER}} table.wpt_product_table tbody tr td:not(.wpt_product_title):not(.wpt_action) a',
                    ]
            );
            
            $this->add_control(
                'tbody_links_color',
                [
                    'label'     => __( 'Color', 'medilac' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} table.wpt_product_table tbody tr td:not(.wpt_product_title):not(.wpt_action) a' => 'color: {{VALUE}}',
                    ],
                    'default'   => '#535353',
                ]
            );
            
            $this->add_control(
                    'price_typo',
                    [
                            'label' => __( 'Price', 'medilac' ),
                            'type' => Controls_Manager::HEADING,
                            'separator' => 'before',
                    ]
            );
            
            $this->start_controls_tabs('product__price');
            
            // Sale Price
            $this->start_controls_tab(
                    'tab_sale_price',
                    [
                            'label'  => esc_html__( 'Sale Price', 'medilac' )
                    ]
            );
            
            $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                            'name' => 'tbody_sale_price_typography',
                            'selector' => '{{WRAPPER}} table.wpt_product_table tbody tr td.wpt_price del',
                    ]
            );
            
            $this->add_control(
                'tbody_sale_price_color',
                [
                    'label'     => __( 'Text Color', 'medilac' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} table.wpt_product_table tbody tr td.wpt_price del' => 'color: {{VALUE}}',
                        
                    ],
                    'default'   => '#535353',
                ]
            );
            
            $this->end_controls_tab();
            
            $this->start_controls_tab(
                    'tab_regular_price',
                    [
                            'label'  => esc_html__( 'Regular Price', 'medilac' )
                    ]
            );
            
            $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                            'name' => 'tbody_regular_price_typography',
                            'selector' => '{{WRAPPER}} table.wpt_product_table tbody tr td.wpt_price ins, {{WRAPPER}} table.wpt_product_table tbody tr td.wpt_price .wpt_product_price',
                    ]
            );
            
            $this->add_control(
                'tbody_regular_price_color',
                [
                    'label'     => __( 'Text Color', 'medilac' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} table.wpt_product_table tbody tr td.wpt_price ins' => 'color: {{VALUE}}',
                        '{{WRAPPER}} table.wpt_product_table tbody tr td.wpt_price span.wpt_product_price' => 'color: {{VALUE}}',
                        '{{WRAPPER}} table.wpt_product_table tbody tr td.wpt_price span.price' => 'color: {{VALUE}}',
                        
                    ],
                    'default'   => '#535353',
                ]
            );
            
            $this->end_controls_tab();
            
            $this->end_controls_tabs();
            
            
            
            $this->add_control(
                    'quantity_style',
                    [
                            'label' => __( 'Quantity', 'medilac' ),
                            'type' => Controls_Manager::HEADING,
                            'separator' => 'before',
                    ]
            );
            
            $this->add_control(
                'tbody_qty_color',
                [
                    'label'     => __( 'Color', 'medilac' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wpt_product_table_wrapper .quantity .qty' => 'color: {{VALUE}}',
                        
                    ],
                    'default'   => '#222',
                ]
            );
            
            $this->add_control(
                'tbody_qty_bg_color',
                [
                    'label'     => __( 'Background Color', 'medilac' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wpt_product_table_wrapper .quantity .qty' => 'background-color: {{VALUE}}',
                        
                    ],
                    'default'   => '#fff',
                ]
            );
            
            $this->add_responsive_control(
                    'qty_input',
                    [
                            'label' => __( 'Padding', 'medilac' ),
                            'type' => Controls_Manager::DIMENSIONS,
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
                                    'top' => 5,
                                    'bottom' => 5,
                                    'left' => 10,
                                    'right' => 10,
                            ],
                            'selectors' => [
                                    '{{WRAPPER}} .wpt_product_table_wrapper .quantity .qty' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                    ]
            );
            
            $this->add_control(
                    'others_typo',
                    [
                            'label' => __( 'Others', 'medilac' ),
                            'type' => Controls_Manager::HEADING,
                            'separator' => 'before',
                    ]
            );
            
            $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                            'name' => 'tbody_other_typography',
                            'selector' => '{{WRAPPER}} table.wpt_product_table tbody tr td p, {{WRAPPER}} table.wpt_product_table tbody tr td:not(.wpt_product_title):not(.wpt_action):not(.wpt_price) div, {{WRAPPER}} table.wpt_product_table tbody tr td div.product_description',
                    ]
            );

            $this->add_control(
                'tbody-other-color',
                [
                    'label'     => __( 'Text Color', 'medilac' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} table.wpt_product_table tbody tr td p, {{WRAPPER}} table.wpt_product_table tbody tr td:not(.wpt_product_title):not(.wpt_action):not(.wpt_price) div, {{WRAPPER}} table.wpt_product_table tbody tr td div.product_description' => 'color: {{VALUE}}',
                        
                    ],
                    'default'   => '#535353',
                ]
            );
            
            
            $this->add_control(
                'tbody-bg-color',
                [
                    'label'     => __( 'Background Color', 'medilac' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} table.wpt_product_table tbody tr td' => 'background-color: {{VALUE}}',
                    ],
                    'default'   => '#fff',
                ]
            );
            
            $this->add_control(
                'striped_table',
                [
                    'label'     => __( 'Striped Table', 'medilac' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Yes', 'medilac' ),
                    'label_off' => __( 'No', 'medilac' ),
                    'return_value' => 'yes',
                    'default' => 'yes',                    
                ]
            );
            
            $this->add_control(
                'tbody_bg_color_striped',
                [
                    'label'     => __( 'Background Striped Color', 'medilac' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} table.wpt_product_table tbody tr:nth-child(2n+2) td' => 'background-color: {{VALUE}}',
                    ],
                    'default' => 'rgba(0,0,0,.05)',
                    'condition' => [
                            'striped_table' => 'yes',
                    ],
                ]
            );
            
            $this->add_control(
                'tbody_border_color_striped',
                [
                    'label'     => __( 'Border Color', 'medilac' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} table.wpt_product_table tbody tr td' => 'border-color: {{VALUE}}',
                    ],
                    'default' => 'rgba(0,0,0,.05)',
                ]
            );
            
            $this->add_responsive_control(
                    'cell_gap_body',
                    [
                            'label' => __( 'Cell Padding', 'medilac' ),
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
                                    'size' => 10,
                            ],
                            'selectors' => [
                                    '{{WRAPPER}} table.wpt_product_table tbody tr td' => 'padding: {{SIZE}}{{UNIT}};',
                            ],
                    ]
            );
            
            
            
            $this->end_controls_section();
        }
    
        /**
         * Typography for Table Column
         * with Repeater
         * 
         * We will take list from  WOO Product Table
         * 
         * @since 1.0.1.0
         * @access protected
         */
        protected function style_column_design(){
            $this->start_controls_section(
                    'design_column',
                    [
                        'label' => __( 'Design Column', 'ultraaddons' ),
                        'tab'       => Controls_Manager::TAB_STYLE,
                    ]
            );
            
            /**
             * sample options
                    [
                            'left'     => __( 'Left', 'medilac' ),
                            'right'     => __( 'Right', 'medilac' ),
                    ]
             */
            $repeater = new Repeater();
            $columns_options = \WPT_Product_Table::$columns_array;
            $repeater->add_control(
                    'column_name',
                    [
                            'label' => __( 'Select a Column', 'ultraaddons' ),
                            'type' => Controls_Manager::SELECT,
                            'options' => $columns_options,
                        'default' => '',

                    ]
            );
            
            $repeater->add_control(
                    'color',
                    [
                            'label' => __( 'Color', 'ultraaddons' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                    '{{WRAPPER}} table.wpt_product_table tbody tr td.wpt_product_title,{{WRAPPER}} table.wpt_product_table tbody tr td.wpt_{{column_name.VALUE}}' => 'color: {{VALUE}} !important',
//                                    '{{WRAPPER}} table.wpt_product_table tbody tr td.wpt_{{{ column_name }}}' => 'color: {{VALUE}} !important',
                                    
                                    
                            ],
                        'condition'   => [
                            'column_name!' => '',
                        ],
                    ]
            );
            
            $repeater->add_control(
                    'background-color',
                    [
                            'label' => __( 'Background Color', 'ultraaddons' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                    '{{WRAPPER}} .wpt_product_table .wpt{{{ column_name }}}' => 'color: {{VALUE}}',
                                    
                                    
                            ],
                        'condition'   => [
                            'column_name!' => '',
                        ],
                    ]
            );
            
            $repeater->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                            'name' => 'tbody_typography',
                            'global' => [
                                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} table.wpt_product_table tbody tr td',
                            ],
                            'condition'   => [
                                'column_name!' => '',
                            ],
                    ]
            );
            
            //var_dump($repeater->get_config());
            $repeater->end_controls_tab();
            
            
            $this->add_control(
                    'table_column',
                    [
                            'type' => Controls_Manager::REPEATER,
                            'fields' => $repeater->get_controls(),
                            'default'   => [
                                [
                                    'column_name' => 'product_title',
                                ],
                                
                                [
                                    'column_name' => 'price',
                                ],
                                
                                
                                [
                                    'column_name' => 'quantity',
                                ],
                                
                                [
                                    'column_name' => 'category',
                                ],
                                
                                
                                [
                                    'column_name' => 'action',
                                ],
                                
                                [
                                    'column_name' => 'stock',
                                ],
                                
                                
                                
                                
                            ],
                            'title_field' => '{{{ column_name }}}',
                    ]
            );
            $this->end_controls_section();
            
        }
        
        /**
         * Button Design
         * 
         * 
         * @since 1.0.1.0
         * @access protected
         */
        protected function style_button(){
            
            $this->start_controls_section(
                'button_style_section',
                [
                    'label' => __( 'Button', 'ultraaddons' ),
                    'tab'       => Controls_Manager::TAB_STYLE,
                ]
            );
            
            $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                            'name' => 'wpt_button_typography',
                            'selector' => '{{WRAPPER}} .wpt_product_table_wrapper table.wpt_product_table tbody tr.wpt_row td a.add_to_cart_button, {{WRAPPER}} .wpt_product_table_wrapper table.wpt_product_table tbody tr.wpt_row td a.button, {{WRAPPER}} .wpt_product_table_wrapper .all_check_header_footer a.button.add_to_cart_all_selected, {{WRAPPER}} .wpt_product_table_wrapper .widget_shopping_cart_content p.woocommerce-mini-cart__buttons.buttons a.button.wc-forward',
                    ]
            );
            
            $this->add_responsive_control(
                    'button_padding',
                    [
                            'label' => __( 'Padding', 'ultraaddons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'default' => [
                                'left' => 15,
                                'right' => 15,
                                'top' => 5,
                                'bottom' => 5,
                            ],
                            'selectors' => [
                                    '{{WRAPPER}} .wpt_product_table_wrapper table.wpt_product_table tbody tr.wpt_row td a.add_to_cart_button, {{WRAPPER}} .wpt_product_table_wrapper table.wpt_product_table tbody tr.wpt_row td a.button, {{WRAPPER}} .wpt_product_table_wrapper .all_check_header_footer a.button.add_to_cart_all_selected, {{WRAPPER}} .wpt_product_table_wrapper .widget_shopping_cart_content p.woocommerce-mini-cart__buttons.buttons a.button.wc-forward' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                    ]
            );
            
            $this->start_controls_tabs( 'button_style_tab' );
            
            $this->start_controls_tab(
                    'wpt_button_style_normal',
                    [
                            'label'  => esc_html__( 'Normal', 'medilac' ),
                    ]
            );
            
            $this->add_control(
                'wpt_button_color',
                [
                    'label'     => __( 'Color', 'medilac' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wpt_product_table_wrapper table.wpt_product_table tbody tr.wpt_row td a.add_to_cart_button, {{WRAPPER}} .wpt_product_table_wrapper table.wpt_product_table tbody tr.wpt_row td a.button' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .wpt_product_table_wrapper .all_check_header_footer a.button.add_to_cart_all_selected' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .wpt_product_table_wrapper .widget_shopping_cart_content p.woocommerce-mini-cart__buttons.buttons a.button.wc-forward' => 'color: {{VALUE}}',
                    ],
                    'default'   => '#fff',
                ]
            );
            
            $this->add_control(
                'wpt_button_bg_color',
                [
                    'label'     => __( 'Background Color', 'medilac' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wpt_product_table_wrapper table.wpt_product_table tbody tr.wpt_row td a.add_to_cart_button, {{WRAPPER}} .wpt_product_table_wrapper table.wpt_product_table tbody tr.wpt_row td a.button' => 'background-color: {{VALUE}}',
                        '{{WRAPPER}} .wpt_product_table_wrapper .all_check_header_footer a.button.add_to_cart_all_selected' => 'background-color: {{VALUE}}',
                        '{{WRAPPER}} .wpt_product_table_wrapper .widget_shopping_cart_content p.woocommerce-mini-cart__buttons.buttons a.button.wc-forward' => 'background-color: {{VALUE}}',
                    ],
                    'default'   => '#0fc392',
                ]
            );
            
            $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                            'name' => 'border',
                            'label' => __( 'Border', 'medilac' ),
                            'selector' => '{{WRAPPER}} .wpt_product_table_wrapper table.wpt_product_table>tbody tr.wpt_row td a.add_to_cart_button, {{WRAPPER}} .wpt_product_table_wrapper table.wpt_product_table>tbody tr.wpt_row td a.button, {{WRAPPER}} .wpt_product_table_wrapper .all_check_header_footer a.button.add_to_cart_all_selected, {{WRAPPER}} .wpt_product_table_wrapper .widget_shopping_cart_content p.woocommerce-mini-cart__buttons.buttons a.button.wc-forward',
                    ]
            );
            
            $this->end_controls_tab();
            
            $this->start_controls_tab(
                    'wpt_button_style_hover',
                    [
                            'label'  => esc_html__( 'Hover', 'medilac' ),
                    ]
            );
            
            $this->add_control(
                'wpt_button_color_hover',
                [
                    'label'     => __( 'Color', 'medilac' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wpt_product_table_wrapper table.wpt_product_table tbody tr.wpt_row td a.add_to_cart_button:hover, {{WRAPPER}} .wpt_product_table_wrapper table.wpt_product_table tbody tr.wpt_row td a.button:hover' => 'color: {{VALUE}} !important',
                        '{{WRAPPER}} .wpt_product_table_wrapper .all_check_header_footer a.button.add_to_cart_all_selected:hover' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .wpt_product_table_wrapper .widget_shopping_cart_content p.woocommerce-mini-cart__buttons.buttons a.button.wc-forward:hover' => 'color: {{VALUE}}',
                    ],
                    'default'   => '#0fc392',
                ]
            );
            
            $this->add_control(
                'wpt_button_bg_color_hover',
                [
                    'label'     => __( 'Background Color', 'medilac' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wpt_product_table_wrapper table.wpt_product_table tbody tr.wpt_row td a.add_to_cart_button:hover, {{WRAPPER}} .wpt_product_table_wrapper table.wpt_product_table tbody tr.wpt_row td a.button:hover' => 'background-color: {{VALUE}} !important',
                        '{{WRAPPER}} .wpt_product_table_wrapper .all_check_header_footer a.button.add_to_cart_all_selected:hover' => 'background-color: {{VALUE}}',
                        '{{WRAPPER}} .wpt_product_table_wrapper .widget_shopping_cart_content p.woocommerce-mini-cart__buttons.buttons a.button.wc-forward:hover' => 'background-color: {{VALUE}}',
                    ],
                    'default'   => '#fff',
                ]
            );
            
            $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                            'name' => 'border_hover',
                            'label' => __( 'Border', 'medilac' ),
                            'selector' => '{{WRAPPER}} .wpt_product_table_wrapper table.wpt_product_table>tbody tr.wpt_row td a.add_to_cart_button:hover, {{WRAPPER}} .wpt_product_table_wrapper table.wpt_product_table>tbody tr.wpt_row td a.button:hover, {{WRAPPER}} .wpt_product_table_wrapper .all_check_header_footer a.button.add_to_cart_all_selected:hover, {{WRAPPER}} .wpt_product_table_wrapper .widget_shopping_cart_content p.woocommerce-mini-cart__buttons.buttons a.button.wc-forward:hover',
                    ]
            );
            
            $this->end_controls_tab();
            
            $this->end_controls_tabs();
            
            
            
            
            $this->end_controls_section();
            
        }
        
        /**
         * Footer Cart
         * 
         * 
         * @since 1.0.1.0
         * @access protected
         */
        protected function style_footer_cart(){
            
            $this->start_controls_section(
                'fc_style_section',
                [
                    'label' => __( 'Footer Cart', 'ultraaddons' ),
                    'tab'       => Controls_Manager::TAB_STYLE,
                ]
            );
            
            $this->add_responsive_control(
                    'fc_size',
                    [
                            'label' => __( 'Size', 'medilac' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px' ],
                            'range' => [
                                    'px' => [
                                            'min' => 40,
                                            'max' => 200,
                                            'step' => 5,
                                    ],
                            ],
                            'default' => [
                                    'unit' => 'px',
                                    'size' => 74,
                            ],
                            'selectors' => [
                                    '{{WRAPPER}} .wpt-footer-cart-wrapper' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                            ],
                    ]
            );
            
            $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'fc_cart_background',
				'label' => __( 'Background', 'medilac' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wpt-footer-cart-wrapper > a',
			]
		);
            
            $this->end_controls_section();
            
        }
}
