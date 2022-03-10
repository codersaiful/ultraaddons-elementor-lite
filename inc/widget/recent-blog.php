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

class Recent_Blog extends Base{
        
    /**
     * Get your widget by keywords
     *
     * @since 1.0.0
     * @access public
     *
     * @return string keywords
     */
    public function get_keywords() {
        return [ 'ultraaddons', 'ua', 'blog', 'recent', 'recent blog', 'recent content' ];
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

        //For Query Section
        $this->content_query_controls();
        
        //For Query Section
        $this->content_layout_controls();
        
        //For General Section
        $this->content_general_controls();

       
        //For Design Section Style Tab
        $this->style_design_controls();
        
        //For Typography Section Style Tab
        $this->style_typography_controls();

       
    }
    protected function excerpts( $limit ){
    
      $excerpt = explode(' ', get_the_excerpt(), $limit);

      if (count($excerpt) >= $limit) {
          array_pop($excerpt);
          $excerpt = implode(" ", $excerpt) . '...';
      } else {
          $excerpt = implode(" ", $excerpt);
      }

      $excerpt = preg_replace('`\[[^\]]*\]`', '', $excerpt);

      return $excerpt;
    }


    /**
     * Populating / Displaying Blogs
     * Based on Args
     * 
     * Why we did it as a method.
     * Becuase, that will be need two different place
     * Such: Layout two, Need two Query.
     * Each time, we dont want to write same code
     * 
     * @param type $args Query argument for query expected post
     * @return Void Actually we have just 
     */
    protected function populate_blog_loop( $args = false ){
        
        if( ! $args || ! is_array( $args ) ){
            return false;
        }
        $the_query = new \WP_Query( $args );
        
        
        // The Loop
        if ( $the_query->have_posts() ){
            while ( $the_query->have_posts() ) {
                $the_query->the_post();

                $thumbnail = ULTRA_ADDONS_URL . 'assets/images/no-image.png';
                if( has_post_thumbnail() ){
                    /**
                     * Compatible with Astha Theme
                     * 
                     * @link https://wordpress.org/themes/astha/ Our Default Theme
                     * 
                     * @since 1.0.5
                     * @author Saiful Islam
                     */
                    $thumbnail = get_the_post_thumbnail_url( null, 'astha-thumbnail' );
                }


            $year  = get_the_time( 'Y' ); 
            $month = get_the_time( 'm' ); 
            $day   = get_the_time( 'd' );      
            $permalink = get_day_link( $year, $month, $day );
            
            
        ?>
        <div class="recent-blog-item">
            <?php if( $this->thumbs ){ ?>
            <div class="recent-blog-img">
                <a 
                    <?php  if( $this->layout == 'modern' && isset( $args['custom_thumb_image'] ) && $args['custom_thumb_image'] == 'background'){ ?>
                    style="background-image:url(<?php echo esc_url( $thumbnail ); ?>);"    
                    <?php } ?>
                    class="ua-recent-blog-permalink ua-recent-blog-permalink-image" href="<?php the_permalink(); ?>">
                    <img class="ultraaddons-rcnt-post-image" 
                        src="<?php echo esc_url( $thumbnail ); ?>" 
                        alt="<?php echo esc_attr( get_the_title() ); ?>"
                        srcset="" style="will-change: auto;">
                </a>
                
                <?php if( $this->date ){ ?>
                <a href="<?php echo esc_url( $permalink ); ?>" class="recent-blog-date">
                    <?php echo esc_html( get_the_date() ); ?>
                </a>
                <?php } ?>
                
            </div>
            <?php } ?>
            <div class="recent-blog-info">
                
                <?php 
                    
                if( $this->meta ){
                    
                //esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) )
                //If need URL we can use upper function
                ?>
                <div class="recent-blog-meta">
                    <span class="recent-blog-author"><i class="far fa-user"></i><?php echo esc_html( get_the_author() ); ?></span>
                    
                    <span class="published-date <?php echo $this->date ? 'hide-on-mobile' : '' ; ?>"><i class="far fa-calendar-alt"></i><?php echo esc_html( get_the_date() ); ?></span>
                    
                </div>
                <?php } ?>
                
                <?php if( $this->title ){ ?>
                <a class="ua-recent-blog-permalink ua-recent-blog-permalink-title" href="<?php the_permalink(); ?>">
                    <h2 class="ua-recent-blog-title"><?php the_title(); ?></h2>
                </a>
                <?php 
//                var_dump($this->excerpt_limit);
                }
                if( $this->excerpt && $this->excerpt_limit ){ ?>
                <div class="para ua-rb-ecerpt"><?php echo $this->excerpts( $this->excerpt_limit['size'] ); ?></div>
                <?php 
                }
                if( ! empty( $this->read_more ) ){ ?>
                <a href="<?php the_permalink(); ?>" class="read-more"><?php echo esc_html( $this->read_more ); ?> <i class="fas fa-angle-double-right"></i></a>
            <?php } ?>
            </div>
        </div>    
        <?php
            }
        } else {
            // no posts found
        }
        wp_reset_postdata();
        wp_reset_query();
        return true;
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

        $this->add_render_attribute( 'wrapper', 'class', 'recent-blog-item-wrap' );
        
        //Find Post Amount, Obviously should Int
        $post_per_page = isset( $settings['ua_posts_per_page']['size'] ) && ! empty( $settings['ua_posts_per_page']['size'] ) ? $settings['ua_posts_per_page']['size'] : 3;
        
        if( ! isset( $settings['ua_posts_per_page'] ) ){
            return;
        }

        $this->add_render_attribute( 'wrapper', 'class', 'layout-' . $settings['layout'] );
        $this->add_render_attribute( 'wrapper', 'class', 'thumbs-' . $settings['thumbs_on_off'] );
        $this->add_render_attribute( 'wrapper', 'class', 'date-' . $settings['date_on_off'] );
        $this->add_render_attribute( 'wrapper', 'class', 'meta-' . $settings['meta_on_off'] );
        
        $categories = !empty( $settings['ua_posts_cat'] ) ? $settings['ua_posts_cat'] : false;
        $this->read_more = !empty( $settings['ua_rc_read_more'] ) ? $settings['ua_rc_read_more'] : false;
        $this->excerpt = !empty( $settings['excerpt_on_off'] ) && $settings['excerpt_on_off'] == 'on' ? true : false;
        $this->excerpt_limit = !empty( $settings['excerpt_limit'] ) && $settings['excerpt_limit'] > 0 ? $settings['excerpt_limit'] : false;
        $this->title = !empty( $settings['title_on_off'] ) && $settings['title_on_off'] == 'on' ? true : false;
        $this->date = !empty( $settings['date_on_off'] ) && $settings['date_on_off'] == 'on' ? true : false;
        $this->meta = !empty( $settings['meta_on_off'] ) && $settings['meta_on_off'] == 'on' ? true : false;
        $this->thumbs = !empty( $settings['thumbs_on_off'] ) && $settings['thumbs_on_off'] == 'on' ? true : false;
        $this->layout = !empty( $settings['layout'] ) ? $settings['layout'] : 'classic';

        
        $args = [
            'posts_per_page'    =>  is_numeric( $post_per_page ) ? $post_per_page : 3,
            'post_type'         =>  'post',
            'post_status'       =>  'publish',
            'category__in'      =>  $categories,
            'post__not_in'      =>  get_option( 'sticky_posts' ),
            'custom_thumb_image'=>  'background', //Normal or background,//Its created by me. To maintain image as background on two layout
        ];
        
        if( $this->layout == 'modern' ){
            $args['offset'] = 1;
        }

        
        
        
        ?>

        

        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <?php
            //SARTING: recent blog right part starting, and when only modern layout
            if( $this->layout == 'modern' ){
                /**
                 * We have to change some Args of our Default Query,
                 * Thats why, need new name.
                 * Becuae: default args will be use at the bottom of this code
                 */
                $single_args = $args;
                $single_args['posts_per_page'] = 1;
                $single_args['custom_thumb_image'] = 'normal';
                $single_args['offset'] = 0;
            ?>
            
            <div class="recent-blog-wrap-left">
                <?php $this->populate_blog_loop( $single_args ); ?>
            </div>
            
            <!-- right part of recent blog -->
            <div class="recent-blog-wrap-right">
            <?php  
            
                /**
                 * After Populating First Post
                 * We will remove Excerpt
                 * and remove Default date in second loop
                 * When Modern Layout is enabled
                 */
//                $this->excerpt = false;
//                $this->date = false;
            
            } //ending if statement
            
            //Blog Populating is for always
            $this->populate_blog_loop( $args );
            
            //CLOSING: recent blog right part closing, and when only modern layout
            if( $this->layout == 'modern' ){
            ?>
            </div> <!-- .recent-blog-wrap-left -->
            <?php }//ending if statement ?>
            
        </div> 
        <?php
        /* Restore original Post Data */
        wp_reset_postdata();
    }
    
