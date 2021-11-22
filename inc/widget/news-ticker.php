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
        $this->ticker_settings_controls();
		
		//For Style Control
        $this->ticker_style_controls();
		//For Btn Control
        $this->ticker_btn_style_controls();
    }

    /**
     * Retrive setting for news tricker control
     * 
     * @author Saiful <codersaiful@gmail.com>
     *
     * @return void
     */
    protected function ticker_settings_controls(){
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
			'delayTimer',
			[
				'label' => __( 'Delay Timer', 'ultraaddons' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 2000,
				'max' => 6000,
				'step' => 100,
				'default' => 4000,
				'frontend_available' => true,
			]
		);
		$this->add_control(
			'scrollSpeed',
			[
				'label' => __( 'Scroll Speed', 'ultraaddons' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 2,
				'max' => 20,
				'step' => 2,
				'default' => 2,
				'frontend_available' => true,
			]
		);
		$this->add_control(
			'zIndex',
			[
				'label' => __( 'ZIndex', 'ultraaddons' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 99999,
				'max' => 999999,
				'step' => 1000,
				'default' => 99999,
				'frontend_available' => true,
			]
		);
		$this->add_control(
			'effect',
			[
				'label' => __( 'Effects', 'ultraaddons' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'scroll',
				'frontend_available' => true,
				'options' => [
					'scroll'  => __( 'Scroll', 'ultraaddons' ),
					'fade'  => __( 'Fade', 'ultraaddons' ),
					'slide-down' => __( 'Slide Down', 'ultraaddons' ),
					'slide-up' => __( 'Slide Up', 'ultraaddons' ),
					'slide-right' => __( 'Slide Right', 'ultraaddons' ),
					'slide-left' => __( 'Slide Left', 'ultraaddons' ),
					'typography' => __( 'Typography', 'ultraaddons' ),
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
			'position',
			[
				'label' => __( 'Position', 'ultraaddons' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'auto',
				'frontend_available' => true,
				'options' => [
					'auto'  => __( 'Default', 'ultraaddons' ),
					'fixed-top' => __( 'Top', 'ultraaddons' ),
					'fixed-bottom' => __( 'Bottom', 'ultraaddons' ),
				],
			]
		);
		$this->add_control(
			'show_controls',
			[
				'label' => __( 'Action Button', 'ultraaddons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ultraaddons' ),
				'label_off' => __( 'No', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

        $this->end_controls_section();
    }

	/**
	 * Here should comment actually\
	 * 
	 * It's actually content control part
	 */
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
				'default' => __( 'Lorem Ipsum simply dummy text of the printing and typesetting industry' , 'ultraaddons' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'news_link',
			[
				'label' => __( 'Link', 'ultraaddons' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'ultraaddons' ),
				'show_external' => true,
				'default' => [
					'url' => '#',
					'is_external' => true,
					'nofollow' => true,
				],
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
						'news_title' => __( 'Lorem Ipsum simply dummy text of the printing and typesetting industry', 'ultraaddons' ),
					],
					[
						'news_title' => __( 'Lorem Ipsum simply dummy text of the printing and typesetting industry', 'ultraaddons' ),
					],
				],
				'title_field' => '{{{ news_title }}}',
			]
		);

		
	$this->end_controls_section();
	}
	protected function ticker_style_controls() {
		
        $this->start_controls_section(
            '_ua_news_ticker_content_style',
            [
                'label'     => esc_html__( 'Content Style', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_control(
			'_ua_ticker_text_color', [
				'label' => __( 'Text Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'default'=>'#333',
				'selectors' => [
						'{{WRAPPER}} .ua-news-ticker-wrap ul li a' => 'color: {{VALUE}};',
				],
				'separator'=> 'after'
			]
        ); 
		$this->add_control(
			'_ua_ticker_label_bg', [
				'label' => __( 'Label Background', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'default'=>'#333',
				'selectors' => [
						'{{WRAPPER}} .bn-label' => 'background-color: {{VALUE}};',
				],
			]
        );
		$this->add_control(
			'_ua_ticker_label_color', [
				'label' => __( 'Label Text Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'default'=>'#fff',
				'selectors' => [
						'{{WRAPPER}} .bn-label' => 'color: {{VALUE}};',
				],
				'separator'=> 'after'
			]
        ); 
		$this->add_control(
			'_ua_ticker_border_color', [
				'label' => __( 'Border Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'default'=>'#333',
				'selectors' => [
						'{{WRAPPER}} .ua-news-ticker-wrap' => 'border: 1px solid {{VALUE}};',
				],
			]
        );
		
		$this->add_control(
			'_ua_ticker_bg_color', [
				'label' => __( 'Background Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'default'=>'#fff',
				'selectors' => [
						'{{WRAPPER}} .ua-news-ticker-wrap' => 'background-color: {{VALUE}};',
				],
				'separator'=> 'after'
			]
        );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'ticker_label_typography',
					'label' => 'Label Typography',
					'selector' => '{{WRAPPER}} .bn-label',

			]
        );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'ticker_typography',
					'label' => 'Ticker Typography',
					'selector' => '{{WRAPPER}} .bn-news ul li',

			]
        );
		
		$this->end_controls_section();
	}
	protected function ticker_btn_style_controls() {
		
        $this->start_controls_section(
            '_ua_ticker_btn_style',
            [
                'label'     => esc_html__( 'Action Button Style', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_control(
			'_ua_ticker_btn_bg', [
				'label' => __( 'Control Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'default'=>'#333',
				'selectors' => [
						'{{WRAPPER}} .bn-arrow:after, .bn-pause:after, .bn-pause:before' => 'border-color: {{VALUE}};',
						'{{WRAPPER}} .bn-pause:after, .bn-pause:before' => 'background-color: {{VALUE}};',
				],
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
		
			if ( isset( $settings['ticker_list'] ) ) {
				echo '<ul>';
				foreach (  $settings['ticker_list'] as $item ) {
					$target 	= $item['news_link']['is_external'] ? ' target="_blank"' : '';
					$nofollow 	= $item['news_link']['nofollow'] ? ' rel="nofollow"' : '';
					$url		= $item['news_link']['url'];
					echo '<li class="news-tricker-element elementor-repeater-item-' . $item['_id'] . '">
					<a href="' . $url. '"' . $target . $nofollow . '>'.$item['news_title'].'</a>
					</li>';
				}
				echo '</ul>';
			}
		?>
	  </div>
	  <?php if('yes'=== $settings['show_controls']): ?>
	  <div class="bn-controls">
		<button><span class="bn-arrow bn-prev"></span></button>
		<button><span class="bn-action"></span></button>
		<button><span class="bn-arrow bn-next"></span></button>
	  </div>
	  <?php endif;?>
	</div>
<?php }
}