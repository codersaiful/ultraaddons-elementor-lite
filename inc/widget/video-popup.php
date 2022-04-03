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
use Elementor\Icons_Manager;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
     * Modal Video
    
    * Accessible for keyboard navigation and screen readers.
    * Rich options for youtube API and Vimeo API

    * @source: https://github.com/appleple/modal-video
    * @since 1.1.0.12
    * @package UltraAddons
    * @author Saiful islam <codersaiful@gmail.com>
    * @author B M Rafiul <bmrafiul.alam@gmail.com>
 */

class Video_Popup extends Base{

    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);

          //Naming of Args for Modal-video
          $name           = 'modal-video';
          $js_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/modal-video/js/modal-video.min.js';
          $dependency     =  ['jquery'];//['jquery'];
          $version        = ULTRA_ADDONS_VERSION;
          $in_footer      = true;
  
          wp_register_script( $name, $js_file_url, $dependency, $version, $in_footer );
          wp_enqueue_script( $name );

          $name           = 'front-end-modal-video';
          $js_file_url    = ULTRA_ADDONS_ASSETS . 'js/frontend-video-popup.js';
          $dependency     =  ['jquery'];//['jquery'];
          $version        = ULTRA_ADDONS_VERSION;
          $in_footer      = true;
  
          wp_register_script( $name, $js_file_url, $dependency, $version, $in_footer );
          wp_enqueue_script( $name );
  

         //CSS file for dependency
		$name           = 'modal-video';
        $css_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/modal-video/css/modal-video.min.css';
        $dependency     =  [];//kaj ta ses hoyni. pore abar try korte hobe.
        $version        = ULTRA_ADDONS_VERSION;
        $media  	= 'all';
        wp_register_style('modal-video', $css_file_url,$dependency,$version, $media ); //modal-video
        wp_enqueue_style('modal-video' );
    }
	

    /**
     * Retrieve the list of scripts the Video Popup widget depended on.
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
		return [ 'jquery', 'modal-video' ];
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
        return [ 'ultraaddons','ua', 'modal', 'video', 'popup','play' ];
    }
    
    
    /**
     * Register oEmbed widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls() {
        //For General Section
        $this->content_general_controls();
        $this->button_style_controls();
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
        'video_type',
        [
            'label' => __( 'Video Type', 'ultraaddons' ),
            'type' => Controls_Manager::SELECT,
            'default' => 'youtube',
            'frontend_available' => true,
            'options' => [
                'youtube'  => __( 'Youtube', 'ultraaddons' ),
                'vimeo' => __( 'Viemo', 'ultraaddons' ),
            ],
        ]
    );
    $this->add_control(
        'btn_text', [
                'label' => __( 'Button text', 'ultraaddons' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Play',
                'label_block' => true,
        ]
    );

     $this->add_control(
            'youtube_url', [
                    'label' => __( 'Youtube URL', 'ultraaddons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => 'https://www.youtube.com/watch?v=n_ea3devnlg',
                    'label_block' => true,
                    'condition'=>[
                        'video_type' =>'youtube'
                    ]
            ]
    );

  
    /**
     * Youtube Controls
     */
    $this->add_control(
        'controls',
        [
            'label' => __( 'Controls', 'ultraaddons' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Yes', 'ultraaddons' ),
            'label_off' => __( 'No', 'ultraaddons' ),
            'return_value' => '1',
            'default' => '1',
            'frontend_available' => true,
            'condition'=>[
                'video_type' =>'youtube'
            ]
        ]
    );
    $this->add_control(
        'autoplay',
        [
            'label' => __( 'Autoplay', 'ultraaddons' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Yes', 'ultraaddons' ),
            'label_off' => __( 'No', 'ultraaddons' ),
            'return_value' => '1',
            'default' => '1',
            'frontend_available' => true,
            'condition'=>[
                'video_type' =>'youtube'
            ]
        ]
    );	
    $this->add_control(
        'loop',
        [
            'label' => __( 'Loop', 'ultraaddons' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Yes', 'ultraaddons' ),
            'label_off' => __( 'No', 'ultraaddons' ),
            'return_value' => '1',
            'default' => '0',
            'frontend_available' => true,
            'condition'=>[
                'video_type' =>'youtube'
            ]
        ]
    );

     /**
     * Viemo Controls
     */
    $this->add_control(
        'vimeo_id', [
                'label' => __( 'Vimeo URL', 'ultraaddons' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'https://vimeo.com/691786534',
                'label_block' => true,
                'condition'=>[
                    'video_type' =>'vimeo'
                ]
        ]
    );
 /*    $this->add_control(
        'vcontrols',
        [
            'label' => __( 'Controls', 'ultraaddons' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Yes', 'ultraaddons' ),
            'label_off' => __( 'No', 'ultraaddons' ),
            'return_value' => '1',
            'default' => '1',
            'frontend_available' => true,
            'condition'=>[
                'video_type' =>'vimeo'
            ]
        ]
    ); */
    $this->add_control(
        'vautoplay',
        [
            'label' => __( 'Autoplay', 'ultraaddons' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Yes', 'ultraaddons' ),
            'label_off' => __( 'No', 'ultraaddons' ),
            'return_value' => '0',
            'default' => '0',
            'frontend_available' => true,
            'condition'=>[
                'video_type' =>'vimeo'
            ]
        ]
    );	
    $this->add_control(
        'vloop',
        [
            'label' => __( 'Loop', 'ultraaddons' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Yes', 'ultraaddons' ),
            'label_off' => __( 'No', 'ultraaddons' ),
            'return_value' => '1',
            'default' => '0',
            'frontend_available' => true,
            'condition'=>[
                'video_type' =>'vimeo'
            ]
        ]
    );
        $this->end_controls_section();
    }
    
        /**
         * Button Style
         */
        protected function button_style_controls() {
            $this->start_controls_section(
                'btn_style',
                [
                    'label'     => esc_html__( 'Style', 'ultraaddons' ),
                    'tab'       => Controls_Manager::TAB_STYLE,
                ]
            );
         
            $this->add_responsive_control(
                '_btn_alignment',
                [
                    'label' => esc_html__( 'Alignment', 'ultraaddons' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'start' => [
                            'title' => esc_html__( 'Left', 'ultraaddons' ),
                            'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', 'ultraaddons' ),
                            'icon' => 'eicon-text-align-center',
                        ],
                        'end' => [
                            'title' => esc_html__( 'Right', 'ultraaddons' ),
                            'icon' => 'eicon-text-align-right',
                        ],
                    ],
                    'default' => 'start',
                    'selectors' => [
                        '{{WRAPPER}} .ua-video-btn-wrap' => 'justify-content: {{VALUE}};',
                    ],
                ]
            );
            $this->add_control(
                'selected_icon',
                [
                    'label' => esc_html__( 'Icon', 'ultraaddons' ),
                    'type' => Controls_Manager::ICONS,
                    'fa4compatibility' => 'icon',
                    'skin' => 'inline',
                    'label_block' => true,
                ]
            );
            $this->add_control(
                'icon_size',
                [
                    'label' => esc_html__( 'Icon Size', 'ultraaddons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => '16',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .ua-video-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .ua-video-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'icon_space',
                [
                    'label' => esc_html__( 'Icon Space', 'ultraaddons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 5,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 5,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .ua-video-btn i' => 'margin-right: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .ua-video-btn svg' => 'margin-right: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->start_controls_tabs(
                'style_tabs'
            );
            //Normal Tab
            $this->start_controls_tab(
                'btn_normal_tab',
                [
                    'label' => esc_html__( 'Normal', 'ultraaddons' ),
                ]
            );
            $this->add_control(
                'btn_size',
                [
                    'label' => esc_html__( 'Button Size', 'ultraaddons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => '16',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .ua-video-btn' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_control(
                '_btn_bg_color', [
                    'label' => __( 'Button Background', 'ultraaddons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                            '{{WRAPPER}} .ua-video-btn' => 'background-color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                        'name' => 'box_border',
                        'label' => __( 'Button Border', 'elementor' ),
                        'fields_options' => [
                                'border' => [
                                        'default' => 'solid',
                                ],
                                'width' => [
                                        'default' => [
                                                'top' => '1',
                                                'right' => '1',
                                                'bottom' => '1',
                                                'left' => '1',
                                                'isLinked' => false,
                                        ],
                                ],
                                'color' => [
                                        'default' => '#DB0C0C',
                                ],
                        ],
                        'selector' => '{{WRAPPER}} .ua-video-btn',
                ]
        );
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'box_shadow',
                    'label' => esc_html__( 'Button Shadow', 'ultraaddons' ),
                    'selector' => '{{WRAPPER}} .ua-video-btn',
                ]
            );
            $this->add_control(
                '_btn_text_color', [
                    'label' => __( 'Button Text Color', 'ultraaddons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                            '{{WRAPPER}} .ua-video-btn' => 'color: {{VALUE}};',
                            '{{WRAPPER}} .ua-video-btn svg' => 'fill: {{VALUE}};',
                    ],
                ]
            );
           
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'btn_typography',
                        'label' => 'Button Typography',
                        'selector' => '{{WRAPPER}} .ua-video-btn',
    
                ]
            );
            $this->add_responsive_control(
                '_btn_padding',
                [
                    'label'       => esc_html__( 'Button Padding', 'ultraaddons' ),
                    'type'        => Controls_Manager::DIMENSIONS,
                    'size_units'  => [ '%', 'px' ],
                    'placeholder' => [
                        'top'    => '',
                        'right'  => '',
                        'bottom' => '',
                        'left'   => '',
                    ],
                    'selectors'   => [
                        '{{WRAPPER}} .ua-video-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                '_btn_radius',
                [
                    'label'       => esc_html__( 'Button Radius', 'ultraaddons' ),
                    'type'        => Controls_Manager::DIMENSIONS,
                    'size_units'  => [ '%', 'px' ],
                    'placeholder' => [
                        'top'    => '',
                        'right'  => '',
                        'bottom' => '',
                        'left'   => '',
                    ],
                    'separator' =>'after',
                    'selectors'   => [
                        '{{WRAPPER}} .ua-video-btn, .ua-video-btn:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
    
            $this->end_controls_tab();
    
            //Hover Tab
            $this->start_controls_tab(
                'btn_hover_tab',
                [
                    'label' => esc_html__( 'Hover', 'ultraaddonse' ),
                ]
            );
            $this->add_control(
                '_btn_bg_hover_bg', [
                    'label' => __( 'Hover Background', 'ultraaddons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                            '{{WRAPPER}} .ua-video-btn:before, .ua-video-btn:hover' => 'background: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => '_btn_hover_border',
                    'label' => esc_html__( 'Button Border', 'ultraaddons' ),
                    'selector' => '{{WRAPPER}} .ua-video-btn:hover',
                ]
            );
    
            $this->add_control(
                '_btn_text_hover_color', [
                    'label' => __( 'Button Text Color', 'ultraaddons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                            '{{WRAPPER}} .ua-video-btn:hover' => 'color: {{VALUE}};',
                            '{{WRAPPER}} .ua-video-btn:hover svg' => 'fill: {{VALUE}};',
                    ],
                    'default' =>'#fff'
                ]
            );
            $this->end_controls_tab();
            $this->end_controls_tabs();
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
        $id= $this->get_id();
        //Get Youtube ID from URL
        $youtube_url        = $settings['youtube_url'];
        parse_str( parse_url( $youtube_url, PHP_URL_QUERY ), $get_youtube_id );

        //Get Vimeo ID from URL
        $vimeo_url          = $settings['vimeo_id'];
        $get_vimeo_id       = (int) substr(parse_url($vimeo_url, PHP_URL_PATH), 1);

        if($settings['video_type']=='youtube'){
            $video_id = $get_youtube_id['v'];
        }else{
            $video_id = $get_vimeo_id;
        }
       
       
        ?>
        <div class="ua-video-btn-wrap">
            <button class="js-modal-btn-<?php echo $id;?> ua-video-btn" data-video-id="<?php echo esc_attr($video_id);?>">
                <?php Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                <?php echo $settings['btn_text']; ?>
            </button>
        </div>
        <?php
        
    }
    
    
    
    
}
