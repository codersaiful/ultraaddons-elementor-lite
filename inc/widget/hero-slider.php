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

class Hero_Slider extends Base{

    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);

        //Naming of Args for swiper
        $name           = 'swiper';
        $js_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/swiper/js/swiper.min.js';
        $dependency     =  ['jquery'];//['jquery'];
        $version        = ULTRA_ADDONS_VERSION;
        $in_footer  = true;

        wp_register_script( $name, $js_file_url, $dependency, $version, $in_footer );
        wp_enqueue_script( $name );


        //CSS file swiper
        wp_register_style('swiper', ULTRA_ADDONS_ASSETS . 'vendor/swiper/css/swiper.min.css' );
        wp_enqueue_style('swiper' );

    }
	/**
     * By Saiful Islam
     * depend css for this widget
     * 
     * @return Array
     */
    public function get_style_depends() {
        return ['swiper'];
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
		return [ 'jquery','swiper' ];
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
        return [ 'ultraaddons','ua', 'slider', 'hero', 'carousel' ];
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
        $repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'list_title', [
				'label' => esc_html__( 'Title', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'List Title' , 'ultraaddons' ),
				'label_block' => true,
			]
		);
        $repeater->add_control(
			'list_content', [
				'label' => esc_html__( 'Content', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Lorem ipsum dolor sit amet consectetur adipisicing elit.' , 'ultraaddons' ),
				'label_block' => true,
			]
		);
        $repeater->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose Image', 'ultraaddons' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					
				],
			]
		);
        $repeater->add_control(
			'list_btn', [
				'label' => esc_html__( 'Button Text', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Read More' , 'ultraaddons' ),
				'label_block' => true,
			]
		);
        $this->add_control(
			'list',
			[
				'label' => esc_html__( 'Repeater List', 'ultraaddons' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'list_title' => esc_html__( 'iPhone', 'ultraaddons' ),
					],
					[
						'list_title' => esc_html__( 'Mackbook', 'ultraaddons' ),
					],

				],
				'title_field' => '{{{ list_title }}}',
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
        ?>
   <!-- Slider main container -->
   <div class="ua-hero">
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper">
            <!-- Slides -->
            <?php 
            if ( $settings['list'] ) {
                $count=0;
                foreach (  $settings['list'] as $item ) {
                    $count = $count+1;


            ?>
            <div class="swiper-slide slide-<?php echo $count;?>">
                <div class="ua-image" style="background: url(<?php echo $item['image']['url'] ;?>)">
                    <div class="ua-slider-container">
                        <h4 class="ua-slider-sub-title">
                            <?php echo $item['list_title'];?>
                        </h4>
                        <div class="animated-area">
                                <h1 class="ua-slider-title"><?php echo $item['list_content'];?></h1>
                                <a href="#" class="ua-slider-buttton"><?php echo $item['list_btn'];?></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php }
        }?>
        </div>
        <!-- If we need navigation buttons -->
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>
        <?php
        
    }
    
    
}
