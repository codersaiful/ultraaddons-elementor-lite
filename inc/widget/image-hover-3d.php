<?php
namespace UltraAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Repeater;
use Elementor\Utils;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Image_Hover_3D extends Base{
    
    /**
     * Get your widget name
     *
     * @since 1.1.0.8
     * @access public
     *
     * @return string keywords
     */
    public function get_keywords() {
        return [ 'ultraaddons','ua', '3d', 'image', 'magic', '3dimage','magic image'];
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

        $this->style_basic();
        $this->style_hover_box();
        
    
       
    }
    


    /**
     * Render whole project
     *
     * @since 1.1.0.8
     * @access protected
     */
    protected function render() {

        
        $settings           = $this->get_settings_for_display();
        

        if(empty($settings['images'])) return;

        ?>
        <div class="ua-3d-image-hover-wrapper">
            <?php
            foreach($settings['images'] as $image){
            ?>
            <div class="ua-3dimage-box 3dimage-box-<?php echo $image['_id']; ?>">
                <div class="ua-3dimage-imgbx">
                    <?php if( ! empty( $image['image']['url'] ) ){ ?>
                    <img src="<?php echo esc_url( $image['image']['url'] ); ?>">
                    <?php } ?>
                </div>
                <div class="ua-3dimage-content">
                    <div class="ua-3dimage-content-inside">
                        <h2><?php echo $image['title']; ?></h2>
                        <p><?php echo $image['content']; ?></p>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
            
            
        </div>
        <?php
        
    }


    /**
     * General Section for Content Controls
     * 
     * @since 1.0.0.9
     */
    protected function content_general_controls() {
    
        $this->start_controls_section(
            'general',
            [
                'label' => __( 'General', 'ultraaddons' ),
            ]
        );

        $repeater = new Repeater();
        $repeater->add_control(
                'image',
                [
                        'label' => __( 'Background Image', 'ultraaddons' ),
                        'type' => Controls_Manager::MEDIA,
                        'default' => [
                                'url' => Utils::get_placeholder_image_src(),
                        ],
                        'dynamic' => [
                                'active' => true,
                        ],

                ]
        );
        $repeater->add_control(
                'title',
                [
                    'label' => __( 'Title', 'ultraaddons' ),
                    'type' => Controls_Manager::TEXT,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'placeholder' => __( 'Enter your title', 'ultraaddons' ),
                    'default' => __( 'Slider Title Text', 'ultraaddons' ),
                    'label_block' => true,
                ]                                       
        );
        
        $repeater->add_control(
                'content',
                [
                    'label' => __( 'Content', 'ultraaddons' ),
                    'type' => Controls_Manager::TEXT,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'placeholder' => __( 'Enter your content...', 'ultraaddons' ),
                    'default' => __( "Lorem Ipsum is simply dumy text of the printing & typesetting industry Lorem Ipsum has been the industry's standard dummy text ever since the 1975, when an unknown printer.", 'ultraaddons' ),
                    'label_block' => true,
                ]
        );

        $this->add_control(
                'images',
                [
                    'label' => __( 'Image and content', 'ultraaddons' ),
                    'type' => Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'default' => [
                        [
                            'image' => Utils::get_placeholder_image_src(),
                            'title' => __( 'Image Title', 'ultraaddons' ),
                            'content' => __( 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi accusamus molestias quidem iusto.', 'ultraaddons' ),
                                                    
                        ],
                                        
                        [
                            'image' => Utils::get_placeholder_image_src(),
                            'title' => __( 'Image Title', 'ultraaddons' ),
                            'content' => __( 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi accusamus molestias quidem iusto.', 'ultraaddons' ),
                                                    
                        ],
                                        
                        [
                            'image' => Utils::get_placeholder_image_src(),
                            'title' => __( 'Image Title', 'ultraaddons' ),
                            'content' => __( 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi accusamus molestias quidem iusto.', 'ultraaddons' ),
                                                    
                        ],
                                        
                        [
                            'image' => Utils::get_placeholder_image_src(),
                            'title' => __( 'Image Title', 'ultraaddons' ),
                            'content' => __( 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi accusamus molestias quidem iusto.', 'ultraaddons' ),
                                                    
                        ],
                                        
                        
                    ],
                    'title_field' => '{{{ title }}}',
                ]
            );
            $this->end_controls_section();
    }
    

    protected function style_basic(){
        $this->start_controls_section(
            'basic',
            [
                'label' => __( 'Basic Box', 'ultraaddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'basic_height',
            [
                    'label' => __( 'Box Height', 'ultraaddons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                            'px' => [
                                    'min' => 100,
                                    'max' => 500,
                                    'step' => 1,
                            ],
                    ],
                    'default' => [
                            'unit' => 'px',
                            'size' => 275,
                    ],
                    'selectors' => [
                            '{{WRAPPER}} .ua-3d-image-hover-wrapper .ua-3dimage-box' => 'height: {{SIZE}}{{UNIT}};',
                    ],
            ]
        );
    
        $this->add_control(
            'basic_width',
            [
                    'label' => __( 'Box Width', 'ultraaddons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                            'px' => [
                                    'min' => 100,
                                    'max' => 500,
                                    'step' => 1,
                            ],
                    ],
                    'default' => [
                            'unit' => 'px',
                            'size' => 275,
                    ],
                    'selectors' => [
                            '{{WRAPPER}} .ua-3d-image-hover-wrapper .ua-3dimage-box' => 'width: {{SIZE}}{{UNIT}};',
                    ],
            ]
        );


        $this->end_controls_section();
    }

    protected function style_hover_box(){
        $this->start_controls_section(
            'hover_box',
            [
                'label' => __( 'Hover Box', 'ultraaddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'box_background',
                'title'=> 'Testing Title',
				'types' => [ 'classic', 'gradient', 'video', 'slideshow' ],
				'selector' => '{{WRAPPER}} .ua-3dimage-box .ua-3dimage-imgbx:before',
                'separator' => 'before',
				'fields_options' => [
					'background' => [
						'frontend_available' => true,
					],
					'color' => [
						'dynamic' => [],
					],
					'color_b' => [
						'dynamic' => [],
					],
				],
			]
		);

        $this->end_controls_section();
    }
    
}