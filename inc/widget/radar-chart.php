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

class Radar_Chart extends Base{
    
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
        return [ 'ultraaddons', 'ua', 'radar', 'mix','mixed', 'bar','line', 'graph' ];
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
        $this->title_style();
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
                'label'     => esc_html__( 'Content', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
         $this->add_control(
			'show_title',
			[
				'label' => esc_html__( 'Custom Title', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'ultraaddons' ),
				'label_off' => esc_html__( 'Hide', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
         $this->add_control(
			'chart_custom_title',
			[
				'label' => __( 'Custom Title', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Ultra Addons', 'ultraaddons' ),
				'label_block' => true,
                'condition'=>[
                    'show_title'=>'yes',
                ],
			]
		);
        
         $this->add_control(
			'chart_description',
			[
				'label' => __( 'Description', 'ultraaddons' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
                'separator' => 'after',
                 'condition'=>[
                    'show_title'=>'yes',
                ],
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
        $this->add_control(
			'bar_legend_label',
			[
				'label' => __( 'Data #1 Legend Label', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Ultra Addons', 'ultraaddons' ),
				'label_block' => true,
                'frontend_available' => true,
			]
		);
        $this->add_control(
			'line_legend_label',
			[
				'label' => __( 'Line Chart Legend Label', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Woo Product Table', 'ultraaddons' ),
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
			'data_1', [
				'label' => esc_html__( 'Data #1', 'ultraaddons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => esc_html__( '10' , 'ultraaddons' ),
				'label_block' => true,
			]
		);
        $repeater->add_control(
			'backgroundColor', [
				'label' => __( 'Background Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
                'separator' =>'after'
			]
        );
        $repeater->add_control(
			'data_2', [
				'label' => esc_html__( 'Data #2', 'ultraaddons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => esc_html__( '10' , 'ultraaddons' ),
				'label_block' => true,
			]
		);
       
        $this->add_control(
			'data_list',
			[
				'label' => esc_html__( 'Chart Data', 'ultraaddons' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
                'frontend_available' => true,
                //   data: [65, 59, 90, 81, 56, 55, 40],
                // data:   [28, 48, 40, 19, 96, 27, 100],
				'default' => [
					[
						'labels' => esc_html__( 'January', 'ultraaddons' ),
						'data_1' => esc_html__( '65', 'ultraaddons' ),
                        'backgroundColor' => '#138FE440',
                        'data_2' => esc_html__( '28', 'ultraaddons' ),
					],
					[
						'labels' => esc_html__( 'Februay', 'ultraaddons' ),
                        'data_1' => esc_html__( '59', 'ultraaddons' ),
                        'backgroundColor' => '#54D300CC',
                        'data_2' => esc_html__( '48', 'ultraaddons' ),
					],
                    [
					    'labels' => esc_html__( 'March', 'ultraaddons' ),
                        'data_1' => esc_html__( '90', 'ultraaddons' ),
                        'data_2' => esc_html__( '40', 'ultraaddons' ),
					],
                    [
					    'labels' => esc_html__( 'April', 'ultraaddons' ),
                        'data_1' => esc_html__( '81', 'ultraaddons' ),
                        'data_2' => esc_html__( '19', 'ultraaddons' ),
					],
                    [
					    'labels' => esc_html__( 'May', 'ultraaddons' ),
                        'data_1' => esc_html__( '56', 'ultraaddons' ),
                        'data_2' => esc_html__( '96', 'ultraaddons' ),
					],
                    [
					    'labels' => esc_html__( 'June', 'ultraaddons' ),
                        'data_1' => esc_html__( '55', 'ultraaddons' ),
                        'data_2' => esc_html__( '27', 'ultraaddons' ),
					],
                    [
					    'labels' => esc_html__( 'July', 'ultraaddons' ),
                        'data_1' => esc_html__( '40', 'ultraaddons' ),
                        'data_2' => esc_html__( '100', 'ultraaddons' ),
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
			'title_color', [
				'label' => __( 'Title Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
                'frontend_available' => true,
                'default'           => '#959595',
                'separator' => 'after'
			]
        );
        $this->add_control(
			'pointLabels', [
				'label' => __( 'Point Labels Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
                'frontend_available' => true,
                'default'           => '#959595',
			]
        );
        $this->add_control(
			'grid_color', [
				'label' => __( 'Grid Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
                'default'   =>'#DEDEDE',
                'frontend_available' => true,
                'separator' => 'before'
			]
        );
        $this->add_control(
			'angle_color', [
				'label' => __( 'Angle Lines Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
                'default'   =>'#DEDEDE',
                'frontend_available' => true,
                'separator' => 'before'
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
				'label' => __( 'Legend Text Color', 'ultraaddons' ),
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
     //Style for Cusom Title
    protected function title_style() {
        $this->start_controls_section(
            'title_style',
            [
                'label'     => esc_html__( 'Title & Description', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
         $this->add_control(
            'content_align',
                [
                    'label'         => esc_html__( 'Align', 'ultraaddons' ),
                    'type'          => Controls_Manager::CHOOSE,
                    'options' => [
                            'left' => [
                                    'title' => __( 'Left', 'ultraaddons' ),
                                    'icon' => 'eicon-text-align-left',
                            ],
                            'center' => [
                                    'title' => __( 'Center', 'ultraaddons' ),
                                    'icon' => 'eicon-text-align-center',
                            ],
                            'right' => [
                                    'title' => __( 'Right', 'ultraaddons' ),
                                    'icon' => 'eicon-text-align-right',
                            ],
                    ],
                    'default' => 'left',
                    'selectors' => [
                            '{{WRAPPER}} .chart-content' => 'text-align:{{VALUE}};',
                    ],
                    
                ]
        );
         $this->add_control(
			'_ua_chart_title_tag',
			[
				'label' => esc_html__( 'Select Title Tag', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
				],
				'default' => 'h2',
                'condition'=>[
                    'show_title'=>'yes',
                ],
			]
		);
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .chart-title',
            ]
        );
        $this->add_control(
			'chart_title_color', [
				'label' => __( 'Title Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .chart-title' => 'color: {{VALUE}};',
				],
			]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                    'label' => __( 'Title Margin', 'ultraaddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px' ],
                    'separator'  => 'after',
                    'selectors' => [
                            '{{WRAPPER}} .chart-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
            ]
        );
         $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'desc_typography',
                'selector' => '{{WRAPPER}} .chart-desc',
            ]
        );
        $this->add_control(
			'chart_desc_color', [
				'label' => __( 'Description Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .chart-desc' => 'color: {{VALUE}};',
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
            <div class="chart-content">
                <?php
                    if('yes'===$settings['show_title']){
                    echo '<' . $settings['_ua_chart_title_tag'] . ' class="chart-title">' . esc_html($settings['chart_custom_title']) . 
                            '</' . $settings['_ua_chart_title_tag'] . '>';
                    }
                ?>
                <p class="chart-desc"><?php echo $settings['chart_description']; ?></p>
           </div>
            <canvas id="uaChart-<?php echo esc_attr($id);?>"></canvas>
        </div>
        <?php
    }
    
    
}
