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
			'website_link',
			[
				'label' => esc_html__( 'Link', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'ultraaddons' ),
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => false,
					'custom_attributes' => '',
				],
                'separator' => 'after'
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
                'separator' => 'after'
			]
		);
        $repeater->add_control(
			'_hotspot_color', [
				'label' => __( 'Spot Background', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .ua-hotspot--cta' => 'background: {{VALUE}};',
                        '{{WRAPPER}} {{CURRENT_ITEM}} .ua-hotspot--cta:before' => ' border-color:{{VALUE}};',
				],
				'default'=>'#E90C03'
			]
        );
        $repeater->add_control(
			'_hotspot_title_bg', [
				'label' => __( 'Spot Title Background', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .ua-hotspot--title' => 'background: {{VALUE}};',
						'{{WRAPPER}} {{CURRENT_ITEM}} .ua-hotspot--title::after' => ' border-color: transparent transparent transparent {{VALUE}};',
				],
				'default'=>'#E90C03'
			]
        );
        $repeater->add_control(
			'_hotspot_title_text_color', [
				'label' => __( 'Title Text Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .ua-hotspot--title' => 'color: {{VALUE}};',
				],
				'default'=>'#fff'
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
        $this->add_responsive_control(
			'_ua_spot_radius',
			[
				'label'       => esc_html__( 'Spot Radius', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', '%' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-hotspot--cta' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->add_control(
			'size',
			[
				'label' => esc_html__( 'Spot Size', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
				],
				'default' => [
					'size' => '35',
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .ua-hotspot--cta' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
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
        <div class="ua-hotspot--wrapper">
             <img  class="ua-hotspots--figure" src="<?php echo esc_url($settings['_hotspot_image']['url']);?>"/>
            <?php 
            if ( $settings['list'] ) {
                $count=0;
                foreach (  $settings['list'] as $item ) {
                     $count		= $count+1;
                     $url		= (!empty( $item['website_link']['url'] )) ? $item['website_link']['url']  : '';
					 $is_external 	= ( $item['website_link']['is_external']=='on') ? 'target="_blank"' : '';
					 $nofollow 	= ( $item['website_link']['nofollow']=='on') ? 'rel="nofollow"' :'';
            ?>
			<?php
			if(!empty($url)):
			?>
				<a href="<?php echo esc_url($url); ?>" <?php echo esc_attr($is_external);?> <?php echo esc_attr($nofollow);?> class="ua-hotspot elementor-repeater-item-<?php echo $item['_id']; ?> ua-hotspot--<?php echo  $count;?>">
					<span class="ua-hotspot--title"><?php echo $item['list_title'];?></span>
					<span class="ua-hotspot--cta"></span>
				</a>
            <?php 
			else:?>
				<div class="ua-hotspot elementor-repeater-item-<?php echo $item['_id']; ?> ua-hotspot--<?php echo $count;?>">
					<span class="ua-hotspot--title"><?php echo $item['list_title'];?></span>
					<span class="ua-hotspot--cta"></span>
				</div>
			<?php 
			endif;
            }
        } ?>
        </div>
        <?php
        
    }
    
    
    
}
