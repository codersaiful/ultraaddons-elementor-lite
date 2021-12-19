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
        $dependency     =  [];//['jquery'];
        $version        = ULTRA_ADDONS_VERSION;
        $in_footer  	= true;

        wp_register_script( $name, $js_file_url, $dependency, $version, $in_footer );
        wp_enqueue_script( $name );

        $ml_name        = 'frontend-moving-letters';
        $ml_js_file_url    = ULTRA_ADDONS_ASSETS . 'js/frontend-moving-letters.js';
        $ml_dependency     =  [];//['jquery'];
        $ml_version        = ULTRA_ADDONS_VERSION;
        $ml_in_footer  	= true;

        wp_register_script( $ml_name , $ml_js_file_url, $ml_dependency, $ml_version, $ml_in_footer );
        wp_enqueue_script( $ml_name );
		
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
            return [ 'anime' ,'frontend-moving-letters' ];
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
        return [ 'ultraaddons', 'ua','animation', 'animate', 'dynamic', 'text', 'heading', 'anime', 'moving', 'letters' ];
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
					'8' => __( 'Style-8', 'ultraaddons' ),
					'9' => __( 'Style-9', 'ultraaddons' ),
					'10' => __( 'Style-10', 'ultraaddons' ),
					'11' => __( 'Style-11', 'ultraaddons' ),
					'12' => __( 'Style-12', 'ultraaddons' ),
					'13' => __( 'Style-13', 'ultraaddons' ),
					'14' => __( 'Style-14', 'ultraaddons' ),
					'15' => __( 'Style-15', 'ultraaddons' ),
					'16' => __( 'Style-16', 'ultraaddons' ),
				],
			]
		);
        $this->add_control(
			'anime_title', [
				'label' => __( 'Text', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Ultra Addons' , 'ultraaddons' ),
				'label_block' => true,
                'condition' =>['anim_type!'=>'8']
			]
		);
        $this->add_control(
			'anime_title_2', [
				'label' => __( 'Text 1', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'UA' , 'ultraaddons' ),
                'condition' =>['anim_type'=>'8']
			]
		);
        $this->add_control(
			'anime_title_3', [
				'label' => __( 'Text 2', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( '!' , 'ultraaddons' ),
                'condition' =>['anim_type'=>'8']
			]
		);
        $this->add_control(
			'anime_title_4', [
				'label' => __( 'Text 1', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Ultra' , 'ultraaddons' ),
                'condition' =>['anim_type'=>'15']
			]
		);
        $this->add_control(
			'anime_title_5', [
				'label' => __( 'Text 2', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Addons' , 'ultraaddons' ),
                'condition' =>['anim_type'=>'15']
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
                    'default' => 'center',
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
        $this->add_control(
			'_line_height',
			[
				'label' => __( 'Line Height', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .line' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->add_control(
			'_line_color', [
				'label' => __( 'Line Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'default'=>'#333',
				'selectors' => [
						'{{WRAPPER}} .line' => 'background-color: {{VALUE}};',
				],
				'separator'=> 'after'
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
        // var_dump($anim_type);
        ?>
<<<<<<< HEAD
    
=======
        <?php
            if( Plugin::$instance->editor->is_edit_mode()){
                ?>
                <script>
                var textWrapper = document.querySelector('.ml1 .letters');
                    textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");

                    anime.timeline({loop: true})
                    .add({
                        targets: '.ml1 .letter',
                        scale: [0.3,1],
                        opacity: [0,1],
                        translateZ: 0,
                        easing: "easeOutExpo",
                        duration: 600,
                        delay: (el, i) => 70 * (i+1)
                    }).add({
                        targets: '.ml1 .line',
                        scaleX: [0,1],
                        opacity: [0.5,1],
                        easing: "easeOutExpo",
                        duration: 700,
                        offset: '-=875',
                        delay: (el, i, l) => 80 * (l - i)
                    }).add({
                        targets: '.ml1',
                        opacity: 0,
                        duration: 1000,
                        easing: "easeOutExpo",
                        delay: 1000
                    });
                </script>
                <?php
            }
        ?>
>>>>>>> 5ca6f6579808570ecf8e6aa3f01c0bd582ed7798
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

        if($anim_type==8){
        echo '<h1 class="ua-ml ml8">
                <span class="letters-container">
                <span class="letters letters-left">' . $settings["anime_title_2"] . '</span>
                <span class="letters bang">'. $settings["anime_title_3"] . '</span>
                </span>
                <span class="circle circle-white"></span>
                <span class="circle circle-dark"></span>
                <span class="circle circle-container"><span class="circle circle-dark-dashed"></span></span>
            </h1>';
         }

        if($anim_type==9){
        echo '<h1 class="ua-ml ml9">
                <span class="text-wrapper">
                <span class="letters">' . $settings["anime_title"] . '</span>
                </span>
            </h1>';
        }

        if($anim_type==10){
        echo '<h1 class="ua-ml ml10">
                <span class="text-wrapper">
                <span class="letters">' . $settings["anime_title"] . '</span>
                </span>
            </h1>';
        }

        if($anim_type==11){
        echo '<h1 class="ua-ml ml11">
                <span class="text-wrapper">
                <span class="line line1"></span>
                <span class="letters">' . $settings["anime_title"] . '</span>
                </span>
            </h1>';
         }

        if($anim_type==12){
        echo '<h1 class="ua-ml ml12">' . $settings["anime_title"] . '</h1>';
        }

        if($anim_type==13){
        echo '<h1 class="ua-ml ml13">' . $settings["anime_title"] . '</h1>';
        }

        if($anim_type==14){
        echo '<h1 class="ua-ml ml14">
                <span class="text-wrapper">
                <span class="letters">' . $settings["anime_title"] . '</span>
                <span class="line"></span>
                </span>
            </h1>';
         }

        if($anim_type==15){
        echo '<h1 class="ml15">
                <span class="word">' . $settings["anime_title_4"] . '</span>
                <span class="word">' . $settings["anime_title_5"] . '</span>
            </h1>';
         }

        if($anim_type==16){
            echo '<h1 class="ml16">' . $settings["anime_title"] . '</h1>';
        }

    }
    
    
}
