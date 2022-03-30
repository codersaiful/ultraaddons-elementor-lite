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

       
    }
    
    
    
    protected function social_links() {
        $settings           = $this->get_settings_for_display();
        if ( $settings['show_profiles' ] && is_array( $settings['profiles' ] ) ) {
        ?>
            <div class="team-box-social-link">
                        
                <ul class="social_links">
                    <?php
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
        
        $placeholder_image = ULTRA_ADDONS_URL . 'assets/images/team-member.jpg';

        $this->start_controls_section(
                '_section_info',
                [
                        'label' => __( 'Information', 'ultraaddons' ),
                        'tab' => Controls_Manager::TAB_CONTENT,
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
                        'default' => __( 'WordPress Developer', 'ultraaddons' ),
                        'placeholder' => __( 'Type Member Designation', 'ultraaddons' ),
                        'dynamic' => [
                                'active' => true,
                        ]
                ]
        );
        $this->add_control(
                'description',
                [
                        'label' => __( 'Description', 'ultraaddons' ),
                        'label_block' => true,
                        'type' => Controls_Manager::TEXTAREA,
                        'default' => __( 'Id cibo omnium perfecto sed, vel eius rationibus ea. Ea postea ocurreret reformidans eam, vix ea iudico.', 'ultraaddons' ),
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
        
        <div id="member-container">
                <div class="member">
                <img src="<?php echo $image;?>">
                <h2><?php echo $settings['title'];?></h2>
                <p class="who"><?php echo $settings['designation'];?></p>
                <p class="member-text"><?php echo $settings['description'];?></p>
                
                <div class="soc-icons">
                <?php 
                foreach($settings['profiles'] as $profile):
                ?>
                <a href="https://twitter.com">&#xf099;</a>
                <?php endforeach;?>
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