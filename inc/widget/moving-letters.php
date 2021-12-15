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
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Plugin;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Moving Letters
 * Create excellentmoving letters using this smart widget.
 * Credit: https://github.com/juliangarnier/anime
 * @since 1.1.0.8
 * @package UltraAddons
 * @author Saiful islam <codersaiful@gmail.com>
 * @author B M Rafiul <bmrafiul.alam@gmail.com>
 */

class Moving_Letters extends Base{
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

        //Naming of Args for anime
        $name           = 'anime';
        $js_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/anime/lib/anime.min.js';
        $dependency     =  ['jquery'];//['jquery'];
        $version        = ULTRA_ADDONS_VERSION;
        $in_footer  	= true;

        wp_register_script( $name, $js_file_url, $dependency, $version, $in_footer );
        wp_enqueue_script( $name );
		
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
            return [ 'jquery','anime' ];
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
        return [ 'ultraaddons', 'anime', 'moving', 'letters' ];
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
         //Style Controls
        $this->style_controls();
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
        $this->add_control(
			'anim_type',
			[
				'label' => __( 'Animation Type', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'default' => '1',
				'frontend_available' => true,
				'options' => [
					'1' => __( 'Style-1', 'ultraaddons' ),
					'2' => __( 'Style-2', 'ultraaddons' ),
					'3' => __( 'Style-3', 'ultraaddons' ),
					'4' => __( 'Style-4', 'ultraaddons' ),
					'5' => __( 'Style-5', 'ultraaddons' ),
					'6' => __( 'Style-6', 'ultraaddons' ),
					'7' => __( 'Style-7', 'ultraaddons' ),
				],
			]
		);
        $this->add_control(
			'anime_title', [
				'label' => __( 'Text', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Ultra Addons' , 'ultraaddons' ),
				'label_block' => true,
			]
		);
        
        $this->end_controls_section();
    }

    /**
     * General Section for Content Controls
     * 
     * @since 1.0.0.9
     */
    protected function style_controls() {
        $this->start_controls_section(
            'general_style',
            [
                'label'     => esc_html__( 'Style', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'text_alignment',
                [
                    'label'         => esc_html__( 'Alignment', 'ultraaddons' ),
                    'type'          => Controls_Manager::CHOOSE,
                    'options' => [
                            'left' => [
                                    'title' => __( 'Left', 'ultraaddons' ),
                                    'icon' => 'fa fa-align-left',
                            ],
                            'center' => [
                                    'title' => __( 'Center', 'ultraaddons' ),
                                    'icon' => 'fa fa-align-center',
                            ],
                            'right' => [
                                    'title' => __( 'Right', 'ultraaddons' ),
                                    'icon' => 'fa fa-align-right',
                            ],
                    ],
                    'default' => 'left',
                    'toggle' => true,
                    'selectors' => [
                        '{{WRAPPER}} .ua-ml' => 'text-align: {{VALUE}}',
                    ],
                ]
        );        
        $this->add_control(
			'_text_color', [
				'label' => __( 'Text Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'default'=>'#333',
				'selectors' => [
						'{{WRAPPER}} .ua-ml' => 'color: {{VALUE}};',
				],
				'separator'=> 'after'
			]
        ); 
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'text_typography',
					'label' => 'Typography',
					'selector' => '{{WRAPPER}} .ua-ml',

			]
        );
        $this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow',
				'label' => __( 'Text Shadow', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} .ua-ml',
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
        $settings = $this->get_settings_for_display();
        $anim_type = $settings['anim_type'];
        ?>
        <?php
            if( Plugin::$instance->editor->is_edit_mode()){
                echo '<script>
                anime({
                    targets: ".ua-ml",
                  });
                </script>';
            }
        ?>
        <?php
        if($anim_type==1){
        echo' <h1 class="ua-ml ml1">
                <span class="text-wrapper">
                    <span class="line line1"></span>
                    <span class="letters"> ' . $settings["anime_title"] . ' </span>
                    <span class="line line2"></span>
                </span>
            </h1>';
        }
        if($anim_type==2){
            echo '<h1 class="ua-ml ml2">' . $settings["anime_title"] . '</h1>';
        }
        if($anim_type==3){
            echo '<h1 class="ua-ml ml3">' . $settings["anime_title"] . '</h1>';
         }
        if($anim_type==4){
        echo '<h1 class="ua-ml ml4">
            <span class="letters letters-1">Ready</span>
            <span class="letters letters-2">Set</span>
            <span class="letters letters-3">Go!</span>
          </h1>';
         }
        if($anim_type==5){
        echo '<h1 class="ua-ml ml5">
            <span class="text-wrapper">
              <span class="line line1"></span>
              <span class="letters letters-left">Signal</span>
              <span class="letters ampersand">&amp;</span>
              <span class="letters letters-right">Noise</span>
              <span class="line line2"></span>
            </span>
          </h1>
          ';
         }
        if($anim_type==6){
        echo '<h1 class="ua-ml ml6">
            <span class="text-wrapper">
              <span class="letters">' . $settings["anime_title"] . '</span>
            </span>
          </h1>';
         }
        if($anim_type==7){
        echo '<h1 class="ua-ml ml7">
                <span class="text-wrapper">
                <span class="letters">' . $settings["anime_title"] . '</span>
                </span>
            </h1>';
         }

        
    }
    
    
}
