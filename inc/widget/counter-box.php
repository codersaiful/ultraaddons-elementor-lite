<?php
namespace UltraAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Repeater;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Counter_Box extends Base{
    
        /**
         * mainly to call specific depends
         * we have called this __construct() method
         * 
         * @param Array $data
         * @param Array $args
         * 
         * @by Saiful Islam
         */
        public function __construct($data = [], $args = null) {
            parent::__construct($data, $args);

             //Naming Args For jQuery.Apear
            $name           = 'appear';
            $js_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/js/jquery.appear.js';
            $dependency     =  ['jquery'];//['jquery'];
            $version        = ULTRA_ADDONS_VERSION;
            $in_footer  = false;

            wp_register_script( $name, $js_file_url, $dependency, $version, $in_footer );
            wp_enqueue_script( $name );

            //Naming Args For jQuery.Apear
            $name           = 'jquery-count-to';
            $js_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/js/jquery-count-to.js';
            $dependency     =  ['jquery'];//['jquery'];
            $version        = ULTRA_ADDONS_VERSION;
            $in_footer  = false;

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
                return [ 'jquery','appear','jquery-count-to' ];
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
        return [ 'ultraaddons','ua' ,'counter', 'count', 'number', 'countdown'];
    }
    
    /**
     * Totally new Created
     * We will use this format to Add
     * Additional JS Library
     * for our Element/Widget
     * 
     * @return Array
     */
    public function ultraaddons_settings(){
        return [
            'additional_scripts'       => [
                'ua-count-to' =>    [
                    'url'               =>  MEDILAC_CORE_BASE_URL . 'assets/vendor/js/jquery-count-to.js',
                    'dependency'        =>  ['jquery'],
                    'version'           => false,
                    'in_footer'         =>  true,
                ],
                'ua-appear' =>    [
                    'url'               =>  MEDILAC_CORE_BASE_URL . 'assets/vendor/js/jquery.appear.js',
                    'dependency'        =>  ['jquery'],
                    'version'           => false,
                    'in_footer'         =>  true,
                ],
            ],
            'dependency'       =>  ['jquery','ua-count-to'],
            'version'       =>  '1.0.0.12',
            'in_footer'     =>  true,
        ];
    }
    
    
    /**
     * Register oEmbed widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls() {
        //For General Section
        $this->content_general_controls();

        //For Design Section Style Tab
        $this->style_design_controls();
        
        $this->style_color_controls();
        
        //For Typography Section Style Tab
        $this->style_typography_controls();

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
        
        
        $repeater = new Repeater();
        
        $default_icon = [
                                'value' => 'far fa-check-square',
                                'library' => 'regular',
                        ];
        
        $repeater->add_control(
                'icon',
                [
                        'label'     => __( 'Icon', 'ultraaddons' ),
                        'type'      => Controls_Manager::ICONS,
                        'default'   => $default_icon,
                ]
        );
        
        $repeater->add_control(
                'label',
                [
                        'label'     => __( 'Label', 'ultraaddons' ),
                        'type'      => Controls_Manager::TEXT,
                        'default'   => __( 'Product', 'ultraaddons' ),
                ]
        );
        
        
        $repeater->add_control(
                'data_to',
                [
                        'label'     => __( 'Upto Number', 'ultraaddons' ),
                        'type'      => Controls_Manager::NUMBER,
                        'default'   => 100,
                ]
        );
        
        $repeater->add_control(
                'data_from',
                [
                        'label'     => __( 'Number From', 'ultraaddons' ),
                        'type'      => Controls_Manager::NUMBER,
                        'default'   => 0,
                ]
        );
        
        $repeater->add_control(
                'speed',
                [
                        'label'     => __( 'Speed (ms)', 'ultraaddons' ),
                        'type'      => Controls_Manager::NUMBER,
                        'default'   => 1200,
                ]
        );
        
        $repeater->add_control(
                'customize_colors',
                [
                        'label' => __( 'Customize Colors?', 'ultraaddons' ),
                        'type' => Controls_Manager::SWITCHER,
                        'label_on' => __( 'Yes', 'ultraaddons' ),
                        'label_off' => __( 'No', 'ultraaddons' ),
                        'return_value' => 'yes',
                ]
        );
        
        $repeater->add_control(
            'counter_bg_color',
            [
                'label'     => __( 'Counter Background', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-counter-wrapper .ua-counter-box {{CURRENT_ITEM}}' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'customize_colors' => 'yes',
                ],
                
            ]
        );
        
        $repeater->add_control(
            'counter_color',
            [
                'label'     => __( 'Counter Text Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-counter-wrapper .ua-counter-box {{CURRENT_ITEM}} .ua-counter-text .ua-counter-value, {{WRAPPER}} .ua-counter-wrapper .ua-counter-box {{CURRENT_ITEM}} .ua-counter-text .ua-counter-pluss, {{WRAPPER}} .ua-counter-wrapper .ua-counter-box {{CURRENT_ITEM}} .ua-counter-text .me-counter-label' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'customize_colors' => 'yes',
                ],                
            ]
        );
        
        $repeater->add_control(
            'counter_icon_color',
            [
                'label'     => __( 'Icon Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-counter-wrapper .ua-counter-box {{CURRENT_ITEM}} .ua-counter-icon span i' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'customize_colors' => 'yes',
                ],                
            ]
        );
        
        $repeater->add_control(
            'counter_icon_bg_color',
            [
                'label'     => __( 'Icon Background Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-counter-wrapper .ua-counter-box {{CURRENT_ITEM}} .ua-counter-icon span' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'customize_colors' => 'yes',
                ],                
            ]
        );
        
        ////icon label data_to data_from counter_bg_color counter_color
        $this->add_control(
                'counters',
                [
                        'type' => Controls_Manager::REPEATER,
                        'fields' => $repeater->get_controls(),
                        'default' => [
                                [
                                        'icon' => $default_icon,
                                        'label' => __( 'Item #1', 'ultraaddons' ),
                                        'data_to'   =>  1200,
                                        'data_from' =>  500,
                                        'speed' =>  500,
                                ],
                                
                                [
                                        'icon' => $default_icon,
                                        'label' => __( 'Item #2', 'ultraaddons' ),
                                        'data_to'   =>  500,
                                        'data_from' =>  100,
                                        'speed'     =>  1000,
                                ],
                                
                                [
                                        'icon' => $default_icon,
                                        'label' => __( 'Item #3', 'ultraaddons' ),
                                        'data_to'   =>  2500,
                                        'data_from' =>  0,
                                        'speed' =>  5000,
                                ],
                                
                                [
                                        'icon' => $default_icon,
                                        'label' => __( 'Item #3', 'ultraaddons' ),
                                        'data_to'   =>  3000,
                                        'data_from' =>  2000,
                                        'speed' =>  3000,
                                ],
                                
                                
                                
                        ],
                        'title_field' => '{{{ label }}}',
                ]
        );
        
               
        
        $this->end_controls_section();
    }
    
    /**
     * Alignment Section for Style Tab
     * 
     * @since 1.0.0.9
     */
    protected function style_design_controls() {
        $this->start_controls_section(
            'style_general',
            [
                'label'     => esc_html__( 'Design', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'title_align',
            [
                'label' => esc_html__( 'Alignment', 'ultraaddons' ),
                'type' => Controls_Manager::CHOOSE,
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
                'default'       => 'center',
                'selectors'     => [
                    '{{WRAPPER}} .ua-counter-text' => 'text-align: {{value}};',
                    '{{WRAPPER}} .ua-counter-icon' => 'justify-content: {{value}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'list-column',
                [
                    'label'         => esc_html__( 'Column', 'ultraaddons' ),
                    'type'          => Controls_Manager::SELECT,
                    'options' => [
                            '100%'     => __( 'One Column', 'ultraaddons' ),
                            '50%'     => __( 'Two Column', 'ultraaddons' ),
                            '33.33%'     => __( 'Three Column', 'ultraaddons' ),
                            '25%'     => __( 'Four Column', 'ultraaddons' ),
                    ],
                    'selectors' => [
                                        '{{WRAPPER}} .ua-counter-wrapper .ua-counter-item' => 'width: {{VALUE}};',
                                ],
                ]
        );
        
        
        $this->add_responsive_control(
                'padding',
                [
                        'label' => __( 'Padding', 'ultraaddons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%' ],
                        'default'   => [
                                'size' => 0,
                                'unit' => 'px',
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .ua-counter-item .ua-counter-item-inside' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ]
        );
 
        $this->end_controls_section();
    }
    
    /**
     * Color Control for Style Tab
     * 
     * @since 1.0.0.9
     */
    protected function style_color_controls() {
        
        $this->start_controls_section(
            'color_control',
            [
                'label'     => esc_html__( 'Colors', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'global_counter_bg_color',
            [
                'label'     => __( 'Counter Background', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-counter-wrapper .ua-counter-box' => 'background-color: {{VALUE}}',
                ],
                'default'   => '#0fc392',
                
            ]
        );
        
        $this->add_control(
            'global_counter_color',
            [
                'label'     => __( 'Counter Text Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-counter-wrapper .ua-counter-box .ua-counter-text .ua-counter-value, {{WRAPPER}} .ua-counter-wrapper .ua-counter-box .ua-counter-text .ua-counter-pluss, {{WRAPPER}} .ua-counter-wrapper .ua-counter-box .ua-counter-text .me-counter-label' => 'color: {{VALUE}}',
                ],
                'default'   => '#fff',
                
            ]
        );
        
        $this->add_control(
            'global_counter_icon_color',
            [
                'label'     => __( 'Icon Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-counter-wrapper .ua-counter-box .ua-counter-icon span i' => 'color: {{VALUE}}',
                ],
                'default'   => '#0fc392',
                
            ]
        );
        
        $this->add_control(
            'global_counter_icon_bg_color',
            [
                'label'     => __( 'Icon Background Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-counter-wrapper .ua-counter-box .ua-counter-icon span' => 'background-color: {{VALUE}}',
                ],
                
            ]
        );
        
        
        $this->end_controls_section();
        
    }
    
    /**
     * Typography Section for Style Tab
     * 
     * @since 1.0.0.9
     */
    protected function style_typography_controls() {
        $this->start_controls_section(
            'typography',
            [
                'label'     => esc_html__( 'Typography', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'int_typography',
                        'label' => 'Number Typography',
                        'selector' => '{{WRAPPER}} .ua-counter-wrapper .ua-counter-value,{{WRAPPER}} .ua-counter-wrapper .ua-counter-pluss',
                ]
        );
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'label_typography',
                        'label' => 'Label Typography',
                        'selector' => '{{WRAPPER}} .ua-counter-wrapper .ua-counter-text .me-counter-label',
//                        'global' => [
//                                'default' => Global_Typography::TYPOGRAPHY_ACCENT,
//                        ],

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

        $this->add_render_attribute( 'wrapper', 'class', 'ua-counter-wrapper' );

        $items = $settings['counters'];
        ?>
    <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
        
        <div class="ua-counter-box">
            <?php
            $serial = 1;
            foreach( $items as $key => $item ){
                //icon label data_to data_from counter_bg_color counter_color speed
                $_id = !empty( $item['_id'] ) ? $item['_id'] : false;
                $label = !empty( $item['label'] ) ? $item['label'] : false;
                $data_to = !empty( $item['data_to'] ) ? $item['data_to'] : 100;
                $data_from = !empty( $item['data_from'] ) ? $item['data_from'] : 0;
                $speed = !empty( $item['speed'] ) ? $item['speed'] : 1000;
                $icon     = !empty( $item['icon']['value'] ) && is_string( $item['icon']['value'] ) ? $item['icon']['value'] : false;

                //.ua-counter-item .ua-counter-item-inside
            ?>
            <div class="ua-counter-item list-item-<?php echo esc_attr( $serial ); ?> elementor-repeater-item-<?php echo esc_attr( $_id ); ?>">
                <div class="ua-counter-item-inside">
                    <?php if( $icon ){  ?>
                    <div class="ua-counter-icon icon-<?php echo $settings['title_align']; ?>">
                        <span>
                        <i class="<?php echo esc_attr( $icon ); ?>"></i>
                        </span>
                    </div>
                    <?php } ?> 
                    <div class="ua-counter-text">
                        <div class="ua-counter-number-area">
                            <h6 class="ua-counter-value" 
                                data-from="<?php echo esc_attr( $data_from ); ?>" 
                                data-to="<?php echo esc_attr( $data_to ); ?>" 
                                data-speed="<?php echo esc_attr( $speed ); ?>"
                                >
                                <span><?php echo esc_html( $data_to ); ?></span>
                            </h6>
                            <span class="ua-counter-pluss">+</span>
                        </div>
                        <?php if( $label ){  ?>
                        <p class="me-counter-label"><?php echo esc_html( $label ); ?></p>
                        <?php } ?>  
                    </div>
                </div>
            </div>
            <?php
                
                $serial++;
            }
            ?>


        </div>
        
    </div>
        <?php
        
    }
       
    
}