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

class Skill_Chart extends Base{
	
	public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);

        //Naming of Args for Skill Chart
        $name           = 'SkillChart';
        $js_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/js/easypiechart.js';
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
		return [ 'jquery','SkillChart' ];
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
        return [ 'ultraaddons', 'heading', 'skill', 'chart','pie chart' ];
    }
	 protected function _register_controls() {
        //For Control Section
        $this->Skill_Chart_settings();
    }
	
	protected function Skill_Chart_settings(){
		$this->start_controls_section(
            'skill_pie_settings',
            [
                'label'     => esc_html__( 'Control Settings', 'ultraaddons' ),
            ]
        );

        
		$this->add_control(
			'lineWidth',
			[
				'label' => __( 'Line Width', 'ultraaddons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 20,
				'max' => 50,
				'step' => 10,
				'default' => 20,
				'frontend_available' => true,
			]
		);
		$this->add_control(
			'size',
			[
				'label' => __( 'Size', 'ultraaddons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 100,
				'max' => 200,
				'step' => 10,
				'default' => 160,
				'frontend_available' => true,
			]
		);
		$this->add_control(
			'animate',
			[
				'label' => __( 'Animate', 'ultraaddons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 100,
				'max' => 300,
				'step' => 10,
				'default' => 200,
				'frontend_available' => true,
			]
		);
		$this->end_controls_section();
	}

    protected function render() {?>
	 <div class="ua-skill-chart" data-percent="80">HTML5</div>
	<?php }
}
