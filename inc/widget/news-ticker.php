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
        $this->ticker_content_controls();
        //For Design Section Style Tab
   
        //For Setting Control
        $this->settings_controls();
    }

    /**
     * Retrive setting for news tricker control
     * 
     * @author Saiful <codersaiful@gmail.com>
     *
     * @return void
     */
    protected function settings_controls(){
        $this->start_controls_section(
            'ticker_settings',
            [
                'label'     => esc_html__( 'Control Settings', 'ultraaddons' ),
            ]
        );

        $this->add_control(
			'direction',
			[
				'label' => __( 'Direction', 'ultraaddons' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'rtl',
				'frontend_available' => true,
				'options' => [
					'rtl'  => __( 'RTL', 'ultraaddons' ),
					'ltr' => __( 'LTR', 'ultraaddons' ),
				],
			]
		);
		$this->add_control(
			'play',
			[
				'label' => __( 'Play', 'ultraaddons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ultraaddons' ),
				'label_off' => __( 'No', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'frontend_available' => true,
			]
		);	
		$this->add_control(
			'stopOnHover',
			[
				'label' => __( 'Stop on Hover', 'ultraaddons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ultraaddons' ),
				'label_off' => __( 'No', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

       $this->add_control(
			'themeColor', [
				'label' => __( 'Theme Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'default'=>'#333',
				'frontend_available' => true,
			]
        );
        $this->end_controls_section();
    }
	protected function ticker_content_controls() {
		
        $this->start_controls_section(
		
            '_ua_news_ticker_content_tab',
            [
                'label'     => esc_html__( 'Content', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
		$this->add_control(
			'ticker_label', [
				'label' => __( 'Label', 'ultraaddons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'News' , 'ultraaddons' ),
				'label_block' => true,
			]
		);
		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'news_title', [
				'label' => __( 'Title', 'ultraaddons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'News Title' , 'ultraaddons' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'ticker_list',
			[
				'label' => __( 'News Ticker List', 'ultraaddons' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'news_title' => __( 'Lorem Ipsum Doler #1', 'ultraaddons' ),
					],
					[
						'news_title' => __( 'Lorem Ipsum Doler #2', 'ultraaddons' ),
					],
				],
				'title_field' => '{{{ news_title }}}',
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
	<div class="ua-news-ticker-wrap">
	  <div class="bn-label"><?php echo $settings['ticker_label']; ?></div>
	  <div class="bn-news">
		<?php
			if ( $settings['ticker_list'] ) {
			echo '<ul>';
			foreach (  $settings['ticker_list'] as $item ) {
				echo '<li class="elementor-repeater-item-' . $item['_id'] . '"><a href="#">'.$item['news_title'].'</a></li>';
			}
			echo '</ul>';
		}
		?>
	  </div>
	  <div class="bn-controls">
		<button><span class="bn-arrow bn-prev"></span></button>
		<button><span class="bn-action"></span></button>
		<button><span class="bn-arrow bn-next"></span></button>
	  </div>
	</div>
<?php }
}

add_action( 'wp_footer', function() {
	if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
		return;
	}
	?>
	<script>
	jQuery(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/ultraaddons-news-ticker.default', function($scope,view ) {
			if ( $scope.find( '.ua-element-news-ticker' ) ){
					var $data =  jQuery('[data-settings]');
						if ($data.length) {
						$data.each(function(index, el){
						var $Options = jQuery(this).data('settings'); 	
						  
						console.log($Options);
						jQuery('.ua-news-ticker-wrap').breakingNews({
							effect: 'typography'
						});
					});
				}
				
			}
		} );
	});
	</script>
	<?php
} );