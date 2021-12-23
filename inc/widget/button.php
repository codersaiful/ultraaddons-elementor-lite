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

class Button extends Base{

    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);

        //CSS file for dependency
		$name           = 'hover-css';
        $css_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/hover-css/css/hover-min.css';
        $dependency     =  [];
        $version        = ULTRA_ADDONS_VERSION;
        $media  	= 'all';
        wp_register_style('hover-css', $css_file_url,$dependency,$version, $media ); //product-carousel
        wp_enqueue_style('hover-css' );
    }
      /**
     * By B M Rafiul Alam
     * depend css for this widget
     * 
     * @return Array
     */
    public function get_style_depends() {
        return ['hover-css'];
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
        return [ 'ultraaddons','ua', 'button', 'btn', 'hover','action' ];
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
         //For Style
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
			'_ua_button',
			[
				'label' => __( 'Button Text', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Button', 'ultraaddons' ),
				'label_block' => true,
			]
		);
        $this->add_control(
			'_ua_btn_animation',
			[
				'label' => esc_html__( 'Select Animation', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'hvr-fade' => 'Fade',
					'hvr-back-pulse' => 'Back Pulse',
					'hvr-sweep-to-right' => 'Sweep To Right',
					'hvr-sweep-to-left' => 'Sweep To Left',
					'hvr-sweep-to-bottom' => 'Sweep To Bottom',
					'hvr-sweep-to-top' => 'Sweep To Top',
					'hvr-sweep-to-top'=>	'Sweep To Top',
					'hvr-bounce-to-top'=>	'Bounce To Top',
					'hvr-bounce-to-right'=>	'Bounce To Right',
					'hvr-bounce-to-left'=>	'Bounce To Left',
					'hvr-bounce-to-bottom'=> 'Bounce To Bottom',
					'hvr-shadow'=> 'Shadow',
					'hvr-grow-shadow'=> 'Grow Shadow',
					'hvr-float-shadow'=> 'Float Shadow',
					'hvr-ripple-out'=> 'Ripple Out',
					'hvr-underline-from-center'=> 'Underline From Center',
					'hvr-overline-from-left'=> 'Overline From Left',
                    'hvr-rectangle-in'=> 'Rectangle In',
					'hvr-rectangle-out'=> 'Rectangle Out',
					'hvr-shutter-in-vertical'=> 'Shutter In Vertical',
					'hvr-shutter-out-vertical'=> 'Shutter out Vertical',
					'hvr-shutter-in-horizontal'=> 'Shutter In Horizontal',
                    'hvr-shutter-out-horizontal'=> 'Shutter Out Horizontal', 
					'ua-effect'=> 'UA Effect',
					'ua-effect-2'=> 'UA Effect 2',
				],
				'default' => 'ua-effect',
			]
		);
        $this->add_control(
			'_ua_button_link',
			[
				'label'       => __( 'Button URL', 'ultraaddons' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => 'https://example.com',
			]
		);
        
        $this->end_controls_section();
    }
     /**
     * General Section for Content Controls
     * 
     * @since 1.0.0.9
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
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .ua-btn-wrap' => 'justify-content: {{VALUE}};',
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
			'_btn_bg_color', [
				'label' => __( 'Button Background', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-btn' => 'background-color: {{VALUE}};',
				],
			]
        );
        $this->add_control(
			'_btn_text_color', [
				'label' => __( 'Button Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-btn' => 'color: {{VALUE}};',
				],
			]
        );
       
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'btn_typography',
					'label' => 'Button Typography',
					'selector' => '{{WRAPPER}} .ua-btn',

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
					'{{WRAPPER}} .ua-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .ua-btn, .ua-btn:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .ua-btn:before' => 'background: {{VALUE}};',
				],
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
        $settings   = $this->get_settings_for_display();
        if ( ! empty( $settings['_ua_button_link']['url'] ) ) {
			$this->add_link_attributes( '_ua_button_link', $settings['_ua_button_link'] );
		}
        $this->add_render_attribute(
            'button_class',
            [
                'class' => 'ua-btn ' . $settings['_ua_btn_animation'] ,
            ]
        );
        ?>
        <div class="ua-btn-wrap ua-d-flex">
            <a <?php echo $this->get_render_attribute_string( '_ua_button_link' ); ?> <?php echo $this->get_render_attribute_string( 'button_class' );?>>
                <?php echo $settings['_ua_button'];?>
            </a>
        </div>
        <?php
        
    }
    
    
    
}
