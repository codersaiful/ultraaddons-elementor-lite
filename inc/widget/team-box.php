<?php
namespace UltraAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Repeater;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

 /**
     * Team Widget 
     *
     * @since 1.1.0.12
     * @author B M Rafiul Alam
     * bmrafiul.alam@gmail.com
     */

class Team_Box extends Base{
    
    /**
     * Register oEmbed widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls() {
        //For Information
        $this->content_information_controls();
        //For General Section
        $this->content_socials_controls();
        //For General Style
        $this->general_style();
         //For Icon Style
        $this->icon_style();
        //For Box Style
        $this->box_style();
        //For Image Style
        $this->image_style();
    }
    
    
    
    protected function social_links() {
        $settings           = $this->get_settings_for_display();
        if ( $settings['show_profiles' ]=='yes' && is_array( $settings['profiles' ] ) ) {
        ?>
            <div class="team-box-social-link">
                        
                <ul class="social_links">
                    <?php
                    foreach ( $settings['profiles'] as $profile ) :
                            $ua_icon = $profile['name'];
                            $url = $profile['link']['url'];

                            if ( $profile['name'] === 'website' ) {
                                    $ua_icon = 'globe far';
                            } elseif ( $profile['name'] === 'email' ) {
                                    $ua_icon = 'envelope far';
                                    $url = 'mailto:' . antispambot( $profile['email'] );
                            } else {
                                    $ua_icon .= ' fab';
                            }

                            printf( '<li><a target="_blank" rel="noopener" href="%s" class="elementor-repeater-item-%s"><i class="lab fa-%s" aria-hidden="true"></i></a></li>',
                                    esc_url( $url ),
                                    esc_attr( $profile['_id'] ),
                                    esc_attr( $ua_icon )
                            );
                    endforeach; ?>
                </ul>
            </div><?php
        }
    }
    
    /**
     * Getting Information
     * Such: Team user 
     * * Photo or Profile Image
     * * Full Name
     * * Job Designation
     * 
     * @since 1.0.0.11
     */
    protected function content_information_controls(){
        
        $placeholder_image = ULTRA_ADDONS_URL . 'assets/images/team.jpg';

        $this->start_controls_section(
                '_section_info',
                [
                        'label' => __( 'Information', 'ultraaddons-elementor-lite' ),
                        'tab' => Controls_Manager::TAB_CONTENT,
                ]
        );


        $this->add_control(
                'image',
                [
                        'label' => __( 'Photo', 'ultraaddons-elementor-lite' ),
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
                'title',
                [
                        'label' => __( 'Name', 'ultraaddons-elementor-lite' ),
                        'label_block' => true,
                        'type' => Controls_Manager::TEXT,
                        'default' => __( 'Jhone Doe', 'ultraaddons-elementor-lite' ),
                        'placeholder' => __( 'Type Member Name', 'ultraaddons-elementor-lite' ),
                        'separator' => 'before',
                        'dynamic' => [
                                'active' => true,
                        ]
                ]
        );

        $this->add_control(
                'designation',
                [
                        'label' => __( 'Designation', 'ultraaddons-elementor-lite' ),
                        'label_block' => true,
                        'type' => Controls_Manager::TEXT,
                        'default' => __( 'WordPress Developer', 'ultraaddons-elementor-lite' ),
                        'placeholder' => __( 'Type Member Designation', 'ultraaddons-elementor-lite' ),
                        'dynamic' => [
                                'active' => true,
                        ]
                ]
        );
        $this->add_control(
                'description',
                [
                        'label' => __( 'Description', 'ultraaddons-elementor-lite' ),
                        'label_block' => true,
                        'type' => Controls_Manager::TEXTAREA,
                        'dynamic' => [
                                'active' => true,
                        ]
                ]
        );
        
        
               
        $this->end_controls_section();
    }

        protected function general_style(){
                        
                $this->start_controls_section(
                        'general_style',
                        [
                                'label' =>  __( 'General', 'ultraaddons-elementor-lite' ) ,
                                'tab' => Controls_Manager::TAB_STYLE,
                        ]
                );
                $this->add_responsive_control(
			'team_text_alignment',
			[
				'label' => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
						'icon' => 'eicon-text-align-right',
					]
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .ua-team-container' => 'text-align: {{VALUE}};',
				],
			]
		);
                $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                                        'name' => 'team_title_typography',
                                        'label' => 'Title Typography',
                                        'selector' => '{{WRAPPER}} .ua-team-title',

                        ]
                );
                $this->add_control(
                        'team_title_color', [
                                'label' => __( 'Title Color', 'ultraaddons-elementor-lite' ),
                                'type'      => Controls_Manager::COLOR,
                                'separator' => 'after',
                                'selectors' => [
                                                '{{WRAPPER}} .ua-team-title' => 'color: {{VALUE}};',
                                ],
                        ]
                );
                $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                                        'name' => 'designation_typography',
                                        'label' => 'Designation Typography',
                                        'selector' => '{{WRAPPER}} .ua-team-container .who',

                        ]
                );
                $this->add_control(
                        'designation_color', [
                                'label' => __( 'Designation Color', 'ultraaddons-elementor-lite' ),
                                'type'      => Controls_Manager::COLOR,
                                'separator' => 'after',
                                'selectors' => [
                                                '{{WRAPPER}} .ua-team-container .who' => 'color: {{VALUE}};',
                                ],
                        ]
                );
                $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                                        'name' => 'desc_typography',
                                        'label' => 'Description Typography',
                                        'selector' => '{{WRAPPER}} .ua-team-container .member-text',
                                        'condition'=>['description!'=>'']

                        ]
                );
                $this->add_control(
                        'desc_color', [
                                'label' => __( 'Description Color', 'ultraaddons-elementor-lite' ),
                                'type'      => Controls_Manager::COLOR,
                                'condition'=>['description!'=>''],
                                'separator' => 'after',
                                'selectors' => [
                                                '{{WRAPPER}} .ua-team-container .member-text' => 'color: {{VALUE}};',
                                ],
                        ]
                );


                $this->end_controls_section();
        }

        protected function icon_style(){
                        
                $this->start_controls_section(
                        'icon_style',
                        [
                                'label' =>  __( 'Icon', 'ultraaddons-elementor-lite' ) ,
                                'tab' => Controls_Manager::TAB_STYLE,
                        ]
                );
               
                $this->add_control(
                        'icon_color', [
                                'label' => __( 'Icon Color', 'ultraaddons-elementor-lite' ),
                                'type'      => Controls_Manager::COLOR,
                                'selectors' => [
                                                '{{WRAPPER}} .social_links i' => 'color: {{VALUE}};',
                                ],
                        ]
                );
                $this->add_control(
                        'icon_hover_color', [
                                'label' => __( 'Icon Hover Color', 'ultraaddons-elementor-lite' ),
                                'type'      => Controls_Manager::COLOR,
                                'separator' => 'before',
                                'selectors' => [
                                                '{{WRAPPER}} .social_links i:hover' => 'color: {{VALUE}};',
                                ],
                        ]
                );
                $this->add_control(
			'icon_size',
			[
				'label' => __( 'Icon Size', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 80,
						'step' => 5,
					],
				],
                                'separator' => 'before',
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .social_links i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
               
                $this->end_controls_section();
        }
        protected function box_style(){
                        
                $this->start_controls_section(
                        'box_style',
                        [
                                'label' =>  __( 'Box', 'ultraaddons-elementor-lite' ) ,
                                'tab' => Controls_Manager::TAB_STYLE,
                        ]
                );
                $this->start_controls_tabs(
			'style_tabs'
		);
                //Normal Tab
                $this->start_controls_tab(
			'style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
			]
		);

                        $this->add_group_control(
                                Group_Control_Background::get_type(),
                                [
                                        'name' => 'team_box_background',
                                        'label' => __( 'Box Background', 'ultraaddons-elementor-lite' ),
                                        'types' => [ 'classic', 'gradient'],
                                        'selector' => '{{WRAPPER}} .ua-team-container .member',
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
                                        'name' => 'team_box_hover_background',
                                        'label' => __( 'Box Background', 'ultraaddons-elementor-lite' ),
                                        'types' => [ 'classic', 'gradient'],
                                        'selector' => '{{WRAPPER}} .ua-team-container .member:hover',
                                ]
                        );


		$this->end_controls_tab();

		$this->end_controls_tabs();
                //End Normal/Hover Tab

                $this->add_responsive_control(
			'box_padding',
			[
				'label'       => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
                                'separator' => 'before',
				'selectors'   => [
					'{{WRAPPER}} .ua-team-container .member' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
                $this->add_responsive_control(
			'content_padding',
			[
				'label'       => esc_html__( 'Content Padding', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-team-container .team-content-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
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
				'selectors'   => [
					'{{WRAPPER}} .ua-team-container .member' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
                $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'team_box_shadow',
				'label' => __( 'Box Shadow', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ua-team-container .member',
			]
		);

                $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                                'name' => 'box_border',
                                'label' => __( 'Border', 'ultraaddons-elementor-lite' ),
                                'fields_options' => [
                                        'border' => [
                                                'default' => 'solid',
                                        ],
                                        'width' => [
                                                'default' => [
                                                        'top' => '1',
                                                        'right' => '1',
                                                        'bottom' => '1',
                                                        'left' => '1',
                                                        'isLinked' => false,
                                                ],
                                        ],
                                        'color' => [
                                                'default' => '#f7f7f7',
                                        ],
                                ],
                                'selector' => '{{WRAPPER}} .ua-team-container .member',
                        ]
                );
               
                $this->end_controls_section();
        }

        protected function image_style(){
                        
                $this->start_controls_section(
                        'image_style',
                        [
                                'label' =>  __( 'Image', 'ultraaddons-elementor-lite' ) ,
                                'tab' => Controls_Manager::TAB_STYLE,
                        ]
                );
                $this->add_control(
			'image_size',
			[
				'label' => esc_html__( 'Image Size', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ua-team-container .member img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
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
					'{{WRAPPER}} .ua-team-container .member img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
                $this->add_responsive_control(
			'image_margin',
			[
				'label'       => esc_html__( 'Image Margin', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ '%', 'px' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-team-container .member img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
                $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'label' => __( 'Border', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ua-team-container .member img',
			]
		);
             
                $this->end_controls_section();
        }

    /**
     * General Section for Content Controls
     * 
     * @since 1.0.0.9
     */
    protected function content_socials_controls() {
        $this->start_controls_section(
            'social',
            [
                'label'     => esc_html__( 'Social', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $repeater = new Repeater();

        $repeater->add_control(
                'name',
                [
                        'label' => __( 'Profile Name', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::SELECT2,
                        'label_block' => true,
                        'select2options' => [
                                'allowClear' => false,
                        ],
                        'options' => self::get_profile_names()
                ]
        );

        $repeater->add_control(
                'link', [
                        'label' => __( 'Profile Link', 'ultraaddons-elementor-lite' ),
                        'placeholder' => __( 'Add your profile link', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::URL,
                        'label_block' => true,
                        'autocomplete' => false,
                        'show_external' => false,
                        'condition' => [
                                'name!' => 'email'
                        ],
                        'dynamic' => [
                                'active' => true,
                        ]
                ]
        );

        $repeater->add_control(
                'email', [
                        'label' => __( 'Email Address', 'ultraaddons-elementor-lite' ),
                        'placeholder' => __( 'Add your email address', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => false,
                        'input_type' => 'email',
                        'condition' => [
                                'name' => 'email'
                        ],
                        'dynamic' => [
                                'active' => true,
                        ]
                ]
        );

        
        
        $repeater->add_control(
                'social_icon_color',
                [
                        'label' => __( 'Icon Color', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-single-team-wrapper {{CURRENT_ITEM}}' => 'color: {{VALUE}}',
				],
                        'style_transfer' => true,
                ]
        );

        

        $this->add_control(
                'profiles',
                [
                        'show_label' => false,
                        'type' => Controls_Manager::REPEATER,
                        'fields' => $repeater->get_controls(),
                        'title_field' => '<# print(name.slice(0,1).toUpperCase() + name.slice(1)) #>',
                        'default' => [
                                [
                                        'link' => ['url' => 'https://facebook.com/'],
                                        'name' => 'facebook'
                                ],
                                [
                                        'link' => ['url' => 'https://twitter.com/'],
                                        'name' => 'twitter'
                                ],
                                [
                                        'link' => ['url' => 'https://linkedin.com/'],
                                        'name' => 'linkedin'
                                ]
                        ],
                ]
        );

        $this->add_control(
                'show_profiles',
                [
                        'label' => __( 'Show Profiles', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::SWITCHER,
                        'label_on' => __( 'Show', 'ultraaddons-elementor-lite' ),
                        'label_off' => __( 'Hide', 'ultraaddons-elementor-lite' ),
                        'return_value' => 'yes',
                        'default' => 'no',
                        'separator' => 'before',
                        'style_transfer' => true,
                ]
        );

        
        $this->end_controls_section();
    }
    
    /**
     * Alignment Section for Style Tab
     * 
     * @since 1.0.0.9
     */
   
    
   
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
        $image = !empty( $settings['image']['url'] ) ? $settings['image']['url'] : false;
        ?>
        
        <div class="ua-team-container">
                <div class="member">
                        <img src="<?php echo esc_url( $image ); ?>">
                        <div class="team-content-wrap">
                                <h2 class="ua-team-title"><?php echo esc_html( $settings['title'] ); ?></h2>
                                        <span class="who"><?php echo esc_html( $settings['designation'] ); ?></span>
                                <p class="member-text"><?php echo esc_html( $settings['description'] ); ?></p>
                                <div class="social-icons">
                                        <?php $this->social_links();?>
                                </div>
                        </div>
                </div>
        </div>
          
        <?php
        
    }

     protected static function get_profile_names() {
        return [
                '500px'          => __( '500px', 'ultraaddons-elementor-lite' ),
                'apple'          => __( 'Apple', 'ultraaddons-elementor-lite' ),
                'behance'        => __( 'Behance', 'ultraaddons-elementor-lite' ),
                'bitbucket'      => __( 'BitBucket', 'ultraaddons-elementor-lite' ),
                'codepen'        => __( 'CodePen', 'ultraaddons-elementor-lite' ),
                'delicious'      => __( 'Delicious', 'ultraaddons-elementor-lite' ),
                'deviantart'     => __( 'DeviantArt', 'ultraaddons-elementor-lite' ),
                'digg'           => __( 'Digg', 'ultraaddons-elementor-lite' ),
                'dribbble'       => __( 'Dribbble', 'ultraaddons-elementor-lite' ),
                'email'          => __( 'Email', 'ultraaddons-elementor-lite' ),
                'facebook'       => __( 'Facebook', 'ultraaddons-elementor-lite' ),
                'flickr'         => __( 'Flicker', 'ultraaddons-elementor-lite' ),
                'foursquare'     => __( 'FourSquare', 'ultraaddons-elementor-lite' ),
                'github'         => __( 'Github', 'ultraaddons-elementor-lite' ),
                'houzz'          => __( 'Houzz', 'ultraaddons-elementor-lite' ),
                'instagram'      => __( 'Instagram', 'ultraaddons-elementor-lite' ),
                'jsfiddle'       => __( 'JS Fiddle', 'ultraaddons-elementor-lite' ),
                'linkedin'       => __( 'LinkedIn', 'ultraaddons-elementor-lite' ),
                'medium'         => __( 'Medium', 'ultraaddons-elementor-lite' ),
                'pinterest'      => __( 'Pinterest', 'ultraaddons-elementor-lite' ),
                'product-hunt'   => __( 'Product Hunt', 'ultraaddons-elementor-lite' ),
                'reddit'         => __( 'Reddit', 'ultraaddons-elementor-lite' ),
                'slideshare'     => __( 'Slide Share', 'ultraaddons-elementor-lite' ),
                'snapchat'       => __( 'Snapchat', 'ultraaddons-elementor-lite' ),
                'soundcloud'     => __( 'SoundCloud', 'ultraaddons-elementor-lite' ),
                'spotify'        => __( 'Spotify', 'ultraaddons-elementor-lite' ),
                'stack-overflow' => __( 'StackOverflow', 'ultraaddons-elementor-lite' ),
                'tripadvisor'    => __( 'TripAdvisor', 'ultraaddons-elementor-lite' ),
                'tumblr'         => __( 'Tumblr', 'ultraaddons-elementor-lite' ),
                'twitch'         => __( 'Twitch', 'ultraaddons-elementor-lite' ),
                'twitter'        => __( 'Twitter', 'ultraaddons-elementor-lite' ),
                'vimeo'          => __( 'Vimeo', 'ultraaddons-elementor-lite' ),
                'vk'             => __( 'VK', 'ultraaddons-elementor-lite' ),
                'website'        => __( 'Website', 'ultraaddons-elementor-lite' ),
                'whatsapp'       => __( 'WhatsApp', 'ultraaddons-elementor-lite' ),
                'wordpress'      => __( 'WordPress', 'ultraaddons-elementor-lite' ),
                'xing'           => __( 'Xing', 'ultraaddons-elementor-lite' ),
                'yelp'           => __( 'Yelp', 'ultraaddons-elementor-lite' ),
                'youtube'        => __( 'YouTube', 'ultraaddons-elementor-lite' ),
        ];
    }

    
}