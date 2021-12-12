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

class Product_Flip_Carousel extends Base{

    /**
     * mainly to call specific depends
     * we have called this __construct() method
     * 
     * @param Array $data
     * @param Array $args
     * 
     * @by Saiful Islam
     */
    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);

        //Naming of Args for flipCarousel
        $name           = 'flipCarousel';
        $js_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/js/flip-carousel.js';
        $dependency     =  ['jquery'];//['jquery'];
        $version        = ULTRA_ADDONS_VERSION;
        $in_footer  = true;

        wp_register_script( $name, $js_file_url, $dependency, $version, $in_footer );
        wp_enqueue_script( $name );

        //CSS file for Slider Script Owl Carousel Slider
        wp_register_style('flipCarousel', ULTRA_ADDONS_ASSETS . 'vendor/css/flip-carousel.css' );
        wp_enqueue_style('flip-carousel' );

    }

    /**
     * By Saiful Islam
     * depend css for this widget
     * 
     * @return Array
     */
    public function get_style_depends() {
        return ['flipCarousel'];
    }

    /**
     * Retrieve the list of scripts the skill bar widget depended on.
     *
     * Used to set scripts dependencies required to run the widget.
     *
     * @since 1.0.9.2
     * @access public
     *
     * @return array Widget scripts dependencies.
     * @by Saiful
     */
    public function get_script_depends() {
            return [ 'jquery','flipCarousel' ];
    }
    
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
        return [ 'ultraaddons', 'flip', 'product', 'carousel' ];
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
        ?>
        <div class="p-flip-item" title="item1"><h1>Item 1</h1><span>$123</span><div class="fi-graph-pie"></div></div>
        <div class="p-flip-item" title="item2"><h1>Item 2</h1><span>$980</span><div class="fi-asl"></div></div>
        <div class="p-flip-item" title="item3"><h1>Item 3</h1><span>$67</span><div class="fi-asterisk"></div></div>
        <div class="p-flip-item" title="item4"><h1>Item 4</h1><span>$13</span><div class="fi-blind"></div></div>
        <div class="p-flip-item" title="item5"><h1>Item 5</h1><span>$134</span><div class="fi-burst-new"></div></div>
        <div class="p-flip-item" title="item6"><h1>Item 6</h1><span>$456</span><div class="fi-calendar"></div></div>
        <div class="p-flip-item" title="item7"><h1>Item 7</h1><span>$99</span><div class="fi-comment-quotes"></div></div>
        <div class="p-flip-item" title="item8"><h1>Item 8</h1><span>$92</span><div class="fi-die-five"></div></div>
        <div class="p-flip-item" title="item9"><h1>Item 9</h1><span>$3</span><div class="fi-dislike"></div></div>
        <div class="p-flip-item" title="item10"><h1>Item 10</h1><span>$12</span><div class="fi-foot"></div></div>
        <div class="p-flip-item" title="item11"><h1>Item 11</h1><span>(last)</span><div class="fi-trophy"></div></div>

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
    
    
    
    
}
