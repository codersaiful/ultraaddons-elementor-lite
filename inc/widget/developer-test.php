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
 * Only for test perpose
 * 
 * 
 * @since 1.1.0.8
 * @package UltraAddons
 * @author Saiful islam <codersaiful@gmail.com>
 * @author Rafiul <bmrafiul.alam@gmail.com>
 */
class Developer_Test extends Base{

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
        return [ 'ultraaddons', 'ua', 'test', 'tst', 'saiful' ];
    }
	
	 /**
     * Register widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.1.0.8
     * @access protected
     */
    protected function register_controls() {
        //For Setting Control
        $this->test_settings_controls();
    }


    protected function render(){
        $settings 				=	(array) $this->get_settings_for_display();
		?>
		<div class="developer_test_element">
			<h2>Developer_Test (Only for View) <span></span></h2>
		</div>
		
		<?php

        //var_dump($settings);
    }



    /**
     * Retrive setting for news tricker control
     * 
     * @author Saiful <codersaiful@gmail.com>
     *
     * @return void
     */
    protected function test_settings_controls(){
        $this->start_controls_section(
            'test_settings',
            [
                'label'     => esc_html__( 'Control Settings', 'ultraaddons' ),
            ]
        );

        
		$this->add_control(
			'delayTimer',
			[
				'label' => __( 'Delay Timer', 'ultraaddons' ),
				'type' => Controls_Manager::NUMBER,
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
				'type' => Controls_Manager::NUMBER,
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
				'type' => Controls_Manager::NUMBER,
				'min' => 99999,
				'max' => 999999,
				'step' => 1000,
				'default' => 99999,
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'play',
			[
				'label' => __( 'Play', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
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
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ultraaddons' ),
				'label_off' => __( 'No', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'frontend_available' => true,
			]
		);
		$this->add_control(
			'show_controls',
			[
				'label' => __( 'Action Button', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ultraaddons' ),
				'label_off' => __( 'No', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'position',
			[
				'label' => __( 'Position', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
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
			'effect',
			[
				'label' => __( 'Effects', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
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
			'direction',
			[
				'label' => __( 'Direction', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'ltr',
				'frontend_available' => true,
				'options' => [
					'rtl'  => __( 'RTL', 'ultraaddons' ),
					'ltr' => __( 'LTR', 'ultraaddons' ),
				],
			]
		);
		

        $this->end_controls_section();
    }
}