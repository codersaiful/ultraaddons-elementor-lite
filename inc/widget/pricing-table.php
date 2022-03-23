<?php
namespace UltraAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Pricing_Table extends Base {
    
    use \UltraAddons\Traits\Button_Helper;
    
    public function get_keywords() {
            return [ 'ultraaddons','ua', 'pricing', 'table', 'price', 'list', 'compare' ];
    }
    
    /**
     * Register widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls() {
            
        $this->btn_align = 'center';
        
        

		$this->content_header_controls();
		$this->content_pricing_controls();
		$this->content_feature_controls();
		$this->content_footer_controls();
		
		/**
		 * All Style Control has transferred to
		 * protected method
		 * 
		 * @since 1.0.4.1
		 */
		$this->style_general_controls();
		$this->style_header_controls();
		$this->style_pricing_controls();
		$this->style_feature_controls();
		$this->style_footer_controls();
		$this->style_box();
		
		/**
		 * Register Controls of Button
		 * will come from our Trait Button Helper
		 * 
		 * @since 1.0.3.2
		 * @date 7.3.2021
		 */
		$this->button_register_controls();
    }
    protected function content_header_controls(){
        $this->start_controls_section(
			'section_header',
			[
				'label' => __( 'Header', 'ultraaddons' ),
			]
		);

		$this->add_control(
			'heading',
			[
				'label' => __( 'Title', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Basic Package', 'ultraaddons' ),
			]
		);

		$this->add_control(
			'sub_heading',
			[
				'label' => __( 'Description', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
			]
		);

		$this->add_control(
			'heading_tag',
			[
				'label' => __( 'Heading Tag', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				],
				'default' => 'h3',
			]
		);
                
                $this->add_control(
                        'ua_icon_select',
                        [
                                'label'     => esc_html__( 'Select Icon/Image', 'ultraaddons' ),
                                'type'      => Controls_Manager::SELECT,
                                'options'   => [
                                        'icon'      => __( 'Icon', 'ultraaddons' ),
                                        'image'     => __( 'Image', 'ultraaddons')
                                ],
                                'default'       => 'icon',

                        ]
                );
                $this->add_control(
                        'ua_icon_choose',
                        [
                                'label'     => __( 'Icon', 'ultraaddons' ),
                                'type' => Controls_Manager::ICONS,
                                'fa4compatibility' => 'icon',
                                'default' => [
                                    'value' => 'uicon uicon-ultraaddons',
                                    'library' => 'ultraaddons',
                                ],
                                'condition' => [
                                        'ua_icon_select' => 'icon',
                                ]
                        ]
            );

                $this->add_control(
                        'ua_image_upload',
                        [
                                'label'     => __( 'Select Image', 'ultraaddons' ),
                                'type'      => Controls_Manager::MEDIA,
                                'condition' => [
                                        'ua_icon_select'    => 'image',
                                ]
                        ]
                );
                
		$this->end_controls_section();
    }
    protected function content_pricing_controls(){
                $this->start_controls_section(
			'section_pricing',
			[
				'label' => __( 'Pricing', 'ultraaddons' ),
			]
		);

		$this->add_control(
			'currency_symbol',
			[
				'label' => __( 'Currency Symbol', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'None', 'ultraaddons' ),
					'dollar' => '&#36; ' . _x( 'Dollar', 'Currency Symbol', 'ultraaddons' ),
					'euro' => '&#128; ' . _x( 'Euro', 'Currency Symbol', 'ultraaddons' ),
					'baht' => '&#3647; ' . _x( 'Baht', 'Currency Symbol', 'ultraaddons' ),
					'franc' => '&#8355; ' . _x( 'Franc', 'Currency Symbol', 'ultraaddons' ),
					'guilder' => '&fnof; ' . _x( 'Guilder', 'Currency Symbol', 'ultraaddons' ),
					'krona' => 'kr ' . _x( 'Krona', 'Currency Symbol', 'ultraaddons' ),
					'lira' => '&#8356; ' . _x( 'Lira', 'Currency Symbol', 'ultraaddons' ),
					'peseta' => '&#8359 ' . _x( 'Peseta', 'Currency Symbol', 'ultraaddons' ),
					'peso' => '&#8369; ' . _x( 'Peso', 'Currency Symbol', 'ultraaddons' ),
					'pound' => '&#163; ' . _x( 'Pound Sterling', 'Currency Symbol', 'ultraaddons' ),
					'real' => 'R$ ' . _x( 'Real', 'Currency Symbol', 'ultraaddons' ),
					'ruble' => '&#8381; ' . _x( 'Ruble', 'Currency Symbol', 'ultraaddons' ),
					'rupee' => '&#8360; ' . _x( 'Rupee', 'Currency Symbol', 'ultraaddons' ),
					'indian_rupee' => '&#8377; ' . _x( 'Rupee (Indian)', 'Currency Symbol', 'ultraaddons' ),
					'shekel' => '&#8362; ' . _x( 'Shekel', 'Currency Symbol', 'ultraaddons' ),
					'yen' => '&#165; ' . _x( 'Yen/Yuan', 'Currency Symbol', 'ultraaddons' ),
					'won' => '&#8361; ' . _x( 'Won', 'Currency Symbol', 'ultraaddons' ),
					'custom' => __( 'Custom', 'ultraaddons' ),
				],
				'default' => 'dollar',
			]
		);

		$this->add_control(
			'currency_symbol_custom',
			[
				'label' => __( 'Custom Symbol', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'currency_symbol' => 'custom',
				],
			]
		);

		$this->add_control(
			'price',
			[
				'label' => __( 'Price', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '39.99',
				'dynamic' => [
					'active' => true,
				],
			]
		);
                
                $this->add_control(
			'currency_format',
			[
				'label' => __( 'Currency Format', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => '1,234.56 (Default)',
					',' => '1.234,56',
				],
			]
		);
                

		$this->add_control(
			'currency_style',
			[
				'label' => __( 'Currency Style', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'1' => __( 'Style 1', 'ultraaddons' ),
					'2' => __( 'Style 2', 'ultraaddons' ),
				],
                                'default' => '1',
			]
		);

		$this->add_control(
			'sale',
			[
				'label' => __( 'Sale', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'ultraaddons' ),
				'label_off' => __( 'Off', 'ultraaddons' ),
				'default' => '',
			]
		);

		$this->add_control(
			'original_price',
			[
				'label' => __( 'Sale Price', 'ultraaddons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => '59',
				'condition' => [
					'sale' => 'yes',
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'period',
			[
				'label' => __( 'Period', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( '/mon', 'ultraaddons' ),
			]
		);

                $this->add_control(
                    'price_top',
                    [
                            'label' => __( 'Price in Top', 'ultraaddons' ),
                            'type' => Controls_Manager::SWITCHER,
                            'label_on' => __( 'On', 'ultraaddons' ),
                            'label_off' => __( 'Off', 'ultraaddons' ),
                            'return_value' => 'yes',    
                    ]
                );
		$this->end_controls_section();
    }
    protected function content_feature_controls(){
                $this->start_controls_section(
			'section_features',
			[
				'label' => __( 'Features', 'ultraaddons' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_text',
			[
				'label' => __( 'Text', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'List Item', 'ultraaddons' ),
			]
		);

		$default_icon = [
			'value' => 'far fa-check-circle',
			'library' => 'fa-regular',
		];

		$repeater->add_control(
			'selected_item_icon',
			[
				'label' => __( 'Icon', 'ultraaddons' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'item_icon',
				'default' => $default_icon,
			]
		);

		$repeater->add_control(
			'item_icon_color',
			[
				'label' => __( 'Icon Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} i' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'features_list',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'item_text' => __( 'List Item #1', 'ultraaddons' ),
						'selected_item_icon' => $default_icon,
					],
					[
						'item_text' => __( 'List Item #2', 'ultraaddons' ),
						'selected_item_icon' => $default_icon,
					],
					[
						'item_text' => __( 'List Item #3', 'ultraaddons' ),
						'selected_item_icon' => $default_icon,
					],
				],
				'title_field' => '{{{ item_text }}}',
			]
		);

		$this->end_controls_section();
    }
    protected function content_footer_controls(){
                $this->start_controls_section(
			'section_footer',
			[
				'label' => __( 'Footer', 'ultraaddons' ),
			]
		);

		
		$this->add_control(
			'footer_additional_info',
			[
				'label' => __( 'Additional Info', 'ultraaddons' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => '',
				'rows' => 3,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_ribbon',
			[
				'label' => __( 'Ribbon', 'ultraaddons' ),
			]
		);

		$this->add_control(
			'show_ribbon',
			[
				'label' => __( 'Show', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'ribbon_title',
			[
				'label' => __( 'Title', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Popular', 'ultraaddons' ),
				'condition' => [
					'show_ribbon' => 'yes',
				],
			]
		);

		$this->add_control(
			'ribbon_x_position',
			[
				'label' => __( 'Position X', 'ultraaddons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'ultraaddons' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'ultraaddons' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'condition' => [
					'show_ribbon' => 'yes',
				],
                                'prefix_class' => 'ultraaddons-featured-item ua-ribbon-',
                                
                                'default' => 'left',
			]
		);
		$this->add_control(
			'ribbon_y_position',
			[
				'label' => __( 'Position Y', 'ultraaddons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => __( 'Top', 'ultraaddons' ),
						'icon' => 'eicon-v-align-top',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'ultraaddons' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'condition' => [
					'show_ribbon' => 'yes',
				],
                                'prefix_class' => 'ultraaddons-featured-item ua-ribbon-',
                                
                                'default' => 'top',
			]
		);
                
                $this->add_control(
                        'ribbon_angle_45',
                        [
                                'label' => __( 'Angle Ribbon', 'ultraaddons' ),
                                'type' => Controls_Manager::SWITCHER,
                                'label_on' => __( 'On', 'ultraaddons' ),
                                'label_off' => __( 'Off', 'ultraaddons' ),
                                'return_value' => 'yes',
                                'prefix_class'  => 'ua-angle-ribbon-',
                                'condition' => [
					'show_ribbon' => 'yes',
				],

                        ]
                );
                
		$this->end_controls_section();
                
    }
    protected function style_general_controls(){
                $this->start_controls_section(
			'section_header_general_style',
			[
				'label' => __( 'General', 'ultraaddons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);
                
//                $this->add_control(
//			'template',
//			[
//				'label' => __( 'Style', 'ultraaddons' ),
//				'type' => Controls_Manager::SELECT,
//				'options' => [
//					'1' => __( 'Lite', 'ultraaddons' ),
//					'2' => __( 'Dark', 'ultraaddons' ),
//				],
//				'default' => '1',
//                                'prefix_class' => 'pricing-table-temp-'
//			]
//		);
//                
                $this->add_responsive_control(
                        'price_table_align',
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
                                        ],
                                ],
                                'prefix_class' => 'elementor%s-align-',
                                'default' => 'center',
                                'toggle' => false,
								
                        ]
                );
                
                
            $this->add_control(
			'section_divider',
			[
				'label' => __( 'Divider', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'divider_style',
			[
				'label' => __( 'Style', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => __( 'Solid', 'ultraaddons' ),
					'double' => __( 'Double', 'ultraaddons' ),
					'dotted' => __( 'Dotted', 'ultraaddons' ),
					'dashed' => __( 'Dashed', 'ultraaddons' ),
				],
				'default' => 'solid',
				'condition' => [
					'section_divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} ul.ua-price-table__features-list:before' => 'border-top-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label' => __( 'Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ddd',
				'scheme' => [
					'type' => Schemes\Color::get_type(),
					'value' => Schemes\Color::COLOR_3,
				],
				'condition' => [
					'section_divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} ul.ua-price-table__features-list:before' => 'border-top-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'divider_weight',
			[
				'label' => __( 'Weight', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 2,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'condition' => [
					'section_divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} ul.ua-price-table__features-list:before' => 'border-top-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'divider_width',
			[
				'label' => __( 'Width', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
                                'default' => [
					'size' => 15,
					'unit' => 'px',
				],
                                'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'condition' => [
					'section_divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} ul.ua-price-table__features-list:before' => 'margin-left: calc((100% - {{SIZE}}%)/2); margin-right: calc((100% - {{SIZE}}%)/2)',
				],
			]
		);

		$this->add_control(
			'divider_gap',
			[
				'label' => __( 'Gap', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
					],
				],
				'condition' => [
					'section_divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} ul.ua-price-table__features-list:before' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);
                
                
                $this->end_controls_section();
    }
    protected function style_header_controls(){
                $this->start_controls_section(
			'section_header_style',
			[
				'label' => __( 'Header', 'ultraaddons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

                $this->start_controls_tabs('header_tabs');
                
                $this->start_controls_tab(
                'header_tab_normal', 
                [
                    'label' => __( 'Normal', 'ultraaddons' ),
                ]
                );
                
		$this->add_control(
			'header_bg_color',
			[
				'label' => __( 'Background Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-price-table__header' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'header_padding',
			[
				'label' => __( 'Padding', 'ultraaddons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ua-price-table__header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_heading_style',
			[
				'label' => __( 'Title', 'ultraaddons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label' => __( 'Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-price-table__heading' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'heading_typography',
				'selector' => '{{WRAPPER}} .ua-price-table__heading',
				'scheme' => Schemes\Typography::TYPOGRAPHY_1,
                                'default' => [
                                        'font-weight'   => 400,
                                ],
			]
		);

		$this->add_control(
			'heading_sub_heading_style',
			[
				'label' => __( 'Sub Title', 'ultraaddons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sub_heading_color',
			[
				'label' => __( 'Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-price-table__subheading' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sub_heading_typography',
				'selector' => '{{WRAPPER}} .ua-price-table__subheading',
				'scheme' => Schemes\Typography::TYPOGRAPHY_2,
			]
		);
                
                $this->add_control(
			'heading_icon_style',
			[
				'label' => __( 'Icon', 'ultraaddons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
                
                $this->add_responsive_control(
                        'header_icon_size',
                        [
                                'label' => __( 'Icon Size', 'ultraaddons' ),
                                'type' => Controls_Manager::SLIDER,
                                'range' => [
                                        'px' => [
                                                'min' => 6,
                                                'max' => 300,
                                        ],
                                ],
                                'default' => [
                                        'size' => 30,
                                ],
                                'selectors' => [
                                        '{{WRAPPER}} .ua-price-table__header_icon' => 'font-size: {{SIZE}}{{UNIT}};',
                                ],
                        ]
                );
                
                $this->add_control(
			'header_icon_color',
			[
				'label' => __( 'Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-pricing-table-icon' => 'color: {{VALUE}}',
				],
			]
		);
                
                $this->end_controls_tab();
                
                $this->start_controls_tab(
                'header_tab_hover', 
                [
                    'label' => __( 'Hover', 'ultraaddons' ),
                ]
                );
                
                $this->add_control(
			'header_bg_color_hover',
			[
				'label' => __( 'Background Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}:hover .ua-price-table__header' => 'background-color: {{VALUE}}',
				],
			]
		);
                
                $this->add_control(
			'heading_color_hover',
			[
				'label' => __( 'Title Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}:hover .ua-price-table__heading' => 'color: {{VALUE}}',
				],
			]
		);
                
                $this->add_control(
			'sub_heading_color_hover',
			[
				'label' => __( 'Subtitle Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}:hover .ua-price-table__subheading' => 'color: {{VALUE}}',
				],
			]
		);
                
                $this->end_controls_tab();
                
                $this->end_controls_tabs();
                

		$this->end_controls_section();
                
    }
    protected function style_pricing_controls(){
                $this->start_controls_section(
			'section_pricing_element_style',
			[
				'label' => __( 'Pricing', 'ultraaddons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);
                
		
                $this->start_controls_tabs('price_tabs');
                
                $this->start_controls_tab(
                'price_tab_normal', 
                [
                    'label' => __( 'Normal', 'ultraaddons' ),
                ]
                );
                
                $this->add_control(
			'pricing_element_bg_color',
			[
				'label' => __( 'Background Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-price-table__price' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'pricing_element_padding',
			[
				'label' => __( 'Padding', 'ultraaddons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ua-price-table__price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'price_color',
			[
				'label' => __( 'Text Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-price-table__currency, {{WRAPPER}} .ua-price-table__integer-part, {{WRAPPER}} .ua-price-table__fractional-part, {{WRAPPER}} .ua-price-table__currency_sep' => 'color: {{VALUE}}',
				],
//				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_typography',
				'selector' => '{{WRAPPER}} .ua-price-table__price_inner',
				'scheme' => Schemes\Typography::TYPOGRAPHY_1,
			]
		);
                
                 $this->add_control(
			'price_box_size',
			[
				'label' => __( 'Price Box Size', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ua-price-table__price' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
				],
			]
		);
                 
                 
                
                $this->end_controls_tab();
                
                $this->start_controls_tab('price_tab_hover', 
                        [
                            'label' => __( 'Hover', 'ultraaddons' ),
                        ]
                );
                
                
                $this->add_control(
			'pricing_element_bg_color_hover',
			[
				'label' => __( 'Background Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}:hover .ua-price-table__price' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'pricing_element_padding_hover',
			[
				'label' => __( 'Padding', 'ultraaddons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}}:hover .ua-price-table__price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'price_color_hover',
			[
				'label' => __( 'Text Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}:hover .ua-price-table__currency, {{WRAPPER}}:hover .ua-price-table__integer-part, {{WRAPPER}}:hover .ua-price-table__fractional-part, {{WRAPPER}}:hover .ua-price-table__currency_sep, {{WRAPPER}}:hover .ua-price-table__period' => 'color: {{VALUE}}',
				],
//				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_typography_hover',
				'selector' => '{{WRAPPER}}:hover .ua-price-table__price_inner',
				'scheme' => Schemes\Typography::TYPOGRAPHY_1,
			]
		);
                
                 $this->add_control(
			'price_box_size_hover',
			[
				'label' => __( 'Price Box Size', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}}:hover .ua-price-table__price' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
				],
			]
		);
                 
                 
                
                
                $this->end_controls_tab();
                
                
                $this->end_controls_tabs();
                
                //Group_Control_Border
                $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'price_box_border',
				'selector' => '{{WRAPPER}} .ua-price-table__price',
			]
		);
                
                
                $this->add_control(
			'price_addional_control',
			[
				'label' => __( 'Show Additional Controls?', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
                                'label_on' => __( 'Show', 'ultraaddons' ),
				'label_off' => __( 'Hide', 'ultraaddons' ),
				'default' => '',
			]
		);
                
               
                
                $this->add_control(
			'price_int_size',
			[
				'label' => __( 'Price Before Fraction', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} span.ua-price-table__integer-part' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'price_addional_control!' => '',
				],
			]
		);
                
                $this->add_control(
			'price_decimal_size',
			[
				'label' => __( 'Price After Fraction', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} span.ua-price-table__fractional-part' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'price_addional_control!' => '',
				],
			]
		);
                
                $this->add_control(
			'currency_fraction_position',
			[
				'label' => __( 'Fraction Position', 'ultraaddons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'right',
				'options' => [
					'left' => [
						'title' => __( 'Before', 'ultraaddons' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __( 'After', 'ultraaddons' ),
						'icon' => 'eicon-h-align-right',
					],
				],
                                'selectors_dictionary' => [
                                        'left' => 'flex-start',
                                        'right' => 'flex-end',
                                ],
                                'selectors' => [
                                        '{{WRAPPER}} span.ua-price-table__fractional-part' => 'align-self: {{VALUE}}',
                                ],
                                'condition' => [
					'price_addional_control!' => '',
				],
			]
		);
                
                

		$this->add_control(
			'heading_currency_style',
			[
				'label' => __( 'Currency Symbol', 'ultraaddons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'currency_symbol!' => '',
				],
			]
		);

		$this->add_control(
			'currency_size',
			[
				'label' => __( 'Size', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
                                'default' => [
                                        'size' => 60,
                                ],
				'selectors' => [
					'{{WRAPPER}} .ua-price-table__currency' => 'font-size: calc({{SIZE}}em/100)',
				],
				'condition' => [
					'currency_symbol!' => '',
				],
			]
		);

		$this->add_control(
			'currency_position',
			[
				'label' => __( 'Position', 'ultraaddons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'before',
				'options' => [
					'before' => [
						'title' => __( 'Before', 'ultraaddons' ),
						'icon' => 'eicon-h-align-left',
					],
					'after' => [
						'title' => __( 'After', 'ultraaddons' ),
						'icon' => 'eicon-h-align-right',
					],
				],
			]
		);

		$this->add_control(
			'currency_vertical_position',
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
				'default' => 'bottom',
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .ua-price-table__currency' => 'align-self: {{VALUE}}',
				],
				'condition' => [
					'currency_symbol!' => '',
				],
			]
		);
                
                $this->add_responsive_control(
			'currency_symbol_gap',
			[
				'label' => __( 'Currency Space', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
                                'default' => [
                                        'size' => 10,
                                        'unit' => 'px',
                                ],
				'selectors' => [
					'{{WRAPPER}} span.ua-price-table__currency' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_original_price_style',
			[
				'label' => __( 'Sale Price', 'ultraaddons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'sale' => 'yes',
					'original_price!' => '',
				],
			]
		);

		$this->add_control(
			'original_price_color',
			[
				'label' => __( 'Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Schemes\Color::get_type(),
					'value' => Schemes\Color::COLOR_2,
				],
				'selectors' => [
					'{{WRAPPER}} .ua-price-table__original-price' => 'color: {{VALUE}}',
				],
				'condition' => [
					'sale' => 'yes',
					'original_price!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'original_price_typography',
				'selector' => '{{WRAPPER}} .ua-price-table__original-price',
				'scheme' => Schemes\Typography::TYPOGRAPHY_1,
				'condition' => [
					'sale' => 'yes',
					'original_price!' => '',
				],
			]
		);
                
                $this->add_responsive_control(
			'sale_price_gap',
			[
				'label' => __( 'Space', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
                                'default' => [
                                        'size' => 10,
                                        'unit' => 'px',
                                ],
				'selectors' => [
					'{{WRAPPER}} span.ua-price-table__original-price' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
                                'condition' => [
					'sale' => 'yes',
					'original_price!' => '',
				],
			]
		);
                
		$this->add_control(
			'original_price_vertical_position',
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
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'default' => 'bottom',
				'selectors' => [
					'{{WRAPPER}} .ua-price-table__original-price' => 'align-self: {{VALUE}}',
				],
				'condition' => [
					'sale' => 'yes',
					'original_price!' => '',
				],
			]
		);

		$this->add_control(
			'heading_period_style',
			[
				'label' => __( 'Period', 'ultraaddons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'period!' => '',
				],
			]
		);

		$this->add_control(
			'period_color',
			[
				'label' => __( 'Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Schemes\Color::get_type(),
					'value' => Schemes\Color::COLOR_2,
				],
				'selectors' => [
					'{{WRAPPER}} .ua-price-table__period' => 'color: {{VALUE}}',
				],
				'condition' => [
					'period!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'period_typography',
				'selector' => '{{WRAPPER}} .ua-price-table__period',
				'scheme' => Schemes\Typography::TYPOGRAPHY_2,
				'condition' => [
					'period!' => '',
				],
			]
		);

		$this->add_control(
			'period_position',
			[
				'label' => __( 'Position', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'options' => [
					'below' => __( 'Below', 'ultraaddons' ),
					'beside' => __( 'Beside', 'ultraaddons' ),
				],
				'default' => 'below',
				'condition' => [
					'period!' => '',
				],
			]
		);
                
                $this->add_control(
			'period_vertical_position',
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
				'default' => 'bottom',
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .ua-price-table__price span.ua-price-table__period' => 'align-self: {{VALUE}}',
				],
				'condition' => [
					'period_position' => 'beside',
				],
			]
		);
                
                $this->add_responsive_control(
			'period_margin_below',
			[
				'label' => __( 'Margin', 'ultraaddons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
                                'default' => [
                                        'isLinked' => false,
                                ],
				'selectors' => [
					'{{WRAPPER}} .ua-price-table__period-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
    }
    protected function style_feature_controls(){
                $this->start_controls_section(
			'section_features_list_style',
			[
				'label' => __( 'Features', 'ultraaddons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'features_list_bg_color',
			[
				'label' => __( 'Background Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .ua-price-table__features-list' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'features_list_padding',
			[
				'label' => __( 'Padding', 'ultraaddons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
                                'default' => [
                                        'left' => 0,
                                        'right' => 0,
                                        'unit' => 'px',
                                ],
				'selectors' => [
					'{{WRAPPER}} .ua-price-table__features-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

                $this->start_controls_tabs('feature_tabs');
                
                $this->start_controls_tab(
                    'feature_tab_normal', 
                    [
                        'label' => __( 'Normal', 'ultraaddons' ),
                    ]
                );
                
                $this->add_control(
			'features_list_color',
			[
				'label' => __( 'Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Schemes\Color::get_type(),
					'value' => Schemes\Color::COLOR_3,
				],
				'selectors' => [
					'{{WRAPPER}} .ua-price-table__features-list' => 'color: {{VALUE}}',
				],
			]
		);
                $this->add_control(
			'features_list_icon_color',
			[
				'label' => __( 'Icon Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-price-table__features-list i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .ua-price-table__features-list svg' => 'fill: {{VALUE}}',
				],
			]
		);
                
                $this->end_controls_tab();
                
                $this->start_controls_tab(
                    'feature_tab_hover', 
                    [
                        'label' => __( 'Hover', 'ultraaddons' ),
                    ]
                );
                
                $this->add_control(
			'features_list_color_hover',
			[
				'label' => __( 'Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
//				'scheme' => [
//					'type' => Schemes\Color::get_type(),
//					'value' => Schemes\Color::COLOR_3,
//				],
				
				'selectors' => [
					'{{WRAPPER}}:hover .ua-price-table__features-list' => 'color: {{VALUE}}',
				],
			]
		);
                $this->add_control(
			'features_list_icon_color_hover',
			[
				'label' => __( 'Icon Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}:hover .ua-price-table__features-list i' => 'color: {{VALUE}}',
					'{{WRAPPER}}:hover .ua-price-table__features-list svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'features_list_typography',
				'selector' => '{{WRAPPER}} .ua-price-table__features-list li',
				'scheme' => Schemes\Typography::TYPOGRAPHY_3,
			]
		);

		$this->add_responsive_control(
			'item_gap',
			[
				'label' => __( 'Item Gap', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
                                'default' => [
                                        'size' => 20,
                                        'unit' => 'px',
                                ],
				'selectors' => [
					'{{WRAPPER}} .ua-price-table__features-list li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
            $this->add_responsive_control(
			'icon_gap',
			[
				'label' => __( 'Icon Gap', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ua-price-table__feature-inner>i' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		
    }

        protected function style_footer_controls(){
        $this->start_controls_section(
			'section_footer_style',
			[
				'label' => __( 'Footer', 'ultraaddons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'footer_bg_color',
			[
				'label' => __( 'Background Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-price-table-footer' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'footer_padding',
			[
				'label' => __( 'Padding', 'ultraaddons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
                                'default' => [
                                        'top' => 40,
                                        'right' => 0,
                                        'bottom' => 40,
                                        'left' => 0,
                                ],
				'selectors' => [
					'{{WRAPPER}} .ua-price-table-footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		
		$this->add_control(
			'heading_additional_info',
			[
				'label' => __( 'Additional Info', 'ultraaddons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'footer_additional_info!' => '',
				],
			]
		);

		$this->add_control(
			'additional_info_color',
			[
				'label' => __( 'Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Schemes\Color::get_type(),
					'value' => Schemes\Color::COLOR_3,
				],
				'selectors' => [
					'{{WRAPPER}} .ua-price-table__additional_info' => 'color: {{VALUE}}',
				],
				'condition' => [
					'footer_additional_info!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'additional_info_typography',
				'selector' => '{{WRAPPER}} .ua-price-table__additional_info',
				'scheme' => Schemes\Typography::TYPOGRAPHY_3,
				'condition' => [
					'footer_additional_info!' => '',
				],
			]
		);

		$this->add_control(
			'additional_info_margin',
			[
				'label' => __( 'Margin', 'ultraaddons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => 15,
					'right' => 30,
					'bottom' => 0,
					'left' => 30,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ua-price-table__additional_info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
				'condition' => [
					'footer_additional_info!' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_ribbon_style',
			[
				'label' => __( 'Ribbon', 'ultraaddons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'show_ribbon' => 'yes',
				],
			]
		);

		$this->add_control(
			'ribbon_bg_color',
			[
				'label' => __( 'Background Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
                                'default' => '#0FC392',
				'selectors' => [
					'{{WRAPPER}} .ua-price-table__ribbon' => 'background-color: {{VALUE}}',
				],
			]
		);

		
		$this->add_control(
			'ribbon_text_color',
			[
				'label' => __( 'Text Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .ua-price-table__ribbon-inner' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ribbon_typography',
				'selector' => '{{WRAPPER}} .ua-price-table__ribbon-inner',
				'scheme' => Schemes\Typography::TYPOGRAPHY_4,
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'selector' => '{{WRAPPER}} .ua-price-table__ribbon-inner',
			]
		);

		$this->end_controls_section();
            
    }

	 /**
	 * Adding Box Style
	 * @author B M Rafiul Alam
	 * bmrafiul.alam@gmail.com
	 * @since 1.1.0.11
	 */
	 protected function style_box(){
         $this->start_controls_section(
			'section_box_style',
			[
				'label' => __( 'Box', 'ultraaddons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'box_background',
				'label' => __( 'Box Background', 'ultraaddons' ),
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} .ua-price-table',
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_wrap_shadow',
				'selector' => '{{WRAPPER}} .ua-price-table',
			]
		);
		$this->add_responsive_control(
			'box_radius',
			[
				'label' => __( 'Box Radius', 'ultraaddons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'selectors' => [
					'{{WRAPPER}} .ua-price-table' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'box_padding',
			[
				'label' => __( 'Box Padding', 'ultraaddons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'selectors' => [
					'{{WRAPPER}} .ua-price-table' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	$this->end_controls_section();
            
    }

    private function render_currency_symbol( $symbol, $location ) {
		$currency_position = $this->get_settings( 'currency_position' );
		$location_setting = ! empty( $currency_position ) ? $currency_position : 'before';
		if ( ! empty( $symbol ) && $location === $location_setting ) {
			echo '<span class="ua-price-table__currency ua-currency--' . $location . '">' . $symbol . '</span>';
		}
	}

	private function get_currency_symbol( $symbol_name ) {
		$symbols = [
			'dollar' => '&#36;',
			'euro' => '&#128;',
			'franc' => '&#8355;',
			'pound' => '&#163;',
			'ruble' => '&#8381;',
			'shekel' => '&#8362;',
			'baht' => '&#3647;',
			'yen' => '&#165;',
			'won' => '&#8361;',
			'guilder' => '&fnof;',
			'peso' => '&#8369;',
			'peseta' => '&#8359',
			'lira' => '&#8356;',
			'rupee' => '&#8360;',
			'indian_rupee' => '&#8377;',
			'real' => 'R$',
			'krona' => 'kr',
		];

		return isset( $symbols[ $symbol_name ] ) ? $symbols[ $symbol_name ] : '';
	}


        protected function get_price_table_header(){
            $settings   = $this->get_settings_for_display();
            ?>
            <div class="ua-price-table__header">
                    <?php if( $this->ua_icon_select ) { ?>
                    <div class="ua-price-table__header_icon">
                            <?php
                            if( 'image' == $this->ua_icon_select ){ ?>
                            <img class="ua-pricing-table-image" src="<?php echo esc_url( $this->ua_image_upload );?>" alt="<?php esc_attr__( 'Pricing Image', 'ultraaddons' ); ?>">
                            <?php }elseif( $this->ua_icon_choose ){ ?>
                                <i class="ua-pricing-table-icon <?php echo esc_attr( $this->ua_icon_choose ); ?>"></i>        
                            <?php }elseif( $this->svg ){ ?>
                                <img class="ua-pricing-table-image ua-pricing-table-svg-image" src="<?php echo esc_url( $this->svg );?>" alt="<?php esc_attr__( 'Pricing Image', 'ultraaddons' ); ?>">
                            <?php } ?>
                    </div>
                    <?php } ?>

                    <?php if ( ! empty( $settings['heading'] ) ) : ?>
                            <<?php echo $this->heading_tag . ' ' . $this->get_render_attribute_string( 'heading' ); ?>><?php echo $settings['heading'] . '</' . $this->heading_tag; ?>>
                    <?php endif; ?>

                    <?php if ( ! empty( $settings['sub_heading'] ) ) : ?>
                            <span <?php echo $this->get_render_attribute_string( 'sub_heading' ); ?>><?php echo $settings['sub_heading']; ?></span>
                    <?php endif; ?>

            </div>    
            <?php
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
                $settings   = $this->get_settings_for_display();
                
                // header icon
                $this->ua_icon_select     = isset( $settings['ua_icon_select'] ) ? $settings['ua_icon_select'] : 'icon';
                $this->ua_icon_choose     = !empty( $settings['ua_icon_choose']['value'] ) && is_string( $settings['ua_icon_choose']['value'] ) ? $settings['ua_icon_choose']['value'] : false;
                $this->ua_image_upload    = isset( $settings['ua_image_upload']['url'] ) ? $settings['ua_image_upload']['url'] : '';
                $this->svg                = !empty( $settings['ua_icon_choose']['value']['url'] ) && is_string( $settings['ua_icon_choose']['value']['url'] ) ? $settings['ua_icon_choose']['value']['url'] : false;
                $price_top = ! empty( $settings['price_top'] ) && $settings['price_top'] == 'yes'? true : false;
                
                $symbol = '';

		if ( ! empty( $settings['currency_symbol'] ) ) {
			if ( 'custom' !== $settings['currency_symbol'] ) {
				$symbol = $this->get_currency_symbol( $settings['currency_symbol'] );
			} else {
				$symbol = $settings['currency_symbol_custom'];
			}
		}
                
                $currency_style = isset( $settings['currency_style'] ) ? $settings['currency_style'] : false;
                $currency_format = empty( $settings['currency_format'] ) ? '.' : $settings['currency_format'];
		$price = explode( $currency_format, $settings['price'] );
                    $intpart = $price[0];
		$fraction = '';
		if ( 2 === count( $price ) ) {
			$fraction = $price[1];
		}
               
        $this->add_render_attribute( 'heading', 'class', 'ua-price-table__heading' );
		$this->add_render_attribute( 'sub_heading', 'class', 'ua-price-table__subheading' );
		$this->add_render_attribute( 'period', 'class', [ 'ua-price-table__period', 'ua-typo-excluded' ] );
		$this->add_render_attribute( 'footer_additional_info', 'class', 'ua-price-table__additional_info' );
		$this->add_render_attribute( 'ribbon_title', 'class', 'ua-price-table__ribbon-inner' );
                
		$this->add_inline_editing_attributes( 'heading', 'none' );
		$this->add_inline_editing_attributes( 'sub_heading', 'none' );
		$this->add_inline_editing_attributes( 'period', 'none' );
		$this->add_inline_editing_attributes( 'footer_additional_info' );
		
		$this->add_inline_editing_attributes( 'ribbon_title' );
                
                $period_position = $settings['period_position'];
		$period_element = '<span ' . $this->get_render_attribute_string( 'period' ) . '>' . $settings['period'] . '</span>';
		$this->heading_tag = $settings['heading_tag'];

		$migration_allowed = Icons_Manager::is_migration_allowed();
        ?>
                <div class="ua-price-table">
                        
						<?php 
                        if( ! $price_top ){
                        $this->get_price_table_header(); 
                        }
                        ?>
						<div class="content-wrap">
                       		<div class="ua-price-table__price">
								<div class="ua-price-table__price_inner">
									<?php if ( 'yes' === $settings['sale'] && ! empty( $settings['original_price'] ) ) : ?>
											<span class="ua-price-table__original-price ua-typo-excluded"><?php echo $symbol . $settings['original_price']; ?></span>
									<?php endif; ?>
									<?php $this->render_currency_symbol( $symbol, 'before' ); ?>
									<?php if ( '' !== $fraction ) : ?>
										<?php if ( $currency_style && $currency_style == '2' ) : ?>
											<span class="currency-inner-wrapper"><span class="ua-price-table__integer-part"><?php echo $intpart; ?></span><span class="ua-price-table__currency_sep"><?php echo $currency_format; ?></span><span class="ua-price-table__fractional-part"><?php echo $fraction; ?></span></span>
										<?php else : ?>
											<span class="ua-price-table__integer-part"><?php echo $intpart; ?></span><span class="ua-price-table__currency_sep"><?php echo $currency_format; ?></span><span class="ua-price-table__fractional-part"><?php echo $fraction; ?></span>
										<?php endif; ?>
									<?php else : ?>
											<span class="ua-price-table__integer-part"><?php echo $intpart; ?></span>

									<?php endif; ?>

									<?php $this->render_currency_symbol( $symbol, 'after' ); ?>
												
									<?php if ( ! empty( $settings['period'] ) && 'beside' === $period_position ) : ?>
										
										<?php echo $period_element; ?>
										
									<?php endif; ?>
									<?php if ( ! empty( $settings['period'] ) && 'below' === $period_position ) : ?>
									<span class="ua-price-table__period-wrapper">
										<?php echo $period_element; ?>
									</span>
								<?php endif; ?>

                            </div>
                        </div>
                        <?php
                            if( $price_top ){
                                $this->get_price_table_header(); 
                            }
                        
                            if ( ! empty( $settings['features_list'] ) ) : ?>
                                <ul class="ua-price-table__features-list">
                                        <?php
                                        foreach ( $settings['features_list'] as $index => $item ) :
                                                $repeater_setting_key = $this->get_repeater_setting_key( 'item_text', 'features_list', $index );
                                                $this->add_inline_editing_attributes( $repeater_setting_key );

                                                $migrated = isset( $item['__fa4_migrated']['selected_item_icon'] );
                                                // add old default
                                                if ( ! isset( $item['item_icon'] ) && ! $migration_allowed ) {
                                                        $item['item_icon'] = 'fa fa-check-circle';
                                                }
                                                $is_new = ! isset( $item['item_icon'] ) && $migration_allowed;
                                                ?>
                                                <li class="elementor-repeater-item-<?php echo $item['_id']; ?>">
                                                        <div class="ua-price-table__feature-inner">
                                                                <?php if ( ! empty( $item['item_icon'] ) || ! empty( $item['selected_item_icon'] ) ) :
                                                                        if ( $is_new || $migrated ) :
                                                                                Icons_Manager::render_icon( $item['selected_item_icon'], [ 'aria-hidden' => 'true' ] );
                                                                        else : ?>
                                                                                <i class="<?php echo esc_attr( $item['item_icon'] ); ?>" aria-hidden="true"></i>
                                                                                <?php
                                                                        endif;
                                                                endif; ?>
                                                                <?php if ( ! empty( $item['item_text'] ) ) : ?>
                                                                        <span <?php echo $this->get_render_attribute_string( $repeater_setting_key ); ?>>
                                                                                <?php echo $item['item_text']; ?>
                                                                        </span>
                                                                        <?php
                                                                else :
                                                                        echo '&nbsp;';
                                                                endif;
                                                                ?>
                                                        </div>
                                                </li>
                                        <?php endforeach; ?>
                                </ul>
                        <?php endif; ?>
						</div>					
                        <?php 
                            $this->button_render();
                        ?>
					<?php if ( ! empty( $settings['footer_additional_info'] ) ) : ?>
                    <div class="ua-price-table-footer">
                    	<div <?php echo $this->get_render_attribute_string( 'footer_additional_info' ); ?>><?php echo $settings['footer_additional_info']; ?></div>
					</div>
					 <?php endif; ?>
                </div>

                <?php
		if ( 'yes' === $settings['show_ribbon'] && ! empty( $settings['ribbon_title'] ) ) :
			$this->add_render_attribute( 'ribbon-wrapper', 'class', 'ua-price-table__ribbon' );

			if ( ! empty( $settings['ribbon_horizontal_position'] ) ) :
				$this->add_render_attribute( 'ribbon-wrapper', 'class', 'ua-ribbon-' . $settings['ribbon_horizontal_position'] );
			endif;

			?>
			<div <?php echo $this->get_render_attribute_string( 'ribbon-wrapper' ); ?>>
				<div <?php echo $this->get_render_attribute_string( 'ribbon_title' ); ?>><?php echo $settings['ribbon_title']; ?></div>
			</div>
			<?php
		endif;
        
        }

}
