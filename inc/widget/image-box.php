<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Image_Box extends Base{

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
        return [ 'ultraaddons-elementor-lite', 'ua', 'image box', 'image', 'box','info' ];
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
        $this->style_general_controls();
        $this->style_icon_controls();
        $this->style_box_controls();
        $this->style_image_controls();
        $this->style_button_controls();
    }
    
        
    /**
     * General Section for Content Controls
     * 
     * @since 1.0.0.9
     */
    protected function content_general_controls() {
        $placeholder_image = ULTRA_ADDONS_URL . 'assets/images/image-box.jpg';

        $this->start_controls_section(
            'general_content',
            [
                'label'     => esc_html__( 'General', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
  
    $this->add_control(
        'ua_image_box_image',
        [
            'label' => __( 'Choose Image', 'ultraaddons-elementor-lite' ),
            'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => $placeholder_image,//Utils::get_placeholder_image_src(),
            ], 
            'dynamic' => [
                    'active' => true,
            ],

        ]
    );
    $this->add_control(
        'icon',
        [
            'label' => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
            'type' => Controls_Manager::ICONS,
            'default' => [
                'value' => 'fas fa-star',
                'library' => 'solid',
            ],
            'condition'=>['image_box_style'=>'1']
        ]
    );

    $this->add_control(
            'title_text',
            [
                    'label' => __( 'Title', 'ultraaddons-elementor-lite' ),
                    'type' => Controls_Manager::TEXT,
                    'dynamic' => [
                            'active' => true,
                    ],
                    'default' => __( 'Image Box Title', 'ultraaddons-elementor-lite' ),
                    'placeholder' => __( 'Enter your title', 'ultraaddons-elementor-lite' ),
                    'label_block' => true,
            ]
    );
    $this->add_control(
        'description_text',
        [
                'label' => __( 'Description', 'ultraaddons-elementor-lite' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __( 'Al contrario del pensamiento popular, el texto de Lorem Ipsum.', 'ultraaddons-elementor-lite' ),
                'label_block' => true,
        ]
    );
    $this->add_control(
        '_button_text',
        [
            'label' => __( 'Button Text', 'ultraaddons-elementor-lite' ),
            'type' => Controls_Manager::TEXT,
            'default' => __( 'Explore Now', 'ultraaddons-elementor-lite' ),
            'label_block' => true,
        ]
    );
    $this->add_control(
        '_button_link',
        [
            'label' => __( 'Link', 'ultraaddons-elementor-lite' ),
            'type' => Controls_Manager::URL,
            'placeholder' => __( 'https://your-link.com', 'ultraaddons-elementor-lite' ),
            'show_external' => true,
            'separator' =>'after',
            'default' => [
                'url' => '#',
                'is_external' => true,
                'nofollow' => true,
            ],
        ]
        );
        $this->end_controls_section();
    }

    /**
	 * General Style
	 */

    protected function style_general_controls(){
        $this->start_controls_section(
             '_ua_general_style',
             [
                 'label'     => esc_html__( 'Content', 'ultraaddons-elementor-lite' ),
                 'tab'       => Controls_Manager::TAB_STYLE,
             ]
         );
         $this->add_responsive_control(
            'image_box_style',
                [
                    'label'         => esc_html__( 'Style', 'ultraaddons-elementor-lite' ),
                    'type'          => Controls_Manager::SELECT,
                    'options' => [
                            '1'   => __( 'Style-1', 'ultraaddons-elementor-lite' ),
                            '2'   => __( 'Style-2', 'ultraaddons-elementor-lite' ),
                            '3'   => __( 'Style-3', 'ultraaddons-elementor-lite' ),
                    ],
                    'default' => '1',
                ]
        );
           $this->add_responsive_control(
            'image_box_align',
            [
                    'label' => __( 'Alignment', 'ultraaddons-elementor-lite' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                            'left'    => [
                                    'title' => __( 'Left', 'ultraaddons-elementor-lite' ),
                                    'icon' => 'eicon-text-align-left',
                            ],
                            'center' => [
                                    'title' => __( 'Center', 'ultraaddons-elementor-lite' ),
                                    'icon' => 'eicon-text-align-center',
                            ],
                            'right' => [
                                    'title' => __( 'Right', 'ultraaddons-elementor-lite' ),
                                    'icon' => 'eicon-text-align-right',
                            ],
                    ],
                    'default' => '',
                    'selectors' => [
                            '{{WRAPPER}} .ua-image-box' => 'text-align: {{VALUE}};',
                    ],
            ]
    ); 
         
         $this->add_control(
			'title_color', [
				'label' => __( 'Title Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-image-box-title' => 'color: {{VALUE}};',
				],
			]
        );
        $this->add_control(
			'title_hover_color', [
				'label' => __( 'Title Hover Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-image-box:hover .ua-image-box-title' => 'color: {{VALUE}};',
				],
			]
        );
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'title_typography',
					'label' => 'Title Typography',
					'selector' => '{{WRAPPER}} .ua-image-box-title',

			]
        );
        $this->add_responsive_control(
			'title_margin',
			[
				'label'       => esc_html__( 'Title Margin', 'ultraaddons-elementor-lite' ),
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
					'{{WRAPPER}} .ua-image-box-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'desc_color', [
				'label' => __( 'Description Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
                'separator' =>'before',
				'selectors' => [
						'{{WRAPPER}} .ua-image-box-content' => 'color: {{VALUE}};',
				],
			]
        );
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'desc_typography',
					'label' => 'Description Typography',
					'selector' => '{{WRAPPER}} .ua-image-box-content',

			]
        );
        $this->add_responsive_control(
			'desc_margin',
			[
				'label'       => esc_html__( 'Description Margin', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ '%', 'px' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-image-box-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
      $this->end_controls_section();
     }
     /**
	 * Icon Style
	 */

	 protected function style_icon_controls(){
        $this->start_controls_section(
             '_ua_icon_style',
             [
                 'label'     => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                 'tab'       => Controls_Manager::TAB_STYLE,
                 'condition'=>['image_box_style'=>'1']
             ]
         );
         $this->start_controls_tabs(
            'style_tabs'
        );
    
        $this->start_controls_tab(
            'style_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        
        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'icon_bg',
				'label' => esc_html__( 'Icon Background', 'ultraaddons-elementor-lite' ),
				'types' => [ 'classic', 'gradient'],
                'exclude' =>['image'],
				'selector' => '{{WRAPPER}} .ua-image-box-icon',
			]
		);
        $this->add_control(
			'icon_color', [
				'label' => __( 'Icon Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-image-box-icon i' => 'color: {{VALUE}};',
						'{{WRAPPER}} .ua-image-box-icon svg' => 'fill: {{VALUE}};',
				],
			]
        );

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'icon_border',
				'label' => esc_html__( 'Border', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ua-image-box-icon',
			]
		);
        $this->add_control(
			'icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 200,
						'step' => 5,
					],
				],
                'default' => [
					'unit' => 'px',
					'size' => 35,
				],
			
				'selectors' => [
					'{{WRAPPER}} .ua-image-box-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ua-image-box-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->add_control(
			'icon_wraper_size',
			[
				'label' => esc_html__( 'Icon Wraper Size', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 200,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ua-image-box-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->add_responsive_control(
			'icon_radius',
			[
				'label'       => esc_html__( 'Icon Box Radius', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ '%', 'px' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'separator' =>'before',
				'selectors'   => [
					'{{WRAPPER}} .ua-image-box-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->end_controls_tab();
        
        //Hover Tab
        $this->start_controls_tab(
            'style_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
            ]
        );
    

        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'icon_bg_hover',
				'label' => esc_html__( 'Icon Background', 'ultraaddons-elementor-lite' ),
				'types' => [ 'classic', 'gradient'],
                'exclude' =>['image'],
				'selector' => '{{WRAPPER}} .ua-image-box-icon:hover',
			]
		);

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'icon_border_hover',
				'label' => esc_html__( 'Border', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ua-image-box-icon:hover',
			]
		);
        $this->end_controls_tab();
    
        $this->end_controls_tabs();

        
      $this->end_controls_section();
     }

     /**
	 * Box Style
	 */

	 protected function style_box_controls(){
        $this->start_controls_section(
             '_ua_box_style',
             [
                 'label'     => esc_html__( 'Box', 'ultraaddons-elementor-lite' ),
                 'tab'       => Controls_Manager::TAB_STYLE,
             ]
         );
         $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => '_box_shadow',
				'label' => __( 'Box Shadow', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ua-image-box',
			]
		);
        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'box_border',
				'label' => esc_html__( 'Border', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ua-image-box',
			]
		);
        $this->add_responsive_control(
			'box_radius',
			[
				'label'       => esc_html__( 'Box Radius', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ '%', 'px' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'separator' =>'before',
				'selectors'   => [
					'{{WRAPPER}} .ua-image-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->add_responsive_control(
			'box_padding',
			[
				'label'       => esc_html__( 'Box Padding', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ '%', 'px' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-image-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        
      $this->end_controls_section();
     }
     /**
	 * Image Style
	 */

	 protected function style_image_controls(){
        $this->start_controls_section(
             '_ua_image_style',
             [
                 'label'     => esc_html__( 'Image', 'ultraaddons-elementor-lite' ),
                 'tab'       => Controls_Manager::TAB_STYLE,
             ]
         );
        
       
        $this->add_responsive_control(
			'image_radius',
			[
				'label'       => esc_html__( 'Image Radius', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ '%', 'px' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-image-box img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->add_responsive_control(
			'image_padding',
			[
				'label'       => esc_html__( 'Image Padding', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ '%', 'px' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-image-box img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->add_control(
			'image_width',
			[
				'label' => esc_html__( 'Image Width', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 500,
						'step' => 5,
					],
				],
			
				'selectors' => [
					'{{WRAPPER}} .ua-image-box img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->add_control(
			'image_height',
			[
				'label' => esc_html__( 'Image Height', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 500,
						'step' => 5,
					],
				],
			
				'selectors' => [
					'{{WRAPPER}} .ua-image-box img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
     
        
        
      $this->end_controls_section();
     }
      /**
	 * Icon Style
	 */

	 protected function style_button_controls(){
        $this->start_controls_section(
             '_ua_button_style',
             [
                 'label'     => esc_html__( 'Button', 'ultraaddons-elementor-lite' ),
                 'tab'       => Controls_Manager::TAB_STYLE,
                 //'condition'=>['image_box_style'=>'2'],
                 'conditions' => [
                    'terms' => [
                        [
                            'name' => 'image_box_style',
                            'operator' => 'in',
                            'value' => [
                                '2',
                                '3'
                            ]
                        ]
                    ]
                ]
             ]
         );
         $this->start_controls_tabs(
            'btn_style_tabs'
        );
    
        $this->start_controls_tab(
            'btn_style_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'button_typography',
					'label' => 'Button Typography',
					'selector' => '{{WRAPPER}} .ua-img-box-button',

			]
        );

        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_bg',
				'label' => esc_html__( 'Button Background', 'ultraaddons-elementor-lite' ),
				'types' => [ 'classic', 'gradient'],
                'exclude' =>['image'],
				'selector' => '{{WRAPPER}} .ua-img-box-button',
			]
		);

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'label' => esc_html__( 'Border', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ua-img-box-button',
			]
		);
       
        $this->add_responsive_control(
			'button_radius',
			[
				'label'       => esc_html__( 'Radius', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ '%', 'px' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'separator' =>'before',
				'selectors'   => [
					'{{WRAPPER}} .ua-img-box-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->end_controls_tab();
        
        //Hover Tab
        $this->start_controls_tab(
            'btn_style_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
            ]
        );
    

        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_bg_hover',
				'label' => esc_html__( 'Button Background', 'ultraaddons-elementor-lite' ),
				'types' => [ 'classic', 'gradient'],
                'exclude' =>['image'],
				'selector' => '{{WRAPPER}} .ua-img-box-button:hover',
			]
		);

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_border_hover',
				'label' => esc_html__( 'Border', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ua-img-box-button:hover',
			]
		);
        $this->end_controls_tab();
    
        $this->end_controls_tabs();

        
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
        $settings           = $this->get_settings_for_display();
    
        $target 	= $settings['_button_link']['is_external'] ? ' target="_blank"' : '';
		$nofollow 	= $settings['_button_link']['nofollow'] ? ' rel="nofollow"' : '';
		$url		= $settings['_button_link']['url'];

        $this->add_render_attribute(
			'image_box_class',
			[
				'class' => 'ua-image-box style-' . $settings['image_box_style'],
			]
		);
        ?>
        <div <?php echo esc_attr( $this->get_render_attribute_string( 'image_box_class' ) );?>>
            <div class="image-wrap">
                <?php 
                if(!empty($settings['ua_image_box_image']['url'])){
                    echo '<img  class="ua-image" src="' . esc_url( $settings['ua_image_box_image']['url'] ) .'" alt="' . esc_attr( $settings['title_text'] ) . '">';
                }
                ?>
                <?php if(! empty($settings['icon']['value']) && $settings['image_box_style']=='1' ){ ?>
                    <div class="ua-image-box-icon">
                        <?php \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); ?>
                    </div>
                <?php } ?>

            </div>
          
            <div class="image-box-content-wrap">
                <?php 
                echo '<a href="' . esc_url( $url ). '"' . esc_attr( $target . $nofollow ) . ' class="ua-image-box-title-link">
                    <h2 class="ua-image-box-title">
                         ' . esc_html( $settings['title_text'] ) .'
                    </h2>
                </a>';
                ?>
                <div class="ua-image-box-content">
                    <?php echo wp_kses_post( $settings['description_text'] ); ?>
                    <?php 
                    if(!empty($url && $settings['image_box_style']=='2' || $settings['image_box_style']=='3' )){
                        echo '<div class="btn-wrap"> 
                                <a href="' . esc_url( $url ). '"' . esc_attr( $target . $nofollow ) . ' class="ua-img-box-button">
                                ' .  esc_html( $settings['_button_text'] ) . '
                                </a>
                            </div>';
                        }
                    ?>
                    
                </div>
            </div>
        </div>
 
        <?php
        
    }
    
    
    
    
}
