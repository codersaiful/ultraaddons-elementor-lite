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
use Elementor\Repeater;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Product_Carousel extends Base{
    
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

        //Naming of Args for owlCarousel
        $name           = 'swiffySlider';
        $js_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/js/swiffy-slider.min.js';
        $dependency     =  ['jquery'];//['jquery'];
        $version        = ULTRA_ADDONS_VERSION;
        $in_footer  = true;

        wp_register_script( $name, $js_file_url, $dependency, $version, $in_footer );
        wp_enqueue_script( $name );


        //CSS file for Slider Script Owl Carousel Slider
        wp_register_style('swiffySlider', ULTRA_ADDONS_ASSETS . 'vendor/css/swiffy-slider.min.css' );
        wp_enqueue_style('swiffySlider' );

    }

    /**
     * By B M Rafiul Alam
     * depend css for this widget
     * 
     * @return Array
     */
    public function get_style_depends() {
        return ['swiffySlider'];
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
            return [ 'jquery','swiffySlider' ];
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
        return [ 'ultraaddons', 'product', 'slider', 'carousel' ];
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
    
    /**
     * Alignment Section for Style Tab
     * 
     * @since 1.0.0.9
     */
    protected function style_general_controls() {
        $this->start_controls_section(
            'style_general',
            [
                'label'     => esc_html__( 'General', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
       
    $this->end_controls_tab();
       
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

    $settings = $this->get_settings_for_display();
    ?>

<div class="swiffy-slider slider-item-show3 slider-item-reveal slider-nav-dark slider-nav-outside-expand">
    <ul class="slider-container py-4" id="slider2">
        <li>
            <div class="ua-card shadow ua-h-100">
                <div class="ratio ratio-1x1">
                    <img src="https://www.cssscript.com/demo/versatile-carousel-swiffy-slider/docs/assets/img/shoes/shoe1.webp" class="card-img-top" loading="lazy" alt="...">
                </div>
                <div class="ua-card-body ua-d-flex ua-flex-column ua-flex-md-row">
                    <div class="ua-flex-grow-1">
                        <strong>LeBron 18</strong>
                        <p class="ua-card-text">Basketball Shoes</p>
                    </div>
                    <div class="ua-px-md-2">$200</div>
                </div>
            </div>
        </li>
    </ul>
    <button type="button" class="slider-nav" aria-label="Go to previous"></button>
    <button type="button" class="slider-nav slider-nav-next" aria-label="Go to next"></button>
</div>
<?php
        
    }
}