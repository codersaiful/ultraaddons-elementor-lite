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

class Hotspot extends Base{
    
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
        return [ 'ultraaddons', 'post', 'page title', 'title' ];
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
         //For Style Section
         $this->style_controls();
    }
    
        
    /**
     * General Section for Content Controls
     * 
     * @since 1.0.0.9
     */
    protected function content_general_controls() {
        $placeholder_image = ULTRA_ADDONS_URL . 'assets/images/hotspot.jpg';
        $this->start_controls_section(
            'general_content',
            [
                'label'     => esc_html__( 'General', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
			'_hotspot_image',
			[
				'label' => __( 'Hotspot Image', 'ultraaddons' ),
				'type' => Controls_Manager::MEDIA,
				 'default' => [
						'url' => $placeholder_image,//Utils::get_placeholder_image_src(),
				], 
				'dynamic' => [
						'active' => true,
				],

			]
        );
        $repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'list_title', [
				'label' => esc_html__( 'Title', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'List Title' , 'ultraaddons' ),
				'label_block' => true,
			]
		);
        $repeater->add_control(
			'top',
			[
				'label' => esc_html__( 'Top Positions', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => '',
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}.ua-hotspot' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $repeater->add_control(
			'left',
			[
				'label' => esc_html__( 'Left Positions', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => '',
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}.ua-hotspot' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $repeater->add_control(
			'_hotspot_color', [
				'label' => __( 'Hotspot Background', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .ua-hotspot--cta' => 'background: {{VALUE}};',
				],
				'default'=>'#E90C03'
			]
        );
        $repeater->add_control(
			'_hotspot_title_bg', [
				'label' => __( 'Hotspot Title Background', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .hotspot--title' => 'background: {{VALUE}};',
						'{{WRAPPER}} {{CURRENT_ITEM}} .hotspot--title::after' => ' border-color: transparent transparent transparent {{VALUE}};',
				],
				'default'=>'#E90C03'
			]
        );
        $this->add_control(
			'list',
			[
				'label' => esc_html__( 'Repeater List', 'ultraaddons' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'list_title' => esc_html__( 'iPhone', 'ultraaddons' ),
					],
					[
						'list_title' => esc_html__( 'Mackbook', 'ultraaddons' ),
					],
                    [
						'list_title' => esc_html__( 'iWatch', 'ultraaddons' ),
					],
				],
				'title_field' => '{{{ list_title }}}',
			]
		);
        
        $this->end_controls_section();
    }
    /**
     * Style
     */
    protected function style_controls() {
        $this->start_controls_section(
            'style_content',
            [
                'label'     => esc_html__( 'Style', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
       
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => '_title_typography',
					'label' => 'Title Typography',
					'selector' => '{{WRAPPER}} .ua-hotspot--title',
			]
        );
        $this->add_responsive_control(
			'_ua_title_radius',
			[
				'label'       => esc_html__( 'Title Radius', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', '%' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-hotspot--title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        ?>
        <section class="ua-hotspot--wrapper">
             <img  class="ua-hotspots--figure" src="<?php echo  $settings['_hotspot_image']['url'];?>"/>
            <?php 
            if ( $settings['list'] ) {
                $count=0;
                foreach (  $settings['list'] as $item ) {
                    $count=$count+1;
            ?>
            <a class="ua-hotspot elementor-repeater-item-<?php echo $item['_id']; ?> ua-hotspot--<?php echo  $count;?>" href="#">
                <span class="ua-hotspot--title">
                    <?php echo  $item['list_title'];?>
                </span>
                <span class="ua-hotspot--cta"></span>
            </a>
            <?php 
            }
        } ?>
        </section>
        <?php
        
    }
    
    
    
}
