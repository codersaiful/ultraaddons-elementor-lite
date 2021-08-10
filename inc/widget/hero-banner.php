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


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Hero_Banner extends Base{
    
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
        return [ 'ultraaddons', 'hero', 'header', 'banner', 'call to action', 'c2a' ];
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
        $this->preset_controls();
        $this->content_controls();
        $this->general_style_controls();
        $this->images_style_controls();
        $this->title_style_controls();
        $this->content_style_controls();
        $this->button_style_controls();
        $this->button2_style_controls();
        do_action('dl_widget/section/style/custom_css', $this);
       
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
        
        
    }
    
      //Preset
   public function preset_controls() {
		$this->start_controls_section(
			'_dl_banner_preset_section',
			[
				'label' => __( 'Preset', 'ultraaddons' ),
			]
        );
        $this->add_control(
			'_dl_banner_skin',
			[
				'label' => esc_html__( 'Design Format', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'options'   => [
					'_skin_1' => 'Style 01',
					'_skin_2' => 'Style 02',
                                        '_skin_3' => 'Style 03',
					'_skin_4' => 'Style 04',
					'_skin_5' => 'Style 05',
					'_skin_6' => 'Style 06',
					'_skin_7' => 'Style 07',
					'_skin_8' => 'Style 08',
					'_skin_9' => 'Style 09',
					'_skin_10' => 'Style 10',
				],
				'default' => '_skin_1'
			]
		);
		$this->add_control(
			'_dl_banner_revers',
			[
				'label' => __( 'Banner Reverse', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					$this->get_control_id( '_dl_banner_skin' ) => ['_skin_1', '_skin_2', '_skin_3' ,'_skin_4','_skin_5', '_skin_6','_skin_7'],
                ],
				'label_on' => __( 'Yes', 'ultraaddons' ),
				'label_off' => __( 'NO', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'No',
			]
		);
        
		$this->end_controls_section();
	}

	//Content
   public function content_controls(){
		$this->start_controls_section(
			'_dl_banner_content_section',
			[
				'label' => __( 'Content', 'ultraaddons' ),
			]
		);

		$this->add_control(
			'_dl_banner_title',
			[
				'label' => __( 'Title', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'The quickest & easiest service provider', 'ultraaddons' ),
				'placeholder' => __( 'Enter your title', 'ultraaddons' ),
				'label_block' => true,
			]
		);
		
        
        $this->add_control(
            '_dl_banner_title_size',
            [
                'label' => __( 'Title HTML Tag', 'ultraaddons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'options' => [
                    'h1'  => [
                        'title' => __( 'H1', 'ultraaddons' ),
                        'icon' => 'eicon-editor-h1'
                    ],
                    'h2'  => [
                        'title' => __( 'H2', 'ultraaddons' ),
                        'icon' => 'eicon-editor-h2'
                    ],
                    'h3'  => [
                        'title' => __( 'H3', 'ultraaddons' ),
                        'icon' => 'eicon-editor-h3'
                    ],
                    'h4'  => [
                        'title' => __( 'H4', 'ultraaddons' ),
                        'icon' => 'eicon-editor-h4'
                    ],
                    'h5'  => [
                        'title' => __( 'H5', 'ultraaddons' ),
                        'icon' => 'eicon-editor-h5'
                    ],
                    'h6'  => [
                        'title' => __( 'H6', 'ultraaddons' ),
                        'icon' => 'eicon-editor-h6'
                    ],
                    'p'  => [
                        'title' => __( 'P', 'ultraaddons' ),
                        'icon' => 'eicon-editor-paragraph'
                    ],
                ],
                'default' => 'h4',
                'toggle' => false,
                
            ]
        );
		
		$this->add_control(
			'_dl_banner_description_text',
			[
				'label' => 'Description',
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'Easily and reliably host a website for your business, organization, or project.', 'ultraaddons' ),
				'placeholder' => __( 'Enter your description', 'ultraaddons' ),
				'show_label' => true,
                'rows' => 10,
			]
		);
        
        
        $this->add_control(
            '_dl_banner_images_show',
            [
                'label' => esc_html__('Enable Images', 'ultraaddons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            '_dl_banner_images_feature', [
                'label'      => __('Feature Image', 'ultraaddons'),
                'type'       => \Elementor\Controls_Manager::MEDIA,
                'default'    => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'show_label' => true,
                'condition' => [
					$this->get_control_id('_dl_banner_images_show') => ['yes'],
                ]
            ]
        );
        $this->end_controls_section();

         //Content
         
        $this->start_controls_section(
            '_dl_banner_button_section',
            [
                'label' => __( 'Banner Button', 'ultraaddons' ),
            ]
        );

        $this->add_control(
            '_dl_banner_button_show',
            [
                'label' => esc_html__('Enable Button', 'ultraaddons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'separator' => 'before',
            ]
        );
		$this->add_control(
			'_dl_banner_button_text',
			[
				'label' => __( 'Button', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Work With Us', 'ultraaddons' ),
				'placeholder' => __( 'Enter your text', 'ultraaddons' ),
				'label_block' => true,
                'condition' => [
                    $this->get_control_id( '_dl_banner_button_show' ) => [ 'yes' ],
                ],
			]
		);
		$this->add_control(
			'_dl_banner_button_link',
			[
				'label' => __( 'Button Link', 'ultraaddons' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'ultraaddons' ),
                'default'   => [
                    'url' => '#'
                ],
                'condition' => [
                    $this->get_control_id( '_dl_banner_button_show' ) => [ 'yes' ],
                ],
			]
		);
		
        
        $this->add_control(
			'_dl_banner_button2_text',
			[
				'label' => __( 'Button Two', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Watch Video', 'ultraaddons' ),
				'placeholder' => __( 'Enter your text', 'ultraaddons' ),
				'label_block' => true,
                'condition' => [
					$this->get_control_id( '_dl_banner_button_show' ) => [ 'yes' ],
					$this->get_control_id( '_dl_banner_skin' ) => ['_skin_1', '_skin_2', '_skin_3' ,'_skin_7'],
                ],
			]
		);
		$this->add_control(
			'_dl_banner2_link',
			[
				'label' => __( 'Button Two Link', 'ultraaddons' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'ultraaddons' ),
                'default'   => [
                    'url' => '#'
                ],
                'condition' => [
					$this->get_control_id( '_dl_banner_button_show' ) => [ 'yes' ],
					$this->get_control_id( '_dl_banner_skin' ) => ['_skin_1', '_skin_2', '_skin_3' ,'_skin_7'],
                ],
			]
		);

		$this->add_control(
			'_dl_banner2_icon',
			[
				'label' => __( 'Button Two Icon', 'ultraaddons' ),
				'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => [
                    'value' => 'fas fa-play',
                    'library' => 'fa-solid',
                ],
			]
		);
        $this->end_controls_section();

	}

	//General
	public function general_style_controls(){
		$this->start_controls_section(
            '_dl_banner_style_general',
            [
                'label' => esc_html__('General', 'ultraaddons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

		$this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'banner_background',
                'label' => esc_html__('Background Color', 'ultraaddons'),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .dl_banner_section_style_01, {{WRAPPER}} .dl_banner_section_style_02, {{WRAPPER}} .dl_banner_section_style_09',
            ]
        );
		
        $this->end_controls_section();
	}

	//Banner Images Setting
	public function images_style_controls(){
		$this->start_controls_section(
            '_dl_banner_style_images',
            [
                'label' => esc_html__('Images', 'ultraaddons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

		$this->add_responsive_control(
            '_banner_image_space_first',
            [
                'label' => __( 'Spacing', 'ultraaddons' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 15,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.dl_banner_img' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.dl_banner_img' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.dl_banner_img' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '(mobile){{WRAPPER}} .dl_banner_img' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_banner_image_size_width_first',
            [
                'label'      => __('Width', 'ultraaddons'),
                'type'       => Controls_Manager::SLIDER,
                'default' => [
                    'size' => '',
                    'unit' => 'px',
                ],
                'size_units' => ['px', '%'],
                'range'      => [
                    'px'  => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    '%'   => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em'  => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                ],

                'selectors'  => [
                    '{{WRAPPER}} .dl_banner_img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            '_banner_image_size_height_first',
            [
                'label'      => __('Height', 'ultraaddons'),
                'type'       => Controls_Manager::SLIDER,
                'default' => [
                    'size' => '',
                ],
                'size_units' => ['px', '%', 'em', 'rem'],
                'range'      => [
                    'px'  => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    '%'   => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em'  => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                ],

                'selectors'  => [
                    '{{WRAPPER}} .dl_banner_img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            '_banner_image_padding_first',
            [
                'label' => esc_html__('Padding', 'ultraaddons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .droit-card-box-wrapper .dl_banner_img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
    
        $this->end_controls_section();
	}


	//banner Title Style
	public function title_style_controls() {
		$this->start_controls_section(
            '_dl_banner_title_style_settings',
            [
                'label' => esc_html__('Title', 'ultraaddons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => '_dl_banner_title_typography',
                'selector' => '{{WRAPPER}} .dl_banner_content .dl_banner_title',
            ]
        );
        $this->add_control(
            '_dl_banner_text_color',
            [
                'label' => esc_html__('Text Color', 'ultraaddons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .dl_banner_content .dl_banner_title' => 'color: {{VALUE}};',
                ],
            ]
		);
		
		$this->add_responsive_control(
			'_dl_banner_title_bottom_space',
			[
				'label' => __( 'Spacing', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .dl_banner_content .dl_banner_title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
        $this->end_controls_section();
	}

	//Content Style
	public function content_style_controls(){
		$this->start_controls_section(
            '_dl_banner_content_style_settings',
            [
                'label' => esc_html__('Content', 'ultraaddons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => '_dl_banner_content_typography',
                'selector' => '{{WRAPPER}} .dl_banner_content .dl_banner_desc',
            ]
        );
        $this->add_control(
            '_dl_banner_content_color',
            [
                'label' => esc_html__('Text Color', 'ultraaddons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .dl_banner_content .dl_banner_desc' => 'color: {{VALUE}};',
                ],
            ]
		);
		$this->add_responsive_control(
			'_dl_banner_content_bottom_space',
			[
				'label' => __( 'Spacing', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .dl_banner_content .dl_banner_desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
            '_banner_content_padding_first',
            [
                'label' => esc_html__('Content Padding', 'ultraaddons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .dl_banner_content.dl_banner_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
    }
    

    //Button Style
	public function button_style_controls(){
		$this->start_controls_section(
            '_dl_banner_button_style_settings',
            [
                'label' => esc_html__('Button One', 'ultraaddons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->start_controls_tabs( '_banner_button_effects' );

		$this->start_controls_tab( '_button_normal',
			[
				'label' => __( 'Normal', 'ultraaddons' ),
			]
		);

		$this->add_control(
			'_banner_button_color',
			[
				'label' => __( 'Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .btn_2' => 'color: {{VALUE}};',
					'{{WRAPPER}} .dl_cu_btn.btn_3' => 'color: {{VALUE}};',
					'{{WRAPPER}} .dl_banner_content .dl_banner_subscribe_form .dl_cu_btn' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'_banner_button_bg_color',
			[
				'label' => __( 'Background Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .dl_cu_btn.btn_3' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
					'{{WRAPPER}} .btn_2' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
					'{{WRAPPER}} .dl_banner_content .dl_banner_subscribe_form .dl_cu_btn' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => '_banner_button_typography',
				'selector' => '{{WRAPPER}} .dl_banner_content .btn_3',
			]
		);
		$this->add_control(
			'_banner_btn_border_radius',
			[
				'label' => __( 'Border Radius', 'ultraaddons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .dl_banner_content .btn_3' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .btn_2' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .dl_banner_content .dl_banner_subscribe_form .dl_cu_btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'_banner_button_transition',
			[
				'label' => __( 'Transition Duration', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.3,
				],
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .dl_banner_content .btn_3, {{WRAPPER}} .btn_2, {{WRAPPER}} .dl_banner_content .dl_banner_subscribe_form .dl_cu_btn' => 'transition-duration: {{SIZE}}s',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab( '_button_hover',
			[
				'label' => __( 'Hover', 'ultraaddons' ),
			]
		);

		$this->add_control(
			'_banner_button_hover_color',
			[
				'label' => __( 'Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .dl_cu_btn.btn_3:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .btn_2:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .dl_banner_content .dl_banner_subscribe_form .dl_cu_btn:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'_banner_button_hover_bg_color',
			[
				'label' => __( 'Background Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .dl_cu_btn.btn_3:hover, {{WRAPPER}} .btn_2:hover, {{WRAPPER}} .dl_banner_content .dl_banner_subscribe_form .dl_cu_btn:hover' => 'background-color: {{VALUE}};border-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'_banner_hover_border_radius',
			[
				'label' => __( 'Border Radius', 'ultraaddons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .btn_2:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .dl_cu_btn.btn_3:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .dl_banner_content .dl_banner_subscribe_form .dl_cu_btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'_banner_button_hover_transition',
			[
				'label' => __( 'Transition Duration', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.3,
				],
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .dl_cu_btn.btn_3:hover, {{WRAPPER}} .dl_cu_btn.btn_3:hover' => 'transition-duration: {{SIZE}}s',
					'{{WRAPPER}} .btn_2:hover, {{WRAPPER}} .btn_2:hover' => 'transition-duration: {{SIZE}}s',
					'{{WRAPPER}} .dl_banner_content .dl_banner_subscribe_form .dl_cu_btn:hover, {{WRAPPER}} .dl_cu_btn.btn_3:hover' => 'transition-duration: {{SIZE}}s',
				],
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();
        
        $this->end_controls_section();
    }
    
     //Button Two Style
     public function button2_style_controls(){
		$this->start_controls_section(
            '_dl_banner_button2_style_settings',
            [
				'label' => esc_html__('Button Two', 'ultraaddons'),
				'condition' => [
					$this->get_control_id('_dl_banner_skin') => ['_skin_1', '_skin_2', '_skin_3' ,'_skin_7'],
				],
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->start_controls_tabs( '_banner_button2_effects' );

		$this->start_controls_tab( '_button2_normal',
			[
				'label' => __( 'Normal', 'ultraaddons' ),
			]
		);
		
		$this->add_control(
			'_banner_button2_color',
			[
				'label' => __( 'Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .dl_cu_btn.btn_4' => 'color: {{VALUE}};',
					'{{WRAPPER}} .dl_cu_btn.btn_1' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'_banner_button2_bg_color',
			[
				'label' => __( 'Background Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .dl_cu_btn.btn_4' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
					'{{WRAPPER}} .dl_cu_btn.btn_1' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => '_banner_button2_typography',
				'selector' => '{{WRAPPER}} .dl_banner_content .btn_4, {{WRAPPER}} .dl_cu_btn.btn_1',
				
			]
		);
		$this->add_control(
			'_banner_btn2_border_radius',
			[
				'label' => __( 'Border Radius', 'ultraaddons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .dl_banner_content .btn_4' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .dl_cu_btn.btn_1' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'_banner_button2_transition',
			[
				'label' => __( 'Transition Duration', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.3,
				],
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .dl_banner_content .btn_4' => 'transition-duration: {{SIZE}}s',
					'{{WRAPPER}} .dl_cu_btn.btn_1' => 'transition-duration: {{SIZE}}s',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab( '_button2_hover',
			[
				'label' => __( 'Hover', 'ultraaddons' ),
			]
		);

		$this->add_control(
			'_banner_button2_hover_color',
			[
				'label' => __( 'Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .dl_cu_btn.btn_4:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .dl_cu_btn.btn_1:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'_banner_button2_hover_bg_color',
			[
				'label' => __( 'Background Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .dl_cu_btn.btn_4:hover' => 'background-color: {{VALUE}};border-color: {{VALUE}};',
					'{{WRAPPER}} .dl_cu_btn.btn_1:hover' => 'background-color: {{VALUE}};border-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'_banner_button2_hover_border_radius',
			[
				'label' => __( 'Border Radius', 'ultraaddons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .dl_cu_btn.btn_4:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .dl_cu_btn.btn_1:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'_banner_button2_hover_transition',
			[
				'label' => __( 'Transition Duration', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.3,
				],
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .dl_cu_btn.btn_4:hover, {{WRAPPER}} .dl_cu_btn.btn_4:hover, {{WRAPPER}} .dl_cu_btn.btn_1:hover' => 'transition-duration: {{SIZE}}s',
				],
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();
        
        $this->end_controls_section();
    }
}
