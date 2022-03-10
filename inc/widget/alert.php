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
use \ELEMENTOR\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Alert extends Base{
    
    
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
        return [ 'ultraaddons', 'ua', 'alert', 'notice', 'info' ];
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
        $this->ua_content_general_controls();
        $this->ua_alert_content_general_controls();
        $this->ua_alert_genaral_style_control();
        $this->ua_alert_title_style_control();

       
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
        $settings               = $this->get_settings_for_display();
        $_ua_alert_title_size   = $settings['_ua_alert_title_size'];
        $alert_show_icon        = ! empty( $settings['_ua_alert_icon_show'] );
        $alert_cross_show       = ! empty( $settings['_ua_alert_cross_icon_show'] );
        ?>
        <div class="ua_alert_box ua_alert_box_style_01 <?php echo esc_html( $settings['_ua_alert_design_format']); ?>">
            <<?php echo $_ua_alert_title_size; ?> class="ua_alert_desc">
                <?php if($alert_show_icon): ?><?php Icons_Manager::render_icon( $settings['_ua_alert_icon'] ); ?><?php endif; ?>
                <?php echo wp_kses_post( $settings['_ua_alert_title']); ?>
            </<?php echo $_ua_alert_title_size; ?>>
            <?php if($alert_cross_show): ?>
            <button type="button" class="ua_alert_close">
                <?php Icons_Manager::render_icon( $settings['_ua_alert_cross_icon'] ); ?>
            </button>
            <?php endif; ?>
        </div>
        <?php
        
        
    }
    
        
    /**
     * General Section for Content Controls
     * 
     * @since 1.0.0.9
     */
    protected function ua_content_general_controls() {
        $this->start_controls_section(
            'general_content',
            [
                'label'     => esc_html__( 'General', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
			'_ua_alert_design_format',
			[
				'label' => esc_html__( 'Alert Design', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'options'   => [
					'ua_notice_alert'  => 'Notice Alert',
					'ua_info_alert'    => 'Info Alert',
                    'ua_error_alert'   => 'Error Alert',
					'ua_success_alert' => 'Success Alert',
					'ua_warning_alert' => 'Warning Alert',
					'ua_default_alert' => 'Default Alert',
				],
				'default' => 'ua_notice_alert'
			]
		);
        
        $this->end_controls_section();
    }

    /**
     * Alert Content Section
     * 
     * @since 1.0.8.0
     */
    protected function ua_alert_content_general_controls() {
        $this->start_controls_section(
			'_ua_alert_content_section',
			[
				'label' => __( 'Alert Content', 'ultraaddons' ),
			]
		);

		$this->add_control(
			'_ua_alert_title',
			[
				'label' => __( 'Alert Title', 'ultraaddons' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'The quickest & easiest service provider', 'ultraaddons' ),
				'placeholder' => __( 'Enter your title', 'ultraaddons' ),
				'label_block' => true,
			]
		);
		
		$this->add_control(
			'_ua_alert_show_as_default',
			[
                'label' => __('Set as Default', 'ultraaddons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'return_value' => 'yes',
			]
        );
        
        $this->add_control(
            '_ua_alert_title_size',
            [
                'label' => __( 'Title HTML Tag', 'ultraaddons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'options' => [
                    'h1'  => [
                        'title' => __( 'H1', 'ultraaddons' ),
                        'icon' => 'eicon-editor-h1'
                    ],
                    'h2'  => [
                        'title' => __( 'H2', 'ultraaddons' ),
                        'icon' => 'eicon-editor-h2'
                    ],
                    'h3'  => [
                        'title' => __( 'H3', 'ultraaddons' ),
                        'icon' => 'eicon-editor-h3'
                    ],
                    'h4'  => [
                        'title' => __( 'H4', 'ultraaddons' ),
                        'icon' => 'eicon-editor-h4'
                    ],
                    'h5'  => [
                        'title' => __( 'H5', 'ultraaddons' ),
                        'icon' => 'eicon-editor-h5'
                    ],
                    'h6'  => [
                        'title' => __( 'H6', 'ultraaddons' ),
                        'icon' => 'eicon-editor-h6'
                    ],
                    'p'  => [
                        'title' => __( 'P', 'ultraaddons' ),
                        'icon' => 'eicon-editor-paragraph'
                    ],
                ],
                'default' => 'p',
                'toggle' => false,
                
            ]
		);
		$this->add_control(
			'_ua_alert_icon_show',
			[
				'label' => __( 'Alert Icon', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ultraaddons' ),
				'label_off' => __( 'NO', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		
		$this->add_control(
			'_ua_alert_icon',
			[
				'label' => __( 'Alert Icon', 'ultraaddons' ),
				'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => [
                    'value' => 'fas fa-volume-up',
                    'library' => 'fa-solid',
                ],
			]
		);
		$this->add_control(
			'_ua_alert_cross_icon_show',
			[
				'label' => __( 'Alert Cross Icon', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ultraaddons' ),
				'label_off' => __( 'NO', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		
		$this->add_control(
			'_ua_alert_cross_icon',
			[
				'label' => __( 'Alert Cross Icon', 'ultraaddons' ),
				'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => [
                    'value' => 'fas fa fa-times',
                    'library' => 'fa-solid',
                ],
			]
		);
        $this->end_controls_section();
    }


    /**
     * Alert General Style Section
     * 
     * @since 1.0.8.0
     */
    protected function ua_alert_genaral_style_control(){
        $this->start_controls_section(
            '_ua_alert_style_general',
            [
                'label' => esc_html__('General', 'ultraaddons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'alert_background',
                'label' => esc_html__('Background Color', 'ultraaddons'),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .ua_alert_box.ua_alert_box_style_01',
            ]
        );
                
        $this->add_control(
            '_ua_alert_box_border',
            [
                'label' => esc_html__('Border Color', 'ultraaddons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ua_alert_box.ua_alert_box_style_01' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_alert_box_border_radius',
            [
                'label' => __( 'Border Radius', 'ultraaddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ua_alert_box.ua_alert_box_style_01' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
		$this->add_responsive_control(
            '_ua_alert_box_margin',
            [
                'label' => esc_html__('Box Margin', 'ultraaddons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ua_alert_box.ua_alert_box_style_01' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
            
        $this->add_responsive_control(
            '_ua_alert_box_padding',
            [
                'label' => esc_html__('Box Padding', 'ultraaddons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ua_alert_box.ua_alert_box_style_01' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
                
        $this->end_controls_section();
    }

    /**
     * Alert Title Style Section
     * 
     * @since 1.0.8.0
     */            
    protected function ua_alert_title_style_control(){
        $this->start_controls_section(
            '_ua_alert_title_style_settings',
            [
                'label' => esc_html__('Alert Title', 'ultraaddons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => '_ua_alert_title_typography',
                'selector' => '{{WRAPPER}} .ua_alert_box .ua_alert_desc',
            ]
        );
        $this->add_control(
            '_ua_alert_text_color',
            [
                'label' => esc_html__('Text Color', 'ultraaddons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ua_alert_box .ua_alert_desc' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            '_alert_title_opacity',
            [
                'label'     => __('Opacity', 'ultraaddons'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max'  => 10,
                        'min'  => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua_alert_box .ua_alert_desc' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            '_alert_title_padding',
            [
                'label'      => __('Padding', 'ultraaddons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .ua_alert_box .ua_alert_desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        
        $this->start_controls_section(
            '_ua_alert_icon_style_settings',
            [
                'label' => esc_html__('Alert Icon', 'ultraaddons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            '_ua_alert_icon_bg_color',
            [
                'label' => esc_html__('Icon Background Color', 'ultraaddons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ua_alert_box.ua_alert_box_style_01 .ua_alert_desc i' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            '_ua_alert_icon_color',
            [
                'label' => esc_html__('Icon Color', 'ultraaddons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ua_alert_box.ua_alert_box_style_01 .ua_alert_desc i' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            '_ua_alert_icon_size',
            [
                'label' => __( 'Icon Size', 'ultraaddons' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua_alert_box.ua_alert_box_style_01 .ua_alert_desc i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua_alert_box.ua_alert_box_style_01 .ua_alert_desc svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            '_alert_icon_padding',
            [
                'label'      => __('Padding', 'ultraaddons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .ua_alert_box.ua_alert_box_style_01 .ua_alert_desc i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            '_alert_icon_radius',
            [
                'label'      => __('Radius', 'ultraaddons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .ua_alert_box.ua_alert_box_style_01 .ua_alert_desc i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            '_ua_alert_cross_icon_style_settings',
            [
                'label' => __('Cross Icon', 'ultraaddons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            '_ua_alert_cross_icon_bg_color',
            [
                'label' => __('Icon Background Color', 'ultraaddons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ua_alert_box.ua_alert_box_style_01 .ua_alert_close i' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            '_ua_alert_cross_icon_color',
            [
                'label' => __('Icon Color', 'ultraaddons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ua_alert_box.ua_alert_box_style_01 .ua_alert_close i' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            '_ua_alert_cross_icon_size',
            [
                'label' => __( 'Icon Size', 'ultraaddons' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua_alert_box.ua_alert_box_style_01 .ua_alert_close i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua_alert_box.ua_alert_box_style_01 .ua_alert_close svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_alert_cross_icon_padding',
            [
                'label'      => __('Padding', 'ultraaddons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .ua_alert_box.ua_alert_box_style_01 .ua_alert_close i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
    }
            
            
            
}
        