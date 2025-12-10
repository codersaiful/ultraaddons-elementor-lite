<?php 
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Skill Chart Widget
 * Create excellent Skill chart by this widget. 
 * Credit: https://github.com/rendro/easy-pie-chart
 * @since 1.1.0.8
 * @package UltraAddons
 * @author Saiful islam <codersaiful@gmail.com>
 * @author Rafiul <bmrafiul.alam@gmail.com>
 */

class Skill_Chart extends Base{
	
	public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);

        //Naming of Args for Skill Chart
        $ultraaddons_name           = 'SkillChart';
        $js_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/js/easypiechart.js';
        $dependency     =  ['jquery'];//['jquery'];
        $version        = ULTRA_ADDONS_VERSION;
        $in_footer  = true;

        wp_register_script( $ultraaddons_name, $js_file_url, $dependency, $version, $in_footer );
        wp_enqueue_script( $ultraaddons_name );
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
        return [ 'ultraaddons-elementor-lite', 'ua', 'heading', 'skill', 'chart','pie chart','graph' ];
    }
	 protected function register_controls() {
        //For Content
        $this->skill_chart_content();
		 //For Control Section
        $this->Skill_Chart_settings(); 
		//For Style Section
        $this->skill_chart_style();
    }

	/**
	 * Frontend text and values.
	 */
	protected function skill_chart_content(){
        $this->start_controls_section(
            'skill_chart_content',
            [
                'label'     => esc_html__( 'Content', 'ultraaddons-elementor-lite' ),
            ]
        );
        $this->add_control(
			'_ua_skill_title', [
				'label' => __( 'Skill Name', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'WordPress' , 'ultraaddons-elementor-lite' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'_ua_data_percent',
			[
				'label' => __( 'Skill Percent', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 100,
				'step' => 5,
				'default' =>80,
			]
		);
        $this->end_controls_section();
    }
	/**
	 * Chart JS Options values set here.
	 */
	protected function Skill_Chart_settings(){
		$this->start_controls_section(
            'skill_pie_settings',
            [
                'label'     => esc_html__( 'Control Settings', 'ultraaddons-elementor-lite' ),
            ]
        );

        
		$this->add_control(
			'lineWidth',
			[
				'label' => __( 'Line Width', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 2,
				'max' => 50,
				'step' => 2,
				'default' =>2,
				'frontend_available' => true,
			]
		);
		$this->add_control(
			'size',
			[
				'label' => __( 'Size', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 100,
				'max' => 500,
				'step' => 10,
				'default' => 160,
				'frontend_available' => true,
			]
		);
		$this->add_control(
			'animate',
			[
				'label' => __( 'Animation Speed', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 100,
				'max' => 5000,
				'step' => 100,
				'default' => 1200,
				'frontend_available' => true,
			]
		);
		$this->add_control(
			'scaleColor', [
				'label' => __( 'Scale Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'=>'#ddd',
				'frontend_available' => true,
			]
        );
		
		$this->add_control(
			'barColor', [
				'label' => __( 'Bar Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'=>'#27CCC0',
				'frontend_available' => true,
			]
        );
		$this->add_control(
			'trackColor', [
				'label' => __( 'Bar Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'=>'#ddd',
				'frontend_available' => true,
			]
        );
        $this->add_control(
			'lineCap',
			[
				'label' => __( 'Line Cap', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'round',
				'frontend_available' => true,
				'options' => [
					'round'  => __( 'Round', 'ultraaddons-elementor-lite' ),
					'butt' => __( 'Butt', 'ultraaddons-elementor-lite' ),
					'square' => __( 'Square', 'ultraaddons-elementor-lite' ),
				],
			]
		);
		$this->end_controls_section();
	}
	protected function skill_chart_style() {
		
        $this->start_controls_section(
            '_ua_skill_chart_style',
            [
                'label'     => esc_html__( 'Content', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'skill_name_typography',
					'label' => 'Skill Typography',
					'selector' => '{{WRAPPER}} .ua-skill-chart',

			]
        );
		$this->add_control(
			'_ua_skill_chart_text', [
				'label' => __( 'Skill Text Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'=>'#333',
				'selectors' => [
						'{{WRAPPER}} .ua-skill-chart' => 'color: {{VALUE}};'
				],
			]
        );
		
		$this->end_controls_section();
	}
	
	/**
	 * Render Methods
	 */
    protected function render() {
        $settings =	$this->get_settings_for_display();
        ?>
	 <div class="ua-skill-chart" data-percent="<?php echo esc_attr( $settings['_ua_data_percent'] ); ?>">
		<?php echo esc_html( $settings['_ua_skill_title'] );?>
	 </div>
	<?php }
}