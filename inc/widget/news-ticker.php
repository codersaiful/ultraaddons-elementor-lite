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
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * News Ticker Widget
 * Create excellent step by step visual diagram and instructions using this smart widget.
 * 
 * @since 1.1.0.7
 * @package UltraAddons
 * @author Saiful islam <codersaiful@gmail.com>
 * @author Rafiul <bmrafiul.alam@gmail.com>
 */
class News_Ticker extends Base{
	
	  public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);

        //Naming of Args for owlCarousel
        $name           = 'NewsTicker';
        $js_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/js/breaking-news-ticker.min.js';
        $dependency     =  ['jquery'];//['jquery'];
        $version        = ULTRA_ADDONS_VERSION;
        $in_footer  = true;

        wp_register_script( $name, $js_file_url, $dependency, $version, $in_footer );
        wp_enqueue_script( $name );


        //CSS file for Slider Script Owl Carousel Slider
        wp_register_style('NewsTicker', ULTRA_ADDONS_ASSETS . 'vendor/css/breaking-news-ticker.css' );
        wp_enqueue_style('NewsTicker' );

    }
	/**
     * By Saiful Islam
     * depend css for this widget
     * 
     * @return Array
     */
    public function get_style_depends() {
        return ['NewsTicker'];
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
		return [ 'jquery','NewsTicker' ];
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
        return [ 'ultraaddons', 'ua', 'step flow', 'step', 'flow' ];
    }
	
	 /**
     * Register widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {
        //For Content Section
        //$this->content_controls();
        //For Design Section Style Tab
   
    }
	protected function content_controls() {
		
        $this->start_controls_section(
		
            '_ua_news_ticker_content_tab',
            [
                'label'     => esc_html__( 'Content', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
		
		
	$this->end_controls_section();
	}
	protected function news_ticker_style_controls() {
		
        $this->start_controls_section(
            '_ua_news_ticker_content_style',
            [
                'label'     => esc_html__( 'Content Style', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		
		
		$this->end_controls_section();
	}
	
	
   protected function render() {
		$settings 	= $this->get_settings_for_display();
	?>
	<div class="ua-news-ticker">
	  <div class="bn-label">Ticker Label</div>
	  <div class="bn-news">
		<ul>
		  <li><a href="#">1.1. Breaking NEWS 1</a></li>
		  <li><a href="#">1.2. Breaking NEWS 2</a></li>
		  <li><a href="#">1.3. Breaking NEWS 3</a></li>
		  <li><a href="#">1.4. Breaking NEWS 4</a></li>
		  <li><a href="#">1.5. Breaking NEWS 5</a></li>
		  <li><a href="#">1.6. Breaking NEWS 6</a></li>
		  <li><a href="#">1.7. Breaking NEWS 7</a></li>
		</ul>
	  </div>
	  <div class="bn-controls">
		<button><span class="bn-arrow bn-prev"></span></button>
		<button><span class="bn-action"></span></button>
		<button><span class="bn-arrow bn-next"></span></button>
	  </div>
	</div>
<?php }
}