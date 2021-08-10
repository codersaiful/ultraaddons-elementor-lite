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
use \ELEMENTOR\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Timeline extends Base{
    
    
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
        return [ 'ultraaddons', 'timeline', 'progress', 'time', 'line' ];
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
        $this->ua_content_general_controls();
        $this->ua_timeline_content_skin_1_controls();
        $this->ua_timeline_content_skin_2_controls();
        $this->ua_register_timeline_options_controls();
        $this->ua_register_timeline_general_style_controls();
        $this->ua_register_timeline_icon_style_controls();
        $this->ua_register_timeline_border_line_style_controls();
        $this->ua_register_timeline_content_style_control();
        $this->ua_register_timeline_option_section_controls();
        $this->ua_register_timeline_navigation_controls();

       
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
        $settings               = $this->get_settings_for_display();
        $_ua_timeline_skin  = !empty( $settings['_ua_timeline_skin']) ?  $settings['_ua_timeline_skin'] : '_skin_1';

            switch ($_ua_timeline_skin) {
                case '_skin_1':
                    $this->_first_timeline_layout();
                    break; 
                case '_skin_2':
                    $this->_second_timeline_layout();
                    break;
                case '_skin_3':
                    $this->_third_timeline_layout();
                    break;
                default:
                    $this->_first_timeline_layout();
                    break;
            }

        ?>
        <!--HTML code will go here -->

        <?php
        
        
    }

    protected function _first_timeline_layout(){
        $settings = $this->get_settings_for_display();
        ?>
       <div class="ua_timeline_section ua_timeline_default_style ultraaddons-timeline-section ultraaddons-timeline-default-style">
            <div class="ua_timeline_section_inner ultraaddons-timeline-section-inner">
                <?php if ( $settings['_ua_timeline_items']):
                    foreach (  $settings['_ua_timeline_items'] as $index => $item ):
    
                        $item_count = $index + 1;
                        
                        /*Inner Wrapper*/
                        $timeline_id_key = $this->get_repeater_setting_key( '_id', '_ua_timeline_items', $index );
                        $this->add_render_attribute( $timeline_id_key, [
                            'id' => 'timeline-' . $item['_id'],
                            'class' => [ "ua_limeline_section_inner_wrapper", "elementor-repeater-item-{$item['_id']}", "ultraaddons-timeline-inner-wraper" ],
                            'data-item' => $item_count,
                        ] );
                        $timeline_inner_wraper = $this->get_render_attribute_string( $timeline_id_key );
    
                        /*Title*/
                        $timeline_title_key = $this->get_repeater_setting_key( '_ua_timeline_title', '_ua_timeline_items', $index );
                        
                        $this->add_render_attribute( $timeline_title_key, [
                            'class' => [ "ua_title", "ultraaddons-timeline-title" ],
                        ] );
    
                        $timeline_title_class = $this->get_render_attribute_string( $timeline_title_key );
                        $has_title_text = ! empty( $item['_ua_timeline_title'] );
    
                        /*Content*/
                        $timeline_content_key = $this->get_repeater_setting_key( '_ua_timeline_desc', '_ua_timeline_items', $index );
                        
                        $this->add_render_attribute( $timeline_content_key, [
                            'class' => [ "ua_desc", "ultraaddons-timeline-content" ],
                        ] );
    
                        $timeline_content_class = $this->get_render_attribute_string( $timeline_content_key );
                        $has_timeline_text = ! empty( $item['_ua_timeline_desc'] );
    
                        /*Date*/
                        $date_format = !empty( $settings['date_format']) ?  $settings['date_format'] : 'F j, Y';
                        $date = date( $date_format, strtotime($item['_ua_timeline_time']));
                        if('timeline_text' == $item['_ua_timeline_style']){
                            $date = $item['_ua_timeline_time_text'];
                        }
                        $time_format = !empty( $settings['time_format']) ?  $settings['time_format'] : 'g:i a';
                        $time = 'timeline_calender' == $item['_ua_timeline_style'] ? date($time_format, strtotime($item['_ua_timeline_time'])) : '';
    
                        ?>
                    <div <?php echo $timeline_inner_wraper; ?>>
                        <div class="ua_limeline_counter ultraaddons-timeline-counter"></div>
                        <div class="ua_timeline_main_coutent_inner ultraaddons-timeline-content-inner">
                            <?php if ( $settings['show_title'] == 'yes'): ?>
                                <?php if ($has_title_text): ?>  
                                    <<?php echo ultraaddons_title_tag($item['_ua_timeline_title_size']); ?> <?php echo $timeline_title_class; ?>><?php echo esc_html($item['_ua_timeline_title']); ?></<?php echo ultraaddons_title_tag($item['_ua_timeline_title_size']); ?>>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if ( $settings['show_desc'] == 'yes'): ?>
                                <?php if ($has_timeline_text): ?>
                                    <p <?php echo $timeline_content_class; ?>>
                                        <?php echo ultraaddons_parse_text_editor($item['_ua_timeline_desc']); ?>
                                    </p>
                                <?php endif ?>
                            <?php endif ?>
                            <?php if ( $settings['show_date_time'] == 'yes'): ?>
                                <div class="ua_timeline_coutent_inner ultraaddons-timeline-date-time">
                                    <p class="ua_date ultraaddons-date-time">
                                        <?php
                                            if ($date &&  $settings['show_date']) {
                                                printf('<span class="date">%s</span>', esc_html($date));
                                            }
                                            if ($time &&  $settings['show_time']) {
                                                printf('<span class="time">%s</span>', esc_html($time));
                                            }
                                        ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
            <?php endforeach; endif; ?>
            </div>
        </div>
    <?php }
    
    protected function _second_timeline_layout(){
        $settings = $this->get_settings_for_display();
        ?>
        <div class="ua_timeline_section ua_timeline_default_style ua_style_01 ultraaddons-timeline-section ultraaddons-timeline-default-style">
            <div class="ua_timeline_section_inner ultraaddons-timeline-section-inner">
                <?php if ( $settings['_ua_timeline_items_skin_second']):
                    foreach (  $settings['_ua_timeline_items_skin_second'] as $index => $item ):

                        $item_count = $index + 1;

                        /*Inner Wrapper*/
                        $timeline_id_key = $this->get_repeater_setting_key( '_id', '_ua_timeline_items_skin_second', $index );
                        $this->add_render_attribute( $timeline_id_key, [
                            'id' => 'timeline-' . $item['_id'],
                            'class' => [ "ua_limeline_section_inner_wrapper", "elementor-repeater-item-{$item['_id']}", "ultraaddons-timeline-inner-wraper" ],
                            'data-item' => $item_count,
                        ] );
                        $timeline_inner_wraper = $this->get_render_attribute_string( $timeline_id_key );

                        /*Title*/
                        $timeline_title_key = $this->get_repeater_setting_key( '_ua_timeline_title', '_ua_timeline_items_skin_second', $index );
                        
                        $this->add_render_attribute( $timeline_title_key, [
                            'class' => [ "ua_title", "ultraaddons-timeline-title" ],
                        ] );

                        $timeline_title_class = $this->get_render_attribute_string( $timeline_title_key );
                        $has_title_text = ! empty( $item['_ua_timeline_title'] );

                        /*Content*/
                        $timeline_content_key = $this->get_repeater_setting_key( '_ua_timeline_desc', '_ua_timeline_items_skin_second', $index );
                        
                        $this->add_render_attribute( $timeline_content_key, [
                            'class' => [ "ua_desc", "ultraaddons-timeline-content" ],
                        ] );

                        $timeline_content_class = $this->get_render_attribute_string( $timeline_content_key );
                        $has_timeline_text = ! empty( $item['_ua_timeline_desc'] );

                        /*Date*/
                        $date_format = !empty( $settings['date_format']) ?  $settings['date_format'] : 'F j, Y';
                        $date = date( $date_format, strtotime($item['_ua_timeline_time']));
                        if('timeline_text' == $item['_ua_timeline_style']){
                            $date = $item['_ua_timeline_time_text'];
                        }
                        $time_format = !empty( $settings['time_format']) ?  $settings['time_format'] : 'g:i a';
                        $time = 'timeline_calender' == $item['_ua_timeline_style'] ? date($time_format, strtotime($item['_ua_timeline_time'])) : '';

                        /*Icon*/

                        $migrated = isset( $item['__fa4_migrated']['_ua_timeline_selected_icon'] );

                        if ( ! isset( $item['icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
                            $item['icon'] = 'fas fa-check';
                        }
                        $is_new = empty( $item['icon'] ) && Icons_Manager::is_migration_allowed();
                        $has_icon = ( ! $is_new || ! empty( $item['_ua_timeline_selected_icon']['value'] ) );

                        ?>
                    <div <?php echo $timeline_inner_wraper; ?>>
                        <?php if ( $item['_ua_timeline_icon_show'] === 'yes' ): ?>
                            <div class="ua_limeline_counter ua_single_limeline_icon ultraaddons-timeline-counter">
                                <?php
                                    if($item['_ua_timeline_icon_type'] == 'icon'){
                                        if ( $is_new || $migrated ) { ?>
                                        <?php Icons_Manager::render_icon( $item['_ua_timeline_selected_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                    <?php }
                                    }elseif( $item['_ua_timeline_icon_type'] == 'image' ){ ?>
                                        <img src="<?php echo esc_url($item['_ua_timeline_icon_image']['url']); ?>" alt="<?php echo esc_attr( get_post_meta($item['_ua_timeline_icon_image']['id'], '_wp_attachment_image_alt', true) ); ?>">
                                <?php } ?>
                            </div>
                        <?php endif; ?>
                        <div class="ua_timeline_main_coutent_inner ultraaddons-timeline-content-inner">
                            <?php if ( $settings['show_title'] == 'yes'): ?>
                                <?php if ($has_title_text): ?>  
                                    <<?php echo ultraaddons_title_tag($item['_ua_timeline_title_size']); ?> <?php echo $timeline_title_class; ?>><?php echo esc_html($item['_ua_timeline_title']); ?></<?php echo ultraaddons_title_tag($item['_ua_timeline_title_size']); ?>>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if ( $settings['show_desc'] == 'yes'): ?>
                                <?php if ($has_timeline_text): ?>
                                    <p <?php echo $timeline_content_class; ?>>
                                        <?php echo ultraaddons_parse_text_editor($item['_ua_timeline_desc']); ?>
                                    </p>
                                <?php endif ?>
                            <?php endif ?>
                            <?php if ( $settings['show_date_time'] == 'yes'): ?>
                                <div class="ua_timeline_coutent_inner ultraaddons-timeline-date-time">
                                    <p class="ua_date ultraaddons-date-time">
                                        <?php
                                            if ($date &&  $settings['show_date']) {
                                                printf('<span class="date">%s</span>', esc_html($date));
                                            }
                                            if ($time &&  $settings['show_time']) {
                                                printf('<span class="time">%s</span>', esc_html($time));
                                            }
                                        ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
            <?php endforeach; endif; ?>
            </div>
        </div>
    <?php }
    
    protected function _third_timeline_layout(){
        $settings = $this->get_settings_for_display();

        $show_slider                    =  $settings['_ua_timeline_slider_nav_show'];
        $slider_autoplay                =  $settings['_ua_timeline_slider_autoplay'];
        $slider_speed                   =  $settings['_ua_timeline_slider_speed'];
        $slider_loop                    =  $settings['_ua_timeline_slider_loop'];
        $slider_space                   =  $settings['_ua_timeline_slider_space'];
        $slider_item                    =  $settings['_ua_timeline_slider_perpage'];
        $slider_center                  =  $settings['_ua_timeline_slider_center'];
        $slider_drag                    =  $settings['_ua_timeline_slider_drag'];
        $slider_pagi_type               =  $settings['_ua_pagination_type'];
        $slider_next_icon               =  $settings['_ua_timeline_nav_next_icon'];
        $slider_prev_icon               =  $settings['_ua_timeline_nav_prev_icon'];

        $slide_controls = [
            'show_slider'                   => $show_slider,
            'slide_autoplay'                => $slider_autoplay,
            'slider_speed'                  => $slider_speed,
            'slider_loop'                   => $slider_loop,
            'slider_space'                  => $slider_space,
            'slider_item'                   => $slider_item,
            'slider_drag'                   => $slider_drag,
            'slider_center'                 => $slider_center,
            'slider_pagi_type'              => $slider_pagi_type,
            'next_icon'                     => $slider_next_icon,
            'prev_icon'                     => $slider_prev_icon,
        ];
        $slide_controls = \json_encode($slide_controls);
        ?> 
        <div class="ua_horizontal_container ultraaddons-timeline-section ultraaddons-timeline-default-style">
            <div class="ua_timeline_inner ultraaddons-timeline-section-inner owl-carousel ultraaddons-top-border" data-controls="<?php echo esc_attr($slide_controls); ?>">
                <?php if ( $settings['_ua_timeline_items']):
                    foreach (  $settings['_ua_timeline_items'] as $index => $item ):

                        $item_count = $index + 1;

                        /*Inner Wrapper*/
                        $timeline_id_key = $this->get_repeater_setting_key( '_id', '_ua_timeline_items', $index );
                        $this->add_render_attribute( $timeline_id_key, [
                            'id' => 'timeline-inner-' . $item['_id'],
                            'class' => [ "horizontal_content_wrapper", "elementor-repeater-item-{$item['_id']}", "ultraaddons-timeline-inner-wraper" ],
                            'data-item' => $item_count,
                        ] );
                        $timeline_inner_wraper = $this->get_render_attribute_string( $timeline_id_key );

                        /*Title*/
                        $timeline_title_key = $this->get_repeater_setting_key( '_ua_timeline_title', '_ua_timeline_items', $index );
                        
                        $this->add_render_attribute( $timeline_title_key, [
                            'class' => [ "ua_title", "ultraaddons-timeline-title" ],
                        ] );

                        $timeline_title_class = $this->get_render_attribute_string( $timeline_title_key );
                        $has_title_text = ! empty( $item['_ua_timeline_title'] );

                        /*Content*/
                        $timeline_content_key = $this->get_repeater_setting_key( '_ua_timeline_desc', '_ua_timeline_items', $index );
                        
                        $this->add_render_attribute( $timeline_content_key, [
                            'class' => [ "ua_desc", "ultraaddons-timeline-content" ],
                        ] );

                        $timeline_content_class = $this->get_render_attribute_string( $timeline_content_key );
                        $has_timeline_text = ! empty( $item['_ua_timeline_desc'] );

                        /*Date*/
                        $date_format = !empty( $settings['date_format']) ?  $settings['date_format'] : 'F j, Y';
                        $date = date( $date_format, strtotime($item['_ua_timeline_time']));
                        if('timeline_text' == $item['_ua_timeline_style']){
                            $date = $item['_ua_timeline_time_text'];
                        }
                        $time_format = !empty( $settings['time_format']) ?  $settings['time_format'] : 'g:i a';
                        $time = 'timeline_calender' == $item['_ua_timeline_style'] ? date($time_format, strtotime($item['_ua_timeline_time'])) : '';

                        /*Icon*/

                        $migrated = isset( $item['__fa4_migrated']['_ua_timeline_selected_icon'] );

                        if ( ! isset( $item['icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
                            $item['icon'] = 'fas fa-check';
                        }
                        $is_new = empty( $item['icon'] ) && Icons_Manager::is_migration_allowed();
                        $has_icon = ( ! $is_new || ! empty( $item['_ua_timeline_selected_icon']['value'] ) );

                        ?>
                    <div <?php echo $timeline_inner_wraper; ?>>
                        <?php if ( $settings['show_date_time'] == 'yes'): ?>
                                <div class="ua_img_handler_top ua_style_2 ultraaddons-timeline-date-time">
                                    <div class="ua_date ultraaddons-date-time">
                                        <?php
                                            if ($date &&  $settings['show_date']) {
                                                printf('<p class="date">%s</p>', esc_html($date));
                                            }
                                            if ($time &&  $settings['show_time']) {
                                                printf('<p class="time">%s</p>', esc_html($time));
                                            }
                                        ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="ua_content_inner_bottom ultraaddons-timeline-content-inner">
                                <?php if ( $settings['show_title'] == 'yes'): ?>
                                <?php if ($has_title_text): ?>  
                                    <<?php echo ultraaddons_title_tag($item['_ua_timeline_title_size']); ?> <?php echo $timeline_title_class; ?>><?php echo esc_html($item['_ua_timeline_title']); ?></<?php echo ultraaddons_title_tag($item['_ua_timeline_title_size']); ?>>
                                <?php endif; ?>
                            <?php endif; ?>
                                <?php if ( $settings['show_desc'] == 'yes'): ?>
                                <?php if ($has_timeline_text): ?>
                                    <p <?php echo $timeline_content_class; ?>>
                                        <?php echo ultraaddons_parse_text_editor($item['_ua_timeline_desc']); ?>
                                    </p>
                                <?php endif ?>
                            <?php endif ?>
                            </div>
                            <span class="ua_bullet_top ultraaddons-bullet-top"></span>
                    </div>
            <?php endforeach; endif; ?>
            </div>
        </div>
    <?php }
    
    
    /**
     * General Section for Content Controls
     * 
     * @since 1.0.0.9
     */
    protected function ua_content_general_controls() {
        $this->start_controls_section(
            'general_content',
            [
                'label'     => esc_html__( 'General', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            '_ua_timeline_skin',
            [
                'label' => esc_html__( 'Design Format', 'ultraaddons' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'options'   => [
                    '_skin_1' => 'Style 01',
                    '_skin_2' => 'Style 02',
                    '_skin_3' => 'Style 03',
                ],
                'default' => '_skin_1'
            ]
        );
        
        $this->end_controls_section();
    }

    protected function ua_timeline_content_skin_1_controls(){
        $this->start_controls_section(
            '_ua_timeline_content_skin_1_layout_section',
            [
                'label' => esc_html__('Content', 'ultraaddons'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
                'condition' => [
                	'_ua_timeline_skin' => ['_skin_1', '_skin_3']
                ],
            ]
        );
        $this->uac_register_timeline_repeater_for_first_layout();

        
        $this->end_controls_section();
    }

    protected function ua_timeline_content_skin_2_controls(){
        $this->start_controls_section(
            '_ua_timeline_content_skin_2_layout_section',
            [
                'label' => esc_html__('Content', 'ultraaddons'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
                'condition' => [
                	'_ua_timeline_skin' => ['_skin_2']
                ],
            ]
        );
        $this->uac_register_timeline_repeater_for_second_layout();

        $this->end_controls_section();
    }

    protected function uac_register_timeline_repeater_for_first_layout(){
        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            '_ua_timeline_title',
            [
                'label' => __( 'Title', 'ultraaddons' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Title Here...', 'ultraaddons' ),
                'placeholder' => __( 'Enter your title', 'ultraaddons' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            '_ua_timeline_desc',
            [
                'label' => 'Description',
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Voluptates eligendiniour dignissimos ads',
                'placeholder' => __( 'Enter your description', 'ultraaddons' ),
                'show_label' => true,
                'rows' => 10,
            ]
        );
        $repeater->add_control(
			'_ua_timeline_style',
			[
				'label' => __('Time', 'ultraaddons'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'timeline_calender' => __('Calender', 'ultraaddons'),
					'timeline_text' => __('Text', 'ultraaddons'),
				],
				'default' => 'timeline_calender',
				'style_transfer' => true,
			]
		);


		$repeater->add_control(
			'_ua_timeline_time',
			[
				'label' => __('Calender', 'ultraaddons'),
				'show_label' => false,
				'type' => Controls_Manager::DATE_TIME,
				'default' => date('M d Y g:i a'),
				'condition' => [
					'_ua_timeline_style' => ['timeline_calender'],
				],
			]
		);

		$repeater->add_control(
			'_ua_timeline_time_text',
			[
				'label' => __('Text Time', 'ultraaddons'),
				'show_label' => false,
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'default' => __('2020 - 2021', 'ultraaddons'),
				'placeholder' => __('Text Time', 'ultraaddons'),
				'condition' => [
					'_ua_timeline_style' => ['timeline_text'],
				],
			]
		);

        $repeater->add_control(
            '_ua_timeline_title_size',
            [
                'label' => __( 'Title Tag', 'ultraaddons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'options' => [
                    'h1'  => [
                        'title' => __( 'H1', 'ultraaddons' ),
                        'icon' => 'eicon-editor-h1'
                    ],
                    'h2'  => [
                        'title' => __( 'H2', 'ultraaddons' ),
                        'icon' => 'eicon-editor-h2'
                    ],
                    'h3'  => [
                        'title' => __( 'H3', 'ultraaddons' ),
                        'icon' => 'eicon-editor-h3'
                    ],
                    'h4'  => [
                        'title' => __( 'H4', 'ultraaddons' ),
                        'icon' => 'eicon-editor-h4'
                    ],
                    'h5'  => [
                        'title' => __( 'H5', 'ultraaddons' ),
                        'icon' => 'eicon-editor-h5'
                    ],
                    'h6'  => [
                        'title' => __( 'H6', 'ultraaddons' ),
                        'icon' => 'eicon-editor-h6'
                    ],
                    'p'  => [
                        'title' => __( 'P', 'ultraaddons' ),
                        'icon' => 'eicon-editor-paragraph'
                    ],
                ],
                'default' => 'h4',
                'toggle' => false,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            '_ua_timeline_items',
            [
                'type' => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default' => [
                    [
                        '_ua_timeline_title' => __( 'Title #1', 'ultraaddons' ),
                        
                        '_ua_timeline_desc' =>__( 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Voluptates eligendiniour dignissimos ads', 'ultraaddons' ),
                    ],
                    [
                        '_ua_timeline_title' => __( 'Title #2', 'ultraaddons' ),

                        '_ua_timeline_desc' =>__( 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Voluptates eligendiniour dignissimos ads', 'ultraaddons' ),
                    ],
                    [
                        '_ua_timeline_title' => __( 'Title #3', 'ultraaddons' ),

                        '_ua_timeline_desc' =>__( 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Voluptates eligendiniour dignissimos ads', 'ultraaddons' ),
                    ],
                    [
                        '_ua_timeline_title' => __( 'Title #4', 'ultraaddons' ),

                        '_ua_timeline_desc' =>__( 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Voluptates eligendiniour dignissimos ads', 'ultraaddons' ),
                    ],
                ],

                'title_field' => '{{ _ua_timeline_title }}',
            ]
        );
    }

    protected function uac_register_timeline_repeater_for_second_layout(){
        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            '_ua_timeline_title',
            [
                'label' => __( 'Title', 'ultraaddons' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Title Here...', 'ultraaddons' ),
                'placeholder' => __( 'Enter your title', 'ultraaddons' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            '_ua_timeline_desc',
            [
                'label' => 'Description',
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Voluptates eligendiniour dignissimos ads',
                'placeholder' => __( 'Enter your description', 'ultraaddons' ),
                'show_label' => true,
                'rows' => 10,
            ]
        );
        $repeater->add_control(
            '_ua_timeline_icon_show',
            [
                'label' => esc_html__('Enable Icon', 'ultraaddons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );
        $repeater->add_control(
            '_ua_timeline_icon_type',
            [   
                'label' => esc_html__('Icon Type', 'ultraaddons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'none' => [
                        'title' => esc_html__('None', 'ultraaddons'),
                        'icon' => 'fa fa-ban',
                    ],
                    'icon' => [
                        'title' => esc_html__('Icon', 'ultraaddons'),
                        'icon' => 'fa fa-gear',
                    ],
                    'image' => [
                        'title' => esc_html__('Image', 'ultraaddons'),
                        'icon' => 'fa fa-picture-o',
                    ],
                ],
                'default' => 'icon',
                'condition' => [
                    '_ua_timeline_icon_show' => [ 'yes' ],
                ],
            ]
        );
        $repeater->add_control(
            '_ua_timeline_selected_icon',
            [
                'label' => __( 'Icon', 'ultraaddons' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => [
                    'value' => 'fas fa-check',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    '_ua_timeline_icon_show' => [ 'yes' ],
                    '_ua_timeline_icon_type' => [ 'icon' ],
                ],
            ]
        );
        $repeater->add_control(
             '_ua_timeline_icon_image',
             [   
                 'label' => esc_html__('Image', 'ultraaddons'),
                 'type' => Controls_Manager::MEDIA,
                 'default' => [
                     'url' => '',
                 ],
                 'condition' => [
                 	'_ua_timeline_icon_show' => [ 'yes' ],
                    '_ua_timeline_icon_type' => [ 'image' ],
                ],
             ]
         );
        $repeater->add_control(
			'_ua_timeline_style',
			[
				'label' => __('Time', 'ultraaddons'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'timeline_calender' => __('Calender', 'ultraaddons'),
					'timeline_text' => __('Text', 'ultraaddons'),
				],
				'default' => 'timeline_calender',
				'style_transfer' => true,
			]
		);

		$repeater->add_control(
			'_ua_timeline_time',
			[
				'label' => __('Calender', 'ultraaddons'),
				'show_label' => false,
				'type' => Controls_Manager::DATE_TIME,
				'default' => date('M d Y g:i a'),
				'condition' => [
					'_ua_timeline_style' => ['timeline_calender'],
				],
			]
		);

		$repeater->add_control(
			'_ua_timeline_time_text',
			[
				'label' => __('Text Time', 'ultraaddons'),
				'show_label' => false,
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'default' => __('2020 - 2021', 'ultraaddons'),
				'placeholder' => __('Text Time', 'ultraaddons'),
				'condition' => [
					'_ua_timeline_style' => ['timeline_text'],
				],
			]
		);

        $repeater->add_control(
            '_ua_timeline_title_size',
            [
                'label' => __( 'Title Tag', 'ultraaddons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'options' => [
                    'h1'  => [
                        'title' => __( 'H1', 'ultraaddons' ),
                        'icon' => 'eicon-editor-h1'
                    ],
                    'h2'  => [
                        'title' => __( 'H2', 'ultraaddons' ),
                        'icon' => 'eicon-editor-h2'
                    ],
                    'h3'  => [
                        'title' => __( 'H3', 'ultraaddons' ),
                        'icon' => 'eicon-editor-h3'
                    ],
                    'h4'  => [
                        'title' => __( 'H4', 'ultraaddons' ),
                        'icon' => 'eicon-editor-h4'
                    ],
                    'h5'  => [
                        'title' => __( 'H5', 'ultraaddons' ),
                        'icon' => 'eicon-editor-h5'
                    ],
                    'h6'  => [
                        'title' => __( 'H6', 'ultraaddons' ),
                        'icon' => 'eicon-editor-h6'
                    ],
                    'p'  => [
                        'title' => __( 'P', 'ultraaddons' ),
                        'icon' => 'eicon-editor-paragraph'
                    ],
                ],
                'default' => 'h4',
                'toggle' => false,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            '_ua_timeline_items_skin_second',
            [
                'type' => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default' => [
                    [
                        '_ua_timeline_title' => __( 'Title #1', 'ultraaddons' ),
                        
                        '_ua_timeline_desc' =>__( 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Voluptates eligendiniour dignissimos ads', 'ultraaddons' ),
                    ],
                    [
                        '_ua_timeline_title' => __( 'Title #2', 'ultraaddons' ),

                        '_ua_timeline_desc' =>__( 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Voluptates eligendiniour dignissimos ads', 'ultraaddons' ),
                    ],
                    [
                        '_ua_timeline_title' => __( 'Title #3', 'ultraaddons' ),

                        '_ua_timeline_desc' =>__( 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Voluptates eligendiniour dignissimos ads', 'ultraaddons' ),
                    ],
                    [
                        '_ua_timeline_title' => __( 'Title #4', 'ultraaddons' ),

                        '_ua_timeline_desc' =>__( 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Voluptates eligendiniour dignissimos ads', 'ultraaddons' ),
                    ],
                ],

                'title_field' => '{{ _ua_timeline_title }}',
            ]
        );
    }
            
    protected function ua_register_timeline_options_controls(){
        $this->start_controls_section(
            '_ua_timeline_options_layout_section',
            [
                'label' => esc_html__('Options', 'ultraaddons'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT, 
            ]
        );
        $this->uac_register_option_controls();

        $this->end_controls_section();
    }

    protected function uac_register_option_controls(){
        $this->add_control(
			'show_title',
			[
				'label' => __('Show Title?', 'ultraaddons'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'ultraaddons'),
				'label_off' => __('Hide', 'ultraaddons'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
    	$this->add_control(
			'show_desc',
			[
				'label' => __('Show Description?', 'ultraaddons'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'ultraaddons'),
				'label_off' => __('Hide', 'ultraaddons'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
    	$this->add_control(
			'show_date_time',
			[
				'label' => __('Show Date Time?', 'ultraaddons'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'ultraaddons'),
				'label_off' => __('Hide', 'ultraaddons'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
    	$this->add_control(
			'show_date',
			[
				'label' => __('Show Date?', 'ultraaddons'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'ultraaddons'),
				'label_off' => __('Hide', 'ultraaddons'),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'show_date_time' => ['yes'],
				],
			]
		);

		$this->add_control(
			'show_time',
			[
				'label' => __('Show Time?', 'ultraaddons'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'ultraaddons'),
				'label_off' => __('Hide', 'ultraaddons'),
				'return_value' => 'yes',
				'default' => '',
				'condition' => [
					'show_date_time' => ['yes'],
				],
			]
		);
		
		$this->add_control(
			'date_format',
			[
				'label' => __('Date Format', 'ultraaddons'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'd M Y'     => date("d M Y"),
					'm.d.y'     => date("m.d.y"),
					'j, n, Y'   => date("j, n, Y"),
					'Ymd'       => date("Ymd"),
					'D M j, Y'  => date("D M j, Y"),
					'F j, Y'    => date("F j, Y"),
					'j M, Y'    => date("j M, Y"),
					'Y-m-d'     => date("Y-m-d"),
					'Y/m/d'     => date("Y/m/d"),
				],
				'default' => 'd M Y',
				'condition' => [
					'show_date_time'    => ['yes'],
					'show_date'         => ['yes'],
				],
			]
		);
		$this->add_control(
			'time_format',
			[
				'label' => __('Time Format', 'ultraaddons'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'g:i a'     => date("g:i a"),
					'g:i A'     => date("g:i A"),
					'g:i'       => date("g:i"),
					'G:i a'     => date("G:i a"),
					'G:i A'     => date("G:i A"),
					'G:i'       => date("G:i"),
					'H:i:s a'   => date("H:i:s a"),
					'H:i:s A'   => date("H:i:s A"),
					'H:i:s'     => date("H:i:s"),
					'H:m:s a'   => date("H:m:s a"),
					'H:m:s A'   => date("H:m:s A"),
					'H:m:s'     => date("H:m:s"),
				],
				'default' => 'g:i a',
				'condition' => [
					'show_date_time'    => ['yes'],
					'show_time'         => ['yes'],
				],
			]
		);

		$this->add_control(
			'show_content_arrow',
			[
				'label' => __('Show Content Arrow?', 'ultraaddons'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'show' => __('Show', 'ultraaddons'),
					'hide' => __('Hide', 'ultraaddons'),
				],
				'default' => 'show',
				'prefix_class' => 'ultraaddons-content-arrow-',
			]
		);


		$this->add_control(
			'icon_box_align',
			[
				'label' => __('Icon Box Alignment', 'ultraaddons'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => __('Top', 'ultraaddons'),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => __('Center', 'ultraaddons'),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __('Bottom', 'ultraaddons'),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'toggle' => false,
				'default' => 'top',
				'prefix_class' => 'ultraaddons-timeline-icon-align-',
			]
		);
    }

    protected function ua_register_timeline_general_style_controls(){
        $this->start_controls_section(
            '_ua_timeline_general_style_layout_section',
            [
                'label' => esc_html__('General', 'ultraaddons'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
         $this->add_responsive_control(
            '_ua_timeline_margin',
            [
                'label' => esc_html__('Margin', 'ultraaddons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-section-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    '_ua_timeline_skin' => ['_skin_3'],
                ]
            ]
        );
        $this->uac_register_general_box_backgound_controls();

        $this->end_controls_section();
    }

    protected function uac_register_general_box_backgound_controls(){
        $this->add_group_control(
	        Group_Control_Background::get_type(),
	        [
	            'name' => '_ua_timeline_box_background',
	            'label' => 'Color',
	            'fields_options' => [
					'background' => [
						'label' => __( 'Box Background', 'ultraaddons' ),
					],
				],
	            'types' => [ 'classic', 'gradient' ],
	            'selector' =>
	             	'{{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-content-inner, {{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-content-inner:before',
                'condition' => [
                    '_ua_timeline_skin!' => ['_skin_3'],
                ]
	        ]
        );
    }

    protected function ua_register_timeline_icon_style_controls(){
        $this->start_controls_section(
            '_ua_timeline_icon_style_layout_section',
            [
                'label' => esc_html__('Icon', 'ultraaddons'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE, 
            ]
        );
        $this->uac_register_icon_controls();

        $this->end_controls_section();
    }

    protected function uac_register_icon_controls(){
    	 $this->add_responsive_control(
        '_ua_timeline_icon_size',[
            'label' => __('Icon Size', 'ultraaddons'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '',
                'unit' => 'px',
            ],
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-counter i' => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-counter img' => 'width: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-counter svg' => 'width: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
				'_ua_timeline_skin' => ['_skin_2'],
			],
        ]
    	);
    	 $this->add_responsive_control(
			'_ua_timeline_icon_rotate', [
				'label' => __( 'Rotate', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'default' => [
					'size' => 0,
					'unit' => 'deg',
				],
				'tablet_default' => [
					'unit' => 'deg',
				],
				'mobile_default' => [
					'unit' => 'deg',
				],
				'selectors' => [
					'{{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-counter i, {{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-counter svg' => 'transform: rotate({{SIZE}}{{UNIT}});',
				],
				'condition' => [
					'_ua_timeline_skin' => ['_skin_2'],
				],
			]
		);
    	 $this->add_control(
			'_ua_timeline_icon_color_skin_2',
			[
				'label' => esc_html__( 'Color', 'ultraaddons'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-counter i' => 'color: {{VALUE}};',

					'{{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-counter svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'_ua_timeline_skin' => ['_skin_2'],
				],
			]
		);
		 $this->add_group_control(
	        Group_Control_Background::get_type(),
	        [
	            'name' => '_ua_timeline_icon_background',
	            'label' => 'Color',
	            'fields_options' => [
					'background' => [
						'label' => __( 'Icon Background', 'ultraaddons' ),
					],
				],
	            'types' => [ 'classic', 'gradient' ],
	            'selector' =>
	             	'{{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-counter',
	            'condition' => [
					'_ua_timeline_skin!' => ['_skin_3'],
				],
	        ]
	    );
		 $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => '_ua_timeline_icon_border',
                'label' => esc_html__('Border', 'ultraaddons'),
                'selector' => '{{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-counter, {{WRAPPER}} .ultraaddons-timeline-section .ua_timeline_inner .ultraaddons-timeline-inner-wraper span.ultraaddons-bullet-top',
            ]
        );
    }

    protected function ua_register_timeline_border_line_style_controls(){
        $this->start_controls_section(
            '_ua_timeline_border_line_style_layout_section',
            [
                'label' => esc_html__('Border line', 'ultraaddons'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE, 
            ]
        );
        $this->uac_register_icon_border_line_controls();

        $this->end_controls_section();
    }

    protected function uac_register_icon_border_line_controls(){
		  $this->add_group_control(
            Group_Control_Border::get_type(),
	            [
	                'name' => '_ua_timeline_border_color_skin_2',
	                'label' => esc_html__('Border', 'ultraaddons'),
	                'selector' => '{{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-section-inner:before',
	                'condition' => [
		                '_ua_timeline_skin' =>  [ '_skin_1', '_skin_2' ],
		            ],  
	            ]
        	);

		  $this->add_control(
			'_ua_timeline_border_style',
			[
				'label' => __( 'Border Type', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'none'       => __( 'None', 'ultraaddons' ),
					'solid'  => __( 'Solid', 'ultraaddons' ),
					'double' => __( 'Double', 'ultraaddons' ),
					'dotted' => __( 'Dotted', 'ultraaddons' ),
					'dashed' => __( 'Dashed', 'ultraaddons' ),
					'groove' => __( 'Groove', 'ultraaddons' ),
				],
				'selectors' => [
	                '{{WRAPPER}} .ua_horizontal_container.ultraaddons-timeline-section .ua_timeline_inner.ultraaddons-top-border' => 'border-top-style: {{VALUE}};',
	            ],
	            'condition' => [
	                '_ua_timeline_skin' =>  [ '_skin_3' ],
	            ],
			]
		 );
		   $this->add_control(
            '_ua_images_nav_normal_color',
            [
                'label' => esc_html__('Color', 'ultraaddons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
					 '{{WRAPPER}} .ua_horizontal_container.ultraaddons-timeline-section .ua_timeline_inner.ultraaddons-top-border' => 'border-top-color: {{VALUE}};',
				],
				'condition' => [
	                '_ua_timeline_skin' =>  [ '_skin_3' ],
	            ],
            ]
        );
		 $this->add_responsive_control(
	        'adv_timeline_border_top',
	        [
	            'label' => __('Border Height', 'ultraaddons'),
	            'type' => Controls_Manager::SLIDER,
	            'default' => [
	                'size' => '',
	                'unit' => 'px',
	            ],
	            'size_units' => ['px'],
	            'range' => [
	                'px' => [
	                    'min' => 0,
	                    'max' => 100,
	                    'step' => 1,
	                ],
	            ],
	            'selectors' => [
	                '{{WRAPPER}} .ua_horizontal_container.ultraaddons-timeline-section .ua_timeline_inner.ultraaddons-top-border' => 'border-top-width: {{SIZE}}{{UNIT}};',
	            ],
	            'condition' => [
	                '_ua_timeline_skin' =>  [ '_skin_3' ],
	            ],
	        ]
		);

		 $this->add_responsive_control(
	        '_ua_timeline_border_position',
	        [
	            'label' => __('Position', 'ultraaddons'),
	            'type' => Controls_Manager::SLIDER,
	            'default' => [
	                'size' => '',
	                'unit' => 'px',
	            ],
	            'size_units' => ['px'],
	            'range' => [
	                'px' => [
	                    'min' => -100,
	                    'max' => 100,
	                    'step' => 1,
	                ],
	            ],
	            'selectors' => [
	                '{{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-section-inner:before' => 'margin-left: {{SIZE}}{{UNIT}};',
	            ],
	            'condition' => [
	                '_ua_timeline_skin' =>  [ '_skin_1', '_skin_2' ],
	            ],
	            
	        ]
		); 
		 $this->add_responsive_control(
	        '_ua_timeline_border_width',
	        [
	            'label' => __('Border Width', 'ultraaddons'),
	            'type' => Controls_Manager::SLIDER,
	            'default' => [
	                'size' => '',
	                'unit' => 'px',
	            ],
	            'size_units' => ['px'],
	            'range' => [
	                'px' => [
	                    'min' => 0,
	                    'max' => 200,
	                    'step' => 1,
	                ],
	            ],
	            'selectors' => [
	                '{{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-section-inner:before' => 'width: {{SIZE}}{{UNIT}};',
	            ],
	            'condition' => [
	                '_ua_timeline_skin' =>  [ '_skin_1', '_skin_2' ],
	            ],
	        ]
		);
    }

    protected function ua_register_timeline_content_style_control(){
		$this->start_controls_section(
            '_ua_timeline_content_style_section',
            [
                'label'     => __('Content', 'ultraaddons'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        

		$this->start_controls_tabs( '_ua_timeline_content_title_style_tabs' );

		$this->start_controls_tab( 'title_normal', 
			[ 
				'label' => esc_html__( 'Normal', 'ultraaddons'),
				'condition' => [
					'show_title' => ['yes'],
				],
			] 
		);
		$this->add_control(
            '_ua_timeline_content_title_heading',
            [
                'label' => __( 'Title', 'ultraaddons' ),
                'type'  => Controls_Manager::HEADING,
                'condition' => [
					'show_title' => ['yes'],
				],
            ]
        );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => '_ua_timeline_content_title_typography',
			'selector' => '{{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-section-inner .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-title',
			'condition' => [
					'show_title' => ['yes'],
				],
			]
		);
		
		$this->add_control(
			'_ua_timeline_content_title_color',
			[
				'label' => esc_html__( 'Color', 'ultraaddons'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-section-inner .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-title' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_title' => ['yes'],
				],
			]
		);
		
		  $this->add_control(
            '_ua_timeline_content_title_ofset',
            [
                'label'        => __('Offset', 'ultraaddons'),
                'type'         => Controls_Manager::POPOVER_TOGGLE,
                'label_on'     => __('Custom', 'ultraaddons'),
                'label_off'    => __('None', 'ultraaddons'),
                'return_value' => 'yes',
                'condition' => [
					'show_title' => ['yes'],
				],
            ]
        );
		$this->start_popover();

        $this->add_responsive_control(
            'content_title_offset_x',
            [
                'label'       => __('Offset Left', 'ultraaddons'),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => ['px', '%', 'em', 'rem'],
                'condition'   => [
                    '_ua_timeline_content_title_ofset' => ['yes'],
                    'show_title' => ['yes'],
                ],
                'range'       => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                ],
                'render_type' => 'ui',
            ]
        );

        $this->add_responsive_control(
            'content_title_offset_y',
            [
                'label'      => __('Offset Top', 'ultraaddons'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem'],
                'condition'  => [
                    '_ua_timeline_content_title_ofset' => ['yes'],
                    'show_title' => ['yes'],
                ],
                'range'      => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-section-inner .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-title' => 'margin-top: {{SIZE}}{{UNIT}};',
                    '(desktop){{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-section-inner .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-title'  => '-ms-transform: translate({{content_title_offset_x.SIZE || 0}}{{UNIT}}, {{content_title_offset_y.SIZE || 0}}{{UNIT}}); -webkit-transform: translate({{content_title_offset_x.SIZE || 0}}{{UNIT}}, {{content_title_offset_y.SIZE || 0}}{{UNIT}}); transform: translate({{content_title_offset_x.SIZE || 0}}{{UNIT}}, {{content_title_offset_y.SIZE || 0}}{{UNIT}});',
                    '(tablet){{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-section-inner .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-title'   => '-ms-transform: translate({{content_title_offset_x_tablet.SIZE || 0}}{{UNIT}}, {{content_title_offset_y_tablet.SIZE || 0}}{{UNIT}}); -webkit-transform: translate({{content_title_offset_x_tablet.SIZE || 0}}{{UNIT}}, {{content_title_offset_y_tablet.SIZE || 0}}{{UNIT}}); transform: translate({{content_title_offset_x_tablet.SIZE || 0}}{{UNIT}}, {{content_title_offset_y_tablet.SIZE || 0}}{{UNIT}});',
                    '(mobile){{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-section-inner .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-title'   => '-ms-transform: translate({{content_title_offset_x_mobile.SIZE || 0}}{{UNIT}}, {{content_title_offset_y_mobile.SIZE || 0}}{{UNIT}}); -webkit-transform: translate({{content_title_offset_x_mobile.SIZE || 0}}{{UNIT}}, {{content_title_offset_y_mobile.SIZE || 0}}{{UNIT}}); transform: translate({{content_title_offset_x_mobile.SIZE || 0}}{{UNIT}}, {{content_title_offset_y_mobile.SIZE || 0}}{{UNIT}});',
                ],

            ]
        );

        $this->end_popover();
		$this->end_controls_tab();

		$this->start_controls_tab( '_ua_timeline_content_title_hover', 
			[ 
				'label' => esc_html__( 'Hover', 'ultraaddons'),
				'condition' => [
					'show_title' => ['yes'],
				],
			]
		);

		$this->add_control(
			'_ua_timeline_content_title_hover_color',
			[
				'label' => esc_html__( 'Color', 'ultraaddons'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-section-inner .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-title:hover' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_title' => ['yes'],
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();

		//content tab

		$this->start_controls_tabs( '_ua_timeline_content_style_tabs' );

		$this->start_controls_tab( 'content_normal', [ 
				'label' => esc_html__( 'Normal', 'ultraaddons'),
				'condition' => [
					'show_desc' => ['yes'],
				],
			] 
		);
		$this->add_control(
            '_ua_timeline_content_heading',
            [
                'label' => __( 'Content', 'ultraaddons' ),
                'type'  => Controls_Manager::HEADING,
                'condition' => [
					'show_desc' => ['yes'],
				],
            ]
        );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => '_ua_timeline_content_typography',
			'selector' => '{{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-section-inner .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-content, .ultraaddons-timeline-section .ultraaddons-timeline-section-inner .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-content p',
			'condition' => [
					'show_desc' => ['yes'],
				],
			]
		);
		
		$this->add_control(
			'_ua_timeline_content_color',
			[
				'label' => esc_html__( 'Color', 'ultraaddons'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-section-inner .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-content, .ultraaddons-timeline-section .ultraaddons-timeline-section-inner .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-content p' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_desc' => ['yes'],
				],
			]
		);
		
		  $this->add_control(
            '_ua_timeline_content_ofset',
            [
                'label'        => __('Offset', 'ultraaddons'),
                'type'         => Controls_Manager::POPOVER_TOGGLE,
                'label_on'     => __('Custom', 'ultraaddons'),
                'label_off'    => __('None', 'ultraaddons'),
                'return_value' => 'yes',
                'condition' => [
					'show_desc' => ['yes'],
				],
            ]
        );
		$this->start_popover();

        $this->add_responsive_control(
            'content_offset_x',
            [
                'label'       => __('Offset Left', 'ultraaddons'),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => ['px', '%', 'em', 'rem'],
                'condition'   => [
                	'show_desc' => ['yes'],
                    '_ua_timeline_content_ofset' => [ 'yes' ]
                ],
                'range'       => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                ],
                'render_type' => 'ui',
            ]
        );

        $this->add_responsive_control(
            'content_offset_y',
            [
                'label'      => __('Offset Top', 'ultraaddons'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem'],
                'condition'  => [
                	'show_desc' => ['yes'],
                    '_ua_timeline_content_ofset' => [ 'yes' ]
                ],
                'range'      => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-section-inner .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-content' => 'margin-top: {{SIZE}}{{UNIT}};',
                    '(desktop){{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-section-inner .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-content'  => '-ms-transform: translate({{content_offset_x.SIZE || 0}}{{UNIT}}, {{content_offset_y.SIZE || 0}}{{UNIT}}); -webkit-transform: translate({{content_offset_x.SIZE || 0}}{{UNIT}}, {{content_offset_y.SIZE || 0}}{{UNIT}}); transform: translate({{content_offset_x.SIZE || 0}}{{UNIT}}, {{content_offset_y.SIZE || 0}}{{UNIT}});',
                    '(tablet){{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-section-inner .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-content'   => '-ms-transform: translate({{content_offset_x_tablet.SIZE || 0}}{{UNIT}}, {{content_offset_y_tablet.SIZE || 0}}{{UNIT}}); -webkit-transform: translate({{content_offset_x_tablet.SIZE || 0}}{{UNIT}}, {{content_offset_y_tablet.SIZE || 0}}{{UNIT}}); transform: translate({{content_offset_x_tablet.SIZE || 0}}{{UNIT}}, {{content_offset_y_tablet.SIZE || 0}}{{UNIT}});',
                    '(mobile){{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-section-inner .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-content'   => '-ms-transform: translate({{content_offset_x_mobile.SIZE || 0}}{{UNIT}}, {{content_offset_y_mobile.SIZE || 0}}{{UNIT}}); -webkit-transform: translate({{content_offset_x_mobile.SIZE || 0}}{{UNIT}}, {{content_offset_y_mobile.SIZE || 0}}{{UNIT}}); transform: translate({{content_offset_x_mobile.SIZE || 0}}{{UNIT}}, {{content_offset_y_mobile.SIZE || 0}}{{UNIT}});',
                ],            ]
        );

        $this->end_popover();
		$this->end_controls_tab();

		$this->start_controls_tab( '_ua_timeline_content_hover', [ 
			'label' => esc_html__( 'Hover', 'ultraaddons'),
			'condition' => [
					'show_desc' => ['yes'],
				],
			] 
		);

		$this->add_control(
			'_ua_timeline_content_hover_color',
			[
				'label' => esc_html__( 'Color', 'ultraaddons'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-section-inner .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-content:hover, .ultraaddons-timeline-section .ultraaddons-timeline-section-inner .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-content p:hover' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_desc' => ['yes'],
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();

		$this->start_controls_tabs( '_ua_timeline_content_date_style_tabs' );
		    $this->start_controls_tab( 'date_normal', 
		    	[ 
		    		'label' => esc_html__( 'Normal', 'ultraaddons'),
		    		'condition' => [
		    			'show_date_time' => ['yes'],
		    		],
		    	 ] );
				$this->add_control(
		            '_ua_timeline_content_date_heading',
		            [
		                'label' => __( 'Date Time', 'ultraaddons' ),
		                'type'  => Controls_Manager::HEADING,
		                'condition' => [
			    			'show_date_time' => ['yes'],
			    		],
		            ]
		        );
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
		             'name' => '_ua_timeline_content_date_typography',
					'selector' => '{{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-section-inner .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-date-time .ultraaddons-date-time',
					'condition' => [
			    			'show_date_time' => ['yes'],
			    		],
					]
				);
				
				$this->add_control(
					'_ua_timeline_content_date_color',
					[
						'label' => esc_html__( 'Color', 'ultraaddons'),
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-section-inner .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-date-time .ultraaddons-date-time' => 'color: {{VALUE}};',
						],
						'condition' => [
			    			'show_date_time' => ['yes'],
			    		],
					]
			);
		
		  $this->add_control(
            '_ua_timeline_content_date_ofset',
            [
                'label'        => __('Offset', 'ultraaddons'),
                'type'         => Controls_Manager::POPOVER_TOGGLE,
                'label_on'     => __('Custom', 'ultraaddons'),
                'label_off'    => __('None', 'ultraaddons'),
                'return_value' => 'yes',
                'condition' => [
	    			'show_date_time' => ['yes'],
	    		],
            ]
        );
		$this->start_popover();

        $this->add_responsive_control(
            'content_date_offset_x',
            [
                'label'       => __('Offset Left', 'ultraaddons'),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => ['px', '%', 'em', 'rem'],
                'condition'   => [
                    '_ua_timeline_content_date_ofset' => 'yes',
                ],
                'range'       => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                ],
                'render_type' => 'ui',
                'condition' => [
	    			'show_date_time' => ['yes'],
	    		],
            ]
        );

        $this->add_responsive_control(
            'content_date_offset_y',
            [
                'label'      => __('Offset Top', 'ultraaddons'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem'],
                'condition'  => [
                    '_ua_timeline_content_date_ofset' => 'yes',
                ],
                'range'      => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-section-inner .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-date-time .ultraaddons-date-time' => 'margin-top: {{SIZE}}{{UNIT}};',
                    '(desktop){{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-section-inner .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-date-time .ultraaddons-date-time'  => '-ms-transform: translate({{content_date_offset_x.SIZE || 0}}{{UNIT}}, {{content_date_offset_y.SIZE || 0}}{{UNIT}}); -webkit-transform: translate({{content_date_offset_x.SIZE || 0}}{{UNIT}}, {{content_date_offset_y.SIZE || 0}}{{UNIT}}); transform: translate({{content_date_offset_x.SIZE || 0}}{{UNIT}}, {{content_date_offset_y.SIZE || 0}}{{UNIT}});',
                    '(tablet){{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-section-inner .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-date-time .ultraaddons-date-time'   => '-ms-transform: translate({{content_date_offset_x_tablet.SIZE || 0}}{{UNIT}}, {{content_date_offset_y_tablet.SIZE || 0}}{{UNIT}}); -webkit-transform: translate({{content_date_offset_x_tablet.SIZE || 0}}{{UNIT}}, {{content_date_offset_y_tablet.SIZE || 0}}{{UNIT}}); transform: translate({{content_date_offset_x_tablet.SIZE || 0}}{{UNIT}}, {{content_date_offset_y_tablet.SIZE || 0}}{{UNIT}});',
                    '(mobile){{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-section-inner .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-date-time .ultraaddons-date-time'   => '-ms-transform: translate({{content_date_offset_x_mobile.SIZE || 0}}{{UNIT}}, {{content_date_offset_y_mobile.SIZE || 0}}{{UNIT}}); -webkit-transform: translate({{content_date_offset_x_mobile.SIZE || 0}}{{UNIT}}, {{content_date_offset_y_mobile.SIZE || 0}}{{UNIT}}); transform: translate({{content_date_offset_x_mobile.SIZE || 0}}{{UNIT}}, {{content_date_offset_y_mobile.SIZE || 0}}{{UNIT}});',
                ],
                'condition' => [
	    			'show_date_time' => ['yes'],
	    		],
            ]
        );

        $this->end_popover();
		$this->end_controls_tab();


        $this->start_controls_tab( '_ua_timeline_date_hover', [ 
            'label' => esc_html__( 'Hover', 'ultraaddons'),
            'condition' => [
    			'show_date_time' => ['yes'],
    		],
            ] 
        );

        $this->add_control(
            '_ua_timeline_date_hover_color',
            [
                'label' => esc_html__( 'Color', 'ultraaddons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-section-inner .ultraaddons-timeline-inner-wraper .ultraaddons-timeline-date-time .ultraaddons-date-time:hover' => 'color: {{VALUE}};',
                ],
                'condition' => [
	    			'show_date_time' => ['yes'],
	    		],
            ]
        );
        
        $this->end_controls_tab();

        $this->end_controls_tabs();
		
        $this->end_controls_section();
    }

    protected function ua_register_timeline_option_section_controls(){

        $this->start_controls_section(
            'section_tab_style',
            [
                'label' => esc_html__('Slider Options', 'ultraaddons'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                	'_ua_timeline_skin' => ['_skin_3'],
                ]
            ]
        );

        $this->add_control(
            '_ua_timeline_slider_autoplay',
            [
                'label'        => esc_html__('Autoplay', 'ultraaddons'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'ultraaddons'),
                'label_off'    => esc_html__('No', 'ultraaddons'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            '_ua_timeline_slider_speed',
            [
                'label'   => esc_html__('Autoplay Speed', 'ultraaddons'),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'default' => 2000,
            ]
        );

        $this->add_control(
            '_ua_timeline_slider_loop',
            [
                'label'        => esc_html__('Infinite Loop', 'ultraaddons'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'ultraaddons'),
                'label_off'    => esc_html__('No', 'ultraaddons'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

         $this->add_responsive_control(
            '_ua_timeline_slider_space',
            [
                'label'   => esc_html__('Slider Space', 'ultraaddons'),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'default' => 55,
            ]
        );

         $this->add_responsive_control(
            '_ua_timeline_slider_perpage',
            [
                'label'   => esc_html__('Slider Item', 'ultraaddons'),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'default' => 3,
            ]
        );

         $this->add_responsive_control(
            '_ua_timeline_slider_center',
            [
                'label'        => esc_html__('Center', 'ultraaddons'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'ultraaddons'),
                'label_off'    => esc_html__('No', 'ultraaddons'),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            '_ua_timeline_slider_drag',
            [
                'label'        => esc_html__('MouseDrag', 'ultraaddons'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'ultraaddons'),
                'label_off'    => esc_html__('No', 'ultraaddons'),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->end_controls_section();
    }

    protected function ua_register_timeline_navigation_controls() {
        $this->start_controls_section(
            '_ua_timeline_nav_control',
            [
                'label' => __( 'Navigation', 'ultraaddons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                	'_ua_timeline_skin' => ['_skin_3'],
                ]
            ]
        );
        $this->add_control(
            '_ua_timeline_slider_nav_show',
            [
                'label'        => esc_html__('Nav Show', 'ultraaddons'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'ultraaddons'),
                'label_off'    => esc_html__('No', 'ultraaddons'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );
        $this->add_control(
            '_ua_pagination_type',
            [   
                'label' => esc_html__('Pagination Type', 'ultraaddons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'arrow' => [
                        'title' => esc_html__('Arrow', 'ultraaddons'),
                        'icon' => 'eicon-arrow-circle-left',
                    ],
                    'dot' => [
                        'title' => esc_html__('Dot', 'ultraaddons'),
                        'icon' => 'eicon-dot-circle-o',
                    ],
                ],
                'default' => 'arrow',
                'condition' => [
	                '_ua_timeline_slider_nav_show' =>  [ 'yes' ],
	            ],
            ]
        );
        
		$this->start_controls_tabs( '_ua_timeline_nav_style_tabs' );

		$this->start_controls_tab( '_ua_timeline_nav_style_normal_tab',
			[ 
				'label' => esc_html__( 'Normal', 'ultraaddons'),
			] 
		);

		 $this->add_control(
            '_ua_timeline_nav_normal_color',
            [
                'label' => esc_html__('Color', 'ultraaddons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
					'{{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-section-inner .owl-prev > i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-section-inner .owl-prev > svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-section-inner .owl-next > i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-section-inner .owl-next > svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
	                '_ua_timeline_slider_nav_show'  =>  [ 'yes' ],
	                '_ua_pagination_type'           =>  [ 'arrow'],
	            ],
            ]
        );
		 $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => '_ua_timeline_nav_normal_color_bg',
                'types' => [ 'classic', 'gradient' ],
                'fields_options' => [
					'background' => [
						'label' => __( 'Background Color', 'ultraaddons' ),
					],
				],
                'selector' => '{{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-section-inner .owl-prev, {{WRAPPER}} .ultraaddons-timeline-section .ultraaddons-timeline-section-inner .owl-next',
                'condition' => [
	                '_ua_timeline_slider_nav_show'  =>  [ 'yes' ],
	                '_ua_pagination_type'           =>  [ 'arrow' ],
	            ],
            ]
        );
		 $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => '_ua_timeline_nav_normal_color_bg_dots',
                'types' => [ 'classic', 'gradient' ],
                'fields_options' => [
					'background' => [
						'label' => __( 'Background Color', 'ultraaddons' ),
					],
				],
                'selector' => 
                	'{{WRAPPER}} .ultraaddons-image-carousel-wrap .ultraaddons-pagination-bg .swiper-pagination-bullet:not(.swiper-pagination-bullet-active)',
                
                'condition' => [
	                '_ua_timeline_slider_nav_show'  =>  [ 'yes' ],
	                '_ua_pagination_type'           =>  [ 'dot'  ],
	            ],
            ]
        );
		 $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => '_ua_timeline_nav_normal_border',
                'label' => esc_html__('Border', 'ultraaddons'),
                'selector' => '{{WRAPPER}} .ultraaddons-image-carousel-wrap .ultraaddons-carouse-next-prev',
                'condition' => [
	                '_ua_timeline_slider_nav_show'  =>  [ 'yes' ],
	                '_ua_pagination_type'           =>  [ 'arrow' ],
	            ],
            ]
        );
		 $this->add_responsive_control(
            '_ua_timeline_nav_normal_border_radius',
            [
                'label' => esc_html__('Border Radius', 'ultraaddons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ultraaddons-image-carousel-wrap .ultraaddons-carouse-next-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
	                '_ua_timeline_slider_nav_show' =>  [ 'yes' ],
	            ],
            ]
        );
		$this->end_controls_tab();

		$this->start_controls_tab( '_ua_timeline_nav_style_hover_tab',
			[ 
				'label' => esc_html__( 'Hover', 'ultraaddons')
			] 
		);
		$this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => '_ua_timeline_nav_hover_color_bg_dots',
                'types' => [ 'classic', 'gradient' ],
                'fields_options' => [
					'background' => [
						'label' => __( 'Active Color', 'ultraaddons' ),
					],
				],
                'selector' => 
                	'{{WRAPPER}} .ultraaddons-image-carousel-wrap .ultraaddons-pagination-bg .swiper-pagination-bullet.swiper-pagination-bullet-active',
                
                'condition' => [
	                '_ua_timeline_slider_nav_show'  =>  [ 'yes' ],
	                '_ua_pagination_type'           =>  [ 'dot'  ],
	            ],
            ]
        );
		$this->add_control(
            '_ua_timeline_nav_hover_color',
            [
                'label' => esc_html__('Color', 'ultraaddons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ultraaddons-image-carousel-wrap .ultraaddons-carouse-next-prev:hover > i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ultraaddons-image-carousel-wrap .ultraaddons-carouse-next-prev:hover > svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    '_ua_timeline_slider_nav_show'  =>  [ 'yes' ],
                    '_ua_pagination_type'           =>  [ 'arrow' ],
                ],
            ]
        );
         $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => '_ua_timeline_nav_hover_color_bg',
                'types' => [ 'classic', 'gradient' ],
                'fields_options' => [
                    'background' => [
                        'label' => __( 'Background Color', 'ultraaddons' ),
                    ],
                ],
                'selector' => '{{WRAPPER}} .ultraaddons-image-carousel-wrap .ultraaddons-carouse-next-prev:hover',
                'condition' => [
                    '_ua_timeline_slider_nav_show'  =>  [ 'yes' ],
                    '_ua_pagination_type'           =>  [ 'arrow' ],
                ],
            ]
        );
		$this->end_controls_tab();
				
		$this->end_controls_tabs();

		$this->add_responsive_control(
            '_ua_timeline_nav_position_top',
            [
                'label'       => __('Position', 'ultraaddons'),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => ['px', '%', 'em', 'rem'],
                'range' => [
                    'em' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 0.1,
                    ],
                    'rem' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 0.1,
                    ],
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'render_type' => 'ui',
                'separator' => 'before',
                'default' => [
                        'unit' => '%',
                    ],
                'selectors' => [
                    '{{WRAPPER}} .ultraaddons-image-carousel-wrap .ultraaddons-carouse-next-prev' => 'top: {{SIZE}}{{UNIT}};',
                    '(desktop){{WRAPPER}} .ultraaddons-image-carousel-wrap .ultraaddons-carouse-next-prev' => 'top: {{SIZE}}{{UNIT}};',
                    '(tablet){{WRAPPER}} .ultraaddons-image-carousel-wrap .ultraaddons-carouse-next-prev' => 'top: {{SIZE}}{{UNIT}};',
                    '(mobile){{WRAPPER}} .ultraaddons-image-carousel-wrap .ultraaddons-carouse-next-prev' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
	                '_ua_timeline_slider_nav_show'  =>  [ 'yes' ],
	                '_ua_pagination_type'           =>  [ 'arrow' ],
	            ],
            ]
        );
		$this->add_responsive_control(
        '_ua_timeline_nav_size',
        [
            'label' => __('Icon Size', 'ultraaddons'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '',
                'unit' => 'px',
            ],
            'size_units' => ['px', '%', 'em', 'rem'],
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 0.1,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 0.1,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'render_type' => 'ui',
            'selectors' => [
                '{{WRAPPER}} .ultraaddons-image-carousel-wrap .ultraaddons-carouse-next-prev i' => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .ultraaddons-image-carousel-wrap .ultraaddons-carouse-next-prev img' => 'width: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .ultraaddons-image-carousel-wrap .ultraaddons-carouse-next-prev svg' => 'width: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                '_ua_timeline_slider_nav_show'  =>  [ 'yes' ],
                '_ua_pagination_type'           =>  [ 'arrow' ],
            ],
            
        ]
    	);
		$this->start_controls_tabs( '_ua_timeline_icon_next_prev_style_tabs' );

		$this->start_controls_tab( '_ua_timeline_icon_next_tab',
			[ 
				'label' => esc_html__( 'Next', 'ultraaddons'),
				'condition' => [
	                '_ua_timeline_slider_nav_show'  =>  [ 'yes' ],
	                '_ua_pagination_type'           =>  [ 'arrow' ],
	            ],
			] 
		);

		$this->add_control(
            '_ua_timeline_nav_right_top_ofset',
            [
                'label'        => __('Next', 'ultraaddons'),
                'type'         => Controls_Manager::POPOVER_TOGGLE,
                'label_on'     => __('Custom', 'ultraaddons'),
                'label_off'    => __('None', 'ultraaddons'),
                'return_value' => 'yes',
                'condition' => [
                    '_ua_timeline_slider_nav_show'  =>  [ 'yes' ],
                    '_ua_pagination_type'           =>  [ 'arrow' ],
                ],
            ]
        );
        
        $this->start_popover();
        $this->add_responsive_control(
            '_ua_timeline_nav_right_left',
            [
                'label'      => __('Slide', 'ultraaddons'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem'],
                'range' => [
                    'em' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 0.1,
                    ],
                    'rem' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 0.1,
                    ],
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'render_type' => 'ui',
                'default' => [
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ultraaddons-image-carousel-wrap .ultraaddons-carouse-next' => 'left: {{SIZE}}{{UNIT}};',
                    '(desktop){{WRAPPER}} .ultraaddons-image-carousel-wrap .ultraaddons-carouse-next' => 'left: {{SIZE}}{{UNIT}};',
                    '(tablet){{WRAPPER}} .ultraaddons-image-carousel-wrap .ultraaddons-carouse-next' => 'left: {{SIZE}}{{UNIT}};',
                    '(mobile){{WRAPPER}} .ultraaddons-image-carousel-wrap .ultraaddons-carouse-next' => 'left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    '_ua_timeline_slider_nav_show'  =>  [ 'yes' ],
                    '_ua_pagination_type'           =>  [ 'arrow' ],
                ],
            ]
        );

        $this->end_popover();


        $this->add_control(
            '_ua_timeline_nav_next_icon',
            [
                'label' => __( 'Change Icon', 'ultraaddons' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon_next',
                'default' => [
                    'value' => 'fas fa-angle-right',
                    'library' => 'fa-solid',
                ],
                'recommended' => [
                	'fa-brands' => [
                		'angle-right',
                		'arrow-right',
                		'arrow-circle-right',
                		'arrow-alt-circle-right',
                	],
                	'fa-solid' => [
						'angle-right',
						'arrow-right',
						'arrow-circle-right',
						'arrow-alt-circle-right',
					],
                ],
                'condition' => [
                    '_ua_timeline_slider_nav_show'  =>  [ 'yes' ],
                    '_ua_pagination_type'           =>  [ 'arrow' ],
                ],
            ]
        );

		$this->end_controls_tab();

		$this->start_controls_tab( '_ua_timeline_icon_prev_tab',
			[ 
				'label' => esc_html__( 'Prev', 'ultraaddons'),
				'condition' => [
	                '_ua_timeline_slider_nav_show'  =>  [ 'yes' ],
	                '_ua_pagination_type'           =>  [ 'arrow' ],
	            ],
			] 

		);
		$this->add_control(
            '_ua_timeline_nav_left_top_ofset',
            [
                'label'        => __('Prev', 'ultraaddons'),
                'type'         => Controls_Manager::POPOVER_TOGGLE,
                'label_on'     => __('Custom', 'ultraaddons'),
                'label_off'    => __('None', 'ultraaddons'),
                'return_value' => 'yes',
                'condition' => [
                    '_ua_timeline_slider_nav_show'  =>  [ 'yes' ],
                    '_ua_pagination_type'           =>  [ 'arrow' ],
                ],
            ]
        );
        $this->start_popover();

        
        $this->add_responsive_control(
            '_ua_timeline_nav_left_left',
            [
                'label'      => __('Slide', 'ultraaddons'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem'],
                'condition'  => [
                    '_ua_timeline_nav_left_top_ofset'   =>  [ 'yes' ],
                ],
                'range' => [
                    'em' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 0.1,
                    ],
                    'rem' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 0.1,
                    ],
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'render_type' => 'ui',
                'default' => [
                        'unit' => '%',
                    ],
                'selectors' => [
                    '{{WRAPPER}} .ultraaddons-image-carousel-wrap .ultraaddons-carouse-prev' => 'left: {{SIZE}}{{UNIT}};',
                    '(desktop){{WRAPPER}} .ultraaddons-image-carousel-wrap .ultraaddons-carouse-prev' => 'left: {{SIZE}}{{UNIT}};',
                    '(tablet){{WRAPPER}} .ultraaddons-image-carousel-wrap .ultraaddons-carouse-prev' => 'left: {{SIZE}}{{UNIT}};',
                    '(mobile){{WRAPPER}} .ultraaddons-image-carousel-wrap .ultraaddons-carouse-prev' => 'left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
	                '_ua_timeline_slider_nav_show'  =>  [ 'yes' ],
	                '_ua_pagination_type'           =>  [ 'arrow' ],
	            ],
            ]
        );

        $this->end_popover();

        $this->add_control(
            '_ua_timeline_nav_prev_icon',
            [
                'label' => __( 'Change Icon', 'ultraaddons' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon_prev',
                'default' => [
                    'value' => 'fas fa-angle-left',
                    'library' => 'fa-solid',
                ],
                'recommended' => [
                	'fa-brands' => [
                		'angle-left',
                		'arrow-left',
                		'arrow-circle-left',
                		'arrow-alt-circle-left',
                	],
                	'fa-solid' => [
						'angle-left',
						'arrow-left',
						'arrow-circle-left',
						'arrow-alt-circle-left',
					],
                ],
                'condition' => [
                    '_ua_timeline_slider_nav_show'  =>  [ 'yes' ],
                    '_ua_pagination_type'           =>  [ 'arrow' ],
                ],
            ]
        );
		$this->end_controls_tab();
				
		$this->end_controls_tabs();
        $this->end_controls_section();
    }
}
        