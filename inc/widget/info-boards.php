<?php
namespace UltraAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;


class Info_Boards extends Base {

        /**
         * Get your widget by keywords
         *
         * @since 1.0.0
         * @access public
         *
         * @return string keywords
         */
        public function get_keywords() {
                return [ 'ultraaddons', 'info', 'box', 'boards', 'banner', 'notification' ];
        }

        /**
         * Register widget controls.
         *
         * Adds different input fields to allow the user to change and customize the widget settings.
         *
         * @since 1.0.0
         * @access protected
         */
        protected function _register_controls() {

                $this->content_general_controls();
                
                $this->style_general_controls();

        }


        /**
         * Render widget output on the frontend.
         *
         * Written in PHP and used to generate the final HTML.
         *
         * @since 1.0.0
         * @access protected
         */
        protected function render() {
                $settings = $this->get_settings_for_display();
                $this->add_render_attribute( 'wrapper', 'class', 'info-board-wrap' );
                $this->add_render_attribute( 'item', 'class', 'info-board bg-green' );
                
                $repeater = $settings['list_items'];
                ?>
                <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
                        
                        <?php
                        foreach( $repeater as $item ){ ?>
                        
                        <div class="info-board elementor-repeater-item-<?php echo $item['_id']; ?>">
                            
                                <div class="info-board-title">
                                    
                                        <?php if( ! empty( $item['icon']['value'] ) ) : ?>
                                    
                                            <i class="<?php echo esc_attr( $item['icon']['value'] ); ?>"></i>
                                            
                                        <?php endif; ?>
                                        
                                        <h4><?php echo esc_html( $item['title'] ); ?></h4>
                                </div>
                            
                                <div class="info-board-content">
                                    
                                        <?php echo wp_kses_post( $item['description'] ); ?>
                                    
                                        <?php echo $this->render_button( $item ); ?>
                                        
                                </div>
                        </div>
                    
                        <?php } ?>
                    
                </div>
                <?php
        }
        
