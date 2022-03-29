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
use Elementor\Repeater;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

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
        
        //For Design Section Style Tab
        $this->style_design_controls();
        
        //For Typography Section Style Tab
        $this->style_typography_controls();

       
    }
    
    
    
    protected function social_links() {
        $settings           = $this->get_settings_for_display();
        if ( $settings['show_profiles' ] && is_array( $settings['profiles' ] ) ) {
        ?>
            <div class="team-box-social-link">
                        
                <ul class="social_links">
                    <?php
                    echo 'temp-2' == $settings['template'] ? '<li class="handle"><i class="fas fa-plus"></i></li>' : '';
                    foreach ( $settings['profiles'] as $profile ) :
                            $icon = $profile['name'];
                            $url = $profile['link']['url'];

                            if ( $profile['name'] === 'website' ) {
                                    $icon = 'globe far';
                            } elseif ( $profile['name'] === 'email' ) {
                                    $icon = 'envelope far';
                                    $url = 'mailto:' . antispambot( $profile['email'] );
                            } else {
                                    $icon .= ' fab';
                            }

                            printf( '<li><a target="_blank" rel="noopener" href="%s" class="elementor-repeater-item-%s"><i class="lab fa-%s" aria-hidden="true"></i></a></li>',
                                    esc_url( $url ),
                                    esc_attr( $profile['_id'] ),
                                    esc_attr( $icon )
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
        
        $placeholder_image = ULTRA_ADDONS_URL . 'assets/images/user.png';

        $this->start_controls_section(
                '_section_info',
                [
                        'label' => __( 'Information', 'ultraaddons' ),
                        'tab' => Controls_Manager::TAB_CONTENT,
                ]
        );
        
        $this->add_control(
            'template',
                [
                    'label'         => __( 'Template', 'ultraaddons' ),
                    'type'          => Controls_Manager::SELECT,
                    'options' => [
                            'default'   => __( 'Default', 'ultraaddons' ),
                            'temp-2'    => __( 'Template Two', 'ultraaddons' ),
                    ],
                    'default' => 'default',
                    'prefix_class' => 'team-box-',
                ]
        );

        $this->add_control(
                'image',
                [
                        'label' => __( 'Photo', 'ultraaddons' ),
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
                        'label' => __( 'Name', 'ultraaddons' ),
                        'label_block' => true,
                        'type' => Controls_Manager::TEXT,
                        'default' => __( 'Mr. Name', 'ultraaddons' ),
                        'placeholder' => __( 'Type Member Name', 'ultraaddons' ),
                        'separator' => 'before',
                        'dynamic' => [
                                'active' => true,
                        ]
                ]
        );

        $this->add_control(
                'designation',
                [
                        'label' => __( 'Designation', 'ultraaddons' ),
                        'label_block' => true,
                        'type' => Controls_Manager::TEXT,
                        'default' => __( 'Business Man', 'ultraaddons' ),
                        'placeholder' => __( 'Type Member Designation', 'ultraaddons' ),
                        'dynamic' => [
                                'active' => true,
                        ]
                ]
        );
        
        $this->add_control(
                'link',
                [
                        'label' => __( 'Link', 'ultraaddons' ),
                        'type' => Controls_Manager::URL,
                        'dynamic' => [
                                'active' => true,
                        ],
                        'placeholder' => __( 'https://your-link.com', 'ultraaddons' ),
                        'default' => [
                                'url' => '#',
                        ],
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
                'label'     => esc_html__( 'Social', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $repeater = new Repeater();

        $repeater->add_control(
                'name',
                [
                        'label' => __( 'Profile Name', 'ultraaddons' ),
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
                        'label' => __( 'Profile Link', 'ultraaddons' ),
                        'placeholder' => __( 'Add your profile link', 'ultraaddons' ),
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
                        'label' => __( 'Email Address', 'ultraaddons' ),
                        'placeholder' => __( 'Add your email address', 'ultraaddons' ),
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
                        'label' => __( 'Icon Color', 'ultraaddons' ),
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
                        'label' => __( 'Show Profiles', 'ultraaddons' ),
                        'type' => Controls_Manager::SWITCHER,
                        'label_on' => __( 'Show', 'ultraaddons' ),
                        'label_off' => __( 'Hide', 'ultraaddons' ),
                        'return_value' => 'yes',
                        'default' => 'yes',
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
    protected function style_design_controls() {
        $this->start_controls_section(
            'style_general',
            [
                'label'     => esc_html__( 'Design', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        

        $this->add_control(
            'primary_color',
            [
                'label'     => __( 'Primary Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-single-team-wrapper .team-box-btn a' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-single-team-wrapper .team-box-item:hover ' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-single-team-wrapper .team-box-item:hover .team-box-btn a' => 'outline: 2px solid {{VALUE}};color: {{VALUE}};background-color: transparent;',
                ],
                'default'   => '#0FC392',
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
                    '{{WRAPPER}} .ua-single-team-wrapper .team-box-title' => 'color: {{VALUE}}',
                ],
                'default'   => '#21272C',
            ]
        );
        
        $this->add_control(
            'description_color',
            [
                'label'     => __( 'Designation Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-single-team-wrapper .team-box-designation' => 'color: {{VALUE}}',
                ],
                'default'   => '#5C6B79',
            ]
        );
        
        
                
        $this->add_responsive_control(
                'margin',
                [
                        'label' => __( 'Content Margin', 'ultraaddons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%' ],
                        'default'   => [
                                //'size' => 55,
                                'unit' => 'px',
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .ua-single-team-wrapper .team-box-info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ]
        );
        
        $this->add_responsive_control(
                'avd_head_space',
                [
                        'label' => __( 'Profile Box Size', 'ultraaddons' ),
                        'type' => Controls_Manager::SLIDER,
                        'default' => [
                                'size' => 230,
                        ],
                        'range' => [
                                'px' => [
                                        'min' => 80,
                                        'max' => 600,
                                ],
                        ],
                        'condition' => [
                                'template' => 'default',
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .ua-single-team-wrapper .team-box-profile-avatar' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
                        ],
                ]
        );
        
        
        $this->add_responsive_control(
                'padding',
                [
                        'label' => __( 'Content Padding', 'ultraaddons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%' ],
                        'default'   => [
                                'unit' => 'px',
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .ua-single-team-wrapper .team-box-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                        'selector' => '{{WRAPPER}} .ua-single-team-wrapper h2.team-box-title',

                ]
        );
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'design_typography',
                        'label' => 'Designation Typography',
                        'selector' => '{{WRAPPER}} .ua-single-team-wrapper .team-box-designation',

                ]
        );
        
        
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
        
        $this->add_render_attribute( 'wrapper', 'class', 'ua-single-team-wrapper' );
        
        $this->add_inline_editing_attributes( 'title', 'None' );
        $this->add_render_attribute( 'title', 'class', 'team-box-title' );

        $this->add_inline_editing_attributes( 'designation', 'none' );
        $this->add_render_attribute( 'designation', 'class', 'team-box-designation' );

        
       $link = false;
        if ( ! empty( $settings['link']['url'] ) ) {
                $this->add_link_attributes( 'button', $settings['link'] );
                $this->add_render_attribute( 'button', 'class', 'team-box-link' );
                $link = true;
        }
        
        //Value Assigning
        $image = !empty( $settings['image']['url'] ) ? $settings['image']['url'] : false;

        if( empty( $image ) ){
            $this->add_render_attribute( 'wrapper', 'class', 'no-profile-image' );
        }
        $title = !empty( $settings['title'] ) ? $settings['title'] : false;
        $designation = !empty( $settings['designation'] ) ? $settings['designation'] : false;
        ?>
    <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
        <div class="team-box-item">
            <div class="team-box-profile-avatar"
                 
                <?php if( ! empty( $image ) ){ ?>
                 style="background-image: url(<?php echo esc_attr( $image ); ?>);"
                <?php } ?> 
                 >
                
                
                <?php 
                if( ! empty( $image ) ){ //'temp-2' == $settings['template'] && 
                ?>
                <img class="ua-temp2-profile-image" src="<?php echo esc_url( $image ); ?>">    
                <?php    
                }

                $this->social_links();
                ?>
            </div>  
            
            <div class="team-box-info">
                <div class="team-box-name">
                    <?php if( $title ){ ?>
                    <h2 <?php echo $this->get_render_attribute_string( 'title' ); ?>><?php echo esc_html( $title ); ?></h2>
                    <?php } ?>
                    
                    <?php if( $designation ){ ?>
                    <span <?php echo $this->get_render_attribute_string( 'designation' ); ?>><?php echo esc_html( $designation ); ?></span>
                    <?php } ?>
                </div>
                <?php if( $link ){ ?>
                <div class="team-box-btn">
                    <a <?php echo $this->get_render_attribute_string( 'button' ); ?>><i class="fas fa-angle-double-right"></i></a>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
          
        <?php
        
    }

     protected static function get_profile_names() {
        return [
                '500px'          => __( '500px', 'ultraaddons' ),
                'apple'          => __( 'Apple', 'ultraaddons' ),
                'behance'        => __( 'Behance', 'ultraaddons' ),
                'bitbucket'      => __( 'BitBucket', 'ultraaddons' ),
                'codepen'        => __( 'CodePen', 'ultraaddons' ),
                'delicious'      => __( 'Delicious', 'ultraaddons' ),
                'deviantart'     => __( 'DeviantArt', 'ultraaddons' ),
                'digg'           => __( 'Digg', 'ultraaddons' ),
                'dribbble'       => __( 'Dribbble', 'ultraaddons' ),
                'email'          => __( 'Email', 'ultraaddons' ),
                'facebook'       => __( 'Facebook', 'ultraaddons' ),
                'flickr'         => __( 'Flicker', 'ultraaddons' ),
                'foursquare'     => __( 'FourSquare', 'ultraaddons' ),
                'github'         => __( 'Github', 'ultraaddons' ),
                'houzz'          => __( 'Houzz', 'ultraaddons' ),
                'instagram'      => __( 'Instagram', 'ultraaddons' ),
                'jsfiddle'       => __( 'JS Fiddle', 'ultraaddons' ),
                'linkedin'       => __( 'LinkedIn', 'ultraaddons' ),
                'medium'         => __( 'Medium', 'ultraaddons' ),
                'pinterest'      => __( 'Pinterest', 'ultraaddons' ),
                'product-hunt'   => __( 'Product Hunt', 'ultraaddons' ),
                'reddit'         => __( 'Reddit', 'ultraaddons' ),
                'slideshare'     => __( 'Slide Share', 'ultraaddons' ),
                'snapchat'       => __( 'Snapchat', 'ultraaddons' ),
                'soundcloud'     => __( 'SoundCloud', 'ultraaddons' ),
                'spotify'        => __( 'Spotify', 'ultraaddons' ),
                'stack-overflow' => __( 'StackOverflow', 'ultraaddons' ),
                'tripadvisor'    => __( 'TripAdvisor', 'ultraaddons' ),
                'tumblr'         => __( 'Tumblr', 'ultraaddons' ),
                'twitch'         => __( 'Twitch', 'ultraaddons' ),
                'twitter'        => __( 'Twitter', 'ultraaddons' ),
                'vimeo'          => __( 'Vimeo', 'ultraaddons' ),
                'vk'             => __( 'VK', 'ultraaddons' ),
                'website'        => __( 'Website', 'ultraaddons' ),
                'whatsapp'       => __( 'WhatsApp', 'ultraaddons' ),
                'wordpress'      => __( 'WordPress', 'ultraaddons' ),
                'xing'           => __( 'Xing', 'ultraaddons' ),
                'yelp'           => __( 'Yelp', 'ultraaddons' ),
                'youtube'        => __( 'YouTube', 'ultraaddons' ),
        ];
    }

    
}