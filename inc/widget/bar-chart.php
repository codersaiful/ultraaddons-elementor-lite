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

class Bar_Chart extends Base{
    
    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);

        //Naming of Args for Skill Chart
        $name           = 'chart-js';
        $js_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/chart-js/chart.js';
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
		return [ 'jquery','chart-js' ];
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
			'label',
			[
				'label' => __( 'Label', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Ultra Addons', 'ultraaddons' ),
				'label_block' => true,
                'frontend_available' => true,
			]
		);
      /*   $this->add_control(
			'type',
			[
				'label' => __( 'Chart Type', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'line',
				'frontend_available' => true,
				'options' => [
					'line' => __( 'Line', 'ultraaddons' ),
					'bar' => __( 'Bar', 'ultraaddons' ),
					'radar' => __( 'Radar', 'ultraaddons' ),
					'doughnut' => __( 'Doughnut', 'ultraaddons' ),
					'pie' => __( 'Pie', 'ultraaddons' ),
					'polarArea' => __( 'PolarArea', 'ultraaddons' ),
				],
			]
		); */
        $this->add_control(
			'borderColor', [
				'label' => __( 'Border Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
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
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '10' , 'ultraaddons' ),
				'label_block' => true,
			]
		);
        $repeater->add_control(
			'backgroundColor', [
				'label' => __( 'Background Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
			]
        );
        $this->add_control(
			'list',
			[
				'label' => esc_html__( 'Repeater List', 'ultraaddons' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
                'frontend_available' => true,
				'default' => [
					[
						'labels' => esc_html__( 'January', 'ultraaddons' ),
						'data' => esc_html__( '10', 'ultraaddons' ),
					],
					[
						'labels' => esc_html__( 'Februay', 'ultraaddons' ),
                        'data' => esc_html__( '15', 'ultraaddons' ),
					],
                    [
						'labels' => esc_html__( 'March', 'ultraaddons' ),
                        'data' => esc_html__( '20', 'ultraaddons' ),
					],
				],
				'title_field' => '{{{ labels }}}',
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
            <canvas id="uaChart-<?php echo $id;?>"></canvas>
        </div>
        <?php
        
    }
    
    
    
    
    
}
