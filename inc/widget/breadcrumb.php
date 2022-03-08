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

class Breadcrumb extends Base{
    
    private $home;
    
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
        return [ 'ultraaddons','ua', 'breadcrumb', 'nav', 'navigation' ];
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
        $this->wrapper_controls();
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
        $this->home = ! empty( $settings['home_title'] ) ? $settings['home_title'] : esc_html( 'Home', 'ultraaddons' );
        $this->separator = ! empty( $settings['separator_sign'] ) ? $settings['separator_sign'] : '/';
        ?>
        <div class="ua-breadcrumb-wrapper" >
            <div class="ua-breadcrumb-menu ultraaddons-breadcrumb-menu">
                <?php $this->breadcrumb(); ?>
            </div>
        </div>    
        <?php
        
    }
    
        
    /**
     * General Section for Content Controls
     * 
     * @since 1.0.0.9
     */
    protected function wrapper_controls() {
        $this->start_controls_section(
            'wraper_style',
            [
                'label'     => esc_html__( 'Box', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'wraper_bg',
            [
                'label'     => __( 'Background', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ultraaddons-breadcrumb' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => esc_html__( 'Border', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} .ultraaddons-breadcrumb',
			]
		);
        $this->add_control(
			'wrap_padding',
			[
				'label' => esc_html__( 'Padding', 'ultraaddons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ultraaddons-breadcrumb' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->add_control(
			'border_radius',
			[
				'label' => esc_html__( 'Border radius', 'ultraaddons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ultraaddons-breadcrumb' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'label' => esc_html__( 'Box Shadow', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} .ultraaddons-breadcrumb',
			]
		);

        
        $this->end_controls_section();
    }
    protected function content_general_controls() {
        $this->start_controls_section(
            'general_content',
            [
                'label'     => esc_html__( 'General', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'home_title',
                [
                    'label'         => esc_html__( 'Home menu Text (Optional)', 'ultraaddons' ),
                    'type'          => Controls_Manager::TEXT,
                    'default'       => esc_html( 'Home', 'ultraaddons' ),
                    'label_block'   => TRUE,
                    'dynamic'       => ['active' => true],
                ]
        );
        
        $this->add_control(
            'separator_sign',
                [
                    'label'         => esc_html__( 'Separator (Optional)', 'ultraaddons' ),
                    'type'          => Controls_Manager::TEXT,
                    'default'       => '/',
                    'label_block'   => TRUE,
                    'dynamic'       => ['active' => true],
                ]
        );
        
        $this->add_control(
            'heading_alignment',
                [
                    'label'         => esc_html__( 'Align', 'ultraaddons' ),
                    'type'          => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
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
                    'default' => 'left',
                    'toggle' => true,
                    'prefix_class' => 'elementor-align-',
                ]
        );
        
        $this->add_control(
            'link_color',
            [
                'label'     => __( 'Link Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-breadcrumb-menu nav a, .ultraaddons-breadcrumb .item a' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'current_item',
            [
                'label'     => __( 'Current Menu Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-breadcrumb-menu nav, ul#ultraaddons-breadcrumb li.item-current' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'separator_color',
            [
                'label'     => __( 'Separator Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-breadcrumb-menu nav span, .ua-breadcrumb-menu .seperator' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'link_color_hover',
            [
                'label'     => __( 'Hover Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-breadcrumb-menu nav a:hover, .ultraaddons-breadcrumb li a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'title_typography',
                        'label' => 'Breadcrumb Typography',
                        'selector' => '{{WRAPPER}} .ua-breadcrumb-menu nav, .ultraaddons-breadcrumb',
                ]
        );
        
        $this->end_controls_section();
    }
    
    
    private function breadcrumb() {
        $settings = $this->get_settings_for_display();
        $this->home = ! empty( $settings['home_title'] ) ? $settings['home_title'] : esc_html( 'Home', 'ultraaddons' );
        $this->separator = ! empty( $settings['separator_sign'] ) ? $settings['separator_sign'] : '/';

    $separator = $this->separator;
    $wooBreadCumb = apply_filters( 'ultraaddons_wc_breadcrumb', true );
    
    /**
     * First We will try to load Woocommerce Default Breadcrumb
     * Because, WooComemrce has a Nice Breadcrumb System
     * 
     * WooCommerce Plugin -> Includes -> wc-template-functions.php
     */
    if( $wooBreadCumb && function_exists( 'woocommerce_breadcrumb' ) ){
        $args = array(
            'delimiter' => '<span>&nbsp;' . $separator . '&nbsp;</span>',
            'home'      => $this->home,
        );
        
        $args = apply_filters( 'ultraaddons_wc_breadcrumb_args', $args );
        woocommerce_breadcrumb( $args );
        return true;
    }
    
    // Check if is front/home page, return
    if ( is_front_page() ) {
      return;
    }

    // Define
    global $post;
    $custom_taxonomy  = ''; // If you have custom taxonomy place it here

    $defaults = array(
        'seperator'   =>  $separator,//'&#187;',
        'id'          =>  'ultraaddons-breadcrumb',
        'classes'     =>  'ultraaddons-breadcrumb',
        'home_title'  => $this->home,
    );
    
    $sep = '';
    if( ! empty( $separator )){
        $sep  = '<li class="seperator">'. esc_html( $defaults['seperator'] ) .'</li>';
    }
    

    // Start the breadcrumb with a link to your homepage
    echo '<ul id="'. esc_attr( $defaults['id'] ) .'" class="'. esc_attr( $defaults['classes'] ) .'">';

    // Creating home link
    echo '<li class="item"><a href="'. get_home_url() .'">'. esc_html( $defaults['home_title'] ) .'</a></li>' . $sep;

        if ( is_single() ) {

          // Get posts type
          $post_type = get_post_type();

          // If post type is not post
          if( $post_type != 'post' ) {

                $post_type_object   = get_post_type_object( $post_type );
                $post_type_link     = get_post_type_archive_link( $post_type );

                echo '<li class="item item-cat"><a href="'. $post_type_link .'">'. $post_type_object->labels->name .'</a></li>'. $sep;

          }

          // Get categories
          $category = get_the_category( $post->ID );

          // If category not empty
          if( !empty( $category ) ) {

                // Arrange category parent to child
                $category_values      = array_values( $category );
                $get_last_category    = end( $category_values );
                // $get_last_category    = $category[count($category) - 1];
                $get_parent_category  = rtrim( get_category_parents( $get_last_category->term_id, true, ',' ), ',' );
                $cat_parent           = explode( ',', $get_parent_category );

                // Store category in $display_category
                $display_category = '';
                foreach( $cat_parent as $p ) {
                    $display_category .=  '<li class="item item-cat">'. $p .'</li>' . $sep;
                }

          }

          // If it's a custom post type within a custom taxonomy
          $taxonomy_exists = taxonomy_exists( $custom_taxonomy );

          if( empty( $get_last_category ) && !empty( $custom_taxonomy ) && $taxonomy_exists ) {

                $taxonomy_terms = get_the_terms( $post->ID, $custom_taxonomy );
                $cat_id         = $taxonomy_terms[0]->term_id;
                $cat_link       = get_term_link($taxonomy_terms[0]->term_id, $custom_taxonomy);
                $cat_name       = $taxonomy_terms[0]->name;

          }

            // Check if the post is in a category
            if( !empty( $get_last_category ) ) {

                echo $display_category;
                echo '<li class="item item-current">'. get_the_title() .'</li>';

            } else if( !empty( $cat_id ) ) {

                echo '<li class="item item-cat"><a href="'. $cat_link .'">'. $cat_name .'</a></li>' . $sep;
                echo '<li class="item-current item">'. get_the_title() .'</li>';

            } else {

                echo '<li class="item-current item">'. get_the_title() .'</li>';

            }

    } else if( is_archive() ) {

            if( is_tax() ) {
                // Get posts type
                $post_type = get_post_type();

                // If post type is not post
                if( $post_type != 'post' ) {

                  $post_type_object   = get_post_type_object( $post_type );
                  $post_type_link     = get_post_type_archive_link( $post_type );

                  echo '<li class="item item-cat item-custom-post-type-' . $post_type . '"><a href="' . $post_type_link . '">' . $post_type_object->labels->name . '</a></li>' . $sep;

                }

                $custom_tax_name = get_queried_object()->name;
                echo '<li class="item item-current">'. $custom_tax_name .'</li>';

            } else if ( is_category() ) {

                $parent = get_queried_object()->category_parent;

                if ( $parent !== 0 ) {

                    $parent_category = get_category( $parent );
                    $category_link   = get_category_link( $parent );

                    echo '<li class="item"><a href="'. esc_url( $category_link ) .'">'. $parent_category->name .'</a></li>' . $sep;

                }

                echo '<li class="item item-current">'. single_cat_title( '', false ) .'</li>';

            } else if ( is_tag() ) {

            // Get tag information
            $term_id        = get_query_var('tag_id');
            $taxonomy       = 'post_tag';
            $args           = 'include=' . $term_id;
            $terms          = get_terms( $taxonomy, $args );
            $get_term_name  = $terms[0]->name;

            // Display the tag name
            echo '<li class="item-current item">'. $get_term_name .'</li>';

            } else if( is_day() ) {

                // Day archive

                // Year link
                echo '<li class="item-year item"><a href="'. get_year_link( get_the_time('Y') ) .'">'. get_the_time('Y') . ' Archives</a></li>' . $sep;

                // Month link
                echo '<li class="item-month item"><a href="'. get_month_link( get_the_time('Y'), get_the_time('m') ) .'">'. get_the_time('M') .' Archives</a></li>' . $sep;

                // Day display
                echo '<li class="item-current item">'. get_the_time('jS') .' '. get_the_time('M'). ' Archives</li>';

            } else if( is_month() ) {

                // Month archive

                // Year link
                echo '<li class="item-year item"><a href="'. get_year_link( get_the_time('Y') ) .'">'. get_the_time('Y') . ' Archives</a></li>' . $sep;

                // Month Display
                echo '<li class="item-month item-current item">'. get_the_time('M') .' Archives</li>';

            } else if ( is_year() ) {

                // Year Display
                echo '<li class="item-year item-current item">'. get_the_time('Y') .' Archives</li>';

          } else if ( is_author() ) {

                // Auhor archive

                // Get the author information
                global $author;
                $userdata = get_userdata( $author );

                // Display author name
                echo '<li class="item-current item">'. 'Author: '. $userdata->display_name . '</li>';

            } else {

                echo '<li class="item item-current">'. post_type_archive_title() .'</li>';

            }

    } else if ( is_page() ) {

        // Standard page
        if( $post->post_parent ) {

        // If child page, get parents
        $anc = get_post_ancestors( $post->ID );

        // Get parents in the right order
        $anc = array_reverse( $anc );

        // Parent page loop
        if ( !isset( $parents ) ) $parents = null;
        foreach ( $anc as $ancestor ) {

          $parents .= '<li class="item-parent item"><a href="'. get_permalink( $ancestor ) .'">'. get_the_title( $ancestor ) .'</a></li>' . $sep;

        }

        // Display parent pages
        echo $parents;

        // Current page
        echo '<li class="item-current item">'. get_the_title() .'</li>';

      } else {

        // Just display current page if not parents
        echo '<li class="item-current item">'. get_the_title() .'</li>';

      }

    } else if ( is_search() ) {

        // Search results page
        echo '<li class="item-current item">Search results for: '. get_search_query() .'</li>';

    } else if ( is_404() ) {

        // 404 page
        echo '<li class="item-current item">' . 'Error 404' . '</li>';

    }

    // End breadcrumb
    echo '</ul>';

}
    
    
}
