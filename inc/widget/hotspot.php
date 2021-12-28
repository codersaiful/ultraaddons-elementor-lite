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
        
        $repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'list_title', [
				'label' => esc_html__( 'Title', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'List Title' , 'ultraaddons' ),
				'label_block' => true,
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
						'list_title' => esc_html__( 'Title #1', 'ultraaddons' ),
						'list_content' => esc_html__( 'Item content. Click the edit button to change this text.', 'ultraaddons' ),
					],
					[
						'list_title' => esc_html__( 'Title #2', 'ultraaddons' ),
						'list_content' => esc_html__( 'Item content. Click the edit button to change this text.', 'ultraaddons' ),
					],
                    [
						'list_title' => esc_html__( 'Title #3', 'ultraaddons' ),
						'list_content' => esc_html__( 'Item content. Click the edit button to change this text.', 'ultraaddons' ),
					],
				],
				'title_field' => '{{{ list_title }}}',
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
        $settings           = $this->get_settings_for_display();
        ?>
        <section class="hotspots--wrapper">
            <img src="https://images.unsplash.com/photo-1494698853255-d0fa521abc6c?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2600&q=80" alt="black Macbook near black iPhone 7 Plus and black Apple Watch" class="hotspots--figure">
            <?php 
            if ( $settings['list'] ) {
                $count=0;
                foreach (  $settings['list'] as $item ) {
                    $count=$count+1;
            ?>
            <a class="hotspot hotspot--<?php echo  $count;?>" href="#">
                <span class="hotspot--title"><?php echo  $item['list_title'];?></span>
                <span class="hotspot--cta"></span>
            </a>
            <?php 
            }
        } ?>
        </section>
        <?php
        
    }
    
    
    
}
