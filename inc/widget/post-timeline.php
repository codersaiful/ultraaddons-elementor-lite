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

class Post_Timeline extends Base{
    
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
        return [ 'ultraaddons', 'post', 'timeline', 'time','line' ];
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
        $this->style_controls();

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
        $settings  = $this->get_settings_for_display();
        ?>
        <div id="timeline">
        <ul>
        <?php
        $args = array(  
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 2,
            'order' => 'ASC', 
        );
         $loop = new \WP_Query( $args ); 
            while ( $loop->have_posts() ) : $loop->the_post();
            if ( has_post_thumbnail() ):
            ?>
             <li>
                <a href="javascript:;" style="animation-delay:0.5s;">
                    <div class="pic">
                        <?php echo get_the_post_thumbnail( $loop->ID, 'medium' ); ?>
                    </div>
                    <div class="txt">
                        <p><?php echo get_the_date(); ?></p>
                        <h3><?php the_title(); ?></h3>
                        <p>
                            <?php echo $this->excerpt(10);?>
                        </p>
                    </div>
                </a>
                <div class="line" style="animation-delay:0.5s;">
                </div>
            </li>
            <?php 
            endif;
              endwhile;
             wp_reset_postdata();
            ?>
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
            'general_content',
            [
                'label'     => esc_html__( 'General', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->end_controls_section();
    }

    protected function style_controls() {
        $this->start_controls_section(
            'style_tab',
            [
                'label'     => esc_html__( 'Style', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function excerpt($limit) {
        $content = wp_strip_all_tags(get_the_content() , true );
        echo wp_trim_words($content, $limit);
    }
    
    
}
