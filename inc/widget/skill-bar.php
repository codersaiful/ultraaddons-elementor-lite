<?php
namespace UltraAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Repeater;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skill_Bar extends Base{
    
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

            //Naming of Barfiller
            $ua_name           = 'barfiller';
            $js_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/js/barfiller.js';
            $dependency     =  ['jquery'];//['jquery'];
            $version        = ULTRA_ADDONS_VERSION;
            $in_footer  = true;

            wp_register_script( $ua_name, $js_file_url, $dependency, $version, $in_footer );
            wp_enqueue_script( $ua_name );

            //Third-party CSS file Load
            wp_register_style( 'barfiller', ULTRA_ADDONS_ASSETS . 'vendor/css/barfiller.css', array(), ULTRA_ADDONS_VERSION );
            wp_enqueue_style('barfiller' );

        }

        /**
         * By Saiful Islam
         * depend css for this widget
         * 
         * @return Array
         */
        public function get_style_depends() {
            return ['barfiller'];
        }

        

        /**
         * Get your widget by keywords
         *
         * @since 1.0.0
         * @access public
         *
         * @return string keywords
         */
        public function get_keywords() {
            return [ 'ultraaddons-elementor-lite', 'ua', 'skill bar', 'progress bar', 'skill' ];
        }

        /**
         * Retrieve the list of scripts the skill bar widget depended on.
         *
         * Used to set scripts dependencies required to run the widget.
         *
         * @since 1.0.0.13
         * @access public
         *
         * @return array Widget scripts dependencies.
         * @by Saiful
         */
        public function get_script_depends() {
                return [ 'jquery','barfiller' ];
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
            $this->style_general_controls();

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
        
                $this->add_render_attribute( 'wrapper', [
                        'class' => 'ua-skillbar-wrapper',
                ] );


                ?>
                <div <?php echo esc_attr( $this->get_render_attribute_string( 'wrapper' ) ); ?>>


                    <ul class="bars">
                        <?php
			foreach ( $settings['progress_list'] as $index => $ua_item ) :
                                $repeater_setting_key = $this->get_repeater_setting_key( 'title', 'progress_list', $index );
                                $_id = !empty( $ua_item['_id'] ) ? $ua_item['_id'] : false;
                                
                                $progress_percentage = is_numeric( $ua_item['percent']['size'] ) ? $ua_item['percent']['size'] : '0';
                                if ( 100 < $progress_percentage ) {
                                        $progress_percentage = 100;
                                }

                                $this->add_render_attribute( $repeater_setting_key . '.title', [
                                        'class' => 'heading',
                                ]);

                                $bar_color = isset( $ua_item['repeater_bar_color'] ) && !empty( $ua_item['repeater_bar_color'] ) ? $ua_item['repeater_bar_color'] : false;
                                $this->add_render_attribute( $repeater_setting_key . '.skill', [
                                        'class' => 'ua-skill-wrapper ua-repeater-skils elementor-repeater-item-' . esc_attr( $_id ),
                                        'role' => 'progressbar',
                                        'aria-valuemin' => '0',
                                        'aria-valuemax' => '100',
                                        'aria-id' => esc_attr( $_id ),
                                        'aria-color' => $bar_color,
                                        'aria-valuenow' => $progress_percentage,
                                        'aria-valuetext' => $ua_item['title'], 
                                ] );

                                $this->add_render_attribute( $repeater_setting_key . '.progress-bar', [
                                        'class' => 'barfiller-wrapper',
                                        'data-max' => $progress_percentage,
                                ] );


//                                $this->add_inline_editing_attributes( $repeater_setting_key . '.title', 'none' );
				?>
                                <li <?php echo esc_attr( $this->get_render_attribute_string( $repeater_setting_key . '.skill' ) ); ?>>
                                    <div <?php echo esc_attr( $this->get_render_attribute_string( $repeater_setting_key . '.title' ) ); ?>><?php echo esc_html( $ua_item['title'] ); ?></div>
                                    <div <?php echo esc_attr( $this->get_render_attribute_string( $repeater_setting_key . '.progress-bar' ) ); ?>>
                                        <div id="bar-<?php echo esc_attr( $this->get_id() . '-' .$_id . '-' . ( $index + 1 ) );?>" class="barfiller">
                                            <span class="fill" data-percentage="<?php echo esc_attr( $progress_percentage ); ?>"></span>
                                        </div>
                                        <?php if ( 'yes' == $settings['display_percentage'] ) { ?>
                                        <div class="progress-score"><?php echo esc_html( $progress_percentage ); ?>%</div>
                                        <?php } ?>
                                    </div>
                                </li>
                                <?php
			endforeach;
			?>

                    </ul>

                </div>
                <?php
        
        }
    
        /**
	 * Render progress widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.9.0
	 * @access protected
	 */
	protected function content_template() {}
    
        /**
         * General Section for Content Controls
         * 
         * @since 1.0.0.9
         */
        protected function content_general_controls() {
        
            $this->start_controls_section(
                        'section_progress',
			[
				'label' => __( 'Progress Bar', 'ultraaddons-elementor-lite' ),
			]
                );
        
                $repeater = new Repeater();
                $repeater->add_control(
                        'title',
			[
				'label' => __( 'Title', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'Enter your title', 'ultraaddons-elementor-lite' ),
				'default' => __( 'My Skill', 'ultraaddons-elementor-lite' ),
				'label_block' => true,
			]
                );
                
            $repeater->add_control(
			'percent',
			[
				'label' => __( 'Percentage', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 50,
					'unit' => '%',
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);
                
        $repeater->add_control(
			'more_options_1',
			[
				'label' => __( 'Color', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
                
                $repeater->add_control(
			'repeater_bar_color',
			[
				'label' => __( 'Bar Color', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .barfiller .fill' => 'background-color: {{VALUE}} !important;',
				],
			]
		);
                
                $repeater->add_control(
			'repeater_bar_bg_color',
			[
				'label' => __( 'Background Color', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .barfiller' => 'background-color: {{VALUE}};',
				],
			]
		);
                
                $repeater->add_control(
			'more_options_2',
			[
				'label' => __( 'Text Color', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
                
        $repeater->add_control(
			'repeater_title_color',
			[
				'label' => __( 'Text Color', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .heading' => 'color: {{VALUE}};',
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'repeater_typography',
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .heading',
			]
		);
                
                $this->add_control(
			'progress_list',
			[
				'label' => __( 'Progress Bars', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'title' => __( 'My Skill #1', 'ultraaddons-elementor-lite' ),
                                                'percent' => [
                                                        'size' => 80,
                                                        'unit' => '%',
                                                ],
                                                'repeater_bar_color' => 'red',
					],
					[
						'title' => __( 'My Skill #2', 'ultraaddons-elementor-lite' ),
                                                'percent' => [
                                                        'size' => 70,
                                                        'unit' => '%',
                                                ],
                                                'repeater_bar_color' => 'green',
					],
					[
						'title' => __( 'My Skill #3', 'ultraaddons-elementor-lite' ),
                                                'percent' => [
                                                        'size' => 65,
                                                        'unit' => '%',
                                                ],
                                                'repeater_bar_color' => 'purple',
					],
					[
						'title' => __( 'My Skill #4', 'ultraaddons-elementor-lite' ),
                                                'percent' => [
                                                        'size' => 75,
                                                        'unit' => '%',
                                                ],
                                                'repeater_bar_color' => 'blue',
					],
				],
				'title_field' => '{{{ title }}}',
			]
		);
                
                $this->add_control( 
                        'display_percentage',
                        [
                                'label' => __( 'Display Percentage', 'ultraaddons-elementor-lite' ),
                                'type' => Controls_Manager::SWITCHER,
                                'label_on' => __( 'Show', 'ultraaddons-elementor-lite' ),
				'label_off' => __( 'Hide', 'ultraaddons-elementor-lite' ),
                                'return_value' => 'yes',
                                'default' => 'yes',
                        ]
                );
        
               
        
                $this->end_controls_section();
        }
    
        /**
         * Alignment Section for Style Tab
         * 
         * @since 1.0.0.9
         */
        protected function style_general_controls() {
                
                $this->start_controls_section(
			'section_progress_style',
			[
				'label' => __( 'Progress Bar', 'ultraaddons-elementor-lite' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
                
               /*  $this->add_control(
			'bar_bg_color',
			[
				'label' => __( 'Background Color', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .barfiller' => 'background-color: {{VALUE}};',
				],
			]
		); */
                
                $this->add_control(
			'bar_height',
			[
				'label' => __( 'Height', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .barfiller .fill' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .barfiller' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);
                
                $this->add_control(
			'bar_border_radius',
			[
				'label' => __( 'Border Radius', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .barfiller .fill' => 'border-radius: {{SIZE}}{{UNIT}}; overflow: hidden;',
					'{{WRAPPER}} .barfiller' => 'border-radius: {{SIZE}}{{UNIT}}; overflow: hidden;',
				],
			]
		);
       
                $this->end_controls_section();
                
                $this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Text Style', 'ultraaddons-elementor-lite' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
                
                $this->add_control(
			'title_more_options',
			[
				'label' => __( 'Title Text Options', 'ultraaddons-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::HEADING,
			]
		);
                
                $this->add_control(
			'title_color',
			[
				'label' => __( 'Text Color', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .heading' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} .heading',
			]
		);
                
//                $this->add_control(
//			'hr',
//			[
//				'type' => Controls_Manager::DIVIDER,
//                                'separator' => 'before',
//			]
//		);
                
                $this->add_control(
			'more_options',
			[
				'label' => __( 'Percentage Options', 'ultraaddons-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
                
                $this->add_control(
			'percentage_color',
			[
				'label' => __( 'Text Color', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .progress-score' => 'color: {{VALUE}};',
				],
			]
		);
                
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'percentage_typography',
				'selector' => '{{WRAPPER}} .progress-score',
			]
		);

		$this->end_controls_section();
        }
    
}
