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

class Line_Chart extends Base{
    
    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);

        //Naming of Args for Skill Chart
        $name           = 'chart-js';
        $js_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/chart-js/chart.js';
        $dependency     =  ['jquery'];//['jquery'];
        $version        = ULTRA_ADDONS_VERSION;
        $in_footer      = true;

        wp_register_script( $name, $js_file_url, $dependency, $version, $in_footer );
        wp_enqueue_script( $name );

        //Naming of Args for front end chart
        $name           = 'frontend-chart-js';
        $js_file_url    = ULTRA_ADDONS_ASSETS . 'js/frontend-chart.js';
        $dependency     =  ['jquery'];//['jquery'];
        $version        = ULTRA_ADDONS_VERSION;
        $in_footer  = true;

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
		return [ 'jquery','chart-js','frontend-chart-js' ];
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
        return [ 'ultraaddons', 'ua', 'chart', 'bar', 'Pie' ];
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
        $this->chart_style();
        $this->legend_style();
        $this->box_style();
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
			'legend_label',
			[
				'label' => __( 'Legend Label', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Ultra Addons', 'ultraaddons' ),
				'label_block' => true,
                'frontend_available' => true,
			]
		);
        $this->add_control(
			'chart_title',
			[
				'label' => __( 'Chart Title', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Ultra Addons Chart Title', 'ultraaddons' ),
				'label_block' => true,
                'frontend_available' => true,
			]
		);
        $repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'labels', [
				'label' => esc_html__( 'Labels', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'January' , 'ultraaddons' ),
				'label_block' => true,
			]
		);
        $repeater->add_control(
			'data', [
				'label' => esc_html__( 'Data', 'ultraaddons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => esc_html__( '10' , 'ultraaddons' ),
				'label_block' => true,
			]
		);
        $repeater->add_control(
			'backgroundColor', [
				'label' => __( 'Node Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
			]
        );
        $this->add_control(
			'list',
			[
				'label' => esc_html__( 'Data List', 'ultraaddons' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
                'frontend_available' => true,
				'default' => [
					[
						'labels' => esc_html__( 'January', 'ultraaddons' ),
						'data' => esc_html__( '10', 'ultraaddons' ),
                        'backgroundColor' => '#37AEFF'
					],
					[
						'labels' => esc_html__( 'Februay', 'ultraaddons' ),
                        'data' => esc_html__( '14', 'ultraaddons' ),
                        'backgroundColor' => '#C60233'
					],
                    [
						'labels' => esc_html__( 'March', 'ultraaddons' ),
                        'data' => esc_html__( '20', 'ultraaddons' ),
                        'backgroundColor' => '#EFF600'
					],
                    [
						'labels' => esc_html__( 'March', 'ultraaddons' ),
                        'data' => esc_html__( '17', 'ultraaddons' ),
                        'backgroundColor' => '#1AAA09'
					],
				],
				'title_field' => '{{{ labels }}}',
			]
		);
        
        $this->end_controls_section();
    }

    protected function chart_style() {
        $this->start_controls_section(
            'chart_style',
            [
                'label'     => esc_html__( 'Chart Style', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
			'borderColor', [
				'label' => __( 'Line Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
                'frontend_available' => true,
                'default'           => '#959595'
			]
        );
        $this->add_control(
			'fill_color',
			[
				'label' => __( 'Fill Color', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ultraaddons' ),
				'label_off' => __( 'No', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'no',
                'frontend_available' => true,
                'description'       => 'Note: Default Fill Color comes from the first node color. '
			]
		);
        $this->add_control(
            'borderWidth',
                [
                    'label' => esc_html__( 'Border Width', 'ultraaddons' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 20,
                    'step' => 1,
                    'default' => 3,
                    'frontend_available' => true,
                ]
        );
        $this->add_control(
            'pointBorderWidth',
                [
                    'label' => esc_html__( 'Point Border Width', 'ultraaddons' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 20,
                    'step' => 1,
                    'default' => 3,
                    'frontend_available' => true,
                ]
        );
        $this->add_control(
			'x_ticks_color', [
				'label' => __( 'X Axis Label Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
                'default'   =>'#ddd',
                'frontend_available' => true,
			]
        );
        $this->add_control(
			'y_ticks_color', [
				'label' => __( 'Y Axis Label Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
                'default'   =>'#ddd',
                'frontend_available' => true,
			]
        );
        $this->add_control(
			'y_grid_color', [
				'label' => __( 'Y Grid Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
                'default'   =>'#444242',
                'frontend_available' => true,
			]
        );
       
        $this->end_controls_section();
    }
    protected function legend_style() {
        $this->start_controls_section(
            'legend_style',
            [
                'label'     => esc_html__( 'Legend Style', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
			'legend_color', [
				'label' => __( 'Legend Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
                'default'   =>'#ddd',
                'frontend_available' => true,
			]
        );
        $this->add_responsive_control(
			'legend_position',
			[
				'label' => esc_html__( 'Legend Position', 'ultraaddons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'ultraaddons' ),
						'icon' => 'eicon-v-align-top',
					],
                    'right' => [
						'title' => esc_html__( 'Right', 'ultraaddons' ),
						'icon' => 'eicon-h-align-right',
					],
                    'bottom' => [
						'title' => esc_html__( 'Bottom', 'ultraaddons' ),
						'icon' => 'eicon-v-align-bottom',
					],
					'left' => [
						'title' => esc_html__( 'Left', 'ultraaddons' ),
						'icon' => 'eicon-h-align-left',
					],
				],
				'default' => 'top',
                'frontend_available' => true
			]
		);
        $this->add_control(
			'display_legend',
			[
				'label' => esc_html__( 'Legend Display', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'ultraaddons' ),
				'label_off' => esc_html__( 'Hide', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'yes',
                'frontend_available' => true,
			]
		);
       
        $this->end_controls_section();
    }

    protected function box_style() {
        $this->start_controls_section(
            'box_style',
            [
                'label'     => esc_html__( 'Box Style', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
			'box_bg', [
				'label' => __( 'Box Background', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
                'default'   =>'#fff',
                'selectors' => [
					'{{WRAPPER}} .ua-chart-container' => 'background: {{VALUE}};',
				],
			]
        );

        $this->add_responsive_control(
            'box_padding',
            [
                    'label' => __( 'Padding', 'ultraaddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px' ],
                    'default'   => [
                            'top' => 15,
                            'left' => 15,
                            'right' => 15,
                            'bottom' => 15,
                            'unit' => 'px',
                    ],
                    'selectors' => [
                            '{{WRAPPER}} .ua-chart-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
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
        $id= $this->get_id();
        ?>
        <div class="ua-chart-container">
            <canvas id="uaChart-<?php echo esc_attr($id);?>"></canvas>
        </div>
        <?php
    }
    
    
    
    
    
}
