<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;



if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Button extends Base{

    public function __construct($data = [], $ultraaddons_args = null) {
        parent::__construct($data, $ultraaddons_args);

        //CSS file for dependency
		$ultraaddons_name           = 'hover-css';
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
        return [ 'ultraaddons-elementor-lite','ua', 'button', 'btn', 'hover','action' ];
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
                'label'     => esc_html__( 'General', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
			'_ua_button',
			[
				'label' => __( 'Button Text', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Ultra Addons', 'ultraaddons-elementor-lite' ),
				'label_block' => true,
			]
		);
        $this->add_control(
			'_ua_button_subtext',
			[
				'label' => __( 'Subtext', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'e.g. Free 30-day trial', 'ultraaddons-elementor-lite' ),
				'label_block' => true,
			]
		);
        $this->add_control(
			'_ua_badge_text',
			[
				'label' => __( 'Badge / Ribbon Text', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'e.g. NEW or 50% OFF', 'ultraaddons-elementor-lite' ),
				'label_block' => false,
			]
		);
        $this->add_control(
			'_ua_badge_position',
			[
				'label' => esc_html__( 'Badge Position', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'top-right' => esc_html__( 'Top Right', 'ultraaddons-elementor-lite' ),
					'top-left'  => esc_html__( 'Top Left', 'ultraaddons-elementor-lite' ),
				],
				'default' => 'top-right',
				'condition' => [
					'_ua_badge_text!' => '',
				],
			]
		);
        $this->add_control(
			'_ua_btn_animation',
			[
				'label' => esc_html__( 'Select Animation', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::SELECT,
				'options' => ultraaddons_button_hover(),
				'default' => 'none',
			]
		);
        $this->add_control(
			'_ua_btn_anim_duration',
			[
				'label' => esc_html__( 'Effect Duration (s)', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 3,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => 0.4,
				],
				'selectors' => [
					'{{WRAPPER}} .ua-btn' => 'transition-duration: {{SIZE}}s; -webkit-transition-duration: {{SIZE}}s;',
					'{{WRAPPER}} .ua-btn:before' => 'transition-duration: {{SIZE}}s; -webkit-transition-duration: {{SIZE}}s;',
					'{{WRAPPER}} .ua-btn .ua-btn-icon' => 'transition-duration: {{SIZE}}s; -webkit-transition-duration: {{SIZE}}s;',
				],
			]
		);
        $this->add_control(
			'selected_icon',
			[
				'label' => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin' => 'inline',
				'label_block' => false,
			]
		);
        $this->add_responsive_control(
			'_icon_position',
			[
				'label' => esc_html__( 'Icon Position', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
						'icon' => 'eicon-arrow-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
						'icon' => 'eicon-arrow-right',
					],
				
				],
				'default' => 'left',
			]
		);
        $this->add_control(
			'_icon_hover_animation',
			[
				'label' => esc_html__( 'Icon Hover Effect', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none'       => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
					'move-right' => esc_html__( 'Move Right', 'ultraaddons-elementor-lite' ),
					'move-left'  => esc_html__( 'Move Left', 'ultraaddons-elementor-lite' ),
					'rotate'     => esc_html__( 'Rotate', 'ultraaddons-elementor-lite' ),
					'zoom'       => esc_html__( 'Zoom', 'ultraaddons-elementor-lite' ),
				],
				'default' => 'none',
			]
		);

        $this->add_control(
			'_ua_button_link',
			[
				'label'       => __( 'Button URL', 'ultraaddons-elementor-lite' ),
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
                'label'     => esc_html__( 'Style', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
			'_btn_alignment',
			[
				'label' => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ),
						'icon' => 'eicon-text-align-center',
					],
					'end' => [
						'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'start',
				'selectors' => [
					'{{WRAPPER}} .ua-btn-wrap' => 'justify-content: {{VALUE}};',
				],
			]
		);
        $this->add_responsive_control(
			'_btn_width',
			[
				'label' => esc_html__( 'Custom Width', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 1000,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ua-btn' => 'width: {{SIZE}}{{UNIT}}; max-width: 100%;',
				],
			]
		);
        $this->add_control(
			'icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 16,
				],
				'selectors' => [
					'{{WRAPPER}} .ua-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ua-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->add_control(
			'icon_space',
			[
				'label' => esc_html__( 'Icon Space', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .ua-btn-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
                'condition' => array(
					'_icon_position' => 'left',
				),
			]
		);
        $this->add_control(
			'icon_space_right',
			[
				'label' => esc_html__( 'Icon Space', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .ua-btn-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
                'condition' => array(
					'_icon_position' => 'right',
				),
			]
		);
        $this->start_controls_tabs(
			'style_tabs'
		);
        //Normal Tab
        $this->start_controls_tab(
			'btn_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => '_btn_bg_gradient',
				'label' => esc_html__( 'Button Background', 'ultraaddons-elementor-lite' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ua-btn',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => '_btn_border',
				'label' => esc_html__( 'Button Border', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ua-btn',
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'label' => esc_html__( 'Button Shadow', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ua-btn',
			]
		);
        $this->add_control(
			'_btn_text_color', [
				'label' => __( 'Button Text Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-btn' => 'color: {{VALUE}};',
						'{{WRAPPER}} .ua-btn svg' => 'fill: {{VALUE}};',
				],
			]
        );
       
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'btn_typography',
					'label' => 'Button Typography',
					'selector' => '{{WRAPPER}} .ua-btn, {{WRAPPER}} .ua-btn-text',

			]
        );
        $this->add_responsive_control(
			'_btn_padding',
			[
				'label'       => esc_html__( 'Button Padding', 'ultraaddons-elementor-lite' ),
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
				'label'       => esc_html__( 'Button Radius', 'ultraaddons-elementor-lite' ),
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
					'{{WRAPPER}} .ua-btn-wrap a.ua-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} ;',
					'{{WRAPPER}} .ua-btn-wrap a.ua-btn:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} ;',
				],
			]
		);

        $this->end_controls_tab();

        //Hover Tab
        $this->start_controls_tab(
			'btn_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => '_btn_bg_hover_gradient',
				'label' => esc_html__( 'Hover Background', 'ultraaddons-elementor-lite' ),
				'types' => [ 'classic', 'gradient' ],
				'condition'=>['_ua_btn_animation'=>'none'],
				'selector' => '{{WRAPPER}} .ua-btn:hover',
			]
		);
        $this->add_control(
			'_btn_bg_hover_bg', [
				'label' => __( 'Hover Effect Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'condition'=>['_ua_btn_animation!'=>'none'],
				'selectors' => [
						'{{WRAPPER}} .ua-btn:before' => 'background: {{VALUE}};',
				],
			]
        );
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => '_btn_hover_border',
				'label' => esc_html__( 'Button Border', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ua-btn:hover',
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => '_btn_hover_box_shadow',
				'label' => esc_html__( 'Button Shadow', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ua-btn:hover',
			]
		);

		$this->add_control(
			'_btn_text_hover_color', [
				'label' => __( 'Button Text Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-btn:hover' => 'color: {{VALUE}};',
						'{{WRAPPER}} .ua-btn:hover .ua-btn-text' => 'color: {{VALUE}};',
						'{{WRAPPER}} .ua-btn:hover .ua-btn-subtext' => 'color: {{VALUE}};',
						'{{WRAPPER}} .ua-btn:hover svg' => 'fill: {{VALUE}};',
				],
				'default' =>'#fff'
			]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        // Subtext Style Section
        $this->start_controls_section(
            'btn_subtext_style',
            [
                'label'     => esc_html__( 'Subtext Style', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    '_ua_button_subtext!' => '',
                ],
            ]
        );
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'subtext_typography',
				'label' => __( 'Typography', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ua-btn-subtext',
			]
        );
        $this->add_control(
			'subtext_color', [
				'label' => __( 'Color', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-btn-subtext' => 'color: {{VALUE}};',
				],
			]
        );
        $this->add_control(
			'subtext_hover_color', [
				'label' => __( 'Hover Color', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-btn:hover .ua-btn-subtext' => 'color: {{VALUE}};',
				],
			]
        );
        $this->add_responsive_control(
			'subtext_margin',
			[
				'label' => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ua-btn-subtext' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->end_controls_section();

        // Badge Style Section
        $this->start_controls_section(
            'btn_badge_style',
            [
                'label'     => esc_html__( 'Badge Style', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    '_ua_badge_text!' => '',
                ],
            ]
        );
        $this->add_control(
			'badge_bg_color', [
				'label' => __( 'Background Color', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ff3b30',
				'selectors' => [
					'{{WRAPPER}} .ua-btn-badge' => 'background-color: {{VALUE}};',
				],
			]
        );
        $this->add_control(
			'badge_text_color', [
				'label' => __( 'Text Color', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ua-btn-badge' => 'color: {{VALUE}};',
				],
			]
        );
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'badge_typography',
				'label' => __( 'Typography', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ua-btn-badge',
			]
        );
        $this->add_responsive_control(
			'badge_padding',
			[
				'label' => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .ua-btn-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->add_responsive_control(
			'badge_radius',
			[
				'label' => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ua-btn-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
	
        if ( ! empty( $settings['_ua_button_link']['url'] ) ) {
			$this->add_link_attributes( '_ua_button_link', $settings['_ua_button_link'] );
		}
		$btn_class = 'ua-btn';
		if ( ! empty( $settings['_ua_btn_animation'] ) && $settings['_ua_btn_animation'] !== 'none' ) {
			$btn_class .= ' ' . $settings['_ua_btn_animation'];
		}
		if ( ! empty( $settings['_icon_hover_animation'] ) && $settings['_icon_hover_animation'] !== 'none' ) {
			$btn_class .= ' ua-icon-anim-' . $settings['_icon_hover_animation'];
		}

        $this->add_render_attribute(
            'button_class',
            [
                'class' => $btn_class,
            ]
        );

        $has_subtext = ! empty( $settings['_ua_button_subtext'] );
        $has_badge   = ! empty( $settings['_ua_badge_text'] );
        $badge_pos   = ! empty( $settings['_ua_badge_position'] ) ? $settings['_ua_badge_position'] : 'top-right';
        ?>
        <div class="ua-btn-wrap ua-d-flex">
            <a <?php echo $this->get_render_attribute_string( '_ua_button_link' ); ?> <?php echo $this->get_render_attribute_string( 'button_class' ); ?>>
                <?php if ( $has_badge ) : ?>
                    <span class="ua-btn-badge ua-badge-<?php echo esc_attr( $badge_pos ); ?>"><?php echo esc_html( $settings['_ua_badge_text'] ); ?></span>
                <?php endif; ?>

                <?php if ( 'left' === $settings['_icon_position'] && ! empty( $settings['selected_icon']['value'] ) ) : ?>
                    <span class="ua-btn-icon ua-btn-icon-left"><?php Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
                <?php endif; ?>

                <span class="ua-btn-text-wrap">
                    <span class="ua-btn-text"><?php echo esc_html( $settings['_ua_button'] ); ?></span>
                    <?php if ( $has_subtext ) : ?>
                        <span class="ua-btn-subtext"><?php echo esc_html( $settings['_ua_button_subtext'] ); ?></span>
                    <?php endif; ?>
                </span>

                <?php if ( 'right' === $settings['_icon_position'] && ! empty( $settings['selected_icon']['value'] ) ) : ?>
                    <span class="ua-btn-icon ua-btn-icon-right"><?php Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
                <?php endif; ?>
            </a>
        </div>
        <?php
    }
}
