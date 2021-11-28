<?php 
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Work Hour Widget
 * Create Nice Working or Business hour with this Widget. 
 * Credit: 
 * @since 1.1.0.8
 * @package UltraAddons
 * @author Saiful islam <codersaiful@gmail.com>
 * @author Rafiul <bmrafiul.alam@gmail.com>
 */

class Work_Hour extends Base{
	
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
        return [ 'ultraaddons', 'work', 'hour', 'business'];
    }
	 protected function _register_controls() {
        //For Content
        $this->work_hour_content();
		 //For Control Section
        $this->work_hour_style();
    }

	/**
	 * Frontend text and values.
	 */
	protected function work_hour_content(){
        $this->start_controls_section(
            'wh_content_tab',
            [
                'label'     => esc_html__( 'Content', 'ultraaddons' ),
            ]
        );
        $this->add_control(
			'_ua_wh_title', [
				'label' => __( 'Office Hour', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Office Hour' , 'ultraaddons' ),
				'label_block' => true,
			]
		);
		$repeater = new \Elementor\Repeater();
		
		$repeater->add_control(
			'_ua_wh_day', [
				'label' => __( 'Day', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Monday' , 'ultraaddons' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'_ua_wh_time', [
				'label' => __( 'Time', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( '08:00 - 19:00' , 'ultraaddons' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'_ua_wh_list',
			[
				'label' => __( 'Work Hour List', 'ultraaddons' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'_ua_wh_day' => __( 'Monday', 'ultraaddons' ),
					],
					[
						'_ua_wh_day' => __( 'Tuesday', 'ultraaddons' ),
					],
				],
				'title_field' => '{{{ _ua_wh_day }}}',
			]
		);
		
        $this->end_controls_section();
    }
	/**
	 * Style.
	 */
	protected function work_hour_style(){
		$this->start_controls_section(
            'wh_style',
            [
                'label'     => esc_html__( 'Control Settings', 'ultraaddons' ),
            ]
        );
		
		$this->add_control(
			'scaleColor', [
				'label' => __( 'Scale Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'default'=>'#ddd',
				'frontend_available' => true,
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
	 <div class="ua-work-hours">
		<div class="ua-work-hours-row">
			<span class="ua-work-day">
				Monday - Friday 
			</span>
			<span class="ua-work-timing">
				09:00 AM - 06:00 PM 
			</span>
		</div>
	</div>
	<?php }
}