    protected function content_template() {
        /*
        ?>
        <#
        view.addInlineEditingAttributes( 'avd_heading', 'none' );
        view.addInlineEditingAttributes( 'avd_sub_heading', 'none' );
        #>
        
        <div class="advance-heading-wrapper">
            <span {{{ view.getRenderAttributeString( 'avd_sub_heading' ) }}}>{{{ settings.avd_sub_heading }}}</span>
            <h4 class="heading-tag" {{{ view.getRenderAttributeString( 'avd_heading' ) }}}>{{{ settings.avd_heading }}}</h4>
        </div>
        <?php
        */
    }
    
    /**
     * Query Section for Content Controls
     * 
     * @since 1.0.0.9
     */
    protected function content_query_controls() {
        $this->start_controls_section(
            'ua_recent_post_query',
            [
                'label'     => esc_html__( 'Query', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
                'ua_posts_per_page',
                [
                        'label' => __( 'Posts Count', 'ultraaddons' ),
                        'type' => Controls_Manager::SLIDER,
                        'default' => [
                                'size' => 3,
                        ],
                        'range' => [
                                        'px' => [
                                                'min' => 1,
                                                'max' => 100,
                                                'step'=> 1
                                        ]
                        ],
                ]
        );
        
        /**
         * Getting Category tax
         * as Option
         */
        $cat_options = $this->get_cat_as_options();
        $this->add_control(
                'ua_posts_cat',
                [
                        'label' => __( 'Categories', 'ultraaddons' ),
                        'type' => Controls_Manager::SELECT2,
                        'default' => [],
                        'multiple' => true,
                        'options' => $cat_options,
                ]
        );
        
        
        
        $this->end_controls_section();
    }
    
    
    /**
     * Query Section for Content Controls
     * 
     * @since 1.0.0.9
     */
    protected function content_layout_controls() {
        $this->start_controls_section(
            'layout_section',
            [
                'label'     => esc_html__( 'Layout', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
        
        //grip-horizontal fa-th-large
        $this->add_control(
                'layout',
                [
                        'label' => __( 'Layout', 'ultraaddons' ),
                        'type' => Controls_Manager::CHOOSE,
                        'options' => [
                                'classic' => [
                                        'title' => __( 'Classic', 'ultraaddons' ),
                                        'icon' => 'fa fas fa-grip-horizontal',
                                ],
                                'modern' => [
                                        'title' => __( 'Modern', 'ultraaddons' ),
                                        'icon' => 'fa fas fa-th-large',
                                ],

                        ],
                        'default' => 'classic',
                        'toggle'    => false,
                
                ]
        );
        
        
        $this->end_controls_section();
    }
    
    
    /**
     * General Section for Content Controls
     * 
     * @since 1.0.0.9
     */
    protected function content_general_controls() {
        $this->start_controls_section(
            'ua_recent_post_general',
            [
                'label'     => esc_html__( 'General', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
                'ua_rc_read_more',
                [
                        'label' => __( 'Read More text', 'ultraaddons' ),
                        'type' => Controls_Manager::TEXT,
                        'default' => __( 'Read More', 'ultraaddons' ),
                ]
        );
        
        $this->add_control(
            'title_on_off',
                [
                    'label'         => esc_html__( 'Title', 'ultraaddons' ),
                    'type'          => Controls_Manager::CHOOSE,
                    'options' => [
                            'on' => [
                                    'title' => __( 'Show', 'ultraaddons' ),
                                    'icon' => 'fa fa-eye',
                            ],
                            'off' => [
                                    'title' => __( 'Hide', 'ultraaddons' ),
                                    'icon' => 'fa fa-eye-slash',
                            ],
                    ],
                    'default' => 'on',
                    'toggle' => true,
                ]
        );
        
        $this->add_control(
            'excerpt_on_off',
                [
                    'label'         => esc_html__( 'Excerpt', 'ultraaddons' ),
                    'type'          => Controls_Manager::CHOOSE,
                    'options' => [
                            'on' => [
                                    'title' => __( 'Show', 'ultraaddons' ),
                                    'icon' => 'fa fa-eye',
                            ],
                            'off' => [
                                    'title' => __( 'Hide', 'ultraaddons' ),
                                    'icon' => 'fa fa-eye-slash',
                            ],
                    ],
                    'default' => 'on',
                    'toggle' => true,
                ]
        );
        
        $this->add_control(
            'excerpt_limit',
                [
                    'label'         => esc_html__( 'Excerpt Limit', 'ultraaddons' ),
                    'type'          => Controls_Manager::SLIDER,
                    'default' => [
                            'size' => 20,
                    ],
                    'range' => [
                            'num' => [
                                    'min' => 10,
                                    'max' => 100,
                            ],
                    ],
                    'condition' => [
                            'excerpt_on_off' => 'on',
                    ],
                ]
        );
        
        
        $this->add_control(
            'thumbs_on_off',
                [
                    'label'         => esc_html__( 'Thumbs', 'ultraaddons' ),
                    'description'   => esc_html__( 'Thumbnail Image and Date will be hidden.', 'ultraaddons' ),
                    'type'          => Controls_Manager::CHOOSE,
                    'options' => [
                            'on' => [
                                    'title' => __( 'Show', 'ultraaddons' ),
                                    'icon' => 'fa fa-eye',
                            ],
                            'off' => [
                                    'title' => __( 'Hide', 'ultraaddons' ),
                                    'icon' => 'fa fa-eye-slash',
                            ],
                    ],
                    'default' => 'on',
                    'toggle' => true,
                ]
        );
        
        
        
        
        $this->add_control(
            'date_on_off',
                [
                    'label'         => esc_html__( 'Date', 'ultraaddons' ),
                    'description'   => esc_html__( 'Recommends for Classic Layout.', 'ultraaddons' ),
                    'type'          => Controls_Manager::CHOOSE,
                    'options' => [
                            'on' => [
                                    'title' => __( 'Show', 'ultraaddons' ),
                                    'icon' => 'fa fa-eye',
                            ],
                            'off' => [
                                    'title' => __( 'Hide', 'ultraaddons' ),
                                    'icon' => 'fa fa-eye-slash',
                            ],
                    ],
                    'default' => 'on',
                    'toggle' => true,
                ]
        );
        
        
        $this->add_control(
            'meta_on_off',
                [
                    'label'         => esc_html__( 'Meta Info', 'ultraaddons' ),
                    'description'   => esc_html__( 'Recommends for Modern Layout.', 'ultraaddons' ),
                    'type'          => Controls_Manager::CHOOSE,
                    'options' => [
                            'on' => [
                                    'title' => __( 'Show', 'ultraaddons' ),
                                    'icon' => 'fa fa-eye',
                            ],
                            'off' => [
                                    'title' => __( 'Hide', 'ultraaddons' ),
                                    'icon' => 'fa fa-eye-slash',
                            ],
                    ],
                    'default' => 'on',
                    'toggle' => true,
                ]
        );
        
        
        
        $this->end_controls_section();
    }
    
    /**
     * Finding Category list as Array
     * What to show as Option for Query Section
     * 
     * @since 1.0.0.9
     */
    protected function get_cat_as_options(){
        $args = [
            'orderby'   =>  'count',
            'hide_empty'=>  0
        ];
        $categories = get_terms( 'category', $args );
        
        $options = [];
        if( is_array( $categories ) && count( $categories ) > 0 ){
            foreach( $categories as $category ){
                $options[$category->term_id]  = $category->name;
            }
        }

        return $options;
    }
    
    /**
     * Alignment Section for Style Tab
     * 
     * @since 1.0.0.9
     */
    protected function style_design_controls() {
        $this->start_controls_section(
            'ua_rc_design_style',
            [
                'label'     => esc_html__( 'Design', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        
        
        $this->add_control(
            'ua_rc_title_color',
            [
                'label'     => __( 'Title Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .recent-blog-info h2.ua-recent-blog-title' => 'color: {{VALUE}}',
                ],
                'default'   => '#021429',
                'condition' => [
                        'title_on_off' => 'on',
                ],
            ]
        );
        
        $this->add_control(
            'ua_rc_excerpt_color',
            [
                'label'     => __( 'Excerpt Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .recent-blog-info .para.ua-rb-ecerpt' => 'color: {{VALUE}}',
                ],
                'default'   => '#021429',
                'condition' => [
                        'excerpt_on_off' => 'on',
                ],
            ]
        );
        
        $this->add_control(
            'ua_rc_meta_color',
            [
                'label'     => __( 'Meta Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} span.recent-blog-author, {{WRAPPER}} span.published-date' => 'color: {{VALUE}}',
                ],
                'default'   => '#021429',
                'condition' => [
                        'meta_on_off' => 'on',
                ],
            ]
        );
        
        $this->add_control(
            'ua_rc_meta_icon_color',
            [
                'label'     => __( 'Meta Icon Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .recent-blog-meta span i' => 'color: {{VALUE}}',
                ],
                'default'   => '#021429',
                'condition' => [
                        'meta_on_off' => 'on',
                ],
            ]
        );
        
        $this->add_control(
            'ua_rc_readmore_color',
            [
                'label'     => __( 'Read More Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .recent-blog-info .read-more' => 'color: {{VALUE}}',
                ],
                'default'   => '#021429',
                'condition' => [
                        'ua_rc_read_more!' => '',
                ],
            ]
        );
        $this->add_control(
            'ua_rc_readmore_hover_color',
            [
                'label'     => __( 'Read More Hover Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .recent-blog-item:hover .recent-blog-info .read-more' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .recent-blog-item .recent-blog-info .read-more:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .recent-blog-item .recent-blog-info .read-more:active' => 'color: {{VALUE}}',
                ],
                'default'   => '#0FC392',
                'condition' => [
                        'ua_rc_read_more!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'ua_rc_date_text_color',
            [
                'label'     => __( 'Date Text Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .recent-blog-item-wrap .recent-blog-img a.recent-blog-date' => 'color: {{VALUE}}',
                ],
                'condition' => [
                        'date_on_off' => 'on',
                ],
            ]
        );
        
        
        $this->add_control(
            'ua_rc_date_bg_color',
            [
                'label'     => __( 'Date Background', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .recent-blog-item-wrap .recent-blog-img a.recent-blog-date' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .recent-blog-item-wrap span.recent-blog-author i,{{WRAPPER}} .recent-blog-item-wrap span.published-date i' => 'color: {{VALUE}}',
                ],
                'default'   => '#0FC392',
            ]
        );
        
        $this->add_responsive_control(
            'ua_rc_date_text_padding',
            [
                'label'     => __( 'Date Text Padding', 'ultraaddons' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .recent-blog-item-wrap .recent-blog-img a.recent-blog-date' => 'padding: {{TOP}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} {{RIGHT}}{{UNIT}}',
                ],
                'condition' => [
                        'date_on_off' => 'on',
                ],
            ]
        );
        
        
        $this->add_responsive_control(
                'ua_rc_space',
                [
                        'label' => __( 'Spacing', 'ultraaddons' ),
                        'type' => Controls_Manager::SLIDER,
                        'default' => [
                                'size' => 0,
                        ],
                        'range' => [
                                'px' => [
                                        'min' => 0,
                                        'max' => 100,
                                ],
                        ],
                        'selectors' => [
                                '{{WRAPPER}} a.ua-recent-blog-permalink.ua-recent-blog-permalink-title,{{WRAPPER}} .recent-blog-info .para.ua-rb-ecerpt' => 'padding-top: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .recent-blog-info .read-more' => 'margin-top: {{SIZE}}{{UNIT}};',
                        ],
                ]
        );
         $this->add_responsive_control(
                'ua_rc_image_height',
                [
                        'label' => __( 'Image Custom Height', 'ultraaddons' ),
                        'type' => Controls_Manager::SLIDER,
                        'default' => [
                                'size' => 0,
                        ],
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
                                '{{WRAPPER}} .recent-blog-img img' => 'height: {{SIZE}}{{UNIT}};',
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
            'ua_rc_typography',
            [
                'label'     => esc_html__( 'Typography', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'ua_rc_title_typography',
                        'label' => __( 'Title Typography', 'ultraaddons' ),
                        'selector' => '{{WRAPPER}} .recent-blog-info h2',
                        'global' => [
                                'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                        ],
                        'condition' => [
                                'title_on_off' => 'on',
                        ],
        
                ]
        );
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'ua_rc_excerpt_typography',
                        'label' => __( 'Content Typography', 'ultraaddons' ),
                        'selector' => '{{WRAPPER}} .ua-rb-ecerpt',
                        'global' => [
                                'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                        ],
                        'condition' => [
                                'excerpt_on_off' => 'on',
                        ],

                ]
        );
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'ua_rc_date_typography',
                        'label' => __( 'Date Typography', 'ultraaddons' ),
                        'selector' => '{{WRAPPER}} .recent-blog-img a.recent-blog-date',
                        'global' => [
                                'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                        ],
                        'condition' => [
                                'date_on_off' => 'on',
                        ],

                ]
        );
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'ua_rc_meta_typography',
                        'label' => __( 'Meta Typography', 'ultraaddons' ),
                        'selector' => '{{WRAPPER}} .recent-blog-meta',
                        'global' => [
                                'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                        ],
                        'condition' => [
                                'meta_on_off' => 'on',
                        ],

                ]
        );
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'ua_rc_readmore_typography',
                        'label' => __( 'Read More Typography', 'ultraaddons' ),
                        'selector' => '{{WRAPPER}} .recent-blog-info .read-more',
                        'global' => [
                                'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                        ],
                        'condition' => [
                                'ua_rc_read_more!' => '',
                        ],

                ]
        );
        
        $this->add_control(
                'ua_rc_icon_size',
                [
                        'label' => __( 'Icon Size', 'ultraaddons' ),
                        'type' => Controls_Manager::SLIDER,
                        'default' => [
                                'size' => 16,
                        ],
                        'range' => [
                                'px' => [
                                        'min' => 3,
                                        'max' => 100,
                                ]
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .recent-blog-meta span i, {{WRAPPER}} .read-more i' => 'font-size: {{SIZE}}{{UNIT}};',
                        ],
                ]
        );
        
        
        
        $this->end_controls_section();
    }
    
    
}