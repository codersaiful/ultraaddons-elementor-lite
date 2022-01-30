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
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;


class Drop_Caps extends Base{
    
    /**
     * Get your widget name
     *
     * Retrieve image accordion widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string keywords
     */
    public function get_keywords() {
        return [ 'ultraaddons','ua', 'drop', 'caps' ];
    }

    /**
     * Register image accordion widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        // For General Section
        $this->content_general_contents_controls();
        // $this->content_tab_content_controls();
        // $this->style_tab_general_controls();
        // $this->style_tab_title_controls();
        // $this->style_tab_content_controls();
        // $this->accordion_action_icon_style_section();
        
    }

    /**
     * Render image accordion widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        extract($settings);
        ?>
		<div class="ua-dropcap-wraper">
			<?php if( !empty( $ua_dropcaps_text ) ) : ?>
			<p class="ua-dropcap-cotnent"><?php echo ultraaddons_addons_kses( $ua_dropcaps_text )?></p>
			<?php endif; ?>
		</div>
        <?php
    }

    protected function content_general_contents_controls(){
        $this->start_controls_section(
            'ua_dropcaps_content',
            [
                'label' => esc_html__( 'Dropcaps', 'ultraaddons' ),
            ]
        );

		$this->add_control(
			'ua_dropcaps_text',
			[
				'label'         => esc_html__( 'Content', 'ultraaddons' ),
				'type'          => Controls_Manager::TEXTAREA,
				'default'       => esc_html__( 'Lorem ipsum dolor sit amet, consec adipisicing elit, sed do eiusmod tempor incidid ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip exl Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incidid ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip.', 'ultraaddons' ),
				'placeholder'   => esc_html__( 'Enter Your Drop Caps Content.', 'ultraaddons' ),
                'separator'=>'before',
                'dynamic' => [
                    'active' => true,
                ],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
            'ua_dropcaps_style_section',
            [
                'label' => esc_html__( 'Style', 'ultraaddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'ua_content_color',
                [
                    'label' => esc_html__( 'Color', 'ultraaddons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#333333',
                    'selectors' => [
                        '{{WRAPPER}} .ua-dropcap-cotnent' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'ua_content_typography',
                    'selector' => '{{WRAPPER}} .ua-dropcap-cotnent',
                ]
            );

        $this->end_controls_section();

        // Style dropcaps latter tab section
        $this->start_controls_section(
            'ua_dropcaps_latter_style_section',
            [
                'label' => esc_html__( 'Dropcap Latter', 'ultraaddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'ua_content_dropcaps_color',
                [
                    'label' => esc_html__( 'Color', 'ultraaddons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#903',
                    'selectors' => [
                        '{{WRAPPER}} .ua-dropcap-cotnent:first-child:first-letter' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'ua_content_dropcaps_typography',
                    'selector' => '{{WRAPPER}} .ua-dropcap-cotnent:first-child:first-letter',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'ua_content_dropcaps_background',
                    'label' => esc_html__( 'Background', 'ultraaddons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .ua-dropcap-cotnent:first-child:first-letter',
                ]
            );

            $this->add_responsive_control(
                'ua_content_dropcaps_padding',
                [
                    'label' => esc_html__( 'Padding', 'ultraaddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ua-dropcap-cotnent:first-child:first-letter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'ua_content_dropcaps_margin',
                [
                    'label' => esc_html__( 'Margin', 'ultraaddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ua-dropcap-cotnent:first-child:first-letter' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'ua_content_dropcaps_border',
                    'label' => esc_html__( 'Border', 'ultraaddons' ),
                    'selector' => '{{WRAPPER}} .ua-dropcap-cotnent:first-child:first-letter',
                ]
            );

            $this->add_responsive_control(
                'ua_content_dropcaps_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'ultraaddons' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ua-dropcap-cotnent:first-child:first-letter' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();
    }

}