        /**
         * 
         * @since 1.0.0
         * @access protected
         */
        protected function content_general_controls(){
            
                $this->start_controls_section(
                        '_section_content',
                        [
                                'label' => esc_html__( 'Info Boards', 'ultraaddons' ),
                                'tab' => Controls_Manager::TAB_CONTENT,
                        ]
                );

                $repeater = new Repeater();
                $default_icon = [
                                'value' => 'fas fa-arrow-right',
                                'library' => 'solid',
                        ];
                
                $repeater->add_control(
                        'bg_color', [
                                'label' => esc_html__( 'Background Color', 'ultraaddons' ),
                                'type'      => Controls_Manager::COLOR,
                                'default'   => '#0FC392',
                                'selectors' => [
                                        '{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}};',
                                ],
                        ]
                );
                $repeater->add_control(
                        'color', [
                                'label' => esc_html__( 'Color', 'ultraaddons' ),
                                'type'      => Controls_Manager::COLOR,
                                'default'   => '#FFF',
                                'selectors' => [
                                        '{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}};',
                                ],
                        ]
                );
                $repeater->add_control(
                        'icon', [
                                'label' => esc_html__( 'Icon', 'ultraaddons' ),
                                'type'      => Controls_Manager::ICONS,
                                'default'   => $default_icon,
                        ]
                );
                
                $repeater->add_control(
                        'title', [
                                'label' => esc_html__( 'Title', 'ultraaddons' ),
                                'type'      => Controls_Manager::TEXT,
                                'default' => '',
                        ]
                );
                
                $repeater->add_control(
                        'description', [
                                'label' => esc_html__( 'Content', 'ultraaddons' ),
                                'type'      => Controls_Manager::WYSIWYG,
                                'default' => '',
                        ]
                );
                
                $repeater->add_control(
                        'button', [
                                'label' => esc_html__( 'Button/Label', 'ultraaddons' ),
                                'type'      => Controls_Manager::TEXT,
                                'default' => '',
                                'label_block' => true,
                                'separator' => 'before',
                        ]
                );
                $repeater->add_control(
                        'link', 
                        [
                                'label' => esc_html__( 'Link', 'ultraaddons' ),
                                'type'      => Controls_Manager::URL,
                                'dynamic' => [
                                        'active' => true,
                                        ],
                                'placeholder' => esc_html__( 'https://your-link.com', 'ultraaddons' ),
                                'default' => [
                                        'url' => '',
                                ],
                                'condition' => [
                                        'button!' => '',
                                ],
                        ]
                );
                
                $repeater->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                                'name' => 'title_typography',
                                'label' => 'Title Typography',
                                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .info-board-title,{{WRAPPER}} {{CURRENT_ITEM}} .info-board-title h4',
                        ]
                );

                $repeater->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                                'name' => 'desc_typography',
                                'label' => 'Description Typography',
                                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .info-board-content',
                        ]
                );
                
                $this->add_control(
                            'list_items',
                            [
                                    'type' => Controls_Manager::REPEATER,
                                    'fields' => $repeater->get_controls(),
                                    'default' => [
                                            [
                                                    'bg_color' => '#0FC392',
                                                    'color' => '#FFF',
                                                    'icon' => [
                                                            'value' => 'fas fa-medkit',
                                                            'library' => 'solid',
                                                    ],
                                                    'title' => esc_html__( 'Opening Hours', 'ultraaddons' ),
                                                    'description' => esc_html__( '<ul><li><p>Fri-Sat</p><p>8AM - 10PM</p></li><li><p>Mon-Tue</p> <p>8AM - 10PM</p></li><li><p>Thu-Sun</p> <p>8AM - 10PM</p></li></ul>', 'ultraaddons' ),
                                                    'button' => esc_html__( 'Sunday Closed', 'ultraaddons' ),
                                                    'link' => [
                                                            'url' => '',
                                                    ],
                                            ],

                                            [
                                                    'bg_color' => '#021429',
                                                    'color' => '#FFF',
                                                    'icon' => [
                                                            'value' => 'fas fa-ambulance',
                                                            'library' => 'solid',
                                                    ],
                                                    'title' => esc_html__( 'Emergency Cases', 'ultraaddons' ),
                                                    'description' => esc_html__( '<p>Lorem ipsum dolor sit amet consect adipisicing a sed do eusmod tempor incididunt ut labore.</p>', 'ultraaddons' ),
                                                    'button' => esc_html__( 'Click Here', 'ultraaddons' ),
                                                    'link' => [
                                                            'url' => '#',
                                                    ],
                                            ],

                                            [
                                                    'bg_color' => '#0FC392',
                                                    'color' => '#FFF',
                                                    'icon' => [
                                                            'value' => 'far fa-clock',
                                                            'library' => 'regular',
                                                    ],
                                                    'title' => esc_html__( 'Doctor Timetable', 'ultraaddons' ),
                                                    'description' => esc_html__( '<p>Lorem ipsum dolor sit amet consect adipisicing a sed do eusmod tempor incididunt ut labore.</p>', 'ultraaddons' ),
                                                    'button' => esc_html__( 'Click Here', 'ultraaddons' ),
                                                    'link' => [
                                                            'url' => '#',
                                                    ],
                                            ],

                                    ],
                                    'title_field' => '{{{ title }}}',
                        ]
                );

                $this->end_controls_section();
        }
        
        protected function style_general_controls() {
                $this->start_controls_section(
                        '_section_style_general',
                        [
                                'label' => esc_html__( 'General', 'ultraaddons' ),
                                'tab' => Controls_Manager::TAB_STYLE,
                        ]
                );
                
                $this->add_responsive_control(
                    'info_board_column',
                        [
                            'label'         => esc_html__( 'Column', 'ultraaddons' ),
                            'type'          => Controls_Manager::SELECT,
                            'options' => [
                                    '100%'     => esc_html__( 'One Column', 'ultraaddons' ),
                                    '50%'     => esc_html__( 'Two Column', 'ultraaddons' ),
                                    '33.33%'     => esc_html__( 'Three Column', 'ultraaddons' ),
                                    '25%'     => esc_html__( 'Four Column', 'ultraaddons' ),
                            ],
                            'selectors' => [
                                        '{{WRAPPER}} .info-board' => 'width: {{VALUE}};',
                                ],
                        ]
                );
                
                $this->add_control(
                        'icon_size', [
                                'label' => esc_html__( 'Icon Size', 'ultraaddons' ),
                                'type'      => Controls_Manager::SLIDER,
                                'range' => [
                                        'px' => [
                                                'min' => 6,
                                                'max' => 72,
                                        ],
                                ],
                                'default' => [
                                        'size' => 30,
                                ],
                                'selectors' => [
                                        '{{WRAPPER}} .info-board-title i' => 'font-size: {{SIZE}}{{UNIT}};',
                                ],
                        ]
                );
                
                $this->end_controls_section();
        }
        
        protected function render_button( $settings ) {
            
                $has_button_link = ! empty( $settings['link']['url'] );
                
                $html = '';
                if( $has_button_link ) {
                    $this->add_link_attributes( 'link', $settings['link'] );
                    $this->add_render_attribute( 'link', 'class', 'ultraaddons-btn-link' );
                    $this->add_render_attribute( 'link', 'role', 'button' );
                    $html =  '<a '. $this->get_render_attribute_string( 'link' ) .'>'. esc_html( $settings['button'] ) .' <i class="fas fa-arrow-right"></i></a>';
                    return $html;
                }
                
                if( ! empty( $settings['button'] ) ){
                    $html = '<span class="ultraaddons-btn">' . esc_html( $settings['button'] ) . '</span>';
                    return $html;
                }
                
        }
}

